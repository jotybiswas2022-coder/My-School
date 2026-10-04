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
        .grid-tiles { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 20px; }

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
        .btn-invert { background: var(--white); color: var(--secondary); box-shadow: 0 12px 28px -14px rgba(0,0,0,.65); }
        .btn-invert:hover { transform: translateY(-3px); box-shadow: 0 18px 34px -14px rgba(0,0,0,.75); }
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

        /* ============ TILE (shared feature card) ============ */
        .tile {
            position: relative; overflow: hidden;
            background: var(--white); border: 1px solid var(--border); border-radius: var(--radius);
            padding: 26px 22px; box-shadow: var(--shadow-sm);
            transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s ease, border-color .3s ease;
        }
        .tile::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--primary), #38BDF8);
            transform: scaleX(0); transform-origin: left; transition: transform .35s cubic-bezier(.4,0,.2,1);
        }
        .tile:hover { transform: translateY(-6px); box-shadow: var(--shadow); border-color: rgba(37,99,235,.3); }
        .tile:hover::before { transform: scaleX(1); }
        .tile-top { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px; }
        .tile-num {
            font-size: .74rem; font-weight: 800; letter-spacing: .08em; color: var(--primary);
            background: rgba(37,99,235,.1); border-radius: 8px; padding: 5px 8px;
            font-variant-numeric: tabular-nums;
        }
        .tile-icon { display: block; color: var(--primary); font-size: 1.3rem; }
        .tile-icon-lead { margin-bottom: 13px; }
        .tile h3, .tile h4 { font-size: .98rem; margin-bottom: 7px; }
        .tile p { font-size: .85rem; color: var(--muted); }

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

        /* ===================== HOME GLANCE BAND ===================== */
        .glance {
            position: relative; overflow: hidden;
            background: linear-gradient(140deg, var(--secondary) 0%, #1E3A8A 100%);
            border-radius: var(--radius-lg); padding: 46px 40px; box-shadow: var(--shadow-lg);
        }
        .glance::before {
            content: ''; position: absolute; width: 380px; height: 380px; border-radius: 50%;
            background: rgba(59,130,246,.35); filter: blur(80px); top: -190px; right: -110px;
        }
        .glance::after {
            content: ''; position: absolute; width: 280px; height: 280px; border-radius: 50%;
            background: rgba(34,211,238,.2); filter: blur(80px); bottom: -170px; left: -90px;
        }
        .glance > * { position: relative; z-index: 1; }

        .glance-head {
            display: flex; align-items: flex-end; justify-content: space-between; gap: 34px; flex-wrap: wrap;
            padding-bottom: 28px; border-bottom: 1px solid rgba(255,255,255,.12);
        }
        .glance .eyebrow { background: rgba(255,255,255,.1); color: #BFDBFE; }
        .glance .section-title { color: #fff; margin-bottom: 0; }
        .glance-head-aside { display: flex; flex-direction: column; align-items: flex-start; gap: 14px; max-width: 430px; }
        .glance-sub { color: rgba(255,255,255,.72); font-size: .95rem; }
        .glance-link {
            display: inline-flex; align-items: center; gap: 8px;
            color: #93C5FD; font-weight: 700; font-size: .88rem; transition: color .2s ease;
        }
        .glance-link i { transition: transform .25s ease; }
        .glance-link:hover { color: #fff; }
        .glance-link:hover i { transform: translateX(5px); }

        .glance-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); padding-top: 32px; }
        .glance-stat { padding: 4px 26px; border-left: 1px solid rgba(255,255,255,.12); }
        .glance-stat:first-child { padding-left: 0; border-left: 0; }
        .glance-stat:last-child { padding-right: 0; }
        .glance-stat > i { display: block; color: #67E8F9; font-size: 1.3rem; margin-bottom: 16px; }
        .glance-value {
            font-size: clamp(2.1rem, 3.4vw, 2.7rem); font-weight: 800; color: #fff; line-height: 1;
            letter-spacing: -.03em; font-variant-numeric: tabular-nums; margin-bottom: 8px;
        }
        .glance-suffix { color: #93C5FD; }
        .glance-label { display: block; color: rgba(255,255,255,.68); font-size: .82rem; font-weight: 600; }

        /* ===================== ACADEMIC PROGRAMS ===================== */
        .prog {
            position: relative; overflow: hidden;
            display: flex; flex-direction: column;
            background: var(--white); border: 1px solid var(--border); border-radius: var(--radius);
            padding: 26px 24px 22px; box-shadow: var(--shadow-sm);
            transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s ease, border-color .3s ease;
        }
        .prog::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--primary), #38BDF8);
            transform: scaleX(0); transform-origin: left; transition: transform .35s cubic-bezier(.4,0,.2,1);
        }
        .prog:hover, .prog:focus-visible { transform: translateY(-6px); box-shadow: var(--shadow); border-color: rgba(37,99,235,.3); }
        .prog:hover::before, .prog:focus-visible::before { transform: scaleX(1); }

        .prog-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
        .prog-initial {
            width: 48px; height: 48px; border-radius: 14px; display: grid; place-items: center;
            background: var(--gradient); color: #fff; font-size: 1.2rem; font-weight: 800;
            box-shadow: 0 10px 22px -12px rgba(37,99,235,.9);
        }
        .prog-num {
            font-size: .74rem; font-weight: 800; letter-spacing: .08em; color: var(--primary);
            background: rgba(37,99,235,.1); border-radius: 8px; padding: 5px 8px;
            font-variant-numeric: tabular-nums;
        }
        .prog h3 { font-size: 1.06rem; margin-bottom: 8px; }
        .prog p { font-size: .87rem; color: var(--muted); }

        /* margin-top:auto keeps the footer row aligned across cards of unequal text length */
        .prog-foot {
            margin-top: auto; display: flex; align-items: center; justify-content: space-between; gap: 12px;
            padding-top: 16px; border-top: 1px solid var(--border);
        }
        .prog-meta { display: inline-flex; align-items: center; gap: 8px; font-size: .8rem; font-weight: 600; color: var(--muted); }
        .prog-go {
            flex-shrink: 0; width: 34px; height: 34px; border-radius: 50%; display: grid; place-items: center;
            background: rgba(37,99,235,.08); color: var(--primary); font-size: .85rem;
            transition: background .25s ease, color .25s ease, transform .25s ease;
        }
        .prog:hover .prog-go { background: var(--primary); color: #fff; transform: translateX(4px); }

        .prog-all {
            display: flex; align-items: center; justify-content: center; gap: 9px;
            width: fit-content; margin: 34px auto 0; padding: 13px 26px; border-radius: 999px;
            background: var(--white); border: 1px solid var(--border); box-shadow: var(--shadow-sm);
            font-weight: 700; font-size: .9rem;
            transition: transform .25s ease, border-color .25s ease, color .25s ease, box-shadow .25s ease;
        }
        .prog-all:hover { transform: translateY(-3px); border-color: var(--primary); color: var(--primary); box-shadow: var(--shadow); }
        .prog-all i { transition: transform .25s ease; }
        .prog-all:hover i { transform: translateX(4px); }


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

        /* ===================== ABOUT PAGE ===================== */
        .about-page .section-title { text-wrap: balance; }
        .about-page .section-head { margin-bottom: 38px; }

        .about-story { display: grid; grid-template-columns: 1.1fr .9fr; gap: 60px; align-items: center; }
        .about-stats {
            position: relative; overflow: hidden; border-radius: var(--radius-lg); padding: 10px 28px;
            background: linear-gradient(160deg, var(--secondary) 0%, #1E3A8A 100%);
            box-shadow: var(--shadow-lg);
        }
        .about-stats::before {
            content: ''; position: absolute; width: 260px; height: 260px; border-radius: 50%;
            background: rgba(59,130,246,.35); filter: blur(80px); top: -130px; right: -80px;
        }
        .about-stat {
            position: relative; z-index: 1;
            display: flex; align-items: center; gap: 16px; padding: 20px 0;
            border-bottom: 1px solid rgba(255,255,255,.12);
        }
        .about-stat:last-child { border-bottom: 0; }
        .about-stat-icon {
            flex-shrink: 0; width: 44px; height: 44px; border-radius: 12px; display: grid; place-items: center;
            background: rgba(255,255,255,.1); color: #67E8F9; font-size: 1.15rem;
        }
        .about-stat-value {
            font-size: 1.85rem; font-weight: 800; color: #fff; line-height: 1;
            font-variant-numeric: tabular-nums; letter-spacing: -.02em;
        }
        .about-stat-label {
            font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em;
            color: rgba(255,255,255,.65); margin-left: auto; text-align: right;
        }

        .about-pillars-wide { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px; margin-bottom: 0; }
        .about-pillars-wide .about-pillar { height: 100%; }

        .about-why {
            display: grid; grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0 44px; max-width: 980px; margin: 0 auto;
        }
        .about-why-item {
            display: flex; align-items: flex-start; gap: 14px;
            padding: 20px 0; border-top: 1px solid var(--border);
        }
        .about-why-check {
            flex-shrink: 0; width: 26px; height: 26px; border-radius: 50%; display: grid; place-items: center;
            background: rgba(22,163,74,.12); color: var(--success); font-size: .85rem;
        }
        .about-why-item h4 { font-size: .95rem; margin-bottom: 4px; }
        .about-why-item p { font-size: .85rem; color: var(--muted); }

        .about-quote {
            display: grid; grid-template-columns: 1.35fr .65fr; gap: 44px; align-items: center;
            background: var(--gradient-soft); border: 1px solid rgba(37,99,235,.2);
            border-radius: var(--radius-lg); padding: 40px 36px;
        }
        .about-quote .section-title { font-size: clamp(1.4rem, 2.4vw, 1.85rem); margin-bottom: 12px; }
        .about-quote .muted { margin-bottom: 22px; }
        .about-quote-person { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 4px; }
        .about-quote-avatar {
            width: 108px; height: 108px; border-radius: 50%; overflow: hidden; margin-bottom: 12px;
            background: var(--gradient); color: #fff; font-size: 2rem;
            display: grid; place-items: center; box-shadow: var(--shadow);
        }
        .about-quote-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .about-quote-person .muted { font-size: .84rem; margin: 0; }

        /* ===================== PRINCIPAL MESSAGE ===================== */
        .principal {
            display: grid; grid-template-columns: auto minmax(0, 1fr); gap: 48px; align-items: center;
            background: linear-gradient(150deg, rgba(37,99,235,.07), rgba(37,99,235,.02));
            border: 1px solid rgba(37,99,235,.18); border-radius: var(--radius-lg);
            padding: 44px 42px; box-shadow: var(--shadow-sm);
        }

        /* justify-self keeps the column shrink-wrapped to the portrait, so the offset
           frame below stays glued to it when the grid collapses to one column */
        .principal-media { position: relative; flex-shrink: 0; justify-self: start; }
        /* offset outline sitting behind the portrait */
        .principal-frame {
            position: absolute; inset: 14px -14px -14px 14px;
            border: 2px solid rgba(37,99,235,.28); border-radius: 20px;
        }
        .principal-photo {
            position: relative; width: 268px; max-width: 100%; aspect-ratio: 1/1; overflow: hidden;
            border-radius: 20px; background: var(--gradient); color: #fff; font-size: 3.4rem;
            display: grid; place-items: center; box-shadow: var(--shadow-lg);
        }
        .principal-photo img { width: 100%; height: 100%; object-fit: cover; }

        .principal-body { min-width: 0; }
        .principal-title { font-size: clamp(1.5rem, 2.6vw, 2rem); margin-bottom: 16px; }

        .principal-figure { margin: 0; }
        .principal-quote {
            position: relative; font-style: italic; font-weight: 700;
            font-size: 1.06rem; line-height: 1.55; color: var(--secondary);
            padding-left: 36px; margin-bottom: 16px;
        }
        .principal-quote::before {
            content: '\201C'; position: absolute; left: 0; top: -16px;
            font-size: 2.8rem; line-height: 1; font-style: normal; color: var(--primary); opacity: .32;
        }
        .principal-text { font-size: .92rem; color: var(--muted); margin-bottom: 24px; }

        .principal-sign {
            display: flex; align-items: center; justify-content: space-between; gap: 18px; flex-wrap: wrap;
            padding-top: 20px; border-top: 1px solid rgba(37,99,235,.16);
        }
        .principal-sign strong { display: block; color: var(--secondary); font-size: .98rem; }
        .principal-sign span { color: var(--muted); font-size: .84rem; font-weight: 600; }
        .principal-link {
            display: inline-flex; align-items: center; gap: 8px;
            color: var(--primary); font-weight: 700; font-size: .88rem; transition: color .2s ease;
        }
        .principal-link i { transition: transform .25s ease; }
        .principal-link:hover { color: var(--primary-dark); }
        .principal-link:hover i { transform: translateX(5px); }

        /* ===================== CLASSES PAGE ===================== */
        .cls-hint {
            display: inline-flex; align-items: center; gap: 9px; margin-bottom: 22px;
            padding: 10px 16px; border-radius: 999px;
            background: rgba(37,99,235,.07); border: 1px solid rgba(37,99,235,.16);
            color: var(--primary-dark); font-size: .86rem; font-weight: 600;
        }
        .cls-hint i { font-size: .8rem; }

        .cls-list { display: flex; flex-direction: column; gap: 12px; }
        .cls {
            background: var(--white); border: 1px solid var(--border); border-radius: var(--radius);
            box-shadow: var(--shadow-sm); overflow: hidden;
            transition: box-shadow .3s ease, border-color .3s ease;
        }
        .cls[open] { box-shadow: var(--shadow); border-color: rgba(37,99,235,.28); }
        .cls:hover { border-color: rgba(37,99,235,.25); }

        .cls-head {
            display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
            padding: 18px 22px; cursor: pointer; list-style: none;
            transition: background .2s ease;
        }
        .cls-head::-webkit-details-marker { display: none; }
        .cls-head:hover { background: rgba(37,99,235,.03); }
        .cls-head:focus-visible { outline: 2px solid var(--primary); outline-offset: -2px; }

        .cls-initial {
            flex-shrink: 0; width: 46px; height: 46px; border-radius: 13px; display: grid; place-items: center;
            background: var(--gradient); color: #fff; font-size: 1.15rem; font-weight: 800;
            box-shadow: 0 10px 22px -12px rgba(37,99,235,.9);
        }
        .cls-title { min-width: 0; }
        .cls-title h3 { font-size: 1.04rem; margin-bottom: 3px; }
        .cls-meta { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; color: var(--muted); font-size: .8rem; font-weight: 600; }
        .cls-dot { width: 3px; height: 3px; border-radius: 50%; background: var(--border); flex-shrink: 0; }

        .cls-code {
            margin-left: auto; flex-shrink: 0; padding: 6px 12px; border-radius: 10px;
            background: var(--gradient-soft); border: 1px solid rgba(37,99,235,.18);
            color: var(--primary-dark); font-size: .74rem; font-weight: 800; letter-spacing: .06em;
        }
        .cls-caret {
            flex-shrink: 0; color: var(--muted); font-size: .95rem;
            transition: transform .3s cubic-bezier(.4,0,.2,1), color .2s ease;
        }
        .cls[open] .cls-caret { transform: rotate(180deg); color: var(--primary); }

        .cls-body { padding: 0 22px 22px; border-top: 1px solid var(--border); }
        .cls-desc { font-size: .89rem; color: var(--muted); padding-top: 18px; margin-bottom: 20px; }

        .cls-group + .cls-group { margin-top: 20px; }
        .cls-label {
            display: block; margin-bottom: 10px;
            font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; color: var(--muted);
        }
        .cls-chips { display: flex; flex-wrap: wrap; gap: 8px; }
        .cls-chip {
            display: inline-flex; align-items: center; padding: 6px 13px; border-radius: 999px;
            background: rgba(37,99,235,.08); color: var(--primary-dark); font-size: .78rem; font-weight: 700;
        }
        .cls-chip-subject { background: rgba(22,163,74,.1); color: #15803D; }
        .cls-empty { font-size: .84rem; }

        /* ===================== FACILITIES PAGE ===================== */
        .fac-group + .fac-group { margin-top: 46px; }
        .fac-group-title { display: flex; align-items: center; gap: 12px; font-size: 1.05rem; margin-bottom: 20px; }
        .fac-group-title::before {
            content: ''; flex-shrink: 0; width: 4px; height: 22px; border-radius: 999px; background: var(--gradient);
        }

        .fac-cta {
            position: relative; overflow: hidden;
            display: flex; align-items: center; justify-content: space-between; gap: 28px; flex-wrap: wrap;
            background: linear-gradient(140deg, var(--secondary) 0%, #1E3A8A 100%);
            border-radius: var(--radius-lg); padding: 40px 36px; box-shadow: var(--shadow-lg);
        }
        .fac-cta::before {
            content: ''; position: absolute; width: 320px; height: 320px; border-radius: 50%;
            background: rgba(59,130,246,.35); filter: blur(80px); top: -150px; right: -90px;
        }
        .fac-cta::after {
            content: ''; position: absolute; width: 240px; height: 240px; border-radius: 50%;
            background: rgba(34,211,238,.2); filter: blur(80px); bottom: -140px; left: -70px;
        }
        .fac-cta > * { position: relative; z-index: 1; }
        .fac-cta h2 { color: #fff; font-size: clamp(1.35rem, 2.3vw, 1.8rem); margin-bottom: 8px; text-wrap: balance; }
        .fac-cta p { color: rgba(255,255,255,.75); font-size: .95rem; max-width: 520px; }
        .fac-cta .btn-ghost { border-color: rgba(255,255,255,.35); }
        .fac-cta-actions { display: flex; gap: 12px; flex-wrap: wrap; }

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

        /* ===================== PRINCIPAL PAGE ===================== */
        /* Profile column stays put while the letter scrolls on desktop. */
        .principal-page { display: grid; grid-template-columns: 300px minmax(0,1fr); gap: 52px; align-items: start; }
        .principal-side { position: sticky; top: calc(var(--nav-h) + 26px); }
        .principal-side-inner {
            background: var(--white); border: 1px solid var(--border); border-radius: var(--radius-lg);
            padding: 34px 28px 30px; text-align: center; box-shadow: var(--shadow);
        }
        .principal-side .principal-media { margin: 0 auto 24px; }
        .principal-side .principal-photo { width: 168px; }
        .principal-name { font-size: 1.24rem; margin-bottom: 6px; }
        .principal-role { display: block; color: var(--primary); font-weight: 700; font-size: .88rem; }
        .principal-mail {
            display: inline-flex; align-items: center; gap: 9px; margin-top: 20px;
            padding: 11px 20px; border-radius: 999px; background: rgba(37,99,235,.09);
            color: var(--primary-dark); font-weight: 700; font-size: .84rem; word-break: break-all;
            transition: background .2s, color .2s, transform .2s;
        }
        .principal-mail:hover { background: var(--primary); color: var(--white); transform: translateY(-2px); }

        /* The letter is the point of the page, so it gets the reading measure and a drop cap. */
        .principal-letter {
            background: var(--white); border: 1px solid var(--border); border-radius: var(--radius-lg);
            padding: 40px 40px 36px; box-shadow: var(--shadow-sm);
        }
        .principal-letter-title { font-size: clamp(1.32rem, 2.4vw, 1.75rem); margin-bottom: 20px; }
        .principal-letter .prose { max-width: 68ch; }
        .principal-letter .prose > p:first-child::first-letter {
            float: left; font-size: 3.3rem; line-height: .84; font-weight: 800;
            padding: 6px 12px 0 0; color: var(--primary);
        }
        .principal-signoff { margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border); }
        .principal-signoff strong { display: block; font-size: 1.02rem; color: var(--secondary); }
        .principal-signoff span { font-size: .84rem; color: var(--muted); }
        .principal-values { margin-top: 34px; }
        .principal-values-head { max-width: 640px; margin-bottom: 26px; }
        .principal-values-head h2 { font-size: clamp(1.22rem, 2.2vw, 1.55rem); margin-bottom: 8px; }

        /* ===================== FACULTY (home) ===================== */
        /* One lead profile beside compact rows: keeps four people from reading as a flat
           roster, and stays far shorter on phones than four stacked cards. */
        .faculty { display: grid; grid-template-columns: 1.05fr .95fr; gap: 24px; }
        .faculty-solo { grid-template-columns: 1fr; max-width: 520px; margin-inline: auto; }

        .faculty-lead {
            display: flex; flex-direction: column; overflow: hidden;
            background: var(--white); border: 1px solid var(--border); border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s ease, border-color .3s ease;
        }
        .faculty-lead:hover { transform: translateY(-6px); box-shadow: var(--shadow); border-color: rgba(37,99,235,.3); }
        .faculty-lead-photo {
            position: relative; aspect-ratio: 4/3; overflow: hidden;
            background: linear-gradient(135deg, #DBEAFE, #BFDBFE); color: var(--primary);
            display: grid; place-items: center; font-size: 3rem; font-weight: 800;
        }
        .faculty-lead-photo img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s cubic-bezier(.4,0,.2,1); }
        .faculty-lead:hover .faculty-lead-photo img { transform: scale(1.06); }
        /* shared department badge, sits over a portrait on the homepage and in the directory */
        .dept-chip {
            position: absolute; left: 16px; bottom: 16px; max-width: calc(100% - 32px);
            padding: 6px 13px; border-radius: 999px;
            background: rgba(15,23,42,.72); color: #fff;
            font-size: .72rem; font-weight: 700;
        }
        .faculty-lead-body { padding: 24px 26px 26px; display: flex; flex-direction: column; flex: 1; }
        .faculty-lead-body h3 { font-size: 1.18rem; margin-bottom: 5px; }
        .faculty-role { display: block; color: var(--primary); font-weight: 700; font-size: .88rem; }
        .faculty-lead-meta { color: var(--muted); font-size: .8rem; margin-top: 8px; }
        .faculty-go {
            margin-top: auto; padding-top: 18px;
            display: inline-flex; align-items: center; gap: 8px;
            color: var(--primary); font-weight: 700; font-size: .88rem;
        }
        .faculty-go i { transition: transform .25s ease; }
        .faculty-lead:hover .faculty-go i { transform: translateX(5px); }

        .faculty-list { display: flex; flex-direction: column; gap: 14px; }
        .faculty-row {
            flex: 1; display: flex; align-items: center; gap: 16px;
            padding: 16px 20px; background: var(--white);
            border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow-sm);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }
        .faculty-row:hover { transform: translateX(6px); box-shadow: var(--shadow); border-color: rgba(37,99,235,.28); }
        .faculty-row .avatar { width: 54px; height: 54px; font-size: 1.05rem; }
        .faculty-row-text { min-width: 0; }
        .faculty-row-text strong { display: block; font-size: .98rem; margin-bottom: 2px; }
        .faculty-row-text span { display: block; color: var(--muted); font-size: .8rem; }
        .faculty-row-go {
            margin-left: auto; flex-shrink: 0; color: var(--primary); opacity: .5;
            transition: transform .25s ease, opacity .25s ease;
        }
        .faculty-row:hover .faculty-row-go { transform: translateX(4px); opacity: 1; }

        /* ===================== TEACHERS PAGE ===================== */
        /* Directory view, so a real card grid stays right here; only the card itself changes. */
        .tbar {
            display: grid; grid-template-columns: 2fr 1.4fr auto; gap: 14px; align-items: end;
            padding: 20px 22px; margin-bottom: 30px;
            background: var(--white); border: 1px solid var(--border); border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
        }
        .tbar-count {
            grid-column: 1 / -1;
            display: flex; align-items: center; gap: 9px;
            margin-top: 2px; padding-top: 14px; border-top: 1px solid var(--border);
            color: var(--muted); font-size: .84rem; font-weight: 600; margin-bottom: 0;
        }
        .tbar-count strong { color: var(--secondary); }

        .tcard {
            display: flex; flex-direction: column; height: 100%; overflow: hidden;
            background: var(--white); border: 1px solid var(--border); border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s ease, border-color .3s ease;
        }
        .tcard:hover { transform: translateY(-6px); box-shadow: var(--shadow); border-color: rgba(37,99,235,.3); }
        .tcard-media {
            position: relative; aspect-ratio: 16/10; overflow: hidden;
            background: linear-gradient(135deg, #DBEAFE, #BFDBFE); color: var(--primary);
            display: grid; place-items: center; font-size: 2.4rem; font-weight: 800;
        }
        .tcard-media img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s cubic-bezier(.4,0,.2,1); }
        .tcard:hover .tcard-media img { transform: scale(1.06); }
        .tcard-body { padding: 20px 22px 22px; display: flex; flex-direction: column; flex: 1; }
        .tcard-body h2 { font-size: 1.06rem; margin-bottom: 5px; }
        .tcard-role { display: block; color: var(--primary); font-weight: 700; font-size: .85rem; }
        .tcard-qual { color: var(--muted); font-size: .79rem; margin-top: 9px; }
        .tcard-go {
            margin-top: auto; padding-top: 16px;
            display: inline-flex; align-items: center; gap: 8px;
            color: var(--primary); font-weight: 700; font-size: .85rem;
        }
        .tcard-go i { transition: transform .25s ease; }
        .tcard:hover .tcard-go i { transform: translateX(5px); }

        /* ===================== TEACHER PROFILE ===================== */
        /* The page header already prints the name as the <h1>, so the sidebar carries the
           portrait, the role and the actions instead of repeating it. */
        .tprofile { display: grid; grid-template-columns: 330px minmax(0,1fr); gap: 44px; align-items: start; }
        .tprofile-side { position: sticky; top: calc(var(--nav-h) + 26px); }
        .tprofile-card {
            background: var(--white); border: 1px solid var(--border); border-radius: var(--radius-lg);
            padding: 30px 26px; text-align: center; box-shadow: var(--shadow);
        }
        .tprofile-card .principal-media { width: 190px; margin: 0 auto 22px; }
        .tprofile-card .principal-photo { width: 190px; }
        .tprofile-role { display: block; color: var(--primary); font-weight: 700; font-size: .9rem; }
        .tprofile-actions { display: flex; flex-direction: column; gap: 10px; margin-top: 20px; }
        .tprofile-actions .btn { width: 100%; justify-content: center; }
        .tprofile-meta {
            display: flex; flex-direction: column; gap: 15px; text-align: left;
            margin-top: 24px; padding-top: 22px; border-top: 1px solid var(--border);
        }
        .tprofile-meta dt {
            font-size: .72rem; text-transform: uppercase; letter-spacing: .07em;
            font-weight: 700; color: var(--muted); margin-bottom: 3px;
        }
        .tprofile-meta dd { margin: 0; font-weight: 600; font-size: .88rem; }

        .tpanel {
            background: var(--white); border: 1px solid var(--border); border-radius: var(--radius-lg);
            padding: 32px 34px; box-shadow: var(--shadow-sm);
        }
        .tpanel + .tpanel { margin-top: 26px; }
        .tpanel-head { display: flex; align-items: center; gap: 13px; margin-bottom: 18px; }
        .tpanel-head h2 { font-size: 1.14rem; }
        .tpanel-icon {
            flex-shrink: 0; width: 40px; height: 40px; border-radius: 12px; display: grid; place-items: center;
            background: rgba(37,99,235,.09); color: var(--primary); font-size: 1.05rem;
        }

        .tsubjects { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 12px; }
        .tsubject {
            display: flex; flex-direction: column; gap: 3px; padding: 15px 18px;
            background: var(--white); border: 1px solid var(--border); border-radius: var(--radius);
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }
        .tsubject:hover { transform: translateY(-2px); box-shadow: var(--shadow-sm); border-color: rgba(37,99,235,.28); }
        .tsubject strong { font-size: .94rem; }
        .tsubject span { color: var(--muted); font-size: .78rem; }
        .empty-sm { padding: 30px 20px; }

        /* ===================== NOTICES (home) ===================== */
        /* Notices are dated documents, so a calendar block per row beats a card grid and
           keeps the section short on phones. */
        .sechead { display: flex; align-items: flex-end; justify-content: space-between; gap: 24px; flex-wrap: wrap; margin-bottom: 30px; }
        .sechead h2 { margin-bottom: 0; }
        .sechead-all {
            flex-shrink: 0; display: inline-flex; align-items: center; gap: 8px;
            color: var(--primary); font-weight: 700; font-size: .9rem; transition: color .2s ease;
        }
        .sechead-all i { transition: transform .25s ease; }
        .sechead-all:hover { color: var(--primary-dark); }
        .sechead-all:hover i { transform: translateX(5px); }

        .nlist { display: flex; flex-direction: column; gap: 12px; }
        .nrow {
            display: flex; align-items: center; gap: 22px; padding: 18px 22px;
            background: var(--white); border: 1px solid var(--border);
            border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }
        .nrow:hover { transform: translateX(6px); box-shadow: var(--shadow); border-color: rgba(37,99,235,.28); }

        .ndate {
            flex-shrink: 0; width: 62px; height: 62px; border-radius: 16px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            background: var(--gradient-soft); border: 1px solid rgba(37,99,235,.18);
        }
        .ndate-day { font-size: 1.28rem; font-weight: 800; line-height: 1; color: var(--secondary); font-variant-numeric: tabular-nums; }
        .ndate-mon { font-size: .68rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; color: var(--primary); }

        .nrow-main { flex: 1; min-width: 0; }
        .nrow-top { display: flex; align-items: center; gap: 12px; margin-bottom: 7px; }
        .nrow-title { font-size: 1.02rem; margin-bottom: 5px; text-wrap: balance; }
        /* clamp so one long notice cannot stretch its row past the others */
        .nrow-desc {
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
            color: var(--muted); font-size: .85rem;
        }
        .nrow-go { flex-shrink: 0; color: var(--primary); opacity: .5; transition: transform .25s ease, opacity .25s ease; }
        .nrow:hover .nrow-go { transform: translateX(4px); opacity: 1; }

        /* ===================== EVENTS (home) ===================== */
        /* Upcoming events read as a schedule, not a card grid: a date rail runs down the
           left and the logistics that actually matter (time, place) become chips. */
        .elist { position: relative; display: flex; flex-direction: column; gap: 14px; }
        /* the rail only shows through the gaps, so the blocks read as points on a timeline */
        .elist::before {
            content: ""; position: absolute; left: 31px; top: 16px; bottom: 16px; width: 2px;
            border-radius: 2px; background: linear-gradient(180deg, rgba(37,99,235,.3), rgba(37,99,235,.05));
        }

        .erow {
            position: relative; display: flex; align-items: center; gap: 22px; padding: 18px 22px;
            background: var(--white); border: 1px solid var(--border);
            border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }
        .erow:hover { transform: translateX(6px); box-shadow: var(--shadow); border-color: rgba(37,99,235,.28); }

        .edate {
            flex-shrink: 0; width: 64px; padding: 9px 0; border-radius: 16px;
            display: flex; flex-direction: column; align-items: center; gap: 1px;
            background: var(--gradient-soft); border: 1px solid rgba(37,99,235,.18);
        }
        .edate-week { font-size: .6rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; color: var(--primary); }
        .edate-day { font-size: 1.3rem; font-weight: 800; line-height: 1.05; color: var(--secondary); font-variant-numeric: tabular-nums; }
        .edate-mon { font-size: .62rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; color: var(--muted); }

        .erow-main { flex: 1; min-width: 0; }
        .erow-title { font-size: 1.04rem; margin-bottom: 9px; text-wrap: balance; }
        .echips { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 8px; }
        .echip {
            display: inline-flex; align-items: center; gap: 6px; padding: 4px 11px; border-radius: 999px;
            background: rgba(37,99,235,.07); color: var(--secondary); font-size: .76rem; font-weight: 700;
        }
        .echip i { font-size: .74rem; color: var(--primary); }
        /* clamp so one wordy event cannot stretch its row past the others */
        .erow-desc {
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
            color: var(--muted); font-size: .85rem;
        }
        .erow-go { flex-shrink: 0; color: var(--primary); opacity: .5; transition: transform .25s ease, opacity .25s ease; }
        .erow:hover .erow-go { transform: translateX(4px); opacity: 1; }

        /* ===================== NEWS (home) ===================== */
        /* Articles do have cover images, so the newest one leads as a wide feature and the
           rest shrink to thumbnails instead of three identical cards. */
        .nwslead {
            display: grid; grid-template-columns: 1.15fr 1fr; overflow: hidden; background: var(--white);
            border: 1px solid var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }
        .nwslead:hover { transform: translateY(-4px); box-shadow: var(--shadow); border-color: rgba(37,99,235,.28); }
        .nwslead-media {
            position: relative; min-height: 260px; display: grid; place-items: center;
            font-size: 3rem; color: var(--primary); background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
        }
        .nwslead-media img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
        .nwslead-badge { position: absolute; top: 16px; left: 16px; z-index: 1; background: rgba(255,255,255,.92); }
        .nwslead-body { display: flex; flex-direction: column; justify-content: center; padding: 30px 32px; }
        .nwslead-date { display: block; font-size: .78rem; font-weight: 700; color: var(--primary); margin-bottom: 10px; }
        .nwslead-title { font-size: clamp(1.18rem, 1.9vw, 1.5rem); margin-bottom: 12px; text-wrap: balance; }
        .nwslead-desc {
            display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
            color: var(--muted); font-size: .9rem; margin-bottom: 18px;
        }
        .nwslead-go { display: inline-flex; align-items: center; gap: 8px; color: var(--primary); font-weight: 700; font-size: .88rem; }
        .nwslead-go i { transition: transform .25s ease; }
        .nwslead:hover .nwslead-go i { transform: translateX(5px); }

        .nwsgrid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-top: 16px; }
        .nwsmini {
            display: grid; grid-template-columns: 104px minmax(0, 1fr); gap: 14px; align-items: center;
            padding: 12px; background: var(--white); border: 1px solid var(--border);
            border-radius: var(--radius); box-shadow: var(--shadow-sm);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }
        .nwsmini:hover { transform: translateY(-3px); box-shadow: var(--shadow); border-color: rgba(37,99,235,.28); }
        .nwsmini-media {
            position: relative; aspect-ratio: 1; overflow: hidden; border-radius: var(--radius-sm);
            display: grid; place-items: center; font-size: 1.4rem; color: var(--primary);
            background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
        }
        .nwsmini-media img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
        .nwsmini-body { min-width: 0; }
        .nwsmini-top { display: flex; align-items: center; gap: 10px; margin-bottom: 6px; }
        .nwsmini-top time { font-size: .74rem; font-weight: 700; color: var(--muted); white-space: nowrap; }
        /* clamp so one long headline cannot stretch its card past the other */
        .nwsmini-title { font-size: .95rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

        /* ===================== RESPONSIVE ===================== */
        @media (max-width: 1024px) {
            .hero-grid { grid-template-columns: 1fr; gap: 46px; padding: 70px 0; }
            .hero-visual { max-width: 520px; }
            .grid-4 { grid-template-columns: repeat(2, minmax(0,1fr)); }
            .grid-3 { grid-template-columns: repeat(2, minmax(0,1fr)); }
            .about-grid { grid-template-columns: 1fr; gap: 44px; }
            .about-side { max-width: 560px; }
            .about-story { grid-template-columns: 1fr; gap: 40px; }
            .about-stats { max-width: 520px; }
            .grid-tiles { grid-template-columns: repeat(2, minmax(0,1fr)); }
            .about-quote { grid-template-columns: 1fr; gap: 30px; padding: 32px 28px; }
            .about-quote-person { order: -1; }
            .principal-page { grid-template-columns: 1fr; gap: 30px; }
            .principal-side { position: static; }
            .principal-side-inner { display: grid; grid-template-columns: auto minmax(0,1fr); gap: 0 28px; text-align: left; align-items: center; padding: 28px 30px; }
            .principal-side .principal-media { margin: 0; grid-row: span 2; }
            .principal-side .principal-photo { width: 140px; }
            .principal-mail { margin-top: 14px; }
            .faculty { grid-template-columns: 1fr; gap: 20px; }
            /* the lead profile turns on its side, which costs far less height than stacking */
            .faculty-lead { flex-direction: row; }
            .faculty-lead-photo { width: 250px; aspect-ratio: auto; flex-shrink: 0; font-size: 2rem; }
            .faculty-lead-body { padding: 22px 24px; }
            .tbar { grid-template-columns: 1fr 1fr; }
            .tbar-actions { grid-column: 1 / -1; }
            .tprofile { grid-template-columns: 1fr; gap: 26px; }
            .tprofile-side { position: static; }
            /* the card lies on its side, so the portrait does not push the whole page down */
            .tprofile-card {
                display: grid; grid-template-columns: auto minmax(0,1fr); gap: 0 26px;
                text-align: left; align-items: center; padding: 26px 28px;
            }
            .tprofile-card .principal-media { grid-row: span 2; width: 150px; margin-bottom: 0; }
            .tprofile-card .principal-photo { width: 150px; }
            .tprofile-actions { flex-direction: row; margin-top: 12px; }
            .tprofile-actions .btn { width: auto; }
            .tprofile-meta { grid-column: 1 / -1; margin-top: 20px; padding-top: 20px; }
            .sechead { margin-bottom: 26px; }
            .erow { gap: 18px; padding: 16px 18px; }
            .nwslead-media { min-height: 240px; }
            .nwslead-body { padding: 26px; }
            .fac-cta { padding: 32px 28px; }
            .cls-head { padding: 16px 18px; }
            .cls-body { padding: 0 18px 20px; }
            .principal { gap: 36px; padding: 34px 30px; }
            .principal-photo { width: 216px; }
            .glance { padding: 38px 28px; }
            .glance-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 28px 0; }
            .glance-stat:nth-child(odd) { padding-left: 0; border-left: 0; }
            .footer-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 720px) {
            .principal-side-inner { grid-template-columns: 1fr; text-align: center; padding: 26px 22px; justify-items: center; }
            .principal-side .principal-media { grid-row: auto; margin: 0 auto 20px; }
            .principal-side .principal-photo { width: 128px; }
            .principal-letter { padding: 28px 22px 26px; }
            .principal-letter .prose > p:first-child::first-letter { font-size: 2.8rem; }
            .principal-values { margin-top: 28px; }
            .faculty { gap: 14px; }
            .faculty-lead-photo { width: 104px; aspect-ratio: 1; font-size: 1.5rem; }
            .faculty-dept { display: none; }
            .faculty-lead-body { padding: 18px 18px 20px; }
            .faculty-lead-body h3 { font-size: 1.02rem; }
            .faculty-go { padding-top: 14px; font-size: .84rem; }
            .faculty-list { gap: 10px; }
            .faculty-row { padding: 12px 15px; gap: 13px; }
            .faculty-row .avatar { width: 46px; height: 46px; font-size: .95rem; }
            .faculty-row-text strong { font-size: .92rem; }
            .dept-chip { display: none; }
            .tbar { grid-template-columns: 1fr; padding: 18px 20px; margin-bottom: 24px; }
            .tbar-actions .btn { flex: 1 1 100%; }
            /* a directory of a dozen photos gets tall fast, so each card lies on its side */
            .tcard { flex-direction: row; }
            .tcard-media { width: 112px; aspect-ratio: 1; font-size: 1.7rem; }
            .tcard-body { padding: 16px 18px; }
            .tcard-body h2 { font-size: .98rem; }
            .tcard-go { padding-top: 12px; font-size: .82rem; }
            .tprofile-card { grid-template-columns: 1fr; text-align: center; justify-items: center; padding: 24px 22px; }
            .tprofile-card .principal-media { grid-row: auto; width: 128px; margin: 0 auto 18px; }
            .tprofile-card .principal-photo { width: 128px; }
            .tprofile-actions { flex-direction: column; width: 100%; }
            .tprofile-actions .btn { width: 100%; }
            .tprofile-meta { width: 100%; }
            .tpanel { padding: 24px 20px; }
            .tsubjects { grid-template-columns: 1fr; gap: 10px; }
            .sechead { margin-bottom: 22px; }
            .nlist { gap: 10px; }
            .nrow { gap: 14px; padding: 14px 16px; }
            .ndate { width: 52px; height: 52px; border-radius: 14px; }
            .ndate-day { font-size: 1.1rem; }
            .nrow-top { margin-bottom: 5px; }
            .nrow-title { font-size: .94rem; }
            .nrow-go { font-size: .8rem; }
            .elist { gap: 10px; }
            .elist::before { left: 27px; }
            .erow { gap: 14px; padding: 14px 16px; }
            .edate { width: 54px; padding: 7px 0; border-radius: 14px; }
            .edate-day { font-size: 1.12rem; }
            .erow-title { font-size: .95rem; margin-bottom: 7px; }
            .echip { font-size: .72rem; padding: 3px 9px; }
            .erow-go { font-size: .8rem; }
            .nwslead { grid-template-columns: 1fr; }
            .nwslead-media { min-height: 180px; font-size: 2.2rem; }
            .nwslead-body { padding: 20px; }
            .nwslead-desc { -webkit-line-clamp: 2; margin-bottom: 14px; }
            .nwsgrid { grid-template-columns: 1fr; gap: 10px; margin-top: 12px; }
            .nwsmini { grid-template-columns: 84px minmax(0, 1fr); padding: 10px; }
            .section { padding: 58px 0; }
            .grid-2, .grid-3, .grid-4, .grid-auto { grid-template-columns: 1fr; }
            .about-pillar { padding: 16px; gap: 13px; }
            .about-actions .btn { width: 100%; }
            .about-panel { padding: 26px 20px; }
            .about-feat { gap: 11px; }
            .about-page .section-head { margin-bottom: 28px; }
            .about-stats { padding: 6px 20px; }
            .about-stat { padding: 16px 0; gap: 13px; }
            .about-stat-value { font-size: 1.5rem; }
            .about-stat-icon { width: 38px; height: 38px; font-size: 1rem; }
            .about-stat-label { font-size: .7rem; }
            .about-pillars-wide, .grid-tiles { grid-template-columns: 1fr; gap: 14px; }
            .about-why { grid-template-columns: 1fr; gap: 0; }
            .tile { padding: 20px 18px; }
            .about-why-item { padding: 16px 0; }
            .cls-head { padding: 15px 16px; gap: 12px; }
            .cls-body { padding: 0 16px 18px; }
            .cls-initial { width: 40px; height: 40px; font-size: 1rem; border-radius: 11px; }
            .cls-title h3 { font-size: .98rem; }
            /* Long class names push the code chip and caret onto their own row */
            .cls-code { margin-left: 52px; }
            .principal { grid-template-columns: 1fr; gap: 28px; padding: 28px 22px; }
            .principal-photo { width: 172px; }
            .principal-frame { inset: 10px -10px -10px 10px; border-radius: 16px; }
            .principal-quote { padding-left: 28px; font-size: 1rem; }
            .principal-sign { flex-direction: column; align-items: flex-start; gap: 14px; }
            .about-quote { padding: 26px 20px; }
            .about-quote .btn { width: 100%; }
            .fac-group + .fac-group { margin-top: 34px; }
            .fac-cta { padding: 26px 20px; }
            .fac-cta-actions { width: 100%; }
            .fac-cta-actions .btn { flex: 1 1 100%; }
            .glance { padding: 26px 18px; }
            .glance-head { gap: 14px; padding-bottom: 22px; }
            .glance-head-aside { max-width: none; gap: 10px; }
            .glance-sub { font-size: .88rem; }
            /* Stay two-up so the band stays roughly half as tall as a stacked list */
            .glance-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px 0; padding-top: 22px; }
            .glance-stat { padding: 0 16px; border-left: 0; border-top: 0; }
            .glance-stat:nth-child(odd) { padding-left: 0; }
            .glance-stat:nth-child(even) { padding-right: 0; }
            .glance-stat > i { font-size: 1.05rem; margin-bottom: 8px; }
            .glance-value { font-size: 1.7rem; margin-bottom: 4px; }
            .glance-label { font-size: .76rem; line-height: 1.35; }
            .prog { padding: 22px 20px; }
            .prog-all { margin-top: 26px; }
            /* Six stacked cards made this section ~1200px tall, so on phones the row
               scrolls sideways instead. No JS: the next card peeking is the affordance. */
            .prog-grid {
                display: flex; gap: 14px;
                overflow-x: auto; overscroll-behavior-x: contain;
                scroll-snap-type: x mandatory;
                /* bleed past the container padding so the next card peeks at the screen edge */
                margin-inline: -20px; padding: 4px 20px;
                scrollbar-width: none;
            }
            .prog-grid::-webkit-scrollbar { display: none; }
            .prog { flex: 0 0 78%; scroll-snap-align: start; }
            /* clamp the copy so one long description cannot stretch the card */
            .prog p { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
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
