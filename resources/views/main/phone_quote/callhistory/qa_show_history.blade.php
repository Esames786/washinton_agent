@include('partials.mainsite_pages.return_function')
<div class="card-title font-weight-bold m-2" id="title_qa9">
    Action On OrderId#:{{$id}}
</div>
@if(isset($data[0]))
    @foreach($data as $key => $val)
        <div class="message-feed media">
            <div class="media-body">
                <div class="mf-content w-100">
                    <div class="row">
                        <div class="col-lg-7 col-sm-12 mt-2">
                            <h3 style="text-decoration:underline;">QA Action</h3>
                            <h4>
                                @if($val->negative_to_user_id > 0) 
                                    Negative To: {{ucfirst(get_user_name($val->negative_to_user_id))}} 
                                @endif 
                                @if($val->negative == 1) 
                                    <span class="badge badge-danger text-light mx-2">Negative</span> 
                                @endif
                                @if($val->verify == 1) <span class="badge badge-success text-light mr-2">Verified</span> @else <span class="badge badge-danger text-light mr-2">Unverified</span> @endif {!! html_entity_decode(get_pstatus2($val->pstatus)) !!}
                            </h4>
                            <h6>User: {{ucfirst(get_user_name($val->user_id))}}</h6>
                            <h6>No. of Calls: {{$val->no_of_calls}} Calls</h6>
                            <h6>Decision Based On? {{$val->decision}}</h6>
                            <h6>Remarks: <?php echo $val->remarks ?? 'N/A'; ?></h6>
                            <strong class="mf-date"><i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}</strong>
                        </div>
                        <div class="col-lg-5 col-sm-12 mt-2">
                            <h3 style="text-decoration:underline;">Admin Action</h3>
                            @if(isset($val->admin_agree) && isset($val->admin_remarks))
                                <h4>
                                    @if($val->admin_agree == 'Agree') <span class="badge badge-success text-light">Agree</span> @else <span class="badge badge-danger text-light">Disagree</span> @endif 
                                </h4>
                                <h6>Remarks: <?php echo $val->admin_remarks ?? 'N/A'; ?></h6>
                                <strong class="mf-date"><i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($val->updated_at)->format('M,d Y h:i A')}}</strong>
                            @else
                                <form action="{{ route('qa_admin_remarks') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{$val->id}}" id="id{{$key}}" name="id" />
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="radio" value="Agree" id="admin_agree{{$key}}" name="admin_agree" /> <label for="admin_agree{{$key}}" class="ml-2">Agree</label>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="radio" checked value="Disagree" id="admin_disagree{{$key}}" name="admin_agree" /> <label for="admin_disagree{{$key}}" class="ml-2">Disagree</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Admin Action</label>
                                        <textarea required name="admin_remarks" id='admin_remarks{{$key}}' class="form-control"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
    
            </div>
        </div>
    @endforeach
@else
    <h2 class="d-flex justify-content-center align-items-center h-100">No Action!</h2>
@endif