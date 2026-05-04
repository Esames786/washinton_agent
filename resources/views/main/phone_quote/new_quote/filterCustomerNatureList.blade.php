
<div class="row">
    <div class="col-sm-12 mt-3">
        <table id="myTable" class="table table-bordered table-striped key-buttons">
            <thead>
              <tr>
                <th scope="col">Sr.#</th>
                <th scope="col">OrderId/Date</th>
                <th scope="col">User</th>
                <th scope="col">Status</th>
                <th scope="col">Description</th>
                <th scope="col">Phone</th>
                <th scope="col">Remarks</th>
                <th scope="col">Status UpdatedBy</th>
                <!--<th scope="col">Admin Action</th>-->
              </tr>
            </thead>
            <tbody>
                @foreach($nature as $key => $val)
                    {{-- dd($nature[0]->toArray()) --}}
                   <tr>
                    <td>{{ $loop->iteration }}</td>
                    <th scope="row">
                        <a href="{{url('searchData')}}?search={{$val->order_id}}" target="_blank">{{$val->order_id}}</a>
                        <br>
                        {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y')}}
                        <br>
                        {{\Carbon\Carbon::parse($val->created_at)->format('h:i A')}}
                    </th>
                    <td>{{ $val->user->name }}</td>
                    <td>
                        @if($val->status == "Verified")
                            <span class="badge badge-success text-light">Positive</span>
                        @elseif($val->status == 'Unverified')
                            <span class="badge badge-danger text-light">Negative</span>
                        @endif
                    </td>
                    <td>
                        <div class="description-container" data-description="{!! $val->description !!}">
                            {!! \Illuminate\Support\Str::limit($val->description, 200, $end = '...') !!}
                            @if (strlen($val->description) > 200)
                                <span class="view-more" onclick="showPopup(this)">View more</span>
                            @endif
                        </div>
                    </td>
                    <div id="popup" style="display: none;">
                        <div id="popup-content"></div>
                        <button onclick="closePopup()">Close</button>
                    </div>
                    <td>
                        <?php
                            $digits = \App\PhoneDigit::first();
                            $new = putXs($digits->hide_digits, $digits->left_right_status, $val->phone);
                        ?>
                        <b>Message:</b> {{-- $val->phone ?? 'N/A' --}}
                        <a onclick="msg('{{ base64_encode($val->phone) }}', '{{ $val->id }}')"
                            class="btn btn-outline-info mobile count_user"
                            style="padding: 3px 5px; font-size: 16px;">
                            <i class="fa fa-envelope"></i>
                            <span>{{ $new }}</span>
                        </a>
                    </td>
                    <td>
                        <b>Remarks:</b> {{$val->remarks ?? 'N/A'}}
                    </td>
                    <td>
                        <b>Status updatedBy:</b> {{$val->statusUpdatedBy->name ?? 'N/A'}}
                    </td>
                    {{-- <td>
                        @if(isset($val->admin_agree))
                            @if($val->admin_agree == 'Agree')
                                <span class="badge badge-success text-light">{{$val->admin_agree}}</span>
                            @else
                                <span class="badge badge-danger">{{$val->admin_agree}}</span>
                            @endif
                            @if(isset($val->admin_remarks))
                                <br>
                                {{$val->admin_remarks}}
                            @endif
                        @else
                            N/A
                        @endif
                    </td> --}}
                  </tr>
              @endforeach
            </tbody>
        </table>
    </div>
</div>

@php
    function putXs($digits, $status, $num) {
        $val = $num;
        if ($status == 0) {
            if ($digits == 0) {
                $val = $num;
            } else if ($digits == 1) {
                $val = '(x'.substr($num, -12);
            } else if ($digits == 2) {
                $val = '(xx'.substr($num, -11);
            } else if ($digits == 3) {
                $val = '(xxx) '.substr($num, -8);
            } else if ($digits == 4) {
                $val = '(xxx) x'.substr($num, -7);
            } else if ($digits == 5) {
                $val = '(xxx) xx'.substr($num, -6);
            } else if ($digits == 6) {
                $val = '(xxx) xxx-'.substr($num, -4);
            } else if ($digits == 7) {
                $val = '(xxx) xxx-x'.substr($num, -3);
            } else if ($digits == 8) {
                $val = '(xxx) xxx-xx'.substr($num, -2);
            } else if ($digits == 9) {
                $val = '(xxx) xxx-xxx'.substr($num, -1);
            } else if ($digits == 10) {
                $val = '(xxx) xxx-xxxx';
            }
        } else if ($status == 1) {
            if ($digits == 0) {
                $val = $num;
            } else if ($digits == 1) {
                $val = substr($num, 0, 13).
                'x';
            } else if ($digits == 2) {
                $val = substr($num, 0, 12).
                'xx';
            } else if ($digits == 3) {
                $val = substr($num, 0, 11).
                'xxx ';
            } else if ($digits == 4) {
                $val = substr($num, 0, 10).
                'xxxx';
            } else if ($digits == 5) {
                $val = substr($num, 0, 8).
                'x-xxxx';
            } else if ($digits == 6) {
                $val = substr($num, 0, 7).
                'xx-xxxx';
            } else if ($digits == 7) {
                $val = substr($num, 0, 6).
                'xxx-xxxx';
            } else if ($digits == 8) {
                $val = substr($num, 0, 3).
                'x) xxx-xxxx';
            } else if ($digits == 9) {
                $val = substr($num, 0, 2).
                'xx) xxx-xxxx';
            } else if ($digits == 10) {
                $val = '(xxx) xxx-xxxx';
            }
        } else if ($status == 2) {
            if ($digits == 0) {
                $val = $num;
            } else if ($digits == 1) {
                $val = substr($num, 0, 7).
                'x'.substr($num, -6);
            } else if ($digits == 2) {
                $val = substr($num, 0, 7).
                'xx'.substr($num, -5);
            } else if ($digits == 3) {
                $val = substr($num, 0, 6).
                'xxx'.substr($num, -5);
            } else if ($digits == 4) {
                $val = substr($num, 0, 3).
                'x) xxx'.substr($num, -5);
            } else if ($digits == 5) {
                $val = substr($num, 0, 3).
                'x) xxx-x'.substr($num, -3);
            } else if ($digits == 6) {
                $val = substr($num, 0, 3).
                'x) xxx-xx'.substr($num, -2);
            } else if ($digits == 7) {
                $val = substr($num, 0, 2).
                'xx) xxx-xx'.substr($num, -2);
            } else if ($digits == 8) {
                $val = substr($num, 0, 2).
                'xx) xxx-xxx'.substr($num, -1);
            } else if ($digits == 9) {
                $val = '(xxx) xxx-xxx'.substr($num, -1);
            } else if ($digits == 10) {
                $val = '(xxx) xxx-xxxx';
            }
        }
        return $val;
    }
@endphp