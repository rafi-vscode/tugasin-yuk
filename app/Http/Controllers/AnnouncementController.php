<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'message' => 'required|string',
        ]);

        $announcement = Announcement::create([
            'classroom_id' => $request->classroom_id,
            'message' => $request->message,
            'title' => $request->title ?? null, // Optional title
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Pengumuman berhasil dikirim',
            'announcement' => $announcement
        ]);
    }

    public function index($classroomId)
    {
        $announcements = Announcement::where('classroom_id', $classroomId)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return response()->json(['announcements' => $announcements]);
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);

        // Cek apakah user adalah admin (pembuat kelas)
        if ($announcement->classroom->created_by !== auth()->id()) {
            return response()->json(['message' => 'Kamu tidak memiliki izin untuk menghapus pengumuman ini.'], 403);
        }

        $announcement->delete();

        return response()->json(['message' => 'Pengumuman berhasil dihapus']);
    }
}
