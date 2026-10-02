@extends('backend.layouts.app')

@section('title', $notice->exists ? 'Edit Notice' : 'Add Notice')

@section('content')
    @php $editing = $notice->exists; @endphp

    <div class="page-header">
        <div>
            <h1>{{ $editing ? 'Edit Notice' : 'Add Notice' }}</h1>
            <p>{{ $editing ? 'Update this notice.' : 'Publish a new announcement.' }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.notices.index') }}" class="b-btn b-btn-outline"><i class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>

    <form method="POST" action="{{ $editing ? route('admin.notices.update', $notice) : route('admin.notices.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="b-grid" style="grid-template-columns:1fr 320px;align-items:start;">
            <div class="b-card">
                <div class="b-card-head"><h3>Notice Content</h3></div>
                <div class="b-card-body">
                    <div class="form-row">
                        <label for="title">Title <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $notice->title) }}" required>
                        @error('title')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <label for="description">Description <span style="color:var(--danger);">*</span></label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" style="min-height:240px;" required>{{ old('description', $notice->description) }}</textarea>
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
                                    <option value="{{ $category }}" @selected(old('category', $notice->category) === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-row">
                            <label for="published_at">Publish Date</label>
                            <input type="datetime-local" name="published_at" id="published_at" class="form-control"
                                   value="{{ old('published_at', optional($notice->published_at)->format('Y-m-d\TH:i')) }}">
                        </div>
                        <label class="checkbox-row">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $notice->is_published))> Published
                        </label>
                    </div>
                </div>

                <div class="b-card">
                    <div class="b-card-head"><h3>Attachment</h3></div>
                    <div class="b-card-body">
                        @if ($notice->attachment)
                            <a href="{{ asset('storage/' . $notice->attachment) }}" target="_blank" rel="noopener" class="b-btn b-btn-outline b-btn-sm b-btn-block" style="margin-bottom:12px;">View current file</a>
                        @endif
                        <input type="file" name="attachment" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx">
                        <div class="form-hint">Images or documents. Max 5MB.</div>
                        @error('attachment')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="b-card">
                    <div class="b-card-body">
                        <button type="submit" class="b-btn b-btn-primary b-btn-block">{{ $editing ? 'Update Notice' : 'Create Notice' }}</button>
                        <a href="{{ route('admin.notices.index') }}" class="b-btn b-btn-outline b-btn-block" style="margin-top:10px;">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <style>
        @media (max-width: 900px) { .b-grid[style*="320px"] { grid-template-columns: 1fr !important; } }
    </style>
@endsection
