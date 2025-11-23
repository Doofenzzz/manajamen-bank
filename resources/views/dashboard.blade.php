@extends('layouts.app')

@push('head')
  <style>
    .hero {
      background: linear-gradient(180deg, #ffffff, #f3f5f9);
      border: 1px solid rgba(0, 0, 0, .06);
    }

    .card-bank {
      border: 1px solid rgba(0, 0, 0, .06);
      border-radius: 16px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
      overflow: hidden;
      transition: all 0.25s ease;
      background: #fff;
      margin-bottom: 2rem;
    }

    .section-header {
      background: #e8f0ff;
      color: #003366;
      font-weight: 700;
      letter-spacing: .2px;
      padding: 1rem 1.5rem;
      font-size: 1rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .table-bank th {
      color: var(--muted);
      font-size: .85rem;
      font-weight: 700;
      border-top: none;
      background: #fafafa;
      padding: 0.75rem;
    }

    .table-bank td {
      padding: 0.75rem;
      vertical-align: middle;
    }

    .table-bank tbody tr {
      transition: background 0.15s ease;
    }

    .table-bank tbody tr:hover {
      background: #fafafa;
    }

    /* Status badges */
    .badge-status {
      font-weight: 700;
      border-radius: 999px;
      padding: .35rem .65rem;
      font-size: .775rem;
      letter-spacing: .2px;
      border: 1px solid transparent;
      display: inline-block;
    }

    .badge-pending {
      color: #7a5d00;
      background: #fff5cc;
      border-color: #ffe89a;
    }

    .badge-diterima {
      color: #0b5d2b;
      background: #dcfce7;
      border-color: #baf7cf;
    }

    .badge-ditolak {
      color: #7a1f1f;
      background: #ffe4e6;
      border-color: #ffc2c8;
    }

    /* Buttons */
    .btn-bank {
      background: var(--blue);
      color: #fff;
      font-weight: 600;
      border: none;
      border-radius: 8px;
      padding: .5rem 1.2rem;
      transition: all .25s ease;
    }

    .btn-bank:hover {
      background: #084dcc;
      color: #fff;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(11, 99, 246, 0.25);
    }

    .btn-outline-bank {
      background: transparent;
      color: var(--blue);
      font-weight: 600;
      border: 1px solid var(--blue);
      border-radius: 8px;
      padding: .5rem 1.2rem;
      transition: all .25s ease;
    }

    .btn-outline-bank:hover {
      background: var(--blue);
      color: #fff;
      transform: translateY(-1px);
    }

    .btn-light {
      border-radius: 8px;
      transition: all .25s ease;
    }

    .btn-light:hover {
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .btn-sm {
      padding: .4rem .8rem;
      font-size: .85rem;
    }

    /* Offcanvas */
    .offcanvas {
      border-radius: 16px 0 0 16px;
      box-shadow: -4px 0 24px rgba(0, 0, 0, 0.12);
    }

    .offcanvas-header {
      background: #e8f0ff;
      color: #003366;
      font-weight: 700;
    }

    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 3rem 1rem;
      color: var(--muted);
    }

    .empty-state i {
      font-size: 3rem;
      opacity: 0.3;
      margin-bottom: 1rem;
    }

    /* Alert info */
    .alert-info {
      background: #e8f4ff;
      border: 1px solid #b3d9ff;
      border-radius: 12px;
      color: #004085;
    }

    /* Apply button */
    .btn-apply {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: .5rem;
      background: var(--blue);
      color: #fff !important;
      font-weight: 600;
      border: none;
      border-radius: 999px;
      padding: .75rem 1.8rem;
      transition: all .25s ease;
      box-shadow: 0 2px 8px rgba(11, 99, 246, 0.25);
      text-decoration: none;
    }

    .btn-apply:hover {
      background: #084dcc;
      transform: translateY(-2px);
      box-shadow: 0 4px 16px rgba(11, 99, 246, 0.35);
      text-decoration: none;
    }
  </style>
@endpush

@section('content')
  <section class="py-4">
    <!-- Hero Section -->
    <div class="hero p-4 mb-4 rounded-4 shadow-sm">
      <h1 class="h4 fw-bold text-dark mb-2">
        Halo, {{ auth()->user()->name }} 👋
      </h1>
      <p class="text-muted mb-0">
        Berikut ringkasan pengajuan kamu di <strong>PT BPR Sarimadu</strong>.
      </p>
    </div>

    <!-- ===================== REKENING ===================== -->
    <div class="card-bank">
      <div class="section-header">
        <i class="bi bi-wallet2"></i> Rekening Tabungan
      </div>

      <div class="card-body p-0">
        @if($rekenings->isEmpty())
          <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p class="text-muted mb-3">Belum ada pengajuan rekening.</p>
            <a href="{{ route('rekening.create') }}" class="btn-apply">
              <i class="bi"></i> Ajukan Sekarang
            </a>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-bank align-middle mb-0">
              <thead>
                <tr>
                  <th>Jenis Tabungan</th>
                  <th>Setoran Awal</th>
                  <th>Status</th>
                  <th>Tanggal Pengajuan</th>
                  <th style="width:200px">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($rekenings as $r)
                  <tr>
                    <td class="fw-semibold">{{ $r->jenis_tabungan }}</td>
                    <td>Rp {{ number_format($r->setoran_awal, 0, ',', '.') }}</td>
                    <td><x-status-badge :status="$r->status" /></td>
                    <td><small class="text-muted">{{ $r->created_at->format('d M Y') }}</small></td>
                    <td>
                      <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-light" data-bs-toggle="offcanvas"
                          data-bs-target="#detailOffcanvas" data-url="{{ route('nasabah.detail', ['rekening', $r->id]) }}">
                          <i class="bi bi-card-text"></i> Detail
                        </button>
                        @if($r->status === 'pending')
                          <a href="{{ route('rekening.edit', $r) }}" class="btn btn-sm btn-outline-bank">
                            <i class="bi bi-pencil-square"></i> Ubah
                          </a>
                        @endif
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>

    <!-- ===================== KREDIT ===================== -->
    <div class="card-bank">
      <div class="section-header">
        <i class="bi bi-cash-coin"></i> Kredit
      </div>

      <div class="card-body p-0">
        @if($kredits->isEmpty())
          <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p class="text-muted mb-3">Belum ada pengajuan kredit.</p>
            <a href="{{ route('kredit.create') }}" class="btn-apply">
              <i class="bi"></i> Ajukan Sekarang
            </a>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-bank align-middle mb-0">
              <thead>
                <tr>
                  <th>Jumlah Pinjaman</th>
                  <th>Tenor</th>
                  <th>Status</th>
                  <th>Tanggal Pengajuan</th>
                  <th style="width:200px">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($kredits as $k)
                  <tr>
                    <td class="fw-bold text-primary">Rp {{ number_format($k->jumlah_pinjaman, 0, ',', '.') }}</td>
                    <td>{{ $k->tenor }} bulan</td>
                    <td><x-status-badge :status="$k->status" /></td>
                    <td><small class="text-muted">{{ $k->created_at->format('d M Y') }}</small></td>
                    <td>
                      <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-light" data-bs-toggle="offcanvas"
                          data-bs-target="#detailOffcanvas" data-url="{{ route('nasabah.detail', ['kredit', $k->id]) }}">
                          <i class="bi bi-card-text"></i> Detail
                        </button>
                        @if($k->status === 'pending')
                          <a href="{{ route('kredit.edit', $k) }}" class="btn btn-sm btn-outline-bank">
                            <i class="bi bi-pencil-square"></i> Ubah
                          </a>
                        @endif
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>

    <!-- ===================== DEPOSITO ===================== -->
    <div class="card-bank">
      <div class="section-header">
        <i class="bi bi-piggy-bank"></i> Deposito
      </div>

      <div class="card-body p-0">
        @if($depositos->isEmpty())
          <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p class="text-muted mb-3">Belum ada pengajuan deposito.</p>
            <a href="{{ route('deposito.create') }}" class="btn-apply">
              <i class="bi"></i> Ajukan Sekarang
            </a>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-bank align-middle mb-0">
              <thead>
                <tr>
                  <th>Nominal</th>
                  <th>Jangka Waktu</th>
                  <th>Status</th>
                  <th>Tanggal Pengajuan</th>
                  <th style="width:200px">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($depositos as $d)
                  <tr>
                    <td class="fw-bold text-success">Rp {{ number_format($d->nominal, 0, ',', '.') }}</td>
                    <td>{{ $d->jangka_waktu }} bulan</td>
                    <td><x-status-badge :status="$d->status" /></td>
                    <td><small class="text-muted">{{ $d->created_at->format('d M Y') }}</small></td>
                    <td>
                      <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-light" data-bs-toggle="offcanvas"
                          data-bs-target="#detailOffcanvas" data-url="{{ route('nasabah.detail', ['deposito', $d->id]) }}">
                          <i class="bi bi-card-text"></i> Detail
                        </button>
                        @if($d->status === 'pending')
                          <a href="{{ route('deposito.edit', $d) }}" class="btn btn-sm btn-outline-bank">
                            <i class="bi bi-pencil-square"></i> Ubah
                          </a>
                        @endif
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>

    <!-- OFFCANVAS DETAIL -->
    <div class="offcanvas offcanvas-end" style="width: 50%;" tabindex="-1" id="detailOffcanvas">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title fw-bold">
          <i class="bi bi-file-text me-2"></i> Detail Pengajuan
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body" id="detailBody">
        <div class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="text-muted mt-3">Memuat detail...</p>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    // Load partial detail via AJAX ke offcanvas
    document.querySelectorAll('[data-bs-target="#detailOffcanvas"]').forEach(btn => {
      btn.addEventListener('click', async () => {
        const url = btn.getAttribute('data-url');
        const body = document.getElementById('detailBody');

        body.innerHTML = `
          <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted mt-3">Memuat detail...</p>
          </div>
        `;

        try {
          const response = await fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          });
          const html = await response.text();
          body.innerHTML = html;
        } catch (error) {
          body.innerHTML = `
            <div class="alert alert-danger">
              <i class="bi bi-exclamation-triangle me-2"></i>
              Gagal memuat data. Silakan coba lagi.
            </div>
          `;
        }
      });
    });
  </script>
@endpush