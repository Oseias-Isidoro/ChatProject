<?php

namespace App\Services\EvolutionApi\Endpoints;

use App\Models\Number;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;

class EvolutionInstanceService extends BaseEndpoint
{
    public function create(Number $number): PromiseInterface|Response
    {
        return $this->service->init()
            ->post("/instance/create", [
                "instanceName" => $number->evolutionInstanceName,
                "qrcode" => True,
                "integration" => "WHATSAPP-BAILEYS",
                "reject_call" => True,
                "groupsIgnore" => True,
                "alwaysOnline" => True,
                "readMessages" => True,
                "readStatus" => false,
                "syncFullHistory" => True,
                "webhookBase64" => false,
                'websocket_enabled' => true,
                "webhook" => [
                    "url" => "http://host.docker.internal/api/webhook/evolution",
                    "byEvents" => false,
                    "base64" => false,
                    'events' => [
                        "APPLICATION_STARTUP",
                        "SEND_MESSAGE",
                        "MESSAGES_UPSERT",
                        "MESSAGES_UPDATE",
                        "CONNECTION_UPDATE",
                    ]
                ]
            ]);
    }

    public function delete(string $instanceName)
    {
        return $this->service->init()
            ->delete("/instance/delete/{$instanceName}");
    }

    public function connect(string $instanceName): PromiseInterface|Response
    {
        return $this->service->init()
            ->get("/instance/connect/$instanceName");
    }

    public function disconnect(string $instanceName)
    {
        return $this->service->init()
            ->delete("/instance/logout/$instanceName");
    }

    public function setWebsocket(string $instanceName): PromiseInterface|Response
    {
        return $this->service->init()
            ->post("/websocket/set/$instanceName", [
                'websocket' => [
                    'enabled' => true,
                    'events' => []
                ]
            ]);
    }
}
