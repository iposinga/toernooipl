<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Round extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournement_id',
        'round_nr',
        'finalround',
        'start',
        'end',
    ];
    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }
    public function finalgames(): HasMany
    {
        return $this->hasMany(Finalgame::class);
    }
    public function tournement(): BelongsTo
    {
        return $this->belongsTo(Tournement::class);
    }
    public static function makeRounds(Tournement $tournement)
    {
        $nmbrgamestotal = Poule::where('tournement_id', $tournement->id)
            ->sum('games_nmbr');
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
    }

    public static function updateRounds($tournementid, $roundnr, $roundstart, $gameduration, $changeduration)
    {
        $tournement = Tournement::find($tournementid);
        $tournement->update([
            'game_duration' => $gameduration,
            'change_duration' => $changeduration
        ]);
        $rounds = Round::where('tournement_id', $tournementid)
            ->where('round_nr', '>=', $roundnr)
            ->orderBy('round_nr', 'asc')
            ->get();
        $start = date('Y-m-d H:i:s', strtotime($roundstart));
        $end = date('Y-m-d H:i:s', strtotime($start . ' +' . $gameduration . ' minutes'));
        foreach ($rounds as $round)
        {
            $round->update([
                'start' => $start,
                'end' => $end
            ]);
            $start = date('Y-m-d H:i:s', strtotime($end . ' +' . $changeduration . ' minutes'));
            $end = date('Y-m-d H:i:s', strtotime($start . ' +' . $gameduration . ' minutes'));
        }
    }
}
