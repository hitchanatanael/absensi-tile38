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

        $data = [
            'title' => 'Home',
            'absensiHariIni' => $absensiHariIni,
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
                    [101.3629689712017, 0.46937355787708773],
                    [101.36295220739564, 0.46947011415901285],
                    [101.36297098285843, 0.46951839229948555],
                    [101.3630427319483, 0.46955057772626807],
                    [101.36313727981437, 0.4695324734237228],
                    [101.36315069085921, 0.46947883104552296],
                    [101.36316141969506, 0.4694332350237229],
                    [101.36313258594868, 0.46935210092537777],
                    [101.36299713439585, 0.46932527973563537],
                    [101.3629689712017, 0.46937355787708773]
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

            if (isset($intersectsResponse[1]) && is_array($intersectsResponse[1]) && count($intersectsResponse[1]) > 0) {
                foreach ($intersectsResponse[1] as $result) {
                    if ($result[0] === 'mygeofence') {
                        return ['status' => 'success', 'isWithin' => true];
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

        if ($geofenceCheck['status'] === 'success' && $geofenceCheck['isWithin']) {
            try {
                Absensi::create([
                    'id_user' => $user->id,
                    'tgl_absen' => $currentDate,
                    'jam_masuk' => $currentTime,
                    // 'koor_masuk' => json_encode(['latitude' => $latitude, 'longitude' => $longitude]),
                    'koor_masuk' => $latitude . ',' . $longitude,
                    'status' => 1,
                ]);

                Log::info('Absensi berhasil', ['user_id' => $user->id, 'date' => $currentDate, 'time' => $currentTime]);
                return back()->with('success', 'Absensi berhasil');
            } catch (\Exception $e) {
                Log::error('Absensi gagal', ['error' => $e->getMessage()]);
                return back()->with('error', 'Absensi gagal, Silahkan coba lagi');
            }
        }

        Log::info('User is outside the geofence.');
        return back()->with('error', $geofenceCheck['message']);
    }

    public function clockOut(Request $request)
    {
        $user = Auth::user();
        $currentTime = now()->toTimeString();
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $geofenceCheck = $this->checkGeofence($latitude, $longitude);

        if ($geofenceCheck['status'] === 'success' && $geofenceCheck['isWithin']) {
            try {
                $absensi = Absensi::where('id_user', $user->id)
                    ->whereDate('tgl_absen', now()->toDateString())
                    ->first();

                if ($absensi && $absensi->status == 1) {
                    $absensi->update([
                        'jam_keluar' => $currentTime,
                        'koor_keluar' => $latitude . ',' . $longitude,
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
        return back()->with('error', $geofenceCheck['message']);
    }
}
