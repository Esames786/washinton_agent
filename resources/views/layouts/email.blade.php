<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    @include('partials.email.head')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');
        
        th , div , tr , h1 , h2 , h3 , h4, h5, h6, p, span{
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body>

<!-- Start of banner -->
<table id="body-table" align="center" width="100%" bgcolor="#e6e5e7" cellspacing="0" cellpadding="0" border="0" style="table-layout:fixed;">
    <tbody>
        @yield('content')
    </tbody>
</table>
<!-- End of banner -->
</body>
</html>