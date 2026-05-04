@extends('layouts.innerpages')

@section('template_title')
    Profile Card Data
@endsection
@include('partials.mainsite_pages.return_function')
@php use Illuminate\Support\Str; @endphp

@section('content')

    <style>
        select.custom-select.custom-select-sm.form-control.form-control-sm {
            height: 29px;
        }
    </style>
    <!--/app header-->                                                <!--Page header-->
    <div class="page-header">
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Profile - Card Data</b></h1>
        </div>

    </div>
    <div class="row">
        <div class="col-12">

            @if(session('flash_message'))
                <div class="alert alert-success">
                    {{session('flash_message')}}
                </div>
            @endif

            <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="row w-100">
                    </div>

                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        @php
                            $emp_panel_access = Auth::user()->emp_panel_access;
                            $emp_panel_access = explode(',', $emp_panel_access);

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
                        @foreach(['first_name', 'last_name', 'oemail', 'ophone', 'ophone2', 'oaddress', 'originzsc', 'payment_method', 'customer_type', 'business_name', 'nature_of_customer','panel_type'] as $col)
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    @if($col == 'payment_method')
                                        <select id="{{ $col }}" name="{{ $col }}" class="form-control filter-input" data-column="{{ $col }}">
                                            <option value="">Payment Method</option>
                                            <option value="Cash/Certified Funds">Cash/Certified Funds</option>
                                            <option value="Check">Check</option>
                                        </select>
                                    @elseif($col == 'panel_type')
                                        <select class="form-control h-70 filter-input" name="{{ $col }}" id="{{ $col }}" data-column="{{ $col }}">

                                            <option selected="selected" value=""><?php echo get_panel_name(); ?></option>
                                            <optgroup label="Select Panel Type">
                                                @if (in_array('1', $emp_panel_access))
                                                    <option value="1">Auction</option>
                                                @endif
                                                @if (in_array('2', $emp_panel_access))
                                                    <option value="2">ProMax</option>
                                                @endif
                                                @if (in_array("110", $phoneaccess) && in_array('3', $emp_panel_access))
                                                    <option value="3">Testing Quote</option>
                                                @endif
                                                @if (in_array('4', $emp_panel_access))
                                                    <option value="4">Shipa1 Website</option>
                                                @endif
                                                @if (in_array('5', $emp_panel_access))
                                                    <option value="5">Panel Type 5 Quote</option>
                                                @endif
                                                @if (in_array('6', $emp_panel_access))
                                                    <option value="6">Panel Type 6 Quote</option>
                                                @endif
                                            </optgroup>
                                        </select>
                                    @else
                                        <input type="text" class="form-control filter-input"
                                               id="{{ $col }}"
                                               placeholder="Search {{ ucwords(str_replace('_', ' ', $col)) }}"
                                               data-column="{{ $col }}">
                                    @endif
                                </div>
                                @if($col == 'originzsc')
                                    <ul class="nav flex-column border scrollul"
                                        style="max-height:200px;overflow:scroll;display:none;">
                                    </ul>
                                @endif
                            </div>

                        @endforeach
                    </div>

                    <div id="table_data">
                        <table id="profile-cards" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone 1</th>
                                <th>Phone 2</th>
                                <th>Address</th>
                                <th>Origin ZSC</th>
                                <th>Payment Method</th>
                                <th>Customer Type</th>
                                <th>Business Name</th>
                                <th>Nature of Customer</th>
                                <th>Panel Type</th>
                                <th>Auctions</th> <!-- This will show a button to open modal -->
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>

        <div class="modal fade" id="auctionModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Auction Details</h5></div>
                    <div class="modal-body" id="auctionModalBody">
                        <!-- Auction details will be loaded here via AJAX -->
                    </div>
                </div>
            </div>
        </div>


    </div>

@endsection

@section('extraScript')

    <script>

        $('#originzsc').keyup(function() {
            var o_zip1 = $(this);
            if (o_zip1.val().length >= 4) {

                var nav = $('.scrollul');
                if (o_zip1.val() == '') {
                    nav.children().remove();
                    nav.hide();
                } else {
                    $.ajax({
                        url: "{{ url('/get_zip') }}",
                        type: "GET",
                        dataType: "json",
                        data: {
                            d_zip1: o_zip1.val()
                        },
                        success: function(res) {
                            nav.show();
                            nav.children().remove();
                            $.each(res, function() {
                                nav.append(`
                                <li class="nav-item selectAdd">
                                    <a class="nav-link" href="javascript:void(0)">${this}</a>
                                </li>
                            `);
                            });
                            $('.selectAdd').click(function() {
                                $(this).parent('.nav').children().remove();
                                $(this).parent('.nav').hide();
                                $('#originzsc').val($(this).children('a').text());

                            })

                        }
                    });
                }
            }
            // console.log(d_zip1);
        })
        function showAuctionModal(id) {
            $.ajax({
                url: '/profile-card/' + id + '/auctions',
                method: 'GET',
                success: function (data) {
                    let html = '';

                    ['auction_1', 'auction_2', 'auction_3'].forEach(prefix => {
                        html += `
                    <h4>${prefix.replace('_', ' ').toUpperCase()}</h4>
                    <ul>
                        <li><strong>Terminal:</strong> ${data[`${prefix}_terminal`] || '-'}</li>
                        <li><strong>Title:</strong> ${data[`${prefix}_title`] || '-'}</li>
                        <li><strong>Name:</strong> ${data[`${prefix}_name`] || '-'}</li>
                        <li><strong>Phone:</strong> ${data[`${prefix}_phone`] || '-'}</li>
                        <li><strong>Account:</strong> ${data[`${prefix}_account`] || '-'}</li>
                        <li><strong>Account Name:</strong> ${data[`${prefix}_account_name`] || '-'}</li>
                        <li><strong>Buyer No:</strong> ${data[`${prefix}_buyer_no`] || '-'}</li>
                        <li><strong>Gate Pass Pin:</strong> ${data[`${prefix}_gate_pass_pin`] || '-'}</li>
                        <li><strong>Lot No:</strong> ${data[`${prefix}_lot_no`] || '-'}</li>
                    </ul>
                    <hr>
                `;
                    });

                    $('#auctionModalBody').html(html);
                    $('#auctionModal').modal('show');
                }
            });
        }


        $(document).ready(function () {
            $('input[data-column="ophone"], input[data-column="ophone2"]').mask("(999) 999-9999");
            const table = $('#profile-cards').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('profile_card_list') }}',
                    method:'post',
                    data: function (d) {
                        $('.filter-input').each(function () {
                            d[$(this).data('column')] = $(this).val();
                        });
                    }
                },
                columns: [
                    {data: 'first_name', name: 'first_name'},
                    {data: 'last_name', name: 'last_name'},
                    {data: 'oemail', name: 'oemail'},
                    {data: 'ophone', name: 'ophone'},
                    {data: 'ophone2', name: 'ophone2'},
                    {data: 'oaddress', name: 'oaddress'},
                    {data: 'originzsc', name: 'originzsc'},
                    {data: 'payment_method', name: 'payment_method'},
                    {data: 'customer_type', name: 'customer_type'},
                    {data: 'business_name', name: 'business_name'},
                    {data: 'nature_of_customer', name: 'nature_of_customer'},
                    {data: 'panel_type', name: 'panel_type'},
                    {
                        data: 'id',
                        render: function (data) {
                            return `<button onclick="showAuctionModal(${data})" class="btn btn-sm btn-primary">View Auctions</button>`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('.filter-input').on('keyup change', function () {
                table.draw();
            });
        });

    </script>
@endsection


