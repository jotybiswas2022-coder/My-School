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
                <div class="grid grid-2">
                    @foreach ($classes as $class)
                        <div class="card card-hover reveal" style="padding:32px;">
                            <div class="row-between" style="margin-bottom:18px;">
                                <div class="row">
                                    <div class="icon-box" style="width:50px;height:50px;margin:0;font-size:1.2rem;">{{ mb_substr($class->name, 0, 1) }}</div>
                                    <div>
                                        <h3 style="font-size:1.15rem;">{{ $class->name }}</h3>
                                        <span class="muted" style="font-size:.82rem;">{{ __('ui.classes.students_enrolled', ['count' => $class->students_count]) }}</span>
                                    </div>
                                </div>
                                @if ($class->code)
                                    <span class="badge">{{ $class->code }}</span>
                                @endif
                            </div>

                            <p class="muted" style="font-size:.88rem;margin-bottom:18px;">
                                {{ $class->description ?? __('ui.classes.class_fallback') }}
                            </p>

                            <div style="margin-bottom:16px;">
                                <div class="muted" style="font-size:.74rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;margin-bottom:8px;">{{ __('ui.classes.sections') }}</div>
                                <div class="row" style="flex-wrap:wrap;gap:8px;">
                                    @forelse ($class->sections as $section)
                                        <span class="badge">{{ __('ui.classes.section_label', ['name' => $section->name]) }}</span>
                                    @empty
                                        <span class="muted" style="font-size:.84rem;">{{ __('ui.academics.no_sections') }}</span>
                                    @endforelse
                                </div>
                            </div>

                            <div>
                                <div class="muted" style="font-size:.74rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;margin-bottom:8px;">{{ __('ui.classes.subjects') }}</div>
                                <div class="row" style="flex-wrap:wrap;gap:8px;">
                                    @forelse ($class->subjects as $subject)
                                        <span class="badge badge-success">{{ $subject->name }}</span>
                                    @empty
                                        <span class="muted" style="font-size:.84rem;">{{ __('ui.classes.subjects_to_be_assigned') }}</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
