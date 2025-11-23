<?php

namespace App\Http\Controllers;

use App\Models\Kredit;
use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class KreditController extends Controller
{
    public function create()
    {
        $nasabahs = Nasabah::all();
        return view('kredit.create', compact('nasabahs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nasabah_id' => 'required|exists:nasabahs,id',
            'jumlah_pinjaman' => 'required|numeric|min:1000000',
            'jenis_kredit'  => 'required|string|max:100',
            'tenor' => 'required|integer|min:1',
            'jaminan_deskripsi' => 'required|string|max:255',
            'jaminan_dokumen' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen_pendukung' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'alasan_kredit' => 'required|string|max:255',
        ]);

        $jaminanPath = $request->file('jaminan_dokumen')
            ? $request->file('jaminan_dokumen')->store('jaminan', 'private')
            : null;

        $dokumenPendukungPath = $request->file('dokumen_pendukung')
            ? $request->file('dokumen_pendukung')->store('dokumen_pendukung', 'private')
            : null;

        Kredit::create([
            'nasabah_id' => Auth::user()->nasabah->id,
            'jumlah_pinjaman' => $validated['jumlah_pinjaman'],
            'jenis_kredit' => $validated['jenis_kredit'],
            'tenor' => $validated['tenor'],
            'bunga' => 5.00,
            'jaminan_deskripsi' => $validated['jaminan_deskripsi'],
            'jaminan_dokumen' => $jaminanPath,
            'dokumen_pendukung' => $dokumenPendukungPath,
            'alasan_kredit' => $validated['alasan_kredit'],
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Pengajuan kredit berhasil dikirim dan sedang diproses oleh pihak bank.');
    }

    // ✅ TAMBAHAN: Edit Method
    public function edit(Kredit $kredit)
    {
        $user = Auth::user();
        
        if ($kredit->nasabah_id !== $user->nasabah->id) {
            abort(403, 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        if ($kredit->status !== 'pending') {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Hanya pengajuan dengan status pending yang dapat diedit.');
        }

        return view('kredit.edit', compact('kredit'));
    }

    // ✅ TAMBAHAN: Update Method
    public function update(Request $request, Kredit $kredit)
    {
        $user = Auth::user();
        
        if ($kredit->nasabah_id !== $user->nasabah->id) {
            abort(403);
        }

        if ($kredit->status !== 'pending') {
            return back()->with('error', 'Hanya pengajuan dengan status pending yang dapat diedit.');
        }

        $validated = $request->validate([
            'jumlah_pinjaman' => 'required|numeric|min:1000000',
            'jenis_kredit'  => 'required|string|max:100',
            'tenor' => 'required|integer|min:1',
            'jaminan_deskripsi' => 'required|string|max:255',
            'jaminan_dokumen' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen_pendukung' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'alasan_kredit' => 'required|string|max:255',
        ]);

        // Update file jaminan jika ada upload baru
        if ($request->hasFile('jaminan_dokumen')) {
            // Hapus file lama
            if ($kredit->jaminan_dokumen) {
                Storage::disk('private')->delete($kredit->jaminan_dokumen);
            }
            $validated['jaminan_dokumen'] = $request->file('jaminan_dokumen')
                ->store('jaminan', 'private');
        }

        // Update dokumen pendukung jika ada upload baru
        if ($request->hasFile('dokumen_pendukung')) {
            if ($kredit->dokumen_pendukung) {
                Storage::disk('private')->delete($kredit->dokumen_pendukung);
            }
            $validated['dokumen_pendukung'] = $request->file('dokumen_pendukung')
                ->store('dokumen_pendukung', 'private');
        }

        $kredit->update($validated);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Pengajuan kredit berhasil diperbarui.');
    }
    public function previewBukti(Kredit $kredit)
    {
        $this->authorizeKredit($kredit);
        [$path, $label] = $this->resolveKreditPath($kredit);

        abort_unless($path, 404, "$label tidak tersedia.");
        $disk = Storage::disk('private');
        abort_unless($disk->exists($path), 404, "$label tidak ditemukan.");

        $mime = $disk->mimeType($path) ?? 'application/octet-stream';
        abort_unless(in_array($mime, ['application/pdf','image/jpeg','image/png','image/jpg','image/webp','image/gif'], true), 415);

        return response()->file($disk->path($path), [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function downloadBukti(Kredit $kredit)
    {
        $this->authorizeKredit($kredit);
        [$path, $label] = $this->resolveKreditPath($kredit);

        abort_unless($path, 404, "$label tidak tersedia.");
        abort_unless(Storage::disk('private')->exists($path), 404, "$label tidak ditemukan.");

        return Storage::disk('private')->download($path);
    }
    private function resolveKreditPath(Kredit $kredit): array
    {
        // default: jaminan
        $type = request()->string('type', 'jaminan')->lower()->value();
        if (!in_array($type, ['jaminan','pendukung'], true)) {
            abort(400, 'Tipe dokumen tidak valid.');
        }

        $path = $type === 'pendukung'
            ? $kredit->dokumen_pendukung
            : $kredit->jaminan_dokumen;

        $label = $type === 'pendukung' ? 'Dokumen Pendukung' : 'Dokumen Jaminan';

        return [$path, $label, $type];
    }
    private function authorizeKredit(Kredit $kredit): void
    {
        $user = Auth::user();

        // pemilik = user_id dari nasabah sama dengan user login
        $isOwner = $kredit->nasabah && $kredit->nasabah->user_id === $user->id;
        $isAdmin = ($user->role ?? null) === 'admin';

        abort_unless($isOwner || $isAdmin, 403, 'Tidak berhak mengakses file ini.');
    }
}
