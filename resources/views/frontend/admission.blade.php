@extends('frontend.layouts.app')

@section('title', __('ui.admission.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.admission.title'),
        'subtitle' => __('ui.admission.subtitle'),
        'crumbs' => [__('ui.nav.admission') => null],
    ])

    <section class="section">
        <div class="container">
            <div class="grid admission-grid" style="gap:36px;align-items:start;">
                <div class="card reveal" style="padding:38px;">
                    <h2 style="font-size:1.4rem;margin-bottom:6px;">{{ __('ui.admission.form_title') }}</h2>
                    <p class="muted" style="margin-bottom:28px;">{{ __('ui.common.required_fields') }}</p>

                    <form method="POST" action="{{ route('admission.store') }}" novalidate id="admissionForm">
                        @csrf

                        <h3 style="font-size:.95rem;margin-bottom:16px;color:var(--primary);">{{ __('ui.admission.student_section') }}</h3>
                        <div class="grid grid-2" style="gap:0 20px;">
                            <div class="form-group">
                                <label class="label" for="student_name">{{ __('ui.admission.student_name') }} <span style="color:var(--danger);">*</span></label>
                                <input type="text" name="student_name" id="student_name" class="input @error('student_name') is-invalid @enderror" value="{{ old('student_name') }}" required>
                                @error('student_name')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="label" for="date_of_birth">{{ __('ui.admission.dob') }} <span style="color:var(--danger);">*</span></label>
                                <input type="date" name="date_of_birth" id="date_of_birth" class="input @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}" max="{{ now()->subDay()->format('Y-m-d') }}" required>
                                @error('date_of_birth')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="label" for="gender">{{ __('ui.admission.gender') }} <span style="color:var(--danger);">*</span></label>
                                <select name="gender" id="gender" class="select @error('gender') is-invalid @enderror" required>
                                    <option value="">{{ __('ui.admission.select_gender') }}</option>
                                    @foreach (['male' => __('ui.admission.male'), 'female' => __('ui.admission.female'), 'other' => __('ui.admission.other')] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('gender') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('gender')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="label" for="applying_class">{{ __('ui.admission.applying_class') }} <span style="color:var(--danger);">*</span></label>
                                <select name="applying_class" id="applying_class" class="select @error('applying_class') is-invalid @enderror" required>
                                    <option value="">{{ __('ui.admission.select_class') }}</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class }}" @selected(old('applying_class') === $class)>{{ $class }}</option>
                                    @endforeach
                                </select>
                                @error('applying_class')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group" style="grid-column:1/-1;">
                                <label class="label" for="previous_school">{{ __('ui.admission.previous_school') }}</label>
                                <input type="text" name="previous_school" id="previous_school" class="input" value="{{ old('previous_school') }}" placeholder="{{ __('ui.common.optional') }}">
                            </div>
                        </div>

                        <h3 style="font-size:.95rem;margin:18px 0 16px;color:var(--primary);">{{ __('ui.admission.guardian_section') }}</h3>
                        <div class="grid grid-2" style="gap:0 20px;">
                            <div class="form-group">
                                <label class="label" for="guardian_name">{{ __('ui.admission.guardian_name') }} <span style="color:var(--danger);">*</span></label>
                                <input type="text" name="guardian_name" id="guardian_name" class="input @error('guardian_name') is-invalid @enderror" value="{{ old('guardian_name') }}" required>
                                @error('guardian_name')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="label" for="guardian_phone">{{ __('ui.admission.guardian_phone') }} <span style="color:var(--danger);">*</span></label>
                                <input type="text" name="guardian_phone" id="guardian_phone" class="input @error('guardian_phone') is-invalid @enderror" value="{{ old('guardian_phone') }}" required>
                                @error('guardian_phone')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group" style="grid-column:1/-1;">
                                <label class="label" for="email">{{ __('ui.admission.email') }}</label>
                                <input type="email" name="email" id="email" class="input @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="{{ __('ui.common.optional') }}">
                                @error('email')<div class="error-text">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="label" for="address">{{ __('ui.admission.address') }} <span style="color:var(--danger);">*</span></label>
                            <textarea name="address" id="address" class="textarea @error('address') is-invalid @enderror" style="min-height:90px;" required>{{ old('address') }}</textarea>
                            @error('address')<div class="error-text">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="label" for="additional_info">{{ __('ui.admission.additional_info') }}</label>
                            <textarea name="additional_info" id="additional_info" class="textarea @error('additional_info') is-invalid @enderror" style="min-height:90px;" placeholder="{{ __('ui.admission.additional_placeholder') }}">{{ old('additional_info') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" id="admissionSubmit">{{ __('ui.admission.submit') }}</button>
                    </form>
                </div>

                <aside class="stack reveal admission-aside" style="position:sticky;top:100px;gap:18px;">
                    <div class="card" style="padding:26px;background:var(--gradient);color:#fff;border:none;">
                        <h3 style="color:#fff;font-size:1.05rem;margin-bottom:8px;">{{ __('ui.admission.status_heading', ['status' => $admissionOpen ? __('ui.admission.status_open') : __('ui.admission.status_closed')]) }}</h3>
                        <p style="color:rgba(255,255,255,.8);font-size:.85rem;">
                            {{ $admissionOpen ? __('ui.admission.open_text') : __('ui.admission.closed_text') }}
                        </p>
                    </div>

                    <div class="card" style="padding:26px;">
                        <h3 style="font-size:1.02rem;margin-bottom:16px;">{{ __('ui.admission.how_it_works') }}</h3>
                        <ol style="padding-left:18px;display:flex;flex-direction:column;gap:12px;">
                            @foreach ([
                                __('ui.admission.step_form'),
                                __('ui.admission.step_id'),
                                __('ui.admission.step_review'),
                                __('ui.admission.step_decision'),
                            ] as $step)
                                <li style="font-size:.86rem;color:var(--muted);">{{ $step }}</li>
                            @endforeach
                        </ol>
                    </div>

                    <div class="card" style="padding:26px;">
                        <h3 style="font-size:1.02rem;margin-bottom:12px;">{{ __('ui.admission.check_status_title') }}</h3>
                        <p class="muted" style="font-size:.84rem;margin-bottom:14px;">{{ __('ui.admission.check_status_text') }}</p>
                        <a href="{{ route('admission.status') }}" class="btn btn-outline btn-block btn-sm">{{ __('ui.admission.check_status_button') }}</a>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <style>
        .admission-grid { grid-template-columns: 1fr 320px; }
        @media (max-width: 940px) {
            .admission-grid { grid-template-columns: 1fr; }
            .admission-aside { position: static !important; }
        }
    </style>

    @push('scripts')
        <script>
            (function () {
                const form = document.getElementById('admissionForm');
                const button = document.getElementById('admissionSubmit');
                if (!form) return;

                form.addEventListener('submit', () => {
                    button.disabled = true;
                    button.textContent = @json(__('ui.common.submitting'));
                });
            })();
        </script>
    @endpush
@endsection
