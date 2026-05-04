<?php
function get_unread_chat($fromuserId)
{
    $data = DB::table('chats')
        ->where('fromuserId', $fromuserId)
        ->where('touserId', Auth::user()->id)
        ->where('chat_view', 0)
        ->where('created_at', '>=', \Carbon\Carbon::today()->subDays(2))
        ->count();
    return $data;
}
?>

<div class="tab-pane @if ($chatText == 'Chat') active @endif" id="tab-7"
    style="height: 100%;overflow: scroll;">
    <!-- Search Input Field -->
    <div class="search-bar mb-3">
        <input type="text" id="userSearch" class="form-control" placeholder="Search Users..." />
    </div>

    <ul class="list-group lg-alt chat-conatct-list" id="ChatList">
        @foreach ($data as $row)
            <li class="list-group-item media p-4 mt-0 border-0" onclick="readMsgs({{ $row->id }},this)"
                style="cursor: pointer">
                <a href="javascript:void(0)">
                    <div class="media-body">
                        <div class="list-group-item-heading text-default font-weight-semibold">
                            {{ $row->slug ? $row->slug : $row->name . ' ' . $row->last_name }}<span
                                class="dot-label bg-success ml-2 w-4 h-4"> {{ get_unread_chat($row->id) }} </span>
                        </div>
                        <small class="list-group-item-text text-muted">{{ $row->email }}</small>
                    </div>
                    <span
                        class="chat-time {{ $row->is_login == 1 ? 'text-success' : 'text-muted' }}">{{ $row->is_login == 1 ? 'Online' : $row->updated_at->diffForHumans() }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>

<div class="tab-pane @if ($chatText == 'Group') active @endif" id="tab-9"
    style="height: 100%;overflow: scroll;">
    <!-- Search Input Field -->
    <div class="search-bar mb-3">
        <input type="text" id="groupSearch" class="form-control" placeholder="Search Groups..." />
    </div>

    <ul class="list-group lg-alt chat-conatct-list" id="ChatList2">
        @foreach ($group as $row)
            <li class="list-group-item media p-4 mt-0 border-0" onclick="groupChat({{ $row->id }},this)"
                style="cursor: pointer">
                <a href="javascript:void(0)">
                    <div class="media-body">
                        <div class="list-group-item-heading text-default font-weight-semibold">
                            {{ $row->name }}<span class="dot-label bg-success ml-2 w-4 h-4"> {{ $row->count }}
                            </span>
                        </div>
                        <small class="list-group-item-text text-muted">
                            @if (isset($row->chatOne->message))
                                @if ($row->chatOne->user->slug)
                                    {{ $row->chatOne->user->slug }}
                                @else
                                    {{ $row->chatOne->user->name . ' ' . $row->chatOne->user->last_name }}
                                @endif
                                :
                                @if ($row->chatOne->type == 'image')
                                    <i class="fa fa-picture-o" aria-hidden="true"></i> Image
                                @elseif($row->chatOne->type == 'file')
                                    <i class="fa fa-link" aria-hidden="true"></i> File
                                @else
                                    {{ \Illuminate\Support\Str::words($row->chatOne->message, 5) }}
                                @endif
                            @endif
                        </small>
                    </div>
                    <span
                        class="chat-time text-muted">{{ isset($row->chatOne->message) ? $row->chatOne->created_at->diffForHumans() : '' }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>

<script>
    $(document).ready(function() {
        // Search functionality for users
        $('#userSearch').on('keyup', function() {
            var searchValue = $(this).val().toLowerCase(); // Get the search input value
            $('#ChatList li').each(function() {
                var userName = $(this).find('.list-group-item-heading').text()
                    .toLowerCase(); // Get the name text
                if (userName.indexOf(searchValue) !== -1) {
                    $(this).show(); // Show the user
                } else {
                    $(this).hide(); // Hide the user
                }
            });
        });

        // Search functionality for groups
        $('#groupSearch').on('keyup', function() {
            var searchValue = $(this).val().toLowerCase(); // Get the search input value
            $('#ChatList2 li').each(function() {
                var groupName = $(this).find('.list-group-item-heading').text()
                    .toLowerCase(); // Get the group name text
                if (groupName.indexOf(searchValue) !== -1) {
                    $(this).show(); // Show the group
                } else {
                    $(this).hide(); // Hide the group
                }
            });
        });
    });
</script>
