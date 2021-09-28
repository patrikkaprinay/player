<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\QueueSong;
use App\Models\Song;
use Illuminate\Http\Request;
use App\Http\Controllers\SongHistoryController;
use App\Http\Controllers\RuleController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class QueueSongController extends Controller
{
    public function index(){
        $queue = QueueSong::orderBy('order', 'asc')->get();

        foreach($queue as $oneSong){
            $song = $oneSong->song;
            
            $artist = Artist::find($song->artist);
            $song['artist'] = $artist;

            $album = Album::find($song->album);
            $song['album'] = $album;

            if($song->addedBy){
                $song['addedBy'] = $song->addedBy();
            }

            $addedby = $oneSong->addedBy;
            if($addedby != 0){
                $user = User::find($addedby);
                $oneSong->addedBy = $user;
            } else {
                $oneSong->addedBy = (object) [
                    'name' => 'Guest'
                ];
            }
        }

        return $queue;
    }

    public function first(){
        $querysong = QueueSong::all()->sortBy('order')->first();
        if($querysong){
            $selectedSong = Song::where('id', $querysong->songNumber)->first();
            $artist = Artist::where('id', $selectedSong->artist)->first();
            $album = Album::where('id', $selectedSong->album)->first();
    
            $selectedSong['album'] = $album;
    
            $selectedSong['artist'] = $artist;
    
            $querysong['songNumber'] = $selectedSong;
    
    
            return $querysong;
        } else {
            /*
            $selectedSong = Song::where('id', SongHistory::orderBy('id', 'desc')->first()->songId)->first();
            $artist = Artist::where('id', $selectedSong->artist)->first();
            $album = Album::where('id', $selectedSong->album)->first();
    
            $selectedSong['album'] = $album;
    
            $selectedSong['artist'] = $artist;
    
            $querysong['songNumber'] = $selectedSong;
            
            return $querysong;
            */
            return null;

        }
    }

    public static function add(Request $request){
        
        if(Auth::check()){
            $user = Auth::user()->id;

            if(RuleController::SpamProtection()){
                if(SongHistoryController::userCooldown($user)){
                    return response()->json(['message'=>'You can\'t put any more songs into the queue because you put more than 15 songs into it in the last 30 minutes']);
                }
            }
        }

        if(RuleController::SameSong()){
            if(SongHistoryController::songCooldown($request->input('song'))){
                return response()->json(['message'=>'This song can\'t be played, because it was played later than 30 minutes ago']);
            }
        }
        if(RuleController::SameArtist()){
            if(SongHistoryController::artistCooldown($request->input('song'))){
                return response()->json(['message'=>'This song can\'t be played, because the artist was played more than 5 times in the last 30 minutes']);
            }
        }


        if($lastQueueOrder = QueueSong::max('order')){
            $newOrderNumber = $lastQueueOrder + 10;
        } else {
            $newOrderNumber = 10;
        }

        $newQueueSong = new QueueSong();
        $newQueueSong->songNumber = $request->input('song');
        $newQueueSong->order = $newOrderNumber;
        if(Auth::check()) {
            $newQueueSong->addedBy = $user;
        } else {
            $newQueueSong->addedBy = 0;
        }
        
        $newQueueSong->save();

        return response()->json($newQueueSong);
    }

    public function next(Request $request){
        $nowPlayed = QueueSong::where('id', $request->input('played'));
        $nowPlayed->delete();

        return $this->first();
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

    public function now(Request $request){
        $firstSong = QueueSong::all()->sortBy('order')->first();
        if($firstSong){
            $firstSong->songNumber = $request->input('id');
            $firstSong->save();

            return response()->json(['msg'=>'Modified the queue']);
        }

        $firstInQueue = new QueueSong();
        $firstInQueue->songNumber = $request->input('id');
        $firstInQueue->order = 10;

        $firstInQueue->save();

        return response()->json(['msg'=>'Added to queue']);
    }

    public function albumShuffle(Request $request)
    {
        $songs = Song::where('album', $request->id)->get();
        $songArray = array();
        $user = Auth::user()->id;

        foreach ($songs as $song) {
            array_push($songArray, $song);
        }

        shuffle($songArray);

        $firstSongfromArray = $songArray[0];

        if(QueueSong::all()->count() > 1){
            $firstTwoSongs = QueueSong::all()->sortBy('order')->take(2);
            $firstO = $firstTwoSongs[0]->order;
            $secondO = $firstTwoSongs[1]->order;
            $newO = ($firstO + $secondO) / 2;

            $newQueue = new QueueSong();
            $newQueue->songNumber = $firstSongfromArray->id;
            $newQueue->order = $newO;
            if(Auth::check()) {
                $newQueue->addedBy = $user;
            } else {
                $newQueue->addedBy = 0;
            }
            $newQueue->save();
        } else {
            $firstInQueue = new QueueSong();
            $firstInQueue->songNumber = $firstSongfromArray->id;
            $firstInQueue->order = 10;
            if(Auth::check()) {
                    $firstInQueue->addedBy = $user;
                } else {
                    $firstInQueue->addedBy = 0;
                }
    
            $firstInQueue->save();
        }


        array_shift($songArray);


        foreach ($songArray as $song) {
            if($lastQueueOrder = QueueSong::max('order')){
                $newOrderNumber = $lastQueueOrder + 10;
            } else {
                $newOrderNumber = 10;
            }
    
            $newQueueSong = new QueueSong();
            $newQueueSong->songNumber = $song->id;
            $newQueueSong->order = $newOrderNumber;
            if(Auth::check()) {
                $newQueueSong->addedBy = $user;
            } else {
                $newQueueSong->addedBy = 0;
            }
            
            $newQueueSong->save();
        }

        return $songArray;
    }
}
