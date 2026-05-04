@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim('All Order Takers', '/')) }}
@endsection

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha384-..." crossorigin="anonymous">
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
            vertical-align: middle;
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
            color: #009eda !important;
            cursor: pointer;
        }

        .popoverContent {
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

        .table {
            table-layout: fixed;
            /* Ensures a fixed layout */
            width: 100%;
            /* Makes the table occupy the full width */
        }

        .table th,
        .table td {
            word-wrap: break-word;
            /* Allows long words to wrap to the next line */
            overflow: hidden;
            /* Prevents overflow */
            white-space: normal;
            /* Normal white space handling */
        }
    </style>

    <!-- Page header -->
    <div class="page-header">
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>All Order Takers</b></h1>
        </div>
    </div>

    <!-- End Page header -->
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

            <!-- Card -->
            <div class="card">
                <div class="card-header">
                    <div class="container-fluid">
                        <div class="d-flex justify-content-between">
                            <h3 class="my-auto">All Order Takers</h3>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Table -->
                    <!-- Filter Form -->
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="nameFilter">Name</label>
                                <select id="nameFilter" class="form-control">
                                    <option value="">Select Name</option>
                                    @foreach ($names as $name)
                                        <option value="{{ $name }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="monthFilter">Month</label>
                                <select id="monthFilter" class="form-control">
                                    <option value="">Select Month</option>
                                    @foreach ($months as $month)
                                        <option value="{{ $month }}">{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <a class="btn btn-success" href="#" data-toggle="modal" data-target="#addModal"><i
                                        class="fa fa-add"></i> Add New</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" style="width:100%" role="grid">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">Name</th>
                                    <th class="border-bottom-0">Email</th>
                                    <th class="border-bottom-0">Revenue</th>
                                    <th class="border-bottom-0">Delivered Order</th>
                                    <th class="border-bottom-0">No. of Cancellation</th>
                                    <th class="border-bottom-0">Cancellation Deduction</th>
                                    <th class="border-bottom-0">Total Booked Order</th>
                                    <th class="border-bottom-0">Month</th>
                                    <th class="border-bottom-0">Review</th>
                                    <th class="border-bottom-0">Achieved/Not Achieved</th>
                                    <th class="border-bottom-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $val)
                                    <tr>
                                        {{-- {{ dd($data->toArray()) }} --}}
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $val->user ? $val->user->name : 'N/A' }}</td>
                                        <td>{{ $val->user ? $val->user->email : 'N/A' }}</td>
                                        <td>{{ number_format($val->revenue) }}</td>
                                        <td>{{ number_format($val->delivered_order) }}</td>
                                        <td>{{ number_format($val->cancellation) }}</td>
                                        <td>{{ number_format($val->cancellation_deduction) }}</td>
                                        <td>{{ number_format($val->total_booked_order) }}</td>
                                        <td>{{ $val->month }}</td>
                                        <td>
                                            @if ($val->review_less_than == 1)
                                                Achieved
                                            @else
                                                Not Achieved
                                            @endif
                                        </td>
                                        <td>{{ $val->review_target_achieved }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <input type="hidden" class="id" value="{{ $val->id }}">
                                                <a class="btn btn-success editMile" href="#" data-toggle="modal"
                                                    data-target="#editModal"><i class="fa fa-edit"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- <div class="d-flex justify-content-between">
                            <div class="text-secondary my-auto">
                                Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} from total
                                {{ $data->total() }} entries
                            </div>
                            <div>
                                {{ $data->links() }}
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end app-content -->

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addForm" class="addForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="user_id">Select Ordertaker</label>
                            <select class="form-control" id="user_id" name="user_id">
                                @foreach ($names as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="revenue">Revenue</label>
                            <input type="text" class="form-control" id="revenue" name="revenue">
                        </div>
                        <div class="form-group">
                            <label for="delivered_order">Delivered Orders</label>
                            <input type="text" class="form-control" id="delivered_order" name="delivered_order">
                        </div>
                        <div class="form-group">
                            <label for="cancellation">No. of Cancellation</label>
                            <input type="text" class="form-control" id="cancellation" name="cancellation">
                        </div>
                        <div class="form-group">
                            <label for="cancellation_deduction">Cancellation Deduction</label>
                            <input type="text" class="form-control" id="cancellation_deduction"
                                name="cancellation_deduction">
                        </div>
                        <div class="form-group">
                            <label for="total_booked_order">Total Booked Orders</label>
                            <input type="text" class="form-control" id="total_booked_order"
                                name="total_booked_order">
                        </div>
                        <div class="form-group">
                            <label for="month">Month</label>
                            <input type="month" class="form-control" id="month" name="month">
                        </div>
                        <div class="form-group">
                            <label for="review_less_than">Review</label>
                            <select class="form-control" id="review_less_than" name="review_less_than" required>
                                <option value="" selected>Select</option>
                                <option value="0">Not Achieved</option>
                                <option value="1">Achieved</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="review_target_achieved">Review Target Achieved/ Not Achieved</label>
                            <input type="text" class="form-control" id="review_target_achieved"
                                name="review_target_achieved">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Add Modal -->

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm" class="editForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="user_id" name="user_id">
                        <input type="hidden" id="revenue_id" name="revenue_id">
                        <div class="form-group">
                            <label for="revenue">Revenue</label>
                            <input type="text" class="form-control" id="edit_revenue" name="revenue">
                        </div>
                        <div class="form-group">
                            <label for="edit_delivered_order">Delivered Orders</label>
                            <input type="text" class="form-control" id="edit_delivered_order" name="delivered_order">
                        </div>
                        <div class="form-group">
                            <label for="edit_cancellation">No. of Cancellation</label>
                            <input type="text" class="form-control" id="edit_cancellation" name="cancellation">
                        </div>
                        <div class="form-group">
                            <label for="edit_cancellation_deduction">Cancellation Deduction</label>
                            <input type="text" class="form-control" id="edit_cancellation_deduction"
                                name="cancellation_deduction">
                        </div>
                        <div class="form-group">
                            <label for="edit_total_booked_order">Total Booked Orders</label>
                            <input type="text" class="form-control" id="edit_total_booked_order"
                                name="total_booked_order">
                        </div>
                        <div class="form-group">
                            <label for="edit_month">Month</label>
                            <input type="month" class="form-control" readonly id="edit_month" name="month">
                        </div>
                        <div class="form-group">
                            <label for="review_less_than">Review</label>
                            <select class="form-control" id="edit_review_less_than" name="review_less_than" required>
                                <option value="" selected>Select</option>
                                <option value="0">Not Achieved</option>
                                <option value="1">Achieved</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_review_target_achieved">Review Target Achieved/ Not Achieved</label>
                            <input type="text" class="form-control" id="edit_review_target_achieved"
                                name="review_target_achieved">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Edit Modal -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            // Add Employee AJAX
            $('#addForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('update_employee_revenue') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            });

            // Edit Employee AJAX
            $('.editMile').on('click', function() {
                var id = $(this).closest('tr').find('.id').val();
                $.ajax({
                    url: '{{ route('get_employee') }}',
                    method: 'GET',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        $('#revenue_id').val(response.id);
                        $('#edit_revenue').val(response.revenue);
                        $('#edit_cancellation').val(response.cancellation);
                        $('#edit_cancellation_deduction').val(response.cancellation_deduction);
                        $('#edit_delivered_order').val(response.delivered_order);
                        $('#edit_total_booked_order').val(response.total_booked_order);
                        $('#edit_month').val(response.month);
                        $('#edit_review_less_than').val(response.review_less_than);
                        $('#edit_review_target_achieved').val(response.review_target_achieved);
                        $('#editModal').modal('show');
                    }
                });
            });

            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route('update_employee_revenue') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('.table').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                columnDefs: [{
                    targets: '_all',
                    searchable: true
                }]
            });

            $('#nameFilter').on('change', function() {
                var name = $(this).val();
                table.column(1).search(name).draw();
            });

            $('#monthFilter').on('change', function() {
                var month = $(this).val();
                table.column(7).search(month).draw();
            });
        });
    </script>
@endsection
