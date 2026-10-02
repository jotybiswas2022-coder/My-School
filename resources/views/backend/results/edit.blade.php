@extends('backend.layouts.app')

@section('title', 'Edit Result')

@section('content')
    <div class="page-header">
        <div>
            <h1>Edit Result</h1>
            <p>{{ $result->student->name ?? '—' }} · {{ $result->subject->name ?? '—' }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.results.index') }}" class="b-btn b-btn-outline"><i class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="b-card" style="max-width:680px;">
        <div class="b-card-head"><h3>Result Details</h3></div>
        <div class="b-card-body">
            <div class="b-grid b-grid-3" style="margin-bottom:22px;">
                @foreach ([
                    ['Student', $result->student->name ?? '—'],
                    ['Exam', $result->exam->name ?? '—'],
                    ['Subject', $result->subject->name ?? '—'],
                ] as $row)
                    <div>
                        <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;color:var(--muted);">{{ $row[0] }}</div>
                        <strong style="font-size:.9rem;">{{ $row[1] }}</strong>
                    </div>
                @endforeach
            </div>

            <form method="POST" action="{{ route('admin.results.update', $result) }}">
                @csrf @method('PUT')

                <div class="form-grid">
                    <div class="form-row">
                        <label for="marks">Marks Obtained <span style="color:var(--danger);">*</span></label>
                        <input type="number" step="0.01" min="0" name="marks" id="marks" class="form-control @error('marks') is-invalid @enderror" value="{{ old('marks', $result->marks) }}" required>
                        @error('marks')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <label for="full_marks">Full Marks <span style="color:var(--danger);">*</span></label>
                        <input type="number" min="1" name="full_marks" id="full_marks" class="form-control @error('full_marks') is-invalid @enderror" value="{{ old('full_marks', $result->full_marks) }}" required>
                        @error('full_marks')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div style="display:flex;gap:12px;margin-bottom:20px;">
                    <span class="badge">Current Grade: {{ $result->grade }}</span>
                    <span class="badge">Current GPA: {{ number_format($result->gpa, 2) }}</span>
                    <span class="badge {{ $result->is_published ? 'badge-success' : 'badge-muted' }}">{{ $result->is_published ? 'Published' : 'Draft' }}</span>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" class="b-btn b-btn-primary">Update Result</button>
                    <a href="{{ route('admin.results.index') }}" class="b-btn b-btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
