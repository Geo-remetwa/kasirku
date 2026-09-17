@extends('layouts.app')
@section('title', 'Login')

@section('addon-css')
<style>
    /* Escape the admin layout's default padding/width so this page can
       take over the full viewport as a two-panel split screen. */
    html, body { height: 100%; margin: 0; padding: 0; }
    body { background: #fff; }
    #app, .main-wrapper, .main-wrapper-1 { padding: 0 !important; margin: 0 !important; height: 100%; }

    .kasirku-login {
        /* Single source of truth for the split so the art panel width
           and the form panel's left offset can never drift apart. */
        --split: 50%;
        --gutter: 64px;

        position: fixed;
        inset: 0;
        width: 100vw;
        height: 100vh;
        height: 100dvh;
        display: flex;
        font-family: 'Nunito', sans-serif;
        overflow: auto;
        z-index: 1050;
    }

    /* ---------- Left panel: illustration ---------- */
    /* The dark panel is a full-bleed layer behind everything; the
       white panel sits on top of it as its own box, clipped along its
       right edge so the dark gradient shows through the curve. */
    .kasirku-login__art {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: var(--split);
        z-index: 2;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        clip-path: url('#kasirku-login-wave');
        transition: width .4s ease;
    }

    /* Hidden holder for the wave clip-path definition. */
    .kasirku-login__wave-clip {
        position: absolute;
        width: 0;
        height: 0;
        overflow: hidden;
    }

    .kasirku-login__brand {
        position: absolute;
        top: 32px;
        left: 40px;
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 3;
    }

    .kasirku-login__brand-badge {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff1e6;
        border: 1px solid rgba(0, 0, 0, .05);
        color: #7c2d12;
    }

    .kasirku-login__brand-badge svg { display: block; }

    .kasirku-login__brand-text strong {
        display: block;
        font-size: 17px;
        font-weight: 800;
        color: #222;
        line-height: 1.15;
    }

    .kasirku-login__brand-text span {
        display: block;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.6px;
        color: #c2410c;
        text-transform: uppercase;
    }

    /* ---------- Background shape ---------- */
    /* Sized relative to the panel itself (min of its own dimensions)
       so it never outgrows a narrow panel at smaller breakpoints. */
    .kasirku-login__blob {
        position: absolute;
        top: 44%;
        left: 42%;
        width: min(56vw, 62vh, 560px);
        height: min(56vw, 62vh, 560px);
        transform: translate(-50%, -50%);
        fill: #fff1e6;
    }

    .kasirku-login__accent {
        position: absolute;
        width: 15vw;
        max-width: 170px;
        aspect-ratio: 1;
        top: 68%;
        left: 72%;
        transform: translate(-50%, -50%);
        border-radius: 50%;
        background: #ffedd5;
        opacity: .55;
    }

    .kasirku-login__bubble {
        position: absolute;
        border-radius: 50%;
        background: #fff7ed;
    }

    .kasirku-login__illustration {
        position: relative;
        z-index: 2;
        width: 300px;
        max-width: 55%;
    }

    /* ---------- Right panel: form ---------- */
    .kasirku-login__panel {
        position: absolute;
        inset: 0;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px var(--gutter) 40px calc(var(--split) + var(--gutter));
        box-sizing: border-box;
        background: linear-gradient(160deg, #431407 0%, #9A3412 100%);
        color: #fff;
        transition: padding .4s ease;
    }

    .kasirku-login__form-wrap { width: 100%; max-width: 360px; }

    /* Brand row shown only on mobile, in normal flow above the title
       (the desktop brand lives inside .art, which is hidden here). */
    .kasirku-login__brand--mobile {
        display: none;
        align-items: center;
        gap: 10px;
        margin-bottom: 28px;
    }

    .kasirku-login__brand--mobile .kasirku-login__brand-text strong { color: #fff; }
    .kasirku-login__brand--mobile .kasirku-login__brand-text span { color: #fdba74; }

    .kasirku-login__title {
        font-weight: 800;
        font-size: 32px;
        letter-spacing: .5px;
        margin-bottom: 32px;
    }

    .kasirku-login__group { margin-bottom: 20px; }

    .kasirku-login__group label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 8px;
        color: rgba(255, 255, 255, .85);
    }

    .kasirku-login__input {
        position: relative;
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, .1);
        border: 1px solid rgba(255, 255, 255, .18);
        border-radius: 12px;
        padding: 0 16px;
        transition: border-color .15s ease, background .15s ease;
    }

    .kasirku-login__input:focus-within {
        border-color: #fb923c;
        background: rgba(255, 255, 255, .16);
    }

    .kasirku-login__input i {
        color: rgba(255, 255, 255, .65);
        font-size: 15px;
        width: 20px;
        text-align: center;
    }

    .kasirku-login__input input {
        flex: 1;
        border: 0;
        background: transparent;
        color: #fff;
        padding: 12px 12px;
        font-size: 14px;
        outline: none;
    }

    .kasirku-login__input input::placeholder { color: rgba(255, 255, 255, .45); }

    .kasirku-login__input.is-invalid { border-color: #fc544b; }

    .kasirku-login__feedback {
        color: #ffb4af;
        font-size: 12px;
        margin-top: 6px;
        padding-left: 4px;
    }

    .kasirku-login__alert {
        background: rgba(252, 84, 75, .15);
        border: 1px solid rgba(252, 84, 75, .4);
        color: #ffd9d6;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .kasirku-login__btn {
        width: 100%;
        border: 0;
        border-radius: 12px;
        padding: 13px;
        font-weight: 800;
        font-size: 14px;
        letter-spacing: .5px;
        color: #fff;
        background: #c2410c;
        cursor: pointer;
        transition: background .15s ease, transform .1s ease;
    }

    .kasirku-login__btn:hover { background: #9a3412; }
    .kasirku-login__btn:active { transform: scale(.98); }

    .kasirku-login__links {
        display: flex;
        justify-content: space-between;
        margin-top: 18px;
        font-size: 12.5px;
    }

    .kasirku-login__links a {
        color: rgba(255, 255, 255, .75);
        text-decoration: none;
    }

    .kasirku-login__links a:hover { color: #fff; text-decoration: underline; }

    .kasirku-login__footer {
        margin-top: 40px;
        text-align: center;
        font-size: 11.5px;
        color: rgba(255, 255, 255, .45);
    }

    /* ---------- Responsive ---------- */
    /* Only --split changes at each breakpoint; the form panel's
       padding-left reads the same variable, so they never drift. */
    @media (max-width: 1024px) {
        .kasirku-login { --split: 44%; --gutter: 40px; }
    }

    @media (max-width: 860px) {
        .kasirku-login__art { display: none; }
        .kasirku-login__panel {
            width: 100%;
            padding: max(32px, env(safe-area-inset-top)) 24px max(32px, env(safe-area-inset-bottom));
            position: relative;
            min-height: 100vh;
            min-height: 100dvh;
            /* A quiet radial glow stands in for the illustration panel
               so the screen doesn't read as a flat, empty rectangle. */
            background:
                radial-gradient(520px 420px at 85% -5%, rgba(251, 146, 60, .18), transparent 60%),
                radial-gradient(420px 360px at -10% 100%, rgba(251, 146, 60, .12), transparent 60%),
                linear-gradient(160deg, #431407 0%, #9A3412 100%);
        }
        .kasirku-login__brand--mobile { display: flex; }
    }

    @media (max-width: 480px) {
        .kasirku-login__title { font-size: 26px; margin-bottom: 20px; }
    }
</style>
@endsection

@section('content')
<div class="kasirku-login">

    <!-- Wavy divider: one flowing curve rather than several small
         bumps, so it stays smooth however tall/narrow the panel is. -->
    <svg class="kasirku-login__wave-clip" aria-hidden="true" focusable="false">
        <defs>
            <clipPath id="kasirku-login-wave" clipPathUnits="objectBoundingBox">
                <path d="M0,0
                         L0.88,0
                         C0.75,0.25 0.75,0.75 0.88,1
                         L0,1 Z"/>
            </clipPath>
        </defs>
    </svg>

    <div class="kasirku-login__art">
        <div class="kasirku-login__brand">
            <span class="kasirku-login__brand-badge" aria-hidden="true">
                <svg width="21" height="21" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.5 9V6.6a4.5 4.5 0 0 1 9 0V9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    <path d="M5.8 9h12.4l-1.06 11.15a1.7 1.7 0 0 1-1.7 1.55H8.56a1.7 1.7 0 0 1-1.7-1.55L5.8 9Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                </svg>
            </span>
            <div class="kasirku-login__brand-text">
                <strong>Kasirku</strong>
                <span>Kasir Toko</span>
            </div>
        </div>

        <svg class="kasirku-login__blob" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path transform="translate(100 100)" d="
                M 70,0
                C 69.75,24.97 59.17,64.95 42.5,73.61
                C 25.83,82.27 -9.92,64.23 -30,51.96
                C -50.08,39.69 -77.58,18.04 -78,0
                C -78.42,-18.04 -52.83,-43.59 -32.5,-56.29
                C -12.17,-68.99 26.92,-85.59 44,-76.21
                C 61.08,-66.83 70.25,-24.97 70,0
                Z" />
        </svg>

        <div class="kasirku-login__accent"></div>

        <div class="kasirku-login__bubble" style="width:26px;height:26px; top:14%; left:28%; opacity:.6;"></div>
        <div class="kasirku-login__bubble" style="width:14px;height:14px; top:22%; left:74%; opacity:.5;"></div>
        <div class="kasirku-login__bubble" style="width:18px;height:18px; top:80%; left:18%; opacity:.6;"></div>

        <svg class="kasirku-login__illustration" viewBox="0 0 260 300" xmlns="http://www.w3.org/2000/svg">
            <!-- shopping bag -->
            <path d="M92 96 L92 68 C92 46 108 32 130 32 C152 32 168 46 168 68 L168 96" fill="none" stroke="#c2410c" stroke-width="8" stroke-linecap="round"/>
            <rect x="58" y="96" width="144" height="118" rx="12" fill="#fff" stroke="#c2410c" stroke-width="5"/>

            <!-- groceries peeking out of the bag -->
            <rect x="104" y="50" width="14" height="52" rx="7" fill="#fb923c"/>
            <rect x="124" y="40" width="14" height="62" rx="7" fill="#c2410c"/>
            <circle cx="158" cy="62" r="18" fill="#ffedd5"/>
            <circle cx="158" cy="62" r="18" fill="none" stroke="#c2410c" stroke-width="2"/>

            <!-- receipt lines on the bag -->
            <rect x="76" y="118" width="60" height="7" rx="3.5" fill="#fff1e6"/>
            <rect x="76" y="136" width="44" height="7" rx="3.5" fill="#fff1e6"/>
            <rect x="76" y="154" width="52" height="7" rx="3.5" fill="#fff1e6"/>

            <!-- price tag accent -->
            <g transform="translate(148 168) rotate(18)">
                <path d="M0 10 L26 10 L26 -6 C26 -12 21 -17 15 -17 L0 -17 Z" fill="#fff1e6" stroke="#c2410c" stroke-width="3" stroke-linejoin="round"/>
                <circle cx="10" cy="-9" r="3" fill="#c2410c"/>
            </g>

            <!-- shopping cart -->
            <path d="M36 232 h16 l16 66 h96" fill="none" stroke="#7c2d12" stroke-width="7" stroke-linecap="round" stroke-linejoin="round"/>
            <rect x="70" y="238" width="94" height="38" rx="5" fill="none" stroke="#7c2d12" stroke-width="7"/>
            <circle cx="92" cy="288" r="10" fill="#7c2d12"/>
            <circle cx="154" cy="288" r="10" fill="#7c2d12"/>
        </svg>
    </div>

    <div class="kasirku-login__panel">
        <div class="kasirku-login__form-wrap">
            <div class="kasirku-login__brand--mobile" aria-hidden="true">
                <span class="kasirku-login__brand-badge">
                    <svg width="21" height="21" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.5 9V6.6a4.5 4.5 0 0 1 9 0V9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                        <path d="M5.8 9h12.4l-1.06 11.15a1.7 1.7 0 0 1-1.7 1.55H8.56a1.7 1.7 0 0 1-1.7-1.55L5.8 9Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                    </svg>
                </span>
                <div class="kasirku-login__brand-text">
                    <strong>Kasirku</strong>
                    <span>Kasir Toko Modern</span>
                </div>
            </div>
            <div class="kasirku-login__title">LOGIN</div>

            @if ($errors->any())
                <div class="kasirku-login__alert">
                    Email atau password salah!
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <div class="kasirku-login__group">
                    <label for="email">Email</label>
                    <div class="kasirku-login__input @error('email') is-invalid @enderror">
                        <i class="fas fa-user"></i>
                        <input id="email" type="email" name="email" placeholder="nama@email.com" tabindex="1" required autofocus>
                    </div>
                    @error('email')
                        <div class="kasirku-login__feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="kasirku-login__group">
                    <label for="password">Password</label>
                    <div class="kasirku-login__input @error('password') is-invalid @enderror">
                        <i class="fas fa-lock"></i>
                        <input id="password" type="password" name="password" placeholder="••••••••" tabindex="2" required>
                    </div>
                    @error('password')
                        <div class="kasirku-login__feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="kasirku-login__btn" tabindex="3">LOGIN</button>

                <div class="kasirku-login__links">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Lupa password?</a>
                    @else
                        <span></span>
                    @endif
                </div>
            </form>
        </div>
    </div>

</div>
@endsection