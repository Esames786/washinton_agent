/**
 * R-Dialer  Audio Settings Modal Helpers
 * Handles audio input/output device selection and management
 */

// ===== START: AUDIO SETTINGS MODAL HELPERS =====
function openMicTestModal() {
    try {
        // Populate global output options if available
        try {
            const sel = document.getElementById('audioOutputSelect-global');
            if (sel) {
                if (window.rcAudioOutputOptionsHtml && !sel.innerHTML.trim()) sel.innerHTML = window.rcAudioOutputOptionsHtml;
                // ensure a sensible default
                if (!sel.value) sel.value = 'default';
            }
        } catch (_) { }
        // Populate input options and active sessions list
        try {
            const inputSel = document.getElementById('audioInputSelect-global');
            const targetSel = document.getElementById('micTargetSessionSelect');
            if (inputSel && !inputSel.innerHTML.trim()) {
                // Build input options from enumerateDevices if available
                if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
                    navigator.mediaDevices.enumerateDevices().then(devs => {
                        const inputs = devs.filter(d => d.kind === 'audioinput');
                        const opts = [{ id: 'default', label: 'Default' }].concat(inputs.map((d, i) => ({ id: d.deviceId, label: (d.label && d.label.length) ? d.label : ('Microphone ' + (i + 1)) })));
                        inputSel.innerHTML = opts.map(o => `<option value="${o.id}">${o.label}</option>`).join('');
                    }).catch(() => { });
                }
            }
            if (targetSel) {
                targetSel.innerHTML = '<option value="">(Select active call)</option>';
                try {
                    if (webPhone && typeof webPhone.listSessions === 'function') {
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
                        if (sessions.length) {
                            const firstId = sessions[0].id ?? sessions[0].index ?? '';
                            targetSel.value = firstId;
                            try { onMicTargetChanged(firstId); } catch (_) { }
                        }
                    }
                } catch (_) { }
                targetSel.onchange = function () { try { onMicTargetChanged(this.value); } catch (_) { } };
            }
        } catch (_) { }
        // Show audio settings inside dialer modal slider
        const audioSection = document.getElementById('dialerAudioSettingsSection');
        const audioOverlay = document.getElementById('dialerAudioSettingsOverlay');
        if (audioOverlay) {
            audioOverlay.classList.add('is-open');
        }
        if (audioSection) {
            audioSection.classList.add('is-open');
        }
    } catch (e) {
        // Error opening mic test modal
    }
}

// Apply selected input/output devices to the chosen active call
function applyAudioSettingsToSession(sid) {
    try {
        const targetSid = sid || (document.getElementById('micTargetSessionSelect') && document.getElementById('micTargetSessionSelect').value);
        if (!targetSid) { return; }
        const outSel = document.getElementById('audioOutputSelect-global');
        const inSel = document.getElementById('audioInputSelect-global');
        const outId = outSel ? outSel.value : 'default';
        const inId = inSel ? inSel.value : null;
        // Try SDK route first
        if (window.webPhone && typeof webPhone.changeOutputDeviceForSession === 'function') {
            webPhone.changeOutputDeviceForSession(targetSid, outId).catch(e => {});
        } else {
            // Fallback: update per-row select and call setSessionOutput
            try {
                const sel = document.getElementById(`audioOutputSelect-${targetSid}`);
                if (sel) { sel.value = outId; }
                try { setSessionOutput(targetSid); } catch (_) { }
            } catch (_) { }
        }
        if (inId && window.webPhone && typeof webPhone.changeInputDeviceForSession === 'function') {
            webPhone.changeInputDeviceForSession(targetSid, inId).catch(e => {});
        }
        // update status
        const status = document.getElementById('micTestStatus-global'); if (status) status.innerHTML = '<div class="alert alert-success">Applied audio settings to call</div>';
    } catch (e) {
        // Error applying audio settings
    }
}

// When the target call changes, load its current device selections into the modal and attach auto-apply handlers
function onMicTargetChanged(sid) {
    try {
        window._micTestModalSelectedSession = sid || null;
        if (!sid) return;
        // Copy per-row output selection into modal select if available
        try {
            const perOut = document.getElementById(`audioOutputSelect-${sid}`);
            const modalOut = document.getElementById('audioOutputSelect-global');
            if (modalOut && perOut) {
                if (!modalOut.innerHTML.trim() && window.rcAudioOutputOptionsHtml) modalOut.innerHTML = window.rcAudioOutputOptionsHtml;
                modalOut.value = perOut.value || modalOut.value || 'default';
            }
        } catch (_) { }
        // Wire modal selects to auto-apply to selected session
        try {
            const modalOut = document.getElementById('audioOutputSelect-global');
            const modalIn = document.getElementById('audioInputSelect-global');
            if (modalOut) modalOut.onchange = function () { applyAudioSettingsToSession(sid); };
            if (modalIn) modalIn.onchange = function () { applyAudioSettingsToSession(sid); };
        } catch (_) { }
        // Immediately apply current modal selections to the session to sync
        try { applyAudioSettingsToSession(sid); } catch (_) { }
    } catch (e) {
        // Error changing mic target
    }
}

function closeMicTestModal() {
    try {
        const audioSection = document.getElementById('dialerAudioSettingsSection');
        const audioOverlay = document.getElementById('dialerAudioSettingsOverlay');
        if (audioSection) audioSection.classList.remove('is-open');
        if (audioOverlay) audioOverlay.classList.remove('is-open');
    } catch (e) {
        // Error closing mic test modal
    }
}
// ===== END: AUDIO SETTINGS MODAL HELPERS =====
