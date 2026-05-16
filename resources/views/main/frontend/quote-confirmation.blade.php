@extends('layouts.new-master')

@section('content')

<section class="page-title-area pt-100 pb-100"
         style="background-image:url({{ asset('frontend/newtheme-assets/img/banner/7.png') }});">
    <div class="container">
        <div class="page-title-content text-center">
            <h2>Quote Submitted</h2>
            <ul>
                <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                <li>Confirmation</li>
            </ul>
        </div>
    </div>
</section>

<section class="main-contact-area ptb-100">
    <div class="container text-center">
        <div style="max-width:560px;margin:0 auto;padding:40px 20px;">
            <div style="font-size:64px;margin-bottom:20px;">✅</div>
            <h2>Thank You!</h2>
            <p style="font-size:16px;color:#555;margin:16px 0;">
                Your quote request has been received. Our team will review it and contact you shortly.
            </p>
            <p style="font-size:14px;color:#888;">
                You can also reach us at <a href="tel:+14107184031">1 (410) 718-4031</a>
                or <a href="mailto:hodontime@shipa1.com">hodontime@shipa1.com</a>.
            </p>
            <a href="{{ route('Frontend.index') }}" class="default-btn btn-two" style="display:inline-block;margin-top:24px;">
                <span>Back to Home</span>
            </a>
        </div>
    </div>
</section>

@endsection
