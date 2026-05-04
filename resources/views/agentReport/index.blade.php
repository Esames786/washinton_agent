@extends('layouts.innerpages')

@section('template_title')
    Agents Today's Report
@endsection

@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    @include('partials.mainsite_pages.return_function')

    <div class="page-header">
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Agents Report List</b></h1>
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
                    <div class="col-lg-3">
                        <div class='input-group'>
                            <div class="row">
                                <label style="float: left">Daterange</label>
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
                    <div class="col-lg-3">
                        <div class='input-group'>
                            <div class="row">
                                <a href="{{ route('addAchieveTarget') }}" class="btn btn-primary" target="_blank"
                                    rel="">Add Target</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class='input-group'>
                            <div class="row">
                                <a href="{{ route('customer.reviews') }}" class="btn btn-primary" target="_blank"
                                    rel="">View Review</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        @if (count($data) >= 1)
                            @include('agentReport.table')
                        @endif
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
                    // 'Today': [moment(), moment()],
                    // 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    // 'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    // 'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    // 'This Month': [moment().startOf('month'), moment().endOf('month')],
                    // 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                    //     .subtract(1, 'month').endOf('month')
                    // ],
                    'January': [moment().month(0).startOf('month'), moment().month(0).endOf('month')],
                    'February': [moment().month(1).startOf('month'), moment().month(1).endOf('month')],
                    'March': [moment().month(2).startOf('month'), moment().month(2).endOf('month')],
                    'April': [moment().month(3).startOf('month'), moment().month(3).endOf('month')],
                    'May': [moment().month(4).startOf('month'), moment().month(4).endOf('month')],
                    'June': [moment().month(5).startOf('month'), moment().month(5).endOf('month')],
                    'July': [moment().month(6).startOf('month'), moment().month(6).endOf('month')],
                    'August': [moment().month(7).startOf('month'), moment().month(7).endOf('month')],
                    'September': [moment().month(8).startOf('month'), moment().month(8).endOf('month')],
                    'October': [moment().month(9).startOf('month'), moment().month(9).endOf('month')],
                    'November': [moment().month(10).startOf('month'), moment().month(10).endOf('month')],
                    'December': [moment().month(11).startOf('month'), moment().month(11).endOf('month')],
                },
                "alwaysShowCalendars": true,
                "startDate": new Date(date.getFullYear(), date.getMonth(), 1),
                "endDate": new Date(date.getFullYear(), date.getMonth() + 1, 0),
                "opens": "center",
                "drops": "auto"
            }, function(start, end, label) {
                $('#date_range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                $('#hidden_date_range').val(start.format('YYYY-MM-DD') + ' - ' + end.format(
                    'YYYY-MM-DD')); // Corrected line

                console.log($('#date_range').val(), $('#hidden_date_range').val());
                fetchAgentReport(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            });

            // Initial value for the date range input
            $('#date_range').val('');
        });

        // Function to fetch the updated agents report
        function fetchAgentReport(startDate, endDate) {
            $.ajax({
                url: '{{ route('agent.report') }}', // Change this to your route
                type: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(response) {
                    $('.table-responsive').html(response); // Update the table with the response
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    </script>
@endsection
