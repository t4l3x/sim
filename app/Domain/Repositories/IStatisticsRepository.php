<?php
declare(strict_types=1);

namespace App\Domain\Repositories;

interface IStatisticsRepository extends IRepository
{
    public function updateStatistics(int $leagueTeamsId, array $stats): void;

    public function findByLeagueTeamsId($id);

}
