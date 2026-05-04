@extends('layouts.innerpages')

@section('template_title')
    Add Targets
@endsection

@section('content')
    @include('partials.mainsite_pages.return_function')

    <div class="container mt-4">
        <h2 class="text-center">Add Targets</h2>
        <form action="{{ route('storeAchieveTarget') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Order Takers -->
                <div class="col-md-6 mb-3">
                    <label for="user_id" class="form-label">Order Taker</label>
                    <select name="user_id" class="form-control" id="user_id">
                        <option value="">Select Order Taker</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Order Target -->
                <div class="col-md-6 mb-3">
                    <label for="order_target" class="form-label">Order Target</label>
                    <input type="number" name="order_target" id="order_target" class="form-control" required>
                </div>

                <!-- Followup Target -->
                <div class="col-md-6 mb-3">
                    <label for="followup_target" class="form-label">Followup Target</label>
                    <input type="number" name="followup_target" id="followup_target" class="form-control" required>
                </div>

                <!-- Review Target -->
                <div class="col-md-6 mb-3">
                    <label for="review_target" class="form-label">Review Target</label>
                    <input type="number" name="review_target" id="review_target" class="form-control" required>
                </div>

                <!-- Revenue Target -->
                <div class="col-md-6 mb-3">
                    <label for="revenew_target" class="form-label">Revenue Target</label>
                    <input type="number" name="revenew_target" id="revenew_target" class="form-control" required>
                </div>

                <!-- Months -->
                <div class="col-md-6 mb-3">
                    <label for="target_month" class="form-label">Months</label>
                    <input type="month" name="target_month" id="target_month" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('extraScript')
    <script>
        // You can add any additional JavaScript here if needed.
    </script>
@endsection
