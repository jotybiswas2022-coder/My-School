@extends('frontend.layouts.app')

@section('title', __('ui.contact.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.contact.title'),
        'subtitle' => __('ui.contact.subtitle'),
        'crumbs' => [__('ui.nav.contact') => null],
    ])

    <section class="section">
        <div class="container">
            <div class="grid contact-grid" style="gap:44px;align-items:start;">
                <div class="card reveal" style="padding:38px;">
                    <h2 style="font-size:1.4rem;margin-bottom:6px;">{{ __('ui.contact.form_title') }}</h2>
                    <p class="muted" style="margin-bottom:26px;">{{ __('ui.contact.form_text') }}</p>

                    <form method="POST" action="{{ route('contact.store') }}" novalidate id="contactForm">
                        @csrf
                        <div class="grid grid-2" style="gap:0 20px;">
                            <div class="form-group">
                                <label class="label" for="name">{{ __('ui.contact.name') }} <span style="color:var(--danger);">*</span></label>
                                <input type="text" name="name" id="name" class="input @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="label" for="email">{{ __('ui.contact.email') }} <span style="color:var(--danger);">*</span></label>
                                <input type="email" name="email" id="email" class="input @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="label" for="phone">{{ __('ui.contact.phone') }}</label>
                                <input type="text" name="phone" id="phone" class="input" value="{{ old('phone') }}">
                            </div>
                            <div class="form-group">
                                <label class="label" for="subject">{{ __('ui.contact.subject') }} <span style="color:var(--danger);">*</span></label>
                                <input type="text" name="subject" id="subject" class="input @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required>
                                @error('subject')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="label" for="message">{{ __('ui.contact.message') }} <span style="color:var(--danger);">*</span></label>
                            <textarea name="message" id="message" class="textarea @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                            @error('message')<div class="error-text">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary" id="contactSubmit">{{ __('ui.contact.send') }}</button>
                    </form>
                </div>

                <aside class="stack reveal" style="gap:18px;">
                    @foreach ([
                        ['bi-geo-alt-fill', __('ui.contact.address'), $settings['address'] ?? '123 Education Avenue, Springfield'],
                        ['bi-telephone-fill', __('ui.common.phone'), $settings['phone'] ?? '+1 (555) 123-4567'],
                        ['bi-envelope-fill', __('ui.common.email'), $settings['email'] ?? 'info@myschool.edu'],
                        ['bi-clock-fill', __('ui.contact.office_hours'), $settings['office_hours'] ?? 'Mon - Fri, 8:00 AM - 4:00 PM'],
                    ] as $info)
                        <div class="card card-hover" style="padding:24px;display:flex;gap:16px;align-items:flex-start;">
                            <div class="icon-box" style="width:46px;height:46px;margin:0;font-size:1.05rem;flex-shrink:0;"><i class="bi {{ $info[0] }}"></i></div>
                            <div>
                                <div class="muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;">{{ $info[1] }}</div>
                                <strong style="font-size:.92rem;">{{ $info[2] }}</strong>
                            </div>
                        </div>
                    @endforeach

                    <div class="card" style="padding:0;overflow:hidden;">
                        <div style="height:220px;background:linear-gradient(135deg,#DBEAFE,#BFDBFE);display:grid;place-items:center;color:var(--primary);font-size:2.4rem;"><i class="bi bi-map"></i></div>
                        <div style="padding:20px;">
                            <strong style="font-size:.92rem;">{{ __('ui.contact.visit_title') }}</strong>
                            <p class="muted" style="font-size:.84rem;margin-top:6px;">{{ $settings['address'] ?? '123 Education Avenue, Springfield' }}</p>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <style>
        .contact-grid { grid-template-columns: 1.2fr 1fr; }
        @media (max-width: 940px) { .contact-grid { grid-template-columns: 1fr; } }
    </style>

    @push('scripts')
        <script>
            (function () {
                const form = document.getElementById('contactForm');
                const button = document.getElementById('contactSubmit');
                if (!form) return;

                form.addEventListener('submit', () => {
                    button.disabled = true;
                    button.textContent = @json(__('ui.common.sending'));
                });
            })();
        </script>
    @endpush
@endsection
