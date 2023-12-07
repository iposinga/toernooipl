<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PouleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('poules')->insert(array(
            array(
                'id' => 1,
                'tournement_id' => 1,
                'poule_name' => 'A',
                'teams_nmbr' => 6,
                'games_nmbr' => 15,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 10,
                'tournement_id' => 4,
                'poule_name' => 'A',
                'teams_nmbr' => 6,
                'games_nmbr' => 15,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 11,
                'tournement_id' => 4,
                'poule_name' => 'B',
                'teams_nmbr' => 6,
                'games_nmbr' => 15,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 12,
                'tournement_id' => 4,
                'poule_name' => 'C',
                'teams_nmbr' => 6,
                'games_nmbr' => 15,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 13,
                'tournement_id' => 4,
                'poule_name' => 'D',
                'teams_nmbr' => 6,
                'games_nmbr' => 15,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 14,
                'tournement_id' => 4,
                'poule_name' => 'E',
                'teams_nmbr' => 5,
                'games_nmbr' => 10,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 15,
                'tournement_id' => 4,
                'poule_name' => 'F',
                'teams_nmbr' => 5,
                'games_nmbr' => 10,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 16,
                'tournement_id' => 4,
                'poule_name' => 'G',
                'teams_nmbr' => 5,
                'games_nmbr' => 10,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 17,
                'tournement_id' => 4,
                'poule_name' => 'H',
                'teams_nmbr' => 5,
                'games_nmbr' => 10,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 18,
                'tournement_id' => 4,
                'poule_name' => 'I',
                'teams_nmbr' => 5,
                'games_nmbr' => 10,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 19,
                'tournement_id' => 4,
                'poule_name' => 'J',
                'teams_nmbr' => 5,
                'games_nmbr' => 10,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 20,
                'tournement_id' => 4,
                'poule_name' => 'K',
                'teams_nmbr' => 5,
                'games_nmbr' => 10,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 21,
                'tournement_id' => 4,
                'poule_name' => 'L',
                'teams_nmbr' => 5,
                'games_nmbr' => 10,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            )
        ));
    }
}
