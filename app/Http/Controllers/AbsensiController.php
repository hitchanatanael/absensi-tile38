<?php

namespace App\Http\Controllers;

use App\Services\Tile38Services;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
    protected $tile38;

    public function __construct(Tile38Services $tile38)
    {
        $this->tile38 = $tile38;
    }

    public function index()
    {
        $data = [
            'title' => 'Absensi',
            'model' => DB::table('absensis')->get(),
            'mytime' => now()->toDateTimeString(),
        ];

        return view('absensi.index', $data);
    }

    public function create(Request $request)
    {
        $lat = $request->input('latitude');
        $lon = $request->input('longitude');

        Log::debug('Memulai pengecekan lokasi absen:', ['lat' => $lat, 'lon' => $lon]);

        if (!$this->cekLokasi($lat, $lon)) {
            Log::debug('Lokasi absen tidak valid.');
            return response()->json(['status' => 'error', 'message' => 'Anda belum berada dalam lokasi absensi!!'], 403);
        }

        $absenId = null;
        if (!session('clockStatus') || session('clockStatus') == 'Clock In') {
            session(['clockStatus' => 'Clock Out']);
            $absenIn = new Absensi();
            $absenIn->tgl_absen = now()->toDateString();
            $absenIn->jam_masuk = now()->format('H:i');
            $absenIn->koordinat_masuk = $lat . ', ' . $lon;
            $absenIn->id_user = Auth::user()->id;
            $absenIn->status = 1;
            $absenIn->save();
            $absenId = $absenIn->id;
        } else {
            session(['clockStatus' => 'Clock In']);
            $absenOut = Absensi::where('tgl_absen', now()->toDateString())->first();
            if ($absenOut) {
                $absenOut->jam_keluar = now()->format('H:i');
                $absenOut->koordinat_keluar = $lat . ', ' . $lon;
                $absenOut->status = 0;
                $absenOut->save();
                $absenId = $absenOut->id;
            }
        }

        if ($absenId) {
            $absen = Absensi::find($absenId);
            session(["absen_{$absen->id}" => $absen]);
        }

        return redirect()->back();
    }

    private function cekLokasi($lat, $lon)
    {
        Log::debug('Memulai pengecekan lokasi:', ['lat' => $lat, 'lon' => $lon]);

        $geofence = [
            "type" => "Polygon",
            "coordinates" => [[
                [101.37455525121597, 0.4871146885461161],
                [101.37485498804526, 0.48723605411430804],
                [101.3750574948069, 0.4868263614968725],
                [101.3748197155561, 0.4866976929500698],
                [101.37455525121597, 0.4871146885461161],
            ]]
        ];

        $setGeofenceCommand = ['SET', 'city', 'pekanbaru', 'OBJECT', json_encode($geofence)];
        $setResponse = $this->tile38->executeCommand($setGeofenceCommand);

        Log::debug('Set Geofence Command:', ['command' => $setGeofenceCommand]);
        Log::debug('Set Response:', ['response' => $setResponse]);

        // Periksa apakah set geofence berhasil
        if ($setResponse === null || !isset($setResponse['ok']) || !$setResponse['ok']) {
            Log::error('Set Geofence gagal.', ['response' => $setResponse]);
            return false;
        }

        // Validasi argumen latitude dan longitude
        if (!is_numeric($lat) || !is_numeric($lon)) {
            Log::debug('Argumen latitude atau longitude tidak valid.', ['lat' => $lat, 'lon' => $lon]);
            return false;
        }

        // Menggunakan perintah 'WITHIN' untuk memeriksa koordinat pengguna
        $checkCommand = ['WITHIN', 'city', 'GET', 'pekanbaru', 'POINT', floatval($lat), floatval($lon)];
        $response = $this->tile38->executeCommand($checkCommand);

        Log::debug('Check Command:', ['command' => $checkCommand]);
        Log::debug('Response:', ['response' => $response]);

        // Memastikan bahwa response dari Tile38 benar dan objek ditemukan
        if ($response === null || !isset($response['ok']) || !isset($response['objects']) || count($response['objects']) === 0) {
            Log::error('Response dari Tile38 tidak valid atau tidak berhasil.', ['response' => $response]);
            return false;
        }

        return true;
    }
}
