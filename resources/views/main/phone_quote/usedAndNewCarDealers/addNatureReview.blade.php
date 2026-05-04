@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim("$_SERVER[REQUEST_URI]", '/')) }}
@endsection

@include('partials.mainsite_pages.return_function')

@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!--=================================multiselect tag============================== -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
    <!--=================================multiselect tag============================== -->
    <style>
        select.form-control:not([size]):not([multiple]) {
            height: 28px;
        }

        input[type='radio']:after {
            width: 15px;
            height: 15px;
            border-radius: 15px;
            top: -4px;
            left: -1px;
            position: relative;
            background-color: #d1d3d1;
            content: '';
            display: inline-block;
            visibility: visible;
            border: 2px solid white;
        }

        input[type='radio']:checked:after {
            width: 20px;
            height: 20px;
            border-radius: 100px;
            top: -2px;
            left: -6px;
            position: relative;
            background-color: rgb(23 162 184);
            content: '';
            display: inline-block;
            visibility: visible;
            border: 2px solid white;
        }

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

        .tx-white {
            color: white !important;
        }

        .badge-orange {
            color: #212529;
            background-color: #F49917;
        }

        .bg-white th {
            border: 1px solid #000000 !important;
        }

        .bg-white td {
            border: 1px solid #000000 !important;
        }

        .choices__inner {
            height: 50px;
            overflow-y: scroll;
            border: 1px solid #86c8ff;
        }
    </style>
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <input type="hidden" value="{{ trim("$_SERVER[REQUEST_URI]", '/') }}" id="titlee">
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">New Order</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Autos Approach</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            @if (session('flash_message'))
                <div class="alert alert-success">
                    {{ session('flash_message') }}
                </div>
            @endif
            <!--div-->
            <div class="card">
                @php
                    $ptype = 1;
                    $query = \App\user_setting::where('user_id', Auth::user()->id)->first();
                    if (!empty($query)) {
                        $ptype = $query['penal_type'];
                    }

                    if ($ptype == 1) {
                        $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                    } elseif ($ptype == 2) {
                        $phoneaccess = explode(',', Auth::user()->emp_access_web);
                    } elseif ($ptype == 3) {
                        $phoneaccess = explode(',', Auth::user()->emp_access_test);
                    } elseif ($ptype == 4) {
                        $phoneaccess = explode(',', Auth::user()->panel_type_4);
                    } elseif ($ptype == 5) {
                        $phoneaccess = explode(',', Auth::user()->panel_type_5);
                    } elseif ($ptype == 6) {
                        $phoneaccess = explode(',', Auth::user()->panel_type_6);
                    } else {
                        $phoneaccess = []; // Default case if $ptype is not within 1-6
                    }
                @endphp
                <div class="card-body">
                    <div id="table_data">
                        <div class="table-responsive" id="usedAndNewTableBody">
                            {{-- example1 --}}
                            <table class="table table-bordered table-sm col-lg-2 fs-18 text-center pd-2 bd-l" role="grid"
                                aria-describedby="">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Sr. #</th>
                                        <th>Order ID</th>
                                        <th>Nature of Customer</th>
                                    </tr>
                                </thead>
                                <tbody id="usedAndNewTableBody">
                                    @foreach ($orer as $key => $val)
                                        @php
                                            // $key = $key + 1;
                                            $i = $orer->firstItem();
                                        @endphp
                                        <tr class="parent1{{ $key }}">
                                            <td>{{ $key + $i }}</td>
                                            <td>{{ $val->id }}</td>
                                            <td>
                                                <form class="form-inline m-0" action="{{ route('customerNature.store') }}"
                                                    method="post">
                                                    @csrf
                                                    <input type="hidden" name="order_id" value="{{ $val->id }}">
                                                    <input type="hidden" name="main_ph"
                                                        value="{{ $val->ophone ?? $val->main_ph }}">
                                                    <input type="text" name="description"
                                                        class="form-control header-search w-75" autocomplete="off"
                                                        placeholder="Enter..." required>
                                                    <button type="submit"
                                                        class="form-control btn btn-primary">Save</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-between">
                                <div class="text-secondary my-auto">
                                    Showing {{ $orer->firstItem() ?? 0 }} to {{ $orer->lastItem() ?? 0 }} from total
                                    {{ $orer->total() }}
                                    entries
                                </div>
                                <div>
                                    {{ $orer->links() }}
                                </div>

                            </div>
                            <div class="modal fade" id="exampleModal8" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel8" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            {{-- <h5 class="modal-title" id="exampleModalLabel7">Employee Access (Assign
                                                Data)</h5> --}}

                                            <h5 class="modal-title" id="exampleModalLabel">Add History For: <span
                                                    class="history_id"></span>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <form id="addHistoryForm" action="{{ route('store.call.history') }}"
                                                        method="POST" class="needs-validation" novalidate
                                                        class="tablelist-form" autocomplete="off">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <input type="hidden" name="CompanyID" value=""
                                                                class="history_val">
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
                                                                                        class="btn btn-success">VIEW
                                                                                        HISTORY</a></li>
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
                                                                                <input type="radio"
                                                                                    class="custom-control-input"
                                                                                    id="connected" name="connectStatus"
                                                                                    value="Connected" checked>
                                                                                <label
                                                                                    class="custom-control-label form-label"
                                                                                    for="connected">Connected</label>
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            <div class="custom-control custom-radio mb-3">
                                                                                <input type="radio"
                                                                                    class="custom-control-input"
                                                                                    id="notConnected" name="connectStatus"
                                                                                    value="Not Connected">
                                                                                <label
                                                                                    class="custom-control-label form-label"
                                                                                    for="notConnected">Not
                                                                                    Connected</label>
                                                                            </div>
                                                                        </div>

                                                                        <div />
                                                                        <div class="col-lg-12">
                                                                            <div>
                                                                                <label for="label-field"
                                                                                    class="form-label">Add
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
                                                                <div class="chat-body-style ChatBody" id="calhistory"
                                                                    style=" height:300px;">

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
                                                </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Save</button>
                                    </div> --}}
                            </div>
                            {{-- <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Save</button>
                                    </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection

@section('extraScript')
@endsection
