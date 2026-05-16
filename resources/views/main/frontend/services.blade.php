@extends('layouts.new-master')
@section('title', 'Our Services – Hello Transport')

@section('content')

    {{-- ================= PAGE TITLE ================= --}}
    <section class="page-title-area pt-100 pb-100"
             style="background-image:url({{ asset('frontend/newtheme-assets/img/banner/7.png') }});">
        <div class="container">
            <div class="page-title-content text-center">
                <h2>Our Services</h2>
                <ul>
                    <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                    <li>Our Services</li>
                </ul>
            </div>
        </div>
    </section>

    {{-- ================= SERVICES INTRO ================= --}}
    <section class="services-area pt-100 pb-70 bg-f9f9f9">
        <div class="container">
            <div class="section-title">
                <span>Our Services</span>
                <h2>Our Services</h2>
                <p>Ship Your Vehicle with Confidence – Trusted Transportation Services Across the U.S.</p>
            </div>

            <div class="row">

                <div class="col-lg-6 col-md-6">
                    <div class="single-services-box">
                        <span class="number">01</span>
                        <h3>Transport Your Car with Hello Transport</h3>
                        <p>
                            Hello Transport ensures your peace of mind. Enjoy safe and secure car shipping services,
                            handled by a highly trained and professional team. Whether you prefer open or enclosed
                            trailers, it is an affordable car shipping service. Get a quote, and let us handle your
                            car shipping.
                        </p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="single-services-box">
                        <span class="number">02</span>
                        <h3>Transport Your Motorcycle with Hello Transport</h3>
                        <p>
                            Hello Transport guarantees your happiness. We offer you a safe, secure, and cost-effective
                            motorcycle shipping service across the United States. No matter how the weather is,
                            regardless of the congestion in the high cities, we are dedicated to delivering to you
                            on time. Get a quote and let us handle your motorcycle shipping.
                        </p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="single-services-box">
                        <span class="number">03</span>
                        <h3>Transport ATV/UTV with Hello Transport</h3>
                        <p>
                            At Hello Transport, we prioritize convenience. Whether it is an All-Terrain Vehicle or a
                            Utility Task Vehicle, ship with us where you want. ATVs and UTVs are shipped safely,
                            securely, and on time by our shipping agency. Get a quote, and let us handle your ATV/UTV
                            shipping.
                        </p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="single-services-box">
                        <span class="number">04</span>
                        <h3>Transport Construction Equipment with Hello Transport</h3>
                        <p>
                            At Hello Transport, you are in responsible hands. We offer you trusted partners to transport
                            oversized machinery at competitive rates. You do not need to be concerned about the safe
                            and affordable transportation of construction equipment. Get a quote, and let us handle
                            your machinery shipping.
                        </p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="single-services-box">
                        <span class="number">05</span>
                        <h3>Transporting Farm Equipment with Hello Transport</h3>
                        <p>
                            At Hello Transport, you are in responsible hands. We offer you trusted partners to transport
                            oversized agricultural machinery at competitive rates. You do not need to be concerned
                            about the safe and affordable transportation of agricultural equipment. Get a quote, and
                            let us handle your agricultural machinery shipping.
                        </p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="single-services-box">
                        <span class="number">06</span>
                        <h3>Transport Excavator with Hello Transport</h3>
                        <p>
                            At Hello Transport, professionalism guides every move. Whether you want terminal delivery
                            or doorstep delivery, we are handling your excavator shipment professionally. With Hello
                            Transport, you no longer have to worry about the size, weight, and complexity of
                            excavators. Your order, our service. Get a quote and let us handle your shipping.
                        </p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="single-services-box">
                        <span class="number">07</span>
                        <h3>Transport Commercial Trucks with Hello Transport</h3>
                        <p>
                            Transporting a heavy-duty load like a commercial truck is a challenging part of the
                            transportation world. But you are in luck with Hello Transport, offering a certified
                            team to transport commercial trucks quickly, safely, and cost-effectively. Get a quote
                            and let us handle your shipping.
                        </p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="single-services-box">
                        <span class="number">08</span>
                        <h3>Transport Heavy Equipment with Hello Transport</h3>
                        <p>
                            Hello Transport promises reliability. We specialize in shipping heavy equipment across the
                            U.S., from agricultural heavy equipment transportation to mining equipment transportation,
                            handled by a highly professional team and shipped at market-competitive rates. Get a quote
                            and let us handle your shipping.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
