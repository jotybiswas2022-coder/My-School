@extends('frontend.layouts.app')

@section('title', __('ui.students.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.students.title'),
        'subtitle' => __('ui.students.subtitle'),
        'crumbs' => [__('ui.nav.about') => route('about'), __('ui.students.title') => null],
    ])

    <section class="section">
        <div class="container">
            <div class="alert alert-info reveal" style="margin-bottom:32px;">
                <i class="bi bi-shield-lock-fill"></i>
                <span>{{ __('ui.students.privacy_note') }}</span>
            </div>

            @if ($students->isEmpty())
                <div class="empty">
                    <div class="empty-icon"><i class="bi bi-people"></i></div>
                    <h3>{{ __('ui.students.empty_title') }}</h3>
                    <p>{{ __('ui.students.empty_text') }}</p>
                </div>
            @else
                <div class="grid grid-4">
                    @foreach ($students as $student)
                        <div class="card card-hover reveal" style="text-align:center;padding:28px 20px;">
                            <div class="avatar" style="width:78px;height:78px;margin:0 auto 14px;font-size:1.3rem;">
                                @if ($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}">
                                @else
                                    {{ $student->initials() }}
                                @endif
                            </div>
                            <h4 style="font-size:.98rem;margin-bottom:4px;">{{ $student->name }}</h4>
                            <p class="muted" style="font-size:.8rem;">{{ $student->schoolClass->name ?? '—' }}</p>
                            @if ($student->section)
                                <span class="badge" style="margin-top:8px;">{{ __('ui.classes.section_label', ['name' => $student->section->name]) }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{ $students->links() }}
            @endif
        </div>
    </section>
@endsection
