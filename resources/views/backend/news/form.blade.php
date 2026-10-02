@extends('backend.layouts.app')

@section('title', $article->exists ? 'Edit News' : 'Add News')

@section('content')
    @php $editing = $article->exists; @endphp

    <div class="page-header">
        <div>
            <h1>{{ $editing ? 'Edit News Article' : 'Add News Article' }}</h1>
            <p>{{ $editing ? 'Update this article.' : 'Publish a new news article.' }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.news.index') }}" class="b-btn b-btn-outline"><i class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>

    <form method="POST" action="{{ $editing ? route('admin.news.update', $article) : route('admin.news.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="b-grid" style="grid-template-columns:1fr 320px;align-items:start;">
            <div class="b-card">
                <div class="b-card-head"><h3>Article Content</h3></div>
                <div class="b-card-body">
                    <div class="form-row">
                        <label for="title">Title <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $article->title) }}" required>
                        @error('title')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <label for="description">Article Body <span style="color:var(--danger);">*</span></label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" style="min-height:280px;" required>{{ old('description', $article->description) }}</textarea>
                        @error('description')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:20px;">
                <div class="b-card">
                    <div class="b-card-head"><h3>Publishing</h3></div>
                    <div class="b-card-body">
                        <div class="form-row">
                            <label for="category">Category <span style="color:var(--danger);">*</span></label>
                            <select name="category" id="category" class="form-select" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" @selected(old('category', $article->category) === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-row">
                            <label for="published_at">Publish Date</label>
                            <input type="datetime-local" name="published_at" id="published_at" class="form-control"
                                   value="{{ old('published_at', optional($article->published_at)->format('Y-m-d\TH:i')) }}">
                        </div>
                        <label class="checkbox-row">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $article->is_published))> Published
                        </label>
                    </div>
                </div>

                <div class="b-card">
                    <div class="b-card-head"><h3>Featured Image</h3></div>
                    <div class="b-card-body">
                        @if ($article->featured_image)
                            <img src="{{ asset('storage/' . $article->featured_image) }}" alt="Featured image" style="width:100%;border-radius:var(--radius-sm);margin-bottom:14px;">
                        @endif
                        <input type="file" name="featured_image" class="form-control" accept="image/*">
                        <div class="form-hint">JPG, PNG, WEBP or SVG. Max 4MB.</div>
                        @error('featured_image')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="b-card">
                    <div class="b-card-body">
                        <button type="submit" class="b-btn b-btn-primary b-btn-block">{{ $editing ? 'Update Article' : 'Create Article' }}</button>
                        <a href="{{ route('admin.news.index') }}" class="b-btn b-btn-outline b-btn-block" style="margin-top:10px;">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <style>
        @media (max-width: 900px) { .b-grid[style*="320px"] { grid-template-columns: 1fr !important; } }
    </style>
@endsection
