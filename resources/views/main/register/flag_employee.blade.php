@extends('layouts.innerpages')
@section('template_title')
    Flag Employee
@endsection
@section('content')
    <style>
        /* Style the tab */
        .table-responsive{
            overflow:unset !important;
        }
        
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
        
        .dropdown-menu{
            left:-6rem !important;
        }

        /* Go from zero to full opacity */
        @keyframes fadeEffect {
            from {opacity: 0;}
            to {opacity: 1;}
        }
    </style>
    <!-- Row -->
    @include('partials.mainsite_pages.return_function')
    <div class="row">
        <div class="col-12">
            <!--div-->
            <div class="page-header">
                <!--<div class="page-leftheader">-->
                <!--    {{--<h4 class="page-title mb-0">Add Employee</h4>--}}-->
                <!--    <ol class="breadcrumb">-->
                <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Home</a>-->
                <!--        </li>-->
                <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Add Employee</a></li>-->
                <!--    </ol>-->
                <!--</div>-->
                <!--{{--<div class="page-rightheader">--}}-->
                <!--    {{--<div class="btn btn-list">--}}-->
                <!--        {{--<a href="#" class="btn btn-info"><i class="fe fe-settings mr-1"></i> General Settings </a>--}}-->
                <!--        {{--<a href="#" class="btn btn-danger"><i class="fe fe-printer mr-1"></i> Print </a>--}}-->
                <!--        {{--<a href="#" class="btn btn-warning"><i class="fe fe-shopping-cart mr-1"></i> Buy Now </a>--}}-->
                <!--    {{--</div>--}}-->
                <!--{{--</div>--}}-->
                <div class="text-secondary text-center text-uppercase w-100">
                    <h1 class="my-4"><b>Flag Employees</b></h1>
                </div>
            </div>
            <div class="card mt-5">
                <!--<div class="card-header">-->
                <!--    <div class="card-title"><a type="button" href="{{url('add_employee')}}"-->
                <!--                               class="btn btn-icon btn-primary">Add-->
                <!--            Employee<i class="fe fe-plus"></i></a></div>-->
                <!--</div>-->
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive">
                            <!-- Tab links -->
                            <div class="tab">
                                <button class="tablinks" onclick="openCity(event, 'Order_Taker')" id="defaultOpen">Order Taker ({{ count($order_taker) }})</button>
                                <button class="tablinks" onclick="openCity(event, 'Dispatcher')">Dispatcher ({{ count($dispatcher) }})</button>
                                <button class="tablinks" onclick="openCity(event, 'Price_Checker')">Price Checker ({{ count($price_checker) }})</button>
                                <button class="tablinks" onclick="openCity(event, 'Code_Giver')">Code Giver ({{ count($code_giver) }})</button>
                                <button class="tablinks" onclick="openCity(event, 'Chat_Approver')">Chat Approver ({{ count($chat_approver) }})</button>
                                <button class="tablinks" onclick="openCity(event, 'Accountant')">Accountant ({{ count($accountant) }})</button>
                                <button class="tablinks" onclick="openCity(event, 'Seller_Agent')">Seller Agent ({{ count($seller_agent) }})</button>
                                <button class="tablinks" onclick="openCity(event, 'CSR')">CSR ({{ count($csr) }})</button>
                                <button class="tablinks" onclick="openCity(event, 'Feedback_And_Review')">Feedback And Review ({{ count($feedback_and_review) }})</button>
                                <button class="tablinks" onclick="openCity(event, 'Trust_And_Safety')">Trust And Safety ({{ count($trust_and_safety) }})</button>
                                <button class="tablinks" onclick="openCity(event, 'QA')">QA ({{ count($qa) }})</button>
                                <button class="tablinks" onclick="openCity(event, 'Manager')">Manager ({{ count($manager) }})</button>
                                <button class="tablinks" onclick="openCity(event, 'Deleted')">Deleted ({{ count($deleted) }})</button>
                            </div>

                            <!-- Tab content -->

                            <div id="Order_Taker" class="tabcontent">
                                <table id="example4" class="table table-bordered table-striped key-buttons" style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">NAME</th>
                                        <th class="border-bottom-0">EMAIL</th>
                                        <th class="border-bottom-0">ROLE</th>
                                        <th class="border-bottom-0">NO OF FLAGS</th>
                                        <th class="border-bottom-0">ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $order_taker as $val)
                                        <tr>
                                            <td>
                                                {{$val->name}}
                                                @if($val->slug)
                                                <br>
                                                ({{$val->slug}})
                                                @endif
                                            </td>
                                            <td>{{$val->email}}</td>
                                            <td>{{ get_role($val->role,'name')}}</td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $val->flag_count > 1 ? $val->flag_count.' Flags' : $val->flag_count.' Flag' }}  <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </span>
                                            </td>
                                            <td>
                                                @if($val->flag_count > 0)
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#removeFlag" title="Remove Flag" onclick="removeFlag({{$val->id}})">
                                                      <i class="fa fa-universal-access" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#flagRed" onclick="redFlag({{$val->id}})" title="Red Flag">
                                                    <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="Dispatcher" class="tabcontent">
                                <table id="example5" class="table table-bordered table-striped key-buttons"  style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">NAME</th>
                                        <th class="border-bottom-0">EMAIL</th>
                                        <th class="border-bottom-0">ROLE</th>
                                        <th class="border-bottom-0">NO OF FLAGS</th>
                                        <th class="border-bottom-0">ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $dispatcher as $val)
                                        <tr>
                                            <td>
                                                {{$val->name}}
                                                @if($val->slug)
                                                <br>
                                                ({{$val->slug}})
                                                @endif
                                            </td>
                                            <td>{{$val->email}}</td>
                                            <td>{{ get_role($val->role,'name')}}</td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $val->flag_count > 1 ? $val->flag_count.' Flags' : $val->flag_count.' Flag' }}  <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </span>
                                            </td>
                                            <td>
                                                @if($val->flag_count > 0)
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#removeFlag" title="Remove Flag" onclick="removeFlag({{$val->id}})">
                                                      <i class="fa fa-universal-access" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#flagRed" onclick="redFlag({{$val->id}})" title="Red Flag">
                                                    <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div id="Price_Checker" class="tabcontent">
                                <table id="example5" class="table table-bordered table-striped key-buttons"  style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">NAME</th>
                                        <th class="border-bottom-0">EMAIL</th>
                                        <th class="border-bottom-0">ROLE</th>
                                        <th class="border-bottom-0">NO OF FLAGS</th>
                                        <th class="border-bottom-0">ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $price_checker as $val)
                                        <tr>
                                            <td>
                                                {{$val->name}}
                                                @if($val->slug)
                                                <br>
                                                ({{$val->slug}})
                                                @endif
                                            </td>
                                            <td>{{$val->email}}</td>
                                            <td>{{ get_role($val->role,'name')}}</td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $val->flag_count > 1 ? $val->flag_count.' Flags' : $val->flag_count.' Flag' }}  <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </span>
                                            </td>
                                            <td>
                                                @if($val->flag_count > 0)
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#removeFlag" title="Remove Flag" onclick="removeFlag({{$val->id}})">
                                                      <i class="fa fa-universal-access" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#flagRed" onclick="redFlag({{$val->id}})" title="Red Flag">
                                                    <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="Code_Giver" class="tabcontent">
                                <table id="example5" class="table table-bordered table-striped key-buttons"  style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">NAME</th>
                                        <th class="border-bottom-0">EMAIL</th>
                                        <th class="border-bottom-0">ROLE</th>
                                        <th class="border-bottom-0">NO OF FLAGS</th>
                                        <th class="border-bottom-0">ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $code_giver as $val)
                                        <tr>
                                            <td>
                                                {{$val->name}}
                                                @if($val->slug)
                                                <br>
                                                ({{$val->slug}})
                                                @endif
                                            </td>
                                            <td>{{$val->email}}</td>
                                            <td>{{ get_role($val->role,'name')}}</td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $val->flag_count > 1 ? $val->flag_count.' Flags' : $val->flag_count.' Flag' }}  <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </span>
                                            </td>
                                            <td>
                                                @if($val->flag_count > 0)
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#removeFlag" title="Remove Flag" onclick="removeFlag({{$val->id}})">
                                                      <i class="fa fa-universal-access" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#flagRed" onclick="redFlag({{$val->id}})" title="Red Flag">
                                                    <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="Chat_Approver" class="tabcontent">
                                <table id="example5" class="table table-bordered table-striped key-buttons"  style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">NAME</th>
                                        <th class="border-bottom-0">EMAIL</th>
                                        <th class="border-bottom-0">ROLE</th>
                                        <th class="border-bottom-0">NO OF FLAGS</th>
                                        <th class="border-bottom-0">ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $chat_approver as $val)
                                        <tr>
                                            <td>
                                                {{$val->name}}
                                                @if($val->slug)
                                                <br>
                                                ({{$val->slug}})
                                                @endif
                                            </td>
                                            <td>{{$val->email}}</td>
                                            <td>{{ get_role($val->role,'name')}}</td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $val->flag_count > 1 ? $val->flag_count.' Flags' : $val->flag_count.' Flag' }}  <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </span>
                                            </td>
                                            <td>
                                                @if($val->flag_count > 0)
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#removeFlag" title="Remove Flag" onclick="removeFlag({{$val->id}})">
                                                      <i class="fa fa-universal-access" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#flagRed" onclick="redFlag({{$val->id}})" title="Red Flag">
                                                    <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="Accountant" class="tabcontent">
                                <table id="example5" class="table table-bordered table-striped key-buttons"  style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">NAME</th>
                                        <th class="border-bottom-0">EMAIL</th>
                                        <th class="border-bottom-0">ROLE</th>
                                        <th class="border-bottom-0">NO OF FLAGS</th>
                                        <th class="border-bottom-0">ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $accountant as $val)
                                        <tr>
                                            <td>
                                                {{$val->name}}
                                                @if($val->slug)
                                                <br>
                                                ({{$val->slug}})
                                                @endif
                                            </td>
                                            <td>{{$val->email}}</td>
                                            <td>{{ get_role($val->role,'name')}}</td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $val->flag_count > 1 ? $val->flag_count.' Flags' : $val->flag_count.' Flag' }}  <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </span>
                                            </td>
                                            <td>
                                                @if($val->flag_count > 0)
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#removeFlag" title="Remove Flag" onclick="removeFlag({{$val->id}})">
                                                      <i class="fa fa-universal-access" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#flagRed" onclick="redFlag({{$val->id}})" title="Red Flag">
                                                    <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="Seller_Agent" class="tabcontent">
                                <table id="example5" class="table table-bordered table-striped key-buttons"  style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">NAME</th>
                                        <th class="border-bottom-0">EMAIL</th>
                                        <th class="border-bottom-0">ROLE</th>
                                        <th class="border-bottom-0">NO OF FLAGS</th>
                                        <th class="border-bottom-0">ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $seller_agent as $val)
                                        <tr>
                                            <td>
                                                {{$val->name}}
                                                @if($val->slug)
                                                <br>
                                                ({{$val->slug}})
                                                @endif
                                            </td>
                                            <td>{{$val->email}}</td>
                                            <td>{{ get_role($val->role,'name')}}</td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $val->flag_count > 1 ? $val->flag_count.' Flags' : $val->flag_count.' Flag' }}  <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </span>
                                            </td>
                                            <td>
                                                @if($val->flag_count > 0)
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#removeFlag" title="Remove Flag" onclick="removeFlag({{$val->id}})">
                                                      <i class="fa fa-universal-access" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#flagRed" onclick="redFlag({{$val->id}})" title="Red Flag">
                                                    <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="CSR" class="tabcontent">
                                <table id="example5" class="table table-bordered table-striped key-buttons"  style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">NAME</th>
                                        <th class="border-bottom-0">EMAIL</th>
                                        <th class="border-bottom-0">ROLE</th>
                                        <th class="border-bottom-0">NO OF FLAGS</th>
                                        <th class="border-bottom-0">ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $csr as $val)
                                        <tr>
                                            <td>
                                                {{$val->name}}
                                                @if($val->slug)
                                                <br>
                                                ({{$val->slug}})
                                                @endif
                                            </td>
                                            <td>{{$val->email}}</td>
                                            <td>{{ get_role($val->role,'name')}}</td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $val->flag_count > 1 ? $val->flag_count.' Flags' : $val->flag_count.' Flag' }}  <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </span>
                                            </td>
                                            <td>
                                                @if($val->flag_count > 0)
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#removeFlag" title="Remove Flag" onclick="removeFlag({{$val->id}})">
                                                      <i class="fa fa-universal-access" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#flagRed" onclick="redFlag({{$val->id}})" title="Red Flag">
                                                    <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="Feedback_And_Review" class="tabcontent">
                                <table id="example5" class="table table-bordered table-striped key-buttons"  style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">NAME</th>
                                        <th class="border-bottom-0">EMAIL</th>
                                        <th class="border-bottom-0">ROLE</th>
                                        <th class="border-bottom-0">NO OF FLAGS</th>
                                        <th class="border-bottom-0">ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $feedback_and_review as $val)
                                        <tr>
                                            <td>
                                                {{$val->name}}
                                                @if($val->slug)
                                                <br>
                                                ({{$val->slug}})
                                                @endif
                                            </td>
                                            <td>{{$val->email}}</td>
                                            <td>{{ get_role($val->role,'name')}}</td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $val->flag_count > 1 ? $val->flag_count.' Flags' : $val->flag_count.' Flag' }}  <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </span>
                                            </td>
                                            <td>
                                                @if($val->flag_count > 0)
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#removeFlag" title="Remove Flag" onclick="removeFlag({{$val->id}})">
                                                      <i class="fa fa-universal-access" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#flagRed" onclick="redFlag({{$val->id}})" title="Red Flag">
                                                    <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="Trust_And_Safety" class="tabcontent">
                                <table id="example5" class="table table-bordered table-striped key-buttons"  style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">NAME</th>
                                        <th class="border-bottom-0">EMAIL</th>
                                        <th class="border-bottom-0">ROLE</th>
                                        <th class="border-bottom-0">NO OF FLAGS</th>
                                        <th class="border-bottom-0">ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $trust_and_safety as $val)
                                        <tr>
                                            <td>
                                                {{$val->name}}
                                                @if($val->slug)
                                                <br>
                                                ({{$val->slug}})
                                                @endif
                                            </td>
                                            <td>{{$val->email}}</td>
                                            <td>{{ get_role($val->role,'name')}}</td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $val->flag_count > 1 ? $val->flag_count.' Flags' : $val->flag_count.' Flag' }}  <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </span>
                                            </td>
                                            <td>
                                                @if($val->flag_count > 0)
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#removeFlag" title="Remove Flag" onclick="removeFlag({{$val->id}})">
                                                      <i class="fa fa-universal-access" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#flagRed" onclick="redFlag({{$val->id}})" title="Red Flag">
                                                    <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="QA" class="tabcontent">
                                <table id="example5" class="table table-bordered table-striped key-buttons"  style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">NAME</th>
                                        <th class="border-bottom-0">EMAIL</th>
                                        <th class="border-bottom-0">ROLE</th>
                                        <th class="border-bottom-0">NO OF FLAGS</th>
                                        <th class="border-bottom-0">ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $qa as $val)
                                        <tr>
                                            <td>
                                                {{$val->name}}
                                                @if($val->slug)
                                                <br>
                                                ({{$val->slug}})
                                                @endif
                                            </td>
                                            <td>{{$val->email}}</td>
                                            <td>{{ get_role($val->role,'name')}}</td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $val->flag_count > 1 ? $val->flag_count.' Flags' : $val->flag_count.' Flag' }}  <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </span>
                                            </td>
                                            <td>
                                                @if($val->flag_count > 0)
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#removeFlag" title="Remove Flag" onclick="removeFlag({{$val->id}})">
                                                      <i class="fa fa-universal-access" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#flagRed" onclick="redFlag({{$val->id}})" title="Red Flag">
                                                    <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="Manager" class="tabcontent">
                                <table id="example5" class="table table-bordered table-striped key-buttons"  style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">NAME</th>
                                        <th class="border-bottom-0">EMAIL</th>
                                        <th class="border-bottom-0">ROLE</th>
                                        <th class="border-bottom-0">NO OF FLAGS</th>
                                        <th class="border-bottom-0">ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $manager as $val)
                                        <tr>
                                            <td>
                                                {{$val->name}}
                                                @if($val->slug)
                                                <br>
                                                ({{$val->slug}})
                                                @endif
                                            </td>
                                            <td>{{$val->email}}</td>
                                            <td>{{ get_role($val->role,'name')}}</td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $val->flag_count > 1 ? $val->flag_count.' Flags' : $val->flag_count.' Flag' }}  <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </span>
                                            </td>
                                            <td>
                                                @if($val->flag_count > 0)
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#removeFlag" title="Remove Flag" onclick="removeFlag({{$val->id}})">
                                                      <i class="fa fa-universal-access" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#flagRed" onclick="redFlag({{$val->id}})" title="Red Flag">
                                                    <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div id="Deleted" class="tabcontent">
                                <table id="example6" class="table table-bordered table-striped key-buttons"  style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">NAME</th>
                                        <th class="border-bottom-0">EMAIL</th>
                                        <th class="border-bottom-0">ROLE</th>
                                        <th class="border-bottom-0">NO OF FLAGS</th>
                                        <th class="border-bottom-0">ACTION</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $deleted as $val)
                                        <tr>
                                            <td>
                                                {{$val->name}}
                                                @if($val->slug)
                                                <br>
                                                ({{$val->slug}})
                                                @endif
                                            </td>
                                            <td>{{$val->email}}</td>
                                            <td>{{ get_role($val->role,'name')}}</td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ $val->flag_count > 1 ? $val->flag_count.' Flags' : $val->flag_count.' Flag' }}  <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </span>
                                            </td>
                                            <td>
                                                @if($val->flag_count > 0)
                                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#removeFlag" title="Remove Flag" onclick="removeFlag({{$val->id}})">
                                                      <i class="fa fa-universal-access" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#flagRed" onclick="redFlag({{$val->id}})" title="Red Flag">
                                                    <i class="fa fa-flag-o" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/div-->
        </div>
    </div>
    <!-- /Row -->
    
    <div class="modal fade" id="removeFlag" tabindex="-1" role="dialog" aria-labelledby="removeFlagTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="activeAccountLongTitle">Do you really want to remove flag from this user?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('remove_employee_flag')}}" method="POST">
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" id="userId3" name="id" />
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="flagRed" tabindex="-1" role="dialog" aria-labelledby="flagRedTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="flagRedLongTitle">Do you really want to give red flag to this user?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('add_employee_flag')}}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="userId4" name="id" />
                        <label>Reason</label>
                        <textarea class="form-control" name="reason" id="reason" placeholder="Write the reason"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
    <script>
        $(document).ready(function() {
            $('#example6').DataTable();
            $('#example5').DataTable();
            $('#example4').DataTable();
            $('#example3').DataTable();
        });
        document.getElementById("defaultOpen").click();
        function openCity(evt, cityName) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }
        
        
        function removeFlag(id)
        {
            $("#userId3").val(id);
        }
        
        function redFlag(id)
        {
            $("#userId4").val(id);
        }
    </script>
@endsection

