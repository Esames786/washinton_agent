<footer class="footer-area pt-100 pb-60">
    <div class="container">
        {{-- florida (is_agent_portal) is a pure portal — no marketing footer columns
             (Services / Useful links / socials); only the copyright bar below renders. --}}
        @if (!config('app.is_agent_portal'))
        <div class="row">

            <!-- ===== LOGO + ABOUT + CONTACT ===== -->
            @php
                // Profile links (client is sending them). An empty link shows its icon/logo without a link.
                $htSocial = [
                    ['bxl-facebook', 'Facebook', ''],
                    ['bxl-instagram', 'Instagram', ''],
                    ['bxl-linkedin-square', 'LinkedIn', ''],
                    ['bxl-twitter', 'Twitter', ''],
                ];
                $htBadges = [
                    ['bbb.webp', 'BBB', '', 48, 48],
                    ['google-reviews.webp', 'Google Reviews', '', 80, 42],
                    ['trustpilot.webp', 'Trustpilot', '', 80, 42],
                ];
            @endphp
            <div class="col-lg-3 col-md-6">
                <div class="single-footer-widget">
                    <a href="{{ route('Frontend.index') }}" class="logo">
                        <img src="{{ asset(($brand['logo'] ?? 'frontend/img/logo/hello_transport.svg')) }}" alt="{{ $brand['name'] ?? 'Hello Transport' }}" style="height:70px;width:auto;">
                    </a>

                    <p>
                        Reliable logistics and transportation services delivering freight, heavy equipment,
                        and vehicles safely across the United States.
                    </p>

                    <p class="ht-footer-follow">Follow Us on Social Media</p>
                    <ul class="social-icon">
                        @foreach ($htSocial as [$icon, $label, $url])
                            <li>
                                <a @if ($url) href="{{ $url }}" target="_blank" rel="noopener noreferrer" @endif aria-label="{{ $label }}" title="{{ $label }}"><i class="bx {{ $icon }}"></i></a>
                            </li>
                        @endforeach
                    </ul>

                    <ul class="ht-footer-contact">
                        <li>
                            <i class="bx bx-map"></i>
                            <a href="https://www.google.com/maps/search/?api=1&amp;query=6340+Security+Blvd+Ste+100+Baltimore+MD+21207" target="_blank" rel="noopener noreferrer">6340 Security Blvd Ste 100, Baltimore, MD 21207</a>
                        </li>
                        <li>
                            <i class="bx bx-envelope"></i>
                            <a href="mailto:info@hellotransport.com">info@hellotransport.com</a>
                        </li>
                        <li>
                            <i class="bx bxs-truck"></i>
                            <span>USDOT No. 4572171 | MC No. 1818817</span>
                        </li>
                    </ul>

                    <div class="ht-footer-badges">
                        @foreach ($htBadges as [$file, $label, $url, $w, $h])
                            <a @if ($url) href="{{ $url }}" target="_blank" rel="noopener noreferrer" @endif title="{{ $label }}">
                                <img src="{{ asset('frontend/img/badges/' . $file) }}" alt="{{ $label }}" width="{{ $w }}" height="{{ $h }}" loading="lazy">
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- ===== SERVICES ===== -->
            <div class="col-lg-3 col-md-6">
                <div class="single-footer-widget">
                    <h3>Services</h3>

                    <ul class="import-link">
                        <li><a href="{{ route('Frontend.services.show', 'car') }}">Car Transportation</a></li>
                        <li><a href="{{ route('Frontend.services.show', 'heavy-equipment') }}">Heavy Equipment Transport</a></li>
                        <li><a href="{{ route('Frontend.services.show', 'dry-van') }}">Freight Transportation</a></li>
                        <li><a href="{{ route('Frontend.services.show', 'boat') }}">Boat Transport</a></li>
                        <li><a href="{{ route('Frontend.services.show', 'rv') }}">RV Transport</a></li>
                        <li><a href="{{ route('Frontend.services.show', 'roro') }}">RoRo Shipping</a></li>
                    </ul>
                </div>
            </div>

            <!-- ===== USEFUL LINKS ===== -->
            <div class="col-lg-3 col-md-6">
                <div class="single-footer-widget">
                    <h3>Company</h3>

                    <ul class="import-link">
                        <li><a href="{{ route('Frontend.about.us') }}">About Us</a></li>
                        <li><a href="{{ route('Frontend.blog') }}">Blog</a></li>
                        <li><a href="{{ route('Frontend.Testimonials') }}">Testimonials</a></li>
                        <li><a href="{{ route('Frontend.faq') }}">FAQ</a></li>
                        <li><a href="{{ route('Frontend.privacy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('Frontend.terms') }}">Terms &amp; Conditions</a></li>
                    </ul>
                </div>
            </div>

            <!-- ===== GET STARTED ===== -->
            <div class="col-lg-3 col-md-6">
                <div class="single-footer-widget">
                    <h3>Get Started</h3>

                    <ul class="import-link">
                        <li><a href="{{ route('Frontend.qoute.request') }}">Get a Quote</a></li>
                        <li><a href="{{ route('Frontend.contact.us') }}">Contact Us</a></li>
                        <li><a href="{{ route('Frontend.earn') }}">Earn With Us</a></li>
                        <li><a href="{{ url('/loginn') }}">Agent Sign In</a></li>
                    </ul>
                </div>
            </div>

        </div>
        @endif
    </div>

    <!-- ===== COPYRIGHT AREA ===== -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p>
                        Copyright &copy; {{ date('Y') }} {{ $brand['footer'] ?? 'Hello Transport. All Rights Reserved.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

</footer>
