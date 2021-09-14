<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\LikedSong;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller
{
    public static function index($songId = 0){
        if($songId == 0){
            $songs = Song::all();
        
            foreach($songs as $song){
                $artist = Artist::where('id', $song->artist)->first();
                $song['artist'] = $artist;
    
                $album = Album::where('id', $song->album)->first();
                $song['album'] = $album;

                if(Auth::check()){
                    $liked = LikedSong::where('userId', Auth::user()->id)->where('songId', $song->id)->first() ? true : false;
                    $song['liked'] = $liked;
                }
            }
            return $songs;
        }

        $song = Song::find($songId);
        
        $artist = Artist::where('id', $song->artist)->first();
        $song['artist'] = $artist;

        $album = Album::where('id', $song->album)->first();
        $song['album'] = $album;

        if(Auth::check()){
            $liked = LikedSong::where('userId', Auth::user()->id)->where('songId', $songId)->first() ? true : false;
            $song['liked'] = $liked;
        }
        
        return $song;

    }

    public function add(Request $request){

        
        $request->validate([
            'name' => 'required|max:255',
            'artist' => 'required|integer',
            'album' => 'required|integer',
        ]);
        
        $artist = Artist::find($request->input('artist'))->name;


        $album = Album::find($request->input('album'))->name;

        if(!$artist){
            return response()->json(['msg'=>'This artist doesn\'t exist. (DB:01)']);
        }
        if(!$album){
            return response()->json(['msg'=>'This album doesn\'t exist. (DB:01)']);
        }

        $normalizeChars = array(
            'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
            'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
            'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
            'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
            'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
            'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
            'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
            'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T',
        );

        $artistFolder = preg_replace('/\s+/', '-', strtolower(strtr($artist, $normalizeChars)));

        $albumFolder = preg_replace('/\s+/', '-', strtolower(strtr($album, $normalizeChars)));

        $artistPath = '/songs/' . $artistFolder;

        $folderPath = public_path() . $artistPath;
        
        
        if(!File::exists($folderPath)){
            return response()->json(['msg' => 'This artist doesn\'t have their directory']);
        }
        
        $albumPath = public_path() . $artistPath . '/' . $albumFolder;


        if(!File::exists($albumPath)){

            return response()->json(['msg' => 'This album doesn\'t exist']);
        } else{

            $songNameFormatted = preg_replace('/\s+/', '-', strtolower(strtr($request->input('name'), $normalizeChars)));

            $songName = $songNameFormatted . '.' . $request->song->extension();

            if(!File::exists($albumPath . '/' . $songName)){
                $request->song->move($albumPath, $songName);
            } else{
                return response()->json(['msg' => 'Song already exists']);
            }

            if(count(Song::where('name', $request->input('name'))->get()) == 0){
                $newSong = new Song;

                $newSong->name = $request->input('name');
                $newSong->artist = $request->input('artist');
                $newSong->album = $request->input('album');
                $newSong->path = $artistPath . '/' . $albumFolder . '/' . $songName;
                $newSong->save();
                return response()->json(['msg'=>'Song created successfully']);
            }else{
                return response()->json(['msg'=>'Song already exists.(DB:02)']);    
            }
        }
    }
}
