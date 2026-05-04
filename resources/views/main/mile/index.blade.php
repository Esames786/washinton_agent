@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim('Mile Price','/'))}}
@endsection
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
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
        
             
           #overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        z-index: 2;
        }
    
       #errorIcon {
        /*font-size: 14px;*/
        color: #009eda!important;
        cursor: pointer;
      }
      .popoverContent {
        /* display: none; */
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        padding: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 3;
        right: 150px;
      }

    .Terminal-error {
        display: inline-flex;
        column-gap: 6px;
        align-items: baseline;
    }

    label#selectedOptionLabel2 {
        display: block;
    }

        
    </style>
    <!--/app header-->                                                <!--Page header-->
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <input type="hidden" value="{{trim("$_SERVER[REQUEST_URI]",'/')}}" id="titlee">-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Price Per Mile</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        
        <div class="text-secondary text-center text-uppercase w-100">
               @if($label[382-1]->status == 1)
               <div class="Terminal-error">
            <h1 class="my-4"><b>Price Per Mile</b></h1>
            <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
            style="cursor: pointer;"></i>
            </div>
            <div class="popoverContent" style="display: none;">
                 <div class="popover-title">{{ $label[382-1]->name }}</div>
                 <div class="popover-content">{{ $label[382-1]->display }}</div>
            </div>
          
            @else
                <h1 class="my-4"><b>Price Per Mile</b></h1>
            @endif
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
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="container-fluid">
                    </div>
                </div>
                <div class="card-body">

                    <!-- Tab content -->
                    <div class="">
                        <div class="d-flex justify-content-between">                        
                            <h3>Price Per Mile</h3>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Add New</button>
                        </div>
                        <br>
                        <div id="table_data">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" style="width:100%" id="" role="grid">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">#</th>
                                        <th class="border-bottom-0">Mile</th>
                                        <th class="border-bottom-0">Price Per Mile</th>
                                        <th class="border-bottom-0">Commission</th>
                                        <th class="border-bottom-0">Created At</th>
                                        <th class="border-bottom-0">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($mile as $key => $val)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{$val->mile - 100}} - {{ $val->mile - 1 }}</td>
                                            <td>${{ $val->mile_price }}</td>
                                            <td>${{ $val->commission }}</td>
                                            <td>{{ \Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i:s A') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <input type="hidden" class="id" value="{{$val->id}}">
                                                    <a class="btn btn-success editMile" href="#" data-toggle="modal" data-target="#exampleModalEdit{{$val->id}}"><i class="fa fa-edit"></i></a>
                                                    <a class="btn btn-danger" href="{{url('/mile-price/destroy/'.$val->id)}}"><i class="fa fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <input type="hidden" value="{{url('')}}" class="url">
                                <div class="d-flex justify-content-between">
                                    <div class="text-secondary my-auto">
                                        Showing {{ $mile->firstItem() }} to {{ $mile->lastItem() }} from total {{$mile->total()}} entries
                                    </div>
                                    <div>
                                        {{  $mile->links() }}
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end app-content-->

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content container p-0">
            <div class="modal-header">
                <h3>Add Mile Record</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="modal-body">
            <div class="col-lg-12 border text-center calculatorMain">
                <form action="{{url('/mile-price/store')}}" method="POST">
                    @csrf
                    <div style="form-layout margin:20px auto 0;padding: 1rem 0 0;">
                        <div class="row">
                            <div class="form-group col-12">
                                <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Select Miles</label>
                                <select  class="form-control mile py-0" required name="mile">
                                    <option selected disabled>Select Miles</option>
                                    <?php
                                        for($i=0; $i<10000; $i = $i + 100)
                                        {
                                    ?>
                                        <option value="<?php echo $i + 101; ?>"><?php echo $i + 1; ?> - <?php echo $i + 100; ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-12 position-relative">
                                <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Per Mile Price</label>
                                <div class="bg-primary py-3 px-2 text-light" style="position: absolute;left: 13px;top: 30px;font-size: 17px;">
                                    <i class="fa fa-usd text-light m-auto" aria-hidden="true"></i>
                                </div>
                                <input style="padding-left:2rem;" class="form-control milePrice" name="milePrice"  type="text" required   placeholder="Per Mile Price">
                            </div>
                            <div class="form-group col-12 position-relative">
                                <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Commission</label>
                                <div class="bg-primary py-3 px-2 text-light" style="position: absolute;left: 13px;top: 30px;font-size: 17px;">
                                    <i class="fa fa-usd text-light m-auto" aria-hidden="true"></i>
                                </div>
                                <input style="padding-left:2rem;" class="form-control commissionPrice" name="commissionPrice" type="text"  required placeholder="Commission Price">
                            </div>
                            <div class="form-group col-12 text-right">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="submit" value="submit" class="btn btn-primary saveMile">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

  
@endsection

@section('extraScript')
    {{--<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>--}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
                              //=================onchange-values=============================
        $(document).ready(function() {
            // Select all error icons within the document
            var $errorIcons = $('.Terminal-error i');
            var $openPopoverContent = null;
        
            // Iterate over each error icon
            $errorIcons.each(function() {
                var $errorIcon = $(this);
                var $popoverContent = $errorIcon.closest('.Terminal-error').siblings('.popoverContent');
        
                // Toggle the popover on icon click
                $errorIcon.on('click', function(event) {
                    event.stopPropagation(); // Prevent the document click event from firing immediately
        
                    // Close the previously open popover content
                    if ($openPopoverContent && !$openPopoverContent.is($popoverContent)) {
                        $openPopoverContent.hide();
                    }
        
                    // Toggle the current popover content
                    $popoverContent.toggle();
                    $openPopoverContent = $popoverContent;
                });
            });
        
            // Close the popover if clicked outside
            $(document).on('click', function(event) {
                if ($openPopoverContent && !$errorIcons.is(event.target) && !$openPopoverContent.is(event.target) && $openPopoverContent
                    .has(event.target).length === 0) {
                    $openPopoverContent.hide();
                    $openPopoverContent = null;
                }
            });
        });

    //=================onchange-values=============================
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $('.editMile').on('click',function(){
                var url = $('.url').val();
                var id = $(this).siblings('.id').val();
                // console.log(id);
                // console.log(url);
                $.ajax({
                    url:url+"/mile-price/edit",
                    type:"GET",
                    data:{id:id},
                    dataType:"json",
                    success:function(data)
                    {
                        var mile = data.mile.mile;
                        $("body").append(`
                            <div class="modal fade" id="exampleModalEdit${data.mile.id}" tabindex="-1" aria-labelledby="exampleModalLabelEdit${data.mile.id}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content container p-0">
                                        <div class="modal-header">
                                            <h3>Edit Mile Record</h3>
                                            <button type="button" class="close close2" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="col-lg-12 border text-center calculatorMain">
                                                <form action="${url}/mile-price/update/${data.mile.id}" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div style="form-layout margin:20px auto 0;padding: 1rem 0 0;">
                                                        <div class="row">
                                                            <div class="form-group col-12">
                                                                <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Select Miles</label>
                                                                <select  class="form-control mileEdit py-0" required name="mile">
                                                                    <option selected disabled>Select Miles</option>
                                                                    <?php
                                                                        for($i=0; $i<10000; $i = $i + 100)
                                                                        {
                                                                    ?>
                                                                        <option 
                                                                        ${("<?php echo $i + 101 ?>" == data.mile.mile) ? "selected" : ""}
                                                                        value="<?php echo $i + 101; ?>"><?php echo $i + 1; ?> - <?php echo $i + 100; ?></option>
                                                                    <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        
                                                            <div class="form-group col-12 position-relative">
                                                                <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Per Mile Price</label>
                                                                <div class="bg-primary py-3 px-2 text-light" style="position: absolute;left: 13px;top: 30px;font-size: 17px;">
                                                                    <i class="fa fa-usd text-light m-auto" aria-hidden="true"></i>
                                                                </div>
                                                                <input style="padding-left:2rem;" class="form-control milePriceEdit" name="milePrice" value="${data.mile.mile_price}"  type="text" required   placeholder="Per Mile Price">
                                                            </div>
                                                                <div class="form-group col-12 position-relative">
                                                                <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Commission</label>
                                                                <div class="bg-primary py-3 px-2 text-light" style="position: absolute;left: 13px;top: 30px;font-size: 17px;">
                                                                    <i class="fa fa-usd text-light m-auto" aria-hidden="true"></i>
                                                                </div>
                                                                <input style="padding-left:2rem;" class="form-control commissionPriceEdit" value="${data.mile.commission}" name="commissionPrice" type="text"  required placeholder="Commission Price">
                                                            </div>
                                                            <div class="form-group col-12 text-right">
                                                                <button type="button" class="btn btn-secondary close2" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="submit2" value="submit" class="btn btn-primary saveMile">Update</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                })
            })
        })
    </script>

@endsection


