<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class NasabahController extends Controller
{
    // Helper: cek owner atau admin
    private function authorizeOwnerOrAdmin(Nasabah $nasabah): void
    {
        $user = Auth::user();
        $isOwner = $nasabah->user_id === ($user->id ?? null);
        $isAdmin = ($user->role ?? null) === 'admin';
        abort_unless($isOwner || $isAdmin, 403, 'Tidak berhak mengakses data ini.');
    }

    public function create()
    {
        if (Auth::user()->nasabah) {
            return redirect()->route('dashboard')->with('info', 'Profil nasabah kamu sudah lengkap.');
        }
        return view('nasabah.create');
    }

    public function store(Request $request)
    {
        if ($request->user()->nasabah) {
            return redirect()->route('dashboard')->with('info', 'Profil nasabah kamu sudah ada.');
        }

        $validated = $request->validate([
            'nama'           => ['required','string','max:255'],
            'nik'            => ['required','digits:16','unique:nasabahs,nik'],
            'alamat'         => ['required','string'],
            'tempat_lahir'   => ['required','string','max:100'],
            'tanggal_lahir'  => ['required','date'],
            'no_hp'          => ['required','string','max:15'],
            'foto_ktp'       => ['required','image','mimes:jpg,jpeg,png','max:2048'],
        ]);

        $userId  = Auth::id();
        $tmpPath = null;

        try {
            $tmpPath = $request->file('foto_ktp')->store("nasabah/{$userId}/ktp", 'private');
            if (!$tmpPath) abort(422, 'Gagal menyimpan file KTP.');

            $nasabah = DB::transaction(function () use ($validated, $userId, $tmpPath) {
                return Nasabah::create([
                    'user_id'        => $userId,
                    'nama'           => $validated['nama'],
                    'nik'            => $validated['nik'],
                    'alamat'         => $validated['alamat'],
                    'tempat_lahir'   => $validated['tempat_lahir'],
                    'tanggal_lahir'  => $validated['tanggal_lahir'],
                    'no_hp'          => $validated['no_hp'],
                    'foto_ktp'       => $tmpPath, //
                ]);
            });

            $finalDir  = "nasabah/{$nasabah->id}/ktp";
            $fileName  = basename($tmpPath);
            $finalPath = "{$finalDir}/{$fileName}";

            if ($finalPath !== $tmpPath) {
                Storage::disk('private')->makeDirectory($finalDir);
                Storage::disk('private')->move($tmpPath, $finalPath);
                $nasabah->update(['foto_ktp' => $finalPath]);
            }

            return redirect()->route('dashboard')->with('success','Data nasabah berhasil dilengkapi!');
        } catch (\Throwable $e) {
            // Cleanup file kalau DB gagal
            if ($tmpPath && Storage::disk('private')->exists($tmpPath)) {
                Storage::disk('private')->delete($tmpPath);
            }
            throw $e;
        }
    }
    // EDIT page (opsional)
    public function edit(Nasabah $nasabah)
    {
        $this->authorizeOwnerOrAdmin($nasabah);
        
        $user = $nasabah->user;
        $user->loadMissing('nasabah');

        return view('profile.edit', [
            'user'    => $user,
            'nasabah' => $nasabah,
        ]);
    }
 
    public function previewKtp(Nasabah $nasabah)
    {
        $this->authorizeOwnerOrAdmin($nasabah);

        $path = $nasabah->foto_ktp;
        abort_unless($path && Storage::disk('private')->exists($path), 404, 'KTP tidak ditemukan.');

        $disk = Storage::disk('private');
        $mime = $disk->mimeType($path) ?? 'application/octet-stream';
        abort_unless(in_array($mime, ['image/jpeg','image/png','image/jpg','image/webp']), 415, 'Tipe file tidak didukung untuk preview.');
        
        return response()->file($disk->path($path), [
            'Content-Type'           => $mime,
            'Content-Disposition'    => 'inline; filename="'.basename($path).'"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control'          => 'no-cache, no-store, must-revalidate',
            'Pragma'                 => 'no-cache',
            'Expires'                => '0',
        ]);

    }
    public function update(Request $request, Nasabah $nasabah)
    {
        // cek dulu: cuma owner atau admin yang boleh update
        $this->authorizeOwnerOrAdmin($nasabah);

        // validasi data utama
        $validated = $request->validate([
            'nama'           => ['required','string','max:255'],
            'nik'            => [
                'required',
                'digits:16',
                Rule::unique('nasabahs', 'nik')->ignore($nasabah->id), // biar nik sekarang nggak dianggap duplikat
            ],
            'alamat'         => ['required','string'],
            'tempat_lahir'   => ['required','string','max:100'],
            'tanggal_lahir'  => ['required','date'],
            'no_hp'          => ['required','string','max:15'],
            'foto_ktp'       => ['nullable','image','mimes:jpg,jpeg,png','max:2048'], // di update boleh kosong
        ]);

        DB::transaction(function () use ($request, $nasabah, $validated) {
            // update field teks dulu
            $nasabah->update([
                'nama'          => $validated['nama'],
                'nik'           => $validated['nik'],
                'alamat'        => $validated['alamat'],
                'tempat_lahir'  => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'no_hp'         => $validated['no_hp'],
            ]);

            // kalau user upload foto_ktp baru
            if ($request->hasFile('foto_ktp')) {
                // hapus file lama kalau ada
                if (!empty($nasabah->foto_ktp) && Storage::disk('private')->exists($nasabah->foto_ktp)) {
                    Storage::disk('private')->delete($nasabah->foto_ktp);
                }

                // simpan file baru di folder nasabah/{id}/ktp
                $path = $request->file('foto_ktp')->store("nasabah/{$nasabah->id}/ktp", 'private');

                $nasabah->update([
                    'foto_ktp' => $path,
                ]);
            }
        });

        return redirect()
            ->route('nasabah.edit', $nasabah->id)
            ->with('success', 'Data nasabah berhasil diperbarui.');
    }


    // Force download dari disk 'private'
    public function downloadKtp(Nasabah $nasabah)
    {
        $this->authorizeOwnerOrAdmin($nasabah);

        $path = $nasabah->foto_ktp;
        abort_unless($path && Storage::disk('private')->exists($path), 404, 'KTP tidak ditemukan.');

        return Storage::disk('private')->download($path);
    }
}
