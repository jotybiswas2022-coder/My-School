@extends('frontend.layouts.app')

@section('title', __('ui.facilities.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.facilities.title'),
        'subtitle' => __('ui.facilities.subtitle'),
        'crumbs' => [__('ui.nav.about') => route('about'), __('ui.facilities.title') => null],
    ])

    @php
        $facilityGroups = [
            'learning_spaces' => [
                ['bi-display', __('ui.facilities.smart_classrooms'), __('ui.facilities.smart_classrooms_text')],
                ['bi-droplet-half', __('ui.facilities.science_lab'), __('ui.facilities.science_lab_text')],
                ['bi-pc-display', __('ui.facilities.computer_lab'), __('ui.facilities.computer_lab_text')],
                ['bi-book', __('ui.facilities.library'), __('ui.facilities.library_text')],
            ],
            'campus_life' => [
                ['bi-trophy', __('ui.facilities.sports_ground'), __('ui.facilities.sports_ground_text')],
                ['bi-bus-front', __('ui.facilities.transport'), __('ui.facilities.transport_text')],
                ['bi-shield-fill-check', __('ui.facilities.security'), __('ui.facilities.security_text')],
                ['bi-cup-hot', __('ui.facilities.cafeteria'), __('ui.facilities.cafeteria_text')],
            ],
        ];
    @endphp

    <section class="section">
        <div class="container">
            <div class="section-head reveal">
                <span class="eyebrow"><i class="bi bi-buildings" aria-hidden="true"></i> {{ __('ui.facilities.eyebrow') }}</span>
                <h2 class="section-title">{{ __('ui.facilities.heading') }}</h2>
                <p class="section-sub">{{ __('ui.facilities.sub') }}</p>
            </div>

            @foreach ($facilityGroups as $groupKey => $facilities)
                <div class="fac-group">
                    <h3 class="fac-group-title reveal">{{ __("ui.facilities.$groupKey") }}</h3>

                    <div class="grid-tiles">
                        @foreach ($facilities as $facility)
                            <div class="tile reveal">
                                <i class="bi {{ $facility[0] }} tile-icon tile-icon-lead" aria-hidden="true"></i>
                                <h3>{{ $facility[1] }}</h3>
                                <p>{{ $facility[2] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="section section-alt">
        <div class="container">
            <div class="fac-cta reveal">
                <div>
                    <h2>{{ __('ui.facilities.tour_title') }}</h2>
                    <p>{{ __('ui.facilities.tour_text') }}</p>
                </div>
                <div class="fac-cta-actions">
                    <a href="{{ route('contact.page') }}" class="btn btn-invert">{{ __('ui.facilities.book_visit') }}</a>
                    <a href="{{ route('admission') }}" class="btn btn-ghost">{{ __('ui.common.apply_now') }}</a>
                </div>
            </div>
        </div>
    </section>
@endsection
