@extends('backend.layouts.app')

@section('title', $student->exists ? 'Edit Student' : 'Add Student')

@section('content')
    @php $editing = $student->exists; @endphp

    <div class="page-header">
        <div>
            <h1>{{ $editing ? 'Edit Student' : 'Add Student' }}</h1>
            <p>{{ $editing ? 'Update the student record below.' : 'Create a new student record.' }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.students.index') }}" class="b-btn b-btn-outline">← Back</a>
        </div>
    </div>

    <form method="POST" action="{{ $editing ? route('admin.students.update', $student) : route('admin.students.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="b-grid" style="grid-template-columns:1fr 320px;align-items:start;">
            <div class="b-card">
                <div class="b-card-head"><h3>Student Information</h3></div>
                <div class="b-card-body">
                    <div class="form-grid">
                        <div class="form-row">
                            <label for="student_id">Student ID <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="student_id" id="student_id" class="form-control @error('student_id') is-invalid @enderror"
                                   value="{{ old('student_id', $student->student_id) }}" required>
                            @error('student_id')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-row">
                            <label for="name">Full Name <span style="color:var(--danger);">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $student->name) }}" required>
                            @error('name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-row">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $student->email) }}">
                            @error('email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-row">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                   placeholder="{{ $editing ? 'Leave blank to keep current' : 'For student portal login' }}">
                            @error('password')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-row">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control"
                                   value="{{ old('date_of_birth', optional($student->date_of_birth)->format('Y-m-d')) }}">
                            @error('date_of_birth')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-row">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" class="form-select">
                                <option value="">Select</option>
                                @foreach (['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('gender', $student->gender) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-row">
                            <label for="class_id">Class</label>
                            <select name="class_id" id="class_id" class="form-select">
                                <option value="">Select class</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" @selected(old('class_id', $student->class_id) == $class->id)>{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-row">
                            <label for="section_id">Section</label>
                            <select name="section_id" id="section_id" class="form-select">
                                <option value="">Select section</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}" @selected(old('section_id', $student->section_id) == $section->id)>
                                        {{ $section->schoolClass->name ?? '' }} - {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-row">
                            <label for="roll_number">Roll Number</label>
                            <input type="text" name="roll_number" id="roll_number" class="form-control" value="{{ old('roll_number', $student->roll_number) }}">
                        </div>
                        <div class="form-row">
                            <label for="admission_date">Admission Date</label>
                            <input type="date" name="admission_date" id="admission_date" class="form-control"
                                   value="{{ old('admission_date', optional($student->admission_date)->format('Y-m-d')) }}">
                        </div>
                        <div class="form-row">
                            <label for="guardian_name">Guardian Name</label>
                            <input type="text" name="guardian_name" id="guardian_name" class="form-control" value="{{ old('guardian_name', $student->guardian_name) }}">
                        </div>
                        <div class="form-row">
                            <label for="guardian_phone">Guardian Phone</label>
                            <input type="text" name="guardian_phone" id="guardian_phone" class="form-control" value="{{ old('guardian_phone', $student->guardian_phone) }}">
                        </div>
                        <div class="form-row span-2">
                            <label for="address">Address</label>
                            <textarea name="address" id="address" class="form-control">{{ old('address', $student->address) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stack" style="display:flex;flex-direction:column;gap:20px;">
                <div class="b-card">
                    <div class="b-card-head"><h3>Photo</h3></div>
                    <div class="b-card-body">
                        @if ($student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" alt="Student photo" style="width:100%;border-radius:var(--radius-sm);margin-bottom:14px;">
                        @endif
                        <input type="file" name="photo" class="form-control" accept="image/*">
                        <div class="form-hint">JPG, PNG, WEBP or SVG. Max 4MB.</div>
                        @error('photo')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="b-card">
                    <div class="b-card-body">
                        <label class="checkbox-row">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $student->is_active))>
                            Active student
                        </label>
                    </div>
                </div>

                <div class="b-card">
                    <div class="b-card-body">
                        <button type="submit" class="b-btn b-btn-primary b-btn-block">{{ $editing ? 'Update Student' : 'Create Student' }}</button>
                        <a href="{{ route('admin.students.index') }}" class="b-btn b-btn-outline b-btn-block" style="margin-top:10px;">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <style>
        @media (max-width: 900px) {
            .b-grid[style*="320px"] { grid-template-columns: 1fr !important; }
        }
    </style>
@endsection
