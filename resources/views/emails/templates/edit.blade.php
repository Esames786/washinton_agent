@extends('layouts.innerpages')
@include('partials.mainsite_pages.return_function')

@section('content')
    <div class="container">
        <h2>Edit Email Template</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('email-templates.update', $emailTemplate->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" name="title" value="{{ old('title', $emailTemplate->title) }}"
                    required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" name="description" required>{{ old('description', $emailTemplate->description) }}</textarea>
            </div>

            <div class="form-group">
                <label >Type</label>
                <select id="type" name="type" class="form-control" >
                    <option value="1" {{ old('status', $emailTemplate->type) == 1 ? 'selected' : '' }}>Other
                    </option>
                    <option value="2" {{ old('status', $emailTemplate->type) == 2 ? 'selected' : '' }}>Approaching Screen
                    </option>

                </select>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" name="status" required>
                    <option value="1" {{ old('status', $emailTemplate->status) == 1 ? 'selected' : '' }}>Active
                    </option>
                    <option value="0" {{ old('status', $emailTemplate->status) == 0 ? 'selected' : '' }}>Inactive
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="banner">Banner:</label>
                <input type="file" class="form-control-file" name="banner" id="bannerInput"
                    onchange="displayImagePreview()">
                <small id="bannerPreview" class="form-text text-muted">
                    @if ($emailTemplate->banner)
                        <img src="{{ asset($emailTemplate->banner) }}" alt="Banner" style="max-width: 100px;">
                    @endif
                </small>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script>
        function displayImagePreview() {
            var input = document.getElementById('bannerInput');
            var preview = document.getElementById('bannerPreview');

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result +
                        '" alt="Banner Preview" style="max-width: 200px; max-height: 200px;">';
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = '';
            }
        }
    </script>
@endsection
