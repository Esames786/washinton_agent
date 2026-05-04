@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]",'/');
if(isset($_GET['titlee'])){
    $respn = $_GET['titlee'];
}
?>

@php
    $check_panel = check_panel();

    if($check_panel == 1){

    $phoneaccess=explode(',',Auth::user()->emp_access_phone);
    }else{
    $phoneaccess=explode(',',Auth::user()->emp_access_web);
    }
@endphp
<div class="table-responsive">
    <table id="example1" class="table table-striped table-bordered text-wrap">
        <thead>
        <tr>
            <th class="border-bottom-0">Guide type</th>
            <th class="border-bottom-0">Page Name </th>
            <th class="border-bottom-0">Page Route</th>
            <th class="border-bottom-0">Thumbnail</th>
            <th class="border-bottom-0">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $val)
            <tr>
                <td>{{ 
                    ($val->guide_type == 1) ? 'Admin' : 
                    (
                        ($val->guide_type == 2) ? 'Vehicle' : 
                        (
                            ($val->guide_type == 3) ? 'Motorcycle' : 
                            (
                                ($val->guide_type == 4) ? 'Heavy' : 
                                (
                                    ($val->guide_type == 5) ? 'Order Taking' : 
                                    (
                                        ($val->guide_type == 6) ? 'Delivery' : 
                                        (
                                            ($val->guide_type == 7) ? 'Dispatch' : 'Approaching'
                                        )
                                    )
                                )
                            )
                        )
                    )
                }}</td>
                <td>{{$val->page_name}}</td>
                <td>{{$val->page_route}}</td>
                <td>{{$val->thumbnail}}</td>
                <td>
                    <a href="{{ url('add_guide') }}?id={{$val->id}}">EDIT</a>
                    /
                    <a href="{{ route('del_guide', $val->id) }}">
                        @if ($val->deleted_at == null)
                            DELETE
                        @else
                            RECOVER
                        @endif
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{  $data->links() }}


</div>


<script>
    regain_call();
    regain_status();
    regain_report_modal();
</script>
