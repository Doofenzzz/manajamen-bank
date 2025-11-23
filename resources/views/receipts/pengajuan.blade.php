<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Bukti {{ $tipe }} - {{ $kodeBukti }}</title>
  <style>
    @page { margin: 24px 28px; }
    * { font-family: DejaVu Sans, Arial, sans-serif; }
    body { color:#1e293b; font-size:12px; }
    .header { text-align:center; margin-bottom: 16px; }
    .title { font-size:18px; font-weight:700; margin: 4px 0; }
    .subtitle { color:#6b7280; font-size:12px; }

    .badge {
      display:inline-block; padding:6px 10px; border-radius:8px;
      font-size:11px; font-weight:700; letter-spacing:.5px;
    }
    .badge-diterima { color:#065f46; background:#ecfdf5; border:1px solid #bbf7d0; }

    .meta { font-size:11px; color:#64748b; margin-top:4px; }

    .card {
      border:1px solid #e5e7eb; border-radius:12px; padding:14px; margin-bottom:12px;
      background:#ffffff;
    }
    .section-title { font-size:13px; font-weight:700; color:#0f172a; margin:0 0 8px; }

    table { width:100%; border-collapse: collapse; }
    th, td { text-align:left; padding:8px 10px; border-bottom:1px solid #e5e7eb; vertical-align:top; }
    th { background:#f8fafc; font-weight:700; color:#0f172a; width: 30%; }
    .right { text-align:right; }

    .footer {
      margin-top: 18px; font-size:11px; color:#6b7280; text-align:center;
      border-top:1px solid #e5e7eb; padding-top:10px;
    }
  </style>
</head>
<body>
  <div class="header">
    <div class="title">Bukti Pengajuan {{ $tipe }}</div>
    <div class="subtitle">{{ $appName }}</div>
    <div style="margin-top:6px;">
      <span class="badge badge-diterima">DITERIMA</span>
    </div>
    <div class="meta">Kode Bukti: {{ $kodeBukti }} &nbsp;•&nbsp; Dicetak: {{ $now->format('d M Y H:i') }}</div>
  </div>

  <div class="card">
    <div class="section-title">Data Nasabah</div>
    <table>
      <tr>
        <th>Nama</th>
        <td>{{ $nasabah->nama ?? '-' }}</td>
      </tr>
      <tr>
        <th>NIK</th>
        <td>{{ $nasabah->nik ?? '-' }}</td>
      </tr>
      <tr>
        <th>Alamat</th>
        <td>{{ $nasabah->alamat ?? '-' }}</td>
      </tr>
    </table>
  </div>

  <div class="card">
    <div class="section-title">Detail Pengajuan</div>
    <table>
      <tr>
        <th>ID Pengajuan</th>
        <td>#{{ $pengajuan->id }}</td>
      </tr>

      @if($tipe === 'Rekening')
        <tr><th>Jenis Tabungan</th><td>{{ $pengajuan->jenis_tabungan ?? '-' }}</td></tr>
        <tr><th>Unit Kerja</th><td>{{ $pengajuan->unit_kerja_pembukaan_tabungan ?? '-' }}</td></tr>
        <tr><th>Setoran Awal</th><td>Rp {{ number_format($pengajuan->setoran_awal ?? 0, 0, ',', '.') }}</td></tr>
        <tr><th>Kartu ATM</th><td>{{ ($pengajuan->kartu_atm ?? 'tidak') === 'ya' ? 'Ya' : 'Tidak' }}</td></tr>
        <tr><th>Nomor Rekening</th><td>{{ $pengajuan->nomor_rekening ?? '-' }}</td></tr>
      @elseif($tipe === 'Kredit')
        <tr><th>Jenis Kredit</th><td>{{ $pengajuan->jenis_kredit ?? '-' }}</td></tr>
        <tr><th>Tenor</th><td>{{ $pengajuan->tenor ?? '-' }} bulan</td></tr>
        <tr><th>Jumlah Pinjaman</th><td>Rp {{ number_format($pengajuan->jumlah_pinjaman ?? 0, 0, ',', '.') }}</td></tr>
        <tr><th>Tujuan</th><td>{{ $pengajuan->alasan_kredit ?? '-' }}</td></tr>
        <tr><th>Jaminan</th><td>{{ $pengajuan->jaminan_deskripsi ?? '-' }}</td></tr>
      @elseif($tipe === 'Deposito')
        <tr><th>Nominal</th><td>Rp {{ number_format($pengajuan->nominal ?? 0, 0, ',', '.') }}</td></tr>
        <tr><th>Jangka Waktu</th><td>{{ $pengajuan->jangka_waktu ?? '-' }} bulan</td></tr>
        <tr><th>Jenis Deposito</th><td>{{ $pengajuan->jenis_deposito ?? 'Deposito Berjangka' }}</td></tr>
        <tr><th>Bunga</th><td>{{ number_format($pengajuan->bunga ?? 0, 2, ',', '.') }}%</td></tr>
      @endif

      <tr><th>Status</th><td style="font-weight:700;">{{ strtoupper($pengajuan->status ?? '-') }}</td></tr>
      <tr><th>Diterima Pada</th><td>{{ optional($pengajuan->updated_at)->format('d M Y H:i') }}</td></tr>
      @if(!empty($pengajuan->catatan))
        <tr><th>Catatan</th><td>{{ $pengajuan->catatan }}</td></tr>
      @endif
    </table>
  </div>

  <div class="footer">
    Dokumen ini dihasilkan secara otomatis oleh sistem dan sah tanpa tanda tangan.
  </div>
</body>
</html>
