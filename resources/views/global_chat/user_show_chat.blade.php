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
                <p id="touser" class="font-weight-bold mb-0 fs-16">{{$user2->name}}</p>
                {{-- <span id="loginOrNot">@if($user->is_login == 1) <span class='dot-label bg-success mr-2 w-2 h-2'></span>Online @else <span class='dot-label bg-danger mr-2 w-2 h-2'></span> {{ is_string($user->updated_at) ? $user->updated_at : $user->updated_at->diffForHumans() }}  @endif</span> --}}
            </div>
        </div>
    </div>
@endif
<div class="chat-body-style ChatBody" id="ChatBody">
@if(isset($data[0])) 
    @foreach($data as $val)
        @if($val->recipient_id == 100 || $val->user_id == 100)
            @if($val->user_id == 100)
                <div class="message-feed media right">
                    <div class="media-body">
                        <div class="mf-content">
                            {{$val->content}}
                        </div>
                        <small class="mf-date"><i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}</small>
                    </div>
                </div>
            @else
                <div class="message-feed">
                    <div class="media-body">
                        <div class="mf-content">
                                {{$val->content}}
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