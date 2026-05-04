@if(count($credit_card_data) > 0)
    @foreach($credit_card_data as $val)
        <?php
        $cards = explode('*^', $val->card_no);
        $card_type = explode('*^', $val->card_type);
        $card_expire = explode('*^', $val->card_expiry_date);
        ?>
        <div class="media">
            @foreach($card_type as $key=>$val2)
                @if(!empty($val2))
                    <?php
                    //$cardd = $cards[$key];
                    if(Auth::user()->userRole->name == 'Admin')
                    {
                        $cardd = $cards[$key];
                        $new = $val->main_ph;
                    }
                    else
                    {
                        $cardd = 'xxxx - xxxx - xxxx -'.substr($cards[$key], -4);
                        $new = '(xxx) xxx-'.substr($val->main_ph, -4);
                    }
                    ?>
                    <tr>
                        @if($val2 == "visa")
                            <td><a href="/searchData?search={{$val->orderId}}">OrderId#{{$val->orderId}}</a>
                            </td>
                            <td><img src="{{asset('visa.png')}}"
                                     style="margin: 11px; padding: 0px;height: 30px"
                                     alt=""></td>
                            <td>{{$cardd}}</td>
                            <td>{{$card_expire[$key]}}</td>
                            <td>{{$new}}</td>
                        @else
                            <td><a href="/searchData?search={{$val->orderId}}">OrderId#{{$val->orderId}}</a>
                            </td>
                            <td><img src="{{asset('master.png')}}"
                                     style=" margin: 11px; padding: 0px;;height: 30px"
                                     alt=""></td>
                            <td>{{$cardd}}</td>
                            <td>{{$card_expire[$key]}}</td>
                            <td>{{$new}}</td>

                        @endif
                    </tr>
                @endif
            @endforeach
        </div>
    @endforeach
@endif