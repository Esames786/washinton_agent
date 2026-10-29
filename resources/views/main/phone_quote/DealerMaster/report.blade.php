@extends('layouts.innerpages')

@section('template_title')
    Dealer Master — Report
@endsection

@section('content')
@include('partials.mainsite_pages.return_function')

<div class="container-fluid">
    <h3 class="text-center my-3">DEALER MASTER — ASSIGNED RECORDS REPORT</h3>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('dealer.master.report') }}" class="form-row align-items-end">
                <div class="form-group col-md-3">
                    <label class="form-label">Agent</label>
                    <select name="orderTaker" class="form-control">
                        <option value="">All agents</option>
                        @foreach ($agents as $a)
                            <option value="{{ $a->id }}" @selected(request('orderTaker') == $a->id)>
                                {{ $a->slug ?: trim($a->name . ' ' . $a->last_name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label class="form-label">State</label>
                    <select name="state" class="form-control">
                        <option value="">All states</option>
                        @foreach ($states as $st)
                            <option value="{{ $st }}" @selected(request('state') === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label class="form-label">Called?</label>
                    <select name="called" class="form-control">
                        <option value="">Any</option>
                        <option value="1" @selected(request('called') === '1')>Called</option>
                        <option value="0" @selected(request('called') === '0')>Not called</option>
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label class="form-label">From</label>
                    <input type="date" name="startDate" class="form-control" value="{{ request('startDate') }}">
                </div>
                <div class="form-group col-md-2">
                    <label class="form-label">To</label>
                    <input type="date" name="endDate" class="form-control" value="{{ request('endDate') }}">
                </div>

                <div class="form-group col-md-1">
                    <button type="submit" class="btn btn-primary btn-block">Run</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Assigned dealers</strong>
            <span class="badge badge-secondary">{{ number_format($data->total()) }}</span>
            <small class="text-muted ml-2">
                Only dealers currently held by an agent. A dealer returns to the pool once a history
                note is saved against it, so this shows live workload, not lifetime activity.
            </small>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>SR. #</th>
                        <th>DEALER</th>
                        <th>LOCATION</th>
                        <th>ASSIGNED TO</th>
                        <th>CALLS</th>
                        <th>LAST CONTACT</th>
                        <th>NOTES</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($data as $key => $val)
                    @php $last = $val->history->sortByDesc('id')->first(); @endphp
                    <tr>
                        <td>{{ $data->firstItem() + $key }}</td>
                        <td>
                            <strong>{{ $val->name }}</strong>
                            @if ($val->rating)
                                <br><span class="badge badge-warning">&#9733; {{ number_format($val->rating, 1) }}</span>
                            @endif
                        </td>
                        <td>{{ trim(collect([$val->city, $val->states, $val->zip])->filter()->implode(', ')) }}</td>
                        <td>
                            @if ($val->user)
                                <span class="badge badge-info">{{ $val->user->slug ?: $val->user->name }}</span>
                            @else
                                <span class="text-muted">#{{ $val->user_id }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $val->callCount->count() ? 'success' : 'light' }}">
                                {{ $val->callCount->count() }}
                            </span>
                        </td>
                        <td>
                            @if ($last)
                                {{ $last->connectStatus }}<br>
                                <small class="text-muted">{{ $last->created_at }}</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $val->history->count() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Nothing assigned for this filter.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-2">{{ $data->appends(request()->query())->links() }}</div>
</div>
@endsection
