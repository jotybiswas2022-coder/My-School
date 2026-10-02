@extends('frontend.layouts.app')

@section('title', __('ui.about.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.about.title'),
        'subtitle' => __('ui.about.subtitle'),
        'crumbs' => [__('ui.nav.about') => null],
    ])

    <section class="section">
        <div class="container">
            <div class="grid grid-2" style="gap:56px;align-items:center;">
                <div class="reveal">
                    <span class="eyebrow"><i class="bi bi-book-half"></i> {{ __('ui.about.story_eyebrow') }}</span>
                    <h2 class="section-title">{{ __('ui.about.story_title', ['year' => $settings['established_year'] ?? '1998']) }}</h2>
                    <div class="prose">
                        <p>{{ $settings['about_description'] ?? __('ui.about.story_fallback') }}</p>
                        <p>{{ __('ui.about.story_text_2') }}</p>
                    </div>
                </div>

                <div class="reveal grid grid-3" style="gap:16px;">
                    @foreach ([
                        ['bi-people-fill', __('ui.about.students'), $stats['students']],
                        ['bi-person-badge-fill', __('ui.about.teachers'), $stats['teachers']],
                        ['bi-easel-fill', __('ui.about.classes'), $stats['classes']],
                    ] as $s)
                        <div class="card card-hover" style="padding:26px 18px;text-align:center;">
                            <div class="icon-box" style="margin:0 auto 12px;"><i class="bi {{ $s[0] }}"></i></div>
                            <div style="font-size:1.9rem;font-weight:800;color:var(--primary);"><span data-count="{{ $s[2] }}">0</span></div>
                            <div class="muted" style="font-size:.8rem;font-weight:600;">{{ $s[1] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="section section-alt">
        <div class="container">
            <div class="grid grid-2" style="gap:32px;">
                <div class="card reveal" style="padding:36px;">
                    <div class="icon-box"><i class="bi bi-bullseye"></i></div>
                    <h3 style="margin-bottom:12px;">{{ __('ui.about.mission') }}</h3>
                    <p class="muted">{{ $settings['mission'] ?? __('ui.about.mission_fallback') }}</p>
                </div>
                <div class="card reveal" style="padding:36px;">
                    <div class="icon-box"><i class="bi bi-eye"></i></div>
                    <h3 style="margin-bottom:12px;">{{ __('ui.about.vision') }}</h3>
                    <p class="muted">{{ $settings['vision'] ?? __('ui.about.vision_fallback') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow"><i class="bi bi-gem"></i> {{ __('ui.about.values_eyebrow') }}</span>
                <h2 class="section-title">{{ __('ui.about.values_title') }}</h2>
            </div>
            <div class="grid grid-4">
                @foreach ([
                    ['bi-shield-fill-check', __('ui.about.value_integrity'), __('ui.about.value_integrity_text')],
                    ['bi-lightbulb', __('ui.about.value_curiosity'), __('ui.about.value_curiosity_text')],
                    ['bi-heart-fill', __('ui.about.value_respect'), __('ui.about.value_respect_text')],
                    ['bi-star-fill', __('ui.about.value_excellence'), __('ui.about.value_excellence_text')],
                ] as $value)
                    <div class="card card-hover reveal" style="padding:30px 24px;">
                        <div class="icon-box"><i class="bi {{ $value[0] }}"></i></div>
                        <h4 style="margin-bottom:8px;font-size:1rem;">{{ $value[1] }}</h4>
                        <p class="muted" style="font-size:.85rem;">{{ $value[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section-alt">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow"><i class="bi bi-patch-check-fill"></i> {{ __('ui.about.why_eyebrow') }}</span>
                <h2 class="section-title">{{ __('ui.about.why_title') }}</h2>
            </div>
            <div class="grid grid-3">
                @foreach ([
                    [__('ui.about.ach_results'), __('ui.about.ach_results_text')],
                    [__('ui.about.ach_faculty'), __('ui.about.ach_faculty_text')],
                    [__('ui.about.ach_facilities'), __('ui.about.ach_facilities_text')],
                    [__('ui.about.ach_cocurricular'), __('ui.about.ach_cocurricular_text')],
                    [__('ui.about.ach_safe'), __('ui.about.ach_safe_text')],
                    [__('ui.about.ach_community'), __('ui.about.ach_community_text')],
                ] as $item)
                    <div class="card card-hover reveal" style="padding:28px 24px;">
                        <div class="badge badge-success" style="margin-bottom:14px;"><i class="bi bi-check-lg"></i> {{ __('ui.about.highlight') }}</div>
                        <h4 style="font-size:1rem;margin-bottom:8px;">{{ $item[0] }}</h4>
                        <p class="muted" style="font-size:.85rem;">{{ $item[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="card reveal" style="padding:44px;background:var(--gradient-soft);border-color:rgba(37,99,235,.2);">
                <div class="grid grid-2" style="align-items:center;gap:36px;">
                    <div>
                        <span class="eyebrow"><i class="bi bi-chat-quote-fill"></i> {{ __('ui.about.principal_eyebrow') }}</span>
                        <h2 class="section-title" style="font-size:1.7rem;">{{ __('ui.about.principal_title') }}</h2>
                        <p class="muted" style="margin-bottom:22px;">{{ \Illuminate\Support\Str::limit($settings['principal_message'] ?? __('ui.principal.subtitle'), 220) }}</p>
                        <a href="{{ route('principal') }}" class="btn btn-primary">{{ __('ui.about.read_full_message') }}</a>
                    </div>
                    <div style="text-align:center;">
                        <div class="avatar" style="width:140px;height:140px;font-size:2.4rem;margin:0 auto 16px;">
                            @if (! empty($settings['principal_photo']))
                                <img src="{{ asset('storage/' . $settings['principal_photo']) }}" alt="{{ $settings['principal_name'] ?? '' }}">
                            @else
                                <i class="bi bi-person-badge"></i>
                            @endif
                        </div>
                        <strong>{{ $settings['principal_name'] ?? '' }}</strong>
                        <div class="muted" style="font-size:.84rem;">{{ $settings['principal_designation'] ?? '' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
