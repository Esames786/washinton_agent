@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]", '/');
if (isset($_GET['titlee'])) {
    $respn = $_GET['titlee'];
}

?>

@php
    $check_panel = check_panel();

    if ($check_panel == 1) {
        $phoneaccess = explode(',', Auth::user()->emp_access_phone);
    } 
    elseif($check_panel == 3)
    {
        $phoneaccess = explode(',',Auth::user()->emp_access_test);
    }
    else {
        $phoneaccess = explode(',', Auth::user()->emp_access_web);
    }
@endphp
<div class="row">
    <div class="col-sm-12">
        <?php
            if($display=='yes'){
        ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered text-wrap">
                <thead>
                    <tr>

                        <th class="border-bottom-0">S/No.</th>
                        <th class="border-bottom-0">CUSTOMER NAME</th>
                        @if (in_array('42', $phoneaccess))
                            <th class="border-bottom-0">PHONE NO</th>
                        @endif
                        <th class="border-bottom-0">OlD/NEW</th>
                        <th class="border-bottom-0">EMAIL</th>
                        <th class="border-bottom-0">Delivery</th>
                        <th class="border-bottom-0">Customer From</th>
                        <th class="border-bottom-0">Action</th>


                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $val)
                        <?PHP $count=0; ?>
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                {{ $val->oname }}
                            </td>
                            @if (in_array('42', $phoneaccess))
                                <td>
                                    <?php $ophone = explode('*^', $val->ophone); ?>
                                    @foreach ($ophone as $val3)
                                        <?php
                                        if (in_array('67', $phoneaccess)) {
                                            $new = $val3;
                                        } else {
                                            $digits = \App\PhoneDigit::first();
                                        
                                            $new = putX($digits->hide_digits, $digits->left_right_status, $val3);
                                        }
                                        
                                        ?>
                                        @if ($val3)
                                            <?php $count = \App\AutoOrder::where('ophone','like',"%$val3%")->count() ?>
                                            <span class="text-center pd-2 bd-l">
                                                <a onclick="call('{{ base64_encode($val3) }}')"
                                                    class="btn btn-outline-info mobile count_user"
                                                    style="padding: 3px 5px; font-size: 20px;">
                                                    <i class="fa fa-phone"></i>
                                                    <span class="">{{ $new }}</span>
                                                </a><br>
                                            </span>
                                            <br />
                                            <span class="text-center pd-2 bd-l">
                                                <a class="btn btn-outline-info sms"
                                                    onclick="msg('{{ base64_encode($val3) }}')"
                                                    style="padding: 3px 5px; font-size: 19.4px;"><i
                                                        class="fa fa-envelope"></i>&nbsp;{{ $new }}</a><br>
                                            </span>
                                        @endif
                                        <br />
                                        <span class="text-center pd-2 bd-l">
                                            <a onclick="openWhatsApp('{{ $val->ophone }}', '{{ $val->id }}')"
                                                class="btn btn-outline-success mobile count_user"
                                                style="padding: 3px 5px; font-size: 20px;">
                                                 <i class="fa fa-whatsapp"></i>
                                                <span>{{ $new }}</span>
                                             </a>
                                        </span>
                                        <script>
                                         function openWhatsApp(phone, userId) {
                                             // Format the phone number to include the country code
                                             const formattedPhone = '+' + phone.replace(/[^\d]+/g, '');
                                         
                                             // Construct the WhatsApp link
                                             const whatsappLink = `https://wa.me/${formattedPhone}`;
                                         
                                             // Open the link in a new window or tab
                                             window.open(whatsappLink, '_blank');
                                         
                                             // You can also use the following code to open in the same window
                                             // window.location.href = whatsappLink;
                                         }
                                         </script>
                                    @endforeach
                                </td>
                            @endif
                            <td>
                                {{ ($count == 1 ? "New ($count)" : "Old($count)" ) }}
                            </td>
                            <td>
                                {{ $val->oemail }}
                            </td>
                            <td>
                                {{ $val->destinationzsc }}
                            </td>
                            <td>
                                @if ($val->paneltype == 1)
                                    <span class="badge badge-secondary">Phone Customer</span>
                                @elseif ($val->paneltype == 3)
                                    <span class="badge badge-primary">Testing Customer</span>
                                @elseif ($val->paneltype == 4)
                                    <span class="badge badge-primary">Panel Type 4 Customer</span>
                                @elseif ($val->paneltype == 5)
                                    <span class="badge badge-primary">Panel Type 5 Customer</span>
                                @elseif ($val->paneltype == 6)
                                    <span class="badge badge-primary">Panel Type 6 Customer</span>
                                @else
                                    <span class="badge badge-primary">Website Customer</span>
                                @endif
                            </td>
                            <td>
                                {{-- <button type="button" onclick="historyUpdateKaro({{ $val->id }})"
                                    class="btn btn-primary get-history" data-toggle="modal" data-target="#historyUpdate">History
                                    Update</button> --}}

                                    <button type="button" class="btn btn-primary get-history" data-toggle="modal"
                                    data-target="#historyUpdate">History
                                    Update
                                    <input hidden type="text" class="Order-ID" value="{{ $val->id }}">
                                    <input hidden type="text" class="Company-Name" value="{{ $val->name }}">
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <span>Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ count($data) }} entries from
                total {{ $data->total() }}</span>
            {{ $data->links() }}
        </div>
        <?php
            }
        ?>

    </div>
</div>

<script>
    function msg(num) {
        var num1 = atob(num);
        window.location.href = 'rcmobile://sms/?number=' + num1;
        console.log(num1);
    }
</script>

{{--    <script>--}}
{{--        $(document).ready(function() {--}}
{{--            $.ajaxSetup({--}}
{{--                headers: {--}}
{{--                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
{{--                }--}}
{{--            });--}}

{{--            function Search() {--}}
{{--                var value  = $("#value").val();--}}
{{--                var entity = $(this).val();--}}
{{--                var sort = $("#sort").children('option:selected').val();--}}
{{--                var search_by = $("#search_by").children('option:selected').val();--}}
{{--                var from = $("#from").val();--}}
{{--                var too = $("#too").val();--}}
{{--                fetch_data3(entity,value,sort,search_by,1,from,too);--}}
{{--            }--}}

{{--            function fetch_data3(entity, value, sort, search_by, page) {--}}

{{--                $('#customerData').html('');--}}
{{--                $('#customerData').append(--}}
{{--                    `<div class="lds-hourglass" style="position:absolute;top:20%;left:50%;transform:translate(-50%,-50%);" id='ldss'></div>`--}}
{{--                    );--}}

{{--                $.ajax({--}}
{{--                    url: "customer_data?page=" + page,--}}
{{--                    type: "POST",--}}
{{--                    data: {--}}
{{--                        value: value,--}}
{{--                        entity: entity,--}}
{{--                        sort: sort,--}}
{{--                        search_by: search_by--}}
{{--                    },--}}
{{--                    success: function(data) {--}}
{{--                        $('#customerData').html('');--}}
{{--                        $('#customerData').html(data);--}}

{{--                    },--}}
{{--                    // complete: function (data) {--}}
{{--                    //     $('#ldss').hide();--}}
{{--                    //     regain();--}}
{{--                    // }--}}

{{--                })--}}

{{--            }--}}

{{--            $("#value").keyup(function(e) {--}}
{{--                var entity = $("#entity").children('option:selected').val();--}}
{{--                var sort = $("#sort").children('option:selected').val();--}}
{{--                var search_by = $("#search_by").children('option:selected').val();--}}
{{--                var value = $(this).val();--}}
{{--                if (e.which == 13) {--}}
{{--                    $(".atoz").css('background', 'transparent');--}}
{{--                    $(".atoz").each(function() {--}}
{{--                        if (value == $(this).text() || value == $(this).text().toUpperCase()) {--}}
{{--                            $(this).css('background', '#d9d8d8');--}}
{{--                        }--}}
{{--                    })--}}
{{--                    var from = $("#from").val();--}}
{{--                    var too = $("#too").val();--}}
{{--                    fetch_data3(entity,value,sort,search_by,1,from,too);--}}
{{--                }--}}
{{--            });--}}

{{--            $("#entity").on('change', function() {--}}
{{--                var value = $("#value").val();--}}
{{--                var entity = $(this).val();--}}
{{--                var sort = $("#sort").children('option:selected').val();--}}
{{--                var search_by = $("#search_by").children('option:selected').val();--}}
{{--                var from = $("#from").val();--}}
{{--                var too = $("#too").val();--}}
{{--                fetch_data3(entity,value,sort,search_by,1,from,too);--}}
{{--            })--}}

{{--            $("#sort").on('change', function() {--}}
{{--                var value = $("#value").val();--}}
{{--                var sort = $(this).val();--}}
{{--                var entity = $("#entity").children('option:selected').val();--}}
{{--                var search_by = $("#search_by").children('option:selected').val();--}}
{{--                fetch_data3(entity, value, sort, search_by, 1);--}}
{{--            })--}}

{{--            $(document).on('click', '.atoz', function() {--}}
{{--                $("#value").val($(this).text());--}}
{{--                var value = $(this).text();--}}
{{--                var sort = $("#sort").children('option:selected').val();--}}
{{--                var entity = $("#entity").children('option:selected').val();--}}
{{--                var search_by = $("#search_by").children('option:selected').val();--}}
{{--                fetch_data3(entity, value, sort, search_by, 1);--}}
{{--                $(".atoz").css('background', 'transparent');--}}
{{--                $(this).css('background', '#d9d8d8');--}}
{{--            })--}}

{{--            $(document).on('click', '.pagination a', function(event) {--}}

{{--                event.preventDefault();--}}
{{--                var value = $("#value").val();--}}
{{--                var entity = $("#entity").children('option:selected').val();--}}
{{--                var sort = $("#sort").children('option:selected').val();--}}
{{--                var search_by = $("#search_by").children('option:selected').val();--}}
{{--                var page = $(this).attr('href').split('page=')[1];--}}
{{--                fetch_data3(entity, value, sort, search_by, page);--}}

{{--            });--}}

{{--            function historyUpdateKaro(id) {--}}
{{--                $("#oId").val(id);--}}
{{--                console.log($("#oId").val(id));--}}
{{--                $('.media-body').html('');--}}
{{--                $.ajax({--}}
{{--                    url: "{{ url('/show-customer-order-history') }}",--}}
{{--                    type: "GET",--}}
{{--                    data: {--}}
{{--                        id: id--}}
{{--                    },--}}
{{--                    success: function(res) {--}}
{{--                        $('.media-body').html('');--}}
{{--                        $('.media-body').html(res);--}}
{{--                    }--}}
{{--                })--}}
{{--            }--}}

{{--            function modalClick() {--}}
{{--                $("#history").val('');--}}
{{--            }--}}

{{--            $("#udpateHistoryOrderCustomer").click(function() {--}}
{{--                var id = $("#oId").val();--}}
{{--                var history = $("#history");--}}
{{--                history.parents('.form-group').children('.alert').remove();--}}
{{--                $.ajax({--}}
{{--                    url: "{{ url('/customer-order-history-update') }}",--}}
{{--                    type: "POST",--}}
{{--                    dataType: "json",--}}
{{--                    data: {--}}
{{--                        id: id,--}}
{{--                        history: history.val()--}}
{{--                    },--}}
{{--                    success: function(res) {--}}
{{--                        if (res.status_code === 200) {--}}
{{--                            location.reload();--}}
{{--                        }--}}
{{--                        if (res.error.history) {--}}
{{--                            history.parents('.form-group').append(`--}}
{{--                                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">--}}
{{--                                    ${res.error.history[0]}--}}
{{--                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">--}}
{{--                                        <span aria-hidden="true">&times;</span>--}}
{{--                                    </button>--}}
{{--                                </div>`);--}}
{{--                        }--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}

{{--        });--}}

{{--        function call(num) {--}}
{{--            var num1 = atob(num);--}}
{{--            var newNum = num1.replace(/[- )(]/g, '');--}}
{{--            window.location.href = 'rcmobile://call?number=' + newNum;--}}
{{--        }--}}

{{--        function msg(num) {--}}
{{--            var num1 = atob(num);--}}
{{--            window.location.href = 'rcmobile://sms/?number=' + num1;--}}
{{--        }--}}


{{--    </script>--}}

{{--    <script>--}}
{{--        $(".get-history").click(function(e) {--}}
{{--            e.preventDefault(); // Prevent the default form submission--}}

{{--            // var company_id = $(this).find('.Company-ID').val();--}}
{{--            // var CompanyName = $(this).find('.Company-Name').val();--}}
{{--            var id = $(this).find('.Order-ID').val();--}}

{{--            // console.log('id', id);--}}
{{--            $("#oId").val(id);--}}
{{--            // Perform AJAX submission--}}
{{--            // $.ajax({--}}
{{--            //     url: '{{ route('get.call.history') }}',--}}
{{--            //     type: 'GET',--}}
{{--            //     data: {--}}
{{--            //         'company_id': company_id,--}}
{{--            //         'user_id': user_id,--}}
{{--            //     },--}}
{{--            //     success: function(data) {--}}
{{--            //         // Handle the success response--}}
{{--            //         console.log('data', data);--}}
{{--            //         //showing history--}}
{{--            //         $(".history-content").html('');--}}
{{--            //         html = "";--}}
{{--            //         $.each(data, function(index, val) {--}}
{{--            //             // Assuming val['created_at'] is a string representation of the date--}}
{{--            //             var createdAt = new Date(val['created_at']);--}}

{{--            //             // Format the date--}}
{{--            //             var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",--}}
{{--            //                 "Aug", "Sep", "Oct", "Nov", "Dec"--}}
{{--            //             ];--}}
{{--            //             var formattedDate = monthNames[createdAt.getMonth()] + "," +--}}
{{--            //                 ("0" + createdAt.getDate()).slice(-2) + " " +--}}
{{--            //                 createdAt.getFullYear() + " " +--}}
{{--            //                 ("0" + createdAt.getHours()).slice(-2) + ":" +--}}
{{--            //                 ("0" + createdAt.getMinutes()).slice(-2) +--}}
{{--            //                 (createdAt.getHours() >= 12 ? " PM" : " AM");--}}

{{--            //             // Append formatted date to HTML--}}
{{--            //             html += "<h6>" + val['user']['name'] + "</h6>";--}}
{{--            //             html += "<h6>" + val['connectStatus'] + "</h6>";--}}
{{--            //             html += "<h6>" + val['comment'] + ".</h6>";--}}
{{--            //             html += "<strong class='mf-date'><i class='fa fa-clock-o'></i>" +--}}
{{--            //                 formattedDate + "</strong> <hr>";--}}
{{--            //         });--}}
{{--            //         $(".history-content").html(html);--}}
{{--            //     },--}}
{{--            //     error: function(error) {--}}
{{--            //         // Handle the error response--}}
{{--            //         console.error('Error submitting the form:', error);--}}
{{--            //         // Optionally, you can display an error message or take other actions--}}
{{--            //     }--}}
{{--            // });--}}
{{--            $.ajax({--}}
{{--                    url: "{{ url('/show-customer-order-history') }}",--}}
{{--                    type: "GET",--}}
{{--                    data: {--}}
{{--                        id: id--}}
{{--                    },--}}
{{--                    success: function(res) {--}}
{{--                        $('.media-body').html('');--}}
{{--                        $('.media-body').html(res);--}}
{{--                    }--}}
{{--                })--}}
{{--        });--}}
{{--    </script>--}}


    <!--Scrolling Modal-->

