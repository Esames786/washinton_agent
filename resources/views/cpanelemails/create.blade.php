@extends('layouts.innerpages')

@section('content')
    @include('partials.mainsite_pages.return_function')

    <div class="container">
        <h1>Create New Cpanel Email</h1>

        <form action="{{ route('cpanelemails.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="url">URL</label>
                <input type="text" class="form-control" id="url" name="url" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Create</button>
        </form>
    </div>
@endsection
