<table class="table table-bordered table-sm col-lg-2 fs-18 text-center pd-2 bd-l" role="grid" aria-describedby="">
    <thead class="table-dark">
        <tr>
            <th>Sr. #</th>
            <th>Request User</th>
            <th>Phone</th>
            <th>Description</th>
            <th>Status</th>
            <th>Approved By</th>
            <th>Created At</th>
            @if (Auth::user()->userRole->name == 'Admin')
                <th>Action</th>
            @endif
        </tr>
    </thead>
    <tbody id="usedAndNewTableBody">
        @foreach ($block_phones as $key => $val)
            <tr class="parent1{{ $key }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $val->user->name }}</td>
                <td>{{ $val->phone }}</td>
                <td>{{ $val->description }}</td>
                <td id="status-{{ $val->id }}">
                    {{ $val->status == 1 ? 'Approved' : 'Not Approved' }}
                </td>
                <td>
                    @if($val->approved_by)
                        {{ $val->approved_by->name }}
                    @else
                        {{ $val->approver }}
                    @endif
                </td>
                <td>{{ $val->created_at }}</td>
                @if (in_array('125', $phoneaccess) || Auth::user()->userRole->name == 'Admin')
                    <td id="action-{{ $val->id }}">
                        @if ($val->status == 0)
                            <button type="button" class="btn btn-success approve-btn" data-id="{{ $val->id }}">
                                Approve
                            </button>
                        @else
                            <button type="button" class="btn btn-danger disapprove-btn" data-id="{{ $val->id }}">
                                Disapprove
                            </button>
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
