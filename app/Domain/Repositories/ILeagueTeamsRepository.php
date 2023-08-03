<?php
declare(strict_types=1);

namespace App\Domain\Repositories;

interface ILeagueTeamsRepository extends IRepository
{
    public function getTeamsByLeagueId(int $leagueLeagueId);

    public function findLeagueTeamsId(int $teamId, int $leagueId);
}
