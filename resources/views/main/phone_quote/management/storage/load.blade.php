@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]",'/');
if(isset($_GET['titlee'])){
    $respn = $_GET['titlee'];
}
?>
<div class="table-responsive">
    <table id="" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th class="border-bottom-0">ID</th>
            <th class="border-bottom-0">NAMES</th>
            <th class="border-bottom-0">ADDRESS</th>
            <th class="border-bottom-0">TIMING</th>
            <th class="border-bottom-0">CONTACT</th>
            <th class="border-bottom-0">STORAGE CHARGES</th>
            <th class="border-bottom-0">STORAGE DURATION</th>
            <th class="border-bottom-0">Forklift/Tow Truck</th>
            <th class="border-bottom-0">Action</th>


        </tr>
        </thead>
        <tbody>
        @foreach($data as $val)
            <tr>
                <td>
                   {{$val->id}}
                </td>
                <td>
                    Company: <b>{{ $val->company_name }}</b>
                    <br>
                    Manager: <b>{{ $val->manager_owner_name }}</b>
                </td>
                <td>
                    Address: <b>{{ $val->company_address }}</b>
                    @if(isset($val->zip))
                    <br>
                    Zip: <b>{{ $val->zip }}</b>
                    @endif
                </td>
                <td>
                    <b>{{str_replace(" ","",$val->open_time)}}</b>
                    <br>
                    <b>{{str_replace(" ","",$val->close_time)}}</b>
                </td>
                <td>
                    Phone: <b>{{ $val->phoneno }}</b>
                    <br>
                    @if(isset($val->phoneno2))
                    Phone 2: <b>{{ $val->phoneno2 }}</b>
                    <br>
                    @endif
                    Fax: <b>{{ $val->faxno }}</b>
                </td>
                <td>
                    Running: <b>{{ $val->charges }}</b>
                    @if(isset($val->charges2))
                    <br>
                    Non-Running: <b>{{ $val->charges2 ?? 0 }}</b>
                    @endif
                </td>
                <td>
                    <b>{{ $val->storage_duration }}</b>
                </td>
                <td>
                    <b>{{ $val->forklift_twotruck }}</b>
                    <?php
                        $forklift_twotruck = explode(', ',$val->forklift_twotruck);
                    ?>
                    @foreach($forklift_twotruck as $key2 => $value)
                        @if($value == 'Forklift')
                            @if(isset($val->forklift_price))
                                <br>
                                ForkLift Price: <b>{{$val->forklift_price}}</b>
                            @endif
                        @endif
                        @if($value == 'Tow Truck')
                            @if(isset($val->tow_truck_price))
                                <br>
                                Tow Truck Price: <b>{{$val->tow_truck_price}}</b>
                            @endif
                        @endif
                    @endforeach
                </td>
                <td>
                    <a href="{{ url('/storage_edit/'.$val->id) }}" class="btn btn-success" title="Edit"><i class="fa fa-book"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <div>
            Showing {{$data->firstItem()}} to {{$data->lastItem()}} of {{$data->total()}} total
        </div>
        <div>
        {{  $data->links() }}
        </div>
    </div>

</div>


<script>
    regain_call();
    regain_status();
    regain_report_modal();
</script>
