@extends('layouts.app')

@push('head')
    <style>
        .produk-hero {
            background: linear-gradient(180deg, #ffffff, #f3f5f9);
            padding: 4rem 0 3rem;
            text-align: center;
        }

        .produk-hero h1 {
            font-weight: 800;
            color: var(--navy);
        }

        .produk-hero p {
            color: var(--muted);
            max-width: 680px;
            margin: auto;
        }

        .produk-section {
            background: var(--surface);
            border: 1px solid rgba(0, 0, 0, .05);
            border-radius: 16px;
            box-shadow: var(--shadow);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .produk-section h3 {
            font-weight: 700;
            color: var(--navy);
            border-left: 4px solid var(--blue);
            padding-left: .75rem;
            margin-bottom: 1rem;
        }

        .produk-section h4 {
            f ont-weight: 600;
            margin-top: 1rem;
            color: var(--ink);
        }

        ul {
            padding-left: 1.2rem;
            margin-bottom: .8rem;
        }

        .btn-bank:hover,
        .btn-outline-bank:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 99, 255, 0.2);
        }

        .produk-hero {
            background: linear-gradient(180deg, #ffffff 0%, #f3f5f9 100%);
            padding: 4rem 0 3rem;
            text-align: center;
        }

        .produk-hero h1 {
            font-weight: 800;
            color: var(--navy);
        }

        .produk-hero p {
            color: var(--muted);
            max-width: 720px;
            margin: 0 auto;
        }
    </style>
@endpush

@section('hero')
<div class="produk-hero">
  <div class="container">
    <h1 class="fw-bold mb-3">Produk Kredit Sarimadu</h1>
    <p class="mb-4">Solusi permodalan yang fleksibel dan terpercaya untuk mendukung usaha mikro, kecil, menengah,
      serta sektor pertanian dan perdagangan.</p>

    <div class="d-flex justify-content-center">
      @auth
        @can('submit-applications')
          <a href="{{ route('kredit.create') }}"
             class="btn btn-bank btn-lg d-flex align-items-center gap-2 shadow-sm"
             style="border-radius:999px; padding:.8rem 1.8rem; transition:all .25s ease;">
            <i class="bi bi-send-check fs-5"></i> Ajukan Kredit Sekarang
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
          <i class="bi bi-box-arrow-in-right fs-5"></i> Login untuk Ajukan Kredit
        </a>
      @endauth
    </div>
  </div>
</div>
@endsection

@section('content')
    <section class="py-4">
        <div class="container">

            {{-- KREDIT WIRAUSAHA --}}
            <div class="produk-section">
                <h3>Kredit Wirausaha</h3>
                <p>Memberikan kemudahan bagi pelaku usaha mikro, kecil, dan menengah (UMKM) dalam memperoleh dana tunai
                    untuk mengembangkan usaha perdagangan maupun jasa.</p>

                <h4>Sektor:</h4>
                <ul>
                    <li>Perdagangan (grosir, eceran, kedai harian, kelontong, dll)</li>
                    <li>Perindustrian (konveksi, industri rumah tangga, perabot, pakan ikan, dll)</li>
                    <li>Rumah makan dan restoran</li>
                    <li>Jasa dunia usaha (penginapan, klinik, bengkel, transportasi, dll)</li>
                    <li>Jasa umum (laundry, rental komputer, internet, dll)</li>
                </ul>
            </div>

            {{-- KREDIT AGRI BISNIS --}}
            <div class="produk-section">
                <h3>Kredit Agri Bisnis</h3>
                <p>Fasilitas dana tunai untuk petani, peternak, dan nelayan guna mendukung modal kerja, pemupukan, serta
                    peningkatan produktivitas usaha di bidang pertanian, perikanan, dan peternakan.</p>

                <h4>Sektor:</h4>
                <ul>
                    <li>Perkebunan (sawit, karet, dll)</li>
                    <li>Peternakan (ayam potong, sapi, kambing, dll)</li>
                    <li>Perikanan (pembesaran dan pembibitan ikan)</li>
                </ul>
            </div>

            {{-- KREDIT INVESTASI USAHA --}}
            <div class="produk-section">
                <h3>Kredit Investasi Usaha</h3>
                <p>Memberikan dukungan finansial kepada UMKM untuk kebutuhan investasi di bidang wirausaha dan agribisnis.
                </p>

                <h4>Tujuan:</h4>
                <ul>
                    <li>Pembelian peralatan, mesin, pembangunan atau renovasi toko dan kios</li>
                    <li>Pembelian lahan pertanian (kebun, kolam, kandang, dll)</li>
                </ul>
            </div>

            {{-- KREDIT BAKULAN --}}
            <div class="produk-section">
                <h3>Kredit Bakulan</h3>
                <p>Mendukung pengusaha ekonomi lemah dalam memenuhi kebutuhan modal kerja untuk memperluas dan
                    mempertahankan usahanya.</p>

                <h4>Sasaran:</h4>
                <ul>
                    <li>Pedagang kaki lima, kedai makanan dan minuman, warung harian</li>
                    <li>Pedagang eceran dan asongan di wilayah pasar</li>
                    <li>Jasa bengkel, tukang pangkas, dan usaha mikro lainnya</li>
                </ul>
            </div>
            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="btn btn-bank px-4 py-2">
                    <i class="bi bi-house-door"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </section>
@endsection