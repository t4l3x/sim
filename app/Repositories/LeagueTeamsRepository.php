<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Domain\Repositories\ILeaguesRepository;
use App\Domain\Repositories\ILeagueTeamsRepository;
use App\Models\LeagueTeams;
use Illuminate\Support\Collection;

class LeagueTeamsRepository implements ILeagueTeamsRepository
{
    public function __construct(protected LeagueTeams $leagueTeams)
    {
    }

    public function findById(int $id): array
    {
        $leagueTeam = $this->leagueTeams->find($id);
        return $leagueTeam ? $leagueTeam->toArray() : [];
    }

    public function findAll(): array
    {
        return $this->leagueTeams->all()->toArray();
    }

    public function save($data): bool
    {
        $leagueTeam = new $this->leagueTeams;
        $leagueTeam->fill($data);
        return $leagueTeam->save();
    }

    public function delete(int $id): bool
    {
        $leagueTeam = $this->leagueTeams->find($id);
        return $leagueTeam ? $leagueTeam->delete() : false;
    }

    public function getTeamsByLeagueId(int $leagueId)
    {
        $teams = $this->leagueTeams->with('team')
            ->where('leagues_id', $leagueId)
            ->get();

        return $teams->toArray();
    }

    public function findLeagueTeamsId(int $teamId, int $leagueId)
    {
        return $this->leagueTeams->where('leagues_id', $leagueId)
            ->where('teams_id', $teamId)
            ->first();
    }
}
