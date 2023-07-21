<?php

namespace Database\Seeders;

use App\Models\LeagueTeams;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeagueTeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i = 1; $i <= 4; $i++) {
            LeagueTeams::create([
                'leagues_id' => 1, // Assuming the Premier League is the first one we created
                'teams_id' => $i, // Looping through our 4 teams
            ]);
        }
    }
}
