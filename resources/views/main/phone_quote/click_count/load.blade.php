@include('partials.mainsite_pages.return_function')

<div class="table-responsive">
    <table id="" class="table tableClick table-striped table-bordered text-nowrap">
        <thead>
        <tr>
            <th class="border-bottom-0">ORDER#</th>
            <th class="border-bottom-0">Date</th>
            <th class="border-bottom-0">C-NAME</th>
            <th class="border-bottom-0">C-EMAIL</th>
            <th class="border-bottom-0">USER NAME</th>
            <th class="border-bottom-0">TOTAL CLICK</th>
            <th class="border-bottom-0">CLICK STATUS</th>
            <th class="border-bottom-0">CURRENT STATUS</th>
            <th class="border-bottom-0">HISTORY UPDATED</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $val)
            <tr>
                <td>{{$val->order_id}}</td>
                <td>{{\Carbon\Carbon::parse($val->created_at)->format('M,d Y')}}</td>
                <td>{{$val->client_name}}</td>
                <td>{{$val->client_email}}</td>
                <td><span class="badge badge-pill badge-default mt-2">{{get_user_name($val->user_id)}}<br></span></td>
                <td>{{$val->total_clicks}}</td>
                <td> <span class="badge badge-pill badge-success-light mt-2">{{get_pstatus($val->pstatus)}}</span></td>
                <td> <span class="badge badge-pill badge-success mt-2">{{ get_pstatus(get_autoorder($val->order_id,'pstatus'))}}</span></td>
                <td>
                    <button data-user_id="{{$val->user_id}}" data-order_id="{{$val->order_id}}" type="button" class="btn btn-link btn-sm btn-show-history" data-toggle="modal" data-target="#historyModal">
                        <i class="fa fa-eye"></i> {{ count_history($val->user_id, $val->order_id) }}
                    </button>


            </tr>
        @endforeach
        </tbody>
    </table>
    {{  $data->links() }}

</div>





