<div class="rc-card-grid">
    @foreach ($users as $user)
        @php
            $rcUser = $user->ringCentralUser;
            $tokenExpires = $rcUser && $rcUser->token_expires_at ? $rcUser->token_expires_at : null;
            $refreshExpires = $rcUser && $rcUser->refresh_token_expires_at ? $rcUser->refresh_token_expires_at : null;
            $isExpired = $tokenExpires ? $tokenExpires->lte(now()) : false;
            $statusLabel = !$rcUser ? 'Not linked' : ($rcUser->is_active && !$isExpired ? 'Active' : ($isExpired ? 'Expired' : 'Inactive'));
            $statusClass = !$rcUser ? 'is-muted' : ($rcUser->is_active && !$isExpired ? 'is-active' : ($isExpired ? 'is-danger' : 'is-idle'));
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
                <div><strong>Phone:</strong> {{ $rcUser->phone_number ?? 'N/A' }}</div>
                <div><strong>Token Expires:</strong> {{ $tokenExpires ? $tokenExpires->format('M d, Y g:i A') : 'N/A' }}</div>
                <div><strong>Refresh Expires:</strong> {{ $refreshExpires ? $refreshExpires->format('M d, Y g:i A') : 'N/A' }}</div>
                <div><strong>Extension:</strong> {{ $rcUser->extension_id ?? 'N/A' }}</div>
            </div>
        </div>
    @endforeach
</div>
