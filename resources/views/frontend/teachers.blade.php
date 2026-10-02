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
            <form method="GET" action="{{ route('teachers') }}" class="card reveal" style="padding:20px;margin-bottom:36px;">
                <div class="grid" style="grid-template-columns:2fr 1.4fr auto;gap:14px;align-items:end;">
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
                    <div class="row">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> {{ __('ui.common.search') }}</button>
                        @if (request()->hasAny(['q', 'department']))
                            <a href="{{ route('teachers') }}" class="btn btn-outline">{{ __('ui.common.reset') }}</a>
                        @endif
                    </div>
                </div>
            </form>

            @if ($teachers->isEmpty())
                <div class="empty">
                    <div class="empty-icon"><i class="bi bi-person-badge"></i></div>
                    <h3>{{ __('ui.teachers.empty_title') }}</h3>
                    <p>{{ __('ui.teachers.empty_text') }}</p>
                </div>
            @else
                <div class="grid grid-4">
                    @foreach ($teachers as $teacher)
                        <div class="card card-hover reveal">
                            <div class="card-body" style="text-align:center;">
                                <div class="avatar" style="width:84px;height:84px;margin:0 auto 16px;font-size:1.4rem;">
                                    @if ($teacher->photo)
                                        <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->name }}">
                                    @else
                                        {{ $teacher->initials() }}
                                    @endif
                                </div>
                                <h4 style="font-size:1rem;margin-bottom:4px;">{{ $teacher->name }}</h4>
                                <p style="color:var(--primary);font-weight:600;font-size:.82rem;">{{ $teacher->designation }}</p>
                                <p class="muted" style="font-size:.78rem;margin-top:6px;">{{ $teacher->department }}</p>
                                @if ($teacher->qualification)
                                    <p class="muted" style="font-size:.76rem;margin-top:4px;">{{ $teacher->qualification }}</p>
                                @endif
                                <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-outline btn-sm" style="margin-top:16px;">{{ __('ui.home.view_profile') }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{ $teachers->links() }}
            @endif
        </div>
    </section>
@endsection
