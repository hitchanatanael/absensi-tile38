<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Dosen;
use App\Models\Izin;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $data = [
            'title'    => 'Dashboard',
            'jmlDosen' => Dosen::count(),
            'jmlHadir' => Absensi::whereDate('tgl_absen', $today)->count(),
            'jmlIzin'  => Izin::count(),
        ];

        return view('admin.dashboard.index', $data);
    }
}
