@extends('frontend.student.layout')

@section('title', __('ui.student.dashboard') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('student-content')
    <div class="welcome-card" style="margin-bottom:24px;">
        <div style="position:relative;z-index:1;">
            <span class="eyebrow" style="background:rgba(255,255,255,.16);color:#BFDBFE;">{{ __('ui.student.dashboard') }}</span>
            <h1 style="color:#fff;font-size:1.6rem;margin-bottom:6px;">{{ __('ui.student.welcome', ['name' => $student->name]) }}</h1>
            <p style="color:rgba(255,255,255,.8);font-size:.9rem;">
                {{ $student->schoolClass->name ?? '—' }}
                @if ($student->section) · {{ __('ui.students.section') }} {{ $student->section->name }} @endif
                · ID {{ $student->student_id }}
            </p>
        </div>
    </div>

    <div class="grid grid-4" style="gap:16px;margin-bottom:24px;">
        @foreach ([
            [__('ui.student.attendance'), $summary['percentage'] . '%', __('ui.student.overall_rate')],
            [__('ui.student.present_days'), $summary['present'], __('ui.student.present_desc')],
            [__('ui.student.absent_days'), $summary['absent'], __('ui.student.absent_desc')],
            [__('ui.student.late_arrivals'), $summary['late'], __('ui.student.late_desc')],
        ] as $tile)
            <div class="stat-tile">
                <div class="value">{{ $tile[1] }}</div>
                <div class="label">{{ $tile[0] }}</div>
                <div class="muted" style="font-size:.74rem;margin-top:4px;">{{ $tile[2] }}</div>
            </div>
        @endforeach
    </div>

    <div class="grid student-dash-grid" style="gap:20px;align-items:start;">
        <div class="stack" style="gap:20px;">
            <div class="panel">
                <div class="panel-head">
                    <h3>{{ __('ui.student.latest_results') }}</h3>
                    <a href="{{ route('student.results') }}" class="btn btn-outline btn-sm">{{ __('ui.common.view_all') }}</a>
                </div>
                @if ($results->isEmpty())
                    <div class="empty" style="padding:44px 20px;">
                        <div class="empty-icon" style="width:60px;height:60px;font-size:1.5rem;"><i class="bi bi-award"></i></div>
                        <h3 style="font-size:1rem;">{{ __('ui.student.no_results') }}</h3>
                        <p style="font-size:.85rem;">{{ __('ui.student.no_results_text') }}</p>
                    </div>
                @else
                    <div class="table-wrap" style="border:none;border-radius:0;">
                        <table class="data">
                            <thead>
                                <tr><th>{{ __('ui.student.exams') }}</th><th>{{ __('ui.results.subject') }}</th><th>{{ __('ui.student.marks') }}</th><th>{{ __('ui.results.grade') }}</th></tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $result)
                                    <tr>
                                        <td>{{ $result->exam->name ?? '—' }}</td>
                                        <td>{{ $result->subject->name ?? '—' }}</td>
                                        <td>{{ rtrim(rtrim(number_format($result->marks, 2), '0'), '.') }} / {{ $result->full_marks }}</td>
                                        <td><strong style="color:{{ \App\Models\Result::gradeColor($result->grade) }};">{{ $result->grade }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="panel">
                <div class="panel-head">
                    <h3>{{ __('ui.student.recent_attendance') }}</h3>
                    <a href="{{ route('student.attendance') }}" class="btn btn-outline btn-sm">{{ __('ui.common.view_all') }}</a>
                </div>
                @if ($recentAttendance->isEmpty())
                    <div class="empty" style="padding:44px 20px;">
                        <div class="empty-icon" style="width:60px;height:60px;font-size:1.5rem;"><i class="bi bi-calendar-check"></i></div>
                        <h3 style="font-size:1rem;">{{ __('ui.student.no_attendance') }}</h3>
                        <p style="font-size:.85rem;">{{ __('ui.student.no_attendance_text') }}</p>
                    </div>
                @else
                    <div class="table-wrap" style="border:none;border-radius:0;">
                        <table class="data">
                            <thead><tr><th>{{ __('ui.student.detail') }}</th><th>{{ __('ui.common.status') }}</th><th>{{ __('ui.student.remark') }}</th></tr></thead>
                            <tbody>
                                @foreach ($recentAttendance as $record)
                                    <tr>
                                        <td>{{ $record->date->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge {{ $record->status === 'present' ? 'badge-success' : ($record->status === 'absent' ? 'badge-danger' : 'badge-warning') }}">
                                                {{ __('ui.student.' . $record->status) }}
                                            </span>
                                        </td>
                                        <td class="muted">{{ $record->remark ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="stack" style="gap:20px;">
            <div class="panel">
                <div class="panel-head"><h3>{{ __('ui.student.profile') }}</h3></div>
                <div class="panel-body">
                    <div style="text-align:center;margin-bottom:18px;">
                        <div class="avatar" style="width:72px;height:72px;margin:0 auto 10px;font-size:1.2rem;">
                            @if ($student->photo)
                                <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}">
                            @else
                                {{ $student->initials() }}
                            @endif
                        </div>
                        <strong>{{ $student->name }}</strong>
                    </div>
                    <div class="stack" style="gap:12px;">
                        @foreach ([
                            [__('ui.results.class'), $student->schoolClass->name ?? '—'],
                            [__('ui.students.section'), $student->section->name ?? '—'],
                            [__('ui.student.roll_number'), $student->roll_number ?? '—'],
                            [__('ui.student.guardian'), $student->guardian_name ?? '—'],
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
                <div class="panel-head"><h3>{{ __('ui.student.recent_notices') }}</h3></div>
                <div class="panel-body" style="padding:12px 0;">
                    @forelse ($notices as $notice)
                        <a href="{{ route('student.notices') }}" style="display:block;padding:12px 24px;border-bottom:1px solid var(--border);">
                            <strong style="font-size:.85rem;display:block;">{{ \Illuminate\Support\Str::limit($notice->title, 50) }}</strong>
                            <span class="muted" style="font-size:.74rem;">{{ optional($notice->published_at)->format('d M Y') }}</span>
                        </a>
                    @empty
                        <p class="muted" style="font-size:.85rem;padding:12px 24px;">{{ __('ui.student.no_notices') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="panel">
                <div class="panel-head"><h3>{{ __('ui.student.upcoming_events') }}</h3></div>
                <div class="panel-body" style="padding:12px 0;">
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
        .welcome-card { background: linear-gradient(135deg, #1D4ED8, #2563EB 60%, #0EA5E9); border-radius: var(--radius); padding: 32px; box-shadow: 0 24px 50px -28px rgba(37,99,235,.8); }
        .student-dash-grid { grid-template-columns: 1.3fr 1fr; }
        @media (max-width: 900px) { .student-dash-grid { grid-template-columns: 1fr; } }
    </style>
@endsection
