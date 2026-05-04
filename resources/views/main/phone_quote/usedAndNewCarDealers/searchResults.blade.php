@php
    $ptype = 1;
    $query = \App\user_setting::where('user_id', Auth::user()->id)->first();
    if (!empty($query)) {
        $ptype = $query['penal_type'];
    }

    if ($ptype == 1) {
        $phoneaccess = explode(',', Auth::user()->emp_access_phone);
    } elseif ($ptype == 2) {
        $phoneaccess = explode(',', Auth::user()->emp_access_web);
    } elseif ($ptype == 3) {
        $phoneaccess = explode(',', Auth::user()->emp_access_test);
    } elseif ($ptype == 4) {
        $phoneaccess = explode(',', Auth::user()->panel_type_4);
    } elseif ($ptype == 5) {
        $phoneaccess = explode(',', Auth::user()->panel_type_5);
    } elseif ($ptype == 6) {
        $phoneaccess = explode(',', Auth::user()->panel_type_6);
    } else {
        $phoneaccess = [];
    }
@endphp
{{-- example1 --}}
<table class="table table-bordered table-sm col-lg-2 fs-18 text-center pd-2 bd-l" role="grid" aria-describedby="">
    <thead class="table-dark">
        <tr>
            <th>Sr. #</th>
            <th width="15%">Client Name</th>
            <th width="15%">Person Name</th>
            <th width="15%">Number</th>
            <th width="15%">Phone2</th>
            <th width="15%">Phone3</th>
            <th width="10%">Address</th>
            <th width="10%">State</th>
            <th width="10%">Email</th>
            <th width="10%">Website</th>
            <th width="10%">Link</th>
            <th width="10%">Category</th>
            <th width="10%">Created At</th>
            @if (Auth::user()->role === 2)
                <th width="10%">Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $val)
            @php
                // $key = $key + 1;
                $i = $data->firstItem();
            @endphp
            <tr class="parent1{{ $key }}">
                <td>{{ $key + $i }}</td>
                <td>{{ $val->name }}</td>
                <td>{{ $val->person_name }}</td>
                <td>
                    {{-- @if (in_array('60', $phoneaccess)) --}}
                    <?php
                    $digits = \App\PhoneDigit::first();
                    if (in_array('61', $phoneaccess)) {
                        $new = $val->phone;
                    } else {
                        $new = putX($digits->hide_digits, $digits->left_right_status, $val->phone);
                        // $new = "";
                    }
                    ?>
                    <span class="badge badge-primary mb-2">
                        {{-- <a onclick="call('{{ base64_encode($val->phone) }}', '{{ $val->id }}')"
                                class="btn btn-outline-info mobile count_user"
                                style="padding: 3px 5px; font-size: 16px;">
                                <i class="fa fa-phone"></i>
                                <span class="">{{ $new }}</span>
                            </a> --}}
                        <a onclick="call('{{ base64_encode($val->phone) }}', '{{ $val->id }}')"
                            class="btn btn-outline-info mobile count_user" style="padding: 3px 5px; font-size: 16px;">
                            <i class="fa fa-phone"></i>
                            <span>{{ $new }}</span>
                        </a>
                    </span><br>
                    {{-- @endif --}}
                </td>
                <td>
                    @if ($val->phone2)
                        <?php
                        $digits = \App\PhoneDigit::first();
                        if (in_array('61', $phoneaccess)) {
                            $new = $val->phone2;
                        } else {
                            $new = putX($digits->hide_digits, $digits->left_right_status, $val->phone2);
                        }
                        ?>
                        <span class="badge badge-primary mb-2">
                            <a onclick="call('{{ base64_encode($val->phone2) }}', '{{ $val->id }}')"
                                class="btn btn-outline-info mobile count_user"
                                style="padding: 3px 5px; font-size: 16px;">
                                <i class="fa fa-phone"></i>
                                <span>{{ $new }}</span>
                            </a>
                        </span><br>
                        @if (in_array('104', $phoneaccess))
                            <span class="badge badge-success mb-2">
                                <a onclick="openWhatsApp('{{ $val->phone2 }}', '{{ $val->id }}')"
                                    class="btn btn-success" style="padding: 3px 5px; font-size: 16px;">
                                    <i class="fa fa-whatsapp"></i>
                                    <span>{{ $new }}</span>
                                </a>
                            </span>
                        @endif

                        <script>
                            function openWhatsApp(phone2, userId) {
                                // Format the phone number to include the country code
                                const formattedPhone = '+' + phone2.replace(/[^\d]+/g, '');

                                // Construct the WhatsApp link
                                const whatsappLink = `https://wa.me/${formattedPhone}`;

                                // Open the link in a new window or tab
                                window.open(whatsappLink, '_blank');

                                // Ajax to add whatsapp count
                                $.ajax({
                                    url: '{{ route('autosapproach.Whatsapp.count') }}',
                                    type: 'GET',
                                    data: {
                                        'approachId': userId,
                                    },
                                    success: function(data) {
                                        alert(data);
                                    },
                                    error: function(data) {
                                        var errors = data.responseJSON;
                                    }
                                });
                            }
                        </script>
                    @endif
                </td>
                <td>
                    @if ($val->phone3)
                        <?php
                        $digits = \App\PhoneDigit::first();
                        if (in_array('61', $phoneaccess)) {
                            $new = $val->phone3;
                        } else {
                            $new = putX($digits->hide_digits, $digits->left_right_status, $val->phone3);
                        }
                        ?>
                        <span class="badge badge-primary mb-2">
                            {{-- <a onclick="call('{{ base64_encode($val->phone3) }}', '{{ $val->id }}')"
                                class="btn btn-outline-info mobile count_user"
                                style="padding: 3px 5px; font-size: 16px;">
                                <i class="fa fa-phone3"></i>
                                <span class="">{{ $new }}</span>
                            </a> --}}
                            <a onclick="call('{{ base64_encode($val->phone3) }}', '{{ $val->id }}')"
                                class="btn btn-outline-info mobile count_user"
                                style="padding: 3px 5px; font-size: 16px;">
                                <i class="fa fa-phone"></i>
                                <span>{{ $new }}</span>
                            </a>
                        </span><br>
                        @if (in_array('104', $phoneaccess))
                            <span class="badge badge-success mb-2">
                                <a onclick="openWhatsApp('{{ $val->phone3 }}', '{{ $val->id }}')"
                                    class="btn btn-success" style="padding: 3px 5px; font-size: 16px;">
                                    <i class="fa fa-whatsapp"></i>
                                    <span>{{ $new }}</span>
                                </a>
                            </span>
                        @endif

                        <script>
                            function openWhatsApp(phone3, userId) {
                                // Format the phone number to include the country code
                                const formattedPhone = '+' + phone3.replace(/[^\d]+/g, '');

                                // Construct the WhatsApp link
                                const whatsappLink = `https://wa.me/${formattedPhone}`;

                                // Open the link in a new window or tab
                                window.open(whatsappLink, '_blank');

                                // Ajax to add whatsapp count
                                $.ajax({
                                    url: '{{ route('autosapproach.Whatsapp.count') }}',
                                    type: 'GET',
                                    data: {
                                        'approachId': userId,
                                    },
                                    success: function(data) {
                                        alert(data);
                                    },
                                    error: function(data) {
                                        var errors = data.responseJSON;
                                    }
                                });
                            }
                        </script>
                    @endif
                </td>
                <td>
                    <span class="text-center pd-2 bd-l mt-2">
                        {{ $val->address }}
                    </span>
                </td>
                {{-- <td>Created At:
                        <br>{{ \Carbon\Carbon::parse($val->created_at)->format('M,d Y') }}<br>{{ \Carbon\Carbon::parse($val->created_at)->format('h:i A') }}
                    </td> --}}
                <td>
                    {{ $val->state }}
                </td>
                <td>
                    {{ $val->email }}
                </td>
                <td>
                    {{ $val->website }}
                </td>
                <td>
                    {{ $val->link }}
                </td>
                <td>
                    {{ $val->category }}
                </td>
                @if (Auth::user()->role === 2)
                    <td>
                        <button type="button" class="btn btn-primary add-history" data-toggle="modal"
                            data-target="#exampleModal8">Add History
                            <input hidden type="text" class="Company-ID" value="{{ $val->id }}">
                            <input hidden type="text" class="Company-Name" value="{{ $val->name }}">
                        </button>
                    </td>
                @endif
                {{-- <td id='order_action'>
                        <div class="btn-list">
                            <button type="button" data-placement="top" title="Order History!"
                                class="btn btn-outline-info btn-sm w-100 updatee">
                                View Order History
                            </button>
                        </div>
                        <br>
                        @if (isset($val->approaching_time))
                            Last Updated At:
                            <br>{{ \Carbon\Carbon::parse($val->approaching_time)->format('M,d Y') }}<br>{{ \Carbon\Carbon::parse($val->approaching_time)->format('h:i A') }}
                        @else
                            No Last History Update
                        @endif
                    </td> --}}
            </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-between">
    <div class="text-secondary my-auto">
        Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} from total {{ $data->total() }}
        entries
    </div>
    <div>
        {{ $data->links() }}
    </div>

</div>
<div class="modal fade" id="exampleModal8" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel8"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <h5 class="modal-title" id="exampleModalLabel7">Employee Access (Assign
                        Data)</h5> --}}

                <h5 class="modal-title" id="exampleModalLabel">Add History For: <span class="history_id"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="form-group">
                        <form id="addHistoryForm" action="{{ route('store.call.history') }}" method="POST"
                            class="needs-validation" novalidate class="tablelist-form" autocomplete="off">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="CompanyID" value="" class="history_val">
                                <div class="row g-3">
                                    <div class="row">
                                        <!--=============new modal===============-->
                                        <div class=" tab-menu-heading p-0 bg-light">
                                            <div class="tabs-menu1 ">
                                                <!-- Tabs -->
                                                <ul class="nav panel-tabs  gap-2">
                                                    <li class=""><a href="#tab1" class="active btn btn-success"
                                                            data-toggle="tab">HISTORY/STATUS</a>
                                                    </li>
                                                    <li><a href="#tab2" data-toggle="tab" class="btn btn-success">VIEW
                                                            HISTORY</a></li>
                                                    <li></li>
                                                    <li class="position-relative">
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!--=============new modal===============-->
                                    </div>
                                    <div class="tab-pane active" id="tab1">
                                        <div class="row">

                                            <div class="col-lg-4">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" id="connected"
                                                        name="connectStatus" value="Connected" checked>
                                                    <label class="custom-control-label form-label"
                                                        for="connected">Connected</label>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="custom-control custom-radio mb-3">
                                                    <input type="radio" class="custom-control-input"
                                                        id="notConnected" name="connectStatus" value="Not Connected">
                                                    <label class="custom-control-label form-label"
                                                        for="notConnected">Not Connected</label>
                                                </div>
                                            </div>

                                            <div />
                                            <div class="col-lg-12">
                                                <div>
                                                    <label for="label-field" class="form-label">Add
                                                        Comments</label>
                                                    <textarea rows="3" name="comment" id="comment" placeholder="Enter Comments" class="form-control"></textarea>
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
                                <div class="tab-pane" id="tab2">
                                    <div class="chat-body-style ChatBody" id="calhistory" style=" height:300px;">

                                        <div class="message-feed media">
                                            <div class="media-body">
                                                <div class="mf-content w-100 history-content">
                                                    {{-- <h6>Agent: Michael</h6>
                                                        <h6>STATUS: TimeQuote</h6>
                                                        <h6>Remarks: She said she has to figure out when the vehicle
                                                            will be ready. She
                                                            asked for a quote on our email so I sent her the booking
                                                            form as well.</h6>
                                                        <strong class="mf-date"><i class="fa fa-clock-o"></i> Nov,10
                                                            2023 10:51
                                                            AM</strong> --}}
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Save</button>
            </div> --}}
</div>

@php
    function putX($digits, $status, $num)
    {
        $val = $num;
        if ($status == 0) {
            if ($digits == 0) {
                $val = $num;
            } elseif ($digits == 1) {
                $val = '(x' . substr($num, -12);
            } elseif ($digits == 2) {
                $val = '(xx' . substr($num, -11);
            } elseif ($digits == 3) {
                $val = '(xxx) ' . substr($num, -8);
            } elseif ($digits == 4) {
                $val = '(xxx) x' . substr($num, -7);
            } elseif ($digits == 5) {
                $val = '(xxx) xx' . substr($num, -6);
            } elseif ($digits == 6) {
                $val = '(xxx) xxx-' . substr($num, -4);
            } elseif ($digits == 7) {
                $val = '(xxx) xxx-x' . substr($num, -3);
            } elseif ($digits == 8) {
                $val = '(xxx) xxx-xx' . substr($num, -2);
            } elseif ($digits == 9) {
                $val = '(xxx) xxx-xxx' . substr($num, -1);
            } elseif ($digits == 10) {
                $val = '(xxx) xxx-xxxx';
            }
        } elseif ($status == 1) {
            if ($digits == 0) {
                $val = $num;
            } elseif ($digits == 1) {
                $val = substr($num, 0, 13) . 'x';
            } elseif ($digits == 2) {
                $val = substr($num, 0, 12) . 'xx';
            } elseif ($digits == 3) {
                $val = substr($num, 0, 11) . 'xxx ';
            } elseif ($digits == 4) {
                $val = substr($num, 0, 10) . 'xxxx';
            } elseif ($digits == 5) {
                $val = substr($num, 0, 8) . 'x-xxxx';
            } elseif ($digits == 6) {
                $val = substr($num, 0, 7) . 'xx-xxxx';
            } elseif ($digits == 7) {
                $val = substr($num, 0, 6) . 'xxx-xxxx';
            } elseif ($digits == 8) {
                $val = substr($num, 0, 3) . 'x) xxx-xxxx';
            } elseif ($digits == 9) {
                $val = substr($num, 0, 2) . 'xx) xxx-xxxx';
            } elseif ($digits == 10) {
                $val = '(xxx) xxx-xxxx';
            }
        } elseif ($status == 2) {
            if ($digits == 0) {
                $val = $num;
            } elseif ($digits == 1) {
                $val = substr($num, 0, 7) . 'x' . substr($num, -6);
            } elseif ($digits == 2) {
                $val = substr($num, 0, 7) . 'xx' . substr($num, -5);
            } elseif ($digits == 3) {
                $val = substr($num, 0, 6) . 'xxx' . substr($num, -5);
            } elseif ($digits == 4) {
                $val = substr($num, 0, 3) . 'x) xxx' . substr($num, -5);
            } elseif ($digits == 5) {
                $val = substr($num, 0, 3) . 'x) xxx-x' . substr($num, -3);
            } elseif ($digits == 6) {
                $val = substr($num, 0, 3) . 'x) xxx-xx' . substr($num, -2);
            } elseif ($digits == 7) {
                $val = substr($num, 0, 2) . 'xx) xxx-xx' . substr($num, -2);
            } elseif ($digits == 8) {
                $val = substr($num, 0, 2) . 'xx) xxx-xxx' . substr($num, -1);
            } elseif ($digits == 9) {
                $val = '(xxx) xxx-xxx' . substr($num, -1);
            } elseif ($digits == 10) {
                $val = '(xxx) xxx-xxxx';
            }
        }
        return $val;
    }
@endphp
