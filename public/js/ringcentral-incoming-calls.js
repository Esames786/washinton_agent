    // ===== START: INCOMING CALL HANDLERS =====
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
    async function endCallFromDialer() {
        const restoreEndBtn = window.rcSetActionButtonLoading
            ? window.rcSetActionButtonLoading('dialerEndCallBtn', { loadingText: 'Ending...', iconOnly: true, statusText: 'Ending call...' })
            : null;
        try {
            let sid = window._dialerCurrentSessionId;

            // Fallback: if no session ID, try to get active session
            if (!sid && webPhone && typeof webPhone.listSessions === 'function') {
                const sessions = webPhone.listSessions() || [];
                const activeSessions = sessions.filter(s => {
                    const state = (s.state || '').toLowerCase();
                    return state !== 'disposed' && state !== 'terminated';
                });
                if (activeSessions.length > 0) {
                    sid = activeSessions[0].id || activeSessions[0].index;
                }
            }

            if (!sid) {
                console.warn('endCallFromDialer: no active session found');
                alert('No active call to end');
                return;
            }

            rcLog('Ending call with session ID:', sid);

            // Call SDK to end the session
            if (webPhone && typeof webPhone.hangupSession === 'function') {
                await webPhone.hangupSession(sid);
                rcLog('Session hangup called');
            } else if (webPhone && typeof webPhone.hangup === 'function') {
                // Fallback to alternate method name
                await webPhone.hangup(sid);
                rcLog('Alternate hangup method called');
            } else {
                throw new Error('hangupSession method not available');
            }

            setTimeout(() => {
                try {
                    if (hasOtherAcceptedCallExcluding(sid)) {
                        if (typeof window.syncDialerLiveCallState === 'function') {
                            window.syncDialerLiveCallState('dialer-end-call:active-call-remains');
                        } else if (typeof activateNextAvailableCall === 'function') {
                            activateNextAvailableCall();
                        }
                    } else {
                        switchDialerToBeforeCall();
                        clearDialer();
                    }
                } catch (_) { }
            }, 250);
        } catch (e) {
            console.error('endCallFromDialer failed', e);
            alert('End call failed: ' + (e.message || e));
        } finally {
            if (typeof restoreEndBtn === 'function') restoreEndBtn();
        }
    }

    function showIncomingSessionFromList(sessionId) {
        try {
            const sid = sessionId ? String(sessionId) : '';
            if (!sid) return false;
            if (typeof window.rcShowIncomingSessionById === 'function') {
                const shown = window.rcShowIncomingSessionById(sid, { focusDialer: false });
                if (shown) return true;
            }
            window._incomingCallSessionId = sid;
            return true;
        } catch (e) {
            console.warn('showIncomingSessionFromList failed', e);
            return false;
        }
    }

    function answerIncomingCallById(sessionId) {
        showIncomingSessionFromList(sessionId);
        return answerIncomingCall();
    }

    function declineIncomingCallById(sessionId) {
        showIncomingSessionFromList(sessionId);
        return declineIncomingCall();
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

    function hasOtherAcceptedCallExcluding(excludedSessionId) {
        try {
            if (!webPhone || typeof webPhone.listSessions !== 'function') {
                return false;
            }

            const sessions = webPhone.listSessions() || [];
            const incomingStates = ['incoming', 'early', 'ringing', 'pending', 'alerting', 'invite'];
            const terminalStates = ['disposed', 'terminated', 'ended', 'disconnected', 'failed', 'rejected', 'cancelled', 'canceled'];
            const acceptedStates = ['active', 'connected', 'established', 'confirmed', 'inprogress', 'answered', 'hold', 'held', 'proceeding'];
            const ignoredSessions = new Set((window._ignoredCallSessions || []).map(String));
            const excludeId = excludedSessionId ? String(excludedSessionId) : '';
            const currentId = window._dialerCurrentSessionId ? String(window._dialerCurrentSessionId) : '';

            return sessions.some((s) => {
                const state = String(s?.state || s?.status || '').toLowerCase();

                if (excludeId && sessionMatchesId(s, excludeId)) return false;
                if (isSessionIgnored(s, ignoredSessions)) return false;
                if (terminalStates.some(x => state.includes(x))) return false;
                if (incomingStates.some(x => state.includes(x))) return false;
                if (s?.hold === true || s?.onHold === true || s?._onHold === true) return true;
                if (acceptedStates.some(x => state.includes(x))) return true;
                if (currentId && sessionMatchesId(s, currentId)) return true;
                return false;
            });
        } catch (_) {
            return false;
        }
    }

    function isConfirmedSessionState(session) {
        if (!session) return false;
        const state = String(session.state || session.status || '').toLowerCase();
        const terminalStates = ['disposed', 'terminated', 'ended', 'disconnected', 'failed', 'rejected', 'cancelled', 'canceled'];
        const incomingStates = ['incoming', 'early', 'ringing', 'pending', 'alerting', 'invite', 'offering'];
        const confirmedStates = ['active', 'connected', 'established', 'confirmed', 'hold', 'held', 'answered'];

        if (terminalStates.some((x) => state.includes(x))) return false;
        if (incomingStates.some((x) => state.includes(x))) return false;
        if (session.hold === true || session.onHold === true || session._onHold === true) return true;
        return confirmedStates.some((x) => state.includes(x));
    }

    function getSessionPrimaryId(session, fallbackId) {
        if (!session) return fallbackId || null;
        return session.id || session.sessionId || session.callId || session.partyId || session.index || fallbackId || null;
    }

    function resolveConfirmedSessionId(preferredSid) {
        try {
            if (!webPhone || typeof webPhone.listSessions !== 'function') return null;
            const sessions = webPhone.listSessions() || [];
            const ignoredSessions = new Set((window._ignoredCallSessions || []).map(String));
            const confirmed = sessions.filter((s) => {
                if (isSessionIgnored(s, ignoredSessions)) return false;
                return isConfirmedSessionState(s);
            });
            if (!confirmed.length) return null;

            if (preferredSid) {
                const matched = confirmed.find((s) => sessionMatchesId(s, preferredSid));
                if (matched) return getSessionPrimaryId(matched, preferredSid);
            }

            confirmed.sort((a, b) => {
                const ta = Number(a.startedAt || a.createdAt || a._ringingStartedAt || a.startTime || 0) || 0;
                const tb = Number(b.startedAt || b.createdAt || b._ringingStartedAt || b.startTime || 0) || 0;
                return tb - ta;
            });

            return getSessionPrimaryId(confirmed[0], preferredSid);
        } catch (_) {
            return null;
        }
    }

    function clearPendingAnswerConnectedWatcher() {
        try {
            const watcher = window._answerIncomingConnectedWatcher;
            if (!watcher) return;
            if (watcher.onConnected) document.removeEventListener('ringcentral:callConnected', watcher.onConnected);
            if (watcher.onEnded) document.removeEventListener('ringcentral:callEnded', watcher.onEnded);
        } catch (_) { }
        window._answerIncomingConnectedWatcher = null;
    }

    function finalizeAnsweredIncomingSession(sessionOrId, fallbackSid) {
        const targetId = (typeof sessionOrId === 'object')
            ? getSessionPrimaryId(sessionOrId, fallbackSid)
            : (sessionOrId || fallbackSid || null);

        if (targetId && typeof switchDialerToDuringCall === 'function') {
            switchDialerToDuringCall(targetId);
        }

        let remainingIncoming = 0;
        try {
            if (typeof window.rcRemoveIncomingCallFromQueue === 'function') {
                remainingIncoming = window.rcRemoveIncomingCallFromQueue(sessionOrId || targetId, { skipRender: true });
            }
        } catch (_) { }

        if (remainingIncoming > 0 && typeof window.rcRenderIncomingQueue === 'function') {
            window.rcRenderIncomingQueue({ focusDialer: false });
        } else {
            window._minimizedIncomingCallActive = false;
            window._minimizedIncomingCallNumber = '';
            try { if (typeof refreshMinimizedDialerVisibility === 'function') refreshMinimizedDialerVisibility(); } catch (_) { }
        }

        return remainingIncoming;
    }

    function watchForIncomingAnswerConnected(expectedSid) {
        clearPendingAnswerConnectedWatcher();

        const expected = expectedSid ? String(expectedSid) : '';
        const onConnected = (e) => {
            try {
                const session = e?.detail?.session || null;
                if (expected && session && !sessionMatchesId(session, expected)) return;
                if (session && !isConfirmedSessionState(session)) return;

                const confirmedSid = resolveConfirmedSessionId(expected) || getSessionPrimaryId(session, expected);
                finalizeAnsweredIncomingSession(session || confirmedSid, expected);
            } catch (_) { }
            clearPendingAnswerConnectedWatcher();
        };

        const onEnded = () => {
            clearPendingAnswerConnectedWatcher();
        };

        window._answerIncomingConnectedWatcher = { onConnected, onEnded, expected };
        document.addEventListener('ringcentral:callConnected', onConnected);
        document.addEventListener('ringcentral:callEnded', onEnded);
    }

    async function answerIncomingCall() {
        const overallStart = performance.now();
        const metrics = {};
        const restoreAnswerBtn = window.rcSetActionButtonLoading
            ? window.rcSetActionButtonLoading('incomingAnswerBtn', { loadingText: 'Answering...', iconOnly: true, statusText: 'Answering call...' })
            : null;
        
        try {
            rcInfo('%c▶️  answerIncomingCall START', 'color: #00ff00; font-weight: bold; font-size: 14px;');
            rcLog('   └─ Timestamp:', new Date().toISOString());

            // === STEP 1: Check Lock ===
            let lockCheckTime = performance.now();
            if (window._answerIncomingLock?.processing) {
                console.warn('%c⏸️  ALREADY PROCESSING - answerIncomingCall IGNORED', 'color: #ff9800; font-weight: bold;');
                rcLog('   ├─ Lock state: processing=true');
                rcLog('   └─ Active since: ', window._answerIncomingLock.startedAt ? 
                    ((performance.now() - window._answerIncomingLock.startedAt).toFixed(0) + 'ms ago') : 'unknown');
                return;
            }
            metrics.lockCheck = performance.now() - lockCheckTime;
            rcLog(`✅ Lock check passed (${metrics.lockCheck.toFixed(1)}ms)`);

            // === STEP 2: Initialize & Acquire Lock ===
            let lockSetTime = performance.now();
            window._answerIncomingLock = window._answerIncomingLock || {};
            window._answerIncomingLock.processing = true;
            window._answerIncomingLock.startedAt = performance.now();
            metrics.lockSet = performance.now() - lockSetTime;
            rcLog(`   ├─ Lock acquired (${metrics.lockSet.toFixed(1)}ms)`);

            // === STEP 3: Validate Prerequisites ===
            // (No specific validation needed here)

            // === STEP 4: Get Session ID ===
            let getSessionIdTime = performance.now();
            const sid = window._incomingCallSessionId;
            metrics.getSessionId = performance.now() - getSessionIdTime;
            rcLog(`   ├─ Session ID retrieved: "${sid}" (${metrics.getSessionId.toFixed(1)}ms)`);
            
            if (!sid) {
                console.error('%c❌ FATAL: NO SESSION ID stored', 'color: #ff0000; font-weight: bold;');
                alert('Error: No incoming call session found');
                window._answerIncomingLock.processing = false;
                return;
            }

            // Ensure stale timeout listeners from prior attempts do not duplicate UI updates.
            clearPendingAnswerConnectedWatcher();

            // === STEP 5: Check WebPhone ===
            let wpCheckTime = performance.now();
            rcLog(`   ├─ Checking webPhone initialization...`);
            if (!webPhone) {
                console.error('%c❌ FATAL: webPhone not initialized', 'color: #ff0000; font-weight: bold;');
                if (typeof answerCall === 'function') {
                    rcLog('   ├─ Fallback: Using answerCall function directly');
                    let answerStartTime = performance.now();
                    await answerCall(sid);
                    rcLog(`   ├─ answerCall completed (${(performance.now() - answerStartTime).toFixed(0)}ms)`);
                    let remainingIncoming = 0;
                    try {
                        if (typeof window.rcRemoveIncomingCallFromQueue === 'function') {
                            remainingIncoming = window.rcRemoveIncomingCallFromQueue(sid, { skipRender: true });
                        }
                    } catch (_) { }
                    switchDialerToDuringCall(sid);
                    if (remainingIncoming > 0 && typeof window.rcRenderIncomingQueue === 'function') {
                        window.rcRenderIncomingQueue({ focusDialer: false });
                    }
                    window._answerIncomingLock.processing = false;
                    return;
                }
                alert('Error: WebPhone not ready. Please wait for initialization.');
                window._answerIncomingLock.processing = false;
                return;
            }
            metrics.wpCheck = performance.now() - wpCheckTime;
            rcLog(`✅ WebPhone initialized (${metrics.wpCheck.toFixed(1)}ms)`);

            // === STEP 6: List Active Sessions ===
            let listSessionsTime = performance.now();
            if (typeof webPhone.listSessions === 'function') {
                const sessions = webPhone.listSessions();
                metrics.listSessions = performance.now() - listSessionsTime;
                rcLog(`   ├─ Found ${sessions.length} active session(s) (${metrics.listSessions.toFixed(1)}ms)`);
                sessions.forEach((s, idx) => {
                    rcLog(`       └─ [${idx}] ID: ${s.id}, State: ${s.state}, Direction: ${s.direction}`);
                });
            }

            // === STEP 7: Find Specific Session ===
            let findSessionTime = performance.now();
            let targetSession = null;
            if (typeof webPhone.findSession === 'function') {
                targetSession = webPhone.findSession(sid);
            }
            metrics.findSession = performance.now() - findSessionTime;
            rcLog(`   ├─ Find session: ${targetSession ? '✅ FOUND' : '⚠️  NOT FOUND'} (${metrics.findSession.toFixed(1)}ms)`);
            if (targetSession) {
                rcLog(`       ├─ State: ${targetSession.state || 'unknown'}`);
                rcLog(`       ├─ Direction: ${targetSession.direction || 'unknown'}`);
                rcLog(`       ├─ Remote: ${targetSession.remoteNumber || 'unknown'}`);
                rcLog(`       └─ Has answerSession: ${typeof targetSession.answerSession === 'function'}`);
            }

            // === STEP 9: Attach Listeners ===
            let attachListenerTime = performance.now();
            try {
                if (targetSession && typeof attachSessionUiListeners === 'function') {
                    attachSessionUiListeners(targetSession);
                    rcLog(`   ├─ Session listeners attached (${(performance.now() - attachListenerTime).toFixed(1)}ms)`);
                } else if (targetSession) {
                    rcLog('   ├─ Session listeners skipped (attachSessionUiListeners not available)');
                }
            } catch (err) {
                console.warn(`   ⚠️  Listener attachment failed:`, err.message);
            }
            metrics.attachListener = performance.now() - attachListenerTime;

            // === STEP 10: Answer Call ===
            rcLog(`   ├─ NOW CALLING: webPhone.answerSession(${sid})`);
            let answerCallTime = performance.now();
            let answerResult;

            const timeoutMs = 2000;
            const answerPromise = (async () => {
                if (typeof webPhone.answerSession === 'function') {
                    // Use answerSession which accepts session ID
                    rcLog(`   │   ├─ Using answerSession method`);
                    const result = await webPhone.answerSession(sid);
                    rcLog(`   │   └─ Result:`, result);
                    return result;
                }
                if (targetSession && typeof webPhone.answerCall === 'function') {
                    // Fallback: use answerCall with session object
                    rcLog(`   │   ├─ Using answerCall method with session object`);
                    const result = await webPhone.answerCall(targetSession);
                    rcLog(`   │   └─ Result:`, result);
                    return result;
                }
                throw new Error('No answer method available: webPhone.answerSession or webPhone.answerCall');
            })();

            const timeoutPromise = new Promise(resolve => {
                setTimeout(() => resolve({ timeout: true }), timeoutMs);
            });

            const outcome = await Promise.race([
                answerPromise.then(result => ({ timeout: false, result })).catch(error => ({ timeout: false, error })),
                timeoutPromise
            ]);

            if (outcome && outcome.timeout) {
                console.warn('%c⏱️  answerSession TIMEOUT - still waiting, unlock for retry', 'color: #ff9800; font-weight: bold;');
                window._answerIncomingLock.processing = false;
                watchForIncomingAnswerConnected(sid);
                return;
            }

            if (outcome && outcome.error) {
                console.error('Answer method error:', outcome.error.message || outcome.error);
                throw outcome.error;
            }

            answerResult = outcome ? outcome.result : undefined;
            
            metrics.answerCall = performance.now() - answerCallTime;
            rcLog(`✅ Answer method completed (${metrics.answerCall.toFixed(0)}ms)`);
            rcLog(`   └─ Answer result:`, answerResult);

            const confirmedSid = resolveConfirmedSessionId(sid);
            if (!confirmedSid) {
                rcLog('Answer returned before confirmed connected state; waiting for callConnected event');
                watchForIncomingAnswerConnected(sid);
                window._answerIncomingLock.processing = false;
                return;
            }

            // === STEP 11: Switch UI (confirmed only) ===
            rcLog(`   ├─ NOW CALLING: switchDialerToDuringCall(${confirmedSid})`);
            let switchUITime = performance.now();
            finalizeAnsweredIncomingSession(confirmedSid, sid);
            metrics.switchUI = performance.now() - switchUITime;
            rcLog(`✅ switchDialerToDuringCall completed (${metrics.switchUI.toFixed(0)}ms)`);

            // === STEP 12: Clear Lock ===
            window._answerIncomingLock.processing = false;
            const totalTime = performance.now() - overallStart;
            rcInfo('%c✅ answerIncomingCall COMPLETE SUCCESS', 'color: #00ff00; font-weight: bold; font-size: 14px;');
            rcLog('   ├─ TOTAL TIME:', totalTime.toFixed(0) + 'ms');
            rcLog('   └─ Breakdown:');
            Object.keys(metrics).forEach(key => {
                rcLog(`       ├─ ${key}: ${metrics[key].toFixed(1)}ms`);
            });
            
        } catch (e) {
            const errorTime = performance.now() - overallStart;
            console.error(`%c❌ answerIncomingCall FAILED (after ${errorTime.toFixed(0)}ms)`, 'color: #ff0000; font-weight: bold; font-size: 14px;');
            console.error('   ├─ Error message:', e.message);
            console.error('   ├─ Error type:', e.name);
            console.error('   └─ Stack trace:', e.stack);
            alert('Answer failed: ' + (e.message || e));
            // Reset lock on error
            try { window._answerIncomingLock.processing = false; } catch (_) { }
        } finally {
            if (typeof restoreAnswerBtn === 'function') restoreAnswerBtn();
        }
    }

    function hideIncomingCallSection() {
        rcLog('=== hideIncomingCallSection START ===');
        
        const incomingSection = document.getElementById('dialerIncomingCallSection');
        const duringSection = document.getElementById('dialerDuringCallSection');
        const beforeSection = document.getElementById('dialerBeforeCallSection');
        const duringIncomingBanner = document.getElementById('dialerDuringIncomingBanner');
        const activeCallsHeader = document.getElementById('activeCallsHeader');
        const currentCallInfo = document.getElementById('currentCallInfo');
        
        if (incomingSection) {
            incomingSection.style.display = 'none';
            rcLog('Incoming call section hidden');
        }
        if (duringIncomingBanner) duringIncomingBanner.style.display = 'none';

        // Clear minimized incoming actions when incoming UI is dismissed
        window._minimizedIncomingCallActive = false;
        window._minimizedIncomingCallNumber = '';
        try { if (typeof refreshMinimizedDialerVisibility === 'function') refreshMinimizedDialerVisibility(); } catch (_) { }

        // Check if there are active calls (excluding the incoming call itself)
        if (typeof webPhone !== 'undefined' && webPhone) {
            const allSessions = webPhone.listSessions();
            const incomingSessionId = window._incomingCallSessionId;
            const acceptedStates = ['active', 'connected', 'established', 'confirmed', 'inprogress', 'answered', 'hold', 'held', 'proceeding'];
            const incomingStates = ['incoming', 'early', 'ringing', 'pending', 'alerting', 'invite'];
            const terminalStates = ['disposed', 'terminated', 'ended', 'disconnected', 'failed', 'rejected', 'cancelled', 'canceled'];
            const ignoredSessions = new Set((window._ignoredCallSessions || []).map(String));
            const isAcceptedSession = (session) => {
                const state = String(session?.state || session?.status || '').toLowerCase();
                if (terminalStates.some(x => state.includes(x))) return false;
                if (session?.hold === true || session?.onHold === true || session?._onHold === true) return true;
                return acceptedStates.some(x => state.includes(x));
            };

            // Build a display list that matches the strict accepted-call detector.
            const otherActiveCalls = allSessions.filter(s => {
                const state = String(s?.state || s?.status || '').toLowerCase();

                if (incomingSessionId && sessionMatchesId(s, incomingSessionId) && !isAcceptedSession(s)) return false;
                if (isSessionIgnored(s, ignoredSessions)) return false;
                if (terminalStates.some(x => state.includes(x))) return false;
                if (incomingStates.some(x => state.includes(x))) return false;
                if (s?.hold === true || s?.onHold === true || s?._onHold === true) return true;
                return acceptedStates.some(x => state.includes(x));
            });
            
            const hasActiveCalls = otherActiveCalls.length > 0;
            const totalOtherCalls = otherActiveCalls ? otherActiveCalls.length : 0;
            
            rcLog('Total sessions:', allSessions ? allSessions.length : 0);
            rcLog('Incoming session ID:', incomingSessionId);
            rcLog('Other active calls (including held):', totalOtherCalls);
            if (totalOtherCalls > 0) {
                rcLog('  Other calls details:', otherActiveCalls.map(s => ({
                    id: s.id || s.index,
                    state: s.state,
                    hold: s.hold || s.onHold || s._onHold,
                    remoteNumber: s.remoteNumber
                })));
            }
            rcLog('Has active calls (excluding incoming):', hasActiveCalls);
            
            if (hasActiveCalls) {
                rcLog('Active calls found (excluding incoming), switching to active call view');
                // Hide before section, show during section
                if (beforeSection) beforeSection.style.display = 'none';
                if (duringSection) duringSection.style.display = 'block';
                
                // Show activeCallsHeader only if there are multiple calls remaining
                if (activeCallsHeader) {
                    if (totalOtherCalls > 1) {
                        activeCallsHeader.style.display = 'flex';
                    } else {
                        activeCallsHeader.style.display = 'none';
                    }
                }
                
                // Show currentCallInfo if there are any calls remaining
                if (currentCallInfo) {
                    if (totalOtherCalls >= 1) {
                        currentCallInfo.style.display = 'block';
                    } else {
                        currentCallInfo.style.display = 'none';
                    }
                }
                try {
                    if (typeof window.syncDialerLiveCallState === 'function') {
                        window.syncDialerLiveCallState('hideIncomingCallSection:active-call-remains');
                    }
                } catch (_) { }
                
            } else {
                rcLog('No active calls (excluding incoming), switching to dialer pad');
                // Show before section (dialer pad), hide during
                if (beforeSection) beforeSection.style.display = 'block';
                if (duringSection) duringSection.style.display = 'none';
                // Hide headers since there are no remaining calls
                if (activeCallsHeader) activeCallsHeader.style.display = 'none';
                if (currentCallInfo) currentCallInfo.style.display = 'none';
                rcLog('Dialer pad should now be visible');
            }
        } else {
            rcLog('WebPhone not available, switching to dialer pad');
            // Show before section (dialer pad), hide during
            if (beforeSection) beforeSection.style.display = 'block';
            if (duringSection) duringSection.style.display = 'none';
            if (activeCallsHeader) activeCallsHeader.style.display = 'none';
            if (currentCallInfo) currentCallInfo.style.display = 'none';
            rcLog('Dialer pad should now be visible (WebPhone unavailable)');
        }
        
        rcLog('=== hideIncomingCallSection END ===');
    }

    async function declineIncomingCall() {
        const restoreDeclineBtn = window.rcSetActionButtonLoading
            ? window.rcSetActionButtonLoading('incomingDeclineBtn', { loadingText: 'Rejecting...', iconOnly: true, statusText: 'Rejecting call...' })
            : null;
        try {
            rcLog('=== declineIncomingCall START ===');
            const sid = window._incomingCallSessionId;
            rcLog('Session ID to decline:', sid);
            rcLog('Global webPhone available:', typeof webPhone !== 'undefined' && webPhone !== null);

            const hasOtherAcceptedCall = () => hasOtherAcceptedCallExcluding(sid);

            if (!sid) {
                console.error('declineIncomingCall: NO SESSION ID stored');
                alert('Error: No incoming call session found');
                return;
            }

            if (!webPhone) {
                console.error('declineIncomingCall: webPhone not initialized. Trying to use SDK...');
                // Try to use the SDK if available
                if (typeof rejectCall === 'function') {
                    rcLog('Using rejectCall function directly');
                    await rejectCall(sid);
                    try {
                        if (typeof window.rcRemoveIncomingCallFromQueue === 'function') {
                            const remaining = window.rcRemoveIncomingCallFromQueue(sid, { countAsLost: true });
                            if (!remaining && !hasOtherAcceptedCall()) {
                                closeDialerModal();
                                switchDialerToBeforeCall();
                            }
                        } else {
                            if (!hasOtherAcceptedCall()) {
                                closeDialerModal();
                                switchDialerToBeforeCall();
                            }
                        }
                    } catch (_) {
                        if (!hasOtherAcceptedCall()) {
                            closeDialerModal();
                            switchDialerToBeforeCall();
                        }
                    }
                    return;
                }
                alert('Error: WebPhone not ready. Please wait for initialization.');
                return;
            }

            // Log all active sessions
            if (typeof webPhone.listSessions === 'function') {
                const sessions = webPhone.listSessions();
                rcLog('Active sessions:', sessions);
            }

            rcLog('Calling rejectCall with sessionId:', sid);
            if (typeof rejectCall === 'function') {
                await rejectCall(sid);
            } else if (typeof webPhone.rejectCall === 'function') {
                const session = (typeof webPhone.findSession === 'function') ? webPhone.findSession(sid) : null;
                await webPhone.rejectCall(session || sid);
            } else {
                throw new Error('rejectCall method not available');
            }
            rcLog('rejectCall completed successfully');

            let remainingQueue = 0;
            try {
                if (typeof window.rcRemoveIncomingCallFromQueue === 'function') {
                    remainingQueue = window.rcRemoveIncomingCallFromQueue(sid, { countAsLost: true });
                }
            } catch (_) { }

            if (remainingQueue <= 0) {
                window._minimizedIncomingCallActive = false;
                window._minimizedIncomingCallNumber = '';
                try { if (typeof refreshMinimizedDialerVisibility === 'function') refreshMinimizedDialerVisibility(); } catch (_) { }
                if (!hasOtherAcceptedCall()) {
                    closeDialerModal();
                    switchDialerToBeforeCall();
                } else {
                    try {
                        if (window._dialerCurrentSessionId && typeof switchDialerToDuringCall === 'function') {
                            switchDialerToDuringCall(window._dialerCurrentSessionId);
                        }
                    } catch (_) { }
                }
            }
            rcLog('=== declineIncomingCall END ===');
        } catch (e) {
            console.error('declineIncomingCall FAILED:', e);
            alert('Decline failed: ' + (e.message || e));
        } finally {
            if (typeof restoreDeclineBtn === 'function') restoreDeclineBtn();
        }
    }

    function ignoreIncomingCall() {
        try {
            rcLog('=== ignoreIncomingCall START ===');
            const sid = window._incomingCallSessionId;

            // Stop ringtone
            if (window.webPhone && typeof webPhone.stopRingtone === 'function') {
                webPhone.stopRingtone();
            } else if (window.webPhone && webPhone.audioHelper && typeof webPhone.audioHelper.stopRingtone === 'function') {
                webPhone.audioHelper.stopRingtone();
            }

            // Mark as ignored to hide from calls list
            if (sid) {
                window._ignoredCallSessions = window._ignoredCallSessions || [];
                if (!window._ignoredCallSessions.includes(sid)) {
                    window._ignoredCallSessions.push(sid);
                }
                rcLog('Call marked as ignored:', sid);
            }

            let remainingQueue = 0;
            try {
                if (typeof window.rcRemoveIncomingCallFromQueue === 'function') {
                    remainingQueue = window.rcRemoveIncomingCallFromQueue(sid, { countAsLost: true });
                }
            } catch (_) { }

            // Hide UI elements only when no pending incoming calls remain
            if (remainingQueue <= 0) {
                try { if (typeof hideIncomingCallSection === 'function') hideIncomingCallSection(); } catch (_) { }
                try { if (typeof hideMinimizedDialerWidget === 'function') hideMinimizedDialerWidget(); } catch (_) { }
                if (!hasOtherAcceptedCallExcluding(sid)) {
                    try { if (typeof closeDialerModal === 'function') closeDialerModal(); } catch (_) { }
                    try { if (typeof switchDialerToBeforeCall === 'function') switchDialerToBeforeCall(); } catch (_) { }
                } else {
                    try {
                        if (window._dialerCurrentSessionId && typeof switchDialerToDuringCall === 'function') {
                            switchDialerToDuringCall(window._dialerCurrentSessionId);
                        }
                    } catch (_) { }
                }
            }

            rcLog('=== ignoreIncomingCall END ===');
        } catch (e) {
            console.warn('ignoreIncomingCall failed', e);
        }
    }

    function toggleIncomingQuickReply() {
        try {
            const customRow = document.getElementById('incomingCustomRow');
            if (!customRow) return;
            customRow.classList.toggle('d-none');
            if (!customRow.classList.contains('d-none')) {
                const customInput = document.getElementById('incomingCustomMsg');
                if (customInput) customInput.focus();
            }
        } catch (e) { console.warn('toggleIncomingQuickReply failed', e); }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const incomingCustomBtn = document.getElementById('incomingCustomBtn');
        if (incomingCustomBtn) {
            incomingCustomBtn.addEventListener('click', function () {
                toggleIncomingQuickReply();
            });
        }

        // Send custom message
        const incomingSendCustom = document.getElementById('incomingSendCustom');
        if (incomingSendCustom) {
            incomingSendCustom.addEventListener('click', function () {
                const msg = document.getElementById('incomingCustomMsg')?.value?.trim();
                if (!msg) return;
                rcLog('Custom message sent:', msg);
                const customRow = document.getElementById('incomingCustomRow');
                const customMsg = document.getElementById('incomingCustomMsg');
                if (customRow) customRow.classList.add('d-none');
                if (customMsg) customMsg.value = '';
            });
        }

        // Send preset quick messages
        const quickMsgButtons = document.querySelectorAll('.incoming-quick-msg');
        quickMsgButtons.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const msg = this.getAttribute('data-msg');
                if (msg) {
                    rcLog('Quick message sent:', msg);
                }
            });
        });
    });
    // ===== END: INCOMING CALL HANDLERS =====
