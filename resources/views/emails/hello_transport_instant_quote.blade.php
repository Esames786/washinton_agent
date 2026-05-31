@php
    $money = function($n){
        if ($n === null || $n === '' || !is_numeric($n)) return '0';
        return number_format((float)$n, 0);
    };

    $platformCode = $platformCode ?? 'hello-autohaul';
    $isAutohaul   = $isAutohaul   ?? true;

    $brandMap = [
        'hello-autohaul' => [
            'name'      => 'Hello Transport',
            'short'     => 'Hello Transport',
            'site'      => 'https://www.hellotransport.com',
            'email'     => 'support@hellotransport.com',
            'phone'     => '1 (844) 474-4721',
            'insurance' => '$75K',
            'logo'      => url('frontend/img/logo/hello_transport.svg'),
        ],
    ];

    $brand        = $brandMap[$platformCode] ?? $brandMap['hello-autohaul'];
    $emailAddress = $brand['email'];
    $phoneDisplay = $brand['phone'];
    $insuranceAmt = $brand['insurance'];
    $brandName    = $brand['name'];
    $brandShort   = $brand['short'];
    $brandSite    = $brand['site'];

    $order_id       = base64_encode($q->order_id);
    $order_taker_id = base64_encode($q->order_taker_id);
    $wash_link      = url('/email_order') . '/' . $order_id . '/' . $order_taker_id;

    $customerName  = $q->customer_name ?? 'Customer';
    $customerPhone = $q->customer_phone ?? '-';
    $transportNumber = $q->id ? str_pad((string)$q->id, 6, '0', STR_PAD_LEFT) : '000000';
    $vehicle     = $q->year_make_model ?? '-';
    $condition   = $q->condition ?? 'Running';
    $origin      = $q->origin_location ?? '-';
    $destination = $q->destination_location ?? '-';
    $trailerType = $q->transport ?? 'Open';

    $logo   = $brand['logo'];
    $google = url('images/google-re.png');
    $bbb    = url('images/bbb-re.jpeg');
    $trust  = url('images/trust-pilot-re.png');

    // ── Slab pricing ──────────────────────────────────────────────────────────
    $tierNames   = ['Standard', 'Express', 'Premium'];
    $tierDays    = ['No ETA', 'ETA Available', '1 to 3 days'];
    $tierDescs   = [
        'Standard delivery at the best available rate.',
        'Faster pickup with a priority dispatch window.',
        'Expedited handling — your vehicle moves first.',
    ];
    $tierSavings = ['Best Savings', 'Good Savings', 'Low Savings'];

    $openSlabs = data_get($pricing, 'primary.modes.open.offer_prices', []);
    $encSlabs  = data_get($pricing, 'primary.modes.enclosed.offer_prices', []);
    $slabCount = max(count($openSlabs), count($encSlabs));
    $isSlabMode = $slabCount > 0;

    $tiers = [];
    for ($i = 0; $i < $slabCount; $i++) {
        $openVal = $openSlabs[$i]['value'] ?? null;
        $encVal  = $encSlabs[$i]['value']  ?? null;
        $openToken = base64_encode($openVal . '|open');
        $encToken  = base64_encode($encVal  . '|enclosed');
        $tiers[] = [
            'name'          => $tierNames[$i] ?? 'Package ' . ($i + 1),
            'days'          => $tierDays[$i]  ?? '',
            'desc'          => $tierDescs[$i] ?? '',
            'savings'       => $tierSavings[$i] ?? '',
            'open'          => $openVal,
            'enclosed'      => $encVal,
            'open_link'     => $wash_link . '/' . $openToken,
            'enclosed_link' => $wash_link . '/' . $encToken,
        ];
    }

    $openDriverPrice = $q->offer_open;
    $encDriverPrice  = $q->offer_enclosed;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Your {{ $brandShort }} Transport Quote</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:'DM Sans',Arial,sans-serif">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f4f6f8;padding:24px 0">
<tr><td align="center">
<table width="620" cellpadding="0" cellspacing="0" border="0" style="max-width:620px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(6,46,57,0.10)">

  {{-- ── HEADER ── --}}
  <tr>
    <td style="background:#062e39;padding:20px 32px">
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td style="vertical-align:middle">
            <img src="{{ $logo }}" alt="{{ $brandName }}" style="max-width:180px;height:auto;display:block">
          </td>
          <td align="right" style="vertical-align:middle">
            <a href="tel:{{ $phoneDisplay }}" style="display:inline-block;background:#079460;color:#ffffff;font-family:'DM Sans',Arial,sans-serif;font-size:14px;font-weight:700;padding:10px 18px;border-radius:8px;text-decoration:none">
              📞 {{ $phoneDisplay }}
            </a>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  {{-- ── TRANSPORT NUMBER BANNER ── --}}
  <tr>
    <td style="background:#079460;padding:12px 32px;text-align:center">
      <span style="font-family:'DM Sans',Arial,sans-serif;font-size:13px;font-weight:700;color:#ffffff;letter-spacing:1px;text-transform:uppercase">
        Transport Number: #{{ $transportNumber }}
      </span>
    </td>
  </tr>

  {{-- ── GREETING ── --}}
  <tr>
    <td style="padding:32px 32px 0">
      <p style="margin:0 0 8px;font-family:'DM Sans',Arial,sans-serif;font-size:22px;font-weight:700;color:#062e39">
        Dear {{ $customerName }},
      </p>
      @if($isAutohaul)
      <p style="margin:0 0 10px;font-family:'Poppins',Arial,sans-serif;font-size:14px;color:#079460;font-weight:600;line-height:1.6">
        🤝 We'd like to extend a special thank you to <strong>AutoHaulingQuotes.com</strong> for connecting <strong>{{ $customerName }}</strong> with us at <strong>{{ $brandName }}</strong>.
      </p>
      @endif
      <p style="margin:0;font-family:'Poppins',Arial,sans-serif;font-size:15px;color:#4a5568;line-height:1.7">
        Thank you for choosing <strong>{{ $brandName }}</strong>. We've prepared your personalised quote below — competitive rates, door-to-door service, and all-inclusive pricing with no hidden fees.
      </p>
    </td>
  </tr>

  {{-- ── FEATURES STRIP ── --}}
  <tr>
    <td style="padding:20px 32px">
      <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f0faf4;border-radius:10px;border-left:4px solid #079460">
        <tr>
          <td style="padding:16px 20px">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td style="font-family:'Poppins',Arial,sans-serif;font-size:13px;color:#062e39;padding:3px 0">✅ &nbsp;All-inclusive pricing — tolls, taxes &amp; insurance up to {{ $insuranceAmt }} USD</td>
              </tr>
              <tr>
                <td style="font-family:'Poppins',Arial,sans-serif;font-size:13px;color:#062e39;padding:3px 0">✅ &nbsp;Door-to-door shipping nationwide</td>
              </tr>
              <tr>
                <td style="font-family:'Poppins',Arial,sans-serif;font-size:13px;color:#062e39;padding:3px 0">✅ &nbsp;Complimentary personal items up to 100 lbs</td>
              </tr>
              <tr>
                <td style="font-family:'Poppins',Arial,sans-serif;font-size:13px;color:#062e39;padding:3px 0">✅ &nbsp;Real-time shipment tracking</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  {{-- ── ORDER DETAILS ── --}}
  <tr>
    <td style="padding:0 32px 20px">
      <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-radius:10px;overflow:hidden;border:1px solid #e2e8f0">
        <tr>
          <td colspan="2" style="background:#062e39;padding:12px 20px">
            <span style="font-family:'DM Sans',Arial,sans-serif;font-size:14px;font-weight:700;color:#ffffff;text-transform:uppercase;letter-spacing:1px">Your Transport Details</span>
          </td>
        </tr>
        @foreach([
            ['Name', $customerName],
            ['Phone', $customerPhone],
            ['Vehicle', $vehicle],
            ['Condition', $condition],
            ['Trailer Type', $trailerType],
            ['Origin', $origin],
            ['Destination', $destination],
        ] as $idx => $row)
        <tr style="background:{{ $idx % 2 === 0 ? '#ffffff' : '#f8fafc' }}">
          <td style="padding:10px 20px;font-family:'Poppins',Arial,sans-serif;font-size:13px;font-weight:600;color:#062e39;width:38%;border-bottom:1px solid #e2e8f0">{{ $row[0] }}</td>
          <td style="padding:10px 20px;font-family:'Poppins',Arial,sans-serif;font-size:13px;color:#4a5568;border-bottom:1px solid #e2e8f0">{{ $row[1] }}</td>
        </tr>
        @endforeach
      </table>
    </td>
  </tr>

  {{-- ── PRICING ── --}}
  <tr>
    <td style="padding:0 32px 24px">
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td style="background:#062e39;padding:12px 20px;border-radius:10px 10px 0 0">
            <span style="font-family:'DM Sans',Arial,sans-serif;font-size:14px;font-weight:700;color:#ffffff;text-transform:uppercase;letter-spacing:1px">Your Price Options</span>
          </td>
        </tr>
        <tr>
          <td style="background:#f8fafc;padding:0;border:1px solid #e2e8f0;border-top:none;border-radius:0 0 10px 10px">

            @if($isSlabMode)
            {{-- Slab pricing table --}}
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              {{-- Column headers --}}
              <tr>
                <td style="padding:12px 16px;font-family:'DM Sans',Arial,sans-serif;font-size:12px;font-weight:700;color:#718096;text-transform:uppercase;letter-spacing:1px;width:30%;border-bottom:2px solid #e2e8f0">Type</td>
                @foreach($tiers as $i => $tier)
                <td style="padding:12px 10px;text-align:center;border-left:1px solid #e2e8f0;border-bottom:2px solid #e2e8f0;background:{{ $i === 0 ? '#f0faf4' : '#ffffff' }}">
                  <div style="font-family:'DM Sans',Arial,sans-serif;font-size:14px;font-weight:700;color:#062e39">{{ $tier['name'] }}</div>
                  @if($tier['savings'])
                  <div style="display:inline-block;background:{{ $i === 0 ? '#079460' : '#718096' }};color:#fff;font-family:'Poppins',Arial,sans-serif;font-size:10px;font-weight:600;padding:2px 8px;border-radius:20px;margin:4px 0">{{ $tier['savings'] }}</div>
                  @endif
                  <div style="font-family:'Poppins',Arial,sans-serif;font-size:11px;color:#718096;margin-top:2px">{{ $tier['days'] }}</div>
                </td>
                @endforeach
              </tr>
              {{-- Open transport row --}}
              <tr>
                <td style="padding:14px 16px;font-family:'Poppins',Arial,sans-serif;font-size:13px;font-weight:600;color:#062e39;border-bottom:1px solid #e2e8f0">
                  🚗 Open
                </td>
                @foreach($tiers as $i => $tier)
                <td style="padding:14px 10px;text-align:center;border-left:1px solid #e2e8f0;border-bottom:1px solid #e2e8f0;background:{{ $i === 0 ? '#f0faf4' : '#ffffff' }}">
                  <div style="font-family:'DM Sans',Arial,sans-serif;font-size:20px;font-weight:700;color:#062e39">${{ $money($tier['open']) }}</div>
                  <a href="{{ $tier['open_link'] }}" target="_blank" style="display:inline-block;margin-top:6px;background:#079460;color:#ffffff;font-family:'Poppins',Arial,sans-serif;font-size:11px;font-weight:600;padding:5px 12px;border-radius:6px;text-decoration:none">Place Order</a>
                </td>
                @endforeach
              </tr>
              {{-- Enclosed transport row --}}
              <tr>
                <td style="padding:14px 16px;font-family:'Poppins',Arial,sans-serif;font-size:13px;font-weight:600;color:#062e39">
                  🔒 Enclosed
                </td>
                @foreach($tiers as $i => $tier)
                <td style="padding:14px 10px;text-align:center;border-left:1px solid #e2e8f0;background:{{ $i === 0 ? '#f0faf4' : '#ffffff' }}">
                  <div style="font-family:'DM Sans',Arial,sans-serif;font-size:20px;font-weight:700;color:#062e39">${{ $money($tier['enclosed']) }}</div>
                  <a href="{{ $tier['enclosed_link'] }}" target="_blank" style="display:inline-block;margin-top:6px;background:#062e39;color:#ffffff;font-family:'Poppins',Arial,sans-serif;font-size:11px;font-weight:600;padding:5px 12px;border-radius:6px;text-decoration:none">Place Order</a>
                </td>
                @endforeach
              </tr>
            </table>

            @else
            {{-- Legacy fallback --}}
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td style="padding:16px 20px;border-bottom:1px solid #e2e8f0">
                  <span style="font-family:'Poppins',Arial,sans-serif;font-size:13px;font-weight:600;color:#062e39">🚗 Open Transport</span>
                  <span style="float:right;font-family:'DM Sans',Arial,sans-serif;font-size:20px;font-weight:700;color:#079460">${{ $money($openDriverPrice) }}</span>
                </td>
              </tr>
              <tr>
                <td style="padding:16px 20px">
                  <span style="font-family:'Poppins',Arial,sans-serif;font-size:13px;font-weight:600;color:#062e39">🔒 Enclosed Transport</span>
                  <span style="float:right;font-family:'DM Sans',Arial,sans-serif;font-size:20px;font-weight:700;color:#079460">${{ $money($encDriverPrice) }}</span>
                </td>
              </tr>
            </table>
            @endif

          </td>
        </tr>
      </table>
    </td>
  </tr>

  {{-- ── OFFER DETAILS (slab cards) ── --}}
  @if($isSlabMode)
  <tr>
    <td style="padding:0 32px 24px">
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td style="background:#062e39;padding:12px 20px;border-radius:10px 10px 0 0">
            <span style="font-family:'DM Sans',Arial,sans-serif;font-size:14px;font-weight:700;color:#ffffff;text-transform:uppercase;letter-spacing:1px">Package Details</span>
          </td>
        </tr>
        @foreach($tiers as $i => $tier)
        <tr>
          <td style="padding:14px 20px;border:1px solid #e2e8f0;border-top:none;background:{{ $i === 0 ? '#f0faf4' : '#ffffff' }};{{ $i === count($tiers)-1 ? 'border-radius:0 0 10px 10px' : '' }}">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>
                  <span style="font-family:'DM Sans',Arial,sans-serif;font-size:15px;font-weight:700;color:#062e39">{{ $tier['name'] }}</span>
                  &nbsp;
                  <span style="display:inline-block;background:{{ $i === 0 ? '#079460' : '#718096' }};color:#fff;font-family:'Poppins',Arial,sans-serif;font-size:10px;font-weight:600;padding:2px 8px;border-radius:20px;vertical-align:middle">{{ $tier['savings'] }}</span>
                  <span style="font-family:'Poppins',Arial,sans-serif;font-size:12px;color:#718096;margin-left:8px">{{ $tier['days'] }}</span>
                </td>
              </tr>
              <tr>
                <td style="padding-top:4px">
                  <span style="font-family:'Poppins',Arial,sans-serif;font-size:12px;color:#718096">{{ $tier['desc'] }}</span>
                </td>
              </tr>
              <tr>
                <td style="padding-top:10px">
                  <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="padding-right:8px">
                        <span style="font-family:'DM Sans',Arial,sans-serif;font-size:16px;font-weight:700;color:#062e39">Open: ${{ $money($tier['open']) }}</span>
                      </td>
                      <td style="padding-right:16px">
                        <a href="{{ $tier['open_link'] }}" target="_blank" style="display:inline-block;background:#079460;color:#fff;font-family:'Poppins',Arial,sans-serif;font-size:11px;font-weight:600;padding:5px 12px;border-radius:6px;text-decoration:none">Book Open</a>
                      </td>
                      <td style="padding-right:8px">
                        <span style="font-family:'DM Sans',Arial,sans-serif;font-size:16px;font-weight:700;color:#062e39">Enclosed: ${{ $money($tier['enclosed']) }}</span>
                      </td>
                      <td>
                        <a href="{{ $tier['enclosed_link'] }}" target="_blank" style="display:inline-block;background:#062e39;color:#fff;font-family:'Poppins',Arial,sans-serif;font-size:11px;font-weight:600;padding:5px 12px;border-radius:6px;text-decoration:none">Book Enclosed</a>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        @endforeach
      </table>
    </td>
  </tr>
  @endif

  {{-- ── CTA BUTTONS ── --}}
  <tr>
    <td style="padding:0 32px 32px;text-align:center">
      <table align="center" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td style="padding:0 6px">
            <a href="{{ $wash_link }}" target="_blank" style="display:inline-block;background:#079460;color:#ffffff;font-family:'DM Sans',Arial,sans-serif;font-size:15px;font-weight:700;padding:13px 24px;border-radius:8px;text-decoration:none">Place Order</a>
          </td>
          <td style="padding:0 6px">
            <a href="mailto:{{ $emailAddress }}" style="display:inline-block;background:#062e39;color:#ffffff;font-family:'DM Sans',Arial,sans-serif;font-size:15px;font-weight:700;padding:13px 24px;border-radius:8px;text-decoration:none">Email Us</a>
          </td>
          <td style="padding:0 6px">
            <a href="tel:{{ $phoneDisplay }}" style="display:inline-block;background:#8fc445;color:#ffffff;font-family:'DM Sans',Arial,sans-serif;font-size:15px;font-weight:700;padding:13px 24px;border-radius:8px;text-decoration:none">Call Us</a>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  {{-- ── CLOSING ── --}}
  <tr>
    <td style="padding:0 32px 24px">
      <p style="margin:0;font-family:'Poppins',Arial,sans-serif;font-size:14px;color:#4a5568;line-height:1.7">
        Thank you for considering {{ $brandName }}. We look forward to serving you!<br><br>
        <strong style="color:#062e39">Best Regards,<br>{{ $brandName }} Team</strong><br>
        <a href="{{ $brandSite }}" style="color:#079460;text-decoration:none">{{ $brandSite }}</a>
      </p>
    </td>
  </tr>

  {{-- ── REVIEW BADGES ── --}}
  <tr>
    <td style="padding:16px 32px;border-top:1px solid #e2e8f0;text-align:center">
      <p style="margin:0 0 12px;font-family:'Poppins',Arial,sans-serif;font-size:12px;color:#718096">Find us on</p>
      <a href="https://www.google.com/search?q=Hello+Transport" target="_blank" style="margin:0 8px">
        <img src="{{ $google }}" alt="Google Reviews" style="width:70px;height:30px;object-fit:contain">
      </a>
      <a href="https://www.bbb.org" target="_blank" style="margin:0 8px">
        <img src="{{ $bbb }}" alt="BBB Reviews" style="width:70px;height:30px;object-fit:contain">
      </a>
      <a href="https://www.trustpilot.com" target="_blank" style="margin:0 8px">
        <img src="{{ $trust }}" alt="Trustpilot Reviews" style="width:70px;height:30px;object-fit:contain">
      </a>
    </td>
  </tr>

  {{-- ── FOOTER ── --}}
  <tr>
    <td style="background:#062e39;padding:16px 32px;border-radius:0 0 12px 12px;text-align:center">
      <p style="margin:0;font-family:'Poppins',Arial,sans-serif;font-size:11px;color:#a0aec0">
        © {{ date('Y') }} {{ $brandName }}. All rights reserved.<br>
        <a href="{{ $brandSite }}/privacy-policy" style="color:#8fc445;text-decoration:none">Privacy Policy</a>
        &nbsp;·&nbsp;
        <a href="{{ $brandSite }}/terms-and-conditions" style="color:#8fc445;text-decoration:none">Terms &amp; Conditions</a>
      </p>
    </td>
  </tr>

</table>
</td></tr>
</table>

</body>
</html>
