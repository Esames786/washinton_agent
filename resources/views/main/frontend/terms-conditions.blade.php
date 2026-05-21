@extends('layouts.new-master')

@section('page_title', 'Terms & Conditions | Hello Transport Auto Transport & Shipping USA')
@section('meta_description', 'Review Hello Transport terms & conditions to learn about our vehicle shipping policies, service guidelines, and commitments for transport.')

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
.ht-section-pra {
    text-align: center;
    font-size: clamp(0.95rem, 1.5vw, 1rem);
    color: var(--text-main);
    margin-bottom: 28px;
    max-width: 760px;
    margin-left: auto;
    margin-right: auto;
}
.ht-section-title-2 {
    font-size: clamp(1rem, 1.8vw, 1.2rem);
    font-weight: 600;
    color: var(--ht-secondary);
    margin-bottom: 8px;
    margin-top: 24px;
    border-left: 4px solid var(--ht-primary);
    padding-left: 12px;
}
.ht-content-section p { color: var(--text-main); line-height: 1.8; margin-bottom: 12px; }
.ht-content-section ol, .ht-content-section ul { color: var(--text-main); padding-left: 24px; margin-bottom: 14px; line-height: 1.8; }
.ht-content-section li { margin-bottom: 6px; }
.ht-pp-link { font-weight: 700; color: var(--ht-primary); text-decoration: underline; }
</style>

{{-- Page Banner --}}
<section class="page-title-area pt-100 pb-100"
         style="background-image:url({{ asset('frontend/newtheme-assets/img/banner/23.png') }});">
    <div class="container">
        <div class="page-title-content text-center">
            <h2>Terms &amp; Conditions</h2>
            <ul>
                <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                <li>Terms &amp; Conditions</li>
            </ul>
        </div>
    </div>
</section>

<section class="ht-content-section">
    <div class="container">

        <h1 class="ht-section-title">Terms &amp; Conditions</h1>
        <p class="ht-section-pra">
            Welcome to the terms and conditions of Hello Transport. Thank you for trusting and choosing
            Hello Transport for your shipping solutions. Review the following terms and conditions carefully
            before proceeding with our services.
        </p>

        <div class="page-details-wrapper service-details-wrapper">

            <h2 class="ht-section-title-2">1) Acceptance of Use</h2>
            <ol>
                <li>By using or accessing any of our services, websites <b><a class="ht-pp-link" href="{{ route('Frontend.index') }}">Hello Transport</a></b>, or features, you agree to adhere to the terms and conditions outlined below.</li>
                <li>If you do not accept any of these terms, you will not be permitted to use our services, website, or features.</li>
            </ol>

            <h2 class="ht-section-title-2">2) Service Agreement and Pickup/Delivery Schedule</h2>
            <ol>
                <li>Hello Transport is licensed and bonded by the FMCSA and agrees to arrange for the shipment of vehicles described in the quotation on or about the estimated dates based on the carrier/transport schedule.</li>
                <li>As a broker, we will designate a reliable carrier/transporter to fulfill the terms of this agreement.</li>
                <li>Hello Transport does not guarantee specific pickup or delivery dates; these dates are estimates only.</li>
                <li>Hello Transport and the designated carrier are not responsible for delays of any kind. Once a reliable carrier/transporter is assigned, Hello Transport fulfills its service agreement.</li>
            </ol>

            <h2 class="ht-section-title-2">3) First-Time Booking Form and Future Orders</h2>
            <ol>
                <li>When a customer contacts Hello Transport for the first time to avail of our services, a Booking Form must be completed and signed. This form will serve as the official agreement for the initial order.</li>
                <li>Once this first-time booking form is completed, it will be saved and stored in our records for future reference.</li>
                <li>For any subsequent orders or quotes made by the same customer, Hello Transport will proceed directly with the booking, without requiring a new booking form.</li>
                <li>The terms and conditions outlined in the original first-time booking form will automatically apply to all future orders made by the customer, including but not limited to any new orders placed over the phone, via email, or through any other means of communication.</li>
                <li>After the initial order, all future bookings are governed by the terms set forth in the first-time booking form, and the customer acknowledges and agrees to the continuation of the terms from that original agreement for any future orders.</li>
            </ol>

            <h2 class="ht-section-title-2">4) Terms and Conditions of Carrier/Transporter's Bill of Lading</h2>
            <ol>
                <li>This order is subject to all terms and conditions outlined in the carrier/transporter's straight bill of lading.</li>
                <li>Copies of the bill of lading are available from the carrier/transporter's office.</li>
                <li>After a carrier has been assigned, the bill of lading becomes the primary agreement, and the terms of that bill of lading will supersede all other agreements.</li>
            </ol>

            <h2 class="ht-section-title-2">5) Third-Party Links</h2>
            <ol>
                <li>Hello Transport's website may include third-party links.</li>
                <li>We are not responsible for the privacy policies or terms and conditions of any third-party links.</li>
                <li>We recommend reviewing the privacy policies of third-party websites before providing any personal information.</li>
            </ol>

            <h2 class="ht-section-title-2">6) Insurance Responsibility and Claims Process</h2>
            <ol>
                <li>The carrier/transporter assumes primary insurance responsibility during the transit of your vehicle.</li>
                <li>Any claims for damage must be directed to the actual carrier/transporter who transported your vehicle.</li>
                <li>The customer agrees that Hello Transport is not responsible for any damage claims. For information on the claim process, refer to the carrier/transporter's bill of lading.</li>
                <li>No claims or legal actions can be initiated against Hello Transport. Only the carrier's terms apply once a carrier is assigned to your order.</li>
            </ol>

            <h2 class="ht-section-title-2">7) Exclusions from Liability</h2>
            <ol>
                <li>The carrier/transporter will not be responsible for damages caused by:
                    <ul>
                        <li>Freezing of engine, cooling systems, or batteries</li>
                        <li>Leaking fluids</li>
                        <li>Mechanical malfunctions (including engine, transmission, drive trains, wiring systems, airbags, clutches, and any electrical components)</li>
                        <li>Wear and tear, including convertible tops, mufflers, and exhaust systems</li>
                        <li>Items left inside the vehicle</li>
                    </ul>
                </li>
            </ol>

            <h2 class="ht-section-title-2">8) Customer Responsibilities for Vehicle Preparation</h2>
            <ol>
                <li>The customer is responsible for preparing the vehicle for transport. This includes:
                    <ul>
                        <li>Disarming any alarms</li>
                        <li>Removing any loose parts, accessories, spoilers, or anything that could potentially fall off during transport</li>
                    </ul>
                </li>
                <li>The customer assumes responsibility for any damages caused by parts that fall off the vehicle during transport, including damage to other vehicles.</li>
            </ol>

            <h2 class="ht-section-title-2">9) Auto Rental Policy</h2>
            <ol>
                <li>Hello Transport does not provide any auto rental services for delays, damages, accidents, acts of God, or other unforeseen circumstances.</li>
                <li>Customers are advised to plan accordingly.</li>
            </ol>

            <h2 class="ht-section-title-2">10) Reposting Fee for Unavailable Vehicles</h2>
            <ol>
                <li>If a carrier is sent to pick up your vehicle and it is not available (e.g., if the vehicle is moved, unavailable, or cannot be picked up for any reason), Hello Transport may charge a $100 reposting fee.</li>
                <li>This fee will be added to your order to reschedule your transport, based on the first available date.</li>
            </ol>

            <h2 class="ht-section-title-2">11) Designation of Pickup/Delivery Agent</h2>
            <ol>
                <li>In the event that the vehicle owner or customer is unavailable for pickup or delivery, the customer must designate a person to act as their agent at the point of pickup or delivery.</li>
                <li>This designation will be noted on the order form.</li>
                <li>The customer or their designated agent will be notified for pickup a minimum of 3 to 24 hours in advance.</li>
            </ol>

            <h2 class="ht-section-title-2">12) Payment Terms and Delivery Attempts</h2>
            <ol>
                <li>All vehicles with a balance owing must be paid by CASH or CASHIER'S CHECK ONLY (in U.S. Dollars), payable to the carrier/transporter.</li>
                <li>If the customer is unavailable or does not have the required funds at the time of delivery, the vehicle will be taken to the nearest terminal.</li>
                <li>The customer will be responsible for retrieval, storage, or redelivery fees.</li>
                <li>It is the customer's responsibility to have the full payment when the carrier/transporter arrives.</li>
                <li>If the carrier cannot access the delivery address, the customer agrees to meet the carrier at a nearby location.</li>
                <li>If payment is not made by the required methods, the vehicle will be stored at the customer's expense.</li>
            </ol>

            <h2 class="ht-section-title-2">13) Non-Operable Vehicle Fee</h2>
            <ol>
                <li>If the vehicle becomes inoperative (due to mechanical issues or other reasons) at the time of pickup or during transport, a $150 non-operable vehicle fee will be assessed at the time of delivery.</li>
            </ol>

            <h2 class="ht-section-title-2">14) Liability Limitations and Content Verification</h2>
            <ol>
                <li>The carrier/transporter is not liable for any damages not caused by their negligence.</li>
                <li>The customer agrees that the vehicle is free of all personal items, including the trunk, and acknowledges that Hello Transport and the carrier are not responsible for any personal belongings left inside the vehicle.</li>
            </ol>

            <h2 class="ht-section-title-2">15) Claims Process and Damages Exception</h2>
            <ol>
                <li>Any damages to the vehicle during transport must be noted on the post-transport inspection form at the time of delivery.</li>
                <li>Any claims for damages not documented during the delivery inspection will not be honored.</li>
                <li>This process must be followed in order to initiate any claims against the carrier/transporter.</li>
            </ol>

            <h2 class="ht-section-title-2">16) Legal Venue for Claims and Litigation</h2>
            <ol>
                <li>All claims, litigation, or legal action arising out of this agreement must be filed in Baltimore County, Maryland, in the Superior Court.</li>
            </ol>

            <h2 class="ht-section-title-2">17) Cancellation Policy</h2>
            <ol>
                <li>You may cancel your order at any time, but cancellations must be submitted in writing via fax or email.</li>
                <li>Hello Transport will charge a $99.00 cancellation fee if you cancel before a carrier is assigned to your order.</li>
                <li>If a carrier has been assigned, a $199.00 cancellation fee will be applied.</li>
                <li>However, if your vehicle has not been scheduled for pickup within 30 days from the initial available date, you are entitled to a full refund of your deposit.</li>
            </ol>

            <h2 class="ht-section-title-2">18) Right to Refuse Service</h2>
            <ol>
                <li>Hello Transport reserves the right to refuse service to anyone who violates these terms and conditions or for any other reason deemed necessary.</li>
                <li>Threats, harassment, or other misconduct will result in immediate cancellation of the service order, and the administrative fee will not be refunded.</li>
                <li>The remainder of the deposit will be refunded.</li>
            </ol>

            <h2 class="ht-section-title-2">19) Disclaimer</h2>
            <ol>
                <li>Hello Transport disclaims responsibility for specific pickup or delivery dates, which are estimates only and are not guaranteed.</li>
                <li>Hello Transport is a broker and does not guarantee the reliability or performance of the carrier/transporter.</li>
                <li>Any delays or damages that arise during transport are not the responsibility of Hello Transport.</li>
                <li>Claims for damages must be filed with the carrier, as they hold primary insurance responsibility during transit.</li>
            </ol>

            <h2 class="ht-section-title-2">20) Limitations of Liability</h2>
            <ol>
                <li>Hello Transport will not be liable for any unforeseen issues, including those caused by other carriers, weather conditions, or mechanical issues.</li>
                <li>Customers must agree to these liability limitations when using our services.</li>
            </ol>

            <h2 class="ht-section-title-2">21) Changes to Terms &amp; Conditions</h2>
            <ol>
                <li>Hello Transport reserves the right to change or update these terms and conditions at any time.</li>
                <li>Any updates will be posted on our website, and it is your responsibility to review these terms periodically. By continuing to use our services, you accept the most recent version of the terms.</li>
            </ol>

            <h2 class="ht-section-title-2">22) Force Majeure</h2>
            <ol>
                <li>Hello Transport will not be held liable for any delays, damages, or failure to perform services due to events beyond its control.</li>
                <li>Such events include, but are not limited to, acts of God, pandemics, strikes, natural disasters, governmental actions, or any other unforeseen circumstances that hinder the ability to fulfill service commitments.</li>
                <li>If such a situation arises, Hello Transport will make reasonable efforts to notify the customer and reschedule services once the external conditions permit.</li>
            </ol>

            <h2 class="ht-section-title-2">23) Privacy Policy</h2>
            <ol>
                <li>Hello Transport respects your privacy and is committed to safeguarding your personal information.</li>
                <li>We do not sell, rent, or share your personal data to third parties except as necessary to complete the transportation service (e.g., sharing with the assigned carrier for shipment).</li>
                <li>For full details on how we collect, store, and use your personal information, please refer to our <a class="ht-pp-link" href="{{ route('Frontend.privacy') }}">Privacy Policy</a>, which is available on our website.</li>
                <li>By agreeing to these terms, you consent to the collection and use of your information as outlined in the Privacy Policy.</li>
            </ol>

            <h2 class="ht-section-title-2">24) Vehicle Inspection</h2>
            <ol>
                <li><b>Pre-Transport Inspection:</b>
                    <ol>
                        <li>The customer agrees to perform an inspection of the vehicle before transport and document any existing damages.</li>
                        <li>A pre-transport inspection will be conducted by Hello Transport or the assigned carrier.</li>
                        <li>The customer should ensure that all noted damages are recorded in the inspection form.</li>
                    </ol>
                </li>
                <li><b>Post-Transport Inspection:</b>
                    <ol>
                        <li>Upon delivery, the customer or their designated representative must conduct a post-transport inspection.</li>
                        <li>Any new damages discovered during the post-transport inspection must be immediately noted on the inspection form.</li>
                        <li>Discrepancies or damages not noted on the pre-transport inspection form will not be eligible for claims.</li>
                    </ol>
                </li>
                <li><b>Claims Process:</b>
                    <ol>
                        <li>In the event of damage, the customer must follow the claim procedure as outlined in the carrier's bill of lading.</li>
                        <li>Claims must be filed with the carrier directly and within the timeframe specified in the carrier's terms.</li>
                    </ol>
                </li>
            </ol>

            <h2 class="ht-section-title-2">25) Dispute Resolution</h2>
            <ol>
                <li><b>Mediation:</b>
                    <ol>
                        <li>Any disputes arising from or relating to this agreement shall first be attempted to be resolved through mediation.</li>
                        <li>Mediation will be conducted in good faith, and both parties agree to participate in the process.</li>
                    </ol>
                </li>
                <li><b>Arbitration:</b>
                    <ol>
                        <li>If mediation is unsuccessful, disputes shall be resolved through binding arbitration.</li>
                        <li>The arbitration will be conducted in accordance with the rules of the American Arbitration Association (AAA).</li>
                        <li>Both parties agree that any arbitration decision will be final and binding.</li>
                    </ol>
                </li>
            </ol>

            <h2 class="ht-section-title-2">26) Additional Fees for Remote Locations</h2>
            <ol>
                <li><b>Remote or Hard-to-Reach Locations:</b>
                    <ol>
                        <li>In certain situations, such as deliveries to remote or hard-to-reach locations, additional fees may apply.</li>
                        <li>These locations may include areas with restricted access or challenging road conditions, including but not limited to rural, mountainous, or isolated regions.</li>
                    </ol>
                </li>
                <li><b>Fee Disclosure:</b>
                    <ol>
                        <li>Any additional fees for such locations will be disclosed to the customer prior to the transport of the vehicle.</li>
                        <li>These fees will be agreed upon in writing by the customer before the service is performed.</li>
                    </ol>
                </li>
            </ol>

            <h2 class="ht-section-title-2">27) Vehicle Eligibility</h2>
            <ol>
                <li><b>Ineligible Vehicles:</b>
                    <ol>
                        <li>Certain vehicle types may not be eligible for transport due to size, weight, modifications, or other restrictions.</li>
                        <li>These vehicles may include:
                            <ul>
                                <li>Oversized vehicles</li>
                                <li>Vehicles with excessive modifications</li>
                                <li>Non-standard vehicle configurations</li>
                            </ul>
                        </li>
                    </ol>
                </li>
                <li><b>Right to Refuse Service:</b>
                    <ol>
                        <li>Hello Transport reserves the right to refuse service for any vehicle that does not meet the necessary criteria for safe transport.</li>
                        <li>The customer will be informed if their vehicle is ineligible, and a full refund (if applicable) will be provided.</li>
                    </ol>
                </li>
            </ol>

            <h2 class="ht-section-title-2">28) Customer Ownership and Documentation</h2>
            <ol>
                <li><b>Ownership Affirmation:</b>
                    <ol>
                        <li>The customer affirms that they are the legal owner of the vehicle or have the legal right to arrange for its transport.</li>
                        <li>The customer is solely responsible for any legal issues related to the ownership of the vehicle.</li>
                    </ol>
                </li>
                <li><b>Required Documentation:</b>
                    <ol>
                        <li>The customer must provide all required ownership documents upon request. These may include, but are not limited to, the vehicle's title, registration, or proof of ownership.</li>
                        <li>Hello Transport reserves the right to refuse service if the necessary documents are not provided.</li>
                    </ol>
                </li>
                <li><b>Legal Responsibility:</b>
                    <ol>
                        <li>Any legal issues or disputes related to the ownership of the vehicle, including but not limited to fraud or title discrepancies, are the sole responsibility of the customer.</li>
                    </ol>
                </li>
            </ol>

        </div>
    </div>
</section>

@endsection
