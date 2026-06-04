/* R-Dialer dialer blocked numbers helper */
(function () {
    function ensureConfirmModal() {
        let modal = document.getElementById('rcActionConfirmModal');
        if (modal) return modal;

        const wrapper = document.createElement('div');
        wrapper.innerHTML = `
            <div class="modal fade" id="rcActionConfirmModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="rcActionConfirmTitle">Confirm Action</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="rcActionConfirmBody"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="rcActionConfirmOk">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>`;
        document.body.appendChild(wrapper.firstElementChild);
        modal = document.getElementById('rcActionConfirmModal');
        return modal;
    }

    function confirmWithModal(message, options = {}) {
        const title = String(options.title || 'Confirm Action');
        const confirmText = String(options.confirmText || 'Confirm');
        const confirmClass = String(options.confirmClass || 'btn-danger');

        if (!window.jQuery || typeof jQuery.fn.modal !== 'function') {
            return Promise.resolve(window.confirm(message));
        }

        return new Promise(function (resolve) {
            const modal = ensureConfirmModal();
            const titleEl = modal.querySelector('#rcActionConfirmTitle');
            const bodyEl = modal.querySelector('#rcActionConfirmBody');
            const okBtn = modal.querySelector('#rcActionConfirmOk');

            if (titleEl) titleEl.textContent = title;
            if (bodyEl) bodyEl.textContent = String(message || '');
            if (okBtn) {
                okBtn.textContent = confirmText;
                okBtn.className = 'btn ' + confirmClass;
            }

            let settled = false;
            const cleanup = function () {
                jQuery(modal).off('hidden.bs.modal', onHidden);
                if (okBtn) okBtn.removeEventListener('click', onConfirm);
            };
            const onHidden = function () {
                if (!settled) resolve(false);
                cleanup();
            };
            const onConfirm = function () {
                settled = true;
                resolve(true);
                jQuery(modal).modal('hide');
            };

            jQuery(modal).on('hidden.bs.modal', onHidden);
            if (okBtn) okBtn.addEventListener('click', onConfirm, { once: true });
            jQuery(modal).modal('show');
        });
    }

    function csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function route(name, params = {}, query = null) {
        if (typeof rcRoute === 'function') return rcRoute(name, params, query);
        const base = (window.RC_ROUTES && window.RC_ROUTES[name]) ? window.RC_ROUTES[name] : '';
        let url = base;
        Object.keys(params || {}).forEach(function (key) {
            url = url.replace(':' + key, encodeURIComponent(params[key]));
        });
        if (query && typeof query === 'object') {
            const qs = new URLSearchParams(query).toString();
            if (qs) url += (url.indexOf('?') >= 0 ? '&' : '?') + qs;
        }
        return url;
    }

    async function parseJson(res) {
        const txt = await res.text();
        try { return txt ? JSON.parse(txt) : null; } catch (_) { return null; }
    }

    async function listBlockedNumbers(search) {
        const res = await fetch(route('ringcentral.api.blocked-numbers.list', {}, search ? { q: search } : {}), {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await parseJson(res);
        return { ok: res.ok && data && data.success, data };
    }

    async function getBlockedNumberSettings() {
        const res = await fetch(route('ringcentral.api.blocked-numbers.settings.get'), {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await parseJson(res);
        return { ok: res.ok && data && data.success, data };
    }

    async function updateBlockedNumberSettings(localBlockEnabled) {
        const res = await fetch(route('ringcentral.api.blocked-numbers.settings.update'), {
            method: 'PATCH',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken()
            },
            body: JSON.stringify({ local_block_enabled: !!localBlockEnabled })
        });
        const data = await parseJson(res);
        return { ok: res.ok && data && data.success, data };
    }

    async function addBlockedNumber(number, reason) {
        const res = await fetch(route('ringcentral.api.blocked-numbers.add'), {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken()
            },
            body: JSON.stringify({ number, reason })
        });
        const data = await parseJson(res);
        return { ok: res.ok && data && data.success, data };
    }

    async function removeBlockedNumber(id) {
        const res = await fetch(route('ringcentral.api.blocked-numbers.remove', { id }), {
            method: 'DELETE',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken()
            }
        });
        const data = await parseJson(res);
        return { ok: res.ok && data && data.success, data };
    }

    async function removeBlockedNumberByNumber(number) {
        const res = await fetch(route('ringcentral.api.blocked-numbers.remove', { id: 0 }, { number: number }), {
            method: 'DELETE',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken()
            }
        });
        const data = await parseJson(res);
        return { ok: res.ok && data && data.success, data };
    }

    async function checkBlockedNumber(number) {
        const res = await fetch(route('ringcentral.api.blocked-numbers.check'), {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken()
            },
            body: JSON.stringify({ number })
        });
        const data = await parseJson(res);
        return { ok: res.ok && data && data.success, data };
    }

    async function isNumberBlocked(number) {
        const result = await checkBlockedNumber(number);
        if (!result.ok) return { ok: false, blocked: false, data: result.data };
        const blocked = !!(
            (result.data && result.data.blocked)
            || (result.data && result.data.data && result.data.data.blocked)
        );
        return { ok: true, blocked: blocked, data: result.data };
    }

    function renderBlockedList(items) {
        const listEl = document.getElementById('rcBlockedNumbersList');
        if (!listEl) return;
        const rows = Array.isArray(items) ? items : [];
        if (!rows.length) {
            listEl.innerHTML = '<div class="text-muted">No blocked numbers</div>';
            return;
        }
        listEl.innerHTML = rows.map(function (row) {
            const number = String(row.normalized_e164 || row.raw_input || '');
            const reason = String(row.reason || '');
            return (
                `<div class="list-group-item d-flex justify-content-between align-items-start">` +
                    `<div>` +
                        `<div class="fw-semibold">${escapeHtml(number)}</div>` +
                        (reason ? `<small class="text-muted">${escapeHtml(reason)}</small>` : '') +
                    `</div>` +
                    `<button type="button" class="btn btn-sm btn-outline-danger rc-unblock-number-btn" data-number="${escapeHtml(String(number))}">Unblock</button>` +
                `</div>`
            );
        }).join('');
    }

    function applySettingsToUi(settings) {
        const toggle = document.getElementById('rcLocalBlockToggle');
        if (!toggle) return;
        const supported = !(settings && settings.local_block_supported === false);
        toggle.checked = !!(settings && settings.local_block_enabled);
        toggle.disabled = !supported;
    }

    function escapeHtml(value) {
        return String(value || '').replace(/[&"'<>]/g, function (ch) {
            return ({ '&': '&amp;', '"': '&quot;', "'": '&#39;', '<': '&lt;', '>': '&gt;' })[ch];
        });
    }

    async function reloadBlockedList() {
        const searchEl = document.getElementById('rcBlockedNumbersSearch');
        const q = searchEl ? searchEl.value.trim() : '';
        const result = await listBlockedNumbers(q);
        if (!result.ok) {
            renderBlockedList([]);
            return;
        }
        applySettingsToUi(result.data.settings || {});
        renderBlockedList(result.data.data || []);
    }

    async function blockNumberFromContext(number, reason) {
        const digits = String(number || '').replace(/\D/g, '');
        if (!digits || digits.length < 10) {
            const msg = 'Unable to block: no valid phone number found for this record.';
            if (typeof showErrorModal === 'function') showErrorModal(msg, false);
            else alert(msg);
            return false;
        }
        const result = await addBlockedNumber(number, reason || '');
        if (!result.ok) {
            const msg = (result.data && result.data.message) ? result.data.message : 'Failed to block number.';
            if (typeof showErrorModal === 'function') showErrorModal(msg, false);
            else alert(msg);
            return false;
        }
        if (typeof window.loadCallHistory === 'function') window.loadCallHistory(null, false, null, false);
        if (typeof window.loadVoicemails === 'function') window.loadVoicemails(false, false, null, true, 'block');
        if (typeof window.loadCallRecordings === 'function') window.loadCallRecordings(false, false, null, true, 'block');
        if (typeof window.loadMessageHistory === 'function') window.loadMessageHistory(null, false, null, false);
        await reloadBlockedList();
        return true;
    }

    async function unblockNumberFromContext(number) {
        const digits = String(number || '').replace(/\D/g, '');
        if (!digits || digits.length < 10) {
            const msg = 'Unable to unblock: no valid phone number found for this record.';
            if (typeof showErrorModal === 'function') showErrorModal(msg, false);
            else alert(msg);
            return false;
        }
        const result = await removeBlockedNumberByNumber(number);
        if (!result.ok) {
            const msg = (result.data && result.data.message) ? result.data.message : 'Failed to unblock number.';
            if (typeof showErrorModal === 'function') showErrorModal(msg, false);
            else alert(msg);
            return false;
        }
        if (typeof window.loadCallHistory === 'function') window.loadCallHistory(null, false, null, false);
        if (typeof window.loadVoicemails === 'function') window.loadVoicemails(false, false, null, true, 'unblock');
        if (typeof window.loadCallRecordings === 'function') window.loadCallRecordings(false, false, null, true, 'unblock');
        if (typeof window.loadMessageHistory === 'function') window.loadMessageHistory(null, false, null, false);
        await reloadBlockedList();
        return true;
    }

    function applyBlockActionState(el, blocked) {
        if (!el) return;
        el.setAttribute('data-rc-blocked', blocked ? '1' : '0');
        el.setAttribute('title', blocked ? 'Unblock number' : 'Block number');
        const icon = '<i class="fa fa-ban"></i>';

        if (el.classList && el.classList.contains('rc-filter-dropdown-item')) {
            el.innerHTML = blocked ? `${icon} Unblock` : `${icon} Block`;
            return;
        }

        if (el.id === 'chatBlockBtnRC') {
            el.classList.toggle('btn-danger', !!blocked);
            el.classList.toggle('btn-outline-danger', !blocked);
            return;
        }
    }

    async function decorateBlockActionElement(el, number) {
        if (!el || !number) return;
        const state = await isNumberBlocked(number);
        if (!state.ok) return;
        applyBlockActionState(el, state.blocked);
    }

    async function toggleNumberFromContext(number, reason, contextLabel) {
        const state = await isNumberBlocked(number);
        if (state.ok && state.blocked) {
            const yes = await confirmWithModal('Unblock this number in dialer?', {
                title: 'Unblock Number',
                confirmText: 'Unblock',
                confirmClass: 'btn-warning'
            });
            if (!yes) return { ok: false, action: 'cancelled' };
            const ok = await unblockNumberFromContext(number);
            return { ok: ok, action: ok ? 'unblocked' : 'failed' };
        }
        const yes = await confirmWithModal('Block this number in dialer?', {
            title: 'Block Number',
            confirmText: 'Block',
            confirmClass: 'btn-danger'
        });
        if (!yes) return { ok: false, action: 'cancelled' };
        const ok = await blockNumberFromContext(number, reason || '');
        return { ok: ok, action: ok ? 'blocked' : 'failed' };
    }

    window.rcBlockedNumbersApi = {
        listBlockedNumbers,
        getBlockedNumberSettings,
        updateBlockedNumberSettings,
        addBlockedNumber,
        removeBlockedNumber,
        removeBlockedNumberByNumber,
        checkBlockedNumber,
        isNumberBlocked,
        reloadBlockedList,
        blockNumberFromContext,
        unblockNumberFromContext,
        toggleNumberFromContext,
        decorateBlockActionElement
    };

    document.addEventListener('DOMContentLoaded', function () {
        const openBtn = document.getElementById('rcBlockedNumbersBtn');
        const form = document.getElementById('rcBlockedNumbersForm');
        const input = document.getElementById('rcBlockedNumberInput');
        const reason = document.getElementById('rcBlockedNumberReason');
        const search = document.getElementById('rcBlockedNumbersSearch');
        const list = document.getElementById('rcBlockedNumbersList');
        const modal = document.getElementById('rcBlockedNumbersModal');
        const localToggle = document.getElementById('rcLocalBlockToggle');

        if (openBtn && modal && window.jQuery && typeof jQuery.fn.modal === 'function') {
            openBtn.addEventListener('click', function () {
                jQuery('#rcBlockedNumbersModal').modal('show');
                reloadBlockedList();
            });
        }

        if (form) {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                const num = input ? input.value.trim() : '';
                const rsn = reason ? reason.value.trim() : '';
                if (!num) return;
                const submitBtn = form.querySelector('button[type="submit"]');
                const restoreSubmitBtn = window.rcSetActionButtonLoading
                    ? window.rcSetActionButtonLoading(submitBtn, { loadingText: 'Blocking...', statusText: 'Blocking number...' })
                    : null;
                try {
                    const ok = await blockNumberFromContext(num, rsn);
                    if (ok && input) {
                        input.value = '';
                        if (reason) reason.value = '';
                    }
                } finally {
                    if (typeof restoreSubmitBtn === 'function') {
                        restoreSubmitBtn();
                    }
                }
            });
        }

        if (search) {
            let t = null;
            search.addEventListener('input', function () {
                clearTimeout(t);
                t = setTimeout(reloadBlockedList, 150);
            });
        }

        if (list) {
            list.addEventListener('click', async function (e) {
                const btn = e.target.closest('.rc-unblock-number-btn');
                if (!btn) return;
                const number = btn.getAttribute('data-number');
                if (!number) return;
                const restoreBtn = window.rcSetActionButtonLoading
                    ? window.rcSetActionButtonLoading(btn, { loadingText: 'Unblocking...', statusText: 'Unblocking number...' })
                    : null;
                try {
                    const result = await removeBlockedNumberByNumber(number);
                    if (!result.ok) {
                        const msg = (result.data && result.data.message) ? result.data.message : 'Failed to unblock number.';
                        if (typeof showErrorModal === 'function') showErrorModal(msg, false);
                        else alert(msg);
                        return;
                    }
                    await reloadBlockedList();
                    if (typeof window.loadCallHistory === 'function') window.loadCallHistory(null, false, null, false);
                    if (typeof window.loadVoicemails === 'function') window.loadVoicemails(false, false, null, true, 'unblock');
                    if (typeof window.loadCallRecordings === 'function') window.loadCallRecordings(false, false, null, true, 'unblock');
                    if (typeof window.loadMessageHistory === 'function') window.loadMessageHistory(null, false, null, false);
                } finally {
                    if (typeof restoreBtn === 'function') restoreBtn();
                }
            });
        }

        if (localToggle) {
            getBlockedNumberSettings().then(function (result) {
                if (!result.ok) return;
                applySettingsToUi(result.data.settings || {});
            }).catch(function () { });

            localToggle.addEventListener('change', async function () {
                const next = !!localToggle.checked;
                const result = await updateBlockedNumberSettings(next);
                if (!result.ok) {
                    const msg = (result.data && result.data.message) ? result.data.message : 'Failed to update settings.';
                    if (typeof showErrorModal === 'function') showErrorModal(msg, false);
                    else alert(msg);
                    localToggle.checked = !next;
                    return;
                }
                applySettingsToUi(result.data.settings || {});
            });
        }

        if (modal && window.jQuery && typeof jQuery.fn.modal === 'function') {
            jQuery('#rcBlockedNumbersModal').on('shown.bs.modal', reloadBlockedList);
        }
    });
})();
