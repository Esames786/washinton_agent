@extends('layouts.innerpages')

@section('template_title')
    Cpanel Emails
@endsection

@section('content')
    @include('partials.mainsite_pages.return_function')

    <div class="page-header">
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Cpanel Email List</b></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card mt-5">
                <div class="card-header">
                    <form method="post"
                        action="{{ isset($emailToEdit) ? route('cpanelemails.update', $emailToEdit->id) : route('cpanelemails.store') }}">
                        @csrf
                        <div class="row">
                            <input type="hidden" id="id" name="id"
                                value="{{ isset($emailToEdit) ? $emailToEdit->id : '' }}">
                            <div class="col-md-3 mb-3">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ isset($emailToEdit) ? $emailToEdit->name : '' }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="url">URL</label>
                                <input type="text" class="form-control" id="url" name="url"
                                    value="{{ isset($emailToEdit) ? $emailToEdit->url : '' }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ isset($emailToEdit) ? $emailToEdit->email : '' }}" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" minlength="6">
                                @if (isset($emailToEdit))
                                    <small class="text-muted">Leave blank to keep current password</small>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="users">Users</label>
                                <select class="form-control select2" id="users" name="users[]" multiple required>
                                    <option value="" disabled>Select</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ isset($emailToEdit) && in_array($user->id, json_decode($emailToEdit->users, true)) ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <input type="submit"
                                    value="{{ isset($emailToEdit) ? 'Update Cpanel Email' : 'Create Cpanel Email' }}"
                                    class="btn btn-info">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped text-nowrap key-buttons">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cpanelEmails as $email)
                                    <tr>
                                        <td>{{ $email->id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($email->created_at)->format('d-M-Y') }}</td>
                                        <td>{{ $email->name }}</td>
                                        <td>{{ $email->email }}</td>
                                        <td>{{ $email->status ? 'Active' : 'Inactive' }}</td>
                                        <td>
                                            <a href="javascript:void(0);" class="btn btn-info btn-sm"
                                                onclick="fillEditForm({{ json_encode($email) }})">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('cpanelemails.destroy', $email->id) }}" method="POST"
                                                style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure?')">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                            <button hidden
                                                onclick="convert_data('{{ $email->id }}', '{{ $email->url }}', '{{ $email->name }}', '{{ $email->users }}')"
                                                class="btn btn-info btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> <!-- Pagination links -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraScript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

        function fillEditForm(email) {
            // Populate the form fields with the email data
            if (document.getElementById('id')) {
                document.getElementById('id').value = email.id;
            }
            if (document.getElementById('name')) {
                document.getElementById('name').value = email.name;
            }
            if (document.getElementById('url')) {
                document.getElementById('url').value = email.url;
            }
            if (document.getElementById('email')) {
                document.getElementById('email').value = email.email;
            }
            document.getElementById('password').value = ''; // Keep password blank for security

            // Handle users
            let usersSelect = $('#users');
            usersSelect.val(email.users ? JSON.parse(email.users) : []).trigger('change'); // Ensure users are set

            // Change the form action to update
            $('form').attr('action', "{{ url('cpanelemails') }}/" + email.id);
            $('input[type="submit"]').val('Update Cpanel Email'); // Change button text to Update
        }

        function convert_data(id, link, sheet_name, user_ids) {
            $('#id').val(id);
            $('#name').val(sheet_name);
            $('#url').val(link);
            $('#users').val(null).trigger('change');
            if (user_ids) {
                var selectedUserIds = JSON.parse(user_ids);
                $('#users').val(selectedUserIds).trigger('change');
            }
        }
    </script>
@endsection
