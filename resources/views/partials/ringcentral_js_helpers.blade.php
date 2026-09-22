<script>
// ═══════════════════════════════════════════════════════════════
// R-Dialer Helper Functions (Shared across all views)
// ═══════════════════════════════════════════════════════════════

var phoneaccessArray = <?php echo isset($phoneaccessJson) ? $phoneaccessJson : '[]'; ?>;
// R-Dialer is permission 169 — the code the "R-Dialer (RingCentral Phone)" checkbox writes, and the
// one RingCentralAccess middleware, the nav entry and the mainsite RC stream all check.
// This line checked 161, which is "Commission Report": an unrelated feature. An agent granted
// R-Dialer but not Commission Report therefore fell through to the tel:/rcapp:// links, which only
// work if the RingCentral DESKTOP app is installed and registered for those protocols — so the call
// and message buttons did nothing at all, with no error. Reported 22 Sep 2026.
var hasRDialerAccess = ({{ Auth::user()->role == 1 ? 'true' : 'false' }} || phoneaccessArray.includes('169'));

var RC_PENDING_DIAL_REQUEST_KEY = 'rcPendingDialRequest';
var RC_PENDING_MESSAGE_REQUEST_KEY = 'rcPendingMessageRequest';
var RC_PORTAL_URL = "{{ route('ringcentral.portal') }}";
var RC_PORTAL_WINDOW_NAME = 'RingCentralPortal';
var RC_PORTAL_ALIVE_KEY = 'rcPortalAlive';
var RC_PORTAL_FOCUS_REQUEST_KEY = 'rcPortalFocusRequest';
var RC_PORTAL_ALIVE_MAX_AGE_MS = 120000;

function getRingCentralPortalAliveTimestamp() {
    try {
        var raw = localStorage.getItem(RC_PORTAL_ALIVE_KEY);
        if (!raw) return 0;
        if (/^\d+$/.test(raw)) return parseInt(raw, 10);
        var parsed = JSON.parse(raw);
        return parseInt((parsed && (parsed.ts || parsed.timestamp || parsed.createdAt)) || '0', 10);
    } catch (_) {
        return 0;
    }
}

function requestRingCentralPortalFocus() {
    try {
        localStorage.setItem(RC_PORTAL_FOCUS_REQUEST_KEY, JSON.stringify({
            requestId: 'rc_focus_' + Date.now() + '_' + Math.random().toString(36).slice(2, 8),
            createdAt: Date.now()
        }));
    } catch (_) { }
}

function openRingCentralPortalWindow() {
    var portalLooksAlive = false;
    try {
        var lastAliveTs = getRingCentralPortalAliveTimestamp();
        portalLooksAlive = !!lastAliveTs && ((Date.now() - lastAliveTs) <= RC_PORTAL_ALIVE_MAX_AGE_MS);
    } catch (_) {
        portalLooksAlive = false;
    }

    if (portalLooksAlive) {
        try {
            if (window.__rcPortalWindow && !window.__rcPortalWindow.closed) {
                window.__rcPortalWindow.focus();
                return;
            }
        } catch (_) { }
        requestRingCentralPortalFocus();
        return;
    }

    var rcWindow = null;
    try {
        rcWindow = window.open(RC_PORTAL_URL, RC_PORTAL_WINDOW_NAME);
    } catch (_) {
        rcWindow = null;
    }

    if (rcWindow) {
        window.__rcPortalWindow = rcWindow;
        try { rcWindow.focus(); } catch (_) { }
        return;
    }

    window.location.href = RC_PORTAL_URL;
}

function launchRingCentralDialer(num) {
    var decodedNumber = atob(num);
    var dialNumber = (decodedNumber || '').trim();

    if (!dialNumber) {
        return;
    }

    if (window.RingCentralPortalBridge && typeof window.RingCentralPortalBridge.handoffDial === 'function') {
        window.RingCentralPortalBridge.handoffDial(dialNumber, {
            autoCall: true,
            source: 'hellotransport_quote'
        });
        return;
    }

    var requestId = 'rc_' + Date.now() + '_' + Math.random().toString(36).slice(2, 10);
    var payload = {
        requestId: requestId,
        number: dialNumber,
        autoCall: true,
        source: 'hellotransport_quote',
        createdAt: Date.now()
    };

    try {
        localStorage.setItem(RC_PENDING_DIAL_REQUEST_KEY, JSON.stringify(payload));
    } catch (e) {
        console.error('Failed to store R-Dialer pending dial request:', e);
    }

    openRingCentralPortalWindow();
}

function launchRingCentralMessage(num) {
    var decodedNumber = atob(num);
    var messageNumber = (decodedNumber || '').trim();

    if (!messageNumber) {
        return;
    }

    if (window.RingCentralPortalBridge && typeof window.RingCentralPortalBridge.handoffMessage === 'function') {
        window.RingCentralPortalBridge.handoffMessage(messageNumber, {
            source: 'hellotransport_quote'
        });
        return;
    }

    var requestId = 'rcm_' + Date.now() + '_' + Math.random().toString(36).slice(2, 10);
    var payload = {
        requestId: requestId,
        number: messageNumber,
        openModal: true,
        source: 'hellotransport_quote',
        createdAt: Date.now()
    };

    try {
        localStorage.setItem(RC_PENDING_MESSAGE_REQUEST_KEY, JSON.stringify(payload));
    } catch (e) {
        console.error('Failed to store R-Dialer pending message request:', e);
    }

    openRingCentralPortalWindow();
}

function regain_call() {
    // Placeholder for WebSocket/SSE stream management
    // Can be extended for real-time call state if needed
}

</script>
