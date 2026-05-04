@extends('layouts.innerpages')

@section('template_title')
    cPanel Email Accounts
@endsection

@include('partials.mainsite_pages.return_function')

@section('content')
<div class="page-header">
    <div class="text-secondary text-center text-uppercase w-100">
        <h1 class="my-4"><b>cPanel Email Accounts</b></h1>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if(session('msg'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('msg') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if(session('err'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('err') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Email Accounts</h5>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal">
                    <i class="fa fa-plus mr-1"></i> Create Account
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>Assigned User</th>
                            <th>IMAP</th>
                            <th>SMTP</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($accounts as $acc)
                            <tr>
                                <td>{{ $acc->id }}</td>
                                <td>
                                    <div class="font-weight-bold">{{ $acc->email }}</div>
                                    <small class="text-muted">{{ $acc->domain }}</small>
                                </td>
                                <td>
                                    @if($acc->user)
                                        <div class="font-weight-bold">{{ $acc->user->name }}</div>
                                        <small class="text-muted">{{ $acc->user->email }}</small>
                                    @else
                                        <span class="text-muted">Not Assigned</span>
                                    @endif
                                </td>
                                <td class="small">{{ $acc->imap_host }}:{{ $acc->imap_port }} ({{ $acc->imap_encryption ?: 'none' }})</td>
                                <td class="small">{{ $acc->smtp_host }}:{{ $acc->smtp_port }} ({{ $acc->smtp_encryption ?: 'none' }})</td>
                                <td>
                                    @if($acc->status === 'active')
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center flex-wrap" style="gap:6px;">
                                        <button class="btn btn-info btn-sm"
                                                data-toggle="modal" data-target="#assignModal"
                                                data-id="{{ $acc->id }}"
                                                data-user="{{ $acc->user_id }}"
                                                data-status="{{ $acc->status }}">
                                            Assign / Edit
                                        </button>
                                        <button class="btn btn-warning btn-sm"
                                                data-toggle="modal" data-target="#resetPassModal"
                                                data-id="{{ $acc->id }}">
                                            Reset Password
                                        </button>
                                        <form method="POST" action="{{ route('cpanel.email.toggle', $acc->id) }}" class="d-inline">
                                            @csrf
                                            <button class="btn btn-secondary btn-sm" type="submit">Toggle</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-muted">No email accounts found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $accounts->links() }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Create Modal --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('cpanel.email.store') }}" id="createForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Create Email Account</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    @php $fixedDomain = config('mailbox.default_domain', 'washinton.com'); @endphp

                    <div class="form-group">
                        <label class="font-weight-bold">Email</label>
                        <div class="input-group">
                            <input type="text" id="local_part" name="local_part" class="form-control"
                                   placeholder="username" required autocomplete="off"
                                   pattern="^[a-zA-Z0-9][a-zA-Z0-9._-]{0,62}$">
                            <div class="input-group-append">
                                <span class="input-group-text">{{ '@' . $fixedDomain }}</span>
                            </div>
                        </div>
                        <small class="text-muted" id="emailPreview">Will become <strong>xxxx@{{ $fixedDomain }}</strong></small>
                    </div>

                    <input type="hidden" name="domain" value="{{ $fixedDomain }}">

                    <div class="form-group">
                        <label class="font-weight-bold">Password</label>
                        <input type="text" name="password" class="form-control" placeholder="Mailbox password" required minlength="6">
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Assign to User (optional)</label>
                        <select name="user_id" class="form-control">
                            <option value="">-- Not Assigned --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Quota (MB)</label>
                                <input type="number" name="quota_mb" class="form-control" placeholder="1024" min="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-dark small mb-0">
                        <strong>Mail Server:</strong><br>
                        IMAP: {{ config('mailbox.imap_host') }}:{{ config('mailbox.imap_port') }} ({{ config('mailbox.imap_encryption') }})<br>
                        SMTP: {{ config('mailbox.smtp_host') }}:{{ config('mailbox.smtp_port') }} ({{ config('mailbox.smtp_encryption') }})
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="createSubmitBtn">Create</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Assign/Edit Modal --}}
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="assignForm" action="#">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Assign / Edit</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Assign to User</label>
                        <select name="user_id" class="form-control">
                            <option value="">-- Not Assigned --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Reset Password Modal --}}
<div class="modal fade" id="resetPassModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="resetPassForm" action="#">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">New Password</label>
                        <input type="text" name="password" class="form-control" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fixedDomain = @json(config('mailbox.default_domain', 'washinton.com'));
    const localInput  = document.getElementById('local_part');
    const preview     = document.getElementById('emailPreview');

    localInput.addEventListener('input', function () {
        const v = (this.value || '').trim();
        preview.innerHTML = 'Will become <strong>' + (v || 'xxxx') + '@' + fixedDomain + '</strong>';
    });

    // Assign modal
    $('#assignModal').on('show.bs.modal', function (e) {
        const btn    = $(e.relatedTarget);
        const id     = btn.data('id');
        const user   = btn.data('user');
        const status = btn.data('status');
        const form   = document.getElementById('assignForm');
        form.action  = '/cpanel-email/' + id + '/update';
        $(form).find('select[name="user_id"]').val(user || '');
        $(form).find('select[name="status"]').val(status || 'active');
    });

    // Reset password modal
    $('#resetPassModal').on('show.bs.modal', function (e) {
        const btn   = $(e.relatedTarget);
        const id    = btn.data('id');
        const form  = document.getElementById('resetPassForm');
        form.action = '/cpanel-email/' + id + '/reset-password';
    });
});
</script>
@endsection
