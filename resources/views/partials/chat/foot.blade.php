<!-- Jquery js-->
<script src="{{ url('assets/js/jquery-3.5.1.min.js') }}"></script>

<!-- Bootstrap4 js-->
<script src="{{ url('assets/plugins/bootstrap/popper.min.js') }}"></script>
<script src="{{ url('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

<!--Othercharts js-->
<script src="{{ url('assets/plugins/othercharts/jquery.sparkline.min.js') }}"></script>

<!-- Circle-progress js-->
<script src="{{ url('assets/js/circle-progress.min.js') }}"></script>

<!-- Jquery-rating js-->
<script src="{{ url('assets/plugins/rating/jquery.rating-stars.js') }}"></script>

<!--Sidemenu js-->
<script src="{{ url('assets/plugins/sidemenu/sidemenu.js') }}"></script>

<!-- P-scroll js-->
<script src="{{ url('assets/plugins/p-scrollbar/p-scrollbar.js') }}"></script>
<script src="{{ url('assets/plugins/p-scrollbar/p-scroll1.js') }}"></script>
<script src="{{ url('assets/plugins/p-scrollbar/p-scroll.js') }}"></script>

<!-- INTERNAL Chat js-->
<script src="{{ url('assets/js/chat.js') }}"></script>
<!-- Simplebar JS -->
<script src="{{ url('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
<!-- Custom js-->
<script src="{{ url('assets/js/custom.js') }}"></script>

<!-- Switcher js-->
<script src="{{ url('assets/switcher/js/switcher.js') }}"></script>
<script src="{{ url('assets/js/htmlCanva.min.js')}}"></script>
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
        
        
        function chatNoti()
        {
            $.ajax({
                url:"/chat-noti",
                type:"GET",
                success:function(res)
                {
                    $("#message-menu").html("");
                    $("#message-menu").html(res);
                }
            });
            
            $.ajax({
                url:"/chat-noti-count",
                type:"GET",
                success:function(res)
                {
                    $("#msg_count").text("");
                    $("#msg_count").text(res.count);
                }
            });
        }
        
        chatNoti();
        setInterval(function(){
            chatNoti();
        },10000);
    })
</script>
