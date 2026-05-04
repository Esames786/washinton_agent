@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]", '/');
if (isset($_GET['titlee'])) {
    $respn = $_GET['titlee'];
}
?>

<style>
    /*====================modalnew================*/
    .tab-pane.active {
        display: block;
    }

    .tab-pane {
        display: none;
    }

    .btn-success.active {
        display: block;
    }

    .btn-success {
        dispaly: none
    }

    .mf-content.w-100 {
        background: #8080802e;
        padding: 20px;
    }

    div#tab2 {
        overflow-y: scroll;
    }

    .modal-dialog.modal-dialog-centered {
        max-width: 50%;
    }

    .modal-backdrop.fade.show {
        width: 100%;
        height: 100%;
    }

    /*====================modalnew================*/
</style>

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
<div class="table-responsive">
    <select name="status" id="status_check" class="form-select">
        <option value="">Select Status</option>
        <option value="1">Active</option>
        <option value="0">Blocked</option>
    </select>
    <table id="example1" class="table table-striped table-bordered text-wrap">
        <thead>
            <tr>
                <th class="border-bottom-0">ID</th>
                <th class="border-bottom-0">COMAPNY NAME</th>
                <th class="border-bottom-0">LOCATION/MCNO</th>
                @if (in_array('42', $phoneaccess))
                    <th class="border-bottom-0">COMPANY PHONE/DRIVER PHONE</th>
                @endif
                <th class="border-bottom-0">EST.PICKUP/DELEVERY</th>
                <th class="border-bottom-0">ASK PRICE/COMMENTS</th>
                @if (in_array('103', $phoneaccess))
                    <th class="border-bottom-0">Block Carrier</th>
                @endif


            </tr>
        </thead>
        <tbody>
            @foreach ($data as $val)
                <tr>
                    <td>
                        {{ $val->orderId }}
                    </td>
                    <td>
                        {{ $val->companyname }}
                    </td>
                    <td>
                        <span class="badge badge-primary mb-2"> {{ $val->location }} </span><br>
                        <span class="badge badge-primary">{{ $val->mcno }}</span>
                    </td>
                    <td>
                        @if (in_array('60', $phoneaccess))
                            <?php
                            $digits = \App\PhoneDigit::first();
                            if (in_array('61', $phoneaccess)) {
                                $new = $val->companyphoneno;
                                $new2 = $val->driverphoneno;
                            } else {
                                $new = putX($digits->hide_digits, $digits->left_right_status, $val->companyphoneno);
                                $new2 = putX($digits->hide_digits, $digits->left_right_status, $val->driverphoneno);
                            }
                            ?>
                            <span class="badge badge-primary mb-2">{{ $new }}</span><br>
                            <span class="badge badge-primary">{{ $new2 }} </span>
                        @endif
                    </td>
                    <td>
                        <span
                            class="badge badge-primary mb-2">{{ \Carbon\Carbon::parse($val->est_pickupdate)->format('M,d Y h:i A') }}</span><br>
                        <span class="badge badge-primary">
                            {{ \Carbon\Carbon::parse($val->est_deliverydate)->format('M,d Y h:i A') }}</span>
                    </td>
                    <td>
                        <span class="badge badge-primary mb-2">{{ $val->askprice }} </span> <br>
                        {{ $val->comments }}
                    </td>
                    @if (in_array('103', $phoneaccess))
                        <td>
                            <div class="statusBox{{ $val->id }}">
                                @if ($val->status === 1)
                                    <button type="button" class="btn btn-outline-danger confirm-dlts"
                                        data-toggle="modal" data-target="#exampleModal">Block

                                        <input hidden type="text" class="Company-ID" value="{{ $val->id }}">
                                        <input hidden type="text" class="Status" value="0">
                                        <input hidden type="text" class="Company-Name"
                                            value="{{ $val->companyname }}">
                                    </button>
                                @else
                                    <button type="button" class="btn btn-outline-success confirm-dlts"
                                        data-toggle="modal" data-target="#exampleModal">Unblock

                                        <input hidden type="text" class="Company-ID" value="{{ $val->id }}">
                                        <input hidden type="text" class="Status" value="1">
                                        <input hidden type="text" class="Company-Name"
                                            value="{{ $val->companyname }}">
                                    </button>
                                @endif
                            </div>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $data->links() }}

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- <h5 class="modal-title" id="exampleModalLabel7">Employee Access (Assign
                        Data)</h5> --}}

                    <h5 class="modal-title" id="exampleModalLabel">Add Reason for Blocking: <span
                            class="this_user"></span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <form id="addHistoryForm" action="{{ route('block.carrier.status') }}" method="POST"
                                class="needs-validation" novalidate class="tablelist-form" autocomplete="off">
                                @csrf
                                {{-- <div class="modal-body">
                                    <input type="hidden" name="company_id" value="" class="history_val">
                                    <input type="hidden" name="status" value="" class="status_val">
                                    <div class="row g-3">
                                        <div class="tab-pane active" id="tab1">
                                            <div class="col-lg-12">
                                                <div>
                                                    <label for="label-field" class="form-label">Add
                                                        Comments</label>
                                                    <textarea rows="3" name="comment" id="comment" placeholder="Enter Comments" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success"
                                                id="add-btn close">Save</button>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="modal-body">
                                    <input type="hidden" name="company_id" value="" class="history_val">
                                    <input type="hidden" name="status" value="" class="status_val">
                                    <div class="row g-3">
                                        <div class="row">
                                            <!--=============new modal===============-->
                                            <div class=" tab-menu-heading p-0 bg-light">
                                                <div class="tabs-menu1 ">
                                                    <!-- Tabs -->
                                                    <ul class="nav panel-tabs  gap-2">
                                                        <li class=""><a href="#tab1" class=" btn btn-success"
                                                                data-toggle="tab">HISTORY/STATUS</a>
                                                        </li>
                                                        <li><a href="#tab2" data-toggle="tab"
                                                                class="active btn btn-success">VIEW HISTORY</a></li>
                                                        <li></li>
                                                        <li class="position-relative">
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <!--=============new modal===============-->
                                        </div>
                                        <div class="tab-pane " id="tab1">
                                            <div class="row">

                                                <div class="col-lg-12">
                                                    <div>
                                                        <label for="label-field" class="form-label">Add
                                                            Comments</label>
                                                        <textarea rows="3" name="comment" id="comment" placeholder="Enter Comments" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success"
                                                        id="add-btn close">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </form>
                        <div class="active tab-pane" id="tab2">
                            <div class="chat-body-style ChatBody" id="calhistory" style=" height:300px;">

                                <div class="message-feed media">
                                    <div class="media-body">
                                        <div class="mf-content w-100 history-content">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Save</button>
            </div> --}}
</div>
</div>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    // regain_call();
    // regain_status();
    // regain_report_modal();


    // Declare variables outside both functions
    var company_id;
    var CompanyName;
    var status;

    // Use event delegation for click event
    $(document).on("click", ".confirm-dlts", function() {
        // Set values when the button is clicked
        company_id = $(this).find('.Company-ID').val();
        CompanyName = $(this).find('.Company-Name').val();
        status = $(this).find('.Status').val();

        // Your other code...
        $(".this_user").html(CompanyName);
        $(".history_val").val(company_id);
        $(".status_val").val(status);

        $.ajax({
            url: '{{ route('block.carrier.getAll') }}',
            type: 'GET',
            data: {
                'company_id': company_id,
            },
            success: function(data) {
                // Handle the success response
                console.log('datas', data);

                $(".history-content").html('');

                html = "";

                $.each(data, function(index, val) {
                    // Assuming val['created_at'] is a string representation of the date
                    var createdAt = new Date(val['created_at']);

                    // Format the date
                    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",
                        "Aug", "Sep", "Oct", "Nov", "Dec"
                    ];
                    var formattedDate = monthNames[createdAt.getMonth()] + "," +
                        ("0" + createdAt.getDate()).slice(-2) + " " +
                        createdAt.getFullYear() + " " +
                        ("0" + createdAt.getHours()).slice(-2) + ":" +
                        ("0" + createdAt.getMinutes()).slice(-2) +
                        (createdAt.getHours() >= 12 ? " PM" : " AM");

                    // Append formatted date to HTML
                    html += "<h6>" + val['user']['name'] + "</h6>";

                    // Handle status
                    html += "<h6>";

                    if (val['status'] == 1) {
                        html += "Unblock";
                    } else if (val['status'] == 0) {
                        html += "Block";
                    } else {
                        // Handle other cases if needed
                        html += "Unknown Status";
                    }

                    html += "</h6>";

                    html += "<h6>" + val['comment'] + ".</h6>";
                    html += "<strong class='mf-date'><i class='fa fa-clock-o'></i>" +
                        formattedDate + "</strong> <hr>";
                });
                $(".history-content").html(html);
            },
            error: function(error) {
                // Handle the error response
                console.error('Error submitting the form:', error);
                // Optionally, you can display an error message or take other actions
            }
        });
    });

    // Add history with ajax
    $("#addHistoryForm").submit(function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Now you can use company_id, CompanyName, and status here
        console.log(company_id, CompanyName, status);

        // Serialize the form data
        var formData = $(this).serialize();

        // Perform AJAX submission
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(data) {
                // Handle the success response
                console.log('datadata', data);
                console.log('datasssss', $(".statusBox" + company_id));

                $(".statusBox" + company_id).html('');

                var html = "";

                if (status == 1) {
                    // console.log('yess', status);
                    html +=
                        "<button type='button' class='btn btn-outline-danger confirm-dlts'>Block";
                    html +=
                        "<input hidden type='text' class='Company-ID' value='" + company_id +
                        "'>";
                    html += "<input hidden type='text' class='Status' value='1'>";
                    html +=
                        "<input hidden type='text' class='Company-Name' value='" + CompanyName +
                        "'>";
                    html += "</button>";
                } else {
                    // console.log('nooo', status);
                    html +=
                        "<button type='button' class='btn btn-outline-success confirm-dlts'>Unblock";
                    html +=
                        "<input hidden type='text' class='Company-ID' value='" + company_id +
                        "'>";
                    html += "<input hidden type='text' class='Status' value='0'>";
                    html +=
                        "<input hidden type='text' class='Company-Name' value='" + CompanyName +
                        "'>";
                    html += "</button>";
                }

                $(".statusBox" + company_id).html(html);

                $(".history-content").html('');
                html = "";
                $.each(data, function(index, val) {
                    // Assuming val['created_at'] is a string representation of the date
                    var createdAt = new Date(val['created_at']);

                    // Format the date
                    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul",
                        "Aug", "Sep", "Oct", "Nov", "Dec"
                    ];
                    var formattedDate = monthNames[createdAt.getMonth()] + "," +
                        ("0" + createdAt.getDate()).slice(-2) + " " +
                        createdAt.getFullYear() + " " +
                        ("0" + createdAt.getHours()).slice(-2) + ":" +
                        ("0" + createdAt.getMinutes()).slice(-2) +
                        (createdAt.getHours() >= 12 ? " PM" : " AM");

                    // Append formatted date to HTML
                    html += "<h6>" + val['user']['name'] + "</h6>";

                    // Handle status
                    html += "<h6>";

                    if (val['status'] == 1) {
                        html += "Unblock";
                    } else if (val['status'] == 0) {
                        html += "Block";
                    } else {
                        // Handle other cases if needed
                        html += "Unknown Status";
                    }

                    html += "</h6>";

                    html += "<h6>" + val['comment'] + ".</h6>";
                    html += "<strong class='mf-date'><i class='fa fa-clock-o'></i>" +
                        formattedDate + "</strong> <hr>";
                });
                $(".history-content").html(html);

                // Resetting form
                $('#addHistoryForm')[0].reset();
                // $("#exampleModal").modal("hide");
            },
            error: function(error) {
                // Handle the error response
                console.error('Error submitting the form:', error);
                // Optionally, you can display an error message or take other actions
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var tabLinks = document.querySelectorAll('.panel-tabs a');

        // Activate the default tab
        var defaultTab = document.querySelector('.panel-tabs .active');
        document.querySelector(defaultTab.getAttribute('href')).classList.add('active');

        tabLinks.forEach(function(tabLink) {
            tabLink.addEventListener('click', function(event) {
                event.preventDefault();

                // Deactivate all tabs
                tabLinks.forEach(function(link) {
                    link.classList.remove('active');
                });

                var tabs = document.querySelectorAll('.tab-pane');
                tabs.forEach(function(tab) {
                    tab.classList.remove('active');
                });

                // Activate the clicked tab
                this.classList.add('active');
                document.querySelector(this.getAttribute('href')).classList.add('active');
            });
        });
    });

    $(document).ready(function() {
        $("#status_check").on("change", function() {

            // Get the new value of the changed input
            var status = $('#status_check').val();
            var state = $('#state').val();
            console.log(state)
            var url = "{{ url('/carrier_list') }}";

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    'status': status,
                },
                dataType: "json",
                success: function(data) {
                    console.log('data: ' + data);
                    $('#table_data').html('');
                    $('#table_data').html(data.html);
                },
                error: function(error) {
                    // Handle errors here
                    console.log("Error:", error);
                }
            });

        });
    });
</script>
