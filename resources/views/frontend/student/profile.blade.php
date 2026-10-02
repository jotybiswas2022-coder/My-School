@extends('frontend.student.layout')

@section('title', __('ui.student.my_profile') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('student-content')
    <div class="panel">
        <div class="panel-head">
            <h3>{{ __('ui.student.my_profile') }}</h3>
            <span class="badge {{ $student->is_active ? 'badge-success' : 'badge-danger' }}">{{ $student->is_active ? __('ui.common.active') : __('ui.common.inactive') }}</span>
        </div>

        <div class="panel-body">
            <div class="row" style="gap:26px;flex-wrap:wrap;margin-bottom:30px;">
                <div class="avatar" style="width:104px;height:104px;font-size:1.8rem;">
                    @if ($student->photo)
                        <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}">
                    @else
                        {{ $student->initials() }}
                    @endif
                </div>
                <div>
                    <h2 style="font-size:1.35rem;margin-bottom:4px;">{{ $student->name }}</h2>
                    <p class="muted" style="font-size:.9rem;">{{ $student->student_id }}</p>
                    <div class="row" style="gap:8px;margin-top:10px;flex-wrap:wrap;">
                        <span class="badge">{{ $student->schoolClass->name ?? __('ui.students.no_class') }}</span>
                        @if ($student->section)<span class="badge">{{ __('ui.classes.section_label', ['name' => $student->section->name]) }}</span>@endif
                    </div>
                </div>
            </div>

            <div class="grid grid-2" style="gap:22px;">
                @foreach ([
                    [__('ui.auth.student_id'), $student->student_id],
                    [__('ui.common.full_name'), $student->name],
                    [__('ui.student.dob'), optional($student->date_of_birth)->format('d M Y') ?? '—'],
                    [__('ui.student.gender'), $student->gender ? __('ui.admission.' . $student->gender) : '—'],
                    [__('ui.results.class'), $student->schoolClass->name ?? '—'],
                    [__('ui.students.section'), $student->section->name ?? '—'],
                    [__('ui.student.roll_number'), $student->roll_number ?? '—'],
                    [__('ui.student.admission_date'), optional($student->admission_date)->format('d M Y') ?? '—'],
                    [__('ui.admission.guardian_name'), $student->guardian_name ?? '—'],
                    [__('ui.admission.guardian_phone'), $student->guardian_phone ?? '—'],
                    [__('ui.common.email'), $student->email ?? '—'],
                ] as $row)
                    <div style="padding:14px 0;border-bottom:1px solid var(--border);">
                        <div class="muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;">{{ $row[0] }}</div>
                        <strong style="font-size:.92rem;">{{ $row[1] }}</strong>
                    </div>
                @endforeach
            </div>

            @if ($student->address)
                <div style="margin-top:24px;">
                    <div class="muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;margin-bottom:8px;">{{ __('ui.common.address') }}</div>
                    <p style="font-size:.92rem;">{{ $student->address }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
