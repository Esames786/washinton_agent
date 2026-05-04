@foreach($chat as $key => $value)
    @if(isset($value->date))
        <p class="bg-secondary text-light text-center" style="width: 50%;border-radius: 30px;padding: 6px 10px;margin: 6px auto 0;">{{$value->date}}</p>
    @endif
    @if($value->user_id == Auth::id())
    <div class="message-feed right media py-0">
        <div class="media-body">
            <div class="mf-content" style="background:#705ec8;">
                {{$value->message}}
            </div>
            <small class="mf-date text-dark"> {{$value->message_time}}
            @if($value->status == 0)
                 <i class="fa fa-check-circle text-light" style="top:0;"></i>
            @elseif($value->status == 1)
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
            <h6>{{ isset($value->user->slug) ? $value->user->slug : $value->user->name.' '.$value->user->last_name }}</h6>
            <div class="mf-content">
                {{$value->message}}
            </div>
            <small class="mf-date text-dark"> {{$value->message_time}}
            </small>
        </div>
    </div>
    @endif
    @if(isset($value->flag))
        @if($value->flag->user_id == $value->user_id)
            <p class="text-danger text-center" style="width: 50%;margin: 6px auto;"><b>@if(Auth::user()->id == $value->flag->user_id) You @else {{$value->flag->user->slug ?? $value->flag->user->name.' '.$value->flag->user->last_name}} @endif got a <i class="fa fa-flag-o" aria-hidden="true"></i> Flag.</b></p>
        @endif
    @endif
@endforeach