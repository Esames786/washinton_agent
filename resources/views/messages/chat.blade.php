<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/img/logo/logofavicon.png') }}">
    <title>Chat</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css'>
    <link rel='stylesheet prefetch'
        href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.2/css/font-awesome.min.css'>
    <link rel="stylesheet" href="{{ asset('backend/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/responsive.css') }}">
</head>
<style>
    .profile {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 20px;
    }

    .profile-header {
        display: flex;
        align-items: center;
    }

    .profile-header img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin-right: 20px;
    }

    .profile-header h2 {
        margin: 0;
    }

    .profile-details {
        margin-top: 20px;
    }

    .detail {
        margin-bottom: 10px;
    }

    .detail label {
        font-weight: bold;
    }

    .detail span {
        margin-left: 10px;
    }

    .profile-sec-none {
        display: none;
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    .profile-visible {
        display: block;
        opacity: 1;
    }
</style>

<body>
    @php
        $currentUser = \Illuminate\Support\Facades\Auth::guard('Authorized')->user();
        $verification = $currentUser->admin_verify && $currentUser->is_email_verified && $currentUser->Profile_Update;
    @endphp
    @php
        $user_id = auth()->guard('Authorized')->user()->Company_Name;
    @endphp
    <div class="container-fluid "
        style="width: 100%; height: 100vh ; padding: 45px 0 ;    background-image: url({{ @asset('../../frontend/img/slider-3.webp') }}) ;  background-size: cover; background-position: center;   background-repeat: no-repeat;">
        <div class="chat-content-area  d-flex justify-center;  "
            style="padding: 0 0 30px 10px; width: 90%; margin: auto; padding: 0 auto ">
            {{-- <div class="container">
                <div class="d-flex justify-content-end mt-2">
                    <a href="{{ route('User.Dashboard') }}" class="btn btn-primary">Home</a>
                </div>
            </div> --}}
            {{-- <div style="display: flex;  width: 100%; "> --}}

            <div class="sidebar-left">
                <div class="sidebar"
                    style="height: 100% ; background-color:rgb(0,0,0 , 0.7);border-radius: 20px 0 0 20px;">
                    <div class="chat-sidebar-header  d-flex align-items-center">
                        <div class="avatar mr-3">
                            <img src="{{ asset($currentUser->profile_photo_path) ?? asset('backend/img/user1.jpg') }}" width="50"
                                height="50" class="rounded-circle" alt="image">
                        </div>
                        <div class="form-group  position-relative mb-0">
                            <label><i class="bx bx-search"></i></label>
                            <input type="text" style="background-color:rgb(0,0,0 , 0.7) ; color: #ddd"
                                id="searchInput" class="form-control" placeholder="Search here..."
                                oninput="filterUsers()">
                        </div>
                    </div>
                    <div class="sidebar-content d-flex chat-sidebar" data-simplebar="init">
                        <div class="simplebar-wrapper" style="margin: 0px;">
                            <div class="simplebar-height-auto-observer-wrapper">
                                <div class="simplebar-height-auto-observer"></div>
                            </div>
                            <div class="simplebar-mask">
                                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                    <div class="simplebar-content-wrapper"
                                        style="height: 500px; overflow: hidden scroll;">
                                        <div class="simplebar-content" style="padding: 0px;">
                                            <div class="chat-menu" id="contacts">
                                                <label
                                                    class="d-block list-group-label mt-0"style="color: #ddd">Chats</label>
                                                <ul class="list-group list-group-user list-unstyled mb-0 "
                                                    id="user-list">

                                                    <!-- Admin Chat (Pinned at the top) -->
                                                    <li data-user-id="0100" data-name="Admin Support"
                                                        class="pinned-chat">
                                                        <div class="d-flex align-items-center p-2"
                                                            style="background-color: #007bff ; border-radius: 10px; gap: 5px;">
                                                            <div class="avatar"
                                                                style="width: 45px; height: 45px; overflow: hidden; border-radius: 100%; position: relative; display: flex; justify-content: center; align-items: center;">
                                                                <img src="{{ asset('frontend/img/logo/hello_transport.svg') }}"
                                                                    class="" alt="Admin"
                                                                    style="width: 45px; height: 45px; border-radius: 100%; object-fit: cover;">
                                                                <span class="status-online"></span>
                                                            </div>
                                                            <div class="user-name">
                                                                <h6
                                                                    style="color: #fff; font-size: 12px; font-weight: bold;">
                                                                    Admin Support</h6>
                                                                <small style="color: #ddd;">Always available to
                                                                    help</small>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    @foreach ($users as $u)
                                                        <li data-user-id="{{ $u->id }}"
                                                            data-name="{{ $u->Company_Name }}">
                                                            <div class="d-flex align-items-center  p-2 "
                                                                style=" background-color:rgb(0,0,0 , 0.7) ; border-radius: 10px; gap: 5px; ">
                                                                <div class="avatar "
                                                                    style="width: 45px; height: 45px; overflow: hidden; border-radius: 100%; position: relative;
                                                                    display: flex; justify-content: center; align-items: center;">
                                                                    <img src="{{ $u->profile_photo_path ? url($u->profile_photo_path) : asset('backend/img/user1.jpg') }}"
                                                                        class="" alt="image"
                                                                        style="width: 45px; height: 45px; border-radius: 100%; object-fit: cover; ">
                                                                    {{-- <span class="status-busy"></span> --}}
                                                                </div>
                                                                <div class="user-name">
                                                                    @if ($u->Company_Name)
                                                                        <h6 class=" "
                                                                            style="color: #ddd ; font-size: 12px;    ">
                                                                            {{ $u->Company_Name }}</h6>
                                                                    @else
                                                                        <span
                                                                            class="d-block">{{ $u->email }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="simplebar-placeholder" style="width: 250px; height: 721px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-right" style="width: ; border-radius: ; height: ; ">
                <div class="chat-area">
                    <div class="chat-list-wrapper">
                        <div class="chat-list"
                            style="border-radius:  0 20px 20px  0 ; background-color:rgb(0,0,0 , 0.7) ; ">
                            <div class="chat-list-header d-flex align-items-center ">
                                <div class="header-left d-flex align-items-center mr-3">
                                    {{-- <div
                                        class="avatar mr-3"style="width: 50px; height: 50px; overflow: hidden; border-radius: 50%; position: relative;">
                                        <img src="" id="user-image" class="rounded-circle" alt="image"
                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%">
                                        <span class="status-online"></span>
                                    </div> --}}
                                    <h6 class="mb-0 font-weight-bold" style="color: #ddd" id="user-name"></h6>
                                </div>
                                <div class="header-right text-right w-100">
                                    <ul class="list-unstyled ">
                                        {{-- <li>
                                            <span class="favorite d-block active">
                                                <i class="bx bx-star"></i>
                                            </span>
                                        </li> --}}
                                        <li class="">
                                            <button
                                                style="border-radius: 7px; padding: 5px 10px ; background-color:rgba(61, 61, 61, 0.7) ; color: #ddd; border: none">
                                                <a href="{{ url()->previous() }}"
                                                    style="text-decoration: none; color: #ddd;">
                                                    Back
                                                </a>
                                            </button>

                                            <button
                                                style="border-radius: 7px; padding: 5px 10px ; background-color:rgba(61, 61, 61, 0.7) ; color: #ddd; border: none">
                                                <a href="{{ url('/Authorized') }}"
                                                    style="text-decoration: none; color: #ddd;">
                                                    Home
                                                </a>
                                            </button>
                                        </li>
                                        {{-- <li>
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle"  type="button"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="true">
                                                        <i class="bx bx-dots-vertical-rounded text-white"></i>
                                                    </button>
                                                    <div class="dropdown-menu"
                                                        style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-93px, 22px, 0px);"
                                                        x-placement="bottom-start">
                                                        <a class="dropdown-item d-flex align-items-center"
                                                            href="#">
                                                            <i class="bx bx-pin"></i> Pin to Top
                                                        </a>
                                                        <a class="dropdown-item d-flex align-items-center"
                                                            href="#">
                                                            <i class="bx bx-trash"></i> Delete Chat
                                                        </a>
                                                        <a class="dropdown-item d-flex align-items-center"
                                                            href="#">
                                                            <i class="bx bx-block"></i> Block
                                                        </a>
                                                    </div>
                                                </div>
                                            </li> --}}
                                    </ul>
                                </div>
                            </div>
                            <div class="chat-container" style="height: ;" id="messages-container"
                                data-simplebar="init">
                                <div class="simplebar-wrapper " style="">
                                    <div class="simplebar-height-auto-observer-wrapper">
                                        <div class="simplebar-height-auto-observer"></div>
                                    </div>
                                    <div class="simplebar-mask">
                                        <div class="simplebar-offset">
                                            <div class="simplebar-content-wrapper"
                                                style="background-color:rgb(0,0,0 , 0.7) ;">
                                                <div class="simplebar-content">
                                                    <div class="chat-content" id="messages">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                    <div class="simplebar-scrollbar"
                                        style="transform: translate3d(0px, 0px, 0px); display: none;">
                                    </div>
                                </div> --}}
                                {{-- <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                                    <div class="simplebar-scrollbar"
                                        style="height: 25px; transform: translate3d(0px, 56px, 0px); display: none;">
                                    </div>
                                </div> --}}
                            </div>
                            <div class="chat-list-footer"
                                style="border-radius: 0 0 20px 0;  background-color:rgb(0,0,0 , 0.7) ; color: #ddd">
                                <div class="d-flex align-items-center">
                                    <input type="text" class="form-control" placeholder="Type your message..."
                                        id="content-val" onkeypress="handleKeyPress(event)">
                                    <input type="hidden" id="recipient_id">
                                    <button type="submit" class="send-btn d-inline-block btn-primary"
                                        onclick="sendMessage()">Send <i class="bx bx-paper-plane  "></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- </div> --}}

            {{-- <div style="background-color:rgb(0,0,0 , 0.7) ; width: 20%; margin-left: 10px; border: 2px solid white; border-radius: 20px;">
                <div class=" " style="width: 85%; margin: 20px auto;">
                    <div style="display: flex ; align-items: center;gap: 10px; " >
                        <div style="width: 45px; height:45px; border-radius: 50%; background-color: greenyellow; display: flex; justify-content: center; align-items: center;">mk</div>
                        <div style="color: #ddd"></div>
                    </div>
                        <div style="text-align: center; padding-top: 20px;">
                            <button style="padding: 2px 7px; border:none;  border-radius: 20px; margin: auto;">view Profile</button>
                        </div>
                        <br>

                        <hr>
                        <div style="">
                            <div style=" padding-top: 20px;">
                                <button style="width: 100%; padding: 2px 7px; border:none;  border-radius: 20px; margin: auto;">view Profile</button>
                            </div>
                            <div style=" padding-top: 20px;">
                                <button style="width: 100%; padding: 2px 7px; border:none;  border-radius: 20px; margin: auto;">view Profile</button>
                            </div>
                            <div style=" padding-top: 20px;">
                                <button style="width: 100%; padding: 2px 7px; border:none;  border-radius: 20px; margin: auto;">view Profile</button>
                            </div>
                            <div style=" padding-top: 20px;">
                                <button style="width: 100%; padding: 2px 7px; border:none;  border-radius: 20px; margin: auto;">view Profile</button>
                            </div>

                        </div>
                </div>
                </div> --}}
        </div>
    </div>


    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
    {{-- <audio id="notificationSound">
        <source src="{{ asset('chat/tone/tone.mp3') }}" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio> --}}
    {{-- <script>
        console.log("console check");

        function playNotificationSound() {
            var notificationSound = document.getElementById('notificationSound');
            if (notificationSound.readyState >= 2) {
                notificationSound.play().catch(error => {
                    console.error('Failed to play sound:', error);
                });
            } else {
                notificationSound.addEventListener('loadedmetadata', function() {
                    notificationSound.play().catch(error => {
                        console.error('Failed to play sound:', error);
                    });
                });
            }
        }

        var lastMessageId = null;
        var messagesLoading = false;
        var displayedMessages = [];

        function clearMessages() {
            $('#messages').empty();
            lastMessageId = null;
            displayedMessages = [];
        }

        function filterUsers() {
            var input, filter, ul, li, a, i, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            ul = document.getElementById('user-list');
            li = ul.getElementsByTagName('li');

            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByClassName('name')[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = '';
                } else {
                    li[i].style.display = 'none';
                }
            }
        }

        function scrollToLastMessage() {
            setTimeout(function() {
                var lastMessage = document.querySelector("#messages .chat:last-child");
                if (lastMessage) {
                    lastMessage.scrollIntoView({
                        behavior: "smooth",
                        block: "end"
                    });
                }
            }, 100); // Thoda delay taake scroll proper ho
        }

        function loadMessages(userId, userName, userImage) {
            userId = userId || $('#recipient_id').val();
            var url = '/chat/messages/' + userId;
            $.get(url, function(data) {
                $('#messages').empty();
                data.forEach(function(message) {
                    var isSender = message.user_id == {{ auth()->guard('Authorized')->user()->id }};
                    var chatClass = isSender ? "chat" : "chat chat-left";
                    var timeString = new Date(message.created_at).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });

                    var chatHtml = `
            <div class="${chatClass}" id="message-${message.id}">
                <div class="chat-body">
                    <div class="chat-message">
                        <p>${message.content}<br>
                        <span style="font-size: 11px; font-weight: 800; color: #bbbbbb; border-top: #bfbfc3 1px solid;">${timeString}</span></p>
                    </div>
                </div>
            </div>
        `;
                    $('#messages').append(chatHtml);
                });

                scrollToLastMessage(); // **Last message tak scroll karega**
            });

            $('#recipient_id').val(userId);
            if (userId) {
                updateUserProfile(userName, userImage);
            }
        }


        var initialUserId = '{{ $userId ?? null }}';
        var initialUserName = '{{ $userName ?? null }}';
        var initialUserImage = '{{ $userImage ?? null }}';
        loadMessages(initialUserId, initialUserName, initialUserImage);
        setInterval(function() {
            loadMessages();
        }, 2000);

        function updateUserProfile(userName, userImage) {
            $('#user-name').text(userName);
            $('#user-image').attr('src', userImage);
        }

        function sendMessage() {
            var content = $('#content-val').val();
            var recipientId = $('#recipient_id').val();
            $('#content-val').val('');
            var data = {
                content: content,
                recipient_id: recipientId,
                _token: $('meta[name="csrf-token"]').attr('content')
            };
            $.post('/chat/send-message', data)
                .done(function(data) {
                    var chatHtml = `
            <div class="chat" id="message-${data.message.id}">
                <div class="chat-body">
                    <div class="chat-message">
                        <p>${data.message.content}</p>
                    </div>
                </div>
            </div>
        `;
                    $('#messages').append(chatHtml);
                    scrollToLastMessage(); // **New message pe last message tak scroll hoga**
                    playNotificationSound()
                });
        }


        setInterval(function() {
            loadMessages();
            scrollToLastMessage();
        }, 2000);



        // function scrollToBottom() {
        //     var scrollContainer = document.querySelector('.simplebar-content-wrapper'); // Scrollable container
        //     if (scrollContainer) {
        //         scrollContainer.scrollTop = scrollContainer.scrollHeight;
        //     }
        // }

        function handleKeyPress(event) {
            if (event.keyCode === 13) {
                sendMessage();
            }
        }


        $(document).ready(function() {
            $('#user-list li').on('click', function() {
                var userId = $(this).data('user-id');
                var userName = $(this).data('name');
                var userImage = $(this).find('img').attr('src');
                loadMessages(userId, userName, userImage);
            });
            scrollToLastMessage(); // **Chat open hone pe bhi last message tak scroll karega**
        });
    </script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/simplebar@latest/dist/simplebar.css" />

    <!-- Include SimpleBar JS -->
    <script src="https://unpkg.com/simplebar@latest/dist/simplebar.min.js"></script>
    <audio id="notificationSound">
        <source src="{{ asset('chat/tone/tone.mp3') }}" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
    <script>


        document.addEventListener("DOMContentLoaded", function() {
            new SimpleBar(document.getElementById('messages-container'));
        });

        function playNotificationSound() {
            var notificationSound = document.getElementById('notificationSound');
            if (notificationSound.readyState >= 2) {
                notificationSound.play().catch(error => {
                    console.error('Failed to play sound:', error);
                });
            } else {
                notificationSound.addEventListener('loadedmetadata', function() {
                    notificationSound.play().catch(error => {
                        console.error('Failed to play sound:', error);
                    });
                });
            }
        }
        var lastMessageId = null;
        var messagesLoading = false;
        var displayedMessages = [];

        function clearMessages() {
            $('#messages').empty();
            lastMessageId = null;
            displayedMessages = [];
        }

        function filterUsers() {
            console.log("function run")
            var input, filter, ul, li, h6, i, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            ul = document.getElementById('user-list');
            li = ul.getElementsByTagName('li');

            for (i = 0; i < li.length; i++) {
                h6 = li[i].getElementsByTagName('h6')[0]; // Targeting h6 inside each li
                if (h6) {
                    txtValue = h6.textContent || h6.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        li[i].style.display = '';
                    } else {
                        li[i].style.display = 'none';
                    }
                }
            }
        }

        function isUserAtBottom() {
            var simpleBarInstance = SimpleBar.instances.get(document.getElementById('messages-container'));
            if (simpleBarInstance) {
                var scrollElement = simpleBarInstance.getScrollElement();
                return scrollElement.scrollTop + scrollElement.clientHeight >= scrollElement.scrollHeight -
                    50; // 50px tolerance
            }
            return false;
        }

        function scrollToBottom() {
            setTimeout(function() {
                var simpleBarInstance = SimpleBar.instances.get(document.getElementById('messages-container'));
                if (simpleBarInstance) {
                    var scrollElement = simpleBarInstance.getScrollElement();
                    scrollElement.scrollTop = scrollElement.scrollHeight;
                }
            }, 50); // 100ms delay
        }

        function loadMessages(userId, userName, userImage) {
            if (messagesLoading) {
                return;
            }

            userId = userId || $('#recipient_id').val();
            var url = '/chat/messages/' + userId + (lastMessageId ? '?lastMessageId=' + lastMessageId : '');
            $.get(url, function(data) {
                var hasNewMessages = false; // Track if new messages were received

                data.forEach(function(message) {
                    if (!displayedMessages.includes(message.id)) {
                        hasNewMessages = true; // New message found
                        var isSender = message.user_id == {{ auth()->guard('Authorized')->user()->id }};
                        var chatClass = isSender ? "chat" : "chat chat-left";
                        var profilePhotoPath = isSender ?
                            "{{ asset('Uploads/Profile/' . auth()->guard('Authorized')->user()->id . '/Profile_Image.webp') }}" :
                            (message.user.profile_photo_path || "{{ asset('backend/img/user1.jpg') }}");
                        var timeString = new Date(message.created_at).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: true
                        });
                        var chatHtml = `
                    <div class="${chatClass}">
                        <div class="chat-body">
                            <div class="chat-message">
                                <p>${message.content}<br>
                                <span style="font-size: 11px; font-weight: 800; color: #bbbbbb; border-top: #bfbfc3 1px solid;">${timeString}</span></p>
                            </div>
                        </div>
                    </div>
                `;
                        $('#messages').append(chatHtml);
                        displayedMessages.push(message.id);
                    }
                });

                // Scroll to the bottom if new messages were received
                if (hasNewMessages) {
                    scrollToBottom();
                }
            }).done(function() {
                messagesLoading = false;
            }).fail(function() {
                messagesLoading = false;
            });
            $('#recipient_id').val(userId);
            if (userId) {
                updateUserProfile(userName, userImage);
            }
        }



        var currentUserId = '{{ 100 ? 100 : $userId }}';
        var currentUserName = '{{ $userName ?? null }}';
        var currentUserImage = '{{ $userImage ?? null }}';

        loadMessages(currentUserId, currentUserName, currentUserImage);

        setInterval(function() {
            loadMessages(currentUserId, currentUserName, currentUserImage); // now always uses latest values
        }, 1000);

        function updateUserProfile(userName, userImage) {
            const baseAssetUrl = "{{ asset('') }}";

            $('#user-name').text(userName);

            const imageUrl = userImage ? baseAssetUrl + userImage : baseAssetUrl + 'backend/img/avatar.webp';
            $('#user-image').attr('src', imageUrl);
        }


        $(document).ready(function() {
            new SimpleBar(document.getElementById('messages-container')); // Initialize SimpleBar
            scrollToBottom();
        });

        function sendMessage() {
            var content = $('#content-val').val();
            var recipientId = $('#recipient_id').val();
            $('#content-val').val('');
            var data = {
                content: content,
                recipient_id: recipientId,
                _token: $('meta[name="csrf-token"]').attr('content')
            };
            $.post('/chat/send-message', data)
                .done(function(data) {
                    var senderLabel = (data.user_id == {{ auth()->guard('Authorized')->user()->id }}) ? 'You' : (data
                        .user_name ? data.user_name : 'Unknown User');
                    var receiverLabel = (data.recipient_id == {{ auth()->guard('Authorized')->user()->id }}) ? 'You' :
                        (
                            data
                            .recipient_name ? data.recipient_name : 'Unknown User');
                    if (data.message.content == undefined) {
                        return
                    } else {
                        var messageClass = (data.message.user_id ==
                            {{ auth()->guard('Authorized')->user()->id }}) ? 'replies' : '';
                        $('#messages').append('<li class="replies ' + messageClass +
                            '"><strong> <img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />' +
                            senderLabel + ':</strong> <p>' + data.message.content + '</p></li>');
                    }
                    lastMessageId = data.message.id;
                    $('#messages-container').scrollTop($('#messages-container')[0].scrollHeight);
                })
                .fail(function(xhr, textStatus, errorThrown) {
                    console.error('Error sending message:', errorThrown);
                });
            scrollToBottom();
        }

        function handleKeyPress(event) {
            if (event.keyCode === 13) {
                sendMessage();
            }
        }

    $('#user-list li').on('click', function() {
        var userId = $(this).data('user-id');
        var userName = $(this).data('name');
        var userImage = $(this).find('img').attr('src');

        // ✅ Update global variables
        currentUserId = userId;
        currentUserName = userName;
        currentUserImage = userImage;

        clearMessages();
        loadMessages(userId, userName, userImage);
    });
        scrollToBottom();
        // var scrollContainer = document.getElementById("messages");
        // scrollContainer.scrollTop = scrollContainer.scrollHeight;
    </script>

</body>

</html>
