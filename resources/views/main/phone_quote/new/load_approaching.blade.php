@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]", '/');
if (isset($_GET['titlee'])) {
    $respn = $_GET['titlee'];
}
?>
@php
    $check_panel = check_panel();
     $check_call = check_call();
     

     if ($check_panel == 1) {
        $phoneaccess = explode(',', Auth::user()->emp_access_phone);
    } elseif ($check_panel == 2) {
        $phoneaccess = explode(',', Auth::user()->emp_access_web);
    } elseif ($check_panel == 3) {
        $phoneaccess = explode(',', Auth::user()->emp_access_test);
    } elseif ($check_panel == 4) {
        $phoneaccess = explode(',', Auth::user()->panel_type_4);
    } elseif ($check_panel == 5) {
        $phoneaccess = explode(',', Auth::user()->panel_type_5);
    } elseif ($check_panel == 6) {
        $phoneaccess = explode(',', Auth::user()->panel_type_6);
    } else {
        $phoneaccess = []; // Default case if $ptype is not within 1-6
    }
@endphp
<style>
    /*.table-bordered {*/
    /*    font-size: 13px; !important;*/
    /*}*/
    /*.badge{*/
    /*    font-size: 14px!important;*/
    /*}*/
    /*.table-bordered, .text-wrap table, .table-bordered th, .text-wrap table th, .table-bordered td, .text-wrap table td {*/
    /*    border: 1px solid rgb(0 0 0) !important;*/
    /*}*/
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
<div class="table-responsive">
    {{-- example1 --}}
    <table class="table table-bordered table-sm col-lg-2 fs-18 text-center pd-2 bd-l" role="grid" aria-describedby="">
        <thead class="table-dark">
            <tr>
                <th width="15%">Client Name</th>
                @if (in_array('42', $phoneaccess))
                    <th width="15%">Number</th>
                @endif
                <th>Last Status</th>
                <th width="15%">Last Time</th>
                <th width="15%">Entry Count</th>
                <th width="10%">Histroy</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paginatedData as $key => $val)
                <tr class="parent1{{ $key }}">
                    <input type="hidden" class='order_id' value="{{ $val->id }}">
                    <input type="hidden" class="pstatus" value="{{ $val->pstatus }}">
                    <input type="hidden" class="client_email" value="{{ $val->oemail }}">
                    <input type="hidden" class="client_name" value="{{ $val->oname }}">
                    <input type="hidden" class="client_phone" value="{{ $val->main_ph }}">
                    <input type="hidden" class="location1" value="{{ $val->originzsc }}">
                    <input type="hidden" class="location2" value="{{ $val->destinationzsc }}">
                    <input type="hidden" class="origincity" value="{{ $val->origincity }}">
                    <input type="hidden" class="destinationcity" value="{{ $val->destinationcity }}">
                    <td>
                        {{ $val->oname ? $val->oname : 'N/A' }}
                        @if (Auth::user()->role == 1)
                            <a href="/searchData?search={{ $val->mainPhNum ? $val->mainPhNum : $val->main_ph }}"
                                data-placement="top" target="_blank" class="btn btn-outline-info btn-sm w-100">
                                Show Previous
                            </a>
                            <!-- Display summarized Order IDs -->
                            Order IDs:
                            {{ \Illuminate\Support\Str::limit($val->order_ids, 10, '...') }}
                            <a href="javascript:void(0);" class="view-order-ids" data-order-ids="{{ $val->order_ids }}">View All</a>

                        @endif
                    </td>
                    @if (in_array('42', $phoneaccess))
                        <td>
                            <?php $ophone = explode('*^', $val->ophone); ?>

                            @foreach ($ophone as $val3)
                                <?php
                                if (in_array('67', $phoneaccess)) {
                                    $new = $val3;
                                } else {
                                    $digits = \App\PhoneDigit::first();
                                
                                    $new = putX($digits->hide_digits, $digits->left_right_status, $val3);
                                }
                                
                                ?>
                                @if ($val3)
                                    <input type="hidden" id="orderId" value="{{ $val->id }}" />
                                    <span class="text-center pd-2 bd-l">
                                        <a onclick="call('{{ base64_encode($val3) }}')"
                                            class="btn btn-outline-info mobile count_user"
                                            style="padding: 3px 5px; font-size: 16px;">
                                            <i class="fa fa-phone"></i>
                                            <span class="">{{ $new }}</span>
                                        </a><br>
                                    </span>
                                    <span class="text-center pd-2 bd-l">
                                        <a class="btn btn-outline-info  sms mb-2"
                                            onclick="msg('{{ base64_encode($val3) }}')"
                                            style="padding: 3px 5px; font-size: 16px;"><i class="fa fa-envelope"></i>
                                            <span class="">{{ $new }}</span>
                                        </a><br>
                                    </span>
                                @endif
                            @endforeach
                        </td>
                    @endif
                    <td>
                        <span class="text-center pd-2 bd-l mt-2">
                            {{ strip_tags(get_pstatus2($val->pstatus)) }}
                            @if (!empty($val->old_code))
                                - Old Quote
                            @endif
                        </span>
                    </td>
                    <td>Created At:
                        <br>{{ \Carbon\Carbon::parse($val->created_at)->format('M,d Y') }}<br>{{ \Carbon\Carbon::parse($val->created_at)->format('h:i A') }}
                    </td>
                    <td>
                        @php
                            $date = \Carbon\Carbon::now();
                            $to = \Carbon\Carbon::parse($date);
                            $from = \Carbon\Carbon::parse($val->created_at);
                            $days = $to->diffInDays($from);
                        @endphp
                        {{ $days }} Days <br />
                        @if ($val->paneltype == 2)
                            <span class="badge badge-primary">Website Quote</span>
                        @elseif($val->paneltype == 3)
                            <span class="badge badge-primary">Type: Testing Quote</span>
                        @elseif($val->paneltype == 4)
                            <span class="badge badge-primary">Type: Panel Type 4 Quote</span>
                        @elseif($val->paneltype == 5)
                            <span class="badge badge-primary">Type: Panel Type 5 Quote</span>
                        @elseif($val->paneltype == 6)
                            <span class="badge badge-primary">Type: Panel Type 6 Quote</span>
                        @else
                            <span class="badge badge-secondary">Phone Quote</span>
                        @endif

                        <br>



                            @if ($val->oemail != null && $val->oemail != '-')

                            <button type="button" class="btn btn-primary open-email-modal" data-email="{{$val->oemail}}">
                                Send Email
                            </button>

                            @php
                                    $emailHistory = \App\EmailHistory::where('recipient', $val->oemail)
                                        ->orderby('id', 'DESC')
                                        ->first();
                                @endphp
                                {{-- @if ($emailHistory != null)
                                    Last email sent: {{ $emailHistory->created_at }}
                                @endif --}}
                                <a href="javascript:void(0)" class="view_email_history" data-toggle="modal"
                                   data-target="#exampleModal11">
                                    <input hidden type="text" class="History-Email-Address"
                                           value="{{ $val->oemail }}">
                                    View History
                                </a>
                            @else
                                <form class="addEmailForm" data-compid="{{ $val->id }}">
                                    @csrf
                                    <input type="hidden" name="comp_id" value="{{ $val->id }}">
                                    <input type="email" class="emailInput" name="email" required
                                           placeholder="Enter Email">
                                    <button type="button" class="btn btn-primary submitBtn">Add</button>
                                </form>

                                <div class="alert alert-danger mt-2" style="display: none;"
                                     id="invalidEmailAlert_{{ $val->id }}">
                                    Please enter a valid email address.
                                </div>

                                <script>
                                    $(document).ready(function() {
                                        // Handle form submission with Ajax
                                        $('.addEmailForm[data-compid="{{ $val->id }}"] .submitBtn').on('click', function() {
                                            var form = $(this).closest('form');
                                            var formData = form.serialize();

                                            // Validate email here if needed
                                            var emailInput = form.find('.emailInput');
                                            var emailValue = emailInput.val();
                                            if (!isValidEmail(emailValue)) {
                                                $('#invalidEmailAlert_' + form.data('compid')).show();
                                                return;
                                            } else {
                                                $('#invalidEmailAlert_' + form.data('compid')).hide();
                                            }

                                            // Ajax request
                                            $.ajax({
                                                url: '{{ route('approaching.save.email') }}',
                                                type: 'POST',
                                                data: formData,
                                                success: function(response) {
                                                   $('#oterminal').trigger('change');
                                                },
                                                error: function(error) {
                                                    console.error('Error:', error);
                                                    // Handle errors if needed
                                                }
                                            });
                                        });

                                        function isValidEmail(email) {
                                            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                            return emailRegex.test(email);
                                        }
                                    });
                                </script>
                            @endif
                    </td>
                    <td id='order_action'>
                        <div class="btn-list">
                            <button type="button" data-placement="top" title="Order History!"
                                class="btn btn-outline-info btn-sm w-100 updatee">
                                View Order History
                            </button>
                        </div>
                        <a class="btn btn-primary btn-sm my-2"
                            onclick="history('{{ $val->id }}','{{ $ophone[0] }}')" target="_blank">History</a>
                        <br>
                        @if (isset($val->latestHistory))
                            Last Updated At:
                            <br>{{ \Carbon\Carbon::parse($val->latestHistory->created_at)->format('M,d Y') }}<br>{{ \Carbon\Carbon::parse($val->latestHistory->created_at)->format('h:i A') }}
                        @else
                            No Updated History
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <div class="text-secondary my-auto">
            Showing {{ $paginatedData->firstItem() ?? 0 }} to {{ $paginatedData->lastItem() ?? 0 }} from total {{ $paginatedData->total() }}
            entries
        </div>
        <div>
            {{ $paginatedData->links() }}
        </div>

    </div>

    <div id="emailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Email</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Email Template Dropdown -->
                    <label for="email-template">Choose Email Template:</label>
                    <select id="email-template" class="form-control">
                        
                    </select>

                    <label >Email</label>
                    <input type="input" class="form-control" id="user-email" value="">
                    <br>

                    <button type="button" class="btn btn-success mt-3 send-email">Send Email</button>
                </div>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>

    @if(count($total_count) > 0 )

        setTimeout(function() {
            $('#order_ids').val('{{ implode(',',$total_count) }}');
            $('#recordsAllowed').val('{{ count($total_count) }}');
        }, 200);
    @endif
    // regain_call();
    regain_status();
    // regain_report_modal();
    var urll = '{{ url('searchData') }}?search=';

    $('.view-order-ids').click(function() {
        var orderIds = $(this).data('order-ids'); // Get order IDs data attribute
        var orderIdsList = orderIds.split(','); // Split the order IDs into an array

        // Clear the modal list
        $('#modalOrderIdsList').empty();

        // Add the order IDs to the modal list
        orderIdsList.forEach(function(orderId) {
            // Construct the URL for each orderId
            var orderUrl = urll + orderId;

            // Append the link to the modal list
            $('#modalOrderIdsList').append('<li><a href="' + orderUrl + '" target="_blank">' + orderId + '</a></li>');
        });

        // Open the modal
        $('#orderIdsModal').modal('show');
    });


    function regain_status() {
        $(".updatee").click(async function() {
            console.log('saasasa');
            var order_id = $(this).closest('tr').find('.order_id').val();
            $("#order_id1").attr("value", order_id);
            $('#ask_low').html('');
            var id = $("#order_id1").val();
            await $.ajax({
                url: "show_call_history",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {
                    if (data.length > 0) {
                        $('#calhistory').html('');
                        $('#calhistory').html(data);
                        setTimeout(function() {
                            $("#calhistory").animate({
                                scrollTop: 20000
                            }, "slow");
                        }, 200);
                    } else {
                        $('#calhistory').html('NO HISTORY FOUND');
                    }
                    // $('#largemodal').modal('show');
                    if (data) {

                        $('#largemodal').modal('show');
                    }
                }
            });
            var titlee = $('#titlee').val();
            if (titlee == "dispatch") {
                $(".pickupdatediv").html('');
                $.ajax({
                    type: "GET",
                    url: "/get_pickup_date",
                    data: {
                        'order_id': order_id
                    },
                    dataType: "json",
                    success: function(data) {
                        $(".pickupdatediv").append(`<div class="form-group"><label class="form-label">PICKUP DATE</label>
                            <input type="date" value="` + data["driver_pickup_date"] +
                            `" required name="pickup_date"
                            id='pickup_date'class="form-control"><input type="checkbox" name="approvalpickup" value="0"/>MARK AS APPROVED</div>`
                        );
                    },
                    error: function(e) {
                        alert("error");
                    }
                });
            }
            if (titlee == "listed") {
                $("#current_carrier").empty();
                var order_id = document.getElementById('order_id1').value;
                var options = "<option selected value=''>Select Carrier</option>";
                $.ajax({
                    type: "GET",
                    url: "/get_carrier",
                    data: {
                        'order_id': order_id
                    },
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, item) {
                            if (item.id) {
                                options = options + `<option value='` + item.id + `'>` +
                                    item.companyname + `</option>`;
                            }
                        });
                        $("#current_carrier").append(options);
                    },
                    error: function(e) {
                        alert("error");
                    }
                });
            }

            if (titlee == "picked_up") {
                $(".pickupdatediv2").html('');
                $(".deliverdate").html('');
                $.ajax({
                    type: "GET",
                    url: "/get_pickup_date",
                    data: {
                        'order_id': order_id
                    },
                    dataType: "json",
                    success: function(data) {
                        $(".pickupdatediv2").append(`<div class="form-group"><label class="form-label">PICKUP DATE</label>
                            <input readonly type="text" value="` + data["driver_pickup_date"] + `"  name="pickup_date1"
                            id='pickup_date1'class="form-control"></div>`);
                        $(".deliverdate").append(`<div class="form-group"><label class="form-label">DELIVER DATE</label>
                            <input required  type="date" value="` + data["driver_deliver_date"] +
                            `"  name="deliver_date"
                            id='deliver_date'class="form-control"></br>
                            <input type="checkbox" name="approvaldeliver" value="1"/> &nbsp; Mark AS APPROVED (DELIVER)</div>`
                        );
                    },
                    error: function(e) {
                        alert("error");
                    }
                });
            }
        });
    }

    function listedUpload(id) {
        let oid = $('#orderId_' + id).val();
        let paid = $('#paid_' + id).val();
        let storage = $('#storage_' + id).val();
        let listed_price = $('#listed_price_' + id).val();
        let auction_update = $('#auction_update_' + id).val();
        let title_keys = $('#title_keys_' + id).val();
        let listed_count = $('#listed_count_' + id).val();
        let price = $('#price_' + id).val();
        let additional = $('#additional_' + id).val();

        $.ajax({
            url: window.location.origin + "/listed_sheet/" + oid, // Url of backend (can be python, php, etc..)
            type: "GET", // data type (can be get, post, put, delete)
            data: {
                paid: paid,
                storage: storage,
                listed_price: listed_price,
                auction_update: auction_update,
                title_keys: title_keys,
                listed_count: listed_count,
                price: price,
                additional: additional
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

    // function regain_call() {
    //     $(".count_user").click(function() {
    //         var order_id = $(this).closest('tr').find('.order_id').val();
    //         var pstatus = $(this).closest('tr').find('.pstatus').val();
    //         var client_email = $(this).closest('tr').find('.client_email').val();
    //         var client_name = $(this).closest('tr').find('.client_name').val();
    //         var client_phone = $(this).closest('tr').find('.client_phone').val();
    //         var data = {
    //             order_id: order_id,
    //             pstatus: pstatus,
    //             client_email: client_email,
    //             client_name: client_name
    //         };
    //         $.ajax({
    //             type: "GET",
    //             url: '/count_user',
    //             dataType: "json",
    //             data: data,
    //             success: function(response) {
    //                 if (response) {
    //                     window.location.href = "rcmobile://call?number=" + client_phone;
    //                 }
    //             }
    //         });
    //     });
    // }


    function call(num) {
        console.log('onclick call');
        var num1 = atob(num);

        var check_panel = '{{ $check_panel }}';
        var check_call = '{{ $check_call }}';

        if (check_call == 134) {
            var formattedNum = num1.replace(/\D/g, '');
            window.location.href = 'tel:' + formattedNum;
        } else if (check_call == 135) {
            window.location.href = 'rcmobile://call/?number=' + num1;
        }

        var id = $("#orderId").val();
        $.ajax({
            url: "{{ url('/notRes') }}",
            type: "GET",
            data: {
                id: id
            },
            success: function(res) {
                console.log('Response from server:', res);
            },
            error: function(err) {
                console.error('AJAX error:', err);
            }
        });
    }


    function call2(num) {
        var num1 = atob(num);
        var check_panel = '{{ $check_panel }}';
        var check_call = '{{ $check_call }}';

        if (check_call == 134) {
            var formattedNum = num1.replace(/\D/g, '');
            window.location.href = 'tel:' + formattedNum;
        } else if (check_call == 135) {
            window.location.href = 'rcmobile://call/?number=' + num1;
        }
    }

    function msg(num) {
        var num1 = atob(num);
        var check_panel = '{{ $check_panel }}';
        var check_call = '{{ $check_call }}';

        if (check_call == 134) {
            var formattedNum = num1.replace(/\D/g, '');
            // window.location.href = 'sms:' + formattedNum;
            window.location.href = 'rcmobile://sms/?number=' + num1;
        } else if (check_call == 135) {
            window.location.href = 'rcmobile://sms/?number=' + num1;
        }
    }

    function dispatchUpload(id) {
        let oid = $('#orderId_' + id).val();
        let pickedup = $('#pickedup_' + id).val();
        let condition = $('#condition_' + id).val();
        let title_keys = $('#title_keys_' + id).val();
        let storage = $('#storage_' + id).val();
        let auction_update = $('#auction_update_' + id).val();
        let who_pay_storage = $('#who_pay_storage_' + id).val();
        let vehicle_position = $('#vehicle_position_' + id).val();
        let additional = $('#additional_' + id).val();

        $.ajax({
            url: window.location.origin + "/dispatch_sheet/" +
                oid, // Url of backend (can be python, php, etc..)
            type: "GET", // data type (can be get, post, put, delete)
            data: {
                pickup_date: pickedup,
                vehicle_condition: condition,
                title_keys: title_keys,
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
        let auction_status = $('#auction_status_' + id).val();
        let driver_status = $('#driver_status_' + id).val();
        let delivery_date = $('#delivery_date_' + id).val();
        let storage = $('#storage_' + id).val();
        let condition = $('#condition_' + id).val();
        let title_keys = $('#title_keys_' + id).val();
        let vehicle_position = $('#vehicle_position_' + id).val();
        let payment = $('#payment_' + id).val();
        let additional = $('#additional_' + id).val();

        $.ajax({
            url: window.location.origin + "/pickedup_sheet/" +
                oid, // Url of backend (can be python, php, etc..)
            type: "GET", // data type (can be get, post, put, delete)
            data: {
                auction_status: auction_status,
                delivery_date: delivery_date,
                driver_status: driver_status,
                storage: storage,
                vehicle_condition: condition,
                title_keys: title_keys,
                vehicle_position: vehicle_position,
                payment: payment,
                additional: additional
            }, // data in json format
            async: false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
            success: function(response) {
                if (response === 'true') {
                    Swal.fire(
                        'Success!',
                        'Picked Up Sheet Updated!',
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
        let vehicle_position = $('#vehicle_position_' + id).val();
        let who_pay_storage = $('#who_pay_storage_' + id).val();
        let title_keys = $('#title_keys_' + id).val();
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
                driver_no: driver_no,
                vehicle_position: vehicle_position,
                delivery_date: delivery_date,
                who_pay_storage: who_pay_storage,
                client_status: client_status,
                title_keys: title_keys,
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
