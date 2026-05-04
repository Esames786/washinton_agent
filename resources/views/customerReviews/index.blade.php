@extends('layouts.innerpages')

@section('template_title')
    Customer Reviews
@endsection

@section('content')
    @include('partials.mainsite_pages.return_function')

    <style>
        #reviewsTable {
            table-layout: fixed;
            width: 100%;
        }

        #reviewsTable th,
        #reviewsTable td {
            white-space: normal;
            word-wrap: break-word;
        }
    </style>

    <div class="page-header">
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Customer Reviews List</b></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card mt-5">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="clientRatingFilter">Client Rating:</label>
                            <select id="clientRatingFilter" class="form-control">
                                <option value="">All</option>
                                <option value="Positive">Positive</option>
                                <option value="Neutral">Neutral</option>
                                <option value="Negative">Negative</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="websiteFilter">Website:</label>
                            <select id="websiteFilter" class="form-control">
                                <option value="">All</option>
                                <option value="BBB">BBB</option>
                                <option value="Trust Pilot">Trust Pilot</option>
                                <option value="Google">Google</option>
                                <option value="Yelp">Yelp</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="userFilter">User:</label>
                            <select id="userFilter" class="form-control">
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label style="float: left">Daterange</label>
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' name="date_range" id="date_range" class="form-control" />
                                <span class="input-group-addon"
                                    style="border: 1px solid #ddd; border-left-color: transparent; border-radius: 0; position: relative; left: -1px; display: flex; align-items: center;">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                <button type="button" id="clearDateRange" class="btn btn-secondary">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        @include('customerReviews.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
    <script>
        $('#reviewsTable').DataTable({
            pageLength: 50,
            lengthMenu: [10, 25, 50, 100],
            order: [
                [0, 'asc']
            ],
        });

        $(function() {
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
                "autoUpdateInput": false, // Disable auto-filling the input with a date range on load
                "opens": "center",
                "drops": "auto"
            }, function(start, end) {
                $('#date_range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                fetchAgentReport(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            });

            // When selecting a date range, update the input
            $('#date_range').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });

            // Clear the date range and reset input
            $('#clearDateRange').on('click', function() {
                $('#date_range').val(''); // Clear the input field
                $('#date_range').data('daterangepicker').setStartDate(moment().startOf(
                'month')); // Reset datepicker start date
                $('#date_range').data('daterangepicker').setEndDate(moment().endOf(
                'month')); // Reset datepicker end date
                fetchAgentReport('', ''); // Send empty date range to the server
            });

            $('#clientRatingFilter, #websiteFilter, #userFilter').on('change', function() {
                var dateRange = $('#date_range').val().split(' - ');
                var startDate = dateRange.length === 2 ? dateRange[0] : '';
                var endDate = dateRange.length === 2 ? dateRange[1] : '';
                fetchAgentReport(startDate, endDate);
            });
        });

        // Function to fetch data based on filters
        function fetchAgentReport(startDate, endDate) {
            var clientRating = $('#clientRatingFilter').val();
            var website = $('#websiteFilter').val();
            var user = $('#userFilter').val();

            $.ajax({
                url: '{{ route('customer.reviews') }}',
                type: 'GET',
                data: {
                    start_date: startDate || null, // Pass null if startDate is empty
                    end_date: endDate || null, // Pass null if endDate is empty
                    clientRatingFilter: clientRating,
                    websiteFilter: website,
                    userFilter: user,
                },
                success: function(response) {
                    console.log('responseresponse', response);
                    $('.table-responsive').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function fetchAgentReport(startDate, endDate) {
            var clientRating = $('#clientRatingFilter').val();
            var website = $('#websiteFilter').val();
            var user = $('#userFilter').val();
            $.ajax({
                url: '{{ route('customer.reviews') }}',
                type: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    clientRatingFilter: clientRating,
                    websiteFilter: website,
                    userFilter: user,
                },
                success: function(response) {
                    console.log('responseresponse', response);
                    $('.table-responsive').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
@endsection
