@extends('frontend.layouts.app')

@section('title', $article->title . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => $article->title,
        'subtitle' => __('ui.categories.' . $article->category) . ' · ' . optional($article->published_at)->format('d M Y'),
        'crumbs' => [__('ui.nav.news') => route('news'), __('ui.news.article') => null],
    ])

    <section class="section">
        <div class="container">
            <div class="grid news-show-grid" style="gap:36px;align-items:start;">
                <article class="card reveal" style="padding:0;overflow:hidden;">
                    @if ($article->featured_image)
                        <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" style="width:100%;max-height:440px;object-fit:cover;">
                    @endif
                    <div style="padding:40px;">
                        <div class="row-between" style="margin-bottom:22px;">
                            <span class="badge">{{ __('ui.categories.' . $article->category) }}</span>
                            <span class="muted" style="font-size:.82rem;font-weight:600;"><i class="bi bi-calendar3"></i> {{ optional($article->published_at)->format('d M Y') }}</span>
                        </div>

                        <div class="prose">
                            @foreach (preg_split('/\r\n|\r|\n/', $article->description) as $paragraph)
                                @if (trim($paragraph) !== '')
                                    <p>{{ $paragraph }}</p>
                                @endif
                            @endforeach
                        </div>

                        <div style="margin-top:28px;">
                            <a href="{{ route('news') }}" class="btn btn-primary btn-sm"><i class="bi bi-arrow-left"></i> {{ __('ui.news.back_to_news') }}</a>
                        </div>
                    </div>
                </article>

                <aside class="reveal news-show-aside" style="position:sticky;top:100px;">
                    <div class="card" style="padding:26px;">
                        <h3 style="font-size:1.02rem;margin-bottom:18px;">{{ __('ui.news.related') }}</h3>
                        @forelse ($related as $item)
                            <a href="{{ route('news.show', $item) }}" style="display:block;padding:12px 0;border-bottom:1px solid var(--border);">
                                <strong style="font-size:.85rem;display:block;">{{ \Illuminate\Support\Str::limit($item->title, 65) }}</strong>
                                <span class="muted" style="font-size:.74rem;">{{ optional($item->published_at)->format('d M Y') }}</span>
                            </a>
                        @empty
                            <p class="muted" style="font-size:.85rem;">{{ __('ui.news.no_related') }}</p>
                        @endforelse
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <style>
        .news-show-grid { grid-template-columns: 1fr 320px; }
        @media (max-width: 940px) {
            .news-show-grid { grid-template-columns: 1fr; }
            .news-show-aside { position: static !important; }
        }
    </style>
@endsection
