<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as AuthenticatableUser; // Penggunaan User Authenticatable
use Illuminate\Notifications\Notifiable;

class User extends AuthenticatableUser implements AuthenticatableContract
{
    use HasFactory, Notifiable, Authenticatable;

    protected $guarded = ['id'];

    public function roles()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    public function absensi()
    {
        return $this->hasMany(User::class, 'id_user');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'nip', 'nip');
    }

    public function izin()
    {
        return $this->hasMany(User::class, 'id_user');
    }

    public function scopeNamaDosen($query)
    {
        return $query->join('dosens', 'users.id', '=', 'dosens.id_user')
            ->select('dosens', 'dosens.nama')
            ->get();
    }

    public function getRoleNameAttribute()
    {
        return $this->id_role == 2 ? 'User' : 'Admin';
    }
}
