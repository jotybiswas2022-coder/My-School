@extends('frontend.layouts.app')

@section('title', __('ui.results.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('content')
    @include('frontend.partials.page-head', [
        'title' => __('ui.results.title'),
        'subtitle' => __('ui.results.subtitle'),
        'crumbs' => [__('ui.nav.results') => null],
    ])

    <section class="section">
        <div class="container" style="max-width:1040px;">
            <form method="POST" action="{{ route('results.search') }}" class="card reveal results-form" style="padding:30px;margin-bottom:36px;">
                @csrf
                <div class="grid" style="gap:14px;align-items:end;">
                    <div>
                        <label class="label" for="student_id">{{ __('ui.results.student_id') }} <span style="color:var(--danger);">*</span></label>
                        <input type="text" name="student_id" id="student_id" class="input @error('student_id') is-invalid @enderror"
                               value="{{ old('student_id') }}" placeholder="{{ __('ui.results.student_id_placeholder') }}" required>
                        @error('student_id')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="label" for="exam_id">{{ __('ui.results.exam') }} <span style="color:var(--danger);">*</span></label>
                        <select name="exam_id" id="exam_id" class="select @error('exam_id') is-invalid @enderror" required>
                            <option value="">{{ __('ui.results.select_exam') }}</option>
                            @foreach ($exams as $examOption)
                                <option value="{{ $examOption->id }}" @selected(old('exam_id') == $examOption->id)>{{ $examOption->name }}</option>
                            @endforeach
                        </select>
                        @error('exam_id')<div class="error-text">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="label" for="academic_session_id">{{ __('ui.results.session') }}</label>
                        <select name="academic_session_id" id="academic_session_id" class="select">
                            <option value="">{{ __('ui.results.any_session') }}</option>
                            @foreach ($sessions as $session)
                                <option value="{{ $session->id }}" @selected(old('academic_session_id') == $session->id)>{{ $session->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> {{ __('ui.results.search_button') }}</button>
                </div>
            </form>

            @if ($searched)
                @if (! $student)
                    <div class="empty">
                        <div class="empty-icon"><i class="bi bi-person-x"></i></div>
                        <h3>{{ __('ui.results.no_student_title') }}</h3>
                        <p>{{ __('ui.results.no_student_text') }}</p>
                    </div>
                @elseif ($results->isEmpty())
                    <div class="empty">
                        <div class="empty-icon"><i class="bi bi-file-earmark-x"></i></div>
                        <h3>{{ __('ui.results.no_result_title') }}</h3>
                        <p>{!! __('ui.results.no_result_text', ['student' => '<strong>' . e($student->name) . '</strong>', 'exam' => '<strong>' . e($exam->name) . '</strong>']) !!}</p>
                    </div>
                @else
                    <div class="card reveal marksheet" id="marksheet">
                        <div class="marksheet-head">
                            <div>
                                <h2 style="color:#fff;font-size:1.35rem;margin-bottom:4px;">{{ $settings['school_name'] ?? 'My School' }}</h2>
                                <p style="color:rgba(255,255,255,.75);font-size:.88rem;">{{ $exam->name }} — {{ __('ui.results.statement') }}</p>
                            </div>
                            <div style="text-align:right;color:#fff;">
                                <div style="font-size:1.6rem;font-weight:800;">{{ $summary['grade'] }}</div>
                                <span style="font-size:.78rem;opacity:.75;">{{ __('ui.results.overall_grade') }}</span>
                            </div>
                        </div>

                        <div class="marksheet-student">
                            @foreach ([
                                [__('ui.results.student_name'), $student->name],
                                [__('ui.results.student_id'), $student->student_id],
                                [__('ui.results.class'), $student->schoolClass->name ?? '—'],
                                [__('ui.results.section'), $student->section->name ?? '—'],
                                [__('ui.results.exam'), $exam->name],
                                [__('ui.results.session'), $exam->academicSession->name ?? '—'],
                            ] as $row)
                                <div>
                                    <div class="muted" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;">{{ $row[0] }}</div>
                                    <strong style="font-size:.9rem;">{{ $row[1] }}</strong>
                                </div>
                            @endforeach
                        </div>

                        <div class="table-wrap" style="border-radius:0;border-left:none;border-right:none;">
                            <table class="data">
                                <thead>
                                    <tr>
                                        <th>{{ __('ui.results.subject') }}</th>
                                        <th>{{ __('ui.results.marks') }}</th>
                                        <th>{{ __('ui.results.full_marks') }}</th>
                                        <th>{{ __('ui.results.percentage') }}</th>
                                        <th>{{ __('ui.results.grade') }}</th>
                                        <th>{{ __('ui.results.gpa') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results as $result)
                                        <tr>
                                            <td><strong>{{ $result->subject->name }}</strong></td>
                                            <td>{{ rtrim(rtrim(number_format($result->marks, 2), '0'), '.') }}</td>
                                            <td>{{ $result->full_marks }}</td>
                                            <td>{{ $result->percentage() }}%</td>
                                            <td>
                                                <span style="color:{{ \App\Models\Result::gradeColor($result->grade) }};font-weight:800;">{{ $result->grade }}</span>
                                            </td>
                                            <td>{{ number_format($result->gpa, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="marksheet-summary">
                            @foreach ([
                                [__('ui.results.total_marks'), $summary['obtained'] . ' / ' . $summary['full']],
                                [__('ui.results.percentage'), $summary['percentage'] . '%'],
                                [__('ui.results.gpa'), number_format($summary['gpa'], 2)],
                                [__('ui.results.subjects_count'), $summary['subjects']],
                            ] as $item)
                                <div class="summary-box">
                                    <div class="muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;">{{ $item[0] }}</div>
                                    <div style="font-size:1.25rem;font-weight:800;color:var(--primary);">{{ $item[1] }}</div>
                                </div>
                            @endforeach
                        </div>

                        <div style="padding:22px 30px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:12px;align-items:center;">
                            <span class="muted" style="font-size:.8rem;">{{ __('ui.results.generated_on', ['date' => now()->format('d M Y, h:i A')]) }}</span>
                            <button type="button" class="btn btn-outline btn-sm" onclick="window.print()"><i class="bi bi-printer"></i> {{ __('ui.results.print_marksheet') }}</button>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </section>

    <style>
        .results-form > .grid { grid-template-columns: 1.2fr 1.2fr 1fr auto; }
        .marksheet { overflow: hidden; }
        .marksheet-head { background: var(--gradient); padding: 28px 30px; display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap; }
        .marksheet-student { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; padding: 26px 30px; background: var(--gradient-soft); }
        .marksheet-summary { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; padding: 24px 30px; }
        .summary-box { background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 16px; text-align: center; }
        @media (max-width: 900px) { .results-form > .grid { grid-template-columns: 1fr; } }
        @media (max-width: 780px) {
            .marksheet-student { grid-template-columns: repeat(2, 1fr); }
            .marksheet-summary { grid-template-columns: repeat(2, 1fr); }
        }
        @media print {
            .site-nav, .site-footer, .page-head, .btn, form { display: none !important; }
        }
    </style>
@endsection
