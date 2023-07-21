<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teams extends Model
{
    use HasFactory;

    public function leagues(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\League', 'league_teams');
    }

    public function homeMatches(): HasMany
    {
        return $this->hasMany('App\Models\Matches', 'home_team_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany('App\Models\Matches', 'away_team_id');
    }

    public function leagueTeams(): HasMany
    {
        return $this->hasMany('App\Models\LeagueTeam');
    }
}
