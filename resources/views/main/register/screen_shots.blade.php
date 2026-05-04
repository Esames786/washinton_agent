@extends('layouts.innerpages')
@section('template_title')
    {{ $data->slug ?? $data->name.' '.$data->last_name }} Screen Shots
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
                    <h1 class="my-4"><b>{{ $data->slug ?? $data->name.' '.$data->last_name }} Screen Shots</b></h1>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header">
                    <div class="card-title">
                        <?php 
                            $min_date = date('Y').'-'.(date('m') - 1).'-'.date('d');
                            if(date('m') == 1)
                            {
                                $min_date = date('Y')-1 .'-12-'. date('d');
                            }
                        ?>
                        <span class="text-secondary">Check Previous Screen Shots</span><input type="date" min="{{$min_date}}" max="{{date('Y-m-d')}}" id="previous_ss" name="previous_ss" class="form-control" value="{{ date('Y-m-d')}}" />
                        <input type="hidden" id="user_id" value="{{$data->id}}" />
                    </div>
                </div>
                <div class="card-body">
                    <div id="data-ss">
                        @include('main.register.show_screen_shots')
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
        function data(previous_ss,user_id,page)
        {
            $.ajax({
                url:"{{ url('/user-ss') }}?page="+page,
                type:"POST",
                data:{created_at:previous_ss,user_id:user_id},
                beforeSend:function(msg)
                {
                    $('#data-ss').html('');
                    $('#data-ss').append(`<div class="lds-hourglass" id='ldss'></div>`);  
                },
                success:function(res)
                {
                    // console.log(res);
                    $("#data-ss").html('');
                    $("#data-ss").html(res);
                }
            });
        }
    
        $("#previous_ss").change(function(){
            var previous_ss = $(this).val();
            var user_id = $(this).siblings("#user_id").val();
            data(previous_ss,user_id,1);
        })
        
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var previous_ss = $("#previous_ss").val();
            var user_id = $("#user_id").val();
            data(previous_ss,user_id,page);
        });
    </script>
@endsection