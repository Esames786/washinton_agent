<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('frontend/style.css') }}?id=6">

<style>
/* ── Reset ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Arial', sans-serif;
    background: transparent;
    height: 100vh;
    overflow: hidden;
}

/* ── Main chat wrapper ── */
.chat-widget {
    display: flex;
    flex-direction: column;
    height: 100vh;
    background: #0a1520;
    color: #fff;
}

/* ── Header ── */
.chat-header {
    background: linear-gradient(135deg, #0d1f2d 0%, #071520 100%);
    border-bottom: 2px solid #C9A84C;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}
.chat-header img {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    border: 2px solid #C9A84C;
    object-fit: cover;
    flex-shrink: 0;
}
.chat-header-info .name {
    font-size: 14px;
    font-weight: 700;
    color: #FFD700;
    letter-spacing: 0.5px;
    font-family: 'Arial Black', sans-serif;
    text-transform: uppercase;
}
.chat-header-info .status {
    font-size: 11px;
    color: #4ade80;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 2px;
}
.chat-header-info .status::before {
    content: '';
    width: 7px;
    height: 7px;
    background: #4ade80;
    border-radius: 50%;
    display: inline-block;
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}

/* ── History sidebar ── */
.chat-history-panel {
    background: #071015;
    border-right: 1px solid rgba(201,168,76,0.2);
    padding: 10px;
    overflow-y: auto;
    min-width: 140px;
    max-width: 140px;
}
.chat-history-panel strong {
    font-size: 11px;
    color: #C9A84C;
    text-transform: uppercase;
    letter-spacing: 1px;
    display: block;
    margin-bottom: 8px;
    padding-bottom: 6px;
    border-bottom: 1px solid rgba(201,168,76,0.2);
}
.chat-history-panel ul { list-style: none; padding: 0; }
.chat-history-panel ul li { margin-bottom: 4px; }
.chat-history-panel ul li button {
    width: 100%;
    background: rgba(201,168,76,0.08);
    border: 1px solid rgba(201,168,76,0.15);
    color: rgba(255,255,255,0.7);
    font-size: 10px;
    padding: 5px 7px;
    border-radius: 6px;
    text-align: left;
    cursor: pointer;
    transition: all .15s;
}
.chat-history-panel ul li button:hover,
.chat-history-panel ul li button.active {
    background: rgba(201,168,76,0.2);
    border-color: #C9A84C;
    color: #FFD700;
}

/* ── Body row ── */
.chat-body-row {
    display: flex;
    flex: 1;
    overflow: hidden;
}

/* ── Messages area ── */
.messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    background: #0a1520;
}
.messages::-webkit-scrollbar { width: 4px; }
.messages::-webkit-scrollbar-track { background: transparent; }
.messages::-webkit-scrollbar-thumb { background: rgba(201,168,76,0.3); border-radius: 2px; }

/* ── Message bubbles ── */
.message {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    max-width: 85%;
}
.message img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1.5px solid #C9A84C;
    flex-shrink: 0;
    object-fit: cover;
}
.message .bubble {
    padding: 10px 14px;
    border-radius: 16px;
    font-size: 13px;
    line-height: 1.5;
    max-width: 100%;
    word-break: break-word;
}
/* Left = agent/support */
.message.left {
    align-self: flex-start;
}
.message.left .bubble {
    background: rgba(201,168,76,0.12);
    border: 1px solid rgba(201,168,76,0.2);
    color: rgba(255,255,255,0.9);
    border-bottom-left-radius: 4px;
}
/* Right = customer */
.message.right {
    align-self: flex-end;
    flex-direction: row-reverse;
}
.message.right .bubble {
    background: linear-gradient(135deg, #C9A84C, #8B6914);
    color: #000;
    font-weight: 600;
    border-bottom-right-radius: 4px;
}
.message .agent-name {
    font-size: 10px;
    color: #C9A84C;
    margin-bottom: 3px;
    font-weight: 600;
}
.info-data {
    font-size: 10px;
    color: rgba(255,255,255,0.35);
    display: block;
    margin-top: 3px;
    text-align: right;
}

/* ── User details form ── */
.user-details-form {
    background: rgba(201,168,76,0.06);
    border: 1px solid rgba(201,168,76,0.2);
    border-radius: 14px;
    padding: 20px;
    margin: 8px 0;
}
.user-details-form h5 {
    color: #FFD700;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.user-details-form label {
    font-size: 11px;
    color: rgba(255,255,255,0.6);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
    display: block;
}
.user-details-form input {
    background: rgba(255,255,255,0.05);
    border: 1.5px solid rgba(201,168,76,0.2);
    border-radius: 8px;
    padding: 9px 12px;
    font-size: 13px;
    color: #fff;
    width: 100%;
    margin-bottom: 10px;
    transition: border-color .2s;
}
.user-details-form input:focus {
    border-color: #C9A84C;
    outline: none;
    background: rgba(255,255,255,0.07);
}
.user-details-form input::placeholder { color: rgba(255,255,255,0.25); }
.btn-start-chat {
    background: linear-gradient(135deg, #FFD700, #C9A84C);
    border: none;
    color: #000;
    font-weight: 700;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 13px;
    cursor: pointer;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all .2s;
    width: 100%;
    margin-top: 4px;
}
.btn-start-chat:hover {
    background: linear-gradient(135deg, #FFE44D, #D4A830);
    transform: translateY(-1px);
}

/* ── Typing indicator ── */
.typing-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: rgba(255,255,255,0.5);
    padding: 8px 12px;
    background: rgba(201,168,76,0.06);
    border-radius: 10px;
    width: fit-content;
}
.typing-indicator .dots::after {
    content: '...';
    animation: typing-dots 1.2s infinite steps(3, start);
}
@keyframes typing-dots {
    0% { content: ''; }
    33% { content: '.'; }
    66% { content: '..'; }
    100% { content: '...'; }
}

/* ── Footer / input area ── */
.chat-footer {
    background: #071015;
    border-top: 1px solid rgba(201,168,76,0.2);
    padding: 10px 14px;
    flex-shrink: 0;
}
.chat-footer form {
    display: flex;
    align-items: center;
    gap: 8px;
}
.chat-footer input[type="text"] {
    flex: 1;
    background: rgba(255,255,255,0.05);
    border: 1.5px solid rgba(201,168,76,0.2);
    border-radius: 24px;
    padding: 10px 16px;
    font-size: 13px;
    color: #fff;
    transition: border-color .2s;
}
.chat-footer input[type="text"]:focus {
    border-color: #C9A84C;
    outline: none;
    background: rgba(255,255,255,0.07);
}
.chat-footer input[type="text"]::placeholder { color: rgba(255,255,255,0.3); }
.chat-footer .emoji-btn {
    font-size: 22px;
    cursor: pointer;
    text-decoration: none;
    flex-shrink: 0;
    line-height: 1;
}
.chat-footer .send-btn {
    background: linear-gradient(135deg, #FFD700, #C9A84C);
    border: none;
    color: #000;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: all .2s;
    font-size: 14px;
}
.chat-footer .send-btn:hover {
    background: linear-gradient(135deg, #FFE44D, #D4A830);
    transform: scale(1.05);
}
.chat-footer .send-btn:disabled {
    background: rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.3);
    cursor: not-allowed;
    transform: none;
}

/* ── Emoji popup ── */
#emoji-popup {
    position: absolute;
    bottom: 60px;
    left: 14px;
    right: 14px;
    background: #0d1f2d;
    border: 1px solid rgba(201,168,76,0.3);
    border-radius: 12px;
    padding: 10px;
    display: none;
    flex-wrap: wrap;
    gap: 4px;
    z-index: 100;
    max-height: 160px;
    overflow-y: auto;
}
#emoji-popup .emoji {
    font-size: 20px;
    cursor: pointer;
    padding: 3px;
    border-radius: 4px;
    transition: background .1s;
}
#emoji-popup .emoji:hover { background: rgba(201,168,76,0.15); }

/* ── Search input ── */
#keyword {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(201,168,76,0.2);
    border-radius: 8px;
    padding: 6px 10px;
    font-size: 12px;
    color: #fff;
    width: 100%;
    margin-bottom: 8px;
}
#keyword::placeholder { color: rgba(255,255,255,0.3); }
</style>

<audio id="audio_success" autostart="false">
    <source src="{{ asset('success_sound.mp3') }}" type="audio/mpeg">
</audio>

<div class="chat-widget">

    {{-- HEADER --}}
    <div class="chat-header">
        <img src="{{ asset('frontend/img/logo/hello_transport.svg') }}" alt="Logo">
        <div class="chat-header-info">
            <div class="name">Hello Transport Support</div>
            <div class="status">Online</div>
        </div>
    </div>

    {{-- BODY ROW --}}
    <div class="chat-body-row" style="flex:1;overflow:hidden;">

        {{-- HISTORY SIDEBAR (admin only) --}}
        <div class="chat-history-panel" style="display: {{ ($user_id != 0) ? 'flex' : 'none' }}; flex-direction:column;" id="show_history">
            <strong>History</strong>
            @if($user_id != 0)
                <input type="text" onkeyup="handleSearch(this)" name="keyword" id="keyword" placeholder="🔍 Search...">
            @endif
            <ul></ul>
        </div>

        {{-- MAIN CHAT COLUMN --}}
        <div style="flex:1;display:flex;flex-direction:column;overflow:hidden;" id="chat_box">
            <input type="hidden" id="thread_id_send">

            {{-- Messages --}}
            @if(!$admin)
                <div class="messages">
                    <div class="message left">
                        <img src="{{ asset('frontend/img/logo/hello_transport.svg') }}" alt="Support">
                        <div>
                            <div class="bubble">
                                Thank you for contacting Hello Transport! We have agents standing by to assist you at <strong style="color:#FFD700;">1 (844) 474-4721</strong> or here on Live Chat. How can we help you today?
                            </div>
                        </div>
                    </div>
                    <div id="user-details-form" class="user-details-form">
                        <h5><i class="fas fa-user-circle me-2"></i> Enter Your Details</h5>
                        <form id="details-form">
                            <div class="row">
                                <div class="col-6">
                                    <label>Name</label>
                                    <input type="text" id="user_name" placeholder="Your name" required>
                                </div>
                                <div class="col-6">
                                    <label>Email</label>
                                    <input type="email" id="user_email" placeholder="your@email.com" required>
                                </div>
                            </div>
                            <button type="submit" class="btn-start-chat">
                                <i class="fas fa-comments me-2"></i> Start Chat
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="messages">
                    <div class="message left">
                        <img src="{{ asset('frontend/img/logo/hello_transport.svg') }}" alt="Support">
                        <div>
                            <div class="bubble">
                                Thank you for contacting Hello Transport! We have agents standing by to assist you at <strong style="color:#FFD700;">1 (844) 474-4721</strong> or here on Live Chat. How can we help you today?
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Typing indicator --}}
            <div id="typingIndicator" class="typing-indicator" style="display:none;margin:0 16px 8px;">
                <i class="fas fa-circle-notch fa-spin" style="color:#C9A84C;font-size:12px;"></i>
                <span>Sending<span class="dots"></span></span>
            </div>

            {{-- Footer --}}
            <div class="chat-footer">
                <div id="emoji-popup"></div>
                <form style="display:none;" id="form_submit_chat">
                    <input type="text" id="message" name="message" placeholder="Enter message..." autocomplete="off">
                    <a id="emoji-btn" href="#" class="emoji-btn">😊</a>
                    <button type="submit" class="send-btn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let deviceId = localStorage.getItem('device_id_chat');
    if (!deviceId) {
        const urlParams = new URLSearchParams(window.location.search);
        deviceId = urlParams.get('device_id');
        if (!deviceId) {
            deviceId = self.crypto?.randomUUID?.() || Math.random().toString(36).substring(2);
        }
        localStorage.setItem('device_id_chat', deviceId);
    }
    window.deviceIdChat = deviceId;
</script>

<script>
    $("body").delegate("#show_history ul li button", "click", function () {
        $("#show_history ul li button").removeClass("active");
        $(this).addClass("active");
    });

    $(document).ready(function () {
        const emojis = ['😀','😃','😄','😁','😆','😅','😂','🤣','😊','😇','🙂','🙃','😉','😌','😍','🥰','😘','😗','😙','😚','😋','😛','😜','🤪','😝','🤑','🤗','🤭','🤫','🤔','🤐','😷','🤒','🤕','🤢','🤮','😵','🤯','😎','🤓','🧐','😕','😟','🙁','😮','😯','😲','🥺','😭','😱'];
        emojis.forEach(function(emoji) {
            $('#emoji-popup').append(`<span class="emoji">${emoji}</span>`);
        });
        $('#emoji-btn').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#emoji-popup').toggle();
        });
        $(document).on('click', '.emoji', function() {
            $('#message').val($('#message').val() + $(this).text());
            $('#emoji-popup').hide();
            $('#message').focus();
        });
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#emoji-btn, #emoji-popup').length) {
                $('#emoji-popup').hide();
            }
        });
    });

    var name = '';
    var email = '';
    $('#details-form').on('submit', function(e) {
        e.preventDefault();
        name = $('#user_name').val().trim();
        email = $('#user_email').val().trim();
        if (name && email) {
            $('#user-details-form').hide();
            $('#form_submit_chat').show();
            var initial_data = `Name: ${name} <br> Email Address: ${email}`;
            $("form #message").val(initial_data);
            $('#form_submit_chat').submit();
        } else {
            alert('Please fill in both fields.');
        }
    });
</script>

<script>
    let searchTimeout;
    function handleSearch(inputElement) {
        clearTimeout(searchTimeout);
        const keyword = inputElement.value;
        searchTimeout = setTimeout(() => {
            get_history('{{ date('Y-m-d') }}', '{{ $deviceId }}', null, $('#thread_id_send').val(), keyword);
        }, 100);
    }
</script>

<script>
    var date_created = "{{ date('Y-m-d') }}";
    var ip_address = "{{ $deviceId }}";
    var user_id = {{ $user_id }};
    var user_name = "{{ $user_name }}";
    var c_thread_id = null;
    var admin = {{ ($admin) ? '1' : '0' }};

    @if($admin)
        $('#keyword').show();
    @else
        get_history(date_created, ip_address);
    @endif
    show_his();

    var ring = 0;
    var set_interval2;

    function show_his() {
        $.ajax({
            url: "{{ url('/chat/show-history') }}",
            method: 'GET',
            data: { date_created }
        }).done(function(res) {
            if (res) {
                var lis = "";
                var total_unread = 0;
                var total_unread_c = 0;
                var seperate_count = 0;
                ring = 0;

                $.each(res, function(index, value) {
                    var text = (value.name && value.name.trim() !== '')
                        ? (value.name.length > 18 ? value.name.substring(0, 14) + '...' : value.name)
                        : `Thread: ${value.thread_id}`;
                    lis += `<li>
                        <button type="button" id="li_${value.thread_id}"
                            onclick="get_history('${value.date_created}','${value.ip_address}','admin','${value.thread_id}',null,1)">
                            ${text}
                            ${(value.replied == 0 && value.tc > 0) ? `<span class="badge badge-danger badge-sm">(${value.tc})</span>` : ''}
                        </button>
                    </li>`;
                    total_unread += parseInt(value.tc);
                    total_unread_c += parseInt(value.tc_c);
                    if (value.replied == 0 && admin == 1 && !$('#chat_box').is(':visible') && value.tc > 0) ring = 1;
                    if (ip_address == value.ip_address) {
                        c_thread_id = value.thread_id;
                        seperate_count += parseInt(value.tc_c);
                    }
                    if (value.replied == 1 && admin == 0 && !$('#chat_box').is(':visible') && seperate_count > 0 && value.thread_id == c_thread_id) ring = 1;
                });

                if (ring == 1) playNotificationSound();

                window.parent.postMessage({ type: 'iframeMessage', payload: total_unread }, '*');
                window.parent.postMessage({ type: 'iframeMessage2', payload: seperate_count }, '*');

                if (set_interval2) clearInterval(set_interval2);
                set_interval2 = setInterval(show_his, 10000);
                $('#show_history ul').html(lis);
            }
        });
    }

    function playNotificationSound() {
        document.getElementById("audio_success").play();
    }

    var set_interval;
    var message_scroll = 0;

    function get_history(date_created=null, ip_address=null, admin=null, thread_id=null, keyword=null, launch=0) {
        c_thread_id = thread_id;
        $("#show_history ul li button").removeClass("active");
        $(`#li_${thread_id}`).addClass("active");
        if (set_interval) clearInterval(set_interval);

        var typeValue = (admin == 'admin') ? 1 : 0;
        if ($('#chat_box').is(':visible')) {
            $.ajax({ url: "{{ url('/chat/update-read') }}", method: 'GET', data: { thread_id: c_thread_id, type: typeValue } });
        }

        set_interval = setInterval(function() {
            get_history(date_created, ip_address, admin, thread_id);
        }, 5000);

        $.ajax({
            url: "{{ url('/chat/history') }}",
            method: 'GET',
            data: { date_created, ip_address, thread_id: c_thread_id, keyword }
        }).done(function(res) {
            if (res.status == 1) {
                $('#user-details-form').hide();
                $('#form_submit_chat').show();
                message_scroll = $('.messages')[0].scrollTop;
                $('.messages').remove();
                $('#chat_box').prepend(`
                    <div class="messages">
                        <div class="message left">
                            <img src="{{ asset('frontend/img/logo/hello_transport.svg') }}" alt="Support">
                            <div><div class="bubble">Thank you for contacting Hello Transport! We have agents standing by at <strong style="color:#FFD700;">1 (844) 474-4721</strong>. How can we help you today?</div></div>
                        </div>
                    </div>
                `);
                if (res.data.length > 0) {
                    $.each(res.data, function(index, value) {
                        make_response(value, value.receive_message ? 1 : 0);
                    });
                    if (message_scroll > 0) $('.messages')[0].scrollTop = message_scroll;
                }
            }
        });

        if (launch) {
            setTimeout(() => { $('.messages')[0].scrollTop = $('.messages')[0].scrollHeight; }, 500);
        }
    }

    function make_response(send_value, res) {
        $("#typingIndicator").hide();
        $('#thread_id_send').val(send_value.thread_id);
        var info = '';
        if (admin == 1 && send_value.info_data) {
            info = `<span class="info-data">${send_value.info_data}</span>`;
        }
        var messageHTML = '';
        if (res == 0) {
            messageHTML = `<div class="message right">
                <img src="{{ asset('frontend/img/logo/hello_transport.svg') }}" alt="You">
                <div><div class="bubble">${send_value.send_message}</div>${info}</div>
            </div>`;
        } else {
            var agent_name = send_value.admin_name || user_name;
            messageHTML = `<div class="message left">
                <img src="{{ asset('frontend/img/logo/hello_transport.svg') }}" alt="Support">
                <div>
                    <div class="agent-name">${agent_name}</div>
                    <div class="bubble">${send_value.receive_message}</div>
                    ${info}
                </div>
            </div>`;
        }
        $(".messages").append(messageHTML);
    }

    $("#form_submit_chat").submit(function(event) {
        event.preventDefault();
        var message = $("#message").val().trim();
        var urlRegex = /(https?:\/\/[^\s]+)/g;
        if (urlRegex.test(message)) { alert("Links are not allowed!"); return false; }
        if (!message) return;

        $("#form_submit_chat #message").prop('disabled', true);
        $("#form_submit_chat button").prop('disabled', true);
        $("#typingIndicator").show();

        $.ajax({
            url: "{{ url('/chat/send') }}",
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            data: {
                model: "gpt-3.5-turbo",
                content: $("#form_submit_chat #message").val(),
                personality: $("#form_submit_chat #responseType").val(),
                ip_address: ip_address,
                user_id: user_id,
                user_name: user_name,
                thread_id: c_thread_id,
                name: name,
                email: email,
                device_id: deviceId,
                reference_domain: "{{ $domain }}",
                admin: '{{ ($admin) ? '1' : 0 }}',
            }
        }).done(function(res) {
            @if($admin)
                get_history(res.data.date_created, res.data.ip_address, 'admin', res.data.thread_id);
            @else
                get_history(res.data.date_created, res.data.ip_address, null, res.data.thread_id);
            @endif
            $("#form_submit_chat #message").val('');
            setTimeout(() => { $('.messages')[0].scrollTop = $('.messages')[0].scrollHeight; }, 500);
            $("#form_submit_chat #message").prop('disabled', false);
            $("#form_submit_chat button").prop('disabled', false);
        });
    });
</script>
