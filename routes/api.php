<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\QueueSongController;
use App\Http\Controllers\SongController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('loggedin', function (){
    return Auth::user();
});


//Songs
Route::get('songs', [SongController::class, 'index']);

//Queue
Route::get('queue', [QueueSongController::class, 'index']);

Route::post('queue/add', [QueueSongController::class, 'add']);

Route::post('queue/remove', [QueueSongController::class, 'remove']);

Route::post('queue/clear', [QueueSongController::class, 'clear']);


//Artist
Route::post('artists', [ArtistController ::class, 'index']);

Route::post('add/artist', [ArtistController ::class, 'add']);

Route::post('artists/search', [ArtistController::class, 'search']);

//Albums
Route::post('albums/search', [AlbumController::class, 'search']);

Route::post('add/album', [AlbumController::class, 'add']);
