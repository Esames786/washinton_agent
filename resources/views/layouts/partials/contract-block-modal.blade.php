{{-- Blocking contract acceptance overlay — included in every layout --}}
<style>
.contract-modal-body { font-family: Georgia, serif; font-size: 14px; line-height: 1.75; color: #222 !important; }
.contract-modal-body h1,.contract-modal-body h2,.contract-modal-body h3,
.contract-modal-body h4,.contract-modal-body h5,.contract-modal-body h6 {
    color: #111 !important; font-weight: 700; margin-top: 20px; margin-bottom: 8px;
    font-size: revert !important; line-height: 1.3;
}
.contract-modal-body p { color: #333 !important; margin-bottom: 10px; font-size: 14px !important; }
.contract-modal-body ul,.contract-modal-body ol { color: #333 !important; padding-left: 24px; margin-bottom: 10px; }
.contract-modal-body li { color: #333 !important; font-size: 14px !important; }
.contract-modal-body strong { color: #111 !important; }
.contract-modal-body a { color: #1a73e8 !important; }
</style>
@auth
@php
    $contractBlock = null;
    try {
        $contractBlock = \Illuminate\Support\Facades\DB::table('hr_employees')
            ->where('agent_id', auth()->id())
            ->whereNotNull('contract')
            ->whereNotNull('contract_updated_at')
            ->whereNull('contract_accepted_at')
            ->first();
    } catch (\Throwable $e) {}
@endphp
@if($contractBlock)
<div id="contractBlockOverlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.88);z-index:999999;display:flex;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:8px;max-width:820px;width:96%;max-height:90vh;display:flex;flex-direction:column;box-shadow:0 20px 60px rgba(0,0,0,0.6);overflow:hidden;">
        <div style="padding:14px 20px;background:#1a1a2e;color:#d4af37;flex-shrink:0;">
            {{-- #3: name the company the contract is with (the agent's own brand). --}}
            <h5 style="margin:0;font-weight:700;font-size:16px;">📝 {{ \App\Support\Brand::for(auth()->user())['name'] ?? 'Hello Transport' }} — Subcontractor Contract</h5>
        </div>
        <div style="padding:12px 20px;background:#fff8e1;border-bottom:1px solid #ffe082;flex-shrink:0;">
            <p style="margin:0;color:#795548;font-size:13px;"><strong>You have a new or updated contract awaiting your acceptance.</strong> Please read the full contract below and click <em>"I Accept"</em> to continue. This dialog cannot be dismissed until you accept.</p>
        </div>
        <div style="flex:1;overflow-y:auto;padding:20px 24px;">
            <div class="contract-modal-body">
                {{-- Brand of the PERSON accepting (not the domain) — a Hello agent must be shown a
                     Hello-worded contract even when viewing it on the CrazyRays portal. --}}
                {!! \App\Support\Brand::applyTokens($contractBlock->contract, \App\Support\Brand::for(auth()->user())) !!}
            </div>
        </div>
        {{-- #4: e-signature (like the NDA) — draw, then accept. --}}
        <div style="padding:12px 20px;border-top:1px solid #e0e0e0;background:#fafafa;flex-shrink:0;">
            <label style="font-size:12px;font-weight:600;color:#444;display:block;margin-bottom:6px;">
                Draw Your Signature <span style="color:red;">*</span>
                <span style="font-weight:400;color:#888;margin-left:8px;font-size:11px;">(mouse or touch)</span>
            </label>
            <div style="position:relative;border:2px dashed #aab4cc;border-radius:6px;background:#fff;display:inline-block;">
                <canvas id="contractSigCanvas" width="440" height="100" style="display:block;cursor:crosshair;touch-action:none;"></canvas>
                <button type="button" onclick="contractSigClear()" style="position:absolute;top:6px;right:6px;font-size:11px;padding:2px 8px;background:#eee;border:1px solid #ccc;border-radius:4px;cursor:pointer;">Clear</button>
            </div>
        </div>
        <div style="padding:14px 20px;border-top:1px solid #e0e0e0;background:#f5f5f5;display:flex;align-items:center;justify-content:flex-end;gap:12px;flex-shrink:0;">
            <span id="contractAcceptMsg" style="font-size:13px;display:none;"></span>
            <button id="contractAcceptBtn"
                    onclick="acceptPendingContract()"
                    style="background:#28a745;color:#fff;border:none;padding:10px 32px;font-size:15px;font-weight:600;border-radius:5px;cursor:pointer;">
                ✓ Sign &amp; Accept this Contract
            </button>
        </div>
    </div>
</div>
<script>
// #4: signature pad (same pattern as the NDA)
var _csCanvas = document.getElementById('contractSigCanvas');
var _csCtx = _csCanvas ? _csCanvas.getContext('2d') : null;
var _csDrawing = false, _csLX = 0, _csLY = 0, _csHasDraw = false;
function _csPos(e){var r=_csCanvas.getBoundingClientRect(),s=e.touches?e.touches[0]:e;return{x:s.clientX-r.left,y:s.clientY-r.top};}
if (_csCanvas) {
    var _s=function(e){e.preventDefault();_csDrawing=true;var p=_csPos(e);_csLX=p.x;_csLY=p.y;};
    var _e=function(){_csDrawing=false;};
    var _d=function(e){if(!_csDrawing)return;e.preventDefault();var p=_csPos(e);_csCtx.strokeStyle='#1a1a2e';_csCtx.lineWidth=2;_csCtx.lineCap='round';_csCtx.lineJoin='round';_csCtx.beginPath();_csCtx.moveTo(_csLX,_csLY);_csCtx.lineTo(p.x,p.y);_csCtx.stroke();_csLX=p.x;_csLY=p.y;_csHasDraw=true;};
    _csCanvas.addEventListener('mousedown',_s);_csCanvas.addEventListener('mouseup',_e);_csCanvas.addEventListener('mouseleave',_e);_csCanvas.addEventListener('mousemove',_d);
    _csCanvas.addEventListener('touchstart',_s,{passive:false});_csCanvas.addEventListener('touchend',_e);_csCanvas.addEventListener('touchmove',_d,{passive:false});
}
function contractSigClear(){if(_csCtx){_csCtx.clearRect(0,0,_csCanvas.width,_csCanvas.height);_csHasDraw=false;}}

function acceptPendingContract() {
    var btn = document.getElementById('contractAcceptBtn');
    var msg = document.getElementById('contractAcceptMsg');

    if (!_csHasDraw) {
        msg.style.color = '#dc3545';
        msg.textContent = 'Please draw your signature before accepting.';
        msg.style.display = 'inline';
        return;
    }

    btn.disabled = true;
    btn.textContent = 'Saving...';
    var token = document.querySelector('meta[name="csrf-token"]');
    fetch('/employee-review/accept-contract', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token ? token.getAttribute('content') : ''
        },
        body: JSON.stringify({ user_id: {{ auth()->id() }}, signature_data: _csCanvas.toDataURL('image/png') })
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.success) {
            document.getElementById('contractBlockOverlay').style.display = 'none';
        } else {
            btn.disabled = false;
            btn.textContent = '✓ Sign & Accept this Contract';
            msg.style.color = '#dc3545';
            msg.textContent = 'Could not record acceptance. Please try again.';
            msg.style.display = 'inline';
        }
    })
    .catch(function() {
        btn.disabled = false;
        btn.textContent = '✓ Sign & Accept this Contract';
        msg.style.color = '#dc3545';
        msg.textContent = 'Network error. Please try again.';
        msg.style.display = 'inline';
    });
}
</script>
@endif
@endauth
