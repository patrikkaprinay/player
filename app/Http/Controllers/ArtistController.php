<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;

class ArtistController extends Controller
{

    public function index(){
        return response()->json(Artist::orderBy('name')->get());
    }


    public function add(Request $request){
        $artist = new Artist();
        $artist->name = $request->input('name');
        $artist->image_path = $request->input('path');

        $artist->save();

        return response()->json($artist);
    }

    public function search(Request $request){
        if(empty($request->input('artist'))){
            return;
        }
        
        return Artist::where('name', 'like', '%' . $request->input('artist') . '%')->get();
    }
}
