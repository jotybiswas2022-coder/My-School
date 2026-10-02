@extends('backend.layouts.app')

@section('title', 'Manage Exam')

@section('content')
    <div class="page-header">
        <div>
            <h1>{{ $exam->name }}</h1>
            <p>
                {{ $exam->exam_type ?? 'Exam' }}
                @if ($exam->academicSession) · {{ $exam->academicSession->name }} @endif
                · <span style="color:{{ $exam->is_published ? 'var(--success)' : 'var(--muted)' }};font-weight:600;">{{ $exam->is_published ? 'Results published' : 'Results draft' }}</span>
            </p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.exams.index') }}" class="b-btn b-btn-outline">← Back</a>
            <a href="{{ route('admin.results.create', ['exam_id' => $exam->id]) }}" class="b-btn b-btn-primary">Enter Marks</a>
            <form method="POST" action="{{ route('admin.exams.publish', $exam) }}">
                @csrf
                <button class="b-btn {{ $exam->is_published ? 'b-btn-outline' : 'b-btn-success' }}">
                    {{ $exam->is_published ? 'Unpublish Results' : 'Publish Results' }}
                </button>
            </form>
        </div>
    </div>

    <div class="b-grid" style="grid-template-columns:1fr 380px;align-items:start;">
        <div class="b-card">
            <div class="b-card-head"><h3>Exam Subjects &amp; Schedule</h3></div>

            @if ($exam->examSubjects->isEmpty())
                <div class="b-empty">
                    <div class="b-empty-ico">◈</div>
                    <h3>No subjects scheduled</h3>
                    <p style="font-size:.86rem;">Assign subjects using the form to build the exam schedule.</p>
                </div>
            @else
                <div class="b-table-wrap">
                    <table class="b-table">
                        <thead>
                            <tr><th>Subject</th><th>Class</th><th>Date</th><th>Full</th><th>Pass</th><th style="text-align:right;">Action</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($exam->examSubjects as $examSubject)
                                <tr>
                                    <td><strong>{{ $examSubject->subject->name ?? '—' }}</strong><div style="font-size:.74rem;color:var(--muted);">{{ $examSubject->subject->code ?? '' }}</div></td>
                                    <td>{{ $examSubject->schoolClass->name ?? 'All' }}</td>
                                    <td>{{ optional($examSubject->exam_date)->format('d M Y') ?? '—' }}</td>
                                    <td>{{ $examSubject->full_marks }}</td>
                                    <td>{{ $examSubject->pass_marks }}</td>
                                    <td style="text-align:right;">
                                        <form method="POST" action="{{ route('admin.exam-subjects.destroy', $examSubject) }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="b-btn b-btn-danger b-btn-sm" data-confirm="Remove this subject from the exam?">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="b-card">
            <div class="b-card-head"><h3>Add Exam Subject</h3></div>
            <div class="b-card-body">
                <form method="POST" action="{{ route('admin.exams.subjects.store', $exam) }}">
                    @csrf
                    <div class="form-row">
                        <label for="subject_id">Subject <span style="color:var(--danger);">*</span></label>
                        <select name="subject_id" id="subject_id" class="form-select" required>
                            <option value="">Select subject</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-row">
                        <label for="class_id">Class</label>
                        <select name="class_id" id="class_id" class="form-select">
                            <option value="">All classes</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-row">
                        <label for="exam_date">Exam Date</label>
                        <input type="date" name="exam_date" id="exam_date" class="form-control">
                    </div>
                    <div class="form-grid">
                        <div class="form-row">
                            <label for="full_marks">Full Marks <span style="color:var(--danger);">*</span></label>
                            <input type="number" name="full_marks" id="full_marks" class="form-control" value="100" min="1" required>
                        </div>
                        <div class="form-row">
                            <label for="pass_marks">Pass Marks <span style="color:var(--danger);">*</span></label>
                            <input type="number" name="pass_marks" id="pass_marks" class="form-control" value="33" min="0" required>
                            @error('pass_marks')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <button class="b-btn b-btn-primary b-btn-block">Save Exam Subject</button>
                </form>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 900px) { .b-grid[style*="380px"] { grid-template-columns: 1fr !important; } }
    </style>
@endsection
