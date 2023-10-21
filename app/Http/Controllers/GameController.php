<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;
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
    }
}
