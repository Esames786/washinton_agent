@extends('layouts.innerpages')
@section('template_title')
    @if(Auth::user()->userRole->name == 'Admin') Dispatcher/Order Taker @else Your @endif Rating
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
        .bg-brown {
            background: #542e2e !important;
        }
        .bg-purple{
            background: #600060 !important;
        }

        /* Go from zero to full opacity */
        @keyframes fadeEffect {
            from {opacity: 0;}
            to {opacity: 1;}
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
                      @if($label[401-1]->status == 1)
                      <div class="Terminal-error">
                    <h1 class="my-4"><b>@if(Auth::user()->userRole->name == 'Admin') Dispatcher/Order Taker @else Your @endif Rating</b></h1>
                     <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon"
                    style="cursor: pointer;"></i>
                    </div>
                    <div class="popoverContent" style="display: none;">
                         <div class="popover-title">{{ $label[401-1]->name }}</div>
                         <div class="popover-content">{{ $label[401-1]->display }}</div>
                    </div>
                  
                    @else
                        <label class="form-label">Company Phone# (999) 999-9999 <span
                            class="text-danger">*</span></label>
                   @endif
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header d-block">
                    <div class="row">
                        @if(Auth::user()->userRole->name == 'Admin')
                        <div class="col-sm-3 my-auto">
                            <label class="form-label">Search By</label>
                            <select id="search_by" name="search_by" class="form-control">
                                <option value="">Select</option>
                                <option value="rater_id">Rater</option>
                                <option value="replyer_id">Replyer</option>
                                <option value="mistake_user_id">Mistaker</option>
                            </select>
                        </div>
                        <div class="col-sm-3 my-auto">
                            <label class="form-label">Users</label>
                            <select id="users" name="users" class="form-control">
                                <option value="">All</option>
                                @foreach($user as $key => $val)
                                    <option value="{{$val->id}}" class="text-capitalize">{{$val->slug ?? $val->name.' '.$val->last_name}} ({{$val->userRole->name}})</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-sm-4 my-auto">
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
                        <div class="col-sm-1 mt-auto mb-1">
                            <button class="btn btn-primary" id="submit">Search</button>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="allData">
                    @include('main.rating.search')
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="ratingPopup" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="ratingPopupLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width:75% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ratingPopupLabel">Rate</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" id="ord_id" name="ord_id" />
                <div id="ratingPopupContent">
                    
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
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
    
    
    
    
    
        function serachData(page)
        {
            var date_range = $("#date_range").val();
            var users = $("#users").children('option:selected').val();
            var search_by = $("#search_by").children('option:selected').val();
            $.ajax({
                url:"{{url('/user_rating')}}?page="+page,
                type:"GET",
                data:{date_range:date_range,users:users,search_by:search_by},
                beforeSend: function () {
                    $('#allData').html("");
                    $('#allData').append(`<div class="lds-hourglass" id='ldss'></div>`);
                },
                success:function(res)
                {
                    $("#allData").html("");
                    $("#allData").html(res);
                }
            });
        }
        $("#submit").click(function(){
            serachData(1);
        })
        $(function () { 
            new Date();
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
                // "startDate": new Date(date.getFullYear(), date.getMonth(), 1),
                // "endDate": new Date(date.getFullYear(), date.getMonth() + 1, 0),
                "opens": "center",
                "drops": "auto"
            }, function (start, end, label) {
                // console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                $('#date_range').val("");
            });
            $('#date_range').val("");
        });

        $("body").delegate(".cancelBtn", "click", function(){
            $('#date_range').val('');
        });
        
        $(document).on('click', '.pagination a', function (event) {

            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            serachData(page);
        });
        
        function ratingDetail(id)
        {
            $("#ord_id").val(id);
            
            $.ajax({
                url:"{{ url('/ratingdetail') }}",
                type:"GET",
                dataType:"html",
                data:{order_id:id},
                success:function(res)
                {
                    $("#ratingPopupContent").html('');
                    $("#ratingPopupContent").html(res);
                }
            })
        }
    </script>
@endsection

