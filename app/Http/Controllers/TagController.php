<?php

namespace App\Http\Controllers;

use App\Models\Tag;
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
}
