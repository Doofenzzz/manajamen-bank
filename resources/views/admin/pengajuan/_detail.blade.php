@php($nas = $item->nasabah)
<div class="mb-3">
  <div class="fw-semibold">Nasabah</div>
  <div>{{ $nas->nama ?? '-' }}</div>
  <div class="text-muted small">{{ $nas->nik ?? '-' }} • {{ $nas->no_hp ?? '-' }}</div>
</div>

{{-- Alasan Kredit (khusus pengajuan kredit) --}}
@if($type === 'kredit' && isset($item->alasan_kredit))
  <div class="mb-3">
    <div class="fw-semibold">Alasan Pengajuan Kredit</div>
    <div>{{ $item->alasan_kredit }}</div>
  </div>
@endif

{{-- Lampiran (opsional, tampil kalau ada) --}}
@if(isset($item->foto_ktp) && $item->foto_ktp)
  <div class="mb-3">
    <div class="fw-semibold">KTP</div>
    <img src="{{ asset('storage/' . $item->foto_ktp) }}" class="img-fluid rounded">
  </div>
@endif
@if(isset($item->bukti_transfer) && $item->bukti_transfer)
  <div class="mb-3">
    <div class="fw-semibold">Bukti Transfer</div>
    <small class="text-muted d-block mb-2">{{ basename($item->bukti_transfer) }}</small>
    <a target="_blank"
       href="{{ route('admin.pengajuan.preview', [$type, $item->id]) }}?doc=bukti_transfer"
       class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Lihat</a>
    <a href="{{ route('admin.pengajuan.download', [$type, $item->id]) }}?doc=bukti_transfer"
       class="btn btn-sm btn-primary ms-2"><i class="bi bi-download"></i> Unduh</a>
  </div>
@endif
@if(isset($item->jaminan_dokumen) && $item->jaminan_dokumen)
  <div class="mb-3">
    <div class="fw-semibold">Dokumen Jaminan</div>
    <small class="text-muted d-block mb-2">{{ basename($item->jaminan_dokumen) }}</small>
    <a target="_blank"
       href="{{ route('admin.pengajuan.preview', [$type, $item->id]) }}?doc=jaminan"
       class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Lihat</a>
    <a href="{{ route('admin.pengajuan.download', [$type, $item->id]) }}?doc=jaminan"
       class="btn btn-sm btn-primary ms-2"><i class="bi bi-download"></i> Unduh</a>
  </div>
@endif
@if(isset($item->dokumen_pendukung) && $item->dokumen_pendukung)
  <div class="mb-3">
    <div class="fw-semibold">Dokumen Pendukung</div>
    <small class="text-muted d-block mb-2">{{ basename($item->dokumen_pendukung) }}</small>
    <a target="_blank"
       href="{{ route('admin.pengajuan.preview', [$type, $item->id]) }}?doc=pendukung"
       class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Lihat</a>
    <a href="{{ route('admin.pengajuan.download', [$type, $item->id]) }}?doc=pendukung"
       class="btn btn-sm btn-primary ms-2"><i class="bi bi-download"></i> Unduh</a>
  </div>
@endif

{{-- Status & alasan --}}
<div class="mb-3">
  <div class="fw-semibold mb-1">Status</div>
  <form method="post" action="{{ route('admin.pengajuan.status', [$type, $item->id]) }}" class="d-flex gap-2">
    @csrf
    <select name="to" class="form-select w-auto">
      @foreach(['pending', 'diterima', 'ditolak'] as $st)
        <option value="{{ $st }}" @selected($item->status === $st)>{{ ucfirst($st) }}</option>
      @endforeach
    </select>
    <input name="reason" class="form-control" placeholder="Alasan (wajib saat ditolak)">
    <button class="btn btn-primary">Update</button>
  </form>
  @if($item->rejection_reason)
    <div class="small mt-1">Alasan terakhir: <em>{{ $item->rejection_reason }}</em></div>
  @endif
</div>
{{-- Assignment --}}
<div class="mb-3">
  <div class="fw-semibold mb-1">Ditangani oleh</div>
  <form method="post" action="{{ route('admin.pengajuan.assign', [$type, $item->id]) }}" class="d-flex gap-2">
    @csrf
    <select name="processed_by" class="form-select">
      @foreach(\App\Models\User::query()->orderBy('name')->get() as $u)
        <option value="{{ $u->id }}" @selected(optional($item->processor)->id === $u->id)>{{ $u->name }}
          ({{ $u->role ?? 'user' }})</option>
      @endforeach
    </select>
    <button class="btn btn-outline-secondary">Assign</button>
  </form>
</div>
{{-- Riwayat status --}}
<div class="mb-3">
  <div class="fw-semibold">Riwayat Status</div>
  <ul class="list-group">
    @forelse($item->statusHistories->sortByDesc('created_at') as $h)
      <li class="list-group-item">
        {{ ucfirst($h->from) }} → <strong>{{ ucfirst($h->to) }}</strong>
        <div class="small text-muted">{{ $h->created_at->format('d M Y H:i') }} • oleh
          {{ $h->changedBy->name ?? 'Admin' }}</div>
        @if($h->reason)
        <div class="small">Alasan: {{ $h->reason }}</div>@endif
      </li>
    @empty
      <li class="list-group-item text-muted">Belum ada riwayat.</li>
    @endforelse
  </ul>
</div>
{{-- Catatan internal --}}
<div class="mb-3">
  <div class="fw-semibold">Catatan Internal</div>
  <form class="d-flex gap-2 mb-2" method="post" action="{{ route('admin.pengajuan.notes', [$type, $item->id]) }}">
    @csrf
    <input name="body" class="form-control" placeholder="Tulis catatan singkat…">
    <button class="btn btn-outline-secondary">Tambah</button>
  </form>
  <div class="list-group">
    @forelse($item->notes->sortByDesc('created_at') as $n)
      <div class="list-group-item">
        {{ $n->body }}
        <div class="small text-muted">{{ $n->created_at->diffForHumans() }} • {{ $n->user->name }}</div>
      </div>
    @empty
      <div class="text-muted small px-2">Belum ada catatan.</div>
    @endforelse
  </div>
</div>