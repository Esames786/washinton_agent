@extends('layouts.chat')

@section('template_title')
    New
@endsection

@section('content')

    <head>
        <title>Chat</title>
        <link rel="icon" href="https://washington.shawntransport.com/assets/images/brand/ship_logo.png" type="image/png">
    </head>
    <style>
        .message-feed.media .media-body,
        .message-feed.right .media-body {
            overflow: visible;
            display: flow-root;
        }

        span.dot-label {
            position: relative !important;
            display: inline-grid !important;
            place-content: center !important;
        }

        .ps {
            overflow: scroll !important;
            overflow-anchor: none;
            -ms-overflow-style: none;
            touch-action: auto;
            -ms-touch-action: auto;
        }

        .flex_right button {
            position: relative;
        }

        label.flex_chat {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .chat-body-style {
            overflow: scroll;
        }
    </style>
    <div class="page-header">
        <div class="text-gray text-center text-uppercase w-100">
            <h1 class="my-4"><b>User Support Chat</b></h1>
        </div>
    </div><br/>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="tile tile-alt mb-0" id="messages-main" style="width: 100%">
                    <div class="ms-menu">
                        <div class="tab-menu-heading border-top-0">
                            <div class="tabs-menu1">
                                <ul class="nav">
                                    <li><a href="#tab-7" class="active" data-toggle="tab">Chat</a></li>
                                </ul>
                                <input type="hidden" value="Chat" id="chatText">
                            </div>
                        </div>
                        <div class="tab-content" id="getChatList">
                            @include('global_chat.user-chat-list')
                        </div>
                    </div>
                    <div class="ms-body">
                        <div id="chat-box-new">
                            <div class="action-header clearfix">
                                <a href="#" class="ms-menu-trigger">
                                    <i class="fe fe-align-left"></i>
                                </a>
                                <div class="float-left hidden-xs d-flex ml-4 chat-user mt-3">
                                    <div class="align-items-center mt-1">
                                        <p id="touser" class="font-weight-bold mb-0 fs-16"></p>
                                        <span id="loginOrNot">Please Select User
                                    </div>
                                </div>
                            </div>
                            <div class="chat-body-style ChatBody" id="ChatBody">
                                <div class="message-feed media">
                                    <div class="media-body">
                                        <div class="mf-content">
                                            SELECT USER TO START THE CHAT
                                        </div>
                                        <small class="mf-date"><i class="fa fa-clock-o"></i>
                                            {{ \Carbon\Carbon::parse(now())->format('Y, M d h:i A') }}</small>
                                    </div>

                                </div>
                                <div id="her" onload="focus()"></div>
                            </div>
                        </div>

                        <form action="" id="form" method="POST" style="display: none">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="d-flex">
                                <div style="display:grid;">
                                    <img id="image" src="" alt="image" style="display:none;" />
                                    <span id="imgName" class="text-center" style="width: 150px;"></span>
                                </div>
                                <div style="display:grid;">
                                    <img id="attachment" src="{{ asset('images/file.png') }}" alt="image"
                                        style="display:none;" />
                                    <span id="fileName" class="text-center" style="width: 150px;"></span>
                                </div>
                            </div>
                            <div class="msb-reply" style="display: flex;">
                                <textarea id="description" name="description" placeholder="What's on your mind..."
                                    style="margin-right: 20px; padding-right: 30px;"></textarea>
                                <div class="flex_right" style="display: flex; gap: 0.8rem;">
                                    <button type="submit" class="flex_chat"><i class="fa fa-paper-plane-o"></i></button>
                                </div>
                            </div>
                            <input type="hidden" id="to_user_id" name="to_user_id" value="" />
                            <input type="hidden" id="count_read" value="0">
                            <input type="hidden" id="count_interval" value="0">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row-->

    <div class="modal" id="modaldemo4">
        <div class="modal-dialog modal-dialog-centered text-center " role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                    <i class="fe fe-check-circle fs-100 text-success lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-success tx-semibold" id="success"></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modaldemo5">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                    <i class="fe fe-x-circle fs-100 text-danger lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-danger" id="not_success"></h4>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
    <script>
        $('#img').change(function(e) {
            var file = $(this).get(0).files[0];
            var name = e.target.files[0].name;

            if (file) {
                var reader = new FileReader();

                reader.onload = function() {
                    $("#image").attr("src", reader.result).width(150).height(200);
                    $('#image').show();
                    $('#imgName').html(name);
                }

                reader.readAsDataURL(file);
            }
        })

        $('#attach').change(function(e) {
            var file = $(this).get(0).files[0];
            var name = e.target.files[0].name;

            if (file) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#fileName').html(name);
                    $('#attachment').show().width(150).height(200);
                }

                reader.readAsDataURL(file);
            }
        })

        $(document).ready(function() {
            $(".app-sidebar__toggle").trigger('click');
            $('.app-sidebar ').hover(function() {
                $(".app-sidebar__toggle").trigger('click');
            });
        });
        $(document).ready(function() {
            $("#ChatBody").animate({
                scrollTop: 20000000000
            }, "slow");
            $(".updatee").click(function() {
                var order_id = $(this).closest('tr').find('.order_id').val();
                $("#order_id1").attr("value", order_id);
            });
        });

        function fetch_data(to_user_id) {
            $('#form2').hide();
            $('#form').show();
            $("#to_user_id").val(to_user_id);
            $.ajax({
                type: "GET",
                url: "{{ route('user_get_chat') }}",
                data: {
                    touserId: to_user_id
                },
                success: function(data) {
                    $('#chat-box-new').html("");
                    $('#chat-box-new').html(data);
                    $("#ChatBody").animate({
                        scrollTop: 20000000000
                    }, "slow");
                },
                error: function(e) {
                    $("#err").html(e).fadeIn();
                }

            });
        }

        function getChat(to_user_id) {
            $('#form').show();
            $("#to_user_id").val(to_user_id);
            // alert(to_user_id);
            $.ajax({
                type: "GET",
                url: "{{ route('user_get_chat_runtime') }}",
                data: {
                    touserId: to_user_id
                },
                success: function(data) {
                    if (data) {
                        $('#chat-box-new').html("");
                        $('#chat-box-new').html(data);
                        $("#ChatBody").animate({
                            scrollTop: 20000000000
                        }, "slow");
                    }
                },
                error: function(e) {
                    $("#err").html(e).fadeIn();
                }

            });
        }

        function readMsgs(to_user_id, e) {
            $('.list-group-item').removeClass("active");
            $("#to_user_id").val(to_user_id);
            e.classList.add("active");
            fetch_data(to_user_id);
        }

        document.getElementById('description').onkeydown = function(e) {
            if (e.keyCode == 13) {
                $('#form').submit();
                $('#description').val("");
                $('#her').focus();
            }
        };

        $(document).ready(function(e) {
            $("#form").on('submit', (function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('user_save_chat') }}",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function() {
                    },
                    success: function(data) {
                        $('#image').hide();
                        $('#attachment').hide();
                        $('#imgName').html("");
                        $('#fileName').html("");

                        let test = data.toString();
                        let test2 = $.trim(test);
                        let text = "SUCCESS";
                        if (test2 == text) {
                            var to_user = $('#to_user_id').val();
                            fetch_data(to_user);
                            $('#description').val("");
                            $("#ChatBody").animate({
                                scrollTop: 20000000000
                            }, "slow");

                        } else {

                        }
                    },
                    error: function(e) {
                        $("#err").html(e).fadeIn();
                    }
                });
            }));
        });

        function getChatList() {
            var chatText = $("#chatText").val();
            $.ajax({
                url: "{{ route('user-get-chats') }}",
                type: "GET",
                data: {
                    chatText: chatText
                },
                success: function(res) {
                    $("#getChatList").html("");
                    $("#getChatList").html(res);
                }
            });
        }

        setInterval(function() {
            getChatList();
        }, 1000 * 60);

        setInterval(function() {
            // if ($("#chatText").val() == 'Chat') {
                var to_user_id = $("#to_user_id").val();
                // alert(to_user_id);
                // fetch_data(to_user_id);
                getChat(to_user_id);
            // }
        }, 5000);

        $("a[data-toggle='tab']").click(function() {
            $("#chatText").val($(this).text());

            if ($("#chatText").val() == 'Chat') {
                var to_user_id = $("#to_user_id").val();
                fetch_data(to_user_id);
            }
        })
    </script>
    {{-- <script>
        if ("{{ \Request::segment(2) }}" == 'user') {
            fetch_data({{ $chatID }});
            $("a[href='#tab-9']").removeClass('active');
            $("a[href='#tab-7']").addClass('active');
            $("#tab-9").removeClass('active');
            $("#tab-7").addClass('active');
            $("li[onclick='readMsgs({{ $chatID }},this)']").addClass('active');
        }
        if ("{{ \Request::segment(2) }}" == 'group') {
            fetch_data4({{ $chatID }});
            viewGroupChat({{ $chatID }});
            $("a[href='#tab-7']").removeClass('active');
            $("a[href='#tab-9']").addClass('active');
            $("#tab-7").removeClass('active');
            $("#tab-9").addClass('active');
            $("li[onclick='groupChat({{ $chatID }},this)']").addClass('active');
        }
    </script> --}}
@endsection



{{-- @extends('layouts.chat')

@section('template_title')
    New
@endsection

@section('content')

    <head>
        <title>User Support</title>
        <link rel="icon" href="{{ asset('assets/images/brand/ship_logo.png') }}" type="image/png">
    </head>
    <style>
        .message-feed.media .media-body,
        .message-feed.right .media-body {
            overflow: visible;
            display: flow-root;
        }

        span.dot-label {
            position: relative !important;
            display: inline-grid !important;
            place-content: center !important;
        }

        .ps {
            overflow: scroll !important;
            overflow-anchor: none;
            -ms-overflow-style: none;
            touch-action: auto;
            -ms-touch-action: auto;
        }

        .flex_right button {
            position: relative;
        }

        label.flex_chat {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .chat-body-style {
            overflow: scroll;
        }
    </style>
    <div class="page-header">
        <div class="text-gray text-center text-uppercase w-100">
            <h1 class="my-4"><b>User Support</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="tile tile-alt mb-0" id="messages-main" style="width: 100%">
                    <div class="ms-menu">
                        <div class="tab-menu-heading border-top-0">
                            <div class="tabs-menu1">
                                <ul class="nav">
                                    <li><a href="#tab-7" class="active" data-toggle="tab">Chat</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active" style="height: 100%; overflow: scroll;">
                                <ul class="list-group lg-alt chat-conatct-list" id="ChatList">
                                    @foreach ($users as $user)
                                        <li class="list-group-item media p-4 mt-0 border-0" 
                                            data-user-id="{{ $user->id }}" style="cursor: pointer">
                                            <div class="media-body">
                                                <div class="list-group-item-heading text-default font-weight-semibold">
                                                    {{ $user->name }}
                                                </div>
                                                <small class="list-group-item-text text-muted">{{ $user->email }}</small>
                                            </div>
                                            <span class="chat-time {{ $user->is_login == 1 ? 'text-success' : 'text-muted' }}">
                                                {{ $user->is_login == 1 ? 'Online' : optional($user->updated_at)->diffForHumans() }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="ms-body">
                        <div id="chat-box-new">
                            <div class="action-header clearfix">
                                <div class="float-left hidden-xs d-flex ml-4 chat-user mt-3">
                                    <div class="align-items-center mt-1">
                                        <p id="chat-with-user" class="font-weight-bold mb-0 fs-16">Select a user to chat</p>
                                    </div>
                                </div>
                            </div>
                            <div class="chat-body-style ChatBody" id="ChatBody">
                                <div class="message-feed media">
                                    <div class="media-body">
                                        <div class="mf-content">
                                            Please select a user to start chatting
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="chatForm" method="POST" style="display: none;">
                            @csrf
                            <input type="hidden" id="to_user_id" name="recipient_id">
                            <div class="msb-reply">
                                <textarea id="message" name="text_message" placeholder="Type your message here..." rows="1"></textarea>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-paper-plane-o"></i> Send
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row-->
@endsection

@section('extraScript')
<script>
$(document).ready(function() {
    // Debugging point 1
    console.log('Document ready, jQuery version:', $.fn.jquery);
    
    // Check if elements exist
    console.log('ChatList items count:', $('#ChatList li').length);
    
    // Enhanced click handler with event delegation
    $(document).on('click', '#ChatList li', function(e) {
        e.preventDefault();
        const userId = $(this).data('user-id');
        console.log('Clicked user ID:', userId);
        
        if (!userId) {
            console.error('No user ID found!');
            return;
        }
        
        readMsgs(userId, this);
    });

    function readMsgs(userId, element) {
        console.log('Executing readMsgs for user:', userId);
        
        // UI Updates
        $('.chat-conatct-list li').removeClass('active');
        $(element).addClass('active');
        $('#chat-with-user').text($(element).find('.list-group-item-heading').text());
        $('#to_user_id').val(userId);
        $('#chatForm').show();
        
        // AJAX Request with full error handling
        $.ajax({
            url: '/chat/messages/' + userId,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                console.log('Sending request to:', '/chat/messages/' + userId);
                $('#ChatBody').html('<div class="text-center py-3">Loading messages...</div>');
            },
            success: function(response) {
                console.log('Received response:', response);
                
                let chatBody = $('#ChatBody');
                chatBody.html('');
                
                if (!response || response.length === 0) {
                    chatBody.html('<div class="message-feed media"><div class="media-body"><div class="mf-content">No messages yet</div></div></div>');
                    return;
                }
                
                response.forEach(function(message) {
                    let alignClass = (message.sender_id === {{ auth()->id() }}) ? 'text-right' : 'text-left';
                    let bgColor = (message.sender_id === {{ auth()->id() }}) ? 'bg-primary text-white' : 'bg-light text-dark';
                    
                    chatBody.append(`
                        <div class="message-feed media ${alignClass}">
                            <div class="media-body">
                                <div class="mf-content ${bgColor} p-2 rounded">${message.text_message}</div>
                                <small class="text-muted">${new Date(message.created_at).toLocaleString()}</small>
                            </div>
                        </div>
                    `);
                });
                
                // Scroll to bottom of chat
                chatBody.scrollTop(chatBody[0].scrollHeight);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                $('#ChatBody').html('<div class="alert alert-danger">Error loading messages. Please try again.</div>');
            }
        });
    }
    
    // Sidebar toggle code
    $(".app-sidebar__toggle").trigger('click');
    $('.app-sidebar').hover(function() {
        $(".app-sidebar__toggle").trigger('click');
    });
});
</script>
@endsection --}}
