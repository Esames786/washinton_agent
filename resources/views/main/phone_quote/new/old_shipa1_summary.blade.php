@extends('layouts.innerpages')

@section('template_title')
    Summary
@endsection
@include('partials.mainsite_pages.return_function')

<style>
    @import url('https://fonts.googleapis.com/css?family=Open+Sans|Rock+Salt|Shadows+Into+Light|Cedarville+Cursive');

    .fa-check {
        color: green;
    }

    #table_heading {
        font-size: 15px;
        font-weight: 600;
        color: black;
        background-color: #6cabefd1;
    }

    .table {
        border: 1px solid black;
    }

    .oauc {
        padding: 11px;
        width: 100%;
    }

    .dauc {
        padding: 11px;
        width: 100%;
    }

    .history_content {
        /*background-color: white;*/
    }

    #right_border_radius {
        border-right: 1px solid black;
        border-top-right-radius: 25px;
        border-bottom-right-radius: 25px;
    }

    #left_border_radius {
        border-left: 1px solid black;
        border-top-left-radius: 25px;
        border-bottom-left-radius: 25px;
    }

    .strong_word {
        font-weight: 700;
        font-size: 14px;
        font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", "Helvetica Neue", Arial, sans-serif;
        /*color: rgb(0 123 255);*/

    }
    .inner_style{
        color: rgb(0 123 255) !important;
        text-transform: capitalize;
    }

    .list-group-item{
        /*color: rgb(0 123 255) !important;*/
    }
    h6{
        /*color: rgb(0 123 255) !important;*/
    }
    td {
        /*color: rgb(0 123 255) !important;*/
        font-weight: 600;
    }

    * {
        font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", "Helvetica Neue", Arial, sans-serif;
    }
    select.custom-select.custom-select-sm.form-control.form-control-sm {
        height: 32px;
    }
</style>

@section('content')


    <div class="modal" id="reportmodal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-header pd-x-20">
                    <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Send Email Link</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <form action="" method="post" id="form">
                        <h5 class=" lh-3 mg-b-20">Order Id <input readonly type="text" id="orderid" name="orderid" value="{{$data->id}}"/></h5>

                        <div class="card">
                            <div class="card-body pd-20">

                                @csrf
                                Email Link
                                </br>
                                <!-- <input type="hidden" name="user_id" value="47"> -->
                                <div class="form-group">
                                    <div class="row row-sm">
                                        <div class="col-sm">
                                            <input type="text" readonly name="link" id="link"
                                                   class="form-control"
                                                   value=""/>
                                        </div><!-- col -->
                                    </div><!-- row -->
                                </div><!-- form-group -->
                                <div class="form-group">
                                    <input type="text" name="email" id="email"
                                           class="form-control"
                                           value="" placeholder="Enter email address..."/>
                                </div><!-- form-group -->
                                <button type="submit" class="btn btn-primary pd-x-20">Submit
                                </button>

                            </div><!-- card-body -->
                        </div>
                    </form>
                </div><!-- modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-5">
        <h3>ORDER #{{$data->id}} History</h3>

        <div class="history_content ">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h4>CUSTOMER INFORMATION</h4>
                                    </div>
                                    @php
                                    $ophones=explode('*^',$data->ophone);
                                    $dphones=explode('*^',$data->dphone);
                                    @endphp
                                    <div class="card-body">
                                        <strong class="strong_word">Name : <span class="inner_style"> {{$data->oname}}</span> </strong><br>
                                        <strong class="strong_word">Email : <span class="inner_style">{{$data->oemail}}</span> </strong><br>
                                        <strong class="strong_word">Phone-Number : <span class="inner_style">
                                                <?php
                                                $new = substr($data->mainPhNum, 0, -8) . 'xxx-xxxx';
                                                 echo $new;
                                                ?>
                                            </span>
                                        </strong>

                                    </div>
                                    <div class="card-footer">
                                        <strong style="color:black" >Previous Record {{$count_previous}}</strong>
                                        <a href="/searchData?search={{$data->mainPhNum}}" class="inner_style" >Show Previous</a>
                                    </div>

                                    <div class="card-header  bg-primary text-white">
                                        <h4>ORIGIN & DEST. INFORMATION</h4>
                                    </div>

                                    <div class="card-body">
                                        <strong class="strong_word">Pickup Phone : <span class="inner_style">

                                                @foreach($ophones as $ophone)
                                                    @php
                                                    $new_ophone = '(xxx) xxx-'.substr( $ophone, -4);
                                                    @endphp
                                                    {{$new_ophone }}
                                                    <br>
                                                @endforeach
                                            </span></strong><br>
                                        <strong class="strong_word">Delivery Phone :
                                            <span class="inner_style">
                                                @foreach($dphones as $dphone)
                                                    @php
                                                    $new_dphone = '(xxx) xxx-'.substr( $dphone, -4);
                                                    @endphp
                                                    {{$new_dphone}}
                                                    <br>
                                                @endforeach
                                            </span></strong><br>
                                        <strong class="strong_word">Pickup Address :
                                            <span class="inner_style">{{$data->oaddress}}</span></strong><br>
                                        <strong class="strong_word">Delivery Address : <span class="inner_style">{{$data->daddress}}</span></strong><br>

                                    </div>
                                    <div class="card-footer">
                                        <strong class="strong_word" style="color: black">SIGNATURE :
                                            <span class="inner_style" style=" font-family: 'Cedarville Cursive', cursive;
                        font-size: 1.8em;">{{$data->signature}}</span>

                                        </strong>

                                    </div>

                                </div>

                            </div>

                            <?php
                            $condition1 = explode('*^', $data->condition);
                            ?>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h4>ORDER DETAILS</h4>

                                    </div>
                                    <div class="card-body">
                                        <strong class="strong_word">Quote Form</strong><br>
                                        <strong class="strong_word">Vehicle Name :
                                            <span class="inner_style">
                                                 <?php
                                                $vehiclenameyear = explode('*^-', $data->year);
                                                foreach ($vehiclenameyear as $vehicleyear){
                                                    echo " ($vehicleyear) ";
                                                }
                                                ?>
                                                <?php
                                                $vehiclenamemake = explode('*^-', $data->make);
                                                foreach ($vehiclenamemake as $vehiclemake){
                                                    echo " ($vehiclemake) ";
                                                }
                                                ?>
                                                <?php
                                                $vehiclenamodel = explode('*^-', $data->model);
                                                foreach ($vehiclenamodel as $vehiclemodel){
                                                    echo " ($vehiclemodel) ";
                                                }
                                                ?>
                                            </span>

                                            <span>{{$data->vin_num}}</span></strong><br>
                                        <strong class="strong_word">Condition : <span class="inner_style">
                                                 @foreach($condition1 as $val2)
                                                    {{ "(".$val2."),"}}
                                                @endforeach

                                            </span></strong><br>
                                        <strong class="strong_word">Trailer Type :
                                            <?php
                                            $transport = explode('*^', $data->transport);
                                            ?>
                                            <span class="inner_style">
                                                 @foreach($transport as $val3)
                                                    {{ "(".$val3."),"}}
                                                @endforeach

                                            </span>
                                        </strong><br>
                                        <strong class="strong_word">Location :
                                            <span class="inner_style">{{$data->origincity}}</span></strong><br>
                                        <strong class="strong_word">Addition Vehicle Information :
                                            <span class="inner_style">{{$data->add_info}}</span></strong><br>
                                        <strong class="strong_word">Addition Information :
                                            <span class="inner_style">{{$data->additional_2}}</span></strong>
                                    </div>

                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="card" style=" height: 30pc; overflow: scroll; ">
                                    <div class="card-header bg-primary text-white">
                                        <h4>STATUS</h4>
                                    </div>

                                    <div class=card-body" style=" height: 30pc; overflow: scroll; ">
                                        <ul class="list-group">
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus == 0)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                New

                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus == 1)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Interested

                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus == 2)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                FollowUp/More
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==3)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                AskingLow
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==4)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                NotInterested
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==5)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                NoResponse
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==6)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif

                                                TimeQuote
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==7)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                PaymentMissing
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==8)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Booked
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==9)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Listed
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==10)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Dispatch
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==11)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Pickup
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==12)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Delivered
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==13)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Completed
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==14)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Cancel
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==15)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                Deleted
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==16)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                OwesMoney
                                            </li>
                                            <li class="list-group-item strong_word">
                                                @if($data->pstatus==17)
                                                    <i class="fa fa-check tx-success mg-r-8"></i>
                                                @endif
                                                CarrierUpdate
                                            </li>

                                        </ul>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                {{--
                    <div class="card-footer " style="text-align: center;">

                        <div class="btn-group" id='order_action'>
                            <a    class="btn bg-dark text-white w-200 p-2 " data-toggle="modal" data-target="#reportmodal"  id="left_border_radius" target="_blank"> Send Link</a>
                            <a href="{{url('/new_edit/'. $data->id)}}" class=" btn btn-secondary text-white w-200 p-2" target="_blank">Edit
                                Listing</a>
                            <a href="{{url("/print_report/$data->id")}}" target="_blank"
                               class=" btn bg-dark text-white w-200 p-2">Print</a>
                            <a target="_blank" href="http://classic.mapquest.com/embed?zoom=5&amp;q={{$data->origincity}}+{{$data->originstate}}+{{$data->originzip}}" class=" btn btn-secondary text-white w-200 p-2  " id="right_border_radius"> View
                                Route</a>

                        </div>
                    </div>
                    --}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class=" col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>PAYMENT DETAILS</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="list-group-item">ORDER PRICE : <span class="inner_style" >${{$data->payment}}</span></h5>
                        <h5 class="list-group-item">ORDER STATUS : <span class="inner_style">{{ get_pstatus($data->pstatus)}}</span></h5>
                        <h5 class="list-group-item">Pay to Carrier: : <span class="inner_style">${{$data->pay_carrier}}</span></h5>
                        <h5 class="list-group-item">COD/COP: : <span class="inner_style">${{$data->cod_cop}}</span></h5>
                        <h5 class="list-group-item">Balance: : <span class="inner_style">${{$data->balance}}</span></h5>
                        <h5 class="list-group-item">Payment Status: : <span class="inner_style">{{$data->paid_status}}</span></h5>
                        <h5 class="list-group-item">Customer Name: : <span class="inner_style">{{ $data->yourname }}</span></h5>
                        <h5 class="list-group-item">Customer Signature: : <span class="inner_style" style=" font-family: 'Cedarville Cursive', cursive;
                        font-size: 1.8em;">{{$data->signature}}</span></h5>
                      {{-- <h5 class="list-group-item">listed price:: <span class="inner_style">{{$data->price}}</span></h5> --}}
                        <h5 class="list-group-item">Payment Status::
                            <span class="badge badge-pill badge-default mt-2">Payment: <?php echo pay_status($data->paid_status)?></span> </h5>

                        {{--
                        @if($data2->payment_status == "Paid")
                            <h5 class="list-group-item">Payment Date: : <span>{{ date("M-d-y h:i:s a",strtotime($val->created_at))}}</span></h5>
                        @endif
                            --}}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card" style="height:96% ">
                    <div class="card-header bg-primary text-white">
                        <h5>CALL/SMS History</h5>
                    </div>
                    <div class="card-body   ">
                        <table class="table table-bordered table-sm">

                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!--
        <div class=" col-md-6">
            <div class="card">
                <div class="card-header  bg-primary text-white">
                    <h4>Status Update</h4></div>
                <div class="card-body">

                    <div class="form-group">
                        <select class="form-control status_update">
                            <option disabled selected><h4>select</h4></option>
                            <option value="inters"><h4>Intersted</h4></option>
                            <option value="ask_low"><h4>Asking Low</h4></option>
                            <option value="m_follow"><h4>More Follow</h4></option>
                        </select>
                    </div>
                    <div class="form-group status inters">
                        <h6>Comments *</h6>
                        <input class="form-control " style="height: 59px;">
                    </div>
                    <div class="form-group status ask_low">
                        <h6>Ask Price *</h6>
                        <input class="form-control " style="height: 59px;">
                    </div>
                    <div class="form-group status m_follow">
                        <h6>Comments *</h6>
                        <input class="form-control " style="height: 59px;">
                    </div>
                    <button class="btn btn bg-dark text-white w-200 float-right"
                            style="height: 50px;border: 1px solid;border-radius: 22px;">Save
                    </button>
                </div>
            </div>
        </div>
    -->
        {{--</div>--}}


        <div class="row">
            <div class="card">
                <div class="card-header bg-primary text-white w-100">
                    <h4>ORDER ACTIVITY</h4></div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">

                        <tbody>

                        @foreach($orderactivity as $activity)
                            <tr>
                                <td>
                                    <span class="inner_style">
                                    <?php
                                        echo $activity->new_value;
                                        ?>
                                    </span>


                                    <br>
                                    {{   date("M-d-y h:i:s a",strtotime($activity->created_at)) }}
                                    <br>

                                </td>

                            </tr>
                        @endforeach


                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="card">
                <div class="card-header bg-primary text-white w-100">
                    <h4>ORDER UPDATE LOGS</h4></div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap dataTable no-footer" id="example1" role="grid"
                               aria-describedby="example1_info" >
                            <thead>
                            <tr>
                                <th>Column Name</th>
                                <th>Old Value</th>
                                <th>New Value</th>
                                <th>User Name</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order_history as $orderhistory)

                                <tr>
                                    <td>

                                        {{$orderhistory->field_name}}
                                    </td>
                                    <td>

                                        {{--
                                        @if($orderhistory->field_name=='Transport')
                                            @if($orderhistory->old_value==1)
                                                Open
                                            @else
                                                Enclosed
                                            @endif
                                        @elseif($orderhistory->namee=='Vehicle Condition')
                                            @if($orderhistory->old_value==1)
                                                Running
                                            @else
                                                Not Running
                                            @endif
                                        @elseif($orderhistory->namee=='Origin Terminal')
                                            {{get_oterminal_name($orderhistory->old_value)}}
                                        @elseif($orderhistory->namee=='Destination Terminal')
                                            {{get_dterminal_name($orderhistory->old_value)}}
                                        @else
                                            {{$orderhistory->old_value}}
                                        @endif
                                     --}}
                                        {{$orderhistory->old_value}}
                                    </td>
                                    <td>
                                        {{--
                                        @if($orderhistory->namee=='Transport')
                                            @if($orderhistory->new_value==1)
                                                Open
                                            @else
                                                Enclosed
                                            @endif
                                        @elseif($orderhistory->namee=='Vehicle Condition')
                                            @if($orderhistory->new_value==1)
                                                Running
                                            @else
                                                Not Running
                                            @endif
                                        @elseif($orderhistory->namee=='Origin Terminal')
                                            {{get_oterminal_name($orderhistory->new_value)}}
                                        @elseif($orderhistory->namee=='Destination Terminal')
                                            {{get_dterminal_name($orderhistory->new_value)}}
                                        @else
                                            {{$orderhistory->new_value}}
                                        @endif
                                        --}}
                                        {{$orderhistory->new_value}}
                                    </td>
                                    <td>
                                        <?php
                                        echo get_user_name_old_ship($orderhistory->user_id);
                                        ?>
                                    </td>
                                    <td>
                                        {{   date("M-d-y h:i:s a",strtotime($orderhistory->created_at)) }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>



                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <div class="modal" id="modaldemo4">
        <div class="modal-dialog modal-dialog-centered text-center " role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                    <i class="fe fe-check-circle fs-100 text-success lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-success tx-semibold" id="success"></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modaldemo5">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                    <i class="fe fe-x-circle fs-100 text-danger lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-danger" id="not_success"></h4>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('extraScript')
    <script>
        $(function () {
            $('.status').hide();
            $('.status_update').change(function () {
                $('.status').hide();
                $('.' + $(this).val()).show();
            })
        })
        $('#reportmodal').on('show.bs.modal', function (e) {

            //get data-id attribute of the clicked element
            //var orderId = $(e.relatedTarget).data('book-id');
            var orderId = $('#orderid').val();


            //populate the textbox
            var encryptvuserid = btoa({{Auth::user()->id}});
            var encryptvoderid = btoa(orderId);
            var linkv = "{{url('/email_order/')}}" + '/' + encryptvoderid + '/' + encryptvuserid;
            $(e.currentTarget).find('input[name="orderid"]').val(orderId);
            $(e.currentTarget).find('input[name="link"]').val(linkv);
        });


        $("#form").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: "/send_order_link",
                type: "POST",
                data: new FormData(this),

                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {

                },
                success: function (data) {

                    let test = data.toString();

                    let test2 = $.trim(test);
                    let text = "SUCCESS";
                    if (test2 == text) {

                        //$('#success').html(data);
                        $('#modaldemo4').modal('show');
                        $('#reportmodal').modal('hide');

                    } else {
                        //$('#not_success').html(data);
                        $('#modaldemo5').modal('show');
                    }
                },
                error: function (e) {
                    $("#err").html(e).fadeIn();
                }
            });
        }));







    </script>







@endsection

