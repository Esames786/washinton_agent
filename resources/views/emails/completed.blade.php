@extends('emails.layouts.app')

@section('content')
    <div class="content">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th colspan="2"
                        style="font-size: 20px; font-weight: bold; text-align: center; padding: 10px; background-color: #062e39; color: #fff; border-bottom: 5px solid #8fc445;">
                        Delivery Confirmation Of Order #{{ $autoorder->id }}
                    </th>
                </tr>
            </thead>
        </table>

        <p>Dear {{ $autoorder->oname }},</p>
        <p>
            We hope this email finds you well. We're delighted to announce that the delivery of your shipment with
            ShipA1 Transport has been successfully completed!
        </p>

        <h2
            style="border-top-right-radius: 10px; border-top-left-radius: 10px; margin: 0; font-size: x-large; border-bottom: 5px solid #8fc445; background: #062e39; color: white; text-transform: uppercase; padding: 5px 18px;">
            Successful Delivery Details:
        </h2>
        <ul style="list-style-type: none; padding: 0;">
            <li style="margin: 5px 0;"><strong>Vehicle:</strong> {{ $autoorder->ymk }}</li>
            <li style="margin: 5px 0;"><strong>Delivery Date:</strong> {{ $autoorder->delivery_date }}</li>
            <li style="margin: 5px 0;"><strong>Delivery Location:</strong> {{ $autoorder->destinationzsc }}</li>
        </ul>

        <h2
            style="border-top-right-radius: 10px; border-top-left-radius: 10px; margin: 0; font-size: x-large; border-bottom: 5px solid #8fc445; background: #062e39; color: white; text-transform: uppercase; padding: 5px 18px;">
            Booking Details:
        </h2>
        <ul style="list-style-type: none; padding: 0;">
            <li style="margin: 5px 0;"><strong>ORDER #:</strong> {{ $autoorder->id }}</li>
            <li style="margin: 5px 0;"><strong>Name:</strong> {{ $autoorder->oname }}</li>
            <li style="margin: 5px 0;"><strong>Phone:</strong> {{ substr_replace(str_repeat('x',
                strlen($autoorder->ophone) - 3), substr($autoorder->ophone, -3), -3) }}</li>
            <li style="margin: 5px 0;"><strong>Vehicle:</strong> {{ $autoorder->year . ' ' . $autoorder->make . ' ' .
                $autoorder->model }}</li>
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

        <p>
            Your vehicle has been safely delivered to the destination address.
            We trust that our services have met your expectations, and we thank you for choosing ShipA1 Transport for
            your transportation needs.
            if you have any questions or require assistance with future shipments, please feel free to reach out to
            us. We're here to assist you.
        </p>
        <p>
            We also invite you to book another shipment with us at your convenience and get a discount of $50. You can
            get an instant quote by visiting our website or calling us at <a href=" 1 (844) 474-4721"
                style="color: #0056b3; text-decoration: none;"> 1 (844) 474-4721</a>.
        </p>
        <p>
            Thank you once again for entrusting ShipA1 Transport with your shipment. We look forward to serving you
            again in the future.
        </p>
        <p>Best Regards,<br />ShipA1 Transport</p>
        <p>
            For your convenience, here are our contact details:<br />
            Phone: <a href=" 1 (844) 474-4721" style="color: #0056b3; text-decoration: none;"> 1 (844)
                474-4721</a><br />
            Email: <a href="mailto:infos@shipa1.com"
                style="color: #0056b3; text-decoration: none;">infos@shipa1.com</a><br />
            Hours of availability: 8am to 7pm EST.
        </p>
@endsection
