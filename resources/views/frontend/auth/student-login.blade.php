@extends('frontend.layouts.app')

@section('title', __('ui.auth.student_title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    <section class="section">
        <div class="container" style="max-width:520px;">
            <div class="card reveal" style="padding:40px;">
                <div style="text-align:center;margin-bottom:28px;">
                    <div class="brand-mark" style="width:60px;height:60px;font-size:1.4rem;margin:0 auto 16px;border-radius:18px;">
                        @if (! empty($settings['logo']))
                            <img src="{{ asset('storage/' . $settings['logo']) }}" alt="{{ $settings['school_name'] ?? 'Logo' }}">
                        @else
                            MS
                        @endif
                    </div>
                    <h1 style="font-size:1.5rem;margin-bottom:6px;">{{ __('ui.auth.student_title') }}</h1>
                    <p class="muted" style="font-size:.9rem;">{{ __('ui.auth.student_subtitle') }}</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('student.login.attempt') }}">
                    @csrf

                    <div class="form-group">
                        <label class="label" for="student_id">{{ __('ui.auth.student_id') }}</label>
                        <input type="text" name="student_id" id="student_id" class="input @error('student_id') is-invalid @enderror"
                               value="{{ old('student_id') }}" placeholder="{{ __('ui.results.student_id_placeholder') }}" required autofocus>
                        @error('student_id')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="label" for="password">{{ __('ui.auth.password') }}</label>
                        <div style="position:relative;">
                            <input type="password" name="password" id="password" class="input" style="padding-right:52px;" required>
                            <button type="button" id="togglePassword" aria-label="{{ __('ui.auth.show_password') }}"
                                    style="position:absolute;right:8px;top:50%;transform:translateY(-50%);border:none;background:transparent;cursor:pointer;font-size:1.1rem;padding:6px;"><i class="bi bi-eye"></i></button>
                        </div>
                        @error('password')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="row" style="justify-content:space-between;margin-bottom:22px;">
                        <label class="row" style="gap:8px;font-size:.86rem;font-weight:600;color:var(--muted);cursor:pointer;">
                            <input type="checkbox" name="remember" value="1" style="width:16px;height:16px;accent-color:var(--primary);">
                            {{ __('ui.auth.remember') }}
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">{{ __('ui.auth.sign_in') }}</button>
                </form>

                <div style="text-align:center;margin-top:22px;">
                    <a href="{{ route('home') }}" class="muted" style="font-size:.85rem;font-weight:600;"><i class="bi bi-arrow-left"></i> {{ __('ui.auth.back_to_website') }}</a>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            (function () {
                const toggle = document.getElementById('togglePassword');
                const input = document.getElementById('password');
                if (!toggle || !input) return;

                toggle.innerHTML = '<i class="bi bi-eye"></i>';

                toggle.addEventListener('click', () => {
                    const show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    toggle.innerHTML = show ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
                });
            })();
        </script>
    @endpush
@endsection
