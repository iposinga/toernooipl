<?php

namespace App\Http\Controllers;

use App\Exports\GamesExport;
use App\Models\Finalgame;
use App\Models\Game;
use App\Models\Pitch;
use App\Models\Poule;
use App\Models\Round;
use App\Models\Team;
use App\Models\Tournement;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class TournementController extends Controller
{
    public function show(int $id, int $focuspoule): View
    {
        $tournement = Tournement::with('users')->find($id);
        $pitches = Pitch::where('tournement_id', $id)->get();
        $poules = Poule::with('teams')->where('tournement_id', $id)->get();
        $games = Game::with('round', 'pitch', 'hometeam', 'awayteam')
            ->where('tournement_id', $id)
            ->orderBy('round_id', 'asc')
            ->orderBy('pitch_id', 'asc')
            ->get();
        $finalgames = Finalgame::with('round', 'pitch', 'homepoule', 'awaypoule', 'hometeam', 'awayteam')
            ->where('tournement_id', $id)
            ->orderBy('round_id', 'asc')
            ->orderBy('pitch_id', 'asc')
            ->get();
        $dates = Tournement::getTournementDates($id);
        return view('tournement.index', compact(['tournement', 'pitches', 'poules','games','finalgames','dates','focuspoule']));
    }

    public function store(Request $request): RedirectResponse
    {
        //dd($request->all());
        $request->merge([
            'tournement_date' => Carbon::createFromFormat('d-m-Y H:i', $request->get('tournement_date'))->toDateTimeString()
        ]);
        $tournement = Tournement::create($request->except(['_token']));
        $tournement->users()->attach([$tournement->id => ['user_id' => Auth::user()->id, 'is_admin' => 1]]);
        $newtournement = Tournement::find($tournement->id);
        Tournement::setupTournement($newtournement);
        return redirect()->back();
    }

    public function addusers(Request $request): JsonResponse
    {
        $tournementid = $request->input('tournement_id');
        $users = User::whereHas('tournements', function($q) use ($tournementid) {
            $q->where('tournement_user.tournement_id', '!=', $tournementid)
                ->where('users.id', '!=', Auth::user()->id);
        })
            ->ordoesntHave('tournements')
            ->get();
        $html = view('user.user_add_form')->with(compact(['users', 'tournementid']))->render();
        return response()->json(['success' => true, 'tournement_id' => $tournementid, 'html' => $html]);
    }
    public function tournement_userstore(Request $request, $id): RedirectResponse
    {
        $tournement = Tournement::find($id);
        $tournement->users()->attach($request->inputUsers);
        return redirect()->back();
    }
    public function destroy(Request $request)
    {
        Tournement::find($request->tournement_id)->delete();
        return redirect()->route('home')
            ->withSuccess('Het toernooi is succesvol verwijderd.');
    }
    public function destroytournementuser(Request $request)
    {
        Tournement::find($request->tournement_id)->users()->detach($request->user_id);
        return redirect()->back()
            ->withSuccess('De user is succesvol bij dit toernooi verwijderd.');
    }
    public function programold(int $id): View
    {
        $tournement = Tournement::with('users')->find($id);
        $pitches = Pitch::where('tournement_id', $id)->get();
        $rounds = Round::find($id);
        $poules = Poule::with('teams')->where('tournement_id', $id)->get();
        $games = Game::with('round', 'pitch', 'hometeam', 'awayteam')
            ->where('tournement_id', $id)
            ->orderBy('round_id', 'asc')
            ->orderBy('pitch_id', 'asc')
            ->get();
        $finalgames = Finalgame::with('round', 'pitch', 'homepoule', 'awaypoule', 'hometeam', 'awayteam')
            ->where('tournement_id', $id)
            ->orderBy('round_id', 'asc')
            ->orderBy('pitch_id', 'asc')
            ->get();
        $dates = Tournement::getTournementDates($id);
        return view('print.program', compact(['tournement', 'pitches', 'rounds', 'poules','games','finalgames','dates']));
    }
    public function program(int $id): View
    {
        $tournement = Tournement::find($id);
        $poules = Poule::with('teams')
        ->where('tournement_id', $id)
        ->orderBy('poule_name')
        ->get();
        $pitches = Pitch::where('tournement_id', $id)
            ->orderBy('pitch_nr', 'asc')
            ->get();
        $rounds = Round::with([
            'games',
            'games.pitch',
            'games.hometeam',
            'games.awayteam'
        ])
        ->where('tournement_id', $id)
        ->where('finalround', '=', -1)
        ->get();
        $finalrounds = Round::with([
            'finalgames',
            'finalgames.pitch'
        ])
        ->where('tournement_id', $id)
        ->where('finalround', '>', -1)
        ->get();
        $dates = Tournement::getTournementDates($id);
        return view('print.program', compact(['tournement', 'poules', 'pitches', 'rounds', 'finalrounds', 'dates']));
    }
    public function gamesheets(int $id): View
    {
        $tournement = Tournement::find($id);
        $games = Game::with('round', 'pitch', 'hometeam', 'awayteam','poule')
            ->where('tournement_id', $id)
            ->orderBy('round_id', 'asc')
            ->orderBy('pitch_id', 'asc')
            ->get();
        $finalgames = Finalgame::with('round', 'pitch', 'homepoule', 'awaypoule')
            ->where('tournement_id', $id)
            ->orderBy('round_id', 'asc')
            ->orderBy('pitch_id', 'asc')
            ->get();
        return view('print.gamesheets', compact(['tournement', 'games','finalgames']));
    }
    public function export(int $id)
    {
        return Excel::download(new GamesExport($id), 'programma.xlsx');
    }
}
