<div class="table-responsive">
    <table class="table table-bordered table-sm" style="width:100%" id="" role="grid">
        <thead>
        <tr>
            <th class="border-bottom-0">SNO#</th>
            <th class="border-bottom-0">Role Name</th>
            <th class="border-bottom-0">Status</th>
            <th class="border-bottom-0">Created At</th>
            <th class="border-bottom-0">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($role as $key => $val)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{$val->name }}</td>
                <td>
                    @if($val->level == 1)
                        <span class="badge badge-success text-light">Active</span>
                    @else
                        <span class="badge badge-danger">Disabled</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($val->created_at)->format('M,d Y h:i A') }}</td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-info" href="{{url('/role/edit/'.$val->id)}}" title="Edit"><i class="fa fa-edit"></i></a>
                        @if($val->level == 1)
                            <a class="btn btn-danger" href="{{url('/role/destroy/'.$val->id)}}" title="Disabled"><i class="fa fa-trash"></i></a>
                        @else
                            <a class="btn btn-success" href="{{url('/role/destroy/'.$val->id)}}" title="Active"><i class="fa fa-universal-access" aria-hidden="true"></i></a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <input type="hidden" value="{{url('')}}" class="url">
    <div class="d-flex justify-content-between">
        <div class="text-secondary my-auto">
            Showing {{ $role->firstItem() }} to {{ $role->lastItem() }} from total {{$role->total()}} entries
        </div>
        <div>
            {{  $role->links() }}
        </div>
    </div>
</div>