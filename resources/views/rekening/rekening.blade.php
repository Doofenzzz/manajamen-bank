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
    <h1 class="fw-bold mb-3">Produk Tabungan Sarimadu</h1>
    <p class="mb-4">Nikmati kemudahan menabung dan bertransaksi di seluruh jaringan Bank Sarimadu.</p>

    <div class="d-flex justify-content-center">
      @auth
        @can('submit-applications')
          <a href="{{ route('rekening.create') }}"
             class="btn btn-bank btn-lg d-flex align-items-center gap-2 shadow-sm"
             style="border-radius:999px; padding:.8rem 1.8rem; transition:all .25s ease;">
            <i class="bi bi-wallet2 fs-5"></i> Buka Rekening Sekarang
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
          <i class="bi bi-box-arrow-in-right fs-5"></i> Login untuk Buka Rekening
        </a>
      @endauth
    </div>
  </div>
</div>
@endsection

@section('content')
<section class="py-4">
  <div class="container">

    {{-- TABUNGAN SARIMADU --}}
    <div class="produk-section">
      <h3>Tabungan Sarimadu</h3>
      <p>Merupakan fasilitas simpanan untuk perorangan dan kelompok dengan berbagai kemudahan transaksi dan biaya ringan.</p>

      <h4>Keunggulan:</h4>
      <ul>
        <li>Kemudahan bertransaksi di seluruh kantor Bank Sarimadu dan Bank Umum dengan fasilitas <strong>ONLINE</strong> (Transfer/menerima Transfer dari Bank lain).</li>
        <li>Biaya administrasi tidak mengurangi saldo tabungan.</li>
        <li>Buka rekening tabungan hanya <strong>Rp 50.000,-</strong>.</li>
        <li>Dijamin oleh Pemerintah (LPS).</li>
      </ul>
    </div>

    {{-- TABUNGAN VISTA --}}
    <div class="produk-section">
      <h3>Tabungan Vista</h3>
      <p>Merupakan simpanan untuk dana-dana Pemerintah, Sekolah, Perguruan Tinggi, Bank, Koperasi, Yayasan, Organisasi, dan Badan Hukum lainnya.</p>

      <h4>Keunggulan:</h4>
      <ul>
        <li>Kemudahan bertransaksi di seluruh kantor Bank Sarimadu dan Bank Umum dengan fasilitas <strong>ONLINE</strong>.</li>
        <li>Fasilitas antar jemput dana sesuai kesepakatan.</li>
        <li>Mempermudah pembayaran gaji secara auto debet.</li>
        <li>Memberikan bunga di atas suku bunga Giro Bank Umum.</li>
        <li>Dijamin oleh Pemerintah (LPS).</li>
      </ul>
    </div>

    {{-- SIMPANAN PELAJAR --}}
    <div class="produk-section">
      <h3>Simpanan Pelajar (SimPel)</h3>
      <p>Merupakan simpanan perorangan atau kelompok untuk anak sekolah mulai dari PAUD, TK, SD, SMP, hingga SMA, yang bertujuan menumbuhkan budaya menabung sejak dini.</p>

      <h4>Keunggulan:</h4>
      <ul>
        <li>Buka rekening tabungan hanya <strong>Rp 5.000,-</strong> dan setor berikutnya mulai dari <strong>Rp 1.000,-</strong>.</li>
        <li>Tidak ada biaya administrasi bulanan.</li>
        <li>Bisa antar jemput dana ke sekolah.</li>
        <li>Dijamin oleh Pemerintah (LPS).</li>
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
