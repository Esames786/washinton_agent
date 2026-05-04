<div>
    <table class="table table-bordered table-striped text-nowrap key-buttons">
        <thead>
            <tr>
                <th class="border-bottom-0">Created Date</th>
                <th class="border-bottom-0">Manager Name</th>
                <th class="border-bottom-0">Manager Email</th>
                <th class="border-bottom-0">Manager Number</th>
                <th class="border-bottom-0">Total Group Member</th>
                <th class="border-bottom-0">Status</th>
                <th class="border-bottom-0">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($manager as $key => $value)
                <tr>
                    <td>{{\Carbon\Carbon::parse($value->created_at)->format('M, d Y')}}</td>
                    <td>
                        <span class="rounded-circle badge-{{$value->is_login == 1 ? 'success' : 'danger'}} my-auto mr-1" style="padding:1px 10.5px;"></span>
                         <span class="text-capitalize">{{$value->slug}}</span>
                    </td>
                    <td>{{$value->email}}</td>
                    <td>{{$value->phone}}</td>
                    <td>{{$value->manager_ot_count > 1 ? $value->manager_ot_count.' Members' : $value->manager_ot_count.' Memeber'}}</td>
                    <td>
                        <span class="badge badge-{{$value->status == "1" ? 'success' : 'danger'}} text-light">{{$value->status == "1" ? 'Active' : 'Not Active'}}</span>
                        @if($value->freeze == 1)
                            <br>
                            <br>
                            <span class="badge badge-danger text-light">Freezed</span>
                            <br>
                            {{$value->freeze_reason}}
                        @endif
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ url('/managers-group/'.$value->id) }}" class="btn btn-warning">Group Members</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-between">
    <div class="text-secondary my-auto">
        Showing {{ $manager->firstItem() ?? 0 }} to {{ $manager->lastItem() ?? 0 }} from total {{$manager->total()}} entries
    </div>
    <div>
        {{$manager->links()}}
    </div>
</div>