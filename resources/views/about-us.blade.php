@extends('layouts.app')

@push('head')
<style>
  .about-hero{
    background: linear-gradient(180deg, #ffffff 0%, #f3f5f9 100%);
    padding: 4rem 0 3rem;
    text-align: center;
  }
  .about-hero h1{ font-weight:800; color:var(--navy); }
  .about-hero p{ color:var(--muted); max-width:680px; margin:auto; }

  .about-section{
    background: var(--surface);
    border:1px solid rgba(0,0,0,.05);
    border-radius:16px;
    box-shadow: var(--shadow);
    padding:2rem;
    margin-bottom:2rem;
  }
  .about-section h3{
    font-weight:700;
    color:var(--navy);
    border-left:4px solid var(--blue);
    padding-left:.75rem;
    margin-bottom:1rem;
  }
  .about-section p{ color:var(--ink); text-align:justify; margin-bottom:.8rem; }
</style>
@endpush

@section('hero')
<div class="about-hero">
  <div class="container">
    <h1 class="fw-bold">Tentang Kami</h1>
    <p>Kenali lebih dekat <strong>PT BPR Sarimadu Perseroda</strong> — lembaga keuangan daerah yang berkomitmen terhadap pertumbuhan ekonomi masyarakat melalui layanan perbankan yang mudah, aman, dan cepat.</p>
  </div>
</div>
@endsection

@section('content')
<section class="py-4">
  <div class="container">
    <div class="text-center mb-5">
      <img src="{{ asset('assets/WEB-KANTOR.jpeg') }}" alt="Bank Illustration"
           class="img-fluid rounded-4 shadow-sm mb-4" style="max-height:380px; object-fit:cover;">
    </div>

    {{-- SEJARAH SINGKAT --}}
    <div class="about-section">
      <h3>A. Sejarah Singkat</h3>
      <p>PT BPR Sarimadu Perseroda pada awalnya merupakan salah satu Badan Kredit Kecamatan (BKK). Melalui deregulasi perbankan tanggal 28 Oktober 1988 (Pakto ’88), BKK Ujungbatu dipersiapkan untuk menjadi Bank Perkreditan Rakyat (BPR).</p>

      <p>Gubernur Provinsi Riau dengan Surat Keputusan Nomor 539/PSD/86.18 tanggal 18 Desember 1988 menginstruksikan kepada Bupati untuk mempersiapkan pendirian Bank Perkreditan Rakyat (BPR) di wilayah masing-masing Kabupaten. Selanjutnya dengan persetujuan DPRD Kabupaten Kampar, Pemda Kabupaten Kampar membentuk BPR ini menjadi Perusahaan Daerah (PD) melalui Peraturan Daerah (Perda) Nomor 03 Tahun 1989.</p>

      <p>Atas persetujuan Bank Indonesia, Menteri Keuangan memberikan izin operasional melalui SK Nomor Kep.067/KM.13/92 tanggal 16 Maret 1992 tentang Pemberian izin usaha PD. Bank Perkreditan Rakyat Ujungbatu. Dengan demikian, BKK Ujungbatu resmi beralih status menjadi Bank PD. BPR Ujungbatu.</p>

      <p>Kemudian, dengan Perda Kabupaten Kampar Nomor 9 Tahun 2003, nama Bank PD. BPR Ujungbatu berubah menjadi Bank PD. BPR Sarimadu. Pada tahun 2020 dilakukan perubahan bentuk hukum menjadi Perusahaan Perseroan Daerah melalui Perda Kabupaten Kampar Nomor 10 Tahun 2020.</p>

      <p>Berdasarkan Surat Keputusan Otoritas Jasa Keuangan (OJK) Provinsi Riau Nomor KEP-15/KO.053/2022 tanggal 5 April 2022, PD. BPR Sarimadu resmi beralih izin usaha menjadi <strong>PT BPR Sarimadu (Perseroda)</strong>.</p>
    </div>

    {{-- KEPEMILIKAN SAHAM --}}
    <div class="about-section">
      <h3>B. Kepemilikan Saham</h3>
      <p>Pada awalnya, kepemilikan saham PT BPR Sarimadu Perseroda berasal dari Pemda Kabupaten Kampar dan BPD Riau (Bank Riau Kepri) dengan perbandingan kepemilikan 85% untuk Kabupaten Kampar dan 15% untuk Bank Riau.</p>

      <p>Seiring perubahan modal dasar, melalui Rapat Umum Pemegang Saham tahun 2000, Bank Riau memberikan kesempatan kepada Pemda Kampar untuk secara bertahap memiliki seluruh modal PT BPR Sarimadu (Perseroda). Dengan ditetapkannya Perda Nomor 09 Tahun 2003, kepemilikan modal PT BPR Sarimadu Perseroda sepenuhnya dimiliki oleh Pemda Kabupaten Kampar.</p>
    </div>

    {{-- KEGIATAN USAHA --}}
    <div class="about-section">
      <h3>C. Kegiatan Usaha</h3>
      <p>PT BPR Sarimadu Perseroda merupakan salah satu <strong>Badan Usaha Milik Daerah (BUMD)</strong> Kabupaten Kampar yang bergerak di bidang lembaga keuangan perbankan (Bank Perkreditan Rakyat). Dengan izin operasional dari Menteri Keuangan Republik Indonesia Nomor Kep.067/KM.13/92 tanggal 16 Maret 1992, PT BPR Sarimadu Perseroda berfokus pada layanan kredit mikro, simpanan masyarakat, serta produk keuangan berbasis inklusi.</p>
    </div>

    {{-- CTA --}}
    <div class="text-center mt-5">
      <a href="{{ url('/') }}" class="btn btn-bank px-4 py-2">
        <i class="bi bi-house-door"></i> Kembali ke Beranda
      </a>
    </div>
  </div>
</section>
@endsection
