<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Repositories\IAttributesRepository;

class PredictionService
{

    public function __construct(
        protected StandingsService      $standingsService,
        protected IAttributesRepository $attributeRepository
    )
    {

    }

    public function getPredictions($week): array
    {
        $standings = $this->standingsService->getStandingsWithPointsAndAttributes();
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

        foreach ($standings as $team) {
            $teamName = $team['name'];
            $teamRegressionScore = $this->calculateRegressionScore($team);

            // Consider the remaining matches, current position in the league standings and regression score
            $maxPossiblePoints = $team['points'] + ($remainingWeeks * 3 * $teamRegressionScore);

            // Weight is defined by team strength and max possible points
            $weight = ($teamRegressionScore / 100) * ($maxPossiblePoints / ($totalWeeks * 3));

            // Weighting adjustment to represent the chances for being champion
            $teamPrediction = round($weight * 100);

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

}
