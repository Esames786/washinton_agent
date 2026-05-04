@include('partials.mainsite_pages.return_function')
<div class="table-responsive">
    {{--example1--}}
    <table id="" class="table table-striped table-bordered text-nowrap">
        <thead>
        <tr>
            <th class="border-bottom-0">ORDER#</th>
            <th class="border-bottom-0">Pickup</th>
            <th class="border-bottom-0">Delivery</th>
            <th class="border-bottom-0">VEHICLE#/ORDERTAKER<BR></th>
            <th class="border-bottom-0">Order Price</th>
            <th class="border-bottom-0">Phone</th>
            <th class="border-bottom-0">Dates</th>
            <th class="border-bottom-0">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $val)
            <tr>
                <td>
                    {{$val->id}}
                    <input type="hidden" class='order_id' value="{{$val->id}}">
                    <input type="hidden" class="pstatus" value="{{ $val->pstatus }}">
                    <input type="hidden" class="client_email" value="{{ $val->oemail }}">
                    <input type="hidden" class="client_name" value="{{ $val->oname }}">
                    <input type="hidden" class="client_phone" value="{{ $val->main_ph }}">
                </td>
                <td>
                    <a href="http://classic.mapquest.com/embed?zoom=5&amp;q={{$val->origincity}}+{{$val->originstate}}+{{$val->originzip}}"
                       target="_blank">
                        <i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                        {{$val->origincity . "-".$val->originstate ."-" .$val->originzip  }}
                    </a><br>
                    <strong><i class="fa fa-location-arrow" style="color:green;" aria-hidden="true"></i>
                        {{$val->oaddress}}
                    </strong>
                    <br>
                    <strong><i class="fa fa-location-arrow" style="color:green;" aria-hidden="true"></i>
                        {{$val->oaddress2}}
                    </strong>
                    <br>
                </td>
                <td>
                    <a href="http://classic.mapquest.com/embed?zoom=5&amp;q={{$val->destinationcity}}+{{$val->destinationstate}}+{{$val->destinationzip}}"
                       target="_blank">
                        <i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                        {{$val->destinationcity . "-".$val->destinationstate ."-" .$val->destinationzip  }}
                    </a><br>
                    <strong><i class="fa fa-location-arrow" style="color:red;" aria-hidden="true"></i>
                        {{$val->daddress}}
                    </strong>
                    <br>
                    <strong><i class="fa fa-location-arrow" style="color:red;" aria-hidden="true"></i>
                        {{$val->daddress2}}
                    </strong>
                    <br>
                </td>
                <?php $ymk = explode('*^-', $val->ymk); ?>
                <td>
                    @foreach($ymk as $val2)
                        @if($val2)
                            <span class="text-center pd-2 bd-l">{{$val2}}<br></span>
                        @endif
                    @endforeach
                    <span class="badge badge-pill badge-default mt-2">{{get_user_name($val->order_taker_id)}}<br></span>
                </td>
                <td><span class="text-center pd-2 bd-l">{{$val->payment}}<br></span></td>
                <?php $ophone = explode('*^', $val->ophone); ?>
                <td>
                    @foreach($ophone as $val3)
                        @php
                            $new = substr($val3, 0, -2) . 'xx';
                        @endphp
                        @if($val3)
                            <span class="text-center pd-2 bd-l"><a
                                    class="btn btn-outline-info fa fa-phone mobile count_user"
                                    style="padding: 3px 5px; font-size: 20px;">{{$new}}</a><br></span>
                            <span class="text-center pd-2 bd-l"><a
                                    onclick="window.location.href = 'rcmobile://sms?number={{$val3}}'"
                                    class="btn btn-outline-info fa fa-envelope sms"
                                    style="padding: 3px 5px; font-size: 20px;">{{$new}}</a><br></span>
                        @endif
                    @endforeach
                </td>
                <td>
                    <span class="text-center pd-2 bd-l"> Created At:<br>{{$val->created_at}}</span><br>
                    <span class="text-center pd-2 bd-l">Updated At:<br>{{$val->updated_at}}</span><br>
                    <span
                        class="badge badge-pill badge-danger-light mt-2 fa-blink">{{get_pstatus($val->pstatus)}}</span>
                        
                    @if($val->pstatus < 13)
                        <span class="badge badge-pill badge-default txt-white">
                            {{ $val->created_at->diffForHumans() }} late time quote
                        </span>
                    @endif
                </td>
                <td id='order_action'>
                    <div class="btn-list">

                        <button type="button"
                                class="btn btn-facebook btn-sm updatee"><i
                                class="fa fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-twitter btn-sm"><i
                                class="fa fa-edit "></i>
                        </button>
                        <br>
                        <button type="button" class="btn btn-google btn-sm"><i
                                class="fa fa-road "></i>
                        </button>
                        <button type="button" class="btn btn-youtube btn-sm"><i
                                class="fa fa-trash "></i>
                        </button>

                    </div>
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
</script>


