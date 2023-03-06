<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poule extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournement_id',
        'poule_name',
    ];

    public function tournement(): BelongsTo
    {
        return $this->belongsTo(Tournement::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
}
