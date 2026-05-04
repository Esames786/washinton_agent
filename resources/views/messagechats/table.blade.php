<div id="table_data">
    <div class="table-responsive">
        <table class="table table-bordered table-sm" style="width:100%" id=""
            role="grid">
            <thead>
                <tr>
                    <th class="border-bottom-0">#</th>
                    <th class="border-bottom-0">User ID</th>
                    <th class="border-bottom-0">Order ID</th>
                    <th class="border-bottom-0">Message</th>
                    {{-- <th class="border-bottom-0">Status</th>
                    <th class="border-bottom-0">Message Date</th> --}}
                    <th class="border-bottom-0">Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($messageChats as $messageChat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $messageChat->user->name . ' ' . $messageChat->user->last_name }}
                        </td>
                        <td>{{ $messageChat->order_id . ' - ' . $messageChat->order->name }}</td>
                        <td>{{ $messageChat->message }}</td>
                        {{-- <td>{{ $messageChat->status }}</td>
                        <td>{{ $messageChat->message_date }}</td>
                        <td>
                            <div class="btn-group">
                                <a title="Show" class="btn btn-info" href="{{ route('messagechats.show', $messageChat->id) }}"><i class="fa fa-eye"></i></a>
                                <a title="Edit" class="btn btn-info"
                                    href="{{ route('messagechats.edit', $messageChat->id) }}"><i
                                        class="fa fa-edit"></i></a>
                                <button type="button" class="btn btn-danger deleteBtn"
                                    data-toggle="modal" data-target="#confirmationModal"
                                    data-action="{{ route('messagechats.destroy', $messageChat->id) }}"><i
                                        class="fa fa-trash"></i></button>

                            </div>
                        </td> --}}
                        <td>{{ $messageChat->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- <div class="d-flex justify-content-between">
            <div class="text-secondary my-auto">
                Showing {{ $messageChats->firstItem() }} to {{ $messageChats->lastItem() }} from total {{ $messageChats->total() }} entries
            </div>
            <div>
                {{ $messageChats->links() }}
            </div>
            
        </div> --}}
    </div>
</div>