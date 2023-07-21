<?php
declare(strict_types=1);

namespace App\Domain\Repositories;

interface ITeamsRepository extends IRepository
{

    public function getTeamsByLeagueId(mixed $id);
}
