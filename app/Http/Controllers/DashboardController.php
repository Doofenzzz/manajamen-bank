<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use App\Models\Kredit;
use App\Models\Deposito;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $nasabah = $user->nasabah;

        if (!$nasabah) {
            return redirect()->route('nasabah.create');
        }

        // Eager load dengan relasi tambahan untuk status history
        $nasabah->load([
            'rekenings' => fn($q) => $q->latest()->with(['statusHistories.changedBy', 'processedBy']),
            'kredits' => fn($q) => $q->latest()->with(['statusHistories.changedBy', 'processedBy']),
            'depositos' => fn($q) => $q->latest()->with(['statusHistories.changedBy', 'processedBy']),
        ]);

        return view('dashboard', [
            'rekenings' => $nasabah->rekenings,
            'kredits' => $nasabah->kredits,
            'depositos' => $nasabah->depositos,
        ]);
    }

    // Detail pengajuan (AJAX)
    public function showDetail($type, $id)
    {
        $user = Auth::user();
        $nasabah = $user->nasabah;

        if (!$nasabah) {
            abort(403);
        }

        // Validasi type dan ambil data
        $item = match($type) {
            'rekening' => Rekening::with(['statusHistories.changedBy', 'processedBy', 'nasabah.user'])
                ->where('nasabah_id', $nasabah->id)
                ->findOrFail($id),
            'kredit' => Kredit::with(['statusHistories.changedBy', 'processedBy', 'nasabah.user'])
                ->where('nasabah_id', $nasabah->id)
                ->findOrFail($id),
            'deposito' => Deposito::with(['statusHistories.changedBy', 'processedBy', 'nasabah.user'])
                ->where('nasabah_id', $nasabah->id)
                ->findOrFail($id),
            default => abort(404)
        };

        return view('partials.detail-pengajuan', [
            'item' => $item,
            'type' => $type
        ]);
    }

    // Batalkan pengajuan (hanya jika pending)
    public function cancelPengajuan($type, $id)
    {
        $user = Auth::user();
        $nasabah = $user->nasabah;

        if (!$nasabah) {
            abort(403);
        }

        $item = match($type) {
            'rekening' => Rekening::where('nasabah_id', $nasabah->id)->findOrFail($id),
            'kredit' => Kredit::where('nasabah_id', $nasabah->id)->findOrFail($id),
            'deposito' => Deposito::where('nasabah_id', $nasabah->id)->findOrFail($id),
            default => abort(404)
        };

        if ($item->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan dengan status pending yang dapat dibatalkan.');
        }

        // Soft delete atau hard delete sesuai kebutuhan
        $item->delete();

        return redirect()->route('dashboard')->with('success', 'Pengajuan berhasil dibatalkan.');
    }
}