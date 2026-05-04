
<script src="https://www.shipa1.com/assets/js/vendor/jquery-1.12.4.min.js"></script>
<script src="https://www.shipa1.com/assets/js/bootstrap.min.js"></script>
<script src="https://www.shipa1.com/assets/js/popper.min.js"></script>
<script src="https://www.shipa1.com/assets/js/jquery.easing.min.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src=
"https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"
            integrity=
"sha256-yE5LLp5HSQ/z+hJeCqkz9hdjNkk1jaiGG0tDCraumnA="
            crossorigin="anonymous">
    </script>
<script>
    
    function customChatUser(id)
    {
        var role = "{{Auth::user()->role}}";
        $.ajax({
            url:"/custom-chat-user",
            type:"POST",
            data:{id:id},
            success:function(res)
            {
                if(role == 2 || role == 1)
                {
                    $('.chat-center').html(res);
                }
                if(role == 3)
                {
                    $('.chat-center').append(res);
                }
            }
        });
    }
    
    function publicChatUser(id)
    {
        $.ajax({
            url:"/public-chat-user",
            type:"POST",
            data:{id:id},
            success:function(res)
            {
                $('.chat-center').append(res);
            }
        });
    }
    
    function extra() 
    {
        $("#fixedQandAleft select").change(function () {
            var quesAnsval = $("#fixedQandAleft select").find(":selected").text();
            $("#messagetextarea").val(quesAnsval)
        });
        $("#fixedQandAright select").change(function () {
            var quesAnsval = $("#fixedQandAright select").find(":selected").text();
            $("#messagetextarea").val(quesAnsval)
        });
        $('#fixedQandAulleft label input').on('change', function () {
            $("#fixedQandAulleft label input").removeClass("activesss")
            $(this).addClass("activesss")
            var spanele = document.querySelector("#fixedQandAulleft label input.activesss");
            var valmess = $(spanele).val()
            var spanelepre = document.querySelector("#fixedQandAulleft label input").previousElementSibling.innerText = "";
            var spanelepre = document.querySelector("#fixedQandAulleft label input.activesss").previousElementSibling;
            $(spanelepre).append("&nbsp;&nbsp;" + valmess + "&nbsp;&nbsp;")
        })
        $("#fixedQandAulleft label input,#fixedQandAulright label input").keyup(function () {
            $('#fixedQandAulleft input.active,#fixedQandAulright input.active').attr('checked', false);
        });
        $('#fixedQandAulright label input').on('change', function () {
            $("#fixedQandAulright label input").removeClass("activesss")
            $(this).addClass("activesss")
            var spanele = document.querySelector("#fixedQandAulright label input.activesss");
            var valmess = $(spanele).val()
            var spanelepre = document.querySelector("#fixedQandAulright label input").previousElementSibling.innerText = "";
            var spanelepre = document.querySelector("#fixedQandAulright label input.activesss").previousElementSibling;
            $(spanelepre).append("&nbsp;&nbsp;" + valmess + "&nbsp;&nbsp;")
            
        })
        $('#fixedQandAulleft input:radio').on('change', function () {
            $('#fixedQandAulleft input:radio').removeClass("active");
            var quesAnsval1 = $('#fixedQandAulleft input[name=question__name]:checked').addClass("active");
            var ch = document.querySelector("#fixedQandAulleft input.active").nextElementSibling.innerText;
            var text = $("#fixedQandAulleft input.active").siblings('label').children('input').val();
            $("#fixedQandAulleft input.active").siblings('label').children('input').val('')
            if (text != '') {
                $("#messagetextarea").val(ch);
                var id = $(this).siblings('.q_ID').val();
                $('#q_ID').val(id);
                $('#a_ID').val("");
            }
            else {
                alert("Fill the field");
                $("#messagetextarea").val('');
                $('#fixedQandAulleft input.active').attr('checked', false);
            }
        });
        $('#fixedQandAulright input:radio').on('change', function () {
            $('#fixedQandAulright input:radio').removeClass("active");
            var quesAnsval1 = $('#fixedQandAulright input[name=question__name]:checked').addClass("active");
            var ch = document.querySelector("#fixedQandAulright input.active").nextElementSibling.innerText;
            var text = $("#fixedQandAulright input.active").siblings('label').children('input').val();
            $("#fixedQandAulright input.active").siblings('label').children('input').val('')
            if (text != '') {
                $("#messagetextarea").val(ch);
                var id = $(this).siblings('.a_ID').val();
                $('#a_ID').val(id);
                $('#q_ID').val("");
            }
            else {
                alert("Fill the field");
                $("#messagetextarea").val('');
                $('#fixedQandAulright input.active').attr('checked', false);
            }
        });
        setInterval(() => {
            $("#messagetextarea").attr('disabled', "true");
        }, 1000);

        let SwiperTop = new Swiper('.mySwiper', {
            spaceBetween: 0,
            speed: 6000,
            direction: "vertical",
            autoplay: {
                delay: 1,
            },
            loop: true,
            grabCursor: false,
            slidesPerView: 'auto',
            allowTouchMove: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: false,
            },
        });
    }
    extra();

    $(document).ready(function() {


        function getMsg(oID)
        {
            var Chat__Body = $('.Chat__Body');
            var mOrderId = Chat__Body.siblings('.mOrderId').val();
            Chat__Body.animate({scrollTop: 20000000000}, "fast");
            var authID = "{{auth()->id()}}";
            $.ajax({
                url:"{{url('/msg')}}",
                type: "GET",
                dataType: "json",
                data: {order_id:mOrderId},
                success: function(res)
                {
                    Chat__Body.children().remove();

                    if(res.qna)
                    {
                        $.each(res.qna, function(){
                            if(this.user_id == authID)
                            {
                                Chat__Body.append(`
                                    <div class="Chat__Body--box rightChat">
                                        <div class="Chat__Body--box--txt">
                                            <p class="bg-primary text-white">
                                                ${this.message}
                                            </p>


                                        </div>
                                    </div>
                                `);
                            }
                            else{
                                Chat__Body.append(`
                                    <div class="Chat__Body--box leftChat">
                                        <div class="Chat__Body--box--img">
                                            ${this.user.slug ? this.user.slug : this.user.name}
                                        </div>
                                        <div class="Chat__Body--box--txt">
                                            <p class="">
                                                ${this.message}
                                            </p>

                                        </div>

                                    </div>`
                                );
                            }
                        })
                    }
                    else{
                        Chat__Body.append(`
                            <div class="Chat__Body--box">
                                <h1 class="text-center">No Chat</h1>
                            </div>
                        `);
                    }
                    // console.log(res);
                }
            });
        }

        var route = "{{\Request::segment(2)}}";
        function index(page,limit,searchIn,search) 
        {
            var global_search = $('.global').val();
            $.ajax({
                url:"{{url('/searchFilter?page=')}}"+page,
                type: "GET",
                // dataType: "json",
                data:{limit:limit,route:route,searchIn:searchIn,search:search,global_search:global_search},
                beforeSend: function () {
                    $('.tabMainbody').html("");
                    $('.tabMainbody').append(`<div class="lds-hourglass" id='ldss'></div>`);
                },
                success: function(res) {
                    // console.log(res);
                    $('.tabMainbody').html("");
                    $(".tabMainbody").html(res);

                    $('.qnaModal').click(function () {
                        // console.log('hello');
                        var id = $(this).siblings('.oID').val();
                        $.ajax({
                            url : "{{url('/qna-modal')}}",
                            type : 'GET',
                            data : {id : id},
                            success: function(res) {
                                $("body").children('.modalQNA').html(res);
                                $(`#viewQuestionModal${id}`).modal('show');
                                $('#fixedQandAulleft').children('li').children('label').children('div').children('input').attr('disabled', false);
                                extra();
                                getMsg(id);
                                setInterval(function() {
                                    getMsg(id);
                                },5000);

                                $('.close').click(function() {
                                    setTimeout(() => {
                                        $("body").children('.modalQNA').children('.modal').remove();
                                    },1000);
                                });
                                $('.questionSelect').change(function(){
                                    var q_id = $(this).children('.demo').val();
                                    // console.log(q_id);
                                    $.ajax({
                                        url:"{{url('/ques-ans')}}",
                                        type:"GET",
                                        data:{id:q_id},
                                        success:function(data){
                                            $('#fixedQandAulright').html("");
                                            $('#fixedQandAulright').html(data);
                                            $('#fixedQandAulright').children('li').children('label').children('div').children('input').attr('disabled', false);
                                            extra();
                                        }
                                    });
                                });

                                $('.sendMessage').click(function(){
                                    var message = $('#messagetextarea');
                                    var qID = $('#q_ID').val();
                                    var aID = $('#a_ID').val();
                                    var oID = $('#oID').val();
                                    if(message.val())
                                    {
                                        $.ajax({
                                            url:"{{url('/send-message')}}",
                                            type:"GET",
                                            dataType:"json",
                                            data:{message:message.val(),q_id:qID,a_id:aID,order_id:oID},
                                            success:function(res)
                                            {
                                                getMsg(oID);
                                            }
                                        })
                                    }
                                    else{
                                        console.log("Please select question or answer");
                                    }
                                })

                            }
                        });
                    });
                }
            })
        }


        if(route)
        {
            $('.searchFilter').change(function() {
                var limit = $(this).children('option:selected').val();
                var searchIn = $(this).parents('.searchmainleft').siblings('.searchmainright').children('.searchIn').children('option:selected').val();
                var search = $(this).parents('.searchmainleft').siblings('.searchmainright').children('.searchWith').val();
                // console.log(page);
                index(1,limit,searchIn,search);
            })
    
            $('.searchWith').keyup(function(e){
                if(e.which == 13)
                {
                    var searchIn = $(this).siblings('.searchIn').children('option:selected').val();
                    var search = $(this).val();
                    var limit = $(this).parents('.searchmainright').siblings('.searchmainleft').children('.searchFilter').children('option:selected').val();
                    index(1,limit,searchIn,search);
                }
            })
    
            $(document).on('click', '.pagination a', function (event) {
    
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                var searchIn = $('.searchIn').children('option:selected').val();
                var search = $('.searchWith').val();
                var limit = $('.searchFilter').children('option:selected').val();
                index(page,limit,searchIn,search);
    
            });
            index(0,5,'id','');
        }
    })
    
</script>
{{--<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>--}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
