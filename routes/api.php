<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/webhook/evolution', [\App\Http\Controllers\Webhooks\EvolutionApiWebhook::class, 'update'])->name('webhook.evolution');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
