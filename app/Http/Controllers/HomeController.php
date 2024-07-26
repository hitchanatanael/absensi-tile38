<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Predis\Client;

class HomeController extends Controller
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
            Log::info('Successfully connected to Tile38');
        } catch (\Exception $e) {
            Log::error('Failed to connect to Tile38: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $user = Auth::user();

        $absensiHariIni = Absensi::where('id_user', $user->id)
            ->whereDate('tgl_absen', now()->toDateString())
            ->get();

        $isClockedIn = $absensiHariIni->isNotEmpty() && $absensiHariIni->first()->status == 1;
        $isClockedOut = $absensiHariIni->isNotEmpty() && $absensiHariIni->first()->status == 0;

        $data = [
            'title' => 'Home',
            'absensiHariIni' => $absensiHariIni,
            'isClockedIn' => $isClockedIn,
            'isClockedOut' => $isClockedOut,
        ];

        return view('user.home', $data);
    }

    private function checkGeofence($latitude, $longitude)
    {
        try {
            $pingResponse = $this->tile38->ping();
            if ($pingResponse->getPayload() !== 'PONG') {
                Log::error('Tidak dapat terhubung ke Tile38.');
                return ['status' => false, 'message' => 'Tidak dapat terhubung ke Tile38.'];
            }

            $geoJson = '{
                "type" : "Polygon",
                "coordinates" : [
                    [
                        [ 101.3858240257524, 0.46995761521579044],
                        [101.38593064355403, 0.4699737079282085],
                        [ 101.38603659080346, 0.46991268972682326],
                        [ 101.38604463742999, 0.46977858378783105],
                        [ 101.38593399631509, 0.46974840995120126],
                        [ 101.38585151839307, 0.46976517319379313],
                        [ 101.385800556425, 0.46979668808975295],
                        [ 101.385800556425, 0.46979668808975295],
                        [ 101.38583274293114, 0.46994152250335974],
                        [ 101.3858240257524, 0.46995761521579044]
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
                        return ['status' => 'success', 'isIntersects' => true];
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
                    // 'koor_masuk' => $latitude . ',' . $longitude,
                    'koor_masuk' => json_encode(['lat' => $latitude, 'lng' => $longitude]),
                    'status' => 1,
                    'is_late' => $isLate,
                ]);

                Log::info('Absensi berhasil', ['user_id' => $user->id, 'date' => $currentDate, 'time' => $currentTime]);
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
                        // 'koor_keluar' => $latitude . ',' . $longitude,
                        'koor_keluar' => json_encode(['lat' => $latitude, 'lng' => $longitude]),
                        'status' => 0
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
