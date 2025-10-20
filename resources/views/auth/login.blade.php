{{-- resources/views/auth/login.blade.php (pure CSS, tanpa Tailwind) --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign in</title>

  <style>
    :root { --card-bg: rgba(255,255,255,.82); --ring: rgba(0,0,0,.06); --text: #111827; --muted:#6B7280; }
    * { box-sizing: border-box; }
    html, body { height: 100%; }
    body {
      margin: 0; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji","Segoe UI Emoji";
      background: linear-gradient(180deg,#dff0ff 0%,#eaf4ff 35%,#f6fbff 60%,#ffffff 100%);
    }
    /* soft clouds */
    .clouds::before,.clouds::after{
      content:"";position:absolute;left:50%;transform:translateX(-50%);width:1200px;height:600px;
      background:
        radial-gradient(closest-side, rgba(255,255,255,.8), rgba(255,255,255,0)) -200px -80px/500px 220px no-repeat,
        radial-gradient(closest-side, rgba(255,255,255,.7), rgba(255,255,255,0)) 100px -60px/420px 180px no-repeat,
        radial-gradient(closest-side, rgba(255,255,255,.6), rgba(255,255,255,0)) -20px 60px/520px 200px no-repeat,
        radial-gradient(closest-side, rgba(255,255,255,.75), rgba(255,255,255,0)) 260px 80px/560px 220px no-repeat;
      filter: blur(6px); pointer-events:none;
    }
    .clouds::before{ top:0; opacity:.9; }
    .clouds::after{ bottom:0; opacity:.7; }

    .wrap { position:relative; min-height:100%; display:flex; align-items:center; justify-content:center; padding:16px; }
    .brand { display:flex; align-items:center; gap:8px; margin-bottom:12px; }
    .logo { width:32px; height:32px; border-radius:12px; background:#0a0a0a; color:#fff; display:grid; place-items:center; font-weight:700; font-size:13px; }
    .brand span { color:#6B7280; font-size:13px; }

    .card {
      width:100%; max-width:420px; background:var(--card-bg); backdrop-filter: blur(8px);
      border-radius:18px; box-shadow: 0 10px 30px rgba(0,0,0,.08); outline:1px solid var(--ring);
    }
    .card-inner { padding:28px; }

    .icon {
      width:48px; height:48px; border-radius:14px; background:#f3f4f6; margin:0 auto 18px; display:grid; place-items:center;
    }
    .icon svg { width:26px; height:26px; stroke:#111827; }

    h1 { margin:0; text-align:center; color:var(--text); font-weight:600; font-size:20px; }
    .sub { margin-top:6px; text-align:center; color:var(--muted); font-size:14px; }

    .field { margin-top:14px; }
    .input-wrap { position:relative; }
    .input-wrap svg { position:absolute; left:12px; top:50%; transform:translateY(-50%); width:18px; height:18px; color:#9CA3AF; }
    input[type="email"], input[type="password"] {
      width:100%; padding:12px 14px 12px 40px; border:1px solid #e5e7eb; background:rgba(255,255,255,.7);
      color:#111827; border-radius:12px; font-size:14px; outline:none;
    }
    input::placeholder { color:#9CA3AF; }
    input:focus { border-color:#60a5fa; box-shadow:0 0 0 3px rgba(96,165,250,.3); }

    .row { margin-top:10px; display:flex; align-items:center; justify-content:space-between; }
    .remember { display:inline-flex; align-items:center; gap:8px; color:#6B7280; font-size:14px; }
    .link { font-size:14px; color:#374151; text-decoration:none; font-weight:600; }
    .link:hover { color:#2563eb; }

    .btn {
      margin-top:12px; width:100%; padding:12px 16px; background:#111827; color:#fff; border:none; border-radius:12px;
      font-size:14px; font-weight:700; cursor:pointer;
    }
    .btn:hover { background:#000; }

    .register { margin-top:20px; text-align:center; font-size:14px; color:#6B7280; }
    .register a { color:#1f2937; text-decoration:none; font-weight:600; }
    .register a:hover{ color:#2563eb; }

    .footer { margin-top:20px; text-align:center; font-size:12px; color:#6B7280; }
  </style>
</head>
<body>
  <div class="clouds" style="position:absolute; inset:0;"></div>

  <div class="wrap">
    <div style="width:100%; max-width:420px;">
      <div class="brand">
        <div class="logo">R</div>
        <span>RASIAP</span>
      </div>

      <div class="card">
        <div class="card-inner">
          <div class="icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-7.5A2.25 2.25 0 003.75 5.25v13.5A2.25 2.25 0 006 21h7.5a2.25 2.25 0 002.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H6"/>
            </svg>
          </div>

          <h1>Sign in with email</h1>
          <p class="sub">Make a new doc to bring your words, data, and teams together. For free</p>

          <form method="POST" action="{{ route('login') }}" style="margin-top:16px;">
            @csrf
            <div class="field">
              <label for="email" class="sr-only">Email</label>
              <div class="input-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                        d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5A2 2 0 003 7v10a2 2 0 002 2z"/>
                </svg>
                <input id="email" name="email" type="email" autocomplete="email" required placeholder="Email" value="{{ old('email') }}">
              </div>
              @error('email') <p style="margin:6px 0 0; font-size:12px; color:#dc2626;">{{ $message }}</p> @enderror
            </div>

            <div class="field">
              <label for="password" class="sr-only">Password</label>
              <div class="input-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v4"/>
                </svg>
                <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="Password">
              </div>
              @error('password') <p style="margin:6px 0 0; font-size:12px; color:#dc2626;">{{ $message }}</p> @enderror
            </div>

            <div class="row">
              <label class="remember"><input type="checkbox" name="remember"> Remember me</label>
              @if (Route::has('password.request'))
                <a class="link" href="{{ route('password.request') }}">Forgot password?</a>
              @endif
            </div>

            <button type="submit" class="btn">Get Started</button>
          </form>

          @if (Route::has('register'))
            <p class="register">Don’t have an account? <a href="{{ route('register') }}">Create one</a></p>
          @endif
        </div>
      </div>

      <p class="footer">© {{ date('Y') }} RASIAP. All rights reserved.</p>
    </div>
  </div>
</body>
</html>
