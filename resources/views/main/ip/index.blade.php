@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim('Ip Address','/'))}}
@endsection
@section('content')

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
            <h1 class="my-4"><b>Ip Address</b></h1>
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
                    <div class="container-fluid">
                    </div>
                </div>
                <div class="card-body">

                    <!-- Tab content -->
                    <div class="">
                        <div class="d-flex justify-content-between">                        
                            <h3>Ip Address</h3>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Add New</button>
                        </div>
                        <br>
                        <div id="table_data">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" style="width:100%" id="" role="grid">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">#</th>
                                        <th class="border-bottom-0">Ip Address</th>
                                        <th class="border-bottom-0">Created At</th>
                                        <th class="border-bottom-0">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $key => $val)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{$val->ip_address }}</td>
                                            <td>{{ \Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <input type="hidden" class="id" value="{{$val->id}}">
                                                    <a class="btn btn-success editMile" title="Edit" href="#" data-toggle="modal" data-target="#exampleModalEdit{{$val->id}}"><i class="fa fa-edit"></i></a>
                                                    @if($val->status == 'Active')
                                                    <a class="btn btn-danger" title="Disable" href="{{url('/ip_address/destroy/'.$val->id)}}"><i class="fa fa-trash"></i></a>
                                                    @else
                                                    <a class="btn btn-info" title="Active" href="{{url('/ip_address/destroy/'.$val->id)}}"><i class="fa fa-child"></i></a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <input type="hidden" value="{{url('')}}" class="url">
                                <div class="d-flex justify-content-between">
                                    <div class="text-secondary my-auto">
                                        Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} from total {{$data->total()}} entries
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

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content container p-0">
            <div class="modal-header">
                <h3>Add Ip Address</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="modal-body">
            <div class="col-lg-12 border text-center calculatorMain">
                <form action="{{url('/ip_address/store')}}" method="POST">
                    @csrf
                    <div style="form-layout margin:20px auto 0;padding: 1rem 0 0;">
                        <div class="row">
                            <div class="form-group col-12 position-relative">
                                <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Ip Address</label>
                                <input class="form-control " name="ip_address" type="text"  required placeholder="Ip Address">
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
                    url:url+"/ip_address/edit",
                    type:"GET",
                    data:{id:id},
                    dataType:"json",
                    success:function(res)
                    {
                        var ip = res.data.ip_address;
                        $("body").append(`
                            <div class="modal fade" id="exampleModalEdit${res.data.id}" tabindex="-1" aria-labelledby="exampleModalLabelEdit${res.data.id}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content container p-0">
                                        <div class="modal-header">
                                            <h3>Edit Ip Address</h3>
                                            <button type="button" class="close close2" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="col-lg-12 border text-center calculatorMain">
                                                <form action="${url}/ip_address/update/${res.data.id}" method="POST">
                                                    @method('PUT')
                                                    @csrf
                                                    <div style="form-layout margin:20px auto 0;padding: 1rem 0 0;">
                                                        <div class="row">
                                                            <div class="form-group col-12 position-relative">
                                                                <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Ip Address</label>
                                                                <input class="form-control" value="${res.data.ip_address}" name="ip_address" type="text" required placeholder="Ip Address">
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
                        $(".saveMile").click(function(e){
                            var input = $(this).parent('div').siblings('div').children('input[name="ip_address"]');
                            input.siblings('text-danger').remove();
                            if(!input.val())
                            {
                                e.preventDefault();
                                input.after('<div class="text-danger text-left">The ip field is required!</div>');
                            }
                        })
                    }
                })
            })
        })
        
        $(".saveMile").click(function(e){
            var input = $(this).parent('div').siblings('div').children('input[name="ip_address"]');
            input.siblings('text-danger').remove();
            if(!input.val())
            {
                e.preventDefault();
                input.after('<div class="text-danger text-left">The ip field is required!</div>');
            }
        })
    </script>

@endsection


