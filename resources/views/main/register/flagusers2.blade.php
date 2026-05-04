<div id="Flag" class="tabcontent">
    <table class="table table-bordered table-striped key-buttons">
        <thead>
            <tr>
                <th class="border-bottom-0">S/No.</th>
                <th class="border-bottom-0">Name</th>
                <th class="border-bottom-0">Role</th>
                <th class="border-bottom-0">Message</th>
                <th class="border-bottom-0">Flags</th>
                <th class="border-bottom-0">Reasons</th>
                {{-- <th class="border-bottom-0">Action</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($flag as $key => $val)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td title="{{ $val->is_login == 1 ? 'Online' : 'Offline' }}">
                        <span class="chat-time dot-label bg-{{ $val->is_login == 1 ? 'success' : 'danger' }}"></span>
                        {{ $val->slug ?? $val->name . ' ' . $val->last_name }}
                    </td>
                    <td>{{ $val->userRole->name }}</td>
                    <td>
                        @if ($val->flag_count < 1)
                            No Message1
                        @elseif(isset($val->flag))
                            @foreach ($val->flag as $key2 => $value2)
                                @if (isset($value2->customChat))
                                    {{ $key2 + 1 }}
                                    {{ $value2->customChat->description }}
                                    <br>
                                @elseif (isset($value2->groupChat))
                                    {{ $key2 + 1 }}
                                    {{ $value2->groupChat->message }}
                                    <br>
                                @else
                                    No Message2ss
                                    <br>
                                @endif
                            @endforeach
                        @else
                            No Message3
                        @endif
                    </td>
                    <td>
                        @if ($val->flag_count > 1)
                            <span class="badge badge-danger">{{ $val->flag_count }}
                                Flags </span>
                        @else
                            <span class="badge badge-danger">{{ $val->flag_count }}
                                Flag</span>
                        @endif
                    </td>
                    <td>
                        @if ($val->flag_count < 1)
                            No Reason
                        @elseif(isset($val->flag))
                            @foreach ($val->flag as $key2 => $value2)
                                @if (isset($value2->reason))
                                    {{ $key2 + 1 }})
                                    {{ $value2->reason }}
                                    <br>
                                @else
                                    No Reason
                                    <br>
                                @endif
                            @endforeach
                        @else
                            No Reason
                        @endif
                    </td>
                    {{-- <td>
                        @if ($val->flag_count > 0)
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#removeFlag"
                                title="Remove Flag" onclick="removeFlag({{ $val->id }})">
                                <i class="fa fa-universal-access" aria-hidden="true"></i>
                            </button>
                        @endif
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#flagRed"
                            onclick="redFlag({{ $val->id }})" title="Red Flag">
                            <i class="fa fa-flag-o" aria-hidden="true"></i>
                        </button>
                    </td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- <div class="d-flex justify-content-between">
        <span class="my-auto">Showing {{ $flag->firstItem() }} to
            {{ $flag->lastItem() }} of {{ count($flag) }} entries from
            total {{ $flag->total() }}</span>
        {{ $flag->links() }}
    </div> --}}
</div>
