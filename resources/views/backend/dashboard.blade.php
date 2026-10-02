@extends('backend.layouts.app')

@section('title', 'Dashboard')

@push('styles')
    <style>
        /* ============ DASHBOARD LAYOUT ============ */
        .dash-block { margin-bottom: 24px; }
        .dash-panels .b-empty { padding: 44px 22px; }

        /* Mobile: shorter, cleaner dashboard */
        @media (max-width: 720px) {
            .page-actions { width: 100%; }
            .page-actions .b-btn { flex: 1 1 130px; justify-content: center; }

            .dash-block { margin-bottom: 16px; }

            /* compact two-up stat tiles */
            .b-grid.dash-stats { grid-template-columns: repeat(2, minmax(0,1fr)); gap: 12px; }
            .dash-stats .stat-card { padding: 13px; gap: 10px; border-radius: 12px; }
            .dash-stats .stat-ico { width: 34px; height: 34px; font-size: .95rem; border-radius: 10px; }
            .dash-stats .stat-value { font-size: 1.2rem; }
            .dash-stats .stat-label { font-size: .7rem; letter-spacing: 0; }

            /* today's attendance collapses into a single compact strip */
            .b-grid.dash-today { grid-template-columns: repeat(3, minmax(0,1fr)); gap: 12px; }
            .dash-today .stat-card { flex-direction: column; align-items: flex-start; gap: 4px; padding: 12px; border-radius: 12px; }
            .dash-today .stat-ico { display: none; }
            .dash-today .stat-value { font-size: 1.28rem; }
            .dash-today .stat-label { font-size: .68rem; letter-spacing: 0; }

            /* tighter panels */
            .dash-panels { gap: 16px; }
            .dash-panels .b-card-head { padding: 14px 16px; }
            .dash-panels .b-card-head h3 { font-size: .94rem; }
            .dash-panels .b-empty { padding: 26px 16px; }
            .dash-panels .b-empty-ico { display: none; }

            /* tables read as compact rows instead of wide grids */
            .dash-table table.b-table { font-size: .82rem; }
            .dash-table table.b-table td { padding: 11px 14px; }
            .dash-table table.b-table th { padding: 10px 14px; }
            .dash-table .hide-sm { display: none; }
            .dash-table td > strong,
            .dash-table td > div { display: block; max-width: 44vw; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
            .dash-table td:last-child { text-align: right; white-space: nowrap; }
            .dash-table .b-btn-sm { padding: 6px 11px; font-size: .74rem; }
            .dash-table .badge { padding: 3px 9px; font-size: .66rem; }

            /* keep the page short: three rows per panel, the rest lives under "View All" */
            .dash-table tbody tr:nth-child(n+4) { display: none; }
        }

        @media (max-width: 400px) {
            .dash-stats .stat-ico { display: none; }
            .dash-stats .stat-card { padding: 12px; }
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard</h1>
            <p>Welcome back, {{ auth()->user()->name }}. Here is what is happening at your school today.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.notices.create') }}" class="b-btn b-btn-outline">+ Notice</a>
            <a href="{{ route('admin.events.create') }}" class="b-btn b-btn-outline">+ Event</a>
            <a href="{{ route('admin.students.create') }}" class="b-btn b-btn-primary">+ Add Student</a>
        </div>
    </div>

    <div class="b-grid b-grid-4 dash-block dash-stats">
        @foreach ([
            ['Students', $stats['students'], 'bi-people', '#2563EB'],
            ['Teachers', $stats['teachers'], 'bi-person-badge', '#0EA5E9'],
            ['Classes', $stats['classes'], 'bi-diagram-3', '#8B5CF6'],
            ['Subjects', $stats['subjects'], 'bi-book', '#16A34A'],
            ['Notices', $stats['notices'], 'bi-megaphone', '#F59E0B'],
            ['Events', $stats['events'], 'bi-calendar-event', '#EC4899'],
            ['News', $stats['news'], 'bi-newspaper', '#14B8A6'],
            ['Pending Admissions', $stats['pendingAdmissions'], 'bi-file-earmark-text', '#DC2626'],
        ] as $i => $card)
            <div class="stat-card" style="--accent: {{ $card[3] }}; animation-delay: {{ $i * 0.05 }}s;">
                <div class="stat-ico"><i class="bi {{ $card[2] }}"></i></div>
                <div>
                    <div class="stat-value">{{ number_format($card[1]) }}</div>
                    <div class="stat-label">{{ $card[0] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="b-grid b-grid-3 dash-block dash-today">
        <div class="stat-card" style="--accent:#16A34A;">
            <div class="stat-ico"><i class="bi bi-check-lg"></i></div>
            <div>
                <div class="stat-value">{{ (int) ($todayAttendance['present'] ?? 0) }}</div>
                <div class="stat-label">Present Today</div>
            </div>
        </div>
        <div class="stat-card" style="--accent:#DC2626;">
            <div class="stat-ico"><i class="bi bi-x-lg"></i></div>
            <div>
                <div class="stat-value">{{ (int) ($todayAttendance['absent'] ?? 0) }}</div>
                <div class="stat-label">Absent Today</div>
            </div>
        </div>
        <div class="stat-card" style="--accent:#F59E0B;">
            <div class="stat-ico"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="stat-value">{{ (int) ($todayAttendance['late'] ?? 0) }}</div>
                <div class="stat-label">Late Today</div>
            </div>
        </div>
    </div>

    <div class="b-grid b-grid-2 dash-panels" style="align-items:start;">
        <div class="b-card">
            <div class="b-card-head">
                <h3>Recent Admissions</h3>
                <a href="{{ route('admin.admissions.index') }}" class="b-btn b-btn-outline b-btn-sm">View All</a>
            </div>
            @if ($recentAdmissions->isEmpty())
                <div class="b-empty">
                    <div class="b-empty-ico"><i class="bi bi-file-earmark-text"></i></div>
                    <h3>No applications yet</h3>
                    <p style="font-size:.85rem;">New admission applications will appear here.</p>
                </div>
            @else
                <div class="b-table-wrap dash-table">
                    <table class="b-table">
                        <thead><tr><th>Applicant</th><th class="hide-sm">Class</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                            @foreach ($recentAdmissions as $admission)
                                <tr>
                                    <td>
                                        <strong>{{ $admission->student_name }}</strong>
                                        <div style="font-size:.75rem;color:var(--muted);">{{ $admission->application_id }}</div>
                                    </td>
                                    <td class="hide-sm">{{ $admission->applying_class }}</td>
                                    <td><span class="badge" style="background:{{ $admission->statusColor() }}1f;color:{{ $admission->statusColor() }};">{{ ucfirst($admission->status) }}</span></td>
                                    <td><a href="{{ route('admin.admissions.show', $admission) }}" class="b-btn b-btn-outline b-btn-sm">View</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="b-card">
            <div class="b-card-head">
                <h3>Recent Notices</h3>
                <a href="{{ route('admin.notices.index') }}" class="b-btn b-btn-outline b-btn-sm">View All</a>
            </div>
            @if ($recentNotices->isEmpty())
                <div class="b-empty">
                    <div class="b-empty-ico"><i class="bi bi-megaphone"></i></div>
                    <h3>No notices yet</h3>
                </div>
            @else
                <div class="b-table-wrap">
                    <table class="b-table">
                        <thead><tr><th>Title</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                            @foreach ($recentNotices as $notice)
                                <tr>
                                    <td>
                                        <strong>{{ \Illuminate\Support\Str::limit($notice->title, 42) }}</strong>
                                        <div style="font-size:.75rem;color:var(--muted);">{{ $notice->category }}</div>
                                    </td>
                                    <td><span class="badge {{ $notice->is_published ? 'badge-success' : 'badge-muted' }}">{{ $notice->is_published ? 'Published' : 'Draft' }}</span></td>
                                    <td><a href="{{ route('admin.notices.edit', $notice) }}" class="b-btn b-btn-outline b-btn-sm">Edit</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="b-card">
            <div class="b-card-head">
                <h3>Upcoming Events</h3>
                <a href="{{ route('admin.events.index') }}" class="b-btn b-btn-outline b-btn-sm">View All</a>
            </div>
            @if ($upcomingEvents->isEmpty())
                <div class="b-empty">
                    <div class="b-empty-ico"><i class="bi bi-calendar-event"></i></div>
                    <h3>No upcoming events</h3>
                </div>
            @else
                <div class="b-table-wrap dash-table">
                    <table class="b-table">
                        <thead><tr><th>Event</th><th>Date</th><th class="hide-sm">Location</th></tr></thead>
                        <tbody>
                            @foreach ($upcomingEvents as $event)
                                <tr>
                                    <td><strong>{{ \Illuminate\Support\Str::limit($event->title, 38) }}</strong></td>
                                    <td>{{ $event->event_date->format('d M Y') }}</td>
                                    <td class="hide-sm">{{ $event->location ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="b-card">
            <div class="b-card-head">
                <h3>Recent Messages</h3>
                <a href="{{ route('admin.messages.index') }}" class="b-btn b-btn-outline b-btn-sm">View All</a>
            </div>
            @if ($recentMessages->isEmpty())
                <div class="b-empty">
                    <div class="b-empty-ico"><i class="bi bi-envelope"></i></div>
                    <h3>No messages yet</h3>
                </div>
            @else
                <div class="b-table-wrap dash-table">
                    <table class="b-table">
                        <thead><tr><th>From</th><th>Subject</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach ($recentMessages as $message)
                                <tr>
                                    <td>
                                        <strong>{{ $message->name }}</strong>
                                        <div class="hide-sm" style="font-size:.75rem;color:var(--muted);">{{ $message->email }}</div>
                                    </td>
                                    <td>{{ \Illuminate\Support\Str::limit($message->subject, 30) }}</td>
                                    <td>
                                        <a href="{{ route('admin.messages.show', $message) }}" class="badge {{ $message->is_read ? 'badge-muted' : 'badge-warning' }}" style="text-decoration:none;">
                                            {{ $message->is_read ? 'Read' : 'Unread' }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
