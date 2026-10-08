@extends('backend.layouts.app')

@section('title', $teacher->exists ? 'Edit Teacher' : 'Add Teacher')

@section('content')
    @php $editing = $teacher->exists; @endphp

    <div class="page-header">
        <div>
            <h1>{{ $editing ? 'Edit Teacher' : 'Add Teacher' }}</h1>
            <p>{{ $editing ? 'Update the staff member below.' : 'Create a new staff member record.' }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.teachers.index') }}" class="b-btn b-btn-outline"><i class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>

    <form method="POST" action="{{ $editing ? route('admin.teachers.update', $teacher) : route('admin.teachers.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="b-grid" style="grid-template-columns:1fr 320px;align-items:start;">
            <div class="b-card">
                <div class="b-card-head"><h3>Teacher Information</h3></div>
                <div class="b-card-body">
                    <div class="form-grid">
                        <div class="form-row">
                            <label for="name">Full Name <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $teacher->name) }}" required>
                            @error('name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-row">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $teacher->email) }}">
                            @error('email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-row">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $teacher->phone) }}">
                        </div>
                        <div class="form-row">
                            <label for="designation">Designation</label>
                            <input type="text" name="designation" id="designation" class="form-control" value="{{ old('designation', $teacher->designation) }}" placeholder="e.g. Senior Teacher">
                        </div>
                        <div class="form-row">
                            <label for="department">Department</label>
                            <input type="text" name="department" id="department" class="form-control" value="{{ old('department', $teacher->department) }}" placeholder="e.g. Science">
                        </div>
                        <div class="form-row">
                            <label for="qualification">Qualification</label>
                            <input type="text" name="qualification" id="qualification" class="form-control" value="{{ old('qualification', $teacher->qualification) }}" placeholder="e.g. M.Sc, B.Ed">
                        </div>
                        <div class="form-row">
                            <label for="experience">Experience</label>
                            <input type="text" name="experience" id="experience" class="form-control" value="{{ old('experience', $teacher->experience) }}" placeholder="e.g. 8 years">
                        </div>
                        <div class="form-row">
                            <label for="join_date">Join Date</label>
                            <input type="date" name="join_date" id="join_date" class="form-control" value="{{ old('join_date', optional($teacher->join_date)->format('Y-m-d')) }}">
                        </div>
                        <div class="form-row span-2">
                            <label for="bio">Biography</label>
                            <textarea name="bio" id="bio" class="form-control" placeholder="A short professional biography">{{ old('bio', $teacher->bio) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:20px;">
                <div class="b-card">
                    <div class="b-card-head"><h3>Photo</h3></div>
                    <div class="b-card-body">
                        @if ($teacher->photo)
                            <img src="{{ asset('storage/' . $teacher->photo) }}" alt="Teacher photo" style="width:100%;border-radius:var(--radius-sm);margin-bottom:14px;">
                            <label class="checkbox-row" style="margin-bottom:14px;">
                                <input type="checkbox" name="remove_photo" value="1"> Remove current photo
                            </label>
                        @endif
                        <input type="file" name="photo" class="form-control" accept="image/*">
                        <div class="form-hint">JPG, PNG, WEBP or SVG. Max 4MB.</div>
                        @error('photo')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="b-card">
                    <div class="b-card-body">
                        <label class="checkbox-row" style="margin-bottom:12px;">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $teacher->is_active))> Active
                        </label>
                        <label class="checkbox-row">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $teacher->is_featured))> Show on homepage
                        </label>
                    </div>
                </div>

                <div class="b-card">
                    <div class="b-card-body">
                        <button type="submit" class="b-btn b-btn-primary b-btn-block">{{ $editing ? 'Update Teacher' : 'Create Teacher' }}</button>
                        <a href="{{ route('admin.teachers.index') }}" class="b-btn b-btn-outline b-btn-block" style="margin-top:10px;">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <style>
        @media (max-width: 900px) { .b-grid[style*="320px"] { grid-template-columns: 1fr !important; } }
    </style>
@endsection
