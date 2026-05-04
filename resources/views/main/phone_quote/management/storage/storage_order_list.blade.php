@extends('layouts.innerpages')

@section('template_title')
Storage Order List
@endsection

@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <style>
        select.custom-select.custom-select-sm.form-control.form-control-sm {
            height: 29px;
        }
        .page-header {
    margin: 0 !important;
    justify-content: center;
}
    </style>

        <!--/app header-->                                                <!--Page header-->
<div class="page-header">
    <div class="text-secondary text-center text-uppercase">
        <h1 class="my-4"><b>Storage Order List</b></h1>
    </div>
    <div class="page-rightheader">
        <div class="btn btn-list">
        </div>
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

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="form-label">Status</label>
                            <select class="form-control" id="search">
                                <option value="">All</option>
                                <option value="11" selected>Pending</option>
                                <option value="12">Complete</option>
                            </select>
                            <!--<input id="search" type="text" placeholder="Global Search" class="form-control" />-->
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Daterange <button type="button" class="btn btn-info btn-sm cancelBtn" style="padding: 3.2px 10px;">Clear</button></label>
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
                    </div>
                    <br>
                    <div id="table_data">

                        @include('main.phone_quote.management.storage.load2')
                    </div>
                </div>
            </div>

    </div>

</div><!-- end app-content-->
<div class="modal fade" id="anotherCarrier" tabindex="-1" role="dialog" aria-labelledby="anotherCarrierTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="anotherCarrierLongTitle">Pickup By Another Carrier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{url('/update_pickup_carreir')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" id="order_id" name="order_id" />
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Old Carrier</label>
                                    <select id="carrier_id" class="form-control" name="carrier_id" style=" height: auto; ">
                                        <option value="">Please Add Carrier</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Pickup By Another Carrier</label>
                                    <select name="change_carrier" id="change_carrier" class="form-control">
                                        <option value="yes">Yes</option>
                                        <option value="no" selected>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12" style="display:none;" id="carrier">
                                <div class="form-group">
                                    <label class="form-label">Select Pickup Another Carrier
                                        <a href="/carrier_add/"
                                           type="button" target="_blank"
                                           class="badge badge-primary" id="updatePickupCarrier">UPDATE CARRIER</a>
                
                                    </label>
                                    <select id="pickup_carrier_id" class="form-control" name="pickup_carrier_id" style=" height: auto; ">
                                        <option value="">Please Add Carrier</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>





@endsection

@section('extraScript')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
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
                "opens": "center",
                "drops": "auto"
            }, function (start, end, label) {
                $('#date_range').val("");
            });
            $('#date_range').val("");
        });
    </script>
    <script>
        $(document).ready(function () {

            $(document).on('click', '.pagination a', function (event) {

                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                var search = $("#search").children("option:selected").val();
                var date_range = $('#date_range').val();
                fetch_data3(page,search,date_range);

            });

            function fetch_data3(page,search,date_range) {

                $('#table_data').html('');
                $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

                $.ajax({

                    url: "storage_order_list?page=" + page,
                    type:"GET",
                    data:{search:search,date_range:date_range},
                    dataType:"html",
                    success: function (data) {
                        $('#table_data').html('');
                        $('#table_data').html(data);

                    },
                    complete: function (data) {
                        $('#ldss').hide();
                    }

                })

            }
            
            $("#search").on('change',function(e){
                var date_range = $('#date_range').val();
                fetch_data3(1,$(this).val(),date_range);
            })
            
            $("#date_range").on('change',function(e){
                var search = $("#search").children("option:selected").val();
                fetch_data3(1,search,$(this).val());
            })

            $("body").delegate(".cancelBtn", "click", function(){
                $('#date_range').val('');
                var search = $("#search").children("option:selected").val();
                fetch_data3(1,search,'');
            });
            
            $("#change_carrier").change(function(){
                ($(this).val() == 'yes' ? $("#carrier").show() : $("#carrier").hide());
            })
        });
    </script>


    <!--Scrolling Modal-->

@endsection


