@extends('layouts.frontend-master')

@section('page_title', 'Privacy Policy')
@section('meta_description', 'Read Hello Transport\'s Privacy Policy to understand how we collect, use, and protect your personal information when you use our auto shipping services.')

@section('content')

<!-- Page Title -->
<div class="page-title-area bg-14">
    <div class="container">
        <div class="page-title-content">
            <h2>Privacy & Policy</h2>
            <ul>
                <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                <li class="active">Privacy & Policy</li>
            </ul>
        </div>
    </div>
</div>

<!-- Privacy Policy Area -->
<section class="terms-conditions-area ptb-100">
    <div class="container">
        <div class="terms-conditions">

            <div class="title text-center">
                <span>Privacy Information</span>
                <h2>Privacy Policy</h2>
            </div>

            <div class="conditions-content">
                <h3>Welcome to the Hello Transport Privacy Policy</h3>
                <p>
                    The segment of each collected data ensures your commitment and transparency with us. You must agree
                    to every term to access our services and allow us to serve you on time. To enjoy seamless
                    transportation services with Hello Transport, accept and agree to the privacy policies.
                </p>
                <p>
                    Once you acknowledge our terms, you will be bound by them. If you deny them, you will not be able
                    to use the services at Hello Transport.
                </p>
            </div>

            <div class="conditions-content">
                <h3>The Data We Collect</h3>
                <ul>
                    <li>Your complete name</li>
                    <li>Email Address</li>
                    <li>Your address</li>
                    <li>Cell/Mobile Number</li>
                    <li>Transport Details</li>
                    <li>Vehicle information</li>
                    <li>Pick up Location (City, State & Zip Code)</li>
                    <li>Delivery Location (City, State & Zip Code)</li>
                </ul>
            </div>

            <div class="conditions-content">
                <h3>How Do We Utilize Your Data?</h3>
                <p>
                    Hello Transport is committed to the highest privacy protection standards. We gather your personal
                    information to ensure compliance with legal obligations and facilitate services.
                </p>
            </div>

            <div class="conditions-content">
                <h3>Use of Cookies and Similar Tracking Technologies</h3>
                <p>
                    Hello Transport uses cookies and other technologies to improve website performance. Such technologies
                    help us to maintain website performance, evaluate user trends, and track users' navigation.
                </p>
            </div>

            <div class="conditions-content">
                <h3>Third-party Involvement</h3>
                <p>
                    Hello Transport shares your data with service partners such as drivers, shipping companies, and
                    payment processors to ensure efficient service delivery.
                </p>
                <p>
                    Our privacy policy prohibits mobile information from being shared for marketing or promotional
                    purposes with third parties.
                </p>
            </div>

            <div class="conditions-content">
                <h3>Your Rights Under the Data Protection Law</h3>
                <p>
                    You have the right to remove, amend, or modify any personal data we have on file and opt out of
                    promotional communications.
                </p>
            </div>

            <div class="conditions-content">
                <h3>Data Security Practices</h3>
                <p>
                    Hello Transport security protocols ensure protection against unauthorized access. We regularly
                    review and enhance our security mechanisms.
                </p>
            </div>

            <div class="conditions-content mb-0">
                <h3>Contact Us</h3>
                <p>
                    In case of any queries regarding Hello Transport privacy terms, you can directly reach us at
                    <a href="mailto:hodontime@shipa1.com">hodontime@shipa1.com</a>.
                </p>
            </div>

        </div>
    </div>
</section>

@endsection
