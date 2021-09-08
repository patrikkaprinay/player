<?php

namespace App\Http\Controllers;

use App\Models\SongHistory;
use Illuminate\Http\Request;

class SongHistoryController extends Controller
{
    public function index()
    {
        $songHistory = SongHistory::all();
        return $songHistory;
    }

    public function last30mins(Request $request)
    {
        ###
    }

    public function add(Request $request)
    {
        $songHistory = new SongHistory();
        $songHistory->songId = $request->id;
        $songHistory->save();

        return response()->json(['song_history'=>$songHistory]);
    }
}
