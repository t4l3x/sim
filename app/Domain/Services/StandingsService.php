<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Repositories\IAttributesRepository;
use App\Domain\Repositories\ILeagueTeamsRepository;
use App\Domain\Repositories\IStatisticsRepository;

class StandingsService
{

    private array $teams;
    public function __construct(
        private readonly ILeagueTeamsRepository $leagueTeamsRepository,
        private readonly IStatisticsRepository  $statisticsRepository,
        private readonly IAttributesRepository  $attributesRepository,
    )
    {

    }

    private function loadTeams($leagueId): void
    {
        if (empty($this->teams)) {
            $this->teams = $this->leagueTeamsRepository->getTeamsByLeagueId($leagueId);
        }
    }

    public function calculateLeagueStandings($leagueId): array
    {
        $this->loadTeams($leagueId);

        $standings = [];

        foreach ($this->teams as $team) {
            $statistics = $this->statisticsRepository->findByLeagueTeamsId($team['id']);

            if (!empty($statistics)) {
                $standings[] = [
                    'team_id' => $team['team']['id'],
                    'team_name' => $team['team']['name'],
                    'played' => $statistics['played'],
                    'won' => $statistics['won'],
                    'lost' => $statistics['lost'],
                    'draw' => $statistics['draw'],
                    'points' => $statistics['points'],
                    'goal_difference' => $statistics['goal_difference'],
                ];
            } else {
                // Handle the case where statistics are not found for the team
            }
        }

        // Sort the standings array based on points and goal difference
        usort($standings, function ($a, $b) {
            if ($a['points'] === $b['points']) {
                return $b['goal_difference'] - $a['goal_difference'];
            }
            return $b['points'] - $a['points'];
        });

        return $standings;
    }
    public function getStandingsWithPointsAndAttributes($leagueId): array
    {
        $this->loadTeams($leagueId); // Replace 1 with your league_id
        $standings = [];

        foreach ($this->teams as $team) {
            $statistics = $this->statisticsRepository->findByLeagueTeamsId($team['id']);

            if (!empty($statistics)) {
                // Calculate total points


                // Fetch attributes
                $attributes = $this->getAttributesForTeam($team['team']['id']);

                $standings[] = [
                    'name' => $team['team']['name'],
                    'played' => $statistics['played'],
                    'goals_for' => $statistics['goals_for'],
                    'goals_against' => $statistics['goals_against'],
                    'won' => $statistics['won'],
                    'lost' => $statistics['lost'],
                    'draw' => $statistics['draw'],
                    'points' => $statistics['points'],
                    'goal_difference' => $statistics['goal_difference'],
                    'historical_performance' => $attributes['historical_performance'] ?? 0,
                    'player_health' => $attributes['player_health'] ?? 0,
                    'strength' => $attributes['strength'] ?? 0,
                ];
            }
        }

        return $standings;
    }

    public function getAttributesForTeam($teamId): array
    {
        $attributesArray = $this->attributesRepository->findByEntity('Team', $teamId);
        return $this->transformAttributes($attributesArray);
    }

    private function transformAttributes(array $attributes): array
    {
        $transformed = [];

        foreach ($attributes as $attribute) {
            $transformed[$attribute['attribute_name']] = $attribute['attribute_value'];
        }

        return $transformed;
    }

}
