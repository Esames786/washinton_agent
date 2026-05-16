<style>
    /* Force all dropdown links visible on white background */
    .navbar .dropdown-menu a.title,
    .navbar .dropdown-menu a.nav-link {
        color: #222 !important;
        font-weight: 500;
        text-decoration: none;
        display: block;
        padding: 6px 12px;
        font-size: 14px;
    }
    .navbar .dropdown-menu a.title:hover,
    .navbar .dropdown-menu a.nav-link:hover {
        color: #d4af37 !important;
        background: #f5f5f5;
        text-decoration: none;
    }
    .navbar .dropdown-menu .ul-child-custom-heading {
        font-weight: 700;
        font-size: 15px;
        color: #111 !important;
        border-bottom: 2px solid #d4af37;
        padding-bottom: 6px;
    }
    .navbar .dropdown-menu {
        z-index: 99999 !important;
    }
    /* Make sure dropdown is not clipped by hero parallax layers */
    .header-area { position: relative; z-index: 9999; }
    .navbar-area { position: relative; z-index: 9999; }

    /* Mobile nav: flex row so logo stays left, toggle stays right */
    .mobile-nav .mobile-menu {
        display: flex;
        align-items: normal;
        justify-content: space-between;
        padding: 8px 0;
        min-height: 70px;
    }
    .mobile-nav .mobile-menu .logo { flex: 0 0 auto; }
    /* Push meanmenu toggle to the far right and center it vertically */
    .meanmenu-reveal {
        position: relative !important;
        top: auto !important;
        right: auto !important;
        float: right;
        margin-top: 0 !important;
        color: #d4af37 !important;
        border-color: #d4af37 !important;
        background: transparent !important;
    }
    /* Hamburger lines — gold */
    .meanmenu-reveal span {
        background: #d4af37 !important;
        display: block;
        height: 3px;
        border-radius: 2px;
    }
    /* X close button text color */
    .meanmenu-reveal.meanclose {
        color: #d4af37 !important;
        font-size: 22px !important;
        font-weight: 700 !important;
    }
</style>
<header class="header-area">

    {{-- ================= NAVBAR ================= --}}
    <div class="navbar-area">
        <div class="mobile-nav">
            <div class="container">
                <div class="mobile-menu">
                    <div class="logo">
                        <a href="{{ url('') }}">
                            <img src="{{ asset('frontend/img/logo/hello_transport.svg') }}" alt="logo" style="height:52px;width:auto;">
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="desktop-nav">
            <div class="container">
                <nav class="navbar navbar-expand-md navbar-light">

                    {{-- LOGO --}}
                    <a class="navbar-brand" href="{{ route('Frontend.index') }}">
                        <img src="{{ asset('frontend/img/logo/hello_transport.svg') }}" alt="Logo" style="height:100px">
                    </a>

                    <div class="collapse navbar-collapse mean-menu">

                        {{-- MENU --}}
                        <ul class="navbar-nav m-auto">
                            <li class="nav-item">
                                <a href="{{ route('Frontend.index') }}"
                                   class="nav-link {{ request()->routeIs('Frontend.index') ? 'active' : '' }}">
                                    Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('Frontend.about.us') }}"
                                   class="nav-link {{ request()->routeIs('Frontend.about.us') ? 'active' : '' }}">
                                    About Us
                                </a>
                            </li>

                            @php($cats = config('hello_services.categories'))

                            <li class="nav-item dropdown {{ request()->routeIs('Frontend.services*') ? 'active' : '' }}">
                                <a href="#" class="nav-link " role="button" data-bs-toggle="dropdown">
                                    Our Services
                                    <i class="bx bx-chevron-down"></i>
                                </a>

                                {{-- Mega Menu --}}
                                <ul class="dropdown-menu p-0" style="min-width: 900px;">
                                    <li class="p-4">
                                        <div class="row">

                                            {{-- Vehicle Transportation --}}
                                            <div class="col-sm-12 col-md-12 col-lg-4 bd-l bd-r">
                                                <p class="mb-3 ul-child-custom-heading text-white d-block d-lg-none ps-3">
                                                    {{ $cats['vehicle-transportation']['title'] }}
                                                </p>
                                                <p class="mb-3 ul-child-custom-heading d-none d-lg-block">
                                                    {{ $cats['vehicle-transportation']['title'] }}
                                                </p>

                                                @foreach($cats['vehicle-transportation']['services'] as $svc)
                                                    <div class="ps-3 ps-lg-0 mb-2">
                                                        <a class="title"
                                                           href="{{ route('Frontend.services.show', $svc['slug']) }}"
                                                           aria-label="{{ $svc['title'] }}"
                                                           title="Click to learn more about {{ $svc['title'] }}">
                                                            {{ $svc['title'] }}
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>

                                            {{-- Heavy Equipment --}}
                                            <div class="col-sm-12 col-md-12 col-lg-4 bd-r">
                                                <p class="mb-3 ul-child-custom-heading text-white d-block d-lg-none ps-3">
                                                    {{ $cats['heavy-equipment']['title'] }}
                                                </p>
                                                <p class="mb-3 ul-child-custom-heading d-none d-lg-block">
                                                    {{ $cats['heavy-equipment']['title'] }}
                                                </p>

                                                @foreach($cats['heavy-equipment']['services'] as $svc)
                                                    <div class="ps-3 ps-lg-0 mb-2">
                                                        <a class="title"
                                                           href="{{ route('Frontend.services.show', $svc['slug']) }}"
                                                           aria-label="{{ $svc['title'] }}"
                                                           title="Click to learn more about {{ $svc['title'] }}">
                                                            {{ $svc['title'] }}
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>

                                            {{-- Freight + RORO --}}
                                            <div class="col-sm-12 col-md-12 col-lg-4">
                                                <p class="mb-3 ul-child-custom-heading text-white d-block d-lg-none ps-3">
                                                    {{ $cats['freight-transportation']['title'] }}
                                                </p>
                                                <p class="mb-3 ul-child-custom-heading d-none d-lg-block">
                                                    {{ $cats['freight-transportation']['title'] }}
                                                </p>

                                                @foreach($cats['freight-transportation']['services'] as $svc)
                                                    <div class="ps-3 ps-lg-0 mb-2">
                                                        <a class="title"
                                                           href="{{ route('Frontend.services.show', $svc['slug']) }}"
                                                           aria-label="{{ $svc['title'] }}"
                                                           title="Click to learn more about {{ $svc['title'] }}">
                                                            {{ $svc['title'] }}
                                                        </a>
                                                    </div>
                                                @endforeach

                                                <p class="mb-3 ul-child-custom-heading text-white d-block d-lg-none ps-3 mt-4" title="RORO">
                                                    {{ $cats['roro']['title'] }}
                                                </p>
                                                <p class="mb-3 ul-child-custom-heading d-none d-lg-block mt-4" title="RORO">
                                                    {{ $cats['roro']['title'] }}
                                                </p>

                                                @foreach($cats['roro']['services'] as $svc)
                                                    <div class="ps-3 ps-lg-0 mb-2">
                                                        <a class="title"
                                                           href="{{ route('Frontend.services.show', $svc['slug']) }}"
                                                           aria-label="{{ $svc['title'] }}"
                                                           title="Click to learn more about {{ $svc['title'] }}">
                                                            {{ $svc['title'] }}
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    </li>
                                </ul>
                            </li>


                            <li class="nav-item dropdown {{ request()->routeIs('Frontend.qoute.request') ? 'active' : '' }}">
                                <a href="#" class="nav-link " role="button" data-bs-toggle="dropdown">
                                    Get a Quote
                                    <i class="bx bx-chevron-down"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="nav-item">
                                        <a href="{{ route('Frontend.qoute.request', ['type' => 'Car']) }}"
                                           class="nav-link {{ request('type') === 'Car' ? 'active' : '' }}">
                                            Car Quote
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('Frontend.qoute.request', ['type' => 'Heavy Equipment']) }}"
                                           class="nav-link {{ request('type') === 'Heavy Equipment' ? 'active' : '' }}">
                                            Heavy Equipment Quote
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('Frontend.qoute.request', ['type' => 'Dryvan']) }}"
                                           class="nav-link {{ request('type') === 'Dryvan' ? 'active' : '' }}">
                                            Freight Quote
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('Frontend.qoute.request', ['type' => 'Motorcycle']) }}"
                                           class="nav-link {{ request('type') === 'Motorcycle' ? 'active' : '' }}">
                                            Motorcycle
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('Frontend.qoute.request', ['type' => 'ATV/UTV']) }}"
                                           class="nav-link {{ request('type') === 'ATV/UTV' ? 'active' : '' }}">
                                            ATV/UTV
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('Frontend.qoute.request', ['type' => 'Golf Cart']) }}"
                                           class="nav-link {{ request('type') === 'Golf Cart' ? 'active' : '' }}">
                                            Golf Cart
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        </ul>

                        {{-- AUTH BUTTONS --}}
                        @if (!Auth::check())
                            <div class="others-option ms-3">
                                <a href="{{ url('/loginn') }}" class="default-btn">
                                    Login
                                </a>
                            </div>
                            <div class="others-option ms-3">
                                <a href="{{ url('/register') }}" class="default-btn">
                                    Signup
                                </a>
                            </div>
                        @else
                            <div class="others-option ms-3">
                                <a href="{{ url('/dashboard') }}" class="default-btn">
                                    Dashboard
                                </a>
                            </div>
                        @endif

                    </div>
                </nav>
            </div>
        </div>
    </div>

</header>
