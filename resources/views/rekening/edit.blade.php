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
  input[type=number] { -moz-appearance: textfield; }
</style>
@endpush

@section('hero')
<div class="form-hero">
  <div class="container">
    <h1 class="fw-bold mb-3">Edit Pengajuan Rekening</h1>
    <p>Update data pengajuanmu biar sesuai kebutuhan. Struktur & style-nya disamain kayak form pengajuan baru.</p>
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

      {{-- Ringkasan Nasabah (read-only) biar konsisten sama create --}}
      @php($nas = $rekening->nasabah ?? null)
      @if($nas)
        <div class="alert alert-info d-flex align-items-center gap-2">
          <i class="bi bi-person-badge"></i>
          <div>
            Pengajuan atas nama: <strong>{{ $nas->nama }}</strong><br>
            NIK: <strong>{{ $nas->nik }}</strong>
          </div>
        </div>
      @endif

      <form method="POST" action="{{ route('rekening.update', $rekening) }}" novalidate>
        @csrf
        @method('PATCH')

        {{-- Produk Rekening (samain list & styling kayak create) --}}
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Jenis Tabungan <span class="text-danger">*</span></label>
            <select name="jenis_tabungan" class="form-select" required>
              <option value="" disabled>Pilih jenis</option>
              <option value="Tabungan Sarimadu" @selected(old('jenis_tabungan', $rekening->jenis_tabungan)==='Tabungan Sarimadu')>Tabungan Sarimadu</option>
              <option value="Tabungan Vista" @selected(old('jenis_tabungan', $rekening->jenis_tabungan)==='Tabungan Vista')>Tabungan Vista</option>
              <option value="Simpanan Pelajar (SimPel)" @selected(old('jenis_tabungan', $rekening->jenis_tabungan)==='Simpanan Pelajar (SimPel)')>Simpanan Pelajar (SimPel)</option>
              <option value="Tabungan Qurban" @selected(old('jenis_tabungan', $rekening->jenis_tabungan)==='Tabungan Qurban')>Tabungan Qurban</option>
              <option value="Tabungan Umrah" @selected(old('jenis_tabungan', $rekening->jenis_tabungan)==='Tabungan Umrah')>Tabungan Umrah</option>
              <option value="Tabungan Kredit" @selected(old('jenis_tabungan', $rekening->jenis_tabungan)==='Tabungan Kredit')>Tabungan Kredit</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Unit Kerja Pembukaan <span class="text-danger">*</span></label>
            <select name="unit_kerja_pembukaan_tabungan" class="form-select" required>
              <option value="" disabled>Unit Kerja</option>
              @php($unit = old('unit_kerja_pembukaan_tabungan', $rekening->unit_kerja_pembukaan_tabungan))
              @foreach ([
                'Pusat Bangkinang','Cabang Ujungbatu','Cabang Pekanbaru','Cabang Lipatkain','Cabang Flamboyan',
                'Kas Pasir Pengaraian','Kas Dalu-dalu','Kas Kabun','Kas Kota Lama','Kas Sukaramai','Kas Tambang'
              ] as $u)
                <option value="{{ $u }}" {{ $unit===$u ? 'selected' : '' }}>{{ $u }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Setoran Awal (Rp) <span class="text-danger">*</span></label>
            <input
              type="number"
              name="setoran_awal"
              class="form-control"
              value="{{ old('setoran_awal', $rekening->setoran_awal) }}"
              min="50000"
              placeholder="Minimal 50000"
              required>
            <small class="text-muted">Minimal setoran awal Rp 50.000</small>
          </div>

          <div class="col-md-6">
            <label class="form-label">Kartu ATM</label>
            <select name="kartu_atm" class="form-select">
              @php($atm = old('kartu_atm', $rekening->kartu_atm))
              <option value="ya" {{ $atm==='ya' ? 'selected' : '' }}>Ya, saya ingin kartu ATM</option>
              <option value="tidak" {{ $atm==='tidak' ? 'selected' : '' }}>Tidak sekarang</option>
            </select>
          </div>
        </div>

        <div class="text-center mt-4">
          <a href="{{ route('dashboard') }}" class="btn btn-outline-bank px-4 py-2 me-2">
            <i class="bi bi-arrow-left"></i> Batal
          </a>
          <button type="submit" class="btn btn-bank px-4 py-2">
            <i class="bi bi-check-circle"></i> Simpan Perubahan
          </button>
        </div>
      </form>

    </div>
  </div>
</section>
@endsection
