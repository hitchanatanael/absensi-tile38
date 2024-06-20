<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'tgl_absen',
        'jam_masuk',
        'jam_keluar',
        'koordinat_masuk',
        'koordinat_keluar',
        'status',
    ];
}
