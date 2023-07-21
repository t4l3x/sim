<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Leagues extends Model
{
    use HasFactory;

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Teams', 'league_teams');
    }

    public function matches(): HasMany
    {
        return $this->hasMany('App\Models\Matches');
    }

    public function leagueTeams(): HasMany
    {
        return $this->hasMany('App\Models\LeagueTeam');
    }

}
