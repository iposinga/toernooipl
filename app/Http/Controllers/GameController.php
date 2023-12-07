<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;
use App\Models\Club;
use App\Models\Tournement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class GameController extends Controller
{
    public function update(Request $request)
    {
        //in de model Game zit een standaard functie 'update' die zonder meer met de gelijke namen van de velden in de model en het verzonden formulier uit de voeten kan
        Game::where('id', $request->input('id'))->update($request->except(['_token', 'poule_id']));
        //nu de stand-velden in de teams-tabel nog bijwerken
        $team = new Team;
        $team->updatestand($request->input('poule_id'));
        //als het een clubcompetitie is, de stand in de clubs-tabel nog bijwerken
        $game = Game::find($request->input('id'));
        $tournement = Tournement::find($game->tournement_id);
        if($tournement->is_clubcompetition > 0) {
            $club = new Club;
            $club->updatestand($tournement->id);
        };
    }
    public function swap_update(Request $request)
    {
        $game = Game::find($request->input('d_game_id'));
        $game->round_id = $request->input('m_round_id');
        $game->pitch_id = $request->input('m_pitch_id');
        $game->save();

        $game2 = Game::find($request->input('m_game_id'));
        $game2->round_id = $request->input('d_round_id');
        $game2->pitch_id = $request->input('d_pitch_id');
        $game2->save();

    }
    public function deletescore(Request $request)
    {
        Game::where('id', $request->input( 'id'))
            ->update([
            'home_score' => null,
            'home_points' => null,
            'home_win' => null,
            'home_draw' => null,
            'home_loss' => null,
            'away_score' => null,
            'away_points' => null,
            'away_win' => null,
            'away_draw' => null,
            'away_loss' => null
        ]);
        $team = new Team;
        $team->updatestand($request->input('poule_id'));
    }
}
