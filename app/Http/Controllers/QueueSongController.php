<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\QueueSong;
use App\Models\Song;
use Illuminate\Http\Request;

class QueueSongController extends Controller
{
    public function index(){
        $queue = QueueSong::all();
        $queueSongInfo = Array();


        foreach($queue as $oneSong){
            $song = $oneSong->song;
            
            $artist = Artist::where('id', $song->artist)->get();
            $song['artist'] = $artist;

            $album = Album::where('id', $song->album)->get();
            $song['album'] = $album;
            
            array_push($queueSongInfo, $song);
        }

        //return $queueSongInfo;
        return $queue;
        
    }

    public function add(Request $request){
        $newQueueSong = new QueueSong();
        $newQueueSong->songNumber = $request->input('song');
        $newQueueSong->save();

        return response()->json($newQueueSong);
    }

    public function remove(Request $request){
        $queueSong = QueueSong::find($request->input('id'));
        $queueSong->delete();

        return response()->json(['msg' => 'Removed from the queue']);
    }

    public function clear(){
        QueueSong::truncate();
        return response()->json(['msg' => 'Queue cleared succesfully']);
    }
}
