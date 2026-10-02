@extends('frontend.layouts.app')

@section('title', __('ui.notices.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.notices.title'),
        'subtitle' => __('ui.notices.subtitle'),
        'crumbs' => [__('ui.nav.notices') => null],
    ])

    <section class="section">
        <div class="container">
            <div class="grid notices-grid" style="gap:36px;align-items:start;">
                <div>
                    <form method="GET" action="{{ route('notices') }}" class="card reveal" style="padding:20px;margin-bottom:28px;">
                        <div class="grid filter-grid" style="gap:12px;align-items:end;">
                            <div>
                                <label class="label" for="q">{{ __('ui.notices.search_label') }}</label>
                                <input type="text" name="q" id="q" class="input" value="{{ request('q') }}" placeholder="{{ __('ui.notices.search_placeholder') }}">
                            </div>
                            <div>
                                <label class="label" for="category">{{ __('ui.common.category') }}</label>
                                <select name="category" id="category" class="select">
                                    <option value="">{{ __('ui.notices.all_categories') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category }}" @selected(request('category') === $category)>{{ __('ui.categories.' . $category) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <button class="btn btn-primary" type="submit"><i class="bi bi-funnel"></i> {{ __('ui.common.filter') }}</button>
                                @if (request()->hasAny(['q', 'category']))
                                    <a href="{{ route('notices') }}" class="btn btn-outline">{{ __('ui.common.reset') }}</a>
                                @endif
                            </div>
                        </div>
                    </form>

                    @if ($notices->isEmpty())
                        <div class="empty">
                            <div class="empty-icon"><i class="bi bi-megaphone"></i></div>
                            <h3>{{ __('ui.notices.empty_title') }}</h3>
                            <p>{{ __('ui.notices.empty_text') }}</p>
                        </div>
                    @else
                        <div class="stack" style="gap:18px;">
                            @foreach ($notices as $notice)
                                <article class="card card-hover reveal" style="padding:28px;">
                                    <div class="row-between" style="margin-bottom:12px;">
                                        <span class="badge">{{ __('ui.categories.' . $notice->category) }}</span>
                                        <span class="muted" style="font-size:.78rem;font-weight:600;"><i class="bi bi-calendar3"></i> {{ optional($notice->published_at)->format('d M Y') }}</span>
                                    </div>
                                    <h3 style="font-size:1.12rem;margin-bottom:8px;">
                                        <a href="{{ route('notices.show', $notice) }}" style="transition:color .2s;">{{ $notice->title }}</a>
                                    </h3>
                                    <p class="muted" style="font-size:.88rem;margin-bottom:16px;">{{ \Illuminate\Support\Str::limit(strip_tags($notice->description), 180) }}</p>
                                    <div class="row-between">
                                        <a href="{{ route('notices.show', $notice) }}" class="btn btn-outline btn-sm">{{ __('ui.common.view_details') }}</a>
                                        @if ($notice->attachment)
                                            <a href="{{ asset('storage/' . $notice->attachment) }}" class="muted" style="font-size:.8rem;font-weight:600;" target="_blank" rel="noopener"><i class="bi bi-paperclip"></i> {{ __('ui.notices.attachment') }}</a>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        {{ $notices->links() }}
                    @endif
                </div>

                <aside class="reveal notices-aside" style="position:sticky;top:100px;">
                    <div class="card" style="padding:26px;">
                        <h3 style="font-size:1.02rem;margin-bottom:18px;">{{ __('ui.notices.latest') }}</h3>
                        @forelse ($latest as $item)
                            <a href="{{ route('notices.show', $item) }}" style="display:block;padding:12px 0;border-bottom:1px solid var(--border);transition:padding-left .2s;">
                                <strong style="font-size:.86rem;display:block;">{{ \Illuminate\Support\Str::limit($item->title, 60) }}</strong>
                                <span class="muted" style="font-size:.74rem;">{{ optional($item->published_at)->format('d M Y') }}</span>
                            </a>
                        @empty
                            <p class="muted" style="font-size:.85rem;">{{ __('ui.notices.none_yet') }}</p>
                        @endforelse
                        <a href="{{ route('notices') }}" class="btn btn-primary btn-block btn-sm" style="margin-top:18px;">{{ __('ui.notices.all') }}</a>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <style>
        .notices-grid { grid-template-columns: 1fr 320px; }
        .filter-grid { grid-template-columns: 2fr 1fr auto; }
        @media (max-width: 940px) {
            .notices-grid { grid-template-columns: 1fr; }
            .notices-aside { position: static !important; }
            .filter-grid { grid-template-columns: 1fr; }
        }
    </style>
@endsection
