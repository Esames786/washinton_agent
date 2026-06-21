<?php

namespace App\Http\Controllers;

use App\CrApplication;
use App\Mail\CrApplicationApprovedMail;
use App\Mail\CrApplicationRejectedMail;
use App\Mail\WelcomeEmail;
use App\role;
use App\Services\HrPortalBridgeService;
use App\User;
use App\user_setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CrApplicationController extends Controller
{
    // Permission code for CrazyRays Applications access
    private const PERMISSION_CODE = '166';

    // Reference user for permission copying (Order Taker default)
    private const AGENT_REFERENCE_USER_ID = 130;

    private const PERMISSION_COLUMNS = [
        'emp_access_phone', 'emp_access_web', 'emp_access_test',
        'panel_type_4', 'panel_type_5', 'panel_type_6',
        'emp_panel_access', 'emp_show_data', 'emp_access_ship',
        'emp_access_profile', 'emp_access_action', 'emp_access_report',
        'emp_access_guide', 'order_taker_quote', 'assign_daily_qoute',
        'sheet_access',
    ];

    public function __construct(protected HrPortalBridgeService $hrBridge)
    {
        $this->middleware('auth');
    }

    private function hasAccess(): bool
    {
        $user = Auth::user();
        if ((int) $user->role === 1) return true;

        $setting = \App\user_setting::where('user_id', $user->id)->first();
        $ptype   = $setting ? (int) $setting->penal_type : 1;

        $col = match ($ptype) {
            2 => 'emp_access_web',
            3 => 'emp_access_test',
            4 => 'panel_type_4',
            5 => 'panel_type_5',
            6 => 'panel_type_6',
            default => 'emp_access_phone',
        };

        return in_array(self::PERMISSION_CODE, explode(',', $user->$col ?? ''));
    }

    public function index(Request $request)
    {
        abort_unless($this->hasAccess(), 403);

        $query = CrApplication::latest();

        if ($request->filled('campaign')) {
            $query->where('campaign', $request->campaign);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('full_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        $applications = $query->paginate(20)->withQueryString();
        $campaigns    = CrApplication::$campaigns;

        return view('main.cr_applications.index', compact('applications', 'campaigns'));
    }

    public function show(int $id)
    {
        abort_unless($this->hasAccess(), 403);
        $application = CrApplication::findOrFail($id);
        return view('main.cr_applications.show', compact('application'));
    }

    public function approve(int $id)
    {
        abort_unless($this->hasAccess(), 403);

        $application = CrApplication::findOrFail($id);

        if (!$application->isPending()) {
            return back()->with('error', 'Application is no longer pending.');
        }

        // Determine role (Logistics campaign → Order Taker, others → Order Taker too)
        $roleName = 'Order Taker';
        $role     = role::where('name', $roleName)->first();

        if (!$role) {
            return back()->with('error', 'Role configuration error. Please contact support.');
        }

        $referenceUser = User::find(self::AGENT_REFERENCE_USER_ID);

        DB::beginTransaction();
        try {
            // Guard: if a user with this email already exists, link to them instead of creating a duplicate
            $existingUser = User::where('email', $application->email)->first();
            if ($existingUser) {
                $application->status = 'approved';
                $application->save();
                DB::commit();
                return back()->with('error', 'A user with this email (' . $application->email . ') already exists (ID #' . $existingUser->id . '). Application marked approved but no new account was created.');
            }

            // Auto-generate slug from name
            $base = Str::slug($application->full_name);
            $slug = $base;
            $i = 1;
            while (User::where('slug', $slug)->exists()) {
                $slug = $base . $i++;
            }

            // Create the User
            $user           = new User();
            $user->name     = $application->full_name;
            $user->last_name= '';
            $user->slug     = $slug;
            $user->email    = $application->email;
            $user->password = $application->password ?? Hash::make(Str::random(12));
            $user->phone    = $application->phone;
            $user->address  = $application->address ?? '';
            $user->role     = $role->id;
            $user->status   = 1; // Active immediately on admin approval
            $user->verify   = 1;
            $user->is_crazyrays = 1; // Originated from a CrazyRays campaign application

            if ($referenceUser) {
                foreach (self::PERMISSION_COLUMNS as $col) {
                    $user->$col = $referenceUser->$col;
                }
            }
            $user->order_taker_quote = 1; // Own quotes default

            $user->save();

            // user_settings
            $setting             = new user_setting();
            $setting->user_id    = $user->id;
            $setting->penal_type = 1;
            $setting->call_type  = 134;
            $setting->save();

            // Mirror to HR portal — pass contract_accepted_at so no blocking modal
            try {
                $this->hrBridge->createEmployee([
                    'name'                 => $application->full_name,
                    'email'                => $application->email,
                    'password'             => Str::random(12), // HR gets its own password
                    'phone'                => $application->phone,
                    'address'              => $application->address,
                    'country'              => $application->country,
                    'user_type'            => 'agent',
                    'agent_id'             => $user->id,
                    'shift_type_id'        => 1,
                    'account_type_id'      => 3,
                    'father_name'          => $application->father_name,
                    'dob'                  => $application->dob?->format('Y-m-d'),
                    'gender'               => $application->gender,
                    'marital_status'       => $application->marital_status,
                    'city'                 => $application->city,
                    'state'                => $application->state,
                    'contract_accepted_at' => $application->contract_accepted_at?->toDateTimeString(),
                ]);
            } catch (\Throwable $e) {
                Log::warning('CrApplicationController approve: HR portal sync failed', [
                    'application_id' => $application->id,
                    'user_id'        => $user->id,
                    'error'          => $e->getMessage(),
                ]);
            }

            // Mark application as approved
            $application->update([
                'status'   => 'approved',
                'agent_id' => $user->id,
            ]);

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('CrApplicationController approve failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to convert application: ' . $e->getMessage());
        }

        // Transfer documents to HR portal (non-blocking)
        // hr_document_settings IDs: 12=Resume, others match doc_id stored in cr_applications.documents
        try {
            $docsToTransfer = [];

            if ($application->resume_path) {
                $fullPath = storage_path('app/public/' . $application->resume_path);
                if (file_exists($fullPath)) {
                    $ext = pathinfo($fullPath, PATHINFO_EXTENSION) ?: 'pdf';
                    $docsToTransfer[] = [
                        'doc_id'   => 12,
                        'filename' => 'resume.' . $ext,
                        'mime_type'=> mime_content_type($fullPath) ?: 'application/octet-stream',
                        'content'  => base64_encode(file_get_contents($fullPath)),
                    ];
                }
            }

            foreach ($application->documents ?? [] as $doc) {
                if (empty($doc['path']) || empty($doc['doc_id'])) continue;
                $fullPath = storage_path('app/public/' . $doc['path']);
                if (!file_exists($fullPath)) continue;
                $docsToTransfer[] = [
                    'doc_id'   => (int) $doc['doc_id'],
                    'filename' => $doc['filename'] ?? basename($doc['path']),
                    'mime_type'=> mime_content_type($fullPath) ?: 'application/octet-stream',
                    'content'  => base64_encode(file_get_contents($fullPath)),
                ];
            }

            if ($docsToTransfer) {
                $this->hrBridge->attachDocuments($user->id, $docsToTransfer);
            }
        } catch (\Throwable $e) {
            Log::warning('CrApplicationController approve: document transfer to HR failed', [
                'application_id' => $application->id,
                'user_id'        => $user->id,
                'error'          => $e->getMessage(),
            ]);
        }

        // Send approval email from CrazyRays (non-blocking)
        try {
            Mail::to($application->email)
                ->send(new CrApplicationApprovedMail($application, rtrim(config('bridge.crazyrays.base_url', url('/')), '/')));
        } catch (\Throwable $e) {
            Log::warning('CrApplicationController: approval email failed', ['error' => $e->getMessage()]);
        }

        return redirect()->route('cr-applications.index')
            ->with('success', "Application approved. User account created for {$application->full_name}.");
    }

    public function reject(Request $request, int $id)
    {
        abort_unless($this->hasAccess(), 403);

        $application = CrApplication::findOrFail($id);

        if (!$application->isPending()) {
            return response()->json(['success' => false, 'message' => 'Application is no longer pending.'], 422);
        }

        $application->update([
            'status'         => 'rejected',
            'rejection_note' => $request->input('rejection_note', ''),
        ]);

        // Send rejection email (non-blocking)
        try {
            Mail::to($application->email)->send(new CrApplicationRejectedMail($application));
        } catch (\Throwable $e) {
            Log::warning('CrApplicationController: rejection email failed', ['error' => $e->getMessage()]);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Application rejected.']);
        }

        return redirect()->route('cr-applications.index')
            ->with('success', "Application for {$application->full_name} has been rejected.");
    }
}
