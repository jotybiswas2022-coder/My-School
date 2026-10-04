@extends('frontend.layouts.app')

@section('title', __('ui.classes.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.classes.title'),
        'subtitle' => __('ui.classes.subtitle'),
        'crumbs' => [__('ui.nav.academics') => route('academics'), __('ui.classes.title') => null],
    ])

    <section class="section">
        <div class="container">
            @if ($classes->isEmpty())
                <div class="empty">
                    <div class="empty-icon"><i class="bi bi-easel"></i></div>
                    <h3>{{ __('ui.classes.empty_title') }}</h3>
                    <p>{{ __('ui.classes.empty_text') }}</p>
                </div>
            @else
                <p class="cls-hint">
                    <i class="bi bi-chevron-down" aria-hidden="true"></i>
                    {{ __('ui.classes.hint') }}
                </p>

                <div class="cls-list">
                    @foreach ($classes as $class)
                        {{-- Native disclosure: no JS, keyboard operable, first class open by default --}}
                        <details class="cls reveal"{{ $loop->first ? ' open' : '' }}>
                            <summary class="cls-head">
                                <span class="cls-initial" aria-hidden="true">{{ mb_substr($class->name, 0, 1) }}</span>

                                <div class="cls-title">
                                    <h3>{{ $class->name }}</h3>
                                    <span class="cls-meta">
                                        {{ __('ui.classes.students_enrolled', ['count' => $class->students_count]) }}
                                        <span class="cls-dot" aria-hidden="true"></span>
                                        {{ __('ui.classes.counts', ['sections' => $class->sections->count(), 'subjects' => $class->subjects->count()]) }}
                                    </span>
                                </div>

                                @if ($class->code)
                                    <span class="cls-code">{{ $class->code }}</span>
                                @endif

                                <i class="bi bi-chevron-down cls-caret" aria-hidden="true"></i>
                            </summary>

                            <div class="cls-body">
                                <p class="cls-desc">{{ $class->description ?? __('ui.classes.class_fallback') }}</p>

                                <div class="cls-group">
                                    <span class="cls-label">{{ __('ui.classes.sections') }}</span>
                                    <div class="cls-chips">
                                        @forelse ($class->sections as $section)
                                            <span class="cls-chip">{{ __('ui.classes.section_label', ['name' => $section->name]) }}</span>
                                        @empty
                                            <span class="muted cls-empty">{{ __('ui.academics.no_sections') }}</span>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="cls-group">
                                    <span class="cls-label">{{ __('ui.classes.subjects') }}</span>
                                    <div class="cls-chips">
                                        @forelse ($class->subjects as $subject)
                                            <span class="cls-chip cls-chip-subject">{{ $subject->name }}</span>
                                        @empty
                                            <span class="muted cls-empty">{{ __('ui.classes.subjects_to_be_assigned') }}</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </details>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
