@extends('layouts.new-master')
@section('page_title', 'Verify Sign In')

@section('content')
{{-- Sign-in code page, in the website theme to match the new sign-in page (client, 16 Oct 2026).
     Form fields, routes and hidden values are unchanged. --}}
<style>
    .verify-section { background: var(--ht-bg-soft); padding: 80px 0; min-height: calc(100vh - 90px); display: flex; align-items: center; }
    .verify-card {
        max-width: 480px; margin: 0 auto; background: #fff;
        border: 1px solid var(--ht-border); border-radius: 18px; overflow: hidden; box-shadow: var(--ht-shadow-lg);
    }
    .verify-header {
        background: linear-gradient(160deg, var(--ht-navy) 0%, var(--ht-navy-2) 100%);
        padding: 32px 36px 26px; text-align: center; border-bottom: 4px solid var(--ht-accent);
    }
    .verify-header .brand-logo { width: 76px; height: 76px; margin-bottom: 14px; }
    .verify-header h2 { color: #fff !important; font-size: 24px; font-weight: 800; margin: 0 0 6px; }
    .verify-header p { color: rgba(255,255,255,.75) !important; font-size: 14.5px; margin: 0; }
    .verify-body { padding: 32px 36px 30px; }
    .otp-info {
        display: flex; gap: 10px; align-items: flex-start;
        background: var(--ht-bg-soft); border: 1px solid var(--ht-border); border-radius: 10px;
        padding: 13px 15px; margin-bottom: 24px; font-size: 14px; color: var(--ht-text); line-height: 1.6;
    }
    .otp-info i { color: var(--ht-accent-dark) !important; margin-top: 3px; }
    .verify-body .form-label { font-size: 14px; font-weight: 600; color: var(--ht-navy); margin-bottom: 7px; display: block; }
    .otp-input {
        width: 100%; height: 56px; text-align: center; font-size: 22px; font-weight: 700; letter-spacing: 8px;
        color: var(--ht-navy); background: #fff; border: 1px solid #cfd8e3; border-radius: 10px;
        transition: border-color .2s, box-shadow .2s; margin-bottom: 22px;
    }
    .otp-input::placeholder { color: #8a96a8; font-size: 15px; letter-spacing: 1px; font-weight: 400; }
    .otp-input:focus { outline: none; border-color: var(--ht-navy-3); box-shadow: 0 0 0 4px rgba(29,65,116,.12); }
    .btn-verify {
        width: 100%; height: 52px; border-radius: 8px; border: 1.5px solid var(--ht-accent);
        background: var(--ht-accent); color: var(--ht-navy); font-weight: 700; font-size: 16px; cursor: pointer;
        box-shadow: 0 6px 18px rgba(201,161,59,.28); transition: all .2s; margin-bottom: 12px;
    }
    .btn-verify:hover { background: var(--ht-navy); border-color: var(--ht-navy); color: #fff; transform: translateY(-1px); }
    .btn-resend {
        width: 100%; height: 48px; border-radius: 8px; border: 1.5px solid var(--ht-border);
        background: #fff; color: var(--ht-navy); font-weight: 600; font-size: 15px; cursor: pointer; transition: all .2s;
    }
    .btn-resend:hover { border-color: var(--ht-navy); background: var(--ht-bg-soft); }
    .btn-verify i, .btn-resend i, .back-link a i { color: inherit !important; }
    .verify-body .alert-danger {
        background: #fdf2f2; border: 1px solid #f5c2c2; color: #9b1c1c;
        border-radius: 8px; font-size: 14px; padding: 12px 16px; margin-bottom: 20px;
    }
    .back-link { text-align: center; margin-top: 20px; font-size: 14.5px; }
    .back-link a { color: var(--ht-navy); font-weight: 700; text-decoration: none; }
    .back-link a:hover { color: var(--ht-accent-text); }
    @media (max-width: 575.98px) {
        .verify-section { padding: 30px 0; }
        .verify-header, .verify-body { padding-left: 22px; padding-right: 22px; }
    }
</style>

<section class="verify-section">
    <div class="container">
        <div class="verify-card">

            <div class="verify-header">
                <img src="{{ asset(($brand['logo'] ?? 'frontend/img/logo/hello_transport.svg')) }}"
                     class="brand-logo" alt="{{ $brand['name'] ?? 'Hello Transport' }}">
                <h2>Verify It's You</h2>
                <p>Enter the code we sent to your email</p>
            </div>

            <div class="verify-body">

                @if(session('flash_message'))
                    <div class="alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('flash_message') }}
                    </div>
                @endif

                <div class="otp-info">
                    <i class="fas fa-envelope"></i>
                    <span>We have sent a verification code to your registered email address. Check your inbox, then enter the code below.</span>
                </div>

                {{-- Verify --}}
                <form action="{{ route('code_verify') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id }}">
                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="password" value="{{ $password }}">

                    <label class="form-label" for="otpCode">Verification code</label>
                    <input type="text" name="verified" id="otpCode"
                           class="otp-input"
                           placeholder="Enter code"
                           maxlength="20"
                           inputmode="numeric"
                           autocomplete="one-time-code"
                           autofocus>

                    <button type="submit" class="btn-verify">
                        <i class="fas fa-check-circle me-2"></i> Verify &amp; Sign In
                    </button>
                </form>

                {{-- Resend --}}
                <form action="{{ route('resend_verify') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id }}">
                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="password" value="{{ $password }}">
                    <button type="submit" class="btn-resend">
                        <i class="fas fa-redo me-2"></i> Resend Code
                    </button>
                </form>

                <div class="back-link">
                    <a href="{{ url('/loginn') }}"><i class="fas fa-arrow-left me-1"></i> Back to Sign In</a>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
