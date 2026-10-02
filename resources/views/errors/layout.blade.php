<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Error') — {{ $settings['school_name'] ?? 'My School' }}</title>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh; display: grid; place-items: center; padding: 28px;
            background: linear-gradient(135deg, #0F172A 0%, #1E3A8A 55%, #2563EB 100%);
            color: #fff; text-align: center;
        }
        .box { max-width: 560px; width: 100%; animation: rise .5s cubic-bezier(.4,0,.2,1); }
        @keyframes rise { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: none; } }
        .code {
            font-size: clamp(4.5rem, 16vw, 8rem); font-weight: 800; line-height: 1;
            background: linear-gradient(120deg, #93C5FD, #67E8F9);
            -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;
            letter-spacing: -.04em;
        }
        h1 { font-size: clamp(1.3rem, 4vw, 1.9rem); margin: 14px 0 12px; color: #fff; }
        p { color: rgba(255,255,255,.78); font-size: 1rem; line-height: 1.7; margin-bottom: 28px; }
        .actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px; padding: 13px 26px; border-radius: 999px;
            font-weight: 700; font-size: .92rem; text-decoration: none; transition: transform .25s ease, box-shadow .25s ease;
        }
        .btn-primary { background: #fff; color: #0F172A; box-shadow: 0 16px 34px -16px rgba(0,0,0,.7); }
        .btn-primary:hover { transform: translateY(-3px); }
        .btn-ghost { border: 1px solid rgba(255,255,255,.3); color: #fff; background: rgba(255,255,255,.12); }
        .btn-ghost:hover { background: rgba(255,255,255,.22); transform: translateY(-3px); }
    </style>
</head>
<body>
    <div class="box">
        <div class="code">@yield('code')</div>
        <h1>@yield('heading')</h1>
        <p>@yield('message')</p>
        <div class="actions">
            <a href="{{ url('/') }}" class="btn btn-primary">Back to Home</a>
            <a href="{{ url('/contact') }}" class="btn btn-ghost">Contact Support</a>
        </div>
    </div>
</body>
</html>
