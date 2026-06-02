@extends('layouts.innerpages')

@section('template_title')
    Guide Videos
@endsection

@section('content')
<div class="page-header">
    <div class="text-secondary text-center text-uppercase w-100">
        <h1 class="my-4"><b>Guide Videos</b></h1>
    </div>
</div>

<div class="row">
    @if($videos->isEmpty())
        <div class="col-12">
            <div class="alert alert-info text-center">No guide videos available yet.</div>
        </div>
    @else
        @foreach($videos as $video)
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card h-100">
                <div class="card-body p-2">
                    <video
                        class="w-100"
                        style="border-radius:6px;max-height:220px;background:#000;"
                        controls
                        preload="metadata">
                        <source src="{{ asset('storage/' . $video->filename) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="card-footer">
                    <h6 class="font-weight-bold mb-1">{{ $video->title }}</h6>
                    @if($video->description)
                        <p class="text-muted small mb-1">{{ $video->description }}</p>
                    @endif
                    <small class="text-secondary">
                        <i class="fe fe-user"></i>
                        {{ $video->user ? $video->user->name . ' ' . $video->user->last_name : '—' }}
                        &nbsp;·&nbsp;
                        <i class="fe fe-calendar"></i>
                        {{ $video->created_at ? $video->created_at->format('M d, Y') : '' }}
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
