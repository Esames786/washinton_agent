@if(session('scope_original_id'))
<div id="scope-warning-bar" style="
    position: fixed; top: 0; left: 0; right: 0; z-index: 99999;
    background: #7b1fa2;
    color: #fff; padding: 4px 16px;
    display: flex; align-items: center; justify-content: space-between; gap: 8px;
    font-size: 11px; font-weight: 600; height: 28px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.35);
">
    <div style="display:flex;align-items:center;gap:6px;">
        <span>👁</span>
        <span>Scope: <strong>{{ Auth::user()->name }}{{ Auth::user()->slug ? ' (' . Auth::user()->slug . ')' : '' }}</strong></span>
    </div>
    <a href="{{ url('/scope/exit') }}"
       style="background:#fff;color:#7b1fa2;font-weight:700;padding:2px 10px;border-radius:4px;
              text-decoration:none;font-size:11px;white-space:nowrap;">
        ↩ Back To My Account
    </a>
</div>
<div style="height:28px;"></div>
@endif
