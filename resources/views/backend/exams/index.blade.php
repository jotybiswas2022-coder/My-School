@extends('backend.layouts.app')

@section('title', 'Exams')

@section('content')
    <div class="page-header">
        <div>
            <h1>Exams</h1>
            <p>Create exams, schedules and publish results.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.exams.create') }}" class="b-btn b-btn-primary">+ Add Exam</a>
        </div>
    </div>

    @if ($exams->isEmpty())
        <div class="b-card">
            <div class="b-empty">
                <div class="b-empty-ico">✎</div>
                <h3>No exams yet</h3>
                <p style="font-size:.86rem;">Create an exam to start recording results.</p>
            </div>
        </div>
    @else
        <div class="b-card">
            <div class="b-table-wrap">
                <table class="b-table">
                    <thead>
                        <tr><th>Exam</th><th>Type</th><th>Session</th><th>Dates</th><th>Subjects</th><th>Results</th><th>Status</th><th style="text-align:right;">Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($exams as $exam)
                            <tr>
                                <td><strong>{{ $exam->name }}</strong></td>
                                <td>{{ $exam->exam_type ?? '—' }}</td>
                                <td>{{ $exam->academicSession->name ?? '—' }}</td>
                                <td style="font-size:.8rem;">
                                    {{ optional($exam->start_date)->format('d M Y') ?? '—' }}
                                    @if ($exam->end_date) – {{ $exam->end_date->format('d M Y') }} @endif
                                </td>
                                <td>{{ $exam->exam_subjects_count }}</td>
                                <td>{{ $exam->results_count }}</td>
                                <td>
                                    <span class="badge {{ $exam->is_published ? 'badge-success' : 'badge-muted' }}">
                                        {{ $exam->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="cell-actions" style="justify-content:flex-end;">
                                        <a href="{{ route('admin.exams.show', $exam) }}" class="b-btn b-btn-outline b-btn-sm">Manage</a>
                                        <a href="{{ route('admin.exams.edit', $exam) }}" class="b-btn b-btn-outline b-btn-sm">Edit</a>
                                        <form method="POST" action="{{ route('admin.exams.publish', $exam) }}">
                                            @csrf
                                            <button class="b-btn b-btn-sm {{ $exam->is_published ? 'b-btn-outline' : 'b-btn-success' }}">
                                                {{ $exam->is_published ? 'Unpublish' : 'Publish' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.exams.destroy', $exam) }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="b-btn b-btn-danger b-btn-sm"
                                                    data-confirm="Delete {{ $exam->name }}? All linked results will be removed.">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding:18px 22px;">
                {{ $exams->links() }}
            </div>
        </div>
    @endif
@endsection
