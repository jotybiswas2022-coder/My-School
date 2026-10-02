@extends('frontend.layouts.app')

@section('title', __('ui.subjects.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.subjects.title'),
        'subtitle' => __('ui.subjects.subtitle'),
        'crumbs' => [__('ui.nav.academics') => route('academics'), __('ui.subjects.title') => null],
    ])

    <section class="section">
        <div class="container">
            @if ($subjects->isEmpty())
                <div class="empty">
                    <div class="empty-icon"><i class="bi bi-journal-bookmark"></i></div>
                    <h3>{{ __('ui.subjects.empty_title') }}</h3>
                    <p>{{ __('ui.subjects.empty_text') }}</p>
                </div>
            @else
                <div class="grid grid-3">
                    @foreach ($subjects as $subject)
                        <div class="card card-hover reveal" style="padding:28px;">
                            <div class="row-between" style="margin-bottom:14px;">
                                <div class="icon-box" style="width:46px;height:46px;margin:0;font-size:1.05rem;">{{ mb_substr($subject->name, 0, 1) }}</div>
                                <span class="badge">{{ $subject->code }}</span>
                            </div>
                            <h3 style="font-size:1.05rem;margin-bottom:8px;">{{ $subject->name }}</h3>
                            <p class="muted" style="font-size:.85rem;margin-bottom:16px;">
                                {{ \Illuminate\Support\Str::limit($subject->description ?? __('ui.subjects.subject_fallback'), 110) }}
                            </p>
                            <div class="meta">
                                @if ($subject->teacher)
                                    <span><i class="bi bi-person-fill"></i> {{ $subject->teacher->name }}</span>
                                @endif
                                @if ($subject->schoolClass)
                                    <span><i class="bi bi-easel"></i> {{ $subject->schoolClass->name }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
