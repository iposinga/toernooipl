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

    protected $fillable = [
        'tournement_name',
        'tournement_date',
        'teams_nmbr',
        'pitches_nmbr',
        'poules_nmbr',
        'match_duration',
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
}
