<!DOCTYPE html>
<html lang="en" dir="ltr">

<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>
    @include('partials.order.head')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');
        
        th , div , tr , h1 , h2 , h3 , h4, h5, h6, p, span{
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="h-100vh bg-primary">

    @yield('content')

    @include('partials.order.foot')

    @yield('extraScript')

</body>
</html>
