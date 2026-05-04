@extends('layouts.innerpages')
@section('template_title')
    Freeze User History
@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        /* Style the tab */
        .table-responsive {
            overflow: unset !important;
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
            animation: fadeEffect 1s;
            /* Fading effect takes 1 second */
        }

        .dropdown-menu {
            left: -6rem !important;
        }

        .bg-yellow {
            background-color: #c3c300 !important;
        }

        .bg-orange {
            background-color: #F49917 !important;
        }

        .bg-pink {
            background: #E91E63 !important;
        }

        .bg-amber {
            background: #FF6F00 !important;
        }

        .bg-teal {
            background: #004D40 !important;
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

        th,
        td {
            text-align: center;
            vertical-align: middle !important;
        }

        .btn-outline-warning i,
        .btn-outline-warning {
            color: #ffab00 !important;
            color: #ffab00 !important;
        }

        .btn-outline-warning:hover i {
            color: #fff !important;
        }
    </style>
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            @if (session('flash_message'))
                <div class="alert alert-success">
                    {{ session('flash_message') }}
                </div>
            @endif
            <!--div-->
            <div class="page-header">
                <div class="text-secondary text-center text-uppercase w-100">
                    <h1 class="my-4"><b>Freeze User History</b></h1>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-12 mt-3">
                            <h1>Freeze User History</h1>
                        </div>
                        {{-- <div class="col-md-3">
                            <label for="search" class="form-label">Search Name</label>
                            <input type="text" id="search" placeholder="Search Name" class="form-control" />
                        </div> --}}
                        <div class="col-md-4">
                            <label for="role" class="form-label">Select Role</label>
                            <select id="role" class="form-control" onchange="filterUsersByRole()">
                                <option value="">Select Role</option>
                                @foreach ($role as $roleItem)
                                    <option value="{{ $roleItem->id }}">{{ $roleItem->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="user" class="form-label">Select User</label>
                            <select id="search" class="form-control">
                                <option value="">Select User</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Daterange <button type="button" class="btn btn-info btn-sm"
                                    onclick="$('#date_range').val('')" style="padding: 3.2px 10px;">Clear</button></label>
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' name="date_range" id="date_range" class="form-control" />
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
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive" id="searchData">
                            @include('main.auth.search3')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('extraScript')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(function() {
            var date = new Date();
            $('#date_range').daterangepicker({
                "showDropdowns": true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                },
                "alwaysShowCalendars": true,
                "opens": "center",
                "drops": "auto"
            }, function(start, end, label) {
                $('#date_range').val("");
            });
            $('#date_range').val("");
        });

        $("body").delegate(".cancelBtn", "click", function() {
            $('#date_range').val('');
        });

        $(document).ready(function() {

            $(document).on('click', '.pagination a', function(event) {

                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                var search = $("#search").val();
                var date_range = $("#date_range").val();
                fetch_data3(page, search, date_range);

            });

            function fetch_data3(page, search, date_range) {

                $('#searchData').html('');
                $('#searchData').append(`<div class="lds-hourglass" id='ldss'></div>`);

                $.ajax({

                    url: "freeze_user?page=" + page,
                    type: "GET",
                    data: {
                        search: search,
                        date_range: date_range
                    },
                    dataType: "html",
                    success: function(data) {
                        $('#searchData').html('');
                        $('#searchData').html(data);

                    },
                    complete: function(data) {}

                })

            }

            $("#search").on('change', function(e) {
                var date_range = $("#date_range").val();
                fetch_data3(1, $(this).val(), date_range);
            })

            $("#date_range").change(function() {
                var search = $("#search").val();
                fetch_data3(1, search, $(this).val());
            })

        });
    </script>
    <script>
        function filterUsersByRole() {
            var roleId = $('#role').val();
            $('#search').empty().append('<option value="">Select User</option>'); // Clear previous options

            if (roleId) {
                $.ajax({
                    url: "{{ url('get-users-by-role') }}", // Adjust the URL as necessary
                    type: 'GET',
                    data: {
                        role_id: roleId
                    },
                    success: function(data) {
                        // Assuming data is an array of users
                        $.each(data, function(index, user) {
                            $('#search').append(`<option value="${user.name}">${user.name}</option>`);
                        });
                    }
                });
            }
        }
    </script>
@endsection
