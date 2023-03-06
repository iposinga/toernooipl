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
        $game = Game::find($id);
        if($request->input('inputHomeScore') != null && $request->input('inputAwayScore') != null)
        {
            $game->home_score = $request->input('inputHomeScore');
            $game->away_score = $request->input('inputAwayScore');
            if ($request->input('inputHomeScore') > $request->input('inputAwayScore'))
            {
                $game->home_points = 3;
                $game->away_points = 0;
                $game->home_win = 1;
                $game->home_draw = 0;
                $game->home_loss = 0;
                $game->away_win = 0;
                $game->away_draw = 0;
                $game->away_loss = 1;
            } elseif ($request->input('inputHomeScore') < $request->input('inputAwayScore'))
            {
                $game->home_points = 0;
                $game->away_points = 3;
                $game->home_win = 0;
                $game->home_draw = 0;
                $game->home_loss = 1;
                $game->away_win = 1;
                $game->away_draw = 0;
                $game->away_loss = 0;
            } else
            {
                $game->home_points = 1;
                $game->away_points = 1;
                $game->home_win = 0;
                $game->home_draw = 1;
                $game->home_loss = 0;
                $game->away_win = 0;
                $game->away_draw = 1;
                $game->away_loss = 0;
            }
        }
        else
        {
            $game->home_score = null;
            $game->away_score = null;
            $game->home_points = null;
            $game->away_points = null;
            $game->home_win = null;
            $game->home_draw = null;
            $game->home_loss = null;
            $game->away_win = null;
            $game->away_draw = null;
            $game->away_loss = null;
        }
        $game->save();
        //return redirect()->back();
    }
}
