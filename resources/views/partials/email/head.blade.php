<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="telephone=no" name="format-detection">
<meta content="width=mobile-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;" name="viewport">
<meta content="IE=9; IE=8; IE=7; IE=EDGE;" http-equiv="X-UA-Compatible">
<title>@if (trim($__env->yieldContent('template_title')))@yield('template_title')
    | @endif {{ config('app.name', Lang::get('titles.app')) }}</title>
<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">

<style type="text/css">

    /**This is to overwrite Outlook.com’s Embedded CSS************/
    table {
        border-collapse: separate;
    }

    a, a:link, a:visited {
        text-decoration: none;
        color: #00788a
    }

    h2, h2 a, h2 a:visited, h3, h3 a, h3 a:visited, h4, h5, h6, .t_cht {
        color: #000 !important
    }

    p {
        margin-bottom: 0
    }

    .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td {
        line-height: 100%
    }

    /**This is to center your email in Outlook.com************/
    .ExternalClass {
        width: 100%;
    }

    /* General Resets */
    #outlook a {
        padding: 0;
    }

    body, #body-table {
        height: 100% !important;
        width: 100% !important;
        margin: 0 auto;
        padding: 0;
        line-height: 100%;
    !important
    }

    img, a img {
        border: 0;
        outline: none;
        text-decoration: none;
    }

    .image-fix {
        display: block;
    }

    table, td {
        border-collapse: collapse;
    }

    /* Client Specific Resets */
    .ReadMsgBody {
        width: 100%;
    }

    .ExternalClass {
        width: 100%;
    }

    .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
        line-height: 100% !important;
    }

    .ExternalClass * {
        line-height: 100% !important;
    }

    table, td {
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
    }

    img {
        outline: none;
        border: none;
        text-decoration: none;
        -ms-interpolation-mode: bicubic;
    }

    body, table, td, p, a, li, blockquote {
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
    }

    body.outlook img {
        width: auto !important;
        max-width: none !important;
    }

    /* Start Template Styles */
    /* Main */
    body {
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
        margin: 0;
        padding: 0;
    }

    body, #body-table {
        margin: 0 auto !important;;
        margin: 0 auto !important;
        text-align: center !important;
    }

    p {
        padding: 0;
        margin: 0;
        line-height: 24px;
        font-family: Open Sans, sans-serif;
    }

    a, a:link {
        color: #1c344d;
        text-decoration: none !important;
    }

    .footer-link a, .nav-link a {
        color: #fff6e5;
    }

    /* Yahoo Mail */
    .thread-item.expanded .thread-body {
        background-color: #000000 !important;
    }

    .thread-item.expanded .thread-body .body, .msg-body {
        display: block !important;
    }

    #body-table .undoreset table {
        display: table !important;
        table-layout: fixed !important;
    }

    /* Start Media Queries */
    @media only screen and (max-width: 640px) {
        a[href^="tel"], a[href^="sms"] {
            text-decoration: none;
            pointer-events: none;
            cursor: default;
        }

        .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
            text-decoration: default;
            pointer-events: auto;
            cursor: default;
        }

        *[class].full-width {
            width: 100% !important;
        }

        *[class].mobile-width {
            width: 440px !important;
            padding: 0 4px;
        }

        *[class].content-width {
            width: 360px !important;
        }

        *[class].content-width-menu {
            width: 360px !important;
        }

        *[class].center {
            text-align: center !important;
            height: auto !important;
        }

        *[class].center-stack {
            padding-bottom: 30px !important;
            text-align: center !important;
            height: auto !important;
        }

        *[class].stack {
            padding-bottom: 30px !important;
            height: auto !important;
        }

        *[class].gallery {
            padding-bottom: 20px !important;
        }

        *[class].fluid-img {
            height: auto !important;
            max-width: 600px !important;
            width: 100% !important;
        }

        *[class].block {
            display: block !important;
        }

        *[class].midaling {
            width: 100% !important;
            border: none !important;
        }
    }

    @media only screen and (max-width: 480px) {
        *[class].full-width {
            width: 100% !important;
        }

        *[class].mobile-width {
            width: 320px !important;
            padding: 0 4px;
        }

        *[class].content-width {
            width: 240px !important;
        }

        *[class].content-width-menu {
            width: 320px !important;
        }

        *[class].navlink {
            font-size: 13px !important;
        }

        *[class].center {
            text-align: center !important;
            height: auto !important;
        }

        *[class].center-stack {
            padding-bottom: 30px !important;
            text-align: center !important;
            height: auto !important;
        }

        *[class].stack {
            padding-bottom: 30px !important;
            height: auto !important;
        }

        *[class].gallery {
            padding-bottom: 20px !important;
        }

        *[class].fluid-img {
            height: auto !important;
            max-width: 600px !important;
            width: 100% !important;
            min-width: 320px !important;
        }

        *[class].midaling {
            width: 100% !important;
            border: none !important;
        }

        *[class].navlink {
            width: 600px !important;
            border: none !important;
        }
    }

    @media only screen and (max-width: 320px) {
        *[class].full-width {
            width: 100% !important;
        }

        *[class].mobile-width {
            width: 100% !important;
            padding: 0 4px;
        }

        *[class].content-width {
            width: 240px !important;
        }

        *[class].center {
            text-align: center !important;
            height: auto !important;
        }

        *[class].center-stack {
            padding-bottom: 30px !important;
            text-align: center !important;
            height: auto !important;
        }

        *[class].stack {
            padding-bottom: 30px !important;
            height: auto !important;
        }

        *[class].gallery {
            padding-bottom: 20px !important;
        }

        *[class].fluid-img {
            height: auto !important;
            max-width: 600px !important;
            width: 100% !important;
            min-width: 320px !important;
        }

        *[class].midaling {
            width: 100% !important;
            border: none !important;
        }
    }

    .table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    .table td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    .table td:first-child {
        width: 25%;
    }

    .table td {
        color: #000;
    }

    .table tr:nth-child(even) {
        background-color: #dddddd;
    }
</style>
