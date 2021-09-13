<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ArtistController extends Controller
{

    public function index(){
        return response()->json(Artist::orderBy('name')->get());
    }


    public function add(Request $request){

        $request->validate([
            'image' => 'required',
            'name' => 'required|max:255'
        ]);

        $existingArtist = Artist::where('name', $request->input('name'))->first();

        if($existingArtist){
            return response()->json(['msg'=>'This artist already exists']);
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

        $artistFolder = preg_replace('/\s+/', '-', strtolower(strtr($request->input('name'), $normalizeChars)));

        $artistPath = public_path() . '/songs/' . $artistFolder;

        if(!File::exists($artistPath)){
            File::makeDirectory($artistPath);

            $extension = $request->image->extension();

            $request->image->move($artistPath, 'pfp.' . $request->image->extension());


            if(File::exists($artistPath . '/pfp.' . $extension)){
                $artist = new Artist();
                $artist->name = $request->input('name');
                $artist->image_path = '/songs/' . $artistFolder . '/pfp.' . $extension;
                $artist->save();

                return response()->json(['msg'=>'Artist created successfully', 'artist'=>$artist]);
            } else{
                return response()->json(['msg'=>'Something went wrong']);
            }

        } else{
            return response()->json(['msg'=>'Artist already exists']);
        }
    }

    public function info(Request $request){
        if(empty($request->input('artist'))){
            return;
        }
        
        return Artist::where('name', 'like', '%' . $request->input('artist') . '%')->first();
    }

    public function search(Request $request){
        if(empty($request->input('artist'))){
            return;
        }
        
        return Artist::where('name', 'like', '%' . $request->input('artist') . '%')->get();
    }

    public function searchId(Request $request){
        return Artist::where('id', $request->input('id'))->first();
    }

    public function allSongs($id)
    {
        $songs = Song::where('artist', $id)->get();

        foreach($songs as $song){
            $artist = Artist::where('id', $song->artist)->first();
            $song['artist'] = $artist;

            $album = Album::where('id', $song->album)->first();
            $song['album'] = $album;
        }

        return $songs;
    }

    public function all($id)
    {
        $songs = Song::where('artist', $id)->get();

        foreach($songs as $song){
            $artist = Artist::where('id', $song->artist)->first();
            $song['artist'] = $artist;

            $album = Album::where('id', $song->album)->first();
            $song['album'] = $album;
        }

        $albums = Album::where('artist', $id)->get();

        return response()->json(['songs'=>$songs, 'albums'=>$albums]);
    }
}
