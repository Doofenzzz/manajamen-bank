<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use App\Models\Kredit;
use App\Models\Deposito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\StatusChanged;

class AdminController extends Controller
{
    public function dashboard()
    {
        $rekenings = Rekening::with('nasabah.user')->latest()->get();
        $kredits   = Kredit::with('nasabah.user')->latest()->get();
        $depositos = Deposito::with('nasabah.user')->latest()->get();

        return view('admin.dashboard', compact('rekenings', 'kredits', 'depositos'));
    }

    /**
     * Update status pengajuan rekening.
     * - Jika diterima dan belum ada nomor_rekening => generate otomatis.
     * - Simpan catatan (optional).
     * - Kirim notifikasi status ke nasabah (jika ada user terkait).
     */
    public function updateRekening(Request $request, Rekening $rekening)
    {
        $data = $request->validate([
            'status'  => 'required|in:pending,diterima,ditolak',
            'catatan' => 'nullable|string',
        ]);

        // simpan catatan (kalau ada)
        if (array_key_exists('catatan', $data)) {
            $rekening->catatan = $data['catatan'];
        }

        // kalau status bakal jadi diterima dan belum punya nomor → generate
        if ($data['status'] === 'diterima' && empty($rekening->nomor_rekening)) {
            $rekening->nomor_rekening = $this->generateNomorRekening($rekening);
        }

        // update status (pakai helper model-mu biar audit trail tetap jalan)
        $rekening->setStatus($data['status'], $data['catatan'] ?? null, $request->user()->id);
        $rekening->save();

        // kirim notif ke nasabah (jika ada user terkait)
        if (optional($rekening->nasabah)->user) {
            Notification::send($rekening->nasabah->user, new StatusChanged(
                'Rekening', $rekening->id, $rekening->status, $data['catatan'] ?? null
            ));
        }

        return back()->with('success', 'Status rekening berhasil diupdate!');
    }

    /**
     * Update status pengajuan kredit.
     */
    public function updateKredit(Request $request, Kredit $kredit)
    {
        $data = $request->validate([
            'status'  => 'required|in:pending,diterima,ditolak',
            'catatan' => 'nullable|string',
        ]);

        if (array_key_exists('catatan', $data)) {
            $kredit->catatan = $data['catatan'];
        }

        $kredit->setStatus($data['status'], $data['catatan'] ?? null, $request->user()->id);
        $kredit->save();

        if (optional($kredit->nasabah)->user) {
            Notification::send($kredit->nasabah->user, new StatusChanged(
                'Kredit', $kredit->id, $kredit->status, $data['catatan'] ?? null
            ));
        }

        return back()->with('success', 'Status kredit berhasil diupdate!');
    }

    /**
     * Update status pengajuan deposito.
     */
    public function updateDeposito(Request $request, Deposito $deposito)
    {
        $data = $request->validate([
            'status'  => 'required|in:pending,diterima,ditolak',
            'catatan' => 'nullable|string',
        ]);

        if (array_key_exists('catatan', $data)) {
            $deposito->catatan = $data['catatan'];
        }

        $deposito->setStatus($data['status'], $data['catatan'] ?? null, $request->user()->id);
        $deposito->save();

        if (optional($deposito->nasabah)->user) {
            Notification::send($deposito->nasabah->user, new StatusChanged(
                'Deposito', $deposito->id, $deposito->status, $data['catatan'] ?? null
            ));
        }

        return back()->with('success', 'Status deposito berhasil diupdate!');
    }

    /**
     * Generator nomor rekening unik.
     * Format contoh: {kodeCabang}{YY}{nasabah_id(4digit)}{rand(3)}
     * Contoh: 101250123456
     */
    private function generateNomorRekening(Rekening $rekening): string
    {
        $kodeCabang = '101';
        $tahunYY    = now()->format('y'); // '25'
        $idNasabah4 = str_pad((string) $rekening->nasabah_id, 4, '0', STR_PAD_LEFT);

        // loop sampai unik
        do {
            $rand3 = (string) random_int(100, 999);
            $candidate = "{$kodeCabang}{$tahunYY}{$idNasabah4}{$rand3}";
        } while (
            Rekening::query()
                ->where('nomor_rekening', $candidate)
                ->exists()
        );

        return $candidate;
    }
}
