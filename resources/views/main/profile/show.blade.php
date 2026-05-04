@include('partials.mainsite_pages.return_function')
<div class="col-sm-12">
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>Pickup</th>
                <th>Delivery</th>
                <th>Vehicle/Order ID</th>
                <th>Order Price/Phone</th>
                <th>Ship On/Modified</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $value)
                <tr>
                    <td>
                        <a href="https://www.google.com/maps/dir/{{$value->order->originzip}},+USA/"
                           target="_blank" class="table1ancher">
                            <i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                            <span>  {{$value->order->origincity . "-".$value->order->originstate ."-" .$value->order->originzip  }}</span>
                        </a>
                        @if(!empty($value->order->oaddress))
                            <a data-placement="bottom" class="table1ancher" title="{{ $value->order->oaddress }}">
                                <i class="fa fa-location-arrow" style="color:green;" aria-hidden="true"></i>
                                <span>{{ $value->order->oaddress }} </span>
                            </a>
                        @endif
                        <p class="mt-1 mb-0"><b>{{ $value->order->oauction }}</b></p>
                    </td>
                    <td>
                        <a href="https://www.google.com/maps/dir/{{$value->order->destinationzip }},+USA/"
                           target="_blank" class="table1ancher">
                            <i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                           <span> {{$value->order->destinationcity . "-".$value->order->destinationstate ."-" .$value->order->destinationzip  }}</span>
                        </a>
                        @if($value->order->daddress)
                            <a data-placement="bottom" title="{{ $value->order->daddress }}" class="table1ancher">
                                <i class="fa fa-location-arrow" style="color:red;" aria-hidden="true"></i>
                                <span>  {{ $value->order->daddress }} </span>
                            </a>
                        @endif
                        <p class="mt-1 mb-0"><b>{{ $value->order->dauction }}</b></p>
                    </td>
                    <td>
                        <b> Order ID# </b> <a target="_blank" href="/searchData?search={{$value->order->id}}">{{$value->order->id}}</a>
                        <br>
                        <b>Move By:</b><span> {{get_user_name($value->userId)}}</span>
                        <br>
                        <?php $ymk = explode('*^-', $value->order->ymk); ?>
                        @foreach($ymk as $val2)
                            @if($val2)
                                {{$val2}} <br>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        Book Price: @if(!empty($value->order->payment)) ${{$value->order->payment}} @else N/A @endif
                        <br>
                        <span class="text-center pd-2 bd-l mt-2">
                            Previous Status:
                            @if($value->pstatus == 30)
                                <span class='badge badge-dark txt-white'>Pickup Approval</span>
                            @elseif($value->pstatus == 31)
                                <span class='badge badge-amber txt-white'>Deliver Approval</span>
                            @elseif($value->pstatus == 32)
                                <span class='badge badge-amber txt-white'>Schedule for Delivery</span>
                            @else
                                <?php echo get_pstatus2($value->pstatus) ?>
                            @endif
                        </span>
                        <br>
                        <span class="text-center pd-2 bd-l mt-5">
                            Current Status: 
                            @if($value->order->pstatus == 30)
                                <span class='badge badge-dark txt-white'>Pickup Approval</span>
                            @elseif($value->order->pstatus == 31)
                                <span class='badge badge-amber txt-white'>Deliver Approval</span>
                            @elseif($value->order->pstatus == 32)
                                <span class='badge badge-amber txt-white'>Schedule for Delivery</span>
                            @else
                                <?php echo get_pstatus2($value->order->pstatus) ?>
                            @endif
                        </span>
                    </td>
                    <td>
                        Created Date: {{\Carbon\Carbon::parse($value->order->created_at)->format('M,d Y h:i A')}} <br>
                        Modified Date: {{\Carbon\Carbon::parse($value->created_at)->format('M,d Y h:i A')}} <br>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="col-sm-12">
    <div class="row">
        <div class="col-sm-6 text-secondary my-auto">
            Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} from total {{$data->total()}} entries
        </div>
        <div class="col-sm-6 d-flex justify-content-end">
            {{  $data->links() }}
        </div>
    </div>
    
</div>