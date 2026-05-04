@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]", '/');
if (isset($_GET['titlee'])) {
    $respn = $_GET['titlee'];
}
?>
@php
    $check_panel = check_panel();

    if ($check_panel == 1) {
        $phoneaccess = explode(',', Auth::user()->emp_access_phone);
    } 
    elseif($check_panel == 3)
    {
        $phoneaccess = explode(',',Auth::user()->emp_access_test);
    }
    else {
        $phoneaccess = explode(',', Auth::user()->emp_access_web);
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
    <div id="updateTable">
        <table class="table table-bordered table-sm col-lg-2 fs-18 text-center pd-2 bd-l" role="grid"
            aria-describedby="">
            <thead class="table-dark">
                <tr>
                    <th>Sr. #</th>
                    <th>Order Taker</th>
                    <th>Company</th>
                    <th>State</th>
                    <th>Call Count</th>
                    <th>Created At</th>
                    <th>Last History</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $val)
                    {{-- dd($val->toArray()) --}}
                    @php
                        $i = $data->firstItem();
                    @endphp
                    <tr class="parent1{{ $key }}">
                        <td>{{ $key + $i }}</td>
                        <td>{{ !is_null($val->user) ? $val->user->name : $val->whatsappCallCount[0]->user_id }}</td>
                        <td>{{ $val->name }}</td>
                        <td>{{ $val->state }}</td>
                        <td>{{ count($val->whatsappCallCount) }}</td>
                        <td>
                            <br>{{ \Carbon\Carbon::parse($val->whatsappCallCount[0]->updated_at)->format('M,d Y') }}<br>{{ \Carbon\Carbon::parse($val->whatsappCallCount[0]->updated_at)->format('h:i A') }}
                        </td>
                        <td>
                            @if (count($val->history) > 0)
                                <span
                                    class="text-danger">{{ !is_null($val->history) ? $val->history[0]->connectStatus : '' }}</span>
                                <br>
                                {{ !is_null($val->history) ? $val->history[0]->comment : '' }}
                            @else
                                No History
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary get-history" data-toggle="modal"
                                data-target="#exampleModal7">View History
                                <input hidden type="text" class="Company-ID" value="{{ $val->id }}">
                                <input hidden type="text" class="User-ID"
                                    value="{{ !is_null($val->user) ? $val->whatsappCallCount[0]->userId : $val->whatsappCallCount[0]->userId }}">
                                <input hidden type="text" class="Company-Name" value="{{ $val->name }}">
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between">
        <div class="text-secondary my-auto">
            Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} from total {{ $data->total() }}
            entries
        </div>
        <div>
            {{ $data->links() }}
        </div>

    </div>

    <div class="modal fade" id="exampleModal7" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel8"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- <h5 class="modal-title" id="exampleModalLabel7">Employee Access (Assign
                        Data)</h5> --}}

                    <h5 class="modal-title" id="exampleModalLabel">Show History For: <span class="history_id"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="modal-body history-content">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Save</button>
            </div> --}}
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    function call(num) {
        var num1 = atob(num);

        window.location.href = 'rcmobile://call/?number=' + num1;
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

    // Add history with ajax
    // Use event delegation for dynamically added elements
    $(document).on("click", ".get-history", function(e) {
        e.preventDefault();

        var company_id = $(this).find('.Company-ID').val();
        var CompanyName = $(this).find('.Company-Name').val();
        var user_id = $(this).find('.User-ID').val();

        $(".history_id").html(CompanyName);
        $(".history_val").val(company_id);

        $.ajax({
            url: '{{ route("get.call.history") }}',
            type: 'GET',
            data: {
                'company_id': company_id,
                'user_id': user_id,
            },
            success: function(data) {
                console.log('data', data);

                $(".history-content").html('');
                var html = "";
                $.each(data, function(index, val) {
                    var createdAt = new Date(val['created_at']);
                    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",
                        "Aug", "Sep", "Oct", "Nov", "Dec"
                    ];
                    var formattedDate = monthNames[createdAt.getMonth()] + "," +
                        ("0" + createdAt.getDate()).slice(-2) + " " +
                        createdAt.getFullYear() + " " +
                        ("0" + createdAt.getHours()).slice(-2) + ":" +
                        ("0" + createdAt.getMinutes()).slice(-2) +
                        (createdAt.getHours() >= 12 ? " PM" : " AM");

                    html += "<h6>" + val['user']['name'] + "</h6>";
                    html += "<h6>" + val['connectStatus'] + "</h6>";
                    html += "<h6>" + val['comment'] + ".</h6>";
                    html += "<strong class='mf-date'><i class='fa fa-clock-o'></i>" +
                        formattedDate + "</strong> <hr>";
                });
                $(".history-content").html(html);
            },
            error: function(error) {
                console.error('Error submitting the form:', error);
            }
        });
    });
</script>
