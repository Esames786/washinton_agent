@extends('emails.layouts.app')

@section('content')

      <div style="padding: 20px 0;">
        <p style="margin: 0; font-size: 16px; line-height: 1.5; color: #333;">
          Dear {{ $autoorder->oname }},
        </p>
        <p>We hope this message finds you well.</p>
        <p>We're thrilled to inform you that the pickup for your 
        @if (!empty($autoorder->freight))
          <li style="font-size: 18px; margin: 5px 0">
            Commodity Details:
            <span>{{ $autoorder->freight->commodity_detail }}</span>
            <span>{{ $autoorder->freight->commodity_unit }}</span>
          </li>
          @else
          <li style="font-size: 18px; margin: 5px 0">
            Vehicle: <span>{{ $autoorder->ymk }}</span>
          </li>
        @endif
        with ShipA1 Transport has been successfully completed.</p>
        
        <h2 style="border-top-right-radius: 10px; border-top-left-radius: 10px; margin: 0; font-size: x-large; border-bottom: 5px solid #8fc445; background: #062e39; color: white; text-transform: uppercase; padding: 5px 18px;">Booking Details:</h2>
        <ul style="list-style-type: none; padding: 0;">
          <li style="margin: 5px 0;"><strong>ORDER #:</strong> {{ $autoorder->id }}</li>
          <li style="margin: 5px 0;"><strong>Name:</strong> {{ $autoorder->oname }}</li>
          <li style="margin: 5px 0;"><strong>Phone:</strong> {{ substr_replace(str_repeat('x', strlen($autoorder->ophone) - 3), substr($autoorder->ophone, -3), -3) }}</li>
          <li style="margin: 5px 0;"><strong>Vehicle:</strong> {{ $autoorder->year . ' ' . $autoorder->make . ' ' . $autoorder->model }}</li>
          <li style="margin: 5px 0;"><strong>Origin:</strong> {{ $autoorder->originzsc }}</li>
          <li style="margin: 5px 0;"><strong>Destination:</strong> {{ $autoorder->destinationzsc }}</li>
          <li style="margin: 5px 0;"><strong>Trailer Type:</strong> {{ $autoorder->trailer_type }}</li>
          <li style="margin: 5px 0;"><strong>Vehicle Condition:</strong> 
            @if($autoorder->condition == 1)
                Running
            @else
                Non Running
            @endif
            </li>
          <li style="margin: 5px 0;"><strong>BOOKING PRICE:</strong> {{ $autoorder->payment }}</li>
        </ul>

        <h2 style="border-top-right-radius: 10px; border-top-left-radius: 10px; margin: 0; font-size: x-large; border-bottom: 5px solid #8fc445; background: #062e39; color: white; text-transform: uppercase; padding: 5px 18px;">Pickup Details:</h2>
        <ul style="list-style-type: none; padding: 0;">
          <li style="margin: 5px 0;"><strong>Transport Number:</strong> {{ $autoorder->id }}</li>
          <li style="margin: 5px 0;"><strong>Pickup Date:</strong> {{ $autoorder->pickup_date }}</li>
          <li style="margin: 5px 0;"><strong>Pickup Location:</strong> {{ $autoorder->originzsc }}</li>
          <li style="margin: 5px 0;"><strong>Destination:</strong> {{ $autoorder->destinationzsc }}</li>
        </ul>

        <p>Your vehicle has been collected from the origin location and is now on its way to the destination.</p>
        <p>You can continue to track the status of your shipment on our website using your transport number.</p>
        <div style="text-align: center; margin-top: 13px; margin-bottom: 13px;">
            <a href="https://www.shipa1.com/order_tracking/" target="_blank">
              <button style="background-color: #8fc445; color: white; font-size: larger; border: none; padding: 10px 20px; border-radius: 5px;">Track Order</button>
            </a>
          </div>

        <h2 style="border-top-right-radius: 10px; border-top-left-radius: 10px; margin: 0; font-size: x-large; border-bottom: 5px solid #8fc445; background: #062e39; color: white; text-transform: uppercase; padding: 5px 18px;">Share Your Experience:</h2>
       
        <!-- <ul style="list-style-type: none; padding: 0;">
          <li style="margin: 5px 0;"><a href="#" style="color: #0056b3; text-decoration: none;">Google Reviews (profile link)</a></li>
          <li style="margin: 5px 0;"><a href="#" style="color: #0056b3; text-decoration: none;">Facebook Reviews (profile link)</a></li>
          <li style="margin: 5px 0;"><a href="#" style="color: #0056b3; text-decoration: none;">BBB Reviews (profile link)</a></li>
          <li style="margin: 5px 0;"><a href="#" style="color: #0056b3; text-decoration: none;">Trustpilot (profile link)</a></li>
        </ul> -->

        <p>Thank you for choosing ShipA1 Transport for your transportation needs. We are committed to providing you with reliable service and ensuring a smooth shipping experience from start to finish.</p>
        <p>Best Regards,<br>ShipA1 Transport</p>
        <p>For your convenience, here are our contact details:</p>
        <p>Phone: 1 (844) 474-4721</p>
        <p>Email: <a href="mailto:infos@shipa1.com" style="color: #0056b3; text-decoration: none;">infos@shipa1.com</a></p>
        <p>Hours of availability: 8am to 7pm EST.</p>
@endsection