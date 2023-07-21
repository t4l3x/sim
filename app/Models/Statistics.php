<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Statistics extends Model
{
    use HasFactory;

    protected $fillable = ['league_teams_id', 'statistic_name', 'statistic_value'];


    public function leagueTeam(): BelongsTo
    {
        return $this->belongsTo('App\Models\LeagueTeams');
    }
}
