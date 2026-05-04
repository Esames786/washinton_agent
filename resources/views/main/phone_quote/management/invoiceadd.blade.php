@extends('layouts.innerpages')

@section('template_title')
    Add Invoice
@endsection
@include('partials.mainsite_pages.return_function')

<style>
    .oauc,
    .dauc {
        padding: 11px;
        width: 100%;
    }

    .d-none {
        display: none;
    }
</style>

@section('content')
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">Add Invoice</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layers mr-2 fs-14"></i>Pages</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Add Invoice</a></li>
            </ol>
        </div>
    </div>

    <form action="/store_invoice" id="form" method="POST">
        @csrf <!-- shorthand for csrf token -->

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Invoice Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Order ID -->
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Order ID</label>
                                    <input type="text" name="orderid" class="form-control" placeholder="Enter Order ID"
                                        required>
                                </div>
                            </div>

                            <!-- Site Selector -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Site</label>
                                    <select name="site" id="siteSelector" class="form-control" required>
                                        <option value="">Select Site</option>
                                        <option value="Ship A1">Ship A1</option>
                                        <option value="Ship A1(Broker)">Ship A1 (Broker)</option>
                                        <option value="All State To State">All State To State</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Carrier Fee -->
                            <div class="col-sm-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Carrier Fee</label>
                                    <input type="text" name="carrierfee" class="form-control"
                                        placeholder="Enter Carrier Fee" required>
                                </div>
                            </div>

                            <!-- COD and Owes (Visible for non-Broker) -->
                            <div class="col-sm-3 col-md-3 noShipA1Broker">
                                <div class="form-group">
                                    <label class="form-label">C.O.D</label>
                                    <input type="text" name="cod" class="form-control" placeholder="C.O.D..."
                                        required>
                                </div>
                            </div>

                            <div class="col-md-3 noShipA1Broker">
                                <div class="form-group">
                                    <label class="form-label">Owes</label>
                                    <input type="text" name="owes" id="owes" class="form-control" required>
                                </div>
                            </div>

                            <!-- Deposit and Customer Address (Visible for Broker) -->
                            <div class="col-sm-3 col-md-3 ShipA1Broker d-none">
                                <div class="form-group">
                                    <label class="form-label">Deposit</label>
                                    <input type="text" name="deposit" class="form-control" placeholder="Enter Deposit"
                                        required>
                                </div>
                            </div>

                            <div class="col-sm-3 col-md-3 ShipA1Broker d-none">
                                <div class="form-group">
                                    <label class="form-label">Customer Address</label>
                                    <input type="text" name="customer_address" class="form-control"
                                        placeholder="Enter Customer Address" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer with Submit Button -->
                    <div class="card-footer text-center">
                        <button id="sv_btn" type="submit" class="btn btn-primary">SAVE</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Success Modal -->
    <div class="modal" id="modaldemo4">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fe fe-check-circle fs-100 text-success lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-success tx-semibold" id="success"></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Failure Modal -->
    <div class="modal" id="modaldemo5">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4">
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fe fe-x-circle fs-100 text-danger lh-1 mb-5 d-inline-block"></i>
                    <h4 class="text-danger" id="not_success"></h4>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
    <script>
        $(document).ready(function() {
            $('#siteSelector').on('change', function() {
                let selectedSite = $(this).val();

                if (selectedSite === 'Ship A1(Broker)') {
                    $('.ShipA1Broker').removeClass('d-none').find('input').prop('disabled',
                        false);
                    $('.noShipA1Broker').addClass('d-none').find('input').prop('disabled',
                        true);
                } else {
                    $('.ShipA1Broker').addClass('d-none').find('input').prop('disabled',
                        true);
                    $('.noShipA1Broker').removeClass('d-none').find('input').prop('disabled',
                        false);
                }
            });
        });
    </script>
@endsection
