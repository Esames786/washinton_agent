<?php 

    function get_pstatus2($id)
    {
    
        $ret = "";
        if ($id == 0) {
            $ret = "NEW";
        } elseif ($id == 1) {
            $ret = "Interested";
        } elseif ($id == 2) {
            $ret = "FollowMore";
        } elseif ($id == 3) {
            $ret = "AskingLow";
        } elseif ($id == 4) {
            $ret = "NotInterested";
        } elseif ($id == 5) {
            $ret = "NoResponse";
        } elseif ($id == 6) {
            $ret = "TimeQuote";
        } elseif ($id == 7) {
            $ret = "PaymentMissing";
        } elseif ($id == 8) {
            $ret = "Booked";
        } elseif ($id == 9) {
            $ret = "Listed";
        } elseif ($id == 10) {
            $ret = "Dispatch";
        } elseif ($id == 11) {
            $ret = "Pickup";
        } elseif ($id == 12) {
            $ret = "Delivered";
        } elseif ($id == 13) {
            $ret = "Completed";
        } elseif ($id == 14) {
            $ret = "Cancel";
        } elseif ($id == 15) {
            $ret = "Deleted";
        } elseif ($id == 16) {
            $ret = "OwesMoney";
        } elseif ($id == 17) {
            $ret = "CarrierUpdate";
        } elseif ($id == 18) {
            $ret = "OnApproval";
        }elseif ($id == 19) {
            $ret = "On Approval Cancelled";
        }
        return $ret;
    
    }
?>
<div class="table-responsive" style="padding-bottom: 1rem;">
    <table class="table table-bordered table-sm" style="width:100%" role="grid">
        <thead>
            <tr>
                <th class="border-bottom-0">Order Id</th>
                <th class="border-bottom-0">Order Taker Name</th>
                <th class="border-bottom-0">Client Name</th>
                <th class="border-bottom-0">Delivery</th>
                <th class="border-bottom-0">Zip</th>
                <th class="border-bottom-0">Vehicle Name</th>
                <th class="border-bottom-0">Status</th>
                <th class="border-bottom-0">Last Time</th>
            </tr>
        </thead>
        <tbody class="showData">
            @foreach($order as $key => $value)
            <tr>
                <td>
                    <a href="/searchData?search={{$value->id}}">{{$value->id}}</a>
                </td>
                <?php 
                    $name = '';
                    if(isset($value->orderTaker))
                    {
                        $name = $value->orderTaker->slug ? $value->orderTaker->slug : $value->orderTaker->name;
                    }
                ?>
                <td>{{$name}}</td>
                <td>{{$value->oname}}</td>
                <td>{{$value->destinationcity}}</td>
                <td>{{$value->destinationzip}}</td>
                <td>{{$value->ymk}}</td>
                <td>{{get_pstatus2($value->pstatus)}}</td>
                <td>Created At: <br>{{\Carbon\Carbon::parse($value->created_at)->format('M,d Y')}}<br>{{\Carbon\Carbon::parse($value->created_at)->format('h:i A')}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-between">
    <div class="text-secondary my-auto">
        Showing {{ $order->firstItem() ?? 0 }} to {{ $order->lastItem() ?? 0 }} from total {{$order->total()}} entries
    </div>
    <div class="pagi">
        {{$order->links()}}
    </div>
</div>