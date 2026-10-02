@extends('backend.layouts.app')

@section('title', 'Manage Album')

@section('content')
    <div class="page-header">
        <div>
            <h1>{{ $album->title }}</h1>
            <p>{{ $album->category }} · {{ $album->images->count() }} photos</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.gallery.index') }}" class="b-btn b-btn-outline"><i class="bi bi-arrow-left"></i> Back</a>
            <a href="{{ route('gallery.show', $album) }}" target="_blank" class="b-btn b-btn-outline">Preview</a>
        </div>
    </div>

    <div class="b-grid" style="grid-template-columns:1fr 340px;align-items:start;">
        <div class="b-card">
            <div class="b-card-head"><h3>Album Images</h3></div>

            @if ($album->images->isEmpty())
                <div class="b-empty">
                    <div class="b-empty-ico"><i class="bi bi-images"></i></div>
                    <h3>No images uploaded</h3>
                    <p style="font-size:.86rem;">Use the upload panel to add photos to this album.</p>
                </div>
            @else
                <div class="b-card-body">
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:14px;">
                        @foreach ($album->images as $image)
                            <div style="border:1px solid var(--border);border-radius:var(--radius-sm);overflow:hidden;">
                                <div style="aspect-ratio:1/1;background:#EFF6FF;overflow:hidden;">
                                    <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->caption }}" style="width:100%;height:100%;object-fit:cover;">
                                </div>
                                <div style="padding:10px;">
                                    <div style="font-size:.74rem;color:var(--muted);margin-bottom:8px;min-height:30px;">{{ $image->caption ?: 'No caption' }}</div>
                                    <form method="POST" action="{{ route('admin.gallery.images.destroy', $image) }}">
                                        @csrf @method('DELETE')
                                        <button type="button" class="b-btn b-btn-danger b-btn-sm b-btn-block" data-confirm="Delete this image?">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="b-card">
                <div class="b-card-head"><h3>Upload Images</h3></div>
                <div class="b-card-body">
                    <form method="POST" action="{{ route('admin.gallery.images.store', $album) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <label for="images">Select Images <span style="color:var(--danger);">*</span></label>
                            <input type="file" name="images[]" id="images" class="form-control" accept="image/*" multiple required>
                            <div class="form-hint">You can select multiple images. Max 4MB each.</div>
                            @error('images')<div class="form-error">{{ $message }}</div>@enderror
                            @error('images.*')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-row">
                            <label for="caption">Caption (applied to all)</label>
                            <input type="text" name="caption" id="caption" class="form-control">
                        </div>
                        <button class="b-btn b-btn-primary b-btn-block">Upload Images</button>
                    </form>
                </div>
            </div>

            <div class="b-card">
                <div class="b-card-head"><h3>Album Settings</h3></div>
                <div class="b-card-body">
                    <form method="POST" action="{{ route('admin.gallery.update', $album) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="form-row">
                            <label for="title">Title <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $album->title }}" required>
                        </div>
                        <div class="form-row">
                            <label for="category">Category <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="category" id="category" class="form-control" value="{{ $album->category }}" required>
                        </div>
                        <div class="form-row">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" style="min-height:90px;">{{ $album->description }}</textarea>
                        </div>
                        <div class="form-row">
                            <label for="cover_image">Replace Cover Image</label>
                            <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*">
                        </div>
                        <button class="b-btn b-btn-primary b-btn-block">Update Album</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 900px) { .b-grid[style*="340px"] { grid-template-columns: 1fr !important; } }
    </style>
@endsection
