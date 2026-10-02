@extends('backend.layouts.app')

@section('title', 'Gallery')

@section('content')
    <div class="page-header">
        <div>
            <h1>Gallery</h1>
            <p>Organise photo albums for campus life, events and activities.</p>
        </div>
        <div class="page-actions">
            <button type="button" class="b-btn b-btn-primary" data-modal-open="albumModal">+ Add Album</button>
        </div>
    </div>

    <form method="GET" class="filter-bar">
        <div class="filter-grid">
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Search</label>
                <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Album title">
            </div>
            <div style="display:flex;gap:8px;">
                <button class="b-btn b-btn-primary">Filter</button>
                <a href="{{ route('admin.gallery.index') }}" class="b-btn b-btn-outline">Reset</a>
            </div>
        </div>
    </form>

    @if ($albums->isEmpty())
        <div class="b-card">
            <div class="b-empty">
                <div class="b-empty-ico">🖼</div>
                <h3>No albums yet</h3>
                <p style="font-size:.86rem;">Create an album to start uploading photos.</p>
                <button type="button" class="b-btn b-btn-primary" style="margin-top:16px;" data-modal-open="albumModal">+ Add Album</button>
            </div>
        </div>
    @else
        <div class="b-grid b-grid-3">
            @foreach ($albums as $item)
                <div class="b-card">
                    <div style="aspect-ratio:16/10;background:linear-gradient(135deg,#DBEAFE,#BFDBFE);display:grid;place-items:center;font-size:2.2rem;overflow:hidden;">
                        @if ($item->cover_image)
                            <img src="{{ asset('storage/' . $item->cover_image) }}" alt="{{ $item->title }}" style="width:100%;height:100%;object-fit:cover;">
                        @elseif ($item->images->first())
                            <img src="{{ asset('storage/' . $item->images->first()->image) }}" alt="{{ $item->title }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            🖼
                        @endif
                    </div>
                    <div class="b-card-body">
                        <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;">
                            <span class="badge">{{ $item->category }}</span>
                            <span style="font-size:.76rem;color:var(--muted);">{{ $item->images_count }} photos</span>
                        </div>
                        <strong style="display:block;margin-bottom:12px;">{{ $item->title }}</strong>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.gallery.show', $item) }}" class="b-btn b-btn-primary b-btn-sm">Manage</a>
                            <form method="POST" action="{{ route('admin.gallery.destroy', $item) }}">
                                @csrf @method('DELETE')
                                <button type="button" class="b-btn b-btn-danger b-btn-sm" data-confirm="Delete album {{ $item->title }} and all its images?">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top:24px;">
            {{ $albums->links() }}
        </div>
    @endif

    {{-- Add album modal --}}
    <div class="b-modal" id="albumModal">
        <div class="b-modal-box">
            <div class="b-modal-head">
                <h3>Create Album</h3>
                <button type="button" class="b-modal-close" data-modal-close>✕</button>
            </div>
            <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="b-modal-body">
                    <div class="form-row">
                        <label for="title">Album Title <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="form-row">
                        <label for="category">Category <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="category" id="category" class="form-control" value="General" required>
                    </div>
                    <div class="form-row">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" style="min-height:90px;"></textarea>
                    </div>
                    <div class="form-row">
                        <label for="cover_image">Cover Image</label>
                        <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="b-modal-foot">
                    <button type="button" class="b-btn b-btn-outline" data-modal-close>Cancel</button>
                    <button type="submit" class="b-btn b-btn-primary">Create Album</button>
                </div>
            </form>
        </div>
    </div>
@endsection
