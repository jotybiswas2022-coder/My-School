@extends('frontend.layouts.app')

@section('title', ($settings['school_name'] ?? 'My School') . ' — ' . ($settings['tagline'] ?? __('ui.home.meta_tagline')))

@section('content')

{{-- ===================== HERO ===================== --}}
<section class="hero">
    <div class="container container-wide">
        <div class="hero-grid">
            <div>
                <span class="eyebrow" style="background:rgba(255,255,255,.14);color:#BFDBFE;">
                    <i class="bi bi-patch-check-fill"></i>
                    {{ $settings['established_year'] ?? '1998' }}
                </span>
                <h1 class="reveal">{{ __('ui.home.hero_title_1') }}<br><span class="accent">{{ __('ui.home.hero_title_2') }}</span></h1>
                <p class="lead reveal">{{ __('ui.home.hero_lead') }}</p>

                <div class="hero-actions reveal">
                    <a href="{{ route('about') }}" class="btn btn-ghost">
                        <i class="bi bi-compass"></i> {{ __('ui.home.explore_school') }}
                    </a>
                    <a href="{{ route('admission') }}" class="btn btn-primary" style="background:#fff;color:var(--secondary);box-shadow:0 14px 30px -14px rgba(0,0,0,.6);">
                        <i class="bi bi-journal-text"></i> {{ __('ui.home.apply_admission') }}
                    </a>
                </div>

                <div class="hero-badges reveal">
                    <div class="hero-badge">
                        <strong><span data-count="{{ $stats['students'] }}">0</span>+</strong>
                        <span>{{ __('ui.home.stat_students') }}</span>
                    </div>
                    <div class="hero-badge">
                        <strong><span data-count="{{ $stats['teachers'] }}">0</span>+</strong>
                        <span>{{ __('ui.home.stat_teachers') }}</span>
                    </div>
                    <div class="hero-badge">
                        <strong><span data-count="{{ $stats['years'] }}">0</span>+</strong>
                        <span>{{ __('ui.home.stat_years') }}</span>
                    </div>
                </div>
            </div>

            <div class="hero-visual reveal">
                <div class="hero-card">
                    <div class="hero-card-image">
                        @if (! empty($settings['hero_image']))
                            <img src="{{ asset('storage/' . $settings['hero_image']) }}" alt="{{ __('ui.home.hero_image_alt') }}">
                        @else
                            <i class="bi bi-mortarboard-fill"></i>
                        @endif
                    </div>
                    <h3 style="color:#fff;font-size:1.1rem;margin-bottom:6px;">{{ __('ui.home.campus_card_title') }}</h3>
                    <p style="font-size:.88rem;color:rgba(255,255,255,.72);">{{ __('ui.home.campus_card_text') }}</p>
                </div>

                <div class="float-chip chip-1">
                    <span class="dot"><i class="bi bi-star-fill"></i></span>
                    <span>{{ __('ui.home.top_ranked') }}<small>{{ __('ui.home.top_ranked_sub') }}</small></span>
                </div>
                <div class="float-chip chip-2">
                    <span class="dot"><i class="bi bi-check-lg"></i></span>
                    <span>{{ ($settings['admission_open'] ?? '1') === '1' ? __('ui.home.admissions_open') : __('ui.home.admissions_closed') }}<small>{{ __('ui.home.apply_online_today') }}</small></span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== NOTICE TICKER ===================== --}}
@if ($ticker->isNotEmpty())
    <div class="ticker">
        <span class="ticker-label"><span class="pulse"></span> {{ __('ui.home.latest') }}</span>
        <div class="ticker-viewport">
            <div class="ticker-track">
                @foreach ($ticker as $notice)
                    <a href="{{ route('notices.show', $notice) }}" class="ticker-item">{{ $notice->title }}</a>
                @endforeach
                @foreach ($ticker as $notice)
                    <a href="{{ route('notices.show', $notice) }}" class="ticker-item" aria-hidden="true">{{ $notice->title }}</a>
                @endforeach
            </div>
        </div>
    </div>
@endif

{{-- ===================== ABOUT PREVIEW ===================== --}}
<section class="section">
    <div class="container">
        <div class="about-grid">
            <div class="about-main reveal">
                <span class="eyebrow"><i class="bi bi-building" aria-hidden="true"></i> {{ __('ui.home.about_eyebrow') }}</span>
                <h2 class="section-title">{{ __('ui.home.about_title') }}</h2>
                <p class="section-sub">
                    {{ $settings['about_description'] ?? __('ui.home.about_fallback') }}
                </p>

                <ul class="about-pillars">
                    @foreach ([
                        ['bi-bullseye', __('ui.home.mission'), $settings['mission'] ?? __('ui.home.mission_fallback')],
                        ['bi-eye', __('ui.home.vision'), $settings['vision'] ?? __('ui.home.vision_fallback')],
                    ] as $pillar)
                        <li class="about-pillar">
                            <span class="about-pillar-icon"><i class="bi {{ $pillar[0] }}" aria-hidden="true"></i></span>
                            <div>
                                <h3>{{ $pillar[1] }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($pillar[2], 120) }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="about-actions">
                    <a href="{{ route('about') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-right" aria-hidden="true"></i> {{ __('ui.home.read_more_about') }}
                    </a>
                    <a href="{{ route('facilities') }}" class="btn btn-outline">{{ __('ui.home.learn_facilities') }}</a>
                </div>
            </div>

            <div class="about-side reveal">
                <div class="about-panel">
                    <div class="about-panel-head">
                        <h3>{{ __('ui.home.about_features_title') }}</h3>
                    </div>

                    <ul class="about-feats">
                        @foreach ([
                            ['bi-display', __('ui.home.feature_smart'), __('ui.home.feature_smart_desc')],
                            ['bi-person-workspace', __('ui.home.feature_faculty'), __('ui.home.feature_faculty_desc')],
                            ['bi-trophy', __('ui.home.feature_growth'), __('ui.home.feature_growth_desc')],
                            ['bi-shield-fill-check', __('ui.home.feature_safe'), __('ui.home.feature_safe_desc')],
                        ] as $i => $item)
                            <li class="about-feat">
                                <span class="about-feat-num" aria-hidden="true">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                <i class="bi {{ $item[0] }} about-feat-icon" aria-hidden="true"></i>
                                <div>
                                    <h4>{{ $item[1] }}</h4>
                                    <p>{{ $item[2] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== STATISTICS ===================== --}}
<section class="section section-alt">
    <div class="container">
        <div class="glance reveal">
            <div class="glance-head">
                <div>
                    <span class="eyebrow"><i class="bi bi-bar-chart-fill" aria-hidden="true"></i> {{ __('ui.home.by_numbers') }}</span>
                    <h2 class="section-title">{{ __('ui.home.at_a_glance') }}</h2>
                </div>

                <div class="glance-head-aside">
                    <p class="glance-sub">{{ __('ui.home.glance_sub') }}</p>
                    <a href="{{ route('about') }}" class="glance-link">
                        {{ __('ui.home.glance_link') }} <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <ul class="glance-stats">
                @foreach ([
                    ['bi-people-fill', __('ui.home.students_enrolled'), $stats['students'], true],
                    ['bi-person-badge-fill', __('ui.home.expert_teachers'), $stats['teachers'], true],
                    ['bi-easel-fill', __('ui.home.active_classes'), $stats['classes'], false],
                    ['bi-award-fill', __('ui.home.years_excellence'), $stats['years'], true],
                ] as $stat)
                    <li class="glance-stat">
                        <i class="bi {{ $stat[0] }}" aria-hidden="true"></i>
                        <div class="glance-value">
                            <span data-count="{{ $stat[2] }}">0</span>@if ($stat[3])<span class="glance-suffix">+</span>@endif
                        </div>
                        <span class="glance-label">{{ $stat[1] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

{{-- ===================== ACADEMIC PROGRAMS ===================== --}}
<section class="section">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow"><i class="bi bi-journal-bookmark-fill" aria-hidden="true"></i> {{ __('ui.home.programs_eyebrow') }}</span>
            <h2 class="section-title">{{ __('ui.home.programs_title') }}</h2>
            <p class="section-sub">{{ __('ui.home.programs_sub') }}</p>
        </div>

        @if ($programs->isEmpty())
            <div class="empty">
                <div class="empty-icon"><i class="bi bi-journal-bookmark"></i></div>
                <h3>{{ __('ui.home.programs_empty') }}</h3>
                <p>{{ __('ui.home.programs_empty_text') }}</p>
            </div>
        @else
            <div class="grid grid-3 prog-grid" tabindex="0" role="group" aria-label="{{ __('ui.home.programs_title') }}">
                @foreach ($programs as $program)
                    <a href="{{ route('classes') }}" class="prog reveal">
                        <div class="prog-head">
                            <span class="prog-initial" aria-hidden="true">{{ mb_substr($program->name, 0, 1) }}</span>
                            <span class="prog-num" aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h3>{{ $program->name }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($program->description ?? __('ui.home.program_fallback'), 100) }}</p>
                        <div class="prog-foot">
                            <span class="prog-meta">
                                <i class="bi bi-people" aria-hidden="true"></i>
                                {{ $program->students_count }} {{ __('ui.home.program_students') }}
                            </span>
                            <span class="prog-go"><i class="bi bi-arrow-right" aria-hidden="true"></i></span>
                        </div>
                    </a>
                @endforeach
            </div>

            <a href="{{ route('classes') }}" class="prog-all reveal">
                {{ __('ui.home.programs_all') }} <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
        @endif
    </div>
</section>

{{-- ===================== PRINCIPAL MESSAGE ===================== --}}
<section class="section section-alt">
    <div class="container">
        <div class="principal reveal">
            <div class="principal-media">
                <span class="principal-frame" aria-hidden="true"></span>
                <div class="principal-photo">
                    @if (! empty($principal['photo']))
                        <img src="{{ asset('storage/' . $principal['photo']) }}" alt="{{ $principal['name'] }}">
                    @else
                        <i class="bi bi-person-badge" aria-hidden="true"></i>
                    @endif
                </div>
            </div>

            <div class="principal-body">
                <span class="eyebrow"><i class="bi bi-chat-quote-fill" aria-hidden="true"></i> {{ __('ui.home.principal_eyebrow') }}</span>
                <h2 class="principal-title">{{ __('ui.home.principal_title') }}</h2>

                <figure class="principal-figure">
                    <blockquote class="principal-quote">{{ __('ui.home.principal_quote') }}</blockquote>

                    <p class="principal-text">{{ \Illuminate\Support\Str::limit($principal['message'], 300) }}</p>

                    <figcaption class="principal-sign">
                        <div>
                            <strong>{{ $principal['name'] }}</strong>
                            <span>{{ $principal['designation'] }}</span>
                        </div>
                        <a href="{{ route('principal') }}" class="principal-link">
                            {{ __('ui.common.read_more') }} <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </a>
                    </figcaption>
                </figure>
            </div>
        </div>
    </div>
</section>

{{-- ===================== TEACHERS ===================== --}}
@if ($featuredTeachers->isNotEmpty())
    @php
        $leadTeacher = $featuredTeachers->first();
        $otherTeachers = $featuredTeachers->slice(1);
        $leadMeta = collect([$leadTeacher->department, $leadTeacher->qualification])->filter()->implode(' · ');
    @endphp

    <section class="section">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow"><i class="bi bi-people-fill" aria-hidden="true"></i> {{ __('ui.home.faculty_eyebrow') }}</span>
                <h2 class="section-title">{{ __('ui.home.faculty_title') }}</h2>
                <p class="section-sub">{{ __('ui.home.faculty_sub') }}</p>
            </div>

            <div class="faculty {{ $featuredTeachers->count() === 1 ? 'faculty-solo' : '' }}">
                <a href="{{ route('teachers.show', $leadTeacher) }}" class="faculty-lead reveal">
                    <span class="faculty-lead-photo">
                        @if ($leadTeacher->photo)
                            <img src="{{ asset('storage/' . $leadTeacher->photo) }}" alt="{{ $leadTeacher->name }}">
                        @else
                            {{ $leadTeacher->initials() }}
                        @endif
                        <span class="dept-chip">{{ $leadTeacher->department }}</span>
                    </span>

                    <span class="faculty-lead-body">
                        <h3>{{ $leadTeacher->name }}</h3>
                        <span class="faculty-role">{{ $leadTeacher->designation }}</span>
                        @if ($leadMeta !== '')
                            <span class="faculty-lead-meta">{{ $leadMeta }}</span>
                        @endif
                        <span class="faculty-go">
                            {{ __('ui.home.view_profile') }} <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </span>
                    </span>
                </a>

                @if ($otherTeachers->isNotEmpty())
                    <div class="faculty-list">
                        @foreach ($otherTeachers as $teacher)
                            <a href="{{ route('teachers.show', $teacher) }}" class="faculty-row reveal">
                                <span class="avatar">
                                    @if ($teacher->photo)
                                        <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->name }}">
                                    @else
                                        {{ $teacher->initials() }}
                                    @endif
                                </span>
                                <span class="faculty-row-text">
                                    <strong>{{ $teacher->name }}</strong>
                                    <span>{{ $teacher->designation }}{{ $teacher->department ? ' · ' . $teacher->department : '' }}</span>
                                </span>
                                <i class="bi bi-arrow-right faculty-row-go" aria-hidden="true"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <a href="{{ route('teachers') }}" class="prog-all">
                {{ __('ui.home.faculty_all') }} <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
    </section>
@endif

{{-- ===================== LATEST NOTICES ===================== --}}
@if ($notices->isNotEmpty())
    <section class="section section-alt">
        <div class="container">
            <div class="sechead reveal">
                <div>
                    <span class="eyebrow"><i class="bi bi-megaphone-fill" aria-hidden="true"></i> {{ __('ui.home.notices_eyebrow') }}</span>
                    <h2 class="section-title">{{ __('ui.home.notices_title') }}</h2>
                </div>
                <a href="{{ route('notices') }}" class="sechead-all">
                    {{ __('ui.common.view_all') }} <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>

            <ul class="nlist">
                @foreach ($notices as $notice)
                    <li>
                        <a href="{{ route('notices.show', $notice) }}" class="nrow reveal">
                            @if ($notice->published_at)
                                <time class="ndate" datetime="{{ $notice->published_at->toDateString() }}">
                                    <span class="ndate-day">{{ $notice->published_at->format('d') }}</span>
                                    <span class="ndate-mon">{{ $notice->published_at->format('M') }}</span>
                                </time>
                            @endif

                            <span class="nrow-main">
                                <span class="nrow-top">
                                    <span class="badge">{{ __('ui.categories.' . $notice->category) }}</span>
                                </span>
                                <h3 class="nrow-title">{{ $notice->title }}</h3>
                                <span class="nrow-desc">{{ \Illuminate\Support\Str::limit(strip_tags($notice->description), 110) }}</span>
                            </span>

                            <i class="bi bi-arrow-right nrow-go" aria-hidden="true"></i>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endif

{{-- ===================== UPCOMING EVENTS ===================== --}}
@if ($events->isNotEmpty())
    <section class="section">
        <div class="container">
            <div class="sechead reveal">
                <div>
                    <span class="eyebrow"><i class="bi bi-calendar-event-fill" aria-hidden="true"></i> {{ __('ui.home.events_eyebrow') }}</span>
                    <h2 class="section-title">{{ __('ui.home.events_title') }}</h2>
                </div>
                <a href="{{ route('events') }}" class="sechead-all">
                    {{ __('ui.common.view_all') }} <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>

            <ol class="elist">
                @foreach ($events as $event)
                    <li>
                        <a href="{{ route('events.show', $event) }}" class="erow reveal">
                            <time class="edate" datetime="{{ $event->event_date->toDateString() }}">
                                <span class="edate-week">{{ $event->event_date->format('D') }}</span>
                                <span class="edate-day">{{ $event->event_date->format('d') }}</span>
                                <span class="edate-mon">{{ $event->event_date->format('M') }}</span>
                            </time>

                            <span class="erow-main">
                                <span class="echips">
                                    @if ($event->event_time)
                                        <span class="echip"><i class="bi bi-clock" aria-hidden="true"></i> {{ \Illuminate\Support\Str::substr($event->event_time, 0, 5) }}</span>
                                    @endif
                                    @if ($event->location)
                                        <span class="echip"><i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $event->location }}</span>
                                    @endif
                                </span>
                                <h3 class="erow-title">{{ $event->title }}</h3>
                                <span class="erow-desc">{{ \Illuminate\Support\Str::limit(strip_tags($event->description), 100) }}</span>
                            </span>

                            <i class="bi bi-arrow-right erow-go" aria-hidden="true"></i>
                        </a>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
@endif

{{-- ===================== LATEST NEWS ===================== --}}
@if ($latestNews->isNotEmpty())
    @php
        $leadArticle = $latestNews->first();
        $moreArticles = $latestNews->slice(1);
    @endphp

    <section class="section section-alt">
        <div class="container">
            <div class="sechead reveal">
                <div>
                    <span class="eyebrow"><i class="bi bi-newspaper" aria-hidden="true"></i> {{ __('ui.home.news_eyebrow') }}</span>
                    <h2 class="section-title">{{ __('ui.home.news_title') }}</h2>
                </div>
                <a href="{{ route('news') }}" class="sechead-all">
                    {{ __('ui.common.view_all') }} <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>

            <a href="{{ route('news.show', $leadArticle) }}" class="nwslead reveal">
                <span class="nwslead-media">
                    @if ($leadArticle->featured_image)
                        <img src="{{ asset('storage/' . $leadArticle->featured_image) }}" alt="{{ $leadArticle->title }}">
                    @else
                        <i class="bi bi-newspaper" aria-hidden="true"></i>
                    @endif
                    <span class="badge nwslead-badge">{{ __('ui.categories.' . $leadArticle->category) }}</span>
                </span>
                <span class="nwslead-body">
                    @if ($leadArticle->published_at)
                        <time class="nwslead-date" datetime="{{ $leadArticle->published_at->toDateString() }}">{{ $leadArticle->published_at->format('d M Y') }}</time>
                    @endif
                    <h3 class="nwslead-title">{{ $leadArticle->title }}</h3>
                    <span class="nwslead-desc">{{ \Illuminate\Support\Str::limit(strip_tags($leadArticle->description), 180) }}</span>
                    <span class="nwslead-go">{{ __('ui.common.read_more') }} <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
                </span>
            </a>

            @if ($moreArticles->isNotEmpty())
                <div class="nwsgrid">
                    @foreach ($moreArticles as $article)
                        <a href="{{ route('news.show', $article) }}" class="nwsmini reveal">
                            <span class="nwsmini-media">
                                @if ($article->featured_image)
                                    <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}">
                                @else
                                    <i class="bi bi-newspaper" aria-hidden="true"></i>
                                @endif
                            </span>
                            <span class="nwsmini-body">
                                <span class="nwsmini-top">
                                    <span class="badge">{{ __('ui.categories.' . $article->category) }}</span>
                                    @if ($article->published_at)
                                        <time datetime="{{ $article->published_at->toDateString() }}">{{ $article->published_at->format('d M Y') }}</time>
                                    @endif
                                </span>
                                <h3 class="nwsmini-title">{{ $article->title }}</h3>
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endif

{{-- ===================== GALLERY PREVIEW ===================== --}}
@if ($albums->isNotEmpty())
    @php $galleryWide = $albums->count() % 3 !== 0; @endphp

    <section class="section">
        <div class="container">
            <div class="sechead reveal">
                <div>
                    <span class="eyebrow"><i class="bi bi-images" aria-hidden="true"></i> {{ __('ui.home.gallery_eyebrow') }}</span>
                    <h2 class="section-title">{{ __('ui.home.gallery_title') }}</h2>
                </div>
                <a href="{{ route('gallery') }}" class="sechead-all">
                    {{ __('ui.common.view_all') }} <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>

            <div class="gal">
                @foreach ($albums as $album)
                    <a href="{{ route('gallery.show', $album) }}"
                       class="gal-item reveal{{ $galleryWide && $loop->last ? ' is-wide' : '' }}">
                        <span class="gal-media">
                            @if ($album->cover_image || $album->images->first())
                                <img src="{{ asset('storage/' . ($album->cover_image ?? $album->images->first()->image)) }}" alt="{{ $album->title }}">
                            @else
                                <span class="gal-fallback"><i class="bi bi-image" aria-hidden="true"></i></span>
                            @endif
                        </span>

                        <span class="gal-count">
                            <i class="bi bi-images" aria-hidden="true"></i> {{ $album->images->count() }}
                        </span>

                        <span class="gal-cap">
                            <h3 class="gal-cap-title">{{ $album->title }}</h3>
                            <span class="gal-cap-meta">{{ __('ui.categories.' . $album->category) }} &middot; {{ __('ui.home.photos') }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ===================== FACILITIES ===================== --}}
@php
    $facilityGroups = [
        'learning_spaces' => [
            ['bi-display', __('ui.facilities.smart_classrooms'), __('ui.facilities.smart_classrooms_short')],
            ['bi-droplet-half', __('ui.facilities.science_lab'), __('ui.facilities.science_lab_short')],
            ['bi-pc-display', __('ui.facilities.computer_lab'), __('ui.facilities.computer_lab_short')],
            ['bi-book', __('ui.facilities.library'), __('ui.facilities.library_short')],
        ],
        'campus_life' => [
            ['bi-trophy', __('ui.facilities.sports_ground'), __('ui.facilities.sports_ground_short')],
            ['bi-bus-front', __('ui.facilities.transport'), __('ui.facilities.transport_short')],
            ['bi-shield-fill-check', __('ui.facilities.security'), __('ui.facilities.security_short')],
            ['bi-cup-hot', __('ui.facilities.cafeteria'), __('ui.facilities.cafeteria_short')],
        ],
    ];
@endphp

<section class="section section-alt">
    <div class="container">
        <div class="sechead reveal">
            <div>
                <span class="eyebrow"><i class="bi bi-buildings" aria-hidden="true"></i> {{ __('ui.home.facilities_eyebrow') }}</span>
                <h2 class="section-title">{{ __('ui.home.facilities_title') }}</h2>
            </div>
            <a href="{{ route('facilities') }}" class="sechead-all">
                {{ __('ui.home.learn_facilities') }} <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

        <div class="fac-home">
            @foreach ($facilityGroups as $groupKey => $facilities)
                <h3 class="fac-home-title">{{ __("ui.facilities.$groupKey") }}</h3>

                @foreach ($facilities as $facility)
                    <div class="fac-home-item">
                        <i class="bi {{ $facility[0] }}" aria-hidden="true"></i>
                        <span>
                            <h4>{{ $facility[1] }}</h4>
                            <small>{{ $facility[2] }}</small>
                        </span>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== ADMISSION CTA ===================== --}}
<section class="section">
    <div class="container">
        <div class="cta-panel reveal">
            <div style="position:relative;z-index:1;">
                <span class="eyebrow" style="background:rgba(255,255,255,.16);color:#BFDBFE;">
                    <i class="bi bi-journal-text"></i>
                    {{ __('ui.admission.status_heading', ['status' => ($settings['admission_open'] ?? '1') === '1' ? __('ui.admission.status_open') : __('ui.admission.status_closed')]) }}
                </span>
                <h2 style="color:#fff;font-size:clamp(1.6rem,3.4vw,2.4rem);margin-bottom:12px;">{{ __('ui.home.cta_title') }}</h2>
                <p style="color:rgba(255,255,255,.82);max-width:600px;">{{ __('ui.home.cta_text') }}</p>
                <div class="row" style="gap:14px;margin-top:26px;flex-wrap:wrap;">
                    <a href="{{ route('admission') }}" class="btn" style="background:#fff;color:var(--secondary);">
                        <i class="bi bi-pencil-square"></i> {{ __('ui.admission.submit') }}
                    </a>
                    <a href="{{ route('admission.info') }}" class="btn btn-ghost">{{ __('ui.home.learn_admission') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== CONTACT PREVIEW ===================== --}}
<section class="section section-alt">
    <div class="container">
        <div class="grid grid-2" style="gap:52px;align-items:center;">
            <div class="reveal">
                <span class="eyebrow"><i class="bi bi-chat-dots-fill"></i> {{ __('ui.home.contact_eyebrow') }}</span>
                <h2 class="section-title">{{ __('ui.home.contact_title') }}</h2>
                <p class="section-sub" style="margin-bottom:24px;">{{ __('ui.home.contact_sub') }}</p>
                <a href="{{ route('contact.page') }}" class="btn btn-primary">{{ __('ui.home.contact_us') }}</a>
            </div>

            <div class="grid" style="gap:16px;">
                @foreach ([
                    ['bi-geo-alt-fill', __('ui.contact.address'), $settings['address'] ?? '123 Education Avenue, Springfield'],
                    ['bi-telephone-fill', __('ui.contact.phone'), $settings['phone'] ?? '+1 (555) 123-4567'],
                    ['bi-envelope-fill', __('ui.contact.email'), $settings['email'] ?? 'info@myschool.edu'],
                    ['bi-clock-fill', __('ui.home.office_hours'), $settings['office_hours'] ?? 'Mon - Fri, 8:00 AM - 4:00 PM'],
                ] as $info)
                    <div class="card reveal" style="padding:18px 22px;display:flex;gap:14px;align-items:center;">
                        <div class="icon-box" style="width:44px;height:44px;margin:0;font-size:1.05rem;"><i class="bi {{ $info[0] }}"></i></div>
                        <div>
                            <div class="muted" style="font-size:.74rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;">{{ $info[1] }}</div>
                            <div style="font-weight:600;font-size:.9rem;">{{ $info[2] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@endsection
