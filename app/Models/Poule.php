<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Poule extends Model
{
    use HasFactory;

    const POULENAMES = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];

    protected $fillable = [
        'tournement_id',
        'poule_name',
        'teams_nmbr',
        'games_nmbr'
    ];

    public function tournement(): BelongsTo
    {
        return $this->belongsTo(Tournement::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function games(): HasManyThrough
    {
        return $this->hasManyThrough(Game::class, Team::class, 'poule_id','hometeam_id','id','id');
    }

    public static function makePoules(int $tournement_id, int $poules_nmbr, int $teams_nmbr, int $is_entire_comp)
    {
        $nmbrteamsperpoule = floor($teams_nmbr / $poules_nmbr);
        $nmbrpoules1teammore = fmod($teams_nmbr, $poules_nmbr);
        //de eerste poules krijgen evt een team meer
        $i = 0;
        while($i < $nmbrpoules1teammore)
        {
            $nmbrteamsinpoule = $nmbrteamsperpoule + 1;
            $nmbrgamesinpoule = ($nmbrteamsinpoule * ($nmbrteamsinpoule - 1)) / 2;
            if($is_entire_comp == 1)
                $nmbrgamesinpoule += $nmbrgamesinpoule;
            Poule::create([
                'tournement_id' => $tournement_id,
                'poule_name' => self::POULENAMES[$i],
                'teams_nmbr' => $nmbrteamsinpoule,
                'games_nmbr' => $nmbrgamesinpoule
            ]);
            $i++;
        }
        //de overige poules krijgen evt. een team minder
        while($i < $poules_nmbr)
        {
            $nmbrgamesinpoule = ($nmbrteamsperpoule * ($nmbrteamsperpoule - 1)) / 2;
            if($is_entire_comp == 1)
                $nmbrgamesinpoule += $nmbrgamesinpoule;
            Poule::create([
                'tournement_id' => $tournement_id,
                'poule_name' => self::POULENAMES[$i],
                'teams_nmbr' => $nmbrteamsperpoule,
                'games_nmbr' => $nmbrgamesinpoule
            ]);
            $i++;
        }
    }
}
