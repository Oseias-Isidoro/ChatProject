<?php

namespace App\Http\Controllers\Webhooks;


use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EvolutionApiWebhook extends Controller
{
    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        [$account_id, $phone_number] = explode('-', $request->instance);

        $number = Account::find($account_id)->numbers()->where('phone_number', $phone_number)->firstOrFail();

        if ($request->event == 'connection.update' and isset($request->data['state']) and $request->data['state'] == 'open') {
            $number->update(['status' => StatusEnum::ACTIVE]);
        } else if ($request->event == 'connection.update' and isset($request->data['state']) and $request->data['state'] == 'close') {
            $number->update(['status' => StatusEnum::INACTIVE]);
        }

        return response()->json([], );
    }
}
