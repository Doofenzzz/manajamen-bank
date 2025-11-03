@php
    // Ambil dari 2 sumber: otp_email (flash/persist) atau registration_data.email (pasti ada selama sesi OTP)
    $otpEmail = session('otp_email') ?? data_get(session('registration_data'), 'email');

    // Optional: masking biar rapi (boleh buang kalau mau full email)
    if ($otpEmail) {
        [$local, $domain] = explode('@', $otpEmail, 2);
        $maskLocal = strlen($local) > 2 ? substr($local, 0, 2) . str_repeat('*', strlen($local) - 2) : $local . '*';
        $displayEmail = $maskLocal . '@' . $domain;
    } else {
        $displayEmail = 'email Anda';
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi OTP - {{ config('app.name', 'PT BPR Sarimadu') }}</title>

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
        }

        .auth-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            padding: 2.5rem;
        }

        .icon-wrapper {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, var(--blue-light) 0%, #e0f2fe 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-wrapper svg {
            width: 32px;
            height: 32px;
            color: var(--blue);
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
            line-height: 1.5;
        }

        .email-badge {
            display: inline-block;
            background: var(--blue-light);
            color: var(--blue-dark);
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .otp-container {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            margin: 2rem 0;
        }

        .otp-input {
            width: 56px;
            height: 56px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            border: 2px solid var(--border);
            border-radius: 12px;
            transition: var(--transition);
            font-family: inherit;
        }

        .otp-input:focus {
            outline: none;
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(11, 99, 246, 0.1);
        }

        .otp-input::-webkit-inner-spin-button,
        .otp-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
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
            font-family: inherit;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(11, 99, 246, 0.4);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .resend-section {
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
            cursor: pointer;
        }

        .link-primary:hover {
            color: var(--blue-dark);
            text-decoration: underline;
        }

        .link-primary.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .error-message {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.875rem;
            color: #dc2626;
        }

        .success-message {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.875rem;
            color: #16a34a;
        }

        .otp-input::-webkit-outer-spin-button,
        .otp-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .otp-input[type=number] {
            -moz-appearance: textfield;
        }

        .otp-input {
            width: 45px;
            height: 50px;
            text-align: center;
            font-size: 1.4rem;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            transition: all .2s ease;
        }

        .otp-input:focus {
            border-color: #0b63f6;
            box-shadow: 0 0 0 3px rgba(11, 99, 246, 0.2);
        }

        @media (max-width: 640px) {
            .auth-card {
                padding: 2rem 1.5rem;
            }

            .otp-input {
                width: 48px;
                height: 48px;
                font-size: 1.25rem;
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
                <div class="icon-wrapper">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                    </svg>
                </div>

                <div class="auth-header">
                    <h1 class="auth-title">Verifikasi Email</h1>
                    <p class="auth-subtitle">
                        Masukkan 6 digit kode OTP yang telah dikirim ke
                        <span class="email-badge">{{ $displayEmail }}</span>
                    </p>
                </div>

                <form method="POST" action="{{ route('verify.otp') }}" id="otpForm">
                    @csrf

                    <div class="otp-container">
                        <input type="number" class="otp-input" maxlength="1" name="otp[]" required>
                        <input type="number" class="otp-input" maxlength="1" name="otp[]" required>
                        <input type="number" class="otp-input" maxlength="1" name="otp[]" required>
                        <input type="number" class="otp-input" maxlength="1" name="otp[]" required>
                        <input type="number" class="otp-input" maxlength="1" name="otp[]" required>
                        <input type="number" class="otp-input" maxlength="1" name="otp[]" required>
                    </div>

                    @if(session('error'))
                        <div class="error-message">{{ session('error') }}</div>
                    @endif

                    @if(session('success'))
                        <div class="success-message">{{ session('success') }}</div>
                    @endif

                    <button type="submit" class="btn-primary" id="verifyBtn">
                        Verifikasi
                    </button>
                </form>

                <div class="resend-section">
                    Tidak menerima kode?
                    <a href="#" class="link-primary" id="resendLink" onclick="resendOTP(event)">
                        Kirim ulang
                    </a>
                    <span id="countdown" style="display: none;"></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-focus dan auto-tab untuk OTP input
        const otpInputs = document.querySelectorAll('.otp-input');

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });

            // Prevent more than 1 character
            input.addEventListener('input', (e) => {
                if (e.target.value.length > 1) {
                    e.target.value = e.target.value.slice(0, 1);
                }
            });
        });

        // Auto-focus first input
        if (otpInputs.length > 0) {
            otpInputs[0].focus();
        }

        // Resend OTP dengan countdown
        let countdownTimer;

        function resendOTP(event) {
            event.preventDefault();

            const resendLink = document.getElementById('resendLink');
            const countdown = document.getElementById('countdown');

            // Disable resend link
            resendLink.classList.add('disabled');

            // Send request
            fetch('{{ route("resend.otp") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show countdown
                        let timeLeft = 60;
                        resendLink.style.display = 'none';
                        countdown.style.display = 'inline';

                        countdownTimer = setInterval(() => {
                            countdown.textContent = `Kirim ulang dalam ${timeLeft}s`;
                            timeLeft--;

                            if (timeLeft < 0) {
                                clearInterval(countdownTimer);
                                countdown.style.display = 'none';
                                resendLink.style.display = 'inline';
                                resendLink.classList.remove('disabled');
                            }
                        }, 1000);
                    }
                });
        }
    </script>
</body>

</html>