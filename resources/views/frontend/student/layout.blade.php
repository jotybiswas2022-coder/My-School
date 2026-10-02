@extends('frontend.layouts.app')

@section('content')
    <section class="section-sm" style="background:var(--bg);">
        <div class="container container-wide">
            <div class="student-shell">
                @include('frontend.student.partials.sidebar')
                <div class="student-main">
                    @yield('student-content')
                </div>
            </div>
        </div>
    </section>

    <style>
        .student-shell { display: grid; grid-template-columns: 260px 1fr; gap: 28px; align-items: start; }
        .student-main { min-width: 0; }
        .student-nav { position: sticky; top: 96px; }
        .student-nav-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px; }
        .student-user { text-align: center; padding-bottom: 18px; border-bottom: 1px solid var(--border); margin-bottom: 16px; }
        .student-link {
            display: flex; align-items: center; gap: 11px; padding: 12px 14px; border-radius: 12px;
            font-size: .89rem; font-weight: 600; color: var(--muted); transition: all .2s ease; margin-bottom: 4px;
        }
        .student-link:hover { background: rgba(37,99,235,.07); color: var(--primary); }
        .student-link.active { background: var(--gradient); color: #fff; box-shadow: 0 10px 22px -14px rgba(37,99,235,.9); }
        .student-icon { width: 22px; text-align: center; }

        .stat-tile { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 24px; }
        .stat-tile .value { font-size: 1.9rem; font-weight: 800; color: var(--secondary); letter-spacing: -.02em; }
        .stat-tile .label { font-size: .8rem; color: var(--muted); font-weight: 600; }

        .panel { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; }
        .panel-head { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; }
        .panel-head h3 { font-size: 1.02rem; }
        .panel-body { padding: 24px; }

        @media (max-width: 900px) {
            .student-shell { grid-template-columns: 1fr; }
            .student-nav { position: static; }
            .student-nav-card { display: flex; flex-wrap: wrap; gap: 6px; }
            .student-user { display: none; }
            .student-link { margin-bottom: 0; }
        }
    </style>
@endsection
