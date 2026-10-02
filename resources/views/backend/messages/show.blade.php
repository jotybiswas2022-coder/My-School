@extends('backend.layouts.app')

@section('title', 'Message Details')

@section('content')
    <div class="page-header">
        <div>
            <h1>{{ $message->subject }}</h1>
            <p>From {{ $message->name }} · {{ $message->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.messages.index') }}" class="b-btn b-btn-outline"><i class="bi bi-arrow-left"></i> Back</a>
            <form method="POST" action="{{ route('admin.messages.read', $message) }}">
                @csrf
                <button class="b-btn b-btn-outline">{{ $message->is_read ? 'Mark Unread' : 'Mark Read' }}</button>
            </form>
            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}">
                @csrf @method('DELETE')
                <button type="button" class="b-btn b-btn-danger" data-confirm="Delete this message?">Delete</button>
            </form>
        </div>
    </div>

    <div class="b-grid" style="grid-template-columns:1fr 320px;align-items:start;">
        <div class="b-card">
            <div class="b-card-head"><h3>Message</h3></div>
            <div class="b-card-body">
                <p style="font-size:.94rem;line-height:1.85;white-space:pre-line;">{{ $message->message }}</p>
            </div>
        </div>

        <div class="b-card">
            <div class="b-card-head"><h3>Sender Details</h3></div>
            <div class="b-card-body">
                <div style="display:flex;flex-direction:column;gap:14px;">
                    @foreach ([
                        ['Name', $message->name],
                        ['Email', $message->email],
                        ['Phone', $message->phone ?? '—'],
                        ['Received', $message->created_at->format('d M Y, h:i A')],
                    ] as $row)
                        <div>
                            <div style="font-size:.7rem;text-transform:uppercase;letter-spacing:.07em;font-weight:700;color:var(--muted);">{{ $row[0] }}</div>
                            <strong style="font-size:.9rem;">{{ $row[1] }}</strong>
                        </div>
                    @endforeach
                </div>

                <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->subject) }}" class="b-btn b-btn-primary b-btn-block" style="margin-top:20px;">Reply by Email</a>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 900px) { .b-grid[style*="320px"] { grid-template-columns: 1fr !important; } }
    </style>
@endsection
