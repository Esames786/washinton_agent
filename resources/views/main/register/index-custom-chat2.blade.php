<div id="Custom_Chat" class="tabcontent"
    style="@if ($heading == 'Custom Chat') display:block; @else display:none; @endif">
    <table class="table table-bordered table-striped key-buttons">
        <thead>
            <tr>
                <th class="border-bottom-0">S/No.</th>
                <th class="border-bottom-0">Sender</th>
                <th class="border-bottom-0">To User</th>
                <th class="border-bottom-0">Message</th>
                <th class="border-bottom-0">Date Time</th>
                <th class="border-bottom-0">Status</th>
                <th class="border-bottom-0">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($chat as $key => $val)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    {{-- <td>{{ $val->touserId }}</td> --}}
                    <td
                        title="@if (isset($val->sender->is_login)) {{ $val->sender->is_login == 1 ? 'Online' : 'Offline' }} @endif">
                        @if (isset($val->sender->id))
                            <span
                                class="chat-time dot-label bg-{{ $val->sender->is_login == 1 ? 'success' : 'danger' }}"></span>
                            {{ $val->sender->slug ?? $val->sender->name . ' ' . $val->sender->last_name }}
                        @endif
                    </td>
                    <td
                        title="@if (isset($val->receiver->is_login)) {{ $val->receiver->is_login == 1 ? 'Online' : 'Offline' }} @endif">
                        @if (isset($val->receiver->id))
                            <span
                                class="chat-time dot-label bg-{{ $val->receiver->is_login == 1 ? 'success' : 'danger' }}"></span>
                            {{ $val->receiver->slug ?? $val->receiver->name . ' ' . $val->receiver->last_name }}
                        @endif
                    </td>
                    <td>{{ $val->description }}</td>
                    <td>
                        {{ $val->created_at }}
                    </td>
                    <td>
                        @if ($val->status == 0)
                            <span class="badge badge-danger">Pending</span>
                        @elseif($val->status == 1)
                            <span class="badge badge-info">Approved</span>
                        @else
                            <span class="badge badge-success text-light">Seen</span>
                        @endif
                        @if (isset($val->flag))
                            @if ($val->flag->user_id == $val->sender->id)
                                <br><br>
                                <span class="badge badge-danger"><i class="fa fa-flag-o" aria-hidden="true"></i>
                                    Flag</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if ($val->status == 0)
                            @if (!isset($val->flag))
                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                    data-target="#flagChat"
                                    onclick="putFlagChatId({{ $val->id }}, {{ $val->fromuserId }})"
                                    title="Red Flag">
                                    <i class="fa fa-flag-o" aria-hidden="true"></i>
                                </button>
                            @else
                                @if ($val->flag->user_id != $val->sender->id)
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#flagChat"
                                        onclick="putFlagChatId({{ $val->id }}, {{ $val->fromuserId }})"
                                        title="Red Flag">
                                        <i class="fa fa-flag-o" aria-hidden="true"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#removeflagChat" title="Remove Flag"
                                        onclick="removeflagChat({{ $val->id }})">
                                        <i class="fa fa-universal-access" aria-hidden="true"></i>
                                    </button>
                                @endif
                            @endif
                            <button type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#approveChat" onclick="putChatId({{ $val->id }})"
                                title="Approve Message">
                                <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <span class="my-auto">Showing {{ $chat->firstItem() }} to
            {{ $chat->lastItem() }} of {{ count($chat) }} entries from
            total {{ $chat->total() }}</span>
        {{ $chat->links() }}
    </div>
</div>
{{-- <div id="Public_Chat" class="tabcontent"
    style="@if ($heading == 'Public Chat') display:block; @else display:none; @endif">
    <table class="table table-bordered table-striped key-buttons">
        <thead>
            <tr>
                <th class="border-bottom-0">S/No.</th>
                <th class="border-bottom-0">Order Id#</th>
                <th class="border-bottom-0">Sender</th>
                <th class="border-bottom-0">Message</th>
                <th class="border-bottom-0">Members</th>
                <th class="border-bottom-0">Date Time</th>
                <th class="border-bottom-0">Status</th>
                <th class="border-bottom-0">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($public as $key => $val)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->order_id }}</td>
                    <td title="{{ $val->user->is_login == 1 ? 'Online' : 'Offline' }}">
                        <span
                            class="chat-time dot-label bg-{{ $val->user->is_login == 1 ? 'success' : 'danger' }}"></span>
                        {{ $val->user->slug ?? $val->user->name . ' ' . $val->user->last_name }}
                    </td>
                    <td>{{ $val->message }}</td>
                    <td>
                        {{ $val->member }} Members
                        <br>
                        Seen By {{ $val->seen_by }} Members
                    </td>
                    <td>
                        {{ $val->message_date }}
                        <br>
                        {{ $val->message_time }}
                    </td>
                    <td>
                        @if ($val->status == 0)
                            <span class="badge badge-danger">Pending</span>
                        @elseif($val->status == 1)
                            <span class="badge badge-info">Approved</span>
                        @else
                            <span class="badge badge-success text-light">Seen</span>
                        @endif
                        @if (isset($val->flag))
                            @if ($val->flag->user_id == $val->user->id)
                                <br><br>
                                <span class="badge badge-danger"><i class="fa fa-flag-o" aria-hidden="true"></i>
                                    Flag</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if ($val->status == 0)
                            @if (!isset($val->flag))
                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                    data-target="#flagPublicChat" onclick="putFlagPublicId({{ $val->id }})"
                                    title="Red Flag">
                                    <i class="fa fa-flag-o" aria-hidden="true"></i>
                                </button>
                            @else
                                @if ($val->flag->user_id != $val->user->id)
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#flagPublicChat" onclick="putFlagPublicId({{ $val->id }})"
                                        title="Red Flag">
                                        <i class="fa fa-flag-o" aria-hidden="true"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#removeflagChatPublic" title="Remove Flag"
                                        onclick="removeflagChatPublic({{ $val->id }})">
                                        <i class="fa fa-universal-access" aria-hidden="true"></i>
                                    </button>
                                @endif
                            @endif
                            <button type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#approvePublic" onclick="putPublicId({{ $val->id }})"
                                title="Approve Message">
                                <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <span class="my-auto">Showing {{ $public->firstItem() }} to {{ $public->lastItem() }} of
            {{ count($public) }} entries from total {{ $public->total() }}</span>
        {{ $public->links() }}
    </div>
</div> --}}
<div id="Group_Chat" class="tabcontent"
    style="@if ($heading == 'Group Chat') display:block; @else display:none; @endif">
    <table class="table table-bordered table-striped key-buttons">
        <thead>
            <tr>
                <th class="border-bottom-0">S/No.</th>
                {{-- <th class="border-bottom-0">Order Id#</th> --}}
                <th class="border-bottom-0">Sender</th>
                <th class="border-bottom-0">Message</th>
                <th class="border-bottom-0">Members</th>
                <th class="border-bottom-0">Date Time</th>
                <th class="border-bottom-0">Status</th>
                <th class="border-bottom-0">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($group as $key => $val)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    {{-- <td>{{ $val->order_id }}</td> --}}
                    <td title="{{ $val->user->is_login == 1 ? 'Online' : 'Offline' }}">
                        <span
                            class="chat-time dot-label bg-{{ $val->user->is_login == 1 ? 'success' : 'danger' }}"></span>
                        {{ $val->user->slug ?? $val->user->name . ' ' . $val->user->last_name }}
                    </td>
                    <td>{{ $val->message }}</td>
                    <td>
                        {{ $val->member }} Members
                        <br>
                        Seen By {{ $val->seen_by }} Members
                    </td>
                    <td>
                        {{ $val->created_at }}
                    </td>
                    <td>
                        @if ($val->status == 0)
                            <span class="badge badge-danger">Pending</span>
                        @elseif($val->status == 1)
                            <span class="badge badge-info">Approved</span>
                        @else
                            <span class="badge badge-success text-light">Seen</span>
                        @endif
                        @if (isset($val->flag))
                            @if ($val->flag->user_id == $val->user->id)
                                <br><br>
                                <span class="badge badge-danger"><i class="fa fa-flag-o" aria-hidden="true"></i>
                                    Flag</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if ($val->status == 0)
                            @if (!isset($val->flag))
                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                    data-target="#flagPublicChat" onclick="putFlagPublicId({{ $val->id }})"
                                    title="Red Flag">
                                    <i class="fa fa-flag-o" aria-hidden="true"></i>
                                </button>
                            @else
                                @if ($val->flag->user_id != $val->user->id)
                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                        data-target="#flagPublicChat" onclick="putFlagPublicId({{ $val->id }})"
                                        title="Red Flag">
                                        <i class="fa fa-flag-o" aria-hidden="true"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#removeflagChatPublic" title="Remove Flag"
                                        onclick="removeflagChatPublic({{ $val->id }})">
                                        <i class="fa fa-universal-access" aria-hidden="true"></i>
                                    </button>
                                @endif
                            @endif
                            <button type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#approvePublic" onclick="putPublicId({{ $val->id }})"
                                title="Approve Message">
                                <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <span class="my-auto">Showing {{ $group->firstItem() }} to {{ $group->lastItem() }} of
            {{ count($group) }} entries from total {{ $group->total() }}</span>
        {{ $group->links() }}
    </div>
</div>
<div id="Users" class="tabcontent"
    style="@if ($heading == 'Freeze Users') display:block; @else display:none; @endif">
    <table class="table table-bordered table-striped key-buttons">
        <thead>
            <tr>
                <th class="border-bottom-0">S/No.</th>
                <th class="border-bottom-0">Name</th>
                <th class="border-bottom-0">Role</th>
                <th class="border-bottom-0">Phone</th>
                <th class="border-bottom-0">Status</th>
                <th class="border-bottom-0">Reason</th>
                <th class="border-bottom-0">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $key => $val)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td title="{{ $val->is_login == 1 ? 'Online' : 'Offline' }}">
                        <span class="chat-time dot-label bg-{{ $val->is_login == 1 ? 'success' : 'danger' }}"></span>
                        {{ $val->slug ?? $val->name . ' ' . $val->last_name }}
                    </td>
                    <td>{{ $val->userRole->name }}</td>
                    <td>
                        @if (isset($val->phone))
                            <span class="text-center pd-2 bd-l">
                                <a href="#" class="btn btn-outline-info"
                                    style="padding: 3px 5px; font-size: 20px;">
                                    <i class="fa fa-phone"></i>
                                    <span class="">{{ $val->phone }}</span>
                                </a><br>
                            </span>
                        @endif
                    </td>
                    <td>
                        @if ($val->freeze == 0)
                            <span class="badge badge-success text-light">Active</span>
                        @else
                            <span class="badge badge-danger">Freeze</span>
                        @endif
                    </td>
                    <td>
                        @if ($val->freeze == 1)
                            @if ($val->freeze_reason)
                                {{ $val->freeze_reason }}
                            @else
                                No Reason
                            @endif
                        @else
                            No Reason
                        @endif
                    </td>
                    <td>
                        @if ($val->freeze == 0)
                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                data-target="#freezeAccount" title="Freeze Account"
                                onclick="freezeAcc({{ $val->id }},1)">
                                <i class="fa fa-ban" aria-hidden="true"></i>
                            </button>
                        @else
                            <button type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#activeAccount" title="Active Account"
                                onclick="activeAcc({{ $val->id }},0)">
                                <i class="fa fa-universal-access" aria-hidden="true"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <span class="my-auto">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ count($users) }}
            entries from total {{ $users->total() }}</span>
        {{ $users->links() }}
    </div>
</div>
<div id="Flag" class="tabcontent"
    style="@if ($heading == 'Flag Users') display:block; @else display:none; @endif">
    <table class="table table-bordered table-striped key-buttons">
        <thead>
            <tr>
                <th class="border-bottom-0">S/No.</th>
                <th class="border-bottom-0">Name</th>
                <th class="border-bottom-0">Role</th>
                <th class="border-bottom-0">Phone</th>
                <th class="border-bottom-0">Flags</th>
                <th class="border-bottom-0">Reasons</th>
                <th class="border-bottom-0">Action</th>
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
                        @if (isset($val->phone))
                            <span class="text-center pd-2 bd-l">
                                <a href="#" class="btn btn-outline-info"
                                    style="padding: 3px 5px; font-size: 20px;">
                                    <i class="fa fa-phone"></i>
                                    <span class="">{{ $val->phone }}</span>
                                </a><br>
                            </span>
                        @endif
                    </td>
                    <td>
                        @if ($val->flag_count > 1)
                            <span class="badge badge-danger">{{ $val->flag_count }} Flags </span>
                        @else
                            <span class="badge badge-danger">{{ $val->flag_count }} Flag</span>
                        @endif
                    </td>
                    <td>
                        @if ($val->flag_count < 1)
                            No Reason
                        @elseif(isset($val->flag))
                            @foreach ($val->flag as $key2 => $value2)
                                @if (isset($value2->reason))
                                    {{ $key2 + 1 }}) {{ $value2->reason }}
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
                    <td>
                        @if ($val->flag_count > 0)
                            <button type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#removeFlag" title="Remove Flag"
                                onclick="removeFlag({{ $val->id }})">
                                <i class="fa fa-universal-access" aria-hidden="true"></i>
                            </button>
                        @endif
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#flagRed"
                            onclick="redFlag({{ $val->id }})" title="Red Flag">
                            <i class="fa fa-flag-o" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <span class="my-auto">Showing {{ $flag->firstItem() }} to {{ $flag->lastItem() }} of {{ count($flag) }}
            entries from total {{ $flag->total() }}</span>
        {{ $flag->links() }}
    </div>
</div>
