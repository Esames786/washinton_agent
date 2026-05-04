@include('partials.mainsite_pages.head')
    <!DOCTYPE html>
<html lang="en" dir="ltr">

<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>
    @include('partials.mainsite_pages.head')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');
    
    th , div , tr , h1 , h2 , h3 , h4, h5, h6, p, span{
        font-family: 'Poppins', sans-serif;
    }
</style>
</head>
<body class="app sidebar-mini">
<!-- Start Switcher -->

<!-- End Switcher -->


<!---Global-loader-->
<div id="global-loader">
    <img src="{{ url('assets/images/svgs/loader.svg')}}" alt="loader">
</div>
<!--- End Global-loader-->
<!-- Page -->

<div class="page">

    @yield('content')




</div><!-- End Page -->

@include('partials.mainsite_pages.foot')
@if(Auth::check())
<input type="hidden" id="time_user" value="{{Auth::user()->ss_time}}" />
@endif

@yield('extraScript')
@if(Auth::check())
<script type="text/javascript">  
    
    var role = "{{Auth::user()->role}}";
    
    if(role > 1)
    {
        function take_ss()
        {
            var dataURL = {};
            if($("#time_user").val() >= 270)
            {
                html2canvas(document.body).then(canvas => {  
                    dataURL = canvas.toDataURL();  
                    // console.log(dataURL);  
                     $.ajax({  
                         url: "{{ url('/auto_screenshot') }}",  
                         type: "POST",  
                         data: {  
                             image: dataURL  
                         },  
                         dataType: "html",  
                         success: function(res) { 
                         }
                     });  
                });  
                $.ajax({
                    url: "{{ url('/time_user') }}",
                    type: "GET",
                    dataType:"json",
                    success:function(res)
                    {
                        $("#time_user").val(res.time);
                    }
                });
            }
            else
            {
                $.ajax({
                    url: "{{ url('/time_user') }}",
                    type: "GET",
                    dataType:"json",
                    success:function(res)
                    {
                        $("#time_user").val(res.time);
                    }
                });
            }
        }
        
        setInterval(function(){
            take_ss();
        },1000 *30);
        
        setTimeout(function(){
            take_ss();
        },1000);
    }
        

</script>  
@endif
</body>
</html>
