@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]", '/');
if (isset($_GET['titlee'])) {
    $respn = $_GET['titlee'];
}

function check_counting($order_id,$carrier_id){
    $temp = 0;
    $count_carrier = App\count_carrier::where('order_id',$order_id)
        ->where('user_id',Auth::user()->id)
        ->where('carrier_id',$carrier_id)
        ->first();
    if(!empty($count_carrier)){
        $temp = $count_carrier->total_clicks;
    }
    return $temp;
}
?>
@php
    $check_panel = check_panel();

    if($check_panel == 1){

    $phoneaccess=explode(',',Auth::user()->emp_access_phone);
    }
    elseif($check_panel == 3)
    {
        $phoneaccess = explode(',',Auth::user()->emp_access_test);
    }
    else{
    $phoneaccess=explode(',',Auth::user()->emp_access_web);
    }
@endphp
<div class="table-responsive">
    <table id="example1" class="table table-striped table-bordered text-nowrap">
        <thead>
        <tr>
            <th class="border-bottom-0">Assign</th>
            <th class="border-bottom-0">TYPE</th>
            <th class="border-bottom-0">COMPANY NAME</th>
            <th class="border-bottom-0">ADDRESS</th>
            <th class="border-bottom-0">MAIN NUMBER</th>
            <th class="border-bottom-0">Click</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $val)



            <tr>
                <td>
                    <button class="btn btn-warning" onclick="find_select('{{$val->id}}','{{$order_id}}')">Select
                    </button>
                </td>
                <td>{{$val->typev}}</td>
                <?php
                $rest = "";
                if (strlen($val->company_name) > 20) {
                    $rest = substr($val->company_name, 0, 20);
                } else {
                    $rest = $val->company_name;
                }
                if (strlen($val->company_name) == 0) {
                    $rest = 'NA';
                }
                ?>
                <td><a class="@if($rest<>'NA') btn btn-outline-success btn-sm @endif" data-toggle="tooltip"
                       data-placement="bottom"
                       title="<?php echo $val->company_name ?>">
                        <?php echo $rest ?>
                    </a>
                </td>
                <td>{{$val->address}}</td>
                <td>
                    @if(in_array("60", $phoneaccess))
                        <?php 
                            $digits = \App\PhoneDigit::first();
                            if(in_array("61", $phoneaccess))
                            {
                                $new = $val->main_number;
                                $new2 = $val->local_number;
                            }
                            else
                            {
                                $new = putX($digits->hide_digits,$digits->left_right_status,$val->main_number);
                                $new2 = putX($digits->hide_digits,$digits->left_right_status,$val->local_number);
                            }
                        ?>
                    <span class="text-center pd-2 bd-l"><a onclick="count_carrier('{{$val->id}}','{{$order_id}}','{{$val->main_number}}')"
                                    class="btn btn-outline-info fa fa-phone mobile count_carrier"
                                    style="padding: 3px 5px; font-size: 20px;">{{$new}}</a><br></span>
                    <span class="text-center pd-2 bd-l"><a onclick="count_carrier('{{$val->id}}','{{$order_id}}','{{$val->local_number}}')"
                                class="btn btn-outline-info fa fa-phone mobile count_carrier"
                                style="padding: 3px 5px; font-size: 20px;">{{$new2}}</a><br></span>
                    <span class="text-center pd-2 bd-l"><a
                                onclick="window.location.href = 'rcmobile://sms?number={{$val->main_number}}'"
                                class="btn btn-outline-info fa fa-envelope sms"
                                style="padding: 3px 5px; font-size: 20px;">{{$new}}</a><br></span>
                    <span class="text-center pd-2 bd-l"><a
                            onclick="window.location.href = 'rcmobile://sms?number={{$val->local_number}}'"
                            class="btn btn-outline-info fa fa-envelope sms"
                            style="padding: 3px 5px; font-size: 20px;">{{$new2}}</a><br></span>
                    @endif
                </td>
                <td style=" justify-content: center; text-align: center; ">
                    <span  id="my_click{{$val->id}}">{{check_counting($order_id,$val->id)}}</span>
                    <br>
                    <br>
                    <button type="button" data-toggle="modal" data-target="#carrier_comment" style=" width: 50px; "
                            onclick="carrier_comments('{{$val->id}}','{{$order_id}}','{{$val->main_number}}')"
                            class="btn btn-twitter btn-sm carrier_comment">
                        <i class="fa fa-edit" data-toggle="tooltip" data-placement="bottom"
                           title="Comments!"></i>
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>


<script>

    function count_carrier( carrier_id ,order_id, client_phone) {

//        client_phone = client_phone.trim(' ');
//        client_phone = client_phone.replace(/[^\d.-]/g, '');
//
        var data = {order_id: order_id, carrier_id: carrier_id};
        $.ajax({
            type: "GET",
            url: '/count_carrier',
            dataType: "json",
            data: data,
            beforeSend: function () {

            },
            complete: function () {

            },
            success: function (response) {
                if (response) {
                    $(`#my_click${carrier_id}`).html(response);
                    window.location.href = "rcmobile://call?number=" + client_phone;

                }

            }
        });


    }

    function carrier_comments( carrier_id ,order_id, client_phone) {

        $(`#ca_order_id`).val(order_id);
        $(`#ca_carrier_id`).val(carrier_id);
        $('#find_carrier_modal').modal('hide');


        setTimeout(function(){

            var data = {order_id: order_id, carrier_id: carrier_id};
            $.ajax({
                type: "GET",
                url: '/carrier_history',
                data: data,
                beforeSend: function () {

                },
                complete: function () {

                },
                success: function (response) {
                    if (response) {

                        $('#ca_carrier_comments1').html(response.trim());

                        $('#ca_carrier_comments').focus();
                    }

                }
            });

        }, 500);



    }
    
</script>
