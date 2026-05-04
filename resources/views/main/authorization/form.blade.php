<!--<!DOCTYPE html>-->
<!--<html lang="en">-->

<!--<head>-->
<!--  <meta charset="UTF-8">-->
<!--  <meta name="viewport" content="width=device-width, initial-scale=1.0">-->
<!--  <title>Form Validation</title>-->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>

<style>
    /* span#card_numberIcon {
      position: absolute;
      top: 40px;
      right: 28px;
    } */

    .container-style {
        margin-top: 3rem !important;
        background: whitesmoke;
        padding: 1rem 2rem;
        border: 1px solid #b5b1b18c;
        box-shadow: 0px 0px 4px gray;
    }

    .underline {
        text-decoration: underline;
    }

    #signatureCanvas {
        width: 100%;
        height: 150px;
    }

    /* Adjust the height as needed */



    /* span#card_numberIcon {
      position: absolute;
      top: 39px;
    } */
    /* right: 135px; */
    /* Start position before animation */
    /*  animation: slideInLeft 3s ease-in-out forwards ; */

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
    .btn-sing {
        background: #715fc8;
        color: white;
        padding: 4px 3px;
        border: none;
        cursor: pointer;
        margin-top: 10px;
        border-radius: 4px;
    }
    .btn-primary {
    color: #fff;
    background-color: #715fc8;
    border-color: #715fc8;
}
form {
    font-family: 'Poppins', sans-serif;
}
</style>

</style>
    <!--</head>-->

    <!--<body>-->

    {{-- dd($cID, $cname, $cphone, $invoiceNo, $invoiceAmount) --}}
    <div class="credit-card-authorization container mt-5 container-style">
        <!--       <div class="container">-->
        <!--    <div class="row justify-content-between flex-wrap">-->
        <!--        <h3 style="color: #ff0048; height: 36px;" class="d-flex align-items-center">-->
        <!--            <span class="my-auto mx-3"></span>-->
        <!--            <button type="button" class="btn" id="btnConvert">-->
        <!--                <i class="fa fa-download" aria-hidden="true"></i> Download Form-->
        <!--            </button>-->
        <!--        </h3>-->
        <!--    </div>-->
        <!--</div>-->

        <form id="myForm" action="{{ route('authorization.form.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="email" value="{{ $cname ?? '' }}">
            <div class="container mt-5">
                <div class="text-center">
                    <h1 class="underline"> SHIP A1 </h1>
                    <h2>CREDIT CARD AUTHORIZATION FORM</h2>
                </div>
            </div>

            <div class="">
                <div>
                    <label for="date">Date:</label>
                    <input type="date" class="form-control" id="date" name="date" required autocomplete="off">
                    <span id="dateError" class="text-danger"></span>
                    <span id="dateIcon" class="ml-2"></span>
                </div>
                <div>
                    <p>This Signed Form Authorized <span>SHIP A1</span> to Charge Your Credit Card
                        for
                        The Amount Shown.</p>
                    <p>This Form is for the transportation of the Vehicle<span> {{ $vehicle }}</span>, Pickup From
                        <span>{{ $origin }}</span>, Deliver to
                        {{ $destination }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="company_name">Company Name:</label>
                <input type="company_name" class="form-control" id="company_name" name="company_name"
                    placeholder="Enter yourCompany Name" required minlength="5" maxlength="50" autocomplete="off"
                    value="">
                <span id="company_nameError" class="text-danger"></span>
                <span id="company_nameIcon" class="ml-2"></span>
            </div>
            
            
            
               <div class="">
            <div class="row">
                <div class="form-group col-lg-2">
                    <label for="master_card">Master Card:</label>
                    <input type="radio" id="card" name="card" value="master_card">
                    <span id="master_cardError" class="text-danger"></span>
                    <span id="master_cardIcon" class="ml-2"></span>
                </div>
                <div class="form-group col-lg-2">
                    <label for="visa">Visa:</label>
                    <input type="radio" id="card" name="card" value="visa">
                    <span id="visaError" class="text-danger"></span>
                    <span id="visaIcon" class="ml-2"></span>
                </div>
                <div class="form-group col-lg-3">
                    <label for="american-express">American Express:</label>
                    <input type="radio" id="card" name="card" value="american-express">
                    <span id="american-expressError" class="text-danger"></span>
                    <span id="american-expressIcon" class="ml-2"></span>
                </div>
            </div>
           </div>
            

            <div class="form-group">
                <label for='card_holders'>Card Holder's Name (As State On the Card):</label>
                <input type="text" class="form-control" id="card_holders" name="card_holders"
                    placeholder="Enter your Card Holder's Name" required autocomplete="off" minLength="5" maxlength="50">
                <span id="card_holdersError" class="text-danger"></span>
                <span id="card_holdersIcon" class="ml-2"></span>
            </div>
            
            
            
                    <div class="">
                <div class="row  flex-wrap">
                    <!--<div class="form-group col">-->
                    <!--    <label for="card_number">Card Number:</label>-->
                    <!--    <input type="number" class="form-control" id="card_number" placeholder="Enter your Card Number"-->
                    <!--        name="card_number" required autocomplete="off">-->
                    <!--    <span id="card_numberError" class="text-danger"></span>-->
                    <!--    <span id="card_numberIcon" class="ml-2"></span>-->
                    <!--</div>-->
                    <div class="form-group col">
                    <label for="card_number">Card Number:</label>
                    <input type="text" class="form-control" id="card_number" placeholder="Enter your Card Number"
                        name="card_number" required autocomplete="off">
                    <span id="card_numberError" class="text-danger"></span>
                    <span id="card_numberIcon" class="ml-2"></span>
                </div>

                    <div class="form-group col">
                        <label for="expdate">Expiration Date:</label>
                        <input type="month" class="form-control" id="expdate" name="expdate" required
                            autocomplete="off">
                        <span id="expdateError" class="text-danger"></span>
                        <span id="expdateIcon" class="ml-2"></span>
                    </div>

                    <div class="form-group col">
                        <label for="Code">Security Code:</label>
                        <input type="Code" class="form-control" id="Code" placeholder="Enter your Security Code"
                            name="Code" required autocomplete="off">
                        <span id="CodeError" class="text-danger"></span>
                        <span id="CodeIcon" class="ml-2"></span>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label for="issuing_bank">issuing Bank:</label>
                        <input type="text" class="form-control" id="issuing_bank"
                            placeholder="Enter your issuing Bank" name="issuing_bank" required autocomplete="off">
                        <span id="issuing_bankError" class="text-danger"></span>
                        <span id="issuing_bankIcon" class="ml-2"></span>
                    </div>
                    <div class="form-group col-sm-8">
                        <label for="bank_approval">Bank Approval number:</label>
                        <input type="number" class="form-control" id="bank_approval"
                            placeholder="Enter your Bank Approval number" name="bank_approval" required
                            autocomplete="off">
                        <span id="bank_approvalError" class="text-danger"></span>
                        <span id="bank_approvalIcon" class="ml-2"></span>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="form-group ">
                    <label for="card_issuer">Card Issuer's Phone Number (Located on Back of credit card):</label>
                    <input type="number" class="form-control" id="card_issuer"
                        placeholder="Enter your Card Issuer's Phone Number" name="card_issuer" required
                        autocomplete="off">
                    <span id="card_issuerError" class="text-danger"></span>
                    <span id="card_issuerIcon" class="ml-2"></span>
                </div>
            </div>
            
            
            
            
            <div class="form-group">
                <label for="billing_address">Billing Address****Complete Address*****:</label>
                <input type="billing_address" class="form-control" id="billing_address"
                    placeholder="Enter your Billing Address" name="billing_address" required autocomplete="off"
                    minlength="5" maxlength="100">
                <span id="billing_addressError" class="text-danger"></span>
                <span id="billing_addressIcon" class="ml-2"></span>
            </div>
            <!-- <div class="container"> -->
            <div class="row  flex-wrap">
                <div class="form-group col">
                    <label for="city">City:</label>
                    <input type="city" class="form-control" id="city" name="city" placeholder="Enter your City"
                        required autocomplete="off" minlength="4" maxlength="50">
                    <span id="cityError" class="text-danger"></span>
                    <span id="cityIcon" class="ml-2"></span>
                </div>
                <div class="form-group col">
                    <label for="state">State:</label>
                    <input type="state" class="form-control" id="state" name="state" placeholder="Enter your State"
                        required autocomplete="off" minlength="5" maxlength="50">
                    <span id="stateError" class="text-danger"></span>
                    <span id="stateIcon" class="ml-2"></span>
                </div>
                <div class="form-group col">
                    <label for="zip_code">Zip Code:</label>
                    <input type="zip_code" class="form-control" id="zip_code" placeholder="Enter your Zip Code"
                        name="zip_code" required autocomplete="off" minLength="5" maxlength="5">
                    <span id="zip_codeError" class="text-danger"></span>
                    <span id="zip_codeIcon" class="ml-2"></span>
                </div>
            </div>
            <!-- </div> -->
            <!-- <div class="container"> -->
            <div class="row">
                <div class="form-group col">
                    <label for="country">Country:</label>
                    <input type="country" class="form-control" id="country" name="country"
                        placeholder="Enter your Country" required autocomplete="off">
                    <span id="countryError" class="text-danger"></span>
                    <span id="countryIcon" class="ml-2"></span>
                </div>
                <div class="form-group col">
                    <label for="phone">Phone Number:</label>
                    <input type="text" class="form-control" id="phone" name="phone" value=""
                        placeholder="Enter your Phone Number" required autocomplete="off">
                    <span id="phoneError" class="text-danger"></span>
                    <span id="phoneIcon" class="ml-2"></span>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="form-group col-lg-2">
                        <label for="invoice">Invoice Number:</label>
                        <input type="text" class="form-control" id="invoice" value="{{ $invoiceNo ?? '' }}"
                            readonly placeholder="Enter your Invoice Number" name="invoice" required autocomplete="off">
                        <span id="invoiceError" class="text-danger"></span>
                        <span id="invoiceIcon" class="ml-2"></span>
                    </div>
                    <div class="form-group">
                        <label for="invoice_amount">Invoice Amount:</label>
                        <input type="number" class="form-control" id="invoice_amount"
                            value="{{ $invoiceAmount ?? '' }}" placeholder="Enter your Invoice Amount"
                            name="invoice_amount" required autocomplete="off">
                        <span id="invoice_amountError" class="text-danger"></span>
                        <span id="invoice_amountIcon" class="ml-2"></span>
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-8">
                <p>Upload Clear Images (Front And Back) of the Credit Card And Driving licence card:</p>

                <label class="custom-file-upload">
                    <input type="file" multiple class="form-control" id="multiImages" name="multiImage[]"
                        accept="image/*" required>
                    Upload Front and Back Images
                </label>

            </div>

            <!--<div class="form-group col-lg-3">-->
            <!--    <label for="signature">Card Holder's Signature:</label>-->
            <!--    <canvas id="signatureCanvas" class="form-control" style="border: 1px solid #ccc;"></canvas>-->
            <!--    <button type="button" onclick="clearSignature()">Clear Signature</button>-->
            <!--    <span id="signatureError" class="text-danger"></span>-->
            <!--</div>-->
            <!-- Your HTML elements -->
            <div class="form-group col-lg-3">
                <!-- ... Other elements ... -->
                <canvas id="signatureCanvas" class="form-control" style="border: 1px solid #ccc;"></canvas>
                <button type="button" class="btn-sing" onclick="clearSignature()">Clear Signature</button>
                <button type="button" class="btn-sing" onclick="saveSignature()">Save Signature</button>
                <img id="signatureImage" style="display: none;">
                <input type="hidden" id="signatureData" name="signatureData">
                <span id="signatureError" class="text-danger"></span>
            </div>

            <div class="container">
                <div class="row  flex-wrap">
                    <button type="button" class="btn btn-primary" onclick="validateForm()">Submit</button>
                </div>
            </div>
            <!-- <span id="successIcon" class="text-success"></span>
            <span id="errorIcon" class="text-danger"></span> -->
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.js"></script>
<!-- Include html2canvas from CDN -->
<!--<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>-->
<script>
    // $(document).ready(function() {
    //     $("#btnConvert").on('click', function() {
    //         html2canvas(document.body).then(canvas => {
    //             // Convert canvas to data URL
    //             const dataURL = canvas.toDataURL('image/jpeg');

    //             // Create a link element and trigger a download
    //             const a = document.createElement('a');
    //             a.href = dataURL;
    //             a.download = 'screenshot.jpg';
    //             document.body.appendChild(a);
    //             a.click();
    //             document.body.removeChild(a);
    //         });
    //     });
    // });
</script>
    <script>
        $('#phone').inputmask('(999) 999-99999');
        // $('#card_number').inputmask('(999) 999-99999');
  $(document).ready(function() {
        $('#card_number').inputmask('9999999999999999').trim();
    });
        const canvas = document.getElementById('signatureCanvas');
        const signaturePad = new SignaturePad(canvas);

        window.clearSignature = function() {
            signaturePad.clear();
            const signatureError = document.getElementById('signatureError');
            signatureError.innerHTML = ''; // Clear the error message
        };
        // =======================================onsubbmitt===========================
let formSubmitted = false;
let formData = {}; // Declare formData in the global scope

// Event listener for submit button
const submitButton = document.querySelector('.btn-primary');
submitButton.addEventListener('click', function(event) {
    // Set the formSubmitted flag to true on submit
    formSubmitted = true;

    // Trigger form validation on submit
    const isFormValid = validateForm();

    if (isFormValid) {
        // Log the form data
        console.log(formData);

        // Submit the form
        $("#myForm").submit();
    } else {
        // Prevent form submission if validation fails
        event.preventDefault();

        // Scroll to the first error element if there are errors
        const firstErrorElement = document.querySelector('.text-danger:not(:empty)');
        if (firstErrorElement) {
            firstErrorElement.scrollIntoView({
                behavior: 'smooth'
            });
        }
    }
});
        // Event listener for input fields
        const inputFields = document.querySelectorAll('input, select, textarea');
        inputFields.forEach(field => {
            field.addEventListener('input', () => {
                // Trigger the validation function on input change

            });
        });
        0
        // =======================================onsubbmitt===========================
        // =======================================onchange===========================
        // const inputFields = document.querySelectorAll('input, select, textarea');
        // inputFields.forEach(field => {
        //   field.addEventListener('input', () => {
        //     // Trigger the validation function on input change
        //     validateForm();
        //     // Alternatively, you can call a specific validation function for the changed field
        //     // validateField(field.id);
        //     triggerAnimation();
        //   });
        // });
        // =======================================onchange===========================

        function triggerAnimation() {
            // Trigger your animation logic here
            const cardNumberIcon = document.getElementById('card_numberIcon');
            cardNumberIcon.style.animation = 'slideInLeft 3s ease-in-out forwards';
        }






        // document.getElementById('imageUploadForm').addEventListener('submit', function (event) {
        //   event.preventDefault();
        //   // Handle form submission, e.g., send images to server
        //   alert('Form submitted. You can handle the image upload logic here.');
        // });


        function validateForm(fieldId) {
            const fields = [{
                    id: 'company_name',
                    type: 'company_name',
                    label: 'Company Name'
                },
                {
                    id: 'country',
                    type: 'country',
                    label: 'Country'
                },
                {
                    id: 'card_holders',
                    type: 'card-holder',
                    label: 'Card Holder\'s Name'
                },
                {
                    id: 'billing_address',
                    type: 'billing_address',
                    label: 'Billing Address'
                },
                {
                    id: 'city',
                    type: 'city',
                    label: 'City'
                },
                {
                    id: 'state',
                    type: 'state',
                    label: 'State'
                },
                {
                    id: 'zip_code',
                    type: 'zip_code',
                    label: 'Zip Code'
                },
                {
                    id: 'phone',
                    type: 'phone',
                    label: 'Phone Number'
                },
                {
                    id: 'card_number',
                    type: 'number',
                    label: 'Card Number'
                },
                {
                    id: 'expdate',
                    type: 'month',
                    label: 'Expiration Date'
                },
                {
                    id: 'Code',
                    type: 'text',
                    label: 'Security Code'
                },
                {
                    id: 'date',
                    type: 'date',
                    label: 'Date'
                },
                {
                    id: 'issuing_bank',
                    type: 'text',
                    label: 'Issuing Bank'
                },
                {
                    id: 'bank_approval',
                    type: 'number',
                    label: 'Bank Approval Number'
                },
                {
                    id: 'card_issuer',
                    type: 'number',
                    label: 'Card Issuer\'s Phone Number'
                },
                {
                    id: 'invoice',
                    type: 'text',
                    label: 'Invoice Number'
                },
                {
                    id: 'invoice_amount',
                    type: 'number',
                    label: 'Invoice Amount'
                },
                {
                    id: 'signatureCanvas',
                    type: 'signatureCanvas',
                    label: 'Card older Signature'
                },

            ];

            // const checkboxFields = ['master_card', 'visa', 'american-express'];
            const checkboxFields = ['card'];
            const formData = {};

            fields.forEach(field => {
                const element = document.getElementById(field.id);
                let value = '';

                if (element && element.value !== undefined) {
                    value = element.value.trim();

                    const errorElement = document.getElementById(`${field.id}Error`);
                    const iconElement = document.getElementById(`${field.id}Icon`);

                    errorElement.innerHTML = '';
                    iconElement.innerHTML = '';

                    const signatureError = document.getElementById('signatureError');
                    if (field.id === 'signatureCanvas') {
                        if (signaturePad.isEmpty()) {
                            signatureError.innerHTML = 'Signature is required';
                        } else {
                            signatureError.innerHTML = '';
                            const signatureData = signaturePad.toDataURL();
                            formData.signatureData = signatureData;
                        }
                        return;
                    }
                    if (value === '') {
                        errorElement.innerHTML = `${field.label} is required`;
                        iconElement.innerHTML = `<i class="fas fa-exclamation-circle text-danger"></i>`;
                    } else if (field.type === 'phone' && !isValidPhone(value)) {
                        errorElement.innerHTML = 'Invalid phone number';
                        iconElement.innerHTML = `<i class="fas fa-exclamation-circle text-danger"></i>`;
                    } else if (field.type === 'company_name' && (value.length < element.minLength || value.length >
                            element.maxLength)) {
                        errorElement.innerHTML =
                            `${field.label} must be between ${element.minLength} and ${element.maxLength} characters`;
                        iconElement.innerHTML = `<i class="fas fa-exclamation-circle text-danger"></i>`;
                    } else if (field.type === 'date' && !isValidDate(value)) {
                        errorElement.innerHTML = 'Invalid date';
                        iconElement.innerHTML = `<i class="fas fa-exclamation-circle text-danger"></i>`;
                    } else if (field.type === 'number' && !isValidNumber(value)) {
                        errorElement.innerHTML = 'Invalid number';
                        iconElement.innerHTML = `<i class="fas fa-exclamation-circle text-danger"></i>`;
                    } else if (field.id === 'zip_code' && !isValidZipCode(value)) {
                        errorElement.innerHTML = 'Invalid zip code';
                        iconElement.innerHTML = `<i class="fas fa-exclamation-circle text-danger"></i>`;
                    } else {
                        iconElement.innerHTML = `<i class="fas fa-check-circle text-success"></i>`;
                    }
                } else {

                }
                formData[field.id] = value;
            });
            checkboxFields.forEach(field => {
                const checkbox = document.getElementById(field);
                formData[field] = checkbox ? checkbox.checked : false;
            });


            const signatureError = document.getElementById('signatureError');
            if (signaturePad.isEmpty() && !formData['signatureCanvas']) {
                signatureError.innerHTML = 'Signature is required';
            } else {
                signatureError.innerHTML = '';
                if (!formData['signatureCanvas']) {
                    const signatureData = signaturePad.toDataURL();
                    formData.signatureData = signatureData;
                }
            }

            // const emptyFields = fields.some(field => document.getElementById(field.id).value.trim() === '');
            const emptyFields = fields.some(field => {
                const element = document.getElementById(field.id);
                return !element || element.value === '';
            });
            // const checkboxChecked = checkboxFields.some(field => document.getElementById(field) && document.getElementById(
            //     field).checked);
        // Check if there are no errors and all fields are filled
        const isFormValid = !emptyFields && document.querySelectorAll('.text-danger:not(:empty)').length === 0;

    return isFormValid;
            const errorIcon = document.getElementById('errorIcon');
            const successIcon = document.getElementById('successIcon');

            // if (errorIcon && successIcon) {
            //     if (emptyFields || !checkboxChecked) {
            //         errorIcon.innerHTML = '<i class="fas fa-exclamation-circle"></i>';
            //         successIcon.innerHTML = '';
            //     } else {
            //         successIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
            //         errorIcon.innerHTML = '';
            //     }
            // }
            // console.log(formData); // all form data
            // $("#myForm").submit();
        }

        function isValidPhone(phone) {
            // Implement phone number validation logic
            const phoneRegex = /^\(\d{3}\) \d{3}-\d{5}$/;
            return phoneRegex.test(phone);
        }

        function isValidDate(date) {
            // Implement date validation logic
            const dateFormatRegex = /^\d{4}-\d{2}-\d{2}$/;
            return dateFormatRegex.test(date);
            return !isNaN(Date.parse(date));
        }

        function isValidNumber(number) {
            // Implement number validation logic
            return !isNaN(number);
        }

        function isValidZipCode(zipCode) {
            // Implement zip code validation logic
            const zipCodeRegex = /^\d{5}$/;
            return zipCodeRegex.test(zipCode);
        }
    </script>
    <script>
        var signatureCanvas = document.getElementById('signatureCanvas');
        var ctx = signatureCanvas.getContext('2d');
        var signatureImage = document.getElementById('signatureImage');
        var signatureDataInput = document.getElementById('signatureData');
    
        // Draw a point to initialize the canvas
        ctx.fillRect(0, 0, 1, 1);
    
        function clearSignature() {
            ctx.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
            signatureImage.style.display = 'none';
            signatureDataInput.value = ''; // Clear the hidden input value
        }
    
        function saveSignature() {
            var dataURL = signatureCanvas.toDataURL(); // Convert canvas content to data URL (default is PNG format)
    
            // Display the captured signature as an image
            signatureImage.src = dataURL;
            signatureImage.style.display = 'block';
    
            // Set the hidden input value with the image data
            signatureDataInput.value = dataURL;
        }
    </script>