<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agent Portal Active — Hello Transport</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:32px 0;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08);">

      <!-- Header -->
      <tr>
        <td style="background:linear-gradient(135deg,#062e39 0%,#0d5c70 100%);padding:32px 40px;text-align:center;">
          <h1 style="margin:0;color:#fff;font-size:26px;font-weight:800;letter-spacing:1px;text-transform:uppercase;">
            🚛 Hello Transport
          </h1>
          <p style="margin:6px 0 0;color:rgba(255,255,255,.7);font-size:13px;">Agent Portal</p>
        </td>
      </tr>

      <!-- Green success banner -->
      <tr>
        <td style="background:#8fc445;padding:16px 40px;text-align:center;">
          <p style="margin:0;color:#fff;font-size:16px;font-weight:700;">
            ✅ Your Account is Now Active!
          </p>
        </td>
      </tr>

      <!-- Body -->
      <tr>
        <td style="padding:36px 40px;">
          <h2 style="margin:0 0 10px;color:#062e39;font-size:20px;font-weight:700;">
            Hi {{ $userName }},
          </h2>
          <p style="margin:0 0 20px;color:#555;font-size:14px;line-height:1.7;">
            Great news! Your <strong>Hello Transport Agent Portal</strong> account has been reviewed and activated by our admin team. You can now log in and start working.
          </p>

          <!-- Login Box -->
          <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
            <tr>
              <td style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:20px 24px;">
                <p style="margin:0 0 6px;color:#166534;font-size:13px;font-weight:600;">🔗 Login URL</p>
                <p style="margin:0 0 16px;color:#555;font-size:13px;">
                  <a href="https://helloagent.daydispatch.com/loginn" style="color:#062e39;word-break:break-all;">
                    https://helloagent.daydispatch.com/loginn
                  </a>
                </p>
                <p style="margin:0 0 4px;color:#166534;font-size:13px;font-weight:600;">📧 Your Email</p>
                <p style="margin:0;color:#555;font-size:13px;">{{ $userEmail }}</p>
              </td>
            </tr>
          </table>

          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center">
                <a href="https://helloagent.daydispatch.com/loginn"
                   style="display:inline-block;background:#8fc445;color:#fff;text-decoration:none;font-weight:700;font-size:14px;padding:13px 40px;border-radius:8px;letter-spacing:.5px;text-transform:uppercase;">
                  Login to Agent Portal →
                </a>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <!-- Divider -->
      <tr><td style="padding:0 40px;"><hr style="border:none;border-top:1px solid #e9ecef;margin:0;"></td></tr>

      <!-- Footer -->
      <tr>
        <td style="background:#f8f9fa;padding:20px 40px;text-align:center;border-top:1px solid #e9ecef;">
          <p style="margin:0 0 6px;color:#6c757d;font-size:12px;">
            If you did not register for a Hello Transport account, please ignore this email.
          </p>
          <p style="margin:0;color:#adb5bd;font-size:12px;">
            © {{ date('Y') }} Hello Transport. All Rights Reserved.<br>
            This email was sent to <strong>{{ $userEmail }}</strong>
          </p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>
</body>
</html>
