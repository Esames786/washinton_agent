{{-- One user's devices with the admin's actions (device restriction, App\Support\DeviceGate).
     Admins and Managers only; nothing shows until the migration has run. Expects $deviceUser. --}}
@php
    $__canManageDevices = \App\Support\DeviceGate::ready()
        && in_array((int) Auth::user()->role, [1, 9], true)
        && ((int) $deviceUser->role !== 1 || (int) Auth::user()->role === 1);
    $__devices = $__canManageDevices
        ? \App\UserDevice::where('user_id', $deviceUser->id)->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected', 'revoked')")->orderByDesc('last_seen_at')->get()
        : collect();
    $__devPolicy = \App\Support\DeviceGate::policy($deviceUser);
    $__devBadge = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'revoked' => 'secondary'];
@endphp
@if ($__canManageDevices)
<div class="row mt-4">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">💻 Devices</h3>
                <span class="text-muted small">Policy: <strong>{{ \App\Support\DeviceGate::POLICIES[$__devPolicy] }}</strong></span>
            </div>
            <div class="card-body p-0">
                @if (session('msg'))
                    <div class="alert alert-info m-3 py-2 small">{{ session('msg') }}</div>
                @endif
                @if ($__devices->isEmpty())
                    <p class="text-muted p-3 mb-0">No devices recorded yet. A device is recorded the first time this user signs in from it.</p>
                @else
                <div class="table-responsive">
                    <table class="table table-sm table-vcenter mb-0">
                        <thead><tr><th>Code</th><th>Device</th><th>IP (first / last)</th><th>First seen</th><th>Last seen</th><th>Status</th><th class="text-right">Actions</th></tr></thead>
                        <tbody>
                        @foreach ($__devices as $__d)
                            <tr>
                                <td><code>{{ $__d->code() }}</code></td>
                                <td title="{{ $__d->user_agent }}">{{ $__d->name() }}</td>
                                <td class="small">{{ $__d->first_ip }}{{ $__d->last_ip && $__d->last_ip !== $__d->first_ip ? ' / ' . $__d->last_ip : '' }}</td>
                                <td class="small">{{ optional($__d->first_seen_at)->format('M d, Y h:i A') }}</td>
                                <td class="small">{{ optional($__d->last_seen_at)->format('M d, Y h:i A') }}</td>
                                <td><span class="badge badge-{{ $__devBadge[$__d->status] ?? 'light' }}">{{ \App\UserDevice::LABELS[$__d->status] ?? $__d->status }}</span></td>
                                <td class="text-right" style="white-space:nowrap;">
                                    @if ($__d->status !== 'approved')
                                        <form method="POST" action="{{ route('device.approve', $__d->id) }}" class="d-inline">
                                            @csrf
                                            <button class="btn btn-xs btn-success" title="Allow this device">Approve</button>
                                        </form>
                                        @if ($__devPolicy !== 'one')
                                        <form method="POST" action="{{ route('device.approve', $__d->id) }}" class="d-inline" onsubmit="return confirm('Approve this device and revoke every other approved device?');">
                                            @csrf <input type="hidden" name="replace" value="1">
                                            <button class="btn btn-xs btn-outline-success" title="Allow this device only">Approve &amp; replace</button>
                                        </form>
                                        @endif
                                    @endif
                                    @if ($__d->status === 'pending')
                                        <form method="POST" action="{{ route('device.reject', $__d->id) }}" class="d-inline">
                                            @csrf
                                            <button class="btn btn-xs btn-outline-danger">Reject</button>
                                        </form>
                                    @endif
                                    @if ($__d->status === 'approved')
                                        <form method="POST" action="{{ route('device.revoke', $__d->id) }}" class="d-inline" onsubmit="return confirm('Revoke this device? It will be stopped on its next page load.');">
                                            @csrf
                                            <button class="btn btn-xs btn-outline-danger">Revoke</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                <p class="text-muted small px-3 py-2 mb-0 border-top">
                    Under <em>One device</em>, approving a device replaces the current one.
                    The code is what the agent sees on their waiting screen.
                </p>
            </div>
        </div>
    </div>
</div>
@endif
