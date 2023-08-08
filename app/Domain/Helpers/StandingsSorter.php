<?php
declare(strict_types=1);

namespace App\Domain\Helpers;

class StandingsSorter implements SortStrategyInterface
{
    public function sort(array $data): array
    {
        usort($data, function ($a, $b) {
            if ($a['points'] === $b['points']) {
                return $b['goal_difference'] - $a['goal_difference'];
            }
            return $b['points'] - $a['points'];
        });

        return $data;
    }
}
