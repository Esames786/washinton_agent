<div>
    <table class="table table-bordered table-striped key-buttons">
        <thead>
            <tr>
                <th class="border-bottom-0">Feedback Date</th>
                <th class="border-bottom-0">Order Id</th>
                <th class="border-bottom-0 w-400">Feedback</th>
                <th class="border-bottom-0">Rate</th>
                <th class="border-bottom-0">Review Email Clicked</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order as $key => $value)
                <tr>
                    <td>{{\Carbon\Carbon::parse($value->updated_at)->format('M, d Y')}}</td>
                    <td>{{$value->id}}</td>
                    @if(isset($value->feedback))
                    <td style="font-size:12px;">
                        {{$value->feedback->feedback}}
                    </td>
                    <td>
                        @if($value->feedback->rate == 1 || $value->feedback->rate == 0)
                            <i class="fa fa-frown-o m-0 text-danger" style="font-size:2rem !important;" aria-hidden="true"></i> 
                            <p style="font-size:1rem !important;" class="text-danger">Negative</p>
                        @elseif($value->feedback->rate == 3 || $value->feedback->rate == 2)
                            <i class="fa fa-meh-o m-0 text-info" style="font-size:2rem !important;" aria-hidden="true"></i> 
                            <p style="font-size:1rem !important;" class="text-info">Neutral</p>
                        @elseif($value->feedback->rate == 5  || $value->feedback->rate == 4)
                            <i class="fa fa-smile-o m-0 text-success" style="font-size:2rem !important;" aria-hidden="true"></i> 
                            <p style="font-size:1rem !important;" class="text-success">Positive</p>
                        @endif
                    </td>
                    @else
                    <td>No feedback added!</td>
                    <td>
                        <i class="fa fa-frown-o m-0 text-warning" style="font-size:2rem !important;" aria-hidden="true"></i> 
                        <p style="font-size:1rem !important;" class="text-warning">No Review!</p>
                    </td>
                    @endif
                    <td>
                        @if(isset($value->email))
                            <span class="badge badge-success text-light">{{$value->email->link_click ?? 0}} click</span>
                        @else
                            <span class="badge badge-danger text-light">No email sent for review!</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-between">
    <div class="text-secondary my-auto">
        Showing {{ $order->firstItem() ?? 0 }} to {{ $order->lastItem() ?? 0 }} from total {{$order->total()}} entries
    </div>
    <div>
        {{$order->links()}}
    </div>
</div>