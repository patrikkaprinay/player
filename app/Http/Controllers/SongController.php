<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Song;
use Illuminate\Http\Request;

class SongController extends Controller
{
    public function index(){
        $songs = Song::all();

        foreach($songs as $song){
            $artist = Artist::where('id', $song->artist)->get();
            $song['artist'] = $artist;

            $album = Album::where('id', $song->album)->get();
            $song['album'] = $album;
        }

        return $songs;
    }
}
