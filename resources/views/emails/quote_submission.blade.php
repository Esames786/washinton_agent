@php $brand = \App\Support\Brand::byKey('hellotransport'); @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quote Submission Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: #fff;
            padding: 30px;
            text-align: center;
        }
        .logo-section {
            margin-bottom: 20px;
        }
        .logo-section img {
            height: 60px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header-tagline {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }
        .content {
            padding: 40px;
        }
        .greeting {
            font-size: 18px;
            color: #1a1a1a;
            margin-bottom: 20px;
        }
        .status-box {
            background-color: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .status-box strong {
            color: #2e7d32;
            font-size: 16px;
        }
        .quote-details {
            background-color: #f9f9f9;
            border: 1px solid #e0e0e0;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f0f0;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .detail-label {
            font-weight: bold;
            color: #666;
            width: 40%;
            min-width: 140px;
        }
        .detail-value {
            color: #333;
            word-break: break-word;
        }
        .next-steps {
            background-color: #fff3e0;
            border-left: 4px solid #ff9800;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .next-steps strong {
            color: #e65100;
        }
        .next-steps ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin-bottom: 8px;
            color: #333;
        }
        .contact-section {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .contact-section strong {
            color: #1565c0;
        }
        .contact-info {
            margin-top: 10px;
        }
        .contact-info p {
            margin: 8px 0;
            color: #333;
        }
        .footer {
            background-color: #1a1a1a;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            border-top: 4px solid #c9a84c;
        }
        .footer-link {
            color: #c9a84c;
            text-decoration: none;
        }
        .accent-color {
            color: #c9a84c;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #c9a84c;
            color: #1a1a1a;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #b39441;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <img src="{{ url(($brand['logo'] ?? 'frontend/img/logo/hello_transport.svg')) }}" alt="Hello Transport" style="height:55px;max-width:220px;">
            </div>
            <h1>Quote Submission {{ $recipientType === 'customer' ? 'Confirmed' : 'Received' }}</h1>
            <div class="header-tagline">Professional Auto Transportation Services</div>
        </div>

        <!-- Content -->
        <div class="content">
            @if($recipientType === 'customer')
                <div class="greeting">
                    <strong>Thank You, {{ $order->oname ?? 'Valued Customer' }}!</strong>
                </div>

                <p>Thank you for submitting your quote request with Hello Transport. We've received your information and appreciate the opportunity to assist you with your auto transportation needs.</p>

                <div class="status-box">
                    <strong style="font-size: 16px;">✓ Quote Status: RECEIVED</strong>
                    <p style="margin-top: 8px; margin-bottom: 0;">Your request is being processed by our team.</p>
                </div>
            @else
                <div class="greeting">
                    <strong>New Quote Submission</strong>
                </div>

                <p>A new quote request has been submitted through the Hello Transport website.</p>

                <div class="status-box">
                    <strong>Order ID: #{{ $order->id }}</strong>
                </div>
            @endif

            <!-- Quote Details -->
            <div class="quote-details">
                <strong style="font-size: 16px; display: block; margin-bottom: 15px;">Quote Details:</strong>

                @if($order->oname)
                    <div class="detail-row">
                        <div class="detail-label">Customer Name:</div>
                        <div class="detail-value">{{ $order->oname }}</div>
                    </div>
                @endif

                @if($order->oemail)
                    <div class="detail-row">
                        <div class="detail-label">Email:</div>
                        <div class="detail-value">{{ $order->oemail }}</div>
                    </div>
                @endif

                @if($order->ophone)
                    <div class="detail-row">
                        <div class="detail-label">Phone:</div>
                        <div class="detail-value">{{ $order->ophone }}</div>
                    </div>
                @endif

                @if($order->originzsc)
                    <div class="detail-row">
                        <div class="detail-label">Pickup Location:</div>
                        <div class="detail-value">{{ $order->originzsc }}</div>
                    </div>
                @endif

                @if($order->destinationzsc)
                    <div class="detail-row">
                        <div class="detail-label">Delivery Location:</div>
                        <div class="detail-value">{{ $order->destinationzsc }}</div>
                    </div>
                @endif

                @if($order->ymk)
                    <div class="detail-row">
                        <div class="detail-label">Vehicle Details:</div>
                        <div class="detail-value">{{ $order->ymk }}</div>
                    </div>
                @endif

                @if($order->transport)
                    <div class="detail-row">
                        <div class="detail-label">Transport Type:</div>
                        <div class="detail-value">{{ ucfirst($order->transport) }}</div>
                    </div>
                @endif

                @if($order->shippingdate)
                    <div class="detail-row">
                        <div class="detail-label">Preferred Date:</div>
                        <div class="detail-value">{{ $order->shippingdate }}</div>
                    </div>
                @endif
            </div>

            @if($recipientType === 'customer')
                <!-- Next Steps -->
                <div class="next-steps">
                    <strong>What Happens Next?</strong>
                    <ul>
                        <li><strong>Review:</strong> Our team will review your request within 24 business hours</li>
                        <li><strong>Contact:</strong> We'll reach out with a detailed quote and answer any questions</li>
                        <li><strong>Booking:</strong> Once you approve the quote, we'll schedule your transport</li>
                        <li><strong>Tracking:</strong> You'll receive real-time updates on your shipment</li>
                    </ul>
                </div>

                <!-- Contact Section -->
                <div class="contact-section">
                    <strong>Need Immediate Assistance?</strong>
                    <div class="contact-info">
                        <p>✉️ <strong>Email:</strong> <a href="mailto:{{ $brand['contact_email'] ?? 'info@hellotransport.com' }}" style="color: #1565c0;">{{ $brand['contact_email'] ?? 'info@hellotransport.com' }}</a></p>
                        <p>🌐 <strong>Website:</strong> <a href="{{ $brand['site'] ?? 'https://www.hellotransport.com' }}" style="color: #1565c0;">www.hellotransport.com</a></p>
                    </div>
                </div>
            @else
                <!-- Company Contact Section -->
                <div class="contact-section">
                    <strong>Customer Contact Information:</strong>
                    <div class="contact-info">
                        <p>📧 <strong>Email:</strong> {{ $order->oemail ?? 'N/A' }}</p>
                        <p>📞 <strong>Phone:</strong> {{ $order->ophone ?? 'N/A' }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 10px 0;">
                <strong>Hello Transport</strong> - Professional Auto Transportation Services
            </p>
            <p style="margin: 0 0 10px 0;">
                <a href="{{ $brand['site'] ?? 'https://www.hellotransport.com' }}" class="footer-link">Visit Our Website</a> |
                <a href="mailto:{{ $brand['contact_email'] ?? 'info@hellotransport.com' }}" class="footer-link">Email Us</a>
            </p>
            <p style="margin: 0; color: #999;">
                © {{ date('Y') }} Hello Transport. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
