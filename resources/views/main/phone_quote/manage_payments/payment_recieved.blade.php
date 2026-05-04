@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim("$_SERVER[REQUEST_URI]", '/')) }}
@endsection

@section('content')
    <style>
        select.form-control:not([size]):not([multiple]) {
            height: 28px;
        }
    </style>

    <!--/app header--> <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            {{-- <h4 class="page-title mb-0">{{  ucfirst(trim("$_SERVER[REQUEST_URI]",'/'))}}</h4> --}}
            <input type="hidden" value="{{ trim("$_SERVER[REQUEST_URI]", '/') }}" id="titlee">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">New Order</a></li>
            </ol>
        </div>
        {{-- <div class="page-rightheader"> --}}
        {{-- <div class="btn btn-list"> --}}
        {{-- <a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> Port Sheet Update</a> --}}
        {{-- <a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a> --}}
        {{-- <a href="#" class="btn btn-warning"><i class="fe fe-shopping-cart mr-1"></i> Buy Now </a> --}}
        {{-- </div> --}}
        {{-- </div> --}}
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
                <div class="card-header">
                    <div class="container-fluid">
                        <form>
                            <div class="col-lg-12">
                                <div class="row">
                                    <!--
                                           <div class="col-lg-4 text-center pd-10">

                                               <div class='input-group date' id='datetimepicker1'>
                                                   <input class="form-control fc-datepicker" placeholder="MM/DD/YYYY"
                                                          type="text">
                                                   <span class="input-group-addon">
                                                       <span class="glyphicon glyphicon-calendar"></span>
                                                   </span>
                                               </div>

                                            </div>
                                            -->

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div id="table_data">

                        @include('main.phone_quote.manage_payments.payment_recieved_load')
                    </div>
                </div>
            </div>

        </div>

    </div><!-- end app-content-->


    <div class="modal" id="reportmodal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-header pd-x-20">
                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Send Email Link</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <form action="" method="post" id="form">
                        <h5 class=" lh-3 mg-b-20">Order Id # <input readonly type="text" style=" border: 0px; "
                                name="orderid" value="" /></h5>

                        <div class="card">
                            <div class="card-body pd-20">

                                @csrf
                                Email Link
                                </br>
                                <!-- <input type="hidden" name="user_id" value="47"> -->
                                <div class="form-group">
                                    <div class="row row-sm">
                                        <div class="col-sm">
                                            <input type="text" readonly name="link" id="link"
                                                class="form-control" value="" />
                                        </div><!-- col -->
                                    </div><!-- row -->
                                </div><!-- form-group -->
                                <div class="form-group">
                                    <input type="text" name="email" id="email" class="form-control" value=""
                                        placeholder="Enter email address..." />
                                </div><!-- form-group -->
                                <button type="submit" class="btn btn-primary pd-x-20">Submit
                                </button>

                            </div><!-- card-body -->
                        </div>
                    </form>
                </div><!-- modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                    </button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal" id="modalPaid">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-header pd-x-20">
                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Payment Status</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <form action="paid_status" method="post" id="form">
                        @csrf
                        <h5 class=" lh-3 mg-b-20">Order Id # <input readonly type="text" style=" border: 0px; "
                                name="orderid" value="" /></h5>

                        <div class="card">
                            <div class="card-body pd-20">


                                Payment Status
                                </br>
                                <!-- <input type="hidden" name="user_id" value="47"> -->
                                <div class="form-group">
                                    <div class="row row-sm">
                                        <div class="col-sm">

                                            <input type="radio" name="status" id="status0" class=""
                                                value="0" /> Pending
                                            <br>

                                            <input type="radio" name="status" id="status1" class=""
                                                value="1" /> Updated
                                            <br>

                                            <input type="radio" name="status" id="status2" class=""
                                                value="2" /> Received
                                            <br>
                                            <input type="checkbox" name="fully_paid" id="fully_paid" value="1" />
                                            Fully Paid
                                            <br>
                                            Comments
                                            <textarea name="pay_comments" maxlength="50" class="form-control"></textarea>

                                        </div><!-- col -->
                                    </div><!-- row -->
                                </div><!-- form-group -->
                                <div class="form-group">

                                </div><!-- form-group -->
                                <button type="submit" class="btn btn-primary pd-x-20">Submit
                                </button>

                            </div><!-- card-body -->
                        </div>
                    </form>
                </div><!-- modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="trashmodal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-header pd-x-20">
                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Delete Order</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="/trash_order" method="post" id="form">
                    <div class="modal-body pd-20">

                        <h5 class=" lh-3 mg-b-20">Order Id # <input style="border: none" readonly type="text"
                                name="orderid" value="" /></h5>

                        <div class="card">
                            <div class="card-body pd-20">
                                @csrf
                                <div class="form-group" style=" text-align: center; font-size: 24px; ">
                                    Do you want to delete order <strong>?</strong>


                                </div><!-- card-body -->
                            </div>
                        </div>

                    </div><!-- modal-body -->
                    <div class="modal-footer" style=" justify-content: center; ">
                        <button type="submit" class="btn btn-danger pd-x-20 w-25">Yes
                        </button>
                        <button type="button" class="btn btn-info w-25" data-dismiss="modal">No
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="modal" id="modaldemo4">
        <div class="modal-dialog modal-dialog-centered text-center " role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                    <i class="fe fe-check-circle fs-100 text-success lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-success tx-semibold" id="success"></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modaldemo5">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                    <i class="fe fe-x-circle fs-100 text-danger lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-danger" id="not_success"></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="comparehmodal" tabindex="-1" role="dialog" aria-labelledby="largemodal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content" style="width: max-content;right: 102px;">
                <div class="modal-header">
                    <center>
                        <h5 class="modal-title" id="largemodal1">Carrier Data</h5>
                    </center>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <table class="table table-responsive table-bordered table-stripe table-condensed"
                            id="table_data_carrier">

                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="largemodal" tabindex="-1" role="dialog" aria-labelledby="largemodal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="largemodal1">HISTORY/STATUS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="panel panel-primary">
                            <div class=" tab-menu-heading p-0 bg-light">
                                <div class="tabs-menu1 ">
                                    <!-- Tabs -->
                                    <ul class="nav panel-tabs">
                                        <li class=""><a href="#tab1" class="active"
                                                data-toggle="tab">HISTORY/STATUS</a>
                                        </li>
                                        <li><a href="#tab2" data-toggle="tab">VIEW HISTORY</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body">
                                <div class="tab-content">
                                    <div class="tab-pane active " id="tab1">

                                        @if (\Request::is('new'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">New HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="1">Interested</option>
                                                                <option value="2">FollowMore</option>
                                                                <option value="3">AskingLow</option>
                                                                <option value="4">NotInterested</option>
                                                                <option value="5">NoResponse</option>
                                                                <option value="6">TimeQuote</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12" id="ask_low">

                                                    </div>

                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif
                                        @if (\Request::is('followup'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">FollowUp HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="1">Interested</option>
                                                                <option value="2">FollowMore</option>
                                                                <option value="3">AskingLow</option>
                                                                <option value="4">NotInterested</option>
                                                                <option value="5">NoResponse</option>
                                                                <option value="6">TimeQuote</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12" id="ask_low">

                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif
                                        @if (\Request::is('interested'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">INTERESTED HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="3">AskingLow</option>
                                                                <option value="5">NoResponse</option>
                                                                <option value="6">TimeQuote</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12" id="ask_low">

                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif
                                        @if (\Request::is('asking_low'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">ASKING LOW HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="1">Interested</option>
                                                                <option value="2">FollowMore</option>
                                                                <option value="4">NotInterested</option>
                                                                <option value="5">NoResponse</option>
                                                                <option value="6">TimeQuote</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12" id="ask_low">

                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('not_interested'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">NOT-INTERESTED HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="1">Interested</option>
                                                                <option value="2">FollowMore</option>
                                                                <option value="3">AskingLow</option>
                                                                <option value="5">NoResponse</option>
                                                                <option value="6">TimeQuote</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12" id="ask_low">

                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('not_responding'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">Not Responding HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="1">Interested</option>
                                                                <option value="2">FollowMore</option>
                                                                <option value="3">AskingLow</option>
                                                                <option value="4">NotInterested</option>
                                                                <option value="5">NoResponse</option>
                                                                <option value="6">TimeQuote</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12" id="ask_low">

                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('time_quote'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">TimeQuote HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="1">Interested</option>
                                                                <option value="2">FollowMore</option>
                                                                <option value="3">AskingLow</option>
                                                                <option value="4">NotInterested</option>
                                                                <option value="5">NoResponse</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12" id="ask_low">

                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif
                                        @if (\Request::is('payment_missing'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">Payment Missing HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="7">PAYMENT MISSING</option>
                                                                <option value="9">LISTED</option>
                                                                <option value="19">ONAPPROVAL CANCEL</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5 col-md-5">
                                                        <div class="form-group">
                                                            <label class="form-label">LISTED PRICE</label>
                                                            <input type="number" required name="listed_price"
                                                                id='listed_price' class="form-control">
                                                        </div>
                                                    </div>
                                                    @php
                                                        $ptype = 1;
                                                        $query = \App\user_setting::where(
                                                            'user_id',
                                                            Auth::user()->id,
                                                        )->first();
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
                                                            $phoneaccess = [];
                                                        }
                                                    @endphp
                                                    @if (in_array('76', $phoneaccess))
                                                        <?php
                                                        $dis = \App\User::with('daily_ass')
                                                            ->whereHas('userRole', function ($q) {
                                                                $q->where('name', 'Dispatcher');
                                                            })
                                                            ->where('deleted', 0)
                                                            ->get();
                                                        ?>
                                                        <div class="col-sm-3 col-md-3 my-auto">
                                                            <button class="btn btn-primary" type="button"
                                                                id="showingDispatchers" disabled>Assign Dispatcher</button>
                                                        </div>
                                                        <div class="col-sm-4 col-md-4" id="showDispatchers"
                                                            style="display:none;">
                                                            <div class="form-group">
                                                                <label class="form-label">Dispatchers <span
                                                                        class="text-muted">(Optional)</span></label>
                                                                <select name="dis_id" id='dis_id'
                                                                    class="form-control">
                                                                    <option value="" selected disabled>Select
                                                                    </option>
                                                                    @foreach ($dis as $key => $dispa)
                                                                        <option value="{{ $dispa->id }}">
                                                                            {{ $dispa->slug ?? $dispa->name . ' ' . $dispa->last_name }}
                                                                            ({{ isset($dispa->daily_ass->total_quote) ? $dispa->daily_ass->total_quote . ' Left' : 'Unlimited' }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row auctionupdate mb-2"></div>
                                                <script>
                                                    $("#pstatus").on('change', function() {
                                                        if ($(this).val() == 9) {
                                                            $(".auctionupdate").html(`
                                                                <input type="hidden" value="9" name="pstatus222">
                                                                <input type="hidden" value="sheet_detail" name="sheet_detail">
                                                                <div class="col-md-12 text-center"><h4>Listed Sheet</h4></div>
                                                                <div class="col-md-4">
                                                                    <label>Paid</label>
                                                                    <select name="auc_paid" id="auc_paid" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Storage</label>
                                                                    <input class="form-control" id="auc_storage" name="auc_storage" placeholder="Storage" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Listed Price</label>
                                                                    <input class="form-control" id="auc_listed_price" placeholder="Listed Price" name="auc_listed_price" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Auction Update</label>
                                                                    <input class="form-control" id="auc_auction_update" placeholder="Auction Update" name="auc_auction_update"
                                                                           value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Title</label>
                                                                    <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Key</label>
                                                                    <select id="auc_keys" name="auc_keys" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Listed Count</label>
                                                                    <input class="form-control" id="auc_listed_count" placeholder="Listed Count" name="auc_listed_count" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Old/New Price</label>
                                                                    <input class="form-control" id="auc_price" placeholder="Old / New Price" name="auc_price"
                                                                           value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Vehicle Position</label>
                                                                    <input class="form-control" placeholder="Vehicle Position" id="auc_port" name="auc_port" value="">
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label>Additional</label>
                                                                    <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="">
                                                                </div>
                                                            `);
                                                            $("#expected_date").attr('disabled', false);
                                                            $("#listed_price").attr('disabled', false);
                                                            $("#showingDispatchers").attr('disabled', false);
                                                        } else if ($(this).val() == 7) {
                                                            $("#expected_date").attr('disabled', false);
                                                            $("#listed_price").attr('disabled', true);
                                                            $("#showingDispatchers").attr('disabled', true);
                                                            $("#showDispatchers").hide();
                                                            $("#dis_id").children('option').removeAttr('selected');
                                                            $("#dis_id").children('option').eq(0).attr('selected', true);
                                                        } else {
                                                            $("#expected_date").attr('disabled', true);
                                                            $("#listed_price").attr('disabled', true);
                                                            $("#showingDispatchers").attr('disabled', true);
                                                            $("#showDispatchers").hide();
                                                            $("#dis_id").children('option').removeAttr('selected');
                                                            $("#dis_id").children('option').eq(0).attr('selected', true);
                                                            $(".auctionupdate").html('');
                                                        }
                                                    })
                                                </script>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif
                                        @if (\Request::is('booked'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">BOOKED HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="8">BOOKED</option>
                                                                <option value="9">LISTED</option>
                                                                <option value="19">ONAPPROVAL CANCEL</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5 col-md-5">
                                                        <div class="form-group">
                                                            <label class="form-label">LISTED PRICE</label>
                                                            <input type="number" required name="listed_price"
                                                                id='listed_price' class="form-control">
                                                        </div>
                                                    </div>
                                                    @php
                                                        $ptype = 1;
                                                        $query = \App\user_setting::where(
                                                            'user_id',
                                                            Auth::user()->id,
                                                        )->first();
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
                                                            $phoneaccess = [];
                                                        }
                                                    @endphp
                                                    @if (in_array('76', $phoneaccess))
                                                        <?php
                                                        $dis = \App\User::with('daily_ass')
                                                            ->whereHas('userRole', function ($q) {
                                                                $q->where('name', 'Dispatcher');
                                                            })
                                                            ->where('deleted', 0)
                                                            ->get();
                                                        ?>
                                                        <div class="col-sm-3 col-md-3 my-auto">
                                                            <button class="btn btn-primary" type="button"
                                                                id="showingDispatchers" disabled>Assign Dispatcher</button>
                                                        </div>
                                                        <div class="col-sm-4 col-md-4" id="showDispatchers"
                                                            style="display:none;">
                                                            <div class="form-group">
                                                                <label class="form-label">Dispatchers <span
                                                                        class="text-muted">(Optional)</span></label>
                                                                <select name="dis_id" id='dis_id'
                                                                    class="form-control">
                                                                    <option value="" selected disabled>Select
                                                                    </option>
                                                                    @foreach ($dis as $key => $dispa)
                                                                        <option value="{{ $dispa->id }}">
                                                                            {{ $dispa->slug ?? $dispa->name . ' ' . $dispa->last_name }}
                                                                            ({{ isset($dispa->daily_ass->total_quote) ? $dispa->daily_ass->total_quote . ' Left' : 'Unlimited' }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row auctionupdate mb-2"></div>
                                                <script>
                                                    $("#pstatus").on('change', function() {
                                                        if ($(this).val() == 9) {
                                                            $(".auctionupdate").html(`
                                                                <input type="hidden" value="9" name="pstatus222">
                                                                <input type="hidden" value="sheet_detail" name="sheet_detail">
                                                                <div class="col-md-12 text-center"><h4>Listed Sheet</h4></div>
                                                                <div class="col-md-4">
                                                                    <label>Paid</label>
                                                                    <select name="auc_paid" id="auc_paid" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Storage</label>
                                                                    <input class="form-control" id="auc_storage" name="auc_storage" placeholder="Storage" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Listed Price</label>
                                                                    <input class="form-control" id="auc_listed_price" placeholder="Listed Price" name="auc_listed_price" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Auction Update</label>
                                                                    <input class="form-control" id="auc_auction_update" placeholder="Auction Update" name="auc_auction_update"
                                                                           value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Title</label>
                                                                    <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Key</label>
                                                                    <select id="auc_keys" name="auc_keys" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Listed Count</label>
                                                                    <input class="form-control" id="auc_listed_count" placeholder="Listed Count" name="auc_listed_count" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Old/New Price</label>
                                                                    <input class="form-control" id="auc_price" placeholder="Old / New Price" name="auc_price"
                                                                           value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Vehicle Position</label>
                                                                    <input class="form-control" placeholder="Vehicle Position" id="auc_port" name="auc_port" value="">
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label>Additional</label>
                                                                    <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="">
                                                                </div>
                                                            `);
                                                            $("#expected_date").attr('disabled', false);
                                                            $("#listed_price").attr('disabled', false);
                                                            $("#showingDispatchers").attr('disabled', false);
                                                        } else if ($(this).val() == 8) {
                                                            $("#expected_date").attr('disabled', false);
                                                            $("#listed_price").attr('disabled', true);
                                                            $("#showingDispatchers").attr('disabled', true);
                                                            $("#showDispatchers").hide();
                                                            $("#dis_id").children('option').removeAttr('selected');
                                                            $("#dis_id").children('option').eq(0).attr('selected', true);
                                                        } else {
                                                            $("#expected_date").attr('disabled', true);
                                                            $("#listed_price").attr('disabled', true);
                                                            $("#showingDispatchers").attr('disabled', true);
                                                            $("#showDispatchers").hide();
                                                            $("#dis_id").children('option').removeAttr('selected');
                                                            $("#dis_id").children('option').eq(0).attr('selected', true);
                                                            $(".auctionupdate").html('');
                                                        }
                                                    })
                                                </script>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif
                                        @if (\Request::is('listed'))
                                            <form id="listedform" method="post"
                                                action="{{ route('call_history_post_relist') }}" id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">LISTED HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row" id="row1">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select required name="pstatus" id='pstatus'
                                                                class="form-control  getcarrier">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="9">Listed</option>
                                                                <option value="10">Schedule</option>
                                                                <option value="19">ONAPPROVAL CANCEL</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Select Carrier
                                                                <a href="javascript:;"
                                                                    onclick="this.href='/carrier_add/'+ document.getElementById('order_id1').value"
                                                                    type="button" target="_blank"
                                                                    class="btn btn-info btn-sm">UPDATE CARRIER</a>

                                                            </label>
                                                            <select id="current_carrier" class="form-control "
                                                                name="current_carrier" required disabled
                                                                data-validation-required-message="This field is required">
                                                                <option value="">Please Add Carrier
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5 col-md-5">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date" disabled
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="row">
                                                                    <div class="col-sm-4 my-auto">
                                                                        <div class="form-group d-flex m-auto">
                                                                            <input type="checkbox" disabled
                                                                                class="mr-2 already_late"
                                                                                name="already_late1" value="1">
                                                                            <label class="form-label my-auto">Already
                                                                                Storage Price</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4 col-md-4 auction_already_late"
                                                                        style="display:none;">
                                                                        <div class="form-group">
                                                                            <input type="text" name="already_storage"
                                                                                id='already_storage' class="form-control"
                                                                                placeholder="Enter Already Storage Price">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="row">
                                                                    <div class="col-sm-4 my-auto">
                                                                        <div class="form-group d-flex m-auto">
                                                                            <input type="checkbox" disabled
                                                                                class="mr-2 already_late"
                                                                                name="already_late2" value="1">
                                                                            <label class="form-label my-auto">Late Pickup
                                                                                Storage Price</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-4 col-md-4 auction_already_late"
                                                                        style="display:none;">
                                                                        <div class="form-group">
                                                                            <input type="text"
                                                                                name="late_pickup_storage"
                                                                                id='late_pickup_storage'
                                                                                class="form-control"
                                                                                placeholder="Enter Late Pickup Storage Price">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="row auctionupdate mb-2"></div>
                                                        <script>
                                                            $("#pstatus").on('change', function() {
                                                                if ($(this).val() == 10) {
                                                                    $(".auctionupdate").html(`
                                                                        <input type="hidden" value="10" name="pstatus222">
                                                                        <input type="hidden" value="sheet_detail" name="sheet_detail">
                                                                        <div class="col-md-12 text-center"><h4>Scheduling Sheet</h4></div>
                                                                        <div class="col-md-4">
                                                                            <label>Pickedup Time</label>
                                                                            <input class="form-control" type="datetime-local" id="auc_pickedup" required name="auc_pickedup" placeholder="=PickedUp time">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Delivery Time</label>
                                                                            <input class="form-control" type="datetime-local" id="auc_delivery_date" required name="auc_delivery_date" placeholder="=Delivery time">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Dispatch Price</label>
                                                                            <input class="form-control" type="text" id="auc_price" name="auc_price" placeholder="Dispatch Price" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Vehicle Condition</label>
                                                                            <input class="form-control" type="text" id="auc_condition" name="auc_condition" required placeholder="Vehicle Condition">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Storage</label>
                                                                            <input class="form-control" id="auc_storage" name="auc_storage" placeholder="Storage" required value="">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Driver FMCSA (Active)?</label>
                                                                            <select id="auc_driver_fmcsa" name="auc_driver_fmcsa" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Carrier Rating</label>
                                                                            <input class="form-control" id="auc_carrier_rating" name="auc_carrier_rating" required placeholder="Carrier Rating" value="">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Verify FMCSA?</label>
                                                                            <input class="form-control" id="auc_fmcsa" name="auc_fmcsa" placeholder="FMCSA" value="" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Date Of Insurance (FMCSA)</label>
                                                                            <input class="form-control" type="date" id="auc_insurance_date" name="auc_insurance_date" placeholder="Date Of Insurance (FMCSA)" value="" required>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>COI Holder</label>
                                                                            <select id="auc_coi_holder" name="auc_coi_holder" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                                <option value="Waiting">Waiting</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Is Vehicle Luxury?</label>
                                                                            <select id="auc_vehicle_luxury" name="auc_vehicle_luxury" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Aware Driver Delivery</label>
                                                                            <input class="form-control" type="text" id="auc_aware_driver_delivery_date" required name="auc_aware_driver_delivery_date" placeholder="Aware Driver Delivery">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>New/Old Driver</label>
                                                                            <select id="auc_new_old_driver" name="auc_new_old_driver" class="form-control h-50" required>
                                                                                <option value="Old Driver">Old Driver</option>
                                                                                <option value="New Driver">New Driver</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Is Local?</label>
                                                                            <select id="auc_is_local" name="auc_is_local" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Job Accept</label>
                                                                            <input class="form-control" id="auc_job_accept" name="auc_job_accept" required placeholder="Job Accept" value="">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Title</label>
                                                                            <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Key</label>
                                                                            <select id="auc_keys" name="auc_keys" class="form-control h-50" required>
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Auction Update</label>
                                                                            <input id="auc_auction_update" name="auc_auction_update" required class="form-control" placeholder="Auction Update" value="">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Storage Pay</label>
                                                                            <select id="auc_who_pay_storage" name="auc_who_pay_storage" required class="form-control h-50">
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Vehicle Position</label>
                                                                            <input id="auc_vehicle_position" name="auc_vehicle_position" required class="form-control" placeholder="Vehicle Position" value="">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Payment Method</label>
                                                                            <input class="form-control" id="auc_payment_method" name="auc_payment_method" required placeholder="Payment Method" value="">
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <label>Additional</label>
                                                                            <input class="form-control" id="auc_additional" placeholder="Additional" required name="auc_additional" value="">
                                                                        </div>
                                                                    `);
                                                                    $("#expected_date").attr('disabled', false);
                                                                    $("#current_carrier").attr('disabled', false);
                                                                    $("input[name='already_late1']").attr('disabled', false);
                                                                    $("input[name='already_late2']").attr('disabled', false);
                                                                } else if ($(this).val() == 9) {
                                                                    $("#expected_date").attr('disabled', false);
                                                                    $("#current_carrier").attr('disabled', true);
                                                                    $("input[name='already_late1']").attr('disabled', true);
                                                                    $("input[name='already_late1']").removeAttr('checked');
                                                                    $("#already_storage").hide();
                                                                    $("input[name='already_late2']").attr('disabled', true);
                                                                    $("input[name='already_late2']").removeAttr('checked');
                                                                    $("#late_pickup_storage").hide();
                                                                } else {
                                                                    $("#expected_date").attr('disabled', true);
                                                                    $("#current_carrier").attr('disabled', true);
                                                                    $(".auctionupdate").html('');
                                                                    $("input[name='already_late1']").attr('disabled', true);
                                                                    $("input[name='already_late1']").removeAttr('checked');
                                                                    $("#already_storage").hide();
                                                                    $("input[name='already_late2']").attr('disabled', true);
                                                                    $("input[name='already_late2']").removeAttr('checked');
                                                                    $("#late_pickup_storage").hide();
                                                                }
                                                            })
                                                        </script>
                                                        <script>
                                                            $(".already_late").on('change', function() {
                                                                if ($(this).is(":checked")) {
                                                                    $(this).parent('div').parent('div').siblings('.auction_already_late').show();
                                                                } else {
                                                                    $(this).parent('div').parent('div').siblings('.auction_already_late').hide();
                                                                }
                                                            })
                                                        </script>
                                                    </div>
                                                </div>
                                                <div class="row" id="row2">
                                                    <div class="col-sm-1 col-md-1">
                                                        <div class="form-group">
                                                            <label class="form-label">Relist</label>
                                                            <input style="width: 20px;height: 20px" onclick="showprice()"
                                                                type="checkbox" name="relist" id='relist'>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-6 col-md-6" id="r1" style="display: none">
                                                        <div class="form-group">
                                                            <label class="form-label">New Relist Price</label>
                                                            <input type="number" name="listed_price" id='relist_id'
                                                                class="form-control">
                                                        </div>
                                                    </div>


                                                </div>

                                                <div class="col-sm-6 col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">HISTORY</label>
                                                        <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('dispatch'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">Schedule HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row" id="dipatchpickup">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control  getpickupdate">
                                                                {{-- <option value="" selected disabled>Select</option> --}}
                                                                <option value="10" selected>Schedule</option>
                                                                <option value="34">Schedule To Another Driver</option>
                                                                <option value="11">Pickup</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Select Carrier
                                                                <a href="javascript:;"
                                                                    onclick="this.href='/carrier_add/'+ document.getElementById('order_id1').value"
                                                                    type="button" target="_blank"
                                                                    class="btn btn-info btn-sm" id="carrier_add">UPDATE
                                                                    CARRIER</a>

                                                            </label>
                                                            <select id="current_carrier"
                                                                class="form-control select_cancel" name="current_carrier"
                                                                required style=" height: auto; "
                                                                data-validation-required-message="This field is required">
                                                                <option value="">Please Add Carrier</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--<div class="col-sm-6 col-md-6">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <label class="form-label">EXPECTED DATE / DELIVER-->
                                                    <!--            DATE</label>-->
                                                    <!--        <input type="date" required name="expected_date"-->
                                                    <!--               id='expected_date' -->
                                                    <!--               class="form-control">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6 col-md-6 pickupdatediv"></div>-->
                                                    <div class="col-sm-6 col-md-6 pickupdatediv">
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row auctionupdate mb-2"></div>
                                                <script>
                                                    $("#pstatus").on('change', function() {
                                                        if ($(this).val() == 11) {
                                                            $("#carrier_add").attr('disabled', true);
                                                            var order_id = $("#order_id1").val();
                                                            $.ajax({
                                                                url: "{{ url('/get_sheet') }}",
                                                                type: "GET",
                                                                data: {
                                                                    order_id: order_id
                                                                },
                                                                dataType: "JSON",
                                                                success: function(res) {
                                                                    var pickup = '';
                                                                    if (res.pickup_date) {
                                                                        pickup = res.pickup_date;
                                                                    }
                                                                    $(".pickupdatediv").html(`
                                                                        <div class="form-group">
                                                                            <label class="form-label">PICKUP DATE</label>
                                                                            <input type="datetime-local" value="${pickup}" required name="pickup_date" 
                                                                            id='pickup_date' class="form-control">
                                                                            <input type="checkbox" name="approvalpickup" value="1"/>MARK AS APPROVED
                                                                        </div>
                                                                    `);
                                                                }
                                                            })

                                                            $.ajax({
                                                                url: "{{ url('/get_carrier2') }}",
                                                                type: "GET",
                                                                data: {
                                                                    order_id: order_id
                                                                },
                                                                dataType: "JSON",
                                                                success: function(res) {
                                                                    var phone1 = '';
                                                                    if (res[0].driverphoneno) {
                                                                        phone1 = res[0].driverphoneno;
                                                                    }
                                                                    var name = '';
                                                                    if (res[0].companyname) {
                                                                        name = res[0].companyname;
                                                                    }
                                                                    $(".auctionupdate").html(`
                                                                        <input type="hidden" value="11" name="pstatus222">
                                                                        <input type="hidden" value="sheet_detail" name="sheet_detail">
                                                                        <div class="col-md-12 text-center"><h2>Pickup Sheet</h2></div>
                                                                        <div class="col-sm-12">
                                                                            <div class="card">
                                                                                <div class="card-header justify-content-center"><h3 class="m-auto">Auction Status</h3></div>
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <label>Auction Status</label>
                                                                                            <input class="form-control" id="auc_auction_status1" name="auc_auction_status1" placeholder="Auction Status" value="" required>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <label>Storage</label>
                                                                                            <input class="form-control" id="auc_storage1" name="auc_storage1" required placeholder="Storage" value="">
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <label>Vehicle Condition</label>
                                                                                            <input class="form-control" id="auc_condition1" required placeholder="Vehicle Condition" name="auc_condition1" value="">
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <label>Title</label>
                                                                                            <select id="auc_title_keys1" name="auc_title_keys1" required class="form-control h-50">
                                                                                                <option value="" selected disabled>Select</option>
                                                                                                <option value="Yes">Yes</option>
                                                                                                <option value="No">No</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <label>Key</label>
                                                                                            <select id="auc_keys1" name="auc_keys1" required class="form-control h-50">
                                                                                                <option value="" selected disabled>Select</option>
                                                                                                <option value="Yes">Yes</option>
                                                                                                <option value="No">No</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <label>Vehicle Position</label>
                                                                                            <input id="auc_vehicle_position1" name="auc_vehicle_position1" required class="form-control" placeholder="Vehicle Position" value="">
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <label>Additional</label>
                                                                                            <input class="form-control" id="auc_additional1" required placeholder="Additional" name="auc_additional1" value="">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                            <div class="card">
                                                                                <div class="card-header justify-content-center"><h3 class="m-auto">Driver Status</h3></div>
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-3">
                                                                                            <label>Driver Status</label>
                                                                                            <input class="form-control" id="auc_driver_status" required name="auc_driver_status" placeholder="Driver Status" value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label>Company Name</label>
                                                                                            <input class="form-control" id="auc_company_name" required name="auc_company_name" placeholder="Company Name" value="${name}">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label>Driver Name</label>
                                                                                            <input class="form-control" id="auc_carrier_name" required name="auc_carrier_name" placeholder="Driver Name" value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label>Driver Payment</label>
                                                                                            <input class="form-control" id="auc_driver_payment" required name="auc_driver_payment" placeholder="Driver Payment" value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label>Driver No1#</label>
                                                                                            <input class="form-control driverphoneno" id="auc_driver_no" required name="auc_driver_no" placeholder="Driver No1#" value="${phone1}">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label>Driver No2#</label>
                                                                                            <input class="form-control driverphoneno" id="auc_driver_no2" name="auc_driver_no2" placeholder="Driver No2#" value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label>Driver No3#</label>
                                                                                            <input class="form-control driverphoneno" id="auc_driver_no3" name="auc_driver_no3" placeholder="Driver No3#" value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label>Driver No4#</label>
                                                                                            <input class="form-control driverphoneno" id="auc_driver_no4" name="auc_driver_no4" placeholder="Driver No4#" value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label>Storage</label>
                                                                                            <input class="form-control" id="auc_storage" name="auc_storage" required placeholder="Storage" value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label>Delivery Datetime</label>
                                                                                            <input class="form-control" id="auc_delivery_date" type="datetime-local" required  placeholder="Delivery Datetime" name="auc_delivery_date"
                                                                                                   value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label>Vehicle Condition</label>
                                                                                            <input class="form-control" id="auc_condition" placeholder="Vehicle Condition" required name="auc_condition" value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label>Vehicle Position</label>
                                                                                            <input id="auc_vehicle_position" name="auc_vehicle_position" required class="form-control" placeholder="Vehicle Position" value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label>Payment</label>
                                                                                            <select id="auc_payment" class="form-control h-50" name="auc_payment" required>
                                                                                                <option value="" selected disabled>Select</option>
                                                                                                <option value="Yes">Yes</option>
                                                                                                <option value="No">No</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label>Payment Charged Or Owes</label>
                                                                                            <input class="form-control" id="auc_payment_charged_or_owes" name="auc_payment_charged_or_owes" required placeholder="Payment Charged Or Owes" value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label>Payment Method</label>
                                                                                            <input class="form-control" id="auc_payment_method" name="auc_payment_method" required placeholder="Payment Method" value="">
                                                                                        </div>
                                                                                        <div class="col-md-3">
                                                                                            <label>Total Amount (If Owed)</label>
                                                                                            <input class="form-control" id="auc_price" name="auc_price" required placeholder="Total Amount (If Owed)" value="">
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <label>Title</label>
                                                                                            <select id="auc_title_keys" name="auc_title_keys" required class="form-control h-50">
                                                                                                <option value="" selected disabled>Select</option>
                                                                                                <option value="Yes">Yes</option>
                                                                                                <option value="No">No</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <label>Key</label>
                                                                                            <select id="auc_keys" name="auc_keys" class="form-control h-50" required>
                                                                                                <option value="" selected disabled>Select</option>
                                                                                                <option value="Yes">Yes</option>
                                                                                                <option value="No">No</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-md-4">
                                                                                            <label>Dock Receipt (If Port)</label>
                                                                                            <input class="form-control" id="auc_stamp_dock_receipt" required name="auc_stamp_dock_receipt" placeholder="Dock Receipt (If Port)" value="">
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <label>Additional</label>
                                                                                            <input class="form-control" id="auc_additional" required placeholder="Additional" name="auc_additional" value="">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    `);
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
                                                                }
                                                            });
                                                        } else if ($(this).val() == 34) {
                                                            $("#carrier_add").attr('disabled', false);
                                                            $(".auctionupdate").html(`
                                                                <input type="hidden" value="10" name="pstatus222">
                                                                <input type="hidden" value="sheet_detail" name="sheet_detail">
                                                                <div class="col-md-12 text-center"><h4>Scheduling Sheet</h4></div>
                                                                <div class="col-md-4">
                                                                    <label>Pickedup Time</label>
                                                                    <input class="form-control" type="datetime-local" id="auc_pickedup" name="auc_pickedup" placeholder="=PickedUp time" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Delivery Time</label>
                                                                    <input class="form-control" type="datetime-local" id="auc_delivery_date" name="auc_delivery_date" placeholder="=Delivery time" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Dispatch Price</label>
                                                                    <input class="form-control" type="text" id="auc_price" name="auc_price" placeholder="Dispatch Price" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Vehicle Condition</label>
                                                                    <input class="form-control" type="text" id="auc_condition" name="auc_condition" placeholder="Vehicle Condition" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Storage</label>
                                                                    <input class="form-control" id="auc_storage" name="auc_storage" placeholder="Storage" value="" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Driver FMCSA (Active)?</label>
                                                                    <select id="auc_driver_fmcsa" name="auc_driver_fmcsa" class="form-control h-50" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Carrier Rating</label>
                                                                    <input class="form-control" id="auc_carrier_rating" name="auc_carrier_rating" placeholder="Carrier Rating" value="" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Verify FMCSA?</label>
                                                                    <input class="form-control" id="auc_fmcsa" name="auc_fmcsa" placeholder="FMCSA" value="" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Date Of Insurance (FMCSA)</label>
                                                                    <input class="form-control" type="date" id="auc_insurance_date" name="auc_insurance_date" placeholder="Date Of Insurance (FMCSA)" value="" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>COI Holder</label>
                                                                    <select id="auc_coi_holder" name="auc_coi_holder" class="form-control h-50" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                        <option value="Waiting">Waiting</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Is Vehicle Luxury?</label>
                                                                    <select id="auc_vehicle_luxury" name="auc_vehicle_luxury" class="form-control h-50" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Aware Driver Delivery</label>
                                                                    <input class="form-control" type="text" id="auc_aware_driver_delivery_date" name="auc_aware_driver_delivery_date" placeholder="Aware Driver Delivery" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>New/Old Driver</label>
                                                                    <select id="auc_new_old_driver" name="auc_new_old_driver" class="form-control h-50" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Old Driver">Old Driver</option>
                                                                        <option value="New Driver">New Driver</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Is Local?</label>
                                                                    <select id="auc_is_local" name="auc_is_local" class="form-control h-50" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Job Accept</label>
                                                                    <input class="form-control" id="auc_job_accept" name="auc_job_accept" placeholder="Job Accept" value="" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Title</label>
                                                                    <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Key</label>
                                                                    <select id="auc_keys" name="auc_keys" class="form-control h-50" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Auction Update</label>
                                                                    <input id="auc_auction_update" name="auc_auction_update" class="form-control" placeholder="Auction Update" value="" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Storage Pay</label>
                                                                    <select id="auc_who_pay_storage" name="auc_who_pay_storage" class="form-control h-50" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Vehicle Position</label>
                                                                    <input id="auc_vehicle_position" name="auc_vehicle_position" class="form-control" placeholder="Vehicle Position" value="" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Payment Method</label>
                                                                    <input class="form-control" id="auc_payment_method" name="auc_payment_method" placeholder="Payment Method" value="" required>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label>Additional</label>
                                                                    <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="" required>
                                                                </div>
                                                            `);
                                                            $(".pickupdatediv").html(`
                                                                <div class="form-group">
                                                                    <label class="form-label">EXPECTED DATE</label>
                                                                    <input type="date" required name="expected_date"
                                                                           id='expected_date' 
                                                                           class="form-control select_cancel">
                                                                </div>
                                                            `);
                                                        } else {
                                                            $(".pickupdatediv").html('');
                                                            $("#carrier_add").attr('disabled', false);
                                                        }
                                                    })
                                                </script>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('picked_up'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">PickedUp HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="11" selected>Pickup</option>
                                                                <option value="12">Deliver</option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--<div class="col-sm-6 col-md-6">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <label class="form-label">EXPECTED DATE</label>-->
                                                    <!--        <input type="date" required name="expected_date"-->
                                                    <!--               id='expected_date' -->
                                                    <!--               class="form-control">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                    <!--<div class="col-sm-6 col-md-6 pickupdatediv2">-->

                                                    <!--</div>-->
                                                    <div class="col-sm-6 col-md-6 deliverdate">

                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row auctionupdate mb-2"></div>
                                                <script>
                                                    $("#pstatus").on('change', function() {
                                                        if ($(this).val() == 12) {
                                                            var order_id = $("#order_id1").val();
                                                            $.ajax({
                                                                url: "{{ url('/get_sheet') }}",
                                                                type: "GET",
                                                                data: {
                                                                    order_id: order_id
                                                                },
                                                                dataType: "JSON",
                                                                success: function(res) {
                                                                    var delivery = '';
                                                                    if (res.delivery_date) {
                                                                        delivery = res.delivery_date;
                                                                    }
                                                                    $(".deliverdate").html(`
                                                                        <div class="form-group">
                                                                            <label class="form-label">DELIVER DATE</label>
                                                                            <input required  type="datetime-local" value="${delivery}"  name="deliver_date" 
                                                                            id='deliver_date'class="form-control">
                                                                            <input type="checkbox" name="approvaldeliver" value="1"/> &nbsp; Mark AS APPROVED (DELIVER)
                                                                        </div>
                                                                    `);
                                                                }
                                                            })
                                                            $.ajax({
                                                                url: "{{ url('/get_carrier2') }}",
                                                                type: "GET",
                                                                data: {
                                                                    order_id: order_id
                                                                },
                                                                dataType: "JSON",
                                                                success: function(res) {
                                                                    var phone1 = '';
                                                                    if (res[0].driverphoneno) {
                                                                        phone1 = res[0].driverphoneno;
                                                                    }
                                                                    $(".auctionupdate").html(`
                                                                        <input type="hidden" value="12" name="pstatus222">
                                                                        <input type="hidden" value="sheet_detail" name="sheet_detail">
                                                                        <div class="col-md-12 text-center"><h4>Delivery Sheet</h4></div>
                                                                        <div class="col-sm-12">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <label>Driver No1#</label>
                                                                                    <input class="form-control driverphoneno" required id="auc_driver_no" name="auc_driver_no" placeholder="Driver No1#" value="${phone1}">
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label>Driver No2#</label>
                                                                                    <input class="form-control driverphoneno" id="auc_driver_no2" name="auc_driver_no2" placeholder="Driver No2#" value="">
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label>Driver No3#</label>
                                                                                    <input class="form-control driverphoneno" id="auc_driver_no3" name="auc_driver_no3" placeholder="Driver No3#" value="">
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <label>Driver No4#</label>
                                                                                    <input class="form-control driverphoneno" id="auc_driver_no4" name="auc_driver_no4" placeholder="Driver No4#" value="">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Driver Status</label>
                                                                            <input id="auc_driver_status" name="auc_driver_status" required class="form-control" placeholder="Driver Status" value="">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Driver Payment Status</label>
                                                                            <input id="auc_driver_payment_status" name="auc_driver_payment_status" required class="form-control" placeholder="Driver Payment Status" value="">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Vehicle Condition</label>
                                                                            <input class="form-control" id="auc_condition" required placeholder="Vehicle Condition" name="auc_condition" value="">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Customer Informed</label>
                                                                            <input class="form-control" id="auc_customer_informed" required placeholder="Customer Informed" name="auc_customer_informed" value="">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Vehicle Position</label>
                                                                            <input id="auc_vehicle_position" name="auc_vehicle_position" required class="form-control" placeholder="Vehicle Position" value="">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Delivery Datetime</label>
                                                                            <input class="form-control" id="auc_delivery_date" type="datetime-local" required  placeholder="Delivery Datetime" name="auc_delivery_date"
                                                                                   value="">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Storage Pay</label>
                                                                            <input id="auc_who_pay_storage" name="auc_who_pay_storage" required class="form-control" placeholder="Who Pay Storage" value="">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Title</label>
                                                                            <select id="auc_title_keys" name="auc_title_keys" required class="form-control h-50">
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Key</label>
                                                                            <select id="auc_keys" name="auc_keys" required class="form-control h-50">
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Client & Status</label>
                                                                            <select id="auc_client_status" required name="auc_client_status" class="form-control h-50">
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Owes Status</label>
                                                                            <select id="auc_owes_status" required name="auc_owes_status" class="form-control h-50">
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Yes">Yes</option>
                                                                                <option value="No">No</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <label>Additional</label>
                                                                            <input class="form-control" required id="auc_additional" placeholder="Additional" name="auc_additional" value="">
                                                                        </div>
                                                                    `);
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
                                                                }
                                                            })
                                                        } else {
                                                            $(".deliverdate").html('');

                                                        }
                                                    })
                                                </script>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('delivered'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">Delivered HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="12">Deliver</option>
                                                                <option value="13">Completed</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6 expectdate">
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row auctionupdate mb-2"></div>
                                                <script>
                                                    $("#pstatus").on('change', function() {
                                                        if ($(this).val() == 13) {
                                                            $(".expectdate").html(`
                                                                <div class="form-group">
                                                                    <label class="form-label">EXPECTED DATE</label>
                                                                    <input type="date" required name="expected_date"
                                                                           id='expected_date' 
                                                                           class="form-control">
                                                                </div>
                                                            `);
                                                            $(".auctionupdate").html(`
                                                                <input type="hidden" value="13" name="pstatus222">
                                                                <input type="hidden" value="sheet_detail" name="sheet_detail">
                                                                <div class="col-md-12 text-center"><h4>Completed Sheet</h4></div>
                                                                <div class="col-md-3">
                                                                    <label>Remarks Status</label>
                                                                    <input class="form-control h-50" id="auc_remarks" required name="auc_remarks" placeholder="Remarks Status" value="">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label>Comments</label>
                                                                    <input class="form-control h-50" id="auc_comments" required name="auc_comments" placeholder="Comments" value="">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label>Satisfied?</label>
                                                                    <input class="form-control h-50" id="auc_satisfied" required name="auc_satisfied" placeholder="How you Satisfied?" value="">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label>Review</label>
                                                                    <select id="auc_review" name="auc_review" class="form-control h-50" required>
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-12" id="all_rating" style="display:none;">
                                                                    <div class="row">
                                                                        <div class="col-sm-6">
                                                                            <label>Website</label>
                                                                            <select id="auc_website" name="auc_website" class="form-control h-50">
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
                                                                            <input class="form-control h-50" id="auc_website_other" name="auc_website_other" placeholder="Other Website" value="">
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label>Rating</label>
                                                                            <select id="auc_client_rating" name="auc_client_rating" class="form-control h-50">
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Positive">Positive</option>
                                                                                <option value="Neutral">Neutral</option>
                                                                                <option value="Negative">Negative</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-6">
                                                                            <label>Website Link</label>
                                                                            <input class="form-control h-50" id="auc_website_link" name="auc_website_link" placeholder="Website Link" value="">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label>Additional</label>
                                                                    <input class="form-control" id="auc_additional" required placeholder="Additional" name="auc_additional" value="">
                                                                </div>
                                                            `);
                                                            $(document).on("change", "#auc_website", function() {
                                                                if ($(this).val() == 'Other') {
                                                                    $("#other_website").show();
                                                                } else {
                                                                    $("#other_website").hide();
                                                                }
                                                            })
                                                            $(document).on("change", "#auc_review", function() {
                                                                if ($(this).val() == 'Yes') {
                                                                    $("#all_rating").show();
                                                                } else {
                                                                    $("#all_rating").hide();
                                                                }
                                                            })
                                                        } else {
                                                            $(".expectdate").html('');

                                                        }
                                                    })
                                                </script>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('completed'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">Completed HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="13">Completed</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--<div class="col-sm-6 col-md-6">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <label class="form-label">EXPECTED DATE</label>-->
                                                    <!--        <input type="date" required name="expected_date"-->
                                                    <!--               id='expected_date' -->
                                                    <!--               class="form-control">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('owns_money'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">OWES MONEY HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="16">OWES MONEY</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif
                                        @if (\Request::is('cancel'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">Cancel HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        id='order_id1' placeholder="" readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="14">Cancel</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--<div class="col-sm-6 col-md-6">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <label class="form-label">EXPECTED DATE</label>-->
                                                    <!--        <input type="date" required name="expected_date"-->
                                                    <!--               id='expected_date' -->
                                                    <!--               class="form-control">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('onapproval'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">On Approval HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        value="{{ $order_id }}" id='order_id1' placeholder=""
                                                        readonly>

                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="18">ON APPROVAL</option>
                                                                <option value="9">LISTED</option>
                                                                <option value="19">ONAPPROVAL CANCEL</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">EXPECTED DATE</label>
                                                            <input type="date" required name="expected_date"
                                                                id='expected_date' class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5 col-md-5">
                                                        <div class="form-group">
                                                            <label class="form-label">LISTED PRICE</label>
                                                            <input type="number" required name="listed_price"
                                                                id='listed_price' class="form-control">
                                                        </div>
                                                    </div>
                                                    @php
                                                        $ptype = 1;
                                                        $query = \App\user_setting::where(
                                                            'user_id',
                                                            Auth::user()->id,
                                                        )->first();
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
                                                            $phoneaccess = [];
                                                        }

                                                    @endphp
                                                    @if (in_array('76', $phoneaccess))
                                                        <?php
                                                        $dis = \App\User::with('daily_ass')
                                                            ->whereHas('userRole', function ($q) {
                                                                $q->where('name', 'Dispatcher');
                                                            })
                                                            ->where('deleted', 0)
                                                            ->get();
                                                        ?>
                                                        <div class="col-sm-3 col-md-3 my-auto">
                                                            <button class="btn btn-primary" type="button"
                                                                id="showingDispatchers" disabled>Assign
                                                                Dispatcher</button>
                                                        </div>
                                                        <div class="col-sm-4 col-md-4" id="showDispatchers"
                                                            style="display:none;">
                                                            <div class="form-group">
                                                                <label class="form-label">Dispatchers <span
                                                                        class="text-muted">(Optional)</span></label>
                                                                <select name="dis_id" id='dis_id'
                                                                    class="form-control">
                                                                    <option value="" selected disabled>Select
                                                                    </option>
                                                                    @foreach ($dis as $key => $dispa)
                                                                        <option value="{{ $dispa->id }}">
                                                                            {{ $dispa->slug ?? $dispa->name . ' ' . $dispa->last_name }}
                                                                            ({{ isset($dispa->daily_ass->total_quote) ? $dispa->daily_ass->total_quote . ' Left' : 'Unlimited' }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">HISTORY</label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row auctionupdate mb-2"></div>
                                                <script>
                                                    $("#pstatus").on('change', function() {
                                                        if ($(this).val() == 9) {
                                                            $(".auctionupdate").html(`
                                                                <input type="hidden" value="9" name="pstatus222">
                                                                <input type="hidden" value="sheet_detail" name="sheet_detail">
                                                                <div class="col-md-12 text-center"><h4>Listed Sheet</h4></div>
                                                                <div class="col-md-4">
                                                                    <label>Paid</label>
                                                                    <select name="auc_paid" id="auc_paid" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Storage</label>
                                                                    <input class="form-control" id="auc_storage" name="auc_storage" placeholder="Storage" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Listed Price</label>
                                                                    <input class="form-control" id="auc_listed_price" placeholder="Listed Price" name="auc_listed_price" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Auction Update</label>
                                                                    <input class="form-control" id="auc_auction_update" placeholder="Auction Update" name="auc_auction_update"
                                                                           value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Title</label>
                                                                    <select id="auc_title_keys" name="auc_title_keys" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Key</label>
                                                                    <select id="auc_keys" name="auc_keys" class="form-control h-50">
                                                                        <option value="" selected disabled>Select</option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Listed Count</label>
                                                                    <input class="form-control" id="auc_listed_count" placeholder="Listed Count" name="auc_listed_count" value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Old/New Price</label>
                                                                    <input class="form-control" id="auc_price" placeholder="Old / New Price" name="auc_price"
                                                                           value="">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Vehicle Position</label>
                                                                    <input class="form-control" placeholder="Vehicle Position" id="auc_port" name="auc_port" value="">
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label>Additional</label>
                                                                    <input class="form-control" id="auc_additional" placeholder="Additional" name="auc_additional" value="">
                                                                </div>
                                                            `);
                                                            $("#expected_date").attr('disabled', false);
                                                            $("#listed_price").attr('disabled', false);
                                                            $("#showingDispatchers").attr('disabled', false);
                                                        } else if ($(this).val() == 18) {
                                                            $("#expected_date").attr('disabled', false);
                                                            $("#listed_price").attr('disabled', true);
                                                            $("#showingDispatchers").attr('disabled', true);
                                                            $("#showDispatchers").hide();
                                                            $("#dis_id").children('option').removeAttr('selected');
                                                            $("#dis_id").children('option').eq(0).attr('selected', true);
                                                        } else {
                                                            $("#expected_date").attr('disabled', true);
                                                            $("#listed_price").attr('disabled', true);
                                                            $("#showingDispatchers").attr('disabled', true);
                                                            $("#showDispatchers").hide();
                                                            $("#dis_id").children('option').removeAttr('selected');
                                                            $("#dis_id").children('option').eq(0).attr('selected', true);
                                                            $(".auctionupdate").html('');
                                                        }
                                                    })
                                                </script>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif

                                        @if (\Request::is('onapproval_cancel'))
                                            <form method="post" action="{{ route('call_history_post') }}"
                                                id="saveChangesForm">
                                                @csrf
                                                <div class="card-title font-weight-bold">On Approval Cancel HISTORY/CHANGE
                                                    STATUS:
                                                </div>
                                                <div class="row">
                                                    <input type="hidden" class="form-control" name="order_id1"
                                                        value="{{ $order_id }}" id='order_id1' placeholder=""
                                                        readonly>

                                                    <div class="col-sm-12 col-md-12" id="last_history"></div>

                                                    @php
                                                        $ptype = 1;
                                                        $query = \App\user_setting::where(
                                                            'user_id',
                                                            Auth::user()->id,
                                                        )->first();
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
                                                            $phoneaccess = [];
                                                        }

                                                    @endphp
                                                    <div class="col-sm-6 col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">CHANGE STATUS</label>
                                                            <select name="pstatus" id='pstatus' required
                                                                class="form-control ">
                                                                <option value="" selected disabled>Select</option>
                                                                @if (in_array('77', $phoneaccess))
                                                                    <option value="14">CANCEL ORDER</option>
                                                                @else
                                                                    <option value="19">ONAPPROVAL CANCEL</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!--<div class="col-sm-6 col-md-6">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <label class="form-label">EXPECTED DATE</label>-->
                                                    <!--        <input type="date" required name="expected_date"-->
                                                    <!--               id='expected_date' -->
                                                    <!--               class="form-control">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                    <div class="col-sm-6 col-md-6" id="mistakers"></div>
                                                    <div class="col-sm-6 col-md-6" id="calls"></div>
                                                    <div class="col-sm-6 col-md-6" id="decisions"></div>

                                                    <div class="col-sm-6 col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">
                                                                @if (in_array('77', $phoneaccess))
                                                                    Admin Remarks
                                                                @else
                                                                    HISTORY
                                                                @endif
                                                            </label>
                                                            <textarea required name="history_update" id='history_update' class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script>
                                                    $("#pstatus").on('change', function() {
                                                        if ($(this).val() == 14) {
                                                            var order_id = $("#order_id1").val();
                                                            $.ajax({
                                                                url: "{{ url('/order_users') }}",
                                                                type: "GET",
                                                                data: {
                                                                    id: order_id
                                                                },
                                                                dataType: "JSON",
                                                                success: function(res) {
                                                                    var ot = 'No Order Taker';
                                                                    var dis = 'No Dispatcher Assigned';
                                                                    var ot_id = '';
                                                                    var dis_id = '';
                                                                    var both = '';
                                                                    if (res.ot) {
                                                                        ot = res.ot;
                                                                    }
                                                                    if (res.dis) {
                                                                        dis = res.dis;
                                                                    }
                                                                    if (res.ot_id) {
                                                                        ot_id = res.ot_id;
                                                                        both = res.ot_id;
                                                                    }
                                                                    if (res.dis_id) {
                                                                        dis_id = res.dis_id;
                                                                        both = both + ',' + res.dis_id
                                                                    }
                                                                    $("#mistakers").html(`
                                                                        <input type="hidden" name="mistaker_id" id="mistaker_id" />
                                                                        <div class="form-group">
                                                                            <label class="form-label">Mistaker</label>
                                                                            <select name="mistaker" id='mistaker' required class="form-control ">
                                                                                <option value="" selected disabled>Select</option>
                                                                                <option value="Customer" data-value="">Customer</option>
                                                                                <option value="${ot}" data-value="${ot_id}">${ot}</option>
                                                                                <option value="${dis}" data-value="${dis_id}">${dis}</option>
                                                                                <option value="Both" data-value="${both}">Both</option>
                                                                            </select>
                                                                        </div>
                                                                    `);
                                                                    $("#calls").html(`
                                                                        <div class="form-group">
                                                                            <label class="form-label">No Of Calls</label>
                                                                            <input type="number" required name="no_of_calls" id="no_of_calls" class="form-control" />
                                                                        </div>
                                                                    `);
                                                                    $("#decisions").html(`
                                                                        <div class="form-group">
                                                                            <label class="form-label">Decision</label>
                                                                            <input type="text" required name="decision" id="decision" class="form-control" />
                                                                        </div>
                                                                    `);

                                                                    $(document).on("change", "#mistaker", function() {
                                                                        $("#mistaker_id").val($(this).children('option:selected').data(
                                                                            'value'));
                                                                    })

                                                                    if (res.last_history) {
                                                                        $("#last_history").html(`
                                                                            <div class="message-feed media m-0 p-0">
                                                                                <div class="media-body">
                                                                                    <div class="mf-content w-100">
                                                                                        <h6>User: ${res.last_history.username}</h6>
                                                                                        ${res.last_history.history}
                                                                                        <h6>
                                                                                            <strong class="mf-date"><i class="fa fa-clock-o"></i>  ${res.last_history.created}</strong>
                                                                                        </h6>
                                                                                        <div class="row">
                                                                                            <div class="col-sm-6">
                                                                                                <input type="radio" value="Agree" name="agree_disagree" id="agree" /> <label class="ml-2" for="agree">Agree</label>
                                                                                            </div>
                                                                                            <div class="col-sm-6">
                                                                                                <input type="radio" checked value="Disagree" name="agree_disagree" id="disagree" /> <label class="ml-2" for="disagree">Disagree</label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        `);
                                                                    } else {
                                                                        $("#last_history").html(`
                                                                            <div class="message-feed media m-0 p-0">
                                                                                <div class="media-body">
                                                                                    <div class="mf-content w-100">
                                                                                        <h6>No Last History</h6>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        `);
                                                                    }
                                                                }
                                                            })
                                                        }
                                                    })
                                                </script>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        @endif
                                        <script>
                                            $("#saveChangesForm").on('submit', function() {
                                                $(this).children('button[type="submit"]').attr('disabled', true);
                                            })
                                        </script>

                                    </div>
                                    <div class="tab-pane" id="tab2">

                                        <div class="chat-body-style ChatBody" id="calhistory"
                                            style="overflow:scroll; height:300px;">
                                            <div class="message-feed media">
                                                <div class="media-body">
                                                    <div class="mf-content">
                                                        hi
                                                    </div>
                                                    <small class="mf-date"><i class="fa fa-clock-o"></i>2021-01-19
                                                        15:53:42
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extraScript')
    <script>
        function showprice() {

            if ($('#relist').is(":checked")) {

                $('#row1').hide();
                $('#r1').show();
                $('#r2').show();
                $('#r3').show();
                $(".getcarrier").removeAttr("required");
                $("#r1").attr("required", true);
                $("#relist_id").attr("required", true);
                $("#expected_date").removeAttr("required");
                $("#current_carrier").removeAttr("required");


            } else {
                $('#row1').show();
                $('#r1').hide();
                $('#r2').hide();
                $('#r3').hide();
                $(".getcarrier").attr("required", true);
                $("#r1").removeAttr("required");
                $("#expected_date").attr("required", true);
                $("#current_carrier").attr("required", true);
                $("#relist_id").removeAttr("required");
            }
        }
        $(document).ready(function() {

            $('input').attr('autocomplete', 'onn');

        });
    </script>

    <script>
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




        $("#form").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: "/send_order_link",
                type: "POST",
                data: new FormData(this),

                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {

                },
                success: function(data) {

                    let test = data.toString();

                    let test2 = $.trim(test);
                    let text = "SUCCESS";
                    if (test2 == text) {

                        $('#success').html(data);
                        $('#modaldemo4').modal('show');
                        $('#reportmodal').modal('hide');

                    } else {
                        $('#not_success').html(data);
                        $('#modaldemo5').modal('show');
                    }
                },
                error: function(e) {
                    $("#err").html(e).fadeIn();
                }
            });
        }));
    </script>


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


        }

        function regain_call() {
            $(".count_user").click(function() {

                var order_id = $(this).closest('tr').find('.order_id').val();
                var pstatus = $(this).closest('tr').find('.pstatus').val();
                var client_email = $(this).closest('tr').find('.client_email').val();
                var client_name = $(this).closest('tr').find('.client_name').val();
                var client_phone = $(this).closest('tr').find('.client_phone').val();

                //alert(order_id + " " + pstatus + " " + client_email);

                var data = {
                    order_id: order_id,
                    pstatus: pstatus,
                    client_email: client_email,
                    client_name: client_name
                };
                $.ajax({
                    type: "GET",
                    url: 'count_user',
                    dataType: "json",
                    data: data,
                    beforeSend: function() {

                    },
                    complete: function() {

                    },
                    success: function(response) {
                        if (response) {
                            window.location.href = "rcmobile://call?number=" + client_phone;
                        }

                    }
                });


            });
        }


        $(".compare").click(function() {
            var order_id = $(this).closest('tr').find('.order_id').val();
            var olcation = $(this).closest('tr').find('.location1').val();
            var dlcation = $(this).closest('tr').find('.location2').val();
            $.ajax({

                url: "/get_carrier_by_location",
                type: "GET",
                //dataType: "json",
                data: {
                    olcation: olcation,
                    dlcation: dlcation
                },

                success: function(data) {
                    //s

                    $('#table_data_carrier').html(data);

                }

            });

        });


        function regain_status() {
            $(".updatee").click(function() {

                var order_id = $(this).closest('tr').find('.order_id').val();
                $("#order_id1").attr("value", order_id);
                $('#ask_low').html('');


                var id = $("#order_id1").val();

                $.ajax({

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
                        $('#largemodal').modal('show');
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
                            //alert(data["driver_pickup_date"]);
                            $(".pickupdatediv").append(`<div class="form-group"><label class="form-label">PICKUP DATE</label>
                            <input type="date" value="` + data["driver_pickup_date"] +
                                `" required name="pickup_date"
                            id='pickup_date'class="form-control"><input type="checkbox" name="approvalpickup" value="0"/>MARK AS APPROVED</div>`);


                        },
                        error: function(e) {
                            alert("error");
                        }

                    });
                }


                if (titlee == "listed") {

                    $("#current_carrier").html('');
                    var order_id = document.getElementById('order_id1').value;
                    var options = "<option value=''>Select Carrier</option>";
                    $.ajax({

                        type: "GET",
                        url: "/get_carrier",
                        data: {
                            'order_id': order_id
                        },
                        dataType: "json",

                        success: function(data) {
                            $.each(data, function(i, item) {

                                options = options + `<option value='` + item.id + `'>` + item
                                    .companyname + `</option>`;

                            });
                            //$("#current_carrier").remove();
                            $("#current_carrier").append(options);
                        },
                        error: function(e) {
                            alert("error");
                        }

                    });
                }


            });
            if (titlee == "picked_up") {
                $(".pickupdatediv2").html('');
                $.ajax({

                    type: "GET",
                    url: "/get_pickup_date",
                    data: {
                        'order_id': order_id
                    },
                    dataType: "json",

                    success: function(data) {
                        //alert(data["driver_pickup_date"]);
                        $(".pickupdatediv").append(`<div class="form-group"><label class="form-label">PICKUP DATE</label>
                        <input type="text" value="` + data["driver_pickup_date"] + `"  name="pickup_date1"
                        id='pickup_date1'class="form-control"></div>`);


                    },
                    error: function(e) {
                        alert("error");
                    }

                });
            }
        }

        $("body").delegate("#keywords", "click", function() {
            setTimeout(function() {
                $('input[name="keywords"]').focus()
            }, 100);
        });

        $("body").delegate("#search_by", "change", function() {

            var search_by = $('#search_by').val();
            if (search_by == "ophone") {

                $("#keywords").mask("(999) 999-9999");
                setTimeout(function() {
                    $('input[name="keywords"]').focus()
                }, 100);
            } else {
                $("#keywords").unmask();
                setTimeout(function() {
                    $('input[name="keywords"]').focus()
                }, 100);

            }
        });


        $("body").delegate("#pstatus", "change", function() {
            var p_status = $('#pstatus').val();
            if (p_status == 3) {

                $('#ask_low').html(`<div class="form-group">
                        <label class="form-label">Asking Low Price</label>
                <input required type="number" min="0" step="0.01" name="asking_low"
                id='asking_low'
            class="form-control">
                        </div>`)

            }
        });
    </script>
    <script>
        function return_data() {
            var search_by = $('#search_by').val();
            var val = $('#keywords').val();
            var titlee = $('#titlee').val();

            $('#table_data').html('');
            $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);
            $.ajax({

                url: "return_data",
                type: "GET",
                data: {
                    val: val,
                    search_by: search_by,
                    titlee: titlee
                },
                success: function(data) {
                    $('#table_data').html('');
                    $('#table_data').html(data);
                },
                complete: function(data) {
                    $('#ldss').hide();
                    regain();
                }

            });
        }
    </script>
    <script>
        $(document).ready(function() {

            $(".count_user").click(function() {

                var order_id = $(this).closest('tr').find('.order_id').val();
                var pstatus = $(this).closest('tr').find('.pstatus').val();
                var client_email = $(this).closest('tr').find('.client_email').val();
                var client_name = $(this).closest('tr').find('.client_name').val();
                var client_phone = $(this).closest('tr').find('.client_phone').val();

                //alert(order_id + " " + pstatus + " " + client_email);

                var data = {
                    order_id: order_id,
                    pstatus: pstatus,
                    client_email: client_email,
                    client_name: client_name
                };
                $.ajax({
                    type: "GET",
                    url: 'count_user',
                    dataType: "json",
                    data: data,
                    beforeSend: function() {

                    },
                    complete: function() {

                    },
                    success: function(response) {
                        if (response) {

                            window.location.href = "rcmobile://call?number=" + client_phone;

                        }

                    }
                });


            });

            $(document).on('click', '.pagination a', function(event) {

                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data3(page);
                $.cookie("page", page, {
                    expires: 1
                });

            });


            function fetch_data3(page) {
                var titlee = $('#titlee').val();

                $('#table_data').html('');
                $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

                $.ajax({

                    url: "fetch_data_profit?page=" + page + "&titlee=" + titlee,
                    success: function(data) {
                        $('#table_data').html('');
                        $('#table_data').html(data);

                    },
                    complete: function(data) {
                        $('#ldss').hide();
                        regain();
                    }

                })

            }
            let cookie = $.cookie("page");
            if (cookie) {
                fetch_data3(cookie);
                $.removeCookie("page");
            }


            $(".updatee").click(function() {

                var order_id = $(this).closest('tr').find('.order_id').val();
                $("#order_id1").attr("value", order_id);
                $('#ask_low').html('');

                var id = $("#order_id1").val();

                $.ajax({

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
                        $('#largemodal').modal('show');
                    }

                });


                var titlee = $('#titlee').val();
                // alert(titlee);
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
                            <input type="date" value="` + data["driver_pickup_date"] + `" required name="pickup_date"
                            id='pickup_date'class="form-control"></div><div class="form-group"><input type="checkbox" name="approvalpickup" value="1"/>
                                    &nbsp; MARK AS APPROVED
                            </div>`);


                        },
                        error: function(e) {
                            alert("error");
                        }

                    });
                }


                if (titlee == "listed") {

                    $("#current_carrier").html('');
                    var order_id = document.getElementById('order_id1').value;
                    var options = "<option value=''>Select Carrier</option>";
                    $.ajax({

                        type: "GET",
                        url: "/get_carrier",
                        data: {
                            'order_id': order_id
                        },
                        dataType: "json",

                        success: function(data) {
                            $.each(data, function(i, item) {

                                options = options + `<option value='` + item.id + `'>` +
                                    item.companyname + `</option>`;

                            });
                            //$("#current_carrier").remove();
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
                            //alert(data["driver_pickup_date"]);<div class="col-sm-6 col-md-6 pickupdatediv2">

                            $(".pickupdatediv2").append(`<div class="form-group"><label class="form-label">PICKUP DATE</label>
                            <input readonly type="text" value="` + data["driver_pickup_date"] + `"  name="pickup_date1"
                            id='pickup_date1'class="form-control"></div>`);

                            $(".deliverdate").append(`<div class="form-group"><label class="form-label">DELIVER DATE</label>
                            <input required  type="date" value="` + data["driver_deliver_date"] +
                                `"  name="deliver_date"
                            id='deliver_date'class="form-control"></br>
                                    <input type="checkbox" name="approvaldeliver" value="1"/> &nbsp; Mark AS APPROVED (DELIVER)</div>`);

                        },
                        error: function(e) {
                            alert("error");
                        }

                    });
                }


            });
        });
    </script>

    <script>
        $("#showingDispatchers").on('click', function() {
            $("#showDispatchers").toggle();
        });
        // $("#auction_storage").on('change',function () {
        //     if($(this).val() == 1)
        //     {
        //         $("#already").show();
        //         $("#late").hide();
        //     }
        //     else if($(this).val() == 2)
        //     {
        //         $("#late").show();
        //         $("#already").hide();
        //     }
        //     else
        //     {
        //         $("#late").hide();
        //         $("#already").hide();
        //     }
        // })
    </script>

    <script>
        $(".already_late").on('change', function() {
            if ($(this).is(":checked")) {
                $(this).parent('div').parent('div').siblings('.auction_already_late').show();
            } else {
                $(this).parent('div').parent('div').siblings('.auction_already_late').hide();
            }
        })
    </script>
    <!--Scrolling Modal-->
@endsection
