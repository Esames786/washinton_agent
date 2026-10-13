@extends('layouts.innerpages')

@section('template_title')
    {{ $post->exists ? 'Edit Post' : 'New Post' }}
@endsection

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

@section('content')

    <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="text-secondary text-uppercase">
            <h1 class="my-4"><b>{{ $post->exists ? 'Edit Post' : 'New Post' }}</b></h1>
        </div>
        <a href="{{ route('blog-admin.index') }}" class="btn btn-outline-secondary">← All posts</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data" id="blogForm"
          action="{{ $post->exists ? route('blog-admin.update', $post->id) : route('blog-admin.store') }}">
        @csrf
        @if($post->exists) @method('PUT') @endif

        <div class="row g-3">

            {{-- ── The post ── --}}
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input name="post_name" class="form-control" required maxlength="255"
                                   value="{{ old('post_name', $post->post_name) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">URL slug</label>
                            <div class="input-group">
                                <span class="input-group-text">/blog/</span>
                                <input name="slug_name" class="form-control" maxlength="255"
                                       value="{{ old('slug_name', $post->slug_name) }}"
                                       placeholder="Leave blank to build it from the title">
                            </div>
                            @if($post->exists && $post->is_live)
                                <small class="text-warning">
                                    This post is live. Changing the slug changes its address — search engines
                                    will have indexed the current one.
                                </small>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Short description</label>
                            <textarea name="post_short_description" rows="2" class="form-control" maxlength="1000"
                                      placeholder="One or two sentences shown on the blog listing">{{ old('post_short_description', $post->post_short_description) }}</textarea>
                        </div>

                        <div class="mb-1">
                            <label class="form-label fw-semibold">Content <span class="text-danger">*</span></label>
                            <textarea id="summernote">{!! old('post_description', $post->post_description) !!}</textarea>
                            <input type="hidden" name="post_description" id="post_description"
                                   value="{{ old('post_description', $post->post_description) }}">
                        </div>
                    </div>
                </div>

                {{-- ── SEO ── --}}
                <div class="card mt-3">
                    <div class="card-header fw-bold">Search engine settings</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Meta title</label>
                            <input name="meta_title" class="form-control" maxlength="255" id="metaTitle"
                                   value="{{ old('meta_title', $post->meta_title) }}"
                                   placeholder="Defaults to the post title">
                            <small class="text-muted"><span id="metaTitleCount">0</span> characters — aim for under 60.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meta description</label>
                            <textarea name="meta_description" rows="2" class="form-control" maxlength="500" id="metaDesc"
                                      placeholder="Defaults to the short description">{{ old('meta_description', $post->meta_description) }}</textarea>
                            <small class="text-muted"><span id="metaDescCount">0</span> characters — aim for under 160.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meta keywords</label>
                            <input name="meta_keyword" class="form-control" maxlength="255"
                                   value="{{ old('meta_keyword', $post->meta_keyword) }}" placeholder="comma, separated">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Canonical URL</label>
                            <input name="canonical_url" type="url" class="form-control" maxlength="255"
                                   value="{{ old('canonical_url', $post->canonical_url) }}"
                                   placeholder="Only if this post first appeared elsewhere">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Schema (JSON-LD)</label>
                            <textarea name="blog_schema" rows="4" class="form-control font-monospace" style="font-size:12px;"
                                      placeholder='{"@context":"https://schema.org","@type":"BlogPosting", ...}'>{{ old('blog_schema', $post->blog_schema) }}</textarea>
                            <small class="text-muted">Optional. Must be valid JSON — it is checked when you save.</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Publishing + image ── --}}
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header fw-bold">Publishing</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="0" @selected((string) old('status', $post->status) === '0')>Draft — not on the site</option>
                                <option value="1" @selected((string) old('status', $post->status) === '1')>Published</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Publish on (optional)</label>
                            <input type="datetime-local" name="scheduled_at" class="form-control"
                                   value="{{ old('scheduled_at', optional($post->scheduled_at)->format('Y-m-d\TH:i')) }}">
                            <small class="text-muted">A published post with a future date stays hidden until then.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <option value="">— None —</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c }}" @selected(old('category', $post->category) === $c)>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Author</label>
                            <input name="blog_author" class="form-control" maxlength="150"
                                   value="{{ old('blog_author', $post->blog_author) }}">
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header fw-bold">Featured image</div>
                    <div class="card-body">
                        @if($post->image_url)
                            <img src="{{ $post->image_url }}" alt="" class="img-fluid rounded mb-2">
                        @endif
                        <input type="file" name="post_image" class="form-control mb-2" accept="image/jpeg,image/png,image/webp">
                        <small class="text-muted d-block mb-3">JPG, PNG or WebP, up to 4 MB.</small>

                        <div class="mb-2">
                            <label class="form-label">Alt text</label>
                            <input name="image_alt" class="form-control" maxlength="255"
                                   value="{{ old('image_alt', $post->image_alt) }}"
                                   placeholder="Describes the image — used by search engines and screen readers">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Image title</label>
                            <input name="image_title" class="form-control" maxlength="255"
                                   value="{{ old('image_title', $post->image_title) }}">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Caption</label>
                            <input name="image_caption" class="form-control" maxlength="255"
                                   value="{{ old('image_caption', $post->image_caption) }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-3 py-2">
                    {{ $post->exists ? 'Save changes' : 'Save post' }}
                </button>
            </div>
        </div>
    </form>

@endsection

@section('extraScript')
    <script src="{{ url('assets/summer_note/js/summernote.min.js') }}"></script>
    <script>
        $('#summernote').summernote({ placeholder: 'Write the post…', tabsize: 2, height: 460 });

        // Read the editor on submit rather than on every keystroke — a keyup listener misses
        // toolbar actions such as inserting an image or a link.
        $('#blogForm').on('submit', function () {
            var html = $('#summernote').summernote('code');
            if ($('#summernote').summernote('isEmpty')) { html = ''; }
            $('#post_description').val(html);
        });

        // Live character counts for the two fields search engines truncate.
        function counter(input, out) {
            var i = document.getElementById(input), o = document.getElementById(out);
            if (!i || !o) return;
            var upd = function () { o.textContent = i.value.length; };
            i.addEventListener('input', upd); upd();
        }
        counter('metaTitle', 'metaTitleCount');
        counter('metaDesc', 'metaDescCount');
    </script>
@endsection
