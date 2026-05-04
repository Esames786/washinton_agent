
<div class="row">
    <div class="col-sm-12 mt-3">
        <table id="myTable" class="table table-bordered table-striped key-buttons">
            <thead>
                <tr>
                    <th scope="col">Sr.#</th>
                    <th scope="col">User</th>
                    <th scope="col">Logout Time</th>
                    <th scope="col">Status</th>
                    <th scope="col">Verified</th>
                    @if(in_array("115", $phoneaccess))
                    <th scope="col">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($groupedData as $key => $group)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $group['user']->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($group['created_at'])->format('M d, Y h:i A') }}</td>
                        <td>
                            <span class="badge mt-2 text-light {{ $group['status'] == 'Negative' ? 'badge-danger' : 'badge-success' }}">
                                {{ $group['status'] }}
                            </span>
                        </td>
                        <td>
                            <span class="badge mt-2 text-light badge-secondary">
                                @if ($group['status'] == '')
                                    UnApproved
                                @else
                                    Approved
                                @endif
                            </span>
                        </td>
                        @if(in_array("118", $phoneaccess))
                        <td>
                            <a class="btn btn-primary btn-sm getAllAnswers" data-user-id="{{ $group['user']->id }}" data-created_at="{{ $group['created_at'] }}">View</a>
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
