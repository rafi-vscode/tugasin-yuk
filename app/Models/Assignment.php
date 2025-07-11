<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function statuses()
    {
    return $this->hasMany(\App\Models\AssignmentStatus::class);
    }

    protected $fillable = [
        'classroom_id',
        'created_by',
        'title',
        'description',
        'file_path',
        'given_at',
        'due_date',
        'submit_link',
        'created_by',
    ];
}
