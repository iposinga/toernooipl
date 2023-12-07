<?php

namespace App\Http\Controllers;

use App\Models\Finalgame;
use App\Models\Poule;
use App\Models\Round;
use App\Models\Team;
use Illuminate\Http\Request;

class FinalgameController extends Controller
{
    //
    public function edit(Request $request)
    {
        $finalgame = Finalgame::with('round', 'homeparent', 'awayparent')->find($request->input('id'));
        $poules = Poule::where('tournement_id', $finalgame->tournement_id)
            ->orderBy('poule_name')
            ->get();
        $tournementid = $finalgame->tournement_id;
        $finalround_minimal_nr = Round::where([['tournement_id', $tournementid], ['finalround', '>', -1]])->selectRaw('min(round_nr) as minround_nr')->get();
        $finalgames_roundearlier = array();
        if($finalgame->round->round_nr > $finalround_minimal_nr[0]->minround_nr)
        {
            $search_round_nr = $finalgame->round->round_nr - 1;
            $finalgames_roundearlier = Finalgame::with('round')
                ->whereHas('round', function($q) use ($search_round_nr) {
                    $q->where('rounds.round_nr', $search_round_nr);
                })
                ->orderBy('id')
                ->get();
        }
        $teams = Team::with('poule')
            ->whereHas('poule', function($q) use ($tournementid){$q->where('poules.tournement_id', $tournementid);})
            ->get()
            ->sortByDesc('goaldifference')
            ->sortBy('played')
            ->sortByDesc('points');
            //->sortBy(function($query){return $query->poule->poule_name;});
        $html = view('finalgame.edit')->with(compact(['finalgame','poules','finalround_minimal_nr','finalgames_roundearlier','teams']))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }

    public function update(Request $request)
    {
        if($request->input('homepoule_id') <> 0) {
            $homepoule_id_array = explode("_", $request->input('homepoule_id'));
            $homepoule_id = $homepoule_id_array[1];
        }
        else
            $homepoule_id = $request->input('homepoule_id');

        if($request->input('awaypoule_id') <> 0) {
            $awaypoule_id_array = explode("_", $request->input('awaypoule_id'));
            $awaypoule_id = $awaypoule_id_array[1];
        }
        else
            $awaypoule_id = $request->input('awaypoule_id');
        $request->merge([
            'homepoule_id' =>  $homepoule_id ,
            'awaypoule_id' =>  $awaypoule_id
        ]);
        Finalgame::where('id', $request->input('id'))->update($request->except(['_token']));
        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        Finalgame::find($request->finalgame_id)->delete();
        return redirect()->back()
            ->withSuccess('De finalewedstrijd is succesvol verwijderd.');
    }

}
