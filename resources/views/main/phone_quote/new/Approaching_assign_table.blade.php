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
        $phoneaccess = [];
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
                <th>Order Taker</th>
                <th>Date Range</th>
                <th>Delivery City</th>
                <th>Delivery Zip</th>
                <th>Call Type</th>
                <th>Terminal</th>
                <th>Records Allowed</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($data as $key => $val)
                <tr>
                    <td>{{ $val->user->name }}</td>
                    <td>{{ $val->date_range }}</td>
                    <td>{{ $val->delivery_city }}</td>
                    <td>{{ $val->delivery_zip }}</td>
                    <td>{{ $val->call_type }}</td>
                    <?php
                        $oterminalDescriptions = [
                            "1" => "Residence",
                            "2" => "COPART Auction",
                            "3" => "Manheim Auction",
                            "4" => "IAAI Auction",
                            "5" => "Body Shop",
                            "10" => "Dealership",
                            "7" => "Business Location",
                            "8" => "Auction (Heavy)",
                            "6" => "Other"
                        ];
                        ?>
                    <td>{{ isset($oterminalDescriptions[$val->oterminal]) ? $oterminalDescriptions[$val->oterminal] : '' }}</td>
                    <td>{{ $val->recordsAllowed }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{-- </div>
    <div class="d-flex justify-content-between"> --}}
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



</script>
