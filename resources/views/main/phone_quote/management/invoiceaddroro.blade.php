@extends('layouts.innerpages')

@section('template_title')
    Add Roro Invoice
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
            <h4 class="page-title mb-0">Add Roro Invoice </h4>

            <h3 class="page-title mb-0"></h3>


            <h4 id="orderidplace"></h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Pages</a>
                </li>

                <li class="breadcrumb-item active" aria-current="page"><a href="#">Add Roro Invoice</a></li>
            </ol>
        </div>

    </div>
    <!--End Page header-->
    <!-- Row -->

    <form action="/store_invoice_roro" id="form" method="POST">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Vin Number </label>
                                    <input type="text" name="vin" id="vin" class="form-control"
                                           onkeyup="get_vin()"
                                           placeholder="Vin Number..." value="{{ old('vin') }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Billing Name</label>
                                    <input type="text" name="bill_name" id="bill_name" class="form-control"
                                           placeholder="Billing Name..." value="{{ old('bill_name') }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Billing Address</label>
                                    <input type="text" name="bill_address" id="bill_address" class="form-control"
                                           placeholder="Billing Address..." value="{{ old('bill_address') }}" />
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Year <span class="text-danger">*</span></label>
                                    <select name="year" id="year" class="form-control">
                                        <option value="" selected>Select Year</option>
                                        @for($i=date('Y'); $i > 1901; $i--)
                                            <option value="{{$i}}" @if(old('year') == $i) selected @endif>{{$i}}</option>
                                        @endfor
                                    </select>
                                    @if($errors->has('year'))
                                        <span class="text-danger">{{$errors->first('year')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Make <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ old('make')  }}" onkeyup="getmake()" id='make' name='make' placeholder="Make...">
                                    @if($errors->has('make'))
                                        <span class="text-danger">{{$errors->first('make')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Model <span class="text-danger">*</span></label>
                                    <input type="text" name="model" id="model" class="form-control"
                                           placeholder="Model..." value="{{ old('model') }}" onkeyup="getmodel()">
                                    @if($errors->has('model'))
                                        <span class="text-danger">{{$errors->first('model')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Pickup From <span class="text-danger">*</span></label>
                                    <input type="text" name="from_address" id="from_address" class="form-control"
                                           placeholder="Pickup From..." value="{{ old('from_address') }}" />
                                    @if($errors->has('from_address'))
                                        <span class="text-danger">{{$errors->first('from_address')}}</span>
                                    @endif
                                </div>
                                <ul class="nav flex-column border scrollul" style="max-height:200px;overflow:scroll;display:none;position: absolute;top: 68px;z-index: 1;background: #f7f7f7;width: 93%;">
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Deliver To <span class="text-danger">*</span></label>
                                    <input type="text" name="too_address" id="too_address" class="form-control"
                                           placeholder="Deliver To..." value="{{ old('too_address') }}" />
                                    @if($errors->has('too_address'))
                                        <span class="text-danger">{{$errors->first('too_address')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Port of Loading</label>
                                    <input type="text" name="delivered_port" id="delivered_port" class="form-control"
                                           placeholder="Port Of Loading..." value="{{ old('delivered_port') }}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Vessel</label>
                                    <select name="vessel_grimaldi_salluam" id="vessel_grimaldi_salluam" class="form-control">
                                        <option value="" selected>Select Vessel</option>
                                        <option value="Grimaldi Group" @if(old('vessel_grimaldi_salluam') == 'Grimaldi Group') selected @endif>Grimaldi Group</option>
                                        <option value="SALLAUM LINES" @if(old('vessel_grimaldi_salluam') == 'SALLAUM LINES') selected @endif>SALLAUM LINES</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Transportation Fees</label>
                                    <input type="text" name="transportation_fees" id="transportation_fees" class="form-control numeric" maxlength="8"
                                           placeholder="Transportation Fees..." value="{{ old('transportation_fees') }}" />
                                    @if($errors->has('transportation_fees'))
                                        <span class="text-danger">{{$errors->first('transportation_fees')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Auction Storage Fees</label>
                                    <input type="text" name="auction_storage_fees" id="auction_storage_fees" class="form-control numeric" maxlength="8"
                                           placeholder="Auction Storage Fees..." value="{{ old('auction_storage_fees') }}" />
                                    @if($errors->has('auction_storage_fees'))
                                        <span class="text-danger">{{$errors->first('auction_storage_fees')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Yard Storage Fees</label>
                                    <input type="text" name="yard_storage_fees" id="yard_storage_fees" class="form-control numeric" maxlength="8"
                                           placeholder="Yard Storage Fees..." value="{{ old('yard_storage_fees') }}" />
                                    @if($errors->has('yard_storage_fees'))
                                        <span class="text-danger">{{$errors->first('yard_storage_fees')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Yard Forklift Fees (Load & Unload)</label>
                                    <input type="text" name="yard_forklift_fees" id="yard_forklift_fees" class="form-control numeric" maxlength="8"
                                           placeholder="Yard Forklift Fees (Load & Unload)..." value="{{ old('yard_forklift_fees') }}" />
                                    @if($errors->has('yard_forklift_fees'))
                                        <span class="text-danger">{{$errors->first('yard_forklift_fees')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Redelivery Fees</label>
                                    <input type="text" name="redelivery_fees" id="redelivery_fees" class="form-control numeric" maxlength="8"
                                           placeholder="Redelivery Fees..." value="{{ old('redelivery_fees') }}" />
                                    @if($errors->has('redelivery_fees'))
                                        <span class="text-danger">{{$errors->first('redelivery_fees')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Shipping Fees</label>
                                    <input type="text" name="shipping_fees" id="shipping_fees" class="form-control numeric" maxlength="8"
                                           placeholder="Shipping Fees..." value="{{ old('shipping_fees') }}" />
                                    @if($errors->has('shipping_fees'))
                                        <span class="text-danger">{{$errors->first('shipping_fees')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Non Runner Fees</label>
                                    <input type="text" name="non_runner_fees" id="non_runner_fees" class="form-control numeric" maxlength="8"
                                           placeholder="Non Runner Fees..." value="{{ old('non_runner_fees') }}" />
                                    @if($errors->has('non_runner_fees'))
                                        <span class="text-danger">{{$errors->first('non_runner_fees')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Forklift Fees</label>
                                    <input type="text" name="forklift_fees" id="forklift_fees" class="form-control numeric" maxlength="8"
                                           placeholder="Forklift Fees..." value="{{ old('forklift_fees') }}" />
                                    @if($errors->has('forklift_fees'))
                                        <span class="text-danger">{{$errors->first('forklift_fees')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Telex Fees</label>
                                    <input type="text" name="telex_fees" id="telex_fees" class="form-control numeric" maxlength="8"
                                           placeholder="Telex Fees..." value="{{ old('telex_fees') }}" />
                                    @if($errors->has('telex_fees'))
                                        <span class="text-danger">{{$errors->first('telex_fees')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Extra Other Fees</label>
                                    <input type="text" name="extra_other_fees" id="extra_other_fees" class="form-control numeric" maxlength="8"
                                           placeholder="Extra Other Fees..." value="{{ old('extra_other_fees') }}" />
                                    @if($errors->has('extra_other_fees'))
                                        <span class="text-danger">{{$errors->first('extra_other_fees')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Paid Amount</label>
                                    <input type="text" name="paid_amount" id="paid_amount" class="form-control numeric" maxlength="8"
                                           placeholder="Paid Amount..." value="{{ old('paid_amount') }}" />
                                    @if($errors->has('paid_amount'))
                                        <span class="text-danger">{{$errors->first('paid_amount')}}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn  btn-primary">SAVE</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row-->
    </form>
@endsection


@section('extraScript')
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>
<script>
    $(document).ready(function () {
        $(".numeric").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });
        
        $('#from_address').keyup(function () {
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
                                <a class="nav-link" href="javascript:void(0)">${this}</a>
                            </li>
                        `);
                    });
                    $('.selectAdd').click(function(){
                        nav.hide();
                        nav.children().remove();
                        $('#from_address').val($(this).children('a').text());
                    })
                }
            });
            }
        })
    });
    function getmake() {
        $("#make").autocomplete({
            source: "/getmake"
        });
    }
    
    function getmodel() {

        var yy = $("#year").val();
        var mm = $("#make").val();


        $("#model").autocomplete({
            source: "/getmodel?year=" + yy + "&make=" + mm
        });
    }
    
    function get_vin() {
        var vinno = $(`#vin`).val();
        if(vinno == '')
        {
            $("#year").children('option').removeAttr('selected');
            $("#year").children('option').eq(0).attr('selected','selected');
            $("#make").val('');
            $("#model").val('');
        }
        else
        {
            $.ajax({
                type: "GET",
                url: "/getvin",
                dataType: 'JSON',
                data: {term: vinno},
                success: function (res) {
                    if (res) {
                        $("#year").children('option').removeAttr('selected');
                        $("#year").children('option').each(function(){
                            if($(this).val() == res[2].value)
                            {
                                $(this).attr('selected','selected');
                            }
                        });
                        $("#make").val(res[0].value);
                        $("#model").val(res[1].value);
                    }
                },

            });
        }

    }
</script>
@endsection


