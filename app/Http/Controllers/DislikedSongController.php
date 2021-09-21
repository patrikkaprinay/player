<?php

namespace App\Http\Controllers;

use App\Models\DislikedSong;
use App\Models\LikedSong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DislikedSongController extends Controller
{
    public static function index($id)
    {
        if(Auth::check()){
            $disliked = DislikedSong::where('songId', $id)->where('userId', Auth::user()->id)->count() ;
            return $disliked;
        }
    }

    public function add(Request $request)
    {
        if(Auth::check()){
            $alreadyDislike = DislikedSong::where('songId', $request->songId)->where('userId', Auth::user()->id)->first() ? true : false;
    
            if($alreadyDislike){
                return 'You already dislike this song';
            }

            $alreadyLike = LikedSong::where('songId', $request->songId)->where('userId', Auth::user()->id)->first();

            if($alreadyLike){
                $alreadyLike->delete();
            }
    
            $newDislike = new DislikedSong();
            $newDislike->songId = $request->songId;
            $newDislike->userId = Auth::user()->id;
    
            $newDislike->save();
    
            return $newDislike;
        }
    }

    public function remove(Request $request)
    {
        if(Auth::check()){
            $songId = $request->id;
    
            DislikedSong::where('songId', $songId)->where('userId', Auth::user()->id)->first()->delete();

            return 'Dislike removed successfully';
        }
        return 'You aren\'t logged in';
    }
}
