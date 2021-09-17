<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    public function allSongs()
    {
        return TagEntries::where('tagId', $this->id)->get();
    }
}
