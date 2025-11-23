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
  input[type=number]{ -moz-appearance: textfield; }
</style>
@endpush

@section('hero')
<div class="form-hero">
  <div class="container">
    <h1 class="fw-bold mb-3">Edit Pengajuan Deposito</h1>
    <p>Perbarui data pengajuanmu. Style & layout disamain persis sama form pengajuan baru.</p>
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

      {{-- Nasabah context --}}
      @php($nas = $deposito->nasabah ?? auth()->user()->nasabah ?? null)
      @if($nas)
        <input type="hidden" name="nasabah_id" value="{{ $nas->id }}">
        <div class="alert alert-info d-flex align-items-center gap-2">
          <i class="bi bi-person-badge"></i>
          <div>
            Pengajuan atas nama: <strong>{{ $nas->nama }}</strong>
            @if(!empty($nas->nik))<br>NIK: <strong>{{ $nas->nik }}</strong>@endif
          </div>
        </div>
      @else
        <div class="alert alert-warning">
          <i class="bi bi-exclamation-triangle"></i>
          Kamu belum punya profil nasabah. <a href="{{ route('nasabah.create') }}" class="fw-semibold">Lengkapi profil</a>.
        </div>
      @endif

      <form method="POST" action="{{ route('deposito.update', $deposito) }}" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PATCH')

        {{-- === FIELD SESUAI CREATE/MIGRATION === --}}
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nominal Deposito (Rp) <span class="text-danger">*</span></label>
            <input type="number" name="nominal" class="form-control"
                   value="{{ old('nominal', $deposito->nominal) }}"
                   placeholder="Contoh: 10000000" min="0" step="1000" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Jangka Waktu (bulan) <span class="text-danger">*</span></label>
            <select name="jangka_waktu" class="form-select" required>
              <option value="" disabled>Pilih tenor</option>
              @foreach ([1,3,6,12,24] as $bulan)
                <option value="{{ $bulan }}" @selected(old('jangka_waktu', $deposito->jangka_waktu) == $bulan)>
                  {{ $bulan }} bulan
                </option>
              @endforeach
            </select>
          </div>

          {{-- bunga diset default di DB; tidak ditampilkan di form --}}

          <div class="col-md-12">
            <label class="form-label">Jenis Deposito <span class="text-danger">*</span></label>
            @php($opsi = ['Deposito Berjangka','Deposito ARO','Deposito Non-ARO'])
            <select name="jenis_deposito" class="form-select" required>
              @php($val = old('jenis_deposito', $deposito->jenis_deposito ?? 'Deposito Berjangka'))
              @foreach ($opsi as $opt)
                <option value="{{ $opt }}" {{ $val===$opt ? 'selected' : '' }}>{{ $opt }}</option>
              @endforeach
            </select>
            <small class="text-muted">Default: Deposito Berjangka.</small>
          </div>

          <div class="col-md-12">
            <label class="form-label">Bukti Transfer (opsional)</label>
            @if(!empty($deposito->bukti_transfer))
              <div class="alert alert-info d-flex align-items-center justify-content-between">
                <div>
                  <i class="bi bi-file-earmark-text me-2"></i>
                  File saat ini: <strong>{{ basename($deposito->bukti_transfer) }}</strong>
                </div>
                <a href="{{ route('deposito.preview', $deposito->id) }}?type=pendukung" target="_blank"
                  class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-eye"></i> Lihat
                </a>
              </div>
            @endif
            <input type="file" name="bukti_transfer" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            <small class="text-muted">Upload ulang jika ingin mengganti bukti.</small>
          </div>

          <div class="col-12">
            <label class="form-label">Catatan (opsional)</label>
            <textarea name="catatan" class="form-control" rows="3"
                      placeholder="Tambahkan keterangan…">{{ old('catatan', $deposito->catatan) }}</textarea>
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
