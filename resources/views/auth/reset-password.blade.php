<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - {{ config('app.name', 'PT BPR Sarimadu') }}</title>

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
            --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --danger: #dc2626;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, var(--blue-light) 0%, #ffffff 100%);
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh; padding: 2rem 1rem;
        }

        .auth-wrapper { width: 100%; max-width: 480px; }
        .auth-brand { text-align: center; margin-bottom: 2rem; }
        .auth-brand img { height: 48px; border-radius: 10px; }

        .auth-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            padding: 2.5rem;
        }

        .auth-header { text-align: center; margin-bottom: 2rem; }
        .auth-title { font-size: 1.75rem; font-weight: 700; color: var(--navy); margin-bottom: 0.5rem; }
        .auth-subtitle { font-size: 0.9375rem; color: var(--muted); }

        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-weight: 600; font-size: 0.875rem; color: var(--ink); margin-bottom: 0.5rem; }
        .form-input {
            width: 100%; padding: 0.75rem 1rem; border-radius: 12px; border: 1.5px solid var(--border);
            font-size: 0.9375rem; color: var(--ink); transition: var(--transition);
        }
        .form-input:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 3px rgba(11,99,246,0.1); }

        .error-message { color: var(--danger); font-size: 0.875rem; margin-top: 0.25rem; list-style: none; }

        .btn-primary {
            width: 100%; padding: 0.875rem 1.5rem; font-size: 1rem; font-weight: 600;
            color: #fff; background: linear-gradient(135deg, var(--blue) 0%, var(--blue-dark) 100%);
            border: none; border-radius: 12px; cursor: pointer;
            transition: var(--transition); box-shadow: 0 4px 12px rgba(11,99,246,0.3);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(11,99,246,0.4); }

        .auth-footer {
            text-align: center; margin-top: 1.5rem; border-top: 1px solid var(--border);
            padding-top: 1.5rem; color: var(--muted); font-size: 0.9375rem;
        }
        .link-primary { color: var(--blue); font-weight: 600; text-decoration: none; }
        .link-primary:hover { text-decoration: underline; color: var(--blue-dark); }

        @media (max-width: 640px) {
            .auth-card { padding: 2rem 1.5rem; }
            .auth-title { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-brand">
            <img src="{{ asset('assets/LOGO_PANJANG_OK.png') }}" alt="PT BPR Sarimadu">
        </div>

        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title">Reset Password</h1>
                <p class="auth-subtitle">Masukkan password baru Anda di bawah ini</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" novalidate>
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" class="form-input"
                           value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                    @error('email')
                        <ul class="error-message"><li>{{ $message }}</li></ul>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password Baru</label>
                    <input id="password" type="password" name="password" class="form-input"
                           placeholder="Minimal 8 karakter" required autocomplete="new-password">
                    @error('password')
                        <ul class="error-message"><li>{{ $message }}</li></ul>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-input"
                           placeholder="Ulangi password" required autocomplete="new-password">
                    @error('password_confirmation')
                        <ul class="error-message"><li>{{ $message }}</li></ul>
                    @enderror
                </div>

                <button type="submit" class="btn-primary" id="resetBtn">
                    Reset Password
                </button>

                <div class="auth-footer">
                    Sudah ingat password? <a href="{{ route('login') }}" class="link-primary">Masuk</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // UX kecil: disable tombol pas diklik biar ga double submit
        const form = document.querySelector('form');
        const btn  = document.getElementById('resetBtn');
        form?.addEventListener('submit', () => {
            if (btn) { btn.disabled = true; btn.textContent = 'Memproses...'; }
        });
    </script>
</body>
</html>
