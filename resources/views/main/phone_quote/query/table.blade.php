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
            <th width="15%">Customer</th>
            <th width="15%">Dates</th>
        </tr>
        </thead>
        <tbody>
        @php
            $check_panel = check_panel();
            $check_call = check_call();

         if ($check_panel == 1) {
             $phoneaccess = explode(',', Auth::user()->emp_access_phone);
         } elseif ($check_panel == 3) {
             $phoneaccess = explode(',', Auth::user()->emp_access_test);
         } else {
             $phoneaccess = explode(',', Auth::user()->emp_access_web);
         }
         $phoneaccessJson = json_encode($phoneaccess);
         if ($check_panel == 3) {
             // dd($phoneaccessJson);
         }
         $actionaccess = explode(',', Auth::user()->emp_access_action);
        @endphp

        @foreach ($data as $key => $val)
            <tr class="parent1{{ $key }}">
                <td>
                    <input type="hidden" class='check_call_type' value="{{ $check_call }}">
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


                    @if (!empty($val->oaddress))
                        <a data-placement="bottom" class="table1ancher" title="{{ $val->oaddress }}">
                            <i class="fa fa-location-arrow" style="color:green;" aria-hidden="true"></i>
                            <span>{{ $val->oaddress }} </span>
                        </a>
                    @endif
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
                    @if ($val->daddress)
                        <a data-placement="bottom" title="{{ $val->daddress }}" class="table1ancher">
                            <i class="fa fa-location-arrow" style="color:red;" aria-hidden="true"></i>
                            <span> {{ $val->daddress }} </span>
                        </a>
                    @endif

                </td>
                    <?php $ymk = explode('*^-', $val->ymk); ?>
                    <?php
                    $standardized = str_replace('*^-', '*^', $val->ymk);
                    $ymk = explode('*^', $standardized);
                    // dd($val->ymk, $standardized, $ymk);
                    ?>
                <td class="table1td">
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
                    <b> Query ID# </b> <span><?php echo $val->id; ?></span>
                    <br>
                    <b> Creator:</b>

                   <span class="badge
                        <?php
                        if ($val->paneltype == 1) {
                            echo 'badge-secondary my-2';
                        } elseif ($val->paneltype == 2) {
                            echo 'badge-primary my-2';
                        } elseif ($val->paneltype == 3) {
                            echo 'badge-info my-2';
                        } elseif ($val->paneltype == 4) {
                            echo 'badge-primary my-2';
                        } elseif ($val->paneltype == 5) {
                            echo 'badge-primary my-2';
                        } elseif ($val->paneltype == 6) {
                            echo 'badge-primary my-2';
                        } else {
                            echo 'badge-secondary my-2';
                        }
                        ?>">
                            {{ $val->paneltype == 1
                                ? 'Phone Quote'
                                : ($val->paneltype == 2
                                    ? 'Website Quote'
                                    : ($val->paneltype == 3
                                        ? 'Testing Quote'
                                        : ($val->paneltype == 4
                                            ? 'Panel Type 4 Quote'
                                            : ($val->paneltype == 5
                                                ? 'Panel Type 5 Quote'
                                                : ($val->paneltype == 6
                                                    ? 'Panel Type 6 Quote'
                                                    : 'Phone Quote'))))) }}
                        </span>

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
                </td>
                <td class="=table1td">
                    @if(!empty($val->ophone))
                            <?php
                            $digits = \App\PhoneDigit::first();
                            if (in_array('61', $phoneaccess)) {
                                $new = $val->ophone;
                            } else {
                                $new = putX($digits->hide_digits, $digits->left_right_status, $val->ophone);
                            }
                            ?>
                        <span class="badge badge-primary mb-2">
                            <a onclick="call('{{ base64_encode($val->ophone) }}', '{{ $val->id }}')"
                               class="btn btn-outline-info mobile count_user"
                               style="padding: 3px 5px; font-size: 16px;">
                                <i class="fa fa-phone"></i>
                                <span>{{ $new }}</span>
                            </a>
                        </span><br>
                        <span class="badge badge-success mb-2">
                            <a class="btn btn-outline-info  sms mb-2" onclick="msg('{{ base64_encode($val->ophone) }}')"
                               style="padding: 3px 5px; font-size: 16px;"><i
                                        class="fa fa-envelope"></i>&nbsp;{{ $new }}
                            </a>
                        </span><br>
                        @if (in_array('104', $phoneaccess))
                            <span class="badge badge-success mb-2">
                                <a onclick="openWhatsApp('{{ $val->ophone }}', '{{ $val->id }}')"
                                   class="btn btn-success" style="padding: 3px 5px; font-size: 16px;">
                                    <i class="fa fa-whatsapp"></i>
                                    <span>{{ $new }}</span>
                                </a>
                            </span>
                        @endif
                        <script>
                            function openWhatsApp(phone, query_id) {
                                const formattedPhone = '+' + phone.replace(/[^\d]+/g, '');
                                const whatsappLink = `https://wa.me/${formattedPhone}`;
                                window.open(whatsappLink, '_blank');
                                $.ajax({
                                    url: '{{ route('shipa1_query.phone.count_dealer') }}',
                                    type: 'GET',
                                    data: {
                                        'query_id': query_id,
                                        'type': 2,
                                    },
                                    success: function(data) {

                                    },
                                    error: function(data) {
                                        var errors = data.responseJSON;
                                    }
                                });
                            }
                        </script>

                    @endif

                    <br>
                    <b> Customer Name:</b>
                    {{$val->oname }}
                    <br>
                    <b> Customer Email:</b>
                    {{$val->oemail }}
                </td>
                <td class="table1td">
                    <b> Created At:</b>
                    <br>{{ \Carbon\Carbon::parse($val->created_at)->format('M,d Y') }}<br>{{ \Carbon\Carbon::parse($val->created_at)->format('h:i A') }}<br><br>
                    <b>Updated At:</b>
                    <br>{{ \Carbon\Carbon::parse($val->updated_at)->format('M,d Y') }}<br>{{ \Carbon\Carbon::parse($val->updated_at)->format('h:i A') }}<br><br>
                    <span class="text-center pd-2 bd-l mt-2">

                    @if ($val->oemail)
                        <button type="button" class="btn btn-info send-email">Send Email
                            <input hidden type="text" class="Email-Address" value="{{ $val->oemail }}">
                        </button>
                        @php
                            $emailHistory = \App\EmailHistory::where('recipient', $val->oemail)
                                ->orderby('id', 'DESC')
                                ->first();
                        @endphp

                        <a href="javascript:void(0)" class="view_email_history btn btn-info" data-toggle="modal"
                           data-target="#exampleModal11">
                            <input hidden type="text" class="History-Email-Address"
                                   value="{{ $val->oemail }}">
                            View Email History
                        </a>
                    @endif

                    @if (Auth::user()->role === 2 || Auth::user()->role === 1 || Auth::user()->role === 9)
                        <button type="button" class="btn btn-primary add-history" data-toggle="modal"
                                data-target="#exampleModal8">Add History
                            <input hidden type="text" class="Company-ID" value="{{ $val->id }}">
                            <input hidden type="text" class="Company-Name" value="{{ $val->oname }}">
                        </button>
                    @endif

                    @if ((int) Auth::user()->role === 1)
                        <button type="button" class="btn btn-warning btn-sm mt-1 assign-ot-btn"
                                data-query-id="{{ $val->id }}"
                                data-query-label="Query #{{ $val->id }} — {{ $val->oname }}"
                                data-toggle="modal" data-target="#assignOtModal">
                            <i class="fa fa-user-plus"></i> Assign OT
                        </button>
                        @if ($val->user)
                            <div class="mt-1">
                                <small class="text-success font-weight-bold ot-assigned-badge">
                                    <i class="fa fa-user"></i> <span class="ot-name-text">{{ $val->user->name }}</span>
                                </small>
                            </div>
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

<div class="modal fade" id="exampleModal11" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel8"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <h5 class="modal-title" id="exampleModalLabel7">Employee Access (Assign
                    Data)</h5> --}}

                <h5 class="modal-title" id="exampleModalLabel">Email History For: <span
                            class="history_id"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="form-group email-history-content">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal8" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel8"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <h5 class="modal-title" id="exampleModalLabel7">Employee Access (Assign
                    Data)</h5> --}}

                <h5 class="modal-title" id="exampleModalLabel">Add History For: <span class="history_id"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="form-group">
                        <form id="addHistoryForm" action="{{ route('shipa1_query.store.call.history') }}" method="POST"
                              class="needs-validation" novalidate class="tablelist-form" autocomplete="off">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="CompanyID" value="" class="history_val">
                                <div class="row g-3">
                                    <div class="row">
                                        <!--=============new modal===============-->
                                        <div class=" tab-menu-heading p-0 bg-light">
                                            <div class="tabs-menu1 ">
                                                <!-- Tabs -->
                                                <ul class="nav panel-tabs  gap-2">
                                                    <li class=""><a href="#tab1"
                                                                    class="active btn btn-success"
                                                                    data-toggle="tab">HISTORY/STATUS</a>
                                                    </li>
                                                    <li><a href="#tab2" data-toggle="tab"
                                                           class="btn btn-success">VIEW HISTORY</a></li>
                                                    <li></li>
                                                    <li class="position-relative">
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!--=============new modal===============-->
                                    </div>
                                    <div class="tab-pane active" id="tab1">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input"
                                                           id="connected" name="connectStatus" value="Connected"
                                                           checked>
                                                    <label class="custom-control-label form-label"
                                                           for="connected">Connected</label>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="custom-control custom-radio mb-3">
                                                    <input type="radio" class="custom-control-input"
                                                           id="notConnected" name="connectStatus"
                                                           value="Not Connected">
                                                    <label class="custom-control-label form-label"
                                                           for="notConnected">Not Connected</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div>
                                                    <label for="label-field" class="form-label">Add
                                                        Comments</label>
                                                    <textarea rows="3" name="comment" id="comment" placeholder="Enter Comments" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success"
                                                    id="add-btn close">Save</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab2">
                                    <div class="chat-body-style ChatBody" id="calhistory" style=" height:300px;">

                                        <div class="message-feed media">
                                            <div class="media-body">
                                                <div class="mf-content w-100 history-content">
                                                    {{-- <h6>Agent: Michael</h6>
                                                    <h6>STATUS: TimeQuote</h6>
                                                    <h6>Remarks: She said she has to figure out when the vehicle
                                                        will be ready. She
                                                        asked for a quote on our email so I sent her the booking
                                                        form as well.</h6>
                                                    <strong class="mf-date"><i class="fa fa-clock-o"></i> Nov,10
                                                        2023 10:51
                                                        AM</strong> --}}
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Save</button>
        </div> --}}
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

    function getIdPhone(id, status, name, phone, origin, destination, vehicle) {
        $("#authorization-orderId").val(id);
        $("#authorization-status").val(status);
        $("#authorization-cname").val(name);
        $("#authorization-cphone").val(phone);
        $("#authorization-origin").val(origin);
        $("#authorization-destination").val(destination);
        $("#authorization-vehicle").val(vehicle);

        // console.log('id', 'status', 'name', 'phone', id, status, name, phone, origin, destination);
    }
</script>
<script>
    function call(num, query_id) {
        var num1 = atob(num);

        var check_panel = '{{ $check_panel }}';
        var check_call = '{{ $check_call }}';

        if (check_call == 134) {
            var formattedNum = num1.replace(/\D/g, '');
            window.location.href = 'tel:' + formattedNum;
        } else if (check_call == 135) {
            window.location.href = 'rcmobile://call/?number=' + num1;
        }

        $.ajax({
            url: "{{ route('shipa1_query.phone.count_dealer') }}",
            type: "GET",
            data: {
                query_id: query_id,
                type: 1,
            },
            success: function(res) {
                console.log(res);
            }
        });
    }

    function msg(num) {
        var num1 = atob(num);
        window.location.href = 'rcmobile://sms/?number=' + num1;
    }

    $(".add-history").click(function() {
        var company_id = $(this).find('.Company-ID').val();
        var CompanyName = $(this).find('.Company-Name').val();

        $(".history_id").html(CompanyName);
        $(".history_val").val(company_id);

        $.ajax({
            url: '{{ route('shipa1_query.call.history') }}',
            type: 'GET',
            data: {
                'company_id': company_id,
            },
            success: function(data) {
                // Handle the success response
                console.log('datas', data);
                //showing history
                $(".history-content").html('');
                html = "";
                $.each(data, function(index, val) {
                    // Assuming val['created_at'] is a string representation of the date
                    var createdAt = new Date(val['created_at']);

                    // Format the date
                    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",
                        "Aug", "Sep", "Oct", "Nov", "Dec"
                    ];
                    var formattedDate = monthNames[createdAt.getMonth()] + "," +
                        ("0" + createdAt.getDate()).slice(-2) + " " +
                        createdAt.getFullYear() + " " +
                        ("0" + createdAt.getHours()).slice(-2) + ":" +
                        ("0" + createdAt.getMinutes()).slice(-2) +
                        (createdAt.getHours() >= 12 ? " PM" : " AM");

                    // Append formatted date to HTML
                    html += "<h6>" + val['user']['name'] + "</h6>";
                    html += "<h6>" + val['connectStatus'] + "</h6>";
                    html += "<h6>" + val['comment'] + ".</h6>";
                    html += "<strong class='mf-date'><i class='fa fa-clock-o'></i>" +
                        formattedDate + "</strong> <hr>";
                });
                $(".history-content").html(html);
                // resetting form
            },
            error: function(error) {
                // Handle the error response
                console.error('Error submitting the form:', error);
                // Optionally, you can display an error message or take other actions
            }
        });
    });

    $(".add-email").click(function() {
        var company_id = $(this).find('.Company-ID').val();
        var CompanyName = $(this).find('.Company-Name').val();

        $(".history_id").html(CompanyName);
        $(".history_val").val(company_id);

    });

    // Add history with ajax
    $("#addHistoryForm").submit(function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Serialize the form data
        var formData = $(this).serialize();

        // Perform AJAX submission
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(data) {
                // Handle the success response
                console.log('data', data);
                //showing history
                $(".history-content").html('');
                html = "";
                $.each(data, function(index, val) {
                    // Assuming val['created_at'] is a string representation of the date
                    var createdAt = new Date(val['created_at']);

                    // Format the date
                    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",
                        "Aug", "Sep", "Oct", "Nov", "Dec"
                    ];
                    var formattedDate = monthNames[createdAt.getMonth()] + "," +
                        ("0" + createdAt.getDate()).slice(-2) + " " +
                        createdAt.getFullYear() + " " +
                        ("0" + createdAt.getHours()).slice(-2) + ":" +
                        ("0" + createdAt.getMinutes()).slice(-2) +
                        (createdAt.getHours() >= 12 ? " PM" : " AM");

                    // Append formatted date to HTML
                    html += "<h6>" + val['user']['name'] + "</h6>";
                    html += "<h6>" + val['connectStatus'] + "</h6>";
                    html += "<h6>" + val['comment'] + ".</h6>";
                    html += "<strong class='mf-date'><i class='fa fa-clock-o'></i>" +
                        formattedDate + "</strong> <hr>";
                });
                $(".history-content").html(html);
                // resetting form
                $('#addHistoryForm')[0].reset();

                location.reload();
            },
            error: function(error) {
                // Handle the error response
                console.error('Error submitting the form:', error);
                // Optionally, you can display an error message or take other actions
            }
        });
    });
</script>

{{-- Assign OT Modal --}}
<div class="modal fade" id="assignOtModal" tabindex="-1" role="dialog" aria-labelledby="assignOtModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="assignOtModalLabel">Assign Order Taker</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-2" id="assignOtQueryLabel"></p>
                <input type="hidden" id="assignOtQueryId">
                <div class="form-group">
                    <label>Search User</label>
                    <input type="text" id="assignOtSearch" class="form-control" placeholder="Type name or email…">
                </div>
                <ul id="assignOtList" class="list-group mt-2" style="max-height:220px;overflow-y:auto;"></ul>
                <div id="assignOtResult" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var _assignOtQueryId = null;
    var _assignOtDebounce = null;

    // When button clicked — set query id and label on modal
    $(document).on('click', '.assign-ot-btn', function () {
        _assignOtQueryId = $(this).data('query-id');
        $('#assignOtQueryId').val(_assignOtQueryId);
        $('#assignOtQueryLabel').text($(this).data('query-label'));
        $('#assignOtSearch').val('');
        $('#assignOtList').empty();
        $('#assignOtResult').html('');
    });

    // Debounced search as user types
    $(document).on('input', '#assignOtSearch', function () {
        clearTimeout(_assignOtDebounce);
        var q = $(this).val().trim();
        var $list = $('#assignOtList');
        if (q.length < 1) { $list.empty(); return; }
        _assignOtDebounce = setTimeout(function () {
            $.get('{{ route("shipa1_query.search_order_takers") }}', { q: q }, function (users) {
                $list.empty();
                if (!users.length) {
                    $list.append('<li class="list-group-item text-muted">No users found</li>');
                    return;
                }
                $.each(users, function (i, u) {
                    $list.append(
                        '<li class="list-group-item list-group-item-action ot-select-item" ' +
                        'data-user-id="' + u.id + '" data-user-name="' + $('<span>').text(u.name).html() + '" ' +
                        'style="cursor:pointer">' +
                        '<strong>' + $('<span>').text(u.name).html() + '</strong> ' +
                        '<small class="text-muted">' + $('<span>').text(u.email).html() + '</small>' +
                        '</li>'
                    );
                });
            });
        }, 300);
    });

    // When a user row is clicked — assign
    $(document).on('click', '.ot-select-item', function () {
        var userId   = $(this).data('user-id');
        var userName = $(this).data('user-name');
        var queryId  = $('#assignOtQueryId').val();
        var $result  = $('#assignOtResult');

        $result.html('<span class="text-muted">Assigning…</span>');

        $.ajax({
            url: '{{ route("shipa1_query.assign_direct") }}',
            type: 'POST',
            data: { query_id: queryId, user_id: userId, _token: '{{ csrf_token() }}' },
            success: function (res) {
                if (res.success) {
                    $result.html('<div class="alert alert-success py-1 mb-0">Assigned to <strong>' +
                        $('<span>').text(res.assigned_to).html() + '</strong></div>');
                    $('#assignOtList').empty();
                    $('#assignOtSearch').val('');
                    // Update the name badge inline on the button row
                    var $btn = $('.assign-ot-btn[data-query-id="' + queryId + '"]');
                    var $badge = $btn.siblings('.ot-assigned-badge');
                    if ($badge.length) {
                        $badge.find('.ot-name-text').text(res.assigned_to);
                    } else {
                        $btn.after('<div class="mt-1"><small class="text-success font-weight-bold ot-assigned-badge"><i class="fa fa-user"></i> <span class="ot-name-text">' +
                            $('<span>').text(res.assigned_to).html() + '</span></small></div>');
                    }
                    setTimeout(function () { $('#assignOtModal').modal('hide'); location.reload(); }, 1200);
                } else {
                    $result.html('<div class="alert alert-danger py-1 mb-0">Failed to assign.</div>');
                }
            },
            error: function () {
                $result.html('<div class="alert alert-danger py-1 mb-0">Server error. Please try again.</div>');
            }
        });
    });
}());
</script>


