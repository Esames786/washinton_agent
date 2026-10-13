@extends('layouts.new-master')
@section('page_title', 'Sign In')

@section('content')
<style>
    /* Sign-in page — follows hello-theme.css (navy + gold). */
    .auth-section {
        background: var(--ht-bg-soft);
        display: flex;
        align-items: center;
        padding: 80px 0;
        min-height: calc(100vh - 90px);
    }
    .auth-card {
        background: #fff;
        border: 1px solid var(--ht-border);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: var(--ht-shadow-lg);
    }
    /* Left panel */
    .auth-left {
        background: linear-gradient(160deg, var(--ht-navy) 0%, var(--ht-navy-2) 100%);
        padding: 56px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        min-height: 500px;
    }
    .auth-left .brand-logo { width: 96px; height: 96px; margin-bottom: 22px; }
    .auth-left h2 {
        font-size: 26px;
        font-weight: 800;
        color: #fff !important;
        margin-bottom: 6px;
    }
    .auth-left h2 span { color: var(--ht-accent); }
    .auth-left .divider {
        width: 44px; height: 3px;
        background: var(--ht-accent);
        border-radius: 2px;
        margin: 14px auto 18px;
    }
    .auth-left p {
        font-size: 14.5px;
        color: rgba(255,255,255,0.75) !important;
        line-height: 1.7;
        max-width: 260px;
    }
    .auth-left .feature-list {
        list-style: none; padding: 0; margin: 22px 0 0; text-align: left; width: 100%;
    }
    .auth-left .feature-list li {
        font-size: 14px;
        color: rgba(255,255,255,0.85);
        padding: 9px 0;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .auth-left .feature-list li:last-child { border-bottom: none; }
    .auth-left .feature-list li i { color: var(--ht-accent) !important; font-size: 14px; width: 16px; text-align: center; }

    /* Right panel */
    .auth-right { padding: 56px 48px; background: #fff; }
    .auth-right .auth-title {
        font-size: 28px;
        font-weight: 800;
        color: var(--ht-navy);
        letter-spacing: -.015em;
        margin-bottom: 6px;
    }
    .auth-right .auth-subtitle { font-size: 15px; color: var(--ht-muted); margin-bottom: 30px; }
    .auth-right .form-label { font-size: 14px; font-weight: 600; color: var(--ht-navy); margin-bottom: 6px; }
    .auth-right .input-wrap { position: relative; margin-bottom: 20px; }
    .auth-right .input-wrap .field-icon {
        position: absolute;
        left: 15px;
        bottom: 16px;
        color: #94a3b8 !important;
        font-size: 15px;
    }
    .auth-right .form-control {
        height: 50px;
        background: #fff;
        border: 1px solid #cfd8e3;
        border-radius: 8px;
        padding: 0 14px 0 42px;
        font-size: 15px;
        color: var(--ht-navy);
        transition: border-color .2s, box-shadow .2s;
        width: 100%;
    }
    .auth-right .form-control:focus {
        border-color: var(--ht-navy-3);
        box-shadow: 0 0 0 4px rgba(29, 65, 116, .12);
        outline: none;
    }
    .auth-right .form-control::placeholder { color: #8a96a8; }
    .btn-login {
        background: var(--ht-accent);
        border: 1.5px solid var(--ht-accent);
        color: var(--ht-navy);
        font-weight: 700;
        height: 52px;
        font-size: 16px;
        border-radius: 8px;
        width: 100%;
        box-shadow: 0 6px 18px rgba(201, 161, 59, .28);
        transition: all .2s;
        cursor: pointer;
    }
    .btn-login:hover { background: var(--ht-navy); border-color: var(--ht-navy); color: #fff; transform: translateY(-1px); }
    .btn-login i { color: inherit !important; }
    .auth-divider { display: flex; align-items: center; gap: 12px; margin: 26px 0; }
    .auth-divider span { color: var(--ht-muted); font-size: 12px; font-weight: 600; letter-spacing: .1em; }
    .auth-divider::before, .auth-divider::after { content: ''; flex: 1; height: 1px; background: var(--ht-border); }
    .auth-footer-text { text-align: center; font-size: 14.5px; color: var(--ht-muted); margin-top: 16px; }
    .auth-footer-text a { color: var(--ht-navy); font-weight: 700; text-decoration: none; }
    .auth-footer-text a:hover { color: var(--ht-accent-text); }
    .auth-footer-text a i { color: inherit !important; }
    .auth-right .alert-danger {
        background: #fdf2f2;
        border: 1px solid #f5c2c2;
        color: #9b1c1c;
        border-radius: 8px;
        font-size: 14px;
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
                            @php
                                $brand = \App\Support\Brand::current();
                                $brandName = $brand['name'] ?? 'Hello Transport';
                                $brandParts = explode(' ', $brandName, 2);
                            @endphp
                            <img src="{{ asset($brand['logo'] ?? 'frontend/img/logo/hello_transport.svg') }}"
                                 class="brand-logo" alt="{{ $brandName }}">
                            <h2>{{ $brandParts[0] }} <span>{{ $brandParts[1] ?? '' }}</span></h2>
                            <div class="divider"></div>
                            {{-- W-05(a) --}}
                            <p>Your {{ $brandName }} workspace for managing orders, customers, and opportunities.</p>
                            {{-- florida (is_agent_portal) is a pure portal — no marketing bullets. --}}
                            @if (!config('app.is_agent_portal'))
                            <ul class="feature-list">
                                {{-- W-05(c): the audience here is agents, not customers --}}
                                <li><i class="fas fa-users"></i> Manage leads, customers &amp; opportunities</li>
                                <li><i class="fas fa-file-invoice-dollar"></i> Create quotes and manage orders</li>
                                <li><i class="fas fa-truck"></i> Track shipments and customer activity</li>
                                <li><i class="fas fa-shield-alt"></i> Secure &amp; reliable workspace</li>
                            </ul>
                            @endif
                        </div>

                        {{-- RIGHT FORM --}}
                        <div class="col-md-7 auth-right">
                            {{-- W-05(b) --}}
                            <div class="auth-title">Access Your Account</div>
                            <div class="auth-subtitle">Enter your credentials to continue to your account.</div>

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

                            {{-- W-05(d). The link points at register until the "Earn with
                                 Hello Transport" page (W-02) exists, then it moves there. --}}
                            <div class="auth-footer-text">
                                New to the {{ $brandName }} network?
                                <a href="{{ route('register') }}">Create Account</a>
                            </div>
                            <div class="auth-footer-text mt-2">
                                <a href="{{ url('/') }}">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Homepage
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
