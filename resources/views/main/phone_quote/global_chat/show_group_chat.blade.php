@if(empty($group))

    <div class="action-header clearfix">
        <a href="#" class="ms-menu-trigger">
            <i class="fe fe-align-left"></i>
        </a>
        <div class="float-left hidden-xs d-flex ml-4 chat-user mt-3">
            <div class="align-items-center mt-1">
                <p id="touser" class="font-weight-bold mb-0 fs-16"></p>
                <span id="loginOrNot">Please Select Group</span>
            </div>
        </div>
    </div>
@else

    <div class="action-header clearfix">
        <a href="#" class="ms-menu-trigger">
            <i class="fe fe-align-left"></i>
        </a>
        <div class="float-left hidden-xs d-flex ml-4 chat-user mt-3">
            <div class="align-items-center mt-1">
                <p id="touser" class="font-weight-bold mb-0 fs-16">{{$group->name}}</p>
            </div>
        </div>
    </div>
    
@endif

<div class="chat-body-style ChatBody" id="ChatBody">
@if(isset($chat[0]))
    @foreach($chat as $val)
        @if($val->user_id == Auth::user()->id)
            <div class="message-feed media">
                <div class="media-body">
                    <span class="text-dark ml-3 font-weight-bold text-capitalize">You</span><br>
                    <div class="mf-content">
                        @if($val->type == 'text')
                        {{$val->message}}
                        @elseif($val->type == 'image')
                        <a href="{{asset('storage/images/group/'.$val->message)}}" target="_blank" style="display:grid;">
                            <img src="{{asset('storage/images/group/'.$val->message)}}" style="width:150px;height:200px;" />
                        </a>
                        @elseif($val->type == 'file')
                        <a href="{{asset('storage/file/group/'.$val->message)}}" target="_blank" style="display:grid;">
                            <img src="{{asset('images/file.png')}}" style="width:150px;height:200px;" />
                            <span class="text-center" style="width:150px;">{{$val->message}}</span>
                        </a>
                        @endif
                    </div>
                    <small class="mf-date"><i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}</small>
                </div>
            </div>
        @else
            <div class="message-feed right ">
                <div class="media-body">
                    <span class="text-dark mr-4 font-weight-bold text-capitalize">{{($val->user->slug) ? $val->user->slug : $val->user->name.' '.$val->user->last_name}}</span><br>
                    <div class="mf-content">
                        @if($val->type == 'text')
                        {{$val->message}}
                        @elseif($val->type == 'image')
                        <a href="{{asset('storage/images/group/'.$val->message)}}" target="_blank" style="display:grid;">
                            <img src="{{asset('storage/images/group/'.$val->message)}}" style="width:150px;height:200px;" />
                        </a>
                        @elseif($val->type == 'file')
                        <a href="{{asset('storage/file/group/'.$val->message)}}" target="_blank" style="display:grid;">
                            <img src="{{asset('images/file.png')}}" style="width:150px;height:200px;" />
                            <span class="text-center" style="width:150px;">{{$val->message}}</span>
                        </a>
                        @endif
                    </div>
                    <small class="mf-date"><i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}</small>
                </div>
            </div>
        @endif
    
    @endforeach
@else
    <div class="message-feed media">
        <div class="media-body">
            <div class="mf-content">
                SELECT GROUP TO START THE CHAT
            </div>
            <small class="mf-date"><i
                        class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse(now())->format('M,d Y h:i A')}}</small>
        </div>
    
    </div>
@endif
<div style="display:none " id="her"></div>