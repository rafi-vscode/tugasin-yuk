<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    // Tambahkan properti fillable di bawah ini
    protected $fillable = [
        'user_id',
        'name',
        'bio',
        'avatar', // kalau kamu ingin upload avatar juga nantinya
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
