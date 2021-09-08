<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\SongHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SongHistoryController extends Controller
{
    public static function index(){
        # Get last
        $x = 30 /* minutes */;
        # of history

        $before30mins = Carbon::now()->subMinute($x);

        return  SongHistory::where('created_at', '>', $before30mins)->get();
    }

    public static function artistCooldown($id)
    {
        /* Put the artist to cooldown after */ $x = 5; /* times appearing in the queue */
        $history = SongHistoryController::index();
        $songRequested = Song::find($id);
        $c = 0;

        foreach ($history as $song) {
            if($song->artist() == $songRequested->artist){
                $c++;
            }
        }
        return $c <= $x ? true : false;
    }

    public static function songCooldown($id)
    {
        # Check if the song has been played in the last $x = 30 mins

        $x = 30;

        $before30mins = Carbon::now()->subMinute($x);

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
