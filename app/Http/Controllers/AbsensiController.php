<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Izin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Predis\Client;

class AbsensiController extends Controller
{
    protected $tile38;

    public function __construct()
    {
        try {
            $this->tile38 = new Client([
                'scheme' => 'tcp',
                'host'   => config('database.redis.tile38.host'),
                'port'   => config('database.redis.tile38.port'),
            ]);

            Log::info("Berhasil terhubung ke Tile38");
        } catch (\Exception $e) {
            Log::error('Gagal terhubung ke Tile38: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $user = Auth::user();
        $absensiHariIni = Absensi::where('id_user', $user->id)
            ->whereDate('tgl_absen', now()->toDateString())
            ->get();

        $sedangIzin = Izin::where('id_user', $user->id)
            ->whereIn('status', [0, 1])
            ->where('mulai_izin', '<=', now()->toDateString())
            ->where('selesai_izin', '>=', now()->toDateString())
            ->exists();

        $isClockedIn = $absensiHariIni->where('status', 1)->isNotEmpty();
        $isClockedOut = $absensiHariIni->where('status', 0)->isNotEmpty();


        $data = [
            'title'          => 'Home',
            'absensiHariIni' => $absensiHariIni,
            'isClockedIn'    => $isClockedIn,
            'isClockedOut'   => $isClockedOut,
            'sedangIzin'     => $sedangIzin,
        ];

        return view('user.absensi.index', $data);
    }


    private function checkGeofence($latitude, $longitude)
    {
        try {
            $pingResponse = $this->tile38->ping();
            if ($pingResponse->getPayload() !== 'PONG') {
                Log::error('Tidak dapat terhubung ke Tile38.');
                return [
                    'status'  => false,
                    'message' => 'Tidak dapat terhubung ke Tile38.'
                ];
            }

            $intersectsCommand = ['INTERSECTS', 'geofence', 'POINT', $latitude, $longitude];
            $intersectsResponse = $this->tile38->executeRaw($intersectsCommand);

            if (isset($intersectsResponse[1]) && is_array($intersectsResponse[1]) && count($intersectsResponse[1]) > 0) {
                return [
                    'status'       => 'success',
                    'isIntersects' => true
                ];
            }

            Log::info('Lokasi sekarang : ' . $latitude . ',' . $longitude);
            return ['status' => 'error', 'message' => 'Anda berada di luar area absensi'];
        } catch (\Exception $e) {
            Log::error('Error in Geofence check: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Internal Server Error'];
        }
    }


    public function clockIn(Request $request)
    {
        $user = Auth::user();
        $currentTime = now()->toTimeString();
        $currentDate = now()->toDateString();
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        Log::info('Lokasi diterima dari request: ', ['lat' => $latitude, 'lng' => $longitude]);

        if (empty($latitude) || empty($longitude)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Lokasi Anda tidak terdeteksi, coba lagi.'
            ]);
        }

        $geofenceCheck = $this->checkGeofence($latitude, $longitude);

        if ($geofenceCheck['status'] === 'success' && $geofenceCheck['isIntersects']) {
            try {
                $isLate = now()->greaterThan(now()->setTime(9, 0));

                Absensi::create([
                    'id_user'    => $user->id,
                    'tgl_absen'  => $currentDate,
                    'jam_masuk'  => $currentTime,
                    'koor_masuk' => json_encode([
                        'lat'    => $latitude,
                        'lng'    => $longitude
                    ]),
                    'status'  => 1,
                    'is_late' => $isLate,
                ]);

                Log::info('Absensi berhasil', [
                    'id_user'   => $user->id,
                    'tgl_absen' => $currentDate,
                    'jam_masuk' => $currentTime,
                    'status'    => 1,
                    'is_late'   => $isLate,
                ]);

                return response()->json([
                    'status'  => 'success',
                    'message' => 'Anda berhasil melakukan absensi'
                ]);
            } catch (\Exception $e) {
                Log::error('Absensi gagal', ['error' => $e->getMessage()]);
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Absensi gagal, Silahkan coba lagi'
                ]);
            }
        }

        Log::info('User is outside the geofence.');
        return response()->json([
            'status'  => 'error',
            'message' => 'Anda belum memasuki area absensi'
        ]);
    }

    public function clockOut(Request $request)
    {
        $user = Auth::user();
        $currentTime = now()->toTimeString();
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        Log::info('Lokasi diterima dari request: ', ['lat' => $latitude, 'lng' => $longitude]);

        if (empty($latitude) || empty($longitude)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Lokasi Anda tidak dapat terdeteksi, coba lagi.'
            ]);
        }

        $geofenceCheck = $this->checkGeofence($latitude, $longitude);

        if ($geofenceCheck['status'] === 'success' && $geofenceCheck['isIntersects']) {
            try {
                // $absensi = Absensi::where('id_user', $user->id)
                //     ->whereDate('tgl_absen', now()->toDateString())
                //     ->first();

                $absensi = Absensi::where('id_user', $user->id)
                    ->whereDate('tgl_absen', now()->toDateString())
                    ->where('status', 1)
                    ->latest('id')
                    ->first();

                if ($absensi && $absensi->status == 1) {
                    $absensi->update([
                        'jam_keluar'  => $currentTime,
                        'koor_keluar' => json_encode([
                            'lat'     => $latitude,
                            'lng'     => $longitude
                        ]),
                        'status'      => 0
                    ]);

                    Log::info($absensi);
                    return response()->json([
                        'status'  => 'success',
                        'message' => 'Clock Out berhasil'
                    ]);
                } else {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Clock In belum dilakukan'
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Clock out gagal', ['error' => $e->getMessage()]);
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Clock out gagal! Silahkan coba lagi'
                ]);
            }
        }

        Log::info('User is outside the geofence.');
        return response()->json([
            'status'  => 'error',
            'message' => 'Anda belum memasuki area absensi'
        ]);
    }
}
