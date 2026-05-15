@extends('emails.layouts.app')

@section('content')
<div style="padding: 20px 0;">

  {{-- Header --}}
  <h2 style="border-top-right-radius:10px;border-top-left-radius:10px;margin:0;font-size:x-large;
             border-bottom:5px solid #8fc445;background:#062e39;color:white;
             text-transform:uppercase;padding:5px 18px;">
    Booking Confirmation — Order #{{ $autoorder->id }}
  </h2>

  <p>Dear {{ $autoorder->oname }},</p>
  <p>
    Thank you for placing your order. Because you confirmed your booking, all pricing tiers are now
    fully visible below — no more hidden prices.
  </p>

  {{-- Order Details --}}
  <h2 style="border-top-right-radius:10px;border-top-left-radius:10px;margin:16px 0 0;font-size:large;
             border-bottom:5px solid #8fc445;background:#062e39;color:white;
             text-transform:uppercase;padding:5px 18px;">
    Order Details
  </h2>
  <ul style="list-style-type:none;padding:0;margin:0 0 16px;">
    <li style="margin:5px 0;"><strong>ORDER #:</strong> {{ $autoorder->id }}</li>
    <li style="margin:5px 0;"><strong>Name:</strong> {{ $autoorder->oname }}</li>
    <li style="margin:5px 0;"><strong>Vehicle:</strong> {{ $autoorder->year }} {{ $autoorder->make }} {{ $autoorder->model }}</li>
    <li style="margin:5px 0;"><strong>Origin:</strong> {{ $autoorder->originzsc }}</li>
    <li style="margin:5px 0;"><strong>Destination:</strong> {{ $autoorder->destinationzsc }}</li>
    @if(!empty($autoorder->trailer_type))
    <li style="margin:5px 0;"><strong>Trailer Type:</strong> {{ $autoorder->trailer_type }}</li>
    @endif
    <li style="margin:5px 0;"><strong>Vehicle Condition:</strong> {{ $autoorder->condition == '1' ? 'Running' : 'Not Running' }}</li>
    <li style="margin:5px 0;"><strong>Booked Price:</strong> ${{ number_format($autoorder->payment, 2) }}</li>
  </ul>

  {{-- Pricing Table --}}
  <h2 style="border-top-right-radius:10px;border-top-left-radius:10px;margin:16px 0 0;font-size:large;
             border-bottom:5px solid #8fc445;background:#062e39;color:white;
             text-transform:uppercase;padding:5px 18px;">
    All Transport Pricing Options
  </h2>

  @if(!empty($pricingData['tiers']))

  {{-- Slab table --}}
  <table width="100%" cellpadding="8" cellspacing="0" border="0"
         style="border-collapse:collapse;margin:0 0 20px;font-size:13px;">
    <thead>
      <tr style="background:#062e39;color:white;">
        <th style="text-align:left;padding:10px 8px;border:1px solid #ddd;">Transport Type</th>
        @foreach($pricingData['tiers'] as $tier)
        <th style="text-align:center;padding:10px 8px;border:1px solid #ddd;">
          <span style="display:block;font-size:14px;font-weight:bold;">{{ $tier['name'] }}</span>
          <span style="display:block;font-size:11px;color:#8fc445;">{{ $tier['window'] }}</span>
          <span style="display:block;font-size:10px;background:#8fc445;color:white;
                       border-radius:3px;padding:1px 4px;margin-top:3px;display:inline-block;">
            {{ $tier['badge'] }}
          </span>
        </th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      <tr style="background:#f9f9f9;">
        <td style="padding:10px 8px;border:1px solid #ddd;font-weight:bold;">Open Transport</td>
        @foreach($pricingData['tiers'] as $tier)
        <td style="text-align:center;padding:10px 8px;border:1px solid #ddd;color:#062e39;font-weight:bold;">
          ${{ number_format($tier['open'], 2) }}
        </td>
        @endforeach
      </tr>
      <tr>
        <td style="padding:10px 8px;border:1px solid #ddd;font-weight:bold;">Enclosed Transport</td>
        @foreach($pricingData['tiers'] as $tier)
        <td style="text-align:center;padding:10px 8px;border:1px solid #ddd;color:#062e39;font-weight:bold;">
          ${{ number_format($tier['enclosed'], 2) }}
        </td>
        @endforeach
      </tr>
    </tbody>
  </table>

  {{-- Tier cards --}}
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:20px;">
    <tr>
      @foreach($pricingData['tiers'] as $tier)
      <td width="25%" style="padding:4px;vertical-align:top;">
        <div style="border:2px solid #8fc445;border-radius:8px;padding:12px;text-align:center;">
          <div style="background:#062e39;color:white;border-radius:5px;padding:6px 4px;margin-bottom:8px;">
            <strong style="font-size:14px;">{{ $tier['name'] }}</strong>
          </div>
          <div style="font-size:11px;color:#555;margin-bottom:4px;">{{ $tier['window'] }}</div>
          <div style="font-size:10px;background:#8fc445;color:white;border-radius:3px;
                      padding:2px 6px;display:inline-block;margin-bottom:8px;">
            {{ $tier['badge'] }}
          </div>
          <div style="font-size:12px;margin:4px 0;">
            <span style="color:#062e39;font-weight:bold;">Open:</span>
            ${{ number_format($tier['open'], 2) }}
          </div>
          <div style="font-size:12px;margin:4px 0;">
            <span style="color:#062e39;font-weight:bold;">Enclosed:</span>
            ${{ number_format($tier['enclosed'], 2) }}
          </div>
        </div>
      </td>
      @endforeach
    </tr>
  </table>

  @endif

  {{-- Includes --}}
  <h2 style="border-top-right-radius:10px;border-top-left-radius:10px;margin:0;font-size:large;
             border-bottom:5px solid #8fc445;background:#062e39;color:white;
             text-transform:uppercase;padding:5px 18px;">
    Your Transport Includes
  </h2>
  <ul style="list-style-type:none;padding:10px;background:#062e39;color:white;border-radius:10px;margin:0 0 16px;">
    <li style="padding:5px 0;">Efficient and reliable transport.</li>
    <li style="padding:5px 0;">Competitive shipping rates.</li>
    <li style="padding:5px 0;">All-inclusive pricing.</li>
    <li style="padding:5px 0;">Complimentary loading of personal items up to 100lbs.</li>
    <li style="padding:5px 0;">Hassle-free experience.</li>
    <li style="padding:5px 0;">Real-time shipment tracking.</li>
  </ul>

  <div style="text-align:center;margin-top:13px;">
    <a href="{{ route('order.tracking', $autoorder->id) }}" target="_blank">
      <button style="background-color:#8fc445;color:white;font-size:larger;border:none;
                     padding:10px 20px;border-radius:5px;">Track Order</button>
    </a>
  </div>

  <p style="margin-top:16px;">
    To view our terms and conditions:
    <a href="https://hellotransport.com/terms-conditions"
       style="color:#0056b3;text-decoration:none;">Terms and Conditions</a>
  </p>
  <p>For any inquiries, please don't hesitate to contact us.</p>
  <p>Thank you for choosing Hello Transport.<br/>Best Regards,<br/>Hello Transport</p>

</div>
@endsection
