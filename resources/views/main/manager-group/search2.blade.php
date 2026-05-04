<div>
    <table class="table table-bordered table-striped text-nowrap key-buttons">
        <thead>
            <tr>
                <th class="border-bottom-0">Created Date</th>
                <th class="border-bottom-0">Member Name</th>
                <th class="border-bottom-0">Member Email</th>
                <th class="border-bottom-0">Member Number</th>
                <th class="border-bottom-0">Status</th>
                <th class="border-bottom-0 w-250">Daily Qoute</th>
                <th class="border-bottom-0">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user as $key => $value)
                <tr>
                    <td>{{\Carbon\Carbon::parse($value->user->created_at)->format('M, d Y')}}</td>
                    <td>
                        <span class="rounded-circle badge-{{$value->user->is_login == 1 ? 'success' : 'danger'}} my-auto mr-1" style="padding:1px 10.5px;"></span>
                         <span class="text-capitalize">{{$value->user->slug}} ({{$value->user->userRole->name}})</span>
                    </td>
                    <td>{{$value->user->email}}</td>
                    <td>{{$value->user->phone}}</td>
                    <td>
                        <span class="badge badge-{{$value->user->status == "1" ? 'success' : 'danger'}} text-light">{{$value->user->status == "1" ? 'Active' : 'Not Active'}}</span>
                        @if($value->user->freeze == 1)
                            <br>
                            <br>
                            <span class="badge badge-danger text-light">Freezed</span>
                            <br>
                            {{$value->user->freeze_reason}}
                        @endif
                    </td>
                    <td>
                        <form action="{{ url('/assign_daily_qoutes/'.$value->user->id) }}" method="POST" class="m-auto position-relative">
                            @csrf
                            <input type="text" name="assign_daily_qoute" maxlength="2" value="{{$value->user->assign_daily_qoute}}" />
                            <button type="submit" class="btn badge-warning savebtn">Save</button>
                        </form>
                    </td>
                    <td>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#activeCalling" onclick="userId({{$value->user->id}},{{$value->calling_status}})">Active/Deactive</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-between">
    <div class="text-secondary my-auto">
        Showing {{ $user->firstItem() ?? 0 }} to {{ $user->lastItem() ?? 0 }} from total {{$user->total()}} entries
    </div>
    <div>
        {{$user->links()}}
    </div>
</div>