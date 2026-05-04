@include('partials.mainsite_pages.return_function')
<div class="row">
    <div class="col-sm-12 mt-3">
        <table class="table table-bordered table-striped key-buttons">
            <thead>
              <tr>
                <th scope="col">OrderId/Date</th>
                <th scope="col">Status</th>
                <th scope="col">Verify</th>
                <th scope="col">Negative</th>
                <th scope="col">Decision</th>
                <th scope="col">Action</th>
                <th scope="col">Admin Action</th>
              </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $val)
                  <tr>
                    <th scope="row">
                        <a href="{{url('searchData')}}?search={{$val->order_id}}" target="_blank">{{$val->order_id}}</a>
                        <br>
                        {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y')}}
                        <br>
                        {{\Carbon\Carbon::parse($val->created_at)->format('h:i A')}}
                    </th>
                    <td>{!! html_entity_decode(get_pstatus2($val->pstatus)) !!}</td>
                    <td>
                        @if($val->verify == 1)
                            <span class="badge badge-success text-light">Verified</span>
                        @else
                            <span class="badge badge-danger">Not Verified</span>
                        @endif
                    </td>
                    <td>
                        @if($val->negative == 1)
                            <span class="badge badge-danger">Negative</span>
                            <br>
                            {{$val->negative_to_user_id ? get_user_name($val->negative_to_user_id) : 'N/A'}}
                        @else
                            <span class="badge badge-success text-light">Not Negative</span>
                        @endif
                    </td>
                    <td>{{$val->decision}}</td>
                    <td>
                        <b>{{get_user_name($val->user_id)}}:</b> {{$val->remarks ?? 'N/A'}}
                    </td>
                    <td>
                        @if(isset($val->admin_agree))
                            @if($val->admin_agree == 'Agree')
                                <span class="badge badge-success text-light">{{$val->admin_agree}}</span>
                            @else
                                <span class="badge badge-danger">{{$val->admin_agree}}</span>
                            @endif
                            @if(isset($val->admin_remarks))
                                <br>
                                {{$val->admin_remarks}}
                            @endif
                        @else
                            N/A
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