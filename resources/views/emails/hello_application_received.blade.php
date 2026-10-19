<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Application received</title>
</head>
<body style="margin:0;padding:0;background:#f4f6fb;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6fb;padding:32px 12px;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e3e8ef;">

      <!-- Header -->
      <tr>
        <td style="background:#0b1f3a;padding:28px 40px 24px;text-align:center;border-bottom:4px solid #c9a13b;">
          <img src="{{ url('frontend/img/logo/hello_transport.png') }}" alt="Hello Transport" width="72" height="72" style="display:block;margin:0 auto 12px;width:72px;height:72px;">
          <p style="margin:0;color:#c9a13b;font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase;">Agent Program</p>
        </td>
      </tr>

      <!-- Body -->
      <tr>
        <td style="padding:34px 40px 10px;">
          <h1 style="margin:0 0 14px;color:#0b1f3a;font-size:22px;font-weight:800;">Application received</h1>
          <p style="margin:0 0 14px;color:#4a5568;font-size:15px;line-height:1.7;">Hi {{ $applicantName }},</p>
          <p style="margin:0 0 22px;color:#4a5568;font-size:15px;line-height:1.7;">
            Thank you for applying to join our team. Your application has been successfully received and is now
            under review. Our recruitment team will review your application and contact you if your profile matches
            an available opportunity.
          </p>

          <!-- What happens next -->
          <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6fb;border-radius:10px;border:1px solid #e3e8ef;">
            <tr><td style="padding:20px 22px 8px;">
              <p style="margin:0 0 12px;color:#0b1f3a;font-size:13px;font-weight:700;letter-spacing:1px;text-transform:uppercase;">What happens next</p>
            </td></tr>
            <tr><td style="padding:0 22px 6px;">
              <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                  <td width="34" valign="top" style="padding:0 0 14px;"><div style="width:24px;height:24px;line-height:24px;border-radius:50%;background:#0b1f3a;color:#c9a13b;font-size:12px;font-weight:700;text-align:center;">1</div></td>
                  <td valign="top" style="padding:2px 0 14px;color:#4a5568;font-size:14px;line-height:1.55;"><strong style="color:#0b1f3a;">Review.</strong> Our recruitment team reviews your application.</td>
                </tr>
                <tr>
                  <td width="34" valign="top" style="padding:0 0 14px;"><div style="width:24px;height:24px;line-height:24px;border-radius:50%;background:#0b1f3a;color:#c9a13b;font-size:12px;font-weight:700;text-align:center;">2</div></td>
                  <td valign="top" style="padding:2px 0 14px;color:#4a5568;font-size:14px;line-height:1.55;"><strong style="color:#0b1f3a;">Shortlisting.</strong> If your profile is a match, we email you a link to set your password and sign in to our HR portal.</td>
                </tr>
                <tr>
                  <td width="34" valign="top" style="padding:0 0 14px;"><div style="width:24px;height:24px;line-height:24px;border-radius:50%;background:#0b1f3a;color:#c9a13b;font-size:12px;font-weight:700;text-align:center;">3</div></td>
                  <td valign="top" style="padding:2px 0 14px;color:#4a5568;font-size:14px;line-height:1.55;"><strong style="color:#0b1f3a;">Onboarding.</strong> You complete each step there (agreement, questions, documents), and we email you at every stage.</td>
                </tr>
              </table>
            </td></tr>
          </table>

          <p style="margin:24px 0 6px;color:#4a5568;font-size:14px;line-height:1.7;">
            You do not need to do anything right now.
          </p>
          <p style="margin:0 0 30px;color:#4a5568;font-size:14px;line-height:1.7;">
            <strong style="color:#0b1f3a;">Questions?</strong> For recruitment-related questions, contact
            <a href="mailto:info@hellotransport.com" style="color:#1d4174;font-weight:600;text-decoration:none;">info@hellotransport.com</a>.
          </p>
        </td>
      </tr>

      <!-- Footer -->
      <tr>
        <td style="background:#f4f6fb;border-top:1px solid #e3e8ef;padding:18px 40px;text-align:center;">
          <p style="margin:0;color:#718096;font-size:12px;line-height:1.6;">
            &copy; {{ date('Y') }} Hello Transport. All rights reserved.<br>
            You are receiving this email because you applied at hellotransport.com.
          </p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>
</body>
</html>
