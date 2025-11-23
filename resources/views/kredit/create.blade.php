@extends('layouts.app')

@push('head')
  <style>
    .form-hero {
      background: linear-gradient(180deg, #ffffff 0%, #f3f5f9 100%);
      padding: 3rem 0 2rem;
      text-align: center;
    }

    .form-hero h1 {
      font-weight: 800;
      color: var(--navy);
    }

    .form-hero p {
      color: var(--muted);
      max-width: 680px;
      margin: auto;
    }

    .form-container {
      background: var(--surface);
      border: 1px solid rgba(0, 0, 0, .05);
      border-radius: 16px;
      box-shadow: var(--shadow);
      padding: 2rem;
    }

    .form-label {
      font-weight: 600;
      color: var(--ink);
    }

    .form-control,
    .form-select {
      border-radius: .75rem;
      border: 1px solid rgba(0, 0, 0, .15);
      padding: .65rem .9rem;
    }

    .btn-bank:hover {
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
      <h1 class="fw-bold mb-3">Form Pengajuan Kredit</h1>
      <p>Isi formulir berikut dengan lengkap untuk mengajukan permohonan kredit di PT BPR Sarimadu. Data Anda akan
        diverifikasi oleh petugas kami.</p>
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

        <form action="{{ route('kredit.store') }}" method="POST" enctype="multipart/form-data">
          @csrf

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
            <div class="alert alert-warning">
              <i class="bi bi-exclamation-triangle"></i>
              Profil nasabah belum lengkap. <a href="{{ route('nasabah.create') }}">Lengkapi di sini</a> sebelum mengajukan
              kredit.
            </div>
          @endif

          {{-- <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', auth()->user()->name) }}"
              placeholder="Masukkan nama lengkap" required>
          </div> --}}

          <div class="mb-3">
            <label for="jenis_kredit" class="form-label">Jenis Kredit</label>
            <select name="jenis_kredit" id="jenis_kredit" class="form-select" required>
              <option value="">-- Pilih Jenis Kredit --</option>
              <option value="Kredit Wirausaha">Kredit Wirausaha</option>
              <option value="Kredit Agri Bisnis">Kredit Agri Bisnis</option>
              <option value="Kredit Investasi Usaha">Kredit Investasi Usaha</option>
              <option value="Kredit Bakulan">Kredit Bakulan</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="tenor" class="form-label">Tenor (bulan)</label>
            <input type="number" name="tenor" id="tenor" class="form-control" placeholder="Contoh: 12" required>
          </div>

          <div class="mb-3">
            <label for="jumlah_pinjaman" class="form-label">Jumlah Pinjaman (Rp)</label>
            <input type="number" name="jumlah_pinjaman" id="jumlah_pinjaman" class="form-control"
              placeholder="Masukkan jumlah pinjaman" required>
          </div>

          <div class="mb-3">
            <label for="alasan_kredit" class="form-label">Tujuan Kredit</label>
            <textarea name="alasan_kredit" id="alasan_kredit" rows="3" class="form-control"
              placeholder="Tuliskan tujuan penggunaan dana..." required></textarea>
          </div>

          <div class="mb-3">
            <label for="jaminan_deskripsi" class="form-label">Jaminan</label>
            <input type="text" name="jaminan_deskripsi" id="jaminan_deskripsi" class="form-control"
              placeholder="Contoh: BPKB Motor Yamaha NMAX 2021" required>
          </div>

          <div class="mb-3">
            <label for="jaminan_dokumen" class="form-label">Upload Dokumen Jaminan</label>
            <input type="file" name="jaminan_dokumen" id="jaminan_dokumen" class="form-control"
              accept=".jpg,.jpeg,.png,.pdf" required>
            <small class="text-muted">Unggah foto BPKB, sertifikat, atau dokumen lain sebagai bukti jaminan.</small>
          </div>


          <div class="mb-3">
            <label for="dokumen_pendukung" class="form-label">Upload Dokumen Pendukung</label>
            <input type="file" name="dokumen_pendukung" id="dokumen_pendukung" class="form-control"
              accept=".pdf,.jpg,.jpeg,.png">
            <small class="text-muted">Opsional — upload dokumen tambahan seperti slip gaji, laporan usaha, dsb (jika
              ada).</small>
          </div>

          <div class="text-center mt-4">
            <button type="submit" class="btn btn-bank px-4 py-2">
              <i class="bi bi-send-check"></i> Kirim Pengajuan
            </button>
            <a href="{{ route('kredit') }}" class="btn btn-outline-bank px-4 py-2 ms-2">
              <i class="bi bi-arrow-left"></i> Kembali
            </a>
          </div>
        </form>
      </div>
    </div>
  </section>
@endsection