{{--
    Device restriction: the page a device waits on until an admin approves it (App\Support\DeviceGate).
    Standalone like the NDA page: the portal is not usable from here, so nothing else is loaded.
    It asks /device-status every 15 seconds and moves on to the dashboard once approved.
--}}
@php
    $__brand = \App\Support\Brand::current();
    $__refused = in_array($device->status, [\App\UserDevice::REJECTED, \App\UserDevice::REVOKED], true);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Device approval — {{ $__brand['name'] }}</title>
<style>
  html, body { margin: 0; padding: 0; min-height: 100%; background: #0f172a; color: #0f172a;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
  .wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box; }
  .card { background: #fff; border-radius: 14px; max-width: 460px; width: 100%; padding: 28px 24px; box-shadow: 0 20px 50px rgba(0,0,0,.35); text-align: center; }
  .logo { height: 44px; margin-bottom: 14px; }
  h1 { font-size: 20px; margin: 0 0 10px; }
  p { font-size: 14px; line-height: 1.55; color: #475569; margin: 0 0 14px; }
  .code { display: inline-block; font: 600 22px/1 ui-monospace, Consolas, monospace; letter-spacing: 3px;
      background: #f1f5f9; border: 1px dashed #94a3b8; border-radius: 8px; padding: 10px 16px; margin: 4px 0 6px; }
  .meta { font-size: 12px; color: #64748b; margin-bottom: 18px; }
  .status { font-size: 13px; color: #b45309; margin-bottom: 18px; }
  .status.refused { color: #b91c1c; }
  a.btn { display: inline-block; text-decoration: none; background: #0f172a; color: #fff; border-radius: 8px; padding: 10px 18px; font-size: 14px; }
</style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <img class="logo" src="{{ asset($__brand['logo']) }}" alt="{{ $__brand['name'] }}">
    @if ($__refused)
      <h1>This device is not allowed</h1>
      <p>Your administrator has not allowed <strong>{{ $user->name }}</strong>'s account to be used on this device.
         Please use your approved device, or contact your administrator.</p>
    @else
      <h1>Waiting for approval</h1>
      <p>This is a new device for <strong>{{ $user->name }}</strong>'s account. Your administrator needs to approve it
         before you can continue. Tell them this code:</p>
    @endif
    <div class="code">{{ $device->code() }}</div>
    <div class="meta">{{ $device->name() }} · {{ $device->last_ip }}</div>
    <div class="status {{ $__refused ? 'refused' : '' }}" id="deviceStatus">
      {{ $__refused ? \App\UserDevice::LABELS[$device->status] : 'Checking for approval every 15 seconds…' }}
    </div>
    <a class="btn" href="{{ url('/logout') }}">Sign out</a>
  </div>
</div>
<script>
(function () {
  var url = @json(route('device.status'));
  function check() {
    fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
      .then(function (r) { return r.ok ? r.json() : null; })
      .then(function (d) {
        if (!d) { return; }
        if (d.allowed) { window.location.href = @json(url('/dashboard')); return; }
        var el = document.getElementById('deviceStatus');
        if (d.status === 'rejected' || d.status === 'revoked') {
          el.textContent = 'This device was ' + d.status + ' by your administrator.';
          el.className = 'status refused';
        }
      })
      .catch(function () {});
  }
  setInterval(check, 15000);
})();
</script>
</body>
</html>
