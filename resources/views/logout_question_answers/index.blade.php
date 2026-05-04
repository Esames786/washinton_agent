@extends('layouts.innerpages')
@section('template_title')
Logout Questions
@endsection
@section('content')
@include('partials.mainsite_pages.return_function')
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
<style>
    .col-sm-6 .card {
        transition: all .2s;
    }

    .col-sm-6 .card:hover {
        box-shadow: 0 0px 30px 0 rgb(35 43 54 / 15%);
        scale: 1.02;
    }

    .col-sm-6 .card .card-title {
        font-weight: 400;
    }

    .col-sm-6 .card .card-title span {
        font-size: 12px;
    }

    .col-sm-6 .card .card-header {
        font-weight: 700;
    }

    .col-sm-6 .card .card-header .dropdown {
        position: absolute;
        right: 0;
    }

    .col-sm-6 .card .card-header .dropdown button::after {
        color: #000;
    }

    .col-sm-6 .card .card-header .dropdown button {
        background: transparent;
        outline: none;
        border: none;
    }

    .col-sm-6 .card .card-header .dropdown div a {
        font-size: 12px;
    }

    .col-sm-6 .card .card-header .dropdown div {
        left: unset !important;
        right: 0px;
    }

    .col-sm-6 .card .card-header span {
        font-size: 11px;
        color: #fff;
    }

    .table-responsive {
        overflow: unset !important;
    }

    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
    }

    .tab button:hover {
        background-color: #ddd;
    }

    .tab button.active {
        background-color: #ccc;
    }

    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }

    .tabcontent {
        animation: fadeEffect 1s;
    }

    .dropdown-menu {
        left: -6rem !important;
    }

    .bg-yellow {
        background-color: #c3c300 !important;
    }

    .bg-orange {
        background-color: #F49917 !important;
    }

    .bg-pink {
        background: #E91E63 !important;
    }

    .bg-amber {
        background: #FF6F00 !important;
    }

    .bg-teal {
        background: #004D40 !important;
    }

    /* Go from zero to full opacity */
    @keyframes fadeEffect {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    #popup {
        position: fixed;
        top: 50%;
        color: black;
        left: 50%;
        HEIGHT: auto;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border: 1px solid #1ea7db;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    #popup button {
        float: inline-end;
        border: 1px solid #d1dde1;
        background: #17a2b8;
        color: white;
        padding: 2px 20px;
    }


    .popover__message {
        text-align: center;
    }
    
    
    
    /* Increase the width of the modal */
    #modalCustomerNature .modal-dialog {
        max-width: 90%;
    }

    /* Style the table */
    #modalCustomerNature table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
    }

    #modalCustomerNature th,
    #modalCustomerNature td {
        padding: 12px;
        text-align: center;
    }

    #modalCustomerNature thead th {
        background-color: #3490dc;
        color: white;
    }

    #modalCustomerNature tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    #modalCustomerNature tbody tr:hover {
        background-color: #e9ecef;
    }
</style>
<div class="row">
    <div class="col-12">
        @if(session('flash_message'))
            <div class="alert alert-success">
                {{session('flash_message')}}
            </div>
        @endif
        <div class="page-header">
            <div class="text-secondary text-center text-uppercase w-100">
                <h1 class="my-4"><b>Logout Q/As</b></h1>
            </div>
        </div>
        <div class="card mt-5">
            <div class="card-header d-block">
                <div class="row">
                    <div class="col-sm-3 my-auto">
                        <label class="form-label">Daterange </label>
                        <div class='input-group date' id='datetimepicker1'>
                            <input type='text' name="date_range" id="date_range" class="form-control" />
                            <span class="input-group-addon" style="
                                        border: 1px solid #ddd;
                                        border-left-color: transparent;
                                        border-radius: 0;
                                        position: relative;
                                        left: -1px;
                                        display: flex;
                                        align-items: center;
                                    ">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-3 my-auto">
                        <label class="form-label">Status </label>
                        <select name="verified" id="verified" class="form-control">
                            <option selected value="">Select</option>
                            <option value="1">Positive</option>
                            <option value="0">Negative</option>
                        </select>
                    </div>
                    <div class="col-sm-3 my-auto">
                        <label class="form-label">Approved </label>
                        <select name="approved" id="approved" class="form-control">
                            <option selected value="">Select</option>
                            <option value="1">Approved</option>
                            <option value="0">UnApproved</option>
                        </select>
                    </div>
                    <div class="col-sm-1 mt-auto mb-1">
                        <button class="btn btn-primary" id="submit">Search</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive" id="searchData">
                    @include('logout_question_answers.table')
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modalCustomerNature">
        <div class="modal-dialog modal-dialog-centered text text-center" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center text p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="text-danger" id="not_success"></h4>
                    <div class="row">
                        <div class="customerTable">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Question/Answers:</th>
                                    </tr>
                                </thead>
                                <tbody id="customerTable">
                                    <!-- Your data rows here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(document).ready(function () {
        // Ensure event handler is attached only once
        $("#getAllNull").off('click').on('click', function () {
            console.log('okasd');
            $.ajax({
                url: "{{ route('logoutQComment.filter') }}",
                type: "POST",
                data: { getAllNull: 'getAllNull' },
                success: function (res) {
                    console.log('resres', res);
                    $("#searchData").html(res);
                    $("#pagination-container").html(res.pagination);
                }
            });
        });
    });

</script>

<script>
    $(document).ready(function () {
        let table = $('#myTable').DataTable();
    });

    $("#submit").click(function () {
        var user = $("#user").children('option:selected').val();
        var verified = $("#verified").children('option:selected').val();
        var approved = $("#approved").children('option:selected').val();
        var date_range = $("#date_range").val();
        var page = 1;
        console.log(date_range);
        $.ajax({
            url: "{{ route('logoutQComment.filter') }}",
            type: "POST",
            data: { date_range: date_range, user: user, verified: verified, page: page, approved: approved,},
            success: function (res) {
                console.log('resres', res);
                $("#searchData").html("");
                $("#searchData").html(res);
                $("#pagination-container").html(res.pagination);
            }
        });
    })
    $(function () {
        new Date();
        $('#date_range').daterangepicker({
            "showDropdowns": true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            "alwaysShowCalendars": true,
            "opens": "center",
            "drops": "auto"
        }, function (start, end, label) {
            $('#date_range').val("{{\Carbon\Carbon::now()->startOfMonth()->format('m/d/Y')}} - {{\Carbon\Carbon::now()->format('m/d/Y')}}");
        });
        $('#date_range').val("{{\Carbon\Carbon::now()->startOfMonth()->format('m/d/Y')}} - {{\Carbon\Carbon::now()->format('m/d/Y')}}");
    });

    $("body").delegate(".cancelBtn", "click", function () {
        $('#date_range').val('');
    });
</script>
<script>
    function showPopup(element) {
        var description = element.parentElement.getAttribute('data-description');
        document.getElementById('popup-content').innerHTML = description;
        document.getElementById('popup').style.display = 'block';
    }

    function closePopup() {
        document.getElementById('popup').style.display = 'none';
    }
    
    
    $(document).ready(function () {
    
        $(document).on('click', '.getAllAnswers', function(event) {
        // $(".getAllAnswers").on("click", function() {
            var user_id = $(this).data('user-id');
            var created_at = $(this).data('created_at');
        
            $.ajax({
                url: "{{ route('logout_questions_answers.getUserAns') }}",
                type: "GET",
                data: {
                    user_id: user_id,
                    created_at: created_at,
                },
                success: function(data) {
                    if (data.length == 0) {
                        $("#customerTable").html('No Records Found');
                        $("#modalCustomerNature").modal("show");
                    } else {
                        var html = '';
                        var lastDateTime = null;
                
                        $.each(data, function(index, val) {
                            var currentDateTime = val['created_at'];
                            var formattedDateTime = moment(currentDateTime).format('YYYY-MM-DD HH:mm:ss');
                
                            if (formattedDateTime !== lastDateTime) {
                                if (lastDateTime !== null) {
                                    html += `<hr data-content="AND" class="hr-text">`;
                                }
                                html += `<h4 class="mt-4 mb-3"><span class="badge bg-primary text-light">${formattedDateTime}</span></h4>`;
                                lastDateTime = formattedDateTime;
                            }
                
                            html += `
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <p><strong>Q: ${val['question']['question']}</strong></p>
                                        </div>
                                        <div class="form-group mb-3">
                                            <p><strong>Ans: ${val['answer']}</strong></p>
                                        </div>
                                    </div>
                                    <div class="col-6">`;
                
                            // Check if comments exist for this answer
                            if (val['comments'].length > 0) {
                                html += `<div class="comments-section">`;
                                $.each(val['comments'], function(index, comment) {
                                    console.log('comment', comment);
                                    html += `<p>${comment['comment']}</p>`;
                                    if (comment['verified'] == 1) {
                                        html += `<span class="badge badge-success mt-2 text-light">Positive</span>`;
                                    } else {
                                        html += `<span class="badge badge-danger mt-2 text-light">Negative</span>`;
                                    }
                                    $.ajax({
                                        url: "{{ route('get.user.name') }}",
                                        method: 'GET',
                                        data: {
                                            id: 2,
                                        },
                                        success: function(response) {
                                            var user_name = response.name;
                                            html += `<br>
                                            <h3>Comment By: ${user_name}</h3>`;
                                            console.log('ujujujuj');
                                        },
                                        error: function(xhr) {
                                            console.log(xhr.responseText);
                                            console.log('ujujujujsss');
                                        }
                                    });
                                });
                                html += `</div>`;
                            }

                            else {
                                html += `
                                    <form method="POST" class="commentForm">
                                        @csrf
                                        <input type="hidden" class="qa_id" name="qa_id" value="${val['id']}">
                                        <input type="hidden" class="user_id" name="user_id" value="${val['user_id']}">
                                        <textarea name="qaComment" class="qaComment form-control" id="qaComment${val['id']}" placeholder="Add QA comments here..."></textarea>
                                        <div class="form-check">
                                            <input class="form-check-input qaVerify${val['id']}" type="radio" name="qaVerify${val['id']}" value="1" id="qaVerify1${val['id']}">
                                            <label class="form-check-label" for="qaVerify1${val['id']}">
                                                Positive
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input qaVerify${val['id']}" type="radio" name="qaVerify${val['id']}" value="0" id="qaVerify2${val['id']}" checked>
                                            <label class="form-check-label" for="qaVerify2${val['id']}">
                                                Negative
                                            </label>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm saveComment" data-qa_id="${val['id']}" data-user_id="${val['user_id']}" data-created_at="${val['created_at']}">Submit</button>
                                    </form>`;
                            }
                
                            html += `
                                    </div>
                                </div>
                                <br><br>`;
                        });
                
                        $('#customerTable').html(html);
                
                        $("#modalCustomerNature").modal("show");
                    }
                }


            });
        });
    
        $(document).on('click', '.saveComment', function(event) {
            event.preventDefault();
            
            var qa_id = $(this).data('qa_id');
            var user_id = $(this).data('user_id');
            var created_at = $(this).data('created_at');
            var qaComment = $('#qaComment' + qa_id).val();
            var qaVerify = $('input[name="qaVerify' + qa_id + '"]:checked').val();
            
            console.log('qa_id', qa_id, qaComment, qaVerify);
        
            $.ajax({
                url: "{{ route('logoutQComment.store') }}",
                type: 'POST',
                data: {
                    'user_id' : user_id,
                    'qa_id' : qa_id,
                    'qaComment' : qaComment,
                    'qaVerify' : qaVerify,
                    'created_at' : created_at,
                },
                success: function(data) {
                    if (data.length == 0) {
                        $("#customerTable").html('No Records Found');
                        $("#modalCustomerNature").modal("show");
                    } else {
                        var html = '';
                        var lastDateTime = null;
                
                        $.each(data, function(index, val) {
                            var currentDateTime = val['created_at'];
                            var formattedDateTime = moment(currentDateTime).format('YYYY-MM-DD HH:mm:ss');
                
                            if (formattedDateTime !== lastDateTime) {
                                if (lastDateTime !== null) {
                                    html += `<hr data-content="AND" class="hr-text">`;
                                }
                                html += `<h4 class="mt-4 mb-3"><span class="badge bg-primary text-light">${formattedDateTime}</span></h4>`;
                                lastDateTime = formattedDateTime;
                            }
                
                            html += `
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group mb-3">
                                            <p><strong>Q: ${val['question']['question']}</strong></p>
                                        </div>
                                        <div class="form-group mb-3">
                                            <p><strong>Ans: ${val['answer']}</strong></p>
                                        </div>
                                    </div>
                                    <div class="col-6">`;
                
                            if (val['comments'].length > 0) {
                                html += `<div class="comments-section">`;
                                $.each(val['comments'], function(index, comment) {
                                    console.log(val, comment, 'hurraaa');
                                    html += `<p>${comment['comment']}</p>`;
                                    if (comment['verified'] == 1) {
                                        html += `<span class="badge badge-success mt-2 text-light">Positive</span>`;
                                    } else {
                                        html += `<span class="badge badge-danger mt-2 text-light">Negative</span>`;
                                    }
                                    html += `<br>
                                    <h3>Comment By: ${comment['user_id']}</h3>`;
                                });
                                html += `</div>`;
                            } else {
                                html += `
                                    <form method="POST" class="commentForm">
                                        @csrf
                                        <input type="hidden" class="qa_id" name="qa_id" value="${val['id']}">
                                        <input type="hidden" class="user_id" name="user_id" value="${val['user_id']}">
                                        <textarea name="qaComment" class="qaComment form-control" id="qaComment${val['id']}" placeholder="Add QA comments here..."></textarea>
                                        <div class="form-check">
                                            <input class="form-check-input qaVerify${val['id']}" type="radio" name="qaVerify${val['id']}" value="1" id="qaVerify1${val['id']}">
                                            <label class="form-check-label" for="qaVerify1${val['id']}">
                                                Positive
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input qaVerify${val['id']}" type="radio" name="qaVerify${val['id']}" value="0" id="qaVerify2${val['id']}" checked>
                                            <label class="form-check-label" for="qaVerify2${val['id']}">
                                                Negative
                                            </label>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm saveComment" data-qa_id="${val['id']}" data-user_id="${val['user_id']}" data-created_at="${val['created_at']}">Submit</button>
                                    </form>`;
                            }
                
                            html += `
                                    </div>
                                </div>
                                <br><br>`;
                        });
                
                        $('#customerTable').html(html);
                
                        $("#modalCustomerNature").modal("show");
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while submitting the comment');
                }
            });
        });
    });


</script>
@endsection