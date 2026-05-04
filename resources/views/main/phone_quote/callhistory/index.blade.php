@include('partials.mainsite_pages.return_function')
@foreach($data as $val)
    <div class="message-feed media">
        <div class="media-body">
            <div class="mf-content w-100">
                <h6>User: {{ucfirst(get_user_name($val->userId))}}</h6>
                <h6>NEW STATUS: {{get_pstatus($val->pstatus)}}</h6>
                @if(isset($val->mistaker))<h6>Mistaker: {{$val->mistaker}}</h6>@endif
                @if(isset($val->agree_disagree))<h6>Admin Remarks: {{$val->agree_disagree}}</h6>@endif
                @if(isset($val->no_of_calls))<h6>No. Of Calls: {{$val->no_of_calls}} Calls</h6>@endif
                @if(isset($val->decision))<h6>Decision: {{$val->decision}}</h6>@endif
                <?php echo $val->history ?>
                <strong class="mf-date"><i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}</strong>
            </div>

        </div>
    </div>
@endforeach
