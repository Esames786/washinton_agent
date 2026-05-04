@include('partials.mainsite_pages.return_function')
@if(isset($data[0]))
    @foreach($data as $val)
        <div class="message-feed media">
            <div class="media-body">
                <div class="mf-content w-100">
                    <h6>User: {{ucfirst(get_user_name($val->user_id))}}</h6>
                    <h6>{!! html_entity_decode(get_pstatus2($val->pstatus)) !!}</h6>
                    <p>
                        <?php echo $val->history ?>
                    </p>
                    <strong class="mf-date"><i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}</strong>
                </div>
    
            </div>
        </div>
    @endforeach
@else
    <div class="message-feed media">
        <div class="media-body">
            <div class="mf-content w-100">
                <h6>No History Update!</h6>
            </div>
        </div>
    </div>
@endif