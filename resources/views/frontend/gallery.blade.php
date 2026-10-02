@extends('frontend.layouts.app')

@section('title', __('ui.gallery.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.gallery.title'),
        'subtitle' => __('ui.gallery.subtitle'),
        'crumbs' => [__('ui.nav.gallery') => null],
    ])

    <section class="section">
        <div class="container">
            @if ($categories->isNotEmpty())
                <div class="row reveal" style="flex-wrap:wrap;gap:10px;margin-bottom:34px;justify-content:center;">
                    <a href="{{ route('gallery') }}" class="chip {{ request('category') ? '' : 'active' }}">{{ __('ui.gallery.all') }}</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('gallery', ['category' => $category]) }}" class="chip {{ request('category') === $category ? 'active' : '' }}">{{ $category }}</a>
                    @endforeach
                </div>
            @endif

            @if ($albums->isEmpty())
                <div class="empty">
                    <div class="empty-icon"><i class="bi bi-images"></i></div>
                    <h3>{{ __('ui.gallery.empty_title') }}</h3>
                    <p>{{ __('ui.gallery.empty_text') }}</p>
                </div>
            @else
                <div class="grid grid-3">
                    @foreach ($albums as $album)
                        <a href="{{ route('gallery.show', $album) }}" class="card card-hover reveal">
                            <div class="thumb" style="aspect-ratio:4/3;">
                                @if ($album->cover_image)
                                    <img src="{{ asset('storage/' . $album->cover_image) }}" alt="{{ $album->title }}">
                                @elseif ($album->images->first())
                                    <img src="{{ asset('storage/' . $album->images->first()->image) }}" alt="{{ $album->title }}">
                                @else
                                    <i class="bi bi-images"></i>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="row-between" style="margin-bottom:8px;">
                                    <span class="badge">{{ $album->category }}</span>
                                    <span class="muted" style="font-size:.76rem;font-weight:600;">{{ $album->images_count }} {{ __('ui.home.photos') }}</span>
                                </div>
                                <h3 style="font-size:1.05rem;margin-bottom:6px;">{{ $album->title }}</h3>
                                <p class="muted" style="font-size:.84rem;">{{ \Illuminate\Support\Str::limit($album->description ?? __('ui.gallery.album_fallback'), 80) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{ $albums->links() }}
            @endif
        </div>
    </section>

    <style>
        .chip { padding: 10px 20px; border-radius: 999px; border: 1px solid var(--border); background: var(--white); font-size: .84rem; font-weight: 600; color: var(--muted); transition: all .25s ease; }
        .chip:hover { border-color: var(--primary); color: var(--primary); }
        .chip.active { background: var(--gradient); color: #fff; border-color: transparent; box-shadow: 0 10px 22px -12px rgba(37,99,235,.9); }
    </style>
@endsection
