@include('partials.mainsite_pages.return_function')
<div class="row">
    <div class="col-sm-12 mt-3">
        <table class="table table-bordered table-striped key-buttons">
            <thead>
              <tr>
                <th scope="col">OrderId</th>
                <th scope="col">Pickup</th>
                <th scope="col">Delivery</th>
                <th scope="col">Company</th>
                <th scope="col">Date</th>
              </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $val)
                  <tr>
                    <th scope="row">
                        <a href="{{url('searchData')}}?search={{$val->id}}" target="_blank">{{$val->id}}</a>
                    </th>
                    <td>
                        <a href="https://www.google.com/maps/dir/{{$val->originzip}},+USA/"
                           target="_blank" class="table1ancher">
                            <i class="fa fa-map-marker" style="color:green;" aria-hidden="true"></i>
                            <span>  {{$val->origincity . "-".$val->originstate ."-" .$val->originzip  }}</span>
                        </a>
                    </td>
                    <td>
                        <a href="https://www.google.com/maps/dir/{{$val->destinationzip }},+USA/"
                           target="_blank" class="table1ancher">
                            <i class="fa fa-map-marker" style="color:red;" aria-hidden="true"></i>
                           <span> {{$val->destinationcity . "-".$val->destinationstate ."-" .$val->destinationzip  }}</span>
                        </a>
                    </td>
                    <td>
                        Company Name: <b>{{$val->carrier->companyname ?? 'N/A'}}</b><br />
                        Company Email: <b>{{$val->carrier->email ?? 'N/A'}}</b><br />
                        Pay Carrier: <b>{{$val->pay_carrier ?? 'N/A'}}</b><br />
                        Company Comments: <b>{{$val->carrier->company_comments ?? 'N/A'}}</b>
                    </td>
                    <td>
                        {!! html_entity_decode(get_pstatus2($val->pstatus)) !!}
                        <br>
                        Created At: {{\Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A')}}
                        <br>
                        Updated At: {{\Carbon\Carbon::parse($val->updated_at)->format('M,d Y h:i A')}}
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