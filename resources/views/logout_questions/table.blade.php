<div class="row">
    <div class="col-sm-12 mt-3">
        <table id="myTable" class="table table-bordered table-striped key-buttons">
            <thead>
                <tr>
                    <th scope="col">Sr.#</th>
                    <th scope="col">Question</th>
                    <th scope="col">Role</th>
                    <th scope="col">Status</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $key => $question)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $question->question }}</td>
                    <td>{{ $question->user_role->name }}</td>
                    <td>
                        @if($question->status == 1)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $question->created_at->format('M d, Y h:i A') }}</td>
                    <td>
                        <a href="{{ route('logout_questions.edit', $question->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('logout_questions.destroy', $question->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this question?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
