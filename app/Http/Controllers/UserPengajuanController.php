<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rekening;
use App\Models\Kredit;
use App\Models\Deposito;

class UserPengajuanController extends Controller
{
    private function findModel(string $type)
    {
        return match ($type) {
            'rekening' => [Rekening::class, 'rekening'],
            'kredit'   => [Kredit::class,   'kredit'],
            'deposito' => [Deposito::class, 'deposito'],
            default    => abort(404),
        };
    }

    private function authorizeOwnerPending($record)
    {
        // Owner check: record->nasabah->user_id == Auth::id()
        abort_unless(optional($record->nasabah?->user)->id === Auth::id(), 403);
        abort_unless($record->status === 'pending', 403);
    }

    public function show(Request $req, string $type, int $id)
    {
        [$cls, ] = $this->findModel($type);
        $item = $cls::with(['nasabah.user', 'statusHistories', 'attachments'])->findOrFail($id);

        // Owner only
        abort_unless(optional($item->nasabah?->user)->id === Auth::id(), 403);

        // Return partial view (buat di-load ke offcanvas)
        return view('pengajuan._detail-user', [
            'type' => $type,
            'item' => $item,
        ]);
    }

    public function edit(Request $req, string $type, int $id)
    {
        [$cls, ] = $this->findModel($type);
        $item = $cls::with('nasabah.user')->findOrFail($id);
        $this->authorizeOwnerPending($item);

        return view('pengajuan.edit-user', [
            'type' => $type,
            'item' => $item,
        ]);
    }

    public function update(Request $req, string $type, int $id)
    {
        [$cls, ] = $this->findModel($type);
        $item = $cls::with('nasabah.user')->findOrFail($id);
        $this->authorizeOwnerPending($item);

        // Validasi minimal: contoh untuk rekening (sesuaikan per tipe)
        $data = match ($type) {
            'rekening' => $req->validate([
                'jenis_tabungan' => 'required|string|max:100',
                'setoran_awal'   => 'required|numeric|min:0',
            ]),
            'kredit' => $req->validate([
                'jumlah_pinjaman' => 'required|numeric|min:0',
                'tenor'           => 'required|integer|min:1',
            ]),
            'deposito' => $req->validate([
                'nominal'      => 'required|numeric|min:0',
                'jangka_waktu' => 'required|integer|min:1',
                'bunga'        => 'nullable|numeric|min:0',
            ]),
        };

        $item->update($data);

        return redirect()->route('dashboard')->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function cancel(Request $req, string $type, int $id)
    {
        [$cls, ] = $this->findModel($type);
        $item = $cls::with('nasabah.user')->findOrFail($id);
        $this->authorizeOwnerPending($item);

        // Kalau mau status khusus "dibatalkan", tambahkan ke enum; 
        // kalau belum ada, tandai 'ditolak' + catatan.
        $item->status = 'ditolak';
        $item->catatan = trim(($item->catatan ? $item->catatan."\n" : '') . 'Dibatalkan oleh nasabah.');
        $item->save();

        // Optional: tulis ke riwayat status
        $item->statusHistories()->create([
            'from' => 'pending',
            'to'   => 'ditolak',
            'note' => 'Dibatalkan oleh nasabah',
            'by'   => 'nasabah',
        ]);

        return back()->with('success', 'Pengajuan dibatalkan.');
    }
}
