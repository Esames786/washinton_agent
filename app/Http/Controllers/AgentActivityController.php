<?php

namespace App\Http\Controllers;

use App\AgentActiveTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentActivityController extends Controller
{
    // Max seconds accepted per heartbeat (prevents a tab from inflating time).
    private const MAX_SECONDS_PER_PING = 120;

    /**
     * Record active working seconds for the current agent (today).
     * Called periodically by the dashboard activity tracker while the
     * agent is actively moving the cursor / typing.
     */
    public function heartbeat(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false], 401);
        }

        $seconds = (int) $request->input('seconds', 0);
        if ($seconds < 1) {
            return response()->json(['success' => true, 'today_seconds' => $this->todaySeconds($user->id)]);
        }
        $seconds = min($seconds, self::MAX_SECONDS_PER_PING);

        $today = date('Y-m-d');
        $row = AgentActiveTime::firstOrNew(['user_id' => $user->id, 'work_date' => $today]);
        $row->active_seconds = (int) $row->active_seconds + $seconds;
        $row->save();

        return response()->json([
            'success'       => true,
            'today_seconds' => (int) $row->active_seconds,
            'today_human'   => AgentActiveTime::format((int) $row->active_seconds),
        ]);
    }

    private function todaySeconds(int $userId): int
    {
        $row = AgentActiveTime::where('user_id', $userId)->where('work_date', date('Y-m-d'))->first();
        return $row ? (int) $row->active_seconds : 0;
    }
}
