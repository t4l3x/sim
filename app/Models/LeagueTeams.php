<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeagueTeams extends Model
{
    use HasFactory;

    public function league(): BelongsTo
    {
        return $this->belongsTo('App\Models\League');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Teams::class, 'teams_id');
    }

    public function statistics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Statistic');
    }
}
