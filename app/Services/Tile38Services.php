<?php

namespace App\Services;

use Predis\Client;

class Tile38Services
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'scheme' => 'tcp',
            'host'   => 'localhost',
            'port'   => 9851,
        ]);
    }

    public function executeCommand($command)
    {
        return $this->client->executeRaw($command);
    }
}
