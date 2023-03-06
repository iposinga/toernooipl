<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RoundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rounds')->insert(array(
            array(
                'tournement_id' => 1,
                'round_nr' => 1,
                'start' => '2022-12-29 14:00:00',
                'end' => '2022-12-29 14:12:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'tournement_id' => 1,
                'round_nr' => 2,
                'start' => '2022-12-29 14:12:00',
                'end' => '2022-12-29 14:24:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'tournement_id' => 1,
                'round_nr' => 3,
                'start' => '2022-12-29 14:24:00',
                'end' => '2022-12-29 14:36:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'tournement_id' => 1,
                'round_nr' => 4,
                'start' => '2022-12-29 14:36:00',
                'end' => '2022-12-29 14:48:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'tournement_id' => 1,
                'round_nr' => 5,
                'start' => '2022-12-29 14:48:00',
                'end' => '2022-12-29 15:00:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'tournement_id' => 1,
                'round_nr' => 6,
                'start' => '2022-12-29 15:00:00',
                'end' => '2022-12-29 15:12:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'tournement_id' => 1,
                'round_nr' => 7,
                'start' => '2022-12-29 15:12:00',
                'end' => '2022-12-29 15:24:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'tournement_id' => 1,
                'round_nr' => 8,
                'start' => '2022-12-29 15:24:00',
                'end' => '2022-12-29 15:36:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'tournement_id' => 1,
                'round_nr' => 9,
                'start' => '2022-12-29 15:36:00',
                'end' => '2022-12-29 15:48:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'tournement_id' => 1,
                'round_nr' => 10,
                'start' => '2022-12-29 15:48:00',
                'end' => '2022-12-29 16:00:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'tournement_id' => 1,
                'round_nr' => 11,
                'start' => '2022-12-29 16:00:00',
                'end' => '2022-12-29 16:12:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'tournement_id' => 1,
                'round_nr' => 12,
                'start' => '2022-12-29 16:12:00',
                'end' => '2022-12-29 16:24:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'tournement_id' => 1,
                'round_nr' => 13,
                'start' => '2022-12-29 16:24:00',
                'end' => '2022-12-29 16:36:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'tournement_id' => 1,
                'round_nr' => 14,
                'start' => '2022-12-29 16:36:00',
                'end' => '2022-12-29 16:48:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'tournement_id' => 1,
                'round_nr' => 15,
                'start' => '2022-12-29 16:48:00',
                'end' => '2022-12-29 17:00:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            )
        ));
    }
}
