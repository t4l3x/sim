<?php
declare(strict_types=1);

namespace App\Domain\Helpers;

class GoalSimulator
{
    public function simulate(float $probability): int
    {
        $randomValue = rand(1, 100);

        // Divide the range into 10 segments, each segment corresponding to a number of goals
        $goals = (int)($randomValue / 10);

        // If the random value is within the range of the probability, return the number of goals.
        // Otherwise, return 0
        return $randomValue <= $probability ? $goals : 0;
    }
}
