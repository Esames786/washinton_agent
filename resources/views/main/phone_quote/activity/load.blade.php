@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]",'/');
if(isset($_GET['titlee'])){
    $respn = $_GET['titlee'];
}
?>
<div class="table-responsive">
    <table class="table table-striped table-bordered text-nowrap">
        <thead>
        <tr>

            <th class="border-bottom-0">DATE</th>
            <th class="border-bottom-0">USER NAME</th>
            <th class="border-bottom-0">START TIME</th>
            <th class="border-bottom-0">END TIME</th>
            <th class="border-bottom-0">DURATION(MINUTES)</th>
            <th class="border-bottom-0">URL</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $val)
            <tr>

                <td>
                    {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}
                </td>
                <td>
                    {{ get_user_name($val->user_id)}}
                </td>

                <td>
                    {{\Carbon\Carbon::parse($val->inactivity_time_start)->format('M,d Y')}}

                </td>
                <td>
                    {{\Carbon\Carbon::parse($val->inactivity_time_end)->format('M,d Y')}}
                    
                </td>
                <td>
                    {{ $val->duration }}
                </td>
                <td>
                    {{ $val->url_name }}
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
    {{  $data->links() }}

</div>


<script>
    regain_call();
    regain_status();
    regain_report_modal();
</script>
