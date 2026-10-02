@php
    $links = [
        ['student.dashboard', __('ui.student.dashboard'), 'bi-speedometer2'],
        ['student.profile', __('ui.student.my_profile'), 'bi-person-circle'],
        ['student.attendance', __('ui.student.attendance'), 'bi-calendar-check'],
        ['student.results', __('ui.student.my_results'), 'bi-award'],
        ['student.notices', __('ui.nav.notices'), 'bi-megaphone'],
        ['student.academics', __('ui.nav.academics'), 'bi-journal-bookmark'],
    ];
@endphp

<aside class="student-nav">
    <div class="student-nav-card">
        <div class="student-user">
            <div class="avatar" style="width:64px;height:64px;margin:0 auto 12px;font-size:1.1rem;">
                @if (auth('student')->user()->photo)
                    <img src="{{ asset('storage/' . auth('student')->user()->photo) }}" alt="{{ auth('student')->user()->name }}">
                @else
                    {{ auth('student')->user()->initials() }}
                @endif
            </div>
            <strong style="font-size:.92rem;display:block;">{{ auth('student')->user()->name }}</strong>
            <span class="muted" style="font-size:.76rem;">{{ auth('student')->user()->student_id }}</span>
        </div>

        @foreach ($links as $link)
            <a href="{{ route($link[0]) }}" class="student-link {{ request()->routeIs($link[0]) ? 'active' : '' }}">
                <span class="student-icon"><i class="bi {{ $link[2] }}"></i></span>
                {{ $link[1] }}
            </a>
        @endforeach

        <form method="POST" action="{{ route('student.logout') }}" style="margin-top:14px;">
            @csrf
            <button type="submit" class="student-link" style="width:100%;border:none;background:rgba(220,38,38,.08);color:var(--danger);cursor:pointer;font-family:inherit;">
                <span class="student-icon"><i class="bi bi-box-arrow-right"></i></span> {{ __('ui.admin.logout') }}
            </button>
        </form>
    </div>
</aside>
