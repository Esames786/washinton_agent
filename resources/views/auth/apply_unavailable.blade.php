@extends('layouts.new-master')
@section('page_title', 'Apply for an Opportunity')

@section('content')
{{-- Shown instead of the Apply form to visitors from a blocked country (config/signup.php). --}}
<section style="background: var(--ht-bg-soft); padding: 90px 0 120px;">
    <div class="container">
        <div style="max-width: 620px; margin: 0 auto; background: #fff; border: 1px solid var(--ht-border);
                    border-radius: 16px; box-shadow: var(--ht-shadow); padding: 40px 36px; text-align: center;">
            <div style="font-size: 40px; line-height: 1; margin-bottom: 14px;">🌐</div>
            <h1 style="font-size: 24px; font-weight: 800; color: var(--ht-navy); margin: 0 0 12px;">
                Applications are not available in your region
            </h1>
            <p style="font-size: 15px; color: var(--ht-muted); margin: 0 0 24px; line-height: 1.7;">
                {{ $message }} If you think this is a mistake, please contact us.
            </p>
            <a href="{{ url('/') }}" class="btn btn-primary">Back to Home</a>
        </div>
    </div>
</section>
@endsection
