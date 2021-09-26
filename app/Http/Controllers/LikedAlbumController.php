<?php

namespace App\Http\Controllers;

use App\Models\LikedAlbum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikedAlbumController extends Controller
{
    public function add(Request $request)
    {
        if(Auth::check()){

            $alreadyLiked = LikedAlbum::where('albumId', $request->id)->where('userId', Auth::user()->id)->count() > 0 ? true : false;

            if($alreadyLiked){
                LikedAlbum::where('albumId', $request->id)->where('userId', Auth::user()->id)->delete();
                return 'Successfully deleted';
            }
    
            if(!$alreadyLiked){
                $newLike = new LikedAlbum();
                $newLike->userId = Auth::user()->id;
                $newLike->albumId = $request->id;
    
                $newLike->save();
                return 'Saved';
            }
            return 'You already like this song';
        }

    }
}
