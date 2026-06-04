/**
 * R-Dialer  Modal Helpers
 * Error and debug modal management functions
 */

// ===== ERROR MODAL HELPERS =====
function triggerDisconnectFromErrorModal() {
    const disconnectBtn = document.getElementById('rcDisconnectBtn');
    if (disconnectBtn && typeof disconnectBtn.click === 'function') {
        disconnectBtn.click();
        return Promise.resolve(true);
    }
    if (typeof window.logout === 'function') {
        return Promise.resolve(window.logout())
            .catch(() => false);
    }
    return Promise.resolve(false);
}

function triggerReconnectFromErrorModal() {
    const reconnectBtn = document.getElementById('reconnectBtn');
    if (reconnectBtn && typeof reconnectBtn.click === 'function') {
        reconnectBtn.click();
        return Promise.resolve(true);
    }
    if (typeof window.rcAttemptReconnect === 'function') {
        return Promise.resolve(window.rcAttemptReconnect())
            .catch(() => false);
    }
    return Promise.resolve(false);
}

function showErrorModal(message, needsReload = true, options = {}) {
    try {
        const friendlyMessage = (typeof window.rcGetFriendlyRingCentralErrorMessage === 'function')
            ? window.rcGetFriendlyRingCentralErrorMessage(message, 'An unexpected error occurred.')
            : (message || 'An unexpected error occurred.');
        const modalEl = document.getElementById('errorModal');
        const msgEl = document.getElementById('errorModalMessage');
        const hintEl = document.getElementById('errorModalHint');
        const reloadBtn = document.getElementById('errorModalReloadBtn');
        const modalFooter = modalEl ? modalEl.querySelector('.modal-footer') : null;
        const showReconnect = !!(options && options.showReconnect);
        const showDisconnect = !!(options && options.showDisconnect);

        let modalReconnectBtn = document.getElementById('errorModalReconnectBtn');
        if (!modalReconnectBtn && modalFooter) {
            modalReconnectBtn = document.createElement('button');
            modalReconnectBtn.type = 'button';
            modalReconnectBtn.className = 'btn btn-outline-primary';
            modalReconnectBtn.id = 'errorModalReconnectBtn';
            modalReconnectBtn.textContent = 'Reconnect';
            modalFooter.insertBefore(modalReconnectBtn, reloadBtn || null);
        }

        let modalDisconnectBtn = document.getElementById('errorModalDisconnectBtn');
        if (!modalDisconnectBtn && modalFooter) {
            modalDisconnectBtn = document.createElement('button');
            modalDisconnectBtn.type = 'button';
            modalDisconnectBtn.className = 'btn btn-danger';
            modalDisconnectBtn.id = 'errorModalDisconnectBtn';
            modalDisconnectBtn.textContent = 'Disconnect';
            modalFooter.insertBefore(modalDisconnectBtn, reloadBtn || null);
        }

        if (modalReconnectBtn) {
            modalReconnectBtn.style.display = showReconnect ? 'inline-block' : 'none';
            modalReconnectBtn.onclick = async function () {
                const originalText = modalReconnectBtn.textContent;
                modalReconnectBtn.disabled = true;
                modalReconnectBtn.textContent = 'Reconnecting...';
                try {
                    const ok = await triggerReconnectFromErrorModal();
                    if (!ok) {
                        modalReconnectBtn.textContent = 'Reconnect';
                    }
                } finally {
                    modalReconnectBtn.disabled = false;
                    if (modalReconnectBtn.textContent !== originalText && modalReconnectBtn.textContent !== 'Reconnect') {
                        modalReconnectBtn.textContent = originalText;
                    }
                }
            };
        }

        if (modalDisconnectBtn) {
            modalDisconnectBtn.style.display = showDisconnect ? 'inline-block' : 'none';
            modalDisconnectBtn.onclick = async function () {
                const originalText = modalDisconnectBtn.textContent;
                modalDisconnectBtn.disabled = true;
                modalDisconnectBtn.textContent = 'Disconnecting...';
                try {
                    const ok = await triggerDisconnectFromErrorModal();
                    if (!ok) {
                        modalDisconnectBtn.textContent = 'Disconnect';
                    }
                } finally {
                    modalDisconnectBtn.disabled = false;
                    if (modalDisconnectBtn.textContent !== originalText && modalDisconnectBtn.textContent !== 'Disconnect') {
                        modalDisconnectBtn.textContent = originalText;
                    }
                }
            };
        }

        if (msgEl) msgEl.textContent = friendlyMessage;
        if (hintEl) hintEl.style.display = needsReload ? 'block' : 'none';
        if (reloadBtn) reloadBtn.style.display = needsReload ? 'inline-block' : 'none';
        if (reloadBtn) reloadBtn.onclick = function () { try { location.reload(); } catch (_) { } };

        // Prefer Bootstrap/jQuery modal if available; fallback to vanilla
        if (window.$ && typeof $('#errorModal').modal === 'function') {
            $('#errorModal').modal('show');
        } else if (modalEl) {
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            modalEl.setAttribute('aria-modal', 'true');
            modalEl.removeAttribute('aria-hidden');
            // Close handler for the X button
            const closeBtn = modalEl.querySelector('.close');
            if (closeBtn) closeBtn.onclick = function () { hideErrorModal(); };
        }
    } catch (e) {
        console.error('Failed to show error modal:', e);
        const fallbackMessage = (typeof window.rcGetFriendlyRingCentralErrorMessage === 'function')
            ? window.rcGetFriendlyRingCentralErrorMessage(message, 'Unexpected error')
            : (message || 'Unexpected error');
        alert(fallbackMessage + (needsReload ? '\nPlease reload the page.' : ''));
    }
}

function hideErrorModal() {
    const modalEl = document.getElementById('errorModal');
    if (window.$ && typeof $('#errorModal').modal === 'function') {
        $('#errorModal').modal('hide');
    } else if (modalEl) {
        modalEl.classList.remove('show');
        modalEl.style.display = 'none';
        modalEl.removeAttribute('aria-modal');
        modalEl.setAttribute('aria-hidden', 'true');
    }
}
// ===== END: ERROR MODAL HELPERS =====

// ===== DEBUG MODAL HELPERS =====
function openDebugModal() {
    try {
        const modalEl = document.getElementById('debugModal');
        if (window.$ && typeof $('#debugModal').modal === 'function') {
            $('#debugModal').modal('show');
        } else if (modalEl) {
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            modalEl.setAttribute('aria-modal', 'true');
            modalEl.removeAttribute('aria-hidden');
        }
    } catch (e) { console.warn('openDebugModal failed', e); }
}

function closeDebugModal() {
    try {
        const modalEl = document.getElementById('debugModal');
        if (window.$ && typeof $('#debugModal').modal === 'function') {
            $('#debugModal').modal('hide');
        } else if (modalEl) {
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
            modalEl.removeAttribute('aria-modal');
            modalEl.setAttribute('aria-hidden', 'true');
        }
    } catch (e) { console.warn('closeDebugModal failed', e); }
}
// ===== END: DEBUG MODAL HELPERS =====
