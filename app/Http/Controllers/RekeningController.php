<?php

// ==================== RekeningController.php ====================

namespace App\Http\Controllers;

use App\Models\Rekening;
use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RekeningController extends Controller
{
    public function create(Request $request)
    {
        abort_unless($request->user()->can('submit-applications'), 403);
        return view('rekening.create');
    }

    public function store(Request $request)
    {
        $user = $request->user();
        abort_unless($user->can('submit-applications'), 403);

        if ($user->nasabah) {
            $nasabahId = $user->nasabah->id;
        } else {
            if (($user->role ?? 'nasabah') !== 'nasabah') {
                abort(403, 'Akun ini tidak dapat membuat pengajuan.');
            }

            $validatedNasabah = $request->validate([
                'nama'           => 'required|string|max:255',
                'nik'            => 'required|string|size:16|unique:nasabahs,nik',
                'alamat'         => 'required|string',
                'tanggal_lahir'  => 'required|date',
                'foto_ktp'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $validatedNasabah['user_id'] = $user->id;
            if ($request->hasFile('foto_ktp')) {
                $validatedNasabah['foto_ktp'] = $request->file('foto_ktp')->store('ktp', 'private');
            }

            $nasabah = \App\Models\Nasabah::create($validatedNasabah);
            $nasabahId = $nasabah->id;
        }

        $validatedRekening = $request->validate([
            'jenis_tabungan'                => 'required|string|max:100',
            'unit_kerja_pembukaan_tabungan' => 'required|string|max:100',
            'setoran_awal'                  => 'required|numeric|min:50000',
            'kartu_atm'                     => 'nullable|in:ya,tidak',
        ]);

        Rekening::create([
            'nasabah_id'                    => $nasabahId,
            'jenis_tabungan'                => $validatedRekening['jenis_tabungan'],
            'unit_kerja_pembukaan_tabungan' => $validatedRekening['unit_kerja_pembukaan_tabungan'],
            'setoran_awal'                  => $validatedRekening['setoran_awal'],
            'kartu_atm'                     => $validatedRekening['kartu_atm'] ?? 'ya',
            'status'                        => 'pending',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Pengajuan rekening berhasil dikirim dan sedang diproses oleh pihak bank.');
    }

    // ✅ TAMBAHAN: Edit Method
    public function edit(Rekening $rekening)
    {
        $user = Auth::user();
        
        // Pastikan yang edit adalah pemilik pengajuan
        if ($rekening->nasabah_id !== $user->nasabah->id) {
            abort(403, 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        // Hanya bisa edit jika status masih pending
        if ($rekening->status !== 'pending') {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Hanya pengajuan dengan status pending yang dapat diedit.');
        }

        return view('rekening.edit', compact('rekening'));
    }

    // ✅ TAMBAHAN: Update Method
    public function update(Request $request, Rekening $rekening)
    {
        $user = Auth::user();
        
        // Validasi ownership
        if ($rekening->nasabah_id !== $user->nasabah->id) {
            abort(403);
        }

        // Validasi status
        if ($rekening->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan dengan status pending yang dapat diedit.');
        }

        $validated = $request->validate([
            'jenis_tabungan'                => 'required|string|max:100',
            'unit_kerja_pembukaan_tabungan' => 'required|string|max:100',
            'setoran_awal'                  => 'required|numeric|min:50000',
            'kartu_atm'                     => 'nullable|in:ya,tidak',
        ]);

        $rekening->update($validated);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Pengajuan rekening berhasil diperbarui.');
    }

    public function index()
    {
        $user = Auth::user();
        $rekenings = $user->isAdmin()
            ? Rekening::with('nasabah')->latest()->get()
            : $user->nasabah->rekenings()->latest()->get();

        return view('rekening.index', compact('rekenings'));
    }

    public function destroy(Rekening $rekening)
    {
        if ($rekening->nasabah_id !== Auth::user()->nasabah->id) {
            abort(403);
        }

        $rekening->delete();
        return back()->with('info', 'Pengajuan rekening telah dihapus.');
    }
}