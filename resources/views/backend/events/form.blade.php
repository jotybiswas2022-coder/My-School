@extends('backend.layouts.app')

@section('title', $event->exists ? 'Edit Event' : 'Add Event')

@section('content')
    @php $editing = $event->exists; @endphp

    <div class="page-header">
        <div>
            <h1>{{ $editing ? 'Edit Event' : 'Add Event' }}</h1>
            <p>{{ $editing ? 'Update this event.' : 'Create a new school event.' }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.events.index') }}" class="b-btn b-btn-outline">← Back</a>
        </div>
    </div>

    <form method="POST" action="{{ $editing ? route('admin.events.update', $event) : route('admin.events.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="b-grid" style="grid-template-columns:1fr 320px;align-items:start;">
            <div class="b-card">
                <div class="b-card-head"><h3>Event Details</h3></div>
                <div class="b-card-body">
                    <div class="form-row">
                        <label for="title">Event Title <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $event->title) }}" required>
                        @error('title')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-grid">
                        <div class="form-row">
                            <label for="event_date">Event Date <span style="color:var(--danger);">*</span></label>
                            <input type="date" name="event_date" id="event_date" class="form-control @error('event_date') is-invalid @enderror" value="{{ old('event_date', optional($event->event_date)->format('Y-m-d')) }}" required>
                            @error('event_date')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-row">
                            <label for="event_time">Event Time</label>
                            <input type="time" name="event_time" id="event_time" class="form-control" value="{{ old('event_time', $event->event_time ? \Illuminate\Support\Str::substr($event->event_time, 0, 5) : '') }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <label for="location">Location</label>
                        <input type="text" name="location" id="location" class="form-control" value="{{ old('location', $event->location) }}" placeholder="e.g. Main Auditorium">
                    </div>
                    <div class="form-row">
                        <label for="description">Description <span style="color:var(--danger);">*</span></label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" style="min-height:200px;" required>{{ old('description', $event->description) }}</textarea>
                        @error('description')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:20px;">
                <div class="b-card">
                    <div class="b-card-head"><h3>Event Image</h3></div>
                    <div class="b-card-body">
                        @if ($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="Event image" style="width:100%;border-radius:var(--radius-sm);margin-bottom:14px;">
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <div class="form-hint">JPG, PNG, WEBP or SVG. Max 4MB.</div>
                        @error('image')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="b-card">
                    <div class="b-card-body">
                        <label class="checkbox-row">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $event->is_published))> Published
                        </label>
                    </div>
                </div>

                <div class="b-card">
                    <div class="b-card-body">
                        <button type="submit" class="b-btn b-btn-primary b-btn-block">{{ $editing ? 'Update Event' : 'Create Event' }}</button>
                        <a href="{{ route('admin.events.index') }}" class="b-btn b-btn-outline b-btn-block" style="margin-top:10px;">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <style>
        @media (max-width: 900px) { .b-grid[style*="320px"] { grid-template-columns: 1fr !important; } }
    </style>
@endsection
