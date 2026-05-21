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
.actions{text-align:center;margin:28px 0;}
.btn{display:inline-block;background:#d4af37;color:#111;font-weight:700;text-decoration:none;padding:14px 36px;border-radius:6px;font-size:15px;}
.footer{padding:16px 32px;text-align:center;color:#aaa;font-size:12px;border-top:1px solid #eee;}
</style></head>
<body>
<div class="wrap">
    <div class="header">
        <h1>🎉 Application Approved!</h1>
        <p>CrazyRays Solutions — Recruitment</p>
    </div>
    <div class="body">
        <p>Dear <strong>{{ $application->full_name }}</strong>,</p>
        <p>We are pleased to inform you that your application for the <strong>{{ $application->campaign_label }}</strong> campaign has been reviewed and <strong>approved</strong>.</p>
        <p>Your account has been created on the HelloTransport portal. You can log in using the credentials below:</p>

        <div class="info-box">
            <p><strong>Portal:</strong> <a href="{{ $portalUrl }}" style="color:#d4af37;">{{ $portalUrl }}</a></p>
            <p><strong>Email:</strong> {{ $application->email }}</p>
            <p><strong>Campaign:</strong> {{ $application->campaign_label }}</p>
        </div>

        <p>If you applied under the Logistics / Trucking Dispatch campaign, use the password you set during your application to log in. For all other campaigns, your account administrator will provide login instructions.</p>

        <p>Please log in at your earliest convenience to complete your profile and get started.</p>

        <div class="actions">
            <a href="{{ $portalUrl }}" class="btn">Log In to Portal →</a>
        </div>

        <p style="font-size:13px;color:#777;">If you have any questions, reply to this email or contact us at <a href="mailto:careers@crazyrayssolutions.com.pk" style="color:#d4af37;">careers@crazyrayssolutions.com.pk</a></p>
    </div>
    <div class="footer">© {{ now()->year }} CrazyRays Solutions · All rights reserved</div>
</div>
</body>
</html>
