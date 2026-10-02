@extends('backend.layouts.app')

@section('title', $exam->exists ? 'Edit Exam' : 'Add Exam')

@section('content')
    @php $editing = $exam->exists; @endphp

    <div class="page-header">
        <div>
            <h1>{{ $editing ? 'Edit Exam' : 'Add Exam' }}</h1>
            <p>{{ $editing ? 'Update exam details.' : 'Create a new exam.' }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.exams.index') }}" class="b-btn b-btn-outline">← Back</a>
        </div>
    </div>

    <form method="POST" action="{{ $editing ? route('admin.exams.update', $exam) : route('admin.exams.store') }}">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="b-card" style="max-width:820px;">
            <div class="b-card-head"><h3>Exam Details</h3></div>
            <div class="b-card-body">
                <div class="form-grid">
                    <div class="form-row">
                        <label for="name">Exam Name <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $exam->name) }}" required placeholder="e.g. Final Term Examination">
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <label for="exam_type">Exam Type</label>
                        <input type="text" name="exam_type" id="exam_type" class="form-control" value="{{ old('exam_type', $exam->exam_type) }}" placeholder="e.g. Written">
                    </div>
                    <div class="form-row">
                        <label for="academic_session_id">Academic Session</label>
                        <select name="academic_session_id" id="academic_session_id" class="form-select">
                            <option value="">Not set</option>
                            @foreach ($sessions as $session)
                                <option value="{{ $session->id }}" @selected(old('academic_session_id', $exam->academic_session_id) == $session->id)>{{ $session->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-row">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', optional($exam->start_date)->format('Y-m-d')) }}">
                    </div>
                    <div class="form-row">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', optional($exam->end_date)->format('Y-m-d')) }}">
                        @error('end_date')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <label class="checkbox-row" style="margin-bottom:18px;">
                    <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $exam->is_published))> Publish results for this exam
                </label>

                <div style="display:flex;gap:10px;">
                    <button type="submit" class="b-btn b-btn-primary">{{ $editing ? 'Update Exam' : 'Create Exam' }}</button>
                    <a href="{{ route('admin.exams.index') }}" class="b-btn b-btn-outline">Cancel</a>
                </div>
            </div>
        </div>
    </form>
@endsection
