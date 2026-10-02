@extends('frontend.layouts.app')

@section('title', __('ui.facilities.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.facilities.title'),
        'subtitle' => __('ui.facilities.subtitle'),
        'crumbs' => [__('ui.nav.about') => route('about'), __('ui.facilities.title') => null],
    ])

    <section class="section">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow"><i class="bi bi-buildings"></i> {{ __('ui.facilities.eyebrow') }}</span>
                <h2 class="section-title">{{ __('ui.facilities.heading') }}</h2>
                <p class="section-sub">{{ __('ui.facilities.sub') }}</p>
            </div>

            <div class="grid grid-auto">
                @foreach ([
                    ['bi-display', __('ui.facilities.smart_classrooms'), __('ui.facilities.smart_classrooms_text')],
                    ['bi-droplet-half', __('ui.facilities.science_lab'), __('ui.facilities.science_lab_text')],
                    ['bi-pc-display', __('ui.facilities.computer_lab'), __('ui.facilities.computer_lab_text')],
                    ['bi-book', __('ui.facilities.library'), __('ui.facilities.library_text')],
                    ['bi-trophy', __('ui.facilities.sports_ground'), __('ui.facilities.sports_ground_text')],
                    ['bi-bus-front', __('ui.facilities.transport'), __('ui.facilities.transport_text')],
                    ['bi-shield-fill-check', __('ui.facilities.security'), __('ui.facilities.security_text')],
                    ['bi-cup-hot', __('ui.facilities.cafeteria'), __('ui.facilities.cafeteria_text')],
                ] as $facility)
                    <div class="card card-hover reveal" style="padding:32px 28px;">
                        <div class="icon-box"><i class="bi {{ $facility[0] }}"></i></div>
                        <h3 style="font-size:1.08rem;margin-bottom:8px;">{{ $facility[1] }}</h3>
                        <p class="muted" style="font-size:.88rem;">{{ $facility[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section-alt">
        <div class="container">
            <div class="cta-simple reveal">
                <div>
                    <h2 style="font-size:1.6rem;margin-bottom:8px;">{{ __('ui.facilities.tour_title') }}</h2>
                    <p class="muted">{{ __('ui.facilities.tour_text') }}</p>
                </div>
                <div class="row" style="gap:12px;flex-wrap:wrap;">
                    <a href="{{ route('contact.page') }}" class="btn btn-primary">{{ __('ui.facilities.book_visit') }}</a>
                    <a href="{{ route('admission') }}" class="btn btn-outline">{{ __('ui.common.apply_now') }}</a>
                </div>
            </div>
        </div>
    </section>

    <style>
        .cta-simple {
            display: flex; align-items: center; justify-content: space-between; gap: 24px; flex-wrap: wrap;
            background: var(--gradient-soft); border: 1px solid rgba(37,99,235,.2);
            border-radius: var(--radius-lg); padding: 44px;
        }
    </style>
@endsection
