@extends('layouts.innerpages')
@section('template_title')
    Group Members
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
            text-align:center !important;
            vertical-align:middle !important;
        }
        .savebtn{
            position: absolute;
            top: 5.8%;
            right: 13.5%;
            padding: 0px 10px;
            color: #fff !important;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            height: 23px;
        }
        input[name='assign_daily_qoute']
        {
            border-radius: 4px;
            border: 2px solid #7676;
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
                    <h1 class="my-4"><b>{{$manager->slug}} Manager Group</b></h1>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header">
                    <div class="card-title d-flex justify-content-{{Auth::user()->userRole->name == 'Manager' ? 'end' : 'between'}} w-100">
                        @if(Auth::user()->userRole->name <> 'Manager')
                            <div class="form-group my-auto">
                                <a href="{{url('/manager')}}" class="btn btn-info" style="border-radius: 50px !important;">Back</a>
                            </div>
                        @endif
                        <div class="form-group my-auto">
                            <input type="text" name="search" placeholder="Search" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive" id="searchData">
                            @include('main.manager-group.search2')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
    <div class="modal fade" id="activeCalling" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="activeCallingLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="activeCallingLabel">Active Calling Button</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{url('/managers-group/calling-button')}}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="user_id"  />
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label">From Date</label>
                                <input type="date" placeholder="YYYY/MM/DD" name="from_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-label">To Date</label>
                                <input type="date" placeholder="YYYY/MM/DD" name="to_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group d-flex mb-0">
                                <input type="radio" name="calling_status" class="form-control w-auto" value="1">
                                <label class="form-label my-auto mx-2">Active</label>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group d-flex mb-0">
                                <input type="radio" name="calling_status" class="form-control w-auto" value="0">
                                <label class="form-label my-auto mx-2">Deactive</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
    <script>
        function userId(id,status)
        {
            $("input[name='user_id']").val(id);
            $("input[name='calling_status']").attr("checked",false);
            if(status == 0)
            {
                $("input[name='calling_status']").eq(1).attr("checked",true);
            }
            if(status == 1)
            {
                $("input[name='calling_status']").eq(0).attr("checked",true);
            }
        }
    
        function search(search,page)
        {
            $.ajax({
                url:"{{url('/managers-group/'.$manager->id)}}?page="+page,
                type:"GET",
                data:{search:search},
                beforeSend: function () {
                    $('#searchData').html("");
                    $('#searchData').append(`<div class="lds-hourglass" id='ldss'></div>`);
                },
                success:function(res)
                {
                   $("#searchData").html("");
                   $("#searchData").html(res);
                }
            });
        }
        
        $("input[name='search']").keypress(function(e){
            if(e.which == 13)
            {
                search($(this).val(),1);
            }
        })
        
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            var search22 = $("input[name='search']").val();
            var page = $(this).attr('href').split('page=')[1];
            search(search22,page);
        });
        
        $("input[name='assign_daily_qoute']").keypress(function(e){
            
            var x = e.which || e.keycode;
            if ((x >= 48 && x <= 57) || x == 8 ||
                (x >= 35 && x <= 40) || x == 46)
                return true;
            else
                return false;
        })
    </script>
@endsection

