<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:Arial,sans-serif;background:#f5f5f5;margin:0;padding:20px;}
.wrap{max-width:600px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1);}
.header{background:#111;padding:28px 32px;text-align:center;}
.header h1{color:#ccc;margin:0;font-size:22px;}
.header p{color:#888;margin:6px 0 0;font-size:13px;}
.body{padding:32px;}
.body p{color:#444;font-size:14px;line-height:1.7;margin-bottom:14px;}
.note-box{background:#fff8f8;border-left:4px solid #e74c3c;padding:16px 20px;border-radius:4px;margin:20px 0;}
.note-box p{margin:0;font-size:13px;color:#555;}
.footer{padding:16px 32px;text-align:center;color:#aaa;font-size:12px;border-top:1px solid #eee;}
</style></head>
<body>
<div class="wrap">
    <div class="header">
        <h1>Application Update</h1>
        <p>CrazyRays Solutions — Recruitment</p>
    </div>
    <div class="body">
        <p>Dear <strong>{{ $application->full_name }}</strong>,</p>
        <p>Thank you for your interest in joining CrazyRays Solutions and for applying to the <strong>{{ $application->campaign_label }}</strong> campaign.</p>
        <p>After careful review, we regret to inform you that we are unable to move forward with your application at this time.</p>

        @if($application->rejection_note)
        <div class="note-box">
            <p><strong>Reviewer Note:</strong> {{ $application->rejection_note }}</p>
        </div>
        @endif

        <p>We appreciate the time and effort you invested in your application. We encourage you to apply again in the future as new opportunities become available.</p>
        <p>If you have any questions, feel free to reach out at <a href="mailto:careers@crazyrayssolutions.com.pk" style="color:#d4af37;">careers@crazyrayssolutions.com.pk</a>.</p>
        <p>We wish you all the best in your career endeavors.</p>
    </div>
    <div class="footer">© {{ now()->year }} CrazyRays Solutions · All rights reserved</div>
</div>
</body>
</html>
