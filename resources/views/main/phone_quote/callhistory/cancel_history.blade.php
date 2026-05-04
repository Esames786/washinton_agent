<?php 
    function user_name($id)
    {
        $setting = App\general_setting::first();
        $query = \App\User::where('id', $id)->first();
        if (!empty($query)) {
            if($query->slug)
            {
                return $query->slug;
            }
            else{
                return $query->name.' '.$query->last_name;
            }
        } else {
            return '';
        }
    }
?>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="my-auto">User Remarks</h5>
                <h5 class="my-auto"><span class='badge badge-info get_car_or_heavy txt-white'>On Approval Cancelled</span></h5>
            </div>
            <div class="card-body">
                @if(isset($second_last->id))
                    <h5>Name: <span class="text-primary ml-2">{{user_name($second_last->userId)}}</span></h5>
                    {!! html_entity_decode($second_last->history) !!}
                @else
                    No OnApprovalCancelled!
                @endif
            </div>
        </div>    
    </div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="my-auto">Admin Remarks</h5>
                <h5 class="my-auto"><span class='badge badge-danger txt-white'>Cancel</span>
            </div>
            <div class="card-body">
                @if(isset($last->id))
                    <h5>Name: <span class="text-primary ml-2">{{user_name($last->userId)}}</span></h5>
                    @if(isset($last->agree_disagree))<h5>Remarks: <span class="text-primary ml-2">{{$last->agree_disagree}}</span></h5>@endif
                    @if(isset($last->mistaker))<h5>Mistaker: <span class="text-primary ml-2">{{$last->mistaker}}</span></h5>@endif
                    {!! html_entity_decode($last->history) !!}
                @else
                    No OnApprovalCancelled!
                @endif
            </div>
        </div>    
    </div>
</div>