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
            <h1 class="my-4"><b>Chats</b></h1>
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
                                    <li><a href="#tab-9" data-toggle="tab">Group</a></li>
                                </ul>
                                <input type="hidden" value="Chat" id="chatText">
                            </div>
                        </div>
                        <div class="tab-content" id="getChatList">
                            @include('global_chat.chat-list')
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
                                    style="
    margin-right: 20px;
    padding-right: 30px;
"></textarea>
                                <div class="flex_right" style="
    display: flex;
    gap: 0.8rem;
">
                                    {{-- <button type="button">
                                        <label for="attach" class="flex_chat">
                                            <i class="fa fa-paperclip" aria-hidden="true"></i>
                                        </label>
                                        <input type="file" style="opacity:0;width:0;" id="attach" name="attach"
                                            accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf" />
                                    </button>
                                    <button type="button">
                                        <label for="img" class="flex_chat">
                                            <i class="fa fa-picture-o" aria-hidden="true"></i>
                                        </label>
                                        <input type="file" style="opacity:0;width:0;" id="img" name="img"
                                            accept="image/jpg, image/jpeg, image/png, image/gif" />
                                    </button> --}}
                                    <button type="submit" class="flex_chat"><i class="fa fa-paper-plane-o"></i></button>
                                </div>
                            </div>
                            <input type="hidden" id="to_user_id" name="to_user_id" value="" />
                            <input type="hidden" id="count_read" value="0">
                            <input type="hidden" id="count_interval" value="0">
                        </form>

                        <form action="" id="form2" method="POST" style="display: none">
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
                            <div class="msb-reply">
                                <textarea id="description2" name="description2" placeholder="What's on your mind..." style="padding-right:150px;"></textarea>
                                {{-- <button type="button" style="right: 50px !important;">
                                    <label for="attach" class="m-auto">
                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                    </label>
                                    <input type="file" style="opacity:0;width:0;" id="attach" name="attach"
                                        accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf" />
                                </button>
                                <button type="button" style="right: 90px !important;">
                                    <label for="img" class="m-auto">
                                        <i class="fa fa-picture-o" aria-hidden="true"></i>
                                    </label>
                                    <input type="file" style="opacity:0;width:0;" id="img" name="img"
                                        accept="image/jpg, image/jpeg, image/png, image/gif" />
                                </button> --}}
                                <button type="submit"><i class="fa fa-paper-plane-o"></i></button>
                            </div>
                            <input type="hidden" id="group_id" name="group_id" value="" />
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

        //   function attachMent(input) {
        //       if (input.files && input.files[0]) {
        //         var reader2 = new FileReader();

        //         reader2.onload = function (e) {
        //           $('#fileName').html(e.target.result);
        //           $('#attachment').show();
        //         };

        //         reader2.readAsDataURL(input.files[0]);
        //       }
        //     }

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

        // function fetch_data(to_user_id, from_user_id, tousername,login,time) {
        //     $('#form2').hide();
        //     $('#count_read').val(1);

        //     // alert(tousername);
        //     document.getElementById("to_user_id").value = to_user_id;
        //     document.getElementById("touser").innerHTML = tousername;
        //     if(login == 0)
        //     {
        //         document.getElementById("loginOrNot").innerHTML = time;
        //     }
        //     else{
        //         document.getElementById("loginOrNot").innerHTML = '<span class="dot-label bg-success mr-2 w-2 h-2"></span>Online';
        //     }
        //     touserId = document.getElementById("to_user_id").value;
        //     $('#ChatBody').html("");

        //     $.ajax({

        //         type: "GET",
        //         url: "/get_chat",
        //         data: {'touserId': touserId},
        //         success: function (data) {
        //             $('#ChatBody').html(data);
        //             $("#ChatBody").animate({scrollTop: 20000000000}, "slow");
        //             $('#form').show();
        //         },
        //         error: function (e) {
        //             $("#err").html(e).fadeIn();
        //         }

        //     });

        //     $.ajax({

        //         type: "GET",
        //         url: "/view_chat",
        //         data: {'touserId': touserId},
        //         success: function (data) {

        //         },
        //         error: function (e) {
        //             $("#err").html(e).fadeIn();
        //         }

        //     });


        //     var interval_id = setInterval(function () {
        //         var count_read = $('#count_read').val();

        //         $.ajax({

        //             type: "GET",
        //             url: "/view_chat_timer",
        //             data: {'touserId': touserId},
        //             success: function (data) {
        //                 if (data > 0) {
        //                     fetch_data3(touserId);
        //                 }
        //             },
        //             error: function (e) {
        //                 $("#err").html(e).fadeIn();
        //             }

        //         });


        //     }, 10000);

        //     for (var i = 1; i < interval_id; i++)
        //         window.clearInterval(i);


        // }

        function fetch_data(to_user_id) {
            $('#form2').hide();
            $('#form').show();
            $("#to_user_id").val(to_user_id);
            $.ajax({
                type: "GET",
                url: "{{ route('get_chat') }}",
                data: {
                    touserId: to_user_id
                },
                success: function(data) {
                    $('#chat-box-new').html("");
                    $('#chat-box-new').html(data);
                    $("#ChatBody").animate({
                        scrollTop: 20000000000
                    }, "slow");
                    // if(to_user_id)
                    // {
                    // }
                },
                error: function(e) {
                    $("#err").html(e).fadeIn();
                }

            });
        }

        function getChat(to_user_id) {
            $('#form2').hide();
            $('#form').show();
            $("#to_user_id").val(to_user_id);
            $.ajax({
                type: "GET",
                url: "{{ route('get_chat_runtime') }}",
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
                    // if(to_user_id)
                    // {
                    // }
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
            $.ajax({
                type: "GET",
                url: "{{ route('read_msg') }}",
                data: {
                    touserId: to_user_id
                },
                success: function(data) {
                    fetch_data(to_user_id);
                }

            });

        }

        function viewGroupChat(groupId) {
            $('#form').hide();
            $('#form2').show();
            $.ajax({

                type: "GET",
                url: "{{ route('get_group_chat') }}",
                data: {
                    'groupId': groupId
                },
                success: function(data) {
                    $('#chat-box-new').html("");
                    $('#chat-box-new').html(data);
                    $("#ChatBody").animate({
                        scrollTop: 20000000000
                    }, "slow");
                    // if(groupId)
                    // {
                    // }
                },
                error: function(e) {
                    $("#err").html(e).fadeIn();
                }

            });
        }

        function viewGroupChat2(groupId) {
            $('#form').hide();
            $('#form2').show();
            $.ajax({

                type: "GET",
                url: "{{ route('get_group_chat_runtime') }}",
                data: {
                    'groupId': groupId
                },
                success: function(data) {
                    if (data) {
                        $('#chat-box-new').html("");
                        $('#chat-box-new').html(data);
                        $("#ChatBody").animate({
                            scrollTop: 20000000000
                        }, "slow");
                    }
                    // if(groupId)
                    // {
                    // }
                },
                error: function(e) {
                    $("#err").html(e).fadeIn();
                }

            });
        }

        function groupChat(group_id, e) {
            $('.list-group-item').removeClass("active");
            fetch_data4(group_id);
            viewGroupChat(group_id);
            e.classList.add("active");
        }

        function fetch_data4(group_id) {
            $("#group_id").val(group_id);
            $.ajax({
                type: "GET",
                url: "{{ route('view_group_chat') }}",
                data: {
                    groupId: group_id
                },
                success: function(data) {},
                error: function(e) {
                    $("#err").html(e).fadeIn();
                }

            });
        }

        // function fetch_data4(group_id, groupname) {
        //     $('#form').hide();
        //     $('#count_read').val(1);

        //     // alert(tousername);
        //     document.getElementById("group_id").value = group_id;
        //     document.getElementById("touser").innerHTML = groupname;
        //     groupId = document.getElementById("group_id").value;
        //     $('#ChatBody').html("");

        //     $("#ChatBody").animate({scrollTop: 20000000000}, "slow");
        //     viewGroupChat(groupId);

        //     $.ajax({

        //         type: "GET",
        //         url: "/view_group_chat",
        //         data: {'groupId': groupId},
        //         success: function (data) {

        //         },
        //         error: function (e) {
        //             $("#err").html(e).fadeIn();
        //         }

        //     });

        //     var interval_group_id = setInterval(function () {
        //         viewGroupChat(groupId);
        //     },10000);

        //     for (var i = 1; i < interval_group_id; i++)
        //         window.clearInterval(i);


        // }

        // function fetch_data3(to_user_id) {

        //     $.ajax({

        //         type: "GET",
        //         url: "/get_chat2",
        //         data: {'touserId': touserId},
        //         success: function (data) {
        //             $('#ChatBody').append(data);
        //             $("#ChatBody").animate({scrollTop: 20000000000}, "slow");
        //         },
        //         error: function (e) {
        //             $("#err").html(e).fadeIn();
        //         }

        //     });
        //     var count_read = $('#count_read').val();
        //     if(count_read == 1){
        //         viewchat(touserId);
        //     }
        // }

        // function viewchat(touserId) {

        //     $.ajax({

        //         type: "GET",
        //         url: "/view_chat",
        //         data: {'touserId': touserId},
        //         success: function (data) {

        //         },
        //         error: function (e) {
        //             $("#err").html(e).fadeIn();
        //         }

        //     });

        // }

        // function fetch_data2(to_user_id) {

        //     document.getElementById("to_user_id").value = to_user_id;
        //     touserId = document.getElementById("to_user_id").value;
        //     $('#ChatBody').html("");

        //     $.ajax({

        //         type: "GET",
        //         url: "/get_chat",
        //         data: {'touserId': touserId},
        //         success: function (data) {
        //             $('#ChatBody').html(data);
        //             $("#ChatBody").animate({scrollTop: 20000000000}, "slow");
        //         },
        //         error: function (e) {
        //             $("#err").html(e).fadeIn();
        //         }

        //     });

        //     $.ajax({

        //         type: "GET",
        //         url: "/view_chat",
        //         data: {'touserId': touserId},
        //         success: function (data) {

        //         },
        //         error: function (e) {
        //             $("#err").html(e).fadeIn();
        //         }

        //     });


        // }

        document.getElementById('description').onkeydown = function(e) {
            if (e.keyCode == 13) {
                $('#form').submit();
                $('#description').val("");
                $('#her').focus();
            }
        };

        document.getElementById('description2').onkeydown = function(e) {
            if (e.keyCode == 13) {
                $('#form2').submit();
                $('#description2').val("");
                $('#her').focus();
            }
        };


        $(document).ready(function(e) {
            $("#form").on('submit', (function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('save_chat') }}",
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

                        // view uploaded file.
                        //$("#preview").html(data).fadeIn();

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

            $("#form2").on('submit', (function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('save_group_chat') }}",
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
                        // view uploaded file.
                        //$("#preview").html(data).fadeIn();

                        let test = data.toString();
                        let test2 = $.trim(test);
                        let text = "SUCCESS";
                        if (test2 == text) {
                            var group_id = $('#group_id').val();
                            viewGroupChat(group_id);
                            $('#description2').val("");
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
                url: "{{ route('get-chats') }}",
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

            if ($("#chatText").val() == 'Chat') {
                var to_user_id = $("#to_user_id").val();
                // fetch_data(to_user_id);
                getChat(to_user_id);
            }
            if ($("#chatText").val() == 'Group') {
                var group_id = $('#group_id').val();
                fetch_data4(group_id);
                viewGroupChat2(group_id);
            }
        }, 5000);

        $("a[data-toggle='tab']").click(function() {
            $("#chatText").val($(this).text());

            if ($("#chatText").val() == 'Chat') {
                var to_user_id = $("#to_user_id").val();
                fetch_data(to_user_id);
            }
            if ($("#chatText").val() == 'Group') {
                var group_id = $('#group_id').val();
                fetch_data4(group_id);
                viewGroupChat(group_id);
            }
        })
    </script>
    <script>
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
    </script>
@endsection
