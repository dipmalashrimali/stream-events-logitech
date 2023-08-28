<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/events', [EventController::class, 'index'])->name('getEventData');
    Route::put('/events/{item}', [EventController::class, 'update'])->name('update');
    Route::get('/eventsSummary', [EventController::class, 'eventsSummary'])->name('eventsSummary');
});
