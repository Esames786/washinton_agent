@extends('layouts.frontend-master')

@section('content')

<!-- Page Title -->
<div class="page-title-area bg-5">
    <div class="container">
        <div class="page-title-content">
            <h2>Testimonials</h2>
            <ul>
                <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                <li class="active">Testimonials</li>
            </ul>
        </div>
    </div>
</div>

<!-- Testimonials Section -->
<section class="testimonials-area testimonials-page pt-100 pb-70">
    <div class="container">

        <div class="section-title">
            <span>Our Testimonials</span>
            <h2>What Our Clients Say About Hello Transport</h2>
        </div>

        <div class="row">

            <div class="col-lg-6 col-md-6">
                <div class="single-testimonials">
                    <img src="{{ asset('frontend/img/avatar.jpg') }}" alt="Testimonial">
                    <h3>Reliable & Professional Service</h3>
                    <p>
                        "I recently used Hello Transport for my transportation needs, and it was a game-changer.
                        As a broker, I struggled to find reliable carriers, but this platform saved time and delivered
                        excellent service."
                    </p>
                    <h4>Oliver D. Drummer</h4>
                    <span>Broker</span>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="single-testimonials">
                    <img src="{{ asset('frontend/img/avatar.jpg') }}" alt="Testimonial">
                    <h3>Efficient & Affordable</h3>
                    <p>
                        "Hello Transport is incredibly efficient and affordable.
                        I instantly accessed verified carriers and confidently selected the right one using reviews."
                    </p>
                    <h4>Miranda Nelson</h4>
                    <span>Customer</span>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="single-testimonials">
                    <img src="{{ asset('frontend/img/avatar.jpg') }}" alt="Testimonial">
                    <h3>Transparent Platform</h3>
                    <p>
                        "What impressed me most was transparency.
                        All carriers are DOT verified with genuine profiles, which really builds trust."
                    </p>
                    <h4>Dewey M. Lewis</h4>
                    <span>Shipper</span>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="single-testimonials">
                    <img src="{{ asset('frontend/img/avatar.jpg') }}" alt="Testimonial">
                    <h3>Outstanding Customer Support</h3>
                    <p>
                        "Their customer support is top-notch and available 24/7.
                        Whether via call or chat, my experience was smooth and stress-free."
                    </p>
                    <h4>Lious M. Peter</h4>
                    <span>Customer</span>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="single-testimonials">
                    <img src="{{ asset('frontend/img/avatar.jpg') }}" alt="Testimonial">
                    <h3>Highly Recommended</h3>
                    <p>
                        "I highly recommend Hello Transport for dispatching and transportation services.
                        Their platform and carrier network exceeded my expectations."
                    </p>
                    <h4>David Smith</h4>
                    <span>Carrier</span>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="single-testimonials">
                    <img src="{{ asset('frontend/img/avatar.jpg') }}" alt="Testimonial">
                    <h3>Unmatched Convenience</h3>
                    <p>
                        "Hello Transport offers unmatched convenience for shipping vehicles,
                        heavy equipment, and freight. Their personalized approach truly stands out."
                    </p>
                    <h4>Jason Smith</h4>
                    <span>Carrier</span>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
