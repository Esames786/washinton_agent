@extends('layouts.frontend-master')

@section('content')

<!-- Page Title -->
<div class="page-title-area bg-11">
    <div class="container">
        <div class="page-title-content">
            <h2>FAQ</h2>
            <ul>
                <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                <li class="active">FAQ</li>
            </ul>
        </div>
    </div>
</div>

<!-- FAQ Area -->
<section class="faq-area ptb-100">
    <div class="container">
        <div class="section-title">
            <span class="sub-title">FAQ</span>
            <h2>Frequently Asked Questions</h2>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="faq-accordion">
                    <ul class="accordion">

                        <li class="accordion-item">
                            <a class="accordion-title active" href="javascript:void(0)">
                                <span>1</span>
                                How is Hello Transport a reliable auto transport agency in the U.S?
                            </a>
                            <p class="accordion-content show">
                                We offer DOT-verified carriers with smart active tracking to provide customized
                                transportation solutions. We provide door-to-door delivery and ensure your vehicle's
                                protection with a fully insured policy.
                            </p>
                        </li>

                        <li class="accordion-item">
                            <a class="accordion-title" href="javascript:void(0)">
                                <span>2</span>
                                What areas do you serve in the U.S?
                            </a>
                            <p class="accordion-content">
                                We offer our services in the continental U.S states so that you can enjoy our service at
                                any corner of these states.
                            </p>
                        </li>

                        <li class="accordion-item">
                            <a class="accordion-title" href="javascript:void(0)">
                                <span>3</span>
                                Do we offer the cheapest transportation service?
                            </a>
                            <p class="accordion-content">
                                Hello Transport values our potential customers and offers only customized transportation
                                solutions that come in a friendly budget range.
                            </p>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="faq-accordion">
                    <ul class="accordion">

                        <li class="accordion-item">
                            <a class="accordion-title" href="javascript:void(0)">
                                <span>4</span>
                                What if my shipment is delayed?
                            </a>
                            <p class="accordion-content">
                                In such a rare case, you can contact your broker immediately to resolve your issues and
                                ensure you get your delivery on time.
                            </p>
                        </li>

                        <li class="accordion-item">
                            <a class="accordion-title" href="javascript:void(0)">
                                <span>5</span>
                                Is my information protected?
                            </a>
                            <p class="accordion-content">
                                We are liable for all legal obligations and will protect your information. For more
                                information, you must visit the website to read the privacy policy and acknowledge that
                                to proceed further.
                            </p>
                        </li>

                        <li class="accordion-item">
                            <a class="accordion-title" href="javascript:void(0)">
                                <span>6</span>
                                How do I know a trusted shipping partner?
                            </a>
                            <p class="accordion-content">
                                Trusted shipping partners offer transparent pricing with no hidden fees and are
                                licensed and insured. The company provides real-time tracking, reliable customer
                                support, and has a positive reputation.
                            </p>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ask a Question -->
<section class="main-contact-area faq-contact-area pb-100">
    <div class="container">
        <div class="section-title">
            <h2>Ask a Question</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('Post.Contact.Lead') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group">
                        <input type="text" name="Lead_Name" class="form-control" placeholder="Your Name" required>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group">
                        <input type="email" name="Lead_Email" class="form-control" placeholder="Your Email" required>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group">
                        <input type="text" name="Lead_Phone" class="form-control" placeholder="Your Phone" required>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6">
                    <div class="form-group">
                        <input type="text" name="Lead_Subject" class="form-control" placeholder="Your Subject">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <textarea name="Lead_Message" class="form-control" rows="6" placeholder="Your Message"></textarea>
                    </div>
                </div>
                <div class="col-lg-12">
                    <button type="submit" class="default-btn btn-two">
                        <span>Send Message</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

@endsection
