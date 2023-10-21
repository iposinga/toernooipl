<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Poule;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    //
    public function edit(Request $request)
    {
        $poule_id = $request->input('id');
        $teams = Team::where('poule_id', $poule_id)->get();
        $html = view('poule.edit')->with(compact(['teams', 'poule_id']))->render();
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
        $poule = Poule::find($id);
        //return redirect()->back();
        return redirect()->route('tournement.show', ['id' => $poule->tournement_id, 'poule_id' => $id]);
    }
    //nodig voor het koppelen van teams aan finalerondes op basis van poule en ranking
    public function stand(Request $request)
    {
        $teams = Team::stand($request->input('poule_id'));
        return response()->json(['success' => true, 'ranking' => $teams]);
    }
    //voor de stand van de teams in een poule
    public function show(Request $request)
    {
        $teams = Team::stand($request->input('poule_id'));
        $html = view('poule.stand')->with(compact('teams'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }

    public function showinjs(Request $request)
    {
        $tournementid = $request->input('id');
        $teams = Team::with('poule')
            ->whereHas('poule', function($q) use ($tournementid){$q->where('poules.tournement_id', $tournementid);})
            ->orderBy('points', 'desc')
            ->orderBy('played')
            ->orderBy('goaldifference', 'desc')
            ->get();
/*            ->sortByDesc('goaldifference')
            ->sortBy('played')
            ->sortByDesc('points')*/
        return response()->json(['success' => true, 'teams' => $teams]);
    }
}
