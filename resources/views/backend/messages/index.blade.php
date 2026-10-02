@extends('backend.layouts.app')

@section('title', 'Messages')

@section('content')
    <div class="page-header">
        <div>
            <h1>Contact Messages</h1>
            <p>Messages submitted through the website contact form.</p>
        </div>
        @if ($unread > 0)
            <div class="page-actions">
                <span class="badge badge-warning">{{ $unread }} unread</span>
            </div>
        @endif
    </div>

    <form method="GET" class="filter-bar">
        <div class="filter-grid">
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Search</label>
                <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Name, email or subject">
            </div>
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Status</label>
                <select name="status" class="form-select">
                    <option value="">All messages</option>
                    <option value="unread" @selected(request('status') === 'unread')>Unread</option>
                    <option value="read" @selected(request('status') === 'read')>Read</option>
                </select>
            </div>
            <div style="display:flex;gap:8px;">
                <button class="b-btn b-btn-primary">Filter</button>
                <a href="{{ route('admin.messages.index') }}" class="b-btn b-btn-outline">Reset</a>
            </div>
        </div>
    </form>

    <div class="b-card">
        @if ($messages->isEmpty())
            <div class="b-empty">
                <div class="b-empty-ico">✉</div>
                <h3>No messages found</h3>
                <p style="font-size:.86rem;">Messages from the website contact form will appear here.</p>
            </div>
        @else
            <div class="b-table-wrap">
                <table class="b-table">
                    <thead>
                        <tr><th>From</th><th>Subject</th><th>Received</th><th>Status</th><th style="text-align:right;">Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $message)
                            <tr style="{{ $message->is_read ? '' : 'background:#FFFBEB;' }}">
                                <td>
                                    <strong>{{ $message->name }}</strong>
                                    <div style="font-size:.74rem;color:var(--muted);">{{ $message->email }}</div>
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($message->subject, 45) }}</td>
                                <td>{{ $message->created_at->format('d M Y, h:i A') }}</td>
                                <td>
                                    <span class="badge {{ $message->is_read ? 'badge-muted' : 'badge-warning' }}">
                                        {{ $message->is_read ? 'Read' : 'Unread' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="cell-actions" style="justify-content:flex-end;">
                                        <a href="{{ route('admin.messages.show', $message) }}" class="b-btn b-btn-outline b-btn-sm">View</a>
                                        <form method="POST" action="{{ route('admin.messages.read', $message) }}">
                                            @csrf
                                            <button class="b-btn b-btn-outline b-btn-sm">{{ $message->is_read ? 'Mark Unread' : 'Mark Read' }}</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.messages.destroy', $message) }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="b-btn b-btn-danger b-btn-sm" data-confirm="Delete this message?">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding:18px 22px;">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
@endsection
