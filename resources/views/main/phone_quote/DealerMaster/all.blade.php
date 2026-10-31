@extends('layouts.innerpages')

@section('template_title')
    Dealer Master — Assign
@endsection

@section('content')
@include('partials.mainsite_pages.return_function')

<div class="container-fluid">
    <h3 class="text-center my-3">DEALER MASTER — ASSIGN</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $e) <div>{{ $e }}</div> @endforeach
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-header"><strong>Hand records to an agent</strong></div>
        <div class="card-body">
            <p class="text-muted">
                <strong>Records</strong> is the agent's whole list from the chosen states. Assigning again
                replaces the list: dealers outside the new choice go back to the pool, and the list is
                topped up with <strong>unassigned</strong> dealers. An agent keeps a record until they
                save a history note against it, at which point it returns to the pool.
            </p>

            <form method="POST" action="{{ route('dealer.master.assign') }}" class="form-row align-items-end">
                @csrf

                <div class="form-group col-md-3">
                    <label class="form-label">Agent <span class="text-danger">*</span></label>
                    <select name="orderTaker" class="form-control" required>
                        <option value="">Select agent…</option>
                        @foreach ($agents as $a)
                            {{-- An inactive agent cannot sign in to see what is assigned to them. --}}
                            <option value="{{ $a->id }}">{{ $a->slug ?: trim($a->name . ' ' . $a->last_name) }}{{ (int) $a->status === 1 ? '' : ' (inactive)' }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label class="form-label">States <span class="text-danger">*</span></label>
                    <select name="state[]" id="dmStates" class="form-control dm-states"
                            multiple required data-placeholder="Choose one or more states">
                        @foreach ($states as $st)
                            <option value="{{ $st }}">{{ $st }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">
                        <a href="javascript:void(0)" id="dmStatesAll">select all</a> ·
                        <a href="javascript:void(0)" id="dmStatesNone">clear</a>
                    </small>
                </div>

                <div class="form-group col-md-2">
                    <label class="form-label">Records <span class="text-danger">*</span></label>
                    <input type="number" name="recordsAllowed" class="form-control" min="1" max="100000"
                           value="50" required>
                </div>

                <div class="form-group col-md-2">
                    <label class="form-label">Which records <span class="text-danger">*</span></label>
                    <select name="w_phone2" class="form-control" required>
                        <option value="1">With a phone number</option>
                        <option value="0">Without a phone number</option>
                    </select>
                </div>

                <div class="form-group col-md-1">
                    <button type="submit" class="btn btn-success btn-block">Assign</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header"><strong>Current assignments</strong></div>
        <div class="card-body p-0">
            <table class="table table-sm table-bordered mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Agent</th><th>States</th><th>Records</th><th>Phone</th><th>Holding now</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($assigns as $as)
                    <tr>
                        <td>
                            {{ $as->orderTakerUser
                                ? ($as->orderTakerUser->slug ?: $as->orderTakerUser->name)
                                : '#' . $as->orderTaker }}
                        </td>
                        <td>{{ $as->state }}</td>
                        <td>{{ $as->recordsAllowed }}</td>
                        <td>{{ (int) $as->with_phone === 1 ? 'With phone' : 'No phone' }}</td>
                        <td>{{ number_format(\App\DealerMaster::where('user_id', $as->orderTaker)->count()) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Nothing assigned yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>All dealers</strong>
            <span class="badge badge-secondary">{{ number_format($data->total()) }}</span>
        </div>
        <div class="card-body">
            @include('main.phone_quote.DealerMaster.table', ['data' => $data, 'state' => ''])
        </div>
    </div>
</div>
{{-- The table partial's buttons need these handlers; without it nothing on the rows works. --}}
<script>
// select2 ships in the layout footer, which loads after this section — so initialise on
// window load. Without it this renders as a full-height list box that swallows the form.
window.addEventListener("load", function () {
    var $s = $("#dmStates");
    if ($s.length && $.fn.select2) {
        $s.select2({ width: "100%", placeholder: $s.data("placeholder"), closeOnSelect: false });
    }
    $("#dmStatesAll").on("click", function () {
        $s.find("option").prop("selected", true).end().trigger("change");
    });
    $("#dmStatesNone").on("click", function () {
        $s.val(null).trigger("change");
    });
});
</script>
@include('main.phone_quote.DealerMaster._scripts')
@endsection
