<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use Illuminate\Support\Facades\Log;

class IzinDosenController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Izin Dosen',
            'izins' => Izin::with('users')->get(),
        ];

        // dd($data);

        return view('admin.izin-dosen.index', $data);
    }

    public function setuju($id)
    {
        try {
            Izin::where('id', $id)->update([
                'status' => 1,
            ]);

            return redirect()->route('izin.dosen')->with('success', 'Berhasil acc izin');
        } catch (\Exception $e) {
            Log::error('Acc izin gagal', ['error' => $e->getMessage()]);
            return redirect()->route('izin.dosen')->with('error', 'Gagal acc izin');
        }
    }

    public function tolak($id)
    {
        try {
            Izin::where('id', $id)->update([
                'status' => 2,
            ]);

            return redirect()->route('izin.dosen')->with('success', 'Berhasil tolak izin');
        } catch (\Exception $e) {
            Log::error('Tolak izin gagal', ['error' => $e->getMessage()]);
            return redirect()->route('izin.dosen')->with('error', 'Gagal tolak izin');
        }
    }
}
