<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueueSong extends Model
{
    use HasFactory;

    public function song(){
        return $this->hasOne(Song::class, 'id', 'songNumber');
    }

    public function addedBy()
    {
        return $this->hasOne(User::class, 'id', 'addedBy');
    }

}
