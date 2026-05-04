@extends('layouts.innerpages')

@section('template_title')
    Update Storage
@endsection
@include('partials.mainsite_pages.return_function')
<style>
    .oauc {
        padding: 11px;
        width: 100%;
    }

    .dauc {
        padding: 11px;
        width: 100%;
    }
</style>

@section('content')

    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">Update Storage </h4>

            <h3 class="page-title mb-0"></h3>


            <h4 id="orderidplace"></h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Pages</a>
                </li>

                <li class="breadcrumb-item active" aria-current="page"><a href="#">Update Storage</a></li>
            </ol>
        </div>

    </div>
    <!--End Page header-->
    <!-- Row -->

    <form action="/update_storage/{{$data->id}}" id="form" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token()}}">

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"></div>
                    </div>
                    <div class="card-body">
                        <div class="card-title font-weight-bold"></div>
                        <div class="row">
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" required name="companyname" class="form-control"
                                           placeholder="Enter Company Name..." value="{{$data->company_name}}">
                                </div>
                            </div>

                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Manager/Owner Name</label>
                                    <input type="text" required name="managerownername" class="form-control"
                                           placeholder="Enter Manager/Owner Name..." value="{{$data->manager_owner_name}}">
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Company Address</label>
                                    <input type="text" required name="caddress" class="form-control"
                                           placeholder="Enter Company Address..." value="{{$data->company_address}}">
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label position-relative">Zipcode</label>
                                    <input type="text" required name="zip" class="form-control" id="zip"
                                           placeholder="Enter Zipcode..." value="{{$data->zip}}">
                                </div>
                                <ul class="nav flex-column border scrollul" style="max-height:200px;overflow:scroll;position: absolute;top: 79.9%;background: grey;width: 95%;z-index: 9;display:none;">
                                </ul>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" required>Company Open Time</label>
                                    <select required name="opentime" id="opentime" class="form-control"  placeholder="Enter Open Time...">
                                        <option value="" selected disabled>Select Company Open Time</option>
                                        <option value="12:00 AM" @if($data->open_time == '12:00 AM') selected @endif>12:00 AM</option>
                                        <option value="12:30 AM" @if($data->open_time == '12:30 AM') selected @endif>12:30 AM</option>
                                        <option value="1:00 AM" @if($data->open_time == '1:00 AM') selected @endif>1:00 AM</option>
                                        <option value="1:30 AM" @if($data->open_time == '1:30 AM') selected @endif>1:30 AM</option>
                                        <option value="2:00 AM" @if($data->open_time == '2:00 AM') selected @endif>2:00 AM</option>
                                        <option value="2:30 AM" @if($data->open_time == '2:30 AM') selected @endif>2:30 AM</option>
                                        <option value="3:00 AM" @if($data->open_time == '3:00 AM') selected @endif>3:00 AM</option>
                                        <option value="3:30 AM" @if($data->open_time == '3:30 AM') selected @endif>3:30 AM</option>
                                        <option value="4:00 AM" @if($data->open_time == '4:00 AM') selected @endif>4:00 AM</option>
                                        <option value="4:30 AM" @if($data->open_time == '4:30 AM') selected @endif>4:30 AM</option>
                                        <option value="5:00 AM" @if($data->open_time == '5:00 AM') selected @endif>5:00 AM</option>
                                        <option value="5:30 AM" @if($data->open_time == '5:30 AM') selected @endif>5:30 AM</option>
                                        <option value="6:00 AM" @if($data->open_time == '6:00 AM') selected @endif>6:00 AM</option>
                                        <option value="6:30 AM" @if($data->open_time == '6:30 AM') selected @endif>6:30 AM</option>
                                        <option value="7:00 AM" @if($data->open_time == '7:00 AM') selected @endif>7:00 AM</option>
                                        <option value="7:30 AM" @if($data->open_time == '7:30 AM') selected @endif>7:30 AM</option>
                                        <option value="8:00 AM" @if($data->open_time == '8:00 AM') selected @endif>8:00 AM</option>
                                        <option value="8:30 AM" @if($data->open_time == '8:30 AM') selected @endif>8:30 AM</option>
                                        <option value="9:00 AM" @if($data->open_time == '9:00 AM') selected @endif>9:00 AM</option>
                                        <option value="9:30 AM" @if($data->open_time == '9:30 AM') selected @endif>9:30 AM</option>
                                        <option value="10:00 AM" @if($data->open_time == '10:00 AM') selected @endif>10:00 AM</option>
                                        <option value="10:30 AM" @if($data->open_time == '10:30 AM') selected @endif>10:30 AM</option>
                                        <option value="11:00 AM" @if($data->open_time == '11:00 AM') selected @endif>11:00 AM</option>
                                        <option value="11:30 AM" @if($data->open_time == '11:30 AM') selected @endif>11:30 AM</option>
                                        <option value="12:00 PM" @if($data->open_time == '12:00 PM') selected @endif>12:00 PM</option>
                                        <option value="12:30 PM" @if($data->open_time == '12:30 PM') selected @endif>12:30 PM</option>
                                        <option value="1:00 PM" @if($data->open_time == '1:00 PM') selected @endif>1:00 PM</option>
                                        <option value="1:30 PM" @if($data->open_time == '1:30 PM') selected @endif>1:30 PM</option>
                                        <option value="2:00 PM" @if($data->open_time == '2:00 PM') selected @endif>2:00 PM</option>
                                        <option value="2:30 PM" @if($data->open_time == '2:30 PM') selected @endif>2:30 PM</option>
                                        <option value="3:00 PM" @if($data->open_time == '3:00 PM') selected @endif>3:00 PM</option>
                                        <option value="3:30 PM" @if($data->open_time == '3:30 PM') selected @endif>3:30 PM</option>
                                        <option value="4:00 PM" @if($data->open_time == '4:00 PM') selected @endif>4:00 PM</option>
                                        <option value="4:30 PM" @if($data->open_time == '4:30 PM') selected @endif>4:30 PM</option>
                                        <option value="5:00 PM" @if($data->open_time == '5:00 PM') selected @endif>5:00 PM</option>
                                        <option value="5:30 PM" @if($data->open_time == '5:30 PM') selected @endif>5:30 PM</option>
                                        <option value="6:00 PM" @if($data->open_time == '6:00 PM') selected @endif>6:00 PM</option>
                                        <option value="6:30 PM" @if($data->open_time == '6:30 PM') selected @endif>6:30 PM</option>
                                        <option value="7:00 PM" @if($data->open_time == '7:00 PM') selected @endif>7:00 PM</option>
                                        <option value="7:30 PM" @if($data->open_time == '7:30 PM') selected @endif>7:30 PM</option>
                                        <option value="8:00 PM" @if($data->open_time == '8:00 PM') selected @endif>8:00 PM</option>
                                        <option value="8:30 PM" @if($data->open_time == '8:30 PM') selected @endif>8:30 PM</option>
                                        <option value="9:00 PM" @if($data->open_time == '9:00 PM') selected @endif>9:00 PM</option>
                                        <option value="9:30 PM" @if($data->open_time == '9:30 PM') selected @endif>9:30 PM</option>
                                        <option value="10:00 PM" @if($data->open_time == '10:00 PM') selected @endif>10:00 PM</option>
                                        <option value="10:30 PM" @if($data->open_time == '10:30 PM') selected @endif>10:30 PM</option>
                                        <option value="11:00 PM" @if($data->open_time == '11:00 PM') selected @endif>11:00 PM</option>
                                        <option value="11:30 PM" @if($data->open_time == '11:30 PM') selected @endif>11:30 PM</option>
                                    </select>
                                    <!--<input type="time" required name="opentime" id="opentime" class="form-control"  placeholder="Enter Open Time..." >-->
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" required>Company Close Time</label>
                                    <select required name="closetime" id="closetime" class="form-control" placeholder="Enter Close Time...">
                                        <option value="" selected disabled>Select Company Close Time</option>
                                        <option value="12:00 AM" @if($data->close_time == '12:00 AM') selected @endif>12:00 AM</option>
                                        <option value="12:30 AM" @if($data->close_time == '12:30 AM') selected @endif>12:30 AM</option>
                                        <option value="1:00 AM" @if($data->close_time == '1:00 AM') selected @endif>1:00 AM</option>
                                        <option value="1:30 AM" @if($data->close_time == '1:30 AM') selected @endif>1:30 AM</option>
                                        <option value="2:00 AM" @if($data->close_time == '2:00 AM') selected @endif>2:00 AM</option>
                                        <option value="2:30 AM" @if($data->close_time == '2:30 AM') selected @endif>2:30 AM</option>
                                        <option value="3:00 AM" @if($data->close_time == '3:00 AM') selected @endif>3:00 AM</option>
                                        <option value="3:30 AM" @if($data->close_time == '3:30 AM') selected @endif>3:30 AM</option>
                                        <option value="4:00 AM" @if($data->close_time == '4:00 AM') selected @endif>4:00 AM</option>
                                        <option value="4:30 AM" @if($data->close_time == '4:30 AM') selected @endif>4:30 AM</option>
                                        <option value="5:00 AM" @if($data->close_time == '5:00 AM') selected @endif>5:00 AM</option>
                                        <option value="5:30 AM" @if($data->close_time == '5:30 AM') selected @endif>5:30 AM</option>
                                        <option value="6:00 AM" @if($data->close_time == '6:00 AM') selected @endif>6:00 AM</option>
                                        <option value="6:30 AM" @if($data->close_time == '6:30 AM') selected @endif>6:30 AM</option>
                                        <option value="7:00 AM" @if($data->close_time == '7:00 AM') selected @endif>7:00 AM</option>
                                        <option value="7:30 AM" @if($data->close_time == '7:30 AM') selected @endif>7:30 AM</option>
                                        <option value="8:00 AM" @if($data->close_time == '8:00 AM') selected @endif>8:00 AM</option>
                                        <option value="8:30 AM" @if($data->close_time == '8:30 AM') selected @endif>8:30 AM</option>
                                        <option value="9:00 AM" @if($data->close_time == '9:00 AM') selected @endif>9:00 AM</option>
                                        <option value="9:30 AM" @if($data->close_time == '9:30 AM') selected @endif>9:30 AM</option>
                                        <option value="10:00 AM" @if($data->close_time == '10:00 AM') selected @endif>10:00 AM</option>
                                        <option value="10:30 AM" @if($data->close_time == '10:30 AM') selected @endif>10:30 AM</option>
                                        <option value="11:00 AM" @if($data->close_time == '11:00 AM') selected @endif>11:00 AM</option>
                                        <option value="11:30 AM" @if($data->close_time == '11:30 AM') selected @endif>11:30 AM</option>
                                        <option value="12:00 PM" @if($data->close_time == '12:00 PM') selected @endif>12:00 PM</option>
                                        <option value="12:30 PM" @if($data->close_time == '12:30 PM') selected @endif>12:30 PM</option>
                                        <option value="1:00 PM" @if($data->close_time == '1:00 PM') selected @endif>1:00 PM</option>
                                        <option value="1:30 PM" @if($data->close_time == '1:30 PM') selected @endif>1:30 PM</option>
                                        <option value="2:00 PM" @if($data->close_time == '2:00 PM') selected @endif>2:00 PM</option>
                                        <option value="2:30 PM" @if($data->close_time == '2:30 PM') selected @endif>2:30 PM</option>
                                        <option value="3:00 PM" @if($data->close_time == '3:00 PM') selected @endif>3:00 PM</option>
                                        <option value="3:30 PM" @if($data->close_time == '3:30 PM') selected @endif>3:30 PM</option>
                                        <option value="4:00 PM" @if($data->close_time == '4:00 PM') selected @endif>4:00 PM</option>
                                        <option value="4:30 PM" @if($data->close_time == '4:30 PM') selected @endif>4:30 PM</option>
                                        <option value="5:00 PM" @if($data->close_time == '5:00 PM') selected @endif>5:00 PM</option>
                                        <option value="5:30 PM" @if($data->close_time == '5:30 PM') selected @endif>5:30 PM</option>
                                        <option value="6:00 PM" @if($data->close_time == '6:00 PM') selected @endif>6:00 PM</option>
                                        <option value="6:30 PM" @if($data->close_time == '6:30 PM') selected @endif>6:30 PM</option>
                                        <option value="7:00 PM" @if($data->close_time == '7:00 PM') selected @endif>7:00 PM</option>
                                        <option value="7:30 PM" @if($data->close_time == '7:30 PM') selected @endif>7:30 PM</option>
                                        <option value="8:00 PM" @if($data->close_time == '8:00 PM') selected @endif>8:00 PM</option>
                                        <option value="8:30 PM" @if($data->close_time == '8:30 PM') selected @endif>8:30 PM</option>
                                        <option value="9:00 PM" @if($data->close_time == '9:00 PM') selected @endif>9:00 PM</option>
                                        <option value="9:30 PM" @if($data->close_time == '9:30 PM') selected @endif>9:30 PM</option>
                                        <option value="10:00 PM" @if($data->close_time == '10:00 PM') selected @endif>10:00 PM</option>
                                        <option value="10:30 PM" @if($data->close_time == '10:30 PM') selected @endif>10:30 PM</option>
                                        <option value="11:00 PM" @if($data->close_time == '11:00 PM') selected @endif>11:00 PM</option>
                                        <option value="11:30 PM" @if($data->close_time == '11:30 PM') selected @endif>11:30 PM</option>
                                    </select>
                                    <!--<input type="time" required name="closetime" id="closetime" class="form-control" placeholder="Enter Close Time..."/>-->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" required>Storage Running Charges</label>
                                    <input type="text" required name="charges" id="charges" class="form-control"
                                           placeholder="Enter Running Charges..." value="{{$data->charges}}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" required>Storage Non-running Charges</label>
                                    <input type="text" name="charges2" id="charges2" class="form-control"
                                           placeholder="Enter Non-running Charges..." value="{{$data->charges2}}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" required>Phone No</label>
                                    <input type="text" required name="phoneno" id="phoneno" class="form-control"
                                           placeholder="Enter Phone No..." value="{{$data->phoneno}}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" required>Phone No 2</label>
                                    <input type="text" name="phoneno2" id="phoneno2" class="form-control"
                                           placeholder="Enter Phone No 2..." value="{{$data->phoneno2}}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" required>Fax No</label>
                                    <input type="text" name="faxno" id="faxno" class="form-control"
                                           placeholder="Enter Fax No..." value="{{$data->faxno}}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Storage Duration</label>
                                    <select name="duration" required class="form-control " style="border: 1px solid black; height: 35px;" >
                                        <option value="">Select Duration</option>
                                        <option value="Day" @if($data->storage_duration == 'Day') selected @endif>Day</option>
                                        <option value="Week" @if($data->storage_duration == 'Week') selected @endif>Week</option>
                                        <option value="Month" @if($data->storage_duration == 'Month') selected @endif>Month</option>
                                    </select>
                                </div>
                            </div>
                            <?php
                                $forklift_twotruck = explode(', ',$data->forklift_twotruck);
                            ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Does Storage Place Have a Forklift or tow truck ? *</label>
                                    <input type="checkbox" name="optionv[]" id="optionv" value="Forklift" 
                                    @foreach($forklift_twotruck as $key => $val)
                                        @if($val == 'Forklift')
                                            checked
                                        @endif
                                    @endforeach
                                    />
                                    Forklift
                                    &nbsp;
                                    &nbsp;
                                    <input type="checkbox" name="optionv[]" id="optionv2" value="Tow Truck" 
                                    @foreach($forklift_twotruck as $key => $val)
                                        @if($val == 'Tow Truck')
                                            checked
                                        @endif
                                    @endforeach  />
                                    Tow Truck
                                </div>
                            </div>
                            <div class="col-md-4" 
                                @foreach($forklift_twotruck as $key => $val)
                                    @if($val == 'Forklift')
                                        style="display:block;"
                                    @else
                                        style="display:none;"
                                    @endif
                                @endforeach>
                                <div class="form-group">
                                    <label class="form-label">Forklift Price</label>
                                    <input type="number" name="forklift_price" id="forklift_price" class="form-control"
                                           placeholder="Enter Forklift Price..." value="{{$data->forklift_price}}" />
                                </div>
                            </div>
                            <div class="col-md-4" 
                                @foreach($forklift_twotruck as $key => $val)
                                    @if($val == 'Tow Truck')
                                        style="display:block;"
                                    @else
                                        style="display:none;"
                                    @endif
                                @endforeach>
                                <div class="form-group">
                                    <label class="form-label">Tow Truck Price</label>
                                    <input type="number" name="tow_truck_price" id="tow_truck_price" class="form-control"
                                           placeholder="Enter Tow Truck Price..." value="{{$data->tow_truck_price}}" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Additional</label>
                                    <textarea name="additional" id="additional" class="form-control" placeholder="Enter Additional...">{{$data->additional}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button id="sv_btn" type="submit" class="btn  btn-primary">SAVE</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row-->
    </form>


    <div class="modal" id="modaldemo4">
        <div class="modal-dialog modal-dialog-centered text-center " role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                    <i class="fe fe-check-circle fs-100 text-success lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-success tx-semibold" id="success"></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modaldemo5">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                    <i class="fe fe-x-circle fs-100 text-danger lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-danger" id="not_success"></h4>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extraScript')

<script>
    $("#phoneno").mask("(999) 999-9999");
    $("#phoneno2").mask("(999) 999-9999");
    // $("#opentime").mask("99:99");
    // $("#closetime").mask("99:99");

    $('#zip').keyup(function () {
        var o_zip1 = $(this);
        var nav = $(this).parents('.form-group').siblings('.nav');
        if(o_zip1.val() == '')
        {
            nav.children().remove();
            nav.hide();
        }
        else{
            $.ajax({
            url: "{{url('/get_zip')}}",
            type: "GET",
            dataType: "json",
            data: {d_zip1:o_zip1.val()},
            success: function (res){
                nav.show();
                nav.children().remove();
                $.each(res, function (){
                    nav.append(`
                        <li class="nav-item selectAdd">
                            <a class="nav-link text-light" href="javascript:void(0)">${this}</a>
                        </li>
                    `);
                });
                $('.selectAdd').click(function(){
                    $('#zip').val($(this).children('a').text());
                    $('.nav').hide();
                    $('.nav').children().remove();
                })
                // console.log(res);
            }
        });
        }
        // console.log(d_zip1);
    })
    $("#optionv").change(function(){
        $("#forklift_price").parent('div').parent('div').toggle();
    })
    $("#optionv2").change(function(){
        $("#tow_truck_price").parent('div').parent('div').toggle();
    })
</script>

@endsection

