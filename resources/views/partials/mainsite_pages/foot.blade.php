@if (request()->route()->getName() != 'logout_questions_answers.create')
    <a href="#top" id="back-to-top"><i class="fe fe-chevrons-up"></i></a>
@endif

<!-- Jquery js-->

<script src="{{ url('assets/js/jquery-3.5.1.min.js') }}"></script>
<script src="{{ url('assets/js/htmlCanva.min.js') }}"></script>
<script src="{{ url('assets/js/canva.js') }}"></script>


<script src="{{ url('assets/js/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>
<script src="{{ url('assets/js/jquery-cookie.js') }}"></script>
<!-- Bootstrap4 js-->
<script src="{{ url('assets/plugins/bootstrap/popper.min.js') }}"></script>
<script src="{{ url('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

<!--Othercharts js-->
<script src="{{ url('assets/plugins/othercharts/jquery.sparkline.min.js') }}"></script>

<!-- Circle-progress js-->
<script src="{{ url('assets/js/circle-progress.min.js') }}"></script>

<!-- Jquery-rating js-->
<script src="{{ url('assets/plugins/rating/jquery.rating-stars.js') }}"></script>

<!--Sidemenu js-->
<script src="{{ url('assets/plugins/sidemenu/sidemenu.js') }}"></script>

<!-- P-scroll js-->
<script src="{{ url('assets/plugins/p-scrollbar/p-scrollbar.js') }}"></script>
<script src="{{ url('assets/plugins/p-scrollbar/p-scroll1.js') }}"></script>
<script src="{{ url('assets/plugins/p-scrollbar/p-scroll.js') }}"></script>



<!-- INTERNAL Notifications js -->
<script src="{{ url('assets/plugins/notify/js/rainbow.js') }}"></script>
<script src="{{ url('assets/plugins/notify/js/sample.js') }}?id=1"></script>
<script src="{{ url('assets/plugins/notify/js/jquery.growl.js') }}"></script>
<script src="{{ url('assets/plugins/notify/js/notifIt.js') }}"></script>


<!-- INTERNAL WYSIWYG Editor js -->
<script src="{{ url('assets/plugins/wysiwyag/jquery.richtext.js') }}"></script>
<script src="{{ url('assets/js/form-editor.js') }}"></script>

@if (Route::is('chats'))
    <!-- INTERNAL Chat js-->
    <script src="{{ url('assets/js/chat.js') }}"></script>
@endif


<!-- INTERNAL Data tables -->
<script src="{{ url('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ url('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ url('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ url('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('assets/plugins/datatable/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ url('assets/js/datatables.js') }}"></script>

<!-- INTERNAL Select2 js -->
<script src="{{ url('assets/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ url('assets/js/select2.js') }}"></script>

<!-- INTERNAL Timepicker js -->
<script src="{{ url('assets/plugins/time-picker/jquery.timepicker.js') }}"></script>
<script src="{{ url('assets/plugins/time-picker/toggles.min.js') }}"></script>

<!-- INTERNAL Datepicker js -->
<script src="{{ url('assets/plugins/date-picker/date-picker.js') }}"></script>
<script src="{{ url('assets/plugins/date-picker/jquery-ui.js') }}"></script>
<script src="{{ url('assets/plugins/input-mask/jquery.maskedinput.js') }}"></script>

<!-- INTERNAL File-Uploads Js-->
<script src="{{ url('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
<script src="{{ url('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
<script src="{{ url('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
<script src="{{ url('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
<script src="{{ url('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>

<!-- INTERNAL File uploads js -->
<script src="{{ url('assets/plugins/fileupload/js/dropify.js') }}"></script>
<script src="{{ url('assets/js/filupload.js') }}"></script>

<!-- INTERNAL Multipleselect js -->
<script src="{{ url('assets/plugins/multipleselect/multiple-select.js') }}"></script>
<script src="{{ url('assets/plugins/multipleselect/multi-select.js') }}"></script>

<!--INTERNAL Sumoselect js-->
<script src="{{ url('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>

<!--INTERNAL telephoneinput js-->
<script src="{{ url('assets/plugins/telephoneinput/telephoneinput.js') }}"></script>
<script src="{{ url('assets/plugins/telephoneinput/inttelephoneinput.js') }}"></script>

<!--INTERNAL jquery transfer js-->
<script src="{{ url('assets/plugins/jQuerytransfer/jquery.transfer.js') }}"></script>

<!--INTERNAL multi js-->
<script src="{{ url('assets/plugins/multi/multi.min.js') }}"></script>

<!--INTERNAL Form Advanced Element -->
<script src="{{ url('assets/js/formelementadvnced.js') }}"></script>
<script src="{{ url('assets/js/form-elements.js') }}"></script>
<script src="{{ url('assets/js/file-upload.js') }}"></script>
<!-- Simplebar JS -->
<script src="{{ url('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
<!-- Custom js-->
<script src="{{ url('assets/js/custom.js') }}"></script>

<!-- Switcher js-->
<script src="{{ url('assets/switcher/js/switcher.js') }}"></script>

@if (Route::is('add_new'))
@endif

@if (Auth::check())
    @if (Auth::user()->role < 6 || Auth::user()->role > 7)
        <script>
            $(document).ready(function(e) {

                // setInterval(function () {
                //     $.ajax({
                //         type: "GET",

                //         url: "/get_notification",

                //         data: {'touserId': 1},

                //         dataType: "json",

                //         success: function (data) {

                //             if(data.length > 0) {
                //                 var discription = "";
                //                 $.each(data, function (i, item) {
                //                     discription = discription + '<h5>Subject : ' + item.subject + '</h5> <h5> Detail : ' + item.detail + '</h5><a href="/issue_comments_add/' + item.issueId + '" class="btn btn-info btn-sm">View</a> &nbsp; <a href="/issue_comments_done/' + item.issueId + '" class="btn btn-info btn-sm">Done</a><a href="" class="btn btn-secondary btn-sm ml-2">Reject</a></br></br>';

                //                 });
                //                 $("#getData").html(discription);
                //                 var audio = document.getElementById("noti");
                //                 audio.play();
                //                 $(".demo_changer").addClass("active");
                //                 $(".demo_changer").css({"right": "0px"});
                //             }
                //         },
                //     });
                //     $.ajax({
                //         type: "GET",

                //         url: "/get_chat_unread",

                //         data: {'touserId': 1},

                //         dataType: "json",

                //         success: function (data) {
                //             $("#getDataChat").html('');

                //             if(data.alldata.length > 0) {
                //                 $.each(data, function (i, item) {
                //                     resultObject = data.alldata;
                //                     for (i = 0; i < resultObject.length; i++) {
                //                         var discription = "";
                //                         discription = discription + '<h5>Total Unread Messages : : ' + resultObject[i].total_count + ' From ' + resultObject[i].fromuserId + " <a class='btn btn-info' href='/chats'>View</a>";
                //                         $("#getDataChat").append(discription);

                //                     }
                //                 });

                //                 var audio = document.getElementById("noti");
                //                 audio.play();
                //                 $(".demo_changer").addClass("active");
                //                 $(".demo_changer").css({"right": "0px"});
                //             }
                //         },
                //     });

                //     request3 =  $.ajax({
                //         type: "GET",

                //         url: "/get_chat_group_unread",

                //         data: {'groupId': 1},

                //         dataType: "json",

                //         success: function (data) {
                //             $("#getGroupChat").html('');

                //             if (data.alldata.length > 0) {
                //                 $.each(data, function (i, item) {
                //                     resultObject = data.alldata;
                //                     for (i = 0; i < resultObject.length; i++) {
                //                         var discription = "";
                //                         discription = discription + '<h5>Total Unread Messages : : ' + resultObject[i].total_count + ' From ' + resultObject[i].user_id + '<br>Group: ' + resultObject[i].group_id +  " <a class='btn btn-info' href='/chats'>View</a>";
                //                         $("#getGroupChat").append(discription);
                //                         $('#msg_count').html(resultObject[i].total_count);
                //                     }

                //                 });

                //                 var audio = document.getElementById("noti");
                //                 audio.play();
                //                 $(".demo_changer").addClass("active");
                //                 $(".demo_changer").css({"right": "0px"});
                //             }


                //         },
                //     });
                // }, 30000);


                var movementTimer = null;

                $(document).mousemove(function(event) {

                    clearInterval(movementTimer);

                    movementTimer = setInterval(function() {
                        var url = window.location.href;

                        $.ajax({
                            type: "GET",

                            url: "/count_activity",

                            data: {
                                'url': url
                            },

                            dataType: "json",

                            success: function(data) {
                                if (data.length > 0) {

                                }
                            },
                        });
                    }, (60 * 5) * 1000);

                });

            });
        </script>

        <script>
            // $("body").delegate(".bp1", "click", function () {
            //     setTimeout(function () {
            //         $.ajax({
            //             type: "GET",

            //             url: "/get_notes",

            //             success: function (data) {
            //                 $('.richText-editor').html(data);
            //             },

            //         });

            //     }, 200);

            // });
        </script>

        <script>
            $("#panel_type").change(function() {
                var panel_type = $('#panel_type').val();
                if (panel_type.length > 0) {
                    $('#panel_form').submit();
                }
            });

            $("#call_type").change(function() {
                var call_type = $('#call_type').val();
                if (call_type.length > 0) {
                    $('#call_form').submit();
                }
            });

            $('#view_mail').click(function() {
                $('#viewEmailType').modal('show');
            });
        </script>
        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var user = "{{ Auth::user()->id }}";
                var roleChecker = "{{ Auth::user()->role }}";

                function req() {
                    var orderID = $('#orderid_find').val();
                    var priceBtn = $('.priceReq');
                    $.ajax({
                        url: '{{ url('/get-request-order') }}',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            id: orderID
                        },
                        success: function(res) {
                            priceBtn.children().remove();
                            if (res.data) {
                                if (res.data.status == 1) {
                                    priceBtn.append('<a href="javascript:void(0)"' +
                                        'class="btn btn-success mg-r-10 completeReq">Request Price</a>');

                                    $('.completeReq').click(function() {
                                        var url = `/complete-request/${orderID}`;
                                        window.open(url, 'View Request Prices',
                                            'height=500,width=1000,left=150,top=250,toolbar=No,location=No,scrollbars=Yes,status=No,resizable=Yes,fullscreen=No,directories=No,menubar=No,copyhistory=No'
                                        );
                                    })
                                } else {
                                    priceBtn.append('<a href="javascript:void(0)"' +
                                        'class="btn btn-danger mg-r-10 alreadyReq">Request Price</a>');

                                    $('.alreadyReq').click(function() {
                                        $("body").append(
                                            '<div class="alert alert-danger w-50 m-auto alert-dismissible fade show" role="alert" style="position:fixed;top:20px;left: 50%;transform: translateX(-50%);z-index: 9999;">' +
                                            '<strong>You already send a request of this order!</strong>' +
                                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                            '<span aria-hidden="true">&times;</span>' +
                                            '</button>' +
                                            '</div>');
                                    });
                                }
                            } else {
                                priceBtn.append('<a href="javascript:void(0)"' +
                                    'class="btn btn-primary mg-r-10 request" data-toggle="modal" data-target="#chat">Request Price</a>'
                                );


                                $('.request').click(function() {
                                    var origin = $('#o_zip1').val();
                                    var destination = $('#d_zip1').val();
                                    var orderID = $('#orderid_find').val();


                                    var vehicleInfo = $('.vehicle-info').val();
                                    var year = ($(".vyear")) ? $(".vyear") : null;
                                    var model = ($(".vmodel")) ? $(".vmodel") : null;
                                    var make = ($(".vmake")) ? $(".vmake") : null;
                                    var vinNumber = ($(".vin_num")) ? $(".vin_num") : null;
                                    var type = ($(".type:checked")) ? $(".type:checked") : null;
                                    var vehicleType = ($(".vehicle-type option:selected")) ? $(
                                        ".vehicle-type option:selected") : null;
                                    var vehicleCondition = ($(
                                        ".vehicle-condition option:selected")) ? $(
                                        ".vehicle-condition option:selected") : null;
                                    var trailerType = ($(".trailer-type option:selected")) ? $(
                                        ".trailer-type option:selected") : null;

                                    var years = [];
                                    $.each(year, function() {
                                        years.push(this.value);
                                    });
                                    var models = [];
                                    $.each(model, function() {
                                        models.push(this.value);
                                    });
                                    var makes = [];
                                    $.each(make, function() {
                                        makes.push(this.value);
                                    });
                                    var vinNumbers = [];
                                    $.each(vinNumber, function() {
                                        vinNumbers.push(this.value);
                                    });
                                    var types = [];
                                    $.each(type, function() {
                                        types.push(this.value);
                                    });
                                    var vehicleTypes = [];
                                    $.each(vehicleType, function() {
                                        vehicleTypes.push(this.value);
                                    });
                                    var vehicleConditions = [];
                                    $.each(vehicleCondition, function() {
                                        vehicleConditions.push(this.value);
                                    });
                                    var trailerTypes = [];
                                    $.each(trailerType, function() {
                                        trailerTypes.push(this.value);
                                    });

                                    var roleChecker = "{{ Auth::user()->role }}";

                                    if (!(year.val() == '' || model.val() == '' || make.val() ==
                                            '' || type.val() == '' || vehicleType.val() == '' ||
                                            vehicleCondition.val() == '' || trailerType.val() == ''
                                        )) {
                                        $(this).attr('disabled', true);
                                        $.ajax({
                                            url: '{{ url('/request') }}',
                                            type: 'POST',
                                            dataType: 'json',
                                            data: {
                                                origin: origin,
                                                destination: destination,
                                                orderID: orderID,
                                                vehicleInfo: (vehicleInfo) ? vehicleInfo :
                                                    '',
                                                year: (years) ? years : '',
                                                model: (models) ? models : '',
                                                make: (makes) ? makes : '',
                                                vinNumber: (vinNumbers) ? vinNumbers : '',
                                                type: (types) ? types : '',
                                                vehicleType: (vehicleTypes) ? vehicleTypes :
                                                    '',
                                                vehicleCondition: (vehicleConditions) ?
                                                    vehicleConditions : '',
                                                trailerType: (trailerTypes) ? trailerTypes :
                                                    '',
                                            },
                                            success: function(res) {
                                                // console.log(res);
                                                if (user == "{{ Auth::user()->id }}") {
                                                    $("body").append(
                                                        '<div class="alert alert-success w-50 m-auto alert-dismissible fade show" role="alert" style="position:fixed;top:20px;left: 50%;transform: translateX(-50%);z-index: 9999;">' +
                                                        '<strong>' + res.message +
                                                        '</strong>' +
                                                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                                        '<span aria-hidden="true">&times;</span>' +
                                                        '</button>' +
                                                        '</div>');
                                                }
                                                if (roleChecker == 2 || roleChecker ==
                                                    1 || roleChecker == 9 ||
                                                    roleChecker == 13 || roleChecker ==
                                                    14) {
                                                    req();
                                                }
                                                if (roleChecker == 5) {
                                                    getRequest(res.id);
                                                }
                                            }
                                        });
                                    } else {
                                        if (user == "{{ Auth::user()->id }}") {
                                            $("body").append(
                                                '<div class="alert alert-danger w-50 m-auto alert-dismissible fade show" role="alert" style="position:fixed;top:20px;left: 50%;transform: translateX(-50%);z-index: 9999;">' +
                                                '<strong>Please fill the vehicle detail!</strong>' +
                                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                                '<span aria-hidden="true">&times;</span>' +
                                                '</button>' +
                                                '</div>');
                                        }
                                    }

                                });
                            }
                        }
                    });
                }

                function reqPrice() {
                    var orderID = $('#orderid_find').val();
                    var reqPrice1 = $('.reqPrice');
                    $.ajax({
                        url: '/get-req-price',
                        type: 'GET',
                        data: {
                            id: orderID
                        },
                        dataType: 'html',
                        success: function(res) {
                            reqPrice1.html('');
                            reqPrice1.html(res);
                            // if(res.status === true){
                            //     reqPrice1.children().remove();
                            //     reqPrice1.append('<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 border p-4 mx-1 my-3">'+
                            //         '<h4>Carrier Prices</h4>'+
                            //         '<table class="table table-hover">'+
                            //         '<tr>'+
                            //         '<th>Price 1: </th>'+
                            //         '<td>$'+res.data[0].carrier_price1+'</td>'+
                            //         '</tr>'+
                            //         '<tr>'+
                            //         '<th>Price 2: </th>'+
                            //         '<td>$'+res.data[0].carrier_price2+'</td>'+
                            //         '</tr>'+
                            //         '<tr>'+
                            //         '<th>Price 3: </th>'+
                            //         '<td>$'+res.data[0].carrier_price3+'</td>'+
                            //         '</tr>'+
                            //         '<tr>'+
                            //         '<th>Price 4: </th>'+
                            //         '<td>$'+res.data[0].carrier_price4+'</td>'+
                            //         '</tr>'+
                            //         '<tr>'+
                            //         '<th>Price 5: </th>'+
                            //         '<td>$'+res.data[0].carrier_price5+'</td>'+
                            //         '</tr>'+
                            //         '</table>'+
                            //     '</div>');   
                            // }  
                            // console.log(res);
                        }
                    })
                }

                function getRequest(reqID) {
                    var roleChecker = "{{ Auth::user()->role }}";
                    $.ajax({
                        url: '{{ url('/get-request') }}',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            id: reqID
                        },
                        success: function(res) {
                            if (res.data[0].price_checker_id == {{ Auth::id() }} && res.data[0]
                                .price_checker_id != null) {
                                if (res.status === true) {
                                    $(".priceReqModal").remove();
                                    $.each(res.data, function() {
                                        // console.log(res);
                                        var vinNUmber = '';
                                        // var condition = '';
                                        // var vin = '';
                                        // var trailer = '';
                                        if (this.vin_num) {
                                            vinNUmber = this.vin_num;
                                        }

                                        // if(this.vehicle_condition == '1')
                                        // {
                                        //     condition = 'Running';
                                        // }
                                        // else
                                        // {
                                        //     condition = 'Non Running';
                                        // }

                                        // if(this.trailer_type == '1')
                                        // {
                                        //     trailer = 'Open';
                                        // }
                                        // else
                                        // {
                                        //     trailer = 'Enclosed';
                                        // }

                                        // if(this.type == 'on')
                                        // {
                                        //     vin = 'vin';
                                        // }
                                        // else
                                        // {
                                        //     vin = 'make';
                                        // }
                                        if (roleChecker == 5) {
                                            $("body").append(
                                                '<div class="position-fixed w-100 priceReqModal" id="' +
                                                this.id +
                                                '" style="top:50%;left:50%;transform:translate(-50%,-50%);z-index:' +
                                                this.id + '">' +
                                                '<div class="modal-dialog" style="max-width:100% !important;">' +
                                                '<div class="modal-content container">' +
                                                '<div class="modal-header">' +
                                                '<h3>Price Request</h3>' +
                                                '</div>' +
                                                '<div class="modal-body">' +
                                                '<div class="col-lg-12 border text-center calculatorMain" style="overflow-x: scroll;">' +
                                                '<h4 class="mt-3 mb-0 text-left">Vehicle Information</h4>' +
                                                '<form action="/calc/update-price-request.php" method="POST">' +
                                                '<div style="form-layout margin:20px auto 0;padding: 1rem 0 0;">' +
                                                '<div class="row">' +
                                                '<table class="table table-hover">' +
                                                '<thead>' +
                                                '<tr>' +
                                                '<th scope="col">#</th>' +
                                                '<th scope="col">Origin</th>' +
                                                '<th scope="col">Destination</th>' +
                                                '<th scope="col">Miles</th>' +
                                                '<th scope="col">Type</th>' +
                                                '<th scope="col">Vin Number</th>' +
                                                '<th scope="col">Vehicle</th>' +
                                                '<th scope="col">Vehicle Type</th>' +
                                                '<th scope="col">Vehicle Condition</th>' +
                                                '<th scope="col">Trailer Type</th>' +
                                                '<th scope="col">Information</th>' +
                                                '</tr>' +
                                                '</thead>' +
                                                '<tbody>' +
                                                '<tr>' +
                                                '<td scope="row">' + this.order_id + '</td>' +
                                                '<td>' + this.origin + '</td>' +
                                                '<td>' + this.destination + '</td>' +
                                                '<td>' + this.miles + ' miles</td>' +
                                                '<td>' + this.vin + '</td>' +
                                                '<td>' + vinNUmber + '</td>' +
                                                '<td>' + this.vehicles + '</td>' +
                                                '<td>' + this.vehicle_type + '</td>' +
                                                '<td>' + this.condition + '</td>' +
                                                '<td>' + this.trailer + '</td>' +
                                                '<td>' + this.vehicle_info + '</td>' +
                                                '</tr>' +
                                                '</tbody>' +
                                                '</table>' +
                                                '<input value="' + this.id +
                                                '" class="reqID" type="hidden">' +
                                                '<div class="col-12 text-left"><h6 class="border-bottom">Car Prices</h6></div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 1</label>' +
                                                '<input class="form-control price1" value="' +
                                                this.carrier_price1 +
                                                '" required name="carrier_price1" type="text" placeholder="Carrier Price 1">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 2</label>' +
                                                '<input class="form-control price2" value="' +
                                                this.carrier_price2 +
                                                '"  required name="carrier_price2" type="text" placeholder="Carrier Price 2">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 3</label>' +
                                                '<input class="form-control price3" value="' +
                                                this.carrier_price3 +
                                                '"  required name="carrier_price3" type="text" placeholder="Carrier Price 3">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 4</label>' +
                                                '<input class="form-control price4" value="' +
                                                this.carrier_price4 +
                                                '"  required name="carrier_price4" type="text" placeholder="Carrier Price 4">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 5</label>' +
                                                '<input class="form-control price5" value="' +
                                                this.carrier_price5 +
                                                '"  required name="carrier_price5" type="text" placeholder="Carrier Price 5">' +
                                                '</div>' +
                                                '<div class="col-12 text-left"><h6 class="border-bottom">SUV Prices</h6></div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 6</label>' +
                                                '<input class="form-control price6" value="' +
                                                this.carrier_price6 +
                                                '" required name="carrier_price6" type="text" placeholder="Carrier Price 6">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 7</label>' +
                                                '<input class="form-control price7" value="' +
                                                this.carrier_price7 +
                                                '" required name="carrier_price7" type="text" placeholder="Carrier Price 7">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 8</label>' +
                                                '<input class="form-control price8" value="' +
                                                this.carrier_price8 +
                                                '" required name="carrier_price8" type="text" placeholder="Carrier Price 8">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 9</label>' +
                                                '<input class="form-control price9" value="' +
                                                this.carrier_price9 +
                                                '" required name="carrier_price9" type="text" placeholder="Carrier Price 9">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 10</label>' +
                                                '<input class="form-control price10" value="' +
                                                this.carrier_price10 +
                                                '" required name="carrier_price10" type="text" placeholder="Carrier Price 10">' +
                                                '</div>' +
                                                '<div class="col-12 text-left"><h6 class="border-bottom">Pickup Prices</h6></div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 11</label>' +
                                                '<input class="form-control price11" value="' +
                                                this.carrier_price11 +
                                                '" required name="carrier_price11" type="text" placeholder="Carrier Price 11">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 12</label>' +
                                                '<input class="form-control price12" value="' +
                                                this.carrier_price12 +
                                                '" required name="carrier_price12" type="text" placeholder="Carrier Price 12">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 13</label>' +
                                                '<input class="form-control price13" value="' +
                                                this.carrier_price13 +
                                                '" required name="carrier_price13" type="text" placeholder="Carrier Price 13">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 14</label>' +
                                                '<input class="form-control price14" value="' +
                                                this.carrier_price14 +
                                                '" required name="carrier_price14" type="text" placeholder="Carrier Price 14">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 15</label>' +
                                                '<input class="form-control price15" value="' +
                                                this.carrier_price15 +
                                                '" required name="carrier_price15" type="text" placeholder="Carrier Price 15">' +
                                                '</div>' +
                                                '<div class="col-12 text-left"><h6 class="border-bottom">Van Prices</h6></div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 16</label>' +
                                                '<input class="form-control price16" value="' +
                                                this.carrier_price16 +
                                                '" required name="carrier_price16" type="text" placeholder="Carrier Price 16">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 17</label>' +
                                                '<input class="form-control price17" value="' +
                                                this.carrier_price17 +
                                                '" required name="carrier_price17" type="text" placeholder="Carrier Price 17">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 18</label>' +
                                                '<input class="form-control price18" value="' +
                                                this.carrier_price18 +
                                                '" required name="carrier_price18" type="text" placeholder="Carrier Price 18">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 19</label>' +
                                                '<input class="form-control price19" value="' +
                                                this.carrier_price19 +
                                                '" required name="carrier_price19" type="text" placeholder="Carrier Price 19">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 20</label>' +
                                                '<input class="form-control price20" value="' +
                                                this.carrier_price20 +
                                                '" required name="carrier_price20" type="text" placeholder="Carrier Price 20">' +
                                                '</div>' +
                                                '<div class="form-group col-12 text-right">' +
                                                '<button type="button" name="submit" value="submit" class="btn btn-primary postRequest">Save</button>' +
                                                '</div>' +
                                                '</div>' +
                                                '</div>' +
                                                '</form>' +
                                                '</div>' +
                                                '</div>' +
                                                '</div>' +
                                                '</div>' +
                                                '</div>');
                                        }
                                    })

                                    $('.price1').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price1 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price2').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price2 = postRequest;
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price3').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price3 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price4').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price4 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price5').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price5 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price6').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price6 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price7').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price7 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price8').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price8 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price9').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price9 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price10').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price10 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price11').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price11 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price12').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price12 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price13').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price13 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price14').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price14 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price15').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price15 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price16').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price16 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price17').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price17 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price18').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price18 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price19').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price19 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price20').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price20 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.postRequest').click(function() {
                                        var postRequest = $(this);
                                        var price1 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price1');
                                        var price2 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price4');
                                        var price5 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price5');
                                        var price6 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-12').siblings(
                                            '.reqID').val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {
                                                if (roleChecker == 5) {
                                                    postRequest.parents('.col-12')
                                                        .children('.closePriceModal')
                                                        .remove();
                                                    btn =
                                                        '<button type="button" class="btn btn-danger closePriceModal ml-2">Close</button>';
                                                    postRequest.parents('.col-12')
                                                        .append(btn);

                                                    price1.val(res.data.carrier_price1);
                                                    price2.val(res.data.carrier_price2);
                                                    price3.val(res.data.carrier_price3);
                                                    price4.val(res.data.carrier_price4);
                                                    price5.val(res.data.carrier_price5);
                                                    price6.val(res.data.carrier_price6);
                                                    price7.val(res.data.carrier_price7);
                                                    price8.val(res.data.carrier_price8);
                                                    price9.val(res.data.carrier_price9);
                                                    price10.val(res.data
                                                        .carrier_price10);
                                                    price11.val(res.data
                                                        .carrier_price11);
                                                    price12.val(res.data
                                                        .carrier_price12);
                                                    price13.val(res.data
                                                        .carrier_price13);
                                                    price14.val(res.data
                                                        .carrier_price14);
                                                    price15.val(res.data
                                                        .carrier_price15);
                                                    price16.val(res.data
                                                        .carrier_price16);
                                                    price17.val(res.data
                                                        .carrier_price17);
                                                    price18.val(res.data
                                                        .carrier_price18);
                                                    price19.val(res.data
                                                        .carrier_price19);
                                                    price20.val(res.data
                                                        .carrier_price20);

                                                    $('.closePriceModal').click(
                                                        function() {
                                                            $(this).parents(
                                                                '.priceReqModal'
                                                            ).hide();
                                                        });
                                                    if (res.status === true) {
                                                        $("body").append(
                                                            '<div class="alert alert-success w-50 m-auto alert-dismissible fade show" role="alert" style="position:fixed;top:20px;left: 50%;transform: translateX(-50%);z-index: 9999;">' +
                                                            '<strong>' + res
                                                            .message + '</strong>' +
                                                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                                            '<span aria-hidden="true">&times;</span>' +
                                                            '</button>' +
                                                            '</div>');
                                                        // postRequest.parents('.priceReqModal').remove();
                                                    } else {

                                                        $("body").append(
                                                            '<div class="alert alert-danger w-50 m-auto alert-dismissible fade show" role="alert" style="position:fixed;top:20px;left: 50%;transform: translateX(-50%);z-index: 9999;">' +
                                                            '<strong>' + res
                                                            .message + '</strong>' +
                                                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                                            '<span aria-hidden="true">&times;</span>' +
                                                            '</button>' +
                                                            '</div>');

                                                    }

                                                }
                                                if (roleChecker == 2 || roleChecker ==
                                                    1 || roleChecker == 9 ||
                                                    roleChecker == 13 || roleChecker ==
                                                    14) {
                                                    req();
                                                    reqPrice();
                                                }
                                            }
                                        });
                                    });
                                }
                            } else {
                                location.reload();
                            }
                        }
                    });
                }

                function getRequest2(reqID) {
                    var roleChecker = "{{ Auth::user()->role }}";
                    $.ajax({
                        url: '{{ url('/get-request2') }}',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            id: reqID
                        },
                        success: function(res) {
                            if (res.data[0].price_checker_id == {{ Auth::id() }} && res.data[0]
                                .price_checker_id != null) {
                                if (res.status === true) {
                                    $.each(res.data, function() {
                                        $('#' + this.id).remove();
                                        // console.log(res);
                                        var vinNUmber = '';
                                        // var condition = '';
                                        // var vin = '';
                                        // var trailer = '';
                                        if (this.vin_num) {
                                            vinNUmber = this.vin_num;
                                        }

                                        // if(this.vehicle_condition == '1')
                                        // {
                                        //     condition = 'Running';
                                        // }
                                        // else
                                        // {
                                        //     condition = 'Non Running';
                                        // }

                                        // if(this.trailer_type == '1')
                                        // {
                                        //     trailer = 'Open';
                                        // }
                                        // else
                                        // {
                                        //     trailer = 'Enclosed';
                                        // }

                                        // if(this.type == 'on')
                                        // {
                                        //     vin = 'vin';
                                        // }
                                        // else
                                        // {
                                        //     vin = 'make';
                                        // }
                                        if (roleChecker == 5) {
                                            $("body").append(
                                                '<div class="position-fixed w-100 priceReqModal" id="' +
                                                this.id +
                                                '" style="top:50%;left:50%;transform:translate(-50%,-50%);z-index:' +
                                                (this.id - 25) + '">' +
                                                '<div class="modal-dialog" style="max-width:100% !important;">' +
                                                '<div class="modal-content container">' +
                                                '<div class="modal-header">' +
                                                '<h3>Price Request</h3>' +
                                                '</div>' +
                                                '<div class="modal-body">' +
                                                '<div class="col-lg-12 border text-center calculatorMain" style="overflow-x: scroll;">' +
                                                '<h4 class="mt-3 mb-0 text-left">Vehicle Information</h4>' +
                                                '<form action="/calc/update-price-request.php" method="POST">' +
                                                '<div style="form-layout margin:20px auto 0;padding: 1rem 0 0;">' +
                                                '<div class="row">' +
                                                '<table class="table table-hover">' +
                                                '<thead>' +
                                                '<tr>' +
                                                '<th scope="col">#</th>' +
                                                '<th scope="col">Origin</th>' +
                                                '<th scope="col">Destination</th>' +
                                                '<th scope="col">Miles</th>' +
                                                '<th scope="col">Type</th>' +
                                                '<th scope="col">Vin Number</th>' +
                                                '<th scope="col">Vehicle</th>' +
                                                '<th scope="col">Vehicle Type</th>' +
                                                '<th scope="col">Vehicle Condition</th>' +
                                                '<th scope="col">Trailer Type</th>' +
                                                '<th scope="col">Information</th>' +
                                                '</tr>' +
                                                '</thead>' +
                                                '<tbody>' +
                                                '<tr>' +
                                                '<td scope="row">' + this.order_id + '</td>' +
                                                '<td>' + this.origin + '</td>' +
                                                '<td>' + this.destination + '</td>' +
                                                '<td>' + this.miles + ' miles</td>' +
                                                '<td>' + this.vin + '</td>' +
                                                '<td>' + vinNUmber + '</td>' +
                                                '<td>' + this.vehicles + '</td>' +
                                                '<td>' + this.vehicle_type + '</td>' +
                                                '<td>' + this.condition + '</td>' +
                                                '<td>' + this.trailer + '</td>' +
                                                '<td>' + this.vehicle_info + '</td>' +
                                                '</tr>' +
                                                '</tbody>' +
                                                '</table>' +
                                                '<input value="' + this.id +
                                                '" class="reqID" type="hidden">' +
                                                '<div class="col-12 text-left"><h6 class="border-bottom">Car Prices</h6></div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 1</label>' +
                                                '<input class="form-control price1" value="' +
                                                this.carrier_price1 +
                                                '" required name="carrier_price1" type="text" placeholder="Carrier Price 1">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 2</label>' +
                                                '<input class="form-control price2" value="' +
                                                this.carrier_price2 +
                                                '"  required name="carrier_price2" type="text" placeholder="Carrier Price 2">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 3</label>' +
                                                '<input class="form-control price3" value="' +
                                                this.carrier_price3 +
                                                '"  required name="carrier_price3" type="text" placeholder="Carrier Price 3">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 4</label>' +
                                                '<input class="form-control price4" value="' +
                                                this.carrier_price4 +
                                                '"  required name="carrier_price4" type="text" placeholder="Carrier Price 4">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 5</label>' +
                                                '<input class="form-control price5" value="' +
                                                this.carrier_price5 +
                                                '"  required name="carrier_price5" type="text" placeholder="Carrier Price 5">' +
                                                '</div>' +
                                                '<div class="col-12 text-left"><h6 class="border-bottom">SUV Prices</h6></div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 6</label>' +
                                                '<input class="form-control price6" value="' +
                                                this.carrier_price6 +
                                                '" required name="carrier_price6" type="text" placeholder="Carrier Price 6">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 7</label>' +
                                                '<input class="form-control price7" value="' +
                                                this.carrier_price7 +
                                                '" required name="carrier_price7" type="text" placeholder="Carrier Price 7">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 8</label>' +
                                                '<input class="form-control price8" value="' +
                                                this.carrier_price8 +
                                                '" required name="carrier_price8" type="text" placeholder="Carrier Price 8">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 9</label>' +
                                                '<input class="form-control price9" value="' +
                                                this.carrier_price9 +
                                                '" required name="carrier_price9" type="text" placeholder="Carrier Price 9">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 10</label>' +
                                                '<input class="form-control price10" value="' +
                                                this.carrier_price10 +
                                                '" required name="carrier_price10" type="text" placeholder="Carrier Price 10">' +
                                                '</div>' +
                                                '<div class="col-12 text-left"><h6 class="border-bottom">Pickup Prices</h6></div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 11</label>' +
                                                '<input class="form-control price11" value="' +
                                                this.carrier_price11 +
                                                '" required name="carrier_price11" type="text" placeholder="Carrier Price 11">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 12</label>' +
                                                '<input class="form-control price12" value="' +
                                                this.carrier_price12 +
                                                '" required name="carrier_price12" type="text" placeholder="Carrier Price 12">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 13</label>' +
                                                '<input class="form-control price13" value="' +
                                                this.carrier_price13 +
                                                '" required name="carrier_price13" type="text" placeholder="Carrier Price 13">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 14</label>' +
                                                '<input class="form-control price14" value="' +
                                                this.carrier_price14 +
                                                '" required name="carrier_price14" type="text" placeholder="Carrier Price 14">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 15</label>' +
                                                '<input class="form-control price15" value="' +
                                                this.carrier_price15 +
                                                '" required name="carrier_price15" type="text" placeholder="Carrier Price 15">' +
                                                '</div>' +
                                                '<div class="col-12 text-left"><h6 class="border-bottom">Van Prices</h6></div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 16</label>' +
                                                '<input class="form-control price16" value="' +
                                                this.carrier_price16 +
                                                '" required name="carrier_price16" type="text" placeholder="Carrier Price 16">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 17</label>' +
                                                '<input class="form-control price17" value="' +
                                                this.carrier_price17 +
                                                '" required name="carrier_price17" type="text" placeholder="Carrier Price 17">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 18</label>' +
                                                '<input class="form-control price18" value="' +
                                                this.carrier_price18 +
                                                '" required name="carrier_price18" type="text" placeholder="Carrier Price 18">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 19</label>' +
                                                '<input class="form-control price19" value="' +
                                                this.carrier_price19 +
                                                '" required name="carrier_price19" type="text" placeholder="Carrier Price 19">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 20</label>' +
                                                '<input class="form-control price20" value="' +
                                                this.carrier_price20 +
                                                '" required name="carrier_price20" type="text" placeholder="Carrier Price 20">' +
                                                '</div>' +
                                                '<div class="form-group col-12 text-right">' +
                                                '<button type="button" name="submit" value="submit" class="btn btn-primary postRequest">Save</button>' +
                                                '</div>' +
                                                '</div>' +
                                                '</div>' +
                                                '</form>' +
                                                '</div>' +
                                                '</div>' +
                                                '</div>' +
                                                '</div>' +
                                                '</div>');
                                        }
                                    })

                                    $('.price1').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price1 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price2').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price2 = postRequest;
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price3').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price3 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price4').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price4 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price5').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price5 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price6').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price6 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price7').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price7 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price8').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price8 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price9').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price9 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price10').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price10 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price11').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price11 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price12').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price12 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price13').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price13 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price14').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price14 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price15').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price15 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price16').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price16 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price17').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price17 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price18').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price18 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price19').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price19 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price20 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price20');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.price20').on('change keyup', function() {
                                        var postRequest = $(this);
                                        var price20 = postRequest;
                                        var price2 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price4');
                                        var price1 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price1');
                                        var price6 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price19');
                                        var price5 = postRequest.parents('.col-2').siblings(
                                            '.col-2').children('.price5');
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {}
                                        });
                                    });

                                    $('.postRequest').click(function() {
                                        var postRequest = $(this);
                                        var price1 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price1');
                                        var price2 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price2');
                                        var price3 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price3');
                                        var price4 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price4');
                                        var price5 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price5');
                                        var price6 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price6');
                                        var price7 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price7');
                                        var price8 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price8');
                                        var price9 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price9');
                                        var price10 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price10');
                                        var price11 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price11');
                                        var price12 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price12');
                                        var price13 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price13');
                                        var price14 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price14');
                                        var price15 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price15');
                                        var price16 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price16');
                                        var price17 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price17');
                                        var price18 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price18');
                                        var price19 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price19');
                                        var price20 = postRequest.parents('.col-12').siblings(
                                            '.col-2').children('.price20');
                                        var reqID = postRequest.parents('.col-12').siblings(
                                            '.reqID').val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: {
                                                price1: price1.val(),
                                                price2: price2.val(),
                                                price3: price3.val(),
                                                price4: price4.val(),
                                                price5: price5.val(),
                                                price6: price6.val(),
                                                price7: price7.val(),
                                                price8: price8.val(),
                                                price9: price9.val(),
                                                price10: price10.val(),
                                                price11: price11.val(),
                                                price12: price12.val(),
                                                price13: price13.val(),
                                                price14: price14.val(),
                                                price15: price15.val(),
                                                price16: price16.val(),
                                                price17: price17.val(),
                                                price18: price18.val(),
                                                price19: price19.val(),
                                                price20: price20.val(),
                                                id: reqID
                                            },
                                            success: function(res) {
                                                if (roleChecker == 5) {
                                                    postRequest.parents('.col-12')
                                                        .children('.closePriceModal')
                                                        .remove();
                                                    btn =
                                                        '<button type="button" class="btn btn-danger closePriceModal ml-2">Close</button>';
                                                    postRequest.parents('.col-12')
                                                        .append(btn);

                                                    price1.val(res.data.carrier_price1);
                                                    price2.val(res.data.carrier_price2);
                                                    price3.val(res.data.carrier_price3);
                                                    price4.val(res.data.carrier_price4);
                                                    price5.val(res.data.carrier_price5);
                                                    price6.val(res.data.carrier_price6);
                                                    price7.val(res.data.carrier_price7);
                                                    price8.val(res.data.carrier_price8);
                                                    price9.val(res.data.carrier_price9);
                                                    price10.val(res.data
                                                        .carrier_price10);
                                                    price11.val(res.data
                                                        .carrier_price11);
                                                    price12.val(res.data
                                                        .carrier_price12);
                                                    price13.val(res.data
                                                        .carrier_price13);
                                                    price14.val(res.data
                                                        .carrier_price14);
                                                    price15.val(res.data
                                                        .carrier_price15);
                                                    price16.val(res.data
                                                        .carrier_price16);
                                                    price17.val(res.data
                                                        .carrier_price17);
                                                    price18.val(res.data
                                                        .carrier_price18);
                                                    price19.val(res.data
                                                        .carrier_price19);
                                                    price20.val(res.data
                                                        .carrier_price20);

                                                    $('.closePriceModal').click(
                                                        function() {
                                                            $(this).parents(
                                                                '.priceReqModal'
                                                            ).hide();
                                                        });
                                                    if (res.status === true) {
                                                        $("body").append(
                                                            '<div class="alert alert-success w-50 m-auto alert-dismissible fade show" role="alert" style="position:fixed;top:20px;left: 50%;transform: translateX(-50%);z-index: 9999;">' +
                                                            '<strong>' + res
                                                            .message + '</strong>' +
                                                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                                            '<span aria-hidden="true">&times;</span>' +
                                                            '</button>' +
                                                            '</div>');
                                                        // postRequest.parents('.priceReqModal').remove();
                                                    } else {

                                                        $("body").append(
                                                            '<div class="alert alert-danger w-50 m-auto alert-dismissible fade show" role="alert" style="position:fixed;top:20px;left: 50%;transform: translateX(-50%);z-index: 9999;">' +
                                                            '<strong>' + res
                                                            .message + '</strong>' +
                                                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                                            '<span aria-hidden="true">&times;</span>' +
                                                            '</button>' +
                                                            '</div>');

                                                    }

                                                }
                                                if (roleChecker == 2 || roleChecker ==
                                                    1 || roleChecker == 9 ||
                                                    roleChecker == 13 || roleChecker ==
                                                    14) {
                                                    req();
                                                    reqPrice();
                                                }
                                            }
                                        });
                                    });
                                }
                            } else {
                                location.reload();
                            }
                        }
                    });
                }
                if (roleChecker == 5) {
                    setInterval(function() {
                        getRequest2(0);
                    }, (1000 * 30));
                    getRequest(0);
                }

                if ("{{ \Request::segment(1) }}" == 'new_edit' || "{{ \Request::segment(1) }}" == 'add_new_heavy' ||
                    "{{ \Request::segment(1) }}" == 'add_new' || "{{ \Request::segment(1) }}" == 'add_new_freight') {
                    if (roleChecker == 2 || roleChecker == 1 || roleChecker == 9 || roleChecker == 13 || roleChecker ==
                        14) {
                        setInterval(function() {
                            reqPrice();
                            req();
                        }, (1000 * 10));
                        reqPrice();
                        req();
                    }
                    $("body").removeClass('sidenav-toggled');
                    $("aside").hover(function() {
                        $("body").toggleClass('sidenav-toggled');
                    });
                }
            })
        </script>
        <script>
            $(document).ready(function() {

                var now = new Date();
                var millisTill10 = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 5, 0, 0, 0) - now;
                if (millisTill10 < 0) {
                    millisTill10 += 86400000;
                }
                setTimeout(function() {
                    $.ajax({
                        url: "{{ url('/logoutAllAccounts') }}",
                        type: 'GET',
                        dataType: 'json',
                        success: function(res) {
                            console.log(res);
                        }
                    });
                }, millisTill10);

            });

            // function chatNoti() {
            //     $.ajax({
            //         url: "/chat-noti",
            //         type: "GET",
            //         success: function(res) {
            //             $("#message-menu").html("");
            //             $("#message-menu").html(res);
            //         }
            //     });

            //     $.ajax({
            //         url: "/chat-noti-count",
            //         type: "GET",
            //         success: function(res) {
            //             if (res.count > 99) {
            //                 var count = "99+";
            //             } else {
            //                 var count = res.count;
            //             }
            //             $("#msg_count").text("");
            //             $("#msg_count").text(count);
            //         }
            //     });
            // }

            function chatNoti() {
                $.ajax({
                    url: "/chat-noti",
                    type: "GET",
                    success: function(res) {
                        $("#message-menu").html("");
                        $("#message-menu").html(res);
                        // console.log('mainsite_pagess', res);

                        if (res.newMessages && res.newMessages.length > 0) {
                            res.newMessages.forEach(message => {
                                const createdAt = new Date(message.created_at);
                                const now = new Date();
                                const diffInSeconds = (now - createdAt) / 1000;

                                if (diffInSeconds <= 10) {
                                    openChatWindow(message
                                        .chat_id);
                                }
                            });
                        }
                    }
                });

                $.ajax({
                    url: "/chat-noti-count",
                    type: "GET",
                    success: function(res) {

                        $("#msg_count").text("");
                        $("#msg_count").text(res.count);
                    }
                });
            }

            function openChatWindow(chatId) {
                // console.log('Opening chat window for chatIdss:', chatId);
                let chatWindow = document.querySelector(`.ChatBodyNew`);

                if (chatWindow) {
                    // console.log('sad');
                    chatWindow.style.display = "show";
                }
            }

            chatNoti();
            setInterval(function() {
                chatNoti();
            }, 1000 * 180);
        </script>
        <script>
            let audio;

            const mutedAudio = new Audio("{{ asset('alert/alertSound.mp3') }}");
            mutedAudio.muted = true;
            mutedAudio.play().then(() => {
                console.log('Muted audio played to enable sound playback');
            }).catch((err) => {
                console.error('Autoplay failed:', err);
            });

            function playCustomBeep() {
                audio = new Audio("{{ asset('alert/alertSound.mp3') }}");
                audio.volume = 0.2;
                audio.play().catch((error) => {
                    console.log('Audio playback failed:', error);
                });
            }

            function checkNewChat() {
                $.ajax({
                    url: "/checkNewChat",
                    type: "GET",
                    success: function(res) {
                        console.log('true', res.new_found);
                        if (res.new_found == 1) {
                            playCustomBeep();
                        }
                    }
                });
            }
            var currentRoute = "{{ Route::currentRouteName() }}";

            if (currentRoute === "dashboard") {
                setInterval(checkNewChat, 10000);
            }
        </script>
        {{-- <script>
            function playBeep2() {
                const audioContext = new(window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                oscillator.type = 'square';
                oscillator.frequency.setValueAtTime(600, audioContext.currentTime);
                gainNode.gain.setValueAtTime(1, audioContext.currentTime);
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                oscillator.start();
                setTimeout(() => {
                    oscillator.stop();
                    audioContext.close();
                }, 300);
            }

            console.log('check playBeeps');

            function checkNewChat() {
                $.ajax({
                    url: "/checkNewChat",
                    type: "GET",
                    success: function(res) {
                        console.log('playBeeps', res.new_found);
                        if (res.new_found == 1) {
                            playBeep2();
                        }
                    }
                });
            }
            setInterval(checkNewChat, 10000);
        </script> --}}
    @endif
@endif
