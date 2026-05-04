@include('partials.mainsite_pages.return_function')
<form method="post" action="{{route('update_qa_remarks')}}">
    @csrf
    <div class="card-title font-weight-bold" id="title_qa8">
        Verify The Quote Of OrderId#:{{$id}}
    </div>
    <div class="row">
        <input type="hidden" class="form-control" name="order_id8" value="{{$id}}"
               id='order_id8' placeholder="" readonly>
        <input type="hidden" class="form-control" name="pstatus8" value="{{$pstatus}}"
               id='pstatus8' placeholder="" readonly>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-3">
                    <input type="radio" value="1" name="verify" id="verify" /> <label class="ml-2" for="verify">Verified</label>
                </div>
                <div class="col-sm-3">
                    <input type="radio" checked value="0" name="verify" id="unverify" /> <label class="ml-2" for="unverify">Unverified</label>
                </div>
                <div class="col-sm-6">
                    <input type="checkbox" value="1" name="negative" id="negative" /> <label class="ml-2" for="negative">Mark as Negative</label>
                </div>
            </div>
        </div>
        <div class="col-sm-12" style="display:none;" id="negative_to_user">
            <div class="form-group">
                <label class="form-label">Order Taker/Dispatcher</label>
                <select class="form-control" name="negative_to_user_id" id="negative_to_user_id">
                    <option value="0" @if(isset($data->dispatcher_id)) selected @endif>SELECT</option>
                    @if(isset($data->order_taker_id))
                        <option value="{{$data->order_taker_id}}" @if(empty($data->dispatcher_id)) selected @endif>{{ucfirst(get_user_name($data->order_taker_id))}} Order Taker</option>
                    @else
                        <option value="0">No Order Taker</option>
                    @endif
                    @if(isset($data->dispatcher_id))
                        <option value="{{$data->dispatcher_id}}">{{ucfirst(get_user_name($data->dispatcher_id))}} Dispatcher</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <label class="form-label">No Of Calls</label>
                <input type="number" required name="no_of_calls" id="no_of_calls" class="form-control" />
            </div>
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="form-group">
                <label class="form-label">Decision Based On?</label>
                <input type="text" required name="decision" id="decision" class="form-control" />
            </div>
        </div>
        <div class="col-sm-12 col-md-12">
            <div class="form-group">
                <label class="form-label">Action</label>
                <textarea required name="remarks"
                          id='remarks'
                          class="form-control"></textarea>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Save changes</button>
</form>
<script>
    $("#negative").on('change',function(){
        if($(this).is(":checked"))
        {
            $("#negative_to_user").show();
        }
        else{
            $("#negative_to_user").hide();
        }
    })
</script>