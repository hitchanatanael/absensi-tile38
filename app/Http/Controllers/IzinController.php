<?php

namespace App\Http\Controllers;

use App\Models\Izin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IzinController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Pengajuan Izin',
            'izins' => Izin::all()
        ];

        return view('user.izin.index', $data);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        try {
            Izin::create([
                'id_user'      => $user->id,
                'jenis_izin'   => $request->input('jenis_izin'),
                'mulai_izin'   => $request->input('mulai_izin'),
                'selesai_izin' => $request->input('selesai_izin'),
                'keterangan'   => $request->input('keterangan'),
                'status'       => 0,
            ]);

            return redirect()->route('izin')->with('success', 'Pengajuan berhasil dibuat');
        } catch (\Exception $e) {
            Log::error('Pengajuan izin gagal', ['error' => $e->getMessage()]);
            return back()->with('error', 'Pengajuan gagal, silahkan coba lagi');
        }
    }

    public function destroy($id)
    {
        $izin = Izin::findOrFail($id);

        if ($izin->delete()) {
            return redirect()->route('izin')->with('success', 'Berhasil menghapus pengajuan');
        } else {
            return redirect()->route('izin')->with('error', 'Gagal menghapus, silahkan coba lagi');
        }
    }
}
