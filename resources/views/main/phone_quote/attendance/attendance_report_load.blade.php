@include('partials.mainsite_pages.return_function')


<div class="table-responsive">

    <?php
    if($display == 'yes'){
    ?>
    <table class="table table-striped table-bordered text-nowrap">
        <thead>
        <tr>
            <th class="border-bottom-0">USER ID</th>
            <th class="border-bottom-0">USER NAME</th>
            <th class="border-bottom-0">LOGIN TIME</th>
            <th class="border-bottom-0">LOGOUT TIME</th>
        </tr>
        </thead>
        <tbody>
        @php
            $val = "attendance_date";
            $val2 = "logout";

        @endphp
        @foreach($users as $user)
            <tr>
                <td>{{$user->id}}</td>
                <td>{{$user->name}}</td>
                <td>{{ get_user_attendance($attendancedate,$user->id,$val)}}</td>
                <td>{{ get_user_attendance($attendancedate,$user->id,$val2)}}</td>
            </tr>
        @endforeach

        </tbody>

    </table>
    <?php
    }
    ?>
    {{$users->links()}}
</div>





