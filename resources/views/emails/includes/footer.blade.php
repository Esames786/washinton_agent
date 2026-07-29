@php
    // Brand-aware email footer. On florida (PORTAL_BRAND=crazyrays) $brand is CrazyRays for every
    // recipient; on the Hello landing it stays Hello. CrazyRays has no Hello social/review accounts,
    // so those Hello-specific links are hidden for the CR brand.
    $brand   = $brand ?? \App\Support\Brand::current();
    $isHello = ($brand['key'] ?? 'hellotransport') === 'hellotransport';
    $site    = rtrim($brand['site'] ?? 'https://hellotransport.com', '/');
    $footer  = $brand['footer'] ?? 'Hello Transport. All Rights Reserved.';
@endphp

@if($isHello)
<p style="text-align: center;font-size: 18px">You can find us here too!</p>

<div style="text-align: center; margin-top: 10px;">
    <a href="https://www.google.com/search?q=hellotransport.com"><img
            src="{{ asset('images/google-re.png') }}" alt="Google Reviews"
            style="width: 75px; height: 32px; margin-right: 15px;" /></a>
    <a href="https://www.bbb.org/"><img
            src="{{ asset('images/bbb-re.jpeg') }}" alt="BBB Reviews"
            style="width: 75px; height: 32px ; margin-right: 15px;" /></a>
    <a href="https://www.trustpilot.com/review/hellotransport.com"><img src="{{ asset('images/trust-pilot-re.png') }}"
            alt="Trustpilot Reviews" style="width: 75px; height: 32px; margin-right: 15px;" /></a>
</div>
</div>
@endif

<div
    style="
          text-align: center;
          background-color: #062e39;
          color: white;
          padding: 10px;
          border-radius: 10px;
        ">
    @if($isHello)
    <div style="margin-top: 10px">
        <a href="https://www.facebook.com/hellotransport" style="margin: 0 10px; display: inline-block">
            <img src="{{ asset('images/fb-white.png') }}" alt="Facebook"
                style="width: 24px; height: 24px; border: none" />
        </a>
        <a href="https://twitter.com/hellotransport" style="margin: 0 10px; display: inline-block">
            <img src="{{ asset('images/X-white.png') }}" alt="Twitter"
                style="width: 24px; height: 24px; border: none" />
        </a>
        <a href="https://www.instagram.com/hellotransport/" style="margin: 0 10px; display: inline-block">
            <img src="{{ asset('images/insta-white.png') }}" alt="Instagram"
                style="width: 24px; height: 24px; border: none" />
        </a>
        <a href="https://www.linkedin.com/company/hellotransport/" style="margin: 0 10px; display: inline-block">
            <img src="{{ asset('images/linkedin-white.png') }}" alt="LinkedIn"
                style="width: 24px; height: 24px; border: none" />
        </a>
        <a href="https://www.youtube.com/@hellotransport" style="margin: 0 10px; display: inline-block">
            <img src="{{ asset('images/YouTube-white.png') }}" alt="YouTube"
                style="width: 30px; height: 22px; border: none" />
        </a>
    </div>
    @endif

    <p style="color: white">
        &copy; {{ date('Y') }} {{ $footer }}
    </p>
    |
    <a href="{{ $site }}/terms-conditions" style="color: #ffffff; text-decoration: none">Terms &
        Conditions</a>
    |
    <a href="{{ $site }}/faq" style="color: #ffffff; text-decoration: none">FAQs</a>
    |
</div>
