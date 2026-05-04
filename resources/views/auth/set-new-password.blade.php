@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Set New Password</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input id="new_password" type="password"
                                    class="form-control @error('new_password') is-invalid @enderror" name="new_password"
                                    required autocomplete="new-password">

                                @if ($errors->has('new_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('new_password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <input id="new_password_confirmation" type="password" class="form-control"
                                    name="new_password_confirmation" required autocomplete="new-password">
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Set New Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
