<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Dosen;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {

        $today = Carbon::today();

        $data = [
            'title'    => 'Dashboard',
            'jmlDosen' => Dosen::count(),
            'jmlHadir' => Absensi::whereDate('tgl_absen', $today)->count(),
        ];

        return view('admin.dashboard.index', $data);
    }
}
