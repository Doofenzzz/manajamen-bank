@extends('layouts.app')

@push('head')
<style>
  .form-hero{
    background: linear-gradient(180deg, #ffffff 0%, #f3f5f9 100%);
    padding: 3rem 0 2rem;
    text-align: center;
  }
  .form-hero h1{ font-weight:800; color: var(--navy); }
  .form-hero p{ color: var(--muted); max-width: 680px; margin: auto; }

  .form-container{
    background: var(--surface);
    border: 1px solid rgba(0,0,0,.05);
    border-radius: 16px;
    box-shadow: var(--shadow);
    padding: 2rem;
  }
  .form-label{ font-weight:600; color: var(--ink); }
  .form-control, .form-select{
    border-radius: .75rem;
    border: 1px solid rgba(0,0,0,.15);
    padding: .65rem .9rem;
  }
  .btn-bank:hover{
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 99, 255, 0.2);
  }
  input[type=number] {
    -moz-appearance: textfield;
  }
</style>
@endpush

@section('hero')
<div class="form-hero">
  <div class="container">
    <h1 class="fw-bold mb-3">Pengajuan Rekening Tabungan</h1>
    <p>Buka rekening Sarimadu dengan cepat dan mudah. Kalau profil nasabahmu sudah lengkap, data identitas akan terisi otomatis.</p>
  </div>
</div>
@endsection

@section('content')
<section class="py-4">
  <div class="container">
    <div class="form-container mx-auto" style="max-width: 720px;">

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('rekening.store') }}" enctype="multipart/form-data" novalidate>
        @csrf

        {{-- Jika user sudah punya profil nasabah, tampilkan ringkasan & hidden input --}}
        @if(auth()->user()->nasabah)
          <input type="hidden" name="nasabah_id" value="{{ auth()->user()->nasabah->id }}">
          <div class="alert alert-info d-flex align-items-center gap-2">
            <i class="bi bi-person-badge"></i>
            <div>
              Pengajuan atas nama: <strong>{{ auth()->user()->nasabah->nama }}</strong><br>
              NIK: <strong>{{ auth()->user()->nasabah->nik }}</strong>
            </div>
          </div>
        @else
          {{-- Kalau belum punya profil nasabah, minta data minimal (light mode) --}}
          <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            Kamu belum punya profil nasabah. Isi data singkat di bawah ini atau
            <a href="{{ route('nasabah.create') }}" class="fw-semibold">lengkapi profil lengkap</a>.
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nama Lengkap (Sesuai KTP)</label>
              <input type="text" name="nama" class="form-control" value="{{ old('nama', auth()->user()->name) }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Tanggal Lahir</label>
              <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" required>
            </div>

            <div class="col-12">
              <label class="form-label">Alamat</label>
              <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat') }}</textarea>
            </div>

            <div class="col-md-6">
              <label class="form-label">NIK</label>
              <input type="text" name="nik" class="form-control" value="{{ old('nik') }}" maxlength="16" placeholder="16 digit" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Foto KTP</label>
              <input type="file" name="foto_ktp" class="form-control" accept="image/*" required>
            </div>
          </div>
          <hr class="my-4">
        @endif

        {{-- Produk Rekening --}}
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Jenis Tabungan</label>
            <select name="jenis_tabungan" class="form-select" required>
              <option value="" selected disabled>Pilih jenis</option>
              <option value="Tabungan Sarimadu" @selected(old('jenis_tabungan')==='Tabungan Sarimadu')>Tabungan Sarimadu</option>
              <option value="Tabungan Vista" @selected(old('jenis_tabungan')==='Tabungan Vista')>Tabungan Vista</option>
              <option value="Simpanan Pelajar (SimPel)" @selected(old('jenis_tabungan')==='Simpanan Pelajar (SimPel)')>Simpanan Pelajar (SimPel)</option>
              <option value="Tabungan Qurban" @selected(old('jenis_tabungan')==='Tabungan Qurban')>Tabungan Qurban</option>
              <option value="Tabungan Umrah" @selected(old('jenis_tabungan')==='Tabungan Umrah')>Tabungan Umrah</option>
              <option value="Tabungan Kredit" @selected(old('jenis_tabungan')==='Tabungan Umrah')>Tabungan Kredit</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Unit Kerja Pembukaan</label>
            <select name="unit_kerja_pembukaan_tabungan" class="form-select" required>
              <option value="" selected disabled>Unit Kerja</option>
              <option value="Pusat Bangkinang" @selected(old('unit_kerja_pembukaan_tabungan')==='Pusat Bangkinang')>Pusat Bangkinang</option>
              <option value="Cabang Ujungbatu" @selected(old('unit_kerja_pembukaan_tabungan')==='Cabang Ujungbatu')>Cabang Ujungbatu</option>
              <option value="Cabang Pekanbaru" @selected(old('unit_kerja_pembukaan_tabungan')==='Cabang Pekanbaru')>Cabang Pekanbaru</option>
              <option value="Cabang Lipatkain" @selected(old('unit_kerja_pembukaan_tabungan')==='Cabang Lipatkain')>Cabang Lipatkain</option>
              <option value="Cabang Flamboyan" @selected(old('unit_kerja_pembukaan_tabungan')==='Cabang Flamboyan')>Cabang Flamboyan</option>
              <option value="Kas Pasir Pengaraian" @selected(old('unit_kerja_pembukaan_tabungan')==='Kas Pasir Pengaraian')>Kas Pasir Pengaraian</option>
              <option value="Kas Dalu-dalu" @selected(old('unit_kerja_pembukaan_tabungan')==='Kas Dalu-dalu')>Kas Dalu-dalu</option>
              <option value="Kas Kabun" @selected(old('unit_kerja_pembukaan_tabungan')==='Kas Kabun')>Kas Kabun</option>
              <option value="Kas Kota Lama" @selected(old('unit_kerja_pembukaan_tabungan')==='Kas Kota Lama')>Kas Kota Lama</option>
              <option value="Kas Sukaramai" @selected(old('unit_kerja_pembukaan_tabungan')==='Kas Sukaramai')>Kas Sukaramai</option>
              <option value="Kas Tambang" @selected(old('unit_kerja_pembukaan_tabungan')==='Kas Tambang')>Kas Tambang</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Setoran Awal (Rp)</label>
            <input type="number" name="setoran_awal" class="form-control" value="{{ old('setoran_awal') }}" placeholder="Contoh: 50000" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Kartu ATM</label>
            <select name="kartu_atm" class="form-select">
              <option value="ya" @selected(old('kartu_atm')==='ya')>Ya, saya ingin kartu ATM</option>
              <option value="tidak" @selected(old('kartu_atm')==='tidak')>Tidak sekarang</option>
            </select>
          </div>
        </div>

        <div class="text-center mt-4">
          <a href="{{ route('dashboard') }}" class="btn btn-outline-bank px-4 py-2 me-2">
            <i class="bi bi-arrow-left"></i> Batal
          </a>
          <button class="btn btn-bank px-4 py-2">
            <i class="bi bi-send"></i> Ajukan
          </button>
        </div>
      </form>

    </div>
  </div>
</section>
@endsection
