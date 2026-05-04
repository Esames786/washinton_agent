@include('partials.mainsite_pages.return_function')
<div class="table-responsive">
    <table id="" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Order Id</th>
            <th>Pickup</th>
            <th>Delivery</th>
            <th>Vehicle</th>
            <th>Storage</th>
            <th>Dates</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $val)
            <tr>
                <td>
                   {{$val->id}}
                </td>
                <td>
                    <a href="https://www.google.com/maps/dir/{{$val->originzip}},+USA/"
                       target="_blank" class="table1ancher">
                        <i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                        <span>  {{$val->origincity . "-".$val->originstate ."-" .$val->originzip  }}</span>
                    </a>
                    @if(!empty($val->oaddress))
                        <a data-placement="bottom" class="table1ancher" title="{{ $val->oaddress }}">
                            <i class="fa fa-location-arrow" style="color:green;" aria-hidden="true"></i>
                            <span>{{ $val->oaddress }} </span>
                        </a>
                    @endif
                </td>
                <td>
                    <a href="https://www.google.com/maps/dir/{{$val->destinationzip }},+USA/"
                       target="_blank" class="table1ancher">
                        <i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                       <span> {{$val->destinationcity . "-".$val->destinationstate ."-" .$val->destinationzip  }}</span>
                    </a>
                    @if($val->daddress)
                        <a data-placement="bottom" title="{{ $val->daddress }}" class="table1ancher">
                            <i class="fa fa-location-arrow" style="color:red;" aria-hidden="true"></i>
                            <span>  {{ $val->daddress }} </span>
                        </a>
                    @endif
                </td>
                <td>
                    <?php 
                        $ymk = explode('*^-', $val->ymk);
                        $transport = explode('*^-',$val->transport);
                        $condition = explode('*^-',$val->condition);
                    ?>
                    @foreach($ymk as $key => $val2)
                        @if($val2)
                            {{$val2}}<br>
                        @endif
                        @if(isset($condition[$key]))
                            @if($condition[$key] == 1)
                                <span class="badge badge-info">Operable</span>
                            @else
                                <span class="badge badge-info">Non-running</span>
                            @endif
                        @else
                            <span class="badge badge-info">Non-running</span>
                        @endif
                        @if(isset($transport[$key]))
                            @if($transport[$key] == 1)
                                <span class="badge badge-primary">Open</span>
                            @else
                                <span class="badge badge-primary">Enclosed</span>
                            @endif
                        @else
                            <span class="badge badge-primary">Enclosed</span>
                        @endif
                        <br>
                    @endforeach
                </td>
                <td>
                    Company Name: <b>{{$val->storage->company_name ?? 'N/A'}}</b> <br>
                    Manager/Owner Name: <b>{{$val->storage->manager_owner_name ?? 'N/A'}}</b> <br>
                    Company Address: <b>{{$val->storage->company_address ?? 'N/A'}}</b> <br>
                    Zip: <b>{{$val->storage->zip ?? 'N/A'}}</b>
                    <br>
                    
                    <?php 
                        $storage_charge = str_replace("$","",$val->storage_charge);
                        $late1 = \Carbon\Carbon::parse($val->storage_date);
                        if($late1->diffInDays() > 0)
                        {
                            if($val->pstatus == 11)
                            {
                                $storage_charge = $storage_charge * $late1->diffInDays();
                            }
                            else
                            {
                                $com_date1 = \Carbon\Carbon::parse($val->storage_move_date);
                                $diff1 = $late1->diffInDays($com_date1);
                                if($diff1 > 0)
                                {
                                    $storage_charge = $storage_charge * $diff1;
                                }
                            }
                        }
                    ?>
                    Storage Charge: <b>${{$storage_charge}}</b>
                    <br>
                    @if($val->pstatus == 11)
                        <span class="badge badge-danger">Pending</span>
                    @else
                        <span class="badge badge-success">Complete</span>
                    @endif
                </td>
                <td>
                    <b>Created At:</b> {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y')}}<br>
                    <b>Updated At:</b> {{\Carbon\Carbon::parse($val->updated_at)->format('M,d Y')}}<br>
                    <b>Storage Date:</b> {{\Carbon\Carbon::parse($val->storage_date)->format('M,d Y')}}<br>
                    @if(!empty($val->storage_move_date))
                    <b>Storage Moved Date:</b> {{\Carbon\Carbon::parse($val->storage_move_date)->format('M,d Y')}}<br>
                    @endif
                    
                    <?php 
                        $late = \Carbon\Carbon::parse($val->storage_date);
                    ?>
                    @if($late->diffInDays() > 0)
                        @if($val->pstatus == 11)
                            <span class="badge badge-danger">{{$late->diffInDays() > 1 ? 'Days '.$late->diffInDays() : 'Day '.$late->diffInDays()}} in storage</span>
                        @else
                        <?php
                            $com_date = \Carbon\Carbon::parse($val->storage_move_date);
                            $diff = $late->diffInDays($com_date);
                        ?>
                            @if($diff > 0)
                                <span class="badge badge-primary">{{$diff > 1 ? 'Days '.$diff : 'Day '.$diff}} in storage</span>
                            @endif
                        @endif
                    @endif
                    
                </td>
                <td>
                    @if($val->pstatus == 11 && $val->pickup_carrier_id == 0)
                        <button type="button" data-placement="top" title="Pickup By Another Carrier!" class="btn btn-outline-info btn-sm w-100" data-toggle="modal" data-target="#anotherCarrier" onclick="pickupCarrier({{$val->id}},{{$val->carrier_id ?? 0}},{{$val->pickup_carrier_id ?? 0}})">Another Carrier</button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <div>
            Showing {{$data->firstItem() ?? 0}} to {{$data->lastItem() ?? 0}} of {{$data->total()}} total
        </div>
        <div>
        {{  $data->links() }}
        </div>
    </div>

</div>
<script> 
    function pickupCarrier(id,carrier,pickup)
    {
        $("#carrier").hide();
        $("#change_carrier").children('option').removeAttr("selected");
        $("#change_carrier").children('option').each(function(){
            if($(this).val() == 'no')
            {
                $(this).attr("selected",true);
            }
        });
        $.ajax({
            url:"{{url('/updatePickupCarrier')}}",
            type:"GET",
            dataType:"JSON",
            data:{id:id},
            beforeSend:function(){
                $("#carrier_id").html('');
                $("#pickup_carrier_id").html('');
                $("#updatePickupCarrier").attr('href',`/carrier_add/${id}`);
                $("#order_id").val(id);
            },
            success:function(res)
            {
                $.each(res.oldcarriers,function(){
                    if(this.id == carrier)
                    {
                        $("#carrier_id").append(`<option value="${this.id}" selected>${this.companyname}</option>`);
                    }
                    else
                    {
                        $("#carrier_id").append(`<option value="${this.id}">${this.companyname}</option>`);
                    }
                });
               
                $("#pickup_carrier_id").append(`<option value="0">Please Add Carrier</option>`);
                $.each(res.oldcarriers,function(){
                    if(this.id == pickup)
                    {
                        $("#pickup_carrier_id").append(`<option value="${this.id}" selected>${this.companyname}</option>`);
                    }
                    else
                    {
                        $("#pickup_carrier_id").append(`<option value="${this.id}">${this.companyname}</option>`);
                    }
                });
            }
        });
    }
</script>
