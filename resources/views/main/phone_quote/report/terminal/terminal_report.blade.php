@extends('layouts.innerpages')

@section('template_title')
    Terminal Report
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
        <!--    <h4 class="page-title mb-0">OTERMINAL -- DTERMINAL REPORT</h4>-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Tables</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Terminal Report</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>OTERMINAL -- DTERMINAL REPORT</b></h1>
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
                    <?php
                        $userData = \App\AutoOrder::with('orderBooker')
                            ->has('orderBooker')
                            ->select('u_id')
                            ->get();
                        
                        $userNames = [];
                    ?>
                    <div class="row w-100">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">SELECT BOOKER</label>
                                <select class="form-control this_save  select2" name="booker" id="booker"
                                        required>
                                    <optgroup label="Categories">
                                        <option selected="" value="">--Select--</option>
                                        @foreach ($userData as $user)
                                            <?php
                                            $userName = $user->orderBooker->slug; // Assuming 'name' is the name column in the 'orderBooker' relation
                                            $userNames[$user->u_id] = $userName;
                                            ?>
                                        @endforeach
                                        @foreach ($userNames as $userId => $userName)
                                            <option value="{{ $userId }}">{{ $userName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">SELECT DATE FROM</label>
                                <input class="form-control" type="date" value="" name="fromdate" id="fromdate"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">SELECT DATE TO</label>
                                <input class="form-control" type="date" value="" name="todate" id="todate"/>
                            </div>
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                                <label class="form-label">SELECT OTERMINAL</label>

                                <select class="form-control this_save  select2" name="oterminal" id="oterminal"
                                        required>
                                    <optgroup label="Categories">
                                        <option data-select2-id="5" selected="" disabled="">--Select--</option>
                                        <option value="1">Residence</option>
                                        <option value="2">COPART Auction</option>
                                        <option value="3">Manheim Auction</option>
                                        <option value="4">IAAI Auction</option>
                                        <option value="5">Body Shop</option>
                                        <option value="10">Dealership</option>
                                        <option value="7">Business Location</option>
                                        <option value="8">Auction (Heavy)</option>
                                        <option value="6">Other</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 com-sm-3">
                            <div class="form-group">
                                <label class="form-label">SELECT DTERMINAL</label>
                                <select class="form-control this_save select2" name="dterminal" id="dterminal"
                                        required>
                                    <option value="">Select</option>
                                    <option value="1">Residence</option>
                                    <option value="2">COPART Auction</option>
                                    <option value="3">Manheim Auction</option>
                                    <option value="4">IAAI Auction</option>
                                    <option value="5">Body Shop</option>
                                    <option value="11">Dealership</option>
                                    <option value="7">Port</option>
                                    <option value="6">AirPort</option>
                                    <option value="9">Business Location</option>
                                    <option value="10">Auction (Heavy)</option>
                                    <option value="8">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="card-header">
                    <div class="col-sm-12 col-md-12">
                        <div class="form-group">
                            <button id="sv_btn" style=" float: right; " type="button" onclick="fetch_data()" class="btn  btn-primary"> DISPLAY</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div id="table_data">
                        @include('main.phone_quote.report.terminal.terminal_report_load')
                    </div>
                </div>
            </div>

        </div>

    </div><!-- end app-content-->


@endsection

@section('extraScript')

    <script>
        $(document).ready(function () {

            $(document).on('click', '.pagination a', function (event) {
                alert("1");
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data3(page);
                $.cookie("page", page, { expires: 1 });

            });

            function fetch_data3(page) {
                alert("2");
                var fromdate = $('#fromdate').val();
                var booker = $('#booker').val();
                var todate = $('#todate').val();
                var oterminal= $('#oterminal').val();
                var dterminal= $('#dterminal').val();
                $('#table_data').html('');
                $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

                $.ajax({

                    url: "/fetch_terminal_data2?page=" + page + "&fromdate=" + fromdate + "&todate=" + todate + "&oterminal=" + oterminal + "&dterminal=" + dterminal + "&booker=" + booker,
                    success: function (data) {
                        $('#table_data').html('');
                        $('#table_data').html(data);

                    },
                    complete: function (data) {
                        $('#ldss').hide();
                        regain();
                    }

                })

            }
            let cookie = $.cookie("page");
            if(cookie)
            {
                fetch_data3(cookie);
                $.removeCookie("page");
            }

        });


        function fetch_data() {
             
            var fromdate = $('#fromdate').val();
            var booker = $('#booker').val();
            var todate = $('#todate').val();
            var oterminal= $('#oterminal').val();
            var dterminal= $('#dterminal').val();
            $('#table_data').html('');
            $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

            $.ajax({
                url: "/fetch_terminal_data",
                data: {fromdate: fromdate,todate: todate,oterminal :oterminal,dterminal: dterminal,booker: booker},
                success: function (data) {
                    $('#table_data').html('');
                    $('#table_data').html(data);

                },
                complete: function (data) {
                    $('#ldss').hide();
                    regain();
                }

            })

        }
    </script>

    <!--Scrolling Modal-->

@endsection


