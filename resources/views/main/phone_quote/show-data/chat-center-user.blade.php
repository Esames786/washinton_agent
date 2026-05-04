<div class="ChatBody p-0 col-lg-3 col-md-4 col-sm-6 col-xs-12 countDiv"
             style="border: 1px solid #94bacb;">
    <div class="py-3 px-2 w-100" style="bottom:0;background:#705ec8;">
        <div class="d-flex justify-content-between">
            <h5 class="text-light my-auto">@if(Auth::user()->role == 1) Dispatchers / Order Takers @elseif(Auth::user()->role == 3) Order Takers @else Dispatchers @endif</h5>
            <div style="font-size:18px;" class="my-auto">
                <button class="btn btn-transparent text-light ml-1 p-0 exit-chat" type="button">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <span class="text-light my-auto">Ask for Order No#{{ isset($id) ? $id : 0 }}</span>
        <input type="hidden" value="{{ isset($id) ? $id : 0 }}" id="oid22" />
    </div>
    <div class="users-dispatchers">
        <ul class="list-group lg-alt">
            @foreach($user as $key => $value)
            <li class="list-group-item media p-4 mt-0 border-0 w-100 custom-chat-user">
                <input type="hidden" value="{{ $value->id }}" class="u_id" />
                <a style="cursor: pointer;" class="d-flex justify-content-between w-100">
                    <div class="media-body">
                        <div class="list-group-item-heading text-default font-weight-semibold">
                            {{$value->slug ?? $value->name.' '.$value->last_name}} @if(Auth::user()->role == 1)<span class="text-secondary" style="opacity:0.6;">({{ $value->role == 3 ? 'Dispatcher' : 'Order Taker' }})</span>@endif
                        </div>
                    </div>
                    <span class="chat-time dot-label bg-{{$value->is_login == 1 ? 'success' : 'danger'}} my-auto"></span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div>

<script>
    $('.exit-chat').click(function(){
        $(this).parents('.ChatBody').remove();
    });
    
    $('.custom-chat-user').click(function(){
        var uId = $(this).children('.u_id').val();
        var oid22 = $('#oid22').val();
        if($('.countDiv').length < 4)
        {
            customChatShow(uId,oid22);
        }
        // $(this).parents('.ChatBody').remove();
        $.ajax({
            url:'/open-chat',
            type:'POST',
            data:{uid:uId,oid:oid22},
            dataType:"json",
            success:function(res)
            {
                
            }
        });
    })
</script>