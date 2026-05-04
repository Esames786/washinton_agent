<style>
    .font-size-34 {
        font-size: 34px!important;
    }
    #Origin_ZipCode_list ul {
        height: 140px;
        overflow-y: auto;
    }
    #Dest_ZipCode_list ul {
        height: 140px;
        overflow-y: auto;
    }
    .price__cta.pt-120.bg-css:before{
        content: '';
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        background: linear-gradient(218deg, rgb(1, 40, 99) 0%, rgba(1, 40, 99, 0) 100%);
    }
    .price__cta.pt-120.bg-css{
        position: relative;
        background-attachment: fixed;
    }
    #exampleModalToggle .nice-select{
        width: 100%;
    }
    #exampleModalToggle button[type="submit"]{
        background: var(--clr-common-color-red-2);
        padding: 17px 40px;
        text-transform: uppercase;
        color: var(--clr-common-white);
        font-weight: 600;
        font-family: oswald, sans-serif;
        display: inline-block;
        clip-path: polygon(11% 0, 100% 0, 89% 100%, 0 100%);
    }
    #exampleModalToggle .card{
        border: none;
    }
    h3.content-img {
        position: absolute;
        top: 64px;
        right: 37px;
        text-align: center;
    }
    .about__3.about__gray-bg::before {
        position: unset;
        top: 0;
        left: 0;
        width: 500px;
        height: 500px;
        background: var(--clr-bg-gray-4);
        content: "";
    }
    .ribbon-box .ribbon-two {
        position: absolute;
        left: 274px;
        top: -1px;
        z-index: 1;
        overflow: hidden;
        width: 75px;
        height: 75px;
        text-align: right;
    }
    .ribbon-box .ribbon-two span {
        font-size: 13px;
        color: #fff;
        text-align: center;
        line-height: 20px;
        -webkit-transform: rotate(-45deg);
        transform: rotate(-45deg);
        width: 100px;
        display: block;
        -webkit-box-shadow: 0 0 8px 0 rgba(0,0,0,.06),0 1px 0 0 rgba(0,0,0,.02);
        box-shadow: 0 0 8px 0 rgba(0,0,0,.06),0 1px 0 0 rgba(0,0,0,.02);
        position: absolute;
        top: 19px;
        left: -21px;
        font-weight: 500;
    }
    .ribbon-box .ribbon-two-danger span {
        background: #ed5e5e;
    }
    .ribbon-box.right.ribbon-box .ribbon-two span {
        left: auto;
        right: -21px;
        -webkit-transform: rotate(45deg);
        transform: rotate(45deg);
    }
    .broker-img {
        background-image: url("/frontend/img/plan-img/Broker-Image.webp");
        height: 354px;
        position: relative;
        background-size: cover;
    }
    .carrier-img {
        background-image: url("/frontend/img/plan-img/Carrier-Image.webp");
        height: 354px;
        position: relative;
        background-size: cover;
    }
    .shipper-img {
        background-image: url("/frontend/img/plan-img/Shipper-Image-1.webp");
        height: 354px;
        position: relative;
        background-size: cover;
    }
    .testimonial-shadow-card {
        clip-path: polygon(5% 0, 100% 0, 95% 100%, 0 100%);
    }
    .testimonial__text_card p {
        font-size: 20px;
        line-height: 34px;
        color: var(--clr-common-white);
        text-align: center;
    }
    .bg-white {
        width: 100%;
        background: var(--clr-theme-1)!important;
        padding: 20px;
    }
    .width-card {
        width: 380px;
    }
    .broker-img::after,
    .carrier-img::after,
    .shipper-img::after {
        position: absolute;
        content: "";
        background: rgb(255 255 255 / 14%);
        height: 100%;
        width: 100%;
        top: 0;
    }
    h2.text-center.z-index{
        z-index: 22;
        position: relative;
        width: 239px;
        margin: auto;
        clip-path: polygon(11% 0, 100% 0, 89% 100%, 0 100%);
        background: #bc101c;
        filter: drop-shadow(1px 17px 1px);
        color: white;
    }
</style>
<!-- banner area start  -->
<section class="banner-area banner-area1 pos-rel">
    <div class="swiper-container slider__active">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="single-banner banner-overlay single-banner-1 banner-830">
                    <div class="banner-bg banner-bg1 banner-bg1-1"
                         data-background="{{ asset('frontend/img/slider/23.png') }}">
                    </div>
                    <div class="container pos-rel">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <div class="banner-content banner-content1 banner-content1-1">
                                    <div class="banner-meta-text" data-animation="fadeInUp" data-delay=".3s">
                                        <span>welcome to our Hello Tranport</span>
                                    </div>
                                    <h1 class="banner-title" data-animation="fadeInUp" data-delay=".5s">
                                        Transport Anything <br> From Anywhere.
                                    </h1>
                                    <div class="banner-btn" data-animation="fadeInUp" data-delay=".7s">
                                        <a href="{{ route('Frontend.qoute.request') }}" class="fill-btn clip-btn">get
                                            estimation</a>
                                        <a class="skew-btn" href="{{ route('Frontend.is.me') }}">our services</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="single-banner banner-overlay single-banner-1 banner-830">
                    <div class="banner-bg banner-bg1 banner-bg1-1"
                         data-background="{{ asset('frontend/img/slider/39.png') }}">
                    </div>
                    <div class="container pos-rel">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <div class="banner-content banner-content1 banner-content1-1">
                                    <div class="banner-meta-text" data-animation="fadeInUp" data-delay=".3s">
                                        <span>welcome to our Hello Tranport</span>
                                    </div>
                                    <h1 class="banner-title" data-animation="fadeInUp" data-delay=".5s">
                                        Transport Business <br> is Movement.
                                    </h1>
                                    <div class="banner-btn" data-animation="fadeInUp" data-delay=".7s">
                                        <a href="{{ route('Frontend.qoute.request') }}" class="fill-btn clip-btn">get
                                            estimation</a>
                                        <a class="skew-btn" href="{{ route('Frontend.is.me') }}">our services</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="single-banner banner-overlay single-banner-1 banner-830">
                    <div class="banner-bg banner-bg1 banner-bg1-1"
                         data-background="{{ asset('frontend/img/slider/7.png') }}">
                    </div>
                    <div class="container pos-rel">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <div class="banner-content banner-content1 banner-content1-1">
                                    <div class="banner-meta-text" data-animation="fadeInUp" data-delay=".3s">
                                        <span>welcome to our Hello Tranport</span>
                                    </div>
                                    <h1 class="banner-title" data-animation="fadeInUp" data-delay=".5s">
                                        Transport Business <br> is Movement.
                                    </h1>
                                    <div class="banner-btn" data-animation="fadeInUp" data-delay=".7s">
                                        <a href="{{ route('Frontend.qoute.request') }}" class="fill-btn clip-btn">get
                                            estimation</a>
                                        <a class="skew-btn" href="{{ route('Frontend.is.me') }}">our services</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- If we need navigation buttons -->
    <div class="slider-nav d-none d-md-block">
        <div class="dp-nav-btn slider-button-prev"><i class="far fa-angle-left"></i></div>
        <div class="dp-nav-btn slider-button-next"><i class="far fa-angle-right"></i></div>
    </div>
    <!-- If we need pagination -->
    <div class="slider-pagination slider1-pagination"></div>
</section>
<!-- banner area end  -->
<!-- Services Area Start Here  -->
{{-- <section class="services__area ">
    <div class="container">
        <div class="services-one">
            <div class="services__box services__box--space wow fadeInUp" data-wow-duration="1.5s" data-wow-delay=".3s">
                <div class="row">
                    <div class="col-lg-3 col-xl-3 col-md-6">
                        <div class="services__item text-center">
                            <div class="services__item-icon">
                                <img src="{{ asset('frontend/img/icons/Vehicle Transportation Icon.png') }}" alt="Vehicle Transportation Icon" />
                            </div>
                            <div class="services__item-content">
                                <h3><a href="JavaScript:void(0);"><span>Vehicle</span> <br> transportation</a></h3>
                                <div class="services__item-btn">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xl-3 col-md-6">
                        <div class="services__item text-center">
                            <div class="services__item-icon">
                                 <img src="{{ asset('frontend/img/icons/Heavy Equipment Transportation Icon.png') }}" alt="Heavy Equipment Transportation Icon" />
                            </div>
                            <div class="services__item-content">
                                <h3><a href="JavaScript:void(0);"><span>Heavy</span><br> transportation </a></h3>
                                </p>
                                <div class="services__item-btn">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xl-3 col-md-6">
                        <div class="services__item text-center">
                            <div class="services__item-icon">
                                 <img src="{{ asset('frontend/img/icons/Dry Van Frieght Icon.png') }}" alt="Dry Van Frieght Icon" class="dryimage" />
                            </div>
                            <div class="services__item-content">
                                <h3><a href="JavaScript:void(0);"><span>Freight </span><br>transportation</a></h3>
                                <div class="services__item-btn">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xl-3 col-md-6">
                        <div class="services__item text-center">
                            <div class="services__item-icon">
                                 <img src="{{ asset('frontend/img/icons/Less%20Than%20Truck%20load%20Icon%20(1).png') }}" alt="" />
                            </div>
                            <div class="services__item-content">
                                <h3><a href="JavaScript:void(0);"><span>LTL </span><br>
                                (Less than truck load)</a></h3>
                                <div class="services__item-btn">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xl-3 col-md-6">
                        <div class="services__item text-center">
                            <div class="services__item-icon">
                                 <img src="{{ asset('frontend/img/icons/Full Truck Load Icon.png') }}" alt="Full Truck Load Icon" />
                            </div>
                            <div class="services__item-content">
                                <h3><a href="JavaScript:void(0);"><span>FTL </span><br> (Full Truck Load)</a></h3>
                                <div class="services__item-btn">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}
<!-- Services Area End Here  -->
<section class="about__area-2 pt-120 pb-60 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay=".3s" style="visibility: visible; animation-duration: 1.5s; animation-delay: 0.3s; animation-name: fadeInUp;background: var(--clr-bg-gray-2);">
    <div class="container">
       <div class="row align-items-center">
          <div class="col-lg-6 col-xl-5">
             <div class="about__content-2 mb-60 wow fadeInRight" data-wow-duration="1.5s" data-wow-delay=".5s" style="visibility: visible; animation-duration: 1.5s; animation-delay: 0.5s; animation-name: fadeInRight;">
                <div class="section__title mb-50">
                   <span class="sub-title">about us</span>
                   <h2 class="title">A company involved in <br> servicing, maintenance.</h2>
                </div>
                <div class="about__content-tab-2 mt-40">
                   <ul class="nav mb-5" id="myTab" role="tablist">
                      <li class="nav-item" role="presentation">
                         <button class="nav-link clip-lg-btn" id="approch-tab" data-bs-toggle="tab" data-bs-target="#approch" type="button" role="tab" aria-controls="approch" aria-selected="false">our approch</button>
                      </li>
                      <li class="nav-item" role="presentation">
                         <button class="nav-link clip-lg-btn" id="goal-tab" data-bs-toggle="tab" data-bs-target="#goal" type="button" role="tab" aria-controls="goal" aria-selected="false">our goal</button>
                      </li>
                      <li class="nav-item" role="presentation">
                         <button class="nav-link clip-lg-btn active" id="mision-tab" data-bs-toggle="tab" data-bs-target="#mision" type="button" role="tab" aria-controls="mision" aria-selected="true">our mision</button>
                      </li>
                   </ul>
                   <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade" id="approch" role="tabpanel" aria-labelledby="approch-tab">
                         <p>From freight forwarding and warehousing to last-mile delivery, fleet optimization, and supply chain visibility,
                             logistics leaders are harnessing the power of data to drive efficiency and maintain a competitive edge.
                             Discover how our solutions can help streamline your operations.
                         </p>
                         <div class="about__content-tab-btn mt-35">
                            <a class="fill-btn clip-md-btn" href="about-us.html">learn more</a>
                         </div>
                      </div>
                      <div class="tab-pane fade" id="goal" role="tabpanel" aria-labelledby="goal-tab">
                         <p>Our goal is to provide seamless, reliable, and high-quality logistics solutions that go beyond expectations.
                             By partnering with trusted carriers and leveraging smart transport strategies, we ensure the safe movement of goods and vehicles—guaranteeing efficiency,
                             transparency, and customer satisfaction at every step.</p>
                         <div class="about__content-tab-btn mt-35">
                            <a class="fill-btn clip-md-btn" href="about-us.html">learn more</a>
                         </div>
                      </div>
                      <div class="tab-pane fade active show" id="mision" role="tabpanel" aria-labelledby="mision-tab">
                         <p>At Hello Transport, our mission is simple; to provide top-notch vehicle transportation & logistics solutions for both bulk and single deliveries.
                             We understand the challenges individuals (Shippers) and brokers face when searching for reliable carriers.
                             That's why we've created a platform that bridges the gap between shippers and carriers, ensuring affordability, speed, and peace of mind throughout the process.</p>
                         <div class="about__content-tab-btn mt-35">
                            <a class="fill-btn clip-md-btn" href="about-us.html">learn more</a>
                         </div>
                      </div>
                   </div>
                </div>
             </div>
          </div>
          <div class="col-lg-6 col-xl-7">
             <div class="about__img-2 mb-60 ml-60 w-img p-relative wow fadeInLeft" data-wow-duration="1.5s" data-wow-delay=".3s" style="visibility: visible; animation-duration: 1.5s; animation-delay: 0.3s; animation-name: fadeInLeft;">
                <img src="{{ asset('img/portfolio/portfolio-sm-1.1.png') }}" alt="About">
                 <div class="about__btn-2">
{{--                   <a href="https://www.youtube.com/watch?v=5Gsam6jyRI0" class="popup-video play-btn play-btn-white"><i class="fas fa-play"></i></a>--}}
                </div>
             </div>
          </div>
       </div>
    </div>
 </section>
<!-- About Us Area Start Here  -->
{{--<section class="about__area about__area-padding mt-70 pb-60 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay=".3s">--}}
{{--    <div class="section__title gallery-section-title  text-center">--}}
{{--        <span class="sub-title">GROW WITH US</span>--}}
{{--        <h2 class="title">UNLOCK THE POTENTIAL OF YOUR BUSINESS</h2>--}}
{{--    </div>--}}
{{--    <div class="testimonial-box wow fadeInUp margin-auto" data-wow-duration="1.5s" data-wow-delay=".5s" style="visibility: visible; animation-duration: 1.5s; animation-delay: 0.5s; animation-name: fadeInUp;">--}}
{{--                            <br>--}}
{{--                            <br>--}}
{{--                            <br>--}}
{{--        <div class="swiper-container  testimonial-one swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events">--}}

{{--            <div class="swiper-wrapper justify-content-center  flex-wrap">--}}

{{--                <div class="width-card">--}}
{{--                <h2 class="text-center z-index">Shipper</h2>--}}
{{--                    <div class="testimonial-shadow-card">--}}
{{--                        <div class=" shipper-img">--}}
{{--                                <div class="testimonial__icon rate">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                            <div class="bg-white">--}}
{{--                            <div class="testimonial__text_card">--}}
{{--                                <p>Daydispatch  Explore Best For You</p>--}}
{{--                            </div>--}}
{{--                            <div class="testimonial__auth justify-content-center">--}}
{{--                            <div class="menu-btn">--}}
{{--                                <a class="clip-btn" href="https://daydispatch.com/Shippers">Shipper</a>--}}
{{--                            </div>--}}
{{--                            </div>--}}
{{--                            <div class="testimonial__quote-icon quote">--}}
{{--                                <i class="fas fa-quote-left"></i>--}}
{{--                            </div>--}}
{{--                            </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="width-card">--}}
{{--                <h2 class="text-center z-index  ">carrier</h2>--}}
{{--                    <div class="testimonial-shadow-card">--}}
{{--                        <div class=" carrier-img">--}}
{{--                        <div class="testimonial__icon rate">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="bg-white">--}}
{{--                            <div class="testimonial__text_card">--}}
{{--                                <p>Daydispatch  Explore Best For You</p>--}}
{{--                            </div>--}}
{{--                            <div class="testimonial__auth justify-content-center">--}}
{{--                            <div class="menu-btn">--}}
{{--                                <a class="clip-btn" href="https://daydispatch.com/Carriers">carrier</a>--}}
{{--                            </div>--}}
{{--                            </div>--}}
{{--                            <div class="testimonial__quote-icon quote">--}}
{{--                                <i class="fas fa-quote-left"></i>--}}
{{--                            </div>--}}
{{--                            </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="width-card">--}}
{{--                <h2 class="text-center z-index ">Broker</h2>--}}
{{--                    <div class="testimonial-shadow-card">--}}
{{--                        <div class="broker-img">--}}
{{--                            <div class="testimonial__icon rate">--}}

{{--                            </div>--}}
{{--                        </div>--}}
{{--                                <div class="bg-white">--}}
{{--                            <div class="testimonial__text_card">--}}
{{--                                <p>Hello Tranport Explore Best For You</p>--}}
{{--                            </div>--}}
{{--                            <div class="testimonial__auth justify-content-center">--}}
{{--                            <div class="menu-btn">--}}
{{--                                <a class="clip-btn" href="https://daydispatch.com/Brokers">Broker</a>--}}
{{--                            </div>--}}

{{--                            </div>--}}
{{--                            <div class="testimonial__quote-icon quote">--}}
{{--                                <i class="fas fa-quote-left"></i>--}}
{{--                            </div>--}}
{{--                            </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>--}}
{{--    </div>--}}
{{--</section>--}}
<!-- About Us Area End Here  -->
<section class="help__cta overlay bg-css overlay-red pt-50 pb-20" data-background="frontend/img/services/services-cta-bg.png" style="background-image: url(frontend/img/services/services-cta-bg.png);">
    <div class="container">
       <div class="row align-items-center wow fadeInUp" data-wow-duration="1.5s" data-wow-delay=".3s" style="visibility: visible; animation-duration: 1.5s; animation-delay: 0.3s; animation-name: fadeInUp;">
          <div class="col-md-8">
             <div class="help__cta-title mb-30">
                <h2>Need Expert Assistance with Your Shipment?</h2>
             </div>
          </div>
          <div class="col-md-4">
             <div class="help__cta-btn text-lg-end mb-30">
                <a class="skew-btn" href="/Quote-Request">get a quote</a>
             </div>
          </div>
       </div>
    </div>
 </section>
<!-- About Us 3 Area Start Here -->
<section class="about__3 black-bg about__gray-bg p-relative pt-90 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay=".3s">
    <div class="section__title gallery-section-title text-center">
        <span class="sub-title">GROW WITH US</span>
        <h2 class="title mb-4">Trusted Logistics & Transportation Services</h2>
    </div>
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-xl-5 col-lg-6">
                <div class="about__3-img-wrapper p-relative mb-60">
                    <div class="about__3-top w-img">
                        <img class="img-content" src="{{ asset('frontend/img/about/about-3-1.webp') }}" decoding="async" width="100%" height="100%" loading="lazy" alt="About" style="opacity: 0.4;">
                        <h3 class="content-img">comming <br> soon</h3>
                    </div>
                    <div class="about__3-main w-img">
                        <img src="{{ asset('frontend/img/about/dispatch-center.jpeg') }}" decoding="async" width="100%" height="100%" loading="lazy" alt="About">
                    </div>
                    <div class="about__3-text clip-box-sm">
                        <span><i class="far fa-trophy-alt"></i></span>
                        <h4 class="about__3-title">Years of Experience</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <div class="about__3-content mb-60">
                    <div class="section__title mb-30">
                        <span class="sub-title">Hello Transport</span>
                        <h2 class="title">Reliable Logistics Solutions</h2>
                    </div>
                    <div class="about__3-content-inner p-relative">
                        <div class="about__3-content-left">
                            <p class="mb-15 text-justify">
                                At Hello Transport, we are dedicated to delivering safe, timely, and efficient transportation solutions across the nation.
                                Our goal is to ensure your goods are delivered on schedule while maintaining the highest standards of care.
                            </p>
                            <p class="text-justify">
                                With years of experience, our operations are optimized to handle everything from vehicles to heavy machinery and freight shipments.
                            </p>
                            <div class="about__3-content-btn mt-35">
                                <a href="{{ route('Frontend.contact.us') }}" class="skew-btn">Contact Us</a>
                            </div>
                        </div>
                        <div class="about__3-content-right">
                            <div class="about__3-shadow">
                                <div class="about__3-content-num">
                                    <h2 class="font-size-34">150+</h2>
                                    <h6>Satisfied Customers</h6>
                                </div>
                            </div>
                            <div class="about__3-shadow">
                                <div class="about__3-content-num">
                                    <h2 class="font-size-34">5,000+</h2>
                                    <h6>Shipments Delivered</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Us 3 Area End Here -->
<section id="services__area-2" class="services__area-2 fix grey-bg-2 pt-120 pb-120" data-background="assets/img/services/services-bg.png" style="background-image: url(&quot;assets/img/services/services-bg.png&quot;);">
    <div class="services__section-area wow fadeInUp" data-wow-duration="1.5s" data-wow-delay=".3s" style="visibility: visible; animation-duration: 1.5s; animation-delay: 0.3s; animation-name: fadeInUp;">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <div class="services__section">
                        <div class="section__title mb-95">
                            <span class="sub-title">services</span>
                            <h2 class="title">what we do</h2>
                        </div>
                        <div class="services-two-nav">
                            <div class="services-button-prev" tabindex="0" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-3d934e9467b92d88"><i class="fas fa-long-arrow-left"></i></div>
                            <div class="services-button-next" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-3d934e9467b92d88"><i class="fas fa-long-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-8">
                    <div class="services-two">
                        <div class="swiper-container services-two-active swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events">
                            <!-- Additional required wrapper -->
                            <div class="swiper-wrapper" id="swiper-wrapper-9d1721eea28028f6" aria-live="polite" style="transition-duration: 0ms; transform: translate3d(-2340px, 0px, 0px);">
                                <div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-active" data-swiper-slide-index="2" role="group" aria-label="1 / 14" style="width: 370px; margin-right: 20px;">
                                    <div class="services__item text-center">
                                        <div class="services__item-icon mb-35">
                                            <i class="flaticon-frontal-truck"></i>
                                        </div>
                                        <div class="services__item-content">
                                            <a href="services-details.html">
                                                <h3>auto <br> transportation service</h3>
                                            </a>
                                            <p>The quality data, best network building
                                                uptime, fastest output
                                            </p>
                                        </div>
                                        <div class="services__item-shape">
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-next" data-swiper-slide-index="3" role="group" aria-label="2 / 14" style="width: 370px; margin-right: 20px;">
                                    <div class="services__item text-center">
                                        <div class="services__item-icon mb-35">
                                            <i class="fas fa-truck-monster"></i>
                                        </div>
                                        <div class="services__item-content">
                                            <a href="services-details.html">
                                                <h3>heavy equipment<br> transportation service</h3>
                                            </a>
                                            <p>The quality data, best network building
                                                uptime, fastest output
                                            </p>
                                        </div>
                                        <div class="services__item-shape">
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide swiper-slide-duplicate" data-swiper-slide-index="4" role="group" aria-label="3 / 14" style="width: 370px; margin-right: 20px;">
                                    <div class="services__item text-center">
                                        <div class="services__item-icon mb-35">
                                            <i class="fas fa-solid fa-dolly"></i>
                                        </div>
                                        <div class="services__item-content">
                                            <a href="services-details.html">
                                                <h3>freight <br> transportation service</h3>
                                            </a>
                                            <p>The quality data, best network building
                                                uptime, fastest output
                                            </p>
                                        </div>
                                        <div class="services__item-shape">
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide swiper-slide-duplicate" data-swiper-slide-index="5" role="group" aria-label="4 / 14" style="width: 370px; margin-right: 20px;">
                                    <div class="services__item text-center">
                                        <div class="services__item-icon mb-35">
                                            <i class="flaticon-frontal-truck"></i>
                                        </div>
                                        <div class="services__item-content">
                                            <a href="services-details.html">
                                                <h3>auto <br> transportation service</h3>
                                            </a>
                                            <p>The quality data, best network building
                                                uptime, fastest output
                                            </p>
                                        </div>
                                        <div class="services__item-shape">
                                        </div>
                                    </div>
                                </div>
                                <!-- Slides -->
                                <div class="swiper-slide" data-swiper-slide-index="0" role="group" aria-label="5 / 14" style="width: 370px; margin-right: 20px;">
                                    <div class="services__item text-center">
                                        <div class="services__item-icon mb-35">
                                            <i class="fas fa-truck-monster"></i>
                                        </div>
                                        <div class="services__item-content">
                                            <a href="services-details.html">
                                                <h3>heavy equipment<br> transportation service</h3>
                                            </a>
                                            <p>The quality data, best network building
                                                uptime, fastest output
                                            </p>
                                        </div>
                                        <div class="services__item-shape">
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide swiper-slide-prev" data-swiper-slide-index="1" role="group" aria-label="6 / 14" style="width: 370px; margin-right: 20px;">
                                    <div class="services__item text-center">
                                        <div class="services__item-icon mb-35">
                                            <i class="fas fa-solid fa-dolly"></i>
                                        </div>
                                        <div class="services__item-content">
                                            <a href="services-details.html">
                                                <h3>freight <br> transportation service</h3>
                                            </a>
                                            <p>The quality data, best network building
                                                uptime, fastest output
                                            </p>
                                        </div>
                                        <div class="services__item-shape">
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide swiper-slide-active" data-swiper-slide-index="2" role="group" aria-label="7 / 14" style="width: 370px; margin-right: 20px;">
                                    <div class="services__item text-center">
                                        <div class="services__item-icon mb-35">
                                            <i class="flaticon-frontal-truck"></i>
                                        </div>
                                        <div class="services__item-content">
                                            <a href="services-details.html">
                                                <h3>auto <br> transportation service</h3>
                                            </a>
                                            <p>The quality data, best network building
                                                uptime, fastest output
                                            </p>
                                        </div>
                                        <div class="services__item-shape">
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide swiper-slide-next" data-swiper-slide-index="3" role="group" aria-label="8 / 14" style="width: 370px; margin-right: 20px;">
                                    <div class="services__item text-center">
                                        <div class="services__item-icon mb-35">
                                            <i class="fas fa-truck-monster"></i>
                                        </div>
                                        <div class="services__item-content">
                                            <a href="services-details.html">
                                                <h3>heavy equipment<br> transportation service</h3>
                                            </a>
                                            <p>The quality data, best network building
                                                uptime, fastest output
                                            </p>
                                        </div>
                                        <div class="services__item-shape">
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide" data-swiper-slide-index="4" role="group" aria-label="9 / 14" style="width: 370px; margin-right: 20px;">
                                    <div class="services__item text-center">
                                        <div class="services__item-icon mb-35">
                                            <i class="fas fa-solid fa-dolly"></i>
                                        </div>
                                        <div class="services__item-content">
                                            <a href="services-details.html">
                                                <h3>freight <br> transportation service</h3>
                                            </a>
                                            <p>The quality data, best network building
                                                uptime, fastest output
                                            </p>
                                        </div>
                                        <div class="services__item-shape">
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide" data-swiper-slide-index="5" role="group" aria-label="10 / 14" style="width: 370px; margin-right: 20px;">
                                    <div class="services__item text-center">
                                        <div class="services__item-icon mb-35">
                                            <i class="flaticon-frontal-truck"></i>
                                        </div>
                                        <div class="services__item-content">
                                            <a href="services-details.html">
                                                <h3>auto <br> transportation service</h3>
                                            </a>
                                            <p>The quality data, best network building
                                                uptime, fastest output
                                            </p>
                                        </div>
                                        <div class="services__item-shape">
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide swiper-slide-duplicate" data-swiper-slide-index="0" role="group" aria-label="11 / 14" style="width: 370px; margin-right: 20px;">
                                    <div class="services__item text-center">
                                        <div class="services__item-icon mb-35">
                                            <i class="fas fa-truck-monster"></i>
                                        </div>
                                        <div class="services__item-content">
                                            <a href="services-details.html">
                                                <h3>heavy equipment<br> transportation service</h3>
                                            </a>
                                            <p>The quality data, best network building
                                                uptime, fastest output
                                            </p>
                                        </div>
                                        <div class="services__item-shape">
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-prev" data-swiper-slide-index="1" role="group" aria-label="12 / 14" style="width: 370px; margin-right: 20px;">
                                    <div class="services__item text-center">
                                        <div class="services__item-icon mb-35">
                                            <i class="fas fa-solid fa-dolly"></i>
                                        </div>
                                        <div class="services__item-content">
                                            <a href="services-details.html">
                                                <h3>freight <br> transportation service</h3>
                                            </a>
                                            <p>The quality data, best network building
                                                uptime, fastest output
                                            </p>
                                        </div>
                                        <div class="services__item-shape">
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-active" data-swiper-slide-index="2" role="group" aria-label="13 / 14" style="width: 370px; margin-right: 20px;">
                                    <div class="services__item text-center">
                                        <div class="services__item-icon mb-35">
                                            <i class="flaticon-frontal-truck"></i>
                                        </div>
                                        <div class="services__item-content">
                                            <a href="services-details.html">
                                                <h3>auto <br> transportation service</h3>
                                            </a>
                                            <p>The quality data, best network building
                                                uptime, fastest output
                                            </p>
                                        </div>
                                        <div class="services__item-shape">
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-next" data-swiper-slide-index="3" role="group" aria-label="14 / 14" style="width: 370px; margin-right: 20px;">
                                    <div class="services__item text-center">
                                        <div class="services__item-icon mb-35">
                                            <i class="fas fa-truck-monster"></i>
                                        </div>
                                        <div class="services__item-content">
                                            <a href="services-details.html">
                                                <h3>heavy equipment<br> transportation service</h3>
                                            </a>
                                            <p>The quality data, best network building
                                                uptime, fastest output
                                            </p>
                                        </div>
                                        <div class="services__item-shape">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{--<section id="services__area-2" class="services__area-2 fix grey-bg-2 pt-120 pb-120" data-background="assets/img/services/services-bg.png" style="background-image: url(&quot;assets/img/services/services-bg.png&quot;);">--}}
{{--    <div class="services__section-area wow fadeInUp" data-wow-duration="1.5s" data-wow-delay=".3s" style="visibility: visible; animation-duration: 1.5s; animation-delay: 0.3s; animation-name: fadeInUp;">--}}
{{--       <div class="container">--}}
{{--          <div class="row">--}}
{{--             <div class="col-lg-3 col-md-4">--}}
{{--                <div class="services__section">--}}
{{--                   <div class="section__title mb-95">--}}
{{--                      <span class="sub-title">services</span>--}}
{{--                      <h2 class="title">what we do</h2>--}}
{{--                   </div>--}}
{{--                   <div class="services-two-nav">--}}
{{--                      <div class="services-button-prev" tabindex="0" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-3d934e9467b92d88"><i class="fas fa-long-arrow-left"></i></div>--}}
{{--                      <div class="services-button-next" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-3d934e9467b92d88"><i class="fas fa-long-arrow-right"></i></div>--}}
{{--                   </div>--}}
{{--                </div>--}}
{{--             </div>--}}
{{--             <div class="col-lg-9 col-md-8">--}}
{{--                <div class="services-two">--}}
{{--                   <div class="swiper-container services-two-active swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events">--}}
{{--                        <!-- Additional required wrapper -->--}}
{{--                        <div class="swiper-wrapper" id="swiper-wrapper-9d1721eea28028f6" aria-live="polite" style="transition-duration: 0ms; transform: translate3d(-2340px, 0px, 0px);">--}}
{{--                            <div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-active" data-swiper-slide-index="2" role="group" aria-label="1 / 14" style="width: 370px; margin-right: 20px;">--}}
{{--                                <div class="services__item text-center">--}}
{{--                                <div class="services__item-icon mb-35">--}}
{{--                                    <i class="flaticon-frontal-truck"></i>--}}
{{--                                </div>--}}
{{--                                <div class="services__item-content">--}}
{{--                                    <a href="services-details.html">--}}
{{--                                        <h3>road <br> transportation</h3>--}}
{{--                                    </a>--}}
{{--                                    <p>The quality data, best network building--}}
{{--                                        uptime, fastest output--}}
{{--                                    </p>--}}
{{--                                </div>--}}
{{--                                <div class="services__item-shape">--}}
{{--                                </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-next" data-swiper-slide-index="3" role="group" aria-label="2 / 14" style="width: 370px; margin-right: 20px;">--}}
{{--                                <div class="services__item text-center">--}}
{{--                                    <div class="services__item-icon mb-35">--}}
{{--                                        <i class="fas fa-truck-monster"></i>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-content">--}}
{{--                                        <a href="services-details.html">--}}
{{--                                            <h3>ocean <br> transportation</h3>--}}
{{--                                        </a>--}}
{{--                                        <p>The quality data, best network building--}}
{{--                                            uptime, fastest output--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-shape">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="swiper-slide swiper-slide-duplicate" data-swiper-slide-index="4" role="group" aria-label="3 / 14" style="width: 370px; margin-right: 20px;">--}}
{{--                                <div class="services__item text-center">--}}
{{--                                    <div class="services__item-icon mb-35">--}}
{{--                                        <i class="flaticon-plane"></i>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-content">--}}
{{--                                        <a href="services-details.html">--}}
{{--                                            <h3>airways <br> transportation</h3>--}}
{{--                                        </a>--}}
{{--                                        <p>The quality data, best network building--}}
{{--                                            uptime, fastest output--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-shape">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="swiper-slide swiper-slide-duplicate" data-swiper-slide-index="5" role="group" aria-label="4 / 14" style="width: 370px; margin-right: 20px;">--}}
{{--                                <div class="services__item text-center">--}}
{{--                                    <div class="services__item-icon mb-35">--}}
{{--                                        <i class="flaticon-frontal-truck"></i>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-content">--}}
{{--                                        <a href="services-details.html">--}}
{{--                                            <h3>road <br> transportation</h3>--}}
{{--                                        </a>--}}
{{--                                        <p>The quality data, best network building--}}
{{--                                            uptime, fastest output--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-shape">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <!-- Slides -->--}}
{{--                            <div class="swiper-slide" data-swiper-slide-index="0" role="group" aria-label="5 / 14" style="width: 370px; margin-right: 20px;">--}}
{{--                                <div class="services__item text-center">--}}
{{--                                    <div class="services__item-icon mb-35">--}}
{{--                                        <i class="flaticon-boat"></i>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-content">--}}
{{--                                        <a href="services-details.html">--}}
{{--                                            <h3>ocean <br> transportation</h3>--}}
{{--                                        </a>--}}
{{--                                        <p>The quality data, best network building--}}
{{--                                            uptime, fastest output--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-shape">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="swiper-slide swiper-slide-prev" data-swiper-slide-index="1" role="group" aria-label="6 / 14" style="width: 370px; margin-right: 20px;">--}}
{{--                                <div class="services__item text-center">--}}
{{--                                    <div class="services__item-icon mb-35">--}}
{{--                                        <i class="flaticon-plane"></i>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-content">--}}
{{--                                        <a href="services-details.html">--}}
{{--                                            <h3>airways <br> transportation</h3>--}}
{{--                                        </a>--}}
{{--                                        <p>The quality data, best network building--}}
{{--                                            uptime, fastest output--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-shape">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="swiper-slide swiper-slide-active" data-swiper-slide-index="2" role="group" aria-label="7 / 14" style="width: 370px; margin-right: 20px;">--}}
{{--                                <div class="services__item text-center">--}}
{{--                                    <div class="services__item-icon mb-35">--}}
{{--                                        <i class="flaticon-frontal-truck"></i>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-content">--}}
{{--                                        <a href="services-details.html">--}}
{{--                                            <h3>road <br> transportation</h3>--}}
{{--                                        </a>--}}
{{--                                        <p>The quality data, best network building--}}
{{--                                            uptime, fastest output--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-shape">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="swiper-slide swiper-slide-next" data-swiper-slide-index="3" role="group" aria-label="8 / 14" style="width: 370px; margin-right: 20px;">--}}
{{--                                <div class="services__item text-center">--}}
{{--                                    <div class="services__item-icon mb-35">--}}
{{--                                        <i class="flaticon-boat"></i>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-content">--}}
{{--                                        <a href="services-details.html">--}}
{{--                                            <h3>ocean <br> transportation</h3>--}}
{{--                                        </a>--}}
{{--                                        <p>The quality data, best network building--}}
{{--                                            uptime, fastest output--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-shape">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="swiper-slide" data-swiper-slide-index="4" role="group" aria-label="9 / 14" style="width: 370px; margin-right: 20px;">--}}
{{--                                <div class="services__item text-center">--}}
{{--                                    <div class="services__item-icon mb-35">--}}
{{--                                        <i class="flaticon-plane"></i>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-content">--}}
{{--                                        <a href="services-details.html">--}}
{{--                                            <h3>airways <br> transportation</h3>--}}
{{--                                        </a>--}}
{{--                                        <p>The quality data, best network building--}}
{{--                                            uptime, fastest output--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-shape">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="swiper-slide" data-swiper-slide-index="5" role="group" aria-label="10 / 14" style="width: 370px; margin-right: 20px;">--}}
{{--                                <div class="services__item text-center">--}}
{{--                                    <div class="services__item-icon mb-35">--}}
{{--                                        <i class="flaticon-frontal-truck"></i>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-content">--}}
{{--                                        <a href="services-details.html">--}}
{{--                                            <h3>road <br> transportation</h3>--}}
{{--                                        </a>--}}
{{--                                        <p>The quality data, best network building--}}
{{--                                            uptime, fastest output--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-shape">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="swiper-slide swiper-slide-duplicate" data-swiper-slide-index="0" role="group" aria-label="11 / 14" style="width: 370px; margin-right: 20px;">--}}
{{--                                <div class="services__item text-center">--}}
{{--                                    <div class="services__item-icon mb-35">--}}
{{--                                        <i class="flaticon-boat"></i>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-content">--}}
{{--                                        <a href="services-details.html">--}}
{{--                                            <h3>ocean <br> transportation</h3>--}}
{{--                                        </a>--}}
{{--                                        <p>The quality data, best network building--}}
{{--                                            uptime, fastest output--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-shape">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-prev" data-swiper-slide-index="1" role="group" aria-label="12 / 14" style="width: 370px; margin-right: 20px;">--}}
{{--                                <div class="services__item text-center">--}}
{{--                                    <div class="services__item-icon mb-35">--}}
{{--                                        <i class="flaticon-plane"></i>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-content">--}}
{{--                                        <a href="services-details.html">--}}
{{--                                            <h3>airways <br> transportation</h3>--}}
{{--                                        </a>--}}
{{--                                        <p>The quality data, best network building--}}
{{--                                            uptime, fastest output--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-shape">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-active" data-swiper-slide-index="2" role="group" aria-label="13 / 14" style="width: 370px; margin-right: 20px;">--}}
{{--                                <div class="services__item text-center">--}}
{{--                                    <div class="services__item-icon mb-35">--}}
{{--                                        <i class="flaticon-frontal-truck"></i>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-content">--}}
{{--                                        <a href="services-details.html">--}}
{{--                                            <h3>road <br> transportation</h3>--}}
{{--                                        </a>--}}
{{--                                        <p>The quality data, best network building--}}
{{--                                            uptime, fastest output--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-shape">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-next" data-swiper-slide-index="3" role="group" aria-label="14 / 14" style="width: 370px; margin-right: 20px;">--}}
{{--                                <div class="services__item text-center">--}}
{{--                                    <div class="services__item-icon mb-35">--}}
{{--                                        <i class="flaticon-boat"></i>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-content">--}}
{{--                                        <a href="services-details.html">--}}
{{--                                            <h3>ocean <br> transportation</h3>--}}
{{--                                        </a>--}}
{{--                                        <p>The quality data, best network building--}}
{{--                                            uptime, fastest output--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                    <div class="services__item-shape">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--             </div>--}}
{{--          </div>--}}
{{--       </div>--}}
{{--    </div>--}}
{{--</section>--}}
<!-- Services Cta Area Start Here  -->
<section class="services__cta">
    <div class="container-fluid container-lg">
        <div class="services__cta-box p-relative services__cta-overlay"
             data-background="{{ asset('frontend/img/services/services-cta-bg.png') }}"
             style="background-image: url('{{ asset('frontend/img/services/services-cta-bg.png') }}');">
            <div class="services__cta-wrapper">
                <div class="services__cta-item t-right mb-15">
                    <h3>Need a Support?</h3>
                </div>
                <div class="services__cta-item text-center mb-15">
                    <i class="flaticon-envelope"></i>
                </div>


                <div class="services__cta-item mb-15">
                    <h3><a href="mailto:hodontime@shipa1.com"><span class="__cf_email__" data-cfemail="c6afa8a0a986b1a3a4a2a9b1e8a5a9ab">hodontime@shipa1.com</span></a></h3>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Services Cta Area End Here  -->
<!-- Work Precess Start Here  -->
<section class="work grey-bg mt--60">
    <div class="container">
        <div class="work__wrapper p-relative wow fadeInUp" data-wow-duration="1.5s" data-wow-delay=".3s">
            <div class="row align-items-center align-items-xxl-end">
                <div class="col-xl-5 col-lg-5">
                    <div class="work__content">
                        <div class="section__title mb-45">
                            <span class="sub-title">process</span>
                            <h2 class="title">Our Logistics Process</h2>
                        </div>
                        <div class="work__content-list pr-60">
                            <div class="work__item">
                                <div class="work__item-num">
                                    <h5>01</h5>
                                </div>
                                <div class="work__item-text">
                                    <h4> Submit Request</h4>
                                    <p>Easily submit your transportation or freight request through our system.
                                    </p>
                                </div>
                            </div>
                            <div class="work__item">
                                <div class="work__item-num">
                                    <h5>02</h5>
                                </div>
                                <div class="work__item-text">
                                    <h4> Confirmation & Planning</h4>
                                    <p>Our team reviews your request and plans the optimal logistics solution tailored to your needs.
                                    </p>
                                </div>
                            </div>
                            <div class="work__item">
                                <div class="work__item-num">
                                    <h5>03</h5>
                                </div>
                                <div class="work__item-text">
                                    <h4> Shipment Execution</h4>
                                    <p>Your goods are transported safely and efficiently, with real-time updates throughout the journey.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-7 col-lg-7">
                    <div class="work__img white-bg p-relative ml-30">
                        <img src="{{ asset('frontend/img/work/work-img.png') }}" alt="Work">
                        <div class="work__btn">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Work Precess End Here  -->
<!-- Gallery Section Start  -->
<section class="dp-gallery-area-03 pt-120 pb-120 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay=".3s" style="    background: var(--clr-bg-gray-2);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 col-12">
                <div class="section__title gallery-section-title mb-55 text-center">
                    <span class="sub-title">explore</span>
                    <h2 class="title">Grow With Us</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="dp-gallery-slider-03 p-relative">
        <div class="dp-gallery-active-03 swiper-container">
            <div class="swiper-wrapper">
                <div class="dp-single-gallery-03 swiper-slide">
                    <div class="dp-single-gallery-thumb-03"
                         data-background="{{ asset('frontend/img/gallery/auto_transport_broker.webp') }}"></div>
                    <div class="dp-gallery-content-03">
                        <h3 class="dp-gallery-title-03">
                            <a href="JavaScript:void(0);">Broker</a>
                        </h3>
                        <div class="dp-gallery-tag-03">
                            <span>logistics</span>
                        </div>
                    </div>
                    <div class="dp-gallery-view-03">
                        <a href="{{ asset('frontend/img/gallery/auto_transport_broker.webp') }}"
                           class="dp-gallery-plus-btn popup-image" aria-label="View Image: Auto Transport Broker">
                            <i class="fal fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="dp-single-gallery-03 swiper-slide">
                    <div class="dp-single-gallery-thumb-03"
                         data-background="{{ asset('frontend/img/gallery/28.png') }}"></div>
                    <div class="dp-gallery-content-03">
                        <h3 class="dp-gallery-title-03">
                            <a href="JavaScript:void(0);">Corporate Relocation</a>
                        </h3>
                        <div class="dp-gallery-tag-03">
                            <span>logistics</span>
                        </div>
                    </div>
                    <div class="dp-gallery-view-03">
                        <a href="{{ asset('frontend/img/gallery/28.png') }}"
                           class="dp-gallery-plus-btn popup-image" aria-label="View Larger Image: Corporate Vehicle Relocation">
                            <i class="fal fa-plus" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                <div class="dp-single-gallery-03 swiper-slide">
                    <div class="dp-single-gallery-thumb-03"
                         data-background="{{ asset('frontend/img/gallery/45.jpg') }}"></div>
                    <div class="dp-gallery-content-03">
                        <h3 class="dp-gallery-title-03">
                            <a href="JavaScript:void(0);">Salvage</a>
                        </h3>
                        <div class="dp-gallery-tag-03">
                            <span>logistics</span>
                        </div>
                    </div>
                    <div class="dp-gallery-view-03">
                        <a href="{{ asset('frontend/img/gallery/45.jpg') }}"
                           class="dp-gallery-plus-btn popup-image" aria-label="View Larger Image: Salvage Cars">
                            <i class="fal fa-plus" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                <div class="dp-single-gallery-03 swiper-slide">
                    <div class="dp-single-gallery-thumb-03"
                         data-background="{{ asset('frontend/img/gallery/17.png') }}"></div>
                    <div class="dp-gallery-content-03">
                        <h3 class="dp-gallery-title-03">
                            <a href="JavaScript:void(0);">Auction</a>
                        </h3>
                        <div class="dp-gallery-tag-03">
                            <span>logistics</span>
                        </div>
                    </div>
                    <div class="dp-gallery-view-03">
                        <a href="{{ asset('frontend/img/gallery/17.png') }}"
                           class="dp-gallery-plus-btn popup-image" aria-label="View Larger Image: Vehicle Auction">
                            <i class="fal fa-plus" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                <div class="dp-single-gallery-03 swiper-slide">
                    <div class="dp-single-gallery-thumb-03"
                         data-background="{{ asset('frontend/img/gallery/Vehicle_Dealer.webp') }}"></div>
                    <div class="dp-gallery-content-03">
                        <h3 class="dp-gallery-title-03">
                            <a href="JavaScript:void(0);">Dealer</a>
                        </h3>
                        <div class="dp-gallery-tag-03">
                            <span>logistics</span>
                        </div>
                    </div>
                    <div class="dp-gallery-view-03">
                        <a href="{{ asset('frontend/img/gallery/Vehicle_Dealer.webp') }}"
                           class="dp-gallery-plus-btn popup-image"   aria-label="View Larger Image: Vehicle Dealer">
                            <i class="fal fa-plus" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                <div class="dp-single-gallery-03 swiper-slide">
                    <div class="dp-single-gallery-thumb-03"
                         data-background="{{ asset('frontend/img/gallery/11.png') }}"></div>
                    <div class="dp-gallery-content-03">
                        <h3 class="dp-gallery-title-03">
                            <a href="JavaScript:void(0);">Import / Export</a>
                        </h3>
                        <div class="dp-gallery-tag-03">
                            <span>logistics</span>
                        </div>
                    </div>
                    <div class="dp-gallery-view-03">
                        <a href="{{ asset('frontend/img/gallery/11.png') }}"
                           class="dp-gallery-plus-btn popup-image"  aria-label="View Larger Image: Vehicle Importer">
                            <i class="fal fa-plus" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="dp-gallery-nav-03 d-none d-md-block">
            <button type="button" class="dp-gallery-button-prev-03"><i class="far fa-arrow-left"></i></button>
            <button type="button" class="dp-gallery-button-next-03"><i class="far fa-arrow-right"></i></button>
        </div>
    </div>
</section>
<section class="dp-funfact-area pt-120 pb-90" style="background: var(--clr-common-color-24)">
    <div class="container">
       <div class="dp-funfactor-grid wow fadeInUp" data-wow-duration="1.5s" data-wow-delay=".3s" style="visibility: visible; animation-duration: 1.5s; animation-delay: 0.3s; animation-name: fadeInUp;">
          <div class="dp-funfact-wrapper mb-30">
                <div class="dp-funfact-icon">
                   <i class="fal fa-globe"></i>
                </div>
                <div class="dp-funfact-content">
                   <h3 class="counter">12545</h3>
                   <p>Worldwide Clients</p>
                </div>
          </div>
          <div class="dp-funfact-wrapper mb-30">
                <div class="dp-funfact-icon">
                   <i class="fal fa-users"></i>
                </div>
                <div class="dp-funfact-content">
                   <h3 class="counter">11103</h3>
                   <p>Company Staffs</p>
                </div>
          </div>
          <div class="dp-funfact-wrapper mb-30">
                <div class="dp-funfact-icon">
                   <i class="fal fa-smile"></i>
                </div>
                <div class="dp-funfact-content">
                   <h3 class="counter">11025</h3>
                   <p>Satisfied Clients</p>
                </div>
          </div>
          <div class="dp-funfact-wrapper mb-30">
                <div class="dp-funfact-icon">
                   <i class="fal fa-truck"></i>
                </div>
                <div class="dp-funfact-content">
                   <h3 class="counter">12100</h3>
                   <p>Successful Delivery</p>
                </div>
          </div>
       </div>
    </div>
</section>
{{-- <div class="call__cta position p-absolute">
    <div class="container">
       <div class="row">
          <div class="col-lg-6">
             <div class="call__cta-wrapper call__cta-padd overlay overlay-red clip-box bg-css" data-background="assets/img/cta/call-cat-bg-1.png" style="background-image: url(&quot;assets/img/cta/call-cat-bg-1.png&quot;);">
                <div class="call__cta-content">
                   <h6 class="call__cta-content-small-title">Call us now</h6>
                   <h3 class="call__cta-content-title"><a href="tel:32622266600">326 222 666 00</a></h3>
                </div>
                <div class="call__cta-icon">
                   <i class="flaticon-telephone-call"></i>
                </div>
             </div>
          </div>
          <div class="col-lg-6">
             <div class="call__cta-wrapper call__cta-padd overlay overlay-red clip-box bg-css" data-background="assets/img/cta/call-cta-bg-2.png" style="background-image: url(&quot;assets/img/cta/call-cta-bg-2.png&quot;);">
                <div class="call__cta-content">
                   <h6 class="call__cta-content-small-title">Call us now</h6>
                   <h3 class="call__cta-content-title"><a href="mailto:info@webmail.com">info@webmail.com</a>
                   </h3>
                </div>
                <div class="call__cta-icon">
                   <i class="flaticon-envelope"></i>
                </div>
             </div>
          </div>
       </div>
    </div>
</div> --}}
<!-- Gallery Section End  -->
<!-- Price CTA Area Start Here  -->
{{--
<section class="price__cta pt-120 bg-css" data-background="{{ asset('frontend/img/services-cta-bg.jpg') }}">
    <div class="container">
        <div class="row justify-content-end">
            <div class="col-xxl-7 col-xl-8">
                <div class="price__cta-content-shadow">
                    <div class="price__cta-content">
                        <div class="section__title mb-55 wow fadeInUp" data-wow-duration="1.5s" data-wow-delay=".3s">
                            <span class="sub-title">call to action</span>
                            <h2 class="title">Get Instant Quote</h2>
                        </div>
                        <div class="price__cta-form wow fadeInUp" data-wow-duration="1.5s" data-wow-delay=".5s">
                            {{-- instant quote form removed --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
--}}
<!-- Price CTA Area End Here  -->
<!-- Fun-fact area start  -->
{{--
<section class="dp-funfact-area dp-funfact-area-03 grey-bg p-relative pt-120 pb-90"
         data-background="{{ asset('frontend/img/funfact/fact-bg-02-1.jpg') }}">
    <div class="container wow fadeInUp" data-wow-duration="1.5s" data-wow-delay=".3s">
        <div class="row">
            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-12">
                <div class="dp-funfact-wrapper dp-funfact-wrapper-03 mb-30">
                    <div class="dp-funfact-icon">
                        <i class="fal fa-globe"></i>
                    </div>
                    <div class="dp-funfact-content">
                        <h3 class="counter">19300</h3>
                        <p>Worldwide Users</p>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-12">
                <div class="dp-funfact-wrapper dp-funfact-wrapper-03 mb-30">
                    <div class="dp-funfact-icon">
                        <i class="fal fa-users"></i>
                    </div>
                    <div class="dp-funfact-content">
                        <h3 class="counter">17083</h3>
                        <p>Verified Users</p>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-12">
                <div class="dp-funfact-wrapper dp-funfact-wrapper-03 mb-30">
                    <div class="dp-funfact-icon">
                        <i class="fal fa-smile"></i>
                    </div>
                    <div class="dp-funfact-content">
                        <h3 class="counter">17227</h3>
                        <p>Satisfied Clients</p>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-12">
                <div class="dp-funfact-wrapper dp-funfact-wrapper-03 mb-30">
                    <div class="dp-funfact-icon">
                        <i class="fal fa-truck"></i>
                    </div>
                    <div class="dp-funfact-content">
                        <h3 class="counter">118907</h3>
                        <p>Successful Delivery</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
--}}
<!-- Funfact area end  -->
{{--
{{-- load board removed --}}
--}}
