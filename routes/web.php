<?php

use Illuminate\Support\Facades\Route;
use App\Events\TimerUpdated;
use Illuminate\Http\Request;
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

Route::resource('/room', App\Http\Controllers\RoomController::class);

Route::get('/rooms', [App\Http\Controllers\RoomController::class, 'all']);

Route::get('/profile', function(){
    return view('profile');
});

Route::post('/open_session', [App\Http\Controllers\SessionController::class, 'openSessionForGuest']);

Route::put('/updatescore/{id}', [App\Http\Controllers\GameController::class, 'updatescore']);

Route::resource('/test', App\Http\Controllers\TestController::class)->middleware('auth');

Route::resource('/question', App\Http\Controllers\QuestionController::class)->middleware('auth');

Route::resource('/answer', App\Http\Controllers\AnswerController::class)->middleware('auth');


Route::get('/parse', [App\Http\Controllers\TestController::class, 'parseQuestion']);



// Route::post('/broadcasting/auth', function (\Illuminate\Http\Request $request) {
//     return Broadcast::auth($request);
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
