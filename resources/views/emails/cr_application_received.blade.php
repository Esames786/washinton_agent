<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:Arial,sans-serif;background:#f5f5f5;margin:0;padding:20px;}
.wrap{max-width:640px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1);}
.header{background:#111;padding:24px 32px;}
.header h1{color:#d4af37;margin:0;font-size:22px;}
.header p{color:#aaa;margin:4px 0 0;font-size:13px;}
.body{padding:28px 32px;}
.section{margin-bottom:20px;}
.section h3{color:#333;font-size:14px;font-weight:700;border-bottom:2px solid #d4af37;padding-bottom:6px;margin-bottom:12px;text-transform:uppercase;letter-spacing:.5px;}
.row{display:flex;margin-bottom:8px;}
.label{color:#777;font-size:13px;width:180px;flex-shrink:0;}
.value{color:#222;font-size:13px;font-weight:600;}
.badge{display:inline-block;background:#d4af37;color:#111;font-size:12px;font-weight:700;padding:3px 10px;border-radius:4px;text-transform:uppercase;}
.actions{background:#f9f9f9;padding:20px 32px;text-align:center;border-top:1px solid #eee;}
.btn{display:inline-block;background:#d4af37;color:#111;font-weight:700;text-decoration:none;padding:12px 32px;border-radius:6px;font-size:14px;}
.footer{padding:16px 32px;text-align:center;color:#aaa;font-size:12px;}
</style></head>
<body>
<div class="wrap">
    <div class="header">
        <h1>New Application Received</h1>
        <p>CrazyRays Solutions — Recruitment Portal</p>
    </div>
    <div class="body">
        <p style="color:#555;font-size:14px;margin-top:0;">A new campaign application has been submitted. Review the details below and take action in the admin panel.</p>

        <div class="section">
            <h3>Campaign</h3>
            <span class="badge">{{ $application->campaign_label }}</span>
        </div>

        <div class="section">
            <h3>Personal Information</h3>
            <div class="row"><span class="label">Full Name</span><span class="value">{{ $application->full_name }}</span></div>
            <div class="row"><span class="label">Father's Name</span><span class="value">{{ $application->father_name ?? '—' }}</span></div>
            <div class="row"><span class="label">National ID</span><span class="value">{{ $application->national_id ?? '—' }}</span></div>
            <div class="row"><span class="label">Date of Birth</span><span class="value">{{ $application->dob ? $application->dob->format('d M Y') : '—' }}</span></div>
            <div class="row"><span class="label">Gender</span><span class="value">{{ ucfirst($application->gender ?? '—') }}</span></div>
            <div class="row"><span class="label">Marital Status</span><span class="value">{{ ucfirst($application->marital_status ?? '—') }}</span></div>
            <div class="row"><span class="label">Email</span><span class="value">{{ $application->email }}</span></div>
            <div class="row"><span class="label">Phone</span><span class="value">{{ $application->phone }}</span></div>
            <div class="row"><span class="label">Country</span><span class="value">{{ $application->country ?? '—' }}</span></div>
            <div class="row"><span class="label">City</span><span class="value">{{ $application->city ?? '—' }}</span></div>
            <div class="row"><span class="label">State/Province</span><span class="value">{{ $application->state ?? '—' }}</span></div>
            <div class="row"><span class="label">Address</span><span class="value">{{ $application->address ?? '—' }}</span></div>
        </div>

        <div class="section">
            <h3>Employment Info</h3>
            <div class="row"><span class="label">Shift Type</span><span class="value">{{ $application->shift_type ?? '—' }}</span></div>
            <div class="row"><span class="label">Pay Type</span><span class="value">{{ $application->pay_type ?? '—' }}</span></div>
            <div class="row"><span class="label">Contract Accepted</span><span class="value">{{ $application->contract_accepted_at ? $application->contract_accepted_at->format('d M Y H:i') : 'Not accepted' }}</span></div>
        </div>

        @if($application->campaign_experience)
        <div class="section">
            <h3>Campaign Experience</h3>
            <p style="color:#333;font-size:13px;line-height:1.6;margin:0;">{{ $application->campaign_experience }}</p>
        </div>
        @endif

        @if($application->additional_info)
        <div class="section">
            <h3>Additional Info</h3>
            <p style="color:#333;font-size:13px;line-height:1.6;margin:0;">{{ $application->additional_info }}</p>
        </div>
        @endif

        @if($application->documents && count($application->documents))
        <div class="section">
            <h3>Documents Submitted</h3>
            @foreach($application->documents as $doc)
            <div class="row"><span class="label">{{ $doc['title'] ?? 'Document' }}</span><span class="value">{{ $doc['filename'] ?? '—' }}</span></div>
            @endforeach
        </div>
        @endif

        @if($application->resume_path)
        <div class="section">
            <h3>Resume</h3>
            <p style="color:#555;font-size:13px;margin:0;">Resume attached to this email.</p>
        </div>
        @endif
    </div>
    <div class="actions">
        <a href="{{ url('/cr-applications') }}" class="btn">Review in Admin Panel →</a>
    </div>
    <div class="footer">© {{ now()->year }} Hello Transport · CrazyRays Recruitment System</div>
</div>
</body>
</html>
