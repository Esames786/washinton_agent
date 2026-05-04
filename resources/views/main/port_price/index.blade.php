@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim('Port Price','/'))}}
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
        
        td{
            vertical-align:middle !important;
        }
        
        
                   /*icon dynimic */
    /*     #overlay {*/
    /*    display: none;*/
    /*    position: fixed;*/
    /*    top: 0;*/
    /*    left: 0;*/
    /*    width: 100%;*/
    /*    height: 100%;*/
    /*    background-color: rgba(0, 0, 0, 0.5);*/
    /*    justify-content: center;*/
    /*    align-items: center;*/
    /*    z-index: 2;*/
    /*}*/

    /*   #errorIcon {*/
    /*    font-size: 17px;*/
    /*    color: #009eda!important;*/
    /*    display: flex;*/
    /*    align-items: center;*/
    /*}*/
    /*.popoverContent {*/
        /* display: none; */
    /*    position: absolute;*/
    /*    background-color: #fff;*/
    /*    border: 1px solid #ccc;*/
    /*    padding: 10px;*/
    /*    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);*/
    /*    z-index: 3;*/
        /* width: 178px; */
        /*right: 295px;*/
        /* bottom: 7px; */
    /*}*/
    /*.Terminal-error {*/
    /*    display: inline-flex;*/
    /*    column-gap: 14px;*/
    /*}*/

    /*label#selectedOptionLabel2 {*/
    /*    display: block;*/
    /*}*/
    /*.icon-relative {*/
    /*    position:relative !important;*/
    /*}*/
    </style>
    <!--/app header-->                                                <!--Page header-->
    <div class="page-header">
        <div class="text-secondary text-center text-uppercase w-100">
                {{-- @if($label[410]->status == 1) --}}
                <!--<div class="Terminal-error">-->
            <h1 class="my-4"><b>Port Price</b></h1>
            <!-- <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon" style="cursor: pointer;"></i>-->
            <!--</div>-->
            {{-- 
            <!--<div class="popoverContent" style="display: none;">-->
            <!--        <div  class="popover-title">{{ $label[365]->name }}</div>-->
            <!--        <div class="popover-content">{{ $label[365]->display }}</div>-->
            <!--     </div>-->
            <!--       @else-->
            <!--         <h1 class="my-4"><b>Port Price</b></h1>-->
            <!--       @endif -->
                   --}}
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
                    Port Price
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="d-flex justify-content-between">                        
                            <h3 class="my-auto">Port Price</h3>
                            @if(Auth::user()->userRole->name == 'Admin')
                            <a class="btn btn-primary" href="{{ url('/port_price/create') }}"><i class="fa fa-plus"></i> Add Port Price</a>
                            @endif
                        </div>
                        <br>
                        <div id="table_data">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" style="width:100%" id="" role="grid">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">Port Price No#</th>
                                        <th class="border-bottom-0">Created By</th>
                                        <th class="border-bottom-0">Created At</th>
                                        <th class="border-bottom-0">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $key => $val)
                                        <tr>
                                            <td>Port Price No#{{ $val->id }}</td>
                                            <td>{{ get_user_name($val->user_id) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    @if(Auth::user()->userRole->name == 'Admin')
                                                        <a class="btn btn-info" href="{{url('/port_price/edit/'.$val->id)}}" title="Edit"><i class="fa fa-edit"></i></a>
                                                        <a class="btn btn-danger" href="{{url('/port_price/destroy/'.$val->id)}}" title="Delete"><i class="fa fa-trash"></i></a>
                                                    @endif
                                                    <a class="btn btn-warning" href="{{url('/port_price/show/'.$val->id)}}" title="Show"><i class="fa fa-eye"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-between">
                                    <div class="text-secondary my-auto">
                                        Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} from total {{$data->total()}} entries
                                    </div>
                                    <div>
                                        {{  $data->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end app-content-->

@endsection

@section('extraScript')
    {{--<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>--}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    
    <script>
                      //=================onchange-values=============================
        // $(document).ready(function() {
        //     // Select all error icons within the document
        //     var $errorIcons = $('.Terminal-error i');
        //     var $openPopoverContent = null;
        
        //     // Iterate over each error icon
        //     $errorIcons.each(function() {
        //         var $errorIcon = $(this);
        //         var $popoverContent = $errorIcon.closest('.Terminal-error').siblings('.popoverContent');
        
        //         // Toggle the popover on icon click
        //         $errorIcon.on('click', function(event) {
        //             event.stopPropagation(); // Prevent the document click event from firing immediately
        
        //             // Close the previously open popover content
        //             if ($openPopoverContent && !$openPopoverContent.is($popoverContent)) {
        //                 $openPopoverContent.hide();
        //             }
        
        //             // Toggle the current popover content
        //             $popoverContent.toggle();
        //             $openPopoverContent = $popoverContent;
        //         });
        //     });
        
        //     // Close the popover if clicked outside
        //     $(document).on('click', function(event) {
        //         if ($openPopoverContent && !$errorIcons.is(event.target) && !$openPopoverContent.is(event.target) && $openPopoverContent
        //             .has(event.target).length === 0) {
        //             $openPopoverContent.hide();
        //             $openPopoverContent = null;
        //         }
        //     });
        // });

    //=================onchange-values=============================
        $(".btn-danger").click(function(e){
          if (confirm("Are you Sure? You want to delete this port price permanently!")) {
          } else {
              e.preventDefault();
          }
        })
    </script>
@endsection


