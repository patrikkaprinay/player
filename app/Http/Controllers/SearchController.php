<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Song;
use Illuminate\Http\Request;
use App\Http\Controllers\SongController;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $songsRaw = Song::where('name', 'like', $request->search . '%')->get();
        $artists = Artist::where('name', 'like', $request->search . '%')->get();
        $albums = Album::where('name', 'like', $request->search . '%')->get();
        $songs = array();

        foreach ($songsRaw as $song) {
            $song = SongController::index($song->id);
            array_push($songs, $song);
        }

        return response()->json(['songs' => $songs, 'artists' => $artists, 'albums' => $albums]);
    }
}
