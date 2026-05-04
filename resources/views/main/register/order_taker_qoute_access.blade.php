@extends('layouts.innerpages')
@section('template_title')
    Order Taker Qoute Access
@endsection
@section('content')
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

        /* Go from zero to full opacity */
        @keyframes fadeEffect {
            from {opacity: 0;}
            to {opacity: 1;}
        }
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
                    <h1 class="my-4"><b>Order Taker Qoute Access</b></h1>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive">
                            <!-- Tab links -->
                            <div class="tab">
                                <button class="tablinks" onclick="openCity(event, 'Order_Taker')" id="defaultOpen">Order Taker ({{ count($order_taker) }})</button>
                            </div>

                            <div id="Order_Taker" class="tabcontent">
                                <table id="example4" class="table table-bordered table-striped key-buttons" style="width: 100% !important;">
                                    <thead>
                                    <tr>
                                        <th class="border-bottom-0">JOINING DATE</th>
                                        <th class="border-bottom-0">NAME</th>
                                        <th class="border-bottom-0">ROLE</th>
                                        <th class="border-bottom-0">Qoute Access</th>
                                        <th class="border-bottom-0">EDIT</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $order_taker as $val)
                                        <tr>
                                            <td>{{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}</td>
                                            <td class="d-flex">
                                                <span class="rounded-circle p-1 badge badge-{{$val->is_login == 1 ? 'success' : 'danger'}} my-auto mr-1" style="display: block;width:0;height: 1px;"></span>
                                                {{$val->name}}
                                                @if($val->slug)
                                                 ({{$val->slug}})
                                                @endif
                                            </td>
                                            <td>{{ get_role($val->role,'name')}}</td>
                                            <td>
                                                @if(isset($val->qoute_access))
                                                    @if($val->qoute_access->status == 1)
                                                        {{count($val->qoute_access->member_ids) > 1 ? count($val->qoute_access->member_ids).' Order Takers Qoutes' : count($val->qoute_access->member_ids).' Order Taker Qoutes'}}
                                                    @else
                                                        All Qoutes
                                                    @endif
                                                @else
                                                    All Qoutes
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ url('/order_taker_qoute_access_update/'.$val->id) }}" class="btn btn-primary">Edit Panel Access</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
            <!--/div-->
        </div>
    </div>
    <!-- /Row -->
@endsection

@section('extraScript')
    <script>
        $(document).ready(function() {
            $('#example6').DataTable();
            $('#example5').DataTable();
            $('#example4').DataTable();
            $('#example3').DataTable();
        });
        document.getElementById("defaultOpen").click();
        function openCity(evt, cityName) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
@endsection
