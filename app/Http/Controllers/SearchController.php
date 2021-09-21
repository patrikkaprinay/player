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
        $albumsRaw = Album::where('name', 'like', $request->search . '%')->get();
        $songs = array();
        $albums = array();

        foreach ($songsRaw as $song) {
            $song = SongController::index($song->id);
            array_push($songs, $song);
        }

        if(count($albumsRaw) == 0 && count($songsRaw) != 0){
            foreach ($songsRaw as $song) {
                $album = SongController::index($song->id)->album;
                array_push($albums, $album);
            }
        } else {
            $albums = $albumsRaw;
        }

        foreach ($albums as $album) {
            $artistId = $album['artist'];
            $album['artist'] = Artist::find($artistId);
        }

        // if(count($songsRaw) == 0 && count($albumsRaw) != 0){
        //     $songArray = array();
        //     foreach ($albumsRaw as $album) {
        //         $song = Song::where('album', $album->id)->take(3)->get();
        //         $songArray = $song;
        //     }
        //     foreach ($songArray as $song) {
        //         $song = SongController::index($song->id);
        //         array_push($songs, $song);
        //     }
        // }

        return response()->json(['songs' => $songs, 'artists' => $artists, 'albums' => $albums]);
    }
}
