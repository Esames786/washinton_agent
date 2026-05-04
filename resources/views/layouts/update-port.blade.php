<div class="modal fade" id="updatePortDetail{{$port->id}}" tabindex="-1" role="dialog" aria-labelledby="updatePortDetail{{$port->id}}Label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updatePortDetail{{$port->id}}Label">Update Port Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="#" type="POST">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                <input type="hidden" id="port_detail_id" value="{{$port->id}}" name="port_detail_id" >
                <div class="form-group">
                    <label for="delivery_address{{$port->id}}">Select Delivery Address</label>
                    <select class="form-control" id="delivery_address{{$port->id}}" name="delivery_address">
                      <option value="" selected>Select Delivery Address</option>
                      <option value="1" @if($port->delivery_address == 1) selected @endif>SALLAUM LINES DELIVERY ADDRESSES</option>
                      <option value="2" @if($port->delivery_address == 2) selected @endif>Grimaldi Group Shipping Line</option>
                    </select>
                </div>
                </div>
                 <div class="col-md-6">
                <div class="form-group">
                    <label for="twic_card{{$port->id}}">Select Twic Card</label>
                    <select class="form-control" id="twic_card{{$port->id}}" name="twic_card">
                      <option value="0" @if($port->twic_card == 0) selected @endif>No TWIC Card required</option>
                      <option value="1" @if($port->twic_card == 1) selected @endif>TWIC card required for entry</option>
                      <option value="2" @if($port->twic_card == 2) selected @endif>TWIC card required for entry (Optional)</option>
                    </select>
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                    <label for="port_name{{$port->id}}">Port Name</label>
                    <input type="text" class="form-control" value="{{$port->port_name}}" id="port_name{{$port->id}}" name="port_name" placeholder="Port Name">
                </div>
                </div>
                 <div class="col-md-6">
                <div class="form-group">
                    <label for="terminal_name{{$port->id}}">Terminal Name</label>
                    <input type="text" class="form-control" value="{{$port->terminal}}" id="terminal_name{{$port->id}}" name="terminal_name" placeholder="Terminal Name">
                </div>
                 </div>
                 <div class="col-md-12">
                <div class="form-group">
                    <label for="address{{$port->id}}">Address</label>
                    <input type="text" class="form-control" value="{{$port->address}}" id="address{{$port->id}}" name="address" placeholder="Address">
                </div>
                </div>
                 <div class="col-md-3">
                <div class="form-group">
                    <label for="zip{{$port->id}}">Zip</label>
                    <input type="text" class="form-control" value="{{$port->zip}}" id="zip{{$port->id}}" name="zip" placeholder="Zip">
                </div>
                  </div>
                 <div class="col-md-3">
                <div class="form-group">
                    <label for="state{{$port->id}}">State</label>
                    <input type="text" class="form-control" value="{{$port->state}}" id="state{{$port->id}}" name="state" placeholder="State">
                </div>
                  </div>
                 <div class="col-md-6">
                <div class="form-group">
                    <label for="city{{$port->id}}">City</label>
                    <input type="text" class="form-control" value="{{$port->city}}" id="city{{$port->id}}" name="city" placeholder="City">
                </div>
                </div>
                <div class="col-md-12">
                <div class="form-group">
                    <label for="telephone{{$port->id}}">Telephone</label>
                    <input type="text" class="form-control" value="{{$port->tel}}" id="telephone{{$port->id}}" name="telephone" placeholder="Telephone">
                </div>
                </div>
                <div class="col-md-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" id="make_sure{{$port->id}}" name="make_sure" @if($port->make_sure == 1) checked @endif>
                  <label class="form-check-label" for="make_sure{{$port->id}}">
                    Make Sure Address
                  </label>
                </div>
                </div>
                <div class="col-md-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" id="accident_vehicle{{$port->id}}" name="accident_vehicle" @if($port->accident_vehicle == 1) checked @endif>
                  <label class="form-check-label" for="accident_vehicle{{$port->id}}">
                    NO INOP OR ACCIDENT VEHICLE
                  </label>
                </div>
                </div>
                
                 </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary updatePort" onclick="updateNewPort({{$port->id}})">Update</button>
            </div>
        </form>
    </div>
  </div>
</div>

<script>
    function updateNewPort(id)
    {
        var delivery_address = $("#delivery_address"+id);
        var port_name = $("#port_name"+id);
        var terminal_name = $("#terminal_name"+id);
        var make_sure = 0;
        if($("#make_sure"+id).is(":checked"))
        {
            make_sure = 1;
        }
        var accident_vehicle = 0;
        if($("#accident_vehicle"+id).is(":checked"))
        {
            accident_vehicle = 1;
        }
        var address = $("#address"+id);
        var zip = $("#zip"+id);
        var state = $("#state"+id);
        var city = $("#city"+id);
        var telephone = $("#telephone"+id);
        var twic_card = $("#twic_card"+id).children("option:selected").val();
        
        delivery_address.parent().children('.text-danger').remove();
        port_name.parent().children('.text-danger').remove();
        terminal_name.parent().children('.text-danger').remove();
        
        if(delivery_address.children("option:selected").val() == '')
        {
            delivery_address.parent().append('<span class="text-danger">This field is required!</span>');
        }
        if(port_name.val() == '')
        {
            port_name.parent().append('<span class="text-danger">This field is required!</span>');
        }
        if(terminal_name.val() == '')
        {
            terminal_name.parent().append('<span class="text-danger">This field is required!</span>');
        }
        if(delivery_address.children("option:selected").val() != '' && port_name.val() != '' && terminal_name.val() != '')
        {
            $.ajax({
                url:"{{url('/update-port')}}",
                type:"POST",
                data:{
                    id:id,
                    delivery_address:delivery_address.children("option:selected").val(),
                    port_name:port_name.val(),
                    terminal_name:terminal_name.val(),
                    make_sure:make_sure,
                    accident_vehicle:accident_vehicle,
                    address:address.val(),
                    zip:zip.val(),
                    state:state.val(),
                    city:city.val(),
                    telephone:telephone.val(),
                    twic_card:twic_card
                },
                success:function(data){
                    window.location.reload();
                }
            });
        }
    }
</script>