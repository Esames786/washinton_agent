@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]",'/');
if(isset($_GET['titlee'])){
    $respn = $_GET['titlee'];
}
?>

@php
    $check_panel = check_panel();

    /* FIX: every panel resolves through accessForPanel() — the old chain sent panels
   2/4/5/6 to emp_access_web, so agents on Multan etc. were checked against the wrong
   panel's permissions (missing phone numbers, logout questions, etc.). */
$phoneaccess = explode(',', (string) Auth::user()->accessForPanel($check_panel));
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
