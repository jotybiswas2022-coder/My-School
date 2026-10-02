@extends('backend.layouts.app')

@section('title', 'Subjects')

@section('content')
    <div class="page-header">
        <div>
            <h1>Subjects</h1>
            <p>Manage the subjects offered across all classes.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.subjects.create') }}" class="b-btn b-btn-primary">+ Add Subject</a>
        </div>
    </div>

    <form method="GET" class="filter-bar">
        <div class="filter-grid">
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Search</label>
                <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Subject name or code">
            </div>
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Class</label>
                <select name="class_id" class="form-select">
                    <option value="">All classes</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:8px;">
                <button class="b-btn b-btn-primary">Filter</button>
                <a href="{{ route('admin.subjects.index') }}" class="b-btn b-btn-outline">Reset</a>
            </div>
        </div>
    </form>

    <div class="b-card">
        @if ($subjects->isEmpty())
            <div class="b-empty">
                <div class="b-empty-ico"><i class="bi bi-book"></i></div>
                <h3>No subjects found</h3>
                <p style="font-size:.86rem;">Add subjects and assign them to classes and teachers.</p>
            </div>
        @else
            <div class="b-table-wrap">
                <table class="b-table">
                    <thead>
                        <tr><th>Subject</th><th>Code</th><th>Class</th><th>Teacher</th><th style="text-align:right;">Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($subjects as $subject)
                            <tr>
                                <td><strong>{{ $subject->name }}</strong></td>
                                <td><span class="badge">{{ $subject->code }}</span></td>
                                <td>{{ $subject->schoolClass->name ?? '—' }}</td>
                                <td>{{ $subject->teacher->name ?? '—' }}</td>
                                <td>
                                    <div class="cell-actions" style="justify-content:flex-end;">
                                        <a href="{{ route('admin.subjects.edit', $subject) }}" class="b-btn b-btn-outline b-btn-sm">Edit</a>
                                        <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="b-btn b-btn-danger b-btn-sm"
                                                    data-confirm="Delete {{ $subject->name }}? Related results will be removed.">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding:18px 22px;">
                {{ $subjects->links() }}
            </div>
        @endif
    </div>
@endsection
