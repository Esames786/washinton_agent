@extends('layouts.innerpages')
@section('template_title')
    Customer Nature Report
@endsection
@section('content')
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
   <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <style>
        .col-sm-6 .card
        {
            transition: all .2s;
        }
        .col-sm-6 .card:hover{
            box-shadow: 0 0px 30px 0 rgb(35 43 54 / 15%);
            scale: 1.02;
        }
        .col-sm-6 .card .card-title
        {
            font-weight:400;
        }
        .col-sm-6 .card .card-title span
        {
            font-size: 12px;
        }
        .col-sm-6 .card .card-header
        {
            font-weight:700;
        }
        .col-sm-6 .card .card-header .dropdown{
            position:absolute;
            right:0;
        }
        .col-sm-6 .card .card-header .dropdown button::after{
            color:#000;
        }
        .col-sm-6 .card .card-header .dropdown button{
            background: transparent;
            outline: none;
            border: none;
        }
        .col-sm-6 .card .card-header .dropdown div a{
            font-size:12px;
        }
        .col-sm-6 .card .card-header .dropdown div{
            left: unset !important;
            right: 0px;
        }
        .col-sm-6 .card .card-header span{
            font-size: 11px;
            color:#fff;
        }
        /* Style the tab */
        .table-responsive{
            overflow:unset !important;
        }
        
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        /* Style the buttons that are used to open the tab content */
        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }
        .tabcontent {
            animation: fadeEffect 1s; /* Fading effect takes 1 second */
        }
        
        .dropdown-menu{
            left:-6rem !important;
        }
        
        .bg-yellow
        {
            background-color:#c3c300 !important;
        }
        
        .bg-orange
        {
            background-color:#F49917 !important;
        }
        .bg-pink {
            background: #E91E63 !important;
        }
        .bg-amber {
            background: #FF6F00 !important;
        }
        .bg-teal {
            background: #004D40 !important;
        }

        /* Go from zero to full opacity */
        @keyframes fadeEffect {
            from {opacity: 0;}
            to {opacity: 1;}
        }
        
#popup {
    position: fixed;
    top: 50%;
    color: black;
    left: 50%;
    HEIGHT: auto;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 20px;
    border: 1px solid #1ea7db;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}
#popup button {
    float: inline-end;
    border: 1px solid #d1dde1;
    background: #17a2b8;
    color: white;
    padding: 2px 20px;
}


.popover__message {
  text-align: center;
}

    </style>
    <!-- Row -->
    @include('partials.mainsite_pages.return_function')
    <div class="row">
        <div class="col-12">
            @if(session('flash_message'))
                <div class="alert alert-success">
                    {{session('flash_message')}}
                </div>
            @endif
            <!--div-->
            <div class="page-header">
                <div class="text-secondary text-center text-uppercase w-100">
                    <h1 class="my-4"><b>Customer Nature Report</b></h1>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header d-block">
                    <div class="row">
                        <div class="col-sm-3 my-auto">
                            <label class="form-label">Daterange </label>
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' name="date_range"  id="date_range" class="form-control" />
                                <span class="input-group-addon" style="
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
                        <div class="col-sm-3 my-auto">
                            <label class="form-label">User:</label>
                            <select class="form-control select2" id="user">
                                <option value="" selected>All</option>
                                @foreach($users as $key => $val)
                                    <option value="{{$val->id}}">{{$val->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3 my-auto">
                            <label class="form-label">Status:</label>
                            <select class="form-control select2" id="status">
                                <option value="" selected>All</option>
                                <option value="Verified">Positive</option>
                                <option value="Unverified">Negative</option>
                            </select>
                        </div>
                        <div class="col-sm-1 mt-auto mb-1">
                            <button class="btn btn-primary" id="submit">Search</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{-- <div class="row" id="allData"> </div> --}}
                    <div class="table-responsive" id="searchData">
                        @include('main.phone_quote.new_quote.filterCustomerNatureList')
                    </div>
                </div>
                {{-- <div class="col-sm-12 mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            Showing {{$nature->firstItem() ?? 0}} to {{$nature->lastItem() ?? 0}} of {{$nature->total()}} results
                        </div>
                        <div class="col-md-6 justify-content-end d-flex">                
                            {{$nature->links()}}
                        </div>
                    </div>
                </div> --}}
                {{-- <div id="pagination-container">
                    {{ $nature->links() }}
                </div> --}}
            </div>
        </div>
    </div>
    <!-- /Row -->
@endsection

@section('extraScript')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
      $(document).ready(function() {
        let table = $('#myTable').DataTable();
    });

        $("#submit").click(function(){
            var user = $("#user").children('option:selected').val();
            var status = $("#status").children('option:selected').val();
            var date_range = $("#date_range").val();
            var page = 1; // Define the page variable
            console.log(date_range);
            $.ajax({
                url:"{{url('/customerNature/filter')}}",
                type:"GET",
                data:{date_range:date_range,user:user,status:status,page:page},
                // beforeSend: function () {
                //     $('#allData').html("");
                //     $('#allData').append(`<div class="lds-hourglass" id='ldss'></div>`);
                // },
                success:function(res)
                {
                    console.log('resres', res);
                    $("#searchData").html("");
                    $("#searchData").html(res);
                    $("#pagination-container").html(res.pagination);
                }
            });
        })
        $(function () { 
            new Date();
            $('#date_range').daterangepicker({
                "showDropdowns": true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                "alwaysShowCalendars": true,
                // "startDate": new Date(date.getFullYear(), date.getMonth(), 1),
                // "endDate": new Date(date.getFullYear(), date.getMonth() + 1, 0),
                "opens": "center",
                "drops": "auto"
            }, function (start, end, label) {
                // console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                $('#date_range').val("{{\Carbon\Carbon::now()->startOfMonth()->format('m/d/Y')}} - {{\Carbon\Carbon::now()->format('m/d/Y')}}");
            });
            $('#date_range').val("{{\Carbon\Carbon::now()->startOfMonth()->format('m/d/Y')}} - {{\Carbon\Carbon::now()->format('m/d/Y')}}");
        });

        $("body").delegate(".cancelBtn", "click", function(){
            $('#date_range').val('');
        });
    </script>
    <script>
        function showPopup(element) {
            var description = element.parentElement.getAttribute('data-description');
            document.getElementById('popup-content').innerHTML = description;
            document.getElementById('popup').style.display = 'block';
        }
    
        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }

    </script>
@endsection

