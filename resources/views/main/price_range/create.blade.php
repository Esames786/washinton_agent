@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim('Add Price Range','/'))}}
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
            <h1 class="my-4"><b>Add Price Range</b></h1>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-12">
        <!--div-->
            <div class="card">
                <div class="card-header">
                    Add New Price Range
                </div>
                <form action="{{ route('store.priceRange') }}" method="POST">
                    <div class="card-body">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Min Price</label>
                            <input type="number" min="0" class="form-control" name="min_price" placeholder="Enter Min Price" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">Max Price</label>
                            <input type="number" min="0" class="form-control" name="max_price" placeholder="Enter Max Price" />
                        </div>
                        <div class="form-group">
                            <label class="form-label">AddOn Price</label>
                            <input type="number" min="0" class="form-control" name="addon_price" placeholder="Enter AddOn Price" />
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
@endsection


