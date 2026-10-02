@extends('backend.layouts.app')

@section('title', 'Teachers & Staff')

@section('content')
    <div class="page-header">
        <div>
            <h1>Teachers &amp; Staff</h1>
            <p>Manage teaching staff, departments and qualifications.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.teachers.create') }}" class="b-btn b-btn-primary">+ Add Teacher</a>
        </div>
    </div>

    <form method="GET" class="filter-bar">
        <div class="filter-grid">
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Search</label>
                <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Name, designation or qualification">
            </div>
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Department</label>
                <select name="department" class="form-select">
                    <option value="">All departments</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department }}" @selected(request('department') === $department)>{{ $department }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:8px;">
                <button class="b-btn b-btn-primary">Filter</button>
                <a href="{{ route('admin.teachers.index') }}" class="b-btn b-btn-outline">Reset</a>
            </div>
        </div>
    </form>

    <div class="b-card">
        @if ($teachers->isEmpty())
            <div class="b-empty">
                <div class="b-empty-ico"><i class="bi bi-person-badge"></i></div>
                <h3>No teachers found</h3>
                <p style="font-size:.86rem;">Add your first teacher to get started.</p>
            </div>
        @else
            <div class="b-table-wrap">
                <table class="b-table">
                    <thead>
                        <tr>
                            <th>Teacher</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Subjects</th>
                            <th>Status</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teachers as $teacher)
                            <tr>
                                <td>
                                    <div class="b-user-cell">
                                        <span class="b-avatar">
                                            @if ($teacher->photo)
                                                <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->name }}">
                                            @else
                                                {{ $teacher->initials() }}
                                            @endif
                                        </span>
                                        <div>
                                            <strong>{{ $teacher->name }}</strong>
                                            <div style="font-size:.74rem;color:var(--muted);">{{ $teacher->email ?? '—' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $teacher->designation ?? '—' }}</td>
                                <td>{{ $teacher->department ?? '—' }}</td>
                                <td>{{ $teacher->subjects_count }}</td>
                                <td>
                                    <span class="badge {{ $teacher->is_active ? 'badge-success' : 'badge-danger' }}">
                                        {{ $teacher->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    @if ($teacher->is_featured)
                                        <span class="badge" style="margin-left:4px;">Featured</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="cell-actions" style="justify-content:flex-end;">
                                        <a href="{{ route('admin.teachers.show', $teacher) }}" class="b-btn b-btn-outline b-btn-sm">View</a>
                                        <a href="{{ route('admin.teachers.edit', $teacher) }}" class="b-btn b-btn-outline b-btn-sm">Edit</a>
                                        <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="b-btn b-btn-danger b-btn-sm"
                                                    data-confirm="Delete {{ $teacher->name }}? Their assigned subjects will be unassigned.">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding:18px 22px;">
                {{ $teachers->links() }}
            </div>
        @endif
    </div>
@endsection
