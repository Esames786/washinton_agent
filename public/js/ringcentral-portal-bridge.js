(function () {
    if (window.RingCentralPortalBridge && window.RingCentralPortalBridge.__ready) return;

    const PORTAL_URL = window.RC_PORTAL_URL || '/r/portal';
    const PORTAL_WINDOW_NAME = window.RC_PORTAL_WINDOW_NAME || 'RingCentralPortal';
    const PORTAL_ALIVE_KEY = 'rcPortalAlive';
    const PORTAL_FOCUS_REQUEST_KEY = 'rcPortalFocusRequest';
    const PENDING_DIAL_REQUEST_KEY = 'rcPendingDialRequest';
    const PENDING_MESSAGE_REQUEST_KEY = 'rcPendingMessageRequest';
    const VISIBLE_HANDOFF_SESSION_KEY = 'rcVisibleHandoffId';
    const PORTAL_ALIVE_MAX_AGE_MS = 120000;
    const CHANNEL_NAME = 'rcPortalBridge';

    let channel = null;
    try {
        if ('BroadcastChannel' in window) {
            channel = new BroadcastChannel(CHANNEL_NAME);
        }
    } catch (_) {
        channel = null;
    }

    function now() {
        return Date.now();
    }

    function makeId(prefix) {
        return prefix + '_' + now() + '_' + Math.random().toString(36).slice(2, 10);
    }

    function readJson(key) {
        try {
            const raw = localStorage.getItem(key);
            if (!raw) return null;
            return JSON.parse(raw);
        } catch (_) {
            return null;
        }
    }

    function readPortalAlive() {
        try {
            const raw = localStorage.getItem(PORTAL_ALIVE_KEY);
            if (!raw) return null;

            if (/^\d+$/.test(raw)) {
                return { ts: parseInt(raw, 10), legacy: true };
            }

            const parsed = JSON.parse(raw);
            if (!parsed || typeof parsed !== 'object') return null;
            const ts = parseInt(parsed.ts || parsed.timestamp || parsed.createdAt || '0', 10);
            if (!ts) return null;
            parsed.ts = ts;
            return parsed;
        } catch (_) {
            return null;
        }
    }

    function isPortalAlive() {
        const alive = readPortalAlive();
        return !!(alive && alive.ts && ((now() - alive.ts) <= PORTAL_ALIVE_MAX_AGE_MS));
    }

    function getFreshPortalAliveRecord() {
        const alive = readPortalAlive();
        if (!alive || !alive.ts || ((now() - alive.ts) > PORTAL_ALIVE_MAX_AGE_MS)) {
            return null;
        }

        return alive;
    }

    function postChannel(message) {
        if (!message || typeof message !== 'object') return;

        try {
            if (channel) channel.postMessage(message);
        } catch (_) { }
    }

    function requestPortalFocus(reason, extra) {
        const payload = {
            requestId: makeId('rc_focus'),
            type: 'focus',
            reason: reason || 'portal-request',
            createdAt: now()
        };

        if (extra && typeof extra === 'object') {
            Object.keys(extra).forEach(function (key) {
                if (extra[key] !== undefined && extra[key] !== null) {
                    payload[key] = extra[key];
                }
            });
        }

        try {
            localStorage.setItem(PORTAL_FOCUS_REQUEST_KEY, JSON.stringify(payload));
        } catch (_) { }

        postChannel(payload);
        return payload;
    }

    function focusKnownPortalReference() {
        try {
            if (window.__rcPortalWindow && !window.__rcPortalWindow.closed) {
                window.__rcPortalWindow.focus();
                return true;
            }
        } catch (_) { }

        return false;
    }

    function setCurrentTabVisibleHandoff(visibleHandoffId) {
        if (!visibleHandoffId) return;

        try {
            sessionStorage.setItem(VISIBLE_HANDOFF_SESSION_KEY, visibleHandoffId);
        } catch (_) { }
    }

    function announceVisibleHandoff(visibleHandoffId, targetPortalInstanceId) {
        if (!visibleHandoffId) return;

        postChannel({
            type: 'rc-visible-handoff',
            visibleHandoffId: visibleHandoffId,
            targetPortalInstanceId: targetPortalInstanceId || null,
            createdAt: now()
        });
    }

    function markPortalWindowForVisibleHandoff(portalWindow, visibleHandoffId) {
        if (!portalWindow || !visibleHandoffId) return false;

        let marked = false;
        try {
            portalWindow.sessionStorage.setItem(VISIBLE_HANDOFF_SESSION_KEY, visibleHandoffId);
            marked = true;
        } catch (_) { }

        try {
            portalWindow.postMessage({
                type: 'rc-visible-handoff',
                visibleHandoffId: visibleHandoffId
            }, new URL(PORTAL_URL, window.location.href).origin);
        } catch (_) { }

        return marked;
    }

    function openVisiblePortalWindow(visibleHandoffId) {
        let portalWindow = null;

        try {
            portalWindow = window.open('', PORTAL_WINDOW_NAME);
        } catch (_) {
            portalWindow = null;
        }

        if (!portalWindow) return false;

        window.__rcPortalWindow = portalWindow;
        markPortalWindowForVisibleHandoff(portalWindow, visibleHandoffId);

        let shouldNavigate = true;
        try {
            const currentHref = (portalWindow.location && portalWindow.location.href) ? portalWindow.location.href : '';
            const portalHref = new URL(PORTAL_URL, window.location.href).href.split('#')[0];
            if (currentHref && currentHref !== 'about:blank' && currentHref.split('#')[0] === portalHref) {
                shouldNavigate = false;
            }
        } catch (_) {
            shouldNavigate = false;
        }

        if (shouldNavigate) {
            try {
                portalWindow.location.href = PORTAL_URL;
            } catch (_) { }
        }

        try { portalWindow.focus(); } catch (_) { }

        setTimeout(() => markPortalWindowForVisibleHandoff(portalWindow, visibleHandoffId), 150);
        setTimeout(() => markPortalWindowForVisibleHandoff(portalWindow, visibleHandoffId), 800);

        return true;
    }

    function showPortalNotice(message) {
        if (!message) return;

        try {
            if (window.toastr && typeof window.toastr.info === 'function') {
                window.toastr.info(message);
                return;
            }
        } catch (_) { }

        try {
            console.info(message);
        } catch (_) { }
    }

    function openPortal(options) {
        const opts = options || {};
        const aliveRecord = getFreshPortalAliveRecord();
        const alive = !!aliveRecord;
        const targetPortalInstanceId = opts.targetPortalInstanceId || (aliveRecord && aliveRecord.instanceId) || null;

        if (alive && !opts.forceOpen) {
            if (opts.visible) {
                announceVisibleHandoff(opts.visibleHandoffId, targetPortalInstanceId);
                requestPortalFocus(opts.reason || 'existing-portal', {
                    visibleHandoffId: opts.visibleHandoffId,
                    targetPortalInstanceId: targetPortalInstanceId
                });

                if (focusKnownPortalReference()) {
                    return { opened: false, alive: true, focused: true };
                }

                if (opts.showNotice !== false) {
                    showPortalNotice('RingCentral portal is already open. Request sent to that tab.');
                }
                return { opened: false, alive: true };
            }

            requestPortalFocus(opts.reason || 'existing-portal', {
                targetPortalInstanceId: targetPortalInstanceId
            });

            if (focusKnownPortalReference()) {
                return { opened: false, alive: true, focused: true };
            }

            if (opts.showNotice !== false) {
                showPortalNotice('RingCentral portal is already open. Request sent to that tab.');
            }
            return { opened: false, alive: true };
        }

        if (opts.visible) {
            if (openVisiblePortalWindow(opts.visibleHandoffId)) {
                return { opened: true, alive: false, focused: true };
            }
        } else {
            let portalWindow = null;
            try {
                portalWindow = window.open(PORTAL_URL, PORTAL_WINDOW_NAME);
            } catch (_) {
                portalWindow = null;
            }

            if (portalWindow) {
                window.__rcPortalWindow = portalWindow;
                try { portalWindow.focus(); } catch (_) { }
                return { opened: true, alive: false };
            }
        }

        window.location.href = PORTAL_URL;
        return { opened: false, redirected: true, alive: false };
    }

    function handoffDial(number, options) {
        const dialNumber = (number || '').toString().trim();
        if (!dialNumber) return false;

        const opts = options || {};
        const visibleHandoffId = opts.requireVisible === false ? null : makeId('rcv');
        const aliveRecord = getFreshPortalAliveRecord();
        const targetPortalInstanceId = (visibleHandoffId && aliveRecord && aliveRecord.instanceId) ? aliveRecord.instanceId : null;
        setCurrentTabVisibleHandoff(visibleHandoffId);
        const payload = {
            requestId: makeId('rc'),
            number: dialNumber,
            autoCall: opts.autoCall !== false,
            source: opts.source || 'site',
            createdAt: now(),
            visibleHandoffId: visibleHandoffId,
            targetPortalInstanceId: targetPortalInstanceId
        };

        try {
            localStorage.setItem(PENDING_DIAL_REQUEST_KEY, JSON.stringify(payload));
        } catch (e) {
            console.error('Failed to store RingCentral pending dial request:', e);
            return false;
        }

        if (!visibleHandoffId) {
            postChannel({ type: 'dial-request', payload: payload });
        }
        openPortal({
            reason: 'dial-request',
            showNotice: opts.showNotice,
            visible: !!visibleHandoffId,
            visibleHandoffId: visibleHandoffId,
            targetPortalInstanceId: targetPortalInstanceId
        });
        return true;
    }

    function handoffMessage(number, options) {
        const messageNumber = (number || '').toString().trim();
        if (!messageNumber) return false;

        const opts = options || {};
        const visibleHandoffId = opts.requireVisible === false ? null : makeId('rcv');
        const aliveRecord = getFreshPortalAliveRecord();
        const targetPortalInstanceId = (visibleHandoffId && aliveRecord && aliveRecord.instanceId) ? aliveRecord.instanceId : null;
        setCurrentTabVisibleHandoff(visibleHandoffId);
        const payload = {
            requestId: makeId('rcm'),
            number: messageNumber,
            openModal: true,
            source: opts.source || 'site',
            createdAt: now(),
            visibleHandoffId: visibleHandoffId,
            targetPortalInstanceId: targetPortalInstanceId
        };

        try {
            localStorage.setItem(PENDING_MESSAGE_REQUEST_KEY, JSON.stringify(payload));
        } catch (e) {
            console.error('Failed to store RingCentral pending message request:', e);
            return false;
        }

        if (!visibleHandoffId) {
            postChannel({ type: 'message-request', payload: payload });
        }
        openPortal({
            reason: 'message-request',
            showNotice: opts.showNotice,
            visible: !!visibleHandoffId,
            visibleHandoffId: visibleHandoffId,
            targetPortalInstanceId: targetPortalInstanceId
        });
        return true;
    }

    function isPortalLink(anchor) {
        if (!anchor || !anchor.href) return false;
        return anchor.href.split('#')[0] === PORTAL_URL.split('#')[0];
    }

    function bindPortalLinks() {
        document.addEventListener('click', function (event) {
            const anchor = event.target && event.target.closest ? event.target.closest('a') : null;
            if (!isPortalLink(anchor)) return;

            event.preventDefault();
            openPortal({ forceOpen: !isPortalAlive(), reason: 'portal-link' });
        });
    }

    window.RingCentralPortalBridge = {
        __ready: true,
        channel: channel,
        handoffDial: handoffDial,
        handoffMessage: handoffMessage,
        isPortalAlive: isPortalAlive,
        getFreshPortalAliveRecord: getFreshPortalAliveRecord,
        openPortal: openPortal,
        requestPortalFocus: requestPortalFocus
    };

    bindPortalLinks();
})();
