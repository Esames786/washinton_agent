<div class="rc-card-grid">
    @foreach ($users as $user)
        @php
            $instance = $user->webPhoneInstance;
            $isActive = $instance && !$instance->closed_at && $instance->last_seen_at && $instance->last_seen_at->gt(now()->subMinutes(0.25));
            $statusLabel = $isActive ? 'Active' : ($instance ? ($instance->closed_at ? 'Closed' : 'Idle') : 'Not connected');
            $statusClass = $isActive ? 'is-active' : ($instance ? ($instance->closed_at ? 'is-danger' : 'is-idle') : 'is-muted');
        @endphp
        <div class="rc-user-card">
            <div class="rc-user-header">
                <div>
                    <p class="rc-user-name">{{ $user->name }}</p>
                    <p class="rc-user-email">{{ $user->email }}</p>
                </div>
                <span class="rc-badge {{ $statusClass }}">{{ $statusLabel }}</span>
            </div>
            <hr class="rc-divider">
            <div class="rc-info-row">
                <div><strong>Instance ID:</strong> {{ $instance->instance_id ?? 'None' }}</div>
                <div><strong>Auth ID:</strong> {{ $instance->authorization_id ?? 'None' }}</div>
                <div><strong>Last Seen:</strong> {{ $instance && $instance->last_seen_at ? $instance->last_seen_at->format('M d, Y g:i A') : 'N/A' }}</div>
                <div><strong>IP:</strong> {{ $instance->ip_address ?? 'N/A' }}</div>
            </div>
        </div>
    @endforeach
</div>
