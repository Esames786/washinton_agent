<?php

namespace App\Http\Controllers;

use App\AutoOrder;
use App\AgentOrderPayment;
use App\AgentOrderPaymentJourney;
use App\AgentOrderPaymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * AGENT Payment Controller
 * Permission: 165 (Agent Payment System)
 * Role Admin (role=1) always has access.
 *
 * Agent can:
 *  - View their own submitted payments
 *  - Submit new payment with screenshot
 *  - Edit/resubmit returned payments
 *  - View journey history of their payments
 */
class AgentPaymentController extends Controller
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

    private function hasAgentPermission(): bool
    {
        $user = Auth::user();
        if ($user->role == 1) return true;

        $check_panel = $this->getPanelType();
        $access = match ($check_panel) {
            1 => $user->emp_access_phone,
            2 => $user->emp_access_web,
            3 => $user->emp_access_test,
            4 => $user->panel_type_4,
            5 => $user->panel_type_5,
            6 => $user->panel_type_6,
            default => (string) $user->accessForPanel($check_panel), // B6: new dynamic panels (7+)
        };

        return in_array('165', explode(',', $access ?? ''));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // INDEX — agent's own payments
    // ─────────────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        if (!$this->hasAgentPermission()) {
            return redirect('/dashboard')->with('error', 'Access denied.');
        }

        $user = Auth::user();

        $query = AgentOrderPayment::with(['order'])
            ->where('user_id', $user->id)
            ->orderByDesc('id');

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59',
            ]);
        }

        $payments = $query->paginate(20)->withQueryString();

        $totals = [
            'pending'   => AgentOrderPayment::where('user_id', $user->id)->where('payment_status', 'Confirmation Pending')->count(),
            'confirmed' => AgentOrderPayment::where('user_id', $user->id)->where('payment_status', 'Payment Confirmed')->count(),
            'returned'  => AgentOrderPayment::where('user_id', $user->id)->where('payment_status', 'Payment Return')->count(),
            'profit'    => AgentOrderPayment::where('user_id', $user->id)->where('payment_status', 'Payment Confirmed')->sum('profit'),
        ];

        return view('main.new_payment_system.agent.index', compact('payments', 'totals'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CREATE — show form to submit new payment
    // ─────────────────────────────────────────────────────────────────────────
    public function create()
    {
        if (!$this->hasAgentPermission()) {
            return redirect('/dashboard')->with('error', 'Access denied.');
        }

        return view('main.new_payment_system.agent.create');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STORE — submit new payment
    // ─────────────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        if (!$this->hasAgentPermission()) {
            return redirect('/dashboard')->with('error', 'Access denied.');
        }

        $request->validate([
            'order_ref'         => 'required|string|max:100',
            'payment_mode'      => 'required|string|max:100',
            'book_price'        => 'required|numeric|min:0',
            'carrier_price'     => 'required|numeric|min:0',
            'confirmation_date' => 'required|date',
            // #11: payment screenshot / proof is now mandatory too
            'screenshot_path'   => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            // Booking/authorization form is now OPTIONAL (per client).
            'booking_form_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'details'           => 'nullable|string|max:1000',
        ], [
            'screenshot_path.required'   => 'The payment screenshot / proof is required to submit a payment.',
        ]);

        // Verify the order exists.
        $order = AutoOrder::find($request->order_ref);

        // #9 client request: an "own quotes only" agent may ONLY submit payment for
        // their own orders (created by them, they are the manager, or assigned via u_id).
        // Admin / managers (order_taker_quote != 1) can submit for any order.
        if ($order && Auth::user()->order_taker_quote == 1) {
            $uid   = (int) Auth::id();
            $owns  = ((int) $order->order_taker_id === $uid)
                  || ((int) $order->manager_id === $uid)
                  || in_array((string) $uid, array_filter(explode(',', (string) $order->u_id)), true);
            if (!$owns) {
                $order = null;
            }
        }

        if (!$order) {
            return back()->with('error', 'Order not found or it does not belong to you.')->withInput();
        }

        DB::beginTransaction();
        try {
            $profit = (float)$request->book_price - (float)$request->carrier_price;

            $payment = new AgentOrderPayment();
            $payment->order_id          = $order ? $order->id : null;
            $payment->listing_order_id  = $request->order_ref;
            $payment->user_id           = Auth::id();
            $payment->payment_mode      = $request->payment_mode;
            $payment->payment_type      = $request->payment_type;
            $payment->book_price        = $request->book_price;
            $payment->carrier_price     = $request->carrier_price;
            $payment->profit            = $profit;
            $payment->confirmation_date = $request->confirmation_date;
            $payment->details           = $request->details;
            $payment->payment_status    = 'Confirmation Pending';
            $payment->save();

            // Handle screenshot upload
            if ($request->hasFile('screenshot_path')) {
                $folder = public_path("Uploads/PaymentScreenShot/{$payment->id}");
                if (!file_exists($folder)) mkdir($folder, 0777, true);

                $file     = $request->file('screenshot_path');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($folder, $filename);

                $payment->screenshot_path = "Uploads/PaymentScreenShot/{$payment->id}/{$filename}";
                $payment->save();
            }

            // #3: handle mandatory booking / authorization form upload
            if ($request->hasFile('booking_form_path')) {
                $folder = public_path("Uploads/PaymentBookingForm/{$payment->id}");
                if (!file_exists($folder)) mkdir($folder, 0777, true);

                $file     = $request->file('booking_form_path');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($folder, $filename);

                $payment->booking_form_path = "Uploads/PaymentBookingForm/{$payment->id}/{$filename}";
                $payment->save();
            }

            // Initial journey entry
            AgentOrderPaymentJourney::create([
                'payment_id' => $payment->id,
                'old_status' => null,
                'new_status' => 'Confirmation Pending',
                'changed_by' => Auth::id(),
                'user_type'  => 'user',
                'note'       => 'Payment submitted by agent',
            ]);

            // Log
            AgentOrderPaymentLog::create([
                'payment_id'  => $payment->id,
                'changed_by'  => Auth::id(),
                'user_type'   => 'user',
                'action_type' => 'create',
                'note'        => 'Payment created',
            ]);

            DB::commit();
            return redirect()->route('agent.payments.index')
                ->with('success', 'Payment submitted successfully! Awaiting admin review.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Agent payment store error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong. Please try again.')->withInput();
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // EDIT — show edit form (only for returned payments)
    // ─────────────────────────────────────────────────────────────────────────
    public function edit($id)
    {
        if (!$this->hasAgentPermission()) {
            return redirect('/dashboard')->with('error', 'Access denied.');
        }

        $payment = AgentOrderPayment::where('user_id', Auth::id())->findOrFail($id);

        if ($payment->payment_status === 'Payment Confirmed') {
            return back()->with('error', 'Confirmed payments cannot be edited.');
        }

        return view('main.new_payment_system.agent.edit', compact('payment'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // UPDATE — resubmit a returned payment
    // ─────────────────────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        if (!$this->hasAgentPermission()) {
            return redirect('/dashboard')->with('error', 'Access denied.');
        }

        $payment = AgentOrderPayment::where('user_id', Auth::id())->findOrFail($id);

        if ($payment->payment_status === 'Payment Confirmed') {
            return back()->with('error', 'Confirmed payments cannot be edited.');
        }

        $request->validate([
            'payment_mode'      => 'required|string|max:100',
            'book_price'        => 'required|numeric|min:0',
            'carrier_price'     => 'required|numeric|min:0',
            'confirmation_date' => 'required|date',
            'screenshot_path'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'details'           => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            // Save old values for log
            $logData = [
                'payment_id'            => $payment->id,
                'changed_by'            => Auth::id(),
                'user_type'             => 'user',
                'old_payment_mode'      => $payment->payment_mode,
                'old_book_price'        => $payment->book_price,
                'old_carrier_price'     => $payment->carrier_price,
                'old_profit'            => $payment->profit,
                'old_details'           => $payment->details,
                'old_confirmation_date' => $payment->confirmation_date,
                'old_payment_status'    => $payment->payment_status,
                'action_type'           => 'update',
                'note'                  => 'Agent resubmitted payment',
            ];

            $profit = (float)$request->book_price - (float)$request->carrier_price;

            $payment->payment_mode      = $request->payment_mode;
            $payment->payment_type      = $request->payment_type;
            $payment->book_price        = $request->book_price;
            $payment->carrier_price     = $request->carrier_price;
            $payment->profit            = $profit;
            $payment->confirmation_date = $request->confirmation_date;
            $payment->details           = $request->details;
            $payment->payment_status    = 'Confirmation Pending'; // Reset to pending after edit
            $payment->admin_remarks     = null;

            // Handle new screenshot
            if ($request->hasFile('screenshot_path')) {
                // Delete old screenshot
                if ($payment->screenshot_path && file_exists(public_path($payment->screenshot_path))) {
                    unlink(public_path($payment->screenshot_path));
                }

                $folder = public_path("Uploads/PaymentScreenShot/{$payment->id}");
                if (!file_exists($folder)) mkdir($folder, 0777, true);

                $file     = $request->file('screenshot_path');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($folder, $filename);
                $payment->screenshot_path = "Uploads/PaymentScreenShot/{$payment->id}/{$filename}";
            }

            $payment->save();

            // Log old values
            AgentOrderPaymentLog::create($logData);

            // Journey entry
            AgentOrderPaymentJourney::create([
                'payment_id' => $payment->id,
                'old_status' => $logData['old_payment_status'],
                'new_status' => 'Confirmation Pending',
                'changed_by' => Auth::id(),
                'user_type'  => 'user',
                'note'       => 'Agent resubmitted after correction',
            ]);

            DB::commit();
            return redirect()->route('agent.payments.index')
                ->with('success', 'Payment resubmitted successfully!');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Agent payment update error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong.')->withInput();
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // JOURNEY — AJAX fetch journey for agent's own payment
    // ─────────────────────────────────────────────────────────────────────────
    public function journey($id)
    {
        $payment = AgentOrderPayment::where('user_id', Auth::id())->findOrFail($id);

        $journeys = AgentOrderPaymentJourney::with('changedBy')
            ->where('payment_id', $payment->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($j) {
                return [
                    'new_status'      => $j->new_status,
                    'old_status'      => $j->old_status,
                    'changed_by_name' => $j->changedBy ? ($j->changedBy->slug ?: $j->changedBy->name) : ($j->changed_by ? '#'.$j->changed_by : '-'),
                    'user_type'       => $j->user_type,
                    'note'            => $j->note,
                    'created_at'      => $j->created_at ? $j->created_at->format('M d, Y H:i') : '-',
                ];
            });

        return response()->json($journeys);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // FETCH ORDER INFO — AJAX: get order details by order ID for auto-fill
    // ─────────────────────────────────────────────────────────────────────────
    public function fetchOrderInfo(Request $request)
    {
        if (!$this->hasAgentPermission()) {
            return response()->json(['error' => 'Access denied.'], 403);
        }

        $orderId = $request->order_id;
        $order   = AutoOrder::find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found.'], 404);
        }

        // #9 client request: an "own quotes only" agent may only look up their OWN orders.
        if (Auth::user()->order_taker_quote == 1) {
            $uid  = (int) Auth::id();
            $owns = ((int) $order->order_taker_id === $uid)
                 || ((int) $order->manager_id === $uid)
                 || in_array((string) $uid, array_filter(explode(',', (string) $order->u_id)), true);
            if (!$owns) {
                return response()->json(['error' => 'This order does not belong to you.'], 403);
            }
        }

        // Check if payment already exists for this order by this agent
        $existing = AgentOrderPayment::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->whereNotIn('payment_status', ['Payment Return'])
            ->first();

        if ($existing) {
            return response()->json(['error' => 'A payment already exists for this order.'], 409);
        }

        return response()->json([
            'order_id'      => $order->id,
            'book_price'    => $order->payment ?? 0,
            'carrier_price' => $order->pay_carrier ?? 0,
            'profit'        => ($order->payment ?? 0) - ($order->pay_carrier ?? 0),
            'oname'         => $order->oname,
            'ymk'           => $order->ymk,
            'origin'        => $order->origincity . ', ' . $order->originstate,
            'destination'   => $order->destinationcity . ', ' . $order->destinationstate,
            'pstatus'       => $order->pstatus,
        ]);
    }
}
