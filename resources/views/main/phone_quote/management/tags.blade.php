@extends('layouts.innerpages')

@section('template_title')
   Tags Guide
@endsection
@include('partials.mainsite_pages.return_function')


@section('content')



    <div class="slim-mainpanel" style=" padding-left: 0px !important; ">
        <div class="container">
            <div class="slim-pageheader pl-0">
                <ol class="breadcrumb slim-breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/guides/">Guides</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tags</li>
                </ol>
                <h6 class="slim-pagetitle">Tags Guide</h6>
            </div><!-- slim-pageheader -->

            <div class="section-wrapper">
                <label class="section-title">Basic Tags</label>
                <p class="mg-b-10 mg-sm-b-10">These tags are used on every status page.</p>

                <table class="table table-reference">
                    <thead>
                    <tr>
                        <th class="wd-30p">Tag</th>
                        <th class="wd-70p">Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><span class="badge badge-orange ml-1 tx-white">Website</span></td>
                        <td>This tag defines that the Quote is generated from Website by Customer</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-orange ml-1 tx-white">Phone</span></td>
                        <td>This tag defines that the Quote is generated from Order On Phone by ShipA1 Agent</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-warning ml-1 tx-white">Car</span> | <span class="badge badge-warning ml-1 tx-white">Motorcycle</span> | <span class="badge badge-danger ml-1 tx-white">Heavy Equipment</span></td>
                        <td>These tags define the category of quote for the shipment of Car, Motorcycle or Heavy</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-success">New Customer</span> | <span class="badge badge-success">Old Customer</span></td>
                        <td>These tags define about the Customers whether they are OLD or NEW. <b>OLD Customers</b> are previous shipper, dealers etc which used to ship their vehicles from us.</td>
                    </tr>
                    </tbody>
                </table>
            </div><!-- section-wrapper -->

            <div class="section-wrapper mg-t-20">
                <label class="section-title">Basic Status Tags</label>
                <p class="mg-b-10 mg-sm-b-10">These tags are used to show the current status of the order.</p>

                <table class="table table-reference">
                    <thead>
                    <tr>
                        <th class="wd-30p">Tag</th>
                        <th class="wd-70p">Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><span class="badge badge-orange ml-1 tx-white">New Order</span></td>
                        <td>This tag defines that the order is New and available in <a href="/new">New Orders</a> folder.</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-warning ml-1 tx-white">Booked Order</span></td>
                        <td>This tag defines that the order is Booked and available in <a href="/booked">Booked Orders</a> folder.</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-primary ml-1 tx-white">Payment Missing Order</span></td>
                        <td>This tag defines that the order payment is not yet paid and available in <a href="/payment_missing">Payment Missing</a> folder.</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-pink ml-1 tx-white">Listed Order</span></td>
                        <td>This tag defines that the order is either Posted or Not posted on Central Schedule. It is in <a href="/listed">Listed</a> folder.</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-success ml-1 tx-white">Scheduled Order</span></td>
                        <td>This tag defines that the shipment is successfully Scheduled and available in <a href="/dispatch">Scheduled</a> folder.</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-dark ml-1 tx-white">Pickedup Order</span></td>
                        <td>This tag defines that the shipment is picked up and available in <a href="/picked_up">Picked Up</a> folder.</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-amber ml-1 tx-white">Delivered Order</span></td>
                        <td>This tag defines that the shipment is Delivered and available in <a href="/delivered">Delivered</a> folder.</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-teal ml-1 tx-white">Completed Order</span></td>
                        <td>This tag defines that the shipment is Completed and available in <a href="/completed">Completed</a> folder.</td>
                    </tr>

                    </tbody>
                </table>
            </div><!-- section-wrapper -->
            <div class="section-wrapper mg-t-20">
                <label class="section-title">Order Action Buttons</label>
                <p class="mg-b-10 mg-sm-b-10">These Buttons are used to show, change, edit or delete the current order.</p>

                <table class="table table-reference">
                    <thead>
                    <tr>
                        <th class="wd-30p">Tag</th>
                        <th class="wd-70p">Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><span class="btn-facebook p-1 badge  fa fa-eye" >&nbsp; Order Histroy
                            </span></td>
                        <td>This button is used to open a modal to view and edit order history.</td>
                    </tr>
                    <tr>
                        <td>
                            <span  class="btn-twitter p-1 badge  fa fa-edit" >
                                    &nbsp; Edit Data
                            </span>
                        </td>
                        <td>This button is used to update the order details.</td>
                    </tr>
                    <tr>
                        <td>
                            <span  class="btn-info p-1 badge  fa fa-print" >
                                    &nbsp; Print Summary
                            </span>
                        </td>
                        <td>This button is used to print order details.</td>
                    </tr>
                    <tr>
                        <td>
                            <span class="btn-twitter p-1 badge  las la-inbox header-icons">
                                &nbsp; Send Payment Link To Customer!
                            </span>
                        </td>
                        <td>This button is used to send email to the customers.</td>
                    </tr>
                    <tr>

                        <td>
                            <span   class="btn-youtube p-1 badge  fa fa-trash">
                                &nbsp; Delete Order!
                            </span>
                        </td>
                        <td>This button is used to move the order to the deleted order portion.</td>
                    </tr>
                    <tr>
                        <td>
                            <span  class="btn-info p-1 badge  fa fa-map-marker" >

                                        &nbsp;   Location

                            </span>
                        </td>
                        <td>This button is used to view the pickup and delivery location.</td>
                    </tr>
                    <tr>
                        <td>
                            <span  class="btn-info p-1 badge  fa fa-money" >
                                        Continue To Payment
                            </span>
                        </td>
                        <td>This button is used to add payment details.</td>
                    </tr>
                    <tr>
                        <td>
                            <span  class="btn-danger p-1 badge  fa fa-dollar" >
                                        Ows Money!
                            </span>
                        </td>
                        <td>This button is used to update the Ows Money payment </td>
                    </tr>
                    <tr>
                        <td>
                            <span  class="btn-success p-1 badge  fa-thumbs-up" >
                                        Payment Confirmation!
                            </span>
                        </td>
                        <td>This button is used to update the status of order payment.</td>
                    </tr>
                    </tbody>
                </table>
            </div><!-- section-wrapper -->

            <div class="section-wrapper mg-t-20">
                <label class="section-title">Order Status Tags</label>
                <p class="mg-b-10 mg-sm-b-10">These tags are used to show the current status of the order.</p>

                <table class="table table-reference">
                    <thead>
                    <tr>
                        <th class="wd-30p">Tag</th>
                        <th class="wd-70p">Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><span class="badge badge-warning tx-white">Payment Missing</span></td>
                        <td>This tag defines that the order is Booked but the payment for this order is still pending.</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-danger tx-white">Signature Missing</span></td>
                        <td>This tag defines that the new order or payment is updated and customer not signed the contract that is sent via email.</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-warning tx-white">COD / COP</span></td>
                        <td>This tag defines that the order payment process is set to Cash on Delivery or Cash on Pickup.</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-dark tx-white">(Confirm)</span> | <span class="badge badge-dark tx-white">(Not Confirm)</span></td>
                        <td>This tag is combined with COD / COP which defines that the Agent confirmed or not confirmed that the customer will surely pay for the order.</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-danger tx-white">Waiting for Owes Money <br>Confirmation</span></td>
                        <td>This tag defines that the order payment is not yet confirmed by Owes Money Agent. Additionally you cannot Mark the order as Delivered until Owes Money Agent confirm the payment.</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-success tx-white">Owes Money Confirmed</span></td>
                        <td>This tag defines that the Owes Money Agent confirmed the payment of the order.</td>
                    </tr>
                    </tbody>
                </table>
            </div><!-- section-wrapper -->

            <div class="section-wrapper mg-t-20">
                <label class="section-title">Updating / Informative / Required / Other Tags</label>
                <p class="mg-b-10 mg-sm-b-10">These tags are could be usefull for Agent.</p>

                <table class="table table-reference">
                    <thead>
                    <tr>
                        <th class="wd-30p">Tag</th>
                        <th class="wd-70p">Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><a href="javascript:void(0)" class="badge badge-dark ml-1 tx-white">Need Title <i class="fa fa-upload" aria-hidden="true"></i></a></td>
                        <td>This tag is visible because you marked the vehicle as "Need Title" but you have not uploaded the title. You can click on the tag to upload title of that particular order.</td>
                    </tr>
                    <tr>
                        <td><a href="javascript:void(0)" class="badge badge-dark ml-1 tx-white">Need Dock Receipt <i class="fa fa-upload" aria-hidden="true"></i></a></td>
                        <td>This tag is visible because the order is for PORT and you have not uploaded the Dock Receipt. You can click on the tag to upload Dock Receipt of that particular order.</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-danger tx-white">Payment Updated</span></td>
                        <td>This tag defines that the payment is updated by Agent but confirmation is still needed.</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-success tx-white">Payment Received</span></td>
                        <td>This tag defines that the payment is received and charged successfully.</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-dark tx-white">Carrier Pay</span> | <span class="badge badge-dark tx-white">Company Pay</span></td>
                        <td>This tag is combined with "Payment Received" which defines that either Carrier or Company pay the balance amount of the order.</td>
                    </tr>
                    <tr>
                        <td><span class="badge badge-dark tx-white">No Payment Received</span></td>
                        <td>This tag defines that the payment is not updated or charged for that order.</td>
                    </tr>
                    </tbody>
                </table>
            </div><!-- section-wrapper -->



        </div><!-- container -->


    </div>

    <style>
        a{
            color: #007bff;
        }
        .tx-white{
            color: white !important;
        }
        .badge-orange {
            color: #212529;
            background-color: #F49917;
        }
        .section-wrapper {
            border: 1px solid #ced4da;
            background-color: #fff;
            padding: 20px;
        }
        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #343a40;
            text-transform: uppercase;
            margin-top: 20px;
            display: block;
            letter-spacing: 1px;
        }
        .mg-sm-b-10 {
            margin-bottom: 10px;
        }
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }
        .table-reference {
            margin-bottom: 0;
        }
        .table-reference thead tr th, .table-reference thead tr td {
            background-color: transparent;
            border: 1px solid #ced4da;
            border-bottom: 0;
        }
        .table-reference tbody tr th, .table-reference tbody tr td {
            border: 1px solid #ced4da;
        }
        .table th, .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .badge-success {
            color: #fff;
            background-color: #23BF08;
        }
        .badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 3px;
        }
        .wd-30p {
            width: 30%;
        }
        .wd-70p {
            width: 70%;
        }
        .newordertab {
            font-size: 40px;
            position: absolute;
            top: -20px;
            right: 0;
        }
        .badge-amber {
            background: #FF6F00;
        }
        .badge-teal {
            background: #004D40;
        }
        .badge-pink {
            background: #E91E63;
        }
        .slim-pagetitle {
            margin-top: 15px;
            margin-bottom: 10px;
            color: #343a40;
            padding-left: 10px;
            border-left: 4px solid #4662D4;
            text-transform: uppercase;
            font-weight: 700;
            font-size: 18px;
            line-height: 18px;
            letter-spacing: .5px;
        }
        .tx-inverse {
            color: #343a40;
        }
        .mg-b-20 {
            margin-bottom: 20px;
        }
        .slim-pageheader {
            padding: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-direction: row-reverse;
        }
        .pd-40 {
            padding: 40px;
        }

        .card-info {
            text-align: center;
        }
        .mg-b-30 {
            margin-bottom: 30px;
        }
    </style>


@endsection

@section('extraScript')

    <script>

    </script>

@endsection

