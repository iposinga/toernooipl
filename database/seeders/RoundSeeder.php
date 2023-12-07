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
            ),
            array(
                'id' => 31,
                'tournement_id' => 4,
                'round_nr' => 1,
                'start' => '2017-03-15 08:20:00',
                'end' => '2017-03-15 08:30:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 32,
                'tournement_id' => 4,
                'round_nr' => 2,
                'start' => '2017-03-15 08:30:00',
                'end' => '2017-03-15 08:40:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 33,
                'tournement_id' => 4,
                'round_nr' => 3,
                'start' => '2017-03-15 08:40:00',
                'end' => '2017-03-15 08:50:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 34,
                'tournement_id' => 4,
                'round_nr' => 4,
                'start' => '2017-03-15 08:50:00',
                'end' => '2017-03-15 09:00:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 35,
                'tournement_id' => 4,
                'round_nr' => 5,
                'start' => '2017-03-15 09:00:00',
                'end' => '2017-03-15 09:10:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 36,
                'tournement_id' => 4,
                'round_nr' => 6,
                'start' => '2017-03-15 09:10:00',
                'end' => '2017-03-15 09:20:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 37,
                'tournement_id' => 4,
                'round_nr' => 7,
                'start' => '2017-03-15 09:20:00',
                'end' => '2017-03-15 09:30:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 38,
                'tournement_id' => 4,
                'round_nr' => 8,
                'start' => '2017-03-15 09:30:00',
                'end' => '2017-03-15 09:40:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 39,
                'tournement_id' => 4,
                'round_nr' => 9,
                'start' => '2017-03-15 09:40:00',
                'end' => '2017-03-15 09:50:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 40,
                'tournement_id' => 4,
                'round_nr' => 10,
                'start' => '2017-03-15 09:50:00',
                'end' => '2017-03-15 10:00:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 41,
                'tournement_id' => 4,
                'round_nr' => 11,
                'start' => '2017-03-15 10:00:00',
                'end' => '2017-03-15 10:10:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 42,
                'tournement_id' => 4,
                'round_nr' => 12,
                'start' => '2017-03-15 10:10:00',
                'end' => '2017-03-15 10:20:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 43,
                'tournement_id' => 4,
                'round_nr' => 13,
                'start' => '2017-03-15 10:20:00',
                'end' => '2017-03-15 10:30:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 44,
                'tournement_id' => 4,
                'round_nr' => 14,
                'start' => '2017-03-15 10:30:00',
                'end' => '2017-03-15 10:40:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 45,
                'tournement_id' => 4,
                'round_nr' => 15,
                'start' => '2017-03-15 10:40:00',
                'end' => '2017-03-15 10:50:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 46,
                'tournement_id' => 4,
                'round_nr' => 16,
                'start' => '2017-03-15 10:50:00',
                'end' => '2017-03-15 11:00:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 47,
                'tournement_id' => 4,
                'round_nr' => 17,
                'start' => '2017-03-15 11:00:00',
                'end' => '2017-03-15 11:10:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 48,
                'tournement_id' => 4,
                'round_nr' => 18,
                'start' => '2017-03-15 11:10:00',
                'end' => '2017-03-15 11:20:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 49,
                'tournement_id' => 4,
                'round_nr' => 19,
                'start' => '2017-03-15 11:20:00',
                'end' => '2017-03-15 11:30:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 50,
                'tournement_id' => 4,
                'round_nr' => 20,
                'start' => '2017-03-15 11:30:00',
                'end' => '2017-03-15 11:40:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 51,
                'tournement_id' => 4,
                'round_nr' => 21,
                'start' => '2017-03-15 11:40:00',
                'end' => '2017-03-15 11:50:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 52,
                'tournement_id' => 4,
                'round_nr' => 22,
                'start' => '2017-03-15 11:50:00',
                'end' => '2017-03-15 12:00:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 53,
                'tournement_id' => 4,
                'round_nr' => 23,
                'start' => '2017-03-15 12:00:00',
                'end' => '2017-03-15 12:10:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 54,
                'tournement_id' => 4,
                'round_nr' => 24,
                'start' => '2017-03-15 12:10:00',
                'end' => '2017-03-15 12:20:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 55,
                'tournement_id' => 4,
                'round_nr' => 25,
                'start' => '2017-03-15 12:20:00',
                'end' => '2017-03-15 12:30:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 56,
                'tournement_id' => 4,
                'round_nr' => 26,
                'start' => '2017-03-15 12:30:00',
                'end' => '2017-03-15 12:40:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 57,
                'tournement_id' => 4,
                'round_nr' => 27,
                'start' => '2017-03-15 12:40:00',
                'end' => '2017-03-15 12:50:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'id' => 58,
                'tournement_id' => 4,
                'round_nr' => 28,
                'start' => '2017-03-15 12:50:00',
                'end' => '2017-03-15 13:00:00',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            )
        ));
    }
}
