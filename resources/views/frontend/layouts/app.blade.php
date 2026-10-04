<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', ($settings['school_name'] ?? 'My School') . ' - ' . ($settings['tagline'] ?? 'Empowering Students. Inspiring Futures.'))">
    <title>@yield('title', ($settings['school_name'] ?? 'My School'))</title>

    @if (! empty($settings['favicon']))
        <link rel="icon" href="{{ asset('storage/' . $settings['favicon']) }}">
    @endif

    {{-- Bootstrap Icons (icon font used instead of emoji) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* ===================== DESIGN TOKENS ===================== */
        :root {
            --primary: #2563EB;
            --primary-dark: #1D4ED8;
            --secondary: #0F172A;
            --bg: #F8FAFC;
            --white: #FFFFFF;
            --text: #1E293B;
            --muted: #64748B;
            --border: #E2E8F0;
            --success: #16A34A;
            --warning: #F59E0B;
            --danger: #DC2626;
            --gradient: linear-gradient(135deg, #2563EB 0%, #1E40AF 100%);
            --gradient-soft: linear-gradient(135deg, rgba(37,99,235,.08), rgba(37,99,235,.02));
            --radius-sm: 10px;
            --radius: 16px;
            --radius-lg: 24px;
            --shadow-sm: 0 1px 2px rgba(15,23,42,.06);
            --shadow: 0 10px 30px -12px rgba(15,23,42,.15);
            --shadow-lg: 0 24px 60px -20px rgba(37,99,235,.28);
            --nav-h: 74px;
            --font: 'Segoe UI', system-ui, -apple-system, 'Helvetica Neue', Arial, 'Nirmala UI', 'Noto Sans Bengali', 'SolaimanLipi', sans-serif;
            --bn-font: 'Nirmala UI', 'Noto Sans Bengali', 'SolaimanLipi', 'Segoe UI', system-ui, sans-serif;
        }

        /* Bengali typography */
        html[lang="bn"] body { font-family: var(--bn-font); line-height: 1.75; }
        html[lang="bn"] h1, html[lang="bn"] h2, html[lang="bn"] h3, html[lang="bn"] h4 { line-height: 1.45; }
        html[lang="bn"] .eyebrow, html[lang="bn"] .badge { letter-spacing: 0; }

        /* Icon sizing helper */
        .bi { line-height: 1; vertical-align: -0.125em; }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a { color: inherit; text-decoration: none; }
        img { max-width: 100%; display: block; }
        ul { list-style: none; }

        h1, h2, h3, h4 { line-height: 1.2; color: var(--secondary); font-weight: 800; letter-spacing: -.02em; }

        /* ===================== LAYOUT ===================== */
        .container { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .container-wide { max-width: 1360px; }
        main { flex: 1; }

        .section { padding: 84px 0; }
        .section-sm { padding: 56px 0; }
        .section-alt { background: var(--white); }

        .section-head { text-align: center; max-width: 720px; margin: 0 auto 48px; }
        .section-head.left { text-align: left; margin-left: 0; }
        .eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: .78rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase;
            color: var(--primary); background: rgba(37,99,235,.08);
            padding: 7px 16px; border-radius: 999px; margin-bottom: 16px;
        }
        .section-title { font-size: clamp(1.7rem, 3.4vw, 2.5rem); margin-bottom: 14px; }
        .section-sub { color: var(--muted); font-size: 1.02rem; }

        /* ===================== GRID ===================== */
        .grid { display: grid; gap: 24px; }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .grid-auto { grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }

        /* ===================== BUTTONS ===================== */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 9px;
            padding: 13px 26px; border-radius: 999px; font-weight: 700; font-size: .92rem;
            border: 1px solid transparent; cursor: pointer; font-family: inherit;
            transition: transform .25s cubic-bezier(.4,0,.2,1), box-shadow .25s ease, background .25s ease, color .25s ease;
            white-space: nowrap;
        }
        .btn-primary { background: var(--gradient); color: #fff; box-shadow: 0 10px 24px -10px rgba(37,99,235,.8); }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 18px 34px -12px rgba(37,99,235,.9); }
        .btn-outline { background: var(--white); color: var(--secondary); border-color: var(--border); }
        .btn-outline:hover { transform: translateY(-3px); border-color: var(--primary); color: var(--primary); box-shadow: var(--shadow); }
        .btn-ghost { background: rgba(255,255,255,.12); color: #fff; border-color: rgba(255,255,255,.28); backdrop-filter: blur(6px); }
        .btn-ghost:hover { background: rgba(255,255,255,.22); transform: translateY(-3px); }
        .btn-sm { padding: 9px 18px; font-size: .82rem; }
        .btn-block { width: 100%; }

        /* ===================== CARDS ===================== */
        .card {
            background: var(--white); border: 1px solid var(--border); border-radius: var(--radius);
            box-shadow: var(--shadow-sm); transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s ease, border-color .3s ease;
            overflow: hidden;
        }
        .card-hover:hover { transform: translateY(-8px); box-shadow: var(--shadow); border-color: rgba(37,99,235,.35); }
        .card-body { padding: 26px; }

        .icon-box {
            width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center;
            background: var(--gradient-soft); color: var(--primary); font-size: 1.5rem; font-weight: 800; margin-bottom: 18px;
        }

        .badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 5px 12px; border-radius: 999px; font-size: .72rem; font-weight: 700;
            background: rgba(37,99,235,.1); color: var(--primary); text-transform: uppercase; letter-spacing: .04em;
        }
        .badge-success { background: rgba(22,163,74,.12); color: var(--success); }
        .badge-warning { background: rgba(245,158,11,.14); color: #B45309; }
        .badge-danger { background: rgba(220,38,38,.12); color: var(--danger); }

        /* ===================== FORMS ===================== */
        .form-group { margin-bottom: 20px; }
        .label { display: block; font-weight: 600; font-size: .85rem; margin-bottom: 8px; color: var(--secondary); }
        .input, .select, .textarea {
            width: 100%; padding: 13px 16px; border: 1.5px solid var(--border); border-radius: var(--radius-sm);
            font-family: inherit; font-size: .93rem; color: var(--text); background: var(--white);
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .input:focus, .select:focus, .textarea:focus {
            outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(37,99,235,.12);
        }
        .textarea { resize: vertical; min-height: 130px; }
        .input.is-invalid, .select.is-invalid, .textarea.is-invalid { border-color: var(--danger); }
        .error-text { color: var(--danger); font-size: .78rem; margin-top: 6px; font-weight: 600; }
        .field-error { border-color: var(--danger) !important; }

        /* ===================== ALERTS ===================== */
        .alert {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 15px 18px; border-radius: var(--radius-sm); margin-bottom: 18px; font-size: .9rem; font-weight: 500;
            animation: alertIn .35s ease;
        }
        .alert-success { background: rgba(22,163,74,.1); color: #15803D; border: 1px solid rgba(22,163,74,.25); }
        .alert-danger { background: rgba(220,38,38,.08); color: #B91C1C; border: 1px solid rgba(220,38,38,.22); }
        .alert-warning { background: rgba(245,158,11,.1); color: #B45309; border: 1px solid rgba(245,158,11,.25); }
        .alert-info { background: rgba(37,99,235,.08); color: #1D4ED8; border: 1px solid rgba(37,99,235,.2); }
        @keyframes alertIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: none; } }

        /* ===================== EMPTY STATE ===================== */
        .empty { text-align: center; padding: 70px 24px; color: var(--muted); }
        .empty-icon {
            width: 76px; height: 76px; margin: 0 auto 18px; border-radius: 50%;
            background: var(--gradient-soft); display: flex; align-items: center; justify-content: center;
            font-size: 2rem; color: var(--primary);
        }
        .empty h3 { margin-bottom: 8px; }
        .empty p { max-width: 420px; margin: 0 auto; }

        /* ===================== PAGINATION ===================== */
        .pg { display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; margin-top: 40px; }
        .pg-item {
            display: grid; place-items: center; min-width: 40px; height: 40px; padding: 0 12px;
            border: 1px solid var(--border); border-radius: 12px; font-size: .86rem; font-weight: 600;
            color: var(--muted); background: var(--white); transition: all .2s ease;
        }
        .pg-item:hover { border-color: var(--primary); color: var(--primary); }
        .pg-active { background: var(--gradient); color: #fff; border-color: transparent; }
        .pg-disabled { opacity: .45; pointer-events: none; }

        /* ===================== MODALS ===================== */
        .modal-backdrop {
            position: fixed; inset: 0; background: rgba(15,23,42,.78); backdrop-filter: blur(6px);
            display: none; align-items: center; justify-content: center; padding: 20px; z-index: 2000;
        }
        .modal-backdrop.open { display: flex; animation: fadeIn .2s ease; }
        .modal-box {
            background: var(--white); border-radius: var(--radius-lg); max-width: 900px; width: 100%;
            max-height: 90vh; overflow: auto; position: relative; animation: modalIn .35s cubic-bezier(.4,0,.2,1);
        }
        .modal-close {
            position: absolute; top: 14px; right: 14px; width: 40px; height: 40px; border-radius: 50%;
            border: none; background: rgba(15,23,42,.6); color: #fff; cursor: pointer; font-size: 1.1rem;
            display: grid; place-items: center; transition: background .2s ease;
        }
        .modal-close:hover { background: var(--danger); }
        @keyframes modalIn { from { opacity: 0; transform: scale(.94) translateY(18px); } to { opacity: 1; transform: none; } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        /* ===================== SCROLL REVEAL ===================== */
        .reveal { opacity: 0; transform: translateY(28px); transition: opacity .7s cubic-bezier(.4,0,.2,1), transform .7s cubic-bezier(.4,0,.2,1); }
        .reveal.visible { opacity: 1; transform: none; }
        @media (prefers-reduced-motion: reduce) {
            .reveal { opacity: 1; transform: none; transition: none; }
            html { scroll-behavior: auto; }
        }

        /* ===================== NAVBAR ===================== */
        .site-nav {
            position: sticky; top: 0; z-index: 1000;
            background: rgba(255,255,255,.82); backdrop-filter: blur(18px) saturate(180%);
            -webkit-backdrop-filter: blur(18px) saturate(180%);
            border-bottom: 1px solid transparent; transition: background .3s ease, border-color .3s ease, box-shadow .3s ease;
        }
        .site-nav.scrolled { background: rgba(255,255,255,.96); border-bottom-color: var(--border); box-shadow: 0 8px 30px -18px rgba(15,23,42,.25); }
        .nav-inner { display: flex; align-items: center; gap: 16px; height: var(--nav-h); }
        .brand { display: flex; align-items: center; gap: 12px; font-weight: 800; font-size: 1.16rem; color: var(--secondary); flex: 0 1 auto; min-width: 0; }
        .brand-mark {
            width: 44px; height: 44px; border-radius: 13px; background: var(--gradient); color: #fff;
            display: grid; place-items: center; font-size: 1.1rem; font-weight: 800;
            box-shadow: 0 10px 22px -10px rgba(37,99,235,.9); overflow: hidden; flex-shrink: 0;
        }
        .brand-mark img { width: 100%; height: 100%; object-fit: cover; }
        .brand-text { display: flex; flex-direction: column; min-width: 0; max-width: 150px; }
        .brand-text small { display: block; font-size: .68rem; font-weight: 600; color: var(--muted); letter-spacing: .06em; text-transform: uppercase; }
        .brand-name, .brand-text small { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .nav-links { display: flex; align-items: center; gap: 2px; margin-left: auto; flex: 0 0 auto; }
        .nav-link {
            position: relative; padding: 8px 8px; border-radius: 10px; font-size: .82rem; font-weight: 600;
            white-space: nowrap; color: var(--muted); transition: color .2s ease, background .2s ease;
        }
        .nav-link:hover { color: var(--primary); background: rgba(37,99,235,.07); }
        .nav-link.active { color: var(--primary); background: rgba(37,99,235,.1); }

        .nav-toggle {
            display: none; width: 46px; height: 46px; border-radius: 12px; border: 1px solid var(--border);
            background: var(--white); cursor: pointer; padding: 0; place-items: center;
        }
        .nav-toggle span { display: block; width: 20px; height: 2px; background: var(--secondary); border-radius: 2px; position: relative; transition: .3s ease; }
        .nav-toggle span::before, .nav-toggle span::after {
            content: ''; position: absolute; left: 0; width: 20px; height: 2px; background: var(--secondary); border-radius: 2px; transition: .3s ease;
        }
        .nav-toggle span::before { top: -6px; }
        .nav-toggle span::after { top: 6px; }
        .nav-toggle.active span { background: transparent; }
        .nav-toggle.active span::before { transform: rotate(45deg); top: 0; }
        .nav-toggle.active span::after { transform: rotate(-45deg); top: 0; }

        .nav-cta { display: flex; align-items: center; gap: 10px; margin-left: 8px; flex-shrink: 0; }
        .nav-cta .btn { padding: 7px 13px; font-size: .78rem; }

        /* Language switcher */
        .lang-switch {
            display: inline-flex; align-items: center; gap: 2px; margin-left: 8px; flex-shrink: 0;
            background: rgba(37,99,235,.07); border: 1px solid var(--border);
            border-radius: 999px; padding: 3px;
        }
        .lang-icon { font-size: .8rem; color: var(--muted); padding: 0 4px 0 7px; }
        .lang-link {
            display: inline-flex; align-items: center; gap: 5px; padding: 5px 9px; border-radius: 999px;
            font-size: .73rem; font-weight: 700; color: var(--muted); transition: all .2s ease; white-space: nowrap;
        }
        .lang-link:hover { color: var(--primary); }
        .lang-link.active { background: var(--gradient); color: #fff; box-shadow: 0 6px 16px -8px rgba(37,99,235,.9); }

        /* Collapse to the drawer before the menu can overflow the bar */
        @media (max-width: 1399px) {
            .nav-links { display: none; }
            .nav-toggle { display: grid; }

            .nav-links.open {
                display: flex; flex-direction: column; align-items: stretch; gap: 4px;
                position: absolute; left: 0; right: 0; top: var(--nav-h);
                background: var(--white); border-bottom: 1px solid var(--border);
                padding: 16px 20px 24px; box-shadow: 0 30px 50px -30px rgba(15,23,42,.4);
                animation: navDrop .28s ease; max-height: calc(100vh - var(--nav-h)); overflow-y: auto;
            }
            .nav-links.open .nav-link { padding: 13px 16px; font-size: .95rem; }
            .nav-links.open .nav-cta { flex-direction: column; align-items: stretch; margin: 10px 0 0; }
            .nav-links.open .nav-cta .btn { width: 100%; padding: 13px 26px; font-size: .92rem; }
            .nav-links.open .lang-switch { margin: 12px 0 0; justify-content: center; }
            .nav-links.open .lang-link { padding: 9px 18px; font-size: .85rem; }
        }
        /* On tablets the full-width drawer would look empty, so anchor it to the right */
        @media (min-width: 761px) and (max-width: 1399px) {
            .nav-links.open {
                left: auto; right: 20px; width: 360px; border: 1px solid var(--border);
                border-top: none; border-radius: 0 0 var(--radius) var(--radius);
            }
        }
        @keyframes navDrop { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: none; } }

        /* ===================== HERO ===================== */
        .hero {
            position: relative; overflow: hidden; color: #fff;
            background: linear-gradient(135deg, #0F172A 0%, #1E3A8A 55%, #2563EB 100%);
        }
        .hero::before, .hero::after {
            content: ''; position: absolute; border-radius: 50%; filter: blur(80px); opacity: .5;
        }
        .hero::before { width: 460px; height: 460px; background: #3B82F6; top: -160px; right: -80px; }
        .hero::after { width: 380px; height: 380px; background: #22D3EE; bottom: -180px; left: -120px; opacity: .28; }
        .hero-grid {
            position: relative; z-index: 1;
            display: grid; grid-template-columns: 1.1fr .9fr; gap: 60px; align-items: center;
            padding: 92px 0 96px;
        }
        .hero h1 { color: #fff; font-size: clamp(2.1rem, 5vw, 3.6rem); margin-bottom: 20px; }
        .hero h1 .accent {
            background: linear-gradient(120deg, #93C5FD, #67E8F9);
            -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
        }
        .hero p.lead { font-size: 1.08rem; color: rgba(255,255,255,.82); max-width: 560px; margin-bottom: 32px; }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 14px; }
        .hero-badges { display: flex; flex-wrap: wrap; gap: 26px; margin-top: 42px; }
        .hero-badge strong { display: block; font-size: 1.75rem; color: #fff; }
        .hero-badge span { font-size: .8rem; color: rgba(255,255,255,.65); text-transform: uppercase; letter-spacing: .08em; }

        .hero-visual { position: relative; }
        .hero-card {
            background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.2);
            backdrop-filter: blur(14px); border-radius: var(--radius-lg); padding: 26px; color: #fff;
        }
        .hero-card-image {
            aspect-ratio: 4/3; border-radius: var(--radius); margin-bottom: 18px; overflow: hidden;
            background:
                radial-gradient(circle at 30% 25%, rgba(255,255,255,.28), transparent 55%),
                linear-gradient(135deg, #2563EB, #0EA5E9 55%, #22D3EE);
            display: grid; place-items: center; font-size: 3.4rem;
        }
        .hero-card-image img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .float-chip {
            position: absolute; background: #fff; color: var(--secondary); border-radius: 14px; padding: 12px 16px;
            box-shadow: var(--shadow-lg); display: flex; align-items: center; gap: 10px; font-size: .82rem; font-weight: 700;
            animation: floaty 5s ease-in-out infinite;
        }
        .float-chip small { display: block; font-weight: 600; color: var(--muted); font-size: .7rem; }
        .float-chip .dot { width: 34px; height: 34px; border-radius: 10px; background: var(--gradient); display: grid; place-items: center; color: #fff; }
        .chip-1 { top: 18px; left: -26px; }
        .chip-2 { bottom: 26px; right: -22px; animation-delay: 1.4s; }
        @keyframes floaty { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }

        /* ===================== TICKER ===================== */
        .ticker {
            background: var(--secondary); color: #fff; display: flex; align-items: center; gap: 18px;
            padding: 12px 0; overflow: hidden;
        }
        .ticker-label {
            flex-shrink: 0; position: relative; z-index: 1; background: var(--danger); color: #fff; font-size: .72rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: .1em; padding: 7px 16px; border-radius: 999px; margin-left: 20px;
            display: inline-flex; align-items: center; gap: 7px;
        }
        .ticker-label .pulse { width: 7px; height: 7px; border-radius: 50%; background: #fff; animation: pulse 1.6s infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: .25; } }
        .ticker-viewport { flex: 1; min-width: 0; overflow: hidden; }
        .ticker-track { display: flex; width: max-content; white-space: nowrap; animation: scrollX 34s linear infinite; }
        .ticker:hover .ticker-track { animation-play-state: paused; }
        /* margin (not gap) keeps -50% exactly one loop long, so the wrap has no jump */
        .ticker-item { font-size: .86rem; font-weight: 500; color: rgba(255,255,255,.88); display: inline-flex; align-items: center; gap: 9px; margin-right: 46px; }
        .ticker-item::before { content: '•'; color: #60A5FA; }
        @keyframes scrollX { from { transform: translateX(0); } to { transform: translateX(-50%); } }

        /* ===================== ABOUT PREVIEW ===================== */
        .about-grid { display: grid; grid-template-columns: 1.02fr .98fr; gap: 64px; align-items: center; }
        .about-main .section-sub { margin-bottom: 30px; }

        .about-pillars { display: flex; flex-direction: column; gap: 14px; margin-bottom: 30px; }
        .about-pillar {
            display: flex; align-items: flex-start; gap: 16px;
            background: var(--white); border: 1px solid var(--border); border-left: 3px solid var(--primary);
            border-radius: var(--radius-sm); padding: 18px 20px; box-shadow: var(--shadow-sm);
            transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s ease, border-color .3s ease;
        }
        .about-pillar:hover { transform: translateX(5px); box-shadow: var(--shadow); }
        .about-pillar-icon {
            flex-shrink: 0; width: 44px; height: 44px; border-radius: 12px; display: grid; place-items: center;
            background: var(--gradient-soft); color: var(--primary); font-size: 1.15rem;
        }
        .about-pillar h3 { font-size: .95rem; margin-bottom: 4px; }
        .about-pillar p { font-size: .85rem; color: var(--muted); }

        .about-actions { display: flex; flex-wrap: wrap; gap: 14px; }

        .about-panel {
            position: relative; overflow: hidden; border-radius: var(--radius-lg); padding: 32px 28px;
            background: linear-gradient(150deg, var(--secondary) 0%, #1E3A8A 100%);
            color: #fff; box-shadow: var(--shadow-lg);
        }
        .about-panel::before {
            content: ''; position: absolute; width: 300px; height: 300px; border-radius: 50%;
            background: rgba(59,130,246,.35); filter: blur(80px); top: -140px; right: -90px;
        }
        .about-panel::after {
            content: ''; position: absolute; width: 220px; height: 220px; border-radius: 50%;
            background: rgba(34,211,238,.22); filter: blur(80px); bottom: -120px; left: -80px;
        }
        .about-panel > * { position: relative; z-index: 1; }
        .about-panel-head { margin-bottom: 20px; }
        .about-panel-head h3 { color: #fff; font-size: 1.06rem; }
        .about-panel-head::after {
            content: ''; display: block; width: 54px; height: 3px; border-radius: 999px; margin-top: 12px;
            background: linear-gradient(90deg, #93C5FD, #67E8F9);
        }

        .about-feats { display: flex; flex-direction: column; }
        .about-feat {
            display: flex; align-items: flex-start; gap: 14px;
            padding: 15px 0; border-top: 1px solid rgba(255,255,255,.12);
            transition: transform .25s ease;
        }
        .about-feat:last-child { border-bottom: 1px solid rgba(255,255,255,.12); }
        .about-feat:hover { transform: translateX(4px); }
        .about-feat-num {
            flex-shrink: 0; font-size: .72rem; font-weight: 800; letter-spacing: .08em; color: #BFDBFE;
            background: rgba(255,255,255,.08); border-radius: 8px; padding: 5px 8px;
            font-variant-numeric: tabular-nums;
        }
        .about-feat-icon { flex-shrink: 0; color: #67E8F9; font-size: 1.05rem; margin-top: 6px; }
        .about-feat h4 { color: #fff; font-size: .93rem; margin-bottom: 3px; }
        .about-feat p { font-size: .8rem; color: rgba(255,255,255,.7); }

        /* ===================== FOOTER ===================== */
        .site-footer { background: var(--secondary); color: rgba(255,255,255,.7); padding-top: 68px; margin-top: auto; }
        .footer-grid { display: grid; grid-template-columns: 1.6fr 1fr 1fr 1.2fr; gap: 40px; padding-bottom: 48px; }
        .footer-brand { display: flex; align-items: center; gap: 12px; margin-bottom: 18px; color: #fff; font-weight: 800; font-size: 1.2rem; }
        .site-footer h4 { color: #fff; font-size: .95rem; margin-bottom: 18px; letter-spacing: .02em; }
        .footer-links li { margin-bottom: 11px; }
        .footer-links a { font-size: .88rem; transition: color .2s ease, padding-left .2s ease; }
        .footer-links a:hover { color: #93C5FD; padding-left: 5px; }
        .footer-contact li { display: flex; gap: 10px; margin-bottom: 13px; font-size: .88rem; }
        .socials { display: flex; gap: 10px; margin-top: 20px; }
        .social {
            width: 40px; height: 40px; border-radius: 12px; display: grid; place-items: center;
            background: rgba(255,255,255,.08); color: #fff; font-size: .85rem; font-weight: 700; transition: all .25s ease;
        }
        .social:hover { background: var(--primary); transform: translateY(-3px); }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.1); padding: 22px 0; font-size: .83rem;
            display: flex; justify-content: space-between; gap: 14px; flex-wrap: wrap;
        }

        /* ===================== PAGE HEADER ===================== */
        .page-head {
            background: linear-gradient(135deg, #0F172A, #1E3A8A); color: #fff; padding: 66px 0 60px; position: relative; overflow: hidden;
        }
        .page-head::after {
            content: ''; position: absolute; width: 420px; height: 420px; border-radius: 50%;
            background: rgba(59,130,246,.35); filter: blur(90px); top: -180px; right: -80px;
        }
        .page-head h1 { color: #fff; font-size: clamp(1.8rem, 4vw, 2.7rem); position: relative; }
        .page-head p { color: rgba(255,255,255,.75); max-width: 640px; margin-top: 12px; position: relative; }
        .crumbs { display: flex; gap: 8px; align-items: center; font-size: .82rem; color: rgba(255,255,255,.6); margin-bottom: 14px; position: relative; }
        .crumbs a:hover { color: #93C5FD; }
        .crumbs .sep { opacity: .5; }

        /* ===================== TABLES (public) ===================== */
        .table-wrap { overflow-x: auto; border: 1px solid var(--border); border-radius: var(--radius); background: var(--white); }
        table.data { width: 100%; border-collapse: collapse; font-size: .89rem; }
        table.data th {
            text-align: left; padding: 14px 18px; background: #F1F5F9; color: var(--muted);
            font-size: .74rem; text-transform: uppercase; letter-spacing: .06em; font-weight: 700; white-space: nowrap;
        }
        table.data td { padding: 14px 18px; border-top: 1px solid var(--border); }
        table.data tbody tr { transition: background .2s ease; }
        table.data tbody tr:hover { background: #F8FAFC; }

        .avatar {
            width: 46px; height: 46px; border-radius: 50%; flex-shrink: 0;
            background: var(--gradient); color: #fff; display: grid; place-items: center; font-weight: 700; font-size: .9rem;
            overflow: hidden;
        }
        .avatar img { width: 100%; height: 100%; object-fit: cover; }

        /* ===================== MISC ===================== */
        .stack { display: flex; flex-direction: column; gap: 14px; }
        .row { display: flex; gap: 14px; align-items: center; }
        .row-between { display: flex; gap: 14px; align-items: center; justify-content: space-between; flex-wrap: wrap; }
        .muted { color: var(--muted); }
        .thumb {
            aspect-ratio: 16/10; background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
            display: grid; place-items: center; color: var(--primary); font-size: 2.2rem; overflow: hidden;
        }
        .thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s cubic-bezier(.4,0,.2,1); }
        .card-hover:hover .thumb img { transform: scale(1.07); }

        .meta { display: flex; flex-wrap: wrap; gap: 16px; color: var(--muted); font-size: .8rem; font-weight: 600; }
        .meta span { display: inline-flex; align-items: center; gap: 6px; }

        .prose { font-size: 1rem; line-height: 1.85; color: #334155; }
        .prose p { margin-bottom: 18px; }
        .prose h2, .prose h3 { margin: 28px 0 14px; }

        /* ===================== RESPONSIVE ===================== */
        @media (max-width: 1024px) {
            .hero-grid { grid-template-columns: 1fr; gap: 46px; padding: 70px 0; }
            .hero-visual { max-width: 520px; }
            .grid-4 { grid-template-columns: repeat(2, minmax(0,1fr)); }
            .grid-3 { grid-template-columns: repeat(2, minmax(0,1fr)); }
            .about-grid { grid-template-columns: 1fr; gap: 44px; }
            .about-side { max-width: 560px; }
            .footer-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 720px) {
            .section { padding: 58px 0; }
            .grid-2, .grid-3, .grid-4, .grid-auto { grid-template-columns: 1fr; }
            .about-pillar { padding: 16px; gap: 13px; }
            .about-actions .btn { width: 100%; }
            .about-panel { padding: 26px 20px; }
            .about-feat { gap: 11px; }
            .footer-grid { grid-template-columns: 1fr; gap: 30px; }
            .float-chip { display: none; }
            .hero-badges { gap: 20px; }
            .footer-bottom { flex-direction: column; text-align: center; }
            .ticker-label { margin-left: 10px; }
        }
    </style>

    @stack('styles')
</head>
<body>
    @include('frontend.partials.navbar')

    <main>
        @include('frontend.partials.flash')
        @yield('content')
    </main>

    @include('frontend.partials.footer')

    {{-- Shared confirmation modal used by destructive actions --}}
    <div class="modal-backdrop" id="confirmModal" role="dialog" aria-modal="true" aria-labelledby="confirmTitle">
        <div class="modal-box" style="max-width:440px;">
            <div style="padding:32px;">
                <div class="empty-icon" style="margin-bottom:16px;background:rgba(220,38,38,.1);color:var(--danger);">!</div>
                <h3 id="confirmTitle" style="text-align:center;margin-bottom:10px;">Are you sure?</h3>
                <p class="muted" style="text-align:center;margin-bottom:24px;" id="confirmText">This action cannot be undone.</p>
                <div class="row" style="justify-content:center;">
                    <button type="button" class="btn btn-outline" data-close>Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmOk" style="background:var(--danger);box-shadow:none;">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ---------- Sticky navbar state ----------
        (function () {
            const nav = document.getElementById('siteNav');
            const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 12);
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });
        })();

        // ---------- Mobile navigation ----------
        (function () {
            const toggle = document.getElementById('navToggle');
            const links = document.getElementById('navLinks');
            if (!toggle || !links) return;

            toggle.addEventListener('click', () => {
                const open = links.classList.toggle('open');
                toggle.classList.toggle('active', open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            });

            document.addEventListener('click', (e) => {
                if (!links.contains(e.target) && !toggle.contains(e.target) && links.classList.contains('open')) {
                    links.classList.remove('open');
                    toggle.classList.remove('active');
                }
            });
        })();

        // ---------- Scroll reveal ----------
        (function () {
            const items = document.querySelectorAll('.reveal');
            if (!items.length) return;

            if (!('IntersectionObserver' in window)) {
                items.forEach(el => el.classList.add('visible'));
                return;
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, i) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => entry.target.classList.add('visible'), i * 70);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px' });

            items.forEach(el => observer.observe(el));
        })();

        // ---------- Animated counters ----------
        (function () {
            const counters = document.querySelectorAll('[data-count]');
            if (!counters.length) return;

            const run = (el) => {
                const target = parseFloat(el.dataset.count);
                const duration = 1600;
                const start = performance.now();
                const suffix = el.dataset.suffix || '';

                const tick = (now) => {
                    const progress = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.floor(eased * target).toLocaleString() + suffix;
                    if (progress < 1) requestAnimationFrame(tick);
                    else el.textContent = target.toLocaleString() + suffix;
                };
                requestAnimationFrame(tick);
            };

            if (!('IntersectionObserver' in window)) { counters.forEach(run); return; }

            const io = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) { run(entry.target); io.unobserve(entry.target); }
                });
            }, { threshold: 0.4 });

            counters.forEach(el => io.observe(el));
        })();

        // ---------- Confirmation modal ----------
        (function () {
            const modal = document.getElementById('confirmModal');
            if (!modal) return;

            const text = document.getElementById('confirmText');
            const ok = document.getElementById('confirmOk');
            let pendingForm = null;

            document.addEventListener('click', (e) => {
                const trigger = e.target.closest('[data-confirm]');
                if (trigger) {
                    e.preventDefault();
                    pendingForm = trigger.closest('form') || document.getElementById(trigger.dataset.form);
                    text.textContent = trigger.dataset.confirm || 'This action cannot be undone.';
                    modal.classList.add('open');
                    return;
                }

                if (e.target.closest('[data-close]') || e.target === modal) {
                    modal.classList.remove('open');
                    pendingForm = null;
                }
            });

            ok.addEventListener('click', () => {
                if (pendingForm) pendingForm.submit();
                modal.classList.remove('open');
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') modal.classList.remove('open');
            });
        })();

        // ---------- Auto-dismiss alerts ----------
        document.querySelectorAll('[data-dismiss]').forEach((el) => {
            setTimeout(() => {
                el.style.transition = 'opacity .4s ease, transform .4s ease';
                el.style.opacity = '0';
                el.style.transform = 'translateY(-8px)';
                setTimeout(() => el.remove(), 400);
            }, 6000);
        });
    </script>

    @stack('scripts')
</body>
</html>
