<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $profile = Auth::user()->profile;

        if (!$profile) {
            return response()->json(['message' => 'Profil belum diisi.'], 404);
        }

        return response()->json($profile);
    }

    public function update(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'bio' => 'nullable|string',
        'avatar' => 'nullable|image|max:2048'
    ]);

    $user = Auth::user();

    // Ambil atau buat profil
    $profile = $user->profile ?? new \App\Models\Profile(['user_id' => $user->id]);

    $profile->name = $request->name;
    $profile->bio = $request->bio;

    // Simpan avatar jika ada
    if ($request->hasFile('avatar')) {
        $path = $request->file('avatar')->store('avatars', 'public');
        $profile->avatar = $path;
    }

    $profile->save();

    return response()->json([
        'message' => 'Profil berhasil disimpan',
        'profile' => $profile
    ]);
}

}
