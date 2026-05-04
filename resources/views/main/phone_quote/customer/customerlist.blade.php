@extends('layouts.innerpages')

@section('template_title')
    Customer List
@endsection

@section('content')


    <style>
        select.custom-select.custom-select-sm.form-control.form-control-sm {
            height: 29px;
        }
    </style>
    
    <!--/app header-->                                                <!--Page header-->
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <h4 class="page-title mb-0">Customer List</h4>-->
        <!--    <input type="hidden" value="{{trim("$_SERVER[REQUEST_URI]",'/')}}" id="titlee">-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Tables</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Customers</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Customer List</b></h1>
        </div>

    </div>
    <!--End Page header-->
    <!-- Row -->
    <form action="{{url('/customer_list_2')}}" method="post">
        @csrf
        Enter Password:
        <input type="password" name="pass" style=" width: 13rem; display: inline-block;height: 2.4rem;top: 2px;position: relative;" class="form-control" />
        <button type="submit" class="btn btn-primary">Go</button> 
    </form>
    <div class="row">
        <div class="col-12">

        @if(session('flash_message'))
            <div class="alert alert-success">
                {{session('flash_message')}}
            </div>
        @endif
        @if(Session::has('error'))
            <div class="alert alert-danger">
                {{Session::get('error')}}
            </div>
        @endif
        <!--div-->
            <div class="card">
                <div class="card-body">
                    @if($hide == 'no')
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <select id="entity" class="form-control">
                                    <option value="25" @if($data->lastItem() == 25) selected @endif>25</option>
                                    <option value="50"  @if($data->lastItem() == 50) selected @endif>50</option>
                                    <option value="100" @if($data->lastItem() == 100) selected @endif>100</option>
                                    <option value="250" @if($data->lastItem() == 250) selected @endif>250</option>
                                    <option value="500" @if($data->lastItem() == 500) selected @endif>500</option>
                                </select>
                                <select id="sort" class="form-control ml-2">
                                    <option value="ASC" selected>A - Z</option>
                                    <option value="DESC">Z - A</option>
                                </select>
                            </div>
                            <div class="d-flex">
                                <input class="form-control" id="from" type="date"   >
                            </div>
                            <div class="d-flex">
                                <input class="form-control" id="too" type="date"   >
                            </div>
                            <div class="d-flex">
                                <select class="form-control h-70"
                                        name="panel_type2" id="panel_type2">
                                    <option selected="selected" value="">All Panels</option>
                                    <optgroup label="Select Panel Type">
                                        <option value="1">Phone Quote</option>


                                        <option value="2">Website Quote</option>


                                        <option value="3">Testing Quote</option>


                                        <option value="4">Panel Type 4 Quote</option>


                                        <option value="5">Panel Type 5 Quote</option>


                                        <option value="6">Panel Type 6 Quote</option>

                                    </optgroup>
                                </select>
                            </div>

                            <div class="d-flex">
                                <select id="search_by" class="form-control ml-2" style="width:50%;">
                                    <option value="oname" selected>Customer Name</option>
                                    <option value="ophone">Customer Phone</option>
                                    <option value="oemail">Customer Email</option>
                                    <option value="destinationzsc">Delivery</option>
                                </select>
                                <input class="form-control ml-2" id="value" type="text" placeholder="Search" style="width:50%;" value="{{$value}}" >
                            </div>
                            <div class="d-flex">
                                <button type="button" class="btn btn-info btn-sm" onclick="Search()">Search</button>
                            </div>
                        </div>
                        <br>
                    @endif
                    <div class="d-flex">
                        @if($hide == 'no')
                            <ul>
                                @foreach (range('a', 'z') as $column)
                                    <li class="mb-3 mr-2 atoz" style="cursor:pointer;border-radius: 50%;width: 20px;text-align: center;">{{$column}}</li>
                                @endforeach
                            </ul>
                        @endif
                        <div id="customerData">
                            @include('main.phone_quote.customer.load')
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div><!-- end app-content-->

@endsection

@section('modal')
    <div class="modal fade" id="historyUpdate" tabindex="-1" role="dialog" aria-labelledby="historyUpdateTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyUpdateLongTitle">HISTORY/STATUS</h5>
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
                                        <li class=""><a href="#tab1" class="active" data-toggle="tab">HISTORY/STATUS</a>
                                        </li>
                                        <li><a href="#tab2" data-toggle="tab">VIEW HISTORY</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body">
                                <div class="tab-content">
                                    <div class="tab-pane active " id="tab1">
                                        <form method="post" action="#">
                                            <div class="row">
                                                <input type="hidden" class="form-control" name="id"
                                                       id='oId' placeholder="" readonly>
                                                <div class="col-sm-6 col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">HISTORY</label>
                                                        <textarea required name="history"
                                                                  id='history'
                                                                  class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary" id="udpateHistoryOrderCustomer">Save changes</button>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="tab2">
                                        <div class="chat-body-style ChatBody" id="calhistory"
                                             style="overflow:scroll; height:300px;">
                                            <div class="message-feed media">
                                                <div class="media-body">
                                                    
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
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="modalClick()">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
               headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
            });

            $("#value").keyup(function(e){
                var entity = $("#entity").children('option:selected').val();
                var sort = $("#sort").children('option:selected').val();
                var search_by = $("#search_by").children('option:selected').val();
                var value  = $(this).val();
                if(e.which ==13)
                {
                    $(".atoz").css('background','transparent');
                    $(".atoz").each(function(){
                        if(value == $(this).text() || value == $(this).text().toUpperCase())
                        {
                            $(this).css('background','#d9d8d8');
                        }
                    })
                    var from = $("#from").val();
                    var too = $("#too").val();
                    fetch_data3(entity,value,sort,search_by,1,from,too);
                }
            });
            

            
            $(document).on('click','.atoz',function(){
                $("#value").val($(this).text());
                var value = $(this).text();
                var sort = $("#sort").children('option:selected').val();
                var entity = $("#entity").children('option:selected').val();
                var search_by = $("#search_by").children('option:selected').val();
                var from = $("#from").val();
                var too = $("#too").val();
                fetch_data3(entity,value,sort,search_by,1,from,too);
                $(".atoz").css('background','transparent');
                $(this).css('background','#d9d8d8');
            })

            $(document).on('click', '.pagination a', function (event) {

                event.preventDefault();
                var value  = $("#value").val();
                var entity = $("#entity").children('option:selected').val();
                var sort = $("#sort").children('option:selected').val();
                var search_by = $("#search_by").children('option:selected').val();
                var page = $(this).attr('href').split('page=')[1];
                var from = $("#from").val();
                var too = $("#too").val();
                fetch_data3(entity,value,sort,search_by,page,from,too);

            });
            
            function historyUpdateKaro(id)
            {
                $("#oId").val(id);
                $('.media-body').html('');
                $.ajax({
                    url:"{{url('/show-customer-order-history')}}",
                    type:"GET",
                    data:{id:id},
                    success:function(res){
                        $('.media-body').html('');
                        $('.media-body').html(res);
                    }
                })
            }
            
            function modalClick()
            {
                $("#history").val('');   
            }
            
            $("#udpateHistoryOrderCustomer").click(function(){
               var id = $("#oId").val();
               var history = $("#history");
               history.parents('.form-group').children('.alert').remove();
               $.ajax({
                   url:"{{url('/customer-order-history-update')}}",
                   type:"POST",
                   dataType:"json",
                   data:{id:id,history:history.val()},
                   success:function(res)
                   {
                       if(res.status_code === 200)
                       {
                           location.reload();
                       }
                       if(res.error.history)
                       {
                            history.parents('.form-group').append(`
                                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                    ${res.error.history[0]}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>`
                            );
                       }
                   }
               });
            });

        });

        function fetch_data3(entity,value,sort,search_by,page,from,too,panel_type2) {

            $('#customerData').html('');
            $('#customerData').append(`<div class="lds-hourglass" style="position:absolute;top:20%;left:50%;transform:translate(-50%,-50%);" id='ldss'></div>`);

            $.ajax({
                url: "customer_data?page=" + page,
                type:"POST",
                data:{value:value,entity:entity,sort:sort,search_by:search_by,from:from,too:too,panel_type2:panel_type2},
                success: function (data) {
                    $('#customerData').html('');
                    $('#customerData').html(data);

                },
                // complete: function (data) {
                //     $('#ldss').hide();
                //     regain();
                // }

            })

        }

        function call(num)
        {
             var num1 = atob(num);
             var newNum = num1.replace(/[- )(]/g,'');
             window.location.href = 'rcmobile://call?number='+newNum;
        }

        function Search() {
            var value  = $("#value").val();
            var entity = $("#entity").children('option:selected').val();
            var sort = $("#sort").children('option:selected').val();
            var search_by = $("#search_by").children('option:selected').val();
            var panel_type2 = $("#panel_type2").val();
            var from = $("#from").val();
            var too = $("#too").val();
            fetch_data3(entity,value,sort,search_by,1,from,too,panel_type2);
        }
    </script>


    <!--Scrolling Modal-->

@endsection


