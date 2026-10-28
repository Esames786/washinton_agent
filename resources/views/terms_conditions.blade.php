<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Terms &amp; Conditions — Hello Transport</title>
<style>
    * { box-sizing: border-box; }
    body { margin:0; background:#f4f6f9; font-family:'Segoe UI',Arial,sans-serif; color:#222; line-height:1.7; }
    .wrap { max-width:900px; margin:0 auto; background:#fff; box-shadow:0 2px 14px rgba(0,0,0,.07); }
    .head { background:linear-gradient(135deg,#062e39 0%,#0d5c70 100%); color:#fff; padding:34px 40px; text-align:center; }
    .head h1 { margin:0; font-size:26px; letter-spacing:.5px; }
    .head p { margin:6px 0 0; opacity:.8; font-size:13px; }
    .body { padding:32px 40px 48px; }
    .body h2 { color:#062e39; font-size:16px; margin:24px 0 6px; border-left:4px solid #8fc445; padding-left:10px; }
    .body p, .body li { font-size:14px; color:#444; }
    .body ul { padding-left:22px; }
    .foot { background:#f8f9fa; border-top:1px solid #e9ecef; padding:18px 40px; text-align:center; color:#888; font-size:12px; }
    a { color:#0d5c70; }
    .body .ht-legal-updated { color:#888; font-size:13px; margin-top:0; }
</style>
</head>
<body>
<div class="wrap">
    <div class="head">
        <h1>Terms &amp; Conditions</h1>
        <p>Hello Transport — Vehicle Transportation Services</p>
    </div>
    <div class="body">
        @include('partials.legal.hello_terms_body')
    </div>
    <div class="foot">
        © {{ date('Y') }} Hello Transport. All Rights Reserved.
    </div>
</div>
</body>
</html>
