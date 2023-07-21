<?php

namespace Database\Factories;

use App\Models\Leagues;
use App\Models\LeagueTeams;
use App\Models\Teams;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<LeagueTeams>
 */
class LeagueTeamsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $league = Leagues::inRandomOrder()->first();
        $team = Teams::inRandomOrder()->first();

        return [
            'leagues_id' => $league->id,
            'teams_id' => $team->id,
            'strength_percent' => $this->faker->numberBetween(80, 100),
        ];
    }
}
