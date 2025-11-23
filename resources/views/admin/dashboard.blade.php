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

  /* Alert modern */
  .alert-info {
    background: #e8f4ff;
    border: 1px solid #b3d9ff;
    border-radius: 12px;
    color: #004085;
  }

  /* Modal styling */
  .modal-content {
    border-radius: 16px;
    border: none;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
  }

  .modal-header {
    background: #e8f0ff;
    color: #003366;
    border-radius: 16px 16px 0 0;
    border-bottom: 1px solid rgba(0,0,0,.06);
  }

  .modal-title {
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
  }

  .form-select,
  .form-control {
    border-radius: 8px;
    border: 1px solid rgba(0,0,0,.12);
    transition: border-color 0.15s ease;
  }

  .form-select:focus,
  .form-control:focus {
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
</style>
@endpush

@section('content')
<section class="py-4">
  <!-- Hero Section -->
  <div class="hero p-4 mb-4 rounded-4 shadow-sm">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <h1 class="h4 fw-bold text-dark mb-1">
          <i class="bi bi-speedometer2 me-2"></i> Dashboard Admin
        </h1>
        <p class="text-muted mb-0">
          Kelola pengajuan rekening, kredit, dan deposito nasabah.
        </p>
      </div>

      <div class="d-flex gap-2">
        <a href="{{ route('admin.pengajuan.index') }}" class="btn btn-outline-bank">
          <i class="bi bi-columns-gap me-1"></i> Kelola Lengkap
        </a>
      </div>
    </div>
  </div>

  <!-- Info Alert -->
  <div class="alert alert-info d-flex align-items-start mb-4" role="alert">
    <i class="bi bi-info-circle me-2 mt-1"></i>
    <div>
      Notifikasi email akan terkirim otomatis saat status diubah. Riwayat status, lampiran, assignment, dan catatan bisa dibuka lewat tombol <strong>Detail</strong>.
    </div>
  </div>

  <!-- ===================== REKENING ===================== -->
  <div class="card-bank">
    <div class="section-header">
      <i class="bi bi-wallet2"></i> Pengajuan Rekening Tabungan
    </div>
    
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bank align-middle mb-0">
          <thead>
            <tr>
              <th>Nasabah</th>
              <th>Jenis Tabungan</th>
              <th>Unit Kerja</th>
              <th>Status</th>
              <th>Catatan</th>
              <th style="width:180px">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rekenings as $r)
              <tr>
                <td class="fw-semibold">{{ $r->nasabah->user->name ?? '-' }}</td>
                <td>{{ $r->jenis_tabungan }}</td>
                <td><span class="text-muted">{{ $r->unit_kerja_pembukaan_tabungan ?? '-' }}</span></td>
                <td><x-status-badge :status="$r->status" /></td>
                <td><small class="text-muted">{{ Str::limit($r->catatan ?? '-', 30) }}</small></td>
                <td>
                  <div class="d-flex gap-2">
                    <a href="{{ route('admin.pengajuan.show',['rekening',$r->id]) }}"
                       class="btn btn-sm btn-light"
                       data-bs-toggle="offcanvas"
                       data-bs-target="#detailOffcanvas"
                       data-url="{{ route('admin.pengajuan.show',['rekening',$r->id]) }}">
                      <i class="bi bi-card-text"></i> Detail
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-bank" 
                            data-bs-toggle="modal"
                            data-bs-target="#rekeningModal{{ $r->id }}">
                      <i class="bi bi-pencil-square"></i> Ubah
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Modal Rekening -->
              <div class="modal fade" id="rekeningModal{{ $r->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">
                        <i class="bi bi-wallet2"></i> Ubah Status Rekening
                      </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('admin.rekening.update', $r) }}">
                      @csrf @method('PATCH')
                      <div class="modal-body">
                        <div class="mb-3">
                          <label class="form-label">Status Pengajuan</label>
                          <select class="form-select" name="status" required>
                            <option value="pending" @selected($r->status === 'pending')>Pending</option>
                            <option value="diterima" @selected($r->status === 'diterima')>Diterima</option>
                            <option value="ditolak" @selected($r->status === 'ditolak')>Ditolak</option>
                          </select>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Alasan/Catatan</label>
                          <textarea class="form-control" name="catatan" rows="3" 
                                    placeholder="Tambahkan catatan (wajib saat menolak)">{{ old('catatan', $r->catatan) }}</textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-bank">
                          <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            @empty
              <tr>
                <td colspan="7" class="empty-state">
                  <i class="bi bi-inbox"></i>
                  <p class="mb-0">Tidak ada pengajuan rekening.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ===================== KREDIT ===================== -->
  <div class="card-bank">
    <div class="section-header">
      <i class="bi bi-cash-coin"></i> Pengajuan Kredit
    </div>
    
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bank align-middle mb-0">
          <thead>
            <tr>
              <th>Nasabah</th>
              <th>Jumlah Pinjaman</th>
              <th>Tenor</th>
              <th>Bunga</th>
              <th>Status</th>
              <th>Tujuan</th>
              <th style="width:180px">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($kredits as $k)
              <tr>
                <td class="fw-semibold">{{ $k->nasabah->user->name ?? '-' }}</td>
                <td class="fw-bold text-primary">Rp {{ number_format($k->jumlah_pinjaman, 0, ',', '.') }}</td>
                <td>{{ $k->tenor }} bulan</td>
                <td><span class="badge bg-light text-dark">{{ rtrim(rtrim(number_format($k->bunga, 2, ',', '.'), '0'), ',') }}%</span></td>
                <td><x-status-badge :status="$k->status" /></td>
                <td><small class="text-muted">{{ Str::limit($k->alasan_kredit ?? '-', 30) }}</small></td>
                <td>
                  <div class="d-flex gap-2">
                    <a href="{{ route('admin.pengajuan.show',['kredit',$k->id]) }}"
                       class="btn btn-sm btn-light"
                       data-bs-toggle="offcanvas"
                       data-bs-target="#detailOffcanvas"
                       data-url="{{ route('admin.pengajuan.show',['kredit',$k->id]) }}">
                      <i class="bi bi-card-text"></i> Detail
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-bank" 
                            data-bs-toggle="modal"
                            data-bs-target="#kreditModal{{ $k->id }}">
                      <i class="bi bi-pencil-square"></i> Ubah
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Modal Kredit -->
              <div class="modal fade" id="kreditModal{{ $k->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">
                        <i class="bi bi-cash-coin"></i> Ubah Status Kredit
                      </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('admin.kredit.update', $k) }}">
                      @csrf @method('PATCH')
                      <div class="modal-body">
                        <div class="mb-3">
                          <label class="form-label">Status Pengajuan</label>
                          <select class="form-select" name="status" required>
                            <option value="pending" @selected($k->status === 'pending')>Pending</option>
                            <option value="diterima" @selected($k->status === 'diterima')>Diterima</option>
                            <option value="ditolak" @selected($k->status === 'ditolak')>Ditolak</option>
                          </select>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Alasan/Catatan</label>
                          <textarea class="form-control" name="catatan" rows="3" 
                                    placeholder="Tambahkan catatan (wajib saat menolak)">{{ old('catatan', $k->catatan) }}</textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-bank">
                          <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            @empty
              <tr>
                <td colspan="7" class="empty-state">
                  <i class="bi bi-inbox"></i>
                  <p class="mb-0">Tidak ada pengajuan kredit.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ===================== DEPOSITO ===================== -->
  <div class="card-bank">
    <div class="section-header">
      <i class="bi bi-piggy-bank"></i> Pengajuan Deposito
    </div>
    
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bank align-middle mb-0">
          <thead>
            <tr>
              <th>Nasabah</th>
              <th>Nominal</th>
              <th>Jangka Waktu</th>
              <th>Bunga</th>
              <th>Status</th>
              <th>Catatan</th>
              <th style="width:180px">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($depositos as $d)
              <tr>
                <td class="fw-semibold">{{ $d->nasabah->user->name ?? '-' }}</td>
                <td class="fw-bold text-success">Rp {{ number_format($d->nominal, 0, ',', '.') }}</td>
                <td>{{ $d->jangka_waktu }} bulan</td>
                <td><span class="badge bg-light text-dark">{{ rtrim(rtrim(number_format($d->bunga, 2, ',', '.'), '0'), ',') }}%</span></td>
                <td><x-status-badge :status="$d->status" /></td>
                <td><small class="text-muted">{{ Str::limit($d->catatan ?? '-', 30) }}</small></td>
                <td>
                  <div class="d-flex gap-2">
                    <a href="{{ route('admin.pengajuan.show',['deposito',$d->id]) }}"
                       class="btn btn-sm btn-light"
                       data-bs-toggle="offcanvas"
                       data-bs-target="#detailOffcanvas"
                       data-url="{{ route('admin.pengajuan.show',['deposito',$d->id]) }}">
                      <i class="bi bi-card-text"></i> Detail
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-bank" 
                            data-bs-toggle="modal"
                            data-bs-target="#depositoModal{{ $d->id }}">
                      <i class="bi bi-pencil-square"></i> Ubah
                    </button>
                  </div>
                </td>
              </tr>

              <!-- Modal Deposito -->
              <div class="modal fade" id="depositoModal{{ $d->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">
                        <i class="bi bi-piggy-bank"></i> Ubah Status Deposito
                      </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('admin.deposito.update', $d) }}">
                      @csrf @method('PATCH')
                      <div class="modal-body">
                        <div class="mb-3">
                          <label class="form-label">Status Pengajuan</label>
                          <select class="form-select" name="status" required>
                            <option value="pending" @selected($d->status === 'pending')>Pending</option>
                            <option value="diterima" @selected($d->status === 'diterima')>Diterima</option>
                            <option value="ditolak" @selected($d->status === 'ditolak')>Ditolak</option>
                          </select>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Alasan/Catatan</label>
                          <textarea class="form-control" name="catatan" rows="3" 
                                    placeholder="Tambahkan catatan (wajib saat menolak)">{{ old('catatan', $d->catatan) }}</textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-bank">
                          <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            @empty
              <tr>
                <td colspan="7" class="empty-state">
                  <i class="bi bi-inbox"></i>
                  <p class="mb-0">Tidak ada pengajuan deposito.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
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