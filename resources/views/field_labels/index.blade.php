@extends('layouts.app')

@section('content')
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
  <!-- Link to the DataTables CSS file -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <!-- Link to the DataTables JavaScript file -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<style>
    .width-container {
        width:1420;
    }
    .tab-content > .tab-pane {
      display: none;
    }

    .tab-content > .active {
      display: block;
     }

</style>
    <div class="container width-container">
        <h1>Field Labels</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!--<button type="button" class="btn btn-primary" id="vehicle-tab" data-toggle="tab" href="#vehicle" role="tab"-->
        <!--                aria-controls="vehicle" aria-selected="false">Vehicle-->
        <!--    {{-- <input hidden type="text" class="fieldID" value="{{ $row->id }}">-->
        <!--    <input hidden type="text" class="fieldName" value="{{ $row->name }}"> --}}-->
        <!--</button>-->
        <!--<button type="button" class="btn btn-primary" id="heavy-tab" data-toggle="tab" href="#heavy" role="tab"-->
        <!--                aria-controls="heavy" aria-selected="false">Heavy-->
        <!--    {{-- <input hidden type="text" class="fieldID" value="{{ $row->id }}">-->
        <!--    <input hidden type="text" class="fieldName" value="{{ $row->name }}"> --}}-->
        <!--</button>-->
        <!--<button type="button" class="btn btn-primary" id="frieght-tab" data-toggle="tab" href="#frieght" role="tab"-->
        <!--                aria-controls="frieght" aria-selected="false">Frieght-->
        <!--    {{-- <input hidden type="text" class="fieldID" value="{{ $row->id }}">-->
        <!--    <input hidden type="text" class="fieldName" value="{{ $row->name }}"> --}}-->
        <!--</button>-->
        <!--<button type="button" class="btn btn-primary" id="sidebar-tab" data-toggle="tab" href="#sidebar" role="tab"-->
        <!--                aria-controls="sidebar" aria-selected="false">Sidebar / Pages-->
        <!--    {{-- <input hidden type="text" class="fieldID" value="{{ $row->id }}">-->
        <!--    <input hidden type="text" class="fieldName" value="{{ $row->name }}"> --}}-->
        <!--</button>-->
               <ul class="nav nav-tabs" id="myTabs">
                    <li class="nav-item">
                        <a class="nav-link" id="all-tab" data-toggle="tab" href="#all" role="tab"
                          aria-controls="all" aria-selected="false">All</a>
                  </li>
                    <li class="nav-item">
                        <a class="nav-link" id="vehicle-tab" data-toggle="tab" href="#vehicle" role="tab"
                          aria-controls="vehicle" aria-selected="false">Vehicle</a>
                  </li>
                  <li class="nav-item">
                        <a class="nav-link" id="heavy-tab" data-toggle="tab" href="#heavy" role="tab"
                          aria-controls="heavy" aria-selected="false">Heavy</a>
                  </li>
                  <li class="nav-item">
                        <a class="nav-link" id="frieght-tab" data-toggle="tab" href="#frieght" role="tab"
                          aria-controls="frieght" aria-selected="false">Frieght</a>
                  </li>
                  <li class="nav-item">
                        <a class="nav-link" id="sidebar-tab" data-toggle="tab" href="#sidebar" role="tab"
                          aria-controls="sidebar" aria-selected="false">Sidebar</a>
                  </li>

        <!--<button type="button" class="btn btn-primary get-data left" data-toggle="modal" data-target="#exampleModal">Add new-->
        <!--    {{-- <input hidden type="text" class="fieldID" value="{{ $row->id }}">-->
        <!--    <input hidden type="text" class="fieldName" value="{{ $row->name }}"> --}}-->
        <!--</button>-->
             </ul>
             <div class="tab-content">
         <div class="tab-pane table-responsive active" id="all" role="tabpanel" aria-labelledby="all-tab">
            <form action="{{ route('field_labels.update') }}" method="post">
                @csrf
                <!--text-nowrap-->
                <table  id="myTable" class="table table-striped table-bordered labelDatatable">
                    <thead  >
                        <tr >
                            <th class="border-bottom-0">Sr. #</th>
                            <th class="border-bottom-0">Name</th>
                            <th class="border-bottom-0">Num To Show</th>
                            <th class="border-bottom-0">Display</th>
                            <th class="border-bottom-0">Old Display</th>
                            <th class="border-bottom-0">Description</th>
                            <th class="border-bottom-0">Status</th>
                            <th class="border-bottom-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fieldLabel as $key => $row)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->id . ' - 1' }}</td>
                                <td>{{ $row->display }}</td>
                                <td>{{ $row->old_display }}</td>
                                <td>{{ $row->description }}</td>
                                <td>
                                    @if ($row->status == 1)
                                        Active
                                    @else
                                        Inactive
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary get-data" data-toggle="modal"
                                        data-target="#exampleModal2">Edit
                                        <input hidden type="text" class="fieldID" value="{{ $row->id }}">
                                        <input hidden type="text" class="fieldName" value="{{ $row->name }}">
                                        <input hidden type="text" class="fieldDisplay" value="{{ $row->display }}">
                                        <input hidden type="text" class="fieldDesc" value="{{ $row->description }}">
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
         <div class="tab-pane table-responsive" id="vehicle" role="tabpanel" aria-labelledby="vehicle-tab">
            <form action="{{ route('field_labels.update') }}" method="post">
                @csrf
                <!--text-nowrap-->
                <table  id="myTable" class="table table-striped table-bordered labelDatatable">
                    <thead  >
                        <tr >
                            <th class="border-bottom-0">Sr. #</th>
                            <th class="border-bottom-0">Name</th>
                            {{-- <th class="border-bottom-0">Num To Show</th> --}}
                            <th class="border-bottom-0">Display</th>
                            <th class="border-bottom-0">Old Display</th>
                            <th class="border-bottom-0">Description</th>
                            <th class="border-bottom-0">Status</th>
                            {{-- <th class="border-bottom-0">Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vehicle as $key => $row)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $row->name }}</td>
                                {{-- <td>{{ $row->id - 1 }}</td> --}}
                                <td>{{ $row->display }}</td>
                                <td>{{ $row->old_display }}</td>
                                <td>{{ $row->description }}</td>
                                <td>
                                    @if ($row->status == 1)
                                        Active
                                    @else
                                        Inactive
                                    @endif
                                </td>
                                {{-- <td>
                                    <button type="button" class="btn btn-primary get-data" data-toggle="modal"
                                        data-target="#exampleModal2">Edit
                                        <input hidden type="text" class="fieldID" value="{{ $row->id }}">
                                        <input hidden type="text" class="fieldName" value="{{ $row->name }}">
                                        <input hidden type="text" class="fieldDisplay" value="{{ $row->display }}">
                                        <input hidden type="text" class="fieldDesc" value="{{ $row->description }}">
                                    </button>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
         <div class="tab-pane table-responsive" id="heavy" role="tabpanel" aria-labelledby="heavy-tab">
            <form action="{{ route('field_labels.update') }}" method="post">
                @csrf
                <!--text-nowrap-->
                <table id="myTable1" class="table table-striped table-bordered labelDatatable">
                    <thead>
                        <tr>
                            <th class="border-bottom-0">Sr. #</th>
                            <th class="border-bottom-0">Name</th>
                            {{-- <th class="border-bottom-0">Num To Show</th> --}}
                            <th class="border-bottom-0">Display</th>
                            <th class="border-bottom-0">Old Display</th>
                            <th class="border-bottom-0">Description</th>
                            <th class="border-bottom-0">Status</th>
                            {{-- <th class="border-bottom-0">Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($heavy as $key => $row)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $row->name }}</td>
                                {{-- <td>{{ $row->id - 1 }}</td> --}}
                                <td>{{ $row->display }}</td>
                                <td>{{ $row->old_display }}</td>
                                <td>{{ $row->description }}</td>
                                <td>
                                    @if ($row->status == 1)
                                        Active
                                    @else
                                        Inactive
                                    @endif
                                </td>
                                {{-- <td>
                                    <button type="button" class="btn btn-primary get-data" data-toggle="modal"
                                        data-target="#exampleModal2">Edit
                                        <input hidden type="text" class="fieldID" value="{{ $row->id }}">
                                        <input hidden type="text" class="fieldName" value="{{ $row->name }}">
                                        <input hidden type="text" class="fieldDisplay" value="{{ $row->display }}">
                                        <input hidden type="text" class="fieldDesc" value="{{ $row->description }}">
                                    </button>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
         <div class="tab-pane table-responsive" id="frieght" role="tabpanel" aria-labelledby="frieght-tab">
            <form action="{{ route('field_labels.update') }}" method="post">
                @csrf
                <!--text-nowrap-->
                <table id="myTable2" class="table table-striped table-bordered labelDatatable">
                    <thead>
                        <tr>
                            <th class="border-bottom-0">Sr. #</th>
                            <th class="border-bottom-0">Name</th>
                            {{-- <th class="border-bottom-0">Num To Show</th> --}}
                            <th class="border-bottom-0">Display</th>
                            <th class="border-bottom-0">Old Display</th>
                            <th class="border-bottom-0">Description</th>
                            <th class="border-bottom-0">Status</th>
                            {{-- <th class="border-bottom-0">Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($frieght as $key => $row)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $row->name }}</td>
                                {{-- <td>{{ $row->id - 1 }}</td> --}}
                                <td>{{ $row->display }}</td>
                                <td>{{ $row->old_display }}</td>
                                <td>{{ $row->description }}</td>
                                <td>
                                    @if ($row->status == 1)
                                        Active
                                    @else
                                        Inactive
                                    @endif
                                </td>
                                {{-- <td>
                                    <button type="button" class="btn btn-primary get-data" data-toggle="modal"
                                        data-target="#exampleModal2">Edit
                                        <input hidden type="text" class="fieldID" value="{{ $row->id }}">
                                        <input hidden type="text" class="fieldName" value="{{ $row->name }}">
                                        <input hidden type="text" class="fieldDisplay" value="{{ $row->display }}">
                                        <input hidden type="text" class="fieldDesc" value="{{ $row->description }}">
                                    </button>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
         <div class="tab-pane table-responsive" id="sidebar" role="tabpanel" aria-labelledby="sidebar-tab">
            <form action="{{ route('field_labels.update') }}" method="post">
                @csrf
                <!--text-nowrap-->
                <table id="myTable3" class="table table-striped table-bordered labelDatatable">
                    <thead>
                        <tr>
                            <th class="border-bottom-0">Sr. #</th>
                            <th class="border-bottom-0">Name</th>
                            {{-- <th class="border-bottom-0">Num To Show</th> --}}
                            <th class="border-bottom-0">Display</th>
                            <th class="border-bottom-0">Old Display</th>
                            <th class="border-bottom-0">Description</th>
                            <th class="border-bottom-0">Status</th>
                            {{-- <th class="border-bottom-0">Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sidebar as $key => $row)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $row->name }}</td>
                                {{-- <td>{{ $row->id - 1 }}</td> --}}
                                <td>{{ $row->display }}</td>
                                <td>{{ $row->old_display }}</td>
                                <td>{{ $row->description }}</td>
                                <td>
                                    @if ($row->status == 1)
                                        Active
                                    @else
                                        Inactive
                                    @endif
                                </td>
                                {{-- <td>
                                    <button type="button" class="btn btn-primary get-data" data-toggle="modal"
                                        data-target="#exampleModal2">Edit
                                        <input hidden type="text" class="fieldID" value="{{ $row->id }}">
                                        <input hidden type="text" class="fieldName" value="{{ $row->name }}">
                                        <input hidden type="text" class="fieldDisplay" value="{{ $row->display }}">
                                        <input hidden type="text" class="fieldDesc" value="{{ $row->description }}">
                                    </button>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
         </div>
        {{-- {{ $fieldLabel->links() }} --}}
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Label <span class="Label_name_display"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <form id="addHistoryForm" action="{{ route('field_labels.store') }}" method="POST"
                                class="needs-validation" novalidate class="tablelist-form" autocomplete="off">
                                @csrf
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="tab-pane active" id="tab1">
                                            <div class="col">
                                                <div>
                                                    <label for="label-field" class="form-label">Field Name (Unique)</label>
                                                    <input type="text" name="name" class="form-control"
                                                        id="">
                                                </div>
                                                <div>
                                                    <label for="label-field" class="form-label">Display</label>
                                                    <input type="text" name="display" class="form-control"
                                                        id="">
                                                </div>
                                                <div>
                                                    <label for="label-field" class="form-label">Description</label>
                                                    <input type="text" name="description" class="form-control"
                                                        id="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success" id="add-btn close">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Edit Label of <span class="show_field_name"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <form id="addHistoryForm" action="{{ route('field_labels.update') }}" method="POST"
                                class="needs-validation" novalidate class="tablelist-form" autocomplete="off">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" name="LabelID" value="" class="get_field_id">
                                    <div class="row g-3">
                                        <div class="tab-pane active" id="tab1">
                                            <div class="col">
                                                <div>
                                                    <label for="label-field" class="form-label">Field Name
                                                        (Unique)</label>
                                                    <input type="text" readonly name="name" class="form-control"
                                                        id="form_field_name">
                                                </div>
                                                <div>
                                                    <label for="label-field" class="form-label">Display</label>
                                                    <input type="text" name="display" class="form-control"
                                                        id="form_field_display">
                                                </div>
                                                <div>
                                                    <label for="label-field" class="form-label">Description</label>
                                                    <input type="text" name="description" class="form-control"
                                                        id="form_field_desc">
                                                </div>
                                                <div>
                                                    <label for="label-field" class="form-label">Select</label>
                                                    <select name="status" class="form-control" id="form_field_status">
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                </div>
                                               <div>
                                                    <label for="label-field" class="form-label">Category</label>
                                                    <select name="category" class="form-control" id="form_field_category">
                                                        <option value="" disabled selected>Select</option>
                                                        <option value="vehicle">Vehicle</option>
                                                        <option value="heavy">Heavy</option>
                                                        <option value="frieght">Frieght</option>
                                                        <option value="sidebar">Sidebar</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success"
                                                id="add-btn close">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
 jQuery(document).ready(function($) {
    var table = $('#myTable').DataTable();
});  
 jQuery(document).ready(function($) {
    var table = $('#myTable1').DataTable();
});  
 jQuery(document).ready(function($) {
    var table = $('#myTable2').DataTable();
});  
                  
 jQuery(document).ready(function($) {
    var table = $('#myTable3').DataTable();
});  
                  
      
        
  $(document).ready(function () {
    // Handle tab clicks to toggle the active class
    $('#myTabs a').on('click', function (e) {
      e.preventDefault();
      $(this).tab('show');
    });

    // Listen for tab show event and set the corresponding active tab
    $('#myTabs a').on('shown.bs.tab', function (e) {
      var targetTab = $(e.target).attr('href'); // Get the href of the clicked tab

      // Set the corresponding tab as active
      $('.tab-content ' + targetTab).addClass('active').siblings().removeClass('active');
    });
  });

    $(document).on("click", ".get-data", function(e) {
        e.preventDefault();

        var Field_id = $(this).find('.fieldID').val();
        var Field_Name = $(this).find('.fieldName').val();
        var Field_Display = $(this).find('.fieldDisplay').val();
        var Field_Desc = $(this).find('.fieldDesc').val();

        $(".get_field_id").val(Field_id);
        $(".show_field_name").html(Field_Name);
        $("#form_field_name").val(Field_Name);
        $("#form_field_display").val(Field_Display);
        $("#form_field_desc").val(Field_Desc);

        var route = '{{ route('field_labels.edit', ['id' => '__Field_id__']) }}'.replace('__Field_id__',
            Field_id);

        console.log('Field_id', Field_id, route);

        $.ajax({
            url: route,
            type: 'GET',
            data: {
                'Field_id': Field_id,
            },
            success: function(data) {
                console.log('data', data);

                $("#field_id").val(data['id']);
                $("#field_name").val(data['name']);
                $("#field_display").val(data['display']);
                $("#field_description").val(data['description']);
                $("#field_status").val(data['status']);
            },
            error: function(error) {
                console.error('Error submitting the form:', error);
            }
        });
    });
</script>
<script>
</script>