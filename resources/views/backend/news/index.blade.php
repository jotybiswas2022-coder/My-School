@extends('backend.layouts.app')

@section('title', 'News')

@section('content')
    <div class="page-header">
        <div>
            <h1>News</h1>
            <p>Publish news stories and updates to the website.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.news.create') }}" class="b-btn b-btn-primary">+ Add News</a>
        </div>
    </div>

    <form method="GET" class="filter-bar">
        <div class="filter-grid">
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Search</label>
                <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Article title">
            </div>
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Category</label>
                <select name="category" class="form-select">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:8px;">
                <button class="b-btn b-btn-primary">Filter</button>
                <a href="{{ route('admin.news.index') }}" class="b-btn b-btn-outline">Reset</a>
            </div>
        </div>
    </form>

    <div class="b-card">
        @if ($news->isEmpty())
            <div class="b-empty">
                <div class="b-empty-ico"><i class="bi bi-newspaper"></i></div>
                <h3>No news articles yet</h3>
                <p style="font-size:.86rem;">Publish your first article to keep the community updated.</p>
            </div>
        @else
            <div class="b-table-wrap">
                <table class="b-table">
                    <thead>
                        <tr><th>Article</th><th>Category</th><th>Published</th><th>Status</th><th style="text-align:right;">Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($news as $article)
                            <tr>
                                <td>
                                    <div class="b-user-cell">
                                        <span class="b-avatar" style="border-radius:10px;">
                                            @if ($article->featured_image)
                                                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}">
                                            @else
                                                <i class="bi bi-newspaper"></i>
                                            @endif
                                        </span>
                                        <strong>{{ \Illuminate\Support\Str::limit($article->title, 50) }}</strong>
                                    </div>
                                </td>
                                <td><span class="badge">{{ $article->category }}</span></td>
                                <td>{{ optional($article->published_at)->format('d M Y') ?? '—' }}</td>
                                <td><span class="badge {{ $article->is_published ? 'badge-success' : 'badge-muted' }}">{{ $article->is_published ? 'Published' : 'Draft' }}</span></td>
                                <td>
                                    <div class="cell-actions" style="justify-content:flex-end;">
                                        <a href="{{ route('news.show', $article) }}" target="_blank" class="b-btn b-btn-outline b-btn-sm">Preview</a>
                                        <form method="POST" action="{{ route('admin.news.publish', $article) }}">
                                            @csrf
                                            <button class="b-btn b-btn-sm {{ $article->is_published ? 'b-btn-outline' : 'b-btn-success' }}">{{ $article->is_published ? 'Unpublish' : 'Publish' }}</button>
                                        </form>
                                        <a href="{{ route('admin.news.edit', $article) }}" class="b-btn b-btn-outline b-btn-sm">Edit</a>
                                        <form method="POST" action="{{ route('admin.news.destroy', $article) }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="b-btn b-btn-danger b-btn-sm" data-confirm="Delete this news article?">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding:18px 22px;">
                {{ $news->links() }}
            </div>
        @endif
    </div>
@endsection
