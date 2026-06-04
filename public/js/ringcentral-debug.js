/**
 * R-Dialer  Debug Helpers
 * Debug modal and diagnostic functions for R-Dialer  WebPhone integration
 */

function rcDebug(msg) {
    console.log('[rcDebug]', msg);
    const el = document.getElementById('rcDebugLog');
    if (!el) return;
    el.textContent = (new Date()).toISOString() + ' - ' + msg + '\n' + el.textContent;
}

function updateDebugUI() {
    const libEl = document.getElementById('rcDebugLib');
    const tokenEl = document.getElementById('rcDebugToken');
    const sessionEl = document.getElementById('rcDebugSession');
    if (!libEl || !tokenEl || !sessionEl) {
        return;
    }
    libEl.textContent = (window.RingCentralWebPhone || window.WebPhone) ? 'Loaded' : 'Not loaded';
    // Guard webPhone references since it may not be created yet
    if (typeof webPhone !== 'undefined' && webPhone) {
        tokenEl.textContent = webPhone.isInitialized ? 'OK' : 'Not initialized';
        sessionEl.textContent = webPhone.isCallActive() ?
            (webPhone.currentSession?.id || 'active') : '—';
    } else {
        tokenEl.textContent = 'Not initialized';
        sessionEl.textContent = '—';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const fetchTokenBtn = document.getElementById('fetchTokenBtn');
    if (fetchTokenBtn) fetchTokenBtn.addEventListener('click', async function () {
        console.log('Fetch Token button clicked');
        try {
            rcDebug('Fetching token with force refresh...');
            const response = await fetch(rcRoute('ringcentral.api.webphone-token', {}, { force_refresh: 1 }), {
                credentials: 'include'
            });
            console.log('Fetch response status:', response.status);
            const data = await response.json();
            console.log('Fetch data:', data);
            rcDebug('Fetched token: ' + JSON.stringify(data, null, 2));
        } catch (e) {
            console.error('Fetch error:', e);
            rcDebug('Fetch token error: ' + e.message);
        }
    });

    // Debug modal button handler
    const debugBtn = document.getElementById('openDebugBtn');
    if (debugBtn) {
        debugBtn.addEventListener('click', function () {
            openDebugModal();
            updateDebugUI(); // Refresh debug info when opening modal
        });
    }

    // Periodically update debug UI only when debug panel controls exist.
    if (fetchTokenBtn || debugBtn) {
        setInterval(updateDebugUI, 2000);
        updateDebugUI();
    }
});
