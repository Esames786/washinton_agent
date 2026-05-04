@extends('layouts.innerpages')

@section('template_title')
    Sheet List
    @endsection

    @section('content')
            <!-- Row -->
    @include('partials.mainsite_pages.return_function')

    <div class="page-header">
        <!--<div class="page-leftheader">-->
        <!--    <h4 class="page-title mb-0">DAY COUNT</h4>-->
        <!--    <ol class="breadcrumb">-->
        <!--        <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Tables</a>-->
        <!--        </li>-->
        <!--        <li class="breadcrumb-item active" aria-current="page"><a href="#">DAY COUNT</a></li>-->
        <!--    </ol>-->
        <!--</div>-->
        <!--<div class="page-rightheader">-->
        <!--    <div class="btn btn-list">-->


        <!--    </div>-->
        <!--</div>-->
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Sheet List</b></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            @if(session('flash_message'))
                <div class="alert alert-success">
                    {{session('flash_message')}}
                </div>
                @endif
                        <!--div-->
                <div class="card mt-5">
                    <div class="card-header">
                        <form method="post" action="{{url('create_sheet')}}">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Sheet Name</label>
                                    <input name="sheet_name">
                                </div>
                                <div class="col-md-3 ">
                                    <label></label>
                                    <input type="submit" value="Create Report" class="btn btn-info">
                                </div>
                            </div>

                        </form>

                    </div>
                    <div class="card-body">
                        <div class="">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped text-nowrap key-buttons">
                                    <thead>
                                    <tr>
                                       <th class="border-bottom-0">ID</th>
                                       <th class="border-bottom-0">Sheets</th>
                                       <th class="border-bottom-0">Date</th>
                                       <th class="border-bottom-0">View & Edit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($sheet_data as $sheets)
                                      <tr>
                                          <td>
                                              {{$sheets->id}}
                                          </td>
                                         <td>
                                            Sheet-{{\Carbon\Carbon::parse($sheets->created_at)->format('M')}} ({{ $sheets->sheet_name }})
                                          </td>
                                          <td>
                                            {{\Carbon\Carbon::parse($sheets->created_at)->format('M,d Y h:i A')}}
                                           </td>
                                          <td>
                                              <button type="button" data-toggle="tooltip" data-placement="top" title="Sheet!"
                                                      class="btn btn-primary btn-sm btn-sm">
                                                  <a href="{{url('sheet_data/').'/'.$sheets->id}}">
                                                      <i class="fa fa-edit"></i>
                                                  </a>
                                              </button>
                                          </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/div-->

        </div>
    </div>
    <!-- /Row -->



@endsection

@section('extraScript')


@endsection

