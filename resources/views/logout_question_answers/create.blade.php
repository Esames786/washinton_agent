@extends('layouts.innerpages')

@section('template_title')
    Create Logout Question
@endsection

@include('partials.mainsite_pages.return_function')

@section('content')

<style>
    .app-content {
        margin-right: 10rem;
        margin-left: 10rem;
        padding-bottom: 1rem;
    }
</style>

    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="text-secondary text-center text-uppercase w-100">
                    <h1 class="my-4"><b>Answer The Questions Please...</b></h1>
                </div>
            </div>
            <div class="card mt-5">
                <div class="card-body">
                    <form method="POST" action="{{ route('logout_questions_answers.store') }}">
                        @csrf
                        @foreach($questions as $question)
                            <div class="form-group">
                                <p><strong>Q: {{ $question->question }}</strong></p>
                                <input name="question_id[]" hidden value="{{ $question->id }}" />
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" id="answer" name="answer[]" rows="3" required placeholder="Write your answer here pls..."></textarea>
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
