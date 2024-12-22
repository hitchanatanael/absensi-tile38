<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Izin;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->get('month', date('m'));
        $selectedYear = $request->get('year', date('Y'));

        $izins = Izin::select(
            'id',
            'tgl_kirim',
            DB::raw('CASE 
                WHEN jenis_izin = 1 THEN "Izin" 
                WHEN jenis_izin = 2 THEN "Sakit" 
                ELSE "Lainnya" 
                END as type'),
            'mulai_izin',
            'selesai_izin',
            DB::raw('NULL as date'),
            DB::raw('NULL as clock_in'),
            DB::raw('NULL as clock_out'),
            'status',
            'keterangan',
            DB::raw('DATEDIFF(selesai_izin, mulai_izin) + 1 as durasi')
        )
            ->where('id_user', auth()->id())
            ->whereIn('status', [1, 2])
            ->whereMonth('tgl_kirim', $selectedMonth)
            ->whereYear('tgl_kirim', $selectedYear);

        $absensis = Absensi::select(
            'id',
            DB::raw('NULL as tgl_kirim'),
            DB::raw('3 as type'),
            DB::raw('NULL as mulai_izin'),
            DB::raw('NULL as selesai_izin'),
            'tgl_absen as date',
            'jam_masuk as clock_in',
            'jam_keluar as clock_out',
            'status',
            DB::raw('IF(is_late, "Terlambat", "Tepat Waktu") as keterangan'),
            DB::raw('NULL as durasi'),
        )
            ->where('id_user', auth()->id())
            ->whereMonth('tgl_absen', $selectedMonth)
            ->whereYear('tgl_absen', $selectedYear);

        $history = $izins->union($absensis)->orderBy('date', 'desc')->get();

        return view('user.history.index', [
            'title'         => 'History',
            'history'       => $history,
            'selectedMonth' => $selectedMonth,
            'selectedYear'  => $selectedYear,
        ]);
    }
}
