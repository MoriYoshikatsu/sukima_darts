<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\SpotTripController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;
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
Route::middleware('auth')->group(function () {
	Route::get('/users/{user}/trip/index', [ParameterController::class, 'index'])->name('dart.index');
	Route::post('/users/{user}/trip/input', [ParameterController::class, 'post_parameter'])->name('post_parameter');
	Route::get('/users/{user}/trip/darts', [ParameterController::class, 'show_darts'])->name('show_darts');
	Route::post('/users/{user}/trip/list', [SpotController::class, 'create_spots'])->name('create_spots');
	Route::get('/create/users/{user}/trip/list', [SpotTripController::class, 'create'])->name('create_list');
	Route::post('/store/spot_trip/status', [SpotTripController::class, 'store_status'])->name('store_status');
	Route::get('/users/{user}/create/trip/{trip}', [TripController::class, 'create'])->name('create_trip');
	Route::post('/store/trip', [TripController::class, 'store'])->name('store_trip');
	Route::get('/users/{user}/trip/{trip}', [TripController::class, 'index'])->name('show_trip');
	Route::get('/users/{user}/trip/{trip}/index',[UserController::class,'index'])->name('users.index');
	Route::post('/users/{user}/trip/{trip}/follow',[UserController::class,'follow'])->name('users.follow');
});



require __DIR__.'/auth.php';
