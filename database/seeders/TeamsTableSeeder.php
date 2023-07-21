<?php

namespace Database\Seeders;

use App\Models\Teams;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $teamNames = ['Liverpool', 'Manchester City', 'Chelsea', 'Manchester United'];

        foreach ($teamNames as $teamName) {
            Teams::create(['name' => $teamName]);
        }

    }
}
