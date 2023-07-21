<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teams>
 */
class TeamsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $teams = ['Arsenal', 'Chelsea', 'Liverpool', 'Manchester City', 'Manchester United',
            'Tottenham Hotspur', 'Leicester City', 'West Ham United', 'Aston Villa',
            'Everton', 'Newcastle United', 'Leeds United', 'Southampton', 'Crystal Palace',
            'Wolverhampton Wanderers', 'Brighton & Hove Albion', 'Burnley', 'Fulham',
            'West Bromwich Albion', 'Sheffield United'];

        return [
            'name' => $this->faker->unique()->randomElement($teams),
        ];
    }
}
