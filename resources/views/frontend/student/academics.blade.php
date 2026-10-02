@extends('frontend.student.layout')

@section('title', __('ui.nav.academics') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('student-content')
    <div class="grid student-academics-grid" style="gap:20px;align-items:start;">
        <div class="panel">
            <div class="panel-head">
                <h3>{{ __('ui.student.my_subjects') }}</h3>
                <span class="badge">{{ $student->schoolClass->name ?? __('ui.students.no_class') }}</span>
            </div>

            @if ($subjects->isEmpty())
                <div class="empty" style="padding:56px 20px;">
                    <div class="empty-icon"><i class="bi bi-journal-bookmark"></i></div>
                    <h3>{{ __('ui.student.no_subjects_title') }}</h3>
                    <p>{{ __('ui.student.no_subjects_text') }}</p>
                </div>
            @else
                <div class="panel-body">
                    <div class="grid grid-2" style="gap:14px;">
                        @foreach ($subjects as $subject)
                            <div style="border:1px solid var(--border);border-radius:var(--radius-sm);padding:18px;">
                                <div class="row-between" style="margin-bottom:8px;">
                                    <strong style="font-size:.94rem;">{{ $subject->name }}</strong>
                                    <span class="badge">{{ $subject->code }}</span>
                                </div>
                                <p class="muted" style="font-size:.8rem;">
                                    {{ $subject->teacher->name ?? __('ui.teachers.teacher_to_be_assigned') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="stack" style="gap:20px;">
            <div class="panel">
                <div class="panel-head"><h3>{{ __('ui.student.academic_info') }}</h3></div>
                <div class="panel-body">
                    <div class="stack" style="gap:12px;">
                        @foreach ([
                            [__('ui.results.class'), $student->schoolClass->name ?? '—'],
                            [__('ui.students.section'), $student->section->name ?? '—'],
                            [__('ui.student.roll_number'), $student->roll_number ?? '—'],
                            [__('ui.results.session'), \App\Models\AcademicSession::current()->name ?? '—'],
                        ] as $row)
                            <div class="row-between" style="font-size:.86rem;">
                                <span class="muted">{{ $row[0] }}</span>
                                <strong>{{ $row[1] }}</strong>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-head"><h3>{{ __('ui.student.upcoming_events') }}</h3></div>
                <div class="panel-body" style="padding:10px 0;">
                    @forelse ($events as $event)
                        <div style="padding:12px 24px;border-bottom:1px solid var(--border);">
                            <strong style="font-size:.85rem;display:block;">{{ $event->title }}</strong>
                            <span class="muted" style="font-size:.74rem;">{{ $event->event_date->format('d M Y') }}{{ $event->location ? ' · ' . $event->location : '' }}</span>
                        </div>
                    @empty
                        <p class="muted" style="font-size:.85rem;padding:12px 24px;">{{ __('ui.student.no_events') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        .student-academics-grid { grid-template-columns: 1.4fr 1fr; }
        @media (max-width: 900px) {
            .student-academics-grid { grid-template-columns: 1fr; }
        }
    </style>
@endsection
