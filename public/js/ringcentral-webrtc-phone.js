/**
 * R-Dialer  WebPhone Integration
 * Uses official R-Dialer  WebPhone SDK for browser-based calling
 * Based on: https://github.com/ringcentral/web-phone-demo
 */

(function initRcLogger() {
    if (window.rcLog) return;
    window.rcLogLevel = window.rcLogLevel || 'brief';
    window.rcLog = function () {
        // Logging disabled
    };
    window.rcInfo = function () {
        // Logging disabled
    };
})();

class RingCentralWebPhone {
    constructor(config = {}) {
        const resolvedApiBaseUrl = config.apiBaseUrl || rcRoute('ringcentral.api.base');
        const resolvedTokenTimerSyncUrl =
            config.webphoneTokenTimerUrl
            || rcRoute('ringcentral.api.webphone-token-timer')
            || `${resolvedApiBaseUrl}/webphone-token-timer`;
        this.config = {
            apiBaseUrl: resolvedApiBaseUrl,
            webphoneTokenTimerUrl: resolvedTokenTimerSyncUrl,
            ...config
        };

        // WebPhone instance
        this.webphone = null;
        this.userAgent = null;
        this.currentSession = null;
        this.callSessions = [];
        this._sessionListenersBound = new WeakSet();
        this._sessionRecoveryInterval = null;

        // Call state
        this.isInitialized = false;
        this.isRegistered = false;

        // Audio elements
        this.remoteAudio = null;
        this.localAudio = null;

        // Audio state
        this.micMuted = false;
        this.speakerEnabled = true;
        this.currentOutputDeviceId = 'default';

        // Recording state
        this.isRecording = false;
        this.isRecordingPaused = false;
        this.incomingCallSession = null;
        // Session metadata store (avoid writing onto SDK session objects which may have getter-only properties)
        this._sessionMeta = {};
        // Recording capabilities provided by server (filled during init)
        this.recordingCapabilities = null;

        // Instance limit (from backend subscription/package)
        // Set during init() when fetching token
        this.maxInstancesAllowed = 2; // Default to 2 (R-Dialer  max), will be updated from server

        // Internal: limit auto-retry on registration failures
        this._registerRetryCount = 0;
        // Token countdown UI state
        this._countdownTimer = null;
        this._countdownRemaining = null; // seconds
        this._countdownTotalSeconds = null;
        this._countdownProgressTotalSeconds = 60 * 60; // Progress bar is always based on 60 minutes
        this._countdownExpiresAtUnix = null;
        this._countdownElementId = 'rc-token-countdown';
        this._countdownProgressElementId = 'rc-token-progress-bar';
        this._countdownExpiredClass = 'rc-token-countdown-expired';
        this._tokenExpiresAtUnix = null;
        this._tokenTimerSyncInterval = null;
        this._tokenTimerSyncInFlight = null;
        this._tokenTimerSyncMs = 1800000;
        this._autoRefreshTimer = null;
        this._refreshIntervalSeconds = this._resolveRefreshIntervalSeconds(config.refreshIntervalSeconds);
        this._refreshIntervalLabel = this._formatRefreshDuration(this._refreshIntervalSeconds);
        this._refreshCountdownLabel = this._formatRefreshCountdown(this._refreshIntervalSeconds);
        this._fiveMinuteWarningTimer = null;
        this._postponeTimer = null;
        this._postponeUntil = null;
        this._warningModalOpen = false;
        this._warningAutoRefreshTimer = null;
        this._warningCountdownTimer = null;
        this._warningToastAutoCloseTimer = null;
        this._silentRefreshInFlight = null;
        this._isRefreshing = false;
        this._refreshStartedAt = 0;
        this._refreshStaleAfterMs = 45000;
        this._dialBlocked = false;
        this._dialBlockedReason = '';
        this._refreshFailureRequiresReconnect = false;
        this._refreshFailureReason = '';
        this._lastSilentRefreshToastAt = 0;
        this._countdownExpiryRefreshTriggered = false;
        // Ringtone state (Web Audio API)
        this._ringtone = null;
        this._ringtoneCtx = null;
        this._ringtoneInterval = null;
        this._localOutboundDialingUntil = 0;
        // If account/call-state consistently rejects bring-in (TAS-106/CMN-102), skip bring-in and use transfer fallback.
        this._bringInUnavailable = false;
    }

    _resolveRefreshIntervalSeconds(rawValue) {
        const globalValue = typeof window !== 'undefined' ? window.RC_WEBPHONE_REFRESH_SECONDS : null;
        const parsed = Number(rawValue || globalValue || (5 * 60));
        return Number.isFinite(parsed) && parsed > 0 ? Math.max(60, Math.floor(parsed)) : (5 * 60);
    }

    _formatRefreshDuration(seconds) {
        const totalSeconds = Math.max(1, Math.floor(Number(seconds) || 0));
        if (totalSeconds % 60 === 0) {
            const minutes = totalSeconds / 60;
            return `${minutes} ${minutes === 1 ? 'minute' : 'minutes'}`;
        }
        return `${totalSeconds} ${totalSeconds === 1 ? 'second' : 'seconds'}`;
    }

    _formatRefreshCountdown(seconds) {
        const totalSeconds = Math.max(0, Math.floor(Number(seconds) || 0));
        const mins = Math.floor(totalSeconds / 60);
        const secs = totalSeconds % 60;
        return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }

    /**
     * Get or create a unique instanceId for this BROWSER (not per tab)
     * All tabs in the same browser share ONE instanceId
     * Different browsers (Chrome, Firefox, etc.) or devices get different instanceIds
     *
     * According to R-Dialer  WebPhone SDK documentation:
     * - Limit: R-Dialer  allows max 5 unique instanceIds per extension
     * - By sharing ONE instanceId across all tabs in same browser, we use only 1 of the 5 available
     * - This allows multiple different browsers/devices to each have their own instance
     *
     * Strategy:
     * - localStorage is shared across all tabs in the same origin/domain
     * - localStorage is unique per browser (Chrome vs Firefox vs Safari vs Edge all have separate storage)
     * - So storing in localStorage makes all tabs use same browser ID
     *
     * @returns {string} Unique instanceId for this browser
     */
    getOrCreateInstanceId() {
        const STORAGE_KEY = 'rc_webphone_browser_instance_id';

        try {
            // Check if localStorage already has a browser instanceId
            // This will be shared across all tabs in THIS browser
            const storedId = localStorage.getItem(STORAGE_KEY);
            if (storedId) {
                rcLog('🔄 Reusing existing instanceId for this BROWSER:', storedId);
                return storedId;
            }

            // Generate new instanceId for this browser
            const instanceId = this.generateUUID();

            // Store in localStorage so ALL tabs in this browser share it
            // Different browsers have separate localStorage, so each browser gets unique ID
            localStorage.setItem(STORAGE_KEY, instanceId);

            rcLog('✨ Generated new instanceId for this BROWSER:', instanceId);
            return instanceId;

        } catch (e) {
            // Fallback: generate a temporary instanceId
            return this.generateUUID();
        }
    }

    /**
     * Generate a UUID v4 string
     * Format: xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx
     * @returns {string} UUID v4
     */
    generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (c) => {
            const r = (Math.random() * 16) | 0;
            const v = c === 'x' ? r : (r & 0x3) | 0x8;
            return v.toString(16);
        });
    }

    /**
     * Get or calculate which instance number this is (1st, 2nd, 3rd copy, etc.)
     * by checking how many other tabs have registered with R-Dialer 
     * @returns {Promise<number>} Instance number (1-5)
     */
    async getInstanceNumber() {
        try {
            // Store this instanceId in localStorage as part of active instances list
            const ACTIVE_INSTANCES_KEY = 'rc_active_instances';
            const INSTANCE_TIMESTAMP_KEY = 'rc_instance_timestamp';

            // Get current instanceId
            const currentInstanceId = this.getOrCreateInstanceId();

            // Get list of active instances from localStorage
            let activeInstances = [];
            try {
                const stored = localStorage.getItem(ACTIVE_INSTANCES_KEY);
                if (stored) {
                    activeInstances = JSON.parse(stored);
                }
            } catch (e) {
            }

            // Check which instances are still alive (timestamp within last 2 seconds)
            const now = Date.now();
            const TIMEOUT_MS = 2000; // 2 second timeout
            activeInstances = activeInstances.filter(inst => {
                const timestamp = localStorage.getItem(`${INSTANCE_TIMESTAMP_KEY}_${inst.id}`);
                if (!timestamp) return false;
                const age = now - parseInt(timestamp);
                return age < TIMEOUT_MS;
            });

            // Check if current instance is in the list
            const currentInst = activeInstances.find(inst => inst.id === currentInstanceId);
            if (!currentInst) {
                // Add current instance
                activeInstances.push({
                    id: currentInstanceId,
                    opened: new Date().toLocaleTimeString()
                });
            }

            // Sort by order they were opened
            activeInstances.sort((a, b) => {
                const aTime = localStorage.getItem(`${INSTANCE_TIMESTAMP_KEY}_${a.id}`) || '0';
                const bTime = localStorage.getItem(`${INSTANCE_TIMESTAMP_KEY}_${b.id}`) || '0';
                return parseInt(aTime) - parseInt(bTime);
            });

            // Save updated list
            try {
                localStorage.setItem(ACTIVE_INSTANCES_KEY, JSON.stringify(activeInstances));
                localStorage.setItem(`${INSTANCE_TIMESTAMP_KEY}_${currentInstanceId}`, now.toString());
            } catch (e) {
            }

            // Find position of current instance
            const instanceNumber = activeInstances.findIndex(inst => inst.id === currentInstanceId) + 1;

            rcLog(`📱 This is instance #${instanceNumber} of ${activeInstances.length} active tabs`);
            return {
                number: instanceNumber,
                total: activeInstances.length,
                allInstances: activeInstances
            };
        } catch (e) {
            return { number: 1, total: 1, allInstances: [] };
        }
    }

    /**
     * Display instance label on the UI
     */
    async updateInstanceLabel() {
        try {
            const instanceInfo = await this.getInstanceNumber();
            const instanceLabelEl = document.getElementById('instanceLabel');

            if (instanceLabelEl) {
                let labelText = '';

                if (instanceInfo.number === 1) {
                    labelText = '(Main Instance)';
                } else if (instanceInfo.number === 2) {
                    labelText = '(2nd Copy)';
                } else if (instanceInfo.number === 3) {
                    labelText = '(3rd Copy)';
                } else if (instanceInfo.number === 4) {
                    labelText = '(4th Copy)';
                } else if (instanceInfo.number === 5) {
                    labelText = '(5th Copy - Max)';
                } else {
                    labelText = `(Instance ${instanceInfo.number})`;
                }

                instanceLabelEl.textContent = labelText;
                instanceLabelEl.title = `Instance ID: ${this.getOrCreateInstanceId()}\nActive tabs: ${instanceInfo.total}`;

                rcLog(`✅ Instance label updated: ${labelText}`);
            }
        } catch (e) {
        }
    }

    /**
     * Start periodic instance label updates
     */
    startInstanceTracking() {
        try {
            // Initial update
            this.updateInstanceLabel();

            rcLog('✅ Instance tracking started');
        } catch (e) {
        }
    }

    /**
     * Stop instance tracking
     */
    stopInstanceTracking() {
        if (this._instanceTrackingInterval) {
            clearInterval(this._instanceTrackingInterval);
            this._instanceTrackingInterval = null;
        }
    }

    /**
     * Check if current instance count has reached the limit
     * Returns { allowed: boolean, current: number, max: number, message: string }
     */
    async checkInstanceLimit() {
        try {
            const instanceInfo = await this.getInstanceNumber();
            const current = instanceInfo.total;
            const max = this.maxInstancesAllowed;

            const result = {
                allowed: current < max,
                current: current,
                max: max,
                message: ''
            };

            if (!result.allowed) {
                result.message = `ðŸš« Maximum instances (${max}) reached. Please close another tab to continue.`;
            } else if (current === max - 1) {
                result.message = `⚠️  Warning: You have 1 instance slot remaining. Current: ${current}/${max}`;
                console.warn(result.message);
            } else {
                result.message = `✅ Instance slots available. Current: ${current}/${max}`;
                rcLog(result.message);
            }

            return result;
        } catch (e) {
            return {
                allowed: true,
                current: 1,
                max: this.maxInstancesAllowed,
                message: 'Unable to verify instance limit'
            };
        }
    }

    _getUnixNow() {
        return Math.floor(Date.now() / 1000);
    }

    _normalizeServerUnixTime(value) {
        const parsed = Number(value);
        if (Number.isFinite(parsed) && parsed > 0) {
            return Math.floor(parsed);
        }
        return this._getUnixNow();
    }

    _extractExpiryUnix(data) {
        if (!data || typeof data !== 'object') return null;

        const explicitExpiry = Number(data.expires_at_unix);
        if (Number.isFinite(explicitExpiry) && explicitExpiry > 0) {
            return Math.floor(explicitExpiry);
        }

        const expiresIn = Number(data.expires_in);
        if (Number.isFinite(expiresIn) && expiresIn > 0) {
            return this._getUnixNow() + Math.floor(expiresIn);
        }

        return null;
    }

    _applySharedTokenTimer(data, options = {}) {
        const expiresAtUnix = this._extractExpiryUnix(data);
        if (!expiresAtUnix) return false;

        const localNowUnix = this._getUnixNow();
        const secondsUntilExpiry = Math.max(1, expiresAtUnix - localNowUnix);
        const expiryChanged = !this._tokenExpiresAtUnix || Math.abs(expiresAtUnix - this._tokenExpiresAtUnix) > 1;
        const postponeActive = !!(this._postponeUntil && Date.now() < this._postponeUntil);

        this._tokenExpiresAtUnix = expiresAtUnix;

        if (options.reschedule || expiryChanged || (!this._autoRefreshTimer && !postponeActive)) {
            this.startAutoRefresh(secondsUntilExpiry, expiresAtUnix);
            return true;
        }

        this._countdownExpiresAtUnix = expiresAtUnix;
        this._countdownRemaining = expiresAtUnix - this._getUnixNow();
        this._updateCountdownDisplay();
        return false;
    }

    async _syncTokenTimerNow() {
        if (this._tokenTimerSyncInFlight) return this._tokenTimerSyncInFlight;

        this._tokenTimerSyncInFlight = (async () => {
            try {
                const instanceId = this.getOrCreateInstanceId();
                const response = await fetch(
                    `${this.config.webphoneTokenTimerUrl}?instance_id=${encodeURIComponent(instanceId)}`,
                    {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'X-Instance-ID': instanceId
                        }
                    }
                );

                if (!response.ok) return false;

                const data = await this._parseJsonResponse(response, 'Token timer sync');
                if (data?.success === false) return false;

                return this._applySharedTokenTimer(data);
            } catch (e) {
                return false;
            } finally {
                this._tokenTimerSyncInFlight = null;
            }
        })();

        return this._tokenTimerSyncInFlight;
    }

    startTokenTimerSync() {
        this.stopTokenTimerSync();
        this._syncTokenTimerNow();
        this._tokenTimerSyncInterval = setInterval(() => {
            this._syncTokenTimerNow();
        }, this._tokenTimerSyncMs);
    }

    stopTokenTimerSync() {
        if (this._tokenTimerSyncInterval) {
            clearInterval(this._tokenTimerSyncInterval);
            this._tokenTimerSyncInterval = null;
        }
        this._tokenTimerSyncInFlight = null;
    }

    /**
     * Start automatic credential refresh loop
     * @param {number} expiresIn seconds until current credentials expire
     */
    startAutoRefresh(expiresIn, expiresAtUnix = null, serverNowUnix = null) {
        // Use one configured refresh interval for scheduling, warnings, and postpones.
        try {
            const localNowUnix = this._getUnixNow();
            const normalizedExpiry = Number.isFinite(Number(expiresAtUnix)) && Number(expiresAtUnix) > 0
                ? Math.floor(Number(expiresAtUnix))
                : null;
            const secondsUntilExpiry = normalizedExpiry
                ? Math.max(1, normalizedExpiry - localNowUnix)
                : Math.max(1, Math.floor(Number(expiresIn) || 0));

            if (normalizedExpiry) {
                this._tokenExpiresAtUnix = normalizedExpiry;
            } else {
                this._tokenExpiresAtUnix = this._getUnixNow() + secondsUntilExpiry;
            }

            const refreshBefore = this._refreshIntervalSeconds;
            // Schedule exactly once at expiry minus the configured refresh window.
            let refreshDelaySec = Math.max(1, secondsUntilExpiry - refreshBefore);
            // Never schedule past expiry.
            refreshDelaySec = Math.min(refreshDelaySec, Math.max(1, secondsUntilExpiry - 1));

            this.stopAutoRefresh();

            this._autoRefreshTimer = setTimeout(() => {
                this._autoRefreshTimer = null;
                this._refreshFlow(false, 'scheduled-interval');
            }, refreshDelaySec * 1000);

            // Add warning popup before the scheduled refresh
            this._setupFiveMinuteWarning(secondsUntilExpiry);

            // Start live countdown display (minutes:seconds)
            try {
                this.startCountdown(secondsUntilExpiry, this._tokenExpiresAtUnix);
            } catch (e) {
                console.warn('startCountdown failed', e);
            }
        } catch (e) {
        }
    }

    /**
     * Setup 5-minute warning toast before token expiration
     * @param {number} expiresIn seconds until token expires
     */
    _setupFiveMinuteWarning(secondsUntilRefresh) {
        // Clear any existing warning timer
        if (this._fiveMinuteWarningTimer) {
            clearTimeout(this._fiveMinuteWarningTimer);
        }

        const warningDelaySec = Math.max(1, (secondsUntilRefresh || 0) - this._refreshIntervalSeconds);

        this._fiveMinuteWarningTimer = setTimeout(() => {
            this._showTokenExpirationWarningToast();
        }, warningDelaySec * 1000);

        rcLog(`â° Refresh warning scheduled in ${Math.round(warningDelaySec / 60)} minutes`);
    }

    _dismissTokenExpirationWarningToast() {
        const toastEl = document.getElementById('ringcentral-token-warning-toast');
        if (toastEl) {
            if (typeof window.$ === 'function' && typeof window.$(toastEl).alert === 'function') {
                window.$(toastEl).alert('close');
            } else {
                toastEl.remove();
            }
        }

        if (this._warningToastAutoCloseTimer) {
            clearTimeout(this._warningToastAutoCloseTimer);
            this._warningToastAutoCloseTimer = null;
        }
    }

    /**
     * Show a non-blocking refresh warning toast with action to open modal
     */
    _showTokenExpirationWarningToast() {
        this._dismissTokenExpirationWarningToast();

        const hasActiveCall = this._hasActiveCall();
        const activeCallHint = hasActiveCall
            ? `<div class="small text-muted mt-1">Active call detected. You can postpone refresh by ${this._refreshIntervalLabel} from details.</div>`
            : '';

        const toastHtml = `
            <div id="ringcentral-token-warning-toast" class="alert alert-warning alert-dismissible fade show position-fixed"
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 340px; max-width: 460px;">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="pr-2">
                        <strong>R-Dialer  refresh in about ${this._refreshIntervalLabel}.</strong>
                        <div class="small text-muted">Your session will auto-refresh in the background.</div>
                        ${activeCallHint}
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-dark ml-2" id="open-refresh-warning-modal-btn">
                        <i class="fas fa-external-link-alt"></i>
                        Details
                    </button>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', toastHtml);

        const openDetailsBtn = document.getElementById('open-refresh-warning-modal-btn');
        if (openDetailsBtn) {
            openDetailsBtn.addEventListener('click', () => {
                this._dismissTokenExpirationWarningToast();
                this._showTokenExpirationWarning();
            });
        }

        this._warningToastAutoCloseTimer = setTimeout(() => {
            this._dismissTokenExpirationWarningToast();
        }, 25 * 1000);

        rcLog('🚨 Refresh warning toast displayed');
    }

    /**
     * Show 5-minute warning popup for token expiration
     */
    _showTokenExpirationWarning() {
        if (this._warningModalOpen) return;
        this._dismissTokenExpirationWarningToast();
        this._warningModalOpen = true;

        const hasActiveCall = this._hasActiveCall();
        const postponeButton = hasActiveCall
            ? `<button type="button" class="btn btn-secondary" id="postpone-refresh-btn">
                    <i class="fas fa-clock"></i>
                    Postpone ${this._refreshIntervalLabel}
               </button>`
            : '';

        const modalHtml = `
            <div id="ringcentral-token-warning-modal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle"></i>
                                R-Dialer  Refresh Scheduled
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-3">
                                <strong>Your R-Dialer  session will refresh in about ${this._refreshIntervalLabel}.</strong>
                            </p>
                            <p class="text-muted">
                                Refreshing keeps your WebPhone connection healthy.
                            </p>
                            <div class="small text-muted">Auto refresh in <span id="rc-refresh-countdown">${this._refreshCountdownLabel}</span></div>
                            ${hasActiveCall ? `<div class="alert alert-info">Active call detected. You can postpone the refresh by ${this._refreshIntervalLabel}.</div>` : ''}
                        </div>
                        <div class="modal-footer">
                            ${postponeButton}
                            <button type="button" class="btn btn-primary" id="refresh-tokens-btn">
                                <i class="fas fa-sync-alt"></i>
                                Refresh Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const existingModal = document.getElementById('ringcentral-token-warning-modal');
        if (existingModal) {
            existingModal.remove();
        }

        document.body.insertAdjacentHTML('beforeend', modalHtml);

        $('#ringcentral-token-warning-modal').modal({
            backdrop: 'static',
            keyboard: false
        });

        const refreshBtn = document.getElementById('refresh-tokens-btn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', async () => {
                refreshBtn.disabled = true;
                refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
                try {
                    await this._refreshFlow(true, 'manual-warning-modal');
                    $('#ringcentral-token-warning-modal').modal('hide');
                } catch (e) {
                    this._showErrorToast('Failed to refresh tokens. Please try again.');
                    refreshBtn.disabled = false;
                    refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh Now';
                }
            });
        }

        const postponeBtn = document.getElementById('postpone-refresh-btn');
        if (postponeBtn) {
            postponeBtn.addEventListener('click', () => {
                this._postponeRefreshFor(this._refreshIntervalSeconds);
                $('#ringcentral-token-warning-modal').modal('hide');
            });
        }

        if (this._warningAutoRefreshTimer) {
            clearTimeout(this._warningAutoRefreshTimer);
        }
        if (this._warningCountdownTimer) {
            clearInterval(this._warningCountdownTimer);
        }

        let remaining = this._refreshIntervalSeconds;
        const countdownEl = document.getElementById('rc-refresh-countdown');
        this._warningCountdownTimer = setInterval(() => {
            remaining -= 1;
            if (countdownEl) {
                countdownEl.textContent = this._formatRefreshCountdown(remaining);
            }
        }, 1000);

        this._warningAutoRefreshTimer = setTimeout(() => {
            if (!this._warningModalOpen) return;
            if (this._hasActiveCall()) {
                this._refreshTokensSilently('warning-auto-active-call');
                this._postponeRefreshFor(this._refreshIntervalSeconds);
            } else {
                this._refreshFlow(true, 'warning-auto-refresh');
            }
            $('#ringcentral-token-warning-modal').modal('hide');
        }, Math.max(1000, (this._refreshIntervalSeconds * 1000) - 1000));

        $('#ringcentral-token-warning-modal').on('hidden.bs.modal', () => {
            this._warningModalOpen = false;
            if (this._warningAutoRefreshTimer) {
                clearTimeout(this._warningAutoRefreshTimer);
                this._warningAutoRefreshTimer = null;
            }
            if (this._warningCountdownTimer) {
                clearInterval(this._warningCountdownTimer);
                this._warningCountdownTimer = null;
            }
        });

        rcLog('🚨 Refresh warning modal displayed');
    }

    /**
     * Show success toast message
     * @param {string} message
     */
    _showSuccessToast(message) {
        this._showToast(message, 'success');
    }

    /**
     * Show error toast message
     * @param {string} message
     */
    _showErrorToast(message) {
        this._showToast(message, 'danger');
    }

    _resolveTokenTrigger(source = 'unknown', mode = 'standard') {
        const normalizedSource = String(source || 'unknown').trim();
        const normalizedMode = ['standard', 'forced', 'silent'].includes(String(mode || '').toLowerCase())
            ? String(mode).toLowerCase()
            : 'standard';

        const triggerMap = {
            'scheduled-interval': { id: 1, name: 'scheduled-interval' },
            'near-expiry-fast-check': { id: 2, name: 'near-expiry-fast-check' },
            'manual-warning-modal': { id: 3, name: 'manual-warning-modal' },
            'warning-auto-refresh': { id: 4, name: 'warning-auto-refresh' },
            'countdown-expired': { id: 5, name: 'countdown-expired' },
            'warning-auto-active-call': { id: 6, name: 'silent-refresh' },
            'active-call-fallback': { id: 6, name: 'silent-refresh' },
            'transport-error-websocket-closed': { id: 6, name: 'silent-refresh' },
            'ws-error-websocket-closed': { id: 6, name: 'silent-refresh' },
            'ua-error-websocket-closed': { id: 6, name: 'silent-refresh' },
            'outbound-call-websocket-closed': { id: 6, name: 'silent-refresh' },
            // Keep postponed scheduled retry under the scheduled bucket.
            'postpone-resume': { id: 1, name: 'scheduled-interval' },
        };

        const mapped = triggerMap[normalizedSource] || { id: 0, name: 'unknown' };
        return {
            id: mapped.id,
            name: mapped.name,
            source: normalizedSource,
            mode: normalizedMode
        };
    }

    _buildWebphoneTokenUrl(instanceId, options = {}) {
        const forceRefresh = !!options.forceRefresh;
        const trigger = this._resolveTokenTrigger(options.triggerSource || 'unknown', options.triggerMode || 'standard');
        const query = new URLSearchParams();
        query.set('instance_id', String(instanceId || ''));
        if (forceRefresh) query.set('force_refresh', '1');
        query.set('trigger_id', String(trigger.id));
        query.set('trigger_name', trigger.name);
        query.set('trigger_mode', trigger.mode);
        return `${this.config.apiBaseUrl}/webphone-token?${query.toString()}`;
    }

    _announceRefreshTrigger(source = 'unknown', mode = 'standard') {
        const trigger = this._resolveTokenTrigger(source, mode);
        const detail = {
            source: trigger.source,
            mode: trigger.mode,
            trigger_id: trigger.id,
            trigger_name: trigger.name,
            at_unix: this._getUnixNow()
        };

        try {
            console.info('[RingCentral] Token refresh triggered', detail);
        } catch (_) { }

        try {
            document.dispatchEvent(new CustomEvent('ringcentral:tokenRefreshTriggered', { detail }));
        } catch (_) { }

        this._showToast(`Token refresh triggered (${detail.trigger_id}: ${detail.trigger_name}, ${detail.mode})`, 'info');
    }

    /**
     * Show toast message using Bootstrap toast or alert fallback
     * @param {string} message
     * @param {string} type - 'success', 'danger', 'warning', 'info'
     */
    _showToast(message, type = 'info') {
        // Try to use existing toast system if available
        if (typeof window.showToast === 'function') {
            window.showToast(message, type);
            return;
        }

        // Fallback: create a simple alert-style notification
        const toastHtml = `
            <div class="alert alert-${type} alert-dismissible fade show position-fixed"
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', toastHtml);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (alert.textContent.includes(message)) {
                    $(alert).alert('close');
                }
            });
        }, 5000);
    }

    stopAutoRefresh() {
        if (this._autoRefreshTimer) {
            clearTimeout(this._autoRefreshTimer);
            this._autoRefreshTimer = null;
        }

        if (this._postponeTimer) {
            clearTimeout(this._postponeTimer);
            this._postponeTimer = null;
        }

        // Also clear the 5-minute warning timer
        if (this._fiveMinuteWarningTimer) {
            clearTimeout(this._fiveMinuteWarningTimer);
            this._fiveMinuteWarningTimer = null;
        }

        if (this._warningAutoRefreshTimer) {
            clearTimeout(this._warningAutoRefreshTimer);
            this._warningAutoRefreshTimer = null;
        }

        if (this._warningCountdownTimer) {
            clearInterval(this._warningCountdownTimer);
            this._warningCountdownTimer = null;
        }

        this._dismissTokenExpirationWarningToast();

        // Stop countdown display
        try {
            this.stopCountdown();
        } catch (e) {
        }
    }

    _hasActiveCall() {
        if (this.currentSession) return true;
        if (Array.isArray(this.callSessions) && this.callSessions.length) return true;
        return false;
    }

    _postponeRefreshFor(seconds) {
        const delaySec = Math.max(60, seconds || 0);
        this._postponeUntil = Date.now() + delaySec * 1000;
        this.stopAutoRefresh();

        this._postponeTimer = setTimeout(() => {
            this._refreshFlow(false, 'postpone-resume');
        }, delaySec * 1000);

        this._setupFiveMinuteWarning(delaySec);
        this._showToast(`Refresh postponed for ${Math.round(delaySec / 60)} minutes`, 'warning');
    }

    _getTransportReadyState() {
        try {
            const ws = this.webphone?.userAgent?._transport?.ws
                || this.userAgent?._transport?.ws
                || this.webphone?._transport?.ws
                || null;
            return (ws && typeof ws.readyState === 'number') ? ws.readyState : null;
        } catch (_) {
            return null;
        }
    }

    _updateDialBlockState(reason = '') {
        const shouldBlock = !!(this._isRefreshing || this._silentRefreshInFlight || this._refreshFailureRequiresReconnect);
        const nextReason = shouldBlock
            ? (
                reason
                || (this._refreshFailureRequiresReconnect ? (this._refreshFailureReason || 'R-Dialer  refresh failed. Please reconnect or reload the page.') : '')
                || this._dialBlockedReason
                || 'R-Dialer  is reconnecting. Please wait a few seconds.'
            )
            : '';
        const changed = (this._dialBlocked !== shouldBlock) || (this._dialBlockedReason !== nextReason);
        this._dialBlocked = shouldBlock;
        this._dialBlockedReason = nextReason;

        if (changed) {
            this.dispatchEvent('dialerStateChanged', {
                blocked: this._dialBlocked,
                reason: this._dialBlockedReason
            });
        }
    }

    canMakeOutboundCall() {
        if (!this.isInitialized) {
            return { ok: false, reason: 'WebPhone is not initialized yet. Please wait a few seconds.' };
        }
        if (!this.webphone) {
            return { ok: false, reason: 'WebPhone is reconnecting. Please wait and try again.' };
        }
        if (this._dialBlocked || this._isRefreshing || this._silentRefreshInFlight) {
            return { ok: false, reason: this._dialBlockedReason || 'R-Dialer  token is refreshing. Please wait a few seconds.' };
        }

        const wsState = this._getTransportReadyState();
        if (typeof wsState === 'number' && wsState !== 1) {
            return { ok: false, reason: 'R-Dialer  connection is reconnecting. Please wait before dialing.' };
        }

        return { ok: true, reason: '' };
    }

    async _parseJsonResponse(response, contextLabel = 'request') {
        const contentType = String(response?.headers?.get('content-type') || '').toLowerCase();
        const raw = await response.text();

        if (!raw) return null;

        if (contentType.includes('application/json')) {
            try {
                return JSON.parse(raw);
            } catch (parseErr) {
                throw new Error(`${contextLabel} returned invalid JSON payload.`);
            }
        }

        const snippet = String(raw || '').slice(0, 80).replace(/\s+/g, ' ');
        throw new Error(`${contextLabel} expected JSON but received non-JSON response: ${snippet}`);
    }

    _shouldForceSilentRefresh(triggerSource = 'unknown') {
        return [
            'transport-error-websocket-closed',
            'ws-error-websocket-closed',
            'ua-error-websocket-closed',
            'outbound-call-websocket-closed'
        ].includes(String(triggerSource || ''));
    }

    async _refreshTokensSilently(triggerSource = 'unknown') {
        if (this._silentRefreshInFlight) return this._silentRefreshInFlight;

        this._silentRefreshInFlight = (async () => {
            this._announceRefreshTrigger(triggerSource, 'silent');
            this._updateDialBlockState('Refreshing R-Dialer  token in background...');
            this._updateCountdownDisplay();
            try {
                const instanceId = this.getOrCreateInstanceId();
                const forceRefresh = this._shouldForceSilentRefresh(triggerSource);
                const resp = await fetch(this._buildWebphoneTokenUrl(instanceId, {
                    forceRefresh: forceRefresh,
                    triggerSource: triggerSource,
                    triggerMode: 'silent'
                }), {
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'X-Instance-ID': instanceId
                    }
                });

                if (!resp.ok) {
                    console.warn('Silent refresh returned non-OK:', resp.status);
                    this._refreshFailureRequiresReconnect = true;
                    this._refreshFailureReason = `Token refresh failed (${resp.status}). Please reconnect R-Dialer .`;
                    return false;
                }

                const data = await this._parseJsonResponse(resp, 'Silent token refresh');
                this._applySharedTokenTimer(data, { reschedule: true });
                this._refreshFailureRequiresReconnect = false;
                this._refreshFailureReason = '';

                if (!this._lastSilentRefreshToastAt || (Date.now() - this._lastSilentRefreshToastAt) > 60000) {
                    this._showToast('Token refreshed in background', 'info');
                    this._lastSilentRefreshToastAt = Date.now();
                }
                return true;
            } catch (e) {
                console.warn('Silent token refresh failed', e);
                this._refreshFailureRequiresReconnect = true;
                this._refreshFailureReason = 'Token refresh failed. Please reconnect R-Dialer  or reload the page.';
                return false;
            } finally {
                this._silentRefreshInFlight = null;
                this._updateDialBlockState();
                this._updateCountdownDisplay();
            }
        })();

        return this._silentRefreshInFlight;
    }

    async _refreshFlow(forceRefresh = false, triggerSource = 'unknown') {
        if (this._isRefreshing) {
            const refreshAgeMs = this._refreshStartedAt ? (Date.now() - this._refreshStartedAt) : 0;
            if (refreshAgeMs > this._refreshStaleAfterMs) {
                console.warn('WebPhone refresh state was stale; clearing and retrying', {
                    triggerSource,
                    refreshAgeMs
                });
                this._isRefreshing = false;
                this._refreshStartedAt = 0;
                this._updateDialBlockState();
            } else {
                this._updateCountdownDisplay();
                return;
            }
        }
        if (this._silentRefreshInFlight) {
            this._updateCountdownDisplay();
            return;
        }
        if (this._hasActiveCall()) {
            await this._refreshTokensSilently(triggerSource || 'active-call-fallback');
            return;
        }
        this._announceRefreshTrigger(triggerSource, forceRefresh ? 'forced' : 'standard');
        this._isRefreshing = true;
        this._refreshStartedAt = Date.now();
        this._updateDialBlockState('Refreshing R-Dialer  session...');
        this._updateCountdownDisplay();
        try {
            const instanceId = this.getOrCreateInstanceId();
            rcLog('WebPhone: fetching fresh webphone-token for refresh');
            const resp = await fetch(this._buildWebphoneTokenUrl(instanceId, {
                forceRefresh: forceRefresh,
                triggerSource: triggerSource,
                triggerMode: forceRefresh ? 'forced' : 'standard'
            }), {
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Instance-ID': instanceId
                }
            });
            if (!resp.ok) {
                this._refreshFailureRequiresReconnect = true;
                this._refreshFailureReason = `Token refresh failed (${resp.status}). Please reconnect R-Dialer .`;
                this._isRefreshing = false;
                this._updateDialBlockState();
                return;
            }
            const data = await this._parseJsonResponse(resp, 'Token refresh');
            this._applySharedTokenTimer(data, { reschedule: true });
            this._refreshFailureRequiresReconnect = false;
            this._refreshFailureReason = '';

            // If the current webphone is not initialized or token changed, reinitialize
            const currentToken = this.webphone && this.webphone._accessToken ? this.webphone._accessToken : null;
            const newToken = data.access_token || null;

            // If access token changed or SIP info changed, attempt safe reinit
            if (!currentToken || (newToken && newToken !== currentToken)) {
                rcLog('WebPhone: credentials changed â€” reinitializing WebPhone to refresh registration');
                // Attempt a safe reinit: stop auto-refresh, dispose and init again
                try {
                    this.stopAutoRefresh();
                } catch (e) {}

                try {
                    const wsState = this.webphone?.userAgent?._transport?.ws?.readyState;
                    if (typeof wsState === 'number' && (wsState === 2 || wsState === 3)) {
                    } else {
                        await this.dispose();
                    }
                } catch (e) {
                }

                // Small delay to ensure cleanup
                await new Promise(r => setTimeout(r, 500));

                try {
                    // Re-run init with fresh payload to avoid a second token request.
                    await this.init({ tokenData: data, skipTimingRefresh: true });
                    rcLog('WebPhone reinitialized after refresh');
                } catch (e) {
                    console.error('Failed to reinitialize WebPhone during refresh:', e);
                }
            } else {
                rcLog('WebPhone: token unchanged; no reinit required');
            }

        } catch (e) {
            console.error('Automatic refresh flow failed:', e);
            this._refreshFailureRequiresReconnect = true;
            this._refreshFailureReason = 'Token refresh failed. Please reconnect R-Dialer  or reload the page.';
        } finally {
            this._isRefreshing = false;
            this._refreshStartedAt = 0;
            this._updateDialBlockState();
            this._updateCountdownDisplay();
        }
    }

    /**
     * Start a live countdown (seconds) and update DOM every second.
     * When remaining <= 0 shows expired time (how long since expiry) in red.
     */
    startCountdown(expiresInSeconds, expiresAtUnix = null) {
        // normalize
        this.stopCountdown();
        if (!expiresInSeconds || typeof expiresInSeconds !== 'number') return;
        const nowUnix = this._getUnixNow();
        const normalizedExpiry = Number.isFinite(Number(expiresAtUnix)) && Number(expiresAtUnix) > 0
            ? Math.floor(Number(expiresAtUnix))
            : (nowUnix + Math.max(1, Math.floor(expiresInSeconds)));

        this._countdownExpiresAtUnix = normalizedExpiry;
        this._countdownTotalSeconds = Math.max(1, normalizedExpiry - nowUnix);
        this._countdownRemaining = this._countdownTotalSeconds;
        this._countdownExpiryRefreshTriggered = false;

        // ensure progress element exists (always)
        this._ensureCountdownProgressElement();
        // old floating countdown remains local-only
        if (window.RC_SHOW_TOKEN_COUNTDOWN) {
            this._ensureCountdownElement();
        }

        // initial update
        this._updateCountdownDisplay();

        this._countdownTimer = setInterval(() => {
            if (this._countdownExpiresAtUnix) {
                this._countdownRemaining = this._countdownExpiresAtUnix - this._getUnixNow();
            } else {
                this._countdownRemaining -= 1;
            }
            this._updateCountdownDisplay();
        }, 1000);
    }

    stopCountdown() {
        if (this._countdownTimer) {
            clearInterval(this._countdownTimer);
            this._countdownTimer = null;
        }
        this._countdownRemaining = null;
        this._countdownTotalSeconds = null;
        this._countdownExpiresAtUnix = null;
        this._countdownExpiryRefreshTriggered = false;
        this._updateCountdownDisplay();
    }

    _ensureCountdownElement() {
        let el = document.getElementById(this._countdownElementId);
        if (!el) {
            el = document.createElement('div');
            el.id = this._countdownElementId;
            el.style.position = 'fixed';
            el.style.right = '12px';
            el.style.top = '10%';
            el.style.zIndex = '99999';
            el.style.padding = '6px 10px';
            el.style.background = '#001d4d';
            el.style.color = '#fff';
            el.style.borderRadius = '6px';
            el.style.fontFamily = 'Arial, sans-serif';
            el.style.fontSize = '13px';
            el.style.boxShadow = '0 2px 6px rgba(0,0,0,0.2)';
            el.style.transition = 'background-color 0.2s';
            el.setAttribute('aria-live', 'polite');
            document.body.appendChild(el);
        }
        return el;
    }

    _ensureCountdownProgressElement() {
        let el = document.getElementById(this._countdownProgressElementId);
        if (!el) {
            el = document.createElement('div');
            el.id = this._countdownProgressElementId;
            const topHeader = document.querySelector('.top-header');
            if (topHeader) {
                topHeader.insertBefore(el, topHeader.firstChild || null);
            } else {
                document.body.appendChild(el);
            }
        }
        return el;
    }

    _updateCountdownDisplay() {
        const progressEl = this._ensureCountdownProgressElement();
        const isLocal = !!window.RC_SHOW_TOKEN_COUNTDOWN;
        const includeLocalPcTime = isLocal;
        const localPcTime = new Date().toLocaleTimeString();
        let textEl = document.getElementById(this._countdownElementId);
        if (isLocal) {
            textEl = this._ensureCountdownElement();
            if (textEl) textEl.style.display = 'block';
        } else if (textEl) {
            textEl.style.display = 'none';
        }

        const remaining = this._countdownRemaining;
        if (typeof remaining === 'number' && remaining <= 0) {
            const canTriggerRefresh =
                !this._countdownExpiryRefreshTriggered
                && !this._isRefreshing
                && !this._silentRefreshInFlight;

            if (canTriggerRefresh) {
                this._countdownExpiryRefreshTriggered = true;
                Promise.resolve().then(() => this._refreshFlow(true, 'countdown-expired'));
            }
        } else if (typeof remaining === 'number' && remaining > 0) {
            this._countdownExpiryRefreshTriggered = false;
        }

        if (remaining === null || typeof remaining === 'undefined') {
            if (progressEl) {
                progressEl.style.setProperty('--rc-token-progress', '0');
                progressEl.classList.remove('rc-token-countdown-refreshing');
                progressEl.classList.remove(this._countdownExpiredClass);
                progressEl.title = '';
                progressEl.setAttribute('aria-label', '');
            }
            if (textEl && isLocal) {
                textEl.textContent = '';
                textEl.title = '';
                textEl.setAttribute('aria-label', '');
            }
            return;
        }

        const total = Math.max(1, parseInt(this._countdownProgressTotalSeconds, 10) || (60 * 60));
        const progress = remaining >= 0
            ? Math.max(0, Math.min(1, remaining / total))
            : 1;

        const mins = Math.floor(Math.abs(remaining) / 60);
        const secs = Math.floor(Math.abs(remaining) % 60);
        const expiresInLabel = `Token expires in ${mins}m ${secs}s`;
        const expiredLabel = `Token expired ${mins}m ${secs}s ago`;
        const refreshingLabel = 'Refreshing token...';
        const withLocalPcTime = (label) => includeLocalPcTime ? `${label} | Local PC ${localPcTime}` : label;

        if (progressEl) {
            const isRefreshing = !!(this._isRefreshing || this._silentRefreshInFlight);
            progressEl.style.setProperty('--rc-token-progress', isRefreshing ? '1' : String(progress));

            if (remaining < 0) {
                progressEl.classList.remove('rc-token-countdown-refreshing');
                progressEl.classList.add(this._countdownExpiredClass);
                progressEl.style.setProperty('--rc-token-color', '#ef4444');
                progressEl.title = withLocalPcTime(expiredLabel);
                progressEl.setAttribute('aria-label', progressEl.title);
            } else if (isRefreshing) {
                progressEl.classList.remove(this._countdownExpiredClass);
                progressEl.classList.add('rc-token-countdown-refreshing');
                progressEl.style.setProperty('--rc-token-color', '#facc15');
                progressEl.title = withLocalPcTime(refreshingLabel);
                progressEl.setAttribute('aria-label', progressEl.title);
            } else {
                const hue = Math.round(45 + (progress * 75)); // low time -> yellow, high time -> green
                progressEl.classList.remove(this._countdownExpiredClass);
                progressEl.classList.remove('rc-token-countdown-refreshing');
                progressEl.style.setProperty('--rc-token-color', `hsl(${hue} 85% 42%)`);
                progressEl.title = withLocalPcTime(expiresInLabel);
                progressEl.setAttribute('aria-label', progressEl.title);
            }
        }

        if (textEl && isLocal) {
            if (remaining < 0) {
                textEl.style.background = '#8b0000';
                textEl.style.color = '#fff';
                textEl.textContent = withLocalPcTime(expiredLabel);
            } else if (this._isRefreshing || this._silentRefreshInFlight) {
                textEl.style.background = '#facc15';
                textEl.style.color = '#111';
                textEl.textContent = withLocalPcTime(refreshingLabel);
            } else {
                textEl.style.background = '#001d4d';
                textEl.style.color = '#fff';
                textEl.textContent = withLocalPcTime(expiresInLabel);
            }
            textEl.title = textEl.textContent;
            textEl.setAttribute('aria-label', textEl.textContent);
        }
    }

    /**
     * Initialize WebPhone with SIP credentials
     */
    async init(options = {}) {
        try {
            // Get unique instanceId for this browser FIRST
            const instanceId = this.getOrCreateInstanceId();
            const preloadedTokenData = options && options.tokenData ? options.tokenData : null;
            const skipTimingRefresh = !!(options && options.skipTimingRefresh);
            let data = preloadedTokenData;

            if (!data) {
                // Get token and SIP info from server
                // Send instanceId both as header (preferred) and query param (fallback)
                const response = await fetch(this._buildWebphoneTokenUrl(instanceId, {
                    forceRefresh: false,
                    triggerSource: 'unknown',
                    triggerMode: 'standard'
                }), {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'X-Instance-ID': instanceId  // Send browser's instanceId to backend for tracking
                    }
                });

                if (!response.ok) {
                    // Handle specific error codes
                    if (response.status === 409) {
                        // Conflict: User already logged in on another device
                        let errorData = {};
                        try {
                            errorData = (await this._parseJsonResponse(response, 'WebPhone token conflict')) || {};
                        } catch (_) { }
                        const errorMessage = errorData.message || 'You are already logged in on another device. Please logout from the other device first.';

                        console.error('âŒ Instance conflict (409):', errorMessage);

                        // Dispatch error event so blade can show modal
                        document.dispatchEvent(new CustomEvent('ringcentral:instanceConflict', {
                            detail: { message: errorMessage, status: 409 }
                        }));

                        throw new Error(errorMessage);
                    }

                    const tokenError = new Error(`Failed to get webphone token: ${response.status}`);
                    tokenError.status = response.status;
                    throw tokenError;
                }

                data = await this._parseJsonResponse(response, 'WebPhone token');
            }

            this._applySharedTokenTimer(data, { reschedule: true });

            // Store instance limit from backend (based on subscription package)
            if (data.max_instances) {
                this.maxInstancesAllowed = data.max_instances;
                rcLog(`ðŸ“Š Max instances allowed for this user: ${this.maxInstancesAllowed}`);
            }

            // Check if we've hit the instance limit
            const limitCheck = await this.checkInstanceLimit();
            if (!limitCheck.allowed) {
                throw new Error(limitCheck.message);
            }

            // Store recording capabilities if provided by the server
            this.recordingCapabilities = data.recordingCapabilities || data.recording_capabilities || null;

            // Check if WebPhone library is loaded
            const WebPhone = window.WebPhone || window.RingCentralWebPhone || window.RingCentral?.WebPhone;

            if (!WebPhone) {
                throw new Error('R-Dialer  WebPhone library not loaded. Please include web-phone.min.js');
            }

            // Get SIP provisioning info
            // SIP info can be an array or a single object
            let sipInfo = null;
            if (Array.isArray(data.sipInfo) && data.sipInfo.length > 0) {
                sipInfo = data.sipInfo[0];
            } else if (data.sipInfo && typeof data.sipInfo === 'object') {
                sipInfo = data.sipInfo;
            }

            if (!sipInfo) {
                throw new Error('SIP provisioning info not available');
            }

            rcLog('SIP Info received:', sipInfo);

            // FIX: Token timing validation to prevent match() error
            // Check if token expires soon and refresh if needed
            const expiresAtForTiming = this._extractExpiryUnix(data);
            const timeToExpiry = expiresAtForTiming
                ? Math.max(0, expiresAtForTiming - this._getUnixNow())
                : Math.max(0, Math.floor(Number(data?.expires_in) || 0));
            const minSafeTime = this._refreshIntervalSeconds;

            if (timeToExpiry < minSafeTime && !skipTimingRefresh) {
                rcLog(`âš ï¸ TOKEN TIMING FIX: Token expires in ${timeToExpiry}s (${Math.round(timeToExpiry/60)}min) - refreshing before WebPhone init...`);

                try {
                    const instanceId = this.getOrCreateInstanceId();
                    // Wait for token refresh to complete
                    await new Promise(resolve => setTimeout(resolve, 1000));

                    // Re-fetch fresh token data
                    const refreshResponse = await fetch(this._buildWebphoneTokenUrl(instanceId, {
                        forceRefresh: true,
                        triggerSource: 'near-expiry-fast-check',
                        triggerMode: 'forced'
                    }), {
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'X-Instance-ID': instanceId
                        }
                    });

                    if (refreshResponse.ok) {
                        const freshData = await this._parseJsonResponse(refreshResponse, 'Token timing refresh');
                        rcLog('âœ… TOKEN TIMING FIX: Token refreshed successfully, using fresh data');


                        // Update data with fresh tokens
                        data.access_token = freshData.access_token;
                        data.refresh_token = freshData.refresh_token;
                        data.expires_in = freshData.expires_in;
                        data.expires_at_unix = freshData.expires_at_unix;
                        data.server_time_unix = freshData.server_time_unix;
                        data.server = freshData.server;
                        data.client_id = freshData.client_id;
                        this._applySharedTokenTimer(freshData, { reschedule: true });

                        // Update SIP info if provided
                        if (freshData.sipInfo) {
                            if (Array.isArray(freshData.sipInfo) && freshData.sipInfo.length > 0) {
                                sipInfo = freshData.sipInfo[0];
                            } else if (freshData.sipInfo && typeof freshData.sipInfo === 'object') {
                                sipInfo = freshData.sipInfo;
                            }
                            rcLog('âœ… TOKEN TIMING FIX: SIP info also refreshed:', sipInfo);
                        }
                    } else {
                    }
                } catch (refreshError) {
                }
            } else {
                rcLog(`âœ… TOKEN TIMING FIX: Token expires in ${Math.round(timeToExpiry/60)}min - safe to proceed`);
            }

            // Create audio elements for WebPhone
            this.createAudioElements();

            // Initialize WebPhone
            // Try different ways to instantiate WebPhone
            let WebPhoneConstructor = WebPhone.default || WebPhone;

            // Some versions export as a class, others as a factory function
            if (typeof WebPhoneConstructor !== 'function') {
                // Try to find the constructor
                if (WebPhone.WebPhone && typeof WebPhone.WebPhone === 'function') {
                    WebPhoneConstructor = WebPhone.WebPhone;
                } else if (WebPhone.default && typeof WebPhone.default === 'function') {
                    WebPhoneConstructor = WebPhone.default;
                }
            }

            rcLog('Initializing WebPhone with:', {
                hasConstructor: typeof WebPhoneConstructor === 'function',
                sipInfo: sipInfo,
                server: data.server
            });

            // WebPhone SDK initialization options
            // According to R-Portal WebPhone SDK docs, sipInfo should be passed directly
            // and the constructor expects: new WebPhone(sipInfo, { accessToken, server, clientId, audioHelper })
            // Derive a robust server URL if not provided explicitly
            let derivedServer = data.server;
            try {
                const outProxy = sipInfo?.outboundProxy || sipInfo?.proxy || sipInfo?.sipServer || null;
                const transport = (sipInfo?.transport || 'wss').toLowerCase();
                const domain = sipInfo?.domain || sipInfo?.server || null;

                const ensureWs = (host) => {
                    if (!host) return null;
                    const trimmed = String(host).trim();
                    if (/^wss?:\/\//i.test(trimmed)) return trimmed;
                    return `${transport}://${trimmed}`;
                };

                if (!derivedServer || typeof derivedServer !== 'string' || !derivedServer.match(/^wss?:\/\//i)) {
                    derivedServer = ensureWs(outProxy) || ensureWs(domain) || derivedServer;
                }
            } catch (e) {
            }

            // Use instanceId generated at the start of init()
            // This ensures that multiple browser tabs/windows can all receive incoming calls
            // See: https://github.com/ringcentral/ringcentral-web-phone#instanceid-behavior-and-best-practices
            rcLog('ðŸ”· WebPhone instanceId for this browser:', instanceId);
            rcLog('â„¹ï¸  This instance will register to SIP server with unique ID:', instanceId);

            const webphoneOptions = {
                accessToken: data.access_token,
                server: derivedServer || data.server,
                clientId: data.client_id,
                instanceId: instanceId,  // CRITICAL: Unique ID per browser tab/window
                audioHelper: {
                    remote: this.remoteAudio,
                    local: this.localAudio
                }
            };

            // Initialize WebPhone
            // Based on R-Dialer  WebPhone SDK, the constructor typically expects:
            // new WebPhone(sipInfo, { accessToken, server, clientId, audioHelper })
            // where sipInfo is the object directly (not wrapped in array)
            if (typeof WebPhoneConstructor === 'function') {
                // Ensure sipInfo has all required properties
                if (!sipInfo.username || !sipInfo.password) {
                    throw new Error('SIP info missing required fields (username or password)');
                }

                try {
                    // Method 1: new WebPhone(sipInfo, options) - most common format
                    // sipInfo is passed as first parameter (object, not array)
                    this.webphone = new WebPhoneConstructor(sipInfo, webphoneOptions);
                    rcLog('WebPhone initialized successfully with method 1: new WebPhone(sipInfo, options)');
                } catch (e1) {
                    rcLog('Method 1 failed:', e1.message);
                    rcLog('SIP Info structure:', {
                        hasUsername: !!sipInfo.username,
                        hasPassword: !!sipInfo.password,
                        keys: Object.keys(sipInfo)
                    });

                    try {
                        // Method 2: new WebPhone({ sipInfo, accessToken, server, clientId, audioHelper })
                        this.webphone = new WebPhoneConstructor({
                            sipInfo: sipInfo,
                            accessToken: data.access_token,
                            server: data.server,
                            clientId: data.client_id,
                            instanceId: instanceId,  // Pass instanceId in options
                            audioHelper: {
                                remote: this.remoteAudio,
                                local: this.localAudio
                            }
                        });
                        rcLog('WebPhone initialized successfully with method 2: new WebPhone({ sipInfo, ...options })');

                        // Explicitly store instanceId on webphone object for easy access
                        this.webphone._instanceId = instanceId;
                        rcLog('âœ… Stored instanceId on webphone object:', instanceId);
                    } catch (e2) {
                        rcLog('Method 2 failed:', e2.message);
                        try {
                            // Method 3: new WebPhone(options) where sipInfo is in options as array
                            this.webphone = new WebPhoneConstructor({
                                sipInfo: [sipInfo],
                                accessToken: data.access_token,
                            server: data.server,
                            clientId: data.client_id,
                            audioHelper: {
                                remote: this.remoteAudio,
                                local: this.localAudio
                            }
                        });
                            rcLog('WebPhone initialized successfully with method 3: new WebPhone({ sipInfo: [sipInfo], ...options })');
                        } catch (e3) {
                            console.error('All initialization methods failed:', {
                                method1: e1.message,
                                method2: e2.message,
                                method3: e3.message,
                                sipInfoKeys: Object.keys(sipInfo),
                                sipInfoSample: {
                                    username: sipInfo.username ? 'present' : 'missing',
                                    password: sipInfo.password ? 'present' : 'missing',
                                    domain: sipInfo.domain
                                }
                            });
                            throw new Error(`Failed to initialize WebPhone. Check console for details. Last error: ${e3.message}`);
                        }
                    }
                }
            } else {
                throw new Error('WebPhone constructor not found');
            }

            // Get user agent - WebPhone SDK structure
            // The webphone instance has a userAgent property
            this.userAgent = this.webphone.userAgent;

            if (!this.userAgent) {
                // Some versions might expose userAgent differently
                this.userAgent = this.webphone;
            }

            rcLog('WebPhone structure:', {
                webphone: this.webphone,
                userAgent: this.userAgent,
                hasInvite: typeof this.userAgent?.invite === 'function',
                hasWebphoneUserAgent: typeof this.webphone?.userAgent?.invite === 'function',
                methods: Object.keys(this.userAgent || {}).slice(0, 10), // First 10 methods
                webphoneMethods: Object.keys(this.webphone || {}).slice(0, 10)
            });

            // Set up event listeners
            this.setupEventListeners();

            // Register WebPhone
            await this.register();

            this.isInitialized = true;
            this._refreshFailureRequiresReconnect = false;
            this._refreshFailureReason = '';
            this._updateDialBlockState();
            this.dispatchEvent('initialized', { success: true });
            this.startSessionRecoveryLoop();

            // Start automatic refresh of credentials so SIP/session stays alive
            try {
                const expiresAtUnix = this._extractExpiryUnix(data);
                if (expiresAtUnix) {
                    this.startAutoRefresh(
                        Math.max(1, expiresAtUnix - this._getUnixNow()),
                        expiresAtUnix
                    );
                } else if (data && data.expires_in) {
                    this.startAutoRefresh(data.expires_in);
                }
            } catch (e) {
            }
            try {
                this.startTokenTimerSync();
            } catch (e) {
            }
            // Expose instances globally to aid runtime inspection and debugging
            try {
                window.RC_WEBPHONE_INSTANCE = this;
                window.rcWebPhone = this.webphone;
                window.rcUserAgent = this.userAgent;
                rcLog('Exposed RC_WEBPHONE_INSTANCE, rcWebPhone, rcUserAgent on window for debugging');
            } catch (e) {
            }

            // Start instance tracking to display "Main Instance", "2nd Copy", etc.
            try {
                this.startInstanceTracking();
            } catch (e) {
            }

            rcInfo('WebPhone initialized successfully');
            return true;

        } catch (error) {
            console.error('Failed to initialize WebPhone:', error);
            const statusCode = (error && typeof error.status !== 'undefined') ? error.status : null;
            this.dispatchEvent('error', { error: error.message, status: statusCode });
            throw error;
        }
    }

    /**
     * Return cached recording capabilities (if available)
     */
    getRecordingCapabilities() {
        return this.recordingCapabilities;
    }

    /**
     * Create audio elements for WebPhone
     */
    createAudioElements() {
        // Remote audio (speaker)
        if (!this.remoteAudio) {
            this.remoteAudio = document.createElement('audio');
            this.remoteAudio.id = 'rc-remote-audio';
            this.remoteAudio.autoplay = true;
            this.remoteAudio.style.display = 'none';
            document.body.appendChild(this.remoteAudio);

            // Apply stored output device if available
            if (this.currentOutputDeviceId && this.currentOutputDeviceId !== 'default') {
                if (typeof this.remoteAudio.setSinkId === 'function') {
                    this.remoteAudio.setSinkId(this.currentOutputDeviceId).then(() => {
                        rcLog('ðŸ”Š Applied stored output device on audio element creation:', this.currentOutputDeviceId);
                    }).catch(e => {
                    });
                }
            }
        }

        // Local audio (microphone feedback - optional)
        if (!this.localAudio) {
            this.localAudio = document.createElement('audio');
            this.localAudio.id = 'rc-local-audio';
            this.localAudio.muted = true; // Mute local audio to prevent feedback
            this.localAudio.style.display = 'none';
            document.body.appendChild(this.localAudio);
        }

        // Ringtone audio element (used as fallback / playback target)
        if (!this.ringtoneAudio) {
            this.ringtoneAudio = document.createElement('audio');
            this.ringtoneAudio.id = 'rc-ringtone-audio';
            this.ringtoneAudio.loop = true;
            this.ringtoneAudio.style.display = 'none';
            document.body.appendChild(this.ringtoneAudio);
        }
    }

    // Ringtone control using Web Audio API
    startRingtone() {
        try {
            if (this.isLocalOutboundDialingActive()) {
                rcLog('[RINGTONE] Suppressed while this device is starting an outbound call');
                return;
            }
            if (this._ringtone) return; // already playing
            // If the SDK exposes a ringtone player, prefer it
            try {
                if (this.webphone && typeof this.webphone.playRingtone === 'function') {
                    rcLog('Using SDK playRingtone');
                    this.webphone.playRingtone();
                    this._ringtone = { sdk: true };
                    return;
                }
                if (this.webphone && this.webphone.audioHelper && typeof this.webphone.audioHelper.playRingtone === 'function') {
                    rcLog('Using SDK audioHelper.playRingtone');
                    this.webphone.audioHelper.playRingtone();
                    this._ringtone = { sdk: true };
                    return;
                }
            } catch (e) {  }
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            // Create AudioContext and route to an <audio> element via MediaStreamDestination
            this._ringtoneCtx = new AudioCtx();
            const ctx = this._ringtoneCtx;
            const gain = ctx.createGain();
            gain.gain.value = 0.0;

            const osc = ctx.createOscillator();
            osc.type = 'sine';
            osc.frequency.value = 1440; // A4 tone
            osc.connect(gain);

            // Create destination and connect gain -> destination
            const dest = ctx.createMediaStreamDestination();
            gain.connect(dest);

            osc.start();

            // Attach stream to ringtone audio element
            try {
                this.createAudioElements();
                if (this.ringtoneAudio) {
                    this.ringtoneAudio.srcObject = dest.stream;
                    // attempt to play; may be blocked by autoplay policies
                    const playPromise = this.ringtoneAudio.play();
                    if (playPromise && typeof playPromise.then === 'function') {
                        playPromise.catch(err => {
                            // show enable-sound hint in active calls area
                            // Do not force a visible enable button; rely on first-user-gesture resume
                            // keep a hidden marker so other code can detect blocked state
                            try { if (!document.getElementById('rc-enable-sound')) { const h = document.createElement('div'); h.id='rc-enable-sound'; h.style.display='none'; document.body.appendChild(h);} } catch(_){}
                        });
                    }
                }
            } catch (e) {
                // Fallback: connect gain -> destination to default output
                gain.connect(ctx.destination);
            }

            // simple beep pattern toggle via gain on/off
            this._ringtone = { ctx, osc, gain, dest };
            let on = true;
            gain.gain.setValueAtTime(0.100, ctx.currentTime);
            this._ringtoneInterval = setInterval(() => {
                try {
                    if (!this._ringtone) return;
                    on = !on;
                    gain.gain.setValueAtTime(on ? 0.100 : 0.0, ctx.currentTime);
                } catch (e) { /* ignore errors */ }
            }, 1200);
        } catch (e) {
        }
    }

    stopRingtone() {
        try {
            if (this._ringtoneInterval) {
                clearInterval(this._ringtoneInterval);
                this._ringtoneInterval = null;
            }
            if (!this._ringtone) return;
            // If SDK was used, attempt to stop it via SDK
            try {
                if (this._ringtone && this._ringtone.sdk) {
                    if (this.webphone && typeof this.webphone.stopRingtone === 'function') {

                        // GLOBAL WebSocket/transport error handler
                        this.userAgent.on('transportError', async (error) => {
                            const msg = String(error?.message || error || '');
                            if (/WebSocket is already in CLOSING or CLOSED state/i.test(msg)) {
                                this._showToast('WebPhone connection error: WebSocket closed. Attempting to refresh token...', 'warning');
                                await this._refreshTokensSilently('transport-error-websocket-closed');
                                this._showToast('WebPhone token refreshed after WebSocket error.', 'success');
                            }
                        });
                        // Some SDKs may emit 'wsError' or generic 'error' events
                        this.userAgent.on('wsError', async (error) => {
                            const msg = String(error?.message || error || '');
                            if (/WebSocket is already in CLOSING or CLOSED state/i.test(msg)) {
                                this._showToast('WebPhone connection error: WebSocket closed. Attempting to refresh token...', 'warning');
                                await this._refreshTokensSilently('ws-error-websocket-closed');
                                this._showToast('WebPhone token refreshed after WebSocket error.', 'success');
                            }
                        });
                        this.userAgent.on('error', async (error) => {
                            const msg = String(error?.message || error || '');
                            if (/WebSocket is already in CLOSING or CLOSED state/i.test(msg)) {
                                this._showToast('WebPhone connection error: WebSocket closed. Attempting to refresh token...', 'warning');
                                await this._refreshTokensSilently('ua-error-websocket-closed');
                                this._showToast('WebPhone token refreshed after WebSocket error.', 'success');
                            }
                        });
                        this.webphone.stopRingtone();
                    } else if (this.webphone && this.webphone.audioHelper && typeof this.webphone.audioHelper.stopRingtone === 'function') {
                        this.webphone.audioHelper.stopRingtone();
                    }
                }
            } catch (e) {  }
            const { osc, ctx } = this._ringtone;
            try { osc.stop(); } catch (_) {}
            try { ctx.close(); } catch (_) {}
            // stop audio element playback
            try {
                if (this.ringtoneAudio) {
                    try { this.ringtoneAudio.pause(); } catch (_) {}
                    try { this.ringtoneAudio.srcObject = null; } catch (_) {}
                }
            } catch (_) {}
            this._ringtone = null;
            this._ringtoneCtx = null;
        } catch (e) {
        }
    }

    /**
     * Register WebPhone with RingCentral
     */
    async register() {
        if (!this.webphone && !this.userAgent) {
            throw new Error('WebPhone not initialized');
        }

        try {
            const agent = this.userAgent || this.webphone?.userAgent || this.webphone;

            if (!agent) {
                throw new Error('WebPhone agent not found');
            }

            // For WebPhone 2.x SDK, start() may complete registration automatically
            // Create a promise that resolves when 'registered' event fires OR after start() completes
            let registrationCompleted = false;
            const registrationPromise = new Promise((resolve, reject) => {
                const timeout = setTimeout(() => {
                    if (!registrationCompleted) {
                        rcLog('â„¹ï¸ Checking WebPhone state for signs of successful registration...');
                        // Don't reject - allow fallback check below
                        resolve();
                    }
                }, 10000);

                const onRegistered = () => {
                    rcLog('âœ… [EVENT] "registered" event received from SDK');
                    registrationCompleted = true;
                    clearTimeout(timeout);
                    resolve();
                };

                const onRegistrationFailed = (error) => {
                    console.error('âŒ [EVENT] "registrationFailed" event received:', error);
                    registrationCompleted = true;
                    clearTimeout(timeout);
                    reject(new Error(`Registration failed: ${error?.message || error}`));
                };

                // Log all events to debug what's happening
                const logAllEvents = (eventName) => {
                    return (...args) => {
                        rcLog(`ðŸ“¡ [EVENT] "${eventName}" event received on agent`);
                    };
                };

                // Attach listeners for any events
                if (typeof agent.once === 'function') {
                    agent.once('registered', onRegistered);
                    agent.once('registrationFailed', onRegistrationFailed);
                    // Also log common events to see what's happening
                    agent.on('connecting', logAllEvents('connecting'));
                    agent.on('connected', logAllEvents('connected'));
                    agent.on('disconnecting', logAllEvents('disconnecting'));
                    agent.on('disconnected', logAllEvents('disconnected'));
                } else if (typeof agent.on === 'function') {
                    agent.on('registered', onRegistered);
                    agent.on('registrationFailed', onRegistrationFailed);
                    agent.on('connecting', logAllEvents('connecting'));
                    agent.on('connected', logAllEvents('connected'));
                    agent.on('disconnecting', logAllEvents('disconnecting'));
                    agent.on('disconnected', logAllEvents('disconnected'));
                }
            });

            // Start WebPhone (initiate SIP registration)
            rcLog('ðŸš€ Calling webphone.start() to initiate SIP registration...');
            if (typeof this.webphone.start === 'function') {
                try {
                    await this.webphone.start();
                    rcLog('âœ… webphone.start() completed successfully');
                } catch (e) {
                    // Don't reject yet - check if registration still worked
                }
            } else if (typeof agent.register === 'function') {
                try {
                    await agent.register();
                    rcLog('âœ… agent.register() completed successfully');
                } catch (e) {
                }
            }

            // Wait for registered event OR timeout
            rcLog('â³ Waiting for SIP server to acknowledge registration (up to 10 seconds)...');
            await registrationPromise;

            // Fallback check: if we got here, assume registration succeeded
            // (WebPhone 2.x may not emit 'registered' event in all cases)
            rcLog('âœ… Registration process complete - checking WebPhone state...');

            this.isRegistered = true;
            this._refreshFailureRequiresReconnect = false;
            this._refreshFailureReason = '';
            this._updateDialBlockState();
            rcInfo('âœ… WebPhone registered and ready for calls');

            // Check instanceId from multiple possible locations
            const instanceIdCheck = this.webphone?.instanceId || this.webphone?._instanceId || 'NOT FOUND';
            rcLog('â„¹ï¸  instanceId:', instanceIdCheck);
            rcLog('â„¹ï¸  SIP username:', this.webphone?.sipInfo?.username || 'NOT FOUND');

            this.dispatchEvent('registered', { success: true });
            return;

        } catch (error) {
            console.error('âŒ Failed to register WebPhone:', error?.message || error);
            this.isRegistered = false;
            this._updateDialBlockState('WebPhone registration failed. Reconnecting...');
            throw error;
        }
    }

    /**
     * Set up WebPhone event listeners
     */
    setupEventListeners() {
        if (!this.userAgent) return;

        // Registration events - CRITICAL for debugging multi-instance setup
        if (this.userAgent.on) {
            this.userAgent.on('registered', () => {
                this.isRegistered = true;
                this._updateDialBlockState();
                rcLog('âœ… [REGISTERED] WebPhone is NOW registered with SIP server (instanceId:', this.webphone?.instanceId, ')');
                this.dispatchEvent('registered', { success: true });
            });

            this.userAgent.on('unregistered', () => {
                this.isRegistered = false;
                this._updateDialBlockState('WebPhone is reconnecting. Please wait a few seconds.');
                rcLog('âŒ [UNREGISTERED] WebPhone lost SIP registration');
                this.dispatchEvent('unregistered', {});
            });

            this.userAgent.on('registrationFailed', (error) => {
                this._updateDialBlockState('WebPhone registration failed. Reconnecting...');
                console.error('âŒ [REGISTRATION FAILED] Error:', error);
                this.dispatchEvent('registrationFailed', { error });
            });

            // Incoming call
            this.userAgent.on('invite', (session) => {
                rcLog('WebPhone userAgent invite event received', session);
                this.handleIncomingCall(session);
            });
        }

        // Attach generic incoming/session handlers to more event names to be robust
        const attachIncomingHandlers = (obj, label) => {
            if (!obj) {
                rcLog('[Incoming] Skipping', label, '- object missing');
                return;
            }

            // Helper to detect available event attachment API
            const hasOn = (o) => typeof o.on === 'function' || typeof o.addEventListener === 'function' || typeof o.addListener === 'function' || (Object.getPrototypeOf(o) && typeof Object.getPrototypeOf(o).on === 'function');

            if (!hasOn(obj)) {
                rcLog('[Incoming] Skipping', label, '- no known event API (on/addEventListener/addListener)');

                // Try to probe nested properties commonly used by different SDK builds
                const candidates = ['ua','userAgent','_ua','sip','sipClient','_sipClient'];
                for (const c of candidates) {
                    if (obj[c] && hasOn(obj[c])) {
                        rcLog('[Incoming] Found nested event-capable object', c, 'on', label);
                        attachIncomingHandlers(obj[c], label + '.' + c);
                        return;
                    }
                }

                return;
            }

            rcLog('[Incoming] Attaching handlers to', label);
            const incomingEventNames = ['invite', 'newRTCSession', 'newSession', 'session', 'incoming', 'call', 'inviteReceived', 'inboundCall'];
            incomingEventNames.forEach(ev => {
                const handler = (session) => {
                    rcLog('ðŸ”” [INCOMING CALL EVENT]', ev, 'on', label, session);
                    this.handleIncomingCall(session);
                };

                try {
                    if (typeof obj.on === 'function') {
                        obj.on(ev, handler);
                    } else if (typeof obj.addEventListener === 'function') {
                        obj.addEventListener(ev, handler);
                    } else if (typeof obj.addListener === 'function') {
                        obj.addListener(ev, handler);
                    } else if (Object.getPrototypeOf(obj) && typeof Object.getPrototypeOf(obj).on === 'function') {
                        Object.getPrototypeOf(obj).on.call(obj, ev, handler);
                    } else {
                        throw new Error('No supported event API');
                    }
                    rcLog('[Incoming] Attached listener for', ev, 'on', label);
                } catch (e) {
                }
            });
        };

        // Try attaching to common places with labels for debugging
        attachIncomingHandlers(this.userAgent, 'userAgent');
        attachIncomingHandlers(this.webphone, 'webphone');
        attachIncomingHandlers(this.webphone?.userAgent, 'webphone.userAgent');
        attachIncomingHandlers(this.webphone?.sipClient, 'webphone.sipClient');
        attachIncomingHandlers(this.webphone?.sipClient?.userAgent, 'webphone.sipClient.userAgent');
        // Also attach to top-level userAgent/call manager cancel events if present
        try {
            if (this.userAgent && typeof this.userAgent.on === 'function') {
                this.userAgent.on('cancel', (session) => { rcLog('userAgent cancel event -> stopRingtone'); try { this.stopRingtone(); } catch(_){} });
            }
        } catch (e) {}

        // Log what's available for debugging (include prototype methods)
        const listFunctionNames = (o) => {
            if (!o) return 'N/A';
            try {
                const names = new Set();
                Object.getOwnPropertyNames(o).forEach(k => { if (typeof o[k] === 'function') names.add(k); });
                let proto = Object.getPrototypeOf(o);
                while (proto && proto !== Object.prototype) {
                    Object.getOwnPropertyNames(proto).forEach(k => { if (typeof proto[k] === 'function') names.add(k); });
                    proto = Object.getPrototypeOf(proto);
                }
                return Array.from(names).slice(0, 40);
            } catch (e) {
                return `Error listing methods: ${e.message}`;
            }
        };

        rcLog('[Incoming] Available methods on userAgent:', listFunctionNames(this.userAgent));
        rcLog('[Incoming] Available methods on webphone:', listFunctionNames(this.webphone));

        // Some SDKs expose invite on webphone or nested userAgent
        if (this.webphone?.on) {
            this.webphone.on('invite', (session) => {
                rcLog('WebPhone root invite event received', session);
                this.handleIncomingCall(session);
            });
            // Some builds emit inboundCall for incoming calls
            try {
                this.webphone.on('inboundCall', (session) => {
                    rcLog('WebPhone root inboundCall event received', session);
                    this.handleIncomingCall(session);
                });
            } catch (e) {}
        }
        // Listen for global cancel/terminated events to ensure ringtone stops
        try {
            if (this.webphone && typeof this.webphone.on === 'function') {
                this.webphone.on('cancel', () => { try { this.stopRingtone(); } catch(_){} });
                this.webphone.on('terminated', () => { try { this.stopRingtone(); } catch(_){} });
            }
        } catch (e) {}
        if (this.webphone?.userAgent?.on) {
            this.webphone.userAgent.on('invite', (session) => {
                rcLog('WebPhone nested userAgent invite event received', session);
                this.handleIncomingCall(session);
            });
        }
        // Some builds may expose 'invite' on sipClient as well
        if (this.webphone?.sipClient?.on) {
            this.webphone.sipClient.on('invite', (session) => {
                rcLog('WebPhone sipClient invite event received', session);
                this.handleIncomingCall(session);
            });
            try {
                this.webphone.sipClient.on('inboundCall', (session) => {
                    rcLog('WebPhone sipClient inboundCall event received', session);
                    this.handleIncomingCall(session);
                });
            } catch (e) {}
        }

        // Session events (for current call)
        this.updateSessionListeners();
    }

    /**
     * Update session event listeners
     */
    updateSessionListeners() {
        if (!this.currentSession) return;

        // Remove old listeners if any
        if (this.currentSession.off) {
            this.currentSession.off();
        }

        // Call state events
        if (this.currentSession.on) {
            this.currentSession.on('accepted', () => {
                try { this.currentSession._startedAt = Date.now(); } catch (_) {}
                this.dispatchEvent('callConnected', { session: this.currentSession });
            });

            this.currentSession.on('rejected', () => {
                this.dispatchEvent('callRejected', { session: this.currentSession });
                this.currentSession = null;
            });

            this.currentSession.on('failed', (error) => {
                this.dispatchEvent('callFailed', { session: this.currentSession, error });
                this.currentSession = null;
            });

            // Handle all possible session termination events
            const terminationEvents = ['terminated', 'bye', 'cancel', 'ended', 'failed', 'rejected'];
            terminationEvents.forEach(evName => {
                try {
                    this.currentSession.on(evName, (data) => {
                        rcLog('ðŸ”´ [CALL END EVENT]', evName, 'fired on session', data);
                        // Dispatch callEnded only once per session
                        if (this.currentSession) {
                            this.dispatchEvent('callEnded', { session: this.currentSession, reason: evName });
                            this.currentSession = null;
                        }
                    });
                } catch (e) {
                }
            });

            this.currentSession.on('muted', () => {
                this.micMuted = true;
                this.dispatchEvent('muted', { session: this.currentSession });
            });

            this.currentSession.on('unmuted', () => {
                this.micMuted = false;
                this.dispatchEvent('unmuted', { session: this.currentSession });
            });
        }
    }

    /**
     * Attach event listeners to a specific session and manage lifecycle
     */
    attachSessionListeners(session) {
        if (!session || typeof session.on !== 'function') return;
        if (this._sessionListenersBound && this._sessionListenersBound.has(session)) return;
        try { this._sessionListenersBound.add(session); } catch (_) { }
        // Call state events â€” stop ringtone and start duration on any active/answered event
        const startCallTimers = () => {
            try { this.stopRingtone(); } catch (_) {}
            try { if (session._ringingInterval) { clearInterval(session._ringingInterval); session._ringingInterval = null; } } catch (_) {}
            try {
                session._startedAt = session._startedAt || Date.now();
                // Also set startedAt (without underscore) for consistency with incoming calls and Active Calls table
                if (!session.startedAt) {
                    session.startedAt = session._startedAt;
                }
                if (session._durationInterval) clearInterval(session._durationInterval);
                session._durationInterval = setInterval(() => {
                    try {
                        const elapsed = Date.now() - (session._startedAt || Date.now());
                        session._durationElapsed = elapsed;
                        this.dispatchEvent('callDurationTick', { session, elapsed });
                    } catch (e) {}
                }, 1000);
            } catch (e) {  }
        };

        ['accepted','connected','answered','established','confirmed','active'].forEach(ev => {
            try { session.on(ev, () => { startCallTimers(); this.dispatchEvent('callConnected', { session }); }); } catch (_) {}
        });

        // Track hold state changes if the SDK emits them
        try {
            session.on('hold', () => {
                session._onHold = true;
                this.dispatchEvent('holdStarted', { session });
            });
        } catch (_) {}
        try {
            session.on('unhold', () => {
                session._onHold = false;
                this.dispatchEvent('holdEnded', { session });
            });
        } catch (_) {}

        const endOnce = (reason) => {
            // Remove from callSessions
            try {
                this.callSessions = this.callSessions.filter(s => s !== session);
            } catch {}
            // Clear currentSession if it matches
            if (this.currentSession === session) {
                this.currentSession = null;
            }
            // Stop ringtone and any timers related to this session
            try { this.stopRingtone(); } catch (_) {}
            try { if (session._ringingInterval) { clearInterval(session._ringingInterval); session._ringingInterval = null; } } catch (_) {}
            try { if (session._durationInterval) { clearInterval(session._durationInterval); session._durationInterval = null; } } catch (_) {}

            this.dispatchEvent('callEnded', { session, reason });
            try { this.cleanupIncomingSession(session); } catch (_) {}
        };

        const terminationEvents = ['terminated', 'bye', 'cancel', 'ended', 'failed', 'rejected', 'disposed'];
        terminationEvents.forEach(evName => {
            try {
                session.on(evName, () => endOnce(evName));
            } catch {}
        });

        session.on('muted', () => {
            this.micMuted = true;
            this.dispatchEvent('muted', { session });
        });

        session.on('unmuted', () => {
            this.micMuted = false;
            this.dispatchEvent('unmuted', { session });
        });
    }

    /**
     * Handle incoming call
     */
    handleIncomingCall(session) {
        if ((this.isLocalOutboundDialingActive() || this.hasLocalOutboundSessionActive()) && !this.hasInboundDirection(session)) {
            rcLog('Suppressing incoming handler/ringtone while this device is starting an outbound call:', {
                id: session && (session.id || session.sessionId || session.callId || session.partyId || session.index),
                state: session && (session.state || session.status),
                direction: session && (session.direction || session.callDirection)
            });
            try { this.stopRingtone(); } catch (_) {}
            return;
        }

        if (!this.isIncomingSessionLike(session)) {
            rcLog('Ignoring non-incoming session in incoming handler:', {
                id: session && (session.id || session.sessionId || session.callId || session.partyId || session.index),
                state: session && (session.state || session.status),
                direction: session && (session.direction || session.callDirection),
                originatedLocally: !!(session && session._rcOriginatedLocally)
            });
            try { this.stopRingtone(); } catch (_) {}
            return;
        }

        rcLog('Incoming call:', session);
        this.incomingCallSession = session;
        // Track session list
        if (!this.callSessions.includes(session)) {
            this.callSessions.push(session);
        }
        this.currentSession = session; // default to latest incoming
        // Attach listeners and start ringtone/timers
        this.attachSessionListeners(session);

        // Start ringtone for incoming call (SDK if available, otherwise Web Audio fallback)
        try { this.startRingtone(); } catch (_) {}

        // Try to extract caller name/number for UI if missing
        try {
            let callerName = 'Unknown';
            let callerNumber = 'Unknown';
            // Prefer remotePeer if available: format like "Name" <sip:+123@host>
            if (session.remotePeer && typeof session.remotePeer === 'string') {
                const m = session.remotePeer.match(/\"?([^\"]*?)\"?\s*<sip:([^@>]+)@/);
                if (m) { callerName = (m[1] && m[1].trim()) || callerName; callerNumber = m[2]; }
            }
            // Fallback to SIP From header
            if ((callerNumber === 'Unknown' || callerName === 'Unknown') && session.sipMessage && session.sipMessage.headers && session.sipMessage.headers.From) {
                const from = session.sipMessage.headers.From;
                const mf = String(from).match(/\"?([^\"]*?)\"?\s*<sip:([^@>]+)@/);
                if (mf) { callerName = (mf[1] && mf[1].trim()) || callerName; callerNumber = mf[2]; }
            }
            // As a last resort, try remoteNumber property if SDK exposes it
            if ((callerNumber === 'Unknown' || callerName === 'Unknown') && session.remoteNumber) {
                callerNumber = session.remoteNumber || callerNumber;
            }
            // Persist into session metadata to avoid mutating SDK session objects
            try { this.setSessionMeta(session, { remoteName: callerName, remoteNumber: callerNumber }); } catch (e) {  }
        } catch (e) {
        }

        // Ringtone handled via startRingtone()

        // Active Calls UI is rendered by the page-level renderActiveCalls(); do not manipulate DOM here to avoid conflicts

        // If the SDK reports this session already disposed (cancelled quickly), clean up immediately
        if (session && session.state === 'disposed') {
            // small delay so UI can briefly show then cleanup
            setTimeout(() => this.cleanupIncomingSession(session), 200);
        }

        // Start ringing timer for UI (per-session)
        try {
            session._ringingStartedAt = Date.now();
            if (session._ringingInterval) clearInterval(session._ringingInterval);
            session._ringingInterval = setInterval(() => {
                try {
                    const elapsed = Date.now() - (session._ringingStartedAt || Date.now());
                    session._ringingElapsed = elapsed;
                    // NOTE: Previously emitted 'ringingTick' here which caused an additional
                    // short tick sound via some listeners/SDKs. The main ringtone is
                    // played via startRingtone(), so avoid emitting a per-second tick
                    // to prevent duplicate sounds. Keep the elapsed bookkeeping.
                } catch (e) {}
            }, 1000);
        } catch (e) {  }

        this.dispatchEvent('incomingCall', { session });
    }

    /**
     * Cleanup incoming session UI and timers for a session
     */
    cleanupIncomingSession(session) {
        try {
            // Stop ringtone and timers
            try { this.stopRingtone(); } catch (_) {}
            try { if (session && session._ringingInterval) { clearInterval(session._ringingInterval); session._ringingInterval = null; } } catch (_) {}
            try { if (session && session._durationInterval) { clearInterval(session._durationInterval); session._durationInterval = null; } } catch (_) {}
            try { if (session && session._uiInterval) { clearInterval(session._uiInterval); session._uiInterval = null; } } catch (_) {}

            // Hide incoming UI elements if present
            try {
                const incomingAlertEl = document.getElementById('incomingCallAlert');
                const incomingFromEl = document.getElementById('incomingCallFrom');
                if (incomingAlertEl) incomingAlertEl.style.display = 'none';
                if (incomingFromEl) incomingFromEl.textContent = '';
                // Remove active call row if present
                try {
                    const id = this.getSessionElementId(session);
                    const el = document.getElementById(id);
                    if (el && el.parentNode) el.parentNode.removeChild(el);
                } catch (_) {}
            } catch (e) {  }

            // Remove from tracked sessions
            try { this.callSessions = this.callSessions.filter(s => s !== session); } catch (_) {}
            if (this.currentSession === session) this.currentSession = null;
            if (this.incomingCallSession === session) this.incomingCallSession = null;
        } catch (e) {
        }
    }

    // Helper to derive a stable DOM id for a session
    getSessionElementId(session) {
        try {
            const sid = session && (session.id || session.sessionId || session.partyId || session.remoteIdentity || session.callId);
            if (sid) return 'activeCall-' + String(sid).replace(/[^a-z0-9_-]/ig, '-');
        } catch (_) {}
        return 'activeCall-' + (session && session._generatedId ? session._generatedId : (session._generatedId = 'g' + Date.now() + '-' + Math.floor(Math.random()*9999)));
    }

    // Return a stable session key used for metadata storage (not prefixed)
    getSessionKey(session) {
        try {
            if (!session) return null;
            const sid = session.id || session.sessionId || session.callId || session.partyId || session._generatedId;
            if (sid) return String(sid);
            if (session && session._generatedId) return String(session._generatedId);
            // fallback: generate and attach
            session._generatedId = session._generatedId || ('g' + Date.now() + '-' + Math.floor(Math.random()*9999));
            return String(session._generatedId);
        } catch (e) { return null; }
    }

    // Set metadata for a session (does not mutate the SDK session object)
    setSessionMeta(session, obj) {
        try {
            if (!session || !obj) return null;
            const key = this.getSessionKey(session);
            if (!key) return null;
            this._sessionMeta[key] = this._sessionMeta[key] || {};
            Object.assign(this._sessionMeta[key], obj);
            return this._sessionMeta[key];
        } catch (e) {  return null; }
    }

    // Get metadata for a session
    getSessionMeta(session) {
        try {
            const key = this.getSessionKey(session);
            if (!key) return {};
            return this._sessionMeta[key] || {};
        } catch (e) { return {}; }
    }

    // Show a small enable-sound control so user can grant audio autoplay
    showEnableSoundPrompt() {
        try {
            // Append to body to avoid being removed by Active Calls renderer
            if (document.getElementById('rc-enable-sound')) return;
            const btn = document.createElement('button');
            btn.id = 'rc-enable-sound';
            btn.className = 'btn btn-sm btn-primary rc-enable-sound';
            btn.style.position = 'fixed';
            btn.style.right = '16px';
            btn.style.bottom = '16px';
            btn.style.zIndex = 2147483647;
            btn.textContent = 'Enable Sound';
            btn.onclick = async () => {
                try {
                    // Resume AudioContext if present
                    if (this._ringtoneCtx && typeof this._ringtoneCtx.resume === 'function') {
                        try { await this._ringtoneCtx.resume(); } catch (_) {}
                    }
                    // Try to play ringtone audio element
                    if (this.ringtoneAudio) {
                        try { await this.ringtoneAudio.play(); } catch (e) {  }
                    }
                    // remove prompt after enabling
                    try { const p = document.getElementById('rc-enable-sound'); if (p && p.parentNode) p.parentNode.removeChild(p); } catch (_) {}
                } catch (e) {  }
            };
            document.body.appendChild(btn);
        } catch (e) {  }
    }

    /**
     * Hold / unhold current call
     */
    async holdCall(hold = null) {
        if (!this.currentSession) {
            throw new Error('No active call to hold');
        }

        const session = this.currentSession;

        try {
            // Track hold state locally; some SDK versions don't expose isOnHold()
            if (typeof this.onHold === 'undefined') {
                this.onHold = false;
            }
            let currentlyOnHold = this.onHold;

            if (hold === null) {
                hold = !currentlyOnHold;
            }

            rcLog('Toggling hold:', { currentlyOnHold, newState: hold });

            if (hold && typeof session.hold === 'function') {
                await session.hold();
                this.onHold = true;
                this.dispatchEvent('holdStarted', { session });
            } else if (!hold && typeof session.unhold === 'function') {
                await session.unhold();
                this.onHold = false;
                this.dispatchEvent('holdEnded', { session });
            } else {
                return currentlyOnHold;
            }

            return this.onHold;
        } catch (error) {
            console.error('Failed to toggle hold:', error);
            throw error;
        }
    }

    /**
     * Make an outgoing call
     */
    async makeCall(phoneNumber, fromNumber = null) {
        if (!this.isInitialized) {
            throw new Error('WebPhone not initialized');
        }

        const preDialCheck = this.canMakeOutboundCall();
        if (!preDialCheck.ok) {
            this._showToast(preDialCheck.reason, 'warning');
            throw new Error(preDialCheck.reason);
        }

        let resolvedFromNumber = (fromNumber || '').trim();
        if (!resolvedFromNumber) {
            try {
                const fromSelect = document.getElementById('callFromNumber');
                const fallback = (fromSelect && fromSelect.value) ? String(fromSelect.value).trim() : '';
                if (fallback) {
                    resolvedFromNumber = fallback;
                }
            } catch (_) { }
        }

        // Registration might be async, so check if webphone exists
        if (!this.webphone) {
            throw new Error('WebPhone instance not available');
        }

        // Ensure WebPhone is started if needed
        if (this.webphone && typeof this.webphone.start === 'function' && !this.isRegistered) {
            rcLog('Starting WebPhone before making call...');
            try {
                await this.webphone.start();
                this.isRegistered = true;
        } catch (e) {
            }
        }

        const finalDialCheck = this.canMakeOutboundCall();
        if (!finalDialCheck.ok) {
            this._showToast(finalDialCheck.reason, 'warning');
            throw new Error(finalDialCheck.reason);
        }

        try {
            // Validate selected output device is still available; reset to default if missing
            try {
                if (navigator.mediaDevices && navigator.mediaDevices.enumerateDevices) {
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    const outputs = devices.filter(d => d.kind === 'audiooutput').map(d => d.deviceId);
                    if (this.currentOutputDeviceId && this.currentOutputDeviceId !== 'default' && !outputs.includes(this.currentOutputDeviceId)) {
                        this.currentOutputDeviceId = 'default';
                        if (this.remoteAudio && typeof this.remoteAudio.setSinkId === 'function') {
                            try { await this.remoteAudio.setSinkId('default'); } catch (_) {}
                        }
                    }
                }
            } catch (e) {
            }

            // Create invite - WebPhone SDK invite method takes phone number as string
            let session;

            // Try different ways to access invite method
            const userAgent = this.userAgent || this.webphone;

            rcLog('Attempting to make call:', {
                phoneNumber,
                fromNumber,
                userAgent: userAgent,
                hasInvite: typeof userAgent?.invite === 'function',
                hasUserAgent: !!this.webphone?.userAgent,
                webphoneKeys: Object.keys(this.webphone || {})
            });


        // Microphone availability check before proceeding
        await this.ensureMicrophoneAvailable();
            // Auto-hold all existing sessions before starting a new call so only the new call is active
            try {
                for (const s of this.callSessions) {
                    if (s && typeof s.hold === 'function') {
                        try { await s.hold(); s._onHold = true; } catch (e) {  }
                    }
                }
            } catch (e) {
            }

            // Try to find invite method - check multiple possible locations
            let inviteMethod = null;
            let inviteMethodName = null;

            // Method 1: userAgent.invite(phoneNumber) - most common
            if (userAgent && typeof userAgent.invite === 'function') {
                inviteMethod = userAgent.invite.bind(userAgent);
                inviteMethodName = 'userAgent.invite';
                rcLog('Found invite method: userAgent.invite()');
            }
            // Method 2: webphone.userAgent.invite(phoneNumber)
            else if (this.webphone?.userAgent && typeof this.webphone.userAgent.invite === 'function') {
                inviteMethod = this.webphone.userAgent.invite.bind(this.webphone.userAgent);
                inviteMethodName = 'webphone.userAgent.invite';
                rcLog('Found invite method: webphone.userAgent.invite()');
            }
            // Method 3: webphone.invite(phoneNumber)
            else if (this.webphone && typeof this.webphone.invite === 'function') {
                inviteMethod = this.webphone.invite.bind(this.webphone);
                inviteMethodName = 'webphone.invite';
                rcLog('Found invite method: webphone.invite()');
            }
            // Method 4: Check sipClient (SIP client might have invite)
            else if (this.webphone?.sipClient && typeof this.webphone.sipClient.invite === 'function') {
                inviteMethod = this.webphone.sipClient.invite.bind(this.webphone.sipClient);
                inviteMethodName = 'webphone.sipClient.invite';
                rcLog('Found invite method: webphone.sipClient.invite()');
            }
            // Method 5: Check if there's a makeCall or call method
            else if (this.webphone && typeof this.webphone.makeCall === 'function') {
                inviteMethod = this.webphone.makeCall.bind(this.webphone);
                inviteMethodName = 'webphone.makeCall';
                rcLog('Found invite method: webphone.makeCall()');
            }
            else if (this.webphone && typeof this.webphone.call === 'function') {
                inviteMethod = this.webphone.call.bind(this.webphone);
                inviteMethodName = 'webphone.call';
                rcLog('Found invite method: webphone.call()');
            }
            // Method 6: Check sipClient.userAgent (some versions nest userAgent inside sipClient)
            else if (this.webphone?.sipClient?.userAgent && typeof this.webphone.sipClient.userAgent.invite === 'function') {
                inviteMethod = this.webphone.sipClient.userAgent.invite.bind(this.webphone.sipClient.userAgent);
                inviteMethodName = 'webphone.sipClient.userAgent.invite';
                rcLog('Found invite method: webphone.sipClient.userAgent.invite()');
            }
            // Method 7: Search all properties including non-enumerable
            else if (this.webphone && typeof this.webphone === 'object') {
                // Get all own properties (including non-enumerable)
                const allProps = Object.getOwnPropertyNames(this.webphone);
                for (const prop of allProps) {
                    if ((prop.toLowerCase().includes('invite') || prop.toLowerCase().includes('call') || prop.toLowerCase().includes('dial')) &&
                        typeof this.webphone[prop] === 'function') {
                        inviteMethod = this.webphone[prop].bind(this.webphone);
                        inviteMethodName = `webphone.${prop}`;
                        rcLog(`Found invite method: webphone.${prop}()`);
                        break;
                    }
                }

                // Also check sipClient properties if not found
                if (!inviteMethod && this.webphone.sipClient) {
                    const sipClientProps = Object.getOwnPropertyNames(this.webphone.sipClient);
                    for (const prop of sipClientProps) {
                        if ((prop.toLowerCase().includes('invite') || prop.toLowerCase().includes('call') || prop.toLowerCase().includes('dial')) &&
                            typeof this.webphone.sipClient[prop] === 'function') {
                            inviteMethod = this.webphone.sipClient[prop].bind(this.webphone.sipClient);
                            inviteMethodName = `webphone.sipClient.${prop}`;
                            rcLog(`Found invite method: webphone.sipClient.${prop}()`);
                            break;
                        }
                    }

                    // Check sipClient.userAgent if it exists
                    if (!inviteMethod && this.webphone.sipClient.userAgent) {
                        const userAgentProps = Object.getOwnPropertyNames(this.webphone.sipClient.userAgent);
                        for (const prop of userAgentProps) {
                            if ((prop.toLowerCase().includes('invite') || prop.toLowerCase().includes('call') || prop.toLowerCase().includes('dial')) &&
                                typeof this.webphone.sipClient.userAgent[prop] === 'function') {
                                inviteMethod = this.webphone.sipClient.userAgent[prop].bind(this.webphone.sipClient.userAgent);
                                inviteMethodName = `webphone.sipClient.userAgent.${prop}`;
                                rcLog(`Found invite method: webphone.sipClient.userAgent.${prop}()`);
                                break;
                            }
                        }
                    }
                }
            }

            if (!inviteMethod) {
                // Debug: log available methods for troubleshooting
                const debugInfo = {
                    userAgentType: typeof userAgent,
                    userAgentMethods: userAgent ? Object.keys(userAgent).filter(k => typeof userAgent[k] === 'function').slice(0, 20) : [],
                    webphoneType: typeof this.webphone,
                    webphoneMethods: this.webphone ? Object.keys(this.webphone).filter(k => typeof this.webphone[k] === 'function').slice(0, 20) : [],
                    webphoneUserAgentMethods: this.webphone?.userAgent ? Object.keys(this.webphone.userAgent).filter(k => typeof this.webphone.userAgent[k] === 'function').slice(0, 20) : [],
                    webphoneKeys: this.webphone ? Object.keys(this.webphone).slice(0, 30) : []
                };
                console.error('Invite method not found. Debug info:', debugInfo);

                // Try to get all properties including non-enumerable and inherited
                if (this.webphone) {
                    const allProps = [];
                    let obj = this.webphone;
                    do {
                        allProps.push(...Object.getOwnPropertyNames(obj));
                    } while (obj = Object.getPrototypeOf(obj));
                    const callRelatedProps = allProps.filter(p =>
                        p.toLowerCase().includes('invite') ||
                        p.toLowerCase().includes('call') ||
                        p.toLowerCase().includes('session') ||
                        p.toLowerCase().includes('dial')
                    );
                    rcLog('All WebPhone properties related to calls:', callRelatedProps);
                    rcLog('Full WebPhone object structure:', this.webphone);
                }

                throw new Error('Invite method not available. WebPhone may not be properly initialized. Check console for debug info.');
            }

            // Format phone number to E.164 format (required by R-Dialer )
            let cleanPhoneNumber = this.formatPhoneNumber(phoneNumber);

            rcLog('Calling with formatted phone number (E.164):', cleanPhoneNumber);

            const callOptions = {};
            if (resolvedFromNumber) {
                callOptions.fromNumber = resolvedFromNumber;
                callOptions.callerId = resolvedFromNumber;
            }

            // Call invite method with phone number
            // The call() method returns a Promise, so we need to await it
            this._localOutboundDialingUntil = Date.now() + 15000;
            try {
                // Try calling with phone number string (E.164 format)
                if (resolvedFromNumber) {
                    if (inviteMethodName === 'webphone.call' || inviteMethodName === 'webphone.makeCall') {
                        session = inviteMethod(cleanPhoneNumber, resolvedFromNumber, callOptions);
                    } else {
                        session = inviteMethod(cleanPhoneNumber, callOptions);
                    }
                } else {
                    session = inviteMethod(cleanPhoneNumber);
                }

                // If it returns a Promise, await it
                if (session && typeof session.then === 'function') {
                    rcLog('Call method returned Promise, awaiting resolution...');
                    session = await session;
                    rcLog('Call session resolved:', session);
                }
            } catch (e1) {
                // Try with options object if direct call fails
                rcLog('Direct phone number call failed, trying with options object:', e1.message);
                try {
                    session = inviteMethod({
                        phoneNumber: cleanPhoneNumber,
                        fromNumber: resolvedFromNumber || null,
                        callerId: resolvedFromNumber || null
                    });

                    // If it returns a Promise, await it
                    if (session && typeof session.then === 'function') {
                        session = await session;
                    }
                } catch (e2) {
                    console.error('Both call methods failed:', e2);
                    throw new Error(`Failed to initiate call: ${e2.message}`);
                }
            }

            if (!session) {
                throw new Error('Call returned null or undefined');
            }

            try {
                session._rcOriginatedLocally = true;
                session._rcDirection = 'outbound';
                this._localOutboundDialingUntil = Date.now() + 5000;
                this.setSessionMeta(session, {
                    direction: 'outbound',
                    originatedLocally: true
                });
            } catch (_) { }

            rcLog('Call session created:', {
                session: session,
                sessionType: typeof session,
                hasId: !!session.id,
                hasSessionId: !!session.sessionId,
                sessionKeys: Object.keys(session || {}).slice(0, 15),
                sessionState: session.state || session.status || 'unknown',
                sessionDirection: session.direction || 'unknown'
            });

            // Check if session has a state property and log it
            if (session.state) {
                rcLog('Call session state:', session.state);
            }
            if (session.status) {
                rcLog('Call session status:', session.status);
            }
            if (session.direction) {
                rcLog('Call session direction:', session.direction);
            }

            this.currentSession = session;
            // Track and attach listeners
            if (!this.callSessions.includes(session)) {
                this.callSessions.push(session);
            }
            this.attachSessionListeners(session);

            // Log session events that are available
            if (session.on) {
                rcLog('Session event listeners attached. Waiting for call events...');
            }

            this.dispatchEvent('callStarted', { session, phoneNumber });

            return session;

        } catch (error) {
            console.error('Failed to make call:', error);
            this._localOutboundDialingUntil = 0;
            try {
                const msg = String(error?.message || error || '');
                if (/WebSocket is already in CLOSING or CLOSED state/i.test(msg)) {
                    this._updateDialBlockState('R-Dialer  connection is reconnecting after a WebSocket close.');
                    this._showToast('Connection dropped while dialing. Reconnecting...', 'warning');
                    await this._refreshTokensSilently('outbound-call-websocket-closed');
                }
            } catch (_) { }
            this.dispatchEvent('callError', { error: error.message });
            throw error;
        }
    }

    /**
     * Ensure a microphone exists and permission is granted
     */
    async ensureMicrophoneAvailable() {
        const mediaDevices = navigator.mediaDevices;
        const getUserMedia = mediaDevices && typeof mediaDevices.getUserMedia === 'function';
        const enumerateDevices = mediaDevices && typeof mediaDevices.enumerateDevices === 'function';

        if (!getUserMedia) {
            const isLocalHost =
                window.location.hostname === 'localhost'
                || window.location.hostname === '127.0.0.1'
                || window.location.hostname === '::1';
            if (!window.isSecureContext && !isLocalHost) {
                throw new Error('Microphone requires HTTPS. Open this portal on a secure origin (for example https://wash2.test).');
            }
            throw new Error('Microphone access is not supported in this browser');
        }

        // Confirm at least one audioinput device exists
        if (enumerateDevices) {
            const devices = await mediaDevices.enumerateDevices();
            const hasMic = devices.some(d => d.kind === 'audioinput');
            if (!hasMic) {
                throw new Error('No microphone available. Please connect a mic.');
            }
        }

        // Request permission to ensure access; stop tracks immediately
        try {
            const stream = await mediaDevices.getUserMedia({ audio: true });
            stream.getTracks().forEach(t => t.stop());
        } catch (e) {
            const reason = String(e && (e.name || e.message) || '').toLowerCase();
            if (reason.includes('notallowed') || reason.includes('permission')) {
                throw new Error('Microphone permission denied. Please allow mic access.');
            }
            if (reason.includes('notfound') || reason.includes('devicesnotfound')) {
                throw new Error('No microphone available. Please connect a mic.');
            }
            if (reason.includes('notreadable')) {
                throw new Error('Microphone is currently in use by another app. Close other apps and try again.');
            }
            if (reason.includes('security') || reason.includes('insecure') || reason.includes('secure')) {
                throw new Error('Microphone requires HTTPS. Open this portal on a secure origin (for example https://wash2.test).');
            }
            throw new Error('Unable to access microphone. Please check browser microphone settings.');
        }
    }

    /**
     * Return shallow info for all sessions
     */
    listSessions() {
        try { this.recoverSessionsFromSdk({ emitIncomingEvent: false }); } catch (_) { }
        const cleanupEndedRegistry = () => {
            try {
                const registry = window._rcEndedSessionIds;
                if (!registry || typeof registry !== 'object') return;
                const now = Date.now();
                Object.keys(registry).forEach((id) => {
                    const ts = Number(registry[id] || 0);
                    if (!ts || (now - ts) > (30 * 60 * 1000)) delete registry[id];
                });
            } catch (_) { }
        };
        const isEndedByRegistry = (entry) => {
            try {
                cleanupEndedRegistry();
                const registry = window._rcEndedSessionIds || {};
                const ids = [entry?.id, entry?.sessionId, entry?.callId, entry?.partyId, entry?.index]
                    .filter((v) => v !== undefined && v !== null && String(v) !== '')
                    .map((v) => String(v));
                return ids.some((id) => !!registry[id]);
            } catch (_) {
                return false;
            }
        };

        const raw = this.callSessions.map((s, idx) => {
            const meta = this.getSessionMeta(s) || {};
            const participants = Array.isArray(meta.participants)
                ? meta.participants.filter(Boolean)
                : [];
            const participantCount = Number(meta.participantCount || participants.length || 0);
            return {
                index: idx,
                id: s.id || s.sessionId || s.session_id || s.callId || s.partyId || s.callSessionId || idx,
                sessionId: s.sessionId || s.session_id || s.id || s.callId || s.partyId || null,
                callId: s.callId || s.call_id || null,
                partyId: s.partyId || s.party_id || null,
                state: meta.state || s.state || s.status,
                direction: meta.direction || s.direction,
                localNumber: meta.localNumber || s.localNumber || s.fromNumber || s.toNumber || null,
                remoteNumber: meta.remoteNumber || s.remoteNumber || s.toNumber || s.fromNumber || null,
                remoteName: meta.remoteName || s.remoteName || null,
                fromNumber: meta.fromNumber || s.fromNumber || s.localNumber || null,
                toNumber: meta.toNumber || s.toNumber || s.remoteNumber || null,
                onHold: !!s._onHold,
                muted: (typeof s._muted !== 'undefined') ? !!s._muted : (typeof s.muted === 'function' ? !!s.muted() : !!s.muted),
                startedAt: meta.startedAt || s._startedAt || null,
                participants: participants.length ? participants : null,
                participantCount: participantCount > 0 ? participantCount : null,
                conferenceSessionId: meta.conferenceApiSessionId || meta.telephonySessionId || s.telephonySessionId || null
            };
        }).filter((entry) => !isEndedByRegistry(entry));

        const toMillis = (value) => {
            if (!value) return 0;
            if (value instanceof Date) return value.getTime();
            if (typeof value === 'number') return value < 1e12 ? value * 1000 : value;
            const parsed = Date.parse(String(value));
            return Number.isNaN(parsed) ? 0 : parsed;
        };
        const rank = (entry) => {
            const state = String(entry?.state || '').toLowerCase();
            if (entry?.onHold === true || /hold|held/.test(state)) return 5;
            if (/active|connected|established|confirmed|answered/.test(state)) return 4;
            if (/trying|dialing|progress|proceeding/.test(state)) return 3;
            if (/incoming|ringing|alerting|early|invite|pending|offering/.test(state)) return 2;
            if (/disposed|terminated|ended|disconnected|failed|rejected|cancelled|canceled/.test(state)) return 0;
            return 1;
        };
        const keyOf = (entry, idx) => {
            const key = entry.callId || entry.sessionId || entry.id || entry.partyId;
            return key ? String(key) : `idx-${idx}`;
        };

        const deduped = new Map();
        raw.forEach((entry, idx) => {
            const key = keyOf(entry, idx);
            const existing = deduped.get(key);
            if (!existing) {
                deduped.set(key, entry);
                return;
            }

            const existingRank = rank(existing);
            const nextRank = rank(entry);
            if (nextRank > existingRank) {
                deduped.set(key, entry);
                return;
            }

            if (nextRank === existingRank) {
                const existingTime = toMillis(existing.startedAt);
                const nextTime = toMillis(entry.startedAt);
                if (nextTime > existingTime) deduped.set(key, entry);
            }
        });

        return Array.from(deduped.values());
    }

    /**
     * Find a session by id or return the session if object provided
     */
    findSession(sessionOrId) {
        if (!sessionOrId) return null;
        if (typeof sessionOrId === 'object') return sessionOrId;
        try { this.recoverSessionsFromSdk({ emitIncomingEvent: false }); } catch (_) { }
        const target = String(sessionOrId);
        // Try direct id or sessionId match first
        const byId = this.callSessions.find(s => {
            const keys = [
                s && s.id,
                s && s.sessionId,
                s && s.session_id,
                s && s.callId,
                s && s.call_id,
                s && s.partyId,
                s && s.party_id,
                s && s.callSessionId
            ].filter(Boolean).map(v => String(v));
            return keys.includes(target);
        });
        if (byId) return byId;
        // Fallback: treat provided value as index into callSessions
        const idx = Number(sessionOrId);
        if (!Number.isNaN(idx) && idx >= 0 && idx < this.callSessions.length) {
            return this.callSessions[idx];
        }
        return null;
    }

    getSessionIdCandidates(session) {
        if (!session) return [];
        const values = [
            session.id,
            session.sessionId,
            session.session_id,
            session.callId,
            session.call_id,
            session.partyId,
            session.party_id,
            session.callSessionId
        ]
            .filter(v => v !== null && typeof v !== 'undefined' && v !== '')
            .map(v => String(v));
        return Array.from(new Set(values));
    }

    isSessionTerminated(session) {
        const state = String(session?.state || session?.status || '').toLowerCase();
        return ['terminated', 'disposed', 'ended', 'failed', 'rejected', 'cancelled', 'canceled'].includes(state);
    }

    isLocalOutboundDialingActive() {
        return Date.now() < Number(this._localOutboundDialingUntil || 0);
    }

    hasInboundDirection(session) {
        const direction = String(session?.direction || session?.callDirection || '').toLowerCase();
        return direction.includes('inbound') || direction.includes('incoming');
    }

    hasLocalOutboundSessionActive() {
        try {
            return (this.callSessions || []).some((session) => {
                if (!session || this.isSessionTerminated(session)) return false;
                const direction = String(session.direction || session.callDirection || session._rcDirection || '').toLowerCase();
                return session._rcOriginatedLocally === true
                    || direction.includes('outbound')
                    || direction.includes('outgoing');
            });
        } catch (_) {
            return false;
        }
    }

    isIncomingSessionLike(session) {
        if (!session) return false;
        const direction = String(session.direction || session.callDirection || '').toLowerCase();
        const state = String(session.state || session.status || '').toLowerCase();
        const incomingStates = ['ringing', 'incoming', 'alerting', 'early', 'invite', 'pending', 'offering'];
        const acceptedStates = ['active', 'connected', 'established', 'confirmed', 'hold', 'held', 'answered'];
        if (this.isLocalOutboundDialingActive() && !this.hasInboundDirection(session)) return false;
        if (this.hasLocalOutboundSessionActive() && !this.hasInboundDirection(session)) return false;
        if (session._rcOriginatedLocally === true) return false;
        if (direction.includes('outbound') || direction.includes('outgoing')) return false;
        if (this.isSessionTerminated(session)) return false;
        if (session.hold === true || session.onHold === true || session._onHold === true) return false;
        if (acceptedStates.some(flag => state.includes(flag))) return false;
        if (incomingStates.some(flag => state.includes(flag))) return true;
        if ((direction.includes('inbound') || direction.includes('incoming')) && !state) return true;
        return false;
    }

    isSessionLikeCandidate(session) {
        if (!session || typeof session !== 'object') return false;
        const hasIdHint = !!(session.id || session.sessionId || session.session_id || session.callId || session.partyId || session.callSessionId);
        const hasStateHint = (typeof session.state !== 'undefined') || (typeof session.status !== 'undefined');
        const hasDirectionHint = (typeof session.direction !== 'undefined') || (typeof session.callDirection !== 'undefined');
        const hasCallMethods = ['accept', 'answer', 'reject', 'hangup', 'terminate', 'bye'].some((name) => typeof session[name] === 'function');
        const hasEventApi = typeof session.on === 'function' || typeof session.addListener === 'function';
        return hasCallMethods || (hasEventApi && (hasIdHint || hasStateHint || hasDirectionHint));
    }

    collectSessionsFromCandidate(candidate) {
        if (!candidate) return [];
        if (Array.isArray(candidate)) return candidate.filter((entry) => this.isSessionLikeCandidate(entry));
        if (candidate instanceof Map) return Array.from(candidate.values()).filter((entry) => this.isSessionLikeCandidate(entry));
        if (candidate instanceof Set) return Array.from(candidate.values()).filter((entry) => this.isSessionLikeCandidate(entry));
        if (typeof candidate === 'object') {
            if (this.isSessionLikeCandidate(candidate)) return [candidate];
            const values = Object.values(candidate || {});
            const hasSessionLikeValue = values.some((entry) => this.isSessionLikeCandidate(entry));
            if (hasSessionLikeValue) {
                return values.filter((entry) => this.isSessionLikeCandidate(entry));
            }
            return [];
        }
        return [];
    }

    recoverSessionsFromSdk(options = {}) {
        const emitIncomingEvent = options.emitIncomingEvent !== false;
        if (!this.webphone) return this.callSessions || [];

        const discovered = [];
        const sources = [
            this.currentSession,
            this.incomingCallSession,
            this.webphone && this.webphone.sessions,
            this.webphone && this.webphone._sessions,
            this.webphone && this.webphone.sessionList,
            this.webphone && this.webphone.calls,
            this.webphone && this.webphone.activeCalls,
            this.webphone && this.webphone.session,
            this.webphone && this.webphone.sipClient && this.webphone.sipClient.sessions,
            this.webphone && this.webphone.sipClient && this.webphone.sipClient._sessions,
            this.webphone && this.webphone.sipClient && this.webphone.sipClient.calls,
            this.webphone && this.webphone.userAgent && this.webphone.userAgent.sessions,
            this.userAgent && this.userAgent.sessions
        ];

        sources.forEach((source) => {
            const sessions = this.collectSessionsFromCandidate(source);
            sessions.forEach((session) => {
                if (!session || typeof session !== 'object') return;
                if (this.isSessionTerminated(session)) return;
                discovered.push(session);
            });
        });

        let added = 0;
        discovered.forEach((session) => {
            const incomingIds = this.getSessionIdCandidates(session);
            const exists = this.callSessions.some((tracked) => {
                if (tracked === session) return true;
                const trackedIds = this.getSessionIdCandidates(tracked);
                return incomingIds.length > 0 && trackedIds.some((id) => incomingIds.includes(id));
            });

            if (!exists) {
                this.callSessions.push(session);
                this.attachSessionListeners(session);
                added += 1;
            } else {
                this.attachSessionListeners(session);
            }

            if (emitIncomingEvent && this.isIncomingSessionLike(session)) {
                const meta = this.getSessionMeta(session) || {};
                if (!meta.recoveredIncomingEmitted) {
                    this.setSessionMeta(session, { recoveredIncomingEmitted: true });
                    this.dispatchEvent('incomingCall', { session, recovered: true });
                }
            }
        });

        if (!this.currentSession && this.callSessions.length > 0) {
            this.currentSession = this.callSessions[this.callSessions.length - 1];
        }

        if (added > 0) {
            rcLog('[Session Recovery] Added sessions from SDK internals:', added);
        }

        return this.callSessions;
    }

    startSessionRecoveryLoop() {
        if (this._sessionRecoveryInterval) return;
        this._sessionRecoveryInterval = setInterval(() => {
            try {
                if (!this.isInitialized) return;
                this.recoverSessionsFromSdk({ emitIncomingEvent: true });
            } catch (_) { }
        }, 1500);
    }

    stopSessionRecoveryLoop() {
        if (this._sessionRecoveryInterval) {
            clearInterval(this._sessionRecoveryInterval);
            this._sessionRecoveryInterval = null;
        }
    }

    isDtmfReadySession(session) {
        if (!session || typeof session.sendDtmf !== 'function') return false;
        if (this.isSessionTerminated(session)) return false;
        if (this.isIncomingSessionLike(session)) return false;

        const state = String(session.state || session.status || '').toLowerCase();
        const isHeld = session.hold === true
            || session.onHold === true
            || session._onHold === true
            || state.includes('hold')
            || state.includes('held');
        if (isHeld) return false;

        const activeStates = ['active', 'connected', 'established', 'confirmed', 'inprogress', 'answered'];
        const hasActiveState = activeStates.some((flag) => state.includes(flag));
        const hasStarted = !!(session._startedAt || session.startedAt || session._durationElapsed);

        return hasActiveState || hasStarted;
    }

    resolveDtmfSession(sessionOrId = null) {
        try { this.recoverSessionsFromSdk({ emitIncomingEvent: false }); } catch (_) { }

        const candidates = [];
        const addCandidate = (session) => {
            if (!session) return;
            if (typeof session !== 'object') {
                session = this.findSession(session);
            }
            if (!session) return;
            if (!candidates.includes(session)) candidates.push(session);
        };

        addCandidate(sessionOrId);

        try {
            if (typeof window !== 'undefined' && window._dialerCurrentSessionId) {
                addCandidate(window._dialerCurrentSessionId);
            }
        } catch (_) { }

        addCandidate(this.currentSession);

        try {
            (this.callSessions || []).forEach(addCandidate);
        } catch (_) { }

        return candidates.find((session) => this.isDtmfReadySession(session)) || null;
    }

    hasDtmfTarget(sessionOrId = null) {
        return !!this.resolveDtmfSession(sessionOrId);
    }

    sendDtmf(tones, sessionOrId = null, options = {}) {
        const normalizedTones = String(tones || '').trim();
        if (!/^[0-9*#]+$/.test(normalizedTones)) {
            throw new Error('Invalid DTMF tone. Use digits 0-9, * or #.');
        }

        const session = this.resolveDtmfSession(sessionOrId);
        if (!session) {
            throw new Error('No connected WebPhone call is available for keypad tones.');
        }
        if (typeof session.sendDtmf !== 'function') {
            throw new Error('This call session does not support keypad tones.');
        }

        const duration = Number.isFinite(Number(options.duration)) ? Number(options.duration) : 160;
        const interToneGap = Number.isFinite(Number(options.interToneGap)) ? Number(options.interToneGap) : 80;

        session.sendDtmf(normalizedTones, duration, interToneGap);
        this.dispatchEvent('dtmfSent', { session, tones: normalizedTones, duration, interToneGap });
        rcLog('DTMF tone sent:', normalizedTones);
        return true;
    }

    /**
     * Toggle hold for a specific session (or current if omitted)
     */
    async toggleHold(sessionOrId = null) {
        const session = sessionOrId ? this.findSession(sessionOrId) : this.currentSession;
        if (!session) throw new Error('No target session to hold/unhold');
        // naive tracking
        if (typeof session._onHold === 'undefined') session._onHold = false;
        const toHold = !session._onHold;
        if (toHold && typeof session.hold === 'function') {
            await session.hold();
            session._onHold = true;
            this.dispatchEvent('holdStarted', { session });
        } else if (!toHold && typeof session.unhold === 'function') {
            await session.unhold();
            session._onHold = false;
            this.dispatchEvent('holdEnded', { session });
        }
        return session._onHold;
    }

    /**
     * Swap to target session: hold current, unhold target
     */
    async swapToSession(sessionOrId) {
        const target = this.findSession(sessionOrId);
        if (!target) throw new Error('Target session not found');
        const current = this.currentSession;
        // Hold current if exists, different, and not already on hold
        if (current && current !== target && typeof current.hold === 'function') {
            const isOnHold = current._onHold || current.onHold || current.hold === true;
            if (!isOnHold) {
                try { await current.hold(); current._onHold = true; } catch (e) {  }
            }
        }
        // Unhold target
        if (typeof target.unhold === 'function') {
            const isTargetOnHold = target._onHold || target.onHold || target.hold === true;
            if (isTargetOnHold) {
                try { await target.unhold(); target._onHold = false; } catch (e) {  }
            }
        }
        // Ensure all other sessions are held so only target is active
        try {
            for (const s of this.callSessions) {
                if (s && s !== target && typeof s.hold === 'function') {
                    const sOnHold = s._onHold || s.onHold || s.hold === true;
                    if (!sOnHold) {
                        try { await s.hold(); s._onHold = true; } catch (e) { /* ignore */ }
                    }
                }
            }
        } catch (_) {}
        this.currentSession = target;
        this.dispatchEvent('swapped', { to: target, from: current });
        return true;
    }

    /**
     * Change output device for a specific session using SDK's changeOutputDevice
     */
    async changeOutputDeviceForSession(sessionOrId, deviceId) {
        const session = this.findSession(sessionOrId) || this.currentSession;
        if (!session) throw new Error('No session available to change output');
        if (typeof session.changeOutputDevice !== 'function') {
            throw new Error('Session does not support changeOutputDevice');
        }
        await session.changeOutputDevice(deviceId);
        this.currentOutputDeviceId = deviceId;
        return true;
    }

    /**
     * Merge current session with target session (conference), if supported by SDK
     */
    async mergeWith(sessionOrId) {
        let target = this.findSession(sessionOrId);
        let current = this.currentSession;

        // Build a candidate list from tracked sessions and exclude likely ended sessions.
        const candidates = (this.callSessions || []).filter((s) => {
            if (!s) return false;
            const state = String(s.state || s.status || '').toLowerCase();
            return !['terminated', 'ended', 'failed', 'rejected', 'disposed'].includes(state);
        });

        // If target cannot be resolved by id, use first available candidate.
        if (!target && candidates.length > 0) {
            target = candidates[0];
        }

        // currentSession can be stale/null after UI interactions; recover from candidates.
        if (!current || current === target) {
            current = candidates.find((s) => s !== target) || current;
        }

        // If target equals current, try to pick another distinct candidate.
        if (target && current && target === current) {
            const alternate = candidates.find((s) => s !== current);
            if (alternate) target = alternate;
        }

        if (!current || !target || current === target) {
            throw new Error(`Two active calls are required to merge (resolved: ${candidates.length}, current: ${!!current}, target: ${!!target})`);
        }

        try {
            rcLog('[MERGE-WITH] Attempting merge of sessions...');

            // Try common merge/conference methods
            if (typeof current.merge === 'function') {
                rcLog('[MERGE-WITH] Found native merge() method');
                await current.merge(target);
                this.dispatchEvent('merged', { sessions: [current, target] });
                return true;
            }
            if (this.webphone && typeof this.webphone.mergeSessions === 'function') {
                rcLog('[MERGE-WITH] Found webphone.mergeSessions() method');
                await this.webphone.mergeSessions(current, target);
                this.dispatchEvent('merged', { sessions: [current, target] });
                return true;
            }
            if (typeof current.conference === 'function') {
                rcLog('[MERGE-WITH] Found native conference() method');
                await current.conference(target);
                this.dispatchEvent('merged', { sessions: [current, target] });
                return true;
            }
            if (this.userAgent && typeof this.userAgent.mergeSessions === 'function') {
                rcLog('[MERGE-WITH] Found userAgent.mergeSessions() method');
                await this.userAgent.mergeSessions(current, target);
                this.dispatchEvent('merged', { sessions: [current, target] });
                return true;
            }

            // Some SDKs may support refer/add methods
            if (typeof current.add === 'function') {
                rcLog('[MERGE-WITH] Found native add() method');
                await current.add(target);
                this.dispatchEvent('merged', { sessions: [current, target] });
                return true;
            }
            if (typeof current.refer === 'function') {
                rcLog('[MERGE-WITH] Found native refer() method');
                await current.refer(target);
                this.dispatchEvent('merged', { sessions: [current, target] });
                return true;
            }

            // Fallback: use backend telephony conference API for SDK builds
            // that do not expose native merge/conference methods.
            rcLog('[MERGE-WITH] No native merge methods found, attempting backend conference fallback...');
            const merged = await this.mergeViaConference(current, target);
            if (merged) {
                rcLog('[MERGE-WITH] Backend merge succeeded with source:', merged.source);
                this.dispatchEvent('merged', { sessions: [current, target], source: merged.source });
                return true;
            }

            // If none of the above are available, report not supported
            this.dispatchEvent('mergeNotSupported', { reason: 'SDK does not expose merge/conference methods in this build' });
            throw new Error('Merge/conference not supported by this WebPhone SDK build');
        } catch (e) {
            rcLog('[MERGE-WITH] Merge attempt failed:', e.message);
            console.error('Merge attempt failed:', e);
            this.dispatchEvent('mergeFailed', { error: e.message, details: e });
            throw e;
        }
    }

    /**
     * Merge two active calls by creating a telephony conference and bringing both parties in.
     */
    async mergeViaConference(currentSessionOrId, targetSessionOrId) {
        await this.ensureMicrophoneAvailable();

        const current = this.findSession(currentSessionOrId);
        const target = this.findSession(targetSessionOrId);
        if (!current || !target) {
            throw new Error('Cannot merge: missing active sessions');
        }
        if (current === target) {
            throw new Error('Cannot merge the same session');
        }

        rcLog('[MERGE] Creating telephony conference...');
        const conferenceResponse = await this.createTelephonyConference();
        const conferenceSession =
            conferenceResponse?.session
            || conferenceResponse?.data?.session
            || null;
        const conferenceSessionId = conferenceSession?.id || null;
        const voiceCallToken = conferenceSession?.voiceCallToken || null;
        const conferenceTarget = voiceCallToken
            ? (String(voiceCallToken).startsWith('conf_') ? String(voiceCallToken) : `conf_${voiceCallToken}`)
            : null;

        rcLog('[MERGE] Conference created:', { conferenceSessionId, voiceCallToken, conferenceTarget });
        console.info('[MERGE DEBUG] Conference create response', {
            conferenceSessionId,
            voiceCallToken,
            conferenceTarget
        });

        if (!conferenceSessionId) {
            throw new Error('Conference API returned no conference session id');
        }

        this.currentTelephonyConferenceId = conferenceSessionId;
        if (voiceCallToken) {
            this.currentConferenceVoiceCallToken = voiceCallToken;
        }
        if (conferenceTarget) {
            this.currentConferenceTarget = conferenceTarget;
        }

        // Join conference host leg before bring-in to match demo behavior.
        let conferenceHostSession = null;
        let hostJoinResult = null;
        if (conferenceTarget) {
            hostJoinResult = await this.joinConferenceHostLeg(conferenceTarget, conferenceSessionId, {
                context: 'merge',
                timeoutMs: 8000
            });
            conferenceHostSession = hostJoinResult?.session || null;
            if (!conferenceHostSession) {
                console.info('[MERGE DEBUG] Conference host invite did not complete', {
                    message: hostJoinResult?.lastError || 'No active conference host leg detected'
                });
            }
        } else {
            console.info('[MERGE DEBUG] Conference voiceCallToken missing; bring-in fallback target unavailable', {
                conferenceSessionId
            });
        }

        // If host join flow already confirmed readiness, skip another long readiness wait.
        const conferenceReadyCheck = (hostJoinResult?.ready === true)
            ? (hostJoinResult?.readiness || {
                ready: true,
                conferenceSessionId,
                activePartyCount: 1,
                source: 'host-join-confirmed'
            })
            : await this.waitForConferenceSessionReady(conferenceSessionId, {
                timeoutMs: 6000,
                pollMs: 750
            });
        console.info('[MERGE DEBUG] Conference host readiness check', conferenceReadyCheck);
        if (!conferenceReadyCheck?.ready) {
            throw new Error('Conference host leg is not established (conference session has no active parties). Bring-in cannot proceed.');
        }

        let currentRef = this.extractBringInIdentifiers(current);
        let targetRef = this.extractBringInIdentifiers(target);

        const unresolvedBeforeFallback = (!currentRef.callSessionId || !currentRef.partyId)
            || (!targetRef.callSessionId || !targetRef.partyId);
        if (unresolvedBeforeFallback) {
            try {
                const resolved = await this.resolveBringInIdentifiersFromCallControl(current, target, currentRef, targetRef);
                currentRef = resolved.currentRef;
                targetRef = resolved.targetRef;
                rcLog('[MERGE] Identifier fallback via active-call lookup result:', { currentRef, targetRef });
            } catch (fallbackErr) {
                rcLog('[MERGE] Identifier fallback via active-call lookup failed:', fallbackErr?.message || fallbackErr);
            }
        }

        try {
            const activeParties = await this.fetchActiveCallControlParties();
            console.info('[MERGE DEBUG] Active call-control parties', activeParties.map((p) => ({
                callSessionId: p?.callSessionId || null,
                partyId: p?.partyId || null,
                ownerExtensionId: p?.ownerExtensionId || null,
                source: p?.source || null
            })));
        } catch (partyLookupErr) {
            console.info('[MERGE DEBUG] Active call-control lookup failed', {
                message: partyLookupErr?.message || String(partyLookupErr || '')
            });
        }

        const missing = [];
        if (!currentRef.callSessionId || !currentRef.partyId) missing.push('current call identifiers');
        if (!targetRef.callSessionId || !targetRef.partyId) missing.push('target call identifiers');
        if (missing.length > 0) {
            throw new Error(`Unable to resolve ${missing.join(' and ')} for conference bring-in`);
        }

        rcLog('[MERGE] Conference identifiers extracted:', { currentRef, targetRef });

        let mergedSource = 'backend-bring-in';
        try {
            rcLog('[MERGE] Attempting bring-in for current call...');
            await this.bringSessionIntoConference(conferenceSessionId, currentRef);
            rcLog('[MERGE] Current call brought in successfully');
            rcLog('[MERGE] Attempting bring-in for target call...');
            await this.bringSessionIntoConference(conferenceSessionId, targetRef);
            rcLog('[MERGE] Target call brought in successfully');
        } catch (bringErr) {
            const message = String(bringErr?.message || bringErr || '');
            rcLog('[MERGE] Bring-in error caught:', { errorMessage: message });
            const isBringInRestricted = /\b403\b|\b404\b|forbidden|TAS-106|CMN-102|operation is not allowed|sessionId\] is not found/i.test(message);
            rcLog('[MERGE] Is bring-in restricted?', isBringInRestricted);

            if (!isBringInRestricted) {
                rcLog('[MERGE] Not a 403 error, re-throwing:', bringErr);
                throw bringErr;
            }

            // Fallback for accounts where bring-in is restricted: transfer each
            // active call into the conference SIP URI.
            rcLog('[MERGE] Bring-in forbidden (403), attempting transfer fallback...');
            if (!conferenceTarget) {
                throw new Error(`Bring-in forbidden (403) and conference token missing for transfer fallback: ${message}`);
            }

            rcLog('[MERGE] Conference target:', conferenceTarget);
            console.info('[MERGE DEBUG] Entering transfer fallback', {
                conferenceSessionId,
                conferenceTarget,
                currentRef,
                targetRef
            });

            try {
                rcLog('[MERGE] Transferring current call to conference...');
                await this.transferSessionToConferenceTarget(current, conferenceTarget, currentRef);
                rcLog('[MERGE] Current call transferred successfully');
            } catch (transferErr) {
                rcLog('[MERGE] ERROR transferring current call:', transferErr.message);
                throw transferErr;
            }

            try {
                rcLog('[MERGE] Transferring target call to conference...');
                await this.transferSessionToConferenceTarget(target, conferenceTarget, targetRef);
                rcLog('[MERGE] Target call transferred successfully');
            } catch (transferErr) {
                rcLog('[MERGE] ERROR transferring target call:', transferErr.message);
                throw transferErr;
            }

            mergedSource = 'transfer-fallback';
        }

        const sanitizeParticipantNumber = (value) => {
            const v = String(value || '').trim();
            if (!v) return '';
            if (/^conf_/i.test(v) || /^sip:conf_/i.test(v)) return '';
            if (/^virtual-merge-conf-/i.test(v)) return '';
            return v;
        };
        const participantFromSession = (session, label, callRef = null) => {
            if (!session) return null;
            const meta = this.getSessionMeta(session) || {};
            const number = sanitizeParticipantNumber(
                meta.remoteNumber
                || session.remoteNumber
                || meta.toNumber
                || session.toNumber
                || meta.fromNumber
                || session.fromNumber
                || ''
            );
            const name = String(meta.remoteName || session.remoteName || label || '').trim() || label;
            const sessionId = String(session.id || session.sessionId || '').trim() || null;
            return {
                role: 'Participant',
                name,
                phoneNumber: number || null,
                sessionId,
                callSessionId: callRef?.callSessionId || null,
                partyId: callRef?.partyId || null
            };
        };
        const participantList = [];
        const hostNumber = sanitizeParticipantNumber(
            current?.localNumber
            || target?.localNumber
            || this.currentLocalNumber
            || ''
        );
        participantList.push({
            role: 'Host',
            name: 'Me',
            phoneNumber: hostNumber || null,
            sessionId: null,
            callSessionId: null,
            partyId: null
        });
        const currentParticipant = participantFromSession(current, 'Participant A', currentRef);
        const targetParticipant = participantFromSession(target, 'Participant B', targetRef);
        if (currentParticipant) participantList.push(currentParticipant);
        if (targetParticipant) participantList.push(targetParticipant);

        const uniqueParticipants = [];
        const seenParticipants = new Set();
        participantList.forEach((participant) => {
            const key = `${participant?.sessionId || ''}|${participant?.phoneNumber || ''}|${participant?.name || ''}`;
            if (seenParticipants.has(key)) return;
            seenParticipants.add(key);
            uniqueParticipants.push(participant);
        });
        const numbers = uniqueParticipants
            .map((participant) => String(participant?.phoneNumber || '').trim())
            .filter(Boolean);
        const conferenceDisplayNumber = numbers.length > 0
            ? `${numbers.slice(0, 2).join(', ')}${numbers.length > 2 ? ` +${numbers.length - 2} more` : ''}`
            : `${Math.max(2, uniqueParticipants.length)} participants`;

        this.currentTelephonyConferenceId = conferenceSessionId;
        let effectiveConferenceSession = conferenceHostSession || null;
        if (!effectiveConferenceSession) {
            effectiveConferenceSession = {
                id: 'virtual-merge-conf-' + Date.now(),
                sessionId: 'virtual-merge-conf-' + Math.random().toString(36).slice(2),
                state: 'active',
                direction: 'conference',
                remoteNumber: 'Conference',
                localNumber: 'Me',
                _startedAt: Date.now(),
                telephonySessionId: conferenceSessionId,
                isVirtual: true
            };
        }

        try {
            this.setSessionMeta(effectiveConferenceSession, {
                remoteName: 'Conference Call',
                remoteNumber: conferenceDisplayNumber,
                direction: 'conference',
                telephonySessionId: conferenceSessionId,
                conferenceApiSessionId: conferenceSessionId,
                state: effectiveConferenceSession.state || 'active',
                localNumber: 'Me',
                startedAt: effectiveConferenceSession._startedAt || Date.now(),
                participantCount: uniqueParticipants.length,
                participants: uniqueParticipants
            });
        } catch (_) { }

        this.currentSession = effectiveConferenceSession;
        if (!this.callSessions.includes(effectiveConferenceSession)) {
            this.callSessions.push(effectiveConferenceSession);
        }

        rcLog('[MERGE] Merge completed with source:', mergedSource);
        console.info('[MERGE DEBUG] Merge completed', {
            conferenceSessionId,
            source: mergedSource,
            hostSessionId: conferenceHostSession?.id || conferenceHostSession?.sessionId || null
        });
        this.dispatchEvent('conferenceStarted', {
            session: effectiveConferenceSession,
            conferenceSessionId,
            source: mergedSource,
            participantCount: uniqueParticipants.length,
            participants: uniqueParticipants
        });
        this.dispatchEvent('callStarted', { session: effectiveConferenceSession, phoneNumber: 'Conference' });

        return {
            success: true,
            conferenceSessionId,
            current: currentRef,
            target: targetRef,
            source: mergedSource
        };
    }

    /**
     * Transfer a session into a conference target (e.g. conf_xxx).
     * Falls back to backend call-control transfer when SDK transfer/refer methods are unavailable.
     */
    async transferSessionToConferenceTarget(sessionOrId, conferenceTarget, callRef = null) {
        const session = this.findSession(sessionOrId);
        const resolvedCallRef = (callRef && callRef.callSessionId && callRef.partyId)
            ? callRef
            : this.extractBringInIdentifiers(session);

        if (!conferenceTarget) {
            throw new Error('Conference target is required for transfer fallback');
        }

        let lastSdkError = null;

        if (session) {
            rcLog('[TRANSFER] Attempting transfer to conference target:', conferenceTarget);
            rcLog('[TRANSFER] Session methods available:', {
                hasTransfer: typeof session.transfer === 'function',
                hasRefer: typeof session.refer === 'function',
                sessionId: session.id || session.sessionId,
                callId: session.callId || null,
                state: session.state || session.status || 'unknown',
                remoteNumber: session.remoteNumber || null,
                localNumber: session.localNumber || null
            });

            // Avoid transfer race immediately after consult leg creation/answer.
            try { await new Promise((resolve) => setTimeout(resolve, 1200)); } catch (_) {}

            rcLog('[TRANSFER] Proceeding transfer after short guard delay', {
                stateAfterDelay: session.state || session.status || 'unknown'
            });

            if (typeof session.transfer === 'function') {
                rcLog('[TRANSFER] Using session.transfer()');
                try {
                    await session.transfer(conferenceTarget);
                    rcLog('[TRANSFER] Transfer succeeded');
                    return true;
                } catch (e) {
                    lastSdkError = e;
                    rcLog('[TRANSFER] Transfer failed:', {
                        message: e?.message || e,
                        name: e?.name || null,
                        stack: e?.stack || null,
                        sessionState: session.state || session.status || 'unknown'
                    });
                }
            }

            if (typeof session.refer === 'function') {
                rcLog('[TRANSFER] Using session.refer()');
                const sipTarget = `sip:${conferenceTarget}@sip.ringcentral.com`;
                rcLog('[TRANSFER] SIP target:', sipTarget);
                try {
                    await session.refer(sipTarget);
                    rcLog('[TRANSFER] Refer succeeded');
                    return true;
                } catch (e) {
                    lastSdkError = e;
                    rcLog('[TRANSFER] Refer failed:', {
                        message: e?.message || e,
                        name: e?.name || null,
                        stack: e?.stack || null,
                        sessionState: session.state || session.status || 'unknown'
                    });
                }
            }
        } else {
            rcLog('[TRANSFER] Session not found locally; trying backend transfer fallback', {
                callRef: resolvedCallRef
            });
        }

        if (resolvedCallRef?.callSessionId && resolvedCallRef?.partyId) {
            console.info('[MERGE DEBUG] Using backend call-control transfer fallback', {
                callSessionId: resolvedCallRef.callSessionId,
                partyId: resolvedCallRef.partyId,
                conferenceTarget
            });
            await this.transferCallControlPartyToConferenceTarget(resolvedCallRef, conferenceTarget);
            return true;
        }

        if (lastSdkError) {
            throw lastSdkError;
        }
        throw new Error('Session does not support transfer/refer fallback for conference merge');
    }

    async transferCallControlPartyToConferenceTarget(callRef, conferenceTarget) {
        const callSessionId = callRef?.callSessionId || null;
        const partyId = callRef?.partyId || null;

        if (!callSessionId || !partyId) {
            throw new Error('Call-control transfer fallback requires callSessionId and partyId');
        }
        if (!conferenceTarget) {
            throw new Error('Conference target is required for call-control transfer fallback');
        }

        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const transferUrl = (typeof rcRoute === 'function')
            ? rcRoute('ringcentral.api.call-control.transfer', { sessionId: callSessionId, partyId: partyId })
            : `${this.config.apiBaseUrl}/call-control/sessions/${encodeURIComponent(callSessionId)}/parties/${encodeURIComponent(partyId)}/transfer`;
        const sipTarget = `sip:${conferenceTarget}@sip.ringcentral.com`;
        const targets = conferenceTarget === sipTarget ? [conferenceTarget] : [conferenceTarget, sipTarget];

        // R-Dialer call-control transfer endpoint expects a valid phone number (PSTN/E.164),
        // not conference tokens like conf_xxx or SIP conference URIs.
        const hasConferenceTokenTarget = targets.some((target) => /^conf_/i.test(String(target || '')))
            || targets.some((target) => /^sip:conf_/i.test(String(target || '')));
        if (hasConferenceTokenTarget) {
            throw new Error('Call-control transfer does not accept conference token targets (conf_/sip:conf_).');
        }

        let lastMessage = 'Unknown transfer fallback error';
        for (const target of targets) {
            console.info('[MERGE DEBUG] Backend transfer fallback request', {
                transferUrl,
                callSessionId,
                partyId,
                target
            });

            const resp = await fetch(transferUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ phone_number: target })
            });

            let payload = null;
            let rawText = '';
            try {
                payload = await resp.json();
            } catch (_) {
                try {
                    rawText = await resp.text();
                } catch (_) { }
            }

            if (resp.ok) {
                console.info('[MERGE DEBUG] Backend transfer fallback succeeded', {
                    callSessionId,
                    partyId,
                    target
                });
                return payload || { success: true };
            }

            lastMessage = payload?.message || rawText || `HTTP ${resp.status}`;
            console.info('[MERGE DEBUG] Backend transfer fallback attempt failed', {
                callSessionId,
                partyId,
                target,
                status: resp.status,
                message: lastMessage
            });
        }

        throw new Error(`Call-control transfer fallback failed: ${lastMessage}`);
    }

    async waitForConferenceSessionReady(conferenceSessionId, options = {}) {
        const timeoutMs = Number(options.timeoutMs || 10000);
        const pollMs = Number(options.pollMs || 1000);
        const start = Date.now();
        const sessionsUrl = (typeof rcRoute === 'function')
            ? rcRoute('ringcentral.api.call-control.sessions')
            : `${this.config.apiBaseUrl}/call-control/sessions`;
        const url = `${sessionsUrl}?activeOnly=false&perPage=100`;
        const isAliveStatus = (status) => ['proceeding', 'answered', 'connected', 'hold', 'onhold', 'parked'].includes(status);

        while (Date.now() - start < timeoutMs) {
            try {
                const resp = await fetch(url, {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    }
                });

                let payload = null;
                try {
                    payload = await resp.json();
                } catch (_) {
                    payload = null;
                }

                if (resp.ok) {
                    const records = Array.isArray(payload?.data?.records)
                        ? payload.data.records
                        : (Array.isArray(payload?.data) ? payload.data : []);
                    const conferenceRecord = records.find((record) => {
                        const sid = String(record?.id || record?.sessionId || '');
                        return sid === String(conferenceSessionId || '');
                    }) || null;

                    const activeParties = (conferenceRecord?.parties || []).filter((party) => {
                        const status = String(party?.status?.code || party?.status || '').toLowerCase();
                        return !!party?.id && isAliveStatus(status);
                    });

                    if (conferenceRecord && activeParties.length > 0) {
                        return {
                            ready: true,
                            conferenceSessionId,
                            activePartyCount: activeParties.length
                        };
                    }
                }
            } catch (_) { }

            await new Promise((resolve) => setTimeout(resolve, pollMs));
        }

        return {
            ready: false,
            conferenceSessionId,
            reason: 'no-active-conference-party'
        };
    }

    async joinConferenceHostLeg(conferenceTarget, conferenceSessionId = null, options = {}) {
        if (!conferenceTarget) {
            return {
                session: null,
                ready: false,
                lastError: 'conference-target-missing'
            };
        }

        const timeoutMs = Number(options.timeoutMs || 8000);
        const context = String(options.context || 'unknown');
        const attempts = [
            // In this environment raw token is the only reliable target. Try it first to reduce latency.
            { label: 'raw-token', target: `${conferenceTarget}` },
            { label: 'sip-raw', target: `sip:${conferenceTarget}` },
            { label: 'sip-domain', target: `sip:${conferenceTarget}@sip.ringcentral.com` }
        ];

        // Fast short-circuit: if conference is already ready, do not spend time on invite attempts.
        if (conferenceSessionId) {
            try {
                const readyNow = await this.waitForConferenceSessionReady(conferenceSessionId, {
                    timeoutMs: 1200,
                    pollMs: 400
                });
                if (readyNow?.ready) {
                    console.info('[MERGE DEBUG] Conference already ready before host invite attempts', {
                        context,
                        conferenceSessionId,
                        readiness: readyNow
                    });
                    return {
                        session: null,
                        ready: true,
                        hostActive: true,
                        readiness: readyNow,
                        attempt: 'already-ready'
                    };
                }
            } catch (_) { }
        }

        let lastError = null;
        for (const attempt of attempts) {
            try {
                console.info('[MERGE DEBUG] Conference host invite attempt', {
                    context,
                    conferenceSessionId,
                    conferenceTarget,
                    attemptLabel: attempt.label,
                    inviteTarget: attempt.target
                });

                const session = await this.inviteUri(attempt.target, {
                    timeoutMs,
                    suppressTimeoutErrorLog: true
                });
                const hostActive = await this.waitForSessionActive(session, {
                    timeoutMs: 15000,
                    pollMs: 500
                });
                const readiness = conferenceSessionId
                    ? await this.waitForConferenceSessionReady(conferenceSessionId, { timeoutMs: 4000, pollMs: 750 })
                    : { ready: hostActive };

                console.info('[MERGE DEBUG] Conference host invite attempt result', {
                    context,
                    attemptLabel: attempt.label,
                    hostActive,
                    readiness,
                    sessionId: session?.id || session?.sessionId || null,
                    sessionState: session?.state || session?.status || 'unknown'
                });

                if (hostActive || readiness?.ready) {
                    return {
                        session,
                        ready: true,
                        hostActive,
                        readiness,
                        attempt: attempt.label
                    };
                }

                lastError = `attempt ${attempt.label} did not become active`;
            } catch (attemptErr) {
                lastError = attemptErr?.message || String(attemptErr || '');
                console.info('[MERGE DEBUG] Conference host invite attempt failed', {
                    context,
                    attemptLabel: attempt.label,
                    message: lastError
                });
            }

            // Best-effort fallback: recover a conference-like session if invite Promise timed out
            // but SDK internally created a session object.
            try {
                this.recoverSessionsFromSdk({ emitIncomingEvent: false });
                const recoveredConferenceSession = (this.callSessions || []).find((s) => {
                    const meta = this.getSessionMeta(s) || {};
                    const remote = String(meta.remoteNumber || s?.remoteNumber || '').toLowerCase();
                    const direction = String(meta.direction || s?.direction || '').toLowerCase();
                    return /^conf_/i.test(String(s?.remoteNumber || ''))
                        || remote === 'conference'
                        || direction === 'conference';
                }) || null;

                if (recoveredConferenceSession) {
                    const readiness = conferenceSessionId
                        ? await this.waitForConferenceSessionReady(conferenceSessionId, { timeoutMs: 4000, pollMs: 750 })
                        : { ready: true };
                    if (readiness?.ready) {
                        console.info('[MERGE DEBUG] Conference host session recovered from SDK state', {
                            context,
                            recoveredSessionId: recoveredConferenceSession?.id || recoveredConferenceSession?.sessionId || null,
                            readiness
                        });
                        return {
                            session: recoveredConferenceSession,
                            ready: true,
                            hostActive: true,
                            readiness,
                            attempt: `${attempt.label}:recovered`
                        };
                    }
                }
            } catch (_) { }
        }

        return {
            session: null,
            ready: false,
            lastError: lastError || 'all-conference-invite-attempts-failed'
        };
    }

    /**
     * Create a conference using backend telephony conference endpoint.
     */
    async createTelephonyConference() {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const conferenceUrl = (typeof rcRoute === 'function')
            ? rcRoute('ringcentral.api.conference')
            : `${this.config.apiBaseUrl}/telephony/conference`;

        const resp = await fetch(conferenceUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            }
        });

        if (!resp.ok) {
            const text = await resp.text();
            throw new Error(`Conference API failed (${resp.status}): ${text}`);
        }

        return await resp.json();
    }

    /**
     * Add one active call to a conference.
     */
    async bringSessionIntoConference(conferenceSessionId, callRef) {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const bringInUrl = (typeof rcRoute === 'function')
            ? rcRoute('ringcentral.api.bring-in', { sessionId: conferenceSessionId })
            : `${this.config.apiBaseUrl}/telephony/sessions/${encodeURIComponent(conferenceSessionId)}/parties/bring-in`;

        rcInfo('[BRING-IN] Sending bring-in request', {
            conferenceSessionId,
            callSessionId: callRef?.callSessionId || null,
            partyId: callRef?.partyId || null
        });
        console.info('[MERGE DEBUG] Bring-in request', {
            conferenceSessionId,
            callSessionId: callRef?.callSessionId || null,
            partyId: callRef?.partyId || null
        });

        const resp = await fetch(bringInUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({
                party_id: callRef.partyId,
                session_id: callRef.callSessionId
            })
        });

        if (!resp.ok) {
            let payload = null;
            let rawText = '';
            try {
                payload = await resp.json();
            } catch (_) {
                try {
                    rawText = await resp.text();
                } catch (_) { }
            }

            const errorCode = payload?.errorCode || payload?.error_code || payload?.data?.errorCode || null;
            const message = payload?.message || rawText || 'Bring-in request failed';
            const codePart = errorCode ? `, errorCode=${errorCode}` : '';
            throw new Error(`Bring-in failed (${resp.status}${codePart}): ${message}`);
        }

        return await resp.json();
    }

    async waitForSessionActive(sessionOrId, options = {}) {
        const timeoutMs = Number(options.timeoutMs || 120000);
        const pollMs = Number(options.pollMs || 500);
        const start = Date.now();
        const isActive = (session) => {
            if (!session) return false;
            const state = String(session.state || session.status || '').toLowerCase();
            return ['answered', 'connected', 'established', 'active', 'inprogress'].some((flag) => state.includes(flag));
        };

        let checks = 0;
        while (Date.now() - start < timeoutMs) {
            const session = this.findSession(sessionOrId);
            if (checks % 4 === 0) {
                rcInfo('[WAIT-ACTIVE] Poll', {
                    elapsedMs: Date.now() - start,
                    state: session?.state || session?.status || 'unknown',
                    id: session?.id || session?.sessionId || null
                });
            }
            if (isActive(session)) return true;
            checks += 1;
            await new Promise((resolve) => setTimeout(resolve, pollMs));
        }
        rcInfo('[WAIT-ACTIVE] Timed out waiting for active session', {
            timeoutMs,
            id: sessionOrId?.id || sessionOrId?.sessionId || sessionOrId || null
        });
        return false;
    }

    async resolveBringInIdentifiersForSession(sessionOrId, options = {}) {
        const session = this.findSession(sessionOrId);
        let callRef = this.extractBringInIdentifiers(session);
        if (callRef.callSessionId && callRef.partyId) return callRef;

        const maxAttempts = Number(options.maxAttempts || 10);
        const delayMs = Number(options.delayMs || 1000);

        const toDigits = (value) => {
            const digits = String(value || '').replace(/\D+/g, '');
            return digits || '';
        };

        for (let attempt = 0; attempt < maxAttempts; attempt += 1) {
            rcInfo('[BRING-IN-RESOLVE] Attempt', {
                attempt: attempt + 1,
                maxAttempts,
                existing: callRef,
                sessionId: session?.id || session?.sessionId || null
            });
            try {
                const parties = await this.fetchActiveCallControlParties();
                if (Array.isArray(parties) && parties.length > 0) {
                    rcInfo('[BRING-IN-RESOLVE] Active parties', {
                        count: parties.length,
                        sample: parties.slice(0, 3)
                    });
                    const meta = session ? (this.getSessionMeta(session) || {}) : {};
                    const hints = [
                        session?.remoteNumber,
                        session?.toNumber,
                        session?.fromNumber,
                        meta?.remoteNumber,
                        meta?.toNumber,
                        meta?.fromNumber
                    ]
                        .map(toDigits)
                        .filter((v) => v);

                    const direction = String(session?.direction || meta?.direction || '').toLowerCase();

                    if (hints.length > 0) {
                        const matched = parties.find((p) => {
                            const fromDigits = toDigits(p?.from?.phoneNumber);
                            const toDigitsVal = toDigits(p?.to?.phoneNumber);
                            return hints.includes(fromDigits) || hints.includes(toDigitsVal);
                        });
                        if (matched) {
                            rcInfo('[BRING-IN-RESOLVE] Matched by phone hint', {
                                callSessionId: matched.callSessionId,
                                partyId: matched.partyId
                            });
                            return { callSessionId: matched.callSessionId, partyId: matched.partyId };
                        }
                    }

                    if (direction) {
                        const matched = parties.find((p) => String(p?.direction || '').toLowerCase() === direction);
                        if (matched) {
                            rcInfo('[BRING-IN-RESOLVE] Matched by direction', {
                                callSessionId: matched.callSessionId,
                                partyId: matched.partyId
                            });
                            return { callSessionId: matched.callSessionId, partyId: matched.partyId };
                        }
                    }

                    if (parties.length === 1) {
                        rcInfo('[BRING-IN-RESOLVE] Single party fallback', {
                            callSessionId: parties[0].callSessionId,
                            partyId: parties[0].partyId
                        });
                        return { callSessionId: parties[0].callSessionId, partyId: parties[0].partyId };
                    }
                }
            } catch (err) {
                rcInfo('[BRING-IN-RESOLVE] Active party lookup failed', {
                    error: err?.message || err
                });
            }

            await new Promise((resolve) => setTimeout(resolve, delayMs));
        }

        rcInfo('[BRING-IN-RESOLVE] Failed to resolve identifiers after attempts', {
            maxAttempts,
            lastKnown: callRef,
            sessionId: session?.id || session?.sessionId || null
        });
        return callRef;
    }

    /**
     * Resolve party and call session identifiers needed by bring-in endpoint.
     */
    extractBringInIdentifiers(sessionOrId) {
        const session = this.findSession(sessionOrId);
        if (!session) {
            return { callSessionId: null, partyId: null };
        }

        const meta = this.getSessionMeta(session) || {};
        let callSessionId =
            session.sessionId ||
            session.id ||
            session.callId ||
            session.telephonySessionId ||
            session.party?.sessionId ||
            session.party?.id ||
            meta.sessionId ||
            meta.id ||
            meta.callSessionId ||
            meta.telephonySessionId ||
            null;

        let partyId =
            session.partyId ||
            session.party?.id ||
            session._partyId ||
            meta.partyId ||
            meta.party?.id ||
            null;

        // Prefer real telephony identifiers (R-Dialer  uses s-*/p-* format).
        // SIP/session ids from WebPhone are not valid for bring-in.
        if (typeof callSessionId === 'string') {
            const trimmed = callSessionId.trim();
            callSessionId = trimmed && trimmed.startsWith('s-') ? trimmed : null;
        }
        if (typeof partyId === 'string') {
            const trimmed = partyId.trim();
            partyId = trimmed && trimmed.startsWith('p-') ? trimmed : null;
        }

        // Best-effort fallback seen in some SDK payloads where party suffix is omitted.
        if (!partyId && typeof callSessionId === 'string') {
            if (/-\d+$/.test(callSessionId)) {
                partyId = callSessionId.replace(/-1$/, '-2');
            } else {
                partyId = callSessionId;
            }
        }

        return {
            callSessionId,
            partyId
        };
    }

    async resolveBringInIdentifiersFromCallControl(currentSession, targetSession, currentRef, targetRef) {
        const parties = await this.fetchActiveCallControlParties();
        if (!Array.isArray(parties) || parties.length < 2) {
            return { currentRef, targetRef };
        }

        const used = new Set();
        const chooseForSession = (session) => {
            const numbers = [];
            const collect = (v) => {
                const s = String(v || '').replace(/\D+/g, '');
                if (s) numbers.push(s);
            };
            const meta = this.getSessionMeta(session) || {};
            collect(session?.remoteNumber);
            collect(session?.toNumber);
            collect(session?.fromNumber);
            collect(meta?.remoteNumber);

            // Prefer party matching local session phone hints
            for (const p of parties) {
                const key = `${p.callSessionId || ''}:${p.partyId || ''}`;
                if (used.has(key)) continue;
                const fromDigits = String(p?.from?.phoneNumber || '').replace(/\D+/g, '');
                const toDigits = String(p?.to?.phoneNumber || '').replace(/\D+/g, '');
                if (numbers.includes(fromDigits) || numbers.includes(toDigits)) {
                    used.add(key);
                    return { callSessionId: p.callSessionId, partyId: p.partyId };
                }
            }

            // Fallback to first unused active party
            for (const p of parties) {
                const key = `${p.callSessionId || ''}:${p.partyId || ''}`;
                if (used.has(key)) continue;
                used.add(key);
                return { callSessionId: p.callSessionId, partyId: p.partyId };
            }
            return { callSessionId: null, partyId: null };
        };

        const resolvedCurrent = (currentRef?.callSessionId && currentRef?.partyId)
            ? currentRef
            : chooseForSession(currentSession);
        const resolvedTarget = (targetRef?.callSessionId && targetRef?.partyId)
            ? targetRef
            : chooseForSession(targetSession);

        return {
            currentRef: resolvedCurrent,
            targetRef: resolvedTarget
        };
    }

    async fetchActiveCallControlParties() {
        const sessionsUrl = (typeof rcRoute === 'function')
            ? rcRoute('ringcentral.api.call-control.sessions')
            : `${this.config.apiBaseUrl}/call-control/sessions`;
        const url = `${sessionsUrl}?activeOnly=true`;

        const resp = await fetch(url, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json'
            }
        });

        let payload = null;
        try {
            payload = await resp.json();
        } catch (_) {
            payload = null;
        }

        if (!resp.ok) {
            const message = payload?.message || `HTTP ${resp.status}`;
            throw new Error(`Failed active-call lookup (${resp.status}): ${message}`);
        }

        const records = Array.isArray(payload?.data?.records)
            ? payload.data.records
            : (Array.isArray(payload?.data) ? payload.data : []);
        const parties = [];
        records.forEach((session) => {
            const callSessionId = session?.id || session?.sessionId || null;
            if (!callSessionId) return;

            const source = session?.source || session?.origin || '';
            (session?.parties || []).forEach((party) => {
                const status = String(party?.status?.code || party?.status || '').toLowerCase();
                const isAlive = ['proceeding', 'answered', 'connected', 'hold', 'onhold', 'parked'].includes(status);
                if (!party?.id || !isAlive) return;

                const ownerExtensionId =
                    String(
                        party?.owner?.extensionId
                        || party?.owner?.id
                        || session?.owner?.extensionId
                        || session?.owner?.id
                        || ''
                    ).trim() || null;

                parties.push({
                    callSessionId,
                    partyId: party.id,
                    status,
                    from: party?.from || {},
                    to: party?.to || {},
                    source,
                    ownerExtensionId
                });
            });
        });

        console.info('[CALL-CONTROL DEBUG] Active call-control party snapshot', {
            count: parties.length,
            partyIds: parties.map((p) => p.partyId),
            ownerExtensionIds: Array.from(new Set(parties.map((p) => p.ownerExtensionId).filter(Boolean)))
        });

        return parties;
    }

    /**
     * Start a conference with all active sessions (best-effort, tries multiple SDK methods)
     */
    async startConference() {
        await this.ensureMicrophoneAvailable();

        // REST-first approach: create conference, then INVITE conference URI via SIP
        try {
            rcLog('[RC DEBUG] Starting conference: calling telephony/conference API...');
            const resp = await fetch(`${this.config.apiBaseUrl}/telephony/conference`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }
            });
            rcLog('[RC DEBUG] Conference API status:', resp.status);
            if (!resp.ok) {
                const text = await resp.text();
                throw new Error(`Conference API failed (${resp.status}): ${text}`);
            }
            const data = await resp.json();
            rcLog('[RC DEBUG] Conference API payload:', data);
            const token = data?.session?.voiceCallToken;
            const telephonySessionId = data?.session?.id || null;
            rcLog('[RC DEBUG] Extracted voiceCallToken:', token, 'telephonySessionId:', telephonySessionId);
            if (!token) {
                throw new Error('Conference API did not return voiceCallToken');
            }
            // voiceCallToken may already be prefixed with "conf_"; avoid double prefix
            const confUser = String(token).startsWith('conf_') ? String(token) : `conf_${token}`;
            const confUri = `sip:${confUser}@sip.ringcentral.com`;
            this.currentConferenceTarget = confUser;
            this.currentConferenceVoiceCallToken = token;
            rcLog('Calling conference URI:', confUri);
            rcLog('[RC DEBUG] Inviting conference URI via SIP...');
            let session;
            const hostJoinResult = await this.joinConferenceHostLeg(confUser, telephonySessionId, {
                context: 'start-conference',
                timeoutMs: 8000
            });
            try {
                session = hostJoinResult?.session || null;
                if (session) {
                    rcLog('[RC DEBUG] INVITE returned session (raw):', session);
                } else {
                    throw new Error(hostJoinResult?.lastError || 'conference-host-join-failed');
                }
            } catch (inviteErr) {
                // Fallback: create a virtual session so UI can manage bring-in actions
                session = {
                    id: 'virtual-conf-' + Date.now(),
                    sessionId: 'virtual-conf-' + Math.random().toString(36).slice(2),
                    state: 'active',
                    direction: 'conference',
                    remoteNumber: 'Conference',
                    localNumber: 'Me',
                    _startedAt: Date.now(),
                    telephonySessionId: telephonySessionId,
                    isVirtual: true
                };
                rcLog('[RC DEBUG] Created virtual conference session:', session);
            }
            // Annotate session for UI purposes (store in metadata to avoid mutating SDK session objects)
            const conferenceCallRef = this.extractBringInIdentifiers(session);
            const conferenceCallSessionId = session?.isVirtual === true ? null : (conferenceCallRef?.callSessionId || null);
            const effectiveConferenceSessionId = conferenceCallSessionId || telephonySessionId || null;
            const conferenceParticipants = [{
                role: 'Host',
                name: 'Me',
                phoneNumber: null,
                sessionId: null
            }];

            try {
                this.setSessionMeta(session, {
                    remoteName: 'Conference Call',
                    remoteNumber: 'Host connected',
                    direction: 'conference',
                    telephonySessionId: effectiveConferenceSessionId,
                    conferenceApiSessionId: telephonySessionId,
                    conferenceCallSessionId: conferenceCallSessionId,
                    state: session.state || 'active',
                    localNumber: 'Me',
                    startedAt: session._startedAt || Date.now(),
                    participantCount: conferenceParticipants.length,
                    participants: conferenceParticipants
                });
            } catch (_) {}
            this.currentTelephonyConferenceId = effectiveConferenceSessionId;
            rcLog('[RC DEBUG] Conference IDs selected:', {
                conferenceCallSessionId,
                conferenceApiSessionId: telephonySessionId,
                effectiveConferenceSessionId
            });
            this.currentSession = session;
            if (!this.callSessions.includes(session)) {
                this.callSessions.push(session);
                rcLog('Conference session tracked. Total sessions:', this.callSessions.length);
            } else {
                rcLog('Conference session already tracked. Total sessions:', this.callSessions.length);
            }
            this.attachSessionListeners(session);
            // Temporary debug: confirm sessions feed
            try {
                rcLog('[RC DEBUG] listSessions() after conference start:', this.listSessions());
            } catch (_) {}
            this.dispatchEvent('conferenceStarted', {
                session,
                confUri,
                source: 'rest',
                participantCount: conferenceParticipants.length,
                participants: conferenceParticipants
            });
            // Also emit generic callStarted for UI components listening on standard events
            this.dispatchEvent('callStarted', { session, phoneNumber: 'Conference' });
            return session;
        } catch (e) {
            console.error('Conference failed (REST + SIP INVITE):', e);
            this.dispatchEvent('callError', { error: e.message });
            throw new Error(`Conference failed: ${e.message}`);
        }
    }

    /**
     * Start a conference and optionally invite one phone number into it.
     */
    async startConferenceWithInvite(phoneNumber, fromNumber = null) {
        rcLog('[CONF+INVITE] startConferenceWithInvite requested', { phoneNumber, fromNumber, bringInUnavailable: this._bringInUnavailable });
        const conferenceSession = await this.startConference();

        const to = String(phoneNumber || '').trim();
        if (!to) {
            return {
                success: true,
                invited: false,
                conferenceSession
            };
        }

        // Create a call leg to the participant, then bring it into conference.
        const inviteSession = await this.makeCall(to, fromNumber);
        const conferenceCallRef = this.extractBringInIdentifiers(conferenceSession);
        const conferenceSessionId = (conferenceSession?.isVirtual === true)
            ? (conferenceSession?.telephonySessionId || this.getSessionMeta(conferenceSession)?.telephonySessionId || this.currentTelephonyConferenceId || null)
            : (conferenceCallRef.callSessionId || this.currentTelephonyConferenceId || this.getTelephonyConferenceId(conferenceSession));
        await this.waitForSessionActive(inviteSession, { timeoutMs: 20000, pollMs: 500 });
        const callRef = await this.resolveBringInIdentifiersForSession(inviteSession, { maxAttempts: 10, delayMs: 1000 });

        rcLog('[CONF+INVITE] Prepared conference and invite call refs', {
            conferenceSessionId,
            conferenceSessionVirtual: !!conferenceSession?.isVirtual,
            conferenceSessionState: conferenceSession?.state || conferenceSession?.status || 'unknown',
            callRef,
            bringInUnavailable: this._bringInUnavailable
        });

        if (!conferenceSessionId) {
            throw new Error('Conference started but conference session id is missing for invite');
        }

        if (!callRef.callSessionId || !callRef.partyId) {
            throw new Error('Invite call created but call identifiers are missing for conference bring-in');
        }

        try {
            if (this._bringInUnavailable) {
                throw new Error('Bring-in skipped: account marked as bring-in unavailable');
            }
            await this.bringSessionIntoConference(conferenceSessionId, callRef);
            return {
                success: true,
                invited: true,
                conferenceSessionId,
                callRef,
                source: 'bring-in'
            };
        } catch (e) {
            const msg = String(e?.message || e || '');
            const isBringInRestricted = /\b403\b|\b404\b|forbidden|TAS-106|CMN-102|operation is not allowed|sessionId\] is not found|bring-in skipped/i.test(msg);
            if (!isBringInRestricted) {
                throw e;
            }

            if (/TAS-106|CMN-102|sessionId\] is not found|\b404\b/i.test(msg)) {
                this._bringInUnavailable = true;
                rcLog('[CONF+INVITE] Marked bring-in as unavailable for this runtime', { reason: msg });
            }

            rcLog('[INVITE-ACTIVE-CONF] Bring-in restricted; entering transfer fallback', {
                conferenceSessionId,
                callRef,
                reason: msg
            });

            const fallbackTarget = this.currentConferenceTarget
                || (this.currentConferenceVoiceCallToken
                    ? (String(this.currentConferenceVoiceCallToken).startsWith('conf_')
                        ? String(this.currentConferenceVoiceCallToken)
                        : `conf_${this.currentConferenceVoiceCallToken}`)
                    : null);

            if (!fallbackTarget) {
                throw new Error(`Bring-in forbidden (403) and conference target is missing for transfer fallback: ${msg}`);
            }

            await this.transferSessionToConferenceTarget(inviteSession, fallbackTarget, callRef);
            rcLog('[INVITE-ACTIVE-CONF] Transfer fallback completed', {
                fallbackTarget,
                inviteSessionId: inviteSession?.id || inviteSession?.sessionId || null
            });
            return {
                success: true,
                invited: true,
                conferenceSessionId,
                callRef,
                source: 'transfer-fallback',
                reason: 'bring-in-restricted'
            };
        }
    }

    /**
     * Invite a participant into an already active conference.
     */
    async inviteIntoActiveConference(phoneNumber, fromNumber = null) {
        const to = String(phoneNumber || '').trim();
        if (!to) throw new Error('Phone number is required');

        rcLog('[INVITE-ACTIVE-CONF] Request received', {
            to,
            fromNumber,
            bringInUnavailable: this._bringInUnavailable,
            currentSessionId: this.currentSession?.id || this.currentSession?.sessionId || null,
            totalSessions: (this.callSessions || []).length
        });

        const isConferenceSession = (session) => {
            if (!session) return false;
            const meta = this.getSessionMeta(session) || {};
            const direction = String(meta.direction || session.direction || '').toLowerCase();
            const remoteNumber = String(meta.remoteNumber || session.remoteNumber || '').toLowerCase();
            return session.isVirtual === true
                || direction === 'conference'
                || remoteNumber === 'conference'
                || /^conf_/i.test(String(session.remoteNumber || ''));
        };

        let conferenceSession = isConferenceSession(this.currentSession)
            ? this.currentSession
            : (this.callSessions || []).find(isConferenceSession) || null;

        if (!conferenceSession) {
            throw new Error('No active conference found. Start conference first.');
        }

        rcLog('[INVITE-ACTIVE-CONF] Selected conference session', {
            id: conferenceSession?.id || conferenceSession?.sessionId || null,
            isVirtual: !!conferenceSession?.isVirtual,
            state: conferenceSession?.state || conferenceSession?.status || 'unknown',
            remoteNumber: conferenceSession?.remoteNumber || null,
            meta: this.getSessionMeta(conferenceSession) || null
        });

        const inviteSession = await this.makeCall(to, fromNumber);
        const conferenceRef = this.extractBringInIdentifiers(conferenceSession);
        const conferenceSessionId = (conferenceSession?.isVirtual === true)
            ? (conferenceSession?.telephonySessionId
                || this.getSessionMeta(conferenceSession)?.telephonySessionId
                || this.currentTelephonyConferenceId
                || null)
            : (conferenceRef.callSessionId
                || conferenceSession?.telephonySessionId
                || this.getSessionMeta(conferenceSession)?.telephonySessionId
                || this.currentTelephonyConferenceId
                || null);

        await this.waitForSessionActive(inviteSession, { timeoutMs: 120000, pollMs: 500 });
        const callRef = await this.resolveBringInIdentifiersForSession(inviteSession, { maxAttempts: 10, delayMs: 1000 });

        rcLog('[INVITE-ACTIVE-CONF] Invite call created', {
            inviteSessionId: inviteSession?.id || inviteSession?.sessionId || null,
            inviteState: inviteSession?.state || inviteSession?.status || 'unknown',
            conferenceSessionId,
            callRef,
            bringInUnavailable: this._bringInUnavailable
        });

        if (!conferenceSessionId) {
            throw new Error('Conference session id is missing for invite');
        }

        if (!callRef.callSessionId || !callRef.partyId) {
            throw new Error('Invite call created but call identifiers are missing for conference bring-in');
        }

        try {
            if (this._bringInUnavailable) {
                throw new Error('Bring-in skipped: account marked as bring-in unavailable');
            }
            await this.bringSessionIntoConference(conferenceSessionId, callRef);
            return {
                success: true,
                invited: true,
                conferenceSessionId,
                callRef,
                source: 'bring-in',
                existingConference: true
            };
        } catch (e) {
            const msg = String(e?.message || e || '');
            const isBringInRestricted = /\b403\b|\b404\b|forbidden|TAS-106|CMN-102|operation is not allowed|sessionId\] is not found|bring-in skipped/i.test(msg);
            if (!isBringInRestricted) {
                throw e;
            }

            if (/TAS-106|CMN-102|sessionId\] is not found|\b404\b/i.test(msg)) {
                this._bringInUnavailable = true;
                rcLog('[INVITE-ACTIVE-CONF] Marked bring-in as unavailable for this runtime', { reason: msg });
            }

            rcLog('[INVITE-ACTIVE-CONF] Bring-in restricted; entering transfer fallback', {
                conferenceSessionId,
                callRef,
                reason: msg,
                bringInUnavailable: this._bringInUnavailable
            });

            const fallbackTarget = this.currentConferenceTarget
                || (this.currentConferenceVoiceCallToken
                    ? (String(this.currentConferenceVoiceCallToken).startsWith('conf_')
                        ? String(this.currentConferenceVoiceCallToken)
                        : `conf_${this.currentConferenceVoiceCallToken}`)
                    : null);

            if (!fallbackTarget) {
                throw new Error(`Bring-in forbidden (403) and conference target is missing for transfer fallback: ${msg}`);
            }

            await this.transferSessionToConferenceTarget(inviteSession, fallbackTarget, callRef);
            return {
                success: true,
                invited: true,
                conferenceSessionId,
                callRef,
                source: 'transfer-fallback',
                reason: 'bring-in-restricted',
                existingConference: true
            };
        }
    }

    /**
     * Invite a raw SIP URI (no E.164 formatting)
     */
    async inviteUri(targetUri, options = {}) {
        if (!this.isInitialized) throw new Error('WebPhone not initialized');
        const timeoutMs = Number.isFinite(options?.timeoutMs) ? Number(options.timeoutMs) : 30000;
        const suppressTimeoutErrorLog = !!options?.suppressTimeoutErrorLog;
        const userAgent = this.userAgent || this.webphone;

        // Find invite-capable method (reuse logic from makeCall)
        let inviteMethod = null;
        let inviteMethodName = null;
        if (userAgent && typeof userAgent.invite === 'function') { inviteMethod = userAgent.invite.bind(userAgent); inviteMethodName = 'userAgent.invite'; }
        else if (this.webphone?.userAgent?.invite) { inviteMethod = this.webphone.userAgent.invite.bind(this.webphone.userAgent); inviteMethodName = 'webphone.userAgent.invite'; }
        else if (this.webphone?.invite) { inviteMethod = this.webphone.invite.bind(this.webphone); inviteMethodName = 'webphone.invite'; }
        else if (this.webphone?.sipClient?.invite) { inviteMethod = this.webphone.sipClient.invite.bind(this.webphone.sipClient); inviteMethodName = 'webphone.sipClient.invite'; }
        else if (this.webphone?.call) { inviteMethod = this.webphone.call.bind(this.webphone); inviteMethodName = 'webphone.call'; }

        if (!inviteMethod) {
            throw new Error('Invite method not available for SIP URI');
        }

        rcLog('[RC DEBUG] Using invite method:', inviteMethodName, 'for target URI:', targetUri);

        // If we fell back to a generic call() method, attempt to find a raw invite again
        if (inviteMethodName === 'webphone.call') {
            try {
                if (userAgent && typeof userAgent.invite === 'function') {
                    inviteMethod = userAgent.invite.bind(userAgent);
                    inviteMethodName = 'userAgent.invite (fallback)';
                } else if (this.webphone?.sipClient?.invite) {
                    inviteMethod = this.webphone.sipClient.invite.bind(this.webphone.sipClient);
                    inviteMethodName = 'webphone.sipClient.invite (fallback)';
                } else if (this.webphone?.userAgent?.invite) {
                    inviteMethod = this.webphone.userAgent.invite.bind(this.webphone.userAgent);
                    inviteMethodName = 'webphone.userAgent.invite (fallback)';
                } else {
                    // Deep scan for any function containing 'invite'
                    const candidates = [];
                    const collectFns = (obj, label) => {
                        if (!obj) return;
                        try {
                            Object.getOwnPropertyNames(obj).forEach(p => {
                                if (typeof obj[p] === 'function' && /invite/i.test(p)) {
                                    candidates.push({ fn: obj[p].bind(obj), name: `${label}.${p}` });
                                }
                            });
                        } catch (_) {}
                    };
                    collectFns(this.webphone, 'webphone');
                    collectFns(this.webphone?.userAgent, 'webphone.userAgent');
                    collectFns(this.webphone?.sipClient, 'webphone.sipClient');
                    collectFns(this.webphone?.sipClient?.userAgent, 'webphone.sipClient.userAgent');
                    if (candidates.length) {
                        inviteMethod = candidates[0].fn;
                        inviteMethodName = candidates[0].name + ' (scan)';
                    }
                }
                rcLog('[RC DEBUG] Adjusted invite method:', inviteMethodName);
            } catch (e) {
            }
        }

        let session;
        let result = inviteMethod(targetUri);
        if (result && typeof result.then === 'function') {
            // Add a timeout to avoid hanging on unresolved Promises
            try {
                session = await Promise.race([
                    result,
                    new Promise((_, reject) => setTimeout(() => reject(new Error('SIP INVITE timed out')), timeoutMs))
                ]);
            } catch (e) {
                const isTimeout = /timed out/i.test(String(e?.message || e || ''));
                if (!(isTimeout && suppressTimeoutErrorLog)) {
                    console.error('[RC DEBUG] inviteUri() Promise rejected:', e);
                }
                throw e;
            }
        } else {
            session = result;
        }
        rcLog('[RC DEBUG] inviteUri() resolved session:', session);
        if (!session) throw new Error('Conference invite returned no session');
        return session;
    }

    /**
     * Get telephony conference session id for a given session or current
     */
    getTelephonyConferenceId(sessionOrId = null) {
        const session = sessionOrId ? this.findSession(sessionOrId) : this.currentSession;
        return (session && session.telephonySessionId) ? session.telephonySessionId : (this.currentTelephonyConferenceId || null);
    }

    /**
     * Hangup a specific session
     */
    async hangupSession(sessionOrId) {
        const session = this.findSession(sessionOrId);
        if (!session) throw new Error('Target session not found');
        // Handle virtual conference session: just remove from tracking
        if (session.isVirtual === true) {
            try { this.callSessions = this.callSessions.filter(s => s !== session); } catch (_) {}
            if (this.currentSession === session) this.currentSession = null;
            this.dispatchEvent('callEnded', { session, reason: 'virtual-ended' });
            return true;
        }
        const state = String(session.state || session.status || '').toLowerCase();
        if (['terminated', 'disposed', 'ended', 'failed', 'cancelled'].includes(state)) {
            try { this.callSessions = this.callSessions.filter(s => s !== session); } catch (_) {}
            if (this.currentSession === session) this.currentSession = null;
            this.dispatchEvent('callEnded', { session, reason: 'already-ended' });
            return true;
        }

        try {
            if (typeof session.hangup === 'function') {
                await session.hangup();
            } else if (typeof session.bye === 'function') {
                await session.bye();
            } else if (typeof session.terminate === 'function') {
                await session.terminate();
            }
        } catch (e) {
            const msg = String(e?.message || e || '');
            if (/WebSocket is already in CLOSING or CLOSED state/i.test(msg)) {
                console.warn('Hangup skipped: WebSocket already closed');
                try { this.callSessions = this.callSessions.filter(s => s !== session); } catch (_) {}
                if (this.currentSession === session) this.currentSession = null;
                this.dispatchEvent('callEnded', { session, reason: 'ws-closed' });
                return true;
            }
            throw e;
        }
        return true;
    }

    /**
     * Answer incoming call
     */
    async answerCall(session) {
        if (!session) {
            session = this.currentSession;
        }

        if (!session) {
            throw new Error('No call to answer');
        }

        try {
            // Try to resume audio contexts/elements as this is typically called from a user gesture (click)
            try {
                if (this._ringtoneCtx && typeof this._ringtoneCtx.resume === 'function' && this._ringtoneCtx.state === 'suspended') {
                    try { await this._ringtoneCtx.resume(); } catch (_) {}
                }
                if (this.ringtoneAudio && this.ringtoneAudio.paused) {
                    try { await this.ringtoneAudio.play(); } catch (_) {}
                }
                if (this.remoteAudio && this.remoteAudio.paused) {
                    try { await this.remoteAudio.play(); } catch (_) {}
                }
            } catch (e) {  }

            if (typeof session.answer === 'function') {
                await session.answer();
            } else if (typeof session.accept === 'function') {
                await session.accept();
            } else {
                throw new Error('Answer method not available');
            }

            this.currentSession = session;
            this.updateSessionListeners();
            this.dispatchEvent('callAnswered', { session });

            // Ensure ringtone is stopped after an answer
            try { this.stopRingtone(); } catch (_) {}

            // Ensure all other sessions are held so only this answered call stays active
            try {
                for (const s of this.callSessions) {
                    if (s && s !== session && typeof s.hold === 'function') {
                        try { await s.hold(); s._onHold = true; } catch (_) {}
                    }
                }
            } catch (_) {}

        } catch (error) {
            console.error('Failed to answer call:', error);
            try {
                const sid = session && (session.id || session.sessionId || session.partyId || session.callId || session._generatedId || null);
                const msg = (error && error.message) ? error.message : String(error);
                const isRecoverable = /InvalidStateError|signalingState is 'closed'|RTCPeerConnection/.test(msg);
                this.dispatchEvent('callError', { error: msg, code: error.name || 'AnswerError', sessionId: sid, recoverable: !!isRecoverable });
            } catch (e) {  }
            throw error;
        }
    }

    /**
     * Answer a specific session by id
     */
    async answerSession(sessionOrId) {
        const session = this.findSession(sessionOrId);
        if (!session) throw new Error('Target session not found');
        return this.answerCall(session);
    }

    /**
     * Reject incoming call
     */
    async rejectCall(session) {
        if (!session) {
            session = this.currentSession;
        }

        if (!session) {
            throw new Error('No call to reject');
        }

        try {
            if (typeof session.reject === 'function') {
                await session.reject();
            } else if (typeof session.decline === 'function') {
                await session.decline();
            } else {
                throw new Error('Reject method not available');
            }

            if (this.currentSession === session) {
                this.currentSession = null;
            }

            // Stop ringtone and cleanup UI/timers for this rejected session
            try { this.stopRingtone(); } catch (_) {}
            try { if (session && session._ringingInterval) { clearInterval(session._ringingInterval); session._ringingInterval = null; } } catch (_) {}
            try { if (session && session._durationInterval) { clearInterval(session._durationInterval); session._durationInterval = null; } } catch (_) {}
            try { this.cleanupIncomingSession(session); } catch (_) {}

            this.dispatchEvent('callRejected', { session });

        } catch (error) {
            console.error('Failed to reject call:', error);
            throw error;
        }
    }

    /**
     * End current call
     */
    async endCall() {
        if (!this.currentSession) {
            rcLog('No active call session to end');
                return;
            }

            try {
            // Handle if currentSession is a Promise (shouldn't happen, but just in case)
            let session = this.currentSession;
            if (session && typeof session.then === 'function') {
                rcLog('Session is a Promise, awaiting...');
                session = await session;
            }

            // Try different hangup methods
            if (typeof session.hangup === 'function') {
                await session.hangup();
                rcLog('Call ended via hangup()');
            } else if (typeof session.bye === 'function') {
                await session.bye();
                rcLog('Call ended via bye()');
            } else if (typeof session.terminate === 'function') {
                await session.terminate();
                rcLog('Call ended via terminate()');
            } else if (typeof session.reject === 'function') {
                await session.reject();
                rcLog('Call ended via reject()');
            } else {
                // Try to find hangup method in all properties
                const allProps = Object.getOwnPropertyNames(session);
                let hangupMethod = null;
                for (const prop of allProps) {
                    if ((prop.toLowerCase().includes('hangup') ||
                         prop.toLowerCase().includes('bye') ||
                         prop.toLowerCase().includes('terminate') ||
                         prop.toLowerCase().includes('end')) &&
                        typeof session[prop] === 'function') {
                        hangupMethod = session[prop].bind(session);
                        rcLog(`Found hangup method: session.${prop}()`);
                        break;
                    }
                }

                if (hangupMethod) {
                    await hangupMethod();
                } else {
                }
            }

            this.currentSession = null;
            this.dispatchEvent('callEnded', {});

            } catch (error) {
            console.error('Failed to end call:', error);
            // Clear session even if hangup fails
            this.currentSession = null;
            this.dispatchEvent('callEnded', {});
            throw error;
        }
    }

    /**
     * Toggle microphone mute
     */
    toggleMicrophone(muted = null) {
        if (!this.currentSession) {
            return false;
        }

        try {
            const currentlyMuted = this.isMuted();
            if (muted === null) {
                muted = !currentlyMuted;
            }

            rcLog('Toggling microphone:', { currentlyMuted, newState: muted });

            let success = false;
            if (typeof this.currentSession.setMute === 'function') {
                this.currentSession.setMute(muted);
                success = true;
            } else if (muted && typeof this.currentSession.mute === 'function') {
                this.currentSession.mute();
                success = true;
            } else if (!muted && typeof this.currentSession.unmute === 'function') {
                this.currentSession.unmute();
                success = true;
            }

            if (success) {
                this.micMuted = muted;
            } else {
                return currentlyMuted;
            }

            this.dispatchEvent('muteToggled', { muted });
            return this.micMuted;

    } catch (error) {
            console.error('Failed to toggle microphone:', error);
            return false;
        }
    }

    /**
     * Toggle mute for a specific session (tries session-level methods first)
     */
    async toggleSessionMute(sessionOrId, muted = null) {
        const session = sessionOrId && typeof sessionOrId === 'object' ? sessionOrId : this.findSession(sessionOrId) || this.currentSession;
        if (!session) throw new Error('No session to toggle mute for');

        try {
            // Determine current muted state for this session
            let currentlyMuted = false;
            if (session._muted !== undefined) currentlyMuted = session._muted;
            else if (typeof session.muted === 'function') currentlyMuted = session.muted();
            else if (session.muted !== undefined) currentlyMuted = !!session.muted;

            if (muted === null) muted = !currentlyMuted;

            let success = false;
            if (typeof session.setMute === 'function') {
                await session.setMute(muted);
                success = true;
            } else if (muted && typeof session.mute === 'function') {
                await session.mute();
                success = true;
            } else if (!muted && typeof session.unmute === 'function') {
                await session.unmute();
                success = true;
            } else if (session === this.currentSession) {
                // Fallback to instance-level toggle for currentSession
                const res = this.toggleMicrophone(muted);
                success = !!res;
            }

            if (success) {
                session._muted = !!muted;
                if (session === this.currentSession) this.micMuted = !!muted;
                this.dispatchEvent('muted', { session });
                return !!muted;
            }

            throw new Error('Mute/unmute not supported for this session');
        } catch (e) {
            console.error('toggleSessionMute failed', e);
            throw e;
        }
    }

    /**
     * Check if microphone is muted
     */
    isMuted() {
        if (!this.currentSession) {
            return false;
        }

        // Prefer tracked state if set
        if (this.micMuted !== undefined) {
            return this.micMuted;
        }

        if (typeof this.currentSession.muted === 'function') {
            return this.currentSession.muted();
        } else if (this.currentSession.muted !== undefined) {
            return this.currentSession.muted;
        }

        return false;
    }

    /**
     * Toggle speaker (mute/unmute remote audio)
     */
    toggleSpeaker(enabled = null) {
        if (!this.remoteAudio) {
            return false;
        }

        try {
            const currentlyEnabled = this.speakerEnabled;

            // If no parameter provided, toggle
            if (enabled === null) {
                enabled = !currentlyEnabled;
            }

            // Muted flag is inverse of enabled
            this.remoteAudio.muted = !enabled;
            // Adjust volume to emphasize toggle
            this.remoteAudio.volume = enabled ? 1 : 0;

            this.speakerEnabled = enabled;

            rcLog('Toggling speaker:', { currentlyEnabled, enabled, muted: this.remoteAudio.muted, volume: this.remoteAudio.volume });

            this.dispatchEvent('speakerToggled', { enabled });
            return enabled;

        } catch (error) {
            console.error('Failed to toggle speaker:', error);
            return false;
        }
    }

    /**
     * Set specific audio output device for remote audio (speakers/headphones)
     * Uses the SDK's session.changeOutputDevice() method during active calls
     */
    async setOutputDevice(deviceId) {
        if (!this.remoteAudio) {
            throw new Error('Remote audio element not available');
        }
        if (typeof this.remoteAudio.setSinkId !== 'function') {
            throw new Error('This browser does not support selecting audio output devices (setSinkId)');
        }
        try {
            // Store the device ID for future reference
            this.currentOutputDeviceId = deviceId;

            rcLog('ðŸ”Š Setting output device to:', deviceId);
            rcLog('ðŸ“ž Active call session:', this.currentSession ? 'YES' : 'NO');

            // If there's an active call session, use SDK's changeOutputDevice method
            if (this.currentSession && typeof this.currentSession.changeOutputDevice === 'function') {
                rcLog('ðŸŽ¯ Using SDK session.changeOutputDevice() method');
                await this.currentSession.changeOutputDevice(deviceId);
                rcLog('âœ… Audio output device changed via SDK session method');
            } else {
                // No active call or method not available - set on audio elements directly
                rcLog('ðŸ“» No active session - setting on audio elements directly');
                await this.remoteAudio.setSinkId(deviceId);
                rcLog('âœ… Audio output device set on remoteAudio element');

                // If there's a local audio element, apply there too
                if (this.localAudio && typeof this.localAudio.setSinkId === 'function') {
                    try {
                        await this.localAudio.setSinkId(deviceId);
                        rcLog('âœ… Audio output device also set on localAudio element');
                    } catch (e) {
                    }
                }
            }

            // Verify it was set
            if (this.remoteAudio.sinkId !== undefined) {
                rcLog('ðŸ” Verified remoteAudio.sinkId is now:', this.remoteAudio.sinkId);
            }

            return true;
        } catch (e) {
            console.error('âŒ Failed to set audio output device', e);
            throw e;
        }
    }

    /**
     * Test microphone for a session by acquiring local media and playing to `localAudio` for a short duration.
     */
    async testMicrophoneForSession(sessionOrId, durationMs = 5000) {
        await this.ensureMicrophoneAvailable();
        let stream = null;
        try {
            stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            this.createAudioElements();
            if (this.localAudio) {
                this.localAudio.srcObject = stream;
                try { await this.localAudio.play(); } catch (e) {  }
            }
            // stop after duration
            setTimeout(() => {
                try {
                    if (this.localAudio) {
                        try { this.localAudio.pause(); } catch (_) {}
                        try { this.localAudio.srcObject = null; } catch (_) {}
                    }
                    if (stream) {
                        try { stream.getTracks().forEach(t => t.stop()); } catch (_) {}
                    }
                } catch (_) {}
            }, durationMs);
            return true;
        } catch (e) {
            if (stream) try { stream.getTracks().forEach(t => t.stop()); } catch (_) {}
            throw e;
        }
    }

    /**
     * Test speaker by playing a short tone into the ringtone audio element.
     */
    async testSpeakerForSession(sessionOrId, durationMs = 3000) {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) throw new Error('AudioContext not supported');
            const ctx = new AudioCtx();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            gain.gain.value = 0.2;
            osc.type = 'sine';
            osc.frequency.value = 660;
            osc.connect(gain);
            const dest = ctx.createMediaStreamDestination();
            gain.connect(dest);

            // Attach to ringtoneAudio element and play
            this.createAudioElements();
            if (this.ringtoneAudio) {
                this.ringtoneAudio.srcObject = dest.stream;
                try { await this.ringtoneAudio.play(); } catch (e) {  }
            }
            osc.start();
            setTimeout(async () => {
                try { osc.stop(); } catch (_) {}
                try { await ctx.close(); } catch (_) {}
                try { if (this.ringtoneAudio) { this.ringtoneAudio.pause(); this.ringtoneAudio.srcObject = null; } } catch (_) {}
            }, durationMs);
            return true;
        } catch (e) {
            console.error('testSpeakerForSession failed', e);
            throw e;
        }
    }

    /**
     * Format phone number to E.164 format
     */
    formatPhoneNumber(phoneNumber) {
        if (!phoneNumber) return '';

        // Remove all non-digit characters except +
        let clean = phoneNumber.replace(/[^\d+]/g, '');

        // Ensure it starts with + (E.164 format)
        if (!clean.startsWith('+')) {
            // If it's a 10-digit US number, add +1
            if (clean.length === 10) {
                clean = '+1' + clean;
            } else if (clean.length === 11 && clean.startsWith('1')) {
                // If it's 11 digits starting with 1, add +
                clean = '+' + clean;
            } else {
                // Otherwise, just add +
                clean = '+' + clean;
            }
        }

        return clean;
    }

    /**
     * Start recording the current call
     */
    async startRecording() {
        if (!this.currentSession) {
            throw new Error('No active call to record');
        }

        try {
            // Check if session has recording methods
            if (typeof this.currentSession.startRecording === 'function') {
                await this.currentSession.startRecording();
                this.isRecording = true;
                this.isRecordingPaused = false;
                this.dispatchEvent('recordingStarted', {});
                return true;
            }
            this.dispatchEvent('recordingNotAllowed', { reason: 'Recording not supported' });
            return false;
        } catch (error) {
            console.error('Failed to start recording:', error);
            throw error;
        }
    }

    /**
     * Pause recording
     */
    async pauseRecording() {
        if (!this.currentSession || !this.isRecording) {
            return false;
        }

        try {
            if (typeof this.currentSession.pauseRecording === 'function') {
                await this.currentSession.pauseRecording();
                this.isRecordingPaused = true;
                this.dispatchEvent('recordingPaused', {});
                return true;
            }
            this.dispatchEvent('recordingNotAllowed', { reason: 'Pause not supported' });
            return false;
        } catch (error) {
            console.error('Failed to pause recording:', error);
            this.dispatchEvent('recordingNotAllowed', { reason: error.message });
            return false;
        }
    }

    /**
     * Resume recording
     */
    async resumeRecording() {
        if (!this.currentSession || !this.isRecording) {
            return false;
        }

        try {
            if (typeof this.currentSession.resumeRecording === 'function') {
                await this.currentSession.resumeRecording();
                this.isRecordingPaused = false;
                this.dispatchEvent('recordingResumed', {});
                return true;
            }
            this.dispatchEvent('recordingNotAllowed', { reason: 'Resume not supported' });
            return false;
        } catch (error) {
            console.error('Failed to resume recording:', error);
            this.dispatchEvent('recordingNotAllowed', { reason: error.message });
            return false;
        }
    }

    /**
     * Stop recording
     */
    async stopRecording() {
        if (!this.currentSession || !this.isRecording) {
            return false;
        }

        try {
            if (typeof this.currentSession.stopRecording === 'function') {
                await this.currentSession.stopRecording();
                this.isRecording = false;
                this.isRecordingPaused = false;
                this.dispatchEvent('recordingStopped', {});
                return true;
            }
            return false;
        } catch (error) {
            console.error('Failed to stop recording:', error);
            return false;
        }
    }

    /**
     * Check if recording is active
     */
    isRecordingActive() {
        return this.isRecording === true;
    }

    /**
     * Get current call session
     */
    getCurrentSession() {
        return this.currentSession;
    }

    /**
     * Check if call is active
     */
    isCallActive() {
        return this.currentSession !== null;
    }

    /**
     * Cleanup and dispose
     */
    async dispose() {
        try {
            rcLog('ðŸ§¹ Disposing WebPhone instance...');
            rcLog('ðŸ“Š Dispose state: webphone=' + (!!this.webphone) + ', userAgent=' + (!!this.userAgent));

            // End current call
            if (this.currentSession) {
                try {
                    rcLog('ðŸ“ž Ending current call before dispose...');
                    await this.endCall();
                    rcLog('âœ… Current call ended');
                } catch (e) {
                }
            }

            // Call WebPhone SDK's dispose() if available - this immediately unregisters from SIP
            if (this.webphone && typeof this.webphone.dispose === 'function') {
                try {
                    rcLog('ðŸ”Œ Calling SDK webphone.dispose()...');
                    await this.webphone.dispose();
                    rcLog('âœ… WebPhone SDK disposed - SIP instance unregistered');
                } catch (e) {
                }
            } else {
                rcLog('â„¹ï¸ WebPhone SDK dispose not available');
            }

            // Unregister userAgent if dispose didn't already do it
            if (this.userAgent && typeof this.userAgent.unregister === 'function') {
                try {
                    await this.userAgent.unregister();
                    rcLog('âœ… UserAgent unregistered');
                } catch (e) {
                }
            }

            // Stop auto-refresh
            try {
                this.stopAutoRefresh();
            } catch (e) {
            }
            try {
                this.stopTokenTimerSync();
            } catch (e) {
            }

            // Stop instance tracking
            try {
                this.stopInstanceTracking();
            } catch (e) {
            }

            // Stop SDK session recovery polling
            try {
                this.stopSessionRecoveryLoop();
            } catch (e) {
            }

            // Stop ringtone
            try {
                this.stopRingtone();
            } catch (e) {
            }

            // Remove audio elements
            if (this.remoteAudio && this.remoteAudio.parentNode) {
                this.remoteAudio.parentNode.removeChild(this.remoteAudio);
            }
            if (this.localAudio && this.localAudio.parentNode) {
                this.localAudio.parentNode.removeChild(this.localAudio);
            }
            if (this.ringtoneAudio && this.ringtoneAudio.parentNode) {
                this.ringtoneAudio.parentNode.removeChild(this.ringtoneAudio);
            }

            // Clean up internal state
            this.webphone = null;
            this.userAgent = null;
            this.currentSession = null;
            this.callSessions = [];
            this.isInitialized = false;
            this.isRegistered = false;
            this._updateDialBlockState();

            rcLog('âœ… WebPhone disposal complete');

        } catch (error) {
            console.error('Error disposing WebPhone:', error);
        }
    }

    /**
     * Dispatch custom event
     */
    dispatchEvent(eventName, detail) {
        const event = new CustomEvent(`ringcentral:${eventName}`, { detail });
        document.dispatchEvent(event);
    }
}

// Export for use in blade templates
window.RingCentralWebPhone = RingCentralWebPhone;

