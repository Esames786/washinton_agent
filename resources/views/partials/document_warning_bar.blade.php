{{--
    Document Warning Bar
    State 1 (orange): Status=7, no docs uploaded → prompt to upload
    State 2 (blue):   Status=7, docs uploaded → pending admin verification
    Hidden when status=1 (Active) or not linked to HR
--}}
@php
    $showDocBanner   = false;
    $docsUploaded    = false;
    $requiredMissing = false;

    if (Auth::check() && Auth::user()->role != 1) {
        $hrEmployee = \Illuminate\Support\Facades\DB::table('hr_employees')
            ->where('agent_id', Auth::id())
            ->where('employee_status_id', 7)
            ->first();

        if ($hrEmployee) {
            $showDocBanner = true;

            $docCount = \Illuminate\Support\Facades\DB::table('hr_employee_documents')
                ->where('employee_id', $hrEmployee->id)
                ->count();

            $requiredCount = \Illuminate\Support\Facades\DB::table('hr_document_settings')
                ->where('is_required', 1)
                ->where('status', 1)
                ->count();

            $uploadedRequiredCount = \Illuminate\Support\Facades\DB::table('hr_employee_documents')
                ->join('hr_document_settings', 'hr_employee_documents.document_setting_id', '=', 'hr_document_settings.id')
                ->where('hr_employee_documents.employee_id', $hrEmployee->id)
                ->where('hr_document_settings.is_required', 1)
                ->count();

            $docsUploaded    = $docCount > 0;
            $requiredMissing = $uploadedRequiredCount < $requiredCount;
        }
    }
@endphp

@if ($showDocBanner)
    @if (!$docsUploaded || $requiredMissing)
    {{-- State 1: Documents missing — prompt to upload --}}
    <div id="doc-warning-bar" style="
        position: sticky; top: 0; z-index: 9998;
        background: linear-gradient(90deg, #b8860b 0%, #d4a017 50%, #b8860b 100%);
        color: #fff; padding: 10px 20px;
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
        font-size: 13px; font-weight: 600;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        border-bottom: 2px solid #8B6914;
    ">
        <div style="display:flex;align-items:center;gap:10px;">
            <span style="font-size:18px;">⚠️</span>
            <span>Your HR profile is incomplete — required documents are missing. Please upload your documents to complete your account setup.</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
            <a href="{{ route('hr.portal.redirect') }}"
               style="background:#fff;color:#b8860b;font-weight:700;padding:6px 16px;border-radius:6px;text-decoration:none;font-size:12px;white-space:nowrap;border:2px solid #fff;">
                📄 Upload Documents
            </a>
            <button onclick="document.getElementById('doc-warning-bar').style.display='none';"
                    style="background:transparent;border:none;color:#fff;font-size:18px;cursor:pointer;line-height:1;padding:0 4px;opacity:.8;"
                    title="Dismiss">×</button>
        </div>
    </div>

    @else
    {{-- State 2: Documents uploaded, waiting for admin to verify & activate --}}
    <div id="doc-warning-bar" style="
        position: sticky; top: 0; z-index: 9998;
        background: linear-gradient(90deg, #1565c0 0%, #1976d2 50%, #1565c0 100%);
        color: #fff; padding: 10px 20px;
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
        font-size: 13px; font-weight: 600;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        border-bottom: 2px solid #0d47a1;
    ">
        <div style="display:flex;align-items:center;gap:10px;">
            <span style="font-size:18px;">🕐</span>
            <span>Your documents have been submitted and are <strong>pending HR verification</strong>. Your account will be activated once reviewed.</span>
        </div>
        <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
            <a href="{{ route('hr.portal.redirect') }}"
               style="background:#fff;color:#1565c0;font-weight:700;padding:6px 16px;border-radius:6px;text-decoration:none;font-size:12px;white-space:nowrap;border:2px solid #fff;">
                👁 View Profile
            </a>
            <button onclick="document.getElementById('doc-warning-bar').style.display='none';"
                    style="background:transparent;border:none;color:#fff;font-size:18px;cursor:pointer;line-height:1;padding:0 4px;opacity:.8;"
                    title="Dismiss">×</button>
        </div>
    </div>
    @endif
@endif
