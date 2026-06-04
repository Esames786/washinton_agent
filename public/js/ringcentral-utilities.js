// R-Dialer Utilities Module
// Contains miscellaneous utility functions

function rcToggleUserMenu(event) {
    if (event) event.stopPropagation();
    const dropdown = document.getElementById('rcUserDropdown');
    if (!dropdown) return;
    rcSetUserMenuOpen(dropdown.hidden || dropdown.style.display === 'none');
}

function rcSetUserMenuOpen(open) {
    const dropdown = document.getElementById('rcUserDropdown');
    const toggle = document.getElementById('rcUserMenuToggle');
    if (!dropdown) return;

    dropdown.hidden = !open;
    dropdown.style.display = open ? 'block' : 'none';
    if (toggle) {
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    }
}

document.addEventListener('click', function (e) {
    const target = e.target;
    const menuWrap = document.getElementById('rcUserMenuWrap');
    if (!menuWrap || !(target instanceof Element) || !menuWrap.contains(target)) {
        rcSetUserMenuOpen(false);
    }
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        rcSetUserMenuOpen(false);
        return;
    }

    const target = e.target;
    const toggle = document.getElementById('rcUserMenuToggle');
    if (
        toggle
        && target === toggle
        && (e.key === 'Enter' || e.key === ' ')
    ) {
        e.preventDefault();
        rcToggleUserMenu(e);
    }
});

/**
 * Resolve a named route for R-Dialer endpoints.
 * Throws if the global route helper is unavailable.
 */
function rcRoute(name, params = {}, query = null) {
    let url = null;
    if (typeof route === 'function') {
        url = route(name, params || {});
    } else if (window.RC_ROUTES && window.RC_ROUTES[name]) {
        url = window.RC_ROUTES[name];
        if (params && typeof params === 'object') {
            Object.keys(params).forEach((key) => {
                const token = ':' + key;
                url = url.replace(token, encodeURIComponent(params[key]));
            });
        }
    }
    if (!url) {
        throw new Error('Route helper is not available and RC_ROUTES is missing ' + name);
    }
    if (query && typeof query === 'object') {
        const qs = new URLSearchParams(query).toString();
        if (qs) {
            url += (url.indexOf('?') >= 0 ? '&' : '?') + qs;
        }
    }
    return url;
}

function rcGetChatDesktopSlot() {
    return document.getElementById('chatDesktopSlot');
}

function rcHideCallsDetailPanel() {
    try {
        const slot = rcGetChatDesktopSlot();
        if (!slot) return;

        const panel = document.getElementById('rcCallsDetailPanel');
        if (panel) {
            panel.remove();
        }

        slot.dataset.rcCallsDetailOpen = '0';
        if (slot.style.display === '' || slot.style.display === 'block') {
            slot.style.display = '';
        }

        const chatCard = document.getElementById('chatViewCard');
        if (chatCard && chatCard.dataset.rcHiddenByCallsDetail === '1') {
            chatCard.style.display = '';
            delete chatCard.dataset.rcHiddenByCallsDetail;
        }
    } catch (e) {
        console.warn('rcHideCallsDetailPanel failed', e);
    }
}

function rcShowCallsDetailPanel(html) {
    try {
        const slot = rcGetChatDesktopSlot();
        if (!slot) return false;

        const chatCard = document.getElementById('chatViewCard');
        if (chatCard && slot.contains(chatCard)) {
            chatCard.style.display = 'none';
            chatCard.dataset.rcHiddenByCallsDetail = '1';
        }

        let panel = document.getElementById('rcCallsDetailPanel');
        if (!panel) {
            panel = document.createElement('div');
            panel.id = 'rcCallsDetailPanel';
            panel.className = 'w-100';
            slot.appendChild(panel);
        }

        panel.innerHTML = String(html || '');
        slot.style.display = '';
        slot.dataset.rcCallsDetailOpen = '1';
        return true;
    } catch (e) {
        console.warn('rcShowCallsDetailPanel failed', e);
        return false;
    }
}

/**
 * Central phone masking policy.
 * Override globally by setting window.RC_PHONE_MASK_POLICY before these scripts run.
 */
const RC_PHONE_MASK_DEFAULT_POLICY = {
    enabled: true,
    visibleDigits: 4,
    minMaskChars: 0,
    maskChar: '*',
    emptyFallback: undefined
};

function rcGetPhoneMaskPolicy(overrides = null) {
    const globalPolicy = (window && window.RC_PHONE_MASK_POLICY && typeof window.RC_PHONE_MASK_POLICY === 'object')
        ? window.RC_PHONE_MASK_POLICY
        : {};
    const localOverrides = (overrides && typeof overrides === 'object') ? overrides : {};
    return Object.assign({}, RC_PHONE_MASK_DEFAULT_POLICY, globalPolicy, localOverrides);
}

function rcMaskPhoneNumber(input, overrides = null) {
    try {
        const policy = rcGetPhoneMaskPolicy(overrides);
        const raw = (input || '').toString();
        const digits = raw.replace(/\D/g, '');

        if (policy.enabled === false) {
            return raw || (policy.emptyFallback !== undefined ? policy.emptyFallback : '');
        }

        if (!digits) {
            return (policy.emptyFallback !== undefined) ? policy.emptyFallback : raw;
        }

        const visibleDigits = Math.max(0, parseInt(policy.visibleDigits, 10) || 0);
        const minMaskChars = Math.max(0, parseInt(policy.minMaskChars, 10) || 0);
        const maskChar = (typeof policy.maskChar === 'string' && policy.maskChar.length)
            ? policy.maskChar.charAt(0)
            : '*';

        if (digits.length <= visibleDigits) return digits;

        const maskCount = Math.max(minMaskChars, digits.length - visibleDigits);
        return maskChar.repeat(maskCount) + digits.slice(-visibleDigits);
    } catch (_) {
        return input;
    }
}

/**
 * Backward-compatible wrapper for legacy calls.
 */
function maskPhoneNumber(input, overrides = null) {
    return rcMaskPhoneNumber(input, overrides);
}

function rcGetFriendlyRingCentralErrorMessage(rawError, fallback = 'Something went wrong. Please try again.') {
    const msg = (rawError || '').toString().trim();
    if (!msg) return fallback;

    const lower = msg.toLowerCase();
    const withoutPrefix = msg
        .replace(/^(failed:\s*)+/i, '')
        .replace(/^failed to send sms:\s*/i, '')
        .replace(/^server error:\s*/i, '')
        .trim();

    if (
        lower.includes('phone number is blocked')
        || lower.includes('blocked number')
        || (lower.includes('parameter [to]') && lower.includes('blocked'))
    ) {
        return 'Blocked number.';
    }
    if (
        (lower.includes('parameter [to]') && lower.includes('invalid'))
        || (lower.includes('to.phonenumber') && lower.includes('invalid value'))
        || lower.includes('invalid phone number')
    ) {
        return 'Invalid phone number.';
    }
    if (
        (lower.includes('parameter [from]') && lower.includes('invalid'))
        || (lower.includes('from.phonenumber') && lower.includes('invalid value'))
        || lower.includes('invalid "text from"')
    ) {
        return 'Invalid sender number. Please reconnect R-Dialer.';
    }
    if (lower.includes('not connected to r-portal') || lower.includes('not connected to r-dialer')) {
        return 'R-Dialer is not connected. Please reconnect.';
    }
    if (lower.includes('failed to authenticate') || lower.includes('token') || lower.includes('unauthorized')) {
        return 'R-Dialer session expired. Please reconnect.';
    }
    if (lower.includes('network') || lower.includes('failed to fetch')) {
        return 'Network error. Please try again.';
    }
    if (lower.includes('validation failed')) {
        return 'Please check the form and try again.';
    }
    if (lower.includes('end call failed')) {
        return 'Could not end the call. Please try again.';
    }
    if (lower.includes('answer failed')) {
        return 'Could not answer the call. Please try again.';
    }
    if (lower.includes('decline failed')) {
        return 'Could not decline the call. Please try again.';
    }
    if (lower.includes('transfer failed')) {
        return 'Transfer failed. Please try again.';
    }
    if (lower.includes('remove participant failed')) {
        return 'Could not remove participant. Please try again.';
    }
    if (lower.includes('merge failed')) {
        return 'Merge failed. Please try again.';
    }
    if (lower.includes('invite failed')) {
        return 'Invite failed. Please try again.';
    }
    if (lower.includes('conference failed')) {
        return 'Conference failed. Please try again.';
    }
    if (lower.includes('switch to web failed')) {
        return 'Could not switch call to web. Please try again.';
    }
    if (lower.includes('recording') && lower.includes('failed')) {
        return 'Recording failed. Please try again.';
    }
    if (lower.includes('call error')) {
        return 'Call failed. Please try again.';
    }

    return withoutPrefix || fallback;
}

window.rcGetFriendlyRingCentralErrorMessage = rcGetFriendlyRingCentralErrorMessage;

(function rcInstallFriendlyAlertWrapper() {
    try {
        if (window.__rcFriendlyAlertInstalled || typeof window.alert !== 'function') return;
        window.__rcFriendlyAlertInstalled = true;
        const nativeAlert = window.alert.bind(window);
        window.alert = function (message) {
            const raw = (message || '').toString();
            const lower = raw.toLowerCase();
            const looksLikeProviderError =
                lower.includes('failed:')
                || lower.includes('failed to ')
                || lower.includes('error:')
                || lower.includes('parameter [')
                || lower.includes('phonenumber')
                || lower.includes('phone number is blocked')
                || lower.includes('blocked number')
                || lower.includes('unauthorized')
                || lower.includes('token')
                || lower.includes('network');
            nativeAlert(looksLikeProviderError ? rcGetFriendlyRingCentralErrorMessage(raw, raw) : raw);
        };
    } catch (_) { }
})();

/**
 * Set SMS From Number from connected phone number.
 */
function setSmsFromNumber() {
    try {
        const connectedPhoneEl = document.getElementById('connectedPhoneNumber');
        const smsFromNumberInput = document.getElementById('smsFromNumber');
        const smsFromNumberDisplay = document.getElementById('smsFromNumberDisplay');

        if (connectedPhoneEl && smsFromNumberInput) {
            const phoneNumber = connectedPhoneEl.getAttribute('data-full-number') || connectedPhoneEl.textContent.trim();
            smsFromNumberInput.value = phoneNumber;
            if (smsFromNumberDisplay) {
                smsFromNumberDisplay.textContent = (typeof maskPhoneNumber === 'function')
                    ? maskPhoneNumber(phoneNumber)
                    : phoneNumber;
            }
        }
    } catch (e) { console.warn('setSmsFromNumber failed', e); }
}

/**
 * Resume audio context for autoplay
 * Some browsers require user interaction to play audio
 */
function resumeAudioContext() {
    document.addEventListener('click', function resumeOnClick() {
        const ringtoneAudio = document.getElementById('rc-ringtone-audio') || document.getElementById('ringtoneAudio');
        const wp = window.webPhone;
        if (ringtoneAudio && wp && wp._ringtoneCtx) {
            if (wp._ringtoneCtx.state === 'suspended') {
                wp._ringtoneCtx.resume().then(() => {
                    console.log('AudioContext resumed via click');
                    ringtoneAudio.play();
                }).catch(err => console.error('Failed to resume AudioContext:', err));
            } else {
                ringtoneAudio.play();
            }
        }
        document.removeEventListener('click', resumeOnClick);
    });
}

/**
 * Apply an output device to a specific active call session.
 * Used by dialer and audio settings modules.
 */
async function setSessionOutput(sessionId, deviceId = 'default') {
    if (!sessionId) {
        throw new Error('Session ID is required');
    }

    if (!window.webPhone) {
        throw new Error('webPhone is not initialized');
    }

    const desiredDevice = deviceId || 'default';

    // Keep selector and preference in sync for UI continuity.
    try {
        const perRowSelect = document.getElementById(`audioOutputSelect-${sessionId}`);
        if (perRowSelect) {
            perRowSelect.value = desiredDevice;
        }
        window.webPhone.currentOutputDeviceId = desiredDevice;
    } catch (_) { }

    if (typeof window.webPhone.changeOutputDeviceForSession === 'function') {
        await window.webPhone.changeOutputDeviceForSession(sessionId, desiredDevice);
        return true;
    }

    if (typeof window.webPhone.setOutputDeviceForSession === 'function') {
        await window.webPhone.setOutputDeviceForSession(sessionId, desiredDevice);
        return true;
    }

    if (typeof window.webPhone.changeOutputDevice === 'function') {
        await window.webPhone.changeOutputDevice(desiredDevice);
        return true;
    }

    throw new Error('No supported output-device method on webPhone');
}

/**
 * Logout and dispose WebPhone
 */
async function logout() {
    if (confirm('Are you sure you want to disconnect and logout?')) {
        try {
            if (webPhone) {
                await webPhone.dispose();
            }
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const response = await fetch(rcRoute('logout'), {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrf
                }
            });
            if (!response.ok) {
                let message = `Logout failed (${response.status})`;
                try {
                    const data = await response.json();
                    if (data && data.message) message = data.message;
                } catch (_) { }
                console.error('Logout failed:', message);
                showErrorModal(message, false);
                return;
            }

            window.location.reload();
        } catch (err) {
            console.error('Logout error:', err);
            showErrorModal('Logout failed due to a network error. Please try again.', false);
        }
    }
}

window.rcShowCallsDetailPanel = rcShowCallsDetailPanel;
window.rcHideCallsDetailPanel = rcHideCallsDetailPanel;

function rcEnsureToastStyles() {
    // Primary styling is served from local /css/ringcentral.css.
    // Keep a tiny runtime fallback if CSS did not load.
    if (document.getElementById('rc-toast-runtime-fallback')) return;
    const hasCssLoaded = !!document.querySelector('link[href*="ringcentral.css"]');
    if (hasCssLoaded) return;

    const style = document.createElement('style');
    style.id = 'rc-toast-runtime-fallback';
    style.textContent = `
        #rcToastStack { position: fixed; right: 16px; bottom: 16px; z-index: 2147483000; display: flex; flex-direction: column; gap: 10px; max-width: min(92vw, 380px); pointer-events: none; }
        .rc-toast { pointer-events: auto; border-radius: 8px; box-shadow: 0 6px 16px rgba(0,0,0,.2); background: #fff; color: #111; border-left: 4px solid #0d6efd; padding: 10px 12px; opacity: 0; transform: translateY(8px); transition: opacity .2s ease, transform .2s ease; }
        .rc-toast.show { opacity: 1; transform: translateY(0); }
        .rc-toast-close { background: transparent; border: 0; cursor: pointer; }
        .rc-toast-progress { margin-top: 8px; height: 2px; background: rgba(0,0,0,.08); overflow: hidden; }
        .rc-toast-progress > span { display: block; height: 100%; width: 100%; background: #0d6efd; }
    `;
    document.head.appendChild(style);
}

function rcEnsureToastContainer() {
    let container = document.getElementById('rcToastStack');
    if (container) return container;
    container = document.createElement('div');
    container.id = 'rcToastStack';
    container.setAttribute('aria-live', 'polite');
    container.setAttribute('aria-atomic', 'false');
    document.body.appendChild(container);
    return container;
}

function rcPlayNotificationPop() {
    try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;

        if (!window._rcToastAudioCtx) {
            window._rcToastAudioCtx = new AudioCtx();
        }
        const ctx = window._rcToastAudioCtx;
        if (!ctx || typeof ctx.createOscillator !== 'function') return;
        if (ctx.state === 'suspended' && typeof ctx.resume === 'function') {
            ctx.resume().catch(function () { });
        }

        const now = ctx.currentTime || 0;
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();

        osc.type = 'sine';
        osc.frequency.setValueAtTime(920, now);
        osc.frequency.exponentialRampToValueAtTime(660, now + 0.14);

        gain.gain.setValueAtTime(0.0001, now);
        gain.gain.exponentialRampToValueAtTime(0.12, now + 0.02);
        gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.18);

        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.start(now);
        osc.stop(now + 0.2);
    } catch (_) { }
}

function rcShowPortalToast(message, type = 'info', options = {}) {
    try {
        const normalizedType = String(type || 'info').toLowerCase();
        const shouldUseFriendlyError = normalizedType === 'error'
            || normalizedType === 'danger'
            || normalizedType === 'warning';
        const msg = shouldUseFriendlyError
            ? rcGetFriendlyRingCentralErrorMessage(message, '')
            : String(message || '').trim();
        if (!msg) return;

        rcEnsureToastStyles();
        const container = rcEnsureToastContainer();
        const tone = options.playSound !== false;
        const duration = Math.max(1200, parseInt(options.duration, 10) || 3800);
        const colorType = (normalizedType === 'danger')
            ? 'error'
            : (['info', 'success', 'warning', 'error'].includes(normalizedType) ? normalizedType : 'info');
        const titleMap = {
            info: 'Info',
            success: 'Success',
            warning: 'Warning',
            error: 'Error'
        };
        const title = String(options.title || titleMap[colorType] || 'Info');

        const toast = document.createElement('div');
        toast.className = `rc-toast rc-toast-${colorType} rc-toast-toastr`;
        toast.innerHTML = `
            <div class="rc-toast-body">
                <div class="rc-toast-title">${title}</div>
                <div class="rc-toast-message">${msg}</div>
            </div>
            <button type="button" class="rc-toast-close" aria-label="Close">&times;</button>
            <div class="rc-toast-progress" aria-hidden="true"><span></span></div>
        `;
        container.appendChild(toast);

        let removed = false;
        const removeToast = function () {
            if (removed) return;
            removed = true;
            toast.classList.remove('show');
            window.setTimeout(function () {
                if (toast.parentNode) toast.parentNode.removeChild(toast);
            }, 220);
        };

        const closeBtn = toast.querySelector('.rc-toast-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function (e) {
                e.preventDefault();
                removeToast();
            });
        }

        window.requestAnimationFrame(function () {
            toast.classList.add('show');
            const progress = toast.querySelector('.rc-toast-progress > span');
            if (progress) {
                progress.style.transition = `width ${duration}ms linear`;
                progress.style.width = '0%';
            }
        });
        window.setTimeout(removeToast, duration);

        if (tone) {
            rcPlayNotificationPop();
        }

    } catch (_) { }
}

function rcNormalizeWebhookPhoneNumber(value) {
    const raw = String(value || '').trim();
    if (!raw || raw.includes('@')) return '';
    const digits = raw.replace(/\D/g, '');
    if (digits.length < 7 || digits.length > 15) return '';
    return raw.startsWith('+') ? ('+' + digits) : digits;
}

function rcExtractWebhookPartyNumber(data) {
    const candidateList = [
        data && data.partyNumber,
        data && data.party_number,
        Array.isArray(data && data.partyNumbers) ? data.partyNumbers[0] : null,
        Array.isArray(data && data.party_numbers) ? data.party_numbers[0] : null,
        data && data.number,
        data && data.phone,
    ];
    for (let i = 0; i < candidateList.length; i++) {
        const normalized = rcNormalizeWebhookPhoneNumber(candidateList[i]);
        if (normalized) return normalized;
    }
    return '';
}

function rcExtractWebhookMessageText(data) {
    const candidateList = [
        data && data.messageText,
        data && data.message_text,
        Array.isArray(data && data.messageTexts) ? data.messageTexts[0] : null,
        Array.isArray(data && data.message_texts) ? data.message_texts[0] : null,
        data && data.text,
        data && data.subject,
    ];
    for (let i = 0; i < candidateList.length; i++) {
        const value = candidateList[i];
        if (typeof value !== 'string' && typeof value !== 'number') continue;
        let text = String(value).trim();
        if (!text) continue;
        text = text.replace(/\s+/g, ' ');
        if (text.length > 140) text = text.slice(0, 137) + '...';
        return text;
    }
    return '';
}

function rcExtractWebhookDurationSeconds(data) {
    const candidateList = [
        data && data.durationSeconds,
        data && data.duration_seconds,
        Array.isArray(data && data.durationSecondsList) ? data.durationSecondsList[0] : null,
        Array.isArray(data && data.duration_seconds_list) ? data.duration_seconds_list[0] : null,
        data && data.duration,
    ];
    for (let i = 0; i < candidateList.length; i++) {
        const value = candidateList[i];
        if (value === null || value === undefined || value === '') continue;
        if (typeof value === 'number' && Number.isFinite(value)) {
            const seconds = Math.max(0, Math.round(value));
            if (seconds > 0) return seconds;
            continue;
        }
        const text = String(value).trim();
        if (!text) continue;
        const mmss = text.match(/^(\d+):(\d{1,2})$/);
        if (mmss) {
            const seconds = (parseInt(mmss[1], 10) * 60) + parseInt(mmss[2], 10);
            if (seconds > 0) return seconds;
            continue;
        }
        const hhmmss = text.match(/^(\d+):(\d{1,2}):(\d{1,2})$/);
        if (hhmmss) {
            const seconds = (parseInt(hhmmss[1], 10) * 3600) + (parseInt(hhmmss[2], 10) * 60) + parseInt(hhmmss[3], 10);
            if (seconds > 0) return seconds;
            continue;
        }
        if (/^\d+(\.\d+)?$/.test(text)) {
            const seconds = Math.max(0, Math.round(parseFloat(text)));
            if (seconds > 0) return seconds;
        }
    }
    return null;
}

function rcFormatWebhookDuration(seconds) {
    const s = parseInt(seconds, 10);
    if (!s || s <= 0) return '';
    const minutes = Math.floor(s / 60);
    const rem = s % 60;
    return `${minutes}:${String(rem).padStart(2, '0')}`;
}

function rcMaskWebhookPhoneForToast(phone) {
    const normalized = rcNormalizeWebhookPhoneNumber(phone);
    if (!normalized) return '';
    return rcMaskPhoneNumber(normalized, { minMaskChars: 6, emptyFallback: '' });
}

function rcNotifyWebhookEvent(entity, count = 1, meta = null) {
    const total = 1;
    const metaObj = meta && typeof meta === 'object' ? meta : {};
    const partyNumber = rcNormalizeWebhookPhoneNumber(metaObj.number || metaObj.partyNumber || metaObj.phone || '');
    const maskedPartyNumber = rcMaskWebhookPhoneForToast(partyNumber);
    const messageText = rcExtractWebhookMessageText(metaObj);
    const durationSeconds = rcExtractWebhookDurationSeconds(metaObj);
    const durationLabel = rcFormatWebhookDuration(durationSeconds);
    const map = {
        messages: total === 1
            ? (maskedPartyNumber
                ? `New message from ${maskedPartyNumber}${messageText ? `: ${messageText}` : ''}`
                : `New message received${messageText ? `: ${messageText}` : ''}`)
            : (maskedPartyNumber
                ? `${total} new messages (latest: ${maskedPartyNumber})${messageText ? `: ${messageText}` : ''}`
                : `${total} new messages received${messageText ? `: ${messageText}` : ''}`),
        calls: total === 1
            ? (maskedPartyNumber ? `New call from ${maskedPartyNumber}` : 'New call event received')
            : (maskedPartyNumber ? `${total} new call events (latest: ${maskedPartyNumber})` : `${total} new call events received`),
        voicemails: total === 1
            ? (maskedPartyNumber
                ? `New voicemail from ${maskedPartyNumber}${durationLabel ? ` (${durationLabel})` : ''}`
                : `New voicemail received${durationLabel ? ` (${durationLabel})` : ''}`)
            : (maskedPartyNumber
                ? `${total} new voicemails (latest: ${maskedPartyNumber})${durationLabel ? ` (${durationLabel})` : ''}`
                : `${total} new voicemails received${durationLabel ? ` (${durationLabel})` : ''}`),
        recordings: total === 1
            ? (maskedPartyNumber
                ? `New recording for ${maskedPartyNumber}${durationLabel ? ` (${durationLabel})` : ''}`
                : `New recording available${durationLabel ? ` (${durationLabel})` : ''}`)
            : (maskedPartyNumber
                ? `${total} new recordings (latest: ${maskedPartyNumber})${durationLabel ? ` (${durationLabel})` : ''}`
                : `${total} new recordings available${durationLabel ? ` (${durationLabel})` : ''}`),
        generic: total === 1
            ? (maskedPartyNumber ? `New R-Dialer event for ${maskedPartyNumber}` : 'New R-Dialer event received')
            : (maskedPartyNumber ? `${total} new R-Dialer events (latest: ${maskedPartyNumber})` : `${total} new R-Dialer events received`)
    };
    const key = String(entity || '').toLowerCase();
    const text = map[key] || (total === 1 ? 'New R-Dialer event received' : `${total} new R-Dialer events received`);
    rcShowPortalToast(text, 'info', { playSound: true, duration: 5000 });
}

function rcNormalizeWebhookScope(scope) {
    const raw = String(scope || '').toLowerCase().trim();
    if (!raw) return '';
    if (raw === 'message' || raw === 'messages' || raw === 'sms' || raw === 'text' || raw === 'texts') return 'messages';
    if (raw === 'call' || raw === 'calls') return 'calls';
    if (raw === 'voicemail' || raw === 'voicemails') return 'voicemails';
    if (raw === 'recording' || raw === 'recordings') return 'recordings';
    return raw;
}

function rcScopesFromWebhookPayload(data) {
    const scopes = Array.isArray(data && data.scopes) ? data.scopes : (data && data.scope ? [data.scope] : []);
    let normalized = scopes
        .map(rcNormalizeWebhookScope)
        .filter(Boolean);

    // Fallback for legacy payloads that may not include scopes.
    if (!normalized.length) {
        const eventName = String((data && data.event) || '').toLowerCase();
        if (eventName.includes('/message-store') || eventName.includes('instant?type=sms')) normalized.push('messages');
        if (eventName.includes('/call-log')) normalized.push('calls');
        if (eventName.includes('/voicemail')) normalized.push('voicemails');
    }

    return Array.from(new Set(normalized));
}

function rcQueueWebhookScopeRefresh(scope) {
    const normalized = String(scope || '').toLowerCase().trim();
    if (!normalized) return;

    window._rcWebhookScopeTimers = window._rcWebhookScopeTimers || {};
    if (window._rcWebhookScopeTimers[normalized]) {
        try {
            console.info('[RC Webhook Refresh Queue]', { scope: normalized, action: 'already_queued' });
        } catch (_) { }
        return;
    }

    try {
        console.info('[RC Webhook Refresh Queue]', { scope: normalized, action: 'queued' });
    } catch (_) { }

    window._rcWebhookScopeTimers[normalized] = window.setTimeout(function () {
        try {
            delete window._rcWebhookScopeTimers[normalized];
        } catch (_) { }

        try {
            try {
                console.info('[RC Webhook Refresh Queue]', { scope: normalized, action: 'executing' });
            } catch (_) { }
            if (normalized === 'messages') {
                if (typeof loadMessagesIncrementalFromWebhook === 'function') {
                    loadMessagesIncrementalFromWebhook().catch(function () { });
                    return;
                }
                if (typeof loadMessageHistory === 'function') {
                    loadMessageHistory(null, false, null, false, false).catch(function () { });
                }
                return;
            }
            if (normalized === 'calls') {
                if (typeof loadCallsIncrementalFromWebhook === 'function') {
                    loadCallsIncrementalFromWebhook().catch(function () { });
                } else if (typeof loadCallHistory === 'function') {
                    loadCallHistory(null, false, null, false).catch(function () { });
                }
                if (typeof refreshCallsSummary === 'function') {
                    refreshCallsSummary(false).catch(function () { });
                }
                return;
            }
            if (normalized === 'voicemails') {
                if (typeof loadVoicemailsIncrementalFromWebhook === 'function') {
                    loadVoicemailsIncrementalFromWebhook().catch(function () { });
                } else if (typeof loadVoicemails === 'function') {
                    loadVoicemails(false, false, null, true, 'webhook_event').catch(function () { });
                }
                return;
            }
            if (normalized === 'recordings') {
                if (typeof loadRecordingsIncrementalFromWebhook === 'function') {
                    loadRecordingsIncrementalFromWebhook().catch(function () { });
                } else if (typeof loadCallRecordings === 'function') {
                    loadCallRecordings(false, false, null, true, 'webhook_event').catch(function () { });
                }
            }
        } catch (_) { }
    }, 250);
}

function rcGetWebhookSeenSeqState() {
    const key = 'rcWebhookSeenSeqState';
    const nowMs = Date.now();
    const ttlMs = 15 * 60 * 1000; // 15 minutes
    const maxItems = 400;

    let entries = [];
    try {
        const raw = window.localStorage ? window.localStorage.getItem(key) : null;
        if (raw) {
            const parsed = JSON.parse(raw);
            if (Array.isArray(parsed)) {
                entries = parsed;
            }
        }
    } catch (_) { }

    entries = entries
        .filter(function (row) {
            const seq = parseInt(row && row.seq, 10);
            const seenAt = parseInt(row && row.seenAt, 10);
            return seq > 0 && seenAt > 0 && (nowMs - seenAt) <= ttlMs;
        })
        .sort(function (a, b) { return (a.seenAt || 0) - (b.seenAt || 0); });

    if (entries.length > maxItems) {
        entries = entries.slice(entries.length - maxItems);
    }

    return { key: key, entries: entries, nowMs: nowMs };
}

function rcPersistWebhookSeenSeqState(state) {
    if (!state || !window.localStorage) return;
    try {
        window.localStorage.setItem(state.key, JSON.stringify(state.entries));
    } catch (_) { }
}

function rcHasSeenWebhookSeq(seq) {
    const normalized = parseInt(seq, 10);
    if (!(normalized > 0)) return false;
    const state = rcGetWebhookSeenSeqState();
    const found = state.entries.some(function (row) {
        return parseInt(row && row.seq, 10) === normalized;
    });
    rcPersistWebhookSeenSeqState(state);
    return found;
}

function rcMarkWebhookSeqSeen(seq) {
    const normalized = parseInt(seq, 10);
    if (!(normalized > 0)) return;
    const state = rcGetWebhookSeenSeqState();
    const exists = state.entries.some(function (row) {
        return parseInt(row && row.seq, 10) === normalized;
    });
    if (!exists) {
        state.entries.push({ seq: normalized, seenAt: state.nowMs });
    }
    rcPersistWebhookSeenSeqState(state);
}

function rcHandleWebhookStreamPayload(payload) {
    const data = payload && typeof payload === 'object' ? payload : {};
    const seq = parseInt(data.seq, 10) || 0;
    if (seq > 0 && rcHasSeenWebhookSeq(seq)) {
        try {
            console.info('[RC Webhook Dedupe]', { seq: seq, action: 'suppressed_already_seen' });
        } catch (_) { }
        return;
    }

    const scopes = rcScopesFromWebhookPayload(data);
    const rawBatchedCount = Math.max(1, parseInt((data.batchedCount ?? data.batched_count ?? 1), 10) || 1);
    const eventCount = 1;
    const direction = String(data.direction || '').toLowerCase().trim() || null;
    const toastEnabled = !(
        data.toastEnabled === false ||
        data.toast_enabled === false ||
        String(data.toastEnabled || '').toLowerCase() === 'false' ||
        String(data.toast_enabled || '').toLowerCase() === 'false'
    );
    const partyNumber = rcExtractWebhookPartyNumber(data);
    const messageText = rcExtractWebhookMessageText(data);
    const durationSeconds = rcExtractWebhookDurationSeconds(data);
    const genericToast = !partyNumber && !messageText && !durationSeconds;
    if (genericToast) {
        try {
            console.info('[RC Webhook Toast Fallback]', {
                seq: seq || null,
                action: 'payload_details_missing_generic_toast'
            });
        } catch (_) { }
    }
    try {
        console.info('[RC Webhook Toast Meta]', {
            seq: data.seq || null,
            event: data.event || null,
            scopes: scopes,
            batchedCount: eventCount,
            rawBatchedCount: rawBatchedCount,
            direction: direction,
            toastEnabled: toastEnabled,
            partyNumber: partyNumber || null,
            messageText: messageText || null,
            durationSeconds: durationSeconds,
            partyNumbers: Array.isArray(data.partyNumbers) ? data.partyNumbers : [],
            messageTexts: Array.isArray(data.messageTexts) ? data.messageTexts : [],
            durationSecondsList: Array.isArray(data.durationSecondsList) ? data.durationSecondsList : [],
        });
    } catch (_) { }
    if (!scopes.length) {
        if (toastEnabled && typeof window.rcNotifyWebhookEvent === 'function') {
            window.rcNotifyWebhookEvent('generic', eventCount, { partyNumber, messageText, durationSeconds });
        }
        if (seq > 0) {
            rcMarkWebhookSeqSeen(seq);
        }
        return;
    }

    scopes.forEach(function (scope) {
        if (scope === 'messages' && !partyNumber && !messageText) {
            try {
                console.info('[RC Webhook Toast Suppressed]', {
                    seq: seq || null,
                    scope: scope,
                    action: 'missing_message_payload_details'
                });
            } catch (_) { }
            return;
        }
        if (toastEnabled && typeof window.rcNotifyWebhookEvent === 'function') {
            window.rcNotifyWebhookEvent(scope, eventCount, { partyNumber, messageText, durationSeconds });
        }
    });
    scopes.forEach(rcQueueWebhookScopeRefresh);

    if (seq > 0) {
        rcMarkWebhookSeqSeen(seq);
    }
}

function rcStartWebhookEventStream() {
    if (window._rcWebhookStreamStarted === '1') return;
    if (typeof window.EventSource === 'undefined') return;
    if (typeof rcRoute !== 'function') return;

    let since = 0;
    try {
        since = parseInt((window.localStorage && window.localStorage.getItem('rcWebhookStreamLastSeq')) || '0', 10) || 0;
    } catch (_) {
        since = 0;
    }

    let streamUrl = '';
    try {
        streamUrl = rcRoute('ringcentral.api.events.stream') + '?since=' + encodeURIComponent(String(since));
    } catch (_) {
        return;
    }

    const source = new EventSource(streamUrl);
    window._rcWebhookStreamStarted = '1';
    window._rcWebhookEventSource = source;

    source.addEventListener('webhook', function (event) {
        try {
            const payload = event && event.data ? JSON.parse(event.data) : {};
            const seqFromPayload = parseInt(payload && payload.seq ? payload.seq : 0, 10) || 0;
            const seqFromHeader = parseInt((event && event.lastEventId) ? event.lastEventId : 0, 10) || 0;
            const seq = Math.max(seqFromPayload, seqFromHeader);
            if (seq > 0) {
                try {
                    if (window.localStorage) window.localStorage.setItem('rcWebhookStreamLastSeq', String(seq));
                } catch (_) { }
            }
            rcHandleWebhookStreamPayload(payload);
        } catch (_) { }
    });

    source.addEventListener('ping', function () { });

    source.onerror = function () {
        // EventSource auto-reconnects; keep stream open.
    };

    window.addEventListener('beforeunload', function () {
        try {
            if (window._rcWebhookEventSource && typeof window._rcWebhookEventSource.close === 'function') {
                window._rcWebhookEventSource.close();
            }
        } catch (_) { }
    });
}

window.rcShowPortalToast = rcShowPortalToast;
window.rcNotifyWebhookEvent = rcNotifyWebhookEvent;
window.rcStartWebhookEventStream = rcStartWebhookEventStream;
window.rcGetPhoneMaskPolicy = rcGetPhoneMaskPolicy;
window.rcMaskPhoneNumber = rcMaskPhoneNumber;
if (typeof window.showToast !== 'function') {
    window.showToast = rcShowPortalToast;
}

// Auto-run on page load
resumeAudioContext();
