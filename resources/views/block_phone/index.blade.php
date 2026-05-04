@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim("$_SERVER[REQUEST_URI]", '/')) }}
@endsection

@section('content')
    @include('partials.mainsite_pages.return_function')
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
        <input type="hidden" value="{{ trim("$_SERVER[REQUEST_URI]", '/') }}" id="titlee">
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Blocked Phones</b></h1>
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
                @php
                    $ptype = 1;
                    $query = \App\user_setting::where('user_id', Auth::user()->id)->first();
                    if (!empty($query)) {
                        $ptype = $query['penal_type'];
                    }
                    $datas = \App\UsedAndNewCarDealers::select(
                        'state',
                        \DB::raw('MIN(id) as id'),
                        \DB::raw('COUNT(CASE WHEN user_id = 0 THEN 1 ELSE NULL END) as total_with_user_id_0'),
                        \DB::raw('COUNT(*) as total'),
                    )
                        ->where('state', '!=', '-')
                        ->groupBy('state')
                        ->orderBy('state', 'asc')
                        ->get();

                    if ($ptype == 1) {
                        $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                    } elseif ($ptype == 2) {
                        $phoneaccess = explode(',', Auth::user()->emp_access_web);
                    } elseif ($ptype == 3) {
                        $phoneaccess = explode(',', Auth::user()->emp_access_test);
                    } elseif ($ptype == 4) {
                        $phoneaccess = explode(',', Auth::user()->panel_type_4);
                    } elseif ($ptype == 5) {
                        $phoneaccess = explode(',', Auth::user()->panel_type_5);
                    } elseif ($ptype == 6) {
                        $phoneaccess = explode(',', Auth::user()->panel_type_6);
                    } else {
                        $phoneaccess = explode(',', Auth::user()->emp_access_phone); // Or handle this differently based on your needs
                    }

                @endphp
                <div class="card-header">
                    <div class="container-fluid">
                        <div class="col-lg-12 p-0">
                            <form method="post" action="{{ route('block_phone.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label style="float: left">Phone Number</label>
                                        <input type='text' required name="phone" id="phone"
                                            class="form-control phone" />
                                    </div>
                                    <div class="col-lg-3">
                                        <label style="float: left">description</label>
                                        <textarea name="description" id="" rows="3" class="form-control"></textarea>
                                    </div>
                                    <div class="col-lg-2 mt-auto">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fa fa-search"></i> Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="table_data">
                        @include('block_phone.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
    <script>
        $(function() {
            $('.status').hide();
            $('.status_update').change(function() {
                $('.status').hide();
                $('.' + $(this).val()).show();
            })
        })
        $(document).on("keyup", "#search", function(e) {
            e.preventDefault();

            var search = $("#search").val();
            console.log('search', search);

            $.ajax({
                url: '{{ route('filter.usedAndNew.data') }}',
                type: 'GET',
                data: {
                    'search': search,
                },
                success: function(data) {
                    console.log('data', data);
                    $("#table_data").html('');
                    $("#table_data").html(data);

                },
                error: function(error) {
                    console.error('Error submitting the form:', error);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#name').keyup(function() {
                var inputField = $(this);
                var suggestionsList = inputField.siblings(".suggestionsName");
                suggestionsList.css("display", "block");
                if (inputField.val() === "") {
                    suggestionsList.css("display", "none");
                }
                updateSuggestions(inputField, suggestionsList);
            });

            function updateSuggestions(inputField, suggestionsList) {
                var inputValue = inputField.val();

                $.ajax({
                    url: '{{ route('autosApproach.validation.check') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        field_name: 'name',
                        field_value: inputValue
                    },
                    success: function(response) {
                        suggestionsList.empty();
                        $.each(response.related_names, function(index, suggestion) {
                            var listItem = $("<li>").text(suggestion).click(function() {
                                inputField.val(suggestion);
                                suggestionsList.css("display", "none");
                            });
                            suggestionsList.append(listItem);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                    }
                });
            }

            $('#address, #email, #phone').on('keyup', function() {
                var fieldName = $(this).attr('name');
                var fieldValue = $(this).val();

                $.ajax({
                    url: '{{ route('autosApproach.validation.check') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        field_name: fieldName,
                        field_value: fieldValue
                    },
                    success: function(response) {
                        if (response.valid) {
                            $('#' + fieldName + '_error').hide();
                        } else {
                            $('#' + fieldName + '_error').show();
                        }

                        if ($('#address_error').is(':visible') || $('#email_error').is(
                                ':visible') || $('#phone_error').is(':visible')) {
                            // $('#submit_button').prop('disabled', true);
                        } else {
                            // $('#submit_button').prop('disabled', false);
                        }
                    }
                });
            });
        });

        $(".phone").keypress(function(e) {
            if ($(this).val() == '') {
                $(this).mask("(999) 999-9999");
            }
            var x = e.which || e.keycode;
            if ((x >= 48 && x <= 57) || (x >= 35 && x <= 40)) {
                return true;
            } else {
                return false;
            }
        })
    </script>
    <script>
        $(document).ready(function() {
            // Approve button click event
            $(document).on('click', '.approve-btn', function() {
                var id = $(this).data('id');
                updateStatus(id, 1);
            });

            // Disapprove button click event
            $(document).on('click', '.disapprove-btn', function() {
                var id = $(this).data('id');
                updateStatus(id, 0);
            });

            // Function to handle status update via AJAX
            function updateStatus(id, status) {
                $.ajax({
                    url: '{{ route('block_phone.updateStatus') }}', // Create a route for this action
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        status: status
                    },
                    success: function(response) {
                        // Update the status text
                        $('#status-' + id).text(status == 1 ? 'Approved' : 'Not Approved');

                        // Update the buttons
                        var actionHtml = '';
                        if (status == 1) {
                            actionHtml =
                                `<button type="button" class="btn btn-danger disapprove-btn" data-id="${id}">Disapprove</button>`;
                        } else {
                            actionHtml =
                                `<button type="button" class="btn btn-success approve-btn" data-id="${id}">Approve</button>`;
                        }
                        $('#action-' + id).html(actionHtml);
                    },
                    error: function(error) {
                        console.error('Error updating status:', error);
                    }
                });
            }
        });
    </script>
@endsection
