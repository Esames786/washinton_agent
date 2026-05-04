@extends('layouts.innerpages')
@section('template_title')
    Coupons
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
            text-align:center;
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
                    <h1 class="my-4"><b>Coupons</b></h1>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header">
                    <div class="card-title d-flex justify-content-end w-100">
                        <div class="form-group my-auto">
                            <input type="text" name="search" placeholder="Search" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive" id="searchData">
                            @include('main.coupon.search')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
@endsection

@section('extraScript')
    <script>
        function search(search,page)
        {
            $.ajax({
                url:"{{url('/coupons')}}?page="+page,
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
    </script>
@endsection

