<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Infrastructure\Laravel\Http\Controllers\ContactController;

Route::prefix('contacts')->group(function (): void {
    Route::get('/', [ContactController::class, 'index']);
    Route::post('/', [ContactController::class, 'store']);
    Route::get('{id}', [ContactController::class, 'show']);
    Route::put('{id}', [ContactController::class, 'update']);
    Route::delete('{id}', [ContactController::class, 'destroy']);
});
