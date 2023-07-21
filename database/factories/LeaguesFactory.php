<?php

namespace Database\Factories;

use App\Models\ScoringRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Leagues>
 */
class LeaguesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $leagues = ['Premier League', 'La Liga', 'Serie A', 'Bundesliga', 'Ligue 1', 'Eredivisie', 'Primeira Liga', 'Super Lig'];

        return [
            'name' => $this->faker->unique()->randomElement($leagues),
            'scoring_rule_id' => ScoringRule::factory(),
        ];
    }
}
