@php
    $authUser = auth()->user();

    // Build breadcrumbs automatically from the current route name.
    $routeName = request()->route()?->getName() ?? 'admin.dashboard';
    $segments = collect(explode('.', $routeName))->slice(1); // drop the "admin" prefix

    $labels = [
        'index' => 'All',
        'create' => 'Create',
        'edit' => 'Edit',
        'show' => 'Details',
        'entry' => 'Entry',
        'report' => 'Report',
        'settings' => 'Website Settings',
        'classes' => 'Classes',
        'sessions' => 'Academic Sessions',
        'messages' => 'Messages',
        'gallery' => 'Gallery',
        'admissions' => 'Admissions',
        'attendance' => 'Attendance',
        'teachers' => 'Teachers',
        'students' => 'Students',
        'subjects' => 'Subjects',
        'results' => 'Results',
        'notices' => 'Notices',
        'events' => 'Events',
        'news' => 'News',
        'exams' => 'Exams',
        'dashboard' => 'Dashboard',
    ];

    $crumbItems = [];
    foreach ($segments as $segment) {
        $crumbItems[] = $labels[$segment] ?? ucfirst($segment);
    }
@endphp

<header class="topbar">
    <button type="button" class="topbar-toggle" id="sidebarToggle" aria-label="Toggle sidebar"><i class="bi bi-list"></i></button>

    <nav class="topbar-crumbs" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Admin</a>
        @foreach ($crumbItems as $crumb)
            <span class="sep">/</span>
            <span class="{{ $loop->last ? 'current' : '' }}">{{ $crumb }}</span>
        @endforeach
    </nav>

    <div class="topbar-user">
        <span class="topbar-avatar">{{ strtoupper(substr($authUser->name ?? 'A', 0, 1)) }}</span>
        <span>
            <span class="topbar-user-name">{{ $authUser->name ?? 'Administrator' }}</span>
            <span class="topbar-user-role">Administrator</span>
        </span>
    </div>

    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="b-btn b-btn-outline b-btn-sm" title="Logout">Logout</button>
    </form>
</header>
