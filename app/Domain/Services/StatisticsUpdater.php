<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Repositories\IStatisticsRepository;

class StatisticsUpdater
{
    public function __construct(protected IStatisticsRepository $statisticsRepository)
    {
    }

    public function update(int $leagueTeamsId, int $goalsFor, int $goalsAgainst, bool $increment = true): void
    {
        $multiplier = $increment ? 1 : -1;

        $stats = [
            'played' => $multiplier * 1,
            'won' => $multiplier * ($goalsFor > $goalsAgainst ? 1 : 0),
            'draw' => $multiplier * ($goalsFor === $goalsAgainst ? 1 : 0),
            'lost' => $multiplier * ($goalsFor < $goalsAgainst ? 1 : 0),
            'goals_for' => $multiplier * $goalsFor,
            'goals_against' => $multiplier * $goalsAgainst,
            'goal_difference' => $multiplier * ($goalsFor - $goalsAgainst)
        ];

        $this->statisticsRepository->updateStatistics($leagueTeamsId, $stats);
    }
}
