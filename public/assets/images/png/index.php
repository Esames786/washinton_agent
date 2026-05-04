<!DOCTYPE html>

<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,IE=8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta ng-if="robotsTagInfo" name="robots" content="{{robotsTagInfo}}">
    <meta http-equiv="Pragma" content="no-cache">
    <meta name="fragment" content="!"/>
    <meta http-equiv="Expires" content="-1">
    <meta name="apple-itunes-app" content="app-id=414275302">
    <base href="https://www.copart.com/"/>
    <title ng-bind-html="title">shariq</title>
    <meta name="description" ng-attr-content="{{description}}"
          content="Leader in live online salvage and insurance auto auctions. Over 100000 vehicles on sale. Salvage, used cars, trucks, construction equipment, fleet and more.">
    <link ng-if="previousPage" rel="prev" href="{{previousPage}}">
    <link ng-if="nextPage" rel="next" href="{{nextPage}}">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Language tags -->
    <link ng-if="showLanguageHref" rel="alternate" href="https://www.copart.com{{languageHrefPath | sanitizeUrl}}"
          hreflang="en-us"/>
    <link ng-if="showLanguageHref" rel="alternate" href="https://www.copart.ca{{languageHrefPath | sanitizeUrl}} "
          hreflang="en-ca"/>
    <link ng-if="showLanguageHref" rel="alternate" href="https://www.copart.co.uk{{languageHrefPath | sanitizeUrl}}"
          hreflang="en-gb"/>
    <link ng-if="showLanguageHref" rel="alternate" href="https://www.copart.ie{{languageHrefPath | sanitizeUrl}}"
          hreflang="en-ie"/>
    <link ng-if="showLanguageHref" rel="alternate" href="https://www.copartmea.com{{languageHrefPath | sanitizeUrl}}"
          hreflang="en-om"/>
    <link ng-if="showLanguageHref" rel="alternate" href="https://www.copartmea.com{{languageHrefPath | sanitizeUrl}}"
          hreflang="en-ae"/>
    <link ng-if="showLanguageHref" rel="alternate" href="https://www.copartmea.com{{languageHrefPath | sanitizeUrl}}"
          hreflang="en-bh"/>
    <link ng-if="showLanguageHref" rel="alternate" href="https://www.copart.com{{languageHrefPath | sanitizeUrl}}"
          hreflang="en"/>
    <meta ng-if="::languageMeta" http-equiv="content-language" content="{{::languageMeta}}">


    <link ng-if="canonicalHref" rel="canonical" href="{{canonicalHref}}">
    <meta name="yandex-verification" content="110e9e21b4756bfd"/>
    <meta name="facebook-domain-verification" content="wy1d6yp8jynig5rnj0z5stfa0o81h0"/>

    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>


    <link href="/wro/font-awesome-0a0a73d4451ac90a57aa086ca70cfb88.css" rel="stylesheet"/>


    <link href="/wro/bootstrap_reduced-3653aa51d645cf8c6c4faf90be6f83e1.css" rel="stylesheet"/>


    <link href="/wro/all_app_styles-d985d8ef169cd646ce48d8b522f99e99.css" rel="stylesheet"/>


    <link href="/wro/en-42a0cd07c02dbdd49027800030fef186.css" rel="stylesheet"/>


    <link href="/wro/CPRTUSCSS-af9ba4339d8e07672c496b7eb669ffc5.css" rel="stylesheet"/>
    <style type="text/css">@media only screen and (max-width: 1079px) {

            .mobile-remove2 {
                display: block;
            }
        }

        @media only screen and (max-width: 676px) {
            .Choosecountry {
                display: block;
                margin: 0 auto;
                width: 187px;
            }
        }
    </style>

    <style name="copartFonts">@font-face {
            font-family: 'Lato 300';
            font-style: normal;
            font-weight: 300;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/lato/v17/S6u9w4BMUTPHh7USSwaPGR_p.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF
        }

        @font-face {
            font-family: 'Lato 300';
            font-style: normal;
            font-weight: 300;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/lato/v17/S6u9w4BMUTPHh7USSwiPGQ.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
        }

        @font-face {
            font-family: Lato;
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/lato/v17/S6uyw4BMUTPHjxAwXjeu.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF
        }

        @font-face {
            font-family: Lato;
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/lato/v17/S6uyw4BMUTPHjx4wXg.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
        }

        @font-face {
            font-family: 'Lato Bold';
            font-style: italic;
            font-weight: 700;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/lato/v17/S6u_w4BMUTPHjxsI5wq_FQft1dw.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF
        }

        @font-face {
            font-family: 'Lato Bold';
            font-style: italic;
            font-weight: 700;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/lato/v17/S6u_w4BMUTPHjxsI5wq_Gwft.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
        }

        @font-face {
            font-family: 'Lato Bold';
            font-style: normal;
            font-weight: 700;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/lato/v17/S6u9w4BMUTPHh6UVSwaPGR_p.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF
        }

        @font-face {
            font-family: 'Lato Bold';
            font-style: normal;
            font-weight: 700;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/lato/v17/S6u9w4BMUTPHh6UVSwiPGQ.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
        }

        @font-face {
            font-family: 'Lato Bolder';
            font-style: normal;
            font-weight: 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/lato/v17/S6u9w4BMUTPHh50XSwaPGR_p.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF
        }

        @font-face {
            font-family: 'Lato Bolder';
            font-style: normal;
            font-weight: 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/lato/v17/S6u9w4BMUTPHh50XSwiPGQ.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
        }

        @font-face {
            font-family: 'Red Hat Display 500';
            font-style: normal;
            font-weight: 500;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/redhatdisplay/v4/8vIV7wUr0m80wwYf0QCXZzYzUoToDh20ZKrAMEc.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF
        }

        @font-face {
            font-family: 'Red Hat Display 500';
            font-style: normal;
            font-weight: 500;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/redhatdisplay/v4/8vIV7wUr0m80wwYf0QCXZzYzUoToDh20aqrA.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
        }

        @font-face {
            font-family: 'Red Hat Display 700';
            font-style: normal;
            font-weight: 700;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/redhatdisplay/v4/8vIV7wUr0m80wwYf0QCXZzYzUoToRhu0ZKrAMEc.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF
        }

        @font-face {
            font-family: 'Red Hat Display 700';
            font-style: normal;
            font-weight: 700;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/redhatdisplay/v4/8vIV7wUr0m80wwYf0QCXZzYzUoToRhu0aqrA.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
        }

        @font-face {
            font-family: 'Red Hat Display';
            font-style: normal;
            font-weight: 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/redhatdisplay/v4/8vIV7wUr0m80wwYf0QCXZzYzUoTofhm0ZKrAMEc.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF
        }

        @font-face {
            font-family: 'Red Hat Display';
            font-style: normal;
            font-weight: 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/redhatdisplay/v4/8vIV7wUr0m80wwYf0QCXZzYzUoTofhm0aqrA.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
        }

        @font-face {
            font-family: 'Red Hat Display Bold';
            font-style: normal;
            font-weight: 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/redhatdisplay/v4/8vIV7wUr0m80wwYf0QCXZzYzUoTofhm0ZKrAMEc.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF
        }

        @font-face {
            font-family: 'Red Hat Display Bold';
            font-style: normal;
            font-weight: 900;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/redhatdisplay/v4/8vIV7wUr0m80wwYf0QCXZzYzUoTofhm0aqrA.woff2) format('woff2');
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD
        }

        @font-face {
            font-family: 'Glyphicons Halflings';
            font-display: swap;
            font-weight: 400;
            font-style: normal;
            src: url(/js/lib/bootstrap-3.3.6/fonts/glyphicons-halflings-regular.woff2) format('woff2')
        }

        @font-face {
            font-family: FontAwesome;
            font-display: swap;
            src: url(/css/fonts/fontawesome-webfont.woff2?v=4.6.3) format('woff2');
            font-weight: 400;
            font-style: normal
        }</style>
    <style name="criticalCSS" id="criticalCSS">html {
            font-family: sans-serif;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%
        }

        body {
            margin: 0;
            font-size: 13px;
            line-height: 20px
        }

        a {
            background-color: transparent
        }

        a:active, a:hover {
            outline: 0
        }

        b {
            font-weight: 700
        }

        img {
            border: 0
        }

        button, input, select {
            margin: 0;
            font: inherit;
            color: inherit
        }

        button {
            overflow: visible
        }

        button, select {
            text-transform: none
        }

        button {
            -webkit-appearance: button;
            cursor: pointer
        }

        html input[disabled] {
            cursor: default
        }

        button::-moz-focus-inner, input::-moz-focus-inner {
            padding: 0;
            border: 0
        }

        input {
            line-height: normal
        }

        @media print {
            *, :after, :before {
                color: #000 !important;
                text-shadow: none !important;
                background: 0 0 !important;
                -webkit-box-shadow: none !important;
                box-shadow: none !important
            }

            a, a:visited {
                text-decoration: underline
            }

            a[href]:after {
                content: "(" attr(href) ")"
            }

            a[href^="javascript:"]:after {
                content: ""
            }

            img {
                page-break-inside: avoid
            }

            img {
                max-width: 100% !important
            }

            p {
                orphans: 3;
                widows: 3
            }

            .navbar {
                display: none
            }
        }

        .glyphicon {
            position: relative;
            top: 1px;
            display: inline-block;
            font-family: 'Glyphicons Halflings';
            font-style: normal;
            font-weight: 400;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale
        }

        .glyphicon-time:before {
            content: "\e023"
        }

        .glyphicon-remove-sign:before {
            content: "\e083"
        }

        .form-control-feedback {
            position: absolute;
            top: 0;
            right: 0;
            z-index: 2;
            display: block;
            width: 34px;
            height: 34px;
            line-height: 34px;
            text-align: center;
            pointer-events: none
        }

        a {
            color: #337ab7;
            text-decoration: none
        }

        a:focus, a:hover {
            color: #23527c;
            text-decoration: underline
        }

        a:focus {
            outline: thin dotted;
            outline: 5px auto -webkit-focus-ring-color;
            outline-offset: -2px
        }

        img {
            vertical-align: middle
        }

        .img-responsive {
            display: block;
            max-width: 100%;
            height: auto
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0
        }

        [role=button] {
            cursor: pointer
        }

        h4 {
            font-family: inherit;
            font-weight: 500;
            line-height: 1.1;
            color: inherit
        }

        h4 {
            margin-top: 10px;
            margin-bottom: 10px
        }

        h4 {
            font-size: 18px
        }

        p {
            margin: 0 0 10px
        }

        .text-left {
            text-align: left
        }

        .text-right {
            text-align: right
        }

        .text-center {
            text-align: center
        }

        ul {
            margin-top: 0;
            margin-bottom: 10px
        }

        ul ul {
            margin-bottom: 0
        }

        .list-inline {
            padding-left: 0;
            margin-left: -5px;
            list-style: none
        }

        .list-inline > li {
            display: inline-block;
            padding-right: 5px;
            padding-left: 5px
        }

        .container {
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto
        }

        @media (min-width: 768px) {
            .container {
                width: 750px
            }
        }

        @media (min-width: 992px) {
            .container {
                width: 970px
            }
        }

        @media (min-width: 1200px) {
            .container {
                width: 1170px
            }
        }

        .container-fluid {
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto
        }

        .row {
            margin-right: -15px;
            margin-left: -15px
        }

        .no-margin {
            margin: 0
        }

        .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px
        }

        .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
            float: left
        }

        .col-xs-12 {
            width: 100%
        }

        .col-xs-11 {
            width: 91.66666667%
        }

        .col-xs-10 {
            width: 83.33333333%
        }

        .col-xs-9 {
            width: 75%
        }

        .col-xs-8 {
            width: 66.66666667%
        }

        .col-xs-7 {
            width: 58.33333333%
        }

        .col-xs-6 {
            width: 50%
        }

        .col-xs-5 {
            width: 41.66666667%
        }

        .col-xs-4 {
            width: 33.33333333%
        }

        .col-xs-3 {
            width: 25%
        }

        .col-xs-2 {
            width: 16.66666667%
        }

        .col-xs-1 {
            width: 8.33333333%
        }

        .col-xs-pull-12 {
            right: 100%
        }

        .col-xs-pull-11 {
            right: 91.66666667%
        }

        .col-xs-pull-10 {
            right: 83.33333333%
        }

        .col-xs-pull-9 {
            right: 75%
        }

        .col-xs-pull-8 {
            right: 66.66666667%
        }

        .col-xs-pull-7 {
            right: 58.33333333%
        }

        .col-xs-pull-6 {
            right: 50%
        }

        .col-xs-pull-5 {
            right: 41.66666667%
        }

        .col-xs-pull-4 {
            right: 33.33333333%
        }

        .col-xs-pull-3 {
            right: 25%
        }

        .col-xs-pull-2 {
            right: 16.66666667%
        }

        .col-xs-pull-1 {
            right: 8.33333333%
        }

        .col-xs-pull-0 {
            right: auto
        }

        .col-xs-push-12 {
            left: 100%
        }

        .col-xs-push-11 {
            left: 91.66666667%
        }

        .col-xs-push-10 {
            left: 83.33333333%
        }

        .col-xs-push-9 {
            left: 75%
        }

        .col-xs-push-8 {
            left: 66.66666667%
        }

        .col-xs-push-7 {
            left: 58.33333333%
        }

        .col-xs-push-6 {
            left: 50%
        }

        .col-xs-push-5 {
            left: 41.66666667%
        }

        .col-xs-push-4 {
            left: 33.33333333%
        }

        .col-xs-push-3 {
            left: 25%
        }

        .col-xs-push-2 {
            left: 16.66666667%
        }

        .col-xs-push-1 {
            left: 8.33333333%
        }

        .col-xs-push-0 {
            left: auto
        }

        .col-xs-offset-12 {
            margin-left: 100%
        }

        .col-xs-offset-11 {
            margin-left: 91.66666667%
        }

        .col-xs-offset-10 {
            margin-left: 83.33333333%
        }

        .col-xs-offset-9 {
            margin-left: 75%
        }

        .col-xs-offset-8 {
            margin-left: 66.66666667%
        }

        .col-xs-offset-7 {
            margin-left: 58.33333333%
        }

        .col-xs-offset-6 {
            margin-left: 50%
        }

        .col-xs-offset-5 {
            margin-left: 41.66666667%
        }

        .col-xs-offset-4 {
            margin-left: 33.33333333%
        }

        .col-xs-offset-3 {
            margin-left: 25%
        }

        .col-xs-offset-2 {
            margin-left: 16.66666667%
        }

        .col-xs-offset-1 {
            margin-left: 8.33333333%
        }

        .col-xs-offset-0 {
            margin-left: 0
        }

        @media (min-width: 768px) {
            .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9 {
                float: left
            }

            .col-sm-12 {
                width: 100%
            }

            .col-sm-11 {
                width: 91.66666667%
            }

            .col-sm-10 {
                width: 83.33333333%
            }

            .col-sm-9 {
                width: 75%
            }

            .col-sm-8 {
                width: 66.66666667%
            }

            .col-sm-7 {
                width: 58.33333333%
            }

            .col-sm-6 {
                width: 50%
            }

            .col-sm-5 {
                width: 41.66666667%
            }

            .col-sm-4 {
                width: 33.33333333%
            }

            .col-sm-3 {
                width: 25%
            }

            .col-sm-2 {
                width: 16.66666667%
            }

            .col-sm-1 {
                width: 8.33333333%
            }

            .col-sm-pull-12 {
                right: 100%
            }

            .col-sm-pull-11 {
                right: 91.66666667%
            }

            .col-sm-pull-10 {
                right: 83.33333333%
            }

            .col-sm-pull-9 {
                right: 75%
            }

            .col-sm-pull-8 {
                right: 66.66666667%
            }

            .col-sm-pull-7 {
                right: 58.33333333%
            }

            .col-sm-pull-6 {
                right: 50%
            }

            .col-sm-pull-5 {
                right: 41.66666667%
            }

            .col-sm-pull-4 {
                right: 33.33333333%
            }

            .col-sm-pull-3 {
                right: 25%
            }

            .col-sm-pull-2 {
                right: 16.66666667%
            }

            .col-sm-pull-1 {
                right: 8.33333333%
            }

            .col-sm-pull-0 {
                right: auto
            }

            .col-sm-push-12 {
                left: 100%
            }

            .col-sm-push-11 {
                left: 91.66666667%
            }

            .col-sm-push-10 {
                left: 83.33333333%
            }

            .col-sm-push-9 {
                left: 75%
            }

            .col-sm-push-8 {
                left: 66.66666667%
            }

            .col-sm-push-7 {
                left: 58.33333333%
            }

            .col-sm-push-6 {
                left: 50%
            }

            .col-sm-push-5 {
                left: 41.66666667%
            }

            .col-sm-push-4 {
                left: 33.33333333%
            }

            .col-sm-push-3 {
                left: 25%
            }

            .col-sm-push-2 {
                left: 16.66666667%
            }

            .col-sm-push-1 {
                left: 8.33333333%
            }

            .col-sm-push-0 {
                left: auto
            }

            .col-sm-offset-12 {
                margin-left: 100%
            }

            .col-sm-offset-11 {
                margin-left: 91.66666667%
            }

            .col-sm-offset-10 {
                margin-left: 83.33333333%
            }

            .col-sm-offset-9 {
                margin-left: 75%
            }

            .col-sm-offset-8 {
                margin-left: 66.66666667%
            }

            .col-sm-offset-7 {
                margin-left: 58.33333333%
            }

            .col-sm-offset-6 {
                margin-left: 50%
            }

            .col-sm-offset-5 {
                margin-left: 41.66666667%
            }

            .col-sm-offset-4 {
                margin-left: 33.33333333%
            }

            .col-sm-offset-3 {
                margin-left: 25%
            }

            .col-sm-offset-2 {
                margin-left: 16.66666667%
            }

            .col-sm-offset-1 {
                margin-left: 8.33333333%
            }

            .col-sm-offset-0 {
                margin-left: 0
            }
        }

        .header .header-mid .btn-lightblue {
            background-color: #ffb838;
            color: #151317;
            font-size: 14px;
            margin-left: 15px;
            height: 38px;
            border: none;
            box-shadow: none;
            margin-bottom: 0;
            padding: 9px 36px;
            font-weight: 600;
        }

        .header .header-mid .btn-lightblue {
            background-color: #ffb838;
            color: #151317;
            font-size: 14px;
            margin-left: 15px;
            height: 38px;
            border: 0;
            box-shadow: none;
            margin-bottom: 0;
            padding: 9px 36px;
        }

        .btn:not(:focus-visible), .dropdown-toggle:not(:focus-visible), a:not(:focus-visible), button:not(:focus-visible) {
            outline: 0 !important;
        }

        .marginleft15 {
            margin-left: 15px;
        }

        .marginleft15 {
            margin-left: 15px;
        }

        .btn {
            -moz-transition: .3s ease all;
            -o-transition: .3s ease all;
            -webkit-transition: .3s ease all;
            transition: .3s ease all;
            border-radius: 4px;
            padding: 4px 16px;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .btn {
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        [role=button] {
            cursor: pointer;
        }

        .marginleft15 {
            margin-left: 15px;
        }

        .marginleft15 {
            margin-left: 15px;
        }

        .btn, .download-sales-data, .refundConfirm li a {
            -moz-transition: .3s ease all;
            -o-transition: .3s ease all;
            -webkit-transition: .3s ease all;
            transition: .3s ease all;
            border-radius: 4px;
            padding: 4px 16px;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .btn {
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        [role=button] {
            cursor: pointer;
        }

        button, html input[type=button], input[type=reset], input[type=submit] {
            -webkit-appearance: button;
            cursor: pointer;
        }

        button, select {
            text-transform: none;
        }

        button {
            overflow: visible;
        }

        button, input, optgroup, select, textarea {
            margin: 0;
            font: inherit;
            color: inherit;
        }

        button {
            -webkit-appearance: button;
            cursor: pointer;
        }

        button, select {
            text-transform: none;
        }

        button {
            overflow: visible;
        }

        button, input, select {
            margin: 0;
            font: inherit;
            color: inherit;
        }

        button, input, select, textarea {
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
        }

        .text-left {
            text-align: left;
        }

        .header .leftheader {
            display: inline-block;
            float: left;
        }

        .header .col-reg {
            display: inline-block;
            float: right;
        }

        .j-c_s-b {
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
        }

        .a-i_c {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }

        .d-f {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }

        .j-c_s-b {
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
        }

        .a-i_c {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }

        .d-f {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }

        .d-f {
            display: flex;
        }

        .j-c_s-b {
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
        }

        .a-i_c {
            -webkit-box-align: center;
            -moz-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
        }

        .d-f {
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
        }

        .j-c_s-b {
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
        }

        .a-i_c {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }

        .d-f {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }

        .d-f {
            display: flex;
        }

        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        user agent stylesheet
        div {
            display: block;
        }

        .header {
            color: #fff;
        }

        .header {
            color: #fff;
        }

        header {
            font-size: 13px;
        }

        .d-f-c {
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .d-f-c, .pricing-calculate .pricing-summary {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            left: 0;
            top: 0;
            font-size: 100%;
            font-family: 'Open Sans', sans-serif;
            color: #1d1d1d;
            line-height: 1.6em;
        }

        body {
            font-family: Lato, sans-serif;
            margin: 0;
        }

        body {
            margin: 0;
            font-size: 13px;
            line-height: 20px;
        }

        body {
            font-family: Lato, sans-serif;
        }

        body {
            font-size: 13px;
            line-height: 20px;
            height: 100%;
            overflow: auto;
            -webkit-overflow-scrolling: touch;
            color: #151317;
            padding: 0 !important;
        }

        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
            line-height: 1.42857143;
            color: #333;
            background-color: #fff;
        }

        html {
            font-family: sans-serif;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        html {
            font-size: 10px;
            -webkit-tap-highlight-color: transparent;
        }

        :after, :before {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        :after, :before {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        @media (min-width: 992px) {
            .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9 {
                float: left
            }

            .col-md-12 {
                width: 100%
            }

            .col-md-11 {
                width: 91.66666667%
            }

            .col-md-10 {
                width: 83.33333333%
            }

            .col-md-9 {
                width: 75%
            }

            .col-md-8 {
                width: 66.66666667%
            }

            .col-md-7 {
                width: 58.33333333%
            }

            .col-md-6 {
                width: 50%
            }

            .col-md-5 {
                width: 41.66666667%
            }

            .col-md-4 {
                width: 33.33333333%
            }

            .col-md-3 {
                width: 25%
            }

            .col-md-2 {
                width: 16.66666667%
            }

            .col-md-1 {
                width: 8.33333333%
            }

            .col-md-pull-12 {
                right: 100%
            }

            .col-md-pull-11 {
                right: 91.66666667%
            }

            .col-md-pull-10 {
                right: 83.33333333%
            }

            .col-md-pull-9 {
                right: 75%
            }

            .col-md-pull-8 {
                right: 66.66666667%
            }

            .col-md-pull-7 {
                right: 58.33333333%
            }

            .col-md-pull-6 {
                right: 50%
            }

            .col-md-pull-5 {
                right: 41.66666667%
            }

            .col-md-pull-4 {
                right: 33.33333333%
            }

            .col-md-pull-3 {
                right: 25%
            }

            .col-md-pull-2 {
                right: 16.66666667%
            }

            .col-md-pull-1 {
                right: 8.33333333%
            }

            .col-md-pull-0 {
                right: auto
            }

            .col-md-push-12 {
                left: 100%
            }

            .col-md-push-11 {
                left: 91.66666667%
            }

            .col-md-push-10 {
                left: 83.33333333%
            }

            .col-md-push-9 {
                left: 75%
            }

            .col-md-push-8 {
                left: 66.66666667%
            }

            .col-md-push-7 {
                left: 58.33333333%
            }

            .col-md-push-6 {
                left: 50%
            }

            .col-md-push-5 {
                left: 41.66666667%
            }

            .col-md-push-4 {
                left: 33.33333333%
            }

            .col-md-push-3 {
                left: 25%
            }

            .col-md-push-2 {
                left: 16.66666667%
            }

            .col-md-push-1 {
                left: 8.33333333%
            }

            .col-md-push-0 {
                left: auto
            }

            .col-md-offset-12 {
                margin-left: 100%
            }

            .col-md-offset-11 {
                margin-left: 91.66666667%
            }

            .col-md-offset-10 {
                margin-left: 83.33333333%
            }

            .col-md-offset-9 {
                margin-left: 75%
            }

            .col-md-offset-8 {
                margin-left: 66.66666667%
            }

            .col-md-offset-7 {
                margin-left: 58.33333333%
            }

            .col-md-offset-6 {
                margin-left: 50%
            }

            .col-md-offset-5 {
                margin-left: 41.66666667%
            }

            .col-md-offset-4 {
                margin-left: 33.33333333%
            }

            .col-md-offset-3 {
                margin-left: 25%
            }

            .col-md-offset-2 {
                margin-left: 16.66666667%
            }

            .col-md-offset-1 {
                margin-left: 8.33333333%
            }

            .col-md-offset-0 {
                margin-left: 0
            }
        }

        @media (min-width: 1200px) {
            .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9 {
                float: left
            }

            .col-lg-12 {
                width: 100%
            }

            .col-lg-11 {
                width: 91.66666667%
            }

            .col-lg-10 {
                width: 83.33333333%
            }

            .col-lg-9 {
                width: 75%
            }

            .col-lg-8 {
                width: 66.66666667%
            }

            .col-lg-7 {
                width: 58.33333333%
            }

            .col-lg-6 {
                width: 50%
            }

            .col-lg-5 {
                width: 41.66666667%
            }

            .col-lg-4 {
                width: 33.33333333%
            }

            .col-lg-3 {
                width: 25%
            }

            .col-lg-2 {
                width: 16.66666667%
            }

            .col-lg-1 {
                width: 8.33333333%
            }

            .col-lg-pull-12 {
                right: 100%
            }

            .col-lg-pull-11 {
                right: 91.66666667%
            }

            .col-lg-pull-10 {
                right: 83.33333333%
            }

            .col-lg-pull-9 {
                right: 75%
            }

            .col-lg-pull-8 {
                right: 66.66666667%
            }

            .col-lg-pull-7 {
                right: 58.33333333%
            }

            .col-lg-pull-6 {
                right: 50%
            }

            .col-lg-pull-5 {
                right: 41.66666667%
            }

            .col-lg-pull-4 {
                right: 33.33333333%
            }

            .col-lg-pull-3 {
                right: 25%
            }

            .col-lg-pull-2 {
                right: 16.66666667%
            }

            .col-lg-pull-1 {
                right: 8.33333333%
            }

            .col-lg-pull-0 {
                right: auto
            }

            .col-lg-push-12 {
                left: 100%
            }

            .col-lg-push-11 {
                left: 91.66666667%
            }

            .col-lg-push-10 {
                left: 83.33333333%
            }

            .col-lg-push-9 {
                left: 75%
            }

            .col-lg-push-8 {
                left: 66.66666667%
            }

            .col-lg-push-7 {
                left: 58.33333333%
            }

            .col-lg-push-6 {
                left: 50%
            }

            .col-lg-push-5 {
                left: 41.66666667%
            }

            .col-lg-push-4 {
                left: 33.33333333%
            }

            .col-lg-push-3 {
                left: 25%
            }

            .col-lg-push-2 {
                left: 16.66666667%
            }

            .col-lg-push-1 {
                left: 8.33333333%
            }

            .col-lg-push-0 {
                left: auto
            }

            .col-lg-offset-12 {
                margin-left: 100%
            }

            .col-lg-offset-11 {
                margin-left: 91.66666667%
            }

            .col-lg-offset-10 {
                margin-left: 83.33333333%
            }

            .col-lg-offset-9 {
                margin-left: 75%
            }

            .col-lg-offset-8 {
                margin-left: 66.66666667%
            }

            .col-lg-offset-7 {
                margin-left: 58.33333333%
            }

            .col-lg-offset-6 {
                margin-left: 50%
            }

            .col-lg-offset-5 {
                margin-left: 41.66666667%
            }

            .col-lg-offset-4 {
                margin-left: 33.33333333%
            }

            .col-lg-offset-3 {
                margin-left: 25%
            }

            .col-lg-offset-2 {
                margin-left: 16.66666667%
            }

            .col-lg-offset-1 {
                margin-left: 8.33333333%
            }

            .col-lg-offset-0 {
                margin-left: 0
            }
        }

        label {
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            font-weight: 700
        }

        .form-control {
            display: block;
            width: 100%;
            height: 34px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s
        }

        .form-control:focus {
            border-color: #66afe9;
            outline: 0;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, .6);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, .6)
        }

        .form-control::-moz-placeholder {
            color: #999;
            opacity: 1
        }

        .form-control:-ms-input-placeholder {
            color: #999
        }

        .form-control::-webkit-input-placeholder {
            color: #999
        }

        .form-control::-ms-expand {
            background-color: transparent;
            border: 0
        }

        .form-control[disabled] {
            background-color: #eee;
            opacity: 1
        }

        .form-control[disabled] {
            cursor: not-allowed
        }

        .form-group {
            margin-bottom: 15px
        }

        .input-sm {
            height: 26px;
            padding: 1px 10px;
            font-size: 12px;
            line-height: 1.5;
            border-radius: 3px
        }

        .btn {
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px
        }

        .btn:active:focus, .btn:focus {
            outline: thin dotted;
            outline: 5px auto -webkit-focus-ring-color;
            outline-offset: -2px
        }

        .btn:active {
            background-image: none;
            outline: 0;
            -webkit-box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
            box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125)
        }

        .btn-default {
            color: #333;
            background-color: #fff;
            border-color: #ccc
        }

        .btn-default:focus {
            color: #333;
            background-color: #e6e6e6;
            border-color: #8c8c8c
        }

        .btn-default:hover {
            color: #333;
            background-color: #e6e6e6;
            border-color: #adadad
        }

        .btn-default:active {
            color: #333;
            background-color: #e6e6e6;
            border-color: #adadad
        }

        .btn-default:active:focus, .btn-default:active:hover {
            color: #333;
            background-color: #d4d4d4;
            border-color: #8c8c8c
        }

        .btn-default:active {
            background-image: none
        }

        .collapse {
            display: none
        }

        .caret {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 2px;
            vertical-align: middle;
            border-top: 4px dashed;
            border-right: 4px solid transparent;
            border-left: 4px solid transparent
        }

        .dropdown {
            position: relative
        }

        .dropdown-toggle:focus {
            outline: 0
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            display: none;
            float: left;
            min-width: 160px;
            padding: 5px 0;
            margin: 2px 0 0;
            font-size: 14px;
            text-align: left;
            list-style: none;
            background-color: #fff;
            -webkit-background-clip: padding-box;
            background-clip: padding-box;
            border: 1px solid #ccc;
            border: 1px solid rgba(0, 0, 0, .15);
            border-radius: 4px;
            -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
            box-shadow: 0 6px 12px rgba(0, 0, 0, .175)
        }

        .dropdown-menu > li > a {
            display: block;
            padding: 3px 20px;
            clear: both;
            font-weight: 400;
            line-height: 1.42857143;
            color: #333;
            white-space: nowrap
        }

        .dropdown-menu > li > a:focus, .dropdown-menu > li > a:hover {
            color: #262626;
            text-decoration: none;
            background-color: #f5f5f5
        }

        .input-group {
            position: relative;
            display: table;
            border-collapse: separate
        }

        .input-group .form-control {
            position: relative;
            z-index: 2;
            float: left;
            width: 100%;
            margin-bottom: 0
        }

        .input-group .form-control:focus {
            z-index: 3
        }

        .input-group .form-control, .input-group-addon, .input-group-btn {
            display: table-cell
        }

        .input-group .form-control:not(:first-child):not(:last-child) {
            border-radius: 0
        }

        .input-group-addon, .input-group-btn {
            width: 1%;
            white-space: nowrap;
            vertical-align: middle
        }

        .input-group-addon {
            padding: 6px 12px;
            font-size: 14px;
            font-weight: 400;
            line-height: 1;
            color: #555;
            text-align: center;
            background-color: #eee;
            border: 1px solid #ccc;
            border-radius: 4px
        }

        .input-group .form-control:first-child, .input-group-btn:first-child > .btn {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0
        }

        .input-group-addon:last-child {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0
        }

        .input-group-addon:last-child {
            border-left: 0
        }

        .input-group-btn {
            position: relative;
            font-size: 0;
            white-space: nowrap
        }

        .input-group-btn > .btn {
            position: relative
        }

        .input-group-btn > .btn:active, .input-group-btn > .btn:focus, .input-group-btn > .btn:hover {
            z-index: 2
        }

        .input-group-btn:first-child > .btn {
            margin-right: -1px
        }

        .nav {
            padding-left: 0;
            margin-bottom: 0;
            list-style: none
        }

        .nav > li {
            position: relative;
            display: block
        }

        .nav > li > a {
            position: relative;
            display: block;
            padding: 10px 15px
        }

        .nav > li > a:focus, .nav > li > a:hover {
            text-decoration: none;
            background-color: #eee
        }

        .navbar {
            position: relative;
            min-height: 50px;
            margin-bottom: 20px;
            border: 1px solid transparent
        }

        @media (min-width: 768px) {
            .navbar {
                border-radius: 4px
            }
        }

        @media (min-width: 768px) {
            .navbar-header {
                float: left
            }
        }

        .navbar-collapse {
            padding-right: 15px;
            padding-left: 15px;
            overflow-x: visible;
            -webkit-overflow-scrolling: touch;
            border-top: 1px solid transparent;
            -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, .1);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .1)
        }

        @media (min-width: 768px) {
            .navbar-collapse {
                width: auto;
                border-top: 0;
                -webkit-box-shadow: none;
                box-shadow: none
            }

            .navbar-collapse.collapse {
                display: block !important;
                height: auto !important;
                padding-bottom: 0;
                overflow: visible !important
            }
        }

        .navbar-toggle {
            position: relative;
            float: right;
            padding: 9px 10px;
            margin-top: 8px;
            margin-right: 15px;
            margin-bottom: 8px;
            background-color: transparent;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px
        }

        .navbar-toggle:focus {
            outline: 0
        }

        .navbar-nav {
            margin: 7.5px -15px
        }

        .navbar-nav > li > a {
            padding-top: 10px;
            padding-bottom: 10px;
            line-height: 20px
        }

        @media (min-width: 768px) {
            .navbar-nav {
                float: left;
                margin: 0
            }

            .navbar-nav > li {
                float: left
            }

            .navbar-nav > li > a {
                padding-top: 15px;
                padding-bottom: 15px
            }
        }

        .navbar-nav > li > .dropdown-menu {
            margin-top: 0;
            border-top-left-radius: 0;
            border-top-right-radius: 0
        }

        .close {
            float: right;
            font-size: 21px;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
            opacity: .2
        }

        .close:focus, .close:hover {
            color: #000;
            text-decoration: none;
            cursor: pointer;
            opacity: .5
        }

        button.close {
            -webkit-appearance: none;
            padding: 0;
            cursor: pointer;
            background: 0 0;
            border: 0
        }

        .modal {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1050;
            display: none;
            overflow: hidden;
            -webkit-overflow-scrolling: touch;
            outline: 0
        }

        .modal-dialog {
            position: relative;
            width: auto;
            margin: 10px
        }

        .modal-content {
            position: relative;
            background-color: #fff;
            -webkit-background-clip: padding-box;
            background-clip: padding-box;
            border: 1px solid #999;
            border: 1px solid rgba(0, 0, 0, .2);
            border-radius: 6px;
            outline: 0;
            -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, .5);
            box-shadow: 0 3px 9px rgba(0, 0, 0, .5)
        }

        .modal-header {
            padding: 15px;
            border-bottom: 1px solid #e5e5e5
        }

        .modal-header .close {
            margin-top: -2px
        }

        .modal-body {
            position: relative;
            padding: 15px
        }

        @media (min-width: 768px) {
            .modal-dialog {
                width: 600px;
                margin: 30px auto
            }

            .modal-content {
                -webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
                box-shadow: 0 5px 15px rgba(0, 0, 0, .5)
            }
        }

        @media (min-width: 992px) {
            .modal-lg {
                width: 900px
            }
        }

        .container-fluid:after, .container-fluid:before, .container:after, .container:before, .modal-header:after, .modal-header:before, .nav:after, .nav:before, .navbar-collapse:after, .navbar-collapse:before, .navbar-header:after, .navbar-header:before, .navbar:after, .navbar:before, .row:after, .row:before {
            display: table;
            content: " "
        }

        .container-fluid:after, .container:after, .modal-header:after, .nav:after, .navbar-collapse:after, .navbar-header:after, .navbar:after, .row:after {
            clear: both
        }

        a {
            color: #1254ff;
            cursor: pointer
        }

        a:focus, a:hover {
            color: #1e78ff;
            text-decoration: underline
        }

        .img-responsive {
            display: inline-block
        }

        :focus {
            outline: 0 !important
        }

        .list-in {
            margin: 0;
            padding: 0
        }

        .list-in li {
            display: inline-block;
            list-style: none
        }

        .btn {
            -moz-transition: .3s ease all;
            -o-transition: .3s ease all;
            -webkit-transition: .3s ease all;
            transition: .3s ease all;
            border-radius: 4px;
            padding: 4px 16px;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 5px
        }

        .btn-default {
            color: #151317;
            background-color: #d4d4d4;
            border-color: #ccc
        }

        .btn-lblue {
            background: #1254ff;
            border-radius: 4px;
            padding: 4px 16px;
            color: #fff;
            font-weight: 600;
            font-size: 16px
        }

        .btn-lblue:focus, .btn-lblue:hover {
            color: #fff;
            background-color: #4d7fff
        }

        .btn-dgray {
            background-color: #f4f4f4 !important;
            border: solid 1px #575758;
            color: #151317
        }

        .btn-dgray:focus, .btn-dgray:hover {
            background-color: #999;
            color: #575758;
            border: solid 1px #575758
        }

        .btn:disabled {
            background: 0 0;
            background-color: #d4d4d4 !important;
            border: 1px solid #151317;
            color: #151317 !important;
            font-size: 14px !important;
            pointer-events: none
        }

        .btn:disabled:active, .btn:disabled:focus, .btn:disabled:hover {
            background-color: #d4d4d4 !important;
            color: #151317 !important;
            font-size: 14px !important
        }

        .btn-black, .btn-secondary {
            padding: .2em .8em;
            border: 1px solid #1254ff;
            color: #1254ff;
            background-color: #fff;
            text-shadow: none
        }

        .btn-black:focus, .btn-black:hover, .btn-secondary:focus, .btn-secondary:hover {
            background-color: #f5f9fd;
            border-color: #1254ff;
            color: #1254ff
        }

        h4 {
            font-weight: 600
        }

        p {
            font-size: 13px;
            font-weight: 400;
            color: #151317
        }

        h4 {
            font-size: 16px;
            line-height: 22px
        }

        .inner-wrap {
            width: 100%;
            display: table;
            table-layout: fixed
        }

        article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section, summary {
            display: block
        }

        audio, canvas, progress, video {
            display: inline-block;
            vertical-align: baseline
        }

        audio:not([controls]) {
            display: none;
            height: 0
        }

        [hidden], template {
            display: none
        }

        a {
            background-color: transparent
        }

        a:active, a:hover {
            outline: 0
        }

        abbr[title] {
            border-bottom: 1px dotted
        }

        b, strong {
            font-weight: 700
        }

        dfn {
            font-style: italic
        }

        h1 {
            margin: .67em 0;
            font-size: 2em
        }

        mark {
            color: #000;
            background: #ff0
        }

        small {
            font-size: 80%
        }

        sub, sup {
            position: relative;
            font-size: 75%;
            line-height: 0;
            vertical-align: baseline
        }

        sup {
            top: -.5em
        }

        sub {
            bottom: -.25em
        }

        img {
            border: 0
        }

        svg:not(:root) {
            overflow: hidden
        }

        figure {
            margin: 1em 40px
        }

        hr {
            height: 0;
            -webkit-box-sizing: content-box;
            -moz-box-sizing: content-box;
            box-sizing: content-box
        }

        pre {
            overflow: auto
        }

        code, kbd, pre, samp {
            font-family: monospace, monospace;
            font-size: 1em
        }

        button, input, optgroup, select, textarea {
            margin: 0;
            font: inherit;
            color: inherit
        }

        button {
            overflow: visible
        }

        button, select {
            text-transform: none
        }

        button, html input[type=button], input[type=reset], input[type=submit] {
            -webkit-appearance: button;
            cursor: pointer
        }

        button[disabled], html input[disabled] {
            cursor: default
        }

        button::-moz-focus-inner, input::-moz-focus-inner {
            padding: 0;
            border: 0
        }

        input {
            line-height: normal
        }

        input[type=checkbox], input[type=radio] {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 0
        }

        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button {
            height: auto
        }

        input[type=search] {
            -webkit-box-sizing: content-box;
            -moz-box-sizing: content-box;
            box-sizing: content-box;
            -webkit-appearance: textfield
        }

        input[type=search]::-webkit-search-cancel-button, input[type=search]::-webkit-search-decoration {
            -webkit-appearance: none
        }

        fieldset {
            padding: .35em .625em .75em;
            margin: 0 2px;
            border: 1px solid silver
        }

        legend {
            padding: 0;
            border: 0
        }

        textarea {
            overflow: auto
        }

        optgroup {
            font-weight: 700
        }

        table {
            border-spacing: 0;
            border-collapse: collapse
        }

        td, th {
            padding: 0
        }

        body {
            font-family: Lato, sans-serif;
            margin: 0
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: "Red Hat Display", sans-serif
        }

        h1, h2, h3 {
            margin-bottom: 10px
        }

        .lazy-load-script {
            opacity: 0;
            display: block;
            width: 0;
            height: 0
        }

        .d-f-c {
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column
        }

        .d-f {
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex
        }

        .d-f-i {
            display: -webkit-box !important;
            display: -moz-box !important;
            display: -ms-flexbox !important;
            display: -webkit-flex !important;
            display: flex !important
        }

        .a-i_f-e {
            -webkit-box-align: end;
            -ms-flex-align: end;
            align-items: flex-end
        }

        .f-1 {
            -webkit-box-flex: 1;
            -moz-box-flex: 1;
            -ms-flex: 1 1 auto;
            -webkit-flex: 1;
            flex: 1 1 auto
        }

        .a-i_c {
            -webkit-box-align: center;
            -moz-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center
        }

        .a-i_b {
            -webkit-box-align: baseline;
            -moz-box-align: baseline;
            -ms-flex-align: baseline;
            -webkit-align-items: baseline;
            align-items: baseline
        }

        .j-c_c {
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center
        }

        .font_w_normal {
            font-weight: 400
        }

        .j-c_s-b {
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between
        }

        .j-c_s-a {
            -ms-flex-pack: distribute;
            justify-content: space-around
        }

        .copart-black-color {
            color: #3a4351
        }

        .flex-b-32 {
            flex-basis: 32%
        }

        .flex-b-24 {
            flex-basis: 24%
        }

        .flex-b-20 {
            flex-basis: 20%
        }

        .flex-b-50 {
            flex-basis: 50%
        }

        .flex-b-75 {
            flex-basis: 75%
        }

        .f-g1 {
            flex-grow: 1
        }

        .f-g2 {
            flex-grow: 2
        }

        .f-w_w {
            flex-wrap: wrap
        }

        .m-0 {
            margin: 0
        }

        .m-0-i {
            margin: 0 !important
        }

        .m-5 {
            margin: 5px
        }

        .m-10 {
            margin: 10px
        }

        .m-15 {
            margin: 15px
        }

        .my-0 {
            margin-top: 0;
            margin-bottom: 0
        }

        .my-5 {
            margin-top: 5px;
            margin-bottom: 5px
        }

        .my-10 {
            margin-top: 10px;
            margin-bottom: 10px
        }

        .my-15 {
            margin-top: 15px;
            margin-bottom: 15px
        }

        .my-20 {
            margin-top: 20px;
            margin-bottom: 20px
        }

        .mx-0 {
            margin-left: 0;
            margin-right: 0
        }

        .mx-5 {
            margin-left: 5px;
            margin-right: 5px
        }

        .mx-10 {
            margin-left: 10px;
            margin-right: 10px
        }

        .desktopScreen {
            display: block;
        }

        .header-nav {
            min-height: 50px;
            background-color: #1d1e20;
            padding-left: 16px;
        }

        .header-nav {
            min-height: 50px;
            background-color: #1d1e20;
        }

        .floating-design-sprite.header-floating-design {
            background-position: 5px -140px;
            width: 112px;
            height: 85px;
            position: absolute;
            top: 0;
            right: 0;
        }

        .floating-design-sprite {
            background-image: url(/images/icons/Floating_design_icons.svg);
            background-repeat: no-repeat;
            display: inline-block;
        }

        .desktopScreen {
            display: block;
        }

        .header-nav {
            min-height: 50px;
            background-color: #1d1e20;
            padding-left: 16px;
        }

        .header-nav {
            min-height: 50px;
            background-color: #1d1e20;
        }

        /***** GRID SYSTEM *****/

        .grid-container {
            width: 90%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .grid-row {
            position: relative;
            width: 100%;
        }

        .grid-container .grid-row [class^="col"] {
            float: left;
            margin: 25px 0.8%;
            min-height: 0.125rem;
            box-sizing: border-box;
        }

        .header-top .globeicon {
            height: 26px;
        }

        .px-5 {
            padding-left: 5px;
            padding-right: 5px;
        }

        .pad10 {
            padding: 10px;
        }

        .header .langdiv {
            margin-top: 20px;
            font-size: 14px;
        }

        .header .header-top .list-options {
            padding-top: 0;
            padding-bottom: 0;
        }

        .list-in li {
            display: inline-block;
            list-style: none;
        }

        .desktop-only {
            display: -webkit-inline-box !important;
        }

        .col-1-sm {
            width: 6.73%;
        }

        .col-2-sm {
            width: 15.06%;
        }

        .col-3-sm {
            width: 23.4%;
        }

        .col-4-sm {
            width: 31.73%;
        }

        .col-5-sm {
            width: 40.07%;
        }

        .col-6-sm {
            width: 48.4%;
        }

        .col-7-sm {
            width: 56.73%;
        }

        .col-8-sm {
            width: 65.07%;
        }

        .col-9-sm {
            width: 73.4%;
        }

        .col-10-sm {
            width: 81.73%;
        }

        .col-11-sm {
            width: 90.07%;
        }

        .col-12-sm {
            width: 98.4%;
        }

        .grid-row::after {
            content: "";
            display: table;
            clear: both;
        }

        .hidden-sm {
            display: none;
        }

        @media only screen and (min-width: 33.75em) {
            /* 540px */
            .grid-container {
                width: 90%;
            }

        }

        @media only screen and (min-width: 770px) {
            /* 720px */
            .col-1 {
                width: 6.73%;
            }

            .col-2 {
                width: 15.06%;
            }

            .col-3 {
                width: 23.4%;
            }

            .col-4 {
                width: 31.73%;
            }

            .col-5 {
                width: 40.07%;
            }

            .col-6 {
                width: 48.4%;
            }

            .col-7 {
                width: 56.73%;
            }

            .col-8 {
                width: 65.07%;
            }

            .col-9 {
                width: 73.4%;
            }

            .col-10 {
                width: 81.73%;
            }

            .col-11 {
                width: 90.07%;
            }

            .col-12 {
                width: 98.4%;
            }

            .hidden-sm {
                display: block;
            }

        }

        @media only screen and (max-width: 770px) {
            .col-1,
            .col-2,
            .col-3,
            .col-4,
            .col-5,
            .col-6,
            .col-7,
            .col-8,
            .col-9,
            .col-10,
            .col-11,
            .col-12 {
                width: 98.33%;
            }
        }

        @media only screen and (min-width: 60em) {
            /* 960px */
            .grid-container {
                width: 100%;
                max-width: 1200px;
                box-sizing: border-box;
                padding: 0 25px;
            }

        }

        /* STRIPS PAGE OF ALL TEMPLATE STYLING */

        .inner-wrap .mbt {
            max-width: 1200px;
            margin: 40px auto 0 auto;
        }

        .inner-wrap .container {
            width: 100% !important;
            padding: 0;
        }

        .inner-wrap .sectionBlock {
            border: none;
            border-radius: 0;
            font-size: 14px;
            margin-bottom: 0;
        }

        .inner-wrap .sectionContent {
            padding: 0;
        }

        .inner-wrap .clear {
            display: none;
        }

        header {
            font-size: 13px;
        }

        /* MAIN STYLE */
        html,
        body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            left: 0;
            top: 0;
            font-size: 100%;
            font-family: 'Open Sans', sans-serif;
            color: #1d1d1d;
            line-height: 1.6em;
        }

        h1 {
            font-size: 28px;
            color: #575758;
            margin: 0 0 10px 0;
            font-weight: 700;
        }

        h2 {
            font-weight: 600;
            font-size: 24px;
            color: #2F3942;
            margin: 0 0 15px 0;
        }

        h3 {
            font-size: 18px;
            margin: 5px 0 8px 0;
            font-weight: 700;
            color: #1D1D1D;
            line-height: 1.3em;
        }

        h4 {
            font-weight: 600;
            font-size: 22px;
            color: #545A63;
            margin: 0 0 5px 0;
        }

        .content-box h3, .content-box h4 {
            padding: 10px 0 15px 0;
            margin-top: 0;
            margin-bottom: 0;
        }

        p {
            margin: 0 0 1.25em 0;
            line-height: 1.6em;
        }

        p:last-of-type {
            margin: 0;
        }

        .text-center {
            text-align: center;
        }

        a {
            font-weight: 600;
            color: #1D5AB9;
            text-decoration: none;
            cursor: pointer;
        }

        a:hover {
            color: #3275DC;
        }

        strong {
            font-weight: 700;
        }

        p.intro-text {
            color: #545A63;
            font-size: 16px;
            font-weight: 400;
            text-align: center;
            margin-top: 20px;
        }

        p.intro-text2 {
            color: #2F3942;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
            margin: 20px 0 40px 0;
        }

        .reg-h3 {
            color: #0D5DB8;
            font-size: 32px;
            font-weight: 700;
            text-align: center;
        }

        .reg-h4 {
            color: #0D5DB8;
            font-size: 20px;
            font-weight: 700;
            text-align: left;
        }

        ol.reg-steps {
            margin-top: 25px;
            display: inline-block;
            text-align: left;
        }

        ol.reg-steps li {
            font-size: 18px;
        }

        ol.business-reg-steps li {
            font-size: 14px;
        }

        .authrep-h3 {
            color: #2F3942;
            font-size: 24px;
            font-weight: 700;
        }

        .authrep-container {
            display: block;
            position: relative;
            width: 100%;
            height: auto;
            min-height: 200px;
            border-radius: 16px;
            background-color: #ffffff;
            padding: 30px 20px;
            box-sizing: border-box;
            overflow: hidden;
        }

        .Vehicle-container {
            display: block;
            position: relative;
            width: 100%;
            height: auto;
            min-height: 100px;
            background-color: #ffffff;
            padding: 5px 5px;
            box-sizing: border-box;
            overflow: hidden;
        }

        h4.authrep-h4 {
            color: #0D5DB8;
            font-size: 18px;
            font-weight: 600;
        }

        .grid-container ul {
            font-size: 15px !important;
        }

        .grid-container ol {
            list-style-type: decimal !important;
            font-size: 15px !important;
            padding-left: 20px;
        }

        .grid-container ul li, .grid-container ol li, .full-width-section ul li {
            margin-bottom: .5em !important;
        }

        ul.steps li, ol.steps li {
            margin-bottom: 3em !important;
        }

        .grid-container ol li ul {
            margin-top: 20px;
        }

        .grid-container ul ol li, .grid-container ol ul li {
            margin-bottom: .5em !important;
        }

        ul.list-style-blue {
            color: #4482E3;
            display: inline-block;
            text-align: left;
            margin-bottom: 25px;
        }

        ul.list-style-blue li span {
            color: #ffffff;
        }

        img.float-left {
            margin: 0 20px 20px 0;
        }

        img.float-right {
            margin: 0 0 20px 20px;
        }

        img.center-content {
            display: block;
            box-sizing: border-box;
            padding: 20px;
        }

        img.fill-container {
            width: 100% !important;
        }

        hr {
            border: solid 0.5px #D8D8D8;
            margin: 20px 0 0 0;
            height: 0;
        }

        .page-hero {
            display: block;
            position: relative;
            overflow: hidden;
            width: 100%;
            height: auto;
            box-sizing: border-box;
            padding: 35px 0 1px 0;
            margin-bottom: 15px;
        }

        .page-hero h1 {
            font-size: 36px;
            margin: 0 0 30px 0;
            font-weight: 700;
            line-height: 1.2;
            text-align: center !important;
        }

        .hero-subtext {
            font-size: 22px;
            margin: 20px 0 !important;
        }

        .full-width-section {
            display: block;
            position: relative;
            overflow: visible;
            width: 100%;
            height: auto;
            box-sizing: border-box;
            padding: 0;
            margin: 30px 0;
            background-color: 4482 E3
        }

        .full-width-section-top {
            display: block;
            position: relative;
            overflow: visible;
            width: 100%;
            height: 55px;
            box-sizing: border-box;
            padding: 0;
            margin: 0;
            background-color: #e2e5e8
        }

        .banner-announcement {
            display: block;
            position: relative;
            margin: 0 0 20px 0;
            width: 100%;
            box-sizing: border-box;
            padding: 8px 20px;
        }

        .banner-announcement p {
            margin-bottom: 0 !important;
        }

        .sub-nav-wrapper {
            display: block;
            position: relative;
            overflow: hidden;
            width: 100%;
            authrep-container height: auto;
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        .sub-nav-wrapper .grid-row [class^="col"] {
            margin: 0 0.8%;
        }

        /* HELPERS */
        .float-left {
            float: left;
        }

        .float-right {
            float: right;
        }

        .float-center {
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }

        .center-content {
            display: block;
            text-align: center;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        .spacer {
            display: block;
            margin: 0 0 15px 0;
            clear: both;
        }

        .clearer {
            display: block;
            clear: both;
        }

        .sticky {
            position: fixed;
            width: 100%;
            left: 0;
            top: 0;
            z-index: 100;
            border-top: 0;
        }

        .italic {
            font-style: italic;
        }

        p.spacer {
            margin-bottom: 35px;
        }

        .last {
            margin-bottom: 0;
        }

        .mobile-remove {
            display: block;
        }

        .mobile-remove1 {
            display: block;
        }

        .mobile-display {
            display: none;
        }

        .round-corners {
            border-radius: 5px;
        }

        .padding {
            padding: 20px;
        }

        /* BUTTONS */
        a.copart-btn-open {
            display: inline-block;
            margin: 0 auto 10px auto;
            font-weight: 600;
            font-size: 14px;
            color: #1D5AB9;
            border: solid 1px #005BBB;
            border-radius: 4px;
            padding: 8px 16px;
            transition: all 0.3s ease-in-out;
        }

        a.copart-btn-open:hover {
            background-color: #005BBB;
            color: #FFFFFF;
            text-decoration: none;
        }

        a.copart-btn-open:active {
            background-color: #3275DD;
        }

        a.copart-btn-open-large {
            display: inline-block;
            margin: 0 auto 10px auto;
            font-weight: 600;
            font-size: 16px;
            color: #1D5AB9;
            border: solid 1px #005BBB;
            border-radius: 4px;
            padding: 12px 20px;
            transition: all 0.3s ease-in-out;
        }

        a.copart-btn-open-large:hover {
            background-color: #005BBB;
            color: #FFFFFF;
            text-decoration: none;
        }

        a.copart-btn-open-large:active {
            background-color: #3275DD;
        }

        a.copart-language-btn {
            background-color: transparent;
            display: inline-block;
            margin: 0 auto;
            min-width: 80px;
            text-align: center;
            font-size: 12px;
            color: black;
            border-radius: 4px;
        }

        a.copart-language-btn-2 {
            background-color: transparent;
            display: inline-block;
            margin: 0 auto;
            min-width: 40px;
            text-align: center;
            font-size: 12px;
            color: black;
            border-radius: 4px;
        }

        a.copart-language-btn-2lang {
            background-color: transparent;
            display: inline-block;
            margin: 0 auto;
            min-width: 65px;
            text-align: center;
            font-size: 12px;
            color: black;
            border-radius: 4px;
        }

        a.copart-language-btn-active {
            background-color: #3275DD;
            display: inline-block;
            margin: 0 auto;
            min-width: 40px;
            text-align: center;
            font-size: 12px;
            color: #FFFFFF;
            border-radius: 4px;
        }

        a.copart-language-btn-active-2lang {
            background-color: #3275DD;
            display: inline-block;
            margin: 0 auto;
            min-width: 65px;
            text-align: center;
            font-size: 12px;
            color: #FFFFFF;
            border-radius: 4px;
        }

        a.authrep-btn {
            background-color: #cad5e8;
            display: inline-block;
            width: 100%;
            min-height: 55px;
            text-align: center;
            color: black;
            font-size: 14px;
            margin-top: 0px;
            border-radius: 0px;
            padding: 14px 10px 10px 20px;
        }

        a.copart-language-btn:hover {
            background-color: #3275DC;
            color: white;
            text-decoration: none;
        }

        a.copart-language-btn:active {
            background-color: #3275DC;
        }

        a.copart-language-btn-2:hover {
            background-color: #3275DC;
            color: white;
            text-decoration: none;
        }

        .copart-language-btn-2lang:hover {
            background-color: #3275DC;
            color: white;
            text-decoration: none;
        }

        a.copart-language-btn-2:active {
            background-color: #3275DC;
        }

        a.copart-btn-closed {
            display: inline-block;
            margin: 0 auto 10px auto;
            font-weight: 600;
            font-size: 14px;
            color: #FFFFFF;
            background-color: #005BBB;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            transition: all 0.3s ease-in-out;
        }

        a.copart-btn-closed:hover {
            background-color: #3275DC;
            text-decoration: none;
        }

        a.copart-btn-closed:active {
            background-color: #005BBB;
        }

        a.copart-btn-closed-large {
            display: inline-block;
            margin: 0 auto 10px auto;
            font-weight: 600;
            font-size: 20px;
            color: #FFFFFF;
            background-color: #005BBB;
            border: none;
            border-radius: 4px;
            padding: 16px 80px;
            transition: all 0.3s ease-in-out;
            text-align: center;
        }

        a.copart-btn-closed-large:hover {
            background-color: #3275DC;
            text-decoration: none;
        }

        a.copart-btn-closed-large:active {
            background-color: #005BBB;
        }

        a.drive-btn-secondary {
            display: inline-block;
            margin: 0 auto 10px auto;
            font-weight: 600;
            font-size: 14px;
            color: #F7BD00;
            background-color: #4A4947;
            border-radius: 4px;
            padding: 8px 16px;
            transition: all 0.3s ease-in-out;
        }

        a.drive-btn-secondary:hover {
            background-color: #5D5D5C;
            text-decoration: none;
        }

        a.drive-btn-secondary:active {
            background-color: #4A4947;
        }

        a.drive-btn-secondary-large {
            display: inline-block;
            margin: 0 auto 10px auto;
            font-weight: 600;
            font-size: 16px;
            color: #F7BD00;
            background-color: #4A4947;
            border-radius: 4px;
            padding: 12px 20px;
            transition: all 0.3s ease-in-out;
        }

        a.drive-btn-secondary-large:hover {
            background-color: #5D5D5C;
            text-decoration: none;
        }

        a.drive-btn-secondary-large:active {
            background-color: #4A4947;
        }

        a.drive-btn-closed {
            display: inline-block;
            margin: 0 auto 10px auto;
            font-weight: 600;
            font-size: 14px;
            color: #231F20;
            background-color: #F7BD00;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            transition: all 0.3s ease-in-out;
        }

        a.drive-btn-closed:hover {
            background-color: #FFCB20;
            text-decoration: none;
        }

        a.drive-btn-closed:active {
            background-color: #F7BD00;
        }

        a.drive-btn-closed-large {
            display: inline-block;
            margin: 0 auto 10px auto;
            font-weight: 600;
            font-size: 16px;
            color: #231F20;
            background-color: #F7BD00;
            border: none;
            border-radius: 4px;
            padding: 12px 20px;
            transition: all 0.3s ease-in-out;
        }

        a.drive-btn-closed-large:hover {
            background-color: #FFCB20;
            text-decoration: none;
        }

        a.drive-btn-closed-large:active {
            background-color: #F7BD00;
        }

        a.ct-btn-open {
            display: inline-block;
            margin: 0 auto 10px auto;
            font-weight: 600;
            font-size: 14px;
            color: #F0231A;
            border: solid 1px #F0231A;
            border-radius: 4px;
            padding: 8px 16px;
            transition: all 0.3s ease-in-out;
        }

        a.ct-btn-open:hover {
            background-color: #F0231A;
            color: #FFFFFF;
            text-decoration: none;
        }

        a.ct-btn-open:active {
            background-color: #3275DD;
        }

        a.ct-btn-open-large {
            display: inline-block;
            margin: 0 auto 10px auto;
            font-weight: 600;
            font-size: 16px;
            color: #F0231A;
            border: solid 1px #F0231A;
            border-radius: 4px;
            padding: 12px 20px;
            transition: all 0.3s ease-in-out;
        }

        a.ct-btn-open-large:hover {
            background-color: #F0231A;
            color: #FFFFFF;
            text-decoration: none;
        }

        a.ct-btn-open-large:active {
            background-color: #3275DD;
        }

        a.ct-btn-closed {
            display: inline-block;
            margin: 0 auto 10px auto;
            font-weight: 600;
            font-size: 14px;
            color: #FFFFFF;
            background-color: #F0231A;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            transition: all 0.3s ease-in-out;
        }

        a.ct-btn-closed:hover {
            background-color: #FF4040;
            text-decoration: none;
        }

        a.ct-btn-closed:active {
            background-color: #F0231A;
        }

        a.ct-btn-closed-large {
            display: inline-block;
            margin: 0 auto 10px auto;
            font-weight: 600;
            font-size: 16px;
            color: #FFFFFF;
            background-color: #F0231A;
            border: none;
            border-radius: 4px;
            padding: 12px 20px;
            transition: all 0.3s ease-in-out;
        }

        a.ct-btn-closed-large:hover {
            background-color: #FF4040;
            text-decoration: none;
        }

        a.ct-btn-closed-large:active {
            background-color: #F0231A;
        }

        a.drive-container-btn-yellow {
            display: block;
            margin: 0 auto;
            font-weight: 700;
            font-size: 14px;
            color: #231F20;
            background-color: #F7BD00;
            border: none;
            border-radius: 0;
            padding: 8px 16px;
            transition: all 0.3s ease-in-out;
            bottom: 0;
            position: absolute;
            width: 100%;
            text-align: center;
        }

        a.drive-container-btn-dark-gray {
            display: block;
            margin: 0 auto;
            font-weight: 600;
            font-size: 14px;
            color: #F7BD00;
            background-color: #231F20;
            border: none;
            border-radius: 0;
            padding: 8px 16px;
            transition: all 0.3s ease-in-out;
            bottom: 0;
            position: absolute;
            width: 100%;
            text-align: center;
        }

        .sub-nav-item {
            display: block;
            margin: 0;
            padding: 8px;
            font-weight: 700;
            font-size: 16px;
            text-align: center;
            color: #231F20;
            border: none;
        }

        .sub-nav-item:hover {
            color: #231F20;
        }

        /* CONTAINERS */
        .container-with-border {
            display: block;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
            width: 100%;
            height: auto;
            margin: 0 0 20px 0;
            border: solid 1px #DFDFDF;
            border-radius: 5px;
        }

        .container-with-no-border {
            display: block;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
            width: 100%;
            height: auto;
            margin: 0 0 20px 0;
        }

        .container-title {
            display: block;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
            background-color: #005BBB;
            padding: 8px 20px;
        }

        .container-with-no-border .container-title {
            display: block;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
            background-color: #005BBB;
            padding: 9px 20px;
            border-radius: 5px;
        }

        .container-title h2, .container-title h3, .container-title h4, .container-title p {
            color: #FFFFFF;
            font-size: 16px;
            font-weight: 600;
            margin: 0;
            padding: 0;
        }

        .container-image {
            display: block;
            overflow: hidden;
            width: 100%;
            height: auto;
            min-height: 200px;
        }

        .container-content {
            display: block;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
            width: 100%;
            height: auto;
            padding: 15px 20px 10px 20px;
        }

        .container-content p:last-of-type {
            margin-bottom: 0;
        }

        .readmore-link {
            display: block;
            position: relative;
            width: 100%;
            overflow: hidden;
            box-sizing: border-box;
            background-color: #F7F7F7;
            padding: 5px 20px;
            border-top: solid 1px #DFDFDF;
            margin-top: 5px;
        }

        .container-with-no-border .readmore-link {
            border: solid 1px #DFDFDF;
            border-radius: 5px;
        }

        /* COLOR CLASSES */
        .copart-blue {
            background-color: #005BBB !important;
            color: #FFFFFF !important;
        }

        .copart-blue-dark {
            background-color: #2F3942 !important;
            color: #FFFFFF !important;
        }

        .copart-blue-light {
            background-color: #E1EDFF !important;
            color: #2F3942 !important;
        }

        .copart-blue-accent {
            background-color: #2768CE !important;
            color: #FFFFFF !important;
        }

        .copart-border {
            border: solid 1px #005BBB;
        }

        .light-gray {
            background-color: #DFDFDF !important;
            color: #575758 !important;
        }

        .white-bg {
            background-color: #FFFFFF !important;
            color: #585858 !important;
        }

        .white-text {
            color: #FFFFFF !important;
        }

        .drive-yellow {
            background-color: #F7BD00 !important;
            color: #231F20 !important;
        }

        .drive-dark-gray {
            background-color: #231F20 !important;
            color: #F7BD00 !important;
        }

        .drive-light-gray {
            background-color: #4A4947 !important;
            color: #F7BD00 !important;
        }

        .drive-light-yellow {
            background-color: #FFF6DA !important;
            color: #231F20 !important;
        }

        .drive-border {
            border: solid 1px #F7BD00;
        }

        .crashedtoys-red {
            background-color: #F0231A !important;
            color: #FFFFFF !important;
        }

        .crashedtoys-red-light {
            background-color: #FFE8E8 !important;
            color: #F0231A !important;
        }

        .crashedtoys-border {
            border: solid 1px #F0231A;
        }

        .FastSearch-border {
            border: solid 1px #F0231A;
        }

        .dropdown-wrapper {
            display: block;
            width: auto;
            max-width: 520px;
            margin: 0 auto;
            padding: 0 4px;
        }

        .dropdown {
            position: relative;
        }

        .button-primary {
            background-color: #e2e5e8;
            border: 0px solid;
            border-color: #DFDFDF;
            width: 115%;
            border-radius: 6px;
            color: #000;
            font-weight: 600;
            text-align: left;
            padding: 10px 10px 10px 10px;
        }

        .button-primary-translation {
            background-color: #e2e5e8;
            border: 0px solid;
            border-color: #DFDFDF;
            width: 120%;
            border-radius: 6px;
            color: #000;
            font-weight: 600;
            text-align: left;
            padding: 10px 10px 10px 10px;
        }

        ul.intl-dropdown {
            margin-top: 0 !important;
            list-style-type: none !important;
            font-size: 14px !important;
            padding: 10px 0 10px 0 !important;
            width: 105%;
        }

        ul.intl-dropdown-translation {
            margin-top: 0 !important;
            list-style-type: none !important;
            font-size: 14px !important;
            padding: 10px 0 10px 0 !important;
            width: 115%;
        }

        .dropdown-menu {
            margin: 0;
            padding: 0;
            font-size: 13px;
            left: 0;
            min-width: 130px;
            z-index: 1100;
        }

        .dropdown-menu-left {
            right: auto;
            left: 0;
        }

        .dropdown-menu li a {
            background-color: #ffffff;
            color: #000000;
        }

        ul.navigation-items {
            list-style-type: none;
            width: 100%;
            margin-top: 0px;
            padding: 0;
        }

        ul.navigation-items li {
            vertical-align: top;
        }

        li.uniqueClassName {
            display: inline-block;
            width: 26%;
            margin-top: 2px;
            padding: 2px 40px 2px 80px;
        }

        li.uniqueClassName-Translation {
            display: inline-block;
            width: 26%;
            margin-top: 2px;
            padding: 2px 0px 2px 85px;
        }

        li.langswitcher {
            display: inline-block;
            list-style-type: none;
            max-height: 50px;
            width: 23%;
            margin-top: 8px;
        }

        li.langswitcher-Translation {
            display: inline-block;
            list-style-type: none;
            max-height: 50px;
            width: 23%;
            margin-top: 8px;
        }

        li.button1 {
            display: inline-block;
            width: 49%;
            height: 55px;
            margin-top: 0px;
            margin-left: 20px;
            text-align: center;
        }

        li.button1-Translation {
            display: inline-block;
            width: 49%;
            height: 55px;
            margin-top: 0px;
            margin-left: 20px;
            text-align: center;
        }

        li.button_en {
            display: inline-block;
            margin-left: 10px;
        }

        li.button_local_language {
            display: inline-block;
            margin-left: 10px;
        }

        .mobile-remove2 {
            float: right;
            width: 67px;
            height: 35px;
            margin-right: 7px;
            margin-top: -5px;
        }

        .mobile-remove3 {
            float: left;
            width: 25px;
            height: 25px;
            margin-right: -3px;
            margin-top: 11px;
        }

        .grid-row1 {
            display: block;
        }

        .modal {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1050;
            overflow: hidden;
            outline: 0;
        }

        .fade {
            opacity: 0;
            transition: opacity .15s;
        }

        .modal-dialog {
            max-width: 750px;
            width: auto;
        }

        .modal-content {
            position: relative;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid;
            border-radius: 6px;
            outline: 0;
        }

        .modal-body {
            padding: 15px;
            position: relative;
        }

        .embed-responsive-16by9 {
            padding-bottom: 56.25%
        }

        .embed-responsive {
            position: relative;
            display: block;
            height: 0;
            padding: 0;
            overflow: hidden;
        }

        .Download-App {
            color: #2F3942;
            padding: 8px 0px;
            font-size: 18px;
            float: left;
        }

        .ULClass {
            margin-top: 6px;
        }

        .page-hero a.copart-btn-closed {
            background-color: #4482E3;
            display: inline-block;
            margin: 0 auto;
            min-width: 380px;
            text-align: center;
            font-size: 20px;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
        }

        .feedback {
            background-color: #E1EDFF;
        }

        .section-wrapper {
            display: block;
            position: relative;
            box-sizing: border-box;
            width: 100%;
            padding: 20px 20px;
            margin: 0 auto;
            text-align: center;
        }

        .question-p {
            font-size: 16px;
            text-align: center;
            margin: 0;
        }

        .cta-h {
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            color: #1D5AB9;
            line-height: 36px;
            margin-top: 25px;
        }

        .cta-text {
            text-align: center;
            font-size: 16px;
            font-weight: 400;
            color: inherit;
            margin-bottom: 0;
        }

        .cta-badges {
            display: block;
            width: 346px;
            margin: 15px auto 10px auto;
        }

        .cta-badges a {
            margin: 0 0 15px 0 !important;
        }

        .badges a {
            display: inline-block;
        }

        .cta-badges img {
            margin: 0 15px;
        }

        .badges img {
            display: block;
        }

        .mobile-remove4 {
            width: 27px;
            height: 27px;
        }

        .language-text {
            color: #000000;
            font-size: 14px;
            font-weight: 600;
        }

        .hero-bullets {
            font-size: 20px;
        }

        .X-display-topright {
            position: absolute;
            right: 0;
            top: 0;
        }

        .close {
            border: none;
            display: inline-block;
            padding: 8px 16px;
            vertical-align: middle;
            overflow: hidden;
            text-decoration: none;
            color: black;
            background-color: inherit;
            text-align: center;
            cursor: pointer;
            white-space: nowrap;
        }

        .close:hover {
            background-color: #4482E3;
            color: #FFFFFF;
            text-decoration: none;
        }

        @media only screen and (max-width: 1554px) {

            ul.navigation-items li.uniqueClassName {
                width: 30%;
            }

            ul.navigation-items li.langswitcher {
                width: 30%;
            }

            ul.navigation-items li.button1 {
                width: 37%;
            }

            ul.navigation-items li.langswitcher-Translation {
                width: 30%;
            }

            ul.navigation-items li.button1-Translation {
                width: 42%;
            }
        }

        @media only screen and (max-width: 1430px) {

            ul.navigation-items li.button1-Translation {
                width: 41%;
            }
        }

        @media only screen and (max-width: 1349px) {

            ul.navigation-items li.uniqueClassName {
                width: 35%;
            }

            ul.navigation-items li.langswitcher {
                width: 30%;
            }

            ul.navigation-items li.button1 {
                width: 32%;
            }
        }

        @media only screen and (max-width: 1160px) {

            ul.navigation-items li.uniqueClassName {
                width: 40%;
            }

            ul.navigation-items li.langswitcher {
                width: 30%;
            }

            ul.navigation-items li.button1 {
                width: 26%;
            }
        }

        @media only screen and (max-width: 1119px) {

            /*ul.navigation-items li.uniqueClassName img.mobile-remove2 {
		display: none;
	}*/
            li.uniqueClassName-Translation img.mobile-remove2 {
                display: none;
            }

            ul.navigation-items li.uniqueClassName {
                width: 35%;
            }

            ul.navigation-items li.langswitcher {
                width: 35%;
            }

            ul.navigation-items li.button1 {
                width: 26%;
            }

            ul.navigation-items li.uniqueClassName-Translation {
                width: 24%;
            }

            ul.navigation-items li.langswitcher-Translation {
                width: 30%;
            }
        }

        @media only screen and (max-width: 1079px) {

            .mobile-remove2 {
                display: none;
            }
        }

        @media only screen and (max-width: 1064px) {

            ul.navigation-items li.uniqueClassName {
                width: 35%;
            }

            ul.navigation-items li.langswitcher {
                width: 35%;
            }

            ul.navigation-items li.button1 {
                width: 26%;
            }

            li.button1-Translation a.authrep-btn {
                padding: 1px 10px 10px 20px;
            }
        }

        @media only screen and (max-width: 1023px) {

            li.langswitcher-Translation img.mobile-remove4 {
                display: none;
            }

            li.uniqueClassName-Translation {
                padding: 2px 0px 2px 55px;
            }

            ul.navigation-items li.uniqueClassName-Translation {
                width: 22%;
            }

            ul.navigation-items li.langswitcher-Translation {
                width: 33%;
            }
        }

        @media only screen and (max-width: 997px) {

            ul.navigation-items li.button1 a.authrep-btn {
                padding: 3px 10px 10px 20px;
            }
        }

        @media only screen and (max-width: 961px) {

            ul.navigation-items li.uniqueClassName {
                width: 30%;
                padding: 2px 15px;
            }

            ul.navigation-items li.langswitcher {
                width: 40%;
            }

            ul.navigation-items li.button1 {
                width: 25%;
            }

            .button-primary {
                width: 118%;
            }

            ul.intl-dropdown {
                width: 113%;
            }
        }

        span.caret {
            display: none !important;
        }

        @media only screen and (max-width: 936px) {

            ul.navigation-items li.uniqueClassName {
                width: 32%;
                padding: 2px 15px;
            }

            ul.navigation-items li.langswitcher {
                width: 39%;
            }

            ul.navigation-items li.button1 {
                width: 25%;
            }
        }

        @media only screen and (max-width: 931px) {

            ul.navigation-items li.uniqueClassName {
                width: 32%;
                padding: 2px 15px;
            }

            ul.navigation-items li.langswitcher {
                width: 39%;
            }

            ul.navigation-items li.button1 {
                width: 25%;
            }

            li.uniqueClassName-Translation {
                padding: 2px 0px 2px 30px;
            }

            ul.navigation-items li.uniqueClassName-Translation {
                width: 20%;
            }

            ul.navigation-items li.langswitcher-Translation {
                width: 35%;
            }
        }

        @media only screen and (max-width: 886px) {

            ul.navigation-items li.uniqueClassName {
                width: 34%;
                padding: 2px 15px;
            }

            ul.navigation-items li.langswitcher {
                width: 37%;
            }

            ul.navigation-items li.button1 {
                width: 25%;
            }
        }

        @media only screen and (max-width: 880px) {

            li.langswitcher-Translation li.button_en {
                margin-left: 1px;
            }

            li.langswitcher-Translation li.button_local_language {
                margin-left: 1px;
            }
        }

        @media only screen and (max-width: 832px) {

            li.button_en {
                margin-left: 7px;
            }
        }

        @media only screen and (max-width: 828px) {

            ul.navigation-items li.uniqueClassName img.mobile-remove3 {
                display: none;
            }

            ul.navigation-items li.langswitcher img.mobile-remove4 {
                display: none;
            }

            li.button_en {
                margin-left: 1px;
            }

            li.button_local_language {
                margin-left: 1px;
            }

            ul.navigation-items li.uniqueClassName {
                width: 33%;
                padding: 2px 15px;
            }

            ul.navigation-items li.langswitcher {
                width: 38%;
            }

            ul.navigation-items li.button1 {
                width: 25%;
            }
        }

        @media only screen and (max-width: 793px) {

            li.uniqueClassName-Translation {
                padding: 2px 0px;
            }

            ul.navigation-items li.uniqueClassName-Translation {
                width: 18%;
            }

            ul.navigation-items li.langswitcher-Translation {
                width: 37%;
            }

            li.langswitcher-Translation ul.ULClass {
                padding-left: 20px;
            }
        }

        @media only screen and (max-width: 786px) {

            ul.navigation-items li.langswitcher ul {
                padding-left: 0px;
            }

            ul.navigation-items li.uniqueClassName {
                width: 37%;
            }

            ul.navigation-items li.langswitcher {
                width: 37%;
            }

            ul.navigation-items li.button1 {
                width: 21%;
            }
        }

        @media only screen and (max-width: 770px) {
            .col-1.mobile-remove {
                display: none;
            }

            .col-2.mobile-remove {
                display: none;
            }
        }

        @media only screen and (max-width: 723px) {

            li.button1-Translation {
                margin-left: 3px;
            }

            ul.navigation-items li.button1-Translation {
                width: 43%;
            }
        }

        @media only screen and (max-width: 705px) {

            ul.navigation-items li.uniqueClassName {
                width: 34%;
                padding: 2px 0px;
            }

            ul.navigation-items li.langswitcher {
                width: 38%;
            }

            ul.navigation-items li.button1 {
                width: 23%;
            }
        }

        @media only screen and (max-width: 698px) {

            .full-width-section-top {
                height: auto;
            }

            ul.navigation-items li.uniqueClassName-Translation img.mobile-remove2 {
                display: inline-block;
            }

            ul.navigation-items li.uniqueClassName-Translation img.mobile-remove3 {
                display: inline-block;
            }

            ul.navigation-items li.langswitcher-Translation img.mobile-remove4 {
                display: inline-block;
            }

            ul.navigation-items li.uniqueClassName-Translation {
                width: 100%;
                padding: 0px;
            }

            ul.navigation-items li.langswitcher-Translation {
                width: 100%;
            }

            ul.navigation-items li.button1-Translation {
                width: 100%;
                margin-top: 8px;
                margin-left: auto;
            }

            ul.navigation-items li.button1-Translation a.authrep-btn {
                padding: 14px 10px 10px 20px;
            }

            ul.navigation-items {
                margin-bottom: auto;
            }

            li.langswitcher-Translation li.button_en {
                margin-left: 5px;
            }

            li.langswitcher-Translation li.button_local_language {
                margin-left: 5px;
            }

            ul.navigation-items li.langswitcher-Translation ul {
                padding-left: 0px;
                text-align: center;
            }

            li.langswitcher-Translation ul.ULClass {
                margin-top: -5px;
            }

            li.uniqueClassName-Translation .Choosecountry {
                display: block;
                margin: 0 auto;
                width: 192px;
            }

            li.uniqueClassName-Translation .button-primary {
                width: 110%;
            }

            li.uniqueClassName-Translation ul.intl-dropdown {
                width: 110%;
            }

            .page-hero a.copart-btn-closed {
                display: block;
                max-width: 380px;
                min-width: 320px;
            }

            a.copart-btn-closed-large {
                display: block;
                max-width: 380px;
                min-width: 320px;
                padding: 16px 35px;
            }
        }

        @media only screen and (max-width: 676px) {

            .full-width-section-top {
                height: auto;
            }

            ul.navigation-items li.uniqueClassName img.mobile-remove2 {
                display: inline-block;
            }

            ul.navigation-items li.uniqueClassName img.mobile-remove3 {
                display: inline-block;
            }

            ul.navigation-items li.langswitcher img.mobile-remove4 {
                display: inline-block;
            }

            ul.navigation-items li.uniqueClassName {
                width: 100%;
                padding: 0px;
            }

            ul.navigation-items li.langswitcher {
                width: 100%;
            }

            ul.navigation-items li.button1 {
                width: 100%;
                margin-top: 8px;
                margin-left: auto;
            }

            ul.navigation-items li.button1 a.authrep-btn {
                padding: 14px 10px 10px 20px;
            }

            ul.navigation-items {
                margin-bottom: auto;
            }

            li.button_en {
                margin-left: 5px;
            }

            li.button_local_language {
                margin-left: 5px;
            }

            ul.navigation-items li.langswitcher ul {
                padding-left: 0px;
                padding-bottom: 8px;
                text-align: center;
            }

            .ULClass {
                margin-top: -5px;
            }

            .Choosecountry {
                display: block;
                margin: 0 auto;
                width: 276px;
            }

            .button-primary {
                width: 110%;
            }

            ul.intl-dropdown {
                width: 110%;
            }

            .page-hero a.copart-btn-closed {
                display: block;
                max-width: 380px;
                min-width: 320px;
            }
        }

        @media only screen and (max-width: 1090px) {
            .mobilelead1 {
                display: block;
                margin: 0 auto;
                width: 550px;
            }

            .mobilelead2 {
                display: block;
                margin: 0 auto;
                width: 280px;
            }
        }

        @media only screen and (max-width: 771px) {
            .mobile-remove1 {
                display: none;
            }
        }

        @media only screen and (max-width: 620px) {
            .mobilelead1 {
                width: auto;
                margin-bottom: 20px;
            }

            .Download-App {
                float: none;
                padding: 0px;
            }
        }

        @media only screen and (max-width: 446px) {
            ul.navigation-items li.button1-Translation a.authrep-btn {
                padding: 3px 10px 10px 20px;
            }
        }

        .desktopScreen {
            display: block;
        }

        .header-nav {
            min-height: 50px;
            background-color: #1d1e20;
            padding-left: 16px;
        }

        .header-nav {
            min-height: 50px;
            background-color: #1d1e20;
        }

        .header-nav .navbar {
            border-radius: 0;
            margin-bottom: 0;
            min-height: 32px;
        }

        .header-nav .navbar {
            border-radius: 0;
            margin-bottom: 0;
            min-height: 32px;
        }

        .header-nav .navbar-collapse {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        @media (min-width: 768px) {
            .navbar-collapse.collapse {
                display: block !important;
                height: auto !important;
                padding-bottom: 0;
                overflow: visible !important;
            }
        }

        .navbar-nav {
            float: left;
            margin: 0;
        }

        .nav > li {
            position: relative;
            display: block;
        }

        .logregdiv {
            float: right;
            display: inline-block;
            position: relative;
            font-size: 13px;
        }

        .header-nav .nav-right-block {
            background-color: #363a3f;
            margin: 0 20px;
            border-radius: 3px;
        }

        .clr {
            clear: both;
        }

        .mar0 {
            margin: 0 !important;
        }

        .mar0 {
            margin: 0 !important;
        }

        .mx-15 {
            margin-left: 15px;
            margin-right: 15px
        }

        .mt-0 {
            margin-top: 0
        }

        .ml-0 {
            margin-left: 0
        }

        .mb-0 {
            margin-bottom: 0
        }

        .mr-0 {
            margin-right: 0
        }

        .mt-5 {
            margin-top: 5px
        }

        .mr-5 {
            margin-right: 5px
        }

        .mb-5 {
            margin-bottom: 5px
        }

        .ml-5 {
            margin-left: 5px
        }

        .mt-10 {
            margin-top: 10px
        }

        .mr-10 {
            margin-right: 10px
        }

        .mb-10 {
            margin-bottom: 10px
        }

        .ml-10 {
            margin-left: 10px
        }

        .mt-15 {
            margin-top: 15px
        }

        .mr-15 {
            margin-right: 15px
        }

        .mb-15 {
            margin-bottom: 15px
        }

        .ml-15 {
            margin-left: 15px
        }

        .mt-20 {
            margin-top: 20px
        }

        .mb-20 {
            margin-bottom: 20px
        }

        .ml-20 {
            margin-left: 20px
        }

        .mt-25 {
            margin-top: 25px
        }

        .mr-25 {
            margin-right: 25px
        }

        .mb-25 {
            margin-bottom: 25px
        }

        .ml-30 {
            margin-left: 30px
        }

        .mt-40 {
            margin-top: 40px
        }

        .mb-40 {
            margin-bottom: 40px
        }

        .p-0 {
            padding: 0
        }

        .p-0-i {
            padding: 0 !important
        }

        .p-5 {
            padding: 5px
        }

        .p-10 {
            padding: 10px
        }

        .p-15 {
            padding: 15px
        }

        .px-0 {
            padding-left: 0;
            padding-right: 0
        }

        .px-5 {
            padding-left: 5px;
            padding-right: 5px
        }

        .px-10 {
            padding-left: 10px;
            padding-right: 10px
        }

        .px-20 {
            padding-left: 20px;
            padding-right: 20px
        }

        .px-30 {
            padding-left: 30px;
            padding-right: 30px
        }

        .py-0 {
            padding-top: 0;
            padding-bottom: 0
        }

        .py-5 {
            padding-top: 5px;
            padding-bottom: 5px
        }

        .py-10 {
            padding-top: 10px;
            padding-bottom: 10px
        }

        .py-20 {
            padding-top: 20px;
            padding-bottom: 20px
        }

        .py-30 {
            padding-top: 30px;
            padding-bottom: 30px
        }

        .pt-0 {
            padding-top: 0
        }

        .pl-0 {
            padding-left: 0
        }

        .pb-0 {
            padding-bottom: 0
        }

        .pr-0 {
            padding-right: 0
        }

        .pt-5 {
            padding-top: 5px
        }

        .pt-8 {
            padding-top: 8px
        }

        .pb-5 {
            padding-bottom: 5px
        }

        .pl-5 {
            padding-left: 5px
        }

        .pt-10 {
            padding-top: 10px
        }

        .pr-10 {
            padding-right: 10px
        }

        .pr-15 {
            padding-right: 15px
        }

        .pr-20 {
            padding-right: 20px
        }

        .pb-10 {
            padding-bottom: 10px
        }

        .pl-10 {
            padding-left: 10px
        }

        .pt-15 {
            padding-top: 15px
        }

        .pt-30 {
            padding-top: 30px
        }

        .pt-55 {
            padding-top: 55px
        }

        .pb-15 {
            padding-bottom: 15px
        }

        .pl-15 {
            padding-left: 15px
        }

        .pl-20 {
            padding-left: 20px
        }

        .pb-20 {
            padding-bottom: 20px
        }

        .pb-25 {
            padding-bottom: 25px
        }

        .pb-30 {
            padding-bottom: 30px
        }

        .pl-30 {
            padding-left: 30px
        }

        .fs12 {
            font-size: 12px
        }

        .fs14 {
            font-size: 14px
        }

        .fs15 {
            font-size: 15px
        }

        .fs16 {
            font-size: 16px
        }

        .fs17 {
            font-size: 17px
        }

        .fs22 {
            font-size: 22px
        }

        .fs28 {
            font-size: 28px
        }

        .displayNone {
            display: none !important
        }

        .displayBlock {
            display: block !important
        }

        .text-left {
            text-align: left
        }

        .text-right {
            text-align: right
        }

        .text-center {
            text-align: center
        }

        .pos-relative {
            position: relative
        }

        .pos-absolute {
            position: absolute
        }

        .font-lato-bold {
            font-family: 'Lato Bold'
        }

        .font-red-hat {
            font-family: "Red Hat Display", sans-serif
        }

        .font-red-hat-bold {
            font-family: "Red Hat Display Bold", sans-serif
        }

        .borderNone {
            border: none !important
        }

        .min-h-75 {
            min-height: 75px
        }

        .line-h-10 {
            line-height: 10px
        }

        .z-i-9999 {
            z-index: 9999
        }

        .z-i-999 {
            z-index: 999
        }

        .list-style-none {
            list-style: none
        }

        .user-select-none {
            -moz-user-select: none;
            -khtml-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none
        }

        .left {
            float: left
        }

        .nowrap {
            white-space: nowrap
        }

        .overflow-x-hidden {
            overflow-x: hidden
        }

        @media (max-width: 500px) {
            html {
                -webkit-text-size-adjust: none;
                -ms-text-size-adjust: none;
                touch-action: manipulation
            }
        }

        .list-unstyled {
            padding-left: 0;
            list-style: none
        }

        .inner-wrap .main-content {
            min-height: 10px
        }

        hr {
            margin-top: 5px;
            margin-bottom: 5px
        }

        .color-yellow {
            color: #ffb838
        }

        .white {
            color: #fff
        }</style>


    <style name="headerCSS">* {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box
        }

        :after, :before {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box
        }

        .inner-wrap .container, .inner-wrap .container-fluid {
            padding: 15px 0 15px 0
        }

        .ng-hide:not(.ng-hide-animate) {
            display: none !important
        }

        .fa {
            display: inline-block;
            font: normal normal normal 14px/1 FontAwesome;
            font-size: inherit;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale
        }

        .fa-lg {
            font-size: 1.33333333em;
            line-height: .75em;
            vertical-align: -15%
        }

        .fa-2x {
            font-size: 2em
        }

        .fa-times:before {
            content: "\f00d"
        }

        .fa-info-circle:before {
            content: "\f05a"
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0
        }

        header, nav {
            display: block
        }

        .header {
            color: #fff
        }

        .header a {
            color: #fff
        }

        .header a.btn {
            color: #fff
        }

        @media (max-width: 1139px) {
            .header .col-reg {
                font-size: 12px
            }
        }

        .header .langdiv {
            display: inline-block;
            float: right;
            position: relative
        }

        @media (max-width: 480px) {
            .header .langdiv {
                float: none;
                text-align: center
            }
        }

        .header .header-top {
            padding-top: 0;
            margin-top: 3px
        }

        .header .header-top select {
            color: #151317
        }

        @media (min-width: 1330px) {
            .header .header-top {
                float: right
            }
        }

        @media (max-width: 1329px) {
            .header .header-top {
                margin-top: 0;
                text-align: left;
                float: right
            }
        }

        @media (max-width: 970px) {
            .header .header-top {
                font-size: 12px;
                margin-top: 0;
                margin-bottom: 3px
            }
        }

        @media (max-width: 768px) {
            .header .header-top {
                font-size: 12px;
                margin-left: 10px
            }
        }

        @media (max-width: 609px) {
            .header .header-top {
                text-align: center;
                width: 100%
            }
        }

        .header .header-top .list-options {
            padding-top: 0;
            padding-bottom: 0
        }

        @media (max-width: 650px) {
            .header .header-top .list-options {
                padding-top: 0
            }
        }

        @media (max-width: 650px) {
            .header .header-top .list-options li {
                display: inline-block;
                border-left: none;
                padding: 0;
                margin: 3px 0
            }

            .header .header-top .list-options:first-child {
                padding-left: 0;
                margin-left: 0
            }
        }

        @media (max-width: 990px) {
            .header .header-top .list-options {
                margin-left: 3px;
                padding-top: 0;
                display: inline-block
            }
        }

        .header .header-top label {
            font-weight: 400;
            margin-bottom: 0
        }

        .header .header-top select {
            background-color: transparent;
            border: none
        }

        .header .header-top select:focus, .header .header-top select:hover {
            outline: 0;
            border: none
        }

        .header .header-mid {
            background-image: -webkit-gradient(linear, left top, right top, from(#1d1e20), to(#043bc7));
            background-image: -o-linear-gradient(left, #1d1e20, #043bc7);
            background-image: linear-gradient(to right, #1d1e20, #043bc7)
        }

        .crashedtoys-site .header .header-mid {
            padding: 1px 0;
            background: 0 0;
            background-color: #b92020
        }

        .header .header-mid .btn-lightblue {
            background-color: #ffb838;
            color: #151317;
            font-size: 14px;
            margin-left: 15px;
            height: 38px;
            border: none;
            box-shadow: none;
            margin-bottom: 0;
            padding: 9px 36px;
            font-weight: 600
        }

        .header .header-mid .btn-lightblue:hover {
            background-color: #ffc356;
            transition: .3s ease all
        }

        .header .header-mid .search-btn {
            padding: 9px 17px
        }

        .header .header-mid .btn-register, .header .header-mid .btn-sign-in, .header .header-mid .btn-sign-out {
            font-size: 14px;
            margin-left: 15px;
            border: none;
            box-shadow: none;
            margin-bottom: 0;
            padding: 8px 12px;
            font-weight: 600
        }

        .header .header-mid .btn-register {
            background-color: #ffb838;
            color: #151317
        }

        .header .header-mid .btn-register:hover {
            background-color: #ffc356;
            transition: .3s ease all
        }

        .header .header-mid .btn-mob-register {
            background-color: #1e78ff;
            color: #fff;
            font-size: 14px;
            margin-left: 10px;
            border: none;
            box-shadow: none;
            margin-bottom: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            font-family: 'Lato 300'
        }

        .header .header-mid .btn-mob-register:hover {
            background-color: #408cff;
            transition: .3s ease all
        }

        .header .header-mid .btn-sign-in, .header .header-mid .btn-sign-out {
            background-color: #1e78ff;
            color: #fff;
            padding: 8px 20px
        }

        .header .header-mid .btn-sign-in:hover, .header .header-mid .btn-sign-out:hover {
            background-color: #408cff;
            transition: .3s ease all
        }

        .header .header-mid .dropdown-menu.signin .btn {
            padding: 3px 6px;
            font-size: 12px;
            position: relative;
            color: #fff
        }

        .header .header-mid .btn-lightblue:active, .header .header-mid .btn-lightblue:focus, .header .header-mid .btn-lightblue:hover {
            box-shadow: none
        }

        .header .header-mid .logodiv {
            display: inline-block;
            position: relative;
            padding-top: 0
        }

        .header .header-mid .searchdiv {
            display: inline-block;
            margin-right: 0;
            margin-left: 0;
            margin-top: 18px
        }

        .header .header-mid .logo {
            text-align: left
        }

        .header .header-mid .logo img {
            margin: 20px 0 20px 0
        }

        .header .header-mid .afterlogin .logo img {
            margin: 20px 0 5px 0
        }

        .header .header-mid .beforelogin .logo img {
            margin: 20px 0 20px 0
        }

        .header .header-mid .beforelogin .portal-info {
            display: none
        }

        .crashedtoys-site .header .header-mid .beforelogin .logo img {
            margin: 20px 20px 20px 0;
            height: 53px
        }

        .header .leftheader {
            display: inline-block;
            float: left
        }

        .header .col-reg {
            display: inline-block;
            float: right
        }

        .dd-white {
            border-radius: 0;
            background-color: #363a3f;
            border: none;
            box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .5)
        }

        .dd-white li {
            color: #151317;
            -webkit-transition: height .2s ease-in;
            -moz-transition: height .2s ease-in;
            -o-transition: height .2s ease-in;
            transition: height .2s ease-in
        }

        .dd-white li a {
            color: #151317
        }

        .dd-white li a:focus, .dd-white li a:hover {
            background-color: #1254ff;
            color: #fff
        }

        .header-nav .navbar {
            border-radius: 0;
            margin-bottom: 0;
            min-height: 32px
        }

        .header-nav .navbar-nav > li > a {
            padding-top: 6px;
            padding-bottom: 6px;
            color: #fff;
            font-size: 13px;
            border: 1px solid #151317
        }

        .header-nav .nav > li > a {
            padding: 15px 10px;
            font-weight: 400;
            font-family: 'Lato 300';
            font-weight: 600;
            letter-spacing: .3px
        }

        @media (max-width: 767px) {
            .header-nav .nav > li > a {
                padding-left: 8px
            }
        }

        .header-nav .nav > li > a:focus, .header-nav .nav > li > a:hover {
            background-color: #1254ff;
            border: 0;
            text-decoration: none
        }

        .header-nav .navbar-nav > li > a {
            border: 0
        }

        .header-nav .navbar-nav > li > a:focus, .header-nav .navbar-nav > li > a:hover {
            border: 0
        }

        .navbar-toggle {
            float: left;
            margin-right: 0;
            margin: 3px;
            padding: 3px
        }

        .dropdown-menu {
            margin: 0;
            padding: 0;
            font-size: 13px;
            left: 0;
            min-width: 130px;
            z-index: 1100
        }

        .dropdown-menu li a {
            padding: 12px 15px;
            background-color: #1d1e20;
            color: #fff;
            font-family: 'Lato 300';
            font-weight: 600;
            letter-spacing: .3px
        }

        .dropdown-menu .separator-list {
            background: #1d1e20;
            display: flex;
            justify-content: center
        }

        .dropdown-menu .separator-list .header-menu-separator {
            width: 85%;
            border-top: 2px solid #363a3f
        }

        @media (max-width: 767px) {
            .dropdown-menu li a {
                background: 0 0
            }
        }

        @media (max-width: 479px) {
            .dropdown-menu li a {
                color: #151317
            }
        }

        .sign-in-btn {
            right: 0;
            left: auto;
            padding: 10px 10px 5px 10px !important;
            margin-top: 5px
        }

        .header-nav .dropdown-submenu {
            position: relative
        }

        @media (min-width: 979px) {
            .header-nav ul.nav li.dropdown:focus > ul.dropdown-menu {
                display: block;
                margin-top: 0
            }
        }

        .modal-header {
            border-bottom: 1px solid #ddd
        }

        .modal-header .close {
            background-color: #8e908f;
            color: #fff;
            width: 25px;
            height: 25px;
            border-radius: 20px;
            opacity: 1;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: baseline;
            -ms-flex-align: baseline;
            align-items: baseline;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center
        }

        .top10 {
            margin-top: 15px !important
        }

        .navbar-collapse {
            box-shadow: none !important
        }

        .ng-hide {
            display: none
        }

        .right {
            float: right
        }

        .pad0 {
            padding: 0
        }

        .mar0 {
            margin: 0 !important
        }

        label {
            font-weight: 400
        }

        .modal-header {
            background-color: #ddd;
            color: #151317
        }

        .pad0 {
            padding: 0
        }

        .input-group-addon {
            padding: 4px 12px
        }

        .right {
            float: right
        }

        .pad10 {
            padding: 10px
        }

        .padtb20 {
            padding-top: 20px;
            padding-bottom: 20px
        }

        .header .header-mid .btn.btn-inline {
            float: none;
            display: inline-block !important;
            margin: 3px !important;
            padding: .3em .5em !important
        }

        .header .header-mid .btn.btn-dgray {
            background-color: #f4f4f4 !important;
            border: solid 1px #575758;
            color: #151317
        }

        .header .header-mid .btn.btn-dgray:focus, .header .header-mid .btn.btn-dgray:hover {
            background-color: #999;
            color: #575758;
            border: solid 1px #575758
        }

        h4 {
            font-weight: 600 !important
        }

        @media (max-width: 320px) {
            .menu-toggle-title {
                display: none
            }
        }

        .mobileShow {
            display: none !important
        }

        .desktopView {
            display: block
        }

        .navbtnclose-mobile {
            display: none
        }

        @media (min-width: 1250px) {
            .header-nav .nav > li > a {
                font-size: 14px
            }

            .navbar-collapse {
                padding-left: 0
            }

            .navbar-nav > li > .dropdown-menu {
                min-width: 180px;
                left: 0
            }
        }

        @media (min-width: 1200px) {
            .logodiv.col-lg-3 {
                width: 22%
            }

            .header .col-reg {
                padding: 5px !important
            }
        }

        @media (min-width: 1024px) and (max-width: 1279px) {
            .header .langdiv {
                float: right;
                text-align: right;
                font-size: 14px
            }

            .list-options {
                padding-top: 8px
            }

            .navbar-collapse {
                padding-left: 0
            }
        }

        @media (min-width: 768px) and (max-width: 1023px) {
            .dd-white {
                padding: 0
            }

            .navbar-collapse {
                padding-right: 0 !important;
                padding-left: 0 !important
            }

            .header .col-reg {
                width: 100%;
                text-align: center;
                float: none;
                padding: 0;
                margin-left: 0;
                margin-right: 0
            }

            .header .leftheader {
                width: 100%
            }

            .header .header-top .list-options {
                float: left;
                display: flex
            }

            .header .langdiv {
                float: none !important
            }
        }

        @media (max-width: 1024px) {
            .header .langdiv {
                margin-top: 0
            }

            .header .header-top .list-options {
                margin-right: 0
            }

            .header .col-reg {
                padding-right: 0 !important
            }
        }

        @media (max-width: 1023px) {
            .header .langdiv {
                float: none !important
            }

            .header .header-top .list-options {
                margin-right: 0
            }
        }

        @media (min-width: 992px) {
            .header .header-mid .logodiv.pad0 {
                padding-left: 0 !important;
                padding-right: 15px !important
            }
        }

        @media (max-width: 900px) {
            .header-nav .navbar-nav > li > a {
                font-size: 12px
            }

            .navbar-nav {
                margin-left: 3px;
                margin-right: 3px
            }

            .navbar-nav > li > .dropdown-menu {
                left: auto !important;
                right: 0 !important
            }
        }

        @media (max-width: 991px) {
            .navbar-nav {
                margin: 0;
                width: 100%
            }

            .header .col-reg {
                text-align: center;
                float: none;
                display: inline-block
            }

            .header .leftheader {
                width: 100%
            }

            .header .header-mid .logodiv {
                text-align: left;
                margin: 8px 0 0 0
            }

            .header-nav .navbar-nav > li > a {
                padding: 5px 8px
            }

            .navbar-nav li.active {
                background-color: #2f2f2f !important;
                border: 1px solid #151317;
                color: #fff
            }

            .navbar-nav li {
                background-color: #151317 !important;
                border-bottom: 1px solid #151317
            }

            .navbar {
                min-height: 0
            }

            .mainmenutogglediv .mainmenubtn {
                margin: 0 10px;
                font-size: 16px
            }

            #mobile-header-nav-links {
                min-width: 360px;
                padding-left: 0 !important;
                padding-right: 0
            }

            #mobile-header-nav-links .navbar-nav li a {
                padding: 8px 10px 8px 30px;
                font-size: 14px
            }

            .header-nav .navbar {
                position: relative;
                overflow: visible !important
            }

            .mainmenutogglediv {
                position: static !important;
                cursor: pointer
            }

            .mobile-nav.collapse, .mobile-nav.collapsing {
                display: none;
                position: absolute;
                top: -60px;
                right: -105vw;
                width: 100vw;
                height: 860px !important;
                -webkit-transition: right .2s;
                -moz-transition: right .2s;
                -o-transition: right .2s;
                transition: right .2s;
                z-index: 9999
            }

            .mobile-nav.collapsing {
                display: block
            }

            .mobile-nav.collapse.in {
                display: block;
                -webkit-appearance: none;
                color: transparent !important;
                z-index: 1099;
                position: absolute;
                left: auto;
                right: 0;
                top: -60px;
                padding: 0;
                background-color: #212020;
                box-shadow: 1px 1px 15px #151317 !important;
                width: 100vw;
                -webkit-transition: right .2s;
                -moz-transition: right .2s;
                -o-transition: right .2s;
                transition: right .2s;
                height: 900px
            }

            #mobile-header-nav-links .navbar-nav li {
                border: 0 !important
            }

            #mobile-header-nav-links .navbar-nav li a {
                font-size: 18px;
                padding: 10px;
                font-weight: 400;
                border-top: 1px dotted #696666
            }

            #mobile-header-nav-links .navbar-nav li a:focus, #mobile-header-nav-links .navbar-nav li a:hover {
                background-color: #1254ff !important;
                color: #fff !important
            }

            #mobile-header-nav-links .navbar-nav li ul li {
                border: 0 !important
            }

            #mobile-header-nav-links .navbar-nav li ul li a {
                font-size: 16px;
                padding: 10px 10px 10px 20px
            }

            #mobile-header-nav-links {
                overflow-y: visible;
                background-color: #151317
            }

            .navbtnclose-mobile {
                z-index: 3000
            }

            #mobile-header-nav-links .caret {
                display: inline-block;
                width: 0;
                height: 0;
                margin-left: 2px;
                vertical-align: middle;
                border-top: 6px dashed;
                border-top: 6px solid;
                border-right: 6px solid transparent;
                border-left: 6px solid transparent;
                float: right;
                margin-top: 8px
            }

            .navbtnclose-mobile {
                position: absolute;
                display: block;
                right: 6px;
                top: 7px;
                font-size: 21px;
                color: #fff
            }

            .navbar-collapse {
                padding-right: 15px !important;
                padding-left: 15px !important
            }
        }

        @media screen and (max-width: 519px) {
            .header .header-mid .logodiv {
                text-align: left;
                width: 100%
            }

            .header .header-mid .searchdiv {
                width: 100%;
                text-align: center
            }

            .header .header-mid .logo {
                text-align: center;
                margin: 10px 0 0
            }
        }

        @media screen and (max-width: 479px) {
            .header .header-mid .logodiv {
                text-align: left
            }

            .header .header-mid .logo {
                text-align: center;
                padding-left: 5px
            }

            .mobileShow {
                display: block !important
            }

            .mobileShow .navbar-collapse {
                padding-left: 0 !important;
                padding-right: 0 !important
            }

            .signinmenu {
                display: inline-block;
                float: right
            }

            .togglemainheader {
                display: block
            }

            .desktopView {
                display: none !important
            }

            .mobilepad20 {
                padding-top: 8px !important;
                padding-bottom: 8px !important
            }
        }

        @media screen and (max-width: 479px) {
            :after, :before {
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box
            }

            .form-control {
                height: 28px;
                padding: 1px 3px
            }

            .form-control {
                font-size: 10px
            }

            .mainmenudiv.collapse {
                width: 85%
            }
        }

        @media (min-width: 200px) and (max-width: 479px) {
            .mainmenudiv.collapse {
                width: 90%
            }
        }

        @media print {
            *, :after, :before {
                color: #151317 !important;
                text-shadow: none !important;
                background: 0 0 !important;
                -webkit-box-shadow: none !important;
                box-shadow: none !important
            }

            a, a:visited {
                text-decoration: underline
            }

            a[href]:after {
                content: "(" attr(href) ")"
            }

            a[href^="javascript:"]:after {
                content: ""
            }

            img {
                page-break-inside: avoid
            }

            img {
                max-width: 100% !important
            }

            p {
                orphans: 3;
                widows: 3
            }

            select {
                background: #fff !important
            }

            .navbar {
                display: none
            }

            h4 {
                font-size: 12px !important;
                margin: 0;
                padding: 0
            }

            .header-top, .langdiv {
                display: none
            }

            .col-sm-12, .col-sm-3, .col-sm-5, .col-sm-6, .col-sm-9 {
                float: left
            }

            .col-sm-12 {
                width: 100%
            }

            .col-sm-9 {
                width: 75%
            }

            .col-sm-6 {
                width: 50%
            }

            .col-sm-5 {
                width: 41.66666667%
            }

            .col-sm-3 {
                width: 25%
            }

            .langdiv {
                display: none !important
            }

            a:after {
                content: ''
            }

            a[href]:after {
                content: none !important
            }

            a:link:after, a:visited:after {
                display: none;
                content: ""
            }

            a[href]:after {
                content: none !important
            }

            img[src]:after {
                content: none !important
            }

            .btn {
                border: 1px solid #1254ff;
                color: #1254ff;
                border-radius: 4px;
                -webkit-border-radius: 4px;
                -moz-border-radius: 4px
            }

            .modal * {
                visibility: hidden
            }
        }

        .displayNone {
            display: none
        }

        .input-group-addon {
            border: 1px solid #999 !important;
            border-left: 0 !important
        }

        .cookie-policymsg {
            background-color: #fff;
            color: #151317;
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 4px;
            padding: 5px 0 5px 0;
            margin: 0 auto;
            overflow: hidden
        }

        .cookie-policymsg-icon {
            color: #4d7fff;
            text-align: right
        }

        @media only screen and (max-width: 475px) {
            .cookie-policymsg span {
                padding: 0
            }

            .cookie-policymsg {
                margin-top: -10px;
                margin-bottom: 5px
            }

            .cookie-continue-btn .btn {
                padding: 3px 0 !important
            }
        }

        @media print {
            .modal {
                position: absolute;
                left: 0;
                top: 0;
                margin: 0;
                padding: 0;
                overflow: visible !important
            }
        }

        .fontW600 {
            font-weight: 600
        }

        .logregdiv {
            float: right;
            display: inline-block;
            position: relative;
            font-size: 13px
        }

        @media (max-width: 992px) {
            .logregdiv {
                font-size: 12px
            }
        }

        @media (max-width: 900px) and (min-width: 760px) {
            .header-nav .navbar-nav > li > a {
                font-size: 11px
            }
        }

        .pad10 {
            padding: 10px
        }

        .newsearch {
            box-shadow: none;
            background-color: #fff !important;
            height: 38px;
            padding: 2px 5px;
            border-left-top-radius: 0 !important;
            border-left-bottom-radius: 0 !important;
            border-top-right-radius: 4px !important;
            border-bottom-right-radius: 4px !important;
            border: 2px solid #c0c8d1;
            border-left: 0;
            padding-top: 0;
            border-radius: 0;
            margin-left: -1px
        }

        .newsearch::placeholder {
            font-size: 13px;
            color: #a3adb8
        }

        .newsearch:-ms-input-placeholder {
            font-size: 12px;
            color: #a3adb8
        }

        .newsearch::-ms-input-placeholder {
            font-size: 12px;
            color: #a3adb8
        }

        .newsearch:focus {
            border-color: #c0c8d1;
            outline: 0;
            -webkit-box-shadow: none;
            box-shadow: none
        }

        .header_lang_dropdown, .header_user_dropdown, .timezoneformat {
            text-align: left
        }

        @media (max-width: 1279px) and (min-width: 1024px) {
            .navbar-collapse {
                padding-left: 15px !important
            }
        }

        @media (max-width: 900px) and (min-width: 760px) {
            .header-nav .navbar-nav > li > a {
                font-size: 11px !important
            }
        }

        @media screen and (max-width: 468px) {
            .mobiletopheader .logo img {
                margin: 0 !important
            }
        }

        @media screen and (max-width: 468px) {
            .mobileflagdrop {
                display: inline-block
            }
        }

        @media screen and (max-width: 468px) {
            .topheadericons {
                margin-top: 15px;
                margin-bottom: 30px
            }

            .topheadericons .navbar-toggle {
                margin: -1px;
                padding: 0
            }
        }

        @media screen and (max-width: 321px) {
            .topheadericons {
                margin-top: 15px;
                margin-bottom: 15px
            }
        }

        .mobilesearchicon {
            vertical-align: top;
            margin-top: 3px
        }

        @media screen and (max-width: 1024px) {
            .searchinputgroup {
                margin-left: 0
            }
        }

        .header .header-mid .searchdiv {
            margin-top: 27px
        }

        .header .header-mid .searchinputgroup .btn {
            padding: 5px 10px 0 7px;
            border: 2px solid #c0c8d1;
            border-right: 0;
            margin: 0;
            background-color: #fff;
            font-size: 14px;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            min-height: 38px;
            box-shadow: none;
            min-height: 38px
        }

        .header .langdiv {
            margin-top: 20px;
            font-size: 14px
        }

        @media screen and (max-width: 1023px) {
            .header .langdiv {
                font-size: 13px;
                margin: 0 auto !important;
                text-align: center
            }
        }

        .header .pipe {
            color: #337ed4
        }

        .btnlanguage {
            padding-right: 0
        }

        @media screen and (max-width: 479px) {
            .mobilepad20 {
                padding-top: 8px !important;
                padding-bottom: 8px !important
            }
        }

        .marginleft15 {
            margin-left: 15px
        }

        @media screen and (max-width: 479px) {
            .header .header-mid .logo img {
                margin: 10px 20px 0 0
            }
        }

        @media (max-width: 991px) {
            #public-user-modal .mobile-signin-block {
                margin-top: 63px;
                max-width: 55%;
                margin-left: 42%;
                background-color: #9f9f9f
            }

            .mobile-signin-block .modal-body {
                padding: 10px
            }

            .mobile-signin-block .mobile-header-menu {
                margin-bottom: 5px
            }

            .mobile-signin-block .mobile-header-menu:last-child {
                margin-bottom: 0
            }

            .mobile-signin-block .menu_click {
                text-align: center;
                background-color: #1254ff !important;
                border-radius: 3px;
                padding: 3px 10px;
                color: #fff;
                border: none
            }
        }

        .header .dropdown.active .menu_click.active {
            color: #fff !important;
            background-color: #1254ff !important
        }

        .mobile-needHelp {
            display: none
        }

        @media screen and (max-width: 767px) {
            .mobile-needHelp {
                display: block
            }
        }

        .searchdiv ul.dropdown-menu {
            left: 0 !important;
            width: 100%
        }

        .mobilesearchicon .search-icon-btn {
            padding: 6px 9px 0 9px;
            border: none;
            background-color: #ffb838
        }

        .mobilesearchicon .search-icon-btn:active, .mobilesearchicon .search-icon-btn:focus, .mobilesearchicon .search-icon-btn:hover {
            background-color: #ffb838
        }

        .mobilesearchicon .my_textarea {
            padding: 20px 3px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid #ccc
        }

        .mobilesearchicon .my_textarea::-webkit-input-placeholder {
            font-size: 14px;
            font-weight: 400;
            padding-left: 5px;
            color: #151317
        }

        .mobilesearchicon .my_textarea::-moz-placeholder {
            font-size: 14px;
            font-weight: 400;
            padding-left: 5px;
            color: #151317
        }

        .mobilesearchicon .my_textarea:-ms-input-placeholder {
            font-size: 14px;
            font-weight: 400;
            padding-left: 5px;
            color: #151317
        }

        .mobilesearchicon .my_textarea::-ms-input-placeholder {
            font-size: 14px;
            font-weight: 400;
            padding-left: 5px;
            color: #151317
        }

        .mobilesearchicon .my_textarea::placeholder {
            font-size: 14px;
            font-weight: 400;
            padding-left: 5px;
            color: #151317
        }

        .mobilesearchicon ul.dropdown-menu {
            width: 100%
        }

        .mob-search-block {
            background: #1d1e20;
            padding: 8px;
            min-height: 60px
        }

        @media screen and (max-width: 321px) {
            .mobiletopheader .mob-search-block {
                padding-left: 10px
            }

            .mobiletopheader .mob-serach-btn {
                padding-left: 10px
            }

            #mobile-header-nav-links {
                min-width: 300px
            }

            #mobile-header-nav-links .navbar-nav .menu_click {
                white-space: normal
            }
        }

        @media (max-width: 991px) {
            #mobile-header-nav-links {
                height: auto !important
            }
        }

        .navbar-nav .active > a {
            background-color: #1254ff !important;
            border-right: 1px solid #151317;
            border-radius: 1px
        }

        .navbar-nav .active .navbtnclose-mobile {
            color: #fff
        }

        .desktop-only {
            display: block
        }

        @media (max-width: 768px) {
            .desktop-only {
                display: none
            }
        }

        .w100 {
            width: 100%
        }

        .opacity1 {
            opacity: 1
        }

        .signinmenu {
            margin-top: 2px
        }

        @media (max-width: 991px) {
            #mobile-header-nav-links {
                background-color: #363a3f
            }

            #mobile-header-nav-links .navbar-nav li a {
                border-top: 1px dotted #39444e
            }

            #mobile-header-nav-links .navbar-nav li a:focus, #mobile-header-nav-links .navbar-nav li a:hover {
                background-color: #1254ff !important;
                color: #fff !important
            }

            .navbar-nav li {
                background-color: #151317 !important
            }

            .navbar-nav li.active {
                background-color: #1254ff !important
            }

            .header .header-nav {
                line-height: 40px
            }

            .menu-toggle-title {
                font-weight: 600;
                margin-left: 8px
            }
        }

        .px-5 {
            padding-left: 5px;
            padding-right: 5px
        }

        .d-f {
            display: flex
        }

        .cursor-pointer {
            cursor: pointer
        }

        .text-left {
            text-align: left
        }

        @media (min-width: 760px) and (max-width: 900px) {
            .header-nav .navbar-nav > li > a {
                font-size: 11.5px
            }
        }

        @media (min-width: 0px) and (max-width: 479px) {
            .header .header-mid .searchdiv {
                margin-top: 0
            }

            .header .col-reg {
                padding-left: 0;
                padding-right: 0
            }

            .header .header-top {
                margin-left: 0
            }

            .language_popup button {
                padding-left: 2px;
                padding-right: 2px
            }

            .header .header-mid .btn {
                margin-bottom: 0
            }

            .header .header-top .list-options li {
                margin-top: 0;
                margin-bottom: 0
            }
        }

        @media (max-width: 650px) {
            .header .header-top .list-options li {
                display: inherit;
                border-left: 0
            }
        }

        .modal-header {
            background-color: #fff
        }

        .padLeft0 {
            padding-left: 0 !important
        }

        .overflowHidden {
            overflow: hidden
        }

        @media (max-width: 991px) {
            .header .header-top .list-options li {
                display: -webkit-inline-box !important
            }
        }

        .timezoneformat {
            box-shadow: 1px 1px 35px #000;
            background: #f9f9f9;
            padding: 15px;
            width: auto;
            float: right
        }

        .timezoneformat .settime_div {
            margin-top: 12px
        }

        .settimecolor {
            color: #000;
            font-size: 18px
        }

        .settimefont {
            font-size: 16px
        }

        .timezoneborders {
            border: 1px solid #dedede;
            background: #fff !important
        }

        .timepad_div {
            margin-top: 20px
        }

        .showtime_div {
            float: left
        }

        .timepad {
            color: #000;
            padding-top: 10px;
            font-size: 16px
        }

        .timepickheight {
            height: 79px
        }

        .zonepicker {
            background: #dedede;
            height: 79px;
            font-size: 25px
        }

        .header_lang_dropdown {
            background: #f9f9f9;
            padding: 15px;
            float: right;
            position: absolute;
            z-index: 9999;
            top: 49px;
            min-width: 300px;
            right: 183px;
            border-radius: 6px;
            box-shadow: 0 1px 5px 0 rgb(0, 0, 0, .32)
        }

        @media (max-width: 1024px) {
            .header_lang_dropdown {
                left: -136px
            }
        }

        .header_user_dropdown {
            background: #f9f9f9;
            padding: 15px;
            float: right;
            position: absolute;
            z-index: 9999;
            top: 42px;
            min-width: 200px;
            left: auto;
            border-radius: 6px;
            box-shadow: 0 1px 5px 0 rgb(0, 0, 0, .32);
            right: 0
        }

        .header_user_dropdown .logged-in-user-link {
            color: #151317;
            font-size: 14px;
            font-weight: 600
        }

        .header_int_buyer_dropdown {
            background: #f9f9f9;
            padding: 15px;
            position: absolute;
            z-index: 9999;
            min-width: 300px;
            border-radius: 6px;
            top: 43px;
            right: 164px;
            box-shadow: 0 1px 5px 0 rgb(0, 0, 0, .32)
        }

        .dropdown-arrow-mark:before {
            content: '';
            display: block;
            position: absolute;
            top: 43px;
            left: 28px;
            width: 0;
            height: 0;
            border: 10px solid transparent;
            border-bottom-color: rgba(0, 0, 0, .25);
            margin-top: -20px;
            margin-right: 38px
        }

        .dropdown-arrow-mark:after {
            content: '';
            display: block;
            position: absolute;
            left: 28px;
            top: 43px;
            width: 1px;
            height: 1px;
            border: 10px solid transparent;
            border-bottom-color: #fff;
            margin-top: -19px;
            margin-right: 38px;
            z-index: 99999
        }

        .header_lang_dropdown:before, .header_user_dropdown:before {
            content: '';
            display: block;
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 0;
            border: 10px solid transparent;
            border-bottom-color: rgba(0, 0, 0, .25);
            margin-top: -20px;
            margin-right: 38px
        }

        .header_lang_dropdown:after, .header_user_dropdown:after {
            content: '';
            display: block;
            position: absolute;
            right: 0;
            top: 0;
            width: 1px;
            height: 1px;
            border: 10px solid transparent;
            border-bottom-color: #fff;
            margin-top: -19px;
            margin-right: 38px
        }

        @media (max-width: 1024px) {
            .header_lang_dropdown {
                left: -136px
            }

            .header_lang_dropdown:after, .header_lang_dropdown:before {
                left: 140px
            }
        }

        .header_user_dropdown:after, .header_user_dropdown:before {
            right: -14px
        }

        .lang_section .description-text, .region_section .description-text {
            font-size: 12px;
            line-height: 18px;
            font-family: 'Red Hat Display 500';
            color: #000;
            text-align: center
        }

        .can-you-read {
            color: #1254ff;
            font-weight: 600;
            font-size: 13px;
            font-family: 'Red Hat Display 500';
            text-align: center
        }

        .lang_region_selection_block:after {
            content: url(/images/icons/union.svg);
            font-size: 10px;
            color: #151317;
            right: 5px;
            top: 12px;
            padding: 0 0 2px;
            position: absolute;
            pointer-events: none;
            background: #f9f9f9;
            width: 15px
        }

        .langDropdown_select {
            height: auto;
            border: 1px solid #dfdfdf !important;
            background: #fff;
            padding: 8px 10px;
            font-size: 17px;
            font-family: 'Red Hat Display 500';
            width: 170px
        }

        .langDropdown_label {
            font-family: 'Red Hat Display 700';
            font-size: 19px;
            margin-bottom: 10px
        }

        .region_label {
            font-family: 'Red Hat Display 700';
            font-size: 19px;
            margin-bottom: 10px
        }

        .region_select {
            height: auto;
            border: 1px solid #dfdfdf !important;
            background: #fff;
            padding: 8px 10px;
            font-size: 17px;
            font-family: 'Red Hat Display 500'
        }

        .header .header_dropdown_int_buyers_link {
            font-size: 13px;
            text-align: center;
            color: #1254ff;
            text-decoration: none
        }

        .region_section .copart_country_list {
            -webkit-appearance: none;
            position: absolute;
            width: 275px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid rgb(20, 18, 22, .5);
            border-radius: 3px;
            left: -2px
        }

        .copart_countries .toggle-country-dropdown:not(checked) ~ .copart_country_list, .copart_countries .toggle-int-user-dropdown:not(checked) ~ .copart_country_list {
            display: none
        }

        .copart_countries .toggle-country-dropdown:checked ~ .copart_country_list, .copart_countries .toggle-int-user-dropdown:checked ~ .copart_country_list {
            display: block;
            display: flex
        }

        .region_section .copart_country_items {
            font-size: 14px;
            font-family: 'Red Hat Display 500';
            color: #151317;
            min-width: 125px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        @media (max-width: 425px) {
            .region_section .copart_country_items img {
                height: 20px
            }
        }

        .region_section .copart_country_items img {
            height: 22px;
            width: 28px;
            margin: auto 0;
            margin-right: 8px
        }

        .region_section .copart_country_items a {
            color: #151317;
            text-decoration: none;
            flex-grow: 1;
            padding: 10px 5px
        }

        .region_section .copart_country_list a:hover {
            background: #ddd
        }

        .header_int_buyer_dropdown .custom_meta_dropdown .copart_country_list {
            max-height: 220px;
            overflow: hidden;
            overflow-y: scroll
        }

        .header_lang_dropdown .region_section .copart_country_items a {
            background-color: #f9f9f9
        }

        .header_lang_dropdown .region_section .custom_meta_dropdown .region_selected_block {
            background-color: #f9f9f9
        }

        .region_section .custom_meta_dropdown .region_selected_block {
            padding: 9px;
            border-radius: 3px;
            border: solid 1px #dfdfdf;
            cursor: pointer;
            width: 100%
        }

        .region_section .custom_meta_dropdown .region_selected_block:after {
            content: url(/images/icons/union.svg);
            font-size: 10px;
            color: #151317;
            right: 5px;
            top: 12px;
            padding: 0 0 2px;
            position: absolute;
            pointer-events: none;
            background: #f9f9f9;
            width: 15px
        }

        .yes-btn {
            width: 71px;
            padding: 11px;
            margin-left: 15px;
            border-radius: 3px;
            background-color: #ffb838;
            border: none;
            font-size: 15px;
            font-family: 'Red Hat Display 500';
            font-weight: 600;
            color: #151317
        }

        .get-started-btn {
            width: 100%;
            border: none;
            color: #151317;
            background-color: #ffb838;
            font-size: 14px;
            font-family: 'Red Hat Display 700';
            padding: 10px;
            border-radius: 3px
        }

        .btnlanguage {
            background: 0 0;
            border: none
        }

        .maintimezone_div {
            background: #eee;
            padding: 0;
            float: right;
            position: absolute;
            z-index: 9999;
            top: 28px;
            min-width: 300px;
            right: 0
        }

        .pagetextcolor {
            color: #000
        }

        .langsel {
            padding: 0;
            margin-top: 5px
        }

        .langdropdown_div {
            padding: 0;
            border: 1px solid #dedede
        }

        .txtfnt {
            font-size: 14px
        }

        .glyiconremove {
            float: right;
            color: gray !important
        }

        #timezonehome {
            display: none
        }

        a {
            cursor: pointer
        }

        input[type=text]::-ms-clear {
            width: 0;
            height: 0
        }

        input[type=text]::-ms-reveal {
            width: 0;
            height: 0
        }

        .padleft10 {
            padding-left: 10px
        }

        .marginleft15 {
            margin-left: 15px
        }

        .d-f {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .a-i_c {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center
        }

        .j-c_s-b {
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between
        }

        h4 {
            font-family: "Red Hat Display", sans-serif
        }

        .d-f {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .a-i_c {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center
        }

        .j-c_s-b {
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between
        }

        .px-5 {
            padding-left: 5px;
            padding-right: 5px
        }

        .displayNone {
            display: none
        }

        .header-nav {
            min-height: 50px;
            background-color: #1d1e20;
            padding-left: 16px
        }

        .header-nav .nav-right-block {
            background-color: #363a3f;
            margin: 0 20px;
            border-radius: 3px
        }

        .header-nav .nav-right-block .edit-btn {
            background: #727780;
            border: none;
            padding: 7px 12px;
            margin-left: 10px;
            border-radius: 0 3px 3px 0
        }

        .header-nav .navbar-collapse {
            padding-left: 0 !important;
            padding-right: 0 !important
        }

        .header-sprite {
            background-image: url(/images/icons/Header_icons.svg);
            background-repeat: no-repeat;
            display: inline-block
        }

        .header-sprite.hamburger-icon {
            background-position: -130px 1px;
            width: 26px;
            height: 24px;
            margin-left: 5px
        }

        .header-sprite.arrow-icon {
            background-position: -48px -1px;
            width: 10px;
            height: 24px;
            margin-left: 5px
        }

        .header-sprite.user-icon {
            background-position: -98px 3px;
            width: 26px;
            height: 29px
        }

        .header-sprite.mob-search-icon {
            background-position: -152px 2px;
            width: 26px;
            height: 32px
        }

        .header-sprite.globe-icon {
            background-position: 0 0;
            width: 26px;
            height: 26px
        }

        .header-sprite.search-icon {
            background-position: -234px 3px;
            width: 22px;
            height: 22px
        }

        .header-sprite.location-arrow-yellow {
            background-position: -74px 0;
            width: 24px;
            height: 24px;
            margin: 5px
        }

        .header-sprite.location-icon {
            background-position: -179px 0;
            width: 33px;
            height: 43px
        }

        .header-sprite.close-int-pop-up {
            float: right;
            background-position: -214px 0;
            width: 19px;
            height: 18px;
            cursor: pointer
        }

        .navbar-nav .caret {
            background-image: url(/images/icons/Header_icons.svg);
            background-repeat: no-repeat;
            display: inline-block;
            background-position: -51px -9px;
            width: 6px;
            height: 10px;
            margin-left: 3px;
            border: none
        }

        @media (max-width: 853px) and (min-width: 768px) {
            .header-nav .dropdown-menu {
                font-size: 12px
            }
        }

        @media screen and (min-width: 1345px) {
            .header-nav .nav > li > a {
                margin-right: 20px;
                font-size: 14px
            }
        }

        @media (min-width: 1250px) {
            .header-nav .nav > li > a {
                font-size: 14px;
                margin-right: 15px
            }
        }

        @media screen and (max-width: 898px) {
            .mainmenudiv .caret {
                margin: 5px
            }

            .header-nav .nav > li > a {
                margin-right: 0;
                font-size: 11px
            }
        }

        @media screen and (max-width: 768px) {
            .header-nav .nav > li > a {
                font-size: 11px;
                padding: 15px 5px
            }
        }

        .international_buyer_section .detected_country {
            font-size: 24px;
            font-family: 'Red Hat Display 700';
            color: #1254ff
        }

        .international_buyer_section .your_location_header {
            font-family: 'Red Hat Display 700';
            font-size: 19px;
            color: #000
        }

        .international_buyer_section .langDropdown_select {
            width: 100%
        }

        .floating-design-sprite {
            background-image: url(/images/icons/Floating_design_icons.svg);
            background-repeat: no-repeat;
            display: inline-block
        }

        .floating-design-sprite.header-floating-design {
            background-position: 5px -140px;
            width: 112px;
            height: 85px;
            position: absolute;
            top: 0;
            right: 0
        }

        @media (max-width: 479px) {
            .floating-design-sprite.header-floating-design {
                background-position: 9px -145px
            }
        }

        .bot-banner-block .floating-design-sprite.bottom-banner-rec-design {
            transform: scale(.75)
        }

        @media (max-width: 1024px) {
            .loggedInUserIcon {
                display: none
            }
        }

        .header-top .loggedInUserIcon {
            margin-top: 3px
        }

        .header-top .globeicon {
            height: 26px
        }

        .dropdown-menu li a.help-center-link {
            color: #ffb838
        }

        .user-dropdown-btn {
            background: 0 0;
            border: none;
            white-space: nowrap
        }

        .btn:not(:focus-visible), .dropdown-toggle:not(:focus-visible), a:not(:focus-visible), button:not(:focus-visible) {
            outline: 0 !important
        }

        .btn:focus-visible, .dropdown-toggle:focus-visible, a:focus-visible, button:focus-visible {
            outline: 3px #ffb838 solid !important
        }

        .desktopScreen {
            display: block
        }

        .mobileScreen {
            display: none
        }

        @media (max-width: 991px) {
            .desktopScreen {
                display: none
            }

            .mobileScreen {
                display: block
            }
        }

        @media (max-width: 991px) {
            .navbar-toggle {
                display: block
            }

            .navbar-nav .open .dropdown-menu {
                position: relative;
                float: none;
                width: auto;
                margin: 0;
                padding: 0;
                -webkit-box-shadow: none;
                box-shadow: none;
                left: 0
            }
        }</style>


    <script type="text/javascript" id="firstScript">
        /*<![CDATA[*/
        window.dataLayer = [];
        window.googleOptimizeMembershipType = null;
        if (!window.googleOptimizeMembershipType || window.googleOptimizeMembershipType == "null") {
            window.googleOptimizeMembershipType = 'NONMEMBER';
            window.googleOptimizeIsLoggedIn = "false";
        } else {
            window.googleOptimizeIsLoggedIn = "true";
        }

        function addLoadEvent(onLoad) {
            var oldOnLoad = window.onload;
            if (typeof window.onload != 'function') {
                window.onload = onLoad;
            }
            else {
                if (window.onLoadCalled) {
                    onLoad();
                } else {
                    window.onload = function () {
                        if (oldOnLoad) {
                            oldOnLoad();
                        }
                        onLoad();
                    }
                }
            }
        }

        addLoadEvent(function () {
            window.onLoadCalled = true;
        })
        /*]]>*/
    </script>


    <script>
        /*<![CDATA[*/
        var appInit = {
            baseUrl: "https://www.copart.com/",
            userLang: "en",//this language code is used for whole i18n JS files, careful when you change
            selectedLang: "en",
            siteCode: "CPRTUS",
            environment: "PROD",
            gtmId: "GTM-TGVSRDM",
            optimizeId: "OPT-WPRQN4D",
            userVariant: "",
            userTerritory: null,//this is the value used for CLDR
            logLevel: 'debug',
            buildNumber: "2245",
            localeString: "en",//this is value that is used for content & site analyst
            redirectUrl: "",
            serverName: "lvpcaw202.corp.copart.com",
            isBrowserOld: false,
            isIEBrowser: false,
            oldBrowser: null,
            regionCode: "us",
            isUserAgentBOT: false,
            isTopBuyer: false,
            facebookUser: false,
            appPlatform: "us",
            rsid: "",
            // receive CSRF Token from CsrfGrantingFilter and set it here.
            csrfToken: "1b953004-0e42-4429-9a8f-fe0a615318e2",
            isAnonymous: true,
            initialPageLayout: "NON_OPTIMIZED_PAGE",
            layout: {
                "LOT_DETAILS_PAGE": "LOT_DETAILS_PAGE",
                "HOME_PAGE": "HOME_PAGE",
                "NON_OPTIMIZED_PAGE": "NON_OPTIMIZED_PAGE",
                "LOCATIONS_PAGE": "LOCATIONS_PAGE",
                "REGISTRATION_PAGE": "REGISTRATION_PAGE",
                "VEHICLE_FINDER_PAGE": "VEHICLE_FINDER_PAGE",
                "SEARCH_RESULTS_PAGE": "SEARCH_RESULTS_PAGE"
            },
            isMobileDevice: false,
            enableModularResources: true,
            staticResources: {
                "SONGBIRD": {
                    "path": "https://songbird.cardinalcommerce.com/edge/v1/songbird.js",
                    "crossOrigin": "true",
                    "originalPath": "https://songbird.cardinalcommerce.com/edge/v1/songbird.js"
                },
                "PAYMENT_LIBRARIES": {
                    "path": "/wro/payments-lib-919fc295eef0643523e187cade942bf4.js",
                    "originalPath": "/wro/payments-lib.js"
                },
                "SITE_STYLES": {
                    "path": "/wro/CPRTUSCSS-af9ba4339d8e07672c496b7eb669ffc5.css",
                    "originalPath": "/wro/CPRTUSCSS.css"
                },
                "HOME_BUNDLE": {
                    "path": "/wro/home_bundle-6ffacf2a286ed19abdd9f00d81df7949.js",
                    "originalPath": "/wro/home_bundle.js"
                },
                "SEARCH_RESULTS_BUNDLE": {
                    "path": "/wro/searchResults_bundle-0f763c0057c14f411026c82683c2fa6e.js",
                    "originalPath": "/wro/searchResults_bundle.js"
                },
                "REGISTRATION_BUNDLE": {
                    "path": "/wro/registration_bundle-7283f2693a7595c825e47e8151a48124.js",
                    "originalPath": "/wro/registration_bundle.js"
                },
                "APP_BUNDLE": {
                    "path": "/wro/app_bundle-117845dc77f0a4c543d814ee356929c4.js",
                    "originalPath": "/wro/app_bundle.js"
                },
                "COPART_FONTS": {
                    "path": "/wro/copartFonts-692a58087d58c1f94b021681f6742ed0.css",
                    "originalPath": "/wro/copartFonts.css"
                },
                "LANGUAGE_STYLES": {
                    "path": "/wro/en-42a0cd07c02dbdd49027800030fef186.css",
                    "originalPath": "/wro/en.css"
                },
                "REFERENCE_DATA_LESS": {
                    "path": "/public/data/referenceData/less-e9bc0b84f07fc3c837ee7afb34473bb9.js",
                    "originalPath": "/public/data/referenceData/less-e9bc0b84f07fc3c837ee7afb34473bb9.js"
                },
                "ALL_APP_STYLES": {
                    "path": "/wro/all_app_styles-d985d8ef169cd646ce48d8b522f99e99.css",
                    "originalPath": "/wro/all_app_styles.css"
                },
                "SONGBIRD_QA": {
                    "path": "https://songbirdstag.cardinalcommerce.com/edge/v1/songbird.js",
                    "crossOrigin": "true",
                    "originalPath": "https://songbirdstag.cardinalcommerce.com/edge/v1/songbird.js"
                },
                "BOOTSTRAP_STYLES": {
                    "path": "/wro/bootstrap_reduced-3653aa51d645cf8c6c4faf90be6f83e1.css",
                    "originalPath": "/wro/bootstrap_reduced.css"
                },
                "LOT_DETAILS_BUNDLE": {
                    "path": "/wro/lot_details_bundle-b6fcfcb94ddab131f10929eec8347885.js",
                    "originalPath": "/wro/lot_details_bundle.js"
                },
                "VEHICLE_FINDER_BUNDLE": {
                    "path": "/wro/vehicleFinder_bundle-92d436dab6cd7b2ab46201bdfd6e5b73.js",
                    "originalPath": "/wro/vehicleFinder_bundle.js"
                },
                "LOCATIONS_BUNDLE": {
                    "path": "/wro/locations_bundle-78e4869fa06ebbdf2c3ff04bfa675d88.js",
                    "originalPath": "/wro/locations_bundle.js"
                },
                "FONT_AWESOME_STYLES": {
                    "path": "/wro/font-awesome-0a0a73d4451ac90a57aa086ca70cfb88.css",
                    "originalPath": "/wro/font-awesome.css"
                }
            },
            liveAuctionsCount: 0
        };
        var getResourcePath = function (resource) {
            var resource = appInit.staticResources[resource];
            var resourcePath;
            if (!!resource && resource.path) {
                resourcePath = resource.path;
            }
            return resourcePath;
        };
        appInit.allJSBundles = [
            getResourcePath("HOME_BUNDLE"),
            getResourcePath("SEARCH_RESULTS_BUNDLE"),
            getResourcePath("VEHICLE_FINDER_BUNDLE"),
            getResourcePath("LOCATIONS_BUNDLE"),
            getResourcePath("REGISTRATION_BUNDLE"),
            getResourcePath("LOT_DETAILS_BUNDLE"),
            getResourcePath("APP_BUNDLE")
        ];

        appInit.allExternalModules = [{
            name: 'angularjs-dropdown-multiselect'
        }, {
            name: 'angularjs-gauge'
        }];

        window.externalLibrariesStallDuration = 2000;
        if (!appInit.isMobileDevice) {
            window.externalLibrariesStallDuration = 250;
        }
        var globalLicenceCategory;
        var kiosk = false;
        console.log('Pretty Build Number is : ' + appInit.buildNumber);
        console.log('Build Number is : ' + appInit.buildNumber);
        console.log('Server Instance is :' + appInit.serverName);
        console.log('Language Selection - ' + appInit.localeString);
        /*]]>*/
    </script>

    <div>
        <script>
            var isAnonymous = appInit.isAnonymous;

            function getCookieByName(cookieName) {
                var name = cookieName + "=";
                var cookieDecoded = decodeURIComponent(document.cookie);
                var cookieArr = cookieDecoded.split('; ');
                var res = null;
                cookieArr.forEach(function (val) {
                    if (val.indexOf(name) === 0) res = val.substring(name.length);
                });
                return res;
            }

            function updateUserCategoryCookie(cookieValue) {
                // Expire the userCategory cookie in 50 years
                document.cookie = "userCategory=" + cookieValue + ";max-age=" + 60 * 60 * 24 * 365 * 50 + "; path=/";
            }

            /*
            *  LIM: Logged-in Member
            *  LOM: Logged-out Member
            *  PU:  Public User
            *  RPU: Recurring Public User
            * */

            var userCategory = getCookieByName("userCategory");
            if (!userCategory) {
                updateUserCategoryCookie("PU");
            } else if (isAnonymous) {
                if (userCategory == "LIM") {
                    updateUserCategoryCookie("LOM");
                } else if (userCategory != "LOM") {
                    updateUserCategoryCookie("RPU");
                }
            }
        </script>
    </div>


    <script src="https://www.googleoptimize.com/optimize.js?id=OPT-WPRQN4D"></script>

    <!--IE Poly fill Library for JS Compatibility-->


    <link rel="shortcut icon" href="/images/favicon-COPART.ico" defer="defer"/>

    <style type="text/css">
        .my-cloak, .ng-cloak {
            display: none !important;
        }
    </style>

    <link rel="preconnect" href="https://smetrics.copart.com"/>
    <link rel="preconnect" href="https://www.google.com"/>

</head>
<body ng-controller="appController" id="mainBody" class="d-f-c"
      ng-class="(currentPageLayout == appInit.layout.REGISTRATION_PAGE) ? 'reg-bg' : ''">
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TGVSRDM" height="0" width="0"
            style="display:none;visibility:hidden"></iframe>
</noscript>

<header id="top" class="header header-section"
        ng-show="(currentPageLayout != appInit.layout.REGISTRATION_PAGE) &amp;&amp; !fromMobileApp"
        ng-controller="headerController">
    <div class="header-mid" clicksigninmenu="">
        <!-- cookie policy message for UK and US-->
        <div class="container cookie-policymsg row" hide-cookie-banner="" style="display: none;">
            <span class="fa fa-info-circle fa-2x col-xs-1 cookie-policymsg-icon"></span>
            <span class="cookie-text col-xs-9" ng-bind-html="locale.messages['app.label.cookieMessage'] | to_trusted">By continuing to use this website, you consent to cookies being used unless you have disabled them. Please note that disabling cookies on your machine or device may prevent parts of our website from functioning properly. <a
                        href="/content/us/en/cookie-policy">Learn more about our Cookie Policy.</a></span>
            <span class="col-xs-2 cookie-continue-btn">
        <a class="btn btn-lblue" id="acceptCookieBanner">
            Continue
        </a>
    </span>
        </div>
        <!-- end of cookie policy message -->
        <div class="container-fluid topheader beforelogin"
             ng-class="(userConfig.roles.indexOf('ROLE_ANONYMOUS') != -1) ? 'beforelogin' : 'afterlogin' ">

            <div class="desktopScreen">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 leftheader  pad0">
                            <div class="logodiv col-lg-3 col-md-3 col-sm-3 col-xs-3 pad0">
                                <time-modal></time-modal>
                                <div class="logo m-0-i">
                                    <a href="./" ng-click="routeReload('/')" data-uname="copartLogo"
                                       class="logo copart-img ml-20">
                                        <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjkiIGhlaWdodD0iNDgiIGZpbGw9Im5vbmUiIHZpZXdCb3g9IjAgMCAxMjkgNDgiPgogICAgPG1hc2sgaWQ9Imo1YWp1a29vbmEiIHdpZHRoPSIxMDciIGhlaWdodD0iNDgiIHg9IjIyIiB5PSIwIiBtYXNrVW5pdHM9InVzZXJTcGFjZU9uVXNlIj4KICAgICAgICA8cGF0aCBmaWxsPSIjZmZmIiBkPSJNMjIuMjgzIDQ4SDEyOC40MlYwSDIyLjI4M3Y0OHoiLz4KICAgIDwvbWFzaz4KICAgIDxnIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBtYXNrPSJ1cmwoI2o1YWp1a29vbmEpIj4KICAgICAgICA8cGF0aCBmaWxsPSIjMTUxMzE3IiBkPSJNMTI3LjExMyAyMy45OTZjLTYuNDQ0IDEzLjI0Mi0zNC45NDYgMjQuMDIyLTYzLjQ0NiAyNC4wMjItMjguNTI4IDAtNDYuNTItMTAuNzgtNDAuMDc2LTI0LjAyMkMzMC4wMSAxMC44MDYgNTguNTM2IDAgODcuMDQgMGMyOC41MjcgMCA0Ni41MTUgMTAuODA2IDQwLjA3NCAyMy45OTZ6Ii8+CiAgICAgICAgPHBhdGggZmlsbD0iIzY0NzM4NyIgZD0iTTg1Ljk4MiAxLjIwOGMyNy4zNjggMCA0NC42MyAxMC4xMDggMzguNDQ4IDIyLjQ0Ny02LjE4MiAxMi4zODctMzMuNTI3IDIyLjQ3Mi02MC44NyAyMi40NzItMjcuMzY5IDAtNDQuNjMtMTAuMDg1LTM4LjQ0Ny0yMi40NzJDMzEuMjcgMTEuMzE1IDU4LjYzOCAxLjIwOCA4NS45ODIgMS4yMDh6Ii8+CiAgICA8L2c+CiAgICA8cGF0aCBmaWxsPSIjMTUxMzE3IiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik04MS42MiAzLjMxN2MyNC43MjUgMCA0MC4yODggOS4wNCAzNC42NjQgMjAuMDk1LTUuNjAxIDExLjA3OC0zMC4zNSAyMC4xMi01NS4wMjcgMjAuMTItMjQuNzI2IDAtNDAuMjktOS4wNDItMzQuNjg5LTIwLjEyIDMuNjM2LTcuMTI2IDE1LjE1LTEzLjM4MSAyOS40NzctMTYuOTY4IDEuMDE4LS4yNjggMi4wNi0uNDg1IDMuMTAzLS43MjggNy4xMjctMS41MjYgMTQuODExLTIuNCAyMi40NzEtMi40eiIgY2xpcC1ydWxlPSJldmVub2RkIi8+CiAgICA8cGF0aCBmaWxsPSIjMTI1NEZGIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMTMuOTczIDE0LjM1N2MxLjIyOSAyLjAxNCAxLjQ1NyA0LjI4LjQ4MyA2LjY1Ni0uOTExIDEuNzk5LTEuOTU3IDMuMjQ3LTMuNTE1IDQuNjcxLTguMDgzIDcuMzY2LTI2LjI3MiAxMi44NTktNDQuNDM2IDEyLjg1OS0yMS40MTcgMC0zNC45MjMtNy42MzMtMzAuMDQ0LTE2Ljk5QzM5LjgxMyAxNS4xMSA1MS4xNzUgOS4yNTIgNjQuNzggNi4zNmMxLjYtLjM0IDMuMjI3LS42MDcgNC44NzgtLjg1LTMuMjAxLjMxNi02LjMzMy43NzctOS40MTIgMS40MzMtMTUuMDg0IDMuMjgyLTI3Ljg3NSA4LjU4Mi0zMS41OTggMTUuOTIyQzIzLjI1IDMzLjUzNiAzOC4yMSA0Mi4yMzcgNjEuOTcgNDIuMjM3YzE5LjQ2OCAwIDM4Ljk2NC01LjgzMyA0OC4zMjktMTMuODMgMS4wNTgtLjg5OCAyLjI5Ni0yLjE2NiAzLjI2Ny0zLjUxOC40MDEtLjU0Ni43NTMtMS4xIDEuMDQ2LTEuNjU5IDEuNjc2LTMuMjA5IDEuMzU2LTYuMjQ3LS42NC04Ljg3M3oiIGNsaXAtcnVsZT0iZXZlbm9kZCIvPgogICAgPHBhdGggZmlsbD0iIzM2M0EzRiIgZmlsbC1ydWxlPSJldmVub2RkIiBkPSJNMTQuMzMgMjEuNzY4Yy0uNDM4LjY4OS0uODc4IDEuMTk3LTEuMzA3IDEuNTEybC0uMDE3LjAxM2MtLjQ5NS4zOS0xLjE0My41ODctMS45MjcuNTg3LS41MSAwLS44NTktLjEwMi0uOTU2LS4yNzQtLjAxLS4wMjctLjI0LS42Ni40NjktMi45NDMuNDY5LTEuNDQ3IDEuMDE1LTIuNDggMS42MjQtMy4wNzIuNzU2LS43NTcgMS41NjctMS4xMDkgMi41NTItMS4xMDkuMzEgMCAuNTY3LjA0OC43MjQuMTM0LjExNy4wNjcuMjEuMTE5LjI0Ni41NDVsLjE0NCAxLjY2N2g3Ljg1OGMtLjQ5My42NTQtLjkxNSAxLjM1OC0xLjI1NCAyLjA5NmgtNy42MTlsLS41MzcuODQ0em0xOS4wNS40ODVjLS4yOTIuOTMzLS42NyAxLjYxMS0xLjA5MiAxLjk1OS0uNDMuMzQ1LS43OTMuNDk5LTEuMTc0LjQ5OS0uMDggMC0uMTMtLjAwNy0uMTYtLjAxNC0uMDE2LS4xMDMtLjA0MS0uNDY5LjIyMS0xLjMwNS4yNS0uODUxLjYxOS0xLjQ5OCAxLjA2My0xLjg3LjQwOC0uMzI1Ljc4NS0uNDcgMS4yMjMtLjQ3LjA1MyAwIC4wOTEuMDAzLjExOC4wMDYuMDE2LjExNi4wMjYuNDYtLjE5OCAxLjE5NXptMTguNzY4LS4wMTljLS4yNDIuODI0LS41NjYgMS40MzktLjkxMyAxLjczLS4yODQuMjQtLjUyMy4zNTUtLjczMi4zNTVsLS4wNTgtLjAwMWMuMDA1LS4xNDQuMDM4LS40MzIuMTg3LS45MTQuMzI0LTEuMDU2LjY5LTEuNTEuOTQtMS43MDQuMjg3LS4yMzMuNTMzLS4zNTYuNzkyLS4zOTItLjAxNS4xNzUtLjA2Ni40NjUtLjIxLjkwN2wtLjAwNi4wMTl6bTU1LjIyMS04LjIwMWwyLjA4NC02LjY4LTEwLjcxMiA0LjQ0My0uMjc0LjExNC0uNjYxIDIuMTIzaC0yLjEyNGwtLjA4Ny4yNzhjLS44OTYtLjQtMS43OC0uNTk2LTIuNzAxLS41OTYtLjcyMyAwLTEuNDA5LjEwNy0yLjA0LjMxOGgtOC4zOWwtMS41ODcgNS4wNzFjLjAwMi0uMjA3LS4wMDQtLjQyLS4wMTgtLjYzNC0uMDc1LTEuMS0uNDYyLTIuMDc2LTEuMDg4LTIuNzQ0LS43OC0uODkyLTEuODgxLTEuNDY3LTMuMjcxLTEuNzEtMS4wMy0uMjA1LTIuNDI4LS4zLTQuMzk5LS4zLTEuMTUzIDAtMi4yNDEuMDc3LTMuMzI4LjIzOS0xLjIyOS4xOS0yLjEzLjQzOS0yLjgzNi43ODMtMS4wNDYuNDUzLTEuOTEzIDEuMDA1LTIuNTc2IDEuNjQtLjU4NC41MzItMS4xMTggMS4yMS0xLjYzMSAyLjA3LS4xMDUtLjUzOC0uMjY2LTEuMDU3LS40OC0xLjU0NC0uNjc5LTEuNDU0LTIuMjA5LTMuMTg5LTUuNjQtMy4xODktLjg0NyAwLTEuNjQ3LjEwNy0yLjQ0Mi4zMjZsLjAwMi0uMDA4aC05LjE2M2wtMS4zMTkgNC4yMTRjLS4xNTMtLjQzNi0uMzU0LS44Ni0uNi0xLjI2LTEuMzgtMi4xNzEtMy45MjktMy4yNzItNy41NzYtMy4yNzItMi45OTIgMC01LjY1NS44MDItNy45MTQgMi4zODMtLjQxNi4yOTMtLjgxNS42MDctMS4xODYuOTM0di0uMDI3YzAtMy4xMDgtLjg0NC01LjMwNy0yLjU4LTYuNzIyLTEuNTEyLTEuMjktMy43NDItMS45MTgtNi44MTctMS45MTgtMy42NjkgMC02Ljg5OC45ODgtOS41OTggMi45MzhDMy43MyAxMy4yNiAxLjgyIDE2LjA0Ny43MzkgMTkuNTg3Yy0uODc1IDIuNzY1LS45NzQgNS4xMzctLjI5MiA3LjA1NC43MyAyLjAwMSAxLjkzOSAzLjQyNyAzLjU5MSA0LjIzOCAxLjQ4NS43NDIgMy41MDkgMS4xMTggNi4wMTUgMS4xMTggMi4wODUgMCAzLjkwOC0uMjkyIDUuNDItLjg3IDEuNTI2LS41NTQgMi45Ny0xLjQyIDQuMjk1LTIuNTcyLjYwNS0uNTQ0IDEuMjctMS4yNjQgMS44NzctMi4wMzMuMzI0IDEuNTA1IDEuMTEzIDIuNTY4IDEuNzI0IDMuMiAxLjQ5MyAxLjQ5MyAzLjcxMiAyLjI1IDYuNTk4IDIuMjUgMy4wMzEgMCA1LjcyNi0uODA4IDguMDA4LTIuNDA1LjU2Ni0uMzk2IDEuMDk1LS44MzIgMS41NzUtMS4yOTZsLTIuNzIgOC42ODdoOS41ODZsMS43MzctNS41MTVjLjgzNi4zNTIgMS43OTkuNTMgMi44NjMuNTMgMi4yMzIgMCA0LjMyLS43NDcgNi4yMDctMi4yMi41MzktLjQyNCAxLjA0Ni0uOTExIDEuNTA4LTEuNDUuMTcuNjk1LjUxIDEuMzI3IDEuMDEgMS44ODEgMS4wNTMgMS4yMDQgMi43MDcgMS43ODkgNS4wNTUgMS43ODkgMS41NSAwIDMuMDAxLS4yMzIgNC4zMTYtLjY5LjIxNi0uMDc2LjQzNy0uMTYzLjY1OC0uMjZsLjIxLjYzMWgxNi41MzJsMS44NjctNS45NjdjLjc3Ny0yLjQ3IDEuNDg5LTMuNDU3IDEuODQzLTMuODI0LjM0MS0uMzA1LjU3LS4zNDcuNzY2LS4zNDcuMDIzIDAgLjIzNS4wMDcuNzUzLjIzNWwxLjI4OS41Ny42MTItLjczOCAxLjgwMy4wMDQtLjc5MiAyLjUyOWMtLjYxOCAxLjg4My0uODI3IDMuMjI2LS42NTYgNC4yMjYuMTc5IDEuMjIzLjgzOCAyLjIzNSAxLjg1NiAyLjg1Ljg3LjUzMyAyLjEyNy43OCAzLjk1Ny43OCAxLjMzNSAwIDIuODI0LS4xNTggNC40MjktLjQ3MmwuNzc5LS4xNTIuNDE1LS4wODIgMS42MDQtNy44NzEtMi44NzEuODQyLjgyMi0yLjY0OGgzLjE0OWwyLjM1OS03LjU1NmgtMy4xMjd6IiBjbGlwLXJ1bGU9ImV2ZW5vZGQiLz4KICAgIDxwYXRoIGZpbGw9IiMxNTEzMTciIGZpbGwtcnVsZT0iZXZlbm9kZCIgZD0iTTE1LjEzMyAyMS40MDhsLS4zOTQuNjJjLS40Ny43NC0uOTUxIDEuMjkyLTEuNDMzIDEuNjQ2LS41ODIuNDU3LTEuMzMyLjY5LTIuMjI3LjY5LS43MiAwLTEuMTg0LS4xNzctMS4zODEtLjUyNy0uMDY1LS4xMTMtLjM1LS44MDUuNDMtMy4zMTcuNDk1LTEuNTI3IDEuMDg0LTIuNjMgMS43NTEtMy4yNzguODM3LS44MzggMS43ODItMS4yNDUgMi44OS0xLjI0NS40MDEgMCAuNzIzLjA2Ni45NTcuMTk0LjI3Ni4xNTYuNDQ2LjM1OC40OTUuOTI4bC4xMDUgMS4yMjRoOC40NDVjLS44MzQuOTItMS40OTMgMS45NDUtMS45NyAzLjA2NWgtNy42Njh6bTE4LjcxMi45ODVjLS4zMjggMS4wNC0uNzQ4IDEuNzc5LTEuMjQ3IDIuMTktLjUyLjQxOC0uOTkyLjYxMi0xLjQ4NC42MTItLjQzOCAwLS41MjctLjE1My0uNTg3LS4yNTYtLjAyMy0uMDM4LS4yMTUtLjQxNC4xODYtMS42OTQuMjgtLjk1LjY4OC0xLjY1NSAxLjIxMy0yLjA5My40OTktLjQuOTg3LS41ODYgMS41MzUtLjU4Ni40MDMgMCAuNDc0LjEyLjU1LjI1LjAwOS4wMTQuMjA4LjM1Mi0uMTY2IDEuNTc3em0xNi4zMjQuODY3Yy4yODYtLjkzLjY2OC0xLjYwMyAxLjEwOC0xLjk0NS40MjEtLjM0My44Mi0uNTA0IDEuMjUzLS41MDQuMjIzIDAgLjIzNy4wMjcuMjg5LjEyLjA0NC4wNzYuMS41LS4yMDYgMS40MzgtLjI3Mi45MjktLjY0IDEuNjEtMS4wNjcgMS45NjYtLjM3NC4zMTUtLjcxNS40NjgtMS4wNDMuNDY4LS4zOTcgMC0uNDYzLS4xMS0uNTA3LS4xODMtLjAwMS0uMDAzLS4xNTItLjMxLjE3My0xLjM2em0xMC43ODQtLjIzOGMuMTk1LS42OTEuMzI3LTEuMzY1LjM5NC0yLjAxbDIuNTQzLjI1OWMtLjkyNS4zMTQtMS42NzcuNzAxLTIuMjg1IDEuMTc2LS4yMy4xNzktLjQ0OC4zNzEtLjY1Mi41NzV6bTQ2LjgyOC0xLjkxOGwyLjA1Ni02LjU4N2gtMy4xMjdsMS45NzItNi4zMi05LjgyIDQuMDczLS42OTkgMi4yNDdoLTIuMTI1bC0uMTUuNDc4Yy0xLS41MzUtMS45OC0uNzk1LTIuOTk0LS43OTUtLjcgMC0xLjM1OC4xMDYtMS45Ni4zMTdoLTguMTEzbC00LjgxIDE1LjM2Ny0uMDQ2LS4yNzFjLS4xMDctLjY1LS4xMTgtLjk3Mi0uMTA4LTEuMTI3LjAxMS0uMTYzLjA3LS41MS4yODUtMS4yMzZsMS45MjUtNi4xNmMuMjY5LS44MTEuMzctMS42NTguMzA4LTIuNTg5LS4wNjgtLjk5NC0uNDEtMS44NjUtLjk2Mi0yLjQ0OS0uNzA2LS44MS0xLjcxNS0xLjMzNC0yLjk5NC0xLjU1OC0xLjAwOC0uMi0yLjM4LS4yOTQtNC4zMTgtLjI5NC0xLjEyOSAwLTIuMTk0LjA3Ni0zLjI1Ny4yMzQtMS4xODQuMTgzLTIuMDQ0LjQyLTIuNzAxLjc0Mi0xIC40MzItMS44MjMuOTU0LTIuNDQ1IDEuNTUtLjY2My42MDUtMS4yNjUgMS40MTUtMS44MzkgMi40NzZsLS40NjQuODU4Yy4wMDEtMS4wNzYtLjE5Ni0yLjA3LS41OS0yLjk2My0uNjE3LTEuMzIyLTIuMDItMi44OTgtNS4xOTUtMi44OTgtMS4xMDcgMC0yLjE1My4xOTYtMy4xODQuNTk4bC4wODctLjI4aC04LjE1bC0xLjc5IDUuNzE2Yy0uMDQ1LTEuMDg4LS4zNDYtMi4wOTEtLjg5OS0yLjk5NS0xLjI4Mi0yLjAxNi0zLjY5Mi0zLjAzOC03LjE2Mi0zLjAzOC0yLjg5IDAtNS40Ni43NzEtNy42MzcgMi4yOTUtLjcyNi41MS0xLjM3NiAxLjA3MS0xLjk0OCAxLjY3OHYtMS4xNjljMC0yLjk1My0uNzg2LTUuMDI5LTIuNC02LjM0NS0xLjQyNy0xLjIxOC0zLjU1OC0xLjgxLTYuNTEyLTEuODEtMy41NjUgMC02LjY5OS45NTgtOS4zMTUgMi44NDctMi41OTggMS44OTMtNC40NDcgNC41OTUtNS40OTcgOC4wMzEtLjg0NCAyLjY2NS0uOTQ1IDQuOTM2LS4yOTkgNi43NTIuNjg1IDEuODc3IDEuODExIDMuMjEgMy4zNDYgMy45NjQgMS40Mi43MSAzLjM3MyAxLjA3IDUuODAzIDEuMDcgMi4wMjUgMCAzLjc5LS4yODMgNS4yNDYtLjgzOCAxLjQ3Ni0uNTM3IDIuODcyLTEuMzczIDQuMTUyLTIuNDg3Ljg1MS0uNzY1IDEuNzkxLTEuODYgMi41My0yLjkzNi4wNTUgMS45NzMuOTU1IDMuMzI2IDEuNzM3IDQuMTM0IDEuMzk0IDEuMzk0IDMuNDk3IDIuMTAyIDYuMjQ5IDIuMTAyIDIuOTMgMCA1LjUzMS0uNzggNy43My0yLjMyIDEuMjI4LS44NTkgMi4yNDYtMS44ODMgMy4wMzYtMy4wNTRsLTMuMjQzIDEwLjM2aDguNTdsMS43OTYtNS43Yy4xMDguMDU0LjIxOC4xMDYuMzI4LjE1My44MTIuMzcyIDEuNzY0LjU2IDIuODMxLjU2IDIuMTIyIDAgNC4xMS0uNzEyIDUuOTEtMi4xMTcuODE1LS42NCAxLjU0NC0xLjQyNSAyLjE3MS0yLjMzNy0uMDYzIDEuMzI1LjQ5IDIuMjU1IDEuMDAzIDIuODI1Ljk2IDEuMDk3IDIuNDk3IDEuNjMgNC42OTcgMS42MyAxLjQ5NSAwIDIuODkzLS4yMjMgNC4xNTgtLjY2My4zNTgtLjEyNy43MjgtLjI4NCAxLjEwNC0uNDdsLjI3MS44MTRoMTUuODI3bDEuNzYtNS42MjdjLjc3NC0yLjQ1OCAxLjUtMy41NSAxLjk2Ny00LjAyNy4zNy0uMzM1LjcxMS0uNDg0IDEuMTA1LS40ODQuMDk3IDAgLjM4My4wMjcuOTQ4LjI3NmwuOTU1LjQyMi41MjQtLjYzMiAyLjY4OS4wMDYtLjk4OCAzLjE1NWMtLjU5NiAxLjgxNy0uNzk5IDMuMDktLjY0IDQuMDA2LjE1NiAxLjA4LjczNCAxLjk3MyAxLjYyNyAyLjUxMi43OTMuNDg2IDEuOTcuNzEyIDMuNzA2LjcxMiAxLjMwNCAwIDIuNzYyLS4xNTYgNC4zMzYtLjQ2NGwuODc3LS4xNzIgMS4zOC02Ljc2Ni0yLjEzMi42MjZjLS4zMDYuMDg5LS41ODIuMTYtLjgyNi4yMTNsMS4xODctMy44MjFoMy4xNXoiIGNsaXAtcnVsZT0iZXZlbm9kZCIvPgogICAgPHBhdGggZmlsbD0iI2ZmZiIgZmlsbC1ydWxlPSJldmVub2RkIiBkPSJNMTguNTU2IDI3LjE5MmMxLjIyMS0xLjA5OSAyLjY2NC0yLjkzMSAzLjMyMy00LjQ0NmgtNi4wMWMtLjUxNC44MDctMS4xIDEuNTE1LTEuNzM1IDEuOTgtLjgwNy42MzQtMS44MzMuOTc3LTMuMDU1Ljk3Ny0xLjI0NSAwLTIuMS0uNDE1LTIuNTQxLTEuMTk4LS40NjQtLjgwNS0uMzQyLTIuMjczLjMxNy00LjM5OC41NjMtMS43MzQgMS4yNDctMy4wMDUgMi4wNzctMy44MTEgMS4xLTEuMSAyLjM5NS0xLjYzOCAzLjgzNy0xLjYzOC42MzUgMCAxLjE3Mi4xMjMgMS42MTMuMzY3Ljc4MS40NCAxLjEgMS4xMjQgMS4xNzMgMS45OGg2LjAzNGMwLTEuOTA2LS4zNDItNC4wMzMtMS45My01LjMyNy0xLjE3My0xLjAwMi0zLjA1NS0xLjQ5LTUuNjQ0LTEuNDktMy4zMjMgMC02LjE1Ny44OC04LjUyNyAyLjU5LTIuMzQ2IDEuNzA5LTQuMDMxIDQuMTU0LTUuMDA5IDcuMzUzLS43NTggMi4zOTUtLjg1NiA0LjM3NC0uMzE3IDUuODg5LjU2MSAxLjUzOSAxLjQ0MSAyLjYxNCAyLjY4NyAzLjIyNSAxLjIyMi42MTEgMi45NTYuOTI5IDUuMjA0LjkyOSAxLjgzMyAwIDMuNDQ1LS4yNDUgNC43ODktLjc1NyAxLjM0My0uNDg5IDIuNTktMS4yNDYgMy43MTQtMi4yMjV6bTE0Ljg4LTEuNTYzYy43MDktLjU4NSAxLjI3LTEuNTE0IDEuNjg2LTIuODM0LjM2Ni0xLjE5Ny4zOS0yLjEwMi4wMjUtMi42ODgtLjM0My0uNTg3LS45MDUtLjg3OS0xLjY4Ni0uODc5LS44NTYgMC0xLjYzOC4yOTItMi4zNzEuODgtLjczMy42MS0xLjI5NSAxLjUxNC0xLjY2MSAyLjc2LS4zOTEgMS4yNDctLjQxNiAyLjE1MS0uMDQ5IDIuNzYuMzQyLjU4OC45MjkuOTA2IDEuNzM0LjkwNi44MzIgMCAxLjU4OC0uMzE4IDIuMzIxLS45MDV6bTEuMDc2LTEwLjA5YzMuMDA0IDAgNS4wMDguODA1IDYuMDMzIDIuNDE4LjgwNyAxLjMyLjkyOSAyLjkzMy4zMTggNC44MzgtLjY4MyAyLjE1LTEuOTc5IDMuOTEtMy45MzMgNS4yNzgtMS45NTUgMS4zNjgtNC4yNzcgMi4wNzctNi45NjMgMi4wNzctMi40MiAwLTQuMTgtLjU4Ny01LjMwMi0xLjcxLTEuMzctMS40MTctMS43MS0zLjI3NS0uOTc4LTUuNTczLjY1OS0yLjEyNCAxLjk3OS0zLjg4NCAzLjk1OS01LjI3NyAxLjk1My0xLjM2NyA0LjI1LTIuMDUyIDYuODY2LTIuMDUyem0xOS4zODYgNy4yMDhjLS4zNjYgMS4yNDUtLjg3OSAyLjEtMS40OSAyLjYxMy0uNjEuNTE0LTEuMjQ1Ljc4My0xLjkwNS43ODMtLjc1OCAwLTEuMzItLjI2OS0xLjYzOC0uODA4LS4zNDItLjUzNi0uMzE2LTEuMzY3LjAyNC0yLjQ2OC4zNjgtMS4xOTUuODgtMi4wNzYgMS41NDEtMi41ODguNjU5LS41MzggMS4zNDMtLjgwNiAyLjEtLjgwNi42NiAwIDEuMTUuMjQ0IDEuNDQzLjc4LjMxNi41MzguMjkzIDEuMzctLjA3NSAyLjQ5NHptNS42OTUtNS4wODRjLS42NjItMS40MTYtMS45OC0yLjEyNS0zLjk4My0yLjEyNS0xLjAwMiAwLTEuOTguMTk1LTIuOTU3LjYxMi0uNzA5LjI5Mi0xLjU2NC44NzgtMi41OSAxLjc1OGwuNjM1LTIuMDUyaC01LjM1MWwtNi4wMzUgMTkuMjhoNS43NjdsMi4xMDItNi42NzJjLjM5LjU2Mi45MjguOTc3IDEuNTYzIDEuMjQ2LjYzNS4yOTIgMS4zOTIuNDQgMi4yNzIuNDQgMS44MDggMCAzLjUxOS0uNjEyIDUuMDgyLTEuODMzIDEuNTg4LTEuMjQ2IDIuNzM3LTMuMDA1IDMuNDctNS4zMjcuNjYtMi4xMjUuNjYtMy44ODQuMDI1LTUuMzI3em0xMS43IDguMDE2Yy4zNjctLjQxNi42MzctLjk3OC44NTgtMS42MzdsLjI2Ni0uODU2Yy0uODc5LjI2OC0xLjc1Ny40ODgtMi42ODYuNzA4LTEuMjQ3LjI5NC0yLjA3Ny41NjMtMi40NjcuODU2LS40MTcuMjk0LS42ODQuNjEtLjc4My45NzgtLjE0Ny40MTUtLjA5Ny43NTcuMTQ4IDEuMDI1LjIxOC4yNy42MzUuNDE2IDEuMjQ0LjQxNi42NjIgMCAxLjI5NS0uMTQ2IDEuOTMxLS40NC42MzUtLjI5NCAxLjEyMy0uNjM2IDEuNDktMS4wNXptNy4xMS04Ljc0NmMuMzY4LjM5LjU4OC45NTIuNjM3IDEuNjYuMDQ4LjczMy0uMDI2IDEuNDE3LS4yNDMgMi4wNzdsLTEuOTMzIDYuMTgxYy0uMTk0LjY2LS4zMTcgMS4xNzMtLjM0MiAxLjU0LS4wMjQuMzY2LjAyNS44NTQuMTIyIDEuNDRoLTUuMzVjLS4xMjMtLjM2OC0uMTctLjYzMy0uMTctLjgzIDAtLjE5Ni4wMjMtLjQ4OS4wNzItLjg4LS45NTEuNjYtMS44NTcgMS4xNDktMi42ODggMS40NDItMS4xMjQuMzktMi4zNy41ODctMy43MTIuNTg3LTEuNzg0IDAtMy4wMDUtLjM5MS0zLjY5LTEuMTczLS42ODMtLjc1OC0uODU0LTEuNzEtLjQ4OC0yLjg1OC4zMTgtMS4wNS45MjktMS45MzEgMS44MDgtMi42MTUuODc5LS42ODYgMi4yNzItMS4xOTggNC4xNzgtMS41MTYgMi4yNzEtLjQxMyAzLjczOC0uNzA4IDQuNDQ2LS44NTQuNjgzLS4xNzEgMS40MTgtLjM5MSAyLjIyMy0uNjM1LjE5Ny0uNjYuMTk3LTEuMTI1IDAtMS4zOTMtLjIyLS4yNy0uNjg0LS4zOTEtMS40MTYtLjM5MS0uOTUzIDAtMS42ODYuMTQ2LTIuMjQ4LjQxNS0uNDQuMjItLjg1NS42MzYtMS4yNzMgMS4yNDZsLTUuMy0uNTM3Yy40OS0uOTA1IDEuMDAzLTEuNjEzIDEuNTY1LTIuMTI1LjUzOC0uNTE0IDEuMjQ1LS45NTQgMi4xLTEuMzIuNTg1LS4yOTMgMS4zOTItLjQ5IDIuMzQ2LS42MzYuOTc4LS4xNDYgMS45NzgtLjIyIDMuMDU0LS4yMiAxLjcwOSAwIDMuMDguMDc0IDQuMDU1LjI3Ljk3Ny4xNyAxLjczNS41MzYgMi4yNDcgMS4xMjV6bTUuNDAxLTEuMDc3aDUuMzVsLS43MDcgMi4yNzJjLjgzLS45NzcgMS41ODgtMS42NjEgMi4yNDgtMi4wMjcuNjYtLjM5MiAxLjM5MS0uNTYzIDIuMTk5LS41NjMuODU1IDAgMS42ODUuMjQ1IDIuNTQuNzMzbC0yLjk1NyAzLjgxMmMtLjYxLS4yNy0xLjA5OC0uMzktMS40ODktLjM5LS43NTcgMC0xLjQxOC4yOTEtMi4wMy44NTUtLjgyOC44My0xLjYxMiAyLjM0Ny0yLjMxOCA0LjU5MmwtMS40NjggNC42OTFINzkuNDNsNC4zNzUtMTMuOTc1em0yMS4wODcgMGwxLjY2MS01LjMyNi02LjU5OCAyLjczNi0uODA2IDIuNTloLTIuMTI2bC0xLjIyMSAzLjkxaDIuMTI0bC0xLjUzNyA0LjkxYy0uNTEzIDEuNTY0LS43MSAyLjY5LS41ODcgMy4zOTcuMDk4LjY4NC40MzkgMS4yMjIgMS4wMDIgMS41NjIuNTYyLjM0NSAxLjU2NS41MTUgMy4wMDYuNTE1IDEuMjIgMCAyLjU4OS0uMTQ4IDQuMDgtLjQ0bC43NTctMy43MTVjLS44MzMuMjQ1LTEuNDY2LjM2OC0xLjg4NC4zNjgtLjQ4NiAwLS43NTYtLjE3MS0uODU0LS40NjUtLjA0OC0uMTk1IDAtLjU4Ni4xOTctMS4xNzJsMS41NC00Ljk2aDMuMTUxbDEuMjIxLTMuOTFoLTMuMTI2eiIgY2xpcC1ydWxlPSJldmVub2RkIi8+Cjwvc3ZnPgo="
                                             alt="Copart" class="img-responsive copart-img" width="129px" height="53px">
                                    </a>

                                    <!-- for crashed Toys-->

                                    <!-- End for crashed Toys-->
                                    <!---->
                                    <div ng-if="!mobileShow" class="portal-info">Member Portal</div><!---->
                                </div>

                            </div>

                            <div class="searchdiv col-lg-9 col-md-9  col-sm-9 col-xs-9  text-center">
                                <form class="ng-pristine ng-valid" role="search" method="get" ng-submit="search()"
                                      id="search-form" name="">
                                    <div ng-controller="freeTextSearchController"
                                         place-text="Search Inventory by Make, Model, VIN, and More.."
                                         searchplaceholder="">
                                        <div class="col-md-9 col-sm-6 col-xs-6 pad0">
                                            <div class="input-group searchinputgroup">
                <span class="input-group-btn">
                    <button class="btn btn-default search-icon" type="submit" role="button" aria-label="Search"
                            ng-click="search()" data-uname="homepageHeadersearchsubmiticon">
                        <span class="header-sprite search-icon"></span>
                    </button>
                </span>
                                                <input type="text"
                                                       class="form-control my_textarea newsearch pl-5 ng-pristine ng-untouched ng-valid ng-empty"
                                                       placeholder="Search Inventory by Make, Model, VIN, and More.."
                                                       autocomplete="off" onfocus="this.placeholder = ''"
                                                       id="input-search" name="" ng-model="searchText" value=""
                                                       data-uname="homeFreeFormSearch"
                                                       uib-typeahead="suggestion | lowercase for suggestion in getSuggestion($viewValue)"
                                                       typeahead-min-length="3" typeahead-focus-first="false"
                                                       aria-autocomplete="list" aria-expanded="false"
                                                       aria-owns="typeahead-5-7437">
                                                <ul class="dropdown-menu ng-hide"
                                                    ng-show="isOpen() &amp;&amp; !moveInProgress"
                                                    ng-style="{top: position().top+'px', left: position().left+'px'}"
                                                    role="listbox" aria-hidden="true" uib-typeahead-popup=""
                                                    id="typeahead-5-7437" matches="matches" active="activeIdx"
                                                    select="select(activeIdx, evt)" move-in-progress="moveInProgress"
                                                    query="query" position="position"
                                                    assign-is-open="assignIsOpen(isOpen)" debounce="debounceUpdate">
                                                    <!---->
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-6 pad0 text-left">
                                            <button type="submit" ng-click="search()" role="button"
                                                    aria-label="Search Inventory"
                                                    data-uname="homepageHeadersearchsubmit"
                                                    class="btn btn-lightblue marginleft15 search-btn">
                                                Search Inventory
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <div class="col-lg-4 col-md-5 col-sm-5  col-xs-12 col-reg">
                            <!-- End register -->
                            <div class="header-top langdiv">
                                <ul class="list-in list-options text-right d-f a-i_c">
                                    <li class="language_popup" ng-click="togglePopup($event)">
                                        <button class="language_select btnlanguage d-f a-i_c"
                                                data-uname="homepageLanguageselect" role="button"
                                                aria-label="USA + English">
        <span class="globeicon px-5">
            <span class="header-sprite globe-icon"></span>
        </span>
                                            <span class="desktop-only">
            <span class="countryname">USA</span>
            <span class="pad10">|</span>
            <label class="cursor-pointer">
                English
            </label>
        </span>
                                            <span class="header-sprite arrow-icon"></span>
                                        </button>
                                    </li>
                                    <div class="pos-relative z-i-999 nowrap">
                                        <a class="btn btn-register" href="./doRegistration"
                                           site-catalyst="{'fname':'doTopNavigationRegister'}"
                                           data-uname="homePageRegister">
                                            Register
                                        </a>

                                        <a class="btn btn-sign-in" data-toggle="dropdown" data-uname="homePageSignIn"
                                           site-catalyst="{'fname':'doTopNavigationSignInMember'}" signinmenu="">
                                            Sign In
                                        </a>
                                        <div class="dropdown-menu signin sign-in-btn">
                                            <div class="boxlg d-f-c" data-uname="homePageModalWindow">
                                                <a class="btn btn-lblue" ng-href="./login" type="button"
                                                   data-uname="homePageMemberSignIn" pref-code="signInOptions"
                                                   access-value="member" href="./login">
                                                    Member
                                                </a>
                                                <a class="btn btn-lblue" ng-click="goToSellerLogin()" type="button"
                                                   data-uname="homePageSellerSignIn" pref-code="signInOptions"
                                                   access-value="seller">
                                                    Seller
                                                </a>
                                                <!-- hiding the counter man for MDS -->

                                            </div>
                                        </div>

                                    </div>


                                </ul>
                                <div id="languagehome" class="header_lang_dropdown ng-hide" ng-show="popupToggle">
                                    <div>
                                        <div class="langformat lang_section">
                                            <div class="text-center">
                                                <label class="settimecolor settimefont langDropdown_label"
                                                       data-uname="homepagelanguageHeading">
                                                    Website Language
                                                </label>
                                            </div>
                                            <div>
                                                <div>
                                                    <p class="can-you-read mt-10"
                                                       data-uname="homepageDdlanguageTextHeading">
                                                        Can You Read This Text?
                                                    </p>
                                                    <p class="description-text">Members all over the world come to
                                                        Copart because of our extensive inventory with more than 125,000
                                                        vehicles available for bidding each day we have something for
                                                        everyone.</p>
                                                </div>
                                                <div class="mt-20 d-f j-c_s-b">
                                                    <label class="lang_region_selection_block pos-relative">
                                                        <select class="form-control langDropdown_select ng-pristine ng-untouched ng-valid ng-not-empty"
                                                                data-uname="homepageDdlanguage" ng-model="selectedLang"
                                                                ng-change="changePopupLanguage(selectedLang)"
                                                                ng-options="obj.value as obj.name for obj in userConfig.langOptions">
                                                            <option label="English" value="string:en"
                                                                    selected="selected">English
                                                            </option>
                                                            <option label="Español" value="string:es">Español</option>
                                                            <option label="Français Canadien" value="string:fr-CA">
                                                                Français Canadien
                                                            </option>
                                                            <option label="العربية" value="string:ar">العربية</option>
                                                            <option label="Русский" value="string:ru">Русский</option>
                                                            <option label="Polski" value="string:pl">Polski</option>
                                                        </select>
                                                    </label>
                                                    <div class="flex-b-24">
                                                        <button site-catalyst="{'fname':'doTagSelectedLanguage'}"
                                                                class="yes-btn" data-uname="homepageDdlanguageYes"
                                                                ng-click="changeLanguage(selectedLang); popupToggle = false">
                                                            Yes
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div compile-cms-content="" parent-id="/content/us/en/region-popup"
                                             fragment-id="region-popup" cms-content-fragment="null"><!----><!---->
                                            <!---->
                                            <div ng-if="compileFragment" compile-trusted-html="fragmentedCms">
                                                <div class="region_section mt-40">
                                                    <div class="text-center"><label
                                                                class="settimecolor settimefont region_label"
                                                                data-uname="homepageRegionHeading">Website
                                                            Region </label></div>

                                                    <div>
                                                        <div>
                                                            <p class="description-text mt-5"
                                                               data-uname="homepageRegionDescription">Copart USA allows
                                                                you to purchase vehicles located inside the <strong
                                                                        style="color: #1254ff;">United States</strong>.
                                                                If you would like to view inventory in other locations
                                                                around the world, choose one from the Dropdown.</p>
                                                        </div>

                                                        <div class="mt-20">
                                                            <div class="custom_meta_dropdown copart_countries pos-relative user-select-none">
                                                                <button aria-expanded="false"
                                                                        class="dropdown-toggle region_selected_block pos-relative"
                                                                        data-toggle="dropdown" type="button"><span
                                                                            class="copart_country_items m-0-i"><img
                                                                                alt="Selected Country Flag"
                                                                                class="img-responsive max-w-42"
                                                                                data-entity-type="" data-entity-uuid=""
                                                                                src="https://www.copart.com/content/us.svg"> <span>USA</span> </span>
                                                                </button>

                                                                <div class="dropdown-menu">
                                                                    <ul class="list-style-none copart_country_list d-f j-c_s-b f-w_w">
                                                                        <li class="copart_country_items"><a
                                                                                    href="https://www.copart.com"><img
                                                                                        alt="United States"
                                                                                        class="img-responsive max-w-42"
                                                                                        data-entity-type=""
                                                                                        data-entity-uuid=""
                                                                                        src="https://www.copart.com/content/us.svg">
                                                                                <span>USA</span> </a></li>
                                                                        <li class="copart_country_items"><a
                                                                                    href="https://www.copart.ca"><img
                                                                                        alt="CANADA"
                                                                                        class="img-responsive max-w-42"
                                                                                        data-entity-type=""
                                                                                        data-entity-uuid=""
                                                                                        src="https://www.copart.com/content/ca.svg">
                                                                                <span>CANADA</span> </a></li>
                                                                        <li class="copart_country_items"><a
                                                                                    href="https://www.copart.co.uk"><img
                                                                                        alt="UK"
                                                                                        class="img-responsive max-w-42"
                                                                                        data-entity-type=""
                                                                                        data-entity-uuid=""
                                                                                        src="https://www.copart.com/content/uk.svg">
                                                                                <span>UK</span> </a></li>
                                                                        <li class="copart_country_items"><a
                                                                                    href="https://www.copart.ie"><img
                                                                                        alt="IRELAND"
                                                                                        class="img-responsive max-w-42"
                                                                                        data-entity-type=""
                                                                                        data-entity-uuid=""
                                                                                        src="https://www.copart.com/content/ie.svg">
                                                                                <span>IRELAND</span> </a></li>
                                                                        <li class="copart_country_items"><a
                                                                                    href="https://www.copartmea.com"><img
                                                                                        alt="UAE(Dubai)"
                                                                                        class="img-responsive max-w-42"
                                                                                        data-entity-type=""
                                                                                        data-entity-uuid=""
                                                                                        src="https://www.copart.com/content/ae.svg">
                                                                                <span>UAE(Dubai)</span> </a></li>
                                                                        <li class="copart_country_items"><a
                                                                                    href="https://www.copartmea.com"><img
                                                                                        alt="BAHRAIN"
                                                                                        class="img-responsive max-w-42"
                                                                                        data-entity-type=""
                                                                                        data-entity-uuid=""
                                                                                        src="https://www.copart.com/content/bh.svg">
                                                                                <span>BAHRAIN</span> </a></li>
                                                                        <li class="copart_country_items"><a
                                                                                    href="https://www.copartmea.com"><img
                                                                                        alt="OMAN"
                                                                                        class="img-responsive max-w-42"
                                                                                        data-entity-type=""
                                                                                        data-entity-uuid=""
                                                                                        src="https://www.copart.com/content/om.svg">
                                                                                <span>OMAN</span> </a></li>
                                                                        <li class="copart_country_items"><a
                                                                                    href="https://www.copart.de"><img
                                                                                        alt="GERMANY"
                                                                                        class="img-responsive max-w-42"
                                                                                        data-entity-type=""
                                                                                        data-entity-uuid=""
                                                                                        src="https://www.copart.com/content/de.svg">
                                                                                <span>GERMANY</span> </a></li>
                                                                        <li class="copart_country_items"><a
                                                                                    href="https://www.copart.es"><img
                                                                                        alt="SPAIN"
                                                                                        class="img-responsive max-w-42"
                                                                                        data-entity-type=""
                                                                                        data-entity-uuid=""
                                                                                        src="https://www.copart.com/content/es.svg">
                                                                                <span>SPAIN</span> </a></li>
                                                                        <li class="copart_country_items"><a
                                                                                    href="https://www.copart.fi"><img
                                                                                        alt="FINLAND"
                                                                                        class="img-responsive max-w-42"
                                                                                        data-entity-type=""
                                                                                        data-entity-uuid=""
                                                                                        src="https://www.copart.com/content/fi.svg">
                                                                                <span>FINLAND</span> </a></li>
                                                                        <li class="copart_country_items">
                                                                            <a href="https://www.copart.com.br">
                                                                                <img alt="BRAZIL"
                                                                                     class="img-responsive max-w-42"
                                                                                     data-entity-type=""
                                                                                     data-entity-uuid=""
                                                                                     src="https://www.copart.com/content/br.svg">
                                                                                <span>BRAZIL</span>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mt-20"><a class="header_dropdown_int_buyers_link"
                                                                              href="/content/us/en/landing-page/international-buyers"
                                                                              target="_blank">More information for
                                                                International Buyers › </a></div>
                                                    </div>
                                                </div>

                                            </div><!----></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Show only in mobile, Header  Mobile View -->
            <div class="mobileScreen">
                <div class="row mobiletopheader">
                    <div class="d-f j-c_s-b a-i_c min-h-75">
                        <div class="padLeft0">
                            <div class="logodiv crashedtoys-logodiv">
                                <div class="logo m-0-i">
                                    <a href="./" ng-click="routeReload('/')" data-uname="copartLogo"
                                       class="logo copart-img ml-20">
                                        <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjkiIGhlaWdodD0iNDgiIGZpbGw9Im5vbmUiIHZpZXdCb3g9IjAgMCAxMjkgNDgiPgogICAgPG1hc2sgaWQ9Imo1YWp1a29vbmEiIHdpZHRoPSIxMDciIGhlaWdodD0iNDgiIHg9IjIyIiB5PSIwIiBtYXNrVW5pdHM9InVzZXJTcGFjZU9uVXNlIj4KICAgICAgICA8cGF0aCBmaWxsPSIjZmZmIiBkPSJNMjIuMjgzIDQ4SDEyOC40MlYwSDIyLjI4M3Y0OHoiLz4KICAgIDwvbWFzaz4KICAgIDxnIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBtYXNrPSJ1cmwoI2o1YWp1a29vbmEpIj4KICAgICAgICA8cGF0aCBmaWxsPSIjMTUxMzE3IiBkPSJNMTI3LjExMyAyMy45OTZjLTYuNDQ0IDEzLjI0Mi0zNC45NDYgMjQuMDIyLTYzLjQ0NiAyNC4wMjItMjguNTI4IDAtNDYuNTItMTAuNzgtNDAuMDc2LTI0LjAyMkMzMC4wMSAxMC44MDYgNTguNTM2IDAgODcuMDQgMGMyOC41MjcgMCA0Ni41MTUgMTAuODA2IDQwLjA3NCAyMy45OTZ6Ii8+CiAgICAgICAgPHBhdGggZmlsbD0iIzY0NzM4NyIgZD0iTTg1Ljk4MiAxLjIwOGMyNy4zNjggMCA0NC42MyAxMC4xMDggMzguNDQ4IDIyLjQ0Ny02LjE4MiAxMi4zODctMzMuNTI3IDIyLjQ3Mi02MC44NyAyMi40NzItMjcuMzY5IDAtNDQuNjMtMTAuMDg1LTM4LjQ0Ny0yMi40NzJDMzEuMjcgMTEuMzE1IDU4LjYzOCAxLjIwOCA4NS45ODIgMS4yMDh6Ii8+CiAgICA8L2c+CiAgICA8cGF0aCBmaWxsPSIjMTUxMzE3IiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik04MS42MiAzLjMxN2MyNC43MjUgMCA0MC4yODggOS4wNCAzNC42NjQgMjAuMDk1LTUuNjAxIDExLjA3OC0zMC4zNSAyMC4xMi01NS4wMjcgMjAuMTItMjQuNzI2IDAtNDAuMjktOS4wNDItMzQuNjg5LTIwLjEyIDMuNjM2LTcuMTI2IDE1LjE1LTEzLjM4MSAyOS40NzctMTYuOTY4IDEuMDE4LS4yNjggMi4wNi0uNDg1IDMuMTAzLS43MjggNy4xMjctMS41MjYgMTQuODExLTIuNCAyMi40NzEtMi40eiIgY2xpcC1ydWxlPSJldmVub2RkIi8+CiAgICA8cGF0aCBmaWxsPSIjMTI1NEZGIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMTMuOTczIDE0LjM1N2MxLjIyOSAyLjAxNCAxLjQ1NyA0LjI4LjQ4MyA2LjY1Ni0uOTExIDEuNzk5LTEuOTU3IDMuMjQ3LTMuNTE1IDQuNjcxLTguMDgzIDcuMzY2LTI2LjI3MiAxMi44NTktNDQuNDM2IDEyLjg1OS0yMS40MTcgMC0zNC45MjMtNy42MzMtMzAuMDQ0LTE2Ljk5QzM5LjgxMyAxNS4xMSA1MS4xNzUgOS4yNTIgNjQuNzggNi4zNmMxLjYtLjM0IDMuMjI3LS42MDcgNC44NzgtLjg1LTMuMjAxLjMxNi02LjMzMy43NzctOS40MTIgMS40MzMtMTUuMDg0IDMuMjgyLTI3Ljg3NSA4LjU4Mi0zMS41OTggMTUuOTIyQzIzLjI1IDMzLjUzNiAzOC4yMSA0Mi4yMzcgNjEuOTcgNDIuMjM3YzE5LjQ2OCAwIDM4Ljk2NC01LjgzMyA0OC4zMjktMTMuODMgMS4wNTgtLjg5OCAyLjI5Ni0yLjE2NiAzLjI2Ny0zLjUxOC40MDEtLjU0Ni43NTMtMS4xIDEuMDQ2LTEuNjU5IDEuNjc2LTMuMjA5IDEuMzU2LTYuMjQ3LS42NC04Ljg3M3oiIGNsaXAtcnVsZT0iZXZlbm9kZCIvPgogICAgPHBhdGggZmlsbD0iIzM2M0EzRiIgZmlsbC1ydWxlPSJldmVub2RkIiBkPSJNMTQuMzMgMjEuNzY4Yy0uNDM4LjY4OS0uODc4IDEuMTk3LTEuMzA3IDEuNTEybC0uMDE3LjAxM2MtLjQ5NS4zOS0xLjE0My41ODctMS45MjcuNTg3LS41MSAwLS44NTktLjEwMi0uOTU2LS4yNzQtLjAxLS4wMjctLjI0LS42Ni40NjktMi45NDMuNDY5LTEuNDQ3IDEuMDE1LTIuNDggMS42MjQtMy4wNzIuNzU2LS43NTcgMS41NjctMS4xMDkgMi41NTItMS4xMDkuMzEgMCAuNTY3LjA0OC43MjQuMTM0LjExNy4wNjcuMjEuMTE5LjI0Ni41NDVsLjE0NCAxLjY2N2g3Ljg1OGMtLjQ5My42NTQtLjkxNSAxLjM1OC0xLjI1NCAyLjA5NmgtNy42MTlsLS41MzcuODQ0em0xOS4wNS40ODVjLS4yOTIuOTMzLS42NyAxLjYxMS0xLjA5MiAxLjk1OS0uNDMuMzQ1LS43OTMuNDk5LTEuMTc0LjQ5OS0uMDggMC0uMTMtLjAwNy0uMTYtLjAxNC0uMDE2LS4xMDMtLjA0MS0uNDY5LjIyMS0xLjMwNS4yNS0uODUxLjYxOS0xLjQ5OCAxLjA2My0xLjg3LjQwOC0uMzI1Ljc4NS0uNDcgMS4yMjMtLjQ3LjA1MyAwIC4wOTEuMDAzLjExOC4wMDYuMDE2LjExNi4wMjYuNDYtLjE5OCAxLjE5NXptMTguNzY4LS4wMTljLS4yNDIuODI0LS41NjYgMS40MzktLjkxMyAxLjczLS4yODQuMjQtLjUyMy4zNTUtLjczMi4zNTVsLS4wNTgtLjAwMWMuMDA1LS4xNDQuMDM4LS40MzIuMTg3LS45MTQuMzI0LTEuMDU2LjY5LTEuNTEuOTQtMS43MDQuMjg3LS4yMzMuNTMzLS4zNTYuNzkyLS4zOTItLjAxNS4xNzUtLjA2Ni40NjUtLjIxLjkwN2wtLjAwNi4wMTl6bTU1LjIyMS04LjIwMWwyLjA4NC02LjY4LTEwLjcxMiA0LjQ0My0uMjc0LjExNC0uNjYxIDIuMTIzaC0yLjEyNGwtLjA4Ny4yNzhjLS44OTYtLjQtMS43OC0uNTk2LTIuNzAxLS41OTYtLjcyMyAwLTEuNDA5LjEwNy0yLjA0LjMxOGgtOC4zOWwtMS41ODcgNS4wNzFjLjAwMi0uMjA3LS4wMDQtLjQyLS4wMTgtLjYzNC0uMDc1LTEuMS0uNDYyLTIuMDc2LTEuMDg4LTIuNzQ0LS43OC0uODkyLTEuODgxLTEuNDY3LTMuMjcxLTEuNzEtMS4wMy0uMjA1LTIuNDI4LS4zLTQuMzk5LS4zLTEuMTUzIDAtMi4yNDEuMDc3LTMuMzI4LjIzOS0xLjIyOS4xOS0yLjEzLjQzOS0yLjgzNi43ODMtMS4wNDYuNDUzLTEuOTEzIDEuMDA1LTIuNTc2IDEuNjQtLjU4NC41MzItMS4xMTggMS4yMS0xLjYzMSAyLjA3LS4xMDUtLjUzOC0uMjY2LTEuMDU3LS40OC0xLjU0NC0uNjc5LTEuNDU0LTIuMjA5LTMuMTg5LTUuNjQtMy4xODktLjg0NyAwLTEuNjQ3LjEwNy0yLjQ0Mi4zMjZsLjAwMi0uMDA4aC05LjE2M2wtMS4zMTkgNC4yMTRjLS4xNTMtLjQzNi0uMzU0LS44Ni0uNi0xLjI2LTEuMzgtMi4xNzEtMy45MjktMy4yNzItNy41NzYtMy4yNzItMi45OTIgMC01LjY1NS44MDItNy45MTQgMi4zODMtLjQxNi4yOTMtLjgxNS42MDctMS4xODYuOTM0di0uMDI3YzAtMy4xMDgtLjg0NC01LjMwNy0yLjU4LTYuNzIyLTEuNTEyLTEuMjktMy43NDItMS45MTgtNi44MTctMS45MTgtMy42NjkgMC02Ljg5OC45ODgtOS41OTggMi45MzhDMy43MyAxMy4yNiAxLjgyIDE2LjA0Ny43MzkgMTkuNTg3Yy0uODc1IDIuNzY1LS45NzQgNS4xMzctLjI5MiA3LjA1NC43MyAyLjAwMSAxLjkzOSAzLjQyNyAzLjU5MSA0LjIzOCAxLjQ4NS43NDIgMy41MDkgMS4xMTggNi4wMTUgMS4xMTggMi4wODUgMCAzLjkwOC0uMjkyIDUuNDItLjg3IDEuNTI2LS41NTQgMi45Ny0xLjQyIDQuMjk1LTIuNTcyLjYwNS0uNTQ0IDEuMjctMS4yNjQgMS44NzctMi4wMzMuMzI0IDEuNTA1IDEuMTEzIDIuNTY4IDEuNzI0IDMuMiAxLjQ5MyAxLjQ5MyAzLjcxMiAyLjI1IDYuNTk4IDIuMjUgMy4wMzEgMCA1LjcyNi0uODA4IDguMDA4LTIuNDA1LjU2Ni0uMzk2IDEuMDk1LS44MzIgMS41NzUtMS4yOTZsLTIuNzIgOC42ODdoOS41ODZsMS43MzctNS41MTVjLjgzNi4zNTIgMS43OTkuNTMgMi44NjMuNTMgMi4yMzIgMCA0LjMyLS43NDcgNi4yMDctMi4yMi41MzktLjQyNCAxLjA0Ni0uOTExIDEuNTA4LTEuNDUuMTcuNjk1LjUxIDEuMzI3IDEuMDEgMS44ODEgMS4wNTMgMS4yMDQgMi43MDcgMS43ODkgNS4wNTUgMS43ODkgMS41NSAwIDMuMDAxLS4yMzIgNC4zMTYtLjY5LjIxNi0uMDc2LjQzNy0uMTYzLjY1OC0uMjZsLjIxLjYzMWgxNi41MzJsMS44NjctNS45NjdjLjc3Ny0yLjQ3IDEuNDg5LTMuNDU3IDEuODQzLTMuODI0LjM0MS0uMzA1LjU3LS4zNDcuNzY2LS4zNDcuMDIzIDAgLjIzNS4wMDcuNzUzLjIzNWwxLjI4OS41Ny42MTItLjczOCAxLjgwMy4wMDQtLjc5MiAyLjUyOWMtLjYxOCAxLjg4My0uODI3IDMuMjI2LS42NTYgNC4yMjYuMTc5IDEuMjIzLjgzOCAyLjIzNSAxLjg1NiAyLjg1Ljg3LjUzMyAyLjEyNy43OCAzLjk1Ny43OCAxLjMzNSAwIDIuODI0LS4xNTggNC40MjktLjQ3MmwuNzc5LS4xNTIuNDE1LS4wODIgMS42MDQtNy44NzEtMi44NzEuODQyLjgyMi0yLjY0OGgzLjE0OWwyLjM1OS03LjU1NmgtMy4xMjd6IiBjbGlwLXJ1bGU9ImV2ZW5vZGQiLz4KICAgIDxwYXRoIGZpbGw9IiMxNTEzMTciIGZpbGwtcnVsZT0iZXZlbm9kZCIgZD0iTTE1LjEzMyAyMS40MDhsLS4zOTQuNjJjLS40Ny43NC0uOTUxIDEuMjkyLTEuNDMzIDEuNjQ2LS41ODIuNDU3LTEuMzMyLjY5LTIuMjI3LjY5LS43MiAwLTEuMTg0LS4xNzctMS4zODEtLjUyNy0uMDY1LS4xMTMtLjM1LS44MDUuNDMtMy4zMTcuNDk1LTEuNTI3IDEuMDg0LTIuNjMgMS43NTEtMy4yNzguODM3LS44MzggMS43ODItMS4yNDUgMi44OS0xLjI0NS40MDEgMCAuNzIzLjA2Ni45NTcuMTk0LjI3Ni4xNTYuNDQ2LjM1OC40OTUuOTI4bC4xMDUgMS4yMjRoOC40NDVjLS44MzQuOTItMS40OTMgMS45NDUtMS45NyAzLjA2NWgtNy42Njh6bTE4LjcxMi45ODVjLS4zMjggMS4wNC0uNzQ4IDEuNzc5LTEuMjQ3IDIuMTktLjUyLjQxOC0uOTkyLjYxMi0xLjQ4NC42MTItLjQzOCAwLS41MjctLjE1My0uNTg3LS4yNTYtLjAyMy0uMDM4LS4yMTUtLjQxNC4xODYtMS42OTQuMjgtLjk1LjY4OC0xLjY1NSAxLjIxMy0yLjA5My40OTktLjQuOTg3LS41ODYgMS41MzUtLjU4Ni40MDMgMCAuNDc0LjEyLjU1LjI1LjAwOS4wMTQuMjA4LjM1Mi0uMTY2IDEuNTc3em0xNi4zMjQuODY3Yy4yODYtLjkzLjY2OC0xLjYwMyAxLjEwOC0xLjk0NS40MjEtLjM0My44Mi0uNTA0IDEuMjUzLS41MDQuMjIzIDAgLjIzNy4wMjcuMjg5LjEyLjA0NC4wNzYuMS41LS4yMDYgMS40MzgtLjI3Mi45MjktLjY0IDEuNjEtMS4wNjcgMS45NjYtLjM3NC4zMTUtLjcxNS40NjgtMS4wNDMuNDY4LS4zOTcgMC0uNDYzLS4xMS0uNTA3LS4xODMtLjAwMS0uMDAzLS4xNTItLjMxLjE3My0xLjM2em0xMC43ODQtLjIzOGMuMTk1LS42OTEuMzI3LTEuMzY1LjM5NC0yLjAxbDIuNTQzLjI1OWMtLjkyNS4zMTQtMS42NzcuNzAxLTIuMjg1IDEuMTc2LS4yMy4xNzktLjQ0OC4zNzEtLjY1Mi41NzV6bTQ2LjgyOC0xLjkxOGwyLjA1Ni02LjU4N2gtMy4xMjdsMS45NzItNi4zMi05LjgyIDQuMDczLS42OTkgMi4yNDdoLTIuMTI1bC0uMTUuNDc4Yy0xLS41MzUtMS45OC0uNzk1LTIuOTk0LS43OTUtLjcgMC0xLjM1OC4xMDYtMS45Ni4zMTdoLTguMTEzbC00LjgxIDE1LjM2Ny0uMDQ2LS4yNzFjLS4xMDctLjY1LS4xMTgtLjk3Mi0uMTA4LTEuMTI3LjAxMS0uMTYzLjA3LS41MS4yODUtMS4yMzZsMS45MjUtNi4xNmMuMjY5LS44MTEuMzctMS42NTguMzA4LTIuNTg5LS4wNjgtLjk5NC0uNDEtMS44NjUtLjk2Mi0yLjQ0OS0uNzA2LS44MS0xLjcxNS0xLjMzNC0yLjk5NC0xLjU1OC0xLjAwOC0uMi0yLjM4LS4yOTQtNC4zMTgtLjI5NC0xLjEyOSAwLTIuMTk0LjA3Ni0zLjI1Ny4yMzQtMS4xODQuMTgzLTIuMDQ0LjQyLTIuNzAxLjc0Mi0xIC40MzItMS44MjMuOTU0LTIuNDQ1IDEuNTUtLjY2My42MDUtMS4yNjUgMS40MTUtMS44MzkgMi40NzZsLS40NjQuODU4Yy4wMDEtMS4wNzYtLjE5Ni0yLjA3LS41OS0yLjk2My0uNjE3LTEuMzIyLTIuMDItMi44OTgtNS4xOTUtMi44OTgtMS4xMDcgMC0yLjE1My4xOTYtMy4xODQuNTk4bC4wODctLjI4aC04LjE1bC0xLjc5IDUuNzE2Yy0uMDQ1LTEuMDg4LS4zNDYtMi4wOTEtLjg5OS0yLjk5NS0xLjI4Mi0yLjAxNi0zLjY5Mi0zLjAzOC03LjE2Mi0zLjAzOC0yLjg5IDAtNS40Ni43NzEtNy42MzcgMi4yOTUtLjcyNi41MS0xLjM3NiAxLjA3MS0xLjk0OCAxLjY3OHYtMS4xNjljMC0yLjk1My0uNzg2LTUuMDI5LTIuNC02LjM0NS0xLjQyNy0xLjIxOC0zLjU1OC0xLjgxLTYuNTEyLTEuODEtMy41NjUgMC02LjY5OS45NTgtOS4zMTUgMi44NDctMi41OTggMS44OTMtNC40NDcgNC41OTUtNS40OTcgOC4wMzEtLjg0NCAyLjY2NS0uOTQ1IDQuOTM2LS4yOTkgNi43NTIuNjg1IDEuODc3IDEuODExIDMuMjEgMy4zNDYgMy45NjQgMS40Mi43MSAzLjM3MyAxLjA3IDUuODAzIDEuMDcgMi4wMjUgMCAzLjc5LS4yODMgNS4yNDYtLjgzOCAxLjQ3Ni0uNTM3IDIuODcyLTEuMzczIDQuMTUyLTIuNDg3Ljg1MS0uNzY1IDEuNzkxLTEuODYgMi41My0yLjkzNi4wNTUgMS45NzMuOTU1IDMuMzI2IDEuNzM3IDQuMTM0IDEuMzk0IDEuMzk0IDMuNDk3IDIuMTAyIDYuMjQ5IDIuMTAyIDIuOTMgMCA1LjUzMS0uNzggNy43My0yLjMyIDEuMjI4LS44NTkgMi4yNDYtMS44ODMgMy4wMzYtMy4wNTRsLTMuMjQzIDEwLjM2aDguNTdsMS43OTYtNS43Yy4xMDguMDU0LjIxOC4xMDYuMzI4LjE1My44MTIuMzcyIDEuNzY0LjU2IDIuODMxLjU2IDIuMTIyIDAgNC4xMS0uNzEyIDUuOTEtMi4xMTcuODE1LS42NCAxLjU0NC0xLjQyNSAyLjE3MS0yLjMzNy0uMDYzIDEuMzI1LjQ5IDIuMjU1IDEuMDAzIDIuODI1Ljk2IDEuMDk3IDIuNDk3IDEuNjMgNC42OTcgMS42MyAxLjQ5NSAwIDIuODkzLS4yMjMgNC4xNTgtLjY2My4zNTgtLjEyNy43MjgtLjI4NCAxLjEwNC0uNDdsLjI3MS44MTRoMTUuODI3bDEuNzYtNS42MjdjLjc3NC0yLjQ1OCAxLjUtMy41NSAxLjk2Ny00LjAyNy4zNy0uMzM1LjcxMS0uNDg0IDEuMTA1LS40ODQuMDk3IDAgLjM4My4wMjcuOTQ4LjI3NmwuOTU1LjQyMi41MjQtLjYzMiAyLjY4OS4wMDYtLjk4OCAzLjE1NWMtLjU5NiAxLjgxNy0uNzk5IDMuMDktLjY0IDQuMDA2LjE1NiAxLjA4LjczNCAxLjk3MyAxLjYyNyAyLjUxMi43OTMuNDg2IDEuOTcuNzEyIDMuNzA2LjcxMiAxLjMwNCAwIDIuNzYyLS4xNTYgNC4zMzYtLjQ2NGwuODc3LS4xNzIgMS4zOC02Ljc2Ni0yLjEzMi42MjZjLS4zMDYuMDg5LS41ODIuMTYtLjgyNi4yMTNsMS4xODctMy44MjFoMy4xNXoiIGNsaXAtcnVsZT0iZXZlbm9kZCIvPgogICAgPHBhdGggZmlsbD0iI2ZmZiIgZmlsbC1ydWxlPSJldmVub2RkIiBkPSJNMTguNTU2IDI3LjE5MmMxLjIyMS0xLjA5OSAyLjY2NC0yLjkzMSAzLjMyMy00LjQ0NmgtNi4wMWMtLjUxNC44MDctMS4xIDEuNTE1LTEuNzM1IDEuOTgtLjgwNy42MzQtMS44MzMuOTc3LTMuMDU1Ljk3Ny0xLjI0NSAwLTIuMS0uNDE1LTIuNTQxLTEuMTk4LS40NjQtLjgwNS0uMzQyLTIuMjczLjMxNy00LjM5OC41NjMtMS43MzQgMS4yNDctMy4wMDUgMi4wNzctMy44MTEgMS4xLTEuMSAyLjM5NS0xLjYzOCAzLjgzNy0xLjYzOC42MzUgMCAxLjE3Mi4xMjMgMS42MTMuMzY3Ljc4MS40NCAxLjEgMS4xMjQgMS4xNzMgMS45OGg2LjAzNGMwLTEuOTA2LS4zNDItNC4wMzMtMS45My01LjMyNy0xLjE3My0xLjAwMi0zLjA1NS0xLjQ5LTUuNjQ0LTEuNDktMy4zMjMgMC02LjE1Ny44OC04LjUyNyAyLjU5LTIuMzQ2IDEuNzA5LTQuMDMxIDQuMTU0LTUuMDA5IDcuMzUzLS43NTggMi4zOTUtLjg1NiA0LjM3NC0uMzE3IDUuODg5LjU2MSAxLjUzOSAxLjQ0MSAyLjYxNCAyLjY4NyAzLjIyNSAxLjIyMi42MTEgMi45NTYuOTI5IDUuMjA0LjkyOSAxLjgzMyAwIDMuNDQ1LS4yNDUgNC43ODktLjc1NyAxLjM0My0uNDg5IDIuNTktMS4yNDYgMy43MTQtMi4yMjV6bTE0Ljg4LTEuNTYzYy43MDktLjU4NSAxLjI3LTEuNTE0IDEuNjg2LTIuODM0LjM2Ni0xLjE5Ny4zOS0yLjEwMi4wMjUtMi42ODgtLjM0My0uNTg3LS45MDUtLjg3OS0xLjY4Ni0uODc5LS44NTYgMC0xLjYzOC4yOTItMi4zNzEuODgtLjczMy42MS0xLjI5NSAxLjUxNC0xLjY2MSAyLjc2LS4zOTEgMS4yNDctLjQxNiAyLjE1MS0uMDQ5IDIuNzYuMzQyLjU4OC45MjkuOTA2IDEuNzM0LjkwNi44MzIgMCAxLjU4OC0uMzE4IDIuMzIxLS45MDV6bTEuMDc2LTEwLjA5YzMuMDA0IDAgNS4wMDguODA1IDYuMDMzIDIuNDE4LjgwNyAxLjMyLjkyOSAyLjkzMy4zMTggNC44MzgtLjY4MyAyLjE1LTEuOTc5IDMuOTEtMy45MzMgNS4yNzgtMS45NTUgMS4zNjgtNC4yNzcgMi4wNzctNi45NjMgMi4wNzctMi40MiAwLTQuMTgtLjU4Ny01LjMwMi0xLjcxLTEuMzctMS40MTctMS43MS0zLjI3NS0uOTc4LTUuNTczLjY1OS0yLjEyNCAxLjk3OS0zLjg4NCAzLjk1OS01LjI3NyAxLjk1My0xLjM2NyA0LjI1LTIuMDUyIDYuODY2LTIuMDUyem0xOS4zODYgNy4yMDhjLS4zNjYgMS4yNDUtLjg3OSAyLjEtMS40OSAyLjYxMy0uNjEuNTE0LTEuMjQ1Ljc4My0xLjkwNS43ODMtLjc1OCAwLTEuMzItLjI2OS0xLjYzOC0uODA4LS4zNDItLjUzNi0uMzE2LTEuMzY3LjAyNC0yLjQ2OC4zNjgtMS4xOTUuODgtMi4wNzYgMS41NDEtMi41ODguNjU5LS41MzggMS4zNDMtLjgwNiAyLjEtLjgwNi42NiAwIDEuMTUuMjQ0IDEuNDQzLjc4LjMxNi41MzguMjkzIDEuMzctLjA3NSAyLjQ5NHptNS42OTUtNS4wODRjLS42NjItMS40MTYtMS45OC0yLjEyNS0zLjk4My0yLjEyNS0xLjAwMiAwLTEuOTguMTk1LTIuOTU3LjYxMi0uNzA5LjI5Mi0xLjU2NC44NzgtMi41OSAxLjc1OGwuNjM1LTIuMDUyaC01LjM1MWwtNi4wMzUgMTkuMjhoNS43NjdsMi4xMDItNi42NzJjLjM5LjU2Mi45MjguOTc3IDEuNTYzIDEuMjQ2LjYzNS4yOTIgMS4zOTIuNDQgMi4yNzIuNDQgMS44MDggMCAzLjUxOS0uNjEyIDUuMDgyLTEuODMzIDEuNTg4LTEuMjQ2IDIuNzM3LTMuMDA1IDMuNDctNS4zMjcuNjYtMi4xMjUuNjYtMy44ODQuMDI1LTUuMzI3em0xMS43IDguMDE2Yy4zNjctLjQxNi42MzctLjk3OC44NTgtMS42MzdsLjI2Ni0uODU2Yy0uODc5LjI2OC0xLjc1Ny40ODgtMi42ODYuNzA4LTEuMjQ3LjI5NC0yLjA3Ny41NjMtMi40NjcuODU2LS40MTcuMjk0LS42ODQuNjEtLjc4My45NzgtLjE0Ny40MTUtLjA5Ny43NTcuMTQ4IDEuMDI1LjIxOC4yNy42MzUuNDE2IDEuMjQ0LjQxNi42NjIgMCAxLjI5NS0uMTQ2IDEuOTMxLS40NC42MzUtLjI5NCAxLjEyMy0uNjM2IDEuNDktMS4wNXptNy4xMS04Ljc0NmMuMzY4LjM5LjU4OC45NTIuNjM3IDEuNjYuMDQ4LjczMy0uMDI2IDEuNDE3LS4yNDMgMi4wNzdsLTEuOTMzIDYuMTgxYy0uMTk0LjY2LS4zMTcgMS4xNzMtLjM0MiAxLjU0LS4wMjQuMzY2LjAyNS44NTQuMTIyIDEuNDRoLTUuMzVjLS4xMjMtLjM2OC0uMTctLjYzMy0uMTctLjgzIDAtLjE5Ni4wMjMtLjQ4OS4wNzItLjg4LS45NTEuNjYtMS44NTcgMS4xNDktMi42ODggMS40NDItMS4xMjQuMzktMi4zNy41ODctMy43MTIuNTg3LTEuNzg0IDAtMy4wMDUtLjM5MS0zLjY5LTEuMTczLS42ODMtLjc1OC0uODU0LTEuNzEtLjQ4OC0yLjg1OC4zMTgtMS4wNS45MjktMS45MzEgMS44MDgtMi42MTUuODc5LS42ODYgMi4yNzItMS4xOTggNC4xNzgtMS41MTYgMi4yNzEtLjQxMyAzLjczOC0uNzA4IDQuNDQ2LS44NTQuNjgzLS4xNzEgMS40MTgtLjM5MSAyLjIyMy0uNjM1LjE5Ny0uNjYuMTk3LTEuMTI1IDAtMS4zOTMtLjIyLS4yNy0uNjg0LS4zOTEtMS40MTYtLjM5MS0uOTUzIDAtMS42ODYuMTQ2LTIuMjQ4LjQxNS0uNDQuMjItLjg1NS42MzYtMS4yNzMgMS4yNDZsLTUuMy0uNTM3Yy40OS0uOTA1IDEuMDAzLTEuNjEzIDEuNTY1LTIuMTI1LjUzOC0uNTE0IDEuMjQ1LS45NTQgMi4xLTEuMzIuNTg1LS4yOTMgMS4zOTItLjQ5IDIuMzQ2LS42MzYuOTc4LS4xNDYgMS45NzgtLjIyIDMuMDU0LS4yMiAxLjcwOSAwIDMuMDguMDc0IDQuMDU1LjI3Ljk3Ny4xNyAxLjczNS41MzYgMi4yNDcgMS4xMjV6bTUuNDAxLTEuMDc3aDUuMzVsLS43MDcgMi4yNzJjLjgzLS45NzcgMS41ODgtMS42NjEgMi4yNDgtMi4wMjcuNjYtLjM5MiAxLjM5MS0uNTYzIDIuMTk5LS41NjMuODU1IDAgMS42ODUuMjQ1IDIuNTQuNzMzbC0yLjk1NyAzLjgxMmMtLjYxLS4yNy0xLjA5OC0uMzktMS40ODktLjM5LS43NTcgMC0xLjQxOC4yOTEtMi4wMy44NTUtLjgyOC44My0xLjYxMiAyLjM0Ny0yLjMxOCA0LjU5MmwtMS40NjggNC42OTFINzkuNDNsNC4zNzUtMTMuOTc1em0yMS4wODcgMGwxLjY2MS01LjMyNi02LjU5OCAyLjczNi0uODA2IDIuNTloLTIuMTI2bC0xLjIyMSAzLjkxaDIuMTI0bC0xLjUzNyA0LjkxYy0uNTEzIDEuNTY0LS43MSAyLjY5LS41ODcgMy4zOTcuMDk4LjY4NC40MzkgMS4yMjIgMS4wMDIgMS41NjIuNTYyLjM0NSAxLjU2NS41MTUgMy4wMDYuNTE1IDEuMjIgMCAyLjU4OS0uMTQ4IDQuMDgtLjQ0bC43NTctMy43MTVjLS44MzMuMjQ1LTEuNDY2LjM2OC0xLjg4NC4zNjgtLjQ4NiAwLS43NTYtLjE3MS0uODU0LS40NjUtLjA0OC0uMTk1IDAtLjU4Ni4xOTctMS4xNzJsMS41NC00Ljk2aDMuMTUxbDEuMjIxLTMuOTFoLTMuMTI2eiIgY2xpcC1ydWxlPSJldmVub2RkIi8+Cjwvc3ZnPgo="
                                             alt="Copart" class="img-responsive copart-img" width="129px" height="53px">
                                    </a>

                                    <!-- for crashed Toys-->

                                    <!-- End for crashed Toys-->

                                    <!--if the data i creasj then it will hel me out -->
                                    <div ng-if="!mobileShow" class="portal-info">Member Portal</div><!---->
                                </div>

                            </div>
                        </div>
                        <div class="d-f flex-b-50">
                            <ul class="list-inline d-f-i j-c_s-a a-i_c w100 m-0-i pr-10 z-i-999">
                                <li class="mobilesearchicon p-0-i m-0-i line-h-10 pos-relative">
                                    <div class="flagdrop mobileflagdrop" ng-click="togglePopup($event)">
        <span class="m-0-i mobile-header-globe-icon nowrap">
                <span class="header-sprite globe-icon"></span>
                <span class="header-sprite arrow-icon m-0-i"></span>
        </span>
                                    </div>
                                    <div id="languagehome" class="header_lang_dropdown ng-hide" ng-show="popupToggle">
                                        <div>
                                            <div class="langformat lang_section">
                                                <div class="text-center">
                                                    <label class="settimecolor settimefont langDropdown_label"
                                                           data-uname="homepagelanguageHeading">
                                                        Website Language
                                                    </label>
                                                </div>
                                                <div>
                                                    <div>
                                                        <p class="can-you-read mt-10"
                                                           data-uname="homepageDdlanguageTextHeading">
                                                            Can You Read This Text?
                                                        </p>
                                                        <p class="description-text">Members all over the world come to
                                                            Copart because of our extensive inventory with more than
                                                            125,000 vehicles available for bidding each day we have
                                                            something for everyone.</p>
                                                    </div>
                                                    <div class="mt-20 d-f j-c_s-b">
                                                        <label class="lang_region_selection_block pos-relative">
                                                            <select class="form-control langDropdown_select ng-pristine ng-untouched ng-valid ng-not-empty"
                                                                    data-uname="homepageDdlanguage"
                                                                    ng-model="selectedLang"
                                                                    ng-change="changePopupLanguage(selectedLang)"
                                                                    ng-options="obj.value as obj.name for obj in userConfig.langOptions">
                                                                <option label="English" value="string:en"
                                                                        selected="selected">English
                                                                </option>
                                                                <option label="Español" value="string:es">Español
                                                                </option>
                                                                <option label="Français Canadien" value="string:fr-CA">
                                                                    Français Canadien
                                                                </option>
                                                                <option label="العربية" value="string:ar">العربية
                                                                </option>
                                                                <option label="Русский" value="string:ru">Русский
                                                                </option>
                                                                <option label="Polski" value="string:pl">Polski</option>
                                                            </select>
                                                        </label>
                                                        <div class="flex-b-24">
                                                            <button site-catalyst="{'fname':'doTagSelectedLanguage'}"
                                                                    class="yes-btn" data-uname="homepageDdlanguageYes"
                                                                    ng-click="changeLanguage(selectedLang); popupToggle = false">
                                                                Yes
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div compile-cms-content="" parent-id="/content/us/en/region-popup"
                                                 fragment-id="region-popup" cms-content-fragment="null"><!----><!---->
                                                <!---->
                                                <div ng-if="compileFragment" compile-trusted-html="fragmentedCms">
                                                    <div class="region_section mt-40">
                                                        <div class="text-center"><label
                                                                    class="settimecolor settimefont region_label"
                                                                    data-uname="homepageRegionHeading">Website
                                                                Region </label></div>

                                                        <div>
                                                            <div>
                                                                <p class="description-text mt-5"
                                                                   data-uname="homepageRegionDescription">Copart USA
                                                                    allows you to purchase vehicles located inside the
                                                                    <strong style="color: #1254ff;">United
                                                                        States</strong>. If you would like to view
                                                                    inventory in other locations around the world,
                                                                    choose one from the Dropdown.</p>
                                                            </div>

                                                            <div class="mt-20">
                                                                <div class="custom_meta_dropdown copart_countries pos-relative user-select-none">
                                                                    <button aria-expanded="false"
                                                                            class="dropdown-toggle region_selected_block pos-relative"
                                                                            data-toggle="dropdown" type="button"><span
                                                                                class="copart_country_items m-0-i"><img
                                                                                    alt="Selected Country Flag"
                                                                                    class="img-responsive max-w-42"
                                                                                    data-entity-type=""
                                                                                    data-entity-uuid=""
                                                                                    src="https://www.copart.com/content/us.svg"> <span>USA</span> </span>
                                                                    </button>

                                                                    <div class="dropdown-menu">
                                                                        <ul class="list-style-none copart_country_list d-f j-c_s-b f-w_w">
                                                                            <li class="copart_country_items"><a
                                                                                        href="https://www.copart.com"><img
                                                                                            alt="United States"
                                                                                            class="img-responsive max-w-42"
                                                                                            data-entity-type=""
                                                                                            data-entity-uuid=""
                                                                                            src="https://www.copart.com/content/us.svg">
                                                                                    <span>USA</span> </a></li>
                                                                            <li class="copart_country_items"><a
                                                                                        href="https://www.copart.ca"><img
                                                                                            alt="CANADA"
                                                                                            class="img-responsive max-w-42"
                                                                                            data-entity-type=""
                                                                                            data-entity-uuid=""
                                                                                            src="https://www.copart.com/content/ca.svg">
                                                                                    <span>CANADA</span> </a></li>
                                                                            <li class="copart_country_items"><a
                                                                                        href="https://www.copart.co.uk"><img
                                                                                            alt="UK"
                                                                                            class="img-responsive max-w-42"
                                                                                            data-entity-type=""
                                                                                            data-entity-uuid=""
                                                                                            src="https://www.copart.com/content/uk.svg">
                                                                                    <span>UK</span> </a></li>
                                                                            <li class="copart_country_items"><a
                                                                                        href="https://www.copart.ie"><img
                                                                                            alt="IRELAND"
                                                                                            class="img-responsive max-w-42"
                                                                                            data-entity-type=""
                                                                                            data-entity-uuid=""
                                                                                            src="https://www.copart.com/content/ie.svg">
                                                                                    <span>IRELAND</span> </a></li>
                                                                            <li class="copart_country_items"><a
                                                                                        href="https://www.copartmea.com"><img
                                                                                            alt="UAE(Dubai)"
                                                                                            class="img-responsive max-w-42"
                                                                                            data-entity-type=""
                                                                                            data-entity-uuid=""
                                                                                            src="https://www.copart.com/content/ae.svg">
                                                                                    <span>UAE(Dubai)</span> </a></li>
                                                                            <li class="copart_country_items"><a
                                                                                        href="https://www.copartmea.com"><img
                                                                                            alt="BAHRAIN"
                                                                                            class="img-responsive max-w-42"
                                                                                            data-entity-type=""
                                                                                            data-entity-uuid=""
                                                                                            src="https://www.copart.com/content/bh.svg">
                                                                                    <span>BAHRAIN</span> </a></li>
                                                                            <li class="copart_country_items"><a
                                                                                        href="https://www.copartmea.com"><img
                                                                                            alt="OMAN"
                                                                                            class="img-responsive max-w-42"
                                                                                            data-entity-type=""
                                                                                            data-entity-uuid=""
                                                                                            src="https://www.copart.com/content/om.svg">
                                                                                    <span>OMAN</span> </a></li>
                                                                            <li class="copart_country_items"><a
                                                                                        href="https://www.copart.de"><img
                                                                                            alt="GERMANY"
                                                                                            class="img-responsive max-w-42"
                                                                                            data-entity-type=""
                                                                                            data-entity-uuid=""
                                                                                            src="https://www.copart.com/content/de.svg">
                                                                                    <span>GERMANY</span> </a></li>
                                                                            <li class="copart_country_items"><a
                                                                                        href="https://www.copart.es"><img
                                                                                            alt="SPAIN"
                                                                                            class="img-responsive max-w-42"
                                                                                            data-entity-type=""
                                                                                            data-entity-uuid=""
                                                                                            src="https://www.copart.com/content/es.svg">
                                                                                    <span>SPAIN</span> </a></li>
                                                                            <li class="copart_country_items"><a
                                                                                        href="https://www.copart.fi"><img
                                                                                            alt="FINLAND"
                                                                                            class="img-responsive max-w-42"
                                                                                            data-entity-type=""
                                                                                            data-entity-uuid=""
                                                                                            src="https://www.copart.com/content/fi.svg">
                                                                                    <span>FINLAND</span> </a></li>
                                                                            <li class="copart_country_items">
                                                                                <a href="https://www.copart.com.br">
                                                                                    <img alt="BRAZIL"
                                                                                         class="img-responsive max-w-42"
                                                                                         data-entity-type=""
                                                                                         data-entity-uuid=""
                                                                                         src="https://www.copart.com/content/br.svg">
                                                                                    <span>BRAZIL</span>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="mt-20"><a
                                                                        class="header_dropdown_int_buyers_link"
                                                                        href="/content/us/en/landing-page/international-buyers"
                                                                        target="_blank">More information for
                                                                    International Buyers › </a></div>
                                                        </div>
                                                    </div>

                                                </div><!----></div>
                                        </div>
                                    </div>
                                </li>
                                <li class="padleft10">
                                    <div class="signinmenu mobileScreen">
                                        <button type="button" class="navbar-toggle m-0-i beforesigninbtn  closebox"
                                                data-toggle="modal" data-target="#public-user-modal"
                                                aria-expanded="false" ng-show="userConfig.displayUserName == null">
            <span class="px-5">
                <span class="header-sprite user-icon"></span>
            </span>
                                        </button>


                                    </div>
                                </li>
                                <li>
                                    <div class="navbar-header togglemainheader">
                                        <div class="mainmenutogglediv">
            <span class="mobile-header-user-icon d-f a-i_c" data-toggle="collapse"
                  data-target="#mobile-header-nav-links" aria-expanded="false">
                <span class="header-sprite hamburger-icon"></span>
                <span class="menu-toggle-title fs15 m-0-i">Menu</span>
            </span>
                                        </div>
                                        <!--End  button mobile views-->
                                    </div>
                                </li>
                            </ul>
                            <!-- button mobile views-->
                        </div>
                    </div>
                    <div class="col-xs-12 mob-search-block mt-10" ng-controller="freeTextSearchController">
                        <ul class="list-inline m-0-i">
                            <li class="text-right mobilesearchicon w100 m-0-i p-0-i">
                                <form role="search" method="get" ng-submit="search()" id="mobile-search-form" name=""
                                      class="opacity1 d-f j-c_s-b a-i_c ng-pristine ng-valid">
                                    <div place-text="Search Inventory by Make, Model, VIN, and More.." class="d-f f-g1">
                                        <div class="form-group col-xs-10 pad0 mar0 f-g1">
                                            <div class="">
                                                <input type="text"
                                                       class="my_textarea form-control ng-pristine ng-untouched ng-valid ng-empty"
                                                       placeholder="Search Inventory by Make, Model, VIN, and More.."
                                                       autocomplete="off" id="mobile-input-search" name=""
                                                       ng-model="searchText" value="" data-uname="homeFreeFormSearch"
                                                       uib-typeahead="suggestion | lowercase for suggestion in getSuggestion($viewValue)"
                                                       typeahead-min-length="1" typeahead-focus-first="false"
                                                       aria-autocomplete="list" aria-expanded="false"
                                                       aria-owns="typeahead-9-1002">
                                                <ul class="dropdown-menu ng-hide"
                                                    ng-show="isOpen() &amp;&amp; !moveInProgress"
                                                    ng-style="{top: position().top+'px', left: position().left+'px'}"
                                                    role="listbox" aria-hidden="true" uib-typeahead-popup=""
                                                    id="typeahead-9-1002" matches="matches" active="activeIdx"
                                                    select="select(activeIdx, evt)" move-in-progress="moveInProgress"
                                                    query="query" position="position"
                                                    assign-is-open="assignIsOpen(isOpen)" debounce="debounceUpdate">
                                                    <!---->
                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="mob-serach-btn ml-5 d-f">
                                        <button type="submit" class="btn btn-default search-icon-btn">
                                            <span class="header-sprite mob-search-icon"></span>
                                        </button>
                                        <a href="./doRegistration" class="btn btn-mob-register d-f a-i_c"
                                           site-catalyst="{'fname':'doTopNavigationRegister'}"
                                           data-uname="homePageMobRegister">
                                            Register
                                        </a>
                                    </div>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <div class="mobileScreen">
                        <div class="modal" id="public-user-modal" role="dialog">
                            <div class="modal-dialog mobile-signin-block" style="background: none;">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <ul class="nav">
                                            <li class="mobile-header-menu" pref-code="signInOptions"
                                                access-value="member">
                                                <a class="menu_click fontW600" ng-href="./login"
                                                   data-uname="homePageMemberSignIn" href="./login"
                                                   onclick="jQuery('#public-user-modal').modal('hide')">
                                                    Sign In - Member
                                                </a>
                                            </li>
                                            <li class="mobile-header-menu" pref-code="signInOptions"
                                                access-value="seller">
                                                <a class="menu_click fontW600" ng-click="goToSellerLogin()"
                                                   type="button" onclick="jQuery('#public-user-modal').modal('hide')"
                                                   data-uname="homePageSellerSignIn" aria-expanded="false">
                                                    Sign In - Seller
                                                </a>
                                            </li>
                                            <!-- hiding the counter man for MDS -->

                                            <!-- End of counter man -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mobileScreen">
                        <div class="col-xs-12 closeBox modal" id="contryLangModal" role="dialog">
                            <div class="modal-dialog" style="top: 75px;">
                                <div class="modal-content overflowHidden">
                                    <div class="modal-header">
                                        <button type="button" class="close d-f j-c_c" data-dismiss="modal">×</button>
                                        <h4 class="text-center selectregion-title"
                                            style="display: inline-block;margin: 0;">
                                            Select Region and Language
                                        </h4>
                                    </div>
                                    <div class="modal-body">
                                        <form class="ng-pristine ng-valid">
                                            <div class="form-group col-xs-12">
                                                <select class="form-control ng-pristine ng-untouched ng-valid ng-empty"
                                                        id="countryselect" ng-model="mySelecturl"
                                                        ng-change="showSelectUrl(mySelecturl)">
                                                    <option value="">Region</option>
                                                    <option label="Copart USA" value="https://www.copart.com/">Copart
                                                        USA
                                                    </option>
                                                    <option label="Copart UK" value="https://www.copart.co.uk/">Copart
                                                        UK
                                                    </option>
                                                    <option label="Copart Canada" value="https://www.copart.ca/">Copart
                                                        Canada
                                                    </option>
                                                    <option label="Copart Germany" value="https://www.copart.de/">Copart
                                                        Germany
                                                    </option>
                                                    <option label="Copart Spain" value="https://www.copart.es/">Copart
                                                        Spain
                                                    </option>
                                                    <option label="Copart Ireland" value="https://www.copart.ie/">Copart
                                                        Ireland
                                                    </option>
                                                    <option label="Copart UAE" value="https://www.copartmea.com/">Copart
                                                        UAE
                                                    </option>
                                                    <option label="Copart Bahrain" value="https://www.copartmea.com/">
                                                        Copart Bahrain
                                                    </option>
                                                    <option label="Copart Oman" value="https://www.copartmea.com/">
                                                        Copart Oman
                                                    </option>
                                                    <option label="Copart Brazil" value="http://www.copart.com.br/">
                                                        Copart Brazil
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-xs-12">
                                                <select class="form-control bfh-timezones timezoneborders ng-pristine ng-untouched ng-valid ng-not-empty"
                                                        data-uname="homepageDdlanguage" ng-model="selectedLang"
                                                        ng-change="changePopupLanguage(selectedLang)"
                                                        ng-options="obj.value as obj.name for obj in userConfig.langOptions">
                                                    <option label="English" value="string:en" selected="selected">
                                                        English
                                                    </option>
                                                    <option label="Español" value="string:es">Español</option>
                                                    <option label="Français Canadien" value="string:fr-CA">Français
                                                        Canadien
                                                    </option>
                                                    <option label="العربية" value="string:ar">العربية</option>
                                                    <option label="Русский" value="string:ru">Русский</option>
                                                    <option label="Polski" value="string:pl">Polski</option>
                                                </select>
                                            </div>
                                            <div class=" text-center padtb20">
                                                <button class="btn btn-dgray btn-inline" data-target="#languagehome"
                                                        data-dismiss="modal">
                                                    Cancel
                                                </button>
                                                <button class="btn btn-lblue btn-inline"
                                                        ng-click="changeLanguage(selectedLang);" data-dismiss="modal">
                                                    Go Now
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <nav class="navbar mar0 borderNone clr" navcollapse="">
                        <div class="collapse navbar-collapse mobile-nav" id="mobile-header-nav-links">
                            <ul class="nav navbar-nav">

                                <li ng-class="{active: $route.current.activeTab == 'how-it-works'}">
                                    <a href="./how-it-works" data-uname="howItWorks">
                                        How It Works
                                    </a>
                                    <button type="button" data-toggle="collapse" data-target="#mobile-header-nav-links"
                                            aria-expanded="false"
                                            class="navbar-toggle collapsed  right navbtnclose-mobile fa fa-times fa-lg"></button>
                                </li>


                                <li>
                                    <a ng-show="enableCompleteRegistration" ng-href="/complete-registration"
                                       data-uname="completeRegistrationSubTab" class="menu_click ng-hide"
                                       href="/complete-registration">
                                        Complete Registration
                                    </a>
                                </li>

                                <li class="ng-hide"
                                    ng-show="userConfig.defaultDisplayPref &amp;&amp; userConfig.defaultDisplayPref.driverSeat"
                                    ng-class="{active: $route.current.activeTab == 'driverseat'}">
                                    <a href="./driverseat/mylots" ng-click="driverSeatReload('/driverseat/mylots')"
                                       data-uname="lotSummaryTab" class="menu_click">
                                        Driver's Seat
                                    </a>
                                </li>

                                <li class="dropdown" ng-class="{active: $route.current.activeTab == 'findVehicles'}">
                                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                       aria-expanded="false" data-uname="homePageFindAVehicle">
                                        Inventory
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu dd-white" menuclick="">

                                        <li>
                                            <a href="./vehicleFinder" data-uname="vehicleFinderTab" class="menu_click">
                                                Vehicle Finder
                                            </a>
                                        </li>


                                        <li ng-show="!userConfig.hideSalesList">
                                            <a href="./salesListResult" data-uname="salesListTab" class="menu_click">
                                                Sales List
                                            </a>
                                        </li>
                                        <li ng-show="userConfig.displayUserName == null">
                                            <a href="./public/watchList" data-uname="watchlistPublicTab"
                                               class="menu_click">
                                                Watchlist
                                            </a>
                                        </li>

                                        <li>
                                            <a href="./savedsearch" data-uname="savedSearchesTab" class="menu_click">
                                                Saved Searches
                                            </a>
                                        </li>
                                        <li pref-code="footerLinks" access-value="veh_alrt">
                                            <a href="./vehicleAlerts" data-uname="homepageVehiclealert"
                                               class="menu_click">
                                                Vehicle Alerts
                                            </a>
                                        </li>
                                        <li ng-show="userConfig.displayUserName != null"
                                            pref-code="downloadSalesDataPreference.showDownloadSalesData"
                                            access-value="true" class="ng-hide">
                                            <a href="./downloadSalesData" class="menu_click">
                                                Download Sales Data
                                            </a>
                                        </li>
                                    </ul>
                                </li>


                                <li class="dropdown" ng-class="{active: $route.current.activeTab == 'Auctions'}">
                                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                       aria-expanded="false" data-uname="auctionsTab">
                                        Auctions
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu dd-white">
                                        <li>
                                            <a href="./todaysAuction" class="menu_click"
                                               ng-click="routeReload('/todaysAuction')" data-uname="todaysAuctionTab">
                                                Today's Auctions
                                            </a>
                                        </li>
                                        <li>
                                            <a href="./auctionCalendar" data-uname="auctionsCalendarTab"
                                               class="menu_click">
                                                Auctions Calendar
                                            </a>
                                        </li>
                                        <li>
                                            <a href="./auctionDashboard" ng-click="routeReload('/auctionDashboard')"
                                               class="menu_click" data-uname="auctionsDashboardTab">
                                                Join Auctions
                                            </a>
                                        </li>
                                    </ul>
                                </li>


                                <li ng-class="{active: $route.current.activeTab == 'locations'}">
                                    <a href="./locations" data-uname="locationsTab" class="menu_click">
                                        Locations
                                    </a>
                                </li>
                                <!--
            For  US sites like Drive there is no dropdown for sell vehicle.. instead it shows the sell Vehicle which is clickable.
            -->
                                <!---->

                                <!---->


                                <!---->


                                <!---->
                                <li ng-if="!locale.messages['tab.app.label.sellVehicle'] &amp;&amp; (userConfig.displayUserName == null)"
                                    ng-show="userConfig.siteCode !== 'CPRTUK' &amp;&amp; userConfig.siteCode !== 'CPRTIE'"
                                    class="dropdown sell-vehicle-dropdown"
                                    ng-class="{active: $route.current.activeTab == 'sellAVehicle'}">
                                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                       aria-expanded="false" data-uname="sellVehicleTab">
                                        Sell a Vehicle
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu dd-white" menuclick="">
                                        <li>
                                            <a href="./sellForIndividuals" data-uname="forIndividualsTab"
                                               class="menu_click">
                                                For Individuals
                                            </a>
                                        </li>
                                        <li>
                                            <a href="./sellForBusiness" data-uname="forBusinessTab" class="menu_click">
                                                For Businesses
                                            </a>
                                        </li>
                                        <li pref-code="footerLinks" access-value="veh_dealer">
                                            <a href="./sellForDealer" data-uname="forDealersTab" class="menu_click">
                                                For Dealers
                                            </a>
                                        </li>
                                    </ul>
                                </li><!---->

                                <li class="dropdown" ng-class="{active: $route.current.activeTab == 'services'}">
                                    <a href="./brokersmarketmakers/1" class="dropdown-toggle addgd"
                                       data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"
                                       data-uname="serviceSubTab">
                                        Services &amp; Support
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu dd-white">
                                        <!---->


                                        <li pref-code="footerLinks" access-value="broker_mkt">
                                            <a tabindex="-1" href="./brokersmarketmakers/1"
                                               data-uname="brokersAndMarketMakersTertiaryTab" class="menu_click">
                                                Brokers
                                            </a>
                                        </li>
                                        <li pref-code="footerLinks" access-value="inspectors">
                                            <a href="/content/us/en/landing-page/third-party-inspections"
                                               data-uname="homepageInspectorstertiarytab"
                                               site-catalyst="{'fname':'doTagInspectionServicesHeader_Footer'}"
                                               class="menu_click">
                                                Inspectors
                                            </a>
                                        </li>
                                        <li pref-code="footerLinks" access-value="vehicle_reports">
                                            <a href="/content/us/en/landing-page/vehicle-reports"
                                               data-uname="homepageVehicleReportstertiarytab" class="menu_click">
                                                Vehicle Reports
                                            </a>
                                        </li>

                                        <li class="separator-list">
                                            <hr class="header-menu-separator">
                                        </li>

                                        <li ng-show="locale.messages['support.services.deliveries.title']"
                                            class="ng-hide">
                                            <a href="en/Content/us/en/Support/Services/Vehicle-Deliveries"
                                               class="menu_click">

                                            </a>
                                        </li>


                                        <li ng-show="locale.messages['support.services.viewingAndLoading']"
                                            class="ng-hide">
                                            <a data-content-ng-href="" class="menu_click" href="">

                                            </a>
                                        </li>

                                        <li ng-show="locale.messages['support.services.vehicleviewing']"
                                            class="ng-hide">
                                            <a href="en/Content/us/en/Services/Vehicle-Auction-Viewing"
                                               class="menu_click">

                                            </a>
                                        </li>


                                        <li ng-class="{active: $route.current.activeTab == 'howToBuy'}">
                                            <a tabindex="-1" href="./overview" data-uname="howToBuyTab">
                                                How to Buy
                                            </a>
                                        </li>

                                        <!---->
                                        <!---->
                                        <!---->

                                        <li>
                                            <a data-content-ng-href="/Support/FAQ-Topics/Buying"
                                               ng-click="doTagPageView('faq_buying')" data-uname="faqSubTab"
                                               class="menu_click" href="/Content/us/en/Support/FAQ-Topics/Buying">
                                                Common Questions
                                            </a>
                                        </li>

                                        <li ng-show="locale.messages['app.label.memberProtectionPledge']"
                                            class="ng-hide">
                                            <a href="en/Content/us/en/Member-Protection-Pledge" class="menu_click">

                                            </a>
                                        </li>
                                        <li ng-show="locale.messages['support.services.buyingCatBs']" class="ng-hide">
                                            <a href="en/Content/us/en/Support/Services/Category-B-Vehicles"
                                               class="menu_click">

                                            </a>
                                        </li>
                                        <li ng-show="locale.messages['support.services.gettheKnowhow']" class="ng-hide">
                                            <a href="en/Content/us/en/landing-page/know-how/educated-buyer-at-auction"
                                               class="menu_click">

                                            </a>
                                        </li>
                                        <!-- This is added for Drive -->

                                        <li pref-code="footerLinks" access-value="licensing">
                                            <a ng-href="./helpWithLicensing" data-uname="helpWithLicensingSubTab"
                                               class="menu_click" href="./helpWithLicensing">
                                                Licensing Help
                                            </a>
                                        </li>
                                        <li>
                                            <a ng-href="./copartVideos" data-uname="videosTeritaryTab"
                                               class="menu_click" href="./copartVideos">
                                                Videos
                                            </a>
                                        </li>
                                        <li>
                                            <a ng-href="./contactUs" data-uname="contactUsSubTabTertiary"
                                               class="menu_click" href="./contactUs">
                                                Contact Us
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="./help" target="_blank" class="menu_click help-center-link"
                                               site-catalyst="{'fname':'doTagNeedHelp'}"
                                               data-uname="needHelpSubTabTertiary">
                                                Help Center
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </nav>

                    <div>
                        <div class="modal" id="member-modal" role="dialog">
                            <div class="modal-dialog mobile-signin-block" style="background: none;">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <ul class="nav">
                                            <li class="mobile-header-menu">
                                                <a ng-href="./accountInformation/contactInfo"
                                                   data-uname="accountInformationSubTab"
                                                   onclick="jQuery('#member-modal').modal('hide')"
                                                   class="menu_click fontW600" href="./accountInformation/contactInfo">
                                                    Account Information
                                                </a>
                                            </li>

                                            <!---->
                                            <li ng-if="locale.messages['app.depositsUpgrades.label']"
                                                class="mobile-header-menu">
                                                <a href="en/Content/us/en/buyer/Payments/Deposits-and-Upgrades"
                                                   onclick="jQuery('#member-modal').modal('hide')"
                                                   data-uname="depositsAndUpgradeSubTab" class="menu_click fontW600">
                                                    Upgrades and Deposits
                                                </a>
                                            </li><!---->

                                            <!---->
                                            <li ng-if="locale.messages['app.memeberFees.label']"
                                                class="mobile-header-menu">
                                                <a ng-href="./memberFees" data-uname="memberFeesSubTab"
                                                   onclick="jQuery('#member-modal').modal('hide')"
                                                   class="menu_click fontW600" href="./memberFees">Member Fees
                                                </a>
                                            </li><!---->

                                            <li class="mobile-header-menu">
                                                <a class="menu_click fontW600"
                                                   onclick="jQuery('#member-modal').modal('hide')">
                                <span ng-click="doLogout()" data-uname="loginSignoutlink"
                                      style="text-transform: uppercase;">
                                    Sign Out
                                </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- end mobile View-->
                        </div>
                    </div>
                </div>

            </div>
            <!-- Top header ends-->
        </div>
    </div>
    <!--  end header mid -->

    <div class="header-nav desktopScreen">
        <div class="container-fluid pad0">
            <div class="d-f j-c_s-b a-i_c">
                <nav class="navbar mar0 borderNone navbar-expand-sm clr" navcollapse="">
                    <div class="collapse navbar-collapse mobile-nav" id="mobile-header-nav-links">
                        <ul class="nav navbar-nav">

                            <li ng-class="{active: $route.current.activeTab == 'how-it-works'}">
                                <a href="./how-it-works" data-uname="howItWorks">
                                    How It Works
                                </a>
                                <button type="button" data-toggle="collapse" data-target="#mobile-header-nav-links"
                                        aria-expanded="false"
                                        class="navbar-toggle collapsed  right navbtnclose-mobile fa fa-times fa-lg"></button>
                            </li>


                            <li>
                                <a ng-show="enableCompleteRegistration" ng-href="/complete-registration"
                                   data-uname="completeRegistrationSubTab" class="menu_click ng-hide"
                                   href="/complete-registration">
                                    Complete Registration
                                </a>
                            </li>

                            <li class="ng-hide"
                                ng-show="userConfig.defaultDisplayPref &amp;&amp; userConfig.defaultDisplayPref.driverSeat"
                                ng-class="{active: $route.current.activeTab == 'driverseat'}">
                                <a href="./driverseat/mylots" ng-click="driverSeatReload('/driverseat/mylots')"
                                   data-uname="lotSummaryTab" class="menu_click">
                                    Driver's Seat
                                </a>
                            </li>

                            <li class="dropdown" ng-class="{active: $route.current.activeTab == 'findVehicles'}">
                                <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                   aria-expanded="false" data-uname="homePageFindAVehicle">
                                    Inventory
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu dd-white" menuclick="">

                                    <li>
                                        <a href="./vehicleFinder" data-uname="vehicleFinderTab" class="menu_click">
                                            Vehicle Finder
                                        </a>
                                    </li>


                                    <li ng-show="!userConfig.hideSalesList">
                                        <a href="./salesListResult" data-uname="salesListTab" class="menu_click">
                                            Sales List
                                        </a>
                                    </li>
                                    <li ng-show="userConfig.displayUserName == null">
                                        <a href="./public/watchList" data-uname="watchlistPublicTab" class="menu_click">
                                            Watchlist
                                        </a>
                                    </li>

                                    <li>
                                        <a href="./savedsearch" data-uname="savedSearchesTab" class="menu_click">
                                            Saved Searches
                                        </a>
                                    </li>
                                    <li pref-code="footerLinks" access-value="veh_alrt">
                                        <a href="./vehicleAlerts" data-uname="homepageVehiclealert" class="menu_click">
                                            Vehicle Alerts
                                        </a>
                                    </li>
                                    <li ng-show="userConfig.displayUserName != null"
                                        pref-code="downloadSalesDataPreference.showDownloadSalesData"
                                        access-value="true" class="ng-hide">
                                        <a href="./downloadSalesData" class="menu_click">
                                            Download Sales Data
                                        </a>
                                    </li>
                                </ul>
                            </li>


                            <li class="dropdown" ng-class="{active: $route.current.activeTab == 'Auctions'}">
                                <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                   aria-expanded="false" data-uname="auctionsTab">
                                    Auctions
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu dd-white">
                                    <li>
                                        <a href="./todaysAuction" class="menu_click"
                                           ng-click="routeReload('/todaysAuction')" data-uname="todaysAuctionTab">
                                            Today's Auctions
                                        </a>
                                    </li>
                                    <li>
                                        <a href="./auctionCalendar" data-uname="auctionsCalendarTab" class="menu_click">
                                            Auctions Calendar
                                        </a>
                                    </li>
                                    <li>
                                        <a href="./auctionDashboard" ng-click="routeReload('/auctionDashboard')"
                                           class="menu_click" data-uname="auctionsDashboardTab">
                                            Join Auctions
                                        </a>
                                    </li>
                                </ul>
                            </li>


                            <li ng-class="{active: $route.current.activeTab == 'locations'}">
                                <a href="./locations" data-uname="locationsTab" class="menu_click">
                                    Locations
                                </a>
                            </li>
                            <!--
            For  US sites like Drive there is no dropdown for sell vehicle.. instead it shows the sell Vehicle which is clickable.
            -->
                            <!---->

                            <!---->


                            <!---->


                            <!---->
                            <li ng-if="!locale.messages['tab.app.label.sellVehicle'] &amp;&amp; (userConfig.displayUserName == null)"
                                ng-show="userConfig.siteCode !== 'CPRTUK' &amp;&amp; userConfig.siteCode !== 'CPRTIE'"
                                class="dropdown sell-vehicle-dropdown"
                                ng-class="{active: $route.current.activeTab == 'sellAVehicle'}">
                                <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                   aria-expanded="false" data-uname="sellVehicleTab">
                                    Sell a Vehicle
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu dd-white" menuclick="">
                                    <li>
                                        <a href="./sellForIndividuals" data-uname="forIndividualsTab"
                                           class="menu_click">
                                            For Individuals
                                        </a>
                                    </li>
                                    <li>
                                        <a href="./sellForBusiness" data-uname="forBusinessTab" class="menu_click">
                                            For Businesses
                                        </a>
                                    </li>
                                    <li pref-code="footerLinks" access-value="veh_dealer">
                                        <a href="./sellForDealer" data-uname="forDealersTab" class="menu_click">
                                            For Dealers
                                        </a>
                                    </li>
                                </ul>
                            </li><!---->

                            <li class="dropdown" ng-class="{active: $route.current.activeTab == 'services'}">
                                <a href="./brokersmarketmakers/1" class="dropdown-toggle addgd" data-toggle="dropdown"
                                   role="button" aria-haspopup="true" aria-expanded="false" data-uname="serviceSubTab">
                                    Services &amp; Support
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu dd-white" style="display: none;">
                                    <!---->


                                    <li pref-code="footerLinks" access-value="broker_mkt">
                                        <a tabindex="-1" href="./brokersmarketmakers/1"
                                           data-uname="brokersAndMarketMakersTertiaryTab" class="menu_click">
                                            Brokers
                                        </a>
                                    </li>
                                    <li pref-code="footerLinks" access-value="inspectors">
                                        <a href="/content/us/en/landing-page/third-party-inspections"
                                           data-uname="homepageInspectorstertiarytab"
                                           site-catalyst="{'fname':'doTagInspectionServicesHeader_Footer'}"
                                           class="menu_click">
                                            Inspectors
                                        </a>
                                    </li>
                                    <li pref-code="footerLinks" access-value="vehicle_reports">
                                        <a href="/content/us/en/landing-page/vehicle-reports"
                                           data-uname="homepageVehicleReportstertiarytab" class="menu_click">
                                            Vehicle Reports
                                        </a>
                                    </li>

                                    <li class="separator-list">
                                        <hr class="header-menu-separator">
                                    </li>

                                    <li ng-show="locale.messages['support.services.deliveries.title']" class="ng-hide">
                                        <a href="en/Content/us/en/Support/Services/Vehicle-Deliveries"
                                           class="menu_click">

                                        </a>
                                    </li>


                                    <li ng-show="locale.messages['support.services.viewingAndLoading']" class="ng-hide">
                                        <a data-content-ng-href="" class="menu_click" href="">

                                        </a>
                                    </li>

                                    <li ng-show="locale.messages['support.services.vehicleviewing']" class="ng-hide">
                                        <a href="en/Content/us/en/Services/Vehicle-Auction-Viewing" class="menu_click">

                                        </a>
                                    </li>


                                    <li ng-class="{active: $route.current.activeTab == 'howToBuy'}">
                                        <a tabindex="-1" href="./overview" data-uname="howToBuyTab">
                                            How to Buy
                                        </a>
                                    </li>

                                    <!---->
                                    <!---->
                                    <!---->

                                    <li>
                                        <a data-content-ng-href="/Support/FAQ-Topics/Buying"
                                           ng-click="doTagPageView('faq_buying')" data-uname="faqSubTab"
                                           class="menu_click" href="/Content/us/en/Support/FAQ-Topics/Buying">
                                            Common Questions
                                        </a>
                                    </li>

                                    <li ng-show="locale.messages['app.label.memberProtectionPledge']" class="ng-hide">
                                        <a href="en/Content/us/en/Member-Protection-Pledge" class="menu_click">

                                        </a>
                                    </li>
                                    <li ng-show="locale.messages['support.services.buyingCatBs']" class="ng-hide">
                                        <a href="en/Content/us/en/Support/Services/Category-B-Vehicles"
                                           class="menu_click">

                                        </a>
                                    </li>
                                    <li ng-show="locale.messages['support.services.gettheKnowhow']" class="ng-hide">
                                        <a href="en/Content/us/en/landing-page/know-how/educated-buyer-at-auction"
                                           class="menu_click">

                                        </a>
                                    </li>
                                    <!-- This is added for Drive -->

                                    <li pref-code="footerLinks" access-value="licensing">
                                        <a ng-href="./helpWithLicensing" data-uname="helpWithLicensingSubTab"
                                           class="menu_click" href="./helpWithLicensing">
                                            Licensing Help
                                        </a>
                                    </li>
                                    <li>
                                        <a ng-href="./copartVideos" data-uname="videosTeritaryTab" class="menu_click"
                                           href="./copartVideos">
                                            Videos
                                        </a>
                                    </li>
                                    <li>
                                        <a ng-href="./contactUs" data-uname="contactUsSubTabTertiary" class="menu_click"
                                           href="./contactUs">
                                            Contact Us
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="./help" target="_blank" class="menu_click help-center-link"
                                           site-catalyst="{'fname':'doTagNeedHelp'}"
                                           data-uname="needHelpSubTabTertiary">
                                            Help Center
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>

                <div class="logregdiv">
                    <div class="nav-right-block d-f">
                        <div class="d-f">
            <span class="d-f a-i_c">
                <span class="header-sprite location-arrow-yellow"></span>
                <span class="desktop-only">
                    <!----><span ng-if="!locationInfo">Enter Your Location</span><!---->
                    <!---->
                </span>
            </span>
                            <button class="edit-btn desktop-only" data-target="#changeLocationModal" data-toggle="modal"
                                    ng-click="showChangeLocationModal();" role="button" aria-label="Edit">
                                Edit
                            </button>
                        </div>
                    </div>
                    <!-- Location change modal -->
                    <div class="modal fade" id="changeLocationModal" tabindex="-1" role="dialog"
                         aria-labelledby="changeLocationModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content overflowHidden">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span></button>
                                    <span class="glyphicon glyphicon-map-marker"></span>
                                    <span class="modal-title">Change Your Location</span>
                                </div>
                                <div class="modal-body">
                                    <div change-location="null">
                                        <form class="form-inline  ng-pristine ng-invalid ng-invalid-required ng-valid-pattern ng-valid-maxlength"
                                              name="changeLocationForm" ng-submit="changeLocation(changeLocationForm)"
                                              novalidate="">
                                            <div class="form-group w100">
        <span class="col-sm-12 col-md-7 pad0">
            <div class="copart-black-color">
                Country:<sup class="reqField">*</sup>
            </div>
            <select class="form-control martop loc-country-dropdown ng-pristine ng-untouched ng-valid ng-not-empty ng-valid-required"
                    name="country" ng-change="checkIfZipCodeRequired()" ng-model="userLocation.country"
                    ng-options="option as option.description for option in countries" required=""><option
                        label="UNITED STATES" value="object:133" selected="selected">UNITED STATES</option><option
                        label="CANADA" value="object:134">CANADA</option><option label="---------" value="object:135">---------</option><option
                        label="AFGHANISTAN" value="object:136">AFGHANISTAN</option><option label="ALBANIA"
                                                                                           value="object:137">ALBANIA</option><option
                        label="ALGERIA" value="object:138">ALGERIA</option><option label="ANDORRA" value="object:139">ANDORRA</option><option
                        label="ANGOLA" value="object:140">ANGOLA</option><option label="ANGUILLA" value="object:141">ANGUILLA</option><option
                        label="ANTIGUA" value="object:142">ANTIGUA</option><option label="ARGENTINA" value="object:143">ARGENTINA</option><option
                        label="ARMENIA, REPUBLIC OF" value="object:144">ARMENIA, REPUBLIC OF</option><option
                        label="ARUBA" value="object:145">ARUBA</option><option label="AUSTRALIA" value="object:146">AUSTRALIA</option><option
                        label="AUSTRIA" value="object:147">AUSTRIA</option><option label="AZERBAIJAN"
                                                                                   value="object:148">AZERBAIJAN</option><option
                        label="BAHAMAS" value="object:149">BAHAMAS</option><option label="BAHRAIN" value="object:150">BAHRAIN</option><option
                        label="BANGLADESH" value="object:151">BANGLADESH</option><option label="BARBADOS"
                                                                                         value="object:152">BARBADOS</option><option
                        label="BELARUS, REPUBLIC OF" value="object:153">BELARUS, REPUBLIC OF</option><option
                        label="BELGIUM" value="object:154">BELGIUM</option><option label="BELIZE" value="object:155">BELIZE</option><option
                        label="BENIN" value="object:156">BENIN</option><option label="BERMUDA" value="object:157">BERMUDA</option><option
                        label="BHUTAN" value="object:158">BHUTAN</option><option label="BOLIVIA" value="object:159">BOLIVIA</option><option
                        label="BOSNIA" value="object:160">BOSNIA</option><option label="BOTSWANA" value="object:161">BOTSWANA</option><option
                        label="BRAZIL" value="object:162">BRAZIL</option><option label="BRITISH VIRGIN ISLANDS"
                                                                                 value="object:163">BRITISH VIRGIN ISLANDS</option><option
                        label="BRUNEI" value="object:164">BRUNEI</option><option label="BULGARIA" value="object:165">BULGARIA</option><option
                        label="BURKINA FASO" value="object:166">BURKINA FASO</option><option label="BURMA"
                                                                                             value="object:167">BURMA</option><option
                        label="BURUNDI" value="object:168">BURUNDI</option><option label="CAMBODIA" value="object:169">CAMBODIA</option><option
                        label="CAMEROON" value="object:170">CAMEROON</option><option label="CANADA" value="object:171">CANADA</option><option
                        label="CAPE VERDE" value="object:172">CAPE VERDE</option><option
                        label="CENTRAL AFRICAN REPUBLIC" value="object:173">CENTRAL AFRICAN REPUBLIC</option><option
                        label="CHAD" value="object:174">CHAD</option><option label="CHILE"
                                                                             value="object:175">CHILE</option><option
                        label="CHINA" value="object:176">CHINA</option><option label="COLOMBIA" value="object:177">COLOMBIA</option><option
                        label="COMOROS" value="object:178">COMOROS</option><option label="CONGO, DEMOCRATIC REPUBLIC OF"
                                                                                   value="object:179">CONGO, DEMOCRATIC REPUBLIC OF</option><option
                        label="CONGO, REPUBLIC OF" value="object:180">CONGO, REPUBLIC OF</option><option
                        label="COSTA RICA" value="object:181">COSTA RICA</option><option label="COTE D'IVOIRE"
                                                                                         value="object:182">COTE D'IVOIRE</option><option
                        label="CROATIA" value="object:183">CROATIA</option><option label="CURACAO" value="object:184">CURACAO</option><option
                        label="CYPRUS" value="object:185">CYPRUS</option><option label="CZECH REPUBLIC"
                                                                                 value="object:186">CZECH REPUBLIC</option><option
                        label="DENMARK" value="object:187">DENMARK</option><option label="DJIBOUTI" value="object:188">DJIBOUTI</option><option
                        label="DOMINICA" value="object:189">DOMINICA</option><option label="DOMINICAN REPUBLIC"
                                                                                     value="object:190">DOMINICAN REPUBLIC</option><option
                        label="ECUADOR" value="object:191">ECUADOR</option><option label="EGYPT" value="object:192">EGYPT</option><option
                        label="EL SALVADOR" value="object:193">EL SALVADOR</option><option label="EQUATORIAL GUINEA"
                                                                                           value="object:194">EQUATORIAL GUINEA</option><option
                        label="ERITREA" value="object:195">ERITREA</option><option label="ESTONIA" value="object:196">ESTONIA</option><option
                        label="ETHIOPIA" value="object:197">ETHIOPIA</option><option label="FIJI" value="object:198">FIJI</option><option
                        label="FINLAND" value="object:199">FINLAND</option><option label="FRANCE" value="object:200">FRANCE</option><option
                        label="GABON" value="object:201">GABON</option><option label="GAMBIA"
                                                                               value="object:202">GAMBIA</option><option
                        label="GEORGIA" value="object:203">GEORGIA</option><option label="GERMANY" value="object:204">GERMANY</option><option
                        label="GHANA,REPUBLIC OF" value="object:205">GHANA,REPUBLIC OF</option><option label="GIBRALTAR"
                                                                                                       value="object:206">GIBRALTAR</option><option
                        label="GREECE" value="object:207">GREECE</option><option label="GRENADA" value="object:208">GRENADA</option><option
                        label="GUATEMALA" value="object:209">GUATEMALA</option><option label="GUERNSEY"
                                                                                       value="object:210">GUERNSEY</option><option
                        label="GUINEA" value="object:211">GUINEA</option><option label="GUINEA-BISSAU"
                                                                                 value="object:212">GUINEA-BISSAU</option><option
                        label="GUYANA" value="object:213">GUYANA</option><option label="HAITI"
                                                                                 value="object:214">HAITI</option><option
                        label="HONDURAS" value="object:215">HONDURAS</option><option label="HONGKONG"
                                                                                     value="object:216">HONGKONG</option><option
                        label="HUNGARY" value="object:217">HUNGARY</option><option label="ICELAND" value="object:218">ICELAND</option><option
                        label="INDIA" value="object:219">INDIA</option><option label="INDONESIA" value="object:220">INDONESIA</option><option
                        label="IRAQ" value="object:221">IRAQ</option><option label="ISRAEL"
                                                                             value="object:222">ISRAEL</option><option
                        label="ITALY" value="object:223">ITALY</option><option label="JAMAICA" value="object:224">JAMAICA</option><option
                        label="JAPAN" value="object:225">JAPAN</option><option label="JERSEY"
                                                                               value="object:226">JERSEY</option><option
                        label="JORDAN" value="object:227">JORDAN</option><option label="KAZAKHSTAN" value="object:228">KAZAKHSTAN</option><option
                        label="KENYA" value="object:229">KENYA</option><option label="KIRIBATI" value="object:230">KIRIBATI</option><option
                        label="KOSOVO" value="object:231">KOSOVO</option><option label="KUWAIT" value="object:232">KUWAIT</option><option
                        label="KYRGYZSTAN" value="object:233">KYRGYZSTAN</option><option
                        label="LAO PEOPLES DEMOCRATIC REPUBLIC"
                        value="object:234">LAO PEOPLES DEMOCRATIC REPUBLIC</option><option label="LATVIA"
                                                                                           value="object:235">LATVIA</option><option
                        label="LEBANON" value="object:236">LEBANON</option><option label="LESOTHO" value="object:237">LESOTHO</option><option
                        label="LIBERIA" value="object:238">LIBERIA</option><option label="LIBYA" value="object:239">LIBYA</option><option
                        label="LIECHTENSTEIN" value="object:240">LIECHTENSTEIN</option><option label="LITHUANIA"
                                                                                               value="object:241">LITHUANIA</option><option
                        label="LUXEMBOURG" value="object:242">LUXEMBOURG</option><option label="MACAU"
                                                                                         value="object:243">MACAU</option><option
                        label="MACEDONIA" value="object:244">MACEDONIA</option><option label="MADAGASCAR"
                                                                                       value="object:245">MADAGASCAR</option><option
                        label="MALAWI" value="object:246">MALAWI</option><option label="MALAYSIA" value="object:247">MALAYSIA</option><option
                        label="MALDIVES" value="object:248">MALDIVES</option><option label="MALI" value="object:249">MALI</option><option
                        label="MALTA" value="object:250">MALTA</option><option label="MARSHALL ISLANDS"
                                                                               value="object:251">MARSHALL ISLANDS</option><option
                        label="MAURITANIA" value="object:252">MAURITANIA</option><option label="MAURITIUS"
                                                                                         value="object:253">MAURITIUS</option><option
                        label="MEXICO" value="object:254">MEXICO</option><option label="MICRONESIA" value="object:255">MICRONESIA</option><option
                        label="MOLDOVA, REPUBLIC OF" value="object:256">MOLDOVA, REPUBLIC OF</option><option
                        label="MONACO" value="object:257">MONACO</option><option label="MONGOLIA" value="object:258">MONGOLIA</option><option
                        label="MONTENEGRO" value="object:259">MONTENEGRO</option><option label="MOROCCO"
                                                                                         value="object:260">MOROCCO</option><option
                        label="MOZAMBIQUE" value="object:261">MOZAMBIQUE</option><option label="NAMIBIA"
                                                                                         value="object:262">NAMIBIA</option><option
                        label="NAURU" value="object:263">NAURU</option><option label="NEPAL"
                                                                               value="object:264">NEPAL</option><option
                        label="NETHERLANDS" value="object:265">NETHERLANDS</option><option label="NEW ZEALAND"
                                                                                           value="object:266">NEW ZEALAND</option><option
                        label="NICARAGUA" value="object:267">NICARAGUA</option><option label="NIGER" value="object:268">NIGER</option><option
                        label="NIGERIA" value="object:269">NIGERIA</option><option label="NORWAY" value="object:270">NORWAY</option><option
                        label="OMAN" value="object:271">OMAN</option><option label="PAKISTAN" value="object:272">PAKISTAN</option><option
                        label="PALAU" value="object:273">PALAU</option><option label="PANAMA"
                                                                               value="object:274">PANAMA</option><option
                        label="PAPUA NEW GUINEA" value="object:275">PAPUA NEW GUINEA</option><option label="PARAGUAY"
                                                                                                     value="object:276">PARAGUAY</option><option
                        label="PERU" value="object:277">PERU</option><option label="PHILIPPINES" value="object:278">PHILIPPINES</option><option
                        label="POLAND" value="object:279">POLAND</option><option label="PORTUGAL" value="object:280">PORTUGAL</option><option
                        label="QATAR" value="object:281">QATAR</option><option label="RAJ TEST" value="object:282">RAJ TEST</option><option
                        label="REPUBLIC OF IRELAND" value="object:283">REPUBLIC OF IRELAND</option><option
                        label="ROMANIA" value="object:284">ROMANIA</option><option label="RUSSIA" value="object:285">RUSSIA</option><option
                        label="RWANDA" value="object:286">RWANDA</option><option label="SAINT LUCIA" value="object:287">SAINT LUCIA</option><option
                        label="SAINT VINCENT AND THE GRENADINES"
                        value="object:288">SAINT VINCENT AND THE GRENADINES</option><option label="SAMOA"
                                                                                            value="object:289">SAMOA</option><option
                        label="SAN MARINO" value="object:290">SAN MARINO</option><option label="SAO TOME AND PRINCIPE"
                                                                                         value="object:291">SAO TOME AND PRINCIPE</option><option
                        label="SAUDI ARABIA" value="object:292">SAUDI ARABIA</option><option label="SENEGAL"
                                                                                             value="object:293">SENEGAL</option><option
                        label="SERBIA AND MONTENEGRO" value="object:294">SERBIA AND MONTENEGRO</option><option
                        label="SEYCHELLES" value="object:295">SEYCHELLES</option><option label="SIERRA LEONE"
                                                                                         value="object:296">SIERRA LEONE</option><option
                        label="SINGAPORE" value="object:297">SINGAPORE</option><option label="SINT MAARTEN"
                                                                                       value="object:298">SINT MAARTEN</option><option
                        label="SLOVAKIA" value="object:299">SLOVAKIA</option><option label="SLOVENIA"
                                                                                     value="object:300">SLOVENIA</option><option
                        label="SOLOMON ISLANDS" value="object:301">SOLOMON ISLANDS</option><option label="SOMALIA"
                                                                                                   value="object:302">SOMALIA</option><option
                        label="SOUTH AFRICA" value="object:303">SOUTH AFRICA</option><option label="SOUTH KOREA"
                                                                                             value="object:304">SOUTH KOREA</option><option
                        label="SPAIN" value="object:305">SPAIN</option><option label="SRI LANKA" value="object:306">SRI LANKA</option><option
                        label="ST KITTS-NEVIS" value="object:307">ST KITTS-NEVIS</option><option label="SURINAME"
                                                                                                 value="object:308">SURINAME</option><option
                        label="SWAZILAND" value="object:309">SWAZILAND</option><option label="SWEDEN"
                                                                                       value="object:310">SWEDEN</option><option
                        label="SWITZERLAND" value="object:311">SWITZERLAND</option><option label="TAIWAN"
                                                                                           value="object:312">TAIWAN</option><option
                        label="TAJIKISTAN" value="object:313">TAJIKISTAN</option><option label="TANZANIA"
                                                                                         value="object:314">TANZANIA</option><option
                        label="THAILAND" value="object:315">THAILAND</option><option label="TIMOR-LESTE"
                                                                                     value="object:316">TIMOR-LESTE</option><option
                        label="TOGO" value="object:317">TOGO</option><option label="TONGA"
                                                                             value="object:318">TONGA</option><option
                        label="TRINIDAD AND TABAGO" value="object:319">TRINIDAD AND TABAGO</option><option
                        label="TUNISIA" value="object:320">TUNISIA</option><option label="TURKEY" value="object:321">TURKEY</option><option
                        label="TURKMENISTAN" value="object:322">TURKMENISTAN</option><option label="TURKS &amp; CAICOS"
                                                                                             value="object:323">TURKS &amp; CAICOS</option><option
                        label="TUVALU" value="object:324">TUVALU</option><option label="UGANDA" value="object:325">UGANDA</option><option
                        label="UKRAINE" value="object:326">UKRAINE</option><option label="UNITED ARAB EMIRATES"
                                                                                   value="object:327">UNITED ARAB EMIRATES</option><option
                        label="UNITED KINGDOM" value="object:328">UNITED KINGDOM</option><option label="UNITED STATES"
                                                                                                 value="object:329">UNITED STATES</option><option
                        label="URUGUAY" value="object:330">URUGUAY</option><option label="UZBEKISTAN"
                                                                                   value="object:331">UZBEKISTAN</option><option
                        label="VANUATU" value="object:332">VANUATU</option><option label="VENEZUELA" value="object:333">VENEZUELA</option><option
                        label="VIETNAM" value="object:334">VIETNAM</option><option label="YEMEN" value="object:335">YEMEN</option><option
                        label="ZAMBIA" value="object:336">ZAMBIA</option><option label="ZIMBABWE" value="object:337">ZIMBABWE</option></select>
        </span>
                                                <!----><span class="col-sm-12 col-md-5 zip-block"
                                                             ng-if="zipCodeRequired">
            <div class="copart-black-color">
                Zip / Postal Code:<sup class="reqField">*</sup>
            </div>
            <input class="form-control martop loc-zip-input ng-pristine ng-untouched ng-empty ng-invalid ng-invalid-required ng-valid-pattern ng-valid-maxlength"
                   maxlength="10" name="zipCode" ng-model="userLocation.zipCode" required=""
                   ng-pattern="/^[a-zA-Z0-9\-\s]+$/">
                                                    <!---->
        </span><!---->
                                            </div>
                                            <div class="modal-footer martop ">
                                                <button class="btn btn-default" data-dismiss="modal" type="button">
                                                    Close
                                                </button>
                                                <button class="btn btn-primary" type="submit">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div int-buyer-pop-up="null">
                        <div cms-content="" fragment="/content/us/en/international-buyer-popup">
                            <div ng-show="isIntBuyer" class="ng-hide">
                                <span class="dropdown-arrow-mark"></span>
                                <div id="internationUsersModal" class="header_int_buyer_dropdown">
                                    <div>
                                        <div class="international_buyer_section">
                                            <div class="text-center">
                                                <label class="your_location_header"
                                                       data-uname="homepageIntBuyerHeading">Your Location</label>
                                                <span class="header-sprite close-int-pop-up"
                                                      ng-click="closeIntPopUpModal()"></span>
                                            </div>
                                            <div>
                                                <div class="text-center">
                                                    <span class="text-center mt-10 header-sprite location-icon"
                                                          data-uname="homepageDdlanguageTextHeading"></span>
                                                    <div class="description-text mb-25">
                                                        <p class="fs14">Are you in</p>
                                                        <p class="detected_country">?</p>
                                                    </div>
                                                </div>
                                                <div class="mt-20 d-f j-c_s-b">
                                                    <label class="lang_region_selection_block pos-relative w100">
                                                        <select class="form-control langDropdown_select ng-pristine ng-untouched ng-valid ng-not-empty"
                                                                data-uname="homepageDdlanguage" ng-model="selectedLang"
                                                                ng-change="changePopupLanguage(selectedLang)"
                                                                ng-options="obj.value as obj.name for obj in userConfig.langOptions">
                                                            <option label="English" value="string:en"
                                                                    selected="selected">English
                                                            </option>
                                                            <option label="Español" value="string:es">Español</option>
                                                            <option label="Français Canadien" value="string:fr-CA">
                                                                Français Canadien
                                                            </option>
                                                            <option label="العربية" value="string:ar">العربية</option>
                                                            <option label="Русский" value="string:ru">Русский</option>
                                                            <option label="Polski" value="string:pl">Polski</option>
                                                        </select>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="region_section mt-20">
                                            <div class="mt-20">
                                                <div class="custom_meta_dropdown copart_countries pos-relative user-select-none">
                                                    <input type="checkbox" id="toggle-int-user"
                                                           class="toggle-int-user-dropdown ng-hide" ng-hide="true">
                                                    <label class="region_selected_block pos-relative"
                                                           for="toggle-int-user">
                            <span class="copart_country_items m-0-i a-i_c">
<!--                                <img alt="Selected Country Flag" class="img-responsive max-w-42"-->
                                <!--                                     src="https://www.copart.com/content/us.svg"/>-->
                                <span></span>
                            </span>
                                                    </label>
                                                    <ul class="list-style-none copart_country_list d-f j-c_s-b f-w_w"
                                                        role="toggle">
                                                        <!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/afghanistan?intcmp=web_lp_international_buyers_en-lp_afghanistan"
                                                               href="/content/us/en/landing-page/afghanistan?intcmp=web_lp_international_buyers_en-lp_afghanistan">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Afghanistan</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/albania?intcmp=web_lp_international_buyers_en-lp_albania"
                                                               href="/content/us/en/landing-page/albania?intcmp=web_lp_international_buyers_en-lp_albania">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Albania</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/angola?intcmp=web_lp_international_buyers_en-lp_angola"
                                                               href="/content/us/en/landing-page/angola?intcmp=web_lp_international_buyers_en-lp_angola">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Angola</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/anguilla?intcmp=web_lp_international_buyers_en-lp_anguilla"
                                                               href="/content/us/en/landing-page/anguilla?intcmp=web_lp_international_buyers_en-lp_anguilla">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Anguilla</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/antigua-barbuda?intcmp=web_lp_international_buyers_en-lp_antigua_barbuda"
                                                               href="/content/us/en/landing-page/antigua-barbuda?intcmp=web_lp_international_buyers_en-lp_antigua_barbuda">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Antigua and Barbuda</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/argentina?intcmp=web_lp_international_buyers_en-lp_argentina"
                                                               href="/content/us/en/landing-page/argentina?intcmp=web_lp_international_buyers_en-lp_argentina">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Argentina</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/armenia?intcmp=web_lp_international_buyers_en-lp_armenia"
                                                               href="/content/us/en/landing-page/armenia?intcmp=web_lp_international_buyers_en-lp_armenia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Armenia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/aruba?intcmp=web_lp_international_buyers_en-lp_aruba"
                                                               href="/content/us/en/landing-page/aruba?intcmp=web_lp_international_buyers_en-lp_aruba">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Aruba</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/australia?intcmp=web_lp_international_buyers_en-lp_australia"
                                                               href="/content/us/en/landing-page/australia?intcmp=web_lp_international_buyers_en-lp_australia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Australia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/austria?intcmp=web_lp_international_buyers_en-lp_austria"
                                                               href="/content/us/en/landing-page/austria?intcmp=web_lp_international_buyers_en-lp_austria">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Austria</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/azerbaijan?intcmp=web_lp_international_buyers_en-lp_azerbaijan"
                                                               href="/content/us/en/landing-page/azerbaijan?intcmp=web_lp_international_buyers_en-lp_azerbaijan">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Azerbaijan</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/bahamas?intcmp=web_lp_international_buyers_en-lp_bahamas"
                                                               href="/content/us/en/landing-page/bahamas?intcmp=web_lp_international_buyers_en-lp_bahamas">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Bahamas</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/bahrain?intcmp=web_lp_international_buyers_en-lp_bahrain"
                                                               href="/content/us/en/landing-page/bahrain?intcmp=web_lp_international_buyers_en-lp_bahrain">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Bahrain</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/barbados?intcmp=web_lp_international_buyers_en-lp_barbados"
                                                               href="/content/us/en/landing-page/barbados?intcmp=web_lp_international_buyers_en-lp_barbados">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Barbados</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/belarus?intcmp=web_lp_international_buyers_en-lp_belarus"
                                                               href="/content/us/en/landing-page/belarus?intcmp=web_lp_international_buyers_en-lp_belarus">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Belarus</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/belgium?intcmp=web_lp_international_buyers_en-lp_belgium"
                                                               href="/content/us/en/landing-page/belgium?intcmp=web_lp_international_buyers_en-lp_belgium">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Belgium</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/belize?intcmp=web_lp_international_buyers_en-lp_belize"
                                                               href="/content/us/en/landing-page/belize?intcmp=web_lp_international_buyers_en-lp_belize">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Belize</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/benin?intcmp=web_lp_international_buyers_en-lp_benin"
                                                               href="/content/us/en/landing-page/benin?intcmp=web_lp_international_buyers_en-lp_benin">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Benin</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/bolivia?intcmp=web_lp_international_buyers_en-lp_bolivia"
                                                               href="/content/us/en/landing-page/bolivia?intcmp=web_lp_international_buyers_en-lp_bolivia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Bolivia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/bosnia-and-herzegovina?intcmp=web_lp_international_buyers_en-lp_bosnia_and_herzegovina"
                                                               href="/content/us/en/landing-page/bosnia-and-herzegovina?intcmp=web_lp_international_buyers_en-lp_bosnia_and_herzegovina">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Bosnia and Herzegovina</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/brazil?intcmp=web_lp_international_buyers_en-lp_brazil"
                                                               href="/content/us/en/landing-page/brazil?intcmp=web_lp_international_buyers_en-lp_brazil">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Brazil</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/british-virgin-islands?intcmp=web_lp_international_buyers_en-lp_british_virgin_islands"
                                                               href="/content/us/en/landing-page/british-virgin-islands?intcmp=web_lp_international_buyers_en-lp_british_virgin_islands">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>British Virgin Islands</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/bulgaria?intcmp=web_lp_international_buyers_en-lp_bulgaria"
                                                               href="/content/us/en/landing-page/bulgaria?intcmp=web_lp_international_buyers_en-lp_bulgaria">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Bulgaria</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/burkina-faso?intcmp=web_lp_international_buyers_en-lp_burkina_faso"
                                                               href="/content/us/en/landing-page/burkina-faso?intcmp=web_lp_international_buyers_en-lp_burkina_faso">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Burkina Faso</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/cambodia?intcmp=web_lp_international_buyers_en-lp_cambodia"
                                                               href="/content/us/en/landing-page/cambodia?intcmp=web_lp_international_buyers_en-lp_cambodia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Cambodia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/cameroon?intcmp=web_lp_international_buyers_en-lp_cameroon"
                                                               href="/content/us/en/landing-page/cameroon?intcmp=web_lp_international_buyers_en-lp_cameroon">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Cameroon</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/cayman-islands?intcmp=web_lp_international_buyers_en-lp_cayman_islands"
                                                               href="/content/us/en/landing-page/cayman-islands?intcmp=web_lp_international_buyers_en-lp_cayman_islands">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Cayman Islands</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/chile?intcmp=web_lp_international_buyers_en-lp_chile"
                                                               href="/content/us/en/landing-page/chile?intcmp=web_lp_international_buyers_en-lp_chile">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Chile</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/china?intcmp=web_lp_international_buyers_en-lp_china"
                                                               href="/content/us/en/landing-page/china?intcmp=web_lp_international_buyers_en-lp_china">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>China</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/colombia?intcmp=web_lp_international_buyers_en-lp_colombia"
                                                               href="/content/us/en/landing-page/colombia?intcmp=web_lp_international_buyers_en-lp_colombia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Colombia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/costa-rica?intcmp=web_lp_international_buyers_en-lp_costa-rica"
                                                               href="/content/us/en/landing-page/costa-rica?intcmp=web_lp_international_buyers_en-lp_costa-rica">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Costa Rica</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/croatia?intcmp=web_lp_international_buyers_en-lp_croatia"
                                                               href="/content/us/en/landing-page/croatia?intcmp=web_lp_international_buyers_en-lp_croatia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Croatia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/curacao?intcmp=web_lp_international_buyers_en-lp_curacao"
                                                               href="/content/us/en/landing-page/curacao?intcmp=web_lp_international_buyers_en-lp_curacao">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Curacao</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/cyprus?intcmp=web_lp_international_buyers_en-lp_cyprus"
                                                               href="/content/us/en/landing-page/cyprus?intcmp=web_lp_international_buyers_en-lp_cyprus">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Cyprus</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/czech-republic?intcmp=web_lp_international_buyers_en-lp_czech_republic"
                                                               href="/content/us/en/landing-page/czech-republic?intcmp=web_lp_international_buyers_en-lp_czech_republic">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Czech Republic</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/denmark?intcmp=web_lp_international_buyers_en-lp_denmark"
                                                               href="/content/us/en/landing-page/denmark?intcmp=web_lp_international_buyers_en-lp_denmark">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Denmark</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/dominican-republic?intcmp=web_lp_international_buyers_en-lp_dominican-republic"
                                                               href="/content/us/en/landing-page/dominican-republic?intcmp=web_lp_international_buyers_en-lp_dominican-republic">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Dominican Republic</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/ecuador?intcmp=web_lp_international_buyers_en-lp_ecuador"
                                                               href="/content/us/en/landing-page/ecuador?intcmp=web_lp_international_buyers_en-lp_ecuador">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Ecuador</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/egypt?intcmp=web_lp_international_buyers_en-lp_egypt"
                                                               href="/content/us/en/landing-page/egypt?intcmp=web_lp_international_buyers_en-lp_egypt">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Egypt</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/el-salvador?intcmp=web_lp_international_buyers_en-lp_el-salvador"
                                                               href="/content/us/en/landing-page/el-salvador?intcmp=web_lp_international_buyers_en-lp_el-salvador">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>El Salvador</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/equatorial-guinea?intcmp=web_lp_international_buyers_en-lp_equatorial_guinea"
                                                               href="/content/us/en/landing-page/equatorial-guinea?intcmp=web_lp_international_buyers_en-lp_equatorial_guinea">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Equatorial Guinea</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/estonia?intcmp=web_lp_international_buyers_en-lp_estonia"
                                                               href="/content/us/en/landing-page/estonia?intcmp=web_lp_international_buyers_en-lp_estonia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Estonia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/finland?intcmp=web_lp_international_buyers_en-lp_finland"
                                                               href="/content/us/en/landing-page/finland?intcmp=web_lp_international_buyers_en-lp_finland">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Finland</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/gabon?intcmp=web_lp_international_buyers_en-lp_gabon"
                                                               href="/content/us/en/landing-page/gabon?intcmp=web_lp_international_buyers_en-lp_gabon">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Gabon</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/gambia?intcmp=web_lp_international_buyers_en-lp_gambia"
                                                               href="/content/us/en/landing-page/gambia?intcmp=web_lp_international_buyers_en-lp_gambia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Gambia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/georgia?intcmp=web_lp_international_buyers_en-lp_georgia"
                                                               href="/content/us/en/landing-page/georgia?intcmp=web_lp_international_buyers_en-lp_georgia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Georgia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/ghana?intcmp=web_lp_international_buyers_en-lp_ghana"
                                                               href="/content/us/en/landing-page/ghana?intcmp=web_lp_international_buyers_en-lp_ghana">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Ghana</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/gibraltar?intcmp=web_lp_international_buyers_en-lp_gibraltar"
                                                               href="/content/us/en/landing-page/gibraltar?intcmp=web_lp_international_buyers_en-lp_gibraltar">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Gibraltar</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/greece?intcmp=web_lp_international_buyers_en-lp_greece"
                                                               href="/content/us/en/landing-page/greece?intcmp=web_lp_international_buyers_en-lp_greece">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Greece</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/guam?intcmp=web_lp_international_buyers_en-lp_guam"
                                                               href="/content/us/en/landing-page/guam?intcmp=web_lp_international_buyers_en-lp_guam">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Guam</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/guatemala?intcmp=web_lp_international_buyers_en-lp_guatemala"
                                                               href="/content/us/en/landing-page/guatemala?intcmp=web_lp_international_buyers_en-lp_guatemala">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Guatemala</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/guyana?intcmp=web_lp_international_buyers_en-lp_guyana"
                                                               href="/content/us/en/landing-page/guyana?intcmp=web_lp_international_buyers_en-lp_guyana">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Guyana</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/haiti?intcmp=web_lp_international_buyers_en-lp_haiti"
                                                               href="/content/us/en/landing-page/haiti?intcmp=web_lp_international_buyers_en-lp_haiti">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Haiti</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/honduras?intcmp=web_lp_international_buyers_en-lp_honduras"
                                                               href="/content/us/en/landing-page/honduras?intcmp=web_lp_international_buyers_en-lp_honduras">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Honduras</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/hong-kong?intcmp=web_lp_international_buyers_en-lp_hong-kong"
                                                               href="/content/us/en/landing-page/hong-kong?intcmp=web_lp_international_buyers_en-lp_hong-kong">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Hong Kong</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/hungary?intcmp=web_lp_international_buyers_en-lp_hungary"
                                                               href="/content/us/en/landing-page/hungary?intcmp=web_lp_international_buyers_en-lp_hungary">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Hungary</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/indonesia?intcmp=web_lp_international_buyers_en-lp_indonesia"
                                                               href="/content/us/en/landing-page/indonesia?intcmp=web_lp_international_buyers_en-lp_indonesia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Indonesia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/iraq?intcmp=web_lp_international_buyers_en-lp_iraq"
                                                               href="/content/us/en/landing-page/iraq?intcmp=web_lp_international_buyers_en-lp_iraq">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Iraq</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/ireland?intcmp=web_lp_international_buyers_en-lp_ireland"
                                                               href="/content/us/en/landing-page/ireland?intcmp=web_lp_international_buyers_en-lp_ireland">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Ireland</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/israel?intcmp=web_lp_international_buyers_en-lp_israel"
                                                               href="/content/us/en/landing-page/israel?intcmp=web_lp_international_buyers_en-lp_israel">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Israel</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/italy?intcmp=web_lp_international_buyers_en-lp_italy"
                                                               href="/content/us/en/landing-page/italy?intcmp=web_lp_international_buyers_en-lp_italy">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Italy</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/ivory-coast?intcmp=web_lp_international_buyers_en-lp_ivory_coast"
                                                               href="/content/us/en/landing-page/ivory-coast?intcmp=web_lp_international_buyers_en-lp_ivory_coast">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Ivory Coast</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/jordan?intcmp=web_lp_international_buyers_en-lp_jordan"
                                                               href="/content/us/en/landing-page/jordan?intcmp=web_lp_international_buyers_en-lp_jordan">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Jordan</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/kazakhstan?intcmp=web_lp_international_buyers_en-lp_kazakhstan"
                                                               href="/content/us/en/landing-page/kazakhstan?intcmp=web_lp_international_buyers_en-lp_kazakhstan">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Kazakhstan</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/kuwait?intcmp=web_lp_international_buyers_en-lp_kuwait"
                                                               href="/content/us/en/landing-page/kuwait?intcmp=web_lp_international_buyers_en-lp_kuwait">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Kuwait</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/kyrgyzstan?intcmp=web_lp_international_buyers_en-lp_kyrgyzstan"
                                                               href="/content/us/en/landing-page/kyrgyzstan?intcmp=web_lp_international_buyers_en-lp_kyrgyzstan">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Kyrgyzstan</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/latvia?intcmp=web_lp_international_buyers_en-lp_latvia"
                                                               href="/content/us/en/landing-page/latvia?intcmp=web_lp_international_buyers_en-lp_latvia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Latvia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/lebanon?intcmp=web_lp_international_buyers_en-lp_lebanon"
                                                               href="/content/us/en/landing-page/lebanon?intcmp=web_lp_international_buyers_en-lp_lebanon">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Lebanon</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/liberia?intcmp=web_lp_international_buyers_en-lp_liberia"
                                                               href="/content/us/en/landing-page/liberia?intcmp=web_lp_international_buyers_en-lp_liberia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Liberia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/libya?intcmp=web_lp_international_buyers_en-lp_libya"
                                                               href="/content/us/en/landing-page/libya?intcmp=web_lp_international_buyers_en-lp_libya">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Libya</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/lithuania?intcmp=web_lp_international_buyers_en-lp_lithuania"
                                                               href="/content/us/en/landing-page/lithuania?intcmp=web_lp_international_buyers_en-lp_lithuania">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Lithuania</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/luxembourg?intcmp=web_lp_international_buyers_en-lp_luxembourg"
                                                               href="/content/us/en/landing-page/luxembourg?intcmp=web_lp_international_buyers_en-lp_luxembourg">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Luxembourg</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/macedonia?intcmp=web_lp_international_buyers_en-lp_macedonia"
                                                               href="/content/us/en/landing-page/macedonia?intcmp=web_lp_international_buyers_en-lp_macedonia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Macedonia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/malaysia?intcmp=web_lp_international_buyers_en-lp_malaysia"
                                                               href="/content/us/en/landing-page/malaysia?intcmp=web_lp_international_buyers_en-lp_malaysia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Malaysia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/malta?intcmp=web_lp_international_buyers_en-lp_malta"
                                                               href="/content/us/en/landing-page/malta?intcmp=web_lp_international_buyers_en-lp_malta">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Malta</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/mauritania?intcmp=web_lp_international_buyers_en-lp_mauritania"
                                                               href="/content/us/en/landing-page/mauritania?intcmp=web_lp_international_buyers_en-lp_mauritania">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Mauritania</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/mauritius?intcmp=web_lp_international_buyers_en-lp_mauritius"
                                                               href="/content/us/en/landing-page/mauritius?intcmp=web_lp_international_buyers_en-lp_mauritius">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Mauritius</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/mexico?intcmp=web_lp_international_buyers_en-lp_mexico"
                                                               href="/content/us/en/landing-page/mexico?intcmp=web_lp_international_buyers_en-lp_mexico">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Mexico</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/myanmar?intcmp=web_lp_international_buyers_en-lp_myanmar"
                                                               href="/content/us/en/landing-page/myanmar?intcmp=web_lp_international_buyers_en-lp_myanmar">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Myanmar</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/moldova?intcmp=web_lp_international_buyers_en-lp_moldova"
                                                               href="/content/us/en/landing-page/moldova?intcmp=web_lp_international_buyers_en-lp_moldova">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Moldova</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/mongolia?intcmp=web_lp_international_buyers_en-lp_mongolia"
                                                               href="/content/us/en/landing-page/mongolia?intcmp=web_lp_international_buyers_en-lp_mongolia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Mongolia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/morocco?intcmp=web_lp_international_buyers_en-lp_morocco"
                                                               href="/content/us/en/landing-page/morocco?intcmp=web_lp_international_buyers_en-lp_morocco">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Morocco</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/netherlands?intcmp=web_lp_international_buyers_en-lp_netherlands"
                                                               href="/content/us/en/landing-page/netherlands?intcmp=web_lp_international_buyers_en-lp_netherlands">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Netherlands</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/new-zealand?intcmp=web_lp_international_buyers_en-lp_new_zealand"
                                                               href="/content/us/en/landing-page/new-zealand?intcmp=web_lp_international_buyers_en-lp_new_zealand">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>New Zealand</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/nicaragua?intcmp=web_lp_international_buyers_en-lp_nicaragua"
                                                               href="/content/us/en/landing-page/nicaragua?intcmp=web_lp_international_buyers_en-lp_nicaragua">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Nicaragua</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/niger?intcmp=web_lp_international_buyers_en-lp_niger"
                                                               href="/content/us/en/landing-page/niger?intcmp=web_lp_international_buyers_en-lp_niger">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Niger</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/nigeria?intcmp=web_lp_international_buyers_en-lp_nigeria"
                                                               href="/content/us/en/landing-page/nigeria?intcmp=web_lp_international_buyers_en-lp_nigeria">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Nigeria</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/norway?intcmp=web_lp_international_buyers_en-lp_norway"
                                                               href="/content/us/en/landing-page/norway?intcmp=web_lp_international_buyers_en-lp_norway">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Norway</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/oman?intcmp=web_lp_international_buyers_en-lp_oman"
                                                               href="/content/us/en/landing-page/oman?intcmp=web_lp_international_buyers_en-lp_oman">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Oman</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/international/prepare-to-bid/business?intcmp=web_lp_international_buyers_en-lp_prepare-to-bid-business"
                                                               href="/content/us/en/landing-page/international/prepare-to-bid/business?intcmp=web_lp_international_buyers_en-lp_prepare-to-bid-business">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Other - For Businesses</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/international/prepare-to-bid/individual?intcmp=web_lp_international_buyers_en-lp_prepare-to-bid-individual"
                                                               href="/content/us/en/landing-page/international/prepare-to-bid/individual?intcmp=web_lp_international_buyers_en-lp_prepare-to-bid-individual">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Other - For Individuals</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/pakistan?intcmp=web_lp_international_buyers_en-lp_pakistan"
                                                               href="/content/us/en/landing-page/pakistan?intcmp=web_lp_international_buyers_en-lp_pakistan">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Pakistan</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/panama?intcmp=web_lp_international_buyers_en-lp_panama"
                                                               href="/content/us/en/landing-page/panama?intcmp=web_lp_international_buyers_en-lp_panama">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Panama</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/paraguay?intcmp=web_lp_international_buyers_en-lp_paraguay"
                                                               href="/content/us/en/landing-page/paraguay?intcmp=web_lp_international_buyers_en-lp_paraguay">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Paraguay</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/peru?intcmp=web_lp_international_buyers_en-lp_peru"
                                                               href="/content/us/en/landing-page/peru?intcmp=web_lp_international_buyers_en-lp_peru">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Peru</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/poland?intcmp=web_lp_international_buyers_en-lp_poland"
                                                               href="/content/us/en/landing-page/poland?intcmp=web_lp_international_buyers_en-lp_poland">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Poland</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/portugal?intcmp=web_lp_international_buyers_en-lp_portugal"
                                                               href="/content/us/en/landing-page/portugal?intcmp=web_lp_international_buyers_en-lp_portugal">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Portugal</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/philippines?intcmp=web_lp_international_buyers_en-lp_philippines"
                                                               href="/content/us/en/landing-page/philippines?intcmp=web_lp_international_buyers_en-lp_philippines">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Philippines</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/puerto-rico?intcmp=web_lp_international_buyers_en-lp_puerto_rico"
                                                               href="/content/us/en/landing-page/puerto-rico?intcmp=web_lp_international_buyers_en-lp_puerto_rico">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Puerto-Rico</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/qatar?intcmp=web_lp_international_buyers_en-lp_qatar"
                                                               href="/content/us/en/landing-page/qatar?intcmp=web_lp_international_buyers_en-lp_qatar">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Qatar</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/romania?intcmp=web_lp_international_buyers_en-lp_romania"
                                                               href="/content/us/en/landing-page/romania?intcmp=web_lp_international_buyers_en-lp_romania">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Romania</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/russia?intcmp=web_lp_international_buyers_en-lp_russia"
                                                               href="/content/us/en/landing-page/russia?intcmp=web_lp_international_buyers_en-lp_russia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Russia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/saint-vincent?intcmp=web_lp_international_buyers_en-lp_saint_vincent"
                                                               href="/content/us/en/landing-page/saint-vincent?intcmp=web_lp_international_buyers_en-lp_saint_vincent">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Saint Vincent and The Grenadines</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/saudi-arabia?intcmp=web_lp_international_buyers_en-lp_saudi_arabia"
                                                               href="/content/us/en/landing-page/saudi-arabia?intcmp=web_lp_international_buyers_en-lp_saudi_arabia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Saudi Arabia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/senegal?intcmp=web_lp_international_buyers_en-lp_senegal"
                                                               href="/content/us/en/landing-page/senegal?intcmp=web_lp_international_buyers_en-lp_senegal">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Senegal</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/serbia?intcmp=web_lp_international_buyers_en-lp_serbia"
                                                               href="/content/us/en/landing-page/serbia?intcmp=web_lp_international_buyers_en-lp_serbia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Serbia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/sierra-leone?intcmp=web_lp_international_buyers_en-lp_sierra_leone"
                                                               href="/content/us/en/landing-page/sierra-leone?intcmp=web_lp_international_buyers_en-lp_sierra_leone">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Sierra Leone</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/singapore?intcmp=web_lp_international_buyers_en-lp_singapore"
                                                               href="/content/us/en/landing-page/singapore?intcmp=web_lp_international_buyers_en-lp_singapore">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Singapore</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/sint-maarten?intcmp=web_lp_international_buyers_en-lp_sint_maarten"
                                                               href="/content/us/en/landing-page/sint-maarten?intcmp=web_lp_international_buyers_en-lp_sint_maarten">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Sint Maarten</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/slovakia?intcmp=web_lp_international_buyers_en-lp_slovakia"
                                                               href="/content/us/en/landing-page/slovakia?intcmp=web_lp_international_buyers_en-lp_slovakia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Slovakia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/slovenia?intcmp=web_lp_international_buyers_en-lp_slovenia"
                                                               href="/content/us/en/landing-page/slovenia?intcmp=web_lp_international_buyers_en-lp_slovenia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Slovenia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/south-africa?intcmp=web_lp_international_buyers_en-lp_south_africa"
                                                               href="/content/us/en/landing-page/south-africa?intcmp=web_lp_international_buyers_en-lp_south_africa">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>South Africa</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/south-korea?intcmp=web_lp_international_buyers_en-lp_korea"
                                                               href="/content/us/en/landing-page/south-korea?intcmp=web_lp_international_buyers_en-lp_korea">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>South Korea</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/suriname?intcmp=web_lp_international_buyers_en-lp_suriname"
                                                               href="/content/us/en/landing-page/suriname?intcmp=web_lp_international_buyers_en-lp_suriname">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Suriname</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/sweden?intcmp=web_lp_international_buyers_en-lp_sweden"
                                                               href="/content/us/en/landing-page/sweden?intcmp=web_lp_international_buyers_en-lp_sweden">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Sweden</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/switzerland?intcmp=web_lp_international_buyers_en-lp_switzerland"
                                                               href="/content/us/en/landing-page/switzerland?intcmp=web_lp_international_buyers_en-lp_switzerland">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Switzerland</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/taiwan?intcmp=web_lp_international_buyers_en-lp_taiwan"
                                                               href="/content/us/en/landing-page/taiwan?intcmp=web_lp_international_buyers_en-lp_taiwan">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Taiwan</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/thailand?intcmp=web_lp_international_buyers_en-lp_thailand"
                                                               href="/content/us/en/landing-page/thailand?intcmp=web_lp_international_buyers_en-lp_thailand">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Thailand</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/tajikistan?intcmp=web_lp_international_buyers_en-lp_tajikistan"
                                                               href="/content/us/en/landing-page/tajikistan?intcmp=web_lp_international_buyers_en-lp_tajikistan">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Tajikistan</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/togo?intcmp=web_lp_international_buyers_en-lp_togo"
                                                               href="/content/us/en/landing-page/togo?intcmp=web_lp_international_buyers_en-lp_togo">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Togo</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/tunisia?intcmp=web_lp_international_buyers_en-lp_tunisia"
                                                               href="/content/us/en/landing-page/tunisia?intcmp=web_lp_international_buyers_en-lp_tunisia">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Tunisia</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/turkey?intcmp=web_lp_international_buyers_en-lp_turkey"
                                                               href="/content/us/en/landing-page/turkey?intcmp=web_lp_international_buyers_en-lp_turkey">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Turkey</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/turkmenistan?intcmp=web_lp_international_buyers_en-lp_turkmenistan"
                                                               href="/content/us/en/landing-page/turkmenistan?intcmp=web_lp_international_buyers_en-lp_turkmenistan">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Turkmenistan</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/turks-and-caicos?intcmp=web_lp_international_buyers_en-lp_turks_and_caicos"
                                                               href="/content/us/en/landing-page/turks-and-caicos?intcmp=web_lp_international_buyers_en-lp_turks_and_caicos">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Turks and Caicos </span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/uae?intcmp=web_lp_international_buyers_en-lp_uae"
                                                               href="/content/us/en/landing-page/uae?intcmp=web_lp_international_buyers_en-lp_uae">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>UAE</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/ukraine?intcmp=web_lp_international_buyers_en-lp_ukraine"
                                                               href="/content/us/en/landing-page/ukraine?intcmp=web_lp_international_buyers_en-lp_ukraine">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Ukraine</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/uruguay?intcmp=web_lp_international_buyers_en-lp_uruguay"
                                                               href="/content/us/en/landing-page/uruguay?intcmp=web_lp_international_buyers_en-lp_uruguay">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Uruguay</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/us-virgin-islands?intcmp=web_lp_international_buyers_en-lp_us_virgin_islands"
                                                               href="/content/us/en/landing-page/us-virgin-islands?intcmp=web_lp_international_buyers_en-lp_us_virgin_islands">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>U.S. Virgin Islands</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/uzbekistan?intcmp=web_lp_international_buyers_en-lp_uzbekistan"
                                                               href="/content/us/en/landing-page/uzbekistan?intcmp=web_lp_international_buyers_en-lp_uzbekistan">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Uzbekistan</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/venezuela?intcmp=web_lp_international_buyers_en-lp_venezuela"
                                                               href="/content/us/en/landing-page/venezuela?intcmp=web_lp_international_buyers_en-lp_venezuela">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Venezuela</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/vietnam?intcmp=web_lp_international_buyers_en-lp_vietnam"
                                                               href="/content/us/en/landing-page/vietnam?intcmp=web_lp_international_buyers_en-lp_vietnam">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Vietnam</span>
                                                            </a>
                                                        </li><!---->
                                                        <li class="copart_country_items"
                                                            ng-repeat="country in listOfCountries">
                                                            <a ng-href="/content/us/en/landing-page/yemen?intcmp=web_lp_international_buyers_en-lp_yemen"
                                                               href="/content/us/en/landing-page/yemen?intcmp=web_lp_international_buyers_en-lp_yemen">
                                                                <!--                                    <img alt="{{country.countryName}}" class="img-responsive max-w-42"-->
                                                                <!--                                         src="https://www.copart.com/content/us.svg"/>-->
                                                                <span>Yemen</span>
                                                            </a>
                                                        </li><!---->
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-20">
                                            <button site-catalyst="{'fname':'doTagSelectedLanguage'}"
                                                    class="get-started-btn" data-uname="homepageDdlanguageYes"
                                                    ng-click="goToIntResource(selectedLang,detectedCountryObj.countryUrl);">
                                                Get Started
                                            </button>
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

    <span class="floating-design-sprite header-floating-design"></span>

    <!--Remove After A/B testing Done-->
    <script type="text/javascript">
        dataLayer.push({'event': 'optimize.activate.header'});
    </script>
    <!--Remove After A/B testing Done-->

</header>
<div class="full-width-section-top">
    <ul class="navigation-items">
        <li class="uniqueClassName">
            <div class="Choosecountry">
                <!--<img alt="Location Icon" class="mobile-remove3" src="/content/location.png" />-->
                <div class="clearfix dropdown-wrapper" style="float: left; padding: 0 4px;">
                    <div class="dropdown">
                        <button class="button-primary dropdown-toggle" data-toggle="dropdown" type="button">Pakistan<i
                                    style="float: right; font-style: normal;">▾</i><img alt="Pakistan Flag"
                                                                                        class="mobile-remove2"
                                                                                        data-entity-type=""
                                                                                        data-entity-uuid=""
                                                                                        src="/content/pk.svg"></button>
                        <ul class="dropdown-menu dropdown-menu-left intl-dropdown"
                            style="margin-top: 0 !important; list-style-type: none !important; font-size: 14px !important; padding: 10px 0 0 0 !important; width: 265px !important; height: 315px; overflow: auto; ">
                            <li value=""><a href="/content/us/en/landing-page/afghanistan">Afghanistan</a></li>
                            <li value=""><a href="/content/us/en/landing-page/albania">Albania</a></li>
                            <li value=""><a href="/content/us/en/landing-page/angola">Angola</a></li>
                            <li value=""><a href="/content/us/en/landing-page/anguilla">Anguilla</a></li>
                            <li value=""><a href="/content/us/en/landing-page/antigua-barbuda">Antigua and Barbuda</a>
                            </li>
                            <li value=""><a href="/content/us/en/landing-page/argentina">Argentina</a></li>
                            <li value=""><a href="/content/us/en/landing-page/armenia">Armenia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/aruba">Aruba</a></li>
                            <li value=""><a href="/content/us/en/landing-page/australia">Australia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/austria">Austria</a></li>
                            <li value=""><a href="/content/us/en/landing-page/azerbaijan">Azerbaijan</a></li>
                            <li value=""><a href="/content/us/en/landing-page/bahamas">Bahamas</a></li>
                            <li value=""><a href="/content/us/en/landing-page/barbados">Barbados</a></li>
                            <li value=""><a href="/content/us/en/landing-page/belarus">Belarus</a></li>
                            <li value=""><a href="/content/us/en/landing-page/belgium">Belgium</a></li>
                            <li value=""><a href="/content/us/en/landing-page/belize">Belize</a></li>
                            <li value=""><a href="/content/us/en/landing-page/benin">Benin</a></li>
                            <li value=""><a href="/content/us/en/landing-page/bolivia">Bolivia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/bosnia-and-herzegovina">Bosnia and
                                    Herzegovina</a></li>
                            <li value=""><a href="/content/us/en/landing-page/brazil">Brazil</a></li>
                            <li value=""><a href="/content/us/en/landing-page/british-virgin-islands">British Virgin
                                    Islands</a></li>
                            <li value=""><a href="/content/us/en/landing-page/bulgaria">Bulgaria</a></li>
                            <li value=""><a href="/content/us/en/landing-page/burkina-faso">Burkina Faso</a></li>
                            <li value=""><a href="/content/us/en/landing-page/cambodia">Cambodia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/cameroon">Cameroon</a></li>
                            <li value=""><a href="/content/us/en/landing-page/cayman-islands">Cayman Islands</a></li>
                            <li value=""><a href="/content/us/en/landing-page/chile">Chile</a></li>
                            <li value=""><a href="/content/us/en/landing-page/china">China</a></li>
                            <li value=""><a href="/content/us/en/landing-page/colombia">Colombia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/costa-rica">Costa Rica</a></li>
                            <li value=""><a href="/content/us/en/landing-page/croatia">Croatia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/curacao">Curacao</a></li>
                            <li value=""><a href="/content/us/en/landing-page/cyprus">Cyprus</a></li>
                            <li value=""><a href="/content/us/en/landing-page/czech-republic">Czech Republic</a></li>
                            <li value=""><a href="/content/us/en/landing-page/denmark">Denmark</a></li>
                            <li value=""><a href="/content/us/en/landing-page/dominican-republic">Dominican Republic</a>
                            </li>
                            <li value=""><a href="/content/us/en/landing-page/ecuador">Ecuador</a></li>
                            <li value=""><a href="/content/us/en/landing-page/egypt">Egypt</a></li>
                            <li value=""><a href="/content/us/en/landing-page/el-salvador">El Salvador</a></li>
                            <li value=""><a href="/content/us/en/landing-page/equatorial-guinea">Equatorial Guinea</a>
                            </li>
                            <li value=""><a href="/content/us/en/landing-page/finland">Finland</a></li>
                            <li value=""><a href="/content/us/en/landing-page/estonia">Estonia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/gabon">Gabon</a></li>
                            <li value=""><a href="/content/us/en/landing-page/gambia">Gambia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/georgia">Georgia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/ghana">Ghana</a></li>
                            <li value=""><a href="/content/us/en/landing-page/gibraltar">Gibraltar</a></li>
                            <li value=""><a href="/content/us/en/landing-page/greece">Greece</a></li>
                            <li value=""><a href="/content/us/en/landing-page/guam">Guam</a></li>
                            <li value=""><a href="/content/us/en/landing-page/guatemala">Guatemala</a></li>
                            <li value=""><a href="/content/us/en/landing-page/guyana">Guyana</a></li>
                            <li value=""><a href="/content/us/en/landing-page/haiti">Haiti</a></li>
                            <li value=""><a href="/content/us/en/landing-page/honduras">Honduras</a></li>
                            <li value=""><a href="/content/us/en/landing-page/hong-kong">Hong Kong</a></li>
                            <li value=""><a href="/content/us/en/landing-page/hungary">Hungary</a></li>
                            <li value=""><a href="/content/us/en/landing-page/indonesia">Indonesia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/iraq">Iraq</a></li>
                            <li value=""><a href="/content/us/en/landing-page/ireland">Ireland</a></li>
                            <li value=""><a href="/content/us/en/landing-page/israel">Israel</a></li>
                            <li value=""><a href="/content/us/en/landing-page/italy">Italy</a></li>
                            <li value=""><a href="/content/us/en/landing-page/ivory-coast">Ivory Coast</a></li>
                            <li value=""><a href="/content/us/en/landing-page/jamaica">Jamaica</a></li>
                            <li value=""><a href="/content/us/en/landing-page/jordan">Jordan</a></li>
                            <li value=""><a href="/content/us/en/landing-page/kazakhstan">Kazakhstan</a></li>
                            <li value=""><a href="/content/us/en/landing-page/kuwait">Kuwait</a></li>
                            <li value=""><a href="/content/us/en/landing-page/kyrgyzstan">Kyrgyzstan</a></li>
                            <li value=""><a href="/content/us/en/landing-page/latvia">Latvia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/lebanon">Lebanon</a></li>
                            <li value=""><a href="/content/us/en/landing-page/liberia">Liberia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/libya">Libya</a></li>
                            <li value=""><a href="/content/us/en/landing-page/lithuania">Lithuania</a></li>
                            <li value=""><a href="/content/us/en/landing-page/luxembourg">Luxembourg</a></li>
                            <li value=""><a href="/content/us/en/landing-page/macedonia">Macedonia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/malaysia">Malaysia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/malta">Malta</a></li>
                            <li value=""><a href="/content/us/en/landing-page/mauritania">Mauritania</a></li>
                            <li value=""><a href="/content/us/en/landing-page/mauritius">Mauritius</a></li>
                            <li value=""><a href="/content/us/en/landing-page/mexico">Mexico</a></li>
                            <li value=""><a href="/content/us/en/landing-page/myanmar">Myanmar</a></li>
                            <li value=""><a href="/content/us/en/landing-page/moldova">Moldova</a></li>
                            <li value=""><a href="/content/us/en/landing-page/mongolia">Mongolia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/morocco">Morocco</a></li>
                            <li value=""><a href="/content/us/en/landing-page/netherlands">Netherlands</a></li>
                            <li value=""><a href="/content/us/en/landing-page/new-zealand">New Zealand</a></li>
                            <li value=""><a href="/content/us/en/landing-page/nicaragua">Nicaragua</a></li>
                            <li value=""><a href="/content/us/en/landing-page/niger">Niger</a></li>
                            <li value=""><a href="/content/us/en/landing-page/nigeria">Nigeria</a></li>
                            <li value=""><a href="/content/us/en/landing-page/norway">Norway</a></li>
                            <li value=""><a href="/content/us/en/landing-page/oman">Oman</a></li>
                            <li value=""><a href="/content/us/en/landing-page/international/prepare-to-bid/business">Other
                                    - For Businesses</a></li>
                            <li value=""><a href="/content/us/en/landing-page/international/prepare-to-bid/individual">Other
                                    - For Individuals</a></li>
                            <li value=""><a href="/content/us/en/landing-page/pakistan">Pakistan</a></li>
                            <li value=""><a href="/content/us/en/landing-page/panama">Panama</a></li>
                            <li value=""><a href="/content/us/en/landing-page/paraguay">Paraguay</a></li>
                            <li value=""><a href="/content/us/en/landing-page/peru">Peru</a></li>
                            <li value=""><a href="/content/us/en/landing-page/poland">Poland</a></li>
                            <li value=""><a href="/content/us/en/landing-page/portugal">Portugal</a></li>
                            <li value=""><a href="/content/us/en/landing-page/philippines">Philippines</a></li>
                            <li value=""><a href="/content/us/en/landing-page/puerto-rico">Puerto-Rico</a></li>
                            <li value=""><a href="/content/us/en/landing-page/qatar">Qatar</a></li>
                            <li value=""><a href="/content/us/en/landing-page/romania">Romania</a></li>
                            <li value=""><a href="/content/us/en/landing-page/russia">Russia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/saint-vincent">Saint Vincent and The
                                    Grenadines</a></li>
                            <li value=""><a href="/content/us/en/landing-page/saudi-arabia">Saudi Arabia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/senegal">Senegal</a></li>
                            <li value=""><a href="/content/us/en/landing-page/serbial">Serbia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/sierra-leone">Sierra Leone</a></li>
                            <li value=""><a href="/content/us/en/landing-page/singapore">Singapore</a></li>
                            <li value=""><a href="/content/us/en/landing-page/sint-maarten">Sint Maarten</a></li>
                            <li value=""><a href="/content/us/en/landing-page/slovakia">Slovakia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/slovenia">Slovenia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/south-africa">South Africa</a></li>
                            <li value=""><a href="/content/us/en/landing-page/south-korea">South Korea</a></li>
                            <li value=""><a href="/content/us/en/landing-page/suriname">Suriname</a></li>
                            <li value=""><a href="/content/us/en/landing-page/sweden">Sweden</a></li>
                            <li value=""><a href="/content/us/en/landing-page/switzerland">Switzerland</a></li>
                            <li value=""><a href="/content/us/en/landing-page/taiwan">Taiwan</a></li>
                            <li value=""><a href="/content/us/en/landing-page/thailand">Thailand</a></li>
                            <li value=""><a href="/content/us/en/landing-page/tajikistan">Tajikistan</a></li>
                            <li value=""><a href="/content/us/en/landing-page/togo">Togo</a></li>
                            <li value=""><a href="/content/us/en/landing-page/tunisia">Tunisia</a></li>
                            <li value=""><a href="/content/us/en/landing-page/turkey">Turkey</a></li>
                            <li value=""><a href="/content/us/en/landing-page/turkmenistan">Turkmenistan</a></li>
                            <li value=""><a href="/content/us/en/landing-page/turks-and-caicos">Turks and Caicos </a>
                            </li>
                            <li value=""><a href="/content/us/en/landing-page/uae">UAE</a></li>
                            <li value=""><a href="/content/us/en/landing-page/ukraine">Ukraine</a></li>
                            <li value=""><a href="/content/us/en/landing-page/uruguay">Uruguay</a></li>
                            <li value=""><a href="/content/us/en/landing-page/us-virgin-islands">U.S. Virgin Islands</a>
                            </li>
                            <li value=""><a href="/content/us/en/landing-page/uzbekistan">Uzbekistan</a></li>
                            <li value=""><a href="/content/us/en/landing-page/venezuela">Venezuela</a></li>
                            <li value=""><a href="/content/us/en/landing-page/vietnam">Vietnam</a></li>
                            <li value=""><a href="/content/us/en/landing-page/yemen">Yemen</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </li>
        <li class="langswitcher">
            <ul class="ULClass">
                <li style="display: inline-block; width:10%;"><img alt="Global Icon" class="mobile-remove4"
                                                                   data-entity-type="" data-entity-uuid=""
                                                                   src="/content/globe_small.svg"></li>
                <li style="display: inline-block;"><span class="language-text">Language: </span></li>
                <li class="button_en"><a class="copart-language-btn-active">EN</a></li>
                <li class="button_local_language"><a class="copart-language-btn-2"
                                                     href="/content/us/en/landing-page/pakistan-ur?intcmp=web_lp_new-international_pakistan_en_en_nav_language_ur"><u>UR</u></a>
                </li>
            </ul>
        </li>
        <!--<li class="button1"><a class="authrep-btn" href="#scroll-rep"><strong>View Authorized Representative</strong></a></li>-->
    </ul>
</div>

<div class="inner-wrap d-f-c f-1">
    <!--<notifications></notifications>-->
    <global-alerts></global-alerts>


    <modal-directive></modal-directive>

    <div class="d-f-c f-1">

        <!--  main content goes here -->
        <div class="">

            <div>

            </div>


            <div ng-view autoscroll=""></div>
        </div>
        <!-- End main content goes here -->

        <!--  footer container  -->
        <div class="footer-section ng-cloak">
            <style name="commonFooterCSS">footer {
                    display: block
                }

                .footer {
                    background-color: #1d1e20;
                    color: #fff;
                    width: 100%;
                    padding-top: 40px !important
                }

                @media (max-width: 768px) {
                    .footer {
                        padding-top: 20px !important
                    }
                }

                .footer-column-0 .logo.copart-img {
                    padding-left: 20px
                }

                @media (max-width: 768px) {
                    .footer-column-0 .logo {
                        padding-left: 0
                    }
                }

                .footer a:link, .footer a:visited {
                    color: #fff !important
                }

                .footer a:hover, .footer a:visited {
                    text-decoration: underline
                }

                .footer .list-unstyled a {
                    font-weight: 400
                }

                .footer .footer-mid {
                    font-size: 12px
                }

                .footer .footer-links {
                    font-size: 13px
                }

                .footer .footer-links .list-unstyled {
                    line-height: 2em
                }

                .footer .brandlogo {
                    background-image: url(/images/footer/footer_logos.svg);
                    background-repeat: no-repeat;
                    display: block;
                    margin-bottom: 30px
                }

                .sprite-brand-ct {
                    background-position: -1px 0;
                    width: 118px;
                    height: 15px
                }

                .sprite-brand-NPA {
                    background-image: url(/images/footer/footer_logos-NPA.svg) !important;
                    background-position: -1px -142px !important;
                    width: 115px;
                    height: 41px;
                    background-size: 170px;
                    display: block
                }

                .brand-cashforcars {
                    background-image: url(/images/footer/cashforcars-logo-white.svg) !important;
                    width: 80%
                }

                .footer-mid {
                    padding: 10px 0 20px
                }

                .crtoys-noshow {
                    display: block
                }

                .footer-container .footer-title {
                    margin: 10px 0;
                    font: 16px/1.1 'Red Hat Display';
                    color: #ffb838
                }

                @media (min-width: 768px) and (max-width: 1023px) {
                    .footer-links li a {
                        font-size: 12px
                    }
                }

                @media (max-width: 768px) {
                    .footer .footer-links {
                        font-size: 12px;
                        padding-left: 0;
                        padding-right: 0
                    }
                }

                @media screen and (max-width: 479px) {
                    footer .footer-links {
                        font-size: 12px;
                        padding-left: 0 !important;
                        padding-right: 0 !important
                    }
                }

                @media only screen and (max-width: 500px) {
                    .footer-links, .footer-links a {
                        font-size: 11px !important;
                        margin-bottom: 5px
                    }
                }

                .copart_countries .footer-country-items {
                    z-index: 999
                }

                .copart_countries .footer-country-items .copart_country_items a {
                    padding: 5px 5px 5px 5px !important;
                    color: #fff !important
                }

                .copart_countries .dropdown-menu .footer-country-items li a {
                    background-color: #363a3f;
                    font-family: 'Red Hat Display 500';
                    letter-spacing: normal
                }

                #footer-container .orange-font {
                    color: #f76120 !important
                }

                .lang_region_selection_block:after {
                    background: #f9f9f9;
                    font-size: 10px;
                    color: #151317;
                    right: 5px;
                    top: 12px;
                    padding: 0 0 2px;
                    position: absolute;
                    pointer-events: none;
                    width: 15px
                }

                #footer-container .container-fluid {
                    padding: 0 15px
                }

                @media (max-width: 768px) {
                    .bot-banner-block .floating-design-sprite {
                        transform: scale(.6);
                        bottom: -35px;
                        left: 0
                    }

                    #footer-container .container-fluid {
                        padding: 0
                    }

                    #footer-container .footer-title {
                        text-align: center
                    }

                    #footer-container .footer-flex-container {
                        flex-direction: column
                    }

                    #footer-container .footer-column {
                        width: 100%
                    }

                    #footer-container .footer-column .list-unstyled.collapse {
                        display: none
                    }

                    #footer-container .footer-links ul li {
                        text-align: center !important
                    }

                    #footer-container .footer-links a {
                        font-size: 14px !important;
                        line-height: 26px
                    }

                    #footer-container .toggle-btn:after {
                        content: "\f107";
                        position: relative;
                        top: 3px;
                        display: inline-block;
                        cursor: pointer;
                        font: normal 400 24px/1 FontAwesome;
                        -webkit-font-smoothing: antialiased;
                        -moz-osx-font-smoothing: grayscale;
                        margin-left: 5px
                    }

                    #footer-container .copart_countries .footer-country-items {
                        z-index: 999;
                        left: -125px !important;
                        bottom: 44px;
                        margin-bottom: 0
                    }

                    #footer-container .copart_countries .footer-country-items .copart_country_items {
                        text-align: left !important
                    }

                    #footer-container .mobile-region-selected {
                        background: #363a3f;
                        border: 1px solid #363a3f
                    }

                    #footer-container .mobile-footer-dropdown {
                        display: flex;
                        justify-content: space-between
                    }

                    #footer-container .mobile-footer-dropdown .langDropdown_select {
                        height: 40px;
                        font-size: 14px;
                        padding: 8px 10px
                    }

                    #footer-container .app-links {
                        display: flex;
                        align-items: center;
                        flex-basis: 53%;
                        justify-content: flex-end;
                        padding-right: 15px
                    }

                    #footer-container .download-mobile-app {
                        margin-top: 25px
                    }

                    #footer-container .mobile-apps {
                        display: flex
                    }

                    #footer-container .app-links-2 {
                        justify-content: flex-start
                    }

                    #footer-container .app-links a {
                        margin-top: -4px
                    }

                    #footer-container .site-links {
                        flex-direction: column-reverse
                    }

                    #footer-container .copart-ca-sites, #footer-container .copart-sites, #footer-container .crashedtoys-sites {
                        display: flex;
                        max-height: 20px;
                        line-height: normal;
                        margin: 15px 0
                    }

                    #footer-container .crashedtoys-sites {
                        justify-content: space-evenly
                    }

                    #footer-container .copart-ca-sites li, #footer-container .copart-sites li, #footer-container .crashedtoys-sites li {
                        display: flex;
                        flex-basis: 33%;
                        justify-content: center
                    }

                    #footer-container .copart-ca-sites {
                        display: inline-block;
                        width: 100%
                    }

                    #footer-container .copart-ca-sites li:nth-child(2) {
                        margin-left: 28px
                    }

                    #footer-container .copart-ca-sites li {
                        width: 29%;
                        display: inline-block
                    }

                    #footer-container .copart-ca-sites a, #footer-container .copart-sites a, #footer-container .crashedtoys-sites a {
                        margin-bottom: 0
                    }

                    #footer-container .site-links .footer-copyright {
                        width: 100%;
                        text-align: center;
                        display: block;
                        font-size: 10px
                    }

                    #footer-container .site-links .footer-links {
                        flex-wrap: wrap;
                        margin: 10px 0;
                        font-size: 10px !important
                    }

                    #footer-container .site-links .footer-links a {
                        line-height: 26px;
                        font-size: 10px !important;
                        padding: 0 5px
                    }
                }

                .footer-container {
                    width: 100%
                }

                .footer .footer-column {
                    margin: 0
                }

                label {
                    display: inline-block;
                    max-width: 100%;
                    margin-bottom: 5px;
                    font-weight: 400
                }

                .relative {
                    position: relative
                }

                h4 {
                    font: 600 16px/22px "Red Hat Display", sans-serif !important;
                    margin-bottom: 10px
                }

                @media (min-width: 768px) and (max-width: 1023px) {
                    .footer-links li a {
                        font-size: 12px
                    }
                }

                @media (max-width: 768px) {
                    .footer .footer-links {
                        font-size: 12px;
                        padding-left: 0;
                        padding-right: 0
                    }
                }

                @media screen and (max-width: 479px) {
                    footer .footer-links {
                        font-size: 12px;
                        padding-left: 0 !important;
                        padding-right: 0 !important
                    }
                }

                @media screen and (max-width: 768px) {
                    .footer-title {
                        font-size: 16px
                    }
                }

                @media screen and (max-width: 768px) {
                    footer.footer {
                        padding: 15px 15px 0 20px
                    }
                }

                .footer-flex-container {
                    display: flex;
                    flex-basis: 100%;
                    flex-direction: row;
                    justify-content: space-between;
                    align-items: flex-start
                }

                .footer-column {
                    display: flex;
                    flex-direction: column;
                    flex-basis: 20%;
                    font-size: 14px
                }

                .footer-column .list-unstyled.collapse {
                    display: block
                }

                .footer-column-0 {
                    display: flex;
                    flex-direction: column;
                    flex-basis: auto;
                    width: 40%
                }

                .footer-column-1 {
                    display: flex;
                    flex-direction: column
                }

                .footer-copyright {
                    display: flex;
                    flex-basis: 20%
                }

                .footer-links {
                    display: flex;
                    justify-content: space-around;
                    flex-basis: 60%;
                    margin-bottom: 0
                }

                .brandlogo {
                    opacity: .35
                }

                .brandlogo:hover {
                    opacity: .75
                }

                .footer-dropdown-li {
                    width: 170px;
                    height: 40px;
                    margin: 20px 0
                }

                .crashedtoys-lang-dropdown {
                    margin: 0 auto
                }

                .footer-dropdown {
                    border-radius: 3px !important;
                    background-color: #363a3f !important;
                    color: #363a3f !important;
                    border: none !important
                }

                .footer .android-app-logo {
                    background-position: 0 -228px;
                    width: 101px;
                    height: 33px;
                    opacity: 1;
                    margin-bottom: 5px
                }

                .android-app-logo:hover {
                    opacity: 1
                }

                .footer .apple-app-logo {
                    background-position: 0 -187px;
                    width: 100px;
                    height: 40px;
                    opacity: 1;
                    margin-bottom: 5px
                }

                .apple-app-logo:hover {
                    opacity: 1
                }

                .desktop-only {
                    display: block
                }

                @media (max-width: 768px) {
                    .desktop-only {
                        display: none
                    }
                }

                .footer-container .mobile-only {
                    display: none
                }

                .mobile-only {
                    display: none
                }

                @media (max-width: 768px) {
                    .mobile-only {
                        display: block
                    }

                    .footer-container .mobile-only {
                        display: block
                    }
                }

                @media (max-width: 768px) {
                    #footer-container .footer-column .list-unstyled.collapse {
                        display: none
                    }

                    #footer-container .footer-column .list-unstyled.collapse.in {
                        display: block
                    }

                    #footer-container .copart-img {
                        margin-top: 20px
                    }

                    #footer-container .copart_countries {
                        width: 150px;
                        float: right
                    }

                    #footer-container .langDropdown_select {
                        width: 150px;
                        float: left
                    }
                }

                #footer-container .region_section .copart_country_list {
                    background-color: #363a3f;
                    top: -315px
                }

                #footer-container .langDropdown_select {
                    -webkit-appearance: none;
                    -moz-appearance: none;
                    appearance: none
                }

                #footer-container .langDropdown_select, #footer-container .region_section .copart_country_items {
                    border: 1px solid #363a3f !important;
                    background: #363a3f;
                    color: #fff
                }

                #footer-container .lang_region_selection_block:after, #footer-container .region_section .custom_meta_dropdown .region_selected_block:after {
                    content: url(/images/icons/union_white.svg);
                    background: #363a3f !important
                }

                #footer-container .region_section .copart_country_list a:hover {
                    background: #fff;
                    color: #363a3f !important;
                    font-weight: 700
                }

                #footer-container .footer-dropdown-li .toggle-language-dropdown {
                    width: 170px;
                    height: 42px;
                    text-align: left;
                    padding-left: 10px;
                    color: #fefefe !important;
                    font-size: 17px;
                    font-family: 'Red Hat Display 500';
                    font-weight: 400
                }

                #footer-container .footer-dropdown-li .lang_region_selection_block:after {
                    top: 10px;
                    padding: 0
                }

                #footer-container .footer-dropdown-li .language-dropdown-menu {
                    width: 172px;
                    left: -1px
                }

                #footer-container .footer-dropdown-li .language-dropdown-menu li a {
                    padding: 10px !important;
                    font-size: 14px;
                    font-family: 'Red Hat Display 500';
                    font-weight: 400;
                    background-color: #363a3f
                }

                #footer-container .footer-dropdown-li .language-dropdown-menu li a:hover {
                    background: #fff;
                    color: #363a3f !important;
                    font-weight: 700
                }

                #footer-container .mobile-only .footer-dropdown-li .language-dropdown-menu {
                    top: inherit;
                    bottom: 42px
                }

                #footer-container .mobile-only .footer-dropdown-li .language-dropdown-menu li a {
                    line-height: inherit;
                    margin: 0
                }

                #footer-container .mobile-only .footer-dropdown-li .lang_region_selection_block:after {
                    top: 13px
                }</style>
            <div lazy-load
                 load-eagerly="{{appInit.isUserAgentBOT || !appInit.enableModularResources || appInit.initialPageLayout == appInit.layout.NON_OPTIMIZED}}"
                 lazy-attr="cms-content-fragment"
                 load-on-interaction="true"
                 lazy-no-spinner="true"
                 compile-cms-content
                 parent-id="/content/us/en/sitefooter-v3"
                 fragment-id="common-footer">
            </div>
        </div>
        <!--  end footer container  -->
    </div>

</div>

<div class="modal fade" id="feedback" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
                <div class="modal-title bold">{{::locale.messages['app.label.feedback']}}</div>
            </div>
            <form ng-submit="submitFeedBack(feedback)">
                <div class="modal-body">
                    <div class="form-group ">
                        <label class="col-md-3 col-sm-3 control-label" for="FirstName">
                            {{::locale.messages['app.label.name']}}</label>

                        <div class="col-md-9 col-sm-9">
                            <input class="input-md form-control margin-bottom" id="firstName" ng-readonly="true"
                                   name="name" type="text" value="" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 col-sm-3 control-label" for="feedbackContent">
                            {{::locale.messages['app.label.feedback']}}</label>

                        <div class="col-md-9 col-sm-9">
                            <textarea class="form-control margin-bottom" for="feedbackContent"
                                      ng-model="feedback.content" id="feedbackContent" name="feedbackContent"
                                      required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-12 text-right">

                        <button class="btn btn-lblue " type="submit">{{::locale.messages["app.label.submit"]}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="kiosksignout" class="modal">
    <div class="modal-dialog kiosk-top">
        <div class="modal-content overflowHidden">
            <div class="modal-header">
                <h4 class="modal-title">{{::locale.messages["signout.kiosk.modal.header"]}}
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                </h4>
            </div>
            <div class="modal-body wordWrap">
                <p>{{::locale.messages["signout.kiosk.modal.message"]}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-lblue btn-onlight"
                        onclick="window.location.href = 'http://exitkiosk';">{{::locale.messages["app.label.ok"]}}
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container" ng-controller="loginController">

    <div class="modal modal-wide fade" id="loginRegister" role="dialog">


        <div class="modal-dialog">

            <!-- Modal content-->


            <div class="modal-content savesearch_register_modal overflowHidden">
                <div class="modal-header modalbgcolor">

                    <button type="button" class="close d-f j-c_c" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">{{locale.messages['login.forgot.modal.close']}}</span>

                    </button>

                    <h4 class="modal-title header_title loginmarginbottom" id="myModalLabel"
                        data-uname="loginPublicloginmodalheader">
                        {{locale.messages['login.forgot.modal.signInOrRegister']}}
                        <br>
                    </h4>
                    <p ng-if="locale.messages['login.forgot.modal.text']">
                        {{locale.messages['login.forgot.modal.text']}}
                    </p>

                </div>
                <div class="formbox">


                    <div class="modal-body">
                        <div class="form-group validation-error text-center top10 col-md-12 col-sm-12"
                             ng-repeat="alert in loginAlerts">
                            <span ng-bind-html="alert.msg | to_trusted" bind-html-compile>{{alert.msg}}</span>
                        </div>


                        <div class="logincenter">
                            <div class="no-margin accounttype_modal panel-heading nmt bg-lblue white d-f j-c_s-b">
                                <span class="">
                                    {{locale.messages['login.page.accountType']}}
                                </span>
                                <span class="">
                                    <select class="form-control" id="opt"
                                            data-uName="loginAccounttypedropdownbox"
                                            ng-model="form.accountType">
                                        <option label="{{locale.messages['app.label.member']}}" value="0"
                                                selected
                                                pref-code="signInOptions"
                                                access-value="member">{{locale.messages['app.label.member']}}
                                        </option>
                                        <option label="{{locale.messages['app.label.seller']}}" value="1"
                                                pref-code="signInOptions"
                                                access-value="seller">{{locale.messages['app.label.seller']}}
                                        </option>

                                    </select>
                                </span>
                            </div>

                            <form ng-submit="submitForm('/processLogin',form)">

                                <div id="show" class=" counterborder" ng-if="form.accountType.value !='1'">

                                    <div class=" col-md-12 col-sm-12 col-xs-12 form-group accounttype_modal">
                                        <div class="clear"></div>
                                        <div class="countermargin">
                                            <h5 style="color:red;display:none"><span>{{locale.messages['login.forgot.modal.errorMessage']}}</span>
                                            </h5>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">


                                            <label>{{locale.messages['login.page.emailUserID']}}</label>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input class="input-md form-control" type="text" value=""
                                                   autofocus="autofocus"
                                                   ng-model="form.username" data-uname="loginPublicloginmodalusername"
                                                   maxlength="{{::emailRule.maxLength}}">
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                    <div class="  col-md-12 col-sm-12 col-xs-12 form-group accounttype_modal">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                        	<span class="tool-tip-pop-over popwidth"><label>{{locale.messages['login.page.password']}}</label>
			                                      <a tooltip popoverid="password-popover"><i
                                                              class="fa fa-question-circle"></i></a>
			                                 </span>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input class="input-md form-control" type="password" value=""
                                                   ng-model="form.password" data-uname="loginPublicloginmodalpassword"
                                                   maxlength="{{passwordRulePref.maxLength ? passwordRulePref.maxLength : 12}}">
                                        </div>
                                    </div>
                                    <div class="form-group padding10 accounttype_modal">
                                        <div class="col-md-12 col-sm-12 col-xs-12 remember_div">
                                            <div class="col-md-5 col-sm-5 no-padding">
                                                <span class="pull-left rmb_txt">{{locale.messages['login.page.remember']}}?</span>
                                                <input class="remember pull-left remembercheck" type="checkbox"
                                                       ng-model="form.rememberme">
                                            </div>
                                            <div class="col-md-7 col-sm-7 no-padding">
                                                <span class="forgetid_txt">
                                                    <a class="loginfloatright forgotuserclass"
                                                       onclick="$('#loginRegister').modal('hide')"
                                                       href="/login?show=forgotuserid">{{locale.messages['login.page.forgot']}}</a>
                                            </span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="text-center margin10">
                                        <button class="btn btn-lblue margin10" data-uname="loginSigninmemberbutton"
                                                type="submit">{{locale.messages['login.button.signIntoAccount']}}
                                        </button>
                                    </div>
                                    <div class="signinstyle">
                                        <h5 class="newtext">
                                            {{locale.messages['login.forgot.modal.newToCopart']}}?</h5>

                                    </div>
                                    <div class="text-center margin10">
                                        <a class="btn btn-lblue" href="/doRegistration"
                                           onclick="$('#loginRegister').modal('hide')">{{locale.messages['app.label.register']}}</a>
                                    </div>
                                </div>
                            </form>


                            <!-- Seller Access -->
                            <div class="counterborder">
                                <div class="seller_access" ng-if="form.accountType.value =='1'">

                                    <div class="setuplogin">
                                        <p class="counteralign">{{locale.messages['login.page.setupWithSeller']}} ?</p>
                                        <button class="btn btn-primary seller_account" ng-click="goToSellerLogin()">
                                            {{locale.messages['login.button.signIntoAccount']}}
                                        </button>
                                    </div>
                                </div>

                            </div>

                            <!-- Seller Access Ends -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div id="password-popover" style="display: none">
                <div class="content-box">
                    <h3 class="nmt bg-lblue tablediv_header">
                        {{locale.messages['app.label.create.password.tooltip.header']}}</h3>
                    <p data-uname="passowrdPopText">
                    <ul>
                        <li>{{locale.getMessage('app.accountinformation.createPasswordMessage',minLength)}}</li>
                    </ul>
                    </p>
                </div>
            </div>
        </div>
    </div>


    <!-- Success Modal -->
    <div id="Successforgotuserid" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title greentxt">{{locale.messages['login.forgot.modal.success']}}</h4>
                </div>
                <div class="modal-body">
                    <p>{{locale.messages['login.forgot.modal.passwordMailed']}}</p>
                </div>
                <div class="modal-footer">


                </div>
            </div>
        </div>
    </div>

    <div>
        <div id="askSecurityQuestions" class="modal fade" tabindex="-1"
             role="dialog">
            <form name="askSecurityQuesForm" ng-submit="verifyQuestions(askSecurityQuesForm.$valid)" novalidate>
                <div class="modal-dialog modal-md">
                    <div class="modal-content overflowHidden">
                        <div class="modal-header error">
                            <h4 class="modal-title">{{locale.messages['app.label.securityQuestion']}}</h4>
                        </div>
                        <div class="modal-body wordWrap">
                            <div class="col-sm-12" ng-show="showSavingError">
                                {{locale.messages['security.question.saving.error']}}
                            </div>
                            <div class="col-sm-12 text-right reqField" data-uname="registrationRequiredfieldslabel">
                                {{locale.messages['required.field']}}
                            </div>
                            <!--question 1-->
                            <div class="form-group-all clr"
                                 ng-class="((isFormSubmitted && askSecurityQuesForm.question1.$error.required)
                                || (errorQuestionIds.indexOf(userQuestion[0].questionId.toString()) > -1 ))
                                ? 'validation-error-box'
                                : ''"
                            >
                                <label class="text-left" for="Type">{{locale.messages['security.question1.text']}}<sup
                                            class="reqField">*</sup></label>
                                <div class="clearfix">
                                    <label class="text-left" for="Type">{{locale.messages['security.question.' +
                                        userQuestion[0].questionId]}}<sup class="reqField">*</sup></label>
                                </div>
                                <input class="form-control" type="text" name="question1"
                                       ng-model="userQuestion[0].answer"
                                       required="required"
                                       maxlength="50"/>
                                <span ng-if="isFormSubmitted && askSecurityQuesForm.question1.$error.required"
                                      class="error">{{locale.messages['security.question.required']}}</span>
                                <span ng-show="errorQuestionIds.indexOf(userQuestion[0].questionId.toString()) > -1"
                                      class="error">{{locale.messages['security.question.answer.mismatch']}}</span>
                            </div>

                            <!--question 2-->
                            <div class="form-group-all clr"
                                 ng-class="((isFormSubmitted && askSecurityQuesForm.question2.$error.required)
                                    || (errorQuestionIds.indexOf(userQuestion[1].questionId.toString()) > -1) )
                                ? 'validation-error-box'
                                : ''"
                            >
                                <label class="text-left" for="Type">{{locale.messages['security.question2.text']}}<sup
                                            class="reqField">*</sup></label>
                                <div class="clearfix">
                                    <label class="text-left" for="Type">{{locale.messages['security.question.' +
                                        userQuestion[1].questionId]}}<sup class="reqField">*</sup></label>
                                </div>
                                <input class="form-control" type="text" name="question2"
                                       ng-model="userQuestion[1].answer"
                                       required="required"
                                       maxlength="50"/>
                                <span ng-if="isFormSubmitted && askSecurityQuesForm.question2.$error.required"
                                      class="error">{{locale.messages['security.question.required']}}</span>
                                <span ng-show="errorQuestionIds.indexOf(userQuestion[1].questionId.toString()) > -1"
                                      class="error">{{locale.messages['security.question.answer.mismatch']}}</span>
                            </div>

                            <!--question 3-->
                            <div class="form-group-all clr"
                                 ng-class="((isFormSubmitted && askSecurityQuesForm.question3.$error.required)
                                    || (errorQuestionIds.indexOf(userQuestion[2].questionId.toString()) > -1) )
                                ? 'validation-error-box'
                                : ''"
                            >
                                <label class="text-left" for="Type">{{locale.messages['security.question3.text']}}<sup
                                            class="reqField">*</sup></label>
                                <div class="clearfix">
                                    <label class="text-left" for="Type">{{locale.messages['security.question.' +
                                        userQuestion[2].questionId]}}<sup class="reqField">*</sup></label>
                                </div>
                                <input class="form-control" type="text" name="question3"
                                       ng-model="userQuestion[2].answer"
                                       required="required"
                                       maxlength="50"/>
                                <span ng-if="isFormSubmitted && askSecurityQuesForm.question3.$error.required"
                                      class="error">{{locale.messages['security.question.required']}}</span>
                                <span ng-show="errorQuestionIds.indexOf(userQuestion[2].questionId.toString()) > -1"
                                      class="error">{{locale.messages['security.question.answer.mismatch']}}</span>
                            </div>

                            <input type="submit" class="btn btn-lblue btn-block"
                                   value="{{locale.messages['app.label.submit']}}"/>

                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Security Questions end -->

        <!-- No Security Questions start -->

        <div id="NoSecurityQuestions" class="modal fade" tabindex="-1"
             role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title redtxt">{{locale.messages['no.security.questions.header']}}</h4>
                    </div>
                    <div class="modal-body">
                        <p>{{locale.messages['no.security.questions.description']}}</p>

                    </div>
                    <div class="modal-footer">


                    </div>
                </div>
            </div>
        </div>
        <!-- No security Questions end -->
        <!--  Security Questions blocked start -->
        <div id="SecurityQuestionsAccountLocked" class="modal fade" tabindex="-1"
             role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title redtxt">{{locale.messages['account.locked.header']}}</h4>
                    </div>
                    <div class="modal-body">
                        <p>{{locale.messages['account.locked.description']}}</p>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div id="passwordExpired" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title lightred">Password Expired !!</h4>
            </div>
            <div class="modal-body ">
                <p class="">Your Password is expired. Please contact <a href="mailto:member.services@copart.com">member.services@copart.com.</a>
                </p>

            </div>
            <div class="modal-footer">


            </div>
        </div>
    </div>
</div>
<div class="modal bid-modal-details" id="UnAuthorizedAccessModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content overflowHidden">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
                <h4 class="modal-title">Session Expired
                </h4>
            </div>
            <div class="modal-body">
                Your session has expired. Please click OK to continue.
            </div>
            <div class="modal-footer">
                <button class="btn btn-lblue" ng-click="closeUnauthorizedModalAndReload()">
                    {{locale.messages["app.label.ok"]}}
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="driveRedirectInfo" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content overflowHidden">
            <div class="modal-header" style="background-color:#231f20">
                <img lazy-attr="src='/images/drive/DRIVE_logo_light.svg'" lazy-load="" lazy-no-spinner="true" src=""
                     alt="Drive" class="img-responsive drive-img" width="129px" height="24px"/>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p><strong class="modal-title">{{::locale.messages['app.msg.lotsearch.redirection.message1']}}</strong>
                </p>
                <p>{{::locale.messages['app.msg.lotsearch.redirection.message2']}}</p>
                <p>{{::locale.messages['app.msg.lotsearch.redirection.message3']}}</p>
                <p class="checkbox col-xs-12">
                    <label for="checkboxes-0" class="normaltxt">
                        <input type="checkbox"
                               id="driveRedirectChk">{{::locale.messages['app.msg.lotsearch.redirection.checkboxMessage']}}
                    </label>
                    <a target="_blank" id="driveRedirectHref"
                       class="btn btn-lblue btn-block"
                       role="button"
                       style="border: none;color: #FFC20E;background-color: #5D5D5C;">
                        {{::locale.messages['app.msg.lotsearch.redirection.buttonMessage']}}
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="international-shipping" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content overflowHidden">
            <div class="modal-header">
                <h4 class="modal-title">Success</h4>
            </div>
            <div class="modal-body">
                <div>Your form has been successfully submitted. Our export team is in process of calculating your
                    shipping quote. Please allow up to 36 hours.
                </div>
                <div class="text-center margin10">
                    <a class="btn btn-lblue" data-dismiss="modal">Ok</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="registrationAlertModal" class="vertical-horizontal-center modal fade"
     tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content overflowHidden">
            <div class="modal-header">
                <h4 class="modal-title">
                    {{locale.messages['app.label.messageSettings.modalPopup.notification']}}
                </h4>
            </div>
            <div class="modal-body wordWrap" id="routeChangeAlertMsg">
                <p>{{locale.messages['registration.navigation.alert.message']}}</p>
            </div>
            <div class="modal-footer">
                <a type="button"
                   class="btn btn-white"
                   data-dismiss="modal">
                    {{locale.messages['app.label.cancel']}}
                </a>
                <a type="button"
                   id="leaveRegistrationButton"
                   class="btn btn-copart-blue">
                    {{locale.messages['app.label.yes']}}
                </a>
            </div>
        </div>
    </div>
</div>


<script src="/wro/startup_libraries_chunk_1-b4896bce2ca8f2a8814d1aed02e049ca.js" defer="defer"></script>
<script src="/wro/startup_libraries_chunk_2-9606a698f354b8fbef41e00026a5f363.js" defer="defer"></script>
<script src="/wro/angular-cfc5822d2955a8af33e20b4342734b8b.js" defer="defer"></script>
<script src="/wro/startup_libraries_chunk_3-05d24da202c9b2606b7145eae52a79fe.js" defer="defer"></script>
<script src="/wro/startup_bundle-a622bebed70d5ab0f71018f07e6153e5.js" defer="defer"></script>


<script src="/public/data/referenceData/less-e9bc0b84f07fc3c837ee7afb34473bb9.js" defer="defer"></script>


<script src="/wro/home_bundle-6ffacf2a286ed19abdd9f00d81df7949.js" defer="defer"></script>


<script src="/wro/searchResults_bundle-0f763c0057c14f411026c82683c2fa6e.js" defer="defer"></script>


<script src="/wro/vehicleFinder_bundle-92d436dab6cd7b2ab46201bdfd6e5b73.js" defer="defer"></script>


<script src="/wro/locations_bundle-78e4869fa06ebbdf2c3ff04bfa675d88.js" defer="defer"></script>


<script src="/wro/registration_bundle-7283f2693a7595c825e47e8151a48124.js" defer="defer"></script>


<script src="/wro/lot_details_bundle-b6fcfcb94ddab131f10929eec8347885.js" defer="defer"></script>


<script src="/wro/app_bundle-117845dc77f0a4c543d814ee356929c4.js" defer="defer"></script>


<script src="/wro/messages_CPRTUS_en-748cf51224e0952c19bfc33ba37ab742.js" defer="defer"></script>
<script src="/wro/CLDR_en-19bcf323c56afdaf3c9782d75dbdc0ad.js" defer="defer"></script>

<link rel="preconnect" href="https://www.facebook.com"/>
<link rel="preconnect" href="https://connect.facebook.net"/>
<link rel="preconnect" href="https://www.googletagmanager.com"/>
<link rel="preconnect" href="https://www.google-analytics.com"/>
<link rel="dns-prefetch" href="https://www.googleadservices.com"/>


<body>

<div class="inner-wrap d-f-c f-1">
    <!--<notifications></notifications>-->
    <div id="global-alerts" class="global-alerts">
        <fragment id="GlobalAlerts">
            <style type="text/css">#global-banner-corona_update {
                    display: none;
                }

                #global-notification-carousel {
                    background: #D55900;
                    border-bottom: none;
                }

                #global-notification-carousel .item a {
                    color: #fff;
                    font-weight: 600;
                    text-decoration: underline;
                    margin-left: 5px;
                }

                #global-notification-carousel .carousel-inner {
                    border: none;
                    border-radius: 0;
                }

                #global-notification-carousel .item {
                    color: #fff;
                    padding: 5px 0;
                    margin: 0;
                    font-size: 15px;
                }

                #global-notification-carousel .item p {
                    color: #fff;
                    padding: 0;
                    margin: 0;
                    font-size: 15px;
                }

                #global-notification-carousel .close {
                    right: 10px;
                    position: absolute;
                    top: 5px;
                    cursor: pointer;
                    z-index: 99;
                    color: #3a4351;
                    opacity: 1;
                }

                #global-banner-OUTDATED_BROWSER {
                    text-align: center;
                    font-size: 15px;
                    margin-bottom: 0px;
                    font-weight: 600;
                    padding: 5px;
                    background: #fdeeee;
                    color: #ab2b2b !important;
                }

                .browser-compatibiltiy-text {
                    border: none;
                    margin: 0;
                    padding: 0;
                }

                #global-notification-carousel #global-banner-OUTDATED_BROWSER a {
                    color: #0D5DB8;
                }
            </style>
            <!---->

            <div class="carousel slide text-center ng-hide" id="global-notification-carousel"
                 ng-hide="!enableGlobalAlerts" style="margin: 0;"><!-- Wrapper for banners -->
                <div class="carousel-inner alert p-0 m-0" id="global-carousel-banner" role="listbox">
                    <!---->

                    <!---->

                    <!---->
                    <style type="text/css">.show-on-dashboard {
                            display: none !important;
                        }
                    </style>
                    <!---->
                    <a aria-label="Close" class="close" ng-click="closeCarousel()">×</a></div>
            </div>
            <script type="text/javascript">
                dataLayer.push({'event': 'optimize.activate.regbar.en'});
            </script>
        </fragment>
    </div>


    <div class="modal fade vertical-horizontal-center" id="dynamic-modal" role="dialog" tabindex="-1"
         aria-labelledby="autoPayConfirmation">
        <div class="modal-dialog" role="document">
            <div class="modal-content overflowHidden">
                <div class="modal-header">
                    <h4 class="mar0"></h4>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-white right" ng-click="closeDynamicModal()" role="button" type="button">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="d-f-c f-1">

        <!--  main content goes here -->
        <div class="main-content d-f-c f-1">

            <div>

            </div>


            <!---->
            <div ng-view="" autoscroll="">
                <div style="display:none;" id="page_meta_title">Buying from Pakistan at Copart USA - Car Auction</div>
                <div style="display:none;" id="page_meta_keywords"></div>
                <div style="display:none;" id="page_meta_description">Learn how buyers from Pakistan can register at
                    Copart auto auction in USA and buy used cars from USA with shipping to Pakistan.
                </div>
                <div style="display:none;" id="page_title">New International - Pakistan</div>
                <div style="display:none;" id="page_robots">index, follow</div>
                <div style="display:none;" id="page_canonical">https://www.copart.com/pakistan</div>

                <div style="display:none;" id="meta_title">Buying from Pakistan at Copart USA - Car Auction</div>
                <div style="display:none;" id="meta_description">Learn how buyers from Pakistan can register at Copart
                    auto auction in USA and buy used cars from USA with shipping to Pakistan.
                </div>
                <div style="display:none;" id="meta_canonical_url">https://www.copart.com/pakistan</div>
                <div style="display:none;" id="meta_robots">index, follow</div>

                <div id="main" role="main">
                    <div id="content" class="content container-fluid cms-mrkt pt-0">
                        <div class="clear"></div>
                        <!-- BeginBody -->
                        <!-- end module contact info nav -->
                        <!-- SOC: block-content type: basic page template g2member_system_main -->

                        <div id="node-10473" class=" clearfix" role="article"
                             about="/en/content/us/en/landing-page/pakistan" typeof="schema:WebPage">

                            <span property="schema:name" content="New International - Pakistan" class="hidden"></span>


                            <div class="content clearfix">

                                <div property="schema:text">
                                    <link href="https://www.copart.com/content/gridonly.css" rel="stylesheet">
                                    <link href="https://www.copart.com/content/intlcountrytemplate_2language_no_reps.css"
                                          rel="stylesheet">
                                    <style type="text/css">@media only screen and (max-width: 1079px) {

                                            .mobile-remove2 {
                                                display: block;
                                            }
                                        }

                                        @media only screen and (max-width: 676px) {
                                            .Choosecountry {
                                                display: block;
                                                margin: 0 auto;
                                                width: 187px;
                                            }
                                        }
                                    </style>


                                    <div class="page-hero copart-blue"
                                         style="margin-bottom: 0; background-image:url(/content/us/en/landing-page/intl_banner.png);background-position: center;">
                                        <div class="grid-container">
                                            <div class="grid-row">
                                                <div class="col-12 text-center">
                                                    <h1 class="white-text text-center">Buy Used Cars at Copart USA Car
                                                        Auction from Pakistan</h1>

                                                    <ul class="list-style-blue">
                                                        <li class="hero-bullets"><span>Over 125,000 Vehicles in Inventory</span>
                                                        </li>
                                                        <li class="hero-bullets"><span>Buyers from 150+ Countries</span>
                                                        </li>
                                                        <li class="hero-bullets"><span>Vehicles for Businesses &amp; Individuals</span>
                                                        </li>
                                                    </ul>
                                                    <br>
                                                    <a class="copart-btn-closed"
                                                       href="https://www.copart.com/doRegistration/?intcmp=web_lp_new-international_pakistan_en_en_hero_register_button"
                                                       target="_blank">Register Now</a>

                                                    <p>&nbsp;</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid-container">
                                        <div class="grid-row">
                                            <div class="col-1 mobile-remove">&nbsp;</div>

                                            <div class="col-10">
                                                <p class="intro-text">Are you an international buyer looking to buy your
                                                    next car, truck, SUV or motorcycle in the USA? Copart is the world’s
                                                    leading online auto auction with thousands of vehicles for sale
                                                    available to buyers from all over the world. Whether you are an
                                                    individual buying a car for personal use or a business buying for
                                                    resale, we have what you are looking for.</p>

                                                <p class="intro-text2">Register NOW to start buying from Copart USA!</p>

                                                <hr>
                                            </div>

                                            <div class="col-1 mobile-remove">&nbsp;</div>
                                        </div>

                                        <div class="grid-row text-center">
                                            <div class="col-2 mobile-remove">&nbsp;</div>

                                            <div class="col-8">
                                                <h3 class="reg-h3">Registration Steps For Buyers From Pakistan</h3>

                                                <ol class="reg-steps">
                                                    <li>Sign up for a free Guest membership to start searching
                                                        vehicles
                                                    </li>
                                                    <li>Upgrade your membership and upload a copy of your
                                                        government-issued ID to start bidding<br>
                                                        <span style="font-size:14px;font-weight:600;">If you are registering as a business, <a
                                                                    data-target="#business-buying-steps"
                                                                    data-toggle="modal"
                                                                    href="#">see details here</a></span>
                                                        <div aria-hidden="true" aria-labelledby="business-buying-steps"
                                                             class="modal fade" id="business-buying-steps" role="dialog"
                                                             style="display: none;" tabindex="-1">
                                                            <div class="modal-dialog"><!-- Modal content-->
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                        <button class="close" data-dismiss="modal"
                                                                                type="button"><span
                                                                                    aria-hidden="true">×</span> <span
                                                                                    class="sr-only">Close</span>
                                                                        </button>
                                                                        <p>International buyers who wish to bid on
                                                                            vehicles that require a business license
                                                                            must be either registered as a business/
                                                                            sole proprietorship in their country or
                                                                            contact brokers to get access to business
                                                                            inventory.</p>

                                                                        <p>If your business is registered outside of the
                                                                            USA, please upload the following documents
                                                                            to start bidding on Copart as a
                                                                            business:</p>

                                                                        <ul>
                                                                            <li style="margin-bottom: 10px; line-height: 1.7em; font-size: 13px;">
                                                                                Business license or self-employed
                                                                                certificate
                                                                            </li>
                                                                            <li style="margin-bottom: 10px; line-height: 1.7em; font-size: 13px;">
                                                                                Taxpayer ID/VAT Certificate obtained
                                                                                from tax authorities (if not eligible
                                                                                for VAT, provide a letter from issuing
                                                                                VAT authority confirming that you do not
                                                                                qualify for VAT at this time)
                                                                            </li>
                                                                            <li style="margin-bottom: 10px; line-height: 1.7em; font-size: 13px;">
                                                                                Notarized English translation of the
                                                                                licenses and certificates mentioned
                                                                                above (if not issued in English)
                                                                            </li>
                                                                            <li style="margin-bottom: 10px; line-height: 1.7em; font-size: 13px;">
                                                                                <a href="https://www.copart.com/content/us/en/_forms/affidavit-for-purchase-of-motor-vehicles-0318.pdf"
                                                                                   target="_blank">Affidavit Form</a>
                                                                                filled out in English. <a
                                                                                        href="https://www.copart.com/content/us/en/landing-page/affidavit%20for%20purchase_example_en.pdf"
                                                                                        target="_blank">Download PDF
                                                                                    inctructions &gt;&gt;</a></li>
                                                                            <li style="margin-bottom: 10px; line-height: 1.7em; font-size: 13px;">
                                                                                <a href="https://www.copart.com/content/us/en/_forms/multi-sate-uniform-sales-use-tax-certificate-0318.pdf"
                                                                                   target="_blank">Multi-state Tax
                                                                                    Form</a> filled out in English. <a
                                                                                        href="https://www.copart.com/content/us/en/landing-page/multi-state%20uniform%20sales%20%26%20use%20tax%20certificate_example_en.pdf"
                                                                                        target="_blank">Download PDF
                                                                                    inctructions &gt;&gt;</a></li>
                                                                        </ul>

                                                                        <p><i>*To register as a business buyer, you may
                                                                                first upload the <strong>business
                                                                                    owner’s photo ID</strong> to
                                                                                activate their account and get access to
                                                                                inventory available to individuals. You
                                                                                then can go back and add the documents
                                                                                mentioned above to begin bidding as a
                                                                                business. Please note that the company
                                                                                name in the registration must match the
                                                                                company name on the licensing documents
                                                                                that you plan to submit.</i></p>

                                                                        <p><i><strong>Please note that you need to
                                                                                    upload to your account ALL the
                                                                                    documents mentioned above to get
                                                                                    access to the inventory available to
                                                                                    businesses.</strong></i></p>

                                                                        <p>All documents will be processed within 1-3
                                                                            business days (excluding Saturdays, Sundays
                                                                            and public holidays). You will receive an
                                                                            email confirmation once your documents are
                                                                            processed and your account is activated for
                                                                            bidding.</p>

                                                                        <div style="width: 171px; margin: 0 auto;"><a
                                                                                    class="copart-btn-closed"
                                                                                    href="https://www.copart.com/accountInformation/accountSetting?showAcc=Y "
                                                                                    style="background-color: #4482E3;display: inline-block;margin:7px auto; text-align: center; font-size: 13px;"
                                                                                    target="_blank">UPLOAD DOCUMENTS</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>See next steps in our buying guide for international members<br>
                                                        <span style="font-size:14px;font-weight:600;"><a
                                                                    href="https://www.copart.com/content/us/en/landing-page/international/prepare-to-bid/individual?intcmp=web_lp_new-international_pakistan_en_en_buying_guide_individual"
                                                                    target="_blank">Buying guide for individuals &gt;&gt;</a> &nbsp; &nbsp; <a
                                                                    href="https://www.copart.com/content/us/en/landing-page/international/prepare-to-bid/business?intcmp=web_lp_new-international_pakistan_en_en_buying_guide_business"
                                                                    target="_blank"> Buying guide for businesses &gt;&gt;</a></span>
                                                    </li>
                                                    <li>Start bidding as soon as your ID is reviewed (1-3 business
                                                        days)
                                                    </li>
                                                </ol>
                                            </div>

                                            <div class="col-2 mobile-remove">&nbsp;</div>

                                            <div class="col-12"><a class="copart-btn-closed-large"
                                                                   href="https://www.copart.com/doRegistration/?intcmp=web_lp_new-international_pakistan_en_en_body_register_button"
                                                                   target="_blank">Register Today</a></div>
                                        </div>

                                        <p class="question-p" style="font-size: 15px; margin-bottom: 40px;"><strong>Questions? </strong>
                                            Email us at <a
                                                    href="mailto:international_marketing@copart.com?subject=Questions to Copart from Pakistan"
                                                    target="_blank">international_marketing@copart.com</a></p>

                                        <hr>
                                    </div>
                                    <!--<div class="full-width-section" style="background-color:#E3ECFB;margin-bottom:0;padding:40px 0;">
                                        <div class="grid-container">
                                            <div class="grid-row">
                                                <h3 class="authrep-h3"><a id="scroll-rep">Copart Authorized Representative in Pakistan</a></h3>

                                                <p style="font-size: 14px">If you need help buying from Copart, our authorized representative&nbsp;will help you with the buying process and/or international shipping.</p>
                                            </div>

                                            <div class="grid-row">
                                                <div class="col-6">
                                                    <div class="authrep-container">
                                                        <h4 class="authrep-h4"><a href="http://www.oldmotor.ru/" target="_blank">Old Motor, LLC</a></h4>

                                                        <p style="font-size: 14px"><strong>Tel.:</strong><a href="tel:+74956607750"> +7 495 660-7750</a><br />
                                                            <strong>Email:</strong><a href="mailto:opt@oldmotor.ru"> opt@oldmotor.ru</a><br />
                                                            <strong>Address:</strong> Burakova Str., 18, Moscow, Russia</p>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="authrep-container">
                                                        <h4 class="authrep-h4"><a href="http://www.w8shipping.com/" target="_blank">W8 SHIPPING</a></h4>

                                                        <p style="font-size: 14px"><strong>General manager:</strong> Leanid Kastsiukevich<br />
                                                            <strong>Tel.:</strong><a href="tel:+995322009008"> +995 322 009 008</a><br />
                                                            <strong>Email:</strong><a href="mailto:leonid@w8shipping.com"> leonid@w8shipping.com</a><br />
                                                            <strong>Address:</strong> Akaki Beliashvili 138, Tbilisi 0159, Georgia</p>
                                                    </div>
                                                </div><div class="col-4">
                                                    <div class="authrep-container">
                                                        <h4 class="authrep-h4">CAUCASUS AUTO IMPORT</h4>

                                                        <p><strong>Tel.:</strong> +99 532 255 1155<br />
                                                            <strong>Email:</strong> info@cai.ge<br />
                                                            <strong>Address:</strong> 156 Agmashenebeli ave., Tbilisi 0112, Georgia</p>
                                                    </div>
                                                </div></div>
                                        </div>
                                    </div>-->

                                    <div class="grid-container">
                                        <div class="grid-row" style="padding: 30px 0;">
                                            <div class="col-4">
                                                <h4>Shipping to Pakistan</h4>

                                                <p style="font-size: 14px">You may arrange international shipping to
                                                    Pakistan through a third-party shipping company of your choice or
                                                    through our authorized representative. <a
                                                            href="https://www.copart.com/content/us/en/landing-page/international/prepare-to-bid/individual #scroll-shipping"
                                                            target="_blank">View shipping instructions &gt;&gt;</a></p>
                                            </div>

                                            <div class="col-4">
                                                <h4>Payments</h4>

                                                <p style="font-size: 14px">All payments are due with (2) business days
                                                    after sale. International buyers are highly recommended to add funds
                                                    to their account ahead of time. <a
                                                            href="https://www.copart.com/content/us/en/landing-page/international/prepare-to-bid/individual #scroll-payment"
                                                            target="_blank">View payment instructions &gt;&gt;</a></p>
                                            </div>

                                            <div class="col-4">
                                                <h4>Import Details</h4>

                                                <p style="font-size: 14px">Visit <a href="http://www.pakcustoms.org/"
                                                                                    target="_blank">Pakistan Customs
                                                        website</a> to learn more about the laws and regulation of
                                                    importing cars into Pakistan.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid-container">
                                        <div class="grid-row"
                                             style="border: solid 2px #d6d6d6; border-color: #4482E3; border-radius: 15px;">
                                            <div class="grid-row">
                                                <h4 class="reg-h4"
                                                    style="background-color:#E3ECFB; text-align: left; padding:20px 40px; border-radius: 15px 15px 0 0;">
                                                    <strong>FASTSEARCH</strong> - Over 125,000 Vehicles in inventory
                                                </h4>

                                                <p style="padding: 10px 0px 0px 40px; font-size: 14px;"><strong>Check
                                                        out the most popular vehicle brands in Pakistan.</strong></p>
                                            </div>

                                            <div class="grid-row" style="margin-top: 0px;">
                                                <div class="col-3">
                                                    <div class="Vehicle-container"><a
                                                                href="https://www.copart.com/popular/make/mini?query=mini&amp;free&amp;intcmp=web_lp_new-international_pakistan_en_en_mini_banner"
                                                                target="_blank"><img alt="Copart Mini Inventory"
                                                                                     data-entity-type=""
                                                                                     data-entity-uuid=""
                                                                                     src="/content/us/en/landing-page/images/mini_inventory.jpg"
                                                                                     style="width: 100%"></a></div>
                                                </div>

                                                <div class="col-3">
                                                    <div class="Vehicle-container"><a
                                                                href="https://www.copart.com/popular/make/bmw?query=bmw&amp;free&amp;intcmp=web_lp_new-international_pakistan_en_en_bmw_banner"
                                                                target="_blank"><img alt="Copart BMW Inventory"
                                                                                     data-entity-type=""
                                                                                     data-entity-uuid=""
                                                                                     src="/content/us/en/landing-page/images/bmw_inventory.jpg"
                                                                                     style="width: 100%"></a></div>
                                                </div>

                                                <div class="col-3">
                                                    <div class="Vehicle-container"><a
                                                                href="https://www.copart.com/popular/make/volkswagen?query=volkswagen&amp;free&amp;intcmp=web_lp_new-international_pakistan_en_en_volkswagen_banner"
                                                                target="_blank"><img alt="Copart Volkswagen Inventory"
                                                                                     data-entity-type=""
                                                                                     data-entity-uuid=""
                                                                                     src="/content/us/en/landing-page/images/volkswagen_inventory.jpg"
                                                                                     style="width: 100%"></a></div>
                                                </div>

                                                <div class="col-3">
                                                    <div class="Vehicle-container"><a
                                                                href="https://www.copart.com/popular/make/yamaha?query=yamaha&amp;free&amp;intcmp=web_lp_new-international_pakistan_en_en_yamaha_banner"
                                                                target="_blank"><img alt="Copart Yamaha Inventory"
                                                                                     data-entity-type=""
                                                                                     data-entity-uuid=""
                                                                                     src="/content/us/en/landing-page/images/yamaha_inventory.jpg"
                                                                                     style="width: 100%"></a></div>
                                                </div>

                                                <div class="col-12 text-center" style="margin-top: 0px;"><a
                                                            class="copart-btn-closed"
                                                            href="https://www.copart.com/vehicleFinder/?intcmp=web_lp_new-international_pakistan_en_en_search_all_vehicles_button"
                                                            style="background-color: #4482E3;display: inline-block;margin:0 auto;min-width: 300px;text-align: center; font-size:20px;"
                                                            target="_blank">Search all Vehicles</a></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid-container">
                                        <div class="grid-row">
                                            <h2 class="cta-h">Download Copart Mobile App to search &amp; bid on the
                                                go!</h2>

                                            <p class="cta-text">Access over 125,000 cars in your pocket now! Search,
                                                bid, and buy with the tap of a finger.</p>

                                            <div class="badges cta-badges"><a
                                                        href="https://itunes.apple.com/us/app/copart-mobile/id414275302?mt=8"
                                                        target="_blank"><img alt="" data-entity-type=""
                                                                             data-entity-uuid=""
                                                                             src="/content/us/en/images/landing-pages/mobile/badges%20-%20apple%20badge%20-%20dark.png">
                                                </a>
                                                <a href="https://play.google.com/store/apps/details?id=com.copart.membermobile"
                                                   target="_blank"> <img alt="" data-entity-type="" data-entity-uuid=""
                                                                         src="/content/us/en/images/landing-pages/mobile/badges%20-%20google%20play%20badge%20-%20dark.png">
                                                </a></div>
                                        </div>
                                    </div>

                                    <div class="section-wrapper feedback">
                                        <div class="content">
                                            <p class="question-p"><strong>Questions? </strong> Email us at <a
                                                        href="mailto:international_marketing@copart.com?subject=Questions to Copart from Pakistan"
                                                        target="_blank">international_marketing@copart.com</a></p>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="link-wrapper">

                            </div>

                        </div>

                        <!-- EOC: block-content type: basic page template g2member_system_main -->


                        <a id="welcomeModal" href="" client-action="WelcomeModal" data-modal-name="modal-welcome"
                           data-modal-url="/US/en/Layout/WelcomeMsg" class="ajax modal-error" style="display:none;"></a>
                        <!-- EndBody -->
                    </div>
                </div>
                <div class="clear">
                </div>

            </div>
        </div>
        <!-- End main content goes here -->

        <!--  footer container  -->
        <div class="footer-section">
            <style name="commonFooterCSS">footer {
                    display: block
                }

                .footer {
                    background-color: #1d1e20;
                    color: #fff;
                    width: 100%;
                    padding-top: 40px !important
                }

                @media (max-width: 768px) {
                    .footer {
                        padding-top: 20px !important
                    }
                }

                .footer-column-0 .logo.copart-img {
                    padding-left: 20px
                }

                @media (max-width: 768px) {
                    .footer-column-0 .logo {
                        padding-left: 0
                    }
                }

                .footer a:link, .footer a:visited {
                    color: #fff !important
                }

                .footer a:hover, .footer a:visited {
                    text-decoration: underline
                }

                .footer .list-unstyled a {
                    font-weight: 400
                }

                .footer .footer-mid {
                    font-size: 12px
                }

                .footer .footer-links {
                    font-size: 13px
                }

                .footer .footer-links .list-unstyled {
                    line-height: 2em
                }

                .footer .brandlogo {
                    background-image: url(/images/footer/footer_logos.svg);
                    background-repeat: no-repeat;
                    display: block;
                    margin-bottom: 30px
                }

                .sprite-brand-ct {
                    background-position: -1px 0;
                    width: 118px;
                    height: 15px
                }

                .sprite-brand-NPA {
                    background-image: url(/images/footer/footer_logos-NPA.svg) !important;
                    background-position: -1px -142px !important;
                    width: 115px;
                    height: 41px;
                    background-size: 170px;
                    display: block
                }

                .brand-cashforcars {
                    background-image: url(/images/footer/cashforcars-logo-white.svg) !important;
                    width: 80%
                }

                .footer-mid {
                    padding: 10px 0 20px
                }

                .crtoys-noshow {
                    display: block
                }

                .footer-container .footer-title {
                    margin: 10px 0;
                    font: 16px/1.1 'Red Hat Display';
                    color: #ffb838
                }

                @media (min-width: 768px) and (max-width: 1023px) {
                    .footer-links li a {
                        font-size: 12px
                    }
                }

                @media (max-width: 768px) {
                    .footer .footer-links {
                        font-size: 12px;
                        padding-left: 0;
                        padding-right: 0
                    }
                }

                @media screen and (max-width: 479px) {
                    footer .footer-links {
                        font-size: 12px;
                        padding-left: 0 !important;
                        padding-right: 0 !important
                    }
                }

                @media only screen and (max-width: 500px) {
                    .footer-links, .footer-links a {
                        font-size: 11px !important;
                        margin-bottom: 5px
                    }
                }

                .copart_countries .footer-country-items {
                    z-index: 999
                }

                .copart_countries .footer-country-items .copart_country_items a {
                    padding: 5px 5px 5px 5px !important;
                    color: #fff !important
                }

                .copart_countries .dropdown-menu .footer-country-items li a {
                    background-color: #363a3f;
                    font-family: 'Red Hat Display 500';
                    letter-spacing: normal
                }

                #footer-container .orange-font {
                    color: #f76120 !important
                }

                .lang_region_selection_block:after {
                    background: #f9f9f9;
                    font-size: 10px;
                    color: #151317;
                    right: 5px;
                    top: 12px;
                    padding: 0 0 2px;
                    position: absolute;
                    pointer-events: none;
                    width: 15px
                }

                #footer-container .container-fluid {
                    padding: 0 15px
                }

                @media (max-width: 768px) {
                    .bot-banner-block .floating-design-sprite {
                        transform: scale(.6);
                        bottom: -35px;
                        left: 0
                    }

                    #footer-container .container-fluid {
                        padding: 0
                    }

                    #footer-container .footer-title {
                        text-align: center
                    }

                    #footer-container .footer-flex-container {
                        flex-direction: column
                    }

                    #footer-container .footer-column {
                        width: 100%
                    }

                    #footer-container .footer-column .list-unstyled.collapse {
                        display: none
                    }

                    #footer-container .footer-links ul li {
                        text-align: center !important
                    }

                    #footer-container .footer-links a {
                        font-size: 14px !important;
                        line-height: 26px
                    }

                    #footer-container .toggle-btn:after {
                        content: "\f107";
                        position: relative;
                        top: 3px;
                        display: inline-block;
                        cursor: pointer;
                        font: normal 400 24px/1 FontAwesome;
                        -webkit-font-smoothing: antialiased;
                        -moz-osx-font-smoothing: grayscale;
                        margin-left: 5px
                    }

                    #footer-container .copart_countries .footer-country-items {
                        z-index: 999;
                        left: -125px !important;
                        bottom: 44px;
                        margin-bottom: 0
                    }

                    #footer-container .copart_countries .footer-country-items .copart_country_items {
                        text-align: left !important
                    }

                    #footer-container .mobile-region-selected {
                        background: #363a3f;
                        border: 1px solid #363a3f
                    }

                    #footer-container .mobile-footer-dropdown {
                        display: flex;
                        justify-content: space-between
                    }

                    #footer-container .mobile-footer-dropdown .langDropdown_select {
                        height: 40px;
                        font-size: 14px;
                        padding: 8px 10px
                    }

                    #footer-container .app-links {
                        display: flex;
                        align-items: center;
                        flex-basis: 53%;
                        justify-content: flex-end;
                        padding-right: 15px
                    }

                    #footer-container .download-mobile-app {
                        margin-top: 25px
                    }

                    #footer-container .mobile-apps {
                        display: flex
                    }

                    #footer-container .app-links-2 {
                        justify-content: flex-start
                    }

                    #footer-container .app-links a {
                        margin-top: -4px
                    }

                    #footer-container .site-links {
                        flex-direction: column-reverse
                    }

                    #footer-container .copart-ca-sites, #footer-container .copart-sites, #footer-container .crashedtoys-sites {
                        display: flex;
                        max-height: 20px;
                        line-height: normal;
                        margin: 15px 0
                    }

                    #footer-container .crashedtoys-sites {
                        justify-content: space-evenly
                    }

                    #footer-container .copart-ca-sites li, #footer-container .copart-sites li, #footer-container .crashedtoys-sites li {
                        display: flex;
                        flex-basis: 33%;
                        justify-content: center
                    }

                    #footer-container .copart-ca-sites {
                        display: inline-block;
                        width: 100%
                    }

                    #footer-container .copart-ca-sites li:nth-child(2) {
                        margin-left: 28px
                    }

                    #footer-container .copart-ca-sites li {
                        width: 29%;
                        display: inline-block
                    }

                    #footer-container .copart-ca-sites a, #footer-container .copart-sites a, #footer-container .crashedtoys-sites a {
                        margin-bottom: 0
                    }

                    #footer-container .site-links .footer-copyright {
                        width: 100%;
                        text-align: center;
                        display: block;
                        font-size: 10px
                    }

                    #footer-container .site-links .footer-links {
                        flex-wrap: wrap;
                        margin: 10px 0;
                        font-size: 10px !important
                    }

                    #footer-container .site-links .footer-links a {
                        line-height: 26px;
                        font-size: 10px !important;
                        padding: 0 5px
                    }
                }

                .footer-container {
                    width: 100%
                }

                .footer .footer-column {
                    margin: 0
                }

                label {
                    display: inline-block;
                    max-width: 100%;
                    margin-bottom: 5px;
                    font-weight: 400
                }

                .relative {
                    position: relative
                }

                h4 {
                    font: 600 16px/22px "Red Hat Display", sans-serif !important;
                    margin-bottom: 10px
                }

                @media (min-width: 768px) and (max-width: 1023px) {
                    .footer-links li a {
                        font-size: 12px
                    }
                }

                @media (max-width: 768px) {
                    .footer .footer-links {
                        font-size: 12px;
                        padding-left: 0;
                        padding-right: 0
                    }
                }

                @media screen and (max-width: 479px) {
                    footer .footer-links {
                        font-size: 12px;
                        padding-left: 0 !important;
                        padding-right: 0 !important
                    }
                }

                @media screen and (max-width: 768px) {
                    .footer-title {
                        font-size: 16px
                    }
                }

                @media screen and (max-width: 768px) {
                    footer.footer {
                        padding: 15px 15px 0 20px
                    }
                }

                .footer-flex-container {
                    display: flex;
                    flex-basis: 100%;
                    flex-direction: row;
                    justify-content: space-between;
                    align-items: flex-start
                }

                .footer-column {
                    display: flex;
                    flex-direction: column;
                    flex-basis: 20%;
                    font-size: 14px
                }

                .footer-column .list-unstyled.collapse {
                    display: block
                }

                .footer-column-0 {
                    display: flex;
                    flex-direction: column;
                    flex-basis: auto;
                    width: 40%
                }

                .footer-column-1 {
                    display: flex;
                    flex-direction: column
                }

                .footer-copyright {
                    display: flex;
                    flex-basis: 20%
                }

                .footer-links {
                    display: flex;
                    justify-content: space-around;
                    flex-basis: 60%;
                    margin-bottom: 0
                }

                .brandlogo {
                    opacity: .35
                }

                .brandlogo:hover {
                    opacity: .75
                }

                .footer-dropdown-li {
                    width: 170px;
                    height: 40px;
                    margin: 20px 0
                }

                .crashedtoys-lang-dropdown {
                    margin: 0 auto
                }

                .footer-dropdown {
                    border-radius: 3px !important;
                    background-color: #363a3f !important;
                    color: #363a3f !important;
                    border: none !important
                }

                .footer .android-app-logo {
                    background-position: 0 -228px;
                    width: 101px;
                    height: 33px;
                    opacity: 1;
                    margin-bottom: 5px
                }

                .android-app-logo:hover {
                    opacity: 1
                }

                .footer .apple-app-logo {
                    background-position: 0 -187px;
                    width: 100px;
                    height: 40px;
                    opacity: 1;
                    margin-bottom: 5px
                }

                .apple-app-logo:hover {
                    opacity: 1
                }

                .desktop-only {
                    display: block
                }

                @media (max-width: 768px) {
                    .desktop-only {
                        display: none
                    }
                }

                .footer-container .mobile-only {
                    display: none
                }

                .mobile-only {
                    display: none
                }

                @media (max-width: 768px) {
                    .mobile-only {
                        display: block
                    }

                    .footer-container .mobile-only {
                        display: block
                    }
                }

                @media (max-width: 768px) {
                    #footer-container .footer-column .list-unstyled.collapse {
                        display: none
                    }

                    #footer-container .footer-column .list-unstyled.collapse.in {
                        display: block
                    }

                    #footer-container .copart-img {
                        margin-top: 20px
                    }

                    #footer-container .copart_countries {
                        width: 150px;
                        float: right
                    }

                    #footer-container .langDropdown_select {
                        width: 150px;
                        float: left
                    }
                }

                #footer-container .region_section .copart_country_list {
                    background-color: #363a3f;
                    top: -315px
                }

                #footer-container .langDropdown_select {
                    -webkit-appearance: none;
                    -moz-appearance: none;
                    appearance: none
                }

                #footer-container .langDropdown_select, #footer-container .region_section .copart_country_items {
                    border: 1px solid #363a3f !important;
                    background: #363a3f;
                    color: #fff
                }

                #footer-container .lang_region_selection_block:after, #footer-container .region_section .custom_meta_dropdown .region_selected_block:after {
                    content: url(/images/icons/union_white.svg);
                    background: #363a3f !important
                }

                #footer-container .region_section .copart_country_list a:hover {
                    background: #fff;
                    color: #363a3f !important;
                    font-weight: 700
                }

                #footer-container .footer-dropdown-li .toggle-language-dropdown {
                    width: 170px;
                    height: 42px;
                    text-align: left;
                    padding-left: 10px;
                    color: #fefefe !important;
                    font-size: 17px;
                    font-family: 'Red Hat Display 500';
                    font-weight: 400
                }

                #footer-container .footer-dropdown-li .lang_region_selection_block:after {
                    top: 10px;
                    padding: 0
                }

                #footer-container .footer-dropdown-li .language-dropdown-menu {
                    width: 172px;
                    left: -1px
                }

                #footer-container .footer-dropdown-li .language-dropdown-menu li a {
                    padding: 10px !important;
                    font-size: 14px;
                    font-family: 'Red Hat Display 500';
                    font-weight: 400;
                    background-color: #363a3f
                }

                #footer-container .footer-dropdown-li .language-dropdown-menu li a:hover {
                    background: #fff;
                    color: #363a3f !important;
                    font-weight: 700
                }

                #footer-container .mobile-only .footer-dropdown-li .language-dropdown-menu {
                    top: inherit;
                    bottom: 42px
                }

                #footer-container .mobile-only .footer-dropdown-li .language-dropdown-menu li a {
                    line-height: inherit;
                    margin: 0
                }

                #footer-container .mobile-only .footer-dropdown-li .lang_region_selection_block:after {
                    top: 13px
                }</style>
            <div compile-cms-content="" parent-id="/content/us/en/sitefooter-v3" fragment-id="common-footer"
                 cms-content-fragment="null"><!----><!----><!---->
                <div ng-if="compileFragment" compile-trusted-html="fragmentedCms">
                    <div class="footer-container" id="footer-container">
                        <footer class="footer">
                            <div class="container-fluid relative">
                                <div class="footer-mid">
                                    <div class="fluid-container">
                                        <div class="container-fluid">
                                            <div class="footer-flex-container footer-links">
                                                <div class="footer-column">
                                                    <div class="footer-column-0" style="width: 100%;">
                                                        <ul class="list-unstyled">
                                                            <li class="mt-10"><a href="./" ng-click="routeReload('/')"
                                                                                 data-uname="copartLogo"
                                                                                 class="logo copart-img"><img
                                                                            src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjkiIGhlaWdodD0iNDgiIGZpbGw9Im5vbmUiIHZpZXdCb3g9IjAgMCAxMjkgNDgiPgogICAgPG1hc2sgaWQ9Imo1YWp1a29vbmEiIHdpZHRoPSIxMDciIGhlaWdodD0iNDgiIHg9IjIyIiB5PSIwIiBtYXNrVW5pdHM9InVzZXJTcGFjZU9uVXNlIj4KICAgICAgICA8cGF0aCBmaWxsPSIjZmZmIiBkPSJNMjIuMjgzIDQ4SDEyOC40MlYwSDIyLjI4M3Y0OHoiLz4KICAgIDwvbWFzaz4KICAgIDxnIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBtYXNrPSJ1cmwoI2o1YWp1a29vbmEpIj4KICAgICAgICA8cGF0aCBmaWxsPSIjMTUxMzE3IiBkPSJNMTI3LjExMyAyMy45OTZjLTYuNDQ0IDEzLjI0Mi0zNC45NDYgMjQuMDIyLTYzLjQ0NiAyNC4wMjItMjguNTI4IDAtNDYuNTItMTAuNzgtNDAuMDc2LTI0LjAyMkMzMC4wMSAxMC44MDYgNTguNTM2IDAgODcuMDQgMGMyOC41MjcgMCA0Ni41MTUgMTAuODA2IDQwLjA3NCAyMy45OTZ6Ii8+CiAgICAgICAgPHBhdGggZmlsbD0iIzY0NzM4NyIgZD0iTTg1Ljk4MiAxLjIwOGMyNy4zNjggMCA0NC42MyAxMC4xMDggMzguNDQ4IDIyLjQ0Ny02LjE4MiAxMi4zODctMzMuNTI3IDIyLjQ3Mi02MC44NyAyMi40NzItMjcuMzY5IDAtNDQuNjMtMTAuMDg1LTM4LjQ0Ny0yMi40NzJDMzEuMjcgMTEuMzE1IDU4LjYzOCAxLjIwOCA4NS45ODIgMS4yMDh6Ii8+CiAgICA8L2c+CiAgICA8cGF0aCBmaWxsPSIjMTUxMzE3IiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik04MS42MiAzLjMxN2MyNC43MjUgMCA0MC4yODggOS4wNCAzNC42NjQgMjAuMDk1LTUuNjAxIDExLjA3OC0zMC4zNSAyMC4xMi01NS4wMjcgMjAuMTItMjQuNzI2IDAtNDAuMjktOS4wNDItMzQuNjg5LTIwLjEyIDMuNjM2LTcuMTI2IDE1LjE1LTEzLjM4MSAyOS40NzctMTYuOTY4IDEuMDE4LS4yNjggMi4wNi0uNDg1IDMuMTAzLS43MjggNy4xMjctMS41MjYgMTQuODExLTIuNCAyMi40NzEtMi40eiIgY2xpcC1ydWxlPSJldmVub2RkIi8+CiAgICA8cGF0aCBmaWxsPSIjMTI1NEZGIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMTMuOTczIDE0LjM1N2MxLjIyOSAyLjAxNCAxLjQ1NyA0LjI4LjQ4MyA2LjY1Ni0uOTExIDEuNzk5LTEuOTU3IDMuMjQ3LTMuNTE1IDQuNjcxLTguMDgzIDcuMzY2LTI2LjI3MiAxMi44NTktNDQuNDM2IDEyLjg1OS0yMS40MTcgMC0zNC45MjMtNy42MzMtMzAuMDQ0LTE2Ljk5QzM5LjgxMyAxNS4xMSA1MS4xNzUgOS4yNTIgNjQuNzggNi4zNmMxLjYtLjM0IDMuMjI3LS42MDcgNC44NzgtLjg1LTMuMjAxLjMxNi02LjMzMy43NzctOS40MTIgMS40MzMtMTUuMDg0IDMuMjgyLTI3Ljg3NSA4LjU4Mi0zMS41OTggMTUuOTIyQzIzLjI1IDMzLjUzNiAzOC4yMSA0Mi4yMzcgNjEuOTcgNDIuMjM3YzE5LjQ2OCAwIDM4Ljk2NC01LjgzMyA0OC4zMjktMTMuODMgMS4wNTgtLjg5OCAyLjI5Ni0yLjE2NiAzLjI2Ny0zLjUxOC40MDEtLjU0Ni43NTMtMS4xIDEuMDQ2LTEuNjU5IDEuNjc2LTMuMjA5IDEuMzU2LTYuMjQ3LS42NC04Ljg3M3oiIGNsaXAtcnVsZT0iZXZlbm9kZCIvPgogICAgPHBhdGggZmlsbD0iIzM2M0EzRiIgZmlsbC1ydWxlPSJldmVub2RkIiBkPSJNMTQuMzMgMjEuNzY4Yy0uNDM4LjY4OS0uODc4IDEuMTk3LTEuMzA3IDEuNTEybC0uMDE3LjAxM2MtLjQ5NS4zOS0xLjE0My41ODctMS45MjcuNTg3LS41MSAwLS44NTktLjEwMi0uOTU2LS4yNzQtLjAxLS4wMjctLjI0LS42Ni40NjktMi45NDMuNDY5LTEuNDQ3IDEuMDE1LTIuNDggMS42MjQtMy4wNzIuNzU2LS43NTcgMS41NjctMS4xMDkgMi41NTItMS4xMDkuMzEgMCAuNTY3LjA0OC43MjQuMTM0LjExNy4wNjcuMjEuMTE5LjI0Ni41NDVsLjE0NCAxLjY2N2g3Ljg1OGMtLjQ5My42NTQtLjkxNSAxLjM1OC0xLjI1NCAyLjA5NmgtNy42MTlsLS41MzcuODQ0em0xOS4wNS40ODVjLS4yOTIuOTMzLS42NyAxLjYxMS0xLjA5MiAxLjk1OS0uNDMuMzQ1LS43OTMuNDk5LTEuMTc0LjQ5OS0uMDggMC0uMTMtLjAwNy0uMTYtLjAxNC0uMDE2LS4xMDMtLjA0MS0uNDY5LjIyMS0xLjMwNS4yNS0uODUxLjYxOS0xLjQ5OCAxLjA2My0xLjg3LjQwOC0uMzI1Ljc4NS0uNDcgMS4yMjMtLjQ3LjA1MyAwIC4wOTEuMDAzLjExOC4wMDYuMDE2LjExNi4wMjYuNDYtLjE5OCAxLjE5NXptMTguNzY4LS4wMTljLS4yNDIuODI0LS41NjYgMS40MzktLjkxMyAxLjczLS4yODQuMjQtLjUyMy4zNTUtLjczMi4zNTVsLS4wNTgtLjAwMWMuMDA1LS4xNDQuMDM4LS40MzIuMTg3LS45MTQuMzI0LTEuMDU2LjY5LTEuNTEuOTQtMS43MDQuMjg3LS4yMzMuNTMzLS4zNTYuNzkyLS4zOTItLjAxNS4xNzUtLjA2Ni40NjUtLjIxLjkwN2wtLjAwNi4wMTl6bTU1LjIyMS04LjIwMWwyLjA4NC02LjY4LTEwLjcxMiA0LjQ0My0uMjc0LjExNC0uNjYxIDIuMTIzaC0yLjEyNGwtLjA4Ny4yNzhjLS44OTYtLjQtMS43OC0uNTk2LTIuNzAxLS41OTYtLjcyMyAwLTEuNDA5LjEwNy0yLjA0LjMxOGgtOC4zOWwtMS41ODcgNS4wNzFjLjAwMi0uMjA3LS4wMDQtLjQyLS4wMTgtLjYzNC0uMDc1LTEuMS0uNDYyLTIuMDc2LTEuMDg4LTIuNzQ0LS43OC0uODkyLTEuODgxLTEuNDY3LTMuMjcxLTEuNzEtMS4wMy0uMjA1LTIuNDI4LS4zLTQuMzk5LS4zLTEuMTUzIDAtMi4yNDEuMDc3LTMuMzI4LjIzOS0xLjIyOS4xOS0yLjEzLjQzOS0yLjgzNi43ODMtMS4wNDYuNDUzLTEuOTEzIDEuMDA1LTIuNTc2IDEuNjQtLjU4NC41MzItMS4xMTggMS4yMS0xLjYzMSAyLjA3LS4xMDUtLjUzOC0uMjY2LTEuMDU3LS40OC0xLjU0NC0uNjc5LTEuNDU0LTIuMjA5LTMuMTg5LTUuNjQtMy4xODktLjg0NyAwLTEuNjQ3LjEwNy0yLjQ0Mi4zMjZsLjAwMi0uMDA4aC05LjE2M2wtMS4zMTkgNC4yMTRjLS4xNTMtLjQzNi0uMzU0LS44Ni0uNi0xLjI2LTEuMzgtMi4xNzEtMy45MjktMy4yNzItNy41NzYtMy4yNzItMi45OTIgMC01LjY1NS44MDItNy45MTQgMi4zODMtLjQxNi4yOTMtLjgxNS42MDctMS4xODYuOTM0di0uMDI3YzAtMy4xMDgtLjg0NC01LjMwNy0yLjU4LTYuNzIyLTEuNTEyLTEuMjktMy43NDItMS45MTgtNi44MTctMS45MTgtMy42NjkgMC02Ljg5OC45ODgtOS41OTggMi45MzhDMy43MyAxMy4yNiAxLjgyIDE2LjA0Ny43MzkgMTkuNTg3Yy0uODc1IDIuNzY1LS45NzQgNS4xMzctLjI5MiA3LjA1NC43MyAyLjAwMSAxLjkzOSAzLjQyNyAzLjU5MSA0LjIzOCAxLjQ4NS43NDIgMy41MDkgMS4xMTggNi4wMTUgMS4xMTggMi4wODUgMCAzLjkwOC0uMjkyIDUuNDItLjg3IDEuNTI2LS41NTQgMi45Ny0xLjQyIDQuMjk1LTIuNTcyLjYwNS0uNTQ0IDEuMjctMS4yNjQgMS44NzctMi4wMzMuMzI0IDEuNTA1IDEuMTEzIDIuNTY4IDEuNzI0IDMuMiAxLjQ5MyAxLjQ5MyAzLjcxMiAyLjI1IDYuNTk4IDIuMjUgMy4wMzEgMCA1LjcyNi0uODA4IDguMDA4LTIuNDA1LjU2Ni0uMzk2IDEuMDk1LS44MzIgMS41NzUtMS4yOTZsLTIuNzIgOC42ODdoOS41ODZsMS43MzctNS41MTVjLjgzNi4zNTIgMS43OTkuNTMgMi44NjMuNTMgMi4yMzIgMCA0LjMyLS43NDcgNi4yMDctMi4yMi41MzktLjQyNCAxLjA0Ni0uOTExIDEuNTA4LTEuNDUuMTcuNjk1LjUxIDEuMzI3IDEuMDEgMS44ODEgMS4wNTMgMS4yMDQgMi43MDcgMS43ODkgNS4wNTUgMS43ODkgMS41NSAwIDMuMDAxLS4yMzIgNC4zMTYtLjY5LjIxNi0uMDc2LjQzNy0uMTYzLjY1OC0uMjZsLjIxLjYzMWgxNi41MzJsMS44NjctNS45NjdjLjc3Ny0yLjQ3IDEuNDg5LTMuNDU3IDEuODQzLTMuODI0LjM0MS0uMzA1LjU3LS4zNDcuNzY2LS4zNDcuMDIzIDAgLjIzNS4wMDcuNzUzLjIzNWwxLjI4OS41Ny42MTItLjczOCAxLjgwMy4wMDQtLjc5MiAyLjUyOWMtLjYxOCAxLjg4My0uODI3IDMuMjI2LS42NTYgNC4yMjYuMTc5IDEuMjIzLjgzOCAyLjIzNSAxLjg1NiAyLjg1Ljg3LjUzMyAyLjEyNy43OCAzLjk1Ny43OCAxLjMzNSAwIDIuODI0LS4xNTggNC40MjktLjQ3MmwuNzc5LS4xNTIuNDE1LS4wODIgMS42MDQtNy44NzEtMi44NzEuODQyLjgyMi0yLjY0OGgzLjE0OWwyLjM1OS03LjU1NmgtMy4xMjd6IiBjbGlwLXJ1bGU9ImV2ZW5vZGQiLz4KICAgIDxwYXRoIGZpbGw9IiMxNTEzMTciIGZpbGwtcnVsZT0iZXZlbm9kZCIgZD0iTTE1LjEzMyAyMS40MDhsLS4zOTQuNjJjLS40Ny43NC0uOTUxIDEuMjkyLTEuNDMzIDEuNjQ2LS41ODIuNDU3LTEuMzMyLjY5LTIuMjI3LjY5LS43MiAwLTEuMTg0LS4xNzctMS4zODEtLjUyNy0uMDY1LS4xMTMtLjM1LS44MDUuNDMtMy4zMTcuNDk1LTEuNTI3IDEuMDg0LTIuNjMgMS43NTEtMy4yNzguODM3LS44MzggMS43ODItMS4yNDUgMi44OS0xLjI0NS40MDEgMCAuNzIzLjA2Ni45NTcuMTk0LjI3Ni4xNTYuNDQ2LjM1OC40OTUuOTI4bC4xMDUgMS4yMjRoOC40NDVjLS44MzQuOTItMS40OTMgMS45NDUtMS45NyAzLjA2NWgtNy42Njh6bTE4LjcxMi45ODVjLS4zMjggMS4wNC0uNzQ4IDEuNzc5LTEuMjQ3IDIuMTktLjUyLjQxOC0uOTkyLjYxMi0xLjQ4NC42MTItLjQzOCAwLS41MjctLjE1My0uNTg3LS4yNTYtLjAyMy0uMDM4LS4yMTUtLjQxNC4xODYtMS42OTQuMjgtLjk1LjY4OC0xLjY1NSAxLjIxMy0yLjA5My40OTktLjQuOTg3LS41ODYgMS41MzUtLjU4Ni40MDMgMCAuNDc0LjEyLjU1LjI1LjAwOS4wMTQuMjA4LjM1Mi0uMTY2IDEuNTc3em0xNi4zMjQuODY3Yy4yODYtLjkzLjY2OC0xLjYwMyAxLjEwOC0xLjk0NS40MjEtLjM0My44Mi0uNTA0IDEuMjUzLS41MDQuMjIzIDAgLjIzNy4wMjcuMjg5LjEyLjA0NC4wNzYuMS41LS4yMDYgMS40MzgtLjI3Mi45MjktLjY0IDEuNjEtMS4wNjcgMS45NjYtLjM3NC4zMTUtLjcxNS40NjgtMS4wNDMuNDY4LS4zOTcgMC0uNDYzLS4xMS0uNTA3LS4xODMtLjAwMS0uMDAzLS4xNTItLjMxLjE3My0xLjM2em0xMC43ODQtLjIzOGMuMTk1LS42OTEuMzI3LTEuMzY1LjM5NC0yLjAxbDIuNTQzLjI1OWMtLjkyNS4zMTQtMS42NzcuNzAxLTIuMjg1IDEuMTc2LS4yMy4xNzktLjQ0OC4zNzEtLjY1Mi41NzV6bTQ2LjgyOC0xLjkxOGwyLjA1Ni02LjU4N2gtMy4xMjdsMS45NzItNi4zMi05LjgyIDQuMDczLS42OTkgMi4yNDdoLTIuMTI1bC0uMTUuNDc4Yy0xLS41MzUtMS45OC0uNzk1LTIuOTk0LS43OTUtLjcgMC0xLjM1OC4xMDYtMS45Ni4zMTdoLTguMTEzbC00LjgxIDE1LjM2Ny0uMDQ2LS4yNzFjLS4xMDctLjY1LS4xMTgtLjk3Mi0uMTA4LTEuMTI3LjAxMS0uMTYzLjA3LS41MS4yODUtMS4yMzZsMS45MjUtNi4xNmMuMjY5LS44MTEuMzctMS42NTguMzA4LTIuNTg5LS4wNjgtLjk5NC0uNDEtMS44NjUtLjk2Mi0yLjQ0OS0uNzA2LS44MS0xLjcxNS0xLjMzNC0yLjk5NC0xLjU1OC0xLjAwOC0uMi0yLjM4LS4yOTQtNC4zMTgtLjI5NC0xLjEyOSAwLTIuMTk0LjA3Ni0zLjI1Ny4yMzQtMS4xODQuMTgzLTIuMDQ0LjQyLTIuNzAxLjc0Mi0xIC40MzItMS44MjMuOTU0LTIuNDQ1IDEuNTUtLjY2My42MDUtMS4yNjUgMS40MTUtMS44MzkgMi40NzZsLS40NjQuODU4Yy4wMDEtMS4wNzYtLjE5Ni0yLjA3LS41OS0yLjk2My0uNjE3LTEuMzIyLTIuMDItMi44OTgtNS4xOTUtMi44OTgtMS4xMDcgMC0yLjE1My4xOTYtMy4xODQuNTk4bC4wODctLjI4aC04LjE1bC0xLjc5IDUuNzE2Yy0uMDQ1LTEuMDg4LS4zNDYtMi4wOTEtLjg5OS0yLjk5NS0xLjI4Mi0yLjAxNi0zLjY5Mi0zLjAzOC03LjE2Mi0zLjAzOC0yLjg5IDAtNS40Ni43NzEtNy42MzcgMi4yOTUtLjcyNi41MS0xLjM3NiAxLjA3MS0xLjk0OCAxLjY3OHYtMS4xNjljMC0yLjk1My0uNzg2LTUuMDI5LTIuNC02LjM0NS0xLjQyNy0xLjIxOC0zLjU1OC0xLjgxLTYuNTEyLTEuODEtMy41NjUgMC02LjY5OS45NTgtOS4zMTUgMi44NDctMi41OTggMS44OTMtNC40NDcgNC41OTUtNS40OTcgOC4wMzEtLjg0NCAyLjY2NS0uOTQ1IDQuOTM2LS4yOTkgNi43NTIuNjg1IDEuODc3IDEuODExIDMuMjEgMy4zNDYgMy45NjQgMS40Mi43MSAzLjM3MyAxLjA3IDUuODAzIDEuMDcgMi4wMjUgMCAzLjc5LS4yODMgNS4yNDYtLjgzOCAxLjQ3Ni0uNTM3IDIuODcyLTEuMzczIDQuMTUyLTIuNDg3Ljg1MS0uNzY1IDEuNzkxLTEuODYgMi41My0yLjkzNi4wNTUgMS45NzMuOTU1IDMuMzI2IDEuNzM3IDQuMTM0IDEuMzk0IDEuMzk0IDMuNDk3IDIuMTAyIDYuMjQ5IDIuMTAyIDIuOTMgMCA1LjUzMS0uNzggNy43My0yLjMyIDEuMjI4LS44NTkgMi4yNDYtMS44ODMgMy4wMzYtMy4wNTRsLTMuMjQzIDEwLjM2aDguNTdsMS43OTYtNS43Yy4xMDguMDU0LjIxOC4xMDYuMzI4LjE1My44MTIuMzcyIDEuNzY0LjU2IDIuODMxLjU2IDIuMTIyIDAgNC4xMS0uNzEyIDUuOTEtMi4xMTcuODE1LS42NCAxLjU0NC0xLjQyNSAyLjE3MS0yLjMzNy0uMDYzIDEuMzI1LjQ5IDIuMjU1IDEuMDAzIDIuODI1Ljk2IDEuMDk3IDIuNDk3IDEuNjMgNC42OTcgMS42MyAxLjQ5NSAwIDIuODkzLS4yMjMgNC4xNTgtLjY2My4zNTgtLjEyNy43MjgtLjI4NCAxLjEwNC0uNDdsLjI3MS44MTRoMTUuODI3bDEuNzYtNS42MjdjLjc3NC0yLjQ1OCAxLjUtMy41NSAxLjk2Ny00LjAyNy4zNy0uMzM1LjcxMS0uNDg0IDEuMTA1LS40ODQuMDk3IDAgLjM4My4wMjcuOTQ4LjI3NmwuOTU1LjQyMi41MjQtLjYzMiAyLjY4OS4wMDYtLjk4OCAzLjE1NWMtLjU5NiAxLjgxNy0uNzk5IDMuMDktLjY0IDQuMDA2LjE1NiAxLjA4LjczNCAxLjk3MyAxLjYyNyAyLjUxMi43OTMuNDg2IDEuOTcuNzEyIDMuNzA2LjcxMiAxLjMwNCAwIDIuNzYyLS4xNTYgNC4zMzYtLjQ2NGwuODc3LS4xNzIgMS4zOC02Ljc2Ni0yLjEzMi42MjZjLS4zMDYuMDg5LS41ODIuMTYtLjgyNi4yMTNsMS4xODctMy44MjFoMy4xNXoiIGNsaXAtcnVsZT0iZXZlbm9kZCIvPgogICAgPHBhdGggZmlsbD0iI2ZmZiIgZmlsbC1ydWxlPSJldmVub2RkIiBkPSJNMTguNTU2IDI3LjE5MmMxLjIyMS0xLjA5OSAyLjY2NC0yLjkzMSAzLjMyMy00LjQ0NmgtNi4wMWMtLjUxNC44MDctMS4xIDEuNTE1LTEuNzM1IDEuOTgtLjgwNy42MzQtMS44MzMuOTc3LTMuMDU1Ljk3Ny0xLjI0NSAwLTIuMS0uNDE1LTIuNTQxLTEuMTk4LS40NjQtLjgwNS0uMzQyLTIuMjczLjMxNy00LjM5OC41NjMtMS43MzQgMS4yNDctMy4wMDUgMi4wNzctMy44MTEgMS4xLTEuMSAyLjM5NS0xLjYzOCAzLjgzNy0xLjYzOC42MzUgMCAxLjE3Mi4xMjMgMS42MTMuMzY3Ljc4MS40NCAxLjEgMS4xMjQgMS4xNzMgMS45OGg2LjAzNGMwLTEuOTA2LS4zNDItNC4wMzMtMS45My01LjMyNy0xLjE3My0xLjAwMi0zLjA1NS0xLjQ5LTUuNjQ0LTEuNDktMy4zMjMgMC02LjE1Ny44OC04LjUyNyAyLjU5LTIuMzQ2IDEuNzA5LTQuMDMxIDQuMTU0LTUuMDA5IDcuMzUzLS43NTggMi4zOTUtLjg1NiA0LjM3NC0uMzE3IDUuODg5LjU2MSAxLjUzOSAxLjQ0MSAyLjYxNCAyLjY4NyAzLjIyNSAxLjIyMi42MTEgMi45NTYuOTI5IDUuMjA0LjkyOSAxLjgzMyAwIDMuNDQ1LS4yNDUgNC43ODktLjc1NyAxLjM0My0uNDg5IDIuNTktMS4yNDYgMy43MTQtMi4yMjV6bTE0Ljg4LTEuNTYzYy43MDktLjU4NSAxLjI3LTEuNTE0IDEuNjg2LTIuODM0LjM2Ni0xLjE5Ny4zOS0yLjEwMi4wMjUtMi42ODgtLjM0My0uNTg3LS45MDUtLjg3OS0xLjY4Ni0uODc5LS44NTYgMC0xLjYzOC4yOTItMi4zNzEuODgtLjczMy42MS0xLjI5NSAxLjUxNC0xLjY2MSAyLjc2LS4zOTEgMS4yNDctLjQxNiAyLjE1MS0uMDQ5IDIuNzYuMzQyLjU4OC45MjkuOTA2IDEuNzM0LjkwNi44MzIgMCAxLjU4OC0uMzE4IDIuMzIxLS45MDV6bTEuMDc2LTEwLjA5YzMuMDA0IDAgNS4wMDguODA1IDYuMDMzIDIuNDE4LjgwNyAxLjMyLjkyOSAyLjkzMy4zMTggNC44MzgtLjY4MyAyLjE1LTEuOTc5IDMuOTEtMy45MzMgNS4yNzgtMS45NTUgMS4zNjgtNC4yNzcgMi4wNzctNi45NjMgMi4wNzctMi40MiAwLTQuMTgtLjU4Ny01LjMwMi0xLjcxLTEuMzctMS40MTctMS43MS0zLjI3NS0uOTc4LTUuNTczLjY1OS0yLjEyNCAxLjk3OS0zLjg4NCAzLjk1OS01LjI3NyAxLjk1My0xLjM2NyA0LjI1LTIuMDUyIDYuODY2LTIuMDUyem0xOS4zODYgNy4yMDhjLS4zNjYgMS4yNDUtLjg3OSAyLjEtMS40OSAyLjYxMy0uNjEuNTE0LTEuMjQ1Ljc4My0xLjkwNS43ODMtLjc1OCAwLTEuMzItLjI2OS0xLjYzOC0uODA4LS4zNDItLjUzNi0uMzE2LTEuMzY3LjAyNC0yLjQ2OC4zNjgtMS4xOTUuODgtMi4wNzYgMS41NDEtMi41ODguNjU5LS41MzggMS4zNDMtLjgwNiAyLjEtLjgwNi42NiAwIDEuMTUuMjQ0IDEuNDQzLjc4LjMxNi41MzguMjkzIDEuMzctLjA3NSAyLjQ5NHptNS42OTUtNS4wODRjLS42NjItMS40MTYtMS45OC0yLjEyNS0zLjk4My0yLjEyNS0xLjAwMiAwLTEuOTguMTk1LTIuOTU3LjYxMi0uNzA5LjI5Mi0xLjU2NC44NzgtMi41OSAxLjc1OGwuNjM1LTIuMDUyaC01LjM1MWwtNi4wMzUgMTkuMjhoNS43NjdsMi4xMDItNi42NzJjLjM5LjU2Mi45MjguOTc3IDEuNTYzIDEuMjQ2LjYzNS4yOTIgMS4zOTIuNDQgMi4yNzIuNDQgMS44MDggMCAzLjUxOS0uNjEyIDUuMDgyLTEuODMzIDEuNTg4LTEuMjQ2IDIuNzM3LTMuMDA1IDMuNDctNS4zMjcuNjYtMi4xMjUuNjYtMy44ODQuMDI1LTUuMzI3em0xMS43IDguMDE2Yy4zNjctLjQxNi42MzctLjk3OC44NTgtMS42MzdsLjI2Ni0uODU2Yy0uODc5LjI2OC0xLjc1Ny40ODgtMi42ODYuNzA4LTEuMjQ3LjI5NC0yLjA3Ny41NjMtMi40NjcuODU2LS40MTcuMjk0LS42ODQuNjEtLjc4My45NzgtLjE0Ny40MTUtLjA5Ny43NTcuMTQ4IDEuMDI1LjIxOC4yNy42MzUuNDE2IDEuMjQ0LjQxNi42NjIgMCAxLjI5NS0uMTQ2IDEuOTMxLS40NC42MzUtLjI5NCAxLjEyMy0uNjM2IDEuNDktMS4wNXptNy4xMS04Ljc0NmMuMzY4LjM5LjU4OC45NTIuNjM3IDEuNjYuMDQ4LjczMy0uMDI2IDEuNDE3LS4yNDMgMi4wNzdsLTEuOTMzIDYuMTgxYy0uMTk0LjY2LS4zMTcgMS4xNzMtLjM0MiAxLjU0LS4wMjQuMzY2LjAyNS44NTQuMTIyIDEuNDRoLTUuMzVjLS4xMjMtLjM2OC0uMTctLjYzMy0uMTctLjgzIDAtLjE5Ni4wMjMtLjQ4OS4wNzItLjg4LS45NTEuNjYtMS44NTcgMS4xNDktMi42ODggMS40NDItMS4xMjQuMzktMi4zNy41ODctMy43MTIuNTg3LTEuNzg0IDAtMy4wMDUtLjM5MS0zLjY5LTEuMTczLS42ODMtLjc1OC0uODU0LTEuNzEtLjQ4OC0yLjg1OC4zMTgtMS4wNS45MjktMS45MzEgMS44MDgtMi42MTUuODc5LS42ODYgMi4yNzItMS4xOTggNC4xNzgtMS41MTYgMi4yNzEtLjQxMyAzLjczOC0uNzA4IDQuNDQ2LS44NTQuNjgzLS4xNzEgMS40MTgtLjM5MSAyLjIyMy0uNjM1LjE5Ny0uNjYuMTk3LTEuMTI1IDAtMS4zOTMtLjIyLS4yNy0uNjg0LS4zOTEtMS40MTYtLjM5MS0uOTUzIDAtMS42ODYuMTQ2LTIuMjQ4LjQxNS0uNDQuMjItLjg1NS42MzYtMS4yNzMgMS4yNDZsLTUuMy0uNTM3Yy40OS0uOTA1IDEuMDAzLTEuNjEzIDEuNTY1LTIuMTI1LjUzOC0uNTE0IDEuMjQ1LS45NTQgMi4xLTEuMzIuNTg1LS4yOTMgMS4zOTItLjQ5IDIuMzQ2LS42MzYuOTc4LS4xNDYgMS45NzgtLjIyIDMuMDU0LS4yMiAxLjcwOSAwIDMuMDguMDc0IDQuMDU1LjI3Ljk3Ny4xNyAxLjczNS41MzYgMi4yNDcgMS4xMjV6bTUuNDAxLTEuMDc3aDUuMzVsLS43MDcgMi4yNzJjLjgzLS45NzcgMS41ODgtMS42NjEgMi4yNDgtMi4wMjcuNjYtLjM5MiAxLjM5MS0uNTYzIDIuMTk5LS41NjMuODU1IDAgMS42ODUuMjQ1IDIuNTQuNzMzbC0yLjk1NyAzLjgxMmMtLjYxLS4yNy0xLjA5OC0uMzktMS40ODktLjM5LS43NTcgMC0xLjQxOC4yOTEtMi4wMy44NTUtLjgyOC44My0xLjYxMiAyLjM0Ny0yLjMxOCA0LjU5MmwtMS40NjggNC42OTFINzkuNDNsNC4zNzUtMTMuOTc1em0yMS4wODcgMGwxLjY2MS01LjMyNi02LjU5OCAyLjczNi0uODA2IDIuNTloLTIuMTI2bC0xLjIyMSAzLjkxaDIuMTI0bC0xLjUzNyA0LjkxYy0uNTEzIDEuNTY0LS43MSAyLjY5LS41ODcgMy4zOTcuMDk4LjY4NC40MzkgMS4yMjIgMS4wMDIgMS41NjIuNTYyLjM0NSAxLjU2NS41MTUgMy4wMDYuNTE1IDEuMjIgMCAyLjU4OS0uMTQ4IDQuMDgtLjQ0bC43NTctMy43MTVjLS44MzMuMjQ1LTEuNDY2LjM2OC0xLjg4NC4zNjgtLjQ4NiAwLS43NTYtLjE3MS0uODU0LS40NjUtLjA0OC0uMTk1IDAtLjU4Ni4xOTctMS4xNzJsMS41NC00Ljk2aDMuMTUxbDEuMjIxLTMuOTFoLTMuMTI2eiIgY2xpcC1ydWxlPSJldmVub2RkIi8+Cjwvc3ZnPgo="
                                                                            alt="Copart" class="copart-img"
                                                                            width="129px" height="53px"></a></li>
                                                            <li class="footer-dropdown-li desktop-only">
                                                                <div class="lang_region_selection_block pos-relative">
                                                                    <button class="footer-dropdown toggle-language-dropdown"
                                                                            data-toggle="dropdown" type="button">English
                                                                    </button>
                                                                    <ul class="dropdown-menu language-dropdown-menu">
                                                                        <!---->
                                                                        <li ng-repeat="obj in userConfig.langOptions"
                                                                            value="en">
                                                                            <a ng-href="/en/"
                                                                               ng-click="$event.preventDefault();changeLanguage(obj.value)"
                                                                               href="/en/">English</a>
                                                                        </li><!---->
                                                                        <li ng-repeat="obj in userConfig.langOptions"
                                                                            value="es">
                                                                            <a ng-href="/es/"
                                                                               ng-click="$event.preventDefault();changeLanguage(obj.value)"
                                                                               href="/es/">Español</a>
                                                                        </li><!---->
                                                                        <li ng-repeat="obj in userConfig.langOptions"
                                                                            value="fr-CA">
                                                                            <a ng-href="/fr-CA/"
                                                                               ng-click="$event.preventDefault();changeLanguage(obj.value)"
                                                                               href="/fr-CA/">Français Canadien</a>
                                                                        </li><!---->
                                                                        <li ng-repeat="obj in userConfig.langOptions"
                                                                            value="ar">
                                                                            <a ng-href="/ar/"
                                                                               ng-click="$event.preventDefault();changeLanguage(obj.value)"
                                                                               href="/ar/">العربية</a>
                                                                        </li><!---->
                                                                        <li ng-repeat="obj in userConfig.langOptions"
                                                                            value="ru">
                                                                            <a ng-href="/ru/"
                                                                               ng-click="$event.preventDefault();changeLanguage(obj.value)"
                                                                               href="/ru/">Русский</a>
                                                                        </li><!---->
                                                                        <li ng-repeat="obj in userConfig.langOptions"
                                                                            value="pl">
                                                                            <a ng-href="/pl/"
                                                                               ng-click="$event.preventDefault();changeLanguage(obj.value)"
                                                                               href="/pl/">Polski</a>
                                                                        </li><!---->
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                            <li class="footer-dropdown-li desktop-only region_section">
                                                                <div class="custom_meta_dropdown copart_countries pos-relative user-select-none">
                                                                    <button class="dropdown-toggle region_selected_block pos-relative footer-dropdown"
                                                                            type="button" data-toggle="dropdown">
                                                                    <span class="copart_country_items m-0-i">
                                                                        <img alt="Selected Country Flag"
                                                                             class="img-responsive max-w-42"
                                                                             data-entity-type="" data-entity-uuid=""
                                                                             src="https://www.copart.com/content/us.svg">
                                                                        <span>USA</span>
                                                                    </span>
                                                                    </button>
                                                                    <div class="dropdown-menu">
                                                                        <ul class="list-style-none copart_country_list d-f j-c_s-b f-w_w footer-country-items">
                                                                            <li class="copart_country_items">
                                                                                <a href="https://www.copart.com">
                                                                                    <img alt="United States"
                                                                                         class="img-responsive max-w-42"
                                                                                         data-entity-type=""
                                                                                         data-entity-uuid=""
                                                                                         src="https://www.copart.com/content/us.svg">
                                                                                    <span>USA</span>
                                                                                </a>
                                                                            </li>
                                                                            <li class="copart_country_items">
                                                                                <a href="https://www.copart.ca">
                                                                                    <img alt="CANADA"
                                                                                         class="img-responsive max-w-42"
                                                                                         data-entity-type=""
                                                                                         data-entity-uuid=""
                                                                                         src="https://www.copart.com/content/ca.svg">
                                                                                    <span>CANADA</span>
                                                                                </a>
                                                                            </li>
                                                                            <li class="copart_country_items">
                                                                                <a href="https://www.copart.co.uk">
                                                                                    <img alt="UK"
                                                                                         class="img-responsive max-w-42"
                                                                                         data-entity-type=""
                                                                                         data-entity-uuid=""
                                                                                         src="https://www.copart.com/content/uk.svg">
                                                                                    <span>UK</span>
                                                                                </a>
                                                                            </li>
                                                                            <li class="copart_country_items">
                                                                                <a href="https://www.copart.ie">
                                                                                    <img alt="IRELAND"
                                                                                         class="img-responsive max-w-42"
                                                                                         data-entity-type=""
                                                                                         data-entity-uuid=""
                                                                                         src="https://www.copart.com/content/ie.svg">
                                                                                    <span>IRELAND</span>
                                                                                </a>
                                                                            </li>
                                                                            <li class="copart_country_items">
                                                                                <a href="https://www.copartmea.com">
                                                                                    <img alt="UAE"
                                                                                         class="img-responsive max-w-42"
                                                                                         data-entity-type=""
                                                                                         data-entity-uuid=""
                                                                                         src="https://www.copart.com/content/ae.svg">
                                                                                    <span>UAE</span>
                                                                                </a>
                                                                            </li>
                                                                            <li class="copart_country_items">
                                                                                <a href="https://www.copartmea.com">
                                                                                    <img alt="BAHRAIN"
                                                                                         class="img-responsive max-w-42"
                                                                                         data-entity-type=""
                                                                                         data-entity-uuid=""
                                                                                         src="https://www.copart.com/content/bh.svg">
                                                                                    <span>BAHRAIN</span>
                                                                                </a>
                                                                            </li>
                                                                            <li class="copart_country_items">
                                                                                <a href="https://www.copartmea.com">
                                                                                    <img alt="OMAN"
                                                                                         class="img-responsive max-w-42"
                                                                                         data-entity-type=""
                                                                                         data-entity-uuid=""
                                                                                         src="https://www.copart.com/content/om.svg">
                                                                                    <span>OMAN</span>
                                                                                </a>
                                                                            </li>
                                                                            <li class="copart_country_items">
                                                                                <a href="https://www.copart.de">
                                                                                    <img alt="GERMANY"
                                                                                         class="img-responsive max-w-42"
                                                                                         data-entity-type=""
                                                                                         data-entity-uuid=""
                                                                                         src="https://www.copart.com/content/de.svg">
                                                                                    <span>GERMANY</span>
                                                                                </a>
                                                                            </li>
                                                                            <li class="copart_country_items">
                                                                                <a href="https://www.copart.es">
                                                                                    <img alt="SPAIN"
                                                                                         class="img-responsive max-w-42"
                                                                                         data-entity-type=""
                                                                                         data-entity-uuid=""
                                                                                         src="https://www.copart.com/content/es.svg">
                                                                                    <span>SPAIN</span>
                                                                                </a>
                                                                            </li>
                                                                            <li class="copart_country_items">
                                                                                <a href="https://www.copart.fi">
                                                                                    <img alt="FINLAND"
                                                                                         class="img-responsive max-w-42"
                                                                                         data-entity-type=""
                                                                                         data-entity-uuid=""
                                                                                         src="https://www.copart.com/content/fi.svg">
                                                                                    <span>FINLAND</span>
                                                                                </a>
                                                                            </li>
                                                                            <li class="copart_country_items">
                                                                                <a href="https://www.copart.com.br">
                                                                                    <img alt="BRAZIL"
                                                                                         class="img-responsive max-w-42"
                                                                                         data-entity-type=""
                                                                                         data-entity-uuid=""
                                                                                         src="https://www.copart.com/content/br.svg">
                                                                                    <span>BRAZIL</span>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div class="footer-column-1 desktop-only">
                                                        <ul class="list-unstyled">
                                                            <li access-value="crashedtoys" pref-code="footerLinks"><a
                                                                        class="brandlogo sprite-brand-ct"
                                                                        data-uname="homepageCrashedtoyswebsitelinkfooter"
                                                                        href="https://www.crashedtoys.com/?intcmp=web_copartfooter_ourbrands_en"
                                                                        target="_blank">&zwnj;</a></li>
                                                            <li access-value="cash4cars" pref-code="footerLinks"><a
                                                                        class="brandlogo brand-cashforcars"
                                                                        href="https://www.cashforcars.com/welcome-copart-buyers/?utm_source=copart-us-footer-logo&amp;utm_medium=footerlogo&amp;utm_campaign=webuycars"
                                                                        target="_blank">&zwnj;</a></li>
                                                            <li access-value="NPA" pref-code="footerLinks"><a
                                                                        class="brandlogo sprite-brand-NPA"
                                                                        data-uname="homepageCopartNPAfooter"
                                                                        href="https://www.npauctions.com/?intcmp=web_copartfooter_ourbrands_en"
                                                                        target="_blank">&zwnj;</a></li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="footer-column">
                                                    <h4 class="footer-title sm-w-inline desktop-only">Get to Know
                                                        Us</h4>

                                                    <h4 class="footer-title toggle-btn sm-w-inline text-center mobile-only"
                                                        data-target="#toggle-content10" data-toggle="collapse">Get to
                                                        Know Us</h4>

                                                    <ul class="list-unstyled collapse" id="toggle-content10">
                                                        <li><!----><a data-uname="homepageAboutcopartfooter"
                                                                      href="./aboutus?intcmp=web_footer_aboutus_en"
                                                                      ng-if="locale.messages['app.label.aboutCopart']">About
                                                                Copart </a><!----></li>
                                                        <li><a data-uname="homepageOurhistoryfooter"
                                                               href="/content/us/en/about-copart/our-history?intcmp=web_footer_ourhistory_en">Our
                                                                History </a></li>
                                                        <li><!----><a data-uname="homepageCopartGlobalfooter"
                                                                      href="https://global.copart.com/"
                                                                      ng-if="locale.messages['app.label.copartGlobal']"
                                                                      target="_blank">Copart Global </a><!----></li>
                                                        <li><!----><a data-uname="howvb3works"
                                                                      href="https://www.copart.com/howVb3Works?intcmp=web_footer_howvb3works_en"
                                                                      ng-if="locale.messages['app.label.copartGlobal']">How
                                                                VB3 Works </a><!----></li>
                                                        <li><!----><a data-uname="howvb3works"
                                                                      href="/content/us/en/landing-page/copart-in-the-community?intcmp=web_footer_copartinthecommunity_en"
                                                                      ng-if="locale.messages['app.label.copartGlobal']">Community </a>
                                                            <!----></li>
                                                        <li><a access-value="member_news"
                                                               data-uname="homepageCopartnewsfooter"
                                                               href="/content/us/en/member-news/index?intcmp=web_footer_membernewsindex_en"
                                                               pref-code="footerLinks">Member News </a></li>
                                                        <li><a access-value="copart_reviews"
                                                               data-uname="homepageCopartreviewsfooter"
                                                               href="./copartreview?intcmp=web_footer_copartreviews_en"
                                                               pref-code="footerLinks">Copart Reviews </a></li>
                                                        <li><a access-value="careers" data-uname="homepageCareersfooter"
                                                               href="/career?intcmp=web_footer_careers_en"
                                                               pref-code="footerLinks" target="_blank">Careers </a></li>
                                                        <li><a access-value="press_releases"
                                                               data-uname="homepagePressreleasefooter"
                                                               href="/content/us/en/press-releases/index?page=0&amp;intcmp=web_footer_pressreleases_en"
                                                               pref-code="footerLinks">Press Releases </a></li>
                                                        <li><a access-value="investor_relations"
                                                               data-uname="homepageInvestorrelationsfooter"
                                                               href="./investorrelation?intcmp=web_footer_investerrelations_en"
                                                               pref-code="footerLinks">Investor Relations </a></li>
                                                        <li><a class="orange-font"
                                                               href="./content/us/en/member-news/live-coronavirus-updates?intcmp=web_footer_coronavirus_mn_en">Covid-19
                                                                Updates </a></li>
                                                    </ul>
                                                </div>

                                                <div class="footer-column">
                                                    <h4 class="footer-title sm-w-inline desktop-only">Find a
                                                        Vehicle</h4>

                                                    <h4 class="footer-title toggle-btn sm-w-inline text-center mobile-only"
                                                        data-target="#toggle-content11" data-toggle="collapse">Find a
                                                        Vehicle</h4>

                                                    <ul class="list-unstyled collapse" id="toggle-content11">
                                                        <li><a data-uname="homepageVehiclefinderfooter"
                                                               href="./vehicleFinder?intcmp=web_footer_vehiclefinder_en">Vehicle
                                                                Finder</a></li>
                                                        <li><a data-uname="homepageUpcomingauctionsfooter"
                                                               href="./salesListResult?intcmp=web_footer_saleslists_en">Sales
                                                                List</a></li>
                                                        <li><a data-uname="homepageWatchlistfooter"
                                                               href="./public/watchList?intcmp=web_footer_watchlistpublic_en"
                                                               ng-show="userConfig.displayUserName == null">Watchlist</a>
                                                        </li>
                                                        <li><a data-uname="homepageSavedsearchesfooter"
                                                               href="./savedsearch?intcmp=web_footer_savedsearches_en">Saved
                                                                Searches</a></li>
                                                        <li><a access-value="veh_alrt"
                                                               data-uname="homepageVehiclealertsfooter"
                                                               href="./vehicleAlerts?intcmp=web_footer_vehiclealerts_en"
                                                               pref-code="footerLinks">Vehicle Alerts</a></li>
                                                    </ul>
                                                </div>

                                                <div class="footer-column">
                                                    <h4 class="footer-title sm-w-inline desktop-only">Auctions</h4>

                                                    <h4 class="footer-title toggle-btn sm-w-inline text-center mobile-only"
                                                        data-target="#toggle-content12" data-toggle="collapse">
                                                        Auctions</h4>

                                                    <ul class="list-unstyled collapse" id="toggle-content12">
                                                        <li><a data-uname="homepageTodaysauctionsfooter"
                                                               href="./todaysAuction?intcmp=web_footer_todaysauctions_en"
                                                               ng-click="routeReload('/todaysAuction')">Today's
                                                                Auctions</a></li>
                                                        <li><a access-value="calendar"
                                                               data-uname="homepageAuctionscalendarfooter"
                                                               href="./auctionCalendar?intcmp=web_footer_auctioncalendar_en"
                                                               pref-code="footerLinks">Auctions Calendar</a></li>
                                                        <li><a data-uname="homepageAuctionsdashboardfooter"
                                                               href="./auctionDashboard?intcmp=web_footer_auctiondashboard_en"
                                                               ng-click="routeReload('/auctionDashboard')">Join
                                                                Auction</a></li>
                                                        <!---->
                                                        <li ng-if="locale.messages['footer.app.label.nightSale']"><a
                                                                    access-value="night_cap_sale"
                                                                    data-uname="homepageNightcapsalesfooter"
                                                                    href="/content/us/en/landing-page/night-cap-auctions?intcmp=web_footer_nightcapsales_en"
                                                                    pref-code="footerLinks">Night Cap Sales</a></li>
                                                        <!---->
                                                        <li>
                                                            <a href="/content/us/en/landing-page/rental-sale?intcmp=web_footer_rentalsale_en">Rental
                                                                Auctions</a></li>
                                                        <li>
                                                            <a href="/content/us/en/landing-page/repossessedvehicles?intcmp=web_footer_repovehicles_en">Repo
                                                                Auctions</a></li>
                                                    </ul>
                                                </div>

                                                <div class="footer-column">
                                                    <h4 class="footer-title sm-w-inline desktop-only">Services</h4>

                                                    <h4 class="footer-title toggle-btn sm-w-inline text-center mobile-only"
                                                        data-target="#toggle-content13" data-toggle="collapse">
                                                        Services</h4>

                                                    <ul class="list-unstyled collapse" id="toggle-content13">
                                                        <li><a access-value="brokers" data-uname="homepageBrokersfooter"
                                                               href="./brokersmarketmakers/1?intcmp=web_footer_brokers_en"
                                                               pref-code="footerLinks">Brokers</a></li>
                                                        <li><a access-value="inspectors"
                                                               data-uname="homepageInspectorsfooter"
                                                               href="/content/us/en/landing-page/third-party-inspections?intcmp=web_footer_inspectors_en"
                                                               pref-code="footerLinks">Inspectors</a></li>
                                                        <li><a access-value="vehicle_reports"
                                                               data-uname="homepageVehiclereportsfooter"
                                                               href="/content/us/en/landing-page/vehicle-reports?intcmp=web_footer_vehiclereports_en"
                                                               pref-code="footerLinks">Vehicle Reports</a></li>
                                                        <li><a access-value="industry_links"
                                                               data-uname="homepageIndustrylinksfooter"
                                                               href="/content/us/en/Services/Industry-Links?intcmp=web_footer_industrylinks_en"
                                                               pref-code="footerLinks">Industry Links</a></li>
                                                        <!---->
                                                        <li ng-if="locale.messages['footer.app.label.intlShip']"><a
                                                                    access-value="intl_shipping"
                                                                    data-uname="homepageInternationalshippingfooter"
                                                                    href="/international-shipping?intcmp=web_footer_internationalshipping_en"
                                                                    pref-code="footerLinks">Shipping</a></li><!---->
                                                        <li><a access-value="tow_prvd" class="crtoys-noshow"
                                                               data-uname="homepageTowprovidersfooter"
                                                               href="./towproviders?intcmp=web_footer_towproviders_en"
                                                               pref-code="footerLinks">Tow Providers</a></li>
                                                        <li access-value="international_buyers" pref-code="footerLinks">
                                                            <a data-uname="homepageInternationalBuyersfooter"
                                                               href="/content/us/en/landing-page/international-buyers?intcmp=web_footer_internationalbuyers_en"
                                                               ng-href="/content/us/en/landing-page/international-buyers?intcmp=web_footer_internationalbuyers_en">International
                                                                Buyers</a></li>
                                                    </ul>
                                                </div>

                                                <div class="footer-column">
                                                    <h4 class="footer-title sm-w-inline desktop-only">Support</h4>

                                                    <h4 class="footer-title toggle-btn sm-w-inline text-center mobile-only"
                                                        data-target="#toggle-content14" data-toggle="collapse">
                                                        Support</h4>

                                                    <ul class="list-unstyled collapse" id="toggle-content14">
                                                        <li access-value="howToBuy" pref-code="footerLinks"><a
                                                                    data-uname="homepageHowtobuyfooter"
                                                                    href="./overview?intcmp=web_footer_howtobuyoverview_en">How
                                                                to Buy</a></li>
                                                        <li><a access-value="faq" data-uname="homepageFaqfooter"
                                                               href="/content/us/en/support/faq-topics/buying?intcmp=web_footer_buying_en"
                                                               ng-click="doTagPageView('faq_buying')"
                                                               pref-code="footerLinks">Common Questions</a></li>
                                                        <li><a access-value="glossary"
                                                               data-uname="homepageGlossaryoftermsfooter"
                                                               href="/content/us/en/support/faq-topics/common-terms?intcmp=web_footer_commonterms_en"
                                                               pref-code="footerLinks">Glossary of Terms</a></li>
                                                        <li><a access-value="resource_center"
                                                               data-uname="homepageResourcecenterfooter"
                                                               href="/content/us/en/landing-page/resource-center?intcmp=web_footer_resource_center_en"
                                                               pref-code="footerLinks">Resource Center</a></li>
                                                        <li><a access-value="help_with_licensing"
                                                               data-uname="homepageHelpwithlicensingfooter"
                                                               href="./helpWithLicensing?intcmp=web_footer_helpwithlicensing_en"
                                                               ng-href="./helpWithLicensing?intcmp=web_footer_helpwithlicensing_en"
                                                               pref-code="footerLinks">Help With Licensing</a></li>
                                                        <!---->
                                                        <li ng-if="locale.messages['footer.app.label.videos']"><a
                                                                    access-value="videos"
                                                                    data-uname="homepageVideosfooter"
                                                                    href="./copartVideos?intcmp=web_footer_copartvideos_en"
                                                                    ng-href="./copartVideos?intcmp=web_footer_copartvideos_en"
                                                                    pref-code="footerLinks">Videos</a></li><!---->
                                                        <!--placed gbl condition for immediate fix, can change it drive through preference for checkLogin-->
                                                        <!--TODO:PostCobalt2.2 should create a preference for this-->
                                                        <li><a access-value="member_fees" check-for-logged-in-user=""
                                                               data-uname="homepageMemberfeesfooter"
                                                               href="./memberFees?intcmp=web_footer_memberfees_en"
                                                               pref-code="footerLinks">Member Fees</a></li>
                                                        <!---->
                                                        <li ng-if="locale.messages['footer.app.label.mobile']"><a
                                                                    access-value="mobile"
                                                                    data-uname="homepageMobilefooter"
                                                                    href="/content/us/en/landing-page/copart-mobile?intcmp=web_footer_membermobile_en"
                                                                    pref-code="footerLinks">Member Mobile</a></li>
                                                        <!---->
                                                        <!---->
                                                        <li ng-if="userConfig.siteCode!='CRTSUS' &amp;&amp; locale.messages['footer.app.label.sellermobile']">
                                                            <a access-value="seller_mobile"
                                                               data-uname="homepageSellermobilefooter"
                                                               href="/content/us/en/landing-page/seller-mobile?intcmp=web_footer_sellermobile_en"
                                                               pref-code="footerLinks">Seller Mobile</a></li><!---->
                                                    </ul>
                                                </div>

                                                <div class="footer-column">
                                                    <div class="footer-column-1">
                                                        <h4 class="footer-title sm-w-inline desktop-only">Connect with
                                                            US</h4>

                                                        <h4 class="footer-title toggle-btn sm-w-inline text-center mobile-only"
                                                            data-target="#toggle-content15" data-toggle="collapse">
                                                            Connect with US</h4>

                                                        <ul class="list-unstyled collapse" id="toggle-content15">
                                                            <li><a access-value="facebook"
                                                                   data-uname="homepageFacebooksharefooter"
                                                                   href="https://www.facebook.com/Copart"
                                                                   ng-href="https://www.facebook.com/Copart"
                                                                   pref-code="footerLinks" target="_blank">Facebook</a>
                                                            </li>
                                                            <li><a access-value="twitter"
                                                                   data-uname="homepageTwittersharefooter"
                                                                   href="https://twitter.com/copart"
                                                                   ng-href="https://twitter.com/copart"
                                                                   pref-code="footerLinks" target="_blank">Twitter</a>
                                                            </li>
                                                            <li><a access-value="instagram"
                                                                   data-uname="homepageInstagramsharefooter"
                                                                   href="https://www.instagram.com/copart1982/"
                                                                   ng-href="https://www.instagram.com/copart1982/"
                                                                   pref-code="footerLinks" target="_blank">Instagram</a>
                                                            </li>
                                                            <li><a access-value="linkedin"
                                                                   href="https://www.linkedin.com/company/copart"
                                                                   ng-href="https://www.linkedin.com/company/copart"
                                                                   pref-code="footerLinks" target="_blank">LinkedIn</a>
                                                            </li>
                                                            <li><a access-value="youtube"
                                                                   data-uname="homepageYoutubesharefooter"
                                                                   href="https://www.youtube.com/copart"
                                                                   ng-href="https://www.youtube.com/copart"
                                                                   pref-code="footerLinks" target="_blank">Youtube</a>
                                                            </li>
                                                            <li><a access-value="wordpress"
                                                                   data-uname="homepageWordpressfooter"
                                                                   href="https://copartcommunity.com/"
                                                                   ng-href="https://copartcommunity.com/"
                                                                   pref-code="footerLinks" target="_blank">Blog</a></li>
                                                        </ul>
                                                    </div>

                                                    <div class="footer-column-1 download-mobile-app">
                                                        <h4 class="footer-title sm-w-inline">Download the App</h4>

                                                        <ul class="list-unstyled mobile-apps">
                                                            <li class="app-links">
                                                                <a data-uname="homepageCopartappleapplinkfooter"
                                                                   href="https://itunes.apple.com/us/app/copart-mobile/id414275302"
                                                                   ng-href="https://itunes.apple.com/us/app/copart-mobile/id414275302"
                                                                   target="_blank" class="brandlogo apple-app-logo"></a>
                                                            </li>
                                                            <li class="app-links-2">
                                                                <a data-uname="homepageCopartandroidapplinkfooter"
                                                                   href="https://play.google.com/store/apps/details?id=com.copart.membermobile&amp;hl=en"
                                                                   ng-href="https://play.google.com/store/apps/details?id=com.copart.membermobile&amp;hl=en"
                                                                   target="_blank"
                                                                   class="brandlogo android-app-logo"></a></li>
                                                        </ul>
                                                    </div>

                                                    <div class="footer-column-1 mobile-only">
                                                        <ul class="list-unstyled copart-sites">
                                                            <li access-value="crashedtoys" pref-code="footerLinks"><a
                                                                        class="brandlogo sprite-brand-ct"
                                                                        data-uname="homepageCrashedtoyswebsitelinkfooter"
                                                                        href="https://www.crashedtoys.com/?intcmp=web_copartfooter_ourbrands_en"
                                                                        target="_blank">&zwnj;</a></li>
                                                            <li access-value="cash4cars" pref-code="footerLinks"><a
                                                                        class="brandlogo brand-cashforcars"
                                                                        href="https://www.cashforcars.com/welcome-copart-buyers/?utm_source=copart-us-footer-logo&amp;utm_medium=footerlogo&amp;utm_campaign=webuycars"
                                                                        target="_blank">&zwnj;</a></li>
                                                            <li access-value="NPA" pref-code="footerLinks"><a
                                                                        class="brandlogo sprite-brand-NPA"
                                                                        data-uname="homepageCopartNPAfooter"
                                                                        href="https://www.npauctions.com/?intcmp=web_copartfooter_ourbrands_en"
                                                                        target="_blank">&zwnj;</a></li>
                                                        </ul>
                                                    </div>

                                                    <div class="mobile-only mobile-footer-dropdown pos-relative">
                                                        <div class="footer-dropdown-li">
                                                            <div class="lang_region_selection_block pos-relative">
                                                                <button class="footer-dropdown toggle-language-dropdown"
                                                                        data-toggle="dropdown" type="button">English
                                                                </button>
                                                                <ul class="dropdown-menu language-dropdown-menu">
                                                                    <!---->
                                                                    <li ng-repeat="obj in userConfig.langOptions"
                                                                        value="en">
                                                                        <a ng-href="/en/"
                                                                           ng-click="$event.preventDefault();changeLanguage(obj.value)"
                                                                           href="/en/">English</a>
                                                                    </li><!---->
                                                                    <li ng-repeat="obj in userConfig.langOptions"
                                                                        value="es">
                                                                        <a ng-href="/es/"
                                                                           ng-click="$event.preventDefault();changeLanguage(obj.value)"
                                                                           href="/es/">Español</a>
                                                                    </li><!---->
                                                                    <li ng-repeat="obj in userConfig.langOptions"
                                                                        value="fr-CA">
                                                                        <a ng-href="/fr-CA/"
                                                                           ng-click="$event.preventDefault();changeLanguage(obj.value)"
                                                                           href="/fr-CA/">Français Canadien</a>
                                                                    </li><!---->
                                                                    <li ng-repeat="obj in userConfig.langOptions"
                                                                        value="ar">
                                                                        <a ng-href="/ar/"
                                                                           ng-click="$event.preventDefault();changeLanguage(obj.value)"
                                                                           href="/ar/">العربية</a>
                                                                    </li><!---->
                                                                    <li ng-repeat="obj in userConfig.langOptions"
                                                                        value="ru">
                                                                        <a ng-href="/ru/"
                                                                           ng-click="$event.preventDefault();changeLanguage(obj.value)"
                                                                           href="/ru/">Русский</a>
                                                                    </li><!---->
                                                                    <li ng-repeat="obj in userConfig.langOptions"
                                                                        value="pl">
                                                                        <a ng-href="/pl/"
                                                                           ng-click="$event.preventDefault();changeLanguage(obj.value)"
                                                                           href="/pl/">Polski</a>
                                                                    </li><!---->
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="footer-dropdown-li region_section">
                                                            <div class="custom_meta_dropdown copart_countries pos-relative user-select-none">
                                                                <button class="dropdown-toggle region_selected_block pos-relative footer-dropdown"
                                                                        type="button" data-toggle="dropdown">
                                                                    <span class="copart_country_items m-0-i">
                                                                        <img alt="Selected Country Flag"
                                                                             class="img-responsive max-w-42"
                                                                             data-entity-type="" data-entity-uuid=""
                                                                             src="https://www.copart.com/content/us.svg">
                                                                        <span>USA</span>
                                                                    </span>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <ul class="list-style-none copart_country_list d-f j-c_s-b f-w_w footer-country-items">
                                                                        <li class="copart_country_items">
                                                                            <a href="https://www.copart.com">
                                                                                <img alt="United States"
                                                                                     class="img-responsive max-w-42"
                                                                                     data-entity-type=""
                                                                                     data-entity-uuid=""
                                                                                     src="https://www.copart.com/content/us.svg">
                                                                                <span>USA</span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="copart_country_items">
                                                                            <a href="https://www.copart.ca">
                                                                                <img alt="CANADA"
                                                                                     class="img-responsive max-w-42"
                                                                                     data-entity-type=""
                                                                                     data-entity-uuid=""
                                                                                     src="https://www.copart.com/content/ca.svg">
                                                                                <span>CANADA</span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="copart_country_items">
                                                                            <a href="https://www.copart.co.uk">
                                                                                <img alt="UK"
                                                                                     class="img-responsive max-w-42"
                                                                                     data-entity-type=""
                                                                                     data-entity-uuid=""
                                                                                     src="https://www.copart.com/content/uk.svg">
                                                                                <span>UK</span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="copart_country_items">
                                                                            <a href="https://www.copart.ie">
                                                                                <img alt="IRELAND"
                                                                                     class="img-responsive max-w-42"
                                                                                     data-entity-type=""
                                                                                     data-entity-uuid=""
                                                                                     src="https://www.copart.com/content/ie.svg">
                                                                                <span>IRELAND</span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="copart_country_items">
                                                                            <a href="https://www.copartmea.com">
                                                                                <img alt="UAE"
                                                                                     class="img-responsive max-w-42"
                                                                                     data-entity-type=""
                                                                                     data-entity-uuid=""
                                                                                     src="https://www.copart.com/content/ae.svg">
                                                                                <span>UAE</span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="copart_country_items">
                                                                            <a href="https://www.copartmea.com">
                                                                                <img alt="BAHRAIN"
                                                                                     class="img-responsive max-w-42"
                                                                                     data-entity-type=""
                                                                                     data-entity-uuid=""
                                                                                     src="https://www.copart.com/content/bh.svg">
                                                                                <span>BAHRAIN</span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="copart_country_items">
                                                                            <a href="https://www.copartmea.com">
                                                                                <img alt="OMAN"
                                                                                     class="img-responsive max-w-42"
                                                                                     data-entity-type=""
                                                                                     data-entity-uuid=""
                                                                                     src="https://www.copart.com/content/om.svg">
                                                                                <span>OMAN</span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="copart_country_items">
                                                                            <a href="https://www.copart.de">
                                                                                <img alt="GERMANY"
                                                                                     class="img-responsive max-w-42"
                                                                                     data-entity-type=""
                                                                                     data-entity-uuid=""
                                                                                     src="https://www.copart.com/content/de.svg">
                                                                                <span>GERMANY</span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="copart_country_items">
                                                                            <a href="https://www.copart.es">
                                                                                <img alt="SPAIN"
                                                                                     class="img-responsive max-w-42"
                                                                                     data-entity-type=""
                                                                                     data-entity-uuid=""
                                                                                     src="https://www.copart.com/content/es.svg">
                                                                                <span>SPAIN</span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="copart_country_items">
                                                                            <a href="https://www.copart.fi">
                                                                                <img alt="FINLAND"
                                                                                     class="img-responsive max-w-42"
                                                                                     data-entity-type=""
                                                                                     data-entity-uuid=""
                                                                                     src="https://www.copart.com/content/fi.svg">
                                                                                <span>FINLAND</span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="copart_country_items">
                                                                            <a href="https://www.copart.com.br">
                                                                                <img alt="BRAZIL"
                                                                                     class="img-responsive max-w-42"
                                                                                     data-entity-type=""
                                                                                     data-entity-uuid=""
                                                                                     src="https://www.copart.com/content/br.svg">
                                                                                <span>BRAZIL</span>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="footer-flex-container site-links">
                                                <div class="footer-copyright">Copyright @ 2021 Copart Inc. All Rights
                                                    Reserved
                                                </div>

                                                <ul class="footer-links list-unstyled">
                                                    <li><a data-uname="homepageSitemapslimfooter"
                                                           href="./sitemapslimfooter?intcmp=web_footer_sitemap_en">Site
                                                            Map</a></li>
                                                    <li><a data-uname="homepageContactusslimfooter"
                                                           href="/content/us/en/contact-us/member-services"
                                                           ng-href="/content/us/en/contact-us/member-services">Contact
                                                            Us</a></li>
                                                    <li><a data-uname="homepageSellavehicleslimfooter"
                                                           href="./sellForIndividuals?intcmp=web_footer_sellforindividuals_en">Sell
                                                            a Vehicle</a></li>
                                                    <li><a data-uname="homepageTermsofserviceslimfooter"
                                                           href="/content/us/en/terms-of-use"
                                                           ng-href="/content/us/en/terms-of-use">Terms of Service</a>
                                                    </li>
                                                    <li><a data-uname="homepagePrivacypolicyslimfooter"
                                                           href="./content/us/en/privacy-policy?intcmp=web_footer_privacypolicy_en">Privacy
                                                            Policy</a></li>
                                                    <li><a data-uname="homepageCopyrightslimfooter"
                                                           href="./content/us/en/copyright?intcmp=web_footer_copyright_en">Copyright</a>
                                                    </li>
                                                    <li><a data-uname="homepageTermsandconditionsfooter"
                                                           href="./content/us/en/member-terms-and-conditions?intcmp=web_footer_termsandconditions_en">Terms
                                                            &amp; Conditions</a></li>
                                                    <li><a data-uname="homepageCookiepolicyfooter"
                                                           href="./content/us/en/cookie-policy?intcmp=web_footer_cookiepolicy_en">Cookie
                                                            Policy</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </footer>
                    </div>

                </div><!----></div>
        </div>
        <!--  end footer container  -->
    </div>

</div>


</div><!----></div>
</div>
</body>


</body>
</html>

