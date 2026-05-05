@extends('layouts.frontend-master')

@section('content')
<style>
    .verify-section {
        min-height: 100vh;
        background: linear-gradient(135deg, #060e14 0%, #0a1a24 50%, #0d2030 100%);
        display: flex;
        align-items: center;
        padding: 60px 0;
        position: relative;
        overflow: hidden;
    }
    .verify-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 20% 30%, rgba(201,168,76,0.06) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(201,168,76,0.04) 0%, transparent 50%);
        pointer-events: none;
    }
    .verify-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(201,168,76,0.2);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 30px 80px rgba(0,0,0,0.5);
        backdrop-filter: blur(10px);
        max-width: 480px;
        margin: 0 auto;
    }
    .verify-header {
        background: linear-gradient(135deg, #0d1f2d, #071520);
        padding: 36px 40px 28px;
        text-align: center;
        border-bottom: 1px solid rgba(201,168,76,0.15);
    }
    .verify-header .brand-logo {
        width: 80px;
        height: 80px;
        margin-bottom: 16px;
        filter: drop-shadow(0 4px 16px rgba(201,168,76,0.3));
    }
    .verify-header h2 {
        font-family: 'Oswald', sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 6px;
    }
    .verify-header p {
        font-size: 13px;
        color: rgba(255,255,255,0.45);
        margin: 0;
    }
    .verify-body {
        padding: 36px 40px;
        background: #0a1520;
    }
    .otp-info {
        background: rgba(201,168,76,0.08);
        border: 1px solid rgba(201,168,76,0.2);
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 24px;
        font-size: 13px;
        color: rgba(255,255,255,0.65);
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .otp-info i { color: #C9A84C; font-size: 16px; margin-top: 1px; flex-shrink: 0; }
    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: rgba(255,255,255,0.7);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        display: block;
    }
    .input-wrap { position: relative; margin-bottom: 24px; }
    .input-wrap .field-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #C9A84C;
        font-size: 15px;
    }
    .otp-input {
        background: rgba(255,255,255,0.05);
        border: 1.5px solid rgba(201,168,76,0.2);
        border-radius: 10px;
        padding: 14px 14px 14px 44px;
        font-size: 20px;
        font-weight: 700;
        color: #FFD700;
        letter-spacing: 8px;
        width: 100%;
        text-align: center;
        transition: border-color .2s, box-shadow .2s;
    }
    .otp-input:focus {
        border-color: #C9A84C;
        box-shadow: 0 0 0 3px rgba(201,168,76,0.15);
        outline: none;
        background: rgba(255,255,255,0.07);
    }
    .otp-input::placeholder {
        color: rgba(255,255,255,0.2);
        font-size: 14px;
        letter-spacing: 2px;
        font-weight: 400;
    }
    .btn-verify {
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
        margin-bottom: 16px;
    }
    .btn-verify:hover {
        background: linear-gradient(135deg, #FFE44D, #D4A830);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(201,168,76,0.3);
    }
    .btn-resend {
        background: transparent;
        border: 1.5px solid rgba(201,168,76,0.3);
        color: #C9A84C;
        font-weight: 600;
        padding: 11px 0;
        font-size: 13px;
        border-radius: 10px;
        width: 100%;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-family: 'Oswald', sans-serif;
        transition: all .2s;
        cursor: pointer;
    }
    .btn-resend:hover {
        background: rgba(201,168,76,0.1);
        border-color: #C9A84C;
    }
    .alert-danger {
        background: rgba(220,53,69,0.15);
        border: 1px solid rgba(220,53,69,0.3);
        color: #ff6b7a;
        border-radius: 10px;
        font-size: 13px;
        padding: 12px 16px;
        margin-bottom: 20px;
    }
    .back-link {
        text-align: center;
        margin-top: 20px;
        font-size: 13px;
        color: rgba(255,255,255,0.35);
    }
    .back-link a { color: #C9A84C; text-decoration: none; font-weight: 600; }
    .back-link a:hover { color: #FFD700; }
    @media (max-width: 576px) {
        .verify-header, .verify-body { padding: 28px 24px; }
    }
</style>

<section class="verify-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-7 col-sm-10">
                <div class="verify-card">

                    {{-- Header --}}
                    <div class="verify-header">
                        <img src="{{ asset('frontend/img/logo/hello_transport.svg') }}"
                             class="brand-logo" alt="Hello Transport">
                        <h2>Verify Your Identity</h2>
                        <p>Enter the OTP code sent to your email</p>
                    </div>

                    {{-- Body --}}
                    <div class="verify-body">

                        @if(session('flash_message'))
                            <div class="alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('flash_message') }}
                            </div>
                        @endif

                        <div class="otp-info">
                            <i class="fas fa-envelope"></i>
                            <span>A verification code has been sent to your registered email address. Please check your inbox and enter the code below.</span>
                        </div>

                        {{-- Verify Form --}}
                        <form action="{{ route('code_verify') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $id }}">
                            <input type="hidden" name="email" value="{{ $email }}">
                            <input type="hidden" name="password" value="{{ $password }}">

                            <div class="input-wrap">
                                <label class="form-label">Verification Code</label>
                                <i class="fas fa-key field-icon" style="top:calc(50% + 14px);"></i>
                                <input type="text" name="verified"
                                       class="otp-input"
                                       placeholder="Enter OTP code"
                                       maxlength="20"
                                       autocomplete="one-time-code"
                                       autofocus>
                            </div>

                            <button type="submit" class="btn-verify">
                                <i class="fas fa-check-circle me-2"></i> Verify & Sign In
                            </button>
                        </form>

                        {{-- Resend Form --}}
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
                            <a href="{{ url('/loginn') }}">
                                <i class="fas fa-arrow-left me-1"></i> Back to Login
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
