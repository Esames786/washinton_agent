@extends('layouts.innerpages')

@section('template_title')
{{  ucfirst(trim("$_SERVER[REQUEST_URI]",'/'))}}
@endsection

@section('content')




    <style>
        select.custom-select.custom-select-sm.form-control.form-control-sm {
            height: 29px;
        }
    </style>
        <!--/app header-->                                                <!--Page header-->
<div class="page-header">
    <!--<div class="page-leftheader">-->
    <!--    <h4 class="page-title mb-0">{{  ucfirst(trim("$_SERVER[REQUEST_URI]",'/'))}}</h4>-->
    <!--    <input type="hidden" value="{{trim("$_SERVER[REQUEST_URI]",'/')}}" id="titlee">-->
    <!--    <ol class="breadcrumb">-->
    <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Tables</a>-->
    <!--        </li>-->
    <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">Customers</a></li>-->
    <!--    </ol>-->
    <!--</div>-->
    <div class="text-secondary text-center text-uppercase w-100">
        <h1 class="my-4"><b>Credit Card List</b></h1>
    </div>

</div>
<!--End Page header-->
<!-- Row -->

    <form name="passform" action="/credit_card_list2" method="post">
        @csrf
        Enter Password:
        <input type="password" name="pass" style=" width: 13rem; display: inline-block;height: 2.4rem;top: 2px;position: relative;" class="form-control" />
        <input type="submit" name="checkpassword" value="Go" class="btn btn-primary" />
    </form>
    <br>

    
<div class="row">
    <div class="col-12">

        @if(session('flash_message'))
            <div class="alert alert-success">
                {{session('flash_message')}}
            </div>
            @endif
                    <!--div-->
            <div class="card">

                <div class="card-body">
                    @if($display == 'yes')
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="search" placeholder="Search" />
                    </div>
                    <br>
                    @endif
                    <div id="table_data">

                        @include('main.phone_quote.credit_card.load')
                    </div>
                </div>
            </div>

    </div>

</div><!-- end app-content-->






@endsection

@section('extraScript')

    <script>
        $(document).ready(function () {

            $(document).on('click', '.pagination a', function (event) {

                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data3(page,'');
                // $.cookie("page", page, { expires: 1 });

            });

            function fetch_data3(page,search) {

                $('#table_data').html('');
                $('#table_data').append(`<div class="lds-hourglass" id='ldss'></div>`);

                $.ajax({

                    url: "credit_card_list?page=" + page,
                    data:{search:search},
                    success: function (data) {
                        $('#table_data').html('');
                        $('#table_data').html(data);

                    },
                    complete: function (data) {
                        $('#ldss').hide();
                        regain();
                    }

                })

            }
            
            $("#search").keypress(function(e){
                if(e.which == 13)
                {
                    fetch_data3(1,$(this).val())
                }
            })
            // let cookie = $.cookie("page");
            // if(cookie)
            // {
            //     fetch_data3(cookie);
            //     $.removeCookie("page");
            // }

        });
    </script>


    <!--Scrolling Modal-->

@endsection


