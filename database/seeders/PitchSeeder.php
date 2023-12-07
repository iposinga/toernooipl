<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PitchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('pitches')->insert(array(
            array(
                'tournement_id' => 1,
                'stadium_id' => 1,
                'pitch_nr' => 1,
                'pitch_name' => 'veld 1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 17,
                'tournement_id' => 4,
                'stadium_id' => 2,
                'pitch_nr' => 1,
                'pitch_name' => 'veld 1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 18,
                'tournement_id' => 4,
                'stadium_id' => 2,
                'pitch_nr' => 1,
                'pitch_name' => 'veld 2',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 19,
                'tournement_id' => 4,
                'stadium_id' => 3,
                'pitch_nr' => 3,
                'pitch_name' => 'veld 3',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 20,
                'tournement_id' => 4,
                'stadium_id' => 3,
                'pitch_nr' => 4,
                'pitch_name' => 'veld 4',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 21,
                'tournement_id' => 4,
                'stadium_id' => 4,
                'pitch_nr' => 5,
                'pitch_name' => 'veld 5',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            )
        ));
    }
}
