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
}
