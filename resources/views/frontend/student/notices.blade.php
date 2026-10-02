@extends('frontend.student.layout')

@section('title', __('ui.notices.title') . ' — ' . ($settings['school_name'] ?? 'My School'))

@section('student-content')
    <div class="panel">
        <div class="panel-head"><h3>{{ __('ui.notices.student_title') }}</h3></div>

        @if ($notices->isEmpty())
            <div class="empty" style="padding:60px 20px;">
                <div class="empty-icon"><i class="bi bi-megaphone"></i></div>
                <h3>{{ __('ui.notices.student_empty') }}</h3>
                <p>{{ __('ui.notices.student_empty_text') }}</p>
            </div>
        @else
            <div class="panel-body" style="padding:0;">
                @foreach ($notices as $notice)
                    <div style="padding:22px 24px;border-bottom:1px solid var(--border);">
                        <div class="row-between" style="margin-bottom:8px;">
                            <span class="badge">{{ __('ui.categories.' . $notice->category) }}</span>
                            <span class="muted" style="font-size:.78rem;font-weight:600;"><i class="bi bi-calendar3"></i> {{ optional($notice->published_at)->format('d M Y') }}</span>
                        </div>
                        <h4 style="font-size:.98rem;margin-bottom:6px;">{{ $notice->title }}</h4>
                        <p class="muted" style="font-size:.86rem;">{{ \Illuminate\Support\Str::limit(strip_tags($notice->description), 200) }}</p>
                        @if ($notice->attachment)
                            <a href="{{ asset('storage/' . $notice->attachment) }}" target="_blank" rel="noopener" class="muted" style="font-size:.8rem;font-weight:600;display:inline-block;margin-top:8px;"><i class="bi bi-paperclip"></i> {{ __('ui.notices.view_attachment') }}</a>
                        @endif
                    </div>
                @endforeach
            </div>

            <div style="padding:20px 24px;">
                {{ $notices->links() }}
            </div>
        @endif
    </div>
@endsection
