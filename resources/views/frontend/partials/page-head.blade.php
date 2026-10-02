@php
    $crumbs = $crumbs ?? [];
@endphp

<section class="page-head">
    <div class="container">
        <nav class="crumbs" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">{{ __('ui.nav.home') }}</a>
            @foreach ($crumbs as $label => $url)
                <span class="sep">/</span>
                @if ($url)
                    <a href="{{ $url }}">{{ $label }}</a>
                @else
                    <span>{{ $label }}</span>
                @endif
            @endforeach
        </nav>
        <h1>{{ $title }}</h1>
        @isset($subtitle)
            <p>{{ $subtitle }}</p>
        @endisset
    </div>
</section>
