@extends('frontend.layouts.app')

@section('title', $notice->title . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => $notice->title,
        'subtitle' => __('ui.categories.' . $notice->category) . ' · ' . optional($notice->published_at)->format('d M Y'),
        'crumbs' => [__('ui.nav.notices') => route('notices'), __('ui.common.view_details') => null],
    ])

    <section class="section">
        <div class="container">
            <div class="grid notice-show-grid" style="gap:36px;align-items:start;">
                <article class="card reveal" style="padding:40px;">
                    <div class="row-between" style="margin-bottom:22px;">
                        <span class="badge">{{ __('ui.categories.' . $notice->category) }}</span>
                        <span class="muted" style="font-size:.82rem;font-weight:600;"><i class="bi bi-calendar3"></i> {{ __('ui.notices.published_on', ['date' => optional($notice->published_at)->format('d M Y')]) }}</span>
                    </div>

                    <div class="prose">
                        @foreach (preg_split('/\r\n|\r|\n/', $notice->description) as $paragraph)
                            @if (trim($paragraph) !== '')
                                <p>{{ $paragraph }}</p>
                            @endif
                        @endforeach
                    </div>

                    @if ($notice->attachment)
                        <div style="margin-top:28px;padding-top:24px;border-top:1px solid var(--border);">
                            <a href="{{ asset('storage/' . $notice->attachment) }}" class="btn btn-outline btn-sm" target="_blank" rel="noopener"><i class="bi bi-paperclip"></i> {{ __('ui.notices.download_attachment') }}</a>
                        </div>
                    @endif

                    <div style="margin-top:28px;">
                        <a href="{{ route('notices') }}" class="btn btn-primary btn-sm"><i class="bi bi-arrow-left"></i> {{ __('ui.notices.back_to_notices') }}</a>
                    </div>
                </article>

                <aside class="reveal notice-show-aside" style="position:sticky;top:100px;">
                    <div class="card" style="padding:26px;">
                        <h3 style="font-size:1.02rem;margin-bottom:18px;">{{ __('ui.notices.related') }}</h3>
                        @forelse ($related as $item)
                            <a href="{{ route('notices.show', $item) }}" style="display:block;padding:12px 0;border-bottom:1px solid var(--border);">
                                <strong style="font-size:.85rem;display:block;">{{ \Illuminate\Support\Str::limit($item->title, 60) }}</strong>
                                <span class="muted" style="font-size:.74rem;">{{ optional($item->published_at)->format('d M Y') }}</span>
                            </a>
                        @empty
                            <p class="muted" style="font-size:.85rem;">{{ __('ui.notices.no_related') }}</p>
                        @endforelse
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <style>
        .notice-show-grid { grid-template-columns: 1fr 320px; }
        @media (max-width: 940px) {
            .notice-show-grid { grid-template-columns: 1fr; }
            .notice-show-aside { position: static !important; }
        }
    </style>
@endsection
