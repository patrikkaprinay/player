<?php

namespace App\Http\Controllers;

use App\Models\LikedSong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikedSongController extends Controller
{
    public function index()
    {
        $likedSongs = LikedSong::where('userId', Auth::user()->id)->get();

        foreach ($likedSongs as $song) {
            $song->songId = SongController::index($song->songId);
        }

        return $likedSongs;
    }

    public function add(Request $request)
    {
        $likedSong = new LikedSong();
        $likedSong->userId = Auth::user()->id;
        $likedSong->songId = $request->id;

        $likedSong->save();

        return response()->json(['msg' => 'Song successfully added to your liked songs']);
    }
}
