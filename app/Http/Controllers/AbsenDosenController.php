<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbsenDosenController extends Controller
{
    public function index()
    {
        $data = ['title' => 'Absensi Dosen'];

        return view('admin.absensi-dosen.index', $data);
    }
}
