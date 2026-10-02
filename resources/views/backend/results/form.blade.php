@extends('backend.layouts.app')

@section('title', 'Marks Entry')

@section('content')
    <div class="page-header">
        <div>
            <h1>Marks Entry</h1>
            <p>Select an exam, class and subject to enter marks for all students.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.results.index') }}" class="b-btn b-btn-outline"><i class="bi bi-arrow-left"></i> Back to Results</a>
        </div>
    </div>

    <form method="GET" class="filter-bar">
        <div class="filter-grid">
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Exam</label>
                <select name="exam_id" class="form-select">
                    <option value="">Select exam</option>
                    @foreach ($exams as $exam)
                        <option value="{{ $exam->id }}" @selected($examId == $exam->id)>{{ $exam->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Class</label>
                <select name="class_id" class="form-select">
                    <option value="">Select class</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected($classId == $class->id)>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Subject</label>
                <select name="subject_id" class="form-select">
                    <option value="">Select subject</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected($subjectId == $subject->id)>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:8px;">
                <button class="b-btn b-btn-primary">Load Students</button>
            </div>
        </div>
    </form>

    @if (! $examId || ! $classId || ! $subjectId)
        <div class="b-card">
            <div class="b-empty">
                <div class="b-empty-ico"><i class="bi bi-pencil-square"></i></div>
                <h3>Select filters to begin</h3>
                <p style="font-size:.86rem;">Choose an exam, class and subject to load the student list.</p>
            </div>
        </div>
    @elseif ($students->isEmpty())
        <div class="b-card">
            <div class="b-empty">
                <div class="b-empty-ico"><i class="bi bi-people"></i></div>
                <h3>No students in this class</h3>
                <p style="font-size:.86rem;">Add students to the selected class first.</p>
            </div>
        </div>
    @else
        <form method="POST" action="{{ route('admin.results.store') }}">
            @csrf
            <input type="hidden" name="exam_id" value="{{ $examId }}">
            <input type="hidden" name="subject_id" value="{{ $subjectId }}">

            <div class="b-card">
                <div class="b-card-head">
                    <h3>Enter Marks</h3>
                    <div style="display:flex;align-items:flex-end;gap:8px;">
                        <div>
                            <label style="font-size:.74rem;font-weight:600;display:block;margin-bottom:5px;">Full Marks</label>
                            <input type="number" name="full_marks" class="form-control" id="fullMarks" value="{{ old('full_marks', $existing->first()->full_marks ?? 100) }}" min="1" style="width:120px;">
                        </div>
                    </div>
                </div>

                @error('marks.*')<div class="b-alert b-alert-danger" style="margin:16px 22px 0;">{{ $message }}</div>@enderror
                @error('marks')<div class="b-alert b-alert-danger" style="margin:16px 22px 0;">{{ $message }}</div>@enderror
                @error('full_marks')<div class="b-alert b-alert-danger" style="margin:16px 22px 0;">{{ $message }}</div>@enderror

                <div class="b-table-wrap">
                    <table class="b-table">
                        <thead>
                            <tr><th>Student</th><th>Student ID</th><th>Marks (out of <span id="fullMarksLabel">{{ $existing->first()->full_marks ?? 100 }}</span>)</th><th>Current Grade</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                @php $record = $existing->get($student->id); @endphp
                                <tr>
                                    <td><strong>{{ $student->name }}</strong></td>
                                    <td>{{ $student->student_id }}</td>
                                    <td>
                                        <input type="number" step="0.01" min="0" name="marks[{{ $student->id }}]" class="form-control"
                                               value="{{ old('marks.' . $student->id, $record->marks ?? '') }}" placeholder="—" style="max-width:180px;">
                                    </td>
                                    <td>
                                        @if ($record)
                                            <strong style="color:{{ \App\Models\Result::gradeColor($record->grade) }};">{{ $record->grade }}</strong>
                                            <span style="font-size:.74rem;color:var(--muted);">({{ number_format($record->gpa, 2) }})</span>
                                        @else
                                            <span style="color:var(--muted);font-size:.84rem;">Not entered</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="padding:18px 22px;">
                    <button type="submit" class="b-btn b-btn-primary">Save Marks</button>
                </div>
            </div>
        </form>
    @endif

    @push('scripts')
        <script>
            const full = document.getElementById('fullMarks');
            const label = document.getElementById('fullMarksLabel');
            if (full && label) {
                full.addEventListener('input', () => { label.textContent = full.value; });
            }
        </script>
    @endpush
@endsection
