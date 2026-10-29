@extends('layouts.innerpages')

@section('template_title')
    Dealer Master
@endsection

@section('content')
@include('partials.mainsite_pages.return_function')
@php
    $__dmU = Auth::user();
    $__dmAdmin = in_array((int) $__dmU->role, [1, 9], true);
    $check_call = check_call();
@endphp

<div class="container-fluid">
    <h3 class="text-center my-3">DEALER MASTER</h3>

    <div class="card mb-3">
        <div class="card-body">
            <form id="dmFilter" class="form-row align-items-end">
                <div class="form-group col-md-2">
                    <label class="form-label">State</label>
                    <select name="state" class="form-control">
                        <option value="">All states</option>
                        @foreach ($states as $st)
                            <option value="{{ $st }}" @selected($state === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label class="form-label">Phone</label>
                    <select name="w_phone" class="form-control">
                        <option value="">With or without</option>
                        <option value="1">Has a phone</option>
                        <option value="0">No phone</option>
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label class="form-label">Email</label>
                    <select name="email" class="form-control">
                        <option value="">Any</option>
                        <option value="1">Has email</option>
                        <option value="0">No email</option>
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label class="form-label">History</label>
                    <select name="withHistory" class="form-control">
                        <option value="">Any</option>
                        <option value="1">Contacted</option>
                        <option value="0">Never contacted</option>
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label class="form-label">Min rating</label>
                    <select name="min_rating" class="form-control">
                        <option value="">Any</option>
                        <option value="4.5">4.5 and up</option>
                        <option value="4">4.0 and up</option>
                        <option value="3">3.0 and up</option>
                    </select>
                </div>

                <div class="form-group col-md-2">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="name, city, phone, email">
                </div>

                @if ($__dmAdmin)
                    <div class="form-group col-md-2">
                        <label class="form-label">Search as</label>
                        <select name="search_as" class="form-control">
                            <option value="">Everyone</option>
                            <option value="2">Only my assigned</option>
                        </select>
                    </div>
                @endif

                <div class="form-group col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fa fa-search"></i> Filter
                    </button>
                </div>

                @if ($__dmAdmin)
                    <div class="form-group col-md-2">
                        <a href="{{ route('dealer.master.all') }}" class="btn btn-success btn-block">Assign Dealers</a>
                    </div>
                    <div class="form-group col-md-2">
                        <a href="{{ route('dealer.master.report') }}" class="btn btn-info btn-block">Report</a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div id="dmTable">
        @include('main.phone_quote.DealerMaster.table', ['data' => $data, 'state' => $state])
    </div>
</div>

<script>
(function () {
    // Filtering reloads only the table, so the form keeps its values and the page does not jump.
    $('#dmFilter').on('submit', function (e) {
        e.preventDefault();
        $('#dmTable').html('<p class="text-center p-4">Loading...</p>');
        $.get('{{ route('dealer.master.search') }}', $(this).serialize(), function (html) {
            $('#dmTable').html(html);
        }).fail(function () {
            $('#dmTable').html('<p class="text-danger text-center p-4">Could not load. Please try again.</p>');
        });
    });

    // Paging inside the reloaded table stays on the same AJAX path.
    $(document).on('click', '#dmTable .pagination a', function (e) {
        e.preventDefault();
        $.get($(this).attr('href'), function (html) { $('#dmTable').html(html); });
    });
})();
</script>

{{-- Behaviour + history modal, shared with the Assign screen. --}}
@include('main.phone_quote.DealerMaster._scripts')

@endsection
