<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Repositories\ILeagueTeamsRepository;
use App\Domain\Repositories\IMatchRepository;
use App\Domain\Repositories\IStatisticsRepository;

class LeagueGeneratorService
{
    private array $teams = [];
    private array $schedule = [];

    public function __construct(
        protected ILeagueTeamsRepository $leagueTeamsRepository,
        protected IMatchRepository       $matchRepository,
        protected IStatisticsRepository  $statisticsRepository
    )
    {
    }

    public function resetMatches(int $leagueId): void
    {
        // Delete all existing matches for the league
        $this->matchRepository->deleteMatchesByLeague($leagueId);

        // Reset statistics for all teams in the league
        $this->removeStatistics($leagueId);

        // Regenerate the matches
        $this->generateMatches($leagueId);
    }

    private function removeStatistics(int $leagueId): void
    {
        $teams = $this->leagueTeamsRepository->getTeamsByLeagueId($leagueId);

        foreach ($teams as $team) {
            // Remove the statistics for the team using the league_teams_id
            $this->statisticsRepository->removeByLeagueTeamsId($team['id']);
        }
    }

    public function generateMatches(int $leagueId): void
    {
        // Check if matches have already been generated for the league
        if ($this->matchRepository->matchesExistForLeague($leagueId)) {
            return;
//            throw new \RuntimeException('Matches already generated for this league');
        }
        $this->teams = $this->leagueTeamsRepository->getTeamsByLeagueId($leagueId);
        $this->schedule = [];

        $this->ensureEvenNumberOfTeams();

        shuffle($this->teams);

        $numTeams = count($this->teams);
        $numRounds = ($numTeams - 1) * 2;

        for ($round = 0; $round < $numRounds; $round++) {
            $matches = $this->generateRoundMatches($round);
            $this->schedule[] = $matches;
            $this->rotateTeams();
        }

        $this->saveMatches($leagueId);
    }

    private function ensureEvenNumberOfTeams(): void
    {
        if (count($this->teams) % 2 !== 0) {
            $this->teams[] = null;
        }
    }

    private function generateRoundMatches(int $round): array
    {
        $matches = [];
        $numTeams = count($this->teams);
        $halfNumTeams = $numTeams / 2;

        for ($i = 0; $i < $halfNumTeams; $i++) {
            $homeTeam = $this->teams[$i];
            $awayTeam = $this->teams[$numTeams - 1 - $i];

            if ($round % 2 === 1) {
                list($homeTeam, $awayTeam) = array($awayTeam, $homeTeam);
            }

            $matches[] = [
                'home_team_id' => $homeTeam ? $homeTeam['teams_id'] : null,
                'away_team_id' => $awayTeam ? $awayTeam['teams_id'] : null,
            ];
        }

        return $matches;
    }

    private function rotateTeams(): void
    {
        $firstTeam = array_shift($this->teams);
        $this->teams[] = $firstTeam;
    }

    private function saveMatches(int $leagueId): void
    {
        foreach ($this->schedule as $week => $matches) {
            foreach ($matches as $match) {
                $this->matchRepository->save([
                    'leagues_id' => $leagueId,
                    'home_team_id' => $match['home_team_id'],
                    'away_team_id' => $match['away_team_id'],
                    'week' => $week + 1,
                ]);
            }
        }
    }
}
