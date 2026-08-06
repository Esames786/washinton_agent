<footer class="footer-area pt-100 pb-60">
    <div class="container">
        {{-- florida (is_agent_portal) is a pure portal — no marketing footer columns
             (Services / Useful links / socials); only the copyright bar below renders. --}}
        @if (!config('app.is_agent_portal'))
        <div class="row">

            <!-- ===== LOGO + ABOUT ===== -->
            <div class="col-lg-3 col-md-6">
                <div class="single-footer-widget">
                    <a href="{{ route('Frontend.index') }}" class="logo">
                        <img src="{{ asset(($brand['logo'] ?? 'frontend/img/logo/hello_transport.svg')) }}" alt="{{ $brand['name'] ?? 'Hello Transport' }}" style="height:70px;width:auto;">
                    </a>

                    <p>
                        Reliable logistics and transportation services delivering freight, heavy equipment,
                        and vehicles safely across the United States.
                    </p>

                    <ul class="social-icon">
                        <li>
                            <a href="#" target="_blank"><i class="bx bxl-facebook"></i></a>
                        </li>
                        <li>
                            <a href="#" target="_blank"><i class="bx bxl-instagram"></i></a>
                        </li>
                        <li>
                            <a href="#" target="_blank"><i class="bx bxl-linkedin-square"></i></a>
                        </li>
                        <li>
                            <a href="#" target="_blank"><i class="bx bxl-twitter"></i></a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- ===== SERVICES ===== -->
            <div class="col-lg-3 col-md-6">
                <div class="single-footer-widget">
                    <h3>Services</h3>

                    <ul class="import-link">
                        <li><a href="#">Car Transportation</a></li>
                        <li><a href="#">Heavy Equipment Transport</a></li>
                        <li><a href="#">Freight Transportation</a></li>
                        <li><a href="#">Logistics Solutions</a></li>
                    </ul>
                </div>
            </div>

            <!-- ===== USEFUL LINKS ===== -->
            <div class="col-lg-3 col-md-6">
                <div class="single-footer-widget">
                    <h3>Useful links</h3>

                    <ul class="import-link">
                        <li><a href="{{ route('Frontend.about.us') }}">About Us</a></li>
                        <li><a href="{{ route('Frontend.Testimonials') }}">Testimonials</a></li>
                        <li><a href="{{ route('Frontend.faq') }}">FAQ</a></li>
                        <li><a href="{{ route('Frontend.privacy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('Frontend.terms') }}">Terms &amp; Conditions</a></li>
                        <li><a href="{{ url('/loginn') }}">Login</a></li>
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
                        Copyright &copy; 2025 {{ $brand['footer'] ?? 'Hello Transport. All Rights Reserved.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

</footer>
