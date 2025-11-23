@extends('layouts.app')

@push('head')
<style>
  .produk-hero{
    background: linear-gradient(180deg,#ffffff,#f3f5f9);
    padding: 4rem 0 3rem;
    text-align: center;
  }
  .produk-hero h1{ font-weight:800; color:var(--navy); }
  .produk-hero p{ color:var(--muted); max-width:720px; margin:auto; }

  .produk-section{
    background: var(--surface);
    border: 1px solid rgba(0,0,0,.05);
    border-radius: 16px;
    box-shadow: var(--shadow);
    padding: 2rem;
    margin-bottom: 2rem;
  }
  .produk-section h3{
    font-weight:700;
    color:var(--navy);
    border-left:4px solid var(--blue);
    padding-left:.75rem;
    margin-bottom:1rem;
  }
  .produk-section h4{ font-weight:600; margin-top:1rem; color:var(--ink); }
  ul{ padding-left:1.2rem; margin-bottom:.8rem; }

  /* Hover + Button smooth effect */
  .btn-bank:hover,
  .btn-outline-bank:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 99, 255, 0.2);
  }
</style>
@endpush

@section('hero')
<div class="produk-hero">
  <div class="container">
    <h1 class="fw-bold mb-3">Produk Deposito Sarimadu</h1>
    <p class="mb-4">Simpan dana Anda dengan aman dan dapatkan bunga menarik sesuai jangka waktu pilihan Anda.</p>

    <div class="d-flex justify-content-center">
      @auth
        @can('submit-applications')
          <a href="{{ route('deposito.create') }}"
             class="btn btn-bank btn-lg d-flex align-items-center gap-2 shadow-sm"
             style="border-radius:999px; padding:.8rem 1.8rem; transition:all .25s ease;">
            <i class="bi bi-piggy-bank fs-5"></i> Buka Deposito Sekarang
          </a>
        @else
          <a href="{{ route('dashboard') }}"
             class="btn btn-outline-bank btn-lg d-flex align-items-center gap-2 shadow-sm"
             style="border-radius:999px; padding:.8rem 1.8rem; transition:all .25s ease;">
            <i class="bi bi-speedometer2 fs-5"></i> Buka Dashboard
          </a>
        @endcan
      @else
        <a href="{{ route('login') }}"
           class="btn btn-outline-bank btn-lg d-flex align-items-center gap-2 shadow-sm"
           style="border-radius:999px; padding:.8rem 1.8rem; transition:all .25s ease;">
          <i class="bi bi-box-arrow-in-right fs-5"></i> Login untuk Buka Deposito
        </a>
      @endauth
    </div>
  </div>
</div>
@endsection

@section('content')
<section class="py-4">
  <div class="container">

    {{-- DEPOSITO --}}
    <div class="produk-section">
      <h3>Deposito</h3>
      <p>Merupakan simpanan berjangka dengan penarikan sesuai jangka waktu yang telah ditetapkan, yaitu <strong>1, 3, 6, dan 12 bulan</strong>. Deposito Sarimadu memberikan kenyamanan, keamanan, serta hasil optimal bagi setiap nasabah.</p>

      <h4>Keunggulan:</h4>
      <ul>
        <li>Suku bunga counter di atas bunga tertinggi Bank Umum.</li>
        <li>Dapat dijadikan agunan kredit dengan proses cepat.</li>
        <li>Bunga sesuai permintaan nasabah atau sampai batas LPS jika deposito di atas Rp100.000.000,-.</li>
        <li>Dijamin oleh pemerintah melalui Lembaga Penjamin Simpanan (LPS).</li>
      </ul>
    </div>

    <div class="text-center mt-4">
      <a href="{{ url('/') }}" class="btn btn-outline-bank px-4 py-2">
        <i class="bi bi-house-door"></i> Kembali ke Beranda
      </a>
    </div>
  </div>
</section>
@endsection
