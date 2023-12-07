<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Club extends Model
{
    use HasFactory;
    protected $fillable = [
        'tournement_id',
        'club_nr',
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
    public function homegames(): HasManyThrough
    {
        return $this->hasManyThrough(Game::class, Team::class, 'club_id', 'hometeam_id');
    }
    public function awaygames(): HasManyThrough
    {
        return $this->hasManyThrough(Game::class, Team::class, 'club_id', 'awayteam_id');
    }
    public function tournement(): BelongsTo
    {
        return $this->belongsTo(Tournement::class);
    }
    public static function makeClubs(int $tournement_id, int $clubs_nmbr ): void
    {
        $counter = 1;
        while($counter <= $clubs_nmbr)
        {
            Club::create([
                'tournement_id' => $tournement_id,
                'club_nr' => $counter,
                'club_name' => "Club ".$counter
            ]);
            $counter++;
        }
    }
    public function updatestand(int $tournement_id)
    {
        $clubs = Club::with('homegames', 'awaygames')
            ->where('tournement_id', $tournement_id)
            ->withSum('homegames','home_points')
            ->withSum('homegames','home_score')
            ->withSum('homegames','away_score')
            ->withSum('homegames','home_win')
            ->withSum('homegames','home_draw')
            ->withSum('homegames','home_loss')
            ->withSum('awaygames','away_points')
            ->withSum('awaygames','away_score')
            ->withSum('awaygames','home_score')
            ->withSum('awaygames','away_win')
            ->withSum('awaygames','away_draw')
            ->withSum('awaygames','away_loss')
            ->get();
        foreach($clubs as $club)
        {
            $club->update([
                'club_points' => $club->homegames_sum_home_points + $club->awaygames_sum_away_points,
                'club_played' => $club->homegames_sum_home_win + $club->awaygames_sum_away_win + $club->homegames_sum_home_draw + $club->awaygames_sum_away_draw + $club->homegames_sum_home_loss + $club->awaygames_sum_away_loss,
                'club_win' => $club->homegames_sum_home_win + $club->awaygames_sum_away_win,
                'club_draw' => $club->homegames_sum_home_draw + $club->awaygames_sum_away_draw,
                'club_loss' => $club->homegames_sum_home_loss + $club->awaygames_sum_away_loss,
                'club_goal' => $club->homegames_sum_home_score + $club->awaygames_sum_away_score,
                'club_goalagainst' => $club->homegames_sum_away_score + $club->awaygames_sum_home_score,
                'club_goaldifference' => $club->homegames_sum_home_score + $club->awaygames_sum_away_score - $club->homegames_sum_away_score - $club->awaygames_sum_home_score,
                'club_average' => ($club->homegames_sum_home_points + $club->awaygames_sum_away_points)/($club->homegames_sum_home_win + $club->awaygames_sum_away_win + $club->homegames_sum_home_draw + $club->awaygames_sum_away_draw + $club->homegames_sum_home_loss + $club->awaygames_sum_away_loss)
            ]);
        }
        /*$sortedclubs = $clubs
            ->sortByDesc('club_average')
            ->sortByDesc('club_goaldifference');
        sortBy sorteert alleen op de laatst genoemde
        */
        $sortedclubs = Club::where('tournement_id', $tournement_id)
            ->orderByDesc('club_average')
            ->orderByDesc('club_goaldifference')
            ->get();
        $ranking = 1;
        foreach($sortedclubs as $sortedclub)
        {
            $sortedclub->update(['club_ranking' => $ranking]);
            $ranking++;
        }
    }
    public static function stand(int $tournement_id)
    {
        $clubs = Club::where('tournement_id', $tournement_id)
            ->orderBy('club_ranking')
            ->get();
        return $clubs;
    }
}
