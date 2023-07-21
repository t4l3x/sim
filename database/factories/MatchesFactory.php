<?php

namespace Database\Factories;

use App\Models\Leagues;
use App\Models\Matches;
use App\Models\Teams;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Matches>
 */
class MatchesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $league = Leagues::inRandomOrder()->first();
        $homeTeam = Teams::inRandomOrder()->first();
        $awayTeam = Teams::inRandomOrder()->where('id', '!=', $homeTeam->id)->first();


        return [
            'leagues_id' => $league->id,
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'week' => $this->faker->numberBetween(1, 38),
            'home_team_goals' => null,
            'away_team_goals' => null,
            'played_at' => null,
        ];
    }
}
