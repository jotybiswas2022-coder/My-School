@extends('backend.layouts.app')

@section('title', 'Attendance')

@section('content')
    <div class="page-header">
        <div>
            <h1>Attendance</h1>
            <p>Mark daily attendance for a class and section.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.attendance.report') }}" class="b-btn b-btn-outline">Attendance Report</a>
        </div>
    </div>

    <form method="GET" class="filter-bar">
        <div class="filter-grid">
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Class</label>
                <select name="class_id" class="form-select">
                    <option value="">Select class</option>
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
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Date</label>
                <input type="date" name="date" class="form-control" value="{{ $date->format('Y-m-d') }}">
            </div>
            <div style="display:flex;gap:8px;">
                <button class="b-btn b-btn-primary">Load Students</button>
            </div>
        </div>
    </form>

    <div class="b-card">
        <div class="b-card-head">
            <h3>{{ $date->format('l, d M Y') }}</h3>
            @if ($students->isNotEmpty())
                <div style="display:flex;gap:8px;">
                    <button type="button" class="b-btn b-btn-outline b-btn-sm" data-mark-all="present">Mark all present</button>
                    <button type="button" class="b-btn b-btn-outline b-btn-sm" data-mark-all="absent">Mark all absent</button>
                </div>
            @endif
        </div>

        @if (! $classId)
            <div class="b-empty">
                <div class="b-empty-ico"><i class="bi bi-calendar-check"></i></div>
                <h3>Select a class</h3>
                <p style="font-size:.86rem;">Choose a class to load students and mark attendance.</p>
            </div>
        @elseif ($students->isEmpty())
            <div class="b-empty">
                <div class="b-empty-ico"><i class="bi bi-people"></i></div>
                <h3>No students in this class</h3>
                <p style="font-size:.86rem;">Add students to this class to mark attendance.</p>
            </div>
        @else
            <form method="POST" action="{{ route('admin.attendance.store') }}" id="attendanceForm">
                @csrf
                <input type="hidden" name="date" value="{{ $date->format('Y-m-d') }}">
                <input type="hidden" name="class_id" value="{{ $classId }}">
                <input type="hidden" name="section_id" value="{{ $sectionId }}">

                <div class="b-table-wrap">
                    <table class="b-table">
                        <thead>
                            <tr><th>#</th><th>Student</th><th>Status</th><th>Remark</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $index => $student)
                                @php $current = $student->attendance->status ?? 'present'; @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="b-user-cell">
                                            <span class="b-avatar">{{ $student->initials() }}</span>
                                            <div>
                                                <strong>{{ $student->name }}</strong>
                                                <div style="font-size:.74rem;color:var(--muted);">{{ $student->student_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:14px;">
                                            @foreach (['present' => 'Present', 'absent' => 'Absent', 'late' => 'Late'] as $value => $label)
                                                <label class="checkbox-row" style="font-weight:500;font-size:.84rem;">
                                                    <input type="radio" name="attendance[{{ $student->id }}][status]" value="{{ $value }}" @checked($current === $value)>
                                                    {{ $label }}
                                                </label>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="attendance[{{ $student->id }}][remark]" class="form-control"
                                               value="{{ $student->attendance->remark ?? '' }}" placeholder="Optional" style="min-width:180px;">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="padding:18px 22px;">
                    <button type="submit" class="b-btn b-btn-primary">Save Attendance</button>
                </div>
            </form>
        @endif
    </div>

    @push('scripts')
        <script>
            const bindMarkAll = () => {
                document.querySelectorAll('[data-mark-all]').forEach((btn) => {
                    if (btn.dataset.bound) return;
                    btn.dataset.bound = '1';

                    btn.addEventListener('click', () => {
                        const status = btn.dataset.markAll;
                        document.querySelectorAll('#attendanceForm input[type="radio"][value="' + status + '"]').forEach((radio) => {
                            radio.checked = true;
                        });
                    });
                });
            };

            bindMarkAll();
            document.addEventListener('admin:live-swapped', bindMarkAll);
        </script>
    @endpush
@endsection
