@include('partials.mainsite_pages.return_function')
<div class="row mb-2">
    <div class="col-lg-12 col-12">
        <div class="card">
            <div class="card-body">
                Total Approachings: {{ $countApproachings }}
                <div class="row">
                    @foreach($data as $key => $val)
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 mb-2">
                            <div class="card" style="height:100%;">
                                <div class="card-header">
                                    <h5 class="my-auto">{{get_user_name($val->user_id)}}</h5>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-text"><b>Additional:</b> {{$val->additional}}</h6>
                                    <?php 
                                        $keys = [];
                                        $value = [];
                                        if(isset($val->key))
                                        {
                                            $keys = explode('*^-',$val->key);
                                        }
                                        if(isset($val->value))
                                        {
                                            $value = explode('*^-',$val->value);
                                        }
                                    ?>
                                    @if(isset($keys[0]) && isset($value[0]))
                                        @foreach($keys as $k => $v)
                                            <span class="card-text"><b>{{$v}} {{$k + 1}}: </b> {{$value[$k]}}</span>
                                            <br />
                                        @endforeach
                                    @endif
                                    @if(isset($val->replyer_id))
                                        <div class="card-title">Replyer: {{get_user_name($val->replyer_id)}}</div>
                                        <p class="card-text">Reply: {{$val->reply ?? 'N/A'}}</p>
                                    @else
                                        <form action="{{ url('/request_shipment_reply/'.$val->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="reply{{$val->id}}" class="form-label">Reply</label>
                                                <input class="form-control" id="reply{{$val->id}}" name="reply" placeholder="Reply..." required />
                                            </div>
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var status = "{{ shipment_status($status) }}";
    var id = "{{$order_id}}";
    $("#order_detail_status").html(`Current Status: <h4 style="font-size:16px;" class="my-auto"><span class="badge badge-success text-light">${status}</span></h4>`);
    $("#order_detail_title").html(`<h3 class="text-center mb-0">Order Id# <a href="/searchData?search=${id}" target="_blank" class="text-primary">${id}</a></h3>`);
</script>