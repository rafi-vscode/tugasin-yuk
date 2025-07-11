<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'user_id',
        'status',
        'submitted_file_path',
        'submitted_link',
        'marked_at',
    ];

    public $timestamps = false; // karena tidak pakai created_at/updated_at

    public function assignment()
    {
        return $this->belongsTo(\App\Models\Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
