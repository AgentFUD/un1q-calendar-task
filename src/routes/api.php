<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('events')->group(function () {
    Route::post('/create', [EventController::class, 'create']);
    Route::put('/update/{event}', [EventController::class, 'update']);
    Route::post('/delete/{event}', [EventController::class, 'delete']);
    Route::get('/show/{event}', [EventController::class, 'show']);
    Route::get('/list', [EventController::class, 'list']);
});
