@extends('layouts.innerpages')

@section('template_title')
    Panel Types
@endsection

@section('content')
    @include('partials.mainsite_pages.return_function')

    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Panel Types</h5>
                    </div>
                    <div class="card-body">
                        {{-- Add a new panel --}}
                        <form action="{{ route('panel_types.store') }}" method="POST" class="form-inline mb-4">
                            @csrf
                            <input type="text" name="name" class="form-control mr-2" placeholder="New panel name (e.g. Karachi)" required>
                            <button type="submit" class="btn btn-primary">Add Panel</button>
                        </form>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:70px">ID</th>
                                    <th>Name</th>
                                    <th style="width:120px">Type</th>
                                    <th style="width:120px">Status</th>
                                    <th style="width:220px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($panels as $panel)
                                    <tr>
                                        <td>{{ $panel->id }}</td>
                                        <td>
                                            @if ($panel->is_system)
                                                {{ $panel->name }}
                                            @else
                                                <form action="{{ route('panel_types.update', $panel->id) }}" method="POST" class="form-inline">
                                                    @csrf
                                                    <input type="text" name="name" value="{{ $panel->name }}" class="form-control mr-2" required>
                                                    <input type="hidden" name="status" value="{{ $panel->status }}">
                                                    <button type="submit" class="btn btn-sm btn-success">Rename</button>
                                                </form>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($panel->is_system)
                                                <span class="badge badge-secondary">System</span>
                                            @elseif ($panel->is_default)
                                                <span class="badge badge-info">Default</span>
                                            @else
                                                <span class="badge badge-light">Custom</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($panel->status)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (!$panel->is_system)
                                                <form action="{{ route('panel_types.update', $panel->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="name" value="{{ $panel->name }}">
                                                    <input type="hidden" name="status" value="{{ $panel->status ? 0 : 1 }}">
                                                    <button type="submit" class="btn btn-sm {{ $panel->status ? 'btn-warning' : 'btn-success' }}">
                                                        {{ $panel->status ? 'Disable' : 'Enable' }}
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <small class="text-muted">
                            Panel ids are fixed (they match the order <code>paneltype</code>). System panels
                            (Testing, Website) cannot be renamed or disabled. New panels are available immediately
                            in the panel switcher, order badges, and the subcontractor panel-access list.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
