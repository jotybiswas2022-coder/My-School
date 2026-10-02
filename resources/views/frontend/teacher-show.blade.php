@extends('frontend.layouts.app')

@section('title', $teacher->name . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => $teacher->name,
        'subtitle' => $teacher->designation . ($teacher->department ? ' · ' . $teacher->department : ''),
        'crumbs' => [__('ui.nav.teachers') => route('teachers'), $teacher->name => null],
    ])

    <section class="section">
        <div class="container">
            <div class="grid teacher-grid" style="gap:36px;align-items:start;">
                <div class="card reveal" style="padding:32px;text-align:center;position:sticky;top:100px;">
                    <div class="avatar" style="width:130px;height:130px;font-size:2.2rem;margin:0 auto 20px;">
                        @if ($teacher->photo)
                            <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->name }}">
                        @else
                            {{ $teacher->initials() }}
                        @endif
                    </div>
                    <h2 style="font-size:1.3rem;margin-bottom:4px;">{{ $teacher->name }}</h2>
                    <p style="color:var(--primary);font-weight:600;font-size:.9rem;">{{ $teacher->designation }}</p>

                    <div style="text-align:left;margin-top:26px;display:flex;flex-direction:column;gap:14px;">
                        @foreach ([
                            [__('ui.teachers.department'), $teacher->department],
                            [__('ui.teachers.qualification'), $teacher->qualification],
                            [__('ui.teachers.experience'), $teacher->experience],
                            [__('ui.common.email'), $teacher->email],
                            [__('ui.common.phone'), $teacher->phone],
                            [__('ui.teachers.joined'), optional($teacher->join_date)->format('M Y')],
                        ] as $row)
                            @if ($row[1])
                                <div>
                                    <div class="muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;">{{ $row[0] }}</div>
                                    <div style="font-weight:600;font-size:.88rem;">{{ $row[1] }}</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="stack" style="gap:28px;">
                    @if ($teacher->bio)
                        <div class="card reveal" style="padding:34px;">
                            <h3 style="margin-bottom:14px;">{{ __('ui.teachers.about') }}</h3>
                            <div class="prose"><p>{{ $teacher->bio }}</p></div>
                        </div>
                    @endif

                    <div class="card reveal" style="padding:34px;">
                        <h3 style="margin-bottom:18px;">{{ __('ui.teachers.subjects_taught') }}</h3>
                        @if ($teacher->subjects->isEmpty())
                            <div class="empty" style="padding:30px;">
                                <p class="muted">{{ __('ui.teachers.no_subjects') }}</p>
                            </div>
                        @else
                            <div class="grid grid-2" style="gap:14px;">
                                @foreach ($teacher->subjects as $subject)
                                    <div class="card" style="padding:18px;">
                                        <strong style="font-size:.94rem;">{{ $subject->name }}</strong>
                                        <div class="muted" style="font-size:.78rem;">{{ $subject->code }}
                                            @if ($subject->schoolClass) · {{ $subject->schoolClass->name }} @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .teacher-grid { grid-template-columns: 340px 1fr; }
        @media (max-width: 900px) {
            .teacher-grid { grid-template-columns: 1fr !important; }
            .teacher-grid > div:first-child { position: static !important; }
        }
    </style>
@endsection
