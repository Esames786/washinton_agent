@extends('layouts.new-master')
@section('title', 'Hello Transport | #1 Auto Shipping Company – Nationwide Door-to-Door Service')

@section('content')

    <style>
        .banner-area-two{
            position: relative!important;
            overflow: hidden!important;
            height: 720px!important;
            background: #000!important;
        }
        .banner-area-two .background-video{
            position: absolute!important;
            inset: 0!important;
            width: 100%!important;
            height: 100%!important;
            object-fit: cover!important;
            object-position: 70% 35%!important;
            z-index: 0!important;
        }
        .banner-area-two::after{
            content: ""!important;
            position: absolute!important;
            inset: 0!important;
            background: rgba(0,0,0,0.35)!important;
            z-index: 1!important;
        }
        .banner-area-two .d-table,
        .banner-area-two .d-table-cell,
        .banner-area-two .banner-content{
            position: relative!important;
            z-index: 2!important;
        }
        .typing-cursor{
            display:inline-block;
            margin-left:2px;
            animation: blink .9s infinite;
        }
        @keyframes blink{
            0%,50%{opacity:1}
            51%,100%{opacity:0}
        }
    </style>

    <!-- ================= BANNER AREA ================= -->
    <section class="banner-area bg-1 jarallax" data-jarallax='{"speed": 0.3}'>
        <div class="d-table">
            <div class="d-table-cell">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-9">
                            <div class="banner-content">
                                <span class="top-title">Welcome to Hello Transport</span>

                                <h1>
                                    Transport Anything <br>
                                    From Anywhere.
                                </h1>

                                <p>
                                    Reliable vehicle, freight, and heavy equipment transportation
                                    solutions across the United States.
                                </p>

                                <div class="banner-btn">
                                    <a href="{{ route('Frontend.qoute.request') }}" class="default-btn">
                                        <span>Get Estimation</span>
                                    </a>
                                    <a href="{{ route('Frontend.is.me') }}" class="default-btn active">
                                        <span>Our Services</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= ABOUT US ================= -->
    <section class="about-us-area pt-100 pb-70">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6">
                    <div class="about-img">
                        <img src="{{ asset('frontend/newtheme-assets/img/about-img.jpg') }}" alt="About" style="width:100%;border-radius:8px;">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="about-content">

                        <span class="top-title">About Us</span>
                        <h2>A Company Involved In <br> Servicing, Maintenance.</h2>

                        <h4 class="mt-4">Our Approach</h4>
                        <p>
                            From freight forwarding and warehousing to last-mile delivery, fleet optimization,
                            and supply chain visibility, logistics leaders are harnessing the power of data
                            to drive efficiency and maintain a competitive edge. Discover how our solutions
                            can help streamline your operations.
                        </p>

                        <h4 class="mt-4">Our Goal</h4>
                        <p>
                            Our goal is to provide seamless, reliable, and high-quality logistics solutions
                            that go beyond expectations. By partnering with trusted carriers and leveraging
                            smart transport strategies, we ensure the safe movement of goods and vehicles—
                            guaranteeing efficiency, transparency, and customer satisfaction at every step.
                        </p>

                        <h4 class="mt-4">Our Mission</h4>
                        <p>
                            At Hello Transport, our mission is simple; to provide top-notch vehicle
                            transportation &amp; logistics solutions for both bulk and single deliveries.
                            We understand the challenges individuals (Shippers) and brokers face when
                            searching for reliable carriers. That's why we've created a platform that
                            bridges the gap between shippers and carriers, ensuring affordability, speed,
                            and peace of mind throughout the process.
                        </p>

                        <ul class="mt-4">
                            <li><i class="bx bx-check"></i> Nationwide Transportation Coverage</li>
                            <li><i class="bx bx-check"></i> Vehicle &amp; Heavy Equipment Transport</li>
                            <li><i class="bx bx-check"></i> Freight &amp; Logistics Solutions</li>
                            <li><i class="bx bx-check"></i> Trusted Carrier Network</li>
                        </ul>

                        <a href="{{ route('Frontend.about.us') }}" class="default-btn mt-3">
                            <span>Learn More</span>
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ================= GROW WITH US ================= --}}
    <section class="choose-us-area pt-100 pb-70">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6">
                    <div class="choose-us-img">
                        <img src="{{ asset('frontend/img/about/dispatch-center.jpeg') }}" decoding="async" width="100%" height="100%" loading="lazy" alt="About">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="choose-us-content">

                        <span class="top-title">Grow With Us</span>
                        <h2>Trusted Logistics &amp; Transportation Services</h2>

                        <h4 class="mt-3">Reliable Logistics Solutions</h4>

                        <p>
                            At Hello Transport, we are dedicated to delivering safe, timely,
                            and efficient transportation solutions across the nation.
                            Our goal is to ensure your goods are delivered on schedule
                            while maintaining the highest standards of care.
                        </p>

                        <p>
                            With years of experience, our operations are optimized to
                            handle everything from vehicles to heavy machinery and
                            freight shipments.
                        </p>

                        <div class="row mt-4">
                            <div class="col-sm-6">
                                <div class="single-counter">
                                    <h2>150+</h2>
                                    <p>Satisfied Customers</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="single-counter">
                                    <h2>5,000+</h2>
                                    <p>Shipments Delivered</p>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('Frontend.contact.us') }}" class="default-btn mt-4">
                            <span>Contact Us</span>
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= SERVICES ================= -->
    <section class="services-area bg-color pt-100 pb-70">
        <div class="container">
            <div class="section-title">
                <span>Services</span>
                <h2>What We Do</h2>
                <p>
                    We specialize in safe, efficient, and reliable transportation services
                    tailored to your logistics needs.
                </p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-4 col-sm-6">
                    <div class="single-services-box">
                        <i class="fa-solid fa-truck-fast"></i>
                        <h3>Auto Transportation</h3>
                        <p>
                            Secure and timely vehicle transportation for individuals,
                            dealers, and brokers nationwide.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6">
                    <div class="single-services-box">
                        <i class="fa-solid fa-truck-monster"></i>
                        <h3>Heavy Equipment Transport</h3>
                        <p>
                            Specialized transport solutions for oversized and heavy equipment
                            with full safety compliance.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6">
                    <div class="single-services-box">
                        <i class="fa-solid fa-dolly"></i>
                        <h3>Freight Transportation</h3>
                        <p>
                            Reliable freight shipping services ensuring cost-effective
                            and on-time deliveries.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= PROCESS ================= -->
    <section class="choose-us-area pt-100 pb-70">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="choose-us-content">
                        <span class="top-title">Process</span>
                        <h2>Our Logistics Process</h2>

                        <p>
                            Our streamlined process ensures smooth, transparent,
                            and efficient transportation from start to finish.
                        </p>

                        <div class="choose-us-slider owl-carousel owl-theme">
                            <div class="single-choose-us-box bg-color-1">
                                <i class="bx bx-edit"></i>
                                <span>Submit Request</span>
                            </div>
                            <div class="single-choose-us-box bg-color-2">
                                <i class="bx bx-check-circle"></i>
                                <span>Confirmation &amp; Planning</span>
                            </div>
                            <div class="single-choose-us-box bg-color-3">
                                <i class="bx bx-tag"></i>
                                <span>Shipment Execution</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="choose-us-img">
                        <img src="{{ asset('frontend/img/work/work-img.png') }}" alt="Work">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= COUNTERS ================= -->
    <section class="counter-area bg-color pt-100 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter">
                        <i class="flaticon-user"></i>
                        <h2><span class="odometer" data-count="150">00</span>+</h2>
                        <p>Satisfied Customers</p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter">
                        <i class="flaticon-package"></i>
                        <h2><span class="odometer" data-count="5000">00</span>+</h2>
                        <p>Shipments Delivered</p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter">
                        <i class="flaticon-worldwide"></i>
                        <h2><span class="odometer" data-count="125">00</span>+</h2>
                        <p>Trusted Partners</p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter">
                        <i class="flaticon-truck"></i>
                        <h2><span class="odometer" data-count="100">00</span>+</h2>
                        <p>Successful Deliveries</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= CTA ================= -->
    <section class="shipment-area ptb-100 jarallax" data-jarallax='{"speed": 0.3}'>
        <div class="container">
            <div class="shipment-content text-center">
                <span class="top-title">Support</span>
                <h2>Need Expert Assistance With Your Shipment?</h2>
                <p>
                    Our logistics experts are ready to help you with fast,
                    secure, and reliable transportation solutions.
                </p>

                <div class="shipment-btn">
                    <a href="/Quote-Request" class="default-btn">
                        <span>Get A Quote</span>
                    </a>
                    <a href="{{ route('Frontend.contact.us') }}" class="default-btn active">
                        <span>Contact Us</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

<script>
(function waitForJQ() {
    if (typeof window.jQuery === 'undefined') { setTimeout(waitForJQ, 50); return; }
    var $ = window.jQuery;
    var counted = false;

    function runCounters() {
        if (counted) return;
        counted = true;
        $('.odometer').each(function () {
            $(this).html($(this).data('count'));
        });
    }

    function checkInView() {
        var el = $('.counter-area');
        if (!el.length) return;
        var top = el.offset().top;
        if ($(window).scrollTop() + $(window).height() >= top - 100) {
            runCounters();
        }
    }

    $(window).on('scroll', checkInView);
    // Trigger once on load in case section is already visible
    setTimeout(checkInView, 400);
}());
</script>

@endsection
