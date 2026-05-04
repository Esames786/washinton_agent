@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim("$_SERVER[REQUEST_URI]",'/'))}}
@endsection

@section('content')
    <style>
        select.custom-select.custom-select-sm.form-control.form-control-sm {
            height: 29px;
        }
    </style>

    <!--/app header-->                                                <!--Page header-->
    <div class="page-header">
        <div class="text-secondary text-center text-uppercase">
            <h1 class="my-4"><b>Carrier List</b></h1>
        </div>
        <div class="page-rightheader">
            <div class="btn btn-list">
                <a href="" class="btn btn-info" data-toggle="modal" data-target="#carrirermodal">
                    <i class="fe fe-settings mr-1"></i> Add Carriers
                </a>
            </div>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->

    <div class="row">
        <div class="col-12">
            @if(session('flash_message'))
                <div class="alert alert-success">
                    {{session('flash_message')}}
                </div>
            @endif

            <!-- Search input -->
            <div class="form-group">
                <label for="searchCarrier">Search Carrier:</label>
                <input type="text" class="form-control" id="searchCarrier" placeholder="Enter carrier name">
            </div>

            <div class="card">
                <div class="card-body">
                    <div id="table_data">
                        @include('main.phone_quote.carrier.load')
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end app-content-->

@endsection

@section('extraScript')
<script>
    $(document).ready(function () {
        // Attach keyup event handler to the search input field
        $('#searchCarrier').on('keyup', function () {
            // Get the search query
            var searchQuery = $(this).val();

            // Make a GET AJAX request with the search query
            $.ajax({
                url: '{{ route("get.search.carriers") }}',
                method: 'GET',
                dataType:'json',
                data: {search: searchQuery},
                success: function (data) {
                    $('#table_data').html('');
                    $('#table_data').html(data.html);

                    // Reinitialize pagination links
                    initPagination();
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', error);
                    // Handle the error if needed
                }
            });
        });

        // Function to initialize pagination links
        function initPagination() {
            $('.pagination').find('a').each(function () {
                $(this).on('click', function (e) {
                    e.preventDefault();
                    var url = $(this).attr('href');
                    loadPage(url);
                });
            });
        }

        // Function to load a specific page
        function loadPage(url) {
            $.ajax({
                url: url,
                method: 'GET',
                success: function (data) {
                    $('#table_data').html('');
                    $('#table_data').html(data.html);
                    // Reinitialize pagination links
                    initPagination();
                },
                error: function (xhr, status, error) {
                    console.error('AJAX request failed:', error);
                    // Handle the error if needed
                }
            });
        }

        // Initialize pagination on page load
        initPagination();
    });
</script>
@endsection
