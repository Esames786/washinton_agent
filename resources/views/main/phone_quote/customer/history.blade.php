@if(isset($data[0]))
    @foreach($data as $key => $value)
    <div class="mf-content">
        {{$key + 1}}) {{$value->history}}
    </div>
    <small class="mf-date"><i class="fa fa-clock-o"></i>{{\Carbon\Carbon::parse($value->created_at)->format('M, d Y h:i A')}}
    </small>
    @endforeach
@else
    <div class="mf-content">No History Update</div>
@endif