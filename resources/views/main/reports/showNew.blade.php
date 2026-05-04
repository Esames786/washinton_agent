@extends('layouts.innerpages')
@section('template_title')
    Employee Reports
@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .col-sm-4 .card {
            transition: all .2s;
            cursor: pointer;
        }

        .col-sm-4 .card:hover {
            box-shadow: 0 0px 30px 0 rgb(35 43 54 / 15%);
            scale: 1.02;
        }

        .col-sm-4 .card .card-header {
            font-weight: 700;
        }

        .col-sm-4 .card .card-header span {
            font-size: 15px;
            color: #fff;
        }

        .table-responsive {
            overflow: unset !important;
        }

        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
        }

        .tab button:hover {
            background-color: #ddd;
        }

        .tab button.active {
            background-color: #ccc;
        }

        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }

        .tabcontent {
            animation: fadeEffect 1s;
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

        .showQuotes {
            border: 2.5px solid #5b7fff;
            border-radius: 8px;
        }

        .badge-custom {
            background-color: #5b7fff;
        }

        @keyframes fadeEffect {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .bounce {
            animation: bounce 0.5s infinite;
        }
    </style>

    <div class="row">
        <div class="col-12">
            @if (session('flash_message'))
                <div class="alert alert-success">
                    {{ session('flash_message') }}
                </div>
            @endif
            <div class="page-header">
                <div class="text-secondary text-center text-uppercase w-100">
                    <h1 class="my-4"><b>Daily Report</b></h1>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header d-block">
                    <div class="row">
                        <div class="col-sm-3 my-auto">
                            <label class="form-label">Search By</label>
                            <select class="form-control select2" id="search_by">
                                <option value="created_at">Move Created Date</option>
                                <option value="updated_at">Move Modified Date</option>
                            </select>
                        </div>
                        <div class="col-sm-3 my-auto">
                            <label class="form-label">Select Call Type</label>
                            <select name="call_type" class="form-control" id="call_type2">
                                <option value="">All</option>
                                <option value="In Bound">In Bound</option>
                                <option value="Out Bound">Out Bound</option>
                            </select>
                        </div>
                        <div class="col-sm-3 my-auto">
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
                        <div class="col-sm-3 my-auto">
                            <label class="form-label">Deparment Employees</label>
                            <select class="form-control select2" id="user">
                                <option value="" selected>All</option>
                                @foreach ($users as $key => $val)
                                    <option value="{{ $val->id }}" style="text-transform:capitalize;">
                                        {{ $val->name }} ({{ $val->userRole->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-1 mt-auto mb-1">
                            <button class="btn btn-primary" id="submit">Search</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="pstatus" value="" />
                <div class="card-body" id="completeData">
                    @include('main.reports.show2New')
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width:85%;">
            <div class="modal-content">
                <div class="d-flex justify-content-between mt-5">
                    <h5 class="text-center my-auto ml-4" id="order_detail_status">Order Status</h5>
                    <h5 class="text-center my-auto" id="order_detail_title">Order Detail</h5>
                    <button type="button" class="close bg-danger text-white mr-4" data-dismiss="modal"
                        aria-label="Close" style="height: 25px;width: 25px;border-radius: 50%;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detail_order">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        function searchData(page) {
            var user = $("#user").children('option:selected').val();
            var search_by2 = $("#search_by").children('option:selected').val();
            var call_type = $("#call_type2").children('option:selected').val();
            var date_range = $("#date_range").val();
            $("#pstatus").val('');
            var pstatus = '';
            $.ajax({
                url: "{{ url('/reports/getNew') }}?page=" + page,
                type: "GET",
                data: {
                    date_range: date_range,
                    user: user,
                    pstatus: pstatus,
                    search_by2: search_by2,
                    call_type: call_type
                },
                beforeSend: function() {
                    $('#completeData').html("");
                    $('#completeData').append(`<div class="lds-hourglass" id='ldss'></div>`);
                },
                success: function(res) {
                    $("#completeData").html("");
                    $("#completeData").html(res);
                }
            });
        }
        $(document).on("change", "#searchBy", "#source", function() {
            if ($(this).val() == "dauction") {
                $("#search").val('Port');
                $("#search").attr("readonly", true);
            } else if ($(this).val() == "ophone") {

                $('#search').val('');
                $("#search").attr("readonly", false);
                $("#search").mask("(999) 999-9999");

                setTimeout(function() {
                    $('input[name="search"]').focus()
                }, 100);

            } else {
                $("#search").val('');
                $("#search").attr("readonly", false);
            }
        })

        function searchData2(page) {
            var user = $("#user").children('option:selected').val();
            var search_by2 = $("#search_by").children('option:selected').val();
            var call_type = $("#call_type2").children('option:selected').val();
            var date_range = $("#date_range").val();
            var date_rangeNew = $("#date_rangeNew").val();
            var pstatus = $("#pstatus").val();
            var search_by = $("#searchBy").children('option:selected').val();
            var source = $("#source").children('option:selected').val();
            var search = $("#search").val();
            var auc_storage = $("#auc_storage").children('option:selected').val();
            $.ajax({
                url: "{{ url('/reports/get2New') }}?page=" + page,
                type: "GET",
                data: {
                    date_range: date_range,
                    date_rangeNew: date_rangeNew,
                    user: user,
                    pstatus: pstatus,
                    search_by: search_by,
                    search: search,
                    auc_storage: auc_storage,
                    search_by2: search_by2,
                    call_type: call_type,
                    source: source
                },
                beforeSend: function() {
                    $('#tableData').html("");
                    $('#tableData').append(`<div class="lds-hourglass" id='ldss'></div>`);
                },
                success: function(res) {
                    $("#tableData").html("");
                    if (pstatus === 'reviews' || pstatus === 'new_customer' || pstatus === 'qa_positive' ||
                        pstatus === 'qa_negative') {
                        $("#filters").hide();
                    } else {
                        $("#filters").show();
                    }
                    $("#tableData").html(res);
                }
            });
        }
        $("#submit").click(function() {
            searchData(1);
        })
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
                $('#date_range').val("{{ $from->format('m/d/Y') }} - {{ $to->format('m/d/Y') }}");
            });
            $('#date_range').val("{{ $from->format('m/d/Y') }} - {{ $to->format('m/d/Y') }}");
            $('#date_rangeNew').daterangepicker({
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
                $('#date_rangeNew').val("{{ $from->format('m/d/Y') }} - {{ $to->format('m/d/Y') }}");
            });
            $('#date_rangeNew').val("{{ $from->format('m/d/Y') }} - {{ $to->format('m/d/Y') }}");
        });

        $("body").delegate(".cancelBtn", "click", function() {
            $('#date_range').val('');
        });


        $(document).on('click', '.pagination a', function(event) {

            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            searchData2(page);
        });

        $(document).on('click', '.showQuotes', function() {
            $(".showQuotes .badge").removeClass('bounce');
            $(this).find('.badge').addClass('bounce');
            $("#pstatus").val($(this).attr('data-value'));
            searchData2(1);
            $(this).attr('data-value') == "35" ? $("#auction_storage").show() : $("#auction_storage").hide();
        });


        $(document).on('click', '#searchValues', function() {
            searchData2(1);
        })
    </script>
@endsection
