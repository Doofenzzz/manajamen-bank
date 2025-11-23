@extends('layouts.app')

@push('head')
<style>
  .form-hero{
    background: linear-gradient(180deg, #ffffff 0%, #f3f5f9 100%);
    padding: 3rem 0 2rem;
    text-align: center;
  }
  .form-hero h1{
    font-weight:800;
    color: var(--navy);
  }
  .form-hero p{
    color: var(--muted);
    max-width: 680px;
    margin: auto;
  }

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
</style>
@endpush

@section('hero')
<div class="form-hero">
  <div class="container">
    <h1 class="fw-bold mb-3">Lengkapi Profil Nasabah</h1>
    <p>Isi data diri dengan lengkap dan benar untuk melanjutkan proses pengajuan layanan di PT BPR Sarimadu.</p>
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

      <form method="POST" action="{{ route('nasabah.store') }}" enctype="multipart/form-data" novalidate>
        @csrf

        {{-- Nama Lengkap --}}
        <div class="mb-3">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="nama" class="form-control"
                 value="{{ old('nama', auth()->user()->name) }}"
                 placeholder="Masukkan nama lengkap" required>
        </div>

        {{-- NIK & Tempat Lahir --}}
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nomor Induk Kependudukan (NIK)</label>
            <input type="text" name="nik" class="form-control"
                   value="{{ old('nik') }}" maxlength="16"
                   placeholder="16 digit NIK sesuai KTP" required>
            <small class="text-muted">Wajib 16 digit.</small>
          </div>
          <div class="col-md-6">
            <label class="form-label">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" class="form-control"
                   value="{{ old('tempat_lahir') }}" placeholder="Contoh: Pekanbaru" required>
          </div>
        </div>

        {{-- Tanggal Lahir --}}
        <div class="mb-3 mt-3">
          <label class="form-label">Tanggal Lahir</label>
          <input type="date" name="tanggal_lahir" class="form-control"
                 value="{{ old('tanggal_lahir') }}" required>
        </div>

        {{-- Alamat --}}
        <div class="mb-3">
          <label class="form-label">Alamat Lengkap</label>
          <textarea name="alamat" class="form-control" rows="3"
                    placeholder="Masukkan alamat sesuai KTP" required>{{ old('alamat') }}</textarea>
        </div>

        {{-- Nomor HP --}}
        <div class="mb-3">
          <label class="form-label">Nomor HP Aktif</label>
          <input type="text" name="no_hp" class="form-control"
                 value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890" required>
        </div>

        {{-- Foto KTP --}}
        <div class="mb-3">
          <label class="form-label">Upload Foto KTP</label>
          <input type="file" name="foto_ktp" class="form-control" accept="image/*" required>
          <small class="text-muted">Pastikan foto KTP terlihat jelas dan tidak blur (format: JPG, PNG).</small>
        </div>

        {{-- Tombol --}}
        <div class="text-center mt-4">
          <a href="{{ route('dashboard') }}" class="btn btn-outline-bank px-4 py-2 me-2">
            <i class="bi bi-arrow-left"></i> Batal
          </a>
          <button type="submit" class="btn btn-bank px-4 py-2">
            <i class="bi bi-save2"></i> Simpan Data
          </button>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
