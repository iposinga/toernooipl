<?php

namespace App\Http\Controllers;


use App\Models\Game;
use App\Models\Poule;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PouleController extends Controller
{
    //
    public function show_stand(int $pouleid)
    {
        $teams = Team::with('homegames', 'awaygames')
            ->where('poule_id', $pouleid)
            ->orderBy('points', 'desc')
            ->orderBy('played')
            ->orderBy('goaldifference', 'desc')
            ->get();
        $html = view('poule.stand')->with(compact('teams'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }

    public function show(int $pouleid)
    {
        $wedstrijden = Game::with('hometeam.poule', 'round.tournement', 'hometeam', 'awayteam', 'pitch')
            ->whereHas('hometeam', function($q) use ($pouleid) {
                $q->where('teams.poule_id', $pouleid);
            })
            ->get();
        $teams = Team::where('poule_id', $pouleid)
                ->orderBy('points', 'desc')
                ->orderBy('played')
                ->orderBy('goaldifference', 'desc')
                ->get();
        return view('poule.index', compact(['wedstrijden', 'teams']));
    }
}
