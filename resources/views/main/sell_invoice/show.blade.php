<div class="table-responsive">
    <table class="table table-bordered table-sm" style="width:100%" id="" role="grid">
        <thead>
        <tr>
            <th class="border-bottom-0">Invoice No#</th>
            <th class="border-bottom-0">Created At</th>
            <th class="border-bottom-0">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $key => $val)
            <tr>
                <td>{{ $val->invoice_number }}</td>
                <td>{{ \Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A') }}</td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-info" href="{{url('/sell_invoice/edit/'.$val->invoice_number)}}" title="Edit"><i class="fa fa-edit"></i></a>
                        <a class="btn btn-success" href="{{url('/sell_invoice/invoice/'.$val->invoice_number)}}" title="Invoice"><i class="fa fa-file"></i></a>
                    </div>
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