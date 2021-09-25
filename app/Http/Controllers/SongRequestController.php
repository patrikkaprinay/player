<?php

namespace App\Http\Controllers;

use App\Models\SongRequest;
use App\Models\SRequestLikes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SongRequestController extends Controller
{
    public function index()
    {
        $requests = SongRequest::orderBy('id', 'desc')->get();
        if(Auth::check()){
            foreach ($requests as $songRequest) {
                $songRequest['liked'] = SRequestLikes::where('requestId', $songRequest->id)->where('userId', Auth::user()->id)->count() > 0 ? 1 : 0;
            }
        }

        return $requests;
    }

    public function add(Request $request)
    {
        $newRequest = new SongRequest();
        $newRequest->name = $request->name;
        $newRequest->artist = $request->artist;
        $newRequest->likes = 0;
        $newRequest->save();

        return $newRequest;
    }

    public function like(Request $request)
    {
        $songRequestLike = SRequestLikes::where('userId', Auth::user()->id)->where('requestId', $request->id)->first();

        if($songRequestLike){
            $songRequestLike->delete();

            $songRequest = SongRequest::where('id', $request->id)->first();
            $songRequest->likes = $songRequest->likes - 1;
            $songRequest->save();

            return 'u a bitch';
        } else {
            $newLike = new SRequestLikes();
            $newLike->userId = Auth::user()->id;
            $newLike->requestId = $request->id;

            $newLike->save();        

            $songRequest = SongRequest::where('id', $request->id)->first();

            if($songRequest){
                $songRequest->likes = $songRequest->likes + 1;
                $songRequest->save();
            }
        }
    }
}
