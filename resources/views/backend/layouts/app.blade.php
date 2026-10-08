<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') — Admin · {{ $settings['school_name'] ?? 'My School' }}</title>

    @if (! empty($settings['favicon']))
        <link rel="icon" href="{{ asset('storage/' . $settings['favicon']) }}">
    @endif

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #2563EB;
            --primary-dark: #1D4ED8;
            --secondary: #0F172A;
            --sidebar: #0B1220;
            --sidebar-hover: rgba(255,255,255,.06);
            --bg: #F1F5F9;
            --white: #fff;
            --text: #1E293B;
            --muted: #64748B;
            --border: #E2E8F0;
            --success: #16A34A;
            --warning: #F59E0B;
            --danger: #DC2626;
            --gradient: linear-gradient(135deg, #2563EB, #1E40AF);
            --shadow-sm: 0 1px 2px rgba(15,23,42,.06);
            --shadow: 0 10px 30px -14px rgba(15,23,42,.18);
            --radius: 14px;
            --radius-sm: 10px;
            --sidebar-w: 262px;
            --top-h: 66px;
            --font: 'Segoe UI', system-ui, -apple-system, 'Helvetica Neue', Arial, sans-serif;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font); background: var(--bg); color: var(--text); line-height: 1.55; -webkit-font-smoothing: antialiased; }
        a { text-decoration: none; color: inherit; }
        img { max-width: 100%; display: block; }
        ul { list-style: none; }
        h1, h2, h3, h4 { color: var(--secondary); font-weight: 700; line-height: 1.25; }

        /* ============ SHELL ============ */
        .admin-shell { display: flex; min-height: 100vh; }

        /* ============ SIDEBAR ============ */
        .sidebar {
            width: var(--sidebar-w); flex-shrink: 0; background: var(--sidebar); color: #cbd5e1;
            display: flex; flex-direction: column; position: fixed; inset: 0 auto 0 0; z-index: 1200;
            transition: transform .3s cubic-bezier(.4,0,.2,1); overflow-y: auto;
        }
        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(148,163,184,.25); border-radius: 10px; }

        .sidebar-brand { display: flex; align-items: center; gap: 12px; padding: 20px 22px; border-bottom: 1px solid rgba(255,255,255,.07); }
        .sidebar-brand-mark {
            width: 42px; height: 42px; border-radius: 12px; background: var(--gradient); color: #fff;
            display: grid; place-items: center; font-weight: 800; font-size: 1rem; flex-shrink: 0; overflow: hidden;
        }
        .sidebar-brand-mark img { width: 100%; height: 100%; object-fit: cover; }
        .sidebar-brand-text { color: #fff; font-weight: 700; font-size: 1rem; line-height: 1.2; }
        .sidebar-brand-text small { display: block; font-size: .68rem; color: #64748B; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; }

        .sidebar-section { padding: 18px 22px 8px; font-size: .67rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #475569; }
        .sidebar-link {
            display: flex; align-items: center; gap: 12px; padding: 11px 16px; margin: 2px 12px;
            border-radius: 11px; font-size: .875rem; font-weight: 500; color: #94A3B8; transition: all .2s ease;
            position: relative;
        }
        .sidebar-link:hover { background: var(--sidebar-hover); color: #fff; }
        .sidebar-link.active { background: var(--gradient); color: #fff; box-shadow: 0 10px 22px -14px rgba(37,99,235,.95); }
        .sidebar-link .ico { width: 20px; text-align: center; font-size: .95rem; flex-shrink: 0; line-height: 1; }
        .sidebar-link .count {
            margin-left: auto; background: var(--danger); color: #fff; font-size: .68rem; font-weight: 700;
            padding: 1px 7px; border-radius: 999px;
        }
        .sidebar-link.active .count { background: rgba(255,255,255,.28); }

        .sidebar-foot { margin-top: auto; padding: 16px 22px; border-top: 1px solid rgba(255,255,255,.07); font-size: .72rem; color: #475569; }

        /* ============ MAIN ============ */
        .admin-main { flex: 1; min-width: 0; margin-left: var(--sidebar-w); display: flex; flex-direction: column; }

        .topbar {
            height: var(--top-h); background: rgba(255,255,255,.92); backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 16px;
            padding: 0 26px; position: sticky; top: 0; z-index: 1100;
        }
        .topbar-toggle {
            display: none; width: 42px; height: 42px; border-radius: 11px; border: 1px solid var(--border);
            background: var(--white); cursor: pointer; font-size: 1.1rem; color: var(--secondary);
        }
        .topbar-crumbs { display: flex; align-items: center; gap: 8px; font-size: .84rem; color: var(--muted); margin-right: auto; flex-wrap: wrap; }
        .topbar-crumbs a:hover { color: var(--primary); }
        .topbar-crumbs .sep { opacity: .5; }
        .topbar-crumbs .current { color: var(--secondary); font-weight: 600; }

        .topbar-user { display: flex; align-items: center; gap: 11px; padding: 6px 12px 6px 6px; border-radius: 999px; border: 1px solid var(--border); background: var(--white); }
        .topbar-avatar { width: 34px; height: 34px; border-radius: 50%; background: var(--gradient); color: #fff; display: grid; place-items: center; font-size: .8rem; font-weight: 700; flex-shrink: 0; }
        .topbar-user-name { font-size: .84rem; font-weight: 600; }
        .topbar-user-role { font-size: .7rem; color: var(--muted); }

        .admin-content { padding: 28px 26px 48px; flex: 1; }

        /* ============ PAGE HEADER ============ */
        .page-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 18px; flex-wrap: wrap; margin-bottom: 24px; }
        .page-header h1 { font-size: 1.5rem; letter-spacing: -.02em; }
        .page-header p { color: var(--muted); font-size: .88rem; margin-top: 4px; }
        .page-actions { display: flex; gap: 10px; flex-wrap: wrap; }

        /* ============ CARDS ============ */
        .b-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow-sm); overflow: hidden; }
        .b-card-head { padding: 18px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; }
        .b-card-head h3 { font-size: 1rem; }
        .b-card-body { padding: 22px; }

        .b-grid { display: grid; gap: 20px; }
        .b-grid-2 { grid-template-columns: repeat(2, minmax(0,1fr)); }
        .b-grid-3 { grid-template-columns: repeat(3, minmax(0,1fr)); }
        .b-grid-4 { grid-template-columns: repeat(4, minmax(0,1fr)); }

        /* ============ STAT CARDS ============ */
        .stat-card {
            background: var(--white); border: 1px solid var(--border); border-radius: var(--radius);
            padding: 20px; display: flex; align-items: center; gap: 15px; position: relative; overflow: hidden;
            transition: transform .28s cubic-bezier(.4,0,.2,1), box-shadow .28s ease, border-color .28s ease;
            animation: cardIn .5s cubic-bezier(.4,0,.2,1) backwards;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow); border-color: rgba(37,99,235,.3); }
        .stat-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--accent, var(--primary)); }
        .stat-ico { width: 48px; height: 48px; border-radius: 13px; display: grid; place-items: center; font-size: 1.2rem; background: color-mix(in srgb, var(--accent, var(--primary)) 12%, white); color: var(--accent, var(--primary)); flex-shrink: 0; }
        .stat-value { font-size: 1.55rem; font-weight: 800; letter-spacing: -.02em; line-height: 1.15; }
        .stat-label { font-size: .8rem; color: var(--muted); font-weight: 600; }
        @keyframes cardIn { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: none; } }

        /* ============ BUTTONS ============ */
        .b-btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 11px 20px; border-radius: 11px; font-weight: 600; font-size: .86rem;
            border: 1px solid transparent; cursor: pointer; font-family: inherit; transition: all .22s ease; white-space: nowrap;
        }
        .b-btn-primary { background: var(--gradient); color: #fff; box-shadow: 0 10px 22px -14px rgba(37,99,235,.95); }
        .b-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 30px -14px rgba(37,99,235,1); }
        .b-btn-outline { background: var(--white); color: var(--text); border-color: var(--border); }
        .b-btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .b-btn-danger { background: var(--danger); color: #fff; }
        .b-btn-danger:hover { transform: translateY(-2px); box-shadow: 0 12px 24px -14px rgba(220,38,38,.9); }
        .b-btn-success { background: var(--success); color: #fff; }
        .b-btn-success:hover { transform: translateY(-2px); }
        .b-btn-sm { padding: 7px 14px; font-size: .78rem; border-radius: 9px; }
        .b-btn-icon { width: 34px; height: 34px; padding: 0; border-radius: 9px; }
        .b-btn-block { width: 100%; }

        /* ============ TABLES ============ */
        .b-table-wrap { overflow-x: auto; }
        table.b-table { width: 100%; border-collapse: collapse; font-size: .865rem; }
        table.b-table th {
            text-align: left; padding: 13px 18px; background: #F8FAFC; color: var(--muted);
            font-size: .71rem; text-transform: uppercase; letter-spacing: .07em; font-weight: 700; white-space: nowrap;
            border-bottom: 1px solid var(--border);
        }
        table.b-table td { padding: 14px 18px; border-bottom: 1px solid #F1F5F9; vertical-align: middle; }
        table.b-table tbody tr { transition: background .18s ease; }
        table.b-table tbody tr:hover { background: #F8FAFC; }
        table.b-table tbody tr:last-child td { border-bottom: none; }
        .cell-actions { display: flex; gap: 6px; }

        /* ============ BADGES ============ */
        .badge {
            display: inline-flex; align-items: center; gap: 5px; padding: 4px 11px; border-radius: 999px;
            font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em;
            background: rgba(37,99,235,.1); color: var(--primary);
        }
        .badge-success { background: rgba(22,163,74,.12); color: #15803D; }
        .badge-warning { background: rgba(245,158,11,.15); color: #B45309; }
        .badge-danger { background: rgba(220,38,38,.12); color: #B91C1C; }
        .badge-muted { background: #F1F5F9; color: var(--muted); }

        /* ============ FORMS ============ */
        .form-row { margin-bottom: 18px; }
        .form-row label { display: block; font-weight: 600; font-size: .82rem; margin-bottom: 7px; color: var(--secondary); }
        .form-control, .form-select, textarea.form-control {
            width: 100%; padding: 11px 14px; border: 1.5px solid var(--border); border-radius: var(--radius-sm);
            font-family: inherit; font-size: .89rem; color: var(--text); background: var(--white); transition: border-color .2s ease, box-shadow .2s ease;
        }
        .form-control:focus, .form-select:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(37,99,235,.1); }
        textarea.form-control { resize: vertical; min-height: 110px; }
        .form-control.is-invalid, .form-select.is-invalid { border-color: var(--danger); }
        .form-error { color: var(--danger); font-size: .76rem; margin-top: 5px; font-weight: 600; }
        .form-hint { color: var(--muted); font-size: .76rem; margin-top: 5px; }
        .form-grid { display: grid; gap: 0 20px; grid-template-columns: repeat(2, minmax(0,1fr)); }
        .form-grid-3 { grid-template-columns: repeat(3, minmax(0,1fr)); }
        .span-2 { grid-column: span 2; }
        .checkbox-row { display: flex; align-items: center; gap: 9px; font-size: .87rem; font-weight: 600; color: var(--text); cursor: pointer; }
        .checkbox-row input { width: 17px; height: 17px; accent-color: var(--primary); }

        /* ============ ALERTS ============ */
        .b-alert { display: flex; gap: 11px; padding: 14px 17px; border-radius: var(--radius-sm); margin-bottom: 18px; font-size: .87rem; font-weight: 500; animation: cardIn .3s ease; }
        .b-alert-success { background: rgba(22,163,74,.1); color: #15803D; border: 1px solid rgba(22,163,74,.25); }
        .b-alert-danger { background: rgba(220,38,38,.08); color: #B91C1C; border: 1px solid rgba(220,38,38,.22); }
        .b-alert-info { background: rgba(37,99,235,.08); color: #1D4ED8; border: 1px solid rgba(37,99,235,.2); }

        /* ============ EMPTY ============ */
        .b-empty { text-align: center; padding: 62px 22px; color: var(--muted); }
        .b-empty-ico { width: 68px; height: 68px; margin: 0 auto 16px; border-radius: 50%; background: rgba(37,99,235,.08); display: grid; place-items: center; font-size: 1.7rem; color: var(--primary); }
        .b-empty h3 { margin-bottom: 6px; font-size: 1.02rem; }

        /* ============ PAGINATION ============ */
        .pg { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 22px; }
        .pg-item { display: grid; place-items: center; min-width: 36px; height: 36px; padding: 0 11px; border: 1px solid var(--border); border-radius: 10px; font-size: .82rem; font-weight: 600; color: var(--muted); background: var(--white); transition: all .2s ease; }
        .pg-item:hover { border-color: var(--primary); color: var(--primary); }
        .pg-active { background: var(--gradient); color: #fff; border-color: transparent; }
        .pg-disabled { opacity: .45; pointer-events: none; }

        /* ============ FILTER BAR ============ */
        .filter-bar { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 18px; margin-bottom: 20px; }
        .filter-grid { display: grid; gap: 12px; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); align-items: end; }

        /* ============ LIVE SEARCH ============ */
        .filter-search { position: relative; display: block; }
        .filter-search .form-control { padding-left: 36px; padding-right: 38px; }
        .live-spin {
            position: absolute; left: 13px; top: 50%; width: 14px; height: 14px; margin-top: -7px;
            border-radius: 50%; border: 2px solid rgba(37,99,235,.2); border-top-color: var(--primary);
            opacity: 0; transition: opacity .15s ease;
        }
        .filter-search.is-loading .live-spin { opacity: 1; animation: spin .65s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .live-clear {
            position: absolute; right: 8px; top: 50%; transform: translateY(-50%); display: none; place-items: center;
            width: 22px; height: 22px; padding: 0; border: none; border-radius: 50%;
            background: #E2E8F0; color: var(--muted); font-size: 1rem; line-height: 1; cursor: pointer;
        }
        .live-clear.show { display: grid; }
        .live-clear:hover { background: var(--danger); color: #fff; }
        .live-results { transition: opacity .18s ease; }
        .live-results.is-fetching { opacity: .5; pointer-events: none; }

        /* ============ IMAGE PICKER / PREVIEW ============ */
        .img-field { display: flex; flex-direction: column; gap: 12px; }
        .img-preview {
            display: grid; place-items: center; min-height: 148px; padding: 12px;
            border: 1.5px dashed var(--border); border-radius: var(--radius-sm);
            background: #F8FAFC; overflow: hidden;
        }
        .img-preview-sm { min-height: 92px; }
        .img-preview img { max-width: 100%; max-height: 220px; border-radius: 8px; object-fit: contain; }
        .img-preview-sm img { max-height: 72px; }
        .img-preview.is-empty { color: var(--muted); font-size: 1.7rem; }
        .img-actions { display: flex; flex-direction: column; gap: 10px; }
        .img-actions .form-control { padding: 9px 11px; }
        .img-name { font-size: .76rem; color: var(--muted); font-weight: 600; word-break: break-all; }
        .img-previews { display: grid; grid-template-columns: repeat(auto-fill, minmax(84px, 1fr)); gap: 10px; margin-top: 12px; }
        .img-previews:empty { display: none; }
        .img-previews figure { margin: 0; border: 1px solid var(--border); border-radius: 10px; overflow: hidden; background: #F8FAFC; }
        .img-previews img { width: 100%; aspect-ratio: 1/1; object-fit: cover; display: block; }
        .img-previews figcaption {
            font-size: .66rem; color: var(--muted); font-weight: 600; padding: 6px 7px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background: var(--white);
        }

        /* ============ MODAL ============ */
        .b-modal { position: fixed; inset: 0; background: rgba(15,23,42,.68); backdrop-filter: blur(5px); display: none; align-items: center; justify-content: center; padding: 20px; z-index: 3000; }
        .b-modal.open { display: flex; animation: fadeIn .2s ease; }
        .b-modal-box { background: var(--white); border-radius: 18px; width: 100%; max-width: 560px; max-height: 90vh; overflow: auto; animation: modalIn .3s cubic-bezier(.4,0,.2,1); }
        .b-modal-head { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 14px; }
        .b-modal-head h3 { font-size: 1.08rem; }
        .b-modal-body { padding: 24px; }
        .b-modal-foot { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; }
        .b-modal-close { width: 34px; height: 34px; border-radius: 9px; border: none; background: #F1F5F9; cursor: pointer; font-size: 1rem; color: var(--muted); }
        .b-modal-close:hover { background: #E2E8F0; }
        @keyframes modalIn { from { opacity: 0; transform: scale(.95) translateY(14px); } to { opacity: 1; transform: none; } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        /* ============ AVATAR ============ */
        .b-avatar { width: 38px; height: 38px; border-radius: 50%; background: var(--gradient); color: #fff; display: grid; place-items: center; font-weight: 700; font-size: .78rem; flex-shrink: 0; overflow: hidden; }
        .b-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .b-user-cell { display: flex; align-items: center; gap: 11px; }

        .b-sidebar-backdrop { position: fixed; inset: 0; background: rgba(15,23,42,.55); z-index: 1150; display: none; }
        .b-sidebar-backdrop.show { display: block; }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 1100px) {
            .b-grid-4 { grid-template-columns: repeat(2, minmax(0,1fr)); }
            .b-grid-3 { grid-template-columns: repeat(2, minmax(0,1fr)); }
        }
        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: none; }
            .admin-main { margin-left: 0; }
            .topbar-toggle { display: grid; place-items: center; }
            .topbar-user-name, .topbar-user-role { display: none; }
        }
        @media (max-width: 640px) {
            .admin-content { padding: 20px 16px 40px; }
            .b-grid-2, .b-grid-3, .b-grid-4, .form-grid, .form-grid-3 { grid-template-columns: 1fr; }
            .span-2 { grid-column: span 1; }
            .page-header h1 { font-size: 1.25rem; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="admin-shell">
        @include('backend.partials.sidebar')

        <div class="b-sidebar-backdrop" id="sidebarBackdrop"></div>

        <div class="admin-main">
            @include('backend.partials.topbar')

            <div class="admin-content">
                @include('backend.partials.flash')
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Confirmation modal --}}
    <div class="b-modal" id="confirmModal">
        <div class="b-modal-box" style="max-width:440px;">
            <div class="b-modal-body" style="text-align:center;padding:32px;">
                <div class="b-empty-ico" style="background:rgba(220,38,38,.1);color:var(--danger);">!</div>
                <h3 style="margin-bottom:8px;">Confirm Action</h3>
                <p class="muted" id="confirmText" style="font-size:.88rem;margin-bottom:24px;">This action cannot be undone.</p>
                <div style="display:flex;gap:10px;justify-content:center;">
                    <button type="button" class="b-btn b-btn-outline" data-close>Cancel</button>
                    <button type="button" class="b-btn b-btn-danger" id="confirmOk">Yes, Continue</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ---------- Sidebar toggle ----------
        (function () {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            const backdrop = document.getElementById('sidebarBackdrop');

            const close = () => { sidebar.classList.remove('open'); backdrop.classList.remove('show'); };

            toggle && toggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
                backdrop.classList.toggle('show');
            });

            backdrop && backdrop.addEventListener('click', close);
        })();

        // ---------- Live search (every admin list screen) ----------
        (function () {
            const forms = Array.from(document.querySelectorAll('form.filter-bar'));
            if (!forms.length) return;

            // The results are the cards/grids that follow the filter bar.
            const targetsOf = (form) => {
                const targets = [];
                let el = form.nextElementSibling;

                while (el) {
                    if (el.classList.contains('b-card') || el.classList.contains('b-grid') || el.classList.contains('b-table-wrap')) {
                        targets.push(el);
                    }
                    el = el.nextElementSibling;
                }

                // Prefer the cards that actually hold rows, so side panels (e.g. "Publish Results") stay untouched.
                const withRows = targets.filter((t) => t.matches('.b-grid') || t.querySelector('.b-table-wrap, .b-empty, .pg'));

                return withRows.length ? withRows : targets;
            };

            forms.forEach((form) => {
                const targets = targetsOf(form);
                if (!targets.length) return;

                const results = document.createElement('div');
                results.className = 'live-results';
                targets[0].parentNode.insertBefore(results, targets[0]);
                targets.forEach((node) => results.appendChild(node));

                // Wrap every text field: spinner on the left, clear button on the right.
                const wraps = Array.from(form.querySelectorAll('input[type="text"], input[type="search"]')).map((input) => {
                    const wrap = document.createElement('span');
                    wrap.className = 'filter-search';
                    input.parentNode.insertBefore(wrap, input);
                    wrap.appendChild(input);
                    wrap.insertAdjacentHTML('afterbegin', '<span class="live-spin" aria-hidden="true"></span>');

                    const clear = document.createElement('button');
                    clear.type = 'button';
                    clear.className = 'live-clear';
                    clear.setAttribute('aria-label', 'Clear search');
                    clear.innerHTML = '&times;';
                    wrap.appendChild(clear);

                    const sync = () => clear.classList.toggle('show', input.value !== '');
                    sync();
                    clear.addEventListener('click', () => { input.value = ''; sync(); input.focus(); schedule(); });

                    return wrap;
                });

                let timer = null;
                let lastUrl = null;
                let seq = 0;

                const url = () => {
                    const params = new URLSearchParams();
                    new FormData(form).forEach((value, key) => {
                        if (String(value).trim() !== '') params.append(key, value);
                    });

                    const qs = params.toString();
                    const base = form.getAttribute('action') || window.location.pathname;

                    return qs ? base + '?' + qs : base;
                };

                const run = () => {
                    const target = url();
                    if (target === lastUrl) return;
                    lastUrl = target;

                    const token = ++seq;
                    wraps.forEach((w) => w.classList.add('is-loading'));
                    results.classList.add('is-fetching');

                    fetch(target, { credentials: 'same-origin', headers: { 'X-Requested-With': 'fetch' } })
                        .then((res) => { if (!res.ok) throw new Error('request failed'); return res.text(); })
                        .then((html) => {
                            if (token !== seq) return;

                            const doc = new DOMParser().parseFromString(html, 'text/html');
                            const docForm = doc.querySelector('form.filter-bar');
                            const fresh = docForm ? targetsOf(docForm) : [];
                            if (!fresh.length) throw new Error('no results in response');

                            results.replaceChildren(...fresh.map((node) => document.importNode(node, true)));
                            history.replaceState(null, '', target);
                            document.dispatchEvent(new CustomEvent('admin:live-swapped', { detail: { root: results } }));
                        })
                        .catch(() => { lastUrl = null; form.submit(); })
                        .finally(() => {
                            if (token === seq) {
                                wraps.forEach((w) => w.classList.remove('is-loading'));
                                results.classList.remove('is-fetching');
                            }
                        });
                };

                const schedule = () => { clearTimeout(timer); timer = setTimeout(run, 300); };

                form.addEventListener('submit', (e) => { e.preventDefault(); clearTimeout(timer); run(); });
                form.addEventListener('input', (e) => { if (e.target.matches('input')) schedule(); });
                form.addEventListener('change', (e) => { if (e.target.matches('select, input')) schedule(); });

                lastUrl = url();
            });
        })();

        // ---------- Confirmation modal ----------
        (function () {
            const modal = document.getElementById('confirmModal');
            if (!modal) return;
            const text = document.getElementById('confirmText');
            const ok = document.getElementById('confirmOk');
            let form = null;

            document.addEventListener('click', (e) => {
                const trigger = e.target.closest('[data-confirm]');
                if (trigger) {
                    e.preventDefault();
                    // a data-form reference wins: triggers inside a big form (e.g. settings)
                    // must not submit that surrounding form instead of the intended one.
                    form = (trigger.dataset.form && document.getElementById(trigger.dataset.form))
                        || trigger.closest('form');
                    text.textContent = trigger.dataset.confirm || 'This action cannot be undone.';
                    modal.classList.add('open');
                    return;
                }
                if (e.target.closest('[data-close]') || e.target === modal) {
                    modal.classList.remove('open');
                    form = null;
                }
            });

            ok.addEventListener('click', () => {
                if (form) form.submit();
                modal.classList.remove('open');
            });

            document.addEventListener('keydown', (e) => { if (e.key === 'Escape') modal.classList.remove('open'); });
        })();

        // ---------- Generic modal openers ----------
        (function () {
            document.addEventListener('click', (e) => {
                const opener = e.target.closest('[data-modal-open]');
                if (opener) {
                    e.preventDefault();
                    document.getElementById(opener.dataset.modalOpen)?.classList.add('open');
                }
                const closer = e.target.closest('[data-modal-close]');
                if (closer) {
                    e.preventDefault();
                    closer.closest('.b-modal')?.classList.remove('open');
                }
            });
        })();

        // ---------- Select-all checkboxes ----------
        function bindSelectAll(root) {
            root.querySelectorAll('[data-check-all]').forEach((master) => {
                master.addEventListener('change', () => {
                    root.querySelectorAll(master.dataset.checkAll).forEach((cb) => { cb.checked = master.checked; });
                });
            });
        }
        bindSelectAll(document);
        document.addEventListener('admin:live-swapped', (e) => bindSelectAll(e.detail.root));

        // ---------- Image pickers ----------
        function bindImagePicker(field) {
            if (field.dataset.pickerBound) return;
            field.dataset.pickerBound = '1';

            const input = field.querySelector('[data-preview-input]');
            if (!input) return;

            const box = field.querySelector('[data-preview-box]');
            const grid = field.querySelector('[data-preview-list]');
            const name = field.querySelector('[data-preview-name]');
            const actions = field.querySelector('.img-actions');

            input.addEventListener('change', () => {
                const files = Array.from(input.files || []);
                if (!files.length) return;

                if (grid) {
                    grid.innerHTML = '';
                    files.forEach((file) => {
                        const item = document.createElement('figure');
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.alt = file.name;
                        const caption = document.createElement('figcaption');
                        caption.textContent = file.name;
                        caption.title = file.name;
                        item.append(img, caption);
                        grid.appendChild(item);
                    });
                    grid.hidden = false;
                }

                if (box) {
                    const first = files[0];
                    let img = box.querySelector('[data-preview-img]');

                    if (!img) {
                        box.innerHTML = '';
                        img = document.createElement('img');
                        img.setAttribute('data-preview-img', '');
                        box.appendChild(img);
                    }

                    img.src = URL.createObjectURL(first);
                    img.alt = first.name;
                    box.classList.remove('is-empty');
                }

                if (name) {
                    name.textContent = files.length > 1 ? files.length + ' images selected' : files[0].name;
                    name.hidden = false;
                } else if (actions && !actions.querySelector('.img-name')) {
                    const label = document.createElement('div');
                    label.className = 'img-name';
                    label.textContent = files.length > 1 ? files.length + ' images selected' : files[0].name;
                    actions.prepend(label);
                }
            });
        }
        document.querySelectorAll('[data-preview]').forEach(bindImagePicker);

        // ---------- Auto-dismiss alerts ----------
        document.querySelectorAll('[data-dismiss]').forEach((el) => {
            setTimeout(() => {
                el.style.transition = 'opacity .4s ease';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 400);
            }, 6000);
        });
    </script>

    @stack('scripts')
</body>
</html>
