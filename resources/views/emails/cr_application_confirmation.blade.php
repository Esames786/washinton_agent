<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:Arial,sans-serif;background:#f5f5f5;margin:0;padding:20px;}
.wrap{max-width:600px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1);}
.header{background:#111;padding:28px 32px;text-align:center;}
.header h1{color:#d4af37;margin:0;font-size:24px;}
.header p{color:#aaa;margin:6px 0 0;font-size:13px;}
.body{padding:32px;}
.body p{color:#444;font-size:14px;line-height:1.7;margin-bottom:14px;}
.info-box{background:#f9f9f9;border-left:4px solid #d4af37;padding:16px 20px;border-radius:4px;margin:20px 0;}
.info-box p{margin:4px 0;font-size:13px;color:#333;}
.badge{display:inline-block;background:#d4af37;color:#111;font-weight:700;padding:4px 12px;border-radius:20px;font-size:12px;}
.footer{padding:16px 32px;text-align:center;color:#aaa;font-size:12px;border-top:1px solid #eee;}
</style></head>
<body>
<div class="wrap">
    <div class="header">
        <h1>Application Received!</h1>
        <p>CrazyRays Solutions — Recruitment</p>
    </div>
    <div class="body">
        <p>Dear <strong>{{ $application->full_name }}</strong>,</p>

        <p>Thank you for applying to <strong>CrazyRays Solutions</strong>. We have successfully received your application and our team will review it shortly.</p>

        <div class="info-box">
            <p><strong>Campaign:</strong> <span class="badge">{{ $application->campaign_label }}</span></p>
            <p><strong>Name:</strong> {{ $application->full_name }}</p>
            <p><strong>Email:</strong> {{ $application->email }}</p>
            <p><strong>Status:</strong> Under Review</p>
        </div>

        <p>Our recruitment team will carefully review your application and get back to you as soon as possible. You will receive another email once a decision has been made.</p>

        <p>In the meantime, if you have any questions or need to update your application, feel free to reach out to us.</p>

        <p style="font-size:13px;color:#777;">
            Questions? Contact us at
            <a href="mailto:careers@crazyrayssolutions.com.pk" style="color:#d4af37;">careers@crazyrayssolutions.com.pk</a>
        </p>
    </div>
    <div class="footer">© {{ now()->year }} CrazyRays Solutions · All rights reserved</div>
</div>
</body>
</html>
