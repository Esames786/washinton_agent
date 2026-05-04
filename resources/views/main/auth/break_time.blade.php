@extends('layouts.innerpages')
@section('template_title')
    Break Time
@endsection
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-..." crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <style>
        /* Style the tab */
        .table-responsive{
            overflow:unset !important;
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
            animation: fadeEffect 1s; /* Fading effect takes 1 second */
        }
        
        .dropdown-menu{
            left:-6rem !important;
        }
        
        .bg-yellow
        {
            background-color:#c3c300 !important;
        }
        
        .bg-orange
        {
            background-color:#F49917 !important;
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
            from {opacity: 0;}
            to {opacity: 1;}
        }
        
        th,td{
            text-align:center;
            vertical-align:middle !important;
        }
        
        .btn-outline-warning i, .btn-outline-warning{
            color: #ffab00 !important;
            color: #ffab00 !important;
        }
        .btn-outline-warning:hover i
        {
            color: #fff !important;
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
        /*font-size: 14px;*/
        color: #009eda!important;
        cursor: pointer;
      }
      .popoverContent {
        /* display: none; */
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
    </style>
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            @if(session('flash_message'))
                <div class="alert alert-success">
                    {{session('flash_message')}}
                </div>
            @endif
            <!--div-->
            <div class="page-header">
                <div class="text-secondary text-center text-uppercase w-100">
                     @if($label[394-1]->status == 1)
                               <div class="Terminal-error">
                    <h1 class="my-4"><b>Break Time</b></h1>
                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                            style="cursor: pointer;"></i>
                            </div>
                            <div class="popoverContent" style="display: none;">
                                 <div class="popover-title">{{ $label[394-1]->name }}</div>
                                 <div class="popover-content">{{ $label[394-1]->display }}</div>
                            </div>
                          
                            @else
                              <h1>Break Time</h1>
                            @endif
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-12 mt-3">
                            <h1>Break Time</h1>
                        </div>
                        <div class="col-md-6">
                            <label for="search" class="form-label">Search Name</label>
                            <input type="text" id="search" placeholder="Search Name" class="form-control" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Daterange <button type="button" class="btn btn-info btn-sm" onclick="$('#date_range').val('')" style="padding: 3.2px 10px;">Clear</button></label>
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' name="date_range"  id="date_range" class="form-control" />
                                <span class="input-group-addon" style="
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
                            @include('main.auth.search2')
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
      //=================onchange-values=============================
        $(document).ready(function() {
            // Select all error icons within the document
            var $errorIcons = $('.Terminal-error i');
            var $openPopoverContent = null;
        
            // Iterate over each error icon
            $errorIcons.each(function() {
                var $errorIcon = $(this);
                var $popoverContent = $errorIcon.closest('.Terminal-error').siblings('.popoverContent');
        
                // Toggle the popover on icon click
                $errorIcon.on('click', function(event) {
                    event.stopPropagation(); // Prevent the document click event from firing immediately
        
                    // Close the previously open popover content
                    if ($openPopoverContent && !$openPopoverContent.is($popoverContent)) {
                        $openPopoverContent.hide();
                    }
        
                    // Toggle the current popover content
                    $popoverContent.toggle();
                    $openPopoverContent = $popoverContent;
                });
            });
        
            // Close the popover if clicked outside
            $(document).on('click', function(event) {
                if ($openPopoverContent && !$errorIcons.is(event.target) && !$openPopoverContent.is(event.target) && $openPopoverContent
                    .has(event.target).length === 0) {
                    $openPopoverContent.hide();
                    $openPopoverContent = null;
                }
            });
        });

    //=================onchange-values=============================
        $(function () {
            var date = new Date();
            $('#date_range').daterangepicker({
                "showDropdowns": true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                "alwaysShowCalendars": true,
                "opens": "center",
                "drops": "auto"
            }, function (start, end, label) {
                $('#date_range').val("");
            });
            $('#date_range').val("");
        });

        $("body").delegate(".cancelBtn", "click", function(){
            $('#date_range').val('');
        });
        
        $(document).ready(function () {

            $(document).on('click', '.pagination a', function (event) {

                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                var search = $("#search").val();
                var date_range = $("#date_range").val();
                fetch_data3(page,search,date_range);

            });

            function fetch_data3(page,search,date_range) {

                $('#searchData').html('');
                $('#searchData').append(`<div class="lds-hourglass" id='ldss'></div>`);

                $.ajax({

                    url: "break_time?page=" + page,
                    type:"GET",
                    data:{search:search,date_range:date_range},
                    dataType:"html",
                    success: function (data) {
                        $('#searchData').html('');
                        $('#searchData').html(data);

                    },
                    complete: function (data) {
                    }

                })

            }
            
            $("#search").on('keypress',function(e){
                var date_range = $("#date_range").val();
                fetch_data3(1,$(this).val(),date_range);
            })
            
            $("#date_range").change(function(){
                var search = $("#search").val();
                fetch_data3(1,search,$(this).val());
            })

        });
    </script>


    <!--Scrolling Modal-->

@endsection
