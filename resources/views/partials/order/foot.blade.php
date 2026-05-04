<!-- Jquery js-->
<script src="{{ url('/assets/js/jquery-3.5.1.min.js') }}"></script>

<!-- Bootstrap4 js-->
<script src="{{ url('/assets/plugins/bootstrap/popper.min.js') }}"></script>
<script src="{{ url('/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>


<!-- INTERNAL Notifications js -->
{{--<script src="{{ url('assets/plugins/notify/js/rainbow.js') }}"></script>--}}
{{--<script src="{{ url('assets/plugins/notify/js/sample.js') }}"></script>--}}
{{--<script src="{{ url('assets/plugins/notify/js/jquery.growl.js') }}"></script>--}}
{{--<script src="{{ url('assets/plugins/notify/js/notifIt.js') }}"></script>--}}

<!--Othercharts js-->
<script src="{{ url('/assets/plugins/othercharts/jquery.sparkline.min.js') }}"></script>

<!-- Circle-progress js-->
<script src="{{ url('/assets/js/circle-progress.min.js') }}"></script>

<!-- Jquery-rating js-->
<script src="{{ url('/assets/plugins/rating/jquery.rating-stars.js') }}"></script>
<!-- Custom js-->
<script src="{{ url('/assets/js/custom.js') }}"></script>
<script>
    $(document).ready(function() {
        
        var now = new Date();
        var millisTill10 = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 5, 0, 0, 0) - now;
        if (millisTill10 < 0) {
            millisTill10 += 86400000; 
        }
        setTimeout(function(){
            $.ajax({
                url:"{{url('/logoutAllAccounts')}}",
                type:'GET',
                dataType:'json',
                success: function (res){
                    console.log(res);
                }
            });
        }, millisTill10);
    })
</script>
