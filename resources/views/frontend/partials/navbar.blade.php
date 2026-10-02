@php
    $schoolName = $settings['school_name'] ?? 'My School';
@endphp

<nav class="site-nav" id="siteNav">
    <div class="container container-wide">
        <div class="nav-inner">
            <a href="{{ route('home') }}" class="brand">
                <span class="brand-mark">
                    @if (! empty($settings['logo']))
                        <img src="{{ asset('storage/' . $settings['logo']) }}" alt="{{ $schoolName }} logo">
                    @else
                        MS
                    @endif
                </span>
                <span class="brand-text">
                    <span class="brand-name" title="{{ $schoolName }}">{{ $schoolName }}</span>
                    <small>{{ $settings['tagline'] ?? __('ui.home.meta_tagline') }}</small>
                </span>
            </a>

            <div class="nav-links" id="navLinks">
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                    {{ __('ui.nav.about') }}
                </a>
                <a href="{{ route('academics') }}" class="nav-link {{ request()->routeIs('academics') ? 'active' : '' }}">
                    {{ __('ui.nav.academics') }}
                </a>
                <a href="{{ route('teachers') }}" class="nav-link {{ request()->routeIs('teachers*') ? 'active' : '' }}">
                    {{ __('ui.nav.teachers') }}
                </a>
                <a href="{{ route('notices') }}" class="nav-link {{ request()->routeIs('notices*') ? 'active' : '' }}">
                    {{ __('ui.nav.notices') }}
                </a>
                <a href="{{ route('events') }}" class="nav-link {{ request()->routeIs('events*') ? 'active' : '' }}">
                    {{ __('ui.nav.events') }}
                </a>
                <a href="{{ route('news') }}" class="nav-link {{ request()->routeIs('news*') ? 'active' : '' }}">
                    {{ __('ui.nav.news') }}
                </a>
                <a href="{{ route('gallery') }}" class="nav-link {{ request()->routeIs('gallery*') ? 'active' : '' }}">
                    {{ __('ui.nav.gallery') }}
                </a>
                <a href="{{ route('results') }}" class="nav-link {{ request()->routeIs('results*') ? 'active' : '' }}">
                    {{ __('ui.nav.results') }}
                </a>
                <a href="{{ route('admission') }}" class="nav-link {{ request()->routeIs('admission*') ? 'active' : '' }}">
                    {{ __('ui.nav.admission') }}
                </a>
                <a href="{{ route('contact.page') }}" class="nav-link {{ request()->routeIs('contact*') ? 'active' : '' }}">
                    {{ __('ui.nav.contact') }}
                </a>

                @include('frontend.partials.language-switch')

                <div class="nav-cta">
                    @if (auth('student')->check())
                        <a href="{{ route('student.dashboard') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-speedometer2"></i>
                            <span>{{ __('ui.nav.dashboard') }}</span>
                        </a>
                    @else
                        <a href="{{ route('student.login') }}" class="btn btn-outline btn-sm">
                            <i class="bi bi-person-circle"></i>
                            <span>{{ __('ui.nav.student_login') }}</span>
                        </a>
                    @endif
                </div>
            </div>

            <button class="nav-toggle" id="navToggle" type="button" aria-label="{{ __('ui.common.menu') ?? 'Menu' }}" aria-expanded="false" aria-controls="navLinks">
                <span></span>
            </button>
        </div>
    </div>
</nav>
