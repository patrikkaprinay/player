<?php

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\QueueSongController;
use App\Http\Controllers\SongController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('loggedin', function (){
    return Auth::user();
    //return response()->json(['user' => Auth::user()]);
});

Route::get('songs', [SongController::class, 'index']);

//Queue
Route::get('queue', [QueueSongController::class, 'index']);

Route::post('queue/add', [QueueSongController::class, 'add']);

Route::post('queue/remove', [QueueSongController::class, 'remove']);

Route::post('queue/clear', [QueueSongController::class, 'clear']);



//Artist
Route::post('add/artist', [ArtistController ::class, 'add']);

Route::post('artists', [ArtistController ::class, 'index']);
