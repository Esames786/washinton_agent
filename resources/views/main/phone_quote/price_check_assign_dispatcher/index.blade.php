@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim("$_SERVER[REQUEST_URI]", '/')) }}
@endsection

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!--=================================multiselect tag============================== -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
    <!--=================================multiselect tag============================== -->
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
            /*color: rgb(0 0 0);*/
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }

        .table>thead>tr>td,
        .table>thead>tr>th {
            font-weight: 400;
            -webkit-transition: all .3s ease;
            font-size: 18px;
            color: rgb(0 0 0);
        }

        .table-data-align {
            display: flex;
            align-items: flex-end;
        }

        .tx-white {
            color: white !important;
        }

        .badge-orange {
            color: #212529;
            background-color: #F49917;
        }

        .bg-white th {
            border: 1px solid #000000 !important;
        }

        .bg-white td {
            border: 1px solid #000000 !important;
        }

        .choices__inner {
            height: 50px;
            overflow-y: scroll;
            border: 1px solid #86c8ff;
        }

        .remaining {
            color: red;
        }
    </style>
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <input type="hidden" value="{{ trim("$_SERVER[REQUEST_URI]", '/') }}" id="titlee">
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">New Order</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Price Checker Dispatcher Assign</b></h1>
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
            <!--div-->
            <div class="card">
                <div class="card-body">
                    <div id="table_data">

                        @include('main.phone_quote.price_check_assign_dispatcher.table')
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end app-content-->

@endsection

@section('extraScript')

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        (function($){
            // AJAX paginate: load table into container
            $(document).on('click', '.tableResponsiveNew .pagination a', function(e){
                e.preventDefault();
                const url = $(this).attr('href');
                $.get(url, { ajax: 1 }, function(html){
                    $('.tableResponsiveNew').html(html);
                });
            });

            // Toggle allow/deny
            $(document).on('click', '.js-toggle-allow', function(){
                const $btn = $(this);
                const url = $btn.data('url');
                const nextVal = $btn.data('current') ? 0 : 1;

                $btn.prop('disabled', true);

                $.ajax({
                    url: url,
                    type: 'PATCH',
                    data: {
                        value: nextVal,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(resp){
                        if(resp.ok){
                            // update button state & text
                            $btn.data('current', resp.value);
                            $btn.toggleClass('btn-success btn-danger');
                            $btn.text(resp.value ? 'Disable' : 'Enable');

                            // update status badge in same row
                            const $row = $btn.closest('tr');
                            const $statusCell = $row.find('.js-status');
                            if(resp.value){
                                $statusCell.html('<span class="badge badge-success">Allowed</span>');
                            } else {
                                $statusCell.html('<span class="badge badge-danger">Blocked</span>');
                            }
                        }
                    },
                    complete: function(){
                        $btn.prop('disabled', false);
                    },
                    error: function(){
                        alert('Failed to update. Please try again.');
                    }
                });
            });
        })(jQuery);
    </script>





@endsection
