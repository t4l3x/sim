<?php

namespace Database\Factories;

use App\Models\LeagueTeams;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Statistic>
 */
class StatisticsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'league_teams_id' => LeagueTeams::factory(),
            'statistic_name' => $this->faker->randomElement(['wins', 'losses', 'draws']),
            'statistic_value' => $this->faker->numberBetween(0, 5)
        ];
    }
}
