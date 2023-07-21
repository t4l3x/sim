<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScoringRule extends Model
{
    use HasFactory;
    protected $fillable = [
        'win_points',
        'draw_points',
        'loss_points',
        'goals_for_points',
        'goals_against_points',
        'goal_difference_points',
        'teams_id',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Teams::class);
    }
}
