    function getSessionIdCandidates(session) {
        if (!session) return [];
        return [session.id, session.sessionId, session.callId, session.partyId, session.index]
            .filter(v => v !== undefined && v !== null && String(v) !== '')
            .map(v => String(v));
    }

    function getSessionStateLower(session) {
        return String(session?.state || session?.status || '').toLowerCase();
    }

    function cleanupEndedSessionRegistry(maxAgeMs = 30 * 60 * 1000) {
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

    function isSessionEndedByRegistry(session) {
        try {
            cleanupEndedSessionRegistry();
            const registry = window._rcEndedSessionIds || {};
            const ids = getSessionIdCandidates(session);
            return ids.some((id) => !!registry[String(id)]);
        } catch (_) {
            return false;
        }
    }

    function isSessionTerminalForDialer(session) {
        const state = getSessionStateLower(session);
        const terminalStates = ['disposed', 'terminated', 'ended', 'disconnected', 'failed', 'rejected', 'cancelled', 'canceled'];
        if (terminalStates.some((flag) => state.includes(flag))) return true;
        if (isSessionEndedByRegistry(session)) return true;
        return false;
    }

    function isSessionIncomingForDialer(session) {
        if (!session) return false;
        const currentId = window._dialerCurrentSessionId ? String(window._dialerCurrentSessionId) : '';
        const sessionIds = getSessionIdCandidates(session);
        const direction = String(session?.direction || session?.callDirection || session?._rcDirection || '').toLowerCase();
        const state = getSessionStateLower(session);
        const acceptedStates = ['active', 'connected', 'established', 'confirmed', 'inprogress', 'answered', 'hold', 'held', 'proceeding'];

        if (session?._rcOriginatedLocally === true) return false;
        if (direction.includes('outbound') || direction.includes('outgoing')) return false;
        if (currentId && sessionIds.includes(currentId)) return false;
        if (session.hold === true || session.onHold === true || session._onHold === true) return false;
        if (isConferenceLikeSession(session)) return false;
        if (acceptedStates.some((flag) => state.includes(flag))) return false;
        if (session._rcQueuedIncoming === true) return true;

        const incomingStates = ['incoming', 'early', 'ringing', 'pending', 'alerting', 'invite', 'offering'];
        if (direction.includes('inbound') || direction.includes('incoming')) return true;
        return incomingStates.some((flag) => state.includes(flag));
    }

    function getQueuedIncomingCallItems() {
        try {
            return Array.isArray(window._incomingCallQueue) ? window._incomingCallQueue : [];
        } catch (_) {
            return [];
        }
    }

    function getQueuedIncomingIds(item) {
        if (!item) return [];
        const ids = Array.isArray(item.sessionIds) && item.sessionIds.length
            ? item.sessionIds
            : [item.sessionId, item.key];
        return ids
            .filter(v => v !== undefined && v !== null && String(v) !== '')
            .map(v => String(v));
    }

    function isSessionInIncomingQueue(session) {
        const sessionIds = getSessionIdCandidates(session);
        if (!sessionIds.length) return false;
        const currentId = window._dialerCurrentSessionId ? String(window._dialerCurrentSessionId) : '';
        const direction = String(session?.direction || session?.callDirection || session?._rcDirection || '').toLowerCase();
        const state = getSessionStateLower(session);
        const acceptedStates = ['active', 'connected', 'established', 'confirmed', 'inprogress', 'answered', 'hold', 'held', 'proceeding'];
        if (session?._rcOriginatedLocally === true) return false;
        if (direction.includes('outbound') || direction.includes('outgoing')) return false;
        if (currentId && sessionIds.includes(currentId)) return false;
        if (session.hold === true || session.onHold === true || session._onHold === true) return false;
        if (isConferenceLikeSession(session)) return false;
        if (acceptedStates.some((flag) => state.includes(flag))) return false;
        return getQueuedIncomingCallItems().some((item) => {
            const queuedIds = getQueuedIncomingIds(item);
            return queuedIds.some((id) => sessionIds.includes(id));
        });
    }

    function shouldHideQueuedIncomingInCallsList(item) {
        try {
            const currentId = window._dialerCurrentSessionId ? String(window._dialerCurrentSessionId) : '';
            const queuedIds = getQueuedIncomingIds(item);
            if (currentId && queuedIds.includes(currentId)) return true;

            const sessions = window.webPhone?.listSessions?.() || [];
            const matched = sessions.find((session) => {
                const sessionIds = getSessionIdCandidates(session);
                return sessionIds.some((id) => queuedIds.includes(id));
            });
            if (!matched) return false;

            const direction = String(matched?.direction || matched?.callDirection || matched?._rcDirection || '').toLowerCase();
            if (matched?._rcOriginatedLocally === true) return true;
            if (direction.includes('outbound') || direction.includes('outgoing')) return true;
            return currentId && getSessionIdCandidates(matched).includes(currentId);
        } catch (_) {
            return false;
        }
    }

    function createQueuedIncomingSession(item, index) {
        const ids = getQueuedIncomingIds(item);
        const sessionId = ids[0] || `queued-incoming-${index}`;
        return {
            id: sessionId,
            sessionId: sessionId,
            sessionIds: ids,
            state: 'incoming',
            direction: 'inbound',
            remoteName: item?.callerName || item?.callerNumber || 'Incoming call',
            remoteNumber: item?.callerNumber || 'Unknown',
            startedAt: item?.createdAt || Date.now(),
            _ringingStartedAt: item?.createdAt || Date.now(),
            _rcQueuedIncoming: true
        };
    }

    function isSessionIgnored(session) {
        const ignored = new Set((window._ignoredCallSessions || []).map(String));
        const ids = getSessionIdCandidates(session);
        return ids.some(id => ignored.has(id));
    }

    function isConferenceLikeSession(session) {
        if (!session) return false;
        const direction = String(session?.direction || '').toLowerCase();
        const remote = String(session?.remoteNumber || session?.remoteName || '').toLowerCase();
        if (session?.isVirtual === true) return true;
        if (direction.includes('conference')) return true;
        if (remote === 'conference') return true;
        if (remote.startsWith('conf_')) return true;
        return false;
    }

    function getSessionMetaSafe(session) {
        try {
            if (!window.webPhone || typeof webPhone.getSessionMeta !== 'function' || !session) return {};
            return webPhone.getSessionMeta(session) || {};
        } catch (_) {
            return {};
        }
    }

    function sanitizeConferenceDisplayValue(value) {
        const text = String(value || '').trim();
        if (!text) return '';
        if (/^conf_/i.test(text)) return '';
        if (/^sip:conf_/i.test(text)) return '';
        if (/^virtual-merge-conf-/i.test(text)) return '';
        return text;
    }

    function maskDialerNumberStrict(value) {
        if (typeof window.rcMaskPhoneNumber === 'function') {
            return window.rcMaskPhoneNumber(value, {
                visibleDigits: 4,
                minMaskChars: 5,
                emptyFallback: '*****'
            });
        }
        if (typeof maskPhoneNumber === 'function') {
            const fallbackMasked = maskPhoneNumber(value);
            return fallbackMasked || '*****';
        }
        const digits = String(value || '').replace(/\D+/g, '');
        if (!digits) return '*****';
        return `*****${digits.slice(-4)}`;
    }

    function maskDialerDisplayNumber(value, overrides = null) {
        if (typeof window.rcMaskPhoneNumber === 'function') {
            return window.rcMaskPhoneNumber(value, overrides);
        }
        if (typeof maskPhoneNumber === 'function') {
            return maskPhoneNumber(value, overrides);
        }
        return value;
    }

    function getConferenceParticipants(session) {
        const meta = getSessionMetaSafe(session);
        const fromSession = Array.isArray(session?.participants) ? session.participants : [];
        const fromMeta = Array.isArray(meta?.participants) ? meta.participants : [];
        const source = fromSession.length ? fromSession : fromMeta;
        const participants = [];
        const seen = new Set();

        source.forEach((participant, index) => {
            if (!participant) return;
            const number = sanitizeConferenceDisplayValue(participant.phoneNumber || participant.number || '');
            const name = String(participant.name || participant.displayName || '').trim() || `Participant ${index + 1}`;
            const role = String(participant.role || '').trim() || null;
            const key = `${name}|${number}|${role || ''}`;
            if (seen.has(key)) return;
            seen.add(key);
            participants.push({
                name,
                number: number || null,
                role,
                sessionId: String(participant.sessionId || '').trim() || null,
                callSessionId: String(participant.callSessionId || '').trim() || null,
                partyId: String(participant.partyId || '').trim() || null
            });
        });

        return participants;
    }

    function getCurrentConferenceSession() {
        try {
            const liveSessions = getDialerLiveSessions().filter(isSessionAcceptedForDuring);
            const currentId = String(window._dialerCurrentSessionId || '').trim();
            if (currentId) {
                const current = liveSessions.find((s) => {
                    const ids = getSessionIdCandidates(s);
                    return ids.includes(currentId);
                }) || null;
                if (current && isConferenceLikeSession(current)) return current;
            }

            return liveSessions.find((s) => isConferenceLikeSession(s)) || null;
        } catch (_) {
            return null;
        }
    }

    function refreshConferenceParticipantsButton() {
        try {
            const btn = document.getElementById('dialerParticipantsBtn');
            const countEl = document.getElementById('dialerParticipantsCount');
            if (!btn || !countEl) return;

            const conferenceSession = getCurrentConferenceSession();
            if (!conferenceSession) {
                btn.style.display = 'none';
                countEl.textContent = '0';
                return;
            }

            const display = getSessionDisplayData(conferenceSession);
            const count = Math.max(1, Number(display.participantCount || display.participants?.length || 0));
            countEl.textContent = String(count);
            btn.title = `Participants (${count})`;
            btn.style.display = 'inline-flex';
        } catch (_) { }
    }

    function openConferenceParticipantsForCurrent() {
        try {
            const conferenceSession = getCurrentConferenceSession();
            if (!conferenceSession) return;
            const sessionId = getSessionIdCandidates(conferenceSession)[0] || null;
            if (!sessionId) return;
            openConferenceParticipants(sessionId);
        } catch (e) {
            console.warn('openConferenceParticipantsForCurrent failed', e);
        }
    }

    function getSessionDisplayData(session) {
        const meta = getSessionMetaSafe(session);
        const isConference = isConferenceLikeSession(session);

        if (isConference) {
            const participants = getConferenceParticipants(session);
            const numbers = participants
                .map((participant) => sanitizeConferenceDisplayValue(participant.number))
                .filter(Boolean);
            const maskedNumbers = numbers.map((number) => maskDialerNumberStrict(number));
            const participantCount = Number(
                session?.participantCount
                || meta?.participantCount
                || participants.length
                || (numbers.length > 0 ? numbers.length : 0)
            );
            const summary = maskedNumbers.length > 0
                ? `${maskedNumbers.slice(0, 2).join(', ')}${maskedNumbers.length > 2 ? ` +${maskedNumbers.length - 2} more` : ''}`
                : `${Math.max(1, participantCount)} participant${Math.max(1, participantCount) === 1 ? '' : 's'}`;
            return {
                isConference: true,
                name: summary,
                number: summary,
                myCallNumber: summary,
                participantCount: Math.max(1, participantCount),
                participants
            };
        }

        const remoteNumber = sanitizeConferenceDisplayValue(
            meta?.remoteNumber
            || session?.remoteNumber
            || session?.remoteName
            || 'Unknown'
        ) || '';
        const maskedRemoteNumber = maskDialerNumberStrict(remoteNumber);
        return {
            isConference: false,
            name: maskedRemoteNumber,
            number: maskedRemoteNumber,
            myCallNumber: maskedRemoteNumber,
            participantCount: 0,
            participants: []
        };
    }

    function isSessionAcceptedForDuring(session) {
        if (!session) return false;
        const state = getSessionStateLower(session);
        const activeStates = ['active', 'connected', 'established', 'confirmed', 'inprogress', 'answered', 'hold', 'held', 'proceeding'];

        if (isSessionTerminalForDialer(session)) return false;
        if (isSessionIgnored(session)) return false;
        if (isSessionIncomingForDialer(session)) return false;
        if (isConferenceLikeSession(session)) return true;
        if (session.hold === true || session.onHold === true || session._onHold === true) return true;
        if (activeStates.some((x) => state.includes(x))) return true;

        return false;
    }

    function getDialerLiveSessions(sourceSessions = null) {
        const sessions = Array.isArray(sourceSessions)
            ? sourceSessions
            : (window.webPhone?.listSessions?.() || []);
        const seen = new Set();
        const liveSessions = [];

        sessions.forEach((session) => {
            if (!session) return;
            if (isSessionIgnored(session)) return;
            if (isSessionTerminalForDialer(session)) return;

            const incoming = isSessionIncomingForDialer(session) || isSessionInIncomingQueue(session);
            const accepted = isSessionAcceptedForDuring(session);
            if (!incoming && !accepted) return;

            const ids = getSessionIdCandidates(session);
            const dedupeKey = ids.length
                ? ids.slice().sort().join('|')
                : `${session.remoteNumber || session.remoteName || 'unknown'}|${getSessionStateLower(session)}`;

            if (seen.has(dedupeKey)) return;
            seen.add(dedupeKey);
            liveSessions.push(session);
        });

        getQueuedIncomingCallItems().forEach((item, index) => {
            if (shouldHideQueuedIncomingInCallsList(item)) return;
            const queuedIds = getQueuedIncomingIds(item);
            const dedupeKey = queuedIds.length
                ? queuedIds.slice().sort().join('|')
                : `queued-incoming-${index}`;
            if (seen.has(dedupeKey)) return;
            seen.add(dedupeKey);
            liveSessions.push(createQueuedIncomingSession(item, index));
        });

        return liveSessions;
    }

    function pickDialerSessionByPriority(sessions) {
        if (!Array.isArray(sessions) || !sessions.length) return null;
        const nonHeld = sessions.filter((s) => !(s.hold || s.onHold || s._onHold));
        const held = sessions.filter((s) => !!(s.hold || s.onHold || s._onHold));
        const sortByRecent = (a, b) => {
            const timeA = a.startedAt || a.createdAt || a._ringingStartedAt || a.startedTimestamp || 0;
            const timeB = b.startedAt || b.createdAt || b._ringingStartedAt || b.startedTimestamp || 0;
            return timeB - timeA;
        };
        nonHeld.sort(sortByRecent);
        held.sort(sortByRecent);
        return nonHeld[0] || held[0] || null;
    }

    function setDialerForceDuringWindow(ms = 12000, reason = 'unknown', sessionId = null) {
        try {
            const ttl = Math.max(0, Number(ms) || 0);
            window._dialerForceDuringUntil = Date.now() + ttl;
            if (sessionId) {
                window._dialerForceSessionId = String(sessionId);
            }
            rcInfo('[DIALER UI] Force during-call window set', {
                reason,
                ttlMs: ttl,
                sessionId: sessionId || null
            });
        } catch (_) { }
    }

    function isDialerForceDuringActive() {
        try {
            const until = Number(window._dialerForceDuringUntil || 0);
            return until > Date.now();
        } catch (_) {
            return false;
        }
    }

    function syncDialerLiveCallState(reason = 'unknown') {
        try {
            if (!window.webPhone || typeof webPhone.listSessions !== 'function') return;

            const liveSessions = getDialerLiveSessions();
            const acceptedSessions = liveSessions.filter(isSessionAcceptedForDuring);
            const currentId = window._dialerCurrentSessionId ? String(window._dialerCurrentSessionId) : '';
            const currentAcceptedSession = currentId
                ? acceptedSessions.find((s) => getSessionIdCandidates(s).includes(currentId))
                : null;
            const targetSession = currentAcceptedSession || pickDialerSessionByPriority(acceptedSessions);

            if (targetSession) {
                const targetId = getSessionIdCandidates(targetSession)[0];
                if (targetId) {
                    window._dialerCurrentSessionId = targetId;
                    const duringSection = document.getElementById('dialerDuringCallSection');
                    if (duringSection && duringSection.style.display === 'none') {
                        switchDialerToDuringCall(targetId);
                    } else {
                        updateDialerCallInfo(targetId);
                        updateMyCallSection();
                        updateMultipleCallsDisplay();
                        updateCallIndicators();
                        startDialerCallTimer();
                    }
                }
            } else {
                if (isDialerForceDuringActive()) {
                    const beforeSection = document.getElementById('dialerBeforeCallSection');
                    const duringSection = document.getElementById('dialerDuringCallSection');
                    if (beforeSection) beforeSection.style.display = 'none';
                    if (duringSection) duringSection.style.display = 'block';
                    updateCallIndicators();
                    rcInfo('[DIALER UI] Keeping during-call section visible during merge/conference stabilization', {
                        reason,
                        forceUntil: window._dialerForceDuringUntil || null,
                        forcedSessionId: window._dialerForceSessionId || null
                    });
                    return;
                }

                window._dialerCurrentSessionId = null;
                stopDialerCallTimer();
                const beforeSection = document.getElementById('dialerBeforeCallSection');
                const duringSection = document.getElementById('dialerDuringCallSection');
                if (duringSection) duringSection.style.display = 'none';
                if (beforeSection) beforeSection.style.display = 'block';
                updateCallIndicators();
            }

            try {
                if (window._dialerSessionStartTimes && typeof window._dialerSessionStartTimes === 'object') {
                    const liveIds = new Set(liveSessions.flatMap((s) => getSessionIdCandidates(s)));
                    Object.keys(window._dialerSessionStartTimes).forEach((sid) => {
                        if (!liveIds.has(String(sid))) {
                            delete window._dialerSessionStartTimes[sid];
                        }
                    });
                }
            } catch (_) { }
        } catch (e) {
            console.warn('syncDialerLiveCallState failed', e);
        }
    }
    window.syncDialerLiveCallState = syncDialerLiveCallState;

    function findAcceptedSessionById(sessionId) {
        if (!window.webPhone || typeof webPhone.listSessions !== 'function') return null;
        const sid = sessionId ? String(sessionId) : '';
        if (!sid) return null;
        const sessions = webPhone.listSessions() || [];
        return sessions.find((s) => {
            const ids = getSessionIdCandidates(s);
            return ids.includes(sid) && isSessionAcceptedForDuring(s);
        }) || null;
    }

    function ensureDuringCallSectionForAnyActiveSession(reason = 'unknown') {
        try {
            if (!window.webPhone || typeof webPhone.listSessions !== 'function') return;

            const accepted = getActiveDialerSessions();
            const forcedSessionId = window._dialerForceSessionId ? String(window._dialerForceSessionId) : '';
            let targetSession = forcedSessionId
                ? accepted.find(function (s) {
                    return getSessionIdCandidates(s).includes(forcedSessionId);
                }) || null
                : null;
            if (!targetSession) {
                targetSession = pickDialerSessionByPriority(accepted);
            }

            if (!targetSession) {
                const fallback = (webPhone.listSessions() || []).find(function (s) {
                    if (!s) return false;
                    if (isSessionTerminalForDialer(s)) return false;
                    if (isSessionIgnored(s)) return false;
                    if (isSessionIncomingForDialer(s)) return false;
                    return isConferenceLikeSession(s);
                }) || null;
                targetSession = fallback;
            }

            if (!targetSession) return;

            const targetId = getSessionIdCandidates(targetSession)[0] || null;
            if (!targetId) return;

            const duringSection = document.getElementById('dialerDuringCallSection');
            const beforeSection = document.getElementById('dialerBeforeCallSection');
            if (beforeSection) beforeSection.style.display = 'none';
            if (duringSection) duringSection.style.display = 'block';

            switchDialerToDuringCall(targetId);
            setDialerForceDuringWindow(15000, reason, targetId);
            rcInfo('[DIALER UI] Ensured during-call section for active/conference session', {
                reason,
                targetId
            });
        } catch (_) { }
    }

    // Activate the next available accepted call after a call ends.
    async function activateNextAvailableCall() {
        if (!window.webPhone || typeof webPhone.listSessions !== 'function') return;
        const sessions = window.webPhone.listSessions() || [];
        const validSessions = sessions.filter(isSessionAcceptedForDuring);

        if (!validSessions.length) {
            if (isDialerForceDuringActive()) {
                const beforeSection = document.getElementById('dialerBeforeCallSection');
                const duringSection = document.getElementById('dialerDuringCallSection');
                if (beforeSection) beforeSection.style.display = 'none';
                if (duringSection) duringSection.style.display = 'block';
                return;
            }
            window._dialerCurrentSessionId = null;
            switchDialerToBeforeCall();
            const hasQueuedIncoming = Array.isArray(window._incomingCallQueue) && window._incomingCallQueue.length > 0;
            if (hasQueuedIncoming && typeof window.rcRenderIncomingQueue === 'function') {
                window.rcRenderIncomingQueue({ focusDialer: false, inlineDuring: false });
            }
            return;
        }

        // Prefer non-held calls, then held calls.
        const nonHeld = validSessions.filter(s => !(s.hold || s.onHold || s._onHold));
        const held = validSessions.filter(s => s.hold || s.onHold || s._onHold);
        const sortByRecent = (a, b) => {
            const timeA = a.startedAt || a.createdAt || a._ringingStartedAt || 0;
            const timeB = b.startedAt || b.createdAt || b._ringingStartedAt || 0;
            return timeB - timeA;
        };
        nonHeld.sort(sortByRecent);
        held.sort(sortByRecent);
        const nextSession = nonHeld[0] || held[0];
        if (!nextSession) {
            window._dialerCurrentSessionId = null;
            switchDialerToBeforeCall();
            return;
        }

        const nextId = nextSession.id || nextSession.index || nextSession.sessionId;
        if ((nextSession.hold || nextSession.onHold || nextSession._onHold) && typeof webPhone.toggleHold === 'function') {
            await webPhone.toggleHold(nextId).catch(() => { });
        }
        window._dialerCurrentSessionId = nextId;
        switchDialerToDuringCall(nextId);
    }
    // ===== START: DIALER MODAL HELPERS =====
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
    function openDialerModal() {
        try {
            rcLog('📱 Opening dialer modal...');

            // Initialize session ID if not already set and there's an active call
            if (!window._dialerCurrentSessionId && webPhone) {
                rcLog('  -> Checking for active sessions from openDialerModal...');
                try {
                    const sessions = webPhone.listSessions ? (webPhone.listSessions() || []) : [];
                    rcLog('  -> listSessions returned:', sessions.length, 'sessions');
                    const acceptedSessions = sessions.filter(isSessionAcceptedForDuring);
                    if (acceptedSessions.length > 0) {
                        const firstSession = acceptedSessions[0];
                        window._dialerCurrentSessionId = firstSession.id || firstSession.index || firstSession.sessionId || null;
                        rcLog('  -> Set _dialerCurrentSessionId to accepted session:', window._dialerCurrentSessionId);
                    } else {
                        window._dialerCurrentSessionId = null;
                        rcLog('  -> No accepted session found; keeping dialer out of during-call mode');
                    }
                } catch (e) {
                    console.warn('  -> Failed to get sessions:', e.message);
                }
            } else if (window._dialerCurrentSessionId) {
                rcLog('  -> _dialerCurrentSessionId already set:', window._dialerCurrentSessionId);
            }

            // Populate phone numbers in dropdowns before opening
            populatePhoneNumbersInDropdowns();

            // Populate audio devices (microphone & speaker sources)
            populateAudioDevices();

            try {
                const tabLink = document.querySelector('a[href="#callKeypad"]');
                if (tabLink) {
                    if (window.$ && typeof $(tabLink).tab === 'function') {
                        $(tabLink).tab('show');
                    } else {
                        tabLink.click();
                    }
                }
            } catch (e) { console.warn('openDialerModal tab switch failed', e); }
            // focus the display
            try { const inp = document.getElementById('callPhone'); if (inp) inp.focus(); } catch (_) { }
        } catch (e) { console.warn('openDialerModal failed', e); }
    }

    // Populate phone numbers from API into callFromNumber and smsFromNumber dropdowns
    function populatePhoneNumbersInDropdowns() {
        try {
            // Check if already populated
            const callFromSelect = document.getElementById('callFromNumber');

            if (callFromSelect && callFromSelect.options.length > 0) return; // Already populated

            const formatNumberLabel = (numObj) => {
                const rawNumber = numObj?.phoneNumber || numObj?.number || '';
                const number = String(rawNumber || '').trim();
                const usageRaw = (numObj?.usageType || numObj?.type || numObj?.phoneType || numObj?.label || '').toString();
                const isPrimary = !!(numObj?.primary || numObj?.isPrimary || numObj?.isMain);
                const normalized = usageRaw
                    .replace(/_/g, ' ')
                    .replace(/([a-z])([A-Z])/g, '$1 $2')
                    .toLowerCase()
                    .trim();

                const usageMap = {
                    'main company number': 'Main company number',
                    'company number': 'Company number',
                    'direct number': 'Primary number',
                    'extension number': 'Extension number',
                    'fax number': 'Company fax number',
                    'fax': 'Company fax number',
                    'toll free number': 'Toll-free number',
                    'additional company number': 'Additional company number',
                    'shared number': 'Shared number'
                };

                let typeLabel = usageMap[normalized] || '';
                if (!typeLabel && isPrimary) typeLabel = 'Primary number';
                if (!typeLabel && normalized) {
                    typeLabel = normalized.replace(/\b[a-z]/g, (c) => c.toUpperCase());
                }
                if (!typeLabel) typeLabel = 'Unknown';
                const maskedNumber = number
                    ? maskDialerDisplayNumber(number, { minMaskChars: 5, emptyFallback: number })
                    : '';

                return {
                    number,
                    label: number ? `${typeLabel} (${maskedNumber})` : typeLabel
                };
            };

            fetch(rcRoute('ringcentral.api.phone-numbers'), {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
                .then(r => r.json())
                .then(data => {
                    let dropdownOptions = '';

                    if (data.success && data.diagnostics) {
                        const numbers = data.diagnostics.extension_phone_numbers || [];
                        if (numbers.length) {
                            numbers.forEach(numObj => {
                                const formatted = formatNumberLabel(numObj);
                                if (!formatted.number) return;
                                dropdownOptions += `<option value="${formatted.number}">${formatted.label}</option>`;
                            });
                        }
                    }

                    // Update call dropdown only
                    if (callFromSelect) {
                        callFromSelect.innerHTML = dropdownOptions;
                    }
                })
                .catch(e => console.warn('Failed to populate phone numbers:', e));
        } catch (e) { console.warn('populatePhoneNumbersInDropdowns failed', e); }
    }

    // Populate actual audio input and output devices in Speaker Settings
    function populateAudioDevices() {
        try {
            if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
                console.warn('Audio device enumeration not supported');
                return;
            }

            navigator.mediaDevices.enumerateDevices().then(devices => {
                const audioInputs = devices.filter(d => d.kind === 'audioinput');
                const audioOutputs = devices.filter(d => d.kind === 'audiooutput');

                // Populate microphone source dropdowns
                const micSelectors = document.querySelectorAll('#audioInputSelect-global');
                micSelectors.forEach(select => {
                    // Build microphone options
                    const micOptions = [
                        { id: 'default', label: '🎙️ Default Microphone' }
                    ].concat(audioInputs.map((d, i) => ({
                        id: d.deviceId,
                        label: (d.label && d.label.length > 0) ? d.label : `🎙️ Microphone ${i + 1}`
                    })));

                    // Clear and repopulate
                    select.innerHTML = '';
                    micOptions.forEach(opt => {
                        const optEl = document.createElement('option');
                        optEl.value = opt.id;
                        optEl.textContent = opt.label;
                        select.appendChild(optEl);
                    });

                    // Try to restore previously selected value
                    if (window._rcAudioMicCache && window._rcAudioMicCache[select.id]) {
                        select.value = window._rcAudioMicCache[select.id];
                    } else {
                        select.value = 'default';
                    }

                    // Save selection on change
                    select.onchange = function () {
                        window._rcAudioMicCache = window._rcAudioMicCache || {};
                        window._rcAudioMicCache[this.id] = this.value;
                    };
                });

                // Populate speaker source dropdowns
                const speakerSelectors = document.querySelectorAll('#audioOutputSelect-speaker');
                speakerSelectors.forEach(select => {
                    // Build speaker options
                    const speakerOptions = [
                        { id: 'default', label: '🔊 Default Speaker' }
                    ].concat(audioOutputs.map((d, i) => ({
                        id: d.deviceId,
                        label: (d.label && d.label.length > 0) ? d.label : `🔊 Speaker ${i + 1}`
                    })));

                    // Clear and repopulate
                    select.innerHTML = '';
                    speakerOptions.forEach(opt => {
                        const optEl = document.createElement('option');
                        optEl.value = opt.id;
                        optEl.textContent = opt.label;
                        select.appendChild(optEl);
                    });

                    // Try to restore previously selected value
                    if (window._rcAudioSpeakerCache && window._rcAudioSpeakerCache[select.id]) {
                        select.value = window._rcAudioSpeakerCache[select.id];
                    } else {
                        select.value = 'default';
                    }

                    // Save selection on change AND apply to ALL active sessions
                    select.onchange = async function () {
                        window._rcAudioSpeakerCache = window._rcAudioSpeakerCache || {};
                        window._rcAudioSpeakerCache[this.id] = this.value;

                        const deviceId = this.value;
                        rcLog('🔊 Speaker selected globally:', deviceId);
                        rcLog('  -> Checking for active sessions...');
                        rcLog('  -> _dialerCurrentSessionId:', window._dialerCurrentSessionId);
                        rcLog('  -> webPhone exists:', !!window.webPhone);

                        // Get list of active sessions - try multiple methods
                        let sessions = [];
                        let appliedCount = 0;

                        // Method 1: Try to get from webPhone.listSessions()
                        if (window.webPhone && typeof window.webPhone.listSessions === 'function') {
                            try {
                                sessions = window.webPhone.listSessions() || [];
                                rcLog('  -> Method 1 (listSessions): found', sessions.length, 'sessions');
                            } catch (e) {
                                console.warn('  -> Method 1 (listSessions) failed:', e.message);
                                sessions = [];
                            }
                        }

                        // Method 2: If no sessions found, check for currently tracked session
                        if (sessions.length === 0 && window._dialerCurrentSessionId) {
                            rcLog('  -> Method 2 (_dialerCurrentSessionId):', window._dialerCurrentSessionId);
                            sessions = [{ id: window._dialerCurrentSessionId }];
                        }

                        // Method 3: Check if there's a current active session on webPhone
                        if (sessions.length === 0 && window.webPhone && window.webPhone.currentSession) {
                            rcLog('  -> Method 3 (webPhone.currentSession):', window.webPhone.currentSession.id);
                            sessions = [{ id: window.webPhone.currentSession.id || window.webPhone.currentSession }];
                        }

                        // Method 4: Use last cached active sessions from callStarted handler
                        if (sessions.length === 0 && window._lastActiveSessions && Array.isArray(window._lastActiveSessions) && window._lastActiveSessions.length > 0) {
                            rcLog('  -> Method 4 (_lastActiveSessions cache):', window._lastActiveSessions.length);
                            sessions = window._lastActiveSessions.slice();
                        }

                        // Method 4: Check webPhone's active session directly
                        if (sessions.length === 0 && window.webPhone) {
                            rcLog('  -> Method 4: Checking webPhone for active session...');
                            rcLog('  -> webPhone.currentOutputDeviceId:', window.webPhone.currentOutputDeviceId);
                            rcLog('  -> webPhone keys:', Object.keys(window.webPhone).filter(k => k.includes('session') || k.includes('Session')).slice(0, 10));
                        }

                        if (sessions.length === 0) {
                            rcLog('ℹ️ No active sessions found - selection saved for next call');
                            // Still store the preference for next call
                            if (window.webPhone) {
                                window.webPhone.currentOutputDeviceId = deviceId;
                                rcLog('  -> Stored device ID for next call:', deviceId);
                            }
                            return;
                        }

                        rcLog(`🔊 Applying speaker device to ${sessions.length} active session(s)`);

                        for (const session of sessions) {
                            const sessionId = session.id || session.index || session;
                            try {
                                rcLog('  -> Applying to session:', sessionId);
                                await setSessionOutput(sessionId, deviceId);
                                appliedCount++;
                            } catch (e) {
                                console.warn('  -> Failed for session', sessionId, ':', e.message);
                            }
                        }

                        if (appliedCount > 0) {
                            rcLog(`✅ Speaker applied to ${appliedCount}/${sessions.length} sessions`);
                            // Test the speaker by playing a beep
                            testSpeakerAudio();
                        } else {
                            console.warn('⚠️ Speaker not applied to any sessions');
                        }
                    };
                });

                // Also populate the combined selector (audioOutputSelect-global) if it exists
                const combinedSelector = document.getElementById('audioOutputSelect-global');
                if (combinedSelector) {
                    const combinedOptions = [
                        { id: 'default', label: '🎤📢 Default (Microphone + Speaker)' }
                    ];

                    // Add combined audio I/O options
                    audioInputs.slice(0, 3).forEach((input, i) => {
                        audioOutputs.slice(0, 3).forEach((output, j) => {
                            if (i === j || (i === 0 && j === 0)) {
                                const label = `${input.label || `Microphone ${i + 1}`} → ${output.label || `Speaker ${j + 1}`}`;
                                combinedOptions.push({
                                    id: `${input.deviceId}|${output.deviceId}`,
                                    label: label
                                });
                            }
                        });
                    });

                    combinedSelector.innerHTML = '';
                    combinedOptions.forEach(opt => {
                        const optEl = document.createElement('option');
                        optEl.value = opt.id;
                        optEl.textContent = opt.label;
                        combinedSelector.appendChild(optEl);
                    });

                    if (window._rcAudioComboCache) {
                        combinedSelector.value = window._rcAudioComboCache;
                    } else {
                        combinedSelector.value = 'default';
                    }

                    combinedSelector.onchange = function () {
                        window._rcAudioComboCache = this.value;
                    };
                }

                rcLog('Audio devices populated:', {
                    inputs: audioInputs.length,
                    outputs: audioOutputs.length
                });
            }).catch(e => {
                console.warn('Failed to enumerate audio devices:', e);
            });
        } catch (e) {
            console.warn('populateAudioDevices failed:', e);
        }
    }

    function closeDialerModal() {
        try {
            return;
        } catch (e) { console.warn('closeDialerModal failed', e); }
    }

    function hasActiveDialerSession() {
        try {
            if (!window.webPhone || typeof webPhone.listSessions !== 'function') return false;
            return getDialerLiveSessions().some(isSessionAcceptedForDuring);
        } catch (_) {
            return false;
        }
    }

    function getActiveDialerSessions() {
        try {
            const activeSessions = getDialerLiveSessions().filter(isSessionAcceptedForDuring);

            if (!activeSessions.length && window.webPhone && webPhone.currentSession) {
                return isSessionAcceptedForDuring(webPhone.currentSession)
                    ? [webPhone.currentSession]
                    : [];
            }

            return activeSessions;
        } catch (_) {
            return [];
        }
    }

    function getCallControlClient() {
        if (!window.RCCallControl || typeof window.RCCallControl.isEnabled !== 'function') {
            return null;
        }
        return window.RCCallControl.isEnabled() ? window.RCCallControl : null;
    }

    function getCachedCallControlSwitchCandidate() {
        try {
            const client = getCallControlClient();
            if (!client || !Array.isArray(window.__rcCallControlSessions)) return null;
            return client.findSwitchableSession(window.__rcCallControlSessions);
        } catch (_) {
            return null;
        }
    }

    function getCachedCallControlActiveParties() {
        try {
            const client = getCallControlClient();
            if (!client || !Array.isArray(window.__rcCallControlSessions)) return [];

            const parties = [];
            window.__rcCallControlSessions.forEach(function (session) {
                const sessionId = session?.id || session?.sessionId;
                if (!sessionId) return;

                (session?.parties || []).forEach(function (party) {
                    const status = String(party?.status?.code || party?.status || '').toLowerCase();
                    const isAlive = ['proceeding', 'answered', 'connected', 'hold', 'onhold', 'parked'].includes(status);
                    if (party?.id && isAlive) {
                        parties.push({
                            sessionId: sessionId,
                            partyId: party.id,
                            status: status,
                            direction: party?.direction,
                            from: party?.from,
                            to: party?.to,
                            source: session?.origin || session?.source || ''
                        });
                    }
                });
            });

            return parties;
        } catch (_) {
            return [];
        }
    }

    function setMergeButtonUiState(isMerging = false) {
        try {
            const mergeBtn = document.getElementById('dialerMergeBtn');
            const mergeLabel = document.getElementById('dialerMergeBtnLabel');
            if (!mergeBtn) return;

            const icon = mergeBtn.querySelector('i');
            if (isMerging) {
                window._dialerMergeInProgress = true;
                mergeBtn.disabled = true;
                mergeBtn.title = 'Merging calls...';
                if (icon) icon.className = 'fa fa-spinner fa-spin';
                if (mergeLabel) mergeLabel.textContent = 'Merging...';
                return;
            }

            window._dialerMergeInProgress = false;
            if (icon) icon.className = 'fa fa-users';
            if (mergeLabel) mergeLabel.textContent = 'Merge';
        } catch (_) { }
    }

    function updateDialerActionButtons() {
        try {
            const transferBtn = document.getElementById('dialerTransferBtn');
            const mergeBtn = document.getElementById('dialerMergeBtn');
            const inviteBtn = document.getElementById('dialerInviteBtn');
            const switchBtn = document.getElementById('dialerSwitchToWebBtn');
            if (!transferBtn && !mergeBtn && !inviteBtn && !switchBtn) return;

            const activeSessions = getActiveDialerSessions();
            const hasOneCall = activeSessions.length >= 1;
            const hasTwoWebCalls = activeSessions.length >= 2;
            const callControlClient = getCallControlClient();
            const hasCallControl = !!callControlClient;
            const switchCandidate = getCachedCallControlSwitchCandidate();
            const callControlParties = getCachedCallControlActiveParties();
            const hasCallControlCall = callControlParties.length >= 1;
            const hasCallControlMerge = callControlParties.length >= 2;

            if (transferBtn) {
                const canTransfer = hasOneCall || hasCallControlCall;
                transferBtn.disabled = !canTransfer;
                transferBtn.title = canTransfer
                    ? 'Transfer call'
                    : 'Start/answer a call to enable transfer';
            }

            if (mergeBtn) {
                const isMerging = !!window._dialerMergeInProgress;
                const canMerge = hasTwoWebCalls;
                mergeBtn.disabled = isMerging || !canMerge;
                mergeBtn.title = isMerging
                    ? 'Merging calls...'
                    : (canMerge
                    ? 'Merge active calls'
                    : 'Two active web calls are required');
            }

            if (inviteBtn) {
                const hasInviteFn = !!(window.webPhone && typeof webPhone.makeCall === 'function');
                inviteBtn.disabled = !hasInviteFn;
                inviteBtn.title = hasInviteFn
                    ? 'Invite call'
                    : 'Invite is not available in this client';
            }

            if (switchBtn) {
                const isEnabled = !!getCallControlClient();
                const canSwitch = !!(isEnabled && switchCandidate);
                switchBtn.disabled = !canSwitch;
                switchBtn.title = canSwitch
                    ? 'Switch active non-web call to web'
                    : (isEnabled
                        ? 'No external active call found'
                        : 'Call control feature is disabled');
            }
        } catch (e) {
            console.warn('updateDialerActionButtons failed', e);
        }
    }

    let _dialerMinimizedInterval = null;

    function updateMinimizedDialerWidget() {
        try {
            const timerEl = document.getElementById('dialerMinimizedTimer');
            const numberEl = document.getElementById('dialerMinimizedNumber');
            const incomingActions = document.getElementById('dialerMinimizedIncomingActions');
            const openBtn = document.getElementById('dialerMinimizedOpenBtn');
            if (!timerEl || !numberEl) return;

            if (window._minimizedIncomingCallActive) {
                timerEl.textContent = 'Incoming';
                const incomingNumber = window._minimizedIncomingCallNumber ||
                    document.getElementById('incomingCallNumber')?.textContent ||
                    'Incoming call';
                numberEl.textContent = maskDialerDisplayNumber(incomingNumber);
                if (incomingActions) incomingActions.style.display = 'flex';
                if (openBtn) openBtn.style.display = 'none';
                return;
            }

            if (incomingActions) incomingActions.style.display = 'none';
            if (openBtn) openBtn.style.display = '';

            const mainTimer = document.getElementById('myCallTimer')?.textContent ||
                document.getElementById('dialerCallDuration')?.textContent || '00:00';
            const timerValue = String(mainTimer).split('—')[0].trim();
            timerEl.textContent = timerValue || '00:00';

            const numberValue = document.getElementById('myCallNumber')?.textContent ||
                document.getElementById('dialerRemoteNumber')?.textContent ||
                'Active call';
            numberEl.textContent = maskDialerDisplayNumber(numberValue);
        } catch (e) { console.warn('updateMinimizedDialerWidget failed', e); }
    }

    function showMinimizedDialerWidget() {
        try {
            const widget = document.getElementById('dialerMinimizedCall');
            if (!widget) return;
            widget.style.display = 'flex';
            updateMinimizedDialerWidget();
            if (!_dialerMinimizedInterval) {
                _dialerMinimizedInterval = setInterval(updateMinimizedDialerWidget, 1000);
            }
        } catch (e) { console.warn('showMinimizedDialerWidget failed', e); }
    }

    function hideMinimizedDialerWidget() {
        try {
            const widget = document.getElementById('dialerMinimizedCall');
            if (widget) widget.style.display = 'none';
            const incomingActions = document.getElementById('dialerMinimizedIncomingActions');
            const openBtn = document.getElementById('dialerMinimizedOpenBtn');
            if (incomingActions) incomingActions.style.display = 'none';
            if (openBtn) openBtn.style.display = '';
            if (_dialerMinimizedInterval) {
                clearInterval(_dialerMinimizedInterval);
                _dialerMinimizedInterval = null;
            }
        } catch (e) { console.warn('hideMinimizedDialerWidget failed', e); }
    }

    function minimizeDialerModal() {
        try {
            closeDialerModal();
            if (hasActiveDialerSession()) {
                showMinimizedDialerWidget();
            } else {
                hideMinimizedDialerWidget();
            }
        } catch (e) { console.warn('minimizeDialerModal failed', e); }
    }

    function restoreDialerFromMinimized() {
        try {
            try {
                const callsTab = document.querySelector('a[href="#tabCalls"]');
                if (callsTab) {
                    if (window.$ && typeof $(callsTab).tab === 'function') {
                        $(callsTab).tab('show');
                    } else {
                        callsTab.click();
                    }
                }
            } catch (_) { }
            openDialerModal();
            if (window._dialerCurrentSessionId && findAcceptedSessionById(window._dialerCurrentSessionId) && typeof switchDialerToDuringCall === 'function') {
                switchDialerToDuringCall(window._dialerCurrentSessionId);
            } else if (Array.isArray(window._incomingCallQueue) && window._incomingCallQueue.length > 0 && typeof window.rcRenderIncomingQueue === 'function') {
                window.rcRenderIncomingQueue({ focusDialer: false });
            } else if (typeof switchDialerToBeforeCall === 'function') {
                switchDialerToBeforeCall();
            }
        } catch (e) { console.warn('restoreDialerFromMinimized failed', e); }
    }

    function refreshMinimizedDialerVisibility() {
        try {
            const shouldShow = (hasActiveDialerSession() || window._minimizedIncomingCallActive);
            if (shouldShow) showMinimizedDialerWidget();
            else hideMinimizedDialerWidget();
        } catch (e) { console.warn('refreshMinimizedDialerVisibility failed', e); }
    }

    function shouldSuppressMinimizedIncomingEvent(detail) {
        try {
            const session = detail?.session || detail || null;
            const currentId = window._dialerCurrentSessionId ? String(window._dialerCurrentSessionId) : '';
            const sessionIds = getSessionIdCandidates(session);
            const direction = String(session?.direction || session?.callDirection || session?._rcDirection || '').toLowerCase();

            if (session?._rcOriginatedLocally === true) return true;
            if (direction.includes('outbound') || direction.includes('outgoing')) return true;
            if (currentId && sessionIds.includes(currentId)) return true;

            return false;
        } catch (_) {
            return false;
        }
    }

    document.addEventListener('ringcentral:callEnded', function(e) {
        refreshMinimizedDialerVisibility();
        // After a call ends, activate the next available call if any
        setTimeout(activateNextAvailableCall, 250); // slight delay to allow session state to update
    });
    document.addEventListener('ringcentral:callStarted', refreshMinimizedDialerVisibility);
    document.addEventListener('ringcentral:callConnected', refreshMinimizedDialerVisibility);
    document.addEventListener('ringcentral:callAnswered', refreshMinimizedDialerVisibility);
    document.addEventListener('ringcentral:incomingCall', function (e) {
        try {
            if (shouldSuppressMinimizedIncomingEvent(e?.detail || {})) {
                window._minimizedIncomingCallActive = false;
                window._minimizedIncomingCallNumber = '';
                refreshMinimizedDialerVisibility();
                return;
            }
            const incomingNumber = e?.detail?.callerNumber || e?.detail?.from?.phoneNumber || e?.detail?.session?.remoteNumber;
            window._minimizedIncomingCallActive = true;
            window._minimizedIncomingCallNumber = incomingNumber || '';
            refreshMinimizedDialerVisibility();
        } catch (err) { console.warn('incomingCall minimized handler failed', err); }
    });

    ['ringcentral:callConnected', 'ringcentral:callAnswered', 'ringcentral:callStarted'].forEach(ev => {
        document.addEventListener(ev, function () {
            window._minimizedIncomingCallActive = false;
            window._minimizedIncomingCallNumber = '';
            refreshMinimizedDialerVisibility();
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        try {
            const tabs = document.querySelectorAll('a[href="#callKeypad"], a[href="#callCalls"], a[href="#callVoicemail"], a[href="#callNotes"], a[href="#callRecordings"]');
            tabs.forEach(tab => {
                if (window.$ && typeof $(tab).on === 'function') {
                    $(tab).on('shown.bs.tab', refreshMinimizedDialerVisibility);
                } else {
                    tab.addEventListener('click', () => setTimeout(refreshMinimizedDialerVisibility, 0));
                }
            });
        } catch (e) { console.warn('minimized widget tab hook failed', e); }
    });

    // ===== END: DIALER MODAL HELPERS =====

    // ===== START: DIALER KEYPAD FUNCTIONS =====
    function showDtmfError(message) {
        const text = message || 'Unable to send keypad tone. Make sure the call is connected.';
        if (typeof showErrorModal === 'function') {
            showErrorModal(text, false);
            return;
        }
        alert(text);
    }

    async function dialerPress(ch) {
        try {
            const digit = String(ch || '');
            if (!/^[0-9*#]$/.test(digit)) return;

            if (
                window.webPhone
                && typeof window.webPhone.hasDtmfTarget === 'function'
                && typeof window.webPhone.sendDtmf === 'function'
                && window.webPhone.hasDtmfTarget()
            ) {
                await window.webPhone.sendDtmf(digit);
                return;
            }

            const inp = document.getElementById('callPhone');
            if (!inp) return;
            inp.value = (inp.value || '') + digit;
        } catch (e) {
            console.warn('dialerPress failed', e);
            showDtmfError(e && e.message ? e.message : null);
        }
    }

    function backspaceDialer() {
        try { const inp = document.getElementById('callPhone'); if (!inp) return; inp.value = inp.value.slice(0, -1); } catch (e) { console.warn(e); }
    }

    function clearDialer() {
        try { const inp = document.getElementById('callPhone'); if (!inp) return; inp.value = ''; } catch (e) { console.warn(e); }
    }
    // ===== END: DIALER KEYPAD FUNCTIONS =====

    // ===== START: DIALER UI FUNCTIONS =====
    function updateCallerIDDisplay() {
        try {
            const select = document.getElementById('callFromNumber');
            if (!select || !select.options[select.selectedIndex]) return;
            const label = select.options[select.selectedIndex].text || 'Unknown';
            // Display in a styled way (update if you add a display element)
        } catch (e) { console.warn('updateCallerIDDisplay failed', e); }
    }

    // Switch dialer UI to "during call" mode
    function updateMyCallSection() {
        try {
            if (!window.webPhone) return;

            // Only count accepted live sessions (exclude incoming/dead/ignored)
            const sessions = getDialerLiveSessions().filter(isSessionAcceptedForDuring);
            const callCount = sessions.length;

            // Update call count
            const callsCountEl = document.getElementById('myCallsCount');
            if (callsCountEl) {
                callsCountEl.textContent = callCount + ' ' + (callCount === 1 ? 'Call' : 'Calls');
            }

            // Show/hide swap button based on number of calls
            const swapBtn = document.getElementById('myCallSwapBtn');
            if (swapBtn) {
                swapBtn.style.display = callCount > 1 ? 'inline-flex' : 'none';
            }

            // Get current session info
            const currentSessionId = window._dialerCurrentSessionId;
            if (currentSessionId) {
                const session = sessions.find((s) => getSessionIdCandidates(s).includes(String(currentSessionId)));
                if (session) {
                    const phoneEl = document.getElementById('myCallNumber');
                    if (phoneEl) {
                        const display = getSessionDisplayData(session);
                        if (display.isConference) {
                            phoneEl.textContent = display.myCallNumber;
                        } else {
                            phoneEl.textContent = display.myCallNumber || '*****';
                        }
                    }
                }
            }
            refreshConferenceParticipantsButton();
        } catch (e) {
            console.warn('updateMyCallSection failed', e);
        }
    }

    function switchDialerToDuringCall(sessionId) {
        const switchStart = performance.now();
        rcLog(`   [switchDialerToDuringCall] Starting for sessionId: ${sessionId}`);
        try {
            // Reset hold button to default "Hold" state when switching to during-call
            try {
                const holdBtn = document.getElementById('dialerHoldBtn');
                const holdLabel = document.getElementById('dialerHoldLabel');
                if (holdBtn) {
                    holdBtn.style.background = '';
                    holdBtn.style.color = '';
                    holdBtn.innerHTML = '<i class="fa fa-pause"></i>';
                }
                if (holdLabel) {
                    holdLabel.textContent = 'Hold';
                }
            } catch (_) { }

            const beforeSection = document.getElementById('dialerBeforeCallSection');
            const duringSection = document.getElementById('dialerDuringCallSection');
            const incomingSection = document.getElementById('dialerIncomingCallSection');

            let hideBeforeTime = performance.now();
            if (beforeSection) beforeSection.style.display = 'none';
            rcLog(`      ├─ Hidden before-call section (${(performance.now() - hideBeforeTime).toFixed(1)}ms)`);

            let showDuringTime = performance.now();
            if (duringSection) duringSection.style.display = 'block';
            rcLog(`      ├─ Shown during-call section (${(performance.now() - showDuringTime).toFixed(1)}ms)`);

            let hideIncomingTime = performance.now();
            if (incomingSection) incomingSection.style.display = 'none';
            rcLog(`      ├─ Hidden incoming-call section (${(performance.now() - hideIncomingTime).toFixed(1)}ms)`);

            // Update call info from session
            let updateCallInfoTime = performance.now();
            try {
                if (window.webPhone && sessionId) {
                    const sessions = window.webPhone.listSessions() || [];
                    const session = sessions.find((s) => getSessionIdCandidates(s).includes(String(sessionId)));
                    if (session) {
                        const nameEl = document.getElementById('dialerRemoteName');
                        const numEl = document.getElementById('dialerRemoteNumber');
                        const display = getSessionDisplayData(session);
                        if (nameEl) nameEl.textContent = display.name;
                        if (numEl) {
                            if (display.isConference) {
                                numEl.textContent = `(${display.number})`;
                            } else {
                                numEl.textContent = `(${display.number || '*****'})`;
                            }
                        }
                        rcLog(`      ├─ Updated call info (${(performance.now() - updateCallInfoTime).toFixed(1)}ms)`);
                    }
                }
            } catch (e) {
                console.warn(`      ⚠️  Call info update failed: ${e.message}`);
            }

            let sessionIdSetTime = performance.now();
            window._dialerCurrentSessionId = sessionId;
            rcLog(`      ├─ Set current session ID (${(performance.now() - sessionIdSetTime).toFixed(1)}ms)`);

            let updateMyCallTime = performance.now();
            updateMyCallSection(); // Update the My Call header
            rcLog(`      ├─ Updated My Call section (${(performance.now() - updateMyCallTime).toFixed(1)}ms)`);

            try {
                const hasQueuedIncoming = Array.isArray(window._incomingCallQueue) && window._incomingCallQueue.length > 0;
                if (hasQueuedIncoming && typeof window.rcRenderIncomingQueue === 'function') {
                    window.rcRenderIncomingQueue({ focusDialer: false, inlineDuring: true });
                } else {
                    const banner = document.getElementById('dialerDuringIncomingBanner');
                    if (banner) banner.style.display = 'none';
                }
            } catch (_) { }

            let startTimerTime = performance.now();
            startDialerCallTimer();
            rcLog(`      ├─ Started call timer (${(performance.now() - startTimerTime).toFixed(1)}ms)`);

            const totalSwitch = performance.now() - switchStart;
            rcLog(`   [switchDialerToDuringCall] Complete (${totalSwitch.toFixed(0)}ms)`);
        } catch (e) {
            console.error(`❌ [switchDialerToDuringCall] failed after ${(performance.now() - switchStart).toFixed(0)}ms:`, e.message);
        }
    }
    // Switch dialer UI back to "before call" mode
    function switchDialerToBeforeCall() {
        try {
            const beforeSection = document.getElementById('dialerBeforeCallSection');
            const duringSection = document.getElementById('dialerDuringCallSection');
            const incomingSection = document.getElementById('dialerIncomingCallSection');
            const duringIncomingBanner = document.getElementById('dialerDuringIncomingBanner');
            if (beforeSection) beforeSection.style.display = 'block';
            if (duringSection) duringSection.style.display = 'none';
            if (incomingSection) incomingSection.style.display = 'none';
            if (duringIncomingBanner) duringIncomingBanner.style.display = 'none';

            window._dialerCurrentSessionId = null;
            stopDialerCallTimer();
            refreshConferenceParticipantsButton();
        } catch (e) { console.warn('switchDialerToBeforeCall failed', e); }
    }
    // ===== END: DIALER UI FUNCTIONS =====

    // ===== START: CALL TIMER FUNCTIONS =====
    let _dialerCallTimerInterval = null;

    function startDialerCallTimer() {
        try {
            if (_dialerCallTimerInterval) clearInterval(_dialerCallTimerInterval);
            _dialerCallTimerInterval = setInterval(() => {
                try {
                    if (!window.webPhone || !window._dialerCurrentSessionId) return;

                    const sessions = window.webPhone.listSessions() || [];
                    const session = sessions.find(s => (s.id || s.index || s.sessionId) === window._dialerCurrentSessionId);

                    if (session) {
                        let elapsed;
                        const sessionId = session.id || session.index || session.sessionId || window._dialerCurrentSessionId;

                        const normalizeTime = (value) => {
                            if (!value) return null;
                            if (value instanceof Date) return value.getTime();
                            if (typeof value === 'number') {
                                // Heuristic: seconds vs ms
                                return value < 1e12 ? value * 1000 : value;
                            }
                            if (typeof value === 'string') {
                                const parsed = Date.parse(value);
                                return isNaN(parsed) ? null : parsed;
                            }
                            return null;
                        };

                        // Prefer SDK timestamps, then fallback to a local start marker
                        let startedAt = session.startedAt || session._ringingStartedAt || session.startedTimestamp ||
                            session._startedAt || session.startTime || session.createdAt || session.inviteTimestamp;
                        let startedAtMs = normalizeTime(startedAt);

                        if (!startedAtMs) {
                            // Outgoing calls may not populate timestamps; mark start when we first see the session.
                            window._dialerSessionStartTimes = window._dialerSessionStartTimes || {};
                            if (!window._dialerSessionStartTimes[sessionId]) {
                                window._dialerSessionStartTimes[sessionId] = Date.now();
                            }
                            startedAtMs = window._dialerSessionStartTimes[sessionId];
                        }

                        elapsed = Math.floor((Date.now() - startedAtMs) / 1000);

                        const mins = Math.floor(elapsed / 60);
                        const secs = elapsed % 60;
                        const display = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0') + ' — On Call';
                        const durationEl = document.getElementById('dialerCallDuration');
                        if (durationEl) durationEl.textContent = display;

                        // Also update multiCallDuration if present
                        const multiDurationEl = document.getElementById('multiCallDuration');
                        if (multiDurationEl) {
                            multiDurationEl.textContent = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
                        }

                        // Update myCallTimer in the My Call header
                        const myTimerEl = document.getElementById('myCallTimer');
                        if (myTimerEl) {
                            myTimerEl.textContent = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
                        }
                    }
                } catch (_) { }
            }, 1000);
        } catch (e) { console.warn('startDialerCallTimer failed', e); }
    }

    function stopDialerCallTimer() {
        try {
            if (_dialerCallTimerInterval) {
                clearInterval(_dialerCallTimerInterval);
                _dialerCallTimerInterval = null;
            }
        } catch (e) { console.warn('stopDialerCallTimer failed', e); }
    }
    // ===== END: CALL TIMER FUNCTIONS =====

    // ===== START: CALL CONTROL FUNCTIONS =====
    async function toggleCallHoldFromHeader() {
        try {
            const activeBtn = document.querySelector('button[title="Active call - Click to hold/unhold"]');
            if (!activeBtn) return;

            if (!window._dialerCurrentSessionId) {
                console.warn('No active session to hold/unhold');
                return;
            }

            // Check if current session is on hold
            let session = window.webPhone ? window.webPhone.findSession(window._dialerCurrentSessionId) : null;
            const isOnHold = !!(session && (session.hold || session._onHold || session.onHold || session._hold || (session.status && session.status.toString().toLowerCase().includes('hold'))));

            if (isOnHold && window.webPhone && typeof window.webPhone.swapToSession === 'function') {
                await switchToCall(window._dialerCurrentSessionId);
            } else {
                await holdCallFromDialer();
            }

            // Update button appearance after a short delay
            setTimeout(() => {
                session = window.webPhone ? window.webPhone.findSession(window._dialerCurrentSessionId) : null;
                if (session) {
                    const isOnHoldNow = !!(session.hold || session._onHold || session.onHold);
                    if (isOnHoldNow) {
                        activeBtn.style.background = '#ff9800';
                        activeBtn.title = 'Call on hold - Click to resume';
                    } else {
                        activeBtn.style.background = '#2ecc71';
                        activeBtn.title = 'Active call - Click to hold';
                    }
                }
            }, 100);
        } catch (e) { console.error('toggleCallHoldFromHeader failed', e); }
    }

    // Call control functions for during-call UI
    async function toggleMuteFromDialer() {
        const restoreMuteBtn = window.rcSetActionButtonLoading
            ? window.rcSetActionButtonLoading('dialerMuteBtn', { loadingText: 'Updating mute...', preserveContent: true, statusText: 'Updating mute...' })
            : null;
        try {
            let sid = window._dialerCurrentSessionId;

            // Fallback 1: if no session ID, try to get from WebPhone's current session
            if (!sid && webPhone && webPhone.currentSession) {
                try {
                    sid = webPhone.currentSession.id || webPhone.currentSession.sessionId || webPhone.currentSession.index;
                    window._dialerCurrentSessionId = sid;
                    rcLog('toggleMuteFromDialer: resolved session from webPhone.currentSession:', sid);
                } catch (_) { }
            }

            // Fallback 2: if still no ID, try to get from active sessions list
            if (!sid && webPhone) {
                try {
                    const sessions = webPhone.listSessions ? (webPhone.listSessions() || []) : [];
                    if (sessions.length > 0) {
                        // Prefer the current session, fall back to first one
                        const currentSes = webPhone.currentSession;
                        const matchedCurrent = currentSes ? sessions.find(s => (s.id || s.sessionId) === (currentSes.id || currentSes.sessionId)) : null;
                        const targetSession = matchedCurrent || sessions[0];
                        sid = targetSession.id || targetSession.sessionId || targetSession.index || targetSession;
                        window._dialerCurrentSessionId = sid;
                        rcLog('toggleMuteFromDialer: resolved session from listSessions:', sid);
                    }
                } catch (_) { }
            }

            if (!sid) {
                console.warn('toggleMuteFromDialer: no active session found');
                return;
            }

            if (!webPhone) throw new Error('WebPhone not initialized');

            const muteBtn = document.getElementById('dialerMuteBtn');
            if (!muteBtn) return;

            try {
                // Call the SDK mute toggle
                rcLog('toggleMuteFromDialer: calling webPhone.toggleSessionMute for sid=', sid);
                const result = await webPhone.toggleSessionMute(sid);

                // Determine if now muted
                let isMuted = false;
                try {
                    if (typeof webPhone.findSession === 'function') {
                        const session = webPhone.findSession(sid);
                        isMuted = session && (session.muted === true || session._muted === true);
                    } else if (result && typeof result.muted === 'boolean') {
                        isMuted = result.muted;
                    }
                } catch (_) { }

                // Update button appearance
                if (isMuted) {
                    muteBtn.style.background = '#ff6b6b';
                    muteBtn.style.color = 'white';
                    muteBtn.innerHTML = '<i class="fa fa-microphone-slash"></i>';
                    try { const lbl = document.getElementById('dialerMuteLabel'); if (lbl) lbl.textContent = 'Unmute'; } catch (_) { }
                } else {
                    muteBtn.style.background = '';
                    muteBtn.style.color = '';
                    muteBtn.innerHTML = '<i class="fa fa-microphone"></i>';
                    try { const lbl = document.getElementById('dialerMuteLabel'); if (lbl) lbl.textContent = 'Mute'; } catch (_) { }
                }

                // Update per-row mute button and status if present
                try {
                    const perRowBtn = document.querySelector(`button[data-mute-btn='${sid}']`);
                    if (perRowBtn) perRowBtn.textContent = isMuted ? 'Unmute' : 'Mute';
                    const row = document.querySelector(`tr[data-session-id="${sid}"]`);
                    if (row) {
                        const statusEl = row.querySelector('.rc-status');
                        if (statusEl) {
                            const st = statusEl.textContent || '';
                            if (isMuted && !st.includes('(Muted)')) statusEl.textContent = st + ' (Muted)';
                            else if (!isMuted && st.includes('(Muted)')) statusEl.textContent = st.replace(' (Muted)', '');
                        }
                    }
                } catch (_) { }

                rcLog('Mute toggled:', isMuted, 'sid=', sid, 'result=', result);
            } catch (err) {
                console.error('Mute toggle failed:', err);
                // Reset button on error
                muteBtn.style.background = '';
                muteBtn.style.color = '';
                muteBtn.innerHTML = '<i class="fa fa-microphone"></i>';
            }
        } catch (e) { console.error('toggleMuteFromDialer failed', e); }
        finally { if (typeof restoreMuteBtn === 'function') restoreMuteBtn(); }
    }

    function toggleKeypadInDialer() {
        try {
            const duringSection = document.getElementById('dialerDuringCallSection');
            const beforeSection = document.getElementById('dialerBeforeCallSection');
            const activeCallsHeader = document.getElementById('activeCallsHeader');
            const currentCallInfo = document.getElementById('currentCallInfo');

            if (!duringSection || !beforeSection) return;

            // Toggle between call controls and keypad dialer
            if (duringSection.style.display !== 'none') {
                // Show keypad dialer, hide call controls
                duringSection.style.display = 'none';
                beforeSection.style.display = 'block';

                // Show conditional headers
                const sessions = getDialerLiveSessions().filter(isSessionAcceptedForDuring);
                if (sessions.length > 1 && activeCallsHeader) {
                    activeCallsHeader.style.display = 'flex';
                }
                if (sessions.length >= 1 && currentCallInfo) {
                    currentCallInfo.style.display = 'block';
                }

                // Update the current call info display
                updateMultipleCallsDisplay();
                // Populate phone numbers dropdown
                populateMultiCallPhoneNumbers();
                // Update call indicators
                updateCallIndicators();
            } else {
                // Back to call controls
                backToCallControls();
            }
        } catch (e) { console.warn('toggleKeypadInDialer failed', e); }
    }

    async function backToCallControls() {
        try {
            const duringSection = document.getElementById('dialerDuringCallSection');
            const beforeSection = document.getElementById('dialerBeforeCallSection');
            const activeCallsHeader = document.getElementById('activeCallsHeader');
            const currentCallInfo = document.getElementById('currentCallInfo');
            const multiCallPhone = document.getElementById('callPhone');

            if (duringSection && beforeSection) {
                duringSection.style.display = 'block';
                beforeSection.style.display = 'none';

                // Always restore only the current active session
                const sessionId = window._dialerCurrentSessionId;
                if (sessionId && window.webPhone && typeof window.webPhone.swapToSession === 'function') {
                    await window.webPhone.swapToSession(sessionId);
                }

                // Update UI header visibility
                const activeSessions = getDialerLiveSessions().filter(isSessionAcceptedForDuring);
                if (activeCallsHeader) {
                    if (activeSessions.length > 1) {
                        activeCallsHeader.style.display = 'flex';
                    } else {
                        activeCallsHeader.style.display = 'none';
                    }
                }
                if (currentCallInfo) {
                    if (activeSessions.length >= 1) {
                        currentCallInfo.style.display = 'block';
                    } else {
                        currentCallInfo.style.display = 'none';
                    }
                }
                // Clear the multi-call dialer
                if (multiCallPhone) multiCallPhone.value = '';
            }
        } catch (e) { console.warn('backToCallControls failed', e); }
    }

    function updateMultipleCallsDisplay() {
        try {
            // Update current call info in the compact header
            const multiCallDuration = document.getElementById('multiCallDuration');
            const multiCallName = document.getElementById('multiCallName');
            const dialerCallDuration = document.getElementById('dialerCallDuration');
            const dialerRemoteName = document.getElementById('dialerRemoteName');

            if (multiCallDuration && dialerCallDuration) {
                multiCallDuration.textContent = dialerCallDuration.textContent.split(' ')[0]; // Get just the time
            }
            if (multiCallName && dialerRemoteName) {
                multiCallName.textContent = dialerRemoteName.textContent;
            }
        } catch (e) { console.warn('updateMultipleCallsDisplay failed', e); }
    }

    function updateCallIndicators() {
        try {
            if (!webPhone) return;

            // Show only accepted live sessions in during-call indicators
            const sessions = getDialerLiveSessions().filter(isSessionAcceptedForDuring);
            const totalCalls = sessions.length;

            // Update total calls count
            const totalCallsCount = document.getElementById('totalCallsCount');
            if (totalCallsCount) {
                totalCallsCount.textContent = totalCalls;
            }

            // Get current active session ID
            const activeSessionId = window._dialerCurrentSessionId;

            // Show badge for all active/held calls
            const heldCount = totalCalls;
            const heldBadge = document.getElementById('heldCallsBadge');
            const heldCountSpan = document.getElementById('heldCallsCount');

            if (heldCount > 0) {
                if (heldBadge) heldBadge.style.display = 'flex';
                if (heldCountSpan) heldCountSpan.textContent = heldCount;
            } else if (heldBadge) {
                heldBadge.style.display = 'none';
            }
        } catch (e) { console.warn('updateCallIndicators failed', e); }
    }

    function updateDialerCallInfo(sessionId) {
        try {
            if (!window.webPhone || !sessionId) return;

            const sessions = window.webPhone.listSessions() || [];
            const session = sessions.find((s) => getSessionIdCandidates(s).includes(String(sessionId)));

            if (session) {
                // Update remote name
                const nameEl = document.getElementById('dialerRemoteName');
                if (nameEl) {
                    const display = getSessionDisplayData(session);
                    nameEl.textContent = display.name;
                }

                // Update remote number
                const numEl = document.getElementById('dialerRemoteNumber');
                if (numEl) {
                    const display = getSessionDisplayData(session);
                    if (display.isConference) {
                        numEl.textContent = `(${display.number})`;
                    } else {
                        numEl.textContent = `(${display.number || '*****'})`;
                    }
                }

                // Check timing properties (consistent with Active Calls table)
                const startedAt = session.startedAt || session._ringingStartedAt || session.startedTimestamp;

                // Diagnostic: log timing properties
                const timingProps = {};
                ['startedAt', '_ringingStartedAt', 'startedTimestamp', '_startedAt', '_durationElapsed', 'startTime', 'createdAt', 'state'].forEach(prop => {
                    if (prop in session) {
                        timingProps[prop] = session[prop];
                    }
                });

                rcLog('Dialer call info updated for session:', sessionId, 'number:', session.remoteNumber, 'startedAt:', startedAt, 'timing properties:', timingProps);
            }
            refreshConferenceParticipantsButton();
        } catch (e) {
            console.warn('updateDialerCallInfo failed', e);
        }
    }

    function syncDialerToActiveSession(sessions = null) {
        try {
            if (!window.webPhone || typeof webPhone.listSessions !== 'function') return;
            const sourceSessions = Array.isArray(sessions) ? sessions : getDialerLiveSessions();
            const activeSessions = sourceSessions.filter(isSessionAcceptedForDuring);

            rcLog('[syncDialerToActiveSession] --- START ---');
            rcLog('[syncDialerToActiveSession] All sessions:', (sourceSessions || []).map(s => ({
                id: s.id || s.index || s.sessionId,
                state: s.state,
                hold: s.hold || s.onHold || s._onHold,
                remoteNumber: s.remoteNumber,
                startedAt: s.startedAt || s._ringingStartedAt || s.startedTimestamp || s._startedAt || s.startTime || s.createdAt || s.inviteTimestamp
            })));
            rcLog('syncDialerToActiveSession: found', activeSessions.length, 'active sessions');
            if (activeSessions.length > 0) {
                rcLog('  Sessions details:', activeSessions.map(s => ({
                    id: s.id || s.index,
                    state: s.state,
                    hold: s.hold || s.onHold || s._onHold,
                    remoteNumber: s.remoteNumber
                })));
            }

            if (!activeSessions.length) {
                rcLog('syncDialerToActiveSession: no active sessions, clearing dialer');
                window._dialerCurrentSessionId = null;
                const beforeSection = document.getElementById('dialerBeforeCallSection');
                const duringSection = document.getElementById('dialerDuringCallSection');
                if (duringSection) duringSection.style.display = 'none';
                if (beforeSection) beforeSection.style.display = 'block';
                stopDialerCallTimer();
                refreshConferenceParticipantsButton();
                return;
            }

            const currentId = window._dialerCurrentSessionId ? String(window._dialerCurrentSessionId) : '';
            const currentSession = currentId
                ? activeSessions.find((s) => getSessionIdCandidates(s).includes(currentId))
                : null;

            // If current session is still valid, keep it
            if (currentSession) {
                rcLog('syncDialerToActiveSession: current session still valid:', currentId);
                updateDialerCallInfo(currentId);
                updateMyCallSection();
                updateMultipleCallsDisplay();
                updateCallIndicators();
                // Do NOT update or change dialerCallsListPanel here
                return;
            }

            const targetSession = pickDialerSessionByPriority(activeSessions);
            const targetId = targetSession ? getSessionIdCandidates(targetSession)[0] : null;
            if (!targetId) {
                console.warn('syncDialerToActiveSession: no valid session ID found');
                return;
            }

            window._dialerCurrentSessionId = targetId;
            const duringSection = document.getElementById('dialerDuringCallSection');
            if (duringSection && duringSection.style.display === 'none') {
                switchDialerToDuringCall(targetId);
            } else {
                updateDialerCallInfo(targetId);
                updateMyCallSection();
                updateMultipleCallsDisplay();
                updateCallIndicators();
                startDialerCallTimer();
            }
        } catch (e) {
            console.warn('syncDialerToActiveSession failed', e);
        }
    }

    async function switchToCall(sessionId) {
        try {
            rcLog('Switching to call:', sessionId);
            if (webPhone && typeof webPhone.swapToSession === 'function') {
                await webPhone.swapToSession(sessionId);
                window._dialerCurrentSessionId = sessionId;
                updateDialerCallInfo(sessionId);
                updateMyCallSection();
                updateMultipleCallsDisplay();
                updateCallIndicators();
                refreshCallsListPanelIfVisible();
            } else {
                console.warn('switchToCall: webPhone.swapToSession not available');
            }
        } catch (e) { console.error('switchToCall failed', e); }
    }

    function refreshCallsListPanelIfVisible() {
        try {
            const panel = document.getElementById('dialerCallsListPanel');
            if (!panel) return;
            if (panel.classList.contains('calls-list-panel-visible')) {
                const view = String(panel.dataset.view || 'calls');
                if (view === 'participants' && panel.dataset.sessionId) {
                    openConferenceParticipants(panel.dataset.sessionId);
                } else {
                    openCallsList();
                }
            }
        } catch (e) {
            console.warn('refreshCallsListPanelIfVisible failed', e);
        }
    }

    function setCallsListPanelTitle(text) {
        try {
            const panel = document.getElementById('dialerCallsListPanel');
            const headerTitle = panel ? panel.querySelector('.swap-header h6') : null;
            if (headerTitle) {
                headerTitle.textContent = text || 'Calls';
            }
        } catch (_) { }
    }

    function openConferenceParticipants(sessionId) {
        try {
            if (!webPhone) return;
            const modal = document.getElementById('dialerCallsListPanel');
            if (!modal) return;
            const contentDiv = modal.querySelector('.calls-list-content');
            if (!contentDiv) return;

            const liveSessions = getDialerLiveSessions();
            const sid = String(sessionId || '');
            const session = liveSessions.find((s) => getSessionIdCandidates(s).includes(sid)) || null;
            if (!session) {
                openCallsList();
                return;
            }

            const display = getSessionDisplayData(session);
            const participants = Array.isArray(display.participants) ? display.participants : [];
            const participantCount = display.participantCount || participants.length || 0;
            const sessionMeta = getSessionMetaSafe(session);
            const conferenceSessionId = String(
                sessionMeta?.telephonySessionId
                || sessionMeta?.conferenceApiSessionId
                || session?.telephonySessionId
                || ''
            ).trim();

            let html = `
                <div class="rc-call-item">
                    <div class="rc-call-avatar-wrapper">
                        <div class="rc-call-avatar rc-call-avatar-active">
                            <i class="fa fa-users"></i>
                        </div>
                        <div style="flex: 1;">
                            <div class="rc-call-name">Conference Call</div>
                            <small class="rc-call-duration">${display.number || `${participantCount} participants`}</small>
                            <div class="rc-call-status">Participants (${participantCount})</div>
                        </div>
                    </div>
                    <div>
                        <div class="rc-call-duration-wrapper">
                            <button class="rc-call-btn" onclick="event.stopPropagation(); openCallsList()" title="Back to calls">
                                <i class="fa fa-arrow-left"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            if (participants.length === 0) {
                html += `<div class="rc-calls-empty">No participant details available yet.</div>`;
            } else {
                participants.forEach((participant) => {
                    const participantName = participant?.name || 'Participant';
                    const participantNumber = participant?.number || 'No number';
                    const participantRole = participant?.role ? ` (${participant.role})` : '';
                    const maskedNumber = participant?.number
                        ? maskDialerDisplayNumber(participant.number, { minMaskChars: 5, emptyFallback: participantNumber })
                        : participantNumber;
                    const participantSessionId = String(participant?.sessionId || '').trim();
                    const participantCallSessionId = String(participant?.callSessionId || '').trim();
                    const participantPartyId = String(participant?.partyId || '').trim();
                    const conferenceSessionIdForAction = conferenceSessionId.replace(/'/g, "\\'");
                    const participantNumberForAction = String(participant?.number || '').replace(/'/g, "\\'");
                    const canRemove = (!!participantSessionId || (!!participantCallSessionId && !!participantPartyId))
                        && String(participant?.role || '').toLowerCase() !== 'host';
                    const removeSessionArg = (participantCallSessionId && participantPartyId) ? '' : participantSessionId;
                    html += `
                        <div class="rc-call-item">
                            <div class="rc-call-avatar-wrapper">
                                <div class="rc-call-avatar rc-call-avatar-incoming">
                                    <i class="fa fa-user"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div class="rc-call-name">${participantName}${participantRole}</div>
                                    <small class="rc-call-duration">${maskedNumber}</small>
                                </div>
                                <div>
                                    <button type="button"
                                        class="rc-call-btn"
                                        ${canRemove ? '' : 'disabled'}
                                        title="${canRemove ? 'Remove participant' : 'Host cannot be removed'}"
                                        onclick="event.stopPropagation(); removeConferenceParticipantBySession('${removeSessionArg}', '${participantCallSessionId}', '${participantPartyId}', '${conferenceSessionIdForAction}', '${participantNumberForAction}')">
                                        <i class="fa fa-user-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }

            contentDiv.innerHTML = html;
            modal.dataset.view = 'participants';
            modal.dataset.sessionId = sid;
            setCallsListPanelTitle(`Participants (${participantCount})`);
            modal.classList.remove('calls-list-panel-hidden');
            modal.classList.remove('calls-list-closing');
            modal.classList.add('calls-list-panel-visible');
        } catch (e) {
            console.warn('openConferenceParticipants failed', e);
        }
    }

    async function removeConferenceParticipantBySession(sessionId, callSessionId = '', partyId = '', conferenceSessionId = '', participantNumber = '') {
        try {
            const targetSessionId = String(sessionId || '').trim();
            const targetCallSessionId = String(callSessionId || '').trim();
            const targetPartyId = String(partyId || '').trim();
            const targetConferenceSessionId = String(conferenceSessionId || '').trim();
            const targetParticipantNumber = String(participantNumber || '').trim();
            const removeKey = [
                targetConferenceSessionId || 'no-conf',
                targetCallSessionId || 'no-call',
                targetPartyId || 'no-party',
                targetParticipantNumber || 'no-number'
            ].join('|');
            window._rcParticipantRemoveInFlight = window._rcParticipantRemoveInFlight || new Set();
            if (window._rcParticipantRemoveInFlight.has(removeKey)) {
                console.info('[REMOVE DEBUG] Skip duplicate participant remove request', { removeKey });
                return;
            }
            window._rcParticipantRemoveInFlight.add(removeKey);
            console.info('[REMOVE DEBUG] removeConferenceParticipantBySession start', {
                sessionId: targetSessionId,
                callSessionId: targetCallSessionId,
                partyId: targetPartyId,
                conferenceSessionId: targetConferenceSessionId,
                participantNumber: targetParticipantNumber
            });
            if (!targetSessionId && !(targetCallSessionId && targetPartyId)) return;
            if (!window.webPhone) throw new Error('WebPhone not initialized');

            let removed = false;
            let lastErr = null;

            const hasCallControlIds = !!(targetCallSessionId && targetPartyId);
            // Prefer call-control remove when identifiers are present; WebPhone session lookup is often not keyed by telephony session id.
            if (!hasCallControlIds && targetSessionId && typeof webPhone.hangupSession === 'function') {
                try {
                    await webPhone.hangupSession(targetSessionId);
                    removed = true;
                    console.info('[REMOVE DEBUG] Participant removed via WebPhone session', {
                        sessionId: targetSessionId
                    });
                    rcInfo('[CONFERENCE] Participant removed via WebPhone session', { sessionId: targetSessionId });
                } catch (err) {
                    lastErr = err;
                    console.warn('[REMOVE DEBUG] WebPhone hangupSession failed', {
                        sessionId: targetSessionId,
                        message: String(err?.message || err || '')
                    });
                }
            }

            if (!removed && targetCallSessionId && targetPartyId) {
                const lastMessage = String(lastErr?.message || '');
                const canFallback = hasCallControlIds && (!lastErr || /target session not found/i.test(lastMessage));
                console.info('[REMOVE DEBUG] Evaluating call-control fallback', {
                    canFallback,
                    lastError: lastMessage
                });
                if (canFallback) {
                    await removeParticipantByCallControl(targetCallSessionId, targetPartyId, {
                        conferenceSessionId: targetConferenceSessionId,
                        participantNumber: targetParticipantNumber
                    });
                    removed = true;
                    console.info('[REMOVE DEBUG] Participant removed via call-control fallback', {
                        callSessionId: targetCallSessionId,
                        partyId: targetPartyId
                    });
                    rcInfo('[CONFERENCE] Participant removed via call-control fallback', {
                        callSessionId: targetCallSessionId,
                        partyId: targetPartyId
                    });
                }
            }

            if (!removed && lastErr) {
                throw lastErr;
            }
            if (!removed) {
                throw new Error('Participant remove failed: no valid session/party identifiers');
            }

            setTimeout(() => {
                refreshConferenceParticipantsButton();
                refreshCallsListPanelIfVisible();
            }, 150);
        } catch (e) {
            const msg = String(e?.message || e || '');
            if (/wrongpartystate|incorrect state|already disconnected/i.test(msg)) {
                console.warn('[REMOVE DEBUG] Participant already disconnected; suppressing hard error', { message: msg });
                setTimeout(() => {
                    refreshConferenceParticipantsButton();
                    refreshCallsListPanelIfVisible();
                }, 120);
                return;
            }
            console.error('removeConferenceParticipantBySession failed', e);
            alert(`Remove participant failed: ${e.message || e}`);
        } finally {
            try {
                const targetCallSessionId = String(callSessionId || '').trim();
                const targetPartyId = String(partyId || '').trim();
                const targetConferenceSessionId = String(conferenceSessionId || '').trim();
                const targetParticipantNumber = String(participantNumber || '').trim();
                const removeKey = [
                    targetConferenceSessionId || 'no-conf',
                    targetCallSessionId || 'no-call',
                    targetPartyId || 'no-party',
                    targetParticipantNumber || 'no-number'
                ].join('|');
                window._rcParticipantRemoveInFlight = window._rcParticipantRemoveInFlight || new Set();
                window._rcParticipantRemoveInFlight.delete(removeKey);
            } catch (_) { }
        }
    }

    async function removeParticipantByCallControl(callSessionId, partyId, options = {}) {
        const sessionId = String(callSessionId || '').trim();
        const party = String(partyId || '').trim();
        if (!sessionId || !party) {
            throw new Error('Missing call-control identifiers for participant removal');
        }
        const conferenceSessionId = String(options?.conferenceSessionId || '').trim();
        const participantNumber = String(options?.participantNumber || '').trim();

        let url = `${window.location.origin}/api/r/call-control/sessions/${encodeURIComponent(sessionId)}/parties/${encodeURIComponent(party)}`;
        if (typeof rcRoute === 'function') {
            try {
                url = rcRoute('ringcentral.api.call-control.remove-party', { sessionId, partyId: party });
            } catch (_) {
                // Route map can be stale/missing in current blade payload; keep absolute fallback URL.
            }
        }

        const requestPayload = {
            conference_session_id: conferenceSessionId || null,
            participant_number: participantNumber || null,
            requested_party_id: party
        };
        console.info('[REMOVE DEBUG] removeParticipantByCallControl request', {
            url,
            sessionId,
            partyId: party,
            payload: requestPayload
        });

        const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));
        let lastResp = null;
        let lastPayload = null;
        const maxAttempts = 2;
        for (let attempt = 1; attempt <= maxAttempts; attempt++) {
            const resp = await fetch(url, {
                method: 'DELETE',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify(requestPayload)
            });

            let payload = null;
            try { payload = await resp.json(); } catch (_) { payload = null; }
            console.info('[REMOVE DEBUG] removeParticipantByCallControl response', {
                attempt,
                status: resp.status,
                ok: resp.ok,
                payload
            });

            lastResp = resp;
            lastPayload = payload;
            const retriable = [502, 503, 504].includes(resp.status);
            if (retriable && attempt < maxAttempts) {
                await sleep(450);
                continue;
            }

            if (!resp.ok || payload?.success === false) {
                const msg = payload?.message || `HTTP ${resp.status}`;
                throw new Error(msg);
            }

            return payload;
        }
        const fallbackMsg = lastPayload?.message || `HTTP ${lastResp?.status || 500}`;
        throw new Error(fallbackMsg);
    }

    function openCallsList() {
        try {
            if (!webPhone) return;

            const modal = document.getElementById('dialerCallsListPanel');
            if (!modal) {
                console.warn('dialerCallsListPanel not found');
                return;
            }

            const contentDiv = modal.querySelector('.calls-list-content');
            if (!contentDiv) return;

            const liveSessions = getDialerLiveSessions();
            const activeSessionId = window._dialerCurrentSessionId ? String(window._dialerCurrentSessionId) : '';
            modal.dataset.view = 'calls';
            delete modal.dataset.sessionId;
            setCallsListPanelTitle('Calls');

            const normalizeTime = (value) => {
                if (!value) return null;
                if (value instanceof Date) return value.getTime();
                if (typeof value === 'number') return value < 1e12 ? value * 1000 : value;
                if (typeof value === 'string') {
                    const parsed = Date.parse(value);
                    return isNaN(parsed) ? null : parsed;
                }
                return null;
            };
            const escapeAttr = (value) => String(value ?? '').replace(/&/g, '&amp;').replace(/"/g, '&quot;');
            const rowModels = [];

            let listHtml = '';
            if (liveSessions.length === 0) {
                listHtml += `<div class="rc-calls-empty">No active calls</div>`;
            } else {
                liveSessions.forEach((session, idx) => {
                    const sessionIds = getSessionIdCandidates(session);
                    const sessionId = sessionIds[0] || String(idx);
                    const display = getSessionDisplayData(session);
                    const displayNumber = display.number || 'Unknown';
                    const maskedRemoteNumber = display.isConference
                        ? display.name
                        : maskDialerDisplayNumber(displayNumber, { minMaskChars: 5, emptyFallback: displayNumber });
                    const isActive = !!activeSessionId && sessionIds.includes(activeSessionId);
                    const isIncoming = isSessionIncomingForDialer(session) && !isSessionAcceptedForDuring(session);
                    const isOnHold = !!(session.hold || session._onHold || session.onHold);
                    let startedAt = session.startedAt || session._ringingStartedAt || session.startedTimestamp ||
                        session._startedAt || session.startTime || session.createdAt || session.inviteTimestamp;
                    let startedAtMs = normalizeTime(startedAt);
                    if (!startedAtMs) {
                        window._dialerSessionStartTimes = window._dialerSessionStartTimes || {};
                        if (!window._dialerSessionStartTimes[sessionId]) {
                            window._dialerSessionStartTimes[sessionId] = Date.now();
                        }
                        startedAtMs = window._dialerSessionStartTimes[sessionId];
                    }
                    const durationSecs = Math.max(0, Math.floor((Date.now() - startedAtMs) / 1000));
                    const durationStr = `${Math.floor(durationSecs / 60)}:${String(durationSecs % 60).padStart(2, '0')}`;
                    const avatarClass = isActive ? 'rc-call-avatar-active' : (isIncoming ? 'rc-call-avatar-incoming' : (isOnHold ? 'rc-call-avatar-hold' : 'rc-call-avatar-incoming'));
                    const statusText = display.isConference
                        ? `Participants (${display.participantCount || display.participants?.length || 0})`
                        : (isActive ? 'Active' : (isIncoming ? 'Incoming' : (isOnHold ? 'On Hold' : 'Connected')));
                    const extraStatus = display.isConference ? display.number : '';
                    const rowKey = sessionIds.length
                        ? sessionIds.slice().sort().join('|')
                        : `${sessionId}|${displayNumber}|${idx}`;
                    rowModels.push({
                        key: rowKey,
                        name: maskedRemoteNumber,
                        duration: durationStr,
                        status: statusText,
                        extraStatus,
                        avatarClass
                    });
                    const switchBtnHtml = isIncoming
                        ? `<button class="rc-call-btn" title="View incoming call" onclick="event.stopPropagation(); showIncomingSessionFromList('${sessionId}')"><i class="fa fa-eye"></i></button>`
                        : `<button class="rc-call-btn" title="Switch to this call" onclick="event.stopPropagation(); switchToCall('${sessionId}')"><i class="fa fa-exchange"></i></button>`;
                    const actionsHtml = isIncoming
                        ? `
                                <button type="button"
                                    onclick="event.stopPropagation(); answerIncomingCallById('${sessionId}')"
                                    class="rc-call-btn"
                                    title="Answer call">
                                    <i class="fa fa-phone"></i>
                                </button>
                                <button type="button"
                                    onclick="event.stopPropagation(); declineIncomingCallById('${sessionId}')"
                                    class="rc-call-btn"
                                    title="Decline call">
                                    <i class="fa fa-phone dialer-incoming-decline-icon"></i>
                                </button>
                          `
                        : `
                                <button type="button"
                                    onclick="event.stopPropagation(); toggleCallHoldFromSidebar('${sessionId}')"
                                    class="rc-call-btn" title="${isOnHold ? 'Resume' : 'Hold'}">
                                    <i class="fa ${isOnHold ? 'fa-play' : 'fa-pause'}"></i>
                                </button>
                                <button type="button"
                                    ${display.isConference ? '' : 'disabled'}
                                    title="${display.isConference ? 'View participants' : 'Participants are available for conference calls only'}"
                                    class="rc-call-btn"
                                    onclick="event.stopPropagation(); openConferenceParticipants('${sessionId}')">
                                    <i class="fa fa-users"></i>
                                </button>
                                <button type="button"
                                    onclick="event.stopPropagation(); endCallFromSidebar('${sessionId}')"
                                    class="rc-call-btn"
                                    title="End call">
                                    <i class="fa fa-phone dialer-incoming-decline-icon"></i>
                                </button>
                          `;

                    listHtml += `
                        <div class="rc-call-item" data-session-key="${escapeAttr(rowKey)}">
                            <div class="rc-call-avatar-wrapper">
                                <div class="rc-call-avatar ${avatarClass}" data-role="avatar">
                                    <i class="fa fa-phone"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div class="rc-call-name" data-role="name">${maskedRemoteNumber}</div>
                                    <small class="rc-call-duration" data-role="duration">${durationStr}</small>
                                    <div class="rc-call-status" data-role="status">${statusText}</div>
                                    ${display.isConference ? `<div class="rc-call-status" data-role="extra-status">${display.number}</div>` : ''}
                                </div>
                            </div>
                            <div>
                                <div class="rc-call-duration-wrapper">
                                    ${switchBtnHtml}
                                    ${actionsHtml}
                                </div>
                            </div>
                        </div>
                    `;
                });
            }

            const signature = liveSessions.length
                ? rowModels.map((row) => row.key).join('||')
                : 'empty';
            if (contentDiv.dataset.callsSignature === signature) {
                const rows = Array.from(contentDiv.querySelectorAll('.rc-call-item'));
                rowModels.forEach((model, index) => {
                    const row = rows[index];
                    if (!row) return;
                    const nameEl = row.querySelector('[data-role="name"]');
                    const durationEl = row.querySelector('[data-role="duration"]');
                    const statusEl = row.querySelector('[data-role="status"]');
                    const extraStatusEl = row.querySelector('[data-role="extra-status"]');
                    const avatarEl = row.querySelector('[data-role="avatar"]');
                    if (nameEl && nameEl.textContent !== model.name) nameEl.textContent = model.name;
                    if (durationEl && durationEl.textContent !== model.duration) durationEl.textContent = model.duration;
                    if (statusEl && statusEl.textContent !== model.status) statusEl.textContent = model.status;
                    if (extraStatusEl && extraStatusEl.textContent !== model.extraStatus) extraStatusEl.textContent = model.extraStatus;
                    if (avatarEl) {
                        avatarEl.classList.remove('rc-call-avatar-active', 'rc-call-avatar-incoming', 'rc-call-avatar-hold');
                        avatarEl.classList.add(model.avatarClass);
                    }
                });
            } else {
                contentDiv.innerHTML = listHtml;
                contentDiv.dataset.callsSignature = signature;
            }
            modal.classList.remove('calls-list-panel-hidden');
            modal.classList.remove('calls-list-closing');
            modal.classList.add('calls-list-panel-visible');

            if (!modal._rcTimerRefresh) {
                modal._rcTimerRefresh = setInterval(() => {
                    if (modal.classList.contains('calls-list-panel-visible')) {
                        const view = String(modal.dataset.view || 'calls');
                        if (view === 'participants' && modal.dataset.sessionId) {
                            openConferenceParticipants(modal.dataset.sessionId);
                        } else {
                            openCallsList();
                        }
                    } else {
                        clearInterval(modal._rcTimerRefresh);
                        modal._rcTimerRefresh = null;
                    }
                }, 1000);
            }
        } catch (e) { console.warn('openCallsList failed', e); }
    }

    function toggleCallsDropdown(event) {
        try {
            event.preventDefault();
            event.stopPropagation();
            const dropdown = document.getElementById('callsDropdown');
            if (!dropdown) return;

            const isVisible = dropdown.style.display === 'block';
            dropdown.style.display = isVisible ? 'none' : 'block';

            if (!isVisible) {
                // Populate dropdown with all calls
                const sessions = webPhone?.listSessions?.() || [];
                const activeSessionId = window._dialerCurrentSessionId;

                let html = '';
                if (sessions.length === 0) {
                    html = '<div class="rc-calls-dropdown-empty">No calls</div>';
                } else {
                    sessions.forEach((session, idx) => {
                        const sessionId = session.id || session.index || idx;
                        const display = getSessionDisplayData(session);
                        const maskedRemoteNumber = display.isConference
                            ? display.name
                            : maskDialerDisplayNumber(display.number || 'Unknown', { minMaskChars: 5, emptyFallback: (display.number || 'Unknown') });
                        const state = (session.state || '').toString().toLowerCase();
                        const direction = (session.direction || '').toString().toLowerCase();
                        const isActive = sessionId === activeSessionId;
                        const isOnHold = !!(session.hold || session._onHold || session.onHold);
                        const isIncoming = state.includes('incoming') || state.includes('ringing') || direction.includes('in');
                        const statusIcon = isActive ? '🟢' : (isOnHold ? '⏸️' : (isIncoming ? '📞' : '⚪'));
                        html += `
                                <div class="rc-calls-dropdown-item" onclick="switchToCall('${sessionId}'); document.getElementById('callsDropdown').style.display='none';">
                                    <span>${statusIcon}</span>
                                    <span>${maskedRemoteNumber}</span>
                                </div>
                            `;
                    });
                }
                dropdown.innerHTML = html;
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function closeDropdown(e) {
                const badge = document.getElementById('heldCallsBadge');
                if (badge && !badge.contains(e.target)) {
                    dropdown.style.display = 'none';
                    document.removeEventListener('click', closeDropdown);
                }
            });
        } catch (e) { console.error('toggleCallsDropdown failed', e); }
    }

    async function toggleCallHoldFromSidebar(sessionId) {
        try {
            const session = (webPhone && typeof webPhone.findSession === 'function')
                ? webPhone.findSession(sessionId)
                : null;
            const isOnHold = !!(session && (session._onHold || session.onHold || session._hold || (session.status && session.status.toString().toLowerCase().includes('hold'))));

            if (isOnHold && webPhone && typeof webPhone.swapToSession === 'function') {
                await switchToCall(sessionId);
            } else {
                window._dialerCurrentSessionId = sessionId;
                await holdCallFromDialer();
                refreshCallsListPanelIfVisible();
            }
        } catch (e) { console.error('toggleCallHoldFromSidebar failed', e); }
    }

    async function endCallFromSidebar(sessionId) {
        try {
            const sid = String(sessionId || '').trim();
            if (!sid) return;
            if (!window.webPhone) throw new Error('WebPhone not initialized');

            if (typeof webPhone.hangupSession === 'function') {
                await webPhone.hangupSession(sid);
            } else if (typeof webPhone.hangup === 'function') {
                await webPhone.hangup(sid);
            } else if (typeof webPhone.endCall === 'function') {
                const previousCurrentId = window._dialerCurrentSessionId;
                window._dialerCurrentSessionId = sid;
                await webPhone.endCall();
                window._dialerCurrentSessionId = previousCurrentId;
            } else {
                throw new Error('No supported end-call method available');
            }

            setTimeout(() => {
                try { if (typeof window.syncDialerLiveCallState === 'function') window.syncDialerLiveCallState('sidebar-end-call'); } catch (_) { }
                try { refreshCallsListPanelIfVisible(); } catch (_) { }
            }, 150);
        } catch (e) {
            console.error('endCallFromSidebar failed', e);
            alert(`End call failed: ${e.message || e}`);
        }
    }

    async function mergeCallFromDialer(targetSessionId = null) {
        try {
            const activeSessions = getActiveDialerSessions();
            const hasWebMerge = !!(window.webPhone && activeSessions.length >= 2);
            if (!hasWebMerge) {
                throw new Error('Merge requires two active web calls');
            }

            const currentId = window._dialerCurrentSessionId
                || activeSessions[0].id
                || activeSessions[0].sessionId
                || activeSessions[0].index;

            let mergeTargetId = targetSessionId ? String(targetSessionId) : null;
            if (!mergeTargetId) {
                const other = activeSessions.find(function (s) {
                    const sid = String(s.id || s.sessionId || s.index || '');
                    return sid && sid !== String(currentId || '');
                });
                mergeTargetId = other ? String(other.id || other.sessionId || other.index || '') : null;
            }

            if (!mergeTargetId) {
                throw new Error('Could not find another active web call to merge');
            }

            rcInfo('[MERGE-FLOW] WebPhone conference merge only', {
                currentId: currentId || null,
                mergeTargetId
            });
            setMergeButtonUiState(true);

            if (typeof webPhone.mergeWith === 'function') {
                await webPhone.mergeWith(mergeTargetId);
            } else if (typeof webPhone.mergeCalls === 'function') {
                const targetSession = (typeof webPhone.findSession === 'function')
                    ? webPhone.findSession(mergeTargetId)
                    : null;
                await webPhone.mergeCalls(targetSession);
            } else if (typeof webPhone.startConference === 'function') {
                await webPhone.startConference();
            } else {
                throw new Error('Merge is not supported by this WebPhone build');
            }

            rcInfo('[MERGE-FLOW] Merge initiated');
            return;
        } catch (e) {
            console.error('mergeCallFromDialer failed', e);
            alert(`Merge failed: ${e.message || e}`);
        } finally {
            setMergeButtonUiState(false);
            updateDialerActionButtons();
        }
    }

    function mergeCallFromSidebar(sessionId) {
        try {
            mergeCallFromDialer(sessionId);
        } catch (e) { console.error('mergeCallFromSidebar failed', e); }
    }

    function inviteCallFromDialer() {
        try {
            if (!window.webPhone) throw new Error('WebPhone not initialized');

            const hasActiveCall =
                (typeof webPhone.isCallActive === 'function' && webPhone.isCallActive())
                || !!webPhone.currentSession
                || (Array.isArray(webPhone.callSessions) && webPhone.callSessions.length > 0);

            if (!hasActiveCall) {
                throw new Error('Invite requires an active call');
            }

            if (typeof webPhone.inviteIntoActiveConference !== 'function') {
                throw new Error('Invite into active conference is not supported by the current client implementation');
            }

            // Show invite modal
            openInviteModal();
        } catch (e) {
            console.error('inviteCallFromDialer failed', e);
            alert(`Invite failed: ${e.message || e}`);
        }
    }

    function openInviteModal() {
        const overlay = document.getElementById('dialerInviteOverlay');
        const modal = document.getElementById('dialerInviteModal');
        const input = document.getElementById('invitePhoneNumber');

        if (overlay && modal) {
            overlay.classList.add('show');
            modal.classList.add('show');
            if (input) {
                input.value = '';
                setTimeout(() => input.focus(), 100);
            }
        }
    }

    function closeInviteModal() {
        const overlay = document.getElementById('dialerInviteOverlay');
        const modal = document.getElementById('dialerInviteModal');
        const spinner = document.getElementById('inviteLoadingSpinner');
        const confirmBtn = document.getElementById('confirmInviteBtn');

        if (overlay && modal) {
            overlay.classList.remove('show');
            modal.classList.remove('show');
        }

        // Reset UI
        if (spinner) spinner.classList.add('d-none');
        if (confirmBtn) confirmBtn.disabled = false;
    }

    async function confirmInviteCall() {
        try {
            if (!window.webPhone) throw new Error('WebPhone not initialized');

            const input = document.getElementById('invitePhoneNumber');
            const toNumber = (input?.value || '').trim();

            if (!toNumber) {
                alert('Please enter a phone number to invite');
                return;
            }

            const confirmBtn = document.getElementById('confirmInviteBtn');
            const spinner = document.getElementById('inviteLoadingSpinner');

            if (confirmBtn) confirmBtn.disabled = true;
            if (spinner) spinner.classList.remove('d-none');

            const fromSelect = document.getElementById('callFromNumber');
            const fromNumber = (fromSelect?.value || '').trim() || null;

            const hasActiveCall =
                (typeof webPhone.isCallActive === 'function' && webPhone.isCallActive())
                || !!webPhone.currentSession
                || (Array.isArray(webPhone.callSessions) && webPhone.callSessions.length > 0);

            if (!hasActiveCall) {
                throw new Error('Invite requires an active call');
            }

            if (typeof webPhone.inviteIntoActiveConference !== 'function') {
                throw new Error('Invite into active conference is not available in this client');
            }

            // Add participant into existing conference only (do not create a new conference here).
            const inviteResult = await webPhone.inviteIntoActiveConference(toNumber, fromNumber);

            if (inviteResult?.source === 'transfer-fallback') {
                rcInfo('Bring-in is restricted (TAS-106). Used transfer fallback to add participant.');
            } else {
                rcInfo('Participant invite initiated');
            }
            closeInviteModal();
        } catch (e) {
            console.error('confirmInviteCall failed', e);
            alert(`Invite failed: ${e.message || e}`);

            // Reset UI on error
            const spinner = document.getElementById('inviteLoadingSpinner');
            const confirmBtn = document.getElementById('confirmInviteBtn');
            if (spinner) spinner.classList.add('d-none');
            if (confirmBtn) confirmBtn.disabled = false;
        }
    }


    async function startConferenceInviteFromCallForm() {
        try {
            if (!window.webPhone) throw new Error('WebPhone not initialized');

            const btn = document.getElementById('callFormConferenceBtn');
            if (btn) {
                btn.disabled = true;
                btn.title = 'Starting conference...';
            }

            // Start conference only. Participant invite is handled separately via Invite action.
            await webPhone.startConference();

            rcInfo('Conference started');
        } catch (e) {
            console.error('startConferenceInviteFromCallForm failed', e);
            alert(`Conference failed: ${e.message || e}`);
        } finally {
            const btn = document.getElementById('callFormConferenceBtn');
            if (btn) {
                btn.disabled = false;
                btn.title = 'Start conference';
            }
            updateDialerActionButtons();
        }
    }

    function closeCallsList() {
        try {
            const panel = document.getElementById('dialerCallsListPanel');
            if (panel) {
                panel.dataset.view = 'calls';
                delete panel.dataset.sessionId;
                setCallsListPanelTitle('Calls');
                panel.classList.add('calls-list-closing');
                setTimeout(() => {
                    panel.classList.remove('calls-list-panel-visible');
                    panel.classList.remove('calls-list-closing');
                    panel.classList.add('calls-list-panel-hidden');
                }, 300);
            }
        } catch (e) { console.warn('closeCallsList failed', e); }
    }

    function populateMultiCallPhoneNumbers() {
        try {
            const sel = document.getElementById('multiCallFromNumber');
            if (!sel) return;
            // Copy from main dialer dropdown if available
            const mainSel = document.getElementById('callFromNumber');
            if (mainSel && mainSel.innerHTML) {
                sel.innerHTML = mainSel.innerHTML;
                sel.value = mainSel.value;
            }
        } catch (e) { console.warn('populateMultiCallPhoneNumbers failed', e); }
    }

    // Handle multi-call form submission
    document.addEventListener('DOMContentLoaded', function () {
        try {
            const multiCallForm = document.getElementById('multiCallForm');
            if (multiCallForm) {
                multiCallForm.onsubmit = function (e) {
                    e.preventDefault();
                    try {
                        const phoneInput = document.getElementById('multiCallPhone');
                        const fromNumber = document.getElementById('multiCallFromNumber');
                        if (phoneInput && phoneInput.value) {
                            // Set the main dialer values and make the call
                            const mainPhone = document.getElementById('callPhone');
                            const mainFrom = document.getElementById('callFromNumber');
                            if (mainPhone) mainPhone.value = phoneInput.value;
                            if (mainFrom && fromNumber) mainFrom.value = fromNumber.value;

                            // Make the call
                            const mainForm = document.getElementById('callForm');
                            if (mainForm) mainForm.dispatchEvent(new Event('submit'));

                            // Go back to call controls
                            setTimeout(() => backToCallControls(), 500);
                        }
                    } catch (err) { console.warn('multiCallForm submit failed', err); }
                };
            }
        } catch (e) { console.warn('multiCallForm setup failed', e); }

        try {
            const transferBtn = document.getElementById('dialerTransferBtn');
            if (transferBtn && !transferBtn.onclick) {
                transferBtn.addEventListener('click', transferCallFromDialer);
            }

            const mergeBtn = document.getElementById('dialerMergeBtn');
            if (mergeBtn) {
                mergeBtn.addEventListener('click', mergeCallFromDialer);
            }

            const inviteBtn = document.getElementById('dialerInviteBtn');
            if (inviteBtn && !inviteBtn.onclick) {
                inviteBtn.addEventListener('click', inviteCallFromDialer);
            }

            const switchBtn = document.getElementById('dialerSwitchToWebBtn');
            if (switchBtn && !switchBtn.onclick) {
                switchBtn.addEventListener('click', switchToWebFromDialer);
            }

            const callFormConferenceBtn = document.getElementById('callFormConferenceBtn');
            if (callFormConferenceBtn && !callFormConferenceBtn.onclick) {
                callFormConferenceBtn.addEventListener('click', startConferenceInviteFromCallForm);
            }

            // Setup invite modal keyboard support
            const invitePhoneInput = document.getElementById('invitePhoneNumber');
            if (invitePhoneInput) {
                invitePhoneInput.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        confirmInviteCall();
                    }
                });
            }

            const transferPhoneInput = document.getElementById('transferPhoneNumber');
            if (transferPhoneInput) {
                transferPhoneInput.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        confirmTransferCall();
                    }
                });
            }

            updateDialerActionButtons();
            setTimeout(() => syncDialerLiveCallState('DOMContentLoaded:init'), 120);
        } catch (e) {
            console.warn('dialer transfer/merge/invite setup failed', e);
        }
    });

    ['ringcentral:callStarted', 'ringcentral:callConnected', 'ringcentral:callAnswered', 'ringcentral:incomingCall', 'ringcentral:callEnded'].forEach(function (eventName) {
        document.addEventListener(eventName, function () {
            try {
                setTimeout(updateDialerActionButtons, 50);
                setTimeout(() => syncDialerLiveCallState(eventName), 70);
                setTimeout(refreshCallsListPanelIfVisible, 80);
            } catch (_) { }
        });
    });

    document.addEventListener('ringcentral:callControlSessionsUpdated', function () {
        try {
            setTimeout(updateDialerActionButtons, 30);
            setTimeout(() => syncDialerLiveCallState('ringcentral:callControlSessionsUpdated'), 60);
        } catch (_) { }
    });

    ['ringcentral:swapped', 'ringcentral:holdStarted', 'ringcentral:holdEnded', 'ringcentral:heldStatusChanged'].forEach(function (eventName) {
        document.addEventListener(eventName, function () {
            try {
                setTimeout(() => syncDialerLiveCallState(eventName), 40);
                setTimeout(refreshCallsListPanelIfVisible, 50);
            } catch (_) { }
        });
    });

    ['ringcentral:conferenceStarted', 'ringcentral:merged'].forEach(function (eventName) {
        document.addEventListener(eventName, function (event) {
            try {
                const eventSession = event?.detail?.session || null;
                const eventSessionId = eventSession
                    ? (getSessionIdCandidates(eventSession)[0] || eventSession.id || eventSession.sessionId || null)
                    : null;
                setDialerForceDuringWindow(20000, eventName, eventSessionId);
                setTimeout(() => ensureDuringCallSectionForAnyActiveSession(eventName), 30);
                setTimeout(() => syncDialerLiveCallState(eventName), 60);
                setTimeout(refreshCallsListPanelIfVisible, 90);
            } catch (_) { }
        });
    });

    function toggleAudioInDialer() {
        try {
            const btn = document.getElementById('dialerAudioBtn');
            if (!btn) return;
            // Toggle audio device selector
            openMicTestModal();
        } catch (e) { console.warn('toggleAudioInDialer failed', e); }
    }

    function addCallFromDialer() {
        try {
            const btn = document.getElementById('dialerAddBtn');
            if (!btn) return;
            btn.style.background = btn.style.background === '#e3f2fd' ? '#f0f0f0' : '#e3f2fd';
            // Trigger add call logic
        } catch (e) { console.warn('addCallFromDialer failed', e); }
    }
    // ===== END: CALL CONTROL FUNCTIONS =====

    // ===== START: CALL HOLD/TRANSFER FUNCTIONS =====
    async function holdCallFromDialer() {
        const restoreHoldBtn = window.rcSetActionButtonLoading
            ? window.rcSetActionButtonLoading('dialerHoldBtn', { loadingText: 'Updating hold...', preserveContent: true, statusText: 'Updating hold...' })
            : null;
        try {
            let sid = window._dialerCurrentSessionId;

            // Fallback 1: if no session ID, try to get from WebPhone's current session
            if (!sid && webPhone && webPhone.currentSession) {
                try {
                    sid = webPhone.currentSession.id || webPhone.currentSession.sessionId || webPhone.currentSession.index;
                    window._dialerCurrentSessionId = sid;
                    rcLog('holdCallFromDialer: resolved session from webPhone.currentSession:', sid);
                } catch (_) { }
            }

            // Fallback 2: if still no ID, try to get from active sessions list
            if (!sid && webPhone) {
                try {
                    const sessions = webPhone.listSessions ? (webPhone.listSessions() || []) : [];
                    if (sessions.length > 0) {
                        // Prefer the current session, fall back to first one
                        const currentSes = webPhone.currentSession;
                        const matchedCurrent = currentSes ? sessions.find(s => (s.id || s.sessionId) === (currentSes.id || currentSes.sessionId)) : null;
                        const targetSession = matchedCurrent || sessions[0];
                        sid = targetSession.id || targetSession.sessionId || targetSession.index || targetSession;
                        window._dialerCurrentSessionId = sid;
                        rcLog('holdCallFromDialer: resolved session from listSessions:', sid);
                    }
                } catch (_) { }
            }

            if (!sid) {
                console.warn('holdCallFromDialer: no active session found');
                return;
            }

            if (!webPhone) throw new Error('WebPhone not initialized');

            const holdBtn = document.getElementById('dialerHoldBtn');
            if (!holdBtn) return;

            try {
                let sessionBeforeToggle = null;
                try {
                    if (typeof webPhone.findSession === 'function') {
                        sessionBeforeToggle = webPhone.findSession(sid);
                    }
                } catch (_) { }
                const wasOnHold = !!(sessionBeforeToggle && (sessionBeforeToggle._onHold || sessionBeforeToggle.onHold || sessionBeforeToggle._hold || (sessionBeforeToggle.status && sessionBeforeToggle.status.toString().toLowerCase().includes('hold'))));

                if (wasOnHold && typeof webPhone.swapToSession === 'function') {
                    await webPhone.swapToSession(sid);
                }

                // Call the SDK hold toggle
                rcLog('holdCallFromDialer: calling webPhone.toggleHold for sid=', sid);
                const result = wasOnHold && typeof webPhone.swapToSession === 'function'
                    ? false
                    : await webPhone.toggleHold(sid);
                rcLog('holdCallFromDialer: raw result=', result);

                // Robustly determine hold state. SDK may return boolean, object, or update session asynchronously.
                let isOnHold;
                try {
                    if (typeof result === 'boolean') isOnHold = result;
                    else if (result && typeof result.hold === 'boolean') isOnHold = result.hold;

                    let session = null;
                    if (typeof webPhone.findSession === 'function') {
                        try { session = webPhone.findSession(sid); } catch (_) { session = null; }
                    }
                    if (!session && window._lastActiveSessions) {
                        try { session = window._lastActiveSessions.find(s => (s.id || s.sessionId || s.partyId || s.callId || s.index) == sid) || null; } catch (_) { session = null; }
                    }
                    if (session) {
                        try { attachSessionUiListeners(session); } catch (_) { }
                        if (typeof isOnHold === 'undefined') {
                            isOnHold = !!(session.hold || session._hold || session.onHold || (session.status && session.status.toString().toLowerCase().includes('hold')));
                        }
                        rcLog('holdCallFromDialer: session snapshot=', session);
                    }
                } catch (err) { console.warn('holdCallFromDialer: determine hold state failed', err); }

                if (typeof isOnHold === 'undefined') {
                    try {
                        setTimeout(() => {
                            try {
                                let session2 = null;
                                if (typeof webPhone.findSession === 'function') session2 = webPhone.findSession(sid);
                                if (!session2 && window._lastActiveSessions) session2 = window._lastActiveSessions.find(s => (s.id || s.sessionId || s.partyId || s.callId || s.index) == sid) || null;
                                const hasHold = !!(session2 && (session2.hold || session2._hold || session2.onHold || (session2.status && session2.status.toString().toLowerCase().includes('hold'))));
                                document.dispatchEvent(new CustomEvent('ringcentral:heldStatusChanged', { detail: { sessionId: sid, onHold: hasHold } }));
                                rcLog('holdCallFromDialer: delayed hold check, sid=', sid, 'hasHold=', hasHold, 'session2=', session2);
                            } catch (_) { }
                        }, 250);
                    } catch (_) { }
                    isOnHold = !!result;
                }

                // Update button appearance
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

                // Update per-row hold button and status if present
                try {
                    const rowHoldBtn = Array.from(document.querySelectorAll(`tr[data-session-id="${sid}"] button`)).find(b => /Hold|Resume/i.test(b.textContent || ''));
                    if (rowHoldBtn) rowHoldBtn.textContent = isOnHold ? 'Resume' : 'Hold';
                    const row = document.querySelector(`tr[data-session-id="${sid}"]`);
                    if (row) {
                        const statusEl = row.querySelector('.rc-status');
                        if (statusEl) {
                            const statusText = statusEl.textContent || '';
                            if (isOnHold && !statusText.includes('(On Hold)')) statusEl.textContent = statusText + ' (On Hold)';
                            else if (!isOnHold && statusText.includes('(On Hold)')) statusEl.textContent = statusText.replace(' (On Hold)', '');
                        }
                    }
                } catch (_) { }

                rcLog('Hold toggled:', isOnHold, 'sid=', sid, 'result=', result);
                refreshCallsListPanelIfVisible();
            } catch (err) {
                console.error('Hold toggle failed:', err);
                // Reset button on error
                holdBtn.style.background = '';
                holdBtn.style.color = '';
                holdBtn.innerHTML = '<i class="fa fa-pause"></i>';
            }
        } catch (e) { console.error('holdCallFromDialer failed', e); }
        finally { if (typeof restoreHoldBtn === 'function') restoreHoldBtn(); }
    }

    async function transferCallFromDialer() {
        try {
            window._dialerTransferTarget = null;

            const client = getCallControlClient();

            const activeSessions = getActiveDialerSessions();
            if (activeSessions.length) {
                const sid = window._dialerCurrentSessionId
                    || activeSessions[0].id
                    || activeSessions[0].sessionId
                    || activeSessions[0].index;

                if (sid) {
                    window._dialerTransferTarget = {
                        type: 'webphone',
                        sessionId: sid
                    };
                    openTransferModal();
                    return;
                }
            }

            if (client) {
                let parties = getCachedCallControlActiveParties();
                if (!parties.length) {
                    const latest = await client.sessions();
                    window.__rcCallControlSessions = client.getRecords(latest);
                    parties = getCachedCallControlActiveParties();
                }

                if (parties.length) {
                    window._dialerTransferTarget = {
                        type: 'call-control',
                        sessionId: parties[0].sessionId,
                        partyId: parties[0].partyId
                    };
                    openTransferModal();
                    return;
                }
            }

            console.warn('transferCallFromDialer: no active session');
            alert('No active call to transfer.');
        } catch (e) {
            console.error('transferCallFromDialer failed', e);
            alert(`Transfer failed: ${e.message || e}`);
        } finally {
            updateDialerActionButtons();
        }
    }

    function openTransferModal() {
        const overlay = document.getElementById('dialerTransferOverlay');
        const modal = document.getElementById('dialerTransferModal');
        const input = document.getElementById('transferPhoneNumber');
        const mode = document.getElementById('transferMode');

        if (overlay && modal) {
            overlay.classList.add('show');
            modal.classList.add('show');
            if (mode) mode.value = 'blind';
            if (input) {
                input.value = '';
                setTimeout(() => input.focus(), 100);
            }
        }
    }

    function closeTransferModal() {
        const overlay = document.getElementById('dialerTransferOverlay');
        const modal = document.getElementById('dialerTransferModal');
        const spinner = document.getElementById('transferLoadingSpinner');
        const confirmBtn = document.getElementById('confirmTransferBtn');

        if (overlay && modal) {
            overlay.classList.remove('show');
            modal.classList.remove('show');
        }

        if (spinner) spinner.classList.add('d-none');
        if (confirmBtn) confirmBtn.disabled = false;
        window._dialerTransferTarget = null;
    }

    async function confirmTransferCall() {
        let restoreConfirmTransferBtn = null;
        try {
            const input = document.getElementById('transferPhoneNumber');
            const transferTarget = (input?.value || '').trim();
            if (!transferTarget) {
                alert('Please enter transfer destination number');
                return;
            }

            const modeSel = document.getElementById('transferMode');
            const transferMode = (modeSel?.value || 'blind').trim();

            const confirmBtn = document.getElementById('confirmTransferBtn');
            const spinner = document.getElementById('transferLoadingSpinner');
            restoreConfirmTransferBtn = window.rcSetActionButtonLoading
                ? window.rcSetActionButtonLoading(confirmBtn, { loadingText: 'Transferring...', statusText: 'Transferring call...' })
                : null;
            if (confirmBtn && !restoreConfirmTransferBtn) confirmBtn.disabled = true;
            if (spinner) spinner.classList.remove('d-none');

            let transferSessionType = window._dialerTransferTarget?.type || null;
            let sid = window._dialerTransferTarget?.sessionId || null;
            let partyId = window._dialerTransferTarget?.partyId || null;

            if (!transferSessionType) {
                const activeSessions = getActiveDialerSessions();
                sid = window._dialerCurrentSessionId
                    || activeSessions[0]?.id
                    || activeSessions[0]?.sessionId
                    || activeSessions[0]?.index;
                if (sid) {
                    transferSessionType = 'webphone';
                } else if (getCallControlClient()) {
                    const parties = getCachedCallControlActiveParties();
                    if (parties.length) {
                        transferSessionType = 'call-control';
                        sid = parties[0].sessionId;
                        partyId = parties[0].partyId;
                    }
                }
            }

            if (!transferSessionType || !sid) {
                throw new Error('Could not determine active call session');
            }

            if (transferSessionType === 'call-control') {
                const client = getCallControlClient();
                if (!client) {
                    throw new Error('Call control feature is disabled');
                }
                if (!partyId) {
                    throw new Error('Could not determine active call party');
                }
                await client.transfer(sid, partyId, transferTarget);
                rcInfo('Call-control transfer initiated');
                try {
                    const refreshed = await client.sessions();
                    window.__rcCallControlSessions = client.getRecords(refreshed);
                } catch (_) { }
                if (typeof restoreConfirmTransferBtn === 'function') restoreConfirmTransferBtn();
                closeTransferModal();
                return;
            }

            if (!window.webPhone) throw new Error('WebPhone not initialized');

            if (transferMode === 'warm') {
                // Warm transfer: prefer consultative helper, then session.warmTransfer when available.
                if (typeof webPhone.consultativeTransfer === 'function') {
                    await webPhone.consultativeTransfer(transferTarget);
                } else if (typeof webPhone.findSession === 'function') {
                    const session = webPhone.findSession(sid);
                    if (session && typeof session.warmTransfer === 'function') {
                        await session.warmTransfer(transferTarget);
                    } else {
                        throw new Error('Warm transfer is not supported by this active session');
                    }
                } else {
                    throw new Error('Warm transfer is not supported by the current client implementation');
                }
                rcInfo('Warm transfer initiated');
            } else {
                // Blind transfer.
                if (typeof webPhone.blindTransfer === 'function') {
                    await webPhone.blindTransfer(transferTarget);
                } else if (typeof webPhone.findSession === 'function') {
                    const session = webPhone.findSession(sid);
                    if (session && typeof session.transfer === 'function') {
                        await session.transfer(transferTarget);
                    } else {
                        throw new Error('Blind transfer is not supported by this active session');
                    }
                } else {
                    throw new Error('Blind transfer is not supported by the current client implementation');
                }
                rcInfo('Blind transfer initiated');
            }

            if (typeof restoreConfirmTransferBtn === 'function') restoreConfirmTransferBtn();
            closeTransferModal();
        } catch (e) {
            console.error('confirmTransferCall failed', e);
            alert(`Transfer failed: ${e.message || e}`);

            const spinner = document.getElementById('transferLoadingSpinner');
            const confirmBtn = document.getElementById('confirmTransferBtn');
            if (spinner) spinner.classList.add('d-none');
            if (typeof restoreConfirmTransferBtn === 'function') restoreConfirmTransferBtn();
            else if (confirmBtn) confirmBtn.disabled = false;
        }
    }

    async function switchToWebFromDialer() {
        const restoreSwitchBtn = window.rcSetActionButtonLoading
            ? window.rcSetActionButtonLoading('dialerSwitchToWebBtn', { loadingText: 'Switching...', preserveContent: true, statusText: 'Switching to web...' })
            : null;
        try {
            const client = getCallControlClient();
            if (!client) {
                alert('Call control feature is disabled.');
                return;
            }

            let candidate = getCachedCallControlSwitchCandidate();
            if (!candidate) {
                const result = await client.sessions();
                const records = client.getRecords(result);
                window.__rcCallControlSessions = records;
                candidate = client.findSwitchableSession(records);
            }

            if (!candidate) {
                alert('No eligible external call is available to switch to web.');
                updateDialerActionButtons();
                return;
            }

            const sessionId = candidate.id || candidate.sessionId;
            const party = client.getFirstActiveParty(candidate);
            const partyId = party?.id;

            if (!sessionId || !partyId) {
                throw new Error('Switch target call is missing session or party id');
            }

            const switchBtn = document.getElementById('dialerSwitchToWebBtn');
            if (switchBtn) switchBtn.disabled = true;

            await client.switchToWeb(sessionId, partyId, {});
            rcInfo('Switch to web requested');

            // Refresh cache/state after switching.
            try {
                const refreshed = await client.sessions();
                window.__rcCallControlSessions = client.getRecords(refreshed);
            } catch (_) { }

            updateDialerActionButtons();
        } catch (e) {
            console.error('switchToWebFromDialer failed', e);
            alert(`Switch to web failed: ${e.message || e}`);
            updateDialerActionButtons();
        } finally {
            if (typeof restoreSwitchBtn === 'function') restoreSwitchBtn();
            updateDialerActionButtons();
        }
    }

    function pauseCallFromDialer() {
        try {
            const btn = document.getElementById('dialerPauseBtn');
            if (!btn) return;
            const sid = window._dialerCurrentSessionId;
            if (!sid || !window.webPhone) return;

            // Pause recording if active
            if (window.webPhone && typeof window.webPhone.pauseRecording === 'function') {
                window.webPhone.pauseRecording(sid);
            }
        } catch (e) { console.warn('pauseCallFromDialer failed', e); }
    }

    function notesFromDialer() {
        try {
            const btn = document.getElementById('dialerNotesBtn');
            if (!btn) return;
            btn.style.background = btn.style.background === '#e3f2fd' ? '#f0f0f0' : '#e3f2fd';
            // Show notes modal or overlay
        } catch (e) { console.warn('notesFromDialer failed', e); }
    }

    function screenShareFromDialer() {
        try {
            const btn = document.getElementById('dialerScreenShareBtn');
            if (!btn) return;
            // Screen share is typically disabled for phone calls, keep grayed out
            alert('Screen sharing is not available for this call type.');
        } catch (e) { console.warn('screenShareFromDialer failed', e); }
    }
    // ===== END: ADDITIONAL CALL FEATURES =====
