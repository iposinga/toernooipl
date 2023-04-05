<?php

namespace App\Http\Controllers;

use App\Models\Finalgame;
use App\Models\Game;
use App\Models\Pitch;
use App\Models\Poule;
use App\Models\Round;
use App\Models\Team;
use App\Models\Tournement;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TournementController extends Controller
{
    const WEDSTRIJDSCHEMA5 = [[0, 1, 1], [2, 3, 1], [4, 0, 2], [1, 2, 2], [3, 4, 3], [2, 0, 3], [1, 4, 4], [0, 3, 4], [4, 2, 5], [3, 1, 5]];
    const WEDSTRIJDSCHEMA6 = [[5, 0, 1], [1, 4, 1], [2, 3, 1], [0, 4, 2], [2, 5, 2], [3, 1, 2], [0, 2, 3], [3, 4, 3], [5, 1, 3],
                [3, 0, 4], [1, 2, 4], [4, 5, 4], [0, 1, 5], [5, 3, 5], [4, 2, 5]];
    const WEDSTRIJDSCHEMA7 = [[0, 1, 1], [3, 6, 1], [5, 4, 1], [6, 2, 2], [1, 5, 2], [4, 3, 2], [5, 0, 3], [2, 4, 3], [3, 1, 3],
                [4, 6, 4], [0, 3, 4], [1, 2, 4], [3, 5, 5], [2, 0, 5], [6, 1, 5], [5, 2, 6], [0, 6, 6], [1, 4, 6], [2, 3, 7], [6, 5, 7], [4, 0, 7]];
    const WEDSTRIJDSCHEMA8 = [[0, 1, 1], [2, 7, 1], [3, 6, 1], [5, 4, 1], [7, 0, 2], [6, 2, 2], [1, 5, 2], [4, 3, 2], [6, 7, 3], [5, 0, 3], [2, 4, 3], [3, 1, 3],
                [7, 5, 4], [4, 6, 4], [0, 3, 4], [1, 2, 4], [4, 7, 5], [3, 5, 5], [2, 0, 5], [6, 1, 5], [3, 7, 6], [5, 2, 6], [0, 6, 6], [1, 4, 6],
                [2, 3, 7], [6, 5, 7], [7, 1, 7], [4, 0, 7]];

    const POULENAMES = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];

    /*public function show(int $id): View
    {
        $pitches = Pitch::where('tournement_id', $id)->get();
        $poules = Poule::with('teams')->where('tournement_id', $id)->get();
        $wedstrijden = Game::with('round.tournement', 'hometeam', 'awayteam', 'pitch', 'round.tournement.users')
            ->whereHas('round', function($q) use ($id) {
                $q->where('rounds.tournement_id', $id);
            })
            ->get();
        return view('tournement.index', compact(['pitches', 'poules','wedstrijden']));
    }*/
    public function show(int $id): View
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
        $dates = $this->getTournementDates($id);
        return view('tournement.index', compact(['tournement', 'pitches', 'poules','games','finalgames','dates']));
    }

    public function store(Request $request): RedirectResponse
    {
        $tournement = new Tournement();
        $tournement->tournement_name = $request->input('inputToernooinaam');
        $datestart = Carbon::createFromFormat('d-m-Y H:i', $request->input('inputDatum'))->toDateTimeString();
        $tournement->tournement_date = $datestart;
        $tournement->teams_nmbr = $request->input('inputTeams');
        $tournement->poules_nmbr = $request->input('inputPoules');
        $tournement->pitches_nmbr = $request->input('inputVelden');
        $tournement->game_duration = $request->input('inputDuur');
        $tournement->change_duration = $request->input('inputPauze');
        if($request->input('inputCompetitieType') !== null)
            $tournement->is_entire_comp = $request->input('inputCompetitieType');
        $tournement->save();
        $tournement->users()->attach([$tournement->id => ['user_id' => Auth::user()->id, 'is_admin' => 1]]);
        $this->setupTournement($tournement);
        return redirect()->route('home');
    }

    public function addusers(int $tournementid)
    {
        /*$users = User::where('id', '!=', Auth::user()->id)->get();*/
        $users = User::whereHas('tournements', function($q) use ($tournementid) {
            $q->where('tournement_user.tournement_id', '!=', $tournementid)
                ->where('users.id', '!=', Auth::user()->id);
        })
            ->ordoesntHave('tournements')
            ->get();
        /*$users = User::whereHas('tournements', function($q) {
            $q->where('tournement_user.user_id', );
        })->get();*/

        /*$users = User::doesntHave('tournements')->get();*/
        $html = view('user.user_add_form')->with(compact(['users', 'tournementid']))->render();
        return response()->json(['success' => true, 'tournement_id' => $tournementid, 'html' => $html]);
        //return view('user.user_add_form', compact(['users', 'tournementid']));
    }

    public function tournementuserstore(Request $request, $id): RedirectResponse
    {
        $tournement = Tournement::find($id);
        $tournement->users()->attach($request->inputUsers);
        return redirect()->back();
    }

    private function setupTournement($tournement)
    {
        if ($tournement->pitches_nmbr == 1 && $tournement->poules_nmbr == 1)
        {
            $this->setupSimpleTournement($tournement);
        }
        else
        {
            $poulesnmbrteamsnmbrgames = array();
            $nmbrteamsperpoule = floor($tournement->teams_nmbr / $tournement->poules_nmbr);
            $nmbrpoules1teammore = fmod($tournement->teams_nmbr, $tournement->poules_nmbr);
            $i = 0;
            while($i < $nmbrpoules1teammore)
            {
                $nmbrteamsinpoule = $nmbrteamsperpoule + 1;
                $nmbrgamesinpoule = ($nmbrteamsinpoule * ($nmbrteamsinpoule - 1)) / 2;
                if($tournement->is_entire_comp == 1)
                    $nmbrgamesinpoule += $nmbrgamesinpoule;
                $poulesnmbrteamsnmbrgames[$i] = [self::POULENAMES[$i], $nmbrteamsinpoule, $nmbrgamesinpoule];
                $i++;
            }
            while($i < $tournement->poules_nmbr)
            {
                $nmbrgamesinpoule = ($nmbrteamsperpoule * ($nmbrteamsperpoule - 1)) / 2;
                if($tournement->is_entire_comp == 1)
                    $nmbrgamesinpoule += $nmbrgamesinpoule;
                $poulesnmbrteamsnmbrgames[$i] = [self::POULENAMES[$i], $nmbrteamsperpoule, $nmbrgamesinpoule];
                $i++;
            }
            $this->setupComplexTournement($tournement , $poulesnmbrteamsnmbrgames);
        }
    }

    private function setupSimpleTournement($tournement)
    {
        //attach 1 pitch
        $pitch = Pitch::create([
            'tournement_id' => $tournement->id,
            'pitch_name' => '1'
        ]);
        //attach 1 poule
        $poule = Poule::create([
            'tournement_id' => $tournement->id,
            'poule_name' => 'A'
        ]);
        //and attach the teams
        $teamnr = 1;
        while ($teamnr <= $tournement->teams_nmbr) {
            Team::create([
                'poule_id' => $poule->id,
                'team_nr' => $teamnr,
                'team_name' => 'Team '.$teamnr
            ]);
            $teamnr++;
        }
        //create the rounds
        $aantalronden = ($tournement->teams_nmbr * ($tournement->teams_nmbr - 1)) / 2;
        $j = 1;
        $start = $tournement->tournement_date;
        $end = date('Y-m-d H:i:s', strtotime($start . ' +' . $tournement->game_duration . ' minutes'));
        while ($j <= $aantalronden) {
            Round::create([
                'tournement_id' => $tournement->id,
                'round_nr' => $j,
                'start' => $start,
                'end' => $end
            ]);
            $start = date('Y-m-d H:i:s', strtotime($end . ' +' . $tournement->change_duration . ' minutes'));
            $end = date('Y-m-d H:i:s', strtotime($start . ' +' . $tournement->game_duration . ' minutes'));
            $j++;
        }
        //create the games en attach to rounds
        $teams = Team::where('poule_id', $poule->id)->get();
        $rounds = Round::where('tournement_id', $tournement->id)->get();
        if($tournement->teams_nmbr == 5)
            $wedstrschema = self::WEDSTRIJDSCHEMA5;
        elseif ($tournement->teams_nmbr == 6)
            $wedstrschema = self::WEDSTRIJDSCHEMA6;
        elseif ($tournement->teams_nmbr == 7)
            $wedstrschema = self::WEDSTRIJDSCHEMA7;
        elseif ($tournement->teams_nmbr == 8)
            $wedstrschema = self::WEDSTRIJDSCHEMA8;
        $k = 0;
        foreach($rounds as $round)
        {
            Game::create([
                'tournement_id' => $tournement->id,
                'round_id' => $round->id,
                'pitch_id' => $pitch->id,
                'hometeam_id' => $teams[$wedstrschema[$k][0]]->id,
                'awayteam_id' => $teams[$wedstrschema[$k][1]]->id,
                'poule_round' => $wedstrschema[$k][2]
            ]);
            $k++;
        };
    }


    private function setupComplexTournement($tournement, $paramarray)
    {
        //attach the pitches to the tournement
        $counter = 1;
        while($counter <= $tournement->pitches_nmbr)
        {
            Pitch::create([
                'tournement_id' => $tournement->id,
                'pitch_name' => $counter
            ]);
            $counter++;
        }
        //attach de poules to the tournement and attach teams to the poules
        $nmbrgamestotal = 0;
        $teamnr = 1;
        foreach ($paramarray as $pouledata)
        {
            $poule = Poule::create([
                'tournement_id' => $tournement->id,
                'poule_name' => $pouledata[0]
            ]);
            $i = 0;
            while ($i < $pouledata[1]) {
                Team::create([
                    'poule_id' => $poule->id,
                    'team_nr' => $teamnr,
                    'team_name' => 'Team '.$teamnr
                ]);
                $i++;
                $teamnr++;
            }
            $nmbrgamestotal += $pouledata[2];
        }
        //calculate de needed number of rounds
        $nmbrounds = $nmbrgamestotal / $tournement->pitches_nmbr;
        if(fmod($nmbrgamestotal, $tournement->pitches_nmbr) <> 0)
        {
            $nmbrounds = floor($nmbrgamestotal / $tournement->pitches_nmbr) + 1;
        }
        //attach the rounds to the tournement
        $j = 1;
        $start = $tournement->tournement_date;
        $end = date('Y-m-d H:i:s', strtotime($start . ' +' . $tournement->game_duration . ' minutes'));
        while ($j <= $nmbrounds) {
            Round::create([
                'tournement_id' => $tournement->id,
                'round_nr' => $j,
                'start' => $start,
                'end' => $end
            ]);
            $start = date('Y-m-d H:i:s', strtotime($end . ' +' . $tournement->change_duration . ' minutes'));
            $end = date('Y-m-d H:i:s', strtotime($start . ' +' . $tournement->game_duration . ' minutes'));
            $j++;
        }
        //attach the games to the tournement
        $this->makeGames($tournement->id, $tournement->is_entire_comp);
        //attach the rounds and pitches to the games
        $this->attachRoundsAndPitches($tournement->id);
        //return view('tournement.index2', compact(['tournement', 'paramarray']));
    }

    private function makeGames($id, $entirecomp)
    {
        $poules = Poule::where('tournement_id', $id)->orderBy('poule_name', 'asc')->get();
        foreach ($poules as $poule)
        {
            $teams = Team::where('poule_id', $poule->id)->orderBy('team_nr', 'asc')->get();
            $teamsCount = $teams->count();
            if($teamsCount == 5)
                $wedstrschema = self::WEDSTRIJDSCHEMA5;
            elseif ($teamsCount == 6)
                $wedstrschema = self::WEDSTRIJDSCHEMA6;
            elseif ($teamsCount == 7)
                $wedstrschema = self::WEDSTRIJDSCHEMA7;
            elseif ($teamsCount == 8)
                $wedstrschema = self::WEDSTRIJDSCHEMA8;
            $k = 0;
            while($k < count($wedstrschema))
            {
                Game::create([
                    'tournement_id' => $id,
                    'hometeam_id' => $teams[$wedstrschema[$k][0]]->id,
                    'awayteam_id' => $teams[$wedstrschema[$k][1]]->id,
                    'poule_round' => $wedstrschema[$k][2]
                ]);
                $k++;
            };
            if($entirecomp == 1)
            {
                $k--;
                $actuelepouleronde = $wedstrschema[$k][2];
                $k = 0;
                while($k < count($wedstrschema))
                {
                    Game::create([
                        'tournement_id' => $id,
                        'hometeam_id' => $teams[$wedstrschema[$k][1]]->id,
                        'awayteam_id' => $teams[$wedstrschema[$k][0]]->id,
                        'poule_round' => $actuelepouleronde + $wedstrschema[$k][2]
                    ]);
                    $k++;
                };
            }
        }
    }

    private function attachRoundsAndPitches($id)
    {
        $games = GAME::where('tournement_id', $id)->get()
            ->sortBy(function($query){return $query->hometeam->poule->poule_name;})
            ->sortBy('poule_round')
            ->all();
        $rounds = ROUND::where('tournement_id', $id)->orderBy('round_nr')->get();
        $pitches = PITCH::where('tournement_id', $id)->orderBy('pitch_name')->get();
        $k=0;
        $m=0;
        foreach($games as $game)
        {
            if($k == $pitches->count()) {
                $k = 0;
                $m++;
            }
            $game->update([
                'round_id' => $rounds[$m]->id,
                'pitch_id' => $pitches[$k]->id
            ]);
            $k++;
        }
    }
    public function destroy(Tournement $tournement)
    {
        $tournement->delete();
        return redirect()->route('home')
            ->withSuccess('Het toernooi is succesvol verwijderd.');
    }

    private function getTournementDates(int $tournementid): array
    {
        $dates = array();
        $rounds = Round::where('tournement_id', $tournementid)->orderBy('round_nr')->get();
        $vorigestart = "";
        foreach ($rounds as $round)
        {
            if(date('d', strtotime($round->start)) <> date('d', strtotime($vorigestart)))
            {
                array_push($dates, $round->start);
                $vorigestart = $round->start;
            }
        }
        return $dates;
    }

}
