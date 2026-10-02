@php
    $pendingAdmissions = \App\Models\Admission::where('status', 'pending')->count();
    $unreadMessages = \App\Models\Contact::where('is_read', false)->count();

    $groups = [
        'Overview' => [
            ['admin.dashboard', 'Dashboard', 'bi-speedometer2', null],
        ],
        'Academics' => [
            ['admin.classes.index', 'Classes & Sections', 'bi-diagram-3', null],
            ['admin.subjects.index', 'Subjects', 'bi-book', null],
            ['admin.sessions.index', 'Academic Sessions', 'bi-calendar-range', null],
            ['admin.exams.index', 'Exams', 'bi-pencil-square', null],
            ['admin.results.index', 'Results', 'bi-trophy', null],
            ['admin.attendance.index', 'Attendance', 'bi-calendar-check', null],
        ],
        'People' => [
            ['admin.students.index', 'Students', 'bi-people', null],
            ['admin.teachers.index', 'Teachers & Staff', 'bi-person-badge', null],
        ],
        'Content' => [
            ['admin.notices.index', 'Notices', 'bi-megaphone', null],
            ['admin.events.index', 'Events', 'bi-calendar-event', null],
            ['admin.news.index', 'News', 'bi-newspaper', null],
            ['admin.gallery.index', 'Gallery', 'bi-images', null],
        ],
        'Inbox' => [
            ['admin.admissions.index', 'Admissions', 'bi-file-earmark-text', $pendingAdmissions],
            ['admin.messages.index', 'Messages', 'bi-envelope', $unreadMessages],
        ],
        'System' => [
            ['admin.settings.edit', 'Website Settings', 'bi-gear', null],
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
                    <span class="ico"><i class="bi {{ $link[2] }}"></i></span>
                    <span>{{ $link[1] }}</span>
                    @if (! empty($link[3]))
                        <span class="count">{{ $link[3] }}</span>
                    @endif
                </a>
            @endforeach
        @endforeach
    </nav>

    <div class="sidebar-foot">
        <a href="{{ route('home') }}" target="_blank" style="color:#94A3B8;">View Website <i class="bi bi-box-arrow-up-right"></i></a>
        <div style="margin-top:6px;">{{ $settings['school_name'] ?? 'My School' }} Admin v1.0</div>
    </div>
</aside>
