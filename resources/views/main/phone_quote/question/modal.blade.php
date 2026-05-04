<div class="modal fade" id="viewQuestionModal{{ $order->id }}" tabindex="-1" role="dialog"
    aria-labelledby="viewQuestionModal{{ $order->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 70%;">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><b>Show Data</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="ChatViewMain">
                <table>
                    <thead>
                        <tr>
                            <th class="box">Date</th>
                            <th class="box">Origin</th>
                            <th class="box">Destination</th>
                            <th class="box">Price</th>
                            <th class="box">Vechile Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="hidden" value="{{ $order->id }}" id="oID">
                                <span
                                    style="color:#1e61a1;margin-bottom: 0.5rem;
                                display: block; font-weight: 600;">{{ $order->created_at }}</span>
                                @if ($order->pickup_date)
                                    <dd>
                                        <b>Pickup:</b>
                                        <span>{{ \Carbon\Carbon::parse($order->pickup_date)->format('Y,M d') }}</span>
                                    </dd>
                                @endif
                                @if ($order->delivery_date)
                                    <dd>
                                        <b>Delivery:</b>
                                        <span>{{ \Carbon\Carbon::parse($order->delivery_date)->format('Y,M d') }}</span>
                                    </dd>
                                @endif
                                <dd>
                                    <b>Modified:</b>
                                    <span>{{ \Carbon\Carbon::parse($order->updated_at)->format('Y,M d h:i A') }}</span>
                                </dd><br />
                                <span class="d-flex">Order Id: <b>{{ $order->id }} </b></span>

                            </td>
                            <td>
                                <a href="#">
                                    <i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                                    <span>{{ $order->originzsc }}</span>
                                </a>
                                <br>
                                @if ($order->oterminal == 2 || $order->oterminal == 3 || $order->oterminal == 4 || $order->oterminal == 8)
                                    <span class="text-primary"><b>{{ $order->oauction }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="#"><span> <i class="fa fa-map-marker" style="color:red;"
                                            aria-hidden="true"></i> {{ $order->destinationzsc }}</span></a>
                                <br>
                                @if ($order->dterminal == 2 || $order->dterminal == 3 || $order->dterminal == 4 || $order->dterminal == 10)
                                    <span class="text-primary"><b>{{ $order->dauction }}</b></span>
                                @endif
                            </td>
                            <td>
                                <span class="d-flex">Carrier: @if ($order->pay_carrier)
                                        <b> ${{ $order->pay_carrier }} </b>
                                    @else
                                        <b>$0</b>
                                    @endif
                                </span>
                                <br />
                                <span class="d-flex">Driver: @if ($order->driver_price)
                                        <b> ${{ $order->driver_price }} </b>
                                    @else
                                        <b>$0</b>
                                    @endif
                                </span>
                                <br />
                                <span class="d-flex">Listed: @if ($order->listed_price)
                                        <b> ${{ $order->listed_price }} </b>
                                    @else
                                        <b>$0</b>
                                    @endif
                                </span>
                                <br />
                                <span class="d-flex">Price: @if ($order->payment)
                                        <b> ${{ $order->payment }} </b>
                                    @else
                                        <b>$0</b>
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div>
                                    <?php
                                    $ymk1 = explode('*^-', $order->ymk);
                                    $ymk2 = explode('*^', $order->ymk);
                                    ?>
                                    <dd>
                                        @if ($ymk1)
                                            @if (count($ymk1) > 1)
                                                <a href="#" title="{{ $order->ymk }}"
                                                    class="text-secondary">{{ count($ymk1) }} Vehicles</a>
                                            @else
                                                {{ $order->ymk }}
                                            @endif
                                        @elseif($ymk2)
                                            @if (count($ymk2) > 1)
                                                <a href="#" title="{{ $order->ymk }}"
                                                    class="text-secondary">{{ count($ymk2) }} Vehicles</a>
                                            @else
                                                {{ $order->ymk }}
                                            @endif
                                        @endif
                                    </dd>
                                </div>
                                @if ($order->car_type == 2)
                                    <dd>
                                    <div class="badge bg-info text-light">Heavy</div>@else<div
                                            class="badge bg-warning text-light my-1">Car/Motor</div>
                                    </dd>
                                @endif
                                <?php
                                $transport1 = explode('*^-', $order->transport);
                                $transport2 = explode('*^', $order->transport);
                                $condition1 = explode('*^-', $order->condition);
                                $condition2 = explode('*^', $order->condition);
                                ?>
                                @if ($transport2[0] == 1 || $transport2[0] == 2)
                                    @if (count($transport2) > 1)
                                        <dd><a href="#" title="{{ typeCondition($transport2, 0) }}">
                                                <div class="badge bg-info text-light my-1">{{ count($transport2) }}
                                                    Types</div>
                                            </a></dd>
                                    @else
                                        <dd>
                                            <div class="badge bg-info text-light my-1">
                                                @if ($transport2[0] == 1)
                                                    open
                                                @elseif($transport2[0] == 2)
                                                    enclosed
                                                @endif
                                            </div>
                                        </dd>
                                    @endif
                                @elseif($transport1[0] == 'open' || $transport1[0] == 'enclosed')
                                    @if (count($transport1) > 1)
                                        <dd><a href="#" title="{{ $order->transport }}">
                                                <div class="badge bg-info text-light my-1">{{ count($transport1) }}
                                                    Types</div>
                                            </a></dd>
                                    @else
                                        <dd>
                                            <div class="badge bg-info text-light my-1">
                                                @if ($transport1[0] == 1)
                                                    open
                                                @elseif($transport1[0] == 2)
                                                    enclosed
                                                @endif
                                            </div>
                                        </dd>
                                    @endif
                                @endif
                                @if ($condition2[0] == 1 || $condition2[0] == 2)
                                    @if (count($condition2) > 1)
                                        <dd><a href="#" title="{{ typeCondition($condition2, 1) }}">
                                                <div class="badge bg-info text-light my-1">{{ count($condition2) }}
                                                    Conditions</div>
                                            </a></dd>
                                    @else
                                        <dd>
                                            <div class="badge bg-info text-light my-1">
                                                @if ($condition2[0] == 1)
                                                    operable
                                                @elseif($condition2[0] == 2)
                                                    non-running
                                                @endif
                                            </div>
                                        </dd>
                                    @endif
                                @elseif($condition1[0] == 'operable' || $condition1[0] == 'non-running')
                                    @if (count($condition1) > 1)
                                        <dd><a href="#" title="{{ $order->condition }}">
                                                <div class="badge bg-info text-light my-1">{{ count($condition1) }}
                                                    Conditions</div>
                                            </a></dd>
                                    @else
                                        <dd>
                                            <div class="badge bg-info text-light my-1">
                                                @if ($condition1[0] == 1)
                                                    operable
                                                @elseif($condition1[0] == 2)
                                                    non-running
                                                @endif
                                            </div>
                                        </dd>
                                    @endif
                                @endif
                                <?php $id = $order->pstatus; ?>
                                <dd>
                                    @if ($id == 0)
                                        <span class='badge badge-orange txt-white'>New</span>
                                    @elseif ($id == 1)
                                        <span class='badge badge-warning txt-white'>Interested</span>
                                    @elseif ($id == 2)
                                        <span class='badge badge-primary txt-white'>FollowMore</span>
                                    @elseif ($id == 3)
                                        <span class='badge badge-pink txt-white'>AskingLow</span>
                                    @elseif ($id == 4)
                                        <span class='badge badge-success '>Not Interested</span>
                                    @elseif ($id == 5)
                                        <span class='badge badge-dark txt-white'>No Response</span>
                                    @elseif ($id == 6)
                                        <span class='badge badge-amber txt-white'>Time Quote</span>
                                    @elseif ($id == 7)
                                        <span class='badge badge-primary  txt-white'>Payment Missing</span>
                                    @elseif ($id == 8)
                                        <span class='badge badge-warning  txt-white'>Booked</span>
                                    @elseif ($id == 9)
                                        <span class='badge badge-pink txt-white'>Listed</span>
                                    @elseif ($id == 10)
                                        <span class='badge badge-success'>Schedule</span>
                                    @elseif ($id == 11 && $value->approve_pickup == 1)
                                        <span class='badge badge-dark txt-white'>Pickedup</span>
                                    @elseif ($id == 11 && $value->approve_pickup != 1)
                                        <span class='badge badge-danger txt-white'>Not Pickedup</span>
                                    @elseif ($id == 12 && $value->approve_deliver == 1)
                                        <span class='badge badge-amber txt-white'>Delivered</span>
                                    @elseif ($id == 12 && $value->approve_deliver != 1)
                                        <span class='badge badge-amber txt-white'>Not Delivered</span>
                                    @elseif ($id == 13)
                                        <span class='badge badge-teal txt-white'>Completed</span>
                                    @elseif ($id == 14)
                                        <span class='badge badge-danger txt-white'>Cancel</span>
                                    @elseif ($id == 15)
                                        <span class='badge badge-danger txt-white'>Deleted</span>
                                    @elseif ($id == 16)
                                        <span class='badge badge-primary txt-white'>OwesMoney</span>
                                    @elseif ($id == 17)
                                        <span class='badge badge-primary txt-white'>Carrier Update</span>
                                    @elseif ($id == 18)
                                        <span class='badge badge-primary txt-white'>On Approval</span>
                                    @elseif ($id == 19)
                                        <span class='badge badge-danger get_car_or_heavy txt-white'>On Approval
                                            Canceled</span>
                                    @endif
                                </dd>
                                <dd>
                                    <span class="my-1 badge badge-{{ 
                                        $order->paneltype == 1 ? 'secondary' : 
                                        ($order->paneltype == 2 ? 'primary' : 
                                        ($order->paneltype == 3 ? 'info' : 
                                        ($order->paneltype == 4 ? 'primary' : 
                                        ($order->paneltype == 5 ? 'primary' : 
                                        ($order->paneltype == 6 ? 'primary' : 'secondary'))))) 
                                    }} text-light">
                                        {{ 
                                            $order->paneltype == 1 ? 'Phone Quote' : 
                                            ($order->paneltype == 2 ? 'Website Quote' : 
                                            ($order->paneltype == 3 ? 'Testing Quote' : 
                                            ($order->paneltype == 4 ? 'Panel Type 4 Quote' : 
                                            ($order->paneltype == 5 ? 'Panel Type 5 Quote' : 
                                            ($order->paneltype == 6 ? 'Panel Type 6 Quote' : 'Phone Quote'))))) 
                                        }}
                                    </span>
                                </dd>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
            <div class="modal-body">
                <input type="hidden" value="{{ $order->id }}" class="mOrderId">
                <div class="Chat__Body">
                </div>
                <div class="showtypeData">
                    <input type="text" placeholder="" id="messagetextarea" value="" disabled
                        style="border-radius:5px 0 0 5px;">
                    <button class="btn btn-info sendMessage" style="border-radius:0 5px 5px 0;">Send</button>
                </div>
                <div class="fixedQandA" id="fixedQandA">
                    <div class="fixedQandAbox" id="fixedQandAleft">
                        <h3>Select Question</h3>
                        <hr>
                        <input type="hidden" value="" id="q_ID">
                        <ul id="fixedQandAulleft">
                            @foreach ($ques as $key => $value)
                                <li class="questionSelect">
                                    <input type="radio" name="question__name" value="{{ $value->id }}"
                                        id="fixedQandAleftquestion{{ $key }}" class="demo">
                                    <label for="fixedQandAleftquestion{{ $key }}">{!! html_entity_decode($value->question) !!}
                                        <span class="fixedleftnohidden"></span></label>
                                    <input type="hidden" value="{{ $value->id }}" class="q_ID">
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="fixedQandAbox" id="fixedQandAright">
                        <h3>Select Answer</h3>
                        <hr>
                        <input type="hidden" value="" id="a_ID">
                        <ul id="fixedQandAulright">
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
