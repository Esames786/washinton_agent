@extends('emails.layouts.app')

@section('content')

    <div
        style="
          margin-top: 16px;
          font-family: Arial, sans-serif;
          line-height: 1.6;
          padding: 20px 0;
          color: #333;
        ">
        <p style="font-size: 18px; margin: 0">
            Dear <span style="font-weight: bold">{{ $order->oname }}</span>,
        </p>
        <p style="font-size: 18px">
            We hope this message finds you well! At ShipA1 Transport, we're
            committed to providing you with top-notch service at pocket-friendly
            prices. We understand the importance of cost-effective solutions
            without compromising on quality.
        </p>

        <h2
            style="
            text-align: center;
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
            margin: 0px;
            font-size: large;
            border-bottom: 5px solid #8fc445;
            background: #062e39;
            color: white;
            text-transform: uppercase;
            padding: 5px 18px;
          ">
            Your Transport number: <span>{{ $order->id }}</span>
        </h2>
        <ul
            style="
            list-style-type: none;
            padding: 10px;
            background: #062e39;
            color: white;
            border-radius: 10px;
            margin: 0;
          ">
            <li style="padding: 5px 0">Efficient and reliable transport.</li>
            <li style="padding: 5px 0">Competitive shipping rates.</li>
            <li style="padding: 5px 0">All-inclusive pricing.</li>
            <li style="padding: 5px 0">
                Complimentary loading of personal items up to 100lbs.
            </li>
            <li style="padding: 5px 0">Hassle-free experience.</li>
            <li style="padding: 5px 0">Real-time shipment tracking</li>
        </ul>

        <h2
            style="
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
            margin: 20px 0 10px;
            font-size: x-large;
            border-bottom: 5px solid #8fc445;
            background: #062e39;
            color: white;
            text-transform: uppercase;
            padding: 10px 20px;
          ">
            Your Transport Details
        </h2>
        <ul style="list-style-type: none; padding: 0;">
            <li style="margin: 5px 0;">
                Name: <span>{{ $order->oname }}</span>
            </li>
            <li style="margin: 5px 0;">
                Phone:
                <span>{{ substr_replace(str_repeat('x', strlen($order->ophone) - 3), substr($order->ophone, -3), -3) }}</span>
            </li>
            <li style="margin: 5px 0;">
                Your Transport number: <span>{{ $order->id }}</span>
            </li>

            @if ($order->car_type == 1)
                <?php
                $standardized = str_replace('*^-', '*^', $order->ymk);
                $ymk = explode('*^', $standardized);
                ?>
                @foreach ($ymk as $val2)
                    @if ($val2)
                        <li style="margin: 5px 0;">
                            <b>Vehicle:</b> <span>{{ $val2 }}</span> <br>
                        </li>
                    @endif

                    @if ($order->condition == '1')
                        <li style="margin: 5px 0;">
                            <b>Condition:</b> Running
                        </li>
                    @else
                        <li style="margin: 5px 0;">
                            <b>Condition:</b> Not Running
                        </li>
                    @endif
                @endforeach

                @if ($order->transport)
                    <li style="margin: 5px 0;">
                        <b>Trailer Type:</b> <span>{{ $order->transport == '1' ? 'Open' : 'Enclosed' }}</span>
                    </li>
                @endif
            @elseif ($order->car_type == 2)
                @if ($order->equip)
                    <li style="margin: 5px 0;">
                        <strong>EQUIP:</strong> <span>{{ $order->equip }}</span>
                    </li>
                @endif

                @if ($order->condition)
                    @if ($order->condition == '1')
                        <li style="margin: 5px 0;">
                            <b>Condition:</b> Running
                        </li>
                    @else
                        <li style="margin: 5px 0;">
                            <b>Condition:</b> Not Running
                        </li>
                    @endif
                @endif

                @if ($order->type)
                    <li style="margin: 5px 0;">
                        <strong>Trailer Type:</strong> <span>{{ $order->type }}</span>
                    </li>
                @endif
            @elseif ($order->car_type == 3 && $order->freight)
                @if ($order->freight->shipment_prefences)
                    <li style="margin: 5px 0;">
                        <strong>Shipment Preference:</strong> <span>{{ $order->freight->shipment_prefences }}</span>
                    </li>
                @endif

                @if ($order->freight->commodity_detail)
                    <li style="margin: 5px 0;">
                        <strong>Commodity Details:</strong> <span>{{ $order->freight->commodity_detail }}</span>
                    </li>
                @endif

                @if ($order->freight->commodity_unit)
                    <li style="margin: 5px 0;">
                        <strong>Commodity Unit:</strong> <span>{{ $order->freight->commodity_unit }}</span>
                    </li>
                @endif

                @if ($order->freight->handling_unit)
                    <li style="margin: 5px 0;">
                        <strong>Handling Unit:</strong> <span>{{ $order->freight->handling_unit }}</span>
                    </li>
                @endif

                @if ($order->freight->trailer_specification)
                    <li style="margin: 5px 0;">
                        <strong>Trailer Specification:</strong> <span>{{ $order->freight->trailer_specification }}</span>
                    </li>
                @endif
                <?php
                $standardizedeq = str_replace('*^-', '*^', $order->freight->equipment_type);
                $eqType = explode('*^', $standardizedeq);
                ?>
                @foreach ($eqType as $row)
                    @if ($row)
                        <li style="margin: 5px 0;">
                            <strong>Trailer Type:</strong> <span>{{ $row }}</span>
                        </li>
                    @endif
                @endforeach
            @endif

            @if ($order->originzsc)
                <li style="margin: 5px 0;">
                    <strong>Origin:</strong> <span>{{ $order->originzsc }}</span>
                </li>
            @endif

            @if ($order->destinationzsc)
                <li style="margin: 5px 0;">
                    <strong>Destination:</strong> <span>{{ $order->destinationzsc }}</span>
                </li>
            @endif

            <li
                style="
        font-size: 20px;
        margin: 20px 100px;
        text-align: center;
        font-weight: bold;
        color: #ffffff;
        background-color: #8fc445;
        border: 2px solid #062e39;
        border-radius: 5px;
        padding: 10px;
    ">
                <strong>Quote:</strong> <span>{{ $order->payment }}</span>
            </li>
        </ul>


        <h2
            style="
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
            margin: 20px 0 10px;
            font-size: x-large;
            border-bottom: 5px solid #8fc445;
            background: #062e39;
            color: white;
            text-transform: uppercase;
            padding: 10px 20px;
          ">
            Offer Details:
        </h2>
        <p style="font-size: 18px">
            Efficient and reliable vehicle transport service At
            <span style="font-weight: bold">$<span style="">{{ $order->payment }}</span></span>
        </p>
        <p style="font-size: 18px">
            Our price includes all tolls, taxes, insurance (<span style="font-weight: bold">$75,000 up to 1 million
                USD</span>) and door-to-door shipping
        </p>
        @if ($order->car_type == 3 && $order->freight)
            <p style="font-size: 18px">
                Pocket-friendly pricing to suit your budget without compromising on
                service quality.
            </p>
        @endif
        <p style="font-size: 18px">
            Complimentary loading of personal items weighing up to 100lbs,
            ensuring you can bring along your essentials hassle-free.
        </p>

        <h2
            style="
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
            margin: 20px 0 10px;
            font-size: x-large;
            border-bottom: 5px solid #8fc445;
            background: #062e39;
            color: white;
            text-transform: uppercase;
            padding: 10px 20px;
          ">
            Place Your Order
        </h2>

        <p style="font-size: 18px">
            We believe in making your transportation experience smooth and
            hassle-free. Our team is dedicated to ensuring your satisfaction every
            step of the way.
        </p>

        <div style="text-align: center; margin-top: 13px; margin-bottom: 13px">
            <a href="{{ $linkv }}" target="_blank">
                <button
                    style="
                background-color: #8fc445;
                color: white;
                font-size: larger;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
              ">
                    Order Now
                </button>
            </a>
        </div>
        <p style="font-size: 18px">
            Thank you for considering ShipA1 Transport for your transportation
            needs. We look forward to serving you soon!
        </p>
        <p style="font-size: 18px">
            Best Regards,<br />
            ShipA1 Transport
        </p>
        <p style="font-size: 18px">
            Visit Our Website:
            <a href="http://www.shipa1.com/" style="color: #8fc445">www.shipa1.com</a>
        </p>
    </div>
@endsection
