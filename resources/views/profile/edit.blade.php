@extends('layouts.app')

@push('head')
  <style>
    .hero {
      background: linear-gradient(180deg, #ffffff, #f3f5f9);
      border: 1px solid rgba(0, 0, 0, .06);
    }

    .bank-card {
      border: 1px solid rgba(0, 0, 0, .06);
      border-radius: 16px;
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    .bank-card .card-header {
      background: #e8f0ff;
      color: #003366;
      font-weight: 700;
      letter-spacing: .2px;
      border: 0;
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

    .btn-bank {
      background: var(--blue);
      color: #fff !important;
      border-radius: 999px;
      padding: .6rem 1.4rem;
      font-weight: 600;
      box-shadow: 0 2px 8px rgba(11, 99, 246, .25);
      text-decoration: none !important;
      transition: .25s;
    }

    .btn-bank:hover {
      background: #084dcc;
      transform: translateY(-2px);
      box-shadow: 0 4px 16px rgba(11, 99, 246, .35);
      text-decoration: none !important;
    }

    .btn-outline-danger {
      border-radius: 999px;
      padding: .55rem 1.2rem;
      font-weight: 600;
    }

    .hint {
      color: var(--muted);
      font-size: .9rem;
    }

    .ktp-thumb {
      width: 100%;
      max-width: 320px;
      border-radius: 12px;
      border: 1px solid rgba(0, 0, 0, .08);
      object-fit: cover;
    }
  </style>
@endpush

@section('content')
  <section class="py-4">
    <div class="hero p-4 mb-5 rounded-4 bg-white">
      <h1 class="fw-bold text-dark mb-2">
        Edit Profil 👤
      </h1>
      <p class="text-muted mb-0">Update data akun & nasabah kamu. Pastikan datanya valid biar proses pengajuan cepet
        mulus.</p>
    </div>

    <div class="row g-4">
      {{-- Kartu: Akun --}}
      <div class="col-lg-6">
        <div class="card bank-card">
          <div class="card-header"><i class="bi bi-person-gear me-1"></i> Akun</div>
          <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}" class="mb-2">
              @csrf
              @method('PATCH')
              <div class="row g-3">
                <div class="col-md-12">
                  <label class="form-label">Nama</label>
                  <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="col-md-12">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
              </div>
              <div class="d-flex gap-2 mt-4">
                <button class="btn btn-bank"><i class="bi bi-save2 me-1"></i> Simpan Akun</button>
              </div>
            </form>

            <hr class="my-4">

            <form method="POST" action="{{ route('profile.destroy') }}"
              onsubmit="return confirm('Yakin ingin menghapus akun? Tindakan ini tidak bisa dibatalkan.');">
              @csrf
              @method('DELETE')
              <button class="btn btn-outline-danger">
                <i class="bi bi-trash3 me-1"></i> Hapus Akun
              </button>
              <div class="hint mt-2">Akun dan seluruh data terkait (termasuk nasabah) akan terhapus permanen.</div>
            </form>
          </div>
        </div>
      </div>

      {{-- Kartu: Data Nasabah (sesuai migrasi) --}}
      <div class="col-lg-6">
        <div class="card bank-card">
          <div class="card-header"><i class="bi bi-credit-card-2-front me-1"></i> Data Nasabah</div>
          <div class="card-body">
            @php
              $nasabah = $nasabah ?? optional($user)->nasabah; // safe
              $isNasabah = (bool) $nasabah;
            @endphp

            @if(!$isNasabah)
              {{-- Bukan nasabah (admin/teller) → ga wajib isi KYC --}}
              <div class="alert alert-info mb-3">
                Akun ini <strong>bukan profil nasabah</strong>, jadi data KYC tidak diperlukan.
                Kalau mau buat profil nasabah baru untuk akun ini, isi form di bawah (opsional).
              </div>
            @endif

            <form method="POST"
                  action="{{ $isNasabah ? route('nasabah.update', $nasabah->id) : route('nasabah.store') }}"
                  enctype="multipart/form-data">
              @csrf
              @if($isNasabah) @method('PUT') @endif

              {{-- hubungkan user_id otomatis saat create --}}
              @unless($isNasabah)
                <input type="hidden" name="user_id" value="{{ $user->id }}">
              @endunless

              <div class="row g-3">

                {{-- Nama (full) --}}
                <div class="col-12">
                  <label class="form-label fw-semibold">Nama Lengkap</label>
                  <input type="text" name="nama" class="form-control"
                        value="{{ old('nama', $nasabah->nama ?? $user->name) }}"
                        {{ $isNasabah ? 'required' : '' }}>
                </div>

                {{-- NIK + Tempat Lahir --}}
                <div class="col-md-6">
                  <label class="form-label fw-semibold">NIK</label>
                  <input type="text" name="nik" maxlength="16" pattern="\d{16}" inputmode="numeric" class="form-control"
                        value="{{ old('nik', $nasabah->nik ?? '') }}"
                        {{ $isNasabah ? 'required' : '' }}>
                  <small class="text-muted d-block mt-1">Wajib tepat 16 digit.</small>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Tempat Lahir</label>
                  <input type="text" name="tempat_lahir" class="form-control"
                        value="{{ old('tempat_lahir', $nasabah->tempat_lahir ?? '') }}"
                        {{ $isNasabah ? 'required' : '' }}>
                </div>

                {{-- Tanggal Lahir + No HP --}}
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Tanggal Lahir</label>
                  <input type="date" name="tanggal_lahir" class="form-control"
                        value="{{ old('tanggal_lahir', optional(optional($nasabah)->tanggal_lahir)->format('Y-m-d')) }}"
                        {{ $isNasabah ? 'required' : '' }}>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">No HP</label>
                  <input type="tel" name="no_hp" class="form-control" maxlength="15" inputmode="tel"
                        value="{{ old('no_hp', $nasabah->no_hp ?? '') }}"
                        {{ $isNasabah ? 'required' : '' }}>
                </div>

                {{-- Alamat --}}
                <div class="col-12">
                  <label class="form-label fw-semibold">Alamat</label>
                  <textarea name="alamat" rows="3" class="form-control"
                  {{ $isNasabah ? 'required' : '' }}>{{ old('alamat', $nasabah->alamat ?? '') }}</textarea>
                {{-- Foto KTP --}}
                  <div class="col-12">
                    <label class="form-label fw-semibold">Foto KTP</label>
                    <input type="file" name="foto_ktp" class="form-control" accept=".jpg,.jpeg,.png">
                    <small class="text-muted d-block mt-1">Format .jpg/.jpeg/.png — maks 2MB.</small>
                  </div>

                  {{-- Preview dari KTP lama --}}
                  @if(!empty($nasabah?->foto_ktp))
                    <div class="col-12 mt-3">
                      <div class="fw-semibold mb-2">KTP Saat Ini</div>

                      {{-- Langsung tampilkan foto dari route nasabah.preview --}}
                      <img src="{{ route('nasabah.preview', $nasabah->id) }}?v={{ $nasabah->updated_at?->timestamp }}"
                          alt="KTP Nasabah"
                          class="img-fluid rounded border shadow-sm"
                          style="max-width: 320px;">


                      <div class="mt-2 d-flex align-items-center gap-2">
                        <a href="{{ route('nasabah.bukti', $nasabah->id) }}" class="btn btn-sm btn-primary">
                          <i class="bi bi-download"></i> Unduh
                        </a>
                        <small class="text-muted">{{ basename($nasabah->foto_ktp) }}</small>
                      </div>
                    </div>
                  @endif

                  {{-- Preview KTP baru sebelum upload --}}
                  <div class="col-12 mt-3" id="ktpPreviewWrap" style="display:none;">
                    <div class="fw-semibold mb-2">Pratinjau KTP Baru</div>
                    <img class="img-fluid rounded border shadow-sm" id="ktpPreviewNew" alt="Preview KTP Baru" style="max-width: 320px;">
                  </div>
              <div class="d-flex gap-2 mt-4">
                <button class="btn btn-bank">
                  <i class="bi bi-save2 me-1"></i>
                  {{ $isNasabah ? 'Simpan Data Nasabah' : 'Simpan & Buat Data Nasabah' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </section>

  @push('scripts')
<script>
  document.querySelector('input[name="foto_ktp"]')?.addEventListener('change', e => {
    const file = e.target.files?.[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = evt => {
      document.getElementById('ktpPreviewNew').src = evt.target.result;
      document.getElementById('ktpPreviewWrap').style.display = 'block';
    };
    reader.readAsDataURL(file);
  });
</script>
@endpush
@endsection