@extends('backend.layouts.app')

@section('title', $subject->exists ? 'Edit Subject' : 'Add Subject')

@section('content')
    @php $editing = $subject->exists; @endphp

    <div class="page-header">
        <div>
            <h1>{{ $editing ? 'Edit Subject' : 'Add Subject' }}</h1>
            <p>{{ $editing ? 'Update subject information.' : 'Create a new subject.' }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.subjects.index') }}" class="b-btn b-btn-outline"><i class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>

    <form method="POST" action="{{ $editing ? route('admin.subjects.update', $subject) : route('admin.subjects.store') }}">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="b-card" style="max-width:820px;">
            <div class="b-card-head"><h3>Subject Details</h3></div>
            <div class="b-card-body">
                <div class="form-grid">
                    <div class="form-row">
                        <label for="name">Subject Name <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $subject->name) }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <label for="code">Subject Code <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $subject->code) }}" required placeholder="e.g. MATH6">
                        @error('code')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <label for="class_id">Class</label>
                        <select name="class_id" id="class_id" class="form-select">
                            <option value="">Not assigned</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @selected(old('class_id', $subject->class_id) == $class->id)>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-row">
                        <label for="teacher_id">Teacher</label>
                        <select name="teacher_id" id="teacher_id" class="form-select">
                            <option value="">Not assigned</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" @selected(old('teacher_id', $subject->teacher_id) == $teacher->id)>{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-row span-2">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description', $subject->description) }}</textarea>
                    </div>
                </div>

                <div style="display:flex;gap:10px;margin-top:6px;">
                    <button type="submit" class="b-btn b-btn-primary">{{ $editing ? 'Update Subject' : 'Create Subject' }}</button>
                    <a href="{{ route('admin.subjects.index') }}" class="b-btn b-btn-outline">Cancel</a>
                </div>
            </div>
        </div>
    </form>
@endsection
