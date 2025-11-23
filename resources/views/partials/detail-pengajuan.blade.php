{{-- resources/views/partials/detail-pengajuan.blade.php --}}

<style>
  .detail-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
  }

  .detail-section h6 {
    color: #003366;
    font-weight: 700;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .detail-row {
    display: flex;
    padding: 0.65rem 0;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
  }

  .detail-row:last-child {
    border-bottom: none;
  }

  .detail-label {
    font-weight: 600;
    color: #666;
    min-width: 180px;
    font-size: 0.9rem;
  }

  .detail-value {
    flex: 1;
    color: #333;
  }

  .timeline {
    position: relative;
    padding-left: 2rem;
  }

  .timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
  }

  .timeline-item:last-child {
    padding-bottom: 0;
  }

  .timeline-item::before {
    content: '';
    position: absolute;
    left: -2rem;
    top: 0.5rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--blue);
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px var(--blue);
  }

  .timeline-item::after {
    content: '';
    position: absolute;
    left: -1.56rem;
    top: 1.5rem;
    width: 2px;
    height: calc(100% - 1rem);
    background: #e0e0e0;
  }

  .timeline-item:last-child::after {
    display: none;
  }

  .timeline-date {
    font-size: 0.85rem;
    color: #999;
  }

  .timeline-content {
    background: white;
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid rgba(0, 0, 0, .06);
    margin-top: 0.5rem;
  }

  .doc-item {
    background: white;
    border: 1px solid rgba(0, 0, 0, .08);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.2s ease;
  }

  .doc-item:hover {
    background: #f8f9fa;
    transform: translateX(4px);
  }

  .doc-icon {
    width: 40px;
    height: 40px;
    background: #e8f0ff;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--blue);
    font-size: 1.2rem;
  }

  .catatan-admin {
    background: #fff9e6;
    border-left: 4px solid #ffc107;
    padding: 1rem;
    border-radius: 8px;
    margin-top: 1rem;
  }

  .catatan-admin i {
    color: #ffc107;
  }
</style>

<div class="p-1">
  <!-- Info Umum -->
  <div class="detail-section">
    <h6><i class="bi bi-info-circle"></i> Informasi Umum</h6>

    <div class="detail-row">
      <span class="detail-label">Nomor Pengajuan</span>
      <span class="detail-value fw-bold">#{{ strtoupper($type) }}-{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</span>
    </div>

    <div class="detail-row">
      <span class="detail-label">Tanggal Pengajuan</span>
      <span class="detail-value">{{ $item->created_at->format('d F Y, H:i') }} WIB</span>
    </div>

    <div class="detail-row">
      <span class="detail-label">Status</span>
      <span class="detail-value"><x-status-badge :status="$item->status" /></span>
    </div>

    @if($item->processedBy)
      <div class="detail-row">
        <span class="detail-label">Diproses Oleh</span>
        <span class="detail-value">{{ $item->processedBy->name }}</span>
      </div>
    @endif

    @if($item->verified_at)
      <div class="detail-row">
        <span class="detail-label">Tanggal Verifikasi</span>
        <span class="detail-value">{{ \Carbon\Carbon::parse($item->verified_at)->format('d F Y, H:i') }} WIB</span>
      </div>
    @endif
  </div>

  <!-- Detail Spesifik -->
  <div class="detail-section">
    <h6><i class="bi bi-list-check"></i> Detail {{ ucfirst($type) }}</h6>

    @if($type === 'rekening')
      <div class="detail-row">
        <span class="detail-label">Jenis Tabungan</span>
        <span class="detail-value">{{ $item->jenis_tabungan }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Unit Kerja</span>
        <span class="detail-value">{{ $item->unit_kerja_pembukaan_tabungan }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Setoran Awal</span>
        <span class="detail-value fw-bold text-primary">Rp {{ number_format($item->setoran_awal, 0, ',', '.') }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Kartu ATM</span>
        <span class="detail-value">{{ $item->kartu_atm === 'ya' ? 'Ya' : 'Tidak' }}</span>
      </div>
      @if($item->nomor_rekening)
        <div class="detail-row">
          <span class="detail-label">Nomor Rekening</span>
          <span class="detail-value fw-bold">{{ $item->nomor_rekening }}</span>
        </div>
      @endif

    @elseif($type === 'kredit')
      <div class="detail-row">
        <span class="detail-label">Jenis Kredit</span>
        <span class="detail-value">{{ $item->jenis_kredit }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Jumlah Pinjaman</span>
        <span class="detail-value fw-bold text-primary">Rp {{ number_format($item->jumlah_pinjaman, 0, ',', '.') }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Tenor</span>
        <span class="detail-value">{{ $item->tenor }} bulan</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Bunga</span>
        <span class="detail-value">{{ rtrim(rtrim(number_format($item->bunga, 2, ',', '.'), '0'), ',') }}% per
          tahun</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Jaminan</span>
        <span class="detail-value">{{ $item->jaminan_deskripsi }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Alasan Kredit</span>
        <span class="detail-value">{{ $item->alasan_kredit }}</span>
      </div>

    @elseif($type === 'deposito')
      <div class="detail-row">
        <span class="detail-label">Jenis Deposito</span>
        <span class="detail-value">{{ $item->jenis_deposito ?? 'Deposito Berjangka' }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Nominal</span>
        <span class="detail-value fw-bold text-success">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Jangka Waktu</span>
        <span class="detail-value">{{ $item->jangka_waktu }} bulan</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Bunga</span>
        <span class="detail-value">{{ rtrim(rtrim(number_format($item->bunga, 2, ',', '.'), '0'), ',') }}% per
          tahun</span>
      </div>
    @endif
  </div>

  <!-- Lampiran Dokumen -->
  @if(
      ($type === 'kredit' && ($item->jaminan_dokumen || $item->dokumen_pendukung)) ||
      ($type === 'deposito' && $item->bukti_transfer)
    )
    <div class="detail-section">
      <h6><i class="bi bi-paperclip"></i> Lampiran Dokumen</h6>

      @if($type === 'kredit')
        @if($item->jaminan_dokumen)
          <div class="doc-item">
            <div class="d-flex align-items-center gap-3">
              <div class="doc-icon">
                <i class="bi bi-file-earmark-pdf"></i>
              </div>
              <div>
                <div class="fw-semibold">Dokumen Jaminan</div>
                <small class="text-muted">{{ basename($item->jaminan_dokumen) }}</small>
              </div>
            </div>
            <a href="{{ route('kredit.bukti', $item->id) }}"?type=jaminan" class="btn btn-sm btn-outline-bank">
              <i class="bi bi-download"></i> Unduh
            </a>
            <a href="{{ route('kredit.preview', $item->id) }}?type=jaminan" target="_blank" class="btn btn-sm btn-outline-bank">
              <i class="bi bi-eye"></i> Lihat
            </a>
          </div>
        @endif


        @if($item->dokumen_pendukung)
          <div class="doc-item">
            <div class="d-flex align-items-center gap-3">
              <div class="doc-icon">
                <i class="bi bi-file-earmark-text"></i>
              </div>
              <div>
                <div class="fw-semibold">Dokumen Pendukung</div>
                <small class="text-muted">{{ basename($item->dokumen_pendukung) }}</small>
              </div>
            </div>
            <a href="{{ route('kredit.bukti', $item->id) }}?type=pendukung" class="btn btn-sm btn-outline-bank">
              <i class="bi bi-download"></i> Unduh
            </a>
            <a href="{{ route('kredit.preview', $item->id) }}?type=pendukung" target="_blank" class="btn btn-sm btn-outline-bank">
              <i class="bi bi-eye"></i> Lihat
            </a>
          </div>
        @endif
      @endif

      @if($type === 'deposito' && $item->bukti_transfer)
        <div class="doc-item">
          <div class="d-flex align-items-center gap-3">
            <div class="doc-icon">
              <i class="bi bi-file-earmark-image"></i>
            </div>
            <div>
              <div class="fw-semibold">Bukti Transfer</div>
              <small class="text-muted">{{ basename($item->bukti_transfer) }}</small>
            </div>
          </div>
          <a href="{{ route('deposito.bukti', $item->id) }}" class="btn btn-sm btn-outline-bank">
            <i class="bi bi-download"></i> Unduh
          </a>
          <a href="{{ route('deposito.preview', $item->id) }}" target="_blank" class="btn btn-sm btn-outline-bank">
            <i class="bi bi-eye"></i> Lihat
          </a>
        </div>
      @endif
    </div>
  @endif

  <!-- Catatan dari Admin -->
  @if($item->catatan || $item->rejection_reason)
    <div class="detail-section">
      <h6><i class="bi bi-chat-left-text"></i> Catatan</h6>

      @if($item->rejection_reason)
        <div class="catatan-admin bg-danger bg-opacity-10 border-danger">
          <div class="d-flex align-items-start gap-2">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>
              <div class="fw-bold text-danger mb-1">Alasan Penolakan</div>
              <p class="mb-0">{{ $item->rejection_reason }}</p>
            </div>
          </div>
        </div>
      @endif

    </div>
  @endif

  <!-- Riwayat Status -->
  @if($item->statusHistories && $item->statusHistories->count() > 0)
    <div class="detail-section">
      <h6><i class="bi bi-clock-history"></i> Riwayat Status</h6>

      <div class="timeline">
        @foreach($item->statusHistories->sortByDesc('created_at') as $history)
          <div class="timeline-item">
            <div class="timeline-date">
              {{ $history->created_at->format('d M Y, H:i') }} WIB
            </div>
            <div class="timeline-content">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                  <span class="text-muted small">Status diubah dari</span>
                  @if($history->from)
                    <x-status-badge :status="$history->from" />
                  @else
                    <span class="badge bg-secondary">-</span>
                  @endif
                  <span class="text-muted small">ke</span>
                  <x-status-badge :status="$history->to" />
                </div>
              </div>

              @if($history->changedBy)
                <div class="text-muted small">
                  <i class="bi bi-person"></i> Oleh: {{ $history->changedBy->name }}
                </div>
              @endif

              @if($history->reason)
                <div class="mt-2 p-2 bg-light rounded small">
                  <i class="bi bi-chat-quote"></i> {{ $history->reason }}
                </div>
              @endif
            </div>
          </div>
        @endforeach

        <!-- Status Awal -->
        <div class="timeline-item">
          <div class="timeline-date">
            {{ $item->created_at->format('d M Y, H:i') }} WIB
          </div>
          <div class="timeline-content">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-send-check text-primary"></i>
              <span class="fw-semibold">Pengajuan dibuat</span>
            </div>
            <div class="text-muted small mt-1">
              Oleh: {{ $item->nasabah->user->name }}
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Tombol Aksi -->
  <div class="detail-section">
    <div class="d-flex gap-2 justify-content-end">
      @if($item->status === 'diterima')
        <a href="{{ route('pengajuan.cetak', [$type, $item->id]) }}" target="_blank" class="btn btn-bank">
          <i class="bi bi-printer"></i> Cetak Bukti
        </a>
      @endif

      @if($item->status === 'pending')
        <a href="{{ route($type . '.edit', $item) }}" class="btn btn-outline-bank">
          <i class="bi bi-pencil-square"></i> Edit Pengajuan
        </a>
        <form method="POST" action="{{ route('nasabah.cancel', [$type, $item->id]) }}"
          onsubmit="return confirm('Yakin ingin membatalkan pengajuan ini?')" class="d-inline">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-danger">
            <i class="bi bi-x-circle"></i> Batalkan Pengajuan
          </button>
        </form>
      @endif
    </div>
  </div>
</div>