<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('albums')->insert(
            [
                'name' => 'Enorellove',
                'tracks' => 16,
                'artist' => 1,
                'published' => Carbon::now(),
                'artwork_path' => '/songs/kise/enorellove/artwork.jpg',
                'genre' => 'rap'
            ]
            );
    }
}
