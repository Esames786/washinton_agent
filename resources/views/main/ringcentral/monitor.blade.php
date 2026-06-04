@extends('layouts.mainsite')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap');

    :root {
        --rc-ink: #0f172a;
        --rc-muted: #5b6b86;
        --rc-card: #ffffff;
        --rc-accent: #0ea5e9;
        --rc-accent-2: #22c55e;
        --rc-warn: #f59e0b;
        --rc-danger: #ef4444;
        --rc-border: #e2e8f0;
        --rc-bg: #f8fafc;
    }

    .rc-monitor {
        font-family: "Space Grotesk", "Segoe UI", sans-serif;
        color: var(--rc-ink);
        background: radial-gradient(1200px 400px at 10% -10%, #e0f2fe 0%, transparent 60%),
            radial-gradient(1000px 500px at 90% -20%, #dcfce7 0%, transparent 55%),
            var(--rc-bg);
        padding: 28px 24px 40px;
        border-radius: 18px;
        border: 1px solid var(--rc-border);
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
    }

    .rc-monitor-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }

    .rc-monitor-title {
        font-size: 26px;
        font-weight: 700;
        letter-spacing: -0.02em;
        margin: 0 0 6px;
    }

    .rc-monitor-subtitle {
        margin: 0;
        color: var(--rc-muted);
        font-size: 14px;
    }

    .rc-monitor-meta {
        font-size: 12px;
        color: var(--rc-muted);
        background: #f1f5f9;
        padding: 6px 10px;
        border-radius: 999px;
        border: 1px solid var(--rc-border);
    }

    .rc-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }

    .rc-stat-card {
        background: var(--rc-card);
        border: 1px solid var(--rc-border);
        border-radius: 14px;
        padding: 14px 16px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
    }

    .rc-stat-label {
        font-size: 12px;
        color: var(--rc-muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 6px;
    }

    .rc-stat-value {
        font-size: 24px;
        font-weight: 700;
    }

    .rc-panels {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 18px;
    }

    .rc-panel {
        background: var(--rc-card);
        border: 1px solid var(--rc-border);
        border-radius: 16px;
        padding: 18px;
        box-shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .rc-panel-header {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 12px;
    }

    .rc-panel-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .rc-panel-note {
        color: var(--rc-muted);
        font-size: 12px;
        margin: 0;
    }

    .rc-card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 12px;
    }

    .rc-user-card {
        border: 1px solid var(--rc-border);
        border-radius: 14px;
        padding: 12px 14px;
        background: #fff;
        display: grid;
        gap: 8px;
    }

    .rc-user-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .rc-user-name {
        font-weight: 600;
        font-size: 15px;
        margin: 0;
    }

    .rc-user-email {
        font-size: 12px;
        color: var(--rc-muted);
        margin: 2px 0 0;
        word-break: break-all;
    }

    .rc-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid transparent;
    }

    .rc-badge.is-active {
        background: rgba(34, 197, 94, 0.12);
        color: #15803d;
        border-color: rgba(34, 197, 94, 0.35);
    }

    .rc-badge.is-idle {
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
        border-color: rgba(245, 158, 11, 0.35);
    }

    .rc-badge.is-danger {
        background: rgba(239, 68, 68, 0.12);
        color: #b91c1c;
        border-color: rgba(239, 68, 68, 0.35);
    }

    .rc-badge.is-muted {
        background: rgba(148, 163, 184, 0.18);
        color: #475569;
        border-color: rgba(148, 163, 184, 0.35);
    }

    .rc-info-row {
        display: grid;
        gap: 4px;
        font-size: 12px;
        color: var(--rc-muted);
    }

    .rc-info-row strong {
        color: var(--rc-ink);
        font-weight: 600;
    }

    .rc-divider {
        height: 1px;
        background: var(--rc-border);
        border: none;
        margin: 2px 0 6px;
    }

    @media (max-width: 720px) {
        .rc-monitor {
            padding: 18px 14px 24px;
        }

        .rc-monitor-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endsection

@section('content')
<div class="rc-monitor">
    <div class="rc-monitor-header">
        <div>
            <h2 class="rc-monitor-title">R Control Center</h2>
            <p class="rc-monitor-subtitle">Live visibility for WebPhone sessions and auth token health.</p>
        </div>
        <div class="rc-monitor-meta">Updated {{ now()->format('M d, Y g:i A') }}</div>
    </div>

    <div class="rc-stats-grid">
        <div class="rc-stat-card">
            <div class="rc-stat-label">WebPhone Active</div>
            <div class="rc-stat-value">{{ $webphoneTotals['active'] }}</div>
        </div>
        <div class="rc-stat-card">
            <div class="rc-stat-label">WebPhone Total</div>
            <div class="rc-stat-value">{{ $webphoneTotals['total'] }}</div>
        </div>
        <div class="rc-stat-card">
            <div class="rc-stat-label">R Active</div>
            <div class="rc-stat-value">{{ $ringcentralTotals['active'] }}</div>
        </div>
        <div class="rc-stat-card">
            <div class="rc-stat-label">Tokens Expired</div>
            <div class="rc-stat-value">{{ $ringcentralTotals['tokenExpired'] }}</div>
        </div>
    </div>

    <div class="rc-panels">
        <section class="rc-panel">
            <div class="rc-panel-header">
                <h3 class="rc-panel-title">WebPhone Instances</h3>
                <p class="rc-panel-note">Active if last seen within 15 seconds.</p>
            </div>
            @include('main.ringcentral.monitor-webphone')
        </section>

        <section class="rc-panel">
            <div class="rc-panel-header">
                <h3 class="rc-panel-title">Auth Tokens</h3>
                <p class="rc-panel-note">Based on R-Dialer  user records.</p>
            </div>
            @include('main.ringcentral.monitor-tokens')
        </section>
    </div>
</div>
@endsection
