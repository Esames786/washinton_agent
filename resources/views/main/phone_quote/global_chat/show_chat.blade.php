@if(empty($user))

    <div class="action-header clearfix">
        <a href="#" class="ms-menu-trigger">
            <i class="fe fe-align-left"></i>
        </a>
        <div class="float-left hidden-xs d-flex ml-4 chat-user mt-3">
            <div class="align-items-center mt-1">
                <p id="touser" class="font-weight-bold mb-0 fs-16"></p>
                <span id="loginOrNot">Please Select User</span>
            </div>
        </div>
    </div>
@else

    <div class="action-header clearfix">
        <a href="#" class="ms-menu-trigger">
            <i class="fe fe-align-left"></i>
        </a>
        <div class="float-left hidden-xs d-flex ml-4 chat-user">
            <div class="align-items-center mt-1">
                <p id="touser" class="font-weight-bold mb-0 fs-16">{{$user->slug ?? $user->name.' '.$user->last_name}}</p>
                <span id="loginOrNot">@if($user->is_login == 1) <span class='dot-label bg-success mr-2 w-2 h-2'></span>Online @else <span class='dot-label bg-danger mr-2 w-2 h-2'></span> {{$user->updated_at->diffForHumans()}} @endif</span>
            </div>
        </div>
    </div>
    
@endif
<div class="chat-body-style ChatBody" id="ChatBody">
@if(isset($data[0])) 
    @foreach($data as $val)
        @if($val->touserId == Auth::user()->id || $val->fromuserId == Auth::user()->id)
            @if($val->fromuserId == Auth::user()->id)
                <div class="message-feed media">
                    <div class="media-body">
                        <div class="mf-content">
                        @if($val->type == 'text')
                        {{$val->description}}
                        @elseif($val->type == 'image')
                        <a href="{{asset('storage/images/chat/'.$val->description)}}" target="_blank" style="display:grid;">
                            <img src="{{asset('storage/images/chat/'.$val->description)}}" style="width:150px;height:200px;" />
                        </a>
                        @elseif($val->type == 'file')
                        <a href="{{asset('storage/file/chat/'.$val->description)}}" target="_blank" style="display:grid;">
                            <img src="{{asset('images/file.png')}}" style="width:150px;height:200px;" />
                            <span class="text-center" style="width:150px;">{{$val->description}}</span>
                        </a>
                        @endif
                        </div>
                        <small class="mf-date"><i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}</small>
                    </div>
                </div>
            @else
                <div class="message-feed right ">
                    <div class="media-body">
                        <div class="mf-content">
                        @if($val->type == 'text')
                        {{$val->description}}
                        @elseif($val->type == 'image')
                        <a href="{{asset('storage/images/chat/'.$val->description)}}" target="_blank" style="display:grid;">
                            <img src="{{asset('storage/images/chat/'.$val->description)}}" style="width:150px;height:200px;" />
                        </a>
                        @elseif($val->type == 'file')
                        <a href="{{asset('storage/file/chat/'.$val->description)}}" target="_blank" style="display:grid;">
                            <img src="{{asset('images/file.png')}}" style="width:150px;height:200px;" />
                            <span class="text-center" style="width:150px;">{{$val->description}}</span>
                        </a>
                        @endif
                        </div>
                        <small class="mf-date"><i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}</small>
                    </div>
                </div>
            @endif
        @endif
    
    @endforeach
@else
    <div class="message-feed media">
        <div class="media-body">
            <div class="mf-content">
                SELECT USER TO START THE CHAT
            </div>
            <small class="mf-date"><i
                        class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse(now())->format('M,d Y h:i A')}}</small>
        </div>
    
    </div>
@endif
</div>
<div style="display:none " id="her"></div>