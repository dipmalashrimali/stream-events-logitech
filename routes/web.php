<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaceBookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return redirect('/login');
})->name('login');

Route::middleware(['auth:sanctum', 'verified'])->get('/events', function () {
    return view('welcome');
})->name('events');

// Facebook Login URL
Route::prefix('facebook')->name('facebook.')->group( function(){
    Route::get('getToken', [FaceBookController::class, 'getToken'])->name('getToken');
    Route::get('auth', [FaceBookController::class, 'loginUsingFacebook'])->name('fbLogin');
    Route::get('callback', [FaceBookController::class, 'callbackFromFacebook'])->name('callback');
    Route::get('logout', [FaceBookController::class, 'logoutUsingFacebook'])->name('fbLogout');
});

Route::get('/login', function () {
    if(\Illuminate\Support\Facades\Auth::check()) {
        return redirect('/events');
    }
    return view('welcome');
})->name('login');

Route::get('/{any}', function () {
    return view('welcome');
})->where("any",".*");

