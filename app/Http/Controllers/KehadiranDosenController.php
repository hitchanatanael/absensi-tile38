<?php

namespace App\Http\Controllers;

use App\Models\Absensi;

class KehadiranDosenController extends Controller
{
    public function index()
    {
        $data = [
            'title'    => 'Absensi Dosen',
            'absensis' => Absensi::with('users')->get(),
        ];

        return view('admin.kehadiran-dosen.index', $data);
    }

    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);

        if ($absensi->delete()) {
            return redirect()->route('kehadiran.dosen')->with('success', 'Absensi berhasil dihapus');
        }

        return redirect()->route('kehadiran.dosen')->with('error', 'Gagal menghapus absensi');
    }
}
