<!-- Meta data -->
<meta charset="UTF-8">
<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
<meta content="Admitro - Laravel Bootstrap Admin Template" name="description">
<meta content="Spruko Technologies Private Limited" name="author">
<meta name="keywords"
      content="laravel admin dashboard, best laravel admin panel, laravel admin dashboard, php admin panel template, blade template in laravel, laravel dashboard template, laravel template bootstrap, laravel simple admin panel,laravel dashboard template,laravel bootstrap 4 template, best admin panel for laravel,laravel admin panel template, laravel admin dashboard template, laravel bootstrap admin template, laravel admin template bootstrap 4"/>

<!-- Title -->
<title>@if (trim($__env->yieldContent('template_title')))@yield('template_title') | @endif {{ config('app.name', Lang::get('titles.app')) }}</title>

<!--Favicon -->
<link rel="icon" href="{{ url('/assets/images/brand/favicon.ico') }}" type="image/x-icon"/>

<!--Bootstrap css -->
<link href="{{ url('/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

<!-- Style css -->
<link href="{{ url('/assets/css/style.css') }}" rel="stylesheet"/>
<link href="{{ url('/assets/css/dark.css') }}" rel="stylesheet"/>
<link href="{{ url('/assets/css/skin-modes.css') }}" rel="stylesheet"/>

<!-- Animate css -->
<link href="{{ url('/assets/css/animated.css') }}" rel="stylesheet"/>

{{--<link href="{{ url('assets/plugins/notify/css/jquery.growl.css') }}" rel="stylesheet" />--}}
{{--<link href="{{ url('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />--}}

<!---Icons css-->
<link href={{ url('/assets/css/icons.css') }} rel="stylesheet"/>


<!-- Color Skin css -->
<link id="theme" href={{ url('/assets/colors/color1.css') }} rel="stylesheet" type="text/css"/>
