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
    <h1 class="fw-bold mb-3">Edit Pengajuan Kredit</h1>
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

      {{-- Ringkasan Nasabah (read-only) biar konsisten --}}
      @php($nas = $kredit->nasabah ?? auth()->user()->nasabah ?? null)
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
          Profil nasabah belum lengkap. <a href="{{ route('nasabah.create') }}" class="fw-semibold">Lengkapi di sini</a>.
        </div>
      @endif

      <form method="POST" action="{{ route('kredit.update', $kredit) }}" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PATCH')

        {{-- Jenis Kredit (samain opsi dengan create) --}}
        @php($opsiJenis = [
          'Kredit Wirausaha',
          'Kredit Agri Bisnis',
          'Kredit Investasi Usaha',
          'Kredit Bakulan',
        ])
        <div class="row g-3">
          <div class="col-md-12">
            <label class="form-label">Jenis Kredit <span class="text-danger">*</span></label>
            <select name="jenis_kredit" class="form-select" required>
              <option value="" disabled>-- Pilih Jenis Kredit --</option>
              @php($val = old('jenis_kredit', $kredit->jenis_kredit))
              @foreach($opsiJenis as $o)
                <option value="{{ $o }}" {{ $val===$o ? 'selected' : '' }}>{{ $o }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-6">
            <label for="tenor" class="form-label">Tenor (bulan) <span class="text-danger">*</span></label>
            <input type="number" name="tenor" id="tenor" class="form-control"
                   value="{{ old('tenor', $kredit->tenor) }}" placeholder="Contoh: 12" min="1" max="240" required>
          </div>

          <div class="col-md-6">
            <label for="jumlah_pinjaman" class="form-label">Jumlah Pinjaman (Rp) <span class="text-danger">*</span></label>
            <input type="number" name="jumlah_pinjaman" id="jumlah_pinjaman" class="form-control"
                   value="{{ old('jumlah_pinjaman', $kredit->jumlah_pinjaman) }}" placeholder="Masukkan jumlah pinjaman" min="1000000" required>
            <small class="text-muted">Minimal Rp 1.000.000</small>
          </div>

          <div class="col-12">
            <label for="alasan_kredit" class="form-label">Tujuan Kredit <span class="text-danger">*</span></label>
            <textarea name="alasan_kredit" id="alasan_kredit" rows="3" class="form-control"
                      placeholder="Tuliskan tujuan penggunaan dana..." required>{{ old('alasan_kredit', $kredit->alasan_kredit) }}</textarea>
          </div>

          <div class="col-12">
            <label for="jaminan_deskripsi" class="form-label">Jaminan <span class="text-danger">*</span></label>
            <input type="text" name="jaminan_deskripsi" id="jaminan_deskripsi" class="form-control"
                   value="{{ old('jaminan_deskripsi', $kredit->jaminan_deskripsi) }}"
                   placeholder="Contoh: BPKB Motor Yamaha NMAX 2021" required>
          </div>

          <div class="col-12">
            <label for="jaminan_dokumen" class="form-label">Upload Dokumen Jaminan</label>
            @if($kredit->jaminan_dokumen)
              <div class="alert alert-info d-flex align-items-center justify-content-between">
                <div>
                  <i class="bi bi-file-earmark-text me-2"></i>
                  File saat ini: <strong>{{ basename($kredit->jaminan_dokumen) }}</strong>
                </div>
                <a href="{{ route('kredit.preview', $kredit->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-eye"></i> Lihat
                </a>
              </div>
            @endif
            <input type="file" name="jaminan_dokumen" id="jaminan_dokumen" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
            <small class="text-muted">Unggah foto BPKB/sertifikat; PDF/JPG/PNG (maks 2MB).</small>
          </div>

          <div class="col-12">
            <label for="dokumen_pendukung" class="form-label">Upload Dokumen Pendukung (Opsional)</label>
            @if($kredit->dokumen_pendukung)
              <div class="alert alert-info d-flex align-items-center justify-content-between">
                <div>
                  <i class="bi bi-file-earmark-text me-2"></i>
                  File saat ini: <strong>{{ basename($kredit->dokumen_pendukung) }}</strong>
                </div>
                <a href="{{ route('kredit.preview', $kredit->id) }}?type=pendukung" target="_blank"
                  class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-eye"></i> Lihat
                </a>
              </div>
            @endif
            <input type="file" name="dokumen_pendukung" id="dokumen_pendukung" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
            <small class="text-muted">Opsional — slip gaji, laporan usaha, dll.</small>
          </div>
        </div>

        <div class="text-center mt-4">
          <a href="{{ route('kredit') }}" class="btn btn-outline-bank px-4 py-2 me-2">
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
