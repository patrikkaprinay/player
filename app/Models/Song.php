<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    public function queuesong(){
        return $this->belongsToMany(QueueSong::class);
    }

    public function artist(){
        return $this->belongsToMany(Artist::class, 'artist', 'id');
    }

    public function album(){
        return $this->belongsTo(Album::class, 'album');
    }

}
