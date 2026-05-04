<html>
<!-- Jquery js-->
<script src="{{ url('assets/js/jquery-3.5.1.min.js')}}"></script>

<!-- Bootstrap4 js-->
<script src="{{ url('assets/plugins/bootstrap/popper.min.js')}}"></script>
<script src="{{ url('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

<!--Othercharts js-->
<script src="{{ url('assets/plugins/othercharts/jquery.sparkline.min.js')}}"></script>

<!-- Circle-progress js-->
<script src="{{ url('assets/js/circle-progress.min.js')}}"></script>
<script src="{{ url('assets/js/form-editor.js')}}"></script>

<script src="{{ url('assets/plugins/peitychart/jquery.peity.min.js')}}"></script>
<script src="{{ url('assets/plugins/peitychart/peitychart.init.js')}}"></script>

<!--INTERNAL Apexchart js-->
<script src="{{ url('assets/js/apexcharts.js')}}"></script>
<script src="{{ url('assets/js/apexchart-custom.js')}}"></script>

<!--INTERNAL ECharts js-->
<script src="{{ url('assets/plugins/echarts/echarts.js')}}"></script>

<!--INTERNAL Chart js -->
<script src="{{ url('assets/plugins/chart/chart.bundle.js')}}"></script>
<script src="{{ url('assets/plugins/chart/utils.js')}}"></script>

<!-- INTERNAL Select2 js -->
<script src="{{ url('assets/plugins/select2/select2.full.min.js')}}"></script>


<script src="{{ url('assets/js/custom.js')}}"></script>

<!-- Switcher js-->
<script src="{{ url('assets/switcher/js/switcher.js')}}"></script>


    <div class="card-body">
        <div class="chartjs-wrapper-demo">
            <div id="chart44" class="h-300 mh-300"></div>
        </div>
    </div>


<script>
    var options44 = {
        series: [
            <?php
             foreach($get_month as $totalorder){
               echo $totalorder.',';
             }  

           ?>
        ],
        colors: [
            '#705ec8',
            '#fa057a',
            '#2dce89',
            '#ff5b51',
            '#fcbf09',
            '#fcbf09',
            '#fcbf09',
            '#fcbf09',
            '#fcbf09',
            '#fcbf09',
            '#fcbf09',
            '#fcbf09',
            '#fcbf09',
            '#fcbf09',
            '#fcbf09'
        ],
        chart: {
            height: 400,
            type: 'donut',
        },
        labels: [
            'New',
            'FollowUp',
            'Interested',
            'Asking Low',
            'Not Interested',
            'NotAnswer',
            'TimeQuote',
            'Payment Missing',
            'Booked',
            'Listed',
            'Dispatch',
            'Pickedup',
            'Deliver',
            'Completed',
            'Cancel'
        ],
        legend: {
            show: false,
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 400
                },
                legend: {
                    show: false,
                    position: 'bottom'
                }
            }
        }]
    };
    var chart44 = new ApexCharts(document.querySelector("#chart44"), options44);
    chart44.render();
</script>

</html>