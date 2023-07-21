<?php

namespace Database\Seeders;

use App\Models\ScoringRule;
use App\Models\Statistics;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatisticsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Statistics::factory()
            ->count(10)
            ->create();
    }
}
