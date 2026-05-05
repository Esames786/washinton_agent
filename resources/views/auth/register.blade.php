@extends('layouts.frontend-master')

@section('content')

<style>
    /* ── Page wrapper ── */
    .signup-section {
        background: #f0f4f8;
        padding: 80px 0 80px;
        min-height: 100vh;
    }

    /* ── Two-column layout ── */
    .signup-left {
        background: linear-gradient(160deg, #062e39 0%, #0a4a5a 60%, #0d5c70 100%);
        border-radius: 16px 0 0 16px;
        padding: 52px 44px;
        color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .signup-left .brand-logo {
        width: 160px;
        margin-bottom: 32px;
    }
    .signup-left h2 {
        font-size: 30px;
        font-weight: 800;
        line-height: 1.3;
        margin-bottom: 16px;
        font-family: 'Oswald', sans-serif;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .signup-left h2 span { color: #8fc445; }
    .signup-left p {
        font-size: 14px;
        color: rgba(255,255,255,.75);
        line-height: 1.8;
        margin-bottom: 28px;
    }
    .signup-left .feature-list { list-style: none; padding: 0; margin: 0; }
    .signup-left .feature-list li {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: rgba(255,255,255,.85);
        margin-bottom: 12px;
    }
    .signup-left .feature-list li i {
        color: #8fc445;
        font-size: 16px;
        flex-shrink: 0;
    }
    .signup-left .divider {
        width: 50px;
        height: 3px;
        background: #8fc445;
        border-radius: 2px;
        margin: 20px 0;
    }

    /* ── Right form panel ── */
    .signup-right {
        background: #fff;
        border-radius: 0 16px 16px 0;
        padding: 44px 40px;
    }
    .signup-right h3 {
        font-size: 22px;
        font-weight: 800;
        color: #062e39;
        margin-bottom: 4px;
        font-family: 'Oswald', sans-serif;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .signup-right .sub-title {
        font-size: 13px;
        color: #6c757d;
        margin-bottom: 24px;
    }

    /* ── Account type cards ── */
    .type-card {
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 16px 12px;
        text-align: center;
        cursor: pointer;
        transition: all .2s;
        background: #fff;
        height: 100%;
    }
    .type-card:hover { border-color: #8fc445; background: #f8fdf0; }
    .type-card.selected { border-color: #8fc445; background: #f4fbe8; box-shadow: 0 0 0 3px rgba(143,196,69,.15); }
    .type-card .type-icon { font-size: 32px; display: block; margin-bottom: 6px; }
    .type-card .type-label { font-weight: 700; font-size: 14px; color: #062e39; display: block; }
    .type-card .type-sub { font-size: 11px; color: #888; display: block; margin-top: 2px; }

    /* ── Form fields ── */
    .signup-right .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #062e39;
        margin-bottom: 5px;
    }
    .signup-right .form-control {
        border: 1.5px solid #dee2e6;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 14px;
        transition: border-color .2s, box-shadow .2s;
    }
    .signup-right .form-control:focus {
        border-color: #8fc445;
        box-shadow: 0 0 0 3px rgba(143,196,69,.15);
        outline: none;
    }
    .signup-right .form-control.is-invalid { border-color: #dc3545; }
    .input-icon-wrap { position: relative; }
    .input-icon-wrap .form-control { padding-left: 40px; }
    .input-icon-wrap .field-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #8fc445;
        font-size: 15px;
    }

    /* ── Submit button ── */
    .btn-signup {
        background: #8fc445;
        border: none;
        color: #fff;
        font-weight: 700;
        padding: 13px 0;
        font-size: 15px;
        border-radius: 8px;
        width: 100%;
        letter-spacing: .5px;
        text-transform: uppercase;
        font-family: 'Oswald', sans-serif;
        transition: background .2s, transform .1s;
    }
    .btn-signup:hover { background: #7aad38; transform: translateY(-1px); }
    .btn-signup:active { transform: translateY(0); }

    /* ── Section divider ── */
    .form-section-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #adb5bd;
        margin: 20px 0 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e9ecef;
    }

    /* ── Responsive ── */
    @media (max-width: 767px) {
        .signup-left { border-radius: 16px 16px 0 0; padding: 36px 24px; }
        .signup-right { border-radius: 0 0 16px 16px; padding: 32px 20px; }
        .signup-section { padding: 40px 0; }
    }
</style>

<section class="signup-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-11">
                <div class="row g-0 shadow" style="border-radius:16px;">

                    {{-- ── LEFT PANEL ── --}}
                    <div class="col-md-5 signup-left">
                        <img src="{{ asset('frontend/img/logo/hello_transport.svg') }}"
                             alt="Hello Transport" class="brand-logo">

                        <h2>Join Our <span>Network</span></h2>
                        <div class="divider"></div>
                        <p>
                            Create your account and get access to the DayDispatch portal.
                            Manage orders, track shipments, and grow your business with us.
                        </p>

                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle"></i> Real-time order tracking</li>
                            <li><i class="fas fa-check-circle"></i> Nationwide auto transport network</li>
                            <li><i class="fas fa-check-circle"></i> Dedicated support team</li>
                            <li><i class="fas fa-check-circle"></i> Instant quote management</li>
                            <li><i class="fas fa-check-circle"></i> Secure & reliable platform</li>
                        </ul>

                        <div class="mt-auto pt-4" style="border-top:1px solid rgba(255,255,255,.15);margin-top:32px;">
                            <p class="mb-0" style="font-size:12px;color:rgba(255,255,255,.5);">
                                Already have an account?
                                <a href="{{ url('/loginn') }}" style="color:#8fc445;font-weight:700;">Sign In Here</a>
                            </p>
                        </div>
                    </div>

                    {{-- ── RIGHT FORM PANEL ── --}}
                    <div class="col-md-7 signup-right">
                        <h3>Create Account</h3>
                        <p class="sub-title">Fill in the details below to request portal access</p>

                        {{-- Alerts --}}
                        @if (session('success'))
                            <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.store') }}" novalidate>
                            @csrf

                            {{-- Account Type --}}
                            <div class="mb-3">
                                <label class="form-label">Account Type <span class="text-danger">*</span></label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="type-card w-100 {{ old('signup_type', 'agent') === 'agent' ? 'selected' : '' }}" id="card-agent">
                                            <input type="radio" name="signup_type" value="agent" class="d-none"
                                                   {{ old('signup_type', 'agent') === 'agent' ? 'checked' : '' }}>
                                            <span class="type-icon">🧑‍💼</span>
                                            <span class="type-label">Agent</span>
                                            <span class="type-sub">Order Taker / Sales</span>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <label class="type-card w-100 {{ old('signup_type') === 'carrier' ? 'selected' : '' }}" id="card-carrier">
                                            <input type="radio" name="signup_type" value="carrier" class="d-none"
                                                   {{ old('signup_type') === 'carrier' ? 'checked' : '' }}>
                                            <span class="type-icon">🚛</span>
                                            <span class="type-label">Carrier</span>
                                            <span class="type-sub">Dispatcher / Driver</span>
                                        </label>
                                    </div>
                                </div>
                                @error('signup_type')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Personal Info --}}
                            <div class="form-section-title">Personal Information</div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-user field-icon"></i>
                                        <input type="text" name="name" value="{{ old('name') }}"
                                               class="form-control @error('name') is-invalid @enderror"
                                               placeholder="First name" required>
                                    </div>
                                    @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-user field-icon"></i>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                                               class="form-control @error('last_name') is-invalid @enderror"
                                               placeholder="Last name" required>
                                    </div>
                                    @error('last_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Display Name / Slug <span class="text-danger">*</span>
                                    <span class="text-muted fw-normal" style="font-size:11px;">(shown in system, e.g. john.doe)</span>
                                </label>
                                <div class="input-icon-wrap">
                                    <i class="fas fa-id-badge field-icon"></i>
                                    <input type="text" name="slug" value="{{ old('slug') }}"
                                           class="form-control @error('slug') is-invalid @enderror"
                                           placeholder="e.g. john.doe or JohnD" required>
                                </div>
                                @error('slug') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            {{-- Contact Info --}}
                            <div class="form-section-title">Contact & Login</div>

                            <div class="mb-3">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <div class="input-icon-wrap">
                                    <i class="fas fa-envelope field-icon"></i>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="your@email.com" required>
                                </div>
                                @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <div class="input-icon-wrap">
                                    <i class="fas fa-phone field-icon"></i>
                                    <input type="text" name="phone" value="{{ old('phone') }}"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           placeholder="+1 (555) 000-0000" required>
                                </div>
                                @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-lock field-icon"></i>
                                        <input type="password" name="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               placeholder="Min. 8 characters" required>
                                    </div>
                                    @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="input-icon-wrap">
                                        <i class="fas fa-lock field-icon"></i>
                                        <input type="password" name="password_confirmation"
                                               class="form-control"
                                               placeholder="Re-enter password" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Full Address <span class="text-danger">*</span></label>
                                <div class="input-icon-wrap">
                                    <i class="fas fa-map-marker-alt field-icon" style="top:18px;transform:none;"></i>
                                    <textarea name="address" rows="2"
                                              class="form-control @error('address') is-invalid @enderror"
                                              style="padding-left:40px;"
                                              placeholder="Enter your complete address" required>{{ old('address') }}</textarea>
                                </div>
                                @error('address') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <button type="submit" class="btn-signup">
                                <i class="fas fa-user-plus me-2"></i> Create Account
                            </button>

                            <p class="text-center text-muted mt-3 mb-0" style="font-size:13px;">
                                Already have an account?
                                <a href="{{ url('/loginn') }}" style="color:#062e39;font-weight:700;">Sign In</a>
                            </p>
                        </form>
                    </div>

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
// Init on load
document.querySelectorAll('input[name="signup_type"]').forEach(function(radio) {
    if (radio.checked) radio.closest('.type-card').classList.add('selected');
});
</script>

@endsection
