<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login — {{ $settings['school_name'] ?? 'My School' }}</title>

    @if (! empty($settings['favicon']))
        <link rel="icon" href="{{ asset('storage/' . $settings['favicon']) }}">
    @endif

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #2563EB; --danger: #DC2626; --border: #E2E8F0; --muted: #64748B; --text: #1E293B;
            --gradient: linear-gradient(135deg, #2563EB, #1E40AF);
            --font: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        body {
            font-family: var(--font); min-height: 100vh; display: grid; place-items: center; padding: 24px;
            background: linear-gradient(135deg, #0F172A 0%, #1E3A8A 55%, #2563EB 100%); color: var(--text);
        }
        .login-card {
            background: rgba(255,255,255,.97); border-radius: 22px; padding: 42px; width: 100%; max-width: 440px;
            box-shadow: 0 40px 80px -30px rgba(15,23,42,.7); animation: rise .5s cubic-bezier(.4,0,.2,1);
        }
        @keyframes rise { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: none; } }
        .brand { text-align: center; margin-bottom: 28px; }
        .brand-mark {
            width: 62px; height: 62px; border-radius: 18px; background: var(--gradient); color: #fff;
            display: grid; place-items: center; font-size: 1.4rem; font-weight: 800; margin: 0 auto 16px;
            box-shadow: 0 16px 30px -14px rgba(37,99,235,1); overflow: hidden;
        }
        .brand-mark img { width: 100%; height: 100%; object-fit: cover; }
        h1 { font-size: 1.45rem; color: #0F172A; margin-bottom: 6px; text-align: center; }
        .sub { color: var(--muted); font-size: .88rem; text-align: center; }
        .form-row { margin-bottom: 18px; }
        label { display: block; font-weight: 600; font-size: .82rem; margin-bottom: 7px; color: #0F172A; }
        input {
            width: 100%; padding: 12px 15px; border: 1.5px solid var(--border); border-radius: 11px;
            font-family: inherit; font-size: .92rem; transition: border-color .2s ease, box-shadow .2s ease;
        }
        input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(37,99,235,.12); }
        input.is-invalid { border-color: var(--danger); }
        .alert { background: rgba(220,38,38,.08); color: #B91C1C; border: 1px solid rgba(220,38,38,.22); padding: 13px 16px; border-radius: 11px; font-size: .85rem; font-weight: 500; margin-bottom: 18px; }
        .error { color: var(--danger); font-size: .76rem; margin-top: 5px; font-weight: 600; }
        .btn {
            width: 100%; padding: 13px; border-radius: 11px; border: none; background: var(--gradient); color: #fff;
            font-family: inherit; font-weight: 700; font-size: .93rem; cursor: pointer; margin-top: 8px;
            box-shadow: 0 14px 28px -14px rgba(37,99,235,1); transition: transform .22s ease, box-shadow .22s ease;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 18px 34px -14px rgba(37,99,235,1); }
        .btn:disabled { opacity: .7; cursor: wait; }
        .remember { display: flex; align-items: center; gap: 9px; font-size: .85rem; font-weight: 600; color: var(--muted); cursor: pointer; margin-bottom: 8px; }
        .remember input { width: 16px; height: 16px; accent-color: var(--primary); }
        .back { text-align: center; margin-top: 20px; font-size: .84rem; color: var(--muted); }
        .back a { color: var(--primary); font-weight: 600; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            <div class="brand-mark">
                @if (! empty($settings['logo']))
                    <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo">
                @else
                    MS
                @endif
            </div>
            <h1>Admin Panel Login</h1>
            <p class="sub">{{ $settings['school_name'] ?? 'My School' }} management system</p>
        </div>

        @if ($errors->any())
            <div class="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.attempt') }}" id="loginForm">
            @csrf

            <div class="form-row">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="@error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-row">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="@error('password') is-invalid @enderror" required autocomplete="current-password">
                @error('password')<div class="error">{{ $message }}</div>@enderror
            </div>

            <label class="remember">
                <input type="checkbox" name="remember" value="1"> Remember me
            </label>

            <button type="submit" class="btn" id="loginBtn">Sign In to Dashboard</button>
        </form>

        <p class="back"><a href="{{ route('home') }}"><i class="bi bi-arrow-left"></i> Back to website</a></p>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('loginBtn');
            btn.disabled = true;
            btn.textContent = 'Signing in…';
        });
    </script>
</body>
</html>
