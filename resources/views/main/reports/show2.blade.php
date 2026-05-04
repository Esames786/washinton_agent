@include('partials.mainsite_pages.return_function')
<?php 
    $emp_report = explode(',',Auth::user()->emp_access_report);
?>
<div class="row" id="allData">
    @if(in_array("0",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="0">
                New <span class="rounded badge badge-success ml-2"><span>{{$new}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("1",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="1">
                Interested <span class="rounded badge badge-success ml-2"><span>{{$int}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("2",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="2">
                Follow More <span class="rounded badge badge-success ml-2"><span>{{$fm}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("3",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="3">
                Asking Low <span class="rounded badge badge-success ml-2"><span>{{$al}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("4",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="4">
                Not Interested <span class="rounded badge badge-success ml-2"><span>{{$not_int}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("5",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="5">
                No Response <span class="rounded badge badge-success ml-2"><span>{{$nr}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("6",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="6">
                Time Quote <span class="rounded badge badge-success ml-2"><span>{{$tq}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("7",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="7">
                Payment Missing <span class="rounded badge badge-success ml-2"><span>{{$pm}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("18",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="18">
                OnApproval <span class="rounded badge badge-success ml-2"><span>{{$oa}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("8",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="8">
                Booked <span class="rounded badge badge-success ml-2"><span>{{$book}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("9",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="9">
                Listed <span class="rounded badge badge-success ml-2"><span>{{$list}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("10",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="10">
                Schedule <span class="rounded badge badge-success ml-2"><span>{{$dis}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("34",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="34">
                Schedule To Another Driver <span class="rounded badge badge-success ml-2"><span>{{$dis_app}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("30",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="30">
                Pickup Approval <span class="rounded badge badge-success ml-2"><span>{{$pick_app}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("11",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="11">
                Pickup <span class="rounded badge badge-success ml-2"><span>{{$pick}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("31",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="31">
                Delivery Approval <span class="rounded badge badge-success ml-2"><span>{{$del_app}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("32",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="32">
               Schedule For Delivery <span class="rounded badge badge-success ml-2"><span>{{$sfd}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("12",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="12">
                Delivered <span class="rounded badge badge-success ml-2"><span>{{$del}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("13",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="13">
                Completed <span class="rounded badge badge-success ml-2"><span>{{$com}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("14",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="14">
                Cancel <span class="rounded badge badge-success ml-2"><span>{{$can}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("19",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="19">
                OnApprovalCancel <span class="rounded badge badge-success ml-2"><span>{{$opcan}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("20",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="20">
                Relist <span class="rounded badge badge-success ml-2"><span>{{$rl}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("21",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="21">
                Price Raise <span class="rounded badge badge-success ml-2"><span>{{$pr}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("22",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="22">
                Approach Id <span class="rounded badge badge-success ml-2"><span>{{$ai}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("23",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="23">
                Different Port <span class="rounded badge badge-success ml-2"><span>{{$dp}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("24",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="24">
                Carrier Update <span class="rounded badge badge-success ml-2"><span>{{$cu}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("25",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="25">
                Storage <span class="rounded badge badge-success ml-2"><span>{{$store}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("26",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="26">
                Approaching <span class="rounded badge badge-success ml-2"><span>{{$app}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("27",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="27">
                Auction Update Request <span class="rounded badge badge-success ml-2"><span>{{$aur}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("28",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="28">
                Move To Storage <span class="rounded badge badge-success ml-2"><span>{{$move_to_storage}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("29",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="29">
                Double Booking <span class="rounded badge badge-success ml-2"><span>{{$db}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("33",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="33">
                Auction Update <span class="rounded badge badge-success ml-2"><span>{{$auction_update}}</span>
            </div>
        </div>
    </div>
    @endif
    @if(in_array("35",$emp_report))
    <div class="col-sm-3">
        <div class="card bg-white mb-3">
            <div class="card-header d-flex justify-content-center text-capitalize bg-light showQuotes" data-value="35">
                Auction Storage <span class="rounded badge badge-success ml-2"><span>{{$auction_storage}}</span>
            </div>
        </div>
    </div>
    @endif
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
    <div class="col-sm-3">
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
    <div class="col-sm-3">
        <label for="search" class="form-label">Search Value</label>
        <input type="text" id="search" class="form-control" placeholder="Search Value" />
    </div>
    <div class="col-sm-3">
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
</div>
<div class="row" id="tableData">
</div>