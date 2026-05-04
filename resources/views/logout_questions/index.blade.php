@extends('layouts.innerpages')
@section('template_title')
Logout Questions
@endsection
@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    .col-sm-6 .card {
        transition: all .2s;
    }

    .col-sm-6 .card:hover {
        box-shadow: 0 0px 30px 0 rgb(35 43 54 / 15%);
        scale: 1.02;
    }

    .col-sm-6 .card .card-title {
        font-weight: 400;
    }

    .col-sm-6 .card .card-title span {
        font-size: 12px;
    }

    .col-sm-6 .card .card-header {
        font-weight: 700;
    }

    .col-sm-6 .card .card-header .dropdown {
        position: absolute;
        right: 0;
    }

    .col-sm-6 .card .card-header .dropdown button::after {
        color: #000;
    }

    .col-sm-6 .card .card-header .dropdown button {
        background: transparent;
        outline: none;
        border: none;
    }

    .col-sm-6 .card .card-header .dropdown div a {
        font-size: 12px;
    }

    .col-sm-6 .card .card-header .dropdown div {
        left: unset !important;
        right: 0px;
    }

    .col-sm-6 .card .card-header span {
        font-size: 11px;
        color: #fff;
    }

    .table-responsive {
        overflow: unset !important;
    }

    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
    }

    .tab button:hover {
        background-color: #ddd;
    }

    .tab button.active {
        background-color: #ccc;
    }

    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }

    .tabcontent {
        animation: fadeEffect 1s;
    }

    .dropdown-menu {
        left: -6rem !important;
    }

    .bg-yellow {
        background-color: #c3c300 !important;
    }

    .bg-orange {
        background-color: #F49917 !important;
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
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
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
@include('partials.mainsite_pages.return_function')
<div class="row">
    <div class="col-12">
        @if(session('flash_message'))
            <div class="alert alert-success">
                {{session('flash_message')}}
            </div>
        @endif
        <div class="page-header">
            <div class="text-secondary text-center text-uppercase w-100">
                <h1 class="my-4"><b>Logout Questions</b></h1>
            </div>
        </div>
        <div class="card mt-5">
            {{-- <div class="card-header d-block">
                <div class="row">
                    <div class="col-sm-3 my-auto">
                        <label class="form-label">Daterange </label>
                        <div class='input-group date' id='datetimepicker1'>
                            <input type='text' name="date_range" id="date_range" class="form-control" />
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
                    <div class="col-sm-1 mt-auto mb-1">
                        <button class="btn btn-primary" id="submit">Search</button>
                    </div>
                </div>
            </div> --}}
            <div class="card-body">
                <a class="btn btn-primary" href="{{ route('logout_questions.create') }}">Add New</a>
                
                <div class="form-group">
                    <label for="roleFilter">Role</label>
                    <select id="roleFilter" class="form-control">
                        <option value="">All Roles</option>
                        <option value="Order Taker">Order Taker</option>
                        <option value="Delivery Boy">Delivery Boy</option>
                        <option value="Dispatcher">Dispatcher</option>
                        <option value="QA">QA</option>
                        <option value="QA DISPATCH">QA DISPATCH</option>
                        <option value="Price Checker">Price Checker</option>
                        <option value="O.T TEAM LEAD">O.T TEAM LEAD</option>
                        <option value="D.BOOKING CHECKER">D.BOOKING CHECKER</option>
                    </select>
                </div>
                
                <div class="table-responsive" id="searchData">
                    @include('logout_questions.table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extraScript')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(document).ready(function () {
        let table = $('#myTable').DataTable();
        
        $('#roleFilter').on('change', function() {
            table.columns(2).search(this.value).draw(); // Apply filter on the Role column
        });
    });

    $("#submit").click(function () {
        var user = $("#user").children('option:selected').val();
        var status = $("#status").children('option:selected').val();
        var date_range = $("#date_range").val();
        var page = 1;
        console.log(date_range);
        $.ajax({
            url: "{{url('/customerNature/filter')}}",
            type: "GET",
            data: { date_range: date_range, user: user, status: status, page: page },
            success: function (res) {
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
            "opens": "center",
            "drops": "auto"
        }, function (start, end, label) {
            $('#date_range').val("{{\Carbon\Carbon::now()->startOfMonth()->format('m/d/Y')}} - {{\Carbon\Carbon::now()->format('m/d/Y')}}");
        });
        $('#date_range').val("{{\Carbon\Carbon::now()->startOfMonth()->format('m/d/Y')}} - {{\Carbon\Carbon::now()->format('m/d/Y')}}");
    });

    $("body").delegate(".cancelBtn", "click", function () {
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