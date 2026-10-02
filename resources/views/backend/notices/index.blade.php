@extends('backend.layouts.app')

@section('title', 'Notices')

@section('content')
    <div class="page-header">
        <div>
            <h1>Notices</h1>
            <p>Create and publish announcements for the school community.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.notices.create') }}" class="b-btn b-btn-primary">+ Add Notice</a>
        </div>
    </div>

    <form method="GET" class="filter-bar">
        <div class="filter-grid">
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Search</label>
                <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Notice title">
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
                <a href="{{ route('admin.notices.index') }}" class="b-btn b-btn-outline">Reset</a>
            </div>
        </div>
    </form>

    <div class="b-card">
        @if ($notices->isEmpty())
            <div class="b-empty">
                <div class="b-empty-ico"><i class="bi bi-megaphone"></i></div>
                <h3>No notices yet</h3>
                <p style="font-size:.86rem;">Create your first notice to keep everyone informed.</p>
            </div>
        @else
            <div class="b-table-wrap">
                <table class="b-table">
                    <thead>
                        <tr><th>Title</th><th>Category</th><th>Published</th><th>Status</th><th style="text-align:right;">Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($notices as $notice)
                            <tr>
                                <td>
                                    <strong>{{ \Illuminate\Support\Str::limit($notice->title, 55) }}</strong>
                                    <div style="font-size:.74rem;color:var(--muted);">{{ $notice->author->name ?? 'System' }}</div>
                                </td>
                                <td><span class="badge">{{ $notice->category }}</span></td>
                                <td>{{ optional($notice->published_at)->format('d M Y') ?? '—' }}</td>
                                <td><span class="badge {{ $notice->is_published ? 'badge-success' : 'badge-muted' }}">{{ $notice->is_published ? 'Published' : 'Draft' }}</span></td>
                                <td>
                                    <div class="cell-actions" style="justify-content:flex-end;">
                                        <a href="{{ route('notices.show', $notice) }}" target="_blank" class="b-btn b-btn-outline b-btn-sm">Preview</a>
                                        <form method="POST" action="{{ route('admin.notices.publish', $notice) }}">
                                            @csrf
                                            <button class="b-btn b-btn-sm {{ $notice->is_published ? 'b-btn-outline' : 'b-btn-success' }}">{{ $notice->is_published ? 'Unpublish' : 'Publish' }}</button>
                                        </form>
                                        <a href="{{ route('admin.notices.edit', $notice) }}" class="b-btn b-btn-outline b-btn-sm">Edit</a>
                                        <form method="POST" action="{{ route('admin.notices.destroy', $notice) }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="b-btn b-btn-danger b-btn-sm" data-confirm="Delete this notice?">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding:18px 22px;">
                {{ $notices->links() }}
            </div>
        @endif
    </div>
@endsection
