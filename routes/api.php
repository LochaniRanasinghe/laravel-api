<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PostController as V1PostController;
use App\Http\Controllers\Api\V2\PostController as V2PostController;


Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());

    Route::prefix('v1')->group(function () {
        Route::apiResource('posts', V1PostController::class);
    });

    Route::prefix('v2')->group(function () {
        Route::apiResource('posts', V2PostController::class);
    });
});



require __DIR__.'/auth.php';