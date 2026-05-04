@extends('layouts.innerpages')
@section('template_title')
    Website Link
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
                    <h1 class="my-4"><b>Websites Links</b></h1>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-header">
                    <div class="card-title d-flex justify-content-between w-100">
                        <div class="form-group my-auto">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop" onclick="update(0)"><i class="fa fa-plus" aria-hidden="true"></i> Add Website Link</button>
                        </div>
                        <div class="form-group my-auto">
                            <input type="text" name="search" placeholder="Search" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive" id="searchData">
                            @include('main.website-link.search')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Create Website Link</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="0" />
                    <div class="form-group">
                        <label class="form-label">Website Name</label>
                        <input type="text" name="website_name" id="website_name" class="form-control mb-2" placeholder="Enter Website Name" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Website Link</label>
                        <input type="text" name="website_link" id="website_link" class="form-control mb-2" placeholder="Enter Website Link" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control mb-2" id="status">
                            <option selected disbaled value="">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Not active</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submit">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
    <script>
        function search(search,page)
        {
            $.ajax({
                url:"{{url('/website-links')}}?page="+page,
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
        
        function update(id)
        {
           $("#id").val(0);
           $("#website_name").val('');
           $("#website_link").val('');
           $("#status").children("option").attr("selected",false);
           $("#status").children("option[value='']").attr("selected",true);
            $.ajax({
                url:"{{ url('/website-links/edit') }}",
                type:"GET",
                data:{id:id},
                dataType:"json",
                success:function(res)
                {
                   if(res.status_code == 200)
                   {
                       $("#id").val(`${res.data.id}`);
                       $("#website_name").val(`${res.data.name}`);
                       $("#website_link").val(`${res.data.link}`);
                       $("#status").children(`option[value='${res.data.status}']`).attr("selected",true);
                   }
                }
            });
        }
        
        $("#submit").click(function(e){
            e.preventDefault();
             var id = $("#id").val();
             var website_name = $("#website_name");
             var website_link = $("#website_link");
             var status = $("#status");
             website_name.parent('.form-group').children('.alert').remove();
             website_link.parent('.form-group').children('.alert').remove();
             status.parent('.form-group').children('.alert').remove();
             
             $.ajax({
                 url:"{{ url('/website-links/create') }}",
                 type:"POST",
                 data:{website_name:website_name.val(),website_link:website_link.val(),status:status.val(),id:id},
                 dataType:"json",
                 success:function(res)
                 {
                     if(res.status_code === 400)
                     {
                        if(res.error.website_name)
                        {
                            website_name.parent('.form-group').append(`
                                <div class="alert bg-danger text-light alert-dismissible fade show" role="alert">
                                  <strong>${res.error.website_name[0]}</strong>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div> 
                            `);
                        }
                        if(res.error.website_link)
                        {
                            website_link.parent('.form-group').append(`
                                <div class="alert bg-danger text-light alert-dismissible fade show" role="alert">
                                  <strong>${res.error.website_link[0]}</strong>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div> 
                            `);
                        }
                        if(res.error.status)
                        {
                            status.parent('.form-group').append(`
                                <div class="alert bg-danger text-light alert-dismissible fade show" role="alert">
                                  <strong>${res.error.status[0]}</strong>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div> 
                            `);
                        }
                     }
                     else
                     {
                         $('#staticBackdrop').modal('hide');
                         location.reload(true);
                     }
                 }
             });
        });
    </script>
@endsection

