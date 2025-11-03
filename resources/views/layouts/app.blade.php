<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'PT BPR Sarimadu' }}</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --navy: #003366;
      --blue: #0b63f6;
      --yellow: #f9c80e;
      --red: #d62828;
      --ink: #1e293b;
      --muted: #6b7280;
      --bg: #f9fafb;
      --surface: #ffffff;
      --radius: 14px;
      --shadow: 0 4px 20px rgba(0, 0, 0, .06);
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--bg);
      color: var(--ink);
      line-height: 1.6;
    }

    /* Navbar */
    .navbar-bank {
      background: var(--surface);
      box-shadow: var(--shadow);
      padding: 0.75rem 0;
    }

    .navbar-brand {
      font-weight: 800;
      color: var(--navy);
      display: flex;
      align-items: center;
      gap: .6rem;
    }

    .nav-link {
      color: var(--muted);
      font-weight: 600;
      margin-inline: .4rem;
      transition: .2s;
    }

    .nav-link:hover,
    .nav-link.active {
      color: var(--blue);
    }

    .btn-login {
      background: var(--blue);
      color: #fff !important;
      border-radius: 50px;
      padding: .4rem 1.2rem;
      font-weight: 600;
      text-decoration: none;
      transition: all .25s ease;
    }

    .btn-login:hover {
      background: #084dcc;
      color: #fff !important;
      text-decoration: none !important;
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(0, 99, 255, 0.2);
    }

    /* Hero */
    .hero {
      background: linear-gradient(180deg, #ffffff, #f3f5f9);
      padding: 4rem 0 5rem;
      text-align: center;
    }

    .hero h1 {
      font-weight: 800;
      font-size: clamp(2rem, 3vw + 1rem, 3rem);
      color: var(--navy);
    }

    .hero span.blue {
      color: var(--blue);
    }

    .hero span.yellow {
      color: var(--yellow);
    }

    .hero span.red {
      color: var(--red);
    }

    .hero p {
      color: var(--muted);
      font-size: 1.1rem;
      margin-bottom: 2rem;
    }

    /* Cards */
    .card-shell {
      background: var(--surface);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid rgba(0, 0, 0, .05);
      padding: 1.5rem;
      height: 100%;
      transition: .2s;
    }

    .card-shell:hover {
      transform: translateY(-3px);
    }

    .btn-bank {
      background: var(--blue);
      color: #fff;
      border-radius: 50px;
      border: none;
    }

    .btn-bank:hover {
      background: #084dcc;
      color: #fff;
    }

    .btn-outline-bank {
      border-color: var(--blue);
      color: var(--blue);
      border-radius: 50px;
    }

    .btn-outline-bank:hover {
      background: var(--blue);
      color: #fff;
    }

    footer {
      background: var(--surface);
      border-top: 1px solid rgba(0, 0, 0, .05);
      color: var(--muted);
      font-size: .9rem;
      text-align: center;
      padding: 1rem 0;
    }

    nav-item.dropdown {
      position: relative;
    }

    .nav-item.dropdown .dropdown-menu {
      display: block;
      opacity: 0;
      visibility: hidden;
      transform: translateY(10px);
      transition: all 0.25s ease;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }

    .nav-item.dropdown:hover .dropdown-menu {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .dropdown-menu.show {
      opacity: 1 !important;
      visibility: visible !important;
      transform: translateY(0) !important;
    }

    .nav-item.dropdown .dropdown-menu {
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.9);
    }
  </style>

  @stack('head')
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-bank">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
        <img src="{{ asset('assets/LOGO_PANJANG_OK.png') }}" alt="Logo PT BPR Sarimadu"
          style="height:50px; width:auto; border-radius:8px; object-fit:contain;">
      </a>

      <button class="navbar-toggler border-0" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <i class="bi bi-list" style="font-size:1.5rem; color:var(--navy);"></i>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li><a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Beranda</a></li>
          @auth
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li> 
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="dropdown-item">
                      <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </button>
                  </form>
                </li>
              </ul>
            </li>

          @endauth

          @guest
            <li><a href="{{ route('login') }}" class="btn btn-login ms-lg-3">Masuk</a></li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero (optional) -->
  @hasSection('hero')
    @yield('hero')
  @endif

  <!-- Main -->
  <main class="container my-5">
    @include('partials.alert')
    {{ $slot ?? '' }}
    @yield('content')
  </main>

  <!-- Footer -->
  <footer>
    © {{ now()->year }} PT BPR Sarimadu — Aman, Cepat, dan Mudah.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>

</html>