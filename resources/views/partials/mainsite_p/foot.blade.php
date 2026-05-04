<a href="#top" id="back-to-top"><i class="fe fe-chevrons-up"></i></a>

<!-- Jquery js-->
<script src="{{ url('assets/js/jquery-3.5.1.min.js') }}"></script>
<script src="{{ url('assets/js/htmlCanva.min.js') }}"></script>
<script src="{{ url('assets/js/canva.js') }}"></script>

<script src="{{ url('assets/js/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>

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

<!-- INTERNAL WYSIWYG Editor js -->
<script src="{{ url('assets/plugins/wysiwyag/jquery.richtext.js') }}"></script>
<script src="{{ url('assets/js/form-editor.js') }}"></script>


<!--INTERNAL Peitychart js-->
<script src="{{ url('assets/plugins/peitychart/jquery.peity.min.js') }}"></script>
<script src="{{ url('assets/plugins/peitychart/peitychart.init.js') }}"></script>

<!--INTERNAL Apexchart js-->
<script src="{{ url('assets/js/apexcharts.js') }}"></script>
<script src="{{ url('assets/js/apexchart-custom.js') }}"></script>

<!--INTERNAL ECharts js-->
<script src="{{ url('assets/plugins/echarts/echarts.js') }}"></script>

<!--INTERNAL Chart js -->
<script src="{{ url('assets/plugins/chart/chart.bundle.js') }}"></script>
<script src="{{ url('assets/plugins/chart/utils.js') }}"></script>

<!-- INTERNAL Select2 js -->
<script src="{{ url('assets/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ url('assets/js/select2.js') }}"></script>

<!--INTERNAL Moment js-->
<script src="{{ url('assets/plugins/moment/moment.js') }}"></script>

<!--INTERNAL Index js-->
{{--
<script src="{{ url('assets/js/index1.js')}}"></script> --}}

<!-- Simplebar JS -->
<script src="{{ url('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
<!-- Custom js-->
<script src="{{ url('assets/js/custom.js') }}"></script>

<!-- Switcher js-->
<script src="{{ url('assets/switcher/js/switcher.js') }}"></script>

@if (Auth::check())
    @if (Auth::user()->role != 6 || Auth::user()->role != 7)
        <script>
            $(document).ready(function(e) {

                // setInterval(function () {
                //     var request = null;
                //     if(request && request.readyState != 4){
                //         request.abort();
                //     }
                //     request = $.ajax({
                //         type: "GET",

                //         url: "/get_notification",

                //         data: {'touserId': 1},

                //         dataType: "json",

                //         success: function (data) {
                //             if (data.length > 0) {
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

                //     var request2 = null;
                //     if(request2 && request2.readyState != 4){
                //         request2.abort();
                //     }
                //     request2 =  $.ajax({
                //         type: "GET",

                //         url: "/get_chat_unread",

                //         data: {'touserId': 1},

                //         dataType: "json",

                //         success: function (data) {
                //             $("#getDataChat").html('');

                //             if (data.alldata.length > 0) {
                //                 $.each(data, function (i, item) {
                //                     resultObject = data.alldata;
                //                     for (i = 0; i < resultObject.length; i++) {
                //                         var discription = "";
                //                         discription = discription + '<h5>Total Unread Messages : : ' + resultObject[i].total_count + ' From ' + resultObject[i].fromuserId + " <a class='btn btn-info' href='/chats'>View</a>";
                //                         $("#getDataChat").append(discription);
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
                //     var request3 = null;
                //     if(request3 && request3.readyState != 4){
                //         request3.abort();
                //     }
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

        @php        $respn = trim("$_SERVER[REQUEST_URI]", '/'); @endphp

        @if ($respn == 'dashboard')

            @if (Auth::user()->role == 1)
                <script>
                    <?php $datee = date('Y'); ?>
                    var options3 = {
                        series: [{
                            name: 'New Order',
                            data: [
                                <?php
                                for ($a = 0; $a < 5; $a++) {
                                    echo $chart[$a] . ',';
                                }
                                ?>

                            ]
                        }, {
                            name: 'Not Answered',
                            data: [
                                <?php
                                for ($a = 5; $a < 10; $a++) {
                                    echo $chart[$a] . ',';
                                }
                                ?>

                            ]
                        }, {
                            name: 'Booked',
                            data: [
                                <?php
                                for ($a = 10; $a < 15; $a++) {
                                    echo $chart[$a] . ',';
                                }
                                ?>
                            ]
                        }, {
                            name: 'Completed',
                            data: [
                                <?php
                                for ($a = 15; $a < 20; $a++) {
                                    echo $chart[$a] . ',';
                                }
                                ?>
                            ]
                        }, {
                            name: 'Cancelled',
                            data: [
                                <?php
                                for ($a = 20; $a < 25; $a++) {
                                    echo $chart[$a] . ',';
                                }
                                ?>
                            ]
                        }],
                        colors: ['#705ec8', '#fa057a', '#2dce89', '#ff5b51', '#fcbf09'],
                        chart: {
                            type: 'bar',
                            height: 340,
                            stacked: true,
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                            },
                        },
                        stroke: {
                            width: 2,
                            colors: ['#fff']
                        },
                        xaxis: {
                            categories: [{{ $datee }}, {{ $datee = $datee - 1 }}, {{ $datee = $datee - 1 }},
                                {{ $datee = $datee - 1 }}, {{ $datee = $datee - 1 }}
                            ],
                            labels: {
                                formatter: function(val) {
                                    return val + ""
                                }
                            }
                        },
                        yaxis: {
                            title: {
                                text: undefined
                            },
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return val + " ORDERS"
                                }
                            }
                        },
                        fill: {
                            opacity: 2
                        },
                        legend: {
                            show: false,
                            position: 'top',
                            horizontalAlign: 'left',
                            offsetX: 10
                        }
                    };
                    var chart3 = new ApexCharts(document.querySelector("#chart3"), options3);
                    chart3.render();
                </script>
            @endif

            <script>
                var options4 = {
                    series: [
                        <?php
                        foreach ($get_month as $totalorder) {
                            echo $totalorder . ',';
                        }
                        
                        ?>
                    ],
                    colors: [
                        '#7dc778',
                        '#4d8f1b',
                        '#2dce89',
                        '#ff5b51',
                        '#ce0020',
                        '#120002',
                        '#ff6ef1',
                        '#ff00ff',
                        '#ff0070',
                        '#a8ffa1',
                        '#00a0fc',
                        '#750079',
                        '#fc7900',
                        '#0100fc',
                        '#fcbf09',
                        '#ff0027'
                    ],
                    chart: {
                        height: 400,
                        type: 'donut',
                    },
                    labels: [
                        'New',
                        'Interested',
                        'FollowUp',
                        'Asking Low',
                        'Not Interested',
                        'NotAnswer',
                        'TimeQuote',
                        'Payment Missing',
                        'Booked',
                        'Listed',
                        'Dispatch',
                        'Pickedup',
                        'Deliver',
                        'Completed',
                        'Cancel',
                        'Deleted'
                    ],
                    legend: {
                        show: false,
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 400
                            },
                            legend: {
                                show: false,
                                position: 'bottom'
                            }
                        }
                    }]
                };
                var chart4 = new ApexCharts(document.querySelector("#chart4"), options4);
                chart4.render();
            </script>

        @endif

        <script>
            function chart_view() {

                var yearv = $('#yearid').val();
                var monthv = $('#monthid').val();
                var penaltypev = $('#panel_type2').val();

                //alert(penaltypev);


                $.ajax({
                    url: '/chart_view?year=' + yearv + '&month=' + monthv + '&penaly_type=' + penaltypev,
                    type: 'get',
                    success: function(data) {

                        $('#chart4').html('');

                        var options44 = {
                            series: data,
                            colors: [
                                '#7dc778',
                                '#4d8f1b',
                                '#2dce89',
                                '#ff5b51',
                                '#ce0020',
                                '#120002',
                                '#ff6ef1',
                                '#ff00ff',
                                '#ff0070',
                                '#a8ffa1',
                                '#00a0fc',
                                '#750079',
                                '#fc7900',
                                '#0100fc',
                                '#fcbf09',
                                '#ff0027'
                            ],
                            chart: {
                                height: 400,
                                type: 'donut',
                            },
                            labels: [
                                'New',
                                'Interested',
                                'FollowUp',
                                'Asking Low',
                                'Not Interested',
                                'NotAnswer',
                                'TimeQuote',
                                'Payment Missing',
                                'Booked',
                                'Listed',
                                'Dispatch',
                                'Pickedup',
                                'Deliver',
                                'Completed',
                                'Cancel',
                                'Deleted'
                            ],
                            legend: {
                                show: false,
                            },
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    chart: {
                                        width: 400
                                    },
                                    legend: {
                                        show: false,
                                        position: 'bottom'
                                    }
                                }
                            }]
                        };
                        var chart44 = new ApexCharts(document.querySelector("#chart4"), options44);
                        chart44.render();

                    },

                });

            }
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
                    alert();
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

                                    var year = $(".vyear");
                                    var model = $(".vmodel");
                                    var make = $(".vmake");
                                    var vinNumber = $(".vin_num");
                                    var type = $(".type:checked");
                                    var vehicleType = $(".vehicle-type option:selected");
                                    var vehicleCondition = $(".vehicle-condition option:selected");
                                    var trailerType = $(".trailer-type option:selected");

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
                                    console.log(roleChecker);
                                    // console.log(models);
                                    // console.log(makes);
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
                                                vehicleInfo: vehicleInfo,
                                                year: years,
                                                model: models,
                                                make: makes,
                                                vinNumber: vinNumbers,
                                                type: types,
                                                vehicleType: vehicleTypes,
                                                vehicleCondition: vehicleConditions,
                                                trailerType: trailerTypes
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
                var show_div = "";

                function getRequest(reqID) {
                    var emp_access_phone = @json(auth()->user()->emp_access_phone);
                    var emp_access_web = @json(auth()->user()->emp_access_web);
                    var emp_access_test = @json(auth()->user()->emp_access_test);
                    var arrayResult_phone = emp_access_phone.split(',');
                    var arrayResult_web = emp_access_web.split(',');
                    // var arrayResult_test = emp_access_test.split(',');

                    var roleChecker = "{{ Auth::user()->role }}";
                    var permission_pass = true;
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

                                var dataArray = Object.values(res.data);

                                var pickupCity = res.last_order.order.origincity;
                                var dropoffCity = res.last_order.order.destinationcity;
                                let states = res.states;
                                let pickupState = res.last_order.order.originstate;
                                let dropoffState = res.last_order.order.destinationstate;

                                let pickupOptions = `<option value="">Select State</option>`;
                                let dropoffOptions = `<option value="">Select State</option>`;

                                states.forEach(state => {
                                    pickupOptions +=
                                        `<option value="${state}" ${state === pickupState ? 'selected' : ''}>${state}</option>`;
                                    dropoffOptions +=
                                        `<option value="${state}" ${state === dropoffState ? 'selected' : ''}>${state}</option>`;
                                });
                                if (res.status === true) {

                                    $(".priceReqModal").remove();
                                    $.each(res.data, function(index, val) {

                                        // if (this.order.car_type == 3) {
                                        //     if (arrayResult_phone.includes("93") || arrayResult_web.includes("93")) {
                                        //         permission_pass = true;
                                        //     } else {
                                        //         permission_pass = false;
                                        //     }
                                        // } else {
                                        //     permission_pass = true;
                                        // }
                                        //
                                        // if (permission_pass) {

                                        var vinNUmber = '';
                                        if (this.vin_num) {
                                            vinNUmber = this.vin_num;
                                        }

                                        var price1 = (JSON.parse(this.carrier_price1)) ? JSON.parse(
                                            this
                                            .carrier_price1) : ['0'];
                                        var price2 = (JSON.parse(this.carrier_price2) ? JSON.parse(
                                            this
                                            .carrier_price2) : ['0']);
                                        var price3 = (JSON.parse(this.carrier_price3) ? JSON.parse(
                                            this
                                            .carrier_price3) : ['0']);
                                        var price4 = (JSON.parse(this.carrier_price4) ? JSON.parse(
                                            this
                                            .carrier_price4) : ['0']);
                                        var price5 = (JSON.parse(this.carrier_price5) ? JSON.parse(
                                            this
                                            .carrier_price5) : ['0']);
                                        var price6 = (JSON.parse(this.carrier_price6) ? JSON.parse(
                                            this
                                            .carrier_price6) : ['0']);
                                        var price7 = (JSON.parse(this.carrier_price7) ? JSON.parse(
                                            this
                                            .carrier_price7) : ['0']);
                                        var price8 = (JSON.parse(this.carrier_price8) ? JSON.parse(
                                            this
                                            .carrier_price8) : ['0']);
                                        var price9 = (JSON.parse(this.carrier_price9) ? JSON.parse(
                                            this
                                            .carrier_price9) : ['0']);
                                        var price10 = (JSON.parse(this.carrier_price10) ? JSON
                                            .parse(
                                                this.carrier_price10) : ['0']);
                                        var price11 = (JSON.parse(this.carrier_price11) ? JSON
                                            .parse(
                                                this.carrier_price11) : ['0']);
                                        var price12 = (JSON.parse(this.carrier_price12) ? JSON
                                            .parse(
                                                this.carrier_price12) : ['0']);
                                        var price13 = (JSON.parse(this.carrier_price13) ? JSON
                                            .parse(
                                                this.carrier_price13) : ['0']);
                                        var price14 = (JSON.parse(this.carrier_price14) ? JSON
                                            .parse(
                                                this.carrier_price14) : ['0']);
                                        var price15 = (JSON.parse(this.carrier_price15) ? JSON
                                            .parse(
                                                this.carrier_price15) : ['0']);
                                        var price16 = (JSON.parse(this.carrier_price16) ? JSON
                                            .parse(
                                                this.carrier_price16) : ['0']);
                                        var price17 = (JSON.parse(this.carrier_price17) ? JSON
                                            .parse(
                                                this.carrier_price17) : ['0']);
                                        var price18 = (JSON.parse(this.carrier_price18) ? JSON
                                            .parse(
                                                this.carrier_price18) : ['0']);
                                        var price19 = (JSON.parse(this.carrier_price19) ? JSON
                                            .parse(
                                                this.carrier_price19) : ['0']);
                                        var price20 = (JSON.parse(this.carrier_price20) ? JSON
                                            .parse(
                                                this.carrier_price20) : ['0']);
                                        var prices = "";
                                        // chan
                                        $.each(price1, function(index, val) {
                                            prices += `
                                                            <div class="row">
                                                                <div class="col-12 text-left">
                                                                    <h6 class="border-bottom chk_pr">Car Prices</h6>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 1</label>
                                                                    <input class="form-control price1" value="${(price1[index] !== 'null') ? price1[index] : 0}" required
                                                                        name="price1[]" type="number" placeholder="Carrier Price 1">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 1</label>
                                                                    <input class="form-control" required name="pickup_city1[]" value="${pickupCity}" type="text" placeholder="Pickup City 1">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 1</label>
                                                                    <select class="form-control" required name="pickup_state1[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 1</label>
                                                                    <input class="form-control" required name="dropoff_city1[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 1">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 1</label>
                                                                    <select class="form-control" required name="dropoff_state1[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 2</label>
                                                                    <input class="form-control price2" value="${(price2[index] !== 'null') ? price2[index] : 0}" required
                                                                        name="price2[]" type="number" placeholder="Carrier Price 1">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 2</label>
                                                                    <input class="form-control" required name="pickup_city2[]" value="${pickupCity}" type="text" placeholder="Pickup City 2">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 2</label>
                                                                    <select class="form-control" required name="pickup_state2[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                    <!-- <input class="form-control" required name="pickup_state2[]" type="text" placeholder="Pickup State 2"> -->
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 2</label>
                                                                    <input class="form-control" required name="dropoff_city2[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 2">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 2</label>
                                                                    <select class="form-control" required name="dropoff_state2[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 3</label>
                                                                    <input class="form-control price3" value="${(price3[index] !== 'null') ? price3[index] : 0}" required
                                                                        name="price3[]" type="number" placeholder="Carrier Price 1">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 3</label>
                                                                    <input class="form-control" required name="pickup_city3[]" value="${pickupCity}" type="text" placeholder="Pickup City 3">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 3</label>
                                                                    <select class="form-control" required name="pickup_state3[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                    <!-- <input class="form-control" required name="pickup_state3[]" type="text" placeholder="Pickup State 3"> -->
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 3</label>
                                                                    <input class="form-control" required name="dropoff_city3[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 3">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 3</label>
                                                                    <select class="form-control" required name="dropoff_state3[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 4</label>
                                                                    <input class="form-control price4" value="${(price4[index] !== 'null') ? price4[index] : 0}" required
                                                                        name="price4[]" type="number" placeholder="Carrier Price 1">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 4</label>
                                                                    <input class="form-control" required name="pickup_city4[]" value="${pickupCity}" type="text" placeholder="Pickup City 4">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 4</label>
                                                                    <select class="form-control" required name="pickup_state4[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
    
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 4</label>
                                                                    <input class="form-control" required name="dropoff_city4[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 4">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 4</label>
                                                                    <select class="form-control" required name="dropoff_state4[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 5</label>
                                                                    <input class="form-control price5" value="${(price5[index] !== 'null') ? price5[index] : 0}" required
                                                                        name="price5[]" type="number" placeholder="Carrier Price 1">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 5</label>
                                                                    <input class="form-control" required name="pickup_city5[]" value="${pickupCity}" type="text" placeholder="Pickup City 5">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 5</label>
                                                                    <select class="form-control" required name="pickup_state5[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 5</label>
                                                                    <input class="form-control" required name="dropoff_city5[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 5">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 5</label>
                                                                    <select class="form-control" required name="dropoff_state5[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
    
                                                            <div class="row">
                                                                <div class="col-12 text-left">
                                                                    <h6 class="border-bottom chk_pr">SUV Prices</h6>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 6</label>
                                                                    <input class="form-control price6" value="${(price6[index] !== 'null') ? price6[index] : 0}" required
                                                                        name="price6[]" type="number" placeholder="Carrier Price 6">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 6</label>
                                                                    <input class="form-control" required name="pickup_city6[]" value="${pickupCity}" type="text" placeholder="Pickup City 6">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 6</label>
                                                                    <select class="form-control" required name="pickup_state6[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 6</label>
                                                                    <input class="form-control" required name="dropoff_city6[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 6">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 6</label>
                                                                    <select class="form-control" required name="dropoff_state6[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 7</label>
                                                                    <input class="form-control price7" value="${(price7[index] !== 'null') ? price7[index] : 0}" required
                                                                        name="price7[]" type="number" placeholder="Carrier Price 6">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 7</label>
                                                                    <input class="form-control" required name="pickup_city7[]" value=${pickupCity}"" type="text" placeholder="Pickup City 7">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 7</label>
                                                                    <select class="form-control" required name="pickup_state7[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 7</label>
                                                                    <input class="form-control" required name="dropoff_city7[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 7">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 7</label>
                                                                    <select class="form-control" required name="dropoff_state7[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 8</label>
                                                                    <input class="form-control price8" value="${(price8[index] !== 'null') ? price8[index] : 0}" required
                                                                        name="price8[]" type="number" placeholder="Carrier Price 6">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 8</label>
                                                                    <input class="form-control" required name="pickup_city8[]" value="${pickupCity}" type="text" placeholder="Pickup City 8">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 8</label>
                                                                    <select class="form-control" required name="pickup_state8[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 8</label>
                                                                    <input class="form-control" required name="dropoff_city8[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 8">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 8</label>
                                                                    <select class="form-control" required name="dropoff_state8[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 9</label>
                                                                    <input class="form-control price9" value="${(price9[index] !== 'null') ? price9[index] : 0}" required
                                                                        name="price9[]" type="number" placeholder="Carrier Price 6">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 9</label>
                                                                    <input class="form-control" required name="pickup_city9[]" value="${pickupCity}" type="text" placeholder="Pickup City 9">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 9</label>
                                                                    <select class="form-control" required name="pickup_state9[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 9</label>
                                                                    <input class="form-control" required name="dropoff_city9[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 9">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 9</label>
                                                                    <select class="form-control" required name="dropoff_state9[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 10</label>
                                                                    <input class="form-control price10" value="${(price10[index] !== 'null') ? price10[index] : 0}" required
                                                                        name="price10[]" type="number" placeholder="Carrier Price 6">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 10</label>
                                                                    <input class="form-control" required name="pickup_city10[]" value="${pickupCity}" type="text" placeholder="Pickup City 10">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 10</label>
                                                                    <select class="form-control" required name="pickup_state10[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 10</label>
                                                                    <input class="form-control" required name="dropoff_city10[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 10">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 10</label>
                                                                    <select class="form-control" required name="dropoff_state10[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
    
    
                                                            <div class="row">
                                                                <div class="col-12 text-left">
                                                                    <h6 class="border-bottom chk_pr">Pickup Prices</h6>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 11</label>
                                                                    <input class="form-control price11" value="${(price11[index] != 'null' ? price11[index] : 0)}" required
                                                                        name="price11[]" type="number" placeholder="Carrier Price 11">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 11</label>
                                                                    <input class="form-control" required name="pickup_city11[]" value="${pickupCity}" type="text" placeholder="Pickup City 11">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 11</label>
                                                                    <select class="form-control" required name="pickup_state11[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 11</label>
                                                                    <input class="form-control" required name="dropoff_city11[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 11">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 11</label>
                                                                    <select class="form-control" required name="dropoff_state11[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 12</label>
                                                                    <input class="form-control price12" value="${(price12[index] != 'null' ? price12[index] : 0)}" required
                                                                        name="price12[]" type="number" placeholder="Carrier Price 11">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 12</label>
                                                                    <input class="form-control" required name="pickup_city12[]" value="${pickupCity}" type="text" placeholder="Pickup City 12">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 12</label>
                                                                    <select class="form-control" required name="pickup_state12[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 12</label>
                                                                    <input class="form-control" required name="dropoff_city12[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 12">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 12</label>
                                                                    <select class="form-control" required name="dropoff_state12[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 13</label>
                                                                    <input class="form-control price13" value="${(price13[index] != 'null' ? price13[index] : 0)}" required
                                                                        name="price13[]" type="number" placeholder="Carrier Price 11">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 13</label>
                                                                    <input class="form-control" required name="pickup_city13[]" value="${pickupCity}" type="text" placeholder="Pickup City 13">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 13</label>
                                                                    <select class="form-control" required name="pickup_state13[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 13</label>
                                                                    <input class="form-control" required name="dropoff_city13[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 13">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 13</label>
                                                                    <select class="form-control" required name="dropoff_state13[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 14</label>
                                                                    <input class="form-control price14" value="${(price14[index] != 'null' ? price14[index] : 0)}" required
                                                                        name="price14[]" type="number" placeholder="Carrier Price 11">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 14</label>
                                                                    <input class="form-control" required name="pickup_city14[]" value="${pickupCity}" type="text" placeholder="Pickup City 14">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 14</label>
                                                                    <select class="form-control" required name="pickup_state14[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 14</label>
                                                                    <input class="form-control" required name="dropoff_city14[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 14">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 14</label>
                                                                    <select class="form-control" required name="dropoff_state14[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 15</label>
                                                                    <input class="form-control price15" value="${(price15[index] != 'null' ? price15[index] : 0)}" required
                                                                        name="price15[]" type="number" placeholder="Carrier Price 11">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 15</label>
                                                                    <input class="form-control" required name="pickup_city15[]" value="${pickupCity}" type="text" placeholder="Pickup City 15">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 15</label>
                                                                    <select class="form-control" required name="pickup_state15[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 15</label>
                                                                    <input class="form-control" required name="dropoff_city15[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 15">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 15</label>
                                                                    <select class="form-control" required name="dropoff_state15[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
    
    
                                                            <div class="row">
                                                                <div class="col-12 text-left">
                                                                    <h6 class="border-bottom chk_pr">Van Prices</h6>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 16</label>
                                                                    <input class="form-control price16" value="${(price16[index] != 'null' ? price16[index] : 0)}" required
                                                                        name="price16[]" type="number" placeholder="Carrier Price 16">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 16</label>
                                                                    <input class="form-control" required name="pickup_city16[]" value="${pickupCity}" type="text" placeholder="Pickup City 16">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 16</label>
                                                                    <select class="form-control" required name="pickup_state16[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 16</label>
                                                                    <input class="form-control" required name="dropoff_city16[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 16">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 16</label>
                                                                    <select class="form-control" required name="dropoff_state16[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 17</label>
                                                                    <input class="form-control price17" value="${(price17[index] != 'null' ? price17[index] : 0)}" required
                                                                        name="price17[]" type="number" placeholder="Carrier Price 16">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 17</label>
                                                                    <input class="form-control" required name="pickup_city17[]" value="${pickupCity}" type="text" placeholder="Pickup City 17">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 17</label>
                                                                    <select class="form-control" required name="pickup_state17[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 17</label>
                                                                    <input class="form-control" required name="dropoff_city17[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 17">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 17</label>
                                                                    <select class="form-control" required name="dropoff_state17[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 18</label>
                                                                    <input class="form-control price18" value="${(price18[index] != 'null' ? price18[index] : 0)}" required
                                                                        name="price18[]" type="number" placeholder="Carrier Price 16">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 18</label>
                                                                    <input class="form-control" required name="pickup_city18[]" value="${pickupCity}" type="text" placeholder="Pickup City 18">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 18</label>
                                                                    <select class="form-control" required name="pickup_state18[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 18</label>
                                                                    <input class="form-control" required name="dropoff_city18[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 18">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 18</label>
                                                                    <select class="form-control" required name="dropoff_state18[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 19</label>
                                                                    <input class="form-control price19" value="${(price19[index] != 'null' ? price19[index] : 0)}" required
                                                                        name="price19[]" type="number" placeholder="Carrier Price 16">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 19</label>
                                                                    <input class="form-control" required name="pickup_city19[]" value="${pickupCity}" type="text" placeholder="Pickup City 19">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 19</label>
                                                                    <select class="form-control" required name="pickup_state19[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 19</label>
                                                                    <input class="form-control" required name="dropoff_city19[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 19">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 19</label>
                                                                    <select class="form-control" required name="dropoff_state19[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black"
                                                                        style="text-align: left !important;display: inherit;">Carrier Price 20</label>
                                                                    <input class="form-control price20" value="${(price20[index] != 'null' ? price20[index] : 0)}" required
                                                                        name="price20[]" type="number" placeholder="Carrier Price 16">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup City 20</label>
                                                                    <input class="form-control" required name="pickup_city20[]" value="${pickupCity}" type="text" placeholder="Pickup City 20">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Pickup State 20</label>
                                                                    <select class="form-control" required name="pickup_state20[]">
                                                                        <option value="">Select State</option>
                                                                        ${pickupOptions}
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff City 20</label>
                                                                    <input class="form-control" required name="dropoff_city20[]" value="${dropoffCity}" type="text" placeholder="Dropoff City 20">
                                                                </div>
                                                                <div class="form-group col-2">
                                                                    <label class="form-control-label font-weight-bold tx-black">Dropoff State 20</label>
                                                                    <select class="form-control" required name="dropoff_state20[]">
                                                                        <option value="">Select State</option>
                                                                        ${dropoffOptions}
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            `;
                                        });


                                        if (roleChecker == 5) {
                                            var freight = (this.freight != null) ? this
                                                .freight : null;
                                            var show_freight = (this.freight == null) ? 'none' :
                                                '';
                                            show_div = (this.freight == null) ? '' : 'none';

                                            var hide_add = (this.car_type != 3) ? 'display:none' :
                                                '';
                                            $("body").html(`
                                                <div class="position-fixed w-100 priceReqModal" id="${this.id}" style="top:50%;left:50%;transform:translate(-50%,-50%);z-index:${this.id}">
                                                    <div class="modal-dialog" style="max-width:100% !important;">
                                                        <div class="modal-content container">
                                                            <div class="modal-header">
                                                                <h3>Price Request</h3>
                                                            </div>
                                                            <div class="modal-body" style="height:500px;overflow-y: scroll">
                                                                <div class="col-lg-12 border text-center calculatorMain" style="overflow-x: scroll;">
                                                                    <h4 class="mt-3 mb-0 text-left">Vehicle Information</h4>
    
                                                                    <form action="/calc/update-price-request.php" method="POST" id="price_form">
                                                                        <div style="form-layout margin:20px auto 0;padding: 1rem 0 0;">
                                                                            <div class="row">
                                                                                <table class="table table-hover" style="display:${show_div}">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th scope="col">#</th>
                                                                                            <th scope="col">Origin</th>
                                                                                            <th scope="col">Destination</th>
                                                                                            <th scope="col">Miles</th>
                                                                                            <th scope="col">Type</th>
                                                                                            <th scope="col">Vin Number</th>
                                                                                            <th scope="col">Vehicle</th>
                                                                                            <th scope="col">Vehicle Type</th>
                                                                                            <th scope="col">Vehicle Condition</th>
                                                                                            <th scope="col">Trailer Type</th>
                                                                                            <th scope="col">Information</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td scope="row">${this.order_id}</td>
                                                                                            <td class="get_origin"><a href="https://www.google.com/maps?q=${this.origin}" target="_blank">${this.origin}</a></td>
                                                                                            <td><a href="https://www.google.com/maps?q=${this.destination}" target="_blank">${this.destination}</a></td>
                                                                                            <td>${this.miles} miles</td>
                                                                                            <td>${this.vin}</td>
                                                                                            <td>${vinNUmber}</td>
                                                                                            <td>${this.vehicles}</td>
                                                                                            <td>${this.vehicle_type}</td>
                                                                                            <td>${this.condition}</td>
                                                                                            <td>${this.trailer}</td>
                                                                                            <td>${this.vehicle_info}</td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <table class="table table-hover" style="display:${show_freight}">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th scope="col">#</th>
                                                                                            <th scope="col">Origin</th>
                                                                                            <th scope="col">Destination</th>
                                                                                            <th scope="col">freight class</th>
                                                                                            <th scope="col">equipment type</th>
                                                                                            <th scope="col">trailer specification</th>
    
                                                                                            <th scope="col">commodity detail</th>
                                                                                            <th scope="col">commodity unit</th>
                                                                                             <th scope="col">shipment prefences</th>
    
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td scope="row">${this.order_id}</td>
                                                                                            <td>${this.origin}</td>
                                                                                            <td>${this.destination}</td>
                                                                                          <td>${freight && freight.frieght_class ? freight.frieght_class : ''}</td>
                                                                                            <td>${freight && freight.equipment_type ? freight.equipment_type.replace(/\^\*/g, '|') : ''}</td>
                                                                                            <td>${freight && freight.trailer_specification ? freight.trailer_specification.replace(/\^\*/g, '|') : ''}</td>
                                                                                            <td>${freight && freight.commodity_detail ? freight.commodity_detail : ''}</td>
                                                                                            <td>${freight && freight.commodity_unit ? freight.commodity_unit : ''}</td>
                                                                                            <td>${freight && freight.shipment_prefences ? freight.shipment_prefences : ''}</td>
    
    
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
    
                                                                                <div class="row">    <button style="float:right;${hide_add}" class="btn btn-info btn-xs add_vh" type="button" >Add Vehicle</button></div>
                                                                                <input value="${this.id}" class="reqID" name="reqID"  type="hidden">
                                                                                <div class="add_vh_detail">
                                                                                ${prices}
                                                                                </div>
    
                                                                                <div class="form-group col-12 text-right">
                                                                                    <button type="button" name="submit" value="submit" class="btn btn-primary postRequest">Save</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            `);
                                        }
                                    });

                                    $("body").delegate("#price_form", "change", function() {
                                        var postRequest = $(this);

                                        var price_form = $('#price_form').serialize();
                                        var reqID = postRequest.parents('.col-2').siblings('.reqID')
                                            .val();
                                        $.ajax({
                                            url: '/update-price-request2',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: price_form,
                                            success: function(res) {}
                                        });
                                    });

                                    $("body").delegate(".add_vh", "click", function() {
                                        var car_more = `         <div class="row">
                                                        <div class="col-12 text-left"><h6 class="border-bottom chk_pr">Car Prices</h6></div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 1</label>
                                                            <input class="form-control price1" value="" required name="price1[]" type="number" placeholder="Carrier Price 1">
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 2</label>
                                                            <input class="form-control price2" value="" required name="price2[]" type="number" placeholder="Carrier Price 2">
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 3</label>
                                                            <input class="form-control price3" value="" required name="price3[]" type="number" placeholder="Carrier Price 3">
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 4</label>
                                                            <input class="form-control price4" value="" required name="price4[]" type="number" placeholder="Carrier Price 4">
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 5</label>
                                                            <input class="form-control price5" value="" required name="price5[]" type="number" placeholder="Carrier Price 5">
                                                        </div>
                                                        <div class="col-12 text-left"><h6 class="border-bottom chk_pr">SUV Prices</h6></div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 6</label>
                                                            <input class="form-control price6" value="" required name="price6[]" type="number" placeholder="Carrier Price 6">
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 7</label>
                                                            <input class="form-control price7" value="" required name="price7[]" type="number" placeholder="Carrier Price 7">
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 8</label>
                                                            <input class="form-control price8" value="" required name="price8[]" type="number" placeholder="Carrier Price 8">
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 9</label>
                                                            <input class="form-control price9" value="" required name="price9[]" type="number" placeholder="Carrier Price 9">
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 10</label>
                                                            <input class="form-control price10" value="" required name="price10[]" type="number" placeholder="Carrier Price 10">
                                                        </div>
                                                        <div class="col-12 text-left"><h6 class="border-bottom chk_pr">Pickup Prices</h6></div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 11</label>
                                                            <input class="form-control price11" value="" required name="price11[]" type="number" placeholder="Carrier Price 11">
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 12</label>
                                                            <input class="form-control price12" value="" required name="price12[]" type="number" placeholder="Carrier Price 12">
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 13</label>
                                                            <input class="form-control price13" value="" required name="price13[]" type="number" placeholder="Carrier Price 13">
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 14</label>
                                                            <input class="form-control price14" value="" required name="price14[]" type="number" placeholder="Carrier Price 14">
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 15</label>
                                                            <input class="form-control price15" value="" required name="price15[]" type="number" placeholder="Carrier Price 15">
                                                        </div>
                                                        <div class="col-12 text-left"><h6 class="border-bottom chk_pr">Van Prices</h6></div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 16</label>
                                                            <input class="form-control price16" value="" required name="price16[]" type="number" placeholder="Carrier Price 16">
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 17</label>
                                                            <input class="form-control price17" value="" required name="price17[]" type="number" placeholder="Carrier Price 17">
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 18</label>
                                                            <input class="form-control price18" value="" required name="price18[]" type="number" placeholder="Carrier Price 18">
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 19</label>
                                                            <input class="form-control price19" value="" required name="price19[]" type="number" placeholder="Carrier Price 19">
                                                        </div>
                                                        <div class="form-group col-2">
                                                            <label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 20</label>
                                                            <input class="form-control price20" value="" required name="price20[]" type="number" placeholder="Carrier Price 20">
                                                        </div>
                                                    </div>`

                                        $('.add_vh_detail').append(car_more);
                                        if (show_div) {
                                            $('.chk_pr').hide();
                                        }
                                    });

                                    $('.postRequest').click(function() {
                                        var postRequest = $(this);
                                        var price_form = $('#price_form').serialize();
                                        var reqID = postRequest.parents('.col-12').siblings(
                                                '.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: price_form,
                                            success: function(res) {
                                                if (roleChecker == 5) {
                                                    postRequest.parents('.col-12')
                                                        .children(
                                                            '.closePriceModal')
                                                    .remove();
                                                    btn =
                                                        '<button type="button" class="btn btn-danger closePriceModal ml-2">Close</button>';
                                                    postRequest.parents('.col-12')
                                                        .append(
                                                            btn);

                                                    // price1.val(res.data.carrier_price1);
                                                    // price2.val(res.data.carrier_price2);
                                                    // price3.val(res.data.carrier_price3);
                                                    // price4.val(res.data.carrier_price4);
                                                    // price5.val(res.data.carrier_price5);
                                                    // price6.val(res.data.carrier_price6);
                                                    // price7.val(res.data.carrier_price7);
                                                    // price8.val(res.data.carrier_price8);
                                                    // price9.val(res.data.carrier_price9);
                                                    // price10.val(res.data.carrier_price10);
                                                    // price11.val(res.data.carrier_price11);
                                                    // price12.val(res.data.carrier_price12);
                                                    // price13.val(res.data.carrier_price13);
                                                    // price14.val(res.data.carrier_price14);
                                                    // price15.val(res.data.carrier_price15);
                                                    // price16.val(res.data.carrier_price16);
                                                    // price17.val(res.data.carrier_price17);
                                                    // price18.val(res.data.carrier_price18);
                                                    // price19.val(res.data.carrier_price19);
                                                    // price20.val(res.data.carrier_price20);

                                                    $('.closePriceModal').click(
                                                        function() {
                                                            $(this).parents(
                                                                    '.priceReqModal'
                                                                    )
                                                                .hide();
                                                        });
                                                    if (res.status === true) {
                                                        $("body").append(
                                                            '<div class="alert alert-success w-50 m-auto alert-dismissible fade show" role="alert" style="position:fixed;top:20px;left: 50%;transform: translateX(-50%);z-index: 9999;">' +
                                                            '<strong>' + res
                                                            .message +
                                                            '</strong>' +
                                                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                                            '<span aria-hidden="true">&times;</span>' +
                                                            '</button>' +
                                                            '</div>');
                                                        // postRequest.parents('.priceReqModal').remove();
                                                    } else {

                                                        $("body").append(
                                                            '<div class="alert alert-danger w-50 m-auto alert-dismissible fade show" role="alert" style="position:fixed;top:20px;left: 50%;transform: translateX(-50%);z-index: 9999;">' +
                                                            '<strong>' + res
                                                            .message +
                                                            '</strong>' +
                                                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                                            '<span aria-hidden="true">&times;</span>' +
                                                            '</button>' +
                                                            '</div>');

                                                    }

                                                }
                                                if (roleChecker == 2 || roleChecker ==
                                                    1 ||
                                                    roleChecker == 9 || roleChecker ==
                                                    13 ||
                                                    roleChecker == 14) {
                                                    req();
                                                    reqPrice();
                                                }
                                            }
                                        });
                                    });
                                }
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

                                        var vinNUmber = '';

                                        if (this.vin_num) {
                                            vinNUmber = this.vin_num;
                                        }

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
                                                this
                                                .carrier_price1 +
                                                '" required name="carrier_price1" type="number" placeholder="Carrier Price 1">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 2</label>' +
                                                '<input class="form-control price2" value="' +
                                                this
                                                .carrier_price2 +
                                                '"  required name="carrier_price2" type="number" placeholder="Carrier Price 2">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 3</label>' +
                                                '<input class="form-control price3" value="' +
                                                this
                                                .carrier_price3 +
                                                '"  required name="carrier_price3" type="number" placeholder="Carrier Price 3">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 4</label>' +
                                                '<input class="form-control price4" value="' +
                                                this
                                                .carrier_price4 +
                                                '"  required name="carrier_price4" type="number" placeholder="Carrier Price 4">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 5</label>' +
                                                '<input class="form-control price5" value="' +
                                                this
                                                .carrier_price5 +
                                                '"  required name="carrier_price5" type="number" placeholder="Carrier Price 5">' +
                                                '</div>' +
                                                '<div class="col-12 text-left"><h6 class="border-bottom">SUV Prices</h6></div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 6</label>' +
                                                '<input class="form-control price6" value="' +
                                                this
                                                .carrier_price6 +
                                                '" required name="carrier_price6" type="number" placeholder="Carrier Price 6">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 7</label>' +
                                                '<input class="form-control price7" value="' +
                                                this
                                                .carrier_price7 +
                                                '" required name="carrier_price7" type="number" placeholder="Carrier Price 7">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 8</label>' +
                                                '<input class="form-control price8" value="' +
                                                this
                                                .carrier_price8 +
                                                '" required name="carrier_price8" type="number" placeholder="Carrier Price 8">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 9</label>' +
                                                '<input class="form-control price9" value="' +
                                                this
                                                .carrier_price9 +
                                                '" required name="carrier_price9" type="number" placeholder="Carrier Price 9">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 10</label>' +
                                                '<input class="form-control price10" value="' +
                                                this
                                                .carrier_price10 +
                                                '" required name="carrier_price10" type="number" placeholder="Carrier Price 10">' +
                                                '</div>' +
                                                '<div class="col-12 text-left"><h6 class="border-bottom">Pickup Prices</h6></div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 11</label>' +
                                                '<input class="form-control price11" value="' +
                                                this
                                                .carrier_price11 +
                                                '" required name="carrier_price11" type="number" placeholder="Carrier Price 11">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 12</label>' +
                                                '<input class="form-control price12" value="' +
                                                this
                                                .carrier_price12 +
                                                '" required name="carrier_price12" type="number" placeholder="Carrier Price 12">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 13</label>' +
                                                '<input class="form-control price13" value="' +
                                                this
                                                .carrier_price13 +
                                                '" required name="carrier_price13" type="number" placeholder="Carrier Price 13">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 14</label>' +
                                                '<input class="form-control price14" value="' +
                                                this
                                                .carrier_price14 +
                                                '" required name="carrier_price14" type="number" placeholder="Carrier Price 14">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 15</label>' +
                                                '<input class="form-control price15" value="' +
                                                this
                                                .carrier_price15 +
                                                '" required name="carrier_price15" type="number" placeholder="Carrier Price 15">' +
                                                '</div>' +
                                                '<div class="col-12 text-left"><h6 class="border-bottom">Van Prices</h6></div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 16</label>' +
                                                '<input class="form-control price16" value="' +
                                                this
                                                .carrier_price16 +
                                                '" required name="carrier_price16" type="number" placeholder="Carrier Price 16">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 17</label>' +
                                                '<input class="form-control price17" value="' +
                                                this
                                                .carrier_price17 +
                                                '" required name="carrier_price17" type="number" placeholder="Carrier Price 17">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 18</label>' +
                                                '<input class="form-control price18" value="' +
                                                this
                                                .carrier_price18 +
                                                '" required name="carrier_price18" type="number" placeholder="Carrier Price 18">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 19</label>' +
                                                '<input class="form-control price19" value="' +
                                                this
                                                .carrier_price19 +
                                                '" required name="carrier_price19" type="number" placeholder="Carrier Price 19">' +
                                                '</div>' +
                                                '<div class="form-group col-2">' +
                                                '<label class="form-control-label font-weight-bold tx-black" style="text-align: left !important;display: inherit;">Carrier Price 20</label>' +
                                                '<input class="form-control price20" value="' +
                                                this
                                                .carrier_price20 +
                                                '" required name="carrier_price20" type="number" placeholder="Carrier Price 20">' +
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
                                    });

                                    $('.postRequest').click(function() {
                                        var postRequest = $(this);
                                        var price_form = $('#price_form').serialize();
                                        var reqID = postRequest.parents('.col-12').siblings(
                                                '.reqID')
                                            .val();
                                        var btn = '';
                                        $.ajax({
                                            url: '/update-price-request',
                                            type: 'GET',
                                            dataType: 'json',
                                            data: price_form,
                                            success: function(res) {
                                                if (roleChecker == 5) {
                                                    postRequest.parents('.col-12')
                                                        .children(
                                                            '.closePriceModal')
                                                    .remove();
                                                    btn =
                                                        '<button type="button" class="btn btn-danger closePriceModal ml-2">Close</button>';
                                                    postRequest.parents('.col-12')
                                                        .append(
                                                            btn);

                                                    // price1.val(res.data.carrier_price1);
                                                    // price2.val(res.data.carrier_price2);
                                                    // price3.val(res.data.carrier_price3);
                                                    // price4.val(res.data.carrier_price4);
                                                    // price5.val(res.data.carrier_price5);
                                                    // price6.val(res.data.carrier_price6);
                                                    // price7.val(res.data.carrier_price7);
                                                    // price8.val(res.data.carrier_price8);
                                                    // price9.val(res.data.carrier_price9);
                                                    // price10.val(res.data.carrier_price10);
                                                    // price11.val(res.data.carrier_price11);
                                                    // price12.val(res.data.carrier_price12);
                                                    // price13.val(res.data.carrier_price13);
                                                    // price14.val(res.data.carrier_price14);
                                                    // price15.val(res.data.carrier_price15);
                                                    // price16.val(res.data.carrier_price16);
                                                    // price17.val(res.data.carrier_price17);
                                                    // price18.val(res.data.carrier_price18);
                                                    // price19.val(res.data.carrier_price19);
                                                    // price20.val(res.data.carrier_price20);

                                                    $('.closePriceModal').click(
                                                        function() {
                                                            $(this).parents(
                                                                    '.priceReqModal'
                                                                    )
                                                                .hide();
                                                        });
                                                    if (res.status === true) {
                                                        $("body").append(
                                                            '<div class="alert alert-success w-50 m-auto alert-dismissible fade show" role="alert" style="position:fixed;top:20px;left: 50%;transform: translateX(-50%);z-index: 9999;">' +
                                                            '<strong>' + res
                                                            .message +
                                                            '</strong>' +
                                                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                                            '<span aria-hidden="true">&times;</span>' +
                                                            '</button>' +
                                                            '</div>');
                                                        // postRequest.parents('.priceReqModal').remove();
                                                    } else {

                                                        $("body").append(
                                                            '<div class="alert alert-danger w-50 m-auto alert-dismissible fade show" role="alert" style="position:fixed;top:20px;left: 50%;transform: translateX(-50%);z-index: 9999;">' +
                                                            '<strong>' + res
                                                            .message +
                                                            '</strong>' +
                                                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                                            '<span aria-hidden="true">&times;</span>' +
                                                            '</button>' +
                                                            '</div>');

                                                    }

                                                }
                                                if (roleChecker == 2 || roleChecker ==
                                                    1 ||
                                                    roleChecker == 9 || roleChecker ==
                                                    13 ||
                                                    roleChecker == 14) {
                                                    req();
                                                    reqPrice();
                                                }
                                            }
                                        });
                                    });

                                    // $('.postRequest').click(function(){
                                    //     var postRequest = $(this);
                                    //     var price1 = postRequest.parents('.col-12').siblings('.col-2').children('.price1');
                                    //     var price2 = postRequest.parents('.col-12').siblings('.col-2').children('.price2');
                                    //     var price3 = postRequest.parents('.col-12').siblings('.col-2').children('.price3');
                                    //     var price4 = postRequest.parents('.col-12').siblings('.col-2').children('.price4');
                                    //     var price5 = postRequest.parents('.col-12').siblings('.col-2').children('.price5');
                                    //     var price6 = postRequest.parents('.col-12').siblings('.col-2').children('.price6');
                                    //     var price7 = postRequest.parents('.col-12').siblings('.col-2').children('.price7');
                                    //     var price8 = postRequest.parents('.col-12').siblings('.col-2').children('.price8');
                                    //     var price9 = postRequest.parents('.col-12').siblings('.col-2').children('.price9');
                                    //     var price10 = postRequest.parents('.col-12').siblings('.col-2').children('.price10');
                                    //     var price11 = postRequest.parents('.col-12').siblings('.col-2').children('.price11');
                                    //     var price12 = postRequest.parents('.col-12').siblings('.col-2').children('.price12');
                                    //     var price13 = postRequest.parents('.col-12').siblings('.col-2').children('.price13');
                                    //     var price14 = postRequest.parents('.col-12').siblings('.col-2').children('.price14');
                                    //     var price15 = postRequest.parents('.col-12').siblings('.col-2').children('.price15');
                                    //     var price16 = postRequest.parents('.col-12').siblings('.col-2').children('.price16');
                                    //     var price17 = postRequest.parents('.col-12').siblings('.col-2').children('.price17');
                                    //     var price18 = postRequest.parents('.col-12').siblings('.col-2').children('.price18');
                                    //     var price19 = postRequest.parents('.col-12').siblings('.col-2').children('.price19');
                                    //     var price20 = postRequest.parents('.col-12').siblings('.col-2').children('.price20');
                                    //     var reqID = postRequest.parents('.col-12').siblings('.reqID').val();
                                    //     var btn = '';
                                    //     $.ajax({
                                    //         url:'/update-price-request',
                                    //         type:'GET',
                                    //         dataType:'json',
                                    //         data:{
                                    //             price1:price1.val(),
                                    //             price2:price2.val(),
                                    //             price3:price3.val(),
                                    //             price4:price4.val(),
                                    //             price5:price5.val(),
                                    //             price6:price6.val(),
                                    //             price7:price7.val(),
                                    //             price8:price8.val(),
                                    //             price9:price9.val(),
                                    //             price10:price10.val(),
                                    //             price11:price11.val(),
                                    //             price12:price12.val(),
                                    //             price13:price13.val(),
                                    //             price14:price14.val(),
                                    //             price15:price15.val(),
                                    //             price16:price16.val(),
                                    //             price17:price17.val(),
                                    //             price18:price18.val(),
                                    //             price19:price19.val(),
                                    //             price20:price20.val(),
                                    //             id:reqID
                                    //         },
                                    //         success:function(res){
                                    //             if(roleChecker == 5)
                                    //             {
                                    //                 postRequest.parents('.col-12').children('.closePriceModal').remove();
                                    //                 btn = '<button type="button" class="btn btn-danger closePriceModal ml-2">Close</button>';
                                    //                 postRequest.parents('.col-12').append(btn);
                                    //
                                    //                 price1.val(res.data.carrier_price1);
                                    //                 price2.val(res.data.carrier_price2);
                                    //                 price3.val(res.data.carrier_price3);
                                    //                 price4.val(res.data.carrier_price4);
                                    //                 price5.val(res.data.carrier_price5);
                                    //                 price6.val(res.data.carrier_price6);
                                    //                 price7.val(res.data.carrier_price7);
                                    //                 price8.val(res.data.carrier_price8);
                                    //                 price9.val(res.data.carrier_price9);
                                    //                 price10.val(res.data.carrier_price10);
                                    //                 price11.val(res.data.carrier_price11);
                                    //                 price12.val(res.data.carrier_price12);
                                    //                 price13.val(res.data.carrier_price13);
                                    //                 price14.val(res.data.carrier_price14);
                                    //                 price15.val(res.data.carrier_price15);
                                    //                 price16.val(res.data.carrier_price16);
                                    //                 price17.val(res.data.carrier_price17);
                                    //                 price18.val(res.data.carrier_price18);
                                    //                 price19.val(res.data.carrier_price19);
                                    //                 price20.val(res.data.carrier_price20);
                                    //
                                    //                 $('.closePriceModal').click(function(){
                                    //                     $(this).parents('.priceReqModal').hide();
                                    //                 });
                                    //                 if(res.status === true){
                                    //                     $("body").append('<div class="alert alert-success w-50 m-auto alert-dismissible fade show" role="alert" style="position:fixed;top:20px;left: 50%;transform: translateX(-50%);z-index: 9999;">'+
                                    //                         '<strong>'+res.message+'</strong>'+
                                    //                         '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                                    //                         '<span aria-hidden="true">&times;</span>'+
                                    //                         '</button>'+
                                    //                     '</div>');
                                    //                     // postRequest.parents('.priceReqModal').remove();
                                    //                 }
                                    //                 else{
                                    //
                                    //                     $("body").append('<div class="alert alert-danger w-50 m-auto alert-dismissible fade show" role="alert" style="position:fixed;top:20px;left: 50%;transform: translateX(-50%);z-index: 9999;">'+
                                    //                         '<strong>'+res.message+'</strong>'+
                                    //                         '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                                    //                         '<span aria-hidden="true">&times;</span>'+
                                    //                         '</button>'+
                                    //                     '</div>');
                                    //
                                    //                 }
                                    //
                                    //             }
                                    //             if(roleChecker == 2 || roleChecker == 1 || roleChecker == 9 || roleChecker == 13 || roleChecker == 14)
                                    //             {
                                    //                 req();
                                    //                 reqPrice();
                                    //             }
                                    //         }
                                    //     });
                                    // });
                                }
                            }
                        }
                    });
                }

                if (roleChecker == 5) {
                    setInterval(function() {
                        getRequest(0);
                    }, (10 * 60 * 1000));
                    getRequest(0);

                    setInterval(function() {
                        if (!$('.priceReqModal').is(':visible')) {
                            location.reload();
                        }
                    }, 30000);
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

            function chatNoti() {
                $.ajax({
                    url: "/chat-noti",
                    type: "GET",
                    success: function(res) {
                        $("#message-menu").html("");
                        $("#message-menu").html(res);
                    }
                });

                $.ajax({
                    url: "/chat-noti-count",
                    type: "GET",
                    success: function(res) {
                        if (res.count > 99) {
                            var count = "99+";
                        } else {
                            var count = res.count;
                        }
                        $("#msg_count").text("");
                        $("#msg_count").text(count);
                    }
                });
            }

            chatNoti();
            setInterval(function() {
                chatNoti();
            }, 1000 * 180);

            console.log('price checker', $("body").html, $("body").val());
            console.log('body val', $("body").val());
        </script>
        <script>
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
                        console.log('playBeeps2', res.new_found);
                        if (res.new_found == 1) {
                            playBeep2();
                        }
                    }
                });
            }
            var currentRoute = "{{ Route::currentRouteName() }}";

            if (currentRoute === "dashboard") {
                setInterval(checkNewChat, 10000);
            }
        </script>
    @endif
@endif
