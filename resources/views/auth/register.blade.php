@extends('layouts.new-master')
@section('page_title', 'Create Account')

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
.input-icon-wrap .form-control { padding-left: 36px; }
.input-icon-wrap .field-icon {
    position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
    color: #8fc445; font-size: 13px; pointer-events: none;
}
.input-icon-wrap textarea.form-control { padding-left: 36px; }
.input-icon-wrap .field-icon.textarea-icon { top: 14px; transform: none; }
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
</style>

<div class="signup-wrap">

    {{-- ── LEFT PANEL ── --}}
    <div class="signup-left">
        <img src="{{ asset('frontend/img/logo/hello_transport.svg') }}"
             alt="Hello Transport" class="brand-logo">
        <h2>Join Our <span>Network</span></h2>
        <div class="divider"></div>
        <p>Create your account and get access to the Hello Transport portal.</p>
        <ul class="feature-list">
            <li><i class="fas fa-check-circle"></i> Real-time order tracking</li>
            <li><i class="fas fa-check-circle"></i> Nationwide auto transport</li>
            <li><i class="fas fa-check-circle"></i> Dedicated support team</li>
            <li><i class="fas fa-check-circle"></i> Instant quote management</li>
            <li><i class="fas fa-check-circle"></i> Secure &amp; reliable platform</li>
        </ul>
        <div class="mt-auto pt-3" style="border-top:1px solid rgba(255,255,255,.15);margin-top:24px;">
            <p class="mb-0" style="font-size:11px;color:rgba(255,255,255,.5);">
                Already have an account?
                <a href="{{ url('/loginn') }}" style="color:#8fc445;font-weight:700;">Sign In Here</a>
            </p>
        </div>
    </div>

    {{-- ── RIGHT PANEL ── --}}
    <div class="signup-right">

        {{-- Success state --}}
        <div class="signup-success" id="signupSuccess">
            <div class="success-icon">✓</div>
            <h3>Account Created!</h3>
            <p id="signupSuccessMsg">Your account has been created and is pending admin approval.</p>
            <a href="{{ url('/loginn') }}" class="btn-signup" style="max-width:300px;display:inline-block;text-decoration:none;padding:12px 40px;width:auto;">
                Go to Login
            </a>
        </div>

        {{-- Form state --}}
        <div id="signupFormWrap">
            <h3>Create Account</h3>
            <p class="sub-title">Fill in the details below to request portal access</p>

            {{-- Global error --}}
            <div id="signupGlobalError" class="alert alert-danger py-2 small" style="display:none;"></div>

            <form id="signupForm" novalidate>
                @csrf

                {{-- Account Type --}}
                <div class="mb-c">
                    <label class="form-label">Account Type <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        <div class="col-6 col-md-3">
                            <label class="type-card w-100 selected" id="card-agent">
                                <input type="radio" name="signup_type" value="agent" class="d-none" checked>
                                <span class="type-icon">🧑‍💼</span>
                                <span class="type-label">Agent</span>
                                <span class="type-sub">Order Taker / Sales</span>
                            </label>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="type-card w-100" id="card-carrier">
                                <input type="radio" name="signup_type" value="carrier" class="d-none">
                                <span class="type-icon">🚛</span>
                                <span class="type-label">Carrier</span>
                                <span class="type-sub">Dispatcher / Driver</span>
                            </label>
                        </div>
                    </div>
                    <div class="field-error" id="err_signup_type"></div>
                </div>

                {{-- Personal Information --}}
                <div class="form-section-title">Personal Information</div>

                <div class="row g-2 mb-c">
                    <div class="col-md-4">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-user field-icon"></i>
                            <input type="text" name="name" class="form-control" placeholder="First name">
                        </div>
                        <div class="field-error" id="err_name"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-user field-icon"></i>
                            <input type="text" name="last_name" class="form-control" placeholder="Last name">
                        </div>
                        <div class="field-error" id="err_last_name"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            Display Name / Slug <span class="text-danger">*</span>
                            <span class="text-muted fw-normal" style="font-size:10px;">(e.g. john.doe)</span>
                        </label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-id-badge field-icon"></i>
                            <input type="text" name="slug" class="form-control" placeholder="e.g. john.doe">
                        </div>
                        <div class="field-error" id="err_slug"></div>
                    </div>
                </div>

                <div class="row g-2 mb-c">
                    <div class="col-md-4">
                        <label class="form-label">Father Name</label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-user-tie field-icon"></i>
                            <input type="text" name="father_name" class="form-control" placeholder="Father's name">
                        </div>
                        <div class="field-error" id="err_father_name"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date of Birth</label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-calendar field-icon"></i>
                            <input type="date" name="dob" class="form-control" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="field-error" id="err_dob"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-control">
                            <option value="">-- Select Gender --</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                        <div class="field-error" id="err_gender"></div>
                    </div>
                </div>

                <div class="row g-2 mb-c">
                    <div class="col-md-4">
                        <label class="form-label">CNIC / ID Card</label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-id-card field-icon"></i>
                            <input type="text" name="cnic" class="form-control" placeholder="e.g. 42101-1234567-1">
                        </div>
                        <div class="field-error" id="err_cnic"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Marital Status</label>
                        <select name="marital_status" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                            <option value="divorced">Divorced</option>
                            <option value="widowed">Widowed</option>
                        </select>
                        <div class="field-error" id="err_marital_status"></div>
                    </div>
                </div>

                {{-- Contact & Login --}}
                <div class="form-section-title">Contact &amp; Login</div>

                <div class="row g-2 mb-c">
                    <div class="col-md-4">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-envelope field-icon"></i>
                            <input type="email" name="email" class="form-control" placeholder="your@email.com">
                        </div>
                        <div class="field-error" id="err_email"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-phone field-icon"></i>
                            <input type="text" name="phone" class="form-control" placeholder="+1 (555) 000-0000">
                        </div>
                        <div class="field-error" id="err_phone"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Full Address <span class="text-danger">*</span></label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-map-marker-alt field-icon textarea-icon"></i>
                            <textarea name="address" rows="1" class="form-control" placeholder="Street address"></textarea>
                        </div>
                        <div class="field-error" id="err_address"></div>
                    </div>
                </div>

                <div class="row g-2 mb-c">
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-city field-icon"></i>
                            <input type="text" name="city" class="form-control" placeholder="City">
                        </div>
                        <div class="field-error" id="err_city"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">State / Province</label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-map field-icon"></i>
                            <input type="text" name="state" class="form-control" placeholder="State / Province">
                        </div>
                        <div class="field-error" id="err_state"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Country</label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-globe field-icon"></i>
                            <input type="text" name="country" class="form-control" placeholder="Country">
                        </div>
                        <div class="field-error" id="err_country"></div>
                    </div>
                </div>

                <div class="row g-2 mb-c">
                    <div class="col-md-4">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-lock field-icon"></i>
                            <input type="password" name="password" class="form-control" placeholder="Min. 8 characters">
                        </div>
                        <div class="field-error" id="err_password"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-lock field-icon"></i>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter password">
                        </div>
                    </div>
                </div>

                {{-- Work Preferences --}}
                <div class="form-section-title">Work Preferences</div>

                <div class="row g-2 mb-c">
                    <div class="col-md-4">
                        <label class="form-label">Shift Timing <span class="text-danger">*</span></label>
                        <select name="shift_type_id" class="form-control">
                            <option value="">-- Select Shift --</option>
                            <option value="1">🌅 Morning (9am – 5pm)</option>
                            <option value="2">🌆 Evening (2pm – 10pm)</option>
                            <option value="3">🌙 Night (8pm – 4am)</option>
                            <option value="4">🕙 General (10am – 6pm)</option>
                        </select>
                        <div class="field-error" id="err_shift_type_id"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Pay Type <span class="text-danger">*</span></label>
                        <select name="account_type_id" class="form-control">
                            <option value="">-- Select Pay Type --</option>
                            <option value="1">💰 Salary Only</option>
                            <option value="2">📈 Commission Only</option>
                            <option value="3" selected>💰📈 Salary + Commission</option>
                        </select>
                        <div class="field-error" id="err_account_type_id"></div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="row g-2 mt-3">
                    <div class="col-md-5">
                        <button type="submit" class="btn-signup" id="signupSubmitBtn">
                            <i class="fas fa-user-plus me-2"></i> Create Account
                        </button>
                    </div>
                    <div class="col-md-7 d-flex align-items-center">
                        <p class="text-muted mb-0" style="font-size:12px;">
                            Already have an account?
                            <a href="{{ url('/loginn') }}" style="color:#062e39;font-weight:700;">Sign In</a>
                        </p>
                    </div>
                </div>
            </form>
        </div>{{-- /signupFormWrap --}}

    </div>{{-- /signup-right --}}
</div>{{-- /signup-wrap --}}

<script>
// Destroy nice-select on signup form so native selects are used
(function waitForJQ() {
    if (typeof window.jQuery === 'undefined') { setTimeout(waitForJQ, 50); return; }
    if (window.jQuery.fn.niceSelect) {
        window.jQuery('.signup-right select').niceSelect('destroy');
    }
}());

(function () {
    // Account type card toggle
    document.querySelectorAll('input[name="signup_type"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.type-card').forEach(function (c) { c.classList.remove('selected'); });
            this.closest('.type-card').classList.add('selected');
        });
    });

    // Clear field errors on input
    document.querySelectorAll('#signupForm .form-control').forEach(function (el) {
        el.addEventListener('input', function () {
            this.classList.remove('is-invalid');
            var errEl = document.getElementById('err_' + this.name);
            if (errEl) { errEl.style.display = 'none'; errEl.textContent = ''; }
        });
    });

    // AJAX submit
    document.getElementById('signupForm').addEventListener('submit', function (e) {
        e.preventDefault();

        // Clear all errors
        document.querySelectorAll('.field-error').forEach(function (el) {
            el.style.display = 'none'; el.textContent = '';
        });
        document.querySelectorAll('.form-control.is-invalid').forEach(function (el) {
            el.classList.remove('is-invalid');
        });
        document.getElementById('signupGlobalError').style.display = 'none';

        var btn = document.getElementById('signupSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Creating Account...';

        var formData = new FormData(this);

        fetch('{{ route("register.store") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': formData.get('_token'), 'Accept': 'application/json' },
            body: formData,
        })
        .then(function (res) { return res.json().then(function (data) { return { status: res.status, data: data }; }); })
        .then(function (res) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-user-plus me-2"></i> Create Account';

            if (res.status === 200 && res.data.success) {
                // Show success screen
                document.getElementById('signupFormWrap').style.display = 'none';
                var succ = document.getElementById('signupSuccess');
                succ.style.display = 'block';
                document.getElementById('signupSuccessMsg').textContent = res.data.message;
                return;
            }

            if (res.status === 422 && res.data.errors) {
                var errors = res.data.errors;
                Object.keys(errors).forEach(function (field) {
                    var input = document.querySelector('#signupForm [name="' + field + '"]');
                    if (input) input.classList.add('is-invalid');
                    var errEl = document.getElementById('err_' + field);
                    if (errEl) {
                        errEl.textContent = errors[field][0];
                        errEl.style.display = 'block';
                    }
                });
                // Scroll to first error
                var first = document.querySelector('.form-control.is-invalid');
                if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            // Other server error
            var errBox = document.getElementById('signupGlobalError');
            errBox.textContent = res.data.message || 'Registration failed. Please try again.';
            errBox.style.display = 'block';
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-user-plus me-2"></i> Create Account';
            var errBox = document.getElementById('signupGlobalError');
            errBox.textContent = 'Network error. Please check your connection and try again.';
            errBox.style.display = 'block';
        });
    });
})();
</script>

@endsection
