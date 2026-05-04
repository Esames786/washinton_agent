<!DOCTYPE html>
<html lang="en" dir="ltr">

<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>
    @include('partials.chat.head')
    <style>
        .page-header{
            margin:0 !important;
        }
        .page-header h1{
            margin:5px !important;
        }
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        th , div , tr , h1 , h2 , h3 , h4, h5, h6, p, span{
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="app sidebar-mini">
{{-- @if(Auth::check())
    @if(Auth::user()->freeze == 1)
    <marquee class="bg-danger text-light" style="position:fixed;top:0;width:100%;z-index:99999;height:50px;opacity:1;">
        <h3 class="mt-3">
            Your account has been freezed due to inactivity. Kindly contact with admin.
        </h3>
    </marquee>
    @endif
@endif --}}

<!-- Start Switcher -->
<!--<div class="switcher-wrapper">-->
<!--    <div class="demo_changer">-->
<!--        {{-- fa-blink  --}}-->
<!--        <div class="demo-icon"><i class="fa fa-exclamation-triangle text_primary"-->
<!--                                  style="color: red;font-size: 30px; "></i></div>-->
<!--        <div class="form_holder switcher-sidebar">-->
<!--            <div class="row">-->
<!--                <div class="predefined_styles" style=" width: 100%; ">-->
<!--                    <div class="swichermainleft">-->

<!--                        <div class="card overflow-hidden">-->
<!--                            <div class="card-header bg-info ">-->
<!--                                <h3 class="card-title text-white">Work Title</h3>-->

<!--                            </div>-->
<!--                            <div class="card-body">-->
<!--                                <h5>some work</h5>-->
<!--                                <h6>24/08/2021</h6>-->
<!--                            </div>-->
<!--                            <div class="card-footer">-->

<!--                                <a href="" class="btn btn-info btn-sm">Done</a>-->
<!--                                <a href="" class="btn btn-secondary btn-sm ml-2">Reject</a>-->

<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->

<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!-- End Switcher -->


<!---Global-loader-->
<!--<div id="global-loader">-->
<!--    <img src="{{ url('assets/images/svgs/loader.svg')}}" alt="loader">-->
<!--</div>-->
<!--- End Global-loader-->
<!-- Page -->
<div class="page">
    <div class="page-main">
        {{--sidebar start--}}
        {{-- @include('partials.sidebar-menu') --}}
        {{--sidebarend--}}

        <div class="app-content main-content">
            <div class="side-app">
                <!--nav header-->
            {{-- @include('partials.mainsite_pages.nav') --}}
            <!--/nav header--><br>
                <div id="session_msg">
                    @if(Session::has('msg'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                      <strong>{{Session::get('msg')}}</strong>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    @endif
                    @if(Session::has('err'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <strong>{{Session::get('err')}}</strong>
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    @endif

                    @if(Auth::check())
                        <div class="d-flex justify-content-between">
                            <?php
                                $role = Auth::user()->userRole->name;
                            ?>
                            @if($role == 'Order Taker' || $role == 'CSR' || $role == 'Seller Agent' || $role == 'Manager' || $role == 'Dispatcher' || $role == 'Admin')
                            <a href="{{ url('/user_rating') }}" class="badge badge-warning text-light position-relative" id="rating_count">
                                <i class="fa fa-star"></i>
                                @if($role == 'Admin')
                                    Rating
                                @else
                                    Your Rating
                                @endif
                            </a>
                            @else
                            <div></div>
                            @endif
                            <div class="d-flex">
                                {{-- @if(Auth::user()->break_time == 1) --}}
                                <?php
                                    date_default_timezone_set('America/New_York');
                                    $timeFirst  = strtotime(Auth::user()->updated_at);
                                    $timeSecond = strtotime(now());
                                    $differenceInSeconds = $timeSecond - $timeFirst;
                                    $getTime = round(($differenceInSeconds/60),2);
                                ?>
                                <input type="hidden" id="break" name="break" value="{{$getTime}}" />
                                <a href="{{url('/end_time')}}" class="badge badge-danger mr-3" id="end_time">00:00</a>
                                {{-- @else
                                <a href="{{url('/start_time')}}" class="badge badge-success mr-3" id="start_time">Start Time</a>
                                @endif --}}
                                <a href="{{url('/clear_cache')}}" class="badge badge-danger" id="clear_cache">Clear Cache</a>
                            </div>
                        </div>
                    @endif
                </div>

                <!--/content-->
                @yield('content')

            </div>
        </div>
        <!-- End app-content-->
    </div>



    <footer class="footer">
        <div class="container">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-md-12 col-sm-12 text-center">
                    Copyright © {{date('Y')}} <a href="{{url('/dashboard')}}">Hello Tranport</a>. Designed by <a  href="{{url('/dashboard')}}">Hello Tranport
                        IT DEPARTMENT </a> All rights reserved.
                </div>
            </div>
        </div>
    </footer>
    {{-- <input type="hidden" id="time_user" value="{{Auth::user()->ss_time}}" /> --}}

</div><!-- End Page -->

@include('partials.chat.foot')

@yield('extraScript')

<script type="text/javascript">

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });



    function updatePort(id)
    {
        $.ajax({
            url:"/edit-port",
            type:"GET",
            data:{id:id},
            success:function(res)
            {
                $(".port-details-modal").children().remove();
                $(".port-details-modal").html(res);
                $(`#updatePortDetail${id}`).modal('show');
            }
        })
    }

    function deletePort(id)
    {
        $.ajax({
            url:"/delete-port",
            type:"GET",
            data:{id:id},
            success:function(res)
            {
                window.location.reload();
            }
        })

    }

    function allReviews()
    {
        $.ajax({
            type: "GET",

            url: "/get_notes",

            success: function (data) {
                $('.allReviews').html(data);
                // console.log(data);

                $(".deleteNote").click(function(){
                    var id = $(this).siblings("#noteId").val();
                    $.ajax({
                        type: "GET",

                        url: "/delete_notes",
                        data: {id:id},
                        success: function (data) {
                            allReviews();
                            // console.log(data);
                        },
                    });
                });
            },
        });
    }

    $(".viewNotes").click(function(){
        allReviews();
    });

    $(".addContent").click(function(){
        var notes_value = $('.richText-editor').html();
        $.ajax({
            type: "POST",

            url: "{{ url('/notes_save') }}",

            data: {notes_value: notes_value},

            success: function (data) {
                $('.richText-editor').html("");
            },

        });
    });


    $(document).ready(function(){

        $('.addPort').click(function(){
            var delivery_address = $("#delivery_address");
            var port_name = $("#port_name");
            var terminal_name = $("#terminal_name");
            var make_sure = 0;
            if($("#make_sure").is(":checked"))
            {
                make_sure = 1;
            }
            var accident_vehicle = 0;
            if($("#accident_vehicle").is(":checked"))
            {
                accident_vehicle = 1;
            }
            var address = $("#address");
            var zip = $("#zip");
            var state = $("#state");
            var city = $("#city");
            var telephone = $("#telephone");
            var twic_card = $("#twic_card").children("option:selected").val();

            delivery_address.parent().children('.text-danger').remove();
            port_name.parent().children('.text-danger').remove();
            terminal_name.parent().children('.text-danger').remove();

            if(delivery_address.children("option:selected").val() == '')
            {
                delivery_address.parent().append('<span class="text-danger">This field is required!</span>');
            }
            if(port_name.val() == '')
            {
                port_name.parent().append('<span class="text-danger">This field is required!</span>');
            }
            if(terminal_name.val() == '')
            {
                terminal_name.parent().append('<span class="text-danger">This field is required!</span>');
            }
            if(delivery_address.children("option:selected").val() != '' && port_name.val() != '' && terminal_name.val() != '')
            {
                $.ajax({
                    url:"{{url('/add-port')}}",
                    type:"POST",
                    data:{
                        delivery_address:delivery_address.children("option:selected").val(),
                        port_name:port_name.val(),
                        terminal_name:terminal_name.val(),
                        make_sure:make_sure,
                        accident_vehicle:accident_vehicle,
                        address:address.val(),
                        zip:zip.val(),
                        state:state.val(),
                        city:city.val(),
                        telephone:telephone.val(),
                        twic_card:twic_card
                    },
                    success:function(data){
                        window.location.reload();
                    }
                });
            }
        })

        function updateNewPort(id)
        {
            var delivery_address = $("#delivery_address"+id);
            var port_name = $("#port_name"+id);
            var terminal_name = $("#terminal_name"+id);
            var make_sure = 0;
            if($("#make_sure"+id).is(":checked"))
            {
                make_sure = 1;
            }
            var accident_vehicle = 0;
            if($("#accident_vehicle"+id).is(":checked"))
            {
                accident_vehicle = 1;
            }
            var address = $("#address"+id);
            var zip = $("#zip"+id);
            var state = $("#state"+id);
            var city = $("#city"+id);
            var telephone = $("#telephone"+id);
            var twic_card = $("#twic_card"+id).children("option:selected").val();

            delivery_address.parent().children('.text-danger').remove();
            port_name.parent().children('.text-danger').remove();
            terminal_name.parent().children('.text-danger').remove();

            if(delivery_address.children("option:selected").val() == '')
            {
                delivery_address.parent().append('<span class="text-danger">This field is required!</span>');
            }
            if(port_name.val() == '')
            {
                port_name.parent().append('<span class="text-danger">This field is required!</span>');
            }
            if(terminal_name.val() == '')
            {
                terminal_name.parent().append('<span class="text-danger">This field is required!</span>');
            }
            if(delivery_address.children("option:selected").val() != '' && port_name.val() != '' && terminal_name.val() != '')
            {
                $.ajax({
                    url:"{{url('/update-port')}}",
                    type:"POST",
                    data:{
                        id:id,
                        delivery_address:delivery_address.children("option:selected").val(),
                        port_name:port_name.val(),
                        terminal_name:terminal_name.val(),
                        make_sure:make_sure,
                        accident_vehicle:accident_vehicle,
                        address:address.val(),
                        zip:zip.val(),
                        state:state.val(),
                        city:city.val(),
                        telephone:telephone.val(),
                        twic_card:twic_card
                    },
                    success:function(data){
                        window.location.reload();
                    }
                });
            }
        }
    });

    function customChatShow(uId,oid22)
    {
        $.ajax({
            url:'/show-chat-center',
            type:'POST',
            data:{uId:uId,oid22:oid22},
            success:function(res)
            {
                $('.chat-center').append(res);
                $(".chat-user").animate({ scrollTop: $(document).height()}, 1000);

            }
        });
    }

    function getAutoChat()
    {
        $.ajax({
            url:'/get-auto-chat',
            type:'POST',
            success:function(res)
            {
                // console.log(res);
                $('.chat-center').html(res);
                $(".chat-user").animate({ scrollTop: $(document).height()}, 1000);
            }
        });
    }

    function getAutoChat2()
    {
        $.ajax({
            url:'/get-auto-chat2',
            type:'POST',
            success:function(res)
            {
                if(res.status === 400)
                {

                }
                else{
                    // console.log(res);
                    $('.chat-center').append(res);
                    $(".chat-user").animate({ scrollTop: $(document).height()}, 1000);
                }
            }
        });
    }

    setTimeout(function(){
        getAutoChat();
    },10);

    function status(s)
    {
        var status = '';
        if(s == 0){
            status = ` <i class="fa fa-check-circle text-light" style="top:0;"></i>`;
        }
        else if(s == 1){
            status = ` <i class="fa fa-check-circle text-warning" style="top:0;"></i>`;
        }
        else{
            status = ` <i class="fa fa-check-circle text-success" style="top:0;"></i>`;
        }

        return status;
    }

    function getAllConvo(v)
    {
        var id = "{{ Auth::guard('Admin')->user()->id }}";
        var data = '';
        var date = '';
        var flag = '';
        if(v.date)
        {
            date = `<p class="bg-secondary text-light text-center" style="width: 50%;border-radius: 30px;padding: 6px 10px;margin: 6px auto 0;">${v.date}</p>`;
        }
        if(v.flag)
        {
            if(v.flag.user_id == v.from_user_id)
            {
                if(id == v.flag.user_id){
                    var name = 'You';
                } else {
                    var name = v.flag.user.slug ?? v.flag.user.name+' '+v.flag.user.last_name;
                }
                flag = `<p class="text-danger text-center" style="width: 50%;margin: 6px auto;"><b>${name} got a <i class="fa fa-flag-o" aria-hidden="true"></i> Flag.</b></p>`
            }
        }
        if(v.from_user_id == id)
        {
            data += `${date}
            <div class="message-feed right media py-0">
                <div class="media-body">
                    <div class="mf-content" style="background:#705ec8;">
                        ${v.message}
                    </div>
                    <small class="mf-date text-dark"> ${v.message_time}
                    ${status(v.status)}
                    </small>
                </div>
            </div>${flag}`;
        }
        else{
            data += `${date}
            <div class="message-feed media py-0">
                <div class="media-body">
                    <div class="mf-content">
                        ${v.message}
                    </div>
                    <small class="mf-date text-dark"> ${v.message_time}
                    </small>
                </div>
            </div>${flag}`;
        }

        return data;
    }

    function getAllConvo2(v)
    {
        var id = "{{ Auth::guard('Admin')->user()->id }}";
        var data = '';
        var date = '';
        var flag = '';
        if(v.date)
        {
            date = `<p class="bg-secondary text-light text-center" style="width: 50%;border-radius: 30px;padding: 6px 10px;margin: 6px auto 0;">${v.date}</p>`;
        }
        if(v.flag)
        {
            if(v.flag.user_id == v.user_id)
            {
                if(id == v.flag.user_id){
                    var name = 'You';
                } else {
                    var name = v.flag.user.slug ?? v.flag.user.name+' '+v.flag.user.last_name;
                }
                flag = `<p class="text-danger text-center" style="width: 50%;margin: 6px auto;"><b>${name} got a <i class="fa fa-flag-o" aria-hidden="true"></i> Flag.</b></p>`
            }
        }
        if(v.user_id == id)
        {
            data += `${date}
            <div class="message-feed right media py-0">
                <div class="media-body">
                    <div class="mf-content" style="background:#705ec8;">
                        ${v.message}
                    </div>
                    <small class="mf-date text-dark"> ${v.message_time}
                    ${status(v.status)}
                    </small>
                </div>
            </div>${flag}`;
        }
        else{
            data += `${date}
            <div class="message-feed media py-0">
                <div class="media-body">
                    <h6>${ v.user.slug ?? v.user.name+' '+v.user.last_name }</h6>
                    <div class="mf-content">
                        ${v.message}
                    </div>
                    <small class="mf-date text-dark"> ${v.message_time}
                    </small>
                </div>
            </div>${flag}`;
        }

        return data;
    }

    function getAutoConvo()
    {
        $.ajax({
            url:'/get-auto-convo',
            type:'POST',
            success:function(res)
            {
                $.each(res.chat,function(key,value){
                    $(`.user-id-${value.user_id}`).children().remove();
                    $.each(value.chat,function(k,v){
                        $(`.user-id-${value.user_id}`).append(`${getAllConvo(v)}`);
                    })
                });
                $.each(res.chat2,function(key,value){
                    $(`.public-id-${value.public_id}`).children().remove();
                    $.each(value.chat,function(k,v){
                        $(`.public-id-${value.public_id}`).append(`${getAllConvo2(v)}`);
                    })
                });
            }
        });
    }

    // setInterval(function(){
    //     getAutoConvo();
    // },1000 * 30);

    // setInterval(function(){
    //     getAutoChat2();
    // },5000);
    getAutoChat2();

        setInterval(function(){
            $("body a").attr("href","#");
            $("body a").removeAttr("target");
            $("body button").removeAttr("type");
            $("body a").removeAttr("data-target");
            $("body button").removeAttr("data-target");
            $("body a").removeAttr("data-toggle");
            $("body button").removeAttr("data-toggle");
            $("body form").attr("action","#");

            $("body").children().css("opacity",0.5);
            $("body marquee").css("opacity",1);
        },5000);
        $("body a").attr("href","#");
        $("body a").removeAttr("target");
        $("body button").removeAttr("type");
        $("body a").removeAttr("data-target");
        $("body button").removeAttr("data-target");
        $("body form").attr("action","#");

        $("body").children().css("opacity",0.5);
        $("body marquee").css("opacity",1);

    var role = "{{ Auth::guard('Admin')->user()->role_id }}";
    var rolename = "{{ Auth::guard('Admin')->user()->role->role }}";

    // if(role > 1)
    // {
    //     function take_ss()
    //     {
    //         var dataURL = {};
    //         if($("#time_user").val() >= 270)
    //         {
    //             html2canvas(document.body).then(canvas => {
    //                 dataURL = canvas.toDataURL();
    //                 // console.log(dataURL);
    //                  $.ajax({
    //                      url: "{{ url('/auto_screenshot') }}",
    //                      type: "POST",
    //                      data: {
    //                          image: dataURL
    //                      },
    //                      dataType: "html",
    //                      success: function(res) {
    //                      }
    //                  });
    //             });
    //             $.ajax({
    //                 url: "{{ url('/time_user') }}",
    //                 type: "GET",
    //                 dataType:"json",
    //                 success:function(res)
    //                 {
    //                     $("#time_user").val(res.time);
    //                 }
    //             });
    //         }
    //         else
    //         {
    //             $.ajax({
    //                 url: "{{ url('/time_user') }}",
    //                 type: "GET",
    //                 dataType:"json",
    //                 success:function(res)
    //                 {
    //                     $("#time_user").val(res.time);
    //                 }
    //             });
    //         }
    //     }

    //     setInterval(function(){
    //         take_ss();
    //     },1000 *30);

    //     setTimeout(function(){
    //         take_ss();
    //     },1000);
    // }

    if(rolename == 'Order Taker' || rolename == 'CSR' || rolename == 'Seller Agent' || rolename == 'Manager' || rolename == 'Dispatcher' || rolename == 'Admin')
    {
        function rating_count()
        {
            $.ajax({
                url:"{{ url('/rating_count') }}",
                type:"GET",
                success:function(res)
                {
                    $("#rating_count").children('.badge-danger').remove();
                    if(res > 0)
                    {
                        $("#rating_count").append(`<span class="badge badge-danger rounded-circle d-flex justify-content-center align-items-center" style="position:absolute;height: 30px;width: 30px;top: -16px;right: -16px;font-size:12px;">${res > 99 ? '99+' : res}</span>`);
                    }
                }
            })
        }
        rating_count();
        setInterval(function(){
            rating_count();
        },10000);
    }

    // if(rolename == 'Order Taker' || rolename == 'CSR' || rolename == 'Seller Agent' || rolename == 'Manager' || rolename == 'Admin')
    // {
    //     $.ajax({
    //         url:"{{ url('/ot_commission') }}",
    //         type:"GET",
    //         success:function(res)
    //         {
    //             $("#clear_cache").before(res);
    //         }
    //     })
    // }

    var mousePos;
    var time;
            //   for development
            // var delayDetectionTime = 60000;
            // var delayDetectionTime = 300000000;

            //   for production
    var delayDetectionTime = 600000;

    document.onmousemove = handleMouseMove;

            // {{-- checks if authenticated user is on break or not --}}

            // {{-- checking ends --}}

    else
    {
        var breaktime = $("#break").val();
        var breaktime2 = breaktime.split('.');
        var minutes = parseInt(breaktime2[0], 10);
        var seconds = parseInt(breaktime2[1], 10);

        if(seconds > 59)
        {
            seconds = 0;
        }
        else if(seconds == '')
        {
            seconds = 0;
        }

        $('#end_time').text(`${minutes}:${seconds}`);
        setInterval(function(){
            var timer2 = $('#end_time').text();
            var timer = timer2.split(':');
            var minutes = parseInt(timer[0], 10);
            var seconds = parseInt(timer[1], 10);

            if(minutes >= 15 && seconds > 0)
            {
                window.location.href = "{{ url('/end_time') }}";
            }
            if(seconds == 59)
            {
                minutes = minutes + 1;
                seconds = "00";
            }
            else if(seconds < 9 )
            {
                seconds++;
                seconds = "0"+seconds;
            }
            else
            {
                seconds++;
            }
            if(minutes < 9 )
            {
                minutes = "0"+minutes;
            }
            $('#end_time').text(`${minutes}:${seconds}`)
        },1000);
    }
            // setInterval repeats every X ms
            // check if object is empty
    function isObjEmpty (obj) {
        return Object.keys(obj).length === 0;
    }

    function handleMouseMove(event)
    {
        var dot, eventDoc, doc, body, pageX, pageY;

        event = event || window.event;
            // IE-ism

            // If pageX/Y aren't available and clientX/Y are,
            // calculate pageX/Y - logic taken from jQuery.
            // (This is to support old IE)
        if (event.pageX == null && event.clientX != null)
        {
            eventDoc = (event.target && event.target.ownerDocument) || document;
            doc = eventDoc.documentElement;
            body = eventDoc.body;

            event.pageX = event.clientX +
            (doc && doc.scrollLeft || body && body.scrollLeft || 0) -
            (doc && doc.clientLeft || body && body.clientLeft || 0);
            event.pageY = event.clientY +
            (doc && doc.scrollTop  || body && body.scrollTop  || 0) -
            (doc && doc.clientTop  || body && body.clientTop  || 0);
        }

        mousePos = {
            x: event.pageX,
            y: event.pageY
        };
    }

    function ajaxPost(time)
    {
        $.ajax({
            url:"{{ url('update_mouse') }}",
            type:"GET",
            data:{time:time},
            dataType:"JSON",
            success:function(res)
            {
                if(res == true)
                {
                    $("body a").attr("href","#");
                    $("body a").removeAttr("target");
                    $("body button").removeAttr("type");
                    $("body a").removeAttr("data-target");
                    $("body button").removeAttr("data-target");
                    $("body form").attr("action","#");

                    $("body").children().css("opacity",0.5);
                    $("body marquee").css("opacity",1);
                    $("body").prepend(`
                        <marquee class="bg-danger text-light" style="position:fixed;top:0;width:100%;z-index:99999;height:50px;opacity:1;">
                            <h3 class="mt-3">
                                Your account has been freezed due to inactivity. Kindly contact with admin.
                            </h3>
                        </marquee>
                    `);
                }
            }
        })
    }

    function getMousePosition()
    {
        var pos = mousePos;
        if (!pos)
        {
            // time =  delayDetectionTime + delayDetectionTime;
            time =  delayDetectionTime;
            ajaxPost(time);
        }
        else
        {
            if(isObjEmpty(pos))
            {
                // time = delayDetectionTime + delayDetectionTime;
                time = delayDetectionTime;
                ajaxPost(time);
                console.log("hello");
            }
            delete pos.x;
            delete pos.y;
        }
    }
</script>

</body>
</html>
