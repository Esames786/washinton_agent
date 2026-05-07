<?php

namespace App\Http\Controllers;

use App\AutoOrder;
use App\AgentOrderPayment;
use App\AgentOrderPaymentJourney;
use App\AgentOrderPaymentLog;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * ADMIN Payment System Controller
 * Permission: 164 (Admin Payment System)
 * Role Admin (role=1) always has access.
 *
 * Admin can:
 *  - View ALL payments from all agents
 *  - Confirm or Return payments
 *  - View payment history/journey
 */
class NewPaymentSystemController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // Permission check helper
    // ─────────────────────────────────────────────────────────────────────────
    private function getPanelType(): int
    {
        $setting = \App\general_setting::first();
        $ptype   = 1;
        $query   = \App\user_setting::where('user_id', Auth::id())
            ->when($setting, fn($q) => $q->where('created_at', '>=', now()->subDays($setting->no_days)))
            ->first();
        if (!empty($query)) {
            $ptype = $query['penal_type'];
        }
        return $ptype;
    }

    private function hasAdminPermission(): bool
    {
        $user = Auth::user();
        if ($user->role == 1) return true; // Admin always has access

        $check_panel = $this->getPanelType();
        $access = match ($check_panel) {
            1 => $user->emp_access_phone,
            2 => $user->emp_access_web,
            3 => $user->emp_access_test,
            4 => $user->panel_type_4,
            5 => $user->panel_type_5,
            6 => $user->panel_type_6,
            default => '',
        };

        return in_array('164', explode(',', $access ?? ''));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // INDEX — list all payments
    // ─────────────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        if (!$this->hasAdminPermission()) {
            return redirect('/dashboard')->with('error', 'Access denied.');
        }

        $query = AgentOrderPayment::with(['agent', 'order'])
            ->orderByDesc('id');

        // Filters
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }
        if ($request->filled('agent_id')) {
            $query->where('user_id', $request->agent_id);
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59',
            ]);
        }
        if ($request->filled('order_ref')) {
            $query->where('listing_order_id', 'like', '%' . $request->order_ref . '%');
        }

        $payments = $query->paginate(25)->withQueryString();

        // Totals for filtered set
        $totals = [
            'total'     => (clone $query)->count(),
            'pending'   => (clone $query)->where('payment_status', 'Payment Pending')->count(),
            'confirmed' => (clone $query)->where('payment_status', 'Payment Confirmed')->count(),
            'returned'  => (clone $query)->where('payment_status', 'Payment Return')->count(),
            'profit'    => (clone $query)->where('payment_status', 'Payment Confirmed')->sum('profit'),
        ];

        $agents = User::where('deleted', 0)->orderBy('name')->get(['id', 'name', 'slug']);

        return view('main.new_payment_system.admin.index', compact('payments', 'totals', 'agents'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SHOW — view single payment detail + journey
    // ─────────────────────────────────────────────────────────────────────────
    public function show($id)
    {
        if (!$this->hasAdminPermission()) {
            return redirect('/dashboard')->with('error', 'Access denied.');
        }

        $payment = AgentOrderPayment::with(['agent', 'order', 'journeys.changedBy', 'logs.changedBy'])
            ->findOrFail($id);

        return view('main.new_payment_system.admin.show', compact('payment'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CONFIRM — admin confirms a payment
    // ─────────────────────────────────────────────────────────────────────────
    public function confirm(Request $request, $id)
    {
        if (!$this->hasAdminPermission()) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        $payment = AgentOrderPayment::findOrFail($id);

        if ($payment->payment_status === 'Payment Confirmed') {
            return response()->json(['success' => false, 'message' => 'Already confirmed.'], 409);
        }

        DB::beginTransaction();
        try {
            $oldStatus = $payment->payment_status;

            $payment->payment_status = 'Payment Confirmed';
            $payment->reviewed_by    = Auth::id();
            $payment->admin_remarks  = $request->remarks ?? null;
            $payment->confirmation_date = $payment->confirmation_date ?? now()->toDateString();
            $payment->save();

            // Journey
            AgentOrderPaymentJourney::create([
                'payment_id'  => $payment->id,
                'old_status'  => $oldStatus,
                'new_status'  => 'Payment Confirmed',
                'changed_by'  => Auth::id(),
                'user_type'   => 'admin',
                'note'        => $request->remarks ?? 'Confirmed by admin',
            ]);

            // Log
            AgentOrderPaymentLog::create([
                'payment_id'         => $payment->id,
                'changed_by'         => Auth::id(),
                'user_type'          => 'admin',
                'old_payment_status' => $oldStatus,
                'action_type'        => 'status_change',
                'note'               => 'Status changed to Payment Confirmed',
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Payment confirmed successfully.']);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Payment confirm error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // RETURN — admin returns a payment for correction
    // ─────────────────────────────────────────────────────────────────────────
    public function returnPayment(Request $request, $id)
    {
        if (!$this->hasAdminPermission()) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        $request->validate(['remarks' => 'required|string|max:500']);

        $payment = AgentOrderPayment::findOrFail($id);

        if ($payment->payment_status === 'Payment Confirmed') {
            return response()->json(['success' => false, 'message' => 'Cannot return a confirmed payment.'], 409);
        }

        DB::beginTransaction();
        try {
            $oldStatus = $payment->payment_status;

            $payment->payment_status = 'Payment Return';
            $payment->reviewed_by    = Auth::id();
            $payment->admin_remarks  = $request->remarks;
            $payment->save();

            AgentOrderPaymentJourney::create([
                'payment_id' => $payment->id,
                'old_status' => $oldStatus,
                'new_status' => 'Payment Return',
                'changed_by' => Auth::id(),
                'user_type'  => 'admin',
                'note'       => $request->remarks,
            ]);

            AgentOrderPaymentLog::create([
                'payment_id'         => $payment->id,
                'changed_by'         => Auth::id(),
                'user_type'          => 'admin',
                'old_payment_status' => $oldStatus,
                'action_type'        => 'status_change',
                'note'               => 'Returned: ' . $request->remarks,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Payment returned for correction.']);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Payment return error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // JOURNEY — AJAX fetch journey for a payment
    // ─────────────────────────────────────────────────────────────────────────
    public function journey($id)
    {
        if (!$this->hasAdminPermission()) {
            return response()->json(['success' => false], 403);
        }

        $journeys = AgentOrderPaymentJourney::with('changedBy')
            ->where('payment_id', $id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($j) {
                return [
                    'new_status'       => $j->new_status,
                    'old_status'       => $j->old_status,
                    'changed_by_name'  => $j->changedBy ? ($j->changedBy->slug ?: $j->changedBy->name) : ($j->changed_by ? '#'.$j->changed_by : '-'),
                    'user_type'        => $j->user_type,
                    'note'             => $j->note,
                    'created_at'       => $j->created_at ? $j->created_at->format('M d, Y H:i') : '-',
                ];
            });

        return response()->json($journeys);
    }
}
