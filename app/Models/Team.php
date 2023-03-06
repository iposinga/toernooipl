<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'poule_id',
        'team_nr',
        'team_name',
        'team_class',
    ];

    public function homegames(): HasMany
    {
        return $this->hasMany(Game::class, 'hometeam_id', 'id');
    }

    public function awaygames(): HasMany
    {
        return $this->hasMany(Game::class, 'awayteam_id', 'id');
    }

    public function games()
    {
        return $this->homegames()->union($this->awaygames());
    }

    public function poule(): BelongsTo
    {
        return $this->belongsTo(Poule::class);
    }
    //koppel het bijbehorende toernooi via de poule
    public function tournement(): HasOneThrough
    {
        return $this->hasOneThrough(Tournement::class,Poule::class);
    }
}
