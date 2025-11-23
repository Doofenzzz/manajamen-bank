@extends('layouts.app')

@push('head')
<style>
  /* styling khusus landing (light, elegan) */
  .hero-wrap{
    background: linear-gradient(180deg, #ffffff 0%, #f3f5f9 100%);
    padding: 4rem 0 5rem;
  }
  .hero-title{
    font-weight: 800;
    font-size: clamp(2rem, 3vw + 1rem, 3.2rem);
    color: var(--navy);
    line-height: 1.2;
    letter-spacing: .2px;
  }
  .hero-sub{
    color: var(--muted);
    max-width: 720px;
  }
  .cta-wrap .btn{ border-radius: 999px; padding:.7rem 1.4rem; font-weight:600; }

  .service-card{
    background: var(--surface);
    border: 1px solid rgba(0,0,0,.06);
    border-radius: 16px;
    box-shadow: var(--shadow);
    padding: 1.25rem;
  }
  .service-item{ padding:.85rem 0; display:flex; gap:.75rem; }
  .service-item + .service-item{ border-top:1px solid rgba(0,0,0,.06); }
  .service-item i{ font-size:1.15rem; width:28px; text-align:center; }

  .feature-card{
    background: var(--surface);
    border: 1px solid rgba(0,0,0,.06);
    border-radius: 16px;
    box-shadow: var(--shadow);
    padding: 1.5rem;
    height: 100%;
    transition: transform .2s ease;
  }
  .feature-card:hover{ transform: translateY(-3px); }

  .step-card{ @apply feature-card; } /* mental note: hanya komentar; ignore di blade */
  .step-badge{
    display:inline-flex; align-items:center; justify-content:center;
    width:32px;height:32px; border-radius:999px; background: var(--blue); color:#fff; font-weight:700;
  }

  .muted{ color: var(--muted); }
</style>
@endpush

@section('hero')
<div class="hero-wrap">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-7">
        <h1 class="hero-title">
          Solusi Perbankan Digital <span style="color:var(--blue)">Aman</span>,
          <span style="color:var(--yellow)">Cepat</span>, & <span style="color:var(--red)">Mudah</span>.
        </h1>
        <p class="hero-sub mt-3">
          Buka rekening, ajukan kredit, dan kelola deposito langsung dari genggamanmu.
          Layanan modern dengan standar keamanan perbankan.
        </p>

        <div class="cta-wrap d-flex gap-2 flex-wrap mt-4">
          <a href="{{ route('register') }}" class="btn btn-bank">
            <i class="bi bi-person-plus"></i> Daftar Sekarang
          </a>
          <a href="{{ route('login') }}" class="btn btn-outline-bank">
            <i class="bi bi-box-arrow-in-right"></i> Masuk
          </a>
        </div>

        <div class="d-flex gap-4 flex-wrap mt-4 muted small">
          <span><i class="bi bi-shield-lock me-1"></i>Enkripsi & KYC</span>
          <span><i class="bi bi-clock-history me-1"></i>Layanan 24/7</span>
          <span><i class="bi bi-graph-up-arrow me-1"></i>Bunga kompetitif</span>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="service-card">
          <div class="d-flex align-items-center gap-2 mb-3 rounded-pill px-3 py-2" 
            style="background: linear-gradient(90deg, rgba(0,99,255,0.08), rgba(249,200,14,0.08), rgba(214,40,40,0.08));">
          <div class="d-flex align-items-center gap-2">
            <span style="width:12px; height:12px; background:var(--blue); border-radius:50%; display:inline-block;"></span>
            <span style="width:12px; height:12px; background:var(--yellow); border-radius:50%; display:inline-block;"></span>
            <span style="width:12px; height:12px; background:var(--red); border-radius:50%; display:inline-block;"></span>
          </div>
          <strong class="ms-2" style="color:var(--navy);">Pilihan Layanan</strong>
        </div>


          <div class="service-item">
            <i class="bi bi-wallet2 text-primary"></i>
            <div>
              <div class="fw-semibold">Buka Rekening</div>
              <div class="muted small">Tabungan reguler, pelajar, hingga bisnis.</div>
              <a href="{{ route('rekening.create') }}" class="link-primary small">Mulai pengajuan</a>
            </div>
          </div>

          <div class="service-item">
            <i class="bi bi-cash-stack" style="color:var(--yellow)"></i>
            <div>
              <div class="fw-semibold">Ajukan Kredit</div>
              <div class="muted small">Bunga transparan, tenor fleksibel.</div>
              <a href="{{ route('kredit.create') }}" class="link-primary small">Ajukan sekarang</a>
            </div>
          </div>

          <div class="service-item">
            <i class="bi bi-piggy-bank" style="color:var(--red)"></i>
            <div>
              <div class="fw-semibold">Deposito</div>
              <div class="muted small">Imbal hasil menarik untuk danamu.</div>
              <a href="{{ route('deposito.create') }}" class="link-primary small">Buka deposito</a>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- /row -->
  </div>
</div>
@endsection

@section('content')
  {{-- Kenapa Kami --}}
  <section>
    <h3 class="text-center fw-bold mb-4">Kenapa PT BPR Sarimadu?</h3>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="feature-card">
          <i class="bi bi-shield-check text-primary fs-3 mb-2"></i>
          <h5 class="fw-semibold mb-1">Keamanan Kelas Bank</h5>
          <p class="muted small mb-0">KYC, enkripsi data, dan compliance terbaik.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <i class="bi bi-lightning-charge text-warning fs-3 mb-2"></i>
          <h5 class="fw-semibold mb-1">Proses Kilat</h5>
          <p class="muted small mb-0">Pengajuan online tanpa perlu ke cabang.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <i class="bi bi-people text-danger fs-3 mb-2"></i>
          <h5 class="fw-semibold mb-1">Dukungan Ramah</h5>
          <p class="muted small mb-0">Tim siap bantu kamu setiap saat.</p>
        </div>
      </div>
    </div>
  </section>

  {{-- Cara Mulai --}}
  <section class="mt-5">
    <h4 class="fw-bold mb-3 text-center">Cara Mulai</h4>
    <div class="row g-3">
      <div class="col-md-4">
        <div class="feature-card h-100">
          <div class="d-flex align-items-start gap-3">
            <span class="step-badge">1</span>
            <div>
              <div class="fw-semibold">Buat Akun</div>
              <p class="muted small mb-2">Registrasi cepat untuk akses layanan.</p>
              <a href="{{ route('register') }}" class="btn btn-sm btn-bank">Daftar</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card h-100">
          <div class="d-flex align-items-start gap-3">
            <span class="step-badge">2</span>
            <div>
              <div class="fw-semibold">Lengkapi Profil Nasabah</div>
              <p class="muted small mb-2">Isi data KYC dasar untuk verifikasi.</p>
              <a href="{{ route('nasabah.create') }}" class="btn btn-sm btn-outline-bank">Lengkapi Profil</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card h-100">
          <div class="d-flex align-items-start gap-3">
            <span class="step-badge">3</span>
            <div>
              <div class="fw-semibold">Ajukan Layanan</div>
              <p class="muted small mb-2">Pilih Rekening/Kredit/Deposito sesuai kebutuhan.</p>
              <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('rekening.create') }}" class="btn btn-sm btn-bank">Rekening</a>
                <a href="{{ route('kredit.create') }}" class="btn btn-sm btn-outline-bank">Kredit</a>
                <a href="{{ route('deposito.create') }}" class="btn btn-sm btn-outline-bank">Deposito</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
