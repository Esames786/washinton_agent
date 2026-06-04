/**
 * R-Dialer  WebPhone Event Listeners
 * Handles all ringcentral:* custom events and WebPhone state management
 */

(function initRcLogger() {
    if (window.rcLog) return;
    window.rcLogLevel = window.rcLogLevel || 'brief';
    window.rcLog = function () {
        if (window.rcLogLevel === 'verbose') {
            window.console.log.apply(console, arguments);
        }
    };
    window.rcInfo = function () {
        if (window.rcLogLevel !== 'silent') {
            console.info.apply(console, arguments);
        }
    };
})();

function rcResolveSessionIdCandidates(source) {
    if (!source) return [];
    if (typeof source === 'string' || typeof source === 'number') return [String(source)];
    const ids = [
        source.id,
        source.sessionId,
        source.callId,
        source.partyId,
        source.index,
        source?.session?.id,
        source?.session?.sessionId,
        source?.session?.callId,
        source?.session?.partyId,
        source?.session?.index
    ]
        .filter((v) => v !== undefined && v !== null && String(v) !== '')
        .map((v) => String(v));
    return Array.from(new Set(ids));
}

function rcCleanupEndedSessionRegistry(maxAgeMs = 30 * 60 * 1000) {
    try {
        const registry = window._rcEndedSessionIds;
        if (!registry || typeof registry !== 'object') return;
        const now = Date.now();
        Object.keys(registry).forEach((id) => {
            const ts = Number(registry[id] || 0);
            if (!ts || (now - ts) > maxAgeMs) delete registry[id];
        });
    } catch (_) { }
}

function rcMarkEndedSessionIds(ids) {
    try {
        if (!Array.isArray(ids) || !ids.length) return;
        window._rcEndedSessionIds = window._rcEndedSessionIds || {};
        rcCleanupEndedSessionRegistry();
        const now = Date.now();
        ids.forEach((id) => {
            if (!id) return;
            window._rcEndedSessionIds[String(id)] = now;
        });
    } catch (_) { }
}

function rcClearEndedSessionIds(ids) {
    try {
        if (!Array.isArray(ids) || !ids.length) return;
        const registry = window._rcEndedSessionIds;
        if (!registry || typeof registry !== 'object') return;
        ids.forEach((id) => {
            if (!id) return;
            delete registry[String(id)];
        });
    } catch (_) { }
}

// Incoming call queue manager for dialer incoming UI
(function initIncomingCallQueueManager() {
    if (window._rcIncomingQueueManagerReady) return;
    window._rcIncomingQueueManagerReady = true;

    window._incomingCallQueue = Array.isArray(window._incomingCallQueue) ? window._incomingCallQueue : [];
    window._incomingCallQueueCursor = Math.max(0, parseInt(window._incomingCallQueueCursor || 0, 10) || 0);
    window._incomingLostCallsCount = Math.max(0, parseInt(window._incomingLostCallsCount || 0, 10) || 0);

    function resolveSessionId(source) {
        if (!source) return null;
        if (typeof source === 'string' || typeof source === 'number') return String(source);
        return source.sessionId || source.id || source.callId || source.partyId || source.index || null;
    }

    function resolveSessionIdCandidates(source) {
        if (!source) return [];
        if (typeof source === 'string' || typeof source === 'number') return [String(source)];

        const direct = [source.sessionId, source.id, source.callId, source.partyId, source.index];
        const nested = source.session
            ? [source.session.sessionId, source.session.id, source.session.callId, source.session.partyId, source.session.index]
            : [];

        const ids = [...direct, ...nested]
            .filter((v) => v !== undefined && v !== null && String(v) !== '')
            .map((v) => String(v));

        return Array.from(new Set(ids));
    }

    function normalizeIncomingDetail(detail) {
        const session = detail?.session || {};
        const from = detail?.from || detail?.caller || session.from || session.caller || session.remote || {};
        const to = detail?.to || session.to || session.destination || session.toNumber || {};
        const sessionIds = Array.from(new Set([
            ...resolveSessionIdCandidates(detail),
            ...resolveSessionIdCandidates(session)
        ]));

        let sessionId = resolveSessionId(detail) || resolveSessionId(session);
        if (!sessionId && window.webPhone && typeof window.webPhone.listSessions === 'function') {
            const sessions = window.webPhone.listSessions() || [];
            if (sessions.length > 0) {
                sessionId = resolveSessionId(sessions[0]);
            }
        }
        if (!sessionId && sessionIds.length > 0) sessionId = sessionIds[0];

        const callerNumber = (
            from.phoneNumber ||
            from.number ||
            from.phone ||
            from.callerNumber ||
            session.remoteNumber ||
            session.fromNumber ||
            detail?.callerNumber ||
            'Unknown'
        );

        const toNumber = to.phoneNumber || to.number || to.phone || to.displayName || 'Your Number';
        const callerNameCandidate = from.name || from.displayName || session.remoteName || '';
        const callerName = isUsableIncomingCallerName(callerNameCandidate, callerNumber)
            ? callerNameCandidate
            : 'Unknown';

        return {
            key: sessionId ? String(sessionId) : ('incoming-' + Date.now() + '-' + Math.floor(Math.random() * 100000)),
            sessionId: sessionId ? String(sessionId) : null,
            sessionIds: sessionIds,
            callerNumber: String(callerNumber || 'Unknown'),
            toNumber: String(toNumber || 'Your Number'),
            callerName: String(callerName || 'Unknown'),
            createdAt: Date.now()
        };
    }

    function applyMask(value) {
        const raw = value || '';
        if (typeof window.rcMaskPhoneNumber === 'function') {
            return window.rcMaskPhoneNumber(raw, {
                visibleDigits: 4,
                minMaskChars: 5,
                emptyFallback: '*****'
            });
        }
        if (typeof maskPhoneNumber === 'function') {
            return maskPhoneNumber(raw, {
                visibleDigits: 4,
                minMaskChars: 5,
                emptyFallback: '*****'
            });
        }
        const digits = String(raw).replace(/\D/g, '');
        if (!digits) return '*****';
        return `*****${digits.slice(-4)}`;
    }

    function isUsableIncomingCallerName(name, callerNumber) {
        const rawName = String(name || '').trim();
        if (!rawName) return false;
        if (/^unknown$/i.test(rawName)) return false;

        const nameDigits = rawName.replace(/\D/g, '');
        const callerDigits = String(callerNumber || '').replace(/\D/g, '');

        if (nameDigits && callerDigits && nameDigits === callerDigits) return false;
        if (!/[A-Za-z]/.test(rawName) && nameDigits.length >= 7) return false;

        return true;
    }

    function isDuringCallUiVisible() {
        try {
            const duringSection = document.getElementById('dialerDuringCallSection');
            if (!duringSection) return false;
            return duringSection.style.display === 'block';
        } catch (_) {
            return false;
        }
    }

    function getSessionIdCandidates(session) {
        if (!session) return [];
        return [session.id, session.sessionId, session.callId, session.partyId, session.index]
            .filter((v) => v !== undefined && v !== null && String(v) !== '')
            .map((v) => String(v));
    }

    function sessionMatchesId(session, targetId) {
        if (!targetId) return false;
        const tid = String(targetId);
        return getSessionIdCandidates(session).includes(tid);
    }

    function isSessionIgnored(session, ignoredSet) {
        const set = ignoredSet || new Set();
        const ids = getSessionIdCandidates(session);
        return ids.some((id) => set.has(id));
    }

    function hasAcceptedActiveCall() {
        try {
            if (!window.webPhone || typeof webPhone.listSessions !== 'function') {
                return false;
            }

            const sessions = webPhone.listSessions() || [];
            const incomingStates = ['incoming', 'early', 'ringing', 'pending', 'alerting', 'invite'];
            const terminalStates = ['disposed', 'terminated', 'ended', 'disconnected', 'failed', 'rejected', 'cancelled', 'canceled'];
            const activeStates = ['active', 'connected', 'established', 'confirmed', 'inprogress', 'answered', 'hold', 'held', 'proceeding'];
            const incomingId = window._incomingCallSessionId ? String(window._incomingCallSessionId) : '';
            const currentId = window._dialerCurrentSessionId ? String(window._dialerCurrentSessionId) : '';
            const ignoredSessions = new Set((window._ignoredCallSessions || []).map(String));
            rcCleanupEndedSessionRegistry();
            const endedRegistry = window._rcEndedSessionIds || {};
            const isSessionEndedByRegistry = (session) => {
                const ids = getSessionIdCandidates(session);
                return ids.some((id) => !!endedRegistry[String(id)]);
            };

            // Prefer current selected session when present to avoid flicker during answer transitions.
            if (currentId) {
                const currentSession = sessions.find((s) => sessionMatchesId(s, currentId));
                if (currentSession) {
                    const currentState = String(currentSession?.state || currentSession?.status || '').toLowerCase();
                    const isTerminal = terminalStates.some(x => currentState.includes(x));
                    const isIncoming = incomingStates.some(x => currentState.includes(x));
                    const ignored = isSessionIgnored(currentSession, ignoredSessions);
                    const endedByRegistry = isSessionEndedByRegistry(currentSession);
                    if (!isTerminal && !isIncoming && !ignored && !endedByRegistry) return true;
                }
            }

            return sessions.some((s) => {
                const state = String(s?.state || s?.status || '').toLowerCase();
                if (terminalStates.some(x => state.includes(x))) return false;
                if (incomingStates.some(x => state.includes(x))) return false;
                if (incomingId && sessionMatchesId(s, incomingId)) return false;
                if (isSessionIgnored(s, ignoredSessions)) return false;
                if (isSessionEndedByRegistry(s)) return false;
                if (s?.hold === true || s?.onHold === true || s?._onHold === true) return true;
                return activeStates.some(x => state.includes(x));
            });
        } catch (_) {
            return false;
        }
    }

    function getIncomingItemIds(item) {
        if (!item) return [];
        const ids = Array.isArray(item.sessionIds) && item.sessionIds.length
            ? item.sessionIds
            : [item.sessionId, item.key];
        return ids
            .filter((v) => v !== undefined && v !== null && String(v) !== '')
            .map((v) => String(v));
    }

    function findSessionForIncomingItem(item) {
        try {
            if (!window.webPhone || typeof webPhone.listSessions !== 'function') return null;
            const itemIds = getIncomingItemIds(item);
            if (!itemIds.length) return null;
            const sessions = webPhone.listSessions() || [];
            return sessions.find((session) => {
                const sessionIds = getSessionIdCandidates(session);
                return sessionIds.some((id) => itemIds.includes(id));
            }) || null;
        } catch (_) {
            return null;
        }
    }

    function shouldSuppressIncomingItemOnThisDevice(item) {
        try {
            const currentId = window._dialerCurrentSessionId ? String(window._dialerCurrentSessionId) : '';
            const itemIds = getIncomingItemIds(item);
            if (currentId && itemIds.includes(currentId)) return true;

            const session = findSessionForIncomingItem(item);
            if (!session) return false;

            const direction = String(session?.direction || session?.callDirection || session?._rcDirection || '').toLowerCase();
            if (session?._rcOriginatedLocally === true) return true;
            if (direction.includes('outbound') || direction.includes('outgoing')) return true;

            return currentId && sessionMatchesId(session, currentId);
        } catch (_) {
            return false;
        }
    }

    function isIncomingQueueItemStillLive(item) {
        try {
            const createdAt = Number(item?.createdAt || 0);
            const ageMs = createdAt ? (Date.now() - createdAt) : 0;
            const graceMs = 3000;

            if (!window.webPhone || typeof webPhone.listSessions !== 'function') return true;

            const itemIds = getIncomingItemIds(item);
            if (!itemIds.length) return ageMs < graceMs;

            const session = findSessionForIncomingItem(item);
            if (!session) return ageMs < graceMs;

            const state = String(session?.state || session?.status || '').toLowerCase();
            const direction = String(session?.direction || session?.callDirection || '').toLowerCase();
            const terminalStates = ['disposed', 'terminated', 'ended', 'disconnected', 'failed', 'rejected', 'cancelled', 'canceled'];
            const incomingStates = ['incoming', 'early', 'ringing', 'pending', 'alerting', 'invite', 'offering'];
            const acceptedStates = ['active', 'connected', 'established', 'confirmed', 'inprogress', 'answered', 'hold', 'held', 'proceeding'];

            if (terminalStates.some((flag) => state.includes(flag))) return false;
            if (acceptedStates.some((flag) => state.includes(flag))) return false;
            if (session?.hold === true || session?.onHold === true || session?._onHold === true) return false;
            if (incomingStates.some((flag) => state.includes(flag))) return true;
            if ((direction.includes('inbound') || direction.includes('incoming')) && !state) return true;

            return ageMs < graceMs;
        } catch (_) {
            return true;
        }
    }

    function pruneSuppressedIncomingQueueItems() {
        const queue = Array.isArray(window._incomingCallQueue) ? window._incomingCallQueue : [];
        if (!queue.length) return queue;

        const filtered = queue.filter((item) => {
            if (shouldSuppressIncomingItemOnThisDevice(item)) return false;
            return isIncomingQueueItemStillLive(item);
        });
        if (filtered.length !== queue.length) {
            window._incomingCallQueue = filtered;
            if (!filtered.length) {
                window._incomingCallQueueCursor = 0;
                window._incomingCallSessionId = null;
                window._minimizedIncomingCallActive = false;
                window._minimizedIncomingCallNumber = '';
            } else if (window._incomingCallQueueCursor >= filtered.length) {
                window._incomingCallQueueCursor = filtered.length - 1;
            }
        }

        return filtered;
    }

    function hideDuringIncomingBanner() {
        const banner = document.getElementById('dialerDuringIncomingBanner');
        if (banner) banner.style.display = 'none';
    }

    function renderDuringIncomingBanner(current, queueCount) {
        const banner = document.getElementById('dialerDuringIncomingBanner');
        const countEl = document.getElementById('dialerDuringIncomingCount');
        const numEl = document.getElementById('dialerDuringIncomingNumber');
        if (!banner) return;

        const maskedCaller = applyMask(current?.callerNumber || 'Unknown');
        const callerName = isUsableIncomingCallerName(current?.callerName, current?.callerNumber)
            ? current.callerName
            : maskedCaller;

        if (countEl) countEl.textContent = String(Math.max(0, parseInt(queueCount || 0, 10) || 0));
        if (numEl) numEl.textContent = callerName || maskedCaller || 'Unknown';
        banner.style.display = 'flex';
    }

    function updateIncomingUiMeta() {
        const queue = pruneSuppressedIncomingQueueItems();
        const cursor = window._incomingCallQueueCursor || 0;
        const queueCount = queue.length;
        const activePosition = queueCount ? (cursor + 1) : 0;
        const incomingSection = document.getElementById('dialerIncomingCallSection');
        const duringSection = document.getElementById('dialerDuringCallSection');
        const beforeSection = document.getElementById('dialerBeforeCallSection');
        const hasAcceptedCall = hasAcceptedActiveCall();

        const countEl = document.getElementById('incomingQueueCount');
        const labelEl = document.getElementById('incomingQueueLabel');
        const positionEl = document.getElementById('incomingQueuePositionText');
        const prevBtn = document.getElementById('incomingPrevCallBtn');
        const nextBtn = document.getElementById('incomingNextCallBtn');
        const lostCountEl = document.getElementById('incomingLostCallsCount');
        const lostBtn = document.getElementById('incomingLostCallsBtn');
        const duringCountEl = document.getElementById('dialerDuringIncomingCount');

        if (countEl) countEl.textContent = String(queueCount || 0);
        if (labelEl) labelEl.textContent = queueCount === 1 ? 'Call' : 'Calls';
        if (positionEl) {
            positionEl.textContent = queueCount ? (`Call ${activePosition} of ${queueCount}`) : 'No incoming calls';
        }
        if (prevBtn) prevBtn.disabled = queueCount <= 1;
        if (nextBtn) nextBtn.disabled = queueCount <= 1;

        const lostCount = Math.max(0, parseInt(window._incomingLostCallsCount || 0, 10) || 0);
        if (lostCountEl) lostCountEl.textContent = lostCount > 99 ? '99+' : String(lostCount);
        if (lostBtn) lostBtn.disabled = lostCount <= 0;
        if (duringCountEl) duringCountEl.textContent = String(queueCount || 0);

        // If there is no active call, never keep during-call section visible.
        if (!hasAcceptedCall && duringSection) {
            duringSection.style.display = 'none';
            if (queueCount <= 0 && beforeSection) beforeSection.style.display = 'block';
        }

        if (queueCount <= 0) {
            if (incomingSection) incomingSection.style.display = 'none';
            hideDuringIncomingBanner();
        }
    }

    function renderIncomingQueueItem(options) {
        const opts = options || {};
        const queue = pruneSuppressedIncomingQueueItems();

        if (!queue.length) {
            const incomingSection = document.getElementById('dialerIncomingCallSection');
            const beforeSection = document.getElementById('dialerBeforeCallSection');
            const duringSection = document.getElementById('dialerDuringCallSection');
            const hasAcceptedCall = hasAcceptedActiveCall();

            window._incomingCallSessionId = null;
            window._minimizedIncomingCallActive = false;
            window._minimizedIncomingCallNumber = '';
            if (incomingSection) incomingSection.style.display = 'none';
            if (hasAcceptedCall) {
                if (duringSection) duringSection.style.display = 'block';
                if (beforeSection) beforeSection.style.display = 'none';
            } else {
                if (duringSection) duringSection.style.display = 'none';
                if (beforeSection) beforeSection.style.display = 'block';
            }
            hideDuringIncomingBanner();
            try { if (typeof refreshMinimizedDialerVisibility === 'function') refreshMinimizedDialerVisibility(); } catch (_) { }
            updateIncomingUiMeta();
            if (!opts.skipHide && typeof hideIncomingCallSection === 'function') {
                hideIncomingCallSection();
            }
            return null;
        }

        if (window._incomingCallQueueCursor >= queue.length) {
            window._incomingCallQueueCursor = queue.length - 1;
        }
        if (window._incomingCallQueueCursor < 0) {
            window._incomingCallQueueCursor = 0;
        }

        const current = queue[window._incomingCallQueueCursor];
        window._incomingCallSessionId = current.sessionId;

        const nameEl = document.getElementById('incomingCallName');
        const numEl = document.getElementById('incomingCallNumber');
        const fromEl = document.getElementById('incomingCallFrom');
        const toEl = document.getElementById('incomingCallTo');

        const maskedCaller = applyMask(current.callerNumber);
        const maskedTo = applyMask(current.toNumber);
        const displayName = isUsableIncomingCallerName(current.callerName, current.callerNumber)
            ? current.callerName
            : maskedCaller;

        if (nameEl) nameEl.textContent = displayName || 'Unknown';
        if (numEl) numEl.textContent = maskedCaller || 'Unknown';
        if (fromEl) fromEl.textContent = maskedCaller || 'Unknown';
        if (toEl) toEl.textContent = maskedTo || 'Your Number';

        const incomingSection = document.getElementById('dialerIncomingCallSection');
        const beforeSection = document.getElementById('dialerBeforeCallSection');
        const duringSection = document.getElementById('dialerDuringCallSection');
        const preferInlineDuring = opts.inlineDuring !== false;
        const shouldInlineDuring = preferInlineDuring && hasAcceptedActiveCall();

        if (shouldInlineDuring) {
            if (incomingSection) incomingSection.style.display = 'block';
            if (duringSection) duringSection.style.display = 'block';
            if (beforeSection) beforeSection.style.display = 'none';
            renderDuringIncomingBanner(current, queue.length);
        } else {
            hideDuringIncomingBanner();
            if (incomingSection) incomingSection.style.display = 'block';
            if (beforeSection) beforeSection.style.display = 'block';
            if (duringSection) duringSection.style.display = 'none';
        }

        window._minimizedIncomingCallActive = true;
        window._minimizedIncomingCallNumber = current.callerNumber || '';
        try { if (typeof refreshMinimizedDialerVisibility === 'function') refreshMinimizedDialerVisibility(); } catch (_) { }

        updateIncomingUiMeta();

        if (opts.focusDialer !== false) {
            try {
                const callsTab = document.querySelector('a[href="#tabCalls"]');
                if (callsTab) {
                    if (window.$ && typeof $(callsTab).tab === 'function') $(callsTab).tab('show');
                    else callsTab.click();
                }
            } catch (_) { }

            if (!shouldInlineDuring && typeof openDialerModal === 'function') openDialerModal();
        }

        return current;
    }

    function enqueueIncomingCall(detail) {
        const item = normalizeIncomingDetail(detail);
        if (shouldSuppressIncomingItemOnThisDevice(item)) {
            rcLog('Suppressing incoming UI for current outbound/local session:', item);
            updateIncomingUiMeta();
            return null;
        }

        const queue = window._incomingCallQueue || [];
        const bySession = item.sessionId
            ? queue.findIndex(q => q.sessionId && q.sessionId === item.sessionId)
            : -1;
        const byKey = bySession < 0
            ? queue.findIndex(q => q.key === item.key)
            : bySession;

        if (byKey >= 0) {
            queue[byKey] = Object.assign({}, queue[byKey], item);
            window._incomingCallQueueCursor = byKey;
        } else {
            queue.push(item);
            window._incomingCallQueueCursor = queue.length - 1;
        }

        window._incomingCallQueue = queue;
        rcLog('Incoming queue updated:', queue);
        return renderIncomingQueueItem({ focusDialer: true });
    }

    function isIncomingSessionQueued(sessionOrId) {
        const candidateIds = resolveSessionIdCandidates(sessionOrId);
        if (!candidateIds.length) return false;
        const candidateSet = new Set(candidateIds);
        const queue = window._incomingCallQueue || [];
        return queue.some(item => {
            const itemIds = Array.isArray(item.sessionIds) && item.sessionIds.length
                ? item.sessionIds.map((id) => String(id))
                : (item.sessionId ? [String(item.sessionId)] : []);
            return itemIds.some((id) => candidateSet.has(id));
        });
    }

    function removeIncomingCallFromQueue(sessionOrId, options) {
        const opts = options || {};
        const candidateIds = resolveSessionIdCandidates(sessionOrId);
        const candidateSet = new Set(candidateIds);
        const queue = window._incomingCallQueue || [];
        const previousLength = queue.length;

        if (!previousLength) {
            updateIncomingUiMeta();
            return 0;
        }

        let removedIndex = -1;
        let removedItem = null;

        if (candidateSet.size > 0) {
            removedIndex = queue.findIndex(item => {
                const itemIds = Array.isArray(item.sessionIds) && item.sessionIds.length
                    ? item.sessionIds.map((id) => String(id))
                    : (item.sessionId ? [String(item.sessionId)] : []);
                return itemIds.some((id) => candidateSet.has(id));
            });
        } else if (window._incomingCallQueueCursor < queue.length) {
            removedIndex = window._incomingCallQueueCursor;
        }

        if (removedIndex >= 0) {
            removedItem = queue.splice(removedIndex, 1)[0];
        }

        if (removedItem && opts.countAsLost) {
            window._incomingLostCallsCount = Math.max(
                0,
                parseInt(window._incomingLostCallsCount || 0, 10) + 1
            );
        }

        window._incomingCallQueue = queue;

        if (queue.length === 0) {
            window._incomingCallQueueCursor = 0;
            window._incomingCallSessionId = null;
        } else if (window._incomingCallQueueCursor >= queue.length) {
            window._incomingCallQueueCursor = queue.length - 1;
        }

        if (!opts.skipRender) {
            renderIncomingQueueItem({ focusDialer: false, skipHide: !!opts.skipHideWhenEmpty });
        } else {
            updateIncomingUiMeta();
        }

        return queue.length;
    }

    function cycleIncomingQueue(step) {
        const queue = window._incomingCallQueue || [];
        if (queue.length <= 1) {
            updateIncomingUiMeta();
            return;
        }
        const increment = step < 0 ? -1 : 1;
        const current = window._incomingCallQueueCursor || 0;
        const next = (current + increment + queue.length) % queue.length;
        window._incomingCallQueueCursor = next;
        renderIncomingQueueItem({ focusDialer: false });
    }

    function showIncomingSessionById(sessionOrId, options) {
        const sid = resolveSessionId(sessionOrId);
        if (!sid) return false;
        const queue = window._incomingCallQueue || [];
        const idx = queue.findIndex(item => item.sessionId && item.sessionId === String(sid));
        if (idx < 0) return false;
        window._incomingCallQueueCursor = idx;
        renderIncomingQueueItem(Object.assign({ focusDialer: false }, options || {}));
        return true;
    }

    function openIncomingLostCalls() {
        try {
            const callsTab = document.querySelector('a[href="#tabCalls"]');
            if (callsTab) {
                if (window.$ && typeof $(callsTab).tab === 'function') $(callsTab).tab('show');
                else callsTab.click();
            }
        } catch (_) { }

        try {
            const typeFilter = document.getElementById('tabCallsTypeFilter');
            if (typeFilter) {
                typeFilter.value = 'missed';
                typeFilter.dispatchEvent(new Event('change', { bubbles: true }));
            }
        } catch (_) { }

        try { if (typeof refreshCallsTab === 'function') refreshCallsTab(); } catch (_) { }

        window._incomingLostCallsCount = 0;
        updateIncomingUiMeta();
    }

    window.rcEnqueueIncomingCall = enqueueIncomingCall;
    window.rcRemoveIncomingCallFromQueue = removeIncomingCallFromQueue;
    window.rcIsIncomingSessionQueued = isIncomingSessionQueued;
    window.rcRenderIncomingQueue = renderIncomingQueueItem;
    window.rcShowIncomingSessionById = showIncomingSessionById;
    window.showNextIncomingCall = function () { cycleIncomingQueue(1); };
    window.showPrevIncomingCall = function () { cycleIncomingQueue(-1); };
    window.openIncomingLostCalls = openIncomingLostCalls;
    window.rcUpdateIncomingUiMeta = updateIncomingUiMeta;

    updateIncomingUiMeta();
    setInterval(updateIncomingUiMeta, 2000);
})();

// Listen for phone events
document.addEventListener('ringcentral:callStarted', function (e) {
    rcLog('Call started:', e.detail);
    try {
        const startedIds = rcResolveSessionIdCandidates(e?.detail);
        if (startedIds.length) rcClearEndedSessionIds(startedIds);

        if (window.webPhone && typeof webPhone.listSessions === 'function') {
            rcLog('[UI DEBUG] callStarted listSessions():', webPhone.listSessions());
            const sessions = webPhone.listSessions() || [];
            // Cache active sessions for later use by speaker selector
            try { window._lastActiveSessions = sessions.slice(); } catch (_) { window._lastActiveSessions = null; }
            if (sessions.length > 0) {
                const lastSession = sessions[sessions.length - 1];
                const sid = lastSession.id || lastSession.sessionId || lastSession.callId || lastSession.partyId || lastSession.index;
                if (sid) rcClearEndedSessionIds([sid]);
                // track the current session for UI and global speaker operations
                window._dialerCurrentSessionId = sid;
                rcLog('[UI DEBUG] set _dialerCurrentSessionId to', sid);
                try {
                    if (typeof attachSessionUiListeners === 'function' && typeof webPhone.findSession === 'function') {
                        const rawSession = webPhone.findSession(sid) || lastSession;
                        attachSessionUiListeners(rawSession);
                    }
                } catch (_) { }
                if (typeof switchDialerToDuringCall === 'function') switchDialerToDuringCall(sid);
                // If a preferred output device is already selected, apply it to the new session
                try {
                    const preferred = (window.webPhone && window.webPhone.currentOutputDeviceId) || (window._rcAudioSpeakerCache && (window._rcAudioSpeakerCache['audioOutputSelect-speaker'] || window._rcAudioSpeakerCache['audioOutputSelect-global'])) || null;
                    if (preferred) {
                        rcLog('[UI DEBUG] Applying preferred output to new session:', preferred);
                        try { if (typeof setSessionOutput === 'function') setSessionOutput(sid, preferred).catch(() => { }); } catch (_) { }
                    }
                } catch (_) { }
            }
        }
    } catch (_) { }
    // All UI updates now handled by dialer modal
});

document.addEventListener('ringcentral:callConnected', function (e) {
    rcInfo('Call connected:', e.detail);
    try {
        const connectedSession = e?.detail?.session || null;
        const connectedId = connectedSession
            ? (connectedSession.id || connectedSession.sessionId || connectedSession.callId || connectedSession.partyId || connectedSession.index || null)
            : (e?.detail?.sessionId || null);
        const connectedIds = Array.from(new Set([
            ...rcResolveSessionIdCandidates(e?.detail),
            ...rcResolveSessionIdCandidates(connectedSession),
            ...(connectedId ? [String(connectedId)] : [])
        ]));
        if (connectedIds.length) rcClearEndedSessionIds(connectedIds);

        let queueRemaining = null;
        if (connectedSession || connectedId) {
            queueRemaining = (typeof window.rcRemoveIncomingCallFromQueue === 'function')
                ? window.rcRemoveIncomingCallFromQueue(connectedSession || connectedId, { skipHideWhenEmpty: true })
                : null;
        }

        const queuedCountNow = Array.isArray(window._incomingCallQueue) ? window._incomingCallQueue.length : 0;
        const effectiveQueueRemaining = queueRemaining === null ? queuedCountNow : queueRemaining;

        if (effectiveQueueRemaining <= 0) {
            window._incomingCallSessionId = null;
            if (typeof hideIncomingCallSection === 'function') hideIncomingCallSection();
            window._minimizedIncomingCallActive = false;
            window._minimizedIncomingCallNumber = '';
            try { if (typeof refreshMinimizedDialerVisibility === 'function') refreshMinimizedDialerVisibility(); } catch (_) { }
        } else if (typeof window.rcRenderIncomingQueue === 'function') {
            // Keep remaining incoming calls visible in queue if any are still ringing.
            window.rcRenderIncomingQueue({ focusDialer: false });
        }
    } catch (_) { }
    try {
        setTimeout(() => {
            try { if (typeof window.syncDialerLiveCallState === 'function') window.syncDialerLiveCallState('ringcentral-events:callConnected'); } catch (_) { }
        }, 90);
    } catch (_) { }
    // All UI updates now handled by dialer modal
});

document.addEventListener('ringcentral:callEnded', function (e) {
    rcInfo('🔴 Call ended:', e.detail);
    const reason = e.detail?.reason || 'unknown';
    rcLog('Call ended reason:', reason);
    const getIdCandidates = (source) => {
        if (!source) return [];
        if (typeof source === 'string' || typeof source === 'number') return [String(source)];
        const ids = [source.id, source.sessionId, source.callId, source.partyId, source.index];
        return Array.from(new Set(ids.filter((v) => v !== undefined && v !== null && String(v) !== '').map((v) => String(v))));
    };
    const matchesByAnyId = (session, targetId) => {
        if (!session || !targetId) return false;
        return getIdCandidates(session).includes(String(targetId));
    };
    const isIgnoredCallEnded = (session, ignoredSet) => {
        const ids = getIdCandidates(session);
        return ids.some((id) => ignoredSet.has(id));
    };

    // Broadcast to detached window
    if (window.dialerDetachedWindow && !window.dialerDetachedWindow.closed) {
        rcLog('Broadcasting callEnded event to detached window');
        window.dialerDetachedWindow.postMessage({
            type: 'ringcentral:callEnded',
            detail: e.detail
        }, window.location.origin);
    }

    const endedSession = e.detail?.session || null;
    const endedSessionId = endedSession
        ? (endedSession.id || endedSession.sessionId || endedSession.callId || endedSession.partyId || endedSession.index || null)
        : null;
    const endedSessionIds = Array.from(new Set([
        ...getIdCandidates(endedSession || endedSessionId),
        ...rcResolveSessionIdCandidates(e?.detail),
        ...(endedSessionId ? [String(endedSessionId)] : [])
    ]));
    if (endedSessionIds.length) rcMarkEndedSessionIds(endedSessionIds);

    let queueRemaining = null;
    try {
        if ((endedSession || endedSessionId) && typeof window.rcIsIncomingSessionQueued === 'function' && window.rcIsIncomingSessionQueued(endedSession || endedSessionId)) {
            queueRemaining = (typeof window.rcRemoveIncomingCallFromQueue === 'function')
                ? window.rcRemoveIncomingCallFromQueue(endedSession || endedSessionId, { countAsLost: true, skipHideWhenEmpty: true })
                : null;
        }
    } catch (_) { }

    const queuedCountNow = Array.isArray(window._incomingCallQueue) ? window._incomingCallQueue.length : 0;
    const effectiveQueueRemaining = queueRemaining === null ? queuedCountNow : queueRemaining;

    // Hide incoming call section only if no queued incoming call remains
    try {
        if (effectiveQueueRemaining <= 0) {
            if (typeof hideIncomingCallSection === 'function') hideIncomingCallSection();
            window._incomingCallSessionId = null;
            rcLog('Cleared _incomingCallSessionId');
        } else {
            rcLog('Incoming queue still has pending calls:', effectiveQueueRemaining);
            if (typeof window.rcRenderIncomingQueue === 'function') {
                window.rcRenderIncomingQueue({ focusDialer: false });
            }
        }
    } catch (_) { }

    // IMPORTANT: callEnded is already a terminal event from SDK.
    // Never invoke endCall() here because it can hang up another still-active session.
    try {
        const sessionsNow = window.webPhone?.listSessions?.() || [];
        const ignoredSessions = new Set((window._ignoredCallSessions || []).map(String));
        const incomingStates = ['incoming', 'early', 'ringing', 'pending', 'alerting', 'invite'];
        const acceptedStates = ['active', 'connected', 'established', 'confirmed', 'inprogress', 'answered', 'hold', 'held', 'proceeding'];
        const activeSessionsNow = sessionsNow.filter(s => {
            const state = String(s?.state || s?.status || '').toLowerCase();
            if (['disposed', 'terminated', 'ended', 'disconnected', 'failed', 'rejected', 'cancelled', 'canceled'].includes(state)) return false;
            if (incomingStates.some(x => state.includes(x))) return false;
            if (isIgnoredCallEnded(s, ignoredSessions)) return false;
            if (s?.hold === true || s?.onHold === true || s?._onHold === true) return true;
            return acceptedStates.some(x => state.includes(x));
        });

        const currentSessionId = window._dialerCurrentSessionId ? String(window._dialerCurrentSessionId) : '';
        const currentStillActive = currentSessionId
            ? activeSessionsNow.some((s) => matchesByAnyId(s, currentSessionId))
            : false;
        const endedIsCurrent = currentSessionId
            ? endedSessionIds.includes(currentSessionId)
            : false;

        if (activeSessionsNow.length <= 0) {
            // Safe UI cleanup only; do not re-hangup via SDK.
            try { if (typeof endCall === 'function') endCall({ skipWebPhoneHangup: true }); } catch (_) { }
            try { if (typeof switchDialerToBeforeCall === 'function') switchDialerToBeforeCall(); } catch (_) { }
            try { if (typeof clearDialer === 'function') clearDialer(); } catch (_) { }
            window._dialerCurrentSessionId = null;
        } else {
            if (!currentStillActive || endedIsCurrent) {
                // Current session was disposed by remote side; clear stale pointer before sync.
                if (!currentStillActive) window._dialerCurrentSessionId = null;
                if (typeof syncDialerToActiveSession === 'function') {
                    setTimeout(() => {
                        try { syncDialerToActiveSession(activeSessionsNow); } catch (_) { }
                    }, 80);
                }
            } else if (typeof syncDialerToActiveSession === 'function') {
                setTimeout(() => {
                    try { syncDialerToActiveSession(activeSessionsNow); } catch (_) { }
                }, 120);
            }
            rcLog('callEnded handled without global hangup; active sessions remain:', activeSessionsNow.length);
        }
    } catch (_) { }

    // Check remaining calls and update header visibility
    try {
        const activeCallsHeader = document.getElementById('activeCallsHeader');
        const currentCallInfo = document.getElementById('currentCallInfo');
        const sessions = window.webPhone?.listSessions?.() || [];
        const ignoredSessions = new Set((window._ignoredCallSessions || []).map(String));
        const incomingStates = ['incoming', 'early', 'ringing', 'pending', 'alerting', 'invite'];
        const acceptedStates = ['active', 'connected', 'established', 'confirmed', 'inprogress', 'answered', 'hold', 'held', 'proceeding'];
        
        // Filter only accepted in-call sessions.
        const activeSessions = sessions.filter(s => {
            const state = String(s?.state || s?.status || '').toLowerCase();
            if (['disposed', 'terminated', 'ended', 'disconnected', 'failed', 'rejected', 'cancelled', 'canceled'].includes(state)) return false;
            if (incomingStates.some(x => state.includes(x))) return false;
            if (isIgnoredCallEnded(s, ignoredSessions)) return false;
            if (s?.hold === true || s?.onHold === true || s?._onHold === true) return true;
            return acceptedStates.some(x => state.includes(x));
        });
        
        rcLog('Remaining active sessions after call ended:', activeSessions.length);
        
        // Show activeCallsHeader only if there are multiple active calls
        if (activeCallsHeader) {
            if (activeSessions.length > 1) {
                activeCallsHeader.style.display = 'flex';
            } else {
                activeCallsHeader.style.display = 'none';
            }
        }
        
        // Show currentCallInfo only if there is at least one active call
        if (currentCallInfo) {
            if (activeSessions.length >= 1) {
                currentCallInfo.style.display = 'block';
            } else {
                currentCallInfo.style.display = 'none';
            }
        }
    } catch (err) {
        console.warn('Failed to update headers after call ended', err);
    }

    // Auto-switch to held call if available
    try {
        setTimeout(async () => { if (typeof autoSwitchToHeldCall === 'function') await autoSwitchToHeldCall(); }, 500);
    } catch (e) { console.warn('Failed to auto-switch after callEnded', e); }

    // Ensure calls list panel is refreshed so disposed sessions disappear immediately.
    try {
        setTimeout(() => {
            try {
                if (typeof refreshCallsListPanelIfVisible === 'function') {
                    refreshCallsListPanelIfVisible();
                } else if (typeof openCallsList === 'function') {
                    const panel = document.getElementById('dialerCallsListPanel');
                    if (panel && panel.classList.contains('calls-list-panel-visible')) openCallsList();
                }
            } catch (_) { }
        }, 90);
    } catch (_) { }
    try {
        setTimeout(() => {
            try { if (typeof window.syncDialerLiveCallState === 'function') window.syncDialerLiveCallState('ringcentral-events:callEnded'); } catch (_) { }
        }, 110);
    } catch (_) { }
});

document.addEventListener('ringcentral:callError', function (e) {
    try {
        const detail = e.detail || {};
        console.error('Call error:', detail);
        const msg = detail.error || 'Unknown call error';
        const needsReload = /registration|webphone.*start|library not loaded/i.test(msg);
        const recoverable = !!detail.recoverable;
        const sessionId = detail.sessionId || null;

        // Check for WebSocket close state error (SIP invalidated)
        const isWebSocketCloseError = /WebSocket.*(?:CLOSING|CLOSED)/i.test(msg);
        if (isWebSocketCloseError) {
            // Remove existing alert if present
            try { const existing = document.getElementById('rc-websocket-close-alert'); if (existing) existing.remove(); } catch (_) { }

            const container = document.createElement('div');
            container.id = 'rc-websocket-close-alert';
            container.className = 'alert alert-danger';
            container.style.position = 'fixed';
            container.style.left = '50%';
            container.style.top = '50%';
            container.style.transform = 'translate(-50%, -50%)';
            container.style.zIndex = 2147483650;
            container.style.minWidth = '400px';
            container.style.boxShadow = '0 4px 20px rgba(0,0,0,0.3)';
            container.innerHTML = `
                <div class="rc-error-center">
                    <div class="rc-error-icon">⚠️</div>
                    <h5 class="rc-error-title"><strong>SIP Connection Lost</strong></h5>
                    <p class="rc-error-message">Your phone connection has been invalidated. Please refresh the page to reconnect.</p>
                    <button id="rc-refresh-sip" class="btn btn-primary btn-lg">
                        <i class="fa fa-refresh"></i> Refresh Page
                    </button>
                </div>`;

            document.body.appendChild(container);

            const refreshBtn = document.getElementById('rc-refresh-sip');
            refreshBtn.onclick = function () { 
                try { 
                    location.reload(); 
                } catch (_) { } 
            };

            return; // Don't process further
        }

        // If recoverable (e.g., RTCPeerConnection closed), show inline alert with Retry
        if (recoverable && sessionId) {
            // Remove existing alert if present
            try { const existing = document.getElementById('rc-call-error-alert'); if (existing) existing.remove(); } catch (_) { }

            const container = document.createElement('div');
            container.id = 'rc-call-error-alert';
            container.className = 'alert alert-warning';
            container.style.position = 'fixed';
            container.style.left = '16px';
            container.style.right = '16px';
            container.style.top = '12px';
            container.style.zIndex = 2147483650;
            container.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <div><strong>Call failed to answer:</strong> ${msg}</div>
                    <div>
                        <button id="rc-retry-answer" class="btn btn-sm btn-primary mr-2">Retry Answer</button>
                        <button id="rc-reload-page" class="btn btn-sm btn-secondary mr-2">Reload Page</button>
                        <button id="rc-dismiss-call-error" class="btn btn-sm btn-light">Dismiss</button>
                    </div>
                </div>`;

            document.body.appendChild(container);

            const retryBtn = document.getElementById('rc-retry-answer');
            const reloadBtn = document.getElementById('rc-reload-page');
            const dismissBtn = document.getElementById('rc-dismiss-call-error');

            retryBtn.onclick = async function () {
                try {
                    retryBtn.disabled = true;
                    retryBtn.textContent = 'Retrying...';
                    if (window.webPhone && typeof webPhone.answerSession === 'function') {
                        await webPhone.answerSession(sessionId);
                        // Success - remove alert and refresh UI
                        try { const el = document.getElementById('rc-call-error-alert'); if (el) el.remove(); } catch (_) { }
                    } else {
                        throw new Error('WebPhone not available to retry');
                    }
                } catch (err) {
                    console.error('Retry answer failed', err);
                    if (typeof showErrorModal === 'function') showErrorModal('Retry failed: ' + (err && err.message ? err.message : err), true);
                } finally {
                    try { retryBtn.disabled = false; retryBtn.textContent = 'Retry Answer'; } catch (_) { }
                }
            };

            reloadBtn.onclick = function () { try { location.reload(); } catch (_) { } };
            dismissBtn.onclick = function () { try { const el = document.getElementById('rc-call-error-alert'); if (el) el.remove(); } catch (_) { } };

            return; // do not call endCall for recoverable answer errors
        }

        // Fallback behavior for non-recoverable errors
        if (needsReload) {
            if (typeof showErrorModal === 'function') showErrorModal('Call error: ' + msg, true);
        } else {
            alert('Call error: ' + msg);
        }
        if (typeof endCall === 'function') endCall();
    } catch (err) {
        console.error('callError handler failed', err);
    }
});

// Global error listener from WebPhone dispatchEvent('error', {error})
document.addEventListener('ringcentral:error', function (e) {
    const msg = e.detail?.error || 'Unexpected error';
    const status = Number(e.detail?.status || 0);
    
    // Check for WebSocket close state error (SIP invalidated)
    const isWebSocketCloseError = /WebSocket.*(?:CLOSING|CLOSED)/i.test(msg);
    if (isWebSocketCloseError) {
        // Remove existing alert if present
        try { const existing = document.getElementById('rc-websocket-close-alert'); if (existing) existing.remove(); } catch (_) { }

        const container = document.createElement('div');
        container.id = 'rc-websocket-close-alert';
        container.className = 'alert alert-danger';
        container.style.position = 'fixed';
        container.style.left = '50%';
        container.style.top = '50%';
        container.style.transform = 'translate(-50%, -50%)';
        container.style.zIndex = 2147483650;
        container.style.minWidth = '400px';
        container.style.boxShadow = '0 4px 20px rgba(0,0,0,0.3)';
        container.innerHTML = `
            <div class="rc-error-center">
                <div class="rc-error-icon">⚠️</div>
                <h5 class="rc-error-title"><strong>SIP Connection Lost</strong></h5>
                <p class="rc-error-message">Your phone connection has been invalidated. Please refresh the page to reconnect.</p>
                <button id="rc-refresh-sip" class="btn btn-primary btn-lg">
                    <i class="fa fa-refresh"></i> Refresh Page
                </button>
            </div>`;

        document.body.appendChild(container);

        const refreshBtn = document.getElementById('rc-refresh-sip');
        refreshBtn.onclick = function () { 
            try { 
                location.reload(); 
            } catch (_) { } 
        };

        return; // Don't process further
    }
    
    const needsReload = /match|library not loaded|Failed to load R-Dialer  WebPhone SDK|token|register/i.test(msg);
    const showReconnect = false;
    const showDisconnect = status === 400 || status === 500 || /webphone token:\s*(400|500)\b/i.test(msg);
    if (typeof showErrorModal === 'function') showErrorModal(msg, needsReload, { showReconnect, showDisconnect });
});

// Handle instance conflict error (user already logged in on another device)
document.addEventListener('ringcentral:instanceConflict', function (e) {
    console.error('🚫 Instance conflict detected:', e.detail?.message);
    const message = e.detail?.message || 'You are already logged in on another device. Please logout from the other device first.';
    if (typeof showErrorModal === 'function') showErrorModal(message, false); // Don't reload on conflict
});

// Global error handler for uncaught WebSocket errors
window.addEventListener('error', function(event) {
    const errorMsg = event.message || event.error?.message || '';
    const isWebSocketCloseError = /WebSocket.*(?:CLOSING|CLOSED)/i.test(errorMsg);
    
    if (isWebSocketCloseError) {
        console.error('🚫 Caught WebSocket close state error:', errorMsg);
        event.preventDefault(); // Prevent default error handling
        
        // Remove existing alert if present
        try { const existing = document.getElementById('rc-websocket-close-alert'); if (existing) existing.remove(); } catch (_) { }

        const container = document.createElement('div');
        container.id = 'rc-websocket-close-alert';
        container.className = 'alert alert-danger';
        container.style.position = 'fixed';
        container.style.left = '50%';
        container.style.top = '50%';
        container.style.transform = 'translate(-50%, -50())';
        container.style.zIndex = 2147483650;
        container.style.minWidth = '400px';
        container.style.boxShadow = '0 4px 20px rgba(0,0,0,0.3)';
        container.innerHTML = `
            <div class="rc-error-center">
                <div class="rc-error-icon">⚠️</div>
                <h5 class="rc-error-title"><strong>SIP Connection Lost</strong></h5>
                <p class="rc-error-message">Your phone connection has been invalidated. Please refresh the page to reconnect.</p>
                <button id="rc-refresh-sip" class="btn btn-primary btn-lg">
                    <i class="fa fa-refresh"></i> Refresh Page
                </button>
            </div>`;

        document.body.appendChild(container);

        const refreshBtn = document.getElementById('rc-refresh-sip');
        refreshBtn.onclick = function () { 
            try { 
                location.reload(); 
            } catch (_) { } 
        };
    }
}, true); // Use capture phase to catch errors early

// Global handler for unhandled promise rejections (catches WebSocket async errors)
window.addEventListener('unhandledrejection', function(event) {
    const errorMsg = event.reason?.message || event.reason || '';
    const isWebSocketCloseError = /WebSocket.*(?:CLOSING|CLOSED)/i.test(errorMsg);
    
    if (isWebSocketCloseError) {
        console.error('🚫 Caught unhandled WebSocket promise rejection:', errorMsg);
        event.preventDefault(); // Prevent default error handling
        
        // Remove existing alert if present
        try { const existing = document.getElementById('rc-websocket-close-alert'); if (existing) existing.remove(); } catch (_) { }

        const container = document.createElement('div');
        container.id = 'rc-websocket-close-alert';
        container.className = 'alert alert-danger';
        container.style.position = 'fixed';
        container.style.left = '50%';
        container.style.top = '50%';
        container.style.transform = 'translate(-50%, -50%)';
        container.style.zIndex = 2147483650;
        container.style.minWidth = '400px';
        container.style.boxShadow = '0 4px 20px rgba(0,0,0,0.3)';
        container.innerHTML = `
            <div class="rc-error-center">
                <div class="rc-error-icon">⚠️</div>
                <h5 class="rc-error-title"><strong>SIP Connection Lost</strong></h5>
                <p class="rc-error-message">Your phone connection has been invalidated. Please refresh the page to reconnect.</p>
                <button id="rc-refresh-sip" class="btn btn-primary btn-lg">
                    <i class="fa fa-refresh"></i> Refresh Page
                </button>
            </div>`;

        document.body.appendChild(container);

        const refreshBtn = document.getElementById('rc-refresh-sip');
        refreshBtn.onclick = function () { 
            try { 
                location.reload(); 
            } catch (_) { } 
        };
    }
});

function rcExtractIncomingNumber(detail) {
    const d = detail || {};
    const session = d.session || {};
    const from = d.from || d.caller || session.from || session.caller || session.remote || {};
    const value = (
        d.callerNumber ||
        from.phoneNumber ||
        from.number ||
        from.phone ||
        session.remoteNumber ||
        session.fromNumber ||
        ''
    );
    return String(value || '').trim();
}

function rcBlockDiagLog(message, payload) {
    try {
        const prefix = '[RC BlockDiag] ';
        if (payload !== undefined) {
            console.info(prefix + message, payload);
            return;
        }
        console.info(prefix + message);
    } catch (_) { }
}

async function rcAuditBlockedIncoming(detail) {
    try {
        rcBlockDiagLog('Incoming detail received for block check', detail || {});
        const number = rcExtractIncomingNumber(detail);
        if (!number) {
            rcBlockDiagLog('No caller number extracted; skipping block check');
            return false;
        }
        rcBlockDiagLog('Extracted incoming caller number', { number: number });

        let check = null;
        const api = window.rcBlockedNumbersApi;
        if (api && typeof api.checkBlockedNumber === 'function') {
            check = await api.checkBlockedNumber(number);
        } else {
            const checkUrl = (typeof rcRoute === 'function')
                ? rcRoute('ringcentral.api.blocked-numbers.check')
                : (window.RC_ROUTES && window.RC_ROUTES['ringcentral.api.blocked-numbers.check']) || '';
            if (!checkUrl) return false;
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const res = await fetch(checkUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({ number })
            });
            const body = await res.json().catch(() => null);
            check = { ok: !!(res.ok && body && body.success), data: body };
        }

        const blocked = !!(
            check
            && check.ok
            && (
                (check.data && check.data.blocked)
                || (check.data && check.data.data && check.data.data.blocked)
            )
        );
        rcBlockDiagLog('Block check response', { check: check, blocked: blocked });
        if (!blocked) {
            rcBlockDiagLog('Number is not blocked; allowing incoming call', { number: number });
            return false;
        }

        rcBlockDiagLog('Blocked caller detected at dialer check. Suppressing incoming UI for blocked number.', {
            number: number
        });
        return true;
    } catch (err) {
        console.warn('Blocked incoming check failed', err);
        rcBlockDiagLog('Blocked incoming check threw error', { error: String(err && err.message ? err.message : err) });
        return false;
    }
}

document.addEventListener('ringcentral:incomingCall', async function (e) {
    rcLog('Incoming call event received', e.detail);

    let blockedIncoming = false;
    try {
        blockedIncoming = await rcAuditBlockedIncoming(e.detail || {});
    } catch (_) { }
    if (blockedIncoming) {
        rcLog('Blocked incoming call suppressed from dialer queue/UI');
        return;
    }
    
    // Broadcast incoming call event to detached dialer window
    try {
        if (window.dialerDetachedWindow && !window.dialerDetachedWindow.closed) {
            rcLog('Broadcasting incomingCall event to detached window');
            window.dialerDetachedWindow.postMessage({
                type: 'ringcentral:incomingCall',
                detail: e.detail
            }, window.location.origin);
        }
    } catch (_) { }

    // Update incoming call UI through queue manager and show in dialer modal
    try {
        if (typeof window.rcEnqueueIncomingCall === 'function') {
            window.rcEnqueueIncomingCall(e.detail || {});
        } else {
            console.warn('rcEnqueueIncomingCall is not available');
        }
    } catch (err) { console.warn('Failed to queue incoming call UI', err); }

    // Clear legacy modal handlers so they don't interfere
    try { const ans = document.getElementById('answerCallBtn'); if (ans) ans.onclick = null; } catch (_) { }
    try { const rej = document.getElementById('rejectCallBtn'); if (rej) rej.onclick = null; } catch (_) { }
});

// Graceful fallback: if SDK event delivery is degraded/missed, recover incoming calls from active sessions.
(function initIncomingSessionRecoveryMonitor() {
    if (window._rcIncomingRecoveryMonitorReady) return;
    window._rcIncomingRecoveryMonitorReady = true;

    const seenIncomingIds = new Set();
    const clearSeenForMissingSessions = (sessions) => {
        const activeIds = new Set(
            (sessions || [])
                .map((s) => String(s?.id || s?.sessionId || s?.callId || s?.partyId || s?.index || ''))
                .filter(Boolean)
        );
        Array.from(seenIncomingIds).forEach((sid) => {
            if (!activeIds.has(sid)) {
                seenIncomingIds.delete(sid);
            }
        });
    };

    const isIncomingLike = (session) => {
        const direction = String(session?.direction || '').toLowerCase();
        const state = String(session?.state || session?.status || '').toLowerCase();
        const terminalStates = ['terminated', 'disposed', 'ended', 'failed', 'rejected', 'cancelled', 'canceled'];
        const incomingStates = ['ringing', 'incoming', 'alerting', 'early', 'invite', 'offering', 'pending'];
        const acceptedStates = ['active', 'connected', 'established', 'confirmed', 'inprogress', 'answered', 'hold', 'held', 'proceeding'];

        if (terminalStates.some(flag => state.includes(flag))) return false;
        if (session?.hold === true || session?.onHold === true || session?._onHold === true) return false;
        if (acceptedStates.some(flag => state.includes(flag))) return false;

        if (incomingStates.some(flag => state.includes(flag))) return true;
        // Direction-only fallback for SDKs that don't expose a call state yet.
        if ((direction.includes('incoming') || direction.includes('inbound')) && !state) return true;
        return false;
    };

    const tick = () => {
        try {
            if (!window.webPhone || typeof webPhone.listSessions !== 'function') return;
            const sessions = webPhone.listSessions() || [];
            clearSeenForMissingSessions(sessions);

            sessions.forEach((session) => {
                if (!isIncomingLike(session)) return;
                const sid = String(session?.id || session?.sessionId || session?.callId || session?.partyId || session?.index || '');
                if (!sid) return;
                if (seenIncomingIds.has(sid)) return;
                if (typeof window.rcIsIncomingSessionQueued === 'function' && window.rcIsIncomingSessionQueued(sid)) {
                    seenIncomingIds.add(sid);
                    return;
                }

                seenIncomingIds.add(sid);

                const incomingDetail = {
                    session: session,
                    sessionId: sid,
                    callerNumber: session?.remoteNumber || session?.fromNumber || 'Unknown',
                    toNumber: session?.localNumber || session?.toNumber || 'Your Number'
                };

                rcAuditBlockedIncoming(incomingDetail)
                    .then((blockedIncoming) => {
                        if (blockedIncoming) {
                            rcLog('Blocked incoming recovered session suppressed from dialer queue/UI', { sessionId: sid });
                            return;
                        }
                        if (typeof window.rcEnqueueIncomingCall === 'function') {
                            window.rcEnqueueIncomingCall(incomingDetail);
                        }
                    })
                    .catch(() => {});
            });
        } catch (_) { }
    };

    setInterval(tick, 1500);
    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'visible') tick();
    });
    setTimeout(tick, 1200);
})();

// Sync mic state from events (guard DOM elements)
document.addEventListener('ringcentral:muted', function () {
    try {
        window.isMicMuted = true;
        const toggleBtn = document.getElementById('toggleMicBtn');
        const micStatusEl = document.getElementById('micStatus');
        if (toggleBtn) {
            toggleBtn.textContent = '🎤 Unmute Microphone';
            toggleBtn.classList.remove('btn-warning');
            toggleBtn.classList.add('btn-danger');
        }
        if (micStatusEl) micStatusEl.textContent = '🔇 Muted';
    } catch (e) { console.warn('muted handler error', e); }
});

document.addEventListener('ringcentral:unmuted', function () {
    try {
        window.isMicMuted = false;
        const toggleBtn = document.getElementById('toggleMicBtn');
        const micStatusEl = document.getElementById('micStatus');
        if (toggleBtn) {
            toggleBtn.textContent = '🎤 Mute Microphone';
            toggleBtn.classList.remove('btn-danger');
            toggleBtn.classList.add('btn-warning');
        }
        if (micStatusEl) micStatusEl.textContent = '✅ Active';
    } catch (e) { console.warn('unmuted handler error', e); }
});

document.addEventListener('ringcentral:speakerToggled', function (e) {
    const enabled = e.detail?.enabled;
    window.isSpeakerMuted = !enabled;
    const speakerStatusEl = document.getElementById('speakerStatus');
    if (speakerStatusEl) {
        const label = window.currentOutputDeviceLabel || 'Default system output';
        speakerStatusEl.textContent = enabled ? '✅ Active (' + label + ')' : '🔇 Muted (' + label + ')';
    }
});

// Session state listeners for mute/unmute per session
document.addEventListener('ringcentral:muted', function (e) {
    try {
        const session = e.detail?.session;
        const sid = session && (session.id || session.sessionId || session.partyId || session.callId);
        if (!sid) return;
        const btn = document.querySelector(`button[data-mute-btn='${sid}']`);
        if (btn) btn.textContent = 'Unmute';

        // Update dialer mute button if this is the current call
        if (sid === window._dialerCurrentSessionId) {
            const dialerMuteBtn = document.getElementById('dialerMuteBtn');
            if (dialerMuteBtn) {
                dialerMuteBtn.style.background = '#ff6b6b';
                dialerMuteBtn.style.color = 'white';
                dialerMuteBtn.innerHTML = '<i class="fa fa-microphone-slash"></i>';
                try { const lbl = document.getElementById('dialerMuteLabel'); if (lbl) lbl.textContent = 'Unmute'; } catch (_) { }
            }
        }
    } catch (err) { console.warn('muted handler failed', err); }
});

document.addEventListener('ringcentral:unmuted', function (e) {
    try {
        const session = e.detail?.session;
        const sid = session && (session.id || session.sessionId || session.partyId || session.callId);
        if (!sid) return;
        const btn = document.querySelector(`button[data-mute-btn='${sid}']`);
        if (btn) btn.textContent = 'Mute';

        // Update dialer mute button if this is the current call
        if (sid === window._dialerCurrentSessionId) {
            const dialerMuteBtn = document.getElementById('dialerMuteBtn');
            if (dialerMuteBtn) {
                dialerMuteBtn.style.background = '';
                dialerMuteBtn.style.color = '';
                dialerMuteBtn.innerHTML = '<i class="fa fa-microphone"></i>';
                try { const lbl = document.getElementById('dialerMuteLabel'); if (lbl) lbl.textContent = 'Mute'; } catch (_) { }
            }
        }
    } catch (err) { console.warn('unmuted handler failed', err); }
});

// Dialer button state update on hold/resume
document.addEventListener('ringcentral:heldStatusChanged', function (e) {
    try {
        const sid = e.detail?.sessionId || e.detail?.id;
        if (!sid) return;

        const isOnHold = e.detail?.onHold || e.detail?.held || false;

        // Update Active Calls table hold button text and status
        try {
            const row = document.querySelector(`tr[data-session-id="${sid}"]`);
            if (row) {
                const holdBtn = Array.from(row.querySelectorAll('button')).find(b => /Hold|Resume/i.test(b.textContent || ''));
                if (holdBtn) {
                    holdBtn.textContent = isOnHold ? 'Resume' : 'Hold';
                }
                // Update status text to show On Hold state
                const statusEl = row.querySelector('.rc-status');
                if (statusEl) {
                    const statusText = statusEl.textContent || '';
                    if (isOnHold && !statusText.includes('(On Hold)')) {
                        statusEl.textContent = statusText + ' (On Hold)';
                    } else if (!isOnHold && statusText.includes('(On Hold)')) {
                        statusEl.textContent = statusText.replace(' (On Hold)', '');
                    }
                }
            }
        } catch (_) { }

        // Update dialer hold button if this is the current call
        if (sid === window._dialerCurrentSessionId) {
            const holdBtn = document.getElementById('dialerHoldBtn');
            if (!holdBtn) return;

            if (isOnHold) {
                holdBtn.style.background = '#ff9800';
                holdBtn.style.color = 'white';
                holdBtn.innerHTML = '<i class="fa fa-play"></i>';
                try { const lbl = document.getElementById('dialerHoldLabel'); if (lbl) lbl.textContent = 'Resume'; } catch (_) { }
            } else {
                holdBtn.style.background = '';
                holdBtn.style.color = '';
                holdBtn.innerHTML = '<i class="fa fa-pause"></i>';
                try { const lbl = document.getElementById('dialerHoldLabel'); if (lbl) lbl.textContent = 'Hold'; } catch (_) { }
            }
        }
    } catch (err) { console.warn('heldStatusChanged handler failed', err); }
});

// Session target management - update mic test modal when sessions change
function refreshMicTargetSessionSelect() {
    try {
        const modal = document.getElementById('micTestModal');
        if (!modal) return;
        const visible = (modal.classList && modal.classList.contains('show')) || modal.style.display === 'block';
        if (!visible) return;
        const targetSel = document.getElementById('micTargetSessionSelect');
        if (!targetSel) return;
        const cur = targetSel.value;
        targetSel.innerHTML = '<option value="">(Select active call)</option>';
        if (window.webPhone && typeof webPhone.listSessions === 'function') {
            const sessions = webPhone.listSessions() || [];
            sessions.forEach((s, idx) => {
                const id = s.id ?? s.index ?? idx;
                const remoteNumber = s.remoteNumber || 'Unknown';
                const maskedRemote = (typeof maskPhoneNumber === 'function')
                    ? maskPhoneNumber(remoteNumber)
                    : remoteNumber;
                const label = `${maskedRemote} (${s.direction || 'call'})`;
                const opt = document.createElement('option'); opt.value = id; opt.textContent = label; targetSel.appendChild(opt);
            });
            if (cur && Array.from(targetSel.options).some(o => o.value === cur)) {
                targetSel.value = cur;
                try { if (typeof onMicTargetChanged === 'function') onMicTargetChanged(cur); } catch (_) { }
            } else if (sessions.length) {
                const firstId = sessions[0].id ?? sessions[0].index ?? '';
                targetSel.value = firstId;
                try { if (typeof onMicTargetChanged === 'function') onMicTargetChanged(firstId); } catch (_) { }
            }
        }
        targetSel.onchange = function () { try { if (typeof onMicTargetChanged === 'function') onMicTargetChanged(this.value); } catch (_) { } };
    } catch (e) { console.warn('refreshMicTargetSessionSelect failed', e); }
}

// Wire up session management to all relevant events
['callStarted', 'incomingCall', 'conferenceStarted', 'callEnded', 'callConnected', 'callAnswered', 'holdStarted', 'holdEnded', 'swapped', 'merged', 'mergeFailed', 'mergeNotSupported', 'muteToggled'].forEach(ev => {
    document.addEventListener('ringcentral:' + ev, refreshMicTargetSessionSelect);
});

// UI update intervals for call duration displays
setInterval(function () {
    try {
        const els = document.querySelectorAll('.rc-duration');
        els.forEach(el => {
            const start = el.getAttribute('data-start');
            if (start && typeof formatDuration === 'function') el.textContent = formatDuration(parseInt(start));
        });
    } catch (e) { }
}, 1000);

// Audio autoplay handler - resume audio on first user gesture
document.addEventListener('click', function rc_resumeAudioOnFirstGesture() {
    try {
        if (window.webPhone) {
            try { if (webPhone._ringtoneCtx && typeof webPhone._ringtoneCtx.resume === 'function') webPhone._ringtoneCtx.resume().catch(() => { }); } catch (_) { }
            try { if (webPhone.ringtoneAudio) webPhone.ringtoneAudio.play().catch(() => { }); } catch (_) { }
        }
        const btn = document.getElementById('rc-enable-sound');
        if (btn && btn.parentNode) btn.parentNode.removeChild(btn);
    } catch (e) { console.warn('rc_resumeAudioOnFirstGesture failed', e); }
}, { once: true });
