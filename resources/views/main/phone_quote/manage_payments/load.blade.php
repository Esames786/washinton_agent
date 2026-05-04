@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]", '/');
if (isset($_GET['titlee'])) {
    $respn = $_GET['titlee'];
}
?>
<div class="table-responsive">
    <table class="table table-bordered text-nowrap dataTable no-footer" id="example1" role="grid"
        aria-describedby="example1_info">
        <thead>
            <tr>
                <th class="border-bottom-0">ORDER#</th>
                <th class="border-bottom-0">Pickup</th>
                <th class="border-bottom-0">Delivery</th>
                <th class="border-bottom-0">VEHICLE#/ORDERTAKER<BR></th>
                {{-- <th class="border-bottom-0">Order Price</th> --}}
                <th class="border-bottom-0">Phone</th>
                <th class="border-bottom-0">Dates</th>
                <th class="border-bottom-0">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $val)
                <tr>
                    <td>
                        {{ $val->id }}
                        <input type="hidden" class='order_id' value="{{ $val->id }}">
                        {{-- <input type="hidden" class="pstatus" value="{{ $val->pstatus }}"> --}}
                    </td>
                    <td> {{ $val->origincity }} , {{ $val->originstate }}, {{ $val->originzip }}</td>
                    <td> {{ $val->destinationcity }} , {{ $val->destinationstate }}, {{ $val->destinationzip }}</td>
                    <td>
                        @php
                            $years = explode('*^', $val->year);
                            $makes = explode('*^', $val->make);
                            $models = explode('*^', $val->model);
                        @endphp
                        @foreach ($years as $year)
                            {{ $year }}
                        @endforeach
                        @foreach ($makes as $make)
                            {{ $make }}
                        @endforeach
                        @foreach ($models as $model)
                            {{ $model }}
                        @endforeach
                    <td>
                        {{ $val->ophone }}
                    </td>
                    <td>
                        {{ $val->created_at }}
                        <br>
                        <span class="badge badge-pill badge-default mt-2">Payment: <?php echo pay_status($val->paid_status); ?></span>
                        <br>
                        @if ($val->paneltype == 1)
                            <span class="badge badge-pill badge-default mt-2">Type: Phone Quote</span>
                        @elseif($val->paneltype == 3)
                            <span class="badge badge-pill badge-default mt-2">Type: Testing Quote</span>
                        @elseif($val->paneltype == 4)
                            <span class="badge badge-pill badge-default mt-2">Type: Panel Type 4 Quote</span>
                        @elseif($val->paneltype == 5)
                            <span class="badge badge-pill badge-default mt-2">Type: Panel Type 5 Quote</span>
                        @elseif($val->paneltype == 6)
                            <span class="badge badge-pill badge-default mt-2">Type: Panel Type 6 Quote</span>
                        @else
                            <span class="badge badge-pill badge-default mt-2">Type: Website Quote</span>
                        @endif
                    </td>
                    <td>


                        <button type="button" data-toggle="tooltip" data-placement="top" title="Payments!"
                            class="btn btn-primary btn-sm btn-sm">
                            <a href="{{ url('/owes_money_update/' . $val->id) }}">
                                <i class="fa fa-eye"></i>
                            </a>
                        </button>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $data->links() }}

</div>


<script>
    regain_call();
    regain_status();
    regain_report_modal();
</script>
