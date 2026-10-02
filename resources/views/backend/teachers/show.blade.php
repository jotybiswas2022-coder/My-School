@extends('backend.layouts.app')

@section('title', 'Teacher Profile')

@section('content')
    <div class="page-header">
        <div>
            <h1>Teacher Profile</h1>
            <p>{{ $teacher->name }} · {{ $teacher->designation }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.teachers.index') }}" class="b-btn b-btn-outline"><i class="bi bi-arrow-left"></i> Back</a>
            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="b-btn b-btn-primary">Edit Teacher</a>
        </div>
    </div>

    <div class="b-grid" style="grid-template-columns:340px 1fr;align-items:start;">
        <div class="b-card">
            <div class="b-card-body" style="text-align:center;">
                <span class="b-avatar" style="width:96px;height:96px;font-size:1.7rem;margin:0 auto 16px;">
                    @if ($teacher->photo)
                        <img src="{{ asset('storage/' . $teacher->photo) }}" alt="{{ $teacher->name }}">
                    @else
                        {{ $teacher->initials() }}
                    @endif
                </span>
                <h2 style="font-size:1.2rem;margin-bottom:4px;">{{ $teacher->name }}</h2>
                <p style="color:var(--primary);font-weight:600;font-size:.86rem;">{{ $teacher->designation }}</p>
                <div style="margin:14px 0;">
                    <span class="badge {{ $teacher->is_active ? 'badge-success' : 'badge-danger' }}">{{ $teacher->is_active ? 'Active' : 'Inactive' }}</span>
                </div>

                <div style="text-align:left;margin-top:20px;display:flex;flex-direction:column;gap:13px;">
                    @foreach ([
                        ['Department', $teacher->department ?? '—'],
                        ['Qualification', $teacher->qualification ?? '—'],
                        ['Experience', $teacher->experience ?? '—'],
                        ['Email', $teacher->email ?? '—'],
                        ['Phone', $teacher->phone ?? '—'],
                        ['Joined', optional($teacher->join_date)->format('d M Y') ?? '—'],
                    ] as $row)
                        <div>
                            <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;color:var(--muted);">{{ $row[0] }}</div>
                            <strong style="font-size:.88rem;">{{ $row[1] }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:20px;">
            @if ($teacher->bio)
                <div class="b-card">
                    <div class="b-card-head"><h3>Biography</h3></div>
                    <div class="b-card-body">
                        <p style="font-size:.9rem;line-height:1.8;color:#334155;">{{ $teacher->bio }}</p>
                    </div>
                </div>
            @endif

            <div class="b-card">
                <div class="b-card-head"><h3>Assigned Subjects</h3></div>
                @if ($teacher->subjects->isEmpty())
                    <div class="b-empty" style="padding:44px 22px;">
                        <div class="b-empty-ico"><i class="bi bi-book"></i></div>
                        <h3>No subjects assigned</h3>
                    </div>
                @else
                    <div class="b-table-wrap">
                        <table class="b-table">
                            <thead><tr><th>Subject</th><th>Code</th><th>Class</th></tr></thead>
                            <tbody>
                                @foreach ($teacher->subjects as $subject)
                                    <tr>
                                        <td><strong>{{ $subject->name }}</strong></td>
                                        <td>{{ $subject->code }}</td>
                                        <td>{{ $subject->schoolClass->name ?? '—' }}</td>
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
        @media (max-width: 900px) { .b-grid[style*="340px"] { grid-template-columns: 1fr !important; } }
    </style>
@endsection
