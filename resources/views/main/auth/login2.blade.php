@extends('layouts.frontend-master')

@section('content')
<style>
    .auth-section {
        min-height: 100vh;
        background: linear-gradient(135deg, #060e14 0%, #0a1a24 50%, #0d2030 100%);
        display: flex;
        align-items: center;
        padding: 60px 0;
        position: relative;
        overflow: hidden;
    }
    /* Background pattern */
    .auth-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 20% 30%, rgba(201,168,76,0.06) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(201,168,76,0.04) 0%, transparent 50%);
        pointer-events: none;
    }
    .auth-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(201,168,76,0.2);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 30px 80px rgba(0,0,0,0.5);
        backdrop-filter: blur(10px);
    }
    /* Left panel */
    .auth-left {
        background: linear-gradient(160deg, #0d1f2d 0%, #071520 100%);
        padding: 60px 44px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        border-right: 1px solid rgba(201,168,76,0.15);
        min-height: 520px;
    }
    .auth-left .brand-logo {
        width: 110px;
        height: 110px;
        margin-bottom: 28px;
        filter: drop-shadow(0 4px 20px rgba(201,168,76,0.3));
    }
    .auth-left h2 {
        font-family: 'Oswald', sans-serif;
        font-size: 26px;
        font-weight: 700;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 10px;
    }
    .auth-left h2 span { color: #C9A84C; }
    .auth-left .divider {
        width: 40px; height: 3px;
        background: linear-gradient(90deg, #FFD700, #C9A84C);
        border-radius: 2px;
        margin: 14px auto 18px;
    }
    .auth-left p {
        font-size: 13px;
        color: rgba(255,255,255,0.55);
        line-height: 1.8;
        max-width: 240px;
    }
    .auth-left .feature-list {
        list-style: none; padding: 0; margin: 24px 0 0; text-align: left; width: 100%;
    }
    .auth-left .feature-list li {
        font-size: 12px;
        color: rgba(255,255,255,0.65);
        padding: 6px 0;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .auth-left .feature-list li:last-child { border-bottom: none; }
    .auth-left .feature-list li i { color: #C9A84C; font-size: 13px; }

    /* Right panel */
    .auth-right {
        padding: 52px 44px;
        background: #0a1520;
    }
    .auth-right .auth-title {
        font-family: 'Oswald', sans-serif;
        font-size: 28px;
        font-weight: 700;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 4px;
    }
    .auth-right .auth-subtitle {
        font-size: 13px;
        color: rgba(255,255,255,0.45);
        margin-bottom: 32px;
    }
    .auth-right .form-label {
        font-size: 12px;
        font-weight: 600;
        color: rgba(255,255,255,0.7);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 6px;
    }
    .auth-right .input-wrap { position: relative; margin-bottom: 20px; }
    .auth-right .input-wrap .field-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #C9A84C;
        font-size: 15px;
    }
    .auth-right .form-control {
        background: rgba(255,255,255,0.05);
        border: 1.5px solid rgba(201,168,76,0.2);
        border-radius: 10px;
        padding: 12px 14px 12px 42px;
        font-size: 14px;
        color: #fff;
        transition: border-color .2s, box-shadow .2s;
        width: 100%;
    }
    .auth-right .form-control:focus {
        border-color: #C9A84C;
        box-shadow: 0 0 0 3px rgba(201,168,76,0.12);
        outline: none;
        background: rgba(255,255,255,0.07);
        color: #fff;
    }
    .auth-right .form-control::placeholder { color: rgba(255,255,255,0.3); }
    .btn-login {
        background: linear-gradient(135deg, #FFD700, #C9A84C);
        border: none;
        color: #000;
        font-weight: 800;
        padding: 13px 0;
        font-size: 14px;
        border-radius: 10px;
        width: 100%;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-family: 'Oswald', sans-serif;
        transition: all .2s;
        cursor: pointer;
    }
    .btn-login:hover {
        background: linear-gradient(135deg, #FFE44D, #D4A830);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(201,168,76,0.3);
    }
    .auth-divider {
        display: flex; align-items: center; gap: 12px;
        margin: 24px 0;
    }
    .auth-divider span { color: rgba(255,255,255,0.2); font-size: 12px; white-space: nowrap; }
    .auth-divider::before, .auth-divider::after {
        content: ''; flex: 1; height: 1px; background: rgba(255,255,255,0.08);
    }
    .auth-footer-text {
        text-align: center;
        font-size: 13px;
        color: rgba(255,255,255,0.4);
        margin-top: 20px;
    }
    .auth-footer-text a { color: #C9A84C; font-weight: 600; text-decoration: none; }
    .auth-footer-text a:hover { color: #FFD700; }
    .alert-danger {
        background: rgba(220,53,69,0.15);
        border: 1px solid rgba(220,53,69,0.3);
        color: #ff6b7a;
        border-radius: 10px;
        font-size: 13px;
        padding: 12px 16px;
        margin-bottom: 20px;
    }
    @media (max-width: 767px) {
        .auth-left { display: none; }
        .auth-right { padding: 36px 24px; }
        .auth-section { padding: 30px 0; }
    }
</style>

<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10">
                <div class="auth-card">
                    <div class="row g-0">

                        {{-- LEFT PANEL --}}
                        <div class="col-md-5 auth-left">
                            <img src="{{ asset('frontend/img/logo/hello_transport.svg') }}"
                                 class="brand-logo" alt="Hello Transport">
                            <h2>Hello <span>Transport</span></h2>
                            <div class="divider"></div>
                            <p>Sign in to access your portal and manage your transport operations.</p>
                            <ul class="feature-list">
                                <li><i class="fas fa-truck"></i> Real-time order tracking</li>
                                <li><i class="fas fa-map-marker-alt"></i> Nationwide coverage</li>
                                <li><i class="fas fa-shield-alt"></i> Secure & reliable platform</li>
                                <li><i class="fas fa-headset"></i> 24/7 support team</li>
                            </ul>
                        </div>

                        {{-- RIGHT FORM --}}
                        <div class="col-md-7 auth-right">
                            <div class="auth-title">Sign In</div>
                            <div class="auth-subtitle">Enter your credentials to access your account</div>

                            @if(session('flash_message'))
                                <div class="alert-danger">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    {{ session('flash_message') }}
                                </div>
                            @endif

                            <form action="{{ route('getlogin2') }}" method="POST">
                                @csrf

                                <div class="input-wrap">
                                    <label class="form-label">Email Address</label>
                                    <i class="fas fa-envelope field-icon"></i>
                                    <input id="email" type="email" name="email"
                                           class="form-control"
                                           placeholder="your@email.com"
                                           value="" required autofocus>
                                </div>

                                <div class="input-wrap">
                                    <label class="form-label">Password</label>
                                    <i class="fas fa-lock field-icon"></i>
                                    <input id="password" type="password" name="password"
                                           class="form-control"
                                           placeholder="Enter your password"
                                           required>
                                </div>

                                <button type="submit" class="btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i> Sign In
                                </button>
                            </form>

                            <div class="auth-divider"><span>OR</span></div>

                            <div class="auth-footer-text">
                                New agent or carrier?
                                <a href="{{ route('register') }}">Create Account</a>
                            </div>
                            <div class="auth-footer-text mt-2">
                                <a href="{{ url('/') }}">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Home
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@section('extraScript')
<script>
$(document).ready(function(){
    $.ajax({
       url:"{{ url('/logoutAllAccounts') }}",
       type:"GET",
       dataType:"json",
       success:function(res){ console.log(res); }
    });
});
</script>
@endsection

@endsection
