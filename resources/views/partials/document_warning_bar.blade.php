{{--
    Document Warning Bar
    Shows a sticky top banner when the logged-in user has an HR employee record
    with missing required documents (employee_status_id = 7 = Document Verification).
    Redirects to the HR portal employee profile to upload documents.
    Only shown to non-admin users who are linked to an HR employee.
--}}
@php
    $showDocBanner = false;
    if (Auth::check() && Auth::user()->role != 1) {
        // Check if this agent has an HR employee record in Document Verification status
        $hrEmployee = \Illuminate\Support\Facades\DB::table('hr_employees')
            ->where('agent_id', Auth::id())
            ->where('employee_status_id', 7) // 7 = Document Verification
            ->first();
        if ($hrEmployee) {
            // Check if they have any documents uploaded
            $docCount = \Illuminate\Support\Facades\DB::table('hr_employee_documents')
                ->where('employee_id', $hrEmployee->id)
                ->count();
            $showDocBanner = ($docCount === 0);
        }
    }
@endphp

@if ($showDocBanner)
<div id="doc-warning-bar" style="
    position: sticky;
    top: 0;
    z-index: 9998;
    background: linear-gradient(90deg, #b8860b 0%, #d4a017 50%, #b8860b 100%);
    color: #fff;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    font-size: 13px;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    border-bottom: 2px solid #8B6914;
">
    <div style="display:flex;align-items:center;gap:10px;">
        <span style="font-size:18px;">⚠️</span>
        <span>
            Your HR profile is incomplete — required documents are missing.
            Please upload your documents to complete your account setup.
        </span>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
        <a href="{{ config('app.hr_portal_url', env('HRPORTAL_BASE_URL', '#')) }}/employee/profile"
           target="_blank"
           style="
               background: #fff;
               color: #b8860b;
               font-weight: 700;
               padding: 6px 16px;
               border-radius: 6px;
               text-decoration: none;
               font-size: 12px;
               white-space: nowrap;
               border: 2px solid #fff;
               transition: all .2s;
           "
           onmouseover="this.style.background='#b8860b';this.style.color='#fff';"
           onmouseout="this.style.background='#fff';this.style.color='#b8860b';">
            📄 Upload Documents
        </a>
        <button onclick="document.getElementById('doc-warning-bar').style.display='none';"
                style="
                    background: transparent;
                    border: none;
                    color: #fff;
                    font-size: 18px;
                    cursor: pointer;
                    line-height: 1;
                    padding: 0 4px;
                    opacity: .8;
                "
                title="Dismiss">×</button>
    </div>
</div>
@endif
