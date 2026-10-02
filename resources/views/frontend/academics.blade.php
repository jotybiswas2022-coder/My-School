@extends('frontend.layouts.app')

@section('title', __('ui.academics.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.academics.title'),
        'subtitle' => __('ui.academics.subtitle'),
        'crumbs' => [__('ui.nav.academics') => null],
    ])

    <section class="section">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow"><i class="bi bi-journal-bookmark-fill"></i> {{ __('ui.academics.overview_eyebrow') }}</span>
                <h2 class="section-title">{{ __('ui.academics.overview_title') }}</h2>
                <p class="section-sub">{{ __('ui.academics.overview_sub') }}</p>
            </div>

            <div class="grid grid-3">
                @foreach ([
                    ['bi-ladder', __('ui.academics.stage_foundation'), __('ui.academics.stage_foundation_text')],
                    ['bi-book', __('ui.academics.stage_primary'), __('ui.academics.stage_primary_text')],
                    ['bi-journal-text', __('ui.academics.stage_middle'), __('ui.academics.stage_middle_text')],
                    ['bi-mortarboard-fill', __('ui.academics.stage_senior'), __('ui.academics.stage_senior_text')],
                    ['bi-trophy-fill', __('ui.academics.stage_cocurricular'), __('ui.academics.stage_cocurricular_text')],
                    ['bi-cpu', __('ui.academics.stage_technology'), __('ui.academics.stage_technology_text')],
                ] as $stage)
                    <div class="card card-hover reveal" style="padding:30px 26px;">
                        <div class="icon-box"><i class="bi {{ $stage[0] }}"></i></div>
                        <h4 style="font-size:1.02rem;margin-bottom:8px;">{{ $stage[1] }}</h4>
                        <p class="muted" style="font-size:.86rem;">{{ $stage[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section-alt">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow"><i class="bi bi-bank"></i> {{ __('ui.academics.departments_eyebrow') }}</span>
                <h2 class="section-title">{{ __('ui.academics.departments_title') }}</h2>
            </div>

            @if ($departments->isEmpty())
                <div class="empty"><div class="empty-icon"><i class="bi bi-bank"></i></div><h3>{{ __('ui.academics.departments_empty') }}</h3></div>
            @else
                <div class="grid grid-4">
                    @foreach ($departments as $department)
                        <div class="card card-hover reveal" style="padding:24px;text-align:center;">
                            <div class="icon-box" style="margin:0 auto 14px;"><i class="bi bi-bookmark-star-fill"></i></div>
                            <h4 style="font-size:.94rem;">{{ $department }}</h4>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="grid grid-2" style="gap:44px;align-items:start;">
                <div class="reveal">
                    <span class="eyebrow"><i class="bi bi-easel"></i> {{ __('ui.academics.classes_eyebrow') }}</span>
                    <h2 class="section-title" style="font-size:1.6rem;">{{ __('ui.academics.classes_title') }}</h2>
                    @if ($classes->isEmpty())
                        <p class="muted">{{ __('ui.classes.empty_text') }}</p>
                    @else
                        <div class="stack">
                            @foreach ($classes as $class)
                                <div class="card" style="padding:20px 24px;">
                                    <div class="row-between" style="margin-bottom:8px;">
                                        <strong>{{ $class->name }}</strong>
                                        <span class="badge"><i class="bi bi-people"></i> {{ $class->students_count }} {{ __('ui.academics.students_count') }}</span>
                                    </div>
                                    <div class="muted" style="font-size:.82rem;">
                                        @if ($class->sections->count())
                                            {{ __('ui.classes.sections') }}: {{ $class->sections->pluck('name')->join(', ') }}
                                        @else
                                            {{ __('ui.academics.no_sections') }}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="reveal">
                    <span class="eyebrow"><i class="bi bi-calendar-range"></i> {{ __('ui.academics.sessions_eyebrow') }}</span>
                    <h2 class="section-title" style="font-size:1.6rem;">{{ __('ui.academics.sessions_title') }}</h2>
                    @if ($sessions->isEmpty())
                        <p class="muted">{{ __('ui.academics.no_sessions') }}</p>
                    @else
                        <div class="stack">
                            @foreach ($sessions as $session)
                                <div class="card" style="padding:20px 24px;">
                                    <div class="row-between">
                                        <strong>{{ $session->name }}</strong>
                                        @if ($session->is_current)
                                            <span class="badge badge-success">{{ __('ui.academics.current') }}</span>
                                        @endif
                                    </div>
                                    <div class="muted" style="font-size:.82rem;margin-top:6px;">
                                        {{ optional($session->start_date)->format('d M Y') ?? '—' }} — {{ optional($session->end_date)->format('d M Y') ?? '—' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div style="text-align:center;margin-top:48px;">
                <a href="{{ route('subjects') }}" class="btn btn-outline">{{ __('ui.academics.browse_subjects') }}</a>
                <a href="{{ route('classes') }}" class="btn btn-primary">{{ __('ui.academics.view_classes') }}</a>
            </div>
        </div>
    </section>
@endsection
