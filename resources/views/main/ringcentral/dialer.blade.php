<!-- 📞 Dialer Modal -->
<link rel="stylesheet" href="/css/ringcentral-dialer.css">
<!-- Dialer Panel (Before & During Call) -->
<div id="dialerPanel" class="dialer-panel">
    <div class="dialer-panel-header">
        <h5 class="dialer-panel-title">Dialer</h5>
    </div>

    <div class="dialer-panel-body">

        <!-- ===== INCOMING CALL UI (Improved) ===== -->
        <div id="dialerIncomingCallSection" class="dialer-incoming-section">
            <div class="card border-0 rounded-4 shadow-sm dialer-incoming-card">
                <div class="dialer-incoming-header">
                    <div class="dialer-incoming-header-row">
                        <div class="dialer-incoming-header-left">
                            <div class="dialer-incoming-header-title">
                                <span id="incomingQueueCount">1</span> <span id="incomingQueueLabel">Call</span>
                            </div>
                            <div class="dialer-incoming-header-sub" id="incomingQueuePositionText">Call 1 of 1</div>
                        </div>
                        <div class="dialer-incoming-header-controls">
                            <button type="button" class="btn btn-sm dialer-incoming-nav-btn" id="incomingPrevCallBtn"
                                onclick="showPrevIncomingCall()" title="Previous incoming call">
                                <i class="fa fa-chevron-left"></i>
                            </button>
                            <button type="button" class="btn btn-sm dialer-incoming-nav-btn" id="incomingNextCallBtn"
                                onclick="showNextIncomingCall()" title="Next incoming call">
                                <i class="fa fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="dialer-incoming-header-tools">
                        <button type="button" class="btn btn-sm dialer-lost-calls-btn" id="incomingLostCallsBtn"
                            onclick="openIncomingLostCalls()">
                            Lost Calls <span id="incomingLostCallsCount" class="dialer-lost-calls-count">0</span>
                        </button>
                    </div>
                </div>

                <div class="card-body text-center p-3">
                    <div class="dialer-incoming-avatar">
                        <div class="dialer-incoming-avatar-ring">
                            <i class="fa fa-user-circle text-danger dialer-incoming-avatar-icon"></i>
                        </div>

                        <div class="dialer-incoming-details">
                            <h4 id="incomingCallName" class="dialer-incoming-name">Unknown
                                Caller</h4>
                            <div id="incomingCallNumber" class="dialer-incoming-number">+1 000000</div>
                            <div id="incomingCallRouting" class="dialer-incoming-routing">From <span
                                    id="incomingCallFrom">...</span> to <span id="incomingCallTo">...</span>
                            </div>
                        </div>
                    </div>

                    <div class="dialer-incoming-actions">
                        {{-- Listen / Ignore / More row --}}
                        <div class="dialer-incoming-top">
                            <div class="dialer-incoming-action">
                                <button type="button" class="btn dialer-top-btn" onclick="answerIncomingCall()"
                                    title="Coming soon" style="opacity:0.5;cursor:not-allowed;">
                                    <i class="fa fa-volume-up"></i>
                                </button>
                                <div class="dialer-incoming-action-label">Listen</div>
                            </div>
                            <div class="dialer-incoming-action">
                                <button type="button" class="btn dialer-top-btn" onclick="ignoreIncomingCall()">
                                    <i class="fa fa-minus"></i>
                                </button>
                                <div class="dialer-incoming-action-label">Ignore</div>
                            </div>
                            <div class="dialer-incoming-action">
                                <button type="button" class="btn dialer-top-btn" onclick="toggleIncomingQuickReply()"
                                    title="Coming soon" style="opacity:0.5;cursor:not-allowed;">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <div class="dialer-incoming-action-label" title="Coming soon"
                                    style="opacity:0.5;cursor:not-allowed;">More</div>
                            </div>
                        </div>

                        {{-- Decline / Answer row --}}
                        <div class="dialer-incoming-bottom">
                            <div class="dialer-incoming-action">
                                <button type="button" id="incomingDeclineBtn" onclick="declineIncomingCall()"
                                    class="btn rounded-circle dialer-incoming-btn dialer-incoming-decline">
                                    <i class="fa fa-phone dialer-incoming-decline-icon"></i>
                                </button>
                                <div class="dialer-incoming-action-label">Decline</div>
                            </div>

                            <div class="dialer-incoming-action">
                                <button type="button" id="incomingAnswerBtn" onclick="answerIncomingCall()"
                                    class="btn rounded-circle dialer-incoming-btn dialer-incoming-accept">
                                    <i class="fa fa-phone dialer-incoming-accept-icon"></i>
                                </button>
                                <div class="dialer-incoming-action-label">Answer</div>
                            </div>
                        </div>
                    </div>
                    <hr class="dialer-incoming-divider">

                </div>
            </div>
        </div>

        <!-- ===== BEFORE CALL UI (Dialer) ===== -->
        <div id="dialerBeforeCallSection" class="dialer-before-call">
            <!-- Active Calls Header (Shows when multiple calls) -->
            <div id="activeCallsHeader" class="dialer-active-calls">
                <div class="dialer-active-calls-title">
                    <span id="totalCallsCount">2</span> Calls
                </div>
                <div id="callsSwitcher" class="dialer-calls-switcher">
                    <!-- Held calls count badge with dropdown -->
                    <div id="heldCallsBadge" class="dialer-held-badge">
                        <button type="button" class="btn btn-sm dialer-held-badge-btn" id="heldCallsBadgeBtn"
                            title="Held calls" onclick="toggleCallsDropdown(event)">
                            <span id="heldCallsCount">1</span>
                        </button>
                        <!-- Dropdown -->
                        <div id="callsDropdown" class="dialer-calls-dropdown">
                            <!-- Populated by JavaScript -->
                        </div>
                    </div>
                    <!-- Calls list hamburger menu -->
                    <button type="button" class="btn btn-sm dialer-calls-list-btn" onclick="openCallsList()"
                        title="All calls">
                        <i class="fa fa-bars dialer-calls-list-icon"></i>
                    </button>
                </div>
            </div>

            <!-- Current Call Info (Shows when any active call) -->
            <div id="currentCallInfo" class="dialer-current-call">
                <div class="dialer-current-call-row">
                    <div>
                        <div id="multiCallDuration" class="dialer-current-call-duration">00:00
                        </div>
                        <div id="multiCallName" class="dialer-current-call-name">Unknown
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-light dialer-current-back-btn"
                        onclick="backToCallControls()">Back</button>
                </div>
            </div>

            <form id="callForm">
                <!-- From Number Selector -->
                <div class="mb-3 dialer-call-from-wrap">
                    <select id="callFromNumber" class="form-control form-control-sm dialer-call-from"
                        onchange="updateCallerIDDisplay()"></select>
                </div>

                <!-- Input field -->
                <div class="form-group mb-3">
                    <input type="tel" class="form-control form-control-lg text-center dialer-call-input" id="callPhone"
                        required placeholder="Enter a name or number" />
                </div>

                <!-- Keypad Grid -->
                <div class="dialer-keypad-grid">
                    <button type="button" class="btn dialer-keypad-btn" onclick="dialerPress('1')">1</button>
                    <button type="button" class="btn dialer-keypad-btn" onclick="dialerPress('2')">2<br /><span
                            class="dialer-keypad-sub">ABC</span></button>
                    <button type="button" class="btn dialer-keypad-btn" onclick="dialerPress('3')">3<br /><span
                            class="dialer-keypad-sub">DEF</span></button>

                    <button type="button" class="btn dialer-keypad-btn" onclick="dialerPress('4')">4<br /><span
                            class="dialer-keypad-sub">GHI</span></button>
                    <button type="button" class="btn dialer-keypad-btn" onclick="dialerPress('5')">5<br /><span
                            class="dialer-keypad-sub">JKL</span></button>
                    <button type="button" class="btn dialer-keypad-btn" onclick="dialerPress('6')">6<br /><span
                            class="dialer-keypad-sub">MNO</span></button>

                    <button type="button" class="btn dialer-keypad-btn" onclick="dialerPress('7')">7<br /><span
                            class="dialer-keypad-sub">PQRS</span></button>
                    <button type="button" class="btn dialer-keypad-btn" onclick="dialerPress('8')">8<br /><span
                            class="dialer-keypad-sub">TUV</span></button>
                    <button type="button" class="btn dialer-keypad-btn" onclick="dialerPress('9')">9<br /><span
                            class="dialer-keypad-sub">WXYZ</span></button>

                    <button type="button" class="btn dialer-keypad-btn" onclick="dialerPress('*')">*</button>
                    <button type="button" class="btn dialer-keypad-btn" onclick="dialerPress('0')">0<br /><span
                            class="dialer-keypad-sub">+</span></button>
                    <button type="button" class="btn dialer-keypad-btn" onclick="dialerPress('#')">#</button>
                </div>

                <!-- Call Button -->
                <div class="dialer-call-button-wrap">
                    <button type="submit" class="btn dialer-call-button" id="makeCallBtn" style="font-size: 12px;">
                        <i class="fa fa-phone"></i>
                    </button>
                    {{-- TEMP (publish-safe): conference start button disabled.
                    <button type="button" class="btn dialer-call-button" id="callFormConferenceBtn"
                        onclick="startConferenceInviteFromCallForm()" style="font-size: 12px; margin-left: 10px;"
                        title="Start conference">
                        <i class="fa fa-users"></i>
                    </button>
                    --}}
                </div>

                <!-- Bottom Actions -->
                <div class="d-flex justify-content-around mt-3">
                    <button type="button" class="btn btn-sm btn-link text-muted dialer-bottom-btn"
                        onclick="backspaceDialer()">⌫
                        Backspace</button>
                    <button type="button" class="btn btn-sm btn-link text-muted dialer-bottom-btn"
                        onclick="clearDialer()">✕ Clear</button>
                </div>

            </form>
        </div>

        <!-- ===== DURING CALL UI (Active Call) ===== -->
        <div id="dialerDuringCallSection" class="dialer-during-call">
            <!-- My Call Header Section -->
            <div class="dialer-call-header">
                <div class="dialer-call-header-main">
                    <div id="myCallsCount" class="dialer-call-count">1 Call</div>
                    <div class="dialer-call-divider"></div>
                    <div class="dialer-call-timer-wrap">
                        <div id="myCallTimer" class="dialer-call-timer">00:00</div>
                        <div id="myCallNumber" class="dialer-call-number">+1 000-000-0000</div>
                    </div>
                </div>
                <div class="dialer-call-header-actions">
                    <button type="button" class="btn btn-sm dialer-call-swap-btn" id="myCallSwapBtn"
                        onclick="openCallsList()" title="All calls">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>
            </div>

            <div id="dialerDuringIncomingBanner" class="dialer-during-incoming-banner" style="display:none;">
                <div class="dialer-during-incoming-left">
                    <div class="dialer-during-incoming-title">
                        Incoming Waiting: <span id="dialerDuringIncomingCount">0</span>
                    </div>
                    <div id="dialerDuringIncomingNumber" class="dialer-during-incoming-number">Unknown</div>
                </div>
                <div class="dialer-during-incoming-actions">
                    <button type="button" class="btn btn-sm btn-success text-dark"
                        onclick="answerIncomingCall()">Accept</button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="declineIncomingCall()">Reject</button>
                </div>
            </div>

            <!-- Call Duration Timer -->
            <div class="dialer-call-duration-wrap">
                <p class="text-muted fs-15 dialer-call-duration" id="dialerCallDuration">00:00 — On Call
                </p>
            </div>

            <!-- Call Info with Avatar -->
            <div class="dialer-call-info-wrap">
                <div class="mb-3">
                    <i class="fa fa-user-circle text-primary dialer-call-avatar-icon"></i>
                </div>
                {{-- TEMP (publish-safe): conference participants UI disabled.
                <button type="button"
                    id="dialerParticipantsBtn"
                    class="btn btn-sm dialer-participants-btn"
                    onclick="openConferenceParticipantsForCurrent()"
                    title="Participants"
                    style="display:none;">
                    <i class="fa fa-users"></i>
                    <span id="dialerParticipantsCount" class="dialer-participants-count">0</span>
                </button>
                --}}
                <div id="dialerCallInfo" class="dialer-call-info">
                    <div id="dialerRemoteName" class="dialer-call-remote-name">
                        Connected
                    </div>
                    <div id="dialerRemoteNumber" class="dialer-call-remote-number">(000)
                        000-0000</div>
                </div>
            </div>

            <!-- Call Controls Grid (3x3) -->
            <div class="row g-3 my-4">
                <!-- Row 1: Mute, Hold, Keypad -->
                <div class="col-4 text-center">
                    <button type="button" id="dialerMuteBtn" onclick="toggleMuteFromDialer()"
                        class="btn btn-light rounded-circle mx-auto d-flex align-items-center justify-content-center dialer-control-btn">
                        <i class="fa fa-microphone"></i>
                    </button>
                    <small id="dialerMuteLabel" class="d-block mt-2">Mute</small>
                </div>
                <div class="col-4 text-center">
                    <button type="button" id="dialerHoldBtn" onclick="holdCallFromDialer()"
                        class="btn btn-light rounded-circle mx-auto d-flex align-items-center justify-content-center dialer-control-btn">
                        <i class="fa fa-pause"></i>
                    </button>
                    <small id="dialerHoldLabel" class="d-block mt-2">Hold</small>
                </div>
                <div class="col-4 text-center">
                    <button type="button" id="dialerKeypadBtn" onclick="toggleKeypadInDialer()"
                        class="btn btn-light rounded-circle mx-auto d-flex align-items-center justify-content-center dialer-control-btn">
                        <i class="fa fa-phone"></i>
                    </button>
                    <small class="d-block mt-2">Keypad</small>
                </div>

                <!-- Row 2: Record, Transfer, Merge -->
                <div class="col-4 text-center">
                    <button type="button" id="dialerRecordBtn" onclick="pauseCallFromDialer()"
                        class="btn btn-light rounded-circle mx-auto d-flex align-items-center justify-content-center dialer-control-btn">
                        <i class="fa fa-circle"></i>
                    </button>
                    <small class="d-block mt-2">Record</small>
                </div>
                <div class="col-4 text-center">
                    <button type="button" id="dialerTransferBtn" onclick="transferCallFromDialer()"
                        class="btn btn-light rounded-circle mx-auto d-flex align-items-center justify-content:center dialer-control-btn"
                        title="Transfer call">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                    <small class="d-block mt-2">Transfer</small>
                </div>
                {{-- TEMP (publish-safe): merge control disabled.
                <div class="col-4 text-center">
                    <button type="button" id="dialerMergeBtn" disabled
                        class="btn btn-light rounded-circle mx-auto d-flex align-items-center justify-content-center dialer-control-btn">
                        <i class="fa fa-users"></i>
                    </button>
                    <small id="dialerMergeBtnLabel" class="d-block mt-2">Merge</small>
                </div>
                --}}

                <!-- Row 3: Speaker, Contacts, More -->
                <div class="col-4 text-center">
                    <button type="button" id="dialerAudioBtn" onclick="toggleAudioInDialer()"
                        class="btn btn-light rounded-circle mx-auto d-flex align-items-center justify-content-center dialer-control-btn">
                        <i class="fa fa-volume-up"></i>
                    </button>
                    <small class="d-block mt-2">Speaker</small>
                </div>
                <!-- <div class="col-4 text-center">
                    <button type="button" id="dialerSwitchToWebBtn" onclick="switchToWebFromDialer()"
                        class="btn btn-light rounded-circle mx-auto d-flex align-items-center justify-content-center dialer-control-btn"
                        title="Switch call to web device">
                        <i class="fa fa-exchange"></i>
                    </button>
                    <small class="d-block mt-2">Switch Web</small>

                </div> -->
                {{-- TEMP (publish-safe): invite participant control disabled.
                <div class="col-4 text-center">
                    <button type="button" class="btn dialer-call-button" id="callFormInviteBtn"
                        onclick="inviteCallFromDialer()" style="font-size: 12px; margin-left: 10px;"
                        title="Invite participant">
                        <i class="fa fa-user-plus"></i>
                    </button>
                    <small class="d-block mt-2">Invite</small>
                </div>
                --}}
            </div>

            <!-- End Call Button -->
            <div class="dialer-endcall-wrap">
                <button type="button" class="btn rounded-circle mt-3 dialer-endcall-btn" id="dialerEndCallBtn"
                    onclick="endCallFromDialer()">
                    <i class="fa fa-phone"></i>
                </button>
            </div>

            {{-- TEMP (publish-safe): invite participant modal disabled.
            <div id="dialerInviteOverlay" class="dialer-invite-overlay" onclick="closeInviteModal()"></div>
            <div id="dialerInviteModal" class="dialer-invite-modal">
                <div class="dialer-invite-modal-header">
                    <h6 class="dialer-invite-modal-title">Invite Participant</h6>
                    <button type="button" class="btn btn-sm dialer-invite-modal-close" onclick="closeInviteModal()">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="dialer-invite-modal-body">
                    <div class="form-group">
                        <label for="invitePhoneNumber" class="form-label">Phone Number</label>
                        <input type="tel" id="invitePhoneNumber" class="form-control form-control-lg"
                            placeholder="Enter phone number to invite" />
                    </div>
                    <div id="inviteLoadingSpinner" class="dialer-invite-spinner d-none">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Inviting...</span>
                        </div>
                        <span class="ms-2">Inviting...</span>
                    </div>
                </div>
                <div class="dialer-invite-modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="closeInviteModal()">Cancel</button>
                    <button type="button" class="btn btn-primary btn-sm" id="confirmInviteBtn"
                        onclick="confirmInviteCall()">Invite</button>
                </div>
            </div>
            --}}

            <!-- Transfer Modal Overlay -->
            <div id="dialerTransferOverlay" class="dialer-invite-overlay" onclick="closeTransferModal()"></div>
            <div id="dialerTransferModal" class="dialer-invite-modal">
                <div class="dialer-invite-modal-header">
                    <h6 class="dialer-invite-modal-title">Transfer Call</h6>
                    <button type="button" class="btn btn-sm dialer-invite-modal-close" onclick="closeTransferModal()">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="dialer-invite-modal-body">
                    <div class="form-group mb-3">
                        <label for="transferMode" class="form-label">Transfer Type</label>
                        <select id="transferMode" class="form-control form-control-sm">
                            <option value="blind">Blind Transfer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="transferPhoneNumber" class="form-label">Destination Number</label>
                        <input type="tel" id="transferPhoneNumber" class="form-control form-control-lg"
                            placeholder="Enter transfer destination number" />
                    </div>
                    <div id="transferLoadingSpinner" class="dialer-invite-spinner d-none">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Transferring...</span>
                        </div>
                        <span class="ms-2">Transferring...</span>
                    </div>
                </div>
                <div class="dialer-invite-modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm"
                        onclick="closeTransferModal()">Cancel</button>
                    <button type="button" class="btn btn-primary btn-sm" id="confirmTransferBtn"
                        onclick="confirmTransferCall()">Transfer</button>
                </div>
            </div>

            <!-- Audio Settings (Inside Modal Slider) -->
            <div id="dialerAudioSettingsOverlay" class="dialer-audio-overlay" onclick="closeMicTestModal()"></div>
            <div id="dialerAudioSettingsSection" class="dialer-audio-sheet">
                <div id="micTestStatus-global" class="mb-3 text-muted dialer-audio-status">
                    Applied audio settings to call</div>

                <div class="mb-4">
                    <label class="dialer-audio-label">Microphone source</label>
                    <select id="audioInputSelect-global" class="form-control form-control-sm dialer-audio-select">
                        <option>Communications - Microphone ...</option>
                    </select>
                    <div class="dialer-mic-test-row">
                        <button id="micRecordBtn-global" class="btn btn-outline-primary btn-sm dialer-mic-test-btn"
                            onclick="recordAndPlaybackMic('global',15000)">Test microphone</button>
                        <div id="micMeter-global" class="dialer-mic-meter">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="dialer-audio-label dialer-audio-label-inline">
                        Remove my background noise
                        <span class="dialer-bgnoise-info">
                            <i class="fa fa-info-circle"></i>
                        </span>
                    </label>
                    <div class="dialer-bgnoise-row">
                        <input type="checkbox" id="bgNoiseToggle" class="dialer-bgnoise-toggle">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="dialer-audio-label">Speaker source</label>
                    <select id="audioOutputSelect-speaker" class="form-control form-control-sm dialer-audio-select">
                        <option>Speakers (2- High Definition Au...)</option>
                    </select>
                    <button id="testSpeakerBtn-global" class="btn btn-outline-primary btn-sm dialer-speaker-test-btn"
                        onclick="playTestSpeakerForSession('global',3000)">Test speaker</button>
                    <div class="dialer-speaker-controls">
                        <button class="dialer-speaker-icon-btn">🔊</button>
                        <input type="range" id="speakerVolume" min="0" max="100" value="50"
                            class="dialer-speaker-volume">
                        <button class="dialer-speaker-icon-btn">🔊</button>
                    </div>
                </div>

                <div id="micTestPlayback-global" class="mb-3"></div>
            </div>
        </div>

    </div>

    <!-- Calls List Panel (Sidebar-style design inside modal) -->
    <div id="dialerCallsListPanel" class="calls-list-panel-hidden">
        <div class="swap-header">
            <h6>Calls</h6>
            <button type="button" onclick="closeCallsList()">
                <i class="fa fa-times"></i>
            </button>
        </div>
        <div class="calls-list-content">
            <!-- Calls populated here -->
        </div>
    </div>
</div>
