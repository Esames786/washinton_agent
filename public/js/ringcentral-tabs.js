/**
 * R-Dialer  Tab Filters and Search
 * Handles tab filtering, search, refresh functionality, and tab activation
 */

function refreshMessagesTab() {
    try {
        if (typeof loadMessageHistory === 'function') {
            return loadMessageHistory(null, false, null, true);
        }
    } catch (e) {}
    return Promise.resolve();
}

function refreshCallsTab() {
    try {
        if (typeof loadCallHistory === 'function') {
            return loadCallHistory(null, false, null, true);
        }
    } catch (e) {}
    return Promise.resolve();
}

function refreshCallsWorkspaceBundle(forceRefresh = true) {
    const refresh = !!forceRefresh;
    const tasks = [];

    try {
        if (typeof loadCallHistory === 'function') {
            tasks.push(Promise.resolve(loadCallHistory(null, false, null, refresh)));
        }
    } catch (_) { }

    try {
        if (typeof loadVoicemails === 'function') {
            tasks.push(Promise.resolve(loadVoicemails(refresh, false, null, true, 'tab_calls_bundle')));
        }
    } catch (_) { }

    try {
        if (typeof loadCallRecordings === 'function') {
            tasks.push(Promise.resolve(loadCallRecordings(refresh, false, null, true, 'tab_calls_bundle')));
        }
    } catch (_) { }

    if (!tasks.length) return Promise.resolve();
    return Promise.allSettled(tasks);
}

function handleCallsTabShown() {
    return refreshCallsWorkspaceBundle(true)
        .then(() => {
            if (typeof refreshCallsSummary === 'function') {
                return refreshCallsSummary();
            }
        })
        .catch(e => { });
}

function handleKeypadTabShown() {
    if (typeof markMissedCallsSeen !== 'function') {
        return Promise.resolve();
    }
    return markMissedCallsSeen()
        .then(() => {
            if (typeof refreshCallsSummary === 'function') {
                return refreshCallsSummary();
            }
        })
        .catch(e => { });
}

document.addEventListener('DOMContentLoaded', function () {
    // Move chat card between desktop/mobile slots and keep messages panel visible on Text tab
    (function setupChatCardLayout() {
        const chatCard = document.getElementById('chatViewCard');
        const mobileSlot = document.getElementById('chatMobileSlot');
        const desktopSlot = document.getElementById('chatDesktopSlot');
        const callsTab = document.querySelector('[href="#tabCalls"]');
        const textTab = document.querySelector('[href="#tabMessages"]');
        const keypadTab = document.querySelector('[href="#callKeypad"]');
        const messagesPanel = document.getElementById('messagesPanel');
        const messagesList = document.getElementById('messagesList');
        const backBtn = document.getElementById('backToList');

        function moveChatCardByScreen() {
            if (!chatCard) return;
            if (window.innerWidth >= 992) {
                if (desktopSlot && chatCard.parentNode !== desktopSlot) {
                    desktopSlot.appendChild(chatCard);
                }
            } else {
                if (mobileSlot && chatCard.parentNode !== mobileSlot) {
                    mobileSlot.appendChild(chatCard);
                }
            }
        }

        function forceShowMessagesPanel() {
            if (typeof window.rcHideCallsDetailPanel === 'function') {
                window.rcHideCallsDetailPanel();
            }

            if (messagesPanel) {
                messagesPanel.classList.remove('d-none');
                messagesPanel.removeAttribute('hidden');
                messagesPanel.style.display = '';
                messagesPanel.style.visibility = '';
                messagesPanel.style.opacity = '';
            }

            if (desktopSlot) {
                if (window.innerWidth >= 992) {
                    desktopSlot.style.display = '';
                } else {
                    desktopSlot.style.display = 'none';
                }
            }

            if (messagesList) {
                messagesList.classList.remove('d-none');
                messagesList.removeAttribute('hidden');
                messagesList.style.display = '';
                messagesList.style.visibility = '';
                messagesList.style.opacity = '';
            }
        }

        function hideMessagesPanel() {
            if (messagesPanel) {
                messagesPanel.style.display = 'none';
            }
            if (desktopSlot) {
                desktopSlot.style.display = 'none';
            }
        }

        if (backBtn) {
            backBtn.addEventListener('click', function () {
                if (chatCard) {
                    chatCard.classList.add('d-none');
                    chatCard.style.display = '';
                    chatCard.style.visibility = '';
                    chatCard.style.opacity = '';
                }
                forceShowMessagesPanel();
            });
        }

        moveChatCardByScreen();
        window.addEventListener('resize', moveChatCardByScreen);

        if (window.jQuery) {
            if (textTab) {
                jQuery(textTab).on('shown.bs.tab', function () {
                    forceShowMessagesPanel();
                    moveChatCardByScreen();
                });
            }
            if (callsTab) {
                jQuery(callsTab).on('shown.bs.tab', function () {
                    hideMessagesPanel();
                    handleCallsTabShown();
                });
            }
            if (keypadTab) {
                jQuery(keypadTab).on('shown.bs.tab', function () {
                    handleKeypadTabShown();
                });
            }
        } else {
            if (textTab) {
                textTab.addEventListener('click', function () {
                    setTimeout(() => {
                        forceShowMessagesPanel();
                        moveChatCardByScreen();
                    }, 100);
                });
            }
            if (callsTab) {
                callsTab.addEventListener('click', function () {
                    setTimeout(hideMessagesPanel, 100);
                    setTimeout(handleCallsTabShown, 120);
                });
            }
            if (keypadTab) {
                keypadTab.addEventListener('click', function () {
                    setTimeout(handleKeypadTabShown, 120);
                });
            }
        }
    })();

    // Toggle messages panel visibility when switching between Calls/Text tabs
    (function setupMessagesPanelToggle() {
        const callsTab = document.querySelector('[href="#tabCalls"]');
        const textTab = document.querySelector('[href="#tabMessages"]');
        const messagesPanel = document.getElementById('messagesPanel');

        function forceHidePanel() {
            if (!messagesPanel) return;
            messagesPanel.style.setProperty('display', 'none', 'important');
            messagesPanel.style.setProperty('visibility', 'hidden', 'important');
            messagesPanel.style.setProperty('opacity', '0', 'important');
            messagesPanel.classList.add('force-hidden');
        }

        function forceShowPanel() {
            if (!messagesPanel) return;
            messagesPanel.style.removeProperty('display');
            messagesPanel.style.removeProperty('visibility');
            messagesPanel.style.removeProperty('opacity');
            messagesPanel.classList.remove('force-hidden');
        }

        if (window.jQuery) {
            if (callsTab) {
                jQuery(callsTab).on('shown.bs.tab', function () {
                    forceHidePanel();
                });
            }
            if (textTab) {
                jQuery(textTab).on('shown.bs.tab', function () {
                    forceShowPanel();
                });
            }
        } else {
            if (callsTab) {
                callsTab.addEventListener('click', function () {
                    setTimeout(forceHidePanel, 100);
                });
            }
            if (textTab) {
                textTab.addEventListener('click', function () {
                    setTimeout(forceShowPanel, 100);
                });
            }
        }
    })();

    function setupFilter(inputId, containerId) {
        const input = document.getElementById(inputId);
        const container = document.getElementById(containerId);
        if (!input || !container) return;
        input.addEventListener('input', function () {
            const q = (this.value || '').trim().toLowerCase();
            Array.from(container.children).forEach(child => {
                const target = (child.getAttribute('data-search') || child.textContent || '').toLowerCase();
                if (!q || target.indexOf(q) !== -1) {
                    child.style.display = '';
                } else {
                    child.style.display = 'none';
                }
            });
        });
    }

    // Attach filters
    setupFilter('tabCallsSearch', 'tabCallsList');
    setupFilter('tabVoicemailsSearch', 'tabVoicemailsList');
    setupFilter('tabRecordingsSearch', 'tabRecordingsList');
    setupFilter('tabMessagesSearch', 'messagesList');

    // Debounced server-side search for messages and calls
    function debounce(fn, delay) {
        let t = null;
        return function (...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    const msgSearchEl = document.getElementById('tabMessagesSearch');
    if (msgSearchEl) {
        msgSearchEl.addEventListener('input', debounce(function () {
            try { loadMessageHistory(null, false); } catch (e) {}
        }, 300));
    }

    const callsSearchEl = document.getElementById('tabCallsSearch');
    if (callsSearchEl) {
        callsSearchEl.addEventListener('input', debounce(function () {
            try { loadCallHistory(null, false); } catch (e) {}
        }, 300));
    }

    // Use Bootstrap tab shown event if available, otherwise fallback to click
    try {
        if (window.jQuery) {
            jQuery('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                const target = e.target.getAttribute('href');
                try { window.rcSyncMessagesLoadingIndicator && window.rcSyncMessagesLoadingIndicator(); } catch (_) { }
                if (target === '#callKeypad' || target === '#callCalls') { try { window.rcHideCallsDetailPanel && window.rcHideCallsDetailPanel(); } catch (_) { } }
            });
        } else {
            Array.from(document.querySelectorAll('a[data-toggle="tab"]')).forEach(a => {
                a.addEventListener('click', function () {
                    const target = a.getAttribute('href');
                    setTimeout(() => {
                        try { window.rcSyncMessagesLoadingIndicator && window.rcSyncMessagesLoadingIndicator(); } catch (_) { }
                        if (target === '#callKeypad' || target === '#callCalls') { try { window.rcHideCallsDetailPanel && window.rcHideCallsDetailPanel(); } catch (_) { } }
                    }, 100);
                });
            });
        }
    } catch (e) {}

});


