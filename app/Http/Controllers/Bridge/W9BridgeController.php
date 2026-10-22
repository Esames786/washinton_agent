<?php

namespace App\Http\Controllers\Bridge;

use App\Http\Controllers\Controller;
use App\Http\Controllers\W9Controller;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * W-9 submitted from the HR portal (washinton_hr → washinton_agent).
 *
 * The W-9 is a step of the HR onboarding workflow now (client, 23 Oct 2026), often before the
 * agent has a CRM account. The HR portal forwards the form here so it is validated, encrypted
 * (the TIN, with this app's key) and rendered exactly like one filed in the CRM, in the same
 * w9_forms table. Auth: X-Bridge-Key, as /bridge/employee/sync.
 */
class W9BridgeController extends Controller
{
    public function submit(Request $request): JsonResponse
    {
        $expected = (string) env('HELLOTRANSPORT_BRIDGE_KEY');
        if ($expected === '' || !hash_equals($expected, (string) $request->header('X-Bridge-Key'))) {
            Log::warning('W9BridgeController: invalid bridge key');
            abort(403, 'Invalid bridge key');
        }

        $request->validate(['hr_employee_id' => 'required|integer']);
        $hr = DB::table('hr_employees')->where('id', $request->hr_employee_id)->first(['id', 'agent_id']);
        if (!$hr) {
            return response()->json(['success' => false, 'message' => 'Unknown HR employee.'], 404);
        }

        $user = $hr->agent_id ? User::find($hr->agent_id) : null;
        if ($user && $user->isCrazyrays()) {
            return response()->json(['success' => false, 'message' => 'W-9 applies to Hello Transport agents only.'], 422);
        }

        $validated = $request->validate(W9Controller::rules(), W9Controller::messages());
        if ($reject = W9Controller::rejectSubmission($validated, $user->id ?? null, $hr->id)) {
            return $reject;
        }

        // The agent's own IP, passed on by the HR portal (the request itself comes from HR's server).
        $ip   = filter_var($request->input('client_ip'), FILTER_VALIDATE_IP) ?: $request->ip();
        $form = W9Controller::saveSubmission($validated, $user, $hr->id, $ip);

        Log::info('W9BridgeController: W-9 filed from the HR portal', ['hr_employee_id' => $hr->id, 'user_id' => $user->id ?? null]);

        return response()->json([
            'success'      => true,
            'message'      => 'Your W-9 has been submitted.',
            'tin_last4'    => $form->tin_last4,
            'document_url' => $form->document_url ?? null,
        ]);
    }
}
