@php
    $schoolName = $settings['school_name'] ?? 'My School';
    $socials = [
        'facebook' => ['Facebook', 'bi-facebook'],
        'twitter' => ['Twitter', 'bi-twitter-x'],
        'instagram' => ['Instagram', 'bi-instagram'],
        'youtube' => ['YouTube', 'bi-youtube'],
        'linkedin' => ['LinkedIn', 'bi-linkedin'],
    ];
@endphp

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="footer-brand">
                    <span class="brand-mark">
                        @if (! empty($settings['logo']))
                            <img src="{{ asset('storage/' . $settings['logo']) }}" alt="{{ $schoolName }} logo">
                        @else
                            MS
                        @endif
                    </span>
                    {{ $schoolName }}
                </div>
                <p style="font-size:.9rem;line-height:1.75;max-width:340px;">
                    {{ $settings['about_description'] ?? __('ui.footer.description_fallback') }}
                </p>

                <div class="socials">
                    @foreach ($socials as $key => [$label, $icon])
                        @if (! empty($settings[$key]))
                            <a href="{{ $settings[$key] }}" class="social" target="_blank" rel="noopener" aria-label="{{ $label }}">
                                <i class="bi {{ $icon }}"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <div>
                <h4>{{ __('ui.footer.quick_links') }}</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('about') }}">{{ __('ui.footer.about_school') }}</a></li>
                    <li><a href="{{ route('principal') }}">{{ __('ui.footer.principal_message') }}</a></li>
                    <li><a href="{{ route('teachers') }}">{{ __('ui.footer.teachers_staff') }}</a></li>
                    <li><a href="{{ route('students') }}">{{ __('ui.footer.students') }}</a></li>
                    <li><a href="{{ route('facilities') }}">{{ __('ui.footer.facilities') }}</a></li>
                </ul>
            </div>

            <div>
                <h4>{{ __('ui.footer.academics') }}</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('academics') }}">{{ __('ui.footer.academic_overview') }}</a></li>
                    <li><a href="{{ route('classes') }}">{{ __('ui.footer.classes') }}</a></li>
                    <li><a href="{{ route('subjects') }}">{{ __('ui.footer.subjects') }}</a></li>
                    <li><a href="{{ route('results') }}">{{ __('ui.footer.results') }}</a></li>
                    <li><a href="{{ route('admission') }}">{{ __('ui.footer.admission') }}</a></li>
                </ul>
            </div>

            <div>
                <h4>{{ __('ui.footer.news_updates') }}</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('notices') }}">{{ __('ui.nav.notices') }}</a></li>
                    <li><a href="{{ route('events') }}">{{ __('ui.nav.events') }}</a></li>
                    <li><a href="{{ route('news') }}">{{ __('ui.nav.news') }}</a></li>
                    <li><a href="{{ route('gallery') }}">{{ __('ui.nav.gallery') }}</a></li>
                </ul>
            </div>

            <div>
                <h4>{{ __('ui.footer.contact_us') }}</h4>
                <ul class="footer-contact">
                    @if (! empty($settings['address']))
                        <li><i class="bi bi-geo-alt-fill" style="color:#93C5FD;"></i> {{ $settings['address'] }}</li>
                    @endif
                    @if (! empty($settings['phone']))
                        <li><i class="bi bi-telephone-fill" style="color:#93C5FD;"></i> <a href="tel:{{ $settings['phone'] }}">{{ $settings['phone'] }}</a></li>
                    @endif
                    @if (! empty($settings['email']))
                        <li><i class="bi bi-envelope-fill" style="color:#93C5FD;"></i> <a href="mailto:{{ $settings['email'] }}">{{ $settings['email'] }}</a></li>
                    @endif
                    @if (! empty($settings['office_hours']))
                        <li><i class="bi bi-clock-fill" style="color:#93C5FD;"></i> {{ $settings['office_hours'] }}</li>
                    @endif
                </ul>
                <a href="{{ route('contact.page') }}" class="btn btn-primary btn-sm" style="margin-top:12px;">
                    <i class="bi bi-chat-dots"></i> {{ __('ui.home.contact_us') }}
                </a>
            </div>
        </div>

        <div class="footer-bottom">
            <span>{{ $settings['footer_text'] ?? ('© ' . date('Y') . ' ' . $schoolName . '. ' . __('ui.footer.all_rights')) }}</span>
            <span>
                <a href="{{ route('student.login') }}">{{ __('ui.footer.student_portal') }}</a>
                &nbsp;·&nbsp;
                <a href="{{ route('admin.login') }}">{{ __('ui.footer.admin_login') }}</a>
            </span>
        </div>
    </div>
</footer>
