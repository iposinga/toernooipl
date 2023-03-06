<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pitch extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournement_id',
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
}
