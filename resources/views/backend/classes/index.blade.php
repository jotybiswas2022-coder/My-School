@extends('backend.layouts.app')

@section('title', 'Classes & Sections')

@section('content')
    <div class="page-header">
        <div>
            <h1>Classes &amp; Sections</h1>
            <p>Manage class levels, sections and subject allocation.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.classes.create') }}" class="b-btn b-btn-primary">+ Add Class</a>
        </div>
    </div>

    @if ($classes->isEmpty())
        <div class="b-card">
            <div class="b-empty">
                <div class="b-empty-ico">▦</div>
                <h3>No classes yet</h3>
                <p style="font-size:.86rem;">Create your first class to begin building the academic structure.</p>
                <a href="{{ route('admin.classes.create') }}" class="b-btn b-btn-primary" style="margin-top:16px;">+ Add Class</a>
            </div>
        </div>
    @else
        <div class="b-grid b-grid-2">
            @foreach ($classes as $class)
                <div class="b-card">
                    <div class="b-card-head">
                        <div>
                            <h3>{{ $class->name }}</h3>
                            <div style="font-size:.76rem;color:var(--muted);">
                                {{ $class->students_count }} students · {{ $class->subjects->count() }} subjects
                            </div>
                        </div>
                        <div class="cell-actions">
                            <a href="{{ route('admin.classes.edit', $class) }}" class="b-btn b-btn-outline b-btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.classes.destroy', $class) }}">
                                @csrf @method('DELETE')
                                <button type="button" class="b-btn b-btn-danger b-btn-sm"
                                        data-confirm="Delete {{ $class->name }}? Sections and subject links will be removed.">Delete</button>
                            </form>
                        </div>
                    </div>

                    <div class="b-card-body">
                        <div style="margin-bottom:18px;">
                            <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;color:var(--muted);margin-bottom:10px;">Sections</div>
                            <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
                                @forelse ($class->sections as $section)
                                    <form method="POST" action="{{ route('admin.sections.destroy', $section) }}">
                                        @csrf @method('DELETE')
                                        <button type="button" class="badge badge-muted"
                                                data-confirm="Remove section {{ $section->name }}?"
                                                style="border:none;cursor:pointer;font-family:inherit;">
                                            Section {{ $section->name }} ✕
                                        </button>
                                    </form>
                                @empty
                                    <span style="font-size:.84rem;color:var(--muted);">No sections yet</span>
                                @endforelse
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.classes.sections.store', $class) }}" style="display:flex;gap:8px;align-items:flex-end;">
                            @csrf
                            <div style="flex:1;">
                                <label style="font-size:.74rem;font-weight:600;display:block;margin-bottom:5px;">Add section</label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. A" required>
                            </div>
                            <div style="width:100px;">
                                <label style="font-size:.74rem;font-weight:600;display:block;margin-bottom:5px;">Capacity</label>
                                <input type="number" name="capacity" class="form-control" value="40" min="1">
                            </div>
                            <button class="b-btn b-btn-primary">Add</button>
                        </form>

                        <div style="margin-top:18px;">
                            <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;color:var(--muted);margin-bottom:10px;">Subjects</div>
                            <div style="display:flex;flex-wrap:wrap;gap:7px;">
                                @forelse ($class->subjects as $subject)
                                    <span class="badge">{{ $subject->name }}</span>
                                @empty
                                    <span style="font-size:.84rem;color:var(--muted);">No subjects assigned</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
