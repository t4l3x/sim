<?php
declare(strict_types=1);

namespace App\Domain\Helpers;

class PredictionsSorter implements SortStrategyInterface
{
    public function sort(array $data): array
    {
        foreach ($data as &$weekPredictions) {
            usort($weekPredictions, function ($a, $b) {
                return $b['team_prediction'] - $a['team_prediction'];
            });

            $totalPredictions = array_sum(array_column($weekPredictions, 'team_prediction'));

            foreach ($weekPredictions as $index => $prediction) {
                $weekPredictions[$index]['team_prediction'] = round(($prediction['team_prediction'] / $totalPredictions) * 100);
            }
        }

        return $data;
    }
}
