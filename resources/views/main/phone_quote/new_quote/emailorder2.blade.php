@extends('layouts.print_layout')

@section('template_title')
    Payment
@endsection
@include('partials.mainsite_pages.return_function2')
@section('content')


<style>
    input, select, textarea {
        border: 1px solid #b0a6e0 !important;
    }
    body {
        /*background-image: linear-gradient(to right, rgb(109, 213, 250), rgb(255, 255, 255), rgb(41, 128, 185)) !important;*/
        box-shadow: 2px 2px #9E9E9E !important;
        background-color: white;
    }
    .card-header{
        color: white !important;
        justify-content: center !important;
        font-weight: 800 !important;
        font-size: 25px !important;
        border: 1px solid #d0d0d9 !important;
        background-color: #8fc445 !important;

    }
    .card-body{
        border:1px solid #d0d0d9 !important;
        padding: 4px 16px !important;
    }
    .icon-container {
        font-size: 24px;
        text-align: center;
        margin-top: -30px;
        margin-bottom: 3px;
    }
    .heading{
        float: left;
    }
    .subhead{
        float: right;
    }
    .app-content .side-app {
        padding: 0px !important;
    }
    .error{
        border:1px solid red !important;
    }
</style>

    <div class="container " style=" margin-top: 0px; ">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="    border-bottom: 1px solid;">
                        <div class=" mb-0 w-100"><strong class="heading">Order Online # {{ $data->id  }} </strong>

                            <p class="subhead">Your IP address - {{ $ip }}</p>

                        </div>
                    </div>
                    <div class="card-body">

                        <form id="paymentForm"
                              action="{{ url('/order_payment_card') }}"
                              method="post"
                              autocomplete="off"
                              class="needs-validation"
                              novalidate>
                            @csrf
                            <input type="hidden" name="id" value="{{ $data->id  }}">
                            <input type="hidden" name="userid" value="{{ $userid  }}">
                            <input type="hidden" name="ip" value="">
                            <input type="hidden" name="ipcity" value="">
                            <input type="hidden" name="ipregion" value="">
                            <input type="hidden" name="ip_details" value="">
                            <input type="hidden" name="ip_details" value="">
                            <input type="hidden" name="ipcountry" value="">
                            <input type="hidden" name="iploc" value="">
                            <input type="hidden" name="ippostal" value="">
                            <input type="hidden" name="browser" value=" ">
                            <input type="hidden" name="platform" value="">

                            <div class="text-muted text-right">
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mainTitle">
                                        <div class="stepContainer">
                                            <span></span>
                                        </div>
                                        <div class="stepTitle">
                                            <h5>Billing Information
                                                <a href="javascript:void(0);" data-toggle="tooltip"
                                                   data-placement="right" title="" data-original-title="
                                                Review pricing and submit your payment information to book your order.
                                                "> <i class="fa fa-question-circle" aria-hidden="true"></i>
                                                </a>
                                            </h5>
                                        </div>
                                        <div class="alert alert-danger mt-2" id="success-alert">
                                            <button type="button" class="close" data-dismiss="alert">x</button>
                                            <strong class="error_text"></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="card-header bg-secondary text-white font-weight-bold">
                                        Price Information
                                    </div>
                                    <div class="card-body border">

                                        <div class="form-group">
                                            <label for="name"><strong>Booking Price</strong><span
                                                        class="text-danger"></span></label>
                                            <input autocomplete="nope" type="text" class="form-control" id="price"
                                                   readonly name="price" value="{{ $data->payment  }}">

                                            <div class="invalid-feedback">

                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="deposit"><strong>Deposit Amount</strong></label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="deposit"
                                                   readonly
                                                   name="deposit"
                                                   value="{{ number_format((float)($data->deposit_amount ?? 0), 2, '.', '') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="balance"><strong>Balance Amount</strong></label>
                                            <input autocomplete="nope" type="text" class="form-control" id="balance"
                                                   readonly name="balance" placeholder="" value="{{ $data->balance  }}">

                                            <div class="invalid-feedback">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <div class="card-header bg-secondary text-white font-weight-bold">
                                        Credit Card Information
                                    </div>
                                    <div class="card-body border">
                                        <label><strong>Accepted Cards</strong></label>

                                        <div class="icon-container d-flex align-items-center justify-content-center" style="gap:12px;">
                                            <img id="card-brand-logo"
                                                 src=""
                                                 alt=""
                                                 style="height:40px; width:auto; display:none; object-fit:contain;">

                                            <span id="card-brand-text" style="font-weight:700; color:#444; font-size:16px;">
        Select or enter card
    </span>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="firstname"><strong>Card First Name</strong></label>
                                                <input type="text"
                                                       class="form-control"
                                                       maxlength="100"
                                                       id="firstname"
                                                       name="firstname"
                                                       placeholder="Enter First Card Name"
                                                       value="{{ old('firstname') }}"
                                                       required>
                                                <div class="invalid-feedback">This field is required.</div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="lastname"><strong>Card Last Name</strong></label>
                                                <input type="text"
                                                       class="form-control"
                                                       maxlength="100"
                                                       id="lastname"
                                                       name="lastname"
                                                       placeholder="Enter Card Last Name"
                                                       value="{{ old('lastname') }}"
                                                       required>
                                                <div class="invalid-feedback">This field is required.</div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="billing_address"><strong>Billing Address</strong></label>
                                            <input type="text"
                                                   name="billing_address"
                                                   id="billing_address"
                                                   class="form-control"
                                                   value="{{ old('billing_address') }}"
                                                   placeholder="Enter Billing Address"
                                                   required>
                                            <div class="invalid-feedback">Billing address is required.</div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label"><strong>Zip Code*</strong></label>
                                            <input type="text"
                                                   id="o_zip1"
                                                   class="form-control"
                                                   maxlength="50"
                                                   name="o_zip1"
                                                   value="{{ old('o_zip1', $data->originzsc) }}"
                                                   placeholder="ZIP CODE"
                                                   required>
                                            <div class="invalid-feedback">ZIP code is required.</div>
                                        </div>

                                        <div class="form-group">
                                            <label for="card_type"><strong>Card Type</strong></label>
                                            <select name="card_type" id="card_type" class="form-control" required>
                                                <option value="">Select Card Type</option>
                                                <option value="visa">Visa</option>
                                                <option value="mastercard">Mastercard</option>
                                                <option value="amex">American Express</option>
                                                <option value="discover">Discover</option>
                                            </select>
                                            <div class="invalid-feedback">Please select card type.</div>
                                        </div>

                                        <div class="form-group">
                                            <label for="card_number"><strong>Credit Card Number</strong></label>
                                            <input type="text"
                                                   name="card_number"
                                                   id="card_number"
                                                   class="form-control"
                                                   placeholder="Enter card number"
                                                   inputmode="numeric"
                                                   autocomplete="off"
                                                   required>
                                            <small class="text-muted d-block mt-1" id="card-help-text">
                                                Card number format will be detected automatically.
                                            </small>
                                            <div class="invalid-feedback">Please enter a valid card number.</div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="cardexpirydate"><strong>Card Expiry Date</strong></label>
                                                <input type="text"
                                                       class="form-control"
                                                       id="cardexpirydate"
                                                       name="cardexpirydate"
                                                       placeholder="MM / YYYY"
                                                       autocomplete="off"
                                                       required>
                                                <div class="invalid-feedback">Enter a valid expiry date.</div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="csvno"><strong>Card Security (CVC)</strong></label>
                                                <input type="text"
                                                       class="form-control"
                                                       id="csvno"
                                                       name="csvno"
                                                       placeholder="CVC"
                                                       autocomplete="off"
                                                       inputmode="numeric"
                                                       maxlength="4"
                                                       required>
                                                <div class="invalid-feedback">Enter a valid CVC.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-12 text-center">
                                    <button type="button"
                                            id="btn-submit-payment"
                                            class="btn btn-primary w-35"
                                            style="font-size:22px; border-radius:10px;"
                                            data-action="save_with_pay">
                                        Submit
                                    </button>

                                    <button type="button"
                                            id="btn-cancel-payment"
                                            class="btn btn-danger w-35"
                                            style="font-size:22px; border-radius:10px;"
                                            data-action="save_without_pay">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="save_but" id="save_but" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
<script src="https://js.stripe.com/v3/"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $("#success-alert").hide();

        $("#o_zip1").autocomplete({
            source: "/get_zip"
        });

        $(".app-sidebar").hide();
        $(".app-header").hide();
        $(".switcher-wrapper").hide();

        const brandMeta = {
            visa: {
                name: 'Visa',
                logo: "{{ asset('assets/images/cards/visa.png') }}",
                lengths: [16],
                cvc: 3
            },
            mastercard: {
                name: 'Mastercard',
                logo: "{{ asset('assets/images/cards/master.png') }}",
                lengths: [16],
                cvc: 3
            },
            amex: {
                name: 'American Express',
                logo: "{{ asset('assets/images/cards/american.png') }}",
                lengths: [15],
                cvc: 4
            },
            discover: {
                name: 'Discover',
                logo: "{{ asset('assets/images/cards/discover.png') }}",
                lengths: [16],
                cvc: 3
            }
        };

        function onlyDigits(value) {
            return (value || '').replace(/\D/g, '');
        }

        function detectCardBrand(number) {
            number = onlyDigits(number);

            if (/^4/.test(number)) return 'visa';
            if (/^(5[1-5])/.test(number) || /^(222[1-9]|22[3-9]\d|2[3-6]\d{2}|27[01]\d|2720)/.test(number)) return 'mastercard';
            if (/^(34|37)/.test(number)) return 'amex';
            if (/^(6011|65|64[4-9])/.test(number)) return 'discover';

            return '';
        }

        function getMaxLengthByBrand(brand) {
            if (!brand || !brandMeta[brand]) return 16;
            return Math.max(...brandMeta[brand].lengths);
        }

        function formatCardNumber(number, brand) {
            number = onlyDigits(number);

            if (brand === 'amex') {
                number = number.substring(0, 15);
                const p1 = number.substring(0, 4);
                const p2 = number.substring(4, 10);
                const p3 = number.substring(10, 15);
                return [p1, p2, p3].filter(Boolean).join(' ');
            }

            number = number.substring(0, 16);
            return number.match(/.{1,4}/g)?.join(' ') || number;
        }

        function luhnCheck(cardNumber) {
            let sum = 0;
            let shouldDouble = false;

            for (let i = cardNumber.length - 1; i >= 0; i--) {
                let digit = parseInt(cardNumber.charAt(i), 10);

                if (shouldDouble) {
                    digit *= 2;
                    if (digit > 9) digit -= 9;
                }

                sum += digit;
                shouldDouble = !shouldDouble;
            }

            return (sum % 10) === 0;
        }

        function updateCardBrandUI(brand) {
            const $logo = $('#card-brand-logo');
            const $text = $('#card-brand-text');
            const $cvc = $('#csvno');

            if (brand && brandMeta[brand]) {
                $logo
                    .attr('src', brandMeta[brand].logo)
                    .attr('alt', brandMeta[brand].name)
                    .show();

                $text.text(brandMeta[brand].name);
                $('#card_type').val(brand);
                $cvc.attr('maxlength', brandMeta[brand].cvc);
                $cvc.attr('placeholder', brandMeta[brand].cvc === 4 ? '4-digit CID' : '3-digit CVC');

                $('#card-help-text').text(
                    brand === 'amex'
                        ? 'Amex format: 15 digits'
                        : brandMeta[brand].name + ' format: 16 digits'
                );
            } else {
                $logo.hide().attr('src', '').attr('alt', '');
                $text.text('Select or enter card');
                $cvc.attr('maxlength', 4).attr('placeholder', 'CVC');
                $('#card-help-text').text('Card number format will be detected automatically.');
            }
        }

        $('#card-brand-logo').on('error', function () {
            $(this).hide();
            $('#card-brand-text').text('Card logo not available');
        });

        $('#card_type').on('change', function () {
            const selectedBrand = $(this).val();
            updateCardBrandUI(selectedBrand);

            const raw = onlyDigits($('#card_number').val());
            $('#card_number').val(formatCardNumber(raw, selectedBrand));
        });

        $('#card_number').on('input', function () {
            let raw = onlyDigits($(this).val());
            let detected = detectCardBrand(raw);
            let selected = $('#card_type').val();
            let activeBrand = detected || selected;

            if (activeBrand) {
                raw = raw.substring(0, getMaxLengthByBrand(activeBrand));
            }

            updateCardBrandUI(activeBrand);
            $(this).val(formatCardNumber(raw, activeBrand));
        });

        $('#csvno').on('input', function () {
            const cardType = $('#card_type').val() || detectCardBrand($('#card_number').val());
            const max = brandMeta[cardType] ? brandMeta[cardType].cvc : 4;
            $(this).val(onlyDigits($(this).val()).substring(0, max));
        });

        $('#cardexpirydate').on('input', function () {
            let value = onlyDigits($(this).val()).substring(0, 6); // MMYYYY

            if (value.length >= 3) {
                value = value.substring(0, 2) + ' / ' + value.substring(2);
            }

            $(this).val(value);
        });

        function validateExpiry(expiry) {
            const match = expiry.match(/^(\d{2})\s*\/\s*(\d{4})$/);
            if (!match) return false;

            const month = parseInt(match[1], 10);
            const year = parseInt(match[2], 10);

            if (month < 1 || month > 12) return false;

            const now = new Date();
            const currentMonth = now.getMonth() + 1;
            const currentYear = now.getFullYear();

            if (year < currentYear) return false;
            if (year === currentYear && month < currentMonth) return false;

            return true;
        }

        function swalError(message, selector = null) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: message,
                confirmButtonColor: '#8fc445'
            }).then(() => {
                if (selector) {
                    $(selector).focus();
                    $(selector).addClass('error');
                }
            });
        }

        function clearErrors() {
            $('.error').removeClass('error');
        }

        function validateForm() {
            clearErrors();

            const firstname = $('#firstname').val().trim();
            const lastname = $('#lastname').val().trim();
            const billingAddress = $('#billing_address').val().trim();
            const zip = $('#o_zip1').val().trim();
            const cardType = $('#card_type').val();
            const cardNumberRaw = onlyDigits($('#card_number').val());
            const expiry = $('#cardexpirydate').val().trim();
            const cvc = onlyDigits($('#csvno').val());

            if (!firstname) return swalError('Please enter Card First Name!', '#firstname'), false;
            if (!lastname) return swalError('Please enter Card Last Name!', '#lastname'), false;
            if (!billingAddress) return swalError('Please enter Billing Address!', '#billing_address'), false;
            if (!zip) return swalError('Please enter ZIP Code!', '#o_zip1'), false;
            if (!cardType) return swalError('Please select Card Type!', '#card_type'), false;
            if (!cardNumberRaw) return swalError('Please enter Card Number!', '#card_number'), false;

            const detected = detectCardBrand(cardNumberRaw);
            if (!detected) {
                return swalError('Unable to detect card type from the entered card number.', '#card_number'), false;
            }

            if (detected !== cardType) {
                return swalError('Selected card type does not match the entered card number.', '#card_number'), false;
            }

            const allowedLengths = brandMeta[cardType]?.lengths || [16];
            if (!allowedLengths.includes(cardNumberRaw.length)) {
                return swalError('Invalid card number length for ' + brandMeta[cardType].name + '.', '#card_number'), false;
            }

            if (!luhnCheck(cardNumberRaw)) {
                return swalError('Please enter a valid card number.', '#card_number'), false;
            }

            if (!validateExpiry(expiry)) {
                return swalError('Please enter a valid expiry date.', '#cardexpirydate'), false;
            }

            const cvcLength = brandMeta[cardType]?.cvc || 3;
            if (!cvc || cvc.length !== cvcLength) {
                return swalError('Please enter a valid ' + cvcLength + '-digit card security code.', '#csvno'), false;
            }

            return true;
        }

        let selectedAction = '';

        function postPaymentForm(actionValue) {
            $('#save_but').val(actionValue);

            const form = $('#paymentForm');
            const formData = form.serialize();

            Swal.fire({
                title: actionValue === 'save_with_pay' ? 'Processing Payment' : 'Cancelling Payment',
                text: actionValue === 'save_with_pay'
                    ? 'Please wait while we process your payment.'
                    : 'Please wait while we update the order status.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: response.title || 'Success',
                        text: response.message || 'Request completed successfully.',
                        confirmButtonColor: '#8fc445'
                    }).then(() => {
                        if (response.redirect_url) {
                            window.location.href = response.redirect_url;
                        } else {
                            window.location.reload();
                        }
                    });
                },
                error: function (xhr) {
                    let message = 'Something went wrong.';

                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        } else if (xhr.responseJSON.errors) {
                            const errors = Object.values(xhr.responseJSON.errors).flat();
                            if (errors.length) {
                                message = errors[0];
                            }
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Request Failed',
                        text: message,
                        confirmButtonColor: '#d33'
                    });
                }
            });
        }

        $('#btn-submit-payment').on('click', function () {
            selectedAction = 'save_with_pay';

            if (!validateForm()) {
                return false;
            }

            postPaymentForm(selectedAction);
        });

        $('#btn-cancel-payment').on('click', function () {
            selectedAction = 'save_without_pay';

            Swal.fire({
                title: 'Are you sure?',
                text: 'This will mark the order as unpaid / missing payment.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#8fc445',
                confirmButtonText: 'Yes, Cancel Payment'
            }).then((result) => {
                if (result.isConfirmed) {
                    postPaymentForm(selectedAction);
                }
            });
        });
    });
</script>


@endsection
