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
            <div class="tprofile">
                {{-- The <h1> above already names the teacher, so this column is portrait,
                     role, actions and the structured details only. --}}
                <aside class="tprofile-side reveal">
                    <div class="tprofile-card">
                        <div class="principal-media">
                            <span class="principal-frame" aria-hidden="true"></span>
                            <div class="principal-photo">
                                @if ($teacher->photo)
                                    <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->name }}">
                                @else
                                    <i class="bi bi-person-badge" aria-hidden="true"></i>
                                @endif
                            </div>
                        </div>

                        <span class="tprofile-role">{{ $teacher->designation }}</span>

                        @if ($teacher->email || $teacher->phone)
                            <div class="tprofile-actions">
                                @if ($teacher->email)
                                    <a href="mailto:{{ $teacher->email }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-envelope" aria-hidden="true"></i> {{ __('ui.common.email') }}
                                    </a>
                                @endif
                                @if ($teacher->phone)
                                    <a href="tel:{{ $teacher->phone }}" class="btn btn-outline btn-sm">
                                        <i class="bi bi-telephone" aria-hidden="true"></i> {{ __('ui.common.phone') }}
                                    </a>
                                @endif
                            </div>
                        @endif

                        @php
                            $details = [
                                [__('ui.teachers.department'), $teacher->department],
                                [__('ui.teachers.qualification'), $teacher->qualification],
                                [__('ui.teachers.experience'), $teacher->experience],
                                [__('ui.teachers.joined'), optional($teacher->join_date)->format('M Y')],
                            ];
                        @endphp

                        @if (collect($details)->contains(fn ($row) => $row[1]))
                            <dl class="tprofile-meta">
                                @foreach ($details as $row)
                                    @if ($row[1])
                                        <div>
                                            <dt>{{ $row[0] }}</dt>
                                            <dd>{{ $row[1] }}</dd>
                                        </div>
                                    @endif
                                @endforeach
                            </dl>
                        @endif
                    </div>
                </aside>

                <div class="tprofile-main">
                    @if ($teacher->bio)
                        <div class="tpanel reveal">
                            <div class="tpanel-head">
                                <span class="tpanel-icon"><i class="bi bi-person-lines-fill" aria-hidden="true"></i></span>
                                <h2>{{ __('ui.teachers.about') }}</h2>
                            </div>
                            <div class="prose"><p>{{ $teacher->bio }}</p></div>
                        </div>
                    @endif

                    <div class="tpanel reveal">
                        <div class="tpanel-head">
                            <span class="tpanel-icon"><i class="bi bi-journal-bookmark-fill" aria-hidden="true"></i></span>
                            <h2>{{ __('ui.teachers.subjects_taught') }}</h2>
                        </div>

                        @if ($teacher->subjects->isEmpty())
                            <div class="empty empty-sm">
                                <p class="muted">{{ __('ui.teachers.no_subjects') }}</p>
                            </div>
                        @else
                            <ul class="tsubjects">
                                @foreach ($teacher->subjects as $subject)
                                    <li class="tsubject">
                                        <strong>{{ $subject->name }}</strong>
                                        <span>
                                            {{ $subject->code }}
                                            @if ($subject->schoolClass) · {{ $subject->schoolClass->name }} @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
