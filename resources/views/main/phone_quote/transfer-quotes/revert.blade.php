@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim('Revert Quotes','/'))}}
@endsection
@section('content')
<style>
    .tableh th {
            border: 1px solid #ddd;
    background: #077199;
       color: #fff !important;
    font-size: 16px;
    }
     .tableh td {
           font-weight:500;
    }
    .btn-show {
        display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-bottom: 10px;
    margin: 10px;
    margin: 10px 0;
    border-bottom: 1px solid #ddd;
    }
    
    .btn-show button {
        border: 1px solid #fff;
        padding: 7px  15px;
        border-radius: 9px;
    }
     .col label {
        text-align: left;
        display: block;
    }

</style>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    @include('partials.mainsite_pages.return_function')
    
    
    @include('main.phone_quote.question.style')
    @php
    $check_panel = check_panel();

    if($check_panel == 1){

    $phoneaccess=explode(',',Auth::user()->emp_access_phone);
    }
    elseif($check_panel == 3)
    {
        $phoneaccess = explode(',',Auth::user()->emp_access_test);
    }
    else{
    $phoneaccess=explode(',',Auth::user()->emp_access_web);
    }
    @endphp
    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <input type="hidden" value="{{trim("$_SERVER[REQUEST_URI]",'/')}}" id="titlee">-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Home</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Questions</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Revert {{$ouser->slug ?? $ouser->name.' '.$ouser->last_name}} Quotes To Him</b></h1>
        </div>
    </div>
    <div class="tabMainbody">
        <form action="{{ url('/revert-the-quotes') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger float-right" style="margin-right: 35px;">Revert</button>
            <div class="ChatViewMain" style="width:95%;">
                <table>
                    <thead>
                        <tr>
                            <th class="box">#</th>
                            <th class="box">Order ID#</th>
                            <th class="box">Old Status</th>
                            <th class="box">New Status</th>
                            <!--<th class="box">Action</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $value)
                            <tr>
                                <td>
                                    <input type="checkbox" value="{{$value->id}}" name="transfer_quote_id[]">
                                </td>
                                <td>
                                    {{$value->order_id}}
                                </td>
                                <td>
                                    {{get_pstatus($value->pstatus)}}
                                </td>
                                <td>
                                    {{get_pstatus($value->order->pstatus)}}
                                </td>
                                <!--<td>-->
                                    
                                <!--</td>-->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-between">
                    <div class="text-secondary my-auto">
                        Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} from total {{$data->total()}} entries
                    </div>
                    <div>
                        {{  $data->links() }}
                    </div>
                    
                </div>
            </div>
        </form>
    </div>
@endsection