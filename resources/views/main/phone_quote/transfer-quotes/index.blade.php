@extends('layouts.innerpages')
@section('template_title')
    Transfer Quotes
@endsection
@section('content')
    <style>
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

        /* Go from zero to full opacity */
        @keyframes fadeEffect {
            from {opacity: 0;}
            to {opacity: 1;}
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
                <!--<div class="page-leftheader">-->
                <!--    {{--<h4 class="page-title mb-0">Add Employee</h4>--}}-->
                <!--    <ol class="breadcrumb">-->
                <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Home</a>-->
                <!--        </li>-->
                <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Add Employee</a></li>-->
                <!--    </ol>-->
                <!--</div>-->
                <!--{{--<div class="page-rightheader">--}}-->
                <!--    {{--<div class="btn btn-list">--}}-->
                <!--        {{--<a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a>--}}-->
                <!--        {{--<a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a>--}}-->
                <!--        {{--<a href="#" class="btn btn-warning"><i class="fe fe-shopping-cart mr-1"></i> Buy Now </a>--}}-->
                <!--    {{--</div>--}}-->
                <!--{{--</div>--}}-->
                <div class="text-secondary text-center text-uppercase w-100">
                    <h1 class="my-4"><b>Transfer Quotes</b></h1>
                </div>
                
            </div>
            <div class="card mt-5">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive">
                            <!-- Tab links -->
                            <div class="tab">
                                <button class="tablinks" onclick="openCity(event, 'Order_Taker')" id="defaultOpen">Order Taker ({{ count($order_taker) }})</button>
                                <button class="tablinks" onclick="openCity(event, 'Dispatcher')">Dispatcher ({{ count($dispatcher) }})</button>
                            </div>

                            <div id="Order_Taker" class="tabcontent">
                                <table id="example4" class="table table-bordered table-striped key-buttons" style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">NAME</th>
                                        <th class="border-bottom-0">ROLE</th>
                                        <th class="border-bottom-0">STATUS</th>
                                        <th class="border-bottom-0">EDIT</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $order_taker as $val)
                                        <tr>
                                            <td class="text-capitalize">
                                                {{$val->name}}
                                                @if($val->slug)
                                                <br>
                                                ({{$val->slug}})
                                                @endif
                                            </td>
                                            <td>{{ get_role($val->role,'name')}}</td>
                                            <td>
                                                <span class="badge badge-{{$val->is_login == "1" ? 'success' : 'danger'}} text-light">{{$val->is_login == "1" ? 'Logged In' : 'Not Logged In'}}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#transferQuotes" onclick="transferQuotesFun({{$val->id}},0)">Transfer Custom Quotes <span style="position: absolute;top: -20px;left: -15px;" class="bg-warning rounded-circle px-2 {{$val->order_count > 99 ? 'py-2' : ($val->order_count > 9 ? 'py-1' : '')}}">{{$val->order_count > 99 ? '99+' : $val->order_count}}</span></button>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#transferSingleQuotes" onclick="transferQuotesFun({{$val->id}},1)">Transfer Single Quote</button>
                                                    <button type="button" class="btn w-100 btn-success" data-toggle="modal" data-target="#transferQuotesShow" onclick="transferQuotesFun({{$val->id}},2)">Revert Quotes <span style="position: absolute;top: -20px;right: -15px;" class="bg-warning rounded-circle px-2 {{$val->transfer_count > 99 ? 'py-2' : ($val->transfer_count > 9 ? 'py-1' : '')}}">{{$val->transfer_count > 99 ? '99+' : $val->transfer_count}}</span></button>
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="Dispatcher" class="tabcontent">
                                <table id="example5" class="table table-bordered table-striped key-buttons"  style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">NAME</th>
                                        <th class="border-bottom-0">ROLE</th>
                                        <th class="border-bottom-0">STATUS</th>
                                        <th class="border-bottom-0">EDIT</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $dispatcher as $val)
                                        <tr>
                                            <td class="text-capitalize">
                                                {{$val->name}}
                                                @if($val->slug)
                                                <br>
                                                ({{$val->slug}})
                                                @endif
                                            </td>
                                            <td>{{ get_role($val->role,'name')}}</td>
                                            <td>
                                                <span class="badge badge-{{$val->is_login == "1" ? 'success' : 'danger'}} text-light">{{$val->is_login == "1" ? 'Logged In' : 'Not Logged In'}}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#transferQuotes" onclick="transferQuotesFun({{$val->id}},0)">Transfer Quotes <span style="position: absolute;top: -20px;left: -15px;" class="bg-warning rounded-circle px-2 {{$val->order_count > 99 ? 'py-2' : ($val->order_count > 9 ? 'py-1' : '')}}">{{$val->order_count > 99 ? '99+' : $val->order_count}}</span></button>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#transferSingleQuotes" onclick="transferQuotesFun({{$val->id}},1)">Transfer Single Quote</button>
                                                    <button type="button" class="btn w-100 btn-success" data-toggle="modal" data-target="#transferQuotesShow" onclick="transferQuotesFun({{$val->id}},2)">Revert Quotes <span style="position: absolute;top: -20px;right: -15px;" class="bg-warning rounded-circle px-2 {{$val->transfer_count > 99 ? 'py-2' : ($val->transfer_count > 9 ? 'py-1' : '')}}">{{$val->transfer_count > 99 ? '99+' : $val->transfer_count}}</span></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!--/div-->
        </div>
    </div>
    <!-- /Row -->
    <div class="modal" id="transferSingleQuotes">
        <div class="modal-dialog modal-dialog-centered text-center" role="document" style="max-width: 30%;" >
            <div class="modal-content tx-size-sm" >
                <div class="modal-header">
                    <h5 class="modal-title" id="transferSingleQuotesLabel">Transfer Quotes To Other User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/transfer-quotes/single') }}" method="POST">
                        @csrf
                        <input type="hidden" id="id1" name="id" />
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label class="form-label" for="id">Order ID# <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="order_id1" name="order_id">
                            </div>
                            <div class="col-sm-12 form-group">
                                <label class="form-label" for="user_id">Transfer To <span class="text-danger">*</span></label>
                                <select class="form-control" id="user_id1" name="user_id"></select>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary submit float-right">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal" id="transferQuotes">
        <div class="modal-dialog modal-dialog-centered text-center" role="document" style="max-width: 30%;" >
            <div class="modal-content tx-size-sm" >
                <div class="modal-header">
                    <h5 class="modal-title" id="transferQuotesLabel">Transfer Quotes To Other User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/transfer-quotes/update') }}" method="POST">
                        @csrf
                        <input type="hidden" id="id0" name="id" />
                        <div class="row">
                            <div class="col-sm-12">
                                <h3>Range For Quotes</h3>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label class="form-label" for="from_date">From Date <span class="text-secondary">(Optional)</span></label>
                                        <input type="date" class="form-control" id="from_date0" name="from_date">
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="form-label" for="to_date">To Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="to_date0" name="to_date" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 form-group">
                                <label class="form-label" for="user_id">Transfer To <span class="text-danger">*</span></label>
                                <select class="form-control" id="user_id0" name="user_id"></select>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary submit float-right">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal" id="transferQuotesShow">
        <div class="modal-dialog modal-dialog-centered text-center" role="document" style="max-width: 30%;" >
            <div class="modal-content tx-size-sm" >
                <div class="modal-header">
                    <h5 class="modal-title" id="transferQuotesShowLabel">Revert Quotes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('/search-for-revert') }}" method="GET">
                        @csrf
                        <input type="hidden" id="id2" name="id" />
                        <div class="row">
                            <div class="col-sm-12">
                                <h3>Range For Quotes</h3>
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label class="form-label" for="from_date2">From Date <span class="text-secondary">(Optional)</span></label>
                                        <input type="date" class="form-control" id="from_date2" name="from_date">
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label class="form-label" for="to_date2">To Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="to_date2" name="to_date" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 form-group">
                                <label class="form-label" for="user_id">Revert To <span class="text-danger">*</span></label>
                                <select class="form-control" id="user_id2" name="user_id"></select>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary submit float-right">Find</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="transferRevert">
    </div>
@endsection

@section('extraScript')
    <script>
        $(document).ready(function() {
            $('#example6').DataTable();
            $('#example5').DataTable();
            $('#example4').DataTable();
            $('#example3').DataTable();
        });
        document.getElementById("defaultOpen").click();
        function openCity(evt, cityName) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }
        
        function count(num,status)
        {
            var count = '';
            if(status == 2)
            {
                count = `(${num})`;
            }
            
            return count;
        }
        
        function transferQuotesFun(id,status)
        {
            $("#id"+status).val(id);
            $("#user_id"+status).children().remove();
            
            var to_date = $("#to_date"+status);
            var user_id = $("#user_id"+status);
            
            user_id.siblings('.alert').remove();
            to_date.siblings('.alert').remove();
            
            $.ajax({
                url:"{{ url('/search-ot-dis') }}",
                type:"POST",
                dataype:"JSON",
                data:{id:id},
                success:function(res)
                {
                    $("#user_id"+status).append(`<option selected disabled value="">Select ${res.role.name}</option>`);
                    $.each(res.data,function(){
                        $("#user_id"+status).append(`
                            <option value="${this.id}" class="text-capitalize">${this.slug ?? this.name+' '+this.last_name} ${count(this.revert_count,status)}</option>
                        `);
                    })
                }
            })
        }
        
        $("#to_date2").change(function(){
           var to_date = $(this);
           var from_date = $('#from_date2');
           var id = $('#id2');
            
            $("#user_id2").children().remove();
            $.ajax({
                url:"{{ url('/search-ot-dis2') }}",
                type:"POST",
                dataype:"JSON",
                data:{id:id.val(),from_date:from_date.val(),to_date:to_date.val()},
                success:function(res)
                {
                    $("#user_id2").append(`<option selected disabled value="">Select ${res.role.name}</option>`);
                    $.each(res.data,function(){
                        $("#user_id2").append(`
                            <option value="${this.id}" class="text-capitalize">${this.slug ?? this.name+' '+this.last_name} ${count(this.revert_count,2)}</option>
                        `);
                    })
                }
            })
           
           
        });
        
        $(".submit").click(function (e){
            var from_date = $(this).parent('.col-sm-12').siblings('.col-sm-12').children(".row").children('.form-group').children('input[name="from_date"]');
            var to_date = $(this).parent('.col-sm-12').siblings('.col-sm-12').children(".row").children('.form-group').children('input[name="to_date"]');
            var user_id = $(this).parent('.col-sm-12').siblings('.form-group').children('select[name="user_id"]');
            var order_id = $(this).parent('.col-sm-12').siblings('.form-group').children('input[name="order_id"]');
            var id = $(this).parent('.col-sm-12').parent('.row').siblings('input[name="id"]');
            
            user_id.siblings('.alert').remove();
            to_date.siblings('.alert').remove();
            order_id.siblings('.alert').remove();
            
            if(to_date)
            {
                if(to_date.val() == '')
                {
                    e.preventDefault();
                    to_date.parent(".form-group").append(`
                        <br>
                        <div class="alert bg-danger text-light">This field is required!</div>
                    `);
                }
            }
            if(order_id)
            {
                if(order_id.val() == '')
                {
                    e.preventDefault();
                    order_id.parent(".form-group").append(`
                        <br>
                        <div class="alert bg-danger text-light">This field is required!</div>
                    `);
                }
            }
            if(user_id.children('option:selected').val() == '')
            {
                e.preventDefault();
                user_id.parent(".form-group").append(`
                    <br>
                    <div class="alert bg-danger text-light">This field is required!</div>
                `);
            }
        })
    </script>
@endsection

