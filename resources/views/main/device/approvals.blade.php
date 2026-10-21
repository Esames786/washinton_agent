@extends('layouts.innerpages')

@section('template_title')
    Device Approvals
@endsection

@section('content')
    {{-- The innerpages layout calls these helpers (check_panel etc.). --}}
    @include('partials.mainsite_pages.return_function')
    {{-- Every device waiting for approval (device restriction, App\Support\DeviceGate). --}}
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">Device Approvals</h4>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-0">
            @if (session('msg'))
                <div class="alert alert-info m-3 py-2 small">{{ session('msg') }}</div>
            @endif
            @if ($devices->isEmpty())
                <p class="text-muted p-4 mb-0">No devices are waiting for approval.</p>
            @else
            <div class="table-responsive">
                <table class="table table-vcenter mb-0">
                    <thead><tr><th>Agent</th><th>Code</th><th>Device</th><th>IP</th><th>Requested</th><th class="text-right">Actions</th></tr></thead>
                    <tbody>
                    @foreach ($devices as $d)
                        @php $__policy = \App\Support\DeviceGate::policy($d->user); @endphp
                        <tr>
                            <td>
                                <a href="{{ url('/edit_subcontractor/' . $d->user_id) }}">{{ trim($d->user->name . ' ' . $d->user->last_name) }}</a>
                                <div class="small text-muted">{{ \App\Support\DeviceGate::POLICIES[$__policy] }}</div>
                            </td>
                            <td><code>{{ $d->code() }}</code></td>
                            <td title="{{ $d->user_agent }}">{{ $d->name() }}</td>
                            <td class="small">{{ $d->last_ip }}</td>
                            <td class="small">{{ optional($d->first_seen_at)->format('M d, Y h:i A') }}</td>
                            <td class="text-right" style="white-space:nowrap;">
                                <form method="POST" action="{{ route('device.approve', $d->id) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success">{{ $__policy === 'one' ? 'Approve (replaces current)' : 'Approve' }}</button>
                                </form>
                                <form method="POST" action="{{ route('device.reject', $d->id) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $devices->links() }}</div>
            @endif
        </div>
    </div>
@endsection
