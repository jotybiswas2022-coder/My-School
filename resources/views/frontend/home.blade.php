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
                        <span class="faculty-dept">{{ $leadTeacher->department }}</span>
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
            <div class="row-between reveal" style="margin-bottom:40px;align-items:flex-end;">
                <div>
                    <span class="eyebrow"><i class="bi bi-megaphone-fill"></i> {{ __('ui.home.notices_eyebrow') }}</span>
                    <h2 class="section-title" style="margin-bottom:0;">{{ __('ui.home.notices_title') }}</h2>
                </div>
                <a href="{{ route('notices') }}" class="btn btn-outline btn-sm">{{ __('ui.common.view_all') }}</a>
            </div>

            <div class="grid grid-2">
                @foreach ($notices as $notice)
                    <div class="card card-hover reveal">
                        <div class="card-body">
                            <div class="row-between" style="margin-bottom:12px;">
                                <span class="badge">{{ __('ui.categories.' . $notice->category) }}</span>
                                <span class="muted" style="font-size:.78rem;font-weight:600;">
                                    <i class="bi bi-calendar3"></i> {{ optional($notice->published_at)->format('d M Y') }}
                                </span>
                            </div>
                            <h3 style="font-size:1.04rem;margin-bottom:8px;">{{ $notice->title }}</h3>
                            <p class="muted" style="font-size:.86rem;margin-bottom:14px;">{{ \Illuminate\Support\Str::limit(strip_tags($notice->description), 120) }}</p>
                            <a href="{{ route('notices.show', $notice) }}" class="btn btn-outline btn-sm">{{ __('ui.common.view_details') }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ===================== UPCOMING EVENTS ===================== --}}
@if ($events->isNotEmpty())
    <section class="section">
        <div class="container">
            <div class="row-between reveal" style="margin-bottom:40px;align-items:flex-end;">
                <div>
                    <span class="eyebrow"><i class="bi bi-calendar-event-fill"></i> {{ __('ui.home.events_eyebrow') }}</span>
                    <h2 class="section-title" style="margin-bottom:0;">{{ __('ui.home.events_title') }}</h2>
                </div>
                <a href="{{ route('events') }}" class="btn btn-outline btn-sm">{{ __('ui.common.view_all') }}</a>
            </div>

            <div class="grid grid-3">
                @foreach ($events as $event)
                    <div class="card card-hover reveal">
                        <div class="thumb">
                            @if ($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}">
                            @else
                                <i class="bi bi-calendar-event"></i>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="badge badge-success" style="margin-bottom:10px;">
                                <i class="bi bi-calendar3"></i> {{ $event->event_date->format('d M Y') }}
                            </div>
                            <h3 style="font-size:1.02rem;margin-bottom:8px;">{{ $event->title }}</h3>
                            <div class="meta" style="margin-bottom:12px;">
                                @if ($event->event_time)<span><i class="bi bi-clock"></i> {{ \Illuminate\Support\Str::substr($event->event_time, 0, 5) }}</span>@endif
                                @if ($event->location)<span><i class="bi bi-geo-alt"></i> {{ $event->location }}</span>@endif
                            </div>
                            <p class="muted" style="font-size:.85rem;margin-bottom:14px;">{{ \Illuminate\Support\Str::limit(strip_tags($event->description), 90) }}</p>
                            <a href="{{ route('events.show', $event) }}" class="btn btn-outline btn-sm">{{ __('ui.common.view_details') }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ===================== LATEST NEWS ===================== --}}
@if ($latestNews->isNotEmpty())
    <section class="section section-alt">
        <div class="container">
            <div class="row-between reveal" style="margin-bottom:40px;align-items:flex-end;">
                <div>
                    <span class="eyebrow"><i class="bi bi-newspaper"></i> {{ __('ui.home.news_eyebrow') }}</span>
                    <h2 class="section-title" style="margin-bottom:0;">{{ __('ui.home.news_title') }}</h2>
                </div>
                <a href="{{ route('news') }}" class="btn btn-outline btn-sm">{{ __('ui.common.view_all') }}</a>
            </div>

            <div class="grid grid-3">
                @foreach ($latestNews as $article)
                    <div class="card card-hover reveal">
                        <div class="thumb">
                            @if ($article->featured_image)
                                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}">
                            @else
                                <i class="bi bi-newspaper"></i>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row-between" style="margin-bottom:10px;">
                                <span class="badge">{{ __('ui.categories.' . $article->category) }}</span>
                                <span class="muted" style="font-size:.76rem;font-weight:600;">{{ optional($article->published_at)->format('d M Y') }}</span>
                            </div>
                            <h3 style="font-size:1.02rem;margin-bottom:8px;">{{ $article->title }}</h3>
                            <p class="muted" style="font-size:.85rem;margin-bottom:14px;">{{ \Illuminate\Support\Str::limit(strip_tags($article->description), 90) }}</p>
                            <a href="{{ route('news.show', $article) }}" class="btn btn-outline btn-sm">{{ __('ui.common.read_more') }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ===================== GALLERY PREVIEW (masonry) ===================== --}}
@if ($albums->isNotEmpty())
    <section class="section">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow"><i class="bi bi-images"></i> {{ __('ui.home.gallery_eyebrow') }}</span>
                <h2 class="section-title">{{ __('ui.home.gallery_title') }}</h2>
            </div>

            <div class="masonry reveal">
                @foreach ($albums as $album)
                    <a href="{{ route('gallery.show', $album) }}" class="masonry-item">
                        @if ($album->cover_image || $album->images->first())
                            <img src="{{ asset('storage/' . ($album->cover_image ?? $album->images->first()->image)) }}" alt="{{ $album->title }}">
                        @else
                            <span class="masonry-fallback"><i class="bi bi-image"></i></span>
                        @endif
                        <span class="masonry-overlay">
                            <strong>{{ $album->title }}</strong>
                            <small>{{ __('ui.categories.' . $album->category) }} · {{ $album->images->count() }} {{ __('ui.home.photos') }}</small>
                        </span>
                    </a>
                @endforeach
            </div>

            <div style="text-align:center;margin-top:36px;">
                <a href="{{ route('gallery') }}" class="btn btn-primary">
                    <i class="bi bi-images"></i> {{ __('ui.common.view_all') }}
                </a>
            </div>
        </div>
    </section>
@endif

{{-- ===================== FACILITIES ===================== --}}
<section class="section section-alt">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow"><i class="bi bi-buildings"></i> {{ __('ui.home.facilities_eyebrow') }}</span>
            <h2 class="section-title">{{ __('ui.home.facilities_title') }}</h2>
        </div>

        <div class="grid grid-4">
            @foreach ([
                ['bi-display', __('ui.facilities.smart_classrooms')],
                ['bi-droplet-half', __('ui.facilities.science_lab')],
                ['bi-pc-display', __('ui.facilities.computer_lab')],
                ['bi-book', __('ui.facilities.library')],
                ['bi-trophy', __('ui.facilities.sports_ground')],
                ['bi-bus-front', __('ui.facilities.transport')],
                ['bi-shield-fill-check', __('ui.facilities.security')],
                ['bi-cup-hot', __('ui.facilities.cafeteria')],
            ] as $facility)
                <div class="card card-hover reveal" style="text-align:center;padding:28px 20px;">
                    <div class="icon-box" style="margin:0 auto 14px;"><i class="bi {{ $facility[0] }}"></i></div>
                    <h4 style="font-size:.94rem;">{{ $facility[1] }}</h4>
                </div>
            @endforeach
        </div>

        <div style="text-align:center;margin-top:36px;">
            <a href="{{ route('facilities') }}" class="btn btn-outline">{{ __('ui.home.learn_facilities') }}</a>
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

<style>
    .masonry { columns: 3; column-gap: 20px; }
    .masonry-item {
        position: relative; break-inside: avoid; margin-bottom: 20px;
        border-radius: var(--radius); overflow: hidden; background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
        min-height: 220px; display: grid; place-items: center;
    }
    .masonry-item img { width: 100%; height: auto; display: block; transition: transform .55s cubic-bezier(.4,0,.2,1); }
    .masonry-fallback { font-size: 3rem; color: var(--primary); }
    .masonry-item:hover img { transform: scale(1.08); }
    .masonry-overlay {
        position: absolute; inset: auto 0 0 0; padding: 22px 20px;
        background: linear-gradient(to top, rgba(15,23,42,.88), transparent);
        color: #fff; display: flex; flex-direction: column; gap: 2px;
        opacity: 0; transform: translateY(12px); transition: all .35s ease;
    }
    .masonry-item:hover .masonry-overlay { opacity: 1; transform: none; }
    .masonry-overlay small { color: rgba(255,255,255,.7); font-size: .76rem; }

    .cta-panel {
        position: relative; overflow: hidden; border-radius: var(--radius-lg); padding: 58px 48px;
        background: linear-gradient(135deg, #1D4ED8, #2563EB 55%, #0EA5E9);
        box-shadow: 0 30px 70px -30px rgba(37,99,235,.7);
    }
    .cta-panel::before {
        content: ''; position: absolute; width: 420px; height: 420px; border-radius: 50%;
        background: rgba(255,255,255,.14); filter: blur(70px); top: -160px; right: -100px;
    }

    @media (max-width: 900px) { .masonry { columns: 2; } }
    @media (max-width: 600px) { .masonry { columns: 1; } .cta-panel { padding: 42px 26px; } }
</style>

@endsection
