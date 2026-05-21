@extends('layouts.innerpages')

@section('template_title', 'Application — ' . $application->full_name)

@section('content')
<div class="container-fluid">

    <div class="row mb-3 align-items-center">
        <div class="col-sm-6">
            <a href="{{ route('cr-applications.index') }}" class="btn btn-sm btn-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <strong>Application #{{ $application->id }}</strong>
        </div>
        <div class="col-sm-6 text-right">
            @if($application->isPending())
                <form method="POST" action="{{ route('cr-applications.approve', $application->id) }}" class="d-inline" onsubmit="return confirm('Convert this application to a system user?');">
                    @csrf
                    <button type="submit" class="btn btn-success mr-2">
                        <i class="fas fa-check"></i> Approve & Convert to User
                    </button>
                </form>
                <button type="button" class="btn btn-danger" onclick="openRejectModal({{ $application->id }}, '{{ addslashes($application->full_name) }}')">
                    <i class="fas fa-times"></i> Reject
                </button>
            @elseif($application->isApproved())
                <span class="badge badge-success px-3 py-2" style="font-size:14px;">✓ Approved — User #{{ $application->agent_id }}</span>
            @else
                <span class="badge badge-danger px-3 py-2" style="font-size:14px;">✗ Rejected</span>
            @endif
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        {{-- Left column --}}
        <div class="col-md-8">

            {{-- Campaign & Status --}}
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center" style="background:#111;color:#d4af37;">
                    <strong>Campaign</strong>
                    @if($application->status === 'pending')
                        <span class="badge badge-warning">Pending Review</span>
                    @elseif($application->status === 'approved')
                        <span class="badge badge-success">Approved</span>
                    @else
                        <span class="badge badge-danger">Rejected</span>
                    @endif
                </div>
                <div class="card-body">
                    <h4 style="color:#d4af37;">{{ $application->campaign_label }}</h4>
                    <small class="text-muted">Applied: {{ $application->created_at->format('d M Y, h:i A') }}</small>
                    @if($application->contract_accepted_at)
                        <br><small class="text-success"><i class="fas fa-check-circle"></i> T&C accepted: {{ $application->contract_accepted_at->format('d M Y, h:i A') }}</small>
                    @else
                        <br><small class="text-danger"><i class="fas fa-times-circle"></i> T&C not accepted</small>
                    @endif
                </div>
            </div>

            {{-- Personal Info --}}
            <div class="card mb-3">
                <div class="card-header"><strong>Personal Information</strong></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr><td class="text-muted" style="width:140px;">Full Name</td><td><strong>{{ $application->full_name }}</strong></td></tr>
                                <tr><td class="text-muted">Father's Name</td><td>{{ $application->father_name ?? '—' }}</td></tr>
                                <tr><td class="text-muted">National ID</td><td>{{ $application->national_id ?? '—' }}</td></tr>
                                <tr><td class="text-muted">Date of Birth</td><td>{{ $application->dob ? $application->dob->format('d M Y') : '—' }}</td></tr>
                                <tr><td class="text-muted">Gender</td><td>{{ ucfirst($application->gender ?? '—') }}</td></tr>
                                <tr><td class="text-muted">Marital Status</td><td>{{ ucfirst($application->marital_status ?? '—') }}</td></tr>
                            </table>
                        </div>
                        <div class="col-sm-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr><td class="text-muted" style="width:140px;">Email</td><td>{{ $application->email }}</td></tr>
                                <tr><td class="text-muted">Phone</td><td>{{ $application->phone }}</td></tr>
                                <tr><td class="text-muted">Country</td><td>{{ $application->country ?? '—' }}</td></tr>
                                <tr><td class="text-muted">City</td><td>{{ $application->city ?? '—' }}</td></tr>
                                <tr><td class="text-muted">State/Province</td><td>{{ $application->state ?? '—' }}</td></tr>
                                <tr><td class="text-muted">Address</td><td>{{ $application->address ?? '—' }}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Employment Info --}}
            <div class="card mb-3">
                <div class="card-header"><strong>Employment Information</strong></div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td class="text-muted" style="width:160px;">Shift Type</td><td>{{ $application->shift_type ?? '—' }}</td></tr>
                        <tr><td class="text-muted">Pay Type</td><td>{{ $application->pay_type ?? '—' }}</td></tr>
                    </table>
                </div>
            </div>

            {{-- Campaign Experience --}}
            @if($application->campaign_experience)
            <div class="card mb-3">
                <div class="card-header"><strong>Campaign Experience</strong></div>
                <div class="card-body">
                    <p class="mb-0" style="white-space:pre-wrap;">{{ $application->campaign_experience }}</p>
                </div>
            </div>
            @endif

            {{-- Additional Info --}}
            @if($application->additional_info)
            <div class="card mb-3">
                <div class="card-header"><strong>Additional Info</strong></div>
                <div class="card-body">
                    <p class="mb-0" style="white-space:pre-wrap;">{{ $application->additional_info }}</p>
                </div>
            </div>
            @endif

            {{-- Rejection note (if rejected) --}}
            @if($application->isRejected() && $application->rejection_note)
            <div class="card mb-3 border-danger">
                <div class="card-header bg-danger text-white"><strong>Rejection Note</strong></div>
                <div class="card-body">
                    <p class="mb-0">{{ $application->rejection_note }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Right column — files & docs --}}
        <div class="col-md-4">

            {{-- Resume --}}
            <div class="card mb-3">
                <div class="card-header"><strong>Resume</strong></div>
                <div class="card-body">
                    @if($application->resume_path)
                        <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-download mr-1"></i> Download Resume
                        </a>
                    @else
                        <p class="text-muted mb-0">No resume uploaded.</p>
                    @endif
                </div>
            </div>

            {{-- Documents --}}
            <div class="card mb-3">
                <div class="card-header"><strong>Submitted Documents</strong></div>
                <div class="card-body p-0">
                    @if($application->documents && count($application->documents))
                        <ul class="list-group list-group-flush">
                            @foreach($application->documents as $doc)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <span>
                                    {{ $doc['title'] ?? 'Document' }}
                                    @if(!empty($doc['is_required']))
                                        <span class="badge badge-danger ml-1">Required</span>
                                    @endif
                                </span>
                                <a href="{{ asset('storage/' . $doc['path']) }}" target="_blank" class="btn btn-xs btn-outline-secondary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0 p-3">No documents uploaded.</p>
                    @endif
                </div>
            </div>

            {{-- Linked user --}}
            @if($application->isApproved() && $application->agent_id)
            <div class="card mb-3 border-success">
                <div class="card-header bg-success text-white"><strong>System User Created</strong></div>
                <div class="card-body">
                    <p class="mb-1">User ID: <strong>#{{ $application->agent_id }}</strong></p>
                    <a href="{{ url('/edit_employee?user_id=' . $application->agent_id) }}" class="btn btn-sm btn-success w-100">
                        <i class="fas fa-user-edit mr-1"></i> Edit Employee
                    </a>
                </div>
            </div>
            @endif
        </div>
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
