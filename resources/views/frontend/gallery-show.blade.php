@extends('frontend.layouts.app')

@section('title', $album->title . ' — ' . __('ui.gallery.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => $album->title,
        'subtitle' => $album->category . ' · ' . $album->images->count() . ' ' . __('ui.home.photos'),
        'crumbs' => [__('ui.nav.gallery') => route('gallery'), $album->title => null],
    ])

    <section class="section">
        <div class="container">
            @if ($album->description)
                <p class="section-sub reveal" style="max-width:720px;margin:0 auto 34px;text-align:center;">{{ $album->description }}</p>
            @endif

            @if ($album->images->isEmpty())
                <div class="empty">
                    <div class="empty-icon"><i class="bi bi-images"></i></div>
                    <h3>{{ __('ui.gallery.album_empty_title') }}</h3>
                    <p>{{ __('ui.gallery.album_empty_text') }}</p>
                </div>
            @else
                <div class="gallery-grid">
                    @foreach ($album->images as $image)
                        <button type="button" class="gallery-tile reveal"
                                data-src="{{ asset('storage/' . $image->image) }}"
                                data-caption="{{ $image->caption }}"
                                aria-label="{{ __('ui.gallery.view_image') }}">
                            <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->caption ?? $album->title }}" loading="lazy">
                            <span class="gallery-plus"><i class="bi bi-arrows-fullscreen"></i></span>
                        </button>
                    @endforeach
                </div>
            @endif

            @if ($others->isNotEmpty())
                <div style="margin-top:64px;">
                    <h3 style="margin-bottom:22px;">{{ __('ui.gallery.more_albums') }}</h3>
                    <div class="grid grid-3">
                        @foreach ($others as $other)
                            <a href="{{ route('gallery.show', $other) }}" class="card card-hover reveal">
                                <div class="thumb" style="aspect-ratio:4/3;">
                                    @if ($other->cover_image)
                                        <img src="{{ asset('storage/' . $other->cover_image) }}" alt="{{ $other->title }}">
                                    @elseif ($other->images->first())
                                        <img src="{{ asset('storage/' . $other->images->first()->image) }}" alt="{{ $other->title }}">
                                    @else
                                        <i class="bi bi-images"></i>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <strong style="font-size:.98rem;">{{ $other->title }}</strong>
                                    <div class="muted" style="font-size:.78rem;">{{ $other->images_count }} {{ __('ui.home.photos') }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Lightbox --}}
    <div class="modal-backdrop" id="lightbox">
        <div class="modal-box" style="background:transparent;box-shadow:none;max-width:1000px;text-align:center;">
            <button type="button" class="modal-close" data-close aria-label="{{ __('ui.common.close') }}"><i class="bi bi-x-lg"></i></button>
            <img id="lightboxImage" src="" alt="" style="width:100%;border-radius:20px;">
            <p id="lightboxCaption" style="color:#fff;margin-top:16px;font-weight:600;"></p>
        </div>
    </div>

    <style>
        .gallery-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
        .gallery-tile { position: relative; border: none; padding: 0; cursor: pointer; border-radius: var(--radius); overflow: hidden; background: linear-gradient(135deg,#DBEAFE,#BFDBFE); aspect-ratio: 1/1; }
        .gallery-tile img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s cubic-bezier(.4,0,.2,1); }
        .gallery-tile:hover img { transform: scale(1.09); }
        .gallery-plus {
            position: absolute; inset: 0; display: grid; place-items: center; font-size: 1.9rem; color: #fff;
            background: rgba(37,99,235,.55); opacity: 0; transition: opacity .3s ease;
        }
        .gallery-tile:hover .gallery-plus { opacity: 1; }
        @media (max-width: 900px) { .gallery-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 520px) { .gallery-grid { grid-template-columns: 1fr; } }
    </style>

    @push('scripts')
        <script>
            (function () {
                const lightbox = document.getElementById('lightbox');
                if (!lightbox) return;
                const img = document.getElementById('lightboxImage');
                const caption = document.getElementById('lightboxCaption');

                document.querySelectorAll('.gallery-tile').forEach((tile) => {
                    tile.addEventListener('click', () => {
                        img.src = tile.dataset.src;
                        caption.textContent = tile.dataset.caption || '';
                        lightbox.classList.add('open');
                    });
                });

                lightbox.addEventListener('click', (e) => {
                    if (e.target === lightbox || e.target.closest('[data-close]')) {
                        lightbox.classList.remove('open');
                        img.src = '';
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') lightbox.classList.remove('open');
                });
            })();
        </script>
    @endpush
@endsection
