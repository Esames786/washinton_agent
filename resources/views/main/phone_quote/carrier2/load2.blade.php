@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]",'/');
if(isset($_GET['titlee'])){
    $respn = $_GET['titlee'];
}
?>

@php
    $check_panel = check_panel();

    if($check_panel == 1){

    $phoneaccess=explode(',',Auth::user()->emp_access_phone);
    }
    elseif($check_panel == 3)
    {
        $phoneaccess = explode(',',Auth::user()->emp_access_test);
    }
    else{
    $phoneaccess=explode(',',Auth::user()->emp_access_web);
    }
@endphp
<div class="table-responsive">
    <table id="example1" class="table table-striped table-bordered text-wrap">
        <thead>
        <tr>
            <th class="border-bottom-0">TYPE</th>
            <th class="border-bottom-0">COMAPNY </th>
            <th class="border-bottom-0">ADDRESS</th>
            @if (in_array("42", $phoneaccess))
            <th class="border-bottom-0">MAIN/LOCAL NUMBER</th>
            @endif
            <th class="border-bottom-0">TRUCK/EQUIPEMTNS</th>
            <th class="border-bottom-0">ROUTE DESCRIPTIONS</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $val)
            <tr>
                <td>{{$val->typev}}</td>
                <td>{{$val->company_name}}</td>
                <td>{{$val->address}}</td>
                <td>
                    @if(in_array("60", $phoneaccess))
                        <?php 
                            $digits = \App\PhoneDigit::first();
                            if(in_array("61", $phoneaccess))
                            {
                                $new = $val->main_number;
                                $new2 = $val->local_number;
                            }
                            else
                            {
                                $new = putX($digits->hide_digits,$digits->left_right_status,$val->main_number);
                                $new2 = putX($digits->hide_digits,$digits->left_right_status,$val->local_number);
                            }
                        ?>
                    <span class="badge badge-primary mb-2">{{ $new }}</span><br>
                    <span class="badge badge-primary">{{ $new2 }} </span>
                    @endif
                </td>
                <td><span class="badge badge-dark mb-2">{{$val->truck}}</span><br>
                    {{$val->equipment}}
                </td>
                <td>{{$val->route_description}}</td>
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
