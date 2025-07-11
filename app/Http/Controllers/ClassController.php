<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ClassController extends Controller
{
    public function create(Request $request)
    {
        Log::info('User ID saat membuat kelas:', ['id' => auth()->id()]);
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $class = Classroom::create([
            'name' => $request->name,
            'description' => $request->description,
            'access_code' => Str::upper(Str::random(6)), // ✅ FIXED typo here
            'created_by' => auth()->id(),
        ]);

        // Tambahkan user pembuat ke dalam daftar anggota juga
        $class->users()->attach(auth()->id(), ['joined_at' => now()]);

        return response()->json([
            'message' => 'Kelas berhasil dibuat',
            'classroom' => $class,
        ]);
    }

    public function index()
    {
        $user = auth()->user();

        // Ambil kelas yang diikuti user
        $classes = $user->classrooms()->get();

        return response()->json([
            'classes' => $classes,
        ]);
    }

    public function join(Request $request)
    {
        $request->validate([
            'access_code' => 'required|string',
        ]);

        $classroom = Classroom::where('access_code', $request->access_code)->first();

        if (!$classroom) {
            return response()->json([
                'message' => 'Kelas tidak ditemukan dengan kode tersebut.'
            ], 404);
        }

        $user = auth()->user();

        // Cek apakah user sudah tergabung
        if ($classroom->users()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'Kamu sudah tergabung dalam kelas ini.'
            ], 409);
        }

        // Tambahkan user ke kelas
        $classroom->users()->attach($user->id, ['joined_at' => now()]);

        return response()->json([
            'message' => 'Berhasil bergabung ke kelas',
            'classroom' => $classroom,
        ]);
    }
}

