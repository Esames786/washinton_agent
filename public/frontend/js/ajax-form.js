$(function () {
    var form = $('#contact-form');
    var formMessages = $('.ajax-response');
    $(form).submit(function (e) {
        e.preventDefault();
        var formData = $(form).serialize();
        $.ajax({type: 'POST', url: $(form).attr('action'), data: formData}).done(function (response) {
            $(formMessages).removeClass('error');
            $(formMessages).addClass('success');
            $(formMessages).text(response);
            $('#contact-form input,#contact-form textarea').val('');
        }).fail(function (data) {
            $(formMessages).removeClass('success');
            $(formMessages).addClass('error');
            if (data.responseText !== '') {
                $(formMessages).text(data.responseText);
            } else {
                $(formMessages).text('Oops! An error occurred and your message could not be sent.');
            }
        });
    });
    var OrderIDForm = $('#Order-Track');
    var OrderIDFormMessages = $('.order-id-form-messages');
    $(OrderIDForm).submit(function (e) {
        e.preventDefault();
        var formData = $(OrderIDForm).serialize();
        $.ajax({type: 'POST', url: $(OrderIDForm).attr('action'), data: formData}).done(function (response) {
            $(OrderIDFormMessages).removeClass('error');
            $(OrderIDFormMessages).addClass('success');
            $(OrderIDFormMessages).text(response);
            $('#contact-form input,#contact-form textarea').val('');
        }).fail(function (data) {
            $(OrderIDFormMessages).removeClass('success');
            $(OrderIDFormMessages).addClass('error');
            if (data.responseText !== '') {
                $(OrderIDFormMessages).text(data.responseText);
            } else {
                $(OrderIDFormMessages).text('Oops! An error occurred and your message could not be sent.');
            }
        });
    });
    var IntantQuoteForm = $('#Instant-Quote-Form');
    var IntantQuoteFormMessages = $('.instant-quote-form-messages');
    $(IntantQuoteForm).submit(function (e) {
        e.preventDefault();
        var formData = $(IntantQuoteForm).serialize();
        $.ajax({type: 'POST', url: $(IntantQuoteForm).attr('action'), data: formData}).done(function (response) {
            $(IntantQuoteFormMessages).removeClass('error');
            $(IntantQuoteFormMessages).addClass('success');
            $(IntantQuoteFormMessages).text(response);
            $('.price__cta-content-shadow').append(thankyou);
            $('#contact-form input,#contact-form textarea').val('');
        }).fail(function (data) {
            $(IntantQuoteFormMessages).removeClass('success');
            $(IntantQuoteFormMessages).addClass('error');
            if (data.responseText !== '') {
                // $(IntantQuoteFormMessages).text(data.responseText);
                $(IntantQuoteFormMessages).text('Fill out required Feilds');
            } else {
                $(IntantQuoteFormMessages).text('Oops! An error occurred and your message could not be sent.');
            }
        });
    });
});


    var thankyou = `
        <div class="" style="
                position: absolute;
                width: 100%;
                height: 100%;
                background: white;
                z-index: 10;
                right: 0;
                padding: 80px 82px 80px 80px;
                clip-path: polygon(3% 0, 100% 0, 97% 100%, 0 100%);
                margin-left: 40px;
                margin-bottom: -1px;
                color: white;
                top: 0;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                text-align: center;
                flex-wrap: nowrap;
                align-items: center;
            ">
    <img src="https://tester.daydispatch.com/frontend/img/logo/logo.png" alt="Logo" style="
    width: 100px;
    margin: auto;
    margin-bottom: 17px;
    margin-top: 0;
">
            <h2>
                Thank You 
            </h2>    
                <br>
                <h4 style="
    border-bottom: 1px solid;
">
                    YOUR QUOTE REQUEST HAS BEEN RECEIVED 
                </h4>
                
                <p style="color: black;">
                    Our Affiliated transporter will get in touch with you shortly.
            Please check your email for further information.
                </p>
            
                <div style="margin-top: 40px;float: right;">
                <a href="/Quote-Request" style="
                background: #bc101c;
                padding: 22px 24px;
                clip-path: polygon(11% 0, 100% 0, 89% 100%, 0 100%);
            ">Get New Quote</a>
                <a href="/" style="color: white;background: #012863;padding: 22px 24px;clip-path: polygon(11% 0, 100% 0, 89% 100%, 0 100%);">Back To Home</a>
                </div>
            </div>
        `;