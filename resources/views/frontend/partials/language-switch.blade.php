@php
    $locales = \App\Http\Middleware\SetLocale::SUPPORTED;
    $current = app()->getLocale();
@endphp

<div class="lang-switch" role="group" aria-label="{{ __('ui.nav.language') }}">
    <i class="bi bi-translate lang-icon" aria-hidden="true"></i>
    @foreach ($locales as $code => $label)
        <a href="{{ route('language.switch', $code) }}"
           class="lang-link {{ $current === $code ? 'active' : '' }}"
           @if ($current === $code) aria-current="true" @endif
           hreflang="{{ $code }}">
            {{ $code === 'bn' ? 'বাংলা' : 'English' }}
        </a>
    @endforeach
</div>
