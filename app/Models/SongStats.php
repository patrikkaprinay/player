<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongStats extends Model
{
    use HasFactory;

    public function song()
    {
        $this->belongsTo(Song::class, 'id', 'songId');
    }
}
