<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\TagEntries;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        return Tag::all();
    }

    public function add(Request $request)
    {
        $newTag = new Tag();
        $newTag->name = $request->name;
        $newTag->color = $request->color;

        $newTag->save();
        
        return $newTag;
    }

    public function delete(Request $request)
    {
        $tag = Tag::find($request->tagId);
        $tag->delete();

        $entries = TagEntries::where('tagId', $request->tagId)->get();

        if(count($entries) > 0){
            $entries->delete();
        }

        return 'Successfully deleted';
    }

    public function ban(Request $request)
    {
        $tagId = $request->id;

        $tag = Tag::find($tagId);
        $tag->enabled = $tag->enabled == 1 ? 0 : 1;

        $tag->save();

        return 'azigen';
    }
}
