<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TournementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tournements')->insert(array(
            array(
                'tournement_name' => 'Lopsterzaaltoernooi JO19',
                'tournement_date' => '2022-12-29 14:00:00',
                'teams_nmbr' => 6,
                'pitches_nmbr' => 1,
                'poules_nmbr' => 1,
                'match_duration' => 12,
                'change_duration' => 0,
                'is_entire_comp' => 0,
                'is_public' => 0,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'tournement_name' => 'Lopsterzaaltoernooi JO15',
                'tournement_date' => '2022-12-29',
                'teams_nmbr' => 6,
                'pitches_nmbr' => 1,
                'poules_nmbr' => 1,
                'start_time' => '11:15:00',
                'match_duration' => 10,
                'is_entire_comp' => 0,
                'is_public' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            )
        ));
    }
}
