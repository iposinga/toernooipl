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
        'start',
        'end',
    ];

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function tournement(): BelongsTo
    {
        return $this->belongsTo(Tournement::class);
    }
}
