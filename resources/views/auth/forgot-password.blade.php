<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Password - {{ config('app.name', 'PT BPR Sarimadu') }}</title>

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
            --success: #16a34a;
            --danger: #dc2626;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .auth-container {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 2rem 1rem; background: linear-gradient(135deg, var(--blue-light) 0%, #ffffff 100%);
        }
        .auth-wrapper { width: 100%; max-width: 480px; }
        .auth-brand { text-align: center; margin-bottom: 2rem; }
        .auth-brand img { height: 48px; width: auto; border-radius: 10px; object-fit: contain; }

        .auth-card {
            background: var(--surface); border: 1px solid var(--border); border-radius: 20px;
            box-shadow: var(--shadow-lg); padding: 2.5rem;
        }
        .auth-header { text-align: center; margin-bottom: 2rem; }
        .auth-title { font-size: 1.75rem; font-weight: 700; color: var(--navy); margin: 0 0 0.5rem 0; letter-spacing: -0.02em; }
        .auth-subtitle { font-size: 0.9375rem; color: var(--muted); margin: 0; }

        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 600; color: var(--ink); margin-bottom: 0.5rem; }
        .form-input {
            width: 100%; padding: 0.75rem 1rem; font-size: 0.9375rem; border: 1.5px solid var(--border);
            border-radius: 12px; background: var(--surface); color: var(--ink); transition: var(--transition); font-family: inherit;
        }
        .form-input:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 3px rgba(11, 99, 246, 0.1); }
        .form-input::placeholder { color: #9ca3af; }

        .btn-primary {
            width: 100%; padding: 0.875rem 1.5rem; font-size: 1rem; font-weight: 600; color: #ffffff;
            background: linear-gradient(135deg, var(--blue) 0%, var(--blue-dark) 100%); border: none; border-radius: 12px;
            cursor: pointer; transition: var(--transition); box-shadow: 0 4px 12px rgba(11, 99, 246, 0.3); font-family: inherit;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(11, 99, 246, 0.4); }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        .auth-footer { text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border); font-size: 0.9375rem; color: var(--muted); }
        .link-primary { color: var(--blue); text-decoration: none; font-weight: 600; transition: var(--transition); }
        .link-primary:hover { color: var(--blue-dark); text-decoration: underline; }

        .error-message { margin-top: 0.5rem; font-size: 0.875rem; color: var(--danger); list-style: none; }
        .alert {
            padding: 0.875rem 1rem; border-radius: 12px; font-size: 0.9375rem; margin-bottom: 1rem; border: 1px solid;
        }
        .alert-success { background: #ecfdf5; color: #065f46; border-color: #a7f3d0; }
        .alert-danger { background: #fef2f2; color: #991b1b; border-color: #fecaca; }

        @media (max-width: 640px) {
            .auth-card { padding: 2rem 1.5rem; }
            .auth-title { font-size: 1.5rem; }
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
                    <h1 class="auth-title">Lupa Password</h1>
                    <p class="auth-subtitle">Masukkan email Anda, kami kirim link reset password ke sana.</p>
                </div>

                {{-- Session Status (flash dari password.email) --}}
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" novalidate>
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
                            <ul class="error-message"><li>{{ $message }}</li></ul>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary" id="sendResetBtn">
                        Kirim Link Reset Password
                    </button>

                    <div class="auth-footer">
                        Ingat password? <a href="{{ route('login') }}" class="link-primary">Masuk</a>
                        &nbsp;•&nbsp;
                        Belum punya akun? <a href="{{ route('register') }}" class="link-primary">Daftar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Optional UX: disable button sebentar biar ga double submit
        const form = document.querySelector('form');
        const btn  = document.getElementById('sendResetBtn');
        form?.addEventListener('submit', () => {
            if (btn) { btn.disabled = true; btn.textContent = 'Mengirim...'; }
        });
    </script>
</body>
</html>
