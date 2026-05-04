@extends('layouts.innerpages')

@section('template_title')
    DAY COUNT
@endsection

@section('content')


    <!--/app header-->                                                <!--Page header-->
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <h4 class="page-title mb-0">DAY COUNT</h4>-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Tables</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">DAY COUNT</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <!--<div class="page-rightheader">-->
        <!--    <div class="btn btn-list">-->


        <!--    </div>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Day Count</b></h1>
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
        <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="col-sm-3 col-md-3">
                        <div class="form-group mb-0">
                            <label class="form-label">CHANGE STATUS</label>
                            <select type="date" name="pstatus" id='pstatus'
                                    class="form-control select2">
                                <option value="">All</option>
                                <option value="0">NEW</option>
                                <option value="1">Interested</option>
                                <option value="2">FollowMore</option>
                                <option value="3">AskingLow</option>
                                <option value="4">NotInterested</option>
                                <option value="5">NoResponse</option>
                                <option value="6">TimeQuote</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4 my-auto">
                        <label class="form-label">Expected Date <button type="button" class="btn btn-info btn-sm" onclick="$('#date_range').val('')" style="padding: 3.2px 10px;">Clear</button></label>
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
                    <div class="col-sm-3 col-md-3">
                        <div class="form-group mb-0">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" id="searchAny" placeholder="Search" />
                        </div>
                    </div>
                    <div class="col-sm-2 col-md-2">
                        <div class="form-group mb-0">
                            <label class="form-label">Entities</label>
                            <select name="entity" id='entity'
                                    class="form-control">
                                <option value="10">Entities</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="250">250</option>
                                <option value="500">500</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div id="table_data">
                        @include('main.phone_quote.day_count.load')
                    </div>
                </div>
            </div>

        </div>

    </div><!-- end app-content-->


@endsection

@section('extraScript')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


    <script>
    

        //     $(document).on('click', '.pagination a', function (event) {

        //         event.preventDefault();
        //         var page = $(this).attr('href').split('page=')[1];
        //         fetch_data3(page);
        //         $.cookie("page", page, { expires: 1 });

        //     });

        //     function fetch_data3(page) {
        //         var pstatus = $('#pstatus').val();
        //         $('#table_data').html('');
        //         $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

        //         $.ajax({

        //             url: "fetch_day?page=" + page+"&pstatus="+pstatus,
        //             success: function (data) {
        //                 $('#table_data').html('');
        //                 $('#table_data').html(data);

        //             },
        //             complete: function (data) {
        //                 $('#ldss').hide();
        //                 regain();
        //             }

        //         })

        //     }
        //     let cookie = $.cookie("page");
        //     if(cookie)
        //     {
        //         fetch_data3(cookie);
        //         $.removeCookie("page");
        //     }

        // });


        // function fetch_day2() {

        //     var pstatus = $('#pstatus').val();
        //     $('#table_data').html('');
        //     $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

        //     $.ajax({
        //         url: "fetch_day2",
        //         data:{pstatus:pstatus},
        //         success: function (data) {
        //             $('#table_data').html('');
        //             $('#table_data').html(data);

        //         },
        //         complete: function (data) {
        //             $('#ldss').hide();
        //             regain();
        //         }

        //     })

        // }
        
        var date = new Date();
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
            $('#date_range').val("");
        });
        $('#date_range').val("");

        $("body").delegate(".cancelBtn", "click", function(){
            $('#date_range').val('');
        });
        
        function searchDayCount(page)
        {
            var status = $("#pstatus").children("option:selected").val();
            var searchAny = $("#searchAny").val();
            var entity = $("#entity").children("option:selected").val();
            var date_range = $("#date_range").val();
            
            $.ajax({
                url:"{{ url('fetch_day2') }}?page="+page,
                type:"GET",
                data:{status:status,searchAny:searchAny,entity:entity,date_range:date_range},
                beforeSend:function()
                {
                    $('#table_data').html('');
                    $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);
                },
                success:function(data)
                {
                    $('#table_data').html('');
                    $('#table_data').html(data);
                }
            });
        }
        
        $("#pstatus").change(function(){
            searchDayCount(1);
        })
        
        $("#date_range").change(function(){
            searchDayCount(1);
        })
        
        $("#entity").change(function(){
            searchDayCount(1);
        })
        
        $("#searchAny").keyup(function(e){
            searchDayCount(1);
        })
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            searchDayCount(page);
        });
    </script>

    <!--Scrolling Modal-->

@endsection


