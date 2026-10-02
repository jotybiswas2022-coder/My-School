@extends('backend.layouts.app')

@section('title', $schoolClass->exists ? 'Edit Class' : 'Add Class')

@section('content')
    @php $editing = $schoolClass->exists; @endphp

    <div class="page-header">
        <div>
            <h1>{{ $editing ? 'Edit Class' : 'Add Class' }}</h1>
            <p>{{ $editing ? 'Update class details and subject allocation.' : 'Create a new class level.' }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.classes.index') }}" class="b-btn b-btn-outline">← Back</a>
        </div>
    </div>

    <form method="POST" action="{{ $editing ? route('admin.classes.update', $schoolClass) : route('admin.classes.store') }}">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="b-grid b-grid-2" style="align-items:start;">
            <div class="b-card">
                <div class="b-card-head"><h3>Class Details</h3></div>
                <div class="b-card-body">
                    <div class="form-row">
                        <label for="name">Class Name <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $schoolClass->name) }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-row">
                        <label for="code">Class Code</label>
                        <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $schoolClass->code) }}" placeholder="e.g. G6">
                    </div>
                    <div class="form-row">
                        <label for="order">Display Order</label>
                        <input type="number" name="order" id="order" class="form-control" value="{{ old('order', $schoolClass->order ?? 0) }}" min="0">
                    </div>
                    <div class="form-row">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description', $schoolClass->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="b-card">
                <div class="b-card-head"><h3>Subjects</h3></div>
                <div class="b-card-body">
                    @if ($subjects->isEmpty())
                        <p style="font-size:.86rem;color:var(--muted);">No subjects exist yet. Create subjects first to assign them here.</p>
                    @else
                        <div class="form-grid">
                            @foreach ($subjects as $subject)
                                <label class="checkbox-row" style="margin-bottom:10px;">
                                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" @checked(in_array($subject->id, old('subjects', $selectedSubjects)))>
                                    {{ $subject->name }} <span style="color:var(--muted);font-weight:500;">({{ $subject->code }})</span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="b-card" style="margin-top:20px;">
            <div class="b-card-body" style="display:flex;gap:10px;">
                <button type="submit" class="b-btn b-btn-primary">{{ $editing ? 'Update Class' : 'Create Class' }}</button>
                <a href="{{ route('admin.classes.index') }}" class="b-btn b-btn-outline">Cancel</a>
            </div>
        </div>
    </form>
@endsection
