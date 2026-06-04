@extends('layouts.mainsite')

@section('styles')
    <link rel="stylesheet" href="/css/ringcentral.css">
    <link rel="stylesheet" href="/css/ringcentral-messages.css">
@endsection

@php
    $rcIsLocalTesting = (bool) config('features.ringcentral_local_testing');
@endphp

@section('content')

    <!-- Checking Connection UI - Shown by default while verifying connection -->

    <div id="checkingConnectionUI" style="display:block;">
        <div class="alert alert-info">
            <div class="rc-checking-flex">
                <span class="rc-spinner-wrapper">
                    <span class="rc-spinner"></span>
                </span>
                <span>Checking R-Dialer  connection...</span>
            </div>
        </div>
    </div>

    <div id="connectedUI" style="display:none;">
        <div id="rc-token-progress-bar" aria-hidden="true"></div>
        <div class="row">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="top-header">
                    @php
                        $rcUserName = $ringCentralUser?->user?->name ?? 'User';
                        $rcPhoneRaw = $ringCentralUser->phone_number ?? '';
                        $rcDigits = preg_replace('/\D+/', '', (string) $rcPhoneRaw);
                        $rcMasked = $rcDigits ? str_repeat('*', max(0, strlen($rcDigits) - 4)) . substr($rcDigits, -4) :
                            'Loading...';
                    @endphp
                    @php $rcInitials = strtoupper(substr(str_replace(' ', '', $rcUserName), 0, 2)); @endphp

                    <div class="rc-user-menu-wrap" id="rcUserMenuWrap">
                        <div class="brand" id="rcUserMenuToggle" onclick="rcToggleUserMenu(event)" style="cursor:pointer;"
                            title="Account menu" role="button" tabindex="0" aria-haspopup="true" aria-expanded="false"
                            aria-controls="rcUserDropdown">
                            <div class="avatar">{{ $rcInitials }}</div>
                            <h1>
                                <strong id="connectedPhoneNumber" data-full-number="{{ $rcPhoneRaw }}">{{ $rcUserName }} -
                                    {{ $rcMasked }}</strong>

                                {{-- WebPhone loading indicator --}}
                                <span id="webphoneLoadingIndicator"
                                    style="display: inline-flex; align-items: center; margin-left: 10px; vertical-align: middle;">
                                    <div class="webphone-loader">
                                        <span class="webphone-loader-text">loading</span>
                                        <span class="webphone-load"></span>
                                    </div>
                                </span>
                            </h1>
                        </div>

                        <!-- User account dropdown -->
                        <div class="rc-user-dropdown" id="rcUserDropdown" hidden style="display:none;">

                            {{-- Profile --}}
                            <div class="rc-ud-profile">
                                <div class="rc-ud-avatar-lg">{{ $rcInitials }}</div>
                                <div>
                                    <div class="rc-ud-name">{{ $rcUserName }}</div>
                                    <a href="#" class="rc-ud-link">View profile</a>
                                </div>
                            </div>

                            <div class="rc-ud-divider"></div>

                            {{-- Status --}}
                            <div class="rc-ud-status-row">
                                <span class="rc-ud-status-dot"></span>
                                <span class="rc-ud-status-text">Available</span>
                                <i class="fa fa-angle-down" style="color:#6c757d;font-size:11px;"></i>
                                <div class="rc-ud-status-sep"></div>
                                <input type="text" class="rc-ud-status-input" placeholder="Set status message">
                            </div>
<!-- 
                            <div class="rc-ud-divider"></div>

                            {{-- Settings --}}
                            <div class="rc-ud-item">Incoming call rules</div>
                            <div class="rc-ud-item rc-ud-item-split">
                                <span>Accept queue calls</span>
                                <div class="rc-ud-item-right">
                                    <span class="rc-ud-edit-link">Edit</span>
                                    <div class="rc-ud-toggle is-on" onclick="this.classList.toggle('is-on'); event.stopPropagation();"></div>
                                </div>
                            </div>
                            <div class="rc-ud-item">Auto-replies</div>

                            <div class="rc-ud-divider"></div>

                            <div class="rc-ud-item">Add account</div>

                            <div class="rc-ud-divider"></div>

                            <div class="rc-ud-item">Manage company account</div>
                            <div class="rc-ud-item">Download desktop app</div>
                            <div class="rc-ud-item">Check for updates</div>
                            <div class="rc-ud-item rc-ud-item-arrow">
                                <span>Help</span>
                                <i class="fa fa-angle-right" style="color:#adb5bd;"></i>
                            </div> -->

                            <div class="rc-ud-divider"></div>

                            <div class="rc-ud-item rc-ud-signout" onclick="logout()">Sign out</div>
                        </div>
                    </div>

                    <div class="header-icons">
                        <span class="icon" title="Coming Soon" style="opacity:0.5;cursor:not-allowed;">⚙</span>
                        <span class="icon" title="Coming Soon" style="opacity:0.5;cursor:not-allowed;">＋</span>
                        <div id="dialerMinimizedCall" class="dialer-minimized">
                            <button type="button" id="dialerMinimizedOpenBtn"
                                onclick="restoreDialerFromMinimized()">Open</button>
                            <span class="dialer-minimized-timer" id="dialerMinimizedTimer">00:00</span>
                            <span class="dialer-minimized-number" id="dialerMinimizedNumber">Active call</span>
                            <div id="dialerMinimizedIncomingActions" style="display:none;gap:6px;align-items:center;">
                                <button type="button" onclick="answerIncomingCall()">Answer</button>
                                <button type="button" onclick="declineIncomingCall()">Reject</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end px-2">
            <div><button class="btn btn-outline-warning" id="clearRcStorageBtn" onclick="clearRcBrowserStorage()"
                    style="{{ $rcIsLocalTesting ? '' : 'display:none;' }}">Clear
                    Browser Session</button></div>
            <div><button class="btn btn-secondary" id="openDebugBtn"
                    style="{{ $rcIsLocalTesting ? '' : 'display:none;' }}">Debug Info</button></div>
            <div><button onclick="logout()" class="btn btn-danger" id="rcDisconnectBtn"
                    style="{{ $rcIsLocalTesting ? '' : 'display:none;' }}">Disconnect</button></div>
        </div>

        <!-- Tabbed panels (Messages, Calls, Voicemail, Recordings) -->
        <div class="col-md-12 p-0">
            <div class="mb-3" style="overflow: hidden; background: #fff;">
                <div class="row h-100">

                    <!-- Sidebar -->
                    <div class="col-2 col-md-1 px-0">
                        <div class="list-group rc-tabs" role="tablist">
                            <a class="list-group-item list-group-item-action" data-toggle="tab" href="#tabCalls" role="tab">
                                <span class="rc-icon-wrapper">
                                    <i class="fa fa-phone"></i>
                                    <span id="rcCallsMissedBadge" class="badge bg-danger ms-1 is-hidden">0</span>
                                </span>
                                <span>Calls</span>
                            </a>

                            <a class="list-group-item list-group-item-action active rc-tab-item" data-toggle="tab"
                                href="#tabMessages" role="tab">

                                <span class="rc-icon-wrap">
                                    <i class="fa fa-comment"></i>
                                    <span id="rcTextUnreadBadge" class="badge d-none">0</span>
                                </span>

                                <span>Text</span>
                            </a>

                            <a class="list-group-item list-group-item-action rc-tab-item" data-toggle="tab"
                                href="#tabSettings" role="tab">
                                <span class="rc-icon-wrap">
                                    <i class="fa fa-cog"></i>
                                </span>
                                <span>Settings</span>
                            </a>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="col-10 col-md-11 col-lg-3 px-0 rc-main-tabs-col"
                        style="overflow: hidden; border-left: 1px solid #dee2e6;">
                        <div class="tab-content rc-main-tab-content">
                            @include('main.ringcentral.messages')
                            @include('main.ringcentral.calls')
                            @include('main.ringcentral.settings')
                        </div>
                    </div>

                    <!-- Single Chat Panel -->
                    <div class="col-12 col-lg-8" style="border-left: 1px solid #dee2e6; padding: 20px;">
                        <div id="chatDesktopSlot"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Disconnected UI - Shown when not connected -->
    <div id="disconnectedUI" style="display:none;">
        <div class="alert alert-warning">
            Not connected to R-Dialer .
            <button id="reconnectBtn" class="btn btn-outline-primary ml-2">Reconnect</button>
            <button id="connectBtn" class="btn btn-primary ml-2"
                onclick="window.location.href='{{ route('ringcentral.auth') }}'">Connect
                to R-Dialer </button>
            <div id="reconnectStatus" class="small text-muted mt-2" style="display:none;"></div>
        </div>
    </div>

    <!-- Global Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">⚠️ Something went wrong</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="errorModalMessage" class="mb-2">An unexpected error occurred.</p>
                    <p class="small text-muted" id="errorModalHint" style="display:none;">You may need to reload the
                        page to
                        recover.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="errorModalReloadBtn">Reload Page</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Debug Info Modal -->
    <div class="modal fade" id="debugModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Debug Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Lib:</strong> <span id="rcDebugLib">Not loaded</span></p>
                    <p><strong>Token:</strong> <span id="rcDebugToken">Not initialized</span></p>
                    <p><strong>Session:</strong> <span id="rcDebugSession">—</span></p>
                    <div class="mb-3">
                        <button id="fetchTokenBtn" class="btn btn-info btn-sm">Fetch Token</button>
                    </div>
                    <h6>Log:</h6>
                    <pre id="rcDebugLog" class="rc-debug-log"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

<script>
    // Console helpers for local testing:
    // showOpenDebugBtn()
    // showRcDisconnectBtn()
    // showClearRcStorageBtn()
    // showRingCentralButtons()
    window.showRingCentralButton = function (buttonId) {
        const btn = document.getElementById(buttonId);
        if (!btn) {
            console.log('#' + buttonId + ' not found');
            return null;
        }

        btn.style.display = 'inline-block';
        btn.hidden = false;
        btn.classList.remove('d-none');
        console.log('Showing #' + buttonId, btn);
        return btn;
    };

    window.showOpenDebugBtn = function () {
        return window.showRingCentralButton('openDebugBtn');
    };

    window.showRcDisconnectBtn = function () {
        return window.showRingCentralButton('rcDisconnectBtn');
    };

    window.showClearRcStorageBtn = function () {
        return window.showRingCentralButton('clearRcStorageBtn');
    };

    window.showRingCentralButtons = function () {
        ['openDebugBtn', 'rcDisconnectBtn', 'clearRcStorageBtn'].forEach(function (buttonId) {
            window.showRingCentralButton(buttonId);
        });
    };
    window.__RC_PORTAL_BOOTSTRAPPED = true;
    const RC_PORTAL_WINDOW_NAME = 'RingCentralPortal';
    const RC_PORTAL_ALIVE_KEY = 'rcPortalAlive';
    const RC_PORTAL_FOCUS_REQUEST_KEY = 'rcPortalFocusRequest';
    const RC_PORTAL_CHANNEL_NAME = 'rcPortalBridge';
    const RC_PORTAL_ALIVE_PING_MS = 15000;
    const RC_PORTAL_INSTANCE_ID = 'rcp_' + Date.now() + '_' + Math.random().toString(36).slice(2, 10);
    let rcPortalBridgeChannel = null;

    try {
        window.name = RC_PORTAL_WINDOW_NAME;
    } catch (_) { }

    try {
        if ('BroadcastChannel' in window) {
            rcPortalBridgeChannel = new BroadcastChannel(RC_PORTAL_CHANNEL_NAME);
        }
    } catch (_) {
        rcPortalBridgeChannel = null;
    }

    function rcPortalReadAliveRecord() {
        try {
            const raw = localStorage.getItem(RC_PORTAL_ALIVE_KEY);
            if (!raw) return null;
            if (/^\d+$/.test(raw)) {
                return { ts: parseInt(raw, 10), legacy: true };
            }
            const parsed = JSON.parse(raw);
            return parsed && typeof parsed === 'object' ? parsed : null;
        } catch (_) {
            return null;
        }
    }

    function rcPortalAliveRecordIsFresh(record) {
        const ts = parseInt((record && (record.ts || record.timestamp || record.createdAt)) || '0', 10);
        return !!ts && ((Date.now() - ts) <= (RC_PORTAL_ALIVE_PING_MS * 4));
    }

    function rcPortalReadPendingRequest(key) {
        try {
            const raw = localStorage.getItem(key);
            if (!raw) return null;
            const parsed = JSON.parse(raw);
            return parsed && typeof parsed === 'object' ? parsed : null;
        } catch (_) {
            return null;
        }
    }

    function rcPortalHasFreshVisibleHandoff() {
        const keys = ['rcPendingDialRequest', 'rcPendingMessageRequest'];
        for (let i = 0; i < keys.length; i++) {
            const pending = rcPortalReadPendingRequest(keys[i]);
            if (!pending || !pending.visibleHandoffId) continue;

            const createdAt = parseInt(pending.createdAt || '0', 10);
            if (!createdAt || ((Date.now() - createdAt) > 45000)) continue;

            if (pending.targetPortalInstanceId) {
                return pending.targetPortalInstanceId === RC_PORTAL_INSTANCE_ID;
            }

            if (document.hidden || document.visibilityState !== 'visible') continue;
            if (typeof document.hasFocus === 'function' && !document.hasFocus()) continue;

            try {
                if (sessionStorage.getItem(RC_VISIBLE_HANDOFF_SESSION_KEY) === pending.visibleHandoffId) {
                    return true;
                }
            } catch (_) {
                return true;
            }
        }

        return false;
    }

    function rcPortalIsActiveInstance() {
        if (rcPortalHasFreshVisibleHandoff()) return true;

        const alive = rcPortalReadAliveRecord();
        if (!rcPortalAliveRecordIsFresh(alive)) return true;
        if (!alive.instanceId) return true;
        return alive.instanceId === RC_PORTAL_INSTANCE_ID;
    }

    window.RC_PORTAL_INSTANCE_ID = RC_PORTAL_INSTANCE_ID;
    window.rcPortalIsActiveInstance = rcPortalIsActiveInstance;

    function rcPortalPingAlive() {
        if (!rcPortalIsActiveInstance()) return;

        const payload = {
            instanceId: RC_PORTAL_INSTANCE_ID,
            ts: Date.now(),
            href: window.location.href
        };

        try {
            localStorage.setItem(RC_PORTAL_ALIVE_KEY, JSON.stringify(payload));
        } catch (_) { }

        try {
            if (rcPortalBridgeChannel) {
                rcPortalBridgeChannel.postMessage({
                    type: 'portal-alive',
                    payload: payload
                });
            }
        } catch (_) { }
    }

    function rcPortalHandleFocusRequest() {
        if (!rcPortalIsActiveInstance()) return;
        rcPortalPingAlive();
        try { window.focus(); } catch (_) { }
    }

    rcPortalPingAlive();
    setInterval(rcPortalPingAlive, RC_PORTAL_ALIVE_PING_MS);
    window.addEventListener('focus', rcPortalPingAlive);
    window.addEventListener('storage', function (event) {
        if (event.key !== RC_PORTAL_FOCUS_REQUEST_KEY || !event.newValue) return;
        rcPortalHandleFocusRequest();
    });
    try {
        if (rcPortalBridgeChannel) {
            rcPortalBridgeChannel.addEventListener('message', function (event) {
                const data = event && event.data ? event.data : {};
                if (data.type === 'focus' || data.type === 'dial-request' || data.type === 'message-request') {
                    rcPortalHandleFocusRequest();
                }
            });
        }
    } catch (_) { }
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) rcPortalPingAlive();
    });
    window.addEventListener('beforeunload', function () {
        try {
            const alive = rcPortalReadAliveRecord();
            if (alive && alive.instanceId === RC_PORTAL_INSTANCE_ID) {
                localStorage.removeItem(RC_PORTAL_ALIVE_KEY);
            }
        } catch (_) { }
    });

    window.RC_ROUTES = {
        checkConnectionStatus: "{{ route('ringcentral.check-connection-status') }}",
        'ringcentral.check-connection-status': "{{ route('ringcentral.check-connection-status') }}",
        'ringcentral.api.base': "{{ route('ringcentral.api.base') }}",
        'ringcentral.api.calls': "{{ route('ringcentral.api.calls') }}",
        'ringcentral.api.calls.summary': "{{ route('ringcentral.api.calls.summary') }}",
        'ringcentral.api.calls.mark-seen': "{{ route('ringcentral.api.calls.mark-seen') }}",
        'ringcentral.api.messages': "{{ route('ringcentral.api.messages') }}",
        'ringcentral.api.messages.mark-read': "{{ route('ringcentral.api.messages.mark-read') }}",
        'ringcentral.api.attachment': "{{ route('ringcentral.api.attachment') }}",
        'ringcentral.api.events.stream': "{{ route('ringcentral.api.events.stream') }}",
        'ringcentral.api.templates': "{{ route('ringcentral.api.templates') }}",
        'ringcentral.api.templates.create': "{{ route('ringcentral.api.templates.create') }}",
        'ringcentral.api.send-sms': "{{ route('ringcentral.api.send-sms') }}",
        'ringcentral.api.phone-numbers': "{{ route('ringcentral.api.phone-numbers') }}",
        'ringcentral.api.voicemails': "{{ route('ringcentral.api.voicemails') }}",
        'ringcentral.api.voicemails.mark-status': "{{ route('ringcentral.api.voicemails.mark-status') }}",
        'ringcentral.api.voicemail': "{{ route('ringcentral.api.voicemail', ['id' => ':id']) }}",
        'ringcentral.api.voicemail.delete': "{{ route('ringcentral.api.voicemail.delete', ['id' => ':id']) }}",
        'ringcentral.api.recording': "{{ route('ringcentral.api.recording', ['id' => ':id']) }}",
        'ringcentral.refreshRecordings': "{{ route('ringcentral.refreshRecordings') }}",
        'ringcentral.api.webphone-token': "{{ route('ringcentral.api.webphone-token') }}",
        'ringcentral.api.webphone-token-timer': "{{ route('ringcentral.api.webphone-token-timer') }}",
        // TEMP (publish-safe): conference routes disabled.
        // 'ringcentral.api.conference': "{{ route('ringcentral.api.conference') }}",
        // 'ringcentral.api.bring-in': "{{ route('ringcentral.api.bring-in', ['sessionId' => ':sessionId']) }}",
        'ringcentral.api.call-control.sessions': "{{ route('ringcentral.api.call-control.sessions') }}",
        'ringcentral.api.call-control.transfer': "{{ route('ringcentral.api.call-control.transfer', ['sessionId' => ':sessionId', 'partyId' => ':partyId']) }}",
        // TEMP (publish-safe): merge route disabled.
        // 'ringcentral.api.call-control.merge': "{{ route('ringcentral.api.call-control.merge') }}",
        'ringcentral.api.call-control.switch-to-web': "{{ route('ringcentral.api.call-control.switch-to-web', ['sessionId' => ':sessionId', 'partyId' => ':partyId']) }}",
        'ringcentral.api.close-instance': "{{ route('ringcentral.api.close-instance') }}",
        'ringcentral.api.blocked-numbers.list': "{{ route('ringcentral.api.blocked-numbers.list') }}",
        'ringcentral.api.blocked-numbers.add': "{{ route('ringcentral.api.blocked-numbers.add') }}",
        'ringcentral.api.blocked-numbers.remove': "{{ route('ringcentral.api.blocked-numbers.remove', ['id' => ':id']) }}",
        'ringcentral.api.blocked-numbers.check': "{{ route('ringcentral.api.blocked-numbers.check') }}",
        'ringcentral.api.blocked-numbers.settings.get': "{{ route('ringcentral.api.blocked-numbers.settings.get') }}",
        'ringcentral.api.blocked-numbers.settings.update': "{{ route('ringcentral.api.blocked-numbers.settings.update') }}",
        'ringcentral.reconnect': "{{ route('ringcentral.reconnect') }}",
        'logout': "{{ route('ringcentral.logout') }}"
    };

    window.RC_FEATURES = {
        callControl: {{ config('features.ringcentral_call_control') ? 'true' : 'false' }}
    };

    // Keep all displayed timestamps in browser local PC time.
    window.RC_DATE_FORMAT = Object.assign({}, window.RC_DATE_FORMAT || {}, { offsetHours: null });

    window.RC_INITIAL_PAGE_SIZE = 30;
    window.RC_REFRESH_SYNC_COUNT = 30;
    window.RC_WEBPHONE_REFRESH_SECONDS = {{ (int) config('services.ringcentral.token_refresh_window_seconds', 300) }};
    window.RC_SHOW_TOKEN_COUNTDOWN = @json($rcIsLocalTesting);
    window.RC_MESSAGES_INITIAL_BEFORE_CURSOR = '2026-04-17T15:31:49-04:00|26240';
</script>

<script src="/js/ringcentral-webrtc-phone.js"></script>
<!-- Include extracted R-Dialer modules -->
<script src="/js/ringcentral-events.js"></script> <!-- Moved to top for early error handling -->
<script src="/js/ringcentral-utilities.js"></script>
<script src="/js/ringcentral-modals.js"></script>
<script src="/js/ringcentral-action-buttons.js"></script>
<script src="/js/ringcentral-tabs.js"></script>
<script src="/js/ringcentral-webphone-loader.js"></script>
<script src="/js/ringcentral-webphone-init.js?v={{ filemtime(public_path('js/ringcentral-webphone-init.js')) }}"></script>
<script src="/js/ringcentral-call-control-init.js"></script>
<script src="/js/ringcentral-webphone-cleanup.js"></script>
<script src="/js/ringcentral-debug.js"></script>
<script src="/js/ringcentral-datetime.js"></script>
<script src="/js/ringcentral-blocked-numbers.js"></script>
<script src="/js/ringcentral-calls.js"></script>
<script src="/js/ringcentral-messages.js"></script>
<script src="/js/ringcentral-recordings.js"></script>
<script src="/js/ringcentral-voicemail.js"></script>
<script src="/js/ringcentral-dialer-controls.js"></script>
<script src="/js/ringcentral-audio-controls.js"></script>
<script src="/js/ringcentral-data-loading.js?v={{ filemtime(public_path('js/ringcentral-data-loading.js')) }}"></script>
<script src="/js/ringcentral-audio-settings.js"></script>
<script src="/js/ringcentral-incoming-calls.js"></script>
