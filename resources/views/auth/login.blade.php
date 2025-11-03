<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'PT BPR Sarimadu') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <style>
        :root {
            --navy: #003366;
            --blue: #0b63f6;
            --blue-dark: #084dcc;
            --blue-light: #f0f7ff;
            --muted: #6b7280;
            --ink: #1e293b;
            --surface: #ffffff;
            --border: #e5e7eb;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            background: linear-gradient(135deg, var(--blue-light) 0%, #ffffff 100%);
        }

        .auth-wrapper {
            width: 100%;
            max-width: 440px;
        }

        .auth-brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-brand img {
            height: 48px;
            width: auto;
            border-radius: 10px;
            object-fit: contain;
            filter: drop-shadow(var(--shadow-sm));
        }

        .auth-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            padding: 2.5rem;
            backdrop-filter: blur(10px);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--navy);
            margin: 0 0 0.5rem 0;
            letter-spacing: -0.02em;
        }

        .auth-subtitle {
            font-size: 0.9375rem;
            color: var(--muted);
            margin: 0;
        }

        .status-message {
            padding: 0.75rem 1rem;
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 12px;
            color: #166534;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            background: var(--surface);
            color: var(--ink);
            transition: var(--transition);
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(11, 99, 246, 0.1);
            background: #ffffff;
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: var(--blue);
        }

        .checkbox-wrapper label {
            margin: 0;
            color: var(--muted);
            cursor: pointer;
        }

        .link-muted {
            color: var(--muted);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
        }

        .link-muted:hover {
            color: var(--blue);
            text-decoration: none;
        }

        .btn-primary {
            width: 100%;
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(135deg, var(--blue) 0%, var(--blue-dark) 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(11, 99, 246, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-family: inherit;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(11, 99, 246, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
            font-size: 0.9375rem;
            color: var(--muted);
        }

        .link-primary {
            color: var(--blue);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .link-primary:hover {
            color: var(--blue-dark);
            text-decoration: underline;
        }

        .error-message {
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #dc2626;
            list-style: none;
        }

        .error-message li {
            margin-bottom: 0.25rem;
        }

        @media (max-width: 640px) {
            .auth-card {
                padding: 2rem 1.5rem;
            }

            .auth-title {
                font-size: 1.5rem;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-wrapper">
            <div class="auth-brand">
                <img src="{{ asset('assets/LOGO_PANJANG_OK.png') }}" alt="PT BPR Sarimadu">
            </div>

            <div class="auth-card">
                <div class="auth-header">
                    <h1 class="auth-title">Selamat Datang Kembali</h1>
                    <p class="auth-subtitle">Masuk dengan email dan password Anda</p>
                </div>

                @if (session('status'))
                    <div class="status-message">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            class="form-input"
                            placeholder="nama@email.com"
                            value="{{ old('email') }}" 
                            required 
                            autofocus 
                            autocomplete="username"
                        >
                        @error('email')
                            <ul class="error-message">
                                <li>{{ $message }}</li>
                            </ul>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            class="form-input"
                            placeholder="Masukkan password"
                            required 
                            autocomplete="current-password"
                        >
                        @error('password')
                            <ul class="error-message">
                                <li>{{ $message }}</li>
                            </ul>
                        @enderror
                    </div>

                    <div class="form-options">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Ingat saya</label>
                        </div>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="link-muted">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10 17 15 12 10 7"></polyline>
                            <line x1="15" y1="12" x2="3" y2="12"></line>
                        </svg>
                        Masuk
                    </button>

                    <div class="auth-footer">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="link-primary">Daftar sekarang</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>