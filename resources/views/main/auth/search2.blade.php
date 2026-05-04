@include('partials.mainsite_pages.return_function')
<div class="row">
    <div class="col-sm-12 mt-3">
        <table class="table table-bordered table-striped key-buttons">
            <thead>
              <tr>
                <th scope="col">SNo#</th>
                <th scope="col">Name</th>
                <th scope="col">Start Time</th>
                <th scope="col">End Time</th>
                <th scope="col">Total Time</th>
              </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $val)
                  <tr>
                    <th scope="row">{{$key + 1}}</th>
                    <td>{{get_user_name($val->user_id)}}</td>
                    <td>{{\Carbon\Carbon::parse($val->start_time)->format('M,d Y h:i A')}}</td>
                    <td>
                        @if(isset($val->end_time))
                            {{\Carbon\Carbon::parse($val->end_time)->format('M,d Y h:i A')}}
                        @else
                            User on Break
                        @endif
                    </td>
                    <td>
                        @if(isset($val->end_time))
                            <?php 
                                $time_difference_in_minutes = \Carbon\Carbon::parse($val->end_time)->diffForHumans(\Carbon\Carbon::parse($val->start_time));
                            ?>
                            {{$time_difference_in_minutes}}
                        @else
                            User on Break
                        @endif
                    </td>
                  </tr>
              @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-sm-12 mt-3">
        <div class="row">
            <div class="col-md-6">
                Showing {{$data->firstItem() ?? 0}} to {{$data->lastItem() ?? 0}} of {{$data->total()}} results
            </div>
            <div class="col-md-6 justify-content-end d-flex">                
                {{$data->links()}}
            </div>
        </div>
    </div>
</div>