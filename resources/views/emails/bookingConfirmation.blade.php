@extends('emails.layouts.app')

@section('content')

      <div style="padding: 20px 0;">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
          <thead>
            <tr>
              <th colspan="2" style="font-size: 20px; font-weight: bold; text-align: center;">Booking Confirmation Of Order #{{ $autoorder->id }}</th>
            </tr>
          </thead>
        </table>
        <p>Dear {{ $autoorder->oname }},</p>
        <p>We are delighted to confirm your booking with ShipA1 Transport! Your satisfaction is our priority, and we are dedicated to providing you with a seamless transportation experience.</p>
        <h2 style="border-top-right-radius: 10px; border-top-left-radius: 10px; margin: 0; font-size: x-large; border-bottom: 5px solid #8fc445; background: #062e39; color: white; text-transform: uppercase; padding: 5px 18px;">Booking Details:</h2>
        <ul style="list-style-type: none; padding: 0;">
          <li style="margin: 5px 0;"><strong>ORDER #:</strong> {{ $autoorder->id }}</li>
          <li style="margin: 5px 0;"><strong>Name:</strong> {{ $autoorder->oname }}</li>
          <li style="margin: 5px 0;"><strong>Phone:</strong> {{ substr_replace(str_repeat('x', strlen($autoorder->ophone) - 3), substr($autoorder->ophone, -3), -3) }}</li>
          <li style="margin: 5px 0;"><strong>Vehicle:</strong> {{ $autoorder->year . ' ' . $autoorder->make . ' ' . $autoorder->model }}</li>
          <li style="margin: 5px 0;"><strong>Origin:</strong> {{ $autoorder->originzsc }}</li>
          <li style="margin: 5px 0;"><strong>Destination:</strong> {{ $autoorder->destinationzsc }}</li>
          @if (isset($autoorder->trailer_type) && $autoorder->trailer_type != null)
          <li style="margin: 5px 0;"><strong>Trailer Type:</strong> {{ $autoorder->trailer_type }}</li>
          @endif
          <li style="margin: 5px 0;"><strong>Vehicle Condition:</strong> @if ($autoorder->condition == '1') Running @else Not Running @endif</li>
          <li style="margin: 5px 0;"><strong>BOOKING PRICE:</strong> {{ $autoorder->payment }}</li>
        </ul>
        <h2 style="border-top-right-radius: 10px; border-top-left-radius: 10px; margin: 0; font-size: x-large; border-bottom: 5px solid #8fc445; background: #062e39; color: white; text-transform: uppercase; padding: 5px 18px;">Your Transport Includes:</h2>
        <ul style="list-style-type: none; padding: 10px; background: #062e39; color: white; border-radius: 10px; margin: 0;">
          <li style="padding: 5px 0;">Efficient and reliable transport.</li>
          <li style="padding: 5px 0;">Competitive shipping rates.</li>
          <li style="padding: 5px 0;">All-inclusive pricing.</li>
          <li style="padding: 5px 0;">Complimentary loading of personal items up to 100lbs.</li>
          <li style="padding: 5px 0;">Hassle-free experience.</li>
          <li style="padding: 5px 0;">Real-time shipment tracking</li>
        </ul>
        <div style="text-align: center; margin-top: 13px;">
              <a href="{{ route('order.tracking', $autoorder->id) }}" target="_blank">
            <button style="background-color: #8fc445; color: white; font-size: larger; border: none; padding: 10px 20px; border-radius: 5px;">Track Order</button>
          </a>
        </div>
        <p>To view the terms and conditions of your booking, please follow this link:
          <a href="https://blog.shipa1.daydispatch.com/public/terms_and_conditions" style="color: #0056b3; text-decoration: none;">Terms and Conditions</a>
        </p>
        <p>For any inquiries or assistance, do not hesitate to contact us. We are here to support you throughout the process.</p>
        <p>Thank you for choosing ShipA1 Transport for your transportation needs. We look forward to serving you!</p>
        <p>Best Regards,<br />ShipA1 Transport</p>
@endsection
