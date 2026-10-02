@extends('backend.layouts.app')

@section('title', 'Students')

@section('content')
    <div class="page-header">
        <div>
            <h1>Students</h1>
            <p>Manage student records, classes and enrolment.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.students.create') }}" class="b-btn b-btn-primary">+ Add Student</a>
        </div>
    </div>

    <form method="GET" class="filter-bar">
        <div class="filter-grid">
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Search</label>
                <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Name, ID or guardian">
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
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Section</label>
                <select name="section_id" class="form-select">
                    <option value="">All sections</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}" @selected(request('section_id') == $section->id)>
                            {{ $section->schoolClass->name ?? '' }} - {{ $section->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:8px;">
                <button class="b-btn b-btn-primary">Filter</button>
                <a href="{{ route('admin.students.index') }}" class="b-btn b-btn-outline">Reset</a>
            </div>
        </div>
    </form>

    <div class="b-card">
        @if ($students->isEmpty())
            <div class="b-empty">
                <div class="b-empty-ico">◉</div>
                <h3>No students found</h3>
                <p style="font-size:.86rem;">Add your first student or adjust the filters above.</p>
                <a href="{{ route('admin.students.create') }}" class="b-btn b-btn-primary" style="margin-top:16px;">+ Add Student</a>
            </div>
        @else
            <div class="b-table-wrap">
                <table class="b-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Student ID</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Guardian</th>
                            <th>Status</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr>
                                <td>
                                    <div class="b-user-cell">
                                        <span class="b-avatar">
                                            @if ($student->photo)
                                                <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}">
                                            @else
                                                {{ $student->initials() }}
                                            @endif
                                        </span>
                                        <div>
                                            <strong>{{ $student->name }}</strong>
                                            <div style="font-size:.74rem;color:var(--muted);">{{ $student->email ?? '—' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $student->student_id }}</td>
                                <td>{{ $student->schoolClass->name ?? '—' }}</td>
                                <td>{{ $student->section->name ?? '—' }}</td>
                                <td>{{ $student->guardian_name ?? '—' }}</td>
                                <td>
                                    <span class="badge {{ $student->is_active ? 'badge-success' : 'badge-danger' }}">
                                        {{ $student->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="cell-actions" style="justify-content:flex-end;">
                                        <a href="{{ route('admin.students.show', $student) }}" class="b-btn b-btn-outline b-btn-sm">View</a>
                                        <a href="{{ route('admin.students.edit', $student) }}" class="b-btn b-btn-outline b-btn-sm">Edit</a>
                                        <form method="POST" action="{{ route('admin.students.destroy', $student) }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="b-btn b-btn-danger b-btn-sm"
                                                    data-confirm="Delete {{ $student->name }}? This will also remove their attendance and results.">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding:18px 22px;">
                {{ $students->links() }}
            </div>
        @endif
    </div>
@endsection
