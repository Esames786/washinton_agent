@extends('layouts.frontend-master')

@section('content')

<style>
    .signup-section {
        background: #f0f4f8;
        padding: 40px 0 40px;
        min-height: 100vh;
    }

    /* ── Two-column layout ── */
    .signup-left {
        background: linear-gradient(160deg, #062e39 0%, #0a4a5a 60%, #0d5c70 100%);
        border-radius: 16px 0 0 16px;
        padding: 40px 36px;
        color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .signup-left .brand-logo { width: 130px; margin-bottom: 24px; }
    .signup-left h2 {
        font-size: 24px; font-weight: 800; line-height: 1.3; margin-bottom: 12px;
        font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: 1px;
    }
    .signup-left h2 span { color: #8fc445; }
    .signup-left p { font-size: 13px; color: rgba(255,255,255,.75); line-height: 1.7; margin-bottom: 20px; }
    .signup-left .feature-list { list-style: none; padding: 0; margin: 0; }
    .signup-left .feature-list li {
        display: flex; align-items: center; gap: 8px;
        font-size: 12px; color: rgba(255,255,255,.85); margin-bottom: 9px;
    }
    .signup-left .feature-list li i { color: #8fc445; font-size: 14px; flex-shrink: 0; }
    .signup-left .divider { width: 40px; height: 3px; background: #8fc445; border-radius: 2px; margin: 14px 0; }

    /* ── Right form panel ── */
    .signup-right {
        background: #fff;
        border-radius: 0 16px 16px 0;
        padding: 32px 36px;
        overflow-y: auto;
    }
    .signup-right h3 {
        font-size: 20px; font-weight: 800; color: #062e39; margin-bottom: 2px;
        font-family: 'Oswald', sans-serif; text-transform: uppercase; letter-spacing: .5px;
    }
    .signup-right .sub-title { font-size: 12px; color: #6c757d; margin-bottom: 16px; }

    /* ── Account type cards ── */
    .type-card {
        border: 2px solid #dee2e6; border-radius: 8px; padding: 10px 8px;
        text-align: center; cursor: pointer; transition: all .2s; background: #fff; height: 100%;
    }
    .type-card:hover { border-color: #8fc445; background: #f8fdf0; }
    .type-card.selected { border-color: #8fc445; background: #f4fbe8; box-shadow: 0 0 0 3px rgba(143,196,69,.15); }
    .type-card .type-icon { font-size: 22px; display: block; margin-bottom: 3px; }
    .type-card .type-label { font-weight: 700; font-size: 13px; color: #062e39; display: block; }
    .type-card .type-sub { font-size: 10px; color: #888; display: block; margin-top: 1px; }

    /* ── Form fields ── */
    .signup-right .form-label {
        font-size: 12px; font-weight: 600; color: #062e39; margin-bottom: 4px; display: block;
    }
    .signup-right .form-control, .signup-right select.form-control {
        border: 1.5px solid #dee2e6; border-radius: 7px;
        padding: 8px 12px; font-size: 13px;
        transition: border-color .2s, box-shadow .2s;
        width: 100%;
    }
    .signup-right .form-control:focus, .signup-right select.form-control:focus {
        border-color: #8fc445; box-shadow: 0 0 0 3px rgba(143,196,69,.15); outline: none;
    }
    .signup-right .form-control.is-invalid { border-color: #dc3545; }
    .input-icon-wrap { position: relative; }
    .input-icon-wrap .form-control { padding-left: 36px; }
    .input-icon-wrap .field-icon {
        position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
        color: #8fc445; font-size: 13px; pointer-events: none;
    }
    .input-icon-wrap textarea.form-control { padding-left: 36px; }
    .input-icon-wrap .field-icon.textarea-icon { top: 14px; transform: none; }

    /* ── Submit button ── */
    .btn-signup {
        background: #8fc445; border: none; color: #fff; font-weight: 700;
        padding: 11px 0; font-size: 14px; border-radius: 8px; width: 100%;
        letter-spacing: .5px; text-transform: uppercase;
        font-family: 'Oswald', sans-serif; transition: background .2s, transform .1s;
    }
    .btn-signup:hover { background: #7aad38; transform: translateY(-1px); }

    /* ── Section divider ── */
    .form-section-title {
        font-size: 10px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 1px; color: #adb5bd; margin: 14px 0 10px;
        display: flex; align-items: center; gap: 8px;
    }
    .form-section-title::after { content: ''; flex: 1; height: 1px; background: #e9ecef; }

    /* ── mb compact ── */
    .mb-c { margin-bottom: 10px; }

    /* ── Responsive ── */
    @media (max-width: 767px) {
        .signup-left { border-radius: 16px 16px 0 0; padding: 28px 20px; }
        .signup-right { border-radius: 0 0 16px 16px; padding: 24px 16px; }
        .signup-section { padding: 20px 0; }
    }
</style>

<section class="signup-section">
    <div class="container-fluid px-3 px-md-4">
        <div class="row justify-content-center">
            {{-- Wider container: col-xl-11 --}}
            <div class="col-xl-11 col-lg-12">
                <div class="row g-0 shadow" style="border-radius:16px;">

                    {{-- ── LEFT PANEL (3 cols) ── --}}
                    <div class="col-md-3 signup-left">
                        <img src="{{ asset('frontend/img/logo/hello_transport.svg') }}"
                             alt="Hello Transport" class="brand-logo">
                        <h2>Join Our <span>Network</span></h2>
                        <div class="divider"></div>
                        <p>Create your account and get access to the DayDispatch portal.</p>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle"></i> Real-time order tracking</li>
                            <li><i class="fas fa-check-circle"></i> Nationwide auto transport</li>
                            <li><i class="fas fa-check-circle"></i> Dedicated support team</li>
                            <li><i class="fas fa-check-circle"></i> Instant quote management</li>
                            <li><i class="fas fa-check-circle"></i> Secure & reliable platform</li>
                        </ul>
                        <div class="mt-auto pt-3" style="border-top:1px solid rgba(255,255,255,.15);margin-top:24px;">
                            <p class="mb-0" style="font-size:11px;color:rgba(255,255,255,.5);">
                                Already have an account?
                                <a href="{{ url('/loginn') }}" style="color:#8fc445;font-weight:700;">Sign In Here</a>
                            </p>
                        </div>
                    </div>

                    {{-- ── RIGHT FORM PANEL (9 cols) ── --}}
                    <div class="col-md-9 signup-right">
                        <h3>Create Account</h3>
                        <p class="sub-title">Fill in the details below to request portal access</p>

                        @if (session('success'))
                            <div class="alert alert-success py-2 small" role="alert">
                                <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger py-2 small" role="alert">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.store') }}" novalidate>
                            @csrf

                            {{-- ── ROW 1: Account Type (full width, 2 cards) ── --}}
                            <div class="mb-c">
                                <label class="form-label">Account Type <span class="text-danger">*</span></label>
                                <div class="row g-2">
                                    <div class="col-6 col-md-3">
                                        <label class="type-card w-100 {{ old('signup_type', 'agent') === 'agent' ? 'selected' : '' }}" id="card-agent">
                                            <input type="radio" name="signup_type" value="agent" class="d-none"
                                                   {{ old('signup_type', 'agent') === 'agent' ? 'checked' : '' }}>
                                            <span class="type-icon">🧑‍💼</span>
                                            <span class="type-label">Agent</span>
                                            <span class="type-sub">Order Taker / Sales</span>
                                        </label>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <label class="type-card w-100 {{ old('signup_type') === 'carrier' ? 'selected' : '' }}" id="card-carrier">
                                            <input type="radio" name="signup_type" value="carrier" class="d-none"
                                                   {{ old('signup_type') === 'carrier' ? 'checked' : '' }}>
                                            <span class="type-icon">🚛</span>
                                            <span class="type-label">Carrier</span>
                                            <span class="type-sub">Dispatcher / Driver</span>
                                        </label>
                                    </div>
                                </div>
                                @error('signup_type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- ── PERSONAL INFO ── --}}
                            <div class="form-section-title">Personal Information</div>

                            {{-- Row: First Name | Last Name | Slug --}}
                            <div class="row g-2 mb-c">
                                <div class="col-md-4">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-user field-icon"></i>
                                        <input type="text" name="name" value="{{ old('name') }}"
                                               class="form-control @error('name') is-invalid @enderror"
                                               placeholder="First name" required>
                                    </div>
                                    @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-user field-icon"></i>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                                               class="form-control @error('last_name') is-invalid @enderror"
                                               placeholder="Last name" required>
                                    </div>
                                    @error('last_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">
                                        Display Name / Slug <span class="text-danger">*</span>
                                        <span class="text-muted fw-normal" style="font-size:10px;">(e.g. john.doe)</span>
                                    </label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-id-badge field-icon"></i>
                                        <input type="text" name="slug" value="{{ old('slug') }}"
                                               class="form-control @error('slug') is-invalid @enderror"
                                               placeholder="e.g. john.doe" required>
                                    </div>
                                    @error('slug') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Row: Father Name | DOB | Gender --}}
                            <div class="row g-2 mb-c">
                                <div class="col-md-4">
                                    <label class="form-label">Father Name</label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-user-tie field-icon"></i>
                                        <input type="text" name="father_name" value="{{ old('father_name') }}"
                                               class="form-control @error('father_name') is-invalid @enderror"
                                               placeholder="Father's name">
                                    </div>
                                    @error('father_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Date of Birth</label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-calendar field-icon"></i>
                                        <input type="date" name="dob" value="{{ old('dob') }}"
                                               class="form-control @error('dob') is-invalid @enderror"
                                               max="{{ date('Y-m-d') }}">
                                    </div>
                                    @error('dob') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                        <option value="">-- Select Gender --</option>
                                        <option value="male"   {{ old('gender') == 'male'   ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other"  {{ old('gender') == 'other'  ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Row: CNIC | Marital Status | (empty) --}}
                            <div class="row g-2 mb-c">
                                <div class="col-md-4">
                                    <label class="form-label">CNIC / ID Card</label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-id-card field-icon"></i>
                                        <input type="text" name="cnic" value="{{ old('cnic') }}"
                                               class="form-control @error('cnic') is-invalid @enderror"
                                               placeholder="e.g. 42101-1234567-1">
                                    </div>
                                    @error('cnic') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Marital Status</label>
                                    <select name="marital_status" class="form-control @error('marital_status') is-invalid @enderror">
                                        <option value="">-- Select --</option>
                                        <option value="single"   {{ old('marital_status') == 'single'   ? 'selected' : '' }}>Single</option>
                                        <option value="married"  {{ old('marital_status') == 'married'  ? 'selected' : '' }}>Married</option>
                                        <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="widowed"  {{ old('marital_status') == 'widowed'  ? 'selected' : '' }}>Widowed</option>
                                    </select>
                                    @error('marital_status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- ── CONTACT & LOGIN ── --}}
                            <div class="form-section-title">Contact & Login</div>

                            {{-- Row: Email | Phone | Address --}}
                            <div class="row g-2 mb-c">
                                <div class="col-md-4">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-envelope field-icon"></i>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                               class="form-control @error('email') is-invalid @enderror"
                                               placeholder="your@email.com" required>
                                    </div>
                                    @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-phone field-icon"></i>
                                        <input type="text" name="phone" value="{{ old('phone') }}"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               placeholder="+1 (555) 000-0000" required>
                                    </div>
                                    @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Full Address <span class="text-danger">*</span></label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-map-marker-alt field-icon textarea-icon"></i>
                                        <textarea name="address" rows="1"
                                                  class="form-control @error('address') is-invalid @enderror"
                                                  placeholder="Street address" required>{{ old('address') }}</textarea>
                                    </div>
                                    @error('address') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Row: City | State | Country --}}
                            <div class="row g-2 mb-c">
                                <div class="col-md-4">
                                    <label class="form-label">City</label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-city field-icon"></i>
                                        <input type="text" name="city" value="{{ old('city') }}"
                                               class="form-control @error('city') is-invalid @enderror"
                                               placeholder="City">
                                    </div>
                                    @error('city') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">State / Province</label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-map field-icon"></i>
                                        <input type="text" name="state" value="{{ old('state') }}"
                                               class="form-control @error('state') is-invalid @enderror"
                                               placeholder="State / Province">
                                    </div>
                                    @error('state') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Country</label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-globe field-icon"></i>
                                        <input type="text" name="country" value="{{ old('country') }}"
                                               class="form-control @error('country') is-invalid @enderror"
                                               placeholder="Country">
                                    </div>
                                    @error('country') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Row: Password | Confirm Password | (empty) --}}
                            <div class="row g-2 mb-c">
                                <div class="col-md-4">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-lock field-icon"></i>
                                        <input type="password" name="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               placeholder="Min. 8 characters" required>
                                    </div>
                                    @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-lock field-icon"></i>
                                        <input type="password" name="password_confirmation"
                                               class="form-control"
                                               placeholder="Re-enter password" required>
                                    </div>
                                </div>
                            </div>

                            {{-- ── WORK PREFERENCES ── --}}
                            <div class="form-section-title">Work Preferences</div>

                            {{-- Row: Shift | Pay Type --}}
                            <div class="row g-2 mb-c">
                                <div class="col-md-4">
                                    <label class="form-label">Shift Timing <span class="text-danger">*</span></label>
                                    <select name="shift_type_id"
                                            class="form-control @error('shift_type_id') is-invalid @enderror" required>
                                        <option value="">-- Select Shift --</option>
                                        <option value="1" {{ old('shift_type_id') == 1 ? 'selected' : '' }}>🌅 Morning (9am – 5pm)</option>
                                        <option value="2" {{ old('shift_type_id') == 2 ? 'selected' : '' }}>🌆 Evening (2pm – 10pm)</option>
                                        <option value="3" {{ old('shift_type_id') == 3 ? 'selected' : '' }}>🌙 Night (8pm – 4am)</option>
                                        <option value="4" {{ old('shift_type_id') == 4 ? 'selected' : '' }}>🕙 General (10am – 6pm)</option>
                                    </select>
                                    @error('shift_type_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Pay Type <span class="text-danger">*</span></label>
                                    <select name="account_type_id"
                                            class="form-control @error('account_type_id') is-invalid @enderror" required>
                                        <option value="">-- Select Pay Type --</option>
                                        <option value="1" {{ old('account_type_id') == 1 ? 'selected' : '' }}>💰 Salary Only</option>
                                        <option value="2" {{ old('account_type_id') == 2 ? 'selected' : '' }}>📈 Commission Only</option>
                                        <option value="3" {{ old('account_type_id', 3) == 3 ? 'selected' : '' }}>💰📈 Salary + Commission</option>
                                    </select>
                                    @error('account_type_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="row g-2 mt-2">
                                <div class="col-md-6">
                                    <button type="submit" class="btn-signup">
                                        <i class="fas fa-user-plus me-2"></i> Create Account
                                    </button>
                                </div>
                                <div class="col-md-6 d-flex align-items-center">
                                    <p class="text-muted mb-0" style="font-size:12px;">
                                        Already have an account?
                                        <a href="{{ url('/loginn') }}" style="color:#062e39;font-weight:700;">Sign In</a>
                                    </p>
                                </div>
                            </div>

                        </form>
                    </div>{{-- /signup-right --}}

                </div>{{-- /row --}}
            </div>
        </div>
    </div>
</section>

<script>
document.querySelectorAll('input[name="signup_type"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.type-card').forEach(c => c.classList.remove('selected'));
        this.closest('.type-card').classList.add('selected');
    });
});
document.querySelectorAll('input[name="signup_type"]').forEach(function(radio) {
    if (radio.checked) radio.closest('.type-card').classList.add('selected');
});
</script>

@endsection
