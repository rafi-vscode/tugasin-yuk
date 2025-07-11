<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // ✅ siapa pembuat tugas
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();      // upload file (PDF, gambar, dll)
            $table->datetime('given_at');                 // tanggal diberi
            $table->datetime('due_date');                 // deadline
            $table->text('submit_link')->nullable();      // link eksternal pengumpulan (optional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
