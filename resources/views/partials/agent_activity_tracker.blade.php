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
    var idle = false;            // true while the work clock is paused

    // Live on-screen clock (seeded from the navbar element if present)
    var seed = document.getElementById('agentActiveTimeDisplay');
    var displaySeconds = seed && seed.dataset.seconds ? parseInt(seed.dataset.seconds, 10) || 0 : 0;
    function human(s) {
        s = Math.max(0, Math.floor(s));
        var h = Math.floor(s / 3600), m = Math.floor((s % 3600) / 60);
        return (h > 0 ? h + 'h ' : '') + m + 'm';
    }
    function paintClock() {
        var el = document.getElementById('agentActiveTimeDisplay');
        if (el) el.textContent = human(displaySeconds);
    }
    paintClock();
    setInterval(function () {
        if (document.visibilityState !== 'hidden' && (Date.now() - lastActivity) < IDLE_LIMIT_MS) {
            displaySeconds += 1; paintClock();
        }
    }, 1000);

    function showWelcomeBack() {
        // Self-contained toast with !important inline styles so portal theme CSS
        // can't inflate/misplace it.
        var box = document.createElement('div');
        box.setAttribute('style', [
            'position:fixed!important','top:20px!important','right:20px!important','z-index:2147483647!important',
            'background:#16a34a!important','color:#fff!important','padding:12px 16px!important',
            'border-radius:10px!important','box-shadow:0 8px 24px rgba(0,0,0,.28)!important',
            'max-width:300px!important','width:auto!important','box-sizing:border-box!important',
            'font-family:Segoe UI,Arial,sans-serif!important','opacity:1!important'
        ].join(';'));
        var title = document.createElement('div');
        title.setAttribute('style', 'font-size:15px!important;font-weight:700!important;line-height:1.3!important;margin:0 0 2px 0!important;color:#fff!important;');
        title.textContent = 'Welcome back! 👋';
        var sub = document.createElement('div');
        sub.setAttribute('style', 'font-size:12.5px!important;font-weight:400!important;line-height:1.4!important;color:#fff!important;');
        sub.textContent = 'Your work timer has resumed.';
        box.appendChild(title); box.appendChild(sub);
        document.body.appendChild(box);
        setTimeout(function () { box.style.transition = 'opacity .4s'; box.style.setProperty('opacity', '0', 'important'); setTimeout(function () { box.remove(); }, 400); }, 3000);
    }

    function markActive() {
        if (idle) { idle = false; showWelcomeBack(); }   // resumed after a pause
        lastActivity = Date.now();
    }
    ['mousemove','mousedown','keydown','scroll','touchstart','click','wheel'].forEach(function (ev) {
        window.addEventListener(ev, markActive, { passive: true });
    });

    // Accrue active time (and detect idle → clock stops)
    setInterval(function () {
        var visible = (document.visibilityState !== 'hidden');
        if (visible && (Date.now() - lastActivity) < IDLE_LIMIT_MS) {
            pendingSeconds += TICK_MS / 1000;
        } else {
            idle = true; // paused: no activity or tab hidden — work clock stopped
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
            if (res && typeof res.today_seconds === 'number') {
                displaySeconds = res.today_seconds; // reconcile live clock with shared total
                paintClock();
            }
        })
        .catch(function () { pendingSeconds += secs; /* retry next flush */ });
    }

    setInterval(function () { flush(false); }, FLUSH_MS);
    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'hidden') {
            flush(true);
            idle = true;          // left the tab → stop the clock
        } else {
            markActive();         // returned to the tab → resume + "Welcome back"
        }
    });
    window.addEventListener('pagehide', function () { flush(true); });
})();
</script>
@endauth
