<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    public function song(){
        return $this->hasMany(Song::class);
    }

    public function artist(){
        return $this->belongsTo(Artist::class, 'artist');
    }

}
