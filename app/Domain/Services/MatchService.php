<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Helpers\GoalSimulator;
use App\Domain\Repositories\ILeagueTeamsRepository;
use App\Domain\Repositories\IMatchRepository;
use App\Domain\Repositories\IUnitOfWork;

class MatchService
{
    public function __construct(
        private readonly IUnitOfWork            $unitOfWork,
        private readonly IMatchRepository       $matchRepository,
        private readonly ILeagueTeamsRepository $leagueTeamsRepository,
        private readonly GoalSimulator          $goalSimulator,
        private readonly TeamsService           $teamsService,
        private readonly StatisticsUpdater      $statisticsUpdater
    )
    {
    }

    /**
     * @throws \Exception
     */

    public function playMatch(int $matchId): void
    {
        // Start a transaction
        $this->unitOfWork->beginTransaction();

        try {
            $match = $this->matchRepository->findById($matchId);

            if ($match['played']) {
                $this->resetMatchStatistics($match);
            }

            $this->simulateMatchResult($match);

            // If everything is okay, commit the changes.
            $this->unitOfWork->commit();
        } catch (\Exception $e) {
            // An error occurred; rollback the transaction
            $this->unitOfWork->rollback();

            // Rethrow the exception
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public function playWeek(int $leagueId, int $weekNumber): void
    {
        $matches = $this->matchRepository->findByWeek($leagueId, $weekNumber);

        foreach ($matches as $match) {
            $this->playMatch($match['id']);
        }
    }

    /**
     * @throws \Exception
     */
    public function playAll(int $leagueId): void
    {
        $totalWeeks = $this->matchRepository->getTotalWeeks($leagueId);

        for ($week = 1; $week <= $totalWeeks; $week++) {
            $this->playWeek($leagueId, $week);
        }
    }

    public function totalWeeks(int $leagueId): int
    {
        return  $this->matchRepository->getTotalWeeks($leagueId);
    }

    public function getMatchesForWeek(int $week, $league_id): array
    {
        return $this->matchRepository->findByWeekAndLeague($week, $league_id);
    }

    public function getAllMatches(int $leagueId): array
    {
        return $this->matchRepository->findByLeague($leagueId);
    }
    private function resetMatchStatistics(array $match): void
    {

        $homeTeamLeagueTeamsId = $this->findLeagueTeamsId($match['home_team_id'], $match['leagues_id']);
        $awayTeamLeagueTeamsId = $this->findLeagueTeamsId($match['away_team_id'], $match['leagues_id']);

        $this->statisticsUpdater->update($homeTeamLeagueTeamsId, $match['home_team_goals'], $match['away_team_goals'], false);
        $this->statisticsUpdater->update($awayTeamLeagueTeamsId, $match['away_team_goals'], $match['home_team_goals'], false);
    }

    private function findLeagueTeamsId(int $teamId, int $leagueId): ?int
    {
        $leagueTeam = $this->leagueTeamsRepository->findLeagueTeamsId($teamId, $leagueId);

        return $leagueTeam ? $leagueTeam->id : null;
    }

    private function simulateMatchResult(array $match): void
    {
        $homeTeamStrength = $this->teamsService->teamStrengthCalculator($match['home_team_id']);
        $awayTeamStrength = $this->teamsService->teamStrengthCalculator($match['away_team_id']);

        // Generate a match result based on team strengths
        $averageStrength = ($homeTeamStrength + $awayTeamStrength) / 2;
        $homeGoalProb = $averageStrength * 0.8;
        $awayGoalProb = $averageStrength * 0.6;
        $homeGoals = $this->goalSimulator->simulate($homeGoalProb);
        $awayGoals = $this->goalSimulator->simulate($awayGoalProb);

        $this->matchRepository->updateMatchResult($match['id'], [
            'homeGoals' => $homeGoals,
            'awayGoals' => $awayGoals,
            'played' => 1,
        ]);

        // Fetch the updated match data
        $updatedMatch = $this->matchRepository->findById($match['id']);

        // Update or create statistics for home team
        $this->statisticsUpdater->update($updatedMatch['home_team_id'], $updatedMatch['home_team_goals'], $updatedMatch['away_team_goals']);

        // Update or create statistics for away team
        $this->statisticsUpdater->update($updatedMatch['away_team_id'], $updatedMatch['away_team_goals'], $updatedMatch['home_team_goals']);


    }

    public function updateResult(int $matchId, int $homeGoals, int $awayGoals): void
    {
        $this->matchRepository->updateMatchResult($matchId, [
            'homeGoals' => $homeGoals,
            'awayGoals' => $awayGoals,
            'played' => 1,
        ]);

        $updatedMatch = $this->matchRepository->findById($matchId);

        $this->statisticsUpdater->update($updatedMatch['home_team_id'], $updatedMatch['home_team_goals'], $updatedMatch['away_team_goals']);

        // Update or create statistics for away team
        $this->statisticsUpdater->update($updatedMatch['away_team_id'], $updatedMatch['away_team_goals'], $updatedMatch['home_team_goals']);
    }

}
