@extends('layouts.new-master')
@section('title', ($service['title'] ?? 'Service') . ' – Hello Transport')

@section('content')

    {{-- ================= PAGE TITLE ================= --}}
    <section class="page-title-area pt-100 pb-100"
             style="background-image:url({{ asset('frontend/newtheme-assets/img/banner/7.png') }});">
        <div class="container">
            <div class="page-title-content text-center">
                <h2>{{ $service['title'] }}</h2>
                <ul>
                    <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                    <li><a href="{{ route('Frontend.services') }}">Services</a></li>
                    <li>{{ $service['title'] }}</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="services-area pt-100 pb-70 bg-f9f9f9">
        <div class="container">
            <div class="section-title">
                <span>{{ $categoryTitle }}</span>
                <h2>{{ $service['content']['headline'] ?? $service['title'] }}</h2>
                <p>{{ $service['excerpt'] ?? '' }}</p>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    @if(!empty($service['image']))
                        <img src="{{ asset($service['image']) }}" alt="{{ $service['title'] }}" style="max-width:100%; border-radius:16px;">
                    @endif
                </div>

                <div class="col-lg-6">
                    @foreach(($service['content']['paragraphs'] ?? []) as $p)
                        <p>{{ $p }}</p>
                    @endforeach

                    @if(!empty($service['content']['bullets']))
                        <ul class="mt-3">
                            @foreach($service['content']['bullets'] as $b)
                                <li>{{ $b }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="mt-4">
                        <a class="default-btn" href="{{ route('Frontend.qoute.request') }}">Get a Quote</a>
                        <a class="default-btn" style="margin-left:10px;" href="{{ route('Frontend.services') }}">Back to Services</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
