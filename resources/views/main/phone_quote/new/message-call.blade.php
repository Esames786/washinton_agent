@if(isset($data[0]))
    @if(isset($data[0]->cname))<h3>Customer Name: {{$data[0]->cname}}</h3>@endif
    @if(isset($data[0]->cphone))<h3>Customer Phone: {{$data[0]->cphone}}</h3>@endif
    @foreach($data as $key => $value)
        @if(isset($value->description))
            <div class="message-feed right" style="margin-left: auto;width: 30%;">
                <div class="media-body">
                    <div class="mf-content" style="background: #705ec8;">
                        {{$value->description}}
                    </div>
                    <small class="mf-date"><i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($value->date_time)->format('M, d Y h:i A')}}
                    </small>
                </div>
            </div>
        @endif
        @if(isset($value->reply))
            <div class="message-feed media">
                <div class="media-body">
                    <div class="mf-content">
                        {{$value->reply}}
                    </div>
                    <small class="mf-date"><i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($value->date_time)->format('M, d Y h:i A')}}
                    </small>
                </div>
            </div>
        @endif
    @endforeach
@else
    <div class="float-left">
        <div class="mf-content">No {{$status == 0 ? 'Messages' : 'Call Logs'}} Update</div>
    </div>
@endif