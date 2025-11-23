<?php

namespace App\Http\Controllers;

use App\Models\Deposito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DepositoController extends Controller
{
    public function create()
    {
        return view('deposito.create');
    }

    public function store(Request $request)
    {
        $user = $request->user();
        abort_unless($user && $user->nasabah, 422, 'Profil nasabah belum lengkap.');

        $validated = $request->validate([
            'nominal'         => ['required','numeric','min:1000000'],
            'jangka_waktu'    => ['required','integer','in:1,3,6,12,24'],
            'jenis_deposito'  => ['required','string','max:100'],
            'bukti_transfer'  => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:2048'],
            'catatan'         => ['nullable','string','max:1000'],
        ]);

        $data = $validated;
        $data['nasabah_id'] = $user->nasabah->id;
        $data['bunga']      = 5.00;
        $data['status']     = 'pending';
        $data['jenis_deposito'] = $data['jenis_deposito'] ?: 'Deposito Berjangka';

        if ($request->hasFile('bukti_transfer')) {
            $data['bukti_transfer'] = $request->file('bukti_transfer')
                ->store('bukti_deposito', 'private');
        } else {
            $data['bukti_transfer'] = null;
        }

        Deposito::create($data);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Pengajuan deposito berhasil dikirim dan sedang diproses oleh pihak bank.');
    }

    // ✅ TAMBAHAN: Edit Method
    public function edit(Deposito $deposito)
    {
        $user = Auth::user();
        
        if ($deposito->nasabah_id !== $user->nasabah->id) {
            abort(403, 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        if ($deposito->status !== 'pending') {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Hanya pengajuan dengan status pending yang dapat diedit.');
        }

        return view('deposito.edit', compact('deposito'));
    }

    // ✅ TAMBAHAN: Update Method
    public function update(Request $request, Deposito $deposito)
    {
        $user = Auth::user();
        
        if ($deposito->nasabah_id !== $user->nasabah->id) {
            abort(403);
        }

        if ($deposito->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan dengan status pending yang dapat diedit.');
        }

        $validated = $request->validate([
            'nominal'         => ['required','numeric','min:1000000'],
            'jangka_waktu'    => ['required','integer','in:1,3,6,12,24'],
            'jenis_deposito'  => ['required','string','max:100'],
            'bukti_transfer'  => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:2048'],
            'catatan'         => ['nullable','string','max:1000'],
        ]);

        // Update file bukti transfer jika ada upload baru
        if ($request->hasFile('bukti_transfer')) {
            // Hapus file lama
            if ($deposito->bukti_transfer) {
                Storage::disk('private')->delete($deposito->bukti_transfer);
            }
            $validated['bukti_transfer'] = $request->file('bukti_transfer')
                ->store('bukti_deposito', 'private');
        }

        $deposito->update($validated);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Pengajuan deposito berhasil diperbarui.');
    }
    public function downloadBukti(Deposito $deposito)
    {
        $user = Auth::user();

        $isOwner = $deposito->nasabah && $deposito->nasabah->user_id === $user->id;
        $isAdmin = ($user->role ?? null) === 'admin';
        abort_unless($isOwner || $isAdmin, 403, 'Tidak berhak mengakses file ini.');

        $path = $deposito->bukti_transfer;
        abort_unless($path, 404, 'File tidak tersedia.');
        abort_unless(Storage::disk('private')->exists($path), 404, 'File tidak ditemukan.');

        return Storage::disk('private')->download($path); // attachment → download
    }
    public function previewBukti(Deposito $deposito)
    {
        $user = Auth::user();

        $isOwner = $deposito->nasabah && $deposito->nasabah->user_id === $user->id;
        $isAdmin = ($user->role ?? null) === 'admin';
        abort_unless($isOwner || $isAdmin, 403, 'Tidak berhak mengakses file ini.');

        $path = $deposito->bukti_transfer;
        abort_unless($path, 404, 'File tidak tersedia.');
        $disk = Storage::disk('private');
        abort_unless($disk->exists($path), 404, 'File tidak ditemukan.');

        $mime = $disk->mimeType($path) ?? 'application/octet-stream';

        // izinkan preview hanya untuk tipe yang umum di-browser
        $allowed = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        abort_unless(in_array($mime, $allowed, true), 415, 'Tipe file tidak didukung untuk preview.');

        $fullPath = $disk->path($path);

        return response()->file($fullPath, [
            'Content-Type'              => $mime,
            'Content-Disposition'       => 'inline; filename="'.basename($path).'"',
            'X-Content-Type-Options'    => 'nosniff',
            // (opsional) 'Cache-Control' => 'private, max-age=0, no-store',
        ]);
    }
}