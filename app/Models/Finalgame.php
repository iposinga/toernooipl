<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Finalgame extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournement_id',
        'round_id',
        'pitch_id',
        'type_id',
        'name',
        'homepoule_id',
        'awaypoule_id',
        'homepoule_teamsnmbr',
        'awaypoule_teamsnmbr',
        'home_ranking',
        'away_ranking',
        'hometeam_id',
        'awayteam_id',
        'home_score',
        'away_score'
    ];

    public function tournement(): BelongsTo
    {
        return $this->belongsTo(Tournement::class);
    }
    public function homepoule(): BelongsTo
    {
        return $this->belongsTo(Poule::class);
    }
    public function awaypoule(): BelongsTo
    {
        return $this->belongsTo(Poule::class);
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

    public function homeparent() {
        return $this->belongsTo(static::class, 'home_winnergame_id');
    }

    public function awayparent() {
        return $this->belongsTo(static::class, 'away_winnergame_id');
    }

    public static function makeFinalgame($tournement_id, $round_id)
    {
        $pitches = Pitch::where('tournement_id', $tournement_id)->get();
        foreach($pitches as $pitch)
        {
            Finalgame::create([
                'tournement_id' => $tournement_id,
                'round_id' => $round_id,
                'pitch_id' => $pitch->id
            ]);
        }
    }
}
