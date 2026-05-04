@extends('layouts.innerpages')

@section('template_title')
    Order Storage
@endsection
@include('partials.mainsite_pages.return_function')
<style>
    .oauc {
        padding: 11px;
        width: 100%;
    }

    .dauc {
        padding: 11px;
        width: 100%;
    }
</style>

@section('content')

    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">Order Storage </h4>

            <h3 class="page-title mb-0"></h3>


            <h4 id="orderidplace"></h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Pages</a>
                </li>

                <li class="breadcrumb-item active" aria-current="page"><a href="#">Order Storage</a></li>
            </ol>
        </div>

    </div>
    <!--End Page header-->
    <!-- Row -->
    <form action="/store_update_storage" id="form" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token()}}">
        <input type="hidden" name="order_id" value="{{$order->id}}">

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Order Storage</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <table class="table table-reponsive table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order Id</th>
                                            <th>Origin</th>
                                            <th>Destination</th>
                                            <th>Vehicle Detail</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{$order->id}}</td>
                                            <td>{{$order->originzsc}}</td>
                                            <td>{{$order->destinationzsc}}</td>
                                            <td>
                                                <?php 
                                                    $ymk = explode('*^-',$order->ymk);
                                                    $transport = explode('*^-',$order->transport);
                                                    $condition = explode('*^-',$order->condition);
                                                    
                                                ?>
                                                @if(isset($ymk[0]))
                                                    @foreach($ymk as $key => $val)
                                                        @if(isset($val))
                                                            <b>{{$val}}</b>
                                                        @endif
                                                        @if(isset($condition[$key]))
                                                            @if($condition[$key] == 1)
                                                                <span class="badge badge-info">Operable</span>
                                                            @else
                                                                <span class="badge badge-info">Non-running</span>
                                                            @endif
                                                        @else
                                                            <span class="badge badge-info">Non-running</span>
                                                        @endif
                                                        @if(isset($transport[$key]))
                                                            @if($transport[$key] == 1)
                                                                <span class="badge badge-primary">Open</span>
                                                            @else
                                                                <span class="badge badge-primary">Enclosed</span>
                                                            @endif
                                                        @else
                                                            <span class="badge badge-primary">Enclosed</span>
                                                        @endif
                                                        <br>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                Created: {{\Carbon\Carbon::parse($order->created_at)->format('M,d Y h:i A')}}<br />
                                                Updated: {{\Carbon\Carbon::parse($order->updated_at)->format('M,d Y h:i A')}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-title font-weight-bold"></div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="row">
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Storage Charge Per Day</label>
                                            <input type="number" required name="storage_charge" id="storage_charge" class="form-control"
                                                   placeholder="Enter Storage Charge Per Day..." value="{{$order->storage_charge > 0 ? $order->storage_charge : ''}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Pickup By Another Carrier</label>
                                            <select name="change_carrier" id="change_carrier" class="form-control">
                                                <option value="yes">Yes</option>
                                                <option value="no" selected>No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php 
                                        $carrier = \App\carrier::select('id','companyname')->where('orderId',$order->id)->orderBy('created_at','DESC')->get();
                                    ?>
                                    <div class="col-sm-4 col-md-4" style="display:none;" id="carrier">
                                        <div class="form-group">
                                            <label class="form-label">Select Carrier
                                                <a href="/carrier_add/{{$order->id}}"
                                                   type="button" target="_blank"
                                                   class="badge badge-primary">UPDATE CARRIER</a>
                        
                                            </label>
                                            <select id="current_carrier" class="form-control"
                                                    name="current_carrier"
                                                    style=" height: auto; ">
                                                <option value="">Please Add Carrier</option>
                                                @foreach($carrier as $key => $val)
                                                    <option value="{{$val->id}}" @if($val->id == $order->carrier_id) selected @endif>{{$val->companyname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" required name="companyname" class="form-control"
                                           placeholder="Enter Company Name..." value="{{isset($order->storage->company_name) ? $order->storage->company_name : ''}}">
                                </div>
                                <ul class="nav flex-column border scrollul" style="max-height:200px;overflow:scroll;position: absolute;top: 79.9%;background: grey;width: 95%;z-index: 9;display:none;">
                                </ul>
                            </div>

                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Manager/Owner Name</label>
                                    <input type="text" required name="managerownername" class="form-control"
                                           placeholder="Enter Manager/Owner Name..." value="{{isset($order->storage->manager_owner_name) ? $order->storage->manager_owner_name : ''}}">
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Company Address</label>
                                    <input type="text" required name="caddress" class="form-control"
                                           placeholder="Enter Company Address..." value="{{isset($order->storage->company_address) ? $order->storage->company_address : ''}}">
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label position-relative">Zipcode</label>
                                    <input type="text" required name="zip" class="form-control" id="zip"
                                           placeholder="Enter Zipcode..." value="{{isset($order->storage->zip) ? $order->storage->zip : ''}}">
                                </div>
                                <ul class="nav flex-column border scrollul" style="max-height:200px;overflow:scroll;position: absolute;top: 79.9%;background: grey;width: 95%;z-index: 9;display:none;">
                                </ul>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" required>Company Open Time</label>
                                    <select required name="opentime" id="opentime" class="form-control"  placeholder="Enter Open Time...">
                                        <option value="" selected disabled>Select Company Open Time</option>
                                        <option value="12:00 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '12:00 AM') selected @endif @endif>12:00 AM</option>
                                        <option value="12:30 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '12:30 AM') selected @endif @endif>12:30 AM</option>
                                        <option value="1:00 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '1:00 AM') selected @endif @endif>1:00 AM</option>
                                        <option value="1:30 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '1:30 AM') selected @endif @endif>1:30 AM</option>
                                        <option value="2:00 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '2:00 AM') selected @endif @endif>2:00 AM</option>
                                        <option value="2:30 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '2:30 AM') selected @endif @endif>2:30 AM</option>
                                        <option value="3:00 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '3:00 AM') selected @endif @endif>3:00 AM</option>
                                        <option value="3:30 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '3:30 AM') selected @endif @endif>3:30 AM</option>
                                        <option value="4:00 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '4:00 AM') selected @endif @endif>4:00 AM</option>
                                        <option value="4:30 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '4:30 AM') selected @endif @endif>4:30 AM</option>
                                        <option value="5:00 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '5:00 AM') selected @endif @endif>5:00 AM</option>
                                        <option value="5:30 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '5:30 AM') selected @endif @endif>5:30 AM</option>
                                        <option value="6:00 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '6:00 AM') selected @endif @endif>6:00 AM</option>
                                        <option value="6:30 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '6:30 AM') selected @endif @endif>6:30 AM</option>
                                        <option value="7:00 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '7:00 AM') selected @endif @endif>7:00 AM</option>
                                        <option value="7:30 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '7:30 AM') selected @endif @endif>7:30 AM</option>
                                        <option value="8:00 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '8:00 AM') selected @endif @endif>8:00 AM</option>
                                        <option value="8:30 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '8:30 AM') selected @endif @endif>8:30 AM</option>
                                        <option value="9:00 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '9:00 AM') selected @endif @endif>9:00 AM</option>
                                        <option value="9:30 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '9:30 AM') selected @endif @endif>9:30 AM</option>
                                        <option value="10:00 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '10:00 AM') selected @endif @endif>10:00 AM</option>
                                        <option value="10:30 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '10:30 AM') selected @endif @endif>10:30 AM</option>
                                        <option value="11:00 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '11:00 AM') selected @endif @endif>11:00 AM</option>
                                        <option value="11:30 AM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '11:30 AM') selected @endif @endif>11:30 AM</option>
                                        <option value="12:00 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '12:00 PM') selected @endif @endif>12:00 PM</option>
                                        <option value="12:30 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '12:30 PM') selected @endif @endif>12:30 PM</option>
                                        <option value="1:00 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '1:00 PM') selected @endif @endif>1:00 PM</option>
                                        <option value="1:30 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '1:30 PM') selected @endif @endif>1:30 PM</option>
                                        <option value="2:00 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '2:00 PM') selected @endif @endif>2:00 PM</option>
                                        <option value="2:30 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '2:30 PM') selected @endif @endif>2:30 PM</option>
                                        <option value="3:00 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '3:00 PM') selected @endif @endif>3:00 PM</option>
                                        <option value="3:30 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '3:30 PM') selected @endif @endif>3:30 PM</option>
                                        <option value="4:00 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '4:00 PM') selected @endif @endif>4:00 PM</option>
                                        <option value="4:30 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '4:30 PM') selected @endif @endif>4:30 PM</option>
                                        <option value="5:00 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '5:00 PM') selected @endif @endif>5:00 PM</option>
                                        <option value="5:30 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '5:30 PM') selected @endif @endif>5:30 PM</option>
                                        <option value="6:00 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '6:00 PM') selected @endif @endif>6:00 PM</option>
                                        <option value="6:30 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '6:30 PM') selected @endif @endif>6:30 PM</option>
                                        <option value="7:00 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '7:00 PM') selected @endif @endif>7:00 PM</option>
                                        <option value="7:30 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '7:30 PM') selected @endif @endif>7:30 PM</option>
                                        <option value="8:00 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '8:00 PM') selected @endif @endif>8:00 PM</option>
                                        <option value="8:30 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '8:30 PM') selected @endif @endif>8:30 PM</option>
                                        <option value="9:00 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '9:00 PM') selected @endif @endif>9:00 PM</option>
                                        <option value="9:30 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '9:30 PM') selected @endif @endif>9:30 PM</option>
                                        <option value="10:00 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '10:00 PM') selected @endif @endif>10:00 PM</option>
                                        <option value="10:30 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '10:30 PM') selected @endif @endif>10:30 PM</option>
                                        <option value="11:00 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '11:00 PM') selected @endif @endif>11:00 PM</option>
                                        <option value="11:30 PM" @if(isset($order->storage->open_time)) @if($order->storage->open_time == '11:30 PM') selected @endif @endif>11:30 PM</option>
                                    </select>
                                    <!--<input type="time" required name="opentime" id="opentime" class="form-control"  placeholder="Enter Open Time..." >-->
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" required>Company Close Time</label>
                                    <select required name="closetime" id="closetime" class="form-control" placeholder="Enter Close Time...">
                                        <option value="" selected disabled>Select Company Close Time</option>
                                        <option value="12:00 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '12:00 AM') selected @endif @endif>12:00 AM</option>
                                        <option value="12:30 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '12:30 AM') selected @endif @endif>12:30 AM</option>
                                        <option value="1:00 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '1:00 AM') selected @endif @endif>1:00 AM</option>
                                        <option value="1:30 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '1:30 AM') selected @endif @endif>1:30 AM</option>
                                        <option value="2:00 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '2:00 AM') selected @endif @endif>2:00 AM</option>
                                        <option value="2:30 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '2:30 AM') selected @endif @endif>2:30 AM</option>
                                        <option value="3:00 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '3:00 AM') selected @endif @endif>3:00 AM</option>
                                        <option value="3:30 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '3:30 AM') selected @endif @endif>3:30 AM</option>
                                        <option value="4:00 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '4:00 AM') selected @endif @endif>4:00 AM</option>
                                        <option value="4:30 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '4:30 AM') selected @endif @endif>4:30 AM</option>
                                        <option value="5:00 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '5:00 AM') selected @endif @endif>5:00 AM</option>
                                        <option value="5:30 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '5:30 AM') selected @endif @endif>5:30 AM</option>
                                        <option value="6:00 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '6:00 AM') selected @endif @endif>6:00 AM</option>
                                        <option value="6:30 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '6:30 AM') selected @endif @endif>6:30 AM</option>
                                        <option value="7:00 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '7:00 AM') selected @endif @endif>7:00 AM</option>
                                        <option value="7:30 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '7:30 AM') selected @endif @endif>7:30 AM</option>
                                        <option value="8:00 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '8:00 AM') selected @endif @endif>8:00 AM</option>
                                        <option value="8:30 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '8:30 AM') selected @endif @endif>8:30 AM</option>
                                        <option value="9:00 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '9:00 AM') selected @endif @endif>9:00 AM</option>
                                        <option value="9:30 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '9:30 AM') selected @endif @endif>9:30 AM</option>
                                        <option value="10:00 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '10:00 AM') selected @endif @endif>10:00 AM</option>
                                        <option value="10:30 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '10:30 AM') selected @endif @endif>10:30 AM</option>
                                        <option value="11:00 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '11:00 AM') selected @endif @endif>11:00 AM</option>
                                        <option value="11:30 AM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '11:30 AM') selected @endif @endif>11:30 AM</option>
                                        <option value="12:00 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '12:00 PM') selected @endif @endif>12:00 PM</option>
                                        <option value="12:30 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '12:30 PM') selected @endif @endif>12:30 PM</option>
                                        <option value="1:00 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '1:00 PM') selected @endif @endif>1:00 PM</option>
                                        <option value="1:30 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '1:30 PM') selected @endif @endif>1:30 PM</option>
                                        <option value="2:00 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '2:00 PM') selected @endif @endif>2:00 PM</option>
                                        <option value="2:30 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '2:30 PM') selected @endif @endif>2:30 PM</option>
                                        <option value="3:00 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '3:00 PM') selected @endif @endif>3:00 PM</option>
                                        <option value="3:30 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '3:30 PM') selected @endif @endif>3:30 PM</option>
                                        <option value="4:00 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '4:00 PM') selected @endif @endif>4:00 PM</option>
                                        <option value="4:30 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '4:30 PM') selected @endif @endif>4:30 PM</option>
                                        <option value="5:00 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '5:00 PM') selected @endif @endif>5:00 PM</option>
                                        <option value="5:30 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '5:30 PM') selected @endif @endif>5:30 PM</option>
                                        <option value="6:00 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '6:00 PM') selected @endif @endif>6:00 PM</option>
                                        <option value="6:30 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '6:30 PM') selected @endif @endif>6:30 PM</option>
                                        <option value="7:00 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '7:00 PM') selected @endif @endif>7:00 PM</option>
                                        <option value="7:30 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '7:30 PM') selected @endif @endif>7:30 PM</option>
                                        <option value="8:00 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '8:00 PM') selected @endif @endif>8:00 PM</option>
                                        <option value="8:30 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '8:30 PM') selected @endif @endif>8:30 PM</option>
                                        <option value="9:00 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '9:00 PM') selected @endif @endif>9:00 PM</option>
                                        <option value="9:30 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '9:30 PM') selected @endif @endif>9:30 PM</option>
                                        <option value="10:00 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '10:00 PM') selected @endif @endif>10:00 PM</option>
                                        <option value="10:30 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '10:30 PM') selected @endif @endif>10:30 PM</option>
                                        <option value="11:00 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '11:00 PM') selected @endif @endif>11:00 PM</option>
                                        <option value="11:30 PM" @if(isset($order->storage->close_time)) @if($order->storage->close_time == '11:30 PM') selected @endif @endif>11:30 PM</option>
                                    </select>
                                    <!--<input type="time" required name="closetime" id="closetime" class="form-control" placeholder="Enter Close Time..."/>-->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" required>Storage Running Charges</label>
                                    <input type="text" required name="charges" id="charges" class="form-control"
                                           placeholder="Enter Running Charges..." value="{{isset($order->storage->charges) ? $order->storage->charges : ''}}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" required>Storage Non-running Charges</label>
                                    <input type="text" name="charges2" id="charges2" class="form-control"
                                           placeholder="Enter Non-running Charges..." value="{{isset($order->storage->charges2) ? $order->storage->charges2 : ''}}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" required>Phone No</label>
                                    <input type="text" required name="phoneno" id="phoneno" class="form-control"
                                           placeholder="Enter Phone No..." value="{{isset($order->storage->phoneno) ? $order->storage->phoneno : ''}}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" required>Phone No 2</label>
                                    <input type="text" name="phoneno2" id="phoneno2" class="form-control"
                                           placeholder="Enter Phone No 2..." value="{{isset($order->storage->phoneno2) ? $order->storage->phoneno2 : ''}}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" required>Fax No</label>
                                    <input type="text" name="faxno" id="faxno" class="form-control"
                                           placeholder="Enter Fax No..." value="{{isset($order->storage->faxno) ? $order->storage->faxno : ''}}" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Storage Duration</label>
                                    <select name="duration" id="duration" required class="form-control " style="border: 1px solid black; height: 35px;" >
                                        <option value="">Select Duration</option>
                                        <option value="Day" @if(isset($order->storage->storage_duration)) @if($order->storage->storage_duration == 'Day') selected @endif @endif>Day</option>
                                        <option value="Week" @if(isset($order->storage->storage_duration)) @if($order->storage->storage_duration == 'Week') selected @endif @endif>Week</option>
                                        <option value="Month" @if(isset($order->storage->storage_duration)) @if($order->storage->storage_duration == 'Month') selected @endif @endif>Month</option>
                                    </select>
                                </div>
                            </div>
                            <?php
                                $forklift_twotruck = [];
                                if(isset($order->storage->forklift_twotruck))
                                {
                                    $forklift_twotruck = explode(', ',$order->storage->forklift_twotruck);
                                }
                            ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Does Storage Place Have a Forklift or tow truck ? *</label>
                                    <input type="checkbox" name="optionv[]" id="optionv" value="Forklift" 
                                    @if(isset($forklift_twotruck[0]))
                                        @foreach($forklift_twotruck as $key => $val)
                                            @if($val == 'Forklift')
                                                checked
                                            @endif
                                        @endforeach
                                    @endif
                                    />
                                    Forklift
                                    &nbsp;
                                    &nbsp;
                                    <input type="checkbox" name="optionv[]" id="optionv2" value="Tow Truck" 
                                    @if(isset($forklift_twotruck[0]))
                                        @foreach($forklift_twotruck as $key => $val)
                                            @if($val == 'Tow Truck')
                                                checked
                                            @endif
                                        @endforeach
                                    @endif  />
                                    Tow Truck
                                </div>
                            </div>
                            <div class="col-md-4" 
                                @if(isset($forklift_twotruck[0]))
                                    @foreach($forklift_twotruck as $key => $val)
                                        @if($val == 'Forklift')
                                            style="display:block;"
                                        @else
                                            style="display:none;"
                                        @endif
                                    @endforeach
                                @else
                                    style="display:none;"
                                @endif
                            >
                                <div class="form-group">
                                    <label class="form-label">Forklift Price</label>
                                    <input type="number" name="forklift_price" id="forklift_price" class="form-control"
                                           placeholder="Enter Forklift Price..." value="{{isset($order->storage->forklift_price) ? $order->storage->forklift_price : ''}}" />
                                </div>
                            </div>
                            <div class="col-md-4" 
                                @if(isset($forklift_twotruck[0]))
                                    @foreach($forklift_twotruck as $key => $val)
                                        @if($val == 'Tow Truck')
                                            style="display:block;"
                                        @else
                                            style="display:none;"
                                        @endif
                                    @endforeach
                                @else
                                    style="display:none;"
                                @endif
                            >
                                <div class="form-group">
                                    <label class="form-label">Tow Truck Price</label>
                                    <input type="number" name="tow_truck_price" id="tow_truck_price" class="form-control"
                                           placeholder="Enter Tow Truck Price..."  value="{{isset($order->storage->tow_truck_price) ? $order->storage->tow_truck_price : ''}}" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Additional</label>
                                    <textarea name="additional" id="additional" class="form-control" placeholder="Enter Additional...">{{isset($order->storage->additional) ? $order->storage->additional : ''}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button id="sv_btn" type="submit" class="btn  btn-primary">SAVE</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row-->
    </form>
@endsection

@section('extraScript')

<script>
    $("#phoneno").mask("(999) 999-9999");
    $("#phoneno2").mask("(999) 999-9999");
    // $("#opentime").mask("99:99");
    // $("#closetime").mask("99:99");
    $('#zip').keyup(function () {
        var o_zip1 = $(this);
        var nav = $(this).parents('.form-group').siblings('.nav');
        if(o_zip1.val() == '')
        {
            nav.children().remove();
            nav.hide();
        }
        else{
            $.ajax({
                url: "{{url('/get_zip')}}",
                type: "GET",
                dataType: "json",
                data: {d_zip1:o_zip1.val()},
                success: function (res){
                    nav.show();
                    nav.children().remove();
                    $.each(res, function (){
                        nav.append(`
                            <li class="nav-item selectAdd">
                                <a class="nav-link text-light" href="javascript:void(0)">${this}</a>
                            </li>
                        `);
                    });
                    $('.selectAdd').click(function(){
                        $('#zip').val($(this).children('a').text());
                        $('.nav').hide();
                        $('.nav').children().remove();
                    })
                    // console.log(res);
                }
            });
        }
        // console.log(d_zip1);
    })
    
    $("#optionv").change(function(){
        $("#forklift_price").parent('div').parent('div').toggle();
    })
    $("#optionv2").change(function(){
        $("#tow_truck_price").parent('div').parent('div').toggle();
    })
    
    $("input[name='companyname']").keyup(function(){
        var name = $(this);
        var nav = $(this).parents('.form-group').siblings('.nav');
        if(name.val() == '')
        {
            $("input[name='companyname']").val('');
            $("input[name='managerownername']").val('');
            $("input[name='caddress']").val('');
            $("input[name='zip']").val('');
            $("input[name='charges']").val('');
            $("input[name='charges2']").val('');
            $("input[name='phoneno']").val('');
            $("input[name='phoneno2']").val('');
            $("input[name='faxno']").val('');
            $("input[name='forklift_price']").val('');
            $("input[name='tow_truck_price']").val('');
            $("textarea[name='additional']").val('');
            
            $("#closetime").children("option").each(function(){
                if($(this).val() == "")
                {
                    $(this).attr("selected",true);
                }
                else
                {
                    $(this).removeAttr("selected");
                }
            })
            $("#opentime").children("option").each(function(){
                if($(this).val() == "")
                {
                    $(this).attr("selected",true);
                }
                else
                {
                    $(this).removeAttr("selected");
                }
            })
            $("#duration").children("option").each(function(){
                if($(this).val() == "")
                {
                    $(this).attr("selected",true);
                }
                else
                {
                    $(this).removeAttr("selected");
                }
            })
            $("#optionv").removeAttr("checked");
            $("#optionv2").removeAttr("checked");
            $("#forklift_price").parent('div').parent('div').hide();
            $("#tow_truck_price").parent('div').parent('div').hide();
            nav.children().remove();
            nav.hide();
        }
        else
        {
            $.ajax({
                url: "{{url('/storage_data')}}",
                type: "GET",
                dataType: "json",
                data: {name:name.val()},
                success: function (res){
                    nav.show();
                    nav.children().remove();
                    $.each(res.data, function (){
                        nav.append(`
                            <li class="nav-item selectCompany">
                                <input type="hidden" class="com_id" value="${this.id}">
                                <a class="nav-link text-light text-capitalize" href="javascript:void(0)">${this.company_name}</a>
                            </li>
                        `);
                    });
                    
                    $(".selectCompany").click(function(){
                        nav.children().remove();
                        nav.hide();
                        var id = $(this).children('.com_id').val();
                        $.ajax({
                            url: "{{url('/storage_data_get')}}",
                            type: "GET",
                            dataType: "json",
                            data: {id:id},
                            success: function (response){
                                // console.log(response);
                                $("input[name='companyname']").val(response.data.company_name);
                                $("input[name='managerownername']").val(response.data.manager_owner_name);
                                $("input[name='caddress']").val(response.data.company_address);
                                $("input[name='zip']").val(response.data.zip);
                                $("input[name='charges']").val(response.data.charges);
                                $("input[name='charges2']").val(response.data.charges2);
                                $("input[name='phoneno']").val(response.data.phoneno);
                                $("input[name='phoneno2']").val(response.data.phoneno2);
                                $("input[name='faxno']").val(response.data.faxno);
                                $("input[name='forklift_price']").val(response.data.forklift_price);
                                $("input[name='tow_truck_price']").val(response.data.tow_truck_price);
                                $("textarea[name='additional']").val(response.data.additional);
                                
                                $("#closetime").children("option").each(function(){
                                    if($(this).val() == response.data.close_time)
                                    {
                                        $(this).attr("selected",true);
                                    }
                                    else
                                    {
                                        $(this).removeAttr("selected");
                                    }
                                })
                                $("#opentime").children("option").each(function(){
                                    if($(this).val() == response.data.open_time)
                                    {
                                        $(this).attr("selected",true);
                                    }
                                    else
                                    {
                                        $(this).removeAttr("selected");
                                    }
                                })
                                $("#duration").children("option").each(function(){
                                    if($(this).val() == response.data.storage_duration)
                                    {
                                        $(this).attr("selected",true);
                                    }
                                    else
                                    {
                                        $(this).removeAttr("selected");
                                    }
                                })
                                var forklift_twotruck = response.data.forklift_twotruck.split(', ');
                                $("#optionv").removeAttr("checked");
                                $("#optionv2").removeAttr("checked");
                                $("#forklift_price").parent('div').parent('div').hide();
                                $("#tow_truck_price").parent('div').parent('div').hide();
                                // console.log(forklift_twotruck);
                                $.each(forklift_twotruck,function (){
                                    if($("#optionv").val() == this)
                                    {
                                        $("#optionv").attr("checked",true);
                                        $("#forklift_price").parent('div').parent('div').show();
                                    }
                                    if($("#optionv2").val() == this)
                                    {
                                        $("#optionv2").attr("checked",true);
                                        $("#tow_truck_price").parent('div').parent('div').show();
                                    }
                                })
                            }
                        })
                    });
                }
            });
        }
    })
    
    $("#storage_charge").keydown(function(event) {
        if( !(event.keyCode == 8                                // backspace
            || event.keyCode == 46                              // delete
            || (event.keyCode >= 35 && event.keyCode <= 40)     // arrow keys/home/end
            || (event.keyCode >= 48 && event.keyCode <= 57)     // numbers on keyboard
            || (event.keyCode >= 96 && event.keyCode <= 105))   // number on keypad
            ) {
                event.preventDefault();     // Prevent character input
        }
    });
    
    $("#change_carrier").change(function(){
        ($(this).val() == 'yes' ? $("#carrier").show() : $("#carrier").hide());
    })
</script>

@endsection

