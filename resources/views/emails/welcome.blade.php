<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Welcome to Hello Transport</title>
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

      <!-- Body -->
      <tr>
        <td style="padding:36px 40px;">
          <h2 style="margin:0 0 8px;color:#062e39;font-size:20px;font-weight:700;">
            Welcome, {{ $userName }}! 👋
          </h2>
          <p style="margin:0 0 20px;color:#555;font-size:14px;line-height:1.7;">
            Thank you for registering with <strong>Hello Transport</strong>. Your account has been successfully created.
          </p>

          <!-- Status Box -->
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td style="background:#fff8e1;border-left:4px solid #f59e0b;border-radius:6px;padding:16px 20px;">
                <p style="margin:0;color:#92400e;font-size:14px;font-weight:600;">
                  ⏳ Account Pending Admin Approval
                </p>
                <p style="margin:6px 0 0;color:#78350f;font-size:13px;line-height:1.6;">
                  Your account is currently under review. An admin will verify your profile and activate your account shortly.
                  <strong>You will receive a notification once your account is approved and ready to use.</strong>
                </p>
              </td>
            </tr>
          </table>

          <p style="margin:24px 0 8px;color:#555;font-size:14px;line-height:1.7;">
            While you wait, our team may contact you to complete your HR profile — including uploading required documents such as your CNIC, educational certificates, and experience letters.
          </p>

          <p style="margin:0 0 24px;color:#555;font-size:14px;line-height:1.7;">
            Once approved, you can log in at:
          </p>

          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center">
                <a href="https://helloagent.daydispatch.com/loginn"
                   style="display:inline-block;background:#8fc445;color:#fff;text-decoration:none;font-weight:700;font-size:14px;padding:12px 32px;border-radius:8px;letter-spacing:.5px;text-transform:uppercase;">
                  Go to Login →
                </a>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <!-- Divider -->
      <tr>
        <td style="padding:0 40px;">
          <hr style="border:none;border-top:1px solid #e9ecef;margin:0;">
        </td>
      </tr>

      <!-- What's next -->
      <tr>
        <td style="padding:24px 40px;">
          <p style="margin:0 0 12px;color:#062e39;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">
            What happens next?
          </p>
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td style="padding:6px 0;color:#555;font-size:13px;">
                <span style="display:inline-block;width:22px;height:22px;background:#8fc445;color:#fff;border-radius:50%;text-align:center;line-height:22px;font-size:11px;font-weight:700;margin-right:8px;vertical-align:middle;">1</span>
                Admin reviews your profile &amp; documents
              </td>
            </tr>
            <tr>
              <td style="padding:6px 0;color:#555;font-size:13px;">
                <span style="display:inline-block;width:22px;height:22px;background:#8fc445;color:#fff;border-radius:50%;text-align:center;line-height:22px;font-size:11px;font-weight:700;margin-right:8px;vertical-align:middle;">2</span>
                You receive an activation confirmation email
              </td>
            </tr>
            <tr>
              <td style="padding:6px 0;color:#555;font-size:13px;">
                <span style="display:inline-block;width:22px;height:22px;background:#8fc445;color:#fff;border-radius:50%;text-align:center;line-height:22px;font-size:11px;font-weight:700;margin-right:8px;vertical-align:middle;">3</span>
                Log in and start working on the Hello Transport portal
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <!-- Footer -->
      <tr>
        <td style="background:#f8f9fa;padding:20px 40px;text-align:center;border-top:1px solid #e9ecef;">
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
