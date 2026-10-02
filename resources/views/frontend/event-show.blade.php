@extends('frontend.layouts.app')

@section('title', $event->title . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => $event->title,
        'subtitle' => $event->event_date->format('l, d F Y') . ($event->location ? ' · ' . $event->location : ''),
        'crumbs' => [__('ui.nav.events') => route('events'), __('ui.common.view_details') => null],
    ])

    <section class="section">
        <div class="container">
            <div class="grid event-show-grid" style="gap:36px;align-items:start;">
                <article class="card reveal" style="padding:0;overflow:hidden;">
                    @if ($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" style="width:100%;max-height:420px;object-fit:cover;">
                    @endif
                    <div style="padding:40px;">
                        <div class="prose">
                            @foreach (preg_split('/\r\n|\r|\n/', $event->description) as $paragraph)
                                @if (trim($paragraph) !== '')
                                    <p>{{ $paragraph }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </article>

                <aside class="card reveal event-show-aside" style="padding:30px;position:sticky;top:100px;">
                    <h3 style="font-size:1.05rem;margin-bottom:20px;">{{ __('ui.events.details') }}</h3>

                    <div class="stack" style="gap:16px;">
                        <div>
                            <div class="muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;">{{ __('ui.common.date') }}</div>
                            <strong style="font-size:.92rem;">{{ $event->event_date->format('l, d M Y') }}</strong>
                        </div>
                        @if ($event->event_time)
                            <div>
                                <div class="muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;">{{ __('ui.common.time') }}</div>
                                <strong style="font-size:.92rem;">{{ \Illuminate\Support\Str::substr($event->event_time, 0, 5) }}</strong>
                            </div>
                        @endif
                        @if ($event->location)
                            <div>
                                <div class="muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;">{{ __('ui.common.location') }}</div>
                                <strong style="font-size:.92rem;">{{ $event->location }}</strong>
                            </div>
                        @endif
                        <div>
                            <div class="muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;">{{ __('ui.common.status') }}</div>
                            <span class="badge {{ $event->event_date->isFuture() ? 'badge-success' : '' }}">
                                {{ $event->event_date->isFuture() ? __('ui.events.upcoming') : __('ui.events.completed') }}
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('events') }}" class="btn btn-outline btn-block btn-sm" style="margin-top:24px;"><i class="bi bi-arrow-left"></i> {{ __('ui.events.back_to_events') }}</a>
                </aside>
            </div>

            @if ($others->isNotEmpty())
                <div style="margin-top:56px;">
                    <h3 style="margin-bottom:22px;">{{ __('ui.events.other_upcoming') }}</h3>
                    <div class="grid grid-3">
                        @foreach ($others as $other)
                            <a href="{{ route('events.show', $other) }}" class="card card-hover reveal" style="padding:24px;">
                                <div class="badge badge-success" style="margin-bottom:10px;">{{ $other->event_date->format('d M Y') }}</div>
                                <strong style="display:block;font-size:.98rem;margin-bottom:6px;">{{ $other->title }}</strong>
                                <span class="muted" style="font-size:.8rem;">{{ $other->location }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <style>
        .event-show-grid { grid-template-columns: 1fr 340px; }
        @media (max-width: 940px) {
            .event-show-grid { grid-template-columns: 1fr; }
            .event-show-aside { position: static !important; }
        }
    </style>
@endsection
