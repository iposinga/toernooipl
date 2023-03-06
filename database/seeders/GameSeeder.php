<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('games')->insert(array(
            array(
                'round_id' => 1,
                'pitch_id' => 1,
                'hometeam_id' => 6,
                'awayteam_id' => 1,

                'home_score' => 1,
                'away_score' => 0,

                'home_points' => 3,
                'home_win' => 1,
                'home_draw' => 0,
                'home_loss' => 0,

                'away_points' => 0,
                'away_win' => 0,
                'away_draw' => 0,
                'away_loss' => 1,

                'poule_round' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'round_id' => 2,
                'pitch_id' => 1,
                'hometeam_id' => 2,
                'awayteam_id' => 5,

                'home_score' => 2,
                'away_score' => 0,

                'home_points' => 3,
                'home_win' => 1,
                'home_draw' => 0,
                'home_loss' => 0,

                'away_points' => 0,
                'away_win' => 0,
                'away_draw' => 0,
                'away_loss' => 1,

                'poule_round' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'round_id' => 3,
                'pitch_id' => 1,
                'hometeam_id' => 3,
                'awayteam_id' => 4,

                'home_score' => 3,
                'away_score' => 0,

                'home_points' => 3,
                'home_win' => 1,
                'home_draw' => 0,
                'home_loss' => 0,

                'away_points' => 0,
                'away_win' => 0,
                'away_draw' => 0,
                'away_loss' => 1,

                'poule_round' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'round_id' => 4,
                'pitch_id' => 1,
                'hometeam_id' => 1,
                'awayteam_id' => 5,

                'home_score' => 2,
                'away_score' => 4,

                'home_points' => 0,
                'home_win' => 0,
                'home_draw' => 0,
                'home_loss' => 1,

                'away_points' => 3,
                'away_win' => 1,
                'away_draw' => 0,
                'away_loss' => 0,

                'poule_round' => 2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'round_id' => 5,
                'pitch_id' => 1,
                'hometeam_id' => 3,
                'awayteam_id' => 6,

                'home_score' => 1,
                'away_score' => 3,

                'home_points' => 0,
                'home_win' => 0,
                'home_draw' => 0,
                'home_loss' => 1,

                'away_points' => 3,
                'away_win' => 1,
                'away_draw' => 0,
                'away_loss' => 0,

                'poule_round' => 2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'round_id' => 6,
                'pitch_id' => 1,
                'hometeam_id' => 4,
                'awayteam_id' => 2,

                'home_score' => 0,
                'away_score' => 0,

                'home_points' => 1,
                'home_win' => 0,
                'home_draw' => 1,
                'home_loss' => 0,

                'away_points' => 1,
                'away_win' => 0,
                'away_draw' => 1,
                'away_loss' => 0,

                'poule_round' => 2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'round_id' => 7,
                'pitch_id' => 1,
                'hometeam_id' => 1,
                'awayteam_id' => 3,

                'home_score' => 0,
                'away_score' => 2,

                'home_points' => 0,
                'home_win' => 0,
                'home_draw' => 0,
                'home_loss' => 1,

                'away_points' => 3,
                'away_win' => 1,
                'away_draw' => 0,
                'away_loss' => 0,

                'poule_round' => 3,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'round_id' => 8,
                'pitch_id' => 1,
                'hometeam_id' => 4,
                'awayteam_id' => 5,

                'home_score' => 0,
                'away_score' => 1,

                'home_points' => 0,
                'home_win' => 0,
                'home_draw' => 0,
                'home_loss' => 1,

                'away_points' => 3,
                'away_win' => 1,
                'away_draw' => 0,
                'away_loss' => 0,

                'poule_round' => 3,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'round_id' => 9,
                'pitch_id' => 1,
                'hometeam_id' => 6,
                'awayteam_id' => 2,

                'home_score' => 1,
                'away_score' => 1,

                'home_points' => 1,
                'home_win' => 0,
                'home_draw' => 1,
                'home_loss' => 0,

                'away_points' => 1,
                'away_win' => 0,
                'away_draw' => 1,
                'away_loss' => 0,

                'poule_round' => 3,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'round_id' => 10,
                'pitch_id' => 1,
                'hometeam_id' => 4,
                'awayteam_id' => 1,

                'home_score' => 0,
                'away_score' => 4,

                'home_points' => 0,
                'home_win' => 0,
                'home_draw' => 0,
                'home_loss' => 1,

                'away_points' => 3,
                'away_win' => 1,
                'away_draw' => 0,
                'away_loss' => 0,

                'poule_round' => 4,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'round_id' => 11,
                'pitch_id' => 1,
                'hometeam_id' => 2,
                'awayteam_id' => 3,

                'home_score' => 0,
                'away_score' => 2,

                'home_points' => 0,
                'home_win' => 0,
                'home_draw' => 0,
                'home_loss' => 1,

                'away_points' => 3,
                'away_win' => 1,
                'away_draw' => 0,
                'away_loss' => 0,

                'poule_round' => 4,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'round_id' => 12,
                'pitch_id' => 1,
                'hometeam_id' => 5,
                'awayteam_id' => 6,

                'home_score' => 1,
                'away_score' => 5,

                'home_points' => 0,
                'home_win' => 0,
                'home_draw' => 0,
                'home_loss' => 1,

                'away_points' => 3,
                'away_win' => 1,
                'away_draw' => 0,
                'away_loss' => 0,

                'poule_round' => 4,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'round_id' => 13,
                'pitch_id' => 1,
                'hometeam_id' => 1,
                'awayteam_id' => 2,

                'home_score' => 0,
                'away_score' => 1,

                'home_points' => 0,
                'home_win' => 0,
                'home_draw' => 0,
                'home_loss' => 1,

                'away_points' => 3,
                'away_win' => 1,
                'away_draw' => 0,
                'away_loss' => 0,

                'poule_round' => 5,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'round_id' => 14,
                'pitch_id' => 1,
                'hometeam_id' => 6,
                'awayteam_id' => 4,

                'home_score' => 5,
                'away_score' => 0,

                'home_points' => 3,
                'home_win' => 1,
                'home_draw' => 0,
                'home_loss' => 0,

                'away_points' => 0,
                'away_win' => 0,
                'away_draw' => 0,
                'away_loss' => 1,

                'poule_round' => 5,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ),
            array(
                'round_id' => 15,
                'pitch_id' => 1,
                'hometeam_id' => 5,
                'awayteam_id' => 3,

                'home_score' => 1,
                'away_score' => 3,

                'home_points' => 0,
                'home_win' => 0,
                'home_draw' => 0,
                'home_loss' => 1,

                'away_points' => 3,
                'away_win' => 1,
                'away_draw' => 0,
                'away_loss' => 0,

                'poule_round' => 5,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            )
        ));
    }
}
