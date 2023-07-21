<?php

namespace Database\Seeders;

use App\Models\Matches;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MatchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Matches::factory()
            ->count(10)
            ->create();
    }
}
