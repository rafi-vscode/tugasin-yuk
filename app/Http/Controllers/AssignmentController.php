<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'given_at' => 'required|date',
            'due_date' => 'required|date|after_or_equal:given_at',
            'submit_link' => 'nullable|url',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,mp4|max:10240'
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('assignments', 'public');
        }

        $assignment = Assignment::create([
            'classroom_id' => $request->classroom_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'given_at' => $request->given_at,
            'due_date' => $request->due_date,
            'submit_link' => $request->submit_link,
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Tugas berhasil dibuat',
            'assignment' => $assignment
        ]);
    }

    public function markStatus(Request $request, $assignmentId)
    {
        $request->validate([
            'status' => 'required|in:selesai,belum',
            'submitted_link' => 'nullable|string',
            'submitted_file' => 'nullable|file|max:20480', // max 20MB
        ]);

        $data = [
            'assignment_id' => $assignmentId,
            'user_id' => auth()->id(),
            'status' => $request->status,
            'submitted_link' => $request->submitted_link,
            'marked_at' => now(),
        ];

        if ($request->hasFile('submitted_file')) {
            $file = $request->file('submitted_file');
            $path = $file->store('assignment_submissions', 'public');
            $data['submitted_file_path'] = $path;
        }

        // Simpan atau update
        \App\Models\AssignmentStatus::updateOrCreate(
            ['assignment_id' => $assignmentId, 'user_id' => auth()->id()],
            $data
        );

        return response()->json(['message' => 'Status tugas berhasil diperbarui']);
    }

    public function statusByUser($classroomId)
    {
        $userId = auth()->id();

        $assignments = \App\Models\Assignment::where('classroom_id', $classroomId)
            ->with(['statuses' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->get();

        return response()->json([
            'assignments' => $assignments
        ]);
    }

    public function destroy($id)
    {
        $assignment = Assignment::findOrFail($id);

        // Pastikan hanya admin kelas yang boleh hapus
        if ($assignment->classroom->created_by !== auth()->id()) {
            return response()->json([
                'message' => 'Kamu tidak memiliki izin untuk menghapus tugas ini.'
            ], 403);
        }

        // Hapus file jika ada
        if ($assignment->file_path && Storage::disk('public')->exists($assignment->file_path)) {
            Storage::disk('public')->delete($assignment->file_path);
        }

        $assignment->delete();

        return response()->json([
            'message' => 'Tugas berhasil dihapus.'
        ]);
    }
}
