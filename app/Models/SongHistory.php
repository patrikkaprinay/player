<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Song;

class SongHistory extends Model
{
    use HasFactory;

    public function song(){
        return $this->belongsTo(Song::class, 'songId');
    }

    public function artist(){
        return Song::find($this->songId)->artist;
    }
}
