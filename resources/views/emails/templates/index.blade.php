@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim('Group Chats', '/')) }}
@endsection
@section('content')

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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

        .table-bordered,
        .text-wrap table,
        .table-bordered th,
        .text-wrap table th,
        .table-bordered td,
        .text-wrap table td {
            border: 1px solid rgb(0 0 0);
        }

        .table>tbody>tr>td,
        .table>thead>tr>th {
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
            animation: fadeEffect 1s;
            /* Fading effect takes 1 second */
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

        .ms-drop ul>li.ms-select-all {
            width: 100%;
        }

        .ms-drop ul>li.text-capitalize {
            width: 50%;
            text-align: left;
        }

        .ms-drop ul {
            display: flex;
            flex-wrap: wrap;
        }

        .btn-ancor {
            display: flex;
            justify-content: center;
            align-items: baseline;
        }

        #table_data td a.btn {
            width: 20%;
        }
        #table_data td a.btn {
     width: 40%;
       }
    </style>
    <!--/app header--> <!--Page header-->
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <input type="hidden" value="{{ trim("$_SERVER[REQUEST_URI]", '/') }}" id="titlee">-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Groups</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Email Templates</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            @if (session('flash_message'))
                <div class="alert alert-success">
                    {{ session('flash_message') }}
                </div>
            @endif
            @if ($errors->any())
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
                            <h3>Email Templates</h3>
                            <a href="{{ route('email-templates.create') }}" class="btn btn-primary">Create Email
                                Template</a>
                        </div>
                        <br>
                        <div id="table_data">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" style="width:100%" id=""
                                    role="grid">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Banner</th>
                                            <th>Status</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($emailTemplates as $template)
                                            <tr>
                                                <td>{{ $template->title }}</td>
                                                <td>{{ $template->description }}</td>
                                                <td>
                                                    @if ($template->banner)
                                                        <img src="{{ asset($template->banner) }}" alt="Banner"
                                                            style="max-width: 100px;">
                                                    @else
                                                        No Banner
                                                    @endif
                                                </td>
                                                <td>{{ $template->status }}</td>
                                                <td>{{ ($template->type == 1) ? 'Other' : 'Approaching Screen' }}</td>
                                                <td>
                                                    <div class="btn-ancor">


                                                        <a href="{{ route('email-templates.edit', $template->id) }}"
                                                            class="btn btn-primary"><i class="fa fa-edit"></i></a>

                                                        <form
                                                            action="{{ route('email-templates.destroy', $template->id) }}"
                                                            method="post" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger"
                                                                onclick="return confirm('Are you sure?')"><i
                                                                    class="fa fa-trash"></i></button>
                                                        </form>
                                                        
                                                        <a href="{{ route('custom.email-send', $template->id) }}"
                                                            class="btn btn-primary">Mail</a>
                                                    </div>
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
        </div>
    </div>
@endsection
