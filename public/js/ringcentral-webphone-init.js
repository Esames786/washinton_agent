/**
 * R-Dialer  WebPhone Initialization
 * Handles WebPhone SDK initialization, connection checking, and setup
 */

(function initRcLogger() {
    if (window.rcLog) return;
    window.rcLogLevel = window.rcLogLevel || 'silent';
    window.rcLog = function () {
        // Logging disabled
    };
    window.rcInfo = function () {
        // Logging disabled
    };
})();

let webPhone = null;
const CALL_STATE_KEY = 'rcCurrentCall';
let currentOutputDeviceLabel = 'Default system output';
let callTimer = null; // legacy timer reference; keep defined to avoid ReferenceError

// Clear RingCentral-related browser storage to reset instance/session conflicts
window.clearRcBrowserStorage = async function clearRcBrowserStorage() {
    const confirmed = window.confirm('Clear R-Dialer browser session data for this site? This will reset the WebPhone instance for this browser.');
    if (!confirmed) return;

    try {
        // Best-effort cleanup of active call/session
        try {
            if (webPhone && typeof webPhone.endCall === 'function') {
                await webPhone.endCall();
            }
        } catch (e) {
            // Error ending call
        }

        try {
            if (webPhone && typeof webPhone.dispose === 'function') {
                await webPhone.dispose();
            }
        } catch (e) {
            // Error disposing webPhone
        }

        const keysToRemove = [
            'rc_webphone_browser_instance_id',
            'rc_active_instances',
            'rcCurrentCall',
            'rcLastDialedNumber'
        ];

        keysToRemove.forEach((k) => {
            try { localStorage.removeItem(k); } catch (_) { }
            try { sessionStorage.removeItem(k); } catch (_) { }
        });

        // Remove any instance timestamp keys
        try {
            const prefix = 'rc_instance_timestamp_';
            const toRemove = [];
            for (let i = 0; i < localStorage.length; i++) {
                const k = localStorage.key(i);
                if (k && k.indexOf(prefix) === 0) {
                    toRemove.push(k);
                }
            }
            toRemove.forEach((k) => localStorage.removeItem(k));
        } catch (e) {
            // Error clearing instance keys
        }

        try {
            if (typeof endCall === 'function') {
                endCall();
            }
        } catch (_) { }

        alert('R-Dialer browser session data cleared. Reloading page...');
        window.location.reload();
    } catch (e) {
        alert('Failed to clear browser session data. Check console for details.');
    }
};

// Initialize WebPhone when page loads
document.addEventListener('DOMContentLoaded', async function () {
    if (typeof window.rcPortalIsActiveInstance === 'function' && !window.rcPortalIsActiveInstance()) {
        const checkingUI = document.getElementById('checkingConnectionUI');
        const connectedUI = document.getElementById('connectedUI');
        const disconnectedUI = document.getElementById('disconnectedUI');

        if (checkingUI) {
            checkingUI.style.display = 'block';
            checkingUI.innerHTML = ''
                + '<div class="alert alert-warning">'
                + '  <div style="display:flex;flex-wrap:wrap;align-items:center;gap:10px;justify-content:space-between;">'
                + '    <span>R-Dialer portal is already open in another tab. Calls and messages will continue there.</span>'
                + '    <button type="button" id="rcUseHereBtn" class="btn btn-sm btn-primary">Use Here</button>'
                + '  </div>'
                + '</div>';

            const useHereBtn = document.getElementById('rcUseHereBtn');
            if (useHereBtn && useHereBtn.dataset.bound !== '1') {
                useHereBtn.dataset.bound = '1';
                useHereBtn.addEventListener('click', function () {
                    try {
                        // Force this tab to become active by clearing stale tab-instance markers.
                        const keysToRemove = [
                            'rc_webphone_browser_instance_id',
                            'rc_active_instances',
                            'rcCurrentCall',
                            'rcPortalVisibleHandoffId',
                            'rcPortalVisibleHandoffPending',
                            'rcPortalAlive'
                        ];
                        keysToRemove.forEach((k) => {
                            try { localStorage.removeItem(k); } catch (_) { }
                            try { sessionStorage.removeItem(k); } catch (_) { }
                        });

                        // Refresh heartbeat for this tab immediately before reload.
                        try { localStorage.setItem('rcPortalAlive', String(Date.now())); } catch (_) { }
                    } catch (_) { }

                    window.location.reload();
                });
            }
        }
        if (connectedUI) connectedUI.style.display = 'none';
        if (disconnectedUI) disconnectedUI.style.display = 'none';
        return;
    }

    // Ensure connected to R-Portal before loading SDK and initializing
    let isConnected = false;
    try {
        let checkUrl = null;
        try {
            if (window.RC_ROUTES && window.RC_ROUTES.checkConnectionStatus) {
                checkUrl = window.RC_ROUTES.checkConnectionStatus;
            } else if (typeof rcRoute === 'function') {
                checkUrl = rcRoute('ringcentral.check-connection-status');
            } else if (typeof route === 'function') {
                checkUrl = route('ringcentral.check-connection-status');
            }
        } catch (e) {
        }

        if (checkUrl) {
            rcLog('R-Dialer connection check URL:', checkUrl);
        }

        const r = await fetch(checkUrl, { credentials: 'same-origin' });
        if (r.ok) {
            const data = await r.json();
            isConnected = !!(data?.connected || data?.success || data?.isConnected || data?.status === 'connected');
            rcLog('R-Dialer connection check response:', data);
        }
    } catch (e) {
    }

    // Show/hide appropriate UI based on connection status
    const checkingUI = document.getElementById('checkingConnectionUI');
    const connectedUI = document.getElementById('connectedUI');
    const disconnectedUI = document.getElementById('disconnectedUI');
    const reconnectBtn = document.getElementById('reconnectBtn');
    const connectBtn = document.getElementById('connectBtn');
    const reconnectStatus = document.getElementById('reconnectStatus');
    const attemptReconnect = async function () {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        if (reconnectBtn) reconnectBtn.disabled = true;
        if (connectBtn) connectBtn.disabled = true;

        if (reconnectStatus) {
            reconnectStatus.style.display = 'block';
            reconnectStatus.textContent = 'Trying saved token reconnect...';
        }

        try {
            const res = await fetch(rcRoute('ringcentral.reconnect'), {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                }
            });
            const data = await res.json().catch(() => ({}));

            if (res.ok && data && data.connected) {
                if (reconnectStatus) {
                    reconnectStatus.textContent = 'Reconnected. Reloading...';
                }
                window.location.reload();
                return true;
            }

            if (data && data.requiresOAuth && data.oauthUrl) {
                const msg = (data && data.message)
                    ? data.message
                    : 'Saved session was not found. Start a new R-Dialer connection?';
                const shouldStartNew = window.confirm(msg);
                if (shouldStartNew) {
                    window.location.href = data.oauthUrl;
                    return false;
                }
            }

            if (reconnectStatus) {
                reconnectStatus.textContent = (data && data.message)
                    ? data.message
                    : 'Reconnect failed. Use Connect to R-Portal.';
            }
            return false;
        } catch (e) {
            if (reconnectStatus) {
                reconnectStatus.textContent = 'Reconnect request failed. Use Connect to R-Portal.';
            }
            return false;
        } finally {
            if (reconnectBtn) reconnectBtn.disabled = false;
            if (connectBtn) connectBtn.disabled = false;
        }
    };

    window.rcAttemptReconnect = attemptReconnect;
    if (reconnectBtn && !reconnectBtn.dataset.bound) {
        reconnectBtn.dataset.bound = '1';
        reconnectBtn.addEventListener('click', attemptReconnect);
    }

    // Hide checking UI
    if (checkingUI) checkingUI.style.display = 'none';

    if (isConnected) {
        if (connectedUI) connectedUI.style.display = 'block';
        if (disconnectedUI) disconnectedUI.style.display = 'none';
        rcInfo('✅ R-Dialer connected - loading WebPhone SDK');
    } else {
        if (connectedUI) connectedUI.style.display = 'none';
        if (disconnectedUI) disconnectedUI.style.display = 'block';
        if (reconnectStatus) {
            reconnectStatus.style.display = 'none';
            reconnectStatus.textContent = '';
        }

        // Enable connect button after check
        if (connectBtn) {
            connectBtn.disabled = false;
            connectBtn.textContent = 'Connect to R-Portal';
        }
        return;
    }

    // Load SDK bundle before init
    try {
        if (typeof loadRcWebPhoneBundle === 'function') {
            await loadRcWebPhoneBundle();
        }
    } catch (e) {
        showErrorModal('Failed to load R-Dialer WebPhone SDK. Please reload the page.', true);
        return;
    }
    
    // FIRST: Clear any stale call state from previous session
    try {
        const saved = localStorage.getItem(CALL_STATE_KEY);
        if (saved) {
            rcLog('🧹 Clearing stale call state from localStorage before init');
            localStorage.removeItem(CALL_STATE_KEY);
            // Reset UI immediately
            if (typeof endCall === 'function') {
                endCall();
            }
        }
    } catch (e) {
        // Error clearing stale call state
    }

    try {
        // RingCentralCallClient
        webPhone = new RingCentralWebPhone({
            apiBaseUrl: rcRoute('ringcentral.api.base')
        });

        // Initialize WebPhone
        await webPhone.init();
        rcInfo('WebPhone initialized successfully');
        // expose on window for other scripts that check window.webPhone
        try { window.webPhone = webPhone; } catch (_) { }
        try {
            document.dispatchEvent(new CustomEvent('ringcentral:webphoneReady', {
                detail: { isInitialized: !!(webPhone && webPhone.isInitialized) }
            }));
        } catch (_) { }

        try {
            const loadingEl = document.getElementById('webphoneLoadingIndicator');
            if (loadingEl) loadingEl.style.display = 'none';
        } catch (_) { }

        try {
            if (typeof loadRcCoreData === 'function') {
                loadRcCoreData();
            }
        } catch (e) {
            // Failed to load RC core data
        }

        // Configure recording UI based on actual account policies if available
        try {
            const caps = (typeof webPhone.getRecordingCapabilities === 'function') ? webPhone.getRecordingCapabilities() : null;
            if (typeof setupRecordingUI === 'function') {
                setupRecordingUI(caps);
            }
        } catch (e) {
            // Could not get recording capabilities
        }

        // Populate available audio outputs (if supported) and wire select change
        try {
            async function populateAudioOutputsSelect() {
                const select = document.getElementById('audioOutputSelect');
                if (select) select.innerHTML = '<option>Loading...</option>';
                if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
                    if (select) select.innerHTML = '<option value="">Not supported</option>';
                    return;
                }

                // Some browsers won't show labels until permission granted; request media permission first
                try {
                    await navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {
                        stream.getTracks().forEach(t => t.stop());
                    }).catch(() => { });
                } catch (err) {
                    // ignore
                }

                const devices = await navigator.mediaDevices.enumerateDevices();
                const outputs = devices.filter(d => d.kind === 'audiooutput');

                // Add default/communications options first
                const options = [];
                options.push({ id: 'default', label: 'Default' });
                options.push({ id: 'communications', label: 'Communications' });
                outputs.forEach((d, i) => {
                    options.push({ id: d.deviceId, label: (d.label && d.label.length) ? d.label : ('Output ' + (i + 1)) });
                });

                // store globally for per-session controls
                window.rcAudioOutputOptions = options;
                window.rcAudioOutputOptionsHtml = options.map(o => `<option value="${o.id}">${o.label}</option>`).join('');

                if (select) {
                    select.innerHTML = window.rcAudioOutputOptionsHtml;
                }
            }

            await populateAudioOutputsSelect();
        } catch (e) {
            // Audio output initialization failed
        }
    } catch (error) {
        // Show user-friendly modal for init failures
        const msg = (error && error.message) ? error.message : 'WebPhone initialization failed';
        const needsReload = /match|library not loaded|Failed to load R-Dialer WebPhone SDK/i.test(msg);
        const showReconnect = (error && Number(error.status) === 400) || /webphone token:\s*400\b/i.test(msg);
        const showDisconnect = (error && Number(error.status) === 500) || /webphone token:\s*500\b/i.test(msg);
        showErrorModal(msg, needsReload, { showReconnect, showDisconnect });
    }
});
