@extends('frontend.layouts.app')

@section('title', __('ui.events.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.events.title'),
        'subtitle' => __('ui.events.subtitle'),
        'crumbs' => [__('ui.nav.events') => null],
    ])

    <section class="section">
        <div class="container">
            <div class="tabs reveal">
                <a href="{{ route('events', ['tab' => 'upcoming']) }}" class="tab {{ $tab === 'upcoming' ? 'active' : '' }}">{{ __('ui.events.upcoming_tab') }}</a>
                <a href="{{ route('events', ['tab' => 'past']) }}" class="tab {{ $tab === 'past' ? 'active' : '' }}">{{ __('ui.events.past_tab') }}</a>
            </div>

            @if ($events->isEmpty())
                <div class="empty">
                    <div class="empty-icon"><i class="bi bi-calendar-x"></i></div>
                    <h3>{{ $tab === 'upcoming' ? __('ui.events.empty_upcoming') : __('ui.events.empty_past') }}</h3>
                    <p>{{ $tab === 'upcoming' ? __('ui.events.empty_upcoming_text') : __('ui.events.empty_past_text') }}</p>
                </div>
            @else
                <div class="grid grid-3">
                    @foreach ($events as $event)
                        <article class="card card-hover reveal">
                            <div class="thumb">
                                @if ($event->image)
                                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}">
                                @else
                                    <i class="bi bi-calendar-event"></i>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="badge {{ $tab === 'upcoming' ? 'badge-success' : '' }}" style="margin-bottom:10px;">
                                    {{ $event->event_date->format('d M Y') }}
                                </div>
                                <h3 style="font-size:1.05rem;margin-bottom:8px;">{{ $event->title }}</h3>
                                <div class="meta" style="margin-bottom:12px;">
                                    @if ($event->event_time)<span><i class="bi bi-clock"></i> {{ \Illuminate\Support\Str::substr($event->event_time, 0, 5) }}</span>@endif
                                    @if ($event->location)<span><i class="bi bi-geo-alt"></i> {{ $event->location }}</span>@endif
                                </div>
                                <p class="muted" style="font-size:.86rem;margin-bottom:16px;">{{ \Illuminate\Support\Str::limit(strip_tags($event->description), 100) }}</p>
                                <a href="{{ route('events.show', $event) }}" class="btn btn-outline btn-sm">{{ __('ui.common.view_details') }}</a>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{ $events->links() }}
            @endif
        </div>
    </section>

    <style>
        .tabs { display: flex; gap: 8px; margin-bottom: 32px; background: var(--white); padding: 8px; border-radius: 999px; border: 1px solid var(--border); width: fit-content; }
        .tab { padding: 11px 24px; border-radius: 999px; font-weight: 600; font-size: .88rem; color: var(--muted); transition: all .25s ease; }
        .tab:hover { color: var(--primary); }
        .tab.active { background: var(--gradient); color: #fff; box-shadow: 0 10px 22px -12px rgba(37,99,235,.9); }
    </style>
@endsection
