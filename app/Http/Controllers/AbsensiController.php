<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
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
                'host' => config('database.redis.tile38.host'),
                'port' => config('database.redis.tile38.port'),
            ]);

            Log::info("Succesfully connected to Tile38");
        } catch (\Exception $e) {
            Log::error('Failed to connect to Tile38: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $user = Auth::user();

        $absensiHariIni = Absensi::where('id_user', $user->id)
            ->where('status', 'Hadir')
            ->whereDate('tgl_absen', now()->toDateString())
            ->get();

        $isClockedIn = $absensiHariIni->isNotEmpty() && $absensiHariIni->first()->status == 1;
        $isClockedOut = $absensiHariIni->isNotEmpty() && $absensiHariIni->first()->status == 0;

        $data = [
            'title'         => 'Home',
            'absensiHariIni'  => $absensiHariIni,
            'isClockedIn'   => $isClockedIn,
            'isClockedOut'  => $isClockedOut,
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

            $geoJson = '{
                "type" : "Polygon",
                "coordinates" : [
                    [
                        [101.37931691924238, 0.4780762218230241],
                        [101.37953320106662, 0.47810196876374406],
                        [101.37971343592015, 0.4781032561107826],
                        [101.37986534815384, 0.4780273026353688],
                        [101.37986534815384, 0.4780273026353688],
                        [101.37996576471511, 0.47771447729551286],
                        [101.37996705210692, 0.4776565466754453],
                        [101.37991813121809, 0.47756900707088334],
                        [101.3796374798033, 0.4775303866567629],
                        [101.37932721837686, 0.47770675321286177],
                        [101.37930404532428, 0.47787797037613744],
                        [101.37931691924238, 0.4780762218230241]
                    ]
                ]
            }';

            $geoJsonString = json_encode(json_decode($geoJson, true));

            $setCommand = ['SET', 'geofence', 'mygeofence', 'OBJECT', $geoJsonString];
            $setResponse = $this->tile38->executeRaw($setCommand);
            Log::info('Tile38 set response', ['setResponse' => $setResponse]);

            $intersectsCommand = ['INTERSECTS', 'geofence', 'POINT', $latitude, $longitude];
            $intersectsResponse = $this->tile38->executeRaw($intersectsCommand);
            Log::info('Tile38 response', ['intersectsResponse' => $intersectsResponse]);
            Log::info('Lat : ' . $latitude);
            Log::info('Long : ' . $longitude);

            if (isset($intersectsResponse[1]) && is_array($intersectsResponse[1]) && count($intersectsResponse[1]) > 0) {
                foreach ($intersectsResponse[1] as $result) {
                    if ($result[0] === 'mygeofence') {
                        return [
                            'status' => 'success',
                            'isIntersects' => true
                        ];
                    }
                }
            }

            return ['status' => 'error', 'message' => 'You are outside the geofence area'];
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

        $geofenceCheck = $this->checkGeofence($latitude, $longitude);

        if ($geofenceCheck['status'] === 'success' && $geofenceCheck['isIntersects']) {
            try {
                $isLate = now()->greaterThan(now()->setTime(9, 0));

                Absensi::create([
                    'id_user' => $user->id,
                    'tgl_absen' => $currentDate,
                    'jam_masuk' => $currentTime,
                    'koor_masuk' => json_encode([
                        'lat' => $latitude,
                        'lng' => $longitude
                    ]),
                    'status' => 'Hadir',
                    'is_late' => $isLate,
                ]);

                Log::info('Absensi berhasil', [
                    'user_id' => $user->id,
                    'date' => $currentDate,
                    'time' => $currentTime
                ]);

                return back()->with('success', 'Anda berhasil melakukan absensi');
            } catch (\Exception $e) {
                Log::error('Absensi gagal', ['error' => $e->getMessage()]);
                return back()->with('error', 'Absensi gagal, Silahkan coba lagi');
            }
        }

        Log::info('User is outside the geofence.');
        return back()->with('error', 'Anda belum memasuki area absensi');
    }

    public function clockOut(Request $request)
    {
        $user = Auth::user();
        $currentTime = now()->toTimeString();
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $geofenceCheck = $this->checkGeofence($latitude, $longitude);

        if ($geofenceCheck['status'] === 'success' && $geofenceCheck['isIntersects']) {
            try {
                $absensi = Absensi::where('id_user', $user->id)
                    ->whereDate('tgl_absen', now()->toDateString())
                    ->first();

                if ($absensi && $absensi->status == 1) {
                    $absensi->update([
                        'jam_keluar' => $currentTime,
                        'koor_keluar' => json_encode([
                            'lat' => $latitude,
                            'lng' => $longitude
                        ]),
                        'status' => 'Tidak Hadir'
                    ]);

                    return back()->with('success', 'Clock Out berhasil');
                } else {
                    return back()->with('error', 'Clock In belum dilakukan');
                }
            } catch (\Exception $e) {
                Log::error('Clock out gagal', ['error' => $e->getMessage()]);
                return back()->with('error', 'Clock out gagal! Silahkan coba lagi');
            }
        }

        Log::info('User is outside the geofence.');
        return back()->with('error', 'Anda belum memasuki area absensi');
    }
}
