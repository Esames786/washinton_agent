<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background: #062e39;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .gp-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #062e39;
            border-bottom: 3px solid #8fc445;
            padding: 10px 20px;
            flex-shrink: 0;
        }

        .gp-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .gp-badge {
            background: #8fc445;
            color: #fff;
            font-size: 11px;
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .gp-badge.autohaul {
            background: #e67e22;
        }

        .gp-title {
            color: #fff;
            font-size: 16px;
            font-weight: bold;
        }

        .gp-back {
            color: #8fc445;
            text-decoration: none;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: opacity 0.2s;
        }

        .gp-back:hover { opacity: 0.75; color: #8fc445; text-decoration: none; }

        .gp-iframe-wrap {
            flex: 1;
            position: relative;
        }

        .gp-iframe-wrap iframe {
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .gp-loading {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            color: #8fc445;
            font-size: 15px;
            pointer-events: none;
        }
    </style>
</head>
<body>

    <div class="gp-header">
        <div class="gp-header-left">
            <span class="gp-badge {{ $portalType === 'autohaul' ? 'autohaul' : '' }}">
                {{ $portalType === 'autohaul' ? 'AutoHaul' : 'Hello' }}
            </span>
            <span class="gp-title">{{ $title }}</span>
        </div>
        <a href="javascript:window.close()" class="gp-back">
            ✕ Close
        </a>
    </div>

    <div class="gp-iframe-wrap">
        <div class="gp-loading" id="gpLoading">Loading portal…</div>
        @if(!empty($portalUrl))
            <iframe
                src="{{ $portalUrl }}"
                id="gatewayIframe"
                allowfullscreen
                onload="document.getElementById('gpLoading').style.display='none'">
            </iframe>
        @else
            <div style="color:#fff;text-align:center;margin-top:80px;font-size:15px;">
                Portal URL not configured.<br>
                <small style="color:#8fc445;">Set GATEWAY_PORTAL_WASHINGTON / GATEWAY_PORTAL_AUTOHAUL in .env</small>
            </div>
        @endif
    </div>

</body>
</html>
