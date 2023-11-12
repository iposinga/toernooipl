<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Pitch extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournement_id',
        'pitch_nr',
        'pitch_name',
        'pitch_spot'
    ];

    public function matches(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function tournement(): BelongsTo
    {
        return $this->belongsTo(Tournement::class);
    }

    public static function makePitches(int $tournement_id, int $pitches_nmbr )
    {
        $counter = 1;
        while($counter <= $pitches_nmbr)
        {
            Pitch::create([
                'tournement_id' => $tournement_id,
                'pitch_nr' => $counter,
                'pitch_name' => "veld ".$counter
            ]);
            $counter++;
        }
    }
    public function zoekPlekVeld(int $toernooiid, int $veldnr): string
    {
        $result = DB::select("SELECT pitch_spot FROM pitches WHERE tournement_id=? AND pitch_nr=?", [$toernooiid, $veldnr]);
        $returnstmt = $result[0]->pitch_spot;
        //dd($result);
        return $returnstmt;
    }
    public function telVeldenPlekVeld(int $toernooiid, int $veldnr)
    {
        $plekveld = $this->zoekPlekVeld($toernooiid, $veldnr);
        $result = DB::select("SELECT COUNT(*) AS aantal FROM pitches WHERE tournement_id=? AND pitch_spot=?", [$toernooiid, $plekveld]);
        $returnstmt = $result[0]->aantal;
        return $returnstmt;
        //dd($result);
    }
/*    public function aantalVelden(int $toernooiid)
    {
        $result = DB::select("SELECT COUNT(*) AS aantal FROM pitches WHERE tournement_id=?", [$toernooiid]);
        return $result[0]->aantal;
    }*/
}

