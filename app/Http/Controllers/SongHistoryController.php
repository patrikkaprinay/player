<?php

namespace App\Http\Controllers;

use App\Models\QueueSong;
use App\Models\Song;
use App\Models\SongHistory;
use App\Models\SongStats;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SongHistoryController extends Controller
{
    public static function index(){
        # Get last
        $x = 30 /* minutes */;
        # of history

        $before30mins = Carbon::now()->subMinute($x);

        return SongHistory::where('created_at', '>', $before30mins)->get();
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

    public static function userCooldown($user)
    {
        /* Put the user on cooldown after */ $x = 15 /* songs added to the queue in the last 30 minutes */;
        $history = SongHistoryController::index();
        $queue = QueueSong::all();

        $c = 0;

        foreach ($history as $song) {
            if($song->addedBy == $user){
                $c++;
            }
        }

        foreach ($queue as $song) {
            if($song->addedBy == $user){
                $c++;
            }
        }

        return $c >= $x ? true : false;
    }

    public function add(Request $request)
    {
        if($request->id != 0){
            $songHistory = new SongHistory();
            $songHistory->songId = $request->id;
            if($user = QueueSong::find($request->played)->first()->addedBy){
                $songHistory->addedBy = $user;
            }else {
                $songHistory->addedBy = 0;
            }
            $songHistory->save();

            $songStats = SongStats::where('songId', $request->id)->first();
            if($songStats){
                $count = (int)$songStats->playCount;
                $count += 1;
                $songStats->playCount = $count++;
            } else {
                $songStats = new SongStats();
                $songStats->songId = $request->id;
                $songStats->playCount = 1;
            }

            $songStats->save();
            
            return response()->json(['song_history'=>$songHistory]);
        }
    }

    public function last()
    {
        $song = SongHistory::orderBy('id', 'desc')->first();
        $song['songNumber'] = SongController::index($song->songId);

        $queueSong = QueueSong::orderBy('id')->first();
        $queueSong->delete();

        return $song;
    }

    public function lastFew()
    {
        $history = SongHistory::latest()->take(30)->get();

        foreach ($history as $song) {
            $song['song'] = SongController::index($song->songId);
            $song['when'] = Carbon::createFromTimeString($song->created_at)->diffForHumans();
        }
        return $history;
    }

    public function lastVFew()
    {
        $history = SongHistory::latest()->take(8)->get();

        foreach ($history as $song) {
            $song['song'] = SongController::index($song->songId);
        }
        
        return $history;
    }
}
