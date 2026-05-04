<div class="ChatBody{{ isset($data->id) ? $data->id : 0 }}public p-0 col-lg-3 col-md-4 col-sm-6 col-xs-12 countDiv"
             style="border: 1px solid #94bacb;">
    <div class="p-2 pb-3 w-100" style="bottom:0;background:#705ec8;">
        <div class="d-flex justify-content-between">
            <h5 class="text-light my-auto"> @if(Auth::user()->role == 1) Dispatchers / Order Takers @elseif(Auth::user()->role == 3) Order Takers @else Dispatchers @endif</h5>
            <div style="font-size:18px;" class="my-auto">
                <button class="btn btn-transparent text-light mr-1 p-0 minimize2" type="button">
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </button>
                <input type="hidden" value="{{ isset($data->id) ? $data->id : 0 }}" class="exit_p_id" />
                <input type="hidden" value="{{ isset($data->order_id) ? $data->order_id : 0 }}" class="exit_o_id" />
                <button class="btn btn-transparent text-light ml-1 p-0 exit-chat2" type="button">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <span class="text-light my-auto">Chat for Order No#{{ isset($data->order_id) ? $data->order_id : 0 }}</span>
    </div>
    <div class="chat-form2">
        <div class="chat-user public-id-{{ isset($data->id) ? $data->id : 0 }} ">
            @include('main.phone_quote.show-data.public-msg')
        </div>
        <form action="" class="form2" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token()}}">
            <div class="msb-reply">
                <input type="hidden" value="{{ isset($data->id) ? $data->id : 0 }}" class="p_id" name="p_id" />
                <textarea class="description3" name="description3"
                          placeholder="Write your message..." style="padding-right:50px;"></textarea>
                <button type="button" class="send-pub-msg"><i class="fa fa-paper-plane-o"></i></button>
            </div>
        </form>
    </div>
</div>

<script>
    $('.minimize2').click(function(){
        var chatForm = $(this).parent('div').parent('div').parent('div').siblings('.chat-form2');
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
    
    $('.exit-chat2').click(function(){
        var exit_p_id = $(this).siblings('.exit_p_id').val();
        var exit_o_id = $(this).siblings('.exit_o_id').val();
        
        $(this).parents('.ChatBody'+exit_p_id+'public').remove();
        $.ajax({
            url:'/exit-public-chat',
            type:'POST',
            data:{pid:exit_p_id,oid:exit_o_id},
            dataType:"json",
            success:function(res)
            {
                
            }
        });
    });
    
    
    function getCurrentPublicMsg(pid)
    {
        $.ajax({
            url:'/public-chat-user2',
            type:'POST',
            data:{pid:pid},
            success:function(res)
            {
                $('.public-id-'+pid).html('');
                $('.public-id-'+pid).html(res);
                $('.public-id-'+pid).animate({ scrollTop: $(document).height() }, 1000);
            }
        });
    }
    
    $(".send-pub-msg").click(function(e){
        e.preventDefault();
        var pid = $(this).siblings('.p_id').val();
        var description3 = $(this).siblings('.description3');
        
        $.ajax({
            url:'/send-public-chat',
            type:'POST',
            dataType:"json",
            data: {pid:pid,message:description3.val()},
            success:function(res)
            {
                getCurrentPublicMsg(pid);
                description3.val('');
            }
        });
    });
    
    $(".description3").keypress(function(e){
        if(e.which == 13)
        {
            e.preventDefault();
            var pid = $(this).siblings('.p_id').val();
            var description3 = $(this);
            
            $.ajax({
                url:'/send-public-chat',
                type:'POST',
                data: {pid:pid,message:description3.val()},
                success:function(res)
                {
                    getCurrentPublicMsg(pid);
                    description3.val('');
                }
            });
        }
    });
    
    $('.chat-form2').click(function(){
        var pid = $(this).children('form').children('.msb-reply').children('.p_id').val();
        
        $.ajax({
            url:'/read-public-chat',
            type:'POST',
            data:{pid:pid},
            dataType:'json',
            success:function(res){
                
            }
        });
    })
</script>