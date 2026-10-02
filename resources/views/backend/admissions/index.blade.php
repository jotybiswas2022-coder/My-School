@extends('backend.layouts.app')

@section('title', 'Admissions')

@section('content')
    <div class="page-header">
        <div>
            <h1>Admissions</h1>
            <p>Review and manage online admission applications.</p>
        </div>
    </div>

    <div class="b-grid b-grid-3" style="margin-bottom:20px;">
        @foreach ([
            ['Pending', $counts['pending'], '#F59E0B', 'pending'],
            ['Approved', $counts['approved'], '#16A34A', 'approved'],
            ['Rejected', $counts['rejected'], '#DC2626', 'rejected'],
        ] as $tile)
            <a href="{{ route('admin.admissions.index', ['status' => $tile[3]]) }}" class="stat-card" style="--accent:{{ $tile[2] }};text-decoration:none;">
                <div>
                    <div class="stat-value">{{ $tile[1] }}</div>
                    <div class="stat-label">{{ $tile[0] }} applications</div>
                </div>
            </a>
        @endforeach
    </div>

    <form method="GET" class="filter-bar">
        <div class="filter-grid">
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Search</label>
                <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Name, application ID or guardian">
            </div>
            <div>
                <label style="font-size:.78rem;font-weight:600;display:block;margin-bottom:6px;">Status</label>
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:8px;">
                <button class="b-btn b-btn-primary">Filter</button>
                <a href="{{ route('admin.admissions.index') }}" class="b-btn b-btn-outline">Reset</a>
            </div>
        </div>
    </form>

    <div class="b-card">
        @if ($admissions->isEmpty())
            <div class="b-empty">
                <div class="b-empty-ico"><i class="bi bi-file-earmark-text"></i></div>
                <h3>No applications found</h3>
                <p style="font-size:.86rem;">New applications from the website will appear here.</p>
            </div>
        @else
            <div class="b-table-wrap">
                <table class="b-table">
                    <thead>
                        <tr><th>Applicant</th><th>Application ID</th><th>Class</th><th>Guardian</th><th>Applied</th><th>Status</th><th style="text-align:right;">Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($admissions as $admission)
                            <tr>
                                <td><strong>{{ $admission->student_name }}</strong></td>
                                <td>{{ $admission->application_id }}</td>
                                <td>{{ $admission->applying_class }}</td>
                                <td>
                                    {{ $admission->guardian_name }}
                                    <div style="font-size:.74rem;color:var(--muted);">{{ $admission->guardian_phone }}</div>
                                </td>
                                <td>{{ $admission->created_at->format('d M Y') }}</td>
                                <td><span class="badge" style="background:{{ $admission->statusColor() }}1f;color:{{ $admission->statusColor() }};">{{ ucfirst($admission->status) }}</span></td>
                                <td>
                                    <div class="cell-actions" style="justify-content:flex-end;">
                                        <a href="{{ route('admin.admissions.show', $admission) }}" class="b-btn b-btn-outline b-btn-sm">View</a>
                                        <form method="POST" action="{{ route('admin.admissions.destroy', $admission) }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="b-btn b-btn-danger b-btn-sm" data-confirm="Delete application {{ $admission->application_id }}?">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding:18px 22px;">
                {{ $admissions->links() }}
            </div>
        @endif
    </div>
@endsection
