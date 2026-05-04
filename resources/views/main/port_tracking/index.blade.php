@extends('layouts.innerpages')

@section('template_title')
    {{ ucfirst(trim("$_SERVER[REQUEST_URI]",'/'))}}
@endsection

@section('content')
    <style>
        select.custom-select.custom-select-sm.form-control.form-control-sm {
            height: 29px;
        }
        .searchContainer {
        display: flex;
        padding: 10px 0px;
        column-gap: 20px;
        color: #0281b1;
        }
    </style>

    <!--/app header-->                                                <!--Page header-->
    <div class="page-header">
        <div class="text-secondary text-center text-uppercase">
            <h1 class="my-4"><b>Port Tracking</b></h1>
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
          <div class="searchContainer">
            <div class="form-group">
                <label for="selectStatus">Select Status:</label>
                <select name="selectStatus" id="selectStatus">
                    <option value="" selected>Select</option>
                    <option value="In Process">In Process</option>
                    <option value="Delivered">Delivered</option>
                </select>
            </div>
            <div class="form-group">
                <label for="selectDock">Dock Receive CreatedBy:</label>
                <select name="selectDock" id="selectDock">
                    <option value="" selected>Select</option>
                    <option value="us">Us</option>
                    <option value="other">Other</option>
                </select>
            </div>
          </div>
            <div class="card">
                <div class="card-body">
                    <div id="table_data">
                        @include('main.port_tracking.load')
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
        $('#selectDock, #selectStatus').on('change', function () {
            // Get the selected values
            var selectDock = $('#selectDock').val();
            var selectStatus = $('#selectStatus').val();
        
            // Make a GET AJAX request with the selected values
            $.ajax({
                url: '{{ route("get.status.port") }}',
                method: 'GET',
                data: {
                    dock: selectDock,
                    status: selectStatus
                },
                success: function (data) {
                    // Update the content of the container with the search results
                    $('#table_data').html(data);
        
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
                    $('#table_data').html(data);
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