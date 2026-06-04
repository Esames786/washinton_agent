/**
 * R-Dialer  Data Loading Functions
 * Handles loading phone numbers, message history, and form submissions
 */

const RC_PENDING_DIAL_REQUEST_KEY = 'rcPendingDialRequest';
const RC_LAST_PROCESSED_DIAL_REQUEST_KEY = 'rcLastProcessedDialRequest';
const RC_PENDING_MESSAGE_REQUEST_KEY = 'rcPendingMessageRequest';
const RC_LAST_PROCESSED_MESSAGE_REQUEST_KEY = 'rcLastProcessedMessageRequest';
let rcPendingDialRequestInFlight = null;
let rcPendingMessageRequestInFlight = null;

function getSmsUnsupportedCountryLabel(compactRaw, digits) {
    const knownCountryCodes = ['91', '92', '44', '52', '61', '63', '81', '86', '971'];
    for (let i = 0; i < knownCountryCodes.length; i += 1) {
        if (compactRaw.indexOf('+' + knownCountryCodes[i]) === 0) {
            return knownCountryCodes[i];
        }
    }

    const match = compactRaw.match(/^\+(\d{2,3})/);
    return match ? match[1] : digits.charAt(0);
}

function rcValidateSmsPhoneNumber(rawPhone) {
    const raw = (rawPhone || '').toString().trim();
    const compactRaw = raw.replace(/\s+/g, '');
    const digits = raw.replace(/\D/g, '');
    if (!digits) {
        return {
            valid: false,
            normalized: null,
            message: 'Enter a phone number.'
        };
    }

    if (compactRaw.charAt(0) === '+' && compactRaw.indexOf('+1') !== 0) {
        const countryCode = getSmsUnsupportedCountryLabel(compactRaw, digits);
        return {
            valid: false,
            normalized: null,
            message: `Only US/Canada SMS numbers are supported. Use +1XXXXXXXXXX; this number starts with +${countryCode}.`
        };
    }

    // Enforce NANP (+1 / US-Canada) with stricter NPA/NXX rules.
    // NPA (area): [2-9]\d\d and not N11
    // NXX (exchange): [2-9]\d\d and not N11
    let local = '';
    if (digits.length === 10) {
        local = digits;
    } else if (digits.length === 11 && digits.charAt(0) === '1') {
        local = digits.slice(1);
    } else if (digits.length < 10) {
        return {
            valid: false,
            normalized: null,
            message: `Too few digits for SMS. Use a 10-digit US/Canada number or +1XXXXXXXXXX; currently ${digits.length} digit${digits.length === 1 ? '' : 's'}.`
        };
    } else {
        return {
            valid: false,
            normalized: null,
            message: `Too many digits or unsupported country code. Use a 10-digit US/Canada number or +1XXXXXXXXXX; currently ${digits.length} digits.`
        };
    }

    const areaCode = local.slice(0, 3);
    const exchangeCode = local.slice(3, 6);

    if (!/^[2-9]\d{2}$/.test(areaCode)) {
        return {
            valid: false,
            normalized: null,
            message: `Invalid area code "${areaCode}". US/Canada area code must be 3 digits and start with 2-9.`
        };
    }

    if (areaCode.slice(1, 3) === '11') {
        return {
            valid: false,
            normalized: null,
            message: `Invalid area code "${areaCode}". N11 area codes like 211, 311, or 911 cannot be used.`
        };
    }

    if (!/^[2-9]\d{2}$/.test(exchangeCode)) {
        return {
            valid: false,
            normalized: null,
            message: `Invalid exchange code "${exchangeCode}". Digits 4-6 must start with 2-9.`
        };
    }

    if (exchangeCode.slice(1, 3) === '11') {
        return {
            valid: false,
            normalized: null,
            message: `Invalid exchange code "${exchangeCode}". N11 exchanges like 211, 311, or 911 cannot be used.`
        };
    }

    return {
        valid: true,
        normalized: '+1' + local,
        message: ''
    };
}

function rcNormalizeSmsPhoneNumber(rawPhone) {
    const validation = rcValidateSmsPhoneNumber(rawPhone);
    return validation.valid ? validation.normalized : null;
}

function getSmsModalUiRefs() {
    return {
        phoneInput: document.getElementById('smsPhone'), // hidden legacy compatibility field
        phoneEntryInput: document.getElementById('smsPhoneEntry'),
        phoneChips: document.getElementById('smsRecipientChips'),
        phoneWrap: document.getElementById('smsRecipientsWrap'),
        phoneClear: document.getElementById('smsPhoneClear'),
        groupToggle: document.getElementById('smsCreateGroupText'),
        groupNameWrap: document.getElementById('smsGroupNameWrap'),
        groupNameInput: document.getElementById('smsGroupName'),
        msgInput: document.getElementById('smsMessage'),
        phoneError: document.getElementById('smsPhoneError'),
        msgError: document.getElementById('smsMessageError'),
        globalError: document.getElementById('smsFormGlobalError'),
        continueBtn: document.getElementById('smsContinueBtn'),
    };
}

function setSmsFieldError(inputEl, errorEl, message) {
    if (!inputEl || !errorEl) return;
    if (message) {
        inputEl.classList.add('rc-sms-invalid');
        errorEl.textContent = message;
        errorEl.style.display = 'block';
    } else {
        inputEl.classList.remove('rc-sms-invalid');
        errorEl.style.display = 'none';
    }
}

const RC_SMS_MAX_RECIPIENTS = 10;
window._rc_smsModalRecipients = window._rc_smsModalRecipients || [];

function getSmsModalRecipients() {
    if (!Array.isArray(window._rc_smsModalRecipients)) {
        window._rc_smsModalRecipients = [];
    }
    return window._rc_smsModalRecipients;
}

function parseSmsRecipientsInput(raw) {
    return (raw || '')
        .toString()
        .split(/[\n,;]+/g)
        .map(v => v.trim())
        .filter(Boolean);
}

function getSmsValidationMessageForValues(rawValues, fallbackMessage = 'Invalid phone number.') {
    const values = Array.isArray(rawValues) ? rawValues : [rawValues];
    for (let i = 0; i < values.length; i += 1) {
        const validation = rcValidateSmsPhoneNumber(values[i]);
        if (!validation.valid) {
            return validation.message || fallbackMessage;
        }
    }

    return fallbackMessage;
}

function syncSmsLegacyPhoneField() {
    const refs = getSmsModalUiRefs();
    if (!refs.phoneInput) return;
    refs.phoneInput.value = getSmsModalRecipients().join(', ');
}

function renderSmsRecipientChips() {
    const refs = getSmsModalUiRefs();
    if (!refs.phoneChips || !refs.phoneEntryInput) return;

    const existingChips = refs.phoneChips.querySelectorAll('.rc-sms-recipient-chip');
    existingChips.forEach(node => node.remove());

    const recipients = getSmsModalRecipients();
    recipients.forEach((recipient) => {
        const chip = document.createElement('span');
        chip.className = 'badge badge-secondary rc-sms-recipient-chip d-inline-flex align-items-center';
        chip.style.gap = '6px';
        chip.textContent = recipient;

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-link btn-sm p-0 text-white';
        removeBtn.setAttribute('aria-label', 'Remove recipient');
        removeBtn.textContent = '×';
        removeBtn.addEventListener('click', function (event) {
            event.preventDefault();
            removeSmsRecipient(recipient);
            validateSmsModalDraft();
        });

        chip.appendChild(removeBtn);
        refs.phoneChips.insertBefore(chip, refs.phoneEntryInput);
    });
}

function addSmsRecipients(rawValues) {
    const recipients = getSmsModalRecipients();
    const added = [];
    const duplicates = [];
    const invalid = [];
    const invalidDetails = [];
    const overLimit = [];
    const values = Array.isArray(rawValues) ? rawValues : [rawValues];

    values.forEach((rawValue) => {
        const validation = rcValidateSmsPhoneNumber(rawValue);
        const normalized = validation.normalized;
        if (!validation.valid || !normalized) {
            if ((rawValue || '').toString().trim()) {
                const value = (rawValue || '').toString().trim();
                invalid.push(value);
                invalidDetails.push({
                    value,
                    message: validation.message || 'Invalid phone number.'
                });
            }
            return;
        }
        if (recipients.includes(normalized)) {
            duplicates.push(normalized);
            return;
        }
        if (recipients.length >= RC_SMS_MAX_RECIPIENTS) {
            overLimit.push(normalized);
            return;
        }
        recipients.push(normalized);
        added.push(normalized);
    });

    renderSmsRecipientChips();
    syncSmsLegacyPhoneField();
    return { added, duplicates, invalid, invalidDetails, overLimit };
}

function removeSmsRecipient(number) {
    const recipients = getSmsModalRecipients();
    const idx = recipients.indexOf(number);
    if (idx >= 0) recipients.splice(idx, 1);
    renderSmsRecipientChips();
    syncSmsLegacyPhoneField();
}

function clearSmsRecipients() {
    window._rc_smsModalRecipients = [];
    const refs = getSmsModalUiRefs();
    if (refs.phoneEntryInput) refs.phoneEntryInput.value = '';
    renderSmsRecipientChips();
    syncSmsLegacyPhoneField();
}

function syncSmsRecipientsFromLegacyField(forceReplace = false) {
    const refs = getSmsModalUiRefs();
    if (!refs.phoneInput) return;
    const legacyRaw = (refs.phoneInput.value || '').toString().trim();
    if (!legacyRaw) {
        if (forceReplace) clearSmsRecipients();
        return;
    }

    const incoming = parseSmsRecipientsInput(legacyRaw);
    if (!incoming.length) return;

    if (forceReplace) {
        window._rc_smsModalRecipients = [];
    }

    addSmsRecipients(incoming);
}

function updateSmsPhoneClearButton() {
    const refs = getSmsModalUiRefs();
    if (!refs.phoneClear) return;
    const hasRecipients = getSmsModalRecipients().length > 0;
    const hasTyped = !!((refs.phoneEntryInput && refs.phoneEntryInput.value || '').trim());
    refs.phoneClear.style.display = (hasRecipients || hasTyped) ? 'inline-flex' : 'none';
}

function wireSmsPhoneClearButton() {
    const refs = getSmsModalUiRefs();
    if (!refs.phoneClear) return;
    if (refs.phoneClear.dataset.rcSmsClearBound === '1') return;

    refs.phoneClear.dataset.rcSmsClearBound = '1';
    refs.phoneClear.addEventListener('click', function () {
        clearSmsRecipients();
        if (refs.phoneEntryInput) refs.phoneEntryInput.focus();
        validateSmsModalDraft();
    });

    updateSmsPhoneClearButton();
}

function setSmsGlobalError(message) {
    const refs = getSmsModalUiRefs();
    if (!refs.globalError) return;
    if (message) {
        const friendlyMessage = (typeof window.rcGetFriendlyRingCentralErrorMessage === 'function')
            ? window.rcGetFriendlyRingCentralErrorMessage(message, message)
            : message;
        refs.globalError.textContent = friendlyMessage;
        refs.globalError.style.display = 'block';
    } else {
        refs.globalError.style.display = 'none';
        refs.globalError.textContent = '';
    }
}

function validateSmsModalDraft() {
    const refs = getSmsModalUiRefs();
    syncSmsRecipientsFromLegacyField(false);

    let entryPhoneError = '';
    if (refs.phoneEntryInput) {
        const pending = parseSmsRecipientsInput(refs.phoneEntryInput.value || '');
        if (pending.length) {
            const allPendingValid = pending.every(v => rcValidateSmsPhoneNumber(v).valid);
            if (allPendingValid) {
                addSmsRecipients(pending);
                refs.phoneEntryInput.value = '';
            } else {
                entryPhoneError = getSmsValidationMessageForValues(pending);
            }
        }
    }

    const recipients = getSmsModalRecipients();
    const hasRecipients = recipients.length > 0;
    const messageValue = refs.msgInput ? (refs.msgInput.value || '').trim() : '';
    const maxError = recipients.length > RC_SMS_MAX_RECIPIENTS
        ? `You can add up to ${RC_SMS_MAX_RECIPIENTS} recipients.`
        : '';
    const phoneError = entryPhoneError || maxError;
    const messageError = '';
    const blocksContinue = !!(phoneError || !hasRecipients || !messageValue);

    if (refs.phoneWrap && refs.phoneError) {
        setSmsFieldError(refs.phoneWrap, refs.phoneError, phoneError);
    }
    setSmsFieldError(refs.msgInput, refs.msgError, messageError);
    updateSmsPhoneClearButton();

    if (refs.continueBtn) {
        refs.continueBtn.disabled = blocksContinue;
    }

    if (refs.groupToggle && refs.groupNameWrap) {
        const on = !!refs.groupToggle.checked;
        refs.groupNameWrap.classList.toggle('d-none', !on);
    }

    return {
        isValid: !blocksContinue,
        recipients: recipients.slice(),
        normalizedPhone: recipients[0] || null,
        message: messageValue,
        phoneError: phoneError,
        messageError: messageError,
        missingPhone: !hasRecipients,
        missingMessage: !messageValue,
        createGroupText: !!(refs.groupToggle && refs.groupToggle.checked),
        groupName: refs.groupNameInput ? (refs.groupNameInput.value || '').trim() : ''
    };
}

function commitSmsEntryRecipients(refs) {
    if (!refs || !refs.phoneEntryInput) {
        return { added: [], duplicates: [], invalid: [], invalidDetails: [], overLimit: [] };
    }

    const pending = parseSmsRecipientsInput(refs.phoneEntryInput.value || '');
    if (!pending.length) {
        return { added: [], duplicates: [], invalid: [], invalidDetails: [], overLimit: [] };
    }

    const result = addSmsRecipients(pending);
    const unresolved = []
        .concat(result.invalid || [])
        .concat(result.overLimit || []);

    refs.phoneEntryInput.value = unresolved.length ? unresolved.join(', ') : '';
    return result;
}

function wireSmsModalValidation() {
    const refs = getSmsModalUiRefs();
    if (!refs.phoneInput || !refs.msgInput) return;
    if (refs.phoneInput.dataset.rcSmsValidationBound === '1') return;

    refs.phoneInput.dataset.rcSmsValidationBound = '1';
    const onInput = function () {
        const result = validateSmsModalDraft();
        if (result.isValid) {
            setSmsGlobalError('');
        } else if (result.phoneError || result.messageError) {
            setSmsGlobalError(result.phoneError || result.messageError);
        } else {
            setSmsGlobalError('');
        }
    };

    if (refs.phoneInput) {
        refs.phoneInput.addEventListener('input', onInput);
        refs.phoneInput.addEventListener('change', onInput);
    }
    if (refs.phoneEntryInput && refs.phoneEntryInput.dataset.rcSmsEntryBound !== '1') {
        refs.phoneEntryInput.dataset.rcSmsEntryBound = '1';
        refs.phoneEntryInput.addEventListener('keydown', function (event) {
            if (!event) return;
            if (event.key === 'Enter' || event.key === ',') {
                event.preventDefault();
                const result = commitSmsEntryRecipients(refs);
                if (result.invalid.length) {
                    setSmsGlobalError((result.invalidDetails && result.invalidDetails[0] && result.invalidDetails[0].message) || 'Invalid phone number.');
                } else if (result.overLimit.length) {
                    setSmsGlobalError(`You can add up to ${RC_SMS_MAX_RECIPIENTS} recipients.`);
                } else {
                    setSmsGlobalError('');
                }
                onInput();
            }
        });
        refs.phoneEntryInput.addEventListener('blur', function () {
            commitSmsEntryRecipients(refs);
            onInput();
        });
        refs.phoneEntryInput.addEventListener('input', updateSmsPhoneClearButton);
    }
    refs.msgInput.addEventListener('input', onInput);
    refs.msgInput.addEventListener('change', onInput);
    if (refs.groupToggle) refs.groupToggle.addEventListener('change', onInput);
    if (refs.groupNameInput) refs.groupNameInput.addEventListener('input', onInput);

    try {
        if (window.jQuery && jQuery('#sendMessageModal').length) {
            jQuery('#sendMessageModal').on('shown.bs.modal', function () {
                syncSmsRecipientsFromLegacyField(false);
                onInput();
            });
            jQuery('#sendMessageModal').on('hidden.bs.modal', function () {
                setSmsGlobalError('');
                const refsNow = getSmsModalUiRefs();
                if (refsNow.msgInput) refsNow.msgInput.value = '';
                if (refsNow.groupToggle) refsNow.groupToggle.checked = false;
                if (refsNow.groupNameInput) refsNow.groupNameInput.value = '';
                if (typeof window.rcSmsModalClearRecipients === 'function') {
                    window.rcSmsModalClearRecipients();
                }
                if (typeof setSmsForwardDraft === 'function') {
                    setSmsForwardDraft(null);
                } else {
                    window._rc_smsForwardDraft = null;
                }
                onInput();
            });
        }
    } catch (_) { }

    const smsForm = document.getElementById('smsForm');
    if (smsForm && smsForm.dataset.rcSmsValidationBound !== '1') {
        smsForm.dataset.rcSmsValidationBound = '1';
        smsForm.addEventListener('change', function (event) {
            if (event && event.target && (event.target.id === 'smsPhone' || event.target.id === 'smsPhoneEntry')) {
                onInput();
            }
        });
    }

    window.rcSmsModalAddRecipients = function (values) {
        const list = Array.isArray(values) ? values : [values];
        const result = addSmsRecipients(list);
        validateSmsModalDraft();
        return result;
    };
    window.rcSmsModalSetRecipients = function (values) {
        clearSmsRecipients();
        let result = { added: [], duplicates: [], invalid: [], invalidDetails: [], overLimit: [] };
        if (Array.isArray(values)) {
            result = addSmsRecipients(values);
        } else if (values !== null && values !== undefined) {
            result = addSmsRecipients(parseSmsRecipientsInput(values));
        }
        validateSmsModalDraft();
        return result;
    };
    window.rcSmsModalClearRecipients = function () {
        clearSmsRecipients();
        validateSmsModalDraft();
    };

    wireSmsPhoneClearButton();
    renderSmsRecipientChips();
    onInput();
}

function readPendingDialRequest() {
    try {
        const raw = localStorage.getItem(RC_PENDING_DIAL_REQUEST_KEY);
        if (!raw) return null;
        const parsed = JSON.parse(raw);
        return (parsed && typeof parsed === 'object') ? parsed : null;
    } catch (e) {
        console.warn('Failed to read pending dial request:', e);
        return null;
    }
}

function readPendingMessageRequest() {
    try {
        const raw = localStorage.getItem(RC_PENDING_MESSAGE_REQUEST_KEY);
        if (!raw) return null;
        const parsed = JSON.parse(raw);
        return (parsed && typeof parsed === 'object') ? parsed : null;
    } catch (e) {
        console.warn('Failed to read pending message request:', e);
        return null;
    }
}

function pendingRequestNeedsVisiblePortal(pending) {
    if (!pending || !pending.visibleHandoffId) return false;

    try {
        if (document.hidden || document.visibilityState !== 'visible') return true;
        if (typeof document.hasFocus === 'function' && !document.hasFocus()) return true;
    } catch (_) {
        return true;
    }

    return false;
}

function markDialRequestProcessed(requestId) {
    if (!requestId) return;
    try {
        localStorage.setItem(RC_LAST_PROCESSED_DIAL_REQUEST_KEY, requestId);
    } catch (e) {
        console.warn('Failed to mark dial request as processed:', e);
    }
}

function markMessageRequestProcessed(requestId) {
    if (!requestId) return;
    try {
        localStorage.setItem(RC_LAST_PROCESSED_MESSAGE_REQUEST_KEY, requestId);
    } catch (e) {
        console.warn('Failed to mark message request as processed:', e);
    }
}

function isDialRequestProcessed(requestId) {
    if (!requestId) return true;
    try {
        return localStorage.getItem(RC_LAST_PROCESSED_DIAL_REQUEST_KEY) === requestId;
    } catch (_) {
        return false;
    }
}

function isMessageRequestProcessed(requestId) {
    if (!requestId) return true;
    try {
        return localStorage.getItem(RC_LAST_PROCESSED_MESSAGE_REQUEST_KEY) === requestId;
    } catch (_) {
        return false;
    }
}

function consumePendingDialRequest(requestId) {
    markDialRequestProcessed(requestId);

    try {
        const raw = localStorage.getItem(RC_PENDING_DIAL_REQUEST_KEY);
        if (!raw) return;
        const parsed = JSON.parse(raw);
        if (parsed && parsed.requestId === requestId) {
            localStorage.removeItem(RC_PENDING_DIAL_REQUEST_KEY);
        }
    } catch (_) {
        try {
            localStorage.removeItem(RC_PENDING_DIAL_REQUEST_KEY);
        } catch (__){ }
    }
}

function consumePendingMessageRequest(requestId) {
    markMessageRequestProcessed(requestId);

    try {
        const raw = localStorage.getItem(RC_PENDING_MESSAGE_REQUEST_KEY);
        if (!raw) return;
        const parsed = JSON.parse(raw);
        if (parsed && parsed.requestId === requestId) {
            localStorage.removeItem(RC_PENDING_MESSAGE_REQUEST_KEY);
        }
    } catch (_) {
        try {
            localStorage.removeItem(RC_PENDING_MESSAGE_REQUEST_KEY);
        } catch (__){ }
    }
}

function waitMs(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function openCallsKeypadTabForDialRequest() {
    try {
        const callsTabLink = document.querySelector('a[href="#tabCalls"]');
        const keypadTabLink = document.querySelector('a[href="#callKeypad"]');

        if (window.jQuery && typeof jQuery.fn.tab === 'function') {
            if (callsTabLink) {
                jQuery(callsTabLink).tab('show');
            }
            if (keypadTabLink) {
                jQuery(keypadTabLink).tab('show');
            }
            return;
        }

        if (callsTabLink) callsTabLink.click();
        if (keypadTabLink) keypadTabLink.click();
    } catch (e) {
        console.warn('Failed to activate Calls/Keypad tab for dial request:', e);
    }
}

function dispatchSmsFieldEvents(inputEl) {
    if (!inputEl) return;
    try { inputEl.dispatchEvent(new Event('input', { bubbles: true })); } catch (_) { }
    try { inputEl.dispatchEvent(new Event('change', { bubbles: true })); } catch (_) { }
}

function applySmsMessageHandoffNumber(phoneNumber, phase = 'handoff') {
    const smsNumber = (phoneNumber || '').toString().trim();
    if (!smsNumber) return false;

    const refs = getSmsModalUiRefs();
    if (refs.phoneInput) {
        refs.phoneInput.value = smsNumber;
        dispatchSmsFieldEvents(refs.phoneInput);
    }

    let result = null;
    if (typeof window.rcSmsModalSetRecipients === 'function') {
        result = window.rcSmsModalSetRecipients([smsNumber]);
    } else if (refs.phoneEntryInput) {
        refs.phoneEntryInput.value = smsNumber;
        dispatchSmsFieldEvents(refs.phoneEntryInput);
    }

    const recipients = getSmsModalRecipients();
    if (!recipients.length && refs.phoneEntryInput) {
        // Preserve invalid/non-SMS-capable handoff numbers visibly instead of dropping them.
        refs.phoneEntryInput.value = smsNumber;
        dispatchSmsFieldEvents(refs.phoneEntryInput);
    }

    let validation = null;
    if (typeof validateSmsModalDraft === 'function') {
        validation = validateSmsModalDraft();
    }

    if (validation && validation.phoneError) {
        setSmsGlobalError(validation.phoneError);
    }

    console.info('Applied SMS handoff number', {
        phase,
        phoneNumber: smsNumber,
        added: result && Array.isArray(result.added) ? result.added.length : getSmsModalRecipients().length,
        invalid: result && Array.isArray(result.invalid) ? result.invalid.length : 0,
        suggestion: validation && validation.phoneError ? validation.phoneError : ''
    });

    return true;
}

function openMessagesTabAndModalForRequest(phoneNumber, requestId = null) {
    try {
        const textTabLink = document.querySelector('a[href="#tabMessages"]');
        if (window.jQuery && typeof jQuery.fn.tab === 'function') {
            if (textTabLink) {
                jQuery(textTabLink).tab('show');
            }
        } else if (textTabLink) {
            textTabLink.click();
        }
    } catch (e) {
        console.warn('Failed to activate Messages tab for message request:', e);
    }

    try {
        applySmsMessageHandoffNumber(phoneNumber, 'before-modal');
    } catch (e) {
        console.warn('Failed to prefill smsPhone for message request:', e);
    }

    try {
        if (window.jQuery && jQuery('#sendMessageModal').length) {
            jQuery('#sendMessageModal').one('shown.bs.modal', function () {
                applySmsMessageHandoffNumber(phoneNumber, 'modal-shown');
            });
            jQuery('#sendMessageModal').modal('show');
        }
        [80, 250, 700].forEach((delay) => {
            setTimeout(() => {
                try { applySmsMessageHandoffNumber(phoneNumber, `retry-${delay}`); } catch (_) { }
            }, delay);
        });
    } catch (e) {
        console.warn('Failed to open sendMessageModal for message request:', e);
    }
}

function getSmsDraftDigits(value) {
    return (value || '').toString().replace(/\D/g, '');
}

function findSmsDraftThreadKeyForRecipient(recipientDigits) {
    const digits = getSmsDraftDigits(recipientDigits);
    if (!digits) return '';

    if (window._rc_messageThreadKeyByDigits && window._rc_messageThreadKeyByDigits[digits]) {
        return window._rc_messageThreadKeyByDigits[digits];
    }

    const threads = window._rc_messageThreads || {};
    const keys = Object.keys(threads);
    for (let i = 0; i < keys.length; i += 1) {
        const key = keys[i];
        if (!key || key.indexOf('grp:') === 0) continue;

        const keyParts = key.split('|').map(getSmsDraftDigits).filter(Boolean);
        if (keyParts.includes(digits)) return key;

        const messages = Array.isArray(threads[key]) ? threads[key] : [];
        for (let j = 0; j < messages.length; j += 1) {
            const message = messages[j] || {};
            const fromDigits = getSmsDraftDigits(message.from && (message.from.phoneNumber || message.from.number || message.from.phone));
            const toList = Array.isArray(message.to) ? message.to : [];
            const hasTo = toList.some((to) => getSmsDraftDigits(to && (to.phoneNumber || to.number || to.phone)) === digits);
            if (fromDigits === digits || hasTo) return key;
        }
    }

    return '';
}

function resolveSmsDraftThreadKey(recipients, fromNumber) {
    const recipientDigits = (recipients || []).map(getSmsDraftDigits).filter(Boolean);
    const fromDigits = getSmsDraftDigits(fromNumber);

    if (recipientDigits.length > 1) {
        const members = Array.from(new Set([fromDigits].concat(recipientDigits).filter(Boolean))).sort();
        return members.length ? ('grp:' + members.join('|')) : '';
    }

    const singleRecipient = recipientDigits[0] || '';
    if (!singleRecipient) return '';

    const existingKey = findSmsDraftThreadKeyForRecipient(singleRecipient);
    if (existingKey) return existingKey;

    return fromDigits
        ? [fromDigits, singleRecipient].sort().join('|')
        : singleRecipient;
}

function prepareSmsModalChatDraft({ fromNumber, recipients, message, forwardedAttachments }) {
    const cleanMessage = (message || '').toString();
    const normalizedRecipients = Array.isArray(recipients) ? recipients : [];
    const threadKey = resolveSmsDraftThreadKey(normalizedRecipients, fromNumber);
    if (!threadKey) return false;

    window._rc_messageThreads = window._rc_messageThreads || {};
    if (!Array.isArray(window._rc_messageThreads[threadKey])) {
        window._rc_messageThreads[threadKey] = [];
    }

    window._rc_messageThreadKeyByDigits = window._rc_messageThreadKeyByDigits || {};
    normalizedRecipients.forEach((recipient) => {
        const digits = getSmsDraftDigits(recipient);
        if (digits) window._rc_messageThreadKeyByDigits[digits] = threadKey;
    });

    try {
        const textTabLink = document.querySelector('a[href="#tabMessages"]');
        if (window.jQuery && typeof jQuery.fn.tab === 'function' && textTabLink) {
            jQuery(textTabLink).tab('show');
        } else if (textTabLink) {
            textTabLink.click();
        }
    } catch (_) { }

    if (typeof showChatFor === 'function') {
        showChatFor(threadKey, { forceScrollBottom: true, focusInput: true });
    }

    const chatView = document.getElementById('chatViewCard');
    const messagesList = document.getElementById('messagesList');
    if (messagesList) messagesList.classList.add('d-none');
    if (chatView) {
        chatView.classList.remove('d-none');
        chatView.setAttribute('data-current-user', threadKey.indexOf('grp:') === 0 ? threadKey : (normalizedRecipients[0] || threadKey));
    }

    window._rc_activeThreadKey = threadKey;
    window._rc_currentChatUser = threadKey.indexOf('grp:') === 0 ? threadKey : (normalizedRecipients[0] || threadKey);

    const smsPhoneEl = document.getElementById('smsPhone');
    if (smsPhoneEl) {
        smsPhoneEl.value = normalizedRecipients.join(', ');
        try { smsPhoneEl.dispatchEvent(new Event('input', { bubbles: true })); } catch (_) { }
    }

    const input = document.getElementById('chatMessageInputRC');
    if (!input) return false;

    input.value = cleanMessage;
    try { input.dispatchEvent(new Event('input', { bubbles: true })); } catch (_) { }
    try {
        input.style.height = 'auto';
        input.style.height = `${Math.min(input.scrollHeight, 220)}px`;
    } catch (_) { }
    input.focus();
    input.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

    window._rc_pendingForwardAttachments = (forwardedAttachments || []).filter(att => att && (att.path || att.uri));
    if (typeof renderForwardAttachmentPreview === 'function') {
        renderForwardAttachmentPreview();
    }

    return true;
}

async function processPendingDialRequest(lastDialedKey, triggerSource = 'load') {
    if (rcPendingDialRequestInFlight) {
        return rcPendingDialRequestInFlight;
    }

    rcPendingDialRequestInFlight = (async () => {
        const pending = readPendingDialRequest();
        if (!pending || !pending.requestId) return;
        if (pendingRequestNeedsVisiblePortal(pending)) {
            console.info('Skipping visible dial handoff while RingCentral portal is hidden', {
                triggerSource,
                requestId: pending.requestId
            });
            return;
        }
        if (typeof window.rcPortalIsActiveInstance === 'function' && !window.rcPortalIsActiveInstance()) {
            console.info('Skipping pending dial request on inactive RingCentral portal instance', {
                triggerSource,
                requestId: pending.requestId
            });
            return;
        }
        if (isDialRequestProcessed(pending.requestId)) return;

        const dialNumber = (pending.number || '').toString().trim();
        if (!dialNumber) {
            consumePendingDialRequest(pending.requestId);
            return;
        }

        openCallsKeypadTabForDialRequest();

        try {
            if (typeof openDialerModal === 'function') {
                openDialerModal();
            }
        } catch (e) {
            console.warn('Failed to open dialer modal for pending request:', e);
        }

        const setDialerInput = () => {
            const input = document.getElementById('callPhone');
            if (input) input.value = dialNumber;
        };

        setDialerInput();
        try {
            localStorage.setItem(lastDialedKey, dialNumber);
        } catch (e) {
            console.warn('Failed to store last dialed number from pending request:', e);
        }

        if (!pending.autoCall) {
            consumePendingDialRequest(pending.requestId);
            return;
        }

        const maxWaitMs = 12000;
        const retryMs = 500;
        const startedAt = Date.now();
        let submitted = false;

        while ((Date.now() - startedAt) < maxWaitMs) {
            try {
                if (typeof openDialerModal === 'function') {
                    openDialerModal();
                }
            } catch (_) { }

            setDialerInput();

            const callForm = document.getElementById('callForm');
            const hasReadyWebPhone = !!(window.webPhone && window.webPhone.isInitialized);

            if (callForm && hasReadyWebPhone) {
                if (!window.webPhone.isRegistered && typeof window.webPhone.register === 'function') {
                    try {
                        await window.webPhone.register();
                    } catch (registerErr) {
                        console.warn('Pending dial request register attempt failed:', registerErr);
                    }
                }

                if (window.webPhone.isRegistered) {
                    callForm.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
                    submitted = true;
                    break;
                }
            }

            await waitMs(retryMs);
        }

        if (!submitted) {
            console.info('Pending dial request kept as prefill only; WebPhone not ready in time', {
                triggerSource,
                requestId: pending.requestId
            });
        }

        consumePendingDialRequest(pending.requestId);
    })();

    try {
        await rcPendingDialRequestInFlight;
    } finally {
        rcPendingDialRequestInFlight = null;
    }
}

async function processPendingMessageRequest(triggerSource = 'load') {
    if (rcPendingMessageRequestInFlight) {
        return rcPendingMessageRequestInFlight;
    }

    rcPendingMessageRequestInFlight = (async () => {
        const pending = readPendingMessageRequest();
        if (!pending || !pending.requestId) return;
        if (pendingRequestNeedsVisiblePortal(pending)) {
            console.info('Skipping visible message handoff while RingCentral portal is hidden', {
                triggerSource,
                requestId: pending.requestId
            });
            return;
        }
        if (typeof window.rcPortalIsActiveInstance === 'function' && !window.rcPortalIsActiveInstance()) {
            console.info('Skipping pending message request on inactive RingCentral portal instance', {
                triggerSource,
                requestId: pending.requestId
            });
            return;
        }
        if (isMessageRequestProcessed(pending.requestId)) return;

        const rawNumber = (pending.number || '').toString().trim();
        const digits = rawNumber.replace(/\D/g, '');
        const smsNumber = digits ? ('+' + digits) : rawNumber;

        if (!smsNumber) {
            consumePendingMessageRequest(pending.requestId);
            return;
        }

        openMessagesTabAndModalForRequest(smsNumber, pending.requestId);

        try {
            if (typeof loadMessageHistory === 'function') {
                loadMessageHistory(null, false, null, false);
            }
        } catch (_) { }

        consumePendingMessageRequest(pending.requestId);

        console.info('Processed pending message request', {
            triggerSource,
            requestId: pending.requestId
        });
    })();

    try {
        await rcPendingMessageRequestInFlight;
    } finally {
        rcPendingMessageRequestInFlight = null;
    }
}

// Load message history and populate leftSection messagesList grouped by recipient
function loadMessageHistory(page = null, append = false, beforeCursor = null, forceRefresh = false) {
    // This function is moved to R-Portal-messages.js
    // Kept as a stub to prevent errors if called from main script
    if (typeof _rc_loadMessageHistory_stub === 'function') {
        return _rc_loadMessageHistory_stub.apply(this, arguments);
    }
    return Promise.resolve();
}

// Load phone numbers and populate dropdowns
function loadPhoneNumbers() {
    const listEl = document.getElementById('phoneNumbersList');
    if (listEl) {
        listEl.innerHTML = '<p class="text-muted">Loading...</p>';
    }

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
            ? ((typeof maskPhoneNumber === 'function') ? maskPhoneNumber(number) : number)
            : '';

        return {
            number,
            label: number ? `${typeLabel} (${maskedNumber})` : typeLabel
        };
    };

    fetch(rcRoute('ringcentral.api.phone-numbers'), {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
        .then(r => r.json())
        .then(data => {
            let listHTML = '';
            let dropdownOptions = '';

            if (data.success && data.diagnostics) {
                // Use extension phone numbers
                let numbers = data.diagnostics.extension_phone_numbers || [];

                if (numbers.length) {
                    listHTML = '<ul class="list-group">';
                    numbers.forEach(numObj => {
                        const formatted = formatNumberLabel(numObj);
                        if (!formatted.number) return;
                        listHTML += `<li class="list-group-item">${formatted.label}</li>`;
                        dropdownOptions += `<option value="${formatted.number}">${formatted.label}</option>`;
                    });
                    listHTML += '</ul>';
                } else {
                    listHTML = '<p class="text-warning">No phone numbers found.</p>';
                    dropdownOptions = '<option value="">No numbers</option>';
                }

            } else {
                listHTML = '<p class="text-warning">No phone numbers found.</p>';
                dropdownOptions = '<option value="">No numbers</option>';
            }

            if (listEl) {
                listEl.innerHTML = listHTML;
            }

            // Fill dropdowns
            const callFromSelect = document.getElementById('callFromNumber');
            const smsFromSelect = document.getElementById('smsFromNumber');

            if (callFromSelect) {
                callFromSelect.innerHTML = dropdownOptions;
            }
            if (smsFromSelect) {
                smsFromSelect.innerHTML = dropdownOptions;
            }

        })
        .catch((err) => {
            if (listEl) {
                listEl.innerHTML =
                    '<p class="text-danger">Error loading phone numbers</p>';
            }
        });
}
function loadRcCoreData() {
    setSmsFromNumber();
}

document.addEventListener('DOMContentLoaded', async function () {
    const LAST_DIALED_KEY = 'rcLastDialedNumber';
    window.rcValidateSmsPhoneNumber = rcValidateSmsPhoneNumber;
    window.rcNormalizeSmsPhoneNumber = rcNormalizeSmsPhoneNumber;
    try {
        if (typeof rcStartWebhookEventStream === 'function') {
            rcStartWebhookEventStream();
        }
    } catch (e) {
        console.warn('rcStartWebhookEventStream failed', e);
    }

    // Populate call/SMS number dropdowns on page refresh when UI exists
    try {
        const callFromSelect = document.getElementById('callFromNumber');
        console.info('DOMContentLoaded: callFromNumber check', {
            exists: !!callFromSelect,
            optionCount: callFromSelect?.options?.length || 0
        });
        if (callFromSelect && callFromSelect.options.length === 0) {
            loadPhoneNumbers();
        }
    } catch (e) {
        console.warn('loadPhoneNumbers failed on load', e);
    }

    try {
        if (typeof setSmsFromNumber === 'function') {
            setSmsFromNumber();
        }
    } catch (e) {
        console.warn('setSmsFromNumber failed on load', e);
    }
    
    // Load message threads into left messages panel on page load:
    // keep initial load bounded by RC_INITIAL_PAGE_SIZE.
    try {
        loadMessageHistory(null, false, null, true, false)
            .catch(function (e) { console.warn('loadMessageHistory failed on load', e); });
    } catch (e) { console.warn('loadMessageHistory failed on load', e); }
    
    // SMS Form Handler
    const smsForm = document.getElementById('smsForm');
    wireSmsModalValidation();

    const resetSmsModalDraft = () => {
        const refs = getSmsModalUiRefs();
        if (refs.msgInput) refs.msgInput.value = '';
        if (refs.groupToggle) refs.groupToggle.checked = false;
        if (refs.groupNameInput) refs.groupNameInput.value = '';
        if (typeof window.rcSmsModalClearRecipients === 'function') {
            window.rcSmsModalClearRecipients();
        }
        if (typeof setSmsForwardDraft === 'function') {
            setSmsForwardDraft(null);
        } else {
            window._rc_smsForwardDraft = null;
        }
    };

    if (smsForm) {
        smsForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const forwardDraft = window._rc_smsForwardDraft || null;
            const validation = validateSmsModalDraft();
            const from = document.getElementById('smsFromNumber')?.value || '';
            const recipients = Array.isArray(validation.recipients) ? validation.recipients : [];
            const message = validation.message || '';
            const forwardedAttachments = forwardDraft && Array.isArray(forwardDraft.attachments)
                ? forwardDraft.attachments
                : [];

            if (!validation.isValid) {
                setSmsGlobalError(validation.phoneError || validation.messageError || '');
                return;
            }
            if (!from) {
                setSmsGlobalError('Unable to determine "Text from" number. Please reconnect RingCentral.');
                return;
            }

            setSmsGlobalError('');

            try {
                const prepared = prepareSmsModalChatDraft({
                    fromNumber: from,
                    recipients,
                    message,
                    forwardedAttachments
                });

                if (!prepared) {
                    setSmsGlobalError('Unable to open chat composer for this recipient.');
                    return;
                }

                if (typeof $ === 'function' && $('#sendMessageModal').length) {
                    $('#sendMessageModal').modal('hide');
                } else {
                    resetSmsModalDraft();
                }
            } catch (error) {
                setSmsGlobalError(error?.message || 'Failed to prepare message.');
            }
        });
    }

    // ===== CALL FORM HANDLER (Using WebPhone) =====
    const callForm = document.getElementById('callForm');
    const callPhoneInput = document.getElementById('callPhone');
    const makeCallBtn = document.getElementById('makeCallBtn');

    const rcShowDialerUnavailable = (message) => {
        const msg = message || 'R-Dialer is reconnecting. Please wait a few seconds and try again.';
        if (typeof showErrorModal === 'function') {
            showErrorModal(msg, false);
        } else {
            alert(msg);
        }
    };

    const rcGetDialGuard = () => {
        if (!window.webPhone) {
            return { ok: false, reason: 'WebPhone is initializing. Please wait...' };
        }
        if (!window.webPhone.isInitialized) {
            return { ok: false, reason: 'WebPhone is not initialized yet. Please wait a few seconds and try again.' };
        }
        if (window.webPhone.isRegistered === false) {
            return { ok: false, reason: 'WebPhone is connecting. Please wait a few seconds and try again.' };
        }
        if (window.webPhone && typeof window.webPhone.canMakeOutboundCall === 'function') {
            try {
                return window.webPhone.canMakeOutboundCall();
            } catch (_) {
                return { ok: false, reason: 'R-Dialer state is updating. Please try again in a few seconds.' };
            }
        }
        return { ok: true, reason: '' };
    };

    const rcApplyDialBlockToCallButton = (blocked) => {
        if (!makeCallBtn) return;
        const isCalling = makeCallBtn.textContent === 'Calling...';
        if (blocked) {
            if (!isCalling) {
                makeCallBtn.dataset.rcDialBlocked = '1';
                makeCallBtn.disabled = true;
                makeCallBtn.textContent = 'Reconnecting...';
            }
            return;
        }
        if (makeCallBtn.dataset.rcDialBlocked === '1') {
            const guard = rcGetDialGuard();
            if (!guard.ok) {
                makeCallBtn.disabled = true;
                makeCallBtn.textContent = 'Unavailable';
                makeCallBtn.title = guard.reason || 'WebPhone is initializing. Please wait...';
                return;
            }
            makeCallBtn.disabled = false;
            makeCallBtn.textContent = 'Call';
            makeCallBtn.title = '';
            delete makeCallBtn.dataset.rcDialBlocked;
        }
    };

    let rcCurrentMakeCallBarrier = '';

    const rcSetMakeCallBarrier = (reason) => {
        const msg = String(reason || 'Call is currently blocked.');
        const changed = rcCurrentMakeCallBarrier !== msg;
        rcCurrentMakeCallBarrier = msg;
        if (changed && window.rcLogLevel === 'debug') {
            console.debug('Call barrier:', msg);
        }
        if (!makeCallBtn) return;
        makeCallBtn.dataset.rcDialBlocked = '1';
        makeCallBtn.disabled = true;
        makeCallBtn.textContent = 'Unavailable';
        makeCallBtn.title = msg;
    };

    const rcRestoreMakeCallButtonIfReady = () => {
        if (!makeCallBtn) return;
        const guard = rcGetDialGuard();
        if (!guard.ok) {
            rcSetMakeCallBarrier(guard.reason);
            return;
        }
        makeCallBtn.disabled = false;
        makeCallBtn.textContent = 'Call';
        makeCallBtn.title = '';
        delete makeCallBtn.dataset.rcDialBlocked;
        rcCurrentMakeCallBarrier = '';
    };

    const rcNormalizeDialDigits = (value) => String(value || '').replace(/\D/g, '');

    // Keep call button unavailable until WebPhone finishes initialization/registration.
    try {
        const initialGuard = rcGetDialGuard();
        if (!initialGuard.ok) {
            rcSetMakeCallBarrier(initialGuard.reason);
        }
    } catch (_) { }

    const rcSessionIdCandidates = (sessionLike) => {
        if (!sessionLike) return [];
        const values = [
            sessionLike.id,
            sessionLike.sessionId,
            sessionLike.session_id,
            sessionLike.callId,
            sessionLike.call_id,
            sessionLike.partyId,
            sessionLike.party_id,
            sessionLike.callSessionId
        ]
            .filter(v => v !== null && typeof v !== 'undefined' && v !== '')
            .map(v => String(v));
        return Array.from(new Set(values));
    };

    const rcResolveSessionId = (session) => {
        if (!session) return null;
        return (
            session.id ||
            session.sessionId ||
            session.session_id ||
            session.partyId ||
            session.party_id ||
            session.callId ||
            session.call_id ||
            session.callSessionId ||
            session.index ||
            null
        );
    };

    const rcSessionLooksTerminated = (sessionLike) => {
        const state = String(sessionLike?.state || sessionLike?.status || '').toLowerCase();
        return ['terminated', 'disposed', 'ended', 'failed', 'rejected', 'cancelled', 'canceled'].includes(state);
    };

    const rcPickBestSessionCandidate = (sessions, originalSession, toNumber) => {
        if (!Array.isArray(sessions) || sessions.length === 0) return null;

        const targetDigits = rcNormalizeDialDigits(toNumber);
        const originalIds = rcSessionIdCandidates(originalSession);
        const originalDir = String(originalSession?.direction || '').toLowerCase();

        let best = null;
        let bestScore = -1;

        sessions.forEach((candidate) => {
            if (!candidate || rcSessionLooksTerminated(candidate)) return;
            let score = 0;

            const candidateIds = rcSessionIdCandidates(candidate);
            if (originalIds.length > 0 && candidateIds.some(id => originalIds.includes(id))) {
                score += 120;
            }

            const remoteDigits = rcNormalizeDialDigits(candidate?.remoteNumber || candidate?.toNumber || '');
            if (targetDigits && remoteDigits) {
                if (remoteDigits === targetDigits) score += 100;
                else if (remoteDigits.endsWith(targetDigits) || targetDigits.endsWith(remoteDigits)) score += 80;
            }

            const state = String(candidate?.state || candidate?.status || '').toLowerCase();
            if (['active', 'confirmed', 'connected', 'answered', 'established'].some(s => state.includes(s))) score += 40;
            else if (['trying', 'dialing', 'progress', 'proceeding', 'early', 'ringing'].some(s => state.includes(s))) score += 20;

            const dir = String(candidate?.direction || '').toLowerCase();
            if (dir.includes('outbound') || dir.includes('outgoing')) score += 20;
            else if (!dir && originalDir.includes('out')) score += 5;

            const started = Number(candidate?.startedAt || 0);
            if (!Number.isNaN(started) && started > 0) {
                score += Math.min(10, Math.floor((Date.now() - started) / 1000) < 30 ? 10 : 3);
            }

            if (score > bestScore) {
                bestScore = score;
                best = candidate;
            }
        });

        return best;
    };

    const rcResolveDialerSessionAfterMakeCall = async (session, toNumber, maxAttempts = 12, delayMs = 400) => {
        let sid = rcResolveSessionId(session);
        if (sid) return sid;

        const pause = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

        for (let i = 0; i < maxAttempts; i++) {
            try {
                if (window.webPhone && typeof window.webPhone.listSessions === 'function') {
                    const sessions = window.webPhone.listSessions() || [];
                    const candidate = rcPickBestSessionCandidate(sessions, session, toNumber);

                    sid = rcResolveSessionId(candidate);
                    if (sid) {
                        return sid;
                    }
                }
            } catch (_) { }

            await pause(delayMs);
        }

        return null;
    };

    if (makeCallBtn && callPhoneInput) {
        makeCallBtn.addEventListener('click', function (e) {
            try {
                if (!callPhoneInput.value) {
                    const lastDialed = localStorage.getItem(LAST_DIALED_KEY) || '';
                    if (lastDialed) {
                        callPhoneInput.value = lastDialed;
                        if (e) {
                            e.preventDefault();
                            e.stopPropagation();
                        }
                        return;
                    }
                }
            } catch (e) {
                console.warn('Failed to restore last dialed number:', e);
            }
        });
    }

    try {
        document.addEventListener('ringcentral:dialerStateChanged', function (e) {
            const blocked = !!(e && e.detail && e.detail.blocked);
            const reason = (e && e.detail && e.detail.reason) ? String(e.detail.reason) : 'Dialer is temporarily blocked.';
            if (blocked) {
                rcSetMakeCallBarrier(reason);
            }
            rcApplyDialBlockToCallButton(blocked);
        });
        setTimeout(() => {
            const guard = rcGetDialGuard();
            if (!guard.ok) {
                rcSetMakeCallBarrier(guard.reason);
            }
            rcApplyDialBlockToCallButton(!guard.ok);
        }, 120);
        document.addEventListener('ringcentral:webphoneReady', function () {
            try {
                const guard = rcGetDialGuard();
                if (!guard.ok) {
                    rcSetMakeCallBarrier(guard.reason);
                    rcApplyDialBlockToCallButton(true);
                    return;
                }
                rcRestoreMakeCallButtonIfReady();
            } catch (_) { }
        });
    } catch (_) { }

    if (callForm) {
        callForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            let from = document.getElementById('callFromNumber').value;
            let to = document.getElementById('callPhone').value;

            if (!to) {
                alert('Please enter a phone number');
                return;
            }

            const preSubmitDialCheck = rcGetDialGuard();
            if (!preSubmitDialCheck.ok) {
                rcSetMakeCallBarrier(preSubmitDialCheck.reason);
                rcShowDialerUnavailable(preSubmitDialCheck.reason);
                return;
            }

            if (window.rcBlockedNumbersApi && typeof window.rcBlockedNumbersApi.checkBlockedNumber === 'function') {
                try {
                    const blockCheck = await window.rcBlockedNumbersApi.checkBlockedNumber(to);
                    if (blockCheck && blockCheck.ok && blockCheck.data && blockCheck.data.blocked) {
                        rcShowDialerUnavailable('This number is blocked for dialer.');
                        rcRestoreMakeCallButtonIfReady();
                        return;
                    }
                } catch (_) {
                    // Ignore transient block-check failures and continue with normal dial guard.
                }
            }

            try {
                localStorage.setItem(LAST_DIALED_KEY, to);
            } catch (e) {
                console.warn('Failed to store last dialed number:', e);
            }

            let restoreMakeCallBtn = null;
            try {
                // Use WebPhone SDK for outbound calls
                if (!webPhone || !webPhone.isInitialized) {
                    rcSetMakeCallBarrier('WebPhone is not initialized yet. Please wait a few seconds and try again.');
                    alert('WebPhone is not initialized yet. Please wait a few seconds and try again.');
                    return;
                }

                // If client is initialized but not registered yet, attempt lazy registration.
                if (!webPhone.isRegistered && typeof webPhone.register === 'function') {
                    try {
                        await webPhone.register();
                    } catch (registerErr) {
                        console.warn('Lazy WebPhone registration failed:', registerErr);
                    }
                }

                if (!webPhone.isRegistered) {
                    rcSetMakeCallBarrier('WebPhone is connected but not registered yet. Please refresh the page or reconnect RingCentral.');
                    alert('WebPhone is connected but not registered yet. Please refresh the page or reconnect RingCentral.');
                    return;
                }

                const preCallDialCheck = rcGetDialGuard();
                if (!preCallDialCheck.ok) {
                    rcSetMakeCallBarrier(preCallDialCheck.reason);
                    rcShowDialerUnavailable(preCallDialCheck.reason);
                    return;
                }

                restoreMakeCallBtn = window.rcSetActionButtonLoading
                    ? window.rcSetActionButtonLoading(makeCallBtn, { loadingText: 'Calling...', statusText: 'Starting call...' })
                    : function () {
                        if (makeCallBtn) {
                            makeCallBtn.disabled = false;
                            makeCallBtn.textContent = 'Call';
                        }
                    };

                console.log('Making outbound call with WebPhone SDK...');
                const session = await webPhone.makeCall(to, from);
                console.log('WebPhone call session:', session);

                // Attach session-level UI listeners for outgoing session
                try { if (typeof attachSessionUiListeners === 'function') attachSessionUiListeners(session); } catch (_) { }

                // Resolve a usable session id from multiple possible properties
                try {
                    const sid = await rcResolveDialerSessionAfterMakeCall(session, to);
                    if (sid) {
                        // ensure global tracking
                        window._dialerCurrentSessionId = sid;
                        try { window._lastActiveSessions = (window._lastActiveSessions || []).concat([session]); } catch (_) { }
                        console.log('Outbound call sid resolved:', sid);
                        if (typeof switchDialerToDuringCall === 'function') switchDialerToDuringCall(sid);
                    } else {
                        console.warn('Could not resolve session id from makeCall/listSessions response; relying on event-based tracking');
                        if (typeof showErrorModal === 'function') {
                            showErrorModal('Call started but SDK did not return a stable session id. Tracking will continue automatically.', false);
                        }
                    }
                } catch (err) {
                    console.warn('Post-call setup failed:', err);
                }
                // Do NOT clear the input - keep it for transfer/add scenarios

                // Re-enable button after call starts (allow multiple calls)
                setTimeout(() => {
                    if (makeCallBtn) {
                        const dialGuardNow = rcGetDialGuard();
                        if (dialGuardNow.ok) {
                            restoreMakeCallBtn();
                            delete makeCallBtn.dataset.rcDialBlocked;
                        } else {
                            restoreMakeCallBtn({ disabled: true });
                            rcSetMakeCallBarrier(dialGuardNow.reason);
                            rcApplyDialBlockToCallButton(true);
                        }
                    }
                }, 2000);
            } catch (error) {
                console.error('Call error:', error);
                const errMsg = String(error?.message || error || 'Call failed');
                if (typeof restoreMakeCallBtn === 'function') {
                    restoreMakeCallBtn();
                }
                rcSetMakeCallBarrier(errMsg);
                if (/WebSocket is already in CLOSING or CLOSED state/i.test(errMsg)
                    || /expected JSON but received non-JSON response/i.test(errMsg)
                    || /Unexpected token </i.test(errMsg)) {
                    rcShowDialerUnavailable('R-Dialer is reconnecting after token refresh. Please wait a few seconds before dialing.');
                } else if (/Microphone requires HTTPS/i.test(errMsg)) {
                    rcShowDialerUnavailable(errMsg);
                } else {
                    alert('Call failed: ' + errMsg);
                }
            }
        });
    }

    try {
        window.addEventListener('storage', function (event) {
            if (event.key !== RC_PENDING_DIAL_REQUEST_KEY || !event.newValue) return;
            processPendingDialRequest(LAST_DIALED_KEY, 'storage-event')
                .catch((err) => console.warn('Failed to process pending dial request from storage event:', err));
        });
    } catch (e) {
        console.warn('Failed to bind pending dial storage listener:', e);
    }

    try {
        window.addEventListener('storage', function (event) {
            if (event.key !== RC_PENDING_MESSAGE_REQUEST_KEY || !event.newValue) return;
            processPendingMessageRequest('storage-event')
                .catch((err) => console.warn('Failed to process pending message request from storage event:', err));
        });
    } catch (e) {
        console.warn('Failed to bind pending message storage listener:', e);
    }

    try {
        if ('BroadcastChannel' in window) {
            const rcPortalBridgeChannel = new BroadcastChannel('rcPortalBridge');
            rcPortalBridgeChannel.addEventListener('message', function (event) {
                const data = event && event.data ? event.data : {};

                if (data.type === 'dial-request') {
                    processPendingDialRequest(LAST_DIALED_KEY, 'broadcast-channel')
                        .catch((err) => console.warn('Failed to process pending dial request from broadcast channel:', err));
                }

                if (data.type === 'message-request') {
                    processPendingMessageRequest('broadcast-channel')
                        .catch((err) => console.warn('Failed to process pending message request from broadcast channel:', err));
                }

                if (data.type === 'rc-visible-handoff' && data.visibleHandoffId) {
                    const targetInstanceId = data.targetPortalInstanceId || data.targetInstanceId || null;
                    if (targetInstanceId && window.RC_PORTAL_INSTANCE_ID && targetInstanceId !== window.RC_PORTAL_INSTANCE_ID) {
                        return;
                    }

                    try {
                        sessionStorage.setItem(RC_VISIBLE_HANDOFF_SESSION_KEY, data.visibleHandoffId);
                    } catch (_) { }

                    processPendingDialRequest(LAST_DIALED_KEY, 'visible-handoff-broadcast')
                        .catch((err) => console.warn('Failed to process pending dial request from visible handoff broadcast:', err));
                    processPendingMessageRequest('visible-handoff-broadcast')
                        .catch((err) => console.warn('Failed to process pending message request from visible handoff broadcast:', err));
                }
            });
        }
    } catch (e) {
        console.warn('Failed to bind RingCentral portal bridge channel:', e);
    }

    try {
        window.addEventListener('message', function (event) {
            if (event.origin !== window.location.origin) return;

            const data = event && event.data ? event.data : {};
            if (!data || data.type !== 'rc-visible-handoff' || !data.visibleHandoffId) return;

            try {
                sessionStorage.setItem(RC_VISIBLE_HANDOFF_SESSION_KEY, data.visibleHandoffId);
            } catch (_) { }

            processPendingDialRequest(LAST_DIALED_KEY, 'visible-handoff-message')
                .catch((err) => console.warn('Failed to process pending dial request from visible handoff message:', err));
            processPendingMessageRequest('visible-handoff-message')
                .catch((err) => console.warn('Failed to process pending message request from visible handoff message:', err));
        });
    } catch (e) {
        console.warn('Failed to bind RingCentral visible handoff message listener:', e);
    }

    try {
        document.addEventListener('visibilitychange', function () {
            if (document.hidden) return;

            processPendingDialRequest(LAST_DIALED_KEY, 'visibility-change')
                .catch((err) => console.warn('Failed to process pending dial request after portal became visible:', err));
            processPendingMessageRequest('visibility-change')
                .catch((err) => console.warn('Failed to process pending message request after portal became visible:', err));
        });
    } catch (e) {
        console.warn('Failed to bind RingCentral portal visibility handoff listener:', e);
    }

    try {
        window.addEventListener('focus', function () {
            processPendingDialRequest(LAST_DIALED_KEY, 'window-focus')
                .catch((err) => console.warn('Failed to process pending dial request after portal focus:', err));
            processPendingMessageRequest('window-focus')
                .catch((err) => console.warn('Failed to process pending message request after portal focus:', err));
        });
    } catch (e) {
        console.warn('Failed to bind RingCentral portal focus handoff listener:', e);
    }

    setTimeout(() => {
        processPendingDialRequest(LAST_DIALED_KEY, 'page-load')
            .catch((err) => console.warn('Failed to process pending dial request on page load:', err));
    }, 100);

    setTimeout(() => {
        processPendingMessageRequest('page-load')
            .catch((err) => console.warn('Failed to process pending message request on page load:', err));
    }, 120);

    // Restore call state notice after refresh (cannot restore media session)
    try {
        const CALL_STATE_KEY = 'rcCurrentCall';
        const saved = localStorage.getItem(CALL_STATE_KEY);
        if (saved) {
            const state = JSON.parse(saved);
            if (state.startedAt) {
                // Show error modal instead of legacy callStatus element
                if (typeof showErrorModal === 'function') showErrorModal('Previous call was disconnected due to page reload.', false);
            }
            // Clear any stale state – call cannot actually be resumed
            localStorage.removeItem(CALL_STATE_KEY);
        }
    } catch (_) { }
});

function formatDuration(startMs) {
    if (!startMs) return '';
    const diff = Math.floor((Date.now() - startMs) / 1000);
    const hh = String(Math.floor(diff / 3600)).padStart(2, '0');
    const mm = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
    const ss = String(diff % 60).padStart(2, '0');
    return `${hh}:${mm}:${ss}`;
}
