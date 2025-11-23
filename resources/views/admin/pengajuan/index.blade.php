@extends('layouts.app')

@push('head')
<style>
  .hero {
    background: linear-gradient(180deg, #ffffff, #f3f5f9);
    border: 1px solid rgba(0,0,0,.06);
  }

  .card-bank {
    border: 1px solid rgba(0,0,0,.06);
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    overflow: hidden;
    transition: all 0.25s ease;
    background: #fff;
    margin-bottom: 2rem;
  }

  .filter-section {
    background: #fff;
    border: 1px solid rgba(0,0,0,.06);
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  }

  .filter-label {
    font-weight: 600;
    color: #333;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
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
    box-shadow: 0 4px 12px rgba(11,99,246,0.25);
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
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }

  .btn-sm {
    padding: .4rem .8rem;
    font-size: .85rem;
  }

  /* Tabs */
  .nav-tabs {
    border-bottom: 2px solid #e9ecef;
  }

  .nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 600;
    padding: 1rem 1.5rem;
    transition: all 0.2s ease;
    border-radius: 8px 8px 0 0;
  }

  .nav-tabs .nav-link:hover {
    color: var(--blue);
    background: #f8f9fa;
  }

  .nav-tabs .nav-link.active {
    color: var(--blue);
    background: #e8f0ff;
    border-bottom: 3px solid var(--blue);
  }

  /* Form controls */
  .form-control,
  .form-select {
    border-radius: 8px;
    border: 1px solid rgba(0,0,0,.12);
    transition: border-color 0.15s ease;
    padding: 0.5rem 0.75rem;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(11,99,246,0.1);
  }

  /* Offcanvas */
  .offcanvas {
    border-radius: 16px 0 0 16px;
    box-shadow: -4px 0 24px rgba(0,0,0,0.12);
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

  /* Pagination */
  .pagination {
    margin-bottom: 0;
  }

  .pagination .page-link {
    border-radius: 8px;
    margin: 0 0.2rem;
    border: 1px solid rgba(0,0,0,.12);
    color: var(--blue);
  }

  .pagination .page-link:hover {
    background: #e8f0ff;
    border-color: var(--blue);
  }

  .pagination .active .page-link {
    background: var(--blue);
    border-color: var(--blue);
  }
</style>
@endpush

@section('content')
<section class="py-4">
  <!-- Hero Section -->
  <div class="hero p-4 mb-4 rounded-4 shadow-sm">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <h1 class="h4 fw-bold text-dark mb-1">
          <i class="bi bi-columns-gap me-2"></i> Kelola Pengajuan Nasabah
        </h1>
        <p class="text-muted mb-0">
          Filter, sortir, dan kelola semua pengajuan dalam satu halaman.
        </p>
      </div>

      <div class="d-flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-light">
          <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
      </div>
    </div>
  </div>

  <!-- Filter Section -->
  <form method="GET" class="filter-section">
    <div class="row g-3">
      <div class="col-md-3">
        <label class="filter-label">
          <i class="bi bi-search me-1"></i> Cari
        </label>
        <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Nama, NIK, atau lainnya..." />
      </div>
      
      <div class="col-md-2">
        <label class="filter-label">
          <i class="bi bi-filter me-1"></i> Status
        </label>
        <select name="status" class="form-select">
          <option value="">Semua Status</option>
          @foreach(['pending','diterima','ditolak'] as $st)
            <option value="{{ $st }}" @selected(request('status')===$st)>{{ ucfirst($st) }}</option>
          @endforeach
        </select>
      </div>
      
      <div class="col-md-2">
        <label class="filter-label">
          <i class="bi bi-calendar me-1"></i> Dari Tanggal
        </label>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control"/>
      </div>
      
      <div class="col-md-2">
        <label class="filter-label">
          <i class="bi bi-calendar-check me-1"></i> Sampai Tanggal
        </label>
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control"/>
      </div>
      
      <div class="col-md-3">
        <label class="filter-label">
          <i class="bi bi-sort-down me-1"></i> Urutkan Berdasarkan
        </label>
        <select name="sort" class="form-select">
          @foreach(['created_at'=>'Tanggal','status'=>'Status','nominal'=>'Nominal'] as $k=>$v)
            <option value="{{ $k }}" @selected(request('sort')===$k)>{{ $v }}</option>
          @endforeach
        </select>
      </div>
    </div>
    
    <div class="d-flex gap-2 justify-content-end mt-3">
      <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-light">
        <i class="bi bi-x-circle me-1"></i> Reset
      </a>
      <button type="submit" class="btn btn-bank">
        <i class="bi bi-funnel me-1"></i> Terapkan Filter
      </button>
    </div>
  </form>

  <!-- Tabs Navigation -->
  <ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-bs-toggle="tab" href="#t-rekening">
        <i class="bi bi-wallet2 me-1"></i> Rekening Tabungan
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="tab" href="#t-kredit">
        <i class="bi bi-cash-coin me-1"></i> Kredit
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="tab" href="#t-deposito">
        <i class="bi bi-piggy-bank me-1"></i> Deposito
      </a>
    </li>
  </ul>

  <!-- Tab Content -->
  <div class="tab-content">
    @foreach(['rekening','kredit','deposito'] as $tab)
    <div class="tab-pane fade @if($loop->first) show active @endif" id="t-{{ $tab }}">
      <div class="card-bank">
        <div class="table-responsive">
          <table class="table table-bank align-middle mb-0">
            <thead>
              <tr>
                <th>Nasabah</th>
                <th>Jenis</th>
                <th>Nominal/Setoran</th>
                <th>Status</th>
                <th>Tanggal Masuk</th>
                <th>Petugas</th>
                <th style="width:120px">Aksi</th>
              </tr>
            </thead>
            <tbody>
            @forelse($tabs[$tab] as $row)
              @php $nominal = $row->nominal ?? $row->setoran_awal ?? $row->jumlah_pinjaman ?? null; @endphp
              <tr>
                <td>
                  <div class="fw-semibold">{{ $row->nasabah->nama ?? '-' }}</div>
                  <div class="text-muted small">{{ $row->nasabah->nik ?? '-' }}</div>
                </td>
                <td>
                  <span class="badge bg-light text-dark text-capitalize">{{ $tab }}</span>
                </td>
                <td class="fw-bold">
                  @if($nominal)
                    Rp {{ number_format($nominal, 0, ',', '.') }}
                  @else
                    -
                  @endif
                </td>
                <td>
                  <span class="badge-status badge-{{ $row->status }}">
                    {{ ucfirst($row->status) }}
                  </span>
                </td>
                <td>
                  <div>{{ $row->created_at->format('d M Y') }}</div>
                  <small class="text-muted">{{ $row->created_at->format('H:i') }}</small>
                </td>
                <td>
                  <span class="text-muted">{{ $row->processor->name ?? '-' }}</span>
                </td>
                <td>
                  <a href="{{ route('admin.pengajuan.show',[$tab,$row->id]) }}" 
                     class="btn btn-sm btn-light"
                     data-bs-toggle="offcanvas" 
                     data-bs-target="#detailOffcanvas"
                     data-url="{{ route('admin.pengajuan.show',[$tab,$row->id]) }}">
                    <i class="bi bi-card-text"></i> Detail
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="empty-state">
                  <i class="bi bi-inbox"></i>
                  <p class="mb-0">Tidak ada data pengajuan {{ $tab }}.</p>
                </td>
              </tr>
            @endforelse
            </tbody>
          </table>
        </div>
        
        @if($tabs[$tab]->hasPages())
        <div class="card-body d-flex justify-content-center">
          {{ $tabs[$tab]->withQueryString()->links() }}
        </div>
        @endif
      </div>
    </div>
    @endforeach
  </div>
</section>

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
          headers: {'X-Requested-With': 'XMLHttpRequest'}
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