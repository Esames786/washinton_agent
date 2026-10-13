@extends('layouts.new-master')
@section('title', 'Earn With Hello Transport – Become an Agent')

@section('content')

    {{-- ================= PAGE TITLE ================= --}}
    <section class="page-title-area pt-100 pb-100"
             style="background-image:url({{ asset('frontend/newtheme-assets/img/banner/7.png') }});">
        <div class="container">
            <div class="page-title-content text-center">
                <h2>Earn With Hello Transport</h2>
                <ul>
                    <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                    <li>Earn With Us</li>
                </ul>
            </div>
        </div>
    </section>

    {{-- ================= 1. OVERVIEW ================= --}}
    <section class="about-us-area pt-100 pb-70">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="about-us-content">
                        <span>Agent Program</span>
                        <h2>Build Your Own Book of Business</h2>
                        <p>
                            Hello Transport works with sales agents across the world. You bring the
                            customers; we handle dispatch, carrier vetting, compliance, paperwork and
                            payment collection. You earn commission on every load that moves.
                        </p>
                        <p>
                            There is no office to come into and no fixed hours beyond covering the
                            U.S. business day when your customers need you. Most of our agents work
                            remotely, on their own schedule, using our portal to quote, book and
                            track their shipments.
                        </p>
                        <a href="#apply" class="default-btn">Apply Now</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="about-us-image">
                        <img src="{{ asset('frontend/newtheme-assets/img/about/1.png') }}" alt="Agent Program">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= 2. WHY WORK WITH US ================= --}}
    <section class="services-area bg-color pt-100 pb-70">
        <div class="container">
            <div class="section-title">
                <span>Benefits</span>
                <h2>Why Work With Us</h2>
                <p>What you get as part of the Hello Transport network.</p>
            </div>

            <div class="row justify-content-center">
                @php
                    $benefits = [
                        ['icon' => 'fa-solid fa-sack-dollar',    'title' => 'Commission On Every Load',
                         'text'  => 'You are paid a percentage of the revenue you generate, with no cap. The more you move, the more you earn.'],
                        ['icon' => 'fa-solid fa-house-laptop',   'title' => 'Work From Anywhere',
                         'text'  => 'Fully remote. All you need is a reliable computer, a stable internet connection and a quiet place to work.'],
                        ['icon' => 'fa-solid fa-headset',        'title' => 'Dispatch &amp; Back Office',
                         'text'  => 'Our dispatch team covers carrier sourcing, paperwork and problem solving, so you can stay on the phone selling.'],
                        ['icon' => 'fa-solid fa-graduation-cap', 'title' => 'Training Provided',
                         'text'  => 'Structured onboarding covering our systems, pricing, lanes and how to win and keep customers.'],
                        ['icon' => 'fa-solid fa-network-wired',  'title' => 'Established Carrier Network',
                         'text'  => 'Quote with confidence using our vetted carrier base and live pricing, instead of starting from nothing.'],
                        ['icon' => 'fa-solid fa-money-check-dollar', 'title' => 'Paid Your Way',
                         'text'  => 'Commission paid by bank transfer, Wise or PayPal, with a clear monthly statement of what you earned.'],
                    ];
                @endphp

                @foreach($benefits as $b)
                    <div class="col-lg-4 col-sm-6">
                        <div class="single-services-box">
                            <i class="{{ $b['icon'] }}"></i>
                            <h3>{!! $b['title'] !!}</h3>
                            <p>{!! $b['text'] !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= 3. HOW IT WORKS ================= --}}
    <section class="pt-100 pb-70" style="background:#062e39;">
        <div class="container">
            <div class="section-title">
                <span style="color:#8fc445;">The Process</span>
                <h2 style="color:#fff;">How To Become An Agent</h2>
                <p style="color:rgba(255,255,255,.7);">
                    Six steps from application to your first commission.
                </p>
            </div>

            <div class="row justify-content-center">
                @php
                    $steps = [
                        ['n' => 1, 'title' => 'Apply',        'text' => 'Complete the short form below. It takes only a few minutes.'],
                        ['n' => 2, 'title' => 'Get Reviewed', 'text' => 'Our recruitment team reviews your experience and gets in touch if it is a fit.'],
                        ['n' => 3, 'title' => 'Get Approved', 'text' => 'An interview and a short practical assessment, then a formal offer.'],
                        ['n' => 4, 'title' => 'Onboarding',   'text' => 'Paperwork, documents and training on our systems, pricing and lanes.'],
                        ['n' => 5, 'title' => 'Start Selling','text' => 'Your portal account goes live and you begin quoting and booking.'],
                        ['n' => 6, 'title' => 'Earn',         'text' => 'Commission on every load you move, paid monthly with a clear statement.'],
                    ];
                @endphp

                @foreach($steps as $s)
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                        <div class="text-center px-2">
                            <div style="width:62px;height:62px;line-height:62px;margin:0 auto 18px;
                                        border-radius:50%;background:#8fc445;color:#062e39;
                                        font-size:24px;font-weight:800;">
                                {{ $s['n'] }}
                            </div>
                            <h4 style="color:#fff;font-size:17px;margin-bottom:10px;">{{ $s['title'] }}</h4>
                            <p style="color:rgba(255,255,255,.7);font-size:13.5px;line-height:1.7;">
                                {{ $s['text'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= 4. READY TO APPLY ================= --}}
    <section id="apply" class="pt-100 pb-100">
        <div class="container">
            <div class="section-title">
                <span>Join Us</span>
                <h2>Ready To Apply?</h2>
                <p>
                    Tell us a little about yourself. If your profile matches an opening, our
                    recruitment team will be in touch.
                </p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div style="background:#f8f9fa;border:1px solid #e9ecef;border-radius:10px;padding:40px 30px;">
                        <i class="fa-solid fa-paper-plane" style="font-size:42px;color:#062e39;margin-bottom:18px;"></i>
                        <h3 style="margin-bottom:12px;">Apply for an Opportunity</h3>
                        <p style="color:#6c757d;margin-bottom:24px;">
                            The application takes only a few minutes. You will need your contact
                            details and, optionally, your CV or LinkedIn profile.
                        </p>
                        <a href="{{ url('/register') }}" class="default-btn">
                            Apply to Join the Network
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= 5. ALREADY PART OF OUR TEAM ================= --}}
    <section class="pt-70 pb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3"
                         style="background:#062e39;border-radius:10px;padding:28px 34px;">
                        <div>
                            <h4 style="color:#fff;margin-bottom:6px;">Already part of our team?</h4>
                            <p style="color:rgba(255,255,255,.7);margin:0;font-size:14px;">
                                Sign in to your workspace to manage orders, customers and opportunities.
                            </p>
                        </div>
                        <a href="{{ url('/loginn') }}" class="default-btn" style="flex-shrink:0;">
                            Sign In
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= 6. FAQ ================= --}}
    <section class="faq-area bg-color pt-100 pb-100">
        <div class="container">
            <div class="section-title">
                <span>FAQs</span>
                <h2>Questions We Get Asked</h2>
            </div>

            @php
                $faqs = [
                    ['q' => 'Do I need previous experience in logistics?',
                     'a' => 'It helps, but it is not essential. We look for people who are comfortable selling over the phone and dealing with customers. Sales, customer service, dispatch or freight experience all count. Training covers the rest.'],
                    ['q' => 'Which campaigns can I work on?',
                     'a' => 'Our agents cover auto transport, heavy equipment, freight and RoRo shipping. Where you start depends on your experience and what we have open, and is agreed during onboarding.'],
                    ['q' => 'What are the working hours?',
                     'a' => 'Your customers are in the United States, so you need to cover a reasonable part of the U.S. business day. Beyond that the schedule is yours. We ask on the application whether you can work U.S. hours.'],
                    ['q' => 'Is this employment or commission only?',
                     'a' => 'Agents work as subcontractors and are paid commission on the revenue they generate, rather than a basic salary. The rate is confirmed in your offer.'],
                    ['q' => 'How and when am I paid?',
                     'a' => 'Monthly, by bank transfer, Wise or PayPal. You get a statement showing revenue generated, the commission rate applied and the net amount payable.'],
                    ['q' => 'What equipment do I need?',
                     'a' => 'A reliable computer, a stable internet connection, a headset and a quiet place to work. We ask for photos of your setup during onboarding.'],
                    ['q' => 'How long does the hiring process take?',
                     'a' => 'It varies with how quickly documents and interviews can be arranged. You can follow your own progress in the HR portal once you are shortlisted.'],
                    ['q' => 'Can I apply from outside the United States?',
                     'a' => 'Yes. Our agents work from many countries. You will need to provide identification and tax or payment documents valid in your own country.'],
                ];
            @endphp

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="accordion" id="earnFaq">
                        @foreach($faqs as $i => $f)
                            <div class="accordion-item" style="margin-bottom:12px;border-radius:8px;overflow:hidden;">
                                <h2 class="accordion-header" id="faqHead{{ $i }}">
                                    <button class="accordion-button {{ $i === 0 ? '' : 'collapsed' }}" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#faqBody{{ $i }}"
                                            aria-expanded="{{ $i === 0 ? 'true' : 'false' }}"
                                            aria-controls="faqBody{{ $i }}">
                                        {{ $f['q'] }}
                                    </button>
                                </h2>
                                <div id="faqBody{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                                     aria-labelledby="faqHead{{ $i }}" data-bs-parent="#earnFaq">
                                    <div class="accordion-body" style="font-size:14.5px;line-height:1.8;color:#555;">
                                        {{ $f['a'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
