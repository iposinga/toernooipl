<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Poule;
use App\Models\Tournement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class GameController extends Controller
{
    //
    public function index(int $id): View
    {

    }

    public function update(Request $request, $id)
    {
        if($request->inputHomeScore != null && $request->inputAwayScore != null)
        {
            if ($request->inputHomeScore > $request->inputAwayScore) {
                Game::find($id)->update([
                    'home_score' => $request->inputHomeScore,
                    'away_score' => $request->inputAwayScore,
                    'home_points' => 3,
                    'away_points' => 0,
                    'home_win' => 1,
                    'home_draw' => 0,
                    'home_loss' => 0,
                    'away_win' => 0,
                    'away_draw' => 0,
                    'away_loss' => 1
                ]);
            } elseif ($request->inputHomeScore < $request->inputAwayScore) {
                Game::find($id)->update([
                    'home_score' => $request->inputHomeScore,
                    'away_score' => $request->inputAwayScore,
                    'home_points' => 0,
                    'away_points' => 3,
                    'home_win' => 0,
                    'home_draw' => 0,
                    'home_loss' => 1,
                    'away_win' => 1,
                    'away_draw' => 0,
                    'away_loss' => 0
                ]);
            } else {
                Game::find($id)->update([
                    'home_score' => $request->inputHomeScore,
                    'away_score' => $request->inputAwayScore,
                    'home_points' => 1,
                    'away_points' => 1,
                    'home_win' => 0,
                    'home_draw' => 1,
                    'home_loss' => 0,
                    'away_win' => 0,
                    'away_draw' => 1,
                    'away_loss' => 0
                ]);
            }
        }
        else
        {
            Game::find($id)->update([
                'home_score' => null,
                'away_score' => null,
                'home_points' => null,
                'away_points' => null,
                'home_win' => null,
                'home_draw' => null,
                'home_loss' => null,
                'away_win' => null,
                'away_draw' => null,
                'away_loss' => null
                ]);
        }
    }
}
