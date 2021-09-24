<?php

namespace App\Http\Controllers;

use App\Models\SongRequest;
use Illuminate\Http\Request;

class SongRequestController extends Controller
{
    public function index()
    {
        return SongRequest::all();
    }
}
