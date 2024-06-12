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
	Route::controller(ParameterController::class)->group(function(){
		Route::get('/users/{user}/trip/index', 'index')->name('index_dart');
		Route::post('/users/{user}/trip/input', 'post_parameter')->name('post_parameter');
		Route::get('/users/{user}/trip/darts', 'show_darts')->name('show_darts');
		Route::post('/users/{user}/trip/list', 'create_spots')->name('create_spots');
	});
	
	Route::controller(TripController::class)->group(function(){
		Route::get('/users/{user}/create/trip/{trip}', 'create')->name('create_trip');
		Route::post('/store/trip', 'store')->name('store_trip');
		Route::get('/users/{user}','index')->name('index_trip');
		Route::get('/users/{user}/others','others_index')->name('others_index_trip');
		Route::get('/users/{user}/edit/trip/{trip}', 'edit')->name('edit_trip');
		Route::put('/users/{user}/put/trip/{trip}', 'update')->name('update_trip');
		// Route::get('/users/{user}/create/trip/{trip}', 'create')->name('create_trip');
	});
	
	Route::controller(FollowController::class)->group(function(){
		Route::get('/users/{user}/followee/index',[FollowController::class,'followee_index'])->name('followee_index');
		Route::post('/users/{user}/followee/search', [FollowController::class, 'followee_search'])->name('followee_search');
		Route::post('/users/{user}/followee/update',[FollowController::class,'followee_update'])->name('followee_update');
		Route::get('/users/{user}/follower/index',[FollowController::class,'follower_index'])->name('follower_index');
		Route::post('/users/{user}/follower/search', [FollowController::class, 'follower_search'])->name('follower_search');
		Route::post('/users/{user}/follower/update',[FollowController::class,'follower_update'])->name('follower_update');
	});
});



require __DIR__.'/auth.php';
