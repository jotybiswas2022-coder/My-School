@extends('backend.layouts.app')

@section('title', 'Student Profile')

@section('content')
    <div class="page-header">
        <div>
            <h1>Student Profile</h1>
            <p>{{ $student->name }} · {{ $student->student_id }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.students.index') }}" class="b-btn b-btn-outline"><i class="bi bi-arrow-left"></i> Back</a>
            <a href="{{ route('admin.students.edit', $student) }}" class="b-btn b-btn-primary">Edit Student</a>
        </div>
    </div>

    <div class="b-grid" style="grid-template-columns:340px 1fr;align-items:start;">
        <div class="b-card">
            <div class="b-card-body" style="text-align:center;">
                <span class="b-avatar" style="width:96px;height:96px;font-size:1.7rem;margin:0 auto 16px;">
                    @if ($student->photo)
                        <img src="{{ asset('storage/' . $student->photo) }}" alt="{{ $student->name }}">
                    @else
                        {{ $student->initials() }}
                    @endif
                </span>
                <h2 style="font-size:1.2rem;margin-bottom:4px;">{{ $student->name }}</h2>
                <p style="color:var(--muted);font-size:.84rem;">{{ $student->student_id }}</p>
                <div style="margin:14px 0;">
                    <span class="badge {{ $student->is_active ? 'badge-success' : 'badge-danger' }}">{{ $student->is_active ? 'Active' : 'Inactive' }}</span>
                </div>

                <div style="text-align:left;margin-top:20px;display:flex;flex-direction:column;gap:13px;">
                    @foreach ([
                        ['Class', $student->schoolClass->name ?? '—'],
                        ['Section', $student->section->name ?? '—'],
                        ['Roll Number', $student->roll_number ?? '—'],
                        ['Date of Birth', optional($student->date_of_birth)->format('d M Y') ?? '—'],
                        ['Gender', $student->gender ? ucfirst($student->gender) : '—'],
                        ['Guardian', $student->guardian_name ?? '—'],
                        ['Guardian Phone', $student->guardian_phone ?? '—'],
                        ['Email', $student->email ?? '—'],
                        ['Admission Date', optional($student->admission_date)->format('d M Y') ?? '—'],
                    ] as $row)
                        <div>
                            <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;color:var(--muted);">{{ $row[0] }}</div>
                            <strong style="font-size:.88rem;">{{ $row[1] }}</strong>
                        </div>
                    @endforeach
                </div>

                @if ($student->address)
                    <div style="text-align:left;margin-top:16px;">
                        <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;color:var(--muted);">Address</div>
                        <p style="font-size:.86rem;">{{ $student->address }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="b-grid b-grid-4">
                @foreach ([
                    ['Overall Attendance', $summary['percentage'] . '%', '#16A34A'],
                    ['Present', $summary['present'], '#2563EB'],
                    ['Absent', $summary['absent'], '#DC2626'],
                    ['Late', $summary['late'], '#F59E0B'],
                ] as $tile)
                    <div class="stat-card" style="--accent:{{ $tile[2] }};">
                        <div>
                            <div class="stat-value" style="font-size:1.3rem;">{{ $tile[1] }}</div>
                            <div class="stat-label">{{ $tile[0] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="b-card">
                <div class="b-card-head"><h3>Recent Results</h3></div>
                @if ($results->isEmpty())
                    <div class="b-empty" style="padding:44px 22px;">
                        <div class="b-empty-ico"><i class="bi bi-trophy"></i></div>
                        <h3>No results recorded</h3>
                    </div>
                @else
                    <div class="b-table-wrap">
                        <table class="b-table">
                            <thead><tr><th>Exam</th><th>Subject</th><th>Marks</th><th>Grade</th><th>GPA</th><th>Published</th></tr></thead>
                            <tbody>
                                @foreach ($results as $result)
                                    <tr>
                                        <td>{{ $result->exam->name ?? '—' }}</td>
                                        <td>{{ $result->subject->name ?? '—' }}</td>
                                        <td>{{ rtrim(rtrim(number_format($result->marks, 2), '0'), '.') }} / {{ $result->full_marks }}</td>
                                        <td><strong style="color:{{ \App\Models\Result::gradeColor($result->grade) }};">{{ $result->grade }}</strong></td>
                                        <td>{{ number_format($result->gpa, 2) }}</td>
                                        <td><span class="badge {{ $result->is_published ? 'badge-success' : 'badge-muted' }}">{{ $result->is_published ? 'Yes' : 'No' }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 900px) {
            .b-grid[style*="340px"] { grid-template-columns: 1fr !important; }
        }
    </style>
@endsection
