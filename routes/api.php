<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\LikedSongController;
use App\Http\Controllers\QueueSongController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\SongHistoryController;
use App\Models\LikedSong;
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

Route::post('add/song', [SongController::class, 'add']);

// Liked Songs
Route::get('songs/liked', [LikedSongController::class, 'index']);

Route::post('songs/liked', [LikedSongController::class, 'add']);

//Queue
Route::get('queue', [QueueSongController::class, 'index']);

Route::post('queue/add', [QueueSongController::class, 'add']);

Route::post('queue/remove', [QueueSongController::class, 'remove']);

Route::post('queue/clear', [QueueSongController::class, 'clear']);

Route::get('queue/first', [QueueSongController::class, 'first']);

Route::post('queue/next', [QueueSongController::class, 'next']);

Route::post('queue/now', [QueueSongController::class, 'now']);

//Artist
Route::post('artists', [ArtistController ::class, 'index']);

Route::post('artist', [ArtistController ::class, 'info']);

Route::post('add/artist', [ArtistController ::class, 'add']);

Route::post('artists/search', [ArtistController::class, 'search']);

Route::post('artists/searchId', [ArtistController::class, 'searchId']);

Route::get('artist/{id}/songs', [ArtistController::class, 'allSongs']);

//Route::get('artist/{id}/albums', [ArtistController::class, 'allAlbums']);

Route::get('artist/{id}/all', [ArtistController::class, 'all']);

//Albums
Route::post('albums/search', [AlbumController::class, 'search']);

Route::post('add/album', [AlbumController::class, 'add']);

//Rules
Route::get('rules', [RuleController::class, 'index']);

Route::post('rule', [RuleController::class, 'changeRuleStatus']);

Route::get('artistCooldown', [SongHistoryController::class, 'artistCooldown']);

//Song History
Route::get('history', [SongHistoryController::class, 'index']);

Route::post('history', [SongHistoryController::class, 'add']);