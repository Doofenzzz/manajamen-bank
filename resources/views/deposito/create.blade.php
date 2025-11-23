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
      <h1 class="fw-bold mb-3">Pengajuan Deposito</h1>
      <p>Buka deposito Sarimadu. Kalau profil nasabah udah lengkap, identitas auto-keisi.</p>
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

        <form method="POST" action="{{ route('deposito.store') }}" enctype="multipart/form-data" novalidate>
          @csrf

          {{-- Nasabah context --}}
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
              Kamu belum punya profil nasabah. Isi data singkat di bawah atau
              <a href="{{ route('nasabah.create') }}" class="fw-semibold">lengkapi profil lengkap</a>.
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Nama Lengkap (Sesuai KTP)</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', auth()->user()->name) }}"
                  required>
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
                <input type="text" name="nik" class="form-control" value="{{ old('nik') }}" maxlength="16"
                  placeholder="16 digit" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Foto KTP</label>
                <input type="file" name="foto_ktp" class="form-control" accept="image/*" required>
              </div>
            </div>
            <hr class="my-4">
          @endif

          {{-- === FIELD SESUAI MIGRATION === --}}
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nominal Deposito (Rp)</label>
              <input type="number" name="nominal" class="form-control" value="{{ old('nominal') }}"
                placeholder="Contoh: 10000000" min="0" step="1000" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Jangka Waktu (bulan)</label>
              <select name="jangka_waktu" class="form-select" required>
                <option value="" selected disabled>Pilih tenor</option>
                @foreach ([1, 3, 6, 12, 24] as $bulan)
                  <option value="{{ $bulan }}" @selected(old('jangka_waktu') == $bulan)>{{ $bulan }} bulan</option>
                @endforeach
              </select>
            </div>

            {{-- bunga DIHILANGKAN dari form. default 5.00% di DB --}}

            <div class="col-md-6">
              <label class="form-label">Jenis Deposito</label>
              <select name="jenis_deposito" class="form-select">
                @php
                  $opsi = [
                    'Deposito Berjangka',
                    'Deposito ARO',
                    'Deposito Non-ARO'
                  ];
                @endphp
                @foreach ($opsi as $opt)
                  <option value="{{ $opt }}" @selected(old('jenis_deposito', 'Deposito Berjangka') === $opt)>
                    {{ $opt }}
                  </option>
                @endforeach
              </select>
              <small class="text-muted">Default: Deposito Berjangka.</small>
            </div>

            <div class="col-md-6">
              <label class="form-label">Bukti Transfer (opsional)</label>
              <input type="file" name="bukti_transfer" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
              <small class="text-muted">Upload bukti setoran jika sudah transfer.</small>
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