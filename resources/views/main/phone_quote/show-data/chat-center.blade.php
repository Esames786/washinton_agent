<div class="ChatBody{{ isset($user->id) ? $user->id : 0 }} p-0 col-lg-3 col-md-4 col-sm-6 col-xs-12 countDiv"
             style="border: 1px solid #94bacb;">
    <div class="p-2 pb-3 w-100" style="bottom:0;background:#705ec8;">
        <div class="d-flex justify-content-between">
            <h5 class="text-light my-auto"><span class="chat-time dot-label bg-{{$user->is_login == 1 ? 'success' : 'danger'}}"></span> {{$user->slug ?? $user->name.' '.$user->last_name}}</h5>
            <div style="font-size:18px;" class="my-auto">
                <button class="btn btn-transparent text-light mr-1 p-0 minimize" type="button">
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </button>
                <input type="hidden" value="{{ isset($user->id) ? $user->id : 0 }}" class="exit_u_id" />
                <input type="hidden" value="{{ isset($id) ? $id : 0 }}" class="exit_o_id" />
                <button class="btn btn-transparent text-light ml-1 p-0 exit-chat" type="button">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <span class="text-light my-auto">Chat for Order No#{{ isset($id) ? $id : 0 }}</span>
    </div>
    <div class="chat-form">
        <div class="chat-user user-id-{{ isset($user->id) ? $user->id : 0 }} ">
            @include('main.phone_quote.show-data.custom-msg')
        </div>
        <form action="" class="form2" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token()}}">
            <div class="msb-reply">
                <input type="hidden" value="{{ isset($id) ? $id : 0 }}" class="oid22" name="order_id" />
                <input type="hidden" value="{{ isset($user->id) ? $user->id : 0 }}" class="u_id" name="u_id" />
                <textarea class="description2" name="description2"
                          placeholder="Write your message..." style="padding-right:50px;"></textarea>
                <button type="button" class="send-cus-msg"><i class="fa fa-paper-plane-o"></i></button>
            </div>
        </form>
    </div>
</div>

<script>
    $('.minimize').click(function(){
        var chatForm = $(this).parent('div').parent('div').parent('div').siblings('.chat-form');
        chatForm.toggle();
        if(chatForm.is(":hidden"))
        {
            $(this).parent('div').parent('div').parent('div').parent('.countDiv').css({"border": "none"});
            $(this).parent('div').parent('div').parent('div').css({"position": "absolute"});
        }
        else{
            $(this).parent('div').parent('div').parent('div').parent('.countDiv').css({"border": "1px solid #94bacb"});
            $(this).parent('div').parent('div').parent('div').css({"position": "unset"});
        }
    });
    
    $('.exit-chat').click(function(){
        var exit_u_id = $(this).siblings('.exit_u_id').val();
        var exit_o_id = $(this).siblings('.exit_o_id').val();
        
        $(this).parents('.ChatBody'+exit_u_id).remove();
        $.ajax({
            url:'/exit-chat',
            type:'POST',
            data:{uid:exit_u_id,oid:exit_o_id},
            dataType:"json",
            success:function(res)
            {
                
            }
        });
    });
    
    $(".send-cus-msg").click(function(e){
        e.preventDefault();
        var uId = $(this).siblings('.u_id').val();
        var oid22 = $(this).siblings('.oid22').val();
        var description2 = $(this).siblings('.description2');
        
        $.ajax({
            url:'/send-custom-chat',
            type:'POST',
            dataType:"json",
            data: {uid:uId,oid:oid22,message:description2.val()},
            success:function(res)
            {
                getCurrentMsg(uId,oid22);
                description2.val('');
            }
        });
    });
    
    $(".description2").keypress(function(e){
        if(e.which == 13)
        {
            e.preventDefault();
            var uId = $(this).siblings('.u_id').val();
            var oid22 = $(this).siblings('.oid22').val();
            var description2 = $(this);
            
            $.ajax({
                url:'/send-custom-chat',
                type:'POST',
                data: {uid:uId,oid:oid22,message:description2.val()},
                success:function(res)
                {
                    getCurrentMsg(uId,oid22);
                    description2.val('');
                }
            });
        }
    });
    
    function getCurrentMsg(uId,oid22)
    {
        $.ajax({
            url:'/show-chat-center2',
            type:'POST',
            data:{uId:uId,oid22:oid22},
            success:function(res)
            {
                $('.user-id-'+uId).html('');
                $('.user-id-'+uId).html(res);
                $('.user-id-'+uId).animate({ scrollTop: $(document).height() }, 1000);
            }
        });
    }
    
    $('.chat-form').click(function(){
        var oid = $(this).children('form').children('.msb-reply').children('.oid22').val();
        var uid = $(this).children('form').children('.msb-reply').children('.u_id').val();
        
        $.ajax({
            url:'/read-chat',
            type:'POST',
            data:{oid:oid,uid:uid},
            dataType:'json',
            success:function(res){
                
            }
        });
    })
</script>