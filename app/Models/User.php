<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'class_user')->withTimestamps()->withPivot('joined_at');
    }

    public function createdClassrooms()
    {
        return $this->hasMany(Classroom::class, 'created_by');
    }

    public function assignmentStatuses()
    {
        return $this->hasMany(AssignmentStatus::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }
}

