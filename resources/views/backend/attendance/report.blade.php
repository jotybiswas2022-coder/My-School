@extends('backend.layouts.app')

@section('title', 'Attendance Report')

@section('content')
    <div class="page-header">
        <div>
            <h1>Attendance Report</h1>
            <p>Class-wise and student-wise attendance summary.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.attendance.index') }}" class="b-btn b-btn-outline">← Mark Attendance</a>
        </div>
    </div>

    <form method="GET" class="filter-bar">
        <div class="filter-grid">
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Class</label>
                <select name="class_id" class="form-select">
                    <option value="">All classes</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected($classId == $class->id)>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Section</label>
                <select name="section_id" class="form-select">
                    <option value="">All sections</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}" @selected($sectionId == $section->id)>{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">From</label>
                <input type="date" name="from" class="form-control" value="{{ $from->format('Y-m-d') }}">
            </div>
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">To</label>
                <input type="date" name="to" class="form-control" value="{{ $to->format('Y-m-d') }}">
            </div>
            <div style="display:flex;gap:8px;">
                <button class="b-btn b-btn-primary">Generate</button>
            </div>
        </div>
    </form>

    <div class="b-grid b-grid-3" style="margin-bottom:22px;">
        @foreach ([
            ['Present', (int) ($totals['present'] ?? 0), '#16A34A'],
            ['Absent', (int) ($totals['absent'] ?? 0), '#DC2626'],
            ['Late', (int) ($totals['late'] ?? 0), '#F59E0B'],
        ] as $tile)
            <div class="stat-card" style="--accent:{{ $tile[2] }};">
                <div>
                    <div class="stat-value">{{ $tile[1] }}</div>
                    <div class="stat-label">{{ $tile[0] }} records</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="b-card">
        <div class="b-card-head">
            <h3>Student-wise Summary</h3>
            <span style="font-size:.8rem;color:var(--muted);">{{ $from->format('d M Y') }} – {{ $to->format('d M Y') }}</span>
        </div>

        @if ($perStudent->isEmpty())
            <div class="b-empty">
                <div class="b-empty-ico">◔</div>
                <h3>No attendance records for this period</h3>
                <p style="font-size:.86rem;">Try adjusting the date range or filters.</p>
            </div>
        @else
            <div class="b-table-wrap">
                <table class="b-table">
                    <thead>
                        <tr><th>Student</th><th>Present</th><th>Absent</th><th>Late</th><th>Total</th><th>Attendance %</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($perStudent as $row)
                            @php $pct = $row->total > 0 ? round((($row->present + $row->late) / $row->total) * 100, 1) : 0; @endphp
                            <tr>
                                <td>
                                    <strong>{{ $row->student->name ?? 'Deleted student' }}</strong>
                                    <div style="font-size:.74rem;color:var(--muted);">{{ $row->student->student_id ?? '' }}</div>
                                </td>
                                <td>{{ $row->present }}</td>
                                <td>{{ $row->absent }}</td>
                                <td>{{ $row->late }}</td>
                                <td>{{ $row->total }}</td>
                                <td>
                                    <span class="badge {{ $pct >= 75 ? 'badge-success' : ($pct >= 50 ? 'badge-warning' : 'badge-danger') }}">{{ $pct }}%</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
