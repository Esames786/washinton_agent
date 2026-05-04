@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim('Demand Order','/'))}}
@endsection
@section('content')

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
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
            color: rgb(0 0 0);
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .table-bordered, .text-wrap table, .table-bordered th, .text-wrap table th, .table-bordered td, .text-wrap table td {
            border: 1px solid rgb(0 0 0);
        }

        .table > tbody > tr > td, .table > thead > tr > th {
            font-weight: 500;
            -webkit-transition: all .3s ease;
            font-size: 18px;
            color: rgb(0 0 0);
            padding:10px;
        }

        /* Style the tab */
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

        /* Go from zero to full opacity */
        @keyframes fadeEffect {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        td{
            vertical-align:middle !important;
        }
    </style>
    <!--/app header-->                                                <!--Page header-->
    <div class="page-header">
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Demand Order</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            @if(session('flash_message'))
                <div class="alert alert-success">
                    {{session('flash_message')}}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        <!--div-->
            <div class="card">
                <div class="card-header d-block">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="form-label mb-0" for="search">Search</label>
                                <input type="text" class="form-control" id="search" name="search" placeholder="Search OrderId or Email..." />
                            </div>
                        </div>
                        <div class="col-sm-2 mt-5">
                            <button type="button" class="btn btn-primary" id="searchbtn">Search</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="d-flex justify-content-between">                        
                            <h3 class="my-auto">Demand Order</h3>
                            <button type="button" data-toggle="modal" data-target="#sendLink" title="Send Vehicle Demand Link!" class="btn btn-outline-info">
                                Send Vehicle Demand Link
                            </button>
                        </div>
                        <br>
                        <div id="table_data">
                            @include('main.demand.search')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end app-content-->

    <div class="modal" id="sendLink">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-header pd-x-20">
                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Send Email Link</h6>
                </div>
                <div class="modal-body pd-20">
                    <form action="" method="post" id="form">
                        @csrf
                        <div class="form-group">
                            Reference Order Id<br>
                            <input type="number" name="order_id" id="order_id"
                                   class="form-control"
                                   value="" placeholder="Enter reference order id..."/>
                        </div>
                        <div class="form-group">
                            Email<br>
                            <input type="text" name="email" id="email"
                                   class="form-control"
                                   value="" placeholder="Enter email address..."/>
                        </div>
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="form-group">
                                    Email Link<br>
                                    <input type="text" readonly name="link" id="link"
                                           class="form-control"
                                           value=""/>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-5">
                                <button type="button" class="btn btn-primary" id="createlink">Create Link</button>
                            </div>
                            <div class="col-sm-12" id="msg"></div>
                        </div>
                        <button type="submit" class="btn btn-primary pd-x-20" id="submit" disabled>Submit
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeModal">Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
    {{--<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>--}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    
    <script>
        $("#createlink").on('click',function(){
            $("#msg").html('');
            var email = $("#email").val();
            var order_id = $("#order_id").val();
            if(email && order_id)
            {
                $.ajax({
                    url:"{{url('/demand_order/add')}}",
                    type:"GET",
                    dataType:"JSON",
                    data:{email:email,order_id:order_id},
                    success:function(res)
                    {
                        var id = btoa(res.id);
                        var uu_id = btoa(res.user_id);
                        var link = `{{ url('/demand_order') }}/${id}/${uu_id}`;
                        $("#link").val(link);
                        $("#form").attr('action',`{{url('/demand_order/store')}}/${res.id}`);
                        $("#createlink").attr('disabled',true);
                        $("#submit").attr('disabled',false);
                    }
                })
            }
            else
            {
                $("#msg").html(`<div class="alert bg-danger text-light">Enter an email and order id first!</div>`);
            }
        })
        
        $("#email").keyup(function(){
            var email = $("#email").val();
            var order_id = $("#order_id").val();
            if(!email && order_id)
            {
                $("#createlink").attr('disabled',false);
                $("#submit").attr('disabled',true);
                $("#link").val('');
            }
        })
        
        $("#order_id").keyup(function(){
            var email = $("#email").val();
            var order_id = $("#order_id").val();
            if(!email && order_id)
            {
                $("#createlink").attr('disabled',false);
                $("#submit").attr('disabled',true);
                $("#link").val('');
            }
        })
        
        $("#closeModal").on('click',function(){
            $("#createlink").attr('disabled',false);
            $("#submit").attr('disabled',true);
            $("#link").val('');
            $("#email").val('');
        })
        
        function searchDemand(page)
        {
            $('#table_data').html('');
            $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);
            var search = $("#search").val();
            $.ajax({
                url:"{{url('/demand_order')}}?page="+page,
                type:"GET",
                dataType:"HTML",
                data:{search:search},
                success:function(res)
                {
                    $("#table_data").html('');
                    $("#table_data").html(res);
                }
            })
        }
        
        $(document).on("click","#searchbtn",function(){
            searchDemand(1);
        })

        $(document).on('click', '.pagination a', function (event) {

            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            searchDemand(page);
        });
    </script>
@endsection


