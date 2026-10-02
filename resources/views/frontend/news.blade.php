@extends('frontend.layouts.app')

@section('title', __('ui.news.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.news.title'),
        'subtitle' => __('ui.news.subtitle'),
        'crumbs' => [__('ui.nav.news') => null],
    ])

    <section class="section">
        <div class="container">
            @if ($featured && ! request()->hasAny(['q', 'category']))
                <a href="{{ route('news.show', $featured) }}" class="card card-hover reveal featured" style="margin-bottom:40px;">
                    <div class="featured-media">
                        @if ($featured->featured_image)
                            <img src="{{ asset('storage/' . $featured->featured_image) }}" alt="{{ $featured->title }}">
                        @else
                            <i class="bi bi-newspaper"></i>
                        @endif
                    </div>
                    <div style="padding:40px;">
                        <span class="badge">{{ __('ui.news.featured') }} · {{ __('ui.categories.' . $featured->category) }}</span>
                        <h2 style="font-size:clamp(1.3rem,2.6vw,1.9rem);margin:16px 0 12px;">{{ $featured->title }}</h2>
                        <p class="muted" style="margin-bottom:18px;">{{ \Illuminate\Support\Str::limit(strip_tags($featured->description), 200) }}</p>
                        <div class="meta"><span><i class="bi bi-calendar3"></i> {{ optional($featured->published_at)->format('d M Y') }}</span></div>
                    </div>
                </a>
            @endif

            <form method="GET" action="{{ route('news') }}" class="card reveal" style="padding:20px;margin-bottom:32px;">
                <div class="grid filter-grid" style="gap:12px;align-items:end;">
                    <div>
                        <label class="label" for="q">{{ __('ui.news.search_label') }}</label>
                        <input type="text" name="q" id="q" class="input" value="{{ request('q') }}" placeholder="{{ __('ui.news.search_placeholder') }}">
                    </div>
                    <div>
                        <label class="label" for="category">{{ __('ui.common.category') }}</label>
                        <select name="category" id="category" class="select">
                            <option value="">{{ __('ui.news.all_categories') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" @selected(request('category') === $category)>{{ __('ui.categories.' . $category) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-funnel"></i> {{ __('ui.common.filter') }}</button>
                        @if (request()->hasAny(['q', 'category']))
                            <a href="{{ route('news') }}" class="btn btn-outline">{{ __('ui.common.reset') }}</a>
                        @endif
                    </div>
                </div>
            </form>

            @if ($news->isEmpty())
                <div class="empty">
                    <div class="empty-icon"><i class="bi bi-newspaper"></i></div>
                    <h3>{{ __('ui.news.empty_title') }}</h3>
                    <p>{{ __('ui.news.empty_text') }}</p>
                </div>
            @else
                <div class="grid grid-3">
                    @foreach ($news as $article)
                        <article class="card card-hover reveal">
                            <div class="thumb">
                                @if ($article->featured_image)
                                    <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}">
                                @else
                                    <i class="bi bi-newspaper"></i>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="row-between" style="margin-bottom:10px;">
                                    <span class="badge">{{ __('ui.categories.' . $article->category) }}</span>
                                    <span class="muted" style="font-size:.76rem;font-weight:600;">{{ optional($article->published_at)->format('d M Y') }}</span>
                                </div>
                                <h3 style="font-size:1.02rem;margin-bottom:8px;">{{ $article->title }}</h3>
                                <p class="muted" style="font-size:.86rem;margin-bottom:16px;">{{ \Illuminate\Support\Str::limit(strip_tags($article->description), 100) }}</p>
                                <a href="{{ route('news.show', $article) }}" class="btn btn-outline btn-sm">{{ __('ui.common.read_more') }}</a>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{ $news->links() }}
            @endif
        </div>
    </section>

    <style>
        .featured { display: grid; grid-template-columns: 1.1fr 1fr; gap: 0; }
        .featured-media { background: linear-gradient(135deg,#DBEAFE,#BFDBFE); display: grid; place-items: center; font-size: 3.4rem; color: var(--primary); min-height: 280px; }
        .featured-media img { width: 100%; height: 100%; object-fit: cover; }
        .filter-grid { grid-template-columns: 2fr 1fr auto; }
        @media (max-width: 940px) { .filter-grid { grid-template-columns: 1fr; } }
        @media (max-width: 820px) { .featured { grid-template-columns: 1fr; } }
    </style>
@endsection
