@extends('frontend.layouts.app')

@section('title', __('ui.principal.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.principal.title'),
        'subtitle' => __('ui.principal.subtitle'),
        'crumbs' => [__('ui.nav.about') => route('about'), __('ui.principal.title') => null],
    ])

    <section class="section">
        <div class="container">
            <div class="principal-page">
                {{-- Profile column: portrait, name, role and a real mailto link --}}
                <aside class="principal-side reveal">
                    <div class="principal-side-inner">
                        <div class="principal-media">
                            <span class="principal-frame" aria-hidden="true"></span>
                            <div class="principal-photo">
                                @if (! empty($principal['photo']))
                                    <img src="{{ asset('storage/' . $principal['photo']) }}" alt="{{ $principal['name'] }}">
                                @else
                                    <i class="bi bi-person-badge" aria-hidden="true"></i>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h2 class="principal-name">{{ $principal['name'] }}</h2>
                            <span class="principal-role">{{ $principal['designation'] }}</span>

                            @if (! empty($principal['email']))
                                <a href="mailto:{{ $principal['email'] }}" class="principal-mail">
                                    <i class="bi bi-envelope" aria-hidden="true"></i> {{ $principal['email'] }}
                                </a>
                            @endif
                        </div>
                    </div>
                </aside>

                {{-- The letter itself, with a drop cap and a signed sign-off --}}
                <div class="principal-letter reveal">
                    <span class="eyebrow"><i class="bi bi-chat-quote-fill" aria-hidden="true"></i> {{ __('ui.principal.message') }}</span>
                    <h2 class="principal-letter-title">{{ __('ui.principal.title') }}</h2>

                    <div class="prose">
                        @foreach (preg_split('/\r\n|\n|\r/', $principal['message']) as $paragraph)
                            @if (trim($paragraph) !== '')
                                <p>{{ $paragraph }}</p>
                            @endif
                        @endforeach
                    </div>

                    <div class="principal-signoff">
                        <strong>{{ $principal['name'] }}</strong>
                        <span>{{ $principal['designation'] }}</span>
                    </div>
                </div>
            </div>

            <div class="principal-values">
                <div class="principal-values-head reveal">
                    <span class="eyebrow"><i class="bi bi-compass-fill" aria-hidden="true"></i> {{ __('ui.principal.about_school') }}</span>
                    <h2>{{ __('ui.principal.values_title') }}</h2>
                </div>

                <div class="grid grid-3">
                    @foreach ([
                        ['bi-award-fill', __('ui.principal.value_excellence'), __('ui.principal.value_excellence_text')],
                        ['bi-shield-fill-check', __('ui.principal.value_character'), __('ui.principal.value_character_text')],
                        ['bi-people-fill', __('ui.principal.value_partnership'), __('ui.principal.value_partnership_text')],
                    ] as $v)
                        <div class="tile reveal">
                            <i class="bi {{ $v[0] }} tile-icon tile-icon-lead" aria-hidden="true"></i>
                            <h3>{{ $v[1] }}</h3>
                            <p>{{ $v[2] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
