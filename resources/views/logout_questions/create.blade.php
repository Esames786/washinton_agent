@extends('layouts.innerpages')

@section('template_title')
    Create Logout Question
@endsection

@include('partials.mainsite_pages.return_function')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="text-secondary text-center text-uppercase w-100">
                    <h1 class="my-4"><b>Create Logout Question</b></h1>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-body">
                    <form method="POST" action="{{ route('logout_questions.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="question">Question:</label>
                            <textarea class="form-control" id="question" name="question" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="role">Role:</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="" disabled selected>Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                                <!-- Add more options as needed -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('logout_questions.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
