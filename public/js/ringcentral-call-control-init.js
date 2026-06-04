(function () {
    function isEnabled() {
        return !!(window.RC_FEATURES && window.RC_FEATURES.callControl);
    }

    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    async function requestJson(url, options) {
        const res = await fetch(url, Object.assign({
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json'
            }
        }, options || {}));

        const data = await res.json().catch(function () { return null; });
        if (!res.ok) {
            const message = (data && (data.message || data.error)) || ('HTTP ' + res.status);
            throw new Error(message);
        }
        return data;
    }

    async function fetchSessions() {
        if (!isEnabled()) return { success: false, data: { records: [] } };
        const url = rcRoute('ringcentral.api.call-control.sessions') + '?activeOnly=true';
        return requestJson(url);
    }

    function getRecords(payload) {
        if (!payload || !payload.data) return [];
        if (Array.isArray(payload.data.records)) return payload.data.records;
        if (Array.isArray(payload.data)) return payload.data;
        return [];
    }

    function findSwitchableSession(records) {
        return (records || []).find(function (s) {
            const source = String(s?.origin || s?.source || '').toLowerCase();
            if (source && source.indexOf('web') >= 0) return false;

            const parties = Array.isArray(s?.parties) ? s.parties : [];
            return parties.some(function (p) {
                const status = String(p?.status?.code || p?.status || '').toLowerCase();
                const alive = ['proceeding', 'answered', 'connected', 'hold', 'onhold', 'parked'].includes(status);
                return !!(p?.id && alive);
            });
        }) || null;
    }

    function getFirstActiveParty(session) {
        const parties = Array.isArray(session?.parties) ? session.parties : [];
        return parties.find(function (p) {
            const status = String(p?.status?.code || p?.status || '').toLowerCase();
            return ['proceeding', 'answered', 'connected', 'hold', 'onhold', 'parked'].includes(status);
        }) || null;
    }

    const api = {
        isEnabled: isEnabled,
        async sessions() {
            return fetchSessions();
        },
        refreshSessions: function (reason) {
            return refreshSessions(reason || 'manual');
        },
        async transfer(sessionId, partyId, phoneNumber) {
            const url = rcRoute('ringcentral.api.call-control.transfer', { sessionId: sessionId, partyId: partyId });
            return requestJson(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ phone_number: phoneNumber })
            });
        },
        async merge(payload) {
            const url = rcRoute('ringcentral.api.call-control.merge');
            return requestJson(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload || {})
            });
        },
        async switchToWeb(sessionId, partyId, payload) {
            const url = rcRoute('ringcentral.api.call-control.switch-to-web', { sessionId: sessionId, partyId: partyId });
            return requestJson(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload || {})
            });
        },
        findSwitchableSession: findSwitchableSession,
        getFirstActiveParty: getFirstActiveParty,
        getRecords: getRecords
    };

    window.RCCallControl = api;

    if (!isEnabled()) {
        return;
    }

    var refreshInFlight = null;

    async function refreshSessions(reason) {
        if (refreshInFlight) return refreshInFlight;

        refreshInFlight = (async function () {
            try {
                var result = await api.sessions();
                var records = getRecords(result);
                window.__rcCallControlSessions = records;

                document.dispatchEvent(new CustomEvent('ringcentral:callControlSessionsUpdated', {
                    detail: { sessions: records, reason: reason || 'event' }
                }));
            } catch (e) {
                console.warn('call-control refresh failed', e);
            } finally {
                refreshInFlight = null;
            }
        })();

        return refreshInFlight;
    }

    function isWebPhoneReady() {
        return !!(window.webPhone && window.webPhone.isInitialized);
    }

    function bindEventDrivenRefresh() {
        var refreshEvents = [
            'ringcentral:webphoneReady',
            'ringcentral:callStarted',
            'ringcentral:callConnected',
            'ringcentral:callAnswered',
            'ringcentral:callEnded',
            'ringcentral:callRejected',
            'ringcentral:callFailed'
        ];

        refreshEvents.forEach(function (eventName) {
            document.addEventListener(eventName, function () {
                refreshSessions(eventName);
            });
        });

        if (isWebPhoneReady()) {
            refreshSessions('webphone-already-ready');
        }
    }

    bindEventDrivenRefresh();
})();
