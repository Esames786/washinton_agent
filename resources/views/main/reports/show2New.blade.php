@include('partials.mainsite_pages.return_function')
<?php
$emp_report = explode(',', Auth::user()->emp_access_report);
?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<h1>
    @php
        echo get_panel_name();
    @endphp
    ({{ $newCurrent + $intCurrent + $fmCurrent + $alCurrent + $not_intCurrent + $nrCurrent + $tqCurrent }})
</h1>
<div class="row" id="allData">
    @if (in_array('0', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="0">
                    New <span class="rounded badge ml-2 badge-custom"><span>{{ $new }}
                            ({{ $newCurrent }})</span>
                </div>
            </div>
        </div>
    @endif
    @if (in_array('1', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="1">
                    Interested <span class="rounded badge ml-2 badge-custom"><span>{{ $int }}
                            ({{ $intCurrent }})</span>
                </div>
            </div>
        </div>
    @endif
    @if (in_array('2', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="2">
                    Follow More <span class="rounded badge ml-2 badge-custom"><span>{{ $fm }}
                            ({{ $fmCurrent }})</span>
                </div>
            </div>
        </div>
    @endif
    @if (in_array('3', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="3">
                    Asking Low <span class="rounded badge ml-2 badge-custom"><span>{{ $al }}
                            ({{ $alCurrent }})</span>
                </div>
            </div>
        </div>
    @endif
    @if (in_array('4', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="4">
                    Not Interested <span class="rounded badge ml-2 badge-custom"><span>{{ $not_int }}
                            ({{ $not_intCurrent }})</span>
                </div>
            </div>
        </div>
    @endif
    @if (in_array('5', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="5">
                    No Response <span class="rounded badge ml-2 badge-custom"><span>{{ $nr }}
                            ({{ $nrCurrent }})</span>
                </div>
            </div>
        </div>
    @endif
    @if (in_array('6', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="6">
                    Time Quote <span class="rounded badge ml-2 badge-custom"><span>{{ $tq }}
                            ({{ $tqCurrent }})</span>
                </div>
            </div>
        </div>
    @endif
</div>
<h1 class="mt-5">Order ({{ $pm + $oa }})</h1>
<div class="row">
    @if (in_array('7', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="7">
                    Payment Missing <span class="rounded badge ml-2 badge-custom"><span>{{ $pm }}

                </div>
            </div>
        </div>
    @endif
    @if (in_array('18', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="18">
                    OnApproval <span class="rounded badge ml-2 badge-custom"><span>{{ $oa }}

                </div>
            </div>
        </div>
    @endif
    {{-- @if (in_array('8', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="8">
                    Booked <span class="rounded badge ml-2 badge-custom"><span>{{ $book }}

                </div>
            </div>
        </div>
    @endif --}}
</div>
<h1 class="mt-5">Listed ({{ $overallListed }})</h1>
<div class="row">
    <div class="col-sm-4">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="123">
                Need To Pickup <span class="rounded badge badge-custom ml-2"><span>{{ $needToPickup }}

            </div>
        </div>
    </div>
    @if (in_array('9', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="9">
                    Today Listed <span class="rounded badge badge-custom ml-2"><span>{{ $todayListed }}

                </div>
            </div>
        </div>
    @endif
    <div class="col-sm-4">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="321">
                Overall Listed <span class="rounded badge badge-custom ml-2"><span>{{ $overallListed }}

            </div>
        </div>
    </div>
</div>
<h1 class="mt-5">Schedule ({{ $schedule + $scheduleToAnother }})</h1>
<div class="row">
    @if (in_array('10', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="10">
                    Schedule <span class="rounded badge ml-2 badge-custom"><span>{{ $schedule }}

                </div>
            </div>
        </div>
    @endif
    @if (in_array('34', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="34">
                    Schedule To Another Driver <span
                        class="rounded badge ml-2 badge-custom"><span>{{ $scheduleToAnother }}

                </div>
            </div>
        </div>
    @endif
</div>
<h1 class="mt-5">Pickup ({{ $pickup + $pickupApproval }})</h1>
<div class="row">
    @if (in_array('11', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="11">
                    Pickup <span class="rounded badge ml-2 badge-custom"><span>{{ $pickup }}

                </div>
            </div>
        </div>
    @endif
    @if (in_array('30', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="30">
                    Pickup Approval <span class="rounded badge ml-2 badge-custom"><span>{{ $pickupApproval }}

                </div>
            </div>
        </div>
    @endif
</div>
<h1 class="mt-5">Delivered ({{ $scheduleForDelivery + $delivered }})</h1>
<div class="row">
    @if (in_array('32', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="32">
                    Schedule For Delivery <span
                        class="rounded badge ml-2 badge-custom"><span>{{ $scheduleForDelivery }}

                </div>
            </div>
        </div>
    @endif
    @if (in_array('12', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="12">
                    Delivered <span class="rounded badge ml-2 badge-custom"><span>{{ $delivered }}

                </div>
            </div>
        </div>
    @endif
</div>
<h1 class="mt-5">Cancel ({{ $cancel + $cancelOnApproval }})</h1>
<div class="row">
    @if (in_array('14', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="14">
                    Cancel <span class="rounded badge ml-2 badge-custom"><span>{{ $cancel }}

                </div>
            </div>
        </div>
    @endif
    @if (in_array('19', $emp_report))
        <div class="col-sm-4">
            <div class="card bg-white mb-3">
                <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                    data-value="19">
                    OnApprovalCancel <span class="rounded badge ml-2 badge-custom"><span>{{ $cancelOnApproval }}

                </div>
            </div>
        </div>
    @endif
</div>
<h1 class="mt-5">Reviews</h1>
<div class="row">
    <div class="col-sm-4">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                data-value="reviews">
                Reviews <span class="rounded badge badge-custom ml-2"><span>{{ $reviews }}</span>
            </div>
        </div>
    </div>
</div>
<h1 class="mt-5">All New User(s)</h1>
<div class="row">
    <div class="col-sm-4">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                data-value="new_customer">
                All New User(s) <span class="rounded badge badge-custom ml-2"><span>{{ $new_customer }}</span>
            </div>
        </div>
    </div>
</div>
<h1 class="mt-5">QA Reviews ({{ $qa_positive + $qa_negative }})</h1>
<div class="row">
    <div class="col-sm-4">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                data-value="qa_positive">
                Positive <span class="rounded badge badge-custom ml-2"><span>{{ $qa_positive }}</span>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes"
                data-value="qa_negative">
                Negative <span class="rounded badge badge-custom ml-2"><span>{{ $qa_negative }}</span>
            </div>
        </div>
    </div>
</div>
<div class="row" id="filters" style="display:none;">
    <div class="col-sm-2" style="display:none;" id="auction_storage">
        <label class="form-label">Auction Storage</label>
        <select class="form-control select2" id="auc_storage">
            <option value="0" selected>ALL</option>
            <option value="1">Already Storage</option>
            <option value="2">Late Pickup Storage</option>
        </select>
    </div>
    <div class="col-sm-2">
        <label for="searchBy" class="form-label">Search By</label>
        <select class="form-control" id="searchBy">
            <option value="id">Order Id</option>
            <option value="originzsc">Pickup</option>
            <option value="destinationzsc">Delivery</option>
            <option value="ymk">Vehicle Name</option>
            <option value="dauction">Port</option>
            <option value="ophone">Phone Number</option>
            <option value="oauction">Pickup Auction</option>
            <option value="dauction">Delivery Auction</option>
        </select>
    </div>
    <div class="col-sm-2">
        <label for="search" class="form-label">Search Value</label>
        <input type="text" id="search" class="form-control" placeholder="Search Value" />
    </div>
    <div class="col-sm-2">
        <label for="source" class="form-label">Source</label>
        <select class="form-control" id="source">
            <option value="">All</option>
            <option value="DayDispatch">DD</option>
            <option value="ShipA1">ShipA1</option>
        </select>
    </div>
    <div class="col-sm-2 my-auto">
        <label class="form-label">Daterange <button type="button" class="btn btn-info btn-sm"
                onclick="$('#date_rangeNew').val('')" style="padding: 3.2px 10px;">Clear</button></label>
        <div class='input-group date' id='datetimepicker1New'>
            <input type='text' name="date_rangeNew" id="date_rangeNew" class="form-control" />
            <span class="input-group-addon"
                style="
                    border: 1px solid #ddd;
                    border-left-color: transparent;
                    border-radius: 0;
                    position: relative;
                    left: -1px;
                    display: flex;
                    align-items: center;
                ">
                <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
    </div>
    <div class="col-sm-2 mt-auto">
        <button class="btn btn-warning" id="searchValues">Search</button>
    </div>
</div>
{{-- <div class="row" id="filters" style="display:none;">
    <div class="col-sm-2" style="display:none;" id="auction_storage">
        <label class="form-label">Auction Storage</label>
        <select class="form-control select2" id="auc_storage">
            <option value="0" selected>ALL</option>
            <option value="1">Already Storage</option>
            <option value="2">Late Pickup Storage</option>
        </select>
    </div>
    <div class="col-sm-4">
        <label for="searchBy" class="form-label">Search By</label>
        <select class="form-control" id="searchBy">
            <option value="id">Order Id</option>
            <option value="originzsc">Pickup</option>
            <option value="destinationzsc">Delivery</option>
            <option value="ymk">Vehicle Name</option>
            <option value="dauction">Port</option>
            <option value="ophone">Phone Number</option>
            <option value="oauction">Pickup Auction</option>
            <option value="dauction">Delivery Auction</option>
        </select>
    </div>
    <div class="col-sm-4">
        <label for="search" class="form-label">Search Value</label>
        <input type="text" id="search" class="form-control" placeholder="Search Value" />
    </div>
    <div class="col-sm-4">
        <label for="source" class="form-label">Source</label>
        <select class="form-control" id="source">
            <option value="">All</option>
            <option value="DayDispatch">DD</option>
            <option value="ShipA1">ShipA1</option>
        </select>
    </div>
    <div class="col-sm-1 mt-auto">
        <button class="btn btn-warning" id="searchValues">Search</button>
    </div>
</div> --}}
<div class="row" id="tableData">
</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(function() {
        var fromFormatted = "{{ $fromFormatted }}";
        var toFormatted = "{{ $toFormatted }}";

        console.log('check formats');
        console.log('fromFormatteds + ' - ' + toFormatted', fromFormatted + ' - ' + toFormatted);

        var date = new Date();
        $('#date_rangeNew').daterangepicker({
            "showDropdowns": true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                    'month').endOf('month')]
            },
            "alwaysShowCalendars": true,
            "opens": "center",
            "drops": "auto"
        }, function(start, end, label) {
            $('#date_rangeNew').val($('#date_range').val());
        });

        $('#date_rangeNew').val($('#date_range').val());
    });
</script>
