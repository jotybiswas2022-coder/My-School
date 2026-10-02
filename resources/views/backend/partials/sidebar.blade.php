@php
    $pendingAdmissions = \App\Models\Admission::where('status', 'pending')->count();
    $unreadMessages = \App\Models\Contact::where('is_read', false)->count();

    $groups = [
        'Overview' => [
            ['admin.dashboard', 'Dashboard', '▤', null],
        ],
        'Academics' => [
            ['admin.classes.index', 'Classes & Sections', '▦', null],
            ['admin.subjects.index', 'Subjects', '◈', null],
            ['admin.sessions.index', 'Academic Sessions', '◷', null],
            ['admin.exams.index', 'Exams', '✎', null],
            ['admin.results.index', 'Results', '★', null],
            ['admin.attendance.index', 'Attendance', '◔', null],
        ],
        'People' => [
            ['admin.students.index', 'Students', '◉', null],
            ['admin.teachers.index', 'Teachers & Staff', '◍', null],
        ],
        'Content' => [
            ['admin.notices.index', 'Notices', '📢', null],
            ['admin.events.index', 'Events', '📅', null],
            ['admin.news.index', 'News', '📰', null],
            ['admin.gallery.index', 'Gallery', '🖼', null],
        ],
        'Inbox' => [
            ['admin.admissions.index', 'Admissions', '📝', $pendingAdmissions],
            ['admin.messages.index', 'Messages', '✉', $unreadMessages],
        ],
        'System' => [
            ['admin.settings.edit', 'Website Settings', '⚙', null],
        ],
    ];
@endphp

<aside class="sidebar" id="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
        <span class="sidebar-brand-mark">
            @if (! empty($settings['logo']))
                <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo">
            @else
                MS
            @endif
        </span>
        <span class="sidebar-brand-text">
            {{ $settings['school_name'] ?? 'My School' }}
            <small>Admin Panel</small>
        </span>
    </a>

    <nav style="padding:10px 0 20px;">
        @foreach ($groups as $title => $links)
            <div class="sidebar-section">{{ $title }}</div>
            @foreach ($links as $link)
                <a href="{{ route($link[0]) }}" class="sidebar-link {{ request()->routeIs($link[0]) || request()->routeIs(str_replace('.index', '.*', $link[0])) ? 'active' : '' }}">
                    <span class="ico">{{ $link[2] }}</span>
                    <span>{{ $link[1] }}</span>
                    @if (! empty($link[3]))
                        <span class="count">{{ $link[3] }}</span>
                    @endif
                </a>
            @endforeach
        @endforeach
    </nav>

    <div class="sidebar-foot">
        <a href="{{ route('home') }}" target="_blank" style="color:#94A3B8;">View Website ↗</a>
        <div style="margin-top:6px;">{{ $settings['school_name'] ?? 'My School' }} Admin v1.0</div>
    </div>
</aside>
