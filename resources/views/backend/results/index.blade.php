@extends('backend.layouts.app')

@section('title', 'Results')

@section('content')
    <div class="page-header">
        <div>
            <h1>Results</h1>
            <p>Review, edit and publish examination results.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.results.create') }}" class="b-btn b-btn-primary">+ Enter Marks</a>
        </div>
    </div>

    <form method="GET" class="filter-bar">
        <div class="filter-grid">
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Search student</label>
                <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Name or student ID">
            </div>
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Exam</label>
                <select name="exam_id" class="form-select">
                    <option value="">All exams</option>
                    @foreach ($exams as $exam)
                        <option value="{{ $exam->id }}" @selected(request('exam_id') == $exam->id)>{{ $exam->name }}</option>
                    @endforeach
                </select>
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
                <a href="{{ route('admin.results.index') }}" class="b-btn b-btn-outline">Reset</a>
            </div>
        </div>
    </form>

    <div class="b-card" style="margin-bottom:20px;">
        <div class="b-card-head"><h3>Publish Results</h3></div>
        <div class="b-card-body">
            <form method="POST" action="{{ route('admin.results.publish') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
                @csrf
                <div style="min-width:240px;">
                    <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Exam</label>
                    <select name="exam_id" class="form-select" required>
                        <option value="">Select exam</option>
                        @foreach ($exams as $exam)
                            <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="min-width:160px;">
                    <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Action</label>
                    <select name="publish" class="form-select">
                        <option value="1">Publish</option>
                        <option value="0">Unpublish</option>
                    </select>
                </div>
                <button class="b-btn b-btn-primary">Apply</button>
            </form>
        </div>
    </div>

    <div class="b-card">
        @if ($results->isEmpty())
            <div class="b-empty">
                <div class="b-empty-ico">★</div>
                <h3>No results found</h3>
                <p style="font-size:.86rem;">Use the marks entry screen to record results.</p>
            </div>
        @else
            <div class="b-table-wrap">
                <table class="b-table">
                    <thead>
                        <tr><th>Student</th><th>Class</th><th>Exam</th><th>Subject</th><th>Marks</th><th>Grade</th><th>GPA</th><th>Status</th><th style="text-align:right;">Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $result)
                            <tr>
                                <td>
                                    <strong>{{ $result->student->name ?? '—' }}</strong>
                                    <div style="font-size:.74rem;color:var(--muted);">{{ $result->student->student_id ?? '' }}</div>
                                </td>
                                <td>{{ $result->student->schoolClass->name ?? '—' }}</td>
                                <td>{{ $result->exam->name ?? '—' }}</td>
                                <td>{{ $result->subject->name ?? '—' }}</td>
                                <td>{{ rtrim(rtrim(number_format($result->marks, 2), '0'), '.') }} / {{ $result->full_marks }}</td>
                                <td><strong style="color:{{ \App\Models\Result::gradeColor($result->grade) }};">{{ $result->grade }}</strong></td>
                                <td>{{ number_format($result->gpa, 2) }}</td>
                                <td><span class="badge {{ $result->is_published ? 'badge-success' : 'badge-muted' }}">{{ $result->is_published ? 'Published' : 'Draft' }}</span></td>
                                <td>
                                    <div class="cell-actions" style="justify-content:flex-end;">
                                        <a href="{{ route('admin.results.edit', $result) }}" class="b-btn b-btn-outline b-btn-sm">Edit</a>
                                        <form method="POST" action="{{ route('admin.results.destroy', $result) }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="b-btn b-btn-danger b-btn-sm" data-confirm="Delete this result?">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding:18px 22px;">
                {{ $results->links() }}
            </div>
        @endif
    </div>
@endsection
