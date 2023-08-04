<?php
declare(strict_types=1);

namespace App\Domain\Services;
class PredictionService
{

    public function __construct(
        private readonly StandingsService      $standingsService,
    )
    {
    }

    public function getPredictions($week): array
    {
        $standings = $this->standingsService->getStandingsWithPointsAndAttributes(1);
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
            $finalWeight = $weight * 0.8 + $actualPointsWeight * 0.2;

            $teamPrediction = round(($finalWeight * 0.5 + $teamPerformance * 0.5) * 100);

            $predictions[$week][] = [
                'team_name' => $teamName,
                'team_prediction' => $teamPrediction,
            ];
        }

        // Sort predictions array based on team predictions
        if (count($predictions) > 0) {
            usort($predictions[$week], function ($a, $b) {
                return $b['team_prediction'] - $a['team_prediction'];
            });

            // Normalize the predictions so that they sum up to 100
            $totalPredictions = array_sum(array_column($predictions[$week], 'team_prediction'));

            foreach ($predictions[$week] as $index => $prediction) {
                $predictions[$week][$index]['team_prediction'] = round(($prediction['team_prediction'] / $totalPredictions) * 100);
            }
        }

        return $predictions;
    }
    private function calculateRegressionScore(array $team): float|int
    {
        $historicalPerformance = $team['historical_performance'];
        $playerHealth = $team['player_health'];
        $teamStrength = $team['strength'];

        // These coefficients are determined by fitting the model to past data
        $coefficients = [
            'historical_performance' => 0.5,
            'player_health' => 0.3,
            'strength' => 0.2,
        ];

        return $coefficients['historical_performance'] * $historicalPerformance
            + $coefficients['player_health'] * $playerHealth
            + $coefficients['strength'] * $teamStrength;
    }

    private function calculateTeamPerformance(array $team): float
    {
        // You should implement the logic here to calculate the team's performance based on actual statistics
        // Example: You can calculate performance based on win percentage, goals scored, goals conceded, etc.
        // Adjust this calculation according to the importance of each statistic in your prediction.

        $winPercentage = ($team['won'] + $team['draw']) / $team['played'];
        $goalsScored = $team['goals_for'];
        $goalsConceded = $team['goals_against'];

        // Adjust the coefficients based on the importance of each statistic
        $coefficients = [
            'win_percentage' => 0.5,
            'goals_scored' => 0.3,
            'goals_conceded' => 0.2,
        ];

        return $coefficients['win_percentage'] * $winPercentage
            + $coefficients['goals_scored'] * $goalsScored
            - $coefficients['goals_conceded'] * $goalsConceded;
    }

}
