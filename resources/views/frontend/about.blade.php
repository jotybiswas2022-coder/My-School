@extends('frontend.layouts.app')

@section('title', __('ui.about.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.about.title'),
        'subtitle' => __('ui.about.subtitle'),
        'crumbs' => [__('ui.nav.about') => null],
    ])

    <div class="about-page">
        {{-- ===================== STORY + STATS ===================== --}}
        <section class="section">
            <div class="container">
                <div class="about-story">
                    <div class="reveal">
                        <span class="eyebrow"><i class="bi bi-book-half" aria-hidden="true"></i> {{ __('ui.about.story_eyebrow') }}</span>
                        <h2 class="section-title">{{ __('ui.about.story_title', ['year' => $settings['established_year'] ?? '1998']) }}</h2>
                        <div class="prose">
                            <p>{{ $settings['about_description'] ?? __('ui.about.story_fallback') }}</p>
                            <p>{{ __('ui.about.story_text_2') }}</p>
                        </div>
                    </div>

                    <div class="about-stats reveal">
                        @foreach ([
                            ['bi-people-fill', __('ui.about.students'), $stats['students']],
                            ['bi-person-badge-fill', __('ui.about.teachers'), $stats['teachers']],
                            ['bi-easel-fill', __('ui.about.classes'), $stats['classes']],
                        ] as $stat)
                            <div class="about-stat">
                                <span class="about-stat-icon"><i class="bi {{ $stat[0] }}" aria-hidden="true"></i></span>
                                <span class="about-stat-value" data-count="{{ $stat[2] }}">0</span>
                                <span class="about-stat-label">{{ $stat[1] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- ===================== MISSION & VISION ===================== --}}
        <section class="section section-alt">
            <div class="container">
                <ul class="about-pillars about-pillars-wide">
                    @foreach ([
                        ['bi-bullseye', __('ui.about.mission'), $settings['mission'] ?? __('ui.about.mission_fallback')],
                        ['bi-eye', __('ui.about.vision'), $settings['vision'] ?? __('ui.about.vision_fallback')],
                    ] as $pillar)
                        <li class="about-pillar">
                            <span class="about-pillar-icon"><i class="bi {{ $pillar[0] }}" aria-hidden="true"></i></span>
                            <div>
                                <h3>{{ $pillar[1] }}</h3>
                                <p>{{ $pillar[2] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{-- ===================== CORE VALUES ===================== --}}
        <section class="section">
            <div class="container">
                <div class="section-head reveal">
                    <span class="eyebrow"><i class="bi bi-gem" aria-hidden="true"></i> {{ __('ui.about.values_eyebrow') }}</span>
                    <h2 class="section-title">{{ __('ui.about.values_title') }}</h2>
                </div>

                <div class="grid-tiles">
                    @foreach ([
                        ['bi-shield-fill-check', __('ui.about.value_integrity'), __('ui.about.value_integrity_text')],
                        ['bi-lightbulb', __('ui.about.value_curiosity'), __('ui.about.value_curiosity_text')],
                        ['bi-heart-fill', __('ui.about.value_respect'), __('ui.about.value_respect_text')],
                        ['bi-star-fill', __('ui.about.value_excellence'), __('ui.about.value_excellence_text')],
                    ] as $i => $value)
                        <div class="tile reveal">
                            <div class="tile-top">
                                <span class="tile-num" aria-hidden="true">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                <i class="bi {{ $value[0] }} tile-icon" aria-hidden="true"></i>
                            </div>
                            <h4>{{ $value[1] }}</h4>
                            <p>{{ $value[2] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ===================== WHY CHOOSE US ===================== --}}
        <section class="section section-alt">
            <div class="container">
                <div class="section-head reveal">
                    <span class="eyebrow"><i class="bi bi-patch-check-fill" aria-hidden="true"></i> {{ __('ui.about.why_eyebrow') }}</span>
                    <h2 class="section-title">{{ __('ui.about.why_title') }}</h2>
                </div>

                <ul class="about-why">
                    @foreach ([
                        [__('ui.about.ach_results'), __('ui.about.ach_results_text')],
                        [__('ui.about.ach_faculty'), __('ui.about.ach_faculty_text')],
                        [__('ui.about.ach_facilities'), __('ui.about.ach_facilities_text')],
                        [__('ui.about.ach_cocurricular'), __('ui.about.ach_cocurricular_text')],
                        [__('ui.about.ach_safe'), __('ui.about.ach_safe_text')],
                        [__('ui.about.ach_community'), __('ui.about.ach_community_text')],
                    ] as $item)
                        <li class="about-why-item reveal">
                            <span class="about-why-check"><i class="bi bi-check-lg" aria-hidden="true"></i></span>
                            <div>
                                <h4>{{ $item[0] }}</h4>
                                <p>{{ $item[1] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{-- ===================== PRINCIPAL MESSAGE ===================== --}}
        <section class="section">
            <div class="container">
                <div class="about-quote reveal">
                    <div>
                        <span class="eyebrow"><i class="bi bi-chat-quote-fill" aria-hidden="true"></i> {{ __('ui.about.principal_eyebrow') }}</span>
                        <h2 class="section-title">{{ __('ui.about.principal_title') }}</h2>
                        <p class="muted">{{ \Illuminate\Support\Str::limit($settings['principal_message'] ?? __('ui.principal.subtitle'), 220) }}</p>
                        <a href="{{ route('principal') }}" class="btn btn-primary">{{ __('ui.about.read_full_message') }}</a>
                    </div>

                    <div class="about-quote-person">
                        <span class="about-quote-avatar">
                            @if (! empty($settings['principal_photo']))
                                <img src="{{ asset('storage/' . $settings['principal_photo']) }}" alt="{{ $settings['principal_name'] ?? '' }}">
                            @else
                                <i class="bi bi-person-badge" aria-hidden="true"></i>
                            @endif
                        </span>
                        <strong>{{ $settings['principal_name'] ?? '' }}</strong>
                        <span class="muted">{{ $settings['principal_designation'] ?? '' }}</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
