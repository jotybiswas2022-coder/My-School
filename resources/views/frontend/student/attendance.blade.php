@extends('frontend.student.layout')

@section('title', __('ui.student.my_attendance') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('student-content')
    <div class="grid grid-4" style="gap:16px;margin-bottom:24px;">
        @foreach ([
            [__('ui.student.overall'), $summary['percentage'] . '%'],
            [__('ui.student.present'), $summary['present']],
            [__('ui.student.absent'), $summary['absent']],
            [__('ui.student.late'), $summary['late']],
        ] as $tile)
            <div class="stat-tile" style="text-align:center;">
                <div class="value">{{ $tile[1] }}</div>
                <div class="label">{{ $tile[0] }}</div>
            </div>
        @endforeach
    </div>

    <div class="panel">
        <div class="panel-head">
            <h3>{{ __('ui.student.records') }}</h3>
            <span class="muted" style="font-size:.82rem;">{{ __('ui.student.total_records', ['count' => $summary['total']]) }}</span>
        </div>

        @if ($attendances->isEmpty())
            <div class="empty" style="padding:56px 20px;">
                <div class="empty-icon"><i class="bi bi-calendar-check"></i></div>
                <h3>{{ __('ui.student.no_attendance') }}</h3>
                <p>{{ __('ui.student.no_attendance_text') }}</p>
            </div>
        @else
            <div class="table-wrap" style="border:none;border-radius:0;">
                <table class="data">
                    <thead><tr><th>{{ __('ui.student.detail') }}</th><th>{{ __('ui.common.status') }}</th><th>{{ __('ui.student.remark') }}</th></tr></thead>
                    <tbody>
                        @foreach ($attendances as $record)
                            <tr>
                                <td><strong>{{ $record->date->format('d M Y') }}</strong><span class="muted" style="margin-left:8px;font-size:.8rem;">{{ $record->date->translatedFormat('l') }}</span></td>
                                <td>
                                    <span class="badge {{ $record->status === 'present' ? 'badge-success' : ($record->status === 'absent' ? 'badge-danger' : 'badge-warning') }}">
                                        {{ __('ui.student.' . $record->status) }}
                                    </span>
                                </td>
                                <td class="muted">{{ $record->remark ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding:20px 24px;">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>
@endsection
