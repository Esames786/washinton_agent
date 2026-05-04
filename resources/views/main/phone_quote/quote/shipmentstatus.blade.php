@extends('layouts.innerpages')
@include('partials.mainsite_pages.return_function')
@section('template_title')
    Shipment Status
@endsection

@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
        <style>
            .pageTable .firstsection{
                padding: 6px;
                /*background: #007fb8;*/
                background:grey;
            }
            .pageTable .firstsection select,input{
                font-size: 14px;
                height: 29px;
                background-color: #f7f7f7;
                border: 1px solid #efefef;
                border-right: none;
                border-radius: 0;
                display: flex;
                /* flex-direction: column; */
                width: 96%;
                outline: 0;
                padding: 0px 6px;
                border-radius: 4px;
            }
            .delpik{
                color: white;
                padding: 3px 12px;
                margin-right: 10px;
                border-radius: 7px; 
            }
            .green{
                background: green;
            }
            .yellow{
                background: #ffab00;
            }
            .red{
                background: red;
            }
            .blue{
                background: #007bff;
            }
            .pageTable .firstsection button{
                text-transform: uppercase;
                color: #000;
                /* padding: 0.75rem; */
                text-align: center;
                font-weight: 600;
                border: 0;
                min-width: 102px;
                height: 29px;
            }
            .customers::-webkit-scrollbar {
                width: 4px;
                background-color: transparent;
            }
            /* .customers::-webkit-scrollbar-track {
                background-color: darkgrey;
                } */
                .customers::-webkit-scrollbar-thumb {
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #a6afffd5;
                border-radius: 50px;
            }
            .table>:not(caption)>*>*{
                padding: 3px;
            }
            .late{
                /* background-color: #e70000e6 !important; */
                /* font-weight: 700;
                color: white; */
            }
            .pageTable .firstsection .sreachdiv{
                display: flex;
            }
            .pageTable .firstsection .icon{
                background: #f7f7f7;
                padding: 8px;
                border-left: 1px solid #c8c8c8;
            }
            .pageTable .secondsection .mainheading{
                /*background: #179dd9;*/
                background:lightgrey;
                padding: 2px;
                text-align: center;
                font-size: 12px;
                border-bottom: 1px solid #ffffff70;
                /*color: white;*/
                color:black;
                text-transform: uppercase;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .pageTable .secondsection .smheading .columns{
                /*background: #179dd9;*/
                background: grey;
                padding: 3px;
                border-right: 1px solid #ffffff70;
                color: white;
                text-align: center;
                font-size: 12px;
            }
            body{
                font-size: 12px;
            }
            .customers .row:nth-child(2n) {
                /*background-color: #179dd970;*/
                background-color: lightgrey;
                color: black;
                font-weight: 600;
                /*margin: 0;*/
            }
            .pageTable .secondsection .customers{
                max-height: 650px;
                overflow-y: scroll;
                overflow-x: hidden;
            }
            .customers .row{
                transition: 0.9s;
            }
            .customers .row:hover{
                background-color: #179dd970;
            }
            .pageTable .secondsection select{
                border: 1px solid #dedede;
                padding: 4px 0px;
                outline: 0;
                width: 95%;
                display: block;
                margin: auto;
                font-family: math;
                font-size: 12px;
            }
            .pageTable .secondsection .row .col-md-6{
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }
            .pageTable .secondsection .maincol{
                border: 1px solid #dadada;
                border-top: none;
            }
            .blink {
              animation: blinker 1.5s linear infinite;
            }
            
            @keyframes blinker {
              50% {
                opacity: 0;
              }
            }
        </style>
    </head>
    <body>
        <div class="page-header" style="display:unset;">
            <div class="row">
                <div class="col-sm-4">
                    <label style="float: left">Daterange <button type="button" class="btn btn-info btn-sm" id="clear" disabled style="padding: 3.2px 10px;">Clear</button></label>
                    <div class='input-group date' id='datetimepicker1'>
                        <input type='text' name="date_range"  id="date_range" class="form-control" disabled />
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
                <div class="text-secondary text-center text-uppercase col-sm-4 mt-auto">
                    <h1 class="my-4"><b>Shipment Status</b></h1>
                </div>
                <div class="col-sm-4"></div>
            </div>
        </div>
        <br />
        <div class="pageTable">

        <div class="container-fluid">
            <div class="row firstsection">
                <center class="m-auto">
                    <span class="badge badge-danger">
                        Refresh In: <span class="countdown">01:00</span>
                    </span>
                </center>
                <!--<h3 class="m-auto text-light">Shipment Status</h3>-->
            </div>
            <div class="row secondsection" id="table_data">
                
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width:85%;">
            <div class="modal-content">
                <div class="d-flex justify-content-between mt-5">
                    <h5 class="text-center my-auto ml-4" id="order_detail_status">Order Status</h5>
                    <h5 class="text-center my-auto" id="order_detail_title">Order Detail</h5>
                    <button type="button" class="close bg-danger text-white mr-4" data-dismiss="modal" aria-label="Close" style="height: 25px;width: 25px;border-radius: 50%;">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detail_order">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
            var data_get = 0;
            day_count(data_get);
            var stat = "{{Auth::user()->emp_access_ship}}";
            var statArr = stat.split(",");
            function day_count(data_get) {
                var date_range = $('#date_range').val();
                if (data_get <= 29) {
                    setTimeout(function () {
                        $.ajax({
                            url: "/shipment_status_load",
                            type: "get",
                            data: {pstatus: statArr[data_get],date_range:date_range},
                            success: function (data) {
                                data_get++;
                                $('#table_data').append(data);
                                day_count(data_get);
                                if(data_get >= 29)
                                {
                                    $("#date_range").attr('disabled',false);
                                    $("#clear").attr('disabled',false);
                                }
                                else
                                {
                                    $("#date_range").attr('disabled',true);
                                    $("#clear").attr('disabled',true);
                                }
                            }
                        });
                    }), 2000
                }
            }

            
            var timer2 = $('.countdown').text();
            setInterval(function(){
                var timer2 = $('.countdown').text();
                var timer = timer2.split(':');
                var minutes = parseInt(timer[0], 10);
                var seconds = parseInt(timer[1], 10);
                if(minutes == 0 && seconds == 0)
                {
                    $('.countdown').html("01:00");
                    $('#table_data').html('');
                    data_get = 0;
                    day_count(data_get);
                }
                else if(minutes == 0 && seconds > 0)
                {
                    seconds = seconds - 1;
                    var newsec = seconds;
                    if(seconds < 10)
                    {
                        newsec = "0"+seconds;
                    }
                    $('.countdown').html("0"+minutes + ':' + newsec);
                }
                else
                {
                    minutes = minutes - 1;
                    seconds = 59;
                    $('.countdown').html("0"+minutes + ':' + seconds);
                }
            },1200);
            
            $(function () {
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
                    "startDate": new Date(date.getFullYear(), date.getMonth(), 1),
                    "endDate": new Date(date.getFullYear(), date.getMonth() + 1, 0),
                    "opens": "center",
                    "drops": "auto"
                }, function (start, end, label) {
                    $('.countdown').html("00:00");
                    $('#table_data').html('');
                    var data_get = 0;
                    $("#date_range").attr('disabled',true);
                    $("#clear").attr('disabled',true);
                });
                $('#date_range').val('');
            });
            $("#clear").on('click',function(){
                $('#date_range').val('');
                $('.countdown').html("00:00");
                $('#table_data').html('');
                var data_get = 0;
                $("#date_range").attr('disabled',true);
                $("#clear").attr('disabled',true);
            })
        });
        
    </script>



@endsection
