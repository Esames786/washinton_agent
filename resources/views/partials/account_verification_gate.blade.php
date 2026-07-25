{{--
    Account Verification Gate
    Blurs the entire portal while the agent's account is still pending HR
    verification / admin activation. Only the onboarding actions (upload
    documents, sign NDA) remain accessible. Lifts automatically once the
    admin verifies documents and activates the account (status away from
    "Document Verification" / user.status = 1).

    Sits BELOW the NDA modal (z 99999) and contract modal (z 999999) so those
    take precedence when present.
--}}
@auth
@php
    $gateActive   = false;
    $docsUploaded = false;
    $reqMissing   = false;
    $ndaRequired  = (int) (auth()->user()->nda_required ?? 0) === 1;
    $brand        = \App\Support\Brand::current();

    if (auth()->user()->role != 1) {
        $hrEmployee = \Illuminate\Support\Facades\DB::table('hr_employees')
            ->where('agent_id', auth()->id())
            ->first();

        if ($hrEmployee) {
            // Pending while in "Document Verification" (status 7) or account still inactive,
            // or an NDA signature is still outstanding.
            $isDocVerification = (int) $hrEmployee->employee_status_id === 7;
            $isInactive        = (int) auth()->user()->status === 0;
            $gateActive        = $isDocVerification || $isInactive || $ndaRequired;

            if ($gateActive) {
                $docCount = \Illuminate\Support\Facades\DB::table('hr_employee_documents')
                    ->where('employee_id', $hrEmployee->id)->count();

                // Only count documents that actually apply to this agent's house ownership —
                // unconditional docs always apply; own/rent docs only for the matching selection.
                // Without this, the gate counted the OTHER ownership's docs as "missing" while
                // the HR profile (which filters by condition) said everything was submitted.
                $ownership = $hrEmployee->house_ownership ?? null;
                $conditionFilter = function ($q) use ($ownership) {
                    $q->whereNull('hr_document_settings.condition');
                    if ($ownership) {
                        $q->orWhere('hr_document_settings.condition', $ownership);
                    }
                };

                $requiredCount = \Illuminate\Support\Facades\DB::table('hr_document_settings')
                    ->where('is_required', 1)->where('status', 1)
                    ->where($conditionFilter)
                    ->count();

                // #3: count DISTINCT required document types uploaded (not rows) — multi-file docs
                // (e.g. Selfie with max_files > 1) create several rows for ONE setting, so a plain
                // row count diverged from the HR profile's distinct-setting count and left the gate
                // out of sync ("documents still missing" while the profile said everything was in).
                $uploadedRequired = \Illuminate\Support\Facades\DB::table('hr_employee_documents')
                    ->join('hr_document_settings', 'hr_employee_documents.document_setting_id', '=', 'hr_document_settings.id')
                    ->where('hr_employee_documents.employee_id', $hrEmployee->id)
                    ->where('hr_document_settings.is_required', 1)
                    ->where($conditionFilter)
                    ->distinct()
                    ->count('hr_employee_documents.document_setting_id');

                $docsUploaded = $docCount > 0;
                $reqMissing   = $uploadedRequired < $requiredCount;
            }
        }
    }
@endphp

@if($gateActive)
<style>
    /* Blur the whole app behind the gate */
    body.acct-gate-active .page-main,
    body.acct-gate-active .app-content,
    body.acct-gate-active .main-content {
        filter: blur(7px) !important;
        pointer-events: none !important;
        user-select: none !important;
    }
    #acctVerificationGate {
        position: fixed; inset: 0; z-index: 99990;
        background: rgba(15, 23, 42, .55);
        display: flex; align-items: center; justify-content: center;
        padding: 16px;
    }
    #acctVerificationGate .agc-card {
        background:#fff; border-radius:14px; max-width:560px; width:100%;
        box-shadow:0 24px 70px rgba(0,0,0,.45); overflow:hidden;
        font-family:'Segoe UI',Arial,sans-serif;
    }
    #acctVerificationGate .agc-head {
        background:linear-gradient(135deg,#062e39 0%,#0d5c70 100%);
        color:#fff; padding:22px 28px; text-align:center;
    }
    #acctVerificationGate .agc-body { padding:24px 28px; }
    #acctVerificationGate .agc-step {
        display:flex; align-items:flex-start; gap:12px;
        padding:12px 14px; border:1px solid #e6e9ef; border-radius:10px; margin-bottom:12px;
    }
    #acctVerificationGate .agc-step .ic { font-size:20px; flex-shrink:0; line-height:1.2; }
    #acctVerificationGate .agc-btn {
        display:inline-block; background:#1a73e8; color:#fff; text-decoration:none;
        font-weight:700; font-size:14px; padding:11px 26px; border-radius:8px; border:none; cursor:pointer;
    }
    #acctVerificationGate .agc-btn.alt { background:#8fc445; }
</style>

<div id="acctVerificationGate">
    <div class="agc-card">
        <div class="agc-head">
            <div style="font-size:30px;line-height:1;margin-bottom:8px;">🔒</div>
            <h3 style="margin:0;font-size:20px;font-weight:800;">Account Pending Verification</h3>
            <p style="margin:6px 0 0;opacity:.85;font-size:13px;">{{ $brand['name'] }} — complete the steps below to activate your portal.</p>
        </div>
        <div class="agc-body">
            <p style="color:#555;font-size:13.5px;line-height:1.6;margin:0 0 18px;">
                Your account is not active yet. Please complete your onboarding. Once our admin team
                verifies your documents{{ $ndaRequired ? ' and NDA' : '' }}, your full portal will unlock automatically.
            </p>

            {{-- Step: Documents --}}
            <div class="agc-step">
                <span class="ic">{{ (!$reqMissing && $docsUploaded) ? '✅' : '📄' }}</span>
                <div style="flex:1;">
                    <div style="font-weight:700;color:#1a1a2e;font-size:14px;">
                        Upload Required Documents
                        @if(!$reqMissing && $docsUploaded)
                            <span style="color:#16a34a;font-size:12px;font-weight:600;">— Submitted, pending review</span>
                        @endif
                    </div>
                    <div style="color:#6b7280;font-size:12.5px;margin:2px 0 8px;">
                        @if($reqMissing || !$docsUploaded)
                            Some required documents are still missing.
                        @else
                            Documents submitted. Waiting for HR verification.
                        @endif
                    </div>
                    <a href="{{ route('hr.portal.redirect') }}?to=profile" class="agc-btn">
                        {{ (!$reqMissing && $docsUploaded) ? 'View / Update Documents' : 'Upload Documents' }}
                    </a>
                </div>
            </div>

            {{-- Step: NDA --}}
            @if($ndaRequired)
            <div class="agc-step">
                <span class="ic">✍️</span>
                <div style="flex:1;">
                    <div style="font-weight:700;color:#1a1a2e;font-size:14px;">Sign the NDA Agreement</div>
                    <div style="color:#6b7280;font-size:12.5px;margin:2px 0 8px;">
                        A Non-Disclosure Agreement is awaiting your signature.
                    </div>
                    <button type="button" class="agc-btn alt" onclick="window.scrollTo(0,0);">Sign NDA (shown above)</button>
                </div>
            </div>
            @endif

            <p style="color:#9ca3af;font-size:11.5px;text-align:center;margin:14px 0 0;">
                This screen will unlock automatically once your account is activated.
            </p>
        </div>
    </div>
</div>

<script>
    document.body.classList.add('acct-gate-active');
</script>
@endif
@endauth
