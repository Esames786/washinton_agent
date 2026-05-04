@include('main.phone_quote.question.return_function')
<form action="{{url('/show-data/search')}}" method="GET">
    @csrf
    <div class="globalSearch">
        <div class="globalSearch_">
            <span>Global Search</span>
            <input type="text" placeholder="Search" class="global_search" name="global_search">
            <button class="btn btn-info globalBtn" type="submit">Search</button>
        </div>
    </div>
</form>
<input type="hidden" class="global" value="{{isset($search) ? $search : ''}}">

<div class="searchmain">
    <div class="searchmainright">
        <span>Search for</span>
        <select name="searchIn" class="form-control searchIn">
            <option value="id">Order ID</option>
            <option value="created_at">Date</option>
            <option value="originzsc">Origin</option>
            <option value="destinationzsc">Destination</option>
            <option value="pay_carrier">Carrier/Pay</option>
            <option value="ymk">Vechile Name</option>
        </select>
        <input type="text" class="form-control searchWith" placeholder="Fill the search and hit enter">
    </div>
    <div class="searchmainleft">
        <span>Show</span>
        <select name="show" id="" class="searchFilter">
            <option value="" disabled>--disable--</option>
            <option value="5">5</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="250">250</option>
            <option value="500">500</option>
        </select>
    </div>
</div>
@if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{Session::get('success')}}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
@endif
<?php 
    $showData = explode(',',Auth::user()->emp_show_data);
?>
<div class="ChatView">
    <div class="tabMain">
        <div class="tabMainbtn">
            @if (in_array("1", $showData))<a href="{{url('/show-data/new')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'new') ? 'active' : ''}}">New <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(0,0) > 99 ? '99+' : listedToCancel(0,0)}}</span></a>@endif
            @if (in_array("2", $showData))<a href="{{url('/show-data/followup')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'followup') ? 'active' : ''}}">Follow Up <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(2,0) > 99 ? '99+' : listedToCancel(2,0)}}</span></a>@endif
            @if (in_array("3", $showData))<a href="{{url('/show-data/interested')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'interested') ? 'active' : ''}}">Interested <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(1,0) > 99 ? '99+' : listedToCancel(1,0)}}</span></a>@endif
            @if (in_array("4", $showData))<a href="{{url('/show-data/not_interested')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'not_interested') ? 'active' : ''}}">Not Interested<span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(4,0) > 99 ? '99+' : listedToCancel(4,0)}}</span></a>@endif
            @if (in_array("5", $showData))<a href="{{url('/show-data/asking_low')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'asking_low') ? 'active' : ''}}">Asking Low <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(3,0) > 99 ? '99+' : listedToCancel(3,0)}}</span></a>@endif
            @if (in_array("6", $showData))<a href="{{url('/show-data/not_responding')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'not_responding') ? 'active' : ''}}">Not Responding <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(5,0) > 99 ? '99+' : listedToCancel(5,0)}}</span></a>@endif
            @if (in_array("7", $showData))<a href="{{url('/show-data/time_quote')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'time_quote') ? 'active' : ''}}">Time Quote<span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(6,0) > 99 ? '99+' : listedToCancel(6,0)}}</span></a>@endif
            @if (in_array("8", $showData))<a href="{{url('/show-data/payment_missing')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'payment_missing') ? 'active' : ''}}">Payment Missing<span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(7,0) > 99 ? '99+' : listedToCancel(7,0)}}</span></a>@endif
            @if (in_array("9", $showData))<a href="{{url('/show-data/on_approval')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'on_approval') ? 'active' : ''}}">On Approval<span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(18,0) > 99 ? '99+' : listedToCancel(18,0)}}</span></a>@endif
            @if (in_array("10", $showData))<a href="{{url('/show-data/on_approval_cancel')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'on_approval_cancel') ? 'active' : ''}}">On Approval Cancel <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(19,0) > 99 ? '99+' : listedToCancel(19,0)}}</span></a>@endif
            @if (in_array("11", $showData))<a href="{{url('/show-data/booked')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'booked') ? 'active' : ''}}">Booked<span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(8,0) > 99 ? '99+' : listedToCancel(8,0)}}</span></a>@endif
            @if (in_array("12", $showData))<a href="{{url('/show-data/listed')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'listed') ? 'active' : ''}}">Listed <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(9,0) > 99 ? '99+' : listedToCancel(9,0)}}</span></a>@endif
            @if (in_array("13", $showData))<a href="{{url('/show-data/schedule')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'schedule') ? 'active' : ''}}">Schedule <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(10,0) > 99 ? '99+' : listedToCancel(10,0)}}</span></a>@endif
            @if (in_array("14", $showData))<a href="{{url('/show-data/not-pickedup')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'not-pickedup') ? 'active' : ''}}">Not PickUp <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(11,0) > 99 ? '99+' : listedToCancel(11,0)}}</span></a>@endif
            @if (in_array("15", $showData))<a href="{{url('/show-data/pickedup')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'pickedup') ? 'active' : ''}}">PickUp <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(11,1) > 99 ? '99+' : listedToCancel(11,1)}}</span></a>@endif
            @if (in_array("16", $showData))<a href="{{url('/show-data/not-delivered')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'not-delivered') ? 'active' : ''}}">Not Delivered <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(12,0) > 99 ? '99+' : listedToCancel(12,0)}}</span></a>@endif
            @if (in_array("23", $showData))<a href="{{url('/show-data/schedule-for-delivery')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'schedule-for-delivery') ? 'active' : ''}}">Schedule For Delivery <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(12,2) > 99 ? '99+' : listedToCancel(12,2)}}</span></a>@endif
            @if (in_array("17", $showData))<a href="{{url('/show-data/delivered')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'delivered') ? 'active' : ''}}">Delivered <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(12,1) > 99 ? '99+' : listedToCancel(12,1)}}</span></a>@endif
            @if (in_array("18", $showData))<a href="{{url('/show-data/complete')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'complete') ? 'active' : ''}}">Complete <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(13,0) > 99 ? '99+' : listedToCancel(13,0)}}</span></a>@endif
            @if (in_array("19", $showData))<a href="{{url('/show-data/cancel')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'cancel') ? 'active' : ''}}">Cancel <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(14,0) > 99 ? '99+' : listedToCancel(14,0)}}</span></a>@endif
            @if (in_array("20", $showData))<a href="{{url('/show-data/deleted')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'deleted') ? 'active' : ''}}">Deleted <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(15,0) > 99 ? '99+' : listedToCancel(15,0)}}</span></a>@endif
            @if (in_array("21", $showData))<a href="{{url('/show-data/owes_money')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'owes_money') ? 'active' : ''}}">Owes Money <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(16,0) > 99 ? '99+' : listedToCancel(16,0)}}</span></a>@endif
            @if (in_array("22", $showData))<a href="{{url('/show-data/auction_not_win')}}" class="btn btn-primary position-relative {{(Request::segment(2) == 'auction_not_win') ? 'active' : ''}}">Not Win Auction <span class="rounded-circle bg-danger text-light position-absolute" style="height: 30px;width: 30px;display: flex;align-items: center;justify-content: center;right: -15px;bottom: 20px;">{{listedToCancel(22,0) > 99 ? '99+' : listedToCancel(22,0)}}</span></a>@endif
        </div>
    </div>