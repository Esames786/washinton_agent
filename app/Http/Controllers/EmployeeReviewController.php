<?php

namespace App\Http\Controllers;

use App\Mail\AgentActivatedEmail;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmployeeReviewController extends Controller
{
    /**
     * Client rule: Hello Transport agents are handled by an ADMIN only — account approval /
     * activation, W-9, NDA and contract assignment. Managers keep their existing rights over
     * CrazyRays agents. Returns an error response when the actor may not act on this target,
     * or null when the action is allowed.
     */
    public static function denyIfHelloAndNotAdmin($userId, bool $allowManager = true): ?JsonResponse
    {
        $actor = Auth::user();
        if ($actor && ((int) ($actor->role ?? 0) === 1 || optional($actor->userRole)->name === 'Admin')) {
            return null; // admins may do everything
        }

        // 2026-08-21: managers were blocked from activating Hello agents, which stalled onboarding.
        // Managers may now handle Hello agents (activation / HR status / NDA / contract).
        // The W-9 stays ADMIN-ONLY — it passes $allowManager = false.
        if ($allowManager && $actor
            && ((int) ($actor->role ?? 0) === 9 || optional($actor->userRole)->name === 'Manager')) {
            return null;
        }

        $target = User::find($userId);
        if ($target && (int) ($target->is_crazyrays ?? 0) !== 1) {
            return response()->json([
                'error' => 'Hello Transport agents are managed by an administrator only. Please ask an admin to complete this action.',
            ], 403);
        }

        return null; // CrazyRays agent — unchanged manager workflow
    }

    public function data(int $userId): JsonResponse
    {
        $agentUser = User::find($userId);
        if (!$agentUser) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $hrEmp = DB::table('hr_employees as e')
            ->leftJoin('hr_employee_statuses as s',  'e.employee_status_id', '=', 's.id')
            ->leftJoin('hr_shift_types as sh',        'e.shift_id',           '=', 'sh.id')
            ->leftJoin('hr_departments as d',          'e.department_id',      '=', 'd.id')
            ->leftJoin('hr_designations as dg',        'e.designation_id',     '=', 'dg.id')
            ->leftJoin('hr_employment_types as et',    'e.employment_type_id', '=', 'et.id')
            ->leftJoin('hr_commission_settings as cs', 'e.commission_id',      '=', 'cs.id')
            ->leftJoin('hr_commission_types as ct',    'cs.commission_type_id','=', 'ct.id')
            ->select(
                'e.id as hr_id',
                'e.full_name', 'e.email', 'e.phone', 'e.cnic', 'e.address',
                'e.profile_path', 'e.joining_date', 'e.basic_salary',
                'e.employee_code', 'e.gender', 'e.dob', 'e.city', 'e.state', 'e.country',
                'e.employee_status_id', 'e.father_name', 'e.marital_status',
                'e.skills', 'e.mother_name', 'e.phone2', 'e.emergency_contact',
                's.name as hr_status_name',
                'sh.name as shift_name', 'sh.shift_start', 'sh.shift_end',
                'd.name as department_name',
                'dg.name as designation_name',
                'et.name as employment_type_name',
                'cs.title as commission_title', 'cs.value as commission_value', 'cs.description as commission_desc',
                'ct.name as commission_type_name',
                'e.contract', 'e.contract_updated_at', 'e.contract_accepted_at'
            )
            ->where('e.agent_id', $userId)
            ->first();

        $documents   = [];
        $leaveQuotas = [];
        $hrStatuses  = DB::table('hr_employee_statuses')->select('id', 'name')->get();

        $equipment = [];

        if ($hrEmp) {
            $documents = DB::table('hr_employee_documents as doc')
                ->leftJoin('hr_document_settings as ds', 'doc.document_setting_id', '=', 'ds.id')
                ->select('doc.id', 'doc.file_path', 'doc.file_name', 'doc.mime_type', 'doc.status', 'ds.title as doc_type')
                ->where('doc.employee_id', $hrEmp->hr_id)
                ->get();

            $leaveQuotas = DB::table('hr_employee_assign_leaves as al')
                ->leftJoin('hr_leave_types as lt', 'al.leave_type_id', '=', 'lt.id')
                ->select(
                    'lt.name as leave_type',
                    'al.assigned_quota', 'al.used_quota',
                    'al.valid_from', 'al.valid_to', 'al.status'
                )
                ->where('al.employee_id', $hrEmp->hr_id)
                ->where('al.status', 1)
                ->orderBy('lt.name')
                ->get();

            $equipment = DB::table('employee_equipment as ee')
                ->leftJoin('equipment_types as et', 'ee.equipment_type_id', '=', 'et.id')
                ->select(
                    'ee.id', 'et.name as type_name', 'et.icon as type_icon',
                    'ee.asset_name', 'ee.serial_number',
                    'ee.assigned_date', 'ee.return_date', 'ee.status', 'ee.notes'
                )
                ->where('ee.employee_id', $hrEmp->hr_id)
                ->orderBy('ee.assigned_date', 'desc')
                ->get();
        }

        // Working-time (active cursor/keyboard seconds) — today + all-time total
        $todaySeconds = (int) (DB::table('agent_active_times')
            ->where('user_id', $userId)->where('work_date', date('Y-m-d'))->value('active_seconds') ?? 0);
        $totalSeconds = (int) (DB::table('agent_active_times')
            ->where('user_id', $userId)->sum('active_seconds') ?? 0);

        // NDA rich-text state (admin's editable copy + signed details) lives on hr_employees.
        $ndaRow = DB::table('hr_employees')->where('agent_id', $agentUser->id)
            ->first(['nda_content', 'nda_signature', 'nda_signed_ip', 'nda_cnic_front', 'nda_cnic_back', 'nda_document_url', 'nda_father_name', 'nda_address']);

        // Re-brand the stored agreements for the AGENT being viewed (not for this portal's brand),
        // so a Hello agent's NDA/contract never shows CrazyRays wording — and vice-versa.
        $agentBrand = \App\Support\Brand::for($agentUser);
        $ndaContent = $ndaRow->nda_content ?? null;
        if ($ndaContent) {
            $ndaContent = \App\Support\Brand::applyTokens($ndaContent, $agentBrand);
        }
        if ($hrEmp && !empty($hrEmp->contract)) {
            $hrEmp->contract = \App\Support\Brand::applyTokens($hrEmp->contract, $agentBrand);
        }

        return response()->json([
            'agent'       => [
                'id'              => $agentUser->id,
                'name'            => $agentUser->name,
                'slug'            => $agentUser->slug,
                'email'           => $agentUser->email,
                'phone'           => $agentUser->phone,
                'status'          => (int) $agentUser->status,
                // Which company this agent belongs to (Hello vs CrazyRays).
                'brand_key'       => $agentBrand['key']  ?? 'hellotransport',
                'brand_name'      => $agentBrand['name'] ?? 'Hello Transport',
                'nda_required'    => (int) ($agentUser->nda_required ?? 0),
                'w9_required'     => (int) ($agentUser->w9_required ?? 0),
                'nda_signed_at'   => $agentUser->nda_signed_at ?? null,
                'nda_document_path' => $agentUser->nda_document_path ?? null,
                'nda_content'     => $ndaContent,
                'nda_signature'   => $ndaRow->nda_signature   ?? null,
                'nda_signed_ip'   => $ndaRow->nda_signed_ip   ?? null,
                'nda_cnic_front'  => $ndaRow->nda_cnic_front  ?? null,
                'nda_cnic_back'   => $ndaRow->nda_cnic_back   ?? null,
                'nda_document_url' => $ndaRow->nda_document_url ?? null,
                'nda_father_name' => $ndaRow->nda_father_name ?? null,
                'nda_address'     => $ndaRow->nda_address     ?? null,
                // W-9 (US / Hello agents). Only ever the masked TIN — never the full number.
                'w9'              => ($w9 = \App\W9Form::where('user_id', $agentUser->id)->latest('id')->first())
                    ? [
                        'submitted_at'   => optional($w9->signed_at)->format('d M Y, h:i A'),
                        'legal_name'     => $w9->legal_name,
                        'classification' => $w9->classificationLabel(),
                        'tin_masked'     => $w9->maskedTin(),
                        'address'        => trim($w9->address . ', ' . $w9->city . ', ' . $w9->state . ' ' . $w9->zip),
                        'signed_ip'      => $w9->signed_ip,
                    ]
                    : null,
            ],
            'hr_employee'  => $hrEmp,
            'documents'    => $documents,
            'leave_quotas' => $leaveQuotas,
            'hr_statuses'  => $hrStatuses,
            'hr_base_url'  => rtrim((string) config('bridge.hrportal.base_url'), '/'),
            'equipment'    => $equipment,
            'working_time' => [
                'today_seconds' => $todaySeconds,
                'today_human'   => \App\AgentActiveTime::format($todaySeconds),
                'total_seconds' => $totalSeconds,
                'total_human'   => \App\AgentActiveTime::format($totalSeconds),
            ],
        ]);
    }

    public function changeAgentStatus(Request $request): JsonResponse
    {
        $request->validate(['user_id' => 'required|integer', 'status' => 'required|in:0,1']);

        if ($deny = self::denyIfHelloAndNotAdmin($request->user_id)) {
            return $deny;
        }

        $user = User::findOrFail($request->user_id);
        $user->status = $request->status;
        $user->save();

        $emailNote = null;
        if ((int) $request->status === 1) {
            try {
                Mail::to($user->email)->send(new AgentActivatedEmail($user->name, $user->email, \App\Support\Brand::for($user)));
                $emailNote = 'Activation email sent to ' . $user->email;
            } catch (\Throwable $e) {
                Log::warning('changeAgentStatus: activation email failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
                $emailNote = 'Email failed: ' . $e->getMessage();
            }
        }

        return response()->json(['success' => true, 'status' => (int) $user->status, 'email_note' => $emailNote]);
    }

    public function changeHrStatus(Request $request): JsonResponse
    {
        $request->validate(['user_id' => 'required|integer', 'hr_status_id' => 'required|integer']);

        if ($deny = self::denyIfHelloAndNotAdmin($request->user_id)) {
            return $deny;
        }

        $hrEmployee = DB::table('hr_employees')->where('agent_id', $request->user_id)->first();
        if (!$hrEmployee) {
            return response()->json(['error' => 'HR employee not found.'], 404);
        }

        // Block activation if any uploaded documents are unverified
        if ((int) $request->hr_status_id === 1) {
            $totalDocs      = DB::table('hr_employee_documents')->where('employee_id', $hrEmployee->id)->count();
            $unverifiedDocs = DB::table('hr_employee_documents')->where('employee_id', $hrEmployee->id)->where('status', 0)->count();
            if ($totalDocs > 0 && $unverifiedDocs > 0) {
                return response()->json([
                    'error' => "Cannot activate: {$unverifiedDocs} document(s) are not verified. Please verify all documents before activating."
                ], 422);
            }
        }

        $updated = DB::table('hr_employees')
            ->where('agent_id', $request->user_id)
            ->update(['employee_status_id' => $request->hr_status_id]);

        if (!$updated) {
            return response()->json(['error' => 'HR employee not found.'], 404);
        }

        $statusName = DB::table('hr_employee_statuses')->where('id', $request->hr_status_id)->value('name');

        if ((int) $request->hr_status_id === 1) {
            try {
                $agentUser = User::find($request->user_id);
                $emailTo   = $hrEmployee->email ?: ($agentUser ? $agentUser->email : null);
                $nameTo    = $hrEmployee->full_name ?: ($agentUser ? $agentUser->name : 'Employee');
                if ($emailTo) {
                    // Per-PERSON brand: a Hello agent must get the hr.hellotransport.com login link
                    // even when activated from florida (the old deployment-level URL sent everyone
                    // to hr.crazyrayssolutions.com.pk).
                    $empBrand = \App\Support\Brand::for($agentUser);
                    Mail::mailer(\App\Support\Brand::mailer($empBrand))
                        ->send('emails.hr_activated_agent', [
                            'employeeName'  => $nameTo,
                            'employeeEmail' => $emailTo,
                            'brand'         => $empBrand,
                            'hrLoginUrl'    => $empBrand['hr_login_url'] ?? (rtrim((string) config('bridge.hrportal.base_url'), '/') . '/subcontractor/login'),
                        ], function ($m) use ($emailTo, $empBrand) {
                            [$fromAddr, $fromName] = \App\Support\Brand::mailFrom($empBrand);
                            $m->from($fromAddr, $fromName)
                              ->to($emailTo)
                              ->subject('Your ' . ($empBrand['name'] ?? 'Hello Transport') . ' HR Account is Now Active!');
                        });
                }
            } catch (\Throwable $e) {
                Log::warning('changeHrStatus: HR activation email failed', ['user_id' => $request->user_id, 'error' => $e->getMessage()]);
            }
        }

        return response()->json(['success' => true, 'hr_status_name' => $statusName]);
    }

    public function verifyDocument(Request $request): JsonResponse
    {
        $request->validate(['doc_id' => 'required|integer', 'status' => 'required|in:0,1']);

        $updated = DB::table('hr_employee_documents')
            ->where('id', $request->doc_id)
            ->update(['status' => $request->status]);

        if (!$updated) {
            return response()->json(['error' => 'Document not found.'], 404);
        }

        return response()->json(['success' => true]);
    }

    public function bulkVerifyDocuments(Request $request): JsonResponse
    {
        $request->validate(['hr_employee_id' => 'required|integer']);

        DB::table('hr_employee_documents')
            ->where('employee_id', $request->hr_employee_id)
            ->update(['status' => 1]);

        return response()->json(['success' => true, 'message' => 'All documents verified']);
    }

    public function saveContract(Request $request): JsonResponse
    {
        $request->validate(['user_id' => 'required|integer', 'contract' => 'required|string']);

        if ($deny = self::denyIfHelloAndNotAdmin($request->user_id)) {
            return $deny;
        }

        $hrEmployee = DB::table('hr_employees')->where('agent_id', $request->user_id)->first();
        if (!$hrEmployee) {
            return response()->json(['error' => 'HR employee not found.'], 404);
        }

        DB::table('hr_employees')->where('agent_id', $request->user_id)->update([
            'contract'             => $request->contract,
            'contract_updated_at'  => now(),
            'contract_accepted_at' => null,
        ]);

        // Notify employee
        DB::table('notifications')->insert([
            'issueId'    => $hrEmployee->id,
            'userId'     => $request->user_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Email the agent that a contract is awaiting their acceptance (non-blocking, brand-aware)
        try {
            $agent = User::find($request->user_id);
            if ($agent && $agent->email) {
                Mail::to($agent->email)->send(
                    new \App\Mail\AgentActionRequiredMail($agent->name, \App\Support\Brand::for($agent), 'contract')
                );
            }
        } catch (\Throwable $e) {
            Log::warning('saveContract: notify email failed', ['user_id' => $request->user_id, 'error' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'message' => 'Contract saved successfully. The employee has been emailed to review and accept.']);
    }

    public function acceptContract(Request $request): JsonResponse
    {
        $request->validate([
            'user_id'        => 'required|integer',
            // #4: contract is e-signed like the NDA. Nullable so an old cached modal still works.
            'signature_data' => 'nullable|string',
        ]);

        $update = ['contract_accepted_at' => now()];

        // Store the drawn signature + the IP it was signed from (columns are guarded so a
        // pre-migration DB can still accept).
        if ($request->filled('signature_data')) {
            $sigBinary = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $request->signature_data));
            if ($sigBinary && strlen($sigBinary) >= 100) {
                if (\Illuminate\Support\Facades\Schema::hasColumn('hr_employees', 'contract_signature')) {
                    $update['contract_signature'] = $request->signature_data;
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('hr_employees', 'contract_signed_ip')) {
                    $update['contract_signed_ip'] = $request->ip();
                }
            }
        }

        $affected = DB::table('hr_employees')->where('agent_id', $request->user_id)
            ->update($update);

        if (!$affected) {
            return response()->json(['error' => 'HR employee not found.'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Contract accepted']);
    }

    public function defaultContract(Request $request): JsonResponse
    {
        $tpl = \App\ContractTemplate::getDefault();

        // Brand the preview for the agent being viewed (if a user_id is supplied),
        // so admins setting up a CrazyRays agent see the correct company name.
        $viewed  = $request->filled('user_id') ? User::find($request->input('user_id')) : null;
        $brand   = \App\Support\Brand::for($viewed);
        $content = $tpl ? \App\Support\Brand::applyTokens($tpl->content, $brand) : '';

        return response()->json([
            'title'   => $tpl ? $tpl->title : 'Employee Contract',
            'content' => $content,
        ]);
    }

    public function publicDefaultContract(): JsonResponse
    {
        $tpl = \App\ContractTemplate::getDefault();

        // This endpoint is consumed by crazyrayssolutions.com.pk's signup/careers
        // form, so the contract must always carry CrazyRays branding.
        $brand   = \App\Support\Brand::byKey('crazyrays');
        $content = $tpl ? \App\Support\Brand::applyTokens($tpl->content, $brand) : '';

        return response()->json([
            'title'   => $tpl ? $tpl->title : 'Terms & Conditions',
            'content' => $content,
        ]);
    }

    /**
     * Return the default NDA template (brand-applied) for the rich-text editor — mirrors
     * defaultContract(). Admins load this into the editor, tweak it, then saveNda().
     */
    public function defaultNda(Request $request): JsonResponse
    {
        $tpl = \App\NdaTemplate::getDefault();

        $viewed  = $request->filled('user_id') ? User::find($request->input('user_id')) : null;
        $brand   = \App\Support\Brand::for($viewed);
        $content = $tpl ? \App\Support\Brand::applyTokens($tpl->content, $brand) : '';

        return response()->json([
            'title'   => $tpl ? $tpl->title : 'Non-Disclosure Agreement',
            'content' => $content,
        ]);
    }

    /**
     * Save the admin's NDA copy for this agent and (re)require signing — mirrors saveContract().
     * Any previous signature is cleared so the agent re-signs the new copy.
     */
    public function saveNda(Request $request): JsonResponse
    {
        $request->validate(['user_id' => 'required|integer', 'nda' => 'required|string']);

        if ($deny = self::denyIfHelloAndNotAdmin($request->user_id)) {
            return $deny;
        }

        $hrEmployee = DB::table('hr_employees')->where('agent_id', $request->user_id)->first();
        if (!$hrEmployee) {
            return response()->json(['error' => 'HR employee not found.'], 404);
        }

        DB::table('hr_employees')->where('agent_id', $request->user_id)->update([
            'nda_content'      => $request->nda,
            'nda_required'     => 1,
            'nda_signed_at'    => null,
            'nda_signature'    => null,
            'nda_signed_ip'    => null,
            'nda_document_url' => null,
        ]);

        $user = User::find($request->user_id);
        if ($user) {
            $user->nda_required      = 1;
            $user->nda_signed_at     = null;
            $user->nda_document_path = null;
            $user->save();

            if ($user->email) {
                try {
                    Mail::to($user->email)->send(
                        new \App\Mail\AgentActionRequiredMail($user->name, \App\Support\Brand::for($user), 'nda')
                    );
                } catch (\Throwable $e) {
                    Log::warning('saveNda: notify email failed', ['user_id' => $request->user_id, 'error' => $e->getMessage()]);
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'NDA saved. The agent has been asked to review and sign it.']);
    }

    public function requireNda(Request $request): JsonResponse
    {
        $request->validate([
            'user_id'      => 'required|integer',
            'nda_required' => 'required|in:0,1',
        ]);

        if ($deny = self::denyIfHelloAndNotAdmin($request->user_id)) {
            return $deny;
        }

        $user = User::findOrFail($request->user_id);

        // Cannot re-require NDA if already signed and admin is trying to set 1 again — allowed
        $user->nda_required = (int) $request->nda_required;

        // If admin un-requires (sets 0), also clear the signed record
        if ((int) $request->nda_required === 0) {
            $user->nda_signed_at      = null;
            $user->nda_document_path  = null;
        }

        $user->save();

        // Mirror to hr_employees (same DB)
        DB::table('hr_employees')
            ->where('agent_id', $request->user_id)
            ->update(['nda_required' => (int) $request->nda_required]);

        // Email the agent when an NDA is newly required (non-blocking, brand-aware)
        if ((int) $request->nda_required === 1 && $user->email) {
            try {
                Mail::to($user->email)->send(
                    new \App\Mail\AgentActionRequiredMail($user->name, \App\Support\Brand::for($user), 'nda')
                );
            } catch (\Throwable $e) {
                Log::warning('requireNda: notify email failed', ['user_id' => $request->user_id, 'error' => $e->getMessage()]);
            }
        }

        return response()->json([
            'success'      => true,
            'nda_required' => (int) $user->nda_required,
        ]);
    }
}
