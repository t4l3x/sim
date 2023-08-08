<?php
declare(strict_types=1);

namespace App\Domain\Services;
use App\Domain\Helpers\SortStrategyInterface;
use App\Models\Leagues;

class PredictionService
{

    const HISTORICAL_PERFORMANCE_COEFFICIENT = 0.5;
    const PLAYER_HEALTH_COEFFICIENT = 0.3;
    const STRENGTH_COEFFICIENT = 0.2;

    const WIN_PERCENTAGE_COEFFICIENT = 0.40;
    const GOALS_SCORED_COEFFICIENT = 0.35;
    const GOALS_CONCEDED_COEFFICIENT = -0.2;

    const FINAL_WEIGHT_COEFFICIENT = 0.8;
    const ACTUAL_POINTS_WEIGHT_COEFFICIENT = 0.2;
    const TEAM_PERFORMANCE_WEIGHT = 0.5;
    const REGRESSION_SCORE_WEIGHT = 0.5;

    public function __construct(
        private readonly StandingsService      $standingsService,
        private readonly SortStrategyInterface $sortStrategy
    )
    {
    }

    public function getPredictions($league_id, $week): array
    {
        $standings = $this->standingsService->getStandingsWithPointsAndAttributes($league_id);
        $predictions = $this->generatePredictions($week, $standings);

        return [
            'predictions' => $predictions
        ];
    }

    private function generatePredictions($week, array $standings): array
    {
        $predictions = [];

        // Calculate total weeks in the league
        $totalWeeks = (count($standings) - 1) * 2;
        $remainingWeeks = $totalWeeks - $week;

        // Calculate the sum of actual points earned by all teams
        $totalPoints = array_reduce($standings, function ($acc, $team) {
            return $acc + $team['points'];
        }, 0);

        foreach ($standings as $team) {
            $teamName = $team['name'];

            // Calculate the team's performance based on actual statistics
            $teamPerformance = $this->calculateTeamPerformance($team);

            // Calculate the team's prediction based on regression score and performance
            $teamRegressionScore = $this->calculateRegressionScore($team);
            $maxPossiblePoints = $team['points'] + ($remainingWeeks * 3 * $teamRegressionScore);
            $weight = ($teamRegressionScore / 100) * ($maxPossiblePoints / ($totalWeeks * 3));

            // Adjust the weight based on the actual points earned by the team
            $actualPointsWeight = $team['points'] / $totalPoints;
            $finalWeight = $weight * self::FINAL_WEIGHT_COEFFICIENT + $actualPointsWeight * self::ACTUAL_POINTS_WEIGHT_COEFFICIENT;

            $teamPrediction = round(($finalWeight * self::TEAM_PERFORMANCE_WEIGHT + $teamPerformance * self::REGRESSION_SCORE_WEIGHT) * 100);

            $predictions[$week][] = [
                'team_name' => $teamName,
                'team_prediction' => $teamPrediction,
            ];
        }

        // Sort predictions array based on team predictions
        if (count($predictions) > 0) {
            $predictions = $this->sortStrategy->sort($predictions);
        }

        return $predictions;
    }

    private function calculateRegressionScore(array $team): float
    {
        $historicalPerformance = $team['historical_performance'];
        $playerHealth = $team['player_health'];
        $teamStrength = $team['strength'];

        return (self::HISTORICAL_PERFORMANCE_COEFFICIENT * $historicalPerformance)
            + (self::PLAYER_HEALTH_COEFFICIENT * $playerHealth)
            + (self::STRENGTH_COEFFICIENT * $teamStrength);
    }

    private function calculateTeamPerformance(array $team): float
    {
        $winPercentage = ($team['won'] + $team['draw']) / $team['played'];
        $goalsScored = $team['goals_for'];
        $goalsConceded = $team['goals_against'];

        return (self::WIN_PERCENTAGE_COEFFICIENT * $winPercentage)
            + (self::GOALS_SCORED_COEFFICIENT * $goalsScored)
            + (self::GOALS_CONCEDED_COEFFICIENT * $goalsConceded);
    }

}
