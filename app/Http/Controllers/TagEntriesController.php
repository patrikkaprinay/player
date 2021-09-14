<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Tag;
use App\Models\TagEntries;
use Illuminate\Http\Request;

class TagEntriesController extends Controller
{
    public static function index($id)
    {
        $tags = Tag::all();
        $tagsArray = Array();


        foreach ($tags as $tag) {
            $tagsArray[$tag->id] = TagEntries::where('tagId', $tag->id)->where('songId', $id)->first() ? true : false;
        }

        return $tagsArray;
    }

    public function add(Request $request)
    {
        if($existingEntry = TagEntries::where('songId', $request->songId)->where('tagId', $request->tagId)->first()){
            $existingEntry->delete();
            return 'Entry has been deleted';
        }

        $newTagEntry = new TagEntries();
        $newTagEntry->songId = $request->songId;
        $newTagEntry->tagId = $request->tagId;

        $newTagEntry->save();

        return $newTagEntry;
    }
}
