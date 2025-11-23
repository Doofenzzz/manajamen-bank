<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $user->loadMissing('nasabah'); // biar bisa akses $user->nasabah di Blade

        return view('profile.edit', [
            'user'    => $user,
            'nasabah' => $user->nasabah,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        // ✅ handle upload foto_ktp (PRIVATE)
        $nasabah = $user->nasabah;
        if ($nasabah && $request->hasFile('foto_ktp')) {
            $request->validate([
                'foto_ktp' => ['file','mimes:jpg,jpeg,png','max:2048'],
            ]);

            // hapus file lama kalau ada
            if (!empty($nasabah->foto_ktp)) {
                Storage::disk('private')->delete($nasabah->foto_ktp);
            }

            // simpan baru di folder private/nasabah/{id}/ktp
            $path = $request->file('foto_ktp')->store("nasabah/{$nasabah->id}/ktp", 'private');
            $nasabah->foto_ktp = $path;
            $nasabah->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
