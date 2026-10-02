@extends('backend.layouts.app')

@section('title', 'Application Details')

@section('content')
    <div class="page-header">
        <div>
            <h1>{{ $admission->student_name }}</h1>
            <p>Application {{ $admission->application_id }} · submitted {{ $admission->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.admissions.index') }}" class="b-btn b-btn-outline">← Back</a>
            <form method="POST" action="{{ route('admin.admissions.destroy', $admission) }}">
                @csrf @method('DELETE')
                <button type="button" class="b-btn b-btn-danger" data-confirm="Delete application {{ $admission->application_id }}?">Delete Application</button>
            </form>
        </div>
    </div>

    <div class="b-grid" style="grid-template-columns:1fr 340px;align-items:start;">
        <div class="b-card">
            <div class="b-card-head">
                <h3>Applicant Information</h3>
                <span class="badge" style="background:{{ $admission->statusColor() }}1f;color:{{ $admission->statusColor() }};">{{ ucfirst($admission->status) }}</span>
            </div>
            <div class="b-card-body">
                <div class="b-grid b-grid-2" style="gap:20px;">
                    @foreach ([
                        ['Full Name', $admission->student_name],
                        ['Date of Birth', $admission->date_of_birth->format('d M Y')],
                        ['Gender', ucfirst($admission->gender)],
                        ['Applying Class', $admission->applying_class],
                        ['Previous School', $admission->previous_school ?? '—'],
                        ['Guardian Name', $admission->guardian_name],
                        ['Guardian Phone', $admission->guardian_phone],
                        ['Email', $admission->email ?? '—'],
                    ] as $row)
                        <div style="padding-bottom:14px;border-bottom:1px solid var(--border);">
                            <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;color:var(--muted);">{{ $row[0] }}</div>
                            <strong style="font-size:.92rem;">{{ $row[1] }}</strong>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top:20px;">
                    <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;color:var(--muted);margin-bottom:6px;">Address</div>
                    <p style="font-size:.9rem;">{{ $admission->address }}</p>
                </div>

                @if ($admission->additional_info)
                    <div style="margin-top:18px;">
                        <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;color:var(--muted);margin-bottom:6px;">Additional Information</div>
                        <p style="font-size:.9rem;">{{ $admission->additional_info }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="b-card">
            <div class="b-card-head"><h3>Update Status</h3></div>
            <div class="b-card-body">
                <form method="POST" action="{{ route('admin.admissions.status', $admission) }}">
                    @csrf @method('PUT')
                    <div class="form-row">
                        <label for="status">Application Status</label>
                        <select name="status" id="status" class="form-select">
                            @foreach (\App\Models\Admission::statuses() as $status)
                                <option value="{{ $status }}" @selected($admission->status === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="b-btn b-btn-primary b-btn-block">Update Status</button>
                </form>

                <div style="margin-top:22px;padding-top:18px;border-top:1px solid var(--border);">
                    <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;color:var(--muted);margin-bottom:10px;">Quick Actions</div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        @foreach (['approved', 'rejected', 'pending'] as $status)
                            <form method="POST" action="{{ route('admin.admissions.status', $admission) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="{{ $status }}">
                                <button class="b-btn b-btn-sm {{ $status === 'approved' ? 'b-btn-success' : ($status === 'rejected' ? 'b-btn-danger' : 'b-btn-outline') }}"
                                        @disabled($admission->status === $status)>
                                    {{ ucfirst($status) }}
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 900px) { .b-grid[style*="340px"] { grid-template-columns: 1fr !important; } }
    </style>
@endsection
