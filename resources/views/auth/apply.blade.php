@extends('layouts.new-master')
@section('page_title', 'Apply for an Opportunity')

@section('content')
<style>
/* Apply page — follows hello-theme.css (navy + gold). Form on the left,
   programme summary on the right; stacks with the form first on mobile. */
.apply-section { background: var(--ht-bg-soft); padding: 70px 0 100px; }

.apply-card {
    background: #fff;
    border: 1px solid var(--ht-border);
    border-radius: 16px;
    box-shadow: var(--ht-shadow);
    padding: 40px 44px;
}
.apply-card .card-heading { font-size: 26px; font-weight: 800; color: var(--ht-navy); margin: 0 0 6px; }
.apply-card .sub-title { font-size: 15px; color: var(--ht-muted); margin-bottom: 8px; }

/* Form fields */
.apply-card .form-label { font-size: 14px; font-weight: 600; color: var(--ht-navy); margin-bottom: 6px; display: block; }
.apply-card .form-control,
.apply-card select.form-control {
    height: 48px;
    border: 1px solid #cfd8e3;
    border-radius: 8px;
    padding: 0 14px;
    font-size: 15px;
    width: 100%;
    background: #fff !important;
    color: var(--ht-navy) !important;
    box-shadow: none;
    transition: border-color .2s, box-shadow .2s;
    appearance: auto; -webkit-appearance: auto;
}
.apply-card textarea.form-control { height: auto; padding: 12px 14px; }
.apply-card input[type="file"].form-control { height: auto; padding: 10px 12px; font-size: 14px; }
.apply-card .form-control::placeholder { color: #8a96a8 !important; }
.apply-card .form-control:focus {
    border-color: var(--ht-navy-3);
    box-shadow: 0 0 0 4px rgba(29, 65, 116, .12);
    outline: none;
}
.apply-card select.form-control option { background: #fff !important; color: var(--ht-navy) !important; }
.apply-card .nice-select { display: none !important; }
.apply-card select { display: block !important; }
.apply-card .form-control.is-invalid { border-color: #c53030 !important; }

.input-icon-wrap { position: relative; }
.input-icon-wrap .form-control { padding-left: 40px !important; }
.input-icon-wrap .field-icon {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: #94a3b8 !important; font-size: 14px; pointer-events: none; z-index: 2; line-height: 1;
}
.field-error { color: #c53030; font-size: 12.5px; margin-top: 4px; display: none; }

.form-section-title {
    font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .12em;
    color: var(--ht-accent-text); margin: 30px 0 14px;
    display: flex; align-items: center; gap: 10px;
}
.form-section-title::after { content: ''; flex: 1; height: 1px; background: var(--ht-border); }
.mb-c { margin-bottom: 16px; }
.linked-note { font-size: 12.5px; color: var(--ht-muted); margin-top: 5px; display: block; }

/* Country-linked phone control */
.phone-combo {
    display: flex; align-items: stretch; height: 48px;
    border: 1px solid #cfd8e3; border-radius: 8px; overflow: hidden; background: #fff;
}
.phone-combo:focus-within { border-color: var(--ht-navy-3); box-shadow: 0 0 0 4px rgba(29, 65, 116, .12); }
.phone-combo .dial {
    display: flex; align-items: center; justify-content: center;
    padding: 0 14px; min-width: 64px;
    background: var(--ht-bg-soft); color: var(--ht-navy); font-weight: 600;
    border-right: 1px solid #cfd8e3; white-space: nowrap;
}
.phone-combo input { border: 0 !important; box-shadow: none !important; height: 46px !important; }

.btn-terms {
    background: var(--ht-bg-soft); color: var(--ht-navy); font-weight: 600;
    border: 1px solid var(--ht-border); border-radius: 8px; padding: 9px 18px; font-size: 14px;
}
.btn-terms:hover { background: var(--ht-navy); color: #fff; border-color: var(--ht-navy); }

/* Submit */
.btn-signup {
    background: var(--ht-accent); border: 1.5px solid var(--ht-accent); color: var(--ht-navy);
    font-weight: 700; font-size: 16px; height: 52px; border-radius: 8px; width: 100%;
    box-shadow: 0 6px 18px rgba(201, 161, 59, .28);
    transition: background .2s, color .2s, transform .1s; cursor: pointer;
}
.btn-signup:hover:not(:disabled) { background: var(--ht-navy); border-color: var(--ht-navy); color: #fff; transform: translateY(-1px); }
.btn-signup:disabled { opacity: .7; cursor: not-allowed; }

/* Success state */
.signup-success { display: none; text-align: center; padding: 40px 10px; }
.signup-success .success-icon {
    width: 76px; height: 76px; border-radius: 50%;
    background: var(--ht-navy); color: var(--ht-accent);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px; font-size: 34px; font-weight: 700;
}
.signup-success h3 { color: var(--ht-navy); font-size: 26px; font-weight: 800; margin-bottom: 10px; }
.signup-success p { color: var(--ht-text); font-size: 15px; line-height: 1.75; max-width: 520px; margin: 0 auto 22px; }

/* Programme summary */
.apply-aside {
    position: sticky; top: 24px;
    background: var(--ht-navy); border-radius: 16px; padding: 34px 30px; color: #fff;
    box-shadow: var(--ht-shadow-lg);
}
.apply-aside .brand-logo { width: 68px; margin-bottom: 16px; }
.apply-aside h4 { color: #fff !important; font-size: 21px; font-weight: 800; margin-bottom: 10px; }
.apply-aside p { color: rgba(255, 255, 255, .75) !important; font-size: 14.5px; line-height: 1.7; margin-bottom: 18px; }
.apply-aside .feature-list, .apply-aside .step-list { list-style: none; padding: 0; margin: 0; }
.apply-aside .feature-list li {
    display: flex; align-items: flex-start; gap: 10px;
    color: rgba(255, 255, 255, .88); font-size: 14.5px; margin-bottom: 11px;
}
.apply-aside .feature-list li i { color: var(--ht-accent) !important; margin-top: 3px; }
.apply-aside .aside-label {
    display: block; color: var(--ht-accent); font-size: 12px; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase; margin: 24px 0 12px;
}
.apply-aside .step-list { counter-reset: step; }
.apply-aside .step-list li {
    counter-increment: step; display: flex; align-items: center; gap: 12px;
    color: rgba(255, 255, 255, .85); font-size: 14px; margin-bottom: 10px;
}
.apply-aside .step-list li::before {
    content: counter(step);
    flex: 0 0 26px; height: 26px; border-radius: 50%;
    background: rgba(201, 161, 59, .18); color: var(--ht-accent);
    font-size: 12.5px; font-weight: 700;
    display: inline-flex; align-items: center; justify-content: center;
}
.apply-aside .signin-box {
    margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(255, 255, 255, .14);
    font-size: 14px; color: rgba(255, 255, 255, .7);
}
.apply-aside .signin-box a { color: var(--ht-accent); font-weight: 700; }

@media (max-width: 991.98px) {
    .apply-aside { position: static; margin-top: 24px; }
}
@media (max-width: 575.98px) {
    .apply-section { padding: 30px 0 60px; }
    .apply-card { padding: 24px 18px; border-radius: 12px; }
}
</style>

{{-- ── Page header ── --}}
<section class="page-title-area pt-100 pb-100">
    <div class="container">
        <div class="page-title-content text-center">
            <h2>Apply for an Opportunity</h2>
            <ul>
                <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                <li><a href="{{ route('Frontend.earn') }}">Earn With Us</a></li>
                <li>Apply</li>
            </ul>
        </div>
    </div>
</section>

<section class="apply-section">
<div class="container">
<div class="row g-4">

    {{-- ── FORM ── --}}
    <div class="col-lg-8">
    <div class="apply-card">

        {{-- Success state (W-04) --}}
        <div class="signup-success" id="signupSuccess">
            <div class="success-icon">✓</div>
            <h3>Application Submitted</h3>
            <p id="signupSuccessMsg">
                Thank you for applying to join our team. Your application has been successfully
                received and is now under review. Our recruitment team will review your application
                and contact you if your profile matches an available opportunity.
            </p>
            <p style="font-size:13px;opacity:.75;margin-top:14px;">
                <strong>Questions?</strong><br>
                For recruitment-related questions, contact
                <a href="mailto:info@hellotransport.com">info@hellotransport.com</a>
            </p>
            <a href="{{ url('/') }}" class="btn-signup"
               style="display:inline-flex;align-items:center;text-decoration:none;padding:0 40px;width:auto;">
                Back to Homepage
            </a>
        </div>

        {{-- Form state --}}
        <div id="signupFormWrap">
            <h3 class="card-heading">Tell Us About Yourself</h3>
            <p class="sub-title">Complete the form below. It takes only a few minutes.</p>

            <div id="signupGlobalError" class="alert alert-danger py-2 small" style="display:none;"></div>

            <form id="applyForm" novalidate enctype="multipart/form-data">
                @csrf

                @php
                    $countries   = config('countries.list', []);
                    $defaultCode = config('countries.default', 'US');
                    $tcBrand     = \App\Support\Brand::byKey('hellotransport');
                    $tzGroups    = [];
                    foreach (DateTimeZone::listIdentifiers() as $tzId) {
                        $region = strpos($tzId, '/') !== false ? strstr($tzId, '/', true) : 'Other';
                        $tzGroups[$region][] = $tzId;
                    }
                    ksort($tzGroups);
                @endphp

                <div class="form-section-title">Contact Information</div>

                <div class="row g-2 mb-c">
                    <div class="col-md-6">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-user field-icon"></i>
                            <input type="text" name="name" class="form-control" placeholder="First name">
                        </div>
                        <div class="field-error" id="err_name"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-user field-icon"></i>
                            <input type="text" name="last_name" class="form-control" placeholder="Last name">
                        </div>
                        <div class="field-error" id="err_last_name"></div>
                    </div>
                </div>

                {{-- Order follows the email: Email, Phone, Country, City. --}}
                <div class="row g-2 mb-c">
                    <div class="col-md-6">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-envelope field-icon"></i>
                            <input type="email" name="email" class="form-control" placeholder="your@email.com">
                        </div>
                        <div class="field-error" id="err_email"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <div class="phone-combo">
                            <span class="dial" id="phoneDial">+1</span>
                            <input type="text" id="phoneLocal" class="form-control"
                                   placeholder="555 000 0000" inputmode="tel" maxlength="15">
                        </div>
                        <input type="hidden" name="phone" id="phoneFull">
                        <div class="field-error" id="err_phone"></div>
                    </div>
                </div>

                {{-- Country drives the phone code and the timezone below. --}}
                <div class="row g-2 mb-c">
                    <div class="col-md-6">
                        <label class="form-label">Country <span class="text-danger">*</span></label>
                        <select name="country" id="countrySelect" class="form-control">
                            @foreach($countries as $c)
                                <option value="{{ $c['name'] }}"
                                        data-dial="{{ $c['dial'] }}"
                                        data-tz="{{ $c['tz'] }}"
                                        @selected($c['code'] === $defaultCode)>{{ $c['name'] }}</option>
                            @endforeach
                        </select>
                        <small class="linked-note">Sets your phone code and timezone — both can be changed.</small>
                        <div class="field-error" id="err_country"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">City <span class="text-danger">*</span></label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-city field-icon"></i>
                            <input type="text" name="city" class="form-control" placeholder="City">
                        </div>
                        <div class="field-error" id="err_city"></div>
                    </div>
                </div>

                {{-- ── Time Zone ── --}}
                <div class="form-section-title">Time Zone</div>
                <div class="mb-c">
                    <label class="form-label">Your Timezone <span class="text-danger">*</span></label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-clock field-icon"></i>
                        <select name="timezone" id="timezoneSelect" class="form-control">
                            @foreach($tzGroups as $region => $zones)
                                <optgroup label="{{ $region }}">
                                    @foreach($zones as $tzId)
                                        <option value="{{ $tzId }}">{{ str_replace('_', ' ', $tzId) }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <small class="linked-note">
                        Automatically detected based on your location — change it if it is wrong.
                        Your attendance, breaks and all portal times use this.
                    </small>
                    <div class="field-error" id="err_timezone"></div>
                </div>

                {{-- ── Experience ── --}}
                <div class="form-section-title">Experience</div>
                <div class="row g-2 mb-c align-items-end">
                    <div class="col-md-6">
                        <label class="form-label">
                            Previously worked with U.S. customers or businesses?
                            <span class="text-danger">*</span>
                        </label>
                        <select name="us_experience" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                        <div class="field-error" id="err_us_experience"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">
                            Can you work U.S. time zones when required?
                            <span class="text-danger">*</span>
                        </label>
                        <select name="us_hours" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                            <option value="flexible">Flexible</option>
                        </select>
                        <div class="field-error" id="err_us_hours"></div>
                    </div>
                </div>

                {{-- ── Additional Information ── --}}
                <div class="form-section-title">Additional Information</div>
                <div class="row g-2 mb-c">
                    <div class="col-md-6">
                        <label class="form-label">Resume / CV</label>
                        <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx">
                        <small class="linked-note">Upload your resume (optional) — PDF or Word, up to 5&nbsp;MB.</small>
                        <div class="field-error" id="err_resume"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">
                            LinkedIn Profile
                            <small class="text-muted" style="font-weight:400;">(optional)</small>
                        </label>
                        <div class="input-icon-wrap">
                            <i class="fab fa-linkedin field-icon"></i>
                            <input type="url" name="linkedin_url" class="form-control"
                                   placeholder="https://www.linkedin.com/in/…">
                        </div>
                        <div class="field-error" id="err_linkedin_url"></div>
                    </div>
                </div>

                <div class="mb-c">
                    <label class="form-label">Tell us about yourself</label>
                    <textarea name="experience" rows="4" class="form-control"
                              placeholder="Briefly describe your experience, skills, and why you're interested in the opportunity."></textarea>
                    <div class="field-error" id="err_experience"></div>
                </div>

                {{-- ── Terms ── --}}
                <div class="form-section-title">Terms &amp; Conditions</div>
                <div class="mb-c">
                    <button type="button" id="tcToggleBtn" class="btn btn-terms"
                            onclick="var b=document.getElementById('tcBox');var open=b.style.display!=='none';b.style.display=open?'none':'block';this.textContent=open?'View Terms and Conditions':'Hide Terms and Conditions';">
                        View Terms and Conditions
                    </button>
                    <div id="tcBox" style="display:none;max-height:260px;overflow-y:auto;border:1px solid var(--ht-border);border-radius:8px;padding:14px 16px;background:var(--ht-bg-soft);font-size:13px;line-height:1.65;color:var(--ht-text);margin-top:10px;">
                        @include('partials.hello_terms')
                    </div>
                    <label class="d-flex align-items-start gap-2 mt-3" style="cursor:pointer;">
                        <input type="checkbox" name="terms_accepted" id="termsAccepted" value="1"
                               style="margin-top:3px;width:17px;height:17px;accent-color:var(--ht-navy);">
                        <span style="font-size:14px;color:var(--ht-navy);">
                            I have read and accept the {{ $tcBrand['name'] }} Terms &amp; Conditions.
                            <span class="text-danger">*</span>
                        </span>
                    </label>
                    <div class="field-error" id="err_terms_accepted"></div>
                </div>

                <div class="row g-2 mt-4 align-items-center">
                    <div class="col-md-6">
                        <button type="submit" class="btn-signup" id="applySubmitBtn">
                            <i class="fas fa-paper-plane me-2"></i> Apply to Join the Network
                        </button>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-0" style="font-size:14px;color:var(--ht-muted);">
                            Already part of our team?
                            <a href="{{ url('/loginn') }}" style="color:var(--ht-navy);font-weight:700;">Sign In</a>
                        </p>
                    </div>
                </div>
            </form>
        </div>{{-- /signupFormWrap --}}
    </div>{{-- /apply-card --}}
    </div>

    {{-- ── PROGRAMME SUMMARY ── --}}
    <div class="col-lg-4">
        <aside class="apply-aside">
            <img src="{{ asset(($brand['logo'] ?? 'frontend/img/logo/hello_transport.svg')) }}"
                 alt="Hello Transport" class="brand-logo">
            <h4>Earn With Hello Transport</h4>
            <p>Join our network of sales agents. Build your own book of business with the
               operational support, compliance and back-office work handled for you.</p>
            <ul class="feature-list">
                <li><i class="fas fa-check-circle"></i> Work remotely, on your own schedule</li>
                <li><i class="fas fa-check-circle"></i> Commission on every load you move</li>
                <li><i class="fas fa-check-circle"></i> Dispatch &amp; back-office support</li>
                <li><i class="fas fa-check-circle"></i> Training and onboarding provided</li>
                <li><i class="fas fa-check-circle"></i> Paid by bank transfer, Wise or PayPal</li>
            </ul>

            <span class="aside-label">How it works</span>
            <ol class="step-list">
                <li>Apply</li>
                <li>Get reviewed</li>
                <li>Get approved</li>
                <li>Onboarding</li>
                <li>Start selling</li>
                <li>Earn</li>
            </ol>

            <div class="signin-box">
                Already part of our team?
                <a href="{{ url('/loginn') }}">Sign in here</a>
            </div>
        </aside>
    </div>

</div>
</div>
</section>

<script>
(function () {
    var form      = document.getElementById('applyForm');
    var country   = document.getElementById('countrySelect');
    var dialEl    = document.getElementById('phoneDial');
    var phoneLocal = document.getElementById('phoneLocal');
    var phoneFull  = document.getElementById('phoneFull');
    var tzSelect   = document.getElementById('timezoneSelect');

    // ── Country drives the dial code and the timezone ──────────────────────────────
    // The applicant can still change the timezone afterwards; we only overwrite it
    // while they have not touched it themselves.
    var tzTouched = false;
    if (tzSelect) { tzSelect.addEventListener('change', function () { tzTouched = true; }); }

    function currentDial() {
        if (!country) return '+1';
        var opt = country.options[country.selectedIndex];
        return (opt && opt.getAttribute('data-dial')) || '+1';
    }

    function syncPhone() {
        if (!phoneLocal || !phoneFull) return;
        var digits = phoneLocal.value.replace(/\D/g, '').slice(0, 15);
        phoneLocal.value = digits;
        phoneFull.value  = digits ? currentDial() + ' ' + digits : '';
    }

    function applyCountry(initial) {
        if (!country) return;
        var opt = country.options[country.selectedIndex];
        if (!opt) return;

        if (dialEl) { dialEl.textContent = opt.getAttribute('data-dial') || '+1'; }

        var tz = opt.getAttribute('data-tz');
        if (tz && tzSelect && (initial || !tzTouched)) {
            var found = Array.prototype.some.call(tzSelect.options, function (o) { return o.value === tz; });
            if (found) { tzSelect.value = tz; }
        }
        syncPhone();
    }

    if (country) { country.addEventListener('change', function () { applyCountry(false); }); }
    if (phoneLocal) {
        phoneLocal.addEventListener('input', function () {
            syncPhone();
            clearError('phone');
        });
    }

    // Start from the browser's own timezone when it matches a real option, otherwise
    // fall back to the default country's.
    (function initTimezone() {
        applyCountry(true);
        try {
            var guess = Intl.DateTimeFormat().resolvedOptions().timeZone;
            if (guess && tzSelect) {
                var ok = Array.prototype.some.call(tzSelect.options, function (o) { return o.value === guess; });
                if (ok) { tzSelect.value = guess; }
            }
        } catch (e) { /* older browser — the country default already applied */ }
    })();

    // ── Validation ────────────────────────────────────────────────────────────────
    function fieldEl(name) { return form.querySelector('[name="' + name + '"]'); }

    function clearError(name) {
        var el = document.getElementById('err_' + name);
        if (el) { el.style.display = 'none'; el.textContent = ''; }
        var input = name === 'phone' ? phoneLocal : fieldEl(name);
        if (input) { input.classList.remove('is-invalid'); }
    }

    function showError(name, message) {
        var el = document.getElementById('err_' + name);
        if (el) { el.textContent = message; el.style.display = 'block'; }
        var input = name === 'phone' ? phoneLocal : fieldEl(name);
        if (input) { input.classList.add('is-invalid'); }
    }

    function val(name) {
        var el = fieldEl(name);
        return el ? el.value.trim() : '';
    }

    var REQUIRED = {
        name:          'First name is required.',
        last_name:     'Last name is required.',
        email:         'Email address is required.',
        city:          'City is required.',
        country:       'Please select your country.',
        timezone:      'Please choose your timezone.',
        us_experience: 'Please answer this question.',
        us_hours:      'Please answer this question.'
    };

    function validate() {
        var ok = true;
        Object.keys(REQUIRED).forEach(clearError);
        clearError('phone');
        clearError('terms_accepted');
        clearError('linkedin_url');

        Object.keys(REQUIRED).forEach(function (name) {
            if (!val(name)) { showError(name, REQUIRED[name]); ok = false; }
        });

        var email = val('email');
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email)) {
            showError('email', 'Enter a valid email address.');
            ok = false;
        }

        // Worldwide: length varies by country, so only a sane range is enforced here.
        var digits = (phoneLocal ? phoneLocal.value : '').replace(/\D/g, '');
        if (!digits) { showError('phone', 'Phone number is required.'); ok = false; }
        else if (digits.length < 6 || digits.length > 15) {
            showError('phone', 'Enter a valid phone number.');
            ok = false;
        }

        var li = val('linkedin_url');
        if (li && !/^https?:\/\//i.test(li)) {
            showError('linkedin_url', 'Enter the full URL, starting with https://');
            ok = false;
        }

        var terms = document.getElementById('termsAccepted');
        if (!terms || !terms.checked) {
            showError('terms_accepted', 'You must accept the Terms & Conditions to continue.');
            ok = false;
        }

        if (!ok) {
            var firstError = form.querySelector('.field-error[style*="block"]');
            if (firstError) { firstError.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
        }
        return ok;
    }

    // ── Submit ────────────────────────────────────────────────────────────────────
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        syncPhone();
        if (!validate()) { return; }

        var btn = document.getElementById('applySubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting…';

        var globalError = document.getElementById('signupGlobalError');
        globalError.style.display = 'none';

        fetch("{{ route('register.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: new FormData(form)
        })
        .then(function (r) { return r.json().then(function (d) { return { status: r.status, data: d }; }); })
        .then(function (res) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Apply to Join the Network';

            if (res.status === 200 && res.data.success) {
                document.getElementById('signupFormWrap').style.display = 'none';
                var succ = document.getElementById('signupSuccess');
                succ.style.display = 'block';
                if (res.data.message) {
                    document.getElementById('signupSuccessMsg').textContent = res.data.message;
                }
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }

            if (res.status === 422 && res.data.errors) {
                Object.keys(res.data.errors).forEach(function (field) {
                    showError(field, res.data.errors[field][0]);
                });
                var firstError = form.querySelector('.field-error[style*="block"]');
                if (firstError) { firstError.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
                return;
            }

            globalError.textContent = res.data.message || 'Something went wrong. Please try again.';
            globalError.style.display = 'block';
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Apply to Join the Network';
            globalError.textContent = 'Could not reach the server. Please check your connection and try again.';
            globalError.style.display = 'block';
        });
    });
})();
</script>

@endsection
