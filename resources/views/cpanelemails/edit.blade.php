@extends('layouts.innerpages')

@section('content')
    @include('partials.mainsite_pages.return_function')

    <div class="container">
        <h1>Edit Cpanel Email</h1>

        <form action="{{ route('cpanelemails.update', $cpanelEmail->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $cpanelEmail->name }}"
                    required>
            </div>

            <div class="form-group">
                <label for="url">URL</label>
                <input type="text" class="form-control" id="url" name="url" value="{{ $cpanelEmail->url }}"
                    required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $cpanelEmail->email }}"
                    required>
            </div>

            <div class="form-group">
                <label for="password">Password (Leave blank if you don’t want to change)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="1" {{ $cpanelEmail->status ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$cpanelEmail->status ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
@endsection
