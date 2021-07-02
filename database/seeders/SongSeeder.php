<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('songs')->insert(
        [
            'name' => 'Nice',
            'artist' => 1,
            'album' => 1,
            'path' => '/songs/kise/Nice.mp3'
        ]
        );
    }
}
