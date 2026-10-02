@extends('frontend.layouts.app')

@section('title', __('ui.principal.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.principal.title'),
        'subtitle' => __('ui.principal.subtitle'),
        'crumbs' => [__('ui.nav.about') => route('about'), __('ui.principal.title') => null],
    ])

    <section class="section">
        <div class="container" style="max-width:980px;">
            <div class="card reveal principal-card" style="padding:0;overflow:hidden;">
                <div style="background:var(--gradient-soft);padding:40px 30px;text-align:center;">
                    <div class="avatar" style="width:150px;height:150px;font-size:2.6rem;margin:0 auto 20px;">
                        @if (! empty($principal['photo']))
                            <img src="{{ asset('storage/' . $principal['photo']) }}" alt="{{ $principal['name'] }}">
                        @else
                            <i class="bi bi-person-badge"></i>
                        @endif
                    </div>
                    <h3 style="font-size:1.15rem;margin-bottom:4px;">{{ $principal['name'] }}</h3>
                    <p style="color:var(--primary);font-weight:600;font-size:.88rem;">{{ $principal['designation'] }}</p>
                    @if (! empty($principal['email']))
                        <p class="muted" style="font-size:.82rem;margin-top:8px;">
                            <i class="bi bi-envelope"></i> {{ $principal['email'] }}
                        </p>
                    @endif
                </div>

                <div class="card-body" style="padding:44px;">
                    <span class="eyebrow"><i class="bi bi-chat-quote-fill"></i> {{ __('ui.principal.message') }}</span>
                    <div class="prose">
                        @foreach (preg_split('/\r\n|\n|\r/', $principal['message']) as $paragraph)
                            @if (trim($paragraph) !== '')
                                <p>{{ $paragraph }}</p>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-3" style="margin-top:32px;">
                @foreach ([
                    ['bi-award-fill', __('ui.principal.value_excellence'), __('ui.principal.value_excellence_text')],
                    ['bi-shield-fill-check', __('ui.principal.value_character'), __('ui.principal.value_character_text')],
                    ['bi-people-fill', __('ui.principal.value_partnership'), __('ui.principal.value_partnership_text')],
                ] as $v)
                    <div class="card reveal" style="padding:26px;">
                        <div class="icon-box" style="width:46px;height:46px;font-size:1.1rem;"><i class="bi {{ $v[0] }}"></i></div>
                        <h4 style="font-size:.98rem;margin-bottom:6px;">{{ $v[1] }}</h4>
                        <p class="muted" style="font-size:.84rem;">{{ $v[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <style>
        .principal-card { display: grid; grid-template-columns: 320px 1fr; }
        @media (max-width: 780px) { .principal-card { grid-template-columns: 1fr; } }
    </style>
@endsection
