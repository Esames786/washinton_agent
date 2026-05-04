@extends('layouts.innerpages')

@section('template_title')
    Add Message Chats
@endsection

@include('partials.mainsite_pages.return_function')

@section('content')

<div class="row">
    <div class="col-12">
        @if(session('flash_message'))
            <div class="alert alert-success">
                {{ session('flash_message') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <h3>Add Message Chats</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('messagechats.store') }}" enctype="multipart/form-data">
                    @csrf
                    {{-- <div class="form-group">
                        <label for="user_id">User ID:</label>
                        <input type="text" class="form-control" id="user_id" name="user_id" value="{{ old('user_id') }}">
                    </div> --}}
                    <div class="form-group">
                        <label for="order_id">Order ID:</label>
                        {{-- <select class="form-control" id="order_id" name="order_id">
                            <option value="">Select Order ID</option>
                            @foreach($orderIds as $orderId)
                                <option value="{{ $orderId->id }}">{{ $orderId->id . ' - ' . $orderId->name }}</option>
                            @endforeach
                        </select> --}}
                        <input type="number" class="form-control" id="order_id" name="order_id" value="{{ old('order_id') }}">
                        <p id="order_name"></p>
                    </div>

                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea class="form-control" id="message" name="message">{{ old('message') }}</textarea>
                    </div>
                    {{-- <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="message_date">Message Date:</label>
                        <input type="date" class="form-control" id="message_date" name="message_date" value="{{ old('message_date') }}">
                    </div> --}}
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#order_id').keyup(function() {
            var orderId = $(this).val();
    
            // Make AJAX request
            $.ajax({
                url: '{{ route('get.order.ids', ['id' => ':id']) }}'.replace(':id', orderId),
                method: 'GET',
                data: {
                    order_id: orderId
                },
                success: function(response) {
                    // Handle successful response
                    console.log('response', response);
                    if (response.name) {
                        console.log('yessss');
                        $('#order_name').html($('#order_id').val() + ' - ' + response.name);
                    }
                    else
                    {
                        $('#order_name').html('');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(error);
                    $('#order_name').html('');
                }
            });
        });
    });

</script>

@endsection
