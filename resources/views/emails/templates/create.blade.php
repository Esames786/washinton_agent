@extends('layouts.innerpages')
@include('partials.mainsite_pages.return_function')

@section('content')
    <div class="container">
        <h2>Create Email Template</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('email-templates.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label >Type</label>
                <select id="type" name="type" class="form-control" >
                    <option value="1">Other</option>
                    <option value="2">Approaching Screen</option>

                </select>
            </div>
            <div class="form-group">
                <label for="banner">Banner:</label>
                <input type="file" class="form-control-file" name="banner" id="bannerInput" onchange="displayFileName()">
                <small id="bannerFileName" class="form-text text-muted"></small>
            </div>
            <button type="submit" class="btn btn-success">Create</button>
        </form>
    </div>

    <script>
        function displayFileName() {
            var input = document.getElementById('bannerInput');
            var fileName = document.getElementById('bannerFileName');
    
            if (input.files.length > 0) {
                fileName.innerHTML = 'Selected file: ' + input.files[0].name;
            } else {
                fileName.innerHTML = '';
            }
        }
    </script>
@endsection