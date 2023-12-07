<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Znck\Eloquent\Relations\BelongsToThrough;

class Game extends Model
{
    use HasFactory;
    use \Znck\Eloquent\Traits\BelongsToThrough;
    const WEDSTRIJDSCHEMA4 = [[0, 1, 1], [2, 3, 1], [3, 0, 2], [1, 2, 2], [3, 1, 3], [2, 0, 3]];
    const WEDSTRIJDSCHEMA5 = [[0, 1, 1], [2, 3, 1], [4, 0, 2], [1, 2, 2], [3, 4, 3], [2, 0, 3], [1, 4, 4], [0, 3, 4], [4, 2, 5], [3, 1, 5]];
    const WEDSTRIJDSCHEMA6 = [[5, 0, 1], [1, 4, 1], [2, 3, 1], [0, 4, 2], [2, 5, 2], [3, 1, 2], [0, 2, 3], [3, 4, 3], [5, 1, 3],
        [3, 0, 4], [1, 2, 4], [4, 5, 4], [0, 1, 5], [5, 3, 5], [4, 2, 5]];
    const WEDSTRIJDSCHEMA7 = [[0, 1, 1], [3, 6, 1], [5, 4, 1], [6, 2, 2], [1, 5, 2], [4, 3, 2], [5, 0, 3], [2, 4, 3], [3, 1, 3],
        [4, 6, 4], [0, 3, 4], [1, 2, 4], [3, 5, 5], [2, 0, 5], [6, 1, 5], [5, 2, 6], [0, 6, 6], [1, 4, 6], [2, 3, 7], [6, 5, 7], [4, 0, 7]];
    const WEDSTRIJDSCHEMA8 = [[0, 1, 1], [2, 7, 1], [3, 6, 1], [5, 4, 1], [7, 0, 2], [6, 2, 2], [1, 5, 2], [4, 3, 2], [6, 7, 3], [5, 0, 3], [2, 4, 3], [3, 1, 3],
        [7, 5, 4], [4, 6, 4], [0, 3, 4], [1, 2, 4], [4, 7, 5], [3, 5, 5], [2, 0, 5], [6, 1, 5], [3, 7, 6], [5, 2, 6], [0, 6, 6], [1, 4, 6],
        [2, 3, 7], [6, 5, 7], [7, 1, 7], [4, 0, 7]];
    const WEDSTRIJDSCHEMA9 = [[1, 8, 1], [2, 7, 1], [3, 6, 1], [4, 5, 1], [6, 4, 2], [7, 3, 2], [8, 2, 2], [0, 1, 2], [2, 0, 3], [3, 8, 3], [4, 7, 3], [5, 6, 3],
        [7, 5, 4], [8, 4, 4], [0, 3, 4], [1, 2, 4], [3, 1, 5], [4, 0, 5], [5, 8, 5], [6, 7, 5], [8, 6, 6], [0, 5, 6], [1, 4, 6], [2, 3, 6],
        [4, 2, 7], [5, 1, 7], [6, 0, 7], [7, 8, 7], [0, 7, 8], [1, 6, 8], [2, 5, 8], [3, 4, 8], [5, 3, 9], [6, 2, 9], [7, 1, 9], [8, 0, 9]];

    protected $fillable = [
        'tournement_id',
        'round_id',
        'pitch_id',
        'hometeam_id',
        'awayteam_id',
        'home_score',
        'home_points',
        'home_win',
        'home_draw',
        'home_loss',
        'away_score',
        'away_points',
        'away_win',
        'away_draw',
        'away_loss',
        'poule_round'
    ];

    public function tournement(): BelongsTo
    {
        return $this->belongsTo(Tournement::class);
    }
    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function pitch(): BelongsTo
    {
        return $this->belongsTo(Pitch::class);
    }

    public function hometeam(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function awayteam(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /*public function poule(): BelongsToThrough
    {
        return $this->belongsToThrough(Poule::class, Team::class, 'hometeam_id', '', [Team::class => 'id']);
    }*/
    //koppel de bijbehorende poule via de teams
   public function poule(): HasOneThrough
    {
        return $this->hasOneThrough(Poule::class, Team::class, 'id','id', 'hometeam_id', 'poule_id');
    }

    //koppel het bijbehorende toernooi via de speelronde
    /*public function tournement(): HasOneThrough
    {
        return $this->hasOneThrough(Tournement::class, Round::class);
    }*/
    public static function makeGames($tournement_id, $entirecomp)
    {
        $poules = Poule::where('tournement_id', $tournement_id)->orderBy('poule_name', 'asc')->get();
        foreach ($poules as $poule)
        {
            $teams = Team::where('poule_id', $poule->id)->orderBy('team_nr', 'asc')->get();
            $teamsCount = $teams->count();
            if($teamsCount == 4)
                $wedstrschema = self::WEDSTRIJDSCHEMA4;
            elseif($teamsCount == 5)
                $wedstrschema = self::WEDSTRIJDSCHEMA5;
            elseif ($teamsCount == 6)
                $wedstrschema = self::WEDSTRIJDSCHEMA6;
            elseif ($teamsCount == 7)
                $wedstrschema = self::WEDSTRIJDSCHEMA7;
            elseif ($teamsCount == 8)
                $wedstrschema = self::WEDSTRIJDSCHEMA8;
            elseif ($teamsCount == 9)
                $wedstrschema = self::WEDSTRIJDSCHEMA9;
            $k = 0;
            while($k < count($wedstrschema))
            {
                Game::create([
                    'tournement_id' => $tournement_id,
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
                        'tournement_id' => $tournement_id,
                        'hometeam_id' => $teams[$wedstrschema[$k][1]]->id,
                        'awayteam_id' => $teams[$wedstrschema[$k][0]]->id,
                        'poule_round' => $actuelepouleronde + $wedstrschema[$k][2]
                    ]);
                    $k++;
                };
            }
        }
    }
    public static function attachRoundsAndPitchesToGames($id)
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

}
