{{-- Agent check-in gate (hellotransport.com).
     An agent who is a managed HR employee (active) must check in for the day
     (check-in happens in the HR portal, shared DB) before using the dashboard
     or making quotes. Lifts automatically once checked in. --}}
@auth
@php
    $__needCheckin = false;
    try {
        if (Auth::user()->role != 1) {
            $__hrEmp = \Illuminate\Support\Facades\DB::table('hr_employees')
                ->where('agent_id', Auth::id())
                ->where('employee_status_id', 1) // active only
                ->first();
            if ($__hrEmp) {
                // Match HR markAttendance()'s date logic EXACTLY (shared DB): the shift date is
                // resolved in the EMPLOYEE'S timezone (Asia/Karachi for CrazyRays staff) and
                // anything before 6 AM belongs to the previous day's overnight shift. A naive
                // date('Y-m-d') queried a different day than HR check-in wrote, so on a night
                // shift after midnight this gate never cleared. (fix)
                $__gateTz = $__hrEmp->timezone ?: (Auth::user()->tz() ?? config('app.timezone', 'Asia/Karachi'));
                $__now   = \Carbon\Carbon::now($__gateTz);
                $__today = $__now->hour < 6 ? $__now->copy()->subDay()->toDateString() : $__now->toDateString();
                $__att = \Illuminate\Support\Facades\DB::table('hr_employee_attendances')
                    ->where('employee_id', $__hrEmp->id)
                    ->whereDate('attendance_date', $__today)
                    ->first();
                $__needCheckin = !($__att && $__att->check_in);
            }
        }
    } catch (\Throwable $e) {}
    $__hrBase = rtrim((string) config('bridge.hrportal.base_url'), '/');
@endphp

@if($__needCheckin)
<style>
    body.agent-checkin-active .page-main,
    body.agent-checkin-active .app-content,
    body.agent-checkin-active .main-content { filter: blur(7px) !important; pointer-events:none !important; }
</style>
<div id="agentCheckinGate" style="position:fixed;inset:0;z-index:99985;background:rgba(15,23,42,.6);display:flex;align-items:center;justify-content:center;padding:16px;">
    <div style="background:#fff;border-radius:14px;max-width:480px;width:100%;box-shadow:0 24px 70px rgba(0,0,0,.45);text-align:center;padding:32px 28px;font-family:'Segoe UI',Arial,sans-serif;">
        <div style="font-size:42px;line-height:1;margin-bottom:10px;">🕒</div>
        <h3 style="margin:0 0 8px;font-weight:800;color:#0f172a;font-size:20px;">Please Check In First</h3>
        <p style="color:#555;font-size:14px;margin:0 0 20px;">You must check in to start your shift before using the dashboard or creating quotes.</p>
        @if($__hrBase)
            <a href="{{ route('hr.portal.redirect') }}?to=dashboard"
               style="display:inline-block;background:#1a73e8;color:#fff;text-decoration:none;border-radius:8px;padding:12px 30px;font-size:15px;font-weight:700;">
                Go to Check In →
            </a>
        @endif
    </div>
</div>
<script>document.body.classList.add('agent-checkin-active');</script>
@endif
@endauth
