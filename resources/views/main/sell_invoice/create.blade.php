@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim('Add Sell Invoice','/'))}}
@endsection
@section('content')
    @include('partials.mainsite_pages.return_function')
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
            text-align: center;
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
    </style>
    <!--/app header-->                                                <!--Page header-->
    <div class="page-header">
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Add Sell Invoice</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-12">
        <!--div-->
            <div class="card">
                <div class="card-header">
                    Add New Sell Invoice
                </div>
                <form action="{{ url('/sell_invoice/store') }}" method="POST">
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label" for="inventory_id">Inventory Id <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required placeholder="Enter Inventory Id..." name="inventory_id" id="inventory_id" />
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label" for="date">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" required placeholder="Enter Date..." name="date" id="date" />
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label" for="sale_person">Sale Person <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required placeholder="Enter Sale Person..." name="sale_person" id="sale_person" />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h3 class="text-center">Bill/Sold To</h3>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label" for="cname">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required placeholder="Enter Name..." name="cname" id="cname" />
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label" for="cemail">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" required placeholder="Enter Email..." name="cemail" id="cemail" />
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label" for="cphone">Phone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required placeholder="Enter Phone..." name="cphone" id="cphone" />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h3 class="text-center">Vehicle Detail</h3>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label" for="vin_number">Vin Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required placeholder="Enter Vin Number..." name="vin_number" id="vin_number" onkeyup="get_vin()" />
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label" for="year">Year <span class="text-danger">*</span></label>
                                    <select name="year" class="form-control" required id="year">
                                        <option value="" selected disabled>Select Year</option>
                                        @for($i=1901; $i <= date('Y'); $i++)
                                            <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label" for="make">Make <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required placeholder="Enter Make..." name="make" id="make" onkeyup='getmake()' />
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label" for="model">Model <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required placeholder="Enter Model..." name="model" id="model" onkeyup='getmodel()' />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h3 class="text-center">Prices</h3>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label" for="sale_price">Sale Price <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" required placeholder="Enter Sale Price..." name="sale_price" id="sale_price" />
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label" for="total_amount">Total Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" required placeholder="Enter Total Amount..." name="total_amount" id="total_amount" />
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label" for="balance">Balance Due at Delivery <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" required placeholder="Enter Balance Due at Delivery..." name="balance" id="balance" />
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label" for="additional">Additional</label>
                                    <textarea rows="6" class="form-control" placeholder="Enter Additional..." name="additional" id="additional"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success float-right mb-4" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- end app-content-->


@endsection
@section('extraScript')
    <script>
        function getmake() {
            $("#make").autocomplete({
                source: "/getmake"
            });
        }

        function getmodel() {

            var yy = $("#year").children('option:selected').val();
            var mm = $("#make").val();


            $("#model").autocomplete({
                source: "/getmodel?year=" + yy + "&make=" + mm
            });
        }
        
        function get_vin() {
            var vinno = $(`#vin_number`).val();
            $.ajax({
                type: "GET",
                url: "/getvin",
                dataType: 'JSON',
                data: {term: vinno},
                success: function (res) {
                    if (res) {
                        var year = $("#year").children('option');
                        $.each(year,function(){
                            $(this).attr('selected',false);
                            if($(this).val() == res[2].value)
                            {
                                $(this).attr('selected',true);
                            }
                        })
                        $("#make").val(res[0].value);
                        $("#model").val(res[1].value);
                    }
                }

            });

        }
        
        $("#cphone").on('keypress',function(){
            if($(this).val() == '')
            {
                $(this).mask("(999) 999-9999");
            }
        })
    </script>
@endsection


