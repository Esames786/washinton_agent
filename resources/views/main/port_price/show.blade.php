@extends('layouts.innerpages')

@section('template_title')
    {{  ucfirst(trim('Show Port Price','/'))}}
@endsection
@section('content')

    @include('partials.mainsite_pages.return_function')
    
    <div class="page-header">
        <div class="text-secondary text-center text-uppercase w-100">
            <h1 class="my-4"><b>Show Port Price</b></h1>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Show Port Price
                </div>
                <div class="card-body">
                    {!! html_entity_decode($data->long_data) !!}
                </div>
            </div>
        </div>
    </div>

@endsection