@extends('layouts.new-master')
@section('page_title', 'Apply for an Opportunity')

@section('content')
<style>
/* ── Full-screen split layout ── */
html, body { height: 100%; margin: 0; }

.signup-wrap {
    display: flex;
    min-height: 100vh;
    width: 100%;
}

/* LEFT PANEL */
.signup-left {
    width: 300px;
    flex-shrink: 0;
    background: linear-gradient(160deg, #062e39 0%, #0a4a5a 60%, #0d5c70 100%);
    padding: 40px 30px;
    color: #fff;
    display: flex;
    flex-direction: column;
    position: sticky;
    top: 0;
    height: 100vh;
    overflow-y: auto;
}
.signup-left .brand-logo { width: 110px; margin-bottom: 22px; }
.signup-left h2 {
    font-size: 22px; font-weight: 800; line-height: 1.3; margin-bottom: 10px;
    font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;
}
.signup-left h2 span { color: #8fc445; }
.signup-left p { font-size: 13px; color: rgba(255,255,255,.75); line-height: 1.7; margin-bottom: 16px; }
.signup-left .feature-list { list-style: none; padding: 0; margin: 0; }
.signup-left .feature-list li {
    display: flex; align-items: center; gap: 8px;
    font-size: 12px; color: rgba(255,255,255,.85); margin-bottom: 9px;
}
.signup-left .feature-list li i { color: #8fc445; font-size: 14px; flex-shrink: 0; }
.signup-left .divider { width: 40px; height: 3px; background: #8fc445; border-radius: 2px; margin: 12px 0; }

/* RIGHT FORM PANEL */
.signup-right {
    flex: 1;
    background: #f0f4f8;
    padding: 40px 60px;
    overflow-y: auto;
}
.signup-right h3 {
    font-size: 22px; font-weight: 800; color: #062e39; margin-bottom: 2px;
    font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: .5px;
}
.signup-right .sub-title { font-size: 12px; color: #6c757d; margin-bottom: 20px; }

/* Account type cards */
.type-card {
    border: 2px solid #dee2e6; border-radius: 8px; padding: 10px 8px;
    text-align: center; cursor: pointer; transition: all .2s; background: #fff; height: 100%;
}
.type-card:hover { border-color: #8fc445; background: #f8fdf0; }
.type-card.selected { border-color: #8fc445; background: #f4fbe8; box-shadow: 0 0 0 3px rgba(143,196,69,.15); }
.type-card .type-icon { font-size: 22px; display: block; margin-bottom: 3px; }
.type-card .type-label { font-weight: 700; font-size: 13px; color: #062e39; display: block; }
.type-card .type-sub { font-size: 10px; color: #888; display: block; margin-top: 1px; }

/* Form fields */
.form-label { font-size: 12px; font-weight: 600; color: #062e39; margin-bottom: 4px; display: block; }
.signup-right .form-control,
.signup-right input.form-control,
.signup-right textarea.form-control,
.signup-right select.form-control {
    border: 1.5px solid #dee2e6; border-radius: 7px;
    padding: 8px 12px; font-size: 13px;
    transition: border-color .2s, box-shadow .2s; width: 100%;
    background: #fff !important;
    color: #333 !important;
    appearance: auto; -webkit-appearance: auto;
}
.signup-right .form-control::placeholder { color: #aaa !important; }
.signup-right .form-control:focus, .signup-right select.form-control:focus {
    border-color: #8fc445; box-shadow: 0 0 0 3px rgba(143,196,69,.15); outline: none;
}
/* Make sure native select options are readable */
.signup-right select.form-control option {
    background: #fff !important;
    color: #333 !important;
}
/* Hide any nice-select wrapper the plugin may create for signup selects */
.signup-right .nice-select { display: none !important; }
.signup-right select { display: block !important; }
.signup-right .form-control.is-invalid { border-color: #dc3545 !important; }
.input-icon-wrap { position: relative; }
.input-icon-wrap .form-control { padding-left: 36px !important; }
.input-icon-wrap .field-icon {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: #8fc445;
    font-size: 13px;
    pointer-events: none;
    z-index: 2;
    line-height: 1;
}
.input-icon-wrap textarea.form-control { padding-left: 36px !important; }
.input-icon-wrap .field-icon.textarea-icon { top: 12px; transform: none; }
.field-error { color: #dc3545; font-size: 11px; margin-top: 3px; display: none; }

/* Submit button */
.btn-signup {
    background: #8fc445; border: none; color: #fff; font-weight: 700;
    padding: 12px 0; font-size: 15px; border-radius: 8px; width: 100%;
    letter-spacing: .5px; text-transform: uppercase;
    font-family: 'Oswald', sans-serif; transition: background .2s, transform .1s;
    cursor: pointer;
}
.btn-signup:hover:not(:disabled) { background: #7aad38; transform: translateY(-1px); }
.btn-signup:disabled { opacity: .7; cursor: not-allowed; }

/* Section divider */
.form-section-title {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 1px; color: #adb5bd; margin: 16px 0 10px;
    display: flex; align-items: center; gap: 8px;
}
.form-section-title::after { content: ''; flex: 1; height: 1px; background: #dee2e6; }
.mb-c { margin-bottom: 12px; }

/* Success overlay */
.signup-success {
    display: none;
    text-align: center;
    padding: 60px 20px;
}
.signup-success .success-icon {
    width: 80px; height: 80px; background: #8fc445; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px; font-size: 36px; color: #fff;
}
.signup-success h3 { color: #062e39; font-family: 'Oswald', sans-serif; font-size: 26px; margin-bottom: 10px; }
.signup-success p { color: #555; font-size: 14px; line-height: 1.7; max-width: 480px; margin: 0 auto 24px; }

/* Responsive */
@media (max-width: 768px) {
    .signup-wrap { flex-direction: column; }
    .signup-left { width: 100%; height: auto; position: relative; padding: 28px 20px; }
    .signup-right { padding: 24px 16px; }
}

/* Country-linked phone control */
.phone-combo {
    display: flex;
    align-items: stretch;
    border: 1px solid #ced4da;
    border-radius: 6px;
    overflow: hidden;
    background: #fff;
}
.phone-combo .dial {
    display: flex;
    align-items: center;
    padding: 0 12px;
    background: #f1f3f5;
    color: #343a40;
    font-weight: 600;
    white-space: nowrap;
    border-right: 1px solid #ced4da;
    min-width: 58px;
    justify-content: center;
}
.phone-combo input { border: 0 !important; box-shadow: none !important; }
.linked-note { font-size: 11.5px; color: #6c757d; margin-top: 4px; display: block; }
</style>

<div class="signup-wrap">

    {{-- ── LEFT PANEL ── --}}
    <div class="signup-left">
        <img src="{{ asset(($brand['logo'] ?? 'frontend/img/logo/hello_transport.svg')) }}"
             alt="Hello Transport" class="brand-logo">
        <h2>Earn With <span>Hello Transport</span></h2>
        <div class="divider"></div>
        <p>Join our network of sales agents. Build your own book of business with the
           operational support, compliance and back-office work handled for you.</p>
        <ul class="feature-list">
            <li><i class="fas fa-check-circle"></i> Work remotely, on your own schedule</li>
            <li><i class="fas fa-check-circle"></i> Commission on every load you move</li>
            <li><i class="fas fa-check-circle"></i> Dispatch &amp; back-office support</li>
            <li><i class="fas fa-check-circle"></i> Training and onboarding provided</li>
            <li><i class="fas fa-check-circle"></i> Paid by bank transfer, Wise or PayPal</li>
        </ul>
        <div class="mt-auto pt-3" style="border-top:1px solid rgba(255,255,255,.15);margin-top:24px;">
            <p class="mb-0" style="font-size:11px;color:rgba(255,255,255,.5);">
                Already part of our team?
                <a href="{{ url('/loginn') }}" style="color:#8fc445;font-weight:700;">Sign In Here</a>
            </p>
        </div>
    </div>

    {{-- ── RIGHT PANEL ── --}}
    <div class="signup-right">

        {{-- Success state (W-04) --}}
        <div class="signup-success" id="signupSuccess">
            <div class="success-icon">✓</div>
            <h3>Application Submitted</h3>
            <p id="signupSuccessMsg">
                Thank you for applying to join our team. Your application has been received and is
                now under review.
            </p>
            <p style="font-size:13px;opacity:.75;margin-top:14px;">
                Questions? For recruitment-related enquiries, contact
                <a href="mailto:info@hellotransport.com">info@hellotransport.com</a>
            </p>
            <a href="{{ url('/') }}" class="btn-signup"
               style="max-width:300px;display:inline-block;text-decoration:none;padding:12px 40px;width:auto;">
                Back to Homepage
            </a>
        </div>

        {{-- Form state --}}
        <div id="signupFormWrap">
            <h3>Apply for an Opportunity</h3>
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

                {{-- ── Contact Information ── --}}
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
                        <label class="form-label">City <span class="text-danger">*</span></label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-city field-icon"></i>
                            <input type="text" name="city" class="form-control" placeholder="City">
                        </div>
                        <div class="field-error" id="err_city"></div>
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
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <div class="phone-combo">
                            <span class="dial" id="phoneDial">+1</span>
                            <input type="text" id="phoneLocal" class="form-control"
                                   placeholder="555 000 0000" inputmode="tel" maxlength="15">
                        </div>
                        <input type="hidden" name="phone" id="phoneFull">
                        <div class="field-error" id="err_phone"></div>
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
                        Set from your country. Your attendance, breaks and all portal times use this.
                    </small>
                    <div class="field-error" id="err_timezone"></div>
                </div>

                {{-- ── Experience ── --}}
                <div class="form-section-title">Experience</div>
                <div class="row g-2 mb-c">
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

                {{-- ── Additional ── --}}
                <div class="form-section-title">
                    Additional
                    <small class="text-muted" style="text-transform:none;font-weight:400;">(optional)</small>
                </div>
                <div class="row g-2 mb-c">
                    <div class="col-md-6">
                        <label class="form-label">Resume / CV</label>
                        <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx">
                        <small class="linked-note">PDF or Word, up to 5&nbsp;MB.</small>
                        <div class="field-error" id="err_resume"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">LinkedIn Profile</label>
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
                    <textarea name="experience" rows="3" class="form-control"
                              placeholder="Your relevant experience — sales, dispatch, logistics, customer service…"></textarea>
                    <div class="field-error" id="err_experience"></div>
                </div>

                {{-- ── Terms ── --}}
                <div class="form-section-title">Terms &amp; Conditions</div>
                <div class="mb-c">
                    <button type="button" id="tcToggleBtn" class="btn btn-sm"
                            style="background:#062e39;color:#fff;font-weight:600;border-radius:6px;padding:8px 18px;"
                            onclick="var b=document.getElementById('tcBox');var open=b.style.display!=='none';b.style.display=open?'none':'block';this.textContent=open?'View Terms and Conditions':'Hide Terms and Conditions';">
                        View Terms and Conditions
                    </button>
                    <div id="tcBox" style="display:none;max-height:260px;overflow-y:auto;border:1px solid #dee2e6;border-radius:6px;padding:12px 14px;background:#fbfbfb;font-size:12.5px;line-height:1.6;color:#444;margin-top:10px;">
                        @include('partials.hello_terms')
                    </div>
                    <label class="d-flex align-items-start gap-2 mt-2" style="cursor:pointer;">
                        <input type="checkbox" name="terms_accepted" id="termsAccepted" value="1"
                               style="margin-top:3px;width:16px;height:16px;">
                        <span style="font-size:13px;color:#333;">
                            I have read and accept the {{ $tcBrand['name'] }} Terms &amp; Conditions.
                            <span class="text-danger">*</span>
                        </span>
                    </label>
                    <div class="field-error" id="err_terms_accepted"></div>
                </div>

                <div class="row g-2 mt-3">
                    <div class="col-md-5">
                        <button type="submit" class="btn-signup" id="applySubmitBtn">
                            <i class="fas fa-paper-plane me-2"></i> Apply to Join the Network
                        </button>
                    </div>
                    <div class="col-md-7 d-flex align-items-center">
                        <p class="text-muted mb-0" style="font-size:12px;">
                            Already part of our team?
                            <a href="{{ url('/loginn') }}" style="color:#062e39;font-weight:700;">Sign In</a>
                        </p>
                    </div>
                </div>
            </form>
        </div>{{-- /signupFormWrap --}}
    </div>
</div>

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
