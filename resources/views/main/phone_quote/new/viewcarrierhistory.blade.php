@if(isset($data[0]))
@foreach($data as $key => $val)
    <div class="message-feed media m-0">
        <div class="media-body">
            <?php 
                $user = \App\User::where('id',$val->user_id)->select('id','slug','name','last_name')->first();
                $name = '';
                if(isset($user->id))
                {
                    $name = $user->slug ?? $user->name.' '.$user->last_name;
                }
            ?>
            <h4>{{$name}}</h4>
            <div class="mf-content">
                {{$val->history}}
            </div>
            <small class="mf-date"><i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($val->created_at)->format('M, d Y h:i A')}}
            </small>
        </div>
    </div>
@endforeach
@else
    <div class="message-feed media m-0">
        <div class="media-body">
            <h1 class="text-center">No History Update!</h1>
        </div>
    </div>
@endif