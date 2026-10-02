@extends('frontend.student.layout')

@section('title', __('ui.student.my_results') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('student-content')
    @if ($results->isEmpty())
        <div class="panel">
            <div class="panel-head"><h3>{{ __('ui.student.my_results') }}</h3></div>
            <div class="empty" style="padding:60px 20px;">
                <div class="empty-icon"><i class="bi bi-award"></i></div>
                <h3>{{ __('ui.student.no_published_results_title') }}</h3>
                <p>{{ __('ui.student.no_published_results_text') }}</p>
            </div>
        </div>
    @else
        <div class="stack" style="gap:22px;">
            @foreach ($results as $examId => $examResults)
                @php
                    $exam = $examResults->first()->exam;
                    $totalGpa = round($examResults->avg('gpa'), 2);
                    $obtained = $examResults->sum('marks');
                    $full = $examResults->sum('full_marks');
                @endphp

                <div class="panel">
                    <div class="panel-head">
                        <div>
                            <h3>{{ $exam->name ?? __('ui.student.exams') }}</h3>
                            <span class="muted" style="font-size:.8rem;">{{ $exam->academicSession->name ?? '' }} {{ $exam->exam_type ? '· ' . $exam->exam_type : '' }}</span>
                        </div>
                        <div class="row" style="gap:14px;">
                            <div style="text-align:center;">
                                <div class="muted" style="font-size:.68rem;text-transform:uppercase;font-weight:700;">{{ __('ui.results.gpa') }}</div>
                                <strong style="font-size:1.1rem;color:var(--primary);">{{ number_format($totalGpa, 2) }}</strong>
                            </div>
                            <div style="text-align:center;">
                                <div class="muted" style="font-size:.68rem;text-transform:uppercase;font-weight:700;">{{ __('ui.student.marks') }}</div>
                                <strong style="font-size:1.1rem;">{{ rtrim(rtrim(number_format($obtained, 2), '0'), '.') }}/{{ $full }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="table-wrap" style="border:none;border-radius:0;">
                        <table class="data">
                            <thead>
                                <tr><th>{{ __('ui.results.subject') }}</th><th>{{ __('ui.results.marks') }}</th><th>{{ __('ui.results.full_marks') }}</th><th>{{ __('ui.results.percentage') }}</th><th>{{ __('ui.results.grade') }}</th><th>{{ __('ui.results.gpa') }}</th></tr>
                            </thead>
                            <tbody>
                                @foreach ($examResults as $result)
                                    <tr>
                                        <td><strong>{{ $result->subject->name ?? '—' }}</strong></td>
                                        <td>{{ rtrim(rtrim(number_format($result->marks, 2), '0'), '.') }}</td>
                                        <td>{{ $result->full_marks }}</td>
                                        <td>{{ $result->percentage() }}%</td>
                                        <td><strong style="color:{{ \App\Models\Result::gradeColor($result->grade) }};">{{ $result->grade }}</strong></td>
                                        <td>{{ number_format($result->gpa, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
