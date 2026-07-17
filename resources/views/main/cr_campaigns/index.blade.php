@extends('layouts.innerpages')

@section('template_title', 'Campaign Management')

{{-- Defines check_panel()/pay_status() etc. used by the shared nav — required, like other innerpages screens. --}}
@include('partials.mainsite_pages.return_function')

@section('content')
<style>
    /* Self-contained modal — independent of the layout's Bootstrap version. */
    .crc-overlay{position:fixed;inset:0;z-index:20000;background:rgba(15,19,26,.55);
        display:none;align-items:flex-start;justify-content:center;overflow-y:auto;padding:40px 16px;}
    .crc-overlay.open{display:flex;}
    .crc-card{background:#fff;border-radius:12px;width:100%;max-width:520px;box-shadow:0 20px 60px rgba(0,0,0,.3);}
    .crc-card .crc-head{display:flex;justify-content:space-between;align-items:center;padding:16px 20px;border-bottom:1px solid #e5e7eb;}
    .crc-card .crc-head h5{margin:0;font-size:17px;font-weight:700;}
    .crc-card .crc-head .crc-x{background:none;border:0;font-size:24px;line-height:1;color:#6b7280;cursor:pointer;}
    .crc-card .crc-body{padding:18px 20px;}
    .crc-card .crc-foot{display:flex;justify-content:flex-end;gap:8px;padding:14px 20px;border-top:1px solid #e5e7eb;}
    .crc-body .form-group{margin-bottom:14px;}
    .crc-body label{font-weight:600;font-size:13px;display:block;margin-bottom:4px;}
    .crc-body .form-control{width:100%;}
    .crc-body .form-row{display:flex;gap:12px;}
    .crc-body .form-row .form-group{flex:1;}
</style>

<div class="container-fluid">

    <div class="row mb-3 align-items-center">
        <div class="col-sm-6">
            <strong style="font-size:18px;">Campaign / Job Management</strong>
            <div class="text-muted small">Work From Home campaigns &amp; In-House / On-Site jobs shown on the application form.</div>
        </div>
        <div class="col-sm-6 text-right">
            <button type="button" class="btn btn-primary btn-sm" data-crc-open="createCampaignModal">
                <i class="fas fa-plus"></i> Add Campaign / Job
            </button>
        </div>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <div class="card">
        <div class="card-body p-0" style="overflow-x:auto;">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:40px;"></th>
                        <th>Name</th>
                        <th>Key</th>
                        <th>Employment Category</th>
                        <th>Default Pay Type</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $c)
                        <tr>
                            <td class="text-center">{!! $c->icon ?: '—' !!}</td>
                            <td><strong>{{ $c->name }}</strong>@if($c->description)<br><small class="text-muted">{{ $c->description }}</small>@endif</td>
                            <td><code>{{ $c->key }}</code></td>
                            <td>
                                @if($c->employment_category === 'work_from_home')
                                    <span class="badge badge-info">Work From Home</span>
                                @else
                                    <span class="badge badge-primary">In-House / On-Site</span>
                                @endif
                            </td>
                            <td>{{ $c->default_pay_type ? \Illuminate\Support\Str::title(str_replace('_',' ',$c->default_pay_type)) : 'All' }}</td>
                            <td>
                                @if($c->status)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-right" style="white-space:nowrap;">
                                <button type="button" class="btn btn-xs btn-outline-secondary" data-crc-open="editCampaignModal{{ $c->id }}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form method="POST" action="{{ route('cr-campaigns.toggle', $c->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-xs {{ $c->status ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                        {{ $c->status ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Edit overlay --}}
                        <div class="crc-overlay" id="editCampaignModal{{ $c->id }}">
                            <form method="POST" action="{{ route('cr-campaigns.update', $c->id) }}" class="crc-card">
                                @csrf
                                <div class="crc-head"><h5>Edit Campaign / Job</h5>
                                    <button type="button" class="crc-x" data-crc-close>&times;</button></div>
                                <div class="crc-body">
                                    @include('main.cr_campaigns._fields', ['c' => $c])
                                </div>
                                <div class="crc-foot">
                                    <button type="button" class="btn btn-secondary" data-crc-close>Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted p-4">No campaigns yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Create overlay --}}
<div class="crc-overlay" id="createCampaignModal">
    <form method="POST" action="{{ route('cr-campaigns.store') }}" class="crc-card">
        @csrf
        <div class="crc-head"><h5>Add Campaign / Job</h5>
            <button type="button" class="crc-x" data-crc-close>&times;</button></div>
        <div class="crc-body">
            @include('main.cr_campaigns._fields', ['c' => null])
        </div>
        <div class="crc-foot">
            <button type="button" class="btn btn-secondary" data-crc-close>Cancel</button>
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </form>
</div>

<script>
(function () {
    function open(id){ var el=document.getElementById(id); if(el){ el.classList.add('open'); document.body.style.overflow='hidden'; } }
    function closeAll(){ document.querySelectorAll('.crc-overlay.open').forEach(function(e){ e.classList.remove('open'); }); document.body.style.overflow=''; }
    document.querySelectorAll('[data-crc-open]').forEach(function(b){
        b.addEventListener('click', function(){ open(b.getAttribute('data-crc-open')); });
    });
    document.querySelectorAll('[data-crc-close]').forEach(function(b){
        b.addEventListener('click', closeAll);
    });
    // Click on the dark backdrop (outside the card) closes.
    document.querySelectorAll('.crc-overlay').forEach(function(o){
        o.addEventListener('click', function(e){ if(e.target===o) closeAll(); });
    });
    document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeAll(); });
})();
</script>
@endsection
