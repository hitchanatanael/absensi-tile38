<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function getJenisIzinAttribute($value)
    {
        $jenis_izin = [
            '1' => 'Izin',
            '2' => 'Sakit',
        ];

        return $jenis_izin[$value] ?? 'Tidak Diketahui';
    }
}
