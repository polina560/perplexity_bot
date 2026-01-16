<?php

use App\Http\Controllers\Api\TextController;
use App\OpenApi\Controllers\SwaggerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/user', fn(Request $request) => $request->user());

require __DIR__.'/auth.php';

if (app()->environment('local')) {
    Route::middleware('moonshine.basic')
        ->get('/openapi.json', [SwaggerController::class, 'json'])
        ->name('openapi.json')
        ->middleware('moonshine.basic');
}

Route::get('/texts', [TextController::class, 'index']);
