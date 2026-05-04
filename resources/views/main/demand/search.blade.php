@include('partials.mainsite_pages.return_function')
<div class="table-responsive">
    <table class="table table-bordered table-sm" style="width:100%" id="" role="grid">
        <thead>
        <tr>
            <th class="border-bottom-0">Demand Id#</th>
            <th class="border-bottom-0">Vehicle Name</th>
            <th class="border-bottom-0">Vehicle Detail</th>
            <th class="border-bottom-0">Vehicle Detail</th>
            <th class="border-bottom-0">Vehicle Detail</th>
            <th class="border-bottom-0">Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $key => $val)
            <tr>
                <td>
                    <b></b>{{ $val->id }}<br>
                    <b>Order Id#</b> {{ $val->order_id }}<br>
                    <b>Send By:</b> {{ get_user_name($val->user_id) }}<br>
                    <b>Send To:</b> {{ $val->email }}<br>
                    <b>Expire At:</b> {{ \Carbon\Carbon::parse($val->created_at)->addDays(1)->format('M,d Y h:i A') }}
                </td>
                <td>
                    @if($val->status == 2)
                    <b>Year:</b> {{$val->from_year}} - {{$val->to_year}}<br>
                    <b>Make:</b> {{$val->make}}<br>
                    <b>Model:</b> {{$val->model}}<br>
                    <b>Trim Level:</b> {{$val->trim_level ?? 'N/A'}}<br>
                    @endif
                </td>
                <td>
                    @if($val->status == 2)
                    <b>Mileage:</b> {{$val->mileage ?? 'N/A'}}<br>
                    <b>Car Color:</b> {{$val->car_color ?? 'N/A'}}<br>
                    <b>Interior Color:</b> {{$val->interior_color ?? 'N/A'}}<br>
                    @endif
                </td>
                <td>
                    @if($val->status == 2)
                    <b>Condition:</b> {{$val->condition ?? 'N/A'}}<br>
                    <b>Title:</b> {{$val->title ?? 'N/A'}}<br>
                    <b>Body Condition:</b> {{$val->body_condition ?? 'N/A'}}<br>
                    @endif
                </td>
                <td>
                    @if($val->status == 2)
                    <b>Budget:</b> {{$val->from_budget}} - {{$val->to_budget}}<br>
                    <b>How Much Time:</b> {{$val->how_much_days ?? 'N/A'}}<br>
                    <b>Requirement:</b> {{$val->requirement ?? 'N/A'}}<br>
                    @endif
                </td>
                <td>
                    @if($val->status == 2)
                        <span class="badge badge-success text-light">Submitted</span><br>
                        <span class="badge badge-info text-light mt-2">{{$val->payment_method}}</span><br><br>
                    @else
                        <span class="badge badge-danger text-light">Pending</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <div class="text-secondary my-auto">
            Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} from total {{$data->total()}} entries
        </div>
        <div>
            {{  $data->links() }}
        </div>
    </div>
</div>