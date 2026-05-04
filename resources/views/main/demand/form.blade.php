<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demand of Vehicle | ShawnTransport</title>
    <link rel="icon" href="{{asset('assets/images/brand/favicon.ico')}}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <style>
        .container {
            display: flex;
            height: 100vh;
            justify-content: center;
        }
        .container .row{
            width:100%;
        }
        .maincontain{
            border: 3px solid #000;
            box-shadow: 4px 4px 15px rgba(4, 4, 7, 0.3);
            border-radius: 5px;
        }
        .card-header {
            background-color: #17a2b8 !important;
            border-bottom: 3px solid #000;
        }
        .card-header h2{
            color: #fff !important;
            letter-spacing: 3px;
        }
        .card-body{
            background-color: #f1f1f1 !important;
        }
        .btn-primary {
            background-color: #17a2b8 !important;
            border-color: #17a2b8 !important;
        }
        .form-group {
            margin-bottom:36px;
        }
        .form-control {
            border: 1px solid grey !important;
            font-size: 13px !important;
        }
        .card-footer {
            background-color: #f1f1f1 !important;
            border-top: 3px solid #000;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-sm-9 m-auto maincontain p-0">
                <div class="card">
                    @if(Session::has('msg'))
                        <div class="card-header">
                            <h2 class="text-center my-auto text-uppercase">{{Session::get('msg')}}</h2>
                        </div>
                    @else
                        @if(isset($data->id))
                            <?php 
                                $created = \Carbon\Carbon::parse($data->created_at)->diffForHumans();
                            ?>
                            @if($created > 0)
                                <div class="card-header d-flex justify-content-between">
                                    <h2 class="my-auto text-uppercase">Demand of Vehicle</h2>
                                    <h5 class="my-auto ml-auto text-light">Demand Id#{{ $data->id }}</h5>
                                </div>
                                <form action="{{url('/demand_order/update/'.$data->id)}}" method="POST">
                                    <div class="card-body">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label for="" class="form-label mb-0"><b>Preferred Required Year</b> <span class="text-danger">*</span></label>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <select name="from_year" id="from_year" class="form-select" required>
                                                                <option value="" selected disabled>Select From Year</option>
                                                                @for($i=1901; $i <= date('Y'); $i++)
                                                                <option value="{{$i}}">{{$i}}</option>
                                                                @endfor
                                                            </select>
                                                        
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <select name="to_year" id="to_year" class="form-select" required>
                                                                <option value="" selected disabled>Select To Year</option>
                                                                @for($i=1901; $i <= date('Y'); $i++)
                                                                <option value="{{$i}}">{{$i}}</option>
                                                                @endfor
                                                            </select>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="make"><b>Preferred Required Make</b> <span class="text-danger">*</span></label>
                                                            <input type="text" name="make" placeholder="Make" id="make" class="form-control" onkeyup='getmake()'>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="model"><b>Preferred Required Model</b> <span class="text-danger">*</span></label>
                                                            <input type="text" name="model" placeholder="Model" id="model" class="form-control" onkeyup='getmodel()'>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="trim_level"><b>Trim Level</b></label>
                                                            <input type="text" name="trim_level" placeholder="Trim Level" id="trim_level" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="mileage"><b>Mileage</b> <span class="text-danger">*</span></label>
                                                            <input type="text" name="mileage" placeholder="Mileage" id="mileage" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="car_color"><b>Preferred Car Color</b> <span class="text-danger">*</span></label>
                                                            <input type="text" name="car_color" placeholder="Preferred Car Color" id="car_color" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="interior_color"><b>Preferred Interior Color</b> <span class="text-danger">*</span></label>
                                                            <input type="text" name="interior_color" placeholder="Preferred Interior Color" id="interior_color" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="condition"><b>Preferred Condition</b> <span class="text-danger">*</span></label>
                                                            <select name="condition" id="condition" class="form-select" required>
                                                                <option value="" selected disabled>Select Condition</option>
                                                                <option value="Runner">Runner</option>
                                                                <option value="Non Runner">Non Runner</option>
                                                            </select>
                                                        
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="title"><b>Preferred Title</b> <span class="text-danger">*</span></label>
                                                            <select name="title" id="title" class="form-select" required>
                                                                <option value="" selected disabled>Select Title</option>
                                                                <option value="Clean">Clean</option>
                                                                <option value="Salvage Title">Salvage Title</option>
                                                            </select>
                                                        
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="body_condition"><b>Body Condition</b> <span class="text-danger">*</span></label>
                                                            <select name="body_condition" id="body_condition" class="form-select" required>
                                                                <option value="" selected disabled>Select Body Condition</option>
                                                                <option value="Accidental">Accidental</option>
                                                                <option value="Non Accidental">Non Accidental</option>
                                                            </select>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        <label for="" class="form-label mb-0"><b>Select Budget Range</b> <span class="text-danger">*</span></label>
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <input type="text" name="from_budget" placeholder="From Budget" id="from_budget" class="form-control" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <div class="form-group">
                                                                    <input type="text" name="to_budget" placeholder="To Budget" id="to_budget" class="form-control" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label for="how_much_days"><b>How Soon You Want?</b> <span class="text-danger">*</span></label>
                                                            <input type="text" name="how_much_days" placeholder="How Soon You Want?" id="how_much_days" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group mb-2">
                                                            <label for="requirement"><b>If Any Specific Requirement</b> <span class="text-danger">*</span></label>
                                                            <input type="text" name="requirement" placeholder="If Any Specific Requirement" id="requirement" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group mb-2">
                                                            <label for="payment_method"><b>Preferred Payment Method</b> <span class="text-danger">*</span></label>
                                                            <select name="payment_method" id="payment_method" class="form-select" required>
                                                                <option value="" selected disabled>Select Payment Method</option>
                                                                <option value="Credit/Debit Card">Credit/Debit Card</option>
                                                                <option value="Zelle">Zelle</option>
                                                                <option value="PayPal">PayPal</option>
                                                            </select>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-sm-2 mt-2 ms-auto">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary float-end w-100 py-2">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <div class="card-header">
                                    <h2 class="text-center my-auto text-uppercase">The form has been expired.</h2>
                                </div>
                            @endif
                        @else
                        <div class="card-header">
                            <h2 class="text-center my-auto text-uppercase">The Link is wrong</h2>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!--<script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        function getmake() {
            $("#make").autocomplete({
                source: "/getmake"
            });
        }

        function getmodel() {

            var yy = $("#from_year").children('option:selected').val();
            var mm = $("#make").val();


            $("#model").autocomplete({
                source: "/getmodel?year=" + yy + "&make=" + mm
            });
        }
        $(document).ready(function() {
            $("#from_budget").keydown(function(e) {
                var x = e.which || e.keycode;
                if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40) || x == 8){
                    
                }else{
                    return false;
                }
            });
            $("#to_budget").keydown(function(e) {
                var x = e.which || e.keycode;
                if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40) || x == 8){
                    
                }else{
                    return false;
                }
            });
        });
    </script>
</body>
</html>