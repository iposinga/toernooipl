<?php

namespace App\Exports;

use App\Models\Finalgame;
use App\Models\Game;
use App\Models\Pitch;
use App\Models\Poule;
use App\Models\Round;
use App\Models\Tournement;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class GamesExport implements FromView
{
    use Exportable;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
    public function view(): View
    {
        $tournement = Tournement::find($this->id);
        $poules = Poule::with('teams')
            ->where('tournement_id', $this->id)
            ->orderBy('poule_name')
            ->get();
        $pitches = Pitch::where('tournement_id', $this->id)->get();
        $rounds = Round::find($this->id);
        $games = Game::with('round', 'pitch', 'hometeam', 'awayteam')
            ->where('tournement_id', $this->id)
            ->orderBy('round_id', 'asc')
            ->orderBy('pitch_id', 'asc')
            ->get();
        $finalrounds = Round::with([
            'finalgames',
            'finalgames.pitch'
        ])
            ->where('tournement_id', $this->id)
            ->where('finalround', '>', -1)
            ->get();
        $dates = Tournement::getTournementDates($this->id);
        return view('export.export', [
            'tournement' => $tournement,
            'poules' => $poules,
            'pitches' => $pitches,
            'rounds' => $rounds,
            'games' => $games,
            'finalrounds' => $finalrounds,
            'dates' => $dates
            ]);
    }
}
