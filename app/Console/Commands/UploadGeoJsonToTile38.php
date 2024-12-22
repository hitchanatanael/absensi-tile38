<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Predis\Client;
use Illuminate\Support\Facades\Log;

class UploadGeoJsonToTile38 extends Command
{
    protected $signature = 'tile38:upload-geojson';
    protected $description = 'Upload GeoJSON data to Tile38';

    protected $tile38;

    public function __construct()
    {
        parent::__construct();

        $this->tile38 = new Client([
            'scheme' => 'tcp',
            'host' => config('database.redis.tile38.host'),
            'port' => config('database.redis.tile38.port'),
        ]);
    }

    public function handle()
    {
        $filePath = storage_path('app/geojson/unri.geojson');

        if (!file_exists($filePath)) {
            $this->error("GeoJSON file not found at $filePath");
            return;
        }

        $geoJson = json_decode(file_get_contents($filePath), true);

        if (!isset($geoJson['features'])) {
            $this->error('Invalid GeoJSON format.');
            return;
        }

        foreach ($geoJson['features'] as $index => $feature) {
            $geoJsonString = json_encode($feature['geometry']);
            $id = $feature['properties']['id'] ?? $index;

            try {
                $setCommand = ['SET', 'geofence', 'object:' . $id, 'OBJECT', $geoJsonString];
                $this->tile38->executeRaw($setCommand);

                Log::info("Uploaded feature ID: $id");
            } catch (\Exception $e) {
                Log::error("Failed to upload feature ID: $id, Error: " . $e->getMessage());
            }
        }

        $this->info('GeoJSON data uploaded successfully to Tile38.');
    }
}
