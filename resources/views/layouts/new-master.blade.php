<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    @php
        $nt = trim($__env->yieldContent('page_title') ?: $__env->yieldContent('title'));
        $nt = $nt ? $nt . ' | Hello Transport' : 'Hello Transport';
    @endphp
    <title>{{ $nt }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="dwlNH_KoCtphxr8_X75_OXA-nxdZWfmnrCrJssvnPO4" />

    <!-- Bootstrap Min CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/newtheme-assets/css/bootstrap.min.css') }}">
    <!-- Owl Theme Default Min CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/newtheme-assets/css/owl.theme.default.min.css') }}">
    <!-- Owl Carousel Min CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/newtheme-assets/css/owl.carousel.min.css') }}">
    <!-- Animate Min CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/newtheme-assets/css/animate.min.css') }}">
    <!-- Boxicons Min CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/newtheme-assets/css/boxicons.min.css') }}">
    <!-- Magnific Popup Min CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/newtheme-assets/css/magnific-popup.min.css') }}">
    <!-- Flaticon CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/newtheme-assets/css/flaticon.css') }}">
    <!-- Meanmenu Min CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/newtheme-assets/css/meanmenu.min.css') }}">
    <!-- Nice Select Min CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/newtheme-assets/css/nice-select.min.css') }}">
    <!-- Odometer Min CSS-->
    <link rel="stylesheet" href="{{ asset('frontend/newtheme-assets/css/odometer.min.css') }}">
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/newtheme-assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/newtheme-assets/css/old-theme-override.css') }}">
    <!-- Dark CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/newtheme-assets/css/dark.css') }}">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/newtheme-assets/css/responsive.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer" />
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('frontend/img/logo/hello_transport.svg') }}">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-N4GGWBB0YZ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-N4GGWBB0YZ');
    </script>

    <style>
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 15px;
            padding-bottom: 15px;
            margin-top: 40px;
        }
        .footer-bottom p {
            color: #ccc;
            margin: 0;
            font-size: 14px;
        }
        #chat-widget-container.inactivee { display: none; }
    </style>

    @yield('css')
</head>

<body>

{{-- HEADER --}}
@include('layouts.partials.new-header')

{{-- PAGE CONTENT --}}
<main>
    @yield('content')
</main>

{{-- LIVE CHAT WIDGET --}}
<div id="chat-widget-container" class="inactivee"
     style="position:fixed;bottom:90px;right:20px;z-index:99999;">

    <button id="chat-close"
            style="position:absolute;top:10px;right:10px;z-index:100001;background:#dc3545;color:#fff;border:none;border-radius:50%;width:36px;height:36px;display:none;">✕</button>

    <iframe
        id="chat-widget"
        src=""
        style="width:500px;height:550px;border:none;border-radius:12px;display:none;background:#fff;box-shadow:0 10px 30px rgba(0,0,0,0.25);overflow:hidden;">
    </iframe>
</div>

<button id="chat_with_us"
        style="position:fixed;bottom:20px;right:20px;z-index:100000;background:#111;color:#d4af37;border:2px solid #d4af37;border-radius:50px;padding:10px 18px;font-weight:600;box-shadow:0 8px 20px rgba(0,0,0,0.35);display:flex;align-items:center;gap:8px;">
    <img src="{{ asset('frontend/img/logo/hello_transport.svg') }}" alt="" style="height:28px;width:28px;border-radius:50%;background:#fff;padding:2px;">
    <span>Live Chat</span>
</button>

{{-- FOOTER --}}
@include('layouts.partials.new-footer')

{{-- NEW THEME JS --}}
<!-- jQuery Min JS -->
<script src="{{ asset('frontend/newtheme-assets/js/jquery.min.js') }}"></script>

<!-- Bootstrap Bundle Min JS -->
<script src="{{ asset('frontend/newtheme-assets/js/bootstrap.bundle.min.js') }}"></script>
<!-- Meanmenu Min JS -->
<script src="{{ asset('frontend/newtheme-assets/js/meanmenu.min.js') }}"></script>
<!-- Wow Min JS -->
<script src="{{ asset('frontend/newtheme-assets/js/wow.min.js') }}"></script>
<!-- Owl Carousel Min JS -->
<script src="{{ asset('frontend/newtheme-assets/js/owl.carousel.min.js') }}"></script>
<!-- Nice Select Min JS -->
<script src="{{ asset('frontend/newtheme-assets/js/nice-select.min.js') }}"></script>
<!-- Magnific Popup Min JS -->
<script src="{{ asset('frontend/newtheme-assets/js/magnific-popup.min.js') }}"></script>
<!-- Jarallax Min JS -->
<script src="{{ asset('frontend/newtheme-assets/js/jarallax.min.js') }}"></script>
<!-- Appear Min JS -->
<script src="{{ asset('frontend/newtheme-assets/js/appear.min.js') }}"></script>
<!-- Odometer Min JS -->
<script src="{{ asset('frontend/newtheme-assets/js/odometer.min.js') }}"></script>
<!-- Smoothscroll Min JS -->
<script src="{{ asset('frontend/newtheme-assets/js/smoothscroll.min.js') }}"></script>
<!-- Custom JS -->
<script src="{{ asset('frontend/newtheme-assets/js/custom.js') }}"></script>

<script>
    $('#chat_with_us').on('click', function() {
        var $widget = $('#chat-widget');
        var $container = $('#chat-widget-container');
        if ($widget.is(':visible')) {
            $widget.slideUp(300, function() {
                $container.addClass('inactivee');
            });
        } else {
            $container.removeClass('inactivee');
            $widget.css('display', 'block');
        }
    });

    $(window).on('load', function() {
        $('#chat-widget').attr('src', "https://www.agent.daydispatch.com/chat_dashboard?user_id=0");
    });
</script>

@stack('after-scripts')
@yield('extraScript')

</body>
</html>
