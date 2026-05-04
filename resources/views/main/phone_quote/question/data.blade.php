<?php

function typeCondition($value, $key)
{
    $tc = [];
    foreach ($value as $val) {
        if ($val == '1') {
            if ($key == 0) {
                $val = 'open';
            } else {
                $val = 'operable';
            }
        } elseif ($val == '2') {
            if ($key == 0) {
                $val = 'enclosed';
            } else {
                $val = 'non-running';
            }
        }
        array_push($tc, $val);
    }
    $all = implode('*^_', $tc);
    return $all;
}

?>
@include('partials.mainsite_pages.return_function')
@php
    $check_panel = check_panel();

    if ($check_panel == 1) {
        $phoneaccess = explode(',', Auth::user()->emp_access_phone);
    } elseif ($check_panel == 3) {
        $phoneaccess = explode(',', Auth::user()->emp_access_test);
    } else {
        $phoneaccess = explode(',', Auth::user()->emp_access_web);
    }
@endphp
<div class="ChatViewMain">
    <div class="row">
        <div class="box boxmain col-12 text-capitalize">
            {{ $route ? ucfirst(str_replace('-', ' ', str_replace('_', ' ', $route))) : 'Show Data' }}</div>
    </div>
    <table>
        <thead>
            <tr>
                <th class="box">#</th>
                <th class="box">Date</th>
                <th class="box">Locations</th>
                <!--<th class="box">Origin</th>-->
                <!--<th class="box">Destination</th>-->
                <th class="box">Prices</th>
                <th class="box">Vechile Detail</th>
                <th class="box">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $value)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        @if ($value->pickup_date)
                            <dd>
                                <b>Pickup:</b>
                                <span>{{ \Carbon\Carbon::parse($value->pickup_date)->format('M,d Y') }}</span>
                            </dd>
                        @endif
                        @if ($value->delivery_date)
                            <dd>
                                <b>Delivery:</b>
                                <span>{{ \Carbon\Carbon::parse($value->delivery_date)->format('M,d Y') }}</span>
                            </dd>
                        @endif
                        <dd>
                            <b>Created:</b>
                            <span>{{ \Carbon\Carbon::parse($value->created_at)->format('M,d Y h:i A') }}</span>
                        </dd>
                        <dd>
                            <b>Modified:</b>
                            <span>{{ \Carbon\Carbon::parse($value->updated_at)->format('M,d Y h:i A') }}</span>
                        </dd>

                    </td>
                    <td>
                        <span class="text-primary"><b>
                                @if ($value->oterminal == 1)
                                    Residence
                                @elseif($value->oterminal == 2)
                                    COPART Auction
                                @elseif($value->oterminal == 3)
                                    Manheim Auction
                                @elseif($value->oterminal == 4)
                                    IAAI Auction
                                @elseif($value->oterminal == 5)
                                    Body Shop
                                @elseif($value->oterminal == 10)
                                    Dealership
                                @elseif($value->oterminal == 7)
                                    Business Location
                                @elseif($value->oterminal == 8)
                                    Auction (Heavy)
                                @elseif($value->dterminal == 6)
                                    Other
                                @endif
                            </b></span>
                        @if ($value->oterminal == 2 || $value->oterminal == 3 || $value->oterminal == 4)
                            @if (isset($value->oauctiondate))
                                <dd><b>Auction Date:
                                    </b><span>{{ \Carbon\Carbon::parse($value->oauctiondate)->format('M, d Y') }}</span>
                                </dd>
                            @endif
                            @if (isset($value->oauctiontime))
                                <dd><b>Auction Time:
                                    </b><span>{{ \Carbon\Carbon::parse($value->oauctiontime)->format('h:i A') }}</span>
                                </dd>
                            @endif
                        @endif
                        <a href="#" style="margin-bottom: 20px;">
                            <b><i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                                <span>{{ $value->originzsc }}</span></b>
                        </a>
                        <!--</td>-->
                        <!--<td>-->
                        <span class="text-primary"><b>
                                @if ($value->dterminal == 1)
                                    Residence
                                @elseif($value->dterminal == 2)
                                    COPART Auction
                                @elseif($value->dterminal == 3)
                                    Manheim Auction
                                @elseif($value->dterminal == 4)
                                    IAAI Auction
                                @elseif($value->dterminal == 5)
                                    Body Shop
                                @elseif($value->dterminal == 11)
                                    Dealership
                                @elseif($value->dterminal == 7)
                                    Port
                                @elseif($value->dterminal == 6)
                                    Airport
                                @elseif($value->dterminal == 9)
                                    Business Location
                                @elseif($value->dterminal == 10)
                                    Auction (Heavy)
                                @elseif($value->dterminal == 8)
                                    Other
                                @endif
                            </b></span>
                        @if ($value->dterminal == 2 || $value->dterminal == 3 || $value->dterminal == 4)
                            @if (isset($value->dauctiondate))
                                <dd><b>Auction Date:
                                    </b><span>{{ \Carbon\Carbon::parse($value->dauctiondate)->format('M, d Y') }}</span>
                                </dd>
                            @endif
                            @if (isset($value->dauctiontime))
                                <dd><b>Auction Time:
                                    </b><span>{{ \Carbon\Carbon::parse($value->dauctiontime)->format('h:i A') }}</span>
                                </dd>
                            @endif
                        @endif
                        <a href="#"> <b><i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                                <span> {{ $value->destinationzsc }}</span></b></a>
                    </td>
                    <td style="width: 200px;">
                        <span class="d-flex justify-content-between">Order Id: <b>{{ $value->id }} </b></span>
                        <span class="d-flex justify-content-between">Start Price: @if ($value->start_price)
                                <b> ${{ $value->start_price }} </b>
                            @else
                                <b>$0</b>
                            @endif
                        </span>
                        @if (Auth::user()->role != 3)
                            <span class="d-flex justify-content-between">Booked Price: @if ($value->payment)
                                    <b> ${{ $value->payment }} </b>
                                @else
                                    <b>$0</b>
                                @endif
                            </span>
                        @endif
                        <span class="d-flex justify-content-between">Driver Price: @if ($value->driver_price)
                                <b> ${{ $value->driver_price }} </b>
                            @else
                                <b>$0</b>
                            @endif
                        </span>
                        <span class="d-flex justify-content-between">Listed Price: @if ($value->listed_price)
                                <b> ${{ $value->listed_price }} </b>
                            @else
                                <b>$0</b>
                            @endif
                        </span>
                        <span class="d-flex justify-content-between">Carrier Price: @if ($value->pay_carrier)
                                <b> ${{ $value->pay_carrier }} </b>
                            @else
                                <b>$0</b>
                            @endif
                        </span>
                        <?php $id = $value->pstatus; ?>
                        <dd>
                            @if ($id == 0)
                                <span class='badge badge-orange txt-white'>New</span>
                            @elseif ($id == 1)
                                <span class='badge badge-warning txt-white'>Interested</span>
                            @elseif ($id == 2)
                                <span class='badge badge-primary txt-white'>FollowMore</span>
                            @elseif ($id == 3)
                                <span class='badge badge-pink txt-white'>AskingLow</span>
                            @elseif ($id == 4)
                                <span class='badge badge-success '>Not Interested</span>
                            @elseif ($id == 5)
                                <span class='badge badge-dark txt-white'>No Response</span>
                            @elseif ($id == 6)
                                <span class='badge badge-amber txt-white'>Time Quote</span>
                            @elseif ($id == 7)
                                <span class='badge badge-primary  txt-white'>Payment Missing</span>
                            @elseif ($id == 8)
                                <span class='badge badge-warning  txt-white'>Booked</span>
                            @elseif ($id == 9)
                                <span class='badge badge-pink txt-white'>Listed</span>
                            @elseif ($id == 10)
                                <span class='badge badge-success'>Schedule</span>
                            @elseif ($id == 11 && $value->approve_pickup == 1)
                                <span class='badge badge-dark txt-white'>Pickedup</span>
                            @elseif ($id == 11 && ($value->approve_pickup == null || $value->approve_pickup == 0))
                                <span class='badge badge-danger txt-white'>Not Pickedup</span>
                            @elseif ($id == 12 && $value->approve_deliver == 1)
                                <span class='badge badge-amber txt-white'>Delivered</span>
                            @elseif ($id == 12 && $value->approve_deliver == 2)
                                <span class='badge badge-amber txt-white'>Schedule For Delivery</span>
                            @elseif ($id == 12 && ($value->approve_deliver == null || $value->approve_deliver == 0))
                                <span class='badge badge-amber txt-white'>Not Delivered</span>
                            @elseif ($id == 13)
                                <span class='badge badge-teal txt-white'>Completed</span>
                            @elseif ($id == 14)
                                <span class='badge badge-danger txt-white'>Cancel</span>
                            @elseif ($id == 15)
                                <span class='badge badge-danger txt-white'>Deleted</span>
                            @elseif ($id == 16)
                                <span class='badge badge-primary txt-white'>OwesMoney</span>
                            @elseif ($id == 17)
                                <span class='badge badge-primary txt-white'>Carrier Update</span>
                            @elseif ($id == 18)
                                <span class='badge badge-primary txt-white'>On Approval</span>
                            @elseif ($id == 19)
                                <span class='badge badge-danger get_car_or_heavy txt-white'>On Approval Canceled</span>
                            @endif
                        </dd>
                        @if (Auth::user()->role != 3)
                            <dd>
                                <span
                                    class="my-1 badge badge-{{ $value->paneltype == 1
                                    ? 'secondary'
                                    : ($value->paneltype == 2
                                    ? 'primary'
                                    : ($value->paneltype == 3
                                    ? 'info'
                                    : ($value->paneltype == 4
                                    ? 'primary'
                                    : ($value->paneltype == 5
                                    ? 'primary'
                                    : ($value->paneltype == 6
                                    ? 'primary'
                                    : 'secondary'))))) }} text-light">
                                    {{ $value->paneltype == 1
                                    ? 'Phone Quote'
                                    : ($value->paneltype == 2
                                    ? 'Website Quote'
                                    : ($value->paneltype == 3
                                    ? 'Testing Quote'
                                    : ($value->paneltype == 4
                                    ? 'Panel Type 4 Quote'
                                    : ($value->paneltype == 5
                                    ? 'Panel Type 5 Quote'
                                    : ($value->paneltype == 6
                                    ? 'Panel Type 6 Quote'
                                    : 'Phone Quote'))))) }}
                                </span>
                            </dd>
                        @endif
                    </td>
                    <td>
                        <div>
                            <?php
                            $ymk1 = explode('*^-', $value->ymk);
                            $ymk2 = explode('*^', $value->ymk);
                            ?>
                            <dd>
                                @if ($ymk1)
                                    @if (count($ymk1) > 1)
                                        <a href="#" title="{{ $value->ymk }}"
                                            class="text-secondary">{{ count($ymk1) }} Vehicles</a>
                                    @else
                                        {{ $value->ymk }}
                                    @endif
                                @elseif($ymk2)
                                    @if (count($ymk2) > 1)
                                        <a href="#" title="{{ $value->ymk }}"
                                            class="text-secondary">{{ count($ymk2) }} Vehicles</a>
                                    @else
                                        {{ $value->ymk }}
                                    @endif
                                @endif
                            </dd>
                        </div>
                        @if ($value->car_type == 2)
                            <dd>
                            <div class="badge bg-info text-light">Heavy</div>@else<div
                                    class="badge bg-warning text-light my-1">Car/Motor</div>
                            </dd>
                        @endif
                        <?php
                        $transport1 = explode('*^-', $value->transport);
                        $transport2 = explode('*^', $value->transport);
                        $condition1 = explode('*^-', $value->condition);
                        $condition2 = explode('*^', $value->condition);
                        
                        ?>
                        @if ($transport2[0] == 1 || $transport2[0] == 2)
                            @if (count($transport2) > 1)
                                <dd><a href="#" title="{{ typeCondition($transport2, 0) }}">
                                        <div class="badge bg-info text-light my-1">{{ count($transport2) }} Types</div>
                                    </a></dd>
                            @else
                                <dd>
                                    <div class="badge bg-info text-light my-1">
                                        @if ($transport2[0] == 1)
                                            open
                                        @elseif($transport2[0] == 2)
                                            enclosed
                                        @endif
                                    </div>
                                </dd>
                            @endif
                        @elseif($transport1[0] == 'open' || $transport1[0] == 'enclosed')
                            @if (count($transport1) > 1)
                                <dd><a href="#" title="{{ $value->transport }}">
                                        <div class="badge bg-info text-light my-1">{{ count($transport1) }} Types</div>
                                    </a></dd>
                            @else
                                <dd>
                                    <div class="badge bg-info text-light my-1">
                                        @if ($transport1[0] == 1)
                                            open
                                        @elseif($transport1[0] == 2)
                                            enclosed
                                        @endif
                                    </div>
                                </dd>
                            @endif
                        @endif
                        @if ($condition2[0] == 1 || $condition2[0] == 2)
                            @if (count($condition2) > 1)
                                <dd><a href="#" title="{{ typeCondition($condition2, 1) }}">
                                        <div class="badge bg-info text-light my-1">{{ count($condition2) }} Conditions
                                        </div>
                                    </a></dd>
                            @else
                                <dd>
                                    <div class="badge bg-info text-light my-1">
                                        @if ($condition2[0] == 1)
                                            operable
                                        @elseif($condition2[0] == 2)
                                            non-running
                                        @endif
                                    </div>
                                </dd>
                            @endif
                        @elseif($condition1[0] == 'operable' || $condition1[0] == 'non-running')
                            @if (count($condition1) > 1)
                                <dd><a href="#" title="{{ $value->condition }}">
                                        <div class="badge bg-info text-light my-1">{{ count($condition1) }} Conditions
                                        </div>
                                    </a></dd>
                            @else
                                <dd>
                                    <div class="badge bg-info text-light my-1">
                                        @if ($condition1[0] == 1)
                                            operable
                                        @elseif($condition1[0] == 2)
                                            non-running
                                        @endif
                                    </div>
                                </dd>
                            @endif
                        @endif
                    </td>
                    <td style="width:200px;">
                        <input type="hidden" value="{{ $value->id }}" class="oID">
                        <!--<dd class="vquest w-75 mx-auto qnaModal">-->
                        <!--    <button type="button" class="btn btn-success w-100" data-toggle="modal"-->
                        <!--        data-target="#viewQuestionModal{{ $value->id }}">View Question</button>-->
                        <!--    <span>{{ count($value->qna) }}</span>-->
                        <!--</dd>-->
                        @if ($value->pstatus >= 9 && $value->pstatus <= 13)
                            @if (
                                $value->oterminal == 2 ||
                                    $value->oterminal == 3 ||
                                    $value->oterminal == 4 ||
                                    $value->oterminal == 8 ||
                                    ($value->dterminal == 2 || $value->dterminal == 3 || $value->dterminal == 4 || $value->dterminal == 10))
                                <?php
                                $count = \App\SheetDetails::where('orderId', $value->id)->count();
                                ?>
                                <dd class="vquest w-100 mx-auto mt-2">
                                    <button type="button" class="btn btn-warning w-100" data-toggle="modal"
                                        data-target="#modalh{{ $value->id }}">View Auction</button>
                                    <span>{{ $count }}</span>
                                </dd>
                                <dd class="vquest w-100 mx-auto updateAuctionBtn mt-2">
                                    <button type="button" class="btn btn-info w-100" data-toggle="modal"
                                        data-target="#modals{{ $value->id }}">Update Auction</button>
                                </dd>
                            @endif
                        @endif

                        @if ($value->pstatus == 5)
                            <dd class="vquest w-100 mx-auto mt-2">
                                <button type="button"
                                    class="btn w-100 btn-{{ count($value->notRespond) > 2 ? 'success' : 'danger' }}"
                                    data-toggle="modal" data-target="#notRespond{{ $value->id }}">Not Responding
                                    View</button>
                                <span>{{ count($value->notRespond) }}</span>
                            </dd>
                        @endif
                        @if (Auth::user()->role < 4 || Auth::user()->role == 9)
                            <dd class="w-100 mx-auto mt-2">
                                <button type="button" class="btn btn-warning w-100"
                                    onclick="customChatUser({{ $value->id }})">Custom Chat</button>
                            </dd>
                            <dd class="w-100 mx-auto mt-2">
                                <button type="button" class="btn btn-info w-100"
                                    onclick="publicChatUser({{ $value->id }})">Public Chat</button>
                            </dd>
                        @endif
                        @if (($value->pstatus >= 7 && $value->pstatus <= 10) || $value->pstatus == 18)
                            @if (in_array('17', $phoneaccess))
                                <dd class="w-100 mx-auto mt-2">
                                    <form style="width: 100%;" action="{{ url('/carrierupdate') }}" method="POST">
                                        @csrf
                                        <input name="orderid" type="hidden" value="{{ $value->id }}" />
                                        <button type="submit" class="btn text-light w-100"
                                            style="background:brown;">Carrier Update</button>
                                    </form>
                                </dd>
                            @endif
                            @if (in_array('53', $phoneaccess))
                                <dd class="w-100 mx-auto mt-2">
                                    <button type="button" data-toggle="modal" data-target="#storagehmodal"
                                        data-book-id="{{ $value->id }}" data-location1="{{ $value->originzsc }}"
                                        data-location2="{{ $value->destinationzsc }}"
                                        class="btn btn-success w-100 storageModal">
                                        Storage Record
                                    </button>
                                </dd>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <div class="text-secondary my-auto">
            Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} from total {{ $data->total() }}
            entries
        </div>
        <div>
            {{ $data->links() }}
        </div>

    </div>
</div>

@if ($data)
    @foreach ($data as $key => $value)
        @if ($value->pstatus == 5)
            <div class="modal" id="notRespond{{ $value->id }}">
                <div class="modal-dialog modal-dialog-centered text-center" role="document" style="max-width: 70%;">
                    <div class="modal-content tx-size-sm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="notRespondLabel{{ $value->id }}">Not Responding Detail
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="tableScroll">
                                <table class="table tableh table-bordered table-hover" style="text-align: center; ">
                                    <thead>
                                        <tr>
                                            <th>Order Id</th>
                                            <th>Origin</th>
                                            <th>Destination</th>
                                            <th>Vehicle</th>
                                            <th>Call By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($value->notRespond))
                                            @foreach ($value->notRespond as $key2 => $value2)
                                                <tr>
                                                    <td>{{ $value2->order_id }}</td>
                                                    <td>{{ $value->originzsc }}</td>
                                                    <td>{{ $value->destinationzsc }}</td>
                                                    <td>{{ $value->ymk }}</td>
                                                    <td>{{ isset($value2->user) ? ($value2->user->slug ? $value2->user->slug : $value2->user->name . ' ' . $value2->user->last_name) : '' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if ($value->pstatus >= 9 && $value->pstatus <= 13)
            <div class="modal" id="modals{{ $value->id }}">
                <div class="modal-dialog modal-dialog-centered text-center" role="document" style="max-width: 70%;">
                    <div class="modal-content tx-size-sm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Update Auction</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <form action="{{ url('/show-data/update-auction') }}" method="POST">
                                @csrf
                                <div class="continer copntainer"></div>
                                <input type="hidden" name="orderId" id="o_id" value="{{ $value->id }}">
                                @if ($value->pstatus == 9)
                                    <input type="hidden" name="url" value="listed">
                                    <h3 class="table-data-align m-2">Listed</h3>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Paid</label>
                                            <select name="paid" id="paid" class="form-control h-50">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Storage</label>
                                            <input class="form-control" id="storage" name="storage"
                                                placeholder="Storage" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Listed Price</label>
                                            <input class="form-control" id="listed_price" placeholder="Listed Price"
                                                name="listed_price" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Auction Update</label>
                                            <input class="form-control" id="auction_update"
                                                placeholder="Auction Update" name="auction_update" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Title</label>
                                            <select id="title_keys" name="title_keys" class="form-control h-50">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Key</label>
                                            <select id="keys" name="keys" class="form-control h-50">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Listed Count</label>
                                            <input class="form-control" id="listed_count" placeholder="Listed Count"
                                                name="listed_count" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Old/New Price</label>
                                            <input class="form-control" id="price" placeholder="Old / New Price"
                                                name="price" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Vehicle Position</label>
                                            <input class="form-control" placeholder="Vehicle Position" id="port"
                                                name="port" value="">
                                        </div>
                                        <div class="col-md-12">
                                            <label>Additional</label>
                                            <input class="form-control" id="additional" placeholder="Additional"
                                                name="additional" value="">
                                        </div>
                                    </div>
                                @endif
                                @if ($value->pstatus == 10)
                                    <input type="hidden" name="url" value="schedule">
                                    <h3 class="table-data-align m-2">Schedule</h3>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Pickedup Time</label>
                                            <input class="form-control" type="datetime-local" id="pickedup"
                                                name="pickedup" placeholder="=PickedUp time" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Delivery Time</label>
                                            <input class="form-control" type="datetime-local" id="delivery_date"
                                                name="delivery_date" placeholder="=Delivery time" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Dispatch Price</label>
                                            <input class="form-control" type="text" id="price" name="price"
                                                placeholder="Dispatch Price" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Vehicle Condition</label>
                                            <input class="form-control" type="text" id="condition"
                                                name="condition" placeholder="Vehicle Condition" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Company Name</label>
                                            <input class="form-control" type="text" id="company_name"
                                                name="company_name" placeholder="Company Name" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Storage</label>
                                            <input class="form-control" id="storage" name="storage"
                                                placeholder="Storage" value="" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Driver FMCSA (Active)?</label>
                                            <select id="driver_fmcsa" name="driver_fmcsa" class="form-control h-50"
                                                required>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Carrier Rating</label>
                                            <input class="form-control" id="carrier_rating" name="carrier_rating"
                                                placeholder="Carrier Rating" value="" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Verify FMCSA?</label>
                                            <input class="form-control" id="fmcsa" name="fmcsa"
                                                placeholder="Verify FMCSA?" value="" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Date Of Insurance (FMCSA)</label>
                                            <input class="form-control" type="date" id="insurance_date"
                                                name="insurance_date" placeholder="Date Of Insurance (FMCSA)"
                                                value="" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>COI Holder</label>
                                            <select id="coi_holder" name="coi_holder" class="form-control h-50"
                                                required>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                                <option value="Waiting">Waiting</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Is Vehicle Luxury?</label>
                                            <select id="vehicle_luxury" name="vehicle_luxury"
                                                class="form-control h-50" required>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Aware Driver Delivery</label>
                                            <input class="form-control" type="text"
                                                id="aware_driver_delivery_date" required
                                                name="aware_driver_delivery_date"
                                                placeholder="=Aware Driver Delivery">
                                        </div>
                                        <div class="col-md-4">
                                            <label>New/Old Driver</label>
                                            <select id="new_old_driver" name="new_old_driver"
                                                class="form-control h-50" required>
                                                <option value="Old Driver">Old Driver</option>
                                                <option value="New Driver">New Driver</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Is Local?</label>
                                            <select id="is_local" name="is_local" class="form-control h-50"
                                                required>
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Job Accept</label>
                                            <input class="form-control" id="job_accept" name="job_accept"
                                                placeholder="Job Accept" value="" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Title</label>
                                            <select id="title_keys" name="title_keys" class="form-control h-50"
                                                required>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Key</label>
                                            <select id="keys" name="keys" class="form-control h-50" required>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Auction Update</label>
                                            <input id="auction_update" name="auction_update" required
                                                class="form-control" placeholder="Auction Update" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Storage Pay</label>
                                            <select id="who_pay_storage" name="who_pay_storage" required
                                                class="form-control h-50">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Vehicle Position</label>
                                            <input id="vehicle_position" name="vehicle_position" required
                                                class="form-control" placeholder="Vehicle Position" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Payment Method</label>
                                            <input class="form-control" id="payment_method" required
                                                name="payment_method" placeholder="Payment Method" value="">
                                        </div>
                                        <div class="col-md-8">
                                            <label>Additional</label>
                                            <input class="form-control" id="additional" required
                                                placeholder="Additional" name="additional" value="">
                                        </div>
                                    </div>
                                @endif
                                @if ($value->pstatus == 11)
                                    <input type="hidden" name="url" value="pickedup">
                                    <h3 class="table-data-align m-2">Picked Up</h3>
                                    <div class="row">
                                        <div class="col-md-12 my-3">
                                            <h4>Auction Status</h4>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Auction Status</label>
                                            <input class="form-control" id="auction_status1" name="status1"
                                                placeholder="Auction Status" value="" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Storage</label>
                                            <input class="form-control" id="storage1" name="storage1"
                                                placeholder="Storage" value="" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Vehicle Condition</label>
                                            <input class="form-control" id="condition1"
                                                placeholder="Vehicle Condition" name="condition1" value=""
                                                required>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Title</label>
                                            <select id="title_keys1" name="title_keys1" class="form-control h-50"
                                                required>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Key</label>
                                            <select id="keys1" name="keys1" class="form-control h-50" required>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Vehicle Position</label>
                                            <input id="vehicle_position1" name="vehicle_position1" required
                                                class="form-control" placeholder="Vehicle Position" value="">
                                        </div>
                                        <div class="col-md-12">
                                            <label>Additional</label>
                                            <input class="form-control" id="additional1" required
                                                placeholder="Additional" name="additional1" value="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 my-3">
                                            <h4>Driver Status</h4>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Driver Status</label>
                                            <input class="form-control" id="driver_status" required
                                                name="driver_status" placeholder="Driver Status" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Driver Name</label>
                                            <input class="form-control" id="carrier_name" required
                                                name="carrier_name" placeholder="Driver Name" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Driver Payment</label>
                                            <input class="form-control" id="driver_payment" required
                                                name="driver_payment" placeholder="Driver Payment" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Driver No1#</label>
                                            <input class="form-control driverphoneno" required id="driver_no"
                                                name="driver_no" placeholder="Driver No1#" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Driver No2#</label>
                                            <input class="form-control driverphoneno" id="driver_no2"
                                                name="driver_no2" placeholder="Driver No2#" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Driver No3#</label>
                                            <input class="form-control driverphoneno" id="driver_no3"
                                                name="driver_no3" placeholder="Driver No3#" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Driver No4#</label>
                                            <input class="form-control driverphoneno" id="driver_no4"
                                                name="driver_no4" placeholder="Driver No4#" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Company Name</label>
                                            <input class="form-control" id="company_name" required
                                                name="company_name" placeholder="Company Name" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Storage</label>
                                            <input class="form-control" id="storage" required name="storage"
                                                placeholder="Storage" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Delivery Datetime</label>
                                            <input class="form-control" id="delivery_date" required
                                                type="datetime-local" placeholder="Delivery Datetime"
                                                name="delivery_date" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Vehicle Condition</label>
                                            <input class="form-control" id="condition" required
                                                placeholder="Vehicle Condition" name="condition" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Vehicle Position</label>
                                            <input id="vehicle_position" name="vehicle_position" required
                                                class="form-control" placeholder="Vehicle Position" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Payment</label>
                                            <select id="payment" class="form-control h-50" name="payment" required>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Payment Charged Or Owes</label>
                                            <input class="form-control" id="payment_charged_or_owes" required
                                                name="payment_charged_or_owes" placeholder="Payment Charged Or Owes"
                                                value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Payment Method</label>
                                            <input class="form-control" id="payment_method" required
                                                name="payment_method" placeholder="Payment Method" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Total Amount (If Owed)</label>
                                            <input class="form-control" id="price" required name="price"
                                                placeholder="Total Amount (If Owed)" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Title</label>
                                            <select id="title_keys" name="title_keys" required
                                                class="form-control h-50">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Key</label>
                                            <select id="keys" name="keys" class="form-control h-50" required>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Dock Receipt (If Port)</label>
                                            <input class="form-control" id="stamp_dock_receipt" required
                                                name="stamp_dock_receipt" placeholder="Dock Receipt (If Port)"
                                                value="">
                                        </div>
                                        <div class="col-md-12">
                                            <label>Additional</label>
                                            <input class="form-control" id="additional" required
                                                placeholder="Additional" name="additional" value="">
                                        </div>
                                    </div>
                                @endif
                                @if ($value->pstatus == 12)
                                    <input type="hidden" name="url" value="delivered">
                                    <h3 class="table-data-align m-2">Delivered</h3>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Driver No1#</label>
                                                    <input class="form-control driverphoneno" required id="driver_no"
                                                        name="driver_no" placeholder="Driver No1#" value="">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Driver No2#</label>
                                                    <input class="form-control driverphoneno" id="driver_no2"
                                                        name="driver_no2" placeholder="Driver No2#" value="">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Driver No3#</label>
                                                    <input class="form-control driverphoneno" id="driver_no3"
                                                        name="driver_no3" placeholder="Driver No3#" value="">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Driver No4#</label>
                                                    <input class="form-control driverphoneno" id="driver_no4"
                                                        name="driver_no4" placeholder="Driver No4#" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Driver Status</label>
                                            <input id="driver_status" name="driver_status" required
                                                class="form-control" placeholder="Driver Status" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Driver Payment Status</label>
                                            <input id="driver_payment_status" name="driver_payment_status" required
                                                class="form-control" placeholder="Driver Payment Status"
                                                value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Vehicle Condition</label>
                                            <input class="form-control" id="condition" required
                                                placeholder="Vehicle Condition" name="condition" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Customer Informed</label>
                                            <input class="form-control" id="customer_informed" required
                                                placeholder="Customer Informed" name="customer_informed"
                                                value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Vehicle Position</label>
                                            <input id="vehicle_position" name="vehicle_position" required
                                                class="form-control" placeholder="Vehicle Position" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Delivery Datetime</label>
                                            <input class="form-control" id="delivery_date" required
                                                type="datetime-local" placeholder="Delivery Datetime"
                                                name="delivery_date" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Storage Pay</label>
                                            <input id="who_pay_storage" name="who_pay_storage" required
                                                class="form-control" placeholder="Who Pay Storage" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Title</label>
                                            <select id="title_keys" name="title_keys" required
                                                class="form-control h-50">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Key</label>
                                            <select id="keys" name="keys" class="form-control h-50" required>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Client & Status</label>
                                            <select id="client_status" name="client_status" class="form-control h-50"
                                                required>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Owes Status</label>
                                            <select id="owes_status" name="owes_status" class="form-control h-50"
                                                required>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Additional</label>
                                            <input class="form-control" id="additional" placeholder="Additional"
                                                name="additional" value="" required>
                                        </div>
                                    </div>
                                @endif
                                @if ($value->pstatus == 13)
                                    <input type="hidden" name="url" value="complete">
                                    <h3 class="table-data-align m-2">Completed</h3>
                                    <div class="row  m-2">
                                        <div class="col-md-3">
                                            <label>Remarks Status</label>
                                            <input class="form-control h-50" id="remarks" required
                                                placeholder="Remarks Status" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Comments</label>
                                            <input class="form-control h-50" id="comments" required
                                                placeholder="Comments" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Satisfied?</label>
                                            <input class="form-control h-50" id="satisfied" required
                                                placeholder="How you Satisfied?" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Review</label>
                                            <select id="review" required class="form-control h-50"
                                                onchange="this.value == 'Yes' ? $('#all_rating').show() : $('#all_rating').hide()">
                                                <option value="" selected disabled>Select</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12" id="all_rating" style="display:none;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <label>Website</label>
                                                    <select id="website" class="form-control h-50"
                                                        onchange="this.value == 'Other' ? $('#other_website').show() : $('#other_website').hide()">
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="BBB">BBB</option>
                                                        <option value="Trust Pilot">Trust Pilot</option>
                                                        <option value="Google">Google</option>
                                                        <option value="Yelp">Yelp</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6" style="display:none;" id="other_website">
                                                    <label>Other Website</label>
                                                    <input class="form-control h-50" id="website_other"
                                                        placeholder="Other Website" value="">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>Rating</label>
                                                    <select id="client_rating" class="form-control h-50">
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Positive">Positive</option>
                                                        <option value="Neutral">Neutral</option>
                                                        <option value="Negative">Negative</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>Website Link</label>
                                                    <input class="form-control h-50" id="website_link"
                                                        placeholder="Website Link" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Additional</label>
                                            <input class="form-control h-50" required id="additional"
                                                placeholder="Additional" value="">
                                        </div>
                                    </div>
                                @endif
                                <div class="btn-show">

                                    <button type="submit" class="bt btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal" id="modalh{{ $value->id }}">
                <div class="modal-dialog modal-dialog-centered text-center" role="document" style="max-width: 70%;">
                    <div class="modal-content tx-size-sm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalhLabel{{ $value->id }}">View Auction</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h3 class="table-data-align m-2">Auction History</h3>
                            <div class="tableScroll">
                                <table class="table tableh table-bordered table-hover" style="text-align: center; ">
                                    <thead>
                                        <tr>
                                            <th>Order Id</th>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>History Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($value->sheet)
                                            @foreach ($value->sheet as $key3 => $value3)
                                                @if ($value3->pstatus == 9)
                                                    <tr>
                                                        <td>
                                                            {{ $value->id }}
                                                        </td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($value3->created_at)->format('M,d Y h:i A') }}
                                                        </td>
                                                        <td>
                                                            @if (isset($value3->user))
                                                                @if ($value3->user->slug)
                                                                    {{ $value3->user->slug }}
                                                                @else
                                                                    {{ $value3->user->name }}
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-primary text-white">Listed</span>
                                                            @if (isset($value3->paid))
                                                                <br>Paid: {{ $value3->paid }}
                                                            @endif
                                                            @if (isset($value3->price))
                                                                <br>Price: {{ $value3->price }}
                                                            @endif
                                                            @if (isset($value3->storage))
                                                                <br>Storage: {{ $value3->storage }}
                                                            @endif
                                                            @if (isset($value3->listed_price))
                                                                <br>Listed Price: {{ $value3->listed_price }}
                                                            @endif
                                                            @if (isset($value3->listed_count))
                                                                <br>Listed Count: {{ $value3->listed_count }}
                                                            @endif
                                                            @if (isset($value3->auction_update))
                                                                <br>Auction Update: {{ $value3->auction_update }}
                                                            @endif
                                                            @if (isset($value3->title_keys))
                                                                <br>Title: {{ $value3->title_keys }}
                                                            @endif
                                                            @if (isset($value3->keys))
                                                                <br>Keys: {{ $value3->keys }}
                                                            @endif
                                                            @if (isset($value3->vehicle_position))
                                                                <br>Vehicle Position: {{ $value3->vehicle_position }}
                                                            @endif
                                                            @if (isset($value3->additional))
                                                                <br>Additional: {{ $value3->additional }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($value3->pstatus == 10)
                                                    <tr>
                                                        <td>
                                                            {{ $value->id }}
                                                        </td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($value3->created_at)->format('M,d Y h:i A') }}
                                                        </td>
                                                        <td>
                                                            @if (isset($value3->user))
                                                                @if ($value3->user->slug)
                                                                    {{ $value3->user->slug }}
                                                                @else
                                                                    {{ $value3->user->name }}
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-danger text-white">Schedule</span>
                                                            @if (isset($value3->storage))
                                                                <br>Storage: {{ $value3->storage }}
                                                            @endif
                                                            @if (isset($value3->who_pay_storage))
                                                                <br>Pay Storage: {{ $value3->who_pay_storage }}
                                                            @endif
                                                            @if (isset($value3->auction_update))
                                                                <br>Auction Update: {{ $value3->auction_update }}
                                                            @endif
                                                            @if (isset($value3->title_keys))
                                                                <br>Title: {{ $value3->title_keys }}
                                                            @endif
                                                            @if (isset($value3->keys))
                                                                <br>Keys: {{ $value3->keys }}
                                                            @endif
                                                            @if (isset($value3->pickup_date))
                                                                <br>Pickup Date:
                                                                {{ \Carbon\Carbon::parse($value3->pickup_date)->format('M,d Y h:i A') }}
                                                            @endif
                                                            @if (isset($value3->delivery_date))
                                                                <br>Delivery Date:
                                                                {{ \Carbon\Carbon::parse($value3->delivery_date)->format('M,d Y h:i A') }}
                                                            @endif
                                                            @if (isset($value3->vehicle_condition))
                                                                <br>Vehicle Condition: {{ $value3->vehicle_condition }}
                                                            @endif
                                                            @if (isset($value3->vehicle_position))
                                                                <br>Vehicle Position: {{ $value3->vehicle_position }}
                                                            @endif
                                                            @if (isset($value3->driver_fmcsa))
                                                                <br>Driver FMCSA: {{ $value3->driver_fmcsa }}
                                                            @endif
                                                            @if (isset($value3->carrier_rating))
                                                                <br>Carrier Rating: {{ $value3->carrier_rating }}
                                                            @endif
                                                            @if (isset($value3->fmcsa))
                                                                <br>FMCSA: {{ $value3->fmcsa }}
                                                            @endif
                                                            @if (isset($value3->coi_holder))
                                                                <br>COI Holder: {{ $value3->coi_holder }}
                                                            @endif
                                                            @if (isset($value3->vehicle_luxury))
                                                                <br>Vehicle Luxury: {{ $value3->vehicle_luxury }}
                                                            @endif
                                                            @if (isset($value3->aware_driver_delivery_date))
                                                                <br>Aware Driver Delivery:
                                                                {{ $value3->aware_driver_delivery_date }}
                                                            @endif
                                                            @if (isset($value3->price))
                                                                <br>Dispatch Price: {{ $value3->price }}
                                                            @endif
                                                            @if (isset($value3->insurance_date))
                                                                <br>Date Of Insurance (FMCSA):
                                                                {{ \Carbon\Carbon::parse($value3->insurance_date)->format('M,d Y') }}
                                                            @endif
                                                            @if (isset($value3->new_old_driver))
                                                                <br>New Or Old Driver: {{ $value3->new_old_driver }}
                                                            @endif
                                                            @if (isset($value3->payment_method))
                                                                <br>Payment Method: {{ $value3->payment_method }}
                                                            @endif
                                                            @if (isset($value3->is_local))
                                                                <br>Is Local?: {{ $value3->is_local }}
                                                            @endif
                                                            @if (isset($value3->job_accept))
                                                                <br>Job Accept: {{ $value3->job_accept }}
                                                            @endif
                                                            @if (isset($value3->additional))
                                                                <br>Additional: {{ $value3->additional }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($value3->pstatus == 11)
                                                    <tr>
                                                        <td>
                                                            {{ $value->id }}
                                                        </td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($value3->created_at)->format('M,d Y h:i A') }}
                                                        </td>
                                                        <td>
                                                            @if (isset($value3->user))
                                                                @if ($value3->user->slug)
                                                                    {{ $value3->user->slug }}
                                                                @else
                                                                    {{ $value3->user->name }}
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-warning text-white">Picked up</span>
                                                            @if (isset($value3->auction_status))
                                                                <br>Auction Status: {{ $value3->auction_status }}
                                                            @endif
                                                            @if (isset($value3->driver_status))
                                                                <br>Driver Status: {{ $value3->driver_status }}
                                                            @endif
                                                            @if (isset($value3->storage))
                                                                <br>Storage: {{ $value3->storage }}
                                                            @endif
                                                            @if (isset($value3->delivery_date))
                                                                <br>Delivery Date:
                                                                {{ \Carbon\Carbon::parse($value3->delivery_date)->format('M,d Y h:i A') }}
                                                            @endif
                                                            @if (isset($value3->vehicle_condition))
                                                                <br>Vehicle Condition:
                                                                {{ $value3->vehicle_condition }}
                                                            @endif
                                                            @if (isset($value3->title_keys))
                                                                <br>Title: {{ $value3->title_keys }}
                                                            @endif
                                                            @if (isset($value3->keys))
                                                                <br>Keys: {{ $value3->keys }}
                                                            @endif
                                                            @if (isset($value3->vehicle_position))
                                                                <br>Vehicle Position: {{ $value3->vehicle_position }}
                                                            @endif
                                                            @if (isset($value3->payment))
                                                                <br>Payment: {{ $value3->payment }}
                                                            @endif
                                                            @if (isset($value3->stamp_dock_receipt))
                                                                <br>Dock Receipt: {{ $value3->stamp_dock_receipt }}
                                                            @endif
                                                            @if (isset($value3->payment_charged_or_owes))
                                                                <br>Payment Charged Or Owes:
                                                                {{ $value3->payment_charged_or_owes }}
                                                            @endif
                                                            @if (isset($value3->payment_method))
                                                                <br>Payment Method: {{ $value3->payment_method }}
                                                            @endif
                                                            @if (isset($value3->price))
                                                                <br>Total Amount (If Owed): {{ $value3->price }}
                                                            @endif
                                                            @if (isset($value3->carrier_name))
                                                                <br>Driver Name: {{ $value3->carrier_name }}
                                                            @endif
                                                            @if (isset($value3->driver_payment))
                                                                <br>Driver Payment: {{ $value3->driver_payment }}
                                                            @endif
                                                            @if (isset($value3->driver_no))
                                                                <br>Driver No#1: {{ $value3->driver_no }}
                                                            @endif
                                                            @if (isset($value3->driver_no2))
                                                                <br>Driver No#2: {{ $value3->driver_no2 }}
                                                            @endif
                                                            @if (isset($value3->driver_no3))
                                                                <br>Driver No#3: {{ $value3->driver_no3 }}
                                                            @endif
                                                            @if (isset($value3->driver_no4))
                                                                <br>Driver No#4: {{ $value3->driver_no4 }}
                                                            @endif
                                                            @if (isset($value3->additional))
                                                                <br>Additional: {{ $value3->additional }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($value3->pstatus == 12)
                                                    <tr>
                                                        <td>
                                                            {{ $value->id }}
                                                        </td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($value3->created_at)->format('M,d Y h:i A') }}
                                                        </td>
                                                        <td>
                                                            @if (isset($value3->user))
                                                                @if ($value3->user->slug)
                                                                    {{ $value3->user->slug }}
                                                                @else
                                                                    {{ $value3->user->name }}
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info text-white">Delivered</span>
                                                            @if (isset($value3->driver_no))
                                                                <br>Driver No#1: {{ $value3->driver_no }}
                                                            @endif
                                                            @if (isset($value3->driver_no2))
                                                                <br>Driver No#2: {{ $value3->driver_no2 }}
                                                            @endif
                                                            @if (isset($value3->driver_no3))
                                                                <br>Driver No#3: {{ $value3->driver_no3 }}
                                                            @endif
                                                            @if (isset($value3->driver_no4))
                                                                <br>Driver No#4: {{ $value3->driver_no4 }}
                                                            @endif
                                                            @if (isset($value3->delivery_date))
                                                                <br>Delivery Date:
                                                                {{ \Carbon\Carbon::parse($value3->delivery_date)->format('M,d Y h:i A') }}
                                                            @endif
                                                            @if (isset($value3->vehicle_position))
                                                                <br>Vehicle Position: {{ $value3->vehicle_position }}
                                                            @endif
                                                            @if (isset($value3->who_pay_storage))
                                                                <br>Pay Storage: {{ $value3->who_pay_storage }}
                                                            @endif
                                                            @if (isset($value3->title_keys))
                                                                <br>Title: {{ $value3->title_keys }}
                                                            @endif
                                                            @if (isset($value3->keys))
                                                                <br>Keys: {{ $value3->keys }}
                                                            @endif
                                                            @if (isset($value3->driver_status))
                                                                <br>Driver Status: {{ $value3->driver_status }}
                                                            @endif
                                                            @if (isset($value3->client_status))
                                                                <br>Client Status: {{ $value3->client_status }}
                                                            @endif
                                                            @if (isset($value3->owes_status))
                                                                <br>Owes Status: {{ $value3->owes_status }}
                                                            @endif
                                                            @if (isset($value3->driver_payment_status))
                                                                <br>Driver Payment Status:
                                                                {{ $value3->driver_payment_status }}
                                                            @endif
                                                            @if (isset($value3->vehicle_condition))
                                                                <br>Vehicle Condition:
                                                                {{ $value3->vehicle_condition }}
                                                            @endif
                                                            @if (isset($value3->customer_informed))
                                                                <br>Customer Informed:
                                                                {{ $value3->customer_informed }}
                                                            @endif
                                                            @if (isset($value3->additional))
                                                                <br>Additional: {{ $value3->additional }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($value3->pstatus == 13)
                                                    <tr>
                                                        <td>
                                                            {{ $value->id }}
                                                        </td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($value3->created_at)->format('M,d Y h:i A') }}
                                                        </td>
                                                        <td>
                                                            @if (isset($value3->user))
                                                                @if ($value3->user->slug)
                                                                    {{ $value3->user->slug }}
                                                                @else
                                                                    {{ $value3->user->name }}
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success text-white">Completed</span>
                                                            @if (isset($value3->comments))
                                                                <br>Comments: {{ $value3->comments }}
                                                            @endif
                                                            @if (isset($value3->satisfied))
                                                                <br>Satisfied: {{ $value3->satisfied }}
                                                            @endif
                                                            @if (isset($value3->remarks))
                                                                <br>Remarks: {{ $value3->remarks }}
                                                            @endif
                                                            @if (isset($value3->review))
                                                                <br>Review: {{ $value3->review }}
                                                            @endif
                                                            @if (isset($value3->client_rating))
                                                                <br>Client Rating: {{ $value3->client_rating }}
                                                            @endif
                                                            @if (isset($value3->website))
                                                                <br>Website: {{ $value3->website }}
                                                            @endif
                                                            @if (isset($value3->website_other))
                                                                <br>Other Website: {{ $value3->website_other }}
                                                            @endif
                                                            @if (isset($value3->website_link))
                                                                <br>Website Link: {{ $value3->website_link }}
                                                            @endif
                                                            @if (isset($value3->additional))
                                                                <br>Additional: {{ $value3->additional }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endif
<script>
    $(".driverphoneno").keypress(function(e) {
        if ($(this).val() == '') {
            $(this).mask("(999) 999-9999");
        }
        var x = e.which || e.keycode;
        if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)) {
            return true;
        } else {
            return false;
        }
    })
</script>
