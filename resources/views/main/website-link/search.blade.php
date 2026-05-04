<div>
    <table class="table table-bordered table-striped text-nowrap key-buttons">
        <thead>
            <tr>
                <th class="border-bottom-0">Created Date</th>
                <th class="border-bottom-0">Website</th>
                <th class="border-bottom-0">Status</th>
                <th class="border-bottom-0">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($link as $key => $value)
                <tr>
                    <td>{{\Carbon\Carbon::parse($value->created_at)->format('M, d Y')}}</td>
                    <td>
                        <a href="{{$value->link}}">{{$value->name}}</a>
                    </td>
                    <td>
                        @if($value->status == 1)
                        <span class="badge badge-success text-light">Active</span>
                        @else
                        <span class="badge badge-danger">Not active</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#staticBackdrop" onclick="update({{$value->id}})"><i class="fa fa-pencil-square" aria-hidden="true"></i> Update</button>
                            @if($value->status == 1)
                            <a href="{{url('/website-links/destroy/'.$value->id)}}" class="btn btn-outline-danger"><i class="fa fa-trash" aria-hidden="true"></i> Unactive</a>
                            @else
                            <a href="{{url('/website-links/destroy/'.$value->id)}}" class="btn btn-outline-success"><i class="fa fa-universal-access" aria-hidden="true"></i> Active</a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-between">
    <div class="text-secondary my-auto">
        Showing {{ $link->firstItem() ?? 0 }} to {{ $link->lastItem() ?? 0 }} from total {{$link->total()}} entries
    </div>
    <div>
        {{$link->links()}}
    </div>
</div>