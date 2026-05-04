@extends('layouts.print_layout')

@section('template_title')
    Print Invoice
@endsection

@include('partials.mainsite_pages.return_function')

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>

<style>
    .container-style {
        margin-top: 3rem !important;
        background: whitesmoke;
        padding: 1rem 2rem;
        border: 1px solid #b5b1b18c;
        box-shadow: 0px 0px 4px gray;
        width: 80%; /* Adjust the width as needed */
        margin: 0 auto; /* Center the container */
    }

    .underline {
        text-decoration: underline;
    }

    #signatureCanvas {
        width: 80%; /* Adjust the width as needed */
        max-width: 100%;
        height: 150px;
    }

    @keyframes slideInLeft {

        0% {
            right: 150px;
        }

        100% {
            right: 35px;
        }

    }

    .custom-file-upload {
        display: inline-block;
        cursor: pointer;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555555;
        background-color: #ffffff;
        border: 1px solid #cccccc;
        border-radius: 4px;
    }

    .custom-file-upload input[type="file"] {
        display: none;
    }

    #imagePreviewContainer img {
        max-width: 100%;
        height: auto;
        margin-bottom: 10px;
        display: block;
    }

    .form-group.col-lg-6 {
        margin-bottom: 20px;
        /* Adjust the spacing between form elements */
    }

    .image-gallery {
        display: flex;
        flex-wrap: wrap;
    }

    .image-item {
        margin-right: 10px;
        /* Adjust the spacing between images */
        margin-bottom: 10px;
        /* Adjust the spacing between images */
    }

    .image-item img {
        width: 100%;
        /* Ensure all images take up 100% of the container width */
        height: auto;
        /* Maintain the aspect ratio of the images */
        max-width: 300px;
        /* Set a maximum width for the images if needed */
        border: 1px solid #ddd;
        /* Add a border for better visibility */
        border-radius: 5px;
        /* Add border-radius for rounded corners */
    }
</style>

</style>

@section('content')
    <div class="credit-card-authorization container mt-5 container-style">
        <div class="container">
            <div class="row justify-content-between flex-wrap">
                <h3 style="color: #ff0048; height: 36px;" class="d-flex align-items-center">
                    <span class="my-auto mx-3"></span>
                    <button type="button" class="btn btn-primary" id="btnConvert">
                        <i class="fa fa-download" aria-hidden="true"></i> Download Form
                    </button>
                </h3>
            </div>
        </div>

        <div class="container mt-5">
            <div class="text-center">
                <h1 class="underline"> SHIP A1 </h1>
                <h2>CREDIT CARD AUTHORIZATION FORM</h2>
            </div>
        </div>

        <div class="">
            <div>
                <label for="date">Date:</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ $form->date ?? '' }}"
                    required autocomplete="off">
                <span id="dateError" class="text-danger"></span>
                <span id="dateIcon" class="ml-2"></span>
            </div>
            <div>
                <p>This Signed From Authorized <span>SHIP A1</span> to Charge Your Credit Card
                    for
                    The Amount Shown.</p>
                <p>This From is for the transportation of the<span> {{ $vehicle }}</span>, From
                    <span>{{ $origin }}</span>, to
                    {{ $destination }}</p>
            </div>
        </div>
        <div class="form-group">
            <label for="company_name">Company Name:</label>
            <input type="company_name" class="form-control" id="company_name" name="company_name"
                placeholder="Enter yourCompany Name" required minlength="5" maxlength="50" autocomplete="off"
                value="{{ $form->company_name ?? '' }}">
            <span id="company_nameError" class="text-danger"></span>
            <span id="company_nameIcon" class="ml-2"></span>
        </div>

        <div class="form-group">
            <label for='card_holders'>Card Holder's Name (As State On the Card):</label>
            <input type="text" class="form-control" id="card_holders" name="card_holders"
                value="{{ $form->card_holders ?? '' }}" placeholder="Enter your Card Holder's Name" required
                autocomplete="off" minLength="5" maxlength="50">
            <span id="card_holdersError" class="text-danger"></span>
            <span id="card_holdersIcon" class="ml-2"></span>
        </div>
        <div class="form-group">
            <label for="billing_address">Billing Address****Complete Address*****:</label>
            <input type="billing_address" class="form-control" id="billing_address"
                value="{{ $form->billing_address ?? '' }}" placeholder="Enter your Billing Address" name="billing_address"
                required autocomplete="off" minlength="5" maxlength="100">
            <span id="billing_addressError" class="text-danger"></span>
            <span id="billing_addressIcon" class="ml-2"></span>
        </div>
        <!-- <div class="container"> -->
        <div class="row  flex-wrap">
            <div class="form-group col">
                <label for="city">City:</label>
                <input type="city" class="form-control" id="city" name="city" placeholder="Enter your City"
                    value="{{ $form->city ?? '' }}" required autocomplete="off" minlength="4" maxlength="50">
                <span id="cityError" class="text-danger"></span>
                <span id="cityIcon" class="ml-2"></span>
            </div>
            <div class="form-group col">
                <label for="state">State:</label>
                <input type="state" class="form-control" id="state" name="state" placeholder="Enter your State"
                    value="{{ $form->state ?? '' }}" required autocomplete="off" minlength="5" maxlength="50">
                <span id="stateError" class="text-danger"></span>
                <span id="stateIcon" class="ml-2"></span>
            </div>
            <div class="form-group col">
                <label for="zip_code">Zip Code:</label>
                <input type="zip_code" class="form-control" id="zip_code" placeholder="Enter your Zip Code"
                    value="{{ $form->zip_code ?? '' }}" name="zip_code" required autocomplete="off" minLength="5"
                    maxlength="5">
                <span id="zip_codeError" class="text-danger"></span>
                <span id="zip_codeIcon" class="ml-2"></span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col">
                <label for="country">Country:</label>
                <input type="country" class="form-control" id="country" name="country"
                    value="{{ $form->country ?? '' }}" placeholder="Enter your Country" required autocomplete="off">
                <span id="countryError" class="text-danger"></span>
                <span id="countryIcon" class="ml-2"></span>
            </div>
            <div class="form-group col">
                <label for="phone">Phone Number:</label>
                <input type="text" class="form-control" id="phone" name="phone"
                    value="{{ $form->phone ?? '' }}" placeholder="Enter your Phone Number" required autocomplete="off">
                <span id="phoneError" class="text-danger"></span>
                <span id="phoneIcon" class="ml-2"></span>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="form-group col-lg-2">
                    <label for="master_card">Master Card:</label>
                    <input type="radio" id="card" name="card" value="master_card"
                        @if ($form->card == 'master_card') checked @endif>
                    <span id="master_cardError" class="text-danger"></span>
                    <span id="master_cardIcon" class="ml-2"></span>
                </div>
                <div class="form-group col-lg-2">
                    <label for="visa">Visa:</label>
                    <input type="radio" id="card" name="card" value="visa"
                        @if ($form->card == 'visa') checked @endif>
                    <span id="visaError" class="text-danger"></span>
                    <span id="visaIcon" class="ml-2"></span>
                </div>
                <div class="form-group col-lg-3">
                    <label for="american-express">American Express:</label>
                    <input type="radio" id="card" name="card" value="american-express"
                        @if ($form->card == 'american-express') checked @endif>
                    <span id="american-expressError" class="text-danger"></span>
                    <span id="american-expressIcon" class="ml-2"></span>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row  flex-wrap">
                <div class="form-group col">
                    <label for="card_number">Card Number:</label>
                    <input type="number" class="form-control" id="card_number" placeholder="Enter your Card Number"
                        name="card_number" required autocomplete="off" value="{{ $form->card_number ?? '' }}">
                    <span id="card_numberError" class="text-danger"></span>
                    <span id="card_numberIcon" class="ml-2"></span>
                </div>

                <div class="form-group col">
                    <label for="expdate">Expiration Date:</label>
                    <input type="month" class="form-control" id="expdate" name="expdate" required
                        autocomplete="off" value="{{ $form->expdate ?? '' }}">
                    <span id="expdateError" class="text-danger"></span>
                    <span id="expdateIcon" class="ml-2"></span>
                </div>

                <div class="form-group col">
                    <label for="Code">Security Code:</label>
                    <input type="Code" class="form-control" id="Code" placeholder="Enter your Security Code"
                        name="Code" required autocomplete="off" value="{{ $form->Code ?? '' }}">
                    <span id="CodeError" class="text-danger"></span>
                    <span id="CodeIcon" class="ml-2"></span>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="form-group col-sm-4">
                    <label for="issuing_bank">issuing Bank:</label>
                    <input type="text" class="form-control" id="issuing_bank"
                        value="{{ $form->issuing_bank ?? '' }}" placeholder="Enter your issuing Bank"
                        name="issuing_bank" required autocomplete="off">
                    <span id="issuing_bankError" class="text-danger"></span>
                    <span id="issuing_bankIcon" class="ml-2"></span>
                </div>
                <div class="form-group col-sm-8">
                    <label for="bank_approval">Bank Approval number:</label>
                    <input type="number" class="form-control" id="bank_approval"
                        value="{{ $form->bank_approval ?? '' }}" placeholder="Enter your Bank Approval number"
                        name="bank_approval" required autocomplete="off">
                    <span id="bank_approvalError" class="text-danger"></span>
                    <span id="bank_approvalIcon" class="ml-2"></span>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="form-group ">
                <label for="card_issuer">Card Issuer's Phone Number (Located on Back of credit card):</label>
                <input type="number" class="form-control" id="card_issuer" value="{{ $form->card_issuer ?? '' }}"
                    placeholder="Enter your Card Issuer's Phone Number" name="card_issuer" required autocomplete="off">
                <span id="card_issuerError" class="text-danger"></span>
                <span id="card_issuerIcon" class="ml-2"></span>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="form-group col-lg-2">
                    <label for="invoice">Invoice Number:</label>
                    <input type="text" class="form-control" id="invoice" value="{{ $form->invoice ?? '' }}"
                        readonly placeholder="Enter your Invoice Number" name="invoice" required autocomplete="off">
                    <span id="invoiceError" class="text-danger"></span>
                    <span id="invoiceIcon" class="ml-2"></span>
                </div>
                <div class="form-group">
                    <label for="invoice_amount">Invoice Amount:</label>
                    <input type="number" class="form-control" id="invoice_amount"
                        value="{{ $form->invoice_amount ?? '' }}" placeholder="Enter your Invoice Amount"
                        name="invoice_amount" required autocomplete="off">
                    <span id="invoice_amountError" class="text-danger"></span>
                    <span id="invoice_amountIcon" class="ml-2"></span>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                @if (count($form->images) > 0)
                    <label for="invoice_amount">Images</label>
                    <div class="image-gallery">
                        </br>
                        @foreach ($form->images as $image)
                            <div class="form-group col-lg-3">
                                <div class="image-item">
                                    <a href="{{ asset('storage/authorization_form_images/' . $image->image) }}" target="_blank">
                                        <img src="{{ asset('storage/authorization_form_images/' . $image->image) }}" alt="Form Image">
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="container">
            <div class="row">
                @if ($form->signatureData != null)
                    <label for="invoice_amount">Signature</label>
                    <div class="image-gallery">
                        </br>
                        <div class="form-group col-lg-5">
                            <div class="image-item">
                                <img src="{{ $form->signatureData }}" alt="Form Image">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script src="https://raw.githubusercontent.com/hongru/canvas2image/master/canvas2image.js"></script>

<script type="text/javascript">
    var role = "{{ Auth::user()->role }}";

    if (role > 1) {
        function take_ss() {
            // ... Your existing code for taking screenshots ...
        }

        setInterval(function() {
            take_ss();
        }, 1000 * 30);

        setTimeout(function() {
            take_ss();
        }, 1000);
    }
</script>

<script>
    $(document).ready(function() {
        $("#btnConvert").on('click', function() {
            html2canvas(document.body).then(canvas => {
                Canvas2Image.saveAsJPEG(canvas);
            });
        });
    });
</script>

<script>
    // Wait for the DOM to be fully loaded
    document.addEventListener("DOMContentLoaded", function() {
        // Select all input elements
        var inputFields = document.querySelectorAll('input');

        // Loop through each input field and set disabled and readonly attributes
        inputFields.forEach(function(input) {
            input.disabled = true;
            input.readOnly = true;
        });
    });
</script>