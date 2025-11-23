<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Status Pengajuan {{ $tipe ?? 'Layanan' }}</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color:#f5f5f5;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f5f5; padding:40px 0;">
    <tr>
      <td align="center">
        <table width="600" cellpadding="0" cellspacing="0"
               style="background-color:#ffffff; border-radius:16px; overflow:hidden;
                      box-shadow:0 4px 20px rgba(0,0,0,0.08);">
          <!-- Header -->
          <tr>
            <td style="background:linear-gradient(135deg,#0b63f6 0%,#084dcc 100%);
                       padding:40px 30px; text-align:center;">
              <h1 style="margin:0; color:#ffffff; font-size:24px; font-weight:700;">
                Status Pengajuan {{ $tipe ?? 'Layanan' }}
              </h1>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:40px 30px;">
              <p style="margin:0 0 10px; color:#1e293b; font-size:16px;">
                Halo <strong>{{ $name ?? 'Nasabah' }}</strong>,
              </p>
              <p style="margin:0 0 24px; color:#6b7280; font-size:15px;">
                Berikut status terbaru pengajuan {{ strtolower($tipe ?? 'layanan') }}
                @if(!empty($id)) dengan ID <strong>#{{ $id }}</strong>@endif.
              </p>

              @php
                $status = strtolower($status ?? 'pending');
                $map = [
                  'pending'  => ['box' => '#0b63f6', 'bg' => '#f0f7ff', 'text' => 'PENDING'],
                  'diterima' => ['box' => '#059669', 'bg' => '#ecfdf5', 'text' => 'DITERIMA'],
                  'ditolak'  => ['box' => '#dc2626', 'bg' => '#fff1f2', 'text' => 'DITOLAK'],
                ];
                $cfg = $map[$status] ?? $map['pending'];
              @endphp

              <!-- Kotak status -->
              <div style="background:{{ $cfg['bg'] }}; border:2px dashed {{ $cfg['box'] }};
                          border-radius:12px; padding:30px; text-align:center; margin:30px 0;">
                <p style="margin:0 0 6px; color:#6b7280; font-size:14px; text-transform:uppercase;">
                  Status Pengajuan
                </p>
                <p style="margin:0; color:{{ $cfg['box'] }}; font-size:36px; font-weight:700;">
                  {{ $cfg['text'] }}
                </p>
              </div>

              <!-- Reason / info -->
              <div style="font-size:15px; line-height:1.6; margin-bottom:30px;">
                @if($status === 'ditolak')
                  <div style="background:#fff1f2; border:1px solid #fecaca; color:#991b1b;
                              padding:16px; border-radius:12px;">
                    <strong>Alasan Penolakan:</strong><br>
                    {{ $reason ?? 'Tidak memenuhi persyaratan dokumen atau kebijakan internal bank.' }}
                  </div>
                @elseif($status === 'diterima')
                  <div style="background:#ecfdf5; border:1px solid #bbf7d0; color:#065f46;
                              padding:16px; border-radius:12px;">
                    <strong>Langkah Selanjutnya:</strong><br>
                    {{ $reason ?? 'Silakan datang ke kantor cabang atau tunggu instruksi petugas untuk aktivasi.' }}
                  </div>
                @else
                  <div style="background:#f0f7ff; border:1px solid #dbeafe; color:#1e3a8a;
                              padding:16px; border-radius:12px;">
                    <strong>Informasi:</strong><br>
                    {{ $reason ?? 'Pengajuan kamu sedang ditinjau oleh tim kami. Harap menunggu konfirmasi selanjutnya.' }}
                  </div>
                @endif
              </div>

              <!-- Pesan humanized -->
              <div style="text-align:center; margin-top:10px; font-size:16px; font-weight:600;">
                @if($status === 'ditolak')
                  <p style="color:#dc2626; background:#fff1f2; display:inline-block;
                            padding:12px 18px; border-radius:12px;">
                    ❌ Maaf, pengajuan kamu belum bisa diterima.
                    <br><small>Silakan periksa kembali dokumen kamu atau hubungi petugas kami untuk informasi lebih lanjut.</small>
                  </p>
                @elseif($status === 'diterima')
                  <p style="color:#065f46; background:#ecfdf5; display:inline-block;
                            padding:12px 18px; border-radius:12px;">
                    ✅ Selamat! Pengajuan kamu telah disetujui 🎉
                    <br><small>Tim kami akan segera menghubungi kamu untuk proses selanjutnya.</small>
                  </p>
                @else
                  <p style="color:#1e3a8a; background:#f0f7ff; display:inline-block;
                            padding:12px 18px; border-radius:12px;">
                    ⏳ Pengajuan kamu sedang diproses.
                    <br><small>Kami akan mengabari kamu setelah verifikasi selesai dilakukan.</small>
                  </p>
                @endif
              </div>

              <!-- Tombol navigasi -->
              <div style="text-align:center; margin-top:32px;">
                <a href="{{ url('/dashboard') }}"
                   style="background:#fff; color:#0b63f6; border:1px solid #0b63f6;
                          padding:10px 18px; border-radius:999px; text-decoration:none; margin-right:8px;">
                  ⬅️ Kembali ke Dashboard
                </a>

                @if($status === 'diterima')
                  <a href="{{ url('/pengajuan/cetak/'.$tipe.'/'.$id) }}"
                     style="background:#0b63f6; color:#fff; padding:10px 18px;
                            border-radius:999px; text-decoration:none;">
                    🖨️ Cetak Bukti
                  </a>
                @elseif($status === 'pending')
                  <a href="{{ url('/dashboard') }}"
                     style="background:#0b63f6; color:#fff; padding:10px 18px;
                            border-radius:999px; text-decoration:none;">
                    📋 Lihat Semua Pengajuan
                  </a>
                @endif
              </div>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background-color:#f9fafb; padding:30px; text-align:center; border-top:1px solid #e5e7eb;">
              <p style="margin:0 0 10px; color:#6b7280; font-size:13px;">
                © {{ date('Y') }} PT BPR Sarimadu. All rights reserved.
              </p>
              <p style="margin:0; color:#9ca3af; font-size:12px;">
                Email ini dikirim secara otomatis, mohon tidak membalas.
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
