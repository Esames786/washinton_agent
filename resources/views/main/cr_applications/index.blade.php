@extends('layouts.innerpages')

@section('template_title', 'CrazyRays Applications')

@include('partials.mainsite_pages.return_function')

@section('content')
<div class="container-fluid">

    <div class="row mb-3 align-items-center">
        <div class="col-sm-6">
            <h4 class="mb-0"><i class="fas fa-user-plus mr-2" style="color:#d4af37;"></i> CrazyRays Applications</h4>
        </div>
        <div class="col-sm-6 text-right">
            <span class="badge badge-warning px-3 py-2">Permission: CrazyRays Applications (166)</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>
    @endif

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('cr-applications.index') }}" class="form-inline flex-wrap" style="gap:8px;">
                <select name="campaign" class="form-control form-control-sm">
                    <option value="">All Campaigns</option>
                    @foreach($campaigns as $slug => $label)
                        <option value="{{ $slug }}" @selected(request('campaign') === $slug)>{{ $label }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-control form-control-sm">
                    <option value="">All Statuses</option>
                    <option value="pending"  @selected(request('status') === 'pending')>Pending</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                </select>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Name / Email / Phone" value="{{ request('search') }}" style="min-width:200px;">
                {{-- #9: filter by application submitted date --}}
                <label class="mb-0 small text-muted">From</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" title="Applied from">
                <label class="mb-0 small text-muted">To</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" title="Applied to">
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                <a href="{{ route('cr-applications.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                {{-- #15 (2026-07-03): show application count for the selected campaign/status filter --}}
                <span class="badge badge-dark px-3 py-2 ml-2" style="font-size:13px;">
                    Total: {{ $applications->total() }}
                </span>
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Campaign</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Applied</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                        <tr>
                            <td>{{ $app->id }}</td>
                            <td>
                                <span class="badge" style="background:#d4af37;color:#111;">{{ $app->campaign_label }}</span>
                            </td>
                            <td><strong>{{ $app->full_name }}</strong></td>
                            <td>{{ $app->email }}</td>
                            <td>{{ $app->phone }}</td>
                            <td>{{ $app->created_at->format('d M Y') }}</td>
                            <td>
                                @if($app->status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($app->status === 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @else
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('cr-applications.show', $app->id) }}" class="btn btn-xs btn-info mr-1">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                @if($app->isPending())
                                    <form method="POST" action="{{ route('cr-applications.approve', $app->id) }}" class="d-inline" onsubmit="return confirm('Convert this application to a system user?');">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-success mr-1">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-xs btn-danger" onclick="openRejectModal({{ $app->id }}, '{{ addslashes($app->full_name) }}')">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No applications found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($applications->hasPages())
        <div class="card-footer">
            {{ $applications->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Application</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Rejecting application for: <strong id="rejectName"></strong></p>
                    <div class="form-group">
                        <label>Rejection Note <small class="text-muted">(optional — will be sent to applicant)</small></label>
                        <textarea name="rejection_note" class="form-control" rows="3" placeholder="Reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('extraScript')
<script>
function openRejectModal(id, name) {
    document.getElementById('rejectName').textContent = name;
    document.getElementById('rejectForm').action = '/cr-applications/' + id + '/reject';
    $('#rejectModal').modal('show');
}
</script>
@endsection
