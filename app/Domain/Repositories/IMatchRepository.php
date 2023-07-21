<?php
declare(strict_types=1);

namespace App\Domain\Repositories;

interface IMatchRepository extends IRepository
{
    public function updateMatchResult(int $matchId, array $result): void;
    public function findByWeek(int $leagueId, int $weekNumber): array;
    public function getTotalWeeks(int $leagueId): int;
    public function matchesExistForLeague(int $leagueId): bool;


}
