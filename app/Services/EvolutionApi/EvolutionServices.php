<?php

namespace App\Services\EvolutionApi;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class EvolutionServices
{
    public PendingRequest $api;

    public function __construct()
    {
        $this->api = Http::withHeaders([
            'apikey'  => config('services.evolution.apikey'),
        ])->baseUrl(config('services.evolution.base_url'));

        $this->api->acceptJson();
    }

    public function init(): PendingRequest
    {
        return $this->api;
    }
}
