@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim('Show Data','/'))}}
@endsection
@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

    th , div , tr , h1 , h2 , h3 , span{
        font-family: 'Poppins', sans-serif;
    }
    .ChatViewMain .tableh th {
        border: 1px solid #ddd;
        background: #077199;
       color: #fff !important;
        font-size: 16px;
    }
     .ChatViewMain .tableh td {
           font-weight:500;
    }
    .ChatViewMain .table td dd{
        font-size: 10px;
    }
    .ChatViewMain .badge{
        font-weight: 600;
        float: left;
        margin-left: 3px;
    }
    .ChatViewMain dd{
        display: flex;
    }
    .ChatViewMain dd b{
        margin-right: 8px;
    }
    .ChatViewMain .d-flex b{
        margin-left: 6px;
    }
    .ChatViewMain .d-flex{
        justify-content: space-around;
    }
    .ChatViewMain .btn-show {
        display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-bottom: 10px;
    margin: 10px;
    margin: 10px 0;
    border-bottom: 1px solid #ddd;
    }
    .tabMain .tabMainbtn .btn{
        padding: auto;
        text-transform: inherit;
        font-size: 11px;
    }
    .ChatViewMain .box{
        text-align: center;
        text-transform: uppercase;
    }
    .ChatViewMain table td a span{
        font-size: 12px;
    }
    
    .btn-show button {
        border: 1px solid #fff;
        padding: 7px  15px;
        border-radius: 9px;
    }
     .col label {
        text-align: left;
        display: block;
    }

</style>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    @include('partials.mainsite_pages.return_function')
    
    
    @include('main.phone_quote.question.style')
    
    @include('main.phone_quote.question.head')
    
    
    @php
    $check_panel = check_panel();

    if($check_panel == 1){

    $phoneaccess=explode(',',Auth::user()->emp_access_phone);
    }
    elseif($check_panel == 3)
    {
        $phoneaccess = explode(',',Auth::user()->emp_access_test);
    }
    else{
    $phoneaccess=explode(',',Auth::user()->emp_access_web);
    }
    @endphp
    <div class="page-header" style="margin-top: 30px !important;">
        <!--<div class="page-leftheader">-->
        <!--    <input type="hidden" value="{{trim("$_SERVER[REQUEST_URI]",'/')}}" id="titlee">-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Questions</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Show Data</b></h1>
        </div>
    </div>
        <div class="tabMainbody">
            @if(\Request::segment(2) == 'searchData')
                @if($data)
                    @include('main.phone_quote.question.data')
                @endif
            @endif
        </div>
        <input type="hidden" value="{{\Request::segment(2)}}" class="route">
    </div>
    
    <div class="modal fade" id="storagehmodal" tabindex="-1" role="dialog" aria-labelledby="largemodal"
         aria-hidden="true">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content"
                 style="right: 215px;min-width: 77pc !important;height: 47pc !important;overflow: scroll;">
                <div class="modal-header">
                    <center>
                        <h5 class="modal-title" id="largemodal1">Storage Data</h5>
                    </center>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <table class="table table-responsive table-bordered table-stripe table-condensed"
                               id="table_data_storage">

                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extraScript')
@include('main.phone_quote.question.foot')
<script>
    $("#vehicle_available").on('click',function(){
        if($(this).val() == 0)
        {
            $("#sub_lot_address").show();
        }
        else
        {
            $("#sub_lot_address").hide();
        }
    })
    $("body").delegate(".storageModal", "click", function () {

        var olcation = $(this).attr('data-location1');
        var dlcation = $(this).attr('data-location2');
        $.ajax({

            url: "/get_storage_by_location",
            type: "GET",
            //dataType: "json",
            data: {olcation: olcation, dlcation: dlcation},
            beforeSend: function () {
                $('#table_data_storage').html("");
                $('#table_data_storage').append(`<div class="lds-hourglass" id='ldss'></div>`);
            },

            success: function (data) {
                //success
                $('#table_data_storage').html("");
                $('#table_data_storage').html(data);
                if (data == "") {
                    $('#table_data_storage').append(`<div ><center> <h3 style="margin-top: 15px;">No Data Found</h3> </center></div>`);
                }
                
                $(document).on('click', '#carrierPagination a', function (event) {
                    event.preventDefault();
                    var page = $(this).attr('href').split('page=')[1];
                    
                    var olcation = $('#origin').val();
                    var dlcation = $('#destination').val();
                    $.ajax({
            
                        url: "/get_storage_by_location?page="+page,
                        type: "GET",
                        //dataType: "json",
                        data: {olcation: olcation, dlcation: dlcation},
                        beforeSend: function () {
                            $('#table_data_storage').html("");
                            $('#table_data_storage').append(`<div class="lds-hourglass" id='ldss'></div>`);
                        },
            
                        success: function (data) {
                            //success
                            $('#table_data_storage').html("");
                            $('#table_data_storage').html(data);
                            if (data == "") {
                                $('#table_data_storage').append(`<div ><center> <h3 style="margin-top: 15px;">No Data Found</h3> </center></div>`);
                            }
            
                        },
            
            
                    });
                });

            },


        });

    });
</script>
@endsection