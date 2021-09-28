<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\DislikedSongController;
use App\Http\Controllers\LikedAlbumController;
use App\Http\Controllers\LikedSongController;
use App\Http\Controllers\QueueSongController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\SongHistoryController;
use App\Http\Controllers\SongRequestController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TagEntriesController;
use App\Http\Controllers\UserController;
use App\Models\TagEntries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('loggedin', function (){
    return Auth::user();
});

// Admins
Route::get('admins', [UserController::class, 'admins']);

// Song Requests
Route::get('requests', [SongRequestController::class, 'index']);

Route::post('request', [SongRequestController::class, 'add']);

Route::post('request/like', [SongRequestController::class, 'like']);

// Search
Route::post('search', [SearchController::class, 'index']);

// Songs
Route::get('songs', [SongController::class, 'index']);

Route::post('add/song', [SongController::class, 'add']);

Route::get('songs/few', [SongController::class, 'lastfew']);

Route::get('songs/banned', [SongController::class, 'banned']);

Route::get('songs/charts', [SongController::class, 'charts']);

// Liked Songs
Route::get('songs/liked', [LikedSongController::class, 'index']);

Route::get('songs/liked/few', [LikedSongController::class, 'lastFew']);

Route::post('songs/liked', [LikedSongController::class, 'add']);

// Disliked Songs

Route::post('songs/dislike/add', [DislikedSongController::class, 'add']);

Route::post('songs/dislike/remove', [DislikedSongController::class, 'remove']);

// Queue
Route::get('queue', [QueueSongController::class, 'index']);

Route::post('queue/add', [QueueSongController::class, 'add']);

Route::post('queue/remove', [QueueSongController::class, 'remove']);

Route::post('queue/clear', [QueueSongController::class, 'clear']);

Route::get('queue/first', [QueueSongController::class, 'first']);

Route::post('queue/next', [QueueSongController::class, 'next']);

Route::post('queue/now', [QueueSongController::class, 'now']);

// Artist
Route::post('artists', [ArtistController ::class, 'index']);

Route::post('artist', [ArtistController ::class, 'info']);

Route::post('add/artist', [ArtistController ::class, 'add']);

Route::post('artists/search', [ArtistController::class, 'search']);

Route::post('artists/searchId', [ArtistController::class, 'searchId']);

Route::get('artist/{id}/songs', [ArtistController::class, 'allSongs']);

// Route::get('artist/{id}/albums', [ArtistController::class, 'allAlbums']);

Route::get('artist/{id}/all', [ArtistController::class, 'all']);

// Albums
Route::post('albums/search', [AlbumController::class, 'search']);

Route::post('album/getAlbum', [AlbumController::class, 'getAlbum']);

Route::post('add/album', [AlbumController::class, 'add']);

Route::post('album/tag', [AlbumController::class, 'addTag']);

Route::post('album/like', [LikedAlbumController::class, 'add']);

// Rules
Route::get('rules', [RuleController::class, 'index']);

Route::get('artistCooldown', [SongHistoryController::class, 'artistCooldown']);

// Song History
Route::get('history', [SongHistoryController::class, 'index']);

Route::post('history', [SongHistoryController::class, 'add']);

Route::get('history/last', [SongHistoryController::class, 'last']);

Route::get('history/songs', [SongHistoryController::class, 'lastFew']);

Route::get('history/few-songs', [SongHistoryController::class, 'lastVFew']);

// Tag Entries
Route::get('tags/{id}', [TagEntriesController::class, 'index']);

// Tags
Route::get('tags', [TagController::class, 'index']);

Route::post('tag/all-songs', [TagEntriesController::class, 'allSongs']);

Route::middleware('adminApi')->group(function(){
    // Rules
    Route::post('rule', [RuleController::class, 'changeRuleStatus']);

    // Tag Entries
    Route::post('tag-entry', [TagEntriesController::class, 'add']);
    
    // Tags
    Route::post('add/tags', [TagController::class, 'add']);

    Route::post('tag', [TagController::class, 'delete']);

    Route::post('tag/ban', [TagController::class, 'ban']);

    // Users
    Route::get('users', [UserController::class, 'index']);

    Route::post('user/delete', [UserController::class, 'delete']);

    Route::post('user/update', [UserController::class, 'update']);

    // Albums
    Route::post('album/shuffle', [QueueSongController::class, 'albumShuffle']);
});


