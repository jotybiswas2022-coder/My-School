@extends('backend.layouts.app')

@section('title', 'Academic Sessions')

@section('content')
    <div class="page-header">
        <div>
            <h1>Academic Sessions</h1>
            <p>Define academic years and mark the current session.</p>
        </div>
    </div>

    <div class="b-grid" style="grid-template-columns:1fr 360px;align-items:start;">
        <div class="b-card">
            <div class="b-card-head"><h3>All Sessions</h3></div>

            @if ($sessions->isEmpty())
                <div class="b-empty">
                    <div class="b-empty-ico"><i class="bi bi-calendar-range"></i></div>
                    <h3>No academic sessions</h3>
                    <p style="font-size:.86rem;">Create a session such as "2025-2026" to get started.</p>
                </div>
            @else
                <div class="b-table-wrap">
                    <table class="b-table">
                        <thead>
                            <tr><th>Session</th><th>Start</th><th>End</th><th>Exams</th><th>Current</th><th style="text-align:right;">Actions</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($sessions as $item)
                                <tr>
                                    <td><strong>{{ $item->name }}</strong></td>
                                    <td>{{ optional($item->start_date)->format('d M Y') ?? '—' }}</td>
                                    <td>{{ optional($item->end_date)->format('d M Y') ?? '—' }}</td>
                                    <td>{{ $item->exams_count }}</td>
                                    <td>
                                        <span class="badge {{ $item->is_current ? 'badge-success' : 'badge-muted' }}">
                                            {{ $item->is_current ? 'Current' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="cell-actions" style="justify-content:flex-end;">
                                            <form method="POST" action="{{ route('admin.sessions.update', $item) }}">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="name" value="{{ $item->name }}">
                                                <input type="hidden" name="start_date" value="{{ optional($item->start_date)->format('Y-m-d') }}">
                                                <input type="hidden" name="end_date" value="{{ optional($item->end_date)->format('Y-m-d') }}">
                                                <input type="hidden" name="is_current" value="1">
                                                <button class="b-btn b-btn-outline b-btn-sm" @disabled($item->is_current)>Set Current</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.sessions.destroy', $item) }}">
                                                @csrf @method('DELETE')
                                                <button type="button" class="b-btn b-btn-danger b-btn-sm"
                                                        data-confirm="Delete session {{ $item->name }}?">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="b-card">
            <div class="b-card-head"><h3>Add Session</h3></div>
            <div class="b-card-body">
                <form method="POST" action="{{ route('admin.sessions.store') }}">
                    @csrf
                    <div class="form-row">
                        <label for="name">Session Name <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. 2026-2027" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}">
                    </div>
                    <div class="form-row">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}">
                    </div>
                    <label class="checkbox-row" style="margin-bottom:16px;">
                        <input type="checkbox" name="is_current" value="1"> Set as current session
                    </label>
                    <button class="b-btn b-btn-primary b-btn-block">Create Session</button>
                </form>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 900px) { .b-grid[style*="360px"] { grid-template-columns: 1fr !important; } }
    </style>
@endsection
