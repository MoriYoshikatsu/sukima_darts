<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\SpotTripController;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/users/{user}/trip/index', [TripController::class, 'index'])->middleware('auth')->name('trip.index');
Route::post('/users/{user}/trip/input', [ParameterController::class, 'post_parameter'])->middleware('auth');
Route::get('/users/{user}/trip/darts', [ParameterController::class, 'show_darts'])->middleware('auth');
Route::post('/users/{user}/trip/list', [SpotController::class, 'create_spots'])->middleware('auth');
Route::get('/create/users/{user}/trip/list', [SpotTripController::class, 'create'])->middleware('auth');
Route::post('/store/spot_trip/status', [SpotTripController::class, 'store_status'])->middleware('auth');
Route::get('/users/{user}/create/trip/{trip}', [TripController::class, 'create'])->middleware('auth');
Route::post('/store/trip', [TripController::class, 'store'])->middleware('auth');


require __DIR__.'/auth.php';
