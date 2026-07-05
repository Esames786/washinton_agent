@extends('layouts.innerpages')

@section('template_title')
   Tags Guide
@endsection
@include('partials.mainsite_pages.return_function')


@section('content')

    <div class="slim-mainpanel" style=" padding-left: 0px !important; ">
        <div class="container">
            <div class="slim-pageheader pl-0">
                <ol class="breadcrumb slim-breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/guides/">Guides</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Luxury Vehicle</li>
                </ol>
                <h6 class="slim-pagetitle">Luxury Vehicle Guide</h6>
            </div><!-- slim-pageheader -->

            {{-- #6 (2026-07-03): static guide copy intentionally removed; page shell kept. --}}

        </div>
    </div>

@endsection

@section('extraScript')

    <script>

    </script>

@endsection
