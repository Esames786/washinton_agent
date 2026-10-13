@extends('layouts.new-master')
@section('title', $post->meta_title ?: $post->post_name)

@php
    $metaDesc  = $post->meta_description ?: \Illuminate\Support\Str::limit(strip_tags((string) $post->post_short_description), 160, '');
    $canonical = $post->canonical_url ?: route('Frontend.blog.show', $post->slug_name);
@endphp

@push('meta')
    @if($metaDesc)<meta name="description" content="{{ $metaDesc }}">@endif
    @if($post->meta_keyword)<meta name="keywords" content="{{ $post->meta_keyword }}">@endif
    <link rel="canonical" href="{{ $canonical }}">

    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->meta_title ?: $post->post_name }}">
    @if($metaDesc)<meta property="og:description" content="{{ $metaDesc }}">@endif
    <meta property="og:url" content="{{ $canonical }}">
    @if($post->image_url)<meta property="og:image" content="{{ $post->image_url }}">@endif
    <meta property="article:published_time" content="{{ optional($post->published_on)->toIso8601String() }}">

    {{-- Stored JSON-LD was validated and re-encoded with JSON_HEX_TAG when saved, so it
         cannot close this tag. Without one, a sensible BlogPosting is generated. --}}
    <script type="application/ld+json">
        {!! $post->blog_schema ?: json_encode([
            '@context'      => 'https://schema.org',
            '@type'         => 'BlogPosting',
            'headline'      => $post->post_name,
            'description'   => $metaDesc ?: null,
            'image'         => $post->image_url,
            'datePublished' => optional($post->published_on)->toIso8601String(),
            'dateModified'  => optional($post->updated_at)->toIso8601String(),
            'author'        => ['@type' => 'Person', 'name' => $post->blog_author ?: 'Hello Transport'],
            'publisher'     => ['@type' => 'Organization', 'name' => 'Hello Transport'],
            'mainEntityOfPage' => $canonical,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
    </script>
@endpush

@section('content')

    {{-- ================= PAGE TITLE ================= --}}
    <section class="page-title-area pt-100 pb-100"
             style="background-image:url({{ asset('frontend/newtheme-assets/img/banner/7.png') }});">
        <div class="container">
            <div class="page-title-content text-center">
                <h2>{{ $post->post_name }}</h2>
                <ul>
                    <li><a href="{{ route('Frontend.index') }}">Home</a></li>
                    <li><a href="{{ route('Frontend.blog') }}">Blog</a></li>
                    @if($post->category)
                        <li><a href="{{ route('Frontend.blog', ['category' => $post->category]) }}">{{ $post->category }}</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </section>

    {{-- ================= ARTICLE ================= --}}
    <section class="blog-details-area ptb-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <article class="blog-details-content">
                        <div class="blog-top-content">

                            @if($post->image_url)
                                <figure class="blog-details-img mb-4">
                                    <img src="{{ $post->image_url }}"
                                         alt="{{ $post->image_alt ?: $post->post_name }}"
                                         @if($post->image_title) title="{{ $post->image_title }}" @endif
                                         style="width:100%;border-radius:8px;">
                                    @if($post->image_caption)
                                        <figcaption class="text-muted mt-2" style="font-size:13px;text-align:center;">
                                            {{ $post->image_caption }}
                                        </figcaption>
                                    @endif
                                </figure>
                            @endif

                            <div class="news-content">
                                <ul class="admin">
                                    <li><i class="bx bx-calendar"></i> {{ optional($post->published_on)->format('d M Y') }}</li>
                                    @if($post->blog_author)
                                        <li><i class="bx bx-user"></i> {{ $post->blog_author }}</li>
                                    @endif
                                    @if($post->category)
                                        <li><i class="bx bx-folder"></i> {{ $post->category }}</li>
                                    @endif
                                </ul>
                            </div>

                            {{-- Admin-authored HTML; scripts and inline handlers are stripped on save. --}}
                            <div class="blog-body" style="font-size:16px;line-height:1.85;">
                                {!! $post->post_description !!}
                            </div>
                        </div>
                    </article>

                    {{-- A quote is the action every reader of a transport blog should be one click from. --}}
                    <div class="mt-5 p-4 text-center" style="background:var(--ht-navy);border-radius:12px;">
                        <h4 style="color:#fff;margin-bottom:8px;">Ready to ship?</h4>
                        <p style="color:rgba(255,255,255,.75);margin-bottom:18px;">
                            Get a free, no-obligation quote in a few minutes.
                        </p>
                        <a href="{{ route('Frontend.qoute.request') }}" class="default-btn">Get a Quote</a>
                    </div>
                </div>
            </div>

            @if($related->isNotEmpty())
                <div class="mt-5 pt-4 border-top">
                    <h3 class="text-center mb-4">More from the blog</h3>
                    <div class="row">
                        @foreach($related as $r)
                            <div class="col-lg-4 col-md-6">
                                <div class="single-blog-post">
                                    <div class="post-image">
                                        <a href="{{ route('Frontend.blog.show', $r->slug_name) }}">
                                            <img src="{{ $r->image_url ?? asset('frontend/newtheme-assets/img/blog/blog-1.jpg') }}"
                                                 alt="{{ $r->image_alt ?: $r->post_name }}" loading="lazy"
                                                 style="width:100%;height:200px;object-fit:cover;">
                                        </a>
                                    </div>
                                    <div class="blog-content">
                                        <span class="date"><i class="bx bx-calendar"></i> {{ optional($r->published_on)->format('d M Y') }}</span>
                                        <h3><a href="{{ route('Frontend.blog.show', $r->slug_name) }}">{{ $r->post_name }}</a></h3>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection
