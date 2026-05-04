@if(isset($value->chat))
@foreach($value->chat as $key2 => $value2)
    @if(isset($value2->date))
        <p class="bg-secondary text-light text-center" style="width: 50%;border-radius: 30px;padding: 6px 10px;margin: 6px auto 0;">{{$value2->date}}</p>
    @endif
    @if($value2->from_user_id == Auth::id())
    <div class="message-feed right media py-0">
        <div class="media-body">
            <div class="mf-content" style="background:#705ec8;">
                {{$value2->message}}
            </div>
            <small class="mf-date text-dark"> {{$value2->message_time}}
            @if($value2->status == 0)
                 <i class="fa fa-check-circle text-light" style="top:0;"></i>
            @elseif($value2->status == 1)
                 <i class="fa fa-check-circle text-warning" style="top:0;"></i>
            @else
                 <i class="fa fa-check-circle text-success" style="top:0;"></i>
            @endif
            </small>
        </div>
    </div>
    @else
    <div class="message-feed media py-0">
        <div class="media-body">
            <div class="mf-content">
                {{$value2->message}}
            </div>
            <small class="mf-date text-dark"> {{$value2->message_time}}
            </small>
        </div>
    </div>
    @endif
    @if(isset($value2->flag))
        @if($value2->flag->user_id == $value2->from_user_id)
            <p class="text-danger text-center" style="width: 50%;margin: 6px auto;"><b>@if(Auth::user()->id == $value2->flag->user_id) You @else {{$value2->flag->user->slug ?? $value2->flag->user->name.' '.$value2->flag->user->last_name}} @endif got a <i class="fa fa-flag-o" aria-hidden="true"></i> Flag.</b></p>
        @endif
    @endif
@endforeach
@endif