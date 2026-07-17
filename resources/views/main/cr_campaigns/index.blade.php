@extends('layouts.innerpages')

@section('template_title', 'Campaign Management')

@section('content')
<div class="container-fluid">

    <div class="row mb-3 align-items-center">
        <div class="col-sm-6">
            <strong style="font-size:18px;">Campaign / Job Management</strong>
            <div class="text-muted small">Work From Home campaigns &amp; In-House / On-Site jobs shown on the application form.</div>
        </div>
        <div class="col-sm-6 text-right">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createCampaignModal">
                <i class="fas fa-plus"></i> Add Campaign / Job
            </button>
        </div>
    </div>

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <div class="card">
        <div class="card-body p-0">
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
                            <td class="text-right">
                                <button type="button" class="btn btn-xs btn-outline-secondary" data-toggle="modal" data-target="#editCampaignModal{{ $c->id }}">
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

                        {{-- Edit modal --}}
                        <div class="modal fade" id="editCampaignModal{{ $c->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('cr-campaigns.update', $c->id) }}" class="modal-content">
                                    @csrf
                                    <div class="modal-header"><h5 class="modal-title">Edit Campaign / Job</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                    <div class="modal-body">
                                        @include('main.cr_campaigns._fields', ['c' => $c])
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted p-4">No campaigns yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Create modal --}}
<div class="modal fade" id="createCampaignModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('cr-campaigns.store') }}" class="modal-content">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Add Campaign / Job</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                @include('main.cr_campaigns._fields', ['c' => null])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>
@endsection
