<?php

namespace App\Http\Controllers;

use App\Models\SongHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SongHistoryController extends Controller
{
    public function index()
    {
        $songHistory = SongHistory::all();
        return $songHistory;
    }

    public static function last30mins($id)
    {
        ###
        $before30mins = Carbon::now()->subMinute(30);

        $lastHistory = SongHistory::where('created_at', '>', $before30mins)->get();

        foreach ($lastHistory as $song) {
            if($song->songId == $id){
                return true;
            }
        }
        return false;
    }

    public function add(Request $request)
    {
        $songHistory = new SongHistory();
        $songHistory->songId = $request->id;
        $songHistory->save();

        return response()->json(['song_history'=>$songHistory]);
    }
}
