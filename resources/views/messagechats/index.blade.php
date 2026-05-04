@extends('layouts.innerpages')

@section('template_title')
    Message Chats
@endsection

@include('partials.mainsite_pages.return_function')

@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!--=================================multiselect tag============================== -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
    <!--=================================multiselect tag============================== -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


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
    </style>

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
                    <div class="filter mt-4 mb-4">
                        <form method="post" action="{{ route('store.autos.approach') }}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-3">
                                    <label style="float: left">User</label>
                                    <div class='input-group'>
                                        <select name="user" class="form-control" id="user" required>
                                            <option selected value="">Select</option>
                                            {{-- dd($users) --}}
                                            @foreach ($users as $key => $val)
                                                <option value="{{ $val->user_id }}">
                                                    {{ $val->user->name . ' ' . $val->user->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label style="float: left">Order</label>
                                    <div class='input-group'>
                                        {{-- <select name="order" class="form-control" id="order" required>
                                            <option selected value="">Select</option>
                                            @foreach ($orders as $key => $val)
                                                <option value="{{ $val->order_id }}">
                                                    {{ $val->order->id . ' - ' . $val->order->oname }}
                                                </option>
                                            @endforeach
                                        </select> --}}
                                        <input type="number" class="form-control" id="order" name="order" value="">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label style="float: left">Daterange</label>
                                    <div class='input-group'>


                                        <div class="row">

                                            <!--<label style="float: left">Daterange</label>-->
                                            <div class='input-group date' id='datetimepicker1'>
                                                <input type='text' name="date_range" id="date_range"
                                                    class="form-control" />
                                                <span class="input-group-addon"
                                                    style="
                                                border: 1px solid #ddd;
                                                border-left-color: transparent;
                                                border-radius: 0;
                                                position: relative;
                                                left: -1px;
                                                display: flex;
                                                align-items: center;
                                            ">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Tab content -->
                    <div class="">
                        <div class="d-flex justify-content-between">
                            <h3>Message Chats</h3>
                            <a href="{{ route('messagechats.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>
                                Add New</a>
                        </div>
                        <br>
                        <div id="table_data">
                            @include('messagechats.table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this message chat?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Handle delete button click
            $('.deleteBtn').on('click', function() {
                var action = $(this).data('action');
                $('#deleteForm').attr('action', action);
            });
    
            // Function to fetch filtered data
            function fetchData() {
                var userData = $('#user').val();
                var orderData = $('#order').val();
                var dateRangeData = $('#date_range').val();
    
                console.log(userData, orderData, dateRangeData);
                $.ajax({
                    url: "{{ route('fetch.messagechat.results') }}",
                    type: "GET",
                    data: {
                        user: userData,
                        order: orderData,
                        date_range: dateRangeData
                    },
                    success: function(response) {
                        $('#table_data').html(response);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }
    
            $('#user, #date_range').change(function() {
                fetchData();
            });
            
            $('#order').on('input', function() {
                fetchData();
            });
    
            // Initialize date range picker
            $('#date_range').daterangepicker({
                "showDropdowns": true,
                "opens": "center",
                "drops": "auto",
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function(start, end, label) {
                // Callback function when date range changes
                fetchData();
            });
    
            // Initial data fetch
            fetchData();
        });
    </script>

@endsection
