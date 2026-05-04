@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]", '/');
if (isset($_GET['titlee'])) {
    $respn = $_GET['titlee'];
}
?>
<style>
    .table {
        /*color: rgb(0 0 0);*/
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
    }

    .table>thead>tr>td,
    .table>thead>tr>th {
        font-weight: 400;
        -webkit-transition: all .3s ease;
        font-size: 18px;
        color: rgb(0 0 0);
    }

    .table-data-align {
        display: flex;
        align-items: flex-end;
    }

    .table-btn-style {}

    .bg-white th {
        border: 1px solid #000000 !important;
    }

    .bg-white td {
        border: 1px solid #000000 !important;
    }
</style>
<div class="table-responsive tableResponsiveNew">
    {{-- example1 --}}
    <table class="table table-bordered table-sm col-lg-2 fs-18 text-center pd-2 bd-l" role="grid" aria-describedby="">
        <thead class="table-dark">
            <tr>
                <th width="15%">Pickup</th>
                <th width="15%">Delivery</th>
                <th>Details</th>
                <th width="15%">Price</th>
            </tr>
        </thead>
        <tbody>
            @php
                $check_panel = check_panel();

                if ($check_panel == 1) {
                    $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                } else {
                    $phoneaccess = explode(',', Auth::user()->emp_access_web);
                }
                $actionaccess = explode(',', Auth::user()->emp_access_action);
            @endphp

            @foreach ($data as $key => $val)
                {{-- dd($data[1]->toArray()) --}}
                @if (in_array('113', $phoneaccess) && $val->car_type == 1 && $val->load_type == null)
                    <tr class="parent1{{ $key }}">
                        <td>
                            <input type="hidden" class='order_id' value="{{ $val->id }}">
                            <input type="hidden" class="pstatus" value="{{ $val->pstatus }}">
                            <input type="hidden" class="client_email" value="{{ $val->oemail }}">
                            <input type="hidden" class="client_name" value="{{ $val->oname }}">
                            <input type="hidden" class="client_phone" value="{{ $val->mainPhNum ?? $val->main_ph }}">
                            <input type="hidden" class="location1" value="{{ $val->originzsc }}">
                            <input type="hidden" class="location2" value="{{ $val->destinationzsc }}">
                            <input type="hidden" class="origincity" value="{{ $val->origincity }}">
                            <input type="hidden" class="destinationcity" value="{{ $val->destinationcity }}">
                            <input type="hidden" class="pickup_date" value="{{ $val->pickup_date }}">
                            <input type="hidden" class="delivery_date" value="{{ $val->delivery_date }}">

                            <a href="https://www.google.com/maps/place/{{ $val->originzip }},+USA/" target="_blank"
                                class="table1ancher">
                                <i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                                <span> {{ $val->origincity . '-' . $val->originstate . '-' . $val->originzip }}</span>
                            </a>
                        </td>
                        <td>
                            @if (isset($val->roro))
                                <a href="https://www.google.com/maps/place/{{ $val->destinationzsc }},+USA/"
                                    target="_blank" class="table1ancher">
                                    <i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                                    <span>
                                        {{ $val->destinationzsc }}</span>
                                </a>
                            @else
                                <a href="https://www.google.com/maps/place/{{ $val->destinationzip }},+USA/"
                                    target="_blank" class="table1ancher">
                                    <i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                                    <span>
                                        {{ $val->destinationcity . '-' . $val->destinationstate . '-' . $val->destinationzip }}</span>
                                </a>
                            @endif
                        </td>
                        <?php $ymk = explode('*^-', $val->ymk); ?>
                        <?php
                        $standardized = str_replace('*^-', '*^', $val->ymk);
                        $ymk = explode('*^', $standardized);
                        ?>
                        <td class="table1td">
                            <a href="https://www.google.com/maps/dir/{{ $val->originzip }},+USA/{{ $val->destinationzip }},+USA/"
                                target="_blank" class="table1ancher">
                                <i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                                <span>View Route</span>
                            </a>
                            @if ($val->car_type == 3 && $val->freight)
                                {{ $val->freight->commodity_detail . ',' . $val->freight->commodity_unit }} <br>
                            @else
                                @foreach ($ymk as $val2)
                                    @if ($val2)
                                        {{ $val2 }} <br>
                                    @endif
                                @endforeach
                            @endif
                            <b> Miles: </b> <span>{{ $val->miles > 0 ? $val->miles : 'N/A' }}</span>
                            <br>
                            <b> Order ID# </b> <span><?php echo $val->id; ?></span>
                            <br>
                            <b> Condition </b> <span>{{ $val->condition == '1' ? 'Running' : 'Non-Running' }}</span>
                            <br>
                            <b> Trailer Type </b> <span>{{ $val->transport == '1' ? 'Open' : 'Enclosed' }}</span>
                            <br>
                            @if (in_array('71', $phoneaccess))
                                @if (isset($val->u_id))
                                    <b>Booker:</b> <span>{{ get_user_name($val->u_id) }}</span><br>
                                @endif
                            @endif
                            @if (isset($val->dispatcher_id))
                                <b>Assign To:</b> <span>{{ get_user_name($val->dispatcher_id) }}</span><br>
                            @else
                                @if ($val->pstatus > 8 && $val->pstatus < 15)
                                    <b>Assign To:</b>
                                    @if (in_array('76', $phoneaccess))
                                        <span type="button" class="badge badge-danger rounded" data-toggle="modal"
                                            onclick="$('#assigning_dispatcher_order').val({{ $val->id }})"
                                            data-target="#assignToDispatcher">Not Assigned</span><br>
                                    @else
                                        <span>Not Assigned</span><br>
                                    @endif
                                @endif
                            @endif
                            @if ($val->pstatus == 13 && !empty($val->completed_sheet) && isset($val->completed_sheet[0]))
                                </br>
                                <b>Review:</b> <span>{{ $val->completed_sheet[0]->review }}</span><br>
                            @endif
                            <span> <?php echo get_car_or_heavy($val->car_type); ?> </span>
                            @if (isset($val->roro))
                                <br>
                                <b>{{ $val->roro }}</b><br>
                            @endif
                            @if (isset($val->available_at_auction))
                                <b>Available At Auction: Yes</b><br>
                                <b>{{ $val->link }}</b><br>
                            @endif
                            @if (isset($val->modification))
                                <b>Modified: Yes</b><br>
                                <b>{{ $val->modify_info }}</b><br>
                            @endif
                            @if ($check_panel == 2 && $val->source != null)
                                <b> Source:</b>
                                <span
                                    class="badge <?php echo $val->source == 'DayDispatch' ? 'badge-primary my-2' : 'badge-primary my-2'; ?>">{{ $val->source == 'DayDispatch' ? 'DD' : '-' }}</span>
                            @endif

                            @if (auth()->user()->role == 1)
                                @if ($val->paneltype != 1)
                                    @if (isset($val->ip_address))
                                        <br>
                                        <div>Ip : <b>{{ $val->ip_address }}</b></div>
                                    @endif
                                    @if (isset($val->ipcountry))
                                        <div>Address :
                                            <b>{{ isset($val->ippostal) ? $val->ippostal . ', ' : '' }}{{ isset($val->ipregion) ? $val->ipregion . ', ' : '' }}{{ isset($val->ipcity) ? $val->ipcity . ', ' : '' }}{{ isset($val->ipcountry) ? $val->ipcountry : '' }}</b>
                                            <br>
                                            <b>{{ isset($val->ip_details) ? $val->ip_details . ', ' : '' }}</b>
                                        </div>
                                    @endif
                                @endif
                            @endif
                            <?php
                            $length_ft = explode('*^', str_replace('*^-', '*^', $val->length_ft));
                            $length_in = explode('*^', str_replace('*^-', '*^', $val->length_in));
                            $height_ft = explode('*^', str_replace('*^-', '*^', $val->height_ft));
                            $height_in = explode('*^', str_replace('*^-', '*^', $val->height_in));
                            $width_ft = explode('*^', str_replace('*^-', '*^', $val->width_ft));
                            $width_in = explode('*^', str_replace('*^-', '*^', $val->width_in));
                            $weight = explode('*^', str_replace('*^-', '*^', $val->weight));
                            $ymk = explode('*^', str_replace('*^-', '*^', $val->ymk));
                            $load_method = explode('*^', str_replace('*^-', '*^', $val->load_method));
                            $unload_method = explode('*^', str_replace('*^-', '*^', $val->unload_method));
                            $category = explode('*^', str_replace('*^-', '*^', $val->category));
                            ?>
                            @if (isset($val->length_ft))
                                <br>
                                @foreach ($length_ft as $key => $row)
                                    @if (empty($ymk[$key]))
                                        @php $ymk[$key] = "" @endphp
                                    @endif
                                    @if (empty($length_ft[$key]))
                                        @php $length_ft[$key] = "" @endphp
                                    @endif
                                    @if (empty($length_in[$key]))
                                        @php $length_in[$key] = "" @endphp
                                    @endif
                                    @if (empty($height_ft[$key]))
                                        @php $height_ft[$key] = "" @endphp
                                    @endif
                                    @if (empty($height_in[$key]))
                                        @php $height_in[$key] = "" @endphp
                                    @endif
                                    @if (empty($width_ft[$key]))
                                        @php $width_ft[$key] = "" @endphp
                                    @endif
                                    @if (empty($width_in[$key]))
                                        @php $width_in[$key] = "" @endphp
                                    @endif
                                    @if (empty($weight[$key]))
                                        @php $weight[$key] = "" @endphp
                                    @endif
                                    @if (empty($load_method[$key]))
                                        @php $load_method[$key] = "" @endphp
                                    @endif
                                    @if (empty($unload_method[$key]))
                                        @php $unload_method[$key] = "" @endphp
                                    @endif
                                    @if (empty($category[$key]))
                                        @php $category[$key] = "" @endphp
                                    @endif

                                    <p>
                                        <b>{{ $ymk[$key] }} <a
                                                href="https://www.google.com/search?q={{ urlencode($ymk[$key]) }}"
                                                target="_blank">{{ $ymk[$key] }}</a></b><br>
                                        <b>Length:</b> {{ $length_ft[$key] . 'ft ' . $length_in[$key] }}in<br>
                                        <b>Height:</b> {{ $height_ft[$key] . 'ft ' . $height_in[$key] }}in<br>
                                        <b>Width:</b> {{ $width_ft[$key] . 'ft ' . $width_in[$key] }}in<br>
                                        <b>Weight:</b> {{ $weight[$key] }}lbs<br>
                                        <b>Category:</b> {{ $category[$key] }}<br>
                                    </p>
                                    @if ($val->load_type != null)
                                        <br>
                                        <b>Load Type:</b> {{ $val->load_type }}<br>
                                        <b>Load Method:</b> {{ $load_method[$i] }}<br>
                                        <b>Unload Method:</b> {{ $unload_method[$i] }}<br>
                                    @endif
                                @endforeach
                            @endif
                            {{-- @if ($val->category != null)
                                <br>
                                <b>Category:</b> {{ $category[$i] }}<br>
                            @endif --}}
                            @if ($val->boat_on_trailer == 1)
                                <br>
                                <b>On trailer:</b> Yes<br>
                            @endif
                            @if ($val->trailer_type && $val->trailer_type != null && $val->trailer_type != 0)
                                <br>
                                <b>On trailer:</b> {{ $val->trailer_type }}<br>
                            @endif
                            @if ($val->commodity_detail && $val->commodity_detail != null && $val->commodity_detail != 0)
                                <br>
                                <b>Commodity Detail:</b> {{ $val->commodity_detail }}<br>
                            @endif
                            @if ($val->handling_unit && $val->handling_unit != null && $val->handling_unit != 0)
                                <br>
                                <b>Handling Unit:</b> {{ $val->handling_unit }}<br>
                            @endif
                            @if ($val->commodity_unit && $val->commodity_unit != null && $val->commodity_unit != 0)
                                <br>
                                <b>Commodity Unit:</b> {{ $val->commodity_unit }}<br>
                            @endif
                            @if ($val->trailer_specification && $val->trailer_specification != null && $val->trailer_specification != 0)
                                <br>
                                <b>Trailer Specification:</b> {{ $val->trailer_specification }}<br>
                            @endif
                            {{-- @if ($val->freight != null && $val->freight->frieght_class != null && $val->freight->frieght_class != 0)
                                <br>
                                <b>Frieghtclass:</b> {{ $val->freight->frieght_class }}<br>
                            @endif
                            @if ($val->freight && $val->freight->equipment_type != null && $val->freight->equipment_type != 0)
                                <br>
                                <b>Equipment Type:</b> {{ $val->freight->equipment_type }}<br>
                            @endif
                            @if ($val->freight && $val->freight->pick_up_services != null && $val->freight->pick_up_services != 0)
                                <br>
                                <b>Pickup Services:</b> {{ $val->freight->pick_up_services }}<br>
                            @endif
                            @if ($val->freight && $val->freight->deliver_services != null && $val->freight->deliver_services != 0)
                                <br>
                                <b>Delivery Services:</b> {{ $val->freight->deliver_services }}<br>
                            @endif
                            @if ($val->freight && $val->freight->ex_pickup_date != null && $val->freight->ex_pickup_date != 0)
                                <br>
                                <b>Pickup Date:</b> {{ $val->freight->ex_pickup_date }}<br>
                            @endif
                            @if ($val->freight && $val->freight->ex_pickup_time != null && $val->freight->ex_pickup_time != 0)
                                <br>
                                <b>Pickup Time:</b> {{ $val->freight->ex_pickup_time }}<br>
                            @endif
                            @if ($val->freight && $val->freight->ex_delivery_date != null && $val->freight->ex_delivery_date != 0)
                                <br>
                                <b>Delivery Date:</b> {{ $val->freight->ex_delivery_date }}<br>
                            @endif
                            @if ($val->freight && $val->freight->ex_delivery_time != null && $val->freight->ex_delivery_time != 0)
                                <br>
                                <b>Delivery Time:</b> {{ $val->freight->ex_delivery_time }}<br>
                            @endif
                            @if ($val->freight && $val->freight->hazardous == 1)
                                <br>
                                <b>Hazardous:</b> Yes<br>
                            @endif
                            @if ($val->freight && $val->freight->stackable == 1)
                                <br>
                                <b>Stackable:</b> Yes<br>
                            @endif
                            @if ($val->freight && $val->freight->protect_from_freezing == 1)
                                <br>
                                <b>Protect From Freezing:</b> Yes<br>
                            @endif
                            @if ($val->freight && $val->freight->sort_segregate == 1)
                                <br>
                                <b>Sort Segregate:</b> Yes<br>
                            @endif
                            @if ($val->freight && $val->freight->blind_shipment == 1)
                                <br>
                                <b>Blind Shipment:</b> Yes<br>
                            @endif --}}
                        </td>
                        <td class="table1td">
                            <form class="addPriceForm" action="{{ route('price_giver.give.price') }}"
                                data-orderid="{{ $val->id }}" method="POST">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $val->id }}">
                                <input type="number" class="form-control" name="given_price" min="0"
                                    placeholder="Price1" required>
                                {{-- <input type="number" class="form-control" name="given_price2" min="0" placeholder="Price2" required> --}}
                                <button type="submit" class="btn btn-primary submitBtn">Add</button>
                            </form>
                        </td>
                    </tr>
                    <tr class="child1{{ $key }}" style="display:none">
                        <td colspan="7">
                            <table class="table table-bordered table-striped bg-white mt-3 mb-4">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th></th>
                                        <th></th>
                                        <th>Storage</th>
                                        <th>ADDITIONAL</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($val->pstatus >= 9 && $val->pstatus <= 13)
                                        @if ($val->listed_sheet)
                                            @foreach ($val->listed_sheet as $key => $value)
                                                <tr>
                                                    <td>Listed</td>
                                                    <td>Listed Price : {{ $value->listed_price }}</td>
                                                    <td>Price : {{ $value->price }}</td>
                                                    <td>{{ $value->storage }}</td>
                                                    <td>{{ $value->additional }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    @if ($val->pstatus >= 10 && $val->pstatus <= 13)
                                        @if ($val->dispatch_sheet)
                                            @foreach ($val->dispatch_sheet as $key => $value)
                                                <tr>
                                                    <td>Schedule</td>
                                                    <td>Pickup Date : {{ $value->pickup_date }}</td>
                                                    <td>Condition : {{ $value->vehicle_condition }}</td>
                                                    <td>{{ $value->storage }}</td>
                                                    <td>{{ $value->additional }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    @if ($val->pstatus >= 11 && $val->pstatus <= 13)
                                        @if ($val->pickedup_sheet)
                                            @foreach ($val->pickedup_sheet as $key => $value)
                                                <tr>
                                                    <td>Picked Up</td>
                                                    <td>Delivery Date : {{ $value->delivery_date }}</td>
                                                    <td>Condition : {{ $value->vehicle_condition }}</td>
                                                    <td>{{ $value->storage }}</td>
                                                    <td>{{ $value->additional }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    @if ($val->pstatus >= 12 && $val->pstatus <= 13)
                                        @if ($val->delivery_sheet)
                                            @foreach ($val->delivery_sheet as $key => $value)
                                                <tr>
                                                    <td>Delivery</td>
                                                    <td>Delivery Date : {{ $value->delivery_date }}</td>
                                                    <td>Position : {{ $value->vehicle_position }}</td>
                                                    <td>{{ $value->driver_no }}</td>
                                                    <td>{{ $value->additional }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    @if ($val->pstatus == 13)
                                        @if ($val->delivery_sheet)
                                            @foreach ($val->delivery_sheet as $key => $value)
                                                <tr>
                                                    <td>Completed</td>
                                                    <td>Remarks :
                                                        {{ $value->remarks . '(' . $value->client_rating . ')' }}</td>
                                                    <td>Comments : {{ $value->comments }}</td>
                                                    <td>Satisfied : {{ $value->satisfied }}</td>
                                                    <td>{{ $value->additional }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                            <form action="send_{{ $val->pstatus }}">
                                <div class="continer copntainer"></div>
                                <input type="hidden" name="orderId" id="orderId_{{ $val->pstatus . $val->id }}"
                                    value="{{ $val->id }}">
                                @if ($val->pstatus == 9)
                                    <h3 class="table-data-align m-2">Listed</h3>
                                    <hr style="margin: 0;">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>Paid</label>
                                            <select name="paid" id="paid_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage</label>
                                            <input class="form-control" id="storage_{{ $val->pstatus . $val->id }}"
                                                name="storage" placeholder="Storage" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Listed Price</label>
                                            <input class="form-control"
                                                id="listed_price_{{ $val->pstatus . $val->id }}"
                                                placeholder="Listed Price" name="listed_price" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Auction Update</label>
                                            <input class="form-control"
                                                id="auction_update_{{ $val->pstatus . $val->id }}"
                                                placeholder="Auction Update" name="Auction Update" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Title</label>
                                            <select id="title_keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Key</label>
                                            <select id="keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Listed Count</label>
                                            <input class="form-control"
                                                id="listed_count_{{ $val->pstatus . $val->id }}"
                                                placeholder="Listed Count" name="listed_count" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Old/New Price</label>
                                            <input class="form-control" id="price_{{ $val->pstatus . $val->id }}"
                                                placeholder="Old / New Price" name="old-new/price" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Condition</label>
                                            <input class="form-control" placeholder="Vehicle Condition"
                                                id="condition_{{ $val->pstatus . $val->id }}" value="">
                                        </div>
                                        <div class="col-md-5">
                                            <label>Additional</label>
                                            <input class="form-control"
                                                id="additional_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button type="button"
                                                onclick="listedUpload({{ $val->pstatus . $val->id }})"
                                                class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>
                                    </div>
                                @endif
                                @if ($val->pstatus == 10)
                                    <h3 class="table-data-align m-2">Schedule</h3>
                                    <hr style="margin: 0;">
                                    <div class="row m-2">
                                        <div class="col-md-3">
                                            <label>Pickedup Time</label>
                                            <input class="form-control" type="datetime-local"
                                                id="pickedup_{{ $val->pstatus . $val->id }}"
                                                placeholder="=PickedUp time">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Delivery Time</label>
                                            <input class="form-control" type="datetime-local"
                                                id="delivery_date_{{ $val->pstatus . $val->id }}"
                                                placeholder="=Delivery time">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Dispatch Price</label>
                                            <input class="form-control" type="text"
                                                id="price_{{ $val->pstatus . $val->id }}"
                                                placeholder="Dispatch Price" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Condition</label>
                                            <input class="form-control" type="text"
                                                id="condition_{{ $val->pstatus . $val->id }}"
                                                placeholder="Vehicle Condition">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Company Name</label>
                                            <input class="form-control" type="text"
                                                id="company_name_{{ $val->pstatus . $val->id }}"
                                                placeholder="Company Name">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage </label>
                                            <input class="form-control" id="storage_{{ $val->pstatus . $val->id }}"
                                                placeholder="Storage" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Carrier Rating </label>
                                            <input class="form-control"
                                                id="carrier_rating_{{ $val->pstatus . $val->id }}"
                                                placeholder="Carrier Rating" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Aware Driver Delivery </label>
                                            <input class="form-control" type="text"
                                                id="aware_driver_delivery_date_{{ $val->pstatus . $val->id }}"
                                                placeholder="Aware Driver Delivery">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver FMCSA (Active)?</label>
                                            <select id="driver_fmcsa_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Verify FMCSA? </label>
                                            <input class="form-control" id="fmcsa_{{ $val->pstatus . $val->id }}"
                                                placeholder="FMCSA" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Date Of Insurance (FMCSA)</label>
                                            <input class="form-control" type="date"
                                                id="insurance_date_{{ $val->pstatus . $val->id }}"
                                                placeholder="Date Of Insurance (FMCSA)" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>COI Holder</label>
                                            <select id="coi_holder_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                                <option value="Waiting">Waiting</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Is Vehicle Luxury?</label>
                                            <select id="vehicle_luxury_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>New/Old Driver</label>
                                            <select id="new_old_driver_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Old Driver">Old Driver</option>
                                                <option value="New Driver">New Driver</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Is Local?</label>
                                            <select id="is_local_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Job Accept </label>
                                            <input class="form-control"
                                                id="job_accept_{{ $val->pstatus . $val->id }}"
                                                placeholder="Job Accept" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Title</label>
                                            <select id="title_keys_{{ $val->pstatus . $val->id }}" name="key"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Key</label>
                                            <select id="keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Auction Update</label>
                                            <input id="auction_update_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Auction Update" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage Pay</label>
                                            <select id="who_pay_storage_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Vehicle Position</label>
                                            <input id="vehicle_position_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Vehicle Position" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Payment Method</label>
                                            <input class="form-control"
                                                id="payment_method_{{ $val->pstatus . $val->id }}"
                                                placeholder="Payment Method" value="">
                                        </div>
                                        <div class="col-md-11">
                                            <label>Additional</label>
                                            <input class="form-control"
                                                id="additional_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button onclick="dispatchUpload({{ $val->pstatus . $val->id }})"
                                                type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>

                                    </div>
                                @endif
                                @if ($val->pstatus == 11)
                                    <h3 class="table-data-align m-2">Picked Up</h3>
                                    <hr style="margin: 0;">
                                    <div class="row m-2">
                                        <div class="col-md-12">
                                            <h4>Auction Status</h4>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Auction Status</label>
                                            <input class="form-control"
                                                id="auction_status1_{{ $val->pstatus . $val->id }}"
                                                placeholder="Auction Status" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage </label>
                                            <input class="form-control" id="storage1_{{ $val->pstatus . $val->id }}"
                                                placeholder="Storage" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Condition </label>
                                            <input class="form-control"
                                                id="condition1_{{ $val->pstatus . $val->id }}"
                                                placeholder="Vehicle Condition" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Title</label>
                                            <select id="title_keys1_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Key</label>
                                            <select id="keys1_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Position </label>
                                            <input id="vehicle_position1_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Vehicle Position" value="">
                                        </div>
                                        <div class="col-md-11">
                                            <label>Additional</label>
                                            <input class="form-control"
                                                id="additional1_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" name="additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button onclick="auctionpickedUpUpload({{ $val->pstatus . $val->id }})"
                                                type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>
                                    </div>
                                    <div class="row m-2">
                                        <div class="col-md-12">
                                            <h4>Driver Status</h4>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver Status </label>
                                            <input class="form-control"
                                                id="driver_status_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver Status" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver Name </label>
                                            <input class="form-control"
                                                id="carrier_name_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver Name" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver Payment </label>
                                            <input class="form-control"
                                                id="driver_payment_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver Payment" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No1# </label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No1#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No2#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no2_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No2#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No3#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no3_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No3#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No4#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no4_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No4#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Company Name </label>
                                            <input class="form-control"
                                                id="company_name_{{ $val->pstatus . $val->id }}"
                                                placeholder="Company Name" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage </label>
                                            <input class="form-control" id="storage_{{ $val->pstatus . $val->id }}"
                                                placeholder="Storage" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Delivery Datetime </label>
                                            <input class="form-control"
                                                id="delivery_date_{{ $val->pstatus . $val->id }}"
                                                type="datetime-local" placeholder="Delivery Datetime" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Condition </label>
                                            <input class="form-control"
                                                id="condition_{{ $val->pstatus . $val->id }}"
                                                placeholder="Vehicle Condition" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Position </label>
                                            <input id="vehicle_position_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Vehicle Position" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Title</label>
                                            <select id="title_keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Key</label>
                                            <select id="keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Payment</label>
                                            <select id="payment_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Payment Charged Or Owes </label>
                                            <input class="form-control"
                                                id="payment_charged_or_owes_{{ $val->pstatus . $val->id }}"
                                                placeholder="Payment Charged Or Owes" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Payment Method </label>
                                            <input class="form-control"
                                                id="payment_method_{{ $val->pstatus . $val->id }}"
                                                placeholder="Payment Method" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Total Amount (If Owed) </label>
                                            <input class="form-control" id="price_{{ $val->pstatus . $val->id }}"
                                                placeholder="Total Amount (If Owed)" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Dock Receipt (If Port)</label>
                                            <input class="form-control"
                                                id="stamp_dock_receipt_{{ $val->pstatus . $val->id }}"
                                                placeholder="Dock Receipt (If Port)" value="">
                                        </div>
                                        <div class="col-md-5">
                                            <label>Additional</label>
                                            <input class="form-control"
                                                id="additional_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" name="additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button onclick="pickedUpUpload({{ $val->pstatus . $val->id }})"
                                                type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>
                                    </div>
                                @endif
                                @if ($val->pstatus == 12)
                                    <h3 class="table-data-align m-2">Delivery</h3>
                                    <hr style="margin: 0;">
                                    <div class="row m-2">
                                        <div class="col-md-2">
                                            <label>Driver No1#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No1#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No2#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no2_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No2#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No3#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no3_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No3#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No4#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no4_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No4#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver Status</label>
                                            <input id="driver_status_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Driver Status" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver Payment Status</label>
                                            <input id="driver_payment_status_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Driver Payment Status"
                                                value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Condition</label>
                                            <input class="form-control"
                                                id="condition_{{ $val->pstatus . $val->id }}"
                                                placeholder="Vehicle Condition" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Customer Informed</label>
                                            <input class="form-control"
                                                id="customer_informed_{{ $val->pstatus . $val->id }}"
                                                placeholder="Customer Informed" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Position</label>
                                            <input id="vehicle_position_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Vehicle Position" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Delivery Datetime</label>
                                            <input class="form-control"
                                                id="delivery_date_{{ $val->pstatus . $val->id }}"
                                                type="datetime-local" placeholder="Delivery Datetime" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage Pay</label>
                                            <input id="who_pay_storage_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Who Pay Storage" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Title</label>
                                            <select id="title_keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Key</label>
                                            <select id="keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Client & Status</label>
                                            <select id="client_status_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Owes Status</label>
                                            <select id="owes_status_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label>Additional</label>
                                            <input class="form-control"
                                                id="additional_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button onclick="deliveryUpload({{ $val->pstatus . $val->id }})"
                                                type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>
                                    </div>
                                @endif
                                @if ($val->pstatus == 13)
                                    <h3 class="table-data-align m-2">Completed</h3>
                                    <hr style="margin:0;">
                                    <div class="row  m-2">
                                        <div class="col-md-3">
                                            <label>Remarks Status</label>
                                            <input class="form-control h-50"
                                                id="remarks_{{ $val->pstatus . $val->id }}"
                                                placeholder="Remarks Status" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Comments</label>
                                            <input class="form-control h-50"
                                                id="comments_{{ $val->pstatus . $val->id }}" placeholder="Comments"
                                                value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Satisfied?</label>
                                            <input class="form-control h-50"
                                                id="satisfied_{{ $val->pstatus . $val->id }}"
                                                placeholder="How you Satisfied?" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Review</label>
                                            <select id="review_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50"
                                                onchange="this.value == 'Yes' ? $('#all_rating').show() : $('#all_rating').hide()">
                                                <option value="" selected disabled>Select</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12" id="all_rating" style="display:none;">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <label>Website</label>
                                                    <select id="website_{{ $val->pstatus . $val->id }}"
                                                        class="form-control h-50"
                                                        onchange="this.value == 'Other' ? $('#other_website').show() : $('#other_website').hide()">
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="BBB">BBB</option>
                                                        <option value="Trust Pilot">Trust Pilot</option>
                                                        <option value="Google">Google</option>
                                                        <option value="Yelp">Yelp</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3" style="display:none;" id="other_website">
                                                    <label>Other Website</label>
                                                    <input class="form-control h-50"
                                                        id="website_other_{{ $val->pstatus . $val->id }}"
                                                        placeholder="Other Website" value="">
                                                </div>
                                                <div class="col-sm-3">
                                                    <label>Rating</label>
                                                    <select id="client_rating_{{ $val->pstatus . $val->id }}"
                                                        class="form-control h-50">
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Positive">Positive</option>
                                                        <option value="Neutral">Neutral</option>
                                                        <option value="Negative">Negative</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label>Website Link</label>
                                                    <input class="form-control h-50"
                                                        id="website_link_{{ $val->pstatus . $val->id }}"
                                                        placeholder="Website Link" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-11">
                                            <label>Additional</label>
                                            <input class="form-control h-50"
                                                id="additional_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button onclick="completedUpload({{ $val->pstatus . $val->id }})"
                                                type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        </td>

                    </tr>
                @elseif (in_array('114', $phoneaccess) && $val->freight == null && $val->load_type != null)
                    <tr class="parent1{{ $key }}">
                        <td>
                            <input type="hidden" class='order_id' value="{{ $val->id }}">
                            <input type="hidden" class="pstatus" value="{{ $val->pstatus }}">
                            <input type="hidden" class="client_email" value="{{ $val->oemail }}">
                            <input type="hidden" class="client_name" value="{{ $val->oname }}">
                            <input type="hidden" class="client_phone"
                                value="{{ $val->mainPhNum ?? $val->main_ph }}">
                            <input type="hidden" class="location1" value="{{ $val->originzsc }}">
                            <input type="hidden" class="location2" value="{{ $val->destinationzsc }}">
                            <input type="hidden" class="origincity" value="{{ $val->origincity }}">
                            <input type="hidden" class="destinationcity" value="{{ $val->destinationcity }}">
                            <input type="hidden" class="pickup_date" value="{{ $val->pickup_date }}">
                            <input type="hidden" class="delivery_date" value="{{ $val->delivery_date }}">



                            <a href="https://www.google.com/maps/place/{{ $val->originzip }},+USA/" target="_blank"
                                class="table1ancher">
                                <i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                                <span> {{ $val->origincity . '-' . $val->originstate . '-' . $val->originzip }}</span>
                            </a>
                        </td>
                        <td>
                            <a href="https://www.google.com/maps/place/{{ $val->destinationzip }},+USA/"
                                target="_blank" class="table1ancher">
                                <i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                                <span>
                                    {{ $val->destinationcity . '-' . $val->destinationstate . '-' . $val->destinationzip }}</span>
                            </a>
                        </td>
                        <?php
                        // $ymk = explode('*^-', $val->ymk);
                        ?>
                        <?php
                        // $standardized = str_replace('*^-', '*^', $val->ymk);
                        // $ymk = explode('*^', $standardized);
                        // dd($val->ymk, $standardized, $ymk);
                        ?>
                        <td class="table1td">
                            <a href="https://www.google.com/maps/dir/{{ $val->originzip }},+USA/{{ $val->destinationzip }},+USA/"
                                target="_blank" class="table1ancher">
                                <i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                                <span>View Route</span>
                            </a>
                            {{-- @foreach ($ymk as $val2)
                                @if ($val2)
                                    {{ $val2 }} <a href="https://www.google.com/search?q={{ urlencode($val2) }}" target="_blank">{{ $val2 }}</a><br>
                                @endif
                            @endforeach --}}
                            <b> Miles: </b> <span>{{ $val->miles > 0 ? $val->miles : 'N/A' }}</span>
                            <br>
                            <b> Order ID# </b> <span><?php echo $val->id; ?></span>
                            <br>
                            <b> Condition </b> <span>{{ $val->condition == '1' ? 'Running' : 'Non-Running' }}</span>
                            <br>
                            <b> Trailer Type </b> <span>{{ $val->transport == '1' ? 'Open' : 'Enclosed' }}</span>
                            <br>
                            @if (in_array('71', $phoneaccess))
                                @if (isset($val->u_id))
                                    <b>Booker:</b> <span>{{ get_user_name($val->u_id) }}</span><br>
                                @endif
                            @endif
                            @if (isset($val->dispatcher_id))
                                <b>Assign To:</b> <span>{{ get_user_name($val->dispatcher_id) }}</span><br>
                            @else
                                @if ($val->pstatus > 8 && $val->pstatus < 15)
                                    <b>Assign To:</b>
                                    @if (in_array('76', $phoneaccess))
                                        <span type="button" class="badge badge-danger rounded" data-toggle="modal"
                                            onclick="$('#assigning_dispatcher_order').val({{ $val->id }})"
                                            data-target="#assignToDispatcher">Not Assigned</span><br>
                                    @else
                                        <span>Not Assigned</span><br>
                                    @endif
                                @endif
                            @endif
                            @if ($val->pstatus == 13 && !empty($val->completed_sheet) && isset($val->completed_sheet[0]))
                                </br>
                                <b>Review:</b> <span>{{ $val->completed_sheet[0]->review }}</span><br>
                            @endif
                            <span> <?php echo get_car_or_heavy($val->car_type); ?> </span>
                            @if (isset($val->roro))
                                <br>
                                <b>{{ $val->roro }}</b><br>
                            @endif
                            @if (isset($val->available_at_auction))
                                <b>{{ $val->available_at_auction }}</b><br>
                                <b>{{ $val->link }}</b><br>
                            @endif
                            @if (isset($val->modification))
                                <b>Modified: Yes</b><br>
                                <b>{{ $val->modify_info }}</b><br>
                            @endif
                            @if ($check_panel == 2 && $val->source != null)
                                <b> Source:</b>
                                <span
                                    class="badge <?php echo $val->source == 'DayDispatch' ? 'badge-primary my-2' : 'badge-primary my-2'; ?>">{{ $val->source == 'DayDispatch' ? 'DD' : '-' }}</span>
                            @endif

                            @if (auth()->user()->role == 1)
                                @if ($val->paneltype != 1)
                                    @if (isset($val->ip_address))
                                        <br>
                                        <div>Ip : <b>{{ $val->ip_address }}</b></div>
                                    @endif
                                    @if (isset($val->ipcountry))
                                        <div>Address :
                                            <b>{{ isset($val->ippostal) ? $val->ippostal . ', ' : '' }}{{ isset($val->ipregion) ? $val->ipregion . ', ' : '' }}{{ isset($val->ipcity) ? $val->ipcity . ', ' : '' }}{{ isset($val->ipcountry) ? $val->ipcountry : '' }}</b>
                                            <br>
                                            <b>{{ isset($val->ip_details) ? $val->ip_details . ', ' : '' }}</b>
                                        </div>
                                    @endif
                                @endif
                            @endif
                            <?php
                            $length_ft = explode('*^', str_replace('*^-', '*^', $val->length_ft));
                            $length_in = explode('*^', str_replace('*^-', '*^', $val->length_in));
                            $height_ft = explode('*^', str_replace('*^-', '*^', $val->height_ft));
                            $height_in = explode('*^', str_replace('*^-', '*^', $val->height_in));
                            $width_ft = explode('*^', str_replace('*^-', '*^', $val->width_ft));
                            $width_in = explode('*^', str_replace('*^-', '*^', $val->width_in));
                            $weight = explode('*^', str_replace('*^-', '*^', $val->weight));
                            $ymk = explode('*^', str_replace('*^-', '*^', $val->ymk));
                            $load_method = explode('*^', str_replace('*^-', '*^', $val->load_method));
                            $unload_method = explode('*^', str_replace('*^-', '*^', $val->unload_method));
                            $category = explode('*^', str_replace('*^-', '*^', $val->category));
                            ?>
                            @if (isset($val->length_ft))
                                <br>
                                @foreach ($length_ft as $key => $row)
                                    @if (empty($ymk[$key]))
                                        @php $ymk[$key] = "" @endphp
                                    @endif
                                    @if (empty($length_ft[$key]))
                                        @php $length_ft[$key] = "" @endphp
                                    @endif
                                    @if (empty($length_in[$key]))
                                        @php $length_in[$key] = "" @endphp
                                    @endif
                                    @if (empty($height_ft[$key]))
                                        @php $height_ft[$key] = "" @endphp
                                    @endif
                                    @if (empty($height_in[$key]))
                                        @php $height_in[$key] = "" @endphp
                                    @endif
                                    @if (empty($width_ft[$key]))
                                        @php $width_ft[$key] = "" @endphp
                                    @endif
                                    @if (empty($width_in[$key]))
                                        @php $width_in[$key] = "" @endphp
                                    @endif
                                    @if (empty($weight[$key]))
                                        @php $weight[$key] = "" @endphp
                                    @endif
                                    @if (empty($load_method[$key]))
                                        @php $load_method[$key] = "" @endphp
                                    @endif
                                    @if (empty($unload_method[$key]))
                                        @php $unload_method[$key] = "" @endphp
                                    @endif
                                    @if (empty($category[$key]))
                                        @php $category[$key] = "" @endphp
                                    @endif

                                    <p>
                                        <b>{{ $ymk[$key] }} <a
                                                href="https://www.google.com/search?q={{ urlencode($ymk[$key]) }}"
                                                target="_blank">{{ $ymk[$key] }}</a></b><br>
                                        <b>Length:</b> {{ $length_ft[$key] . 'ft ' . $length_in[$key] }}in<br>
                                        <b>Height:</b> {{ $height_ft[$key] . 'ft ' . $height_in[$key] }}in<br>
                                        <b>Width:</b> {{ $width_ft[$key] . 'ft ' . $width_in[$key] }}in<br>
                                        <b>Weight:</b> {{ $weight[$key] }}lbs<br>
                                        <b>Category:</b> {{ $category[$key] }}<br>
                                    </p>
                                    @if ($val->load_type != null)
                                        <br>
                                        <b>Load Type:</b> {{ $val->load_type }}<br>
                                        <b>Load Method:</b> {{ $load_method[$i] }}<br>
                                        <b>Unload Method:</b> {{ $unload_method[$i] }}<br>
                                    @endif
                                @endforeach
                            @endif
                            {{-- @if ($val->category != null)
                                <br>
                                <b>Category:</b> {{ $category[$i] }}<br>
                            @endif --}}
                            @if ($val->boat_on_trailer == 1)
                                <br>
                                <b>On trailer:</b> Yes<br>
                            @endif
                            @if ($val->trailer_type && $val->trailer_type != null && $val->trailer_type != 0)
                                <br>
                                <b>On trailer:</b> {{ $val->trailer_type }}<br>
                            @endif
                            @if ($val->commodity_detail && $val->commodity_detail != null && $val->commodity_detail != 0)
                                <br>
                                <b>Commodity Detail:</b> {{ $val->commodity_detail }}<br>
                            @endif
                            @if ($val->handling_unit && $val->handling_unit != null && $val->handling_unit != 0)
                                <br>
                                <b>Handling Unit:</b> {{ $val->handling_unit }}<br>
                            @endif
                            @if ($val->commodity_unit && $val->commodity_unit != null && $val->commodity_unit != 0)
                                <br>
                                <b>Commodity Unit:</b> {{ $val->commodity_unit }}<br>
                            @endif
                            @if ($val->trailer_specification && $val->trailer_specification != null && $val->trailer_specification != 0)
                                <br>
                                <b>Trailer Specification:</b> {{ $val->trailer_specification }}<br>
                            @endif
                            @if ($val->freight && $val->freight->equipment_type != null && $val->freight->equipment_type != 0)
                                <br>
                                <b>Equipment Type:</b> {{ $val->freight->equipment_type }}<br>
                            @endif
                            @if ($val->freight && $val->freight->pick_up_services != null && $val->freight->pick_up_services != 0)
                                <br>
                                <b>Pickup Services:</b> {{ $val->freight->pick_up_services }}<br>
                            @endif
                            @if ($val->freight && $val->freight->deliver_services != null && $val->freight->deliver_services != 0)
                                <br>
                                <b>Delivery Services:</b> {{ $val->freight->deliver_services }}<br>
                            @endif
                            @if ($val->freight && $val->freight->ex_pickup_date != null && $val->freight->ex_pickup_date != 0)
                                <br>
                                <b>Pickup Date:</b> {{ $val->freight->ex_pickup_date }}<br>
                            @endif
                            @if ($val->freight && $val->freight->ex_pickup_time != null && $val->freight->ex_pickup_time != 0)
                                <br>
                                <b>Pickup Time:</b> {{ $val->freight->ex_pickup_time }}<br>
                            @endif
                            @if ($val->freight && $val->freight->ex_delivery_date != null && $val->freight->ex_delivery_date != 0)
                                <br>
                                <b>Delivery Date:</b> {{ $val->freight->ex_delivery_date }}<br>
                            @endif
                            @if ($val->freight && $val->freight->ex_delivery_time != null && $val->freight->ex_delivery_time != 0)
                                <br>
                                <b>Delivery Time:</b> {{ $val->freight->ex_delivery_time }}<br>
                            @endif
                            @if ($val->freight && $val->freight->hazardous == 1)
                                <br>
                                <b>Hazardous:</b> Yes<br>
                            @endif
                            @if ($val->freight && $val->freight->stackable == 1)
                                <br>
                                <b>Stackable:</b> Yes<br>
                            @endif
                            @if ($val->freight && $val->freight->protect_from_freezing == 1)
                                <br>
                                <b>Protect From Freezing:</b> Yes<br>
                            @endif
                            @if ($val->freight && $val->freight->sort_segregate == 1)
                                <br>
                                <b>Sort Segregate:</b> Yes<br>
                            @endif
                            @if ($val->freight && $val->freight->blind_shipment == 1)
                                <br>
                                <b>Blind Shipment:</b> Yes<br>
                            @endif
                        </td>
                        <td class="table1td">
                            <form class="addPriceForm" action="{{ route('price_giver.give.price') }}"
                                data-orderid="{{ $val->id }}" method="POST">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $val->id }}">
                                <input type="number" class="form-control" name="given_price" min="0"
                                    placeholder="Price1" required>
                                {{-- <input type="number" class="form-control" name="given_price2" min="0" placeholder="Price2" required> --}}
                                <button type="submit" class="btn btn-primary submitBtn">Add</button>
                            </form>
                        </td>
                    </tr>
                    <tr class="child1{{ $key }}" style="display:none">
                        <td colspan="7">
                            <table class="table table-bordered table-striped bg-white mt-3 mb-4">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th></th>
                                        <th></th>
                                        <th>Storage</th>
                                        <th>ADDITIONAL</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($val->pstatus >= 9 && $val->pstatus <= 13)
                                        @if ($val->listed_sheet)
                                            @foreach ($val->listed_sheet as $key => $value)
                                                <tr>
                                                    <td>Listed</td>
                                                    <td>Listed Price : {{ $value->listed_price }}</td>
                                                    <td>Price : {{ $value->price }}</td>
                                                    <td>{{ $value->storage }}</td>
                                                    <td>{{ $value->additional }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    @if ($val->pstatus >= 10 && $val->pstatus <= 13)
                                        @if ($val->dispatch_sheet)
                                            @foreach ($val->dispatch_sheet as $key => $value)
                                                <tr>
                                                    <td>Schedule</td>
                                                    <td>Pickup Date : {{ $value->pickup_date }}</td>
                                                    <td>Condition : {{ $value->vehicle_condition }}</td>
                                                    <td>{{ $value->storage }}</td>
                                                    <td>{{ $value->additional }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    @if ($val->pstatus >= 11 && $val->pstatus <= 13)
                                        @if ($val->pickedup_sheet)
                                            @foreach ($val->pickedup_sheet as $key => $value)
                                                <tr>
                                                    <td>Picked Up</td>
                                                    <td>Delivery Date : {{ $value->delivery_date }}</td>
                                                    <td>Condition : {{ $value->vehicle_condition }}</td>
                                                    <td>{{ $value->storage }}</td>
                                                    <td>{{ $value->additional }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    @if ($val->pstatus >= 12 && $val->pstatus <= 13)
                                        @if ($val->delivery_sheet)
                                            @foreach ($val->delivery_sheet as $key => $value)
                                                <tr>
                                                    <td>Delivery</td>
                                                    <td>Delivery Date : {{ $value->delivery_date }}</td>
                                                    <td>Position : {{ $value->vehicle_position }}</td>
                                                    <td>{{ $value->driver_no }}</td>
                                                    <td>{{ $value->additional }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    @if ($val->pstatus == 13)
                                        @if ($val->delivery_sheet)
                                            @foreach ($val->delivery_sheet as $key => $value)
                                                <tr>
                                                    <td>Completed</td>
                                                    <td>Remarks :
                                                        {{ $value->remarks . '(' . $value->client_rating . ')' }}</td>
                                                    <td>Comments : {{ $value->comments }}</td>
                                                    <td>Satisfied : {{ $value->satisfied }}</td>
                                                    <td>{{ $value->additional }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                            <form action="send_{{ $val->pstatus }}">
                                <div class="continer copntainer"></div>
                                <input type="hidden" name="orderId" id="orderId_{{ $val->pstatus . $val->id }}"
                                    value="{{ $val->id }}">
                                @if ($val->pstatus == 9)
                                    <h3 class="table-data-align m-2">Listed</h3>
                                    <hr style="margin: 0;">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>Paid</label>
                                            <select name="paid" id="paid_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage</label>
                                            <input class="form-control" id="storage_{{ $val->pstatus . $val->id }}"
                                                name="storage" placeholder="Storage" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Listed Price</label>
                                            <input class="form-control"
                                                id="listed_price_{{ $val->pstatus . $val->id }}"
                                                placeholder="Listed Price" name="listed_price" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Auction Update</label>
                                            <input class="form-control"
                                                id="auction_update_{{ $val->pstatus . $val->id }}"
                                                placeholder="Auction Update" name="Auction Update" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Title</label>
                                            <select id="title_keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Key</label>
                                            <select id="keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Listed Count</label>
                                            <input class="form-control"
                                                id="listed_count_{{ $val->pstatus . $val->id }}"
                                                placeholder="Listed Count" name="listed_count" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Old/New Price</label>
                                            <input class="form-control" id="price_{{ $val->pstatus . $val->id }}"
                                                placeholder="Old / New Price" name="old-new/price" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Condition</label>
                                            <input class="form-control" placeholder="Vehicle Condition"
                                                id="condition_{{ $val->pstatus . $val->id }}" value="">
                                        </div>
                                        <div class="col-md-5">
                                            <label>Additional</label>
                                            <input class="form-control"
                                                id="additional_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button type="button"
                                                onclick="listedUpload({{ $val->pstatus . $val->id }})"
                                                class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>
                                    </div>
                                @endif
                                @if ($val->pstatus == 10)
                                    <h3 class="table-data-align m-2">Schedule</h3>
                                    <hr style="margin: 0;">
                                    <div class="row m-2">
                                        <div class="col-md-3">
                                            <label>Pickedup Time</label>
                                            <input class="form-control" type="datetime-local"
                                                id="pickedup_{{ $val->pstatus . $val->id }}"
                                                placeholder="=PickedUp time">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Delivery Time</label>
                                            <input class="form-control" type="datetime-local"
                                                id="delivery_date_{{ $val->pstatus . $val->id }}"
                                                placeholder="=Delivery time">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Dispatch Price</label>
                                            <input class="form-control" type="text"
                                                id="price_{{ $val->pstatus . $val->id }}"
                                                placeholder="Dispatch Price" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Condition</label>
                                            <input class="form-control" type="text"
                                                id="condition_{{ $val->pstatus . $val->id }}"
                                                placeholder="Vehicle Condition">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Company Name</label>
                                            <input class="form-control" type="text"
                                                id="company_name_{{ $val->pstatus . $val->id }}"
                                                placeholder="Company Name">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage </label>
                                            <input class="form-control" id="storage_{{ $val->pstatus . $val->id }}"
                                                placeholder="Storage" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Carrier Rating </label>
                                            <input class="form-control"
                                                id="carrier_rating_{{ $val->pstatus . $val->id }}"
                                                placeholder="Carrier Rating" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Aware Driver Delivery </label>
                                            <input class="form-control" type="text"
                                                id="aware_driver_delivery_date_{{ $val->pstatus . $val->id }}"
                                                placeholder="Aware Driver Delivery">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver FMCSA (Active)?</label>
                                            <select id="driver_fmcsa_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Verify FMCSA? </label>
                                            <input class="form-control" id="fmcsa_{{ $val->pstatus . $val->id }}"
                                                placeholder="FMCSA" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Date Of Insurance (FMCSA)</label>
                                            <input class="form-control" type="date"
                                                id="insurance_date_{{ $val->pstatus . $val->id }}"
                                                placeholder="Date Of Insurance (FMCSA)" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>COI Holder</label>
                                            <select id="coi_holder_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                                <option value="Waiting">Waiting</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Is Vehicle Luxury?</label>
                                            <select id="vehicle_luxury_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>New/Old Driver</label>
                                            <select id="new_old_driver_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Old Driver">Old Driver</option>
                                                <option value="New Driver">New Driver</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Is Local?</label>
                                            <select id="is_local_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Job Accept </label>
                                            <input class="form-control"
                                                id="job_accept_{{ $val->pstatus . $val->id }}"
                                                placeholder="Job Accept" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Title</label>
                                            <select id="title_keys_{{ $val->pstatus . $val->id }}" name="key"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Key</label>
                                            <select id="keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Auction Update</label>
                                            <input id="auction_update_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Auction Update" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage Pay</label>
                                            <select id="who_pay_storage_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Vehicle Position</label>
                                            <input id="vehicle_position_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Vehicle Position" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Payment Method</label>
                                            <input class="form-control"
                                                id="payment_method_{{ $val->pstatus . $val->id }}"
                                                placeholder="Payment Method" value="">
                                        </div>
                                        <div class="col-md-11">
                                            <label>Additional</label>
                                            <input class="form-control"
                                                id="additional_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button onclick="dispatchUpload({{ $val->pstatus . $val->id }})"
                                                type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>

                                    </div>
                                @endif
                                @if ($val->pstatus == 11)
                                    <h3 class="table-data-align m-2">Picked Up</h3>
                                    <hr style="margin: 0;">
                                    <div class="row m-2">
                                        <div class="col-md-12">
                                            <h4>Auction Status</h4>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Auction Status</label>
                                            <input class="form-control"
                                                id="auction_status1_{{ $val->pstatus . $val->id }}"
                                                placeholder="Auction Status" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage </label>
                                            <input class="form-control" id="storage1_{{ $val->pstatus . $val->id }}"
                                                placeholder="Storage" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Condition </label>
                                            <input class="form-control"
                                                id="condition1_{{ $val->pstatus . $val->id }}"
                                                placeholder="Vehicle Condition" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Title</label>
                                            <select id="title_keys1_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Key</label>
                                            <select id="keys1_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Position </label>
                                            <input id="vehicle_position1_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Vehicle Position" value="">
                                        </div>
                                        <div class="col-md-11">
                                            <label>Additional</label>
                                            <input class="form-control"
                                                id="additional1_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" name="additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button onclick="auctionpickedUpUpload({{ $val->pstatus . $val->id }})"
                                                type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>
                                    </div>
                                    <div class="row m-2">
                                        <div class="col-md-12">
                                            <h4>Driver Status</h4>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver Status </label>
                                            <input class="form-control"
                                                id="driver_status_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver Status" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver Name </label>
                                            <input class="form-control"
                                                id="carrier_name_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver Name" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver Payment </label>
                                            <input class="form-control"
                                                id="driver_payment_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver Payment" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No1# </label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No1#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No2#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no2_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No2#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No3#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no3_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No3#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No4#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no4_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No4#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Company Name </label>
                                            <input class="form-control"
                                                id="company_name_{{ $val->pstatus . $val->id }}"
                                                placeholder="Company Name" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage </label>
                                            <input class="form-control"
                                                id="storage_{{ $val->pstatus . $val->id }}" placeholder="Storage"
                                                value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Delivery Datetime </label>
                                            <input class="form-control"
                                                id="delivery_date_{{ $val->pstatus . $val->id }}"
                                                type="datetime-local" placeholder="Delivery Datetime"
                                                value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Condition </label>
                                            <input class="form-control"
                                                id="condition_{{ $val->pstatus . $val->id }}"
                                                placeholder="Vehicle Condition" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Position </label>
                                            <input id="vehicle_position_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Vehicle Position"
                                                value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Title</label>
                                            <select id="title_keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Key</label>
                                            <select id="keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Payment</label>
                                            <select id="payment_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Payment Charged Or Owes </label>
                                            <input class="form-control"
                                                id="payment_charged_or_owes_{{ $val->pstatus . $val->id }}"
                                                placeholder="Payment Charged Or Owes" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Payment Method </label>
                                            <input class="form-control"
                                                id="payment_method_{{ $val->pstatus . $val->id }}"
                                                placeholder="Payment Method" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Total Amount (If Owed) </label>
                                            <input class="form-control" id="price_{{ $val->pstatus . $val->id }}"
                                                placeholder="Total Amount (If Owed)" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Dock Receipt (If Port)</label>
                                            <input class="form-control"
                                                id="stamp_dock_receipt_{{ $val->pstatus . $val->id }}"
                                                placeholder="Dock Receipt (If Port)" value="">
                                        </div>
                                        <div class="col-md-5">
                                            <label>Additional</label>
                                            <input class="form-control"
                                                id="additional_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" name="additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button onclick="pickedUpUpload({{ $val->pstatus . $val->id }})"
                                                type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>
                                    </div>
                                @endif
                                @if ($val->pstatus == 12)
                                    <h3 class="table-data-align m-2">Delivery</h3>
                                    <hr style="margin: 0;">
                                    <div class="row m-2">
                                        <div class="col-md-2">
                                            <label>Driver No1#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No1#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No2#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no2_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No2#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No3#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no3_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No3#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No4#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no4_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No4#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver Status</label>
                                            <input id="driver_status_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Driver Status" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver Payment Status</label>
                                            <input id="driver_payment_status_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Driver Payment Status"
                                                value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Condition</label>
                                            <input class="form-control"
                                                id="condition_{{ $val->pstatus . $val->id }}"
                                                placeholder="Vehicle Condition" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Customer Informed</label>
                                            <input class="form-control"
                                                id="customer_informed_{{ $val->pstatus . $val->id }}"
                                                placeholder="Customer Informed" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Position</label>
                                            <input id="vehicle_position_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Vehicle Position"
                                                value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Delivery Datetime</label>
                                            <input class="form-control"
                                                id="delivery_date_{{ $val->pstatus . $val->id }}"
                                                type="datetime-local" placeholder="Delivery Datetime"
                                                value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage Pay</label>
                                            <input id="who_pay_storage_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Who Pay Storage" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Title</label>
                                            <select id="title_keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Key</label>
                                            <select id="keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Client & Status</label>
                                            <select id="client_status_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Owes Status</label>
                                            <select id="owes_status_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label>Additional</label>
                                            <input class="form-control"
                                                id="additional_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button onclick="deliveryUpload({{ $val->pstatus . $val->id }})"
                                                type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>
                                    </div>
                                @endif
                                @if ($val->pstatus == 13)
                                    <h3 class="table-data-align m-2">Completed</h3>
                                    <hr style="margin:0;">
                                    <div class="row  m-2">
                                        <div class="col-md-3">
                                            <label>Remarks Status</label>
                                            <input class="form-control h-50"
                                                id="remarks_{{ $val->pstatus . $val->id }}"
                                                placeholder="Remarks Status" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Comments</label>
                                            <input class="form-control h-50"
                                                id="comments_{{ $val->pstatus . $val->id }}"
                                                placeholder="Comments" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Satisfied?</label>
                                            <input class="form-control h-50"
                                                id="satisfied_{{ $val->pstatus . $val->id }}"
                                                placeholder="How you Satisfied?" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Review</label>
                                            <select id="review_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50"
                                                onchange="this.value == 'Yes' ? $('#all_rating').show() : $('#all_rating').hide()">
                                                <option value="" selected disabled>Select</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12" id="all_rating" style="display:none;">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <label>Website</label>
                                                    <select id="website_{{ $val->pstatus . $val->id }}"
                                                        class="form-control h-50"
                                                        onchange="this.value == 'Other' ? $('#other_website').show() : $('#other_website').hide()">
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="BBB">BBB</option>
                                                        <option value="Trust Pilot">Trust Pilot</option>
                                                        <option value="Google">Google</option>
                                                        <option value="Yelp">Yelp</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3" style="display:none;" id="other_website">
                                                    <label>Other Website</label>
                                                    <input class="form-control h-50"
                                                        id="website_other_{{ $val->pstatus . $val->id }}"
                                                        placeholder="Other Website" value="">
                                                </div>
                                                <div class="col-sm-3">
                                                    <label>Rating</label>
                                                    <select id="client_rating_{{ $val->pstatus . $val->id }}"
                                                        class="form-control h-50">
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Positive">Positive</option>
                                                        <option value="Neutral">Neutral</option>
                                                        <option value="Negative">Negative</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label>Website Link</label>
                                                    <input class="form-control h-50"
                                                        id="website_link_{{ $val->pstatus . $val->id }}"
                                                        placeholder="Website Link" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-11">
                                            <label>Additional</label>
                                            <input class="form-control h-50"
                                                id="additional_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button onclick="completedUpload({{ $val->pstatus . $val->id }})"
                                                type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        </td>

                    </tr>
                @elseif (in_array('115', $phoneaccess) && $val->freight != null)
                    <tr class="parent1{{ $key }}">
                        <td>
                            <input type="hidden" class='order_id' value="{{ $val->id }}">
                            <input type="hidden" class="pstatus" value="{{ $val->pstatus }}">
                            <input type="hidden" class="client_email" value="{{ $val->oemail }}">
                            <input type="hidden" class="client_name" value="{{ $val->oname }}">
                            <input type="hidden" class="client_phone"
                                value="{{ $val->mainPhNum ?? $val->main_ph }}">
                            <input type="hidden" class="location1" value="{{ $val->originzsc }}">
                            <input type="hidden" class="location2" value="{{ $val->destinationzsc }}">
                            <input type="hidden" class="origincity" value="{{ $val->origincity }}">
                            <input type="hidden" class="destinationcity" value="{{ $val->destinationcity }}">
                            <input type="hidden" class="pickup_date" value="{{ $val->pickup_date }}">
                            <input type="hidden" class="delivery_date" value="{{ $val->delivery_date }}">



                            <a href="https://www.google.com/maps/place/{{ $val->originzip }},+USA/"
                                target="_blank" class="table1ancher">
                                <i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                                <span> {{ $val->origincity . '-' . $val->originstate . '-' . $val->originzip }}</span>
                            </a>
                        </td>
                        <td>
                            <a href="https://www.google.com/maps/place/{{ $val->destinationzip }},+USA/"
                                target="_blank" class="table1ancher">
                                <i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                                <span>
                                    {{ $val->destinationcity . '-' . $val->destinationstate . '-' . $val->destinationzip }}</span>
                            </a>
                        </td>
                        <?php
                        // $ymk = explode('*^-', $val->ymk);
                        ?>
                        <?php
                        // $standardized = str_replace('*^-', '*^', $val->ymk);
                        // $ymk = explode('*^', $standardized);
                        // dd($val->ymk, $standardized, $ymk);
                        ?>
                        <td class="table1td">
                            <a href="https://www.google.com/maps/dir/{{ $val->originzip }},+USA/{{ $val->destinationzip }},+USA/"
                                target="_blank" class="table1ancher">
                                <i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                                <span>View Route</span>
                            </a>
                            {{-- @foreach ($ymk as $val2)
                                @if ($val2)
                                    {{ $val2 }} <a href="https://www.google.com/search?q={{ urlencode($val2) }}" target="_blank">{{ $val2 }}</a><br>
                                @endif
                            @endforeach --}}
                            <b> Miles: </b> <span>{{ $val->miles > 0 ? $val->miles : 'N/A' }}</span>
                            <br>
                            <b> Order ID# </b> <span><?php echo $val->id; ?></span>
                            <br>
                            <b> Condition </b> <span>{{ $val->condition == '1' ? 'Running' : 'Non-Running' }}</span>
                            <br>
                            <b> Trailer Type </b> <span>{{ $val->transport == '1' ? 'Open' : 'Enclosed' }}</span>
                            @if (in_array('71', $phoneaccess))
                                @if (isset($val->u_id))
                                    <br>
                                    <b>Booker:</b> <span>{{ get_user_name($val->u_id) }}</span><br>
                                @endif
                            @endif
                            @if (isset($val->dispatcher_id))
                                <b>Assign To:</b> <span>{{ get_user_name($val->dispatcher_id) }}</span><br>
                            @else
                                @if ($val->pstatus > 8 && $val->pstatus < 15)
                                    <b>Assign To:</b>
                                    @if (in_array('76', $phoneaccess))
                                        <span type="button" class="badge badge-danger rounded"
                                            data-toggle="modal"
                                            onclick="$('#assigning_dispatcher_order').val({{ $val->id }})"
                                            data-target="#assignToDispatcher">Not Assigned</span><br>
                                    @else
                                        <span>Not Assigned</span><br>
                                    @endif
                                @endif
                            @endif
                            @if ($val->pstatus == 13 && !empty($val->completed_sheet) && isset($val->completed_sheet[0]))
                                </br>
                                <b>Review:</b> <span>{{ $val->completed_sheet[0]->review }}</span><br>
                            @endif
                            <span> <?php echo get_car_or_heavy($val->car_type); ?> </span>
                            @if (isset($val->roro))
                                <br>
                                <b>{{ $val->roro }}</b><br>
                            @endif
                            @if (isset($val->available_at_auction))
                                <b>{{ $val->available_at_auction }}</b><br>
                                <b>{{ $val->link }}</b><br>
                            @endif
                            @if (isset($val->modification))
                                <b>Modified: Yes</b><br>
                                <b>{{ $val->modify_info }}</b><br>
                            @endif
                            @if ($check_panel == 2 && $val->source != null)
                                <b> Source:</b>
                                <span
                                    class="badge <?php echo $val->source == 'DayDispatch' ? 'badge-primary my-2' : 'badge-primary my-2'; ?>">{{ $val->source == 'DayDispatch' ? 'DD' : '-' }}</span>
                            @endif

                            @if (auth()->user()->role == 1)
                                @if ($val->paneltype != 1)
                                    @if (isset($val->ip_address))
                                        <br>
                                        <div>Ip : <b>{{ $val->ip_address }}</b></div>
                                    @endif
                                    @if (isset($val->ipcountry))
                                        <div>Address :
                                            <b>{{ isset($val->ippostal) ? $val->ippostal . ', ' : '' }}{{ isset($val->ipregion) ? $val->ipregion . ', ' : '' }}{{ isset($val->ipcity) ? $val->ipcity . ', ' : '' }}{{ isset($val->ipcountry) ? $val->ipcountry : '' }}</b>
                                            <br>
                                            <b>{{ isset($val->ip_details) ? $val->ip_details . ', ' : '' }}</b>
                                        </div>


                                    @endif
                                @endif
                            @endif
                            <?php
                            $length_ft = explode('*^', str_replace('*^-', '*^', $val->length_ft));
                            $length_in = explode('*^', str_replace('*^-', '*^', $val->length_in));
                            $height_ft = explode('*^', str_replace('*^-', '*^', $val->height_ft));
                            $height_in = explode('*^', str_replace('*^-', '*^', $val->height_in));
                            $width_ft = explode('*^', str_replace('*^-', '*^', $val->width_ft));
                            $width_in = explode('*^', str_replace('*^-', '*^', $val->width_in));
                            $weight = explode('*^', str_replace('*^-', '*^', $val->weight));
                            $ymk = explode('*^', str_replace('*^-', '*^', $val->ymk));
                            $load_method = explode('*^', str_replace('*^-', '*^', $val->load_method));
                            $unload_method = explode('*^', str_replace('*^-', '*^', $val->unload_method));
                            $category = explode('*^', str_replace('*^-', '*^', $val->category));
                            ?>
                            @if (isset($val->length_ft))
                                <br>
                                @foreach ($length_ft as $key => $row)
                                    @if (empty($ymk[$key]))
                                        @php $ymk[$key] = "" @endphp
                                    @endif
                                    @if (empty($length_ft[$key]))
                                        @php $length_ft[$key] = "" @endphp
                                    @endif
                                    @if (empty($length_in[$key]))
                                        @php $length_in[$key] = "" @endphp
                                    @endif
                                    @if (empty($height_ft[$key]))
                                        @php $height_ft[$key] = "" @endphp
                                    @endif
                                    @if (empty($height_in[$key]))
                                        @php $height_in[$key] = "" @endphp
                                    @endif
                                    @if (empty($width_ft[$key]))
                                        @php $width_ft[$key] = "" @endphp
                                    @endif
                                    @if (empty($width_in[$key]))
                                        @php $width_in[$key] = "" @endphp
                                    @endif
                                    @if (empty($weight[$key]))
                                        @php $weight[$key] = "" @endphp
                                    @endif
                                    @if (empty($load_method[$key]))
                                        @php $load_method[$key] = "" @endphp
                                    @endif
                                    @if (empty($unload_method[$key]))
                                        @php $unload_method[$key] = "" @endphp
                                    @endif
                                    @if (empty($category[$key]))
                                        @php $category[$key] = "" @endphp
                                    @endif

                                    <p>
                                        <b>{{ $ymk[$key] }} <a
                                                href="https://www.google.com/search?q={{ urlencode($ymk[$key]) }}"
                                                target="_blank">{{ $ymk[$key] }}</a></b><br>
                                        <b>Length:</b> {{ $length_ft[$key] . 'ft ' . $length_in[$key] }}in<br>
                                        <b>Height:</b> {{ $height_ft[$key] . 'ft ' . $height_in[$key] }}in<br>
                                        <b>Width:</b> {{ $width_ft[$key] . 'ft ' . $width_in[$key] }}in<br>
                                        <b>Weight:</b> {{ $weight[$key] }}lbs<br>
                                        <b>Category:</b> {{ $category[$key] }}<br>
                                    </p>
                                    @if ($val->load_type != null)
                                        <br>
                                        <b>Load Type:</b> {{ $val->load_type }}<br>
                                        <b>Load Method:</b> {{ $load_method[$i] }}<br>
                                        <b>Unload Method:</b> {{ $unload_method[$i] }}<br>
                                    @endif
                                @endforeach
                            @endif
                            {{-- @if ($val->category != null)
                                <br>
                                <b>Category:</b> {{ $category[$i] }}<br>
                            @endif --}}
                            @if ($val->boat_on_trailer == 1)
                                <br>
                                <b>On trailer:</b> Yes<br>
                            @endif
                            @if ($val->trailer_type && $val->trailer_type != null && $val->trailer_type != 0)
                                <br>
                                <b>On trailer:</b> {{ $val->trailer_type }}<br>
                            @endif
                            @if ($val->commodity_detail && $val->commodity_detail != null && $val->commodity_detail != 0)
                                <br>
                                <b>Commodity Detail:</b> {{ $val->commodity_detail }}<br>
                            @endif
                            @if ($val->handling_unit && $val->handling_unit != null && $val->handling_unit != 0)
                                <br>
                                <b>Handling Unit:</b> {{ $val->handling_unit }}<br>
                            @endif
                            @if ($val->commodity_unit && $val->commodity_unit != null && $val->commodity_unit != 0)
                                <br>
                                <b>Commodity Unit:</b> {{ $val->commodity_unit }}<br>
                            @endif
                            @if ($val->trailer_specification && $val->trailer_specification != null && $val->trailer_specification != 0)
                                <br>
                                <b>Trailer Specification:</b> {{ $val->trailer_specification }}<br>
                            @endif
                            @if ($val->freight && $val->freight->frieght_class)
                                <br>
                                <b>Freight Class:</b> {{ $val->freight->frieght_class }}<br>
                            @endif
                            @if ($val->freight && $val->freight->equipment_type != null && $val->freight->equipment_type != 0)
                                <br>
                                <b>Equipment Type:</b> {{ $val->freight->equipment_type }}<br>
                            @endif
                            @if ($val->freight && $val->freight->pick_up_services != null && $val->freight->pick_up_services != 0)
                                <br>
                                <b>Pickup Services:</b> {{ $val->freight->pick_up_services }}<br>
                            @endif
                            @if ($val->freight && $val->freight->deliver_services != null && $val->freight->deliver_services != 0)
                                <br>
                                <b>Delivery Services:</b> {{ $val->freight->deliver_services }}<br>
                            @endif
                            @if ($val->freight && $val->freight->ex_pickup_date != null && $val->freight->ex_pickup_date != 0)
                                <br>
                                <b>Pickup Date:</b> {{ $val->freight->ex_pickup_date }}<br>
                            @endif
                            @if ($val->freight && $val->freight->ex_pickup_time != null && $val->freight->ex_pickup_time != 0)
                                <br>
                                <b>Pickup Time:</b> {{ $val->freight->ex_pickup_time }}<br>
                            @endif
                            @if ($val->freight && $val->freight->ex_delivery_date != null && $val->freight->ex_delivery_date != 0)
                                <br>
                                <b>Delivery Date:</b> {{ $val->freight->ex_delivery_date }}<br>
                            @endif
                            @if ($val->freight && $val->freight->ex_delivery_time != null && $val->freight->ex_delivery_time != 0)
                                <br>
                                <b>Delivery Time:</b> {{ $val->freight->ex_delivery_time }}<br>
                            @endif
                            @if ($val->freight && $val->freight->hazardous == 1)
                                <br>
                                <b>Hazardous:</b> Yes<br>
                            @endif
                            @if ($val->freight && $val->freight->stackable == 1)
                                <br>
                                <b>Stackable:</b> Yes<br>
                            @endif
                            @if ($val->freight && $val->freight->protect_from_freezing == 1)
                                <br>
                                <b>Protect From Freezing:</b> Yes<br>
                            @endif
                            @if ($val->freight && $val->freight->sort_segregate == 1)
                                <br>
                                <b>Sort Segregate:</b> Yes<br>
                            @endif
                            @if ($val->freight && $val->freight->blind_shipment == 1)
                                <br>
                                <b>Blind Shipment:</b> Yes<br>
                            @endif
                        </td>
                        <td class="table1td">
                            <form class="addPriceForm" action="{{ route('price_giver.give.price') }}"
                                data-orderid="{{ $val->id }}" method="POST">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $val->id }}">
                                <input type="number" class="form-control" name="given_price" min="0"
                                    placeholder="Price1" required>
                                {{-- <input type="number" class="form-control" name="given_price2" min="0" placeholder="Price2" required> --}}
                                <button type="submit" class="btn btn-primary submitBtn">Add</button>
                            </form>
                        </td>
                    </tr>
                    <tr class="child1{{ $key }}" style="display:none">
                        <td colspan="7">
                            <table class="table table-bordered table-striped bg-white mt-3 mb-4">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th></th>
                                        <th></th>
                                        <th>Storage</th>
                                        <th>ADDITIONAL</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($val->pstatus >= 9 && $val->pstatus <= 13)
                                        @if ($val->listed_sheet)
                                            @foreach ($val->listed_sheet as $key => $value)
                                                <tr>
                                                    <td>Listed</td>
                                                    <td>Listed Price : {{ $value->listed_price }}</td>
                                                    <td>Price : {{ $value->price }}</td>
                                                    <td>{{ $value->storage }}</td>
                                                    <td>{{ $value->additional }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    @if ($val->pstatus >= 10 && $val->pstatus <= 13)
                                        @if ($val->dispatch_sheet)
                                            @foreach ($val->dispatch_sheet as $key => $value)
                                                <tr>
                                                    <td>Schedule</td>
                                                    <td>Pickup Date : {{ $value->pickup_date }}</td>
                                                    <td>Condition : {{ $value->vehicle_condition }}</td>
                                                    <td>{{ $value->storage }}</td>
                                                    <td>{{ $value->additional }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    @if ($val->pstatus >= 11 && $val->pstatus <= 13)
                                        @if ($val->pickedup_sheet)
                                            @foreach ($val->pickedup_sheet as $key => $value)
                                                <tr>
                                                    <td>Picked Up</td>
                                                    <td>Delivery Date : {{ $value->delivery_date }}</td>
                                                    <td>Condition : {{ $value->vehicle_condition }}</td>
                                                    <td>{{ $value->storage }}</td>
                                                    <td>{{ $value->additional }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    @if ($val->pstatus >= 12 && $val->pstatus <= 13)
                                        @if ($val->delivery_sheet)
                                            @foreach ($val->delivery_sheet as $key => $value)
                                                <tr>
                                                    <td>Delivery</td>
                                                    <td>Delivery Date : {{ $value->delivery_date }}</td>
                                                    <td>Position : {{ $value->vehicle_position }}</td>
                                                    <td>{{ $value->driver_no }}</td>
                                                    <td>{{ $value->additional }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                    @if ($val->pstatus == 13)
                                        @if ($val->delivery_sheet)
                                            @foreach ($val->delivery_sheet as $key => $value)
                                                <tr>
                                                    <td>Completed</td>
                                                    <td>Remarks :
                                                        {{ $value->remarks . '(' . $value->client_rating . ')' }}</td>
                                                    <td>Comments : {{ $value->comments }}</td>
                                                    <td>Satisfied : {{ $value->satisfied }}</td>
                                                    <td>{{ $value->additional }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                            <form action="send_{{ $val->pstatus }}">
                                <div class="continer copntainer"></div>
                                <input type="hidden" name="orderId" id="orderId_{{ $val->pstatus . $val->id }}"
                                    value="{{ $val->id }}">
                                @if ($val->pstatus == 9)
                                    <h3 class="table-data-align m-2">Listed</h3>
                                    <hr style="margin: 0;">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>Paid</label>
                                            <select name="paid" id="paid_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage</label>
                                            <input class="form-control"
                                                id="storage_{{ $val->pstatus . $val->id }}" name="storage"
                                                placeholder="Storage" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Listed Price</label>
                                            <input class="form-control"
                                                id="listed_price_{{ $val->pstatus . $val->id }}"
                                                placeholder="Listed Price" name="listed_price" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Auction Update</label>
                                            <input class="form-control"
                                                id="auction_update_{{ $val->pstatus . $val->id }}"
                                                placeholder="Auction Update" name="Auction Update" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Title</label>
                                            <select id="title_keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Key</label>
                                            <select id="keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Listed Count</label>
                                            <input class="form-control"
                                                id="listed_count_{{ $val->pstatus . $val->id }}"
                                                placeholder="Listed Count" name="listed_count" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Old/New Price</label>
                                            <input class="form-control" id="price_{{ $val->pstatus . $val->id }}"
                                                placeholder="Old / New Price" name="old-new/price" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Condition</label>
                                            <input class="form-control" placeholder="Vehicle Condition"
                                                id="condition_{{ $val->pstatus . $val->id }}" value="">
                                        </div>
                                        <div class="col-md-5">
                                            <label>Additional</label>
                                            <input class="form-control"
                                                id="additional_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button type="button"
                                                onclick="listedUpload({{ $val->pstatus . $val->id }})"
                                                class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>
                                    </div>
                                @endif
                                @if ($val->pstatus == 10)
                                    <h3 class="table-data-align m-2">Schedule</h3>
                                    <hr style="margin: 0;">
                                    <div class="row m-2">
                                        <div class="col-md-3">
                                            <label>Pickedup Time</label>
                                            <input class="form-control" type="datetime-local"
                                                id="pickedup_{{ $val->pstatus . $val->id }}"
                                                placeholder="=PickedUp time">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Delivery Time</label>
                                            <input class="form-control" type="datetime-local"
                                                id="delivery_date_{{ $val->pstatus . $val->id }}"
                                                placeholder="=Delivery time">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Dispatch Price</label>
                                            <input class="form-control" type="text"
                                                id="price_{{ $val->pstatus . $val->id }}"
                                                placeholder="Dispatch Price" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Condition</label>
                                            <input class="form-control" type="text"
                                                id="condition_{{ $val->pstatus . $val->id }}"
                                                placeholder="Vehicle Condition">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Company Name</label>
                                            <input class="form-control" type="text"
                                                id="company_name_{{ $val->pstatus . $val->id }}"
                                                placeholder="Company Name">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage </label>
                                            <input class="form-control"
                                                id="storage_{{ $val->pstatus . $val->id }}" placeholder="Storage"
                                                value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Carrier Rating </label>
                                            <input class="form-control"
                                                id="carrier_rating_{{ $val->pstatus . $val->id }}"
                                                placeholder="Carrier Rating" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Aware Driver Delivery </label>
                                            <input class="form-control" type="text"
                                                id="aware_driver_delivery_date_{{ $val->pstatus . $val->id }}"
                                                placeholder="Aware Driver Delivery">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver FMCSA (Active)?</label>
                                            <select id="driver_fmcsa_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Verify FMCSA? </label>
                                            <input class="form-control" id="fmcsa_{{ $val->pstatus . $val->id }}"
                                                placeholder="FMCSA" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Date Of Insurance (FMCSA)</label>
                                            <input class="form-control" type="date"
                                                id="insurance_date_{{ $val->pstatus . $val->id }}"
                                                placeholder="Date Of Insurance (FMCSA)" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>COI Holder</label>
                                            <select id="coi_holder_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                                <option value="Waiting">Waiting</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Is Vehicle Luxury?</label>
                                            <select id="vehicle_luxury_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>New/Old Driver</label>
                                            <select id="new_old_driver_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Old Driver">Old Driver</option>
                                                <option value="New Driver">New Driver</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Is Local?</label>
                                            <select id="is_local_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Job Accept </label>
                                            <input class="form-control"
                                                id="job_accept_{{ $val->pstatus . $val->id }}"
                                                placeholder="Job Accept" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Title</label>
                                            <select id="title_keys_{{ $val->pstatus . $val->id }}" name="key"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Key</label>
                                            <select id="keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Auction Update</label>
                                            <input id="auction_update_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Auction Update" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage Pay</label>
                                            <select id="who_pay_storage_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Vehicle Position</label>
                                            <input id="vehicle_position_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Vehicle Position"
                                                value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Payment Method</label>
                                            <input class="form-control"
                                                id="payment_method_{{ $val->pstatus . $val->id }}"
                                                placeholder="Payment Method" value="">
                                        </div>
                                        <div class="col-md-11">
                                            <label>Additional</label>
                                            <input class="form-control"
                                                id="additional_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button onclick="dispatchUpload({{ $val->pstatus . $val->id }})"
                                                type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>

                                    </div>
                                @endif
                                @if ($val->pstatus == 11)
                                    <h3 class="table-data-align m-2">Picked Up</h3>
                                    <hr style="margin: 0;">
                                    <div class="row m-2">
                                        <div class="col-md-12">
                                            <h4>Auction Status</h4>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Auction Status</label>
                                            <input class="form-control"
                                                id="auction_status1_{{ $val->pstatus . $val->id }}"
                                                placeholder="Auction Status" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage </label>
                                            <input class="form-control"
                                                id="storage1_{{ $val->pstatus . $val->id }}" placeholder="Storage"
                                                value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Condition </label>
                                            <input class="form-control"
                                                id="condition1_{{ $val->pstatus . $val->id }}"
                                                placeholder="Vehicle Condition" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Title</label>
                                            <select id="title_keys1_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Key</label>
                                            <select id="keys1_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Position </label>
                                            <input id="vehicle_position1_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Vehicle Position"
                                                value="">
                                        </div>
                                        <div class="col-md-11">
                                            <label>Additional</label>
                                            <input class="form-control"
                                                id="additional1_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" name="additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button onclick="auctionpickedUpUpload({{ $val->pstatus . $val->id }})"
                                                type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>
                                    </div>
                                    <div class="row m-2">
                                        <div class="col-md-12">
                                            <h4>Driver Status</h4>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver Status </label>
                                            <input class="form-control"
                                                id="driver_status_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver Status" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver Name </label>
                                            <input class="form-control"
                                                id="carrier_name_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver Name" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver Payment </label>
                                            <input class="form-control"
                                                id="driver_payment_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver Payment" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No1# </label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No1#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No2#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no2_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No2#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No3#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no3_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No3#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No4#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no4_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No4#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Company Name </label>
                                            <input class="form-control"
                                                id="company_name_{{ $val->pstatus . $val->id }}"
                                                placeholder="Company Name" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage </label>
                                            <input class="form-control"
                                                id="storage_{{ $val->pstatus . $val->id }}" placeholder="Storage"
                                                value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Delivery Datetime </label>
                                            <input class="form-control"
                                                id="delivery_date_{{ $val->pstatus . $val->id }}"
                                                type="datetime-local" placeholder="Delivery Datetime"
                                                value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Condition </label>
                                            <input class="form-control"
                                                id="condition_{{ $val->pstatus . $val->id }}"
                                                placeholder="Vehicle Condition" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Position </label>
                                            <input id="vehicle_position_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Vehicle Position"
                                                value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Title</label>
                                            <select id="title_keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Key</label>
                                            <select id="keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Payment</label>
                                            <select id="payment_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Payment Charged Or Owes </label>
                                            <input class="form-control"
                                                id="payment_charged_or_owes_{{ $val->pstatus . $val->id }}"
                                                placeholder="Payment Charged Or Owes" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Payment Method </label>
                                            <input class="form-control"
                                                id="payment_method_{{ $val->pstatus . $val->id }}"
                                                placeholder="Payment Method" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Total Amount (If Owed) </label>
                                            <input class="form-control" id="price_{{ $val->pstatus . $val->id }}"
                                                placeholder="Total Amount (If Owed)" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Dock Receipt (If Port)</label>
                                            <input class="form-control"
                                                id="stamp_dock_receipt_{{ $val->pstatus . $val->id }}"
                                                placeholder="Dock Receipt (If Port)" value="">
                                        </div>
                                        <div class="col-md-5">
                                            <label>Additional</label>
                                            <input class="form-control"
                                                id="additional_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" name="additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button onclick="pickedUpUpload({{ $val->pstatus . $val->id }})"
                                                type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>
                                    </div>
                                @endif
                                @if ($val->pstatus == 12)
                                    <h3 class="table-data-align m-2">Delivery</h3>
                                    <hr style="margin: 0;">
                                    <div class="row m-2">
                                        <div class="col-md-2">
                                            <label>Driver No1#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No1#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No2#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no2_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No2#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No3#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no3_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No3#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver No4#</label>
                                            <input class="form-control driverphoneno"
                                                id="driver_no4_{{ $val->pstatus . $val->id }}"
                                                placeholder="Driver No4#" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver Status</label>
                                            <input id="driver_status_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Driver Status" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Driver Payment Status</label>
                                            <input id="driver_payment_status_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Driver Payment Status"
                                                value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Condition</label>
                                            <input class="form-control"
                                                id="condition_{{ $val->pstatus . $val->id }}"
                                                placeholder="Vehicle Condition" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Customer Informed</label>
                                            <input class="form-control"
                                                id="customer_informed_{{ $val->pstatus . $val->id }}"
                                                placeholder="Customer Informed" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Vehicle Position</label>
                                            <input id="vehicle_position_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Vehicle Position"
                                                value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Delivery Datetime</label>
                                            <input class="form-control"
                                                id="delivery_date_{{ $val->pstatus . $val->id }}"
                                                type="datetime-local" placeholder="Delivery Datetime"
                                                value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Storage Pay</label>
                                            <input id="who_pay_storage_{{ $val->pstatus . $val->id }}"
                                                class="form-control" placeholder="Who Pay Storage" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <label>Title</label>
                                            <select id="title_keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Key</label>
                                            <select id="keys_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Client & Status</label>
                                            <select id="client_status_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Owes Status</label>
                                            <select id="owes_status_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50">
                                                <option value="">SELECT</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label>Additional</label>
                                            <input class="form-control"
                                                id="additional_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button onclick="deliveryUpload({{ $val->pstatus . $val->id }})"
                                                type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>
                                    </div>
                                @endif
                                @if ($val->pstatus == 13)
                                    <h3 class="table-data-align m-2">Completed</h3>
                                    <hr style="margin:0;">
                                    <div class="row  m-2">
                                        <div class="col-md-3">
                                            <label>Remarks Status</label>
                                            <input class="form-control h-50"
                                                id="remarks_{{ $val->pstatus . $val->id }}"
                                                placeholder="Remarks Status" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Comments</label>
                                            <input class="form-control h-50"
                                                id="comments_{{ $val->pstatus . $val->id }}"
                                                placeholder="Comments" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Satisfied?</label>
                                            <input class="form-control h-50"
                                                id="satisfied_{{ $val->pstatus . $val->id }}"
                                                placeholder="How you Satisfied?" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Review</label>
                                            <select id="review_{{ $val->pstatus . $val->id }}"
                                                class="form-control h-50"
                                                onchange="this.value == 'Yes' ? $('#all_rating').show() : $('#all_rating').hide()">
                                                <option value="" selected disabled>Select</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12" id="all_rating" style="display:none;">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <label>Website</label>
                                                    <select id="website_{{ $val->pstatus . $val->id }}"
                                                        class="form-control h-50"
                                                        onchange="this.value == 'Other' ? $('#other_website').show() : $('#other_website').hide()">
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="BBB">BBB</option>
                                                        <option value="Trust Pilot">Trust Pilot</option>
                                                        <option value="Google">Google</option>
                                                        <option value="Yelp">Yelp</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3" style="display:none;" id="other_website">
                                                    <label>Other Website</label>
                                                    <input class="form-control h-50"
                                                        id="website_other_{{ $val->pstatus . $val->id }}"
                                                        placeholder="Other Website" value="">
                                                </div>
                                                <div class="col-sm-3">
                                                    <label>Rating</label>
                                                    <select id="client_rating_{{ $val->pstatus . $val->id }}"
                                                        class="form-control h-50">
                                                        <option value="" selected disabled>Select</option>
                                                        <option value="Positive">Positive</option>
                                                        <option value="Neutral">Neutral</option>
                                                        <option value="Negative">Negative</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label>Website Link</label>
                                                    <input class="form-control h-50"
                                                        id="website_link_{{ $val->pstatus . $val->id }}"
                                                        placeholder="Website Link" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-11">
                                            <label>Additional</label>
                                            <input class="form-control h-50"
                                                id="additional_{{ $val->pstatus . $val->id }}"
                                                placeholder="Additional" value="">
                                        </div>
                                        <div class="col-md-1 mt-auto">
                                            <!--<button type="button" class="bt btn-info fa fa-eye mt-2 fs-25"></button>-->
                                            <button onclick="completedUpload({{ $val->pstatus . $val->id }})"
                                                type="button" class="bt btn-primary fa fa-upload fs-25"></button>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        </td>

                    </tr>
                @endif
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
<div class="modal fade" id="approval_pick_deliver" tabindex="-1" role="dialog"
    aria-labelledby="approval_pick_deliverLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approval_pick_deliverLabel"></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-dismiss="modal">No</button>
                <a href="#" id="submitting_approval" class="btn btn-outline-success">Yes</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="assignToDispatcher" tabindex="-1" role="dialog"
    aria-labelledby="assignToDispatcherTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignToDispatcherLongTitle">Assign To</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('/assign_to_dispatcher') }}" method="POST">
                @csrf
                <input type="hidden" name="order_id" id="assigning_dispatcher_order" />
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="assigning_dispatcher" class="form-label">Assign To</label>
                                <?php
                                $dis = \App\User::with('daily_ass')
                                    ->whereHas('userRole', function ($q) {
                                        $q->where('name', 'Dispatcher');
                                    })
                                    ->where('deleted', 0)
                                    ->get();
                                ?>
                                <select name="assigning_dispatcher" id='assigning_dispatcher' class="form-control"
                                    required>
                                    <option value="" selected disabled>Select</option>
                                    @foreach ($dis as $key => $dispa)
                                        <option value="{{ $dispa->id }}">
                                            {{ $dispa->slug ?? $dispa->name . ' ' . $dispa->last_name }}
                                            ({{ isset($dispa->daily_ass->total_quote) ? $dispa->daily_ass->total_quote . ' Left' : 'Unlimited' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="specialIns" tabindex="-1" aria-labelledby="specialInsLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="specialInsModelLabel">Special Instructions</h5>
            </div>
            <form action="{{ url('/special_instructions') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="instr_id" name="order_id" />
                    <div class="form-group">
                        <label for="instruction" class="form-label">Special Instructions</label>
                        <textarea required class="form-control" name="instruction" id="instruction"
                            placeholder="Enter Special Instruction" rows="12" cols="12"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="$('#exampleModal').modal('show')"
                        data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal8" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel8"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Carrier Approaching Details For: <span class="show_order_id"></span>
                </h5>
            </div>
            <form action="{{ route('store.carrier_approachings') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="val_order_id" name="order_id" />

                    <div class="form-group row">
                        <label for="extension" class="col-sm-4 col-form-label">Extension</label>
                        <div class="col-sm-8">
                            <input type="text" name="extension" id="extension" class="form-control col-12"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="comp_name" class="col-sm-4 col-form-label">Company Name</label>
                        <div class="col-sm-8">
                            <input type="text" name="comp_name" id="comp_name" class="form-control col-12"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="comp_phone" class="col-sm-4 col-form-label">Company Phone</label>
                        <div class="col-sm-8">
                            <input type="text" name="comp_phone" id="comp_phone" class="form-control col-12"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-sm-4 col-form-label">Status</label>
                        <div class="col-sm-8">
                            <select name="status" id="status" class="form-control col-12" required>
                                <option value="1">Interested</option>
                                <option value="0">Not Interested</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="comp_response" class="col-sm-4 col-form-label">Company Response</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="comp_response" id="comp_response" placeholder="Company's Response"
                                rows="12" cols="12" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .tx-white {
        color: white !important;
    }

    .badge-orange {
        color: #212529;
        background-color: #F49917;
    }
</style>

<script src="{{ url('assets/js/jquery-3.5.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
<script src="{{ url('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script>
    function regain_report_modal() {

        $('#reportmodal').on('show.bs.modal', function(e) {

            //get data-id attribute of the clicked element
            var orderId = $(e.relatedTarget).data('book-id');

            //populate the textbox
            var encryptvuserid = btoa({{ Auth::user()->id }});
            var encryptvoderid = btoa(orderId);
            var linkv = "{{ url('/email_order/') }}" + '/' + encryptvoderid + '/' + encryptvuserid;
            $(e.currentTarget).find('input[name="orderid"]').val(orderId);
            $(e.currentTarget).find('input[name="link"]').val(linkv);
        });


        $('#trashmodal').on('show.bs.modal', function(e) {

            var orderId = $(e.relatedTarget).data('book-id');
            $(e.currentTarget).find('input[name="orderid"]').val(orderId);

        });

        $('#modalPaid').on('show.bs.modal', function(e) {
            var orderId = $(e.relatedTarget).data('book-id');
            $(e.currentTarget).find('input[name="orderid"]').val(orderId);

            var comments = $(e.relatedTarget).data('comments');
            //  alert(comments);
            $(e.currentTarget).find('textarea[name="pay_comments"]').val(comments);


            var paid_status = $(e.relatedTarget).data('paid_status');
            if (paid_status == 0) {
                $("#status0").prop("checked", true);
            } else if (paid_status == 1) {
                $("#status1").prop("checked", true);
            } else if (paid_status == 2) {
                $("#status2").prop("checked", true);
            }

        });

    }
    regain_report_modal();

    function listedUpload(id) {
        let oid = $('#orderId_' + id).val();
        let paid = $('#paid_' + id).val();
        let storage = $('#storage_' + id).val();
        let listed_price = $('#listed_price_' + id).val();
        let auction_update = $('#auction_update_' + id).val();
        let title_keys = $('#title_keys_' + id).val();
        let keys = $('#keys_' + id).val();
        let listed_count = $('#listed_count_' + id).val();
        let price = $('#price_' + id).val();
        let additional = $('#additional_' + id).val();
        let vehicle_condition = $("#condition_" + id).val();

        $.ajax({
            url: window.location.origin + "/listed_sheet/" + oid, // Url of backend (can be python, php, etc..)
            type: "GET", // data type (can be get, post, put, delete)
            data: {
                paid: paid,
                storage: storage,
                listed_price: listed_price,
                auction_update: auction_update,
                title_keys: title_keys,
                keys: keys,
                listed_count: listed_count,
                price: price,
                additional: additional,
                vehicle_condition: vehicle_condition
            }, // data in json format
            async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
            success: function(response) {
                if (response === 'true') {
                    Swal.fire(
                        'Success!',
                        'Listed Sheet Updated!',
                        'success'
                    )
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {}
        });
    }

    function call(num) {
        var num1 = atob(num);
        //  var newNum = num1.replace(/[- )(]/g,'');
        //  console.log(num1);
        window.location.href = 'rcmobile://call/?number=' + num1;
        //  window.location.href = 'tel://'+newNum;
        var id = $("#orderId").val();
        $.ajax({
            url: "{{ url('/notRes') }}",
            type: "GET",
            data: {
                id: id
            },
            success: function(res) {
                console.log(res);
            }
        });
    }

    function call2(num) {
        var num1 = atob(num);
        //  var newNum = num1.replace(/[- )(]/g,'');
        //  console.log(num1);
        window.location.href = 'rcmobile://call/?number=' + num1;
        //  window.location.href = 'tel://'+newNum;
    }

    function msg(num) {
        var num1 = atob(num);
        window.location.href = 'rcmobile://sms/?number=' + num1;
    }

    function dispatchUpload(id) {
        let oid = $('#orderId_' + id).val();
        let pickedup = $('#pickedup_' + id).val();
        let delivery_date = $('#delivery_date_' + id).val();
        let driver_fmcsa = $('#driver_fmcsa_' + id).val();
        let carrier_rating = $('#carrier_rating_' + id).val();
        let fmcsa = $('#fmcsa_' + id).val();
        let coi_holder = $('#coi_holder_' + id).val();
        let vehicle_luxury = $('#vehicle_luxury_' + id).val();
        let aware_driver_delivery_date = $('#aware_driver_delivery_date_' + id).val();
        let new_old_driver = $('#new_old_driver_' + id).val();
        let is_local = $('#is_local_' + id).val();
        let job_accept = $('#job_accept_' + id).val();
        let condition = $('#condition_' + id).val();
        let title_keys = $('#title_keys_' + id).val();
        let keys = $('#keys_' + id).val();
        let storage = $('#storage_' + id).val();
        let auction_update = $('#auction_update_' + id).val();
        let who_pay_storage = $('#who_pay_storage_' + id).val();
        let vehicle_position = $('#vehicle_position_' + id).val();
        let payment_method = $('#payment_method_' + id).val();
        let stamp_dock_receipt = $('#stamp_dock_receipt_' + id).val();
        let company_name = $('#company_name_' + id).val();
        let price = $('#price_' + id).val();
        let insurance_date = $('#insurance_date_' + id).val();
        let additional = $('#additional_' + id).val();

        $.ajax({
            url: window.location.origin + "/dispatch_sheet/" +
                oid, // Url of backend (can be python, php, etc..)
            type: "GET", // data type (can be get, post, put, delete)
            data: {
                stamp_dock_receipt: stamp_dock_receipt,
                company_name: company_name,
                payment_method: payment_method,
                price: price,
                insurance_date: insurance_date,
                pickup_date: pickedup,
                delivery_date: delivery_date,
                driver_fmcsa: driver_fmcsa,
                carrier_rating: carrier_rating,
                fmcsa: fmcsa,
                coi_holder: coi_holder,
                vehicle_luxury: vehicle_luxury,
                aware_driver_delivery_date: aware_driver_delivery_date,
                new_old_driver: new_old_driver,
                is_local: is_local,
                job_accept: job_accept,
                vehicle_condition: condition,
                title_keys: title_keys,
                keys: keys,
                storage: storage,
                auction_update: auction_update,
                who_pay_storage: who_pay_storage,
                vehicle_position: vehicle_position,
                additional: additional
            }, // data in json format
            async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
            success: function(response) {
                if (response === 'true') {
                    Swal.fire(
                        'Success!',
                        'Dispatch Sheet Updated!',
                        'success'
                    )
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {}
        });
    }

    function pickedUpUpload(id) {
        let oid = $('#orderId_' + id).val();
        let driver_status = $('#driver_status_' + id).val();
        let delivery_date = $('#delivery_date_' + id).val();
        let storage = $('#storage_' + id).val();
        let condition = $('#condition_' + id).val();
        let title_keys = $('#title_keys_' + id).val();
        let keys = $('#keys_' + id).val();
        let vehicle_position = $('#vehicle_position_' + id).val();
        let payment_charged_or_owes = $('#payment_charged_or_owes_' + id).val();
        let payment_method = $('#payment_method_' + id).val();
        let price = $('#price_' + id).val();
        let carrier_name = $('#carrier_name_' + id).val();
        let driver_payment = $('#driver_payment_' + id).val();
        let driver_no = $('#driver_no_' + id).val();
        let driver_no2 = $('#driver_no2_' + id).val();
        let driver_no3 = $('#driver_no3_' + id).val();
        let driver_no4 = $('#driver_no4_' + id).val();
        let payment = $('#payment_' + id).val();
        let company_name = $('#company_name_' + id).val();
        let additional = $('#additional_' + id).val();

        $.ajax({
            url: window.location.origin + "/pickedup_sheet/" +
                oid, // Url of backend (can be python, php, etc..)
            type: "GET", // data type (can be get, post, put, delete)
            data: {
                company_name: company_name,
                driver_no: driver_no,
                price: price,
                driver_no2: driver_no2,
                driver_no3: driver_no3,
                driver_no4: driver_no4,
                driver_payment: driver_payment,
                carrier_name: carrier_name,
                payment_method: payment_method,
                payment_charged_or_owes: payment_charged_or_owes,
                delivery_date: delivery_date,
                driver_status: driver_status,
                storage: storage,
                vehicle_condition: condition,
                title_keys: title_keys,
                keys: keys,
                vehicle_position: vehicle_position,
                payment: payment,
                additional: additional
            }, // data in json format
            async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
            success: function(response) {
                if (response === 'true') {
                    Swal.fire(
                        'Success!',
                        'Driver Picked Up Sheet Updated!',
                        'success'
                    )
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {}
        });
    }

    function auctionpickedUpUpload(id) {
        let oid = $('#orderId_' + id).val();
        let storage = $('#storage1_' + id).val();
        let condition = $('#condition1_' + id).val();
        let title_keys = $('#title_keys1_' + id).val();
        let keys = $('#keys1_' + id).val();
        let vehicle_position = $('#vehicle_position1_' + id).val();
        let auction_status = $('#auction_status1_' + id).val();
        let additional = $('#additional1_' + id).val();

        $.ajax({
            url: window.location.origin + "/auction_pickedup_sheet/" +
                oid, // Url of backend (can be python, php, etc..)
            type: "GET", // data type (can be get, post, put, delete)
            data: {
                storage: storage,
                vehicle_condition: condition,
                title_keys: title_keys,
                keys: keys,
                vehicle_position: vehicle_position,
                auction_status: auction_status,
                additional: additional
            }, // data in json format
            async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
            success: function(response) {
                if (response === 'true') {
                    Swal.fire(
                        'Success!',
                        'Auction Picked Up Sheet Updated!',
                        'success'
                    )
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {}
        });
    }

    function deliveryUpload(id) {
        let oid = $('#orderId_' + id).val();
        let driver_no = $('#driver_no_' + id).val();
        let driver_no2 = $('#driver_no2_' + id).val();
        let driver_no3 = $('#driver_no3_' + id).val();
        let driver_no4 = $('#driver_no4_' + id).val();
        let driver_payment_status = $('#driver_payment_status_' + id).val();
        let vehicle_condition = $('#condition_' + id).val();
        let vehicle_position = $('#vehicle_position_' + id).val();
        let customer_informed = $('#customer_informed_' + id).val();
        let who_pay_storage = $('#who_pay_storage_' + id).val();
        let title_keys = $('#title_keys_' + id).val();
        let keys = $('#keys_' + id).val();
        let delivery_date = $('#delivery_date_' + id).val();
        let client_status = $('#client_status_' + id).val();
        let driver_status = $('#driver_status_' + id).val();
        let owes_status = $('#owes_status_' + id).val();
        let additional = $('#additional_' + id).val();

        $.ajax({
            url: window.location.origin + "/delivery_sheet/" +
                oid, // Url of backend (can be python, php, etc..)
            type: "GET", // data type (can be get, post, put, delete)
            data: {
                customer_informed: customer_informed,
                vehicle_condition: vehicle_condition,
                driver_payment_status: driver_payment_status,
                driver_no: driver_no,
                driver_no2: driver_no2,
                driver_no3: driver_no3,
                driver_no4: driver_no4,
                vehicle_position: vehicle_position,
                delivery_date: delivery_date,
                who_pay_storage: who_pay_storage,
                client_status: client_status,
                title_keys: title_keys,
                keys: keys,
                driver_status: driver_status,
                owes_status: owes_status,
                additional: additional
            }, // data in json format
            async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
            success: function(response) {
                if (response === 'true') {
                    Swal.fire(
                        'Success!',
                        'Delivery Sheet Updated!',
                        'success'
                    )
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {}
        });
    }

    function completedUpload(id) {
        let oid = $('#orderId_' + id).val();
        let remarks = $('#remarks_' + id).val();
        let comments = $('#comments_' + id).val();
        let satisfied = $('#satisfied_' + id).val();
        let review = $('#review_' + id).val();
        let website = $('#website_' + id).val();
        let website_other = $('#website_other_' + id).val();
        let website_link = $('#website_link_' + id).val();
        let client_rating = $('#client_rating_' + id).val();
        let additional = $('#additional_' + id).val();

        $.ajax({
            url: window.location.origin + "/completed_sheet/" +
                oid, // Url of backend (can be python, php, etc..)
            type: "GET", // data type (can be get, post, put, delete)
            data: {
                remarks: remarks,
                comments: comments,
                satisfied: satisfied,
                review: review,
                website: website,
                website_other: website_other,
                website_link: website_link,
                client_rating: client_rating,
                additional: additional
            }, // data in json format
            async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
            success: function(response) {
                if (response === 'true') {
                    Swal.fire(
                        'Success!',
                        'Completed Sheet Updated!',
                        'success'
                    )
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {}
        });
    }

    function get_prev(o_zip1, d_zip1) {


        var ozip = o_zip1;
        var dzip = d_zip1;


        if (!ozip || !dzip) {
            not7();

        } else {
            ozip = ozip.split(", ");
            dzip = dzip.split(", ");

            var url = `/old_previous?ocity=${btoa(ozip[0])}&dcity=${btoa(dzip[0])}`;
            window.open(url, 'Previous Orders',
                'height=600,width=800,left=300,top=100,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No'
            );

        }

    }

    function history(order_id, ophone) {



        if (!order_id || !ophone) {
            not7();

        } else {

            var url = `/get_last_5_2?phone_no=${btoa(ophone)}&order_id=${btoa(order_id)}`;
            window.open(url, 'Previous Orders',
                'height=400,width=600,left=350,top=250,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No'
            );

        }

    }
</script>

<script>
    var getData = (id) => {
        $.ajax({
            url: "{{ url('/get_shipment_status_order_detail') }}",
            type: "GET",
            data: {
                id: id
            },
            dataType: "HTML",
            success: function(res) {
                $("#detail_order").html('');
                $("#detail_order").html(res);
                $("#instr_id").val(id);
                $("#specialInsModelLabel").html(`Special Instruction of Order Id#${id}`);
                $("#specialInstruction").html(
                    `<button type="button" onclick="$('#exampleModal').modal('hide');" class="btn btn-primary" data-toggle="modal" data-target="#specialIns">Special Instruction</button>`
                );
            }
        });
    }

    var getData2 = (id) => {
        $.ajax({
            url: "{{ url('/show_last_two_history') }}",
            type: "GET",
            data: {
                id: id
            },
            dataType: "html",
            success: function(res) {
                $("#viewCancelHistoryTitle").html(
                    `View Cancel History Of OrderId#<span class="text-primary ml-1">${id}</span>`);
                $("#cancel_history").html('');
                $("#cancel_history").html(res);
            }
        });
    }

    var getData3 = (id) => {
        $("#order_id_request").val(id);
    }

    $(".readmore").on('click', function() {
        $(this).parents('.less').hide();
        $(this).parents('.less').siblings('.more').show();
    });

    $(".readless").on('click', function() {
        $(this).parents('.more').hide();
        $(this).parents('.more').siblings('.less').show();
    });

    // $(".add-approaching").click(function() {
    //     var order_id = $(this).find('.Get-Order-ID').val();

    //     console.log('order_id', order_id);

    //     $(".show_order_id").html(order_id);
    //     $("#val_order_id").val(order_id);

    //     $.ajax({
    //         url: '{{ route('get.carrier_approachings') }}',
    //         type: 'GET',
    //         data: {
    //             'order_id': order_id,
    //         },
    //         success: function(data) {
    //             // Handle the success response
    //             console.log('datas', data);
    //         },
    //         error: function(error) {
    //             // Handle the error response
    //             console.error('Error submitting the form:', error);
    //             // Optionally, you can display an error message or take other actions
    //         }
    //     });
    // });
</script>
