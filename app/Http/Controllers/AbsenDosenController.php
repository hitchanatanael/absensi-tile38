<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsenDosenController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Absensi Dosen',
            'absensis' => Absensi::with('users')->get(),
        ];

        return view('admin.absensi-dosen.index', $data);
    }
}
