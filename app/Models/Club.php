<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Club extends Model
{
    use HasFactory;
    protected $fillable = [
        'tournement_id',
        'club_name',
        'club_played',
        'club_points',
        'club_win',
        'club_draw',
        'club_loss',
        'club_goalagainst',
        'club_goal',
        'club_goaldifference',
        'club_ranking',
        'club_average'
    ];
    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
    public function tournement(): BelongsTo
    {
        return $this->belongsTo(Tournement::class);
    }
}
