<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Game extends Model
{
    use HasFactory;

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

    //koppel de bijbehorende poule via de teams
    public function poule(): HasOneThrough
    {
        return $this->hasOneThrough(Poule::class, Team::class);
    }

    //koppel het bijbehorende toernooi via de speelronde
    /*public function tournement(): HasOneThrough
    {
        return $this->hasOneThrough(Tournement::class, Round::class);
    }*/
}
