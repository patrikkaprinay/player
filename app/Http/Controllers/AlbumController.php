<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function search(Request $request){
        if(empty($request->input('artist'))){
            return;
        }
        
        $albums = Album::where('name', 'like', '%' . $request->input('artist') . '%')->get();

        foreach ($albums as $album) {
            $artist = Artist::where('id', $album->artist)->get();
            $album['artist'] = $artist;
        }

        return $albums; 
    }
}
