@extends('layouts.new-master')
@section('title', 'About Us – Hello Transport')

@section('content')

    {{-- ================= PAGE TITLE / BANNER ================= --}}
    <section class="page-title-area pt-100 pb-100"
             style="background-image:url({{ asset('frontend/newtheme-assets/img/banner/23.png') }});">
        <div class="container">
            <div class="page-title-content text-center">
                <h2>About Us</h2>
                <ul>
                    <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                    <li>About Us</li>
                </ul>
            </div>
        </div>
    </section>

    {{-- ================= ABOUT INTRO ================= --}}
    <section class="about-us-area pt-100 pb-70">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6">
                    <div class="about-img">
                        <img src="{{ asset('frontend/img/about/dispatch-center.jpeg') }}" decoding="async" width="100%" height="100%" loading="lazy" alt="About">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="about-content">
                        <span class="top-title">Hello Transport</span>
                        <h2>About Us</h2>

                        <p>
                            Hello Transport is an all-in-one logistics and transportation solution, offering a variety of
                            services ranging from vehicles and heavy equipment to freight transportation. With a variety
                            of transportation methods and a team of experts on hand, we ensure your transportation runs
                            smoothly and according to your specifications. Our highest priority is your satisfaction and
                            the safety of your vehicle. Whether you're transporting a single vehicle or large-scale freight,
                            Hello Transport is fully equipped to manage it all
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ================= OUR MISSION ================= --}}
    <section class="about-us-area bg-f9f9f9 pt-100 pb-70">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6">
                    <div class="about-content">
                        <span class="top-title">Hello Transport</span>
                        <h2>Our Mission</h2>

                        <p>
                            At Hello Transport, our mission is simple; to provide top-notch vehicle transportation
                            &amp; logistics solutions for both bulk and single deliveries. We understand the challenges
                            individuals (Shippers) and brokers face when searching for reliable carriers. That's why
                            we've created a platform that bridges the gap between shippers and carriers, ensuring
                            affordability, speed, and peace of mind throughout the process.
                        </p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="about-img">
                        <img src="{{ asset('frontend/img/banner/11.jpg') }}" decoding="async" width="100%"
                             height="100%" loading="lazy" alt="approach">
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ================= SERVICES WE OFFER ================= --}}
    <section class="services-area pt-100 pb-70">
        <div class="container">

            <div class="section-title">
                <span>Explore</span>
                <h2>Services We Offer</h2>
            </div>

            <div class="row">

                <div class="col-lg-6 col-md-6">
                    <div class="single-services-box">
                        <h3>Trusted Vehicle Transportation Service</h3>
                        <p>Reliable delivery of cars, bikes, and other vehicles with a network of verified carriers.</p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="single-services-box">
                        <h3>Offers a Wide Range of Shipping Methods</h3>
                        <p>Multiple options including open, enclosed, FTL, and LTL tailored to your shipment size.</p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="single-services-box">
                        <h3>Safe and Secure Vehicle Transportation</h3>
                        <p>Every shipment is insured, tracked, and handled with professional care.</p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="single-services-box">
                        <h3>Customer-Centred Approach &amp; Services</h3>
                        <p>Easy booking, responsive support, and flexible scheduling to suit your needs.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3"></div>
                <div class="col-lg-6 col-md-6">
                    <div class="single-services-box">
                        <h3>Ensuring On-Time Delivery Across the U.S.</h3>
                        <p>Fast and efficient transport network delivering nationwide, without delays.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ================= EXPERIENCE / ABOUT DETAIL ================= --}}
    <section class="about-us-area bg-f9f9f9 pt-100 pb-70">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6">
                    <div class="about-img">
                        <img src="{{ asset('frontend/img/about/about-page-1.webp') }}" decoding="async"
                             width="100%" height="100%" loading="lazy" alt="About">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="about-content">
                        <span class="top-title">About Us</span>
                        <h2>Hello Transport</h2>

                        <p>
                            Customers struggled to find reliable carriers for their deliveries, leading to wasted time,
                            high charges, unsatisfactory service, shipment delays, safety concerns, and regulatory issues.
                        </p>

                        <p>
                            Hello Transport: One-stop solution for finding reliable carriers quickly.
                        </p>
                        <div class="row mt-4">
                            <div class="col-sm-6">
                                <div class="single-counter">
                                    <h2>21268</h2>
                                    <p>Carriers</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="single-counter">
                                    <h2>152399</h2>
                                    <p>Shippers</p>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('Frontend.contact.us') }}" class="default-btn">
                            <span>Contact Us</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ================= WHY CHOOSE US ================= --}}
    <section class="choose-us-area pt-100 pb-70">
        <div class="container">

            <div class="section-title">
                <span>Explore</span>
                <h2>Why Choose Us?</h2>
            </div>

            <div class="row">

                <div class="col-lg-4 col-md-6">
                    <div class="single-choose-us-box">
                        <h3>Full Spectrum Transport Service</h3>
                        <p>
                            We offer a wide range of transportation services. Whether looking for vehicles,
                            heavy equipment, or freight transportation, we tailor to all needs and provide
                            a customized solution.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="single-choose-us-box">
                        <h3>Excellence at Every Mile</h3>
                        <p>
                            Guaranteeing top-notch high quality transport services with trustworthy partners
                            to move vehicles and ensuring customer satisfaction.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="single-choose-us-box">
                        <h3>Deliver On-Time, Every Time</h3>
                        <p>
                            Providing you with a smart logistics solution. Delivering fast, safe, and secure
                            on time, every time.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mt-5">
                    <div class="single-choose-us-box">
                        <h3>Customer-Centered Approach</h3>
                        <p>
                            Your peace of mind is our priority. Offering tailored solutions that prioritize
                            your satisfaction at Hello Transport.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mt-5">
                    <div class="single-choose-us-box">
                        <h3>Secure Transportation Services</h3>
                        <p>
                            Your vehicle is secure every mile. We are dedicated to delivering you a safe and
                            secure shipment.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ================= VISION ================= --}}
    <section class="about-us-area bg-f9f9f9 pt-100 pb-70">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6">
                    <div class="about-content">
                        <span class="top-title">Our Vision</span>
                        <h2>Our Mission</h2>

                        <p>
                            Hello Transport provides innovative services to carriers, connecting them with a large pool
                            of shippers quickly. With millions of cargoes listed on their website, they help carriers
                            thrive by keeping their trucks filled. Their products promote efficient work from drivers
                            and dispatchers, benefiting both parties.
                        </p>

                        <p>
                            <strong>Email:</strong> hodontime@shipa1.com
                        </p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="about-img">
                        <img src="{{ asset('frontend/img/banner/8.png') }}" decoding="async" width="100%" height="100%" loading="lazy" alt="mission">
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
