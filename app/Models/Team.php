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
        'played',
        'points',
        'win',
        'draw',
        'loss',
        'goalagainst',
        'goal',
        'goaldifference',
        'team_ranking'
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
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
    //koppel het bijbehorende toernooi via de poule
    public function tournement(): HasOneThrough
    {
        return $this->hasOneThrough(Tournement::class,Poule::class);
    }
    public static function makeTeams(int $tournement_id)
    {
        $poules= Poule::where('tournement_id', $tournement_id)
        ->orderBy('poule_name')
        ->get();
        $teamnr = 1;
        foreach ($poules as $poule)
        {
            $i = 0;
            while ($i < $poule->teams_nmbr) {
                Team::create([
                    'poule_id' => $poule->id,
                    'team_nr' => $teamnr,
                    'team_name' => 'Team '.$teamnr
                ]);
                $i++;
                $teamnr++;
            }
        }
    }
    public function updatestand(int $poule_id)
    {
        $teams = Team::with('homegames', 'awaygames')
            ->where('poule_id', $poule_id)
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
        foreach($teams as $team)
        {
            $team->update([
                'points' => $team->homegames_sum_home_points + $team->awaygames_sum_away_points,
                'played' => $team->homegames_sum_home_win + $team->awaygames_sum_away_win + $team->homegames_sum_home_draw + $team->awaygames_sum_away_draw + $team->homegames_sum_home_loss + $team->awaygames_sum_away_loss,
                'win' => $team->homegames_sum_home_win + $team->awaygames_sum_away_win,
                'draw' => $team->homegames_sum_home_draw + $team->awaygames_sum_away_draw,
                'loss' => $team->homegames_sum_home_loss + $team->awaygames_sum_away_loss,
                'goal' => $team->homegames_sum_home_score + $team->awaygames_sum_away_score,
                'goalagainst' => $team->homegames_sum_away_score + $team->awaygames_sum_home_score,
                'goaldifference' => $team->homegames_sum_home_score + $team->awaygames_sum_away_score - $team->homegames_sum_away_score - $team->awaygames_sum_home_score
            ]);
        }
        $sortedteams = $teams
            ->sortByDesc('goaldifference')
            ->sortBy('played')
            ->sortByDesc('points');
        /*$sortedteams = Team::where('poule_id', $poule_id)
            ->orderBy('points', 'desc')
            ->orderBy('played')
            ->orderBy('goaldifference', 'desc')
            ->get();*/
        $ranking = 1;
        foreach($sortedteams as $sortedteam)
        {
            $sortedteam->update(['team_ranking' => $ranking]);
            $ranking++;
        }
    }

    public static function stand(int $poule_id)
    {
        $teams = Team::where('poule_id', $poule_id)
            ->orderBy('team_ranking')
            /*->orderBy('points', 'desc')
            ->orderBy('played')
            ->orderBy('goaldifference', 'desc')*/
            ->get();
        return $teams;
    }
}
