@extends('backend.layouts.app')

@section('title', 'Events')

@section('content')
    <div class="page-header">
        <div>
            <h1>Events</h1>
            <p>Manage school events, dates and locations.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.events.create') }}" class="b-btn b-btn-primary">+ Add Event</a>
        </div>
    </div>

    <form method="GET" class="filter-bar">
        <div class="filter-grid">
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Search</label>
                <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Event title">
            </div>
            <div style="display:flex;gap:8px;">
                <button class="b-btn b-btn-primary">Filter</button>
                <a href="{{ route('admin.events.index') }}" class="b-btn b-btn-outline">Reset</a>
            </div>
        </div>
    </form>

    <div class="b-card">
        @if ($events->isEmpty())
            <div class="b-empty">
                <div class="b-empty-ico"><i class="bi bi-calendar-event"></i></div>
                <h3>No events yet</h3>
                <p style="font-size:.86rem;">Create your first event to show it on the website.</p>
            </div>
        @else
            <div class="b-table-wrap">
                <table class="b-table">
                    <thead>
                        <tr><th>Event</th><th>Date</th><th>Time</th><th>Location</th><th>Status</th><th style="text-align:right;">Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr>
                                <td>
                                    <div class="b-user-cell">
                                        <span class="b-avatar" style="border-radius:10px;">
                                            @if ($event->image)
                                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}">
                                            @else
                                                <i class="bi bi-calendar-event"></i>
                                            @endif
                                        </span>
                                        <strong>{{ \Illuminate\Support\Str::limit($event->title, 45) }}</strong>
                                    </div>
                                </td>
                                <td>{{ $event->event_date->format('d M Y') }}</td>
                                <td>{{ $event->event_time ? \Illuminate\Support\Str::substr($event->event_time, 0, 5) : '—' }}</td>
                                <td>{{ $event->location ?? '—' }}</td>
                                <td><span class="badge {{ $event->is_published ? 'badge-success' : 'badge-muted' }}">{{ $event->is_published ? 'Published' : 'Draft' }}</span></td>
                                <td>
                                    <div class="cell-actions" style="justify-content:flex-end;">
                                        <form method="POST" action="{{ route('admin.events.publish', $event) }}">
                                            @csrf
                                            <button class="b-btn b-btn-sm {{ $event->is_published ? 'b-btn-outline' : 'b-btn-success' }}">{{ $event->is_published ? 'Unpublish' : 'Publish' }}</button>
                                        </form>
                                        <a href="{{ route('admin.events.edit', $event) }}" class="b-btn b-btn-outline b-btn-sm">Edit</a>
                                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="b-btn b-btn-danger b-btn-sm" data-confirm="Delete this event?">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding:18px 22px;">
                {{ $events->links() }}
            </div>
        @endif
    </div>
@endsection
