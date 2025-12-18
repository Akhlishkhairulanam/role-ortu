<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /* =======================
     | RELASI
     ======================= */

    // Jika USER adalah SISWA
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    // Jika USER adalah ORANG TUA
    public function children()
    {
        return $this->hasMany(Student::class, 'parent_user_id');
    }

    /* =======================
     | ROLE CHECK
     ======================= */

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isParent()
    {
        return $this->role === 'parent';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    /* =======================
     | SCOPES
     ======================= */

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeParent($query)
    {
        return $query->where('role', 'parent');
    }

    public function scopeStudent($query)
    {
        return $query->where('role', 'student');
    }
}
