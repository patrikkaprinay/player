<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AlbumController extends Controller
{
    public function search(Request $request){
        if(empty($request->input('album'))){
            return;
        }
        
        $albums = Album::where('name', 'like', '%' . $request->input('album') . '%')->get();

        if(count($albums) == 0){
            $artist = Artist::where('name', 'like', '%' . $request->input('album') . '%')->value('id');


            $albumsFromArtist = Album::where('artist', $artist)->get();

            foreach ($albumsFromArtist as $album) {
                $artist = Artist::where('id', $album->artist)->get();
                $album['artist'] = $artist;
            }

            return $albumsFromArtist;

        } else{
            foreach ($albums as $album) {
                $artist = Artist::where('id', $album->artist)->get();
                $album['artist'] = $artist;
            }
    
            return $albums; 
        }

    }

    public function add(Request $request){
        $request->validate([
            'name' => 'required',
            'artist' => 'required',
            'date' => 'required|date|before:today',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $artist = Artist::find($request->input('artist'))->name;

        if(!$artist){
            return response()->json(['msg'=>'This artist doesn\'t exist']);
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


        $artistPath = '/songs/' . $artistFolder;

        $folderPath = public_path() . $artistPath;
        if(!File::exists($folderPath)){
            File::makeDirectory($folderPath);
        }

        $albumnameFormatted = preg_replace('/\s+/', '-', strtolower(strtr($request->input('name'), $normalizeChars)));

        $albumPath = public_path() . $artistPath . '/' . $albumnameFormatted;

        if(!File::exists($albumPath)){
            File::makeDirectory($albumPath);
        
            $imageName = 'artwork' . '.' . $request->image->extension();
            $request->image->move($albumPath, $imageName);

            if(!Album::where('name', '=', $request->input('name'))->first()){
                $newAlbum = new Album;
    
                $newAlbum->name = $request->input('name');
                $newAlbum->artist = $request->input('artist');
                $newAlbum->published = $request->input('date');
                $newAlbum->artwork_path = $artistPath . '/' . $albumnameFormatted . '/' . $imageName;
                $newAlbum->genre = 'rap';
                $newAlbum->save();
                return response()->json(['msg'=>'Album created successfully']);
            } else{
                return response()->json(['msg'=>'Album already exists in the database']);    
            }
        } else{
            return response()->json(['msg'=>'Album already exists']);
        }        
    }
}
