@extends('layouts.new-master')

@section('page_title', 'Privacy Policy | Hello Transport Vehicle Shipping USA')
@section('meta_description', 'Discover how Hello Transport safeguards your information. Our privacy policy ensures secure, transparent handling of customer data in nationwide auto transport.')

@section('content')

<style>
:root {
    --ht-primary: #d4af37;
    --ht-secondary: #111;
    --bg-light: #f8faff;
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
.ht-section-title-2 {
    font-size: clamp(1.1rem, 2vw, 1.3rem);
    font-weight: 600;
    color: var(--ht-secondary);
    margin-bottom: 8px;
    margin-top: 24px;
    border-left: 4px solid var(--ht-primary);
    padding-left: 12px;
}
.ht-content-section p { color: var(--text-main); line-height: 1.8; margin-bottom: 12px; }
.ht-content-section ul, .ht-content-section ol { color: var(--text-main); padding-left: 24px; margin-bottom: 14px; line-height: 1.8; }
.ht-pp-link { font-weight: 700; color: var(--ht-primary); text-decoration: underline; }
.ht-check-list { list-style: none; padding-left: 0; }
.ht-check-list li { padding: 4px 0 4px 28px; position: relative; color: var(--text-main); }
.ht-check-list li::before { content: "✓"; position: absolute; left: 0; color: var(--ht-primary); font-weight: 700; }
</style>

{{-- Page Banner --}}
<section class="page-title-area pt-100 pb-100"
         style="background-image:url({{ asset('frontend/newtheme-assets/img/banner/39.png') }});">
    <div class="container">
        <div class="page-title-content text-center">
            <h2>Privacy &amp; Policy</h2>
            <ul>
                <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                <li>Privacy &amp; Policy</li>
            </ul>
        </div>
    </div>
</section>

<section class="ht-content-section">
    <div class="container">

        <h1 class="ht-section-title">Hello Transport</h1>

        <div class="page-details-wrapper service-details-wrapper">

            <h2 class="ht-section-title-2">Welcome to the Privacy Policy</h2>
            <p>
                The information that Hello Transport ("we," "us," "our") gathers, uses, and discloses
                about you when you use our services and website,
                <a class="ht-pp-link" href="{{ route('Frontend.index') }}">www.hellotransport.com</a>
                (the "Site"), is outlined in this Privacy Policy. By accessing or using the Site or its services,
                you agree to the Privacy Policy.
            </p>
            <p>
                By accepting and agreeing to the privacy and policy of Hello Transport you therefore agree
                and acknowledge to be bound by the advised terms. If you deny or disagree with any of the
                below factors, then you won't be allowed access to our website or any of our services.
            </p>

            <h2 class="ht-section-title-2">Information We Collect</h2>
            <p>
                At Hello Transport we prioritize the privacy and security of our clients and viewers. As per
                the privacy policy, we only collect information to arrange your transportation and proceed
                with the process according to your needs. This policy covers both personal and non-personal,
                browsing behavior and cookies. We ensure the awareness and accuracy of our legal obligation
                to safeguard our client and viewer's information with the highest discretion and care and to
                use it only for the delivery of transportation services.
            </p>
            <p>By accessing or using our services, users agree to our Privacy Policy and the terms outlined below.</p>

            <ul class="ht-check-list">
                <li>Name</li>
                <li>Email Address</li>
                <li>Phone Number</li>
                <li>Shipping Address</li>
                <li>Shipping Details</li>
                <li>Vehicle Information</li>
                <li>Pick-up City, State &amp; Zip Code</li>
                <li>Delivery City, State &amp; Zip Code</li>
            </ul>

            <h2 class="ht-section-title-2">Personal Information</h2>
            <p>To book a transport Hello Transport diligently collects personal information relevant to your transportation needs.</p>
            <p>This could include:</p>
            <p>This information is essential to contact you about your transportation scheduling and booking.</p>

            <h2 class="ht-section-title-2">Usage Information</h2>
            <p>
                In our commitment to enhancing user experience, we gather usage information to improve our
                website's functionality and optimization. To maintain our commitment to improving user
                experience, it is vital to collect information about how our users and clients utilize our
                website, such as:
            </p>
            <p>
                All of the information collected is only to upgrade the operation of our website
                <a class="ht-pp-link" href="{{ route('Frontend.index') }}">www.hellotransport.com</a>.
            </p>

            <h2 class="ht-section-title-2">Cookies &amp; Relevant Technologies</h2>
            <p>
                Hello Transport utilizes cookies and similar technologies to enhance the functionality of our
                website and services. Using cookies for our website is essential as it helps us evaluate the
                website's overall performance, maintain user trends, analyze the website, track user navigation
                as well as collect demographic data.
            </p>

            <h2 class="ht-section-title-2">How We Use Your Information</h2>
            <p>
                At Hello Transport we are aware of our commitment to uphold the highest privacy protection
                standards by suitable laws and regulations. The reason why we collect your information is to
                ensure compliance with legal requirements and facilitate our services.
            </p>

            <h2 class="ht-section-title-2">Improvement of Services</h2>
            <p>
                We utilize your information and data to help us deliver and improve our services. Your data
                assists us and enables us to deliver our services in a timely and updated manner specially
                tailored to the needs and preferences of our customers.
            </p>

            <h2 class="ht-section-title-2">Order and Shipment Process</h2>
            <p>
                To proceed with your shipment and order on time we require your data such as name, shipping
                address, phone numbers, and payment information. This information is only required if you are
                willing to place an order with us.
            </p>

            <h2 class="ht-section-title-2">Order and Account Outreach</h2>
            <p>
                Your provided information enables us to communicate with our clients regarding their updates
                as well as orders. This includes delivery updates, order confirmation, and relevant information
                regarding your services.
            </p>

            <h2 class="ht-section-title-2">Promotional Offers and Services</h2>
            <p>
                We may send you service updates and promotional offers, but only with your express permission.
                By sending you these messages, we hope to improve your interaction with our services by keeping
                you informed about new products, exclusive offers, and significant changes.
            </p>

            <h2 class="ht-section-title-2">Third-Party Data Sharing</h2>
            <p>
                We might disclose your data to service providers such as drivers, shipping companies, and
                payment processors as they will ensure your services are being delivered timely and efficiently.
                To comply with legal requirements, and defend our rights, or the rights of others, we might
                also disclose your information to law enforcement. Our privacy policy states: "no mobile
                information will be shared with third parties/affiliates for marketing/promotional purposes.
                All other categories exclude text messaging originator opt-in data and consent; this
                information will not be shared with third parties."
            </p>

            <h2 class="ht-section-title-2">Your Privacy Rights</h2>
            <p>
                We value your privacy rights and are committed to helping you exercise them. You retain the
                right to remove, amend, or modify any personal data within our custody we have. Additionally,
                you maintain the right to opt out of receiving promotional communications from us at your own
                choice.
            </p>

            <h2 class="ht-section-title-2">Security Measures</h2>
            <p>
                Rigorous safety and security measures are taken into action to protect your information against
                unauthorized access, alteration, violation, or disclosure. Our security protocols adhere to
                best practices and are reviewed and updated regularly to practice data protection mechanisms.
            </p>

            <h2 class="ht-section-title-2">Changes to the Privacy Policy</h2>
            <p>
                Hello Transport is devoted to maintaining the privacy and security of your personal data.
                However, as part of our continuous efforts to ensure transparency and compliance with suitable
                laws, we might modify and update this Privacy Policy to welcome changes in our practices and
                regulatory requirements. We encourage and urge you to review this Privacy Policy periodically
                to stay updated.
            </p>

            <h2 class="ht-section-title-2">Contact Us</h2>
            <p>
                If you have any queries or concerns related to the privacy policy, you can directly contact us
                at <a class="ht-pp-link" href="mailto:info@hellotransport.com">info@hellotransport.com</a>.
            </p>

        </div>
    </div>
</section>

@endsection
