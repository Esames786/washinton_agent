@extends('layouts.innerpages')

@section('template_title')
    Blog Posts
@endsection

@section('content')

    <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="text-secondary text-uppercase">
            <h1 class="my-4"><b>Blog Posts</b></h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('Frontend.blog') }}" target="_blank" class="btn btn-outline-secondary">View the blog</a>
            <a href="{{ route('blog-admin.create') }}" class="btn btn-primary">+ New Post</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            @if($posts->isEmpty())
                <div class="p-5 text-center text-muted">
                    No posts yet.
                    <a href="{{ route('blog-admin.create') }}">Write the first one</a>.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                        <tr>
                            <th style="width:72px;"></th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($posts as $p)
                            <tr>
                                <td>
                                    @if($p->image_url)
                                        <img src="{{ $p->image_url }}" alt="" style="width:56px;height:40px;object-fit:cover;border-radius:4px;">
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $p->post_name }}</strong>
                                    <div class="text-muted" style="font-size:12px;">/blog/{{ $p->slug_name }}</div>
                                </td>
                                <td>{{ $p->category ?: '—' }}</td>
                                <td>
                                    @php
                                        $badge = ['Published' => 'success', 'Scheduled' => 'info', 'Draft' => 'secondary'][$p->state];
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ $p->state }}</span>
                                    @if($p->state === 'Scheduled')
                                        <div class="text-muted" style="font-size:11px;">{{ $p->scheduled_at->format('d M Y, H:i') }}</div>
                                    @endif
                                </td>
                                <td>{{ optional($p->published_on)->format('d M Y') }}</td>
                                <td class="text-end text-nowrap">
                                    @if($p->is_live)
                                        <a href="{{ route('Frontend.blog.show', $p->slug_name) }}" target="_blank"
                                           class="btn btn-sm btn-outline-secondary">View</a>
                                    @endif
                                    <a href="{{ route('blog-admin.edit', $p->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form method="POST" action="{{ route('blog-admin.destroy', $p->id) }}" class="d-inline"
                                          onsubmit="return confirm('Remove this post from the site?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-3">{{ $posts->links() }}</div>

@endsection
