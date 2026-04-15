<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PiggyTrunk Admin Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --login-bg: #f7f8fb;
            --login-panel: #ffffff;
            --login-border: #dbe2ec;
            --login-text: #1d2735;
            --login-muted: #5d6a7b;
            --login-input: #e8edf3;
            --login-accent: #445571;
            --login-accent-hover: #36465f;
            --login-brand-bg: linear-gradient(165deg, #e8eef6 0%, #d8e2ed 100%);
            --login-danger: #c73f57;
            --login-success: #246b45;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Sora", sans-serif;
            background: var(--login-bg);
            color: var(--login-text);
        }

        .login-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(320px, 1fr) minmax(380px, 1fr);
        }

        .login-form-panel {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3.5rem 2.5rem;
            background: var(--login-panel);
            border-right: 1px solid var(--login-border);
        }

        .login-form-wrap {
            width: min(100%, 460px);
        }

        .login-brand-mobile {
            display: none;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        .login-brand-mobile img {
            width: 68px;
            height: 68px;
            object-fit: contain;
        }

        .login-brand-mobile strong {
            font-size: 1.1rem;
            letter-spacing: -0.02em;
        }

        h1 {
            margin: 0;
            font-size: clamp(2rem, 3.5vw, 3rem);
            letter-spacing: -0.04em;
            line-height: 1.06;
            text-align: center;
        }

        .login-subtitle {
            margin: 1rem 0 2rem;
            color: var(--login-muted);
            max-width: 28ch;
            line-height: 1.6;
            font-size: 1.03rem;
        }

        .login-alert {
            margin-bottom: 1rem;
            border-radius: 13px;
            padding: 0.85rem 0.95rem;
            border: 1px solid transparent;
            font-size: 0.92rem;
            line-height: 1.45;
        }

        .login-alert.error {
            background: #fff1f4;
            border-color: #f0c7d0;
            color: var(--login-danger);
        }

        .login-alert.success {
            background: #ecf9f1;
            border-color: #b8e1c9;
            color: var(--login-success);
        }

        .field-row + .field-row {
            margin-top: 1.45rem;
        }

        .field-label {
            display: block;
            margin-bottom: 0.55rem;
            color: #495566;
            font-size: 0.93rem;
            letter-spacing: 0.08em;
            font-weight: 700;
        }

        .field-input {
            width: 100%;
            border: 1px solid transparent;
            border-radius: 12px;
            background: var(--login-input);
            color: var(--login-text);
            font: inherit;
            padding: 0.9rem 1rem;
            min-height: 52px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .field-input::placeholder {
            color: #6d7989;
            opacity: 1;
        }

        .field-input:focus {
            outline: none;
            border-color: #9aaac3;
            box-shadow: 0 0 0 3px rgba(68, 85, 113, 0.12);
        }

        .input-icon-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 0.95rem;
            transform: translateY(-50%);
            color: #5f6d81;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .input-icon svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
        }

        .input-icon-wrap:focus-within .input-icon {
            color: #445571;
            transform: translateY(-50%) scale(1.06);
        }

        .email-wrap .field-input {
            padding-left: 2.8rem;
        }

        .password-wrap {
            position: relative;
        }

        .password-wrap .field-input {
            padding-right: 3rem;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 0.75rem;
            transform: translateY(-50%);
            width: 2rem;
            height: 2rem;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: #5f6d81;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .password-toggle:hover {
            background: rgba(68, 85, 113, 0.1);
        }

        .password-toggle svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
        }

        .password-toggle.is-visible {
            color: #445571;
        }

        #eyeSlash {
            opacity: 1;
            transform-origin: center;
            transform: scale(1) rotate(0deg);
            transition: opacity 0.22s ease, transform 0.28s ease;
        }

        .password-toggle.is-visible #eyeSlash {
            opacity: 0;
            transform: scale(0.4) rotate(-20deg);
        }

        .form-meta {
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            font-size: 0.9rem;
        }

        .remember-wrap {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--login-muted);
        }

        .remember-wrap input {
            width: 17px;
            height: 17px;
            accent-color: var(--login-accent);
        }

        .meta-link {
            color: #4f5d7a;
            text-decoration: none;
            letter-spacing: 0.08em;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
        }

        .meta-link:hover {
            text-decoration: underline;
        }

        .signin-button {
            margin-top: 1.8rem;
            width: 100%;
            border: 0;
            border-radius: 12px;
            background: var(--login-accent);
            color: #ffffff;
            font: inherit;
            font-weight: 700;
            letter-spacing: 0.04em;
            min-height: 54px;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .signin-button:hover {
            background: var(--login-accent-hover);
            transform: translateY(-1px);
        }

        .signin-button:active {
            transform: translateY(0);
        }

        .login-brand-panel {
            position: relative;
            background: var(--login-brand-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
        }

        .brand-wrap {
            width: min(92%, 600px);
            text-align: center;
        }

        .brand-logo-card {
            width: 164px;
            height: 164px;
            margin: 0 auto 2rem;
            border-radius: 36px;
            background: rgba(255, 255, 255, 0.86);
            box-shadow: 0 22px 40px rgba(60, 80, 112, 0.15);
            display: grid;
            place-items: center;
        }

        .brand-logo-card img {
            width: 120px;
            height: 120px;
            object-fit: contain;
        }

        .brand-title {
            margin: 0;
            font-size: clamp(2rem, 3.4vw, 3rem);
            letter-spacing: -0.04em;
            line-height: 1.05;
        }

        .brand-divider {
            width: 118px;
            height: 4px;
            border-radius: 999px;
            background: #4e607d;
            margin: 1rem auto 1.35rem;
        }

        .brand-copy {
            margin: 0 auto 2.2rem;
            color: #4f5e71;
            font-size: 1.1rem;
            line-height: 1.75;
            max-width: 38ch;
        }

        @media (max-width: 1120px) {
            .login-shell {
                grid-template-columns: minmax(320px, 1fr);
            }

            .login-form-panel {
                border-right: 0;
            }

            .login-brand-mobile {
                display: inline-flex;
            }

            .login-brand-panel {
                display: none;
            }
        }

        @media (max-width: 640px) {
            .login-form-panel {
                padding: 2.25rem 1.3rem 2rem;
            }

            .form-meta {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <main class="login-shell">
        <section class="login-form-panel">
            <div class="login-form-wrap">
                <div class="login-brand-mobile">
                    <img src="{{ asset('piggytrunkremovebg.png') }}" alt="PiggyTrunk logo">
                    <strong>Piggy Trunk</strong>
                </div>

                <h1>Admin Login</h1>

                @if (session('status'))
                    <div class="login-alert success">{{ session('status') }}</div>
                @endif

                @if (session('error'))
                    <div class="login-alert error">{{ session('error') }}</div>
                @endif

                @if ($errors->any())
                    <div class="login-alert error">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf

                    <div class="field-row">
                        <label for="email" class="field-label">EMAIL ADDRESS</label>
                        <div class="input-icon-wrap email-wrap">
                            <span class="input-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <rect x="3" y="5" width="18" height="14" rx="2.5" stroke-width="1.8" />
                                    <path d="M4.5 7L12 12.7L19.5 7" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                class="field-input"
                                placeholder="admin@piggytrunk"
                                autocomplete="username"
                                required
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="field-row">
                        <label for="password" class="field-label">PASSWORD</label>
                        <div class="password-wrap">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="field-input"
                                placeholder="********"
                                autocomplete="current-password"
                                required
                            >
                            <button type="button" class="password-toggle" id="passwordToggle" aria-label="Show password">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M2 12C3.8 8.5 7.3 6 12 6C16.7 6 20.2 8.5 22 12C20.2 15.5 16.7 18 12 18C7.3 18 3.8 15.5 2 12Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                    <circle cx="12" cy="12" r="2.8" stroke-width="1.8" />
                                    <path id="eyeSlash" d="M3 3L21 21" stroke-width="1.8" stroke-linecap="round" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-meta">
                        <label for="remember" class="remember-wrap">
                            <input id="remember" name="remember" type="checkbox">
                            <span>Remember this device</span>
                        </label>

                        <a href="#" class="meta-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="signin-button">SIGN IN TO DASHBOARD</button>
                </form>
            </div>
        </section>

        <aside class="login-brand-panel">
            <div class="brand-wrap">
                <div class="brand-logo-card">
                    <img src="{{ asset('piggytrunkremovebg.png') }}" alt="PiggyTrunk logo">
                </div>

                <h2 class="brand-title">Piggy Trunk</h2>
                <div class="brand-divider"></div>
            </div>
        </aside>
    </main>

    <script>
        (() => {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('passwordToggle');
            const eyeSlash = document.getElementById('eyeSlash');

            if (!passwordInput || !toggleButton || !eyeSlash) {
                return;
            }

            const syncToggleState = () => {
                const isVisible = passwordInput.type === 'text';
                toggleButton.classList.toggle('is-visible', isVisible);
                toggleButton.setAttribute('aria-label', isVisible ? 'Hide password' : 'Show password');
            };

            toggleButton.addEventListener('click', () => {
                passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
                syncToggleState();
            });

            syncToggleState();
        })();
    </script>
</body>
</html>
