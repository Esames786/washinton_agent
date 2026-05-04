@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]", '/');
if (isset($_GET['titlee'])) {
    $respn = $_GET['titlee'];
}


?>
<div class="table-responsive">
    <table class="table table-bordered table-sm" style="width:100%" id="" role="grid"
           aria-describedby="example1_info">
        <thead>
        <tr>
            <th class="col-lg-2 text-center pd-10">Pickup</th>
            <th>Delivery</th>
            <th>Vehicle/Order ID</th>
            <th>Order Price/Phone</th>
            <th>Ship On/Modified</th>
            <th class="border-bottom-0">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php $order = array(); ?>
        @foreach($data2 as $val)
            <?php $order = (array) $val; ?>
            <tr>

                <td>
                    <?php
                    if($order['originstate'] || $order['origincity'] || $order['originzip'] || $order['oaddress']) { ?>
                    <a href="http://classic.mapquest.com/embed?zoom=5&q=<?php echo $order['origincity']; ?>+<?php echo $order['originstate']; ?>+<?php echo $order['originzip']; ?>"
                       target="_blank"><i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                        <?php echo $order['originstate'] . ' - ' . $order['origincity'] . ' ' . $order['originzip']; ?>
                    </a> <br> <?php echo $order['oaddress']; ?>
                    <?php } else {
                        echo "N/A";
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if($order['destinationstate'] || $order['destinationcity'] || $order['destinationzip'] || $order['daddress']) { ?>
                    <a href="http://classic.mapquest.com/embed?zoom=5&q=<?php echo $order['destinationcity']; ?>+<?php echo $order['destinationstate']; ?>+<?php echo $order['destinationzip']; ?>"
                       target="_blank"><i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                        <?php echo $order['destinationstate'] . ' - ' . $order['destinationcity'] . ' ' . $order['destinationzip']; ?>
                    </a> <br> <?php echo $order['daddress']; ?>
                    <?php } else {
                        echo "N/A";
                    }
                    ?>
                </td>
                <td>
                    <?php
                    $hd = "";
                    $heading = explode('*^-', $order['ymk']);
                    if (count($heading) == 1) {
                        if ($heading[0]) {
                            if ($heading[0] == 'Select Year Select Make Select Model') {
                                echo "N/A";
                            } else {
                                echo $heading[0];
                            }
                        } else {
                            echo "N/A";
                        }
                    } else {
                        $i = 0;
                        $len = count($heading);
                        foreach ($heading as $key => $data) {
                            if ($i == $len - 1) {
                                // last
                                $hd .= $data;
                            } else {
                                $hd .= $data . ' | ';
                            }
                            $i++;
                        }
                        echo '<a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="' . $hd . '">' . $len . ' Vehicles' . '</a>';
                    }
                    ?>
                    <br>
                    <strong>Order ID:</strong> <?php echo $order['id'];

                    ?>
                </td>
                <td>
                    <?php

                    if ($order['driver_price'] != '') {
                        echo "<b>C-Price:</b> " . "<span class='badge badge-success badge-lg' style='font-size: 15px;'>" . "$" . $order['driver_price'] . "</span>";
                    } else {
                        echo "N/A";
                    }
                    echo "<br>";


                    if ($order['payment'] != '') {
                        echo "<b>Offer-Price:</b>" . "<span class='badge badge-success badge-lg' style='font-size: 15px;'>" . "$" . $order['payment'] . "</span>";
                    } else {
                        echo "N/A";
                    }

                    if ($order['carrier_status'] == 'quick_pay') {
                        echo '<span class="badge badge-success ml-1 tx-white" style="font-size: 12px">Quick Pay</span>';
                    } elseif ($order['carrier_status'] == 'cod') {
                        echo '<span class="badge badge-success ml-1 tx-white" style="font-size: 12px">COD</span>';
                    }
                    ?>
                    <?php
                    if ($order['main_ph'] == 1) {
                        $new = substr($order['mainPhNum'], 0, -8) . 'xxx-xxxx';
                        echo "<br> <b>No:</b>" . $new;
                        echo "<br>";
                    } elseif ($order['main_ph'] == 2) {
                        echo "<p style='margin-bottom: 0px!important;'> Unknown No: " . $order['custName'] . "</p>";
                    } else {
                        echo "";
                    }
                    if ($order['oname']) {
                        echo '<b>Name: </b>' . ucwords($order['oname']);
                    }

                    ?>
                    <div style="display: flex; gap: 1rem; justify-content: center;">
                    <a onclick="window.location.href = 'rcmobile://call?number=<?php echo $order['mainPhNum']; ?>'"
                       class="btn btn-outline-info fa fa-phone mobile" style="padding: 3px 5px; font-size: 20px;"
                       name="mobile"
                       id="<?php echo $order['id']; ?>"></a>
                    <a onclick="window.location.href = 'rcmobile://sms?number=<?php echo $order['mainPhNum']; ?>'"
                       class="btn btn-outline-info fa fa-envelope sms" style="padding: 3px 5px; font-size: 20px; "
                       name="sms"
                       id="<?php echo $order['id']; ?>"></a>
            </div>
                     <div style="display: flex; gap: 1rem; justify-content: center;">
                    <a class="btn btn-primary btn-sm "
                       onclick="get_prev('<?php echo $order['origincity']  ?>','<?php echo $order['destinationcity']  ?>')"
                       target="_blank" style="">Previous-Record</a>

                        <button class="btn btn-primary btn-sm " type="button"
                           onclick="alreadyCreditCard('<?php echo $order['id']  ?>','<?php echo $order['id']  ?>')" style="    width: 100%;">Previous Card</button>
                           </div>

                      </td>
                <td>
                    <b>Modified:</b>
                    <?php echo date("m/d/y h:i a T", strtotime($order['updated_at']));
                    echo "<br>";
                    if ($order['go_status'] == "interested") {
                        $price = $order['intrested'] ? ' ' . $order['intrested'] : "N/A";
                        echo '<br><span class="badge badge-success ml-1 tx-white" style="font-size: 14px">Interested</span>
                <br><b> Comments: </b>' . $price . '
                ';
                    } elseif ($order['go_status'] == "asking_low") {
                        $price = $order['asking_low'] ? '$' . $order['asking_low'] : "N/A";
                        echo '<br><span class="badge badge-warning ml-1 tx-white" style="font-size: 14px">Asking Low</span>
                      <br> Ask. Price: ' . $price . '
                     ';
                    } elseif ($order['go_status'] == "more_follow") {
                        $price = $order['mfollow'] ? ' ' . $order['mfollow'] : "N/A";
                        echo '<br><span class="badge badge-primary ml-1 tx-white" style="font-size: 14px">More Follow</span>
                <br> <b>Comments:</b> ' . $price . '
                ';
                    } else {
                        echo '<br><span class="badge badge-dark ml-1 tx-white" style="font-size: 14px">Auto Save</span>';
                    }
                    ?>
                </td>


                <td>


                    <button type="button" data-toggle="tooltip" data-placement="top" title="Move to new!"
                            class="btn btn-success btn-sm btn-sm">
                        <a href="{{url('/move_to_new/'. $val->id)}}">
                            <i class="fa fa-paper-plane"></i>
                        </a>
                    </button>

                    <button type="button" data-toggle="tooltip" data-placement="top" title="View!"
                            class="btn btn-warning btn-sm btn-sm">
                        <a href="{{url('/old_shipa1_summary/'. $val->id)}}">
                            <i class="fa fa-eye"></i>
                        </a>
                    </button>


                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{  $data2->links() }}

</div>


<div id="alreadyCreditCard" class="modal fade" style="display: none; padding-right: 17px;">
    <div class="modal-dialog modal-dialog-vertical-center" role="document" style="width: 100%;">
        <div class="modal-content bd-0 tx-14">
            <div class="modal-header pd-y-20 pd-x-25">
                <h6 class="tx-30 mg-b-0 tx-uppercase tx-inverse tx-bold">ALREADY HAVE CARD</h6>
            </div>
            <div class="modal-body pd-25 pl-20 pr-20">
                <div class="card card-people-list mg-y-20" id="creditCardInfo">
                    <div class="slim-card-title mb-3">Credit Cards</div>
                    <div class="row" id="creditModal">

                    </div>
                </div>
            </div>
        </div>
    </div><!-- modal-dialog -->
</div>

<script>


    function alreadyCreditCard(id, obj) {

        var ob = obj.split('@');

        var data = {creditCard: id};
        $.ajax({
            type: "GET",
            url: "/old_cards_shipa1",
            dataType: 'JSON',
            data: data,
            success: function (res) {

                $("#alreadyCreditCard").modal('show');
                $("#creditModal").html('');

                if (!jQuery.isEmptyObject(res)) {
                    $.each(res, function (key, value) {
                        if (value.card_type.split('^*-')) {

                            var cardNoCheck = `${value.card_number.split('^*-')}`;
                            cardNoCheck = cardNoCheck.split(',');
                            var card_type = value.card_type.split('^*-');
                            var card_sec = value.card_sec.split('^*-');
                            var card_exp = value.card_exp.split('^*-');
                            var img = "";
                            for (var card = 0; card < card_type.length; card++) {
                                if (card_type[card] == "visa") {
                                    img = "{{ url('public') }}/visa.png";
                                }
                                else if (card_type[card] == "Amex") {
                                    img = "{{ url('public') }}/american.png";
                                }
                                else if (card_type[card] == "MC") {
                                    img = "{{ url('public') }}/master.png";
                                }
                                else if (card_type[card] == "Discover") {
                                    img = "{{ url('public') }}/discover.png";
                                }
                                else {
                                    img = "{{ url('public') }}/no-card.png";
                                }

                                var cardInfo = value.card_name + '^*-' + value.card_last_name + '^*-' + card_type[card] + '^*-' + cardNoCheck[card] + '^*-' + card_sec[card] + '^*-' + card_exp[card] + '^*-' + '';

                                var card_no = `${cardNoCheck[card].replace(/\s/g, '')}`;
                                card_no = card_no.replace(/-/g, "");
                                card_no = card_no.replace(/.(?=.{4})/g, 'X')


                                if (card_no || cardNoCheck[card]) {

                                    $("#creditModal").append(`
                                            <div class="col-lg-12">
                                                <div class="media-list">
                                                    <div class="media">
                                                        <img src="${img}" alt="">
                                                        <div class="media-body">
                                                            <a href="javascript:void(0)">${(value.card_name && value.card_last_name) ? value.card_name + ' ' + value.card_last_name : (value.card_name) ? value.card_name : "N/A"}</a>
                                                            <p>
                                                                ${card_no}
                                                            </p>
                                                            <p>
                                                                Order ID: <a href="#" style="display: unset;" target="_blank">${value.id}</a>  <br>

                                                                Email: <a href="#" style="display: unset;" target="_blank">${value.oemail}</a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        `);

                                }
                            }
                        } else {
                            var cardNoCheck = `${value.card_number.split('^*-')}`;
                            cardNoCheck = cardNoCheck.split(',');
                            var img = "";
                            if (value.card_type == "visa") {
                                img = "{{ url('public') }}/visa.png";
                            }
                            else if (value.card_type == "Amex") {
                                img = "{{ url('public') }}/american.png";
                            }
                            else if (value.card_type == "MC") {
                                img = "{{ url('public') }}/master.png";
                            }
                            else if (value.card_type == "Discover") {
                                img = "{{ url('public') }}/discover.png";
                            }
                            else {
                                img = "{{ url('public') }}/no-card.png";
                            }
                            var cardInfo = value.card_name + '^*-' + value.card_last_name + '^*-' + value.card_type + '^*-' + value.card_number + '^*-' + value.card_sec + '^*-' + value.card_exp + '^*-' + '';

                            var card_no = `${value.card_number.replace(/\s/g, '')}`;
                            card_no = card_no.replace(/-/g, "");
                            card_no = card_no.replace(/.(?=.{4})/g, 'X')

                            if (card_no || value.card_type) {
                                $("#creditModal").append(`
                                <div class="col-lg-12">
                                    <div class="media-list">
                                        <div class="media">
                                            <img src="${img}" alt="">
                                            <div class="media-body">
                                                <a href="javascript:void(0)">${(value.card_name && value.card_last_name) ? value.card_name + ' ' + value.card_last_name : (value.card_name) ? value.card_name : "N/A"}</a>
                                                <p>
                                                    ${card_no}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);
                            } else {
                                $("#creditModal").append(`
                                <div class="col-lg-12 text-center mt-2">
                                    <h4 class="tx-black">No Credit Card Data</h4>
                                </div>
                            `);
                            }
                        }
                    });
                } else {
                    $("#creditModal").append(`
                    <div class="col-lg-12 text-center">
                        <h2>No Card Data</h2>
                    </div>
                `);
                }

            }
        });

    }


    function get_prev(o_zip1,d_zip1){


        var ozip = o_zip1;
        var dzip = d_zip1;


        if (ozip == '' || dzip == '') {
            toastr.error("Please Enter Origin & Dest City or Zip", "Error");

        } else {
            ozip = ozip.split(", ");
            dzip = dzip.split(", ");

            var url = `/old_previous?ocity=${btoa(ozip[0])}&dcity=${btoa(dzip[0])}`;
            window.open(url, 'Previous Orders', 'height=400,width=600,left=350,top=250,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No');

        }

    }
</script>

<script>
    regain_call();
    regain_status();
    regain_report_modal();
</script>
