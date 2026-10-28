@extends('layouts.new-master')

@section('page_title', 'Terms & Conditions | Hello Transport Auto Transport & Shipping USA')
@section('meta_description', 'Review Hello Transport terms & conditions to learn about our vehicle shipping policies, service guidelines, and commitments for transport.')

@section('content')

<style>
:root {
    --ht-primary: var(--ht-accent-dark);
    --ht-secondary: var(--ht-navy);
    --bg-light: var(--ht-bg-soft);
    --text-main: #2d3436;
}
.ht-content-section { padding: 60px 0; background-color: var(--bg-light); }
.ht-section-title {
    text-align: center;
    font-size: clamp(1.8rem, 3vw, 2.6rem);
    font-weight: 700;
    color: var(--ht-secondary);
    margin-bottom: 10px;
}
.ht-section-pra {
    text-align: center;
    font-size: clamp(0.95rem, 1.5vw, 1rem);
    color: var(--text-main);
    margin-bottom: 28px;
    max-width: 760px;
    margin-left: auto;
    margin-right: auto;
}
.ht-section-title-2 {
    font-size: clamp(1rem, 1.8vw, 1.2rem);
    font-weight: 600;
    color: var(--ht-secondary);
    margin-bottom: 8px;
    margin-top: 24px;
    border-left: 4px solid var(--ht-primary);
    padding-left: 12px;
}
.ht-content-section p { color: var(--text-main); line-height: 1.8; margin-bottom: 12px; }
.ht-content-section ol, .ht-content-section ul { color: var(--text-main); padding-left: 24px; margin-bottom: 14px; line-height: 1.8; }
.ht-content-section li { margin-bottom: 6px; }
.ht-pp-link { font-weight: 700; color: var(--ht-primary); text-decoration: underline; }
.ht-legal-updated { text-align: center; color: var(--text-main); margin-bottom: 8px; }
</style>

{{-- Page Banner --}}
<section class="page-title-area pt-100 pb-100"
         style="background-image:url({{ asset('frontend/newtheme-assets/img/banner/23.png') }});">
    <div class="container">
        <div class="page-title-content text-center">
            <h2>Terms &amp; Conditions</h2>
            <ul>
                <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                <li>Terms &amp; Conditions</li>
            </ul>
        </div>
    </div>
</section>

<section class="ht-content-section">
    <div class="container">

        <h1 class="ht-section-title">Terms &amp; Conditions</h1>

        <div class="page-details-wrapper service-details-wrapper">
            @include('partials.legal.hello_terms_body')
        </div>
    </div>
</section>

@endsection
