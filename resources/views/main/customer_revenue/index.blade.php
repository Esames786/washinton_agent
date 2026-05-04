@extends('layouts.innerpages')
@section('template_title')
    Revenue
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
        
        
                   /*icon dynimic */
    /*     #overlay {*/
    /*    display: none;*/
    /*    position: fixed;*/
    /*    top: 0;*/
    /*    left: 0;*/
    /*    width: 100%;*/
    /*    height: 100%;*/
    /*    background-color: rgba(0, 0, 0, 0.5);*/
    /*    justify-content: center;*/
    /*    align-items: center;*/
    /*    z-index: 2;*/
    /*}*/

    /*   #errorIcon {*/
    /*    font-size: 17px;*/
    /*    color: #009eda!important;*/
    /*    display: flex;*/
    /*    align-items: center;*/
    /*}*/
    /*.popoverContent {*/
        /* display: none; */
    /*    position: absolute;*/
    /*    background-color: #fff;*/
    /*    border: 1px solid #ccc;*/
    /*    padding: 10px;*/
    /*    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);*/
    /*    z-index: 3;*/
        /* width: 178px; */
        /*right: 295px;*/
        /* bottom: 7px; */
    /*}*/
    /*.Terminal-error {*/
    /*    display: inline-flex;*/
    /*    column-gap: 14px;*/
    /*}*/

    /*label#selectedOptionLabel2 {*/
    /*    display: block;*/
    /*}*/
    /*.icon-relative {*/
    /*    position:relative !important;*/
    /*}*/
    </style>
    <!-- Row -->
    @include('partials.mainsite_pages.return_function')
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
                  {{--  @if($label[410]->status == 1) --}}
                 <!--<div class="Terminal-error">-->
                    <h1 class="my-4"><b>Revenue</b></h1>
             <!--        <i id="errorIcon" class="fas fa-info-circle fa-lg text-info info-icon" style="cursor: pointer;"></i>-->
             <!--</div>-->
              {{--
            <!-- <div class="popoverContent" style="display: none;">-->
            <!--        <div  class="popover-title">{{ $label[365]->name }}</div>-->
            <!--        <div class="popover-content">{{ $label[365]->display }}</div>-->
            <!--</div>-->
            <!-- @else-->
            <!--     <h1 class="my-4"><b>Revenue</b></h1>-->
            <!-- @endif-->
             --}}
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header d-block">
                    <div class="row">
                        <div class="col-sm-3 my-auto">
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
                        <div class="col-sm-3 my-auto">
                            <label class="form-label">Deparment Employees</label>
                            <select class="form-control select2" id="user">
                                <option value="" selected>All</option>
                                @foreach($users as $key => $val)
                                    <option value="{{$val->id}}">{{$val->slug}} ({{$val->userRole->name}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-1 mt-auto mb-1">
                            <button class="btn btn-primary" id="submit">Search</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row" id="allData">
                        <div class="col-sm-3">
                            <div class="card text-white bg-brown mb-3">
                              <div class="card-header d-flex justify-content-between">New To Time Quote<span>({{pstatusRole(Auth::user()->userRole->name,Auth::user()->id,6,$from,$to)}})</span></div>
                              <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,6,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
                                <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,6,$from,$to,100)}}/100%</span></h5>
                              </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card text-white bg-primary mb-3">
                              <div class="card-header d-flex justify-content-between">PaymentMissing<span>({{pstatusRole(Auth::user()->userRole->name,Auth::user()->id,7,$from,$to)}})</span></div>
                              <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,7,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
                                <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,7,$from,$to,100)}}/100%</span></h5>
                              </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card text-white bg-yellow mb-3">
                              <div class="card-header d-flex justify-content-between">Booked <span>({{pstatusRole(Auth::user()->userRole->name,Auth::user()->id,8,$from,$to)}})</span></div>
                              <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,8,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
                                <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,8,$from,$to,100)}}/100%</span></h5>
                              </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card text-white bg-teal mb-3">
                              <div class="card-header d-flex justify-content-between">OnApproval <span>({{pstatusRole(Auth::user()->userRole->name,Auth::user()->id,18,$from,$to)}})</span></div>
                              <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,18,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
                                <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,18,$from,$to,100)}}/100%</span></h5>
                              </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card text-white bg-purple mb-3">
                              <div class="card-header d-flex justify-content-between">Listed <span>({{pstatusRole(Auth::user()->userRole->name,Auth::user()->id,9,$from,$to)}})</span></div>
                              <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,9,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
                                <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,9,$from,$to,100)}}/100%</span></h5>
                              </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card text-white bg-warning mb-3">
                              <div class="card-header d-flex justify-content-between">Schedule <span>({{pstatusRole(Auth::user()->userRole->name,Auth::user()->id,10,$from,$to)}})</span></div>
                              <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,10,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
                                <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,10,$from,$to,100)}}/100%</span></h5>
                              </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card text-white bg-info mb-3">
                              <div class="card-header d-flex justify-content-between">Pickup <span>({{pstatusRole(Auth::user()->userRole->name,Auth::user()->id,11,$from,$to)}})</span></div>
                              <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,11,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
                                <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,11,$from,$to,100)}}/100%</span></h5>
                              </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card text-white bg-orange mb-3">
                              <div class="card-header d-flex justify-content-between">Delivered <span>({{pstatusRole(Auth::user()->userRole->name,Auth::user()->id,12,$from,$to)}})</span></div>
                              <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,12,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
                                <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,12,$from,$to,100)}}/100%</span></h5>
                              </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card text-white bg-success mb-3">
                              <div class="card-header d-flex justify-content-between">Completed <span>({{pstatusRole(Auth::user()->userRole->name,Auth::user()->id,13,$from,$to)}})</span></div>
                              <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,13,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
                                <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,13,$from,$to,100)}}/100%</span></h5>
                              </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card text-white bg-danger mb-3">
                              <div class="card-header d-flex justify-content-between">Cancel <span>({{pstatusRole(Auth::user()->userRole->name,Auth::user()->id,14,$from,$to)}})</span></div>
                              <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,14,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
                                <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,14,$from,$to,100)}}/100%</span></h5>
                              </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card text-white bg-amber mb-3">
                              <div class="card-header d-flex justify-content-between">Deleted <span>({{pstatusRole(Auth::user()->userRole->name,Auth::user()->id,15,$from,$to)}})</span></div>
                              <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,15,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
                                <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,15,$from,$to,100)}}/100%</span></h5>
                              </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card text-white bg-pink mb-3">
                              <div class="card-header d-flex justify-content-between">OnApprovalCancelled <span>({{pstatusRole(Auth::user()->userRole->name,Auth::user()->id,19,$from,$to)}})</span></div>
                              <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,19,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>
                                <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,19,$from,$to,100)}}/100%</span></h5>
                              </div>
                            </div>
                        </div>
                        <!--<div class="col-sm-3">-->
                        <!--    <div class="card text-white bg-dark mb-3">-->
                        <!--      <div class="card-header d-flex justify-content-between">CarrierUpdate <span>({{pstatusRole(Auth::user()->userRole->name,Auth::user()->id,17,$from,$to)}})</span></div>-->
                        <!--      <div class="card-body">-->
                        <!--        <h5 class="card-title d-flex justify-content-between">Average Rating: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,17,$from,$to,5)}}/5 <i class="fa fa-star-o" aria-hidden="true"></i></span></h5>-->
                        <!--        <h5 class="card-title d-flex justify-content-between">Average Percent: <span>{{rating($total_order,Auth::user()->userRole->name,Auth::user()->id,17,$from,$to,100)}}/100%</span></h5>-->
                        <!--      </div>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
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
        // $(document).ready(function() {
        //     // Select all error icons within the document
        //     var $errorIcons = $('.Terminal-error i');
        //     var $openPopoverContent = null;
        
        //     // Iterate over each error icon
        //     $errorIcons.each(function() {
        //         var $errorIcon = $(this);
        //         var $popoverContent = $errorIcon.closest('.Terminal-error').siblings('.popoverContent');
        
        //         // Toggle the popover on icon click
        //         $errorIcon.on('click', function(event) {
        //             event.stopPropagation(); // Prevent the document click event from firing immediately
        
        //             // Close the previously open popover content
        //             if ($openPopoverContent && !$openPopoverContent.is($popoverContent)) {
        //                 $openPopoverContent.hide();
        //             }
        
        //             // Toggle the current popover content
        //             $popoverContent.toggle();
        //             $openPopoverContent = $popoverContent;
        //         });
        //     });
        
        //     // Close the popover if clicked outside
        //     $(document).on('click', function(event) {
        //         if ($openPopoverContent && !$errorIcons.is(event.target) && !$openPopoverContent.is(event.target) && $openPopoverContent
        //             .has(event.target).length === 0) {
        //             $openPopoverContent.hide();
        //             $openPopoverContent = null;
        //         }
        //     });
        // });

    //=================onchange-values=============================
    
    
    
        $("#submit").click(function(){
            var user = $("#user").children('option:selected').val();
            var date_range = $("#date_range").val();
            console.log(date_range);
            $.ajax({
                url:"{{url('/revenue/search')}}",
                type:"POST",
                data:{date_range:date_range,user:user},
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
                $('#date_range').val("{{\Carbon\Carbon::now()->startOfMonth()->format('m/d/Y')}} - {{\Carbon\Carbon::now()->format('m/d/Y')}}");
            });
            $('#date_range').val("{{\Carbon\Carbon::now()->startOfMonth()->format('m/d/Y')}} - {{\Carbon\Carbon::now()->format('m/d/Y')}}");
        });

        $("body").delegate(".cancelBtn", "click", function(){
            $('#date_range').val('');
        });
    </script>
@endsection

