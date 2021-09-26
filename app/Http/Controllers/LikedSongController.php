<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\LikedAlbum;
use App\Models\LikedSong;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikedSongController extends Controller
{
    public function index()
    {
        $likedSongs = LikedSong::where('userId', Auth::user()->id)->get();
        $likedAlbumsA = array();

        foreach ($likedSongs as $song) {
            $song->songId = SongController::index($song->songId);
        }

        $likedAlbums = LikedAlbum::where('userId', Auth::user()->id)->latest()->get();

        
        foreach ($likedAlbums as $l) {
            $album = Album::find($l->albumId);
            $album->artist = Artist::find($album->artist);
            array_push($likedAlbumsA, $album);
        }

        return response()->json(['songs' => $likedSongs, 'albums' => $likedAlbumsA]);
    }

    public function add(Request $request)
    {
        $userId = Auth::user()->id;
        $songId = $request->id;
        $existingLike = LikedSong::where('userId', Auth::user()->id)->where('songId', $songId)->first();

        if(!$existingLike){
            $likedSong = new LikedSong();
            $likedSong->userId = $userId;
            $likedSong->songId = $songId;
    
            $likedSong->save();
    
            return response()->json(['msg' => 'Song successfully added to your liked songs']);
        }

        $existingLike->delete();

        return response()->json(['msg' => 'Song has been removed']);

    }

    public function lastFew()
    {
        if(Auth::check()){
            $history = LikedSong::where('userId', Auth::user()->id)->latest()->take(8)->get();
    
            foreach ($history as $song) {
                $song['song'] = SongController::index($song->songId);
            }
            return $history;
        }
    }
}
