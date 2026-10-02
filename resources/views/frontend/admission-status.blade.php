@extends('frontend.layouts.app')

@section('title', __('ui.admission.status_page_title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.admission.status_page_title'),
        'subtitle' => __('ui.admission.status_page_subtitle'),
        'crumbs' => [__('ui.nav.admission') => route('admission'), __('ui.admission.check_status_title') => null],
    ])

    <section class="section">
        <div class="container" style="max-width:760px;">
            @if (session('application_id'))
                <div class="alert alert-success reveal">
                    <div>
                        <strong><i class="bi bi-check-circle-fill"></i> {{ __('ui.admission.submitted_title') }}</strong>
                        <p style="margin-top:6px;">{!! __('ui.admission.submitted_text', ['id' => '<strong style="font-size:1.05rem;">' . e(session('application_id')) . '</strong>']) !!}</p>
                    </div>
                </div>
            @endif

            <form method="GET" action="{{ route('admission.status') }}" class="card reveal" style="padding:26px;margin-bottom:30px;">
                <label class="label" for="application_id">{{ __('ui.admission.application_id') }}</label>
                <div class="row" style="gap:12px;">
                    <input type="text" name="application_id" id="application_id" class="input" value="{{ $query }}" placeholder="e.g. ADM-2026-A1B2C3" required>
                    <button type="submit" class="btn btn-primary" style="flex-shrink:0;">{{ __('ui.common.check_status') }}</button>
                </div>
            </form>

            @if ($searched)
                @if (! $application)
                    <div class="empty">
                        <div class="empty-icon"><i class="bi bi-search"></i></div>
                        <h3>{{ __('ui.admission.not_found_title') }}</h3>
                        <p>{{ __('ui.admission.not_found_text') }}</p>
                    </div>
                @else
                    <div class="card reveal" style="overflow:hidden;">
                        <div style="padding:28px 30px;background:var(--gradient-soft);border-bottom:1px solid var(--border);" class="row-between">
                            <div>
                                <div class="muted" style="font-size:.74rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;">{{ __('ui.admission.application_id') }}</div>
                                <strong style="font-size:1.1rem;">{{ $application->application_id }}</strong>
                            </div>
                            <span class="badge" style="background:{{ $application->statusColor() }}1f;color:{{ $application->statusColor() }};">
                                {{ __('ui.admission.' . $application->status) }}
                            </span>
                        </div>

                        <div style="padding:30px;">
                            <div class="grid grid-2" style="gap:22px;">
                                @foreach ([
                                    [__('ui.admission.student_name'), $application->student_name],
                                    [__('ui.admission.dob'), $application->date_of_birth->format('d M Y')],
                                    [__('ui.admission.gender'), __('ui.admission.' . $application->gender)],
                                    [__('ui.admission.applying_class'), $application->applying_class],
                                    [__('ui.admission.previous_school'), $application->previous_school ?? '—'],
                                    [__('ui.admission.guardian_name'), $application->guardian_name],
                                    [__('ui.admission.guardian_phone'), $application->guardian_phone],
                                    [__('ui.admission.email'), $application->email ?? '—'],
                                    [__('ui.admission.applied_on'), $application->created_at->format('d M Y')],
                                ] as $row)
                                    <div>
                                        <div class="muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;">{{ $row[0] }}</div>
                                        <strong style="font-size:.92rem;">{{ $row[1] }}</strong>
                                    </div>
                                @endforeach
                            </div>

                            <div style="margin-top:26px;padding-top:22px;border-top:1px solid var(--border);">
                                <div class="muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;margin-bottom:8px;">{{ __('ui.admission.address') }}</div>
                                <p style="font-size:.9rem;">{{ $application->address }}</p>
                            </div>

                            @if ($application->additional_info)
                                <div style="margin-top:18px;">
                                    <div class="muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;margin-bottom:8px;">{{ __('ui.admission.additional_info') }}</div>
                                    <p style="font-size:.9rem;">{{ $application->additional_info }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </section>
@endsection
