{{--
    The NDA on its own page.

    Deliberately bare: the agent cannot use the portal until this is signed, so loading the
    dashboard behind it (385 KB, 56 scripts, 42 AJAX calls) only made the page freeze on the
    machines our agents actually use. The form partial is unchanged and needs nothing but a CSRF
    token — it is plain JavaScript and fetch(), with no jQuery.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>NDA &amp; Confidentiality Agreement</title>
<style>
  html, body {
    margin: 0; padding: 0; min-height: 100%;
    background: #0f172a;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
  }
</style>
</head>
<body>

@include('nda.modal')

</body>
</html>
