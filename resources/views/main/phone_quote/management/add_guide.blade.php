@extends('layouts.innerpages')

@section('template_title')
    Add Carrier
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
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@section('content')

    <div class="page-header">
        <div class="text-secondary text-center text-uppercase">
            <h1 class="my-4"><b>Add Guide</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    <form action="/add_guide_post" id="form" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token()}}">
        <input type="hidden" name="orderid" value="">
        <input  name="id" type="hidden" value="{{ isset($data->id) ? $data->id : '' }}">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title mt-3"><h1>Add Guide</h1></div>
                    </div>
                    <div class="card-body">
                        <div class="card-title font-weight-bold"></div>
                        <div class="row">
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Type</label>
                                    <select required name="guide_type" class="form-control guide_type">
                                        <option {{ isset($data->guide_type) && $data->guide_type == 1 ? 'selected' : '' }} value="1">Admin Panel</option>
                                        <option {{ isset($data->guide_type) && $data->guide_type == 2 ? 'selected' : '' }} value="2">Vehicles</option>
                                        <option {{ isset($data->guide_type) && $data->guide_type == 3 ? 'selected' : '' }} value="3">MotorCycles</option>
                                        <option {{ isset($data->guide_type) && $data->guide_type == 4 ? 'selected' : '' }} value="4">Heavy</option>
                                        <option {{ isset($data->guide_type) && $data->guide_type == 5 ? 'selected' : '' }} value="5">Order Taking</option>
                                        <option {{ isset($data->guide_type) && $data->guide_type == 6 ? 'selected' : '' }} value="6">Delivery</option>
                                        <option {{ isset($data->guide_type) && $data->guide_type == 7 ? 'selected' : '' }} value="7">Dispatch</option>
                                        <option {{ isset($data->guide_type) && $data->guide_type == 8 ? 'selected' : '' }} value="8">Approaching</option>
                                    </select>


                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Page Name</label>
                                    <input required name="page_name" class="form-control" value="{{ isset($data->page_name) ? $data->page_name : '' }}">
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Page Route</label>
                                    <input onkeyup="this.value = this.value.replace(/\s/g, '');" required name="page_route" class="form-control" value="{{ isset($data->page_name) ? $data->page_route : '' }}">
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Thumbnail</label>
                                    <input required type="file" name="thumbnail" class="form-control" accept="image/*">

                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12">
                                <div id="summernote"></div>
                                <textarea name="guide_content" id="guide_content" style="display:none;">{{ isset($data->guide_content) ? $data->guide_content : '' }}</textarea>
                            </div>


                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button id="sv_btn" type="submit" class="btn  btn-primary w-35">SAVE</button>
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
    <script src="{{ url('assets/summer_note/js/summernote.min.js')}}"></script>
    <script>
        $('#summernote').summernote({
            placeholder: '',
            tabsize: 2,
            height: 500
        });

        $('body').on('keyup change', '.note-editable', function () {
            $('#guide_content').val($(this).html());
        });

        setTimeout(function() {
            var val = `<?php echo isset($data->guide_content) ? $data->guide_content : '' ?>`;
            $('.note-editable').html(val);
        }, 1000);
    </script>
    <script>
        $("body").delegate(".ophone", "focus", function () {
            $(".ophone").mask("(999) 999-9999");
            $(".ophone")[0].setSelectionRange(0, 0);
        });
        $(document).on('click', '#companyphone', function () {
            $("#companyphone").mask("(999) 999-9999");
        });
        $(document).on('click', '#driverphone', function () {
            $("#driverphone").mask("(999) 999-9999");
        });

        $(document).on('click', '#negative', function () {
            if ($('#negative').is(":checked")) {
                $('#negativeno').append(`<input maxlength="10" type="text" required name="negativenovalue" id="negativenovalue" class="" />`);
            }
            else {

                $('#negativenovalue').remove();
            }
        });


        $(".model0").autocomplete({
            source: "/get_carrier_name"
        });

        $(".model0").change(function(){

            var carriername=$('.model0').val();
            $.ajax({
                url:'/get_carrier_detail',
                type: 'get',
                data: {carriername:carriername},
                success: function(data){

                    $('#location').val(data.location);
                    $('#mcno').val(data.mcno);
                    $('#companyphone').val(data.companyphoneno);
                    $('#driverphone').val(data.driverphoneno);
                    $('#pickupdate').val(data.est_pickupdate);
                    $('#deliverydate').val(data.est_deliverydate);
                    $('#askprice').val(data.askprice);
                    $('#comments').val(data.comments);
                    if(data.opt_insurance==1){
                        $("#askinsurance").prop("checked", true);
                    }
                    if(data.opt_negative==1){
                        //$("#negative").prop("checked", true);
                        $('#negative').trigger('click');

                        $('#negativenovalue').val(data.negative_no);


                    }

                }

            });
        });


    </script>

@endsection

