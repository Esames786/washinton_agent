

<!-- Messages Tab & Modal -->
<div class="tab-pane active" id="tabMessages" role="tabpanel">
    <div id="messagesPanel" class="mb-3">
        <!-- Send SMS moved into modal; open with button -->
        <div class="my-2 px-2 d-flex justify-content-between align-items-center">
            <div class="rc-flex-gap-8">
                <div style="font-size: 18px; font-weight: 600;">Text</div>

            </div>
            <div class="d-flex align-items-center rc-flex-gap-8">
                <div id="messagesLoadMore" style="display:none;"></div>
                <button type="button" class="ui-btn" data-toggle="modal" data-target="#sendMessageModal"><i
                        class="fa fa-comment"></i></button>
            </div>
        </div>
        <hr class="my-2">

        <div class="d-flex align-items-center flex-wrap gap-3">

            <div class="rc-flex-gap-6" style="flex:1">
                <div class="position-relative" style="display:inline-block;width:100%;">
                    <input id="tabMessagesSearch" class="form-control form-control-sm rc-msg-search pr-4"
                        placeholder="Search messages..." style="padding-right:2rem;" />
                    <button type="button" id="tabMessagesSearchClear" aria-label="Clear search"
                        style="position:absolute;right:6px;top:50%;transform:translateY(-50%);border:none;background:transparent;padding:0;margin:0;display:none;font-size:1.1rem;line-height:1;color:#888;z-index:2;">
                        &times;
                    </button>
                </div>
            </div>
            <div class="rc-flex-gap-8">
                <button type="button" id="tabMessagesAllBtn" class="btn btn-sm rc-filter-btn is-active">All</button>
                <div class="rc-filter-dropdown" id="tabMessagesFilterDropdown">
                    <button type="button" id="tabMessagesFilterBtn"
                        class="btn btn-sm rc-filter-btn rc-filter-dropdown-toggle"
                        aria-haspopup="true"
                        aria-expanded="false">
                        <span id="tabMessagesFilterBtnText">Filter</span>
                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                    </button>
                    <div id="tabMessagesFilterMenu" class="rc-filter-dropdown-menu d-none" role="menu" aria-label="Message filter options">
                        <button type="button" class="rc-filter-dropdown-item" data-filter="unread">Unread</button>
                        <button type="button" class="rc-filter-dropdown-item" data-filter="muted">Muted</button>
                        <button type="button" class="rc-filter-dropdown-item" data-filter="favourites">Favorites</button>
                        <button type="button" class="rc-filter-dropdown-item" data-filter="draft">Draft</button>
                        <button type="button" class="rc-filter-dropdown-item" data-filter="failed">Failed</button>
                    </div>
                </div>
                <select id="tabMessagesTypeFilter" class="form-select form-select-sm rc-filter-select d-none">
                    <option value="">Filter</option>
                    <option value="unread">Unread</option>
                    <!-- <option value="muted">Muted</option> -->
                    <!-- <option value="favourites">Favorites</option> -->
                    <!-- <option value="draft">Draft</option> -->
                    <!-- <option value="failed">Failed</option> -->
                </select>
            </div>
        </div>
        <div id="messagesList" class="list-group mb-2 rc-scroll-tall">
            <div id="messagesLoading" class="rc-messages-list-loading" style="display:none;">
                <div class="d-inline-flex align-items-center px-3 py-2 rounded-pill rc-loading-pill">
                    <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
                    <span class="ms-2 text-muted" data-label>Refreshing messages...</span>
                </div>
            </div>
            <!-- Threads populated by JS -->
        </div>
        <!-- MOBILE SLOT -->
        <div id="chatMobileSlot"></div>
    </div>
</div>


{{-- .........slot... --}}
<div id="chatViewCard" class="card border-0 bg-light rounded-4 mt-3 d-none" style="height: 82vh">

    <div class="d-flex justify-content-between bg-white">
        <div class="card-header border-bottom d-flex align-items-center p-2">
            <button class="btn btn-sm btn-link" id="backToList">
                <i class="fa fa-arrow-left"></i>
            </button>
            <h6 class="mb-0 ms-2" id="chatUserName">Contact</h6>

        </div>
        <button type="button" class="btn btn-sm border-0" id="chatCallBtnRC"><i
                class="fa fa-phone"></i></button>
    </div>

    <div class="card-body p-2 rc-chat-inbox" id="chatInboxContent"></div>

    <div class="card-footer bg-white border-top p-3">
        <div class="d-flex align-items-center gap-2 mb-2" style="gap:8px">
            <button type="button" class="chat-icon-btn" id="rcUseTemplateBtn" aria-label="Use Template"><i
                    class="fa fa-file-text-o"></i></button>
            <button type="button" class="chat-icon-btn" id="rcEmojiBtn"><i class="fa fa-smile-o"></i></button>
            <button type="button" class="chat-icon-btn" id="rcAttachBtn"><i class="fa fa-paperclip"></i></button>
            <input type="file" id="rcChatAttachmentInput" class="d-none" multiple
                accept="image/*,video/*,audio/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
        </div>
        <div id="rcEmojiPicker" class="rc-emoji-picker d-none"></div>
        <div id="rcChatAttachmentPreview" class="rc-attachment-preview"></div>
        <div class="position-relative">
            <textarea class="form-control" id="chatMessageInputRC" placeholder="Type a message..." rows="1"
                style="resize: none; min-height: 42px; max-height: 220px; overflow-y: auto; padding-right: 84px; height: 1px;"
                oninput="this.style.height='auto'; this.style.height=(Math.min(this.scrollHeight,220))+'px';"></textarea>
            <button class="send-ui-btn position-absolute" id="sendChatBtnRC" style="right: 8px; bottom: 8px;" aria-label="Send message"><i class="fa fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<!-- Send New Message Modal -->
<div class="modal fade" id="sendMessageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send New Message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="smsForm">
                    <!-- <div id="smsFormGlobalError" class="rc-sms-global-error"></div> -->
                    <div class="mb-2">
                        <label class="form-label"><strong>From</strong></label>
                        <input type="hidden" id="smsFromNumber">
                        <div class="form-control rc-readonly-input" id="smsFromNumberDisplay">
                            Loading...</div>
                    </div>

                    <div class="row g-2">
                        <div class="col-12 mb-2" id="rcTemplateSelectModalGroup" style="display:none;">
                            <label class="form-label">Template</label>
                            <select id="rcTemplateSelectModal" class="form-select form-select-sm">
                                <option value="">Templates</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="hidden" id="smsPhone">
                            <div id="smsRecipientsWrap" class="form-control p-2" style="min-height:44px;">
                                <div id="smsRecipientChips" class="d-flex flex-wrap align-items-center" style="gap:6px;">
                                    <input type="tel" id="smsPhoneEntry" class="border-0 flex-grow-1"
                                        style="min-width:180px;outline:none;box-shadow:none;"
                                        placeholder="Type number and press Enter or comma">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <small class="text-muted">Up to 10 recipients.</small>
                                <button type="button" id="smsPhoneClear" class="btn btn-link btn-sm p-0"
                                    aria-label="Clear recipients">Clear</button>
                            </div>
                            <div id="smsPhoneError" class="rc-sms-error-text">Invalid phone number.</div>
                        </div>
                        <div class="col-12 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="smsCreateGroupText">
                                <label class="form-check-label" for="smsCreateGroupText">Create group text</label>
                            </div>
                        </div>
                        <div class="col-12 mb-3 d-none" id="smsGroupNameWrap">
                            <label class="form-label">Group name (Optional)</label>
                            <input type="text" class="form-control" id="smsGroupName" maxlength="120"
                                placeholder="Optional">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" id="smsMessage" rows="3" required></textarea>
                            <div id="smsMessageError" class="rc-sms-error-text">Message is required.</div>
                            <div id="smsForwardAttachmentNotice" class="mt-2 text-muted small" style="display:none;"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="smsContinueBtn"
                    onclick="document.getElementById('smsForm').dispatchEvent(new Event('submit', {cancelable:true}));">Continue</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rcCreateTemplateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create SMS Template</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="rcCreateTemplateForm">
                    <div class="mb-2">
                        <label class="form-label">Template Name</label>
                        <input type="text" class="form-control" id="rcTemplateName" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Template Text</label>
                        <textarea class="form-control" id="rcTemplateDescription" rows="3" required></textarea>
                    </div>
                </form>
                <div id="rcCreateTemplateError" class="text-danger small" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="rcSaveTemplateBtn">Save Template</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rcTemplateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Templates</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-bold">Choose a template</div>
                    <button type="button" class="btn btn-sm btn-outline-secondary btn-primary" id="rcCreateTemplateBtn">New
                        Template</button>
                </div>
                <div id="rcTemplateList" class="rc-template-list"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="alertModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Notification</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p id="alertModalMessage"></p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>

        </div>
    </div>
</div>

<script src="/js/ringcentral-datetime.js"></script>
<script src="/js/ringcentral-messages.js"></script>
<script>
    // Show/hide clear button and clear input
    const tabMessagesSearch = document.getElementById('tabMessagesSearch');
    const tabMessagesSearchClear = document.getElementById('tabMessagesSearchClear');
    if (tabMessagesSearch && tabMessagesSearchClear) {
        function toggleClearBtn() {
            tabMessagesSearchClear.style.display = tabMessagesSearch.value ? 'block' : 'none';
        }
        tabMessagesSearch.addEventListener('input', toggleClearBtn);
        tabMessagesSearchClear.addEventListener('click', function () {
            tabMessagesSearch.value = '';
            tabMessagesSearch.dispatchEvent(new Event('input'));
            tabMessagesSearch.focus();
            toggleClearBtn();
        });
        toggleClearBtn();
    }
</script>
