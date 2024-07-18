<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index()
    {
        $absenHariIni = Absensi::where('id_user', Auth::id())
            ->whereDate('tgl_absen', now()->toDateString())
            ->first();

        $data = [
            'title' => 'title',
            'absenHariIni' => $absenHariIni,
        ];

        return view('user.home', $data);
    }
}
