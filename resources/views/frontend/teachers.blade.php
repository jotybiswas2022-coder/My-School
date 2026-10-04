@extends('frontend.layouts.app')

@section('title', __('ui.teachers.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.teachers.title'),
        'subtitle' => __('ui.teachers.subtitle'),
        'crumbs' => [__('ui.nav.teachers') => null],
    ])

    <section class="section">
        <div class="container">
            <form method="GET" action="{{ route('teachers') }}" class="tbar reveal">
                <div>
                    <label class="label" for="q">{{ __('ui.teachers.search_label') }}</label>
                    <input type="text" name="q" id="q" class="input" value="{{ request('q') }}" placeholder="{{ __('ui.teachers.search_placeholder') }}">
                </div>
                <div>
                    <label class="label" for="department">{{ __('ui.teachers.department') }}</label>
                    <select name="department" id="department" class="select">
                        <option value="">{{ __('ui.teachers.all_departments') }}</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept }}" @selected(request('department') === $dept)>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row tbar-actions">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search" aria-hidden="true"></i> {{ __('ui.common.search') }}</button>
                    @if (request()->hasAny(['q', 'department']))
                        <a href="{{ route('teachers') }}" class="btn btn-outline">{{ __('ui.common.reset') }}</a>
                    @endif
                </div>

                @if ($teachers->isNotEmpty())
                    <p class="tbar-count">
                        <i class="bi bi-people-fill" aria-hidden="true"></i>
                        {{ __('ui.teachers.found', ['count' => $teachers->total()]) }}
                    </p>
                @endif
            </form>

            @if ($teachers->isEmpty())
                <div class="empty">
                    <div class="empty-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></div>
                    <h2>{{ __('ui.teachers.empty_title') }}</h2>
                    <p>{{ __('ui.teachers.empty_text') }}</p>
                </div>
            @else
                <div class="grid grid-4">
                    @foreach ($teachers as $teacher)
                        <a href="{{ route('teachers.show', $teacher) }}" class="tcard reveal">
                            <span class="tcard-media">
                                @if ($teacher->photo)
                                    <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->name }}">
                                @else
                                    {{ $teacher->initials() }}
                                @endif
                                @if ($teacher->department)
                                    <span class="dept-chip">{{ $teacher->department }}</span>
                                @endif
                            </span>

                            <span class="tcard-body">
                                <h2>{{ $teacher->name }}</h2>
                                <span class="tcard-role">{{ $teacher->designation }}</span>
                                @if ($teacher->qualification)
                                    <span class="tcard-qual">{{ $teacher->qualification }}</span>
                                @endif
                                <span class="tcard-go">
                                    {{ __('ui.home.view_profile') }} <i class="bi bi-arrow-right" aria-hidden="true"></i>
                                </span>
                            </span>
                        </a>
                    @endforeach
                </div>

                {{ $teachers->links() }}
            @endif
        </div>
    </section>
@endsection
