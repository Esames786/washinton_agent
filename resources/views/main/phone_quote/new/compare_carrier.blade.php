@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]", '/');
if (isset($_GET['titlee'])) {
    $respn = $_GET['titlee'];
}
?>
<style>
    td::-webkit-scrollbar {
        display: none;
    }
</style>
<div class="table-responsive">
    <input type="hidden" id="origin" value="{{ $origin }}" />
    <input type="hidden" id="destination" value="{{ $destination }}" />
    <input type="hidden" id="order_id" value="{{ $order_id }}" />
    <table class="table table-bordered text-nowrap dataTable no-footer" id="example1" role="grid"
        aria-describedby="example1_info">
        <thead>
            <tr>
                <!--<th class="border-bottom-0">ID</th>-->
                <!--<th class="border-bottom-0">Order ID</th>-->
                <!--<th class="border-bottom-0">Carrier&nbsp;Details</th>-->
                <!--<th class="border-bottom-0">Pickup/Delivery</th>-->
                <!--<th class="border-bottom-0">comments</th>-->
                <!--<th class="border-bottom-0">created_at</th>-->
                <th class="border-bottom-0">Order Id</th>
                <th class="border-bottom-0">Origin</th>
                <th class="border-bottom-0">Destination</th>
                <th class="border-bottom-0">Carrier Detail</th>
                <th class="border-bottom-0">Vehicle Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $k => $val)
                <tr>
                    <!--<td>{{ $val->id }}</td>-->
                    <!--<td>{{ $val->orderId }}</td>-->
                    <!--<td>Company Name &nbsp; <span-->
                    <!--            class="badge badge-light mb-1 "> {{ $val->companyname }}</span><br>-->
                    <!--    location &nbsp; <span class="badge badge-light mb-1"> {{ $val->location }} </span>-->
                    <!--    <br>-->
                    <!--    mcno &nbsp; <span class="badge badge-light mb-1"> {{ $val->mcno }}</span> <br>-->

                    <!--     Company Phone-No  &nbsp; <span  class="badge badge-light mb-1"><button class="btn btn-info btn-sm" onclick="window.location.href = 'rcmobile://sms?number={{ $val->companyphoneno }}'">{{ $val->companyphoneno }}</button></span> <br>-->

                    <!--    Driver phone-No &nbsp; <span class="badge badge-light mb-1"><button class="btn btn-info btn-sm" onclick="window.location.href = 'rcmobile://sms?number={{ $val->driverphoneno }}'">{{ $val->driverphoneno }}</button></span></td>-->
                    <!--<td>Pickup Date &nbsp; <span-->
                    <!--            class="badge badge-light mb-1">{{ $val->est_pickupdate }}</span> <br>-->
                    <!--    Delivery Date &nbsp; <span-->
                    <!--            class="badge badge-light mb-1">{{ $val->est_deliverydate }}</span> <br>-->
                    <!--    Price &nbsp; <span class="badge badge-light mb-1">{{ $val->askprice }} </span> <br>-->
                    <!--</td>-->
                    <!--<td>{{ $val->comments }}</td>-->
                    <!--<td>{{ $val->created_at }}</td>-->
                    <td>
                        SNO# <span class="badge badge-light p-0">{{ $k + 1 }}</span> <br>
                        {{ $val->id }}
                    </td>
                    <td>
                        {{ $val->originzsc }}<br>
                        @if (isset($val->est_pickupdate))
                            Pickup Date: <span class="badge badge-light mb-1 ">
                                {{ \Carbon\Carbon::parse($val->est_pickupdate)->format('M,d Y') }}</span><br>
                        @else
                            Pickup Date: <span class="badge badge-light mb-1 ">
                                {{ \Carbon\Carbon::parse($val->est_pick_date)->format('M,d Y') }}</span><br>
                        @endif
                    </td>
                    <td>
                        {{ $val->destinationzsc }}<br>
                        @if (isset($val->est_deliverydate))
                            Delivery Date: <span class="badge badge-light mb-1 ">
                                {{ \Carbon\Carbon::parse($val->est_deliverydate)->format('M,d Y') }}</span><br>
                        @else
                            Delivery Date: <span class="badge badge-light mb-1 ">
                                {{ \Carbon\Carbon::parse($val->est_delivery_date)->format('M,d Y') }}</span><br>
                        @endif
                    </td>
                    <td style="width:300px;max-width:300px;overflow-x:scroll;">
                        @if (isset($val->companyname))
                            Name: <span class="badge badge-light mb-1 "> {{ $val->companyname }}</span><br>
                        @else
                            Name: <span class="badge badge-light mb-1 "> {{ $val->cname }}</span><br>
                        @endif
                        @if (isset($val->location))
                            Location: <span class="badge badge-light mb-1 "> {{ $val->location }}</span><br>
                        @else
                            Location: <span class="badge badge-light mb-1 "> {{ $val->caddress }}</span><br>
                        @endif
                        @if (isset($val->mcno))
                            MC No: <span class="badge badge-light mb-1 "> {{ $val->mcno }}</span><br>
                        @else
                            MC No: <span class="badge badge-light mb-1 "> {{ $val->mc }}</span><br>
                        @endif
                        @if (isset($val->companyphoneno))
                            Company Phone: <span class="badge badge-light mb-1 "> <button class="btn btn-info btn-sm"
                                    {{-- onclick="window.location.href = 'rcmobile://sms?number={{ $val->carrier->companyphoneno }}'">{{ $val->carrier->companyphoneno }}</button></span><br> --}}
                                    onclick="call('{{ base64_encode($val->companyphoneno) }}')">{{ $val->companyphoneno }}</button></span><br>
                            Company Message: <span class="badge badge-light mb-1 "> <button class="btn btn-info btn-sm"
                                    {{-- onclick="window.location.href = 'rcmobile://sms?number={{ $val->carrier->companyphoneno }}'">{{ $val->carrier->companyphoneno }}</button></span><br> --}}
                                    onclick="msg('{{ base64_encode($val->companyphoneno) }}')">{{ $val->companyphoneno }}</button></span><br>
                        @else
                            Company Phone: <span class="badge badge-light mb-1 "> <button class="btn btn-info btn-sm"
                                    {{-- onclick="window.location.href = 'rcmobile://sms?number={{ $val->company_contact }}'">{{ $val->company_contact }}</button></span><br> --}}
                                    onclick="call('{{ base64_encode($val->company_contact) }}')">{{ $val->company_contact }}</button></span><br>
                            Company Message: <span class="badge badge-light mb-1 "> <button class="btn btn-info btn-sm"
                                    {{-- onclick="window.location.href = 'rcmobile://sms?number={{ $val->company_contact }}'">{{ $val->company_contact }}</button></span><br> --}}
                                    onclick="msg('{{ base64_encode($val->company_contact) }}')">{{ $val->company_contact }}</button></span><br>
                        @endif

                        @php
                            $check_panel = check_panel();

                            if ($check_panel == 1) {
                                $phoneaccess = explode(',', Auth::user()->emp_access_phone);
                            } elseif ($check_panel == 3) {
                                $phoneaccess = explode(',', Auth::user()->emp_access_test);
                            } else {
                                $phoneaccess = explode(',', Auth::user()->emp_access_web);
                            }
                        @endphp
                        @if (in_array('60', $phoneaccess))
                            <?php
                            $digits = \App\PhoneDigit::first();
                            if (isset($val->driverphoneno)) {
                                // if (in_array('61', $phoneaccess)) {
                                    $new = $val->driverphoneno;
                                // }
                                // else {
                                //     $new = putX($digits->hide_digits, $digits->left_right_status, $val->carrier->driverphoneno);
                                // }
                            } else {
                                // if (in_array('61', $phoneaccess)) {
                                    $new = $val->driver_phone;
                                // }
                                // else {
                                //     $new = putX($digits->hide_digits, $digits->left_right_status, $val->driver_phone);
                                // }
                            }
                            ?>
                        @endif
                        Driver Phone: <span class="badge badge-light mb-1 "> <button class="btn btn-info btn-sm"
                                onclick="call('{{ base64_encode($new) }}')">{{ $new }}</button></span><br>
                                {{-- onclick="call('{{ base64_encode($new) }}')">{{ $new }}</button></span><br> --}}
                        Driver Message: <span class="badge badge-light mb-1 "> <button class="btn btn-info btn-sm"
                                onclick="call('{{ base64_encode($new) }}')">{{ $new }}</button></span><br>
                                {{-- onclick="call('{{ base64_encode($new) }}')">{{ $new }}</button></span><br> --}}
                        @if (isset($val->askprice))
                            Driver Price: <span
                                class="badge badge-light mb-1 ">${{ $val->askprice ?? 0 }}</span><br>
                        @else
                            Driver Price: <span class="badge badge-light mb-1 ">
                                ${{ $val->driver_price ?? 0 }}</span><br>
                        @endif
                    </td>
                    <td>
                        <?php
                        $ymk = explode('*^-', $val->ymk);
                        $arraytype = explode('*^', $val->type);
                        $arraycondition = explode('*^', $val->condition);
                        $arraytrailertype = explode('*^', $val->transport);
                        ?>
                        @foreach ($ymk as $key => $val2)
                            @if ($val2)
                                {{ $val2 }} <br>
                                @if (isset($arraytype[$key]))
                                    <span class="badge badge-primary">{{ $arraytype[$key] }}</span>
                                @endif
                                @if (isset($arraycondition[$key]))
                                    <span class="badge badge-info">
                                        @if ($arraycondition[$key] == '1' || $arraycondition[$key] == 'operable')
                                            Operable
                                        @else
                                            Non Running
                                        @endif
                                    </span>
                                @endif
                                @if (isset($arraytrailertype[$key]))
                                    <span class="badge badge-success">
                                        @if ($arraytrailertype[$key] == '1' || $arraytrailertype[$key] == 'open')
                                            Open
                                        @else
                                            Closed
                                        @endif
                                    </span>
                                @endif
                                <br>
                            @endif
                        @endforeach
                        @if (isset($val->created_at))
                            Created At: <span class="badge badge-light mb-1 ">
                                {{ \Carbon\Carbon::parse($val->created_at)->format('M,d Y') }}</span><br>
                        @else
                            Created At: <span class="badge badge-light mb-1 ">
                                {{ \Carbon\Carbon::parse($val->created_at)->format('M,d Y') }}</span><br>
                        @endif
                        @if (isset($val->updated_at))
                            Updated At: <span class="badge badge-light mb-1 ">
                                {{ \Carbon\Carbon::parse($val->updated_at)->format('M,d Y') }}</span><br>
                        @else
                            Updated At: <span class="badge badge-light mb-1 ">
                                {{ \Carbon\Carbon::parse($val->updated_at)->format('M,d Y') }}</span><br>
                        @endif
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning updateHistoryCarrier" data-toggle="modal"
                                data-old-id="{{ $val->id }}" data-new-id="{{ $order_id }}"
                                data-target="#updateCarrierHistory">Update History</button>
                            <button type="button" class="btn btn-success viewHistoryCarrier" data-toggle="modal"
                                data-target="#viewCarrierHistory" data-old-id="{{ $val->id }}"
                                data-new-id="{{ $order_id }}">View History</button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row" style="max-width: 100%;">
        <div class="col-lg-4 my-auto ml-5">
            Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} of {{ $data->total() }} results
        </div>
        <div class="col-lg-7 d-flex justify-content-end" id="carrierPagination2">
            {{ $data->links() }}
        </div>
    </div>

    <script>
        $("#carrierPagination2 .pagination").addClass('pagination1 d-flex');
        $("#carrierPagination2 .pagination").removeClass('pagination');
    </script>
</div>

<script>
    function call(num) {
        var num1 = atob(num);
        window.location.href = 'rcmobile://call/?number=' + num1;
    }

    function msg(num) {
        var num1 = atob(num);
        window.location.href = 'rcmobile://sms/?number=' + num1;
    }
</script>
