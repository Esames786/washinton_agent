@extends('layouts.new-master')
@section('title', $category ? $category . ' – Blog' : 'Blog')

@push('meta')
    <meta name="description" content="Auto transport, heavy equipment and freight shipping guides, tips and news from Hello Transport.">
    <link rel="canonical" href="{{ $category ? route('Frontend.blog', ['category' => $category]) : route('Frontend.blog') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Hello Transport Blog">
    <meta property="og:url" content="{{ route('Frontend.blog') }}">
@endpush

@section('content')

    {{-- ================= PAGE TITLE ================= --}}
    <section class="page-title-area pt-100 pb-100"
             style="background-image:url({{ asset('frontend/newtheme-assets/img/banner/7.png') }});">
        <div class="container">
            <div class="page-title-content text-center">
                <h2>{{ $category ?: 'Blog' }}</h2>
                <ul>
                    <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                    @if($category)
                        <li><a href="{{ route('Frontend.blog') }}">Blog</a></li>
                        <li>{{ $category }}</li>
                    @else
                        <li>Blog</li>
                    @endif
                </ul>
            </div>
        </div>
    </section>

    {{-- ================= POSTS ================= --}}
    <section class="blog-area pt-100 pb-70">
        <div class="container">

            @if($categories->isNotEmpty())
                <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
                    <a href="{{ route('Frontend.blog') }}"
                       class="btn btn-sm {{ $category ? 'btn-outline-secondary' : 'btn-dark' }}">All</a>
                    @foreach($categories as $c)
                        <a href="{{ route('Frontend.blog', ['category' => $c]) }}"
                           class="btn btn-sm {{ $category === $c ? 'btn-dark' : 'btn-outline-secondary' }}">{{ $c }}</a>
                    @endforeach
                </div>
            @endif

            @if($posts->isEmpty())
                <div class="text-center py-5">
                    <h3 class="mb-3">No posts yet</h3>
                    <p class="text-muted mb-4">We are working on our first articles — check back soon.</p>
                    <a href="{{ route('Frontend.qoute.request') }}" class="default-btn">Get a Quote</a>
                </div>
            @else
                <div class="row">
                    @foreach($posts as $p)
                        <div class="col-lg-4 col-md-6">
                            <div class="single-blog-post">
                                <div class="post-image">
                                    <a href="{{ route('Frontend.blog.show', $p->slug_name) }}">
                                        <img src="{{ $p->image_url ?? asset('frontend/newtheme-assets/img/blog/blog-1.jpg') }}"
                                             alt="{{ $p->image_alt ?: $p->post_name }}" loading="lazy"
                                             style="width:100%;height:230px;object-fit:cover;">
                                    </a>
                                </div>
                                <div class="blog-content">
                                    <span class="date">
                                        <i class="bx bx-calendar"></i>
                                        {{ optional($p->published_on)->format('d M Y') }}
                                        @if($p->category) · {{ $p->category }} @endif
                                    </span>
                                    <h3>
                                        <a href="{{ route('Frontend.blog.show', $p->slug_name) }}">{{ $p->post_name }}</a>
                                    </h3>
                                    @if($p->post_short_description)
                                        <p>{{ \Illuminate\Support\Str::limit($p->post_short_description, 140) }}</p>
                                    @endif
                                    <a href="{{ route('Frontend.blog.show', $p->slug_name) }}" class="default-btn">Read More</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">{{ $posts->links() }}</div>
            @endif
        </div>
    </section>

@endsection
