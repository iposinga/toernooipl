<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;

class Tournement extends Model
{
    use HasFactory;

/*    const WEDSTRIJDSCHEMA5 = [[0, 1, 1], [2, 3, 1], [4, 0, 2], [1, 2, 2], [3, 4, 3], [2, 0, 3], [1, 4, 4], [0, 3, 4], [4, 2, 5], [3, 1, 5]];
    const WEDSTRIJDSCHEMA6 = [[5, 0, 1], [1, 4, 1], [2, 3, 1], [0, 4, 2], [2, 5, 2], [3, 1, 2], [0, 2, 3], [3, 4, 3], [5, 1, 3],
        [3, 0, 4], [1, 2, 4], [4, 5, 4], [0, 1, 5], [5, 3, 5], [4, 2, 5]];
    const WEDSTRIJDSCHEMA7 = [[0, 1, 1], [3, 6, 1], [5, 4, 1], [6, 2, 2], [1, 5, 2], [4, 3, 2], [5, 0, 3], [2, 4, 3], [3, 1, 3],
        [4, 6, 4], [0, 3, 4], [1, 2, 4], [3, 5, 5], [2, 0, 5], [6, 1, 5], [5, 2, 6], [0, 6, 6], [1, 4, 6], [2, 3, 7], [6, 5, 7], [4, 0, 7]];
    const WEDSTRIJDSCHEMA8 = [[0, 1, 1], [2, 7, 1], [3, 6, 1], [5, 4, 1], [7, 0, 2], [6, 2, 2], [1, 5, 2], [4, 3, 2], [6, 7, 3], [5, 0, 3], [2, 4, 3], [3, 1, 3],
        [7, 5, 4], [4, 6, 4], [0, 3, 4], [1, 2, 4], [4, 7, 5], [3, 5, 5], [2, 0, 5], [6, 1, 5], [3, 7, 6], [5, 2, 6], [0, 6, 6], [1, 4, 6],
        [2, 3, 7], [6, 5, 7], [7, 1, 7], [4, 0, 7]];*/

/*    const POULENAMES = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];*/

    protected $fillable = [
        'tournement_name',
        'tournement_date',
        'teams_nmbr',
        'pitches_nmbr',
        'poules_nmbr',
        'game_duration',
        'change_duration',
        'is_entire_comp',
        'is_public',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot('is_admin');
    }

    public function rounds(): HasMany
    {
        return $this->hasMany(Round::class);
    }

    public function poules(): HasMany
    {
        return $this->hasMany(Poule::class);
    }

    public function pitches(): HasMany
    {
        return $this->hasMany(Pitch::class);
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }
    /*public function games(): HasManyThrough
    {
        return $this->hasManyThrough(Game::class, Round::class);
    }*/

    public function teams(): HasManyThrough
    {
        return $this->hasManyThrough(Team::class, Poule::class);
    }

    public static function setupTournement(Tournement $tournement)
    {
        Pitch::makePitches($tournement->id, $tournement->pitches_nmbr);
        Poule::makePoules($tournement->id, $tournement->poules_nmbr, $tournement->teams_nmbr, $tournement->is_entire_comp);
        Team::makeTeams($tournement->id);
        Round::makeRounds($tournement);
        //attach the games to the tournement
        Game::makeGames($tournement->id, $tournement->is_entire_comp);
        //attach the rounds and pitches to the games
        Game::attachRoundsAndPitchesToGames($tournement->id);
    }
    public static function getTournementDates(int $tournement_id): array
    {
        $dates = array();
        $rounds = Round::where('tournement_id', $tournement_id)->orderBy('round_nr')->get();
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
