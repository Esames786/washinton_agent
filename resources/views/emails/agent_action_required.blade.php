<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:'Segoe UI',Arial,sans-serif;">
@php
    $type      = in_array($type ?? null, ['nda', 'w9'], true) ? $type : 'contract';
    $brandName = $brand['name'] ?? 'Hello Transport';
    $loginUrl  = $brand['login_url'] ?? 'https://hellotransport.com/loginn';
    $copy = [
        'nda'      => ['Please Sign Your NDA', 'A Non-Disclosure Agreement (NDA) has been assigned to your account and is awaiting your signature.',
                       'read and sign it', 'Sign NDA', 'After logging in, the NDA will be shown automatically. You won\'t be able to proceed until it is signed.'],
        'w9'       => ['Please Complete Your W-9', 'An IRS Form W-9 has been assigned to you for US tax reporting.',
                       'fill in and sign it', 'Complete W-9', 'It takes a few minutes; your tax number is encrypted before it is stored.'],
        'contract' => ['Please Review Your Contract', 'Your employment contract has been prepared and is awaiting your review and acceptance.',
                       'review and accept it', 'Review Contract', 'After logging in, the contract will be shown automatically. You won\'t be able to proceed until it is accepted.'],
    ];
    [$title, $intro, $action, $button, $after] = $copy[$type];
@endphp
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:32px 0;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08);">
      <tr>
        <td style="background:linear-gradient(135deg,#062e39 0%,#0d5c70 100%);padding:30px 40px;text-align:center;">
          <h1 style="margin:0;color:#fff;font-size:24px;font-weight:800;letter-spacing:1px;text-transform:uppercase;">{{ $brandName }}</h1>
          <p style="margin:6px 0 0;color:rgba(255,255,255,.75);font-size:13px;">Agent Portal</p>
        </td>
      </tr>
      <tr>
        <td style="background:#f0ad4e;padding:14px 40px;text-align:center;">
          <p style="margin:0;color:#fff;font-size:16px;font-weight:700;">⚠️ {{ $title }}</p>
        </td>
      </tr>
      <tr>
        <td style="padding:34px 40px;">
          <h2 style="margin:0 0 10px;color:#062e39;font-size:19px;font-weight:700;">Hi {{ $userName }},</h2>
          <p style="margin:0 0 18px;color:#555;font-size:14px;line-height:1.7;">
            {{ $intro }} Please log in to your portal to {{ $action }} so you can continue working.
          </p>
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr><td align="center">
              <a href="{{ $loginUrl }}"
                 style="display:inline-block;background:#8fc445;color:#fff;text-decoration:none;font-weight:700;font-size:14px;padding:13px 40px;border-radius:8px;letter-spacing:.5px;text-transform:uppercase;">
                Log In &amp; {{ $button }} →
              </a>
            </td></tr>
          </table>
          <p style="margin:22px 0 0;color:#888;font-size:12.5px;line-height:1.6;">
            {{ $after }}
          </p>
        </td>
      </tr>
      <tr><td style="padding:0 40px;"><hr style="border:none;border-top:1px solid #e9ecef;margin:0;"></td></tr>
      <tr>
        <td style="background:#f8f9fa;padding:18px 40px;text-align:center;border-top:1px solid #e9ecef;">
          <p style="margin:0;color:#adb5bd;font-size:12px;">© {{ date('Y') }} {{ $brand['footer'] ?? ($brandName . '. All Rights Reserved.') }}</p>
        </td>
      </tr>
    </table>
  </td></tr>
</table>
</body>
</html>
