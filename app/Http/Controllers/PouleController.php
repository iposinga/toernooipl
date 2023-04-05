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
    public function edit(int $id)
    {
        $poule = Poule::with('teams')->where('id', $id)->get();
        $html = view('poule.edit')->with(compact('poule'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }

    public function update(Request $request, $id)
    {
        $teams = Team::where('poule_id', $id)->get();
        foreach ($teams as $team) {
            $team->update([
                'team_name'=>$request->input('inputTeamname_'.$team->id)
            ]);
        }
        return redirect()->back();
    }

    public function show_stand(int $pouleid)
    {
        $teams = Team::with('homegames', 'awaygames')
            ->where('poule_id', $pouleid)
            ->withSum('homegames','home_points')
            ->withSum('homegames','home_score')
            ->withSum('homegames','away_score')
            ->withSum('homegames','home_win')
            ->withSum('homegames','home_draw')
            ->withSum('homegames','home_loss')
            ->withSum('awaygames','away_points')
            ->withSum('awaygames','away_score')
            ->withSum('awaygames','home_score')
            ->withSum('awaygames','away_win')
            ->withSum('awaygames','away_draw')
            ->withSum('awaygames','away_loss')
            ->get();
        foreach($teams as $team)
        {
            $team->punten = $team->homegames_sum_home_points + $team->awaygames_sum_away_points;
            $team->gespeeld = $team->homegames_sum_home_win + $team->awaygames_sum_away_win + $team->homegames_sum_home_draw + $team->awaygames_sum_away_draw + $team->homegames_sum_home_loss + $team->awaygames_sum_away_loss;
            $team->gewonnen = $team->homegames_sum_home_win + $team->awaygames_sum_away_win;
            $team->gelijk = $team->homegames_sum_home_draw + $team->awaygames_sum_away_draw;
            $team->verloren = $team->homegames_sum_home_loss + $team->awaygames_sum_away_loss;
            $team->doelpvoor = $team->homegames_sum_home_score + $team->awaygames_sum_away_score;
            $team->doelptegen = $team->homegames_sum_away_score + $team->awaygames_sum_home_score;
            $team->doelsdaldo = $team->doelpvoor - $team->doelptegen;
        }
        $sortedteams = $teams->sortBy([
            ['punten', 'desc'],
            ['gespeeld', 'asc'],
            ['doelsaldo', 'desc']
        ]);
        $html = view('poule.stand')->with(compact('sortedteams'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }

    public function returnstand(int $pouleid)
    {
        $teams = Team::with('homegames', 'awaygames')
            ->where('poule_id', $pouleid)
            ->withSum('homegames','home_points')
            ->withSum('homegames','home_score')
            ->withSum('homegames','away_score')
            ->withSum('homegames','home_win')
            ->withSum('homegames','home_draw')
            ->withSum('homegames','home_loss')
            ->withSum('awaygames','away_points')
            ->withSum('awaygames','away_score')
            ->withSum('awaygames','home_score')
            ->withSum('awaygames','away_win')
            ->withSum('awaygames','away_draw')
            ->withSum('awaygames','away_loss')
            ->get();
        foreach($teams as $team)
        {
            $team->punten = $team->homegames_sum_home_points + $team->awaygames_sum_away_points;
            $team->gespeeld = $team->homegames_sum_home_win + $team->awaygames_sum_away_win + $team->homegames_sum_home_draw + $team->awaygames_sum_away_draw + $team->homegames_sum_home_loss + $team->awaygames_sum_away_loss;
            $team->gewonnen = $team->homegames_sum_home_win + $team->awaygames_sum_away_win;
            $team->gelijk = $team->homegames_sum_home_draw + $team->awaygames_sum_away_draw;
            $team->verloren = $team->homegames_sum_home_loss + $team->awaygames_sum_away_loss;
            $team->doelpvoor = $team->homegames_sum_home_score + $team->awaygames_sum_away_score;
            $team->doelptegen = $team->homegames_sum_away_score + $team->awaygames_sum_home_score;
            $team->doelsdaldo = $team->doelpvoor - $team->doelptegen;
        }
        $sortedteams = $teams->sortBy([
            ['punten', 'desc'],
            ['gespeeld', 'asc'],
            ['doelsaldo', 'desc']
        ]);
        //$ranking = compact('sortedteams');
        return response()->json(['success' => true, 'ranking' => $sortedteams]);
    }

    public function show(int $pouleid)
    {
        $wedstrijden = Game::with('hometeam.poule', 'round.tournement', 'hometeam', 'awayteam', 'pitch')
            ->whereHas('hometeam', function($q) use ($pouleid) {
                $q->where('teams.poule_id', $pouleid);
            })
            ->get();
        $teams = Team::with('homegames', 'awaygames')
                ->where('poule_id', $pouleid)
                ->withSum('homegames','home_points')
                ->withSum('homegames','home_score')
                ->withSum('homegames','away_score')
                ->withSum('homegames','home_win')
                ->withSum('homegames','home_draw')
                ->withSum('homegames','home_loss')
                ->withSum('awaygames','away_points')
                ->withSum('awaygames','away_score')
                ->withSum('awaygames','home_score')
                ->withSum('awaygames','away_win')
                ->withSum('awaygames','away_draw')
                ->withSum('awaygames','away_loss')
                ->get();
        foreach($teams as $team)
        {
            $team->punten = $team->homegames_sum_home_points + $team->awaygames_sum_away_points;
            $team->gespeeld = $team->homegames_sum_home_win + $team->awaygames_sum_away_win + $team->homegames_sum_home_draw + $team->awaygames_sum_away_draw + $team->homegames_sum_home_loss + $team->awaygames_sum_away_loss;
            $team->gewonnen = $team->homegames_sum_home_win + $team->awaygames_sum_away_win;
            $team->gelijk = $team->homegames_sum_home_draw + $team->awaygames_sum_away_draw;
            $team->verloren = $team->homegames_sum_home_loss + $team->awaygames_sum_away_loss;
            $team->doelpvoor = $team->homegames_sum_home_score + $team->awaygames_sum_away_score;
            $team->doelptegen = $team->homegames_sum_away_score + $team->awaygames_sum_home_score;
            $team->doelsdaldo = $team->doelpvoor - $team->doelptegen;
        }
        $sortedteams = $teams->sortBy([
            ['punten', 'desc'],
            ['gespeeld', 'asc'],
            ['doelsaldo', 'desc']
        ]);
        return view('poule.index', compact(['wedstrijden', 'sortedteams']));
    }
}
