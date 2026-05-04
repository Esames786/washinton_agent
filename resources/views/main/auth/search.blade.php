<div class="row">
    <div class="col-sm-12 mt-3">
        <table class="table table-bordered table-striped key-buttons">
            <thead>
              <tr>
                <th scope="col">SNo#</th>
                <th scope="col">Name</th>
                <th scope="col">Ip</th>
                <th scope="col">Location</th>
                <th scope="col">Date Time</th>
                <th scope="col">Activity</th>
              </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $val)
                  <tr>
                    <th scope="row">{{$key + 1}}</th>
                    <td>{{$val->name}}</td>
                    <td>{{$val->ip}}</td>
                    <td>{{$val->location}}</td>
                    <td>{{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}</td>
                    <td>{{$val->activity}}</td>
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