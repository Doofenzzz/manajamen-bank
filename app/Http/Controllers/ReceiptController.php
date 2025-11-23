<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Rekening;
use App\Models\Kredit;
use App\Models\Deposito;

class ReceiptController extends Controller
{
    public function cetak(Request $request, string $tipe, int $id)
    {
        // Normalisasi tipe
        $tipe = Str::lower($tipe); // 'rekening' | 'kredit' | 'deposito'

        $map = [
            'rekening' => Rekening::class,
            'kredit'   => Kredit::class,
            'deposito' => Deposito::class,
        ];

        abort_unless(isset($map[$tipe]), 404, 'Tipe pengajuan tidak ditemukan.');

        /** @var \Illuminate\Database\Eloquent\Model $pengajuan */
        $pengajuan = $map[$tipe]::query()->with('nasabah')->findOrFail($id);

        // Authorization: pemilik pengajuan atau admin
        $user = $request->user();
        $isOwner = $pengajuan->nasabah && method_exists($pengajuan->nasabah, 'user')
            ? optional($pengajuan->nasabah->user)->id === $user->id
            : ($pengajuan->nasabah_id ?? null) === optional($user->nasabah)->id;

        $isAdmin = method_exists($user, 'isAdmin') ? $user->isAdmin() : ($user->role ?? null) === 'admin';

        abort_unless($isOwner || $isAdmin, 403, 'Kamu tidak berhak mengakses bukti ini.');

        // Hanya boleh cetak kalau sudah diterima
        abort_unless(($pengajuan->status ?? null) === 'diterima', 403, 'Bukti hanya tersedia untuk pengajuan yang DITERIMA.');

        // Data ke view
        $data = [
            'appName'    => config('app.name', 'PT BPR Sarimadu'),
            'tipe'       => ucfirst($tipe),
            'pengajuan'  => $pengajuan,
            'nasabah'    => $pengajuan->nasabah,
            'now'        => now(),
            'kodeBukti'  => strtoupper($tipe) . '-' . str_pad($pengajuan->id, 6, '0', STR_PAD_LEFT),
        ];

        $pdf = Pdf::loadView('receipts.pengajuan', $data)->setPaper('a4', 'portrait');

        $filename = 'Bukti_' . ucfirst($tipe) . '_' . $data['kodeBukti'] . '.pdf';
        return $pdf->stream($filename); // atau ->download($filename);
    }
}
