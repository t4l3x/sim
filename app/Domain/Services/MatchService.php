<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Helpers\GoalSimulator;
use App\Domain\Repositories\IMatchRepository;
use App\Domain\Repositories\IUnitOfWork;

class MatchService
{
    public function __construct(
        protected IUnitOfWork $unitOfWork,
        protected IMatchRepository       $matchRepository,
        protected GoalSimulator          $goalSimulator,
        protected TeamsService $teamsService,
        protected StatisticsUpdater      $statisticsUpdater
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

            // Check if match has been played before
            if ($match['played']) {

                $oldHomeGoals = $match['home_team_goals'];
                $oldAwayGoals = $match['away_team_goals'];

                // Remove old statistics for home and away team
                $this->statisticsUpdater->update($match['home_team_id'], $oldHomeGoals, $oldAwayGoals, false);
                $this->statisticsUpdater->update($match['away_team_id'], $oldAwayGoals, $oldHomeGoals, false);
            }

            $homeTeamStrength = $this->teamsService->teamStrengthCalculator($match['home_team_id']);
            $awayTeamStrength = $this->teamsService->teamStrengthCalculator($match['away_team_id']);

            // Generate a match result based on team strengths
            $averageStrength = ($homeTeamStrength + $awayTeamStrength) / 2;
            $homeGoalProb = $averageStrength * 0.8;
            $awayGoalProb = $averageStrength * 0.6;
            $homeGoals = $this->goalSimulator->simulate($homeGoalProb);
            $awayGoals = $this->goalSimulator->simulate($awayGoalProb);

            $this->matchRepository->updateMatchResult($matchId, [
                'homeGoals' => $homeGoals,
                'awayGoals' => $awayGoals,
                'played' => 1,
            ]);

            // Fetch the updated match data
            $match = $this->matchRepository->findById($matchId);

            // Update or create statistics for home team
            $this->statisticsUpdater->update($match['home_team_id'], $match['home_team_goals'], $match['away_team_goals']);

            // Update or create statistics for away team
            $this->statisticsUpdater->update($match['away_team_id'], $match['away_team_goals'], $match['home_team_goals']);

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

    public function getMatchesForWeek(int $week): array
    {
        return $this->matchRepository->getMatchesForWeek($week);
    }

}
