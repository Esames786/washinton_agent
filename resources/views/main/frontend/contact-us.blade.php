@extends('layouts.frontend-master')

@section('content')

<!-- Page Title -->
<div class="page-title-area bg-25">
    <div class="container">
        <div class="page-title-content">
            <h2>Contact Us</h2>
            <ul>
                <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                <li class="active">Contact Us</li>
            </ul>
        </div>
    </div>
</div>

<!-- Contact Form Area -->
<section class="main-contact-area ptb-100">
    <div class="container">
        <div class="section-title">
            <span class="sub-title">Contact With Us</span>
            <h2>Speak With Our Consultant</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('Post.Contact.Lead') }}" method="POST">
            @csrf
            <div class="row">

                <div class="col-lg-6 col-sm-6">
                    <div class="form-group">
                        <input type="text" name="Lead_Name" class="form-control" placeholder="Your Name" required value="{{ old('Lead_Name') }}">
                    </div>
                </div>

                <div class="col-lg-6 col-sm-6">
                    <div class="form-group">
                        <input type="email" name="Lead_Email" class="form-control" placeholder="Email Address" required value="{{ old('Lead_Email') }}">
                    </div>
                </div>

                <div class="col-lg-6 col-sm-6">
                    <div class="form-group">
                        <input type="text" name="Lead_Phone" class="form-control" placeholder="Phone Number" required value="{{ old('Lead_Phone') }}">
                    </div>
                </div>

                <div class="col-lg-6 col-sm-6">
                    <div class="form-group">
                        <input type="text" name="Lead_Subject" class="form-control" placeholder="Subject / Purpose" value="{{ old('Lead_Subject') }}">
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group">
                        <textarea name="Lead_Message" class="form-control" rows="6" placeholder="Write your message here...">{{ old('Lead_Message') }}</textarea>
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="default-btn btn-two">
                        <span>Send Message</span>
                    </button>
                </div>

            </div>
        </form>
    </div>
</section>

<!-- Google Map -->
<div class="map-area">
    <iframe
        src="https://www.google.com/maps?q=1007+FREDERICK+ROAD+CATONSVILLE+MD+21228+UNITED+STATES&output=embed"
        style="border:0;width:100%;height:400px;"
        allowfullscreen=""
        loading="lazy">
    </iframe>
</div>

@endsection
