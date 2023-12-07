<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stadium extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournement_id',
        'stadium_nr',
        'stadium_name'
    ];
    public function tournement(): BelongsTo
    {
        return $this->belongsTo(Tournement::class);
    }
    public function pitches(): HasMany
    {
        return $this->hasMany(Pitch::class);
    }
    public static function makeStadiums(int $tournement_id, int $stadiums_nmbr )
    {
        $counter = 1;
        while($counter <= $stadiums_nmbr)
        {
            Stadium::create([
                'tournement_id' => $tournement_id,
                'stadium_nr' => $counter,
                'stadium_name' => "Plek ".$counter
            ]);
            $counter++;
        }
    }
}
