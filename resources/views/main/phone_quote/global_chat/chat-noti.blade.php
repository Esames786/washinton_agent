@include('partials.mainsite_pages.return_function')
@foreach($getchat as $chatrow)

    <a class="dropdown-item border-bottom" href="{{url('/chats/user/'.$chatrow->fromuserId)}}">
        <div class="d-flex align-items-center">
            <div class="">
                            <span
                                    class="avatar avatar-md brround align-self-center cover-image"
                                    data-image-src="{{ url('assets/images/users/user.jpg')}}"></span>
            </div>
            <div class="d-flex">
                <div class="pl-3">
                    <h6 class="mb-1">{{get_user_name($chatrow->fromuserId)}}:</h6>

                    <p class="fs-13 mb-1">
                        {{$chatrow->description}}
                    </p>

                    <div class="small text-muted">
                        {{\Carbon\Carbon::parse($chatrow->created_at)->format('M,d Y h:i A')}}
                    </div>
                </div>
            </div>
        </div>
    </a>
@endforeach
@foreach($getGroupChat as $chatrow)
    <a class="dropdown-item border-bottom" href="{{url('/chats/group/'.$chatrow->group_id)}}">
        <div class="d-flex align-items-center">
            <div class="">
                @if($chatrow->grouplogo)
                <span
                class="avatar avatar-md brround align-self-center cover-image"
                data-image-src="{{asset('storage/images/group/'.$chatrow->group->logo)}}"></span>
                @else
                <span
                class="avatar avatar-md brround align-self-center cover-image"
                data-image-src="{{asset('images/group-chat.png')}}"></span>
                @endif
            </div>
            <div class="d-flex">
                <div class="pl-3">
                    <h6 class="mb-1">{{$chatrow->group->name}}:</h6>

                    <p class="fs-13 mb-1">
                        {{$chatrow->user->name}}: {{ \Illuminate\Support\Str::words($chatrow->message,3)}}
                    </p>

                    <div class="small text-muted">
                        {{\Carbon\Carbon::parse($chatrow->created_at)->format('M,d Y h:i A')}}
                    </div>
                </div>
            </div>
        </div>
    </a>
@endforeach