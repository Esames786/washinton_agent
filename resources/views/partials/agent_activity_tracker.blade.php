{{-- Agent active working-time tracker.
     Counts seconds only while the user is genuinely active (cursor moves,
     keyboard, clicks, scroll, touch) and the tab is visible. Flushes the
     accumulated active seconds to the server every 60s and on page hide. --}}
@auth
<script>
(function () {
    var HEARTBEAT_URL = "{{ route('agent.activity.heartbeat') }}";
    var CSRF = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
    if (!CSRF) return;

    var IDLE_LIMIT_MS = 60000;   // no activity for 60s => considered idle
    var TICK_MS       = 5000;    // accrue active time every 5s
    var FLUSH_MS      = 60000;   // send to server every 60s

    var lastActivity  = Date.now();
    var pendingSeconds = 0;      // active seconds not yet flushed

    function markActive() { lastActivity = Date.now(); }
    ['mousemove','mousedown','keydown','scroll','touchstart','click','wheel'].forEach(function (ev) {
        window.addEventListener(ev, markActive, { passive: true });
    });

    // Accrue active time
    setInterval(function () {
        var visible = (document.visibilityState !== 'hidden');
        if (visible && (Date.now() - lastActivity) < IDLE_LIMIT_MS) {
            pendingSeconds += TICK_MS / 1000;
        }
    }, TICK_MS);

    function flush(useBeacon) {
        var secs = Math.round(pendingSeconds);
        if (secs < 1) return;
        pendingSeconds -= secs;

        if (useBeacon && navigator.sendBeacon) {
            var fd = new FormData();
            fd.append('_token', CSRF);
            fd.append('seconds', secs);
            navigator.sendBeacon(HEARTBEAT_URL, fd);
            return;
        }

        fetch(HEARTBEAT_URL, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json' },
            body: JSON.stringify({ seconds: secs }),
            keepalive: true
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (res && res.today_human) {
                var el = document.getElementById('agentActiveTimeDisplay');
                if (el) el.textContent = res.today_human;
            }
        })
        .catch(function () { pendingSeconds += secs; /* retry next flush */ });
    }

    setInterval(function () { flush(false); }, FLUSH_MS);
    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'hidden') flush(true);
    });
    window.addEventListener('pagehide', function () { flush(true); });
})();
</script>
@endauth
