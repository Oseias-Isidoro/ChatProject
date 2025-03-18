<?php

namespace App\Services\EvolutionApi\Endpoints;

use App\Services\EvolutionApi\EvolutionServices;

class BaseEndpoint
{
    protected EvolutionServices $service;

    public function __construct()
    {
        $this->service = new EvolutionServices();
    }
}
